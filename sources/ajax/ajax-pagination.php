<?php
if (!isset($_SESSION)) {
    session_set_cookie_params(['SameSite' => 'None']);
    session_start();
}
ob_start();
error_reporting(0);
define('_lib', '../../admin/lib/');
define('_source_lib', '../lib/');
global $d;
global $lang;
include _lib . "config.php";
include_once _lib . "function.php";
include_once _lib . "class.php";
$d = new func_index($config['database']);
include_once _source_lib . "lang.php";
include_once _source_lib . "info.php";
include_once _source_lib . "function.php";
$do = validate_content($_POST['do']);

if ($do == 'pagination_tintuc') {
    $current_page   =   $_POST['page'];
    $id_loai        =   validate_content($_POST['chuyenmuc']);
    $total_page     =   $_POST['totalPages'];
    $limit          =   $_POST['limit'];
    if ($current_page > $total_page) {
        $current_page = $total_page;
    } else if ($current_page < 1) {
        $current_page = 1;
    }
    $start = ($current_page - 1) * $limit;
    $tintuc    =   $d->o_fet("select * from #_tintuc where id_loai in ($id_loai) and hien_thi =1 " . _where_lang . " order by so_thu_tu ASC, id DESC limit $start,$limit ");
    foreach ($tintuc as $key => $value) {
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
    <?php }
} elseif ($do == 'pagination_sanpham') {
    $id_loai   =   $_POST['chuyenmuc'];
    $current_page   =   $_POST['page'];
    $total_page     =   $_POST['totalPages'];
    $limit          =   $_POST['limit'];
    if ($current_page > $total_page) {
        $current_page = $total_page;
    } else if ($current_page < 1) {
        $current_page = 1;
    }
    $start = ($current_page - 1) * $limit;

    $sanpham = $d->o_fet("select * from #_sanpham where id_loai in ($id_loai) and hien_thi =1 " . _where_lang . " order by so_thu_tu ASC, id DESC limit $start,$limit ");
    ?>

    <?php foreach ($sanpham as $key => $item) {
        if ($item['khuyen_mai'] > 0) {
            $gia_ban = $item['khuyen_mai'];
            $gia_km = $item['gia'];
            $giamgia = (($gia_km - $gia_ban) / $gia_km) * 100;
            $gia = '<span><strong>' . numberformat($gia_ban) . ' <sup>đ</sup> </strong><del>' .  number_format($gia_km) . ' <sup>đ</sup> </del></span>';
        } else {
            if ($item['gia'] > 0) {
                $gia_ban = $item['gia'];
                $gia_km = 0;
                $gia = '<strong>' . numberformat($gia_ban) . ' <sup>đ</sup></strong>';
            } else {
                $gia_ban = 0;
                $gia = '<strong>Liên hệ</strong>';
            }
        }
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

<?php } elseif ($do == 'pagination_sanpham2') {
    $keyword       =   $_POST['key'];
    $id_loai      =   $_POST['id_loai'];
    $current_page   =   $_POST['page'];
    $total_page     =   $_POST['totalPages'];
    $limit          =   $_POST['limit'];
    if ($current_page > $total_page) {
        $current_page = $total_page;
    } else if ($current_page < 1) {
        $current_page = 1;
    }
    $start = ($current_page - 1) * $limit;
    if ($id_loai > 0) {
        $where_loai = " and id_loai = " . $id_loai . " ";
    } else {
        $where_loai = "";
    }
    $sanpham = $d->o_fet("select * from #-sanpham where ten like '%" . $keyword . "%' $where_loai  and hien_thi =1 " . _where_lang . " order by so_thu_tu ASC, id DESC limit $start,$limit ");
?>
    <?php foreach ($sanpham as $key => $item) {
        if ($item['khuyen_mai'] > 0) {
            $gia_ban = $item['khuyen_mai'];
            $gia_km = $item['gia'];
            $giamgia = (($gia_km - $gia_ban) / $gia_km) * 100;
            $gia = '<span><strong>' . numberformat($gia_ban) . ' VNĐ </strong><del>' .  number_format($gia_km) . ' VNĐ</del></span> <span class="sale">-' . ceil($giamgia) . '%</span>';
        } else {
            if ($item['gia'] > 0) {
                $gia_ban = $item['gia'];
                $gia_km = 0;
                $gia = '<strong>' . numberformat($gia_ban) . ' VNĐ</strong>';
            } else {
                $gia_ban = 0;
                $gia = '<strong>Liên hệ</strong>';
            }
        }
        $thuoctinh_chitiet = $d->o_fet("SELECT * FROM `db_sanpham_chitiet` WHERE id_sp = " . (int)$item['id_code'] . " and id_loai = 0");
    ?>
        <div class="col">
            <div class="product_item ">
                <a <?= cf_tag_a_url($item['slug'], $item['alias'], $item['nofollow'], $item['target']) ?> title="<?= $item['ten'] ?>" class="img_box">
                    <img class="img_show" src="<?= Img($item['hinh_anh']) ?>" alt="<?= $item['ten'] ?>">
                    <img src="<?= $item['hinh_anh2'] != '' ? Img($item['hinh_anh2']) : Img($item['hinh_anh']) ?>" class="img_hover" alt="<?= $item['ten'] ?>" />
                </a>
                <div class="product_content">
                    <h3 class="title"><a <?= cf_tag_a_url($item['slug'], $item['alias'], $item['nofollow'], $item['target']) ?> title="<?= $item['ten'] ?>"><?= $item['ten'] ?></a></h3>
                    <div class="sao">
                        <?php
                        $sao = $item['ma_sp'];
                        if ((int)$sao == 0) {
                            $sao =  randomInRange();
                        }
                        for ($i = 0; $i < $sao; $i++) { ?>
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-star-fill" viewBox="0 0 16 16">
                                <path d="M3.612 15.443c-.386.198-.824-.149-.746-.592l.83-4.73L.173 6.765c-.329-.314-.158-.888.283-.95l4.898-.696L7.538.792c.197-.39.73-.39.927 0l2.184 4.327 4.898.696c.441.062.612.636.282.95l-3.522 3.356.83 4.73c.078.443-.36.79-.746.592L8 13.187l-4.389 2.256z" />
                            </svg>
                        <?php } ?>
                        <?php for ($i = 0; $i < (5 - $sao); $i++) { ?>
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-star" viewBox="0 0 16 16">
                                <path d="M2.866 14.85c-.078.444.36.791.746.593l4.39-2.256 4.389 2.256c.386.198.824-.149.746-.592l-.83-4.73 3.522-3.356c.33-.314.16-.888-.282-.95l-4.898-.696L8.465.792a.513.513 0 0 0-.927 0L5.354 5.12l-4.898.696c-.441.062-.612.636-.283.95l3.523 3.356-.83 4.73zm4.905-2.767-3.686 1.894.694-3.957a.565.565 0 0 0-.163-.505L1.71 6.745l4.052-.576a.525.525 0 0 0 .393-.288L8 2.223l1.847 3.658a.525.525 0 0 0 .393.288l4.052.575-2.906 2.77a.565.565 0 0 0-.163.506l.694 3.957-3.686-1.894a.503.503 0 0 0-.461 0z" />
                            </svg>
                        <?php } ?>
                    </div>
                    <div class="price d-flex justify-content-between align-items-center">
                        <?= $gia ?>
                    </div>
                    <div class=" d-flex justify-content-between align-items-center">
                        <a class="product_cate" href=""><?= $d->getCate($item['id_loai'], 'ten') ?></a>
                        <div class="list_size">
                            <?php foreach ($thuoctinh_chitiet as $key2 => $value) { ?>
                                <span><?= $value['ten'] ?></span>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php } ?>
<?php } ?>