<?php
 $menu = "";
    $nav  = $d->o_fet("select * from #_category where menu = 1 and hien_thi=1 "._where_lang." order by so_thu_tu asc, id desc");
    $delay=0.1;
    foreach($nav as $item) {
        $sub=$d->o_fet("select * from #_category where id_loai={$item['id_code']} and hien_thi=1 "._where_lang." order by so_thu_tu asc, id desc");
        if(count($sub)>0) {
            $menusub="";
            foreach ($sub as $key => $item1) {
                $sub1=$d->o_fet("select * from #_category where id_loai={$item1['id_code']} and hien_thi=1 "._where_lang." order by so_thu_tu asc, id desc");
                if(count($sub1)>0){
                    $menusub2="";
                    foreach ($sub1 as $key1 => $item2) {
                        $menusub2.='<li><a href="'._URLLANG.$item2['alias'].'.html">'.$item2['ten'].'</a></li>';
                    }
                    $menusub.='
                        <li  class="sub-nav">
                            <a href="'._URLLANG.$item1['alias'].'.html" title="'.$item1['ten'].'">'.$item1['ten'].'</a>
                            <ul>'.$menusub2.'</ul>
                        </li>'; 
                }  else {
                   $menusub.='<li><a href="'._URLLANG.$item1['alias'].'.html" title="'.$item1['ten'].'">'.$item1['ten'].'</a></li>'; 
                }
                
            }
            $menu.='<li class="dropdown">
                        <a href="'._URLLANG.$item['alias'].'.html" title="'.$item['ten'].'">'.$item['ten'].' <span class="caret"></span></a>
                        <ul class="dropdown-menu fadeInUp">
                            '.$menusub.'
                        </ul>
                    </li>';
        }  else {
            
            $menu.='<li ><a href="'._URLLANG.$item['alias'].'.html">'.$item['ten'].'</a></li>';
        }
        $delay= $delay+0.1;
    }
    echo $menu;
?>
