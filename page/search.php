<?php
class search{
    private $db,$lang,$hint,$stack,$field_search,$field_get;
    function __construct($db,$hint,$lang='vi'){
        $this->lang=$lang;
        $this->db=$db;
        $this->db->reset();
        $hint=explode('-',$hint);
        $hint=implode(' ',$hint);
        $this->hint=$hint;
        $this->stack=array();
        $this->field_search=array('title');
        $this->field_get=array('id,title');
    }
    function field_search($arr=null){
        foreach((array)$arr as $item){
            array_push($this->field_search,$item);
        }
        return $this;
    }
    function field_get($arr=null){
        foreach((array)$arr as $item){
            array_push($this->field_get,$item);
        }
        return $this;  
    }
    function add($table,$label,$view){
        $this->db->reset();
        //build or query
        foreach($this->field_search as $val){
            $tmp[]= $val.' like "%'.$this->hint.'%"';
        }
        $or_query=implode(' OR ',$tmp);
        //build or query
        $field_get=implode(',',$this->field_get);
        $sql='
        select '.$field_get.' from '.$table.' where active=1 and ( '.$or_query.' )';
        $list=$this->db->rawQuery($sql);
        if($this->db->count>0){
            $this->stack[]=array('label'=>$label,'view'=>$view,'list'=>$list);
        }        
        $this->field_search=array('title');
        $this->field_get=array('id','title');
        return $this;
    }
    function total(){
        $i=0;
        foreach($this->stack as $item){
            $i+=count($item['list']);
        }
        return $i;
    }
    function output(){
        $str='
        <div class="color">
        <div class="container paint-drop-blue bg-left clearfix">
        <p class="subtitle fancy"><span>Tìm Kiếm</span></p>
        <div class="search-content">
        <h1>Có '.$this->total().' kết quả với từ khoá <b style="color:#f00">"'.$this->hint.'"</b></h1>
        ';
        foreach($this->stack as $item){
            $str.='
            <h2><i class="fa fa-modx"></i> '.$item['label'].'</h2>
            <ul>';
            foreach($item['list'] as $sub_item){
                $lnk=myWeb.$item['view'].'/'.common::slug($sub_item['title']).'-i'.$sub_item['id'];
                $str.='
                <li><a href="'.$lnk.'">
                '.$sub_item['title'].'
                <em>'.$lnk.'</em>';
                if(isset($sub_item['sum'])){
                    $str.='
                    <span>'.common::str_cut($sub_item['sum'],200).'</span>';   
                }
                $str.='
                </a></li>';
            }
            $str.='
            </ul>';
        }
        $str.='
        </div>
        </div>
        </div>';
        return $str;
    }
}
?>