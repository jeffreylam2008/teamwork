<?php
    extract($data["query"]);
?>

<form id="form1" name="form1" method="POST" action="<?=$save_url.$cate_code?>">
    <div class="card">
        <div class="card-header">
            <h2>Edit Category: <u></u></h2>
        </div>
        <div class="card-body"> 
            <div class="form-row">
                <div class="col-3">
                    <label for="">Category Code</label>
                    <input type="text" class="form-control form-control-sm" name="i-catecode" placeholder="Categorg Code" value="<?=$cate_code?>">
                </div>
                <div class="col-3">
                    <label for="">Description</label>
                    <input type="text" class="form-control form-control-sm" name="i-decs" placeholder="Description" value="<?=$desc?>">
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
            "i-catecode": {
                required: true,
                minlength: 3
            },
            "i-decs": {
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