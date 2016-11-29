<?php
class support{
    private $db;
    private $lang;
    private $view;
    function __construct($db,$lang='vi'){
        $db->where('id',7);
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
                <img src="'.selfPath.'htbanner.png" alt="Banner đẹp" class="img_full" />
            </div>
        </div>     
    ﻿   <div class="bk_HoTro">
            <div>
                <h3 class="white">'.sp_title.'</h3>
            </div>
        </div>';
        return $str;
    }
    function support_all($db){
        $page=isset($_GET['page'])?intval($_GET['page']):1;
        $db->where('active',1);
        $db->orderBy('id');
        $db->pageLimit=40;
        $list=$db->paginate('sp_cate',$page);
        $count=$db->totalCount;
        $str='
        <div class="container">
            <div class="col-md-12">';
        $i=1;
        foreach($list as $item){
            if($this->lang=='en'){
                $title=$item['e_title'];
            }else{
                $title=$item['title'];
            }
            $lnk=myWeb.$this->lang.'/'.$this->view.'/'.common::slug($title).'-p'.$item['id'].'.html';
            if($i%4==1){
                $str.='
                <div class="row">';
            }
            $str.='
            <div class="col-sm-3 list_row">
                <a href="'.$lnk.'" title="'.$title.'" class="titleblock_red">'.$title.'</a>
                <a class="" href="'.$lnk.'">
                    <img src="'.webPath.$item['img'].'" class="img6 img-responsive" style="width:300px"/>
                </a>
                <p style="word-wrap: break-word;">';
            $db->where('active',1)->where('pId',$item['id']);
            $sub_list=$db->get('sp',null,'id,title,e_title');
            $str.='
                    <ul class="hotroitem">';
            foreach((array)$sub_list as $sub_item){
                if($this->lang=='en'){
                    $sub_title=$sub_item['e_title'];
                }else{
                    $sub_title=$sub_item['title'];
                }
                $sub_lnk=myWeb.$this->lang.'/'.$this->view.'/'.common::slug($title).'-i'.$item['id'].'.html';
                $str.='
                <li><a href="'.$sub_lnk.'">'.$sub_title.'</a></li>';   
            }
            $str.='    
                    </ul>
                </p>
            </div>';
            if($i%4==0){
                $str.='
                </div>';
            }
            $i++;
        }
        if($i%4!=1){
            $str.='
            </div>';   
        }
        $str.='
            </div>
        </div>';
        return $str;
    } 
    function categories($db,$pId=0){
        $str='
        <div class="row" style="padding-bottom:10px">
            <div class="accordion" id="accordion2">';
        $db->where('active',1)->orderBy('id','ASC');
        $list=$db->get('sp_cate',null,'id,title,e_title,icon');
        foreach($list as $item){
            if($this>lang=='en'){
                $title=$item['e_title'];
            }else{
                $title=$item['title'];
            }
            $lnk=myWeb.$this->lang.'/'.$this->view.'/'.common::slug($title).'-p'.$item['id'].'.html';
            if($item['id']==$pId){
                $cls=' active';
            }else{
                $cls='';
            }
            $str.='
            <div class="accordion-group">
                <div class="accordion-heading  accordion_sp menu_hotro'.$cls.'">
                    <a class="accordion-toggle accordion_sp_link" href="'.$lnk.'">
                        <span class="icon_menu_left">
                            <img src="'.webPath.$item['icon'].'" class="img1">
                        </span>
                            '.$title.'
                        <span class="icon_menu right">
                            <i class="fa fa-angle-right"></i>
                        </span>
                    </a>
                </div>
            </div>';
        }
        $str.='
            </div>
        </div>';
        return $str;
    }
    function support_cate($db,$pId=0){
        $str='
        <div class="container">
        <div class="row">
            <div class="col-md-3">
                '.$this->categories($db,$pId).'
            </div>';
        $db->where('id',$pId);
        $item=$db->getOne('sp_cate','title,e_title');
        if($this->lang=='en'){
            $title=$item['e_title'];
        }else{
            $title=$item['title'];
        }
        $str.='
            <div class="col-md-9">
            <div class="row">
                <h1 style="" class="sp-title">'.$title.'</h1>
                <p style="word-wrap: break-word;">
                    <ul class="hotroitem">';
        $db->where('pId',$pId)->where('active',1)->orderBy('id');
        $list=$db->get('sp',null,'id,title,e_title');
        foreach($list as $item){
            if($this->lang=='en'){
                $title=$item['e_title'];
            }else{
                $title=$item['title'];
            }
            $lnk=myWeb.$this->lang.'/'.$this->view.'/'.common::slug($title).'-i'.$item['id'].'.html';
            $str.='
            <li><a href="'.$lnk.'">'.$title.'</a></li>
            ';
        }
        $str.='
                    </ul>
                </p>
            </div>
            </div>
        </div>
        </div>';
        return $str;  
    }
    function support_one($db,$id=0){
        $db->where('id',$id);
        $item=$db->getOne('sp','title,e_title,content,e_content,pId');
        if($this->lang=='en'){
            $title=$item['e_title'];
            $content=$item['e_content'];
        }else{
            $title=$item['title'];
            $content=$item['content'];   
        }
        $str='
        <div class="container">
            <div class="col-md-3">';
        $str.=$this->categories($db,$item['pId']);

        $str.='
            </div>
            <div class="col-md-9">
                <h3 style="text-transform:uppercase" class="art_title">'.$title.'</h3>
                <p>
                    '.$content.'
                </p>    
            </div>
        </div>';
        return $str;
    }
}


?>