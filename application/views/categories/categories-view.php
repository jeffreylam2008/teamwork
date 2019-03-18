<!--<div class="container-fluid">-->
<table id="tbl" class="table table-striped table-borderedNO" style="width:100%">
        <thead>
            <tr>
                <th>#</td>
                <?php 
                    if($user_auth):
                ?>          
                <th></th>
                <?php
                    endif;
                ?>   
                <th>Code</th>
                <th>Description</th>
                <th>create_date</th>
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

                    foreach($data as $key => $val)
                    {
                        echo "<tr>";
                        echo "<td>".($key+1)."</td>";
                        if($user_auth)
                        {
                            echo "<td><a href='".$del_url.$val['cate_code']."'><i class='fas fa-trash-alt'></i></a></td>";
                        }
                        echo "<td><a href='".$base_url.$val['cate_code']."'>".$val['cate_code']."</a></td>";
                        echo "<td>".$val['desc']."</td>";
                        echo "<td>".substr($val['create_date'],0,10)."</td>";
                        echo "<td>".substr($val['modify_date'],0,10)."</td>";
                        echo "</tr>";
                    }
                }

            ?>
        </tbody>
        
    </table>
    <script>

    
    $(document).ready(function() {  
        var table = $('#tbl').DataTable({
            "select": {
                Code : 'column'
            },
            "iDisplayLength": <?=$default_per_page?>,
        });
        table.page(<?=$page-1?>).draw('page');

        $('#tbl').on( 'page.dt', function () {
            var info = table.page.info();
            $(location).attr('href', '<?=$route_url?>'+(info.page+1))
        });
    });

    
    </script>
<!--</div>-->