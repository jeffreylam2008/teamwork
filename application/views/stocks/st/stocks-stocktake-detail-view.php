<div>
    <?php
        // echo "<pre>";
        // var_dump($data);
        // echo "</pre>";
        extract($data);
    ?>

</div>

<div class="container-fluid">

    <div class="input-group mb-2 input-group-sm">
        <div class="input-group-prepend">
            <span class="input-group-text" id="">Transaction Number</span>
        </div>            
        <input type="text" class="form-control" id="i-adj-num" value="<?=$trans_code?>" disabled>
    </div>
    <div class="input-group mb-2 input-group-sm">
        <div class="input-group-prepend">
            <span class="input-group-text" id="">Date</span>
        </div>
        <input type="text" class="form-control" id="i-date" value="<?=$date?>" disabled>
    </div>
    <table class="table table-sm table-striped" id="items-table">
        <thead>
            <tr>
                <th scope="col-1">#</th>
                <th scope="col-1">Item Code</th>
                <th scope="col-2">English Name</th>
                <th scope="col-2">Chinese Name</th>
                <th scope="col-1">Current Stockonhand</th>
                <th scope="col-1">Qty</th>
                <th scope="col-1">Unit</th>
            </tr>
        </thead>
        <!-- render items-list here -->
        <tbody id="render-items">
        <?php 
            if(!empty($items))
            {
                $index = 1;
                foreach($items as $k => $v):
                    extract($v);
        ?>
            <tr>
                <td class="col-1"><?=$index?></th>
                <td class="col-1"><?=$item_code?></td>
                <td class="col-2"><?=$eng_name?></td>
                <td class="col-2"><?=$chi_name?></td>
                <td class="col-1"><?=$stockonhand?></td>
                <td class="col-1"><?=$qty?></td>
                <td class="col-1"><?=$unit?></td>
            </tr>
        <?php
                $index++; 
                endforeach;
            }
        ?>
        </tbody>
    </table>
    
    <div class="input-group mb-2 input-group-sm">
         <textarea  class="form-control" rows="3" id="i-remark" placeholder="Remark"><?=$remark?></textarea>
    </div>
</div>

<script>

$("#preview").on("click",function(){
    window.open('<?=$preview_url?>', '_blank', 'location=yes,height=900,width=800,scrollbars=yes,status=yes');
})
$("#save").on("click",function(){
    window.open('<?=$print_url?>', '_blank', 'location=yes,height=900,width=800,scrollbars=yes,status=yes');
})

</script>