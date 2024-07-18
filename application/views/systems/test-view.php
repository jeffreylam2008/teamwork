<form class="" method="POST" id="products" action="<?=base_url('import/products')?>" enctype="multipart/form-data">
    <label for="basic-url">Products File</label>
    <div class="input-group mb-2 input-group-sm">
        <input type="file" class="form-control col-6" name="i-import" id="i-products" value="">
        <input type="submit" class="btn btn-info btn-sm" id="i-import-products" value="import">
        <input type="hidden" name="type" value="products" />
        <div class="input-group-prepend ">
            <span class="input-group-text" id="err-product"></span>
        </div>
    </div>
</form>

<script> 
    let error = false;
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
    // load file function: to check file header
    function loadfile(el,form,url,err)
    {
        let response = {error: false};
        const inputElement = document.getElementById(el);
        inputElement.addEventListener("change", handleFiles = () => {
            const newurl = url+"/"+form
            doFetch(new FormData(document.querySelector("#"+form)),newurl)
            .then(response => {
                $("#"+err).html(response.message)
                console.log(response);
                if(response.error == true)
                    error = true
                else
                    error = false
            })
        }, false);
        

    }
    // load products file

    loadfile("i-products", "products", "<?=$checkheader_url?>","err-product")
    console.log(error)
    $("#i-import-products").on("click", function(){
        console.log(error)
        if(!error)
        {
            doFetch(new FormData(document.querySelector("#products")),"<?=$products_import_url?>")
            .then(response => {
                console.log(response);
                $("#err-product").html(response.message)
            })
        }
    });
            

    // loadfile("i-categories", "categories", "<?=$checkheader_url?>","err-categories")
    
    // $("#i-import-categories").on("click", function(){
    //     doFetch(new FormData(document.querySelector("#categories")),"<?=$categories_import_url?>")
    //     .then(response => {
    //         console.log(response)
    //         $("#err-categories").html(response.message)
    //     })
    // });

    // loadfile("i-categories", "categories", "<?=$checkheader_url?>","err-categories")
    
    // $("#i-import-categories").on("click", function(){
    //     doFetch(new FormData(document.querySelector("#categories")),"<?=$categories_import_url?>")
    //     .then(response => {
    //         console.log(response)
    //         $("#err-categories").html(response.message)
    //     })
    // });
</script>