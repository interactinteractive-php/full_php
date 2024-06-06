<div class="erl-parent" id="windowid-erl-<?php echo $this->uniqid; ?>" data-id="<?php echo $this->id; ?>" data-name="<?php echo $this->name; ?>" data-prepare-file-count="<?php echo issetVar($this->row['preparedfilecount']); ?>">    
    <div class="table-toolbar">
        <div class="row">
            <div class="col-md-7">
                <div class="btn-group btn-group-devided">
                    <button class="btn btn-circle btn-sm green-meadow" onclick="erlBulkNewScanFromForm(this);">
                        <i class="fa fa-print"></i> Нэмэлт скан хийх
                    </button>
                    <button class="btn btn-circle btn-sm btn-warning" onclick="erlBulkReScanFromForm(this);">
                        <i class="icon-plus3 font-size-12"></i> Шинээр скан хийх
                    </button>
                    <button type="button" class="btn btn-sm btn-circle blue" onclick="erlSaveContentParamsCvl_<?php echo $this->uniqid; ?>(this);"><i class="fa fa-save"></i> Хадгалах</button>                    
                </div>
            </div>
            <div class="col-md-5">
                <span class="workflow-buttons-<?php echo $this->id; ?> float-right"></span>
            </div>
        </div>
    </div>

    <table style="table-layout: fixed; width: 100%; border-top: 1px #999 solid; border-bottom: 1px #999 solid; border-left: 1px #999 solid;">
        <tbody>
            <tr>
                <td style="width: 450px; vertical-align: top; padding: 0 10px 0 10px;">
                    <table class="table table-sm mb0">
                        <thead>
                            <tr>
                                <th style="width: 20px">№</th>
                                <th style="width: 95px">Файлын нэр</th>
                                <th style="width: 220px">Баримт бичгийн төрөл</th>
                                <th style="width: 95px">Олгосон огноо</th>
                            </tr>
                        </thead>
                    </table>
                    <div class="ecl-height">
                        <form class="xs-form">
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
                <td style="width: 100%; vertical-align: top; padding: 0;">
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

<div class="hidden hidden-html-<?php echo $this->uniqid ?>">
    <div id="metadata-<?php echo $this->uniqid ?>"></div>
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
    
    var $index_renderhtml_<?php echo $this->uniqid ?> = 1;
    var oldCvlBookType_<?php echo $this->uniqid ?> = '';
    var $cvlUniqHtmlContainer_<?php echo $this->uniqid ?> = '';
    var $viewFormMeta_<?php echo $this->uniqid ?> = $('#metadata-<?php echo $this->uniqid ?>');
    
    var ubegScanFtpLink = getConfigValue('ubegScanFtpLink');
    var ubegScanLink = getConfigValue('ubegScanLink');
    var ubegFtpUsername = getConfigValue('ftp_username');
    var ubegFtpPassword = getConfigValue('ftp_password');

    
    function appendHtml_<?php echo $this->uniqid ?>($trBookDate, selectedBookType, selectedErlCvlBookId, $trIndex) {
        
        var $mainSelectedTr = $('#windowid-erl-<?php echo $this->uniqid; ?> .erl-content-tbl > tbody > tr.cvlTable:eq('+ $trIndex +')');
        
        $('.bookHtml-<?php echo $this->uniqid ?>').hide();
        
        var $cvlBookId = (selectedErlCvlBookId) ? selectedErlCvlBookId : $mainSelectedTr.find('input[data-path="erlCvlBookId"]').val();
        
        var responseJson_<?php echo $this->uniqid; ?> = $.ajax({
            type: 'post',
            url: 'mddoc/runProcessValue', 
            data: {
                id: $cvlBookId
            },
            dataType: 'json',
            async: false
        });
        
        $('#metadataform-<?php echo $this->uniqid ?>').append('<div class="book-type bookHtml-<?php echo $this->uniqid ?> book-type-process-html-' + $trBookDate + '-' + selectedBookType + '" data-tr-index="'+ $trIndex +'"></div>');
            
        var $mainSelector = $('#metadataform-<?php echo $this->uniqid ?> > .book-type-process-html-' + $trBookDate + '-' + selectedBookType);
        
        $mainSelector.empty().append($cvlUniqHtmlContainer_<?php echo $this->uniqid ?>).promise().done(function() {

            var selectedTr = $('#windowid-erl-<?php echo $this->uniqid; ?>').find('.erl-content-tbl > tbody > tr:eq('+ $trIndex +')');


            var selectedBookType1 = selectedTr.find('select[data-path="cvlBookType"]').val();
            var selectedContentId1 = selectedTr.find('input[data-path="erlContentId"]').val();
            var selectedErlCvlBookId1 = selectedTr.find('input[data-path="erlCvlBookId"]').val();
            var selectedErlSemanticId1 = selectedTr.find('input[data-path="erlSemanticId"]').val();
            var selectedPackId1 = selectedTr.attr('data-hdr-id');

            if (typeof $mainSelector.find('.select2-container') !== 'undefined')  {

                $mainSelector.find('.select2-container').remove();
                $mainSelector.find('.select2').select2();

            }

            $mainSelector.find('input[data-path="bookTypeId"]').val(selectedBookType1).trigger('change');
            $mainSelector.find('input[data-path="CVL_DM_RECORD_MAP_DV.trgRecordId"]').val(selectedContentId1);
            $mainSelector.find('input[data-path="CVL_DM_RECORD_MAP_DV.id"]').val(selectedErlSemanticId1);
            $mainSelector.find('input[data-path="CVL_DM_RECORD_MAP_DV.srcRecordId"]').val(selectedErlCvlBookId1);
            $mainSelector.find('input[data-path="civilPackId"]').val(selectedPackId1);
            $mainSelector.show();

            if (typeof responseJson_<?php echo $this->uniqid; ?>.responseJSON !== 'undefined' && responseJson_<?php echo $this->uniqid; ?>.responseJSON) {

                $.each(responseJson_<?php echo $this->uniqid; ?>.responseJSON, function ($index, $row) {
                    if ($row) {
                        if ($index === 'bookdate' && $trBookDate) {
                            $mainSelector.find("input[data-path='"+ $index +"']").val($trBookDate);
                        } else {
                            $mainSelector.find("select[data-path='"+ $index +"']").trigger("select2-opening").select2('val', $row).select2("close");
                            $mainSelector.find("input[data-path='"+ $index +"']").val($row);
                        }
                    }
                });

            }
            $mainSelector.show();
        });
    }
    
    function cvlMetaProcess_<?php echo $this->uniqid ?>($trIndex, $type, selectedBookType, selectedContentId, selectedErlCvlBookId, selectedErlSemanticId, selectedPackId) {
    
        var $mainSelectedTr = $('#windowid-erl-<?php echo $this->uniqid; ?> .erl-content-tbl > tbody > tr.cvlTable:eq('+ $trIndex +')');
        
        if ($mainSelectedTr.attr('call-process-get') === '0') {
            var $cvlBookId = (selectedErlCvlBookId) ? selectedErlCvlBookId : $mainSelectedTr.find('input[data-path="erlCvlBookId"]').val();
            var responseJson_<?php echo $this->uniqid; ?> = $.ajax({
                type: 'post',
                url: 'mddoc/runProcessValue', 
                data: {
                    id: $cvlBookId
                },
                dataType: 'json',
                async: false
            });
        }
        
        var $trBookDate = $mainSelectedTr.find('input[data-path="cvl-bookdate"]').val();
        
        $('.bookHtml-<?php echo $this->uniqid ?>').hide();
        
        if (!$cvlUniqHtmlContainer_<?php echo $this->uniqid ?>) {
            $cvlUniqHtmlContainer_<?php echo $this->uniqid ?> = $viewFormMeta_<?php echo $this->uniqid ?>.html();
            $('#metadataform-<?php echo $this->uniqid ?>').find('.book-type-process-html-' + $trBookDate + '-' + selectedBookType).empty().append($cvlUniqHtmlContainer_<?php echo $this->uniqid ?>);
        }
        
        if (!$('#metadataform-<?php echo $this->uniqid ?>').find('.book-type-process-html-' + $trBookDate + '-' + selectedBookType).length) {
            
            $('#metadataform-<?php echo $this->uniqid ?>').append('<div class="book-type bookHtml-<?php echo $this->uniqid ?> book-type-process-html-' + $trBookDate + '-' + selectedBookType + '" data-tr-index="'+ $trIndex +'"></div>');
            
            var $mainSelector = $('#metadataform-<?php echo $this->uniqid ?> > .book-type-process-html-' + $trBookDate + '-' + selectedBookType);
            
            $mainSelector.empty().append($cvlUniqHtmlContainer_<?php echo $this->uniqid ?>).promise().done(function() {

                var selectedTr = $('#windowid-erl-<?php echo $this->uniqid; ?>').find('.erl-content-tbl > tbody > tr:eq('+ $trIndex +')');

                var selectedBookType1 = selectedTr.find('select[data-path="cvlBookType"]').val();
                var selectedContentId1 = selectedTr.find('input[data-path="erlContentId"]').val();
                var selectedErlCvlBookId1 = selectedTr.find('input[data-path="erlCvlBookId"]').val();
                var selectedErlSemanticId1 = selectedTr.find('input[data-path="erlSemanticId"]').val();
                var selectedPackId1 = selectedTr.attr('data-hdr-id');

                if (typeof $mainSelector.find('.select2-container') !== 'undefined')  {

                    $mainSelector.find('.select2-container').remove();
                    $mainSelector.find('.select2').select2();

                }

                $mainSelector.find('input[data-path="bookTypeId"]').val(selectedBookType1).trigger('change');
                $mainSelector.find('input[data-path="CVL_DM_RECORD_MAP_DV.trgRecordId"]').val(selectedContentId1);
                $mainSelector.find('input[data-path="CVL_DM_RECORD_MAP_DV.id"]').val(selectedErlSemanticId1);
                $mainSelector.find('input[data-path="CVL_DM_RECORD_MAP_DV.srcRecordId"]').val(selectedErlCvlBookId1);
                $mainSelector.find('input[data-path="civilPackId"]').val(selectedPackId1);
                $mainSelector.show();

                if ($mainSelectedTr.attr('call-process-get') === '0') {
                    if (typeof responseJson_<?php echo $this->uniqid; ?>.responseJSON !== 'undefined' && responseJson_<?php echo $this->uniqid; ?>.responseJSON) {

                        $.each(responseJson_<?php echo $this->uniqid; ?>.responseJSON, function ($index, $row) {
                            
                            if ($row) {
                                
                                $mainSelector.find("input[data-path='"+ $index +"']").val($row);
                                $mainSelector.find("select[data-path='"+ $index +"']").trigger("select2-opening").select2('val', $row).select2("close");
                            }
                        });

                    }
                }

            });
            
        } else {
            if ($type === 'change') {
                
                var $mainSelector = $('#metadataform-<?php echo $this->uniqid ?> > .book-type-process-html-' + $trBookDate + '-' + selectedBookType);
                
                $mainSelector.empty().append($cvlUniqHtmlContainer_<?php echo $this->uniqid ?>).promise().done(function() {
                
                    if (typeof $mainSelector.find('.select2-container') !== 'undefined')  {
                    
                        $mainSelector.find('.select2-container').remove();
                        $mainSelector.find('.select2').select2();

                    }
                    
                    $mainSelector.find('input[data-path="bookTypeId"]').val(selectedBookType).trigger('change');
                    
                    $mainSelector.find('input[data-path="CVL_DM_RECORD_MAP_DV.trgRecordId"]').val(selectedContentId);
                    $mainSelector.find('input[data-path="CVL_DM_RECORD_MAP_DV.id"]').val(selectedErlSemanticId);
                    $mainSelector.find('input[data-path="CVL_DM_RECORD_MAP_DV.srcRecordId"]').val(selectedErlCvlBookId);
                    $mainSelector.find('input[data-path="civilPackId"]').val(selectedPackId);
                    $mainSelector.show();
                    
                });
                
            } else {
                var $mainSelector = $('#metadataform-<?php echo $this->uniqid ?> > .book-type-process-html-' + $trBookDate + '-' + selectedBookType);
                
                if (!$('#metadataform-<?php echo $this->uniqid ?> > .book-type-process-html-' + $trBookDate + '-' + selectedBookType).children().length) {
                    $mainSelector.empty().append($cvlUniqHtmlContainer_<?php echo $this->uniqid ?>).promise().done(function() {
                        
                        var selectedTr = $('#windowid-erl-<?php echo $this->uniqid; ?>').find('.erl-content-tbl > tbody > tr:eq('+ $trIndex +')');

                        var selectedBookType1 = selectedTr.find('select[data-path="cvlBookType"]').val();
                        var selectedContentId1 = selectedTr.find('input[data-path="erlContentId"]').val();
                        var selectedErlCvlBookId1 = selectedTr.find('input[data-path="erlCvlBookId"]').val();
                        var selectedErlSemanticId1 = selectedTr.find('input[data-path="erlSemanticId"]').val();
                        var selectedPackId1 = selectedTr.attr('data-hdr-id');

                        if (typeof $mainSelector.find('.select2-container') !== 'undefined')  {

                            $mainSelector.find('.select2-container').remove();
                            $mainSelector.find('.select2').select2();

                        }

                        $mainSelector.find('input[data-path="bookTypeId"]').val(selectedBookType1).trigger('change');
                        $mainSelector.find('input[data-path="CVL_DM_RECORD_MAP_DV.trgRecordId"]').val(selectedContentId1);
                        $mainSelector.find('input[data-path="CVL_DM_RECORD_MAP_DV.id"]').val(selectedErlSemanticId1);
                        $mainSelector.find('input[data-path="CVL_DM_RECORD_MAP_DV.srcRecordId"]').val(selectedErlCvlBookId1);
                        $mainSelector.find('input[data-path="civilPackId"]').val(selectedPackId1);
                        $mainSelector.show();
                        var responseJson_<?php echo $this->uniqid; ?> = $.ajax({
                            type: 'post',
                            url: 'mddoc/runProcessValue', 
                            data: {
                                id: selectedErlCvlBookId1
                            },
                            dataType: 'json',
                            async: false
                        });
                            
                        if (typeof responseJson_<?php echo $this->uniqid; ?>.responseJSON !== 'undefined' && responseJson_<?php echo $this->uniqid; ?>.responseJSON) {

                            $.each(responseJson_<?php echo $this->uniqid; ?>.responseJSON, function ($index, $row) {
                                if ($index === 'bookdate' && $trBookDate) {
                                    $mainSelector.find("input[data-path='"+ $index +"']").val($trBookDate);
                                } else {
                                    $mainSelector.find("select[data-path='"+ $index +"']").trigger("select2-opening").select2('val', $row).select2("close");
                                    $mainSelector.find("input[data-path='"+ $index +"']").val($row);
                                }
                            });

                        }

                    });
                }
                
                if (typeof $mainSelector.find('.select2-container') !== 'undefined')  {

                    $mainSelector.find('.select2-container').remove();
                    $mainSelector.find('.select2').select2();

                }

                $mainSelector.find('input[data-path="bookTypeId"]').val(selectedBookType).trigger('change');
                $mainSelector.find('input[data-path="CVL_DM_RECORD_MAP_DV.trgRecordId"]').val(selectedContentId);
                $mainSelector.find('input[data-path="CVL_DM_RECORD_MAP_DV.id"]').val(selectedErlSemanticId);
                $mainSelector.find('input[data-path="CVL_DM_RECORD_MAP_DV.srcRecordId"]').val(selectedErlCvlBookId);
                $mainSelector.find('input[data-path="civilPackId"]').val(selectedPackId);
                $mainSelector.show();
            }
        }
        
        if ($mainSelectedTr.attr('call-process-get') === '0') {
            $mainSelectedTr.attr('call-process-get', '1');
        }
        
    }
    
    $(document.body).on('click', '.erl-content-tbl > tbody > tr.cvlTable', function() {
        var $this = $(this), $imagePanel = $('.erl-image-preview'), 
            uniqId = getUniqueId(1), $table = $this.closest('tbody');
        
        $table.find('.selected-row').removeClass('selected-row');
        $this.addClass('selected-row');
        
        var $row = $table.find('.selected-row:eq(0)'), imagePath = $row.attr('data-filepath'), 
            recordId = $row.attr('data-hdr-id');
        
        if (imagePath !== '') {
            
            var imgHeight = $(window).height();
            var uid = getUniqueId(1);
            
            $imagePanel.empty().append('<img style="height: '+(imgHeight - 170)+'px" src="'+ubegScanLink+'?scan_id='+recordId+'&filename='+imagePath+'&uid='+uid+'">');
        }
        
        $this.find(' > td > input:not(:hidden):first').focus().select();
        
        $imagePanel.parent().find('span').html('<i class="fa fa-search-plus"></i> Томоор харах');
        
        var selectedBookType = $this.find('select[data-path="cvlBookType"]').val();
        var selectedContentId = $this.find('input[data-path="erlContentId"]').val();
        var selectedErlCvlBookId = $this.find('input[data-path="erlCvlBookId"]').val();
        var selectedErlSemanticId = $this.find('input[data-path="erlSemanticId"]').val();
        var selectedPackId = $this.attr('data-hdr-id');

        if ($index_renderhtml_<?php echo $this->uniqid ?> === 1) {
            $index_renderhtml_<?php echo $this->uniqid ?> = 2;
            mainCallMethodProcess_<?php echo $this->uniqid ?>($this.index(), 'render', selectedBookType, selectedContentId, selectedErlCvlBookId, selectedErlSemanticId, selectedPackId);
        } else {
            cvlMetaProcess_<?php echo $this->uniqid ?>($this.index(), 'render', selectedBookType, selectedContentId, selectedErlCvlBookId, selectedErlSemanticId, selectedPackId);
        }
        
    });

    $(document.body).on('hover', '.erl-content-tbl > tbody > tr > td > select[data-path="cvlBookType"]', function(e, isTrigger) {
        oldCvlBookType_<?php echo $this->uniqid ?> = $(this).val();
    });
    
    $(document.body).on('select2-opening', '.erl-content-tbl > tbody > tr > td > select[data-path="cvlBookType"]', function(e, isTrigger) {
        oldCvlBookType_<?php echo $this->uniqid ?> = $(this).val();
        changeSelectorErlCvl_<?php echo $this->uniqid ?>(this);
    });
    
    $(document.body).on('change', '.erl-content-tbl > tbody > tr > td > select[data-path="cvlBookType"]', function() {
        var $this = $(this);
        var $thisValText = $this.find('option:selected').html(); 
        var $thisVal = $this.val(), 
            trindex = $this.closest('tr').index(),
            setVal = false;
        var $row = $this.closest('tr');
        
        var selectedContentId = $row.find('input[data-path="erlContentId"]').val();
        var selectedErlCvlBookId = $row.find('input[data-path="erlCvlBookId"]').val();
        var selectedErlSemanticId = $row.find('input[data-path="erlSemanticId"]').val();
        var selectedPackId = $row.attr('data-hdr-id');
            
        cvlMetaProcess_<?php echo $this->uniqid ?>(trindex, 'change', $thisVal, selectedContentId, selectedErlCvlBookId, selectedErlSemanticId, selectedPackId);
        
        var $trLoop = $('.erl-content-tbl > tbody > tr');
        
        $trLoop.each(function(k, v) {
            var $thisBook = $(v);
            
            if (k > trindex) {
                var $mainSelector = $thisBook.find('select[data-path="cvlBookType"]');
                
                if (($mainSelector.val() == oldCvlBookType_<?php echo $this->uniqid ?> || $mainSelector.val() == '') && !setVal) {
                    if ($mainSelector.find('option[value="'+ $thisVal +'"]').length > 0) {
                        $mainSelector.select2('val', $thisVal);
                    } else {
                        $mainSelector.empty();
                        $mainSelector.append('<option selected="selected" value="'+ $thisVal +'">'+ $thisValText +'</option>');
                        if ($mainSelector.hasClass('select2-offscreen')) {
                            $mainSelector.select2('val', $thisVal);
                        }
                    }
                    
                    
                } else {
                    setVal = true;
                }
            }
        });
    });
        
    $(document.body).on('focus', '#windowid-erl-<?php echo $this->uniqid; ?> input[data-path="cvl-bookdate"]', function() {
        var $this = $(this);
        inputOldVal = $this.val();

    });

    $(document.body).on('change', '#windowid-erl-<?php echo $this->uniqid; ?> input[data-path="cvl-bookdate"]', function(e) {
        var __t = $(this),
            _thisVal = __t.val(), 
            trindex = __t.closest('tr').index(),
            setVal = false,
            trs = __t.closest('tbody').children();

        var selectedBookType = __t.closest('tr').find('select[data-path="cvlBookType"]').val();
        if (new Date(_thisVal) < new Date('1900-01-01') || new Date(_thisVal) > new Date()) {
            __t.val('');
            return;
        }

        __t.val(_thisVal);
        
        if (!$('#metadataform-<?php echo $this->uniqid ?>').find('.book-type-process-html-' + _thisVal + '-' + selectedBookType).length) {
            appendHtml_<?php echo $this->uniqid ?>(_thisVal, selectedBookType, __t.closest('tr').find('input[data-path="erlCvlBookId"]').val(), trindex);
        }
        
        var $bookDateSelector = $('#metadataform-<?php echo $this->uniqid ?>').find('.book-type-process-html-' + _thisVal + '-' + selectedBookType).find('input[data-path="bookDate"]');
        
        if ($bookDateSelector.length > 0) {

            $bookDateSelector.val(_thisVal);
            $bookDateSelector.val(_thisVal);

        };

        trs.each(function(k, v) {
            var _t = $(this);

            if (k > trindex) {

                if((_t.find('input[name="bookDate[]"]').val() == '' || _t.find('input[name="bookDate[]"]').val() == inputOldVal) && !setVal) {              
                    _t.find('input[name="bookDate[]"]').val(_thisVal);

                    var $_bookDateSelector = $('#metadataform-<?php echo $this->uniqid ?>').find('.book-type-process-html-' + _thisVal + '-' + selectedBookType).find('input[data-path="bookDate"]');
                    if ($_bookDateSelector.length > 0) {

                        $_bookDateSelector.val(_thisVal);
                        $_bookDateSelector.val(_thisVal);

                    };

                } else {
                    setVal = true;
                }

            }
        });
    });  
    
    $(function() {
        
        var $erlContentTbl = $('.erl-content-tbl', '#windowid-erl-<?php echo $this->uniqid; ?>');
        
        var inputOldVal = '';
        
        $erlContentTbl.find('input[data-path="cvl-bookdate"]').inputmask("y-m-d");      
        
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
                                $('.workflow-buttons-<?php echo $this->id; ?>').append('<a href="javascript:;" ' + advancedCriteria + ' class="btn btn-circle btn-sm ml5" style="background-color: '+v.wfmstatuscolor+'; color: #fff;" onclick="changeWfmStatusId(this, \''+v.wfmstatusid+'\', \'<?php echo $this->metaDataId ?>\', \'<?php echo $this->refStructureId ?>\', \''+$.trim(v.wfmstatuscolor)+'\', \''+v.wfmstatusname+'\');" id="'+ v.wfmstatusid +'">'+ v.wfmstatusname +'</a>'); 
                            } else {
                                if (typeof v.wfmstatusname != 'undefined' && v.wfmstatusname != '' && (v.wfmstatusprocessid == '' || v.wfmstatusprocessid == 'null' || v.wfmstatusprocessid == null)) {
                                    if (v.wfmisneedsign == '1') {
                                        $('.workflow-buttons-<?php echo $this->id; ?>').append('<a href="javascript:;" ' + advancedCriteria + ' class="btn btn-circle btn-sm ml5" style="background-color: '+v.wfmstatuscolor+'; color: #fff;" onclick="beforeSignChangeWfmStatusId(this, \''+v.wfmstatusid+'\', \'<?php echo $this->metaDataId ?>\', \'<?php echo $this->refStructureId ?>\', \''+$.trim(v.wfmstatuscolor)+'\', \''+v.wfmstatusname+'\');" id="'+ v.wfmstatusid +'">'+ v.wfmstatusname +' <i class="fa fa-key"></i></a>'); 
                                    } else if (v.wfmisneedsign == '2') {
                                        $('.workflow-buttons-<?php echo $this->id; ?>').append('<a href="javascript:;" ' + advancedCriteria + ' class="btn btn-circle btn-sm ml5" style="background-color: '+v.wfmstatuscolor+'; color: #fff;" onclick="beforeHardSignChangeWfmStatusId(this, \''+v.wfmstatusid+'\', \'<?php echo $this->metaDataId ?>\', \'<?php echo $this->refStructureId ?>\', \''+$.trim(v.wfmstatuscolor)+'\', \''+v.wfmstatusname+'\');" id="'+ v.wfmstatusid +'">'+ v.wfmstatusname +' <i class="fa fa-key"></i></a>'); 
                                    } else {
                                        $('.workflow-buttons-<?php echo $this->id; ?>').append('<a href="javascript:;" ' + advancedCriteria + ' class="btn btn-circle btn-sm ml5" style="background-color: '+v.wfmstatuscolor+'; color: #fff;" onclick="changeWfmStatusId(this, \''+v.wfmstatusid+'\', \'<?php echo $this->metaDataId ?>\', \'<?php echo $this->refStructureId ?>\', \''+$.trim(v.wfmstatuscolor)+'\', \''+v.wfmstatusname+'\');" id="'+ v.wfmstatusid +'">'+ v.wfmstatusname +'</a>'); 
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
        
    });
    
    function mainCallMethodProcess_<?php echo $this->uniqid ?>($thisIndex, $type, selectedBookType, selectedContentId, selectedErlCvlBookId, selectedErlSemanticId, selectedPackId) {
        Core.blockUI({
            target: '#windowid-erl-<?php echo $this->uniqid; ?>',
            animate: true
        });
        
        var $processParam_<?php echo $this->uniqid ?> = {
            metaDataId: '1533273238626',
            isDialog: true,
            isSystemMeta: false,
            runDefaultGet: 0,
        };
        
        if (!$cvlUniqHtmlContainer_<?php echo $this->uniqid ?>) {
            $.ajax({
                type: 'post',
                url: 'mdwebservice/callMethodByMeta',
                data: $processParam_<?php echo $this->uniqid ?>,
                dataType: 'json',
                beforeSend: function () {
                    Core.blockUI({
                        message: 'Loading...', 
                        boxed: true
                    });
                },
                success: function (data) {
                    
                    $viewFormMeta_<?php echo $this->uniqid ?>.empty().append(data.Html).promise().done(function () {
                        Core.initBPAjax($viewFormMeta_<?php echo $this->uniqid ?>);

                        $viewFormMeta_<?php echo $this->uniqid ?>.find('td[data-cell-path="bookTypeId"]').closest('tr').addClass('hidden');
                        
                        $viewFormMeta_<?php echo $this->uniqid ?>.find('.select2-container').remove();
                        $viewFormMeta_<?php echo $this->uniqid ?>.find('.select2').select2();
                        
                        cvlMetaProcess_<?php echo $this->uniqid ?>(0, 'revert', selectedBookType, selectedContentId, selectedErlCvlBookId, selectedErlSemanticId, selectedPackId);

                        Core.unblockUI($('#windowid-erl-<?php echo $this->uniqid; ?>'));
                        
                        $cvlUniqHtmlContainer_<?php echo $this->uniqid ?> = data.Html;
                    });

                    Core.unblockUI();
                },
                error: function () {
                    alert('Error');
                }
            });
        } else {
            $viewFormMeta_<?php echo $this->uniqid ?>.empty().append($cvlUniqHtmlContainer_<?php echo $this->uniqid ?>).promise().done(function () {
                Core.initBPAjax($viewFormMeta_<?php echo $this->uniqid ?>);

                $viewFormMeta_<?php echo $this->uniqid ?>.find('td[data-cell-path="bookTypeId"]').closest('tr').addClass('hidden');

                $viewFormMeta_<?php echo $this->uniqid ?>.find('.select2-container').remove();
                $viewFormMeta_<?php echo $this->uniqid ?>.find('.select2').select2();
                
                cvlMetaProcess_<?php echo $this->uniqid ?>(0, 'revert', selectedBookType, selectedContentId, selectedErlCvlBookId, selectedErlSemanticId, selectedPackId);

                Core.unblockUI($('#windowid-erl-<?php echo $this->uniqid; ?>'));

            });
        }
        
    }

    function changeSelectorErlCvl_<?php echo $this->uniqid ?>(element) {

        var $this = $(element);
        var $parent = $this.parent();

        var $inputMetaDataId = '1532482967376752';

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
    
    function erlSaveContentParamsCvl_<?php echo $this->uniqid; ?>(elem) {
        var $this = $(elem);
        var $parent = $this.closest('.erl-parent');
        var $serializeData = [];
        var $mainWindowTable = $this.closest('#windowid-erl-<?php echo $this->uniqid; ?>').find('table.erl-content-tbl > tbody > tr');
        
        $mainWindowTable.each(function ($indexTr, $rowTr) {
            var $selectedTr = $($rowTr);
            
            var $contentBookDate = $selectedTr.find('input[data-path="cvl-bookdate"]').val();
            var $cvlBookType = $selectedTr.find('select[data-path="cvlBookType"]').val();
            
            $this.closest('.erl-parent').find('.book-type-process-html-' + $contentBookDate + '-' + $cvlBookType).each(function ($index, $row) {
                if ($($row).find('#wsForm').length > 0) {
                    $serializeData.push($($row).find('#wsForm').serialize());
                }
            });
            
        });
        

        Core.blockUI({
            message: 'Loading...',
            boxed: true
        });      

        $.ajax({
            type: 'post',
            url: 'mddoc/runProcessBefore',
            dataType: 'json',
            data: {recordId:$parent.data('id'), serializeData: $serializeData},
            async: false,
            beforeSend: function () {
                Core.blockUI({
                    message: 'Түр хүлээнэ үү...',
                    boxed: true
                });
            },
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
                Core.unblockUI();  

                if (responseData.status == 'success') {
                    $('.erl-content-tbl > tbody').empty().append(responseData.fileRender);

                    $('.erl-content-tbl').find('input[name="bookDate[]"]').inputmask("y-m-d");

                    Core.initSelect2($('.erl-content-tbl'));                    

                    $this.closest('.table-toolbar').find('span[class^="workflow-buttons-"]').children().removeClass('disabled');

                    $('.erl-content-tbl > tbody > tr:eq(0)').click();
                }     

            },
            error: function () {
                Core.unblockUI();  
                alert('Error');
            }
        });

        return;
    }
    
</script>
<style  type="text/css">
    div[aria-describedby="dialog-changeWfmStatus-<?php echo $this->metaDataId ?>"] {
        z-index: 1052;
    }
</style>