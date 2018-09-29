<?php

// $last
// $current
// $data
if(isset($last)):
?>
<nav aria-label="Page navigation">
  <ul class="pagination pagination-sm justify-content-center">
    
    <?php 
    if($current == 1):
    ?>
        <li class="page-item disabled"><a class="page-link" href="<?=base_url('/products/items/page/1')?>">|<</a></li>
        <li class="page-item disabled"><a class="page-link"  href="<?=base_url('/products/items/page/'.($current-1))?>"><</a></li>
    <?php
    else:
    ?>
        <li class="page-item"><a class="page-link" href="<?=base_url('/products/items/page/1')?>">|<</a></li>
        <li class="page-item"><a class="page-link"  href="<?=base_url('/products/items/page/'.($current-1))?>"><</a></li>
    <?php    
    endif;
    ?>
        <?php
        // this is view file with only render HTML page
        for($m=0; $m<count($data); $m++)
        {
            if($current == ($data[$m]))
            {
                echo "<li class='page-item active'><a class='page-link'>".$current."</a></li>";
            }
            else
            {
                echo "<li class='page-item'><a class='page-link' href='".base_url('/products/items/page/'.$data[$m])."'>".$data[$m]."</a></li>";
            }
        }
        ?>
    <?php
    if($current == $last):
    ?>
        <li class="page-item disabled"><a class="page-link" href="<?=base_url('/products/items/page/'.($current+1))?>">></a></li>
        <li class="page-item disabled"><a class="page-link" href="<?=base_url('/products/items/page/'.$last)?>">>|</a></li>
    <?php
    else:
    ?>
        <li class="page-item"><a class="page-link" href="<?=base_url('/products/items/page/'.($current+1))?>">></a></li>
        <li class="page-item"><a class="page-link" href="<?=base_url('/products/items/page/'.$last)?>">>|</a></li>
    <?php
    endif;
    ?>
    
  </ul>
</nav>
<?php
endif;
?>