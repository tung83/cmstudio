<?php
function mainProcess($db)
{
    if(isset($_GET['pdId'])) return product_image($db);
    else if(isset($_GET['id'])) return product($db);
    else if(isset($_GET['pId'])) return cate_sub($db);
	else return cate($db);	
}
function cate($db)
{
	$msg='';
    $act='category';
    $table='category';
    $lev=1;
    $pId=0;
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
        $ind=intval($_POST['ind']);
        $active=$_POST['active']=="on"?1:0;
        $file=time().$_FILES['file']['name'];
        $icon=time().$_FILES['icon']['name'];
	}
    if(isset($_POST['listDel'])&&$_POST['listDel']!=''){
        $list = explode(',',$_POST['listDel']);
        foreach($list as $item){
            $db->where('id',intval($item))->where('pId',intval($item),'OR');
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
                    'title'=>$title,'e_title'=>$e_title,'ind'=>$ind,
                    'active'=>$active,'meta_keyword'=>$meta_kw,
                    'meta_description'=>$meta_desc,'pId'=>$pId,
                    'lev'=>$lev,
                    'e_meta_keyword'=>$e_meta_kw,'e_meta_description'=>$e_meta_desc
                );
		try{
            $recent = $db->insert($table,$insert);
            if($form->file_chk($_FILES['file'])){
                WideImage::load('file')->resize(538,273, 'fill')->saveToFile(myPath.$file);
                $db->where('id',$recent);
                $db->update($table,array('img'=>$file));
            }
            if($form->file_chk($_FILES['icon'])){
                WideImage::load('icon')->resize(77,85, 'fill')->saveToFile(myPath.$icon);
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
        if($form->file_chk($_FILES['file'])){
            WideImage::load('file')->resize(538,273, 'fill')->saveToFile(myPath.$file);
            $update = array_merge($update,array('img'=>$file));
            $form->img_remove($_POST['idLoad'],$db,$table);
        }
        if($form->file_chk($_FILES['icon'])){
            WideImage::load('icon')->resize(77,85, 'fill')->saveToFile(myPath.$icon);
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
        $db->where('id',$_POST['idLoad'])->where('pId',$_POST['idLoad'],'OR');
        try{
           $db->delete($table); 
           header("location:".$_SERVER['REQUEST_URI'],true);
        } catch(Exception $e) {
            $msg=mysql_error();
        }
	}
    $page_head= array(
                    array('#','Danh mục SP')
                );
	$str=$form->breadcumb($page_head);
	$str.=$form->message($msg);
    $head_title=array('Tiêu đề','Icon','Hình nền','Thứ tự','Hiển thị');
	$str.=$form->table_head($head_title);
	
    $page=isset($_GET["page"])?intval($_GET["page"]):1;
    $db->pageLimit=ad_lim;
    $db->where('pId',$pId)->where('lev',$lev);
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
                '<img src="'.myPath.$item['img'].'" class="img-thumbnail img-admin"/>',
                $item['ind'],
                $active
            );
            if(isset($_POST['Edit'])==1&&$_POST['idLoad']==$item_id) $change=true;
            else $change=false;
            $addition=array(
                array('direction'=>'main.php?act='.$act.'&pId='.$item_id,'icon'=>'eye-open')
            );
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
    $pg->defaultUrl = "main.php?act=$act";
    $pg->paginationUrl = "main.php?act=$act&page=[p]";
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
            '.$form->text('ind','Thứ tự','',true).'        
            '.$form->file('icon','Icon <code>( 77 x 85 )</code>').'
            '.$form->file('file','Hình nền <code>( 538 x 273 )</code>').'
            '.$form->checkbox('active','Hiển Thị','',true).'
        </div>
		'.$form->hidden($_POST['idLoad'],$btn['name'],$btn['value']).'
	</div>
	</form>
	';	
	return $str;	
}
function cate_sub($db){
    $msg='';
    $act='category';
    $table='category';
    $lev=2;
    $pId=intval($_GET['pId']);
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
        $ind=intval($_POST['ind']);
        $active=$_POST['active']=="on"?1:0;
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
                    'title'=>$title,'e_title'=>$e_title,'ind'=>$ind,
                    'active'=>$active,'meta_keyword'=>$meta_kw,
                    'meta_description'=>$meta_desc,'pId'=>$pId,
                    'lev'=>$lev,
                    'e_meta_keyword'=>$e_meta_kw,'e_meta_description'=>$e_meta_desc
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
                    'title'=>$title,'e_title'=>$e_title,'ind'=>$ind,
                    'active'=>$active,'meta_keyword'=>$meta_kw,
                    'meta_description'=>$meta_desc,
                    'e_meta_keyword'=>$e_meta_kw,'e_meta_description'=>$e_meta_desc
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
            $msg=mysql_error();
        }
	}
    $db->where('id',$pId);
    $pName=$db->getOne($table,'title');
    $page_head= array(
                    array('main.php?act='.$act,'Danh mục SP'),
                    array('#',$pName['title'])
                );
	$str=$form->breadcumb($page_head);
	$str.=$form->message($msg);
    $head_title=array('Tiêu đề','Thứ tự','Hiển thị');
	$str.=$form->table_head($head_title);
	
    $page=isset($_GET["page"])?intval($_GET["page"]):1;
    $db->pageLimit=ad_lim;
    $db->where('pId',$pId)->where('lev',$lev);
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
                $item['ind'],
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
    $pg->defaultUrl = "main.php?act=$act&pId=$pId";
    $pg->paginationUrl = "main.php?act=$act&pId=$pId&page=[p]";
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
            '.$form->text('ind','Thứ tự','',true).'        
            '.$form->checkbox('active','Hiển Thị','',true).'
        </div>
		'.$form->hidden($_POST['idLoad'],$btn['name'],$btn['value']).'
	</div>
	</form>
	';	
	return $str;
}
function product($db){
    $msg='';
    $act='category';
    $table='product';
    $pId=intval($_GET['id']);
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
        $feature=htmlspecialchars($_POST['feature']);
        $meta_kw=htmlspecialchars($_POST['meta_keyword']);
        $meta_desc=htmlspecialchars($_POST['meta_description']);
        $detail=str_replace("'","",$_POST['detail']);
        $price=intval($_POST['price']);
        $price_reduce=intval($_POST['price_reduce']);
        $in_stock=intval($_POST['in_stock']);
        $condition=$_POST['condition']=="on"?1:0;
        $active=$_POST['active']=="on"?1:0;
        $home=$_POST['home']=='on'?1:0;
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
                    'title'=>$title,'feature'=>$feature,'price'=>$price,
                    'active'=>$active,'meta_keyword'=>$meta_kw,
                    'meta_description'=>$meta_desc,'pId'=>$pId,
                    'price_reduce'=>$price_reduce,'home'=>$home,'condition'=>$condition,
                    'detail'=>$detail,'in_stock'=>$in_stock
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
                    'title'=>$title,'feature'=>$feature,'price'=>$price,
                    'active'=>$active,'meta_keyword'=>$meta_kw,
                    'meta_description'=>$meta_desc,'condition'=>$condition,
                    'price_reduce'=>$price_reduce,'home'=>$home,
                    'detail'=>$detail,'in_stock'=>$in_stock
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
            $msg=mysql_error();
        }
	}
    $db->where('id',$pId);
    $cate_sub=$db->getOne('category','id,title,pId');
    $db->where('id',$cate_sub['pId']);
    $cate=$db->getOne('category','id,title');
    $page_head= array(
                    array('main.php?act='.$act,'Danh mục SP'),
                    array('main.php?act='.$act.'&pId='.$cate['id'],$cate['title']),
                    array('#',$cate_sub['title'])
                );
	$str=$form->breadcumb($page_head);
	$str.=$form->message($msg);
    $head_title=array('Tiêu đề','Đơn giá(VNĐ)','KM (VNĐ)','Hiển thị');
	$str.=$form->table_head($head_title);
    
	$page=isset($_GET["page"])?intval($_GET["page"]):1;
    $db->pageLimit=ad_lim;
    $db->where('pId',$pId);
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
                number_format($item['price'],0,'.',','),
                number_format($item['price_reduce'],0,'.',','),
                $active
            );
            if(isset($_POST['Edit'])==1&&$_POST['idLoad']==$item_id) $change=true;
            else $change=false;
            $addition=array(
                array('direction'=>'main.php?act='.$act.'&pdId='.$item_id,'icon'=>'upload')
            );
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
    $pg->defaultUrl = "main.php?act=$act&id=$pId";
    $pg->paginationUrl = "main.php?act=$act&id=$pId&page=[p]";
    $str.= $pg->process();
	$str.='			
			</div>
		</div>
		<!-- Row -->
		<form role="form" id="actionForm" name="actionForm" enctype="multipart/form-data" action="" method="post" data-toggle="validator">
		<div class="row">
		<div class="col-lg-12"><h3>Cập nhật - Thêm mới thông tin</h3></div>
        <div class="col-lg-6">	            
            '.$form->text('title','Tên SP').'
            '.$form->number('price','Đơn giá<code> VNĐ </code>','',true).'
            '.$form->number('price_reduce','Giá khuyến mãi<code> VNĐ </code>').'            
            '.$form->checkbox('condition','Mới / Cũ','',true).'   
            '.$form->checkbox('active','Hiển Thị','',true).'  
            '.$form->checkbox('home','Trang chủ','',true).'                			
		</div>    
        <div class="col-lg-6">
            '.$form->text('meta_keyword','Keyword <code>SEO</code>').'
            '.$form->textarea('meta_description','Description <code>SEO</code>').'
            '.$form->textarea('feature','Mô tả sơ lược sản phẩm').' 
            '.$form->number('in_stock','Số lượng hàng','',true).'           
        </div>
        <div class="col-lg-12">
            '.$form->ckeditor('detail','Chi tiết sản phẩm').'
        </div>
		'.$form->hidden($_POST['idLoad'],$btn['name'],$btn['value']).'
	</div>
	</form>
	';	
	return $str;
}
function product_image($db){
    $msg='';
    $act='category';
    $table='product_image';
    $pId=intval($_GET['pdId']);
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
                $msg=mysql_error();
            }
        }
        header("location:".$_SERVER['REQUEST_URI'],true);
    }
	if(isset($_POST["addNew"])) {
        $insert = array('ind'=>$ind,'active'=>$active,'pId'=>$pId);
		try{
            $recent = $db->insert($table,$insert);
            if($form->file_chk($_FILES['file'])){
                WideImage::load('file')->resize(566, 500, 'fill')->saveToFile(myPath.$file);
                $db->where('id',$recent);
                $db->update($table,array('img'=>$file));
            }
            header("location:".$_SERVER['REQUEST_URI'],true); 
        } catch(Exception $e) {
            $msg=mysql_error();
        }			
	}
	if(isset($_POST["update"]))	{
	   $update=array('ind'=>$ind,'active'=>$active);
       if($form->file_chk($_FILES['file'])){
            WideImage::load('file')->resize(566, 500, 'fill')->saveToFile(myPath.$file);
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
            $msg = $e->getErrorMessage();
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
    $pd=$db->getOne('product','id,title,pId');
    $db->where('id',$pd['pId']);
    $cate_sub=$db->getOne('category','id,title,pId');
    $db->where('id',$cate_sub['pId']);
    $cate=$db->getOne('category','id,title');
    $page_head= array(
                    array('main.php?act='.$act,'Danh mục SP'),
                    array('main.php?act='.$act.'&pId='.$cate['id'],$cate['title']),
                    array('main.php?act='.$act.'&id='.$cate_sub['id'],$cate_sub['title']),
                    array('#',$pd['title'])
                );
	$str=$form->breadcumb($page_head);
	$str.=$form->message($msg);
    $head_title=array('Hình ảnh','Thứ tự','Hiển thị');
	$str.=$form->table_head($head_title);
	
    $page=isset($_GET["page"])?intval($_GET["page"]):1;
    $db->pageLimit=ad_lim;
    $db->where('pId',$pId);
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
                '<img src="'.myPath.$item['img'].'" class="img-thumbnail" style="max-height:100px"/>',
                $item['ind'],
                $active
            );
            if(isset($_POST['Edit'])==1&&$_POST['idLoad']==$item_id) $change=true;
            else $change=false;
            $str.=$form->table_body($item_id,$item_content,$change,$_SERVER['REQUEST_URI']);      
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
    $pg->defaultUrl = "main.php?act=$act&pdId=$pId";
    $pg->paginationUrl = "main.php?act=$act&pdId=$pId&page=[p]";
    $str.= $pg->process();
	$str.='			
			</div>
		</div>
		<!-- Row -->
		<form role="form" id="actionForm" name="actionForm" enctype="multipart/form-data" action="" method="post" data-toggle="validator">
		<div class="row">
		<div class="col-lg-12"><h3>Cập nhật - Thêm mới thông tin</h3></div>   
        <div class="col-lg-12">
            '.$form->file('file','Hình ảnh <code>( 566 x 500 )</code>').'
            '.$form->number('ind','Thứ tự','',true).'
            '.$form->checkbox('active','Hiển Thị','',true).'           
        </div>
		'.$form->hidden($_POST['idLoad'],$btn['name'],$btn['value']).'
	</div>
	</form>
	';	
	return $str;
}
?>