<?php
class news{
    private $db,$view,$lang;
    function __construct($db,$lang='vi'){
        $this->db=$db;
        $this->db->reset();
        $this->lang=$lang;
        $db->where('id',3);
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
            $item=$this->db->getOne('news','id,title,e_title,pId');
            $cate=$this->db->where('id',$item['pId'])->getOne('news_cate','id,title,e_title');
            if($this->lang=='en'){
                $cate_title=$cate['e_title'];
                $title=$item['e_title'];
            }else{
                $cate_title=$cate['title'];
                $title=$item['title'];
            }
            $str.='
            <li><a href="'.myWeb.$this->lang.'/'.$this->view.'/'.common::slug($cate_title).'-p'.$cate['id'].'">'.$cate_title.'</a></li>
            <li><a href="#">'.$title.'</a></li>';
        }elseif(isset($_GET['pId'])){
            $cate=$this->db->where('id',intval($_GET['pId']))->getOne('news_cate','id,title,e_title');
            if($this->lang=='en'){
                $cate_title=$cate['e_title'];
            }else{
                $cate_title=$cate['title'];
            }
            $str.='
            <li><a href="#">'.$cate_title.'</a></li>';
        }
        $str.='
        </ul>';
        return $str;
    }
    function news_item($item){
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
    function news_cate($pId){
        $page=isset($_GET['page'])?intval($_GET['page']):1;
        $this->db->reset();
        $this->db->where('active',1);
        if($pId!=0) $this->db->where('pId',$pId);
        $this->db->orderBy('id');
        $this->db->pageLimit=limit;
        $list=$this->db->paginate('news',$page);
        $count=$this->db->totalCount;
        if($count>0){
            foreach($list as $item){
                $str.=$this->news_item($item);
            }
        }        
        
        $pg=new Pagination(array('limit'=>limit,'count'=>$count,'page'=>$page,'type'=>0));        
        if($pId==0){
            $pg->set_url(array('def'=>myWeb.$this->view,'url'=>myWeb.'[p]/'.$this->view));
        }else{
            $cate=$this->db->where('id',$pId)->getOne('news_cate','id,title');       
            $pg->defaultUrl = myWeb.$this->lang.'/'.$this->view.'/'.common::slug($cate['title']).'-p'.$cate['id'];
            $pg->paginationUrl = myWeb.$this->lang.'/'.$this->view.'/[p]/'.common::slug($cate['title']).'-p'.$cate['id'];
        }
        $str.= '<div class="pagination-centered">'.$pg->process().'</div>';
        return $str;
    }
    function news_one(){
        $id=intval($_GET['id']);
        $item=$this->db->where('id',$id)->getOne('news');
        $str='
        <div class="col-xs-12">
            <article class="article">
                <h1 class="article">'.$item['title'].'</h1>
                <p>'.$item['content'].'</p>
            </article>
        </div>';
        $this->db->where('active',1)->where('id',$id,'<>');
        $this->db->orderBy('id');
        $list=$this->db->get('news',limit);
        $count=$this->db->count;
        if($count>0){
            $str.='        
            <div class="col-xs-12">    
                <h2 class="title-tag"><span><b>Bài Viết Liên Quan</b></span></h2>
            </div>';
            foreach($list as $item){
                $str.=$this->news_item($item);
            }
        }     
        return $str;
    }
    function ind_news(){
        $this->db->reset();
        $this->db->where('active',1)->orderBy('id');
        $this->db->pageLimit=6;
        $list=$this->db->paginate('news',1,'id,title,sum,img');
        $str='
        <div class="ind-news">
        <h2 class="title-tag"><span><b>Tin Tức</b></span></h2>
        <ul class="clearfix">';
        foreach($list as $item){
            $lnk=myWeb.$this->view.'/'.common::slug($item['title']).'-i'.$item['id'];
            $img=webPath.$item['img'];
            if($img=='') $img='holder.js/126x100';
            $str.='
            <li class="clearfix">
                <a href="'.$lnk.'">
                    <img src="'.$img.'" alt="'.$item['title'].'" width="126" height="100"/>
                    <div>
                    <h2>'.common::str_cut($item['title'],30).'</h2>
                    <span>'.nl2br(common::str_cut($item['sum'],160)).'</span>
                    </div>
                </a>
            </li>';   
        }
        $str.='
        </ul>
        <p class="text-right">
            <a href="'.myWeb.$this->view.'">'.all.'</a>
        </p>
        </div>';
        return $str;
    }
    function one_ind_news($id){
        $this->db->reset();
        $this->db->where('id',$id);
        $item=$this->db->getOne('news','id,img,title,sum');
        $lnk=myWeb.$this->view.'/'.common::slug($item['title']).'-i'.$item['id'];
        $str='
        <div class="ind_news">
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
