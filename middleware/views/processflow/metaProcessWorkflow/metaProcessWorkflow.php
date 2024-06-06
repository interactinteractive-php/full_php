<?php
if (!$this->isAjax) {
?>
<div class="col-md-12">
    <div class="card p-3">
        <div class="card-header header-elements-inline">
            <div class="caption buttons d-flex flex-row align-items-center">
                <?php
                echo html_tag('a', array(
                        'href' => 'javascript:history.back();',
                        'class' => 'btn btn-sm btn-outline bg-primary border-primary text-primary-800 btn-icon mr-2'
                    ), '<i class="icon-arrow-left7"></i>', true
                );
                ?>
                <h5 class="mb-0"><?php echo $this->title; ?> <?php echo $this->lang->line('metadata_manager'); ?></h5>
            </div>
            <div class="header-elements">
                <div class="list-icons">
                    <a class="list-icons-item" data-action="collapse"></a>
                    <a class="list-icons-item" data-action="fullscreen"></a>
                </div>
            </div>
        </div>
        <div class="card-body">
<?php
}
?>
            <div id="metaProcessWindow">
                <form class="form-horizontal" role="form" method="post" id="metaProcess-form">
                    <div class="d-flex flex-row mt-2 mb-2 taskflow-bp-action-btn">
                        <label class="mr-1"><?php echo $this->lang->line('metadata_businessprocess'); ?>:</label>
                        <div class="mr-1">
                            <div class="input-group double-between-input" data-section-path="META_BUSINESS_PROCESS_LIST">
                                <?php 
                                echo Form::hidden(array('name' => 'mainBpId_valueField', 'id' => 'mainBpId_valueField', 'value' => $this->mainBpId));
                                echo Form::hidden(array('id' => 'mainBpId', 'value' => $this->mainBpId));
                                echo Form::text(array('name' => 'mainBpId_displayField', 'id' => 'mainBpId_displayField', 'value' => $this->mainBpData['META_DATA_CODE'], 'class' => 'form-control form-control-sm', 'placeholder' => 'кодоор хайх')); 
                                ?>
                                <span class="input-group-btn">
                                    <button type="button" class="btn default btn-bordered form-control-sm mr0" onclick="dataViewCustomSelectableGrid('META_BUSINESS_PROCESS_LIST', 'single', 'processFlowSelectableGrid', '', this);"><i class="fa fa-search"></i></button>
                                </span>     
                                <span class="input-group-btn" style="width:25%;">
                                    <?php echo Form::text(array('name' => 'mainBpId_nameField', 'id' => 'mainBpId_nameField', 'value' => $this->mainBpData['META_DATA_NAME'], 'class' => 'form-control form-control-sm', 'style' => 'width: 400px !important;', 'placeholder' => 'нэрээр хайх')); ?>    
                                </span>     
                            </div>
                        </div>
                        <button type="button" class="btn green btn-circle btn-sm mr-1" id="mainBpParamInput" title="Оролтын параметр"><i class="fa fa-download"></i></button>
                        <button type="button" class="btn blue btn-circle btn-sm" id="mainBpParamOutput" title="Гаралтын параметр"><i class="fa fa-upload"></i></button>
                    </div>
                    <div id="metaProcessDetial"></div>
                    <div class="clearfix w-100"></div>
                </form>
            </div>    
<?php
if (!$this->isAjax) {
?>            
        </div>
    </div>
</div>
<?php
}
?>

<style type="text/css">
    /* .wfIconRectangleBackground {
        background: #E67E22 !important;
    } */
    .wfcomplexbpWorked .wfIcon {
        background: #e9e9e973 !important;
    }
    .wfcomplexbpWillWork .wfIcon {
        background: #e9e9e973 !important;
    }
    .pflow-bp-descr {
        color: orange;
    }
</style>

<script type="text/javascript">
    var metaProcessWindowId = "#metaProcessWindow";
    var _detachParam = '';
    var isTaskFlow = <?php echo ($this->mainBpData['META_TYPE_ID'] == Mdmetadata::$taskFlowMetaTypeId || $this->mainBpData['SUB_TYPE'] == 'endtoend' ? 'true' : 'false'); ?>;   
    var isViewTaskFlow = '<?php echo Input::post('isViewTaskFlow') ?>'; 
    
    $(function () {
        $('#metaProcessDetial').on('click', '.addVisualMetaData', function () {
            commonMetaDataGrid('multi', 'metaGroup', 'autoSearch=1&metaTypeId=<?php echo Mdmetadata::$businessProcessMetaTypeId;?>|<?php echo Mdmetadata::$taskFlowMetaTypeId; ?>|<?php echo Mdmetadata::$bookmarkMetaTypeId; ?>&isComplexProcess=1');
        });

        $('#metaProcessDetial').on('click', '.removeAllArrowData', function () {
            var $dialogName = 'dialog-bparrowdelete-confirm';
            if (!$("#" + $dialogName).length) {
                $('<div id="' + $dialogName + '"></div>').appendTo('body');
            }
            var $dialog = $('#' + $dialogName);

            $dialog.empty().append('<strong style="color:#ef5350">БҮХ УРСГАЛЫН СУМ УСТГАХ УЧИР</strong><br><br> Та итгэлтэй байна уу?');
            $dialog.dialog({
                cache: false,
                resizable: false,
                bgiframe: true,
                autoOpen: false,
                title: 'Confirm',
                width: 400,
                height: 'auto',
                modal: true,
                close: function () {
                    $dialog.empty().dialog('destroy').remove();
                },
                buttons: [
                    {text: 'Тийм', class: 'btn green-meadow btn-sm', click: function () {
                        $.ajax({
                            type: 'post',
                            url: 'mdprocessflow/deleteAllArrowBp', 
                            data: {mainBpId: $("#mainBpId").val()}, 
                            beforeSend: function() {
                                Core.blockUI({message: 'Loading...', boxed: true});
                            },
                            success: function(dataSub) {
                                $dialog.dialog('close');
                                Core.unblockUI();
                                viewVisualHtmlMetaProcessFlowData($('#mainBpId').val());
                            }
                        });                        
                    }},
                    {text: 'Үгүй', class: 'btn blue-madison btn-sm', click: function () {
                        $dialog.dialog('close');
                    }}
                ]
            });

            $dialog.dialog('open');            
        });

        $('#metaProcessDetial').on('click', '.saveVisualParam', function () {
            saveVisualMetaControlData('', $("#mainBpId").val());
        });

        <?php if ($this->mainBpId != ''): ?>
            viewVisualHtmlMetaProcessFlowData(<?php echo $this->mainBpId; ?>);
        <?php endif; ?>

        if (isViewTaskFlow == '') {
            jsPlumb.bind("contextmenu", function(connection, originalEvent) {
                _detachParam = {source: connection.sourceId, target: connection.targetId};
                $.contextMenu({
                    selector: '._jsPlumb_connector',
                    callback: function (key, opt) {
                        if (key === '_jsPlumb_connector') {
                            if (_detachParam != '')
                                jsPlumb.select(_detachParam).detach();
                        }
                        if (key === '_jsPlumb_process') {
                            console.log('Criteria тохируулах');
                            return;
                        }
                    },
                    items: {
                        "_jsPlumb_connector": {name: "Сум устгах", icon: "trash"},
                        /* "_jsPlumb_process": {name: "Criteria тохируулах", icon: "gears"}*/
                    }
                });
            });
        }
        jsPlumb.bind("dblclick", function (connection, originalEvent) {
            if (isViewTaskFlow) return;

            var $dialogName = 'dialog-bp-process';
            if (!$("#" + $dialogName).length) {
                $('<div id="' + $dialogName + '"></div>').appendTo('body');
            }
            var $dialog = $('#' + $dialogName);
            
            sourceBpOrder = 0;
            if (connection.sourceId != 'startObject001') {
                var doneBpObject = jsPlumb.getSelector('#'+connection.sourceId + ' a');
                var sourceBpOrder = doneBpObject.find('div.wfIcon').attr('data-bporder');
            }
            
            var doBpObject = jsPlumb.getSelector('#'+connection.targetId + ' a');
            var targetBpOrder = doBpObject.find('div.wfIcon').attr('data-bporder');
            
            if (connection.targetId != 'endObject001') {
                var sourceId = '';
                var targetId = '';
                var mainBpId = $("#mainBpId").val();
                if (connection.sourceId != 'startObject001') {
                    sourceId = mainBpId;
                }
                if (!$('#'+connection.sourceId+ '_' + connection.targetId).length) {
                    $('<div id="'+connection.sourceId+ '_' + connection.targetId + '"></div>').appendTo('.heigh-editor', '#metaProcessDetial');
                }
                
                $.cachedScript('assets/custom/addon/plugins/codemirror/lib/codemirror.min.js').done(function() {
                    $.ajax({
                        type: 'post',
                        url: 'mdprocessflow/bpCriteria',
                        data: {
                            mainBpId: mainBpId, 
                            sourceId: connection.sourceId, 
                            targetId: connection.targetId, 
                            criteria: $('#'+connection.sourceId+ '_' + connection.targetId).val(),
                            isScheduled: $('#'+connection.sourceId+ '_' + connection.targetId+'_isscheduled').val(),
                            scheduleDatePath: $('#'+connection.sourceId+ '_' + connection.targetId+'_scheduledpath').val()
                        },
                        dataType: 'json',
                        beforeSend: function() {
                            if (!$("link[href='assets/custom/addon/plugins/codemirror/lib/codemirror.v1.css']").length){
                                $("head").append('<link rel="stylesheet" type="text/css" href="assets/custom/addon/plugins/codemirror/lib/codemirror.v1.css"/>');
                            }
                            Core.blockUI({animate: true});
                        },
                        success: function(data) {
                            $dialog.empty().append(data.Html);
                            $dialog.dialog({
                                cache: false,
                                resizable: true,
                                bgiframe: true,
                                autoOpen: false,
                                title: data.Title,
                                width: 600,
                                minWidth: 600,
                                height: 'auto',
                                modal: true,
                                close: function() {
                                    $dialog.dialog('destroy').remove();
                                },
                                buttons: [
                                    {text: plang.get('save_btn'), class: 'btn btn-sm green', click: function() {
                                        bpCriteriaEditorParam.save();
                                        $('#'+connection.sourceId+ '_' + connection.targetId).val($('#criteria').val());
                                        $('#'+connection.sourceId+ '_' + connection.targetId+'_isscheduled').val($('#isScheduled').val());
                                        $('#'+connection.sourceId+ '_' + connection.targetId+'_scheduledpath').val($('#scheduleDatePath').val());
                                        $dialog.dialog('close');
                                    }},
                                    {text: plang.get('close_btn'), class: 'btn btn-sm blue-hoki', click: function() {
                                        $dialog.dialog('close');
                                    }}
                                ]
                            });
                            $dialog.dialog('open');
                            Core.unblockUI();
                        },
                        error: function() {
                            alert("Error");
                        }
                    }).done(function() {
                        bpCriteriaEditorParam.refresh();
                        Core.initAjax($dialog);
                    });
                });
            } else {
                $dialog.html('Төгсгөлийн бизнес процесс criteria тохируулах боломжгүй');
                $dialog.dialog({
                    cache: false,
                    resizable: true,
                    bgiframe: true,
                    autoOpen: false,
                    title: 'Сануулах',
                    width: '300',
                    height: 'auto',
                    modal: true,
                    buttons: [
                        {text: plang.get('close_btn'), class: 'btn blue-madison btn-sm', click: function () {
                            $dialog.dialog('close');
                        }}
                    ]
                });
                $dialog.dialog('open');
            }
        });

        $('#metaProcessDetial').on('click', '.previewMeta', function () {
            viewVisualMetaData();
        });

        $('#metaProcessDetial').on('click', '#bpChild .extra', function () {
            callMetaParameter($("#mainBpId").val(), $(this).attr('data-id'));
        });
        
        $('#metaProcessDetial').on('click', '#bpChild .IS_START', function () {
            $('.IS_START').val(0);
            $(this).val($(this).attr('data-id'));
        });
        
        $('#metaProcessDetial').on('click', '.saveMeta', function () {
            $.ajax({
                type: 'post',
                url: 'mdprocessflow/saveMetaProcess',
                data: $('#metaProcess-form').serialize(),
                dataType: "json",
                beforeSend: function () {
                    Core.blockUI({animate: true});
                },
                success: function (data) {
                    if (data.status === 'success') {
                        new PNotify({
                            title: data.status,
                            text: 'Амжилттай хадгаллаа',
                            type: data.status,
                            sticker: false
                        });
                    } else {
                        new PNotify({
                            title: data.status,
                            text: data.text,
                            type: data.status,
                            sticker: false
                        });
                    }
                    Core.unblockUI();
                },
                error: function () {
                    Core.unblockUI();
                    new PNotify({
                        title: 'Анхааруулга',
                        text: 'Хадгалах боломжгүй',
                        type: 'error',
                        sticker: false
                    });
                }
            });
        });
        
        $("button#mainBpParamOutput").on("click", function () {
            if ($("#mainBpId").val().length > 0) {
                var dialogName = '#bpChildDialog';
                if (!$(dialogName).length) {
                    $('<div id="' + dialogName.replace('#', '') + '"></div>').appendTo('body');
                }
                var doProcessId = $('#mainBpId').val();
                var pId = $("div[data-dobpid='"+ doProcessId +"']").attr('data-workflowid');
                var connections = [];
                var connection = returnDoneProcessList(pId, connections, jsPlumb.getConnections());
                $.ajax({
                    type: 'post',
                    url: 'mdprocessflow/getOutputMetaParameterByProcess',
                    data: {mainBpId: doProcessId, connection: connection},
                    beforeSend: function () {
                        Core.blockUI({animate: true});
                    },
                    success: function (data) {
                        $(dialogName).html(data);
                        $(dialogName).dialog({
                            cache: false,
                            resizable: true,
                            bgiframe: true,
                            autoOpen: false,
                            title: 'Бизнес процессийн параметр',
                            width: '100%',
                            height: 'auto',
                            modal: true,
                            buttons: [
                                {text: 'Хадгалах', class: 'btn blue btn-sm', click: function () {
                                    $.ajax({
                                        type: 'post',
                                        url: 'mdprocessflow/saveMetaProcessParameter',
                                        data: $('#inputParameter-form').serialize(),
                                        dataType: "json",
                                        beforeSend: function () {
                                            Core.blockUI({animate: true});
                                        },
                                        success: function (data) {
                                            PNotify.removeAll();
                                            if (data.status === 'success') {
                                                new PNotify({
                                                    title: 'Success',
                                                    text: data.message,
                                                    type: 'success',
                                                    sticker: false
                                                });
                                                $(dialogName).dialog('close');
                                            } else {
                                                new PNotify({
                                                    title: 'Error',
                                                    text: data.message,
                                                    type: 'error',
                                                    sticker: false
                                                });
                                            }
                                            Core.unblockUI();
                                        },
                                        error: function () {
                                            new PNotify({
                                                title: 'Error',
                                                text: 'error',
                                                type: 'error',
                                                sticker: false
                                            });
                                        }
                                    });
                                }},
                                {text: 'Хаах', class: 'btn grey-cascade btn-sm', click: function () {
                                    $(dialogName).dialog('close');
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
                            }
                        });
                        $(dialogName).dialogExtend("maximize");
                        $(dialogName).dialog('open');
                        Core.unblockUI();
                    },
                    error: function () {
                        alert("Error");
                    }
                });

            } else {
                warningMsgChooseMainBp();
            }
        });
        
        $("button#mainBpParamInput").on("click", function () {
            if ($("#mainBpId").val().length > 0) {
                mainBpId = $("#mainBpId").val();
                doBpId = $("#mainBpId").val();
                var pId = $("div[data-dobpid='"+ doBpId +"']").attr('data-workflowid');
                callMetaParameter(mainBpId, doBpId, pId);
            } else {
                warningMsgChooseMainBp();
            }

        });
        
        $('#mainBpId_displayField').on("focus", function(e){
            var _this = $(this);
            var isHoverSelect = false;
            
            _this.autocomplete({
                minLength: 1,
                maxShowItems: 30,
                delay: 500,
                highlightClass: "lookup-ac-highlight", 
                appendTo: "body",
                position: {my : "left top", at: "left bottom", collision: "flip flip"}, 
                autoFocus: false,
                source: function(request, response) {
                    $.ajax({
                        type: 'post',
                        url: 'mdprocessflow/filterBusinessProcessInfo',
                        dataType: "json",
                        data: {q: request.term},
                        success: function(data) {
                            response($.map(data, function(item) {
                                return {
                                    label: item.CODE,
                                    name: item.NAME,
                                    data: item
                                };
                            }));
                        }
                    });
                },
                focus: function(event, ui) {
                    if (typeof event.keyCode === 'undefined' || event.keyCode == 0) {
                        isHoverSelect = false;
                    } else {
                        if (event.keyCode == 38 || event.keyCode == 40) {
                            isHoverSelect = true;
                        }
                    }
                    return false;
                },
                open: function() {
                    $(this).autocomplete('widget').zIndex(99999999999999);
                    return false;
                },
                close: function (event, ui){
                    $(this).autocomplete("option","appendTo","body"); 
                }, 
                select: function(event, ui){
                    var data = ui.item.data;
                    $("#mainBpId", metaProcessWindowId).val(data.ID);
                    $("#mainBpId_valueField", metaProcessWindowId).val(data.ID);
                    $("#mainBpId_displayField", metaProcessWindowId).val(data.CODE);
                    $("#mainBpId_nameField", metaProcessWindowId).val(data.NAME);
                    viewVisualHtmlMetaProcessFlowData(data.ID);
                }
            }).autocomplete("instance")._renderItem = function(ul, item) {
                ul.addClass('lookup-ac-render');

                var re = new RegExp("(" + this.term + ")", "gi"),
                    cls = this.options.highlightClass,
                    template = "<span class='" + cls + "'>$1</span>",
                    label = item.label.replace(re, template);

                return $('<li>').append('<div class="lookup-ac-render-code">'+label+'</div><div class="lookup-ac-render-name">'+item.name+'</div>').appendTo(ul);
            };
        });
        
        $('#mainBpId_nameField').on('keyup', function(e) {
            var code = (e.keyCode ? e.keyCode : e.which);
            if (code !== 13) {
                return false;
            }
            var _this = $(this);
            var isHoverSelect = false;
            var _parent = _this.closest("div.input-group");
            var lookupCode = _parent.attr("data-section-path");

            _this.autocomplete({
                minLength: 1,
                maxShowItems: 10,
                delay: 500,
                highlightClass: "lookup-ac-highlight",
                appendTo: "body",
                position: {my: "left top", at: "left bottom", collision: "flip flip"},
                autoSelect: false,
                source: function(request, response) {
                    $.ajax({
                        type: 'post',
                        url: 'mdgl/glLookupAutoComplete',
                        dataType: "json",
                        data: {
                            lookupCode: lookupCode,
                            q: request.term,
                        },
                        success: function(data) {
                            response($.map(data, function(item) {
                                var code = item.codeName.split("|");
                                return {
                                    value: code[1],
                                    label: code[1],
                                    name: code[2],
                                    row: item.row
                                };
                            }));
                        }
                    });
                },
                focus: function(event, ui) {
                    if (typeof event.keyCode === 'undefined' || event.keyCode == 0) {
                        isHoverSelect = false;
                    } else {
                        if (event.keyCode == 38 || event.keyCode == 40) {
                            isHoverSelect = true;
                        }
                    }
                    return false;
                },
                open: function() {
                    $(this).autocomplete('widget').zIndex(99999999999999);
                    return false;
                },
                close: function() {
                    $(this).autocomplete("option", "appendTo", "body");
                },
                select: function(event, ui) {
                    var origEvent = event;

                    if (isHoverSelect || event.originalEvent.originalEvent.type == 'click') {
                        if (type === 'code') {
                            _parent.find("input[id*='_displayField']").val(ui.item.label);
                        } else {
                            _parent.find("input[id*='_nameField']").val(ui.item.name);
                        }
                    } else {
                        if (type === 'code') {
                            if (ui.item.label === _this.val()) {
                                _parent.find("input[id*='_displayField']").val(ui.item.label);
                                _parent.find("input[id*='_nameField']").val(ui.item.name);
                            } else {
                                _parent.find("input[id*='_displayField']").val(_this.val());
                                event.preventDefault();
                            }
                        } else {
                            if (ui.item.name === _this.val()) {
                                _parent.find("input[id*='_displayField']").val(ui.item.label);
                                _parent.find("input[id*='_nameField']").val(ui.item.name);
                            } else {
                                _parent.find("input[id*='_nameField']").val(_this.val());
                                event.preventDefault();
                            }
                        }
                    }

                    while (origEvent.originalEvent !== undefined) {
                        origEvent = origEvent.originalEvent;
                    }

                    if (origEvent.type === 'click') {
                        var e = jQuery.Event("keydown");
                        e.keyCode = e.which = 13;
                        _this.trigger(e);
                    }
                }
            }).autocomplete("instance")._renderItem = function(ul, item) {};
        });
    });
    
    function warningMsgChooseMainBp() {
        var dialogName = '#warningMsgDialog';
        if (!$(dialogName).length) {
            $('<div id="' + dialogName.replace('#', '') + '"></div>').appendTo('body');
        }
        $(dialogName).html("Бизнес процесс сонгоогүй байна");
        $(dialogName).dialog({
            cache: false,
            resizable: true,
            bgiframe: true,
            autoOpen: false,
            title: 'Анхааруулга',
            width: '300',
            height: 'auto',
            modal: true,
            buttons: [
                {text: 'Хаах', class: 'btn grey-cascade btn-sm', click: function () {
                    $(dialogName).dialog('close');
                }}
            ]
        });
        $(dialogName).dialog('open');
    }
    
    function processDrillDown(elem) {
        var $this = $(elem);
        window.open('mdmetadata/gotoEditMeta/' + $this.find('[data-dobpid]').attr('data-dobpid'), '_blank');
    }
    
    function processFlowSelectableGrid(metaDataCode, chooseType, elem, rows) {
        var row = rows[0];
        $("#mainBpId", metaProcessWindowId).val(row.id);
        $("#mainBpId_valueField", metaProcessWindowId).val(row.id);
        $("#mainBpId_displayField", metaProcessWindowId).val(row.code);
        $("#mainBpId_nameField", metaProcessWindowId).val(row.name);
        viewVisualHtmlMetaProcessFlowData(row.id);
    }
</script>
