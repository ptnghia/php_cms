<?php
$lang = get_json('lang');
$languages = array();
foreach ($lang as $key => $value) {
    $languages[$key] = $value['code'];
}

if (count($languages) > 1) {
    
    if ($_REQUEST['lang'] && in_array($_REQUEST['lang'], $languages)) {
        $_SESSION['lang'] = $_REQUEST['lang'];
    } else {
        if (!isset($_SESSION['lang']) || !in_array($_SESSION['lang'], $languages)) {
            $_SESSION['lang'] = $languages[0];
            header("Location:" . URLPATH . $_SESSION['lang'] . '/');
            exit();
        } else {
            header("Location:" . URLPATH . $_SESSION['lang'] . '/');
            exit();
        }
    }
    $lang = $_SESSION['lang'];
    $where_lang = "and lang ='" . $_SESSION['lang'] . "'";
    define("_URLLANG",  URLPATH . $_SESSION['lang'] . '/');
} else {
    $_SESSION['lang'] = LANG;
    $where_lang = "and lang ='" . $_SESSION['lang'] . "'";
    define("_URLLANG",  URLPATH);
}

define("_lang",  $_SESSION['lang']);
define("_where_lang",  $where_lang);
