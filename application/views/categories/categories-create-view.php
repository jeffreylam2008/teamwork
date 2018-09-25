<form id="form1" action="" name="form1" method="post">
    <div class="card">
        <div class="card-body"> 
            <div class="form-row">
                <div class="col-3">
                    <label for="">Category Code</label>
                    <input type="text" class="form-control form-control-sm" name="i-catecode" placeholder="Categorg Code" value="">
                </div>
                <div class="col-3">
                    <label for="">Description</label>
                    <input type="text" class="form-control form-control-sm" name="i-decs" placeholder="Description" value="">
                </div>
            </div>

        </div>
    </div>

</form>
<script>
    $( function() {
        $( "#form1" ).sisyphus( {
            locationBased: false,
            timeout: 5,
            autoRelease: true,
            onSave: function(){
                console.log("saved")
            }
        } );
    } );

</script>