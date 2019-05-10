<form name="form1" id="form1" action="" method="GET" > 
Categories: 
<div class='btn-group-toggle' id='cate_search' data-toggle='buttons'>

<?php
$index = 0;

foreach($categories as $k => $v)
{
    $active = "";
    if(in_array($k, $where))
    {
        $active = "active";
    }
    if($index % 5 == 0)
        echo "<br>";
    echo "<label class='btn btn-outline-secondary ".$active."'>";
    echo "<input type='checkbox' name='' value='".$k."' autocomplete='off' /> ";
    echo $v;
    echo "</label>&nbsp;";
    $index++;
}

?>
<input type="hidden" value="" id="i-all-cate" name="i-all-cate">
</div>

</form>


<table id="tbl" class="table table-striped table-borderedNO" style="width:100%">
    <thead>
        <tr>
            <th>#</td>      
            <th></th>
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
                $edit_auth = "";
                foreach($data as $key => $val)
                {
                    echo "<tr>";
                    echo "<td>".($key+1)."</td>";
                    if($user_auth)
                    {
                        $edit_auth = "href=".$edit_url.$val['item_code'];
                    }
                    echo "<td><a href='".$del_url.$val['item_code']."'><i class='fas fa-trash-alt'></i></a></td>";
                    echo "<td><a ".$edit_auth.">".$val['item_code']."</a></td>";
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
        var cate = ""
        $("#cate_search").children().each(function(i){
            if($(this).hasClass('active'))
                cate += "/" +$(this).children().val();
        });
        $("#i-all-cate").val(cate);
        $("#form1").submit();
    });
    
});
</script>
