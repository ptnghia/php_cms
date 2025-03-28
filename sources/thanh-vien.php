<?php
if(!isset($_SESSION['id_login'])){
    $d->location(URLPATH.$d->getCate(21,'alias').".html");
    exit();
}
$link=explode("?",$_SERVER['REQUEST_URI']);
if($link[1]!=''){
    $vari=explode("&",$link[1]);
    $search=array();
    foreach($vari as $item) {
        $str=explode("=",$item);
        $search["$str[0]"]=$str[1];
    }
}
if(isset($_POST['capnhat_thongtin']) and $_SESSION['token']   == $_POST['_token']){
    token();
    $data['ho_ten']             =   addslashes(replaceHTMLCharacter($_POST['ho_ten']));
    $data['email']              =   addslashes(replaceHTMLCharacter($_POST['email']));
    $data['dien_thoai']         =   addslashes(replaceHTMLCharacter($_POST['dien_thoai']));
    $data['dia_chi']            =   addslashes(replaceHTMLCharacter($_POST['dia_chi']));
    $data['md5_email']          =   addslashes(MD5($_POST['email']));
    if(isset($_POST['mat_khau']) and $_POST['mat_khau']!=''){
        $data['mat_khau']           =   MD5($_POST['mat_khau']);
    }
    
    $targetDirectory = 'img_data/images/'; // Thư mục lưu trữ tệp tải lên
    $targetFile = $targetDirectory . basename($_FILES['imageFile']['name']); // Đường dẫn đến tệp được tải lên
    
    $newFileName = 'avata/'.$user_login['ma_thanhvien'].'_'.time().'.jpg'; // Tên tệp mới
    $newFilePath = $targetDirectory . $newFileName;
    
    // Kiểm tra xem tệp có phải là hình ảnh hợp lệ hay không
    $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));
    $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif','webp'];
    
    if (!empty($_FILES['imageFile']['name']) && in_array($imageFileType, $allowedExtensions)) {
        // Di chuyển tệp đã tải lên vào thư mục đích và đổi tên
        if (move_uploaded_file($_FILES['imageFile']['tmp_name'], $newFilePath)) {
          $data['avata']    =   addslashes($newFileName);
        } else {
          //$data['avata']    ='';
        }
    } else {
        //echo 'Vui lòng chọn một tệp hình ảnh hợp lệ.';
    }
    
    
    $d->reset();
    $d->setTable('#_thanhvien');
    $d->setWhere('id',$user_login['id']);
    if($d->update($data)){
        $thongbao_tt        =   'Cập nhật thành công';
        $thongbao_icon      =   'success';
        $thongbao_content   =   '';
        $thongbao_url       =   URLPATH.$com.".html";
    }
}
?>
<div class="head_page mb-5">
    <div class=" container text-center head_page_content">
        <h1 class="title_page"><?=$row['ten']?></h1>
        <div class="d-flex justify-content-center">
            <nav aria-label="breadcrumb">
                <?=$d->breadcrumblist($row['id_code'])?>
            </nav>
        </div>
    </div>
</div>
<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-lg-6 col-md-7 col-sm-2">
            <div class="box-login" style="box-shadow: 0px 15px 25px 0px rgba(0, 0, 0, 0.08);border: none;">
                <form method="post" action="" class="form-cart" id="form-thongtin" enctype="multipart/form-data">
                    <input type="hidden" value="<?=$_SESSION['token']?>" name="_token" />
                    
                    <div class=" d-flex justify-content-center">
                        <div class="box_avata">
                            <img id="previewImage" src="<?=Img($user_login['avata'])?>" width="100px" height="100px" />
                            <span class="btn-avata">
                                <input id="imageInput" name="imageFile" type="file" accept="" />
                                <i class="fa-solid fa-camera-rotate"></i>
                            </span>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Họ và tên</label>
                        <input required="" class="form-control" name="ho_ten" placeholder="Nhập họ tên"value="<?=$user_login['ho_ten']?>" type="text">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input required="" class="form-control" name="email" placeholder="Nhập email" value="<?=$user_login['email']?>" type="text">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Điện thoại</label>
                        <input class="form-control" name="dien_thoai" placeholder="Nhập điện thoại" value="<?=$user_login['dien_thoai']?>" type="text">
                    </div>
                    <div class="login_footer mb-3" onclick="doimatkhau()">
                        <div class="chek-form">
                            <div class="custome-checkbox" >
                                <input class="form-check-input" style="height: 10px;width: 10px;padding: 6px;" type="checkbox"  name="save" id="exampleCheckbox1" value="1">
                                <label class="form-check-label" for="exampleCheckbox1"><span>Đổi mật khẩu</span></label>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3 an" style=" display: none">
                        <label class="form-label">Mật khẩu</label>
                        <input required="" disabled class="form-control re_makhau" placeholder="Nhập mật khẩu" id="matkhau" name="mat_khau" type="password">
                    </div>
                    <div class="mb-3 an" style=" display: none">
                        <label class="form-label">Xác nhận mật khẩu</label>
                        <input required="" disabled="" class="form-control re_makhau" name="mat_khau2" placeholder="Nhập lại mật khẩu" type="password">
                    </div>
                    <div class="text-center">
                        <button type="submit" class="btn  btn-gioithieu" name="capnhat_thongtin" value="Submit">Cập nhật thông tin</button>
                    </div>
                    
                </form>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript" src="https://ajax.aspnetcdn.com/ajax/jquery.validate/1.13.1/jquery.validate.min.js"></script>
<script>
    var imageInput = document.getElementById('imageInput');
    var previewImage = document.getElementById('previewImage');

    // Xử lý sự kiện onchange khi người dùng chọn tệp hình ảnh
    imageInput.addEventListener('change', function(event) {
      var file = event.target.files[0]; // Lấy tệp hình ảnh từ input

      if (file) {
        var reader = new FileReader(); // Đối tượng đọc tệp

        // Xử lý khi tệp hình ảnh đã được đọc
        reader.onload = function(e) {
          previewImage.src = e.target.result; // Hiển thị hình ảnh xem trước
        };

        reader.readAsDataURL(file); // Đọc tệp hình ảnh dưới dạng URL dữ liệu
      }
    });
    function doimatkhau(){
        var checkbox1 = document.getElementById('exampleCheckbox1');
        if (checkbox1.checked) {
            //alert('đổi pass');
            $(".re_makhau").prop('disabled', false);
            $('.an').show();
        }else{
            //alert('ko đổi pass');
            $(".re_makhau").prop('disabled', true);
            $('.an').hide();
        }
    }
</script>
<script>
$(document).ready(function() {
    //Khi bàn phím được nhấn và thả ra thì sẽ chạy phương thức này
    $("#form-thongtin").validate({
        rules: {
            ho_ten: "required",
            xacnhan: "required", 
            email: {
                required: true,
                email: true
            },
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
            ho_ten: "Vui lòng nhập họ tên",
            xacnhan: "Vui lòng xác nhận đồng ý với các điều khoản",
            email: {
                required: "Vui lòng nhập email",
                email: "Vui lòng nhập đúng định dạng mail"
            },
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
    $("#form-diachi").validate({
        rules: {
            ho_ten          :   "required",
            dien_thoai      :   "required", 
            email: {
                email: true
            },
            id_countries    :   "required",
            id_states       :   "required", 
            id_cities       :   "required", 
            dia_chi         :   "required"
        },
        messages: {
            ho_ten          :   "Vui lòng nhập họ tên",
            dien_thoai      :   "Vui lòng nhập số diện thoại",
            email: {
                email       :   "Vui lòng nhập đúng định dạng mail"
            },
            id_countries    :   "Vui lòng chọn quốc gia",
            id_states       :   "Vui lòng chọn tỉnh / thành phố",
            id_cities       :   "Vui lòng chọn quận / huyện",
            dia_chi         :   "Vui lòng nhập dịa chỉ"
        }
    });
});
</script>