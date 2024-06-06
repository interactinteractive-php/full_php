<form class="xs-form" id="approveLastDateForm">
    <div class="form-group row fom-row">
        <div class="row">
            <label class="col-md-4 col-form-label">Огноо:</label>
            <div class="col-md-8">
                <div class="dateElement input-group">
                    <input type="text" id="approveLastDate" name="approveLastDate" class="form-control form-control-sm dateInit">
                    <span class="input-group-btn"><button onclick="return false;" class="btn"><i class="fa fa-calendar"></i></button></span>
                </div>
            </div>
            <div class="clearfix w-100"></div>
        </div>
    </div>
</form>
<script type="text/javascript">
    $(function(){
        $( "#approveLastDate" ).focus();
    })
</script>