
<div class="container-fluid">
    <div class="col-4 float-left">
        <ul class="list-group">
            <li class="list-group-item"><h4><b>Backup</b></h4></li>
            <li class="list-group-item">
                <label for="basic-url">Products File</label>
                <div class="input-group mb-2 input-group-sm">
                    
                    <a href="<?=$products_export_url?>" class="btn btn-info btn-sm" id="">Export</a>
                </div>
            </li>
            <li class="list-group-item">
                <label for="basic-url">Categories File</label>
                <div class="input-group mb-2 input-group-sm">
                    <a href="<?=$categories_export_url?>" class="btn btn-info btn-sm" id="">Export</a>
                </div>
            </li>
            <li class="list-group-item">
                <label for="basic-url">Customers File</label>
                <div class="input-group mb-2 input-group-sm">
                    <a href="<?=$customers_export_url?>" class="btn btn-info btn-sm" id="">Export</a>
                </div>
            </li>
            <li class="list-group-item">
                <label for="basic-url">Suppliers File</label>
                <div class="input-group mb-2 input-group-sm">
                    <a href="<?=$suppliers_export_url?>" class="btn btn-info btn-sm" id="">Export</a>
                </div>
            </li>
            <li class="list-group-item">
                <label for="basic-url">Payment Methods File</label>
                <div class="input-group mb-2 input-group-sm">
                    <a href="<?=$paymentmethod_export_url?>" class="btn btn-info btn-sm" id="">Export</a>
                </div>
            </li>
            <li class="list-group-item">
                <label for="basic-url">Payment Term File</label>
                <div class="input-group mb-2 input-group-sm">
                    <a href="<?=$paymentterm_export_url?>" class="btn btn-info btn-sm" id="">Export</a>
                </div>
            </li>
            <li class="list-group-item">
                <label for="basic-url">Districts File</label>
                <div class="input-group mb-2 input-group-sm">
                    <a href="<?=$districts_export_url?>" class="btn btn-info btn-sm" id="">Export</a>
                </div>
            </li>
        </ul> 
    </div>
    <div class="col-8 float-right">
        <ul class="list-group">
            <li class="list-group-item"><h4><b>Restore</b></h4></li>
            <li class="list-group-item">
                <form class="" method="POST" id="products" action="<?=$submit_to?>" enctype="multipart/form-data">
                    <label for="basic-url">Products File</label>
                    <div class="input-group mb-2 input-group-sm">
                        <input type="file" class="form-control col-6" name="i-import" id="i-products" value="">
                        <a href="#" class="btn btn-info btn-sm" id="i-import-products">Import</a>
                        <input type="hidden" name="type" value="products" />
                        <div class="input-group-prepend ">
                            <span class="input-group-text" id="err-product"></span>
                        </div>
                    </div>
                </form>
            </li>
            <li class="list-group-item">
                <form class="" method="POST" id="categories" action="<?=$submit_to?>" enctype="multipart/form-data">
                    <label for="basic-url">Categories File</label>
                    <div class="input-group mb-2 input-group-sm">
                        <input type="file" class="form-control col-6" name="i-import" id="i-categories" value="" >
                        <a href="#" class="btn btn-info btn-sm" id="i-import-categories">Import</a>
                        <div class="input-group-prepend ">
                            <span class="input-group-text" id="err-categories"></span>
                        </div>
                    </div>
                 </form>
            </li>
            <li class="list-group-item">
                <form class="" method="POST" id="customers" action="<?=$submit_to?>" enctype="multipart/form-data">
                    <label for="basic-url">Customers File</label>
                    <div class="input-group mb-2 input-group-sm">
                        <input type="file" class="form-control col-6" name="i-import" id="i-customers" value="" >
                        <a href="#" class="btn btn-info btn-sm" id="i-import-customers">Import</a>
                        <div class="input-group-prepend ">
                            <span class="input-group-text" id="err-customers"></span>
                        </div>
                    </div>
                </form>
            </li>
            <li class="list-group-item">
                <form class="" method="POST" id="suppliers" action="<?=$submit_to?>" enctype="multipart/form-data">
                    <label for="basic-url">Suppliers File</label>
                    <div class="input-group mb-2 input-group-sm">
                        <input type="file" class="form-control col-6" name="i-import" id="i-suppliers" value="" >
                        <a href="#" class="btn btn-info btn-sm" id="i-import-suppliers">Import</a>
                        <div class="input-group-prepend ">
                            <span class="input-group-text" id="err-suppliers"></span>
                        </div>
                    </div>
                </form>
            </li>
            <li class="list-group-item">
                <form class="" method="POST" id="paymentmethod" action="<?=$submit_to?>" enctype="multipart/form-data">
                    <label for="basic-url">Payment Methods File</label>
                    <div class="input-group mb-2 input-group-sm">   
                        <input type="file" class="form-control col-6" name="i-import" id="i-paymentmethod" value="" >
                        <a href="#" class="btn btn-info btn-sm" id="i-import-paymentmethod">Import</a>
                        <div class="input-group-prepend ">
                            <span class="input-group-text" id="err-paymentmethod"></span>
                        </div>
                    </div>
                </form>
            </li>
            <li class="list-group-item">
                <form class="" method="POST" id="paymentterm" action="<?=$submit_to?>" enctype="multipart/form-data">
                    <label for="basic-url">Payment Term File</label>
                    <div class="input-group mb-2 input-group-sm">
                        <input type="file" class="form-control col-6" name="i-import" id="i-paymentterm" value="" >
                        <a href="#" class="btn btn-info btn-sm" id="i-import-paymentterm">Import</a>
                        <div class="input-group-prepend ">
                            <span class="input-group-text" id="err-paymentterm"></span>
                        </div>
                    </div>
                </form>
            </li>
            <li class="list-group-item">
                <form class="" method="POST" id="districts" action="<?=$submit_to?>" enctype="multipart/form-data">
                    <label for="basic-url">Districts File</label>
                    <div class="input-group mb-2 input-group-sm">
                        <input type="file" class="form-control col-6" name="i-import" id="i-districts" value="" >
                        <a href="#" class="btn btn-info btn-sm" id="i-import-districts">Import</a>
                        <div class="input-group-prepend ">
                            <span class="input-group-text" id="err-districts"></span>
                        </div>
                    </div>
                </form>
            </li>
        </ul> 
    </div>
</div>



<script> 
    async function doFetch(files, import_url)
    {
        //const form = new FormData(document.querySelector('#'+form_name));
        const url = import_url
        const request = new Request(url, {
            method: 'POST',
            body: files
        });

        return await fetch(request)
            .then(response => response)
            .then(data => { return data.json() })
            .catch(err => { err })
    }
    function loadfile(el,form,url,err)
    {
        const inputElement = document.getElementById(el);
        inputElement.addEventListener("change", handleFiles = () => {
            const newurl = url+"/"+form
            doFetch(new FormData(document.querySelector("#"+form)),newurl)
            .then(response => {
                //console.log(response)
                $("#"+err).html(response.message)
            })
        }, false);
    }

    loadfile("i-products", "products", "<?=$checkheader_url?>","err-product")
    
    $("#i-import-products").on("click", function(){
        doFetch(new FormData(document.querySelector("#products")),"<?=$products_import_url?>")
        .then(response => {
            console.log(response)
            $("#err-product").html(response.message)
        })
    });

    loadfile("i-categories", "categories", "<?=$checkheader_url?>","err-categories")
    
    $("#i-import-categories").on("click", function(){
        doFetch(new FormData(document.querySelector("#categories")),"<?=$categories_import_url?>")
        .then(response => {
            console.log(response)
            $("#err-categories").html(response.message)
        })
    });

    loadfile("i-categories", "categories", "<?=$checkheader_url?>","err-categories")
    
    $("#i-import-categories").on("click", function(){
        doFetch(new FormData(document.querySelector("#categories")),"<?=$categories_import_url?>")
        .then(response => {
            console.log(response)
            $("#err-categories").html(response.message)
        })
    });
</script>