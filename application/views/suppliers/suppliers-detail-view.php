<?php
// echo "<pre>";
// print_r($data);
// //print_r($data_payment_method);
// echo "</pre>";
extract($data);
?>
<form id="form1" name="form1" method="POST" action="">
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col">
                    <div class="form-row">
                        <div class="col-2">
                            <label for="t1">Status</label>
                            <select class="custom-select custom-select-sm" id="i-status" name="i-status" disabled>
                                <option value=""><?=$status?></option>
                            </select>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="col-6">
                            <label for="t1">Supplier Name</label>
                            <input type="text" class="form-control form-control-sm" name="i-name" id="i-name" placeholder="Name" value="<?=$name?>" disabled >
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="col-2">
                            <label for="t1">Attn *</label>
                            <input type="text" class="form-control form-control-sm" name="i-attn_1" id="i-attn_1" placeholder="Primary Attn" value="<?=$attn_1?>" disabled >
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="col-6">
                            <label for="">Mail Address</label>
                            <input type="text" class="form-control form-control-sm" name="i-mail_addr" id="i-mail_addr" placeholder="Type Something" value="<?=$mail_addr?>" disabled >
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="col-3">
                            <label for="t1">Email</label>
                            <input type="text" class="form-control form-control-sm" name="i-email_1" placeholder="Primary Email" value="<?=$email_1?>" disabled >
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="col-3">
                            <label for="t1">Phone *</label>
                            <input type="text" class="form-control form-control-sm" name="i-phone_1" id="i-phone_1" placeholder="00000000" value="<?=$phone_1?>" disabled >
                        </div>
                        <div class="col-3">
                            <label for="t1">Fax</label>
                            <input type="text" class="form-control form-control-sm" name="i-fax_1" placeholder="00000000" value="<?=$fax_1?>" disabled >
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="col-6">
                            <label for="t1">Remark</label>
                            <textarea class="form-control form-control-sm" placeholder="Type Something" name="i-remark" rows="2" disabled ><?=$remark?></textarea>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="col-2">
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
                        <div class="col-2">
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
                </div>
            </div>
        </div>
    </div>
</form>