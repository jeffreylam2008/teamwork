<!-- Modal -->
<div class="modal fade" id="modal01" tabindex="-1" role="dialog" aria-labelledby="newitem" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <!-- Modal Head -->
                <h2 class="modal-title" id=""><b>New Employee</b></h2>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
                <!-- Modal Head End -->
            </div>
            <div class="modal-body">
                <?php echo $function_bar; ?>
                <!-- Modal Content -->
                <form id="form1" name="form1" method="POST" action="<?=$save_url?>">
                    <div class="card">
                        <div class="card-body">
                           
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
        // $( "#form1" ).sisyphus( {
        //     locationBased: false,
        //     timeout: 5,
        //     autoRelease: true,
        //     onSave: function(){
        //         console.log("saved")
        //     }
        // });
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
                "i-attn_1": {
                    required: true
                },
                "i-phone_1": {
                    required: true,
                    digits:true,
                    minlength: 8,
                    maxlength: 9
                }
            }
        });
        if(isvalid){
            $("#form1").submit();
        }
        
    });
</script>
