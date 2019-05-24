<?php
    echo "<pre>";
    var_dump($data);
    echo "</pre>";
    echo "<pre>";
    var_dump($data_payment_method);
    echo "</pre>";

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
                            <div class="col-8">
                                <label for="t1">Customer Shop</label>
                                <input type="text" class="form-control form-control-sm" name="i-name" id="i-name" placeholder="Name" value="<?=$name?>">
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="col-4">
                                <label for="t1">Primary Attn</label>
                                <input type="text" class="form-control form-control-sm" name="i-attn_1" id="i-attn_1" placeholder="Primary Attn" value="<?=$attn_1?>">
                            </div>
                            <div class="col-4">
                                <label for="t1">Secondary Attn </label>
                                <input type="text" class="form-control form-control-sm" name="i-attn_2" placeholder="Secondary Attn" value="<?=$attn_2?>">
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="col-12">
                                <label for="">Mail Address</label>
                                <input type="text" class="form-control form-control-sm" name="i-mail_addr" id="i-mail_addr" placeholder="Type Something" value="<?=$mail_addr?>">
                            </div>
                            <div class="col-12">
                                <label for="">Shop Address</label>
                                <input type="text" class="form-control form-control-sm" name="i-shop_addr" placeholder="Type Something" value="<?=$shop_addr?>">
                            </div>
                            
                        </div>

                        <div class="form-row">
                            <div class="col-6">
                                <label for="t1">Primary Email</label>
                                <input type="text" class="form-control form-control-sm" name="i-email_1" placeholder="Primary Email" value="<?=$email_2?>">
                            </div>
                            <div class="col-6">
                                <label for="t1">Secondary Email</label>
                                <input type="text" class="form-control form-control-sm" name="i-email_2" placeholder="Secondary Email" value="<?=$email_2?>">
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="col-4">
                                <label for="t1">Phone 1</label>
                                <input type="text" class="form-control form-control-sm" name="i-phone_1" id="i-phone_1" placeholder="0000 0000" value="<?=$phone_1?>">
                            </div>
                            <div class="col-4">
                                <label for="t1">Fax 1</label>
                                <input type="text" class="form-control form-control-sm" name="i-fax_1" placeholder="0000 0000" value="<?=$fax_1?>">
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="col-4">
                                <label for="t1">Phone 2</label>
                                <input type="text" class="form-control form-control-sm" name="i-phone_2" placeholder="0000 0000" value="<?=$phone_2?>">
                            </div>
                            <div class="col-4">
                                <label for="t1">Fax 2</label>
                                <input type="text" class="form-control form-control-sm" name="i-fax_2" placeholder="0000 0000" value="<?=$fax_2?>">
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="col-12">
                                <label for="t1">Statement Remark</label>
                                <textarea class="form-control form-control-sm" placeholder="Type Something" name="i-statement_remark" rows="2"><?=$statement_remark?></textarea>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="col-12">
                                <label for="t1">Remark</label>
                                <textarea class="form-control form-control-sm" placeholder="Type Something" name="i-remark" rows="2"><?=$remark?></textarea>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="col-4">
                                <label for="t1">Payment Method</label>
                                <select class="custom-select custom-select-sm" id="i-paymentmethod" name="i-pm_code">
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
                                <select class="custom-select custom-select-sm" id="i-paymentterms" name="i-pt_code">
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
								<label for="t1">District</label>
                                <input type="text" class="form-control form-control-sm" name="i-district_chi" placeholder="District" value="<?=$district_chi?>" disabled>
							</div>
							<div class="col-6">
								<label for="t1">District</label>
                                <input type="text" class="form-control form-control-sm" name="i-district_eng" placeholder="District" value="<?=$district_eng?>" disabled>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="col-12">
                                <label for="">Delivery Address</label>
                                <input type="text" class="form-control form-control-sm" name="i-delivery_addr" placeholder="Type Something" value="<?=$delivery_addr?>">
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="col-3">
                                <label for="t1">From</label>
                                <input type="text" class="form-control form-control-sm" name="i-from_time" placeholder="From" value="<?=$from_time?>">
                            </div>
							<div class="col-3">
                                <label for="t1">To</label>
                                <input type="text" class="form-control form-control-sm" name="i-to_time" placeholder="To" value="<?=$to_time?>">
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="col-12">
                                <label for="t1">Delivery Remark</label>
                                <textarea class="form-control form-control-sm" placeholder="Type Something" name="i-delivery_remark" rows="3"></textarea>
                            </div>
                        </div>
                    </li>
                    <li class="list-group-item">
                        <span class="badge badge-pill badge-secondary">Account</span>    
                        <div class="form-row">
                            <div class="col-8">
                                <label for="t1">Company BR Number</label>
                                <input type="text" class="form-control form-control-sm" name="i-name" id="i-br" placeholder="BR Number" value="">
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="col-8">
                                <label for="t1">Group Name</label>
                                <input type="text" class="form-control form-control-sm" name="i-group_name" placeholder="Group" value="">
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="col-4">
                                <label for="t1">Accountant</label>
                                <input type="text" class="form-control form-control-sm" name="i-attn_1" id="i-attn_1" placeholder="" value="">
                            </div>

                        </div>
                    </li>
                </ul>
            </div>
            
        </div>                      
        <!-- end card body -->
    </div>
