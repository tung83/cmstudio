<?php
class career{
    private $db,$view,$lang;
    function __construct($db,$lang='vi'){
        $this->db=$db;
        $this->db->reset();
        $this->lang=$lang;
        $db->where('id',6);
        $item=$db->getOne('menu');
        if($lang=='en'){
            $this->view=$item['e_view'];
        }else{
            $this->view=$item['view'];
        }
    }
    function ind_career(){
        $this->db->where('active',1)->orderBy('id');
        $list=$this->db->get('career',null,'id,title');
        $str='
        <div class="color" style="background: #ededed;">
            <div class="container career clearfix">
                <div class="title-tag">
                    Tuyển Dụng
                </div>
                <div class="left">
                    <img src="'.selfPath.'career.png" alt="" title=""/>
                </div>
                <div class="right">';
        foreach($list as $item){
            $lnk=myWeb.$this->view.'/'.common::slug($item['title']).'-i'.$item['id'];
            $str.='
            <span><a href="'.$lnk.'">'.$item['title'].'</a></span>';
        }
        $str.='
                </div>
            </div>
        </div>';
        return $str;
    }
    function career_all(){
        $page=isset($_GET['page'])?intval($_GET['page']):1;
        $this->db->where('active',1);
        $this->db->orderBy('id');
        $this->db->pageLimit=10;
        $list=$this->db->paginate('career',$page);
        $count=$this->db->totalCount;
        $str='
        <style>
            body{
                background:url('.selfPath.'about.jpg) top center no-repeat
            }
        </style>
        <div style="background:#f8f8f8;padding:10px 0px 0px 0px;margin-top:50px">
        <div class="container clearfix about_content">
            <div class="title-tag">Tuyển Dụng</div>';
        if($count>0){
            $str.='
            <ul class="about_list">';
            foreach($list as $item){
                $str.='<li>'.$this->career_item($item).'</li>';
            }
            $str.='
            </ul>';
        }        
        $str.='
        </div>
        </div>';
        return $str;
    }
    function career_item($item){
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
    function career_head(){
        $str='
        <div class="slider" style="">
            <div class="img-responsive">
                        <img src="'.selfPath.'gioithieubanner.png" alt="Banner đẹp" class="img_full" />
            </div>
        </div>
    ﻿   <div class="bk_baoHanh1 gioithieu_1">
            <div>
                <h3 class="white" style="text-transform:uppercase">'.about_title.'</h3>
            </div>
        </div>';
        return $str;
    }
    function career_one(){
        $id=intval($_GET['id']);
        $item=$this->db->where('id',$id)->getOne('career');
        $str='
        <style>
            body{
                background:url('.selfPath.'about.jpg) top center no-repeat
            }
        </style>
        <div style="background:#f8f8f8;padding:10px 0px 0px 0px;margin-top:50px">
        <div class="container clearfix about_content">
            <div class="title-tag">Tuyển Dụng</div>
            <article class="article">
                <h1 class="article">'.$item['title'].'</h1>
                <p>'.$item['content'].'</p>
            </article>';
        $page=isset($_GET['page'])?intval($_GET['page']):1;
        $this->db->where('active',1)->where('id',$id,'<>');
        $this->db->orderBy('id');
        $this->db->pageLimit=10;
        $list=$this->db->paginate('career',$page);
        $count=$this->db->totalCount;
        if($count>0){
            $str.='
            <ul class="about_list">';
            foreach($list as $item){
                $str.='<li>'.$this->career_item($item).'</li>';
            }
            $str.='
            </ul>';
        }     
        $str.='
        </div>
        </div>';
        return $str;
    }
}
?>
