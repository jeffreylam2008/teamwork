<?php
    // echo "<pre>";
    // var_dump($data);
    // echo "</pre>";
    //echo "<pre>";
    //var_dump($data_payment_method);
    //echo "</pre>";

    extract($data);
?>
    <div class="card">
        <div class="card-header">
            <h2> Customer: <u><?=$cust_code?></u></h2>
        </div>
        <!-- Card body -->
        <div class="card-body">
			<div class="row">
				<div class="col">
					<ul class="list-group">
						<li class="list-group-item">
							<span class="badge badge-pill badge-secondary">General</span> 
							<div class="form-row">
								<div class="col-3">
									<label for="t1">Status</label>
									<select class="custom-select custom-select-sm" id="i-status" name="i-status" disabled>
										<?php 
											if(!empty($status)):
												switch($status):
													case "Active":
														echo "<option value='Active'>Active</option>";
													break;
													case "Closed":
														echo "<option value='Closed'>Closed</option>";
													break;
												endswitch;
											endif;
										?>
									</select>
								</div>
							</div>
							<div class="form-row">
								<div class="col-8">
									<label for="t1">Customer Shop</label>
									<input type="text" class="form-control form-control-sm" name="i-name" id="i-name" placeholder="" value="<?=$name?>" disabled>
								</div>
							</div>
							<div class="form-row">
								<div class="col-4">
									<label for="t1">Primary Attn</label>
									<input type="text" class="form-control form-control-sm" name="i-attn_1" id="i-attn_1" placeholder="" value="<?=$attn_1?>" disabled>
								</div>
								<div class="col-4">
									<label for="t1">Secondary Attn </label>
									<input type="text" class="form-control form-control-sm" name="i-attn_2" placeholder="" value="<?=$attn_2?>" disabled>
								</div>
							</div>

							<div class="form-row">
								<div class="col-12">
									<label for="">Mail Address</label>
									<input type="text" class="form-control form-control-sm" name="i-mail_addr" id="i-mail_addr" placeholder="" value="<?=$mail_addr?>" disabled>
								</div>
								<div class="col-12">
									<label for="">Shop Address</label>
									<input type="text" class="form-control form-control-sm" name="i-shop_addr" placeholder="" value="<?=$shop_addr?>" disabled>
								</div>
								
							</div>

							<div class="form-row">
								<div class="col-6">
									<label for="t1">Primary Email</label>
									<input type="text" class="form-control form-control-sm" name="i-email_1" placeholder="" value="<?=$email_1?>" disabled>
								</div>
								<div class="col-6">
									<label for="t1">Secondary Email</label>
									<input type="text" class="form-control form-control-sm" name="i-email_2" placeholder="" value="<?=$email_2?>" disabled>
								</div>
							</div>
							<div class="form-row">
								<div class="col-4">
									<label for="t1">Phone 1</label>
									<input type="text" class="form-control form-control-sm" name="i-phone_1" id="i-phone_1" placeholder="" value="<?=$phone_1?>" disabled>
								</div>
								<div class="col-4">
									<label for="t1">Fax 1</label>
									<input type="text" class="form-control form-control-sm" name="i-fax_1" placeholder="" value="<?=$fax_1?>" disabled>
								</div>
							</div>
						   

							<div class="form-row">
								<div class="col-12">
									<label for="t1">Statement Remark</label>
									<textarea class="form-control form-control-sm" placeholder="" name="i-statement_remark" rows="2" disabled><?=$statement_remark?></textarea>
								</div>
							</div>
							<div class="form-row">
								<div class="col-12">
									<label for="t1">Remark</label>
									<textarea class="form-control form-control-sm" placeholder="" name="i-remark" rows="2" disabled><?=$remark?></textarea>
								</div>
							</div>

							<div class="form-row">
								<div class="col-4">
									<label for="t1">Payment Method</label>
									
									<select class="custom-select custom-select-sm" id="i-paymentmethod" name="i-pm_code" disabled>
										<?php 
											
											if(!empty($pm_code) && $pm_code != "-1"):
												$key = array_search($pm_code, array_column($data_payment_method,"pm_code"));	
										?>
											<option value="<?=$pm_code?>"><?=$data_payment_method[$key]["payment_method"]?></option>
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
												$key = array_search($pt_code, array_column($data_payment_term,"pt_code"));
										?>
											<option value="<?=$pt_code?>"><?=$data_payment_term[$key]["terms"]?></option>
										<?php
											endif;
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
								<div class="col-6">
									<label for="t1">District Code</label>
									<?php 
										if(!empty($district_code) && $district_code != "-1"):
											$_dc = $district_code;
										else:
											$_dc = "N/A";
										endif;
									?>
									<input type="text" class="form-control form-control-sm" name="i-district_code" placeholder="" value="<?=$_dc?> <?=$district_chi?> <?=$district_eng?>" disabled>

								</div>

							</div>
							<div class="form-row">
								<div class="col-12">
									<label for="">Delivery Address</label>
									<input type="text" class="form-control form-control-sm" name="i-delivery_addr" placeholder="" value="<?=$delivery_addr?>" disabled>
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
									<input type="text" class="form-control form-control-sm" name="i-delivery_phonn" placeholder="" value="<?=$phone_2?>" disabled>
								</div>
								<div class="col-4">
									<label for="t1">Fax</label>
									<input type="text" class="form-control form-control-sm" name="i-delivery_fax" placeholder="" value="<?=$fax_2?>" disabled>
								</div>
							</div>
							<div class="form-row">
								<div class="col-12">
									<label for="t1">Delivery Remark</label>
									<textarea class="form-control form-control-sm" placeholder="" name="i-delivery_remark" rows="3" disabled><?=$delivery_remark?></textarea>
								</div>
							</div>
						   
						</li>
						<li class="list-group-item">
							<span class="badge badge-pill badge-secondary">Account</span>    
							<div class="form-row">
								<div class="col-8">
									<label for="t1">Company BR Number</label>
									<input type="text" class="form-control form-control-sm" name="i-acc_company_br" id="i-br" placeholder="" value="<?=$company_BR?>" disabled>
								</div>
							</div>
							<div class="form-row">
								<div class="col-8">
									<label for="t1">Company Sign</label>
									<input type="text" class="form-control form-control-sm" name="i-acc_company_sign" placeholder="" value="<?=$company_sign?>" disabled>
								</div>
							</div>
							<div class="form-row">
								<div class="col-8">
									<label for="t1">Group Name</label>
									<input type="text" class="form-control form-control-sm" name="i-acc_group_name" placeholder="" value="<?=$group_name?>" disabled>
								</div>
							</div>
							<div class="form-row">
								<div class="col-4">
									<label for="t1">Accountant</label>
									<input type="text" class="form-control form-control-sm" name="i-acc_attn" id="i-acc_attn" placeholder="" value="<?=$attn?>" disabled>
								</div>
							</div>
							<div class="form-row">
								<div class="col-8">
									<label for="t1">Accountant Email</label>
									<input type="text" class="form-control form-control-sm" name="i-acc_email" id="i-acc_email" placeholder="" value="<?=$email?>" disabled >
								</div>
							</div>
							<div class="form-row">
								<div class="col-4">
									<label for="t1">Phone</label>
									<input type="text" class="form-control form-control-sm" name="i-acc_phone" placeholder="" value="<?=$tel?>" disabled>
								</div>
								<div class="col-4">
									<label for="t1">Fax</label>
									<input type="text" class="form-control form-control-sm" name="i-acc_fax" placeholder="" value="<?=$fax?>" disabled>
								</div>
							</div>
						</li>
					</ul>
				</div>
			</div>
		</div>
        <!-- end card body -->
    </div>
