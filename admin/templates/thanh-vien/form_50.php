<?php
$id_form = (int)$_GET['form'];
$title_form = $d->getContent($id_form);
$content_form = $d->getContents($id_form);
$danhmuc_sp = $d->o_fet("select * from #_category_noidung where type = 1 and  id_code = 50  "._where_lang." order by id ASC");
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
        <div class="form_text mb-4">
            <?=$title_form['noi_dung']?>
        </div>
        <?php 
            $js = $check_form['detail'];//'[{"code":"I.","name":"EQUIPMENT FOR LIGHTING","detail":{"64":{"code":"LF","ten":"Fluorescent Tube (40W) ","price":"35","so_luong":"2","thanhtien":"70"},"65":{"code":"LSP","ten":"LED Light 50W ","price":"54","so_luong":"1","thanhtien":"54"},"66":{"code":"SSP","ten":"LED Light 100W with arm/ without arm ","price":"60","so_luong":"0","thanhtien":"0"}}},{"code":"II.","name":"BREAKERS FOR EXHIBITS","detail":{"67":{"code":"E16","ten":"5 Amp socket (single phase 220V) ","price":"50","so_luong":"1","thanhtien":"50"},"68":{"code":"E17","ten":"15Amp socket (single phase 220V) ","price":"80","so_luong":"2","thanhtien":"160"},"69":{"code":"E18","ten":"30 Amp breaker (single phase 220V) ","price":"220","so_luong":"0","thanhtien":"0"},"70":{"code":"E19","ten":"60 Amp breaker (single phase 220V) ","price":"400","so_luong":"5","thanhtien":"2000"},"71":{"code":"E20","ten":"15Amp breaker (3 phase 380V) ","price":"300","so_luong":"0","thanhtien":"0"},"72":{"code":"E21","ten":"30 Amp breaker (3 phase 380V) ","price":"465","so_luong":"1","thanhtien":"465"},"73":{"code":"E22","ten":"60 Amp breaker (3 phase 380V) ","price":"725","so_luong":"0","thanhtien":"0"}}}]';
            $data_sp = json_decode($js, true);
            //print_r($data_sp);
        ?>
        <table class="table table-bordered table_form">
            <thead>
                <tr>
                    <th class="text-left"><?=$d->getTxt(72)?></th>
                    <th class="text-end"><?=$d->getTxt(49)?></th>
                    <th class="text-center"><?=$d->getTxt(50)?></th>
                    <th class="text-end"><?=$d->getTxt(51)?></th>
                </tr>
            </thead>
            
            <tbody>
                <?php 
                $i=0;
                foreach ($danhmuc_sp as $key => $value) {?>
                <tr style="display: none">
                    <td colspan="4"> <b><?=$value['code']?>. <?=$value['ten']?></b>
                        <input type="hidden" value="<?=$value['id_code']?>" name="danhmuc_id[]"/>
                    </td>
                </tr> 
                <?php foreach ($d->getContents($value['id_code']) as $key1 => $value1) {
                $i= $i+1;    
                ?>
                <tr>
                    <td>
                        <input type="hidden" value="<?=$value1['id_code']?>" name="sp_<?=$value['id_code']?>[]"/>
                        <b><?=$value1['ten']?>. <?=strip_tags($value1['noi_dung'])?></b>
                    </td>
                    <td class="text-end">
                        $<?=  number_format($value1['link'], 2)?>/sqm
                        <input type="hidden" value="<?=$value1['link']?>" name="price_<?=$value['id_code']?>[]"/>
                    </td>
                    <td class="text-center">
                        <input type="number" value="<?=count($data_sp)>0?$data_sp[$key]['detail'][$value1['id_code']]['so_luong']:'0'?>" min="0" class="input_form num_sl" name="soluong_<?=$value['id_code']?>[]" data_id="<?=$value1['id_code']?>" data_price="<?=$value1['link']?>" />
                    </td>
                    <td class="text-end">
                        <strong id="text_thanhtien_<?=$value1['id_code']?>">$<?=count($data_sp)>0?number_format($data_sp[$key]['detail'][$value1['id_code']]['thanhtien'], 2):'0'?></strong>
                        <input type="hidden" value="<?=count($data_sp)>0?$data_sp[$key]['detail'][$value1['id_code']]['thanhtien']:'0'?>" class="thanhtien" id="thanhtien_<?=$value1['id_code']?>" name="thanhtien_<?=$value['id_code']?>[]"/>
                    </td>
                </tr>        
                <?php } ?>
                <?php $i= $i+1;} ?>
            </tbody>
            
            <tfoot>
                <tr>
                    <th><?=$d->getTxt(52)?></th>
                    <th class="text-end"></th>
                    <th class="text-center">
                        <span id="tong_sl_text"><?=count($check_form)>0?number_format($check_form['tong_sl'], 0):'0'?></span>
                        <input type="hidden" id="tong_sl" value="<?=count($check_form)>0?$check_form['tong_sl']:'0'?>" name="tong_sl" />
                    </th>
                    <th class="text-end">
                        <span id="tong_tien_text">$<?=count($check_form)>0?number_format($check_form['tong_tien'], 2):'0'?></span>
                        <input type="hidden" id="tong_tien" value="<?=count($check_form)>0?$check_form['tong_tien']:'0'?>" name="tong_tien" />
                    </th>
                </tr>
                <!--tr>
                    <th colspan="2"><?=$d->getTxt(53)?></th>
                    <th colspan="3" class="text-end">
                        <span id="vat_text">$<?=count($check_form)>0?number_format($check_form['vat'], 2):'0'?></span>
                        <input type="hidden" id="vat" value="<?=count($check_form)>0?$check_form['vat']:'0'?>" name="vat" />
                    </th>
                </tr>
                <tr>
                    <th colspan="2"><?=$d->getTxt(54)?></th>
                    <th colspan="3" class="text-end">
                        <span id="thanh_toan_text">$<?=count($check_form)>0?number_format($check_form['thanh_toan'], 2):'0'?></span>
                        <input type="hidden" id="thanh_toan" value="<?=count($check_form)>0?$check_form['thanh_toan']:'0'?>" name="thanh_toan" />
                    </th>
                </tr-->
            </tfoot>
        </table>
        <?=$d->getContent(129)['noi_dung']?>
        
        <div class="mb-2 mt-4">
            <label class="form-label"><?=$d->getTxt(55)?></label>
            <div class="form-check form-check-inline">
                <input class="form-check-input" <?= ($check_form['bill_to'] == $d->getTxt(56) or count($check_form)=='0')?'checked':''?> type="radio" name="bill_to" id="bill_to1" value="<?=$d->getTxt(56)?>">
                <label class="form-check-label" style="position: relative;top: 2px;" for="bill_to1"><?=$d->getTxt(56)?></label>
            </div>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" <?=$check_form['bill_to']==$d->getTxt(57)?'checked':''?> name="bill_to" id="bill_to2" value="<?=$d->getTxt(57)?>">
                <label class="form-check-label" style="position: relative;top: 2px;" for="bill_to2"><?=$d->getTxt(57)?></label>
            </div>
        </div>
        <h3 class="card_body-title ps-0 mt-0"><?=$d->getTxt(58)?></h3>
        <div class="row">
            <div class="mb-3 col-lg-6">
                <label class="form-label" for="company_name"><?=$d->getTxt(9)?> <span class="text-red">*</span></label>
                <input type="text" class="form-control" value="<?=  isset($check_form['company_name'])?$check_form['company_name']:$thanhvien['company_name']?>" required id="company_name"  placeholder="<?=$d->getTxt(9)?>" name="company_name">
                <div class="invalid-feedback"><?=$d->getTxt(30)?> </div>
            </div>
            <div class="mb-3 col-lg-6">
                <label class="form-label" for="company_address"><?=$d->getTxt(59)?> <span class="text-red">*</span></label>
                <input type="text" class="form-control" value="<?=  isset($check_form['company_address'])?$check_form['company_address']:$thanhvien['address'].', '.$thanhvien['country']?>" required id="company_address"  placeholder="<?=$d->getTxt(59)?>" name="company_address">
                <div class="invalid-feedback"><?=$d->getTxt(30)?> </div>
            </div>
        </div>
         <div class="row">
             <div class="mb-3 col-lg-3 col-md-6">
                <label class="form-label" for="telephone"><?=$d->getTxt(60)?> <span class="text-red">*</span></label>
                <input type="text" class="form-control" value="<?=  isset($check_form['telephone'])?$check_form['telephone']:$thanhvien['phone']?>" required id="telephone"  placeholder="<?=$d->getTxt(60)?>" name="telephone">
                <div class="invalid-feedback"><?=$d->getTxt(30)?> </div>
            </div>
            <div class="mb-3  col-lg-3 col-md-6">
                <label class="form-label" for="tax_code"><?=$d->getTxt(61)?> <span class="text-red">*</span></label>
                <input type="text" class="form-control" value="<?=  isset($check_form['tax_code'])?$check_form['tax_code']:''?>" required id="tax_code"  placeholder="<?=$d->getTxt(61)?>" name="tax_code">
                <div class="invalid-feedback"><?=$d->getTxt(30)?> </div>
            </div>
             
             <div class="mb-3  col-lg-3 col-md-6">
                <label class="form-label" for="person_charge"><?=$d->getTxt(62)?> <span class="text-red">*</span></label>
                <input type="text" class="form-control" value="<?=  isset($check_form['person_charge'])?$check_form['person_charge']:$thanhvien['attn']?>" required id="person_charge"  placeholder="<?=$d->getTxt(62)?>" name="person_charge">
                <div class="invalid-feedback"><?=$d->getTxt(30)?> </div>
            </div>
            <div class="mb-3  col-lg-3 col-md-6">
                <label class="form-label" for="email"><?=$d->getTxt(16)?> <span class="text-red">*</span></label>
                <input type="text" class="form-control" value="<?=  isset($check_form['email'])?$check_form['email']:$thanhvien['email']?>" required id="email"  placeholder="<?=$d->getTxt(16)?>" name="email">
                <div class="invalid-feedback"><?=$d->getTxt(30)?> </div>
            </div>
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
<script>
     function formatCurrency(amount) {
        const formattedAmount = new Intl.NumberFormat('en-US', {
            style: 'currency',
            currency: 'USD',
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        }).format(amount);

        return formattedAmount;
    }
    $('.num_sl').on("keyup change", function(e) {
        var id      =   $(this).attr('data_id');
        var price   =   $(this).attr('data_price');
        var soluong =   $(this).val();
        
        var thanhtien = price*soluong;
        var thanhtien_txr = formatCurrency(thanhtien);
      
        
        $('#text_thanhtien_'+id).html(thanhtien_txr);
        $('#thanhtien_'+id).val(thanhtien);
        
        
        const total = $('.thanhtien').toArray().reduce((sum, input) => {
            const value = parseFloat($(input).val());
            return sum + value;
        }, 0);
        
        var tong_tien = total.toFixed(2);
        var vat = tong_tien * (10/100);
        var thanhtoan = parseFloat(tong_tien) + parseFloat(vat);
       
        $('#tong_tien_text').html(formatCurrency(tong_tien));
        $('#tong_tien').val(tong_tien);
        
        $('#vat_text').html(formatCurrency(vat));
        $('#vat').val(vat);
        
        $('#thanh_toan_text').html(formatCurrency(thanhtoan));
        $('#thanh_toan').val(thanhtoan);
        
         const total_sl = $('.num_sl').toArray().reduce((sum, input) => {
            const value_sl = parseFloat($(input).val());
            return sum + value_sl;
        }, 0);
        
        var tong_sl = total_sl;
        $('#tong_sl_text').html(tong_sl);
        $('#tong_sl').val(tong_sl);
    })
</script>