<?php
// echo "<pre>";
// print_r($data);
// echo "</pre>";
?>
<form class="" method="GET" id="this-form" action="<?=$submit_to?>">
    <!-- search selection -->
    Advanced Search:
    <input type="hidden" name="page" id="i-page" value="<?=$page?>" />
    <input type="hidden" name="show" id="i-show" value="<?=$default_per_page?>" />
    <div class="row">
        <div class="col-3">
            <div class="input-group input-group-sm mb-3 date">
                <div class="input-group-prepend">
                    <span class="input-group-text" id="basic-addon1">Start Date</span>
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
                    <span class="input-group-text" id="basic-addon1">End Date</span>
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
                    <span class="input-group-text" id="basic-addon1">DN Number</span>
                </div>
                <input type="text" class="form-control" id="i-invoice-num" name="i-dn" value="<?=$ad_dn?>" placeholder="#" />
            </div>
        </div>
        <div class="col-3">
            <div class="input-group input-group-sm mb-3">
                <div class="input-group-prepend">
                    <span class="input-group-text" id="basic-addon1">Customer Code</span>
                </div>
                <input type="text" class="form-control" id="i-cust-code" name="i-cust-code" value="<?=$ad_cust_code?>" placeholder="#" />
            </div>
        </div>
    </div>
    
</form>
    <!-- table -->
    <table id="invoices-tbl" class="table table-striped table-borderedNO" style="width:100%">
        <thead>
            <tr>
                <th>Transaction Number</th>
                <th>Reference Number</th>
                <th>Shop code / name</th>
                <th>Customer</th>
                <th>Supplier</th>
                <th>Total</th>
                <th>Remark</th>
                <th>Create Date</th>
                <!--<th>Modify Date</th>-->
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
                    $cust = !empty($val['cust_code']) ? "(".$val['cust_code'].") - ".$val['customer'] : "";
                    $supp = !empty($val['supp_code'] ) ? "(".$val['supp_code'].") - ".$val['supplier'] : "";
                    echo "<tr>";
                    echo "<td><a href='".$edit_url.$val['trans_code']."'>".$val['trans_code']."</a></td>";
                    echo "<td><a href='".$edit_url.$val['refer_code']."'>".$val['refer_code']."</a></td>";
                    echo "<td>(".$val['shop_code'].") - ".$val['shop_name']."</td>";
                    echo "<td>".$cust."</td>";
                    echo "<td>".$supp."</td>";
                    echo "<td>".$val['total'] ."</td>";
                    echo "<td>".$val['remark']."</td>";
                    echo "<td>".$val['create_date']."</td>";
                    //echo "<td>".$val['modify_date']."</td>";
                    echo "</tr>";
                }
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
            order : [7,"desc"]
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
            $("#i-invoice-num").val("")
            $("#i-cust-code").val("")
        });
       
    });
</script>