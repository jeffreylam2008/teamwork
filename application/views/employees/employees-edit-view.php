<?php
    // echo "<pre>";
    // var_dump($employees);
    // echo "</pre>";
  
?>

<form id="form1" name="form1" method="POST" action="<?=$save_url?>">
    <div class="card">
        <div class="card-header">
            <h2> <?=$this->lang->line('employee_id')?>: <u><?=$employees['employee_code']?></u></h2>
        </div>
        <div class="card-body">
            <div class="form-row">
                <div class="col-3">
                    <label for="">* <?=$this->lang->line('employee_id')?></label>
                    <input type="text" class="form-control form-control-sm" name="i-emp-code" placeholder="<?=$this->lang->line('employee_id')?>" value="<?=$employees['employee_code']?>" >
                </div>
            </div>
            <div class="form-row">
                <div class="col-3">
                    <label for="">* <?=$this->lang->line('employee_username')?></label>
                    <input type="text" class="form-control form-control-sm" name="i-username" placeholder="<?=$this->lang->line('employee_username')?>" value="<?=$employees['username']?>" >
                    
                </div>
            </div>
            <div class="form-row">
                <div class="col-12">
                    <input type="button" href="#pwd" class="btn-primary btn-sm" data-toggle='collapse' aria-expanded='true' value="<?=$this->lang->line('function_change_password')?>" />
                    <div class="collapse" id="pwd">
                        <div class="form-row">
                            <div class="col-3">
                                <label for="">* <?=$this->lang->line('employee_password')?></label>
                                <input type="password" class="form-control form-control-sm" name="i-pwd" id="i-pwd" placeholder="<?=$this->lang->line('employee_password')?>" value="" >
                            </div>
                            <div class="col-3">
                                <label for="">* <?=$this->lang->line('employee_comfirm_password')?></label>
                                <input type="password" class="form-control form-control-sm" name="i-confirm-pwd" placeholder="<?=$this->lang->line('employee_comfirm_password')?>" value="" >
                            </div> 
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="form-row">
                <div class="col-3">
                    <label for=""><?=$this->lang->line('employee_default_shop')?></label>
                    <select class="custom-select custom-select-sm" id="i-shops" name="i-shops" >
                        <option value="<?=$employees['default_shopcode']?>"><?=$employees['shop_name']?></option>
                        <?php 
                        foreach($shops as $key => $val):
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
                    <label for=""><?=$this->lang->line('employee_status')?></label>
                    <select class="custom-select custom-select-sm" id="i-status" name="i-status" >
                        <option value="<?=$employees['status']?>"><?=$employees['status'] ? "Active" : "Disable"?></option>
                        <option value="1">Active</option>
                        <option value="0">Disable</option>
                    </select>
                </div>
            </div>
        </div>
    </div>
</form>
<script>

$(function() {
    $("#reset").click(function(){
        $("#form1").trigger("reset");
    });
});
// form validation
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
                cRequired: true
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
    console.log("clicked")
});

</script>