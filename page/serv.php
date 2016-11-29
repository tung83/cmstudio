<?php
class serv{
    private $db,$view,$lang;
    function __construct($db,$lang='vi'){
        $this->db=$db;
        $this->db->reset();
        $this->lang=$lang;
        $db->where('id',4);
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
            <li><a href="'.myWeb.$this->view.'">Dịch vụ</a></li>';
        if(isset($_GET['id'])){
            $this->db->where('id',intval($_GET['id']));
            $item=$this->db->getOne('serv','id,title,pId');
            $cate=$this->db->where('id',$item['pId'])->getOne('serv_cate','id,title');
            $str.='
            <li><a href="'.myWeb.$this->view.'/'.common::slug($cate['title']).'-p'.$cate['id'].'">'.$cate['title'].'</a></li>
            <li><a href="#">'.$item['title'].'</a></li>';
        }elseif(isset($_GET['pId'])){
            $cate=$this->db->where('id',intval($_GET['pId']))->getOne('serv_cate','id,title');
            $str.='
            <li><a href="#">'.$cate['title'].'</a></li>';
        }
        $str.='
        </ul>';
        return $str;
    }
    function serv_item($item){
        $lnk=myWeb.$this->view.'/'.common::slug($item['title']).'-i'.$item['id'];
        $str.='
        <a href="'.$lnk.'" class="about-item clearfix">
            <img src="'.webPath.$item['img'].'" class="img-responsive" alt="" title=""/>
            <div>
                <h2>'.$item['title'].'</h2>
                <span>'.nl2br(common::str_cut($item['sum'],620)).'</span>
            </div>
        </a>';
        return $str;
    }
    function serv_cate($pId){
        $page=isset($_GET['page'])?intval($_GET['page']):1;
        $this->db->reset();
        $this->db->where('active',1);
        if($pId!=0) $this->db->where('pId',$pId);
        $this->db->orderBy('id');
        $this->db->pageLimit=limit;
        $list=$this->db->paginate('serv',$page);
        $count=$this->db->totalCount;
        if($count>0){
            foreach($list as $item){
                $str.=$this->serv_item($item);
            }
        }        
        $pg = new Pagination();
        $pg->pagenumber = $page;
        $pg->pagesize = limit;
        $pg->totalrecords = $count;
        $pg->showfirst = true;
        $pg->showlast = true;
        $pg->paginationcss = "pagination-large";
        $pg->paginationstyle = 1; // 1: advance, 0: normal
        if($pId==0){
            $pg->defaultUrl = myWeb.$this->view;
            $pg->paginationUrl = myWeb.'[p]/'.$this->view;    
        }else{
            $cate=$this->db->where('id',$pId)->getOne('serv_cate','id,title');            
            $pg->defaultUrl = myWeb.$this->view.'/'.common::slug($cate['title']).'-p'.$cate['id'];
            $pg->paginationUrl = myWeb.$this->view.'/[p]/'.common::slug($cate['title']).'-p'.$cate['id'];
        }
        $str.= '<div class="pagination pagination-centered">'.$pg->process().'</div>';
        return $str;
    }
    function serv_one(){
        $id=intval($_GET['id']);
        $item=$this->db->where('id',$id)->getOne('serv');
        $str='
        <article class="article">
            <h1 class="article">'.$item['title'].'</h1>
            <p>'.$item['content'].'</p>
        </article>';
        $this->db->where('active',1)->where('id',$id,'<>');
        $this->db->orderBy('id');
        $list=$this->db->get('serv',limit);
        $count=$this->db->count;
        if($count>0){
            $str.='
            <div class="hr-custom"></div>
            <h2 class="title-tag"><span><b>Bài Viết Liên Quan</b></span></h2>
            <ul class="about-list">';
            foreach($list as $item){
                $str.='<li>'.$this->serv_item($item).'</li>';
            }
            $str.='
            </ul>';
        }     
        $str.='
        </div>
        </div>';
        return $str;
    }
    function ind_serv(){
        $this->db->where('active',1)->orderBy('id');
        $list=$this->db->get('serv',null,'id,title');
        $str='
        <div class="ind-serv">
        <h2 class="title-tag"><span><b>Dịch Vụ</b></span></h2>
        <ul class="clearfix">';
        foreach($list as $item){
            $lnk=myWeb.$this->view.'/'.common::slug($item['title']).'-i'.$item['id'];
            $str.='<li><a href="'.$lnk.'">'.$item['title'].'</a></li>';
        }
        $str.='
        </ul>
        </div>';
        return $str;
    }
    function one_ind_serv($id){
        $this->db->reset();
        $this->db->where('id',$id);
        $item=$this->db->getOne('serv','id,img,title,sum');
        $lnk=myWeb.$this->view.'/'.common::slug($item['title']).'-i'.$item['id'];
        $str='
        <div class="ind_serv">
            <a href="'.$lnk.'">
                <img src="'.webPath.$item['img'].'" alt="" title="'.$item['title'].'"/>
                <h2>'.$item['title'].'</h2>
                <span>'.common::str_cut($item['sum'],120).'</span>
            </a>
        </div>';
        return $str;
    }
    function product_image_first($db,$pId){
        $db->where('active',1)->where('pId',$pId);
        $item=$db->getOne('product_image','img');
        return $item['img'];
    }

}
?>
