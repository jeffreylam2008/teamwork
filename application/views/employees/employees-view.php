<table id="tbl" class="table table-striped table-borderedNO" style="width:100%">
        <thead>
            <tr>
                <th>#</th>
                    <?php 
                        if($user_auth['delete']):
                    ?>                 
                    <th></th>
                    <?php
                        endif;
                    ?>
                <th><?=$this->lang->line("employee_id")?></th>
                <th><?=$this->lang->line("employee_username")?></th>
                <th><?=$this->lang->line("employee_default_shop")?></th>
                <th><?=$this->lang->line("employee_access_level")?></th>
                <th><?=$this->lang->line("employee_role")?></th>
                <th><?=$this->lang->line("employee_status")?></th>
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

                if($user_auth['edit'])
                {
                    echo "<td><a href='".$edit_url.$val['employee_code']."'>".$val['employee_code']."</a></td>";
                }
                else
                {
                    echo "<td>".$val['employee_code']."</td>";
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
            "select": {
                items: 'column'
            },
            "iDisplayLength": <?=$default_per_page?>,
            "language": {
                "emptyTable" : "<?=$this->lang->line('label_emptytable')?>",
                "infoEmpty":   "<?=$this->lang->line('label_infoEmpty')?>",
                "lengthMenu" : "<?=$this->lang->line('function_page_showing')?> _MENU_",
                "search": "<?=$this->lang->line('function_search')?> :",
                "info": "<?=$this->lang->line('function_page_showing')?> _START_ <?=$this->lang->line('function_page_to')?> _END_ <?=$this->lang->line('function_page_of')?> _TOTAL_ <?=$this->lang->line('function_page_entries')?>",
                "paginate": {
                    "first": "<?=$this->lang->line('function_first')?>",
                    "last": "<?=$this->lang->line('function_last')?>",
                    "next": "<?=$this->lang->line('function_next')?>",
                    "previous": "<?=$this->lang->line('function_previous')?>"
                }
            }
        });
        table.page(<?=$page-1?>).draw('page');

        table.on( 'draw', function () {
            var urlParams = new URLSearchParams(location.search)
            var tPage = table.page() + 1
            urlParams.set('page', tPage)
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
            $("#i-page").val(tPage);
            $("#i-show").val($(".dataTables_length > label > select").val());
        });
        //console.log(table);
    });
    </script>