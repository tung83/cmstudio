<?php
require_once 'ClientInfo.php';
class visitors extends ClientInfo
{
    /**
     * Usage
     *$vs=new visitors($db);
     *$vs->getOnlineVisitors();
     *$vs->getTodayVisitors();
     *$vs->getCounter();
     */

    /**
     * @var tinyint
     */
    private $interval = 300;  // 5 minutes interval 

    private $vs_id = 'unknow';

    public function __construct(MysqliDb $db){
        parent::__construct();
        $this->db = $db;
        $this->db->reset();
        $this->insertNewVisitor();
        $this->removeDeadVisitors();
    }
    /**
     * Function that inserts the visitors IP address into the database. 
     * If the IP address is a NULL value then assume its from the localhost.
     * Returns the row id of the inserted data from the database.
     *
     * @return integer $id
     */
    function insertNewVisitor()
    {
        $this->db->reset();
        if(!isset($_SESSION['visitor'])){
            $_SESSION['visitor'] = session_id();
            $this->vs_id = $_SESSION['visitor'];
            $insert=array(
                'dates'=>date('Y-m-d h:i:s'),
                'vs_ip'=>$this->getUserIp(),
                'vs_city'=>$this->getUserCity(),
                'vs_browser'=>$this->getBrowser(),
                'vs_os'=>$this->getOS(),
                'vs_id'=>$this->vs_id,
                'vs_flag'=>1
            );
            $this->db->insert('vs_detail',$insert);
            $query = 'UPDATE vs_counter SET hit_counter = hit_counter + 1';
            $this->db->rawQuery($query);
        }
        else {
            $this->vs_id = $_SESSION['visitor'];
            $item=$this->db->where('vs_id',$this->vs_id)->getOne('vs_detail');
            if($item==null||count($item)==0){
                //$insert
            }
        }
        return TRUE;
    }

    /**
     * Function that deletes all entries that older than the $interval value. 
     */
    private function removeDeadVisitors(){
        $this->db->reset();
        $query='UPDATE vs_detail
                SET vs_flag = 0
                WHERE
                    vs_id <> ?
                AND (
                    dates + INTERVAL '.$this->interval.' SECOND
                ) < NOW()
                AND vs_flag=1';
        $param  = array();
        array_push($param,$this->vs_id);
        $this->db->rawQuery($query,$param);
        //update present visitor
        $query='UPDATE vs_detail
                SET vs_flag = 1,
                 dates = now()
                WHERE
                    vs_id =?';
        $this->db->rawQuery($query,$param);
        //remove day visit
        $query='DELETE FROM vs_detail WHERE dates + INTERVAL 1 DAY<=now()';
        $this->db->rawQuery($query);
    }
    public function getOnlineVisitors(){
        $this->db->reset();
        $query='SELECT
                    count(*) as res
                FROM
                    vs_detail
                WHERE
                    vs_flag = 1';
        $tab = $this->db->rawQuery($query);
        return $tab[0]['res'];
    }
    public function getCounter(){
        $this->db->reset();
        $tab = $this->db->get('vs_counter');
        return $tab[0]['hit_counter'];
    }
    public function getTodayVisitors(){
        $this->db->reset();
        $query='SELECT
                    count(*) as res
                FROM
                    vs_detail
                WHERE
                    DAY(dates) = DAY(NOW())';
        $tab = $this->db->rawQuery($query);
        return $tab[0]['res'];
    }
}
?>