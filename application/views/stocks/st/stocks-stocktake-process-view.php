<div>
    <?php 
       
        $data = json_decode($_POST["i-post"],true);
        // echo "<pre>";
        // var_dump($data);
        // echo "</pre>";
        extract($data);

    ?>

</div>

<div class="container-fluid">

    <div class="input-group mb-2 input-group-sm">
        <div class="input-group-prepend">
            <span class="input-group-text" id=""><?=$this->lang->line("stock_tran_number")?></span>
        </div>            
        <input type="text" class="form-control" id="i-st-num" value="<?=$trans_code?>" disabled>
    </div>
    <div class="input-group mb-2 input-group-sm">
        <div class="input-group-prepend">
            <span class="input-group-text" id=""><?=$this->lang->line("date")?></span>
        </div>
        <input type="text" class="form-control" id="i-date" value="<?=$date?>" disabled>
    </div>
    <table class="table table-sm table-striped" id="items-table">
        <thead>
            <tr>
                <th scope="col">#</th>
                <th><?=$this->lang->line("item_code")?></th>
                <th><?=$this->lang->line("item_eng_name")?></th>
                <th><?=$this->lang->line("item_chi_name")?></th>
                <th><?=$this->lang->line("item_qty")?></th>
                <th><?=$this->lang->line("item_unit")?></th>
            </tr>
        </thead>
        <!-- render items-list here -->
        <tbody id="render-items">
        <?php 
            $index = 1;
            foreach($items as $k => $v):
                extract($v);
        ?>
            <tr>
                <td class="col-1"><?=$index?></th>
                <td class="col-1"><?=$item_code?></td>
                <td class="col-2"><?=$eng_name?></td>
                <td class="col-2"><?=$chi_name?></td>
                <td class="col-2"><?=$qty?></td>
                <td class="col-1"><?=$unit?></td>
            </tr>
        <?php
            $index++; 
            endforeach;
        ?>
        </tbody>
    </table>
    
    <div class="input-group mb-2 input-group-sm">
         <textarea  class="form-control" rows="3" id="i-remark" placeholder="Remark"><?=$remark?></textarea>
    </div>
</div>

<script>
    // unload and redirect
    function doUnLoad(){
            // console.log(document.activeElement.href);
            // while unload then redirect
        $(window).on('unload', function(e){
            e.preventDefault();
            // to fix page refresh
            if(typeof document.activeElement.href !== "undefined")
            {
                // target url defined then discard current session
                fetch("<?=$discard_url?>").then(function(response) {
                    if(response.ok){
                        window.location.replace(document.activeElement.href);
                        window.onbeforeunload = null;
                    }
                });
            }
        });
    }
    // unload window
    $(window).on('beforeunload', function(){
        doUnLoad();
        return "Any changes will be lost";
    });
    // the button to free page unload
    $("#save, #preview, #reprint").on("click", function(){
        $(window).off('beforeunload');
    });
    // the button to free page unload
    $("#back, #discard").on("click", function(){
        doUnLoad();
        $(window).off('beforeunload');
    });
    $("#preview").on("click",function(){
        window.open('<?=$preview_url?>', '_blank', 'location=yes,height=900,width=800,scrollbars=yes,status=yes');
    })
    // Print receipt pop up window
    $("#reprint").on("click",function(){
    window.open('<?=$print_url?>', '_blank', 'location=yes,height=500,width=900,scrollbars=yes,status=yes');
    })
    // $("#save").on("click",function(){
    //     window.open('<?=$print_url?>', '_blank', 'location=yes,height=900,width=800,scrollbars=yes,status=yes');
    // })

</script>