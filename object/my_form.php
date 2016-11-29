<?php
class my_form{
    function construct(){
        
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
        if(isset($options['class'])) $dimension.=' class="'.$options['class'].'"';else $dimension.=' class="form-control"';
        switch($type){
            case 'textarea':
                $str.='
                <textarea '.$dimension.'></textarea>
                ';
                break;
            default:
                $str.='
                <input '.$dimension.' class="form-control" />
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
    
}