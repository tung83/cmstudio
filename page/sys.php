<?php
class sys{
    private $db;
    private $lang;
    private $view;
    function __construct($db,$lang='vi'){
        $db->where('id',4);
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
                <img src="'.selfPath.'bk_hethongphanphoi.png" alt="Banner đẹp" class="img_full" />
            </div>
        </div>
    ﻿   <div class="bk_hethonghanphoi">
            <div>
                <h3 class="white">'.sys.'</h3>
                <p class="white">'.sys_desc.'</p>
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
                <span class="title">'.sys.'</span>
            </div>';
        $db->where('active',1)->orderBy('id','ASC');
        $list=$db->get('sys_cate',null,'id,title,e_title');
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
    function sys_item($db,$id){
        $db->where('id',$id);
        $item=$db->getOne('sys');
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
        <div class="row list_row" style="padding-left:0px">
            <div class="col-md-4">
                <img src="'.webPath.$item['img'].'" class="img-responsive" alt="'.$city['title'].': '.$title.'"/>
            </div>
            <div class="media-body">
                <a class="tilte_phanphoi" title="'.$city['title'].': '.$title.'" href="'.$lnk.'">
                    '.$city['title'].': '.$title.'
                </a>
                <p>'.nl2br(common::str_cut($sum,110)).'</p>
                <a class="tilte_phanphoi" title="'.$city['title'].': '.$title.'" href="'.$lnk.'">
                    <a href="'.$lnk.'"><button type="button" class="btn btn-info xemthem">'.more.'</button></a>
                </a>
            </div>
        </div>';
        return $str;
    }
    function sys_form($db){
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
        $form=new form($this->lang);
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
    function sys_cate($db,$pId=0){
        $str='
        <div class="container">
        <div class="row">
            <div class="col-md-3">
                '.$this->categories($db,$pId).'
                '.$this->sys_form($db).'
            </div>';
        if(isset($_POST['city'])&&intval($_POST['city'])>0){
            $db->where('city',intval($_POST['city']));
        }
        if(isset($_POST['district'])&&intval($_POST['district'])>0){
            $db->where('district',intval($_POST['district']));
        }
        if(isset($_POST['sys_cate'])&&intval($_POST['sys_cate'])>0){
            $db->where('pId',intval($_POST['sys_cate']));
        }elseif($pId!=0) $db->where('pId',$pId);
        $db->where('active',1)->orderBy('id');
        $list=$db->get('sys',null,'id');
        $str.='
            <div class="col-md-9">
                <div class="row">
                    <div class="col-md-7">';
        foreach($list as $item){
            $str.=$this->sys_item($db,$item['id']);
        }
        $str.='
                    </div>
                    <div class="col-md-5 viet-nam-maps">
                        '.$this->maps_generator($db,$pId).'
                    </div>
                </div>
            </div>
        </div>
        </div>';
        return $str;
    }
    function maps_generator($db,$pId=0){
        $db->reset();
        if($pId!=0){
            $db->where('pId',$pId);
        }
        $db->where('active',1);
        $list=$db->get('sys',null,'adds,title,e_title');
        if($db->count>0){
            $temp=array();
            foreach($list as $item){
                if($this->lang=='en'){
                    $title=$item['e_title'];
                }else{
                    $title=$item['title'];
                }
                if($item['adds']!=''){
                    $geo=common::lat_long($item['adds']);
                    $temp[]='['.$geo['lat'].', '.$geo['long'].', "'.selfPath.'marker.png", "'.$title.'","'.$item['adds'].'", true, "1"]';   
                }
            }
            $temp=implode(',',$temp);
        }
        $str.='
        <div id="map_canvas">
        
        </div>
        <script>
            $("#map_canvas").mapit({
                latitude:16.5722464,
                longitude:104.7705509,
                zoom:5,
                type:"roadmap",
                styles:false,
                marker :false,
                locations:[
                    '.$temp.'
                ],
                scrollwheel:true
            });
        </script>
        ';
        return $str;
    }
    function map_generator($db,$id){
        $db->reset();
        $db->where('id',$id);
        $item=$db->getOne('sys','adds,title,e_title');
        if($this->lang=='en'){
            $title=$item['e_title'];
        }else{
            $title=$item['title'];
        }
        if($item['adds']!=''){
            $geo=common::lat_long($item['adds']);
            $temp[]='['.$geo['lat'].', '.$geo['long'].', "'.selfPath.'marker.png", "'.$title.'","'.$adds.'", true, "1"]';   
        }
        $str.='
        <div id="map_canvas">
        
        </div>
        <script>
            $("#map_canvas").mapit({
                latitude:'.$geo['lat'].',
                longitude:'.$geo['long'].',
                zoom:16,
                type:"roadmap",
                styles:false,
                marker:{
                    latitude:   '.$geo['lat'].',
                    longitude:  '.$geo['long'].',
                    icon:       "'.selfPath.'marker.png",
                    open:       true,
                    center:     true
                },
                address:"<h2>'.$title.'</h2><p>'.nl2br($item['adds']).'</p>",
                scrollwheel:true
            });
        </script>
        ';
        return $str;
    }
    function sys_one($db,$id=0){
        $item=$db->where('id',$id)->getOne('sys');
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
                '.$this->sys_form($db).'
            </div>';
        $str.='
            <div class="col-md-9">
                <div class="col-md-12">
                    <h3 style="margin-top:0px">'.$city['title'].':'.$title.'</h3>
                    <p>'.$content.'</p>
                </div>
                <div class="col-md-12">
                    <div class="map">'.$this->map_generator($db,$item['id']).'</div>
                </div>
            </div>
        </div>';
        return $str;
    }
    function ind_sys($db){
        $str='
        <div class="main-feature   bk_white ">
            <div class="bk_danhmuc1 text-center">
                <h3 class="white" style="text-transform:uppercase;font-size:30px">'.sys.'</h3>
                <p class="white">'.sys_desc.'</p>
            </div>
        </div>
        <div class="hethongPhanPhoi_content bk_white">
            <div class="container">
                <div class="col-md-4 viet-nam-maps">
                    '.$this->maps_generator($db).'
                </div>
               <form class="form-horizontal" role="form" method="post" action="'.myWeb.$this->lang.'/'.$this->view.'.html">
                <div class="col-md-8">
                    <div class="row">
                        <div class="col-md-3 padding2">
                            <select class="form-control" name="sys_cate" id="sys_cate">';
        $form=new form($this->lang);
        $str.=$form->get_options($db,'sys_cate','');
        $str.='
                            </select>
                        </div>';
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
        $str.='

                        <div class="col-md-4 padding2 ">
                        '.$form->location_select($db,$param).'
                        </div> ';
        $param=array(
            'lev'=>2,
            'table' => 'quanhuyen',
            'name'=>'district',
            'id'=>intval($_GET['district']),
            'desc'=> dist_choose,
        );
        $str.='
                        <div class="col-md-3 padding2">
                        '.$form->location_select($db,$param).'
                        </div>
                        <div class="col-md-2 padding2">
                            <button type="submit" class="btn btcn" name="ok" value="Submit">Tìm</button>
                        </div>
                    </div>
                </form>';
        $db->where('active',1);
        $db->orderBy('id',desc);
        $list=$db->get('sys',null,'id,title,e_title,img,city');
        foreach($list as $item){
            if($this->lang=='vi'){
                $title=$item['title'];
            }else{
                $title=$item['e_title'];
            }
            $db->where('id',$item['city']);
            $city=$db->getOne('tinhthanh','title');
            $lnk=myWeb.$this->lang.'/'.sys_view.'/'.common::slug($title).'-i'.$item['id'].'.html';
            $str.='
            <div class="col-md-6">
                <ul class="listPhanPhoi">
                    <li><a href="'.$lnk.'">'.$city['title'].': '.$item['title'].'</a></li>
                </ul>
            </div>';
        }
        $str.='
                </div>
            </div>
        </div>';
        return $str;
    }
}


?>
