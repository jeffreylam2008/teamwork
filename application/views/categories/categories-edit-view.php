<?php
    extract($data["query"]);
?>


<div class="card">
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