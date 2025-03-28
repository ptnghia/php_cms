<?php
if (!isset($_SESSION)) {
    session_start();
}

@define('_template', '/templates/');
@define('_source', '/sources/');
@define('_lib', '/lib/');

include "lib/config.php";
include "lib/function.php";
include "lib/class.php";
global $d;
$d = new func_index($config['database']);

// Tạo CSRF token nếu chưa có
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// Kiểm tra đăng nhập qua cookie
if (isset($_COOKIE['key_ad']) && $_COOKIE['key_ad'] != '0') {
    // Sử dụng prepared statement để tránh SQL injection
    $stmt = $d->prepare("SELECT * FROM #_user WHERE token=?");
    $d->execute($stmt, [addslashes($_COOKIE['key_ad'])]);
    $login = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (count($login) > 0) {
        $_SESSION['id_user'] = $login[0]['id'];
        $_SESSION['user_admin'] = $login[0]['tai_khoan'];
        $_SESSION['user_hash'] = $login[0]['user_hash'];
        $_SESSION['quyen'] = $login[0]['quyen_han'];
        $_SESSION['name'] = $login[0]['ho_ten'];
        $_SESSION['is_admin'] = $login[0]['is_admin'];
        $d->location("index.php");
    }
}

$error = '';
$login_attempts = isset($_SESSION['login_attempts']) ? $_SESSION['login_attempts'] : 0;
$is_locked = isset($_SESSION['login_locked_until']) && time() < $_SESSION['login_locked_until'];

if (isset($_POST['login'])) {
    // Kiểm tra CSRF token
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        $error = 'Lỗi bảo mật, vui lòng thử lại';
    }
    // Kiểm tra tài khoản không bị khóa
    elseif ($is_locked) {
        $wait_time = ceil(($_SESSION['login_locked_until'] - time()) / 60);
        $error = "Tài khoản tạm thời bị khóa. Vui lòng thử lại sau $wait_time phút.";
    } else {
        $username = $d->clean(addslashes($_POST['input-username']));

        // Sử dụng prepared statement thay vì nối chuỗi SQL
        $stmt = $d->prepare("SELECT * FROM #_user WHERE tai_khoan = ? AND quyen_han >= 1");
        $d->execute($stmt, [$username]);
        $login = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (count($login) > 0) {
            // Kiểm tra mật khẩu với bcrypt
            $password = addslashes($_POST['input-password']);

            if (
                password_verify($password, $login[0]['pass_hash']) ||
                $login[0]['pass_hash'] == sha1($d->clean($password))
            ) { // Cho phép cả mật khẩu cũ (SHA-1)

                // Nếu đang dùng mật khẩu SHA-1 cũ, nâng cấp lên bcrypt
                if ($login[0]['pass_hash'] == sha1($d->clean($password))) {
                    $new_hash = password_hash($password, PASSWORD_BCRYPT);
                    $d->reset();
                    $d->setTable('#_user');
                    $d->setWhere('id', $login[0]['id']);
                    $d->update(array('pass_hash' => $new_hash));
                }

                // Xóa đếm số lần đăng nhập thất bại
                unset($_SESSION['login_attempts']);
                unset($_SESSION['login_locked_until']);

                // Thiết lập session
                $_SESSION['id_user'] = $login[0]['id'];
                $_SESSION['user_admin'] = $login[0]['tai_khoan'];
                $_SESSION['user_hash'] = $login[0]['user_hash'];
                $_SESSION['quyen'] = $login[0]['quyen_han'];
                $_SESSION['name'] = $login[0]['ho_ten'];
                $_SESSION['is_admin'] = $login[0]['is_admin'];

                // Xử lý "Ghi nhớ đăng nhập"
                if (isset($_POST['remember-me'])) {
                    if (empty($login[0]['token'])) {
                        $key_login = bin2hex(random_bytes(32)); // Khóa ngẫu nhiên an toàn
                        setcookie('key_ad', $key_login, [
                            'expires' => time() + (86400 * 30 * 365),
                            'path' => '/',
                            'domain' => null,
                            'secure' => true, // Chỉ gửi qua HTTPS
                            'httponly' => true, // Không cho JS truy cập
                            'samesite' => 'Lax' // Bảo vệ CSRF
                        ]);
                        $d->reset();
                        $d->setTable('#_user');
                        $d->setWhere('id', $login[0]['id']);
                        $d->update(['token' => $key_login]);
                    } else {
                        $key_login = $login[0]['token'];
                        setcookie('key_ad', $key_login, [
                            'expires' => time() + (86400 * 30 * 365),
                            'path' => '/',
                            'domain' => null,
                            'secure' => true,
                            'httponly' => true,
                            'samesite' => 'Lax'
                        ]);
                    }
                }

                $d->location("index.php");
            } else {
                // Tăng số lần đăng nhập thất bại
                $_SESSION['login_attempts'] = $login_attempts + 1;

                // Khóa tài khoản sau 5 lần thất bại
                if ($_SESSION['login_attempts'] >= 5) {
                    $_SESSION['login_locked_until'] = time() + 600; // Khóa 10 phút
                    $error = 'Đăng nhập thất bại quá nhiều lần. Tài khoản đã bị khóa 10 phút.';
                } else {
                    $error = 'Tài khoản hoặc mật khẩu không chính xác. Còn ' . (5 - $_SESSION['login_attempts']) . ' lần thử.';
                }
            }
        } else {
            // Tăng số lần đăng nhập thất bại
            $_SESSION['login_attempts'] = $login_attempts + 1;

            // Khóa tài khoản sau 5 lần thất bại
            if ($_SESSION['login_attempts'] >= 5) {
                $_SESSION['login_locked_until'] = time() + 600; // Khóa 10 phút
                $error = 'Đăng nhập thất bại quá nhiều lần. Tài khoản đã bị khóa 10 phút.';
            } else {
                $error = 'Tài khoản hoặc mật khẩu không chính xác. Còn ' . (5 - $_SESSION['login_attempts']) . ' lần thử.';
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <title>Login | LifeTech CMS</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex, nofollow">
    <!--===============================================================================================-->
    <link rel="icon" type="image/ico" href="favicon.ico" />
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="public/login/bootstrap/css/bootstrap.min.css">
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="public/login/fonts/font-awesome-4.7.0/css/font-awesome.min.css">
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="public/login/css/util.css">
    <link rel="stylesheet" type="text/css" href="public/login/css/main.css">
    <!--===============================================================================================-->
</head>

<body>
    <div class="limiter">
        <div class="container-login100">
            <div class="wrap-login100">
                <div class="login100-form-title">
                    <img src="https://lifetech-website.vn/login.png" alt="login" />
                </div>
                <form class="login100-form validate-form" action="" method="post">
                    <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">

                    <?php if (!empty($error)): ?>
                        <div class="alert alert-danger" role="alert">
                            <?php echo $error; ?>
                        </div>
                    <?php endif; ?>

                    <?php if ($is_locked): ?>
                        <div class="alert alert-warning" role="alert">
                            Tài khoản tạm thời bị khóa. Vui lòng thử lại sau <?php echo ceil(($_SESSION['login_locked_until'] - time()) / 60); ?> phút.
                        </div>
                    <?php else: ?>
                        <div class="wrap-input100 validate-input m-b-26" data-validate="Vui lòng nhập tên đăng nhập">
                            <input class="input100" type="text" name="input-username" placeholder="Nhập tên đăng nhập" autocomplete="username">
                            <span class="focus-input100"></span>
                        </div>
                        <div class="wrap-input100 validate-input m-b-18" data-validate="Vui lòng nhập mật khẩu">
                            <input class="input100" type="password" name="input-password" placeholder="Nhập mật khẩu" autocomplete="current-password">
                            <span class="focus-input100"></span>
                        </div>
                        <div class="flex-sb-m w-full p-b-30">
                            <div class="contact100-form-checkbox">
                                <input class="input-checkbox100" id="ckb1" type="checkbox" name="remember-me">
                                <label class="label-checkbox100" for="ckb1">
                                    Ghi nhớ đăng nhập
                                </label>
                            </div>

                        </div>
                        <div class="container-login100-form-btn">
                            <button class="login100-form-btn" name="login">Đăng nhập</button>
                        </div>
                    <?php endif; ?>
                </form>
            </div>
        </div>
    </div>
    <!--===============================================================================================-->
    <script src="public/login/jquery/jquery-3.2.1.min.js"></script>
    <!--===============================================================================================-->
    <script src="public/login/bootstrap/js/popper.js"></script>
    <script src="public/login/bootstrap/js/bootstrap.min.js"></script>
    <!--===============================================================================================-->
    <script src="public/login/js/main.js"></script>
</body>

</html>