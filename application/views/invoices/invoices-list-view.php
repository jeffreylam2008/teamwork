
    <table id="invoices-tbl" class="table table-striped table-borderedNO" style="width:100%">
        <thead>
            <tr>
                <th>Invoices Number</th>
                <th>Quotations Number</th>
                <th>Shop code / name</th>
                <th>Customer</th>
                <th>Payment Method</th>
                <th>Total</th>
                <th>Invoice Date</th>
                <th>Modify Date</th>
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
                echo "<td>".$val['quotation_code']."</td>";
                echo "<td>(".$val['shop_code'].") - ".$val['shop_name']."</td>";
                echo "<td>".$val['customer']."</td>";
                echo "<td>".$val['payment_method']."</td>";
                echo "<td>$".$val['total']."</td>";
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
        var table = $('#invoices-tbl').DataTable({
                select: {
                    items: 'column'
                },
                "iDisplayLength": <?=$default_per_page?>,
                order : [6,"desc"]
            });
            table.page(<?=$page-1?>).draw('page');
        //console.log(table);
    });
    </script>