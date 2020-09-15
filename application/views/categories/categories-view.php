<!--<div class="container-fluid">-->
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
                        if($user_auth['delete'])
                        {
                            echo "<td><a href='".$del_url.$val['cate_code']."'><i class='fas fa-trash-alt'></i></a></td>";
                        }
                        echo "<td>";
                        if($user_auth['edit'])
                        {
                            echo "<a href='".$base_url.$val['cate_code']."'>".$val['cate_code']."</a>";
                        }
                        else
                        {
                            echo $val['cate_code'];
                        }
                        echo "</td>";
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
        // initial data table
        var table = $('#tbl').DataTable({
            "order" : [[4, "desc"]],
            "select": {
                Code : 'column'
            },
            "iDisplayLength": <?=$default_per_page?>,
        });
        // set page number from previous
        table.page(<?=$page - 1?>).draw('page');

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
<!--</div>-->