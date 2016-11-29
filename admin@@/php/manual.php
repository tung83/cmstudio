<?php
function mainProcess($db)
{
    $type=$_GET['type'];
    switch($type){
        case 'manual_cate': return manual_cate($db,$type);break;
        case 'manual': return manual($db,$type);break;
        default:return manual($db,$type);break;
    }
}
function manual_cate($db,$type)
{
	$msg='';
    $act='manual';
    $table='manual_cate';
    if(isset($_POST["Edit"])&&$_POST["Edit"]==1){
		$db->where('id',$_POST['idLoad']);
        $list = $db->get($table);
        $btn=array('name'=>'update','value'=>'Update');
        $form = new form($list);
	} else {
        $btn=array('name'=>'addNew','value'=>'Submit');	
        $form = new form();
	}
	if(isset($_POST["addNew"])||isset($_POST["update"])) {
        $title=htmlspecialchars($_POST['title']);	   
        $e_title=htmlspecialchars($_POST['e_title']);
        $meta_kw=htmlspecialchars($_POST['meta_keyword']);
        $meta_desc=htmlspecialchars($_POST['meta_description']);
        $e_meta_kw=htmlspecialchars($_POST['e_meta_keyword']);
        $e_meta_desc=htmlspecialchars($_POST['e_meta_description']);
        $active=$_POST['active']=="on"?1:0;
        $icon=time().$_FILES['icon']['name'];
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
                    'title'=>$title,'e_title'=>$e_title,
                    'active'=>$active,'meta_keyword'=>$meta_kw,
                    'meta_description'=>$meta_desc,
                    'e_meta_keyword'=>$e_meta_kw,'e_meta_description'=>$e_meta_desc
                );
		try{
            $recent = $db->insert($table,$insert);
            if($form->file_chk($_FILES['icon'])){
                WideImage::load('icon')->resize(14,16, 'fill')->saveToFile(myPath.$icon);
                $db->where('id',$recent);
                $db->update($table,array('icon'=>$icon));
            }
            header("location:".$_SERVER['REQUEST_URI'],true); 
        } catch(Exception $e) {
            $msg=$e->getMessage();
        }			
	}
	if(isset($_POST["update"]))	{
        $update=array(
                    'title'=>$title,'e_title'=>$e_title,'ind'=>$ind,
                    'active'=>$active,'meta_keyword'=>$meta_kw,
                    'meta_description'=>$meta_desc,
                    'e_meta_keyword'=>$e_meta_kw,'e_meta_description'=>$e_meta_desc
                );
        if($form->file_chk($_FILES['icon'])){
            WideImage::load('icon')->resize(14,16, 'fill')->saveToFile(myPath.$icon);
            $update = array_merge($update,array('icon'=>$icon));
            $form->img_remove($_POST['idLoad'],$db,$table,'icon');
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
    $page_head= array(
                    array('#','Loại manual clip')
                );
	$str=$form->breadcumb($page_head);
	$str.=$form->message($msg);
    $head_title=array('Tiêu đề','Icon','Hiển thị');
	$str.=$form->table_head($head_title);
	
    $page=isset($_GET["page"])?intval($_GET["page"]):1;
    $db->pageLimit=ad_lim;
    $db->orderBy('id');
    $list=$db->paginate($table,$page);

    if($db->count!=0){
        foreach($list as $item){
            $item_id=$item['id'];
            if($item['active']==1){
                $active = '<span class="glyphicon glyphicon-ok"></span>';
            } else {
                $active='<span class="glyphicon glyphicon-remove"></span>';
            }
            $item_content = array(
                $item['title'],
                '<img src="'.myPath.$item['icon'].'" class="img-thumbnail img-admin"/>',
                $active
            );
            if(isset($_POST['Edit'])==1&&$_POST['idLoad']==$item_id) $change=true;
            else $change=false;
            $str.=$form->table_body($item_id,$item_content,$change,$_SERVER['REQUEST_URI'],$addition);      
        }
    }                               
	$str.='					
					</tbody>
				</table>
				</div>';
    $str.=$form->del_list();
    $pg = new Pagination();
    $pg->pagenumber = $page;
    $pg->pagesize = ad_lim;
    $pg->totalrecords = $db->totalCount;
    $pg->paginationstyle = 1; // 1: advance, 0: normal
    $pg->defaultUrl = "main.php?act=$act&type=$type";
    $pg->paginationUrl = "main.php?act=$act&type=$type&page=[p]";
    $str.= $pg->process();
	$str.='			
			</div>
		</div>
		<!-- Row -->
		<form role="form" id="actionForm" name="actionForm" enctype="multipart/form-data" action="" method="post" data-toggle="validator">
		<div class="row">
		<div class="col-lg-12"><h3>Cập nhật - Thêm mới thông tin</h3></div>
        <div class="col-lg-12 admin-tabs">
            <ul class="nav nav-tabs">
    			<li class="active"><a href="#vietnamese" data-toggle="tab">Việt Nam</a></li>
    			<li><a href="#english" data-toggle="tab">English</a></li>
    		</ul>
    		<div class="tab-content">
    			<div class="tab-pane bg-vi active" id="vietnamese">
                    '.$form->text('title','Tiêu đề').'
                    '.$form->text('meta_keyword','Keyword <code>SEO</code>').'
                    '.$form->textarea('meta_description','Description <code>SEO</code>').'
    			</div>
    			<div class="tab-pane bg-en" id="english">
                    '.$form->text('e_title','Tiêu đề').'
                    '.$form->text('e_meta_keyword','Keyword <code>SEO</code>').'
                    '.$form->textarea('e_meta_description','Description <code>SEO</code>').'
    			</div>
    		</div>
        </div>
        <div class="col-lg-12">
            '.$form->file('icon','Icon <code>( 14 x 16 )</code>').'
            '.$form->checkbox('active','Hiển Thị','',true).'
        </div>
		'.$form->hidden($_POST['idLoad'],$btn['name'],$btn['value']).'
	</div>
	</form>
	';	
	return $str;	
}
function manual($db,$type)
{
	$msg='';
    $act='manual';
    $type='manual';
    $table='manual';
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
        $e_title=htmlspecialchars($_POST['e_title']);
        /*$price=intval($_POST['price']);
        $price_reduce=intval($_POST['price_reduce']);*/
        /*$meta_kw=htmlspecialchars($_POST['meta_keyword']);
        $meta_desc=htmlspecialchars($_POST['meta_description']);
        $content=str_replace("'","",$_POST['content']);     
        $feature=str_replace("'","",$_POST['feature']);
        $manual=str_replace("'","",$_POST['manual']);
        $promotion=str_replace("'","",$_POST['promotion']);*/
        $manual=htmlspecialchars($_POST['manual']);
        $active=$_POST['active']=="on"?1:0;
        /*$home=$_POST['home']=='on'?1:0;
        $pId=intval($_POST['frm_cate_1']);*/
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
                    'title'=>$title,'e_title'=>$e_title,'manual'=>$manual,
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
                    'title'=>$title,'e_title'=>$e_title,'manual'=>$manual,
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
                    array('#','Danh sách hướng dẫn')
                );

	$str=$form->breadcumb($page_head);
	$str.=$form->message($msg);
    
    $str.=$form->search_area($db,$act,'manual_cate',$_GET['hint'],0);

    $head_title=array('Tiêu đề','Hình ảnh','Hiển thị');
	$str.=$form->table_start($head_title);
    
    $page=isset($_GET["page"])?intval($_GET["page"]):1;
    if(isset($_GET['hint'])) $db->where('title','%'.$_GET['hint'].'%','LIKE'); 
    if(isset($_GET['cate_lev_2'])&&intval($_GET['cate_lev_2'])>0){
        $db->where('pId',intval($_GET['cate_lev_2']));
    }elseif(isset($_GET['cate_lev_1'])&&intval($_GET['cate_lev_1'])>0){
        $db_tmp=$db;
        $db_tmp->reset();
        $db_tmp->where('lev',2)->where('pId',intval($_GET['cate_lev_1']));
        $list=$db_tmp->get('product_cate',null,'id');
        foreach($list as $item){
            $list_tmp[]=$item['id'];
        }
        $db->where('pId',$list_tmp,'in');   
    }
    $db->orderBy('id');
    $db->pageLimit=ad_lim;
    $list=$db->paginate($table,$page);
    $count=$db->totalCount;
    if($db->count!=0){
        $db_sub=$db;
        foreach($list as $item){
            if(trim($item['video'])==='') $img='holder.js/130x100';
            else $img='http://img.youtube.com/vi/'.$item['video'].'/1.jpg';   
            $item_content = array(
                array($item['title'].'<br/><code>'.$item['e_title'].'</code>','text'),
                array($img,'image'),
                array($item['active'],'bool')
            );
            $str.=$form->table_body($item['id'],$item_content);
        }
    }
	$str.=$form->table_end();                            
    $str.=$form->pagination($page,ad_lim,$count);
	$str.='
	<form role="form" class="form" id="actionForm" name="actionForm" enctype="multipart/form-data" action="" method="post" data-toggle="validator">
	<div class="row">
    	<div class="col-lg-12"><h3>Cập nhật - Thêm mới thông tin</h3></div>
        
        <div class="col-lg-12">
            <ul class="nav nav-tabs">
    			<li class="active"><a href="#vietnamese" data-toggle="tab">Việt Nam</a></li>
    			<li><a href="#english" data-toggle="tab">English</a></li>
    		</ul>
    		<div class="tab-content">
    			<div class="tab-pane bg-vi active" id="vietnamese">
            '.$form->text('title',array('label'=>'Tiêu đề')).'
                </div>
                <div class="tab-pane bg-en" id="english">
            '.$form->text('e_title',array('label'=>'Tiêu đề')).'
                </div>
            </div>
            '.$form->text('manual',array('label'=>'manual<code>https://www.youtube.com/embed/<i style="color:#000">60g__iiYDPo</i></code>')).'
            '.$form->checkbox('active',array('label'=>'Hiển Thị','checked'=>true)).'
    	</div>
        
    	'.$form->hidden($btn['name'],$btn['value']).'
	</div>
	</form>
	';
	return $str;	
}
?>