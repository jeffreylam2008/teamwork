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
            <th>Supplier Code</th>
            <th>Name</th>
			<th>Attn</th>
            <th>Phone</th>
			<th>fax</th>
            <th>Email</th>
            <th>Status</th>
            <th>Create Date</th>
            <th>Modify Date</th>
            
        </tr>
    <thead>
    <tbody>
    <?php

        // echo "<pre>";
        // var_dump($data);
        // echo "</pre>";
            foreach($data as $key => $val)
            {
                echo "<tr>";
                echo "<td>".($key+1)."</td>";
                if($user_auth['delete'])
                {
                    echo "<td><a href='".$del_url.$val['supp_code']."'><i class='fas fa-trash-alt'></i></a></td>";
                }
                echo "<td>";
                if($user_auth['edit'])
                {
                    echo "<a href='".$detail_url.$val['supp_code']."'>".$val['supp_code']."</a>";
                }
                else
                {
                    echo $val['supp_code'];
                }
                echo "</td>";
				echo "<td>".$val['name']."</td>";
				echo "<td>".$val['attn_1']."</td>";
                echo "<td>".$val['phone_1']."</td>";
                echo "<td>".$val['fax_1']."</td>";
				echo "<td>".$val['email_1']."</td>";
				echo "<td>".$val['status']."</td>";
				echo "<td>".$val['create_date']."</td>";
				echo "<td>".$val['modify_date']."</td>";
                echo "</tr>";
            }
        
    ?>
    </tbody>
    
</table>

    <script>
    $(document).ready(function() { 
        
        var param = ""
        // init data table 
        var table = $('#tbl').DataTable({
            "order" : [[2, "desc"]],
            select : {
                items : 'column'
            },
            "iDisplayLength": <?=$default_per_page?>
        });
        // set table current page
        table.page(<?=$page-1?>).draw('page');
        // Change query string while change page and page page setting
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

        // Show create modal page if $_GET _NEW value = 1
        if(<?=$modalshow?>)
            $('#modal01').modal('show');
    });
    </script>