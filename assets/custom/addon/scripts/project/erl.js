var comboOldVal_bookTypeId = '';
var comboOldVal_contentTypeId = '';
var bookTypeData = [];
var contentTypeData = [];
var contentTypeHtml = '';
var boookTypeHtml = '';
var IS_LOAD_ERL_SCRIPT = true;

$(function() {
    
    $(document.body).on('click', '.erl-content-tbl > tbody > tr:not(.cvlTable)', function() {
        var $this = $(this), $imagePanel = $('.erl-image-preview'), $table = $this.closest('tbody');
        
        $table.find('.selected-row').removeClass('selected-row');
        $this.addClass('selected-row');
        
        var imagePath = $this.attr('data-filepath'), recordId = $this.attr('data-hdr-id');
        var ubegScanLink = CONFIG_URL + getConfigValue('ubegScanLink');
        
        if (imagePath !== '') {
            var imgHeight = $(window).height();
            var uid = getUniqueId(1);
            $imagePanel.empty().append('<img style="height: '+(imgHeight - 205)+'px" src="'+ubegScanLink+'?scan_id='+recordId+'&filename='+imagePath+'&uid='+uid+'">');
        }
        
        $imagePanel.parent().find('span').html('<i class="fa fa-search-plus"></i> Томоор харах');
        
        $this.find(' > td > input:not(:hidden):first').focus().select();
    });
    
    /*$(document.body).on('keydown', '.erl-content-tbl > tbody > tr > td > select', function(e, isTrigger) {
        if (e.keyCode == 40) {
            $(this).select2('open');
            e.preventDefault();
            e.stopPropagation();
        }
    });*/
    
    /*$(document.body).on('hover', '.erl-content-tbl > tbody > tr > td > select[data-path="bookTypeId"]', function(e, isTrigger) {
        comboOldVal_bookTypeId = $(this).val();
    });
    
    $(document.body).on('hover', '.erl-content-tbl > tbody > tr > td > select[data-path="contentTypeId"]', function(e, isTrigger) {
        comboOldVal_contentTypeId = $(this).val();
    });*/
    
    $(document.body).on('select2-opening', '.erl-content-tbl > tbody > tr > td > select[data-path="bookTypeId"]', function(e, isTrigger) {
        var $this = $(this);
        comboOldVal_bookTypeId = $this.val();
        changeSelectorErl($this);
    });
    
    $(document.body).on('select2-opening', '.erl-content-tbl > tbody > tr > td > select[data-path="contentTypeId"]', function(e, isTrigger) {
        var $this = $(this);
        comboOldVal_contentTypeId = $this.val();
        changeSelectorErl_contentType($this);
    });
    
    $(document.body).on('change', '.erl-content-tbl > tbody > tr > td > select[data-path="bookTypeId"]', function() {
        var $this = $(this), 
            $thisValText = $this.find('option:selected').text(), 
            $thisVal = $this.val(), 
            $parentRow = $this.closest('tr'), 
            trindex = $parentRow.index(),
            oldId = $this.attr('data-oldval'), 
            setVal = false;
            
        //$parentRow.find('input[name="erlCompanyBookId[]"]').attr('value', '');
        
        var $trLoop = $this.closest('tbody').find('> tr');
        var len = $trLoop.length, i = 0;
        
        $this.attr('data-oldval', $thisVal);
        
        for (i; i < len; i++) { 
            
            if (i > trindex) {
                var $thisRow = $($trLoop[i]);
                var $mainSelector = $thisRow.find('select[data-path="bookTypeId"]');
                
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
                    //$thisRow.find('input[name="erlCompanyBookId[]"]').attr('value', '');
                    
                } else {
                    setVal = true;
                }
            }
        }
        
        /*$trLoop.each(function(k, v) {
            if (k > trindex) {
                
                var $thisRow = $(v);
                var $mainSelector = $thisRow.find('select[data-path="bookTypeId"]');
                
                if (($mainSelector.val() == comboOldVal_bookTypeId || $mainSelector.val() == '') && !setVal) {
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
                    
                    $thisRow.find('input[name="erlCompanyBookId[]"]').attr('value', '');
                    
                } else {
                    setVal = true;
                }
            }
        });*/
    });
    
    $(document.body).on('change', '.erl-content-tbl > tbody > tr > td > select[data-path="contentTypeId"]', function() {
        var $this = $(this), 
            $thisValText = $this.find('option:selected').text(), 
            $thisVal = $this.val(), 
            $parentRow = $this.closest('tr'), 
            trindex = $parentRow.index(),
            oldId = $this.attr('data-oldval'), 
            setVal = false;
            
        var $trLoop = $this.closest('tbody').find('> tr');
        var len = $trLoop.length, i = 0;
        
        $this.attr('data-oldval', $thisVal);
        
        for (i; i < len; i++) { 
            
            if (i > trindex) {
                
                var $thisRow = $($trLoop[i]);
                var $mainSelector = $thisRow.find('select[data-path="contentTypeId"]');
                
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
        
        /*$trLoop.each(function(k, v) {
            if (k > trindex) {
                
                var $thisRow = $(v);
                var $mainSelector = $thisRow.find('select[data-path="contentTypeId"]');
                
                if (($mainSelector.val() == comboOldVal_contentTypeId || $mainSelector.val() == '') && !setVal) {
                    
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
                    
                } else {
                    setVal = true;
                }
            }
        });*/
    });
    
    $(document.body).on('keyup', ".erl-bookdate", function (e) {
        var keyCode = (e.keyCode ? e.keyCode : e.which);
        var $this = $(this);

        if (!e.shiftKey && keyCode === 13) { // enter 

            e.preventDefault();

        } else if (keyCode === 38) { // up

            var $dtlTbl = $this.closest('table');

            if ($dtlTbl.hasClass('erl-content-tbl')) {
                var $rowCell = $this.closest('td'); 
                var $row = $this.closest('tr');
                var $prevRow = $row.prev('tr:visible');
                var $colIndex = $rowCell.index();

                if ($prevRow.length) {

                    var $parentScrollDiv = $dtlTbl.closest('.ecl-height');
                    var scrollHeight = $parentScrollDiv[0].scrollHeight;
                    var clientHeight = $parentScrollDiv[0].clientHeight;

                    if (scrollHeight !== clientHeight) {
                        var parentScrollTop = $parentScrollDiv.scrollTop();

                        if (parentScrollTop > 0) {
                            var prevRowTop = $prevRow.position().top;
                            $parentScrollDiv.scrollTop(parentScrollTop - 19);
                        }
                    }

                    //$row.find('td:eq('+$colIndex+') input:not(:hidden):first').datepicker('hide');
                    $prevRow.click();
                    $prevRow.find('td:eq('+$colIndex+') input:not(:hidden):first').focus().select();
                }
            }

            return e.preventDefault();
            
        } else if (keyCode === 40) { // down

            var $dtlTbl = $this.closest('table');

            if ($dtlTbl.hasClass('erl-content-tbl')) {

                var $rowCell = $this.closest('td'); 
                var $row = $this.closest('tr');
                var $nextRow = $row.next('tr:visible');
                var $colIndex = $rowCell.index();

                if ($nextRow.length) {

                    var $parentScrollDiv = $dtlTbl.closest('.ecl-height');
                    var scrollHeight = $parentScrollDiv[0].scrollHeight;
                    var clientHeight = $parentScrollDiv[0].clientHeight;

                    if (scrollHeight !== clientHeight) {
                        var nextRowTop = $nextRow.position().top;
                        var parentScrollTop = $parentScrollDiv.scrollTop();

                        if (nextRowTop == clientHeight) {
                            $parentScrollDiv.scrollTop(10);
                        } else if (nextRowTop > clientHeight) {
                            $parentScrollDiv.scrollTop(nextRowTop - clientHeight + parentScrollTop);
                        }
                    }

                    //$row.find('td:eq('+$colIndex+') input:not(:hidden):first').datepicker('hide');
                    $nextRow.click();
                    $nextRow.find('td:eq('+$colIndex+') input:not(:hidden):first').focus().select();
                }
            }

            return e.preventDefault();

        } else if (keyCode === 9) { // tab

            var $dtlTbl = $this.closest('table');

            if ($dtlTbl.hasClass('erl-content-tbl')) {

                var $rowCell = $this.closest('td'); 
                var $row = $this.closest('tr');
                var $nextRow = $row;
                var $colIndex = $rowCell.index();

                if ($nextRow.length) {

                    var $parentScrollDiv = $dtlTbl.closest('.ecl-height');
                    var scrollHeight = $parentScrollDiv[0].scrollHeight;
                    var clientHeight = $parentScrollDiv[0].clientHeight;

                    if (scrollHeight !== clientHeight) {
                        var nextRowTop = $nextRow.position().top;
                        var parentScrollTop = $parentScrollDiv.scrollTop();

                        if (nextRowTop == clientHeight) {
                            $parentScrollDiv.scrollTop(10);
                        } else if (nextRowTop > clientHeight) {
                            $parentScrollDiv.scrollTop(nextRowTop - clientHeight + parentScrollTop);
                        }
                    }

                    //$row.find('td:eq('+$colIndex+') input:not(:hidden):first').datepicker('hide');
                    $nextRow.click();
                    $nextRow.find('td:eq('+$colIndex+') input:not(:hidden):first').focus().select();
                }
            }

            return e.preventDefault();
        }
    });    
    
    $.ajax({
        type: 'post',
        async: false,
        url: 'mddoc/callDvData',
        data: {inputMetaDataId: '1529300693049215'},
        dataType: 'json',
        success: function(data) {
            if (typeof data.emptyCombo === 'undefined') {
                bookTypeData = data.data;
                
                boookTypeHtml += '<option value="">- Сонгох -</option>';
                $.each(bookTypeData, function (index, row) {
                    boookTypeHtml += '<option value="' + row.META_VALUE_ID + '">' + row.META_VALUE_NAME + '</option>';
                });                   
            }
        }
    });    
    
    $.ajax({
        type: 'post',
        async: false,
        url: 'mddoc/callDvData',
        data: {inputMetaDataId: '1529014411545'},
        dataType: 'json',
        success: function(data) {
            if (typeof data.emptyCombo === 'undefined') {
                contentTypeData = data.data;
                
                contentTypeHtml += '<option value="">- Сонгох -</option>';
                $.each(contentTypeData, function (index, row) {
                    contentTypeHtml += '<option value="' + row.META_VALUE_ID + '">' + row.META_VALUE_NAME + '</option>';
                });                   
            }
        }
    });
    
    /*$(document.body).on('keydown', '.erl-content-tbl > tbody > tr > td > select[data-path="bookTypeId"]', function(e, isTrigger) {
        var keyCode = (e.keyCode ? e.keyCode : e.which);
        var $this = $(this);
        
        if(keyCode !== 9) {
            //$this.select2();
            setTimeout(function() {
                $this.trigger('select2-opening');
            }, 0);            
        }
    });    
    
    $(document.body).on('keydown', '.erl-content-tbl > tbody > tr > td > select[data-path="contentTypeId"]', function(e, isTrigger) {
        var keyCode = (e.keyCode ? e.keyCode : e.which);
        var $this = $(this);
        
        if(keyCode !== 9) {
            //$this.select2();
            setTimeout(function() {
                $this.trigger('select2-opening');
            }, 0);            
        }
    });*/
     
});

function functionFocusSelect(element) {
    $(element).select2();
    setTimeout(function() {
        $(element).trigger('select2-opening');
    }, 0);
}

function electronRegisterLegalInit(elem, processMetaDataId, dataViewId, selectedRow, paramData, $type) {

    var $mainUrl = 'mddoc/electronRegisterLegal';
    
    if (typeof $type !== 'undefined') {
        switch ($type) {
            case '2': 
                $mainUrl = 'mddoc/electronRegisterLegalCivil';
                break;
            case '4': 
                $mainUrl = 'mddoc/electronRegisterCnt';
                break;
            case '5': 
                $mainUrl = 'mddoc/elcRegisterLegalBook';
                break;
        }
    }
    
    var newParamData = paramDataToObject(paramData);
    
    $.ajax({
        type: 'post',
        url: $mainUrl,
        data: {selectedRow: selectedRow, paramData: newParamData, type: $type},
        dataType: 'json',
        beforeSend: function () {
            Core.blockUI({
                message: 'Loading...',
                boxed: true
            });
        },
        success: function (data) {
            if (typeof $type !== 'undefined' && ($type == '2' || $type == '4') && typeof data.status !== 'undefined' && data.status !== 'success') {
                PNotify.removeAll();
                new PNotify({
                    title: data.status,
                    text: data.message,
                    type: data.status,
                    sticker: false
                });
                
                Core.unblockUI();
                return;
            }
            
            var $dialogName = 'dialog-erl-'+data.uniqId;
            var windowHeight = $(window).height();
            
            $('<div class="modal pl0 fade modal-after-save-close" id="'+ $dialogName +'" tabindex="-1" role="dialog" aria-hidden="true">'+
                '<div class="modal-dialog modal-full" style="margin-top: 10px;">'+
                    '<div class="modal-content">'+
                '<div class="modal-header">'+
                    '<h4 class="modal-title">' + data.title + '</h4>'+
                    '<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>'+
                '</div>'+
                '<div class="modal-body">'+
                    data.html+
                '</div>'+
                '<div class="modal-footer">'+
                    '<button type="button" data-dismiss="modal" class="btn blue-hoki btn-sm">' + data.close_btn + '</button>'+
                '</div></div></div></div>').appendTo('body');
        
            var $dialog   = $('#' + $dialogName);
            var $eclCivil = $dialog.find('.ecl-civil-height');
            
            $dialog.find('.erl-image-preview').css({'height': windowHeight - 165});
            $dialog.find('.ecl-height').css({'height': windowHeight - 270});
            
            if ($eclCivil.length) {
                $eclCivil.css({'height': windowHeight - 300});
                $eclCivil.find('.bookMeta').css({'height': windowHeight - 320});
            }
            
            $dialog.modal();
            
            $dialog.on('shown.bs.modal', function () {
                $dialog.find('.erl-content-tbl > tbody > tr:eq(0)').click();     
                setTimeout(function(){
                    $dialog.find('.erl-content-tbl > tbody > tr:eq(0) > td:eq(2) > input').focus().select();
                    Core.unblockUI();
                }, 10);    
                disableScrolling();
            });   
            
            $dialog.on('hidden.bs.modal', function () {
                
                if (typeof $type !== 'undefined' && $type == '2') {
                    
                    dataViewReload('1533714393827725'); 
                    
                    if (dataViewSearchParamFocus('1533714393827725', 'filterregisternumber') == false) {
                        dataViewFirstColumnFocus('1533714393827725');
                    } 
                }
                
                if (typeof $type !== 'undefined' && $type == '4') {
                    //dataViewReload('1536131133813'); 
                    dataViewFirstColumnFocus('1536131133813');
                    
                    dataViewReload('1542010065976'); 
                    dataViewFirstColumnFocus('1542010065976');
                }
                
                $dialog.remove();
                enableScrolling();
            });            
        }
    });
}

function erlSaveContentParams(elem, saveProcessCode, refStructureId) {
    var $this = $(elem), $form = $this.closest('.erl-parent').find('form');
    
    $form.validate({errorPlacement: function () {}});
    
    if ($form.valid()) {
        var $rows = $('.erl-content-tbl > tbody > tr.selected-row');
        var $parent = $this.closest('.erl-parent');

        Core.blockUI({
            message: 'Saving...',
            boxed: true
        });              
        
        setTimeout(function() {
            var $requiredInputs = $parent.find('.erl-content-tbl > tbody input[name="bookDate[]"], .erl-content-tbl > tbody select').filter(function() { $(this).removeClass('error'); return this.value == ''; });

            if ($requiredInputs.length) {
                $requiredInputs.addClass('error');
                
                PNotify.removeAll();
                new PNotify({
                    title: 'Warning',
                    text: 'Мэдээллээ бүрэн гүйцэд оруулна уу!', 
                    type: 'warning',
                    sticker: false
                });     
                Core.unblockUI();
                
                var $firstInput = $requiredInputs.eq(0);
                
                if ($firstInput.prop('tagName') == 'INPUT') {
                    $firstInput.focus().select();
                } else {
                    if ($firstInput.hasClass('select2-offscreen')) {
                        $firstInput.select2('focus');
                    } else {
                        $firstInput.focus();
                    }
                }
                
                return;
            }       

            $.ajax({
                type: 'post',
                url: 'mddoc/electronRegisterLegalSave',
                data: 'saveProcessCode='+saveProcessCode+'&refStructureId='+refStructureId+'&recordId='+$parent.data('id')+'&'+$form.serialize()+'&'+$rows.find('input').serialize(),
                dataType: 'json',
                async: false, 
                success: function (data) {
                    PNotify.removeAll();
                    new PNotify({
                        title: data.status,
                        text: data.message,
                        type: data.status,
                        addclass: 'pnotify-center',
                        sticker: false
                    });

                    if (data.status == 'success') {
                        if (data.hasOwnProperty('closeModal')) {
                            
                            $parent.closest('.modal-after-save-close').modal('hide');
                            dataViewReload(data.dataViewId);
                            
                            Core.unblockUI();
                            
                        } else {
                            
                            $this.closest('.table-toolbar').find('span[class^="workflow-buttons-"]').children().removeClass('disabled');
                        
                            $('.erl-content-tbl > tbody').empty().append(data.fileRender).promise().done(function(){
                                $('.erl-content-tbl > tbody > tr:eq(0)').click();
                                Core.unblockUI();
                            });

                            //$('.erl-content-tbl > tbody > tr:eq(0)').click();

                            /*var $rowsIds = $('.erl-content-tbl > tbody > tr');
                            var rowsIdsLength = $rowsIds.length, r = 0;
                            var rowsIdsObj = data.rowsIds;

                            for (r; r < rowsIdsLength; r++) { 
                                var $rowsId = $($rowsIds[r]);
                                $rowsId.find('input[name="erlCompanyBookId[]"]').attr('value', rowsIdsObj[r]['companyBookId']);
                                $rowsId.find('input[name="erlSemanticId[]"]').attr('value', rowsIdsObj[r]['semanticId']);
                            }*/
                        }
                    } else {
                        Core.unblockUI();
                    }
                }
            });
        }, 250);        
    }
    
    return;
}

function erlSaveBookContentParams(elem, saveProcessCode, refStructureId, companyKeyId) {
    var $this = $(elem), $form = $this.closest('.erl-parent').find('form');
    
    $form.validate({errorPlacement: function () {}});
    
    if ($form.valid()) {
        var $rows = $('.erl-content-tbl > tbody > tr.selected-row');
        var $parent = $this.closest('.erl-parent');

        Core.blockUI({
            message: 'Saving...',
            boxed: true
        });              
        
        setTimeout(function() {
            var $requiredInputs = $parent.find('.erl-content-tbl > tbody input[name="bookDate[]"], .erl-content-tbl > tbody select').filter(function() { $(this).removeClass('error'); return this.value == ''; });

            if ($requiredInputs.length) {
                $requiredInputs.addClass('error');
                
                PNotify.removeAll();
                new PNotify({
                    title: 'Warning',
                    text: 'Мэдээллээ бүрэн гүйцэд оруулна уу!', 
                    type: 'warning',
                    sticker: false
                });     
                Core.unblockUI();
                
                var $firstInput = $requiredInputs.eq(0);
                
                if ($firstInput.prop('tagName') == 'INPUT') {
                    $firstInput.focus().select();
                } else {
                    if ($firstInput.hasClass('select2-offscreen')) {
                        $firstInput.select2('focus');
                    } else {
                        $firstInput.focus();
                    }
                }
                
                return;
            }       

            $.ajax({
                type: 'post',
                url: 'mddoc/elcRegisterBookLegalSave',
                data: 'companyKeyId='+ companyKeyId +'&saveProcessCode='+saveProcessCode+'&refStructureId='+refStructureId+'&recordId='+$parent.data('id')+'&'+$form.serialize(),
                dataType: 'json',
                async: false, 
                success: function (data) {
                    PNotify.removeAll();
                    new PNotify({
                        title: data.status,
                        text: data.message,
                        type: data.status,
                        sticker: false
                    });

                    if (data.status == 'success') {
                        
                        $this.closest('.table-toolbar').find('span[class^="workflow-buttons-"]').children().removeClass('disabled');
                        
                        $('.erl-content-tbl > tbody').empty().append(data.fileRender).promise().done(function(){
                            $('.erl-content-tbl > tbody > tr:eq(0)').click();
                            Core.unblockUI();
                        });
                        
                        //$('.erl-content-tbl > tbody > tr:eq(0)').click();
                        
                        /*var $rowsIds = $('.erl-content-tbl > tbody > tr');
                        var rowsIdsLength = $rowsIds.length, r = 0;
                        var rowsIdsObj = data.rowsIds;
                        
                        for (r; r < rowsIdsLength; r++) { 
                            var $rowsId = $($rowsIds[r]);
                            $rowsId.find('input[name="erlCompanyBookId[]"]').attr('value', rowsIdsObj[r]['companyBookId']);
                            $rowsId.find('input[name="erlSemanticId[]"]').attr('value', rowsIdsObj[r]['semanticId']);
                        }*/
                    } else {
                        Core.unblockUI();
                    }
                }
            });
        }, 250);        
    }
    
    return;
}

function saveNextProcess($processFormParent, $index) {
    $processFormParent.find('.book-type:eq('+ $index +')').find('#wsForm').ajaxSubmit({
        type: 'post',
        url: 'mdwebservice/runProcess',
        dataType: 'json',
        iframe: true,
        beforeSend: function () {
            Core.blockUI({
                message: 'Түр хүлээнэ үү...',
                boxed: true
            });
        },
        success: function (responseData) {

            PNotify.removeAll();
            new PNotify({
                title: responseData.status,
                text: responseData.message,
                type: responseData.status,
                sticker: false
            });
                
            if (responseData.status === 'success') {
                
                $index++;
                
                if ($processFormParent.find('.book-type:eq('+ $index +')').length > 0) {
                    saveNextProcess($processFormParent, $index);
                }
            } 
            
            Core.unblockUI();  
        },
        error: function () {
            alert('Error');
        }
    });
}

function erlBulkNewScanFromForm(elem, paramData) {
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
        
        if ($parent.find('.erl-content-tbl > tbody > tr').length)
            scanType = 'new';

        ws.onopen = function () {
            var currentDateTime = erlGetCurrentDateTime();        
            var ubegScanFtpLink = getConfigValue('ubegScanFtpLink');
            var ubegFtpUsername = getConfigValue('ftp_username');
            var ubegFtpPassword = getConfigValue('ftp_password');
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
                    url: 'mddoc/electronRegisterLegalBulkScan',
                    data: {recordId: recordId, filesObj: filesObj, paramData: paramData},
                    dataType: 'json',
                    async: false, 
                    success: function (data) {
                        $('.erl-content-tbl > tbody').empty().append(data.fileRender);
                        $('#erl-file-count').text(data.fileCount);
                        $('.erl-content-tbl > tbody > tr:eq(0)').click();
                        $('.erl-content-tbl > tbody > tr:eq(0)').find('input:not(:hidden):first').focus().select();
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
                    
                    /*var jsonData = {"command":"bulk_scan","dateTime":"2018/06/18 16:59:30","status":"success","description":null,"planText":null,"cypherText":null,"details":[{"key":"scan_id","value":"1"},{"key":"files","value":"00000.tif,00001.tif,00002.tif,00003.tif,00004.tif,00005.tif"}]};
                    var filesObj = convertDataElementToArray(jsonData.details);

                    $.ajax({
                        type: 'post',
                        url: 'mddoc/electronRegisterLegalBulkScan',
                        data: {recordId: recordId, filesObj: filesObj},
                        dataType: 'json',
                        async: false, 
                        success: function (data) {
                            $('.erl-content-tbl > tbody').empty().append(data.fileRender);
                            $('.erl-content-tbl > tbody > tr').click();
                        }
                    });*/
                }
            }
            
            Core.unblockUI();
            Pace.stop();
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
            Pace.stop();Core.unblockUI();
        };

        ws.onclose = function () {
            console.log("Connection is closed...");
            Pace.stop();Core.unblockUI();
        };
        
    } else {
        
        PNotify.removeAll();
        new PNotify({
            title: 'Error',
            text: 'WebSocket NOT supported by your Browser!', 
            type: 'error',
            sticker: false
        });
        
        Pace.stop();Core.unblockUI();
    }
}

function erlBulkReScanFromForm(elem, paramData) {
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
                            var ubegScanFtpLink = getConfigValue('ubegScanFtpLink');
                            var ubegFtpUsername = getConfigValue('ftp_username');
                            var ubegFtpPassword = getConfigValue('ftp_password');
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
                                    url: 'mddoc/electronRegisterLegalBulkReScan',
                                    data: {recordId: recordId, filesObj: filesObj, paramData: paramData},
                                    dataType: 'json',
                                    async: false, 
                                    success: function (data) {
                                        $('.erl-content-tbl > tbody').empty().append(data.fileRender); 
                                        $('#erl-file-count').text(data.fileCount);                                   
                                        $('.erl-content-tbl > tbody > tr:eq(0)').click();
                                        $('.erl-content-tbl > tbody > tr:eq(0)').find('input:not(:hidden):first').focus().select();
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

                            Pace.stop();Core.unblockUI();
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
                            Pace.stop();Core.unblockUI();
                        };

                        ws.onclose = function () {
                            console.log("Connection is closed...");
                            Pace.stop();Core.unblockUI();
                        };

                    } else {

                        PNotify.removeAll();
                        new PNotify({
                            title: 'Error',
                            text: 'WebSocket NOT supported by your Browser!', 
                            type: 'error',
                            sticker: false
                        });

                        Pace.stop();Core.unblockUI();
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

function erlDirectScanInit(elem, processMetaDataId, dataViewId, selectedRow, paramData, $type, variable) {
    var addinVariable = (typeof variable !== 'undefined') ? variable : '';
    Core.blockUI({
        boxed: true, 
        message: 'Loading...'
    });
    
    if ("WebSocket" in window) {
        console.log("WebSocket is supported by your Browser!");
        
        var name = '';
        var timeId = erlGetCurrentDateTimeId();
        /* var recordId = getConfigValue('CIVIL_OFFLINE_SERVER') === '1' ? selectedRow.civilpackid : selectedRow.id; */
        var recordId = (typeof selectedRow.recordid !== 'undefined' ? selectedRow.recordid : selectedRow.id);
        var newParamData = paramDataToObject(paramData);
        
        if (typeof $type !== 'undefined' && ($type == '2' || $type == '4')) {
            name = (selectedRow.stateregnumber + ' - '+ selectedRow.name).toUpperCase();
        } else if (typeof $type !== 'undefined' && $type == '3') {
            recordId = recordId + '_' + timeId;
        } else {
            name = (selectedRow.companyregiternumber + ' - '+ selectedRow.companyname).toUpperCase();
        }
        
        if (selectedRow.hasOwnProperty('ftpscanid')) {
            recordId = selectedRow.ftpscanid;
        }
        
        var ws = new WebSocket("ws://localhost:58324/socket");

        ws.onopen = function () {
            var currentDateTime = erlGetCurrentDateTime();
            var ubegScanFtpLink = getConfigValue('ubegScanFtpLink');
            var ubegFtpUsername = getConfigValue('ftp_username');
            var ubegFtpPassword = getConfigValue('ftp_password');
            ws.send('{"command":"bulk_scan", "dateTime":"' + currentDateTime + '", details: ['+ addinVariable +'{"key": "scan_id", "value": "'+recordId+'"},{"key": "ftp_server", "value": "'+ubegScanFtpLink+'"},{"key": "ftp_username", "value": "'+ubegFtpUsername+'"},{"key": "ftp_password", "value": "'+ubegFtpPassword+'"},{"key": "selected_image", "value": ""},{"key": "name", "value": "'+name+'"}, {"key": "scan_type", "value": "new"}, {"key": "prepared_file_count", "value": "'+selectedRow.preparedfilecount+'"}]}');
        };

        ws.onmessage = function (evt) {
            var received_msg = evt.data;
            var jsonData = JSON.parse(received_msg);
            
            PNotify.removeAll();

            if (jsonData.status == 'success' && 'details' in Object(jsonData)) {
                
                var filesObj = convertDataElementToArray(jsonData.details);
                
                $.ajax({
                    type: 'post',
                    url: 'mddoc/electronRegisterLegalBulkScan',
                    data: {recordId: recordId, filesObj: filesObj, nextWfmStatus: true, notFilesRender: true, selectedRow: selectedRow, type: $type, timeId: timeId, paramData: newParamData},
                    dataType: 'json',
                    async: false, 
                    success: function (data) {
                        if (data.status == 'success') {
                            dataViewReload(dataViewId);
                        } else {
                            new PNotify({
                                title: data.status,
                                text: data.message, 
                                type: data.status,
                                sticker: false
                            });
                        }
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
            Pace.stop();
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
            Pace.stop();Core.unblockUI();
        };

        ws.onclose = function () {
            console.log("Connection is closed...");
            Pace.stop();Core.unblockUI();
        };
        
    } else {
        
        PNotify.removeAll();
        new PNotify({
            title: 'Error',
            text: 'WebSocket NOT supported by your Browser!', 
            type: 'error',
            sticker: false
        });
        
        Pace.stop();Core.unblockUI();
    }
}

function erlReDirectScanInit(elem, processMetaDataId, dataViewId, selectedRow, paramData, $type, variable) {
    var addinVariable = (typeof variable !== 'undefined') ? variable : '';
    Core.blockUI({
        boxed: true, 
        message: 'Loading...'
    });
    
    if ("WebSocket" in window) {
        console.log("WebSocket is supported by your Browser!");

        var name = '';
        var recordId = getConfigValue('CIVIL_OFFLINE_SERVER') === '1' ? selectedRow.civilpackid : selectedRow.id;
        var recordId = (typeof selectedRow.recordid !== 'undefined' ? selectedRow.recordid : recordId);
        var newParamData = paramDataToObject(paramData);
        
        if (typeof $type !== 'undefined' && ($type == '2' || $type == '4')) {
            name = (selectedRow.stateregnumber + ' - '+ selectedRow.name).toUpperCase();
        } else {
            name = (selectedRow.companyregiternumber + ' - '+ selectedRow.companyname).toUpperCase();
        }
        
        if (selectedRow.hasOwnProperty('ftpscanid')) {
            recordId = selectedRow.ftpscanid;
        }
		
        var ws = new WebSocket("ws://localhost:58324/socket");

        ws.onopen = function () {
            var currentDateTime = erlGetCurrentDateTime();
            var ubegScanFtpLink = getConfigValue('ubegScanFtpLink');
            var ubegFtpUsername = getConfigValue('ftp_username');
            var ubegFtpPassword = getConfigValue('ftp_password');
            ws.send('{"command":"bulk_scan", "dateTime":"' + currentDateTime + '", details: ['+ addinVariable +'{"key": "scan_id", "value": "'+recordId+'"},{"key": "ftp_server", "value": "'+ubegScanFtpLink+'"},{"key": "ftp_username", "value": "'+ubegFtpUsername+'"},{"key": "ftp_password", "value": "'+ubegFtpPassword+'"},{"key": "selected_image", "value": ""},{"key": "name", "value": "'+name+'"}, {"key": "scan_type", "value": "again"}, {"key": "prepared_file_count", "value": "'+selectedRow.preparedfilecount+'"}]}');
        };

        ws.onmessage = function (evt) {
            var received_msg = evt.data;
            var jsonData = JSON.parse(received_msg);
            
            PNotify.removeAll();

            if (jsonData.status == 'success' && 'details' in Object(jsonData)) {
                
                var filesObj = convertDataElementToArray(jsonData.details);
                
                $.ajax({
                    type: 'post',
                    url: 'mddoc/electronRegisterLegalBulkReScan',
                    data: {recordId: recordId, filesObj: filesObj, nextWfmStatus: true, notFilesRender: true, selectedRow: selectedRow, type: $type, paramData: newParamData},
                    dataType: 'json',
                    async: false, 
                    success: function (data) {
                        if (data.status == 'success') {
                            dataViewReload(dataViewId);
                        } else {
                            new PNotify({
                                title: data.status,
                                text: data.message, 
                                type: data.status,
                                sticker: false
                            });
                        }
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
            
            Pace.stop();Core.unblockUI();
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
            Pace.stop();Core.unblockUI();
        };

        ws.onclose = function () {
            console.log("Connection is closed...");
            Pace.stop();Core.unblockUI();
        };
        
    } else {
        
        PNotify.removeAll();
        new PNotify({
            title: 'Error',
            text: 'WebSocket NOT supported by your Browser!', 
            type: 'error',
            sticker: false
        });
        
        Pace.stop();Core.unblockUI();
    }
}

function erlImgFullsize(elem) {
    var $this = $(elem), $parent = $this.parent(),
        imgPath = $parent.find('.erl-image-preview').find('img').attr('src');
        
    if ($this.children().hasClass('fa-search-plus')) {
        $this.html('<i class="fa fa-search-minus"></i> Жижгээр харах');
        $parent.find('.erl-image-preview').empty().append('<div style="overflow: auto; width: 1200px;"><img style="width: 1200px;" src="'+imgPath+'"></div>');
        
    } else {
        var imgHeight = $(window).height();
        $this.html('<i class="fa fa-search-plus"></i> Томоор харах');
        $parent.find('.erl-image-preview').empty().append('<img style="height: '+(imgHeight - 170)+'px" src="'+imgPath+'">');    
    }
}

function changeSelectorErl($this) {
    
    var $row = $this.closest("tr");
    
    if ($row.attr('data-content-type-append') === "0") {
        
        var boookTypeHtmlClone = boookTypeHtml;
        
        boookTypeHtmlClone = boookTypeHtmlClone.replace('value="' + comboOldVal_bookTypeId + '"', 'value="' + comboOldVal_bookTypeId + '" selected="selected"');
 
        $this.empty();
        $this.append(boookTypeHtmlClone);      
        
        $row.attr('data-content-type-append', "1");
        $this.select2('open');
    }
}

function changeSelectorErl_contentType($this) {
    
    var $row = $this.closest('tr');

    if ($row.attr('data-book-type-append') === '0') {
        
        var contentTypeHtmlClone = contentTypeHtml;

        contentTypeHtmlClone = contentTypeHtmlClone.replace('value="' + comboOldVal_contentTypeId + '">', 'value="' + comboOldVal_contentTypeId + '" selected="selected">');
        
        $this.empty();
        $this.append(contentTypeHtmlClone);
        
        $row.attr('data-book-type-append', '1');
        $this.select2('open');        
    }
}

function erlGetCurrentDateTime() {
    var today = new Date();
    var dd = today.getDate();
    var MM = today.getMonth() + 1; //January is 0!
    var yyyy = today.getFullYear();
    var HH = today.getHours();
    var mm = today.getMinutes();
    var ss = today.getSeconds();

    if (dd < 10) { dd = '0' + dd }
    if (MM < 10) { MM = '0' + MM }
    if (HH < 10) { HH = '0' + HH }
    if (mm < 10) { mm = '0' + mm }
    if (ss < 10) { ss = '0' + ss }

    var datetime = yyyy + "/" + MM + "/" + dd + " " + HH + ":" + mm + ":" + ss;
    return datetime;
}

function erlGetCurrentDateTimeId() {
    var today = new Date();
    var dd = today.getDate();
    var MM = today.getMonth() + 1; //January is 0!
    var yyyy = today.getFullYear();
    var HH = today.getHours();
    var mm = today.getMinutes();
    var ss = today.getSeconds();

    if (dd < 10) { dd = '0' + dd }
    if (MM < 10) { MM = '0' + MM }
    if (HH < 10) { HH = '0' + HH }
    if (mm < 10) { mm = '0' + mm }
    if (ss < 10) { ss = '0' + ss }

    var datetime = yyyy + MM + dd + HH + mm + ss;
    return datetime;
}
