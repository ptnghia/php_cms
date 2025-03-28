<?php
include './smtp/index.php';
$link=explode("?",$_SERVER['REQUEST_URI']);
if($link[1]!=''){
    $vari=explode("&",$link[1]);
    $search=array();
    foreach($vari as $item) {
        $str=explode("=",$item);
        $search["$str[0]"]=$str[1];
    }
}
if(isset($_POST['quenpass']) and $_POST['_token'] == $_SESSION['token'] ){
    token();
    $email_tv       =   md5($_POST['email']);
    $dem = $d->num_rows("select id from #_thanhvien where md5_email= '".$email_tv."' ");
    if($dem>0){
        $row_tv = $d->simple_fetch("select id,email, ho_ten from #_thanhvien where md5_email= '".$email_tv."'");
        $data_tv['token'] = $_POST['_token'];
        $data_tv['trang_thai'] 	= 3;
        $d->reset();
        $d->setTable('#_thanhvien');
        $d->setWhere('id', $row_tv['id']);
        if($d->update($data_tv)){
            $link_config = URLPATH.'?com='.$com.'&verificationid='.$data_tv['token'].'&confirmid='.$email_tv;
            $noidung = '
       <div style="width: 700px;max-width: 100%;margin: 0 auto;padding: 20px;box-shadow: 1px 1px 10px 1px #ccc;">
            <p style="margin-bottom: 20px;"><b>Xin chào! '.$row_tv['ho_ten'].'!</b></p>
            <p><i>Bạn vừa yêu cầu đổi lại mật khẩu cho tài khoản của mình tại website '._web_page.'. Ấn vào link dưới đây để đổi mật khẩu mới.</i></p>
            <div style="text-align: center">
                <a style="background-color: #f7941d;border-color: #f7941d;color: #fff;display: inline-block;padding: 10px 30px;text-decoration: none;text-transform: uppercase;font-weight: 600;border-radius: 4px;margin-bottom: 15px;font-size: 13px;" href="'.$link_config.'" target="_blank">ĐỔI MẬT KHẨU</a>
            </div>
        </div>';
            if(sendmail($ten_cong_ty." - đổi mật khẩu!", $noidung, _email , $_POST['email'] ,  $ten_cong_ty)){
                $thongbao_tt        =   'Xác nhận yêu cầu đổi mật khẩu';
                $thongbao_icon      =   'success';
                $thongbao_content   =   'Vui lòng kiểm tra email và làm theo hướng dẫn';
                $thongbao_url       =   URLPATH;
            }else{
                $thongbao_tt        =   'Đã sảy ra lỗi!';
                $thongbao_icon      =   'error';
                $thongbao_content   =   'Lỗi gửi email xác nhận';
                $thongbao_url       =   URLPATH;
            }
        }
    }else{
        $thongbao_tt        =   'Đã sảy ra lỗi!';
        $thongbao_icon      =   'error';
        $thongbao_content   =   'Email chưa được đăng ký';
        $thongbao_url       =   URLPATH;
    }
}
if(isset($_POST['doipasss']) and $_POST['_token'] == $_SESSION['token'] and  $_SESSION['confirmid']!=''){
    token();
    $thanhvien = $d->simple_fetch("select * from #_thanhvien where md5_email = '".addslashes($_SESSION['confirmid'])."' and token ='".addslashes($_SESSION['verificationid'])."' and trang_thai = 3 ");
    if(count($thanhvien)>0 and $thanhvien['trang_thai']==3 and $_POST['mat_khau'] == $_POST['mat_khau2']){
        $data['mat_khau']           =   MD5($_POST['mat_khau']);
        $data['token']     			=  '';
        $data['trang_thai']         =  1;
        $d->reset();
        $d->setTable('#_thanhvien');
        $d->setWhere('id',$thanhvien['id']);
        if($d->update($data)){
            unset($_SESSION['confirmid']);
            unset($_SESSION['verificationid']);
            
            $thongbao_tt        =   'Mật khẩu đã dược cập nhật';
            $thongbao_icon      =   'success';
            $thongbao_content   =   '';
            $thongbao_url       =   URLPATH.$d->getCate(23,'alias').".html";
        }
    }else{
        $thongbao_tt        =   'Đã sảy ra lỗi';
        $thongbao_icon      =   'error';
        $thongbao_content   =   'Không tìm thấy yêu cầu đổi mật khẩu';
        $thongbao_url       =   URLPATH.$d->getCate(25,'alias').".html";
    }
}
if(isset($search['verificationid'])){
    
    $thanhvien = $d->simple_fetch("select * from #_thanhvien where md5_email = '".addslashes($search['confirmid'])."' and token ='".addslashes($search['verificationid'])."' and trang_thai = 3 ");
    if(count($thanhvien)>0){
        $_SESSION['verificationid'] =   $search['verificationid'];
        $_SESSION['confirmid']      =   $search['confirmid'];
        $d->location(URLPATH.$com.'.html?reset-password=1');
    }
    
    
}
if(isset($_SESSION['confirmid'])){
    $thanhvien = $d->simple_fetch("select * from #_thanhvien where md5_email = '".addslashes($_SESSION['confirmid'])."' and token ='".addslashes($_SESSION['verificationid'])."' and trang_thai = 3 ");
    if(count($thanhvien)==0){
        $d->location(URLPATH."404.html");
    }
}
if(isset($search['reset-password']) and !isset($_SESSION['confirmid'])){
    $d->location(URLPATH.$com.".html");
}
?>
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-lg-6 col-md-7 col-sm-2">
            <div class="login_wrap widget-taber-content background-white">
                <div class="box-login" style="min-height: auto;">
                    <?php if(isset($_SESSION['verificationid'])){ ?>
                    <h1 class="title" style="margin-top: 50px;"><?=$d->getTxt(56)?></h1>
                    <form method="POST" action="" class="form-cart" id="form-quenpass">
                        <input type="hidden" value="<?=$_SESSION['token']?>" name="_token" />
                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input type="text" required="" class="form-control"  name="email" readonly="" value="<?=$thanhvien['email']?>" placeholder="<?=$d->getTxt(31)?> *">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Mật khẩu</label>
                            <input required="" type="password" class="form-control" id="matkhau" name="mat_khau" placeholder="<?=$d->getTxt(66)?>">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Nhập lại mật khẩu</label>
                            <input required="" class="form-control" type="password" name="mat_khau2" placeholder="Nhập">
                        </div>
                        <div class="mb-3">
                            <button type="submit" class="btn btn-login" name="doipasss">Cập nhật</button>
                        </div>
                    </form>
                    <?php }else{?>
                    <h1 class="title" style="margin-top: 50px;"><?=$row['ten']?></h1>
                    <form method="POST" action="" class="form-cart" id="form-dangnhap">
                        <input type="hidden" value="<?=$_SESSION['token']?>" name="_token" />
                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input type="text" required="" name="email" class=" form-control" placeholder="<?=$d->getTxt(25)?> *">
                        </div>
                        <div class="text-center">
                            <button type="submit" class="btn btn-login" name="quenpass"><?=$d->getTxt(55)?></button>
                        </div>
                    </form>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript" src="https://ajax.aspnetcdn.com/ajax/jquery.validate/1.13.1/jquery.validate.min.js"></script>
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<script>
    <?php if(isset($thongbao_tt) and $thongbao_tt!=""){ ?>
    swal({
        title: '<?=$thongbao_tt ?>',
        text: '<?= $thongbao_content ?>',
        icon: '<?= $thongbao_icon ?>',
        button: false,
        timer: 2000
    }).then((value) => {
        window.location="<?=$thongbao_url?>";
    }); 
    <?php }?> 
$(document).ready(function() {
    //Khi bàn phím được nhấn và thả ra thì sẽ chạy phương thức này
    $("#form-dangnhap").validate({
        rules: { 
            email: {
                required: true,
                email: true
            }
        },
        messages: {
            email: {
                required: "Vui lòng nhập email",
                email: "Vui lòng nhập đúng định dạng mail"
            }
        }
    });
    $("#form-quenpass").validate({
        rules: {
            mat_khau: {
                required: true,
                minlength: 6,
            },
            mat_khau2: {
                required: true,
                minlength: 6,
                equalTo: "#matkhau"
            }
        },
        messages: {
            mat_khau: {
                required: "Vui lòng nhập mật khẩu",
                minlength: 'Mật khẩu tối thiểu 6 ký tự'
            },
            mat_khau2: {
                required: 'Vui lòng xác nhận lại mật khẩu',
                minlength: 'Mật khẩu tối thiểu 6 ký tự',
                equalTo: "Nhập lại mật khẩu không đúng"
            }
        }
    });
});
</script>