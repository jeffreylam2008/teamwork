<table id="tbl" class="table table-striped table-borderedNO" style="width:100%">
        <thead>
            <tr>
                <th>Customer Code</th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
            </tr>
        <thead>
        <tbody>
    <?php
        if(!empty($data))
        {
        // echo "<pre>";
        // var_dump($paymethod);
        // echo "</pre>";
            extract($data);
            foreach($query as $key => $val)
            {
                if(array_key_exists($val['pm_code'],$paymethod['query']))
                {
                    $_pm_code = $paymethod['query'][$val['pm_code']]['payment_method'];
                }
                echo "<tr>";
                echo "<td><a href='".$url.$val['cust_code']."'>".$val['cust_code']."</a></td>";
                echo "<td>".$val['name']."</td>";
                echo "<td>".$val['phone_1']."</td>";
                echo "<td>".$_pm_code."</td>";
                echo "<td></td>";
                echo "<td></td>";
                echo "<td></td>";
                echo "<td></td>";
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
                "iDisplayLength": <?=$default_per_page?>
            });
            table.page(<?=$page-1?>).draw('page');
        //console.log(table);
    });
    </script>