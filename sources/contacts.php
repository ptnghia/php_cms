<?php
if (isset($_POST['sub_dangky']) and $_SESSION['token']   == $_POST['_token']) {
    $noidung    =   validate_content($_POST['ghi_chu']);
    $ho_ten     =   validate_content($_POST['ho_ten']);
    $dien_thoai =   validate_content($_POST['dien_thoai']);
    $email      =   validate_content($_POST['email']);
    $tieu_de    =   validate_content($_POST['tieu_de']);
    $dia_chi      =   validate_content($_POST['dia_chi']);
    if (check_phone($dien_thoai) == 0) {
        $data['ho_ten']     =   $ho_ten;
        $data['email']      =   $email;
        $data['sdt']        =   $dien_thoai;
        $data['dia_chi']    =   $dia_chi;
        $data['tieu_de']    =   $tieu_de;
        $data['noi_dung']   =   $noidung;

        $d->reset();
        $d->setTable('#_lienhe');
        if ($d->insert($data)) {
            $thongbao_tt    =   $d->getTxt(78);
            $thongbao_icon  =   'success';
            $thongbao_content =  $d->getTxt(78);
            $thongbao_url       = _URLLANG;
        }
    } else {
        $thongbao_tt    =   $d->getTxt(80);
        $thongbao_icon  =   'error';
        $thongbao_content =  $d->getTxt(81);
        $thongbao_url       = cre_Link($com);
    }
}
?>
<div class="head_page mb-0">
    <div class=" container text-center head_page_content">
        <h1 class="title_page"><?= $row['ten'] ?></h1>
        <div class="d-flex justify-content-center">
            <nav aria-label="breadcrumb">
                <?= $d->breadcrumblist($row['id_code']) ?>
            </nav>
        </div>
    </div>
</div>
<?php
$khoahoc_dk = $d->o_fet("select * from #_sanpham where hien_thi = 1 " . _where_lang . " order by so_thu_tu ASC, id DESC ");
?>
<section class="contact">
    <div class="bando_contact">
        <?= _bando ?>
    </div>
    <div class=" container">

        <div class="row justify-content-end">
            <div class="col-md-6">
                <div class="block-title mt-5">
                    <div class="sub-title"><?= $d->getContent(62, 'link') ?></div>
                    <h2><?= $d->getContent(62, 'ten') ?></h2>
                </div>
                <form action="" method="post" class="contact_form pb-5">
                    <div class="mb-3">
                        <input type="text" class="form-control" placeholder="Họ và tên">
                    </div>
                    <div class="row gx-3">
                        <div class="mb-3 col-sm-12">
                            <input type="text" class="form-control" placeholder="Email">
                        </div>
                        <div class="mb-3 col-sm-12">
                            <input type="text" class="form-control" placeholder="Số điện thoại">
                        </div>
                    </div>

                    <div class="mb-3">
                        <textarea class="form-control" placeholder="Nội dung"></textarea>
                    </div>
                    <button type="submit" class="btn btn-main w-100">Đăng ký</button>
                </form>
            </div>
        </div>
    </div>
</section>