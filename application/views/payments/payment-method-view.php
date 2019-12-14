<table id="tbl" class="table table-striped table-borderedNO" style="width:100%">
        <thead>
            <tr>
                <th>#</td>
                    <?php 
                        if($user_auth['delete']):
                    ?>                 
                    <th></th>
                    <?php
                        endif;
                    ?>   
                <th>Code</th>
                <th>Method</th>
                <th>Create Date</th>
                <th>Modify Date</th>
            </tr>
        <thead>
        <tbody>
    <?php
        if(!empty($data))
        {
            foreach($data as $key => $val)
            {
                echo "<tr>";
                echo "<td>".($key+1)."</td>";
                if($user_auth['delete'])
                {
                    echo "<td><a href='".$del_url.$val['pm_code']."'><i class='fas fa-trash-alt'></i></a></td>";
                }
               
                echo "<td>".$val['pm_code']."</td>";
                echo "<td>";
                if($user_auth['edit'])
                {
                    echo "<a href='".$edit_url.$val['pm_code']."'>".$val['payment_method']."</a>";
                }
                else
                {
                    echo $val['payment_method'];
                }
                echo "</td>";
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
                "iDisplayLength": <?=$default_per_page?>
            });
            table.page(<?=$page-1?>).draw('page');
        //console.log(table);
    });
    </script>