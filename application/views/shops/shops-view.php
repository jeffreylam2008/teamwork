<table id="tbl" class="table table-striped table-borderedNO" style="width:100%">
    <thead>
        <tr>

        </tr>
    <thead>
    <tbody>
        
    </tbody>
</table>
<script>
$(document).ready(function() {  
    var table = $('#tbl').DataTable({
        "select": {
            items: 'column'
        },
        "iDisplayLength": <?=$default_per_page?>,
    });
    table.page(<?=$page-1?>).draw('page');

    $('#tbl').on( 'page.dt', function () {
        var info = table.page.info();
        $(location).attr('href', '<?=$route_url?>'+(info.page+1))
    });
});
</script>
