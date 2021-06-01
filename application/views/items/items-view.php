<form name="form1" id="form1" action="" method="GET" > 
    <?=$this->lang->line("category")?>: 
    <div class='btn-group-toggle' id='cate_search' data-toggle='buttons'>

    <?php
    $index = 0;
    // echo "<pre>";
    // print_r($categories);
    // echo "</pre>";
    foreach($categories as $k => $v)
    {
        $active = "";
        if(in_array($v['cate_code'], $where))
        {
            $active = "active";
        }
        if($index % 5 == 0)
            echo "<br>";
        echo "<label class='btn btn-outline-secondary ".$active."'>";
        echo "<input type='checkbox' name='' value='".$v['cate_code']."' autocomplete='off' /> ";
        echo $v['desc'];
        echo "</label>&nbsp;";
        $index++;
    }

    ?>
    <input type="hidden" id="i-all-cate" name="i-all-cate" value="">
    <input type="hidden" id="i-page" name="page" value="<?=$page?>" />
    <input type="hidden" id="i-show" name="show" value="<?=$default_per_page?>" />
    </div>
</form>

<table id="tbl" class="table table-striped table-borderedNO" style="width:100%">
    <thead>
        <tr>
            <th>#</td>      
            <th></th>
            <th><?=$this->lang->line("item_code")?></th>
            <th><?=$this->lang->line("item_eng_name")?></th>
            <th><?=$this->lang->line("item_chi_name")?></th>
            <th><?=$this->lang->line("label_description")?></th>
            <th><?=$this->lang->line("item_Stockonhand")?></th>
            <th><?=$this->lang->line("item_price")?></th>
            <th><?=$this->lang->line("label_create_date")?></th>
            <th><?=$this->lang->line("label_modify_date")?></th>
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
                    echo "<td>".$val['stockonhand']."</td>";
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
    // initial data table
    var table = $('#tbl').DataTable({
        "order" : [[2, "asc"]],
        "select": {
            items: 'column'
        },
        "iDisplayLength": <?=$default_per_page?>,
        "dom": '<"top"flp<"clear">>rt<"bottom"ip<"clear">>',
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
    // set page number from previous
    table.page(<?=($page-1)?>).draw('page');
    
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
        $("#i-page").val($("ul.pagination > li.active > a").text());
        $("#i-show").val($(".dataTables_length > label > select").val());
    });
    
    // search button event
    $("#search").click(function(){
        var cate = ""
        $("#cate_search").children().each(function(i){
            if($(this).hasClass('active'))
                cate += "/" +$(this).children().val();
        });
        $("#i-all-cate").val(cate);
        $("#form1").submit();
    });
    
    // Show create modal page if $_GET _NEW value = 1
    if(<?=$modalshow?>)
        $('#modal01').modal('show');
});
</script>
