<?php
include '../front.php';
$act=$_POST['act'];
$lang=isset($_GET['lang'])?$_GET['lang']:'vi';
$str='';
switch($act){
    case 'category':
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
        $desc=$_POST['desc'];
        $table=htmlspecialchars($_POST['table']);
        if($pId!=0) $db->where('pId',$pId);
        $list=$db->orderBy('title','ASC')->get($table,null,'id,title');
        $str.='<option>'.$desc.'</option>';
        foreach((array)$list as $item){
            $str.='<option value="'.$item['id'].'">'.$item['title'].'</option>';
        }
        break;
    default:break;
}
echo $str;
?>