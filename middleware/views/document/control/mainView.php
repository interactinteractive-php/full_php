<div class="erl-parent" data-id="" data-name="">    
    <div class="table-toolbar">
        <div class="row">
            <?php if (Config::getFromCache('CIVIL_OFFLINE_SERVER') !== '1') { ?>
                <?php if (($this->isShowBtn && $this->isShowBtn == '1') || ($this->isShowPrintBtn && $this->isShowPrintBtn == '1')) { ?>
                    <div class="col-md-12">
                        <div class="btn-group btn-group-devided pull-right">
                            <?php if ($this->isShowBtn == '1') { ?>
                                <span class="workflow-buttons-<?php echo $this->uniqid ?> workflow-buttons-<?php echo $this->id; ?>" style=""></span>
                            <?php } ?>
                            <?php if ($this->isShowPrintBtn == '1') { ?>
                                <a href="javascript:;" class="btn btn-default btn-circle btn-sm ml5" onclick="erlPrint(this, '<?php echo issetParam($this->postDatac) ?>')" id="">Хэвлэх</a>
                                <a href="javascript:;" class="btn btn-default btn-circle btn-sm ml5" onclick="erlPrintAll(this, '<?php echo issetParam($this->postDatac) ?>')" id="">Хэвлэх /Бүгд/</a>
                            <?php } ?>                    
                        </div>
                    </div>
                <?php } ?>
            <?php } ?>
        </div>
    </div>    
    <table style="table-layout: fixed; width: 100%; border-top: 1px #999 solid; border-bottom: 1px #999 solid; border-left: 1px #999 solid;">
        <tbody>
            <tr>
                <td style="width: 350px; vertical-align: top; padding: 0 10px 0 10px;">
                    <div class="ecl-height ecl-jstree-container"></div>
                    <hr style="margin: 8px 0;">
                    <table style="width: 100%; display:none;">
                        <tbody>
                            <tr>
                                <td>
                                    Регистрийн дугаар:
                                </td>
                                <td style="width: 32%">
                                    <strong><?php echo isset($this->row['stateregnumber']) ? issetVar($this->row['stateregnumber']) : ''; ?></strong>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    Овог:
                                </td>
                                <td>
                                    <strong><?php echo isset($this->row['lastname']) ? issetVar($this->row['lastname']) : ''; ?></strong>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    Нэр:
                                </td>
                                <td>
                                    <strong><?php echo isset($this->row['firstname']) ? issetVar($this->row['firstname']): ''; ?></strong>
                                </td>
                            </tr>
                        </tbody>
                    </table>                  
                    <div class="mt5" style="font-size: 11px;">
                        Бэлтгэсэн: <strong><?php echo isset($this->row['preparedfilecount']) ? issetVar($this->row['preparedfilecount']) : '0'; ?></strong> &nbsp;&nbsp;&nbsp;
                    </div>
                </td>
                <td style="width: 100%; vertical-align: top; padding: 0;">
                    <span style="position: absolute; cursor: pointer;" onclick="erlImgFullsize(this)" class="ml5 mt5"><i class="fa fa-search-plus"></i> Томоор харах</span>
                    <div class="erl-image-preview"></div>
                </td>
                <?php if (Config::getFromCache('CIVIL_OFFLINE_SERVER') !== '1') { ?>
                <td style="width: 350px; vertical-align: top; padding: 0;">
                    <div class="col-md-12 main-view-metadata-<?php echo $this->uniqid ?>">
                        <form class="xs-form">
                            <div class="col-md-12 cvl-bookMeta mt10"></div>
                        </form>
                    </div>
                </td>
                <?php } ?>
            </tr>
        </tbody>
    </table>
</div>
<?php if (Config::getFromCache('CIVIL_OFFLINE_SERVER') !== '1') { ?>
<div class="hidden hidden-html-<?php echo $this->uniqid ?>">
    <div id="metadata-<?php echo $this->uniqid ?>"></div>
</div>
<?php } ?>
<link rel="stylesheet" href="assets/custom/addon/plugins/jqTree/jqtree.css">

<style type="text/css">
    
    .erl-image-preview {
        padding: 20px;
        overflow: auto;
        border: 1px #999 solid;
        border-top: 0;
        border-bottom: 0;
        border-right: 0;
        background: #ccc;
        text-align: center;
    }    
    
    .erl-image-preview img {
        -webkit-box-shadow: 0px 0px 15px 3px rgba(0,0,0,0.3);
        -moz-box-shadow: 0px 0px 15px 3px rgba(0,0,0,0.3);
        box-shadow: 0px 0px 15px 3px rgba(0,0,0,0.3);
        max-width: 100%;
        /*max-height: 100%;*/
    }
    
    .ecl-height {
        overflow: auto;
    }
    
    .erl-content-tbl > tbody > tr {
        cursor: pointer;
    }
    
    .erl-content-tbl > tbody > tr.selected-row > td {
        background-color: #a8d3f3;
    }
    
</style>

<script type="text/javascript">
    
    $(function() {
    
        $(document).bind('keydown', 'Ctrl+s', function(e) {

            $('body').find('.workflow-buttons-<?php echo $this->uniqid ?> a[id="1532504728290422"]').trigger('click');

            e.preventDefault();
            return false;
        });
    
        Core.initDateInputByElement($('.erl-content-tbl').find('input[name="bookDate[]"]'));
        
        var row = <?php echo $this->rowJson; ?>;
        
<?php if (Config::getFromCache('CIVIL_OFFLINE_SERVER') !== '1') { ?>
        $.ajax({
            type: 'post',
            url: 'mdobject/getWorkflowNextStatus',
            data: {metaDataId: '<?php echo $this->metaDataId ?>', dataRow: row},
            dataType: "json",
            beforeSend: function() {
                Core.blockUI({
                    message: 'Loading...',
                    boxed: true
                });
            },
            success: function(response) {
                if (response.status === 'success') {
                    if (response.datastatus && response.data) {
                        var rowId = '';
                        if (typeof row.id !== 'undefined') {
                            rowId = row.id;
                        }
                        $.each(response.data, function (i, v) {
                            var advancedCriteria = '';
                            if (typeof v.advancedCriteria !== "undefined" && v.advancedCriteria !== null) {
                                advancedCriteria = ' data-advanced-criteria="' + v.advancedCriteria.replace(/\"/g, '') + '"';
                            }

                            if (typeof v.wfmusedescriptionwindow != 'undefined' && v.wfmusedescriptionwindow == '0' && typeof v.wfmuseprocesswindow != 'undefined' && v.wfmuseprocesswindow == '0') {
                                $('.workflow-buttons-<?php echo $this->id; ?>').append('<a href="javascript:;" ' + advancedCriteria + ' class="btn btn-circle btn-sm ml5" style="background-color: '+v.wfmstatuscolor+'; color: #fff;" onclick="changeWfmStatusId(this, \''+v.wfmstatusid+'\', \'<?php echo $this->metaDataId ?>\', \'<?php echo $this->refStructureId ?>\', \''+$.trim(v.wfmstatuscolor)+'\', \''+v.wfmstatusname+'\', {cyphertext: \'<?php echo $this->fileCount; ?>\', plainText: \'view mode\'});" id="'+ v.wfmstatusid +'">'+ v.wfmstatusname +'</a>'); 
                            } else {
                                if (typeof v.wfmstatusname != 'undefined' && v.wfmstatusname != '' && (v.wfmstatusprocessid == '' || v.wfmstatusprocessid == 'null' || v.wfmstatusprocessid == null)) {
                                    if (v.wfmisneedsign == '1') {
                                        $('.workflow-buttons-<?php echo $this->id; ?>').append('<a href="javascript:;" ' + advancedCriteria + ' class="btn btn-circle btn-sm ml5" style="background-color: '+v.wfmstatuscolor+'; color: #fff;" onclick="beforeSignChangeWfmStatusId(this, \''+v.wfmstatusid+'\', \'<?php echo $this->metaDataId ?>\', \'<?php echo $this->refStructureId ?>\', \''+$.trim(v.wfmstatuscolor)+'\', \''+v.wfmstatusname+'\');" id="'+ v.wfmstatusid +'">'+ v.wfmstatusname +' <i class="fa fa-key"></i></a>'); 
                                    } else if (v.wfmisneedsign == '2') {
                                        $('.workflow-buttons-<?php echo $this->id; ?>').append('<a href="javascript:;" ' + advancedCriteria + ' class="btn btn-circle btn-sm ml5" style="background-color: '+v.wfmstatuscolor+'; color: #fff;" onclick="beforeHardSignChangeWfmStatusId(this, \''+v.wfmstatusid+'\', \'<?php echo $this->metaDataId ?>\', \'<?php echo $this->refStructureId ?>\', \''+$.trim(v.wfmstatuscolor)+'\', \''+v.wfmstatusname+'\');" id="'+ v.wfmstatusid +'">'+ v.wfmstatusname +' <i class="fa fa-key"></i></a>'); 
                                    } else {
                                        $('.workflow-buttons-<?php echo $this->id; ?>').append('<a href="javascript:;" ' + advancedCriteria + ' class="btn btn-circle btn-sm ml5" style="background-color: '+v.wfmstatuscolor+'; color: #fff;" onclick="changeWfmStatusId(this, \''+v.wfmstatusid+'\', \'<?php echo $this->metaDataId ?>\', \'<?php echo $this->refStructureId ?>\', \''+$.trim(v.wfmstatuscolor)+'\', \''+v.wfmstatusname+'\', {cyphertext: \'<?php echo $this->fileCount; ?>\', plainText: \'view mode\'});" id="'+ v.wfmstatusid +'">'+ v.wfmstatusname +'</a>'); 
                                    }
                                } else if (v.wfmstatusprocessid != '' || v.wfmstatusprocessid != 'null' || v.wfmstatusprocessid != null) {
                                    var wfmStatusCode = ('wfmstatuscode' in Object(v)) ? v.wfmstatuscode : ''; 
                                    if (v.wfmisneedsign == '1') {
                                        $('.workflow-buttons-<?php echo $this->id; ?>').append('<a href="javascript:;" ' + advancedCriteria + ' class="btn btn-circle btn-sm" style="background-color: '+v.wfmstatuscolor+'; color: #fff;" onclick="transferProcessAction(\'signProcess\', \'<?php echo $this->metaDataId ?>\', \''+v.wfmstatusprocessid+'\', \'<?php echo Mdmetadata::$businessProcessMetaTypeId; ?>\', \'toolbar\', this, {callerType: \'<?php echo $this->metaDataCode ?>\', isWorkFlow: true, wfmStatusId: \''+v.wfmstatusid+'\', wfmStatusCode: \''+wfmStatusCode+'\'}, \'dataViewId=<?php echo $this->metaDataId ?>&refStructureId=<?php echo $this->refStructureId; ?>&statusId='+v.wfmstatusid+'&statusName='+v.wfmstatusname+'&statusColor='+$.trim(v.wfmstatuscolor)+'&rowId='+rowId+'\');">'+v.wfmstatusname+' <i class="fa fa-key"></i></a>');
                                    } else if (v.wfmisneedsign == '2') {
                                        $('.workflow-buttons-<?php echo $this->id; ?>').append('<a href="javascript:;" ' + advancedCriteria + ' class="btn btn-circle btn-sm" style="background-color: '+v.wfmstatuscolor+'; color: #fff;" onclick="transferProcessAction(\'hardSignProcess\', \'<?php echo $this->metaDataId ?>\', \''+v.wfmstatusprocessid+'\', \'<?php echo Mdmetadata::$businessProcessMetaTypeId; ?>\', \'toolbar\', this, {callerType: \'<?php echo $this->metaDataCode ?>\', isWorkFlow: true, wfmStatusId: \''+v.wfmstatusid+'\', wfmStatusCode: \''+wfmStatusCode+'\'}, \'dataViewId=<?php echo $this->metaDataId ?>&refStructureId=<?php echo $this->refStructureId; ?>&statusId='+v.wfmstatusid+'&statusName='+v.wfmstatusname+'&statusColor='+$.trim(v.wfmstatuscolor)+'&rowId='+rowId+'\');">'+v.wfmstatusname+' <i class="fa fa-key"></i></a>');
                                    } else {
                                        $('.workflow-buttons-<?php echo $this->id; ?>').append('<a href="javascript:;" ' + advancedCriteria + ' class="btn btn-circle btn-sm" style="background-color: '+v.wfmstatuscolor+'; color: #fff;" onclick="transferProcessAction(\'\', \'<?php echo $this->metaDataId ?>\', \''+v.wfmstatusprocessid+'\', \'<?php echo Mdmetadata::$businessProcessMetaTypeId; ?>\', \'toolbar\', this, {callerType: \'<?php echo $this->metaDataCode ?>\', isWorkFlow: true, wfmStatusId: \''+v.wfmstatusid+'\', wfmStatusCode: \''+wfmStatusCode+'\'}, \'dataViewId=<?php echo $this->metaDataId ?>&refStructureId=<?php echo $this->refStructureId; ?>&statusId='+v.wfmstatusid+'&statusName='+v.wfmstatusname+'&statusColor='+$.trim(v.wfmstatuscolor)+'&rowId='+rowId+'\');">'+v.wfmstatusname+'</a>');
                                    }
                                }
                            }
                        });
                    } 
                } else {
                    PNotify.removeAll();
                    new PNotify({
                        title: 'Error',
                        text: response.message,
                        type: response.status,
                        sticker: false
                    });
                }
                Core.unblockUI();
            },
            error: function() {
                alert("Error");
            }
        });
<?php } ?>
        
    });
    
    function cvlEditBookData_<?php echo $this->uniqid; ?>(elem) {
    
        $('.cvlSaveBookData_<?php echo $this->uniqid ?>').show();
        $('.cvlEditBookData_<?php echo $this->uniqid ?>').hide();
        var $this = $(elem), 
            $parent = $this.closest('.cvl-bookMeta'),
            $table = $parent.find('table');
   
        var $el = $table.find('input.dateInit');

        $el.inputmask('y-m-d');
        $el.datepicker({
            format: 'yyyy-mm-dd',
            autoclose: true, 
            showOnFocus: false, 
            todayBtn: 'linked', 
            todayHighlight: true 
        }).off('keyup focus');   
        
        $table.find('input[type="text"]').each(function(){
            $(this).attr('readonly', false);
        });
        
        $parent.find('select').removeAttr('disabled');
       
    }
    
    function cvlShowDeletePhotoData_<?php echo $this->uniqid; ?>(elem) {
        
        var dialogName = '#dialog-editwordtemplate';
        if (!$(dialogName).length) {
            $('<div id="' + dialogName.replace('#', '') + '"></div>').appendTo('body');
        }

        $(dialogName).empty().append('Устгахдаа итгэлтэй байна уу?');
        $(dialogName).dialog({
            cache: false,
            resizable: true,
            bgiframe: true,
            autoOpen: false,
            title: 'Taxonomy cache цэвэрлэх',
            width: 550,
            height: 'auto',
            modal: true,
            close: function(){
                $(dialogName).empty().dialog('destroy').remove();
            },
            buttons: [
                {text: plang.get('yes_btn'), class: 'btn green-meadow btn-sm', click: function () {
                    $.ajax({
                        type: 'post',
                        url: 'mddoc/deletePhotCvl',
                        dataType: 'json',
                        data: {
                            civilId: $('input[data-path="civilId_<?php echo $this->uniqid; ?>"]').val(), 
                            contentId: $('input[data-path="contentId_<?php echo $this->uniqid; ?>"]').val() 
                        },
                        async: false,
                        beforeSend: function () {},
                        success: function (responseData) {

                            PNotify.removeAll();
                            new PNotify({
                                title: responseData.status,
                                text: responseData.message,
                                type: responseData.status,
                                sticker: false
                            });

                            if (responseData.status === 'success') {
                                var $dialogName2 = 'dialog-erl-<?php echo $this->uniqid; ?>';
                                var $dialog2 = $('#' + $dialogName2);
                                var $tree = $dialog2.find('.ecl-jstree-container');
                                var node = $tree.tree('getNodeById', $('input[data-path="nodeId_<?php echo $this->uniqid; ?>"]').val());
                                $tree.tree('removeNode', node);
                                $dialog2.find('.cvl-bookMeta').empty();
                                $(dialogName).dialog('close');
                            }
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
                {text: plang.get('close_btn'), class: 'btn blue-madison btn-sm', click: function () {
                    $(dialogName).dialog('close');
                }}
            ]
        }).dialogExtend({
            "closable": true,
            "maximizable": false,
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
        $(dialogName).dialog('open');
        
    }
    
    function cvlSaveBookData_<?php echo $this->uniqid; ?>(elem) {
    
        $('.cvlSaveBookData_<?php echo $this->uniqid ?>').hide();
        $('.cvlEditBookData_<?php echo $this->uniqid ?>').show();
        
        var $this = $(elem), 
            $parent = $this.closest('.cvl-bookMeta'),
            $table = $parent.find('table');
        var $form = $this.closest('.erl-parent').find('form');
        
        Core.blockUI({
            target: '#cvl-<?php echo $this->uniqid; ?>',
            animate: true
        });
        
        $.ajax({
            type: 'post',
            url: 'mddoc/saveCvlBookData',
            dataType: 'json',
            data: 'civilId=<?php echo $this->id ?>&recordId='+$parent.data('id')+'&'+$form.serialize()+'&'+$('.main-serialize-metadata-<?php echo $this->uniqid ?>').find('input').serialize()+'&'+$('.cvl-marriage-table-<?php echo $this->uniqid ?>').find('input').serialize(),
            async: false,
            beforeSend: function () {},
            success: function (responseData) {

                PNotify.removeAll();
                new PNotify({
                    title: responseData.status,
                    text: responseData.message,
                    type: responseData.status,
                    sticker: false
                });
                
                $table.find('input[type="text"]').each(function() {
                    $(this).attr('readonly', true);
                });
                
                $parent.find('select').attr('disabled', 'disabled');
                
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
       
    }
    
</script>