<?php

?>
<div class="head_page mb-5">
    <div class=" container text-center head_page_content">
        <h1 class="title_page"><?= $category['ten'] ?></h1>
        <div class="d-flex justify-content-center">
            <nav aria-label="breadcrumb">
                <?= $d->breadcrumblist($category['id_code']) ?>
            </nav>
        </div>
    </div>
</div>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-lg-10 col-md-10">
            <div class="news-detail">
                <img src="<?= Img($row['hinh_anh']) ?>" class="blog-detail-img" alt="<?= $row['ten'] ?>">
                <div class="head d-flex justify-content-between mt-3 mb-4">
                    <div>
                        <a class="tag" href="<?= cre_Link($category['alias']) ?>"><i class="fa-solid fa-tag me-2"></i> <?= $category['ten'] ?></a>
                    </div>
                    <div class="chiase">
                        Chia sẻ:
                        <a class="share" href="http://www.facebook.com/sharer/sharer.php?u=<?= $url_page ?>" title="" target="_blank">
                            <i class="fa-brands fa-facebook-f"></i>
                        </a>
                        <a class="share" href="https://twitter.com/intent/tweet?text=<?= $row['ten'] ?>&url=<?= $url_page ?>" title="" target="_blank">
                            <i class="fa-brands fa-twitter"></i>
                        </a>
                        <a class="share" href="http://www.linkedin.com/shareArticle?mini=true&url=<?= $url_page ?>&title=<?= $row['ten'] ?>&source=<?= $url_page ?>" title="" target="_blank">
                            <i class="fa-brands fa-linkedin-in"></i>
                        </a>
                    </div>
                </div>
                <h1 class="title"><?= $row['ten'] ?></h1>

                <div class="blog-details">
                    <?= $row['noi_dung'] ?>
                </div>
            </div>
        </div>
    </div>


    <div class="tintuc">
        <div class="container">
            <div class="block-title">
                <div class="sub-title"><?= $category['ten'] ?></div>
                <h2>Bài viết liên quan</h2>
            </div>
            <div class="swiper swiper-container"
                data-slides-per-view="1"
                data-space-between="20"
                data-loop="true"
                data-effect=""
                data-speed="800"
                data-breakpoints='{"1366": {"slidesPerView": 3}, "1024": {"slidesPerView": 2}, "768": {"slidesPerView": 2}, "576": {"slidesPerView": 1}}'>
                <div class="swiper-wrapper">
                    <?php foreach ($tinlienquan as $value) { ?>
                        <div class="swiper-slide">
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
                <div class="swiper-pagination" data-pagination-type="fraction"></div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $('.share').click(function() {
        var NWin = window.open($(this).prop('href'), '', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=300,width=600');
        if (window.focus) {
            NWin.focus();
        }
        return false;
    });
</script>