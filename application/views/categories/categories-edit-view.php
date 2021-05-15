<?php

    extract($data);
?>

<form id="form1" name="form1" method="POST" action="<?=$save_url.$cate_code?>">
    <div class="card">
        <div class="card-header">
            <h2><b><?=$this->lang->line("category_edit_titles")?></b></h2>
        </div>
        <div class="card-body"> 
            <div class="form-row">
                <div class="col-3">
                    <label for=""><?=$this->lang->line("category_label")?></label>
                    <input type="text" class="form-control form-control-sm" name="i-catecode" placeholder="<?=$this->lang->line("category_label")?>" value="<?=$cate_code?>" disabled>
                </div>
                <div class="col-3">
                    <label for=""><?=$this->lang->line("category_description")?></label>
                    <input type="text" class="form-control form-control-sm" name="i-desc" placeholder="<?=$this->lang->line("category_description")?>" value="<?=$desc?>">
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
            }
        }
    });
    if(isvalid){
        $("#form1").submit();
    }
    // console.log("clicked")
});

</script>