
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
                            <div class="row">
                                <div class="col">
                                    <ul class="list-group">
                                        <li class="list-group-item">
                                            <span class="badge badge-pill badge-secondary">General</span> 
                                            <div class="form-row">
                                                <div class="col-3">
                                                    <label for="t1">Status</label>
                                                    <select class="custom-select custom-select-sm" id="i-status" name="i-status">
                                                        <option value="Active">Active</option>
                                                        <option value="Closed">Closed</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-row">
                                                <div class="col-8">
                                                    <label for="t1">Customer Shop</label>
                                                    <input type="text" class="form-control form-control-sm" name="i-name" id="i-name" placeholder="Name" value="" >
                                                </div>
                                            </div>
                                            <div class="form-row">
                                                <div class="col-4">
                                                    <label for="t1">Primary Attn *</label>
                                                    <input type="text" class="form-control form-control-sm" name="i-attn_1" id="i-attn_1" placeholder="Primary Attn" value="" >
                                                </div>
                                                <div class="col-4">
                                                    <label for="t1">Secondary Attn </label>
                                                    <input type="text" class="form-control form-control-sm" name="i-attn_2" placeholder="Secondary Attn" value="" >
                                                </div>
                                            </div>

                                            <div class="form-row">
                                                <div class="col-12">
                                                    <label for="">Mail Address</label>
                                                    <input type="text" class="form-control form-control-sm" name="i-mail_addr" id="i-mail_addr" placeholder="Type Something" value="" >
                                                </div>
                                                <div class="col-12">
                                                    <label for="">Shop Address</label>
                                                    <input type="text" class="form-control form-control-sm" name="i-shop_addr" placeholder="Type Something" value="" >
                                                </div>
                                                
                                            </div>

                                            <div class="form-row">
                                                <div class="col-6">
                                                    <label for="t1">Primary Email</label>
                                                    <input type="text" class="form-control form-control-sm" name="i-email_1" placeholder="Primary Email" value="" >
                                                </div>
                                                <div class="col-6">
                                                    <label for="t1">Secondary Email</label>
                                                    <input type="text" class="form-control form-control-sm" name="i-email_2" placeholder="Secondary Email" value="" >
                                                </div>
                                            </div>
                                            <div class="form-row">
                                                <div class="col-4">
                                                    <label for="t1">Phone 1</label>
                                                    <input type="text" class="form-control form-control-sm" name="i-phone_1" id="i-phone_1" placeholder="00000000" value="" >
                                                </div>
                                                <div class="col-4">
                                                    <label for="t1">Fax 1</label>
                                                    <input type="text" class="form-control form-control-sm" name="i-fax_1" placeholder="00000000" value="" >
                                                </div>
                                            </div>
                                        

                                            <div class="form-row">
                                                <div class="col-12">
                                                    <label for="t1">Statement Remark</label>
                                                    <textarea class="form-control form-control-sm" placeholder="Type Something" name="i-statement_remark" rows="2" ></textarea>
                                                </div>
                                            </div>
                                            <div class="form-row">
                                                <div class="col-12">
                                                    <label for="t1">Remark</label>
                                                    <textarea class="form-control form-control-sm" placeholder="Type Something" name="i-remark" rows="2" ></textarea>
                                                </div>
                                            </div>

                                            <div class="form-row">
                                                <div class="col-4">
                                                    <label for="t1">Payment Method</label>
                                                    <select class="custom-select custom-select-sm" id="i-paymentmethod" name="i-pm_code" >
                                                        <option value="-1">Select...</option>
                                                        <?php
                                                            foreach($data_payment_method as $k => $v):
                                                        ?>
                                                            <option value="<?=$k?>"><?=$v['payment_method']?></option>
                                                        <?php
                                                            endforeach;
                                                        ?>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-row">
                                                <div class="col-4">
                                                    <label for="t1">Payment Terms</label>
                                                    <select class="custom-select custom-select-sm" id="i-paymentterms" name="i-pt_code" >
                                                        <option value="-1">Select...</option>
                                                        <?php
                                                            foreach($data_payment_term as $k => $v):
                                                        ?>
                                                            <option value="<?=$k?>"><?=$v['terms']?></option>
                                                        <?php
                                                            endforeach;
                                                        ?>
                                                    </select>
                                                </div>
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                                <div class="col">
                                    <ul class="list-group">
                                        <li class="list-group-item">
                                            <span class="badge badge-pill badge-secondary">Delivery</span>
                                            <div class="form-row">
                                                <div class="col-3">
                                                    <label for="t1">District</label> 
                                                    <select class="custom-select custom-select-sm" id="i-district" name="i-district" >
                                                        <option value="-1">Select...</option>
                                                        <?php
                                                            foreach($data_district as $k => $v):
                                                        ?>
                                                            <option value="<?=$k?>"><?=$v['district_chi']?></option>
                                                        <?php
                                                            endforeach;
                                                        ?>
                                                    </select>
                                                </div>
                                                
                                            </div>
                                            <div class="form-row">
                                                <div class="col-12">
                                                    <label for="">Delivery Address</label>
                                                    <input type="text" class="form-control form-control-sm" name="i-delivery_addr" placeholder="Type Something" value="" >
                                                </div>
                                            </div>
                                            <div class="form-row">
                                                <div class="col-3">
                                                    <label for="t1">From</label>
                                                    <input type="text" class="form-control form-control-sm" name="i-from_time" placeholder="00:00:00" value="" >
                                                </div>
                                                <div class="col-3">
                                                    <label for="t1">To</label>
                                                    <input type="text" class="form-control form-control-sm" name="i-to_time" placeholder="00:00:00" value="" >
                                                </div>
                                            </div>
                                            <div class="form-row">
                                                <div class="col-4">
                                                    <label for="t1">Phone</label>
                                                    <input type="text" class="form-control form-control-sm" name="i-delivery_phone" placeholder="00000000" value="" >
                                                </div>
                                                <div class="col-4">
                                                    <label for="t1">Fax</label>
                                                    <input type="text" class="form-control form-control-sm" name="i-delivery_fax" placeholder="00000000" value="" >
                                                </div>
                                            </div>
                                            <div class="form-row">
                                                <div class="col-12">
                                                    <label for="t1">Delivery Remark</label>
                                                    <textarea class="form-control form-control-sm" placeholder="Type Something" name="i-delivery_remark" rows="3" ></textarea>
                                                </div>
                                            </div>
                                        </li>
                                        <li class="list-group-item">
                                            <span class="badge badge-pill badge-secondary">Account</span>    
                                            <div class="form-row">
                                                <div class="col-8">
                                                    <label for="t1">Company BR Number</label>
                                                    <input type="text" class="form-control form-control-sm" name="i-acc_company_br" id="i-br" placeholder="BR Number" value="" >
                                                </div>
                                            </div>
                                            <div class="form-row">
                                                <div class="col-8">
                                                    <label for="t1">Company Sign</label>
                                                    <input type="text" class="form-control form-control-sm" name="i-acc_company_sign" placeholder="Company Sign" value="">
                                                </div>
                                            </div>
                                            <div class="form-row">
                                                <div class="col-8">
                                                    <label for="t1">Group Name</label>
                                                    <input type="text" class="form-control form-control-sm" name="i-acc_group_name" placeholder="Group" value="" >
                                                </div>
                                            </div>
                                            <div class="form-row">
                                                <div class="col-4">
                                                    <label for="t1">Accountant</label>
                                                    <input type="text" class="form-control form-control-sm" name="i-acc_attn" id="i-acc_attn" placeholder="Account Attn" value="" >
                                                </div>
                                            </div>
                                            <div class="form-row">
                                                <div class="col-4">
                                                    <label for="t1">Phone</label>
                                                    <input type="text" class="form-control form-control-sm" name="i-acc_phone" placeholder="00000000" value="" >
                                                </div>
                                                <div class="col-4">
                                                    <label for="t1">Fax</label>
                                                    <input type="text" class="form-control form-control-sm" name="i-acc_fax" placeholder="00000000" value="" >
                                                </div>
                                            </div>
                                            <div class="form-row">
                                                <div class="col-6">
                                                    <label for="t1">Email</label>
                                                    <input type="text" class="form-control form-control-sm" name="i-acc_email" placeholder="Email" value="" >
                                                </div>
                                            </div>
                                        </li>
                                    </ul>
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
