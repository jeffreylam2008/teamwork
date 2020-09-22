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
            <span class="input-group-text" >Purchases Number</span>
        </div>
        
        <input type="text" class="form-control" value="<?=$purchasenum?>" disabled />
    </div>
    <div class="input-group mb-2 input-group-sm">
        <div class="input-group-prepend">
            <span class="input-group-text" >Reference Number</span>
        </div>
        
        <input type="text" class="form-control" value="<?=$refernum?>" disabled />
    </div>
    <div class="input-group mb-2 input-group-sm">
        <div class="input-group-prepend">
            <span class="input-group-text" >Date</span>
        </div>
        <input type="text" class="form-control" value="<?=$date?>" disabled />
    </div>
    <!-- Company -->
    <div class="input-group mb-2 input-group-sm">
        <div class="input-group-prepend">
            <span class="input-group-text">Company</span>
        </div>
        <input type="text" class="form-control" value="(<?=$shopcode?>) <?=$shopname?>" disabled />
    </div>
    <div class="input-group mb-2 input-group-sm">
        <div class="input-group-prepend">
            <span class="input-group-text">Supplier</span>
        </div>
        <input type="text" class="form-control" value="(<?=$suppcode?>) <?=$suppname?>" disabled />
    </div>

    <!-- Payment Method -->
    <div class="input-group mb-2 input-group-sm">
        <div class="input-group-prepend">
            <label class="input-group-text">Payment Method</label>
        </div>
        <input type="text" class="form-control" value="(<?=$paymentmethod?>) <?=$paymentmethodname?>" disabled />
    </div>

    <table class="table table-sm table-striped" id="items-table">
        <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">Item Code</th>
                <th scope="col">English Name</th>
                <th scope="col">Chinese Name</th>
                <th scope="col">Qty</th>
                <th scope="col">Unit</th>
                <th scope="col">Unit Price</th>
                <th scope="col">Total</th>
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
                <td>$<?=number_format($price,2,".","")?></td>
                <td>$<?=number_format($subtotal,2,".","")?></td>
                <!--<td><button class='btn btn-danger btn-sm' id='item-del' type='button'>X</button></td>-->
            </tr>
        <?php
            $index++; 
            endforeach;
        ?>
            <tr>
                <td colspan="6"></td>
                <td align="right">Total: </td>
                <td>$<?=$total?></td>
            </tr>
        </tbody>
    </table>
    
    <div class="input-group mb-2 input-group-sm">
        <textarea  class="form-control" rows="10" placeholder="Remark" disabled><?=$remark?></textarea>
    </div>
</div>

<script>

// $("#preview").on("click",function(){
//     window.open('<?=$preview_url?>', '_blank', 'location=yes,height=900,width=800,scrollbars=yes,status=yes');
// })
// $("#save").on("click",function(){
//     window.open('<?=$print_url?>', '_blank', 'location=yes,height=900,width=800,scrollbars=yes,status=yes');
// })
// $("#reprint").on("click",function(){
//     window.open('<?=$print_url?>', '_blank', 'location=yes,height=900,width=800,scrollbars=yes,status=yes');
// })

</script>