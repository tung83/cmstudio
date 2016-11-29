<?php
function mainProcess($db)
{
    return seo($db);	
}
function seo($db)
{
	$msg='';
    $act='seo';
    $table='menu';
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
        $meta_kw=htmlspecialchars($_POST['meta_keyword']);
        $meta_desc=htmlspecialchars($_POST['meta_description']);
        $e_meta_kw=htmlspecialchars($_POST['e_meta_keyword']);
        $e_meta_desc=htmlspecialchars($_POST['e_meta_description']);
	}
    if(isset($_POST['addNew'])){
        header("location:".$_SERVER['REQUEST_URI'],true);     
    }
	if(isset($_POST["update"]))	{
	   $update=array(
                    'meta_keyword'=>$meta_kw,
                    'meta_description'=>$meta_desc,
                    'e_meta_keyword'=>$e_meta_kw,
                    'e_meta_description'=>$e_meta_desc
                );
        try{
            $db->where('id',$_POST['idLoad']);
            $db->update($table,$update);  
            header("location:".$_SERVER['REQUEST_URI'],true);   
        } catch (Exception $e){
            $msg=$e->getMessage();
        }
	}

    $page_head= array(
                    array('#','Pages SEO')
                );
	$str=$form->breadcumb($page_head);
	$str.=$form->message($msg);
    $head_title=array('Tên Trang','Meta keyword','Meta Description');
	$str.=$form->table_start($head_title);
	
    $page=isset($_GET["page"])?intval($_GET["page"]):1;
    $db->pageLimit=ad_lim;
    $list=$db->paginate($table,$page);

    if($db->count!=0){
        foreach($list as $item){
            $item_content = array(        
                array($item['title'],'text'),
                array($item['meta_keyword'],'text'),
                array($item['meta_description'],'text')
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
            '.$form->text('title',array('label'=>'Tiêu đề','disabled'=>true)).'
            '.$form->text('meta_keyword',array('label'=>'Keyword <code>SEO</code>')).'
            '.$form->textarea('meta_description',array('label'=>'Description <code>SEO</code>')).'
        </div>
		'.$form->hidden($btn['name'],$btn['value']).'
	</div>
	</form>
	';	
	return $str;	
}
?>