<?php
$num_cart = 0;
$tong = 0;
if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $key => $value) {
        $price_h = $value['gia'];
        $num_cart = $num_cart + $value['so_luong'];
        $tong = $tong + ($price_h * $value['so_luong']);
    }
}
$link = explode("?", $_SERVER['REQUEST_URI']);
if (isset($link[1]) and  $link[1] != '') {
    $vari = explode("&", $link[1]);
    $search = array();
    foreach ($vari as $item) {
        $str = explode("=", $item);
        $search["$str[0]"] = $str[1];
    }
}
$nav  = $d->o_fet("select * from #_category where menu = 1 and hien_thi=1 " . _where_lang . " order by so_thu_tu asc, id desc");
?>
<?php if ($detect->isMobile() && !$detect->isTablet()) { ?>
    <nav class="navbar navbar-expand-lg navbar-light main-menu">
        <div class="container">
            <a class="navbar-brand logo d-md-none" href="<?= URLPATH ?>" title="<?= _ten_cong_ty ?>">
                <img src="<?= _logo ?>" alt="<?= _ten_cong_ty ?>" class="d-inline-block">
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav ">
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="#">Trang chủ</a>
                    </li>
                    <?php foreach ($nav as $key => $value) {
                        $submenu = $d->getCates($value['id_code']);
                        if (count($submenu) > 0) { ?>
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="<?= cre_Link($value['alias']) ?>" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    <?= $value['ten'] ?>
                                </a>
                                <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                                    <?php foreach ($submenu as $key => $value) { ?>
                                        <li><a class="dropdown-item" href="<?= cre_Link($value['alias']) ?>"><?= $value['ten'] ?></a></li>
                                    <?php } ?>
                                </ul>
                            </li>
                        <?php } else {
                        ?>
                            <li class="nav-item">
                                <a class="nav-link" href="<?= cre_Link($value['alias']) ?>"><?= $value['ten'] ?></a>
                            </li>
                    <?php }
                    } ?>
                </ul>

            </div>
        </div>
    </nav>
<?php } else { ?>
    <header>
        <div class="container">
            <div class="row justify-content-between align-items-center">
                <div class="col-lg-5 col-md-5">
                    <ul class="contact_head d-flex">
                        <li class="contact_head_item">
                            <a class="nav-link" href="tell:<?= _hotline ?>">
                                <i class="fas fa-phone-alt"></i> <?= _hotline ?>
                            </a>
                        </li>
                        <li class="contact_head_item">
                            <a class="nav-link" href="mailto:<?= _email ?>">
                                <i class="fas fa-envelope"></i> <?= _email ?>
                            </a>
                        </li>
                    </ul>
                </div>
                <div class="col-lg-2 col-md-2">
                    <div class="logo">
                        <a href="<?= URLPATH ?>" title="<?= _ten_cong_ty ?>">
                            <img src="<?= _logo ?>" alt="<?= _ten_cong_ty ?>" class="d-inline-block">
                        </a>
                    </div>
                </div>

                <div class="col-lg-5 col-md-5 d-flex justify-content-end align-items-center">
                    <div class="search">
                        <form action="<?= URLPATH ?>index.php" method="get">
                            <input type="hidden" name="com" value="search">
                            <input type="text" name="keyword" placeholder="Tìm kiếm..." value="<?= isset($search['keyword']) ? $search['keyword'] : '' ?>">
                            <button type="submit"><i class="fas fa-search"></i></button>
                        </form>
                    </div>
                </div>
            </div>
            <hr>
        </div>
    </header>
    <nav class="navbar navbar-expand-lg navbar-light main-menu">
        <div class="container">
            <a class="navbar-brand logo d-md-none" href="<?= URLPATH ?>" title="<?= _ten_cong_ty ?>">
                <img src="<?= _logo ?>" alt="<?= _ten_cong_ty ?>" class="d-inline-block">
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse d-flex justify-content-center" id="navbarSupportedContent">
                <ul class="navbar-nav w-100 justify-content-between mb-2 mb-lg-0 ">
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="#">Trang chủ</a>
                    </li>
                    <?php foreach ($nav as $key => $value) {
                        $submenu = $d->getCates($value['id_code']);
                        if (count($submenu) > 0) { ?>
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="<?= cre_Link($value['alias']) ?>" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    <?= $value['ten'] ?>
                                </a>
                                <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                                    <?php foreach ($submenu as $key => $value) { ?>
                                        <li><a class="dropdown-item" href="<?= cre_Link($value['alias']) ?>"><?= $value['ten'] ?></a></li>
                                    <?php } ?>
                                </ul>
                            </li>
                        <?php } else {
                        ?>
                            <li class="nav-item">
                                <a class="nav-link" href="<?= cre_Link($value['alias']) ?>"><?= $value['ten'] ?></a>
                            </li>
                    <?php }
                    } ?>
                </ul>

            </div>
        </div>
    </nav>
<?php } ?>