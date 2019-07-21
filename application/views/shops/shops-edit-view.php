<?php
    // echo "<pre>";
    // var_dump($data);
    // echo "</pre>";
    extract($data);
?>

<form id="form1" name="form1" method="POST" action="<?=$save_url?>">
    <div class="card">
        <div class="card-header">
            <h2> Shop: <u><?=$shop_code?></u></h2>
        </div>
        <div class="card-body">

            <div class="form-row">
                <div class="col-4">
                    <label for="t1">Shop Name</label>
                    <input type="text" class="form-control form-control-sm" name="i-name" id="i-name" placeholder="Name" value="<?=$name?>">
                </div>
            </div>

            <div class="form-row">
                <div class="col-3">
                    <label for="t2">Phone</label>
                    <input type="text" class="form-control form-control-sm" name="i-phone" id="i-phone" placeholder="phone" value="<?=$phone?>">
                </div>
            </div>

            <div class="form-row">
                <div class="col-6">
                    <label for="t3">Address 1</label>
                    <input type="text" class="form-control form-control-sm" name="i-address1" id="i-address1" placeholder="Address 1" value="<?=$address1?>">
                </div>
            </div>

            <div class="form-row">
                <div class="col-6">
                    <label for="t4">Address 2</label>
                    <input type="text" class="form-control form-control-sm" name="i-address2" id="i-address2" placeholder="Address 2" value="<?=$address2?>">
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
            "i-phone": {
                required: true,
                minlength: 8,
                maxlength: 9
            },
            "i-address1" : {
                required: true
            }
        }
    });
    if(isvalid){
        $("#form1").submit();
    }
    console.log("clicked")
});

</script>