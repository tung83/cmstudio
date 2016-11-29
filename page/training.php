<?php
class training{
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
    function ind_training(){
        $this->db->reset();
        $this->db->where('active',1);
        $list=$this->db->get('training_cate',null,'id,title,img');
        $str='
        <div class="white">
            <div class="container">
                <div class="row">
                    <div class="col-md-12 text-center">
                        <h1>Chào mừng bạn đến với World Nail School</h1>
                    </div>  
                    <div class="col-md-5"></div>
                    <div class="col-md-2 text-center">
                        <h2 class="one on-white"><span><i class="fa fa-diamond"></i></span></h2>
                    </div>
                    <div class="col-md-5"></div>
                </div>
                <div class="row ind-training">';
        foreach($list as $item){
            $img=$item['img']===''?'holder.js/600x600':webPath.$item['img'];
            $lnk=myWeb.$this->view.'/'.common::slug($item['title']).'-p'.$item['id'];
            $str.='
            <div class="col-xs-6 col-md-3">
            <a href="'.$lnk.'">
                <img src="'.$img.'" class="img-reponsive img-circle hvr-bounce-in" style="" />
                <h2 class="">'.$item['title'].'</h2>
            </a>
            </div>';   
        }
        $str.='
                </div>
            </div>
        </div>';
        return $str;
    }
    function breadcrumb(){
        $this->db->reset();
        $str.='
        <ul class="breadcrumb clearfix">
        	<li><a href="'.myWeb.'"><i class="fa fa-home"></i></a></li>
            <li><a href="'.myWeb.$this->view.'">Đào Tạo</a></li>';
        if(isset($_GET['id'])){
            $this->db->where('id',intval($_GET['id']));
            $item=$this->db->getOne('training','id,title,pId');
            $cate=$this->db->where('id',$item['pId'])->getOne('training_cate','id,title');
            $str.='
            <li><a href="'.myWeb.$this->view.'/'.common::slug($cate['title']).'-p'.$cate['id'].'">'.$cate['title'].'</a></li>
            <li><a href="#">'.$item['title'].'</a></li>';
        }elseif(isset($_GET['pId'])){
            $cate=$this->db->where('id',intval($_GET['pId']))->getOne('training_cate','id,title');
            $str.='
            <li><a href="#">'.$cate['title'].'</a></li>';
        }
        $str.='
        </ul>';
        return $str;
    }
    function category($pId){
        $this->db->reset();
        $this->db->where('active',1)->orderBy('ind','asc')->orderBy('id');
        $list=$this->db->get('training_cate',null,'id,title');
        $str='
        <div class="category">';
        foreach($list as $item){
            if($item['id']==$pId) $cls=' class="active"';
            else $cls='';
            $lnk=myWeb.$this->view.'/'.common::slug($item['title']).'-p'.$item['id'];
            $str.='
            <a href="'.$lnk.'"'.$cls.'>
                '.$item['title'].'
            </a>';
        }
        $str.='
        </div>';
        return $str;
    }
    function training_all(){
        $this->db->reset();
        $this->db->where('active',1)->orderBy('ind','asc')->orderBy('id');
        $list=$this->db->get('training_cate',null,'id,title');
        $str.='
        <div class="breadcrumb-background">
            <div>
                <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <h1>Đào Tạo</h1>
                    </div>
                    <div class="col-md-12">
                        '.$this->breadcrumb().'
                    </div>
                </div>
                </div>
            </div>
        </div>
        <div class="white">
            <div class="container">
                <div class="col-md-3">
                '.$this->category(0).'
                </div>
                <div class="col-md-9">
                <div class="row">
                <div class="col-md-12">';
        foreach($list as $item){
            $this->db->where('active',1)->where('pId',$item['id'])->orderBy('id');
            $sub_list=$this->db->get('training',3,'id,title,sum,img');
            if(count($sub_list)>0){
                $str.='<h2 class="cate-title">'.$item['title'].'</h2>';
                foreach($sub_list as $sub_item){
                    $str.=$this->training_list_item($sub_item);
                }
                $str.='<div class="hr-custom"></div>';
            }
        } 
        $str.='
                </div>
                </div>    
                </div>
            </div>
        </div>';
        return $str;
    }
    function training_cate($pId){
        $str.='
        <div class="breadcrumb-background">
            <div>
                <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <h1>Đào Tạo</h1>
                    </div>
                    <div class="col-md-12">
                        '.$this->breadcrumb().'
                    </div>
                </div>
                </div>
            </div>
        </div>
        <div class="white">
            <div class="container">
                <div class="col-md-3">
                '.$this->category($pId).'
                </div>
                <div class="col-md-9">
                '.$this->training_list($pId).'
                </div>
            </div>
        </div>';
        return $str;
    }
    function training_list($pId){
        $page=isset($_GET['page'])?intval($_GET['page']):1;
        $this->db->reset();
        $this->db->where('pId',$pId)->where('active',1)->orderBy('id');
        $this->db->pageLimit=limit;
        $list=$this->db->paginate('training',$page,'id,title,sum,img');
        foreach($list as $item){
            $str.=$this->training_list_item($item);
        }
        return $str;
    }
    function training_list_item($item){
        $img=$item['img']===''?'holder.js/600x600':webPath.$item['img'];
        $lnk=myWeb.$this->view.'/'.common::slug($item['title']).'-i'.$item['id'];
        $str='
        <div class="row training-item">
        <a href="'.$lnk.'">
            <div class="col-xs-3">
                <img src="'.$img.'" class="img-responsive img-circle"/>
            </div>
            <div class="col-xs-9">
                <h2>'.$item['title'].'</h2>
                <p>'.$item['sum'].'</p>
            </div>
        </a>
        </div>';
        return $str;
    }
    function training_one($id){
        $this->db->where('id',$id);
        $item=$this->db->getOne('training','id,title,content,pId');
        $this->db->where('id',$item['id'],'<>')->where('pId',$item['pId'])->where('active',1)->orderBy('id');
        $list=$this->db->get('training',5,'id,title,img,sum');
        $str.='
        <div class="breadcrumb-background">
            <div>
                <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <h1>Đào Tạo</h1>
                    </div>
                    <div class="col-md-12">
                        '.$this->breadcrumb().'
                    </div>
                </div>
                </div>
            </div>
        </div>
        <div class="white">
            <div class="container">
                <div class="col-md-3">
                '.$this->category($item['pId']).'
                </div>
                <div class="col-md-9">
                    <div class="row">
                        <div class="col-md-12">
                            <article>
                                <h1>'.$item['title'].'</h1>
                                <p>'.$item['content'].'</p>
                            </article>
                            <div class="hr-custom"></div>
                            <h2 class="other">Bài Viết Liên Quan</h2>';
        foreach($list as $item){
            $str.=$this->training_list_item($item);
        }
        $str.='
                        </div>
                    </div>
                </div>
            </div>
        </div>';
        return $str;
    }
}