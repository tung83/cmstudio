<?php
class  form{
    public function __construct($param=NULL){
        $this->param=$param[0];
    }
    private function get($name){
        if(isset($this->param[$name]) && isset($this->param)){
            return $this->param[$name];
        }
        else return '';
    }
    private function required( $required ) {
		return $required ? ' required' : '';
	}

	private function disabled( $disabled ) {
		return $disabled ? ' disabled' : '';
	}
    function input($type, $name, $label, $desc='', $required = FALSE, $disabled = FALSE){
        $str='
        <div class="form-group">
            <label for="'.$name.'">'.$label.' :</label>';
        $moreOption='';
        if($type!='file') $moreOption=' value="'.$this->get($name).'"';
        $str.='
        <input type="'.$type.'" name="'.$name.'" id="'.$name.'"'.$moreOption.''.$this->required($required).$this->disabled($disabled).' class="form-control" />
        ';
        $str.='
            <div class="help-block with-errors">'.$desc.'</div>
        </div>
        ';
        return $str;
    }
    public function file($name,$label,$desc='',$required=false,$disabled=false){
        return $this->input('file',$name,$label,$desc,$required,$disabled);
    }
	public function text( $name, $label, $desc='', $required = FALSE, $disabled = FALSE ) {
	   return $this->input('text', $name, $label , $desc, $required, $disabled);
	}

	public function number( $name, $label, $desc='', $required = FALSE, $disabled = FALSE ) {
	   return $this->input('number', $name, $label, $desc, $required, $disabled);
	}

	public function email( $name, $label, $desc='', $required = FALSE, $disabled = FALSE ) {
		return $this->input('email', $name, $label, $desc, $required, $disabled);
	}

	public function password( $name, $label, $desc='', $required = FALSE, $disabled = FALSE ) {
		return $this->input('password', $name, $label, $desc, $required, $disabled);
	}
    public function confirm_password($name,$name_match,$label,$desc,$required=false,$disable=false){
        $str='
        <div class="form-group">
            <label for="'.$name.'">'.$label.' :</label>
            <input type="password" data-match="#'.$name_match.'" name="'.$name.'" id="'.$name.'" value="'.$this->get($name).'"'.$this->required($required).$this->disabled($disabled).' class="form-control" data-match-error="Xác nhận mật khẩu không đúng.">
            <div class="help-block with-errors">'.$desc.'</div>
        </div>
        ';
        return $str;
    }
	public function textarea( $name, $label ,$desc='' ) {
		$str.='
		<div class="form-group">
			<label for="'.$name.'">'.$label.' :</label>
			<textarea name="'.$name.'" id="'.$name.'" class="form-control">'.$this->get($name).'</textarea>
            <div class="help-block with-errors">'.$desc.'</div>
		</div>
		';
        return $str;
	}
    function ckeditor($name,$label,$desc=''){
        $str='
        <div class="form-group">
    		<label>'.$label.' :</label>
            <textarea name='.$name.' id='.$name.' class="ckeditor">'.$this->get($name).'</textarea>
            '.$desc.'
        </div>';
        return $str;
    }
    function checkbox($name,$label,$checked=false){
        $active = '';
        if($this->get($name)==1||($this->get($name)===''&&$checked==true)) $active = "checked='checked'";
        $str.='
        <div class="form-group">
			<label class="checkbox-inline">
				<input type="checkbox" name="'.$name.'" '.$active.'>'.$label.'
			</label>
		</div>';
        return $str;
    }
    function breadcumb($param){
        $str='
        <!-- Page Heading -->
    	<div class="row">
    		<div class="col-lg-12">
        <ol class="breadcrumb">';
        for($i=0;$i<count($param);$i++)
        {
            if(($i+1)==count($param)) $active='active';
            else $active='';
            $str.='
            <li class="'.$active.'">
      		    '.($i==0?'<i class="fa fa-dashboard"></i>':'').' <a href="'.$param[$i][0].'">'.$param[$i][1].'</a>
        	</li>';
        }
        $str.='</ol>
            </div>
    	</div>';
        return $str;
    }
    function file_chk($file,$type='image'){
        if($file['error']>0) return false;
		else if(strstr($file['type'],$type)!=NULL) return true;
		else return false;
    }
    function del_list(){
        $str.='
        <div>
        <ol class="breadcrumb">
            <li><a href="javascript:void(0)" onclick="$(\'.checkbox_child\').prop(\'checked\', true);">Check All</a></li>
            <li><a href="javascript:void(0)" onclick="$(\'.checkbox_child\').prop(\'checked\', false);">Uncheck All</a></li>
            <li><a href="javascript:void(0)" onclick="del_all();">Delete All Checked</a></li>
        </ol>
        </div>
        ';
        return $str;
    }
    function message($msg){
        if(trim($msg)!=''){
            $str.='<div class="alert alert-danger" role="alert" style="margin-top:10px">'.$msg.'</div>';
        }
        return $str;
    }
    function hidden($idLoad,$name,$value){
        $str.='
        <div class="col-lg-12" style="padding-bottom:10px">
			<input type="hidden" name="idLoad" value="'.$idLoad.'"/>
            <input type="hidden" name="listDel"/>
			<input type="hidden" name="Edit"/>
			<input type="hidden" name="Del"/>
            <button type="submit" name="'.$name.'" class="btn btn-default">'.$value.'</button>
			<button type="reset" class="btn btn-default" onClick="location.href=\''.$_SERVER['REQUEST_URI'].'\'">Reset</button>
		</div>';
        return $str;
    }
    function table_head($head_title){
        $str.='
    	<!-- Row -->
    	<div class="row">
    		 <div class="col-lg-12">
    			<div class="table-responsive">
    				<table class="table table-bordered table-hover table-striped">
    					<thead>
    						<tr style="">
    							<th class="text-center" style="width:7%">ID</th>';
        foreach($head_title as $item){
            $str.='
            <th>'.$item.'</th>
            ';
        }
		$str.='
    							<th style="width:10% !important" class="text-center">Options</th>
    						</tr>
    					</thead>
    					<tbody>
    					';
         return $str;
    }
    function table_body($id,$item,$change=false,$url,$addition=NULL){
        $str='
        <tr>
            <td class="text-center">
                <label class="checkbox-inline">
                <input type="checkbox" class="checkbox_child" name="checkbox_child" value="'.$id.'"/>
                 '.$id.'
                </label>
            </td>';
        foreach($item as $val){
            $str.='<td>'.$val.'</td>';
        }
       	$str.='
        	<td align="center">
        ';
        $str.=$this->row_addition_link($addition);
        if($change==true){
    		$str.='<a href="'.$url.'" class="glyphicon glyphicon-refresh" aria-hidden="true"></a>';
        } else {
        	$str.='
    		<a href="javascript:operationFrm('.$id.",'E'".')" class="glyphicon glyphicon-pencil" aria-hidden="true"></a>
    		';
        }
        $str.='
        <a href="javascript:operationFrm('.$id.",'D'".')" class="glyphicon glyphicon-trash" aria-hidden="true"></a>
        		</td>
        	</tr>
        	';
        return $str;
    }
    private function row_addition_link($arr){
        $str='';
        if($arr!=NULL){
            foreach($arr as $item){
                $str.='
                <a href="'.$item['direction'].'" class="glyphicon glyphicon-'.$item['icon'].'" aria-hidden="true"></a>
                ';
            }
        }
        return $str;
    }
    function img_upload_handle($file,$type='ins'){
        $def=array('height'=>0,'width'=>0,'type'=>'fill','location'=>myPath);
        /*switch($type){
            case 'ins':{
                if($form->file_chk($_FILES['file'])){
                    WideImage::load('file')->resize(484, 441, 'fill')->saveToFile(myPath.$file);
                    $db->where('id',$recent);
                    $db->update($table,array('img'=>$file));
                }
                break;
            }
            case 'up':{
                if($form->file_chk($_FILES['file'])){
                    WideImage::load('file')->resize(484, 441, 'fill')->saveToFile(myPath.$file);
                    $update = array_merge($update,array('img'=>$file));
                    $db->where('id',$_POST['idLoad']);
                    $last_img = $db->getOne($table,'img');
                    if($last_img['img']!='') unlink(myPath.$last_img['img']);
                }
                break;
            }
            default:break;
        }*/
        return $str;
    }
    function img_remove($id,$db,$table,$col='img'){
        $db->where('id',$id);
        $img=$db->getOne($table,$col);
        if($img!='') unlink(myPath.$img[$col]);
    }
    function search_area($db,$act,$hint){
        $str.='
        <div class="row">
        <form role="form" name="search_area" id="search_area" action="main.php" method="get">
        <input type="hidden" name="act" value="'.$act.'"/>';
        $str.=$this->category_select($db,'category',1);
        $str.=$this->category_select($db,'category',2);
        $str.='
        <div class="form-group col-lg-4">
            <input type="text" class="form-control" name="hint" placeholder="Tên sản phẩm..." value="'.$hint.'"/>
        </div>
        <div class="form-group col-lg-2">
            <button type="submit" class="btn btn-default">Submit</button>
        </div>
        </form>
        </div>
        ';
        return $str;
    }
    function category_select($db,$table,$lev,$add='',$required=false){
        $id=$_GET['cate_lev_'.$lev];
        $db->where('lev',$lev)->where('active',1);
        if($lev>1){
            $pId=$_GET['cate_lev_'.($lev-1)];
            $db->where('pId',$pId);
        }
        $list=$db->get($table,null,'id,title');
        $str='
        <div class="form-group col-lg-3">
            <select'.$this->required($required).' class="form-control" name="cate_lev_'.$lev.$add.'" id="cate_lev_'.$lev.$add.'" style="text-align-last:center">
            <option value="">-----o0o-----</option>';
        foreach($list as $item){
            if($item['id']==$id) $slt=' selected';else $slt='';
            $str.='<option value="'.$item['id'].'"'.$slt.'>'.$item['title'].'</option>';
        }
        $str.='
            </select>
        </div>';
        $db->where('lev',$lev+1)->where('active',1);
        $db->get($table,null,'id');
        if($db->count>0){
            $str.='
            <script>
            $(\'#cate_lev_'.$lev.$add.'\').on(\'change\',function(){
                $.ajax({
                  method: "POST",
                  url: "ajax.php",
                  data: { act: "category", table: "'.$table.'", lev: '.intval($lev+1).', pId: $(this).val() }
                }).done(function( msg ) {
                    $(\'#cate_lev_'.($lev+1).$add.'\').html(msg);
                });
            });
            </script>
            ';
        }
        return $str;
    }
    function category_group($db){
        $id_lev_2=$this->get('pId');
        $table='category';
        if(intval($id_lev_2)!=0){
            $db->where('id',$id_lev_2);
            $id_lev_1 = $db->getOne($table,'pId');
            $id_lev_1 = $id_lev_1['pId'];
        } else {
            $id_lev_1 = 0;
        }
        $str='
        <div class="form-group">
            <label>Danh mục :</label>

        ';
        $param=array(
            'table' => 'category',
            'lev'=>1,
            'name'=>'frm_cate_1',
            'id'=>intval($id_lev_1),
            'control'=>'frm_cate_2'
        );
        $str.=$this->cate_select($db,$param,true);
        $param=array(
            'table' => 'category',
            'lev'=>2,
            'name'=>'frm_cate_2',
            'pId'=>$id_lev_1,
            'id'=>$id_lev_2
        );
        $str.=$this->cate_select($db,$param,true);
        $str.='</div>';
        return $str;
    }
    function cate_select($db,$param,$required=false){
        if(array_key_exists('id',$param)) $id=$param['id']; else $id=0;
        if($param['lev']>1){
            $pId=array_key_exists('pId',$param)?intval($param['pId']):0;
            $db->where('pId',$param['pId']);
        }
        $db->where('lev',$param['lev'])->where('active',1);
        $list=$db->get($param['table'],null,'id,title');
        $str='

            <select'.$this->required($required).' class="form-control" name="'.$param['name'].'" id="'.$param['name'].'" style="text-align-last:center">
            <option value="">-----o0o-----</option>';
        foreach($list as $item){
            if($item['id']==$id) $slt=' selected';else $slt='';
            $str.='<option value="'.$item['id'].'"'.$slt.'>'.$item['title'].'</option>';
        }
        $str.='
            </select>
            <div class="help-block with-errors"></div>
        ';
        if($param['control']!=''){
            $str.='
            <script>
            $(\'#'.$param['name'].'\').on(\'change\',function(){
                $.ajax({
                  method: "POST",
                  url: "ajax.php",
                  data: { act: "category", table: "'.$param['table'].'", lev: '.intval($param['lev']+1).', pId: $(this).val() }
                }).done(function( msg ) {
                    $(\'#'.$param['control'].'\').html(msg);
                });
            });
            </script>
            ';
        }
        return $str;
    }
    function select($name,$label,$list=array(),$required=false){
        $id=intval($this->get($name));
        $str='
        <div class="form-group">
            <label>'.$label.'</label>
            <select'.$this->required($required).' class="form-control" name="'.$name.'" id="'.$name.'" style="text-align-last:center">
            <option value="">-----o0o-----</option>';
        foreach($list as $item){
            if($item['id']==$id) $slt=' selected';else $slt='';
            $str.='<option value="'.$item['id'].'"'.$slt.'>'.$item['title'].'</option>';
        }
        $str.='
            </select>
            <div class="help-block with-errors"></div>
        </div>
        ';
        return $str;
    }
    function select_table($name,$label,$table,$db,$required=false){
        $id=intval($this->get($name));
        $list=$db->where('active',1)->orderBy('id')->get($table,null,'id,title');
        $str='
        <div class="form-group">
            <label>'.$label.'</label>
            <select'.$this->required($required).' class="form-control" name="'.$name.'" id="'.$name.'" style="text-align-last:center">
            <option value="">-----o0o-----</option>';
        foreach($list as $item){
            if($item['id']==$id) $slt=' selected';else $slt='';
            $str.='<option value="'.$item['id'].'"'.$slt.'>'.$item['title'].'</option>';
        }
        $str.='
            </select>
            <div class="help-block with-errors"></div>
        </div>
        ';
        return $str;
    }

    function location($db){
        $district=$this->get('district');
        $table='quanhuyen';
        if(intval($district)!=0){
            $db->where('id',$district);
            $city = $db->getOne($table,'pId');
            $city = $city['pId'];
        } else {
            $city = 0;
        }
        $str='
        <div class="form-group">
            <label>Địa điểm :</label>

        ';
        $param=array(
            'lev'=>1,
            'table' => 'tinhthanh',
            'name'=>'city',
            'id'=>intval($city),
            'control'=>'district',
            'control_table'=>'quanhuyen'
        );
        $str.=$this->location_select($db,$param,true);
        $param=array(
            'lev'=>2,
            'table' => 'quanhuyen',
            'name'=>'district',
            'pId'=>$city,
            'id'=>$district
        );
        $str.=$this->location_select($db,$param,true);
        $str.='</div>';
        return $str;
    }
    function location_select($db,$param,$required=false){
        if(array_key_exists('id',$param)) $id=$param['id']; else $id=0;
        if($param['lev']>1){
            $pId=array_key_exists('pId',$param)?intval($param['pId']):0;
            $db->where('pId',$pId);
        }
        $db->orderBy('title','ASC');
        $list=$db->get($param['table'],null,'id,title');
        $str='

            <select'.$this->required($required).' class="form-control" name="'.$param['name'].'" id="'.$param['name'].'" style="text-align-last:center">
            <option value="">-----o0o-----</option>';
        foreach($list as $item){
            if($item['id']==$id) $slt=' selected';else $slt='';
            $str.='<option value="'.$item['id'].'"'.$slt.'>'.$item['title'].'</option>';
        }
        $str.='
            </select>
            <div class="help-block with-errors"></div>
        ';
        if($param['control']!=''){
            $str.='
            <script>
            $(\'#'.$param['name'].'\').on(\'change\',function(){
                $.ajax({
                  method: "POST",
                  url: "ajax.php",
                  data: { act: "location", table: "'.$param['control_table'].'", pId: $(this).val() }
                }).done(function( msg ) {
                    $(\'#'.$param['control'].'\').html(msg);
                });
            });
            </script>
            ';
        }
        return $str;
    }

    function get_options($db,$table,$first='<option value="">-----o0o-----</option>'){
        $str=$first;
        $db->where('active',1);
        $db->get($table,null,'id,title');
        foreach($list as $item){
            if($item['id']==$id) $slt=' selected';else $slt='';
            $str.='<option value="'.$item['id'].'"'.$slt.'>'.$item['title'].'</option>';
        }
        return $str;
    }
    function product_option($db){
        $current=$this->get('pd_option');
        $list=$db->get('product_option',null,'id,title');
        $str.='
        <div class="form-group">
            <label>Tính năng sản phẩm:</label>
        </div>
        <div class="form-group">';
        foreach($list as $item){
            if(strstr($current,(string)$item['id'])==false) $active = '';
            else $active=' checked="checked"';
            $str.='
            <label class="checkbox-inline">
				<input type="checkbox" name="product_option[]" value="'.$item['id'].'"'.$active.'>'.$item['title'].'
			</label>';
        }
        $str.='
        </div>';
        return $str;
    }
}
?>
