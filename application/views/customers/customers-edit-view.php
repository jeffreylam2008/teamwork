<?php
    // echo "<pre>";
    // var_dump($data);
    // echo "</pre>";
    extract($data);
?>

<form id="form1" name="form1" method="POST" action="<?=$save_url?>">
    <div class="card">
        <div class="card-header">
            <h2> Customer: <u><?=$cust_code?></u></h2>
        </div>
        <div class="card-body">

            <div class="form-row">
                <div class="col-6">
                    <label for="t1">Customer Shop *</label>
                    <input type="text" class="form-control form-control-sm" name="i-name" id="i-name" placeholder="Name" value="<?=$name?>">
                </div>
            </div>

            <div class="form-row">
                <div class="col-2">
                    <label for="t1">Primary Attn *</label>
                    <input type="text" class="form-control form-control-sm" name="i-attn_1" id="i-attn_1" placeholder="Primary Attn" value="<?=$attn_1?>">
                </div>
                <div class="col-2">
                    <label for="t1">Secondary Attn </label>
                    <input type="text" class="form-control form-control-sm" name="i-attn_2" placeholder="Secondary Attn" value="<?=$attn_2?>">
                </div>
            </div>

            <div class="form-row">
                <div class="col-4">
                    <label for="">Mail Address *</label>
                    <input type="text" class="form-control form-control-sm" name="i-mail_addr" id="i-mail_addr" placeholder="Type Something" value="<?=$mail_addr?>">
                </div>
                <div class="col-4">
                    <label for="">Shop Address</label>
                    <input type="text" class="form-control form-control-sm" name="i-shop_addr" placeholder="Type Something" value="<?=$shop_addr?>">
                </div>
                <div class="col-4">
                    <label for="">Delivery Address</label>
                    <input type="text" class="form-control form-control-sm" name="i-delivery_addr" placeholder="Type Something" value="<?=$delivery_addr?>">
                </div>
            </div>

            <div class="form-row">
                <div class="col-3">
                    <label for="t1">Primary Email</label>
                    <input type="text" class="form-control form-control-sm" name="i-email_1" placeholder="Primary Email" value="<?=$email_2?>">
                </div>
                <div class="col-3">
                    <label for="t1">Secondary Email</label>
                    <input type="text" class="form-control form-control-sm" name="i-email_2" placeholder="Secondary Email" value="<?=$email_2?>">
                </div>
            </div>
            <div class="form-row">
                <div class="col-3">
                    <label for="t1">Phone 1 *</label>
                    <input type="text" class="form-control form-control-sm" name="i-phone_1" id="i-phone_1" placeholder="0000 0000" value="<?=$phone_1?>">
                </div>
                <div class="col-3">
                    <label for="t1">Fax 1</label>
                    <input type="text" class="form-control form-control-sm" name="i-fax_1" placeholder="0000 0000" value="<?=$fax_1?>">
                </div>
            </div>
            <div class="form-row">
                <div class="col-3">
                    <label for="t1">Phone 2</label>
                    <input type="text" class="form-control form-control-sm" name="i-phone_2" placeholder="0000 0000" value="<?=$phone_2?>">
                </div>
                <div class="col-3">
                    <label for="t1">Fax 2</label>
                    <input type="text" class="form-control form-control-sm" name="i-fax_2" placeholder="0000 0000" value="<?=$fax_2?>">
                </div>
            </div>

            <div class="form-row">
                <div class="col-6">
                    <label for="t1">Remark</label>
                    <textarea class="form-control form-control-sm" placeholder="Type Something" name="i-statement_remark" rows="2"><?=$statement_remark?></textarea>
                </div>
            </div>

            <div class="form-row">
                <div class="col-3">
                    <label for="t1">Payment Method</label>
                    <select class="custom-select custom-select-sm" id="i-paymentmethod" >
                        <?php 
                            if(!empty($pm_code)):
                        ?>
                            <option value="<?=$pm_code?>"><?=$payment_method[$pm_code]["payment_method"]?></option>
                        <?php
                            else:
                        ?>
                            <option value="-1">Choose...</option>
                        <?php
                            endif;
                        ?>
                        
                        <?php 
                            foreach($payment_method as $k => $v):
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
                    <select class="custom-select custom-select-sm" id="i-paymentterms" >
                        <?php 
                            if(!empty($pt_code)):
                        ?>
                            <option value="<?=$pt_code?>"><?=$payment_term[$pt_code]["terms"]?></option>
                        <?php
                            else:
                        ?>
                            <option value="-1">Choose...</option>
                        <?php
                            endif;
                        ?>
                        
                        <?php 
                            foreach($payment_term as $k => $v):
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
            "i-name": {
                required: true
            },
            "i-attn_1": {
                required: true
            },
            "i-mail_addr" : {
                required: true
            },
            "i-phone_1" : {
                required: true,
                minlength: 8
            }
        }
    });
    if(isvalid){
        $("#form1").submit();
    }
    console.log("clicked")
});

</script>