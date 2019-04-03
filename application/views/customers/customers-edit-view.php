<?php
    extract($data);
?>

<form id="form1" name="form1" method="POST" action="">
<div class="card">
    <div class="card-header">
        <h2> Customer Code: <u></u></h2>
    </div>
    <div class="card-body">

        <div class="form-row">
            <div class="col-3">
                <label for="">Chinese Name</label>
                <input type="text" class="form-control form-control-sm" name="i-chiname" placeholder="Type Something" value="">
            </div>
            <div class="col-3">
                <label for="">English Name</label>
                <input type="text" class="form-control form-control-sm" name="i-engname" placeholder="Type Something" value="">
            </div>
        </div>

        <div class="form-row">
            <div class="col-6">
                <label for="t1">Description</label>
                <textarea class="form-control form-control-sm" placeholder="Type Something" name="i-desc" rows="2"></textarea>
            </div>
        </div>

        <div class="form-row">
            <div class="col-2">
                <label>Price</label>
            <div class="input-group input-group-sm">
                <div class="input-group-prepend">
                    <span class="input-group-text">$</span>
                </div>
                <input type="text" class="form-control" placeholder="0.00" name="i-price" value="">
            </div>
        </div>
        </div>
        <div class="form-row">
            <div class="col-2">
                <label>Special Price</label>
                <div class="input-group input-group-sm">
                        <div class="input-group-prepend">
                            <span class="input-group-text">$</span>
                        </div>
                        <input type="text" class="form-control" placeholder="0.00" name="i-specialprice" value="">
                </div>
            </div>
        </div>
        <div class="form-row">
            <div class="col-6">
                <label>Category</label>                
                <a class='btn btn-outline-primary btn-sm' href='' type='button'>New</a>
                <select class="form-control" name="i-category">
                
                </select>
            </div>
        </div>
        <div class="form-row">
            <div class="col-6">
                <label>Unit</label>
                <input type="text" class="form-control form-control-sm" name="i-unit" placeholder="i.e. pack, 4x3L etc" value="">
            </div>
        </div>
    </div>
</div>
</form>
<script>
$("#save").click(function(){
    $.validator.addMethod("selectValid", function(value, element, arg){
        return arg !== value;
    }, "This field is required.");

    var isvalid = $("#form1").validate({
        rules: {
            // simple rule, converted to {required:true}
            "i-itemcode": {
                required: true,
                minlength: 2
            },
            "i-chiname": {
                required: true
            },
            "i-price" : {
                required: true,
                number: true
            },
            "i-specialprice" : {
                required: true,
                number: true
            },
            "i-category": {
                selectValid : "null"
            }
        }
    });
    if(isvalid){
        $("#form1").submit();
    }
    console.log("clicked")
});

</script>