<?php
// echo "<pre>";
// var_dump($data);
// echo "</pre>";  
extract($data); 
?>

<div class="container-fluid">
    <form class="" method="POST" id="this-form" action="<?=$submit_to?>">
        <div class="input-group mb-2 input-group-sm">
            <div class="input-group-prepend">
                <span class="input-group-text" id="">PO Number</span>
            </div>            
            <input type="text" class="form-control" id="i-purchasesnum" value="<?=$trans_code?>" disabled>
        </div>
        <div class="input-group mb-2 input-group-sm">
            <div class="input-group-prepend">
                <span class="input-group-text" id="">Reference Number</span>
            </div>
            <input type="text" class="form-control" id="i-refernum" value="<?=$refernum?>" disabled>
        </div>
        <div class="input-group mb-2 input-group-sm">
            <div class="input-group-prepend">
                <span class="input-group-text" id="">Date</span>
            </div>
            <input type="text" class="form-control" id="i-date" value="<?=$date?>" disabled>
        </div>
        <!-- Company -->
        <div class="input-group mb-2 input-group-sm">
            <div class="input-group-prepend">
                <label class="input-group-text">Company</label>
            </div>
            <?php
                if(!empty($ajax["shop_code"])):
                    $key = array_search($default_shopcode,array_column($ajax["shop_code"],"shop_code"));
            ?>
            <select class="custom-select custom-select-sm" id="i-shopcode" <?=($show===true) ? "" : "disabled"?>>
                <?php if(!empty($shopcode)): ?>
                    <option value="<?=$shopcode?>"><?=$shopname?></option>
                <?php else: ?>
                    <option value="<?=$ajax["shop_code"][$key]['shop_code']?>"><?=$ajax["shop_code"][$key]['name']?></option>
                <?php endif; ?>
                <?php
                    foreach($ajax["shop_code"] as $k => $v):
                ?>
                        <option value="<?=$v['shop_code']?>"><?=$v['name']?></option>
                <?php
                    endforeach;
                ?>
            </select>
            <?php endif;?>
        </div>
        
        <!-- ///////////////////////////////////////////////////////////////////////////////////////////////////////////////// -->
        <!-- supplier Modal -->
        <?php include(APPPATH."views/modal-suppliers.php"); ?>
        <!-- supplier Modal End -->
        
        <!-- supplier Modal button -->
        <div class="input-group mb-2 input-group-sm">
            <div class="input-group-prepend">
                <span class="input-group-text">Suppliers</span>
            </div>
            <input type="text" class="form-control" value="<?=$data['supp_code']?>" id="i-suppliers" disabled="" />
            <input type="text" class="form-control" value="<?=$data['supp_name']?>" id="i-suppliers-name" disabled="">
            <?php if($show===true) :
                echo "<button type='button' class='btn btn-secondary btn-sm' data-toggle='modal' data-target='#suppliers_modal'>More...</button>";
            endif;?>
        </div>
        <!-- supplier Modal button END -->
        <!-- Payment Method button -->
        <div class="input-group mb-2 input-group-sm">
            <div class="input-group-prepend">
                <label class="input-group-text">Payment Method</label>
            </div>
            <select class="custom-select custom-select-sm" id="i-paymentmethod" <?=($show===true) ? "" : "disabled"?>>
                <?php if(!empty($paymentmethod)): ?>
                    <option value="<?=$paymentmethod?>"><?=$paymentmethodname?></option>
                <?php else: ?>
                    <option value="-1">Choose...</option>
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

        <!-- Product Search Button -->
        <div class="input-group mb-2 input-group-sm">
            <?php if($show===true) :?>
                <input type="text" class="form-control item-input" id="item-input" placeholder="items code">
                <div class="input-group-append">
                    <button class='btn btn-outline-secondary btn-sm' type='button' id='item-search'>Search</button>
                    <button type='button' class='btn btn-secondary btn-sm' data-toggle='modal' data-target='#items_modal'>More...</button>
                </div>
            <?php endif;?>
            
            <!-- items Modal -->
            <?php include(APPPATH."views/modal-items.php"); ?>
            <!-- items Modal End -->
        </div>
        <!-- Product Search Button END-->
        <!-- Product view -->
        <table class="table table-sm table-striped" id="tbl">
            <thead>
                <th>item code</th>
                <th>eng name</th>
                <th>chi_name</th>
                <th></th>
                <th>qty</th>
                <th></th>
                <th>unit</th>
                <th>price</th>
                <th>discount</th>
                <th>subtotal</th>
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
                        <input type="button" class='btn btn-secondary btn-sm w-70 float-right' id="minus_<?=$k?>" value="-" />
                    </td>
                    <td class="col-sm-1" >
                        <input type="text" class="form-control form-control-sm item-input" id="qty_<?=$k?>" value="<?=$qty?>" disabled />
                    </td>
                    <td class="col-1">
                        <input type="button" class='btn btn-secondary btn-sm w-70' id="plus_<?=$k?>" value="+" />
                    </td>
                    <td class="col-1"><?=$unit?></td>
                    <td class="col-1">
                        <input type="text" class="form-control form-control-sm item-input" id="price_<?=$k?>" value="<?=$price?>" />
                    </td>
                    <td class="col-1"><?=$price_special?></td>
                    <td class="col-2" id="subtotal_<?=$k?>"><?=$subtotal?></td>
                    <!-- <td class='col-1'><button class='btn btn-danger btn-sm w-90' data-del-itemcode='<?=$item_code?>' id='del_<?=$k?>' type='button'><i class='fas fa-trash-alt'></i></button></td> -->
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
                    <td class="col-sm-10"></td>
                    <td align="right">Total: </td>
                    <td id="total"><?php echo number_format($total,2,".","");?></td>
                </tr>
            </tbody>        
        </table>
        <!-- Product view END -->
        <!-- ///////////////////////////////////////////////////////////////////////////////////////////////////////////////// -->
        <div class="input-group mb-2 input-group-sm">
            <textarea  class="form-control" rows="3" id="i-remark" placeholder="Remark" <?=($show===true) ? "" : "disabled"?>><?=$remark?></textarea>
        </div>
        <input type="hidden" name="i-post" id="i-post" value="" />
        <input type="hidden" name="i-prefix" id="i-prefix" value="<?=$prefix?>" />
        <input type="hidden" name="i-employeecode" id="i-employeecode" value="<?=$employee_code?>" />
        <input type="hidden" name="i-void" id="i-void" value="<?=($show===true) ? "true" : "false"?>" />
        <input type="hidden" name="i-form-type" id="i-form-type" value="edit" />
    </form>
</div>

<script>
    var dbItems = <?=json_encode($ajax["items"],true)?>;
    var toVoid = <?php echo ($show===true) ? "true" : "false"; ?>;
    var items = []
    var selecteditemcode = ""
    var suppcode = "" 
    var suppname = ""
    var supp_pmcode = ""
    var ftotal = 0
    var suppTbl = $('#supp-list').DataTable({
        "select": {
            items: 'column'
        },
        "iDisplayLength": <?=$default_per_page?>,
    });
    var itemTbl =$('#items-list').DataTable({
        "select": {
            items: 'column'
        },
        "iDisplayLength": <?=$default_per_page?>,
    });

    /* /////////////////// START Libaray Session /////////////////////*/
    // search item from list
    function search(lookfor, arr){
        var a
        for (var i=0; i < arr.length; i++) {
            if (arr[i].item_code === lookfor) {
                arr[i].uid = arr[i].uid.toString()
                a = arr[i];
            }
        }
        return a
    }
    // update items from list
    function refresh(){
        $.each($("#tbl > tbody > tr"), function(i){
            items[i]= {
                "item_code": $(this).children()[0].innerText,
                "eng_name" : $(this).children()[1].innerText,
                "chi_name" : $(this).children()[2].innerText,
                "qty" :  parseInt($(this).children()[4].children[0].value),
                "unit":  $(this).children()[6].innerText,
                "price" : $(this).children()[7].children[0].value,
                "price_special" : parseFloat($(this).children()[8].innerText),
                "subtotal" : $(this).children()[9].innerText,
            }
        });
    }
    function render(){
        // rewrite template
        var tmpl=""
        for(item in items){
            var _price = parseFloat(items[item].price)
            var _subtotal = parseFloat(items[item].subtotal)
            tmpl += "<tr data-items='itmes_"+item+"'>"
            +"<td class='col-1'>"+items[item].item_code+"</td>"
            +"<td class='col-2'>"+items[item].eng_name+"</td>"
            +"<td class='col-2'>"+items[item].chi_name+"</td>"
            +"<td class='col-1 clearfix'><input type='button' class='btn btn-secondary btn-sm w-70 float-right' id='minus_"+item+"' value='-' /></td>"
            +"<td class='col-sm-1'><input type='text' class='form-control form-control-sm item-input' id='qty_"+item+"' value='"+items[item].qty+"' disabled /></td>"
            +"<td class='col-1'><input type='button' class='btn btn-secondary btn-sm w-70' id='plus_"+item+"' value='+' /></td>"
            +"<td class='col-1'>"+items[item].unit+"</td>"
            +"<td class='col-1'><input type='text' class='form-control form-control-sm item-input' id='price_"+item+"' value='"+_price.toFixed(2)+"' /></td>"
            +"<td class='col-1'>"+items[item].price_special+"</td>"
            +"<td class='col-1' id='subtotal_"+item+"'>"+_subtotal.toFixed(2)+"</td>"
            +"<td class='col-1'><button class='btn btn-danger btn-sm w-90' data-del-itemcode='"+items[item].item_code+"' id='del_"+item+"' type='button'><i class='fas fa-trash-alt'></i></button></td>"
            +"</tr>"
            //console.log(tmpl)
        }
        // reapply template
        $("#tdisplay").html(tmpl)
        
        // reapply table function 
        $.each($("#tbl > tbody > tr"), function(i){
            // qty update recalc function
            $('#qty_'+i).on("change", function(){
                recalc()
            });
            // price change function
            $('#price_'+i).on("change", function(){
                var price = parseFloat($(this).val())
                if(isNaN(price)){
                    $(this).val(0)
                }
                else{
                    $(this).val(price.toFixed(2))
                }
                recalc()

            });
            // minus function
            $('#minus_'+i).on("click", function(){
            
               var $qty = $('#qty_'+i)
                if(parseInt($qty.val()) > 1){
                    let new_qty = parseInt($qty.val()) - 1
                    $qty.val(new_qty)
                    recalc()
                }
            });
            // plus function
            $('#plus_'+i).on("click",function(){
                var $qty = $('#qty_'+i)
                if(parseInt( $qty.val()) >= 0 && parseInt( $qty.val()) < 10){
                    let new_qty = parseInt( $qty.val()) + 1
                    $qty.val(new_qty)
                    recalc()
                }
            });
            // remove function
            $('#del_'+i).on("click",function(){
                $(this).parent().parent().remove()
                // return index if found item in source
                let found = items.findIndex(o => o.item_code === $(this).data('del-itemcode'))
                items.splice(found,1)
                refresh()
                render()
                recalc()
            });
        });
    }
    // re-calcuate item list total after updated
    function recalc()
    {
        var total = 0
        $.each($("#tbl > tbody > tr"), function(i){
            var qty = parseInt($('#qty_'+i).val())
            var uprice = $('#price_'+i).val()
            var subtotal = qty * uprice
            total += subtotal
            $('#subtotal_'+i).text(subtotal.toFixed(2))
        });
        $('#total').text(total.toFixed(2))

        // get update value from existing table
        refresh()
        render()
    }
    
    $(window).on('beforeunload', function(){
        return "Any changes will be lost";
    });
    $(document).on("submit", "form", function(event){
        // disable unload warning
        $(window).off('beforeunload');
    });
    $("#Back, #copy, #discard, #grn").on("click", function(){
        $(window).off('beforeunload');
    });

    //construct
    $(document).ready(function(){
        refresh()
        if(toVoid){
            render()
        }
    });
    /** 
    * START suppliers_modal handler
    **/
    // handle Suppliers modal close while press enter
    $('body').on('shown.bs.modal', '#suppliers_modal', function () {
        $(this).on("keypress", function(e){
            if(e.keyCode==13){
                $("#supp-list > tbody > tr").each(function(i){
                    $(this).css("background-color","")
                })
                if(pmcode){
                    for( var _dom_sel of $("#i-paymentmethod > option") ){
                        if(_dom_sel.value == pmcode){
                            _dom_sel.selected = true
                        }
                    }
                    $("#i-customer").val(custcode)
                    $("#i-customer-name").val(custname)
                }
                $(this).modal("hide")
            }
        });
    });
    // Exit Modal
    $('body').on('hidden.bs.modal', '#suppliers_modal', function () {
        $(this).modal("hide")
        suppTbl.$('tr.selected').removeClass('selected');
        $(this).unbind()
    })
    // Selecting suppliers
    $('#supp-list tbody').on( 'click', 'tr', function () {
        if ( $(this).hasClass('selected') ) {
            $(this).removeClass('selected');
            pmcode = ""
        }
        else {
            suppTbl.$('tr.selected').removeClass('selected');
            $(this).addClass('selected');
            suppcode = $(this).data("suppcode")
            suppname = $(this).data("suppname")
            pmcode = $(this).data("pmcode")
        }
    });
    // OK Button
    $("#supp-ok").on("click", function(){
        if(pmcode){
            for( var _dom_sel of $("#i-paymentmethod > option") ){
                if(_dom_sel.value == pmcode){
                    _dom_sel.selected = true
                }
            }
            $("#i-suppliers").val(suppcode)
            $("#i-suppliers-name").val(suppname)
        }
    });
    /** 
    * END suppliers_modal
    **/
   
    /** 
     * START items_modal handler
     **/
    // handle items modal close while press enter
    $('body').on('shown.bs.modal', '#items_modal', function () {
        $(this).on("keypress", function(e){
            if(e.keyCode==13){
                //doSearch(selecteditemcode)
                $("#items-list > tbody > tr").each(function(i){
                    $(this).css("background-color","")
                })
                if(selecteditemcode!=""){
                    // find item from source
                    item = search(selecteditemcode,dbItems)
                    // return index if found item in source
                    let found = items.findIndex(o => o.item_code === item.item_code)
                    // check item exist on list and group it
                    if(found != -1){
                        items[found].qty += 1;
                        items[found].subtotal = items[found].qty * items[found].price
                    }
                    // not in list then add one
                    else{
                        item.qty = 1
                        var subtotal = item.qty * item.price
                        items.push(item)
                    }
                    // print on screen
                    render()
                    recalc()
                    // hide modal
                    $(this).modal("hide")
                }
            }
        });
    });
    // Exit Modal
    $('body').on('hidden.bs.modal', '#items_modal', function () {
        $(this).modal("hide")
        $(this).unbind()
    })
    // Selecting items
    $('#items-list tbody').on( 'click', 'tr', function () {
        if ( $(this).hasClass('selected') ) {
            $(this).removeClass('selected');
            selecteditemcode = ""
        }
        else {
            itemTbl.$('tr.selected').removeClass('selected');
            $(this).addClass('selected');
            selecteditemcode = $(this).data("itemcode")
        }
    });
    // Ok Button   
    $("#item-ok").on("click", function(){
        if(selecteditemcode!=""){
            // find item from source
            item = search(selecteditemcode,dbItems)
            // return index if found item in source
            let found = items.findIndex(o => o.item_code === item.item_code)
            // check item exist on list and group it
            if(found != -1){
                items[found].qty += 1;
                items[found].subtotal = items[found].qty * items[found].price
            }
            // not in list then add one
            else{
                item.qty = 1
                var subtotal = item.qty * item.price
                items.push(item)
            }
            // print on screen
            render()
            recalc()
        }
    });
    /**
     * END items_modal
     **/

    $("#item-search").on("click", function(){
        const input = $("#item-input").val();
        if(input != ""){
            // find item from source
            item = search(input,dbItems)
            if(item === undefined)
            {
                alert("Item not found!");
            }
            else
            {
                // return index if found item in source
                let found = items.findIndex(o => o.item_code === item.item_code)
                // check item exist on list and group it
                if(found != -1){
                    new_qty = parseInt(items[found].qty) 
                    new_qty += 1;
                    items[found].qty = new_qty
                    items[found].subtotal = items[found].qty * items[found].price
                }
                // not in list then add one
                else{
                    item.qty = 1
                    var subtotal = item.qty * item.price
                    items.push(item)
                }
                // print on screen
                render()
                recalc()
            }
        }
    });

    $("#next").on("click",function(){
        var _inputs = {};
        var _valid = 0;
        _inputs["purchasesnum"] = $("#i-purchasesnum").val()
        _inputs["prefix"] = $("#i-prefix").val()
        _inputs["refernum"] = $("#i-refernum").val()
        _inputs["employee_code"] = $("#i-employeecode").val()
        _inputs["date"] = $("#i-date").val()
        _inputs["shopcode"] = $("#i-shopcode").val()
        _inputs["suppcode"] = $("#i-suppliers").val()
        _inputs["suppname"] = $("#i-suppliers-name").val()
        _inputs['items'] = items
        _inputs['total'] = $("#total").text()
        _inputs['remark'] = $("#i-remark").val()
        _inputs['paymentmethod'] = $("#i-paymentmethod").val()
        _inputs['shopname'] = $("#i-shopcode option:selected").text()
        _inputs['paymentmethodname'] = $("#i-paymentmethod option:selected").text()
        _inputs['formtype'] = $("#i-form-type").val()
        _inputs['void'] = $("#i-void").val()
        
        $("#i-post").val(JSON.stringify(_inputs))
        $("#i-shopcode").removeClass("is-invalid")
        $("#i-suppliers").removeClass("is-invalid")
        $("#i-suppliers-name").removeClass("is-invalid")
        $("#i-paymentmethod").removeClass("is-invalid")

        if(_inputs["shopcode"] == -1){
            $("#i-shopcode").addClass("is-invalid")
            _valid = 1
        }
        if(_inputs["supp_code"] == ""){
            $("#i-suppliers").addClass("is-invalid")
            $("#i-suppliers-name").addClass("is-invalid")
            _valid = 1
        }
        if($.isEmptyObject(_inputs["items"])){
            alert("At least one input")
            _valid = 1
        }
        if(_valid == 0){
            //console.log(_inputs)
            $("#this-form").submit();
        }
    });

</script>