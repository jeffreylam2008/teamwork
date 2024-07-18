<?php
    extract($data);
    // echo "<pre>";
    // print_r($data);
    // echo "</pre>";
?>

<form id="form1" name="form1" method="POST" action="<?=$save_url.$item_code?>" enctype="multipart/form-data">
<div class="card">
    <div class="card-header">
        <h2> <?=$this->lang->line("item_code")?>: <u><?=$item_code?></u></h2>
    </div>
    <div class="card-body">
        <div class="container-fluid">
            <div class="row">
                <div class="col-6">
                    <div class="form-row">
                        <div class="col-6">
                            <label for=""><?=$this->lang->line("item_chi_name")?></label>
                            <input type="text" class="form-control form-control-sm" name="i-chiname" placeholder="<?=$this->lang->line("item_chi_name")?>" value="<?=$chi_name?>">
                        </div>
                        <div class="col-6">
                            <label for=""><?=$this->lang->line("item_eng_name")?></label>
                            <input type="text" class="form-control form-control-sm" name="i-engname" placeholder="<?=$this->lang->line("item_eng_name")?>" value="<?=$eng_name?>">
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="col-12">
                            <label for="t1"><?=$this->lang->line("label_description")?></label>
                            <textarea class="form-control form-control-sm" placeholder="<?=$this->lang->line("label_description")?>" name="i-desc" rows="2"><?=$desc?></textarea>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="col-4">
                            <label><?=$this->lang->line("item_price")?></label>
                        <div class="input-group input-group-sm">
                            <div class="input-group-prepend">
                                <span class="input-group-text">$</span>
                            </div>
                            <input type="text" class="form-control" placeholder="0.00" name="i-price" value="<?=$price?>">
                        </div>
                    </div>
                    </div>
                    <div class="form-row">
                        <div class="col-4">
                            <label><?=$this->lang->line("item_discount")?></label>
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
                            <label><?=$this->lang->line("category_label")?></label>                
                            <a class='btn btn-outline-primary btn-sm' href='<?=$categories_baseurl?>' type='button'><?=$this->lang->line("function_new")?></a>
                            <select class="form-control" name="i-category">
                            <?php if( array_key_exists($cate_code, $categories)):?>
                                <option value="<?=$cate_code?>"><?=$categories[$cate_code]?></option>
                            <?php
                                else:
                            ?>  
                                <option value="null"><?=$this->lang->line("function_select")?></option>
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
                            <label><?=$this->lang->line("item_type")?></label> 
                            <select class="form-control" id="i-type" name="i-type">
                                <?php if( array_key_exists($type, $types)):?>
                                    <option value="<?=$type?>"><?=$types[$type]?></option>
                                <?php
                                    else:
                                ?>
                                <option value="-1"><?=$this->lang->line("function_select")?></option>
                                <?php endif;?>                                                                   
                                <option value='1'><?=$this->lang->line("item_non_inventory")?></option>
                                <option value='2'><?=$this->lang->line("item_inventory")?></option>
                                <option value='3'><?=$this->lang->line("item_non_inventory_point")?></option>
                            </select>
                        </div>
                    </div>    
                    <div class="form-row">
                        <div class="col-6">
                            <label><?=$this->lang->line("item_unit")?></label>
                            <input type="text" class="form-control form-control-sm" name="i-unit" placeholder="i.e. pack, 4x3L etc" value="<?=$unit?>">
                        </div>
                    </div>
                </div>
                <div class="col-6">
                    <div class="form-row" id="i-file">
                        <div class="col-10">
                            <label><?=$this->lang->line("item_image")?></label>
                            <input type="file" class="form-control form-control-sm" id="i-img" name="i-img" value="">
                        </div>
                    </div>
                    <?php if(!$remove_img):?>
                        <div class="form-row">
                            <div class="col-10">
                                <a href="#" type="button" class="btn btn-danger btn-sm" id="i-img-remove" name="i-img-remove" ><?=$this->lang->line("item_removeimage")?></a>
                            </div>
                        </div>   
                    <?php endif;?>
                    <div class="form-row">
                        <div class="col-10">
                            <img id="i-preview" src="<?=$image_body?>" width="300" height="300">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="card">
    <div class="card-header">
        <h2> <?=$this->lang->line("warehouse")?></u></h2>
    </div>
    <div class="card-body">
        <div class="form-row">
            <div class="col-2">
                <label for=""><?=$this->lang->line("item_Stockonhand")?></label>
                <input type="text" class="form-control form-control-sm" name="i-stockonhand" placeholder="No Data" value="<?=$stockonhand?>" disabled>
            </div>
        </div>
    </div>
</div>
</form>
<script>

$(document).ready(function() {
    // The change event while edit product image
    $("#i-img").on("change", function(event){
        if(event.target.files.length > 0){
            var src = URL.createObjectURL(event.target.files[0])
            var preview = document.getElementById("i-preview")
            preview.src = src
            preview.style.display = "block"
        }
    });
    // The remove event to remove product image then display back default empty image  
    $("#i-img-remove").on("click", function(event){
        var src = "<?=base_url("/assets/img/empty-img.jpg")?>";
        var preview = document.getElementById("i-preview")
        preview.src = src
        preview.style.display = "block"
        $(this).hide()
        $("#i-file").show()
        $("#i-img").prop("disabled", false)
    });
});
$("#save").click(function(){
    $.validator.addMethod("selectValid", function(value, element, arg){
        return arg !== value;
    });
    $.validator.addMethod("fileMax", function(value, element, arg){
        if(element.files[0] !== undefined ){
            if(element.files[0].size<=arg){
                return true;
            }else{
                return false;
            }
        }
        else{
            return true;
        }
    });
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
            },
            "i-img": {
                accept:"image/jpeg,image/png",
                fileMax: 2097152
            }
        },
        messages: {
            "i-category": { selectValid: "This field is required."},
            "i-img":{
                fileMax:" file size must be less than 2MB.",
                accept:"Please upload .jpg or .png file of notice.",
            }
        }
    });
    if(isvalid){
        $("#form1").submit();
    }
});

</script>