<?php
    // echo "<pre>";
    // var_dump($employees);
    // echo "</pre>";
  
?>

<form id="form1" name="form1" method="POST" action="<?=$save_url?>">
    <div class="card">
        <div class="card-header">
            <h2> Employee: <u><?=$employees['employee_code']?></u></h2>
        </div>
        <div class="card-body">
            <div class="form-row">
                <div class="col-3">
                    <label for="">* Employee ID</label>
                    <input type="text" class="form-control form-control-sm" name="i-emp-code" placeholder="Employee ID" value="<?=$employees['employee_code']?>" >
                </div>
            </div>
            <div class="form-row">
                <div class="col-3">
                    <label for="">* Username</label>
                    <input type="text" class="form-control form-control-sm" name="i-username" placeholder="Username" value="<?=$employees['username']?>" >
                    
                </div>
            </div>
            <div class="form-row">
                <div class="col-12">
                    <input type="button" href="#pwd" class="btn-primary btn-sm" data-toggle='collapse' aria-expanded='true' value="Change Password" />
                    <div class="collapse" id="pwd">
                        <div class="form-row">
                            <div class="col-3">
                                <label for="">* Password</label>
                                <input type="password" class="form-control form-control-sm" name="i-pwd" id="i-pwd" placeholder="Password" value="" >
                            </div>
                            <div class="col-3">
                                <label for="">* Comfirm Password</label>
                                <input type="password" class="form-control form-control-sm" name="i-confirm-pwd" placeholder="Confirm Password" value="" >
                            </div> 
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="form-row">
                <div class="col-3">
                    <label for="">Default Shop</label>
                    <select class="custom-select custom-select-sm" id="i-shops" name="i-shops" >
                        <option value="<?=$employees['default_shopcode']?>"><?=$employees['shop_name']?></option>
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
                    <label for="">Status</label>
                    <select class="custom-select custom-select-sm" id="i-status" name="i-status" >
                        <option value="<?=$employees['status']?>"><?=$employees['status'] ? "Active" : "Disable"?></option>
                        <option value="1">Active</option>
                        <option value="0">Disable</option>
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
            "i-emp-code": {
                required: true
            },
            "i-username": {
                required: true
            },
            "i-pwd": {
                required: true
            },
            "i-confirm-pwd": {
                required: true,
                equalTo: "#i-pwd"
            }
        }
    });

    if(isvalid){
        $("#form1").submit();
    }
    console.log("clicked")
});

</script>