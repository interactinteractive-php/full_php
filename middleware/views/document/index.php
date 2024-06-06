<div class="col-md-12">
    <div class="card light shadow">	
        <div class="card-header card-header-no-padding header-elements-inline">
            <div class="card-title"><?php echo $this->title; ?></div>
            <div class="header-elements">
                <div class="list-icons">
                    <a class="list-icons-item" data-action="collapse"></a>
                    <a class="list-icons-item" data-action="fullscreen"></a>
                </div>
            </div>
        </div>
        <div class="card-body form" id="docMainRenderDiv">
            <div class="m-0 no-padding fileexplorer" id="renderMeta"></div>
            <div class="m-0 no-padding hide" id="editFormFolder"></div>
        </div>
    </div>   
</div>

<input type="hidden" id="docSystemView" value="0"/>

<script type="text/javascript">
$(window).on('load', function() {
    if (window.location.hash !== '') {
        var parsedHash = queryString.parse(location.hash);
        if (parsedHash.objectType !== undefined && parsedHash.objectId !== undefined) {
            if (parsedHash.objectType !== '' && parsedHash.objectId !== '') {
                var folderId = parsedHash.objectId;
                docChildRecordView(folderId.toString(), parsedHash.objectType, '<?php echo $this->params; ?>');
            } else {
                docListDefault('<?php echo $this->params; ?>');
            }
        } else {
            docListDefault('<?php echo $this->params; ?>');
        }
    } else {
        docListDefault('<?php echo $this->params; ?>');
    }
});     
</script>