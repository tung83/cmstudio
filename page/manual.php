<?php
class manual{
    private $db;
    private $lang,$title;
    private $view;
    function __construct($db,$lang='vi'){
        $this->db=$db;
        $this->db->reset();
        $this->lang=$lang;
        $db->where('id',5);
        $item=$db->getOne('menu');
        if($lang=='en'){
            $this->title=$item['e_title'];
            $this->view=$item['e_view'];
        }else{
            $this->title=$item['title'];
            $this->view=$item['view'];
        }
    }
    function breadcrumb(){
        $this->db->reset();
        $str.='
        <ul class="breadcrumb clearfix">
        	<li><a href="'.myWeb.'"><i class="fa fa-home"></i></a></li>
            <li><a href="'.myWeb.$this->lang.'/'.$this->view.'">'.$this->title.'</a></li>';
        if(isset($_GET['id'])){
            $this->db->where('id',intval($_GET['id']));
            $item=$this->db->getOne('manual','id,title,e_title');
            if($this->lang=='en'){
                $title=$item['e_title'];
            }else{
                $title=$item['title'];
            }
            $str.='
            <li><a href="#">'.$title.'</a></li>';
        }
        $str.='
        </ul>';
        return $str;
    }
    function video_cate(){              
        $this->db->reset();
        $page=isset($_GET['page'])?intval($_GET['page']):1;
        $this->db->where('active',1)->orderBy('id');
        $this->db->pageLimit=15;
        $list=$this->db->paginate('manual',$page);
        foreach($list as $item){
            $str.=$this->one_ind_video($item);
        }
        return $str;  
    }
    function one_ind_video($item){
        $lnk=myWeb.$this->lang.'/'.$this->view.'/'.common::slug($item['title']).'-i'.$item['id'];
        $str='
        <a href="'.$lnk.'" class="video-list">
        <div class="col-xs-12 col-md-4">            
            <img src="http://img.youtube.com/vi/'.$item['video'].'/0.jpg" alt="" title="'.$item['title'].'"/>
            <h2>'.$item['title'].'</h2>            
        </div>
        </a>';
        return $str;
    }
    function video_one($db,$id=0){
       
        $db->where('active',1)->where('id',$id)->orderBy('id');
        $item=$db->getOne('manual','id,video,pId');

        $str.='
        <div class="col-xs-12 col-md-8 col-md-offset-2" style="margin-bottom:40px">';
        $str.='
        <iframe width="100%" height="500" src="https://www.youtube.com/embed/'.$item['video'].'" frameborder="0" allowfullscreen></iframe>';
        $str.='
        </div>';
        $page=isset($_GET['page'])?intval($_GET['page']):1;
        $db->where('pId',$item['pId']);
        $db->where('id',$item['id'],'<>')->orderBy('id');
        $db->pageLimit=15;
        $list=$db->paginate('manual',$page);
        foreach($list as $item){
            $str.=$this->one_ind_video($item);
        }
        return $str;  
    }
    function ind_video($db){
        $db->where('active',1)->orderBy('id');
        $item=$db->getOne('manual','video,id');
        $str='
        <div class="col-md-4">
            <h3>'.video.'</h3>
            <hr class="hr_title" />
            <div>
                <iframe width="100%" height="250" src="https://www.youtube.com/embed/'.$item['video'].'" frameborder="0" allowfullscreen></iframe>
            </div>
            <div>
                <ul class="listNews">';
        $db->where('active',1)->where('id',$item['id'],'<>')->orderBy('id');
        $list=$db->get('manual',null,'id,title,e_title');
        foreach($list as $item){
            if($this->lang=='en'){
                $title=$item['e_title'];
            }else{
                $title=$item['title'];
            }
            $lnk=myWeb.$this->lang.'/'.$this->view.'/'.common::slug($title).'-i'.$item['id'].'.html';
            $str.='
            <li><a href="'.$lnk.'">'.$title.'</a></li>';
        }
        $str.='
                </ul>
            </div>
        </div>';
        return $str;
    }
}


?>