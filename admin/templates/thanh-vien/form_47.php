<?php
$id_form = (int)$_GET['form'];
$title_form = $d->getContent($id_form);
$content_form = $d->getContents($id_form);

$check_form = $d->simple_fetch("select * from #_form_".$id_form." where thanhvien_id= ".(int)$_GET['id']." ");
if($check_form['trang_thai']=='0'){
    $d->o_que("update #_form_".$id_form." set trang_thai = 1 where id = ".$check_form['id']." ");
}
?>
<div class="card-header">
    <h2 class="card-header_title" style="background-image: url('<?=Img($title_form['hinh_anh'])?>')"><?=$title_form['ten']?></h2>
</div>
<div class="card-body">
    <?php if(count($check_form)>0){
        $danhsach =  $d->o_fet("select * from #_form_47_chitiet where id_form = ".$check_form['id']." order by id asc");?>
      
    <div class=" table-responsive">
        <table class="table table-bordered align-middle">
            <thead>
                <tr>
                    <th class="text-center">No.</th>
                    <th><?=$d->getTxt(65)?></th>
                    <th><?=$d->getTxt(66)?></th>
                    <th><?=$d->getTxt(67)?></th>
                    <th><?=$d->getTxt(9)?></th>
                    <th><?=$d->getTxt(68)?></th>
                    <th><?=$d->getTxt(69)?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($danhsach as $key => $value) {?>
                <tr>
                    <td class="text-center"><?=$key+1?></td>
                    <td><?=$value['ho_ten']?></td>
                    <td><?=$value['id_hochieu']?></td>
                    <td><?=$value['chuc_vu']?></td>
                    <td><?=$value['ten_congty']?></td>
                    <td><?=$value['quoc_gia']?></td>
                    <td class="text-center">
                        <a href="<?=URLPATH.$value['ho_chieu']?>" data-fancybox data-caption="Single image">
                            <img src="<?=URLPATH.$value['ho_chieu']?>" height="50px" />
                        </a>
                    </td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
    <?php } ?>
    <form method="POST" action="">
        <div class="text-center">
            <?php if($check_form['trang_thai']==2){ ?>
           <button type="button" class="btn btn-main btn-success w-50" >Đã xác nhận</button>
           <?php }else{ ?>
           <button type="submit" class="btn btn-main btn-primary w-50" name="dang_ky">Xác nhận</button>
           <?php } ?>
       </div>
    </form>
</div>
<style>
    .select2-container--bootstrap-5 .select2-selection{
        height: 40px !important;
        min-height: 40px !important;
    }
</style>