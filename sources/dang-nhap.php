<?php

//require_once './library/vendor/autoload.php';
//use \Firebase\JWT\JWT;
//$key="205049";


$link=explode("?",$_SERVER['REQUEST_URI']);
if($link[1]!=''){
    $vari=explode("&",$link[1]);
    $search=array();
    foreach($vari as $item) {
        $str=explode("=",$item);
        $search["$str[0]"]=$str[1];
    }
}
if(isset($search) and $search['url']!=''){
    $url_back = URLPATH.$search['url'].'.html';
}else{
    $url_back = URLPATH;
}
if(isset($_POST['login']) ){
    if($_SESSION['token']  == $_POST['_token']){
        $md5_email  = MD5($_POST['email']);
        $matkhau    = MD5($_POST['password']);

        $row_tv     = $d->num_rows("select * from #_thanhvien where md5_email ='".$md5_email."' and  mat_khau ='".$matkhau."' ");
        if(count($row_tv)>0){
            $thanhvien = $d->simple_fetch("select * from #_thanhvien where md5_email = '".$md5_email."' and mat_khau='".$matkhau."' ");
            if($thanhvien['trang_thai']==2){
                token();
                $_SESSION['id_login']           = $thanhvien['id'];
                $_SESSION['thanhvien_login']    = $thanhvien['attn'];
                if(isset($_POST['save'])){
                    if($thanhvien['token']==""){
                        $token  =   chuoird(6).$_POST['_token'];
                        $data_tv['token'] =  $token;
                        $d->reset();
                        $d->setTable('#_thanhvien');
                        $d->setWhere('id', $thanhvien['id']);
                        $d->update($data_tv);
                    }else{
                        $token  =   $thanhvien['token'];
                    }
                    setrawcookie("keyId", urlencode($token), time()+(60*60*24*365), "/",NULL,FALSE,TRUE);
                }
                $thongbao_tt    =   $d->getTxt(34);
                $thongbao_icon  =   'success';
                $thongbao_content=  '';
                $thongbao_url       = cre_Link($d->getCate(10,'alias'));
            }else{
                $thongbao_tt        =   $d->getTxt(32);
                $thongbao_icon      =   'error';
                $thongbao_content   =   $d->getTxt(35);
                $thongbao_url       =   cre_Link($com);
            }

        }else{
            $thongbao_tt        =   $d->getTxt(32);
            $thongbao_icon      =   'error';
            $thongbao_content   =   $d->getTxt(36);
            $thongbao_url       =   cre_Link($com);
        }
    }else{
        $thongbao_tt        =   $d->getTxt(32);
        $thongbao_icon      =   'error';
        $thongbao_content   =   'Erore token';
        $thongbao_url       =   $url_page;
    }    
}
?>
<style>
    
    main{
        background-image: url('<?=Img($row['banner'])?>');
        background-size: cover;
        background-repeat: no-repeat;
        background-position: center;
    }
</style>
<?php if($row['banner']!=''){ ?>
<!--img src="<?=Img($row['banner'])?>" style="width: 100%;max-height: 400px;object-fit: cover;" alt="<?=$row['ten']?>" /-->
<?php } ?>
<!--header class="py-2">
    <div class="tochuc text-center">
        <span><i class="fa-solid fa-map-location-dot"></i> <?=$tochuc['ten']?></span>
        <span><i class="fa-solid fa-calendar-days"></i> <?=$tochuc['link']?></span>
    </div>
</header-->
<div class="container mt-5 mb-5">
    <div class="row justify-content-center align-items-center position-relative"style="min-height: 60vh">
        <div class="col-lg-6 col-md-7 col-sm-2">
            <!--div class="text-center" style="margin-bottom: 30px;">
                <img style="height: 100px;" src="<?=_favicon?>" alt="" />
            </div-->
            <div class="box-login" style="box-shadow: 0px 15px 25px 0px rgba(0, 0, 0, 0.08);border: none;    background-color: #fff;">
                <h1 class="title text-center"><?=$d->getTxt(3)?></h1>
                <form method="POST" action="" class="form-cart" id="form-dangnhap">
                    <input type="hidden" value="<?=$_SESSION['token']?>" name="_token" />
                    <div class="mb-3">
                        <label class="form-label">Login ID</label>
                        <input type="email" required="" name="email" class=" form-control"  placeholder="<?=$d->getTxt(4)?>">
                    </div>
                    <div class="form-group mb-3">
                        <label class="form-label"><?=$d->getTxt(5)?></label>
                        <input required="" type="password" class=" form-control" name="password" placeholder="<?=$d->getTxt(6)?>">
                    </div>
                    <div class="mb-4 d-flex justify-content-between">
                        <a href="<?=  cre_Link($d->getCate(19,'alias'))?>" target="_blank"><?=$d->getTxt(7)?>?</a> 
                    </div>
                    <div class="text-center">
                        <button type="submit" class="btn btn-main btn-primary w-100" name="login"><?=$d->getTxt(8)?></button>
                        <!--p class="mt-3"><a href="<?=  cre_Link($d->getCate(20,'alias'))?>"><b><?=$d->getCate(20,'ten')?></b></a></p-->
                    </div>
                </form>
            </div>
        </div>  
    </div>
</div>

<script type="text/javascript" src="https://ajax.aspnetcdn.com/ajax/jquery.validate/1.13.1/jquery.validate.min.js"></script>
<script>

$(document).ready(function() {
    //Khi bàn phím được nhấn và thả ra thì sẽ chạy phương thức này
    $("#form-dangnhap").validate({
        rules: { 
            email: {
                required: true,
                email: true
            },
            password: {
                required: true
            }
        },
        messages: {
            email: {
                required: "Please enter email",
                email: "Please enter the correct email format"
            },
            password: {
                required: "Please enter password"
            }
        }
    });
});
</script>