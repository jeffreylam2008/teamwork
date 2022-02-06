
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
                                    <ul class="list-group">
                                        <li class="list-group-item">
                                            <span class="badge badge-pill badge-secondary"><?=$this->lang->line("function_general")?></span> 
                                            <div class="form-row">
                                                <div class="col-4">
                                                    <label for="t1"><?=$this->lang->line("customer_status")?></label>
                                                    <select class="custom-select custom-select-sm" id="i-status" name="i-status">
                                                        <option value="Active">Active</option>
                                                        <option value="Closed">Closed</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-row">
                                                <div class="col-8">
                                                    <label for="t1"><?=$this->lang->line("customer_name")?></label>
                                                    <input type="text" class="form-control form-control-sm" name="i-name" id="i-name" placeholder="<?=$this->lang->line("customer_name")?>" value="" >
                                                </div>
                                            </div>
                                            <div class="form-row">
                                                <div class="col-4">
                                                    <label for="t1"><?=$this->lang->line("customer_attn_1")?> *</label>
                                                    <input type="text" class="form-control form-control-sm" name="i-attn_1" id="i-attn_1" placeholder="<?=$this->lang->line("customer_attn_1")?>" value="" >
                                                </div>
                                                <div class="col-4">
                                                    <label for="t1"><?=$this->lang->line("customer_attn_2")?> </label>
                                                    <input type="text" class="form-control form-control-sm" name="i-attn_2" placeholder="<?=$this->lang->line("customer_attn_2")?>" value="" >
                                                </div>
                                            </div>

                                            <div class="form-row">
                                                <div class="col-12">
                                                    <label for=""><?=$this->lang->line("customer_mail_addr")?></label>
                                                    <input type="text" class="form-control form-control-sm" name="i-mail_addr" id="i-mail_addr" placeholder="<?=$this->lang->line("customer_mail_addr")?>" value="" >
                                                </div>
                                                <div class="col-12">
                                                    <label for=""><?=$this->lang->line("customer_shop_addr")?></label>
                                                    <input type="text" class="form-control form-control-sm" name="i-shop_addr" placeholder="<?=$this->lang->line("customer_shop_addr")?>" value="" >
                                                </div>
                                                
                                            </div>

                                            <div class="form-row">
                                                <div class="col-6">
                                                    <label for="t1"><?=$this->lang->line("customer_email_1")?></label>
                                                    <input type="text" class="form-control form-control-sm" name="i-email_1" placeholder="<?=$this->lang->line("customer_email_1")?>" value="" >
                                                </div>
                                                <div class="col-6">
                                                    <label for="t1"><?=$this->lang->line("customer_email_2")?></label>
                                                    <input type="text" class="form-control form-control-sm" name="i-email_2" placeholder="<?=$this->lang->line("customer_email_2")?>" value="" >
                                                </div>
                                            </div>
                                            <div class="form-row">
                                                <div class="col-4">
                                                    <label for="t1"><?=$this->lang->line("customer_phone")?> *</label>
                                                    <input type="text" class="form-control form-control-sm" name="i-phone_1" id="i-phone_1" placeholder="00000000" value="" >
                                                </div>
                                                <div class="col-4">
                                                    <label for="t1"><?=$this->lang->line("customer_fax")?></label>
                                                    <input type="text" class="form-control form-control-sm" name="i-fax_1" placeholder="00000000" value="" >
                                                </div>
                                            </div>
                                        

                                            <div class="form-row">
                                                <div class="col-12">
                                                    <label for="t1"><?=$this->lang->line("customer_statement_remark")?></label>
                                                    <textarea class="form-control form-control-sm" placeholder="<?=$this->lang->line("customer_statment_remark")?>" name="i-statement_remark" rows="2" ></textarea>
                                                </div>
                                            </div>
                                            <div class="form-row">
                                                <div class="col-12">
                                                    <label for="t1"><?=$this->lang->line("customer_remark")?></label>
                                                    <textarea class="form-control form-control-sm" placeholder="<?=$this->lang->line("customer_remark")?>" name="i-remark" rows="2" ></textarea>
                                                </div>
                                            </div>

                                            <div class="form-row">
                                                <div class="col-4">
                                                    <label for="t1"><?=$this->lang->line("payment_method")?></label>
                                                    <select class="custom-select custom-select-sm" id="i-paymentmethod" name="i-pm_code" >
                                                        <option value="-1"><?=$this->lang->line("function_select")?></option>
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
                                                <div class="col-4">
                                                    <label for="t1"><?=$this->lang->line("customer_payment_term")?></label>
                                                    <select class="custom-select custom-select-sm" id="i-paymentterms" name="i-pt_code" >
                                                        <option value="-1"><?=$this->lang->line("function_select")?></option>
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
                                        </li>
                                    </ul>
                                </div>
                                <div class="col">
                                    <ul class="list-group">
                                        <li class="list-group-item">
                                            <span class="badge badge-pill badge-secondary"><?=$this->lang->line("function_delivery")?></span>
                                            <div class="form-row">
                                                <div class="col-4">
                                                    <label for="t1"><?=$this->lang->line("customer_district")?></label> 
                                                    <select class="custom-select custom-select-sm" id="i-district" name="i-district" >
                                                        <option value="-1"><?=$this->lang->line("function_select")?></option>
                                                        <?php
                                                            foreach($data_district as $k => $v):
                                                        ?>
                                                            <option value="<?=$v['district_code']?>"><?=$v['district_chi']?></option>
                                                        <?php
                                                            endforeach;
                                                        ?>
                                                    </select>
                                                </div>
                                                
                                            </div>
                                            <div class="form-row">
                                                <div class="col-12">
                                                    <label for=""><?=$this->lang->line("customer_delivery_addr")?></label>
                                                    <input type="text" class="form-control form-control-sm" name="i-delivery_addr" placeholder="<?=$this->lang->line("customer_delivery_addr")?>" value="" >
                                                </div>
                                            </div>
                                            <div class="form-row">
                                                <div class="col-4">
                                                    <label for="t1"><?=$this->lang->line("customer_delivery_from")?></label>
                                                    <input type="text" class="form-control form-control-sm" name="i-from_time" placeholder="00:00:00" value="" >
                                                </div>
                                                <div class="col-4">
                                                    <label for="t1"><?=$this->lang->line("customer_delivery_to")?></label>
                                                    <input type="text" class="form-control form-control-sm" name="i-to_time" placeholder="00:00:00" value="" >
                                                </div>
                                            </div>
                                            <div class="form-row">
                                                <div class="col-4">
                                                    <label for="t1"><?=$this->lang->line("customer_phone")?></label>
                                                    <input type="text" class="form-control form-control-sm" name="i-delivery_phone" placeholder="00000000" value="" >
                                                </div>
                                                <div class="col-4">
                                                    <label for="t1"><?=$this->lang->line("customer_fax")?></label>
                                                    <input type="text" class="form-control form-control-sm" name="i-delivery_fax" placeholder="00000000" value="" >
                                                </div>
                                            </div>
                                            <div class="form-row">
                                                <div class="col-12">
                                                    <label for="t1"><?=$this->lang->line("customer_delivery_remark")?></label>
                                                    <textarea class="form-control form-control-sm" placeholder="<?=$this->lang->line("Customer_delivery_remark")?>" name="i-delivery_remark" rows="3" ></textarea>
                                                </div>
                                            </div>
                                        </li>
                                        <li class="list-group-item">
                                            <span class="badge badge-pill badge-secondary"><?=$this->lang->line("function_accounting")?></span>    
                                            <div class="form-row">
                                                <div class="col-8">
                                                    <label for="t1"><?=$this->lang->line("customer_br_number")?></label>
                                                    <input type="text" class="form-control form-control-sm" name="i-acc_company_br" id="i-br" placeholder="<?=$this->lang->line("customer_br_number")?>" value="" >
                                                </div>
                                            </div>
                                            <div class="form-row">
                                                <div class="col-8">
                                                    <label for="t1"><?=$this->lang->line("customer_sign_company")?></label>
                                                    <input type="text" class="form-control form-control-sm" name="i-acc_company_sign" placeholder="<?=$this->lang->line("customer_sign_company")?>" value="">
                                                </div>
                                            </div>
                                            <div class="form-row">
                                                <div class="col-8">
                                                    <label for="t1"><?=$this->lang->line("customer_group")?></label>
                                                    <input type="text" class="form-control form-control-sm" name="i-acc_group_name" placeholder="<?=$this->lang->line("customer_group")?>" value="" >
                                                </div>
                                            </div>
                                            <div class="form-row">
                                                <div class="col-4">
                                                    <label for="t1"><?=$this->lang->line("customer_accountant")?></label>
                                                    <input type="text" class="form-control form-control-sm" name="i-acc_attn" id="i-acc_attn" placeholder="<?=$this->lang->line("customer_accountant")?>" value="" >
                                                </div>
                                            </div>
                                            <div class="form-row">
                                                <div class="col-8">
                                                    <label for="t1"><?=$this->lang->line("customer_acc_email")?></label>
                                                    <input type="text" class="form-control form-control-sm" name="i-acc_email" id="i-acc_email" placeholder="<?=$this->lang->line("customer_acc_email")?>" value="" >
                                                </div>
                                            </div>
                                            <div class="form-row">
                                                <div class="col-4">
                                                    <label for="t1"><?=$this->lang->line("customer_phone")?></label>
                                                    <input type="text" class="form-control form-control-sm" name="i-acc_phone" placeholder="00000000" value="" >
                                                </div>
                                                <div class="col-4">
                                                    <label for="t1"><?=$this->lang->line("customer_fax")?></label>
                                                    <input type="text" class="form-control form-control-sm" name="i-acc_fax" placeholder="00000000" value="" >
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
        $.validator.addMethod("cRequired", $.validator.methods.required, "<?=$this->lang->line("function_valid_field_require")?>");
        $.validator.addMethod("cDigits", $.validator.methods.digits,$.validator.format("<?=$this->lang->line("function_valid_field_digits")?>"));
        $.validator.addMethod("cMinlength", $.validator.methods.minlength,$.validator.format("<?=$this->lang->line("function_valid_field_minlength")?>"));
        $.validator.addMethod("cMaxlength", $.validator.methods.maxlength,$.validator.format("<?=$this->lang->line("function_valid_field_maxlength")?>"));
        var isvalid = $("#form1").validate({
            rules: {
                // simple rule, converted to {required:true}
                "i-attn_1": {
                    cRequired: true
                },
                "i-phone_1": {
                    cRequired: true,
                    cDigits:true,
                    cMinlength: 8,
                    cMaxlength: 9
                }
            }
        });
        if(isvalid){
            $("#form1").submit();
        }
        
    });
</script>
