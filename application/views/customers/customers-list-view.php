<table id="tbl" class="table table-striped table-borderedNO" style="width:100%">
        <thead>
            <tr>
                <th></th>
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
        // var_dump($data);
        // echo "</pre>";
            extract($data);
            foreach($query as $key => $val)
            {
                echo "<tr>";
                echo "<td><a href='".$url.$val['cust_code']."'>".$val['cust_code']."</a></td>";
                echo "<td>".$val['name']."</td>";
                echo "<td></td>";
                echo "<td></td>";
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