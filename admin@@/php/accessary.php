<?php
function mainProcess($db)
{
    switch($_GET['type']){
        case 'accessary_cate':
            return accessary_cate($db);
            break;
        default:
            if(isset($_GET['id'])) return accessary_image($db);
            else return accessary($db);
            break;
    }
}
function accessary_cate($db)
{
	$msg='';
    $act='accessary';
    $type='accessary_cate';
    $table='accessary_cate';
    $lev=1;
    if(isset($_POST["Edit"])&&$_POST["Edit"]==1){
		$db->where('id',$_POST['idLoad']);
        $list = $db->getOne($table);
        $btn=array('name'=>'update','value'=>'Update');
        $form = new form($list);
	} else {
        $btn=array('name'=>'addNew','value'=>'Submit');	
        $form = new form();
	}
	if(isset($_POST["addNew"])||isset($_POST["update"])) {
        $title=htmlspecialchars($_POST['title']);	   
        $meta_kw=htmlspecialchars($_POST['meta_keyword']);
        $meta_desc=htmlspecialchars($_POST['meta_description']);
        $active=$_POST['active']=="on"?1:0;
        $ind=intval($_POST['ind']);
	}
    if(isset($_POST['listDel'])&&$_POST['listDel']!=''){
        $list = explode(',',$_POST['listDel']);
        foreach($list as $item){
            $db->where('id',intval($item));
            try{
               $db->delete($table); 
            } catch(Exception $e) {
                $msg=$e->getMessage();
            }
        }
        header("location:".$_SERVER['REQUEST_URI'],true);
    }
	if(isset($_POST["addNew"])) {
        $insert = array(
                    'title'=>$title,'lev'=>$lev,
                    'active'=>$active,'meta_keyword'=>$meta_kw,
                    'meta_description'=>$meta_desc,'ind'=>$ind
                );
		try{
            $recent = $db->insert($table,$insert);
            header("location:".$_SERVER['REQUEST_URI'],true); 
        } catch(Exception $e) {
            $msg=$e->getMessage();
        }			
	}
	if(isset($_POST["update"]))	{
	   $update=array(
                    'title'=>$title,'lev'=>$lev,
                    'active'=>$active,'meta_keyword'=>$meta_kw,
                    'meta_description'=>$meta_desc,'ind'=>$ind
                );
        try{
            $db->where('id',$_POST['idLoad']);
            $db->update($table,$update);  
            header("location:".$_SERVER['REQUEST_URI'],true);   
        } catch (Exception $e){
            $msg=$e->getMessage();
        }
	}
	
	if(isset($_POST["Del"])&&$_POST["Del"]==1) {
        $db->where('id',$_POST['idLoad']);
        try{
           $db->delete($table); 
           header("location:".$_SERVER['REQUEST_URI'],true);
        } catch(Exception $e) {
            $msg=$e->getMessage();
        }
	}
    $page_head= array(
                    array('#','Loại phụ tùng')
                );
	$str=$form->breadcumb($page_head);
	$str.=$form->message($msg);
    
    $str.=$form->search_area($db,$act,'',$_GET['hint'],0);
    
    $head_title=array('Tiêu đề','Thứ tự','Hiển thị');
	$str.=$form->table_start($head_title);
	
    $page=isset($_GET["page"])?intval($_GET["page"]):1;
    if(isset($_GET['hint'])) $db->where('title','%'.$_GET['hint'].'%','LIKE');  
    $db->orderBy('id');
    $db->pageLimit=ad_lim;
    $list=$db->paginate($table,$page);

    if($db->count!=0){
        foreach($list as $item){
            $item_content = array(
                array($item['title'],'text'),
                array($item['ind'],'text'),
                array($item['active'],'bool')
            );
            $str.=$form->table_body($item['id'],$item_content);      
        }
    }                               
	$str.=$form->table_end();                            
    $str.=$form->pagination($page,ad_lim,$count);
	$str.='			
	<form role="form" id="actionForm" name="actionForm" enctype="multipart/form-data" action="" method="post" data-toggle="validator">
	<div class="row">
    	<div class="col-lg-12"><h3>Cập nhật - Thêm mới thông tin</h3></div>
        <div class="col-lg-12">
            '.$form->text('title',array('label'=>'Tiêu đề','required'=>true)).'
            '.$form->text('meta_keyword',array('label'=>'Keyword <code>SEO</code>')).'
            '.$form->textarea('meta_description',array('label'=>'Description <code>SEO</code>')).'
            '.$form->number('ind',array('label'=>'Thứ tự','required'=>true)).'
            '.$form->checkbox('active',array('label'=>'Hiển Thị','checked'=>true)).'
        </div>
    	'.$form->hidden($btn['name'],$btn['value']).'
	</div>
	</form>
	';	
	return $str;
}
function accessary($db){
    $msg='';
    $act='accessary';
    $type='accessary';
    $table='accessary';
    if(isset($_POST["Edit"])&&$_POST["Edit"]==1){
		$db->where('id',$_POST['idLoad']);
        $list = $db->getOne($table);
        $btn=array('name'=>'update','value'=>'Update');
        $form = new form($list);
	} else {
        $btn=array('name'=>'addNew','value'=>'Submit');
        $form = new form();
	}
	if(isset($_POST["addNew"])||isset($_POST["update"])) {
        $title=htmlspecialchars($_POST['title']);
        $price=intval($_POST['price']);
        $price_reduce=intval($_POST['price_reduce']);
        $meta_kw=htmlspecialchars($_POST['meta_keyword']);
        $meta_desc=htmlspecialchars($_POST['meta_description']);
        $content=str_replace("'","",$_POST['content']);     
        $feature=str_replace("'","",$_POST['feature']);
        $video=htmlspecialchars($_POST['video']);
        $active=$_POST['active']=="on"?1:0;
        $home=$_POST['home']=='on'?1:0;
        $pId=intval($_POST['frm_cate_1']);
	}
    if(isset($_POST['listDel'])&&$_POST['listDel']!=''){
        $list = explode(',',$_POST['listDel']);
        foreach($list as $item){
            $db->where('id',intval($item));
            try{
               $db->delete($table);
            } catch(Exception $e) {
                $msg=mysql_error();
            }
        }
        header("location:".$_SERVER['REQUEST_URI'],true);
    }
	if(isset($_POST["addNew"])) {
        $insert = array(
                    'title'=>$title,'price_reduce'=>$price_reduce,'content'=>$content,
                    'feature'=>$feature,'price'=>$price,'video'=>$video,
                    'meta_keyword'=>$meta_kw,'meta_description'=>$meta_desc,
                    'pId'=>$pId,'home'=>$home,
                    'active'=>$active
                );
		try{
            $db->insert($table,$insert);
            header("location:".$_SERVER['REQUEST_URI'],true);
        } catch(Exception $e) {
            $msg=$e->getMessage();
        }
	}
	if(isset($_POST["update"]))	{
	   $update=array(
                    'title'=>$title,'price_reduce'=>$price_reduce,'content'=>$content,
                    'feature'=>$feature,'price'=>$price,'video'=>$video,
                    'meta_keyword'=>$meta_kw,'meta_description'=>$meta_desc,
                    'pId'=>$pId,'home'=>$home,
                    'active'=>$active
                );
        try{
            $db->where('id',$_POST['idLoad']);
            $db->update($table,$update);
            header("location:".$_SERVER['REQUEST_URI'],true);
        } catch (Exception $e){
            $msg=$e->getMessage();
        }
	}

	if(isset($_POST["Del"])&&$_POST["Del"]==1) {
        $db->where('id',$_POST['idLoad']);
        try{
           $db->delete($table);
           header("location:".$_SERVER['REQUEST_URI'],true);
        } catch(Exception $e) {
            $msg=$e->getMessage();
        }
	}
    
    $page_head= array(
                    array('#','Danh sách phụ tùng')
                );

	$str=$form->breadcumb($page_head);
	$str.=$form->message($msg);
    
    $str.=$form->search_area($db,$act,'accessary_cate',$_GET['hint'],1);

    $head_title=array('Tiêu đề','Hình ảnh','Danh mục','Trang chủ','Hiển thị');
	$str.=$form->table_start($head_title);
    
    $page=isset($_GET["page"])?intval($_GET["page"]):1;
    if(isset($_GET['hint'])) $db->where('title','%'.$_GET['hint'].'%','LIKE'); 
    if(isset($_GET['cate_lev_1'])&&intval($_GET['cate_lev_1'])>0){
        $db->where('pId',intval($_GET['cate_lev_1']));
    }
    $db->orderBy('id');
    $db->pageLimit=ad_lim;
    $list=$db->paginate($table,$page);
    $count=$db->totalCount;
    
    if($db->count!=0){
        $db_sub=$db;
        foreach($list as $item){
            $cate=$db->where('id',$item['pId'])->getOne('accessary_cate','id,title');
            $img=$db->where('pId',$item['id'])->orderBy('ind','asc')->getOne('accessary_image','img');
            if(trim($img['img'])==='') $img='holder.js/130x100';else $img=myPath.$img['img'];   
            $item_content = array(
                array($item['title'],'text'),
                array($img,'image'),
                array(array($cate),'cate'),
                array($item['home'],'bool'),
                array($item['active'],'bool')
            );
            $addition=array(
                array('variable'=>array('act'=>$act,'type'=>$type,'id'=>$item['id']),'icon'=>'upload')
            );
            $str.=$form->table_body($item['id'],$item_content,$addition);
        }
    }
	$str.=$form->table_end();                            
    $str.=$form->pagination($page,ad_lim,$count);
	$str.='
	<form role="form" class="form" id="actionForm" name="actionForm" enctype="multipart/form-data" action="" method="post" data-toggle="validator">
	<div class="row">
    	<div class="col-lg-12"><h3>Cập nhật - Thêm mới thông tin</h3></div>
        
        <div class="col-lg-12">
            '.$form->text('title',array('label'=>'Tên SP')).'
            '.$form->cate_group($db,$table='accessary_cate',1).'
            '.$form->number('price',array('label'=>'Giá Bán')).'
            '.$form->number('price_reduce',array('label'=>'Giá Khuyến Mãi')).'
            '.$form->text('meta_keyword',array('label'=>'Keyword <code>SEO</code>')).'
            '.$form->textarea('meta_description',array('label'=>'Description <code>SEO</code>')).'
            '.$form->ckeditor('feature',array('label'=>'Điểm nổi bật')).'
            '.$form->ckeditor('content',array('label'=>'Mô tả')).'
            '.$form->text('video',array('label'=>'Video<code>https://www.youtube.com/embed/<i style="color:#000">60g__iiYDPo</i></code>')).'
            '.$form->checkbox('active',array('label'=>'Hiển Thị','checked'=>true)).'
            '.$form->checkbox('home',array('label'=>'Trang chủ')).'
    	</div>
        
    	'.$form->hidden($btn['name'],$btn['value']).'
	</div>
	</form>
	';
	return $str;
}
function accessary_image($db){
    $msg='';
    $act='accessary';
    $type='accessary';
    $table='accessary_image';
    $pId=intval($_GET['id']);
    
    if(isset($_POST["Edit"])&&$_POST["Edit"]==1){
		$db->where('id',$_POST['idLoad']);
        $list = $db->getOne($table);
        $btn=array('name'=>'update','value'=>'Update');
        $form = new form($list);
	} else {
        $btn=array('name'=>'addNew','value'=>'Submit');
        $form = new form();
	}
	if(isset($_POST["addNew"])||isset($_POST["update"])) {
        $ind=intval($_POST['ind']);
        $active=$_POST['active']=="on"?1:0;
        $file=time().$_FILES['file']['name'];
	}
    if(isset($_POST['listDel'])&&$_POST['listDel']!=''){
        $list = explode(',',$_POST['listDel']);
        foreach($list as $item){
            $db->where('id',intval($item));
            try{
               $db->delete($table);
            } catch(Exception $e) {
                $msg=$e->getMessage();
            }
        }
        header("location:".$_SERVER['REQUEST_URI'],true);
    }
	if(isset($_POST["addNew"])) {
        $insert = array('ind'=>$ind,'active'=>$active,'pId'=>$pId);
		try{
            $recent = $db->insert($table,$insert);
            if(common::file_check($_FILES['file'])){
                WideImage::load('file')->resize(800, 600, 'fill')->saveToFile(myPath.$file);
                WideImage::load(myPath.$file)->resize(400, 300, 'fill')->saveToFile(myPath.'thumb_'.$file);
                $db->where('id',$recent);
                $db->update($table,array('img'=>$file));
            }
            header("location:".$_SERVER['REQUEST_URI'],true);
        } catch(Exception $e) {
            $msg=$e->getMessage();
        }
	}
	if(isset($_POST["update"]))	{
	   $update=array('ind'=>$ind,'active'=>$active);
       if(common::file_check($_FILES['file'])){
            WideImage::load('file')->resize(800, 600, 'fill')->saveToFile(myPath.$file);
            WideImage::load(myPath.$file)->resize(400, 300, 'fill')->saveToFile(myPath.'thumb_'.$file);
            $update = array_merge($update,array('img'=>$file));
            $db->where('id',$_POST['idLoad']);
            $last_img = $db->getOne($table,'img');
            if($last_img['img']!='') unlink(myPath.$last_img['img']);
        }
        try{
            $db->where('id',$_POST['idLoad']);
            $db->update($table,$update);
            header("location:".$_SERVER['REQUEST_URI'],true);
        } catch (Exception $e){
            $msg=$e->getMessage();
        }
	}

	if(isset($_POST["Del"])&&$_POST["Del"]==1) {
        $db->where('id',$_POST['idLoad']);
        try{
           $db->delete($table);
           header("location:".$_SERVER['REQUEST_URI'],true);
        } catch(Exception $e) {
            $msg=mysql_error();
        }
	}
    $db->where('id',$pId);
    $pd=$db->getOne('accessary','id,title,pId');
    $db->where('id',$pd['pId']);
    $cate=$db->getOne('accessary_cate','id,title');

    $page_head= array(
                    array('#','Hình ảnh phụ tùng'),
                    array('main.php?act='.$act.'&type='.$type,$pd['title'].' <code><i class="fa fa-backward"></i></code>')
                );
	$str=$form->breadcumb($page_head);
	$str.=$form->message($msg);
    $head_title=array('Hình ảnh','Thứ tự','Hiển thị');
	$str.=$form->table_start($head_title);
    
    $page=isset($_GET["page"])?intval($_GET["page"]):1;
	$db->where('pId',$pId);
    $db->pageLimit=ad_lim;
	$list=$db->paginate($table,$page);
	$count= $db->totalCount;
	
	
    if($count>0){
        foreach($list as $item){
            $item_content = array(
                array(myPath.$item['img'],'image'),
                array($item['ind'],'text'),
                array($item['active'],'bool')
            );
            $str.=$form->table_body($item['id'],$item_content);
        }
    }
    $str.=$form->table_end();                            
    $str.=$form->pagination($page,ad_lim,$count);
	$str.='
	<form role="form" id="actionForm" name="actionForm" enctype="multipart/form-data" action="" method="post" data-toggle="validator">
	<div class="row">
    	<div class="col-lg-12"><h3>Cập nhật - Thêm mới thông tin</h3></div>
        <div class="col-lg-12">
            '.$form->file('file',array('label'=>'Hình ảnh <code>( 800 x 600 )</code>')).'
            '.$form->number('ind',array('label'=>'Thứ tự','required'=>true)).'
            '.$form->checkbox('active',array('label'=>'Hiển Thị','checked'=>true)).'
        </div>
    	'.$form->hidden($btn['name'],$btn['value']).'
	</div>
	</form>
	';
	return $str;
}
?>
