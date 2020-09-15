<div class="modal show" id="_modal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-dialog-centered modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="">Delete</h5>
            </div>

            <div class="modal-body">
            <!-- content -->
                <div class="container-fluid">
                    <p>Are you sure to delete Supplier: <u><?=$to_deleted_num?></u>?</p>
                    <?php  if(!$confirm_show) : ?>
                    <p>Transaction : <?=$count?> transactions has included this supplier. Cannot delete: <u><?=$to_deleted_num?></u></p>
                    <?php  endif; ?>
                </div>
            <!-- content end -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" id="canceled" data-dismiss="modal">Cancel</button>
                <?php  if($confirm_show) : ?>
                <button type="button" class="btn btn-danger" id="confirmed" data-dismiss="modal">Yes</button>
                <?php  endif; ?>
            </div>
        </div>
    </div>
</div>
<form class="" method="POST" id="this-form" action="<?=$submit_to?>">
</form>

<script>
$('#_modal').modal({
    backdrop: 'static'
});
$("#canceled").on("click",function(){
    $(this).modal("hide")
    $(this).unbind()
    window.location.href = "<?=$return_url?>";
});
$("#confirmed").on("click",function(){
    $("#this-form").submit();
});

</script>