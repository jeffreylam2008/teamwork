<?php
extract($data);
?>
<form id="form1" name="form1" method="POST" action="<?=$save_url.$pm_code?>">
    <div class="card">
        <div class="card-header">
            <h2>Edit Payment Method: <u></u></h2>
        </div>
        <div class="card-body"> 
            <div class="form-row">
                <div class="col-3">
                    <label for="">Code</label>
                    <input type="text" class="form-control form-control-sm" name="i-pm-code" placeholder="Categorg Code" value="<?=$pm_code?>" disabled>
                </div>
                <div class="col-3">
                    <label for="">Name</label>
                    <input type="text" class="form-control form-control-sm" name="i-payment-method" placeholder="Categorg Code" value="<?=$payment_method?>">
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
            "i-payment-method": {
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