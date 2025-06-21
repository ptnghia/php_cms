<?php
if (!defined('_source')) die("Error");
$a = (isset($_REQUEST['a'])) ? addslashes($_REQUEST['a']) : "";

$link_option = '';
if (isset($_GET['search'])) {
    $link_option .= '&search=' . addslashes($_GET['search']);
}
if (isset($_GET['key'])) {
    $link_option .= '&key=' . addslashes($_GET['key']);
}
if (isset($_GET['page'])) {
    $link_option .= '&page=' . addslashes($_GET['page']);
}
$row_setting = $d->simple_fetch("select setting from #_module where id = 3 ");
$setting = $row_setting['setting'];
$arrr_setting = json_decode($setting, true);

switch ($a) {
    case "man":
        showdulieu();
        $template = @$_REQUEST['p'] . "/hienthi";
        break;
    case "add":
        showdulieu();
        $template = @$_REQUEST['p'] . "/them";
        break;
    case "coppy":
        showdulieu();
        $template = @$_REQUEST['p'] . "/coppy";
        break;
    case "edit":
        showdulieu();
        $template = @$_REQUEST['p'] . "/them";
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
function show_menu_tintuc_hd($menus = array(), $parrent = 0, &$chuoi = '')
{
    foreach ($menus as $val) {
        if ($val['id_loai'] == $parrent) {
            $chuoi .= $val['id'] . ',';
            show_menu_tintuc_hd($menus, $val['id'], $chuoi);
        }
    }
    return $chuoi;
}
function showdulieu()
{
    global $d, $items, $limit, $loai, $loai_khac, $total_page, $where_search;
    $loai = $d->array_category(0, '', $_GET['loaitin'], 3);
    $loai_khac = $d->array_category_multi(0, '=', '', 3);
    if ($_REQUEST['a'] == 'man') {
        if (isset($_GET['search']) and $_GET['key'] != '' and $_GET['search'] != '') {
            if ($_GET['search'] == 'loai') {
                $id_code = $_GET['key'];
                $list_id = $id_code . $d->getIdsub($id_code);
                $where_search = " and id_loai in ($list_id)";
                $loai = $d->array_category(0, '', $id_code, 3);
            } else {
                $col    =   addslashes($_GET['search']);
                $value  =   addslashes($_GET['key']);
                $where_search = " and $col like '%" . $value . "%' ";
            }
        }
        $limit = 10;
        $items = $d->o_fet("select * from #_sanpham where lang ='" . LANG . "' $where_search order by so_thu_tu asc, cap_nhat desc, id desc limit 0, $limit");
        $total_records = $d->num_rows("select * from #_sanpham where lang ='" . LANG . "' $where_search order by so_thu_tu asc, cap_nhat desc, id desc");
        $total_page = ceil($total_records / $limit);
    } elseif ($_REQUEST['a'] == 'coppy' and isset($_REQUEST['id'])) {
        if (isset($_REQUEST['id'])) {
            @$id = addslashes($_REQUEST['id']);
            $items = $d->o_fet("select * from #_sanpham where id_code =  '" . $id . "'");
            $loai = $d->array_category(0, '', $items[0]['id_loai'], 3);
        }
    } else {
        if (isset($_REQUEST['id'])) {
            @$id = addslashes($_REQUEST['id']);
            $items = $d->o_fet("select * from #_sanpham where id_code =  '" . $id . "'");
            $loai = $d->array_category(0, '', $items[0]['id_loai'], 3);
            $loai_khac = $d->array_category_multi(0, '=', $items[0]['id_loai_khac'], 3);
        }
    }
}

function luudulieu($id_module)
{

    global $d;
    global $link_option;
    global $arrr_setting;
    if ($arrr_setting['option_resize'] != '') {
        $option_resize = $arrr_setting['option_resize'];
    } else {
        $option_resize = "auto";
    }
    if ($d->checkPermission_edit($id_module) == 1) {
        $id = (isset($_REQUEST['id'])) ? addslashes($_REQUEST['id']) : "";

        $file_name = $d->fns_Rand_digit(0, 9, 12);
        if ($id != '') {

            $alias0      = addslashes($_POST['alias'][0]);
            if ($d->checkLink($alias0, $id) == 0) {
                $alias0 .= "-" . rand(0, 9);
            }
            $hinh_anh = addslashes($_POST['hinh_anh']);
            $hinh_anh2 = addslashes($_POST['hinh_anh2']);
            if ($file_download = Uploadfile("file_download", 'file', '../img_data/files/', $alias0)) {
                $hinhanh = $d->o_fet("select file from #_sanpham where id_code = '" . $id . "'");
                @unlink('../img_data/files/' . $hinhanh[0]['file']);
                $file_dw = $file_download;
            }
            $id_loai    =   addslashes($_POST['id_loai']);
            $so_thu_tu  =   $_POST['so_thu_tu'] != '' ? $_POST['so_thu_tu'] : 0;
            $hien_thi   =   isset($_POST['hien_thi']) ? 1 : 0;

            $data0['ten']   =   addslashes($_POST['ten'][0]);
            $d->reset();
            $d->setTable('cf_parent');
            $d->setWhere('id', $id);
            if ($d->update($data0)) {


                //upload hình album
                $arr_img = $_POST['album'];
                if (!empty($arr_img)) {
                    for ($i = 0; $i < count($arr_img); $i++) {
                        $data_img['id_sp'] = $id;
                        $data_img['hinh_anh'] = $arr_img[$i];
                        $data_img['stt'] = $i;
                        $data_img['title'] = '';
                        $d->reset();
                        $d->setTable('#_sanpham_hinhanh');
                        $d->insert($data_img);
                    }
                }


                foreach (get_json('lang') as $key => $value) {
                    $data['id_loai'] = $id_loai;
                    $data['video'] = $d->clear(addslashes($_POST['video']));
                    $data['file'] = $file_dw;
                    $data['link_khac'] = $d->clear(addslashes($_POST['link_khac']));
                    if ($_POST['link_khac'] != '') {
                        $data['loai_file']  = addslashes($_POST['loai_file']);
                    }
                    $data['ten'] = $d->clear(addslashes($_POST['ten'][$key]));
                    $data['slug']            = addslashes($_POST['slug'][$key]);
                    if ($hinh_anh != '') {
                        $data['hinh_anh'] = $hinh_anh;
                    }
                    if ($hinh_anh2 != '') {
                        $data['hinh_anh2'] = $hinh_anh2;
                    }
                    $data['mo_ta'] = $d->clear($_POST['mo_ta'][$key]);
                    $data['noi_dung'] = $d->clear($_POST['noi_dung'][$key]);
                    $data['noi_dung_2'] = $d->clear($_POST['noi_dung_2'][$key]);
                    if (isset($_POST['id_loai_khac'])) {
                        $id_loai_khac = "," . implode(",", $_POST['id_loai_khac']) . ",";
                        $data['id_loai_khac'] = $id_loai_khac;
                    } else {
                        $data['id_loai_khac'] = "";
                    }
                    if (isset($_POST['nameinfo_' . $value['code']])) {
                        $arr_info = array();
                        foreach ($_POST['nameinfo_' . $value['code']] as $i => $items) {
                            $detail_info = $_POST['detailinfo_' . $value['code']][$i];
                            $name_info = $items;
                            if ($name_info != '' and $detail_info != '') {
                                array_push($arr_info, $name_info . '%%%' . $detail_info);
                            }
                        }

                        if ($_POST['nameinfo_' . $value['code']][0] == '' && empty($arr_info)) {
                            $str_arr = "";
                            $data['thong_so_kt'] = ""; // Changed from thong_so_kt to thong_so to match the field name
                        } else {

                            $str_arr = json_encode($arr_info);

                            if ($str_arr === false) {
                                // Handle JSON encoding error
                                $str_arr = "";
                                $data['thong_so_kt'] = "";
                            } else {
                                $data['thong_so_kt'] = $str_arr;
                            }
                        }
                    }
                    $data['alias']          = addslashes($_POST['alias'][$key]);
                    if ($d->checkLink($data['alias'], $_POST['id_row'][$key]) == 0) {
                        $data['alias'] .= "-" . rand(0, 9);
                    }
                    $data['dvt']            =   $d->clear(addslashes($_POST['dvt'][$key]));
                    $data['title']          =   $d->clear(addslashes($_POST['title'][$key]));
                    $data['keyword']        =   $d->clear(addslashes($_POST['keyword'][$key]));
                    $data['des']            =   addslashes($_POST['des'][$key]);
                    $data['seo_head']       = addslashes($_POST['seo_head'][$key]);
                    $data['seo_body']       = addslashes($_POST['seo_body'][$key]);
                    $data['cap_nhat']       =   time();
                    $data['hien_thi']       =   $hien_thi;
                    $data['nofollow']       =   isset($_POST['nofollow']) ? 1 : 0;
                    $data['noindex']        =   isset($_POST['noindex']) ? 1 : 0;
                    $data['so_thu_tu']      =   $so_thu_tu;
                    $data['ma_sp']          =   $d->clear(addslashes($_POST['ma_sp']));
                    $data['gia']            =   (int)$_POST['gia'];
                    $data['khuyen_mai']     =   (int)$_POST['khuyen_mai'];

                    $d->reset();
                    $d->setTable('#_sanpham');
                    $d->setWhere('id', $_POST['id_row'][$key]);
                    $d->update($data);
                }
                $d->redirect("index.php?p=san-pham&a=man" . $link_option);
            } else {
                $d->alert("Cập nhật dữ liệu bị lỗi!");
                $d->redirect("Cập nhật dữ liệu bị lỗi", "index.php?p=san-pham&a=man" . $link_option);
            }
        } else {

            $alias0      = addslashes($_POST['alias'][0]);
            if ($d->checkLink($alias0) == 0) {
                $alias0 .= "-" . rand(0, 9);
            }
            $hinh_anh = addslashes($_POST['hinh_anh']);
            $hinh_anh2 = addslashes($_POST['hinh_anh2']);
            if ($file_download = Uploadfile("file_download", 'file', '../img_data/files/', $alias0)) {
                $file_dw = $file_download;
            }
            $id_loai        =   addslashes($_POST['id_loai']);
            $so_thu_tu      =   $_POST['so_thu_tu'] != '' ? $_POST['so_thu_tu'] : 0;
            $hien_thi       =   isset($_POST['hien_thi']) ? 1 : 0;
            $data0['ten']   =   addslashes($_POST['ten'][0]);
            $d->reset();
            $d->setTable('cf_parent');
            if ($id_code = $d->insert($data0)) {
                //upload hình album
                $arr_img = $_POST['album'];
                if (!empty($arr_img)) {
                    for ($i = 0; $i < count($arr_img); $i++) {
                        $data_img['id_sp'] = $id_code;
                        $data_img['hinh_anh'] = $arr_img[$i];
                        $data_img['stt'] = $i;
                        $data_img['title'] = '';
                        $d->reset();
                        $d->setTable('#_sanpham_hinhanh');
                        $d->insert($data_img);
                    }
                }
                foreach (get_json('lang') as $key => $value) {
                    $data['id_loai'] = $id_loai;
                    $data['video'] = $d->clear(addslashes($_POST['video']));
                    $data['file'] = $file_dw;
                    $data['link_khac'] = $d->clear(addslashes($_POST['link_khac']));
                    if ($_POST['link_khac'] != '') {
                        $data['loai_file']  = addslashes($_POST['loai_file']);
                    }
                    $data['ten'] = $d->clear(addslashes($_POST['ten'][$key]));
                    $data['slug']            = addslashes($_POST['slug'][$key]);
                    $data['hinh_anh'] = $hinh_anh;
                    $data['hinh_anh2'] = $hinh_anh2;
                    $data['mo_ta'] = $d->clear($_POST['mo_ta'][$key]);
                    $data['noi_dung'] = $d->clear($_POST['noi_dung'][$key]);
                    $data['noi_dung_1'] = $d->clear($_POST['noi_dung_1'][$key]);
                    $data['noi_dung_2'] = $d->clear($_POST['noi_dung_2'][$key]);
                    if (isset($_POST['id_loai_khac'])) {
                        $id_loai_khac = "," . implode(",", $_POST['id_loai_khac']) . ",";
                        $data['id_loai_khac'] = $id_loai_khac;
                    } else {
                        $data['id_loai_khac'] = "";
                    }
                    if (isset($_POST['nameinfo_' . $value['code']])) {
                        $arr_info = array();
                        foreach ($_POST['nameinfo_' . $value['code']] as $i => $items) {
                            $detail_info = $_POST['detailinfo_' . $value['code']][$i];
                            $name_info = $items;
                            if ($name_info != '' and $detail_info != '') {
                                array_push($arr_info, $name_info . '%%%' . $detail_info);
                            }
                        }

                        if ($_POST['nameinfo_' . $value['code']][0] == '' && empty($arr_info)) {
                            $str_arr = "";
                            $data['thong_so_kt'] = ""; // Changed from thong_so_kt to thong_so to match the field name
                        } else {
                            $str_arr = json_encode($arr_info);
                            if ($str_arr === false) {
                                // Handle JSON encoding error
                                $str_arr = "";
                                $data['thong_so_kt'] = "";
                            } else {
                                $data['thong_so_kt'] = $str_arr;
                            }
                        }
                    }

                    $data['alias'] = $d->clear(addslashes($_POST['alias'][$key]));
                    if ($d->checkLink($data['alias'], $id) == 0) {
                        $data['alias'] .= "-" . rand(10, 999);
                    }
                    $data['title']          =   $d->clear(addslashes($_POST['title'][$key]));
                    $data['dvt']            =   $d->clear(addslashes($_POST['dvt'][$key]));
                    $data['keyword']        =   $d->clear(addslashes($_POST['keyword'][$key]));
                    $data['des']            =   addslashes($_POST['des'][$key]);
                    $data['seo_head']       =   addslashes($_POST['seo_head'][$key]);
                    $data['seo_body']       =   addslashes($_POST['seo_body'][$key]);
                    $data['ngay_dang']      =   time();
                    $data['cap_nhat']       =   time();
                    $data['hien_thi']       =   $hien_thi;
                    $data['nofollow']       =   isset($_POST['nofollow']) ? 1 : 0;
                    $data['noindex']        =   isset($_POST['noindex']) ? 1 : 0;
                    $data['so_thu_tu']      =   $so_thu_tu;
                    $data['ma_sp']          =   $d->clear(addslashes($_POST['ma_sp']));
                    $data['gia']            =   (int)$_POST['gia'];
                    $data['khuyen_mai']     =   (int)$_POST['khuyen_mai'];
                    $data['id_code']        =   $id_code;
                    $data['lang']           =   $value['code'];
                    $d->reset();
                    $d->setTable('#_sanpham');
                    $d->insert($data);
                }
                $d->redirect("index.php?p=san-pham&a=man" . $link_option);
            } else {
                $d->alert("Thêm dữ liệu bị lỗi!");
                $d->redirect("Thêm dữ liệu bị lỗi", "index.php?p=san-pham&a=man" . $link_option);
            }
        }
    } else {
        $d->redirect("index.php?p=san-pham&a=man&" . $link_option);
    }
}

function xoadulieu($id_module)
{
    global $d;
    global $link_option;
    if ($d->checkPermission_dele($id_module) == 1) {
        if (isset($_GET['id'])) {
            $id =  addslashes($_GET['id']);
            $d->reset();
            $d->setTable('#_sanpham');
            $d->setWhere('id_code', $id);
            if ($d->delete()) {
                $d->o_que("delete from cf_parent where id = $id ");
                $d->o_que("delete from #_sanpham_hinhanh where id_sp = $id ");
                $d->o_que("delete from #_sanpham_chitiet where id_sp = $id ");
                $d->redirect("index.php?p=san-pham&a=man" . $link_option);
            } else {
                $d->alert("Xóa dữ liệu bị lỗi!");
                $d->redirect("Xóa dữ liệu bị lỗi", "index.php?p=san-pham&a=man" . $link_option);
            }
        } else {
            $d->alert("Không nhận được dữ liệu!");
            $d->redirect("Xóa dữ liệu bị lỗi", "index.php?p=san-pham&a=man" . $link_option);
        }
    } else {
        $d->redirect("index.php?p=san-pham&a=man" . $link_option);
    }
}

function xoadulieu_mang($id_module)
{
    global $d;
    global $link_option;
    if ($d->checkPermission_dele($id_module) == 1) {
        if (isset($_POST['chk_child'])) {
            $chuoi = "";
            foreach ($_POST['chk_child'] as $val) {
                $chuoi .= $val . ',';
            }
            $chuoi = trim($chuoi, ',');
            if ($d->o_que("delete from #_sanpham where id_code in ($chuoi)")) {
                $d->o_que("delete from cf_parent where id in ($chuoi) ");
                $d->o_que("delete from #_sanpham_hinhanh where id_sp in ($chuoi) ");
                $d->o_que("delete from #_sanpham_chitiet where id_sp in ($chuoi)  ");

                $d->redirect("index.php?p=san-pham&a=man" . $link_option);
            } else {
                $d->alert("Không nhận được dữ liệu!");
                $d->redirect("Xóa dữ liệu bị lỗi", "index.php?p=san-pham&a=man" . $link_option);
            }
        } else $d->redirect("index.php?p=san-pham&a=man" . $link_option);
    } else {
        $d->redirect("index.php?p=san-pham&a=man" . $link_option);
    }
}