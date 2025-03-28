<?php
if(!isset($_SESSION)){
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

$id_sp = (int)$_GET['id'];

$row = $d->simple_fetch("select * from #_sanpham where id_code='$id_sp' "._where_lang." ");

$hinh_anh_sp = $d -> o_fet("select * from #_sanpham_hinhanh where id_sp = ".$row['id_code']." ");
$category = $d->getCate($row['id_loai']);
$id_loai = $category['id_code'].$d->getIdsub($category['id_code']);
$ma_sp          =   $row['ma_sp'];
$soluong_con    =   $row['so_luong'];

if(isset($_GET["token_share"]) and $_GET["token_share"]!=''){
    $token_share = addslashes($_GET["token_share"]);
    $row_sp_ctv     =   $d -> simple_fetch("select * from #_sanpham_ctv where token = '".$token_share."' ");
    $gia            =   $row_sp_ctv['gia'];
    $khuyen_mai     =   0;
    $gia1           =   $row_sp_ctv['gia'];
    $khuyen_mai1    =   0;
    $min = 1;
}else{
    $gia = $row['gia0'];
    $khuyen_mai = $row['khuyen_mai0'];
    $gia1 = $row['gia1'];
    $khuyen_mai1 = $row['khuyen_mai1'];
    $min = 10;
}
$tt_chinh_chon = (int)$_GET['tt_chinh'];

if($tt_chinh_chon > 0){
    $thuoctinh_chinh = $d->o_fet("SELECT * FROM `db_sanpham_chitiet` WHERE id_sp = ".(int)$row['id_code']." and id_loai=0");
    $thuoctinh_chinh_chon = $d->simple_fetch("SELECT * FROM `db_sanpham_chitiet` WHERE id_sp = ".(int)$row['id_code']." and id =".$tt_chinh_chon."");
    if(count($thuoctinh_chinh)>0){
        //$tt_chinh_chon = $thuoctinh_chinh[0]['id'];
        $thuoctinh = $d->simple_fetch("select * from #_sanpham_thuoctinh where id = '".$thuoctinh_chinh[0]['id_thuoctinh']."' ");
        if($thuoctinh['hinh_anh']==1){
            $hinh_anh_sp = $d -> o_fet("select * from #_sanpham_hinhanh where id_sp = ".$row['id_code']." and id_chitiet =  ".$tt_chinh_chon." ");
        }
        if($thuoctinh['ma']==1){
            $ma_sp = $thuoctinh_chinh_chon['ma'];
        }
        if($thuoctinh['gia']==1){
            $gia            =   $thuoctinh_chinh_chon['gia'];
            $khuyen_mai     =   0;
            $gia1           =   $thuoctinh_chinh_chon['gia'];
            $khuyen_mai1    =   0;
        }
        if($thuoctinh['so_luong']==1){
            $soluong_con =$thuoctinh_chinh_chon['so_luong'];
        }
        $thuoctinh_sub = $d->o_fet("SELECT * FROM `db_sanpham_chitiet` WHERE id_sp = ".(int)$row['id_code']." and id_loai=".$tt_chinh_chon."");
        if(count($thuoctinh_sub)>0){
            $thuoctinh2 = $d->simple_fetch("select * from #_sanpham_thuoctinh where id = '".$thuoctinh_sub[0]['id_thuoctinh']."' ");
            $tt_sub_chon = $thuoctinh_sub[0]['id'];

            if($thuoctinh2['hinh_anh']==1){
                $hinh_anh_sp = $d -> o_fet("select * from #_sanpham_hinhanh where id_sp = ".$row['id_code']." and id_chitiet =  ".$tt_sub_chon." ");
            }
            if($thuoctinh2['ma']==1){
                $ma_sp = $thuoctinh_sub[0]['ma'];
            }
            if($thuoctinh2['gia']==1){
                $gia            =   $thuoctinh_sub[0]['gia'];
                $khuyen_mai     =   0;
                $gia1           =   $thuoctinh_sub[0]['gia'];
                $khuyen_mai1    =   0;
            }
            if($thuoctinh2['so_luong']==1){
                $soluong_con = $thuoctinh_sub[0]['so_luong'];
            }
        }
    }
}else{
    $thuoctinh_chinh = $d->o_fet("SELECT * FROM `db_sanpham_chitiet` WHERE id_sp = ".(int)$row['id_code']." and id_loai=0");
    if(count($thuoctinh_chinh)>0){
        $tt_chinh_chon = $thuoctinh_chinh[0]['id'];
        $thuoctinh = $d->simple_fetch("select * from #_sanpham_thuoctinh where id = '".$thuoctinh_chinh[0]['id_thuoctinh']."' ");
        if($thuoctinh['hinh_anh']==1){
            $hinh_anh_sp = $d -> o_fet("select * from #_sanpham_hinhanh where id_sp = ".$row['id_code']." and id_chitiet =  ".$tt_chinh_chon." ");
        }
        if($thuoctinh['ma']==1){
            $ma_sp = $thuoctinh_chinh[0]['ma'];
        }
        if($thuoctinh['gia']==1){
            $gia            =   $thuoctinh_chinh[0]['gia'];
            $khuyen_mai     =   0;
            $gia1           =   $thuoctinh_chinh[0]['gia'];
            $khuyen_mai1    =   0;
        }
        if($thuoctinh['so_luong']==1){
            $soluong_con = $thuoctinh_chinh[0]['so_luong'];
        }
        $thuoctinh_sub = $d->o_fet("SELECT * FROM `db_sanpham_chitiet` WHERE id_sp = ".(int)$row['id_code']." and id_loai=".$tt_chinh_chon."");
        if(count($thuoctinh_sub)>0){
            $thuoctinh2 = $d->simple_fetch("select * from #_sanpham_thuoctinh where id = '".$thuoctinh_sub[0]['id_thuoctinh']."' ");
            $tt_sub_chon = $thuoctinh_sub[0]['id'];

            if($thuoctinh2['hinh_anh']==1){
                $hinh_anh_sp = $d -> o_fet("select * from #_sanpham_hinhanh where id_sp = ".$row['id_code']." and id_chitiet =  ".$tt_sub_chon." ");
            }
            if($thuoctinh2['ma']==1){
                $ma_sp = $thuoctinh_sub[0]['ma'];
            }
            if($thuoctinh2['gia']==1){
                $gia            =   $thuoctinh_sub[0]['gia'];
                $khuyen_mai     =   0;
                $gia1           =   $thuoctinh_sub[0]['gia'];
                $khuyen_mai1    =   0;
            }
            if($thuoctinh2['so_luong']==1){
                $soluong_con = $thuoctinh_sub[0]['so_luong'];
            }
        }
    }
}

if($khuyen_mai1 > 0){ 
    $gia_1000 = '<span>'.number_format($khuyen_mai1).'<sup>đ</sup></span> <del>'.number_format($gia1).'<sup>đ</sup></del>';
}else{ 
    if($gia1 > 0){ 
        $gia_1000 =' <span>'.number_format($gia1).'<sup>đ</sup></span>';
    }else{
         $gia_1000 = '<span>Liên hệ</span>';
    }
}
if($khuyen_mai > 0){ 
    $gia_10 = '<span>'.number_format($khuyen_mai).'<sup>đ</sup></span> <del>'.number_format($gia).'<sup>đ</sup></del>';
}else{ 
    if($gia > 0){ 
        $gia_10 =' <span>'.number_format($gia).'<sup>đ</sup></span>';
    }else{
         $gia_10 = '<span>Liên hệ</span>';
    }
}
?>
<link rel="stylesheet" type="text/css" href="<?=URLPATH?>templates/module/slick/slick.css"/>
<link rel="stylesheet" type="text/css" href="<?=URLPATH?>templates/module/slick/slick-theme.css"/>
<script type="text/javascript" src="<?=URLPATH?>templates/module/slick/jquery-migrate-1.2.1.min.js"></script>
<script type="text/javascript" src="<?=URLPATH?>templates/module/slick/slick.min.js"></script>
<div class="product-details">
    <div class="container">
        <div class="row">
            <div class="col-xl-6" id="slider-sp">
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
                    <?php if(!isset($_GET['tt_chinh'])){ ?>setTimeout(function(){<?php } ?>
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
                    <?php if(!isset($_GET['tt_chinh'])){ ?>}, 200);<?php } ?>
                    
                    
                </script>
            </div>
             <?php 
                $count_bl = $d->num_rows("select * from #_binhluan where id_sanpham =".(int)$row['id_code']." and trang_thai = 1 and parent=0 and danh_gia > 0 order by id DESC ");
                $tongsao = $d->simple_fetch("select sum(danh_gia) as tong from #_binhluan where id_sanpham =".(int)$row['id_code']." and trang_thai = 1 and parent=0 and danh_gia > 0 order by id DESC ");
                if($count_bl>0){
                    $sao_trung_binh = $tongsao['tong']/$count_bl;
                }else{
                    $sao_trung_binh = 0;
                }

                if($sao_trung_binh>0){
                    $sao ='';
                    for($i=0;$i<$sao_trung_binh;$i++){
                        $sao.= '<li><a href="#"><i class="fas fa-star"></i></a></li>';
                    }
                    for($i=0;$i< 5-$sao_trung_binh;$i++){
                        $sao.=  '<li><a href="#"><i class="fal fa-star"></i></a></li>';
                    }
                }else{
                    $sao='<li><a href="#"><i class="fal fa-star"></i></a></li>
                        <li><a href="#"><i class="fal fa-star"></i></a></li>
                        <li><a href="#"><i class="fal fa-star"></i></a></li>
                        <li><a href="#"><i class="fal fa-star"></i></a></li>
                        <li><a href="#"><i class="fal fa-star"></i></a></li>';
                }
                ?>
            <div class="col-xl-6">
                <div class="product__details-content">
                    <h6><?=$row['ten']?></h6>
                    <div class="pd-rating mb-10">
                        <ul class="rating">
                            <?=$sao?>
                        </ul>
                        <span>(<?=$count_bl?> review)</span>
                    </div>
                    <div class="price mb-10" id="gia_sp">
                        <?php if($khuyen_mai > 0){ ?>
                        <span><?=  number_format($khuyen_mai)?><sup>đ</sup></span> <del><?=number_format($gia)?><sup>đ</sup></del>
                        <?php }else{?>
                        <?php if($gia > 0){ ?>
                        <span><?=  number_format($gia)?><sup>đ</sup></span>
                        <?php }else{?>
                        <span>Liên hệ</span>
                        <?php } ?>
                        <?php } ?>

                    </div>
                    <div class="features-des mb-20 mt-10">
                        <?=$row['mo_ta']?>
                    </div>

                    <div class="product-stock mb-20">
                        <h5>Số lượng còn: <span id="sl_con_text"> <?=  numberformat($soluong_con)?> sản phẩm</span></h5>
                    </div>

                    <form method="POST" action="" id="form-cart">
                        <input type="hidden" value="<?=$_SESSION['token']?>" name="_token" />
                        <input type="hidden" value="<?=$row['id_code']?>" name="id_sp" />
                        <?php if(isset($_GET["token_share"]) and $_GET["token_share"]!=''){?>
                        <input type="hidden" value="<?=$_GET["token_share"]?>" name="token_share" />
                        <input type="hidden" value="<?=$_COOKIE["id_share"]?>" name="id_share" />
                        <?php } ?>
                        <input type="hidden" value="<?=$soluong_con?>" id="sl_con" />
                        <?php if(count($thuoctinh_chinh)>0){ ?>
                        <div class="d-flex thuoctinh-sp">
                            <div class="title"><?=$thuoctinh['ten']?>: </div>
                            <input type="hidden" value="<?=$thuoctinh['id']?>" name="thuoctinh[]" />
                            <div class="list-tt">
                                <?php foreach ($thuoctinh_chinh as $key2 => $value2) {?>
                                <label class="item-thuoctinh" onclick="get_chitiet_sp(<?=$row['id_code']?>,<?=$value2['id']?>)">
                                    <input <?=$tt_chinh_chon==$value2['id']?'checked':'' ?> type="radio" name="thuoctinh_<?=$thuoctinh['id']?>" value="<?=$value2['id']?>" />
                                    <?php if($value2['ma_ta']!=''){ ?>
                                    <span class="lab_color" style="background-color: <?=$value2['ma_ta']?>"><?=$value2['ten']?></span>
                                    <?php }elseif($value2['hinh_anh']!=''){ ?>
                                    <span class="lab_img" style=" background-image: url('img_data/images/<?=$value2['hinh_anh']?>')"><?=$value2['ten']?></span>
                                    <?php }else{ ?>
                                    <span><?=$value2['ten']?></span>
                                    <?php } ?>
                                </label>                       
                                <?php } ?>
                            </div>
                        </div> 
                        <?php } ?>
                        <?php  if(count($thuoctinh_sub)>0){ ?>
                        <div id="thuoctinh_sub">
                            <div class="d-flex thuoctinh-sp">
                                <div class="title"><?=$thuoctinh2['ten']?>: </div>
                                <input type="hidden" value="<?=$thuoctinh2['id']?>" name="thuoctinh[]" />
                                <div class="list-tt">
                                    <?php foreach ($thuoctinh_sub as $key2 => $value2) {?>
                                    <label class="item-thuoctinh" onclick="chon_thuoctinh(<?=$thuoctinh2['hinh_anh']?>,<?=$thuoctinh2['gia']?>,<?=$thuoctinh2['ma']?>,<?=$value2['id']?>,<?=$value2['so_luong']?> )">
                                        <input <?=$tt_sub_chon == $value2['id']?'checked':'' ?> type="radio" name="thuoctinh_<?=$thuoctinh2['id']?>" value="<?=$value2['id']?>" />
                                        <?php if($value2['ma_ta']!=''){ ?>
                                        <span class="lab_color" style="background-color: <?=$value2['ma_ta']?>"><?=$value2['ten']?></span>
                                        <?php }elseif($value2['hinh_anh']!=''){ ?>
                                        <span class="lab_img" style=" background-image: url('img_data/images/<?=$value2['hinh_anh']?>')"><?=$value2['ten']?></span>
                                        <?php }else{ ?>
                                        <span><?=$value2['ten']?></span>
                                        <?php } ?>
                                    </label>                       
                                    <?php } ?>
                                </div>
                            </div>
                        </div>
                        <?php } ?>
                        <div class="cart-option mb-15" <?php if($soluong_con==0){ ?>style=" display: none"<?php }else{ ?>style=" display: flex"<?php }?> id="html_dathang">
                            <div class="product-quantity mr-20" style="display: flex;">
                                <span style="display: block;margin-right: 10px;line-height: 40px;font-weight: 500;">Số lượng:</span>
                                <div class="cart-plus-minus p-relative">
                                    <input type="text" value="<?=$min?>" name="so_luong" id="soluong" data_0="10" >
                                    <div class="dec qtybutton">-</div>
                                    <div class="inc qtybutton">+</div>
                                </div>
                            </div>
                            <br>
                            <button class="cart-btn" type="button" onclick="add_to_cart(0)"><?=$d->getTxt(78)?></button>
                            <button class="cart-btn cart-btn2" type="button" onclick="add_to_cart(1)"><?=$d->getTxt(77)?></button>
                        </div>
                        <button id="html_hethang" <?php if($soluong_con==0){ ?>style=" display: block"<?php }else{ ?>style=" display: none"<?php }?> class="cart-btn" type="button"><?=$d->getTxt(82)?></button>
                    </form>
                    <div class="details-meta">
                        <div class="d-meta-left">
                            <div class="dm-item mr-20">
                                <a href="javascript:void(0)" onclick="add_wishlist(<?=$row['id_code']?>)"><i class="fal fa-heart"></i><?=$d->getTxt(93)?></a>
                            </div>
                        </div>
                        <div class="d-meta-left">
                            <div class="dm-item">
                                <a href="javascript:void(0)" data-fancybox data-src="#hidde-share"><i class="fal fa-share-alt"></i>Chia sẻ</a>
                            </div>
                        </div>
                    </div>
                    <div class="product-tag-area mt-15">
                        <div class="product_info">
                            <?php if($ma_sp!=''){ ?>
                            <span class="sku_wrapper">
                                <span class="title" ><?=$d->getTxt(20)?>:</span>
                                <span class="sku" id="ma_sp"><?=$ma_sp?></span>
                            </span>
                            <?php } ?>
                            <span class="posted_in">
                                <span class="title"><?=$d->getTxt(27)?>:</span>
                                <a href="<?=URLPATH.$category['alias']?>.html"><?=$category['ten']?></a>
                            </span>
                            <!--span class="tagged_as">
                            <span class="title">Tags:</span>
                            <a href="#">Smartphone</a>, 
                            <a href="#">Tablets</a>
                            </span-->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $url_share = URLPATH.$row['alias'].'.html'; ?>
<div style="display: none;" id="hidde-share">
    <div class="btn-group chiase-link">
        <input class="btn form-control input-linl" id="link_share" value="<?=URLPATH.$row['alias']?>.html" />
        <button class="btn btn-coppylink" id="btn-coppylink" onclick="coppylink()" onmouseout="outFunc()">
            <span class="tooltiptext" id="myTooltip">Copy to clipboard</span>
            <?=$d->getTxt(79)?>
        </button>
    </div>
    <div class="link_chiase">
        <!-- Facebook Share Button -->
        <a class="button_share share facebook" href="http://www.facebook.com/sharer/sharer.php?u=<?=$url_share?>"><i class="fab fa-facebook-f"></i> Facebook</a>
        <!-- Twitter Share Button -->
        <a class="button_share share twitter" href="https://twitter.com/intent/tweet?text=<?=$row['ten']?>&url=<?=$url_share?>"><i class="fab fa-twitter"></i> Tweet</a>
        <!-- Stumbleupon Share Button -->
        <a class="button_share share stumbleupon" href="http://www.stumbleupon.com/submit?url=<?=$url_share?>&title=<?=$row['ten']?>"><i class="fab fa-stumbleupon"></i> Stumble</a>
        <!-- Pinterest Share Button -->
        <a class="button_share share pinterest" href="http://pinterest.com/pin/create/button/?url=<?=$url_share?>&description=<?=$row['ten']?>&media=<?=URLPATH?>img_data/images/<?=$row['hinh_anh']?>"><i class="fab fa-pinterest"></i> Pin it</a>
        <!-- LinkedIn Share Button -->
        <a class="button_share share linkedin" href="http://www.linkedin.com/shareArticle?mini=true&url=<?=$url_share?>&title=<?=$row['ten']?>&source=<?=$url_share?>"><i class="fab fa-linkedin-in"></i> LinkedIn</a>
        <!-- Buffer Share Button -->
        <a class="button_share share buffer" href="https://buffer.com/add?text=<?=$row['ten']?>&url=<?=$url_share?>"><i class="fab fa-buffer"></i> Buffer</a>
    </div>
</div>
<link rel="stylesheet" type="text/css" href="<?=URLPATH?>templates/css/jquery.fancybox.min.css"/>
<script src="<?=URLPATH?>templates/js/jquery.fancybox.min.js"></script>
<script> 
    function add_to_cart(type){
        <?php if(!isset($_SESSION['id_login'])){ ?>
        window.location="<?=URLPATH.$d->getCate(21,'alias')?>.html?url=<?=$com?>";
        <?php }else{?>
        $.ajax({
            method: $('#form-cart').attr('method'),
            url: 'sources/ajax/ajax_cart.php',
            data: $('#form-cart').serialize(),
            // other AJAX settings goes here
            // ..
        }).done(function(response) {
            if(response==='0'){
                if(type===0){
                    swal({
                        title: '',
                        text: 'Đã thêm vào giỏ hàng',
                        icon: 'success',
                        button: false,
                        timer: 2000
                    }).then((value) => {
                        window.location="<?=URLPATH.$row['alias']?>.html";
                    }); 
                }else{
                     window.location="<?=URLPATH.$d->getCate(26,'alias')?>.html";
                }
            }else{
                if(response === '1'){
                    swal({
                        title: '',
                        text: 'Vui lòng chọn sản phẩm muốn mua',
                        icon: 'error',
                        button: false,
                        timer: 2000
                    }).then((value) => {
                        window.location="<?=URLPATH.$row['alias']?>.html";
                    }); 
                }else if(response==='2'){
                    swal({
                        title: '',
                        text: 'Số lượng còn lại không đủ',
                        icon: 'error',
                        button: false,
                        timer: 2000
                    }).then((value) => {
                        window.location="<?=URLPATH.$row['alias']?>.html";
                    }); 
                }
            }
            
        });
        event.preventDefault(); // <- avoid reloading
        <?php } ?>
        
   }
    function get_chitiet_sp(id_sp,id_thuoctinh){
       $.ajax({
            url : "sources/ajax/ajax-sanpham.php",
            type : "get",
            dataType:"text",
            data : {
                 id : id_sp,
                 tt_chinh: id_thuoctinh,
                 token_share: '<?=$_GET["token_share"]?>'
            },
            success : function (result){
                $('#ct-sp').html(result);
                
            }
        });
    }
    function chon_thuoctinh(hinh_anh, gia, ma,id,sl){
        
        if(hinh_anh===1){
            getImg_product(id);
        }
        <?php if(!isset($_COOKIE["token_share"])){ ?>
        if(gia===1){
            getgia_product(id);
        }
        <?php } ?>
        if(ma===1){
            getma_product(id);
        }
        $('#sl_con_text').html(sl+' sản phẩm');
        $('#sl_con').attr('value',sl);
        if(sl>0){
            $('#html_dathang').css('display','flex');
            $('#html_hethang').css('display','none');
        }else{
             $('#html_dathang').css('display','none');
            $('#html_hethang').css('display','block');
        }
        $('#soluong').val('<?=$min?>')
    }
    
    function getma_product(id){
        $.ajax({
            url : "sources/ajax/ajax.php",
            type : "post",
            dataType:"text",
            data : {
                 do : 'get_ma_product',
                 id : id
            },
            success : function (result){
                if(result!==''){
                    $('#ma_sp').html(result);
                }
            }
        });
    }
    <?php if(!isset($_COOKIE["token_share"])){ ?>
    function getgia_product(id){
        $.ajax({
            url : "sources/ajax/ajax.php",
            type : "post",
            dataType:"text",
            data : {
                 do : 'get_gia_product',
                 id : id
            },
            success : function (result){
                if(result!==''){
                    $('#gia_sp').html(result);
                }
            }
        });
    }
    <?php } ?>
    function getImg_product(id){
        $.ajax({
            url : "sources/ajax/ajax.php",
            type : "post",
            dataType:"text",
            data : {
                 do : 'get_img_product',
                 id : id
            },
            success : function (result){
                if(result!==''){
                    $('#slider-sp').html(result);
                }
            }
        });
    }
    function coppylink() {
        var copyText = document.getElementById("link_share");
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
    
    $(".cart-plus-minus").append('<div class="dec qtybutton">-</div><div class="inc qtybutton">+</div>');
    
    $(".qtybutton").on("click", function () {
        var $button = $(this);
        var oldValue = $button.parent().find("input").val();
        if ($button.text() == "+") {
            
            if(oldValue < parseFloat($('#sl_con').val())){
                var newVal = parseFloat(oldValue) + 1   ;
            }else{
                 newVal =oldValue;
            }
           
            
        } else {
            // Don't allow decrementing below zero
            if (oldValue > parseFloat(<?=$min?>)) {
                    var newVal = parseFloat(oldValue) - 1;
            } else {
                    newVal = <?=$min?>;
            }
        }
        if(newVal>999){
            $('#gia_sp').html('<?=$gia_1000?>');
        }else{
            $('#gia_sp').html('<?=$gia_10?>');
        }
        $button.parent().find("input").val(newVal);
    });
    $('#soluong').change(function(){
        var num = $(this).val();
        if(num < <?=$min?>){
            $(this).val('<?=$min?>');
        }
        if(num > $('#sl_con').val()){
            $(this).val($('#sl_con').val());
        }
    })
</script>