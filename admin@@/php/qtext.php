<?php
function mainProcess($db)
{
	return qtext($db);	
}
function qtext($db)
{
	$msg='';
    $act='qtext';
    $table='qtext';
    $id=intval($_GET['id']);

	$db->where('id',$id);
    $item = $db->getOne($table);
    $btn=array('name'=>'update','value'=>'Update');
    $form = new form($item);

	if(isset($_POST["addNew"])||isset($_POST["update"])) {
        $content=str_replace("'","",$_POST['content']);
        $e_content=str_replace("'","",$_POST['e_content']);
	}
	if(isset($_POST["update"]))	{
        $update=array(
            'content'=>$content,'e_content'=>$e_content
        );       
        try{
            $db->where('id',$id);
            $db->update($table,$update);  
            header("location:".$_SERVER['REQUEST_URI'],true);   
        } catch (Exception $e){
            $msg = $e->getErrorMessage();
        }
	}
    $page_head= array(
                    array('#','Quản lý bài viết'),
                    array('#',$item['title'])
                );
	$str=$form->breadcumb($page_head);
	$str.=$form->message($msg);
	$str.='			
		<!-- Row -->
		<form role="form" id="actionForm" name="actionForm" enctype="multipart/form-data" action="" method="post" data-toggle="validator">
		<div class="row">
		<div class="col-lg-12"><h3>Cập nhật - Thêm mới thông tin</h3></div>
        <div class="col-lg-12">
            <ul class="nav nav-tabs">
    			<li class="active"><a href="#vietnamese" data-toggle="tab">Việt Nam</a></li>
    			<li><a href="#english" data-toggle="tab">English</a></li>
    		</ul>
            <div class="tab-content">
    			<div class="tab-pane bg-vi active" id="vietnamese">
                    '.$form->ckeditor('content',array('label'=>'Nội dung')).'
                </div>
                <div class="tab-pane bg-en" id="english">
                    '.$form->ckeditor('e_content',array('label'=>'Nội dung')).'
                </div>
            </div>
        </div>
		'.$form->hidden($btn['name'],$btn['value']).'
	</div>
	</form>
	';	
	return $str;	
}

?>		