<form name="form1" id="form1" action="" method="GET" > 
Categories: 
<div class='btn-group-toggle' data-toggle='buttons'>

<?php


foreach($categories as $k => $v)
{
    $active = "";


    if(in_array($k, $where))
    {
        $active = "active";

    }

    echo "<label class='btn btn-info ".$active."'>";
    
    echo "<input type='checkbox' name='".$k."' value='1' autocomplete='off' /> ";
    echo $v;
    echo "</label>&nbsp;";
}

?>
</div>

</form>


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
            <th>English Name</th>
            <th>Chinese Name</th>
            <th>Description</th>
            <th>Price</th>
            <th>create_date</th>
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
                    if($user_auth)
                    {
                        echo "<td><a href='".$del_url.$val['item_code']."'><i class='fas fa-trash-alt'></i></a></td>";
                    }
                    echo "<td><a href='".$edit_url.$val['item_code']."'>".$val['item_code']."</a></td>";
                    echo "<td>".$val['eng_name']."</td>";
                    echo "<td>".$val['chi_name']."</td>";
                    echo "<td>".$val['desc']."</td>";
                    echo "<td>$".$val['price']."</td>";
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
        "order" : [[0, "asc"]],
        "select": {
            items: 'column'
        },
        "iDisplayLength": <?=$default_per_page?>,
    });
    table.page(<?=($page-1)?>).draw('page');
    
    $('#tbl').on( 'page.dt', function () {
        var info = table.page.info();
        $(location).attr('href', '<?=$route_url?>'+(info.page+1))
    });

    $("#search").click(function(){
        $("#form1").submit();
    });
    
});
</script>
