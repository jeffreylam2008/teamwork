<?php
    extract($data["query"]);
?>

<form id="form1" name="form1" method="POST" action="<?=$save_url.$item_code?>">
<div class="card">
    <div class="card-header">
        <h2> Item Code: <u><?=$item_code?></u></h2>
    </div>
    <div class="card-body">

        <div class="form-row">
            <div class="col-3">
                <label for="">Chinese Name</label>
                <input type="text" class="form-control form-control-sm" name="i-chiname" placeholder="Type Something" value="<?=$chi_name?>">
            </div>
            <div class="col-3">
                <label for="">English Name</label>
                <input type="text" class="form-control form-control-sm" name="i-engname" placeholder="Type Something" value="<?=$eng_name?>">
            </div>
        </div>

        <div class="form-row">
            <div class="col-6">
                <label for="t1">Description</label>
                <textarea class="form-control form-control-sm" placeholder="Type Something" name="i-desc" rows="2"><?=$desc?></textarea>
            </div>
        </div>

        <div class="form-row">
            <div class="col-2">
                <label>Price</label>
            <div class="input-group input-group-sm">
                <div class="input-group-prepend">
                    <span class="input-group-text">$</span>
                </div>
                <input type="text" class="form-control" placeholder="0.00" name="i-price" value="<?=$price?>">
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
                        <input type="text" class="form-control" placeholder="0.00" name="i-specialprice" value="<?=$price_special?>">
                </div>
            </div>
        </div>
        <div class="form-row">
            <div class="col-6">
                <label>Items Type</label> <a href='<?=$categories_baseurl?>'>New</a>
                <select class="form-control" name="i-category">
                <?php if(array_key_exists($cate_code, $categories)):?>
                    <option value="<?=$cate_code?>"><?=$categories[$cate_code]?></option>
                <?php endif;?>
                    <?php 
                            foreach($categories as $k => $v):
                    ?>
                                <?="<option value='".$k."'>".$v."</option>"?>
                    <?php
                            endforeach;
                    ?>
                </select>
            </div>
        </div>
        <div class="form-row">
            <div class="col-6">
                <label>Unit</label>
                <input type="text" class="form-control form-control-sm" name="i-unit" placeholder="i.e. pack, 4x3L etc" value="<?=$unit?>">
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