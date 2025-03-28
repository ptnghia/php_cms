<?php
if ($com == 'tags') {
    $tags = addslashes($_REQUEST['alias']);
    $query = $d->simple_fetch("select * from #_tags where alias = '$tags'");
    $tintuc = $d->o_fet("select  * from #_tintuc where hien_thi = 1 and tags_hienthi like '%" . $query['ten'] . "%' order by so_thu_tu asc, id desc");
} else {
    $id_loai = $row['id_code'] . $d->getIdsub($row['id_code']);
    $total_records = $d->num_rows("select * from #_tintuc where id_loai in ($id_loai) and hien_thi =1 " . _where_lang . " order by so_thu_tu ASC, id DESC");
    $limit = 12;
    $total_page = ceil($total_records / $limit);
    $tintuc = $d->o_fet("select * from #_tintuc where id_loai in ($id_loai) and hien_thi =1 " . _where_lang . " order by so_thu_tu ASC, id DESC limit 0,$limit ");
}
$sub = $d->getCates($row['id_code']);
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
    <div class="row g-3" id="result">
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
                            do: "pagination_tintuc"
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