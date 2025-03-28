<?php
$row = $d->getCate(55);
$link=explode("?",$_SERVER['REQUEST_URI']);
$vari=explode("&",$link[1]);
$search=array();
foreach($vari as $item) {
    $str=explode("=",$item);
    $search["$str[0]"]=$str[1];
}
if(isset($search['id']) and $search['id']!='' and isset($_SESSION['madh']) and  $_SESSION['madh'] == $search['id']){
    $madonhang = validate_content($search['id']);
    $donhang = $d->simple_fetch("select * from #_dathang where ma_dh='$madonhang' ");
    $donhang_ct = $d->o_fet("select * from #_dathang_chitiet where ma_dh='$madonhang' ");
    if(isset($_POST['xacnhan'])){
        $data['tinhtrang_donhang']=1;
        $d->reset();
        $d->setTable('#_dathang');
        $d->setWhere('id',$donhang['id']);
        if($d->update($data)){
            $d->location(_URLLANG.$com.'.html?id='.$madonhang);
        }
    }
    if(isset($_POST['huy'])){
        $d->reset();
        $d->setTable('#_dathang');
        $d->setWhere('id',$donhang['id']);
        if($d->delete()){
            $d->location(_URLLANG.$d->getCate(55)['alias'].'.html');
        }
    }
}else{
     $d->location(URLPATH."404.html");
}
?>
<div class="bread_home" style="background-image: url('img_data/images/<?=$row['banner']?>')">
    <div class="container">
         <h1 class="title"><span><?=$row['ten']?></span></h1>
        <?=$d->breadcrumblist($row['id_code'])?>
    </div>
</div>
<div class="container">
    <div class="row">
        <div class="col-md-6 col-sm-8 col-md-offset-3 col-sm-offset-2">
            <?php if($donhang['tinhtrang_donhang']==1){?>
            <div class="alert alert-success thongbaodathang" role="alert">
                <img src="templates/images/icon-check.png" /> <span><?=$d->gettxt(54)?></span>
            </div>
             <?php } ?>
            <div class="br-dathang"  style="background-color: #f5f5f5;border-radius: 10px;border: 1px solid #eee;">
                <div class="title-giohang"><?=$d->gettxt(48)?> - <?=$madonhang?></div>
                <table class="table table-dathang-tc">
                    <?php 
                    $thanhtien = 0;
                    foreach ($donhang_ct as $key => $value) {
                        $product = $d->getProduct($value['id_sp'],'*');
                        $thanhtien = $thanhtien+($value['so_luong']*$value['gia']);
                    ?>
                    <tr>
                        <td class=" text-center">
                            <img src="<?=URLPATH?>img_data/images/<?=$product['hinh_anh']?>" alt="<?=$product['ten']?>" />
                        </td>
                        <td>
                            <p style="font-size: 14px;text-transform: uppercase;"><b><?=$product['ten']?></b></p>
                            <div class="ct-giohang">
                                <span class="pull-left"><span><?=$d->gettxt(39)?>:</span> <b><?=$value['so_luong']?></b></span>
                                <span class="pull-right"><span><?=$d->gettxt(40)?>:</span> <b><?=  numberformat($value['so_luong']*$value['gia'])?> <sup>đ</sup></b></span>
                                <div class=" clearfix"></div>
                            </div>
                        </td>
                    </tr>
                    <?php } ?>
                    <tr style="font-weight: 600;font-size: 15px;">
                        <td><?=$d->gettxt(49)?>:</td>
                        <td class="text-right"><?=  numberformat($thanhtien)?> <sup>đ</sup></td>
                    </tr>
                </table>
                <h4 style="background-color: #fff;padding: 10px;font-size: 14px;text-transform: uppercase;"><?=$d->gettxt(50)?></h4>
                <table class="table table-thongtin">
                    <tr>
                        <td><b><?=$d->gettxt(5)?>:</b></td>
                        <td><?=$donhang['ho_ten']?></td>
                    </tr>
                    <tr>
                        <td><b><?=$d->gettxt(6)?>:</b></td>
                        <td><?=$donhang['dien_thoai']?></td>
                    </tr>
                    <tr>
                        <td><b>Email:</b></td>
                        <td><?=$donhang['email']?></td>
                    </tr>
                    <tr>
                        <td><b><?=$d->gettxt(7)?>:</b></td>
                        <td><?=$donhang['dia_chi']?></td>
                    </tr>
                    <tr>
                        <td><b><?=$d->gettxt(51)?>:</b></td>
                        <td><?=$donhang['thanh_toan']?></td>
                    </tr>
                    <tr>
                        <td><b><?=$d->gettxt(33)?>:</b></td>
                        <td><?=$donhang['loi_nhan']?></td>
                    </tr>
                </table>
                <?php if($donhang['tinhtrang_donhang']==0){ ?>
                <form method="POST" action="" class="text-center"> 
                    <button class="btn btn-xacnhan" name="xacnhan"><?=$d->gettxt(52)?></button>
                    <button class="btn btn-huy" name="huy"><?=$d->gettxt(53)?></button>
                </form>
                <?php } ?>
            </div>
        </div> 
    </div>
</div>