<script>
$(document).ready(function(){
    $('.tree-toggle').click(function () {	
        $(this).next('div.tree').toggle(
            function(){
            
            },function(){
            
            });
    });
});
$(function(){
    $('.tree-toggle').next('div.tree').toggle(0);
    //$('a.active').parent().toggle(0);
});
</script>

<div class="list-group">

    <?php 

     
   
    foreach($sideNav as $parent => $val):
        if(empty($parent))
        {
         $order = 1;
            // go through no parent item/s on menu
            foreach($sideNav[$parent] as $k => $v):
?>
            <a href="<?=base_url($sideNav[$parent][$order]["slug"]);?>" 
            class="list-group-item list-group-item-action <?php echo $sideNav[$parent][$order]["active"]? 'active' : ''?> ">
            <span class="mr-4"></span><?=$sideNav[$parent][$order]["name"]?>
            </a>
<?php
                $order++;
            endforeach;
        }
        else
        {
?>
            <a class='list-group-item list-group-item-dark tree-toggle '>
                <i class='fas fa-angle-right'></i><span class='mr-3'></span><?=$parent?>
            </a>
            <div class="list-group tree">
<?php
            $order = 1;
            // menu child
            foreach($sideNav[$parent] as $k => $v):
?>
                <a href="<?=base_url($sideNav[$parent][$order]["slug"]);?>" 
                class="list-group-item list-group-item-action tree-toggle <?php echo $sideNav[$parent][$order]["active"]? 'active' : ''?> ">
                    <span class="mr-4"></span><?=$sideNav[$parent][$order]["name"]?>
                </a>
<?php
                $order++;
            endforeach;
?>
            </div>
<?php
        }
    endforeach;
?>

</div>