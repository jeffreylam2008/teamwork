
<!-- Modal -->
<div class="modal fade" id="modal01" tabindex="-1" role="dialog" aria-labelledby="newitem" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <!-- Modal Head -->
                <h2 class="modal-title" id=""><b>New Customer</b></h2>
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
                                <div class="col-2">
                                    <label for="t1">Customer Code</label>
                                    <a href="#" class="btn btn-outline-primary btn-sm" id="gen-id" >Generate</a>
                                    <input type="text" class="form-control form-control-sm" name="i-cust_code" id="i-cust_code" placeholder="Customer Code" value="">
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="col-6">
                                    <label for="t1">Customer Shop</label>
                                    <input type="text" class="form-control form-control-sm" name="i-name" placeholder="Name" value="">
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="col-2">
                                    <label for="t1">Primary Attn </label>
                                    <input type="text" class="form-control form-control-sm" name="i-attn_1" placeholder="Primary Attn" value="">
                                </div>
                                <div class="col-2">
                                    <label for="t1">Secondary Attn </label>
                                    <input type="text" class="form-control form-control-sm" name="i-attn_2" placeholder="Secondary Attn" value="">
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="col-4">
                                    <label for="">Mail Address</label>
                                    <input type="text" class="form-control form-control-sm" name="i-mail_addr" placeholder="Type Something" value="">
                                </div>
                                <div class="col-4">
                                    <label for="">Shop Address</label>
                                    <input type="text" class="form-control form-control-sm" name="i-shop_addr" placeholder="Type Something" value="">
                                </div>
                                <div class="col-4">
                                    <label for="">Delivery Address</label>
                                    <input type="text" class="form-control form-control-sm" name="i-delivery_addr" placeholder="Type Something" value="">
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="col-3">
                                    <label for="t1">Primary Email</label>
                                    <input type="text" class="form-control form-control-sm" name="i-email_1" placeholder="Primary Email" value="">
                                </div>
                                <div class="col-3">
                                    <label for="t1">Secondary Email</label>
                                    <input type="text" class="form-control form-control-sm" name="i-email_2" placeholder="Secondary Email" value="">
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="col-3">
                                    <label for="t1">Phone 1</label>
                                    <input type="text" class="form-control form-control-sm" name="i-phone_1" placeholder="0000 0000" value="">
                                </div>
                                <div class="col-3">
                                    <label for="t1">Fax 1</label>
                                    <input type="text" class="form-control form-control-sm" name="i-fax_1" placeholder="0000 0000" value="">
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="col-3">
                                    <label for="t1">Phone 2</label>
                                    <input type="text" class="form-control form-control-sm" name="i-phone_2" placeholder="0000 0000" value="">
                                </div>
                                <div class="col-3">
                                    <label for="t1">Fax 2</label>
                                    <input type="text" class="form-control form-control-sm" name="i-fax_2" placeholder="0000 0000" value="">
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="col-6">
                                    <label for="t1">Remark</label>
                                    <textarea class="form-control form-control-sm" placeholder="Type Something" name="i-statement_remark" rows="2"></textarea>
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="col-3">
                                    <label for="t1">Payment Method</label>
                                    <a class='btn btn-outline-primary btn-sm' href='<?=$categories_baseurl?>' type='button'>New</a>
                                    <select class="custom-select custom-select-sm" id="i-paymentmethod">
                                        <option value="-1">Choose...</option>
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
                            <?php 
                                // $pt = "";
                                // if(!empty($payment_term[$pt_code]['terms'])){
                                //     $pt = $payment_term[$pt_code]['terms'];
                                // }
                            ?>
                            <div class="form-row">
                                <div class="col-3">
                                    <label for="t1">Payment Term</label>
                                    <a class='btn btn-outline-primary btn-sm' href='<?=$categories_baseurl?>' type='button'>New</a>
                                    <select class="custom-select custom-select-sm" id="i-paymentmethod">
                                        <option value="-1">Choose...</option>
                                        <?php 
                                            foreach($payment_term as $k => $v):
                                        ?>
                                                <option value="<?=$v["pt_code"]?>"><?=$v['terms']?></option>
                                        <?php
                                            endforeach;
                                        ?>
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
        $( "#form1" ).sisyphus( {
            locationBased: false,
            timeout: 5,
            autoRelease: true,
            onSave: function(){
                console.log("saved")
            }
        });
        $("#reset").click(function(){
            $("#form1").trigger("reset");
        });
        $("#modal01").on('hidden.bs.modal', function(){
            $("#form1").trigger("reset");
        });
        $("#gen-id").on("click", function(){
            var r = Math.floor(Math.random() * Date.now())
            var p = r.toString().substr(1,6)
            $("#i-cust_code").val("C"+p)
        });
    });
    // configure your validation
    $("#save").click(function(){
        $.validator.addMethod("selectValid", function(value, element, arg){
            return arg !== value;
        }, "This field is required.");

        var isvalid = $("#form1").validate({
            rules: {
                // simple rule, converted to {required:true}
                "i-cust_code": {
                    required: true
                },
                "i-name": {
                    required: true
                }
            }
        });
        if(isvalid){
            $("#form1").submit();
        }
        
    });
</script>
