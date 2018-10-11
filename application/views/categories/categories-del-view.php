
<div class="card">
    <div class="card-header">
        <?php
        if(!$data['query'])
		{
        ?>
            <h2> Are you sure to delete item code: <u><?=$cate_code?></u></h2>
		<?php
        }
		else
		{
        ?>
            <h2> Total <?=$data['error']['message'];?>. Cannot delete category code: <u><?=$cate_code?></u></h2>
        <?php
        }
        ?>
    </div>
</div>
