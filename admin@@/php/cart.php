<?php
function mainProcess()
{
    if(isset($_GET['cata_id'])) return cart_add();
    else return cart();	
}
function cart_add()
{
    $cata_id=intval($_GET['cata_id']);
    if(isset($_POST["addNew"])||isset($_POST["update"]))
	{
		$customer=$_POST['customer'];
	}
	if(isset($_POST["addNew"]))
	{
		$sInsert="insert into cart(customer_id,category_id,dates";
		$sInsert.=") values($customer,$cata_id,now())";
        $test=mysql_query($sInsert);
        $recent=mysql_insert_id();
        $temp=mysql_query("select id,price from product where pId=$cata_id");
        while($tmp=mysql_fetch_object($temp))
        {
            $quantity=intval($_POST['qty_'.$tmp->id]);
            $s="insert into cart_detail(cart_id,product_id,price,qty) values(";
            $s.="$recent,$tmp->id,$tmp->price,$quantity)";
            mysql_query($s);
        }		
		if($test)
		{
			header("location:main.php?act=cart",true);
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
					<i class="fa fa-dashboard"></i> Tạo mới đơn hàng
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
    <form role="form" name="actionForm" enctype="multipart/form-data" action="" method="post">
	<div class="row">

		 <div class="col-lg-12">
            <div class="form-group">'.customer_choose().'</div>
			<div class="table-responsive">
				<table class="table table-bordered table-hover table-striped">
					<thead>
						<tr>
							<th>STT</th>
							<th>Tên sản phẩm</th>
                            <th>Số lượng</th>
                            <th>Đơn giá</th>                            
                            <th>Thành tiền</th>
						</tr>
					</thead>
					<tbody>
					';
	$s="select * from product where pId=$cata_id order by id desc";
	$tab=mysql_query($s);
    $k=1;
	while($row=mysql_fetch_object($tab))
	{
		$str.='
		<tr id="product_'.$row->id.'_row" class="row_for_total">
			<td>'.$k.'</td>
			<td>'.$row->title.'</td>
            <td>
                <input type="number" min="0" required value="0" name="qty_'.$row->id.'" class="form-control qty_change" id="product_'.$row->id.'"/>
            </td>
            <td>
                <input type="hidden" readonly value="'.$row->price.'" name="price_'.$k.'" class="form-control"/>
                <span class="price" data-price="'.$row->price.'">'.number_format($row->price,0,',','.').'</span>
            </td>
            
            <td>
                <span class="sum" data-sum="0">0</span>
            </td>			
        ';
	$str.='
		</tr>
		';	
        $k++;
	}                                 
	$str.='
    				
					</tbody>
                    <tfoot>
                        <tr>
                            <td colspan="3">
                                <label>
                                    Tổng tiền
                                </label>
                            </td>
                            <td colspan="2">
                                <span id="total"></span>
                            </td>
                        </tr>
                    </tfoot>
				</table>
				</div>';
	$str.='
			</div>
		</div>
		<!-- Row -->
		
		<div class="row">
		<div class="col-lg-12">
		<div class="panel panel-default">
                
		<div class="panel-heading">
			Cập nhật - Thêm mới thông tin
		</div>
        
		<div class="panel-body">
        
        <div class="row">';
  $str.='      
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
				<button type="submit" name="addNew" class="btn btn-default" onclick="return confirm('."'Bạn nên kiểm tra kỹ trước khi thêm mới...'".')">Submit</button>';	
	}
	$str.='
			<button type="reset" class="btn btn-default" id="reset">Reset</button>
		</div>
		
    	<div>
    	<!--div row-->
    	</div>
    	<!--panel body-->
        </div>
        <!--panel-->
        </div>
        <!--col-->
        </div>
        <!--div row-->
	</form>
	';	
	return $str;
}
function cart()
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
		$sInsert="insert into cart(name,adds,phone,email,facebook,dates";
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
		$sUpdate="update cart set name='$name',adds='$adds',phone='$phone',email='$email',facebook='$facebook'";
		$sUpdate.=" where id=".$_POST["idLoad"];
		$test=mysql_query($sUpdate);
		if($test) header("location:".$_SERVER['REQUEST_URI'],true);
		else $msg=mysql_error();
	}
	if(isset($_POST["Edit"])&&$_POST["Edit"]==1)
	{
		$sql="select * from cart where id=".$_POST["idLoad"];
		$tabEdit=mysql_query($sql);
		$rowEdit=mysql_fetch_object($tabEdit);
	}
	if(isset($_POST["Del"])&&$_POST["Del"]==1)
	{
		$sDelete="delete from cart where id=".$_POST["idLoad"];
        mysql_query("delete from cart_detail where cart_id=".$_POST['idLoad']);
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
					<i class="fa fa-dashboard"></i> Danh sách đơn hàng
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
							<th>STT</th>
							<th>Tên Khách Hàng</th>
                            <th>Địa Chỉ</th>							
							<th>Ngày Giờ</th>
                            <th>Tổng SL</th>
                            <th>Tổng Tiền</th>
                            <th>Options</th>
						</tr>
					</thead>
					<tbody>
					';
	$s="select b.*,a.name,c.title,a.adds from customer a,cart b,category c where b.customer_id=a.id and b.category_id=c.id order by b.id desc";
	$tab=mysql_query($s);
	$count=mysql_num_rows($tab);
	$page=isset($_GET["page"])?intval($_GET["page"]):1;
	$lim=10;
	$start=($page-1)*$lim;
	$s.=" limit $start,$lim";
	$tab=mysql_query($s);
	while($row=mysql_fetch_object($tab))
	{
        $tb=mysql_query("select * from cart_detail where cart_id=$row->id");
        $sm=0;$qty=0;
        while($r=mysql_fetch_object($tb))
        {
            $sm+=$r->price*$r->qty;
            $qty+=$r->qty;
        }
		$str.='
		<tr>
			<td>'.$row->id.'</td>
			<td>'.$row->name.'</td>
            <td>'.$row->adds.'</td>
			<!--td>'.$row->title.'</td-->
            <td>'.date("d-m-Y H:i:s",strtotime($row->dates)).'</td>
            <td>'.$qty.'</td>
            <td>'.number_format($sm,0,".",",").'</td>
			<td align="center">
                <a href="../cart_show.php?id='.$row->id.'" target="_blank" class="glyphicon glyphicon-eye-open" aria-hidden="true"></a>
        ';
	/*if(isset($_POST["Edit"])==1)
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
	*/
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
	$str.=ad_paging($lim,$count,'main.php?act=cart&',$page);
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
    	</form>
	';		
	return $str;
}
function customer_choose()
{
    $tab=mysql_query("select id,name,phone,adds from customer order by name asc,id desc");
    $str='
    <select name="customer" id="customer" class="form-control" required>';
    $str.='
    <option value="" selected="selected">Chọn khách hàng</option>
    ';
    while($row=mysql_fetch_object($tab))
    {
        $str.='
        <option value="'.$row->id.'">'.$row->name.' ('.$row->adds.')</option>
        ';
    }
    $str.='
    </select>
    ';
    return $str;
}
function category_choose()
{
    $tab=mysql_query("select id,title from category order by title asc,id desc");
    $str='
    <select name="category" id="category" class="form-control">';
    $str.='
    <option value="0" selected="selected">Chọn ngành hàng</option>
    ';
    while($row=mysql_fetch_object($tab))
    {
        $str.='
        <option value="'.$row->id.'">'.$row->title.'</option>
        ';
    }
    $str.='
    </select>
    ';
    return $str;
}
?>