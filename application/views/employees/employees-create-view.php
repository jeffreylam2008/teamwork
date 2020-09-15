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
                            <div class="form-row">
                                <div class="col-3">
                                    <label for="">* Employee ID</label>
                                    <input type="text" class="form-control form-control-sm" name="i-emp-code" placeholder="Employee ID" value="" >
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="col-3">
                                    <label for="">* Username</label>
                                    <input type="text" class="form-control form-control-sm" name="i-username" placeholder="Username" value="" >
                                </div>
                                <div class="col-3">
                                    <label for="">* Password</label>
                                    <input type="password" class="form-control form-control-sm" name="i-pwd" id="i-pwd" placeholder="Password" value="" >
                                </div>
                                <div class="col-3">
                                    <label for="">* Comfirm Password</label>
                                    <input type="password" class="form-control form-control-sm" name="i-confirm-pwd" placeholder="Confirm Password" value="" >
                                </div>
                            </div>
                        
                            <div class="form-row">
                                <div class="col-3">
                                    <label for="">Default Shop Code</label>
                                    <select class="custom-select custom-select-sm" id="i-shops" name="i-shops" >
                                        <?php 
                                        foreach($data as $key => $val):
                                        ?>
                                        <option value="<?=$val['shop_code']?>"><?=$val['name']?></option>
                                        <?php
                                        endforeach;
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="col-2">
                                    <label for="">Status</label>
                                    <select class="custom-select custom-select-sm" id="i-status" name="i-status" >
                                        <option value="1">Active</option>
                                        <option value="0">Disable</option>
                                    </select>
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
                "i-emp-code": {
                    required: true,
                    digits:true,
                    minlength: 5,
                    maxlength: 10
                },
                "i-username": {
                    required: true
                },
                "i-pwd": {
                    required: true
                },
                "i-confirm-pwd": {
                    required: true,
                    equalTo: "#i-pwd"
                }
            }
        });
        if(isvalid){
            $("#form1").submit();
        }
        
    });
</script>
