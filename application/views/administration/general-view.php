<div class="container-fluid">
    <div class="row">

        <div class="col-sm-4">
            <form class="" method="POST" id="this-form" action="<?=$submit_to?>">
                <div class="input-group mb-2 input-group-sm">
                    <div class="input-group-prepend">
                        <label class="input-group-text"><?=$this->lang->line("config_debug_mode")?></label>
                    </div>

                    <select class="custom-select custom-select-sm" id="i-debug-mode">
                        <option value="<?=$debug_mode == true? "true" : "False";?>"><?=$debug_mode == true? "True" : "False";?></option>
                        <option value="true">True</option>
                        <option value="false">False</option>
                    </select>

                </div>
                
                <div class="input-group mb-2 input-group-sm">
                    <div class="input-group-prepend">
                        <span class="input-group-text" id=""><?=$this->lang->line("config_default_per_page")?></span>
                    </div>
                    <input type="text" class="form-control" id="i-per-page" value="<?=$default_per_page?>">
                </div>
                <input type="hidden" name="i-post" id="i-post" value="" />
            </form>
        </div>
    </div>
</div>

<script>
$("#save").on("click",function(){
    var _inputs = {};
    _inputs["perpage"] = $("#i-per-page").val()
    _inputs["debugmode"] = $("#i-debug-mode").val()


    $("#i-post").val(JSON.stringify(_inputs))
    
    //console.log(_inputs)
    $("#this-form").submit();
    
});
</script>