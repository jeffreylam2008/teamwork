<?php 
    // echo "<pre>";
    // var_dump($data);
    // echo "</pre>";
    extract($data);
?>
<form class="" method="GET" id="this-form" action="<?=$submit_to?>">
    <!-- search selection -->
    <?=$this->lang->line("function_adv_search")?>
    <input type="hidden" name="page" id="i-page" value="<?=$page?>" />
    <input type="hidden" name="show" id="i-show" value="<?=$default_per_page?>" />
    <div class="row">
        <div class="col-3">
            <div class="input-group input-group-sm mb-3 date">
                <div class="input-group-prepend">
                    <span class="input-group-text" id="basic-addon1"><?=$this->lang->line("function_start_date")?></span>
                </div>
                <input type="text" class="form-control" id="i-start-date" name="i-start-date" value="<?=$ad_start_date?>" placeholder="yyyy-mm-dd" />
                <div class="input-group-append">
                    <span class="input-group-text">
                    <i class="far fa-calendar-alt"></i>
                    </span>
                </div>
            </div>
        </div>
        <div class="col-3">
            <div class="input-group input-group-sm mb-3 date">
                <div class="input-group-prepend">
                    <span class="input-group-text" id="basic-addon1"><?=$this->lang->line("function_end_date")?></span>
                </div>
                <input type="text" class="form-control" id="i-end-date" name="i-end-date" value="<?=$ad_end_date?>" placeholder="yyyy-mm-dd" />
                <div class="input-group-append">
                    <span class="input-group-text">
                    <i class="far fa-calendar-alt"></i>
                    </span>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-6">
            <div class="input-group input-group-sm mb-3">
                <div class="input-group-prepend">
                    <span class="input-group-text" id="basic-addon1"><?=$this->lang->line("purchase_number")?></span>
                </div>
                <input type="text" class="form-control" id="i-num" name="i-num" value="<?=$ad_num?>" placeholder="#" />
            </div>
        </div>
        <div class="col-3">
            <div class="input-group input-group-sm mb-3">
                <div class="input-group-prepend">
                    <span class="input-group-text" id="basic-addon1"><?=$this->lang->line("supplier_id")?></span>
                </div>
                <input type="text" class="form-control" id="i-supp-code" name="i-supp-code" value="<?=$ad_supp_code?>" placeholder="#" />
            </div>
        </div>
    </div>
    
</form>
    <!-- table -->
    <table id="invoices-tbl" class="table table-striped table-borderedNO" style="width:100%">
        <thead>
            <tr>
                <th><?=$this->lang->line("purchase_number")?></th>
                <th><?=$this->lang->line("purchase_reference_number")?></th>
                <th><?=$this->lang->line("purchase_shop_code")?></th>
                <th><?=$this->lang->line("supplier")?></th>
                <th><?=$this->lang->line("payment_method")?></th>
                <th><?=$this->lang->line("purchase_remark")?></th>
                <th><?=$this->lang->line("common_total")?></th>
                <th><?=$this->lang->line("label_create_date")?></th>
                <th><?=$this->lang->line("label_modify_date")?></th>
            </tr>
        <thead>
        <tbody>
        <?php

            foreach($data as $key => $val)
            {
                echo "<tr>";
                echo "<td><a href='".$edit_url.$val['trans_code']."'>".$val['trans_code']."</a></td>";
                echo "<td>".$val['refer_code']."</td>";
                echo "<td>(".$val['shop_code'].") - ".$val['shop_name']."</td>";
                echo "<td>(".$val['supp_code'].") - ".$val['supp_name']."</td>";
                echo "<td>".$val['payment_method']."</td>";
                echo "<td>".$val['remark']."</td>";
                echo "<td>$".number_format($val['total'],2,".",",")."</td>";
                echo "<td>".$val['create_date']."</td>";
                echo "<td>".$val['modify_date']."</td>";
                echo "</tr>";
            }
        
        ?>
        </tbody>
    </table>

<script>

    $(document).ready(function() { 
        var table = $('#invoices-tbl').DataTable({
            select: {
                items: 'column'
            },
            "iDisplayLength": <?=$default_per_page?>,
            order : [7,"desc"],
            "language": {
                "emptyTable" : "<?=$this->lang->line('label_emptytable')?>",
                "infoEmpty":   "<?=$this->lang->line('label_infoEmpty')?>",
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
        // capture page while page refreshing
        table.page(<?=$page-1?>).draw('page');

        table.on( 'draw', function () {
            var urlParams = new URLSearchParams(location.search)
            var tPage = table.page() + 1
            urlParams.set('page', tPage)
            urlParams.set('show', $(".dataTables_length > label > select").val())
            window.history.replaceState({}, '', `${location.pathname}?${urlParams.toString()}`);
            // search for all a href on this page and append query string at the end
            $.each($("tbody > tr"), function(i){
                $.each($(this).children(), function(j){
                    if($(this)[0].children[0] != undefined)
                    {
                        var q = $(this)[0].children[0]
                        if(q.href.indexOf('?') === -1)
                        {
                            q.href += `?${urlParams.toString()}`
                        }
                    }
                });
            });
            $("#i-page").val(tPage);
            $("#i-show").val($(".dataTables_length > label > select").val());
        });

        $('.input-group.date').datepicker({
            format: "yyyy-mm-dd",
            orientation: "bottom left",
            todayHighlight: true,
            autoclose: true
        });
        $('#i-num, #i-supp-code').on("keypress", function(e){
            if(e.keyCode==13){
                $("#this-form").submit()
            }
        });
        $("#i-search").on("click",function(){
            $("#this-form").submit()
        });
        $("#i-clear").on("click",function(){
            $("#i-start-date").val("")
            $("#i-end-date").val("")
            $("#i-num").val("")
            $("#i-supp-code").val("")
        });
       
    });
</script>