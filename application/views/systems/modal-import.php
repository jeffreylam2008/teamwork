<form class="" method="POST" id="this-form" action="" enctype="multipart/form-data">     
    <div class="modal show" id="_modal" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-dialog-centered modal-md" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id=""><?=$title?></h5>
                </div>
                <div class="modal-body">
                <!-- content -->
                    <div class="container-fluid">
                        <?=$extra_txt?>
                        <input type="file" class="form-control" name="i-import" id="i-import" value="" >          
                        <span class="input-group-text" id="err"></span>
                    </div>
                <!-- content end -->

                    <div class="container-fluid">
                      
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" id="canceled" data-dismiss="modal"><?=$this->lang->line("function_cancel")?></button>
                    <button type="button" class="btn btn-danger" id="confirmed" ><i class="fas fa-spinner fa-spin" id="spin"></i> <?=$this->lang->line("function_yes")?></button>
                </div>
            </div>
        </div>
    </div>
</form>

<script>
let data = [];
let error = false;
// API call
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
// sendFile to API
function sendFile(data,url,err)
{  
    $("#spin").show()
    doFetch(data, url)
    .then(response => {
        $("#"+err).html(response.error.message)
console.log(response);
        $("#spin").hide()
        $("#confirmed").hide()
    })
}
// loadFile to check header 
function loadFile(el,form,url,type,err)
{
    let response = {error: false};
    const inputElement = document.getElementById(el);
    inputElement.addEventListener("change", handleFiles = () => {
        $("#spin").show()
        $("#confirmed").prop('disabled', true)
        const newurl = url+"/"+type
        doFetch(new FormData(document.querySelector("#"+form)),newurl)
        .then(response => {
            $("#"+err).html(response.message)
console.log(response);
            // has error
            if(response.error == true){
                error = true
                $("#confirmed").prop('disabled', true)
                $("#spin").hide()
            }
            // has no error
            else{
                error = false
                data = response.query
                $("#confirmed").prop('disabled', false)
                $("#spin").hide()
            }
        })
    }, false);
}

$( document ).ready(function() {
    $("#spin").hide()
    $("#confirmed").prop('disabled', true) 
    $("#canceled").on("click",function(){
        $(this).modal("hide")
        $(this).unbind()
        window.location.href = "<?=$return_url?>";
    });
    $('#_modal').modal({
        backdrop: 'static'
    });
    // invoke loadfile
    loadFile("i-import", "this-form", "<?=$check_url?>","<?=$check_type?>","err")
    $("#confirmed").on("click", function() {
        sendFile(JSON.stringify(data),"<?=$submit_to?>","err")
    });
});

</script>