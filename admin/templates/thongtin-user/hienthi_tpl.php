<?php
if (isset($_POST['capnhat'])) {
    $data['tai_khoan'] = $d->clean(addslashes($_POST['tai_khoan']));
    $data['user_hash'] = sha1($data['tai_khoan']);
    $data['ho_ten'] = addslashes($_POST['ho_ten']);
    $data['email'] = addslashes($_POST['email']);
    $data['hinh_anh'] = addslashes($_POST['hinh_anh']);
    $data['hien_thi'] = isset($_POST['hien_thi']) ? 1 : 0;

    if (isset($_POST['password']) && !empty($_POST['password']) and $_POST['password'] == $_POST['cfpassword']) {
        // Thay SHA-1 bằng bcrypt
        $data['pass_hash'] = password_hash($d->clean(addslashes($_POST['password'])), PASSWORD_BCRYPT);
    }

    $d->setTable('#_user');
    $d->setWhere('id', $_SESSION['id_user']);
    if ($d->update($data)) {
        // Cập nhật session nếu có thay đổi về tên
        if (isset($data['ho_ten'])) {
            $_SESSION['name'] = $data['ho_ten'];
        }
        $d->redirect("index.php?p=thongtin-user&a=man");
    } else {
        $d->transfer("Lưu dữ liệu bị lỗi", "index.php?p=thongtin-user&a=man");
    }
}
?>
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Thông tin user <small>[ <?= $_GET['a'] == 'add' ? 'Thêm mới' : 'Sửa' ?> ]</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="<?= urladmin ?>"><i class="fa fa-dashboard"></i> Trang chủ</a></li>
            <li><a href="#">Thông tin user</a></li>
            <li class="active">Quản lý user</li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="box box-primary">
            <div class="box-body">
                <form method="post" class=" form-horizontal" action="" enctype="multipart/form-data">
                    <div class="row">
                        <div class="col-sm-8 col-sm-offset-2">
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Hình ảnh:</label>
                                <div class="col-sm-9 form-group m0 hinh_anh">
                                    <span class="box-img2">
                                        <?php if (isset($_GET['id']) and $row['hinh_anh'] != '') { ?>
                                            <img src="../img_data/images/<?php echo $row['hinh_anh'] ?>" id="review_hinh_anh" alt="NO PHOTO" />
                                            <button class="btn btn-xs btn-danger" type="button" onclick="xoa_img('_tintuc','hinh_anh', '<?= $_GET['id'] ?>','')"><i class="fa fa-trash"></i></button>
                                        <?php } else { ?>
                                            <img src="img/no-image.png" style="max-width: 100%;max-height: 100%;object-fit: contain;" id="review_hinh_anh" alt="NO PHOTO" />
                                        <?php } ?>
                                        <input type="hidden" value="<?= $row['hinh_anh'] ?>" name="hinh_anh" id="hinh_anh" class=" form-control">
                                        <a href="filemanager/dialog.php?type=1&field_id=hinh_anh&relative_url=1&multiple=0" class="btn btn-upload2 iframe-btn"> <i class="fa fa-upload" aria-hidden="true"></i>Chọn hình ảnh</a>
                                    </span>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Họ tên:</label>
                                <div class="col-sm-9">
                                    <input class="form-control" type="text" name="ho_ten" value="<?php echo @$items[0]['ho_ten'] ?>" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Email:</label>
                                <div class="col-sm-9">
                                    <input class="form-control" type="text" name="email" value="<?php echo @$items[0]['email'] ?>" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Tên đăng nhập:</label>
                                <div class="col-sm-9">
                                    <input class="form-control" type="text" name="tai_khoan" value="<?php echo @$items[0]['tai_khoan'] ?>" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Mật khẩu:</label>
                                <div class="col-sm-9">
                                    <input class="form-control" type="password" name="password" id="password" value="<?php echo @$items[0]['password'] ?>" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Nhập lại mật khẩu:</label>
                                <div class="col-sm-9">
                                    <input class="form-control" type="password" name="cfpassword" id="cfpassword" value="<?php echo @$items[0]['cfpassword'] ?>" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Tác vụ:</label>
                                <div class="col-sm-9">
                                    <div class="checkbox icheck" style="margin-left: 20px;">
                                        <label>
                                            <input name="hien_thi" <?php if (isset($items[0]['hien_thi'])) {
                                                                        if (@$items[0]['hien_thi'] == 1) echo 'checked="checked"';
                                                                    } else echo 'checked="checked"'; ?> type="checkbox"> Hiển thị
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-sm-9 col-sm-offset-3">
                                    <button type="submit" name="capnhat" class="btn btn-primary"><span class="glyphicon glyphicon-floppy-save"></span> Cập nhật</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </section>
</div>