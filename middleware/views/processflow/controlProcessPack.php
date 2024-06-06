<div class="col-md-12 metaWfmStatusForm <?php echo ($this->showFields) ? 'pl0 pr0' : '' ?>" id="metaProcessWindow" style="height: 100%;">
    <?php if (!$this->showFields) { ?>
    <form class="form-horizontal" role="form" method="post" id="metaProcess-form" style="height: 100%;">
        <?php
        if ($this->isAjax == true && Config::getFromCache('CONFIG_MULTI_TAB')) {
        ?>
        <div class="row">
        <?php
        } else {
        ?>  
        <div class="card light shadow"  style="height: 100%;">
            <div class="card-header card-header-no-padding header-elements-inline">
                <div class="caption buttons">
                    <?php
                    echo html_tag('a', array(
                            'href' => 'javascript:history.back();',
                            'class' => 'btn btn-circle btn-secondary card-subject-btn-border',
                            'style' => ''
                        ), '<i class="icon-arrow-left7"></i>', true
                    );
                    ?>     
                </div>
                <div class="caption ml10">
                    <span class="caption-subject font-weight-bold uppercase card-subject-blue">
                        <?php echo $this->title; ?>
                    </span>
                    <span class="caption-subject font-weight-bold text-uppercase text-gray2">УДИРДАХ</span>
                </div>
                <div class="header-elements">
                    <div class="list-icons">
                        <a class="list-icons-item" data-action="fullscreen"></a>
                    </div>
                </div>
            </div>
            <div class="card-body"  style="height: 100%;">  
            <div class="row">
        <?php
        }
        ?>        
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
            <div id="metaProcessDetial" class="row w-100">
                <div style="min-height: 350px; border:1px solid #CCC; border-radius: 3px;">
                </div>
            </div>
        <?php 
        if (!$this->showFields) { 

        if ($this->isAjax == true && Config::getFromCache('CONFIG_MULTI_TAB')) {
        ?>       
        </div>
            <div class="clearfix w-100"></div>
        <?php } else { ?>
            <div class="clearfix w-100"></div>
            </div>
        <?php } ?>    
        </div>
        </div>    
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
    var _detachParam = '';
    $(function () {
        
        $.ajax({
            url: 'assets/custom/addon/plugins/jsplumb/jsplumb.min.js',
            dataType: 'script',
            cache: false, 
            async: false
        }).done(function(){
            
            $.ajax({
                url: 'middleware/assets/js/mdworkflowProcessPack.js',
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
                }).done(function(){

                    <?php if ($this->mainBpId != '') { ?>
                                viewVisualHtmlMetaData('<?php echo $this->mainBpId ?>', '<?php echo $this->transId ?>');
                            <?php } else { ?>
                                $('#mainBpId').on('change', function () {
                                    if ($(this).val() != '') {
                                        viewVisualHtmlMetaData($(this).val());    //visual data zurj uzuulne
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
                                    data: {sourceId: connection.sourceId, targetId: connection.targetId, transitionId: selectedTransitionId},
                                    dataType: 'json',
                                    beforeSend: function() {
                                        $("head").append('<link rel="stylesheet" type="text/css" href="assets/custom/addon/plugins/codemirror/lib/codemirror.css"/>');
                                        $.getScript("assets/custom/addon/plugins/codemirror/lib/codemirror.js", function(){
                                            $.getScript("assets/custom/addon/plugins/codemirror/mode/javascript/javascript.js", function(){
                                                $.getScript("assets/custom/addon/plugins/codemirror/addon/selection/active-line.js");
                                                $.getScript("assets/custom/addon/plugins/codemirror/addon/edit/matchbrackets.js");
                                                $.getScript("assets/custom/addon/plugins/codemirror/addon/edit/closebrackets.js");
                                            });
                                        });
                                        Core.blockUI({
                                            animate: true
                                        });
                                    },
                                    success: function(data) {
                                        $("#" + $dialogName).empty().append(data.Html);
                                        $("#" + $dialogName).dialog({
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
                                                $("#" + $dialogName).empty().dialog('close');
                                            },
                                            buttons: [
                                                {text: data.save_btn, class: 'btn btn-sm green', click: function() {
                                                    transitionCriteriaEditorParam.save();
                                                    $.ajax({
                                                        type: 'post',
                                                        url: 'mdprocessflow/saveWfmCriteria',
                                                        data: $("#brcriteria-form", "#"+$dialogName).serialize(),
                                                        dataType: 'json',
                                                        beforeSend: function() {
                                                            Core.blockUI({
                                                                animate: true
                                                            });
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
                                                                $("#" + $dialogName).dialog('close');
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
                                                        error: function() {
                                                            alert("Error");
                                                        }
                                                    });
                                                    $("#" + $dialogName).dialog('close');
                                                }},
                                                {text: data.close_btn, class: 'btn btn-sm blue-hoki', click: function() {
                                                    $("#" + $dialogName).dialog('close');
                                                }}
                                            ]
                                        });
                                        $("#" + $dialogName).dialog('open');
                                        Core.unblockUI();
                                    },
                                    error: function() {
                                        alert("Error");
                                    }
                                }).done(function() {
                                    transitionCriteriaEditorParam.refresh();
                                    Core.initAjax($("#" + $dialogName));
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
            viewVisualHtmlMetaData(rows[0].metadataid);
        }
    }
    
</script>