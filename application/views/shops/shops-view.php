
<table id="tbl" class="table table-striped table-borderedNO" style="width:100%">
    <thead>
        <tr>
            <td>#</td>
            <td><?=$this->lang->line('shop_code')?></td>
            <td><?=$this->lang->line('shop_name')?></td>
            <td><?=$this->lang->line('shop_phone')?></td>
            <td><?=$this->lang->line('shop_Addr1')?></td>
            <td><?=$this->lang->line('shop_Addr2')?></td>
        </tr>
    <thead>
    <tbody>
        <?php
            $edit_auth = "";
            foreach($data as $k => $v): 
        ?>
        <tr>
            <td><?=($k + 1)?></td>
            <?php
            if($user_auth):
                $edit_auth = "href=".$edit_url.$v['shop_code'];
            endif;
            ?>
            <td><a <?=$edit_auth?> ><?=$v['shop_code']?></a></td>
            <td><?=$v['name']?></td>
            <td><?=preg_replace("/(\d{4})(\d{4})/", "$1-$2", $v['phone']);?></td>
            <td><?=$v['address1']?></td>
            <td><?=$v['address2']?></td>
        </tr>
        <?php
            endforeach;
        ?>
    </tbody>
</table>
<script>
$(document).ready(function() {  
    var table = $('#tbl').DataTable({
        "order" : [[1, "desc"]],
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
});

</script>

