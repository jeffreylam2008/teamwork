<div>
    <?php 
        
        // $data = json_decode($_POST["i-post"],true);
        // echo "<pre>";
        // var_dump($_POST);
        // echo "</pre>";
        extract($data);

    ?>

</div>

<div class="container-fluid">

    <div class="input-group mb-2 input-group-sm">
        <div class="input-group-prepend">
            <span class="input-group-text"><?=$this->lang->line("invoice_number")?></span>
        </div>
        
        <input type="text" class="form-control" value="<?=$trans_code?>" disabled />
    </div>
    <div class="input-group mb-2 input-group-sm">
        <div class="input-group-prepend">
            <span class="input-group-text" ><?=$this->lang->line("quotation_number")?></span>
        </div>
        <input type="text" class="form-control" value="<?=$quotation?>" disabled />
    </div>
    <div class="input-group mb-2 input-group-sm">
        <div class="input-group-prepend">
            <span class="input-group-text" ><?=$this->lang->line("invoice_date")?></span>
        </div>
        <input type="text" class="form-control" value="<?=$date?>" disabled />
    </div>
    <!-- Company -->
    <div class="input-group mb-2 input-group-sm">
        <div class="input-group-prepend">
            <span class="input-group-text"><?=$this->lang->line("company")?></span>
        </div>
        <input type="text" class="form-control" value="(<?=$shopcode?>) <?=$shopname?>" disabled />
    </div>
    <div class="input-group mb-2 input-group-sm">
        <div class="input-group-prepend">
            <span class="input-group-text"><?=$this->lang->line("customer")?></span>
        </div>
        <input type="text" class="form-control" value="(<?=$cust_code?>) <?=$cust_name?>" disabled />
    </div>

    <!-- Payment Method -->
    <div class="input-group mb-2 input-group-sm">
        <div class="input-group-prepend">
            <label class="input-group-text"><?=$this->lang->line("customer_payment_method")?></label>
        </div>
        <input type="text" class="form-control" value="(<?=$paymentmethod?>) <?=$paymentmethodname?>" disabled />
    </div>

    <table class="table table-sm table-striped" id="items-table">
        <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col"><?=$this->lang->line("item_code")?></th>
                <th scope="col"><?=$this->lang->line("item_eng_name")?></th>
                <th scope="col"><?=$this->lang->line("item_chi_name")?></th>
                <th scope="col"><?=$this->lang->line("item_qty")?></th>
                <th scope="col"><?=$this->lang->line("item_unit")?></th>
                <th scope="col"><?=$this->lang->line("item_price")?></th>
                <th scope="col"><?=$this->lang->line("item_subtotal")?></th>
                <th scope="col"></th>
            </tr>
        </thead>
        <!-- render items-list here -->
        <tbody id="render-items">
        <?php 
            $index = 1;
            foreach($items as $k => $v):
                extract($v);
        ?>
            <tr>
                <td scope="row"><?=$index?></th>
                <td><?=$item_code?></td>
                <td><?=$eng_name?></td>
                <td><?=$chi_name?></td>
                <td><?=$qty?></td>
                <td><?=$unit?></td>
                <td>$<?=$price?></td>
                <td>$<?=$subtotal?></td>
                <!--<td><button class='btn btn-danger btn-sm' id='item-del' type='button'>X</button></td>-->
            </tr>
        <?php
            $index++; 
            endforeach;
        ?>
            <tr>
                <td colspan="6"></td>
                <td align="right"><?=$this->lang->line("common_total")?>: </td>
                <td>$<?=$total?></td>
            </tr>
        </tbody>
    </table>
    
    <div class="input-group mb-2 input-group-sm">
        <textarea  class="form-control" rows="10" placeholder="<?=$this->lang->line("item_remark")?>" disabled><?=$remark?></textarea>
    </div>
</div>

<script>

$("#preview").on("click",function(){
    window.open('<?=$preview_url?>', '_blank', 'location=yes,height=1000,width=1100,scrollbars=yes,status=yes');
})
$("#save").on("click",function(){
    window.open('<?=$print_url?>', '_blank', 'location=yes,height=1000,width=1100,scrollbars=yes,status=yes');
})
$("#reprint").on("click",function(){
    window.open('<?=$print_url?>', '_blank', 'location=yes,height=1000,width=1100,scrollbars=yes,status=yes');
})

</script>