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
    <div class="blog-details">
        <?= $row['noi_dung'] ?>
    </div>
</div>