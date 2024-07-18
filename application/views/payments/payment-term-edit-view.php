<?php

extract($data);
?>
<form id="form1" name="form1" method="POST" action="<?=$save_url.$pt_code?>">
    <div class="card">
        <div class="card-header">
            <h2><?=$this->lang->line("function_edit") . $this->lang->line("paymentterms_title")?>: <u><?=$pt_code?></u></h2>
        </div>
        <div class="card-body"> 
            <div class="form-row">
                <div class="col-3">
                    <label for=""><?=$this->lang->line("paymentterms_code")?></label>
                    <input type="text" class="form-control form-control-sm" name="i-pm-code" placeholder="<?=$this->lang->line("paymentterms_code")?>" value="<?=$pt_code?>" disabled>
                </div>
                <div class="col-3">
                    <label for=""><?=$this->lang->line("paymentterms_title")?></label>
                    <textarea class="form-control form-control-sm" name="i-payment-term" placeholder="<?=$this->lang->line("paymentterms_title")?>"><?=$terms?></textarea>
                </div>

            </div>

        </div>
    </div>

</form>

<script>


$("#save").click(function(){
    var isvalid = $("#form1").validate({
        rules: {
            // simple rule, converted to {required:true}
            "i-payment-term": {
                required: true
            }
        }
    });
    if(isvalid){
        $("#form1").submit();
    }
    // console.log("clicked")
});

</script>