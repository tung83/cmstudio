<?php
class base{
    protected $db,$lang,$view,$title;
    protected function __construct($db,$id,$lang='vi',$table='menu'){
        $this->db=$db;
        $this->db->reset();
        $this->lang=$lang;
        $db->where('id',$id);
        $item=$db->getOne($table);
        switch($this->lang){
            case 'en':
                $this->view=$item['e_view'];
                $this->title=$item['e_title'];
                break;
            default:
                $this->view=$item['view'];
                $this->title=$item['title'];
                break;
        }
    }
    /*protected function breadcrumb($prefix='',$lev=1){
        $this->db->reset();
        $str.='
        <ul class="breadcrumb clearfix">
        	<li><a href="'.myWeb.'"><i class="fa fa-home"></i></a></li>
            <li><a href="'.myWeb.$this->view.'">'.$this->title.'</a></li>';
        if(isset($_GET['id'])){
            $this->db->where('id',intval($_GET['id']));
            $item=$this->db->getOne($prefix,'id,title');
            $str.='
            <li><a href="#">'.$item['title'].'</a></li>';
        }
        $str.='
        </ul>';
        return $str;
    }*/
}