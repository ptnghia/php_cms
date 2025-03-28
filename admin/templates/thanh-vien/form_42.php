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
    <h2 class="card-header_title"><?=$title_form['ten']?></h2>
</div>
<div class="card-body">
    <form method="POST" action="" class="needs-validation" novalidate>
        <input type="hidden" value="<?=$_SESSION['token']?>" name="_token" />
        <h3 class="card_body-title"><?=$content_form[0]['ten']?></h3>
        <div class="form_text">
            <?=$content_form[0]['noi_dung']?>
        </div>
        <div class="form-check">
            <input <?=$check_form['tran_ten']=='0'?'checked':''?> class="form-check-input" type="checkbox" value="0" name="tran_ten" id="tran_ten">
            <label class="form-check-label" for="tran_ten">
                <?=$content_form[0]['link']?>
            </label>
        </div>
        <div class="form_text">
            <p><i><?=$d->getTxt(42)?></i></p>
        </div>
        <h3 class="card_body-title"><?=$content_form[1]['ten']?></h3>
        <div class="form_text">
            <?=$content_form[1]['noi_dung']?>
        </div>
        <?php foreach ($content_form as $key => $value) {
            if($key>1){?>
        <div class="form-check">
            <input <?=strpos($check_form['hangmuc_bo'], $value['ten']) !== false?'checked':''?> class="form-check-input" type="checkbox" value="<?=$value['ten']?>" name="hangmuc_bo[]" id="hangmuc_bo_<?=$value['id_code']?>">
            <label class="form-check-label" for="hangmuc_bo_<?=$value['id_code']?>">
                <?=$value['ten']?>
            </label>
        </div>
        <?php } } ?>
        <div class="text-center">
             <?php if($check_form['trang_thai']==2){ ?>
            <button type="button" class="btn btn-main btn-success w-50" >Đã xác nhận</button>
            <?php }else{ ?>
            <button type="submit" class="btn btn-main btn-primary w-50" name="dang_ky">Xác nhận</button>
            <?php } ?>
        </div>
    </form>
    
</div>