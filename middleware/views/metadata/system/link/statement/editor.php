<div class="row columns">
    <div class="col-md-9">  
        <div class="editor">
            <textarea name="tempEditor" id="tempEditor"><?php echo $this->htmlContent; ?></textarea>
        </div>
    </div>
    <div class="col-md-3">  
        <div class="scroller report-tags" style="max-height: 450px;" data-always-visible="1" data-rail-visible="0" data-handle-color="#dae3e7">
            <?php
            if ($this->reportType == 'dataview') {
                ?>
                <p class="meta-title">Dataview</p>
                <ul id="metas">
                    <?php
                    (Array) $filterArray = array();
                    foreach ($this->metaList as $value) {
                        ?>
                        <li><div class="metaData h-card" data-metaData="<?php echo strtolower($value['FIELD_PATH']); ?>" draggable="true" title="<?php echo strtolower($value['META_DATA_NAME']); ?>"><?php echo strtolower($value['META_DATA_CODE']); ?></div></li> 
                        <?php
                        if ($value['IS_CRITERIA'] == 1) {
                            array_push($filterArray, $value);
                        }
                    }
                    ?>
                </ul>
                <hr/> 
                <?php
                if (!empty($filterArray)) {
                    ?>
                    <p class="filter-title"><?php echo $this->lang->line('META_00193'); ?></p>
                    <ul id="filters">
                        <?php
                        foreach ($filterArray as $filterValue) {
                            ?>
                            <li><div class="filter h-card" data-filter="<?php echo strtolower($filterValue['FIELD_PATH']); ?>" draggable="true" title="<?php echo strtolower($filterValue['META_DATA_NAME']); ?>"><?php echo strtolower($filterValue['META_DATA_CODE']); ?></div></li> 
                            <?php
                        }
                        ?>
                    </ul>
                    <hr/> 
                    <?php
                }
            }
            ?>
            <p class="consts-title">Тогтмолууд</p>
            <ul id="constants">
                <li><div class="const h-card" data-constant="sysdatetime" draggable="true">sysdatetime</div></li> 
                <li><div class="const h-card" data-constant="sysdate" draggable="true">sysdate</div></li> 
                <li><div class="const h-card" data-constant="systime" draggable="true">sysyear</div></li>
                <li><div class="const h-card" data-constant="systime" draggable="true">sysmonth</div></li>
                <li><div class="const h-card" data-constant="systime" draggable="true">sysday</div></li>
                <li><div class="const h-card" data-constant="systime" draggable="true">systime</div></li> 
                <li><div class="const h-card" data-constant="sessionPersonName" draggable="true">sessionPersonName</div></li>
                <li><div class="const h-card" data-constant="sessionUserName" draggable="true">sessionUserName</div></li>
                <li><div class="const h-card" data-constant="sessionPosition" draggable="true">sessionPosition</div></li>
                <li><div class="const h-card" data-constant="sessionEmployeeId" draggable="true">sessionEmployeeId</div></li>
            </ul>
            <hr/>
            <p class="consts-title">Тохиргооны утгууд</p>
            <ul id="configvalues">
                <li><div class="configvalue h-card" data-configvalue="config_OrganizationName" draggable="true">config_OrganizationName</div></li>
                <li><div class="configvalue h-card" data-configvalue="config_OrganizationPhone" draggable="true">config_OrganizationPhone</div></li>
                <li><div class="configvalue h-card" data-configvalue="config_OrganizationAddress" draggable="true">config_OrganizationAddress</div></li>
                <li><div class="configvalue h-card" data-configvalue="config_OrganizationFax" draggable="true">config_OrganizationFax</div></li>
            </ul>
        </div>
    </div>
</div>
<script type="text/javascript" src="assets/custom/addon/plugins/ckeditor/4.5.4/ckeditor.js"></script>
<script type="text/javascript">
    var statementEditor = CKEDITOR.replace('tempEditor', {
        width: 'auto',
        height: '500px',
        extraPlugins: 'hcard,layoutmanager,templates,stylesheetparser,pastefromword'
    });
    CKEDITOR.config.contentsCss = URL_APP + 'assets/custom/addon/plugins/ckeditor/4.5.4/css/myEditorStyle.css';
    CKEDITOR.config.pasteFromWordRemoveStyles = false;
    CKEDITOR.config.pasteFromWordPromptCleanup = true;
    CKEDITOR.disableAutoInline = true;
    
    if($('#metas').length > 0){
        CKEDITOR.document.getById('metas').on('dragstart', function(evt) {
            var target = evt.data.getTarget().getAscendant('div', true);
            CKEDITOR.plugins.clipboard.initDragDataTransfer(evt);
            var dataTransfer = evt.data.dataTransfer;
            var fieldpath = $(evt.data.$.target).attr('data-metadata');
            dataTransfer.setData('metadata', fieldpath);
            dataTransfer.setData('text/html', target.getText());
        });
    }
    if($('#filters').length > 0){
        CKEDITOR.document.getById('filters').on('dragstart', function(evt) {
            var target = evt.data.getTarget().getAscendant('div', true);
            CKEDITOR.plugins.clipboard.initDragDataTransfer(evt);
            var dataTransfer = evt.data.dataTransfer;
            var fieldpath = $(evt.data.$.target).attr('data-filter');
            dataTransfer.setData('filter', fieldpath);
            dataTransfer.setData('text/html', target.getText());
        });
    }
    CKEDITOR.document.getById('constants').on('dragstart', function(evt) {
        var target = evt.data.getTarget().getAscendant('div', true);
        CKEDITOR.plugins.clipboard.initDragDataTransfer(evt);
        var dataTransfer = evt.data.dataTransfer;
        var consts = $(evt.data.$.target).attr('data-constant');
        dataTransfer.setData('const', consts);
        dataTransfer.setData('text/html', target.getText());
    });
    CKEDITOR.document.getById('configvalues').on('dragstart', function(evt) {
        var target = evt.data.getTarget().getAscendant('div', true);
        CKEDITOR.plugins.clipboard.initDragDataTransfer(evt);
        var dataTransfer = evt.data.dataTransfer;
        var configvalues = $(evt.data.$.target).attr('data-configvalue');
        dataTransfer.setData('configvalue', configvalues);
        dataTransfer.setData('text/html', target.getText());
    });
    statementEditor.on('contentDom', function() {
        statementEditor.editable().attachListener(this.document, 'click', function(event) {
            if (event.data.$.target.closest("span") != null || event.data.$.target.closest("span") != undefined) {
                var clickedElement = $(event.data.$.target).prop("tagName");
                if (clickedElement !== 'SPAN') {
                    var el = event.data.$.target.closest("span");
                    var elementWithHandler = $(el).parent();
                    $(el).remove();
                    $(elementWithHandler).remove();
                }
            }
        });
    });
    $(document).on('focusin', function(e) {
        e.stopImmediatePropagation();
    });
    
    $(function() {
        var dialogId = $("#dialog-editTemplateEditor");
        dialogId.bind("dialogextendmaximize", function(){
            var dialogHeight = dialogId.height();
            dialogId.find("div.report-tags").css("height", (dialogHeight - 20)+'px');
            dialogId.find("div.report-tags").css("max-height", (dialogHeight - 20)+'px');
            dialogId.find("div.cke_contents").css("height", (dialogHeight - 140)+'px');
        });
        dialogId.bind("dialogextendrestore", function(){
            var dialogHeight = dialogId.height();
            dialogId.find("div.report-tags").css("height", (dialogHeight - 20)+'px');
            dialogId.find("div.report-tags").css("max-height", (dialogHeight - 20)+'px');
            dialogId.find("div.cke_contents").css("height", (dialogHeight - 140)+'px');
        });
    });
</script>