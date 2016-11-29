<?php
function mainProcess()
{
	return slider();	
}
function slider()
{
	$msg='';
	if(isset($_POST["addNew"])||isset($_POST["update"]))
	{
		$file=time().$_FILES["file"]["name"];
		
		$lnk=str_replace("'","",$_POST["lnk"]);
		$sum=str_replace("'","&rsquo;",$_POST["sum"]);
		
		$title=str_replace("'","&rsquo;",$_POST["title"]);
	}
	if(isset($_POST["addNew"]))
	{
		$sInsert="insert into home_categories(lnk,title,sum";
		$sInsert.=") values('$lnk','$title','$sum')";
		$test=mysql_query($sInsert);
		$recent=mysql_insert_id();
		if(checkImg($file)==true)
		{
			move_uploaded_file($_FILES["file"]["tmp_name"],myPath.$file);
			$rexobj = new resize(myPath.$file);
			$rexobj -> resizeImage(216,216, 'exact');
			$rexobj->saveImage(myPath.$file,100);
			mysql_query("update home_categories set img='$file' where id=$recent");	
		}
		if($test)
		{
			header("location:".$_SERVER['REQUEST_URI'],true);
		}
		else echo $sInsert;//$msg=mysql_error();			
	}
	if(isset($_POST["update"]))
	{
		$sUpdate="update home_categories set lnk='$lnk',title='$title',sum='$sum'";
		if(checkImg($file)==true)
		{
			move_uploaded_file($_FILES["file"]["tmp_name"],myPath.$file);
			$rexobj = new resize(myPath.$file);
			$rexobj -> resizeImage(484,441, 'exact');
			$rexobj->saveImage(myPath.$file,100);
			$sUpdate.=",img='$file'";
		}
		$sUpdate.=" where id=".$_POST["idLoad"];
		$test=mysql_query($sUpdate);
		if($test) header("location:".$_SERVER['REQUEST_URI'],true);
		else $msg=mysql_error();
	}
	if(isset($_POST["Edit"])&&$_POST["Edit"]==1)
	{
		$sql="select * from home_categories where id=".$_POST["idLoad"];
		$tabEdit=mysql_query($sql);
		$rowEdit=mysql_fetch_object($tabEdit);
	}
	
	$str='
	<!-- Page Heading -->
	<div class="row">
		<div class="col-lg-12">
			<ol class="breadcrumb">
				<li class="active">
					<i class="fa fa-dashboard"></i> Home Slider
				</li>
			</ol>
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
							<th>Liên kết</th>
							<th>Tiêu đề</th>
                            <th>Tóm tắt</th>
							<th style="width:12% !important">Options</th>
						</tr>
					</thead>
					<tbody>
					';
	$s="select * from home_categories order by id desc";
	$tab=mysql_query($s);
	$count=mysql_num_rows($tab);
	$page=isset($_GET["page"])?intval($_GET["page"]):1;
	$lim=10;
	$start=($page-1)*$lim;
	$s.=" limit $start,$lim";
	$tab=mysql_query($s);
	while($row=mysql_fetch_object($tab))
	{
		$active=$row->active==1?'<span class="glyphicon glyphicon-ok"></span>':'<span class="glyphicon glyphicon-remove"></span>';
		$str.='
		<tr>
			<td>'.$row->id.'</td>
			<td>
	'.($row->img!=''?'<img src="'.myPath.$row->img.'" class="img-responsive img-thumbnail" style="height:80px"/>':'&nbsp;').'
			</td>
			<td>'.($row->lnk!=''?'<a href="'.$row->lnk.'">'.$row->lnk.'</a>':'&nbsp;').'</td>
			<td>'.($row->title!=''?''.$row->title.'':'&nbsp;').'</td>
			<td>'.($row->sum!=''?''.nl2br($row->sum).'':'&nbsp;').'</td>
			<td align="center">
		';
	if(isset($_POST["Edit"])==1)
	{
		if($_POST["idLoad"]==$row->id)
		{
			$str.='
			<a href="'.$_SERVER['REQUEST_URI'].'" class="glyphicon glyphicon-refresh" aria-hidden="true"></a>
			';	
		}
		else
		{
			$str.='
			<a href="javascript:operationFrm('.$row->id.",'E'".')" class="glyphicon glyphicon-pencil" aria-hidden="true"></a>
			';	
		}	
	}
	else
	{
		$str.='
			<a href="javascript:operationFrm('.$row->id.",'E'".')" class="glyphicon glyphicon-pencil" aria-hidden="true"></a>
			';		
	}
	
	$str.='
				  
			</td>
		</tr>
		';	
	}                                 
	$str.='					
					</tbody>
				</table>
				</div>';
	$str.=ad_paging($lim,$count,'main.php?act=home-slider&',$page);
	$str.='
			</div>
		</div>
		<!-- Row -->
		<form role="form" name="actionForm" enctype="multipart/form-data" action="" method="post">
		<div class="row">
		<div class="col-lg-12">
		<div class="panel panel-default">
		<div class="panel-heading">
			Cập nhật - Thêm mới thông tin
		</div>
		
		<div class="panel-body">
		<div class="row">
		
		<div class="col-lg-6">
			<div class="form-group">
				<label>Liên kết </label>
				<input class="form-control" name="lnk" type="text" value="'.$rowEdit->lnk.'">
			</div>
			
			<div class="form-group">
				<label>Hình ảnh(216x216) </label>
				<input type="file" name="file" />
			</div>					
			
				
		</div>
		<div class="col-lg-6">
			
			<div class="form-group">
				<label>Tiêu đề </label>
				<input class="form-control" name="title" type="text" value="'.$rowEdit->title.'">
			</div>					
			<div class="form-group">
				<label>Nội dung tóm tắt</label>
				<textarea name="sum" class="form-control">'.$rowEdit->sum.'</textarea>
			</div>
		</div>
		<div class="col-lg-12">
			<input type="hidden" name="idLoad" value="'.$_POST["idLoad"].'"/>
			<input type="hidden" name="Edit"/>
			<input type="hidden" name="Del"/>';
	if(isset($_POST["Edit"])&&$_POST["Edit"]==1)
	{
		$str.='		
				<button type="submit" name="update" class="btn btn-default">Update</button>';
	}
	else
	{
		$str.='		
				<button type="submit" name="addNew" class="btn btn-default">Submit</button>';	
	}
	$str.='
			<button type="reset" class="btn btn-default" id="reset">Reset</button>
		</div>
		
	<div>
	<!--div row-->
	</div>
	<!--panel body-->
	</div>
	</div>
	</div>
	</form>
	';	
	return $str;
}
?>