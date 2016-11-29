<?php
function mainProcess($db)
{
    return product_option($db);	
}
function product_option($db)
{
	$msg='';
    $act='product_option';
    $table='product_option';
    $lev=1;
    $pId=0;
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
        $e_title=htmlspecialchars($_POST['e_title']);
        $sum=htmlspecialchars($_POST['sum']);
        $e_sum=htmlspecialchars($_POST['e_sum']);
        $file=time().$_FILES['file']['name'];
        $icon=time().$_FILES['icon']['name'];
	}
    if(isset($_POST['listDel'])&&$_POST['listDel']!=''){
        $list = explode(',',$_POST['listDel']);
        foreach($list as $item){
            $db->where('id',intval($item))->where('pId',intval($item),'OR');
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
                    'title'=>$title,'e_title'=>$e_title,
                    'sum'=>$sum,'e_sum'=>$e_sum
                );
		try{
            $recent = $db->insert($table,$insert);
            if($form->file_chk($_FILES['file'])){
                WideImage::load('file')->resize(null,41, 'fill')->saveToFile(myPath.$file);
                $db->where('id',$recent);
                $db->update($table,array('img'=>$file));
            }
            if($form->file_chk($_FILES['icon'])){
                WideImage::load('icon')->resize(null,21, 'fill')->saveToFile(myPath.$icon);
                $db->where('id',$recent);
                $db->update($table,array('icon'=>$icon));
            }
            header("location:".$_SERVER['REQUEST_URI'],true); 
        } catch(Exception $e) {
            $msg=$e->getMessage();
        }			
	}
	if(isset($_POST["update"]))	{
	   $update=array(
                    'title'=>$title,'e_title'=>$e_title,
                    'sum'=>$sum,'e_sum'=>$e_sum
                );
        if($form->file_chk($_FILES['file'])){
            WideImage::load('file')->resize(null,41, 'fill')->saveToFile(myPath.$file);
            $update = array_merge($update,array('img'=>$file));
            $form->img_remove($_POST['idLoad'],$db,$table);
        }
        if($form->file_chk($_FILES['icon'])){
            WideImage::load('icon')->resize(null,21, 'fill')->saveToFile(myPath.$icon);
            $update = array_merge($update,array('icon'=>$icon));
            $form->img_remove($_POST['idLoad'],$db,$table,'icon');
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
        $db->where('id',$_POST['idLoad']);
        try{
           $db->delete($table); 
           header("location:".$_SERVER['REQUEST_URI'],true);
        } catch(Exception $e) {
            $msg=$e->getMessage();
        }
	}
    $page_head= array(
                    array('#','Tính năng sản phẩm')
                );
	$str=$form->breadcumb($page_head);
	$str.=$form->message($msg);
    $head_title=array('Tiêu đề','Icon','Hình nền');
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
            $item_content = array(
                $item['title'],
                '<img src="'.myPath.$item['icon'].'" class="img-thumbnail img-admin"/>',
                '<img src="'.myPath.$item['img'].'" class="img-thumbnail img-admin"/>'
            );
            if(isset($_POST['Edit'])==1&&$_POST['idLoad']==$item_id) $change=true;
            else $change=false;
            $str.=$form->table_body($item_id,$item_content,$change,$_SERVER['REQUEST_URI']);      
        }
    }                               
	$str.='					
					</tbody>
				</table>
				</div>';
    $str.=$form->del_list();
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
		<form role="form" id="actionForm" name="actionForm" enctype="multipart/form-data" action="" method="post" data-toggle="validator">
		<div class="row">
		<div class="col-lg-12"><h3>Cập nhật - Thêm mới thông tin</h3></div>
        <div class="col-lg-12 admin-tabs">
            <ul class="nav nav-tabs">
    			<li class="active"><a href="#vietnamese" data-toggle="tab">Việt Nam</a></li>
    			<li><a href="#english" data-toggle="tab">English</a></li>
    		</ul>
    		<div class="tab-content">
    			<div class="tab-pane bg-vi active" id="vietnamese">
                    '.$form->text('title','Tiêu đề').'
                    '.$form->textarea('sum','Trích dẫn').'
    			</div>
    			<div class="tab-pane bg-en" id="english">
                    '.$form->text('e_title','Tiêu đề').'
                    '.$form->textarea('e_sum','Trích dẫn').'
    			</div>
    		</div>
        </div>
        <div class="col-lg-12">     
            '.$form->file('icon','Icon sản phẩm <code>( cao : 21px )</code>').'
            '.$form->file('file','Hình chi tiết <code>( cao : 41px )</code>').'
        </div>
		'.$form->hidden($_POST['idLoad'],$btn['name'],$btn['value']).'
	</div>
	</form>
	';	
	return $str;	
}
?>