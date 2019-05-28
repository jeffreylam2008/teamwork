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
			
			<div class="row">
				<div class="col">
					<ul class="list-group">
						<li class="list-group-item">
							<span class="badge badge-pill badge-secondary">General</span> 
							<div class="form-row">
								<div class="col-8">
									<label for="t1">Customer Shop</label>
									<input type="text" class="form-control form-control-sm" name="i-name" id="i-name" placeholder="Name" value="<?=$name?>" disabled>
								</div>
							</div>
							<div class="form-row">
								<div class="col-4">
									<label for="t1">Primary Attn</label>
									<input type="text" class="form-control form-control-sm" name="i-attn_1" id="i-attn_1" placeholder="Primary Attn" value="<?=$attn_1?>" disabled>
								</div>
								<div class="col-4">
									<label for="t1">Secondary Attn </label>
									<input type="text" class="form-control form-control-sm" name="i-attn_2" placeholder="Secondary Attn" value="<?=$attn_2?>" disabled>
								</div>
							</div>

							<div class="form-row">
								<div class="col-12">
									<label for="">Mail Address</label>
									<input type="text" class="form-control form-control-sm" name="i-mail_addr" id="i-mail_addr" placeholder="Type Something" value="<?=$mail_addr?>" disabled>
								</div>
								<div class="col-12">
									<label for="">Shop Address</label>
									<input type="text" class="form-control form-control-sm" name="i-shop_addr" placeholder="Type Something" value="<?=$shop_addr?>" disabled>
								</div>
								
							</div>

							<div class="form-row">
								<div class="col-6">
									<label for="t1">Primary Email</label>
									<input type="text" class="form-control form-control-sm" name="i-email_1" placeholder="Primary Email" value="<?=$email_2?>" disabled>
								</div>
								<div class="col-6">
									<label for="t1">Secondary Email</label>
									<input type="text" class="form-control form-control-sm" name="i-email_2" placeholder="Secondary Email" value="<?=$email_2?>" disabled>
								</div>
							</div>
							<div class="form-row">
								<div class="col-4">
									<label for="t1">Phone 1</label>
									<input type="text" class="form-control form-control-sm" name="i-phone_1" id="i-phone_1" placeholder="0000 0000" value="<?=$phone_1?>" disabled>
								</div>
								<div class="col-4">
									<label for="t1">Fax 1</label>
									<input type="text" class="form-control form-control-sm" name="i-fax_1" placeholder="0000 0000" value="<?=$fax_1?>" disabled>
								</div>
							</div>
						   

							<div class="form-row">
								<div class="col-12">
									<label for="t1">Statement Remark</label>
									<textarea class="form-control form-control-sm" placeholder="Type Something" name="i-statement_remark" rows="2" disabled><?=$statement_remark?></textarea>
								</div>
							</div>
							<div class="form-row">
								<div class="col-12">
									<label for="t1">Remark</label>
									<textarea class="form-control form-control-sm" placeholder="Type Something" name="i-remark" rows="2" disabled><?=$remark?></textarea>
								</div>
							</div>

							<div class="form-row">
								<div class="col-4">
									<label for="t1">Payment Method</label>
									<select class="custom-select custom-select-sm" id="i-paymentmethod" name="i-pm_code" disabled>
										<?php 
											if(!empty($pm_code) && $pm_code != "-1"):
										?>
											<option value="<?=$pm_code?>"><?=$data_payment_method[$pm_code]["payment_method"]?></option>
										<?php
											endif;
										?>
									</select>
								</div>
							</div>
							<div class="form-row">
								<div class="col-4">
									<label for="t1">Payment Terms</label>
									<select class="custom-select custom-select-sm" id="i-paymentterms" name="i-pt_code" disabled>
										<?php 
											if(!empty($pt_code) && $pt_code != "-1"):
										?>
											<option value="<?=$pt_code?>"><?=$data_payment_term[$pt_code]["terms"]?></option>
										<?php
											endif;
										?>    
									</select>
								</div>
							</div>
							<input type="hidden" name="i-group_name" value="" />
						</li>
					</ul>
				</div>
				<div class="col">
					<ul class="list-group">
						<li class="list-group-item">
							<span class="badge badge-pill badge-secondary">Delivery</span>
							<div class="form-row">
								<div class="col-3">
									<label for="t1">District Code</label> 
									<input type="text" class="form-control form-control-sm" name="i-district_code" placeholder="District" value="<?=$district_code?>" disabled>
								</div>
								<div class="col-3">
									<label for="t1">District ZH</label>
									<input type="text" class="form-control form-control-sm" name="i-district_zh" placeholder="District ZH" value="<?=$district_chi?>" disabled>
								</div>
								<div class="col-6">
									<label for="t1">District EN</label>
									<input type="text" class="form-control form-control-sm" name="i-district_en" placeholder="District EN" value="<?=$district_eng?>" disabled>
								</div>
							</div>
							<div class="form-row">
								<div class="col-12">
									<label for="">Delivery Address</label>
									<input type="text" class="form-control form-control-sm" name="i-delivery_addr" placeholder="Type Something" value="<?=$delivery_addr?>" disabled>
								</div>
							</div>
							<div class="form-row">
								<div class="col-3">
									<label for="t1">From</label>
									<input type="text" class="form-control form-control-sm" name="i-from_time" placeholder="From" value="<?=$from_time?>" disabled>
								</div>
								<div class="col-3">
									<label for="t1">To</label>
									<input type="text" class="form-control form-control-sm" name="i-to_time" placeholder="To" value="<?=$to_time?>" disabled>
								</div>
							</div>
							<div class="form-row">
								<div class="col-4">
									<label for="t1">Phone</label>
									<input type="text" class="form-control form-control-sm" name="i-delivery_phonn" placeholder="0000 0000" value="<?=$phone_2?>" disabled>
								</div>
								<div class="col-4">
									<label for="t1">Fax</label>
									<input type="text" class="form-control form-control-sm" name="i-delivery_fax" placeholder="0000 0000" value="<?=$fax_2?>" disabled>
								</div>
							</div>
							<div class="form-row">
								<div class="col-12">
									<label for="t1">Delivery Remark</label>
									<textarea class="form-control form-control-sm" placeholder="Type Something" name="i-delivery_remark" rows="3" disabled></textarea>
								</div>
							</div>
						   

						</li>
						<li class="list-group-item">
							<span class="badge badge-pill badge-secondary">Account</span>    
							<div class="form-row">
								<div class="col-8">
									<label for="t1">Company BR Number</label>
									<input type="text" class="form-control form-control-sm" name="i-company_br" id="i-br" placeholder="BR Number" value="" disabled>
								</div>
							</div>
							<div class="form-row">
								<div class="col-8">
									<label for="t1">Group Name</label>
									<input type="text" class="form-control form-control-sm" name="i-group_name" placeholder="Group" value="" disabled>
								</div>
							</div>
							<div class="form-row">
								<div class="col-4">
									<label for="t1">Accountant</label>
									<input type="text" class="form-control form-control-sm" name="i-acc_attn" id="i-acc_attn" placeholder="Account Attn" value="" disabled>
								</div>
							</div>
							<div class="form-row">
								<div class="col-4">
									<label for="t1">Phone</label>
									<input type="text" class="form-control form-control-sm" name="i-acc_phone" placeholder="0000 0000" value="" disabled>
								</div>
								<div class="col-4">
									<label for="t1">Fax</label>
									<input type="text" class="form-control form-control-sm" name="i-acc_fax" placeholder="0000 0000" value="" disabled>
								</div>
							</div>
						</li>
					</ul>
				</div>
			</div>
			
			
			
			
			
			
			
			
			
			
			
			<!--
			
            <div class="form-row">
                <div class="col-6">
                    <label for="t1">Customer Shop *</label>
                    <input type="text" class="form-control form-control-sm" name="i-name" id="i-name" placeholder="Name" value="<?=$name?>">
                </div>
            </div>
            <div class="form-row">
                <div class="col-6">
                    <label for="t1">Group Name</label>
                    <input type="text" class="form-control form-control-sm" name="i-group_name" placeholder="Group" value="<?=$group_name?>">
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
                    <label for="t1">Statement Remark</label>
                    <textarea class="form-control form-control-sm" placeholder="Type Something" name="i-statement_remark" rows="2"><?=$statement_remark?></textarea>
                </div>
            </div>
            <div class="form-row">
                <div class="col-6">
                    <label for="t1">Remark</label>
                    <textarea class="form-control form-control-sm" placeholder="Type Something" name="i-remark" rows="2"><?=$remark?></textarea>
                </div>
            </div>

            <div class="form-row">
                <div class="col-3">
                    <label for="t1">Payment Method</label>
                    <select class="custom-select custom-select-sm" id="i-paymentmethod" name="i-pm_code">
                        <?php 
                            if(!empty($pm_code) && $pm_code != "-1"):
                        ?>
                            <option value="<?=$pm_code?>"><?=$payment_method[$pm_code]["payment_method"]?></option>
                        <?php
                            endif;
                        ?>
                    </select>
                </div>
            </div>
            <div class="form-row">
                <div class="col-3">
                    <label for="t1">Payment Terms</label>
                    <select class="custom-select custom-select-sm" id="i-paymentterms" name="i-pt_code">
                        <?php 
                            if(!empty($pt_code) && $pt_code != "-1"):
                        ?>
                            <option value="<?=$pt_code?>"><?=$payment_term[$pt_code]["terms"]?></option>
                        <?php
                            endif;
                        ?>    
                    </select>
                </div>
            </div>
            <input type="hidden" name="i-group_name" value="" />
        </div>
		-->
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