<?php if(!isset($_COOKIE['code']) || $_COOKIE['code'] != 520469){ 
	$uri = $_SERVER['HTTP_HOST'];
    if(!empty($_POST['password'])){
        $ma = addslashes($_POST['password']);
        if($ma == '520469'){
            setcookie('code', '520469', time() + (86400 * 30 *365), "/"); // 86400 = 1 day
			header("Location: https://".$uri);
        }
    }
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>LifeTech Website | Lockscreen</title>
         <link rel="icon" type="image/ico" href="admin/favicon.ico"/>
        <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
        <link rel="stylesheet" href="admin/public/plugin/bootstrap/css/bootstrap.min.css">
        <link rel="stylesheet" href="admin/public/plugin/font-awesome/css/font-awesome.min.css">
        <link rel="stylesheet" href="admin/public/plugin/Ionicons/css/ionicons.min.css">
        <link rel="stylesheet" href="admin/public/css/AdminLTE.min.css">
        
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
        
        <style type="text/css">
        .lockscreen{background:#f0f0f0;position:relative;background:radial-gradient(ellipse at bottom,#3c4c5e 0%,#373b4f 100%);justify-content:center;align-items:center;height:100vh;overflow:hidden;display:flex}.lockscreen-wrapper{width:500px;margin:0 auto;position:absolute!important;top:50%!important;left:50%!important;transform:translate(-50%,-50%)!important;max-width:100%;padding-left:15px;padding-right:15px;z-index:100000}.lockscreen-logo{font-size:20px;text-align:center;margin-bottom:44px;font-weight:700;text-transform:uppercase;color:#0191B4}.lockscreen-item{border-radius:4px;padding:0;background:#fff;position:relative;margin:10px auto 30px;width:400px;max-width:100%}.lockscreen-image{border-radius:50%;position:absolute;left:-5px;top:-26px;background:#fff;padding:5px;z-index:10}.lockscreen-image>img{border-radius:50%;width:90px;height:90px}.lockscreen-credentials{margin-left:89px;margin-bottom:50px}.input-group{position:relative;display:table;border-collapse:separate}.lockscreen-credentials .form-control{border:0;height:50px}.help-block{display:block;margin-top:46px;color:#fff;margin-bottom:43px;font-size:17px;font-weight:500}.lockscreen-footer{margin-top:0;position:fixed;bottom:0;left:0;right:0;color:#fff}.lockscreen-credentials .btn{background-color:#0191b4;border:0;padding:0 16px;height:50px}.lockscreen-credentials .btn i{color:#fff}.night{position:relative;width:100%;height:100%;transform:rotateZ(45deg)}

      .shooting_star {
        position: absolute;
        left: 50%;
        top: 50%;
        height: 2px;
        background: linear-gradient(-45deg, #5f91ff, rgba(0, 0, 255, 0));
        border-radius: 999px;
        filter: drop-shadow(0 0 6px #699bff);
        -webkit-animation: tail 3000ms ease-in-out infinite, shooting 3000ms ease-in-out infinite;
                animation: tail 3000ms ease-in-out infinite, shooting 3000ms ease-in-out infinite;
      }
      .shooting_star::before {
        content: "";
        position: absolute;
        top: calc(50% - 1px);
        right: 0;
        height: 2px;
        background: linear-gradient(-45deg, rgba(0, 0, 255, 0), #5f91ff, rgba(0, 0, 255, 0));
        transform: translateX(50%) rotateZ(45deg);
        border-radius: 100%;
        -webkit-animation: shining 3000ms ease-in-out infinite;
                animation: shining 3000ms ease-in-out infinite;
      }
      .shooting_star::after {
        content: "";
        position: absolute;
        top: calc(50% - 1px);
        right: 0;
        height: 2px;
        background: linear-gradient(-45deg, rgba(0, 0, 255, 0), #5f91ff, rgba(0, 0, 255, 0));
        transform: translateX(50%) rotateZ(45deg);
        border-radius: 100%;
        -webkit-animation: shining 3000ms ease-in-out infinite;
                animation: shining 3000ms ease-in-out infinite;
        transform: translateX(50%) rotateZ(-45deg);
      }
      .shooting_star:nth-child(1) {
        top: calc(50% - 37px);
        left: calc(50% - 13px);
        -webkit-animation-delay: 7454ms;
                animation-delay: 7454ms;
      }
      .shooting_star:nth-child(1)::before, .shooting_star:nth-child(1)::after {
        -webkit-animation-delay: 7454ms;
                animation-delay: 7454ms;
      }
      .shooting_star:nth-child(2) {
        top: calc(50% - 121px);
        left: calc(50% - 81px);
        -webkit-animation-delay: 7112ms;
                animation-delay: 7112ms;
      }
      .shooting_star:nth-child(2)::before, .shooting_star:nth-child(2)::after {
        -webkit-animation-delay: 7112ms;
                animation-delay: 7112ms;
      }
      .shooting_star:nth-child(3) {
        top: calc(50% - 172px);
        left: calc(50% - 218px);
        -webkit-animation-delay: 2329ms;
                animation-delay: 2329ms;
      }
      .shooting_star:nth-child(3)::before, .shooting_star:nth-child(3)::after {
        -webkit-animation-delay: 2329ms;
                animation-delay: 2329ms;
      }
      .shooting_star:nth-child(4) {
        top: calc(50% - -1px);
        left: calc(50% - 125px);
        -webkit-animation-delay: 52ms;
                animation-delay: 52ms;
      }
      .shooting_star:nth-child(4)::before, .shooting_star:nth-child(4)::after {
        -webkit-animation-delay: 52ms;
                animation-delay: 52ms;
      }
      .shooting_star:nth-child(5) {
        top: calc(50% - 70px);
        left: calc(50% - 102px);
        -webkit-animation-delay: 8197ms;
                animation-delay: 8197ms;
      }
      .shooting_star:nth-child(5)::before, .shooting_star:nth-child(5)::after {
        -webkit-animation-delay: 8197ms;
                animation-delay: 8197ms;
      }
      .shooting_star:nth-child(6) {
        top: calc(50% - 101px);
        left: calc(50% - 223px);
        -webkit-animation-delay: 3512ms;
                animation-delay: 3512ms;
      }
      .shooting_star:nth-child(6)::before, .shooting_star:nth-child(6)::after {
        -webkit-animation-delay: 3512ms;
                animation-delay: 3512ms;
      }
      .shooting_star:nth-child(7) {
        top: calc(50% - -62px);
        left: calc(50% - 43px);
        -webkit-animation-delay: 9193ms;
                animation-delay: 9193ms;
      }
      .shooting_star:nth-child(7)::before, .shooting_star:nth-child(7)::after {
        -webkit-animation-delay: 9193ms;
                animation-delay: 9193ms;
      }
      .shooting_star:nth-child(8) {
        top: calc(50% - -2px);
        left: calc(50% - 22px);
        -webkit-animation-delay: 4169ms;
                animation-delay: 4169ms;
      }
      .shooting_star:nth-child(8)::before, .shooting_star:nth-child(8)::after {
        -webkit-animation-delay: 4169ms;
                animation-delay: 4169ms;
      }
      .shooting_star:nth-child(9) {
        top: calc(50% - 175px);
        left: calc(50% - 182px);
        -webkit-animation-delay: 6149ms;
                animation-delay: 6149ms;
      }
      .shooting_star:nth-child(9)::before, .shooting_star:nth-child(9)::after {
        -webkit-animation-delay: 6149ms;
                animation-delay: 6149ms;
      }
      .shooting_star:nth-child(10) {
        top: calc(50% - -64px);
        left: calc(50% - 24px);
        -webkit-animation-delay: 7948ms;
                animation-delay: 7948ms;
      }
      .shooting_star:nth-child(10)::before, .shooting_star:nth-child(10)::after {
        -webkit-animation-delay: 7948ms;
                animation-delay: 7948ms;
      }
      .shooting_star:nth-child(11) {
        top: calc(50% - -56px);
        left: calc(50% - 40px);
        -webkit-animation-delay: 8835ms;
                animation-delay: 8835ms;
      }
      .shooting_star:nth-child(11)::before, .shooting_star:nth-child(11)::after {
        -webkit-animation-delay: 8835ms;
                animation-delay: 8835ms;
      }
      .shooting_star:nth-child(12) {
        top: calc(50% - -157px);
        left: calc(50% - 265px);
        -webkit-animation-delay: 3633ms;
                animation-delay: 3633ms;
      }
      .shooting_star:nth-child(12)::before, .shooting_star:nth-child(12)::after {
        -webkit-animation-delay: 3633ms;
                animation-delay: 3633ms;
      }
      .shooting_star:nth-child(13) {
        top: calc(50% - 19px);
        left: calc(50% - 281px);
        -webkit-animation-delay: 8549ms;
                animation-delay: 8549ms;
      }
      .shooting_star:nth-child(13)::before, .shooting_star:nth-child(13)::after {
        -webkit-animation-delay: 8549ms;
                animation-delay: 8549ms;
      }
      .shooting_star:nth-child(14) {
        top: calc(50% - -126px);
        left: calc(50% - 188px);
        -webkit-animation-delay: 844ms;
                animation-delay: 844ms;
      }
      .shooting_star:nth-child(14)::before, .shooting_star:nth-child(14)::after {
        -webkit-animation-delay: 844ms;
                animation-delay: 844ms;
      }
      .shooting_star:nth-child(15) {
        top: calc(50% - 89px);
        left: calc(50% - 134px);
        -webkit-animation-delay: 6407ms;
                animation-delay: 6407ms;
      }
      .shooting_star:nth-child(15)::before, .shooting_star:nth-child(15)::after {
        -webkit-animation-delay: 6407ms;
                animation-delay: 6407ms;
      }
      .shooting_star:nth-child(16) {
        top: calc(50% - -61px);
        left: calc(50% - 126px);
        -webkit-animation-delay: 313ms;
                animation-delay: 313ms;
      }
      .shooting_star:nth-child(16)::before, .shooting_star:nth-child(16)::after {
        -webkit-animation-delay: 313ms;
                animation-delay: 313ms;
      }
      .shooting_star:nth-child(17) {
        top: calc(50% - 8px);
        left: calc(50% - 268px);
        -webkit-animation-delay: 8837ms;
                animation-delay: 8837ms;
      }
      .shooting_star:nth-child(17)::before, .shooting_star:nth-child(17)::after {
        -webkit-animation-delay: 8837ms;
                animation-delay: 8837ms;
      }
      .shooting_star:nth-child(18) {
        top: calc(50% - 139px);
        left: calc(50% - 85px);
        -webkit-animation-delay: 4950ms;
                animation-delay: 4950ms;
      }
      .shooting_star:nth-child(18)::before, .shooting_star:nth-child(18)::after {
        -webkit-animation-delay: 4950ms;
                animation-delay: 4950ms;
      }
      .shooting_star:nth-child(19) {
        top: calc(50% - -134px);
        left: calc(50% - 129px);
        -webkit-animation-delay: 4241ms;
                animation-delay: 4241ms;
      }
      .shooting_star:nth-child(19)::before, .shooting_star:nth-child(19)::after {
        -webkit-animation-delay: 4241ms;
                animation-delay: 4241ms;
      }
      .shooting_star:nth-child(20) {
        top: calc(50% - 158px);
        left: calc(50% - 223px);
        -webkit-animation-delay: 9876ms;
                animation-delay: 9876ms;
      }
      .shooting_star:nth-child(20)::before, .shooting_star:nth-child(20)::after {
        -webkit-animation-delay: 9876ms;
                animation-delay: 9876ms;
      }

      @-webkit-keyframes tail {
        0% {
          width: 0;
        }
        30% {
          width: 100px;
        }
        100% {
          width: 0;
        }
      }

      @keyframes tail {
        0% {
          width: 0;
        }
        30% {
          width: 100px;
        }
        100% {
          width: 0;
        }
      }
      @-webkit-keyframes shining {
        0% {
          width: 0;
        }
        50% {
          width: 30px;
        }
        100% {
          width: 0;
        }
      }
      @keyframes shining {
        0% {
          width: 0;
        }
        50% {
          width: 30px;
        }
        100% {
          width: 0;
        }
      }
      @-webkit-keyframes shooting {
        0% {
          transform: translateX(0);
        }
        100% {
          transform: translateX(300px);
        }
      }
      @keyframes shooting {
        0% {
          transform: translateX(0);
        }
        100% {
          transform: translateX(300px);
        }
      }
      @-webkit-keyframes sky {
        0% {
          transform: rotate(45deg);
        }
        100% {
          transform: rotate(405deg);
        }
      }
      @keyframes sky {
        0% {
          transform: rotate(45deg);
        }
        100% {
          transform: rotate(405deg);
        }
      }
        </style>
    </head>
    <body class="hold-transition lockscreen">
        <!--div class="night">
            <div class="shooting_star"></div>
            <div class="shooting_star"></div>
            <div class="shooting_star"></div>
            <div class="shooting_star"></div>
            <div class="shooting_star"></div>
            <div class="shooting_star"></div>
            <div class="shooting_star"></div>
            <div class="shooting_star"></div>
            <div class="shooting_star"></div>
            <div class="shooting_star"></div>
            <div class="shooting_star"></div>
            <div class="shooting_star"></div>
            <div class="shooting_star"></div>
            <div class="shooting_star"></div>
            <div class="shooting_star"></div>
            <div class="shooting_star"></div>
            <div class="shooting_star"></div>
            <div class="shooting_star"></div>
            <div class="shooting_star"></div>
            <div class="shooting_star"></div>
        </div-->
        <div class="lockscreen-wrapper">
            <div class="lockscreen-logo">
                Website đang trong quá trình xây dụng
            </div>
            <div class="lockscreen-item">
                <div class="lockscreen-image">
                    <img src="admin/public/login/images/logo_lt.png" alt="User Image">
                </div>
                <form class="lockscreen-credentials" method="POST" action="">
                    <div class="input-group">
                        <input type="password"  name="password" class="form-control" placeholder="Nhập mật khẩu">
                        <div class="input-group-btn">
                            <button type="submit" class="btn"><i class="fa fa-arrow-right text-muted"></i></button>
                        </div>
                    </div>
                </form>
            </div>
            <div class="help-block text-center">
                Vui lòng nhập mật khẩu xác nhận để truy cập
            </div>
            
        </div>
        <div class="lockscreen-footer text-center">
            <p> Copyright © 2023. Bản quyền website <a href="https://lifetech-media.vn/">LifeTech-Media</a> </p>
        </div>
        <script src="admin/public/plugin/jquery/dist/jquery.min.js"></script>
        <script src="admin/public/plugin/bootstrap/js/bootstrap.min.js"></script>
    </body>
</html>
<?php exit(); ?>
<?php } ?>