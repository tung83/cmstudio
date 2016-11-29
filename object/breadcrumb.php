<?php
class breadcrumb{
    private $crumbs;
    function __construct(){  
         $this->crumbs=array();
    }
    function add($label, $url, $type=0){
        $crumb = array();
        $crumb['label'] = $label;
        $crumb['url'] = $url;
        $crumb['type']=$type;
        array_push($this->crumbs,$crumb);
        return $this;
    }
    function output(){
    
      var_dump($this->crumbs);
    }
    function bootstrap(){
        $str='
        <ol class="breadcrumb">';
        $i=1;
        foreach($this->crumbs as $item){
            if($i<count($this->crumbs)){
                $cls='';
            }else $cls=' class="active"';
            if($item['url']!='#'){
                $str.='<li'.$cls.'><a href="'.$item['url'].'">'.$item['label'].'</a></li>';    
            }else{
                $str.='<li'.$cls.'>'.$item['label'].'</li>';
            }            
            $i++;
        }
        $str.='
        </ol>
        ';
        return $str;
    }
}
?>
