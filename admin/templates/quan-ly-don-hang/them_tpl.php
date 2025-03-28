<?php
if($items[0]['trangthai_xuly']==0){
    $d->o_que("update #_dathang set trangthai_xuly = '1', ngay_capnhat ='".  date('Y-m-d H:i:s',time())."' where id=".(int)$_GET['id']." ");
    $d->redirect("index.php?p=".$_GET['p']."&a=edit&id=".$_GET['id']);
}
if(isset($_POST['capnhat_xuly'])){
    $data['id_dh']              = (int)$_GET['id'];
    $data['trang_thai_xuly']    = (int)$_POST['trangthai'];
    $data['ghi_chu']            = addslashes($_POST['ghichu']);
    $data['ngay_xuly']          = date('Y-m-d H:i:s',time());
    $data['nguoi_xuly']         = addslashes($_SESSION['name']);
    $d->reset();
    $d->setTable('#_dathang_xuly');
    if($d->insert($data)) {
        if($data['trang_thai_xuly']<5){
            $d->o_que("update #_dathang set trangthai_xuly = '".$data['trang_thai_xuly']."', ngay_capnhat ='".  date('Y-m-d H:i:s',time())."' where id=".(int)$_GET['id']." ");
        }else{
            if($data['trang_thai_xuly']==5){
                $tt = 1;
            }else{
                $tt = 0;
            }
            $d->o_que("update #_dathang set trangthai_thanhtoan = '".$tt."', ngay_capnhat ='".  date('Y-m-d H:i:s',time())."' where id=".(int)$_GET['id']." ");
        }
        if((int)$_POST['trangthai']==5){
            
            $thanhvien  = $d->simple_fetch("select * from #_thanhvien where email= '".$items[0]['email']."' or dien_thoai='".$items[0]['dien_thoai']."'");
           
            if(count($thanhvien)>0){
                $row_tong_dh = $d->simple_fetch("SELECT SUM(so_luong*gia_ban) as tong FROM `db_dathang_chitiet` WHERE id_dh =".(int)$_GET['id']." ");
                if(($row_tong_dh['tong'] - $items[0]['so_tien_giam'])>199000){
                    $tyle = _sitekey;
                    $diem = ($row_tong_dh['tong']-$items[0]['so_tien_giam'])*$tyle; 
                    $data_tv['diem'] = $thanhvien['diem'] + $diem;
                    $d->reset();
                    $d->setTable('#_thanhvien');
                    $d->setWhere('id', $thanhvien['id']);
                    $d->update($data_tv);
                    
                    $d->o_que("update #_dathang set diem_cong = '".$diem."' where id=".(int)$_GET['id']." ");
                }
            }
        }
        $d->redirect("index.php?p=".$_GET['p']."&a=edit&id=".$_GET['id']);
    }
}
$don_hang = $d->o_fet("select * from #_dathang_chitiet where id_dh = ".(int)$_GET['id']." order by id desc");
?>
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Chi tiết đơn hàng
      </h1>
      <ol class="breadcrumb">
        <li><a href="<?=urladmin?>"><i class="fa fa-dashboard"></i> Trang chủ</a></li>
        <li><a href="#">Quản trị khách hàng</a></li>
        <li class="active">Đơn hàng</li>
      </ol>
    </section>
    <!-- Main content -->
    <section class="content">
        <div class="box box-primary">
            <div class="box-body">
                
                <div class="row">
                    <div class="col-sm-5">
                        <h3 class="box-title">Đơn hàng: <span style="color: red;"><?=$items[0]['ma_dh']?></span></h3>
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-primary table-hover">
                                <thead>
                                    <tr>
                                        <th  class="text-center">STT</th>
                                        <th>Sản phẩm</th>
                                        <th>Đơn giá</th>
                                        <th>Số lượng</th>
                                        <th>Thành tiền</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    
                                    <?php $tongtien=0;foreach ($don_hang as $key => $value) {
                                    $tongtien = $tongtien+($value['gia_ban']*$value['so_luong'])
                                    ?>
                                    <tr>
                                        <td class="text-center"><?=$key+1?></td>
                                        <td>
                                            <?=$value['ten_sp']?><br>
                                            <span style="color: #dd4b39;"><?=$value['thuoc_tinh']?></span>
                                        </td>
                                        <td class="text-right"><?=  numberformat($value['gia_ban'])?></td>
                                        <td class="text-right"><?=$value['so_luong']?></td>
                                        <td class="text-right"><?=  numberformat($value['so_luong']*$value['gia_ban'])?></td>
                                    </tr>
                                    <?php } ?>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th colspan="4" class="text-right">Tổng tiền </th>
                                        <td class="text-right"><b><?=  numberformat($tongtien)?></b></td>
                                    </tr>
                                    <?php if($items[0]['so_tien_giam']>0){ ?>
                                    <tr>
                                        <th colspan="4" class="text-right">Dùng điểm  (<?=$items[0]['dung_diem']?>)</th>
                                        <td class="text-right"><b>-<?=  numberformat($items[0]['so_tien_giam'])?></b></td>
                                    </tr>
                                    <?php } ?>
                                    <?php if($items[0]['phi_vanchuyen']>0){ ?>
                                    <tr>
                                        <th colspan="4" class="text-right">Phí ship</th>
                                        <td class="text-right"><b><?=  numberformat($items[0]['phi_vanchuyen'])?></b></td>
                                    </tr>
                                    <?php }?>
                                    <tr>
                                        <th colspan="4" class="text-right">Thanh toán </th>
                                        <td class="text-right"><b><?=  numberformat($tongtien-$items[0]['so_tien_giam'])?></b></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <h3 class="box-title">Thông tin khách hàng</h3>
                        <table class="table">
                            <tr>
                                <td style="width: 90px;font-weight: bold;">Họ tên:</td>
                                <td><?=$items[0]['ho_ten']?></td>
                            </tr>
                            <tr>
                                <td style="width: 90px;font-weight: bold;">Điện thoại:</td>
                                <td><?=$items[0]['dien_thoai']?></td>
                            </tr>
                            <tr>
                                <td style="width: 90px;font-weight: bold;">Email:</td>
                                <td><?=$items[0]['email']?></td>
                            </tr>
                            <tr>
                                <td style="width: 90px;font-weight: bold;">Địa chỉ:</td>
                                <td><?=$items[0]['dia_chi']?></td>
                            </tr>
                            <tr>
                                <td style="width: 90px;font-weight: bold;">Ghi chú:</td>
                                <td><?=$items[0]['loi_nhan']?></td>
                            </tr>
                            <tr>
                                <td style="width: 90px;font-weight: bold;">Thanh toán:</td>
                                <td>
                                    <?=$items[0]['thanh_toan']?>
                                    <?php if($items[0]['trangthai_thanhtoan']==1){ ?>
                                    <span class="label label-success">Đã thanh toán</span>
                                    <?php if($items[0]['diem_cong']>0){ ?>
                                    <br>
                                    Tích điểm: +<?=$items[0]['diem_cong']?> điểm
                                    <?php } ?>
                                    <?php }else{?>
                                    <span class="label label-danger">Chưa thanh toán</span>
                                    <?php }?>
                                </td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-sm-3">
                        <h3 class="box-title">Xử lý đơn hàng</h3>
                        <form method="POST" action="">
                            <div class="form-group">
                                <label>Trạng thái đơn hàng</label>
                                <select class="form-control select2" name="trangthai">
                                    <option <?=$items[0]['trangthai_xuly']==0?'selected':''?> value="0">Chưa xem</option>
                                    <option <?=$items[0]['trangthai_xuly']==1?'selected':''?> value="1">Đang xử lý</option>
                                    <option <?=$items[0]['trangthai_xuly']==2?'selected':''?> value="2">Đang giao</option>
                                    <option <?=$items[0]['trangthai_xuly']==3?'selected':''?> value="3">Kích hoạt khóa học</option>
                                    <option <?=$items[0]['trangthai_xuly']==4?'selected':''?> value="4">Hủy đơn hàng</option>
                                    <?php if($items[0]['trangthai_thanhtoan']==0){ ?>
                                    <option value="5">Đã thanh toán</option>
                                    <?php }else{ ?>
                                    <option value="6">Chưa thanh toán</option>
                                    <?php }?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Ghi chú</label>
                                <textarea class="form-control" name="ghichu" rows="3" placeholder="Nhập ghi chú xử lý"></textarea>
                            </div>
                            <div class="text-right">
                                <button class="btn btn-primary" type="submit" name="capnhat_xuly">Cập nhật</button>
                            </div>
                        </form>
                        <?php 
                        $lichsu = $d->o_fet("select * from #_dathang_xuly where id_dh = ".(int)$_GET['id']." order by id desc");
                        ?>
                        <a style="display: inline-block;margin-bottom: 10px;" href="javascript:void(0)" onclick="$('#list-ls').slideToggle();">Theo dõi đơn hàng</a>
                        <div style="background-color: #eeeeee78;padding: 10px; display: none" id="list-ls">
                            <!--p><strong>Lịch sử cập nhật</strong></p-->
                            <?php foreach ($lichsu as $key => $value) {?>
                            <div class="item-lichsu" style="border-bottom: 1px dashed #ccc;padding-bottom: 5px;margin-bottom: 5px;">
                                <p>
                                    <?php if($value['trang_thai_xuly']==1){ ?>
                                    <b>Đang xử lý</b>
                                    <?php }elseif ($value['trang_thai_xuly']==2) {?>
                                    <b>Đang giao</b>
                                    <?php }elseif ($value['trang_thai_xuly']==3) {?>
                                    <b>Kích hoạt khóa học</b>        
                                    <?php }elseif ($value['trang_thai_xuly']==4) {?>
                                    <b>Hủy đơn hàng</b>
                                    <?php }elseif ($value['trang_thai_xuly']==5) {?>
                                    <b>Đã thanh toán</b>
                                    <?php }elseif ($value['trang_thai_xuly']==6) {?>
                                    <b>Chưa thanh toán</b>
                                    <?php }?>
                                    <?php if($value['ghi_chu']!=''){ ?>
                                    - <i><?=$value['ghi_chu']?></i>
                                    <?php }?>
                                </p>
                                <i class="pull-left" style="color: #716e6e;font-size: 13px;">Ngày: <?=date('d/m/Y',strtotime($value['ngay_xuly']))?></i>
                                <i class="pull-right" style="color: #716e6e;font-size: 13px;">User: <?=$value['nguoi_xuly']?></i>
                                <div class="clearfix"></div>
                            </div>                
                            <?php } ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>