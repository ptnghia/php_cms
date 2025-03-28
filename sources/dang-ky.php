<?php
include '../smtp/index.php';
if(isset($_POST['dang_ky']) and $_SESSION['token']   == $_POST['_token']){
    
    $target_dir = "img_data/files/"; // Thư mục lưu trữ tệp đã tải lên
    $target_file = $target_dir . basename($_FILES["introduce"]["name"]);
    $uploadOk = 1;
    $fileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    // Kiểm tra định dạng tệp
    if ($fileType != "doc" && $fileType != "docx" && $fileType != "pdf") {
        $error = "Only DOC, DOCX, and PDF file uploads are allowed.";
        $uploadOk = 0;
    }elseif ($_FILES["introduce"]["size"] > 5000000) {
        $error = "Maximum file size 5Gb";
        $uploadOk = 0;
    }
    if ($uploadOk == 0) {
        
        $thongbao_tt        =   $d->getTxt(32);
        $thongbao_icon      =   'error';
        $thongbao_content   =  $error;
        $thongbao_url       = cre_Link($com);
    }else {
        $new_name = time().chuoird(3).'.'.$fileType;
        $new_target_file = $target_dir . $new_name;
    
        if (move_uploaded_file($_FILES["introduce"]["tmp_name"], $new_target_file)) {
            $data['introduce']          =   $new_target_file;
           
            $data['company_name']       =   addslashes($_POST['company_name']);
            $data['booth_number']       =   addslashes($_POST['booth_number']);
            $data['country']            =   addslashes($_POST['country']);
            $data['address']            =   addslashes($_POST['address']);
            $data['attn']               =   addslashes($_POST['attn']);
            $data['position']           =   addslashes($_POST['position']);
            $data['email']              =   addslashes($_POST['email']);
            $data['phone']              =   addslashes($_POST['phone']);
            $data['fax']                =   addslashes($_POST['fax']);
            $data['website']            =   addslashes($_POST['website']);
            $data['company_profile_en'] =   addslashes($_POST['company_profile_en']);
            $data['company_profile_vi'] =   addslashes($_POST['company_profile_vi']);
            $data['images']             =   addslashes($_POST['images']);

            $data['md5_email']          =   MD5($_POST['email']);
            $data['ngay_tao']           =   time();
            $data['trang_thai']         =   0;

            

            $d->reset();
            $d->setTable('#_thanhvien');
            if($d->insert($data)) {
                token();
                $thongbao_tt        =   $d->getTxt(33);
                $thongbao_icon      =   'success';
                $thongbao_content   =   $d->getTxt(43).' '.$data['ho_ten'];
                $thongbao_url       =   URLPATH;
            }  

        } else {
            $thongbao_tt    =   $d->getTxt(32);
            $thongbao_icon  =   'error';
            $thongbao_content=  'Error! Could not upload file';
            $thongbao_url       = cre_Link($com);
        }
    }
     
}

?>
<?php if($row['banner']!=''){ ?>
<img src="<?=Img($row['banner'])?>" style="width: 100%;max-height: 400px;object-fit: cover;" alt="<?=$row['ten']?>" />
<?php } ?>
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-lg-8 col-md-10">
            <div class="box-login" style="box-shadow: 0px 15px 25px 0px rgba(0, 0, 0, 0.08);border: none;">
                <h1 class="title text-center"><?=$row['ten']?></h1>
                <form method="post" action class="form-cart needs-validation" novalidate enctype="multipart/form-data">
                    <input type="hidden" value="<?=$_SESSION['token']?>" name="_token" />
                    <div class="mb-3">
                        <label class="form-label" for="company_name"><?=$d->getTxt(9)?> <span class="text-red">*</span></label>
                        <input onchange="check_limit($(this),'company_name')" limit="24" type="text" class="form-control" required id="company_name" name="company_name" placeholder="<?=$d->getTxt(29)?>">
                        <div class="invalid-feedback" id="error_company_name">
                            <?=$d->getTxt(30)?>
                        </div>
                        <i class="d-block mt-1">*<?=$d->getTxt(24)?>: <?=$d->getTxt(10)?></i>
                    </div>
                    <div class="row"> 
                        <div class="mb-3 col-md-6">
                            <label class="form-label" for="booth_number"><?=$d->getTxt(11)?> <span class="text-red">*</span></label>
                            <input type="text" class="form-control" required id="booth_number" placeholder="<?=$d->getTxt(11)?>" name="booth_number">
                            <div class="invalid-feedback"> <?=$d->getTxt(30)?> </div>
                        </div>
                        <?php
                            $string = file_get_contents("country-by-name.json"); 
                            $arr_country   = json_decode($string, true);
                        ?>
                        <div class="mb-3 col-md-6">
                            <label class="form-label" for="country"><?=$d->getTxt(13)?> <span class="text-red">*</span></label>
                            <select name="country" class="form-select" required id="country">
                                <option value="">Choose...</option>
                                <?php foreach ($arr_country as $key => $value) {?>
                                <option value="<?=$value['country']?>"><?=$value['country']?></option>
                                <?php } ?>
                            </select>
                            <div class="invalid-feedback"> <?=$d->getTxt(30)?> </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="address"><?=$d->getTxt(12)?> <span class="text-red">*</span></label>
                        <input type="text" class="form-control" required id="address"  placeholder="<?=$d->getTxt(12)?>" name="address">
                        <div class="invalid-feedback"><?=$d->getTxt(30)?> </div>
                    </div>
                    <div class="row"> 
                        <div class="mb-3 col-md-6">
                            <label class="form-label" for="attn"><?=$d->getTxt(14)?> <span class="text-red">*</span></label>
                            <input type="text" class="form-control" required placeholder="<?=$d->getTxt(14)?>" id="attn" name="attn">
                            <div class="invalid-feedback"> <?=$d->getTxt(30)?> </div>
                        </div>
                        <div class="mb-3 col-md-6">
                            <label class="form-label" for="position"><?=$d->getTxt(15)?> <span class="text-red">*</span></label>
                            <input type="text" class="form-control" placeholder="<?=$d->getTxt(15)?>" required id="position" name="position">
                            <div class="invalid-feedback"> <?=$d->getTxt(30)?> </div>
                        </div>
                    </div>
                    <div class="row"> 
                        <div class="mb-3 col-md-6">
                            <label class="form-label" for="email"><?=$d->getTxt(16)?> <span class="text-red">*</span></label>
                            <input type="email" class="form-control" placeholder="<?=$d->getTxt(16)?>" required id="attn" name="email">
                            <div class="invalid-feedback"> <?=$d->getTxt(30)?> </div>
                        </div>
                        <div class="mb-3 col-md-6">
                            <label class="form-label" for="phone"><?=$d->getTxt(17)?></label>
                            <input type="text" class="form-control" placeholder="<?=$d->getTxt(17)?>" id="phone" name="phone">
                            <div class="invalid-feedback"> <?=$d->getTxt(30)?> </div>
                        </div>
                    </div>
                    <div class="row"> 
                        <div class="mb-3 col-md-6">
                            <label class="form-label" for="fax"><?=$d->getTxt(18)?></label>
                            <input type="text" class="form-control" placeholder="<?=$d->getTxt(18)?>" id="fax" name="fax">
                            <div class="invalid-feedback"> <?=$d->getTxt(30)?> </div>
                        </div>
                        <div class="mb-3 col-md-6">
                            <label class="form-label" for="website"><?=$d->getTxt(19)?></label>
                            <input type="text" class="form-control" id="website" placeholder="<?=$d->getTxt(19)?>" name="website">
                            <div class="invalid-feedback"> <?=$d->getTxt(30)?> </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="company_profile_en"><?=$d->getTxt(20)?> (<?=$d->getTxt(21)?>)<span class="text-red">*</span></label>
                        <textarea rows="3" onchange="check_limit($(this),'company_profile_en')" limit="120" class="form-control" required id="company_profile_en" name="company_profile_en" placeholder="<?=$d->getTxt(29)?>"></textarea>
                        <div class="invalid-feedback" id="error_company_profile_en">
                            <?=$d->getTxt(30)?>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="company_profile_vi"><?=$d->getTxt(20)?> (<?=$d->getTxt(22)?>)<span class="text-red">*</span></label>
                        <textarea rows="3" onchange="check_limit($(this),'company_profile_vi')" limit="120" class="form-control" required id="company_profile_vi" name="company_profile_vi" placeholder="<?=$d->getTxt(29)?>"></textarea>
                        <div class="invalid-feedback" id="error_company_profile_vi">
                            <?=$d->getTxt(30)?>
                        </div>
                        <i class="d-block mt-1">*<?=$d->getTxt(24)?>: <?=$d->getTxt(25)?></i>
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="introduce"><?=$d->getTxt(26)?> <span class="text-red">*</span></label>
                        <input type="file" accept=".doc, .docx, .pdf" class="form-control" required id="introduce" name="introduce">
                        <div class="invalid-feedback">
                            <?=$d->getTxt(30)?>
                        </div>
                        <i class="d-block mt-1">*<?=$d->getTxt(24)?>: <?=$d->getTxt(27)?></i>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label" for="images"><?=$d->getTxt(28)?> <span class="text-red">*</span></label>
                        <input type="text" class="form-control" required id="images"  placeholder="https://drive.google.com/..." name="images">
                        <div class="invalid-feedback"><?=$d->getTxt(30)?> </div>
                    </div>
                    
                    <div class="mb-3 text-center ">
                        <button type="submit" class="btn btn-main btn-primary w-50" name="dang_ky"><?=$d->getTxt(31)?></button>
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
    $("#form-dangky").validate({
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
                equalTo: "#matkhau"
            }
        },
        messages: {
            ho_ten: "<?=$d->getTxt(46)?>",
            email: {
                required: "<?=$d->getTxt(47)?>",
                email: "<?=$d->getTxt(48)?>"
            },
            mat_khau: {
                required: "<?=$d->getTxt(49)?>",
                minlength: '<?=$d->getTxt(50)?>'
            },
            mat_khau2: {
                required: '<?=$d->getTxt(51)?>',
                equalTo: "<?=$d->getTxt(52)?>"
            }
        }
    });
});
</script>