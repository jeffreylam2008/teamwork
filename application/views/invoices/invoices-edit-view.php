<div class="container-fluid">
    <form class="" method="POST" id="this-form" action="<?=$submit_to?>">
        <div class="input-group mb-2 input-group-sm">
            <div class="input-group-prepend">
                <span class="input-group-text" id="">Invoice Number</span>
            </div>
            
            <input type="text" class="form-control" id="i-invoicenum" value="<?=$invoice_num?>" disabled>
        </div>
        <div class="input-group mb-2 input-group-sm">
            <div class="input-group-prepend">
                <span class="input-group-text" id="">Quotation</span>
            </div>
            <input type="text" class="form-control" id="i-quotation" value="<?=$quotation?>" disabled>
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
            <select class="custom-select custom-select-sm" id="i-shopcode" <?=($show==true) ? "" : "disabled"?>>
                <option value="-1">Choose...</option>
                <?php
                if(!empty($ajax["shop_code"])):
                    foreach($ajax["shop_code"] as $k => $v):
                ?>
                        <option value="<?=$v['shop_code']?>"><?=$v['name']?></option>
                <?php
                    endforeach;
                endif;
                ?>
            </select>
        </div>
        
        <!-- ///////////////////////////////////////////////////////////////////////////////////////////////////////////////// -->
        <?php
        if($show):
        ?>
        <div class="input-group mb-2 input-group-sm">
            <input type="text" class="form-control" placeholder="Customers" />
            <div class="input-group-append">
                <button class="btn btn-outline-secondary btn-sm" type="button" id="cust-search">Search</button>
            </div>
            <button type="button" class="btn btn-secondary btn-sm" data-toggle="modal" data-target="#customers_modal">More...</button>
            <!-- customer Modal -->
            <div class="modal fade" id="customers_modal" tabindex="-1" role="dialog" aria-labelledby="" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="">Customer List</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body-600">
                        <!-- content -->
                            <div class="container-fluid">
                                <table class="table table-sm table-striped" id="cust-list">
                                    <thead>
                                        <tr>
                                            <th scope="col">#</th>
                                            <th scope="col">Code</th>
                                            <th scope="col">Name</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                            if(!empty($ajax['customers'])):
                                                foreach($ajax['customers'] as $k => $v):
                                        ?>
                                            <tr data-custcode="<?=$v['cust_code']?>" data-custname="<?=$v['name']?>" data-pmcode="<?=$v['pm_code']?>">
                                                <td><?=$k+1?></td>
                                                <td><?=$v['cust_code']?></td>
                                                <td><?=$v['name']?></td>
                                            </tr>
                                        <?php
                                                endforeach;
                                            endif;
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        <!-- content end -->
                        </div>
                        
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="button" class="btn btn-primary" id="cust-ok" data-dismiss="modal">OK</button>
                        </div>
                    </div>
                </div>
            </div>
            <!-- customer Modal End -->
           
        </div>
        <?php
        endif;
        ?>
        <div class="input-group mb-2 input-group-sm">
            <div class="input-group-prepend">
                <span class="input-group-text">Customer</span>
            </div>
            <input type="text" class="form-control" value="" id="i-customer" disabled="" />
            <input type="text" class="form-control" value="" id="i-customer-name" disabled="">
            
        </div>

        <!-- ///////////////////////////////////////////////////////////////////////////////////////////////////////////////// -->

        <!-- Payment Method -->
        <div class="input-group mb-2 input-group-sm">
            <div class="input-group-prepend">
                <label class="input-group-text">Payment Method</label>
            </div>
            <select class="custom-select custom-select-sm" id="i-paymentmethod" <?=($show==true) ? "" : "disabled"?>>
                <option value="-1">Choose...</option>
                <?php 
                    foreach($ajax["tender"] as $k => $v):
                ?>
                        <option value="<?=$v['pm_code']?>"><?=$v['payment_method']?></option>
                <?php
                    endforeach;
                ?>
            </select>
        </div>

        <!-- ///////////////////////////////////////////////////////////////////////////////////////////////////////////////// -->
        <?php
        if($show):
        ?>
        <div class="input-group mb-2 input-group-sm">
            <input type="text" class="form-control item-input" placeholder="items code">
            <div class="input-group-append">
                <button class="btn btn-outline-secondary btn-sm" type="button" id="item-search">Search</button>
            </div>
            <button type="button" class="btn btn-secondary btn-sm" data-toggle="modal" data-target="#items_modal">More...</button>
            <!-- items Modal -->
            <div class="modal fade" id="items_modal" tabindex="-1" role="dialog" aria-labelledby="" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="">Items List</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body-600">
                        <!-- content -->
                            <div class="container-fluid">
                                <table class="table table-sm table-striped" id="items-list">
                                    <thead>
                                        <tr>
                                            <th scope="col">#</th>
                                            <th scope="col">Code</th>
                                            <th scope="col">Chinese Name</th>
                                            <th scope="col">English Name</th>
                                            <th scope="col">Price</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                            if(!empty($ajax["items"])):
                                                foreach($ajax["items"] as $k => $v):
                                        ?>
                                                <tr data-itemcode="<?=$v['item_code']?>">
                                                    <td><?=$k+1?></td>
                                                    <td><?=$v["item_code"]?></td>
                                                    <td><?=$v["chi_name"]?></td>
                                                    <td><?=$v["eng_name"]?></td>
                                                    <td>$<?=$v["price"]?></td>
                                                </tr>
                                        <?php
                                                endforeach;
                                            endif;

                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        <!-- content end -->
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="button" class="btn btn-primary" id="item-ok" data-dismiss="modal">OK</button>
                        </div>
                    </div>
                </div>
            </div>
            <!-- items Modal End -->
        </div>
        <?php endif;?>
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
                foreach($items as $k => $v):
                    extract($v);
            ?>
                <tr data-itemcode="<?=$item_code?>">
                    <td scope="row"></th>
                    <td><?=$item_code?></td>
                    <td><?=$eng_name?></td>
                    <td><?=$chi_name?></td>
                    <td><?=$qty?></td>
                    <td><?=$unit?></td>
                    <td><?=$price?></td>
                    <td><?=$total?></td>
                    <!--<td><button class='btn btn-danger btn-sm' id='item-del' type='button'>X</button></td>-->
                </tr>
            <?php
                endforeach;
            ?>
                <tr>
                    <td colspan="6"></td>
                    <td align="right">Total: </td>
                    <td><?=$total?></td>
                </tr>
            </tbody>
        </table>
        
        <div class="input-group mb-2 input-group-sm">
            <textarea  class="form-control" rows="3" id="i-remark" placeholder="Remark" <?=($show==true) ? "" : "disabled"?>></textarea>
        </div>
        <input type="hidden" name="i-post" id="i-post" value="" />
        <input type="hidden" name="i-prefix" id="i-prefix" value="<?=$prefix?>" />
        <input type="hidden" name="i-employeecode" id="i-employeecode" value="<?=$employee_code?>" />
        <input type="hidden" name="i-edit-mode" id="i-edit-mode" value="<?=$show?>" />
        <input type="hidden" name="i-form-type" id="i-form-type" value="edit" />
    </form>
</div>
<!-- / / / // / / // / //// //  / / / // / / // // / / / // / / // / /  // / / / // / / // / / / // / / / // / / / // / / / // / / -->
<script id="template-items" type="x-tmpl-mustache">
    {{#items}}
        <tr data-itemcode="{{item_code}}">
            <td scope="row">{{index}}</th>
            <td>{{item_code}}</td>
            <td>{{eng_name}}</td>
            <td>{{chi_name}}</td>
            <td>
            <button class='btn btn-secondary btn-sm' id='item-minus' type='button'>
                <i class="fas fa-minus"></i>
            </button>
            {{qty}} 
            <button class='btn btn-secondary btn-sm' id='item-plus' type='button'>
                <i class="fas fa-plus"></i>
            </button>
            </td>
            <td>{{unit}}</td>
            <td>{{price}}</td>
            <td>{{subtotal}}</td>
            <?php if($show):?>
            <td><button class='btn btn-danger btn-sm' id='item-del' type='button'><i class='fas fa-trash-alt'></i></button></td>
            <?php endif; ?>
        </tr>
    {{/items}}
    <tr>
        <td colspan="6"></td>
        <td align="right">Total: </td>
        <td>{{total}}</td>
    </tr>
</script>


<script>
    var theprint = <?=json_encode($theprint_data,true)?>;
    var dbItems = <?=json_encode($ajax["items"],true)?>;
    var cpAllItems = {};
    var cpTotal = 0;
    var custcode = "", custname = "", cust_pmcode = "", selecteditemcode = "";

    //testing here
    console.log(theprint);

    // Data massage
    for(j in dbItems){
        dbItems[j]["qty"] = 0;
        dbItems[j]["subtotal"] = 0;
    }

    function arrToObj(items){
        var arr = {}
        for(i in items){
            arr[items[i].item_code] = items[i]
        }
        return arr
    }
    function objToArr(items){
        var arr = [];
        for(i in items){
            arr.push(items[i])
        }
        return arr
    }
    function showItemsList(itemList, itemListTotal){
        itemList = objToArr(itemList)
        for(var i in itemList){
           itemList[i]["index"] = (parseInt(i) + 1)
           itemList[i]["qty"] = parseFloat(itemList[i]["qty"])
        }

        var toHtml = Mustache.render($("#template-items").html(), {"items": itemList ,"total": itemListTotal.toFixed(2)})
        $("#render-items").html(toHtml)
    }

    function doRender(list){
        if(list != ""){
            $("#i-invoicenum").val(list.invoicenum)
            $("#i-date").val(list.date)
            $("#i-quotation").val(list.quotation)
            $("#i-shopcode").val(list.shopcode)
            if(list.hasOwnProperty("customer")){
                $("#i-customer").val(list.customer.cust_code)
                $("#i-customer-name").val(list.customer.name)
            }
            $("#i-remark").val(list.remark)
            $("#i-paymentmethod").val(list.paymentmethod)
            cpTotal = parseFloat(list.total)
            cpAllItems = arrToObj(list.items)
            showItemsList(cpAllItems, cpTotal)
        }
    }
    function searchList(lookfor, arr){
        var a
        for (var i=0; i < arr.length; i++) {
            if (arr[i].item_code === lookfor) {
                arr[i].uid = arr[i].uid.toString()
                a = arr[i];
            }
        }
        return a
    }
    function doSearch(itemcode){
        let _item
        let _uSearch = itemcode
        let _recal = 0

        //let allItems = []
        if(_uSearch != ""){
            _item = searchList(_uSearch, dbItems)
            if(_item != undefined){
                if(cpAllItems.hasOwnProperty(_uSearch)){
                    for(let i in cpAllItems){
                        if(i == _uSearch){
                            cpAllItems[i].qty = parseFloat(cpAllItems[i].qty) + 1
                            _subtotal = cpAllItems[i].qty * parseFloat(cpAllItems[i].price)
                            cpAllItems[i].subtotal = _subtotal.toFixed(2)
                        }
                        _recal += parseFloat(cpAllItems[i].subtotal)
                        cpTotal = _recal
                    }
                }
                else{
                    cpAllItems[_uSearch] = _item
                    cpAllItems[_uSearch].qty = parseFloat(cpAllItems[_uSearch].qty) + 1
                    _subtotal = cpAllItems[_uSearch].qty * parseFloat(cpAllItems[_uSearch].price)
                    cpAllItems[_uSearch].subtotal = _subtotal.toFixed(2)
                    for(let i in cpAllItems){
                        if(cpAllItems.hasOwnProperty(i)){
                            _recal += parseFloat(cpAllItems[i].subtotal)
                            cpTotal = _recal

                        }
                    }
                }
                console.log(cpAllItems)
                showItemsList(cpAllItems, cpTotal)
            }
            else{
                alert("Item not found")
            }
        }
    }
    function doRemove(itemcode)
    {
        let _uSearch = itemcode
        let _recal = 0
        let _isEmpty
        //console.log(cpAllItems)
        cpAllItems[_uSearch].qty = 0
        delete cpAllItems[_uSearch]
        console.log(cpAllItems)
        for(let i in cpAllItems){
            //console.log("loop")
            if(cpAllItems.hasOwnProperty(i)){
                _recal += parseFloat(cpAllItems[i].subtotal)
                cpTotal = _recal
            }
        }
        if($.isEmptyObject(cpAllItems)){
            cpTotal = 0
        }
        showItemsList(cpAllItems, cpTotal)        
    }
        
    function doQtyPlus(itemcode)
    {
        let _uSearch = itemcode
        let _recal = 0    
        if(cpAllItems[_uSearch].qty >= 0){
            for(let i in cpAllItems){   
                if(i == _uSearch){
                    cpAllItems[i].qty = parseFloat(cpAllItems[i].qty) + 1
                    _subtotal = cpAllItems[i].qty * parseFloat(cpAllItems[i].price)
                    cpAllItems[i].subtotal = _subtotal.toFixed(2)
                }
                _recal += parseFloat(cpAllItems[i].subtotal)
                cpTotal = _recal
            }
            showItemsList(cpAllItems, cpTotal)
        }
    }

    function doQtyMinus(itemcode)
    {
        let _uSearch = itemcode
        let _recal = 0
        if(cpAllItems[_uSearch].qty > 1){
            for(let i in cpAllItems){   
                if(i == _uSearch){
                    cpAllItems[i].qty = parseFloat(cpAllItems[i].qty) - 1
                    _subtotal = cpAllItems[i].qty * parseFloat(cpAllItems[i].price)
                    cpAllItems[i].subtotal = _subtotal.toFixed(2)
                }
                _recal += parseFloat(cpAllItems[i].subtotal)
                cpTotal = _recal
            }
            showItemsList(cpAllItems, cpTotal)
        }
    }
    

    doRender(theprint)

   // use Datatable plug-in in customer and items modal 
   var custTbl = $('#cust-list').DataTable({
        "select": {
            items: 'column'
        },
        "iDisplayLength": <?=$default_per_page?>,
    });

    var itemTbl =$('#items-list').DataTable({
        "select": {
            items: 'column'
        },
        "iDisplayLength": 10,
    });

    // customer modal
    // event trigger - customer modal
    $('#cust-list tbody').on( 'click', 'tr', function () {
        if ( $(this).hasClass('selected') ) {
            $(this).removeClass('selected');
        }
        else {
            custTbl.$('tr.selected').removeClass('selected');
            $(this).addClass('selected');
            custcode = $(this).data("custcode")
            custname = $(this).data("custname")
            cust_pmcode = $(this).data("pmcode")
        }
    });
    
    // handle customer modal close while press enter
    $('body').on('shown.bs.modal', '#customers_modal', function () {
        $(this).on("keypress", function(e){
            if(e.keyCode==13){
                $("#cust-list > tbody > tr").each(function(i){
                    $(this).css("background-color","")
                })
                $("#i-customer").val(custcode)
                $("#i-customer-name").val(custname)
                if(cust_pmcode){
                    for( var _dom_sel of $("#i-paymentmethod > option") ){
                        if(_dom_sel.value == cust_pmcode){
                            _dom_sel.selected = true
                        }
                    }
                }
                $(this).modal("hide")
            }
        });
    });
    $("#cust-ok").on("click", function(){
        $("#i-customer").val(custcode)
        $("#i-customer-name").val(custname)
        if(cust_pmcode){
            for( var _dom_sel of $("#i-paymentmethod > option") ){
                if(_dom_sel.value == cust_pmcode){
                    _dom_sel.selected = true
                }
            }
        }
    });
    // clear customer model cache
    $('body').on('hidden.bs.modal', '#customers_modal', function () {
        $(this).modal("hide")
        $(this).unbind()
    })

    // item modal
    // event trigger - items modal
    $('#items-list tbody').on( 'click', 'tr', function () {
        if ( $(this).hasClass('selected') ) {
            $(this).removeClass('selected');
        }
        else {
            itemTbl.$('tr.selected').removeClass('selected');
            $(this).addClass('selected');
            selecteditemcode = $(this).data("itemcode")
        }
    });

    // handle items modal close while press enter
    $('body').on('shown.bs.modal', '#items_modal', function () {
        $(this).on("keypress", function(e){
            if(e.keyCode==13){
                doSearch(selecteditemcode)
                $("#items-list > tbody > tr").each(function(i){
                    $(this).css("background-color","")
                })
                $(this).modal("hide")
            }
        });
    });
    // clear items model cache
    $('body').on('hidden.bs.modal', '#items_modal', function () {
        $(this).modal("hide")
        $(this).unbind()
    })
    // remove item from list
    $("#items-table").on("click","tbody > tr > td > button#item-del",function(e){
        e.preventDefault()
        $(this).parent().parent().remove()
        doRemove($(this).parent().parent().data("itemcode"))
    });
    $("#items-table").on("click","tbody > tr > td > button#item-plus",function(e){
        e.preventDefault()
        doQtyPlus($(this).parent().parent().data("itemcode"))
    });
    $("#items-table").on("click","tbody > tr > td > button#item-minus",function(e){
        e.preventDefault()
        doQtyMinus($(this).parent().parent().data("itemcode"))
    });
    // dispatch data from modal to outside
    $("#item-ok").on("click", function(){
        doSearch(selecteditemcode)
    });
    $("#item-search").on("click", function(){
        doSearch($(".item-input").val())
    });
    // "enter" press event
    $(".item-input").on("keypress", function(e){
        if(e.keyCode==13){
            e.preventDefault() 
            doSearch($(this).val())
        }
    });

    $("#next").on("click",function(){
        var _inputs = {};
        var _valid = 0;
        _inputs["invoicenum"] = $("#i-invoicenum").val()
        _inputs["prefix"] = $("#i-prefix").val()
        _inputs["quotation"] = $("#i-quotation").val()
        _inputs["employeecode"] = $("#i-employeecode").val()
        _inputs["date"] = $("#i-date").val()
        _inputs["shopcode"] = $("#i-shopcode").val()
        _inputs["customer"] = $("#i-customer").val()
        _inputs["customername"] = $("#i-customer-name").val()
        _inputs['items'] = cpAllItems
        _inputs['total'] = cpTotal.toString()
        _inputs['remark'] = $("#i-remark").val()
        _inputs['paymentmethod'] = $("#i-paymentmethod").val()
        _inputs['shopname'] = $("#i-shopcode option:selected").text()
        _inputs['paymentmethodname'] = $("#i-paymentmethod option:selected").text()
        _inputs['editmode'] = $("#i-edit-mode").val()
        _inputs['formtype'] = $("#i-form-type").val()


        $("#i-post").val(JSON.stringify(_inputs))
        $("#i-shopcode").removeClass("is-invalid")
        $("#i-customer").removeClass("is-invalid")
        if(_inputs['paymentmethod'] == -1){
            $("#i-paymentmethod").addClass("is-invalid")
            _valid = 1
        }
        if(_inputs["shopcode"] == -1){
            $("#i-shopcode").addClass("is-invalid")
            _valid = 1
        }
        if(_inputs["customer"] == ""){
            $("#i-customer").addClass("is-invalid")
            _valid = 1
        }
        if($.isEmptyObject(_inputs["items"])){
            alert("At least one input")
            _valid = 1
        }
        if(_valid == 0){
            $("#this-form").submit();
            
        }
        
    });

    
</script>

