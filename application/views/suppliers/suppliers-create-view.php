
<!-- Modal -->
<div class="modal fade" id="modal01" tabindex="-1" role="dialog" aria-labelledby="newitem" aria-hidden="true">


    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <!-- Modal Head -->
                <h2 class="modal-title" id=""><b><?=$title?></b></h2>
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
                            <div class="row">
                                <div class="col">
                                    
                                    <div class="form-row">
                                        <div class="col-2">
                                            <label for="t1">Status</label>
                                            <select class="custom-select custom-select-sm" id="i-status" name="i-status">
                                                <option value="Active">Active</option>
                                                <option value="Closed">Closed</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="col-6">
                                            <label for="t1">Supplier Name</label>
                                            <input type="text" class="form-control form-control-sm" name="i-name" id="i-name" placeholder="Name" value="" >
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="col-2">
                                            <label for="t1">Attn *</label>
                                            <input type="text" class="form-control form-control-sm" name="i-attn_1" id="i-attn_1" placeholder="Primary Attn" value="" >
                                        </div>
                                    </div>

                                    <div class="form-row">
                                        <div class="col-6">
                                            <label for="">Mail Address</label>
                                            <input type="text" class="form-control form-control-sm" name="i-mail_addr" id="i-mail_addr" placeholder="Type Something" value="" >
                                        </div>
                                    </div>

                                    <div class="form-row">
                                        <div class="col-3">
                                            <label for="t1">Email</label>
                                            <input type="text" class="form-control form-control-sm" name="i-email_1" placeholder="Primary Email" value="" >
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="col-3">
                                            <label for="t1">Phone *</label>
                                            <input type="text" class="form-control form-control-sm" name="i-phone_1" id="i-phone_1" placeholder="00000000" value="" >
                                        </div>
                                        <div class="col-3">
                                            <label for="t1">Fax</label>
                                            <input type="text" class="form-control form-control-sm" name="i-fax_1" placeholder="00000000" value="" >
                                        </div>
                                    </div>

                                    <div class="form-row">
                                        <div class="col-6">
                                            <label for="t1">Remark</label>
                                            <textarea class="form-control form-control-sm" placeholder="Type Something" name="i-remark" rows="2" ></textarea>
                                        </div>
                                    </div>

                                    <div class="form-row">
                                        <div class="col-2">
                                            <label for="t1">Payment Method</label>
                                            <a class='btn btn-outline-primary btn-sm' href='<?=$new_pm_url?>' type='button'>New</a>
                                            <select class="custom-select custom-select-sm" id="i-paymentmethod" name="i-pm_code" >
                                                <option value="-1">Select...</option>
                                                <?php
                                                    foreach($data_payment_method as $k => $v):
                                                ?>
                                                    <option value="<?=$v['pm_code']?>"><?=$v['payment_method']?></option>
                                                <?php
                                                    endforeach;
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="col-2">
                                            <label for="t1">Payment Terms</label>
                                            <a class='btn btn-outline-primary btn-sm' href='<?=$new_pt_url?>' type='button'>New</a>
                                            <select class="custom-select custom-select-sm" id="i-paymentterms" name="i-pt_code" >
                                                <option value="-1">Select...</option>
                                                <?php
                                                    foreach($data_payment_term as $k => $v):
                                                ?>
                                                    <option value="<?=$v['pt_code']?>"><?=$v['terms']?></option>
                                                <?php
                                                    endforeach;
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                       
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
