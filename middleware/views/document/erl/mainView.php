<div class="erl-parent" id="<?php echo $this->uniqid; ?>" data-id="" data-name="" data-verifer="<?php echo issetParamZero($this->isverifier) ?>">
    
    <div class="table-toolbar">
        <div class="row">
            <div class="col-md-12">
                <div class="col">
                    <?php if (issetParamZero($this->isverifier) === '1') { ?>
                        <a href="javascript:;" class="btn btn-danger btn-circle btn-sm" style="display: none;" data-name="" data-contentid="" data-deleteid="" onclick="erlDelete(this, '<?php echo issetParam($this->postDatac) ?>', '<?php echo $this->uniqid; ?>')">Устгах</a>
                    <?php } ?>
                    <div class="btn-group btn-group-devided float-right">
                        <a href="javascript:;" class="btn btn-secondary btn-circle btn-sm ml5" onclick="erlPrint(this, '<?php echo issetParam($this->postDatac) ?>')" id="">Хэвлэх</a>
                        <a href="javascript:;" class="btn btn-secondary btn-circle btn-sm ml5" onclick="erlPrintAll(this, '<?php echo issetParam($this->postDatac) ?>')" id="">Хэвлэх /Бүгд/</a>
                        <a href="javascript:;" class="btn btn-secondary btn-circle btn-sm ml5" onclick="erlPdf(this, '<?php echo issetParam($this->postDatac) ?>')" id="">PDF</a>
                        <a href="javascript:;" class="btn btn-secondary btn-circle btn-sm ml5" onclick="erlPdfAll(this, '<?php echo issetParam($this->postDatac) ?>')" id="">PDF /Бүгд/</a>
                        <span class="workflow-buttons-<?php echo $this->id; ?>"></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <table style="table-layout: fixed; width: 100%; border-top: 1px #999 solid; border-bottom: 1px #999 solid; border-left: 1px #999 solid;">
        <tbody>
            <tr>
                <td style="width: 650px; vertical-align: top; padding: 0 10px 0 10px;">
                    <div class="ecl-height ecl-jstree-container" data-treedvid="<?php echo issetParam($this->treeDataViewId); ?>" data-inputparams="<?php echo issetParam($this->treeInputParams); ?>"></div>
                    <?php if (isset($this->hideFooter) && $this->hideFooter) {} else { ?>
                    <hr style="margin: 8px 0;">
                    <table style="width: 100%">
                        <tbody>
                            <?php if (issetParam($this->ishrm) === '1') { ?>
                                <tr>
                                    <td>Регистрийн дугаар:</td>
                                    <td style="width: 32%"><strong><?php echo issetVar($this->row['stateregnumber']); ?></strong></td>
                                    <td>Төлөв:</td>
                                    <td><strong><?php echo issetVar($this->row['statusname']); ?></strong></td>
                                </tr>
                                <tr>
                                    <td>Албан хаагчийн нэр:</td>
                                    <td style="line-height: 12px;"><strong><?php echo issetVar($this->row['employeename']); ?></strong></td>
                                    <td>Албан тушаал:</td>
                                    <td><strong><?php echo issetVar($this->row['positionname']); ?></strong></td>
                                </tr>
                            <?php } else { ?>
                                <tr>
                                    <td>
                                        Регистрийн дугаар:
                                    </td>
                                    <td style="width: 30%">
                                        <strong><?php echo issetVar($this->row['companyregiternumber']); ?></strong>
                                    </td>
                                    <td>
                                        Улсын бүртгэлийн дугаар:
                                    </td>
                                    <td>
                                        <strong><?php echo issetVar($this->row['stateregisternumber']); ?></strong>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        Хуулийн этгээдийн нэр:
                                    </td>
                                    <td>
                                        <strong><?php echo issetVar($this->row['companyname']); ?></strong>
                                    </td>
                                    <td>
                                        Хуулийн этгээдийн төлөв:
                                    </td>
                                    <td>
                                        <strong><?php echo issetVar($this->row['statusname']); ?></strong>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        Хуулийн этгээдийн хэлбэр:
                                    </td>
                                    <td style="line-height: 12px;">
                                        <strong><?php echo issetVar($this->row['companytypename']); ?></strong>
                                    </td>
                                    <td>
                                        LES системд бүртгэсэн огноо:
                                    </td>
                                    <td>
                                        <strong><?php echo issetVar($this->row['establisheddate']); ?></strong>
                                    </td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>                  
                    <div class="mt5" style="font-size: 11px;">Бэлтгэсэн: <strong><?php echo issetVar($this->row['preparedfilecount']); ?></strong> &nbsp;&nbsp;&nbsp;Скандсан: <strong><?php echo $this->fileCount; ?></strong></div>                    
                    <?php } ?>
                </td>
                <td style="width: 100%; vertical-align: top; padding: 0;">
                    <span style="position: absolute; cursor: pointer;" onclick="erlImgFullsize(this)" class="ml5 mt5"><i class="fa fa-search-plus"></i> Томоор харах</span>
                    <div class="erl-image-preview"></div>
                    <div class="hidden" id="erl-image-preview-hidden"></div>
                </td>
            </tr>
        </tbody>
    </table>
    
</div>

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
    var $window_<?php echo $this->uniqid; ?> = $('#<?php echo $this->uniqid; ?>');      
    var row_<?php echo $this->uniqid; ?> = <?php echo $this->rowJson; ?>;
    
    $(function(){
        
        <?php
        if (!isset($this->ignoreWorkFlow)) {
        ?>
        $.ajax({
            type: 'post',
            url: 'mdobject/getWorkflowNextStatus',
            data: {metaDataId: '<?php echo $this->metaDataId ?>', dataRow: row_<?php echo $this->uniqid; ?>},
            dataType: 'json',
            beforeSend: function() {
                Core.blockUI({
                    message: 'Loading...',
                    boxed: true
                });
            },
            success: function(response) {
                PNotify.removeAll();
                if (response.status == 'success') {
                    if (response.datastatus && response.data) {
                        var rowId = '';
                        if (typeof row_<?php echo $this->uniqid; ?>.id !== 'undefined') {
                            rowId = row_<?php echo $this->uniqid; ?>.id;
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
                                        $('.workflow-buttons-<?php echo $this->id; ?>').append('<a href="javascript:;" ' + advancedCriteria + ' class="btn btn-circle btn-sm ml5" style="background-color: '+v.wfmstatuscolor+'; color: #fff;" onclick="transferProcessAction(\'signProcess\', \'<?php echo $this->metaDataId ?>\', \''+v.wfmstatusprocessid+'\', \'<?php echo Mdmetadata::$businessProcessMetaTypeId; ?>\', \'toolbar\', this, {callerType: \'<?php echo $this->metaDataCode ?>\', isWorkFlow: true, wfmStatusId: \''+v.wfmstatusid+'\', wfmStatusCode: \''+wfmStatusCode+'\'}, \'dataViewId=<?php echo $this->metaDataId ?>&refStructureId=<?php echo $this->refStructureId; ?>&statusId='+v.wfmstatusid+'&statusName='+v.wfmstatusname+'&statusColor='+$.trim(v.wfmstatuscolor)+'&rowId='+rowId+'\');">'+v.wfmstatusname+' <i class="fa fa-key"></i></a>');
                                    } else if (v.wfmisneedsign == '2') {
                                        $('.workflow-buttons-<?php echo $this->id; ?>').append('<a href="javascript:;" ' + advancedCriteria + ' class="btn btn-circle btn-sm ml5" style="background-color: '+v.wfmstatuscolor+'; color: #fff;" onclick="transferProcessAction(\'hardSignProcess\', \'<?php echo $this->metaDataId ?>\', \''+v.wfmstatusprocessid+'\', \'<?php echo Mdmetadata::$businessProcessMetaTypeId; ?>\', \'toolbar\', this, {callerType: \'<?php echo $this->metaDataCode ?>\', isWorkFlow: true, wfmStatusId: \''+v.wfmstatusid+'\', wfmStatusCode: \''+wfmStatusCode+'\'}, \'dataViewId=<?php echo $this->metaDataId ?>&refStructureId=<?php echo $this->refStructureId; ?>&statusId='+v.wfmstatusid+'&statusName='+v.wfmstatusname+'&statusColor='+$.trim(v.wfmstatuscolor)+'&rowId='+rowId+'\');">'+v.wfmstatusname+' <i class="fa fa-key"></i></a>');
                                    } else {
                                        $('.workflow-buttons-<?php echo $this->id; ?>').append('<a href="javascript:;" ' + advancedCriteria + ' class="btn btn-circle btn-sm ml5" style="background-color: '+v.wfmstatuscolor+'; color: #fff;" onclick="transferProcessAction(\'\', \'<?php echo $this->metaDataId ?>\', \''+v.wfmstatusprocessid+'\', \'<?php echo Mdmetadata::$businessProcessMetaTypeId; ?>\', \'toolbar\', this, {callerType: \'<?php echo $this->metaDataCode ?>\', isWorkFlow: true, wfmStatusId: \''+v.wfmstatusid+'\', wfmStatusCode: \''+wfmStatusCode+'\'}, \'dataViewId=<?php echo $this->metaDataId ?>&refStructureId=<?php echo $this->refStructureId; ?>&statusId='+v.wfmstatusid+'&statusName='+v.wfmstatusname+'&statusColor='+$.trim(v.wfmstatuscolor)+'&rowId='+rowId+'\');">'+v.wfmstatusname+'</a>');
                                    }
                                }
                            }
                        });
                    } 
                } else {
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
                Core.unblockUI();
            }
        });             
        
        <?php
        }
        if (isset($this->isMainWindow)) {
        ?>
            
        var dynamicHeight_<?php echo $this->uniqid; ?> = $(window).height() - $window_<?php echo $this->uniqid; ?>.offset().top - 30;
        
        $window_<?php echo $this->uniqid; ?>.find('.erl-image-preview').css({'height': dynamicHeight_<?php echo $this->uniqid; ?>});
        $window_<?php echo $this->uniqid; ?>.find('.ecl-height').css({'height': dynamicHeight_<?php echo $this->uniqid; ?> - 100}); 
        
        if (typeof IS_LOAD_ERLVIEW_SCRIPT === 'undefined') {
            $.getScript(URL_APP+"assets/custom/addon/plugins/jqTree/tree.jquery.js").done(function() {
                $.getScript(URL_APP+"assets/custom/addon/scripts/project/erlview.js").done(function() {
                    doneViewRender($window_<?php echo $this->uniqid; ?>, row_<?php echo $this->uniqid; ?>, '', 1);
                });
            });
        } else {
            doneViewRender($window_<?php echo $this->uniqid; ?>, row_<?php echo $this->uniqid; ?>, '', 1);
        }
        
        <?php
        }
        ?>        
    });
</script>
<style  type="text/css">
    div[aria-describedby="dialog-changeWfmStatus-<?php echo $this->metaDataId ?>"] {
        z-index: 1052;
    }
</style>