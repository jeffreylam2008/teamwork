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
                <span class="input-group-text"><?=$this->lang->line("grn_number")?></span>
            </div>
            <input type="text" class="form-control" value="<?=$grn_num?>" id="i-grn-num" disabled="" />
        </div>

        <div class="input-group mb-2 input-group-sm">
            <div class="input-group-prepend">
                <span class="input-group-text" id=""><?=$this->lang->line("grn_reference_number")?></span>
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
                <option value="<?=$shop_code?>"><?=$shopname?></option>
            </select>
        </div>

        <!-- Start Suppliers -->
        <div class="input-group mb-2 input-group-sm">
            <div class="input-group-prepend">
                <span class="input-group-text"><?=$this->lang->line("supplier")?></span>
            </div>
            <input type="text" class="form-control" value="<?=$supp_code?>" id="i-suppliers" disabled="" />
            <input type="text" class="form-control" value="<?=$supp_name?>" id="i-suppliers-name" disabled="">
        </div>
        <!-- End Suppliers -->
        
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
                <?php 
                    foreach($ajax["tender"] as $k => $v):
                ?>
                        <option value="<?=$v['pm_code']?>"><?=$v['payment_method']?></option>
                <?php
                    endforeach;
                ?>
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
                <th></th>
                <th><?=$this->lang->line("item_qty")?></th>
                <th></th>
                <th><?=$this->lang->line("item_unit")?></th>
                <th><?=$this->lang->line("item_price")?></th>
                <th><?=$this->lang->line("item_subtotal")?></th>
            </thead>
            <!-- render items-list here -->
            <tbody id="tdisplay">
            <?php
                $total = 0;
                $subtotal = 0;
                foreach ($items as $k=> $v){
                    extract($v);
                    $subtotal = $qty * $price;
            ?>
                <tr data-items="item_<?=$k?>">
                    <td class="col-1" ><?=$item_code?></td>
                    <td class="col-2" ><?=$eng_name?></td>
                    <td class="col-2" ><?=$chi_name?></td>
                    <td class="col-1 clearfix" >
                        <!--<input type="button" class='btn btn-secondary btn-sm w-70 float-right' id="minus_<?=$k?>" value="-" />-->
                    </td>
                    <td class="col-sm-1" >
                        <input type="text" class="form-control form-control-sm item-input" id="qty_<?=$k?>" value="<?=$qty?>" disabled />
                    </td>
                    <td class="col-1">
                        <!--<input type="button" class='btn btn-secondary btn-sm w-70' id="plus_<?=$k?>" value="+" />-->
                    </td>
                    <td class="col-1"><?=$unit?></td>
                    <td class="col-1">
                       <input type="text" class="form-control form-control-sm item-input" id="price_<?=$k?>" value="<?=$price?>" disabled />
                    </td>
                    <td class="col-2" id="subtotal_<?=$k?>">$ <?=$subtotal?></td>
                </tr>
            <?php
                    $total += $subtotal;
                }
            ?>
             <tbody>
        </table>
        <table class="table table-sm table-striped" id="tbl-total">
            <tbody>
                <tr>
                    <td class="col-sm-8"></td>
                    <td class="col-sm-2" align="right">Total: </td>
                    <td id="total">$ <?=number_format($total,2,".",",")?></td>
                </tr>
            </tbody>        
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

$("#preview").on("click",function(){
    window.open('<?=$preview_url?>', '_blank', 'location=yes,height=900,width=800,scrollbars=yes,status=yes');
})
$("#reprint").on("click",function(){
    window.open('<?=$print_url?>', '_blank', 'location=yes,height=900,width=800,scrollbars=yes,status=yes');
})

</script>