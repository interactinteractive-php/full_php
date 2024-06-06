<div class="erl-parent" id="windowid-erl-<?php echo $this->uniqid; ?>" data-id="<?php echo $this->id; ?>" data-name="<?php echo $this->name; ?>" data-prepare-file-count="<?php echo issetVar($this->row['preparedfilecount']); ?>">    
    <div class="table-toolbar">
        <div class="row">
            <div class="col-md-7">
                <div class="btn-group btn-group-devided">
                    <button class="btn btn-circle btn-sm green-meadow" onclick="erlBulkNewScanFromForm(this, {refstructureid: '<?php echo $this->refStructureId; ?>'});">
                        <i class="fa fa-print"></i> Нэмэлт скан хийх
                    </button>
                    <button class="btn btn-circle btn-sm btn-warning" onclick="erlBulkReScanFromForm(this, {refstructureid: '<?php echo $this->refStructureId; ?>'});">
                        <i class="icon-plus3 font-size-12"></i> Шинээр скан хийх
                    </button>
                    <button class="btn btn-circle btn-sm btn-danger" onclick="erlBulkReScanFromForm(this, {refstructureid: '<?php echo $this->refStructureId; ?>'});">
                        <i class="fa fa fa-exclamation"></i>  Устгаад шинээр сканнердах
                    </button>
                    <button type="button" class="btn btn-sm btn-circle blue" onclick="erlSaveBookContentParams(this, '<?php echo $this->saveProcessCode; ?>', '<?php echo $this->refStructureId; ?>', '<?php echo $this->companykeyid; ?>');"><i class="fa fa-save"></i> Хадгалах</button>                    
                </div>
            </div>
            <div class="col-md-5">
                <span class="workflow-buttons-<?php echo $this->id; ?> float-right"></span>
            </div>
        </div>
    </div>

    <table style="table-layout: fixed; width: 100%; border: 1px #999 solid;">
        <tbody>
            <tr>
                <td style="width: 720px; vertical-align: top; padding: 0 10px 0 10px;">
                    <table class="table table-sm mb0">
                        <thead>
                            <tr>
                                <th style="width: 23px">№</th>
                                <th style="width: 87px">Файлын нэр</th>
                                <th style="width: 90px">Бүртгэлийн огноо</th>
                                <th style="width: 230px">Бүртгэлийн төрөл</th>
                                <th style="width: 200px">Нотлох баримтын төрөл</th>
                            </tr>
                        </thead>
                    </table>
                    <div class="ecl-height">
                        <form>
                            <table class="table table-sm table-bordered table-hover bprocess-table-dtl bprocess-theme1 mb0 erl-content-tbl">
                                <tbody>
                                    <?php echo $this->fileRender; ?>
                                </tbody>
                            </table>
                        </form>
                    </div>
                    <hr style="margin: 8px 0;">
                    <table style="width: 100%">
                        <tbody>
                            <tr>
                                <td>Регистрийн дугаар:</td>
                                <td style="width: 32%"><strong><?php echo issetVar($this->row['companyregiternumber']); ?></strong></td>
                                <td>Улсын бүртгэлийн дугаар:</td>
                                <td><strong><?php echo issetVar($this->row['stateregisternumber']); ?></strong></td>
                            </tr>
                            <tr>
                                <td>Хуулийн этгээдийн нэр:</td>
                                <td><strong><?php echo issetVar($this->row['companyname']); ?></strong></td>
                                <td>Хуулийн этгээдийн төлөв:</td>
                                <td><strong><?php echo issetVar($this->row['statusname']); ?></strong></td>
                            </tr>
                            <tr>
                                <td>Хуулийн этгээдийн хэлбэр:</td>
                                <td style="line-height: 12px;"><strong><?php echo issetVar($this->row['companytypename']); ?></strong></td>
                                <td></td>
                                <td></td>
                            </tr>
                        </tbody>
                    </table>                  
                    <div class="mt5" style="font-size: 11px;">Бэлтгэсэн: <strong><?php echo issetVar($this->row['preparedfilecount']); ?></strong> &nbsp;&nbsp;&nbsp;Скандсан: <strong id="erl-file-count"><?php echo $this->fileCount; ?></strong></div>
                </td>
                <td style="width: 100%; vertical-align: top; padding: 0;">
                    <span style="position: absolute; cursor: pointer;" onclick="erlImgFullsize(this)" class="ml5 mt5"><i class="fa fa-search-plus"></i> Томоор харах</span>
                    <div class="erl-image-preview"></div>
                </td>
            </tr>
        </tbody>
    </table>
</div>

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
    table.bprocess-theme1 > tbody > tr > td {
        padding-left: 3px !important;
    }
</style>

<script type="text/javascript">
    var $erlWindow_<?php echo $this->uniqid; ?> = $('#windowid-erl-<?php echo $this->uniqid; ?>');        

    $(function(){
        
        var inputOldVal = '';
        
        $erlWindow_<?php echo $this->uniqid; ?>.on('focus', '.erl-bookdate', function(){
            var $this = $(this);
            inputOldVal = $this.val();
            $this.inputmask('y-m-d');
        });

        $erlWindow_<?php echo $this->uniqid; ?>.on('change', '.erl-bookdate', function(e){
            var $t = $(this), _thisVal = $t.val(), $row = $t.closest('tr'),
                trindex = $row.index(), setVal = false;
                
            var $trLoop = $('.erl-content-tbl > tbody > tr');
            var dte = new Date(_thisVal);
                
            if (dte < new Date('1990-01-01') || dte > new Date()) {
                $t.val('');
                return;
            }

            $t.val(_thisVal);
            //$row.find('input[name="erlCompanyBookId[]"]').val('');

            $trLoop.each(function(k, v){
                var $this = $(this);
                if (k > trindex) {

                    if (($this.find('input[name="bookDate[]"]').val() == '' || $this.find('input[name="bookDate[]"]').val() == inputOldVal) && !setVal) {              
                        $this.find('input[name="bookDate[]"]').val(_thisVal);
                        //$this.find('input[name="erlCompanyBookId[]"]').val('');
                    } else {
                        setVal = true;
                    }
                }
            });
        });        
        
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
                                $('.workflow-buttons-<?php echo $this->id; ?>').append('<a href="javascript:;" ' + advancedCriteria + ' class="btn btn-circle btn-sm ml5" style="background-color: '+v.wfmstatuscolor+'; color: #fff;" onclick="changeWfmStatusId(this, \''+v.wfmstatusid+'\', \'<?php echo $this->metaDataId; ?>\', \'<?php echo $this->refStructureId; ?>\', \''+$.trim(v.wfmstatuscolor)+'\', \''+v.wfmstatusname+'\', {cyphertext: \'<?php echo $this->fileCount; ?>\', plainText: \'edit mode\'});" id="'+ v.wfmstatusid +'">'+ v.wfmstatusname +'</a>'); 
                            } else {
                                if (typeof v.wfmstatusname != 'undefined' && v.wfmstatusname != '' && (v.wfmstatusprocessid == '' || v.wfmstatusprocessid == 'null' || v.wfmstatusprocessid == null)) {
                                    if (v.wfmisneedsign == '1') {
                                        $('.workflow-buttons-<?php echo $this->id; ?>').append('<a href="javascript:;" ' + advancedCriteria + ' class="btn btn-circle btn-sm ml5" style="background-color: '+v.wfmstatuscolor+'; color: #fff;" onclick="beforeSignChangeWfmStatusId(this, \''+v.wfmstatusid+'\', \'<?php echo $this->metaDataId; ?>\', \'<?php echo $this->refStructureId; ?>\', \''+$.trim(v.wfmstatuscolor)+'\', \''+v.wfmstatusname+'\');" id="'+ v.wfmstatusid +'">'+ v.wfmstatusname +' <i class="fa fa-key"></i></a>'); 
                                    } else if (v.wfmisneedsign == '2') {
                                        $('.workflow-buttons-<?php echo $this->id; ?>').append('<a href="javascript:;" ' + advancedCriteria + ' class="btn btn-circle btn-sm ml5" style="background-color: '+v.wfmstatuscolor+'; color: #fff;" onclick="beforeHardSignChangeWfmStatusId(this, \''+v.wfmstatusid+'\', \'<?php echo $this->metaDataId; ?>\', \'<?php echo $this->refStructureId; ?>\', \''+$.trim(v.wfmstatuscolor)+'\', \''+v.wfmstatusname+'\');" id="'+ v.wfmstatusid +'">'+ v.wfmstatusname +' <i class="fa fa-key"></i></a>'); 
                                    } else {
                                        $('.workflow-buttons-<?php echo $this->id; ?>').append('<a href="javascript:;" ' + advancedCriteria + ' class="btn btn-circle btn-sm ml5" style="background-color: '+v.wfmstatuscolor+'; color: #fff;" onclick="changeWfmStatusId(this, \''+v.wfmstatusid+'\', \'<?php echo $this->metaDataId; ?>\', \'<?php echo $this->refStructureId; ?>\', \''+$.trim(v.wfmstatuscolor)+'\', \''+v.wfmstatusname+'\', {cyphertext: \'<?php echo $this->fileCount; ?>\', plainText: \'edit mode\'});" id="'+ v.wfmstatusid +'">'+ v.wfmstatusname +'</a>'); 
                                    }
                                } else if (v.wfmstatusprocessid != '' || v.wfmstatusprocessid != 'null' || v.wfmstatusprocessid != null) {
                                    var wfmStatusCode = ('wfmstatuscode' in Object(v)) ? v.wfmstatuscode : ''; 
                                    if (v.wfmisneedsign == '1') {
                                        $('.workflow-buttons-<?php echo $this->id; ?>').append('<a href="javascript:;" ' + advancedCriteria + ' class="btn btn-circle btn-sm ml5" style="background-color: '+v.wfmstatuscolor+'; color: #fff;" onclick="transferProcessAction(\'signProcess\', \'<?php echo $this->metaDataId; ?>\', \''+v.wfmstatusprocessid+'\', \'<?php echo Mdmetadata::$businessProcessMetaTypeId; ?>\', \'toolbar\', this, {callerType: \'<?php echo $this->metaDataCode; ?>\', isWorkFlow: true, wfmStatusId: \''+v.wfmstatusid+'\', wfmStatusCode: \''+wfmStatusCode+'\'}, \'dataViewId=<?php echo $this->metaDataId; ?>&refStructureId=<?php echo $this->refStructureId; ?>&statusId='+v.wfmstatusid+'&statusName='+v.wfmstatusname+'&statusColor='+$.trim(v.wfmstatuscolor)+'&rowId='+rowId+'\');">'+v.wfmstatusname+' <i class="fa fa-key"></i></a>');
                                    } else if (v.wfmisneedsign == '2') {
                                        $('.workflow-buttons-<?php echo $this->id; ?>').append('<a href="javascript:;" ' + advancedCriteria + ' class="btn btn-circle btn-sm ml5" style="background-color: '+v.wfmstatuscolor+'; color: #fff;" onclick="transferProcessAction(\'hardSignProcess\', \'<?php echo $this->metaDataId; ?>\', \''+v.wfmstatusprocessid+'\', \'<?php echo Mdmetadata::$businessProcessMetaTypeId; ?>\', \'toolbar\', this, {callerType: \'<?php echo $this->metaDataCode; ?>\', isWorkFlow: true, wfmStatusId: \''+v.wfmstatusid+'\', wfmStatusCode: \''+wfmStatusCode+'\'}, \'dataViewId=<?php echo $this->metaDataId; ?>&refStructureId=<?php echo $this->refStructureId; ?>&statusId='+v.wfmstatusid+'&statusName='+v.wfmstatusname+'&statusColor='+$.trim(v.wfmstatuscolor)+'&rowId='+rowId+'\');">'+v.wfmstatusname+' <i class="fa fa-key"></i></a>');
                                    } else {
                                        $('.workflow-buttons-<?php echo $this->id; ?>').append('<a href="javascript:;" ' + advancedCriteria + ' class="btn btn-circle btn-sm ml5" style="background-color: '+v.wfmstatuscolor+'; color: #fff;" onclick="transferProcessAction(\'\', \'<?php echo $this->metaDataId; ?>\', \''+v.wfmstatusprocessid+'\', \'<?php echo Mdmetadata::$businessProcessMetaTypeId; ?>\', \'toolbar\', this, {callerType: \'<?php echo $this->metaDataCode; ?>\', isWorkFlow: true, wfmStatusId: \''+v.wfmstatusid+'\', wfmStatusCode: \''+wfmStatusCode+'\'}, \'dataViewId=<?php echo $this->metaDataId; ?>&refStructureId=<?php echo $this->refStructureId; ?>&statusId='+v.wfmstatusid+'&statusName='+v.wfmstatusname+'&statusColor='+$.trim(v.wfmstatuscolor)+'&rowId='+rowId+'\');">'+v.wfmstatusname+'</a>');
                                    }
                                }
                            }
                        });
                        
                        $('.workflow-buttons-<?php echo $this->id; ?>').children().addClass('disabled');
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