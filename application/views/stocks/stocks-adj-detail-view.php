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
            <span class="input-group-text" id=""><?=$this->lang->line("adjustment_number")?></span>
        </div>            
        <input type="text" class="form-control" id="i-adj-num" value="<?=$adj_num?>" disabled>
    </div>
    <div class="input-group mb-2 input-group-sm">
        <div class="input-group-prepend">
            <span class="input-group-text" id=""><?=$this->lang->line("adjustment_reference_number")?></span>
        </div>
        <input type="text" class="form-control" id="i-refer-num" value="<?=$refer_num?>" disabled>
    </div>
    <div class="input-group mb-2 input-group-sm">
        <div class="input-group-prepend">
            <span class="input-group-text" id=""><?=$this->lang->line("date")?></span>
        </div>
        <input type="text" class="form-control" id="i-date" value="<?=$date?>" disabled>
    </div>
    <table class="table table-sm table-striped" id="items-table">
        <thead>
            <tr>
                <th scope="col-1">#</th>
                <th scope="col-1"><?=$this->lang->line("item_code")?></th>
                <th scope="col-2"><?=$this->lang->line("item_eng_name")?></th>
                <th scope="col-2"><?=$this->lang->line("item_chi_name")?></th>
                <th scope="col-1"><?=$this->lang->line("item_Stockonhand")?></th>
                <th scope="col-1"><?=$this->lang->line("item_qty")?></th>
                <th scope="col-1"><?=$this->lang->line("item_unit")?></th>
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
         <textarea  class="form-control" rows="3" id="i-remark" placeholder="<?=$this->lang->line("item_remark")?>"><?=$remark?></textarea>
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