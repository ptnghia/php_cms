<?php
    if(isset($_POST['cap_nhat_thanhvien'])){
        $id = (int)$_POST['id'];
        if($_POST['mat_khau']!=''){
            $matkhau = MD5($_POST['mat_khau']);
            $d->o_que("update #_thanhvien set mat_khau = '".$matkhau."' where id = $id");
        }
        if(isset($_POST['trang_thai'])){
           $d->o_que("update #_thanhvien set trang_thai_duyet = 1 where id = $id");
        }
        $d->alert("Cập nhật dữ liệu thành công!");
        $d->redirect("index.php?p=".$_GET['p']."&a=man");    
    } 
?>
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Quản lý thành viên
      </h1>
      <ol class="breadcrumb">
        <li><a href="<?=urladmin?>"><i class="fa fa-dashboard"></i> Trang chủ</a></li>
        <li><a href="#">Quản lý khách hàng</a></li>
        <li class="active">Thành viên</li>
      </ol>
    </section>
    <!-- Main content -->
    <section class="content">
        <div class="box box-primary">
            <div class="box-header with-border">
                <div class="pull-right">
                    <button data-toggle="modal" data-target="#AddModal" class="btn btn-primary pull-right"><i class="glyphicon glyphicon-plus"></i> Thêm mới</button>
                </div>
                <div class="clearfix"></div>
            </div> 
            <div class="box-body">
                <table class="table table-bordered table-striped table-primary table-hover" id="dataTable1">
                    <thead>
                        <tr>
                            <th style="width: 70px">STT</th>
                            <th>Company Name</th>
                            <th>Booth Number</th>
                            <th>Address</th>
                            <th>Country</th>
                            <th>Contact</th>
                            <th>Position</th>
                            <th>Fax</th>
                            <th>Company Profile (English)</th>
                            <th>Website (Vietnamese)</th>
                            <th>Introduction</th>
                            <th>Link Images</th>
                            <th style="width: 150px">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($items as $key => $value) {
                        ?>
                        <tr>
                            <td class="text-center"><?=$key+1?><br>
                                <a data-toggle="modal" class="btn btn-xs btn-info" onclick="get_Thongtinthanhvien(<?=$value['id']?>)" data-target="#myModal" class="btn btn-xs btn-info" href="#"  title="Sửa">Chi tiết</a>
                                <a style="padding: 3px 5px 5px;font-size: 11px; margin-top: 3px" href="index.php?p=thanh-vien&a=edit&id=<?=$value['id']?>&form=42" class="btn btn-sm btn-warning" title="Sửa">order form</a>
                            </td>
                            <td>
                                <div style="width: 150px;overflow: hidden;text-overflow: ellipsis;display: -webkit-box;-webkit-line-clamp: 3;-webkit-box-orient: vertical;"><?=$value['company_name']?></div>
                                <a href="<?=$value['website']?>" target="_blank">Xem website</a>
                            </td>
                            <td class="text-center">
                                <?=$value['booth_number']?>
                                <div style="margin-bottom: 4px">
                                    <?php if($value['trang_thai']=='0'){ ?>
                                    <span class="btn btn-xs btn-warning">Chưa xem</span>
                                    <?php }elseif($value['trang_thai']=='1'){?>
                                    <span class="btn btn-xs btn-primary">Đã xem</span>
                                    <?php }else{ ?>
                                    <span class="btn btn-xs btn-success">Đã duyệt</span>
                                    <?php } ?>
                                </div>
                            </td>
                            <td><div style="width: 200px;overflow: hidden;text-overflow: ellipsis;display: -webkit-box;-webkit-line-clamp: 3;-webkit-box-orient: vertical;"><?=$value['address']?></div></td>
                            <td><?=$value['country']?></td>
                            <td>
                                <div style="width: 150px;overflow: hidden;text-overflow: ellipsis;display: -webkit-box;-webkit-line-clamp: 2;-webkit-box-orient: vertical;"><?=$value['attn']?></div>
                                <b>Email:</b><?=$value['email']?><br>
                                <b>Phone: </b><?=$value['phone']?>
                            </td>
                            <td><?=$value['position']?></td>
                            <td><?=$value['fax']?></td>
                            <td><div style="width: 300px;overflow: hidden;text-overflow: ellipsis;display: -webkit-box;-webkit-line-clamp: 3;-webkit-box-orient: vertical;">
                                    <?=$value['company_profile_en']?>
                                </div></td>
                            <td><div style="width: 300px;overflow: hidden;text-overflow: ellipsis;display: -webkit-box;-webkit-line-clamp: 3;-webkit-box-orient: vertical;"><?=$value['company_profile_vi']?></div></td>
                            <td class="text-center"><a href="<?=URLPATH.$value['introduce']?>" target="_blank" >Xem file</a></td>
                            <td class="text-center"><a href="<?=$value['images']?>" target="_blank" >Mở link</a></td>
                            <td class="text-center">
                                
                                
                                <?php if($d->checkPermission_dele($id_module)==1){ ?>
                                <a href="index.php?p=<?=$_GET['p']?>&a=delete&id=<?=$value['id']?>" onClick="if(!confirm('Xác nhận xóa?')) return false;" class="btn btn-xs btn-danger" title="Xóa">Xóa</a>
                                <?php }?>
                            </td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </section>
</div>
<link rel="stylesheet" href="templates/plugin/datatables.net-bs/css/dataTables.bootstrap.min.css">
<!-- DataTables -->
<script src="public/plugin/datatables.net/js/jquery.dataTables.min.js"></script>
<script src="public/plugin/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>
<!-- Modal -->

<?php 
if(isset($_POST['add_khachhang'])){
    $data['email']              =   addslashes($_POST['email']);
    $data['md5_email']          =   MD5($_POST['email']);
    $data['mat_khau']           =   MD5($_POST['password']);
    $data['trang_thai']         =   2;
    
    $d->reset();
    $d->setTable('#_thanhvien');
    if($d->insert($data)) {
        $d->alert("Cập nhật dữ liệu thành công!");
        $d->redirect("index.php?p=".$_GET['p']."&a=man");    
    } 
}
function chuoird_new($length) {
    $str ='';
    $chars = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789qưertyuioplkjhgfdsazxcvbnm!@#$%&*()_+-|}{[]";
    $size = strlen( $chars );
    for( $i = 0; $i < $length; $i++ ) {
    $str .= $chars[ rand( 0, $size - 1 ) ];
    }
    return $str;
}
?>
<div class="modal fade" id="AddModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title" id="myModalLabel">Tạo Tài khoản khách hàng</h4>
        </div>
        <form method="POST" action="">
            <div class="modal-body">
                <div class="form-group">
                    <label for="exampleInputEmail1">Email address</label>
                    <input type="email" class="form-control" name="email" id="exampleInputEmail1" placeholder="Email">
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="text" class="form-control" value="<?=chuoird_new(10)?>" name="password" id="password" placeholder="Nhập mật khẩu">
                </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
              <button type="submit" name="add_khachhang" class="btn btn-primary">Save changes</button>
            </div>
        </form>
        
    </div>
  </div>
</div>

<script>
    $('#dataTable1').DataTable({
        'autoWidth'   : false,
        'searching'   : true,
        'lengthChange': true,
        "iDisplayLength": 25
    });
</script>
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-body" id="body-lienhe">
                
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Đóng</button>
            </div>
        </div>
    </div>
</div>
<script>
    function get_Thongtinthanhvien(id){
        $.ajax({
            url : "sources/ajax.php",
            type : "post",
            dataType:"text",
            data : {
                 do : 'get_Thongtinthanhvien',
                 id :   id
            },
            success : function (result){
                $('#body-lienhe').html(result);
            }
        });
    }
</script>