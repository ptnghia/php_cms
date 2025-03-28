<?php if(isset($_GET['search'])){
    $link_search = '&search='.$_GET['search'].'&key='.$_GET['key'];
}else{
    $link_search ='';
} ?>
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Sản phẩm
      </h1>
      <ol class="breadcrumb">
        <li><a href="<?=urladmin?>"><i class="fa fa-dashboard"></i> Trang chủ</a></li>
        <li><a href="#">Quản trị danh mục</a></li>
        <li class="active">Sản phẩm</li>
      </ol>
    </section>
    <!-- Main content -->
    <section class="content">
        <div class="box box-primary">
            <div class="box-header with-border">
                <div class="btn-group">
                    <select id="action" name="action" onclick="form_submit(this)" class="form-control">
                        <option selected="">Tác vụ</option>
                        <option value="delete">Xóa</option>
                    </select>
                </div>
                <div class="btn-group">
                    <input type="text" value="<?=$_GET['search']!='loai'?$_GET['key']:''?>" id="key-search" class=" form-control" placeholder="Nhập nội dung cần tìm" />
                </div>
                <div class="btn-group">
                    <select id="search-input" class="form-control" data-p="<?=$_GET['p']?>">
                        <option value="">Tìm theo...</option>
                        <option <?=$_GET['search']=="ten"?'selected':''?> value="ten">Tên sản phẩm</option>
                        <option <?=$_GET['search']=="id_code"?'selected':''?> value="id_code">ID sản phẩm</option>
                        <option <?=$_GET['search']=="ma_sp"?'selected':''?> value="ma_sp">Mã sản phẩm</option>
                    </select>
                </div>
                <div class="btn-group">
                    <select id="search-cate" class="form-control" data-p="<?=$_GET['p']?>">
                        <option value="">Tìm theo danh mục</option>
                        <?=$loai?>
                    </select>
                </div>
                <?php if($d->checkPermission_edit($id_module)==1){ ?>
                <div class="pull-right">
                    <a href="index.php?p=<?=$_GET['p']?>&a=add" class="btn btn-primary pull-right"><i class="glyphicon glyphicon-plus"></i> Thêm mới</a>
                </div>
                <?php }?>
                <div class="clearfix"></div>
            </div>  
            <form id="form" method="post" action="index.php?p=<?=$_GET['p']?>&a=delete_all" role="form">
                <div class="box-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-primary table-hover">
                            <thead>
                                <tr>
                                    <th style="width:50px" class="text-center"><input class="chk_box checkall" type="checkbox" name="chk" value="0"  id="check_all"></th>
                                    <th style="width:70px"  class="text-center">STT</th>
                                    <th>Danh mục</th>
                                    <th>Sản phẩm</th>
                                    <th>Hình ảnh</th>
                                    <!--th>SL còn</th>
                                    <th style="width:100px">Thêm SL</th-->
                                    <!--th class="text-center" style="width:70px">Sp sale</th>
                                    <th class="text-center" style="width:70px">SP Top</th>
                                    <th class="text-center" style="width:70px">Mới</th-->
                                    <th class="text-center" style="width:120px">Phân loại</th>
                                    <th class="text-center" style="width:70px">Hiển thị</th>
                                    <th class="text-center" style="width:120px">Trạng thái</th>
                                    <th class="text-center" style="width:120px">Tác vụ</th>
                                </tr>
                            </thead>
                            <tbody id="data-ajax">
                                <?php $count=count($items); 
                                    for($i=0; $i<$count; $i++){ 
                                    $thuoctinh_chitiet = $d->o_fet("SELECT * FROM `db_sanpham_chitiet` WHERE id_sp = ".$items[$i]['id_code']." and id_loai = 0 ");    
                                    ?>
                                <tr>
                                    <td class="text-center">
                                        <?php if($d->checkPermission_dele($id_module)==1){ ?>
                                        <input class="chk_box" style="margin-top: 0;" type="checkbox" name="chk_child[]" value="<?=$items[$i]['id_code']?>">
                                        <?php }?>
                                    </td>
                                    <td class="text-center">
                                        <?php if($d->checkPermission_edit($id_module)==1){ ?>
                                        <input type="number" value="<?=$items[$i]['so_thu_tu']?>" class="a_stt" data-table="#_sanpham" data-col="so_thu_tu" data-id="<?=$items[$i]['id_code']?>" />
                                        <?php }else{?>
                                        <span class="label label-primary"><?=$items[$i]['so_thu_tu']?></span>
                                        <?php }?>
                                    </td>
                                    <td>
                                        <?php 
                                        if($items[$i]['id_loai']>0){
                                            $query = $d->simple_fetch("select * from #_category where id_code={$items[$i]['id_loai']} and lang = 'vi'");					
                                            //$str = ""; for($k=0;$k<$query['level'];$k++) { $str.="= "; }	
                                            echo $query['ten'] ;
                                        }
                                            ?>
                                    </td>
                                    <td style="text-align:left">
                                        <p><a href="index.php?p=<?=$_GET['p']?>&a=edit&id=<?=$items[$i]['id_code']?><?=$link_search?>"><?=$items[$i]['ten']?></a></p>
                                        
                                        <span style="font-size: 13px;color: #444;"><i class="fa fa-calendar"></i> <?=date('d/m/Y', $items[$i]['ngay_dang']) ?></span>
                                    </td>
                                    <td  class="text-center">
                                        <?=($items[$i]['hinh_anh'] <> '')?"<img src='../img_data/images/".$items[$i]['hinh_anh']."' style='height: 80px;'>":""; ?>
                                    </td>
                                    <!--td class="text-center sl_<?=$items[$i]['id_code']?>" >
                                        <?php if(count($thuoctinh_chitiet)==0){ ?>
                                            <?=  numberformat($items[$i]['so_luong'])?>
                                        <?php }else{ ?>
                                            <?=  number_format($sl)?>
                                        <?php } ?>
                                    </td>
                                    <td>
                                        <?php if(count($thuoctinh_chitiet)==0){ ?>
                                        <input type="number" value="0" onchange="add_sl($(this),<?=$items[$i]['id_code']?>)" style="width: 100px;" class="form-control" />
                                        <?php } ?>
                                    </td-->
                                    <td class="text-left" style="line-height: 10px;">
                                        <label style="font-size: 13px;">
                                            <input class="chk_box" <?php if($d->checkPermission_edit($id_module)==0){ ?>disabled<?php }?> type="checkbox" onclick="on_check(this,'#_sanpham','sp_sale','<?=$items[$i]['id_code']?>')" <?php if($items[$i]['sp_sale'] == 1) echo 'checked="checked"'; ?>> SP Khuyến mãi
                                        </label><br>
                                        <!--label style="font-size: 13px;">
                                            <input class="chk_box" <?php if($d->checkPermission_edit($id_module)==0){ ?>disabled<?php }?> type="checkbox" onclick="on_check(this,'#_sanpham','sp_top','<?=$items[$i]['id_code']?>')" <?php if($items[$i]['sp_top'] == 1) echo 'checked="checked"'; ?>> SP Top
                                        </label><br-->
                                        <label style="font-size: 13px;">
                                            <input class="chk_box" <?php if($d->checkPermission_edit($id_module)==0){ ?>disabled<?php }?> type="checkbox" onclick="on_check(this,'#_sanpham','sp_moi','<?=$items[$i]['id_code']?>')" <?php if($items[$i]['sp_moi'] == 1) echo 'checked="checked"'; ?>> SP Mới
                                        </label><br>
                                        <label style="font-size: 13px;">
                                            <input class="chk_box" <?php if($d->checkPermission_edit($id_module)==0){ ?>disabled<?php }?> type="checkbox" onclick="on_check(this,'#_sanpham','tieu_bieu','<?=$items[$i]['id_code']?>')" <?php if($items[$i]['tieu_bieu'] == 1) echo 'checked="checked"'; ?>> Bán chạy
                                        </label>
                                        
                                    </td>
                                    
                                    <td  class="text-center">
                                        <input class="chk_box" <?php if($d->checkPermission_edit($id_module)==0){ ?>disabled<?php }?> type="checkbox" onclick="on_check(this,'#_sanpham','hien_thi','<?=$items[$i]['id_code']?>')" <?php if($items[$i]['hien_thi'] == 1) echo 'checked="checked"'; ?>>
                                    </td>
                                    <td class="text-left">
                                        <?php if($items[$i]['nofollow']==1){ ?>
                                        <span class="text-danger"><i style="font-size: 12px;" class="fa fa-circle-thin" aria-hidden="true"></i> No-follow</span>
                                        <?php }else{?>
                                        <span class="text-success"><i style="font-size: 12px;" class="fa fa-circle" aria-hidden="true"></i> Do-follow</span>
                                        <?php }?><br>
                                        <?php if($items[$i]['noindex']==1){ ?>
                                        <span class="text-danger"><i style="font-size: 12px;" class="fa fa-circle-thin" aria-hidden="true"></i> Noindex</span>
                                        <?php }else{?>
                                        <span class="text-success"><i style="font-size: 12px;" class="fa fa-circle" aria-hidden="true"></i> Index</span>
                                        <?php }?>
                                    </td>
                                    <td class="text-center">
                                        <a style="padding: 2px 5px 1px;font-size: 11px;" href="index.php?p=san-pham&a=coppy&id=<?=$items[$i]['id_code']?><?=$link_search?>" class="btn btn-sm btn-info" title="Sửa">Sao chép</a>
                                        <a style="padding: 3px 5px 5px;font-size: 11px;" href="index.php?p=<?=$_GET['p']?>&a=edit&id=<?=$items[$i]['id_code']?><?=$link_search?>" class="btn btn-sm btn-warning" title="Sửa"><i class="glyphicon glyphicon-edit"></i></a>
                                        <?php if($d->checkPermission_dele($id_module)==1){ ?>
                                        <a style="padding: 3px 5px 5px;font-size: 11px;" href="index.php?p=<?=$_GET['p']?>&a=delete&id=<?=$items[$i]['id_code']?><?=$link_search?>" onClick="if(!confirm('Xác nhận xóa?')) return false;" class="bnt btn-sm btn-danger" title="Xóa"><i class="glyphicon glyphicon-remove"></i></a>
                                        <?php }?>
                                    </td>
                                </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                    <?php if($total_page>1){ ?>
                    <div class="text-center">
                        <ul id="pagination-ajax" class="pagination-sm" style="margin: 0;"></ul>
                    </div>
                    <?php }?>
                </div>
            </form>
        </div>
        <div class="box box-primary  collapsed-box" >
            <?php
            if(isset($_POST['luucauhinh'])){
                $data['num_post']           = addslashes($_POST['num_post']);
                $data['heading_cate']       = addslashes($_POST['heading_cate']);
                $data['heading_ct']         = addslashes($_POST['heading_ct']);
                $data['heading_home']       = addslashes($_POST['heading_home']);
                $data['heading_danhmuc_1']       = addslashes($_POST['heading_danhmuc_1']);
                $data['heading_danhmuc_2']       = addslashes($_POST['heading_danhmuc_2']);
                $data['heading_danhmuc']       = addslashes($_POST['heading_danhmuc']);
                $data['video']              = addslashes($_POST['video']);
                $data['thong_so']           = addslashes($_POST['thong_so']);
                $data['file']               = addslashes($_POST['file']);
                $data['danh_gia']           = addslashes($_POST['danh_gia']);
                $data['binh_luan']          = addslashes($_POST['binh_luan']);
                $data['ma_sp']              = addslashes($_POST['ma_sp']);
                $data['gia']                = addslashes($_POST['gia']);
                $data['khuyen_mai']         = addslashes($_POST['khuyen_mai']);
                $data['thumb1_w']           = addslashes($_POST['thumb1_w']);
                $data['thumb1_h']           = addslashes($_POST['thumb1_h']);
                $data['thumb2_w']           = addslashes($_POST['thumb2_w']);
                $data['thumb2_h']           = addslashes($_POST['thumb2_h']);
                $data['thumb3_w']           = addslashes($_POST['thumb3_w']);
                $data['thumb3_h']           = addslashes($_POST['thumb3_h']);
                $data['option_resize']   = addslashes($_POST['option_resize']);
                $str_json = json_encode($data);
                $d->o_que("update #_module set setting= '$str_json' where id= 3 ");
                $d->redirect("index.php?p=".$_GET['p']."&a=man");
            }
            
            ?>
            <div class="box-header with-border ">
                <h3 class="box-title">Cấu hình trang sản phẩm</h3>
                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i>
                    </button>
                    <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                </div>
            </div>
            <!-- /.box-header -->
            <div class="box-body"  style="display: none">
                <form method="POST" action="" class="form-horizontal" >
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group m-5">
                                <label class="col-sm-5 p5 control-label" style="font-weight: 500;">Số sản phẩm trên trang:</label>
                                <div class="col-sm-4 p5">
                                    <input type="number" name="num_post" value="<?=$arrr_setting['num_post']?>" class="form-control" placeholder="">
                                </div>
                            </div>
                            <div class="form-group m-5">
                                <label class="col-sm-5 p5 control-label" style="font-weight: 500;">Tên sản phẩm trang danh mục:</label>
                                <div class="col-sm-4 p5">
                                    <select class=" form-control" name="heading_cate">
                                        <option <?=$arrr_setting['heading_cate']=='div'?'selected':''?> value="div">Bình thường (DIV)</option>
                                        <option <?=$arrr_setting['heading_cate']=='h1'?'selected':''?> value="h1">Heading 1</option>
                                        <option <?=$arrr_setting['heading_cate']=='h2'?'selected':''?> value="h2">Heading 2</option>
                                        <option <?=$arrr_setting['heading_cate']=='h3'?'selected':''?> value="h3">Heading 3</option>
                                        <option <?=$arrr_setting['heading_cate']=='h4'?'selected':''?> value="h4">Heading 4</option>
                                        <option <?=$arrr_setting['heading_cate']=='h5'?'selected':''?> value="h5">Heading 5</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group m-5">
                                <label class="col-sm-5 p5 control-label" style="font-weight: 500;">Tên sản phẩm trang chi tiết:</label>
                                <div class="col-sm-4 p5">
                                    <select class=" form-control" name="heading_ct">
                                        <option <?=$arrr_setting['heading_ct']=='div'?'selected':''?> value="div">Bình thường (DIV)</option>
                                        <option <?=$arrr_setting['heading_ct']=='h1'?'selected':''?> value="h1">Heading 1</option>
                                        <option <?=$arrr_setting['heading_ct']=='h2'?'selected':''?> value="h2">Heading 2</option>
                                        <option <?=$arrr_setting['heading_ct']=='h3'?'selected':''?> value="h3">Heading 3</option>
                                        <option <?=$arrr_setting['heading_ct']=='h4'?'selected':''?> value="h4">Heading 4</option>
                                        <option <?=$arrr_setting['heading_ct']=='h5'?'selected':''?> value="h5">Heading 5</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group m-5">
                                <label class="col-sm-5 p5 control-label" style="font-weight: 500;">Tên danh mục trang chủ:</label>
                                <div class="col-sm-4 p5">
                                    <select class=" form-control" name="heading_home">
                                        <option <?=$arrr_setting['heading_home']=='div'?'selected':''?> value="div">Bình thường (DIV)</option>
                                        <option <?=$arrr_setting['heading_home']=='h1'?'selected':''?> value="h1">Heading 1</option>
                                        <option <?=$arrr_setting['heading_home']=='h2'?'selected':''?> value="h2">Heading 2</option>
                                        <option <?=$arrr_setting['heading_home']=='h3'?'selected':''?> value="h3">Heading 3</option>
                                        <option <?=$arrr_setting['heading_home']=='h4'?'selected':''?> value="h4">Heading 4</option>
                                        <option <?=$arrr_setting['heading_home']=='h5'?'selected':''?> value="h5">Heading 5</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group m-5">
                                <label class="col-sm-5 p5 control-label" style="font-weight: 500;">Tiêu đề cấp 1 trang danh mục:</label>
                                <div class="col-sm-4 p5">
                                    <select class=" form-control" name="heading_danhmuc_1">
                                        <option <?=$arrr_setting['heading_danhmuc_1']=='div'?'selected':''?> value="div">Bình thường (DIV)</option>
                                        <option <?=$arrr_setting['heading_danhmuc_1']=='h1'?'selected':''?> value="h1">Heading 1</option>
                                        <option <?=$arrr_setting['heading_danhmuc_1']=='h2'?'selected':''?> value="h2">Heading 2</option>
                                        <option <?=$arrr_setting['heading_danhmuc_1']=='h3'?'selected':''?> value="h3">Heading 3</option>
                                        <option <?=$arrr_setting['heading_danhmuc_1']=='h4'?'selected':''?> value="h4">Heading 4</option>
                                        <option <?=$arrr_setting['heading_danhmuc_1']=='h5'?'selected':''?> value="h5">Heading 5</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group m-5">
                                <label class="col-sm-5 p5 control-label" style="font-weight: 500;">Tiêu đề cấp 2 trang danh mục:</label>
                                <div class="col-sm-4 p5">
                                    <select class=" form-control" name="heading_danhmuc_2">
                                        <option <?=$arrr_setting['heading_danhmuc_2']=='div'?'selected':''?> value="div">Bình thường (DIV)</option>
                                        <option <?=$arrr_setting['heading_danhmuc_2']=='h1'?'selected':''?> value="h1">Heading 1</option>
                                        <option <?=$arrr_setting['heading_danhmuc_2']=='h2'?'selected':''?> value="h2">Heading 2</option>
                                        <option <?=$arrr_setting['heading_danhmuc_2']=='h3'?'selected':''?> value="h3">Heading 3</option>
                                        <option <?=$arrr_setting['heading_danhmuc_2']=='h4'?'selected':''?> value="h4">Heading 4</option>
                                        <option <?=$arrr_setting['heading_danhmuc_2']=='h5'?'selected':''?> value="h5">Heading 5</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group m-5">
                                <label class="col-sm-5 p5 control-label" style="font-weight: 500;">Tiêu đề trang danh mục:</label>
                                <div class="col-sm-4 p5">
                                    <select class=" form-control" name="heading_danhmuc">
                                        <option <?=$arrr_setting['heading_danhmuc']=='div'?'selected':''?> value="div">Bình thường (DIV)</option>
                                        <option <?=$arrr_setting['heading_danhmuc']=='h1'?'selected':''?> value="h1">Heading 1</option>
                                        <option <?=$arrr_setting['heading_danhmuc']=='h2'?'selected':''?> value="h2">Heading 2</option>
                                        <option <?=$arrr_setting['heading_danhmuc']=='h3'?'selected':''?> value="h3">Heading 3</option>
                                        <option <?=$arrr_setting['heading_danhmuc']=='h4'?'selected':''?> value="h4">Heading 4</option>
                                        <option <?=$arrr_setting['heading_danhmuc']=='h5'?'selected':''?> value="h5">Heading 5</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group m-5">
                                <label class="col-sm-5 p5 control-label" style="font-weight: 500;">Thông số kỹ thuật:</label>
                                <div class="col-sm-4 p5">
                                    <select name="thong_so" class="form-control">
                                        <option <?=$arrr_setting['thong_so']==0?'selected':''?> value="0">Không</option>
                                        <option <?=$arrr_setting['thong_so']==1?'selected':''?> value="1">Có</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group m-5">
                                <label class="col-sm-5 p5 control-label" style="font-weight: 500;">Video:</label>
                                <div class="col-sm-4 p5">
                                    <select name="video" class="form-control">
                                        <option <?=$arrr_setting['video']==0?'selected':''?> value="0">Không</option>
                                        <option <?=$arrr_setting['video']==1?'selected':''?> value="1">Có</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group m-5">
                                <label class="col-sm-5 p5 control-label" style="font-weight: 500;">File download:</label>
                                <div class="col-sm-4 p5">
                                    <select name="file" class="form-control">
                                        <option <?=$arrr_setting['file']==0?'selected':''?> value="0">Không</option>
                                        <option <?=$arrr_setting['file']==1?'selected':''?> value="1">Có</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group m-5">
                                <label class="col-sm-5 p5 control-label" style="font-weight: 500;">Mã sản phẩm:</label>
                                <div class="col-sm-4 p5">
                                    <select name="ma_sp" class="form-control">
                                        <option <?=$arrr_setting['ma_sp']==0?'selected':''?> value="0">Không</option>
                                        <option <?=$arrr_setting['ma_sp']==1?'selected':''?> value="1">Có</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group m-5">
                                <label class="col-sm-5 p5 control-label" style="font-weight: 500;">Giá:</label>
                                <div class="col-sm-4 p5">
                                    <select name="gia" class="form-control">
                                        <option <?=$arrr_setting['gia']==0?'selected':''?> value="0">Không</option>
                                        <option <?=$arrr_setting['gia']==1?'selected':''?> value="1">Có</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group m-5">
                                <label class="col-sm-5 p5 control-label" style="font-weight: 500;">Khuyến mãi:</label>
                                <div class="col-sm-4 p5">
                                    <select name="khuyen_mai" class="form-control">
                                        <option <?=$arrr_setting['khuyen_mai']==0?'selected':''?> value="0">Không</option>
                                        <option <?=$arrr_setting['khuyen_mai']==1?'selected':''?> value="1">Có</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group m-5">
                                <label class="col-sm-5 p5 control-label" style="font-weight: 500;">Đánh giá sao:</label>
                                <div class="col-sm-4 p5">
                                    <select name="danh_gia" class="form-control">
                                        <option <?=$arrr_setting['danh_gia']==0?'selected':''?> value="0">Không</option>
                                        <option <?=$arrr_setting['danh_gia']==1?'selected':''?> value="1">Có</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group m-5">
                                <label class="col-sm-5 p5 control-label" style="font-weight: 500;">Bình luận:</label>
                                <div class="col-sm-4 p5">
                                    <select name="binh_luan" class="form-control">
                                        <option <?=$arrr_setting['binh_luan']==0?'selected':''?> value="0">Không</option>
                                        <option <?=$arrr_setting['binh_luan']==1?'selected':''?> value="1">Có</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        
                    </div>
                    <div class="text-center">
                        <button class="btn btn-info" name="luucauhinh"><i class="fa fa-save"></i> Lưu cấu hình</button>
                    </div>
                </form>
            </div>
        </div>
    </section>
</div>

<script type="text/javascript">
    function add_sl(_this, id){
        $.ajax({
            url : "sources/ajax.php",
            type : "post",
            dataType:"text",
            data : {
                do : 'add_sl',
                id:id,
                so_luong: _this.val()
            },
            success : function (data){
                _this.val('0');
                $('.sl_'+id).html(data);
                $.notify("Thêm thành công", "success");
            }
        });
    }
    jQuery(document).ready(function($) {
        var where = "<?=$where_search?>";
        $('#pagination-ajax').twbsPagination({
        totalPages: <?=$total_page?>,
        visiblePages: 5,
        <?php if(isset($_GET['page'])){ ?>
        startPage:<?=$_GET['page']?>,
        <?php } ?>
        prev: '<span aria-hidden="true">&laquo;</span>',
        next: '<span aria-hidden="true">&raquo;</span>',
        onPageClick: function (event, page) {
            $.ajax({
                url: "templates/<?=$_GET['p']?>/ajax_pagination.php",
                type:'POST',
                data: {page: page,totalPages:'<?=$total_page?>', where: where, limit:'<?=$limit?>',search:'<?=$link_search?>'},
                success: function(data){
                    //console.log(data);
                    $('#data-ajax').html(data);
                }
            })
        }
    });
    });
</script>