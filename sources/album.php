<?php
$list_album = $d->o_fet("select * from #_album where hien_thi = 1 ");
$all_images=$d->o_fet("select * from #_album_hinhanh");
?>
<!-- ==================== End Navbar ==================== -->
<div class="circle-bg">
    <div class="circle-color fixed">
        <div class="gradient-circle"></div>
        <div class="gradient-circle two"></div>
    </div>
</div>
<!-- ==================== Start header ==================== -->
<header class="works-header fixed-slider hfixd valign sub-bg">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-7 col-md-9 static">
                <div class="capt mt-50">
                    <div class="parlx text-center">
                        <h1 class="color-font"><?=$row['ten']?></h1>
                    </div>
                    <div class="bactxt custom-font valign">
                        <span>  </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="line bottom right"></div>
</header>
<!-- ==================== End header ==================== -->
<div class="main-content">
    <!-- ==================== Start works ==================== -->
    <section class="portfolio three-column section-padding pb-70">
        <div class="container">
            <div class="row">
                <!-- filter links -->
                <div class="filtering text-center mb-30 col-12">
                    <div class="filter">
                        <span data-filter='*' class="active">Tất cả</span>
                        <?php foreach ($list_album as $key => $value) {?>
                        <span data-filter='.album_<?=$value['id_code']?>'><?=$value['ten']?></span>
                        <?php } ?>
                    </div>
                </div>
                <!-- gallery -->
                <div class="gallery full-width">
                    <?php foreach ($all_images as $key => $value) {?>
                    <!-- gallery item -->
                    <div class="col-lg-4 col-md-6 items album_<?=$value['id_album']?>">
                        <div class="item-img wow fadeInUp" data-wow-delay=".2s">
                            <a href="img_data/images/<?=$value['hinh_anh']?>" data-fancybox="images">
                                <img src="img_data/images/<?=$value['hinh_anh']?>" alt="image">
                            </a>
                        </div>
                    </div> 
                    <?php } ?>
                </div>
            </div>
        </div>
    </section>
</div>
    <!-- ==================== End works ==================== -->
    