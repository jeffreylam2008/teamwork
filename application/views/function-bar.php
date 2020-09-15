<nav class="navbar navbar-light bg-light">
    <div class="row">
    <?php
        foreach($btn as $k => $v):
            if($v['show']):
    ?>
        <a class="<?php echo $v['style'] == '' ? "btn btn-outline-primary" : $v['style'];?> btn-sm"
        href="<?=$v['url']?>" 
        type="<?=$v['type']?>"
        id="<?=$v['id']?>"
        <?php 
            if(!empty($v['extra']) && isset($v['extra'])){
                echo $v['extra'];
            }
        ?>
        >
        <?=$v['name']?>
        </a>&nbsp;
    <?php
            endif;
        endforeach;

        if(isset($select))
        {
            var_dump($select);
        }
        
    ?>
    </div>
</nav>