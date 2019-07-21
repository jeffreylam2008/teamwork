
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
        $i = 0;
        foreach($data['query']['data'] as $k => $v):

            if($k % 2 == 0):

        ?>
                <a href="<?=$trans_url?><?=$v['trans_code']?>"><?=$v['trans_code']?></a>
        <?php
            endif;
            $i++;
        endforeach;
        $total = $k >= 2 ? $i - 2 : 0;
        ?>
            (<?=$total?> transaction) contain this customer. Cannot delete this customer:  <u><?=$cust_code?></u>
            </h2>
        <?php
        }
        ?>
    </div>
</div>