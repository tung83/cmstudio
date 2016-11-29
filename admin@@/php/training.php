<?php
function mainProcess($db)
{
    if(isset($_GET['id'])) return product_image($db);
    else if(isset($_GET['pId'])) return training($db);
    else return cate($db);
}
function cate($db)
{
	$msg='';
    $act='training';
    $table='training_cate';
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
        $meta_kw=htmlspecialchars($_POST['meta_keyword']);
        $meta_desc=htmlspecialchars($_POST['meta_description']);
        $ind=intval($_POST['ind']);
        $active=$_POST['active']=="on"?1:0;
        $file=time().$_FILES['file']['name'];
        //$icon=time().$_FILES['icon']['name'];
	}
    if(isset($_POST['listDel'])&&$_POST['listDel']!=''){
        $list = explode(',',$_POST['listDel']);
        foreach($list as $item){
            $db->where('id',intval($item))->where('pId',intval($item),'=','OR');
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
            'title'=>$title,'ind'=>$ind,
            'active'=>$active,'meta_keyword'=>$meta_kw,
            'meta_description'=>$meta_desc,
        );
		try{
            $recent = $db->insert($table,$insert);
            if(common::file_check($_FILES['file'])){
                WideImage::load('file')->resize(114,114, 'fill')->saveToFile(myPath.$file);
                $db->where('id',$recent);
                $db->update($table,array('img'=>$file));
            }
            header("location:".$_SERVER['REQUEST_URI'],true); 
        } catch(Exception $e) {
            $msg=$e->getMessage();
        }			
	}
	if(isset($_POST["update"]))	{
	   $update=array(
            'title'=>$title,'ind'=>$ind,
            'active'=>$active,'meta_keyword'=>$meta_kw,
            'meta_description'=>$meta_desc
        );
        if(common::file_check($_FILES['file'])){
            WideImage::load('file')->resize(114,114, 'fill')->saveToFile(myPath.$file);
            $update = array_merge($update,array('img'=>$file));
            $form->img_remove($_POST['idLoad'],$db,$table);
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
        $db->where('id',$_POST['idLoad'])->where('pId',$_POST['idLoad'],'=','OR');
        try{
           $db->delete($table); 
           header("location:".$_SERVER['REQUEST_URI'],true);
        } catch(Exception $e) {
            $msg=mysql_error();
        }
	}
    $page_head= array(
                    array('#','Danh mục đào tạo')
                );
	$str=$form->breadcumb($page_head);
	$str.=$form->message($msg);
    $head_title=array('Tiêu đề','Hình ảnh','Thứ tự','Hiển thị');
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
            $img=($item['img']==='')?'holder.js/114x144':myPath.$item['img'];
            $item_content = array(
                $item['title'],
                '<img src="'.$img.'" class="img-responsive img-thumbnail img-admin" />',
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
    $pg = new Pagination(array('page'=>$page,'limit'=>ad_lim,'count'=>$count,'type'=>1));
    $pg->set_url(array('def'=>'main.php?act='.$act,'url'=>'main.php?act='.$act.'&page=[p]'));
    $str.= $pg->process();
	$str.='			
			</div>
		</div>
		<!-- Row -->
		<form role="form" id="actionForm" name="actionForm" enctype="multipart/form-data" action="" method="post" data-toggle="validator">
		<div class="row">
		<div class="col-lg-12"><h3>Cập nhật - Thêm mới thông tin</h3></div>
        <div class="col-lg-12">
            '.$form->text('title',array('label'=>'Tiêu đề')).'
            '.$form->text('meta_keyword',array('label'=>'Keyword <code>SEO</code>')).'
            '.$form->textarea('meta_description',array('label'=>'Description <code>SEO</code>')).'
            '.$form->text('ind',array('label'=>'Thứ tự','required'=>true)).'        
            '.$form->file('file',array('label'=>'Hình Ảnh<code>(114 x 114)</code>')).'
            '.$form->checkbox('active',array('label'=>'Hiển Thị','checked'=>true)).'
        </div>
		'.$form->hidden($_POST['idLoad'],$btn['name'],$btn['value']).'
	</div>
	</form>
	';	
	return $str;	
}
function training($db){
    $msg='';
    $act='training';
    $table='training';
    $pId=intval($_GET['pId']);
    $hint=htmlspecialchars($_GET['hint']);
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
        $sum=htmlspecialchars($_POST['sum']);
        $meta_kw=htmlspecialchars($_POST['meta_keyword']);
        $meta_desc=htmlspecialchars($_POST['meta_description']);
        $content=str_replace("'","",$_POST['content']);
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
        $insert = array(
                    'title'=>$title,'content'=>$content,'sum'=>$sum,
                    'meta_keyword'=>$meta_kw,'meta_description'=>$meta_desc,
                    'pId'=>$pId,'active'=>$active
                );
		try{
            $recent=$db->insert($table,$insert);
            if(common::file_check($_FILES['file'])){
                WideImage::load('file')->resize(600,600, 'fill')->saveToFile(myPath.$file);
                $db->where('id',$recent);
                $db->update($table,array('img'=>$file));
            }
            header("location:".$_SERVER['REQUEST_URI'],true);
        } catch(Exception $e) {
            $msg=$e->getMessage();
        }
	}
	if(isset($_POST["update"]))	{
	   $update=array(
                    'title'=>$title,'content'=>$content,'sum'=>$sum,
                    'meta_keyword'=>$meta_kw,'meta_description'=>$meta_desc,
                    'pId'=>$pId,'active'=>$active
                );
        try{
            if(common::file_check($_FILES['file'])){
                WideImage::load('file')->resize(600,600, 'fill')->saveToFile(myPath.$file);
                $update = array_merge($update,array('img'=>$file));
                $form->img_remove($_POST['idLoad'],$db,$table);
            }
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
    $cate=$db->getOne('training_cate','title');
    $page_head= array(
                    array('main.php?act='.$act,'Danh mục đào tạo'),
                    array('#',$cate['title'])
                );

	$str=$form->breadcumb($page_head);
	$str.=$form->message($msg);

    $head_title=array('Tiêu đề','Hình ảnh','Hiển thị');
	$str.=$form->table_head($head_title);
    
    $page=isset($_GET["page"])?intval($_GET["page"]):1;
    $db->where('pId',$pId);
    $db->orderBy('id');
    $db->pageLimit=ad_lim;
    $list=$db->paginate($table,$page);
    $count=$db->totalCount;
    if($db->count!=0){
        $db_sub=$db;
        foreach($list as $item){
            $item_id=$item['id'];
            if($item['active']==1){
                $active = '<span class="glyphicon glyphicon-ok"></span>';
            } else {
                $active='<span class="glyphicon glyphicon-remove"></span>';
            }
            $img=($item['img']==='')?'holder.js/100x100':myPath.$item['img'];        
            $item_content = array(
                $item['title'],
                '<img src="'.$img.'" class="img-admin img-thumbnail img-responsive"/>',
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
    $pg = new Pagination(array('page'=>$page,'limit'=>ad_lim,'count'=>$count,'type'=>1));
    $pg->set_url(array('def'=>'main.php?act='.$act.'&pId='.$pId,'url'=>'main.php?act='.$act.'&pId='.$pId.'&page=[p]'));
    $str.= $pg->process();
	$str.='
			</div>
		</div>
		<!-- Row -->
		<form role="form" class="form" id="actionForm" name="actionForm" enctype="multipart/form-data" action="" method="post" data-toggle="validator">
		<div class="row">
		<div class="col-lg-12"><h3>Cập nhật - Thêm mới thông tin</h3></div>
        
        <div class="col-lg-12">
            '.$form->text('title',array('label'=>'Tiều đề','required'=>true)).'
            '.$form->textarea('sum',array('label'=>'Tóm tắt','required'=>true)).'
            '.$form->text('meta_keyword',array('label'=>'Keyword <code>SEO</code>')).'
            '.$form->textarea('meta_description',array('label'=>'Description <code>SEO</code>')).'
            '.$form->ckeditor('content',array('label'=>'Mô tả')).'
            '.$form->file('file',array('label'=>'Hình ảnh<code>(600 x 600)</code>')).'
            '.$form->checkbox('active',array('label'=>'Hiển Thị','checked'=>true)).'
		</div>
        
		'.$form->hidden($_POST['idLoad'],$btn['name'],$btn['value']).'
	</div>
	</form>
	';
	return $str;
}

?>
