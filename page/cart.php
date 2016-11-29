<?php
include_once '../front.php';
common::load('cart');
$obj=new cart($db);
$act=$_POST['act'];
$product_id=intval($_POST['product_id']);
$product_qty=intval($_POST['product_qty']);
switch($act){
    case 'multi_update':
        $obj->cart_update_multi();
        break;
    case 'update':
        $obj->cart_update($product_id,$product_qty);
        break;
    case 'empty':
        $obj->cart_empty();
        break;
    case 'del':
        $obj->cart_remove($product_id);
        break;
    default:
        $obj->cart_add($product_id,$product_qty);
        break;
}
echo $obj->cart_count();
?>