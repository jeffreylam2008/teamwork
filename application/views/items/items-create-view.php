
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

                <form id="form1" name="form1" method="POST" action="<?=$save_url?>">
                    <div class="card-body">
                        <div class="form-row">
                            <div class="col-3">
                                <label for="">Item Code</label>
                                <input type="text" class="form-control form-control-sm" name="i-itemcode" placeholder="For itemcode" value="">
                            </div>
                        </div>

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
                                <label>Categories</label>                
                                <a class='btn btn-outline-primary btn-sm' href='<?=$categories_baseurl?>' type='button'>New</a>
                                <select class="form-control" name="i-category">
                                <option value="null">-- Select --</option>
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
                                <input type="text" class="form-control form-control-sm" name="i-unit" placeholder="i.e. pack, 4x3L etc" value="">
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

    $(function() {
        $( "#form1" ).sisyphus( {
            locationBased: false,
            timeout: 5,
            autoRelease: true,
            onSave: function(){
                console.log("saved")
            }
        });
        $("#reset").click(function(){
            $("#form1").trigger("reset");
        });
        $("#modal01").on('hidden.bs.modal', function(){
            $("#form1").trigger("reset");
        });
    });
    // configure your validation
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
                "i-engname": {
                    required: true
                },
                "i-price" : {
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
        
    });
</script>