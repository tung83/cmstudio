<?php
function mainProcess()
{
    return statistic();	
}
function statistic()
{
	$str='
	<!-- Page Heading -->
	<div class="row">
		<div class="col-lg-12">
			<ol class="breadcrumb">
				<li class="active">
					<i class="fa fa-dashboard"></i> Thống kê đơn hàng
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
	<div class="panel panel-default">            
	<div class="panel-heading">
		Cập nhật - Thêm mới thông tin
	</div>
    
	<div class="panel-body">
    
    <div class="row">';
    $str.='
    <div class="col-lg-12">
         <div class="form-group">'.customer_choose().'</div>
    </div>
    <div class="col-lg-6">
         <div class="form-group"><label>Từ</label>
                <div class="input-group date" id="datetimepicker1">
                    <input type="text" required class="form-control" autocomplete="off" id="from" name="from"/>
                    <span class="input-group-addon">
                        <span class="glyphicon glyphicon-calendar"></span>
                    </span>
                </div>
                <script type="text/javascript">
                    $(function () {
                        $("#from").datetimepicker();
                    });
                </script>
         </div>
    </div>
    <div class="col-lg-6">
         <div class="form-group"><label>Đến</label>
                <div class="input-group date" id="datetimepicker2">
                    <input type="text" required class="form-control" autocomplete="off" id="to" name="to"/>
                    <span class="input-group-addon">
                        <span class="glyphicon glyphicon-calendar"></span>
                    </span>
                </div>
                <script type="text/javascript">
                    $(function () {
                        $("#to").datetimepicker();
                    });
                </script>
         </div>
    </div>
    ';
    $str.='      
   	<div class="col-lg-12">	
    	<button type="submit" name="addNew" class="btn btn-default">Submit</button>
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
	<div class="row">
		 <div class="col-lg-12">';
   if(isset($_POST['addNew']))
   {
        $cus=$_POST['customer'];
        $from=$_POST['from'];
        $to=$_POST['to'];
        if($cus!=0)
        {
            $temp=mysql_query("select name,adds from customer where id=$cus");
            $tmp=mysql_fetch_object($temp);
            $name=$tmp->name.' ( '.$tmp->adds.' ) ';
        }
        else
        {
            $name="Thống kê tất cả";
        }
        $str.='
            <div class="form-group">
                <h4 class="text-center">KẾT QUẢ THỐNG KÊ</h4>
            </div>
            <div class="form-group">
                <label>Khách Hàng:</label> '.$name.' 
            </div>
            <div class="form-group">
                <label>Thời gian:</label> '.date("d/m/Y",strtotime($from)).' -> '.date("d/m/Y",strtotime($to)).'
            </div>
            <div class="table-responsive" style="margin-bottom:100px">
    				<table class="table table-bordered table-hover table-striped">
    					<thead>
    						<tr>
    							<th>STT</th>
    							<th>Ngày đặt hàng</th>
                                <th>Thành tiền</th>                            
                                <th>Options</th>
    						</tr>
    					</thead>
    					<tbody>
    					';
        $from=strtotime($from);
        $to=strtotime($to);
        if($cus!=0)
        {
    	   $s="select * from cart where customer_id=$cus and dates between FROM_UNIXTIME('$from') and FROM_UNIXTIME('$to') order by id asc";
        }
        else
        {
           $s="select * from cart where dates between FROM_UNIXTIME('$from') and FROM_UNIXTIME('$to') order by id asc"; 
        }
    	$tab=mysql_query($s);
        $k=1;
        $total=0;
    	while($row=mysql_fetch_object($tab))
    	{
    	   $sum=0;
           $tb=mysql_query("select * from cart_detail where cart_id=$row->id and qty<>0");
           while($r=mysql_fetch_object($tb))
           {
                $one_item=$r->qty*$r->price;
                $sum+=$one_item;
           }
           $total+=$sum;
    		$str.='
    		<tr id="product_'.$row->id.'_row" class="row_for_total">
    			<td>'.$k.'</td>
    			<td>'.date("d/m/Y",strtotime($row->dates)).'</td>
                <td>
                    '.number_format($sum,0,",",".").' VNĐ
                </td>	
                <td>
                    <a href="../cart_show.php?id='.$row->id.'" target="_blank" class="glyphicon glyphicon-eye-open" aria-hidden="true"></a>
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
                                <td colspan="1">
                                    <label>
                                        Tổng tiền
                                    </label>
                                </td>
                                <td colspan="3">
                                    <b style="color:#f00">'.number_format($total,0,",",".").' VNĐ</b>
                                </td>
                            </tr>
                        </tfoot>
    				</table>
    				</div>';
   }
           
			
	$str.='
			</div>
		</div>
		<!-- Row -->
	';	
	return $str;
}
function customer_choose()
{
    $tab=mysql_query("select id,name,phone,adds from customer order by name asc,id desc");
    $str='
    <select name="customer" id="customer" class="form-control" required>';
    $str.='
    <option value="0" selected="selected">Chọn khách hàng</option>
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