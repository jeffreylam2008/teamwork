<?php
    extract($data["query"]);
?>


<div class="card">
  <div class="card-header">
    <h2> Item Code: <u><?=$item_code?></u></h2>
  </div>
  <div class="card-body">
    
    <div class="form-row">
        <div class="col-3">
            <label for="">Chinese Name</label>
            <input type="text" class="form-control form-control-sm" name="i-chiname" placeholder="Item Chinese Name" value="<?=$chi_name?>">
        </div>
        <div class="col-3">
            <label for="">English Name</label>
            <input type="text" class="form-control form-control-sm" name="i-engname" placeholder="Item English Name" value="<?=$eng_name?>">
        </div>
    </div>

    <div class="form-row">
        <div class="col-6">
            <label for="t1">Description</label>
            <textarea class="form-control form-control-sm" id="t1" rows="2">
                <?=$desc?>
            </textarea>
        </div>
    </div>

    <div class="form-row">
        <div class="col-2">
            <label>Price</label>
            <div class="input-group input-group-sm">
                    <div class="input-group-prepend">
                        <span class="input-group-text">$</span>
                    </div>
                    <input type="text" class="form-control" aria-label="Amount (to the nearest dollar)" name="i-price" value="<?=$price?>">
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
                    <input type="text" class="form-control" aria-label="Amount (to the nearest dollar)" name="i-specialprice" value="<?=$price_special?>">
            </div>
        </div>
    </div>


    <div class="form-row">
        <div class="col-6">
            <label>Items Type</label> <a href='<?=$categories_baseurl?>'>New</a>
            <select class="form-control">
            <?php if(array_key_exists($cate_code, $cate_code)):?>
                <option value="<?=$cata_code?>"><?=$categories[$cata_code]?></option>
            <?php endif;?>
                <?php 
                        foreach($categories as $k => $v):
                ?>
                            <?="<option value='".$k."'>".$v."</option>"?>
                <?
                        endforeach;
                ?>
            </select>
        </div>
    </div>
    <div class="form-row">
        <div class="col-6">
            <label>Unit</label>
            <input type="text" class="form-control form-control-sm" name="i-chiname" placeholder="i.e. pack, 4x3L etc" value="<?=$unit?>">
        </div>
    </div>
</div>

<script>

</script>