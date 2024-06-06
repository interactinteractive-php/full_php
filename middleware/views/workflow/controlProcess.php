<div class="col-md-12" id="metaProcessWindow">
    <form class="form-horizontal" role="form" method="post" id="metaProcess-form">
        <div class="portlet light shadow">
            <div class="portlet-title">
                <div class="caption buttons">
                    <?php
                    echo html_tag('a',
                            array(
                        'href' => 'javascript:history.back();',
                        'class' => 'btn btn-circle btn-secondary portlet-subject-btn-border',
                        'style' => ''
                            ), '<i class="fa fa-arrow-left portlet-subject-blue"></i>', true
                    );
                    ?>     
                </div>
                <div class="caption ml10">
                    <span class="caption-subject font-weight-bold uppercase portlet-subject-blue">
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
            <div class="portlet-body">
                <div class="portlet">
                    <div class="col-md-12">
                        <div class="form-group row fom-row">
                            <label class="col-md-3 col-sm-3 col-xs-3 col-form-label">Бизнес процесс: </label>
                            <div class="col-md-9 col-sm-9 col-xs-9">
                                <div class="col-md-7 col-sm-7 col-xs-6">
                                    <?php
                                    echo Form::select(array(
                                        'name' => 'mainBpId',
                                        'id' => 'mainBpId',
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
                                <div class="col-md-2 col-sm-2 col-xs-2">
                                    <div class="btn-group btn-group-devided">
                                        <button type="button" class="btn green btn-circle btn-sm" id="mainBpParamInput" title="Оролтын параметр"><i class="fa fa-download"></i></button>
                                        <button type="button" class="btn blue btn-circle btn-sm" id="mainBpParamOutput" title="Гаралтын параметр"><i class="fa fa-upload"></i></button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="mt20">
                            <div id="metaProcessDetial" class="row w-100"></div>
                        </div>
                    </div>

                    <div class="clearfix w-100"></div>
                </div>
            </div>
        </div>
    </form>
</div>    


<script type="text/javascript">
    var metaProcessWindowId="#metaProcessWindow";
    $(function(){
        var $metaProcessDetial=$('#metaProcessDetial');
        var $mainBpId=$('#mainBpId');
        $metaProcessDetial.on('click', '.addVisualMetaData', function(){            
            commonMetaDataGrid('multi', 'metaGroup', 'autoSearch=1&metaTypeId=<?php echo Mdmetadata::$businessProcessMetaTypeId; ?>|<?php echo Mdmetadata::$expressionMetaTypeId; ?>');
        });

        $metaProcessDetial.on('click', '.saveVisualParam', function(){
            saveVisualMetaData('', $mainBpId.val());
        });

<?php if ($this->mainBpId != ''): ?>
            $mainBpId.val(<?php echo $this->mainBpId; ?>).trigger("change");
            //drawWorkFlowListHtml($(this).val());    //control form-r zurj uzuulne
            viewVisualHtmlMetaData(<?php echo $this->mainBpId; ?>);    //visual data zurj uzuulne
<?php endif; ?>

        $mainBpId.on('change', function(){
            if($(this).val() != ''){
                //drawWorkFlowListHtml($(this).val());    //control form-r zurj uzuulne
                viewVisualHtmlMetaData($(this).val());    //visual data zurj uzuulne
            } else {
                $metaProcessDetial.empty();
            }
        });

        jsPlumb.bind("dblclick", function(connection, originalEvent){

            var $dialogName='dialog-bp-process';
            if(!$("#" + $dialogName).length){
                $('<div id="' + $dialogName + '"></div>').appendTo('body');
            }
            sourceBpOrder=0;
            if(connection.sourceId != 'startObject001'){
                var doneBpObject=jsPlumb.getSelector('#' + connection.sourceId + ' a');
                var sourceBpOrder=doneBpObject.find('div.wfIcon').attr('data-bporder');
            }

            var doBpObject=jsPlumb.getSelector('#' + connection.targetId + ' a');
            var targetBpOrder=doBpObject.find('div.wfIcon').attr('data-bporder');

            if(connection.targetId != 'endObject001'){
                var sourceId='';
                var targetId='';
                var mainBpId=$mainBpId.val();

                if(connection.sourceId != 'startObject001'){
                    sourceId=mainBpId;
                }

                $.cachedScript('assets/custom/addon/plugins/codemirror/lib/codemirror.min.js').done(function(){
                    $.ajax({
                        type: 'post',
                        url: 'mdprocessflow/bpCriteria',
                        data: {mainBpId: mainBpId, sourceId: connection.sourceId, targetId: connection.targetId, targetBpOrder: targetBpOrder, sourceBpOrder},
                        dataType: 'json',
                        beforeSend: function(){
                            if (!$("link[href='assets/custom/addon/plugins/codemirror/lib/codemirror.v1.css']").length){
                                $("head").append('<link rel="stylesheet" type="text/css" href="assets/custom/addon/plugins/codemirror/lib/codemirror.v1.css"/>');
                            }
                            Core.blockUI({
                                animate: true
                            });
                        },
                        success: function(data){
                            $("#" + $dialogName).empty().html(data.Html);
                            $("#" + $dialogName).dialog({
                                cache: false,
                                resizable: true,
                                bgiframe: true,
                                autoOpen: false,
                                title: data.Title,
                                width: 600,
                                minWidth: 600,
                                height: 420,
                                modal: true,
                                close: function(){
                                    $("#" + $dialogName).empty().dialog('close');
                                },
                                buttons: [
                                    {text: data.save_btn, class: 'btn btn-sm green', click: function(){
                                            bpCriteriaEditorParam.save();
                                            $.ajax({
                                                type: 'post',
                                                url: 'mdprocessflow/saveBpCriteria',
                                                data: $("#brcriteria-form", "#" + $dialogName).serialize(),
                                                dataType: 'json',
                                                beforeSend: function(){
                                                    Core.blockUI({
                                                        animate: true
                                                    });
                                                },
                                                success: function(data){
                                                    if(data.status === 'success'){
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
                                                error: function(){
                                                    alert("Error");
                                                }
                                            });
                                        }},
                                    {text: data.close_btn, class: 'btn btn-sm blue-hoki', click: function(){
                                            $("#" + $dialogName).dialog('close');
                                        }}
                                ]
                            });
                            $("#" + $dialogName).dialog('open');
                            Core.unblockUI();
                        },
                        error: function(){
                            alert("Error");
                        }
                    }).done(function(){
                        bpCriteriaEditorParam.refresh();
                        Core.initAjax($("#" + $dialogName));
                    });
                });
            } else {
                $("#" + $dialogName).html('Төгсгөлийн бизнес процесс criteria тохируулах боломжгүй');
                $("#" + $dialogName).dialog({
                    cache: false,
                    resizable: true,
                    bgiframe: true,
                    autoOpen: false,
                    title: 'Сануулах',
                    width: '300',
                    height: 'auto',
                    modal: true,
                    buttons: [
                        {text: 'Хаах', class: 'btn blue-madison btn-sm', click: function(){
                                $("#" + $dialogName).dialog('close');
                            }}
                    ]
                });
                $("#" + $dialogName).dialog('open');
            }

        });

//        jsPlumb.bind("contextmenu", function(conn, originalEvent) {
//            $.contextMenu({
//                selector: '._jsPlumb_connector',
//                callback: function (key, opt) {
//                    if (key === '_jsPlumb_connector') {
//                        jsPlumb.detach(conn); 
//                    }
//                },
//                items: {
//                    "_jsPlumb_connector": {name: "Сум устгах", icon: "trash"}
//                }
//            });
//        });

        $metaProcessDetial.on('click', '.previewMeta', function(){
            viewVisualMetaData();
        });

        $metaProcessDetial.on('click', '#bpChild .extra', function(){
            callMetaParameter($mainBpId.val(), $(this).attr('data-id'));
        });
        $metaProcessDetial.on('click', '#bpChild .IS_START', function(){
            $('.IS_START').val(0);
            $(this).val($(this).attr('data-id'));
        });
        $metaProcessDetial.on('click', '.saveMeta', function(){
            $.ajax({
                type: 'post',
                url: 'mdprocessflow/saveMetaProcess',
                data: $('#metaProcess-form').serialize(),
                dataType: "json",
                beforeSend: function(){
                    Core.blockUI({
                        animate: true
                    });
                },
                success: function(data){
                    if(data.status === 'success'){
                        new PNotify({
                            title: data.status,
                            text: 'Амжилттай хадгаллаа',
                            type: data.status,
                            sticker: false
                        });
                        //submitMainProcess($mainBpId.val());
                        //drawWorkFlowListHtml($mainBpId.val());//control form-r zurj uzuulne
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
                error: function(){
                    //alert("Error");
                    $.unblockUI();
                    new PNotify({
                        title: 'Анхааруулга',
                        text: 'Хадгалах боломжгүй',
                        type: 'error',
                        sticker: false
                    });
                }
            }).done(function(){
                Core.initAjax();
            });

        });
        $("button#mainBpParamOutput").on("click", function(){
            if($mainBpId.val().length > 0){
                var dialogName='#bpChildDialog';
                if(!$(dialogName).length){
                    $('<div id="' + dialogName.replace('#', '') + '"></div>').appendTo('body');
                }

                $.ajax({
                    type: 'post',
                    url: 'mdprocessflow/getOutputMetaParameterByProcess',
                    data: {mainBpId: $('select#mainBpId option:selected').val()},
                    beforeSend: function(){
                        Core.blockUI({
                            animate: true
                        });
                    },
                    success: function(data){
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
                                {text: 'Хадгалах', class: 'btn blue btn-sm', click: function(){
                                        $.ajax({
                                            type: 'post',
                                            url: 'mdprocessflow/saveMetaProcessParameter',
                                            data: $('#inputParameter-form').serialize(),
                                            dataType: "json",
                                            beforeSend: function(){
                                                Core.blockUI({
                                                    animate: true
                                                });
                                            },
                                            success: function(data){
                                                PNotify.removeAll();
                                                if(data.status === 'success'){
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
                                            error: function(){
                                                new PNotify({
                                                    title: 'Error',
                                                    text: 'error',
                                                    type: 'error',
                                                    sticker: false
                                                });
                                            }
                                        }).done(function(){
                                            Core.initAjax();
                                        });
                                    }},
                                {text: 'Хаах', class: 'btn grey-cascade btn-sm', click: function(){
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
                        $.unblockUI();
                    },
                    error: function(){
                        alert("Error");
                    }
                });

            } else {
                warningMsgChooseMainBp();
            }

        });
        $("button#mainBpParamInput").on("click", function(){
            if($mainBpId.val().length > 0){
                mainBpId=$mainBpId.val();
                doBpId=$mainBpId.val();
                callMetaParameter(mainBpId, doBpId);
            } else {
                warningMsgChooseMainBp();
            }

        });

    });

    function warningMsgChooseMainBp(){
        var dialogName='#warningMsgDialog';
        if(!$(dialogName).length){
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
                {text: 'Хаах', class: 'btn grey-cascade btn-sm', click: function(){
                        $(dialogName).dialog('close');
                    }}
            ]
        });
        $(dialogName).dialog('open');
    }

    function processDrillDown(elem){
        var $this=$(elem);
        window.open('mdprocessflow/metaProcess/' + $this.attr("id"), '_blank');
    }
</script>
