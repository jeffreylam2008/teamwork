<div>
    <?php 
       
        $data = json_decode($_POST["i-post"],true);
        // echo "<pre>";
        // var_dump($data);
        // echo "</pre>";
        extract($data);

    ?>

</div>

<div class="container-fluid">

    <div class="input-group mb-2 input-group-sm">
        <div class="input-group-prepend">
            <span class="input-group-text" ><?=$this->lang->line("grn_number")?></span>
        </div>
        
        <input type="text" class="form-control" value="<?=$grn_num?>" disabled />
    </div>
    <div class="input-group mb-2 input-group-sm">
        <div class="input-group-prepend">
            <span class="input-group-text" ><?=$this->lang->line("purchase_number")?></span>
        </div>
        <input type="text" class="form-control" value="<?=$po_num?>" disabled />
    </div>
    <div class="input-group mb-2 input-group-sm">
        <div class="input-group-prepend">
            <span class="input-group-text" ><?=$this->lang->line("date")?></span>
        </div>
        <input type="text" class="form-control" value="<?=$date?>" disabled />
    </div>
    <!-- Company -->
    <div class="input-group mb-2 input-group-sm">
        <div class="input-group-prepend">
            <span class="input-group-text"><?=$this->lang->line("company")?></span>
        </div>
        <input type="text" class="form-control" value="(<?=$shop_code?>) <?=$shopname?>" disabled />
    </div>
    <div class="input-group mb-2 input-group-sm">
        <div class="input-group-prepend">
            <span class="input-group-text"><?=$this->lang->line("supplier")?></span>
        </div>
        <input type="text" class="form-control" value="(<?=$supp_code?>) <?=$supp_name?>" disabled />
    </div>

    <!-- Payment Method -->
    <div class="input-group mb-2 input-group-sm">
        <div class="input-group-prepend">
            <label class="input-group-text"><?=$this->lang->line("payment_method")?></label>
        </div>
        <?php if($paymentmethod != -1) :?>
        <input type="text" class="form-control" value="(<?=$paymentmethod?>) <?=$paymentmethodname?>" disabled />
        <?php else:?>
        <input type="text" class="form-control" value="N/A" disabled />
        <?php endif;?>
    </div>

    <table class="table table-sm table-striped" id="items-table">
        <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col"><?=$this->lang->line("item_code")?></th>
                <th scope="col"><?=$this->lang->line("item_eng_name")?></th>
                <th scope="col"><?=$this->lang->line("item_chi_name")?></th>
                <th scope="col"><?=$this->lang->line("item_Stockonhand")?></th>
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
                <td><?=$stockonhand?></td>
                <td><?=$qty?></td>
                <td><?=$unit?></td>
                <td>$<?=number_format($price,2,".","")?></td>
                <td>$<?=number_format($subtotal,2,".","")?></td>
                <!--<td><button class='btn btn-danger btn-sm' id='item-del' type='button'>X</button></td>-->
            </tr>
        <?php
            $index++; 
            endforeach;
        ?>
            <tr>
                <td colspan="7"></td>
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
    $("#back, #discard").on("click", function(){
        doUnLoad();
        $(window).off('beforeunload');
    });
    // Preview print pop up window 
    $("#preview").on("click",function(){
        $(window).off('beforeunload');
        window.open('<?=$preview_url?>', '_blank', 'location=yes,height=900,width=800,scrollbars=yes,status=yes');
    })
    // Print receipt pop up window
    $("#save").on("click",function(){
        $(window).off('beforeunload');
        window.open('<?=$print_url?>', '_blank', 'location=yes,height=900,width=800,scrollbars=yes,status=yes');
    })


</script>