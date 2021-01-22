<div class="row">
    <div class="col-3">
        <div class="input-group input-group-sm mb-3 date">
            <div class="input-group-prepend">
                <span class="input-group-text" id="basic-addon1">Start Date</span>
            </div>
            <input type="text" class="form-control" id="i-start-date" name="i-start-date" value="<?=date('Y')?>-<?=date('m')?>" placeholder="" />
            <div class="input-group-append">
                <span class="input-group-text">
                <i class="far fa-calendar-alt"></i>
                </span>
            </div>
        </div>
    </div>
    <div class="col-3">
        <div class="input-group input-group-sm mb-3 date">
            <div class="input-group-prepend">
                <span class="input-group-text" id="basic-addon1">End Date</span>
            </div>
            <input type="text" class="form-control" id="i-end-date" name="i-end-date" value="<?=date('Y')?>-<?=date('m')?>" placeholder="" />
            <div class="input-group-append">
                <span class="input-group-text">
                <i class="far fa-calendar-alt"></i>
                </span>
            </div>
        </div>
    </div>
</div>


<script>
    $(document).ready(function() { 
        $('.input-group.date').datepicker({
            format: "yyyy-mm",
            viewMode: "months",
            minViewMode: "months",
            autoclose: true
        });
    });
</script>