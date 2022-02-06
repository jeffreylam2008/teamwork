<?php

extract($data);
// echo "<pre>";
// print_r($data);
// echo "</pre>";
?>
<div class="container-fluid">
    <form class="" method="POST" id="this-form" action="<?=$submit_to?>">
        <!-- Suppliers -->
        <div class="input-group mb-2 input-group-sm">
            <div class="input-group-prepend">
                <span class="input-group-text"><?=$this->lang->line("grn_number")?></span>
            </div>
            <input type="text" class="form-control" value="<?=$grn_num?>" id="i-grn-num" disabled="" />
        </div>

        <div class="input-group mb-2 input-group-sm">
            <div class="input-group-prepend">
                <span class="input-group-text" id=""><?=$this->lang->line("purchase_number")?></span>
            </div>
            <input type="text" class="form-control" id="i-po-num" value="<?=$po_num?>" disabled="" />
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
            <?php
                if(!empty($ajax["shop_code"])):
                    $key = array_search($default_shopcode,array_column($ajax["shop_code"],"shop_code"));
            ?>
            <select class="custom-select custom-select-sm" id="i-shopcode">
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

        
        <!-- Start Suppliers -->
        <div class="input-group mb-2 input-group-sm">
            <!-- suppliers Modal -->
            <?php include(APPPATH."views/modal-suppliers.php"); ?>
            <!-- suppliers Modal End -->
            <div class="input-group-prepend">
                <span class="input-group-text"><?=$this->lang->line("supplier")?></span>
            </div>
            <input type="text" class="form-control" value="<?=$supp_code?>" id="i-suppliers" disabled="" />
            <input type="text" class="form-control" value="<?=$supp_name?>" id="i-suppliers-name" disabled="">
            <button type="button" class="btn btn-secondary btn-sm" data-toggle="modal" data-target="#suppliers_modal"><?=$this->lang->line("function_more")?></button>
        </div>
        <!-- End Suppliers -->
        
        <!-- Payment Method button -->
        <div class="input-group mb-2 input-group-sm">
            <div class="input-group-prepend">
                <label class="input-group-text"><?=$this->lang->line("payment_method")?></label>
            </div>
            <select class="custom-select custom-select-sm" id="i-paymentmethod">
                <?php if(!empty($paymentmethod)): ?>
                    <option value="<?=$paymentmethod?>"><?=$paymentmethodname?></option>
                <?php else: ?>
                    <option value="-1"><?=$this->lang->line("function_select")?></option>
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
            <input type="text" class="form-control item-input" id="item-input" placeholder="<?=$this->lang->line("item_code")?>">
            <div class="input-group-append">
                <button class="btn btn-outline-secondary btn-sm" type="button" id="item-search"><?=$this->lang->line("function_search")?></button>
            </div>
            <button type="button" class="btn btn-secondary btn-sm" data-toggle="modal" data-target="#items_modal"><?=$this->lang->line("function_more")?></button>
        </div>
        <table class="table table-sm table-striped" id="tbl">
            <thead>
                <th><?=$this->lang->line("item_code")?></th>
                <th><?=$this->lang->line("item_eng_name")?></th>
                <th><?=$this->lang->line("item_chi_name")?></th>
                <th><?=$this->lang->line("item_Stockonhand")?></th>
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
                <input type="hidden" id="maxqty_<?=$k?>" value="<?=$qty?>" />
                <tr data-items="item_<?=$k?>">
                    <td class="col-1" ><?=$item_code?></td>
                    <td class="col-2" ><?=$eng_name?></td>
                    <td class="col-2" ><?=$chi_name?></td>
                    <td class="col-1" ><?=$stockonhand?></td>
                    <td class="col-1 clearfix" >
                        <input type="button" class='btn btn-warning btn-sm w-70 float-right' id="more_minus_<?=$k?>" value="-10" />
                        <input type="button" class='btn btn-secondary btn-sm w-70 float-right' id="minus_<?=$k?>" value="-" />
                    </td>
                    <td class="col-sm-1" >
                        <input type="text" class="form-control form-control-sm item-input" id="qty_<?=$k?>" value="<?=$qty?>" disabled />
                    </td>
                    <td class="col-1">
                        <input type="button" class='btn btn-secondary btn-sm w-70' id="plus_<?=$k?>" value="+" />
                        <input type="button" class='btn btn-warning btn-sm w-70' id="more_plus_<?=$k?>" value="+10" />
                    </td>
                    <td class="col-1"><?=$unit?></td>
                    <td class="col-1">
                       <input type="text" class="form-control form-control-sm item-input" id="price_<?=$k?>" value="<?=$price?>" />
                    </td>
                    <td class="col-2" id="subtotal_<?=$k?>"><?=$subtotal?></td>
                    
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
                    <td align="right"><?=$this->lang->line("common_total")?>: </td>
                    <td id="total"><?=number_format($total,2,".",",")?></td>
                </tr>
            </tbody>        
        </table>
        <div class="input-group mb-2 input-group-sm">
            <textarea  class="form-control" rows="3" id="i-remark" placeholder="<?=$this->lang->line("item_remark")?>"><?=$remark?></textarea>
        </div>
        <input type="hidden" name="i-post" id="i-post" value="" />
        <input type="hidden" name="i-prefix" id="i-prefix" value="<?=$prefix?>" />
        <input type="hidden" name="i-employeecode" id="i-employeecode" value="<?=$employee_code?>" />
        <input type="hidden" name="i-form-type" id="i-form-type" value="create" />
    </form>
</div>

<script>
var dbItems = <?=json_encode($ajax["items"],true)?>;
var items = []
var selecteditemcode = ""
var supp_code = "" 
var supp_name = ""
var pmcode = ""
var ftotal = 0
var item_qty_limit = 1000
var maxqty_msg = "<?=$this->lang->line('label_maxqty_msg')?>"
var atleastoneitem_msg = "<?=$this->lang->line('label_atleastoneitem_msg')?>"
var suppTbl = $('#supp-list').DataTable({
    "select": {
        items: 'column'
    },
    "iDisplayLength": <?=$default_per_page?>,
    "language": {
        "lengthMenu" : "<?=$this->lang->line('function_page_showing')?> _MENU_",
        "search": "<?=$this->lang->line('function_search')?> :",
        "info": "<?=$this->lang->line('function_page_showing')?> _START_ <?=$this->lang->line('function_page_to')?> _END_ <?=$this->lang->line('function_page_of')?> _TOTAL_ <?=$this->lang->line('function_page_entries')?>",
        "paginate": {
            "first": "<?=$this->lang->line('function_first')?>",
            "last": "<?=$this->lang->line('function_last')?>",
            "next": "<?=$this->lang->line('function_next')?>",
            "previous": "<?=$this->lang->line('function_previous')?>"
        }
    }
});
var itemTbl =$('#items-list').DataTable({
    "select": {
        items: 'column'
    },
    "iDisplayLength": <?=$default_per_page?>,
    "language": {
        "lengthMenu" : "<?=$this->lang->line('function_page_showing')?> _MENU_",
        "search": "<?=$this->lang->line('function_search')?> :",
        "info": "<?=$this->lang->line('function_page_showing')?> _START_ <?=$this->lang->line('function_page_to')?> _END_ <?=$this->lang->line('function_page_of')?> _TOTAL_ <?=$this->lang->line('function_page_entries')?>",
        "paginate": {
            "first": "<?=$this->lang->line('function_first')?>",
            "last": "<?=$this->lang->line('function_last')?>",
            "next": "<?=$this->lang->line('function_next')?>",
            "previous": "<?=$this->lang->line('function_previous')?>"
        }
    }
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
            "stockonhand" : parseInt($(this).children()[3].innerText),
            "qty" :  parseInt($(this).children()[5].children[0].value),
            "unit":  $(this).children()[7].innerText,
            "price" : $(this).children()[8].children[0].value,
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
        tmpl += "<input type='hidden' id='maxqty_"+item+"' value='"+$('#maxqty_'+item).val()+"' />"
        +"<tr data-items='itmes_"+item+"'>"
        +"<td class='col-1'>"+items[item].item_code+"</td>"
        +"<td class='col-2'>"+items[item].eng_name+"</td>"
        +"<td class='col-2'>"+items[item].chi_name+"</td>"
        +"<td class='col-1'>"+items[item].stockonhand+"</td>"
        +"<td class='col-1 clearfix'>"
        +"<input type='button' class='btn btn-secondary btn-sm w-70 float-right' id='minus_"+item+"' value='-' />"
        +"<input type='button' class='btn btn-warning btn-sm w-70 float-right' id='more_minus_"+item+"' value='-10' />"
        +"</td>"
        +"<td class='col-sm-1'><input type='text' class='form-control form-control-sm item-input' id='qty_"+item+"' value='"+items[item].qty+"'  disabled /></td>"
        +"<td class='col-1'>"
        +"<input type='button' class='btn btn-secondary btn-sm w-70' id='plus_"+item+"'  value='+' />"
        +"<input type='button' class='btn btn-warning btn-sm w-70' id='more_plus_"+item+"' value='+10' />"
        +"</td>"
        +"<td class='col-1'>"+items[item].unit+"</td>"
        +"<td class='col-1'><input type='text' class='form-control form-control-sm item-input' id='price_"+item+"' value='"+_price.toFixed(2)+"' /></td>"
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
         $('#more_minus_'+i).on("click", function(){
            var $qty = $('#qty_'+i)
            if(parseInt($qty.val()) > 10){
                let new_qty = parseInt($qty.val()) - 10
                if(new_qty > 0)
                    new_qty = "+"+new_qty
                $qty.val(new_qty)
                recalc()
            }
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
            var $maxqty = $('#maxqty_'+i)
            if($maxqty.val() != 0 || $maxqty.val() != undefined)
            {
                item_qty_limit = $maxqty.val()
            }
            if(parseInt( $qty.val()) > 0 && parseInt( $qty.val()) < item_qty_limit){
                let new_qty = parseInt( $qty.val()) + 1
                $qty.val(new_qty)
                recalc()
            }
            else
            {
                alert(maxqty_msg)
            }
        });
         // plus function
         $('#more_plus_'+i).on("click",function(){
            var $qty = $('#qty_'+i)
            var $maxqty = $('#maxqty_'+i)
            if($maxqty.val() != 0 || $maxqty.val() != undefined)
            {
                item_qty_limit = $maxqty.val()
            }
            if(parseInt( $qty.val()) > 0 && parseInt( $qty.val()) < item_qty_limit){
                let new_qty = parseInt( $qty.val()) + 10
                if(new_qty > 0)
                    new_qty = "+"+new_qty
                $qty.val(new_qty.toString())
                recalc()
            }
            else
            {
                alert(maxqty_msg)
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
 // lookup select item code from source
function lookup(selecteditemcode, source)
{
    item = search(selecteditemcode,source)
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

$(window).on('beforeunload', function(){
    return "Any changes will be lost";
});
$(document).on("submit", "form", function(event){
    // disable unload warning
    $(window).off('beforeunload');
});
//construct
$(document).ready(function(){
    refresh()
    render()
});
/* /////////////////// END Libaray Session /////////////////////*/


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
        supp_code = $(this).data("suppcode")
        supp_name = $(this).data("suppname")
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
        $("#i-suppliers").val(supp_code)
        $("#i-suppliers-name").val(supp_name)
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
            lookup(selecteditemcode,dbItems)
            // hide modal
            $(this).modal("hide")
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
    lookup(selecteditemcode,dbItems)
});
/**
* END items_modal
**/

$("#item-search").on("click", function(){
    const selecteditemcode = $("#item-input").val();
    if(selecteditemcode != ""){
        lookup(selecteditemcode,dbItems)
    }
});
$("#item-input").on('focus', function(){
    $(this).on("keypress", function(e){
        if(e.keyCode==13){
            const selecteditemcode = $(this).val();
            if(selecteditemcode != ""){
                lookup(selecteditemcode,dbItems)
            }
        }
    });
});
/**
 * Submit form
 */
$("#next").on("click",function(){
    var _inputs = {};
    var _valid = 0;
    _inputs["grn_num"] = $("#i-grn-num").val()
    _inputs["prefix"] = $("#i-prefix").val()
    _inputs["po_num"] = $("#i-po-num").val()
    _inputs["date"] = $("#i-date").val()
    _inputs["employee_code"] = $("#i-employeecode").val()
    _inputs["shop_code"] = $("#i-shopcode").val()
    _inputs['shopname'] = $("#i-shopcode option:selected").text()
    _inputs["supp_code"] = $("#i-suppliers").val()
    _inputs["supp_name"] = $("#i-suppliers-name").val()
    _inputs["paymentmethod"] = $("#i-paymentmethod").val()
    _inputs['paymentmethodname'] = $("#i-paymentmethod option:selected").text()
    _inputs['items'] = items
    _inputs['total'] = $("#total").text()
    _inputs['remark'] = $("#i-remark").val()
    _inputs['formtype'] = $("#i-form-type").val()

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
        alert(atleastoneitem_msg)
        _valid = 1
    }
    if(_valid == 0){
        //console.log(_inputs)
        $("#this-form").submit();
    }
});

</script>