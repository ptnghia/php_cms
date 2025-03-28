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
    <form method="POST" action="" class="needs-validation" novalidate>
        <input type="hidden" value="<?=$_SESSION['token']?>" name="_token" />
        <div class="form_text">
            <?=$title_form['noi_dung']?>
        </div>
        <h3 class="card_body-title"><?=$content_form[0]['ten']?></h3>
        <div class="form_text">
            <?=$content_form[0]['noi_dung']?>
        </div>
        <h3 class="card_body-title"><?=$content_form[1]['ten']?></h3>
        <div class="form_text">
            <?=$content_form[1]['noi_dung']?>
        </div>
        <div class="mb-3">
            <div class="mb-3">
                <label class="form-label" for="title"><?=$d->getTxt(45)?> <span class="text-red">*</span></label>
                <input type="text" class="form-control" value="<?=$check_form['title']?>" required id="title"  placeholder="<?=$d->getTxt(45)?>" name="address">
                <div class="invalid-feedback"><?=$d->getTxt(30)?> </div>
            </div>
        </div>
        <div class="mb-3">
            <label class="form-label" for="content"><?=$d->getTxt(46)?><span class="text-red">*</span></label>
            <textarea rows="3" onchange="check_limit($(this),'content')" limit="150" class="form-control" required id="content" name="content" placeholder="<?=$d->getTxt(46)?>"><?=$check_form['content']?></textarea>
            <div class="invalid-feedback" id="error_content">
                <?=$d->getTxt(30)?>
            </div>
        </div>
        <div class="row"> 
            <div class="mb-3 col-md-6">
                <label class="form-label" for="name"><?=$d->getTxt(47)?> <span class="text-red">*</span></label>
                <input type="text" class="form-control" value="<?=$check_form['name']?>" required placeholder="<?=$d->getTxt(47)?>" id="attn" name="name">
                <div class="invalid-feedback"> <?=$d->getTxt(30)?> </div>
            </div>
            <div class="mb-3 col-md-6">
                <label class="form-label" for="position"><?=$d->getTxt(15)?> <span class="text-red">*</span></label>
                <input type="text" class="form-control" value="<?=$check_form['position']?>" placeholder="<?=$d->getTxt(15)?>" required id="position" name="position">
                <div class="invalid-feedback"> <?=$d->getTxt(30)?> </div>
            </div>
        </div>
        <div class="form_text">
            <p><i><?=$title_form['link']?></i></p>
        </div>
        <div class="text-center">
             <?php if($check_form['trang_thai']==2){ ?>
            <button type="button" class="btn btn-main btn-success w-50" >Đã xác nhận</button>
            <?php }else{ ?>
            <button type="submit" class="btn btn-main btn-primary w-50" name="dang_ky">Xác nhận</button>
            <?php } ?>
        </div>
    </form>
    
</div>