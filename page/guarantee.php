<?php
class guarantee{
    private $db;
    private $lang;
    private $view;
    function __construct($db,$lang='vi'){
        $db->where('id',5);
        $item=$db->getOne('menu');
        if($lang=='en'){
            $this->view=$item['e_view'];
        }else{
            $this->view=$item['view'];
        }
        $this->lang=$lang;
        //$this->db=$db;
    }
    function heading(){
        $str='
        <div class="slider">
            <div class="img-responsive">
                <img src="'.selfPath.'bh1.png" alt="Banner đẹp" class="img_full" />
            </div>
        </div>
    ﻿   <div class="bk_baoHanh1">
            <div>
                <h3 class="white">'.gua.'</h3>
                <p class="white">'.gua_desc.'</p>
            </div>
        </div>';
        return $str;
    }
    function categories($db,$pId=0){
        $str='
        <div class="row" style="padding-bottom:10px">
            <div class="dropbtn">
                <span class="caret-2">
                    <img src="'.selfPath.'icon-bao-hanh.png">
                </span>
                <span class="title">'.gua.'</span>
            </div>';
        $db->where('active',1)->orderBy('id','ASC');
        $list=$db->get('guarantee_cate',null,'id,title,e_title');
        foreach($list as $item){
            if($this->lang=='en'){
                $title=$item['e_title'];
            }else{
                $title=$item['title'];
            }
            $lnk=myWeb.$this->lang.'/'.$this->view.'/'.common::slug($title).'-p'.$item['id'].'.html';
            if($item['id']==$pId){
                $cls=' class="active"';
            }else{
                $cls='';
            }
            $str.='
            <div class="dropbtn menu_sub">
                <span class="icon_menu">
                    <i class="fa fa-caret-right"></i>
                </span>
                <a'.$cls.' href="'.$lnk.'">'.$title.'</a>
                <span class="icon_menu right">
                    <i class="fa fa-angle-right"></i>
                </span>
            </div>';
        }
        $str.='
        </div>';
        return $str;
    }
    function guarantee_item($db,$id){
        $db->where('id',$id);
        $item=$db->getOne('guarantee');
        if($this->lang=='en'){
            $title=$item['e_title'];
            $sum=$item['e_sum'];
        }else{
            $title=$item['title'];
            $sum=$item['sum'];
        }
        $city=$db->where('id',$item['city'])->getOne('tinhthanh','title');
        $dis=$db->where('id',$item['district'])->getOne('quanhuyen','title');
        $lnk=myWeb.$this->lang.'/'.$this->view.'/'.common::slug($title).'-i'.$item['id'].'.html';
        $str='
        <div class="col-sm-3 list_row">
            <a class="" href="'.$lnk.'" title="'.$title.'">
                <img src="'.webPath.$item['img'].'" class="  img-responsive" />
            </a>
            <a href="'.$lnk.'" title="'.$title.'" class="TileBaoHanh">'.$title.'</a>
            <p style="word-wrap: break-word;">'.nl2br($sum).'</p>
        </div>';
        return $str;
    }
    function guarantee_form($db){
        $str='
        <div class="row" style="padding-bottom:10px">
        <div class="dropbtn">
            <span class="caret-2">
                <img src="'.selfPath.'icon-chon-dia-diem.png">
            </span>
            <span class="title">Chọn địa điểm</span>
        </div>';
        $lnk=myWeb.$this->lang.'/'.$this->view.'.html';
        $str.='
        <form class="form-horizontal" role="form" method="post" action="'.$lnk.'">
            <div class="dropbtn menu_sub1">';
        $param=array(
            'lev'=>1,
            'table' => 'tinhthanh',
            'name'=>'city',
            'id'=>intval($_GET['city']),
            'control'=>'district',
            'control_table'=>'quanhuyen',
            'desc'=> city_choose,
            'control_desc'=>dist_choose
        );
        $form=new form($lang);
        $str.=$form->location_select($db,$param);
        $param=array(
            'lev'=>2,
            'table' => 'quanhuyen',
            'name'=>'district',
            'id'=>intval($_GET['district']),
            'desc'=> dist_choose,
        );
        $str.=$form->location_select($db,$param);
        $str.='
                   <button type="submit" class="btn btcn btn-search" name="ok" value="Submit">'.search.'</button>
            </div>
        </form>
        </div>';
        return $str;
    }
    function guarantee_cate($db,$pId=0){
        $str='
        <div class="container">
            <div class="col-md-3">
                '.$this->categories($db,$pId).'
                '.$this->guarantee_form($db).'
            </div>';
        if(isset($_POST['city'])&&intval($_POST['city'])>0){
            $db->where('city',intval($_POST['city']));
        }
        if(isset($_POST['district'])&&intval($_POST['district'])>0){
            $db->where('district',intval($_POST['district']));
        }
        if($pId!=0) $db->where('pId',$pId);
        $db->where('active',1)->orderBy('id');
        $list=$db->get('guarantee',null,'id');
        $str.='
            <div class="col-md-9">';
        foreach($list as $item){
            $str.=$this->guarantee_item($db,$item['id']);
        }
        $str.='
            </div>
        </div>';
        return $str;
    }
    function guarantee_one($db,$id=0){
        $item=$db->where('id',$id)->getOne('guarantee');
        if($this->lang=='en'){
            $title=$item['e_title'];
            $content=$item['e_content'];
        }else{
            $title=$item['title'];
            $content=$item['content'];
        }
        $city=$db->where('id',$item['city'])->getOne('tinhthanh','title');
        $str='
        <div class="container">
            <div class="col-md-3">
                '.$this->categories($db,$item['pId']).'
                '.$this->guarantee_form($db).'
            </div>';
        $str.='
            <div class="col-md-9">
                <div class="col-md-6">
                    <p><a class="TileBaoHanh" title="'.$title.'" href="#">'.$title.'</a></p>
                    <p>'.$content.'</p>
                </div>
                <div class="col-md-6">
                    <img src="'.webPath.$item['img'].'" class="  img-responsive" width="500px" height="333px" />
                </div>
            </div>
        </div>';
        return $str;
    }
    function ind_guarantee($db){
        $db->where('active',1)->orderBy('id');
        $item=$db->getOne('guarantee','id,title,e_title,sum,e_sum,img');
        if($this->lang=='en'){
            $title=$item['e_title'];
            $sum=$item['e_sum'];
        }else{
            $title=$item['title'];
            $sum=$item['sum'];
        }
        $lnk=myWeb.$this->lang.'/'.$this->view.'/'.common::slug($title).'-i'.$item['id'].'.html';
        $str='
        <div class="col-md-4">
            <h3>'.gua.'</h3>
            <hr class="hr_title" />
            <div class="ind_news">
                <div class="col-sm-12 padding0" style="padding:4px">
                    <a href="'.$lnk.'" class="for-img">
                        <img src="'.webPath.$item['img'].'" alt="'.$title.'" class="img-responsive img-t" style="width:350px"/>
                    </a>
                </div>
                <div class="col-sm-12 padding0" style="padding:4px">
                    <a href="'.$lnk.'" style="">
                        <h4 style="margin-top:0px">'.$title.'</h4>
                        <p>'.nl2br($sum).'</p>
                    </a>
                </div>
                <hr class="device" style="clear:both"/>
                <div>
                    <ul class="listNews">';
        $db->where('active',1)->where('id',$item['id'],'<>')->orderBy('id');
        $list=$db->get('guarantee',null,'id,title,e_title');
        foreach($list as $item){
            if($this->lang=='en'){
                $title=$item['e_title'];
                $sum=$item['e_sum'];
            }else{
                $title=$item['title'];
                $sum=$item['sum'];
            }
            $lnk=myWeb.$this->lang.'/'.$this->view.'/'.common::slug($title).'-i'.$item['id'].'.html';
            $str.='
            <li><a href="'.$lnk.'">'.$title.'</a></li>';
        }
        $str.='
                    </ul>
                </div>
            </div>
        </div>';
        return $str;
    }
}


?>
