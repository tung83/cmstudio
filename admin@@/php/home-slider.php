<?php
function mainProcess($db)
{
	return slider($db);	
}
function slider($db)
{
	$msg='';
    $table='slider';
	if(isset($_POST["addNew"])||isset($_POST["update"]))
	{
		$email=htmlspecialchars($_POST['email']);
		$pwd = md5($_POST['pwd']);	
	}
	if(isset($_POST["addNew"]))
	{
        $insert = array('email'=>$email,'pwd'=>$pwd,'lastOnl'=>date( 'Y-m-d H:i:s' ));
		try{
            $db->where('email',$email);            
            if($db->has($table)>=1){
                $msg='Email này đã được sử dụng!!';
            } else {
                $db->insert($table,$insert);
                header("location:".$_SERVER['REQUEST_URI'],true);
            }            
        }
        catch(Exception $e){
            $msg=mysql_error();
        }			
	}
	if(isset($_POST["update"]))
	{
        try{
            $db->where('id',$_POST['idLoad']);
            $db->update($table,array('email'=>$email,'pwd'=>$pwd));  
            header("location:".$_SERVER['REQUEST_URI'],true);          
        }
        catch(Exception $e){
            $msg=mysql_error();
        }
	}
	if(isset($_POST["Edit"])&&$_POST["Edit"]==1)
	{
		$sql="select * from ad_user where id=".$_POST["idLoad"];
        $list = $db->rawQuery($sql);
        $form = new form($list);
	} else {
	   $form = new form();
	}
	if(isset($_POST["Del"])&&$_POST["Del"]==1)
	{
        $db->where('id',$_POST['idLoad']);
        try{
           $db->delete($table); 
           header("location:".$_SERVER['REQUEST_URI'],true);
        }
        catch(Exception $e){
            $msg=mysql_error();
        }
	}
    $page_head= array(
                    array('#','Home Slider')
                );
	$str='
	<!-- Page Heading -->
	<div class="row">
		<div class="col-lg-12">
			'.$form->breadcumb($page_head).'
		</div>
	</div>';
	if($msg!='')
	{
		$str.='<div class="alert alert-danger" role="alert" style="margin-top:10px">'.$msg.'</div>';	
	}
	$str.='
	<!-- Row -->
	<div class="row">
		 <div class="col-lg-12">
			<div class="table-responsive">
				<table class="table table-bordered table-hover table-striped">
					<thead>
						<tr>
							<th>ID</th>
							<th>Hình ảnh</th>
							<th>Tóm tắt</th>
                            <th>Hiển thị
							<th style="width:12% !important">Options</th>
						</tr>
					</thead>
					<tbody>
					';
	$s="select * from $table";
	$list = $db->rawQuery($s);
	$count= $db->count;
	$page=isset($_GET["page"])?intval($_GET["page"]):1;
	$lim=10;
	$start=($page-1)*$lim;
	$s.=" limit $start,$lim";
	$list = $db->rawQuery($s);
    if($db->count!=0){
        foreach($list as $item){
            if($item['active']==1){
                $active = '<span class="glyphicon glyphicon-ok"></span>';
            } else {
                $active='<span class="glyphicon glyphicon-remove"></span>';
            }
            $str.='
            <tr>
            	<td>'.$item['id'].'</td>
            	<td>'.$item['img'].'</td> 
            	<td>'.nl2br($item['sum']).'</td>
            	<td align="center">
            ';
            if(isset($_POST["Edit"])==1&&$_POST['idLoad']==$item['id'])
            {
        		$str.='
        		<a href="'.$_SERVER['REQUEST_URI'].'" class="glyphicon glyphicon-refresh" aria-hidden="true"></a>
        		';	
            }
            else
            {
            	$str.='
        		<a href="javascript:operationFrm('.$item['id'].",'E'".')" class="glyphicon glyphicon-pencil" aria-hidden="true"></a>
        		';		
            }            
            $str.='
            <a href="javascript:operationFrm('.$item['id'].",'D'".')" class="glyphicon glyphicon-trash" aria-hidden="true"></a>			  
            		</td>
            	</tr>
            	';	   
            }
    }                               
	$str.='					
					</tbody>
				</table>
				</div>';
    $pg = new Pagination();
    $pg->pagenumber = $page;
    $pg->pagesize = $lim;
    $pg->totalrecords = $count;
    $pg->paginationcss = "pagination-large";
    $pg->paginationstyle = 1; // 1: advance, 0: normal
    $pg->defaultUrl = "main.php?act=ad_user";
    $pg->paginationUrl = "main.php?act=ad_user&page=[p]";
    $str.= $pg->process();
	$str.='			
			</div>
		</div>
		<!-- Row -->
		<form role="form" id="actionForm" name="actionForm" enctype="multipart/form-data" action="" method="post" data-toggle="validator">
		<div class="row">
		<div class="col-lg-12"><h3>Cập nhật - Thêm mới thông tin</h3></div>
 	    

        <div class="col-lg-12">		
            '.$form->email('email','Email','',true).'
            '.$form->password('pwd','Password','',true).'			
		</div>    
		<div class="col-lg-12" style="padding-bottom:10px">
			<input type="hidden" name="idLoad" value="'.$_POST["idLoad"].'"/>
			<input type="hidden" name="Edit"/>
			<input type="hidden" name="Del"/>';
	if(isset($_POST["Edit"])&&$_POST["Edit"]==1)
	{
	   $btn=array('name'=>'update','value'=>'Update');
	}
	else
	{
		$btn=array('name'=>'addNew','value'=>'Submit');	
	}
	$str.='
            <button type="submit" name="'.$btn['name'].'" class="btn btn-default">'.$btn['value'].'</button>
			<button type="reset" class="btn btn-default">Reset</button>
		</div>
	</div>
	</form>
	';	
	return $str;	
}
?>