<div class=" container-fluid">
    <section class="swiper mainSwiper swiper-container"
        data-slides-per-view="1"
        data-space-between="20"
        data-loop="true"
        data-effect="fade"
        data-speed="800"
        data-breakpoints=''>
        <div class="swiper-wrapper">
            <?php foreach ($d->getContents(3) as $key => $value) { ?>
                <!-- Slide 1 -->
                <div class="swiper-slide">
                    <div class="swiper-slide-bg" style="background-image:url('<?= Img($value['hinh_anh']) ?>')"></div>
                    <?php if ($value['ten'] != '') { ?>
                        <div class="content">
                            <h2 class="fade-down" data-swiper-parallax="-300"><?= $value['ten'] ?></h2>
                            <?php if ($value['noi_dung'] != '') { ?>
                                <div class="fade-down des" data-swiper-parallax="-200" data-swiper-parallax-opacity="0">
                                    <?= $value['noi_dung'] ?>
                                </div>
                            <?php } ?>
                            <?php if ($value['link'] != '') { ?>
                                <a href="<?= $value['link'] ?>" class="btn btn-main fade-down" data-swiper-parallax="-100">Xem thêm</a>
                            <?php } ?>
                        </div>
                    <?php } ?>
                </div>
            <?php } ?>
        </div>
        <!-- Add Pagination -->
        <div class="swiper-pagination"></div>
    </section>
</div>
<?php $bosutap = $d->getContent(57); ?>
<section class="section section-padding background-img bg-img-2">
    <div class="container">
        <!-- Block Product Categories (Layout 3) -->
        <div class="block block-product-cats slider layout-3">
            <div class="block-widget-wrap">
                <div class="block-title">
                    <div class="sub-title"><?= $bosutap['link'] ?></div>
                    <h2><?= $bosutap['ten'] ?></h2>
                </div>
                <div class="block-content">
                    <div class="product-cats-list ">
                        <div class="swiper content-category swiper-container"
                            data-slides-per-view="1"
                            data-space-between="10"
                            data-loop="true"
                            data-effect=""
                            data-speed="800"
                            data-breakpoints='{"1366": {"slidesPerView": 4}, "1024": {"slidesPerView": 3}, "768": {"slidesPerView": 2}, "576": {"slidesPerView": 1}}'>
                            <div class="swiper-wrapper">
                                <?php foreach ($d->getCates(28) as $value) { ?>
                                    <div class="item item-product-cat swiper-slide">
                                        <div class="item-product-cat-content">
                                            <a href="<?= cre_Link($value['alias']) ?>">
                                                <div class="item-image animation-horizontal">
                                                    <img width="273" height="376" src="<?= Img($value['hinh_anh']) ?>" alt="<?= $value['ten'] ?>">
                                                </div>
                                            </a>
                                        </div>
                                        <div class="product-cat-content-info">
                                            <h2 class="item-title">
                                                <a href="<?= cre_Link($value['alias']) ?>" title="<?= $value['ten'] ?>"><?= $value['ten'] ?></a>
                                            </h2>
                                        </div>
                                    </div>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<?php $danhmcsp = $d->getContent(58); ?>
<section class="section-home">
    <div class="container">
        <div class="block-title">
            <div class="sub-title"><?= $danhmcsp['link'] ?></div>
            <h2><?= $danhmcsp['ten'] ?></h2>
        </div>
        <div class="product-cats-list ">
            <div class="swiper content-category swiper-container"
                data-slides-per-view="2"
                data-space-between="10"
                data-loop="true"
                data-effect=""
                data-speed="800"
                data-breakpoints='{"1366": {"slidesPerView": 6}, "1024": {"slidesPerView": 5}, "768": {"slidesPerView": 3}, "576": {"slidesPerView": 2}}'>
                <div class="swiper-wrapper">
                    <?php foreach ($d->getCates(20) as $value) { ?>
                        <div class="item cate_product swiper-slide">
                            <div class="cate_product_content">
                                <a href="<?= cre_Link($value['alias']) ?>">
                                    <img width="200" height="200" src="<?= Img($value['hinh_anh']) ?>" alt="<?= $value['ten'] ?>">
                                </a>
                                <h2 class="item-title">
                                    <a href="<?= cre_Link($value['alias']) ?>" title="<?= $value['ten'] ?>"><?= $value['ten'] ?></a>
                                </h2>
                            </div>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
</section>
<?php
$sanpham_c = $d->getContent(59);
$sanpham = $d->o_fet("select * from #_sanpham where sp_moi = 1 and hien_thi = 1 " . _where_lang . " order by so_thu_tu ASC, id DESC ");
?>
<section class="section section-padding background-img py-5">
    <div class="container">
        <div class="block-title">
            <div class="sub-title"><?= $sanpham_c['link'] ?></div>
            <h2><?= $sanpham_c['ten'] ?></h2>
        </div>
        <div class="row row-cols-2 row-cols-lg-5 row-cols-md-4 row-cols-sm-3 g-2 g-lg-3">
            <?php foreach ($sanpham as $key => $value) {
            ?>
                <div class="col">
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
    </div>
</section>
<?php $gioithieu = $d->getContent(61); ?>
<section class="section section-padding background-img bg-img-2 gioithieu">
    <div class="container" style="z-index: 1;position: relative;">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <div class="block-title">
                    <div class="sub-title">Về chúng tôi</div>
                    <h2><?= $gioithieu['ten'] ?></h2>
                </div>
                <div class="block-content">
                    <?= $gioithieu['noi_dung'] ?>
                </div>

            </div>
            <div class="col-lg-6">
                <img src="<?= Img($gioithieu['hinh_anh']) ?>" alt="<?= $gioithieu['ten'] ?>" class="img-fluid">
            </div>
        </div>
    </div>
</section>
<?php
$id_loai = '18' . $d->getIdsub(18);
$tintuc = $d->o_fet("select * from #_tintuc where id_loai in ($id_loai) and hien_thi =1 " . _where_lang . " order by so_thu_tu ASC, id DESC limit 0,3 ");
$tintuc_c = $d->getContent(60);
?>
<section class="tintuc">
    <div class="container">
        <div class="block-title">
            <div class="sub-title"><?= $tintuc_c['link'] ?></div>
            <h2><?= $tintuc_c['ten'] ?></h2>
        </div>
        <div class="row g-lg-4 g-3">
            <?php foreach ($tintuc as $key => $value) {
                $cate = $d->getCate($value['id_loai']);
            ?>
                <div class="col-md-4">
                    <div class="tintuc_item">
                        <a href="<?= cre_Link($value['alias']) ?>" class="tintuc_img d-block">
                            <img src="<?= Img($value['hinh_anh']) ?>" alt="<?= $value['ten'] ?>">
                        </a>
                        <div class="tintuc_content">
                            <span class="cate"><?= $cate['ten'] ?> - <?= date('M d, Y') ?></span>
                            <h3 class="text"> <a href="<?= cre_Link($value['alias']) ?>" title="<?= $value['ten'] ?>"><?= $value['ten'] ?></a></h3>
                            <div class="tintuc_des "><?= $value['mo_ta'] ?></div>
                        </div>
                    </div>
                </div>
            <?php } ?>
        </div>
    </div>
</section>