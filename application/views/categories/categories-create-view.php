<!--<form id="form1" action="" name="form1" method="post">
    <div class="card">
        <div class="card-body"> 
            <div class="form-row">
                <div class="col-3">
                    <label for="">Category Code</label>
                    <input type="text" class="form-control form-control-sm" name="i-catecode" placeholder="Categorg Code" value="">
                </div>
                <div class="col-3">
                    <label for="">Description</label>
                    <input type="text" class="form-control form-control-sm" name="i-desc" placeholder="Description" value="">
                </div>
            </div>

        </div>
    </div>

</form>-->


<!-- Modal -->
<div class="modal fade" id="modal01" tabindex="-1" role="dialog" aria-labelledby="newitem" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <!-- Modal Head -->
                <h2 class="modal-title" id=""><b>Create New Category</b></h2>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
                <!-- Modal Head End -->
            </div>
            <div class="modal-body">
                <?php echo $function_bar; ?>
                <!-- <button type="button" class="btn btn-outline-secondary btn-sm" data-dismiss="modal">Back</button>
                <button type="button" id="reset" class="btn btn-outline-secondary btn-sm">Reset</button>
                <button type="button" id="save" class="btn btn-outline-primary btn-sm">Save</button> -->
                <!-- Modal Content -->
                <form id="form1" name="form1" method="POST" action="<?=$save_url?>">
                    <div class="card">
                        <div class="card-body"> 
                            <div class="form-row">
                                <div class="col-3">
                                    <label for="">Category Code</label>
                                    <input type="text" class="form-control form-control-sm" name="i-catecode" placeholder="Categorg Code" value="">
                                </div>
                                <div class="col-3">
                                    <label for="">Description</label>
                                    <input type="text" class="form-control form-control-sm" name="i-desc" placeholder="Description" value="">
                                </div>
                            </div>

                        </div>
                    </div>
                </form>
                <!-- Modal Content End-->
            </div>
        </div>
    </div>
</div>

<script>

    $(function() {
        $( "#form1" ).sisyphus( {
            locationBased: false,
            timeout: 5,
            autoRelease: true,
            onSave: function(){
                console.log("saved")
            }
        });
        $("#reset").click(function(){
            $("#form1").trigger("reset");
        });
        $("#modal01").on('hidden.bs.modal', function(){
            $("#form1").trigger("reset");
        });
    });
    // configure your validation
    $("#save").click(function(){
        $.validator.addMethod("selectValid", function(value, element, arg){
            return arg !== value;
        }, "This field is required.");

        var isvalid = $("#form1").validate({
            rules: {
                // simple rule, converted to {required:true}
                "i-catecode": {
                    required: true,
                    minlength: 2
                }
            }
        });
        if(isvalid){
            $("#form1").submit();
        }
        
    });
</script>
