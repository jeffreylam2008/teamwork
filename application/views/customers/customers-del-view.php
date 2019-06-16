
<div class="card">
    <div class="card-header">
        <?php
        if(!($data['query']['has']))
		{
        ?>
            <h2> Are you sure to delete : <u><?=$cust_code?></u></h2>
		<?php
        }
		else
		{
        ?>
            <h2> Transaction : 
        <?php
         foreach($data['query']['data'] as $k => $v):
        ?>
            <a href="">
                <?=$v['trans_code']?>
            </a>
        <?php
        endforeach;
        ?>
             has this item. Cannot delete item code: 
            <u><?=$cust_code?></u>

        
            </h2>
        <?php
        }
        ?>
    </div>
</div>