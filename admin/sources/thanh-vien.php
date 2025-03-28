<?php
if(!defined('_source')) die("Error");
$a = (isset($_REQUEST['a'])) ? addslashes($_REQUEST['a']) : "";
if($_GET['form']==1){
    $temp='hienthi_'.$_GET['form'];
}else{
    $temp='hienthi';
}
switch($a){
    case "man":
        showdulieu();
        $template = @$_REQUEST['p']."/".$temp;
    break;
    case "add":
        showdulieu();
        $template = @$_REQUEST['p']."/them";
    break;
    case "edit":
        showdulieu();
        $template = @$_REQUEST['p']."/them";
    break;
    case "save":
        luudulieu($id_module);
    break;
    case "delete":
        xoadulieu($id_module);
    break;
    case "delete_all":
        xoadulieu_mang($id_module);
    break;
    default:
        $template = "index";
}
function showdulieu(){
	global $d, $items, $paging, $loai, $soluong;
	//$loai = $d->array_category(0,'',$_GET['loaitin'],2);
        $loai = $d->array_category(0,'','',2);
	if($_REQUEST['a'] == 'man'){
            if($_GET['form']==1){
                $items = $d->o_fet("select * from #_form_order.".$_GET['form']." order by id desc");
            }else{
                $items = $d->o_fet("select * from #_thanhvien order by id desc");
            }
	}else{
            if(isset($_REQUEST['id'])){
                @$id = addslashes($_REQUEST['id']);
                $items = $d->simple_fetch("select * from #_thanhvien where id =  '".$id."' ");
            }
	}
}

function luudulieu($id_module){
    
    global $d;
    global $link_option;
    global $arrr_setting;
    
    if($d->checkPermission_edit($id_module)==1){
	$id = (isset($_REQUEST['id'])) ? addslashes($_REQUEST['id']) : "";
	$file_name=$d->fns_Rand_digit(0,9,12);
	if($id != '')
	{
            $data['ho_ten']         = $d->clear(addslashes($_POST['ho_ten']));
            $data['email']          = $d->clear(addslashes($_POST['email']));
            $data['dien_thoai']     = $d->clear(addslashes($_POST['dien_thoai']));
            $data['dia_chi']        = $d->clear(addslashes($_POST['dia_chi']));
            if($_POST['mat_khau']!=''){
                $data['mat_khau']        = MD5($_POST['mat_khau']);
            }
            $d->reset();
            $d->setTable('#_thanhvien');
            $d->setWhere('id', $id );
            if($d->update($data)){
                $d->redirect("index.php?p=thanh-vien&a=man".$link_option);    
            }else{
                $d->alert("Cập nhật dữ liệu bị lỗi!");
                $d->redirect("Cập nhật dữ liệu bị lỗi", "index.php?p=thanh-vien&a=man".$link_option);
            }
	}else{
            
	}
    }else{
        $d->redirect("index.php?p=thanh-vien&a=man&loaitin=".@$_GET['loaitin']);
    }
}
function xoadulieu($id_module){
    global $d;
    if($d->checkPermission_dele($id_module)==1){
        if(isset($_GET['id'])){
            $id =  addslashes($_GET['id']);
            $d->reset();
            $d->setTable('#_thanhvien');
            $d->setWhere('id',$id);
            if($d->delete()){
                
                for($i=42;$i<51;$i++){
                    $d->o_que("delete from db_form_".$i." where thanhvien_id = ".$_GET['id']." ");
                }
                //$d->o_que("delete from db_form_47_chitiet where id_form = ".$_GET['id']." ");
                
                $d->redirect("index.php?p=thanh-vien&a=man");
            }else{
                $d->alert("Xóa dữ liệu bị lỗi!");
                $d->redirect("Xóa dữ liệu bị lỗi", "index.php?p=thanh-vien&a=man");
            }
        }else {
            $d->alert("Không nhận được dữ liệu!");
            $d->redirect("Xóa dữ liệu bị lỗi", "index.php?p=&a=man");
        }
    }else{
        $d->redirect("index.php?p=thanh-vien&a=man");
    }
	
}

function xoadulieu_mang($id_module){
    global $d;
    global $link_option;
    if($d->checkPermission_dele($id_module)==1){
        if(isset($_POST['chk_child'])){
            $chuoi = "";
            foreach ($_POST['chk_child'] as $val) {
                $chuoi .=$val.',';
            }
            $chuoi = trim($chuoi,',');
            if($d->o_que("delete from #_thanhvien where id in ($chuoi)")){
                $d->o_que("delete from db_dathang_chitiet where id_dh in ($chuoi) ");
                $d->redirect("index.php?p=thanh-vien&a=man");
            }
            else{
                $d->alert("Không nhận được dữ liệu!");
                $d->redirect("Xóa dữ liệu bị lỗi", "index.php?p=thanh-vien&a=man");
            } 
        }else $d->redirect("index.php?p=thanh-vien&a=man");
    }else{
        $d->redirect("index.php?p=thanh-vien&a=man");
    }
}