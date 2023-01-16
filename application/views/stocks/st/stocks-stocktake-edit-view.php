<?php
    // echo "<pre>";
    // print_r($data);
    // echo "</pre>";
    extract($data);
?>

<div class="container-fluid">
    <form class="" method="POST" id="this-form" action="<?=$submit_to?>" enctype="multipart/form-data">
        <div class="input-group mb-2 input-group-sm">
            <div class="input-group-prepend">
                <span class="input-group-text" id=""><?=$this->lang->line("stock_tran_number")?></span>
            </div>            
            <input type="text" class="form-control" id="i-st-num" value="<?=$trans_code?>" disabled>
        </div>
        <div class="input-group mb-2 input-group-sm">
            <div class="input-group-prepend">
                <span class="input-group-text" id=""><?=$this->lang->line("date")?></span>
            </div>
            <input type="text" class="form-control" id="i-date" value="<?=$date?>" disabled>
        </div>

        <!-- Product view -->
        <table class="table table-sm table-striped" id="tbl">
            <thead>
                <th>#</th>
                <th><?=$this->lang->line("item_code")?></th>
                <th><?=$this->lang->line("item_eng_name")?></th>
                <th><?=$this->lang->line("item_chi_name")?></th>
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
                    <td class="col-1" ><?=$k?></td>
                    <td class="col-1" ><?=$item_code?></td>
                    <td class="col-3" ><?=$eng_name?></td>
                    <td class="col-3" ><?=$chi_name?></td>
                    <td class="col-1" >
                        <input type="text" class="form-control form-control-sm item-input" id="qty_<?=$k?>" value="<?=$qty?>"  />
                    </td>
                    <td class="col-1"><?=$unit?></td>
                </tr>
            <?php
                }
            ?>
            <tbody>
        </table>
        <table class="table table-sm table-striped" id="tbl-total">
            <tbody>
                <tr>
                    <td class="col-sm-10"></td>
                </tr>
            </tbody>        
        </table>
        <!-- Product view END -->
        <div class="input-group mb-2 input-group-sm">
            <textarea  class="form-control" rows="3" id="i-remark" placeholder="<?=$this->lang->line("item_remark")?>"><?=$remark?></textarea>
        </div>       
        <input type="hidden" name="i-post" id="i-post" value="" />
        <input type="hidden" class="form-control" id="i-is-convert" value="<?=$is_convert?>">
        <input type="hidden" class="form-control" id="i-prefix" value="<?=$prefix?>">
        <input type="hidden" class="form-control" id="i-employeecode" value="<?=$employee_code?>">
        <input type="hidden" class="form-control" id="i-shopcode" value="<?=$default_shopcode?>">
        <input type="hidden" name="i-form-type" id="i-form-type" value="edit" />
    </form>
</div>

<!-- ///////////////////////////////////////////////////////////////////////////////////////////////////////////////// -->
<script>
    var dbItems = <?=json_encode($ajax["items"],true)?>;
    var items = []
    var selecteditemcode = ""
    var ftotal = 0
    var atleastoneitem_msg = "<?=$this->lang->line('label_atleastoneitem_msg')?>"
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
            items[i] = {
                "index": $(this).children()[0].innerText,
                "item_code": $(this).children()[1].innerText,
                "eng_name" : $(this).children()[2].innerText,
                "chi_name" : $(this).children()[3].innerText,
                "qty" :  $(this).children()[4].children[0].value,
                "unit":  $(this).children()[5].innerText
            }
        });

    }
    function render(){
        // rewrite template
        var tmpl=""
        var i = 1
        for(item in items){
            tmpl += "<tr data-items='itmes_"+item+"'>"
            +"<td class='col-1'>"+i+"</td>"
            +"<td class='col-1'>"+items[item].item_code+"</td>"
            +"<td class='col-3'>"+items[item].eng_name+"</td>"
            +"<td class='col-3'>"+items[item].chi_name+"</td>"
            +"<td class='col-1'><input type='text' class='form-control form-control-sm item-input' id='qty_"+item+"' value='"+items[item].qty+"' <?=$idisabled?> /></td>"
            +"<td class='col-1'>"+items[item].unit+"</td>"
            //+"<td class='col-1'><button class='btn btn-danger btn-sm w-90' data-del-itemcode='"+items[item].item_code+"' id='del_"+item+"' type='button'><i class='fas fa-trash-alt'></i></button></td>"
            +"</tr>"
            i++
            //console.log(tmpl)
        }
        // reapply template
        $("#tdisplay").html(tmpl)

        // reapply table function 
        $.each($("#tbl > tbody > tr"), function(i){
            // remove function
            $('#del_'+i).on("click",function(){
                $(this).parent().parent().remove()
                // return index if found item in source
                let found = items.findIndex(o => o.item_code === $(this).data('del-itemcode'))
                items.splice(found,1)
            });
        });
    }
    // function doFetch(form_name)
    // {
    //     const form = new FormData(document.querySelector('#'+form_name));
    //     const url = '//$import_url'
    //     const request = new Request(url, {
    //         method: 'POST',
    //         body: form
    //     });
    //     return fetch(request)
    //         .then(response => response.json())
    //         .then(data => { return data })
    //         .catch(err => {console.error(err)})
    // }
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
            // recalc()
        }
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
    $("#i-export-all, #next, #delete, #confirm").on("click",function(){
        $(window).off('beforeunload');
    });
    $("#back").on("click", function(){
        doUnLoad();
        $(window).off('beforeunload');
    });
    // Print receipt pop up window
    $("#reprint").on("click",function(){
        window.open('<?=$print_url?>', '_blank', 'location=yes,height=500,width=900,scrollbars=yes,status=yes');
    })
    //construct
    $(document).ready(function(){
        refresh()
        render()
    });
    /* /////////////////// END Libaray Session /////////////////////*/
    
   
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
    // File input change event
    // $("#i-import-submit").on("click", function(){
    //     doFetch("this-form").then(response => {
    //         items = []
    //         const json = response.data;
    //         //console.log(json)
    //         for(i=1; i < json.length; i++){
    //             const item = {}
    //             item['index'] = i
    //             item['item_code'] = json[i][0]
    //             item['eng_name'] = json[i][1]
    //             item['chi_name'] = json[i][2]
    //             if(json[i][3] == "" ) 
    //                 json[i][3] = 0
    //             item['qty'] = json[i][3]
    //             item['unit'] = json[i][4]
    //             items.push(item)
    //         }
    //         //console.log(items)
    //         render()
    //     });
    // });

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
        refresh()
        _inputs["trans_code"] = $("#i-st-num").val()
        _inputs["prefix"] = $("#i-prefix").val()
        _inputs["refer_num"] = $("#i-refer-num").val()
        _inputs["employee_code"] = $("#i-employeecode").val()
        _inputs["date"] = $("#i-date").val()
        _inputs["shopcode"] = $("#i-shopcode").val()
        _inputs['items'] = items
        _inputs['is_convert'] = $("#i-is-convert").val()
        _inputs['remark'] = $("#i-remark").val()
        _inputs['formtype'] = $("#i-form-type").val()

        $("#i-post").val(JSON.stringify(_inputs))
        $("#i-shopcode").removeClass("is-invalid")

        if(_inputs["shopcode"] == -1){
            $("#i-shopcode").addClass("is-invalid")
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