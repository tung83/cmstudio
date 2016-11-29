<?php
class cart{
    private $cart;
    public function __construct($db,$table='product'){
        $this->db=$db;
        $this->table=$table;
        if(!isset($_SESSION['cart'])) $_SESSION['cart']=array();
        $this->cart=$_SESSION['cart'];    
    }
    private function set_cart($res){
        $_SESSION['cart']=$this->cart=$res;
    }
    function cart_add($id,$qty){
        $k=0;
        $tmp=array();        
        foreach($this->cart as $item){
            if($item['id']==$id){
                $qty=$item['qty']+$qty;
                array_push($tmp,array('id'=>$item['id'],'qty'=>$qty));        
                $k++;  
            }else{
                array_push($tmp,array('id'=>$item['id'],'qty'=>$item['qty'])); 
            }
        }
        if($k==0){            
            array_push($tmp,array('id'=>$id,'qty'=>$qty));
        }
        $this->set_cart($tmp);
        return $this;
    }
    function cart_remove($id){
        $tmp=array();
        foreach($this->cart as $item){
            if($item['id']==$id){
                continue;
            }else{
                array_push($tmp,array('id'=>$item['id'],'qty'=>$item['qty']));
            }
        }    
        $this->set_cart($tmp);
        return $this;
    }
    function cart_empty(){
        unset($_SESSION['cart']);
        $this->cart=null;
    }
    function cart_update($id,$qty){
        $tmp=array();
        foreach($this->cart as $item){
            if($item['id']==$id){
                array_push($tmp,array('id'=>$item['id'],'qty'=>$qty));
            } else {
                array_push($tmp,array('id'=>$item['id'],'qty'=>$item['qty']));
            }
        }
        $this->set_cart($tmp);
        return $this;
    }
    function cart_update_multi($arr=array()){
        foreach($arr as $cart){
            $this->cart_update($cart['id'],$cart['qty']);
        }    
        return $this;
    }
    function cart_count(){
        $count=0;
        if(count($this->cart)>0){
            foreach($this->cart as $item){
                $count+=$item['qty'];
            }   
        }
        return $count;
    }
    function cart_output(){
        $str='';
        return $str;
    }
    function cart_insert(){
        
    }
    function cart_info(){
        
    }
}
?>