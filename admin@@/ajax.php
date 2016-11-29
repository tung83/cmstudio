<?php
include '../config.php';
$act=$_POST['act'];
$str='';
switch($act){
    case 'cate':
        $lev=$_POST['lev'];
        $pId=intval($_POST['pId']);
        $table=htmlspecialchars($_POST['table']);
        $list=$db->where('pId',$pId)->where('lev',$lev)->orderBy('id')->get($table,null,'id,title');
        $str.='<option>-----o0o-----</option>';
        foreach((array)$list as $item){
            $str.='<option value="'.$item['id'].'">'.$item['title'].'</option>';
        }
        break;
    case 'location':
        $pId=intval($_POST['pId']);
        $table=htmlspecialchars($_POST['table']);
        $list=$db->where('pId',$pId)->orderBy('title','ASC')->get($table,null,'id,title');
        $str.='<option>-----o0o-----</option>';
        foreach((array)$list as $item){
            $str.='<option value="'.$item['id'].'">'.$item['title'].'</option>';
        }
        break;
    default:break;
}
echo $str;
?>