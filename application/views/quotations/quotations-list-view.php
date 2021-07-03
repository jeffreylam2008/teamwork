<form class="" method="GET" id="this-form" action="<?=$submit_to?>">
    <!-- search selection -->
    Advanced Search:
    <input type="hidden" name="page" value="<?=$page?>" />
    <input type="hidden" name="show" value="<?=$default_per_page?>" />
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
                    <span class="input-group-text" id="basic-addon1"><?=$this->lang->line("quotation_number")?></span>
                </div>
                <input type="text" class="form-control" id="i-quotation-num" name="i-quotation-num" value="<?=$ad_quotation_num?>" placeholder="#" />
            </div>
        </div>
        <div class="col-3">
            <div class="input-group input-group-sm mb-3">
                <div class="input-group-prepend">
                    <span class="input-group-text" id="basic-addon1"><?=$this->lang->line("customer_label")?></span>
                </div>
                <input type="text" class="form-control" id="i-cust-code" name="i-cust-code" value="" placeholder="#" />
            </div>
        </div>
    </div>
</form>
    <!-- table -->
    <table id="tbl" class="table table-sm table-borderedNO" style="width:100%">
        <thead>
            <tr>
                <th><?=$this->lang->line("quotation_number")?></th>
                <th><?=$this->lang->line("company")?></th>
                <th><?=$this->lang->line("customer_name")?></th>
                <th><?=$this->lang->line("customer_payment_method")?></th>
                <th><?=$this->lang->line("common_total")?></th>
                <th><?=$this->lang->line("common_is_convert")?></th>
                <th><?=$this->lang->line("label_create_date")?></th>
                <th><?=$this->lang->line("label_modify_date")?></th>
            </tr>
        <thead>
        <tbody>
    <?php
        if(!empty($data))
        {
        // echo "<pre>";
        // var_dump($data);
        // echo "</pre>";
            
            extract($data);
            foreach($query as $key => $val)
            {
                
                echo "<tr>";
                echo "<td><a href='".$url.$val['trans_code']."'>".$val['trans_code']."</a></td>";
                echo "<td>(".$val['shop_code'].") - ".$val['shop_name']."</td>";
                echo "<td>".$val['customer']."</td>";
                echo "<td>".$val['payment_method']."</td>";
                echo "<td>$".$val['total']."</td>";
                echo $val['is_convert'] ? "<td>YES</td>":"<td>NO</td>";
                echo "<td>".$val['create_date']."</td>";
                echo "<td>".$val['modify_date']."</td>";
                echo "</tr>";
            }
        }
    ?>
    </tbody>
    </table>

<script>

    $(document).ready(function() { 
        var table = $('#tbl').DataTable({
            select: {
                items: 'column'
            },
            "iDisplayLength": <?=$default_per_page?>,
            order : [6,"desc"],
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
            urlParams.set('page', $("ul.pagination > li.active > a").text())
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
            $("#i-page").val($("ul.pagination > li.active > a").text());
            $("#i-show").val($(".dataTables_length > label > select").val());
        });

        $('.input-group.date').datepicker({
            format: "yyyy-mm-dd",
            orientation: "bottom left",
            todayHighlight: true,
            autoclose: true
        });

        $("#i-search").on("click",function(){
            $("#this-form").submit()
        });
        $("#i-clear").on("click",function(){
            $("#i-start-date").val("")
            $("#i-end-date").val("")
            $("#i-quotation-num").val("")
            $("#i-cust-code").val("")
        });
       
    });
</script>