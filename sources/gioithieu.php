<?php
$gioithieu = $d->getContent(68);
?>
<div class="container">
    <div class="gioithieu row">
        <div class="row">
            <div class="col-sm-6 col-text">
                <div class="img">
                    <img src="<?=URLPATH?>uploads/images/<?=$gioithieu['hinh_anh']?>" alt="<?=$gioithieu['ten']?>" />
                </div>
            </div>
            <div class="col-sm-6 col-text">
                <div class="body-gt">
                     <h2 class="title"><?=$gioithieu['ten']?></h2>
                    <div class="content">
                        <?=$gioithieu['noi_dung']?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="lichhsu container-fluid">
    <div class=" container">
        <h2 class="title-home"><span><?=$d->getContent(69,'ten')?></span></h2>
        <div class="row">
            <div class="col-sm-2">
                <div class=" slider-nav nav-lichsu">
                    <?php foreach ($d->getContents(69) as $key => $value) {?>
                    <div class="item-lichsu-nav">
                        <a href="javascript:void(0)"><?=$value['link']?></a>
                    </div>
                    <?php }?>
                </div>
            </div>
            <div class="col-sm-10">
                <div class="slider-single">
                <?php foreach ($d->getContents(69) as $key => $value) {?>
                <div class="item-lichsu">
                    <div class="row">
                        <div class="col-sm-5 col-text">
                            <img src="<?=URLPATH?>uploads/images/<?=$value['hinh_anh']?>" alt="<?=$value['ten']?>" />
                        </div>
                        <div class="col-sm-7 col-text">
                            <div class="body-lichsu">
                                <h3 class="title"><?=$value['ten']?></h3>
                                <div class="content"><?=$value['noi_dung']?></div>
                            </div>
                        </div>
                    </div>
                </div>   
                <?php } ?>
            </div>
            </div>
        </div>
    </div>
</div>
<div class=" container">
    <div class="giatri">
        <h2 class="title-home"><span><?=$d->getContent(72,'ten')?></span></h2>
        <div class="row">
            <?php foreach ($d->getContents(72) as $key => $value) {?>
            <div class="col-sm-6">
                <figure class="snip1321 item-giatri">
                    <img src="<?=URLPATH?>uploads/images/<?=$value['hinh_anh']?>" alt="<?=$value['ten']?>"/>
                    <figcaption>
                      <h2><?=$value['ten']?></h2>
                      <div class="content">
                          <?=$value['noi_dung']?>
                      </div>
                      </figcaption>
                    <div class="title0"><?=$value['ten']?></div>
                </figure>
            </div>
            <?php }?>
        </div>
    </div>
</div>

<div class="doitac">
    <div class=" container">
        <h2 class="title-home"><span><?=$d->getContent(76,'ten')?></span></h2>
        <div class="row slide-doitac m-10">
            <?php foreach ($d->getContents(76) as $key => $value) {?>
            <div class="col-md-5ths col-sm-4 p10">
                <a href="<?=$value['link']?>" title="<?=$value['ten']?>" class="item-doitac">
                    <img src="<?=URLPATH?>uploads/images/<?=$value['hinh_anh']?>" alt="<?=$value['ten']?>" />
                </a>
            </div>
            <?php }?>
        </div>
    </div>
</div>

<div class="container-fluid">
    <h2 class="title-home"><span><?=$d->getContent(88,'ten')?></span></h2>
    <div class="slide-album m-5">
        <?php foreach ($d->getContents(88) as $key => $value) {?>
        <div class="col-md-5ths col-sm-3 p5">
            <a href="<?=URLPATH?>uploads/images/<?=$value['hinh_anh']?>" href="<?=URLPATH?>uploads/images/<?=$value['hinh_anh']?>"  data-fancybox="images" title="<?=$value['ten']?>" class="item-album">
                <img src="<?=URLPATH?>uploads/images/<?=$value['hinh_anh']?>" alt="<?=$value['ten']?>" />
            </a>
        </div>
        <?php }?>
    </div>
</div>
<div class=" container">
    <div class="nhaphanphoi">
        <h2 class="title-home"><span><?=$d->getContent(82,'ten')?></span></h2>
        <div class="row slide-doitac m-10">
            <?php foreach ($d->getContents(82) as $key => $value) {?>
            <div class="col-md-5ths col-sm-4 p10">
                <a href="<?=$value['link']?>" title="<?=$value['ten']?>" class="item-doitac">
                    <img src="<?=URLPATH?>uploads/images/<?=$value['hinh_anh']?>" alt="<?=$value['ten']?>" />
                </a>
            </div>
            <?php }?>
        </div>
    </div>
</div>
    
<link rel="stylesheet" type="text/css" href="<?=URLPATH?>templates/css/jquery.fancybox.min.css">
<script src="<?=URLPATH?>templates/js/jquery.fancybox.min.js"></script>
<link rel="stylesheet" type="text/css" href="<?=URLPATH?>templates/module/slick/slick.css"/>
<link rel="stylesheet" type="text/css" href="<?=URLPATH?>templates/module/slick/slick-theme.css"/>
<script type="text/javascript" src="<?=URLPATH?>templates/module/slick/jquery-migrate-1.2.1.min.js"></script>
<script type="text/javascript" src="<?=URLPATH?>templates/module/slick/slick.min.js"></script>
<script>
    $('.slider-single').slick({
        slidesToShow: 1,
        slidesToScroll: 1,
        arrows: true,
        fade: false,
        adaptiveHeight: true,
        infinite: true,
        useTransform: true,
        vertical: true,
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
        slidesToShow: 4,
        slidesToScroll: 1,
        autoplay:false,
        autoplaySpeed: 4000,
        vertical: true,
        arrows:false,
        infinite: true,
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
    $('.slide-album').slick({
        dots: false,
        infinite: false,
        speed: 300,
        slidesToShow: 5,
        slidesToScroll: 1,
        responsive: [
          {
            breakpoint: 1024,
            settings: {
              slidesToShow: 3,
              slidesToScroll: 1,
            }
          },
          {
            breakpoint: 600,
            settings: {
              slidesToShow: 3,
              slidesToScroll: 1
            }
          },
          {
            breakpoint: 480,
            settings: {
              slidesToShow: 2,
              slidesToScroll: 1
            }
          }
          // You can unslick at a given breakpoint now by adding:
          // settings: "unslick"
          // instead of a settings object
        ]
      });
    $('.slide-doitac').slick({
        dots: false,
        infinite: false,
        speed: 300,
        slidesToShow: 5,
        slidesToScroll: 1,
        responsive: [
          {
            breakpoint: 1024,
            settings: {
              slidesToShow: 4,
              slidesToScroll: 1,
            }
          },
          {
            breakpoint: 600,
            settings: {
              slidesToShow: 3,
              slidesToScroll: 1
            }
          },
          {
            breakpoint: 480,
            settings: {
              slidesToShow: 2,
              slidesToScroll: 1
            }
          }
          // You can unslick at a given breakpoint now by adding:
          // settings: "unslick"
          // instead of a settings object
        ]
      });
</script>