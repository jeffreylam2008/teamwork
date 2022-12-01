<div>
    <?php 
        // echo "<pre>";
        // var_dump($data);
        // echo "</pre>";
        extract($data);
    ?>
</div>
<div class="container-fluid">

        <!-- Suppliers -->
        <div class="input-group mb-2 input-group-sm">
            <div class="input-group-prepend">
                <span class="input-group-text"><?=$this->lang->line("dn_number")?></span>
            </div>
            <input type="text" class="form-control" value="<?=$dn_num?>" id="i-grn-num" disabled="" />
        </div>

        <div class="input-group mb-2 input-group-sm">
            <div class="input-group-prepend">
                <span class="input-group-text" id=""><?=$this->lang->line("dn_reference_number")?></span>
            </div>
            <input type="text" class="form-control" id="i-po-num" value="<?=$refer_num?>" disabled="" />
        </div>

        <div class="input-group mb-2 input-group-sm">
            <div class="input-group-prepend">
                <span class="input-group-text" id=""><?=$this->lang->line("date")?></span>
            </div>
            <input type="text" class="form-control" id="i-date" value="<?=$date?>" disabled >
        </div>

        <!-- Company -->
        <div class="input-group mb-2 input-group-sm">
            <div class="input-group-prepend">
                <label class="input-group-text"><?=$this->lang->line("company")?></label>
            </div>
          
            <select class="custom-select custom-select-sm" id="i-shopcode" disabled>
                <option value="<?=$shopcode?>"><?=$shopname?></option>
            </select>
           
        </div>

        
        <!-- Customer Modal button -->
        <div class="input-group mb-2 input-group-sm">
            <div class="input-group-prepend">
                <span class="input-group-text"><?=$this->lang->line("customer_name")?></span>
            </div>
            <input type="text" class="form-control" value="<?=$data['cust_code']?>" id="i-customer" disabled="" />
            <input type="text" class="form-control" value="<?=$data['cust_name']?>" id="i-customer-name" disabled="">
        </div>
        <!-- Customer Modal button END -->

        
        <!-- Payment Method button -->
        <div class="input-group mb-2 input-group-sm">
            <div class="input-group-prepend">
                <label class="input-group-text"><?=$this->lang->line("payment_method")?></label>
            </div>
            <select class="custom-select custom-select-sm" id="i-paymentmethod" disabled>
                <?php if(!empty($paymentmethod) && $paymentmethod != -1): ?>
                    <option value="<?=$paymentmethod?>"><?=$paymentmethodname?></option>
                <?php else: ?>
                    <option value="-1">N/A</option>
                <?php endif; ?>
            </select>
        </div>
        <!-- Payment Method button END-->

        <!-- Products -->
        <div class="input-group mb-2 input-group-sm">
            <!-- items Modal -->
            <?php include(APPPATH."views/modal-items.php"); ?>
            <!-- items Modal End -->
            <!--<input type="text" class="form-control item-input" placeholder="items code">
            <div class="input-group-append">
                <button class="btn btn-outline-secondary btn-sm" type="button" id="item-search">Search</button>
            </div>
            <button type="button" class="btn btn-secondary btn-sm" data-toggle="modal" data-target="#items_modal">More...</button>-->
        </div>
        <table class="table table-sm table-striped" id="tbl">
            <thead>
                <th><?=$this->lang->line("item_code")?></th>
                <th><?=$this->lang->line("item_eng_name")?></th>
                <th><?=$this->lang->line("item_chi_name")?></th>
                <th><?=$this->lang->line("dn_previous_stock")?></th>
                <th><?=$this->lang->line("item_qty")?></th>
                <th><?=$this->lang->line("item_unit")?></th>

            </thead>
            <!-- render items-list here -->
            <tbody id="tdisplay">
            <?php
                foreach ($items as $k=> $v){
                    extract($v);
            ?>
                <tr data-items="item_<?=$k?>">
                    <td class="col-1" ><?=$item_code?></td>
                    <td class="col-2" ><?=$eng_name?></td>
                    <td class="col-2" ><?=$chi_name?></td>
                    <td class="col-1"><?=$stockonhand?></td>
                    <td class="col-sm-1" >
                        <input type="text" class="form-control form-control-sm item-input" id="qty_<?=$k?>" value="<?=$qty?>" disabled />
                    </td>
                    <td class="col-1"><?=$unit?></td>
                </tr>
            <?php
                }
            ?>
             <tbody>
        </table>

        <div class="input-group mb-2 input-group-sm">
            <textarea  class="form-control" rows="3" id="i-remark" placeholder="Remark" disabled><?=$remark?></textarea>
        </div>
        <input type="hidden" name="i-post" id="i-post" value="" />
        <input type="hidden" name="i-prefix" id="i-prefix" value="<?=$prefix?>" />
        <input type="hidden" name="i-employeecode" id="i-employeecode" value="<?=$employee_code?>" />
        <input type="hidden" name="i-form-type" id="i-form-type" value="create" />
</div>


<script>
// unload and redirect
function doUnLoad(){
    // console.log(document.activeElement.href);
    // while unload then redirect
    $(window).on('unload', function(e){
        e.preventDefault();
        // to fix page refresh
        if(typeof document.activeElement.href !== "undefined")
        {
            // target url defined then discard current session
            fetch("<?=$discard_url?>").then(function(response) {
                if(response.ok){
                    window.location.replace(document.activeElement.href);
                    window.onbeforeunload = null;
                }
            });
        }
    });
}
// unload window
$(window).on('beforeunload', function(){
    doUnLoad();
    return "Any changes will be lost";
});
// the button to free page unload
$("#back, #save, #preview, #reprint, #discard").on("click", function(){
    $(window).off('beforeunload');
});
$("#preview").on("click",function(){
    window.open('<?=$preview_url?>', '_blank', 'location=yes,height=900,width=1000,scrollbars=yes,status=yes');
})
$("#reprint").on("click",function(){
    window.open('<?=$print_url?>', '_blank', 'location=yes,height=900,width=1000,scrollbars=yes,status=yes');
})

</script>