<div class="col-md-12 metaWfmStatusForm metaWfmStatus<?php echo issetParam($this->uniqId) ?> <?php echo ($this->showFields) ? 'pl0 pr0' : '' ?>" id="metaProcessWindow" style="height: 100%;">
    <?php if (!$this->showFields) { ?>
    <form class="form-horizontal" role="form" method="post" id="metaProcess-form" style="height: 100%;">
        <div class="form-group row fom-row hidden">
            <label class="col-md-3 col-sm-3 col-xs-3 col-form-label custom-label">Жагсаалт: </label>
            <div class="col-md-9">
                <div class="col-md-7 col-sm-7 col-xs-6">
                    <div class="meta-autocomplete-wrap" data-section-path="metaDataId">
                        <div class="input-group double-between-input">
                            <input type="hidden" id="metaDataId_valueField" name="param[metaDataId]" class="popupInit">
                            <input type="text" id="metaDataId_displayField" style="border-top-left-radius: 3px; border-bottom-left-radius: 3px" readonly="readonly" name="metaDataId_displayField" class="form-control form-control-sm meta-autocomplete lookup-code-autocomplete" value="">
                            <span class="input-group-btn">
                                <button type="button" class="btn default btn-bordered form-control-sm mr0" onclick="dataViewCustomSelectableGrid('sysWorkflowLU1', 'single', 'chooseFunction', '', this);"><i class="fa fa-search"></i></button>
                            </span>     
                            <span class="input-group-btn">
                                <input type="text" id="metaDataId_nameField" readonly="readonly" name="metaDataId_nameField" class="form-control form-control-sm meta-name-autocomplete lookup-name-autocomplete" value="" title="" placeholder="">      
                            </span>     
                        </div>
                    </div>
                </div>
                <div class="col-md-1">
                    <button type="button" class="btn btn-secondary btn-circle btn-sm" title="Ажлын урсгал нэмэх" onclick="addMetaWorkFlowFuntion()"><i class="icon-plus3 font-size-12"></i></button>
                </div>
            </div>
        </div>
    <?php } ?>
        <div id="metaProcessDetial" class="row">
            <div style="min-height: 350px; border:1px solid #CCC; border-radius: 3px;"></div>
        </div>
        <div class="clearfix w-100"></div>
        <?php if (!$this->showFields) { ?>    
    </form>
    <?php } ?>
</div>
<style type="text/css">
    .nav-pills>li>a {
        padding: 6px 8px 6px;
    }
    #metaProcessWindow .jstree-clicked {
        background-color: #cce6ff;
    }
    .aLabel {
        background-color: white;
        opacity: 0.8;
        padding: 0.3em;
        border-radius: 0.5em;
        border: 1px solid #346789;
        cursor: pointer;
    }
    ._jsPlumb_overlay {
        background: white;
        border: 1px solid #CCC;
        padding: 3px;
    }
    #metaProcessDetial .pv-field {
        border-radius: 15px;
        padding: 3px 7px;
        float: left;
    }
    #metaProcessDetial .pv-field span {
        vertical-align: middle;
        overflow: auto;
        white-space: normal; 
        display: inline-block; 
        max-width: 100%; 
        font-size: 12px;
        
    }
    .jstree {
        overflow-y: auto;
    }    
</style>
<script type="text/javascript">
    var tempWfmStatusId = 0;
    var metaProcessWindowId = "#metaProcessWindow";
    var ws_selector_left = $('div#metaProcessWindow');
    var showFields = <?php echo (isset($this->showFields) && !$this->showFields) ? 1 : 0 ?>;
    var linkWorkFlowId = <?php echo (isset($this->workFlowId) && $this->workFlowId) ? $this->workFlowId : 0 ?>;
    var _detachParam = '', arrowShape = '<?php echo $this->arrowShape; ?>';
    $(function () {
        
        $.ajax({
            url: 'assets/custom/addon/plugins/jsplumb/jsplumb.min.js',
            dataType: 'script',
            cache: false, 
            async: false
        }).done(function(){
            
            $.ajax({
                url: 'middleware/assets/js/mdworkflowProcessInteractive.js',
                dataType: 'script',
                cache: false, 
                async: false
            }).done(function(){
                $.ajax({
                    url: 'assets/custom/addon/plugins/codemirror/lib/codemirror.min.js',
                    dataType: 'script',
                    cache: false, 
                    async: false,
                    beforeSend:function(){
                        $("head").append('<link rel="stylesheet" type="text/css" href="assets/custom/addon/plugins/codemirror/lib/codemirror.css"/>');
                        $("head").append('<link rel="stylesheet" type="text/css" href="assets/custom/addon/plugins/jsplumb/css/style.css"/>');
                        $("head").append('<link rel="stylesheet" type="text/css" href="assets/custom/addon/plugins/kwicks/step.css"/>');
                        $("head").append('<link rel="stylesheet" type="text/css" href="middleware/assets/theme/theme4/css/main.css"/>');
                    }
                }).done(function() {

                    <?php if ($this->mainBpId != '') { ?>
                        viewVisualHtmlMetaData('<?php echo $this->mainBpId ?>', '<?php echo $this->transId ?>', '<?php echo issetParam($this->type) ?>', JSON.stringify(<?php echo json_encode($this->selectedRow) ?>), '<?php echo $this->refStructureId ?>', '<?php echo $this->dataViewId ?>');
                    <?php } else { ?>
                        $('#mainBpId').on('change', function () {
                            if ($(this).val() != '') {
                                viewVisualHtmlMetaData($(this).val(), undefined, '<?php echo issetParam($this->type) ?>');    //visual data zurj uzuulne
                            } else {
                                $('#metaProcessDetial').empty();
                            }
                        });
                    <?php } ?>
                           
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

                    jsPlumb.bind("dblclick", function (connection, originalEvent) {
                        var $dialogName = 'dialog-meta-wfm-status';
                        if (!$("#" + $dialogName).length) {
                            $('<div id="' + $dialogName + '"></div>').appendTo('body');
                        }
                        var $dialog = $("#" + $dialogName);

                        sourceBpOrder = 0;
                        if (connection.sourceId != 'startObject001') {
                            var doneBpObject = jsPlumb.getSelector('#'+connection.sourceId + ' a');
                            var sourceBpOrder = doneBpObject.find('div.wfIcon').attr('data-bporder');
                        }

                        var doBpObject = jsPlumb.getSelector('#'+connection.targetId + ' a');
                        var targetBpOrder = doBpObject.find('div.wfIcon').attr('data-bporder');

                        var sourceId = '';
                        var targetId = '';

                        $.ajax({
                            type: 'post',
                            url: 'mdprocessflow/wfmCriteria',
                            data: {sourceId: connection.sourceId, targetId: connection.targetId, transitionId: selectedTransitionId, metaDataId: mainMetaDataId},
                            dataType: 'json',
                            beforeSend: function() {
                                if (!$("link[href='middleware/assets/css/salary/expression.css']").length){
                                    $("head").append('<link rel="stylesheet" type="text/css" href="middleware/assets/css/salary/expression.css"/>');
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
                                    width: 1100,
                                    minWidth: 1100,
                                    height: 'auto',
                                    modal: true,
                                    close: function() {
                                        $dialog.empty().dialog('close');
                                    },
                                    buttons: [
                                        {text: data.save_btn, class: 'btn btn-sm green', click: function() {
                                            PNotify.removeAll();    
                                            var expArea = $("#brcriteria-form", "#"+$dialogName).find('.p-exp-area');
                                            var expAreaContent = $.trim(expArea.html());
                                            $("#brcriteria-form", "#"+$dialogName).find('input[name="bpCriteria"]').val(expAreaContent);

                                            $.ajax({
                                                type: 'post',
                                                url: 'mdprocessflow/saveWfmCriteria',
                                                data: $("#brcriteria-form", "#"+$dialogName).serialize(),
                                                dataType: 'json',
                                                beforeSend: function() {
                                                    Core.blockUI({animate: true});
                                                },
                                                success: function(data) {
                                                    if (data.status === 'success') {
                                                        jsPlumb.detach(connection);

                                                        $('#DESCRIPTION_'+ connection.sourceId +'_'+ connection.targetId).remove();
                                                        $('#CRITERIA_'+ connection.sourceId +'_'+ connection.targetId).remove();

                                                        var _arrayConnector = {
                                                            PREV_WFM_STATUS_ID: connection.sourceId,
                                                            NEXT_WFM_STATUS_ID: connection.targetId,
                                                            DESCRIPTION: data.data.DESCRIPTION,
                                                            CRITERIA: data.data.CRITERIA
                                                        }
                                                        workflowConnectionImport(_arrayConnector);
                                                        new PNotify({
                                                            title: data.status,
                                                            text: data.message,
                                                            type: data.status,
                                                            sticker: false
                                                        });
                                                        $dialog.dialog('close');
                                                    } else {
                                                        new PNotify({
                                                            title: data.status,
                                                            text: data.message,
                                                            type: data.status,
                                                            sticker: false
                                                        });
                                                    }
                                                    Core.unblockUI();
                                                },
                                                error: function() { alert("Error"); }
                                            });
                                        }},
                                        {text: data.close_btn, class: 'btn btn-sm blue-hoki', click: function() {
                                            $dialog.dialog('close');
                                        }}
                                    ]
                                });
                                $dialog.dialog('open');
                                Core.unblockUI();
                            },
                            error: function() {alert("Error");}
                        }).done(function() {
                            transitionCriteriaEditorParam.refresh();
                            Core.initAjax($dialog);
                        });
                    });

                });
            });
        });

    });
    
    function chooseFunction(metaDataCode, chooseType, elem, rows) {
        if (typeof rows[0].metadataid != 'undefined') {
            $('#metaDataId_valueField').val(rows[0].metadataid);
            $('#metaDataId_displayField').val(rows[0].metadatacode);
            $('#metaDataId_nameField').val(rows[0].metadataname);
            viewVisualHtmlMetaData(rows[0].metadataid, undefined, '<?php echo issetParam($this->type) ?>');
        }
    }

    function wfmCriteriaMore(refId, islookup, desc) {
        $.ajax({
            type: 'post',
            url: 'mdprocessflow/wfmCriteriaMore',
            data: {refId:refId, islookup:islookup, desc},
            dataType: 'json',
            beforeSend: function() {
                Core.blockUI({animate: true});
            },
            success: function(data) {
                var $dialogName = 'dialog-meta-wfm-status-more';
                if (!$("#" + $dialogName).length) {
                    $('<div id="' + $dialogName + '"></div>').appendTo('body');
                }
                var $dialog = $("#" + $dialogName);       

                $dialog.empty().append(data.Html);
                $dialog.dialog({
                    cache: false,
                    resizable: true,
                    bgiframe: true,
                    autoOpen: false,
                    title: data.Title,
                    width: 500,
                    minWidth: 500,
                    height: 'auto',
                    modal: true,
                    close: function() {
                        $dialog.empty().dialog('close');
                    },
                    buttons: [
                        {text: data.close_btn, class: 'btn btn-sm blue-hoki', click: function() {
                            $dialog.dialog('close');
                        }}
                    ]
                });
                $dialog.dialog('open');
                Core.unblockUI();
            },
            error: function() {alert("Error");}
        }).done(function() {
            transitionCriteriaEditorParam.refresh();
            Core.initAjax($dialog);
        });        
    }
    
</script>