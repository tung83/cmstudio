<?php
function mainProcess($db)
{
	return support_online($db);	
}
function support_online($db)
{
	$msg='';
    $act='support_online';
    $table='support_online';
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
        $yahoo=htmlspecialchars($_POST['yahoo']);	
        $skype=htmlspecialchars($_POST['skype']);
        $phone=htmlspecialchars($_POST['phone']);
        $active=$_POST['active']=="on"?1:0;
	}
    if(isset($_POST['listDel'])&&$_POST['listDel']!=''){
        $list = explode(',',$_POST['listDel']);
        foreach($list as $item){
            $form->img_remove(intval($item),$db,$table);
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
            'title'=>$title,'skype'=>$skype,'phone'=>$phone,
            'yahoo'=>$yahoo,'active'=>$active
        );
		try{
            $db->insert($table,$insert);
            header("location:".$_SERVER['REQUEST_URI'],true); 
        } catch(Exception $e) {
            $msg=mysql_error();
        }			
	}
	if(isset($_POST["update"]))	{
        $update=array(
            'title'=>$title,'skype'=>$skype,'phone'=>$phone,
            'yahoo'=>$yahoo,'active'=>$active
        );
        try{
            $db->where('id',$_POST['idLoad']);
            $db->update($table,$update);  
            header("location:".$_SERVER['REQUEST_URI'],true);   
        } catch (Exception $e){
            $msg = $e->getErrorMessage();
        }
	}
	
	if(isset($_POST["Del"])&&$_POST["Del"]==1) {
        try{
            $form->img_remove($_POST['idLoad'],$db,$table);
            $db->where('id',$_POST['idLoad']);
            $db->delete($table);            
            header("location:".$_SERVER['REQUEST_URI'],true);
        } catch(Exception $e) {
            $msg=$e->getErrorMessage();
        }
	}
    $page_head= array(
                    array('#','Quản lý hỗ trợ trực tuyến')
                );
	$str=$form->breadcumb($page_head);
	$str.=$form->message($msg);
    
    $str.=$form->search_area($db,$act,'category',$_GET['hint'],0);
    
    $head_title=array('Tiêu đề','Skype','Yahoo','Phone','Hiển thị');
	$str.=$form->table_start($head_title);
    
    $page=isset($_GET["page"])?intval($_GET["page"]):1;
    if(isset($_GET['hint'])) $db->where('title','%'.$_GET['hint'].'%','LIKE');  
    $db->orderBy('id');
    $db->pageLimit=$lim=ad_lim;
	$list=$db->paginate($table,$page);    
	$count= $db->totalCount;
	
    if($count!=0){
        foreach($list as $item){
            $item_content = array(            
                array($item['title'],'text'),
                array($item['skype'],'text'),
                array($item['yahoo'],'text'),
                array($item['phone'],'text'),
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
            '.$form->text('skype',array('label'=>'Nick Skype')).'
            '.$form->text('yahoo',array('label'=>'Nick Yahoo')).'
            '.$form->text('phone',array('label'=>'Số điện thoại')).'
            '.$form->checkbox('active',array('label'=>'Hiển Thị','checked'=>true)).'
        </div>
	   '.$form->hidden($btn['name'],$btn['value']).'
	</div>
	</form>
	';	
	return $str;	
}

?>		