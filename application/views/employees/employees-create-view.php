<!-- Modal -->
<div class="modal fade" id="modal01" tabindex="-1" role="dialog" aria-labelledby="newitem" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <!-- Modal Head -->
                <h2 class="modal-title" id=""><b><?=$this->lang->line("employee_new_titles")?></b></h2>
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
                                    <label for="">* <?=$this->lang->line("employee_id")?></label>
                                    <input type="text" class="form-control form-control-sm" name="i-emp-code" placeholder="<?=$this->lang->line("employee_id")?>" value="" >
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="col-3">
                                    <label for="">* <?=$this->lang->line("employee_username")?></label>
                                    <input type="text" class="form-control form-control-sm" name="i-username" placeholder="<?=$this->lang->line("employee_username")?>" value="" >
                                </div>
                                <div class="col-3">
                                    <label for="">* <?=$this->lang->line("employee_password")?></label>
                                    <input type="password" class="form-control form-control-sm" name="i-pwd" id="i-pwd" placeholder="<?=$this->lang->line("employee_password")?>" value="" >
                                </div>
                                <div class="col-3">
                                    <label for="">* <?=$this->lang->line("employee_comfirm_password")?></label>
                                    <input type="password" class="form-control form-control-sm" name="i-confirm-pwd" placeholder="<?=$this->lang->line("employee_comfirm_password")?>" value="" >
                                </div>
                            </div>
                        
                            <div class="form-row">
                                <div class="col-3">
                                    <label for=""><?=$this->lang->line("employee_default_shop")?></label>
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
                                    <label for=""><?=$this->lang->line("employee_status")?></label>
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
        $.validator.addMethod("cRequired", $.validator.methods.required, "<?=$this->lang->line("function_valid_field_require")?>");
        $.validator.addMethod("cDigits", $.validator.methods.digits,$.validator.format("<?=$this->lang->line("function_valid_field_digits")?>"));
        $.validator.addMethod("cMinlength", $.validator.methods.minlength,$.validator.format("<?=$this->lang->line("function_valid_field_minlength")?>"));
        $.validator.addMethod("cMaxlength", $.validator.methods.maxlength,$.validator.format("<?=$this->lang->line("function_valid_field_maxlength")?>"));
        $.validator.addMethod("cEqualto", $.validator.methods.equalTo,$.validator.format("<?=$this->lang->line("function_valid_field_equalto")?>"));
        var isvalid = $("#form1").validate({
            rules: {
                // simple rule, converted to {required:true}
                "i-emp-code": {
                    cRequired: true,
                    cDigits:true,
                    cMinlength: 5,
                    cMaxlength: 10
                },
                "i-username": {
                    cRequired: true
                },
                "i-pwd": {
                    cRequired: true
                },
                "i-confirm-pwd": {
                    cRequired: true,
                    cEqualto: "#i-pwd"
                }
            }
        });
        if(isvalid){
            $("#form1").submit();
        }
        
    });
</script>
