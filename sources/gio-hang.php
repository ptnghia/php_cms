<?php
if(isset($_POST['add_cart'])){
    $so_luong       =   (int)$_POST['so_luong'];
    $id_sp          =   (int)$_POST['id_sp'];
    $gia            =   $_POST['gia'];
    $thuoctinh      =   $_POST['thuoc_tinh'];
    $row_sp  = $d->simple_fetch("select * from #_sanpham where id_code = ".$id_sp." $where_lang ");
    $key_cart  = $id_sp.bodautv($thuoctinh);
    if(count($row_sp)>0){
        $_SESSION['cart'][$key_cart]['id_sp']       = $id_sp;
        $_SESSION['cart'][$key_cart]['gia']         = $gia;
        $_SESSION['cart'][$key_cart]['thuoc_tinh']  = $thuoctinh;
        if(isset($_SESSION['cart'][$id_pro])){
            $_SESSION['cart'][$key_cart]['so_luong'] = $_SESSION['cart'][$id_pro]['so_luong'] + $soluong;
        }
        else{
            $_SESSION['cart'][$key_cart]['so_luong'] = 1;
        }
    }
}

if(isset($_POST['update_cart']) and isset($_SESSION['cart'])){
    $arr_keycart    = $_POST['key_cart'];
    $arr_sl         = $_POST['sl'];
    $arr_sl_con     = $_POST['sl_con'];
    $arr_slmin      = $_POST['sl_min'];
    for($i=0;$i<count($arr_keycart);$i++){
        $key_cart       =   $arr_keycart[$i];
        $so_luong       =   $arr_sl[$i];
        $id_sp          =   $_SESSION['cart'][$key_cart]['id_sp'];
        $row_sp         =   $d->simple_fetch("select * from #_sanpham where id_code = ".$id_sp." $where_lang ");
        $so_luongcon    =   $arr_sl_con[$i];
        $min = $arr_slmin[$i];
        if($so_luong <= $so_luongcon and $so_luong >= $min){ 
            if($so_luong>1000){
                $gia = $row_sp['gia1'];
                if($row_sp['khuyen_mai1']>0){
                    $gia = $row_sp['khuyen_mai1'];
                }
                $_SESSION['cart'][$key_cart]['gia']         =   $gia;
                $_SESSION['cart'][$key_cart]['so_luong']    =   $so_luong; 
            }else{
                $_SESSION['cart'][$key_cart]['so_luong']    =   1; 
            }
        }
    }
    $d->redirect("".URLPATH.$com.".html");
}
if(!isset($_SESSION['id_login'])){
    $d->location(URLPATH.$d->getCate(23,'alias').".html");
    exit();
}
if(isset($_POST['guidonhang']) ){
    $ma_dh = 'DH-'.chuoird(5);
    token();
    $data['ma_dh']              =   $ma_dh;
    $data['ho_ten']             =   addslashes($_POST['ho_ten']);
    $data['id_thanhvien']       =   (int)$_SESSION['id_login'];
    $data['dien_thoai']         =   addslashes($_POST['dien_thoai']);
    $data['email']              =   addslashes($_POST['email']);
    $data['quan']               =   addslashes($_POST['code_huyen']);
    $data['thanh_pho']          =   addslashes($_POST['code_tinh']);
    $data['dia_chi']            =   addslashes($_POST['dia_chi']).', '.$d->getHuyen((int)$_POST['code_tinh'],'ten', (int)$_POST['code_huyen'])['ten'].', '.$d->getTinh('ten', (int)$_POST['code_tinh'])['ten'];
    $data['loi_nhan']           =   addslashes($_POST['loinhan']);
    $data['thanh_toan']         =   addslashes($_POST['phuongthucthanhtoan']);
    if(isset($_POST['dungdiem'])){
        $data['so_tien_giam']       =   addslashes($_POST['so_tien_giam']);
        $data['dung_diem']          =   addslashes($_POST['dung_diem']);
    }
    $data['ngay_dathang']       =   date('Y-m-d', time());
    $data['tinhtrang_donhang']  =   1;
    $d->reset();
    $d->setTable('#_dathang');
    if($id_dh = $d->insert($data)){
        $_SESSION['ma_dh'] =  $ma_dh;
        foreach ($_SESSION['cart'] as $key => $value) {
            $row_sp = $d->simple_fetch("select * from #_sanpham where id_code = '".$value['id_sp'] ."' "._where_lang." ");
            $gia = $value['gia'];
            $tong = $tong +($gia*$value['so_luong']);
            
            $data_ct['id_dh']       = $id_dh;
            $data_ct['ma_dh']       = $ma_dh;
            $data_ct['ten_sp']      = $row_sp['ten'];
            $data_ct['gia_ban']     = $gia;
            $data_ct['thuoc_tinh']  = $value['thuoc_tinh'];
            $data_ct['so_luong']    = $value['so_luong'];
            $data_ct['id_sp']       = $key;
            $data_ct['hinh_sp']     = $row_sp['hinh_sp'];
            $d->reset();
            $d->setTable('#_dathang_chitiet');
            $d->insert($data_ct);
        }
        if(isset($_SESSION['id_login']) and isset($_POST['dungdiem'])){
            $thanhvien = $user_login = $d->simple_fetch("select * from #_thanhvien where id= ".(int)$_SESSION['id_login']." and trang_thai = 1");
            $data_tv['diem'] = $_POST['diem_con'];
            $d->reset();
            $d->setTable('#_thanhvien');
            $d->setWhere('id', $thanhvien['id']);
            $d->update($data_tv);
        }
        unset($_SESSION['cart']);
        $thongbao_tt        =   'Mua hàng thành công';
        $thongbao_icon      =   'success';
        $thongbao_content   =   '';
        $thongbao_url       =  URLPATH.$com.'.html?success='.$ma_dh;
    }
}

$link=explode("?",$_SERVER['REQUEST_URI']);
if($link[1]!=''){
    $vari=explode("&",$link[1]);
    $search=array();
    foreach($vari as $item) {
        $str=explode("=",$item);
        $search["$str[0]"]=$str[1];
    }
}
if(isset($search['delete'])){
     unset($_SESSION['cart'][$search['delete']]);
     if(count($_SESSION['cart'])==0){
         unset($_SESSION['cart']);
     }
     $d->redirect("".URLPATH.$com.".html");
}

//unset($_SESSION['cart'])
?>
<div class="head_page mb-5">
    <div class=" container text-center head_page_content">
        <h1 class="title_page"><?=$row['ten']?></h1>
        <div class="d-flex justify-content-center">
            <nav aria-label="breadcrumb">
                <?=$d->breadcrumblist($row['id_code'])?>
            </nav>
        </div>
    </div>
</div>
<div class="container">
    <?php if(isset($search['success'])){ 
    $madonhang = validate_content($search['success']);
    $donhang = $d->simple_fetch("select * from #_dathang where ma_dh='$madonhang' ");
    $donhang_ct = $d->o_fet("select * from #_dathang_chitiet where ma_dh='$madonhang' ");
    if(isset($_POST['capnhat_thanhtoan']) and $_SESSION['token']   == $_POST['_token']){
        token();
        $targetDirectory = 'img_data/images/'; // Thư mục lưu trữ tệp tải lên
        $targetFile = $targetDirectory . basename($_FILES['unc']['name']); // Đường dẫn đến tệp được tải lên

        $newFileName = 'thanh-toan/'.$madonhang.'_'.time().'.jpg'; // Tên tệp mới
        $newFilePath = $targetDirectory . $newFileName;

        // Kiểm tra xem tệp có phải là hình ảnh hợp lệ hay không
        $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));
        $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif','webp'];

        if (!empty($_FILES['unc']['name']) && in_array($imageFileType, $allowedExtensions)) {
            // Di chuyển tệp đã tải lên vào thư mục đích và đổi tên
            if (move_uploaded_file($_FILES['unc']['tmp_name'], $newFilePath)) {
              $data['unc']    =   addslashes($newFileName);
            } else {
              //$data['avata']    ='';
            }
        } else {
            //echo 'Vui lòng chọn một tệp hình ảnh hợp lệ.';
        }


        $d->reset();
        $d->setTable('#_dathang');
        $d->setWhere('id',$donhang['id']);
        if($d->update($data)){
            $thongbao_tt        =   'Đã cập nhật thông tin thanh toán';
            $thongbao_icon      =   'success';
            $thongbao_content   =   '';
            $thongbao_url       =   URLPATH.$com.".html?success=DH-HL2CJ";
        }
    }
    ?>
    <div class="row justify-content-center">
        <div class="col-lg-6">
            <div class="br-dathang p-3"  style="background-color: #f5f5f5;border-radius: 10px;border: 1px solid #eee;">
                <div class="title-giohang text-center" style="text-transform: uppercase;font-weight: 600;margin-bottom: 20px;font-size: 18px;">Thông tin đơn hàng - <b style="color: var(--bs-secondary);"><?=$madonhang?></b></div>
                <table class="table table-dathang-tc">
                    <?php 
                    $thanhtien = 0;
                    foreach ($donhang_ct as $key => $value) {
                        $product = $d->getProduct($value['id_sp'],'*');
                        $thanhtien = $thanhtien+($value['so_luong']*$value['gia_ban']);
                    ?>
                    <tr>
                        <td class=" text-center">
                            <img src="<?=URLPATH?>img_data/images/<?=$product['hinh_anh']?>" width="100px" alt="<?=$product['ten']?>" />
                        </td>
                        <td>
                            <p style="font-size: 14px;"><b style="font-weight: 600;margin-bottom: 15px;display: block;font-size: 14px;"><?=$product['ten']?></b></p>
                            <div class="ct-giohang d-flex justify-content-between">
                                <span><span>Số lượng:</span> <b><?=$value['so_luong']?></b></span>
                                <span ><span>Giá:</span> <b style="font-family: 'Roboto Condensed',sans-serif;color: red;"><?=  numberformat($value['so_luong']*$value['gia_ban'])?> <sup>đ</sup></b></span>
                            </div>
                        </td>
                    </tr>
                    <?php } ?>
                    <tr style="font-weight: 600;font-size: 14px;">
                        <td class="text-end" style="font-weight: 500;font-size: 15px;">Tổng tiền:</td>
                        <td style="font-family: 'Roboto Condensed',sans-serif;color: red;text-align: right;"><?=  numberformat($thanhtien)?> đ</td>
                    </tr>
                    <?php if($donhang['dung_diem']>0){ ?>
                    <tr>
                        <th class="text-end" style="font-weight: 500;font-size: 15px;">Sử dụng điểm : </th>
                        <td class="text-end" style="font-family: 'Roboto Condensed',sans-serif;color: red;text-align: right;">
                            <b>-<?=  numberformat($donhang['so_tien_giam'])?>đ</b><br><i>(<?=  number_format($donhang['dung_diem'])?> điểm)</i></td>
                    </tr>
                    <tr style="font-weight: 600;font-size: 14px;">
                        <td class="text-end" style="font-weight: 500;font-size: 15px;">Thanh toán:</td>
                        <td style="font-family: 'Roboto Condensed',sans-serif;color: red;text-align: right;"><?=  numberformat($thanhtien-$donhang['so_tien_giam'])?> đ</td>
                    </tr>
                    <?php } ?>
                </table>
                <h4 style="padding: 10px;font-size: 16px;text-transform: capitalize;color: var(--bs-primary);text-align: center;font-weight: 600;">Thông tin giao hàng</h4>
                <table class="table table-thongtin">
                    <tr>
                        <td><b>Họ tên:</b></td>
                        <td><?=$donhang['ho_ten']?></td>
                    </tr>
                    <tr>
                        <td><b>Điện thoại:</b></td>
                        <td><?=$donhang['dien_thoai']?></td>
                    </tr>
                    <tr>
                        <td><b>Email:</b></td>
                        <td><?=$donhang['email']?></td>
                    </tr>
                    <tr>
                        <td><b>Thanh toán:</b></td>
                        <td><?=$donhang['thanh_toan']?></td>
                    </tr>
                    <tr>
                        <td><b>Ghi chú:</b></td>
                        <td><?=$donhang['loi_nhan']?></td>
                    </tr>
                </table>
                <?php if($donhang['unc']){ ?>
                <img src="<?=Img($donhang['unc'])?>" alt="" />
                <?php } ?>
                <?php if($donhang['trangthai_thanhtoan']==0 and $donhang['trangthai_xuly']!=3){ ?>
                
                <p class="mt-3"><b>Thanh toán:</b> Chuyển khoản số tiền <b><?=  numberformat($thanhtien)?>đ</b> với nội dung <b>Thanh toan don hang <?=$madonhang?></b></p>
                <div style="padding: 10px;border-radius: 10px;background-color: #ffff;margin-bottom: 10px;">
                    <?= $d->getContent_id(147)['noi_dung']?>
                </div>
                <form method="post" action="" class="form-cart" id="form-thongtin" enctype="multipart/form-data">
                    <input type="hidden" value="<?=$_SESSION['token']?>" name="_token" />
                    <div class="mb-3">
                        <label class="form-label">Tải lên hình ảnh thanh toán</label>
                        <input required="" class="form-control" name="unc" type="file">
                    </div>
                    <button class="btn btn_link w-100" name="capnhat_thanhtoan" type="submit"> <i class="fa-solid fa-credit-card"></i> Xác nhận đã thanh toán</button>
                </form>
                <?php } ?>
            </div>
        </div>
    </div>
    <?php }else{?>
    <?php if(count($_SESSION['cart'])>0){ ?>
    <form action="" id="form-shopping" method="post">
        <div class="row g-5 justify-content-center">
            <div class="col-sm-7" id="giohang">
                <table class="table table-giohang table-bordered align-middle">
                    <thead>
                        <tr>
                            <th colspan="2">Khóa học</th>
                            <th class="text-end">Giá</th>
                            <th class="text-center">Xóa</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $tongtien = $diem =  0;
                        foreach ($_SESSION['cart'] as $key => $value) {
                        $id_pro = $value['id_sp'];    
                        $row_sp = $d->simple_fetch("select * from #_sanpham where id_code = '".$id_pro."' "._where_lang." "); 
                        $cate = $d->getCate($row_sp['id_loai']);
                        
                        if(count($row_sp)>0){
                            $gia = $value['gia'];
                            $tongtien = $tongtien+($gia*$value['so_luong']);
                            
                            $diem = $diem+ ($gia*$value['so_luong'])*$cate['url'];
                        ?>
                        <tr>
                            <td class="text-center">
                                <img style="height: 80px;object-fit: contain;" src="<?=Img($row_sp['hinh_anh'])?>" alt="<?=$row_sp['ten']?>">
                            </td>
                            <td class="text-left pro-cart">
                                <?=$row_sp['ten']?>                            
                            </td>
                            <td class="text-end">
                                <b><?=  numberformat($gia)?><sup>đ</sup></b>
                            </td>
                            <td class="text-center">
                                 <a href="javascript:;" class="xoa_giohang btn btn-danger btn-sm" onclick="xoa_sp_gh_dh('<?=$key?>','<?=$d->getTxt(22)?>?')" ><i class="fa-solid fa-trash-can"></i></a>
                            </td>
                        </tr>  
                        <?php }} ?>
                        <tr class="tongtien">
                            <td colspan="2" class="text-end"><?=$d->getTxt(23)?>:</td>
                            <td colspan="" class="text-end"><span id="tong_tien_gh" class="tongtieng-cart"><?=  numberformat($tongtien)?>đ</span></td>
                            <td></td>
                        </tr>
                    </tbody>
                    <tfoot style="font-family: 'Roboto Condensed', sans-serif;">
                        <?php 
                        if(isset($_SESSION['id_login'])){  
                            $diem_ht = $thanhvien['diem'];
                            $tienhienco = $diem_ht*_secretkey;
                            if($tienhienco > 50000){
                                $tien_giam = 50000;
                                $diem_giam = 50000/_secretkey;
                                $diem_con = $diem_ht - $diem_giam;
                            }else{
                                $tien_giam = $tienhienco;
                                $diem_giam = $diem_ht;
                                $diem_con = 0;
                            }
                        
                        ?>
                        <tr class="an_diem" style=" display: none">
                            <th class="text-end">
                                <input type="hidden" name="dung_diem" value="<?=$diem_giam?>" />
                                <input type="hidden" name="so_tien_giam" value="<?=$tien_giam?>" />
                                <input type="hidden" name="diem_con" value="<?=$diem_con?>" />
                                Giảm giá: 
                            </th>
                            <th style="text-align: right;" class="tongtien">
                                <span class="tongtieng-cart">-<?=  numberformat($tien_giam)?>đ</span>
                            </th>
                        </tr>
                        <tr class="an_diem"  style="display: none">
                            <th class="text-end">
                                Thanh toán: 
                            </th>
                            <th style="text-align: right;" class="tongtien">
                                <span class="tongtieng-cart"><?=  numberformat($tongtien - $tien_giam)?>đ</span>
                            </th>
                        </tr>
                        <?php } ?>
                        <?php  
                        if(isset($_SESSION['id_login'])){  
                            $diem_ht = $thanhvien['diem'];?>
                        <?php if($diem_ht>=2500){ ?>
                        <tr>
                            <th>
                                <label onclick="check_diem()"><input type="checkbox" id="dungdiem" name="dungdiem" /> Sử dụng điểm</label>
                                <script>
                                    var checkbox = document.getElementById("dungdiem");
                                    checkbox.addEventListener("change", function() {
                                        // Kiểm tra trạng thái check
                                        if (checkbox.checked) {
                                            $('.an_diem').show()
                                        } else {
                                            $('.an_diem').hide()
                                        }
                                    });
                                </script>
                            </th>
                            <th style="text-align: right;font-weight: 400;;">
                                Điểm hiện có: <?=  number_format($diem_ht)?> điểm <=> <?=  numberformat($diem_ht*_secretkey)?> VNĐ<br>
                                <i>(Sử dụng tối đa <?=  number_format(50000/_secretkey)?> điểm <=> <?=  numberformat((50000/_secretkey)*_secretkey)?> VNĐ cho 1 lần thanh toán)</i>
                            </th>
                        </tr>
                        <?php } ?>
                        <?php } ?>
                        
                    </tfoot>
                </table>
                
                <div class="form-cart">
                    <input type="hidden" value="<?=$diem?>" name="diem" />
                    <div class="mb-3">
                        <input value="<?=$thanhvien['ho_ten']?>" required placeholder="<?=$d->getTxt(24)?> (*)"  type="text" class="form-control" id="ten" name="ho_ten" >
                    </div>
                    <div class="mb-3">
                        <input value="<?=$thanhvien['email']?>" placeholder="<?=$d->getTxt(25)?>" type="email" class="form-control" id="email" name="email">
                    </div>
                    <div class="mb-3">
                        <input value="<?=$thanhvien['dien_thoai']?>" placeholder="<?=$d->getTxt(26)?>(*)" required="" type="text" class="form-control" id="dienthoai" name="dien_thoai" data-error="Vui lòng nhập số điện thoại">
                    </div>
                    <!--div class="row">
                        <div class="col-md-6 mb-3">
                            <select class="tp_1 form-select" name="code_tinh" id="code_tinh" onchange="get_huyen('code_tinh', 'code_huyen')">
                                <option value=""><?=$d->getTxt(27)?></option>
                                <?php foreach ($d->getTinh('code,ten') as $key => $value) {?>
                                <option value="<?=$value['code']?>"><?=$value['ten']?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <select class="quan_1 form-select" id="code_huyen" name="code_huyen">
                                <option value=""><?=$d->getTxt(31)?></option>
                            </select>
                        </div>
                    </div>
                    <div class="mb-3">
                        <input value="" type="text" placeholder="<?=$d->getTxt(28)?>" class="form-control" id="diachi" name="dia_chi" data-error="Vui lòng nhập địa chỉ">
                    </div-->
                    <div class="mb-3">
                        <textarea id="loinhan" placeholder="<?=$d->getTxt(29)?>" class="form-control" rows="3" name="loinhan"></textarea>
                    </div>
                    <h2 class="title_thanhtoan"><?=$d->getTxt(30)?></h2>
                    <div class="mb-3">
                        <?php foreach ($d->getContents(146) as $key => $value) {?>
                        <label class="lab-thanhtoan" id="lab_<?=$value['id_code']?>" data="thanhtoan_1194">
                            <input required="" type="radio" <?php if($key==0){ ?>checked<?php }?> name="phuongthucthanhtoan" value="<?=$value['ten']?>"> 
                            <img src="<?=Img($value['hinh_anh'])?>">
                            <span><?=$value['ten']?></span>
                        </label>
                        <div class="thanhtoan" <?php if($key==0){ ?>style="display: block;"<?php } ?> id="thanhtoan_<?=$value['id_code']?>">
                            <?=$value['noi_dung']?>
                        </div>
                        <?php } ?>
                    </div>
                    <div class="form-group">
                        <button type="submit" class="btn btn-gioithieu w-100" name="guidonhang">Mua hàng</button>
                        <div class=" clearfix"></div>
                    </div>
                </div>
            </div>
            
        </div>
    </form>
    <?php }else{?>
    <p clas="text-center" style="font-size: 18px;    text-align: center;"><?=$d->getTxt(33)?></p>
    <?php } ?>
    <?php } ?>
</div>
<script type="text/javascript" src="https://ajax.aspnetcdn.com/ajax/jquery.validate/1.13.1/jquery.validate.min.js"></script>
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<script>
    <?php if(isset($thongbao_tt) and $thongbao_tt!=""){ ?>
    swal({
        title: '<?=$thongbao_tt ?>',
        text: '<?= $thongbao_content ?>',
        icon: '<?= $thongbao_icon ?>',
        button: false,
        timer: 2000
    }).then((value) => {
        window.location="<?=$thongbao_url?>";
    }); 
    <?php }?>
    function get_huyen(code_tinh,code_huyen){
        var id_quocgia = $('#'+code_tinh).val();
         $.ajax({
            url : "<?=URLPATH?>sources/ajax/ajax.php",
            type : "post",
            dataType:"text",
            data : {
                 do         : 'get_huyen',
                 code_tinh : id_quocgia
            },
            success : function (result){
                $('#'+code_huyen).html(result);
            }
        });

    }
function chang_soluong(obj,id){
    var sl = $(obj).val();
    $.ajax({
        url: "<?=URLPATH?>sources/ajax/ajax.php",
        type:'POST',
        data:{'do':'change_so_luong','id':id,'sl':sl},
        success: function(data){
            $('#giohang').html(data);
        }
    })
}
function xoa_sp_gh_dh(id,al){
    var cf = confirm(al);
    if(cf){
        $.ajax({
            url : "<?=URLPATH?>sources/ajax/ajax.php",
            type : "post",
            dataType:"text",
            data : {
                 do         : 'delete_cart',
                 id : id
            },
            success : function (){
                window.location.href="<?=$url_page ?>";
            }
        });
    }
}
</script>