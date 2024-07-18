
<div class="container-fluid">
    <!-- Export Session -->      
    <div class="col-6 float-left">
        <ul class="list-group">
            <li class="list-group-item"><h4><b><?=$this->lang->line("common_data").$this->lang->line("export")?></b></h4></li>
            <li class="list-group-item">
                <label for="basic-url"><?=$this->lang->line("export_products")?></label>
                <div class="input-group mb-2 input-group-sm">
                    
                    <a href="<?=$products_export_url?>" class="btn btn-info btn-sm" id=""><?=$this->lang->line("export")?></a>
                </div>
            </li>
            <li class="list-group-item">
                <label for="basic-url"><?=$this->lang->line("export_categories")?></label>
                <div class="input-group mb-2 input-group-sm">
                    <a href="<?=$categories_export_url?>" class="btn btn-info btn-sm" id=""><?=$this->lang->line("export")?></a>
                </div>
            </li>
            <li class="list-group-item">
                <label for="basic-url"><?=$this->lang->line("export_customers")?></label>
                <div class="input-group mb-2 input-group-sm">
                    <a href="<?=$customers_export_url?>" class="btn btn-info btn-sm" id=""><?=$this->lang->line("export")?></a>
                </div>
            </li>
            <li class="list-group-item">
                <label for="basic-url"><?=$this->lang->line("export_suppilers")?></label>
                <div class="input-group mb-2 input-group-sm">
                    <a href="<?=$suppliers_export_url?>" class="btn btn-info btn-sm" id=""><?=$this->lang->line("export")?></a>
                </div>
            </li>
            <li class="list-group-item">
                <label for="basic-url"><?=$this->lang->line("export_paymentmethods")?></label>
                <div class="input-group mb-2 input-group-sm">
                    <a href="<?=$paymentmethod_export_url?>" class="btn btn-info btn-sm" id=""><?=$this->lang->line("export")?></a>
                </div>
            </li>
            <li class="list-group-item">
                <label for="basic-url"><?=$this->lang->line("export_paymentterm")?></label>
                <div class="input-group mb-2 input-group-sm">
                    <a href="<?=$paymentterm_export_url?>" class="btn btn-info btn-sm" id=""><?=$this->lang->line("export")?></a>
                </div>
            </li>
            <li class="list-group-item">
                <label for="basic-url"><?=$this->lang->line("export_Districts")?></label>
                <div class="input-group mb-2 input-group-sm">
                    <a href="<?=$districts_export_url?>" class="btn btn-info btn-sm" id=""><?=$this->lang->line("export")?></a>
                </div>
            </li>
        </ul> 
    </div>

    <!-- Import Session -->
    <div class="col-6 float-right">
        <ul class="list-group">
            <li class="list-group-item"><h4><b><?=$this->lang->line("common_data").$this->lang->line("import")?></b></h4></li>
            <li class="list-group-item">
                <label for="basic-url"><?=$this->lang->line("export_products")?></label>
                <div class="input-group mb-2 input-group-sm">
                    <a href="<?=$products_import_url?>" class="btn btn-info btn-sm" id="i-import-products"><?=$this->lang->line("import")?></a>
                </div>
            </li>
            <li class="list-group-item">
                <label for="basic-url"><?=$this->lang->line("export_categories")?></label>
                <div class="input-group mb-2 input-group-sm">
                    <a href="<?=$categories_import_url?>" class="btn btn-info btn-sm" id="i-import-categories"><?=$this->lang->line("import")?></a>
                </div>
            </li>
            <li class="list-group-item">
                <label for="basic-url"><?=$this->lang->line("export_customers")?></label>
                <div class="input-group mb-2 input-group-sm">
                    <a href="#" class="btn btn-info btn-sm" id="i-import-customers"><?=$this->lang->line("import")?></a> 
                </div>
            </li>
            <li class="list-group-item">
                <label for="basic-url"><?=$this->lang->line("export_suppilers")?></label>
                <div class="input-group mb-2 input-group-sm">
                    <a href="#" class="btn btn-info btn-sm" id="i-import-suppliers"><?=$this->lang->line("import")?></a>
                </div>
            </li>
            <li class="list-group-item">
                <label for="basic-url"><?=$this->lang->line("export_paymentmethods")?></label>
                <div class="input-group mb-2 input-group-sm">   
                    <a href="#" class="btn btn-info btn-sm" id="i-import-paymentmethod"><?=$this->lang->line("import")?></a>
                </div>
            </li>
            <li class="list-group-item">
                <label for="basic-url"><?=$this->lang->line("export_paymentterm")?></label>
                <div class="input-group mb-2 input-group-sm">
                    <a href="#" class="btn btn-info btn-sm" id="i-import-paymentterm"><?=$this->lang->line("import")?></a>
                </div>
            </li>
            <li class="list-group-item">
                <label for="basic-url"><?=$this->lang->line("export_Districts")?></label>
                <div class="input-group mb-2 input-group-sm">
                    <a href="#" class="btn btn-info btn-sm" id="i-import-districts"><?=$this->lang->line("import")?></a>
                </div>
            </li>
        </ul> 
    </div>
</div>
