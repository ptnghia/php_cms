<?php 
function Img($img) {
    if($img!=''){
        $link_img = URLPATH.'img_data/images/'.$img; 
    }else{
        $link_img = URLPATH.'img_data/no-image.png';
    }
    return $link_img;
}
function cre_Link($link) {
    $link_text = URLPATH.$link.'.html';
    return $link_text;
}
?>
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Khách hàng <small>[<?php if(isset($_GET['id'])) echo "chi tiết "; else echo "Thêm mới" ?>]</small>
        </h1>
        <ol class="breadcrumb">
          <li><a href="<?=urladmin?>"><i class="fa fa-dashboard"></i> Trang chủ</a></li>
          <li><a href="#">Quản lý khách hàng</a></li>
          <li class="active">Order form</li>
        </ol>
    </section>
    <!-- Main content -->
    <section class="content">
        <?php $danhmuc_home = $d->o_fet("select * from #_category_noidung where type = 1 and id_code > 41 and id_code < 50  "._where_lang." order by id ASC"); ?>
        <div class="card">
            <div class="row">
                <div class="col-md-3">
                    <ul class="nav_sidebar">
                        <?php foreach ($danhmuc_home as $key => $value) {?>
                        <li><a class="<?=$value['id_code']==$_GET['form']?'active':''?> " href="index.php?p=thanh-vien&a=edit&id=<?=$_GET['id']?>&form=<?=$value['id_code']?>"><?=$value['ten']?></a></li>
                        <?php } ?>
                    </ul>
                </div>
                <div class="col-md-9">
                    <?php 
                    
                    ?>
                    <?php include 'form_'.$_GET['form'].'.php'; 
                    
                    if(isset($_POST['dang_ky'])){
                       $d->o_que("update #_form_".$id_form." set trang_thai = 2 where id = ".$check_form['id']." ");
                    }
                    ?>
                    
                </div>
            </div>
        </div>
        
    </section>
</div>