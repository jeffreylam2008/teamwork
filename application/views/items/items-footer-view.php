<?php
    extract($items['query']);
?>


<div class="row justify-content-md-center">
    <?php
        if($previous_btn_show):
    ?>
    <div class="col col-lg-2">
        <a href="<?=$url.$previous?>" class="btn btn-primary btn-sm active" role="button" aria-pressed="true">|<</a>
    </div>
    <div class="col-md-auto">
    <?php
        endif;
    ?>
    </div>
    <div class="col col-lg-2">
        <a href="<?=$url.$next?>" class="btn btn-primary btn-sm active" role="button" aria-pressed="true">>|</a>
    </div>
</div>
