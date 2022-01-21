
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
                    echo "<td><a href='".$del_url.$val['pt_code']."'><i class='fas fa-trash-alt'></i></a></td>";
                }
                echo "<td>";
                if($user_auth['edit'])
                {
                    echo "<a href='".$edit_url.$val['pt_code']."'>".$val['pt_code']."</a>";
                }
                else
                {
                    echo $val['pt_code'];
                }
                echo "</td>";
                echo "<td>".$val['terms']."</td>";
               
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

