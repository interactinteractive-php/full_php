<div class="erl-parent" id="windowid-cvl-<?php echo $this->uniqid; ?>" data-id="<?php echo $this->id; ?>" data-name="<?php echo $this->name; ?>" data-prepare-file-count="<?php echo issetVar($this->row['preparedfilecount']); ?>">    
    
    <div class="table-toolbar">
        <div class="row">
            <div class="col-md-7">
                <div class="btn-group btn-group-devided">
                    <?php 
                    if ($this->isEdit && $this->isEdit == '1') {
                        
                        if ($this->isShowBtn && $this->isShowBtn == '1') { ?>
                            <button class="btn btn-circle btn-sm green-meadow" onclick="erlBulkNewScanFromForm_<?php echo $this->uniqid; ?>(this, '<?php echo $this->typeId ?>');">
                                <i class="fa fa-print"></i> Нэмэлт скан хийх
                            </button>
                            <button class="btn btn-circle btn-sm btn-warning" onclick="erlBulkReScanFromForm_<?php echo $this->uniqid; ?>(this, '<?php echo $this->typeId ?>');">
                                <i class="icon-plus3 font-size-12"></i> Шинээр скан хийх
                            </button>
                        <?php } 
                    ?>
                    <button type="button" class="btn btn-sm btn-circle blue erlSaveContentParamsCvl_<?php echo $this->uniqid; ?>" onclick="erlSaveContentParamsCvl_<?php echo $this->uniqid; ?>(this, '<?php echo $this->typeId ?>');" data-status-click="false"><i class="fa fa-save"></i> Хадгалах</button>                    
                    <?php } ?>
                </div>
            </div>
            <div class="col-md-5">
                <span class="workflow-buttons-<?php echo $this->id; ?> float-right"></span>
            </div>
        </div>
    </div>

    <table style="table-layout: fixed; width: 100%; border-top: 1px #999 solid; border-bottom: 1px #999 solid; border-left: 1px #999 solid;" id="cvl-<?php echo $this->uniqid; ?>">
        <tbody>
            <tr>
                <td style="width: 550px; vertical-align: top; padding: 0 10px 0 10px;">
                    <table class="table table-sm mb0">
                        <thead>
                            <tr>
                                <th style="width: 20px">№</th>
                                <th style="width: 95px">Файлын нэр</th>
                                <th style="width: 220px">Баримт бичгийн төрөл</th>
                                <!--<th style="width: 95px">Олгосон огноо</th>-->
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
                    <table style="width: 100%; display: none !important;">
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
                        Бэлтгэсэн: <strong><?php echo isset($this->row['preparedfilecount']) ? issetVar($this->row['preparedfilecount']) : '0'; ?></strong> &nbsp;&nbsp;&nbsp;Скандсан: <strong id="erl-file-count"><?php echo $this->fileCount; ?></strong>
                    </div>
                </td>
                <td style="width: 100%; vertical-align: top; padding: 0;">
                    <span style="position: absolute; cursor: pointer;" onclick="erlImgFullsize(this)" class="ml5 mt5"><i class="fa fa-search-plus"></i> Томоор харах</span>
                    <div class="erl-image-preview"></div>
                </td>
                <td style="width: 350px; vertical-align: top; padding: 0; overflow: auto" class="ecl-civil-height">
                    <div class="col-md-12 main-serialize-metadata-<?php echo $this->uniqid ?>">
                        <form class="xs-form">
                            <div class="col-md-12 bookMeta mt10" id="metadataform-<?php echo $this->uniqid ?>"></div>
                        </form>
                    </div>
                </td>
            </tr>
        </tbody>
    </table>
    
</div>

<style type="text/css">
    
    .border-bottom-cvl label {
        border-bottom: 1px solid #CCC;
    }
    
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
    
    .ui-widget-overlay {
        z-index: 1;
    }
    
</style>

<script type="text/javascript">
    
    var oldCvlBookType_<?php echo $this->uniqid ?> = '';
    var ubegScanFtpLink = getConfigValue('ubegScanFtpLink');
    var ubegScanLink = getConfigValue('ubegScanLink');
    var ubegFtpUsername = getConfigValue('ftp_username');
    var ubegFtpPassword = getConfigValue('ftp_password');

    
    var inputOldVal = '';
    
    $(function() { 
        
        var $erlContentTbl = $('.erl-content-tbl', '#windowid-cvl-<?php echo $this->uniqid; ?>');
        
        $erlContentTbl.find('input[data-path="cvlBookdate"]').inputmask("y-m-d");      
        $erlContentTbl.find('input[data-path="displayCvlBookdate"]').inputmask("y-m-d");      
        
        var row = <?php echo $this->rowJson; ?>;
        /*
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
                                $('.workflow-buttons-<?php echo $this->id; ?>').append('<a href="javascript:;" ' + advancedCriteria + ' class="btn btn-circle btn-sm ml5" style="background-color: '+v.wfmstatuscolor+'; color: #fff;" onclick="changeWfmStatusId(this, \''+v.wfmstatusid+'\', \'<?php echo $this->metaDataId ?>\', \'<?php echo $this->refStructureId ?>\', \''+$.trim(v.wfmstatuscolor)+'\', \''+v.wfmstatusname+'\', {cyphertext: \'<?php echo $this->fileCount; ?>\', plainText: \'\'});" id="'+ v.wfmstatusid +'">'+ v.wfmstatusname +'</a>'); 
                            } else {
                                if (typeof v.wfmstatusname != 'undefined' && v.wfmstatusname != '' && (v.wfmstatusprocessid == '' || v.wfmstatusprocessid == 'null' || v.wfmstatusprocessid == null)) {
                                    if (v.wfmisneedsign == '1') {
                                        $('.workflow-buttons-<?php echo $this->id; ?>').append('<a href="javascript:;" ' + advancedCriteria + ' class="btn btn-circle btn-sm ml5" style="background-color: '+v.wfmstatuscolor+'; color: #fff;" onclick="beforeSignChangeWfmStatusId(this, \''+v.wfmstatusid+'\', \'<?php echo $this->metaDataId ?>\', \'<?php echo $this->refStructureId ?>\', \''+$.trim(v.wfmstatuscolor)+'\', \''+v.wfmstatusname+'\');" id="'+ v.wfmstatusid +'">'+ v.wfmstatusname +' <i class="fa fa-key"></i></a>'); 
                                    } else if (v.wfmisneedsign == '2') {
                                        $('.workflow-buttons-<?php echo $this->id; ?>').append('<a href="javascript:;" ' + advancedCriteria + ' class="btn btn-circle btn-sm ml5" style="background-color: '+v.wfmstatuscolor+'; color: #fff;" onclick="beforeHardSignChangeWfmStatusId(this, \''+v.wfmstatusid+'\', \'<?php echo $this->metaDataId ?>\', \'<?php echo $this->refStructureId ?>\', \''+$.trim(v.wfmstatuscolor)+'\', \''+v.wfmstatusname+'\');" id="'+ v.wfmstatusid +'">'+ v.wfmstatusname +' <i class="fa fa-key"></i></a>'); 
                                    } else {
                                        $('.workflow-buttons-<?php echo $this->id; ?>').append('<a href="javascript:;" ' + advancedCriteria + ' class="btn btn-circle btn-sm ml5" style="background-color: '+v.wfmstatuscolor+'; color: #fff;" onclick="changeWfmStatusId(this, \''+v.wfmstatusid+'\', \'<?php echo $this->metaDataId ?>\', \'<?php echo $this->refStructureId ?>\', \''+$.trim(v.wfmstatuscolor)+'\', \''+v.wfmstatusname+'\', {cyphertext: \'<?php echo $this->fileCount; ?>\', plainText: \'\'});" id="'+ v.wfmstatusid +'">'+ v.wfmstatusname +'</a>'); 
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
            }
        });
        */
    });
    
    <?php  if ($this->isEdit && $this->isEdit == '1') { ?>
        $(document).bind('keydown', '#windowid-cvl-<?php echo $this->uniqid; ?> Ctrl+s', function(e) {
            var $mainSelector_<?php echo $this->uniqid; ?> = $('body').find('#windowid-cvl-<?php echo $this->uniqid; ?> .erlSaveContentParamsCvl_<?php echo $this->uniqid; ?>');

            if ($mainSelector_<?php echo $this->uniqid; ?>.attr('data-status-click') == 'false') {
                $mainSelector_<?php echo $this->uniqid; ?>.attr('data-status-click', 'true');
                $mainSelector_<?php echo $this->uniqid; ?>.trigger('click');

                e.preventDefault();
                return false;
            } 
            /*else {

                PNotify.removeAll();
                new PNotify({
                    title: 'Warning',
                    text: 'Баримт бичгийн төрөл солигдсон тохиолдолд хадгалах боломжтой.',
                    type: 'success',
                    sticker: false
                });

            }*/

        });

        $(document.body).on('keydown', '#windowid-cvl-<?php echo $this->uniqid; ?> input, select, textarea, a, button', 'Ctrl+s', function(e) {
            var $mainSelector_<?php echo $this->uniqid; ?> = $('body').find('#windowid-cvl-<?php echo $this->uniqid; ?> .erlSaveContentParamsCvl_<?php echo $this->uniqid; ?>');
            if ($mainSelector_<?php echo $this->uniqid; ?>.attr('data-status-click') == 'false') {
                $mainSelector_<?php echo $this->uniqid; ?>.attr('data-status-click', 'true');
                $mainSelector_<?php echo $this->uniqid; ?>.trigger('click');

                e.preventDefault();
                return false;
            } else {
                /*
                PNotify.removeAll();
                new PNotify({
                    title: 'Warning',
                    text: 'Баримт бичгийн төрөл солигдсон тохиолдолд хадгалах боломжтой.',
                    type: 'success',
                    sticker: false
                });
                */
            }

        });
    <?php } ?>
        
    $(document.body).on('click', '#windowid-cvl-<?php echo $this->uniqid; ?> .erl-content-tbl > tbody > tr.cvlTable', function() {
        var $this = $(this), 
            $imagePanel = $('.erl-image-preview'), 
            uniqId = getUniqueId(1), 
            $table = $this.closest('tbody');
        
        $table.find('.selected-row').removeClass('selected-row');
        $this.addClass('selected-row');
        
        var $row = $table.find('.selected-row:eq(0)'), imagePath = $row.attr('data-filepath'), 
            recordId = $row.attr('data-hdr-id');
        
        if (imagePath !== '') {
            
            var imgHeight = $(window).height();
            var uid = getUniqueId(1);
            
//            $imagePanel.empty().append('<img style="height: '+(imgHeight - 170)+'px" src="'+ubegScanLink+'?scan_id='+recordId+'&filename='+imagePath+'&uid='+uid+'">');
            $imagePanel.empty().append('<img id="' + recordId + '" class="erl-single-image" src="'+ubegScanLink+'?scan_id='+recordId+'&filename='+imagePath+'&uid='+uid+'">');
        }
        
        $this.find(' > td > input:not(:hidden):first').focus().select();
        
        $imagePanel.parent().find('span').html('<i class="fa fa-search-plus"></i> Томоор харах');
        
        cvlMetaProcess_<?php echo $this->uniqid ?>($this.attr('tr-index'), $this, $this.find('input[data-path="cvlBookdate"]').val(), $this.find('select[data-path="cvlBookType"]').val());
        
    });

    $(document.body).on('hover', '#windowid-cvl-<?php echo $this->uniqid; ?> .erl-content-tbl > tbody > tr > td > select[data-path="cvlBookType"]', function(e, isTrigger) {
        oldCvlBookType_<?php echo $this->uniqid ?> = $(this).val();
    });
    
    $(document.body).on('select2-opening', '#windowid-cvl-<?php echo $this->uniqid; ?> .erl-content-tbl > tbody > tr > td > select[data-path="cvlBookType"]', function(e, isTrigger) {
        oldCvlBookType_<?php echo $this->uniqid ?> = $(this).val();
        changeSelectorErlCvl_<?php echo $this->uniqid ?>(this);
    });
    
    $(document.body).on('change', '#windowid-cvl-<?php echo $this->uniqid; ?> .erl-content-tbl > tbody > tr > td > select[data-path="cvlBookType"]', function() {
        var $this = $(this);
        var $row = $this.closest('tr');
        
        var $thisValText = $this.find('option:selected').html(); 
        var $thisVal = $this.val(), 
            trindex = $row.index(),
            oldId = $this.attr('data-oldval'), 
            setVal = false;
            
        $this.attr('data-oldval', $thisVal);
        
        cvlMetaProcess_<?php echo $this->uniqid ?>($row.attr('tr-index'), $row, $row.find('input[data-path="cvlBookdate"]').val(), $thisVal);
        
        var $trLoop = $this.closest('tbody').find('> tr');
        var len = $trLoop.length, i = 0;
		
        for (i; i < len; i++) { 
            
            if (i > trindex) {
                var $thisRow = $($trLoop[i]);
                var $mainSelector = $thisRow.find('select[data-path="cvlBookType"]');
                
                if (($mainSelector.val() == oldId || $mainSelector.val() == '') && !setVal) {
                    if ($mainSelector.find('option[value="'+ $thisVal +'"]').length > 0) {
                        if ($mainSelector.hasClass('select2-offscreen')) {
                            $mainSelector.select2('val', $thisVal);
                        } else {
                            $mainSelector.removeAttr('selected').filter('[value='+$thisVal+']').attr('selected', 'selected');
                        }
                    } else {
                        $mainSelector.empty();
                        $mainSelector.append('<option selected="selected" value="'+ $thisVal +'">'+ $thisValText +'</option>');
                        if ($mainSelector.hasClass('select2-offscreen')) {
                            $mainSelector.select2('val', $thisVal);
                        }
                    }
                    
                    $mainSelector.attr('data-oldval', $thisVal);
                } else {
                    setVal = true;
                }
            }
            
        }
    });
    
    $(document.body).on('focus', '#windowid-cvl-<?php echo $this->uniqid; ?> input[data-path="displayCvlBookdate"]', function() {
        inputOldVal = $(this).closest('tr').find('input[data-path="cvlBookdate"]').val();
    });

    $(document.body).on('change', '#windowid-cvl-<?php echo $this->uniqid; ?> input[data-path="displayCvlBookdate"]', function(e) {
        var $this = $(this);
        var $thisval = $this.val();
        if ($thisval == '') {
            $thisval = '1910-01-01';
        }
        
        $this.closest('tr').find('input[data-path="cvlBookdate"]').val($thisval).trigger('change');
        
    });
    
    $(document.body).on('focus', '#windowid-cvl-<?php echo $this->uniqid; ?> input[data-path="cvlBookdate"]', function() {
        inputOldVal = $(this).val();
    });

    $(document.body).on('change', '#windowid-cvl-<?php echo $this->uniqid; ?> input[data-path="cvlBookdate"]', function(e) {
        var __t = $(this),
            _thisVal = __t.val(), 
            $trRow = __t.closest('tr'),
            trindex = $trRow.index(),
            setVal = false,
            trs = __t.closest('tbody').children();

        if (new Date(_thisVal) < new Date('1910-01-01') || new Date(_thisVal) > new Date()) {
            __t.val('');
            return;
        }

        __t.val(_thisVal);
        
        cvlMetaProcess_<?php echo $this->uniqid ?>($trRow.attr('tr-index'), $trRow, _thisVal, $trRow.find('select[data-path="cvlBookType"]').val());
        
        trs.each(function(k, v) {
            var _t = $(this);

            if (k > trindex) {

                if((_t.find('input[data-path="cvlBookdate"]').val() == '' || _t.find('input[data-path="cvlBookdate"]').val() == inputOldVal) && !setVal) {              
                    _t.find('input[data-path="cvlBookdate"]').val(_thisVal);
                    if (_thisVal !== '1910-01-01') {
                        _t.find('input[data-path="displayCvlBookdate"]').val(_thisVal);
                    } else {
                        _t.find('input[data-path="displayCvlBookdate"]').val('');
                    }
                } else {
                    setVal = true;
                }

            }
        });
    });
    
    function changeSelectorErlCvl_<?php echo $this->uniqid ?>(element) {

        var $this = $(element);
        var $parent = $this.parent();

        var $inputMetaDataId = '1533653504803';

        if ($this.closest("tr").attr('data-content-type-append') === "0") {

            $.ajax({
                type: 'post',
                async: false,
                url: 'mddoc/callDvData',
                data: {
                    inputMetaDataId: $inputMetaDataId
                },
                dataType: 'json',
                success: function(data) {
                    if (typeof data.emptyCombo === 'undefined') {
                        $this.empty();

                        $this.append($("<option />").val('').text('- Сонгох -'));
                        $.each(data.data, function (index, row) {
                            if (oldCvlBookType_<?php echo $this->uniqid ?> == row.META_VALUE_ID) {
                                $this.append($("<option />").val(row.META_VALUE_ID).text(row.META_VALUE_NAME).attr("selected", "selected").attr("data-row-data", JSON.stringify(row.ROW_DATA)));  
                            } else {
                                $this.append($("<option />").val(row.META_VALUE_ID).text(row.META_VALUE_NAME).attr("data-row-data", JSON.stringify(row.ROW_DATA)));
                            }                    
                        });
                    }
                },
                error: function () {
                    alert("Ajax Error!");
                } 
            }).done(function() {
                $this.closest("tr").attr('data-content-type-append', "1");
                $this.select2('open');

                Core.unblockUI($parent);
            });

        }
        
    }
    
    function erlSaveContentParamsCvl_<?php echo $this->uniqid; ?>(elem, type) {
    
        var $this = $(elem);
        var $parent = $this.closest('.erl-parent');
        var $form = $this.closest('.erl-parent').find('form');
        /*if ($this.attr('data-status-click') == 'true') {
            PNotify.removeAll();
            new PNotify({
                title: 'Warning',
                text: 'Баримт бичгийн төрөл солигдсон тохиолдолд хадгалах боломжтой.',
                type: 'success',
                sticker: false
            });
            return;
        }*/
        setTimeout(function(){
            Core.blockUI({
                target: '#cvl-<?php echo $this->uniqid; ?>',
                animate: true
            });
        }, 5);
        
        var $paramData = {
            paramData: <?php echo isset($this->paramData) ? json_encode($this->paramData) : '' ?>,
            type: <?php echo $this->typeId ?>,
            wfmStatusId: <?php echo $this->row['wfmstatusid'] ?>,
            civilId: <?php echo $this->civilId ?>,
            recordId: $parent.data('id'),
            formData: $form.serialize(),
            formInput: $('.main-serialize-metadata-<?php echo $this->uniqid ?>').find('input').serialize()
        };
        
        $.ajax({
            type: 'post',
            url: 'mddoc/runProcessBeforeV2',
            dataType: 'json',
            data: $paramData,
            async: false,
            beforeSend: function () {},
            success: function (responseData) {

                PNotify.removeAll();

                if (responseData.status === 'success') {
                    new PNotify({
                        title: 'Success',
                        text: responseData.message,
                        type: 'success',
                        sticker: false
                    });
                } else {
                    new PNotify({
                        title: 'Error',
                        text: responseData.message,
                        type: 'error',
                        sticker: false
                    });
                }
                
                setTimeout(function(){
                    Core.unblockUI($('#cvl-<?php echo $this->uniqid; ?>'));  
                }, 300);

                if (responseData.status == 'success') {
                    
                    $('#windowid-cvl-<?php echo $this->uniqid; ?> .erl-content-tbl > tbody').empty().append(responseData.fileRender).promise().done(function() {
                        
                        $('#windowid-cvl-<?php echo $this->uniqid; ?> .erl-content-tbl').find('input[data-path="cvlBookdate"]').inputmask("y-m-d");
                        $('#windowid-cvl-<?php echo $this->uniqid; ?> .erl-content-tbl').find('input[data-path="displayCvlBookdate"]').inputmask("y-m-d");     

                        $this.closest('.table-toolbar').find('span[class^="workflow-buttons-"]').children().removeClass('disabled');

                        $('#windowid-cvl-<?php echo $this->uniqid; ?> .erl-content-tbl > tbody > tr:eq(0)').click();
                        
                    });

                }     

            },
            error: function () {
                Core.unblockUI($('#cvl-<?php echo $this->uniqid; ?>',));  
                alert('Error');
            }
        });
    }
    
    function cvlMetaProcess_<?php echo $this->uniqid ?>($trIndex, $selectedTr, $bookDate, $bookType) {
        var $maindataForm = $('#metadataform-<?php echo $this->uniqid ?>');
        $maindataForm.find('.cvl-form-data').hide();
        
        if (/*(typeof $bookDate !== 'undefined' && $bookDate == '' ) || */(typeof $bookType !== 'undefined' && (/*$bookType == '' || */$bookType == '0'))) {
        
            return;
        }
        
        var $param = {
            trIndex: $trIndex,
            isDisabled: '1',
            isHide: '1',
            uniqId: '<?php echo $this->uniqid; ?>',
            civilPackId: '<?php echo isset($this->id) ? $this->id : '' ?>', 
            cvlBookType: $selectedTr.find('select[data-path="cvlBookType"]').val(), 
            cvlBookDate: $selectedTr.find('input[data-path="cvlBookdate"]').val(), 
            cvlContentId: $selectedTr.find('input[data-path="cvlContentId"]').val(),
            cvlBookId: $selectedTr.find('input[data-path="cvlBookId"]').val(),
            cvlSemanticId: $selectedTr.find('input[data-path="cvlSemanticId"]').val(),
            selectedRow: <?php echo json_encode($this->row) ?>,
            postData: <?php echo isset($this->paramData) ? json_encode($this->paramData) : '' ?>,
        };
        
        if (!$maindataForm.find('.cvl-form-data-' + $bookDate + '-' +$bookType).length) {
            $maindataForm.append('<div class="cvl-form-data cvl-form-data-' + $bookDate + '-' +$bookType + '" data-bookType="'+ $bookType +'" data-bookdate="'+ $bookDate +'" data-tr-index="'+ $trIndex +'"></div>').promise().done(function () {
                $.ajax({
                    type: 'post',
                    url: 'mddoc/cvlControlFormDataHtml',
                    data: $param,
                    dataType: "json",
                    beforeSend: function() {
                        Core.blockUI({
                            message: 'Loading...',
                            boxed: true
                        });
                    },
                    success: function(response) {
                        $maindataForm.find('.cvl-form-data-' + $bookDate + '-' +$bookType).empty().append(response.Html);
                        Core.unblockUI();
                        $('body').find('#windowid-cvl-<?php echo $this->uniqid; ?> .erlSaveContentParamsCvl_<?php echo $this->uniqid; ?>').attr('data-status-click', 'false');
                    },
                    error: function() {
                        alert("Error");
                    }
                });
            });
        } else {
            
            $maindataForm.find('.cvl-form-data-' + $bookDate + '-' +$bookType).find('input[data-path="bookDate"]').val($bookDate);
            $maindataForm.find('.cvl-form-data-' + $bookDate + '-' +$bookType).find('input[data-path="bookTypeId"]').val($bookType);
            $maindataForm.find('.cvl-form-data-' + $bookDate + '-' +$bookType).show();
            
        }
        
    }
    
    function erlBulkNewScanFromForm_<?php echo $this->uniqid; ?>(elem, type) {
        var $this = $(elem), $parent = $this.closest('.erl-parent'), 
            recordId = $parent.data('id'), name = $parent.data('name'),
            prepareFileCount = $parent.data('prepare-file-count');    

        Core.blockUI({
            boxed: true, 
            message: 'Loading...'
        });

        if ("WebSocket" in window) {
            console.log("WebSocket is supported by your Browser!");
            var ws = new WebSocket("ws://localhost:58324/socket");
            var scanType = '';

            if($parent.find('.erl-content-tbl > tbody > tr').length)
                scanType = 'new';

            ws.onopen = function () {
                var currentDateTime = erlGetCurrentDateTime();            
                ws.send('{"command":"bulk_scan", "dateTime":"' + currentDateTime + '", details: [{"key": "scan_id", "value": "'+recordId+'"},{"key": "ftp_server", "value": "'+ubegScanFtpLink+'"},{"key": "ftp_username", "value": "'+ubegFtpUsername+'"},{"key": "ftp_password", "value": "'+ubegFtpPassword+'"},{"key": "selected_image", "value": ""},{"key": "name", "value": "'+name+'"}, {"key": "scan_type", "value": "'+scanType+'"}, {"key": "prepared_file_count", "value": "'+prepareFileCount+'"}]}');
            };

            ws.onmessage = function (evt) {
                var received_msg = evt.data;
                
                var jsonData = JSON.parse(received_msg);

                PNotify.removeAll();

                if (jsonData.status == 'success' && 'details' in Object(jsonData)) {

                    var filesObj = convertDataElementToArray(jsonData.details);

                    $.ajax({
                        type: 'post',
                        url: 'mddoc/electronRegisterLegalBulkScanCvl',
                        data: {recordId: recordId, filesObj: filesObj},
                        dataType: 'json',
                        async: false, 
                        success: function (data) {
                            $('.erl-content-tbl > tbody').empty().append(data.fileRender).promise().done(function () {
                                $('.erl-content-tbl').find('input[data-path="cvlBookdate"]').inputmask("y-m-d");
                                $('.erl-content-tbl').find('input[data-path="displayCvlBookdate"]').inputmask("y-m-d");     
                                
                                $('.erl-content-tbl > tbody > tr:eq(0)').click();
                            });
                        }
                    });

                } else {
                    if (jsonData.description != null) {
                        
                        new PNotify({
                            title: 'Error',
                            text: jsonData.description, 
                            type: 'error',
                            sticker: false
                        });
                    }
                }

                Core.unblockUI();
            };

            ws.onerror = function (event) {
                if (event.code != null) {
                    PNotify.removeAll();
                    new PNotify({
                        title: 'Error',
                        text: event.code, 
                        type: 'error',
                        sticker: false
                    });
                }
                Core.unblockUI();
            };

            ws.onclose = function () {
                console.log("Connection is closed...");
                Core.unblockUI();
            };

        } else {

            PNotify.removeAll();
            new PNotify({
                title: 'Error',
                text: 'WebSocket NOT supported by your Browser!', 
                type: 'error',
                sticker: false
            });

            Core.unblockUI();
        }
    }

    function erlBulkReScanFromForm_<?php echo $this->uniqid; ?>(elem, type) {
        var $this = $(elem), $parent = $this.closest('.erl-parent'), 
            recordId = $parent.data('id'), name = $parent.data('name'),
            prepareFileCount = $parent.data('prepare-file-count');

            var $wfmDialogName = 'dialog-confirm-status-erl';
            if (!$("#" + $wfmDialogName).length) {
                $('<div id="' + $wfmDialogName + '">Өмнө сканнердсан бүх зургийг устгаж шинээр сканнердахдаа итгэлтэй байна уу?</div>').appendTo('body');
            }

            $("#" + $wfmDialogName).dialog({
                cache: false,
                resizable: false,
                bgiframe: true,
                autoOpen: false,
                title: 'Санамж',
                width: 370,
                height: "auto",
                modal: true,
                close: function () {
                    $("#" + $wfmDialogName).empty().dialog('destroy').remove();
                },
                buttons: [
                    {text: 'Тийм', class: 'btn green-meadow btn-sm', click: function () {
                        Core.blockUI({
                            boxed: true, 
                            message: 'Loading...'
                        });

                        if ("WebSocket" in window) {
                            console.log("WebSocket is supported by your Browser!");
                            var ws = new WebSocket("ws://localhost:58324/socket");

                            ws.onopen = function () {
                                var currentDateTime = erlGetCurrentDateTime();
                                ws.send('{"command":"bulk_scan", "dateTime":"' + currentDateTime + '", details: [{"key": "scan_id", "value": "'+recordId+'"},{"key": "ftp_server", "value": "'+ubegScanFtpLink+'"},{"key": "ftp_username", "value": "'+ubegFtpUsername+'"},{"key": "ftp_password", "value": "'+ubegFtpPassword+'"},{"key": "selected_image", "value": ""},{"key": "name", "value": "'+name+'"}, {"key": "scan_type", "value": "again"}, {"key": "prepared_file_count", "value": "'+prepareFileCount+'"}]}');
                            };

                            ws.onmessage = function (evt) {
                                var received_msg = evt.data;
                                var jsonData = JSON.parse(received_msg);

                                PNotify.removeAll();

                                if (jsonData.status == 'success' && 'details' in Object(jsonData)) {

                                    var filesObj = convertDataElementToArray(jsonData.details);

                                    $.ajax({
                                        type: 'post',
                                        url: 'mddoc/electronRegisterLegalBulkReScanCvl',
                                        data: {recordId: recordId, filesObj: filesObj, type: '<?php echo $this->typeId ?>'},
                                        dataType: 'json',
                                        async: false, 
                                        success: function (data) {
                                            $('.erl-content-tbl > tbody').empty().append(data.fileRender).promise().done(function () {
                                                $('.erl-content-tbl').find('input[data-path="cvlBookdate"]').inputmask("y-m-d");
                                                $('.erl-content-tbl').find('input[data-path="displayCvlBookdate"]').inputmask("y-m-d");   
                                                $('.erl-content-tbl > tbody > tr:eq(0)').click();
                                            });
                                        }
                                    });

                                } else {
                                    if (jsonData.description != null) {
                                        new PNotify({
                                            title: 'Error',
                                            text: jsonData.description, 
                                            type: 'error',
                                            sticker: false
                                        });

                                    }
                                }

                                Core.unblockUI();
                            };

                            ws.onerror = function (event) {
                                if (event.code != null) {
                                    PNotify.removeAll();
                                    new PNotify({
                                        title: 'Error',
                                        text: event.code, 
                                        type: 'error',
                                        sticker: false
                                    });
                                }
                                Core.unblockUI();
                            };

                            ws.onclose = function () {
                                console.log("Connection is closed...");
                                Core.unblockUI();
                            };

                        } else {

                            PNotify.removeAll();
                            new PNotify({
                                title: 'Error',
                                text: 'WebSocket NOT supported by your Browser!', 
                                type: 'error',
                                sticker: false
                            });

                            Core.unblockUI();
                        }

                        $("#" + $wfmDialogName).dialog('close');
                    }},
                    {text: 'Үгүй', class: 'btn blue-madison btn-sm', click: function () {
                        $("#" + $wfmDialogName).dialog('close');
                    }}
                ]
            });
            $("#" + $wfmDialogName).dialog('open');

    }
    
    function cvlEditBookData_<?php echo $this->uniqid; ?>(elem) {
       var $this = $(elem), $parent = $this.closest('.cvl-form-data'),
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
       
    }
    
</script>