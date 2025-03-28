<?php

$id_loai = $row['id_code'] . $d->getIdsub($row['id_code']);
$total_records = $d->num_rows("select * from #_sanpham where id_loai in ($id_loai) and hien_thi =1 " . _where_lang . " order by so_thu_tu ASC, id DESC");
$limit = 20; //get_json('product', 'paging');
$total_page = ceil($total_records / $limit);
$sanpham = $d->o_fet("select * from #_sanpham where id_loai in ($id_loai) and hien_thi =1 " . _where_lang . " order by so_thu_tu ASC, id DESC limit 0,$limit ");


?>
<div class="head_page mb-5" style="background-image: url('<?= Img($row['banner']) ?>');">
    <div class=" container text-center head_page_content">
        <h1 class="title_page"><?= $row['ten'] ?></h1>
        <div class="d-flex justify-content-center">
            <nav aria-label="breadcrumb">
                <?= $d->breadcrumblist($row['id_code']) ?>
            </nav>
        </div>
    </div>
</div>

<div class="container mb-5">
    <div class="row row-cols-2 row-cols-lg-5 row-cols-md-4 row-cols-sm-3 g-2 g-lg-3" id="result">
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
    <?php if ($total_page > 1) { ?>
        <div class="d-flex justify-content-center mt-4">
            <nav aria-label="Page navigation example">
                <ul class="pagination" id="pagination-ajax">
                </ul>
            </nav>
        </div>
    <?php } ?>

</div>
<?php if ($total_page > 0) {
    $js_phantrang = '
    <script type="text/javascript">
        jQuery(document).ready(function($) {
            var chuyenmuc = "' . $row['id_code'] . '";
            $("#pagination-ajax").twbsPagination({
                totalPages: "' . $total_page . '",
                visiblePages: 5,
                prev: "<span aria-hidden=\'true\'>&laquo;</span>",
                next: "<span aria-hidden=\'true\'>&raquo;</span>",
                onPageClick: function(event, page) {
                    $.ajax({
                        url: "sources/ajax/ajax-pagination.php",
                        type: "POST",
                        data: {
                            page: page,
                            totalPages: "' . $total_page . '",
                            chuyenmuc: chuyenmuc,
                            limit: "' . $limit . '",
                            do: "pagination_sanpham"
                        },
                        success: function(data) {
                            //console.log(data);
                            $("#result").html(data);
                        }
                    })
                }
            });
        });
    </script>';
} ?>