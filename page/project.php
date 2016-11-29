<?php
class project{
    private $db,$view,$lang;
    function __construct($db,$lang='vi'){
        $this->db=$db;
        $this->db->reset();
        $this->lang=$lang;
        $db->where('id',3);
        $item=$db->getOne('menu');
        if($lang=='en'){
            $this->view=$item['e_view'];
        }else{
            $this->view=$item['view'];
        }
    }
    function breadcrumb(){
        $this->db->reset();
        $str.='
        <ul class="breadcrumb clearfix">
        	<li><a href="'.myWeb.'"><i class="fa fa-home"></i></a></li>
            <li><a href="'.myWeb.$this->view.'">Dự Án Tiêu Biểu</a></li>';
        if(isset($_GET['id'])){
            $this->db->where('id',intval($_GET['id']));
            $item=$this->db->getOne('project','id,title,pId');
            $cate=$this->db->where('id',$item['pId'])->getOne('project_cate','id,title');
            $str.='
            <li><a href="'.myWeb.$this->view.'/'.common::slug($cate['title']).'-p'.$cate['id'].'">'.$cate['title'].'</a></li>
            <li><a href="#">'.$item['title'].'</a></li>';
        }elseif(isset($_GET['pId'])){
            $cate=$this->db->where('id',intval($_GET['pId']))->getOne('project_cate','id,title');
            $str.='
            <li><a href="#">'.$cate['title'].'</a></li>';
        }
        $str.='
        </ul>';
        return $str;
    }
    function category($pId){
        $this->db->reset();
        $this->db->where('active',1)->orderBy('ind','ASC')->orderBy('id');
        $list=$this->db->get('project_cate',null,'id,title');
        $str='
        <ul class="category">';
        foreach($list as $item){
            if($item['id']==$pId) $cls=' class="active"';else $cls='';
            $lnk=myWeb.$this->view.'/'.common::slug($item['title']).'-p'.$item['id'];
            $str.='
            <li><a'.$cls.' href="'.$lnk.'"><i class="fa fa-angle-double-right"></i> '.$item['title'].'</a></li>';
        }
        $str.='
        </ul>';
        return $str;
    }
    function project_item($item){
        $lnk=myWeb.$this->view.'/'.common::slug($item['title']).'-i'.$item['id'];
        $str.='
        <a href="'.$lnk.'" class="about_item">
            <div class="clearfix">
                <div class="left">
                <img src="'.webPath.$item['img'].'" alt="" title=""/>
                </div>
                <div class="right">
                <h2>'.$item['title'].'</h2>
                <span>'.nl2br(common::str_cut($item['sum'],620)).'</span>
                </div>
            </div>
        </a>';
        return $str;
    }
    function project_cate($pId){
        $page=isset($_GET['page'])?intval($_GET['page']):1;
        $this->db->reset();
        $this->db->where('active',1);
        if($pId!=0) $this->db->where('pId',$pId);
        $this->db->orderBy('id');
        $this->db->pageLimit=pr_pg;
        $list=$this->db->paginate('project',$page);
        $count=$this->db->totalCount;
        $str='
        <div class="color page-bg">
        <div class="container paint-drop-blue bg-right">
            '.$this->breadcrumb().'
            <h1 class="ind-title">Dự Án Tiêu Biểu</h1>
            <div class="project clearfix">
            <div class="the-left">
                '.$this->category($pId).'
            </div>
            <div class="the-right">';
        if($count>0){
            $str.='
            <ul class="project-list in-page clearfix">';
            foreach($list as $item){
                $str.='<li>'.$this->one_ind_project($item).'</li>';
            }
            $str.='
            </ul>';
        }      
        $pg = new Pagination();
        $pg->pagenumber = $page;
        $pg->pagesize = pr_pg;
        $pg->totalrecords = $count;
        $pg->showfirst = true;
        $pg->showlast = true;
        $pg->paginationcss = "pagination-large";
        $pg->paginationstyle = 1; // 1: advance, 0: normal
        if($pId==0){
            $pg->defaultUrl = myWeb.$this->view;
            $pg->paginationUrl = myWeb.'[p]/'.$this->view;    
        }else{
            $cate=$this->db->where('id',$pId)->getOne('project_cate','id,title');
            
            $pg->defaultUrl = myWeb.$this->view.'/'.common::slug($cate['title']).'-p'.$cate['id'];
            $pg->paginationUrl = myWeb.$this->view.'/[p]/'.common::slug($cate['title']).'-p'.$cate['id'];
        }     
        $str.= '<div class="pagination pagination-centered">'.$pg->process().'</div>';  
        $str.='
            </div>
        </div>
        </div>';
        return $str;
    }
    function project_one(){
        $id=intval($_GET['id']);
        $item=$this->db->where('id',$id)->getOne('project');
        $str='
        <div class="color page-bg">
        <div class="container paint-drop-blue bg-right">
            '.$this->breadcrumb().'
            <h1 class="ind-title">Dự Án Tiêu Biểu</h1>
            <div class="project clearfix">
            <div class="the-left">
                '.$this->category($item['pId']).'
            </div>
            <div class="the-right">';
        $str.='
        <article class="article">
            <h1 class="article">'.$item['title'].'</h1>
            <p>'.$item['content'].'</p>
        </article>';
        $page=isset($_GET['page'])?intval($_GET['page']):1;
        $this->db->where('active',1)->where('pId',$item['pId'])->where('id',$id,'<>');
        $this->db->orderBy('id');
        $this->db->pageLimit=10;
        $list=$this->db->paginate('project',$page);
        $count=$this->db->totalCount;
        if($count>0){
            $str.='
            <p class="subtitle fancy"><span>Dự Án Liên Quan</span></p>
            <ul class="project-list in-page clearfix">';
            foreach($list as $item){
                $str.='<li>'.$this->one_ind_project($item).'</li>';
            }
            $str.='
            </ul>';
        }     
        $str.='       
            </div>';
        $str.='
            </div>
        </div>
        </div>';
        return $str;
    }
    function ind_project(){
        $this->db->reset();
        $this->db->where('active',1)->orderBy('id');
        $this->db->pageLimit=6;
        $list=$this->db->paginate('project',1,'id,title,img');
        //$str='
        //<p class="subtitle fancy"><span>Dự Án</span></p>';
        $str='
        <div class="color access-bg">
            <div class="container clearfix paint-drop-white">
            <h2 class="ind-title">Dự Án</h2>';
        $str.='
        <ul class="project-list clearfix">';
        foreach($list as $item){
            $str.='<li>'.$this->one_ind_project($item).'</li>';
        }
        $str.='
        </ul>
        <p class="text-right">
        <a href="'.myWeb.$this->view.'">'.all.'</a>
        </p>
            </div>
        </div>';
        return $str;
    }
    function one_ind_project($item){
        $lnk=myWeb.$this->view.'/'.common::slug($item['title']).'-i'.$item['id'];
        $str='
        <div class="ind-project">
            <a href="'.$lnk.'">
                <img src="'.webPath.$item['img'].'" alt="" title="'.$item['title'].'"/>
                <h2>'.$item['title'].'</h2>
            </a>
        </div>';
        return $str;
    }

}
?>
