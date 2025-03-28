<?php
$hinh_anh_sp    =   $d->o_fet("select * from #_sanpham_hinhanh where id_sp = " . $row['id_code'] . " ");
$ma_sp          =   $row['ma_sp'];
$soluong_con    =   $row['so_luong'];
$id_loai = $category['id_code'] . $d->getIdsub($category['id_code']);
$sanpham = $d->o_fet("select * from #_sanpham where id_loai in ($id_loai) and hien_thi =1 " . _where_lang . " and id <> " . $row['id'] . " order by so_thu_tu ASC, id DESC limit 0,5 ");
if ($row['khuyen_mai'] > 0) {
    $gia_ban    = $row['khuyen_mai'];
    $gia_km     = $row['gia'];
    $giamgia = round((($gia_km - $gia_ban) / $gia_km) * 100);
    $gia = '
        <div class="product-price primary-color float-left">
            <span class="text-brand">' . numberformat($gia_ban) . '<u>đ</u></span>
            <span class="old-price font-md ms-3">' . number_format($gia_km) . '<u>đ</u></span>
            <span class="save-price  font-md color3 ms-3">' . $giamgia . '% Off</span>
        </div>';
} else {
    $giamgia = 0;
    if ($row['gia'] > 0) {
        $gia_ban = $row['gia'];
        $gia_km = 0;
        $gia = '
            <div class="product-price primary-color float-left">
                <span class="text-brand">' . numberformat($gia_ban) . '<u>đ</u></span>
            </div>';
    } else {
        $gia_ban = 0;
        $gia = '
            <div class="product-price primary-color float-left">
                <span class="text-brand">Liên hệ</span>
            </div>';
    }
}
?>
<div class="head_page mb-5" <?php if ($category['banner'] != '') { ?>style="background-image: url('<?= Img($category['banner']) ?>');" <?php } ?>>
    <div class=" container text-center head_page_content">
        <h1 class="title_page"><?= $category['ten'] ?></h1>
        <div class="d-flex justify-content-center">
            <nav aria-label="breadcrumb">
                <?= $d->breadcrumblist($category['id_code']) ?>
            </nav>
        </div>
    </div>
</div>
<div class="chitiet_sanpham container position-relative mb-5">
    <div class="row album_sp" id="ct_sp">
        <div class="col-md-7">
            <div class="swiper slider_product_big mySwiper2 mb-2">
                <div class="swiper-wrapper big_img_sp">
                    <?php foreach ($hinh_anh_sp as $key => $item) { ?>
                        <div class="swiper-slide">
                            <a href="<?= Img($item['hinh_anh']) ?>" data-fancybox="gallery" data-caption="">
                                <img src="<?= Img($item['hinh_anh']) ?>" />
                            </a>
                        </div>
                    <?php } ?>
                </div>
                <div class="swiper-button-next"></div>
                <div class="swiper-button-prev"></div>
            </div>
            <div class="swiper mySwiper slider_product_small">
                <div class="swiper-wrapper nav_img_pro">
                    <?php foreach ($hinh_anh_sp as $key => $item) { ?>
                        <div class="swiper-slide">
                            <img src="<?= Img($item['hinh_anh']) ?>" />
                        </div>
                    <?php } ?>
                </div>
            </div>
        </div>
        <div class="col-md-5" data-sticky-container>
            <div class="detail-info sticky">
                <h1 class="title-detail"><?= $row['ten'] ?></h1>
                <div class="product-detail-rating">
                    <div class="pro-details-brand">
                        <span> Danh mục: <a href="<?= $category['alias'] ?>"><?= $category['ten'] ?></a></span>
                    </div>
                    <div class="product-rate-cover text-end">
                        <span class="font-small ml-5 text-muted"> Mã sản phẩm: <?= $row['ma_sp'] ?></span>
                    </div>
                </div>
                <div class="clearfix product-price-cover">
                    <?= $gia ?>
                </div>
                <div class="bt-1 border-color-1 mt-4 mb-4"></div>
                <div class="chitiet_sanpham">
                    <?= $row['mo_ta'] ?>
                </div>
                <div class="bt-1 border-color-1 mt-4 mb-4"></div>
                <div class="detail-extralink">
                    <div class="product-extra-link2">
                        <a href="https://zalo.me/<?= _zalo ?>" target="_blank" class="btn btn-main">Liên hệ mua hàng</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class=" mt-5">
        <h2 class="title_home_product text-center">Chi tiết sản phẩm</h2>
        <div class="chitiet_sanpham">
            <?= $row['noi_dung'] ?>
        </div>
    </div>
</div>
<div class="container mb-5">
    <div class="tintuc">
        <div class="container">
            <div class="block-title">
                <div class="sub-title"><?= $category['ten'] ?></div>
                <h2>Sản phẩm cùng loại</h2>
            </div>
            <div class="swiper swiper-container"
                data-slides-per-view="1"
                data-space-between="20"
                data-loop="true"
                data-effect=""
                data-speed="800"
                data-breakpoints='{"1366": {"slidesPerView": 5}, "1024": {"slidesPerView": 4}, "768": {"slidesPerView": 3}, "576": {"slidesPerView": 2}}'>
                <div class="swiper-wrapper">
                    <?php foreach ($sanpham as $value) { ?>
                        <div class="swiper-slide">
                            <div class="product-item">
                                <div class="product-img">
                                    <a href="<?= cre_Link($value['alias']) ?>">
                                        <img src="<?= Img($value['hinh_anh']) ?>" alt="<?= $value['ten'] ?>" class="img-show">
                                        <img src="<?= Img($value['hinh_anh2']) ?>" alt="<?= $value['ten'] ?>" class="img-hover">
                                    </a>
                                </div>
                                <div class="product-content">
                                    <h3 class="text">
                                        <a href="<?= cre_Link($value['alias']) ?>" title="<?= $value['ten'] ?>"><?= $value['ten'] ?></a>
                                    </h3>
                                    <div class="product-price">
                                        <span class="price"><?= $value['gia'] == 0 ? "Liên hệ" : number_format($value['gia'], 0, ',', '.') . 'đ'; ?></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                </div>
                <div class="swiper-pagination" data-pagination-type="fraction"></div>
            </div>
        </div>
    </div>
</div>