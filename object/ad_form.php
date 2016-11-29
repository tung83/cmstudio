<?php
class  form{
    private $param;
    public function __construct($param=NULL){
        $this->param=$param;
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
    private function checked( $checked ) {
		return $checked ? ' checked="checked"' : '';
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
    function message($msg){
        if(trim($msg)!=''){
            $str.='<div class="alert alert-danger" role="alert" style="margin-top:10px">'.$msg.'</div>';
        }
        return $str;
    }
    function hidden($name,$value){
        $str.='
        <div class="col-lg-12" style="padding-bottom:10px">
			<input type="hidden" name="idLoad" value="'.$this->get('id').'"/>
            <input type="hidden" name="listDel"/>
			<input type="hidden" name="Edit"/>
			<input type="hidden" name="Del"/>
            <button type="submit" name="'.$name.'" class="btn btn-default">'.$value.'</button>
			<button type="reset" class="btn btn-default" onClick="location.href=\''.$_SERVER['REQUEST_URI'].'\'">Reset</button>
		</div>';
        return $str;
    }
    function table_start($head_title){
        $str.='
        <!-- Row -->
        <div class="row">
        <div class="col-lg-12">
		<div class="table-responsive">
    	   <table class="table table-bordered table-hover table-striped">
    	       <thead>
                    <tr>
					   <th style="width:7%">ID</th>';
        foreach($head_title as $item){
            $str.='
            <th>'.$item.'</th>
            ';
        }
		$str.='
						<th style="width:10% !important" class="text-center">Options</th>
					</tr>
				</thead>
                <tbody>';
         return $str;
    }
    function table_body($id,$item,$addition=NULL){
        $str='
        <tr>
            <td class="">
                <label class="checkbox-inline">
                <input type="checkbox" class="checkbox_child" name="checkbox_child" value="'.$id.'"/>
                 '.$id.'
                </label>
            </td>';
        foreach($item as $val){
            $str.='
            <td>';
            switch($val[1]){
                case 'image':
                    $str.='<img src="'.$val[0].'" class="img-responsive img-thumbnail img-admin"/>';
                    break;
                case 'link':
                    $str.='<a href="http://'.$val[0].'" target="_blank">'.$val[0].'</a>';
                    break;
                case 'bool':
                    $icon=$val[0]==1?'ok':'remove';
                    $str.='<span class="glyphicon glyphicon-'.$icon.'"></span>';
                    break;
                case 'number':
                    $str.=number_format($val[0],0,'.',',');
                    break;
                case 'cate':
                    $str.='
                    <ol class="cate-tree">';
                    $i=1;
                    foreach($val[0] as $value){
                        $str.='
                        <li>';
                        if($i==1){
                            $str.='<i class="fa fa-folder-open"></i> ';
                        }elseif($i==2){
                            $str.='<i class="fa fa-folder"></i> ';
                        }
                        $str.=$value['title'].'</li>';
                        $i++;
                    }
                    $str.='
                    </ol>';
                    break;
                case 'text':
                default:
                    $str.=nl2br($val[0]);
                    break;
            }
            $str.='
            </td>';
        }
       	$str.='
        	<td align="center">
        ';
        $str.=$this->row_addition_link($addition);
        if($this->param['id']==$id){
    		$str.='<a href="'.$_SERVER['REQUEST_URI'].'" class="glyphicon glyphicon-refresh" aria-hidden="true"></a>';
        } else {
        	$str.='
    		<a href="javascript:operationFrm('.$id.",'E'".')" class="glyphicon glyphicon-pencil" aria-hidden="true"></a>
    		';
        }
        $str.='
        <a href="javascript:operationFrm('.$id.",'D'".')" class="glyphicon glyphicon-trash" aria-hidden="true"></a>
      		</td>
       	</tr>';
        return $str;
    }
    function table_end(){
        $str.='
            </tbody>
        </table>
        </div>
        </div>
        </div>';
        $str.=$this->del_list();
        return $str;
    }
    private function row_addition_link($arr=NULL){
        $str='';
        if($arr!=NULL){
            foreach($arr as $item){
                $lnk=null;
                foreach($item['variable'] as $key=>$val){
                    $lnk[]=$key.'='.$val;
                }
                $lnk=implode('&',$lnk);
                $lnk='main.php?'.$lnk;
                $str.='
                <a href="'.$lnk.'" class="glyphicon glyphicon-'.$item['icon'].'" aria-hidden="true"></a>
                ';
            }
        }
        return $str;
    }
    function img_remove($id,$db,$table,$col='img'){
        $db->where('id',$id);
        $img=$db->getOne($table,$col);
        if($img[$col]!=''&&file_exists($col)) {
            unlink(myPath.$img[$col]);
        }
    }
    function del_list(){
        $str.='
        <div class="row">
        <div class="col-md-12">
        <ol class="breadcrumb">
            <li><a href="javascript:void(0)" onclick="$(\'.checkbox_child\').prop(\'checked\', true);">Check All</a></li>
            <li><a href="javascript:void(0)" onclick="$(\'.checkbox_child\').prop(\'checked\', false);">Uncheck All</a></li>
            <li><a href="javascript:void(0)" onclick="del_all();">Delete All Checked</a></li>
        </ol>
        </div>
        </div>
        ';
        return $str;
    }
    function input($name,$options=array()){
        if(isset($options['required'])) $required=$options['required'];else $required=false;
        if(isset($options['disabled'])) $disabled=$options['disabled'];else $disabled=false;
        if(isset($options['type'])) $type=$options['type'];else $type='text';
        $str='
        <div class="form-group">';
        if(isset($options['label'])&&$options['label']!=''){
            $str.='<label for="'.$name.'">'.$options['label'].' :</label>';
        }
        $dimension='';
        $dimension.=' type="'.$type.'"';
        $dimension.=' name="'.$name.'" id="'.$name.'"';
        $dimension.=$this->required($required);
        $dimension.=$this->disabled($disabled);
        if(isset($options['rows'])) $dimension.=' rows="'.$options['rows'].'"';
        if(isset($options['holder']))$dimension.=' placeholder="'.$options['holder'].'"';
        if(isset($options['match'])) $dimension.=' data-match="#'.$options['match'].'"';
        if(isset($options['error'])) $dimension.=' data-error="'.$options['error'].'"';
        if(isset($options['class'])){
            $dimension.=' class="'.$options['class'].'"';  
        }elseif($type=='file'){
            $dimension.='';
        }else {
            $dimension.=' class="form-control"';   
        }
        $value=$this->get($name);
        if($type=='file'||(isset($options['get'])&&$options['get']==false)) $value='';
        switch($type){
            case 'textarea':
                $str.='
                <textarea '.$dimension.'>'.$value.'</textarea>
                ';
                break;
            default:
                $str.='
                <input '.$dimension.' value="'.$value.'"/>
                ';
                break;
        }
        $str.='
            <div class="help-block with-errors">'.(isset($options['desc'])?$options['desc']:'').'</div>
        </div>
        ';
        return $str;
    }
    function file($name,$options=array()){
        $options=array_merge($options,array('type'=>'file'));
        return $this->input($name,$options);
    }
    function text($name,$options=array()){
        $options=array_merge($options,array('type'=>'text'));
        return $this->input($name,$options);
    }
    function pwd($name,$options=array()){
        $options=array_merge($options,array('type'=>'password'));
        return $this->input($name,$options);
    }
    function number($name,$options=array()){
        $options=array_merge($options,array('type'=>'number'));
        return $this->input($name,$options);
    }
    function email($name,$options=array()){
        $options=array_merge($options,array('type'=>'email'));
        return $this->input($name,$options);
    }
    function textarea($name,$options=array()){
        $options=array_merge($options,array('type'=>'textarea'));
        return $this->input($name,$options);
    }
    function ckeditor($name,$options=array()){
        $options=array_merge($options,array('class'=>'ckeditor'));
        return $this->textarea($name,$options);
    }
    function checkbox($name,$options=array()){
        if(isset($options['disabled'])) $disabled=$options['disabled'];else $disabled=false;
         if(isset($options['checked'])) $checked=$options['checked'];else $checked=false;
        $str='
        <div class="form-group">
            <label for="'.$name.'" class="checkbox-inline">';
        $dimension='';
        $dimension.=' type="checkbox"';
        $dimension.=' name="'.$name.'" id="'.$name.'"';
        $dimension.=$this->disabled($disabled);
        if($this->get($name)!==''){
            $checked=$this->get($name);
        }
        $dimension.=$this->checked($checked);
        if(isset($options['class'])) $dimension.=' class="'.$options['class'].'"';else $dimension.='';
        $str.='
                <input '.$dimension.'/>'.$options['label'].' 
            </label>';
        $str.='            
        </div>';
        return $str;
    }
    function button($name,$options=array()){
        $type=isset($options['type'])?$options['type']:'submit';
        $label=isset($options['label'])?$options['label']:'';
        $dimension='';
        $dimension.=' type="'.$type.'"';
        $dimension.=' name="'.$name.'" id="'.$name.'"';
        if(isset($options['class'])) $dimension.=' class="'.$options['class'].'"';else $dimension.=' class="btn btn-primary"';
        $str='
        <button'.$dimension.'>'.$label.'</button>';
        return $str;
    }
    function search_area($db,$act,$table,$hint,$num=2){
        $str.='
        <div class="row">
        <form role="form" name="search_area" id="search_area" action="main.php" method="get">
        <input type="hidden" name="act" value="'.$act.'"/>';
        if(isset($_GET['type'])) $str.='<input type="hidden" name="type" value="'.$_GET['type'].'"/>';
        for($i=1;$i<=$num;$i++){
            $str.=$this->search_cate_select($db,$table,$i);
        }
        $str.='
        <div class="form-group col-lg-4">
            <input type="text" class="form-control" name="hint" placeholder="Từ khoá..." value="'.$hint.'"/>
        </div>
        <div class="form-group col-lg-2">
            <button type="submit" class="btn btn-default">Search</button>
        </div>
        </form>
        </div>
        ';
        return $str;
    }
    function search_cate_select($db,$table,$lev){
        $db->reset();
        $id=$_GET['cate_lev_'.$lev];
        $db->where('lev',$lev)->where('active',1);
        if($lev>1){
            $pId=$_GET['cate_lev_'.($lev-1)];
            $db->where('pId',$pId);
        }
        $list=$db->get($table,null,'id,title');
        $str='
        <div class="form-group col-lg-3">
            <select class="form-control" name="cate_lev_'.$lev.$add.'" id="cate_lev_'.$lev.$add.'" style="text-align-last:center">
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
                  data: { act: "cate", table: "'.$table.'", lev: '.intval($lev+1).', pId: $(this).val() }
                }).done(function( msg ) {
                    $(\'#cate_lev_'.($lev+1).$add.'\').html(msg);
                });
            });
            </script>
            ';
        }
        return $str;
    }
    
    
    function cate_group($db,$table='',$lev,$label='Danh mục:'){
        $str='
        <div class="form-group">
            <label>'.$label.'</label>

        ';
        $flow_parent=array();
        for($i=$lev,$k=0;$i>=1;$i--,$k++){
            if($i==$lev){
                $flow_parent[]=$this->get('pId');
            }else{
                $db->where('id',$flow_parent[$k-1]);
                $tmp=$db->getOne($table,'pId');
                $flow_parent[]=$tmp['pId'];
            }
        }
        $flow_parent=array_reverse($flow_parent);
        for($i=1;$i<=$lev;$i++){
            $options=array(
                'table' => $table,
                'lev'=>$i,
                'name'=>'frm_cate_'.$i,
                'id'=>$flow_parent[$i-1],
            );    
            if($i<$lev) $options['control']='frm_cate_'.($i+1);
            if($i>1) $options['pId']=$flow_parent[$i-2];
            $str.=$this->cate_select($db,$options);
        }
        $str.='</div>';
        return $str;
    }
    function cate_select($db,$options=array(),$required=true){
        if(array_key_exists('id',$options)) $id=$options['id']; else $id=0;
        if($options['lev']>1){
            $pId=array_key_exists('pId',$options)?intval($options['pId']):0;
            $db->where('pId',$options['pId']);
        }
        $db->where('lev',$options['lev'])->where('active',1);
        $list=$db->get($options['table'],null,'id,title');
        $str='
        <select'.$this->required($required).' class="form-control" name="'.$options['name'].'" id="'.$options['name'].'" style="text-align-last:center">
        <option value="">-----o0o-----</option>';
        foreach($list as $item){
            if($item['id']==$id) $slt=' selected';else $slt='';
            $str.='<option value="'.$item['id'].'"'.$slt.'>'.$item['title'].'</option>';
        }
        $str.='
        </select>
        <div class="help-block with-errors"></div>
        ';
        if($options['control']!=''){
            $str.='
            <script>
            $(\'#'.$options['name'].'\').on(\'change\',function(){
                $.ajax({
                  method: "POST",
                  url: "ajax.php",
                  data: { act: "cate", table: "'.$options['table'].'", lev: '.intval($options['lev']+1).', pId: $(this).val() }
                }).done(function( msg ) {
                    $(\'#'.$options['control'].'\').html(msg);
                });
            });
            </script>
            ';
        }
        return $str;
    }
    function pagination($page,$limit,$count){
        $query_string=array();
        parse_str($_SERVER['QUERY_STRING'],$query_string);
        if(isset($query_string['page'])){
            unset($query_string['page']);
        } 
        $def_url='main.php?'.$this->implode_assoc('&',$query_string);
        $query_string['page']='[p]';
        $url='main.php?'.$this->implode_assoc('&',$query_string);
        $pg = new Pagination(array('page'=>$page,'limit'=>$limit,'count'=>$count,'type'=>1));
        $pg->set_url(array('def'=>$def_url,'url'=>$url));
        return $pg->process();
    }
    function implode_assoc($glue,$arr){
        $new_arr=array();
        foreach($arr as $val=>$key){
            $new_arr[]=$val.'='.$key;
        }
        return implode($glue,$new_arr);
    }
 }
?>