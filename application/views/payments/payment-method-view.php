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
                <th><?=$this->lang->line("paymentmethod_code")?></th>
                <th><?=$this->lang->line("paymentmethod_name")?></th>
                <th><?=$this->lang->line("label_create_date")?></th>
                <th><?=$this->lang->line("label_modify_date")?></th>
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
                echo "<td>";
                if($user_auth['edit'])
                {
                    echo "<a href='".$edit_url.$val['pm_code']."'>".$val['pm_code']."</a>";
                }
                else
                {
                    echo $val['pm_code'];
                }
                echo "</td>";
                echo "<td>".$val['payment_method']. "</td>";
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
            "order" : [0,"asc"],
            select: {
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
        if(<?=$modalshow?>)
            $('#modal01').modal('show');
    });
    </script>