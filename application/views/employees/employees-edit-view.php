<?php
    echo "<pre>";
    var_dump($data['employees']);
    echo "</pre>";
    extract($data);
?>

<form id="form1" name="form1" method="POST" action="<?=$save_url?>">
    <div class="card">
        <div class="card-header">
            <h2> Employee: <u><?=$employees['employee_code']?></u></h2>
        </div>
        <div class="card-body">
            <div class="form-row">
                <div class="col-3">
                    <label for="">Username</label>
                    <input type="text" class="form-control form-control-sm" name="i-emp-code" placeholder="Type Something" value="<?=$employees['username']?>" >
                </div>
                <div class="col-3">
                    <label for="">Default Shop Code</label>
                    <select class="custom-select custom-select-sm" id="i-shops" name="i-shops" >
                        <option value=""><?=$employees['shop_name']?></option>
                        <?php 
                        foreach($shops as $key => $val):
                        ?>
                        <option value="<?=$val['shop_code']?>"><?=$val['name']?></option>
                        <?php
                        endforeach;
                        ?>
                    </select>
                </div>
            </div>
            <div class="form-row">
                <div class="col-3">
                    <label for="">Username</label>
                    <select class="custom-select custom-select-sm" id="i-shops" name="i-shops" >
                        <option value=""><?=$employees['shop_name']?></option>
                        <?php 
                        foreach($shops as $key => $val):
                        ?>
                        <option value="<?=$val['shop_code']?>"><?=$val['name']?></option>
                        <?php
                        endforeach;
                        ?>
                    </select>
                </div>
            </div>
            <div class="form-row">
                <div class="col-3">
                    <label for="">Username</label>
                    <select class="custom-select custom-select-sm" id="i-shops" name="i-shops" >
                        <option value=""><?=$employees['shop_name']?></option>
                        <?php 
                        foreach($shops as $key => $val):
                        ?>
                        <option value="<?=$val['shop_code']?>"><?=$val['name']?></option>
                        <?php
                        endforeach;
                        ?>
                    </select>
                </div>
            </div>
            <div class="form-row">
                <div class="col-2">
                    <label for="">Username</label>
                    <select class="custom-select custom-select-sm" id="i-shops" name="i-shops" >
                        <option value="<?=$employees['status']?>"><?=$employees['status'] ? "Active" : "Disable"?></option>
                        <option value="1">Active</option>
                        <option value="1">Disable</option>
                    </select>
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