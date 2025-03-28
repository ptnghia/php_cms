<?php 
    $news_left  = $d->o_fet("select * from #_tintuc where noi_bat=1 and hien_thi =1 "._where_lang." order by id desc limit 0,9");
    $sanpham_c = $d->o_fet("select * from #_category where module = 3 and id_loai=55 and hien_thi =1 "._where_lang." order by so_thu_tu ASC, id DESC limit 0,5");
?>
<div class="col-lg-3">
    <div class="widget-category mb-5">
        <h5 class="section-title mb-30">Danh mục sản phẩm</h5>
        <ul class="categories">
            <?php foreach ($sanpham_c as $key => $value) {?>
            <li><a href="<?=URLPATH.$value['alias']?>.html"><?=$value['ten']?></a></li>
            <?php } ?>
        </ul>
    </div>

    <h5 class="slidebar_title mb-4">Bài viết nổi bật</h5>
    <div class="row">
        <div class="col-12 sm-grid-content sm-grid-content-big  mb-30">
            <div class="post-thumb d-flex border-radius-5 img-hover-scale mb-15">
                <a <?=  cf_tag_a_url($news_left[0]['slug'], $item['alias'], $item['nofollow'], $news_left[0]['target'])?> title="<?=$news_left[0]['ten']?>">
                    <img src="<?=  Img($news_left[0]['hinh_anh'])?>" alt="<?=$news_left[0]['ten']?>" />
                </a>
            </div>
            <div class="post-content media-body">
                <h4 class="post-title mb-10 text-limit-2-row">
                    <a style="text-decoration: none;" <?=  cf_tag_a_url($news_left[0]['slug'], $news_left[0]['alias'], $news_left[0]['nofollow'], $news_left[0]['target'])?> title="<?=$news_left[0]['ten']?>"><?=$news_left[0]['ten']?></a>
                </h4>
                <div class="entry-meta meta-13 font-xxs color-grey">
                    <span class="post-on mr-10"><?=date('d/m/Y',$news_left[0]['cap_nhat'])?></span>
                </div>
            </div>
        </div>
        <?php foreach ($news_left as $key => $item) {?>
        <div class="col-md-6 col-sm-6 sm-grid-content mb-30">
            <div class="post-thumb d-flex border-radius-5 img-hover-scale mb-15">
                <a <?=  cf_tag_a_url($item['slug'], $item['alias'], $item['nofollow'], $item['target'])?> title="<?=$item['ten']?>">
                    <img src="<?=  Img($item['hinh_anh'])?>" alt="<?=$item['ten']?>" />
                </a>
            </div>
            <div class="post-content media-body">
                <h6 class="post-title mb-10 text-limit-2-row">
                    <a style="text-decoration: none;" <?=  cf_tag_a_url($item['slug'], $item['alias'], $item['nofollow'], $item['target'])?> title="<?=$item['ten']?>">
                        <?=$item['ten']?>
                    </a>
                </h6>
                <div class="entry-meta meta-13 font-xxs color-grey">
                    <span class="post-on mr-10"><?=date('d/m/Y',$item['cap_nhat'])?></span>
                </div>
            </div>
        </div> 
        <?php } ?>
        
    </div>
</div>