<?php
session_start();

if(!isset($_SESSION['id_login'])){
    $d->location(URLPATH.$d->getCate(23,'alias').".html");
    exit();
}
if(isset($search['del']) and $search['token'] == $_SESSION['token']){
    $id_luu = (int)$search['del'];
    $d->o_que("delete from #_luu_sp where id = ".$id_luu." ");
    $d->redirect(URLPATH.$com.'.html');
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
<?php if(isset($_SESSION['id_login'])){

}else{ ?>
<?php } ?>
<div class="container">
    <?php if(isset($search['khoahoc'])){ 
    $dh = $d->simple_fetch("SELECT db_dathang_chitiet.id FROM `db_dathang_chitiet` JOIN db_dathang ON db_dathang_chitiet.id_dh = db_dathang.id WHERE db_dathang.trangthai_xuly = 3  and db_dathang.id_thanhvien = '".$_SESSION['id_login']."' and db_dathang_chitiet.id_sp= ".(int)$search['khoahoc']." ");
    
    ?>
    <?php if(count($dh)>0){
    $row = $d->simple_fetch("select * from #_sanpham where id_code ='".(int)$search['khoahoc']."' $where_lang ");   
    ?>
    <div class="detail-info box-login" style="box-shadow: 0px 15px 25px 0px rgba(0, 0, 0, 0.08);border: none;">
        <h1 class="title-detail mb-3"><?=$row['ten']?></h1>
        <?php if($row['video']!=''){ ?>
        <video controls style="width: 100%;height: 700px;">
            <source src="<?=URLPATH?>img_data/images/<?=$row['video']?>" id="review_video"  type="video/mp4">
        </video >
        <?php } ?>
        <div class="chitiet_sp mt-0">
            <?=$row['noi_dung_2']?>
        </div>
    </div>
    <?php 
    $sanpham = $d->o_fet("SELECT db_sanpham.* FROM `db_dathang_chitiet` JOIN db_dathang ON db_dathang_chitiet.id_dh = db_dathang.id JOIN db_sanpham ON db_dathang_chitiet.id_sp = db_sanpham.id_code WHERE db_dathang_chitiet.id_sp <> ".$search['khoahoc']." and db_dathang.trangthai_xuly = 3  and db_dathang.id_thanhvien = '".$_SESSION['id_login']."' ");  
    ?>
    <h2 class="title_home text-center mt-4">Các khóa học khác</h2>
    <div class="row g-4 row-cols-lg-3 row-cols-md-3 row-cols-sm-4 row-cols-1 justify-content-center" id="result">
        <?php foreach ($sanpham as $key => $item) {
            if($item['khuyen_mai']>0){
                $gia_ban = $item['khuyen_mai'];
                $gia_km = $item['gia'];
                $giamgia = (($gia_km-$gia_ban)/$gia_km)*100;
                $gia = '<span><strong>'.numberformat($gia_ban).' <sup>đ</sup> </strong><del>'.  number_format($gia_km).' <sup>đ</sup> </del></span>';
            }else{
                if($item['gia']>0){
                    $gia_ban = $item['gia'];
                    $gia_km=0;
                    $gia = '<strong>'.numberformat($gia_ban).' <sup>đ</sup></strong>';
                }else{
                    $gia_ban = 0;
                    $gia = '<strong>Liên hệ</strong>';
                }
            }    
        ?>
        <div class="col">
            <div class="item_khoahoc" style="box-shadow: 0px 15px 25px 0px rgba(0, 0, 0, 0.08);border: none;">
                <a class="item_khoahoc_img" href="<?=  cre_Link($com)?>?khoahoc=<?=$item['id_code']?>" title="<?=$item['ten']?>">
                    <img src="<?=Img($item['hinh_anh'])?>" alt="<?=$item['ten']?>" />
                </a>
                <h3 class="item_khoahoc_title"><a href="<?=  cre_Link($com)?>?khoahoc=<?=$item['id_code']?>" title="<?=$item['ten']?>"><?=$item['ten']?></a></h3>
                <div class="item_khoahoc_des">
                    <?=catchuoi($item['mo_ta'],250) ?>
                </div>
                <a href="<?=  cre_Link($com)?>?khoahoc=<?=$item['id_code']?>" class="btn btn_link" title="<?=$item['ten']?>"><i class="fa-regular fa-circle-play"></i> Xem khóa học</a>
            </div>
        </div>
        <?php } ?>
    </div>
    <?php }else{ ?>
    <div class="text-center" style="font-size: 18px;font-weight: 500;color: var(--bs-danger);"> 
        Khóa học không tồn tại
    </div>
    <?php } ?>
    <?php }else{ 
    $sanpham = $d->o_fet("SELECT db_sanpham.* FROM `db_dathang_chitiet` JOIN db_dathang ON db_dathang_chitiet.id_dh = db_dathang.id JOIN db_sanpham ON db_dathang_chitiet.id_sp = db_sanpham.id_code WHERE db_dathang.trangthai_xuly = 3  and db_dathang.id_thanhvien = '".$_SESSION['id_login']."' ");    
    ?>
    <div class="row g-4 row-cols-lg-4 row-cols-md-3 row-cols-sm-4 row-cols-1" id="result">
        <?php foreach ($sanpham as $key => $item) {
            if($item['khuyen_mai']>0){
                $gia_ban = $item['khuyen_mai'];
                $gia_km = $item['gia'];
                $giamgia = (($gia_km-$gia_ban)/$gia_km)*100;
                $gia = '<span><strong>'.numberformat($gia_ban).' <sup>đ</sup> </strong><del>'.  number_format($gia_km).' <sup>đ</sup> </del></span>';
            }else{
                if($item['gia']>0){
                    $gia_ban = $item['gia'];
                    $gia_km=0;
                    $gia = '<strong>'.numberformat($gia_ban).' <sup>đ</sup></strong>';
                }else{
                    $gia_ban = 0;
                    $gia = '<strong>Liên hệ</strong>';
                }
            }    
        ?>
        <div class="col">
            <div class="item_khoahoc" style="box-shadow: 0px 15px 25px 0px rgba(0, 0, 0, 0.08);border: none;">
                <a class="item_khoahoc_img" href="<?=  cre_Link($com)?>?khoahoc=<?=$item['id_code']?>" title="<?=$item['ten']?>">
                    <img src="<?=Img($item['hinh_anh'])?>" alt="<?=$item['ten']?>" />
                </a>
                <h3 class="item_khoahoc_title"><a href="<?=  cre_Link($com)?>?khoahoc=<?=$item['id_code']?>" title="<?=$item['ten']?>"><?=$item['ten']?></a></h3>
                <div class="item_khoahoc_des">
                    <?=catchuoi($item['mo_ta'],250) ?>
                </div>
                <a href="<?=  cre_Link($com)?>?khoahoc=<?=$item['id_code']?>" class="btn btn_link" title="<?=$item['ten']?>"><i class="fa-regular fa-circle-play"></i> Xem khóa học</a>
            </div>
        </div>
        <?php } ?>
    </div>
    <?php } ?>
</div>
