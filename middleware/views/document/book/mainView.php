<div class="erl-parent" data-id="" data-name="">    
    <div class="table-toolbar">
        <div class="row">
            <div class="col-md-12">
                <div class="btn-group btn-group-devided float-right">
                    <span class="workflow-buttons-<?php echo $this->id; ?>"></span>
                </div>
            </div>
        </div>
    </div>    
    <table style="table-layout: fixed; width: 100%; border-top: 1px #999 solid; border-bottom: 1px #999 solid; border-left: 1px #999 solid;">
        <tbody>
            <tr>
                <td style="width: 650px; vertical-align: top; padding: 0 10px 0 10px;">
                    <div class="ecl-height ecl-jstree-container" data-treedvid="<?php echo issetParam($this->treeDataViewId); ?>"></div>
                    <hr style="margin: 8px 0;">
                    <table style="width: 100%">
                        <tbody>
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
                        </tbody>
                    </table>                  
                    <div class="mt5" style="font-size: 11px;">Бэлтгэсэн: <strong><?php echo issetVar($this->row['preparedfilecount']); ?></strong> &nbsp;&nbsp;&nbsp;Скандсан: <strong><?php echo $this->fileCount; ?></strong></div>                    
                </td>
                <td style="width: 100%; vertical-align: top; padding: 0;">
                    <span style="position: absolute; cursor: pointer;" onclick="erlImgFullsize(this)" class="ml5 mt5"><i class="fa fa-search-plus"></i> Томоор харах</span>
                    <div class="erl-image-preview"></div>
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
    $(function(){
        
        var row = <?php echo $this->rowJson; ?>;
        
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
                    if (response.datastatus) {
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
                Core.unblockUI();
            }
        });                
    });
</script>