<?php
if (!isset($nav_active)) {
    $nav_active = ''; // Initialize $nav_active if not set
}
$menu = "";
$nav  = $d->o_fet("select * from #_category where menu = 1 and hien_thi=1 " . _where_lang . " order by so_thu_tu asc, id desc");
//print_r($nav);
foreach ($nav as $item) {
    if (isset($item['id_code']) && $nav_active == $item['id_code']) {
        $active = "active";
    } else {
        $active = "";
    }
    $sub = $d->o_fet("select * from #_category where id_loai={$item['id_code']} and hien_thi=1 " . _where_lang . " order by so_thu_tu asc, id desc");
    if (!empty($sub)) {
        $menusub = "";
        foreach ($sub as $key => $item1) {
            if (isset($row['id_code']) && ($row['id_code'] == $item1['id_code'] || $row['id_loai'] == $item1['id_code'])) {
                $active2 = "active";
            } else {
                $active2 = "";
            }
            $sub1 = $d->getCates($item1['id_code']);
            if (!empty($sub1)) {
                $menusub2 = "";
                foreach ($sub1 as $key1 => $item2) {
                    if ($row['id_code'] == $item2['id_code'] or $row['id_loai'] == $item2['id_code']) {
                        $active3 = "active";
                    } else {
                        $active3 = "";
                    }
                    $menusub2 .= '<li><a class="' . $active3 . '" href="' . _URLLANG . $item2['alias'] . '.html" title="' . $item2['ten'] . '">' . $item2['ten'] . '</a></li>';
                }
                $menusub .= '
                        <li>
                            <a class="' . $active2 . '" href="' . _URLLANG . $item1['alias'] . '.html" title="' . $item1['ten'] . '"> ' . $item1['ten'] . ' <span class="caret"></span></a>
                            <ul>' . $menusub2 . '</ul>
                        </li>';
            } else {
                $menusub .= '<li><a class="' . $active2 . '" href="' . _URLLANG . $item1['alias'] . '.html" title="' . $item1['ten'] . '">' . $item1['ten'] . '</a></li>';
            }
        }
        $menu .= '<li>
                        <a class="' . $active . '" href="' . _URLLANG . $item['alias'] . '.html"  title="' . $item['ten'] . '">' . $item['ten'] . '</a>
                        <ul>
                            ' . $menusub . '
                        </ul>
                    </li>';
    } else {
        $menu .= '<li><a class="' . $active . '" href="' . _URLLANG . $item['alias'] . '.html" title="' . $item['ten'] . '">' . $item['ten'] . '</a></li>';
    }
}
//echo $menu;
?>
<link href="<?= URLPATH ?>templates/module/HC-MobileNav/css/HC-Mobilenav.css" rel="stylesheet" />
<nav id="main-nav">
    <ul class="first-nav d-flex justify-content-between">
        <li>
            <a href="<?= URLPATH ?>" title="<?= _ten_cong_ty ?>">
                <img height="80" src="<?= _favicon ?>" alt="<?= _ten_cong_ty ?>" />
            </a>
        </li>
        <li class="nav-close"><a href="#"><i class="fa-solid fa-x"></i></a></li>

    </ul>
    <ul class="second-nav">
        <li class="form-search-hc">
            <form method="get" action="index.php" role="search">
                <input type="hidden" name="lang" value="<?= $lang ?>">
                <input type="hidden" name="com" value="search">
                <button class="btn" type="submit"><i class="fa-solid fa-magnifying-glass"></i></button>
                <input class="form-control" type="search" name="textsearch" placeholder="<?= $d->getTxt(2) ?>..." aria-label="Search">
            </form>
        </li>
        <?php echo  $menu ?>

    </ul>
</nav>
<!-- hc-offcanvas-nav -->
<script src="<?= URLPATH ?>templates/module/HC-MobileNav/js/hc-offcanvas-nav.js"></script>
<script>
    (function($) {
        var $main_nav = $('#main-nav');
        var $toggle = $('.navbar-toggle');
        var defaultData = {
            maxWidth: false,
            customToggle: $toggle,
            navTitle: false,
            levelTitles: true,
            pushContent: '#container',
            insertClose: false,
        };
        // call our plugin
        var Nav = $main_nav.hcOffcanvasNav(defaultData);

    })(jQuery);
</script>
<!-- hc-offcanvas-nav -->