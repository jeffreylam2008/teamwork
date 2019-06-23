<?php
    // echo "<pre>";
    // var_dump($data);
    // echo "</pre>";
    extract($data);
?>

<form id="form1" name="form1" method="POST" action="<?=$save_url?>">
    <div class="card">
        <div class="card-header">
            <h2> Employee: <input type="text" class="form-control form-control-sm" name="i-emp-code" placeholder="Type Something" value="<?=$employee_code?>"></h2>
        </div>
        <div class="card-body">
            <div class="form-row">
                <div class="col-3">
                    <label for="">Username</label>
                    <input type="text" class="form-control form-control-sm" name="i-emp-code" placeholder="Type Something" value="<?=$username?>" disabled>
                </div>
                <div class="col-3">
                    <label for="">Default Shop Code</label>
                    
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