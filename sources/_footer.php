<?php $chantrang = $d->getContents(52) ?>
<footer>
    <div class=" container">
        <div class="row">
            <div class="col-lg-4 col-md-4">
                <h3 class="title_f"><?= $chantrang[0]['ten'] ?></h3>
                <div class="footer-content">
                    <?= $chantrang[0]['noi_dung'] ?>
                </div>
                <div class="mxh-f">
                    <a href="<?= _facebook ?>" title="Facebook">
                        <i class="fab fa-facebook-f"></i>
                    </a>
                    <a href="<?= _twitter ?>" title="Twitter">
                        <i class="fab fa-twitter"></i>
                    </a>
                    <a href="<?= _youtube ?>" title="Youtube">
                        <i class="fab fa-youtube"></i>
                    </a>
                    <a href="<?= _instagram ?>" title="Instagram">
                        <i class="fab fa-instagram"></i>
                    </a>
                </div>
            </div>
            <div class="col-lg-8 col-md-8">
                <div class="row">
                    <?php foreach ($chantrang as $key => $value) {
                        if ($key > 0) {
                    ?>
                            <div class="col-lg-4 col-md-4">
                                <h3 class="title_f"><?= $value['ten'] ?></h3>
                                <div class="footer-content">
                                    <?= $value['noi_dung'] ?>
                                </div>
                            </div>
                    <?php }
                    } ?>
                </div>
            </div>
        </div>
        <hr>
        <div class="chantrang text-center">
            <?= _copyright ?>
        </div>
    </div>
</footer>