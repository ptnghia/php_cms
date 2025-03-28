<?php
if(!isset($_SESSION)){
    session_set_cookie_params(['SameSite' => 'None']);
    session_start();
}
ob_start();
error_reporting(0);
define('_lib','../../admin/lib/');
define('_source_lib','../lib/');
global $d;
global $lang;
include _lib."config.php";
include_once _lib."function.php";
include_once _lib."class.php";
$d = new func_index($config['database']);
include_once _source_lib."lang.php";
include_once _source_lib."info.php";
include_once _source_lib."function.php";
$do = validate_content($_POST['do']);
$so_luong       =   (int)$_POST['so_luong'];
$id_sp          =   (int)$_POST['id_sp'];
$thuoctinh      =   (int)$_POST['thuoctinh'];
$gia            =   $_POST['gia'];
$row_sp  = $d->simple_fetch("select * from #_sanpham where id_code = ".$id_sp." $where_lang ");
$key_cart  = $id_sp;
if(count($row_sp)>0){
    $_SESSION['cart'][$key_cart]['id_sp']       = $id_sp;
    $_SESSION['cart'][$key_cart]['hinh_anh']    = $row_sp['hinh_anh'];
    $_SESSION['cart'][$key_cart]['ma_sp']       = '';
    $_SESSION['cart'][$key_cart]['gia']         = $gia;
    $_SESSION['cart'][$key_cart]['so_luong']    = $so_luong;
    echo 0;
    exit();
}