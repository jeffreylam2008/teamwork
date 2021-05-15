<form class="" method="POST" id="products-form" action="<?=base_url('import/products')?>" enctype="multipart/form-data">
    <label for="basic-url">Products File</label>
    <div class="input-group mb-2 input-group-sm">
        <input type="file" class="form-control col-6" name="i-import" value="">
        <input type="submit" class="btn btn-info btn-sm" id="i-import-products" value="Import" />
        <div class="input-group-prepend ">
            <span class="input-group-text" id="err-product"></span>
        </div>
    </div>
</form>