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
                <th>Employee Code</th>
                <th>Username</th>
                <th>Default Shop</th>
                <th>Access Level</th>
                <th>Role</th>
                <th>Status</th>
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
                    echo "<td><a href='".$del_url.$val['employee_code']."'><i class='fas fa-trash-alt'></i></a></td>";
                }
                echo "<td>";
                if($user_auth['edit'])
                {
                    echo "<a href='".$edit_url.$val['employee_code']."'>".$val['employee_code']."</a>";
                }
                else
                {
                    echo $val['employee_code'];
                }
                echo "<td>".$val['username']."</td>";
                echo "<td>".$val['shop_name']."</td>";
                echo "<td>".$val['access_level']."</td>";
                echo "<td>".$val['role_code']."</td>";
                echo "<td>".$val['status_name']."</td>";
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
        });
        //console.log(table);
    });
    </script>