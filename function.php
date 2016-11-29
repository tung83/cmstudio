<?php
include_once 'front.php';
function pageHeader($view, $db)
{
    switch ($view) {
        case 'san-pham':
            if (isset($_GET['id'])) {
                $db->where('id', intval($_GET['id']));
                $item = $db->getOne('product', 'title,meta_keyword,meta_description');
                $param = array(
                    'title' => $item['title'],
                    'keyword' => $item['meta_keyword'],
                    'description' => $item['meta_description']);
                break;
            } elseif (isset($_GET['pId'])) {
                $db->where('id', intval($_GET['pId']));
                $item = $db->getOne('category', 'title,meta_keyword,meta_description');
                $param = array(
                    'title' => $item['title'],
                    'keyword' => $item['meta_keyword'],
                    'description' => $item['meta_description']);
                break;
            }
        case 'gioi-thieu':
            if (isset($_GET['id'])) {
                $db->where('id',intval($_GET['id']));
                $item = $db->getOne('about', 'title,meta_keyword,meta_description');
                $param = array(
                    'title' => $item['title'],
                    'keyword' => $item['meta_keyword'],
                    'description' => $item['meta_description']);
                break;
            }
        case 'du-an-tieu-bieu':
            if (isset($_GET['id'])) {
                $db->where('id',intval($_GET['id']));
                $item = $db->getOne('project', 'title,meta_keyword,meta_description');
                $param = array(
                    'title' => $item['title'],
                    'keyword' => $item['meta_keyword'],
                    'description' => $item['meta_description']);
                break;
            } elseif(isset($_GET['pId'])) {
                $db->where('id', intval($_GET['pId']));
                $item = $db->getOne('project_cate', 'title,meta_keyword,meta_description');
                $param = array(
                    'title' => $item['title'],
                    'keyword' => $item['meta_keyword'],
                    'description' => $item['meta_description']);
                break;
            }
        case 'trang-chu':
        case 'lien-he':
        default:
            $db->where('view', $view);
            $item = $db->getOne('menu', 'title,meta_keyword,meta_description');
            $param = array(
                'title' => 'Quang Dũng | ' . $item['title'],
                'keyword' => $item['meta_keyword'],
                'description' => $item['meta_description']);
            break;
    }
    $param['title'] = $param['title'] === '' ? head_title : $param['title'];
    $param['meta_keyword'] = $param['meta_keyword'] === '' ? head_keyword : $param['meta_keyword'];
    $param['meta_description'] = $param['meta_description'] === '' ?
        head_description : $param['meta_description'];
    common::page_head($param);
}
function home($db,$view,$lang)
{
    $str=slide($db,$view,$lang);
    return $str;
}
function ind_video($db){
    $list=$db->where('active',1)->orderBy('id')->get('video',10,'id,title,video');
    $str.='
    <div class="ind-video">';
    if($db->count>0){
        $str.='
        <a href="http://www.youtube.com/watch?v='.$list[0]['video'].'" class="popup-youtube big-ind-video">
        <div>
            <img src="http://img.youtube.com/vi/'.$list[0]['video'].'/0.jpg" class="main-img" alt="" title=""/>
            <span>'.$list[0]['title'].'</span>
            <img src="'.selfPath.'youtube.png" class="button" alt="" title=""/>
        </div>
        </a>';
        for($i=1;$i<$db->count;$i++){
            $str.='
            <a href="http://www.youtube.com/watch?v='.$list[$i]['video'].'" class="popup-youtube small-ind-video">
                '.$list[$i]['title'].'
            </a>';
        }
    }
    $str.='
    </div>';
    return $str;
}
function training($db,$view){
    common::load('training','page');
    $obj=new training($db);
    $str='';
    if(isset($_GET['pId'])){
        $str.=$obj->training_cate(intval($_GET['pId']));
    }elseif(isset($_GET['id'])){
        $str.=$obj->training_one(intval($_GET['id']));
    }else{
        $str.=$obj->training_all();
    }
    return $str;
}
function  foot_menu($db,$view){
    $db->where('active', 1);
    $db->orderBy('ind', 'ASC');
    $db->orderBy('id');
    $list = $db->get('menu');
    $str.='
    <ul class="foot_menu clearfix">';
    foreach($list as $item){
        $title=$item['title'];
        $str.='
        <li><a href="'.myWeb.$item['view'].'">'.$title.'</a>';
        $str.='
        </li>';
        if($item['view']=='san-pham'){
            $cate_list=$db->where('active',1)->where('lev',1)->orderBy('ind','ASC')->orderBy('id')->get('product_cate',null,'id,title');
            foreach($cate_list as $cate){
                $lnk=myWeb.$item['view'].'/'.common::slug($cate_item['title']).'-p'.$cate_item['id'];
                $str.='
                <li><a href="'.myWeb.$item['view'].'">'.$title.'</a>';
            }
        }
    }
    $str.='
    </ul>';
    return $str;
}
function flag_show($lang){
    if($lang=='en'){
        $flag = 'vi_lang.gif';
        $flag_lnk = common::language_change($_SERVER['REQUEST_URI'],'vi');  
    }
    else{
        $flag = 'en_lang.gif';
        $flag_lnk = common::language_change($_SERVER['REQUEST_URI'],'en');
    }
    $str.='
    <a href="'.$flag_lnk.'">
        <img src="'.selfPath.$flag.'" class="language-flag" alt="" title=""/>
    </a>';
    return $str;
}
function menu($db, $view, $lang='vi')
{
    $db->where('active', 1)->orderBy('ind', 'ASC')->orderBy('id');
    $list = $db->get('menu');
    $str.='
    <div class="row">
    <nav class="wsdownmenu clearfix">
        <ul class="wsdown-mobile wsdownmenu-list">';
    foreach($list as $item){
        $cls=($view==$item['view'])?' class="active"':'';
        switch($item['view']){
            case 'du-an':
            case 'san-pham':
                $lnk='#';
                $caret='<span class="arrow"></span>';
                break;
            default:
                $caret='';
                break;
        }
        if($lang=='en'){
            $title=$item['e_title'];
            $lnk=myWeb.$lang.'/'.$item['e_view'];
        }else{
            $title=$item['title'];
            $lnk=myWeb.$lang.'/'.$item['view'];   
        }
        $str.='
        <li><a href="'.$lnk.'" '.$cls.'>'.$title.$caret.'</a>';
        switch($item['view']){
            case 'san-pham':
                $db->where('active',1)->orderBy('id');
                $cate=$db->get('product_cate',null,'id,title,e_title');
                if(count($cate)>0){
                    $str.='
                    <ul class="wsdownmenu-submenu">';
                    foreach($cate as $cate_item){
                        if($lang=='en'){
                            $cate_item_title=$cate_item['e_title'];
                            $lnk=myWeb.$lang.'/'.$item['e_view'].'/'.common::slug($cate_item['e_title']).'-p'.$cate_item['id'];   
                        }else
                        {
                            $cate_item_title=$cate_item['title'];
                            $lnk=myWeb.$lang.'/'.$item['view'].'/'.common::slug($cate_item['title']).'-p'.$cate_item['id'];
                        }
                        $str.='
                        <li><a href="'.$lnk.'"><i class="fa fa-angle-right"></i>'.$cate_item_title.'</a></li>';
                    }
                    $str.='
                    </ul>';
                }
                break;
            case 'du-an':
                $db->where('active',1)->orderBy('id');
                $cate=$db->get('news_cate',null,'id,title');
                if(count($cate)>0){
                    $str.='
                    <ul class="wsdownmenu-submenu">';
                    foreach($cate as $cate_item){
                        $lnk=myWeb.$lang.'/'.$item['view'].'/'.common::slug($cate_item['title']).'-p'.$cate_item['id'];
                        $str.='
                        <li><a href="'.$lnk.'"><i class="fa fa-angle-right"></i>'.$cate_item['title'].'</a></li>';
                    }
                    $str.='
                    </ul>';
                }
                break;
            default:
                break;
        }
        $str.='
        </li>';
    }
    $str.='
        </ul>
    </nav>
    </div>';
    return $str;
}
function slide($db,$view,$lang)
{
    $db->orderBy('ind', 'asc');
    $db->orderBy('id', 'asc');
    $db->where('active', 1);
    $list = $db->get('slider');
    $str='
    <div class="row">
    <div id="layerslider_1" class="ls-wp-container" style="max-width:100%;height:800px;margin:0 auto;margin-bottom: 0px;">';
    foreach($list as $item){
        $str.='
        <div class="ls-slide" data-ls="slidedelay:10000;transition2d:11;">
        <img src="" data-src="'.webPath.$item['img'].'" class="ls-bg" alt="Slide background" />';
        if(trim($item['title'])!=''){
            $str.='
            <p class="ls-l" style="top:100px;left:60px;font-weight: 500;font-size:30px;color:#ffffff;white-space: nowrap;" data-ls="offsetxin:0;durationin:2500;delayin:2000;rotateyin:90;transformoriginin:left 50% 0;offsetxout:0;rotateyout:-90;transformoriginout:left 50% 0;">
            '.$item['title'].'
            </p>';
        }
        if(trim($item['sum'])!=''){
            $str.='
            <p class="ls-l" style="max-width:600px;top:170px;left:60px;font-size:16px;line-height:20px;color:#fff;background:rgba(0,0,0,0.5);padding:10px" data-ls="offsetxin:0;durationin:2000;delayin:3500;">
            '.nl2br(common::str_cut($item['sum'],300)).'
            </p>';
        }
        if(trim($item['lnk'])!=''){
            $str.='
            <p class="ls-l" style="top:350px;left:60px; border-radius: 3px; color: #FFFFFF; cursor: pointer; display: inline-block; line-height: 50px; outline: medium none; position: relative; text-transform: capitalize; transition: all 0.3s ease 0s; z-index: 1; background: none repeat scroll 0 0 #263944; padding: 0 20px; font-size:16px; font-weight:300;white-space: nowrap;" data-ls="offsetxin:0;durationin:400;delayin:4500;easingin:linear;rotateyin:90;transformoriginin:left 50% 0;offsetxout:0;durationout:100;showuntil:5400;easingout:linear;rotateyout:90;transformoriginout:left 50% 0;">
            <a href="http://'.$item['lnk'].'" target="_blank" style="color:#fff">
            Xem Thêm...
            </a>
            </p>';
        }
        $str.='
        </div>';
    }
    $str.='
    </div>
    </div>';
    return $str;
}

function left_module($db)
{
    $str .= category($db);
    return $str;
}
function category($db)
{
    $str = '
    <div class="col-sm-3">
    	<div class="left-sidebar">
    		<h2>Danh Mục</h2>
    		<div class="panel-group category-products" id="accordian"><!--category-productsr-->';
    $db->where('active', 1);
    $db->where('lev', 1);
    $list = $db->get('category', null, 'id,title');
    foreach ($list as $item) {
        $db->where('pId', $item['id']);
        $db->where('lev', 2);
        $child_list = $db->get('category', null, 'id,title');
        if ($db->count > 0) {
            $plus = '<span class="pull-right"><i class="fa fa-plus"></i></span>';
            $tmp = '
            <div id="cate_sub' . $item['id'] .
                '" class="panel-collapse collapse">
				<div class="panel-body">
					<ul>';
            foreach ($child_list as $child_item) {
                $lnk = myWeb . 'san-pham/' . common::slug($child_item['title']) . '-p' . $child_item['id'] .
                    '.html';
                $tmp .= '<li><a href="' . $lnk . '">' . $child_item['title'] . ' </a></li>';
            }
            $tmp .= '
					</ul>
				</div>
			</div>
            ';
        } else {
            $plus = '';
            $tmp = '';
        }
        $str .= '
        <div class="panel panel-default">
			<div class="panel-heading">
				<h4 class="panel-title">
					<a data-toggle="collapse" data-parent="#accordian" href="#cate_sub' . $item['id'] .
            '">
						' . $plus . '
						' . $item['title'] . '
					</a>
				</h4>
			</div>
			' . $tmp . '
		</div>
        ';
    }
    $str .= '
    		</div><!--/category-products-->
    		<div class="shipping text-center"><!--shipping-->
    			<img src="/images/home/shipping.jpg" alt="" />
    		</div><!--/shipping-->

    	</div>
    </div>
    ';
    return $str;
}
function feature_product($db)
{
    $db->where('active', 1)->where('home', 1);
    $list = $db->get('product', null, 'id');
    $str .= '
    <div class="features_items"><!--features_items-->
		<h2 class="title text-center">Sản Phẩm Nổi Bật</h2>
    ';
    foreach ($list as $item) {
        $pd = new product($db, 'product');
        $pd->set_id($item['id']);
        $str .= $pd->feature_item();
    }
    $str .= '
	</div><!--features_items-->
    ';
    return $str;
}
function contact($db,$lang)
{
    common::load('contact','page');
    $obj = new contact($db,$lang);    
    $str.='    
    <div class="row">
        <div class="white clearfix">
            <div class="col-md-12">'.$obj->breadcrumb().'</div>
            '.$obj->contact().'
        </div>
    </div>';
    return $str;
}

function breadcrumb($db)
{
    $breadcrumb = new breadcrumb();
    $breadcrumb->add('Trang Chủ', 'trang-chu.html')->add('Sản Phẩm', 'san-pham.html');
    $breadcrumb->add('Liên Hệ', '#');
    return $breadcrumb->bootstrap();
}
function ind_cate($db, $lang)
{
    $db->where('active', 1)->where('lev', 1)->orderBy('ind', 'ASC')->orderBy('id');
    $list = $db->get('category', null, 'id,title,e_title,icon');
    $str = '
    <div class="main-feature">
        <div class="bk_danhmuc text-center">
            <h2 class="white">
               ' . cate . '
            </h2>
        </div>
        <div class="text-center ">
            <div class="container">
                <div class="row">';
    $i = 0;
    foreach ($list as $item) {
        $i++;
        if ($i % 4 == 1) {
            $str .= '
            <div class="col-md-6 col-sm-6">
                <div class="row">
            ';
        }
        if ($lang == 'vi') {
            $title = $item['title'];
        } else {
            $title = $item['e_title'];
        }
        $lnk = myWeb . $lang . '/' . pd_view . '/' . common::slug($title) . '-cate' . $item['id'] .
            '.html';
        $str .= '
        <div class="col-md-3 col-xs-6 item_dm">
            <div>
                <a href="' . $lnk . '">
                    <img src="' . webPath . $item['icon'] . '" />
                </a>
            </div>
            <h3>
                <a href="' . $lnk . '">' . $title . '</a>
            </h3>
        </div>';
        if ($i % 4 == 0) {
            $str .= '
                </div>
            </div>
            ';
        }
    }
    if ($i % 4 != 0) {
        $str .= '
            </div>
        </div>
        ';
    }
    $str .= '
                </div>
            </div>
        </div>
    </div>
    ';
    return $str;
}
function about($db,$view,$lang)
{
    common::load('about','page');
    $obj = new about($db,$lang);
    
    $str.='    
    <div class="row">
        <div class="white clearfix">
            <div class="col-md-12">'.$obj->breadcrumb().'</div>';                    
    if (!isset($_GET['id'])) {
        $str .= $obj->about_all();
    } else {
        $str .= $obj->about_one();
    }
            
    $str.='
        </div>
    </div>';
    return $str;
}
function career($db, $view)
{
    common::load('career','page');
    $obj = new career($db);
    if (!isset($_GET['id'])) {
        $str .= $obj->career_all();
    } else {
        $str .= $obj->career_one();
    }
    return $str;
}
function serv($db, $view)
{
    common::load('serv','page');
    $obj = new serv($db);
    
    $str.='
    <div class="container all-i-know">
        <div class="row">'.$obj->breadcrumb().'</div>';
            
    if (isset($_GET['id'])) {
        $str .= $obj->serv_one($db, intval($_GET['id']));
    } else {
        $pId=isset($_GET['pId'])?intval($_GET['pId']):0;
        $str .= $obj->serv_cate($pId);
    }            
    $str.='
    </div>';
    return $str;
}

function sitemap($db, $lang, $view)
{
    $db->where('id', 1);
    $item = $db->getOne('qtext');
    if ($lang == 'en') {
        $content = $item['e_content'];
    } else {
        $content = $item['content'];
    }
    $str = '
    <div class="slider">
        <div class="img-responsive">
            <img src="' . selfPath .
        'lienhe_banner.png" alt="Banner đẹp" class="img_full" />
        </div>
    </div>
﻿   <div class="bk_video">
        <div>
            <h3 class="white">Sitemap</h3>
        </div>
    </div>
    <div class="container">
        <div class="col-md-12">
            <div class="col-sm-3 list_row">
                <p style="word-wrap: break-word;">
                    ' . $content . '
                </p>
            </div>

        </div>
    </div>
    ';
    return $str;
}
function project($db,$view){
    include_once phpLib . 'project.php';
    $obj = new project($db);
    if (isset($_GET['id'])) {
        $str .= $obj->project_one($db, intval($_GET['id']));
    } else {
        $pId=isset($_GET['pId'])?intval($_GET['pId']):0;
        $str .= $obj->project_cate($pId);
    }
    return $str;
}
function news($db, $view,$lang)
{
    common::load('news','page');
    $obj = new news($db,$lang);
    
    $str.='    
    <div class="row">
        <div class="white clearfix">
            <div class="col-md-12">'.$obj->breadcrumb().'</div>'; 
            
    if (isset($_GET['id'])) {
        $str .= $obj->news_one($db, intval($_GET['id']));
    } else {
        $pId=isset($_GET['pId'])?intval($_GET['pId']):0;
        $str .= $obj->news_cate($pId);
    }            
    $str.='
        </div>
    </div>';
    return $str;
}
function cart($db, $view)
{
    common::load('product','page');
    $pd=new product($db);
    common::load('cart_show','page');
    $cart = new cart_show($db);
    
    $str.='
    <div class="container all-i-know">
        <div class="row"></div>
        <div class="row">
            <div class="col-md-3">
                '.$pd->category(0).'
            </div>
            <div class="col-md-9">';
    $str.=$cart->cart_output();
    $str.='
            </div>
        </div>
    </div>';
    return $str;
}
function manual($db,$lang)
{
    common::load('manual','page');
    $obj = new manual($db,$lang);
    
    $str.='    
    <div class="row">
        <div class="white clearfix">
            <div class="col-md-12">'.$obj->breadcrumb().'</div>'; 
            
    if (isset($_GET['id'])) {
        $str .= $obj->video_one($db, intval($_GET['id']));
    } else {
        $pId=isset($_GET['pId'])?intval($_GET['pId']):0;
        $str .= $obj->video_cate($pId);
    }            
    $str.='
        </div>
    </div>';
    return $str;
}
function promotion($db, $view)
{
    common::load('promotion','page');
    $obj = new promotion($db);
    
    $str.='
    <div class="container all-i-know">
        <div class="row">'.$obj->breadcrumb().'</div>';
            
    if (isset($_GET['id'])) {
        $str .= $obj->promotion_one($db, intval($_GET['id']));
    } else {
        $pId=isset($_GET['pId'])?intval($_GET['pId']):0;
        $str .= $obj->promotion_cate($pId);
    }            
    $str.='
    </div>';
    return $str;
}
function product($db,$view,$lang)
{
    common::load('product','page');
    $pd=new product($db,$lang);
    if(isset($_GET['cate_id'])) $pId=intval($_GET['cate_id']);
    elseif(isset($_GET['pId'])) $pId=intval($_GET['pId']);
    else $pId=0;
    $str.='    
    <div class="row">
        <div class="white clearfix">
            <div class="col-md-12">'.$pd->breadcrumb().'</div>';
    if(isset($_GET['id'])){
        $str.=$pd->product_one(intval($_GET['id']));
    }elseif(isset($_GET['pId'])){
        $str.=$pd->product_cate(intval($_GET['pId']));
    }else{
        $str.=$pd->cate();   
    }  
    $str.='
        </div>
    </div>';
    return $str;
}
function support($db, $lang, $view)
{
    include_once phpLib . 'support.php';
    $obj = new support($db, $lang);
    $str = $obj->heading();
    if (isset($_GET['id'])) {
        $str .= $obj->support_one($db, intval($_GET['id']));
    } elseif (isset($_GET['pId'])) {
        $str .= $obj->support_cate($db, intval($_GET['pId']));
    } else {
        $str .= $obj->support_all($db);
    }
    return $str;
}
function video($db,$lang)
{
    common::load('video','page');
    $obj = new video($db,$lang);
    
    $str.='    
    <div class="row">
        <div class="white clearfix">
            <div class="col-md-12">'.$obj->breadcrumb().'</div>'; 
            
    if (isset($_GET['id'])) {
        $str .= $obj->video_one($db, intval($_GET['id']));
    } else {
        $pId=isset($_GET['pId'])?intval($_GET['pId']):0;
        $str .= $obj->video_cate($pId);
    }            
    $str.='
        </div>
    </div>';
    return $str;
}
function sys($db, $lang, $view)
{
    include_once phpLib . 'sys.php';
    $obj = new sys($db, $lang);
    $str = $obj->heading();
    if (isset($_GET['id'])) {
        $str .= $obj->sys_one($db, intval($_GET['id']));
    } else {
        $str .= $obj->sys_cate($db, intval($_GET['pId']));
    }
    return $str;
}
function guarantee($db, $lang, $view)
{
    include_once phpLib . 'guarantee.php';
    $obj = new guarantee($db, $lang);
    $str = $obj->heading();
    if (isset($_GET['id'])) {
        $str .= $obj->guarantee_one($db, intval($_GET['id']));
    } else {
        $str .= $obj->guarantee_cate($db, intval($_GET['pId']));
    }
    return $str;
}
function search($db,$view){
    $hint=$_GET['hint'];
    include_once phpLib . 'search.php';
    $obj = new search($db,$hint);
    $obj->add('product','Sản Phẩm','san-pham');
    $obj->field_search(array('sum','content'))->field_get(array('sum'))->add('about','Giới Thiệu','gioi-thieu');
    $obj->field_search(array('sum','content'))->field_get(array('sum'))->add('project','Dự Án Tiêu Biểu','du-an-tieu-bieu');
    
    $obj->field_search(array('sum','content'))->field_get(array('sum'))->add('news','Tin Tức','tin-tuc');
    $obj->add('video','Video Clips','tuyen-dung');
    $str.=$obj->output();
    return $str;
}
function qtext($db,$id){
    $db->where('id',$id);
    $item=$db->getOne('qtext','content');
    return $item['content'];
}
function homepage_db($db,$view){
    $db->where('active',1)->orderBy('id');
    $item=$db->getOne('slider','img');
    if($item['img']!=''&&$view='trang-chu') return 'background-url:url('.webPath.$item['img'].')';
    else return '';
}
function cart_count($db){
    common::load('cart');
    $obj=new cart($db);
    return $obj->cart_count();
}
?>
