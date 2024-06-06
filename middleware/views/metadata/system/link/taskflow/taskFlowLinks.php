<div class="panel panel-default bg-inverse">
    <table class="table sheetTable">
        <tbody>
            <tr>
                <td style="height: 32px; width: 170px" class="left-padding"><?php echo $this->lang->line('META_00046'); ?></td>
                <td>
                    <input id="inputMetaDataId" name="inputMetaDataId" type="hidden">
                    <?php echo Form::button(array('class' => 'btn btn-sm purple-plum', 'value' => '...', 'onclick' => 'setParamAttributesNew(this);')); ?>
                    <div id="dialog-paramattributes-new" style="display: none"></div>
                </td>
            </tr>
            <tr>
                <td style="height: 32px;" class="left-padding"><?php echo $this->lang->line('META_00104'); ?></td>
                <td>
                    <input id="outputMetaDataId" name="outputMetaDataId" type="hidden">
                    <?php echo Form::button(array('class' => 'btn btn-sm purple-plum', 'value' => '...', 'onclick' => 'setOutputParamAttributesNew(this);')); ?>
                    <div id="dialog-outputparamattributes-new" style="display: none"></div>
                </td>
            </tr>
        </tbody>
    </table>
</div>

<script type="text/javascript">
function setParamAttributesNew(elem) {

    Core.blockUI({
        message: 'Loading...', 
        boxed: true
    });

    var $dialogName = 'dialog-paramattributes-new';
    
    if ($("form#addMetaSystemForm").length > 0) {
        var appendToForm = 'form#addMetaSystemForm';
    } else {
        var appendToForm = 'form#editMetaSystemForm';
    }

    if ($("#" + $dialogName).children().length > 0) {

        var $dialogContainer = $("#" + $dialogName);
        var $detachedChildren = $dialogContainer.children().detach();

        $dialogContainer.dialog({
            appendTo: appendToForm,
            cache: false,
            resizable: true,
            bgiframe: true,
            autoOpen: false,
            title: '<?php echo $this->lang->line('META_00046'); ?>',
            width: 1200,
            minWidth: 1200,
            height: "auto",
            modal: false,
            open: function(){
                $detachedChildren.appendTo($dialogContainer);
                Core.unblockUI();
            }, 
            buttons: [
                {text: plang.get('save_btn'), class: 'btn btn-sm green bp-btn-subsave', click: function () {
                    $("#" + $dialogName).dialog('close');
                }},
                {text: plang.get('close_btn'), class: 'btn btn-sm blue-hoki', click: function () {
                    $("#" + $dialogName).dialog('close');
                }}
            ]
        }).dialogExtend({
            "closable": true,
            "maximizable": true,
            "minimizable": true,
            "collapsable": true,
            "dblclick": "maximize",
            "minimizeLocation": "left",
            "icons": {
                "close": "ui-icon-circle-close",
                "maximize": "ui-icon-extlink",
                "minimize": "ui-icon-minus",
                "collapse": "ui-icon-triangle-1-s",
                "restore": "ui-icon-newwin"
            }, 
            "maximize": function() { 
                var dialogHeight = $dialogContainer.height();
                $dialogContainer.find("div#fz-process-params-option").css({"height": (dialogHeight - 41)+'px'});
                $dialogContainer.find("div.params-addon-config").css({"height": (dialogHeight - 41)+'px'});
            }
        });
        $dialogContainer.dialog('open');
        $dialogContainer.dialogExtend('maximize');

    } else {

        $.ajax({
            type: 'post',
            url: 'mdmetadata/setParamAttributesEditModeNew',
            data: {metaDataId: ''},
            dataType: 'json',
            beforeSend: function () {
                if (!$("link[href='assets/custom/addon/plugins/codemirror/lib/codemirror.v1.css']").length){
                    $("head").append('<link rel="stylesheet" type="text/css" href="assets/custom/addon/plugins/codemirror/lib/codemirror.v1.css"/>');
                    $.cachedScript('assets/custom/addon/plugins/codemirror/lib/codemirror.min.js');
                }
                
                if (!$("link[href='assets/custom/addon/plugins/bootstrap-iconpicker/css/bootstrap-iconpicker.min.css']").length) {
                    $("head").append('<link rel="stylesheet" type="text/css" href="assets/custom/addon/plugins/bootstrap-iconpicker/css/bootstrap-iconpicker.min.css"/>');
                    $.cachedScript("assets/custom/addon/plugins/bootstrap-iconpicker/js/bootstrap-iconpicker.min.js?v=1");
                }
            },
            success: function (data) {
                
                var $dialogContainer = $("#" + $dialogName);
                
                $dialogContainer.empty().append(data.Html);

                var $detachedChildren = $dialogContainer.children().detach();

                $dialogContainer.dialog({
                    appendTo: appendToForm,
                    cache: false,
                    resizable: true,
                    bgiframe: true,
                    autoOpen: false,
                    title: data.Title,
                    width: 1200,
                    minWidth: 1200,
                    height: 'auto',
                    modal: false,
                    open: function(){
                        $detachedChildren.appendTo($dialogContainer);
                        Core.unblockUI();
                    }, 
                    buttons: [
                        {text: data.save_btn, class: 'btn btn-sm green bp-btn-subsave', click: function () {
                            $dialogContainer.dialog('close');
                        }},
                        {text: data.close_btn, class: 'btn btn-sm blue-hoki', click: function () {
                            $dialogContainer.dialog('close');
                        }}
                    ]
                }).dialogExtend({
                    "closable": true,
                    "maximizable": true,
                    "minimizable": true,
                    "collapsable": true,
                    "dblclick": "maximize",
                    "minimizeLocation": "left",
                    "icons": {
                        "close": "ui-icon-circle-close",
                        "maximize": "ui-icon-extlink",
                        "minimize": "ui-icon-minus",
                        "collapse": "ui-icon-triangle-1-s",
                        "restore": "ui-icon-newwin"
                    }, 
                    "maximize": function() { 
                        var dialogHeight = $dialogContainer.height();
                        $dialogContainer.find("div#fz-process-params-option").css({"height": (dialogHeight - 41)+'px'});
                        $dialogContainer.find("div.params-addon-config").css({"height": (dialogHeight - 41)+'px'});
                    }
                });
                $dialogContainer.dialog('open');
                $dialogContainer.dialogExtend('maximize');
            }

        }).done(function () {
            Core.initNumber($("#" + $dialogName));
        });
    }
}
function setOutputParamAttributesNew(elem) {

    Core.blockUI({
        message: 'Loading...', 
        boxed: true
    });

    var $dialogName = 'dialog-outputparamattributes-new';
    
    if ($("form#addMetaSystemForm").length > 0) {
        var appendToForm = 'form#addMetaSystemForm';
    } else {
        var appendToForm = 'form#editMetaSystemForm';
    }

    if ($("#" + $dialogName).children().length > 0) {

        var $dialogContainer = $("#" + $dialogName);
        var $detachedChildren = $dialogContainer.children().detach();

        $dialogContainer.dialog({
            appendTo: appendToForm,
            cache: false,
            resizable: true,
            bgiframe: true,
            autoOpen: false,
            title: '<?php echo $this->lang->line('META_00104'); ?>',
            width: 1200,
            minWidth: 1200,
            height: 'auto',
            modal: false,
            open: function(){
                $detachedChildren.appendTo($dialogContainer);
                Core.unblockUI();
            }, 
            buttons: [
                {text: plang.get('save_btn'), class: 'btn btn-sm green bp-btn-subsave', click: function () {
                    $dialogContainer.dialog('close');
                }},
                {text: plang.get('close_btn'), class: 'btn btn-sm blue-hoki', click: function () {
                    $dialogContainer.dialog('close');
                }}
            ]
        }).dialogExtend({
            "closable": true,
            "maximizable": true,
            "minimizable": true,
            "collapsable": true,
            "dblclick": "maximize",
            "minimizeLocation": "left",
            "icons": {
                "close": "ui-icon-circle-close",
                "maximize": "ui-icon-extlink",
                "minimize": "ui-icon-minus",
                "collapse": "ui-icon-triangle-1-s",
                "restore": "ui-icon-newwin"
            }, 
            "maximize" : function() { 
                var dialogHeight = $dialogContainer.height();
                $dialogContainer.find('div#fz-process-output-params-option').css({'height': (dialogHeight - 41)+'px'});
                $dialogContainer.find('div.params-addon-config').css({'height': (dialogHeight - 41)+'px'});
            }
        });
        $dialogContainer.dialog('open');
        $dialogContainer.dialogExtend('maximize');

    } else {

        $.ajax({
            type: 'post',
            url: 'mdmetadata/setOutputParamAttributesEditModeNew',
            data: {metaDataId: ''},
            dataType: 'json',
            success: function (data) {

                $("#" + $dialogName).empty().append(data.Html);

                var $dialogContainer = $("#" + $dialogName);
                var $detachedChildren = $dialogContainer.children().detach();

                $dialogContainer.dialog({
                    appendTo: appendToForm,
                    cache: false,
                    resizable: true,
                    bgiframe: true,
                    autoOpen: false,
                    title: data.Title,
                    width: 1200,
                    minWidth: 1200,
                    height: "auto",
                    modal: false,
                    open: function(){
                        $detachedChildren.appendTo($dialogContainer);
                        Core.unblockUI();
                    }, 
                    buttons: [
                        {text: data.save_btn, class: 'btn btn-sm green bp-btn-subsave', click: function () {
                            $dialogContainer.dialog('close');
                        }},
                        {text: data.close_btn, class: 'btn btn-sm blue-hoki', click: function () {
                            $dialogContainer.dialog('close');
                        }}
                    ]
                }).dialogExtend({
                    "closable": true,
                    "maximizable": true,
                    "minimizable": true,
                    "collapsable": true,
                    "dblclick": "maximize",
                    "minimizeLocation": "left",
                    "icons": {
                        "close": "ui-icon-circle-close",
                        "maximize": "ui-icon-extlink",
                        "minimize": "ui-icon-minus",
                        "collapse": "ui-icon-triangle-1-s",
                        "restore": "ui-icon-newwin"
                    }, 
                    "maximize" : function() { 
                        var dialogHeight = $dialogContainer.height();
                        $dialogContainer.find('div#fz-process-output-params-option').css({'height': (dialogHeight - 41)+'px'});
                        $dialogContainer.find('div.params-addon-config').css({'height': (dialogHeight - 41)+'px'});
                    }
                });
                $dialogContainer.dialog('open');
                $dialogContainer.dialogExtend('maximize');
            },
            error: function () {
                alert("Error");
            }
        });
    }
}
</script>