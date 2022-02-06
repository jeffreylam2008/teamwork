<div class="modal show" id="_modal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-dialog-centered modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="">Adjustment</h5>
            </div>

            <div class="modal-body">
            <!-- content -->
                <div class="container-fluid">
                    <p><?=$this->lang->line("label_stocktakeadjust_msg")?> (<?=$to_deleted_num?>) ?</p>
                </div>
            <!-- content end -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" id="canceled" data-dismiss="modal"><?=$this->lang->line("function_cancel")?></button>
                <button type="button" class="btn btn-danger" id="confirmed" data-dismiss="modal"><?=$this->lang->line("function_yes")?></button>
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