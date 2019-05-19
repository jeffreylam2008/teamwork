
<!-- Modal -->
<div class="modal fade" id="modal01" tabindex="-1" role="dialog" aria-labelledby="newitem" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <!-- Modal Head -->
                <h2 class="modal-title" id=""><b>New Customer</b></h2>
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
                                <div class="col-12">
                                    <label for="t1">Customer Code *</label>
                                    <div class="form-inline">
                                        <div class="input-group my-1 mr-sm-2">
                                            <input type="text" class="form-control form-control-sm" name="i-cust_code" id="i-cust_code" placeholder="Customer Code" value="">
                                            <a href="#" class="btn btn-outline-primary btn-sm" id="gen-id" >Generate</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="col-6">
                                    <label for="t1">Customer Shop</label>
                                    <input type="text" class="form-control form-control-sm" name="i-name" placeholder="Name" value="">
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="col-6">
                                    <label for="t1">Group Name</label>
                                    <input type="text" class="form-control form-control-sm" name="i-group_name" placeholder="Group" value="">
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="col-2">
                                    <label for="t1">Primary Attn *</label>
                                    <input type="text" class="form-control form-control-sm" name="i-attn_1" placeholder="Primary Attn" value="">
                                </div>
                                <div class="col-2">
                                    <label for="t1">Secondary Attn </label>
                                    <input type="text" class="form-control form-control-sm" name="i-attn_2" placeholder="Secondary Attn" value="">
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="col-4">
                                    <label for="">Mail Address</label>
                                    <input type="text" class="form-control form-control-sm" name="i-mail_addr" placeholder="Type Something" value="">
                                </div>
                                <div class="col-4">
                                    <label for="">Shop Address</label>
                                    <input type="text" class="form-control form-control-sm" name="i-shop_addr" placeholder="Type Something" value="">
                                </div>
                                <div class="col-4">
                                    <label for="">Delivery Address</label>
                                    <input type="text" class="form-control form-control-sm" name="i-delivery_addr" placeholder="Type Something" value="">
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="col-3">
                                    <label for="t1">Primary Email</label>
                                    <input type="text" class="form-control form-control-sm" name="i-email_1" placeholder="Primary Email" value="">
                                </div>
                                <div class="col-3">
                                    <label for="t1">Secondary Email</label>
                                    <input type="text" class="form-control form-control-sm" name="i-email_2" placeholder="Secondary Email" value="">
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="col-3">
                                    <label for="t1">Phone 1 *</label>
                                    <input type="text" class="form-control form-control-sm" name="i-phone_1" placeholder="0000 0000" value="">
                                </div>
                                <div class="col-3">
                                    <label for="t1">Fax 1</label>
                                    <input type="text" class="form-control form-control-sm" name="i-fax_1" placeholder="0000 0000" value="">
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="col-3">
                                    <label for="t1">Phone 2</label>
                                    <input type="text" class="form-control form-control-sm" name="i-phone_2" placeholder="0000 0000" value="">
                                </div>
                                <div class="col-3">
                                    <label for="t1">Fax 2</label>
                                    <input type="text" class="form-control form-control-sm" name="i-fax_2" placeholder="0000 0000" value="">
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="col-6">
                                    <label for="t1">Statement Remark</label>
                                    <textarea class="form-control form-control-sm" placeholder="Type Something" name="i-statement_remark" rows="2"></textarea>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="col-6">
                                    <label for="t1">Remark</label>
                                    <textarea class="form-control form-control-sm" placeholder="Type Something" name="i-remark" rows="2"></textarea>
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="col-12">
                                    <label for="t1">Payment Method</label>
                                    <div class="form-inline">
                                        <div class="input-group my-1 mr-sm-2">             
                                            <select class="custom-select custom-select-sm" name="i-pm_code" id="i-pm_code">
                                                <option value="-1">Choose...</option>
                                                <?php 
                                                    foreach($payment_method as $k => $v):
                                                ?>
                                                        <option value="<?=$v['pm_code']?>"><?=$v['payment_method']?></option>
                                                <?php
                                                    endforeach;
                                                ?>
                                            </select>
                                            <a class='btn btn-outline-primary btn-sm' href='<?=$new_pm_url?>' type='button'>New</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="form-row">
                                <div class="col-12">
                                    <label for="t1">Payment Term</label>
                                    <div class="form-inline">
                                        <div class="input-group my-1 mr-sm-2">
                                            <select class="custom-select custom-select-sm" name="i-pt_code" id="i-pt_code">
                                                <option value="-1">Choose...</option>
                                                <?php 
                                                    foreach($payment_term as $k => $v):
                                                ?>
                                                        <option value="<?=$v["pt_code"]?>"><?=$v['terms']?></option>
                                                <?php
                                                    endforeach;
                                                ?>
                                            </select>
                                            <a class='btn btn-outline-primary btn-sm' href='<?=$new_pt_url?>' type='button'>New</a>
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
        $("#gen-id").on("click", function(){
            var r = Math.floor(Math.random() * Date.now())
            var p = r.toString().substr(1,6)
            $("#i-cust_code").val("C"+p)
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
                "i-cust_code": {
                    required: true
                },
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
