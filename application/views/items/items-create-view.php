<!-- Modal -->
<div class="modal fade" id="modal01" tabindex="-1" role="dialog" aria-labelledby="newitem" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <!-- Modal Head -->
                <h2 class="modal-title" id=""><b>New Item</b></h2>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
                <!-- Modal Head End -->
            </div>
            <div class="modal-body">
                <?php echo $function_bar; ?>

                <form id="form2" name="form2" method="POST" action="<?=$save_url?>" enctype="multipart/form-data">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-6">
                                <div class="form-row">
                                    <div class="col-6">
                                        <label for=""><?=$this->lang->line("item_code")?></label>
                                        <input type="text" class="form-control form-control-sm" id="i-itemcode" name="i-itemcode" placeholder="<?=$this->lang->line("item_code")?>" value="">
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="col-6">
                                        <label for=""><?=$this->lang->line("item_chi_name")?></label>
                                        <input type="text" class="form-control form-control-sm" id="i-chiname" name="i-chiname" placeholder="<?=$this->lang->line("item_chi_name")?>" value="">
                                    </div>
                                    <div class="col-6">
                                        <label for=""><?=$this->lang->line("item_eng_name")?></label>
                                        <input type="text" class="form-control form-control-sm" id="i-engname" name="i-engname" placeholder="<?=$this->lang->line("item_eng_name")?>" value="">
                                    </div>
                                </div>

                                <div class="form-row">
                                    <div class="col-12">
                                        <label for="t1"><?=$this->lang->line("label_description")?></label>
                                        <textarea class="form-control form-control-sm" placeholder="<?=$this->lang->line("label_description")?>" id="i-desc" name="i-desc" rows="2"></textarea>
                                    </div>
                                </div>

                                <div class="form-row">
                                    <div class="col-4">
                                        <label><?=$this->lang->line("item_price")?></label>
                                        <div class="input-group input-group-sm">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">$</span>
                                            </div>
                                            <input type="text" class="form-control" placeholder="0.00" id="i-price" name="i-price" value="">
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
                                                <input type="text" class="form-control" placeholder="0.00" id="i-specialprice" name="i-specialprice" value="0">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="col-12">
                                        <label><?=$this->lang->line("category_label")?></label>                
                                        <a class='btn btn-outline-primary btn-sm' href='<?=$categories_baseurl?>' type='button'><?=$this->lang->line("function_new")?></a>
                                        <select class="form-control" id="i-category" name="i-category">
                                        <option value="null"><?=$this->lang->line("function_select")?></option>
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
                                    <div class="col-12">
                                        <label>Type</label>  
                                        <select class="form-control" id="i-type" name="i-type">
                                            <option value="null"><?=$this->lang->line("function_select")?></option>                                            
                                            <option value='1'>Non Inventory</option>
                                            <option value='2'>Inventory</option>
                                            <option value='3'>Non Inventory - Point</option>
                                        </select>
                                    </div>
                                </div>    

                                <div class="form-row">
                                    <div class="col-12">
                                        <label>Unit</label>
                                        <input type="text" class="form-control form-control-sm" id="i-unit" name="i-unit" placeholder="i.e. pack, 4x3L etc" value="">
                                    </div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-row">
                                    <div class="col-10">
                                        <label>Upload Product Image (Max file size = 2MB)</label>
                                        <input type="file" class="form-control form-control-sm" id="i-img" name="i-img" value="">
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="col-10">
                                        <img id="i-preview">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div> 
                </form>
                <!-- Modal Content End-->
            </div>
        </div>
    </div>
</div>

<script>

    $(document).ready(function() {
        // $( "#form1" ).sisyphus( {
        //     locationBased: false,
        //     timeout: 5,
        //     autoRelease: true,
        //     onSave: function(){
        //         console.log("saved")
        //     }
        // });
        $("#reset").click(function(){
            $("#form2").trigger("reset");
        });
        $("#modal01").on('hidden.bs.modal', function(){
            $("#form2").trigger("reset");
        });
        $("#i-img").on("change", function(event){
            if(event.target.files.length > 0){
                var src = URL.createObjectURL(event.target.files[0])
                var preview = document.getElementById("i-preview")
                preview.src = src
                preview.style.display = "block"
                preview.style.width = "300px"
                preview.style.height = "300px"
            }
        });
    });
    // configure your validation
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
        var isvalid = $("#form2").validate({
            rules: {
                // simple rule, converted to {required:true}
                "i-itemcode": {
                    required: true,
                    minlength: 2
                },
                "i-chiname": {
                    required: true,
                    minlength: 2
                },
                "i-price": {
                    required: true,
                    number: 2
                },
                "i-category": {
                    selectValid: "null"
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
            $("#form2").submit();
        }
    });
</script>