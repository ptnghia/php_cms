<?php
$keyword = validate_content(urldecode($search['textsearch']));
$id_loai = (int)$search['id_loai'];
if($id_loai>0){
	$where_loai = " and id_loai = ".$id_loai." ";
}else{
	$where_loai="";
}
$total_records = $d->num_rows("select * from #_sanpham where ten like '%".$keyword."%' $where_loai and hien_thi =1 "._where_lang." order by so_thu_tu ASC, id DESC");
$limit = 20;//get_json('product', 'paging');
$total_page = ceil($total_records / $limit);
$sanpham = $d->o_fet("select * from #_sanpham where  ten like '%".$keyword."%' $where_loai and hien_thi =1 "._where_lang." order by so_thu_tu ASC, id DESC limit 0,$limit ");
?>


<div class="header_page position-relative">
    <?php if($row['banner']!=''){ ?><span class="br"></span><?php } ?>
    <div class="container position-absolute top-50 start-50 translate-middle">
        <h1 class="title">Tìm kiếm</h1>
        <div class="d-flex justify-content-center">
            <nav aria-label="breadcrumb">
               <ol vocab="https://schema.org/" typeof="BreadcrumbList" class="breadcrumb"> 
                    <li property="itemListElement" typeof="ListItem" class="breadcrumb-item">
                        <a property="item" typeof="WebPage" href="<?=URLPATH?>">
                        <span property="name">Trang chủ</span></a>
                         <meta property="position" content="1">
                    </li>

                    <li property="itemListElement" typeof="ListItem" class="breadcrumb-item active">
                        <a property="item" typeof="WebPage" href="<?=$url_page?>">
                        <span property="name">Tìm kiếm</span></a>
                        <meta property="position" content="2">
                    </li>
                    <?php if($id_loai>0){
                    $cate = $d->getCate($id_loai);    
                    ?>
                    <li property="itemListElement" typeof="ListItem" class="breadcrumb-item active">
                        <a property="item" typeof="WebPage" href="<?=  cre_Link($cate['alias'])?>">
                        <span property="name"><?=$cate['ten']?></span></a>
                        <meta property="position" content="3">
                    </li>
                    <?php } ?>
                </ol>
            </nav>
        </div>
    </div>
</div>

<div class="container testimonial-style1">
    <div class="row g-2 g-sm-3 row-cols-lg-5 row-cols-md-3 row-cols-sm-4 row-cols-2" id="result">
        <?php foreach ($sanpham as $key => $item) {
        if($item['khuyen_mai']>0){
            $gia_ban = $item['khuyen_mai'];
            $gia_km = $item['gia'];
            $giamgia = (($gia_km-$gia_ban)/$gia_km)*100;
            $gia = '<span><strong>'.numberformat($gia_ban).' VNĐ </strong><del>'.  number_format($gia_km).' VNĐ</del></span> <span class="sale">-'.ceil($giamgia).'%</span>';
        }else{
            if($item['gia']>0){
                $gia_ban = $item['gia'];
                $gia_km=0;
                $gia = '<strong>'.numberformat($gia_ban).' VNĐ</strong>';
            }else{
                $gia_ban = 0;
                $gia = '<strong>Liên hệ</strong>';
            }
        }
        $thuoctinh_chitiet = $d->o_fet("SELECT * FROM `db_sanpham_chitiet` WHERE id_sp = ".(int)$item['id_code']." and id_loai = 0");
        ?>
        <div class="col">
            <div class="product_item ">
                <a <?=  cf_tag_a_url($item['slug'], $item['alias'], $item['nofollow'], $item['target'])?> title="<?=$item['ten']?>" class="img_box">
                    <img src="<?=  Img($item['hinh_anh'])?>" alt="<?=$item['ten']?>" >
                </a>
                <div class="product_content">
                    <h3 class="title"><a <?=  cf_tag_a_url($item['slug'], $item['alias'], $item['nofollow'], $item['target'])?> title="<?=$item['ten']?>"><?=$item['ten']?></a></h3>
                    <div class="sao"><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star-half-stroke"></i></div>
                    <div class="price d-flex justify-content-between align-items-center">
                        <?=$gia?>
                    </div>
                    <div class=" d-flex justify-content-between align-items-center">
                        <a class="product_cate" href=""><?=$d->getCate($item['id_loai'], 'ten')?></a>
                        <div class="list_size">
                            <?php foreach ($thuoctinh_chitiet as $key2 => $value) {?>
                            <span><?=$value['ten']?></span>            
                            <?php } ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php } ?>
    </div>

    <?php if($total_page>1){ ?>
    <div class="d-flex justify-content-center mt-4">
        <nav aria-label="Page navigation example">
            <ul class="pagination" id="pagination-ajax">
            </ul>
        </nav>
    </div>
    <?php }?>
</div>

<script type="text/javascript" src="templates/js/jquery.twbsPagination.js"></script>

<?php if($total_page>1){ ?>
<script type="text/javascript">
    jQuery(document).ready(function($) {
        var keyword = '<?=$keyword?>';
        $('#pagination-ajax').twbsPagination({
            totalPages: <?=$total_page?>,
            visiblePages: 5,
            prev: '<span aria-hidden="true">&laquo;</span>',
            next: '<span aria-hidden="true">&raquo;</span>',
            onPageClick: function (event, page) {
                $.ajax({
                    url: "sources/ajax/ajax-pagination.php",
                    type:'POST',
                    data: {page: page,totalPages:'<?=$total_page?>', key:keyword, id_loai:'<?=$id_loai?>',  limit: '<?=$limit?>', do: 'pagination_sanpham2'},
                    success: function(data){
                        console.log(data);
                        $('#result').html(data);
                    }
                })
            }
        });
    });
</script>
<?php } ?>