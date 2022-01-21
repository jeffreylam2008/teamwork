<?php
    // echo "<pre>";
    // var_dump($data);
    // echo "</pre>";
    extract($data);
?>

<form id="form1" name="form1" method="POST" action="<?=$save_url?>">
    <div class="card">
        <div class="card-header">
            <h2> <?=$this->lang->line("customer_name")?>: <u><?=$cust_code?></u></h2>
        </div>
        <div class="card-body">
			<div class="row">
				<div class="col">
					<ul class="list-group">
						<li class="list-group-item">
							<span class="badge badge-pill badge-secondary"><?=$this->lang->line("function_general")?></span> 
							<div class="form-row">
								<div class="col-3">
									<label for="t1"><?=$this->lang->line("customer_status")?></label>
									<select class="custom-select custom-select-sm" id="i-status" name="i-status">
										<?php 
											if(!empty($status)):
										?>
											<option value="<?=$status?>"><?=$status?></option>
										<?php
											endif;
										?>
										<option value="Active">Active</option>
										<option value="Closed">Closed</option>
									</select>
								</div>
							</div>
							<div class="form-row">
								<div class="col-8">
									<label for="t1"><?=$this->lang->line("customer_name")?></label>
									<input type="text" class="form-control form-control-sm" name="i-name" id="i-name" placeholder="<?=$this->lang->line("customer_name")?>" value="<?=$name?>" >
								</div>
							</div>
							<div class="form-row">
								<div class="col-4">
									<label for="t1"><?=$this->lang->line("customer_attn_1")?></label>
									<input type="text" class="form-control form-control-sm" name="i-attn_1" id="i-attn_1" placeholder="<?=$this->lang->line("customer_attn_1")?>" value="<?=$attn_1?>" >
								</div>
								<div class="col-4">
									<label for="t1"><?=$this->lang->line("customer_attn_2")?></label>
									<input type="text" class="form-control form-control-sm" name="i-attn_2" placeholder="<?=$this->lang->line("customer_attn_2")?>" value="<?=$attn_2?>" >
								</div>
							</div>

							<div class="form-row">
								<div class="col-12">
									<label for=""><?=$this->lang->line("customer_mail_addr")?></label>
									<input type="text" class="form-control form-control-sm" name="i-mail_addr" id="i-mail_addr" placeholder="<?=$this->lang->line("customer_mail_addr")?>" value="<?=$mail_addr?>" >
								</div>
								<div class="col-12">
									<label for=""><?=$this->lang->line("customer_shop_addr")?></label>
									<input type="text" class="form-control form-control-sm" name="i-shop_addr" placeholder="<?=$this->lang->line("customer_shop_addr")?>" value="<?=$shop_addr?>" >
								</div>
								
							</div>

							<div class="form-row">
								<div class="col-6">
									<label for="t1"><?=$this->lang->line("customer_email_1")?></label>
									<input type="text" class="form-control form-control-sm" name="i-email_1" placeholder="<?=$this->lang->line("customer_email_1")?>" value="<?=$email_1?>" >
								</div>
								<div class="col-6">
									<label for="t1"><?=$this->lang->line("customer_email_2")?></label>
									<input type="text" class="form-control form-control-sm" name="i-email_2" placeholder="<?=$this->lang->line("customer_email_2")?>" value="<?=$email_2?>" >
								</div>
							</div>
							<div class="form-row">
								<div class="col-4">
									<label for="t1"><?=$this->lang->line("customer_phone")?></label>
									<input type="text" class="form-control form-control-sm" name="i-phone_1" id="i-phone_1" placeholder="00000000" value="<?=$phone_1?>" >
								</div>
								<div class="col-4">
									<label for="t1"><?=$this->lang->line("customer_fax")?></label>
									<input type="text" class="form-control form-control-sm" name="i-fax_1" placeholder="00000000" value="<?=$fax_1?>" >
								</div>
							</div>
						   

							<div class="form-row">
								<div class="col-12">
									<label for="t1"><?=$this->lang->line("customer_statement_remark")?></label>
									<textarea class="form-control form-control-sm" placeholder="Type Something" name="i-statement_remark" rows="2" ><?=$statement_remark?></textarea>
								</div>
							</div>
							<div class="form-row">
								<div class="col-12">
									<label for="t1"><?=$this->lang->line("customer_remark")?></label>
									<textarea class="form-control form-control-sm" placeholder="Type Something" name="i-remark" rows="2" ><?=$remark?></textarea>
								</div>
							</div>

							<div class="form-row">
								<div class="col-4">
									<label for="t1"><?=$this->lang->line("customer_payment_method")?></label>
									<select class="custom-select custom-select-sm" id="i-paymentmethod" name="i-pm_code" >
										<?php 
											if(!empty($pm_code) && $pm_code != "-1"):
												$key = array_search($pm_code, array_column($data_payment_method,"pm_code"));
										?>
											<option value="<?=$pm_code?>"><?=$data_payment_method[$key]["payment_method"]?></option>
										<?php
											else:
											?>
												<option value="-1"><?=$this->lang->line("function_select")?></option>
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
								<div class="col-4">
									<label for="t1"><?=$this->lang->line("customer_payment_term")?></label>
									<select class="custom-select custom-select-sm" id="i-paymentterms" name="i-pt_code" >
										<?php 
											if(!empty($pt_code) && $pt_code != "-1"):
												$key = array_search($pt_code, array_column($data_payment_term,"pt_code"));
										?>
											<option value="<?=$pt_code?>"><?=$data_payment_term[$key]["terms"]?></option>
                                        <?php
											else:
											?>
												<option value="-1"><?=$this->lang->line("function_select")?></option>
											<?php
                                            endif;
                                            foreach($data_payment_term as $k => $v):
										?>
                                            <option value="<?=$v['pt_code']?>"><?=$v['terms']?></option>
                                        <?php
                                            endforeach;
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
							<span class="badge badge-pill badge-secondary"><?=$this->lang->line("function_delivery")?></span>
							<div class="form-row">
								<div class="col-3">
									<label for="t1"><?=$this->lang->line("customer_district")?></label> 
									<select class="custom-select custom-select-sm" id="i-district" name="i-district" >
										<?php 
											if(!empty($district_code) && $district_code != "-1"):
										?>
											<option value="<?=$district_code?>"><?=$data_district[$district_code]["district_chi"]?></option>
										<?php
											else:
										?>
											<option value="-1"><?=$this->lang->line("function_select")?></option>
										<?php
											endif;
										?>
											
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
									<input type="text" class="form-control form-control-sm" name="i-delivery_addr" placeholder="Type Something" value="<?=$delivery_addr?>" >
								</div>
							</div>
							<div class="form-row">
								<div class="col-3">
									<label for="t1"><?=$this->lang->line("customer_delivery_from")?></label>
									<input type="text" class="form-control form-control-sm" name="i-from_time" placeholder="From" value="<?=$from_time?>" >
								</div>
								<div class="col-3">
									<label for="t1"><?=$this->lang->line("customer_delivery_to")?></label>
									<input type="text" class="form-control form-control-sm" name="i-to_time" placeholder="To" value="<?=$to_time?>" >
								</div>
							</div>
							<div class="form-row">
								<div class="col-4">
									<label for="t1"><?=$this->lang->line("customer_phone")?></label>
									<input type="text" class="form-control form-control-sm" name="i-delivery_phone" placeholder="00000000" value="<?=$phone_2?>" >
								</div>
								<div class="col-4">
									<label for="t1"><?=$this->lang->line("customer_fax")?></label>
									<input type="text" class="form-control form-control-sm" name="i-delivery_fax" placeholder="00000000" value="<?=$fax_2?>" >
								</div>
							</div>
							<div class="form-row">
								<div class="col-12">
									<label for="t1"><?=$this->lang->line("customer_delivery_remark")?></label>
									<textarea class="form-control form-control-sm" placeholder="<?=$this->lang->line("customer_delivery_remark")?>" name="i-delivery_remark" rows="3" ><?=$delivery_remark?></textarea>
								</div>
							</div>
						</li>
						<li class="list-group-item">
							<span class="badge badge-pill badge-secondary"><?=$this->lang->line("function_accounting")?></span>    
							<div class="form-row">
								<div class="col-8">
									<label for="t1"><?=$this->lang->line("customer_br_number")?></label>
									<input type="text" class="form-control form-control-sm" name="i-acc_company_br" id="i-br" placeholder="<?=$this->lang->line("customer_br_number")?>" value="<?=$company_BR?>" >
								</div>
							</div>
							<div class="form-row">
								<div class="col-8">
									<label for="t1"><?=$this->lang->line("customer_sign_company")?></label>
									<input type="text" class="form-control form-control-sm" name="i-acc_company_sign" placeholder="<?=$this->lang->line("customer_sign_company")?>" value="<?=$company_sign?>">
								</div>
							</div>
							<div class="form-row">
								<div class="col-8">
									<label for="t1"><?=$this->lang->line("customer_group")?></label>
									<input type="text" class="form-control form-control-sm" name="i-acc_group_name" placeholder="<?=$this->lang->line("customer_group")?>" value="<?=$group_name?>" >
								</div>
							</div>
							<div class="form-row">
								<div class="col-4">
									<label for="t1"><?=$this->lang->line("customer_accountant")?></label>
									<input type="text" class="form-control form-control-sm" name="i-acc_attn" id="i-acc_attn" placeholder="<?=$this->lang->line("customer_accountant")?>" value="<?=$attn?>" >
								</div>
							</div>
							<div class="form-row">
								<div class="col-8">
									<label for="t1"><?=$this->lang->line("customer_acc_email")?></label>
									<input type="text" class="form-control form-control-sm" name="i-acc_email" id="i-acc_email" placeholder="<?=$this->lang->line("customer_acc_email")?>" value="<?=$email?>" >
								</div>
							</div>
							<div class="form-row">
								<div class="col-4">
									<label for="t1"><?=$this->lang->line("customer_phone")?></label>
									<input type="text" class="form-control form-control-sm" name="i-acc_phone" placeholder="00000000" value="<?=$tel?>" >
								</div>
								<div class="col-4">
									<label for="t1"><?=$this->lang->line("customer_fax")?></label>
									<input type="text" class="form-control form-control-sm" name="i-acc_fax" placeholder="00000000" value="<?=$fax?>" >
								</div>
							</div>
						</li>
					</ul>
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