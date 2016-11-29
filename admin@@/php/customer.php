<?php
function mainProcess()
{
    return customer();	
}
function customer()
{
    $msg='';
	if(isset($_POST["addNew"])||isset($_POST["update"]))
	{
		$name=str_replace("'","&rsquo;",$_POST["name"]);
        $adds=str_replace("'","&rsquo;",$_POST["adds"]);
        $phone=str_replace("'","&rsquo;",$_POST["phone"]);
        $email=str_replace("'","&rsquo;",$_POST["email"]);
        $facebook=str_replace("'","&rsquo;",$_POST["facebook"]);
	}
	if(isset($_POST["addNew"]))
	{
		$sInsert="insert into customer(name,adds,phone,email,facebook,dates";
		$sInsert.=") values('$name','$adds','$phone','$email','$facebook',now())";
		$test=mysql_query($sInsert);
		if($test)
		{
			header("location:".$_SERVER['REQUEST_URI'],true);
		}
		else echo $sInsert;		
	}
	if(isset($_POST["update"]))
	{
		$sUpdate="update customer set name='$name',adds='$adds',phone='$phone',email='$email',facebook='$facebook'";
		$sUpdate.=" where id=".$_POST["idLoad"];
		$test=mysql_query($sUpdate);
		if($test) header("location:".$_SERVER['REQUEST_URI'],true);
		else $msg=mysql_error();
	}
	if(isset($_POST["Edit"])&&$_POST["Edit"]==1)
	{
		$sql="select * from customer where id=".$_POST["idLoad"];
		$tabEdit=mysql_query($sql);
		$rowEdit=mysql_fetch_object($tabEdit);
	}
	if(isset($_POST["Del"])&&$_POST["Del"]==1)
	{
		$sDelete="delete from customer where id=".$_POST["idLoad"];
		$test=mysql_query($sDelete);
		if($test)
		{
			header("location:".$_SERVER['REQUEST_URI'],true);
		}
		else $msg=mysql_error();
	}
	$str='
	<!-- Page Heading -->
	<div class="row">
		<div class="col-lg-12">
			<ol class="breadcrumb">
				<li class="active">
					<i class="fa fa-dashboard"></i> Quản lý khách hàng
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
							<th>Họ & Tên</th>							
							<th>Địa Chỉ</th>
                            <th>Phone</th>
                            <th>Email / Facebook</th>
							<th style="width:12% !important">Options</th>
						</tr>
					</thead>
					<tbody>
					';
	$s="select * from customer order by name asc,id desc";
	$tab=mysql_query($s);
	$count=mysql_num_rows($tab);
	$page=isset($_GET["page"])?intval($_GET["page"]):1;
	$lim=10;
	$start=($page-1)*$lim;
	$s.=" limit $start,$lim";
	$tab=mysql_query($s);
	while($row=mysql_fetch_object($tab))
	{
        $email=trim($row->email)==''?'':'<a class="alert-success">'.$row->email.'</a>';
        $facebook=trim($row->facebook)==''?'':'<a class="alert-danger">'.$row->facebook.'</a>';
		$str.='
		<tr>
			<td>'.$row->id.'</td>
			<td>'.$row->name.'</td>
			<td>'.$row->adds.'</td>
            <td>'.$row->phone.'</td>
            <td>'.$email.' '.$facebook.'</td>
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
	<a href="javascript:operationFrm('.$row->id.",'D'".')" class="glyphicon glyphicon-trash" aria-hidden="true"></a>			  
			</td>
		</tr>
		';	
	}                                 
	$str.='					
					</tbody>
				</table>
				</div>';
	$str.=ad_paging($lim,$count,'main.php?act=customer&',$page);
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
				<label>Họ và Tên :</label>
				<input class="form-control" required name="name" value="'.$rowEdit->name.'">
			</div>	
            <div class="form-group">
                <label>Địa chỉ :</label>
                <input class="form-control" required name="adds" type="text" value="'.$rowEdit->adds.'"/>
            </div>
            <div class="form-group">
                <label>Phone :</label>
                <input class="form-control" required name="phone" type="text" value="'.$rowEdit->phone.'"/>
            </div>				
		</div>
		<div class="col-lg-6">
            <div class="form-group">
                <label>Email :</label>
                <input class="form-control" name="email" type="email" value="'.$rowEdit->email.'"/>
            </div>
            <div class="form-group">
                <label>Facebook :</label>
                <input class="form-control" name="facebook" type="text" value="'.$rowEdit->facebook.'"/>
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