<?php
session_start();
ob_start();

// Change from error_reporting(0) for better debugging during development
error_reporting(E_ALL & ~E_NOTICE & ~E_DEPRECATED);
ini_set('display_errors', 0); // Don't display errors in production

define('_source', './sources/');
define('_lib', './admin/lib/');
define('_source_lib', 'sources/lib/');
global $d;
global $lang;
include _lib . "config.php";
include_once _lib . "function.php";
include_once _lib . "class.php";
$d = new func_index($config['database']); // Khởi tạo kết nối cơ sở dữ liệu
$db = $d; // Đảm bảo biến $db được sử dụng trong ddos_protection.php
include_once _source_lib . "lang.php";
include_once _source_lib . "info.php";
include_once _source_lib . "function.php";
include _source_lib . "file_router_index.php";
$ZERO_PATH = _lib . "Mobile_Detect.php";
require_once($ZERO_PATH);
$detect = new Mobile_Detect;

// Bỏ qua kiểm tra DDoS và CSRF cho sitemap.xml
if (basename($_SERVER['REQUEST_URI']) === 'sitemap.xml') {
    return;
}

// Include DDoS protection
if (file_exists('log/ddos_protection.php')) {
    include 'log/ddos_protection.php';
} else {
    die('DDoS protection file not found.');
}

if (!isset($_SESSION['token'])) {
    token();
}

// Add CSRF protection for POST requests
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SESSION['token'])) {
    if (!isset($_POST['token']) || $_POST['token'] !== $_SESSION['token']) {
        die('Invalid CSRF token');
    }
}

// Bảo vệ tệp .htaccess
if (!file_exists('.htaccess')) {
    file_put_contents('.htaccess', "Order Allow,Deny\nAllow from all\n");
}

?>
<!DOCTYPE html>
<html lang="<?= _lang ?>">

<head>
    <meta http-equiv="content-type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php include _source . "module/seo.php" ?>
    <?php include _source . "templates/css.php" ?>
    <?php if ($com != '') { ?>
    <?= $row['seo_head'] ?>
    <?php } ?>
</head>

<body>
    <?php include _source . "_header.php"; ?>
    <main>
        <?php include _source . $source . ".php"; ?>
    </main>
    <?php include _source . "_footer.php"; ?>
    <?php include _source . "templates/js.php" ?>
    <?php include _source . "module/hotrotructuyen.php" ?>
    <?php include 'sitemap/seo_footer.inc'; ?>
    <?php if ($com != '') { ?>
    <?= $row['seo_body'] ?>
    <?php } ?>
</body>

</html>

<?php $d->disconnect() ?>