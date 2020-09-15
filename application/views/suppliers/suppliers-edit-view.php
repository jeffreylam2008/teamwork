<?php
extract($data);
?>
<form id="form1" name="form1" method="POST" action="<?=$save_url?>">
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col">
                    <div class="form-row">
                        <div class="col-2">
                            <label for="t1">Status</label>
                            <select class="custom-select custom-select-sm" id="i-status" name="i-status">
                                <option value="<?=$status?>"><?=$status?></option>
                                <option value="Active">Active</option>
                                <option value="Closed">Closed</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="col-6">
                            <label for="t1">Supplier Name</label>
                            <input type="text" class="form-control form-control-sm" name="i-name" id="i-name" placeholder="Name" value="<?=$name?>" >
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="col-2">
                            <label for="t1">Attn *</label>
                            <input type="text" class="form-control form-control-sm" name="i-attn_1" id="i-attn_1" placeholder="Primary Attn" value="<?=$attn_1?>" >
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="col-6">
                            <label for="">Mail Address</label>
                            <input type="text" class="form-control form-control-sm" name="i-mail_addr" id="i-mail_addr" placeholder="Type Something" value="<?=$mail_addr?>" >
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="col-3">
                            <label for="t1">Email</label>
                            <input type="text" class="form-control form-control-sm" name="i-email_1" placeholder="Primary Email" value="<?=$email_1?>" >
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="col-3">
                            <label for="t1">Phone *</label>
                            <input type="text" class="form-control form-control-sm" name="i-phone_1" id="i-phone_1" placeholder="00000000" value="<?=$phone_1?>" >
                        </div>
                        <div class="col-3">
                            <label for="t1">Fax</label>
                            <input type="text" class="form-control form-control-sm" name="i-fax_1" placeholder="00000000" value="<?=$fax_1?>" >
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="col-6">
                            <label for="t1">Remark</label>
                            <textarea class="form-control form-control-sm" placeholder="Type Something" name="i-remark" rows="2" ><<?=$remark?>/textarea>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="col-3">
                            <label for="t1">Payment Method</label>
                            <a class='btn btn-outline-primary btn-sm' href='<?=$new_pm_url?>' type='button'>New</a>
                            <select class="custom-select custom-select-sm" id="i-paymentmethod" name="i-pm_code" >
                                <?php 
                                    if(!empty($pm_code) && $pm_code != "-1"):
                                        $key = array_search($pm_code, array_column($data_payment_method,"pm_code"));
                                ?>
                                    <option value="<?=$pm_code?>"><?=$data_payment_method[$key]["payment_method"]?></option>
                                <?php
                                    else:
                                ?>
                                    <option value="-1">Select...</option>
                                <?php
                                    endif;
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
                        <div class="col-3">
                            <label for="t1">Payment Terms</label>
                            <a class='btn btn-outline-primary btn-sm' href='<?=$new_pt_url?>' type='button'>New</a>
                            <select class="custom-select custom-select-sm" id="i-paymentterms" name="i-pt_code" >
                                <?php 
                                    if(!empty($pt_code) && $pt_code != "-1"):
                                        $key = array_search($pt_code, array_column($data_payment_term,"pt_code"));
                                ?>
                                    <option value="<?=$pt_code?>"><?=$data_payment_term[$key]["terms"]?></option>
                                <?php
                                    else:
                                ?>
                                    <option value="-1">Select...</option>
                                <?php
                                    endif
                                    ;
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
<script>

$(function() {
    $("#reset").click(function(){
        $("#form1").trigger("reset");
    });
});
// form validation
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
    console.log("clicked")
});

</script>