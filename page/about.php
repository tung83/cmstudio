<?php
common::load('base','page');
class about{
    private $db;
    private $lang;
    private $view;
    function __construct($db,$lang='vi'){
        $this->db=$db;
        $this->db->reset();
        $this->lang=$lang;
        $db->where('id',4);
        $item=$db->getOne('menu');
        if($lang=='en'){
            $this->title=$item['e_title'];
            $this->view=$item['e_view'];
        }else{
            $this->title=$item['title'];
            $this->view=$item['view'];
        }
    }
    function ind_about(){
        $this->db->where('active',1);
        $this->db->orderBy('id','ASC');
        $item=$this->db->getOne('about');
        $lnk=myWeb.$this->view.'/'.common::slug($item['title']).'-i'.$item['id'];
        $str='
        <div class="ind-about clearfix">
            <div>
            <img src="'.webPath.$item['img'].'" alt="" title=""/>
            <h2>'.$item['title'].'</h2>
            </div>
            '.nl2br(common::str_cut($item['sum'],220)).'
            <a href="'.$lnk.'">'.more.'</a>                
        </div>';
        return $str;
    }
    function breadcrumb(){
        $this->db->reset();
        $str.='
        <ul class="breadcrumb clearfix">
        	<li><a href="'.myWeb.'"><i class="fa fa-home"></i></a></li>
            <li><a href="'.myWeb.$this->lang.'/'.$this->view.'">'.$this->title.'</a></li>';
        if(isset($_GET['id'])){
            $this->db->where('id',intval($_GET['id']));
            $item=$this->db->getOne('about','id,title,e_title');
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
    function category($id){
        $this->db->reset();
        $this->db->where('active',1)->orderBy('id');
        $list=$this->db->get('about',null,'id,title');
        $str='
        <div class="row">
        <div class="col-md-12 about-cate">';
        foreach($list as $item){
            if($item['id']==$id) $cls=' class="active"';
            else $cls='';
            $lnk=myWeb.$this->view.'/'.common::slug($item['title']).'-i'.$item['id'];
            $str.='
            <a href="'.$lnk.'"'.$cls.'>
                <i class="fa fa-caret-right"></i>
                <span>'.$item['title'].'</span>
            </a>';
        }
        $str.='
        </div>
        </div>';
        return $str;
    }
    function about_all(){
        $page=isset($_GET['page'])?intval($_GET['page']):1;
        $this->db->where('active',1);
        $this->db->orderBy('id');
        $this->db->pageLimit=10;
        $list=$this->db->paginate('about',$page);
        $count=$this->db->totalCount;
        foreach($list as $item){
            $str.=$this->about_item($item);
        }
        
        $pg=new Pagination(array('limit'=>limit,'count'=>$count,'page'=>$page,'type'=>0));
        $pg->set_url(array('def'=>myWeb.$this->lang.'/'.$this->view,'url'=>myWeb.$this->lang.'/[p]/'.$this->view));

        $str.= '<div class="pagination-centered">'.$pg->process().'</div>';
        return $str;
    }
    function about_item($item){
        $lnk=myWeb.$this->lang.'/'.$this->view.'/'.common::slug($item['title']).'-i'.$item['id'];
        $str.='
        <a href="'.$lnk.'" class="about-item clearfix">
        <div class="col-xs-4">
            <img src="'.webPath.$item['img'].'" class="img-responsive" alt="" title=""/>
        </div>
        <div class="col-xs-8">
            <h2>'.$item['title'].'</h2>
            <span>'.nl2br(common::str_cut($item['sum'],620)).'</span>
        </div>
        </a>';
        return $str;
    }
    
    function about_one(){
        $id=intval($_GET['id']);
        $item=$this->db->where('id',$id)->getOne('about');
        $this->db->where('active',1)->where('id',$item['id'],'<>');
        $this->db->orderBy('id');
        $list=$this->db->get('about',3,'id,title,sum,img');
        $lnk=myWeb.$this->view.'/'.common::slug($item['title']).'-i'.$item['id'];
        $str.='     
        <div class="col-xs-12">   
            <article>
                <h1>'.$item['title'].'</h1>
                <p>'.$item['content'].'</p>
            </article>
        </div>
        <div class="col-xs-12">
        <h2 class="title-tag"><span><b>Bài Viết Liên Quan</b></span></h2>
        </div>';
        foreach($list as $item){
            $str.=$this->about_item($item);
        }
        return $str;
    }
}


?>
