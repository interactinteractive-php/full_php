<div class="card p-0">
    <div class="card-body" style="background: #FFF;">
        <div class="clearfix w-100"></div>
        <div class="col-md-12 row" id="asset-mobi-port-connection-<?php echo $this->uniqId ?>" data-assetid="<?php echo issetParam($this->assetId) ?>">
            <div class="col-md-4 col-sm-12 col-xs-12 pad-0 lifecycle-toggler-left"  style="background: #FFF; min-height: 400px">
                <div class="lifecycle-div lifecycle-common-div not-datagrid" id="lifecycle_div-<?php echo $this->uniqId; ?>" data-id="<?php echo $this->metaDataId . '-' . $this->uniqId; ?>">
                 <!--<h4 class="lifecycle-title cursorPointer lifecycle-toggler" data-toggler="collapse"><?php echo $this->lang->line('menu_001'); ?> <i class="fa fa-chevron-circle-left"></i></h4>-->
                    <div class="btn-group btn-group-devided pr4" >
                        <a class="btn btn-success btn-circle btn-sm" title="Сайтад төхөөрөмж бүртгэх" data-advanced-criteria="" onclick="callProcessLifeCycle_<?php echo $this->uniqId; ?>('', '<?php echo $this->metaDataId ?>', '1533139042269', '200101010000011', 'toolbar', this, {callerType: 'mobSiteEquipmentDropList'});" data-actiontype="insert" data-dvbtn-processcode="MOB_CHECK_KEY_DV_006" href="javascript:;"><i class="icon-plus3 font-size-12"></i> </a>
                        <a class="btn btn-warning btn-circle btn-sm" title="Сайтад төхөөрөмж бүртгэх" data-advanced-criteria="" onclick="callProcessLifeCycle_<?php echo $this->uniqId; ?>('', '<?php echo $this->metaDataId ?>', '1533139065225', '200101010000011', 'toolbar', this, {callerType: 'mobSiteEquipmentDropList'});" data-actiontype="update" data-dvbtn-processcode="MOB_CHECK_KEY_DV_007" href="javascript:;"><i class="fa fa-edit"></i> </a>
<!--                        <a class="btn red-thunderbird btn-circle btn-sm" title="Сайтын төхөөрөмж хураах" data-advanced-criteria="" onclick="callProcessLifeCycle_<?php echo $this->uniqId; ?>('', '<?php echo $this->metaDataId ?>', '1535617006997', '200101010000011', 'toolbar', this, {callerType: 'mobSiteEquipmentDropList'});" data-actiontype="update" data-dvbtn-processcode="MOB_CHECK_KEY_DV_0010" href="javascript:;"><i class="fa fa-minus-circle"></i> </a>
                        <a class="btn btn-danger btn-circle btn-sm" title="Устгах" data-advanced-criteria="" onclick="callProcessLifeCycle_<?php echo $this->uniqId; ?>('', '<?php echo $this->metaDataId ?>', '1533139064244', '200101010000011', 'toolbar', this, {callerType: 'mobSiteEquipmentDropList'});" data-actiontype="delete" data-dvbtn-processcode="MOB_CHECK_KEY_DV_005" href="javascript:;"><i class="fa fa-trash"></i> </a>-->
                        <a class="btn btn-info btn-circle btn-sm" title="Дахин ачааллах" onclick="connectionAssets.init('<?php echo $this->uniqId; ?>', '<?php echo $this->metaDataId ?>', '<?php echo (isset($this->selectedRow) && $this->selectedRow) ? $this->selectedRow : '' ?>', '<?php echo $this->taskTabMetaDataId ?>', '<?php echo $this->pkiTabMetaDataId ?>', '', '<?php echo $this->taskId; ?>');" href="javascript:;"><i class="fa fa-refresh"></i> </a>
                        <?php echo Form::hidden(array('name' => 'dataview-criteria-params-' . $this->metaDataId, 'id' => 'dataview-criteria-params-' . $this->metaDataId, 'value' => 'parentLocationId=')); ?>
                    </div>
                    <div class="lifecycle-tree">
                        <div id="left-tree-list-<?php echo $this->uniqId; ?>" class="lifecycle-common-div lifecycle-selected-t lifecycle-dv-<?php echo $this->uniqId; ?> "></div>
                        <div id="left-tree-list-adjacent_<?php echo $this->uniqId; ?>"></div>
                    </div>
                </div>
            </div>
            <div class="col-md-8 col-sm-12 col-xs-12 pr0 pt10 lifecycle-toggler-right mt20" style="background: #FFF;  min-height: 400px">
                <div id="rightSideDv_<?php echo $this->uniqId; ?>" class="lifecycle-common-right">
                    <div class="col-md-12">
                        <div class="rightsideSite rightSideRenderBp_<?php echo $this->uniqId; ?>" data-uniqid="<?php echo $this->uniqId; ?>"></div>
                    </div>
                    <div class="clearfix w-100"></div>
                </div>
                <?php echo Form::hidden(array('name' => 'wfm-status-params-' . $this->uniqId, 'id' => 'wfm-status-params-' . $this->metaDataId, 'value' => $this->wfmStatusParams)); ?>
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
    #left-tree-list-<?php echo $this->uniqId; ?>{
        height: 400px;
        overflow:scroll;  
    }
    .lifecycle-dv-<?php echo $this->uniqId; ?> .lifecycle-div .jstree-default .jstree-anchor {
        width: initial !important;    
    }
    .rightSideRenderBp_<?php echo $this->uniqId; ?> .meta-toolbar{
        background: none !important;
        display: none;
        position: unset !important;
        width: 100% !important;
        display: flex !important;
        margin: 6px 0 !important;
    }
</style>
<script type="text/javascript">
    
    $(function () {
        $taskTabMetaDataId = '<?php echo $this->taskTabMetaDataId ?>';
        $pkiTabMetaDataId = '<?php echo $this->pkiTabMetaDataId ?>';
        $selectedRow = '<?php echo (isset($this->selectedRow) && $this->selectedRow) ? $this->selectedRow : '' ?>';
        $taskid = '<?php echo $this->taskId; ?>';
        $.getStylesheet(URL_APP + 'middleware/assets/css/mobi/lifecycle.css');
        if (typeof lifecycle === 'undefined') {
            $.getScript(URL_APP + 'middleware/assets/js/mobi/assets.js', function () {
                $.getStylesheet(URL_APP + 'middleware/assets/css/lifecycle/lifecycle.css');
                connectionAssets.init('<?php echo $this->uniqId; ?>', '<?php echo $this->metaDataId ?>', '<?php echo (isset($this->selectedRow) && $this->selectedRow) ? $this->selectedRow : '' ?>', '<?php echo $this->taskTabMetaDataId ?>', '<?php echo $this->pkiTabMetaDataId ?>', 'dialog', '<?php echo $this->taskId; ?>');
            });
        } else {
            connectionAssets.init('<?php echo $this->uniqId; ?>', '<?php echo $this->metaDataId ?>', '<?php echo (isset($this->selectedRow) && $this->selectedRow) ? $this->selectedRow : '' ?>', '<?php echo $this->taskTabMetaDataId ?>', '<?php echo $this->pkiTabMetaDataId ?>', 'dialog', '<?php echo $this->taskId; ?>');
        }
    });

    function initLifeCycleListTree_<?php echo $this->uniqId ?>() {
        connectionAssets.initLifeCycleListTree('dialog', $selectedRow);
    }

    function callProcessLifeCycle_<?php echo $this->uniqId ?>(passPath, mainMetaDataId, processMetaDataId, metaTypeId, whereFrom, elem, params, wfmStatusParams, drillDownType, mainRow, mainDataGrid, dataviewUniqid, path, appendHTml, callbackFunction) {
        var _params = '', ticketDataGrid = false, timeoutMseconds = 0, isMainDv = false;

        var $this = $(elem), dataGrid = $('#asset-mobi-port-connection-' + <?php echo $this->uniqId ?> + ' .lifecycle-div');

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
        connectionAssets.initLifeCycleListTree('dialog', $selectedRow);
    }
    
</script>