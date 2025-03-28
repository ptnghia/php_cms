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
if($do=='change_so_luong'){
    $id_code_sp = $_POST['id'];
    $so_luong = (int)$_POST['sl'];
    $_SESSION['cart'][$id_code_sp]['so_luong'] = $so_luong;
    if(isset($_SESSION['id_login'])){
        $thanhvien = $user_login = $d->simple_fetch("select * from #_thanhvien where id= ".(int)$_SESSION['id_login']." and trang_thai = 1");
        $diem_ht = $thanhvien['diem'];
        
    }
?>
<table class="table table-giohang">
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
                <img style="height: 130px;object-fit: contain;" src="<?=Img($row_sp['hinh_anh'])?>" alt="<?=$row_sp['ten']?>">
            </td>
            <td class="text-left pro-cart">
                <p class="name-pro"><?=$row_sp['ten']?></p>
                <p class=" d-flex justify-content-between"><span><strong><?=$d->getTxt(20)?>:</strong> <?=  numberformat($gia)?>đ</span>  <span><strong>Size:</strong> <?=$value['thuoc_tinh']?></span></p>
                <p></p>
                <p><strong><?=$d->getTxt(19)?>:</strong> 
                        <input name="soluong" style="width: 100px;" type="number" value="<?=$value['so_luong']?>" onchange="chang_soluong(this,'<?=$key?>')" class="text-center txt_sl soluong_">
                </p>
                <p><strong><?=$d->getTxt(21)?>:</strong> <span class="gia-cart"><?=  numberformat($gia*$value['so_luong'])?>đ</span></p>
                <a href="javascript:;" class="xoa_giohang" onclick="xoa_sp_gh_dh('<?=$key?>','<?=$d->getTxt(22)?>?')" ><i class="fa-solid fa-trash-can"></i></a>
            </td>
        </tr>  
        <?php }} ?>
        <tr class="tongtien">
            <td class="text-end"><?=$d->getTxt(23)?>:</td>
            <td class="text-end"><span id="tong_tien_gh" class="tongtieng-cart"><?=  numberformat($tongtien)?>đ</span></td>
        </tr>
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
    </tbody>
</table>

<?php }elseif($do=='xoa_sp_gh'){
    $id_code_sp = (int)$_POST['id'];
    unset($_SESSION['cart'][$id_code_sp]);
    if(count($_SESSION['cart'])==0){
         unset($_SESSION['cart']);
    }
}elseif($do=='get_img_product'){
    $id_tt = (int)$_POST['id'];
    $thuoctinh = $d->simple_fetch("select * from #_sanpham_chitiet where id = '".$id_tt."' ");
    if(count($thuoctinh)>0){
        $id_sp = $thuoctinh['id_sp'];
        $hinh_anh_sp = $d -> o_fet("select * from #_sanpham_hinhanh where id_sp = ".$id_sp." and id_chitiet = ".$id_tt." ");?>
        <div class="slider slider-single hinh-sp">
            <?php if(count($hinh_anh_sp)==0){ ?>
            <div class="big_img">
                <a style="cursor: zoom-in;" data-fancybox="images" href="<?=URLPATH ?>img_data/images/<?=$ctsp[0]['hinh_anh'] ?>"> 
                    <img src="<?=URLPATH ?>img_data/images/<?=$ctsp[0]['hinh_anh'] ?>" />
                </a>
            </div>
            <?php }?>
            <?php foreach ($hinh_anh_sp as $key => $item) { ?>
            <div class="big_img">
                <a style="cursor: zoom-in;" data-fancybox="images" href="<?=URLPATH ?>img_data/images/<?=$item['hinh_anh'] ?>"> 
                    <img src="<?=URLPATH ?>img_data/images/<?=$item['hinh_anh'] ?>" />
                </a>
            </div>
            <?php }?>
        </div>
        <div class="slide-sp slider slider-nav">
            <?php if(count($hinh_anh_sp)==0){ ?>
            <div class="item-thumb">
                <a class="thumb-item" >
                    <img src="<?=URLPATH ?>img_data/images/<?=$ctsp[0]['hinh_anh'] ?>" />
                </a>
            </div>
            <?php }?>
            <?php foreach ($hinh_anh_sp as $key => $item) { ?>
            <div class="item-thumb">
                <a class="thumb-item" >
                    <img src="<?=URLPATH ?>img_data/images/<?=$item['hinh_anh'] ?>" />
                </a>
            </div>
            <?php } ?>
        </div>
        <script>
            var w = $('.big_img').width();
            $('.big_img a').height(w*0.7);
            $('.slider-single').slick({
                slidesToShow: 1,
                slidesToScroll: 1,
                arrows: false,
                fade: false,
                adaptiveHeight: true,
                infinite: true,
                useTransform: true,
                speed: 400,
                cssEase: 'cubic-bezier(0.77, 0, 0.18, 1)',
                prevArrow:'<button type="button" class="slick-prev tour-prev"><i class="fa fa-chevron-left" aria-hidden="true"></i></button>',
                nextArrow:'<button type="button" class="slick-next tour-next"><i class="fa fa-chevron-right" aria-hidden="true"></i></button>',
            });

            $('.slider-nav')
            .on('init', function(event, slick) {
                $('.slider-nav .slick-slide.slick-current').addClass('is-active');
            })
            .slick({
                slidesToShow: 5,
                slidesToScroll: 1,
                autoplay:false,
                autoplaySpeed: 4000,
                vertical: false,
                arrows:true,
                infinite: true, 
                responsive: [
                    {
                      breakpoint: 1024,
                      settings: {
                        slidesToShow: 4,
                        slidesToScroll: 3,
                        infinite: true,
                        dots: true
                      }
                    },
                    {
                      breakpoint: 600,
                      settings: {
                        slidesToShow: 3,
                        slidesToScroll: 2
                      }
                    },
                    {
                      breakpoint: 480,
                      settings: {
                        slidesToShow: 3,
                        slidesToScroll: 1
                      }
                    }
                  ]
            });
            $('.slider-single').on('afterChange', function(event, slick, currentSlide) {
                $('.slider-nav').slick('slickGoTo', currentSlide);
                var currrentNavSlideElem = '.slider-nav .slick-slide[data-slick-index="' + currentSlide + '"]';
                $('.slider-nav .slick-slide.is-active').removeClass('is-active');
                $(currrentNavSlideElem).addClass('is-active');
            });

            $('.slider-nav').on('click', '.slick-slide', function(event) {
                event.preventDefault();
                var goToSingleSlide = $(this).data('slick-index');
                $('.slider-single').slick('slickGoTo', goToSingleSlide);
            });
        </script>
    <?php }
}elseif($do=='get_ma_product'){
    $id_tt = (int)$_POST['id'];
    $thuoctinh = $d->simple_fetch("select * from #_sanpham_chitiet where id = '".$id_tt."' ");
    echo $thuoctinh['ma'];
}elseif($do=='get_gia_product'){
    $id_tt = (int)$_POST['id'];
    $thuoctinh = $d->simple_fetch("select * from #_sanpham_chitiet where id = '".$id_tt."' ");
    echo '<span>'.numberformat($thuoctinh['gia']).'<sup>đ</sup></span>';
}elseif($do=='get_total_page'){
    
    
    if($_POST['chuyenmuc']!=''){
        $id_loai        =   trim(validate_content($_POST['chuyenmuc']),',');    
         $arr_loai = explode(',', $id_loai);
         $str_id_loai='';
         for($i=0;$i<count($arr_loai);$i++){
             $str_id_loai .= $arr_loai[$i].$d->getIdsub($arr_loai[$i]).',';
         }
         if( trim($str_id_loai,',')!=''){
             $where_loai = " and db_sanpham.id_loai in (".trim($str_id_loai,',').") ";
         }else{
             $where_loai='';
         }
     }else{
         $where_loai='';
     }

     if($_POST['thuoctinh']!=''){
         $id_thuoctitnh  =   trim(validate_content($_POST['thuoctinh']),',');
         $arr_thuoctinh = explode(',', $id_thuoctitnh);
         $str_id_thuoctinh='';
         for($i=0;$i<count($arr_thuoctinh);$i++){
             $str_id_thuoctinh .=$arr_thuoctinh[$i].',';
         }
         if( trim($str_id_thuoctinh,',')!=''){
             $where_thuoctinh = " and db_sanpham_chitiet.id in (".trim($str_id_thuoctinh,',').") ";
         }else{
           $where_thuoctinh='';  
         }
     }else{
         $where_thuoctinh=''; 
     }
    $count_sp = $d->num_rows("SELECT db_sanpham.* FROM `db_sanpham` JOIN db_sanpham_chitiet ON db_sanpham.id_code = db_sanpham_chitiet.id_sp WHERE  db_sanpham.hien_thi =1 $where_loai $where_thuoctinh "._where_lang." GROUP BY db_sanpham.id_code order by db_sanpham.so_thu_tu ASC, db_sanpham_chitiet.id DESC");
    $limit = 20;//get_json('product', 'paging');
    echo $total_page = ceil($count_sp / $limit);
   
}elseif($do=='get_huyen'){
    $code_tinh= addslashes($_POST['code_tinh']);
    echo '<option value="" >Chọn Quận / Huyện</option>';
    foreach ($d->getHuyen($code_tinh,'code, ten') as $key => $value) {
        echo '<option value="'.$value['code'].'" >'.$value['ten'].'</option>';
    }
}elseif($do=='get_xa'){
    $code_huyen = addslashes($_POST['code_huyen']);
    echo '<option value="" >Chọn phường/xã</option>';
    foreach ($d->getXa($code_huyen,'code,ten') as $key => $value) {
        echo '<option value="'.$value['code'].'" >'.$value['ten'].'</option>';
    }
}elseif($do=="get_diachi_edit"){
    $row_diachi = $d -> simple_fetch("select * from #_diachi where id = '".(int)$_POST['id']."' order by id DESC ");?>
    <form method="POST" action="" id="form-diachi">
        <input type="hidden" value="<?=$_SESSION['token']?>" name="_token" />
        <input type="hidden" value="<?=(int)$_POST['id']?>"  name="id" />
        <div class="row">
            <div class="input-style mb-20 col-sm-6">
                <label>Loại địa chỉ</label>
                <select class="form-control" name="mo_ta">
                    <option <?=$row_diachi['mo_ta']=='Nhà riêng'?'selected':''?> value="Nhà riêng">Nhà riêng</option>
                    <option <?=$row_diachi['mo_ta']=='Văn phòng'?'selected':''?> value="Văn phòng">Văn phòng</option>
                </select>
            </div>
            <div class="input-style mb-20 col-sm-6">
                <label>Họ tên người nhận</label>
                <input type="text" required  placeholder="Nhập họ tên" value="<?=$row_diachi['ho_ten']?>" name="ho_ten" class="form-control" />
            </div>
        </div>
        <div class="row">
            <div class="input-style mb-20  col-sm-6">
                <label>Điện thoại</label>
                <input type="text" required placeholder="Nhập số điện thoại" value="<?=$row_diachi['dien_thoai']?>" name="dien_thoai" class="form-control" />
            </div>
            <div class="input-style mb-20  col-sm-6">
                <label>Email</label>
                <input type="text" placeholder="Nhập email" value="<?=$row_diachi['email']?>" name="email" class="form-control" />
            </div>
        </div>
        <div class="row">
            <div class="input-style mb-20 col-sm-6">
                <label>Tỉnh / Thành phố</label>
                <select class="form-control" required name="code_tinh" id="code_tinh_edit" onchange="get_huyen('code_tinh_edit', 'code_huyen_edit')">
                    <option value="">Chọn Tỉnh / Thành phố</option>
                    <?php foreach ($d->getTinh('code,ten') as $key => $value) {?>
                    <option <?=$row_diachi['code_tinh']==$value['code']?'selected':''?> value="<?=$value['code']?>"><?=$value['ten']?></option>
                    <?php } ?>
                </select>
            </div>
            <div class="input-style mb-20 col-sm-6">
                <label>Quân / Huyện</label>
                <select class="form-control" required id="code_huyen_edit" name="code_huyen" onchange="get_xa('code_huyen_edit', 'code_xa_edit')">
                    <?php foreach ($d->getHuyen($row_diachi['code_tinh'],'code,ten') as $key => $value) {?>
                    <option <?=$row_diachi['code_huyen']==$value['code']?'selected':''?> value="<?=$value['code']?>"><?=$value['ten']?></option>
                    <?php } ?>
                </select>
            </div>
        </div>
        <div class="row">
            <div class="input-style mb-20 col-sm-6">
                <label>Phường / xã</label>
                <select class="form-control" required id="code_xa_edit" name="code_xa">
                    <?php foreach ($d->getXa($row_diachi['code_huyen'],'code,ten') as $key => $value) {?>
                    <option <?=$row_diachi['code_xa']==$value['code']?'selected':''?> value="<?=$value['code']?>"><?=$value['ten']?></option>
                    <?php } ?>
                </select>
            </div>
            <div class="input-style mb-20 col-sm-6">
                <label>Địa chỉ</label>
                <input type="text" required placeholder="Nhập tên đường, phường/xã" value="<?=$row_diachi['dia_chi']?>" name="dia_chi" class="form-control" />
            </div>
        </div>
        <div class="text-center">
            <button class="btn" name="update_diachi">Cập nhật địa chỉ</button>
        </div>
    </form>  
        <script>
        $('select').niceSelect('update');
        </script>
<?php }elseif($do=='delete_cart'){
    echo $_POST['id'];
    $id_cart= addslashes($_POST['id']);
    unset($_SESSION['cart'][$id_cart]);
}elseif($do=='check_sale'){
   
    $ma_sale = addslashes($_POST['ma_sale']);
    $tongdong = addslashes($_POST['tong_dong']);
    $phiship = addslashes($_POST['phi_ship']);
     
    $row_sale = $d -> simple_fetch("select * from #_khuyenmai where ma = '".$ma_sale."'");   
    $row_check_ls = $d -> simple_fetch("select * from #_khuyenmai_ls where ma_km = '".$ma_sale."' and id_thanhvien = '".$_SESSION['id_login']."'");
    $error=0;
   
    if(count($row_sale)=='0'){
        $error = $error+1; //mã khogno tồn tại
    }
    if(count($row_check_ls)>0){
        $error = $error+1; //mã đã được sử dụng
    }
    if($row_sale['dieu_kien']>0 and $tongdong < $row_sale['dieu_kien']){
        $error = $error+1; //tổng giá trị đơn hàng ko thoải đk khuyến mãi
    }
    if($row_sale['gioi_han']>0){
        $dem_sl_sale = $d -> num_rows("select * from #_khuyenmai_ls where ma_km = '".$ma_sale."' ");
        if($dem_sl_sale >= $row_sale['gioi_han'] ){
            $error = $error+1; //đã hết lượt khuyến mãi
        }
    }
    if($row_sale['id_thanhvien']!=''){
        $row_sale_tv = $d -> simple_fetch("select * from #_khuyenmai where ma = '".$ma_sale."' and id_thanhvien like '%".$_SESSION['id_login']."%'");
        if(count($row_sale_tv)==0){
            $error = $error+1; //mã chỉ áp dụng cho thành viên chỉ định
        }
    }
    if($error==0){
        if($row_sale['don_vi']==0){
            $donvi = '<sup>đ</sup>';
            if($row_sale['loai']==0){
                $price_sale = $row_sale['gia_tri'];
            }else{
                $price_sale = $row_sale['gia_tri'];
            }
        }else{
           $donvi ='%'; 
           if($row_sale['loai']==0){
               $price_sale = $tongdong*($row_sale['gia_tri']/100);
           }  else {
                $price_sale = $phiship*($row_sale['gia_tri']/100);
           }
        }
        $data['ma_km'] = $ma_sale;
        $data['id_thanhvien'] = $_SESSION['id_login'];
        $data['ngay_dung']  = date('Y-m-d',time());
         $d->reset();
        $d->setTable('#_khuyenmai_ls');
        if($d->insert($data)){
            $_SESSION['nhahang']['ma_sale']         = $ma_sale;
            $_SESSION['nhahang']['giatri_sale']     = $row_sale['gia_tri'].$donvi;
            $_SESSION['nhahang']['phi_sale']        = $price_sale;
        }
        echo '0';
    }else{
        echo 1;
    }
    
}elseif($do=='wishlist'){
    $id_sp = (int)$_POST['id_sp'];
    $row_sale_tv = $d -> simple_fetch("select * from #_luu_sp where id_sp = ".$id_sp." and id_thanhvien = ".$_SESSION['id_login']." ");
    if(count($row_sale_tv)==0){
        $data['id_sp'] = $id_sp;
        $data['id_thanhvien'] = $_SESSION['id_login'];
        $d->reset();
        $d->setTable('#_luu_sp');
        $d->insert($data);
        echo 1;
    }else{
        echo 0;
    }
}elseif($do=='view_donhang'){
    $ma_dh = addslashes($_POST['ma_dh']);
    $donhang =  $d -> simple_fetch("select * from #_dathang where ma_dh = '".$ma_dh."' and id_thanhvien = ".$_SESSION['id_login']." "); 
    $donhang_ct = $d -> o_fet("select * from #_dathang_chitiet where id_dh = ".$donhang['id']." ");?>
        <div class="calculate-shiping p-40 border-radius-15 border">
            <h3 class="mb-10 text-center">CHI TIẾT ĐƠN HÀNG</h3>
            <p class="mb-30 text-center"><span class="font-lg text-muted">Mã đơn hàng: </span><strong class="text-brand"><?=$ma_dh?></strong></p>
            <div class=" mb-10">
                <b>Khách hàng: </b> <?=$donhang['ho_ten']?>
            </div>
            <div class=" mb-10">
                <b>Điện thoại: </b> <?=$donhang['dien_thoai']?>
            </div>
            <div class=" mb-10">
                <b>Email: </b> <?=$donhang['email']?>
            </div>
            <div class=" mb-10">
                <b>Địa chỉ: </b> <?=$donhang['dia_chi']?>
            </div>
            <table class="table">
                <thead>
                    <tr>
                        <td>Tên SP</td>
                        <td style="text-align: right;">Đơn giá</td>
                        <td style="text-align: right;">SL</td>
                        <td style="text-align: right;">Thành tiền</td>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($donhang_ct as $key => $value) {?>
                    <tr>
                        <td>
                            <?=$value['ten_sp']?><br>
                            <?=$value['thuoc_tinh']?>
                        </td>
                        <td style="text-align: right;"><?=  number_format($value['gia_ban'])?></td>
                        <td style="text-align: right;"><?=  number_format($value['so_luong'])?></td>
                        <td style="text-align: right;"><?=  number_format($value['gia_ban']*$value['so_luong'])?></td>
                    </tr>          
                    <?php } ?>
                </tbody>
            </table>
            <div class="mb-10"><b>Ghi chú đơn hàng: </b> <?=$donhang['loi_nhan']?></div>
            <div class="mb-10"><b>Thanh toán: </b> <?=$donhang['thanh_toan']?></div>
        </div>
<?php }elseif($do=='view_donhang_ctv'){
    $ma_dh = addslashes($_POST['ma_dh']);
    $donhang =  $d -> simple_fetch("select * from #_dathang where ma_dh = '".$ma_dh."' "); 
    $donhang_ct = $d -> o_fet("select * from #_dathang_chitiet where id_dh = ".$donhang['id']." and id_ctv = ".$_SESSION['id_login']." ");?>
        <div class="calculate-shiping p-40 border-radius-15 border">
            <h3 class="mb-10 text-center">CHI TIẾT ĐƠN HÀNG</h3>
            <p class="mb-30 text-center"><span class="font-lg text-muted">Mã đơn hàng: </span><strong class="text-brand"><?=$ma_dh?></strong></p>
            <div class=" mb-10">
                <b>Khách hàng: </b> <?=$donhang['ho_ten']?>
            </div>
            <div class=" mb-10">
                <b>Điện thoại: </b> <?=$donhang['dien_thoai']?>
            </div>
            <div class=" mb-10">
                <b>Email: </b> <?=$donhang['email']?>
            </div>
            <div class=" mb-10">
                <b>Địa chỉ: </b> <?=$donhang['dia_chi']?>
            </div>
            <table class="table">
                <thead>
                    <tr>
                        <td>Tên SP</td>
                        <td style="text-align: right;">Đơn giá</td>
                        <td style="text-align: right;">SL</td>
                        <td style="text-align: right;">Thành tiền</td>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $tong_sl = $tong_gia = 0;
                    foreach ($donhang_ct as $key => $value) {
                    $tong_sl = $tong_sl+$value['so_luong'];
                    $tong_gia = $tong_gia+($value['gia_ban']*$value['so_luong']);
                    ?>
                    <tr>
                        <td>
                            <?=$value['ten_sp']?><br>
                            <?=$value['thuoc_tinh']?>
                        </td>
                        <td style="text-align: right;"><?=  number_format($value['gia_ban'])?></td>
                        <td style="text-align: right;"><?=  number_format($value['so_luong'])?></td>
                        <td style="text-align: right;"><?=  number_format($value['gia_ban']*$value['so_luong'])?></td>
                    </tr>          
                    <?php } ?>
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="2">Tổng:</th>
                        <th style="text-align: right;"><?=  number_format($tong_sl)?></th>
                        <th style="text-align: right;"><?=  number_format($tong_gia)?></th>
                    </tr>
                </tfoot>
            </table>
            <div class="mb-10"><b>Ghi chú đơn hàng: </b> <?=$donhang['loi_nhan']?></div>
            <div class="mb-10"><b>Thanh toán: </b> <?=$donhang['thanh_toan']?></div>
        </div>
        <button type="button" data-fancybox-close="" class="fancybox-button fancybox-close-small" title="Close"><svg xmlns="http://www.w3.org/2000/svg" version="1" viewBox="0 0 24 24"><path d="M13 12l5-5-1-1-5 5-5-5-1 1 5 5-5 5 1 1 5-5 5 5 1-1z"></path></svg></button>
<?php }elseif($do=='update_giasp'){
    $id_sp = (int)$_POST['id'];
    $gia = (int)$_POST['gia'];
    if($gia>0){
        if($d->o_que("update #_sanpham_ctv set gia= '$gia' where id = $id_sp ")){
            echo number_format($gia).'<sup>đ</sup>';
        }else{
            echo 0;
        }
    }
}elseif($do=='get_linkshare'){
    $id_sp = (int)$_POST['id'];
    $sp_share =  $d -> simple_fetch("select * from #_sanpham_ctv where id = ".$id_sp." ");
    $sp = $d -> simple_fetch("select * from #_sanpham where id_code = ".$sp_share['id_sp']." ");
    $url_share = URLPATH.$sp['alias'].'.html?token_share='.$sp_share['token'];
    ?>
    <div class="btn-group chiase-link">
        <input class="btn form-control input-linl" id="link_share2" value="<?=$url_share?>" />
        <button class="btn btn-coppylink" id="btn-coppylink" onclick="coppylink2()" onmouseout="outFunc()">
            <span class="tooltiptext" id="myTooltip">Copy to clipboard</span>
            <?=$d->getTxt(79)?>
        </button>
    </div>
    <div class="link_chiase">
        <!-- Facebook Share Button -->
        <a class="button_share share facebook" href="http://www.facebook.com/sharer/sharer.php?u=<?=$url_share?>"><i class="fab fa-facebook-f"></i> Facebook</a>
        <!-- Twitter Share Button -->
        <a class="button_share share twitter" href="https://twitter.com/intent/tweet?text=<?=$sp['ten']?>&url=<?=$url_share?>"><i class="fab fa-twitter"></i> Tweet</a>
        <!-- Stumbleupon Share Button -->
        <a class="button_share share stumbleupon" href="http://www.stumbleupon.com/submit?url=<?=$url_share?>&title=<?=$sp['ten']?>"><i class="fab fa-stumbleupon"></i> Stumble</a>
        <!-- Pinterest Share Button -->
        <a class="button_share share pinterest" href="http://pinterest.com/pin/create/button/?url=<?=$url_share?>&description=<?=$sp['ten']?>&media=<?=URLPATH?>img_data/images/<?=$sp['hinh_anh']?>"><i class="fab fa-pinterest"></i> Pin it</a>
        <!-- LinkedIn Share Button -->
        <a class="button_share share linkedin" href="http://www.linkedin.com/shareArticle?mini=true&url=<?=$url_share?>&title=<?=$sp['ten']?>&source=<?=$url_share?>"><i class="fab fa-linkedin-in"></i> LinkedIn</a>
        <!-- Buffer Share Button -->
        <a class="button_share share buffer" href="https://buffer.com/add?text=<?=$sp['ten']?>&url=<?=$url_share?>"><i class="fab fa-buffer"></i> Buffer</a>
    </div>
        <button type="button" data-fancybox-close="" class="fancybox-button fancybox-close-small" title="Close"><svg xmlns="http://www.w3.org/2000/svg" version="1" viewBox="0 0 24 24"><path d="M13 12l5-5-1-1-5 5-5-5-1 1 5 5-5 5 1 1 5-5 5 5 1-1z"></path></svg></button>
        <script>
            function coppylink2() {
                var copyText = document.getElementById("link_share2");
                copyText.select();
                copyText.setSelectionRange(0, 99999);
                navigator.clipboard.writeText(copyText.value);

                var tooltip = document.getElementById("myTooltip");
                tooltip.innerHTML = "Đã copy";
              }

              function outFunc() {
                var tooltip = document.getElementById("myTooltip");
                tooltip.innerHTML = "Copy liên kết";
              }
            jQuery(document).ready(function($) {
                $('.share').click(function() {
                    var NWin = window.open($(this).prop('href'), '', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=300,width=600');
                    if (window.focus){NWin.focus();}
                    return false;
                });
            });
        </script>
<?php     
}elseif($do=='get_slider_sp'){
    $id_code = addslashes($_POST['id_code']);
    if($id_code!=''){
    $sp = $d -> o_fet("select * from #_sanpham where id_code in ($id_code) ");
    ?>
        <div class="row">
            <?php foreach ($sp as $k => $item) {
            //$gia_flash_sale =     $item['gia_flash_sale'];
            $row_thongso =     $d->o_fet("select * from #_sanpham_chitiet where id_sp = ".$item['id_code']." ");
            $dem_thongso = count($row_thongso);
            if($dem_thongso>0){
                $dem_cuoi = $dem_thongso-1;
                $gia_ban1 = $row_thongso[0]['gia'];
                $gia_ban2 = $row_thongso[$dem_cuoi]['gia'];
                $gia = numberformat($gia_ban1).'đ - '.numberformat($gia_ban2).'đ';
            }else{
                if($item['khuyen_mai']>0){
                    $gia_ban = $item['khuyen_mai'];
                    $gia_km = $item['gia'];
                    $gia = numberformat($gia_ban).'đ <del>'.  number_format($gia_km).'đ</del>';
                }else{
                    if($item['gia']>0){
                        $gia_ban = $item['gia'];
                        $gia_km=0;
                        $gia = numberformat($gia_ban).'đ';
                    }else{
                        $gia_ban = 0;
                        $gia = 'Liên hệ';
                    }
                }
            }
            ?>
            <div class="col-lg-3 col-md-4 col-sm-6">
                <div class="item_product " style="box-shadow: 1px 2px 1px 1px #e9e6e6;padding: 7px;border-radius: 10px;">
                    <a <?=  cf_tag_a_url($item['slug'], $item['alias'], $item['nofollow'], $item['target'])?> title="<?=$item['ten']?>">
                        <img style="height: 290px !important;width: 100%;" class="item_product_img" src="<?=  Img($item['hinh_anh'])?>" alt="<?=$item['ten']?>" />
                    </a>
                    <<?=_heading_cate_pro?> class="item_product_title"><a style="color: #000;font-weight: 600;" <?=  cf_tag_a_url($item['slug'], $item['alias'], $item['nofollow'], $item['target'])?>><?=$item['ten']?></a></<?=_heading_cate_pro?>>
                    <!--div class="item_product_price">
                        <?=$gia?>
                    </div>
                    <div class="item_product_f d-flex justify-content-start">
                        <?=  $d->getReview($item['id_code'])?>
                    </div-->
                </div>
            </div>  
            <?php } ?>
        </div>
    <?php }}
?>