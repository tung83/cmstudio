<?php
function mainProcess($db)
{
	return contact($db);	
}

function contact($db)
{
	$msg='';
    $table='contact';
	if(isset($_POST["addNew"])||isset($_POST["update"]))
	{
		$title=str_replace("'",'&rsquo;',$_POST["title"]);
		$detail=str_replace("'",'',$_POST["detail"]);
		$active=$_POST["active"]=="on"?1:0;
	}
	if(isset($_POST["addNew"]))
	{
		$sInsert="insert into contact(title,detail,active,dates";
		$sInsert.=") values('$title','$detail',$active,now())";
		$test=mysql_query($sInsert);
		if($test)
		{
			header("location:".$_SERVER['REQUEST_URI'],true);
		}
		else $msg=mysql_error();			
	}
	if(isset($_POST["update"]))
	{
		$sUpdate="update design set title='$title',active=$active";
		$sUpdate.=",detail='$detail'";
		$sUpdate.=" where id=".$_POST["idLoad"];
		$test=mysql_query($sUpdate);
		if($test) header("location:".$_SERVER['REQUEST_URI'],true);
		else $msg=mysql_error();
	}
	if(isset($_POST["Edit"])&&$_POST["Edit"]==1)
	{
		$sql="select * from design where id=".$_POST["idLoad"];
		$tabEdit=mysql_query($sql);
		$rowEdit=mysql_fetch_object($tabEdit);
	}
	if(isset($_POST["Del"])&&$_POST["Del"]==1)
	{
		$db->where('id',$_POST['idLoad']);
        try{
           $db->delete($table); 
           header("location:".$_SERVER['REQUEST_URI'],true);
        } catch(Exception $e) {
            $msg=mysql_error();
        }
	}
	$str='
	<!-- Page Heading -->
	<div class="row">
		<div class="col-lg-12">
			<ol class="breadcrumb">
				<li class="active">
					<i class="fa fa-dashboard"></i> Liên hệ
				</li>
			</ol>
		</div>
	</div>';
	if($msg!='')
	{
		$str.='<div class="alert alert-danger" role="alert" style="margin-top:10px">'.$sUpdate.'</div>';	
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
							<th>Họ&Tên</th>
							<th>Địa chỉ</th>							
							<th>Email</th>
							<th>Fax</th>
							<th>Nội dung</th>
							<th style="width:10% !important">Options</th>
						</tr>
					</thead>
					<tbody>
					';
	$page=isset($_GET["page"])?intval($_GET["page"]):1;
    $db->pageLimit=ad_lim;
    $db->orderBy('id');
    $list=$db->paginate($table,$page);
    
	foreach($list as $item)
	{
		$active=$item['active']?'<span class="glyphicon glyphicon-ok"></span>':'<span class="glyphicon glyphicon-remove"></span>';
		$str.='
		<tr>
			<td>'.$item['id'].'</td>
			<td>'.$item['name'].'</td>
			<td>'.$item['adds'].'</td>
			<td><a href="mailto:'.$item['email'].'">'.$item['email'].'</a></td>
			<td>'.$item['fax'].'</td>
			<td>'.nl2br($item['content']).'</td>
			<td align="center">
		';
	
	
	$str.='
	<a href="javascript:operationFrm('.$item['id'].",'D'".')" class="glyphicon glyphicon-trash" aria-hidden="true"></a>			  
			</td>
		</tr>
		';	
	}                                 
	$str.='					
					</tbody>
				</table>
				</div>';
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
		<form role="form" name="actionForm" enctype="multipart/form-data" action="" method="post">
		
		<div class="col-lg-12">
			<input type="hidden" name="idLoad" value="'.$_POST["idLoad"].'"/>
			<input type="hidden" name="Edit"/>
			<input type="hidden" name="Del"/>	
		</div>
	</div>
	</form>
	';	
	return $str;		
}
?>