<div id="metaProcessWindow">


    <form class="form-horizontal" role="form" method="post" id="metaProcess-form">
        <div style="display: none;">
            <?php
            echo Form::select(array(
                'name' => 'dataModelId',
                'id' => 'dataModelId',
                'class' => 'form-control select2me',
                'data' => array(array('META_DATA_ID' => $this->dataModelId)),
                'op_value' => 'META_DATA_ID',
                'op_text' => 'META_DATA_ID',
                'value' => $this->dataModelId
            ));
            echo Form::select(array(
                'name' => 'lcBookId',
                'id' => 'lcBookId',
                'class' => 'form-control select2me',
                'data' => array(array('LC_BOOK_ID' => $this->lcBookId)),
                'op_value' => 'LC_BOOK_ID',
                'op_text' => 'LC_BOOK_ID',
                'value' => $this->lcBookId
            ));
            ?>
        </div>
        <?php
        echo Form::hidden(array('id' => 'lifecycleId', 'name' => 'lifecycleId', 'value' => $this->lifecycleId));

        echo Form::hidden(array('id' => 'selectedObject', 'name' => 'selectedObject'));
        echo Form::hidden(array('id' => 'selectedObjectName', 'name' => 'selectedObjectName'));
        echo Form::hidden(array('id' => 'selectedLifeCycleId', 'name' => 'selectedLifeCycleId', 'value' => $this->lifecycleId));
        echo Form::hidden(array('id' => 'sourceId', 'name' => 'sourceId', 'value' => 0));
        ?>
        <div class="col-md-12 center-sidebar">

            <div class="form-group row fom-row mb10">
                <label class="col-md-2 col-sm-2 col-xs-3 col-form-label align-left pt0">Entity: </label>
                <div class="col-md-10 col-sm-10 col-xs-9 font-weight-bold align-left">
                    <?php echo $this->dataModelName; ?>
                </div>
            </div>
            <div class="clearfix w-100"></div>
            <div class="form-group row fom-row">
                <label class="col-md-2 col-sm-2 col-xs-3 col-form-label align-left pt0">Lifecycle Book: </label>
                <div class="col-md-10 col-sm-10 col-xs-9 font-weight-bold align-left"><?php echo $this->lcBookName; ?> (<?php echo $this->lifecycleName; ?>)</div>
            </div>
            <div class="clearfix w-100"></div>
            <div id="metaProcessDetial" class="row w-100"></div>
        </div>
        <div class="right-sidebar" data-status="closed">
            <div class="stoggler sidebar-right">
                <span style="display: none;" class="fa fa-chevron-right">&nbsp;</span> 
                <span style="display: block;" class="fa fa-chevron-left">&nbsp;</span>
            </div>
            <div class="right-sidebar-content">
                <div class="card light bg-blue-hoki">
                    <div class="card-body">
                        <div class="clearfix w-100">
                            <a href="javascript:;" class="float-left thumb avatar border m-r">
                                <?php echo Ue::getSessionPhoto('class="rounded-circle"'); ?>
                            </a>
                            <div class="clear">
                                <div class="h4 mt5 mb5 text-color-white">
                                    <?php echo Ue::getSessionPersonName(); ?>                
                                </div>
                                <small class="text-muted text-color-white"><?php echo Ue::getSessionPositionName(); ?></small>
                            </div>
                        </div>
                    </div>
                </div>
                <p class="property_page_title"></p>
                <span id="processConfig" style="display: none;">
                    <div class="property_page_title"><span>Тохиргооны мэдээлэл</span></div>
                    <div class="mb10"></div>
                    <div class="panel panel-default bg-inverse grid-row-content taskFlowMainConfig">
                        <table class="table sheetTable">
                            <tbody>                  
                            </tbody>
                        </table>
                    </div>
                    <div class="property_page_title">
                        <span class="float-left">Criteria config</span>
                        <span class="float-right"><button type="button" class="btn grey-cascade btn-circle btn-sm metaDmPeriodicLimit" onclick="addMetaDmBehaviourDtl()" title="Ажиллах дараалалын тохиргоо"><i class="icon-plus3 font-size-12"></i> <?php echo $this->lang->line('META_00103'); ?></button></span>
                        <div class="clearfix w-100"></div>
                    </div>
                    <div class="mb10"></div>
                    <div class="panel panel-default bg-inverse grid-row-content metaDmBehaviourDtlConfig">
                        <table class="table sheetTable">
                            <tbody></tbody>
                        </table>
                    </div>
                </span>     

            </div>
        </div>                           
    </form>
</div>


<script type="text/javascript">
    viewVisualHtmlMetaData('<?php echo $this->dataModelId; ?>', '<?php echo $this->lifecycleId; ?>');
    var metaProcessWindowId = "#metaProcessWindow";
    $(function () {
        $("#dataModelId", metaProcessWindowId).select2("val", "<?php echo $this->dataModelId; ?>");
        showRenderSidebar(metaProcessWindowId);
        $('#metaProcessDetial', metaProcessWindowId).on('click', '.addVisualMetaData', function () {
            commonMetaDataGrid('multi', 'metaGroup', 'autoSearch=1&metaTypeId=200101010000011|200101010000015');
        });
        $('#metaProcessDetial', metaProcessWindowId).on('click', '.saveVisualParam', function () {
            saveVisualMetaData($("#dataModelId", metaProcessWindowId).select2("val"), $("#lcBookId", metaProcessWindowId).select2("val"), $("#selectedLifeCycleId", metaProcessWindowId).val());
        });

        $('#metaProcessDetial').on('click', '.previewMeta', function () {
            viewVisualMetaData();
        });

        $('#metaProcessDetial').on('click', '#bpChild .extra', function () {
            callMetaParameter($("#mainBpId").select2('val'), $(this).attr('data-id'));
        });
        $('#metaProcessDetial').on('click', '#bpChild .IS_START', function () {
            $('.IS_START').val(0);
            $(this).val($(this).attr('data-id'));
        });
        $('#metaProcessDetial').on('click', '.saveMeta', function () {
            $.ajax({
                type: 'post',
                url: 'mdtaskflow/saveMetaProcess',
                data: $('#metaProcess-form').serialize(),
                dataType: "json",
                beforeSend: function () {
                    Core.blockUI({
                        animate: true
                    });
                },
                success: function (data) {
                    if (data.status === 'success') {
                        new PNotify({
                            title: data.status,
                            text: 'Амжилттай хадгаллаа',
                            type: data.status,
                            sticker: false
                        });
                        //submitMainProcess($('#mainBpId').val());
                        //drawWorkFlowListHtml($('#mainBpId').val());//control form-r zurj uzuulne
                        //viewVisualHtmlMetaData();    //visul data zurj uzuulne
                    } else {
                        new PNotify({
                            title: data.status,
                            text: data.text,
                            type: data.status,
                            sticker: false
                        });
                    }
                    //saveMetaProcess
                    $.unblockUI();
                },
                error: function () {
                    //alert("Error");
                    $.unblockUI();
                    new PNotify({
                        title: 'Анхааруулга',
                        text: 'Хадгалах боломжгүй',
                        type: 'error',
                        sticker: false
                    });
                }
            }).done(function () {
                Core.initAjax();
            });
        });
    });


    function metaDmPeriodicLimitShow() {
        var dataModelId = $("#dataModelId", metaProcessWindowId).select2('val');
        var selectedLifeCycleId = $("#selectedLifeCycleId", metaProcessWindowId).val();
        if (selectedLifeCycleId != '' && dataModelId != '') {
            $(".metaDmPeriodicLimit", metaProcessWindowId).show();
        }

    }
    function emptyLifeCycle() {
        $("#parent_lifecycle", metaProcessWindowId).empty();
        $("#lifecycle_tree", metaProcessWindowId).empty();
        $("#lifecycle_tree", metaProcessWindowId).append('<input type="hidden" id="sub_lifecycle" name="sub_lifecycle" class="form-control select2"/>');
        $("#metaProcessDetial", metaProcessWindowId).empty();
        $("span#processConfig", metaProcessWindowId).hide();
    }
</script>
