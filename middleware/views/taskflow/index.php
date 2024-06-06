<div class="col-md-12" id="metaProcessWindow">
    <form class="form-horizontal" role="form" method="post" id="metaProcess-form">
        <div class="card light shadow">
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
                        <a class="list-icons-item" data-action="collapse"></a>
                        <a class="list-icons-item" data-action="fullscreen"></a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12 center-sidebar">
                        <div class="form-group row fom-row">
                            <label class="col-md-3 col-sm-3 col-xs-3 col-form-label">Entity: </label>
                            <div class="col-md-9 col-sm-9 col-xs-9">
                                <div class="col-md-7 col-sm-7 col-xs-6">
                                    <?php
                                    echo Form::select(array(
                                        'name' => 'dataModelId',
                                        'id' => 'dataModelId',
                                        'class' => 'form-control select2me',
                                        'data' => $this->getMetaTypeProcessList,
                                        'op_value' => 'META_DATA_ID',
                                        'op_text' => 'META_DATA_CODE| |-| |META_DATA_NAME',
                                        'data-placeholder' => '- Сонгох -',
                                        'text' => ' ',
                                        'required' => 'required'
                                    ));
                                    ?>
                                </div>
                                
                                <div class="clearfix w-100"></div>
                            </div>
                        </div>
                        <div class="form-group row fom-row" id="lcbook" style="display: none;">
                            <label class="col-md-3 col-sm-3 col-xs-3 col-form-label">Lifecycle Book: </label>
                            <div class="col-md-9 col-sm-9 col-xs-9">
                                <div class="col-md-7 col-sm-7 col-xs-6">
                                    <?php
                                    echo Form::select(array(
                                        'name' => 'lcBookId',
                                        'id' => 'lcBookId',
                                        'class' => 'form-control select2me',
                                        'required' => 'required'
                                    ));
                                    ?>
                                </div>
                                <div class="clearfix w-100"></div>
                            </div>
                        </div>

                        <div id="parent_lifecycle"></div>
                        <div class="clearfix w-100"></div>
                        <div class="row mt20" style="width: 500px; margin-left: 0px">
                            <div id="lifecycle_tree">
                                <input type="hidden" id="sub_lifecycle" name="sub_lifecycle" class="form-control select2"/>
                            </div>
                        </div>
                        <div class="clearfix w-100"></div>
                        <div class="mt20">
                            <div id="metaProcessDetial" class="row w-100"></div>
                        </div>

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
                            <?php
                            echo Form::hidden(array('id' => 'selectedObject', 'name' => 'selectedObject'));
                            echo Form::hidden(array('id' => 'selectedObjectName', 'name' => 'selectedObjectName'));
                            echo Form::hidden(array('id' => 'selectedLifeCycleId', 'name' => 'selectedLifeCycleId'));
                            echo Form::hidden(array('id' => 'sourceId', 'name' => 'sourceId', 'value' => 0));
                            ?>

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
                                    <span class="float-right"><button type="button" class="btn grey-cascade btn-circle btn-sm metaDmPeriodicLimit" onclick="addMetaDmBehaviourDtl()" title="Ажиллах дараалалын тохиргоо"><i class="icon-plus3 font-size-12"></i> Нэмэх</button></span>
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
                    <div class="clearfix w-100"></div>
                </div>
            </div>
        </div>
    </form>
</div>    
<script type="text/javascript">
    var metaProcessWindowId = "#metaProcessWindow";
    $(function () {
        
        $(".right-sidebar", metaProcessWindowId).css("min-height", $(".right-sidebar-content", metaProcessWindowId).height()+"px");
        $("#dataModelId", metaProcessWindowId).select2("val", "<?php echo $this->dataModelId; ?>");
        
        lcBookFillOption('<?php echo $this->dataModelId; ?>');
        showRenderSidebar(metaProcessWindowId);
        $('#metaProcessDetial', metaProcessWindowId).on('click', '.addVisualMetaData', function () {
            commonMetaDataGrid('multi', 'metaGroup', 'autoSearch=1&metaTypeId=200101010000011|200101010000015');
        });
        $('#metaProcessDetial', metaProcessWindowId).on('click', '.saveVisualParam', function () {
            saveVisualMetaData($("#dataModelId", metaProcessWindowId).select2("val"), $("#lcBookId", metaProcessWindowId).select2("val"), $("#selectedLifeCycleId", metaProcessWindowId).val());
        });
        $('#dataModelId', metaProcessWindowId).on('change', function () {
            var dataModelId = $(this).select2('val');
            lcBookFillOption(dataModelId);
        });

        $('#lcBookId', metaProcessWindowId).on('change', function () {
            var lcBookId = $(this).select2('val');
            var dataModelId = $("#dataModelId").val();
            if (lcBookId != '') {
                $.ajax({
                    type: 'post',
                    url: 'mdtaskflow/getDMLifeCycleList',
                    data: {lcBookId: lcBookId},
                    dataType: "json",
                    beforeSend: function () {
                        Core.blockUI({
                            animate: true
                        });
                    },
                    success: function (data) {
                        if (data.status === 'error') {
                            new PNotify({
                                title: 'Error',
                                text: data.text,
                                type: 'error',
                                sticker: false
                            });
                        } else {

                            var parent = '<div class="wizard-steps" style="margin-left: 0">';
                            var j = 0;
                            var fClass = "first";
                            $.each(data.result, function (index) {
                                j = j + 1;
                                parent += '<div data-step="' + this.id + '" class="' + fClass + '">';
                                parent += '<a href="javascript:;">\n\
                                            <span class="badge">' + j + '</span>\n\
                                            <span class="badge">' + this.name + '</span>\n\
                                        </a>';
                                parent += '</div>';
                                fClass = "";
                            });
                            parent += "</div>";

                            $("#parent_lifecycle", metaProcessWindowId).html(parent);

                            $(".wizard-steps", metaProcessWindowId).find('div').click(function () {

                                $("span#processConfig", metaProcessWindowId).hide();
                                var step = $(this).data('step');
                                $(this).addClass('active-step');
                                $(".wizard-steps div.active-step").not(this).removeClass('active-step');

                                $.ajax({
                                    type: 'POST',
                                    dataType: 'json',
                                    url: "mdtaskflow/getChildLifecycle",
                                    data: {parent_id: step, source_id: 0},
                                    success: function (data) {
                                        $("#metaProcessDetial").empty();
                                        if (data != null) {
                                            $('#sub_lifecycle').select2({
                                                data: {results: data, text: function (item) {
                                                        return item.name;
                                                    }},
                                                id: 'id',
                                                tag: 'name',
                                                multiple: false,
                                                minimumResultsForSearch: -1,
                                                formatSelection: function (item) {
                                                    return item.name;
                                                },
                                                formatResult: function (item, container, query) {
                                                    return item.name;
                                                }
                                            }).on('select2-selecting', function (e) {
                                                $("#selectedLifeCycleId", metaProcessWindowId).val(e.val);
                                                viewVisualHtmlMetaData($("#lcBookId", metaProcessWindowId).val(), e.val);
                                                metaDmPeriodicLimitShow();
                                            });

                                        } else {
                                            $("#sub_lifecycle").select2('destroy');
                                            $("#selectedLifeCycleId", metaProcessWindowId).val(step);
                                            viewVisualHtmlMetaData($("#lcBookId", metaProcessWindowId).val(), step);
                                            metaDmPeriodicLimitShow();
                                        }
                                    },
                                    error: function (xhr, textStatus, error) {
                                        console.log(xhr.statusText);
                                        console.log(textStatus);
                                        console.log(error);
                                    },
                                    async: false
                                });
                            });

                            $.ajax({
                                type: 'post',
                                url: 'mdtaskflow/getDMLifeCycleParentChildId',
                                data: {lifeCycleId: "<?php echo $this->lcBookId; ?>"},
                                dataType: "json",
                                beforeSend: function () {
                                    Core.blockUI({
                                        animate: true
                                    });
                                },
                                success: function (data) {
                                    console.log(data);
                                }
                            });
                        }
                        Core.unblockUI();
                    },
                    error: function () {
                        alert("Error");
                        Core.unblockUI();
                    }
                }).done(function () {
                    Core.initAjax();
                });
            }
            emptyLifeCycle();
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

    function lcBookFillOption(dataModelId) {
        if (dataModelId !== '') {
            metaDmPeriodicLimitShow();
            $("#lcbook", metaProcessWindowId).show();
            $.ajax({
                type: 'post',
                url: 'mdtaskflow/getMetaDmLcBookList',
                data: {dataModelId: dataModelId},
                dataType: "json",
                beforeSend: function () {
                    Core.blockUI({
                        animate: true
                    });
                },
                success: function (data) {
                    PNotify.removeAll();
                    if (data.status === 'error') {
                        new PNotify({
                            title: 'Error',
                            text: data.text,
                            type: 'error',
                            sticker: false
                        });
                    } else {
                        var _cellSelect = $('#lcBookId', metaProcessWindowId);
                        $("option:gt(0)", _cellSelect).remove();
                        $.each(data, function () {
                            _cellSelect.append($("<option />").val(this.LC_BOOK_ID).text(this.LC_BOOK_CODE + ' - ' + this.LC_BOOK_NAME));
                        });
                    }
                    Core.unblockUI();
                },
                error: function () {
                    alert("Error");
                    Core.unblockUI();
                }
            }).done(function () {
                Core.initAjax();
            });
        }
        emptyLifeCycle();
    }
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
