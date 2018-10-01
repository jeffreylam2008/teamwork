
<div class="card">
    <div class="card-header">
        <?php
        if(!$data['query'])
		{
        ?>
            <h2> Are you sure to delete item code: <u><?=$item_code?></u></h2>
		<?php
        }
		else
		{
        ?>
            <h2> Transaction : <a href="<?=$trans_url?>"><?=$data['query']['trans_code']?></a> has this item. Cannot delete item code: <u><?=$item_code?></u></h2>
        <?php
        }
        ?>
    </div>
</div>