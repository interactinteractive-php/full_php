<div class="card p-0">
    <div class="card-body" style="background: #FFF;">
        <div class="clearfix w-100"></div>
        <div class="col-md-12 row assetmobi" id="asset-mobi-port-connection-<?php echo $this->uniqId ?>" data-assetid="<?php echo issetParam($this->assetId) ?>">
            <div class="w-100 pull-left">
                
            </div>
            <div class="col-md-4 col-sm-12 col-xs-12 pad-0 lifecycle-toggler-left"  style="background: #FFF; min-height: 500px">
                <div class="lifecycle-div lifecycle-common-div not-datagrid" id="lifecycle_div-<?php echo $this->uniqId; ?>" data-id="<?php echo $this->metaDataId . '-' . $this->uniqId; ?>">
                    <h4 class="lifecycle-title cursorPointer lifecycle-toggler" data-toggler="collapse"><?php echo $this->lang->line('menu_001'); ?> <i class="fa fa-chevron-circle-left"></i></h4>
                    <div class="btn-group btn-group-devided pr4" >
                        <?php if ($this->isEdit === 'true'): ?>
                            <a class="btn btn-success btn-circle btn-sm" title="Сайтад төхөөрөмж бүртгэх" data-advanced-criteria="" onclick="callProcessLifeCycle_<?php echo $this->uniqId; ?>('', '<?php echo $this->metaDataId ?>', '1533139042269', '200101010000011', 'toolbar', this, {callerType: 'mobSiteEquipmentDropList'});" data-actiontype="insert" data-dvbtn-processcode="MOB_CHECK_KEY_DV_006" href="javascript:;"><i class="icon-plus3 font-size-12"></i> </a>
                            <a class="btn btn-warning btn-circle btn-sm" title="Сайтад төхөөрөмж бүртгэх" data-advanced-criteria="" onclick="callProcessLifeCycle_<?php echo $this->uniqId; ?>('', '<?php echo $this->metaDataId ?>', '1533139065225', '200101010000011', 'toolbar', this, {callerType: 'mobSiteEquipmentDropList'});" data-actiontype="update" data-dvbtn-processcode="MOB_CHECK_KEY_DV_007" href="javascript:;"><i class="fa fa-edit"></i> </a>
                            <!--<a class="btn red-thunderbird btn-circle btn-sm" title="Сайтын төхөөрөмж хураах" data-advanced-criteria="" onclick="callProcessLifeCycle_<?php echo $this->uniqId; ?>('', '<?php echo $this->metaDataId ?>', '1535617006997', '200101010000011', 'toolbar', this, {callerType: 'mobSiteEquipmentDropList'});" data-actiontype="update" data-dvbtn-processcode="MOB_CHECK_KEY_DV_0010" href="javascript:;"><i class="fa fa-minus-circle"></i> </a>-->
                            <!--<a class="btn btn-danger btn-circle btn-sm" title="Устгах" data-advanced-criteria="" onclick="callProcessLifeCycle_<?php echo $this->uniqId; ?>('', '<?php echo $this->metaDataId ?>', '1533139064244', '200101010000011', 'toolbar', this, {callerType: 'mobSiteEquipmentDropList'});" data-actiontype="delete" data-dvbtn-processcode="MOB_CHECK_KEY_DV_005" href="javascript:;"><i class="fa fa-trash"></i> </a>-->
                            <a class="btn btn-info btn-circle btn-sm" title="Дахин ачааллах" onclick="connectionAssets.init('<?php echo $this->uniqId; ?>', '<?php echo $this->metaDataId ?>', '<?php echo (isset($this->selectedRow) && $this->selectedRow) ? $this->selectedRow : '' ?>', '<?php echo $this->taskTabMetaDataId ?>', '<?php echo $this->pkiTabMetaDataId ?>', '', '<?php echo $this->taskId; ?>', '<?php echo $this->isEdit ?>');" href="javascript:;"><i class="fa fa-refresh"></i> </a>

<!--                            <a class="btn btn-success btn-circle btn-sm" title="Сайтад төхөөрөмж бүртгэх" data-advanced-criteria="" onclick="callProcessLifeCycle_<?php echo $this->uniqId; ?>('', '<?php echo $this->metaDataId ?>', '1558084582283', '200101010000011', 'toolbar', this, {callerType: 'mobSiteEquipmentDropList'});" data-actiontype="insert" data-dvbtn-processcode="CHECK_KEY_DV_006" href="javascript:;"><i class="fa fa-key"></i> </a>
                            <a class="btn btn-success btn-circle btn-sm" title="Сайтад төхөөрөмж бүртгэх" data-advanced-criteria="" onclick="callProcessLifeCycle_<?php echo $this->uniqId; ?>('', '<?php echo $this->metaDataId ?>', '1558083766894', '200101010000011', 'toolbar', this, {callerType: 'mobSiteEquipmentDropList'});" data-actiontype="insert" data-dvbtn-processcode="SITE_EQUIPMENT_MOVEMENT_BP" href="javascript:;"><i class="fa fa-key"></i> </a>
                            <a class="btn btn-success btn-circle btn-sm" title="Сайтад төхөөрөмж бүртгэх" data-advanced-criteria="" onclick="callProcessLifeCycle_<?php echo $this->uniqId; ?>('', '<?php echo $this->metaDataId ?>', '1558514441117', '200101010000011', 'toolbar', this, {callerType: 'mobSiteEquipmentDropList'});" data-actiontype="insert" data-dvbtn-processcode="SITE_EQUIPMENT_AKT_DV_001" href="javascript:;"><i class="fa fa-key"></i> </a>-->
                        <?php endif; ?>
                        <?php echo Form::hidden(array('name' => 'dataview-criteria-params-' . $this->metaDataId, 'id' => 'dataview-criteria-params-' . $this->metaDataId, 'value' => 'parentLocationId=')); ?>
                    </div>
                    <div class="lifecycle-tree">
                        <div id="left-tree-list-<?php echo $this->uniqId; ?>" class="lifecycle-common-div lifecycle-selected-t lifecycle-dv-<?php echo $this->uniqId; ?> "></div>
                        <div id="left-tree-list-adjacent_<?php echo $this->uniqId; ?>"></div>
                    </div>
                </div>
            </div>
            <div class="col-md-8 col-sm-12 col-xs-12 pr0 pt10 lifecycle-toggler-right" style="background: #FFF;  min-height: 500px">
                <div id="rightSideDv_<?php echo $this->uniqId; ?>" class="lifecycle-common-right">
                    <div class="tabbable-line">
                        <ul class="nav nav-tabs">
                            <li class="nav-item">
                                <a href="#tab_asset_general_<?php echo $this->uniqId ?>" class="nav-link active" data-toggle="tab">Ерөнхий</a>
                            </li>
                            <li class="nav-item">
                                <a href="#tab_asset_connection_<?php echo $this->uniqId ?>" onclick="connectionAssetsTab_<?php echo $this->uniqId ?>(this)" data-toggle="tab" class="nav-link">Холболт</a>
                            </li>
                            <li class="nav-item">
                                <a href="#tab_asset_task_<?php echo $this->uniqId ?>" onclick="assetTaskTab_<?php echo $this->uniqId ?>(this, '.taskRenderBp_<?php echo $this->uniqId; ?>')" data-toggle="tab" class="nav-link"><?php echo $this->lang->line('asset_task'); ?></a>
                            </li>
                            <li class="nav-item">
                                <a href="#tab_asset_pki_<?php echo $this->uniqId ?>" id="href-tab-<?php echo $this->uniqId ?>" data-toggle="tab" onclick="assetPkiTab_<?php echo $this->uniqId ?>('.pkiRenderBp_<?php echo $this->uniqId ?>');" aria-expanded="true" class="nav-link"><?php echo $this->lang->line('asset_config'); ?></a>
                            </li>
                        </ul>
                        <div class="tab-content">
                            <div class="tab-pane active" id="tab_asset_general_<?php echo $this->uniqId ?>">
                                <div class="col-md-12">
                                    <div class="rightsideSite rightSideRenderBp_<?php echo $this->uniqId; ?>" data-uniqid="<?php echo $this->uniqId; ?>"></div>
                                </div>
                            </div>
                            <div class="tab-pane" id="tab_asset_connection_<?php echo $this->uniqId ?>">
                                <div class="col-md-12">
                                </div>
                            </div>
                            <div class="tab-pane" id="tab_asset_task_<?php echo $this->uniqId ?>">
                                <div class="col-md-12">
                                    <div class="taskRenderBp_<?php echo $this->uniqId; ?>"></div>
                                </div>
                            </div>
                            <div class="tab-pane" id="tab_asset_pki_<?php echo $this->uniqId ?>">
                                <div class="col-md-12">
                                    <div class="pkiRenderBp_<?php echo $this->uniqId; ?>"></div>
                                </div>
                            </div>
                            <div class="clearfix w-100"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="clearfix w-100"></div>
    </div>
</div>
<style type="text/css">
    #left-tree-list-<?php echo $this->uniqId; ?> .jstree-ocl {
        width: 24px !important;
        float: left;
        display: block !important;
        height: 20px;
    }
    
    #left-tree-list-<?php echo $this->uniqId; ?> .jstree-wholerow-ul {
        min-width: 96% !important;
    }
    .lifecycle-dv-<?php echo $this->uniqId; ?> .lifecycle-div .jstree-default .jstree-anchor {
        width: initial !important;
        white-space: nowrap;
    }
    #asset-mobi-port-connection-<?php echo $this->uniqId; ?> .lifecycle-div .jstree-default .jstree-anchor {
        white-space: nowrap;
        width: inherit;
    }
    .pkiRenderBp_<?php echo $this->uniqId; ?> .meta-toolbar, .rightSideRenderBp_<?php echo $this->uniqId; ?> .meta-toolbar{
        background: none !important;
        position: unset !important;
        width: 100% !important;
        display: flex !important;
        margin: 6px 0 !important;
    }
    .pkiRenderBp_<?php echo $this->uniqId; ?> .meta-toolbar .bp-btn-back, .pkiRenderBp_<?php echo $this->uniqId; ?> .meta-toolbar .card-subject-blue, 
    .rightSideRenderBp_<?php echo $this->uniqId; ?> .meta-toolbar .card-subject-blue{
        display: none !important;
    }
    <?php if ($this->isEdit === 'false') : ?>
        .pkiRenderBp_<?php echo $this->uniqId; ?> .meta-toolbar, .rightSideRenderBp_<?php echo $this->uniqId; ?> .meta-toolbar{
            display: none !important;
        }
    <?php endif; ?>
</style>
<script type="text/javascript">
    $(function () {
        $taskTabMetaDataId = '<?php echo $this->taskTabMetaDataId ?>';
        $pkiTabMetaDataId = '<?php echo $this->pkiTabMetaDataId ?>';
        $taskid = '<?php echo $this->taskId; ?>';
        $selectedRow = '<?php echo (isset($this->selectedRow) && $this->selectedRow) ? $this->selectedRow : '' ?>';
        
        $.getStylesheet(URL_APP + 'middleware/assets/css/mobi/lifecycle.css');
        
        if ((typeof lifecycle === 'undefined' && typeof IS_LOAD_ASSET_MOBI_SCRIPT === 'undefined') || typeof connectionAssets !== 'undefined') {
            $.getScript(URL_APP + 'middleware/assets/js/mobi/assets.js', function () {
                $.getStylesheet(URL_APP + 'middleware/assets/css/lifecycle/lifecycle.css');
                connectionAssets.init('<?php echo $this->uniqId; ?>', '<?php echo $this->metaDataId ?>', '<?php echo (isset($this->selectedRow) && $this->selectedRow) ? $this->selectedRow : '' ?>', '<?php echo $this->taskTabMetaDataId ?>', '<?php echo $this->pkiTabMetaDataId ?>', '', '<?php echo $this->taskId; ?>', '<?php echo $this->isEdit ?>', '<?php echo issetParam($this->selectedTreeId) ?>');
            });
        } else {
            if (typeof IS_LOAD_ASSET_MOBI_SCRIPT === 'undefined') {
                $.getScript(URL_APP + "middleware/assets/js/mobi/assets.js", function () {
                    connectionAssets.init('<?php echo $this->uniqId; ?>', '<?php echo $this->metaDataId ?>', '<?php echo (isset($this->selectedRow) && $this->selectedRow) ? $this->selectedRow : '' ?>', '<?php echo $this->taskTabMetaDataId ?>', '<?php echo $this->pkiTabMetaDataId ?>', '', '<?php echo $this->taskId; ?>', '<?php echo $this->isEdit ?>', '<?php echo issetParam($this->selectedTreeId) ?>');
                });
            } else {
                connectionAssets.init('<?php echo $this->uniqId; ?>', '<?php echo $this->metaDataId ?>', '<?php echo (isset($this->selectedRow) && $this->selectedRow) ? $this->selectedRow : '' ?>', '<?php echo $this->taskTabMetaDataId ?>', '<?php echo $this->pkiTabMetaDataId ?>', '', '<?php echo $this->taskId; ?>', '<?php echo $this->isEdit ?>', '<?php echo issetParam($this->selectedTreeId) ?>');
            }
        }
    });

    function connectionAssetsTab_<?php echo $this->uniqId ?>(element) {
        connectionAssets.tabRender(element);
    }
    function assetPkiTab_<?php echo $this->uniqId ?>(renderDiv) {
        connectionAssets.processTabRender(renderDiv);
    }
    function assetTaskTab_<?php echo $this->uniqId ?>(element, renderDiv) {
        connectionAssets.dataViewTabRender(element, renderDiv);
    }

    function callConnectionPort_<?php echo $this->uniqId ?>(element, assetId, uniqId, locationId, directorypath, checkkeyid, connectionId, isstart, installationId) {
        connectionAssets.formRender(element, assetId, uniqId, locationId, directorypath, checkkeyid, connectionId, isstart, installationId);
    }

    function deleteConnectionPort_<?php echo $this->uniqId ?>(element, assetId, uniqId, locationId, directorypath, checkkeyid, connectionId, isstart, installationId) {
        
        var $dialogConfirm = 'dialog-confirm-<?php echo $this->uniqId ?>';
        if (!$("#" + $dialogConfirm).length) {
            $('<div id="' + $dialogConfirm + '"></div>').appendTo('body');
        }
        var $dialog = $("#" + $dialogConfirm);

        $dialog.empty().append('Устгахдаа итгэлтэй байна уу?');
        $dialog.dialog({
            cache: false,
            resizable: false,
            bgiframe: true,
            autoOpen: false,
            title: 'Баталгаажуулалт',
            width: 400,
            height: "auto",
            modal: true,
            close: function () {
                $dialog.empty().dialog('close');
            },
            buttons: [
                {text: plang.get('yes_btn'), class: 'btn green-meadow btn-sm', click: function () {
                    $.ajax({
                        type: 'post',
                        url: 'mdasset/deleteConnectionPort',
                        data: {
                            assetId: assetId,
                            locationId: locationId,
                            directorypath: directorypath,
                            checkkeyid: checkkeyid,
                            connectionId: connectionId,
                            isstart: isstart,
                            installationId: installationId
                        },
                        dataType: 'json',
                        beforeSend: function () {
                            Core.blockUI({
                                message: 'Loading...',
                                boxed: true
                            });
                        },
                        success: function (data) {
                            var $parentTr = $(element).closest('tr');
                            $parentTr.find('.target-remove').html('');
                            $(element).remove();
                            $dialog.dialog('close');
                            Core.unblockUI();
                        },
                        error: function (jqXHR, exception) {
                            var msg = '';
                            if (jqXHR.status === 0) {
                                msg = 'Not connect.\n Verify Network.';
                            } else if (jqXHR.status == 404) {
                                msg = 'Requested page not found. [404]';
                            } else if (jqXHR.status == 500) {
                                msg = 'Internal Server Error [500].';
                            } else if (exception === 'parsererror') {
                                msg = 'Requested JSON parse failed.';
                            } else if (exception === 'timeout') {
                                msg = 'Time out error.';
                            } else if (exception === 'abort') {
                                msg = 'Ajax request aborted.';
                            } else {
                                msg = 'Uncaught Error.\n' + jqXHR.responseText;
                            }

                            PNotify.removeAll();
                            new PNotify({
                                title: 'Error',
                                text: msg,
                                type: 'error',
                                sticker: false
                            });
                            
                            Core.unblockUI();
                        }
                    });
                }},
                {text: plang.get('no_btn'), class: 'btn blue-madison btn-sm', click: function () {
                    $dialog.dialog('close');
                }}
            ]
        });
        $dialog.dialog('open');
    }

    function initLifeCycleListTree_<?php echo $this->uniqId ?>() {
        connectionAssets.initLifeCycleListTree('tab', '');
    }

    function callProcessLifeCycle_<?php echo $this->uniqId ?>(passPath, mainMetaDataId, processMetaDataId, metaTypeId, whereFrom, elem, params, wfmStatusParams, drillDownType, mainRow, mainDataGrid, dataviewUniqid, path, appendHTml, callbackFunction) {
        var _params = '', ticketDataGrid = false, timeoutMseconds = 0, isMainDv = false;

        var $this = $(elem), dataGrid = $('.lifecycle-div');

        if (typeof wfmStatusParams === 'undefined') {
            var wfmStatusParams = '';
        }

        if (typeof drillDownType === 'undefined') {
            var drillDownType = '';
        }

        var $mainUniqId = '', $mainMetaDataId = '', $mainStatusId = '';

        if (wfmStatusParams !== '' && typeof $this.attr('data-mainmetaDataId') !== 'undefined' && typeof $this.attr('data-mainuniqId') !== 'undefined') {
            $mainMetaDataId = $this.attr('data-mainmetaDataId');
            $mainStatusId = $this.attr('data-mainstatusId');
            $mainUniqId = $this.attr('data-mainuniqId');
        }

        setTimeout(function () {
            var rows = getDataViewSelectedRowsByElement(dataGrid);

            privateTransferProcessAction(mainMetaDataId, processMetaDataId, metaTypeId, whereFrom, elem, params, dataGrid, wfmStatusParams, drillDownType, false, mainRow, $mainStatusId, $mainUniqId, rows, dataviewUniqid, path, appendHTml, 'initLifeCycleListTree_<?php echo $this->uniqId ?>');

        }, timeoutMseconds);


    }
    function explorerRefresh_<?php echo $this->uniqId; ?>(elem) {
        connectionAssets.initLifeCycleListTree('tab', $selectedRow);
    }
</script>