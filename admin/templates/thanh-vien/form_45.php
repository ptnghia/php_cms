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
        <?php 
        $js = $check_form['detail'];
        $data_sp = json_decode($js, true);
        
        ?>
        <div class="mt-4 mb-3">
            <div class=" d-flex justify-content-between">
                <div class="col_table" style="border-left: 1px solid #ccc;border-top: 1px solid #ccc;">
                    <label for="cum1_1_1">
                    <input type="checkbox" name="vitri[]" id="cum1_1_1" value="cum1_1_1" />
                    </label>
                </div>
                <div class="col_table" style="border-top: 1px solid #ccc;">
                    <label for="cum1_1_2">
                        <input type="checkbox" name="vitri[]" id="cum1_1_2" value="cum1_1_2" />
                    </label>
                </div>
                <div class="col_table" style="border-top: 1px solid #ccc;">
                    <label for="cum1_1_3">
                        <input type="checkbox" name="vitri[]" id="cum1_1_3" value="cum1_1_3" />
                    </label>
                </div>
                <div class="col_table" style="border-top: 1px solid #ccc;">
                    <label for="cum1_1_4">
                        <input type="checkbox" name="vitri[]" id="cum1_1_4" value="cum1_1_4" />
                    </label>
                </div>
                <div class="col_table" style="border-top: 1px solid #ccc;">
                    <label for="cum1_1_5">
                        <input type="checkbox" name="vitri[]" id="cum1_1_5" value="cum1_1_5" />
                    </label>
                </div>
                <div class="col_table" style="border-top: 1px solid #ccc;">
                    <label for="cum1_1_6">
                        <input type="checkbox" name="vitri[]" id="cum1_1_6" value="cum1_1_6" />
                    </label>
                </div>
                <div class="col_table" style="border-top: 1px solid #ccc;">
                    <label for="cum1_1_7">
                        <input type="checkbox" name="vitri[]" id="cum1_1_7" value="cum1_1_7" />
                    </label>
                </div>
                <div class="col_table" style="border-top: 1px solid #ccc;">
                    <label for="cum1_1_8">
                        <input type="checkbox" name="vitri[]" id="cum1_1_8" value="cum1_1_8" />
                    </label>
                </div>
                
                <div class="col_table" style="border-bottom: none;border-right: none;"></div>
                <div class="col_table" style="border-bottom: none;"></div>
                
                <div class="col_table" style="border-top: 1px solid #ccc;">
                    <label for="cum2_1_1">
                        <input type="checkbox" name="vitri[]" id="cum2_1_1" value="cum2_1_1" />
                    </label>
                </div>
                <div class="col_table" style="border-top: 1px solid #ccc;">
                    <label for="cum2_1_2">
                        <input type="checkbox" name="vitri[]" id="cum2_1_2" value="cum2_1_2" />
                    </label>
                </div>
                <div class="col_table" style="border-top: 1px solid #ccc;">
                    <label for="cum2_1_3">
                        <input type="checkbox" name="vitri[]" id="cum2_1_3" value="cum2_1_3" />
                    </label>
                </div>
                <div class="col_table" style="border-top: 1px solid #ccc;">
                    <label for="cum2_1_4">
                        <input type="checkbox" name="vitri[]" id="cum2_1_4" value="cum2_1_4" />
                    </label>
                </div>
            </div>
            
            <div class=" d-flex justify-content-between">
                <div class="col_table"  style="border-left: 1px solid #ccc;">
                    <label for="cum1_2_1">
                        <input type="checkbox" name="vitri[]" id="cum1_2_1" value="cum1_2_1" />
                    </label>
                </div>
                <div class="col_table">
                    <label for="cum1_2_2">
                        <input type="checkbox" name="vitri[]" id="cum1_2_2" value="cum1_2_2" />
                    </label>
                </div>
                <div class="col_table">
                    <label for="cum1_2_3">
                        <input type="checkbox" name="vitri[]" id="cum1_2_3" value="cum1_2_3" />
                    </label>
                </div>
                <div class="col_table">
                    <label for="cum1_2_4">
                        <input type="checkbox" name="vitri[]" id="cum1_2_4" value="cum1_2_4" />
                    </label>
                </div>
                <div class="col_table">
                    <label for="cum1_2_5">
                        <input type="checkbox" name="vitri[]" id="cum1_2_5" value="cum1_2_5" />
                    </label>
                </div>
                <div class="col_table">
                    <label for="cum1_2_6">
                        <input type="checkbox" name="vitri[]" id="cum1_2_6" value="cum1_2_6" />
                    </label>
                </div>
                <div class="col_table">
                    <label for="cum1_2_7">
                        <input type="checkbox" name="vitri[]" id="cum1_2_7" value="cum1_2_7" />
                    </label>
                </div>
                <div class="col_table">
                    <label for="cum1_2">
                        <input type="checkbox" name="vitri[]" id="cum1_2" value="cum1_2-8" />
                    </label>
                </div>
                
                <div class="col_table" style="border-bottom: none;border-right: none;"></div>
                <div class="col_table" style="border-bottom: none;"></div>
                
                <div class="col_table">
                    <label for="cum2_2">
                        <input type="checkbox" name="vitri[]" id="cum2_2" value="cum2_2-1" />
                    </label>
                </div>
                <div class="col_table">
                    <label for="cum2_2_2">
                        <input type="checkbox" name="vitri[]" id="cum2_2_2" value="cum2_2_2" />
                    </label>
                </div>
                <div class="col_table">
                    <label for="cum2_2_3">
                        <input type="checkbox" name="vitri[]" id="cum2_2_3" value="cum2_2_3" />
                    </label>
                </div>
                <div class="col_table">
                    <label for="cum2_2_4">
                        <input type="checkbox" name="vitri[]" id="cum2_2_4" value="cum2_2_4" />
                    </label>
                </div>
            </div>
            
            <div class=" d-flex justify-content-between">
                <div class="col_table"  style="border-left: 1px solid #ccc;">
                    <label for="cum1_3_1">
                        <input type="checkbox" name="vitri[]" id="cum1_3_1" value="cum1_3_1" />
                    </label>
                </div>
                <div class="col_table">
                    <label for="cum1_3_2">
                        <input type="checkbox" name="vitri[]" id="cum1_3_2" value="cum1_3_2" />
                    </label>
                </div>
                <div class="col_table">
                    <label for="cum1_3_3">
                        <input type="checkbox" name="vitri[]" id="cum1_3_3" value="cum1_3_3" />
                    </label>
                </div>
                <div class="col_table">
                    <label for="cum1_3_4">
                        <input type="checkbox" name="vitri[]" id="cum1_3_4" value="cum1_3_4" />
                    </label>
                </div>
                <div class="col_table">
                    <label for="cum1_3_5">
                        <input type="checkbox" name="vitri[]" id="cum1_3_5" value="cum1_3_5" />
                    </label>
                </div>
                <div class="col_table">
                    <label for="cum1_3_6">
                        <input type="checkbox" name="vitri[]" id="cum1_3_6" value="cum1_3_6" />
                    </label>
                </div>
                <div class="col_table">
                    <label for="cum1_3_7">
                        <input type="checkbox" name="vitri[]" id="cum1_3_7" value="cum1_3_7" />
                    </label>
                </div>
                <div class="col_table">
                    <label for="cum1_3_8">
                        <input type="checkbox" name="vitri[]" id="cum1_3_8" value="cum1_3_8" />
                    </label>
                </div>
                
                <div class="col_table" style="border-bottom: none;border-right: none;"></div>
                <div class="col_table" style="border-bottom: none;"></div>
                
                <div class="col_table">
                    <label for="cum2_3_1">
                        <input type="checkbox" name="vitri[]" id="cum2_3_1" value="cum2_3_1" />
                    </label>
                </div>
                <div class="col_table">
                    <label for="cum2_3_2">
                        <input type="checkbox" name="vitri[]" id="cum2_3_2" value="cum2_3_2" />
                    </label>
                </div>
                <div class="col_table">
                    <label for="cum2_3_3">
                        <input type="checkbox" name="vitri[]" id="cum2_3_3" value="cum2_3_3" />
                    </label>
                </div>
                <div class="col_table">
                    <label for="cum2_3_4">
                        <input type="checkbox" name="vitri[]" id="cum2_3_4" value="cum2_3_4" />
                    </label>
                </div>
            </div>
        </div>
        
        <div class="mt-5">
            <div class=" d-flex justify-content-between">
                <div class="col_table" style=" border: 1px solid #000"></div>
                <div class="col_table" style=" border-color: transparent;"><b style="display: block;margin-top: 20px;text-align: left;margin-left: 6px;">1m</b></div>
                <div class="col_table" style=" border-color: transparent"></div>
                <div class="col_table" style=" border-color: transparent"></div>
                <div class="col_table" style=" border-color: transparent"></div>
                <div class="col_table" style=" border-color: transparent"></div>
                <div class="col_table" style=" border-color: transparent"></div>
                
                <div class="col_table" style="border-left: 1px solid #ccc;border-top: 1px solid #ccc;">
                    <label for="cum3_1_1">
                        <input type="checkbox" name="vitri[]" id="cum3_1_1" value="cum3_1_1" />
                    </label>
                </div>
                <div class="col_table" style="border-top: 1px solid #ccc;">
                    <label for="cum3_1_2">
                        <input type="checkbox" name="vitri[]" id="cum3_1_2" value="cum3_1_2" />
                    </label>
                </div>
                <div class="col_table" style="border-top: 1px solid #ccc;">
                    <label for="cum3_1_3">
                        <input type="checkbox" name="vitri[]" id="cum3_1_3" value="cum3_1_3" />
                    </label>
                </div>
                <div class="col_table" style="border-top: 1px solid #ccc;">
                    <label for="cum3_1_4">
                        <input type="checkbox" name="vitri[]" id="cum3_1_4" value="cum3_1_4" />
                    </label>
                </div>
                <div class="col_table" style="border-top: 1px solid #ccc;">
                    <label for="cum3_1_5">
                        <input type="checkbox" name="vitri[]" id="cum3_1_5" value="cum3_1_5" />
                    </label>
                </div>
                <div class="col_table" style="border-top: 1px solid #ccc;">
                    <label for="cum3_1_6">
                        <input type="checkbox" name="vitri[]" id="cum3_1_6" value="cum3_1_6" />
                    </label>
                </div>
                <div class="col_table" style="border-top: 1px solid #ccc;">
                    <label for="cum3_1_7">
                        <input type="checkbox" name="vitri[]" id="cum3_1_7" value="cum3_1_7" />
                    </label>
                </div>
            </div>
            <div class=" d-flex justify-content-between">
                <div class="col_table" style=" border-color: transparent;"><b>1m</b></div>
                <div class="col_table" style=" border-color: transparent"></div>
                <div class="col_table" style=" border-color: transparent"></div>
                <div class="col_table" style=" border-color: transparent"></div>
                <div class="col_table" style=" border-color: transparent"></div>
                <div class="col_table" style=" border-color: transparent"></div>
                <div class="col_table" style=" border-color: transparent"></div>
                <div class="col_table" style="border-left: 1px solid #ccc;">
                    <label for="cum3_2_1">
                        <input type="checkbox" name="vitri[]" id="cum3_2_1" value="cum3_2_1" />
                    </label>
                </div>
                <div class="col_table">
                    <label for="cum3_2_2">
                        <input type="checkbox" name="vitri[]" id="cum3_2_2" value="cum3_2_2" />
                    </label>
                </div>
                <div class="col_table">
                    <label for="cum3_2_3">
                        <input type="checkbox" name="vitri[]" id="cum3_2_3" value="cum3_2_3" />
                    </label>
                </div>
                <div class="col_table">
                    <label for="cum3_2_4">
                        <input type="checkbox" name="vitri[]" id="cum3_2_4" value="cum3_2_4" />
                    </label>
                </div>
                <div class="col_table">
                    <label for="cum3_2_5">
                        <input type="checkbox" name="vitri[]" id="cum3_2_5" value="cum3_2_5" />
                    </label>
                </div>
                <div class="col_table">
                    <label for="cum3_2_6">
                        <input type="checkbox" name="vitri[]" id="cum3_2_6" value="cum3_2_6" />
                    </label>
                </div>
                <div class="col_table">
                    <label for="cum3_2_7">
                        <input type="checkbox" name="vitri[]" id="cum3_2_7" value="cum3_2_7" />
                    </label>
                </div>
            </div>
            <div class=" d-flex justify-content-between">
                <div class="col_table" style="border-left: 1px solid #ccc;border-top: 1px solid #ccc ">
                    <label for="cum4_1_1">
                        <input type="checkbox" name="vitri[]" id="cum4_1_1" value="cum4_1_1" />
                    </label>
                </div>
                <div class="col_table" style="border-top: 1px solid #ccc;">
                    <label for="cum4_1_2">
                        <input type="checkbox" name="vitri[]" id="cum4_1_2" value="cum4_1_2" />
                    </label>
                </div>
                <div class="col_table" style="border-top: 1px solid #ccc;">
                    <label for="cum4_1_3">
                        <input type="checkbox" name="vitri[]" id="cum4_1_3" value="cum4_1_3" />
                    </label>
                </div>
                <div class="col_table" style="border-top: 1px solid #ccc;">
                    <label for="cum4_1_4">
                        <input type="checkbox" name="vitri[]" id="cum4_1_4" value="cum4_1_4" />
                    </label>
                </div>
                <div class="col_table" style="border-top: 1px solid #ccc;">
                    <label for="cum4_1_5">
                        <input type="checkbox" name="vitri[]" id="cum4_1_5" value="cum4_1_5" />
                    </label>
                </div>
                <div class="col_table" style="border-top: 1px solid #ccc;">
                    <label for="cum4_1_6">
                        <input type="checkbox" name="vitri[]" id="cum4_1_6" value="cum4_1_6" />
                    </label>
                </div>
                <div class="col_table" style=" border-color: transparent"></div>
                <div class="col_table" style="border-left: 1px solid #ccc;">
                    <label for="cum3_3_1">
                        <input type="checkbox" name="vitri[]" id="cum3_3_1" value="cum3_3_1" />
                    </label>
                </div>
                <div class="col_table">
                    <label for="cum3_3_2">
                        <input type="checkbox" name="vitri[]" id="cum3_3_2" value="cum3_3_2" />
                    </label>
                </div>
                <div class="col_table">
                    <label for="cum3_3_3">
                        <input type="checkbox" name="vitri[]" id="cum3_3_3" value="cum3_3_3" />
                    </label>
                </div>
                <div class="col_table">
                    <label for="cum3_3_4">
                        <input type="checkbox" name="vitri[]" id="cum3_3_4" value="cum3_3_4" />
                    </label>
                </div>
                <div class="col_table">
                    <label for="cum3_3_5">
                        <input type="checkbox" name="vitri[]" id="cum3_3_5" value="cum3_3_5" />
                    </label>
                </div>
                <div class="col_table">
                    <label for="cum3_3_6">
                        <input type="checkbox" name="vitri[]" id="cum3_3_6" value="cum3_3_6" />
                    </label>
                </div>
                <div class="col_table">
                    <label for="cum3_3_7">
                        <input type="checkbox" name="vitri[]" id="cum3_3_7" value="cum3_3_7" />
                    </label>
                </div>
            </div>
            <div class=" d-flex justify-content-between">
                <div class="col_table" style="border-left: 1px solid #ccc;">
                    <label for="cum4_2_1">
                        <input type="checkbox" name="vitri[]" id="cum4_2_1" value="cum4_2_1" />
                    </label>
                </div>
                <div class="col_table">
                    <label for="cum4_2_2">
                        <input type="checkbox" name="vitri[]" id="cum4_2_2" value="cum4_2_2" />
                    </label>
                </div>
                <div class="col_table">
                    <label for="cum4_2_3">
                        <input type="checkbox" name="vitri[]" id="cum4_2_3" value="cum4_2_3" />
                    </label>
                </div>
                <div class="col_table">
                    <label for="cum4_2_4">
                        <input type="checkbox" name="vitri[]" id="cum4_2_4" value="cum4_2_4" />
                    </label>
                </div>
                <div class="col_table">
                    <label for="cum4_2_5">
                        <input type="checkbox" name="vitri[]" id="cum4_2_5" value="cum4_2_5" />
                    </label>
                </div>
                <div class="col_table" >
                    <label for="cum4_2_6">
                        <input type="checkbox" name="vitri[]" id="cum4_2_6" value="cum4_2_6" />
                    </label>
                </div>
                <div class="col_table" style=" border-color: transparent"></div>
                <div class="col_table" style="border-left: 1px solid #ccc;">
                    <label for="cum3_4_1">
                        <input type="checkbox" name="vitri[]" id="cum3_4_1" value="cum3_4_1" />
                    </label>
                </div>
                <div class="col_table">
                    <label for="cum3_4_2">
                        <input type="checkbox" name="vitri[]" id="cum3_4_2" value="cum3_4_2" />
                    </label>
                </div>
                <div class="col_table">
                    <label for="cum3_4_3">
                        <input type="checkbox" name="vitri[]" id="cum3_4_3" value="cum3_4_3" />
                    </label>
                </div>
                <div class="col_table">
                    <label for="cum3_4_4">
                        <input type="checkbox" name="vitri[]" id="cum3_4_4" value="cum3_4_4" />
                    </label>
                </div>
                <div class="col_table">
                    <label for="cum3_4_5">
                        <input type="checkbox" name="vitri[]" id="cum3_4_5" value="cum3_4_5" />
                    </label>
                </div>
                <div class="col_table">
                    <label for="cum3_4_6">
                        <input type="checkbox" name="vitri[]" id="cum3_4_6" value="cum3_4_6" />
                    </label>
                </div>
                <div class="col_table">
                    <label for="cum3_4_7">
                        <input type="checkbox" name="vitri[]" id="cum3_4_7" value="cum3_4_7" />
                    </label>
                </div>
            </div>
            <div class=" d-flex justify-content-between">
                <div class="col_table" style="border-left: 1px solid #ccc;">
                    <label for="cum4_3_1">
                        <input type="checkbox" name="vitri[]" id="cum4_3_1" value="cum4_3_1" />
                    </label>
                </div>
                <div class="col_table">
                    <label for="cum4_3_2">
                        <input type="checkbox" name="vitri[]" id="cum4_3_2" value="cum4_3_2" />
                    </label>
                </div>
                <div class="col_table">
                    <label for="cum4_3_3">
                        <input type="checkbox" name="vitri[]" id="cum4_3_3" value="cum4_3_3" />
                    </label>
                </div>
                <div class="col_table">
                    <label for="cum4_3_4">
                        <input type="checkbox" name="vitri[]" id="cum4_3_4" value="cum4_3_4" />
                    </label>
                </div>
                <div class="col_table">
                    <label for="cum4_3_5">
                        <input type="checkbox" name="vitri[]" id="cum4_3_5" value="cum4_3_5" />
                    </label>
                </div>
                <div class="col_table" >
                    <label for="cum4_3_6">
                        <input type="checkbox" name="vitri[]" id="cum4_3_6" value="cum4_3_6" />
                    </label>
                </div>
                <div class="col_table" style=" border-color: transparent"></div>
                <div class="col_table" style="border-left: 1px solid #ccc;">
                    <label for="cum3_5_1">
                        <input type="checkbox" name="vitri[]" id="cum3_5_1" value="cum3_5_1" />
                    </label>
                </div>
                <div class="col_table">
                    <label for="cum3_5_2">
                        <input type="checkbox" name="vitri[]" id="cum3_5_2" value="cum3_5_2" />
                    </label>
                </div>
                <div class="col_table">
                    <label for="cum3_5_3">
                        <input type="checkbox" name="vitri[]" id="cum3_5_3" value="cum3_5_3" />
                    </label>
                </div>
                <div class="col_table">
                    <label for="cum3_5_4">
                        <input type="checkbox" name="vitri[]" id="cum3_5_4" value="cum3_5_4" />
                    </label>
                </div>
                <div class="col_table">
                    <label for="cum3_5_5">
                        <input type="checkbox" name="vitri[]" id="cum3_4_5" value="cum3_5_5" />
                    </label>
                </div>
                <div class="col_table">
                    <label for="cum3_5_6">
                        <input type="checkbox" name="vitri[]" id="cum3_5_6" value="cum3_5_6" />
                    </label>
                </div>
                <div class="col_table">
                    <label for="cum3_5_7">
                        <input type="checkbox" name="vitri[]" id="cum3_5_7" value="cum3_5_7" />
                    </label>
                </div>
            </div>
            <div class=" d-flex justify-content-between">
                <div class="col_table" style=" border-color: transparent"></div>
                <div class="col_table" style=" border-color: transparent"></div>
                <div class="col_table" style=" border-color: transparent"></div>
                <div class="col_table" style=" border-color: transparent"></div>
                <div class="col_table" style=" border-color: transparent"></div>
                <div class="col_table" style=" border-color: transparent"></div>
                <div class="col_table" style=" border-color: transparent"></div>
                <div class="col_table" style="border-left: 1px solid #ccc;">
                    <label for="cum3_6_1">
                        <input type="checkbox" name="vitri[]" id="cum3_6_1" value="" />
                    </label>
                </div>
                <div class="col_table">
                    <label for="cum3_6_2">
                        <input type="checkbox" name="vitri[]" id="cum3_6_2" value="" />
                    </label>
                </div>
                <div class="col_table">
                    <label for="cum3_6_3">
                        <input type="checkbox" name="vitri[]" id="cum3_6_3" value="" />
                    </label>
                </div>
                <div class="col_table">
                    <label for="cum3_6_4">
                        <input type="checkbox" name="vitri[]" id="cum3_6_4" value="" />
                    </label>
                </div>
                <div class="col_table">
                    <label for="cum3_6_5">
                        <input type="checkbox" name="vitri[]" id="cum3_6_5" value="" />
                    </label>
                </div>
                <div class="col_table">
                    <label for="cum3_6_6">
                        <input type="checkbox" name="vitri[]" id="cum3_6_6" value="" />
                    </label>
                </div>
                <div class="col_table">
                    <label for="cum3_6_7">
                        <input type="checkbox" name="vitri[]" id="cum3_6_7" value="" />
                    </label>
                </div>
            </div>
        </div>
        <div class="list_thietbi  d-flex mt-4 mb-5">
             <?php foreach ($content_form as $key => $value) {?>
            <div class="mb-3 d-flex  item_thietbi align-items-center">
                <span class="color" style="background-color: <?=$value['link']?>"></span> <?=$value['ten']?>
            </div>
             <?php } ?>
        </div>
        <input type="hidden" class=" form-control" id="vitri" name="text_vitri"  />
        <input type="hidden" class=" form-control" id="id_thietbi" name="id_thietbi"  />
        
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
<!-- Modal -->
<!--div class="modal fade" id="exampleModal" data-bs-backdrop="static" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel"><?=$d->getTxt(64)?></h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <input type="hidden" id="check_chon" class=" form-control"  />
            <input type="hidden" id="thietbi_dachon" class=" form-control"  />
            <?php foreach ($content_form as $key => $value) {?>
            <div class="mb-3">
                <button style="border: none; padding: 11px 15px; background-color: <?=$value['link']?>;" class="btn btn-primary btn-main w-100 chon_thietbi text-start" data_id="<?=$value['id_code']?>" data_color="<?=$value['link']?>" ><?=$value['ten']?></button>
            </div> 
            <?php } ?>
        </div>
        <div class="modal-footer text-center">
            <button type="button" class="btn btn-secondary" id="huy_chon" >Xác nhận</button>
        </div>
    </div>
  </div>
</div-->
<script>
    <?php 
    foreach ($data_sp as $key => $value) {
    $thietbi = $d->getContent_id($value['thiet_bi']);    
    $chuoi_vitri .=','. $value['vitri'];
    $chuoi_thietbi .=','. $value['thiet_bi'];
    ?>
        
     $('#<?=$value['vitri']?>').prop('checked', true); 
     $('label[for="<?=$value['vitri']?>"]').css('background-color', '<?=$thietbi['link']?>'); 
    <?php } ?>
    $('#vitri').val('<?=$chuoi_vitri?>');    
    $('#id_thietbi').val('<?=$chuoi_thietbi?>');  
    
    var w = $('.col_table').width();
    $('.col_table').height(w);
    $('.col_table label').click(function(){
        var id = $(this).attr('for');
        var id_thietbi = $(this).attr('id_thietbi');
        if($('#'+id).prop('checked')){
            $('#exampleModal').modal('show');
            $('#check_chon').val(id);
            $('#thietbi_dachon').val(id_thietbi);
        }else{
           //alert('huychon');
        }
    })
    $('.chon_thietbi').click(function(){
        var vitri_chon =  $('#check_chon').val();
        var color   =   $(this).attr('data_color');
        var id_code =   $(this).attr('data_id');
        
        var text_vitri = $('#vitri').val();
        var id_thietbi = $('#id_thietbi').val();
        
        $('#vitri').val(text_vitri+','+vitri_chon);
        $('#id_thietbi').val(id_thietbi+','+id_code);
        $('label[for="'+vitri_chon+'"]').css('background-color', color); 
        $('label[for="'+vitri_chon+'"]').attr('id_thietbi',id_code )
        $('#exampleModal').modal('hide');
    })
    $('#huy_chon').click(function(){
        var idchon =  $('#check_chon').val();
        $('#'+idchon).prop('checked', false);
        
        var vitri_chon =  $('#check_chon').val();
        var id_code =   $('#thietbi_dachon').val();
        
        var text_vitri = $('#vitri').val();
        var id_thietbi = $('#id_thietbi').val();
        
        var newtext_vitri = text_vitri.replace(','+vitri_chon, '');
        var newid_thietbi = id_thietbi.replace(','+id_code, '');
        
        $('label[for="'+vitri_chon+'"]').css('background-color', 'transparent'); 
        
        $('#vitri').val(newtext_vitri);
        $('#id_thietbi').val(newid_thietbi);
    
        $('#exampleModal').modal('hide');
        $('#check_chon').val('');
        
        
    })
</script>