<div>
    <?php 
        //echo "<pre>";
        //var_dump($data);
        //echo "</pre>";
        extract($data);
    ?>
</div>
<div class="container-fluid">
    <form class="" method="POST" id="this-form" action="<?=$submit_to?>">
        <!-- Delivery Note -->
        <div class="input-group mb-2 input-group-sm">
            <div class="input-group-prepend">
                <span class="input-group-text"><?=$this->lang->line("dn_number")?></span>
            </div>
            <input type="text" class="form-control" value="<?=$dn_num?>" id="i-dn-num" disabled="" />
        </div>

        <div class="input-group mb-2 input-group-sm">
            <div class="input-group-prepend">
                <span class="input-group-text" id=""><?=$this->lang->line("dn_reference_number")?></span>
            </div>
            <input type="text" class="form-control" id="i-ref-num" value=" "  />
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

    
        <!-- ///////////////////////////////////////////////////////////////////////////////////////////////////////////////// -->
        <!-- customer Modal -->
        <?php include(APPPATH."views/modal-customers.php"); ?>
        <!-- customer Modal End -->

        <!-- Customer Modal button -->
        <div class="input-group mb-2 input-group-sm">
            <div class="input-group-prepend">
                <span class="input-group-text"><?=$this->lang->line("customer_name")?></span>
            </div>
            <input type="text" class="form-control" value="<?=$cust_code?>" id="i-customer" disabled="" />
            <input type="text" class="form-control" value="<?=$cust_name?>" id="i-customer-name" disabled="">
            <button type="button" class="btn btn-secondary btn-sm" data-toggle="modal" data-target="#customers_modal"><?=$this->lang->line("function_more")?></button>
        </div>
        <!-- Customer Modal button END -->
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
        <!-- Product Search Button -->
        <div class="input-group mb-2 input-group-sm">
            <input type="text" class="form-control item-input" id="item-input" placeholder="<?=$this->lang->line("item_code")?>">
            <div class="input-group-append">
                <button class="btn btn-outline-secondary btn-sm" type="button" id="item-search"><?=$this->lang->line("function_search")?></button>
            </div>
            <button type="button" class="btn btn-secondary btn-sm" data-toggle="modal" data-target="#items_modal"><?=$this->lang->line("function_more")?></button>
            <!-- items Modal -->
            <?php include(APPPATH."views/modal-items.php"); ?>
            <!-- items Modal End -->
        </div>
        <!-- Payment Method button END-->

        <!-- Products -->
        <div class="input-group mb-2 input-group-sm">
            <!-- items Modal -->
            <?php include(APPPATH."views/modal-items.php"); ?>
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
                <th></th>
            </thead>
            <!-- render items-list here -->
            <tbody id="tdisplay">
            <?php
                
                foreach ($items as $k=> $v){
                    extract($v);
                    
            ?>
                <tr data-items="item_<?=$k?>">
                    <td><?=$item_code?></td>
                    <td><?=$eng_name?></td>
                    <td><?=$chi_name?></td>
                    <td><?=$stockonhand?></td>
                    <td>
                        <input type="button" class='btn btn-secondary btn-sm w-70 float-right' id="minus_<?=$k?>" value="-" />
                    </td>
                    <td>
                        <input type="text" class="form-control form-control-sm item-input" id="qty_<?=$k?>" value="<?=$qty?>" disabled />
                    </td>
                    <td>
                        <input type="button" class='btn btn-secondary btn-sm w-70' id="plus_<?=$k?>" value="+" />
                    </td>
                    <td><?=$unit?></td>
                    <td></td>
                </tr>
            <?php
                    
                }
            ?>
            <tbody>
        </table>

        <div class="input-group mb-2 input-group-sm">
            <textarea  class="form-control" rows="3" id="i-remark" placeholder="<?=$this->lang->line("stock_remark")?>" disabled></textarea>
        </div>
        <input type="hidden" name="i-post" id="i-post" value="" />
        <input type="hidden" name="i-prefix" id="i-prefix" value="<?=$prefix?>" />
        <input type="hidden" name="i-void" id="i-void" value="true" />
        <input type="hidden" name="i-employeecode" id="i-employeecode" value="<?=$employee_code?>" />
        <input type="hidden" name="i-form-type" id="i-form-type" value="create" />
    </form>
</div>


<script>
    var dbItems = <?=json_encode($ajax["items"],true)?>;
    var items = []
    var selecteditemcode = ""
    var custcode = $("#i-customer").val()
    var custname = ""
    var cust_pmcode = ""
    var ftotal = 0
    var atleastoneitem_msg = "<?=$this->lang->line('label_atleastoneitem_msg')?>"
    var custTbl = $('#cust-list').DataTable({
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
    //testing here

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
                "stockonhand" : $(this).children()[3].innerText,
                "qty" : parseInt($(this).children()[5].children[0].value),
                "unit":  $(this).children()[7].innerText,
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
            +"<td>"+items[item].item_code+"</td>"
            +"<td>"+items[item].eng_name+"</td>"
            +"<td>"+items[item].chi_name+"</td>"
            +"<td>"+items[item].stockonhand+"</td>"
            +"<td><input type='button' class='btn btn-secondary btn-sm w-50 float-right' id='minus_"+item+"' value='-' /></td>"
            +"<td><input type='text' class='form-control form-control-sm item-input' id='qty_"+item+"' value='"+items[item].qty+"' disabled /></td>"
            +"<td><input type='button' class='btn btn-secondary btn-sm w-50' id='plus_"+item+"' value='+' /></td>"
            +"<td>"+items[item].unit+"</td>"
            +"<td><button class='btn btn-danger btn-sm w-90' data-del-itemcode='"+items[item].item_code+"' id='del_"+item+"' type='button'><i class='fas fa-trash-alt'></i></button></td>"
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
                //refresh()
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
        }
        // print on screen
        render()
        recalc()
    }
    function doUnLoad(){
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
    $(window).on('beforeunload', function(){
        doUnLoad();
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
     * START customers_modal handler
     **/
    // handle customer modal close while press enter
    $('body').on('shown.bs.modal', '#customers_modal', function () {
        $(this).on("keypress", function(e){
            if(e.keyCode==13){
                $("#cust-list > tbody > tr").each(function(i){
                    $(this).css("background-color","")
                })
                if(cust_pmcode){
                    for( var _dom_sel of $("#i-paymentmethod > option") ){
                        if(_dom_sel.value == cust_pmcode){
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
    $('body').on('hidden.bs.modal', '#customers_modal', function () {
        $(this).modal("hide")
        $(this).unbind()
    })
    // Selecting customer
    $('#cust-list tbody').on( 'click', 'tr', function () {
        if ( $(this).hasClass('selected') ) {
            $(this).removeClass('selected');
            cust_pmcode = ""
        }
        else {
            custTbl.$('tr.selected').removeClass('selected');
            $(this).addClass('selected');
            custcode = $(this).data("custcode")
            custname = $(this).data("custname")
            cust_pmcode = $(this).data("pmcode")
        }
    });
    // OK Button
    $("#cust-ok").on("click", function(){
        if(cust_pmcode){
            for( var _dom_sel of $("#i-paymentmethod > option") ){
                if(_dom_sel.value == cust_pmcode){
                    _dom_sel.selected = true
                }
            }
            $("#i-customer").val(custcode)
            $("#i-customer-name").val(custname)
        }
    });
    /** 
     * END customers_modal
     **/

    /** START items_modal handler **/
    // handle items modal close while press enter
    $('body').on('shown.bs.modal', '#items_modal', function () {
        $(this).on("keypress", function(e){
            if(e.keyCode==13){
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
    });
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
    /** END items_modal  **/
    
    /** 
    * START tips_modal handler
    **/
    
    // Exit Modal
    $('body').on('hidden.bs.modal', '#tips_modal', function () {
        $(this).modal("hide")
        $(this).unbind()
    })
    /** 
    * END tips_modal
    **/

    $("#item-search").on("click", function(){
        const selecteditemcode = $("#item-input").val();
        if(selecteditemcode != ""){
            lookup(selecteditemcode,dbItems) 
        }
    });

    $("#item-input").on("keypress", function(e){
        if(e.keyCode==13){
            const selecteditemcode = $(this).val();
            if(selecteditemcode != ""){
                lookup(selecteditemcode,dbItems)
            }
        }
    });
    

    $("#next").on("click",function(){
        var _inputs = {};
        var _valid = 0;
        _inputs["dn_num"] = $("#i-dn-num").val()
        _inputs["dn_prefix"] = $("#i-prefix").val()
        _inputs["trans_code"] = $("#i-ref-num").val()
        _inputs["employee_code"] = $("#i-employeecode").val()
        _inputs["date"] = $("#i-date").val()
        _inputs["shopcode"] = $("#i-shopcode").val()
        _inputs['shopname'] = $("#i-shopcode option:selected").text()
        _inputs["cust_code"] = $("#i-customer").val()
        _inputs["cust_name"] = $("#i-customer-name").val()
        _inputs['items'] = items
        _inputs['total'] = $("#total").text()
        _inputs['remark'] = $("#i-remark").val()
        _inputs['paymentmethod'] = $("#i-paymentmethod").val()
        _inputs['paymentmethodname'] = $("#i-paymentmethod option:selected").text()
        _inputs['formtype'] = $("#i-form-type").val()
        _inputs['void'] = $("#i-void").val()

        $("#i-post").val(JSON.stringify(_inputs))
        $("#i-shopcode").removeClass("is-invalid")
        $("#i-customer").removeClass("is-invalid")
        $("#i-customer-name").removeClass("is-invalid")
        $("#i-paymentmethod").removeClass("is-invalid")
        if(_inputs['paymentmethod'] == -1){
            $("#i-paymentmethod").addClass("is-invalid")
            _valid = 1
        }
        if(_inputs["shopcode"] == -1){
            $("#i-shopcode").addClass("is-invalid")
            _valid = 1
        }
        if(_inputs["cust_code"] == ""){
            $("#i-customer").addClass("is-invalid")
            $("#i-customer-name").addClass("is-invalid")
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