
<table id="tbl" class="table table-striped table-borderedNO" style="width:100%">
    <thead>
        <tr>
            <th>#</td>
            <th></th>
            <th>Customer Code</th>
            <th>Name</th>
            <th>Delivery Address</th>
            <th>Contact Number</th>
            <th>Payment Method</th>
            
        </tr>
    <thead>
    <tbody>
    <?php
        if(!empty($data))
        {
        // echo "<pre>";
        // var_dump($paymethod);
        // echo "</pre>";
        $edit_auth = "";
            foreach($data as $key => $val)
            {
                echo "<tr>";
                echo "<td>".($key+1)."</td>";
                if($user_auth)
                {
                    $edit_auth = "href=".$edit_url.$val['cust_code'];
                }
                echo "<td><a href='".$del_url.$val['cust_code']."'><i class='fas fa-trash-alt'></i></a></td>";
                echo "<td><a ".$edit_auth.">".$val['cust_code']."</a></td>";
                echo "<td>".$val['name']."</td>";
                echo "<td>".$val['delivery_addr']."</td>";
                echo "<td>".$val['phone_1']."</td>";
                echo "<td>".$val['payment_method']."</td>";
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
        if(<?=$modalshow?>)
            $('#modal01').modal('show');
    });
    </script>