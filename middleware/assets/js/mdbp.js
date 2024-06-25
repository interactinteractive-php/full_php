var lookupAutoCompleteRequest = null, bpIsRowReloadParams = [], $textAComplete = $();

function lookupAutoComplete(elem, type) {
    var $this = elem;
    var _lookupId = $this.attr("data-lookupid"), _processId = $this.attr("data-processid");
    var params = '';
    var isHoverSelect = false;
    var isCodeWithPhoto = $this.hasClass('pf-codewithphoto-input');
    var autoCompleteUrl = 'mdwebservice/lookupAutoComplete';
    
    if (!isCodeWithPhoto) {
        var $parent = $this.closest("div.meta-autocomplete-wrap");
        if ($parent.hasClass('mv-popup-control')) {
            autoCompleteUrl = 'mdform/lookupAutoComplete';
        }
    } else {
        var $parent = $this.closest('.pf-codewithphoto-parent');
    }
    
    var $bpElem = $parent.find("input[type='hidden']");
    var _paramRealPath = $bpElem.attr("data-path");
    
    if (typeof $bpElem.attr('data-criteria-param') !== 'undefined' && $bpElem.attr('data-criteria-param') != '') {
        
        var $mainSelector = $this.closest('form');
        var paramsPathArr = $bpElem.attr("data-criteria-param").split("|");
        
        for (var i = 0; i < paramsPathArr.length; i++) {
            var fieldPathArr = paramsPathArr[i].split("@");
            var fieldPath = fieldPathArr[0];
            var inputPath = fieldPathArr[1];
            var fieldValue = '', isCriteria = false;
            
            if ($("[data-path='"+fieldPath+"']", $mainSelector).length) {
                fieldValue = getBpRowParamNum($mainSelector, elem, fieldPath);
                isCriteria = true;
            } else if ($this.closest('.popup-parent-tag').length) {
                fieldValue = getBpRowParamNum($this.closest('.popup-parent-tag'), elem, fieldPath);
                isCriteria = true;
            } else {
                if (inputPath != fieldPath) {
                    fieldValue = fieldPath;
                    isCriteria = true;
                } 
            }
            
            if (isCriteria) {
                params += inputPath + "=" + fieldValue + "&";
            }
        }
    }
    
    if (typeof $bpElem.attr('data-in-param') !== 'undefined' && typeof $bpElem.attr('data-in-lookup-param') !== 'undefined' 
        && $bpElem.attr('data-in-param') != '' && $bpElem.attr('data-in-lookup-param') != '') {
    
        if ($parent.closest('.popup-parent-tag').length) {
            var $mainSelector = $parent.closest('.popup-parent-tag');
        } else {
            var $mainSelector = $this.closest('form');
            if ($mainSelector.attr('id') == 'default-criteria-form') {
                $mainSelector = $mainSelector.closest('.main-dataview-container').find('form');
            }
        }
        
        var paramsPathArr = $bpElem.attr('data-in-param').split('|');
        var lookupPathArr = $bpElem.attr('data-in-lookup-param').split('|');
        
        for (var i = 0; i < paramsPathArr.length; i++) {
            var fieldPath = paramsPathArr[i];
            var inputPath = lookupPathArr[i];
            var fieldValue = '', isCriteria = false;
            
            if ($mainSelector.find("[data-path='"+fieldPath+"']").length) {
                fieldValue = getBpRowParamNum($mainSelector, elem, fieldPath);
                isCriteria = true;
            } else {
                if (inputPath != fieldPath) {
                    fieldValue = fieldPath;
                    isCriteria = true;
                }
            }
            
            if (isCriteria) {
                params += inputPath + '=' + fieldValue + '&';
            }
        }
    }
    
    if (typeof $bpElem.attr('data-criteria') !== 'undefined' && $bpElem.attr('data-criteria') != '') {
        params += $bpElem.attr('data-criteria');
    }

    $this.autocomplete({
        minLength: 1,
        maxShowItems: 30,
        delay: 500,
        highlightClass: "lookup-ac-highlight", 
        appendTo: "body",
        position: {my : "left top", at: "left bottom", collision: "flip flip"}, 
        autoSelect: false,
        source: function(request, response) {
            
            if (lookupAutoCompleteRequest != null) {
                lookupAutoCompleteRequest.abort();
                lookupAutoCompleteRequest = null;
            }
        
            lookupAutoCompleteRequest = $.ajax({
                type: 'post',
                url: autoCompleteUrl,
                dataType: 'json',
                data: {
                    lookupId: _lookupId, 
                    processId: _processId, 
                    paramRealPath: _paramRealPath, 
                    q: request.term, 
                    type: type, 
                    criteriaParams: encodeURIComponent(params) 
                },
                success: function(data) {
                    
                    var isBreadCrumbName = false, valueKey = (type == 'code') ? 1 : 2;
                    
                    if (data.length) {
                        var firstRow = data[0], countSplit = firstRow.split('|');
                        isBreadCrumbName = (countSplit.length == 4) ? true : false;
                    }
                    
                    if (isBreadCrumbName) {
                        
                        response($.map(data, function(item) {
                            var code = item.split('|');
                            return {
                                value: code[valueKey], 
                                label: code[1],
                                name: code[2], 
                                id: code[0], 
                                breadCrumbName: code[3]
                            };
                        }));
                        
                    } else {
                        response($.map(data, function(item) {
                            var code = item.split('|');
                            return {
                                value: code[valueKey], 
                                label: code[1],
                                name: code[2], 
                                id: code[0]
                            };
                        }));
                    }
                }
            });
        },
        focus: function(event, ui) {
            if (typeof event.keyCode === 'undefined' || event.keyCode == 0) {
                isHoverSelect = false;
            } else {
                if (event.keyCode == 38 || event.keyCode == 40) {
                    isHoverSelect = true;
                }
            }
            return false;
        },
        open: function() {
            var $this = $(this);
            var $onTopElem = $this.closest('.ui-front');
            if ($onTopElem.length > 0) {
                var $widget = $this.autocomplete('widget');
                $widget.css('z-index', $onTopElem.css('z-index') + 1);
            }
            return false;
        },
        close: function() {
            $(this).autocomplete('option', 'appendTo', 'body'); 
        }, 
        select: function(event, ui) {
            var origEvent = event;	
            
            if (isHoverSelect || event.originalEvent.originalEvent.type == 'click') {
                if (type === 'code') {
                    if (isCodeWithPhoto) {
                        $this.val(ui.item.id).attr('data-ac-id', ui.item.id);
                    } else {
                        $parent.find("input[id*='_displayField']").val(ui.item.label).attr('data-ac-id', ui.item.id);
                    }
                } else {
                    $parent.find("input[id*='_nameField']").val(ui.item.name).attr('data-ac-id', ui.item.id);
                }
            } else {
                if (type === 'code') {
                    
                    if (isCodeWithPhoto) {
                        
                        if (ui.item.label === $this.val()) {
                            $this.val(ui.item.id).attr('data-ac-id', ui.item.id);
                        } else {
                            $this.val(ui.item.id).attr('data-ac-id', ui.item.id);
                            event.preventDefault();
                        }
                        
                    } else {
                        if (ui.item.label === $this.val()) {
                            $parent.find("input[id*='_displayField']").val(ui.item.label);
                            $parent.find("input[id*='_nameField']").val(ui.item.name);
                        } else {
                            $parent.find("input[id*='_displayField']").val($this.val());
                            event.preventDefault();
                        }
                    }
                    
                } else {
                    if (ui.item.name === $this.val()) {
                        $parent.find("input[id*='_displayField']").val(ui.item.label);
                        $parent.find("input[id*='_nameField']").val(ui.item.name);
                    } else {
                        $parent.find("input[id*='_nameField']").val($this.val());
                        event.preventDefault();
                    }
                }
            }

            while (origEvent.originalEvent !== undefined) {
                origEvent = origEvent.originalEvent;
            }

            if (origEvent.type === 'click') {
                var e = jQuery.Event("keydown");
                e.keyCode = e.which = 13;
                $this.trigger(e);
            }
        }
    }).autocomplete("instance")._renderItem = function(ul, item) {
        ul.addClass('lookup-ac-render');
        
        var $qTerm = this.term;
        
        if ($qTerm.indexOf('*') !== -1) {
            var $leftSubstr = $qTerm.substring(0, 1);
            var $rightSubstr = $qTerm.substring(-1);
            
            if ($leftSubstr == '*' && $rightSubstr == '*') {
                $qTerm = $qTerm.substring(0, -1).substring(1);
            } else if ($leftSubstr == '*') {
                $qTerm = $qTerm.substring(1);
            } else if ($rightSubstr == '*') {
                $qTerm = $qTerm.substring(0, -1);
            }
        }
        
        var re = new RegExp("(" + $qTerm + ")", "gi"),
            cls = this.options.highlightClass,
            template = "<span class='" + cls + "'>$1</span>", 
            breadCrumbName = '';
        
        if (item.hasOwnProperty('breadCrumbName') && item.breadCrumbName) {
            breadCrumbName = '<div class="clearfix text-gray overflow-hidden text-ellipsis text-truncate mt-1" title="'+item.breadCrumbName+'"><i class="fas fa-caret-right mr-0"></i> '+item.breadCrumbName+'</div>';
        }
        
        if (type === 'code') {
            
            var label = item.label.replace(re, template);
            return $('<li>').append('<div class="lookup-ac-render-code">'+label+'</div><div class="lookup-ac-render-name d-block">'+item.name+breadCrumbName+'</div>').appendTo(ul);
            
        } else {
            
            var name = item.name.replace(re, template);
            return $('<li>').append('<div class="lookup-ac-render-code">'+item.label+'</div><div class="lookup-ac-render-name d-block">'+name+breadCrumbName+'</div>').appendTo(ul);
        }
    };
}
function lookupTextAutoComplete(elem, type) {
    var $this = elem;
    var _lookupId = $this.attr('data-lookupid'), _processId = $this.attr('data-processid');
    var _paramRealPath = $this.attr('data-path');
    var _displayField = $this.attr('data-displayfield');
    var params = '';
    var isHoverSelect = false;
    $textAComplete = $this;
    
    if (typeof $this.attr('data-criteria-param') !== 'undefined' && $this.attr('data-criteria-param') != '') {
        
        var $mainSelector = $this.closest('form');
        var paramsPathArr = $this.attr("data-criteria-param").split("|");
        
        for (var i = 0; i < paramsPathArr.length; i++) {
            var fieldPathArr = paramsPathArr[i].split("@");
            var fieldPath = fieldPathArr[0];
            var inputPath = fieldPathArr[1];
            var fieldValue = '', isCriteria = false;
            
            if ($("[data-path='"+fieldPath+"']", $mainSelector).length) {
                fieldValue = getBpRowParamNum($mainSelector, elem, fieldPath);
                isCriteria = true;
            } else if ($this.closest('.popup-parent-tag').length) {
                fieldValue = getBpRowParamNum($this.closest('.popup-parent-tag'), elem, fieldPath);
                isCriteria = true;
            } else {
                if (inputPath != fieldPath) {
                    fieldValue = fieldPath;
                    isCriteria = true;
                } 
            }
            
            if (isCriteria) {
                params += inputPath + "=" + fieldValue + "&";
            }
        }
    }
    
    if (typeof $this.attr('data-criteria') !== 'undefined' && $this.attr('data-criteria') != '') {
        params += $this.attr('data-criteria');
    }

    $this.autocomplete({
        minLength: 1,
        maxShowItems: 30,
        delay: 500,
        highlightClass: "lookup-ac-highlight", 
        appendTo: "body",
        position: {my : "left top", at: "left bottom", collision: "flip flip"}, 
        autoSelect: false,
        source: function(request, response) {
            
            if (lookupAutoCompleteRequest != null) {
                lookupAutoCompleteRequest.abort();
                lookupAutoCompleteRequest = null;
            }
        
            lookupAutoCompleteRequest = $.ajax({
                type: 'post',
                url: 'mdwebservice/lookupAutoComplete',
                dataType: 'json',
                data: {
                    lookupId: _lookupId, 
                    processId: _processId, 
                    paramRealPath: _paramRealPath,
                    isAutoCompleteText: 1, 
                    displayField: _displayField, 
                    q: request.term, 
                    type: type, 
                    criteriaParams: encodeURIComponent(params) 
                },
                success: function(data) {
                    response($.map(data, function(item) {
                        var code = item.codeName.split('|');
                        var rowData = item.row;
                        var rightGreyField = rowData.hasOwnProperty('rightgreyfield') ? rowData.rightgreyfield : '';
                        var breadCrumbName = (rowData.hasOwnProperty('breadcrumbname') && (rowData.breadcrumbname).trim() != '') ? rowData.breadcrumbname : '';
                        
                        return {
                            id: code[0], 
                            value: code[2],  
                            rowData: JSON.stringify(rowData), 
                            rightGreyField: rightGreyField, 
                            breadCrumbName: breadCrumbName 
                        };
                    }));
                }
            });
        },
        focus: function(event, ui) {
            if (typeof event.keyCode === 'undefined' || event.keyCode == 0) {
                isHoverSelect = false;
            } else {
                if (event.keyCode == 38 || event.keyCode == 40) {
                    isHoverSelect = true;
                }
            }
            return false;
        },
        open: function() {
            var $this = $(this);
            var $onTopElem = $this.closest('.ui-front');
            if ($onTopElem.length > 0) {
                var $widget = $this.autocomplete('widget');
                $widget.css('z-index', $onTopElem.css('z-index') + 1);
            } 
            return false;
        },
        close: function() {
            $(this).autocomplete('option', 'appendTo', 'body'); 
        }, 
        select: function(event, ui) {
            
            var $clickElement = $(event.originalEvent.originalEvent.target);
            
            if ($clickElement.hasClass('ac-remove-click')) {
                return false;
            }
            
            if (isHoverSelect || event.originalEvent.originalEvent.type == 'click') {
                
                $this.val(ui.item.value);
                
            } else {
                
                if (ui.item.value == $this.val()) {
                    $this.val(ui.item.value);
                } else {
                    $this.val($this.val());
                }
            }
            
            $this.attr('data-row-data', ui.item.rowData).trigger('change');
            
            event.preventDefault();
        }
    }).autocomplete("instance")._renderItem = function(ul, item) {
        ul.addClass('lookup-ac-render');
        
        var $qTerm = this.term;
        
        if ($qTerm.indexOf('*') !== -1) {
            var $leftSubstr = $qTerm.substring(0, 1);
            var $rightSubstr = $qTerm.substring(-1);
            
            if ($leftSubstr == '*' && $rightSubstr == '*') {
                $qTerm = $qTerm.substring(0, -1).substring(1);
            } else if ($leftSubstr == '*') {
                $qTerm = $qTerm.substring(1);
            } else if ($rightSubstr == '*') {
                $qTerm = $qTerm.substring(0, -1);
            }
        }
        
        var re = new RegExp("(" + $qTerm + ")", "gi"), 
            cls = this.options.highlightClass, 
            template = "<span class='" + cls + "'>$1</span>", 
            name = item.value.replace(re, template), 
            rightGreyField = '', breadCrumbName = '';
        
        if (item.rightGreyField) {
            rightGreyField = '<div class="mr-1 text-gray overflow-hidden text-ellipsis text-truncate" title="'+item.rightGreyField+'"><i class="icon-price-tag2 mr-0 font-size-12"></i> '+item.rightGreyField+'</div>';
        }
        
        if (item.breadCrumbName) {
            breadCrumbName = '<div class="clearfix text-gray overflow-hidden text-ellipsis text-truncate mt-1" title="'+item.breadCrumbName+'"><i class="fas fa-caret-right mr-0"></i> '+item.breadCrumbName+'</div>';
        }

        return $('<li>').append('<div class="lookup-ac-render-text d-flex flex-row align-items-center" data-rid="'+item.id+'">'+
            '<div class="mr-1">' + name + breadCrumbName + '</div>'+
            rightGreyField+ 
            '<div class="text-gray ml-auto">'+
                '<button type="button" class="btn btn-sm ac-item-remove ac-remove-click p-0 line-height-normal">'+
                    '<i class="icon-cross3 ac-remove-click mr-0"></i>'+
                '</button>'+
            '</div>'+
        '</div>').appendTo(ul);
    };
}
function lookupTextMention(term, type) {

    var $this = term;
    var _lookupId = $this.attr('data-lookupid'), _processId = $this.attr('data-processid');
    var _paramRealPath = $this.attr('data-path');
    var _displayField = $this.attr('data-displayfield');
    var params = '';
    $textAComplete = $this;

    $this.autocomplete({
        minLength: 1,
        maxShowItems: 30,
        delay: 500,
        highlightClass: "lookup-ac-highlight", 
        appendTo: "body",
        position: {my : "left top", at: "left bottom", collision: "flip flip"}, 
        autoSelect: false,
        source: function(request, response) {
            
            if (lookupAutoCompleteRequest != null) {
                lookupAutoCompleteRequest.abort();
                lookupAutoCompleteRequest = null;
            }

            var searchText = request.term;
            var split = searchText.split(/@\s*/).pop(); 

            if (request.term.indexOf("@") >= 0) {
                
                lookupAutoCompleteRequest = $.ajax({

                    type: 'post',
                    url: 'mdwebservice/lookupAutoComplete',
                    dataType: 'json',
                    data: {
                        lookupId: _lookupId, 
                        processId: _processId, 
                        paramRealPath: _paramRealPath,
                        isAutoCompleteText: 1, 
                        displayField: _displayField, 
                        q: split, 
                        type: type, 
                        criteriaParams: encodeURIComponent(params) 
                    },
                    success: function(data) {
    
                        response($.map(data, function(item) {
                            var code = item.codeName.split('|');
                            var rowData = item.row;
                            var rightGreyField = rowData.hasOwnProperty('rightgreyfield') ? rowData.rightgreyfield : '';
                            
                            return {
                                id: code[0], 
                                value: code[2],  
                                rowData: JSON.stringify(rowData), 
                                rightGreyField: rightGreyField 
                            };
                        }));
                    }
                });
            }
        },
        focus: function(event, ui) {
            return false;
        },
        open: function() {
            var $this = $(this);
            var $onTopElem = $this.closest('.ui-front');
            if ($onTopElem.length > 0) {
                var $widget = $this.autocomplete('widget');
                $widget.css('z-index', $onTopElem.css('z-index') + 1);
            } 
            return false;
        },
        close: function() {
            $(this).autocomplete('option', 'appendTo', 'body'); 
        }, 
        select: function(event, ui) {
            var terms = this.value.split('');
            // remove the current input
            terms.pop();
            // add the selected item
            terms.push("@" + ui.item.value);
            // add placeholder to get the comma-and-space at the end
            terms.push(" ");
            this.value = terms.join("");
            return false;
        },
        multiple: true
    }).autocomplete("instance")._renderItem = function(ul, item) {
        return $("<li>")
            .data("item.autocomplete", item)
            .append("<strong>" + item.label + "</strong>")
            .appendTo(ul);
    }; 
}

$(function() {
    
    $(document).bind('keydown', 'Shift+5', function() {
        location.reload(true);
    });

    $(document.body).on('click', 'input[type="checkbox"]', function(e) {
        var $this = $(this);
        if (typeof $this.attr('readonly') !== 'undefined') {
            $this.parent().removeClass('checked');
            return false;
        }
    });
    
    $(document.body).on('click', '.ac-item-remove', function(e) {
        
        var $this = $(this);
        var lookupId = $textAComplete.attr('data-lookupid');
        var $parent = $this.closest('.lookup-ac-render-text');
        var rowId = $parent.attr('data-rid');
        var $dialogName = 'dialog-ac-confirm';
        
        if (!$("#" + $dialogName).length) {
            $('<div id="' + $dialogName + '"></div>').appendTo('body');
        }
        var $dialog = $('#' + $dialogName);
        
        $dialog.empty().append(plang.get('msg_delete_confirm'));  

        $dialog.dialog({
            resizable: true,
            bgiframe: true,
            autoOpen: false,
            title: 'Confirm',
            width: 350,
            height: 'auto',
            modal: true,
            create: function() {
                $dialog.parent('.ui-dialog').css('zIndex', 1000001).next('.ui-widget-overlay').css('zIndex', 1000000);
            },
            open: function() {
                if (!$("ul.ui-autocomplete").is(":visible")) {
                    $("ul.ui-autocomplete").show();
                }
            }, 
            buttons: [
                {text: plang.get('yes_btn'), class: 'btn green-meadow btn-sm', click: function () {

                    $.ajax({
                        type: 'post',
                        url: 'mddatamodel/saveRemovedLookupItem',
                        data: {lookupId: lookupId, rowId: rowId}, 
                        dataType: 'json',
                        success: function(dataSub) {

                            PNotify.removeAll();

                            if (dataSub.status == 'success') {

                                $parent.closest('li').remove();
                                $dialog.dialog('close');

                            } else {
                                new PNotify({
                                    title: dataSub.status,
                                    text: dataSub.message, 
                                    type: dataSub.status,
                                    sticker: false
                                });
                            }
                        }
                    });
                }}, 
                {text: plang.get('no_btn'), class: 'btn blue-madison btn-sm', click: function () {
                    $dialog.dialog('close');
                }}
            ]
        });
        $dialog.dialog('open');
    });
    
    $(document).bind('keydown', 'Ctrl+p', function(e){
        if ($('body').find('.ctrl-print-container').length > 0 && $('body').find('.ctrl-print-container').is(':visible')) {
            var $printElement = $('body').find('.ctrl-print-container:visible:last');
            $printElement.find('.ctrl-print-btn').click();
        }
        e.preventDefault();
        return false;
    });
    $(document.body).on('keydown', 'input, select, textarea, a, button, span', 'Ctrl+p', function(e){
        if ($('body').find('.ctrl-print-container').length > 0 && $('body').find('.ctrl-print-container').is(':visible')) {
            $(this).trigger('change');
            var $printElement = $('body').find('.ctrl-print-container:visible:last');
            $printElement.find('.ctrl-print-btn').click();
        }
        e.preventDefault();
        return false;
    });
    $(document).bind('keydown', 'Ctrl+s', function(e){
        if ($('body').find('.mce-bp-btn-subsave').length > 0 && $('body').find('.mce-bp-btn-subsave').is(':visible')) {
            var $buttonElement = $('body').find('.mce-bp-btn-subsave:visible:last');
            if (!$buttonElement.is(':disabled')) {
                $buttonElement.click();
                e.preventDefault();
                return false;
            }
        }
        if ($('body').find('button.bp-btn-subsave').length > 0 && $('body').find('button.bp-btn-subsave').is(':visible')) {
            var $buttonElement = $('body').find('button.bp-btn-subsave:visible:last');
            if (!$buttonElement.is(':disabled')) {
                $buttonElement.click();
                e.preventDefault();
                return false;
            }
        }
        if ($('body').find('button.bp-btn-save').length > 0 && $('body').find('button.bp-btn-save').is(':visible')) {
            var $buttonElement = $('body').find('button.bp-btn-save:visible:last');
            if (!$buttonElement.is(':disabled')) {
                $buttonElement.click();
            }
        }
        e.preventDefault();
        return false;
    });
    $(document.body).on('keydown', 'input, select, textarea, a, button', 'Ctrl+s', function(e){
        if ($('body').find('.mce-bp-btn-subsave').length > 0 && $('body').find('.mce-bp-btn-subsave').is(':visible')) {
            var $buttonElement = $('body').find('.mce-bp-btn-subsave:visible:last');
            if (!$buttonElement.is(':disabled')) {
                $buttonElement.click();
                e.preventDefault();
                return false;
            }
        }
        if ($('body').find('button.bp-btn-subsave').length > 0 && $('body').find('button.bp-btn-subsave').is(':visible')) {
            $(this).trigger('change');
            var $buttonElement = $('body').find('button.bp-btn-subsave:visible:last');
            if (!$buttonElement.is(':disabled')) {
                setTimeout(function(){
                    $buttonElement.click();
                }, 100);
                e.preventDefault();
                return false;
            }
        }
        if ($('body').find('button.bp-btn-save').length > 0 && $('body').find('button.bp-btn-save').is(':visible')) {
            $(this).trigger('change');
            var $buttonElement = $('body').find('button.bp-btn-save:visible:last');
            if (!$buttonElement.is(':disabled')) {
                setTimeout(function(){
                    $buttonElement.click();
                }, 100);
            }
        }
        e.preventDefault();
        return false;
    });
    $(document).bind('keydown', 'Ctrl+l', function(e){
        if ($('body').find('button.bp-btn-save').length > 0 && $('body').find('button.bp-btn-save').is(':visible')) {
            var $buttonElement = $('body').find('button.bp-btn-save:visible:last');
            if (!$buttonElement.is(':disabled')) {
                $buttonElement.click();
            }
        }
        e.preventDefault();
        return false;
    });
    $(document.body).on('keydown', 'input, select, textarea, a, button', 'Ctrl+l', function(e){
        if ($('body').find('button.bp-btn-save').length > 0 && $('body').find('button.bp-btn-save').is(':visible')) {
            $(this).trigger('change');
            var $buttonElement = $('body').find('button.bp-btn-save:visible:last');
            if (!$buttonElement.is(':disabled')) {
                $buttonElement.click();
            }
        }
        e.preventDefault();
        return false;
    });
    $(document).bind('keydown', 'Ctrl+Shift+s', function(e){
        if ($('body').find('button.bp-btn-saveadd').length > 0 && $('body').find('button.bp-btn-saveadd').is(':visible')) {
            var $buttonElement = $('body').find('button.bp-btn-saveadd:visible:last');
            if (!$buttonElement.is(':disabled')) {
                $buttonElement.click();
            }
        }
        e.preventDefault();
        return false;
    });
    $(document.body).on('keydown', 'input, select, textarea, a, button', 'Ctrl+Shift+s', function(e){
        if ($('body').find('button.bp-btn-saveadd').length > 0 && $('body').find('button.bp-btn-saveadd').is(':visible')) {
            $(this).trigger('change');
            var $buttonElement = $('body').find('button.bp-btn-saveadd:visible:last');
            if (!$buttonElement.is(':disabled')) {
                $buttonElement.click();
            }
        }
        e.preventDefault();
        return false;
    });
    $(document).bind('keydown', 'Ctrl+Shift+p', function(e){
        if ($('body').find('button.bp-btn-saveprint').length > 0 && $('body').find('button.bp-btn-saveprint').is(':visible')) {
            var $buttonElement = $('body').find('button.bp-btn-saveprint:visible:last');
            if (!$buttonElement.is(':disabled')) {
                $buttonElement.click();
            }
        }
        e.preventDefault();
        return false;
    });
    $(document.body).on('keydown', 'input, select, textarea, a, button', 'Ctrl+Shift+p', function(e){
        if ($('body').find('button.bp-btn-saveprint').length > 0 && $('body').find('button.bp-btn-saveprint').is(':visible')) {
            $(this).trigger('change');
            var $buttonElement = $('body').find('button.bp-btn-saveprint:visible:last');
            if (!$buttonElement.is(':disabled')) {
                $buttonElement.click();
            }
        }
        e.preventDefault();
        return false;
    });
    $(document).bind('keydown', 'F3', function(e){
        if ($('body').find('.bp-add-ac-row button').length > 0 && $('body').find('.bp-add-ac-row button').is(':visible')) {
            var $buttonElement = $('body').find('.bp-add-ac-row button:visible:last');
            $buttonElement.click();
        } else if ($('body').find('#glquickCode').length > 0 && $('body').find('#glquickCode').is(':visible')) {
            var $buttonElement = $('body').find('#glquickCode:visible:last');
            $buttonElement.closest('.quick-item').find('button').click();
        }
        e.preventDefault();
        return false;
    });
    $(document.body).on('keydown', 'input, select, textarea, a, button', 'F3', function(e){
        if ($('body').find('.bp-add-ac-row button').length > 0 && $('body').find('.bp-add-ac-row button').is(':visible')) {
            $(this).trigger('change');
            var $buttonElement = $('body').find('.bp-add-ac-row button:visible:last');
            $buttonElement.click();
        } else if ($('body').find('#glquickCode').length > 0 && $('body').find('#glquickCode').is(':visible')) {
            $(this).trigger('change');
            var $buttonElement = $('body').find('#glquickCode:visible:last');
            $buttonElement.closest('.quick-item').find('button').click();
        }
        e.preventDefault();
        return false;
    });
    $(document).bind('keydown', 'F5', function(e){
        if ($('body').find('a[href*="#dv_criteria_tab_"]').length > 0 && $('body').find('a[href*="#dv_criteria_tab_"]').is(':visible')) {
            var $buttonElement = $('body').find('a[href*="#dv_criteria_tab_"]:visible:last');
            $buttonElement.click();
            $('body').find('form:visible:first').find('input[type="text"]:visible:first').focus();
        } else if ($('body').find('.card-title > .caption.card-collapse').length > 0 && $('body').find('.card-title > .caption.card-collapse').is(':visible')) {
            var $buttonElement = $('body').find('.card-title > .caption.card-collapse:visible:last');
            $buttonElement.click();
            $('body').find('form:visible:first').find('input[type="text"]:visible:first').focus();
        }
        e.preventDefault();
        return false;
    });
    $(document.body).on('keydown', 'input, select, textarea, a, button', 'F5', function(e){
        if ($('body').find('a[href*="#dv_criteria_tab_"]').length > 0 && $('body').find('a[href*="#dv_criteria_tab_"]').is(':visible')) {
            var $buttonElement = $('body').find('a[href*="#dv_criteria_tab_"]:visible:last');
            $buttonElement.click();
            $('body').find('form:visible:first').find('input[type="text"]:visible:first').focus();
        } else if ($('body').find('.card-title > .caption.card-collapse').length > 0 && $('body').find('.card-title > .caption.card-collapse').is(':visible')) {
            var $buttonElement = $('body').find('.card-title > .caption.card-collapse:visible:last');
            $buttonElement.click();
            $('body').find('form:visible:first').find('input[type="text"]:visible:first').focus();
        }
        e.preventDefault();
        return false;
    });
    $(document).bind('keydown', 'Shift+F4', function(e){
        if ($('body').find('span.l-btn-icon.pagination-load').length > 0 && $('body').find('span.l-btn-icon.pagination-load').is(':visible')) {
            var $buttonElement = $('body').find('span.l-btn-icon.pagination-load:visible:last');
            $buttonElement.click();
        } 
        e.preventDefault();
        return false;
    });
    $(document.body).on('keydown', 'input, select, textarea, a, button', 'Shift+F4', function(e){
        if ($('body').find('span.l-btn-icon.pagination-load').length > 0 && $('body').find('span.l-btn-icon.pagination-load').is(':visible')) {
            var $buttonElement = $('body').find('span.l-btn-icon.pagination-load:visible:last');
            $buttonElement.click();
        } 
        e.preventDefault();
        return false;
    });
    $(document).bind('keydown', 'Shift+F5', function(e){
        if ($('body').find('button.dataview-default-filter-btn').length > 0 && $('body').find('button.dataview-default-filter-btn').is(':visible')) {
            var $buttonElement = $('body').find('button.dataview-default-filter-btn:visible:last');
            $buttonElement.click();
        } else if ($('body').find('button.dataview-statement-filter-btn').length > 0 && $('body').find('button.dataview-statement-filter-btn').is(':visible')) {
            var $buttonElement = $('body').find('button.dataview-statement-filter-btn:visible:last');
            $buttonElement.click();
        }
        e.preventDefault();
        return false;
    });
    $(document).bind('keydown', 'Shift+c', function(e){
        if ($('body').find('button.dataview-default-filter-reset-btn').length > 0 && $('body').find('button.dataview-default-filter-reset-btn').is(':visible')) {
            var $buttonElement = $('body').find('button.dataview-default-filter-reset-btn:visible:last');
            $buttonElement.click();
        }
        e.preventDefault();
        return false;
    });
    $(document.body).on('keydown', 'input, select, textarea, a, button', 'Shift+F5', function(e){
        if ($('body').find('button.dataview-default-filter-btn').length > 0 && $('body').find('button.dataview-default-filter-btn').is(':visible')) {
            var $buttonElement = $('body').find('button.dataview-default-filter-btn:visible:last');
            $buttonElement.click();
        } else if ($('body').find('button.dataview-statement-filter-btn').length > 0 && $('body').find('button.dataview-statement-filter-btn').is(':visible')) {
            var $buttonElement = $('body').find('button.dataview-statement-filter-btn:visible:last');
            $buttonElement.click();
        }
        e.preventDefault();
        return false;
    });
    $(document).bind('keydown', 'Shift+F2', function(e){
        if ($('body').find('button.datagrid-choose-btn').length > 0 && $('body').find('button.datagrid-choose-btn').is(':visible')) {
            var $buttonElement = $('body').find('button.datagrid-choose-btn:visible:last');
            $buttonElement.click();
        }
        e.preventDefault();
        return false;
    });
    $(document.body).on('keydown', 'input, select, textarea, a, button', 'Shift+F2', function(e){
        if ($('body').find('button.datagrid-choose-btn').length > 0 && $('body').find('button.datagrid-choose-btn').is(':visible')) {
            var $buttonElement = $('body').find('button.datagrid-choose-btn:visible:last');
            $buttonElement.click();
        }
        e.preventDefault();
        return false;
    });
    $(document).bind('keydown', 'Ctrl++', function(e){
        if ($('body').find('button.bp-add-one-row').length > 0 && $('body').find('button.bp-add-one-row').is(':visible')) {
            var $buttonElement = $('body').find('button.bp-add-one-row:visible:last');
            $buttonElement.click();
        }
        e.preventDefault();
        return false;
    });
    $(document.body).on('keydown', 'input, select, textarea, a, button', 'Ctrl++', function(e){
        if ($('body').find('button.bp-add-one-row').length > 0 && $('body').find('button.bp-add-one-row').is(':visible')) {
            $(this).trigger('change');
            var $buttonElement = $('body').find('button.bp-add-one-row:visible:last');
            $buttonElement.click();
        }
        e.preventDefault();
        return false;
    });
    $(document).bind('keydown', 'Alt+n', function(e){
        if (($('body').find('div.dv-process-buttons:visible:last > .btn-group-devided > a[data-actiontype="insert"]:eq(0)').length > 0 && $('body').find('div.dv-process-buttons:visible:last > .btn-group-devided > a[data-actiontype="insert"]:eq(0)').is(':visible')) 
            || ($('body').find('div.dv-process-buttons:visible:last > .btn-group-devided > .dv-buttons-batch').length > 0 && $('body').find('div.dv-process-buttons:visible:last > .btn-group-devided > .dv-buttons-batch').is(':visible'))) {
            
            var $buttonElement = $('body').find('div.dv-process-buttons:visible:last a[data-actiontype="insert"]:visible');
            var $batchElement = $('body').find('div.dv-process-buttons:visible:last > .btn-group-devided > .dv-buttons-batch:visible');
            var $buttonElements = $batchElement.find('ul.dropdown-menu > li > a[data-actiontype="insert"]');
            
            if ($buttonElement.length == 1 && $buttonElements.length == 0) {
                $buttonElement.click();
            } else if ($buttonElement.length == 0 && $buttonElements.length == 1) {
                $buttonElements.click();
            } else if (($buttonElement.length && $buttonElements.length) 
                || ($buttonElement.length == 0 && $buttonElements.length > 1) 
                || ($buttonElement.length > 1 && $buttonElements.length == 0)) {
                createProcessPopupByHotKey($buttonElements.length);
            }
        }
        e.preventDefault();
        return false;
    });
    $(document.body).on('keydown', 'input, select, textarea, a, button', 'Alt+n', function(e){
        if (($('body').find('div.dv-process-buttons:visible:last > .btn-group-devided > a[data-actiontype="insert"]:eq(0)').length > 0 && $('body').find('div.dv-process-buttons:visible:last > .btn-group-devided > a[data-actiontype="insert"]:eq(0)').is(':visible')) 
            || ($('body').find('div.dv-process-buttons:visible:last > .btn-group-devided > .dv-buttons-batch').length > 0 && $('body').find('div.dv-process-buttons:visible:last > .btn-group-devided > .dv-buttons-batch').is(':visible'))) {
            
            var $buttonElement = $('body').find('div.dv-process-buttons:visible:last a[data-actiontype="insert"]:visible');
            var $batchElement = $('body').find('div.dv-process-buttons:visible:last > .btn-group-devided > .dv-buttons-batch:visible');
            var $buttonElements = $batchElement.find('ul.dropdown-menu > li > a[data-actiontype="insert"]');
            
            if ($buttonElement.length == 1 && $buttonElements.length == 0) {
                $buttonElement.click();
            } else if ($buttonElement.length == 0 && $buttonElements.length == 1) {
                $buttonElements.click();
            } else if (($buttonElement.length && $buttonElements.length) 
                || ($buttonElement.length == 0 && $buttonElements.length > 1) 
                || ($buttonElement.length > 1 && $buttonElements.length == 0)) {
                createProcessPopupByHotKey($buttonElements.length);
            }
        }
        e.preventDefault();
        return false;
    });
    $(document).bind('keydown', 'Ctrl+Shift+left', function(e){
        if (!$('.ui-dialog:visible').length && $('body').find('ul.card-multi-tab-navtabs > li').length > 1 && $('body').find('ul.card-multi-tab-navtabs > li > a.active').is(':visible')) {
            var $buttonElement = $('body').find('ul.card-multi-tab-navtabs > li > a.active:visible:last').closest('li');
            $buttonElement.prev().find('a:eq(0)').click();
            
            e.preventDefault();
            return false;
        }
    });
    $(document.body).on('keydown', 'input, select, textarea, a, button', 'Ctrl+Shift+left', function(e){
        if (!$('.ui-dialog:visible').length && $('body').find('ul.card-multi-tab-navtabs > li').length > 1 && $('body').find('ul.card-multi-tab-navtabs > li > a.active').is(':visible')) {
            var $buttonElement = $('body').find('ul.card-multi-tab-navtabs > li > a.active:visible:last').closest('li');
            $buttonElement.prev().find('a:eq(0)').click();
            
            e.preventDefault();
            return false;
        }
    });
    $(document).bind('keydown', 'Ctrl+Shift+right', function(e){
        if (!$('.ui-dialog:visible').length && $('body').find('ul.card-multi-tab-navtabs > li').length > 1 && $('body').find('ul.card-multi-tab-navtabs > li > a.active').is(':visible')) {
            var $buttonElement = $('body').find('ul.card-multi-tab-navtabs > li > a.active:visible:last').closest('li');
            if ($buttonElement.next().length) {
                $buttonElement.next().find('a:eq(0)').click();
                
                e.preventDefault();
                return false;
            }
        }
    });  
    $(document.body).on('keydown', 'input, select, textarea, a, button', 'Ctrl+Shift+right', function(e){
        if (!$('.ui-dialog:visible').length && $('body').find('ul.card-multi-tab-navtabs > li').length > 1 && $('body').find('ul.card-multi-tab-navtabs > li > a.active').is(':visible')) {
            var $buttonElement = $('body').find('ul.card-multi-tab-navtabs > li > a.active:visible:last').closest('li');
            if ($buttonElement.next().length) {
                $buttonElement.next().find('a:eq(0)').click();
                
                e.preventDefault();
                return false;
            }
        }
    });
    /**
     * Process tab move using keyboard
     */
    $(document).bind('keydown', 'Ctrl+Left Arrow', function(e){
        if ($('body').find('.bp-tabs').find('ul.nav-tabs > li').length > 1 && $('body').find('.bp-tabs').find('ul.nav-tabs > li > a.active').is(':visible')) {
            var $buttonElement = $('body').find('.bp-tabs').find('ul.nav-tabs > li > a.active:visible:last').closest('li');
            if ($buttonElement.prev().length) {
                if ($buttonElement.prev().find('a:eq(0):visible').length) {
                    $buttonElement.prev().find('a:eq(0)').click();
                } else if ($buttonElement.prev().prev().length) {
                    if ($buttonElement.prev().prev().find('a:eq(0):visible').length) {
                        $buttonElement.prev().prev().find('a:eq(0)').click();
                    } else if ($buttonElement.prev().prev().prev().length) {
                        $buttonElement.prev().prev().prev().find('a:eq(0)').click();
                    }
                }
                
                e.preventDefault();
                return false;
            }
        }
    });
    $(document).on('keydown', 'input, select, textarea, a, button', 'Ctrl+Left Arrow', function(e){
        if ($('body').find('.bp-tabs').find('ul.nav-tabs > li').length > 1 && $('body').find('.bp-tabs').find('ul.nav-tabs > li > a.active').is(':visible')) {
            var $buttonElement = $('body').find('.bp-tabs').find('ul.nav-tabs > li > a.active:visible:last').closest('li');
            if ($buttonElement.prev().length) {
                if ($buttonElement.prev().find('a:eq(0):visible').length) {
                    $buttonElement.prev().find('a:eq(0)').click();
                } else if ($buttonElement.prev().prev().length) {
                    if ($buttonElement.prev().prev().find('a:eq(0):visible').length) {
                        $buttonElement.prev().prev().find('a:eq(0)').click();
                    } else if ($buttonElement.prev().prev().prev().length) {
                        $buttonElement.prev().prev().prev().find('a:eq(0)').click();
                    }
                }
                
                e.preventDefault();
                return false;
            }
        }
    });      
    $(document).bind('keydown', 'Ctrl+Right Arrow', function(e){
        if ($('body').find('.bp-tabs').find('ul.nav-tabs > li').length > 1 && $('body').find('.bp-tabs').find('ul.nav-tabs > li > a.active').is(':visible')) {
            var $buttonElement = $('body').find('.bp-tabs').find('ul.nav-tabs > li > a.active:visible:last').closest('li');
            if ($buttonElement.next().length) {
                if ($buttonElement.next().find('a:eq(0):visible').length) {
                    $buttonElement.next().find('a:eq(0)').click();
                } else if ($buttonElement.next().next().length) {
                    if ($buttonElement.next().next().find('a:eq(0):visible').length) {
                        $buttonElement.next().next().find('a:eq(0)').click();
                    } else if ($buttonElement.next().next().next().length) {
                        $buttonElement.next().next().next().find('a:eq(0)').click();
                    }
                }
                
                e.preventDefault();
                return false;
            }
        }
    });
    $(document).on('keydown', 'input, select, textarea, a, button', 'Ctrl+Right Arrow', function(e){
        if ($('body').find('.bp-tabs').find('ul.nav-tabs > li').length > 1 && $('body').find('.bp-tabs').find('ul.nav-tabs > li > a.active').is(':visible')) {
            var $buttonElement = $('body').find('.bp-tabs').find('ul.nav-tabs > li > a.active:visible:last').closest('li');
            if ($buttonElement.next().length) {
                if ($buttonElement.next().find('a:eq(0):visible').length) {
                    $buttonElement.next().find('a:eq(0)').click();
                } else if ($buttonElement.next().next().length) {
                    if ($buttonElement.next().next().find('a:eq(0):visible').length) {
                        $buttonElement.next().next().find('a:eq(0)').click();
                    } else if ($buttonElement.next().next().next().length) {
                        $buttonElement.next().next().next().find('a:eq(0)').click();
                    }
                }
                
                e.preventDefault();
                return false;
            }
        }
    });      
    $(document.body).on('keydown', 'input, select, textarea, a, button', 'Shift+del', function(e){
        var $this = $(this);
        
        if ($this.closest('.bprocess-table-dtl[data-table-path]')) {
            var $row = $this.closest('.bp-detail-row');
            if ($row.find('.bp-remove-row:visible').length) {
                var $removeCheckedRows = $row.closest('.tbody').find('.bp-detail-row:visible .checker > span > input[type="checkbox"][data-field-name="isDeleteRowConfirm"]:checked');
                if ($removeCheckedRows.length) {
                    bpCheckedDetailRemoveConfirm($removeCheckedRows.closest('.bp-detail-row'));
                } else {
                    $row.find('.bp-remove-row:visible').click();
                }
            }
        }
        
        e.preventDefault();
        return false;
    });
    
    if (!isCloseOnEscape) {
        $(document).bind('keydown', 'Shift+esc', function(e) {
            if ($('.ui-dialog').length && $('.ui-dialog').is(':visible')) {
                var $activeDialog = $('.ui-dialog:visible:last').find('.ui-dialog-content');
                $activeDialog.dialog('close');
            } else {
                if ($('body').find('a.bp-btn-back').length > 0 && $('body').find('a.bp-btn-back').is(':visible')) {
                    var $buttonElement = $('body').find('a.bp-btn-back:visible:last');
                    $buttonElement.click();
                } else {
                    if ($('body').find('ul.card-multi-tab-navtabs > li > a.active[data-type="process"]').length > 0 && $('body').find('ul.card-multi-tab-navtabs > li > a.active[data-type="process"]').is(':visible')) {
                        var $tabElement = $('body').find('ul.card-multi-tab-navtabs > li > a.active[data-type="process"]:visible:last').closest('li');
                        var $anchorElement = $tabElement.find('> a');
                        $('div.card-multi-tab > div.card-body > div.card-multi-tab-content').find('div'+$anchorElement.attr('href')).empty().remove();
                        var $prevLi = $tabElement.prev('li:not(.tabdrop)');
                        if ($prevLi.length === 0) {
                            var $prevLi = $tabElement.next('li:not(.tabdrop)');
                        }
                        $tabElement.remove();
                        $prevLi.find('a').tab('show');
                    }
                }
            }
            e.preventDefault();
            return false;
        });
        $(document.body).on('keydown', 'input, select, textarea, a, button', 'Shift+esc', function(e){
            if ($('.ui-dialog').length && $('.ui-dialog').is(':visible')) {
                var $activeDialog = $('.ui-dialog:visible:last').find('.ui-dialog-content');
                $activeDialog.dialog('close');
            } else {
                if ($('body').find('a.bp-btn-back').length > 0 && $('body').find('a.bp-btn-back').is(':visible')) {
                    var $buttonElement = $('body').find('a.bp-btn-back:visible:last');
                    $buttonElement.click();
                } else {
                    if ($('body').find('ul.card-multi-tab-navtabs > li > a.active[data-type="process"]').length > 0 && $('body').find('ul.card-multi-tab-navtabs > li > a.active[data-type="process"]').is(':visible')) {
                        var $tabElement = $('body').find('ul.card-multi-tab-navtabs > li > a.active[data-type="process"]:visible:last').closest('li');
                        var $anchorElement = $tabElement.find('> a');
                        $('div.card-multi-tab > div.card-body > div.card-multi-tab-content').find('div'+$anchorElement.attr('href')).empty().remove();
                        var $prevLi = $tabElement.prev('li:not(.tabdrop)');
                        if ($prevLi.length === 0) {
                            var $prevLi = $tabElement.next('li:not(.tabdrop)');
                        }
                        $tabElement.remove();
                        $prevLi.find('a').tab('show');
                    }
                }
            }
            e.preventDefault();
            return false;
        });
    }
    
    $(document.body).on('keydown', 'input.meta-autocomplete:not([readonly="readonly"], [readonly], readonly, [disabled="disabled"], [disabled], disabled)', function (e) {
        var keyCode = (e.keyCode ? e.keyCode : e.which);
        if (!e.shiftKey && keyCode === 13) {
            
            var $this = $(this);
            var isCodeWithPhoto = $this.hasClass('pf-codewithphoto-input');
            var url = 'mdobject/autoCompleteById';
            
            if (isCodeWithPhoto) {
                var $parent = $this.closest('.pf-codewithphoto-parent');
            } else {
                var $parent = $this.closest('div.meta-autocomplete-wrap');
                if ($parent.hasClass('mv-popup-control')) {
                    url = 'mdform/autoCompleteById';
                }
            }
            
            var _value = ($this.val()).trim();
            
            if (_value == '') {
                
                $this.removeAttr('data-ac-id');
                $parent.find("input[id*='_valueField']:not(:eq(0))").remove();
                $parent.find("input[id*='_valueField']").val('').attr('data-row-data', '').trigger('change');
                
                if (isCodeWithPhoto) {
                    var $img = $parent.find('img');
                    $img.attr({'title':'', 'src': ''}).on('error', function(){ onUserImgError(this); });
                } else {
                    $parent.find("input[id*='_nameField']").val('').attr('title', '');
                }
                                
            } else {
                
                $this.addClass('spinner2');
                _value = _value.replace(/[\u200B-\u200D\uFEFF]/g, '');
            
                setTimeout(function () {

                    var _processId = $this.attr("data-processid");
                    var _lookupId = $this.attr("data-lookupid");
                    var _metaDataCode = $this.attr("data-field-name");
                    var $bpElem = $parent.find("input[type='hidden']");
                    var _paramRealPath = $bpElem.attr("data-path");
                    var _isName = false;
                    var params = '';

                    if (typeof $bpElem.attr("data-in-param") !== 'undefined' && typeof $bpElem.attr('data-in-lookup-param') !== 'undefined' 
                        && $bpElem.attr("data-in-param") != '' && $bpElem.attr('data-in-lookup-param') != '') {

                        var $mainSelector = $this.closest('form');
                        
                        if ($mainSelector.attr('id') == 'default-criteria-form') {
                            $mainSelector = $mainSelector.closest('.main-dataview-container').find('form');
                        } else if ($mainSelector.closest('.selectable-dataview-grid').length) {
                            $mainSelector = $mainSelector.closest('.selectable-dataview-grid').find('form');
                        }
        
                        var paramsPathArr = $bpElem.attr('data-in-param').split('|');
                        var lookupPathArr = $bpElem.attr('data-in-lookup-param').split('|');

                        for (var i = 0; i < paramsPathArr.length; i++) {
                            var fieldPath = paramsPathArr[i];
                            var inputPath = lookupPathArr[i];
                            var fieldValue = '', isCriteria = false;

                            if ($("[data-path='"+fieldPath+"']", $mainSelector).length > 0) {
                                fieldValue = getBpRowParamNum($mainSelector, $bpElem, fieldPath);
                                isCriteria = true;
                            } else {
                                if (inputPath != fieldPath) {
                                    fieldValue = fieldPath;
                                    isCriteria = true;
                                }
                            }
                            
                            if (isCriteria) {
                                params += inputPath + "=" + fieldValue + "&";
                            }
                        }
                    }

                    if (typeof $bpElem.attr("data-criteria-param") !== 'undefined' && $bpElem.attr("data-criteria-param") != '') {
                        var paramsPathArr = $bpElem.attr("data-criteria-param").split('|');
                        for (var i = 0; i < paramsPathArr.length; i++) {
                            var fieldPathArr = paramsPathArr[i].split('@');
                            var fieldPath = fieldPathArr[0];
                            var inputPath = fieldPathArr[1];
                            var fieldValue = '', isCriteria = false;

                            if ($("[data-path='"+fieldPath+"']", $this.closest('form')).length) {
                                fieldValue = getBpRowParamNum($this.closest('form'), $this, fieldPath);
                                isCriteria = true;
                            } else if ($this.closest('.popup-parent-tag').length) {
                                fieldValue = getBpRowParamNum($this.closest('.popup-parent-tag'), $this, fieldPath);
                                isCriteria = true;
                            } else {
                                if (inputPath != fieldPath) {
                                    fieldValue = fieldPath;
                                    isCriteria = true;
                                }
                            }
                            
                            if (isCriteria) {
                                params += inputPath + '=' + fieldValue + '&';
                            }
                        }
                    }

                    if (typeof $bpElem.attr('data-criteria') !== 'undefined' && $bpElem.attr('data-criteria') !== '') {
                        params += $bpElem.attr('data-criteria');
                    }

                    if (typeof $this.attr('data-ac-id') !== 'undefined') {
                        _isName = 'idselect';
                        _value = $this.attr('data-ac-id');
                    }

                    $.ajax({
                        type: 'post',
                        url: url,
                        data: {
                            processMetaDataId: _processId,
                            lookupId: _lookupId, 
                            paramRealPath: _paramRealPath,
                            code: _value,
                            isName: _isName, 
                            params: encodeURIComponent(params) 
                        },
                        dataType: 'json',
                        async: false,
                        success: function (data) {

                            $this.removeAttr('data-ac-id');

                            var controlsData, rowData;

                            if (typeof (data.controlsData) !== 'undefined') {
                                controlsData = data.controlsData;
                            }
                            if (typeof (data.rowData) !== 'undefined') {
                                rowData = data.rowData;
                            }

                            if (controlsData !== undefined) {
                                
                                if ($parent.closest("table.bprocess-table-dtl").length > 0) {
                                    var $parentTable = $parent.closest('.bp-detail-row');
                                } else {
                                    var $parentTable = $parent.closest('form');
                                }
                            
                                var i = 0, controlsDataCount = controlsData.length;

                                for (i; i < controlsDataCount; i++) {

                                    var v = controlsData[i];

                                    if (typeof rowData[v.FIELD_NAME] !== 'undefined' && _metaDataCode !== v.META_DATA_CODE) {
                                        var $getPathElement = $parentTable.find("[data-field-name='" + v.META_DATA_CODE + "']");

                                        if ($getPathElement.length > 0) {
                                            if ($getPathElement.prop("tagName").toLowerCase() == 'select') {
                                                if ($getPathElement.hasClass('select2')) {
                                                    $getPathElement.trigger("select2-opening", 'notdisabled');
                                                    $getPathElement.select2('val', rowData[v.FIELD_NAME]);
                                                } else {                                                
                                                    $getPathElement.trigger("focus");
                                                    $getPathElement.val(rowData[v.FIELD_NAME]);
                                                }
                                            } else if ($getPathElement.hasClass('dateInit')) {
                                                $getPathElement.datepicker('update', date('Y-m-d', strtotime(rowData[v.FIELD_NAME])));
                                            } else if ($getPathElement.hasClass('bigdecimalInit')) {
                                                $getPathElement.next("input[type=hidden]").val(setNumberToFixed(rowData[v.FIELD_NAME]));
                                                $getPathElement.val(rowData[v.FIELD_NAME]).trigger('change');
                                            } else {
                                                $getPathElement.val(html_entity_decode(rowData[v.FIELD_NAME])).trigger('change');
                                            }
                                        }
                                    }
                                }
                            }

                            if (data.META_VALUE_ID !== '') {
                                
                                $parent.find("input[id*='_valueField']:not(:eq(0))").remove();
                                $parent.find("input[id*='_valueField']").val(data.META_VALUE_ID).attr('data-row-data', JSON.stringify(rowData).replace(/&quot;/g, '\\&quot;'));
                                $parent.find("input[id*='_displayField']").val(data.META_VALUE_CODE).attr('title', data.META_VALUE_CODE).removeClass('error');
                                $parent.find("input[id*='_nameField']").val(data.META_VALUE_NAME).attr('title', data.META_VALUE_NAME).removeClass('error');
                                
                                if (isCodeWithPhoto) {
                                    
                                    var $img = $parent.find('img');
                                    $img.attr('title', data.META_VALUE_NAME);
                                    
                                    if (rowData.hasOwnProperty('picture') && rowData.picture) {
                                        $img.attr('src', rowData.picture).on('error', function(){ onUserImgError(this); });
                                    }
                                }
                                
                            } else {
                                $parent.find("input[id*='_valueField']:not(:eq(0))").remove();
                                $parent.find("input[id*='_valueField']").val('').attr('data-row-data', '');
                                $parent.find("input[id*='_nameField']").val('').attr('title', '');
                                bpSoundPlay('error');
                            }

                            var $selectedTR = $('.bprocess-table-dtl .tbody').find('.currentTarget');
                            var fieldPath = $parent.attr('data-section-path');
                            if ($selectedTR.find("td:last-child").find("i.input_html").find("div[data-section-path='" + fieldPath + "']").length > 0) {
                                $parent.find("input").removeClass("spinner2");
                                $selectedTR.find("td:last-child").find("i.input_html").find("div[data-section-path='" + fieldPath + "']").empty().append($parent.html());
                            }
                        },
                        error: function () {
                            alert("Error");
                        }
                    }).done(function(){
                        $parent.find("input[id*='_valueField']").trigger('change');
                        $this.removeClass('spinner2');
                    });

                }, 100);
            }
            
        } else if (keyCode === 113) {
            
            var $this = $(this), isCodeWithPhoto = $this.hasClass('pf-codewithphoto-input');
            
            if (isCodeWithPhoto) {
                $this.closest('.pf-codewithphoto-parent').find('button.pf-codewithphoto-popup').click();
            } else {
                $this.closest('.double-between-input').find('button').click();
            }
            
            return e.preventDefault();
        }
    });
    $(document.body).on('keydown', 'input.meta-name-autocomplete:not([readonly="readonly"], [readonly], readonly, [disabled="disabled"], [disabled], disabled)', function (e) {
        var keyCode = (e.keyCode ? e.keyCode : e.which);
        if (!e.shiftKey && keyCode === 13) {
            
            var $this = $(this);
            $this.addClass('spinner2');
            
            setTimeout(function () {
            
                var _value = ($this.val()).trim();
                _value = _value.replace(/[\u200B-\u200D\uFEFF]/g, '');
                
                var _processId = $this.attr("data-processid");
                var _lookupId = $this.attr("data-lookupid");
                var _metaDataCode = $this.attr("data-field-name");
                var $bpElem = $this.parent().parent().find("input[type='hidden']");
                var _paramRealPath = $bpElem.attr("data-path");
                var $parent = $this.closest("div.meta-autocomplete-wrap");
                var _isName = true;
                var params = '';
                var url = 'mdobject/autoCompleteById';
                
                if ($parent.hasClass('mv-popup-control')) {
                    url = 'mdform/autoCompleteById';
                }

                if (typeof $bpElem.attr('data-in-param') !== 'undefined' && typeof $bpElem.attr('data-in-lookup-param') !== 'undefined' 
                    && $bpElem.attr('data-in-param') != '' && $bpElem.attr('data-in-lookup-param') != '') { 
                
                    var $mainSelector = $this.closest('form');
                    if ($mainSelector.attr('id') == 'default-criteria-form') {
                        $mainSelector = $mainSelector.closest('.main-dataview-container').find('form');
                    } else if ($mainSelector.closest('.selectable-dataview-grid').length) {
                        $mainSelector = $mainSelector.closest('.selectable-dataview-grid').find('form');
                    }
                        
                    var paramsPathArr = $bpElem.attr('data-in-param').split('|');
                    var lookupPathArr = $bpElem.attr('data-in-lookup-param').split('|');

                    for (var i = 0; i < paramsPathArr.length; i++) {
                        var fieldPath = paramsPathArr[i];
                        var inputPath = lookupPathArr[i];
                        var fieldValue = '', isCriteria = false;

                        if ($("[data-path='"+fieldPath+"']", $mainSelector).length > 0) {
                            fieldValue = getBpRowParamNum($mainSelector, $bpElem, fieldPath);
                            isCriteria = true;
                        } else {
                            if (inputPath != fieldPath) {
                                fieldValue = fieldPath;
                                isCriteria = true;
                            }
                        }
                        
                        if (isCriteria) {
                            params += inputPath + "=" + fieldValue + "&";
                        }
                    }
                }
                
                if (typeof $bpElem.attr("data-criteria-param") !== 'undefined' && $bpElem.attr("data-criteria-param") != '') {
                    var paramsPathArr = $bpElem.attr("data-criteria-param").split('|');
                    for (var i = 0; i < paramsPathArr.length; i++) {
                        var fieldPathArr = paramsPathArr[i].split('@');
                        var fieldPath = fieldPathArr[0];
                        var inputPath = fieldPathArr[1];
                        var fieldValue = '', isCriteria = false;

                        if ($("[data-path='"+fieldPath+"']", $this.closest('form')).length > 0) {
                            fieldValue = getBpRowParamNum($this.closest('form'), $this, fieldPath);
                            isCriteria = true;
                        } else if ($this.closest('.popup-parent-tag').length) {
                            fieldValue = getBpRowParamNum($this.closest('.popup-parent-tag'), $this, fieldPath);
                            isCriteria = true;
                        } else {
                            if (inputPath != fieldPath) {
                                fieldValue = fieldPath;
                                isCriteria = true;
                            }
                        }
                        
                        if (isCriteria) {
                            params += inputPath + "=" + fieldValue + "&";
                        }
                    }
                }
                
                if (typeof $bpElem.attr('data-criteria') !== 'undefined' && $bpElem.attr('data-criteria') !== '') {
                    params += $bpElem.attr('data-criteria');
                }

                if (typeof $this.attr('data-ac-id') !== 'undefined') {
                    _isName = 'idselect';
                    _value = $this.attr('data-ac-id');
                }

                $.ajax({
                    type: 'post',
                    url: url,
                    data: {
                        processMetaDataId: _processId,
                        lookupId: _lookupId, 
                        paramRealPath: _paramRealPath,
                        code: _value,
                        isName: _isName, 
                        params: encodeURIComponent(params) 
                    },
                    dataType: 'json',
                    async: false,
                    success: function (data) {

                        $this.removeAttr('data-ac-id');

                        var controlsData;
                        var rowData;

                        if (typeof (data.controlsData) !== 'undefined') {
                            controlsData = data.controlsData;
                        }
                        if (typeof (data.rowData) !== 'undefined') {
                            rowData = data.rowData;
                        }

                        if ($parent.closest("div.bp-param-cell").length > 0) {
                            var $parentCell = $parent.closest("div.bp-param-cell");
                            var $parentTable = $parent.closest("div.xs-form");
                        } else if ($parent.closest("div.form-md-line-input").length > 0) {
                            var $parentCell = $parent.closest("div.form-md-line-input");
                            var $parentTable = $parent.closest("div.xs-form");
                        } else {
                            if ($parent.closest("div.meta-autocomplete-wrap").length > 0) {
                                var $parentCell = $parent.closest("div.meta-autocomplete-wrap");
                            } else {
                                var $parentCell = $parent.closest("td");
                            }

                            if ($parent.closest(".bprocess-table-dtl").length > 0) {
                                var $parentTable = $parent.closest(".bp-detail-row");
                            } else {
                                var $parentTable = $parent.closest("form");
                            }
                        }

                        if (controlsData !== undefined) {
                            var i = 0, controlsDataCount = controlsData.length;

                            for (i; i < controlsDataCount; i++) {

                                var v = controlsData[i];

                                if (typeof rowData[v.FIELD_NAME] !== 'undefined' && _metaDataCode !== v.META_DATA_CODE) {
                                    var $getPathElement = $parentTable.find("[data-field-name='" + v.META_DATA_CODE + "']");

                                    if ($getPathElement.length > 0) {
                                        if ($getPathElement.prop('tagName') == 'SELECT') {
                                            if ($getPathElement.hasClass('select2')) {
                                                $getPathElement.trigger('select2-opening', 'notdisabled');
                                                $getPathElement.select2('val', rowData[v.FIELD_NAME]);
                                            } else {                                                
                                                $getPathElement.trigger('focus');
                                                $getPathElement.val(rowData[v.FIELD_NAME]);
                                            }
                                        } else if ($getPathElement.hasClass('dateInit')) {
                                            $getPathElement.datepicker('update', date('Y-m-d', strtotime(rowData[v.FIELD_NAME])));
                                        } else if ($getPathElement.hasClass('bigdecimalInit')) {
                                            $getPathElement.next("input[type=hidden]").val(setNumberToFixed(rowData[v.FIELD_NAME]));
                                            $getPathElement.val(rowData[v.FIELD_NAME]).trigger('change');
                                        } else {
                                            $getPathElement.val(html_entity_decode(rowData[v.FIELD_NAME])).trigger('change');
                                        }
                                    }
                                }
                            }
                        }

                        if (data.META_VALUE_ID !== '') {
                            $parent.find("input[id*='_valueField']:not(:eq(0))").remove();
                            $parent.find("input[id*='_valueField']").val(data.META_VALUE_ID).attr('data-row-data', JSON.stringify(rowData).replace(/&quot;/g, '\\&quot;'));
                            $parent.find("input[id*='_displayField']").val(data.META_VALUE_CODE).attr('title', data.META_VALUE_CODE).removeClass('error');
                            $parent.find("input[id*='_nameField']").val(data.META_VALUE_NAME).attr('title', data.META_VALUE_NAME).removeClass('error');
                            if (data.hasOwnProperty('attributes')) {
                                $parent.find("input[id*='_valueField']").attr('data-attributes', JSON.stringify(data.attributes).replace(/&quot;/g, '\\&quot;'));
                            }
                        } else {
                            $parent.find("input[id*='_valueField']:not(:eq(0))").remove();
                            $parent.find("input[id*='_valueField']").val('').attr('data-row-data', '');  
                            $parent.find("input[id*='_displayField']").val('');  
                            $parent.find("input[id*='_nameField']").attr('title', '');
                            bpSoundPlay('error');
                        }

                        var $selectedTR = $('table.bprocess-table-dtl tbody').find('tr.currentTarget');
                        var fieldPath = $parent.attr('data-section-path');
                        if ($selectedTR.find("td:last-child").find("i.input_html").find("div[data-section-path='" + fieldPath + "']").length > 0) {
                            $parent.find("input").removeClass("spinner2");
                            $selectedTR.find("td:last-child").find("i.input_html").find("div[data-section-path='" + fieldPath + "']").empty().append($parent.html());
                        }
                    },
                    error: function () {
                        alert("Error");
                    }
                }).done(function(){
                    $parent.find("input[id*='_valueField']").trigger('change');
                    $this.removeClass('spinner2');
                });
            
            }, 100);
            
        } else if (keyCode === 113) {
            $(this).closest('.double-between-input').find('button').click();
            return e.preventDefault();
        }
    });
    
    $(document.body).on('focus', 'input.lookup-code-autocomplete:not(disabled, readonly)', function(e){
        lookupAutoComplete($(this), 'code');
    });
    $(document.body).on('focus', 'input.lookup-name-autocomplete:not(disabled, readonly)', function(e){
        lookupAutoComplete($(this), 'name');
    }); 
    $(document.body).on('focus', '.lookup-text-autocomplete:not(disabled, readonly)', function(e){
        lookupTextAutoComplete($(this), 'name');
    });
    $(document.body).on('keydown', 'input.lookup-code-autocomplete:not(disabled, readonly)', function(e){
        var code = (e.keyCode ? e.keyCode : e.which);
        var $this = $(this);
        if (code === 13) {
            if ($this.data("ui-autocomplete")) {
                $this.autocomplete("destroy");
            }
            return false;
        } else {
            if (!$this.data("ui-autocomplete")) {
                lookupAutoComplete($this, 'code');
            }
        }
    });
    $(document.body).on('keydown', '.lookup-mention-autocomplete:not(disabled, readonly)', function(e){
        var code = (e.keyCode ? e.keyCode : e.which);
        var $this = $(this);

        if (code === 13) {
            $this.autocomplete('destroy');
            e.preventDefault();
            return false;
        } else {
            lookupTextMention($this, 'name');
        }
    });
    $(document.body).on('keydown', 'input.lookup-name-autocomplete:not(disabled, readonly)', function(e){
        var code = (e.keyCode ? e.keyCode : e.which);
        var $this = $(this);
        if (code === 13) {
            if ($this.data('ui-autocomplete')) {
                $this.autocomplete('destroy');
            }
            return false;
        } else {
            if (!$this.data('ui-autocomplete')) {
                lookupAutoComplete($this, 'name');
            }
        }
    });
    $(document.body).on('keydown', '.lookup-text-autocomplete:not(disabled, readonly)', function(e){
        var code = (e.keyCode ? e.keyCode : e.which);
        var $this = $(this);
        if (code === 13) {
            if ($this.data('ui-autocomplete')) {
                $this.autocomplete('destroy');
            }
            return false;
        } else {
            if (!$this.data('ui-autocomplete')) {
                lookupTextAutoComplete($this, 'name');
            }
        }
    });
    $(document.body).on('keydown', 'input.lookup-code-hard-autocomplete:not([readonly="readonly"], [readonly], readonly, [disabled="disabled"], [disabled], disabled)', function (e) {
        var keyCode = (e.keyCode ? e.keyCode : e.which);
        if (!e.shiftKey && keyCode === 13) {
            
            var $this = $(this), _value = $this.val();
            
            if (_value !== '') {
                var $form = $this.closest('div[data-bp-uniq-id]'), _isName = false, _paramRealPath = $this.attr('data-path'), params = '', 
                    _lookupId = $this.attr('data-lookupid'), _processId = $this.attr('data-processid'), isIndicator = false, 
                    autoCompleteUrl = 'mdobject/autoCompleteById';

                if (typeof $this.attr('data-in-param') !== 'undefined' && $this.attr('data-in-param') != '') {
                    var _inputParam = $this.attr('data-in-param').split('|'), 
                        _lookupParam = $this.attr('data-in-lookup-param').split('|');

                    for (var i = 0; i < _inputParam.length; i++) {
                        var $paramField = getBpElement($form, $this, _inputParam[i]);
                        if ($paramField.length) {
                            var paramVal = '';
                            if ($paramField.prop('tagName') == 'SELECT') {
                                if ($paramField.hasClass('select2')) {
                                    paramVal = $paramField.select2('val');
                                } else {
                                    paramVal = $paramField.val();
                                }
                            } else {
                                paramVal = $paramField.val();
                            }
                            params += _lookupParam[i] + "=" + paramVal + "&";
                        }
                    }
                }  
                
                if ($this.hasAttr('data-filter-type') && $this.attr('data-filter-type') == 'name') {
                    _isName = 'true';
                }
                
                var postData = {
                    processMetaDataId: _processId,
                    lookupId: _lookupId, 
                    paramRealPath: _paramRealPath,
                    code: _value,
                    isName: _isName, 
                    params: encodeURIComponent(params) 
                };
                
                if ($this.hasAttr('data-filter-path') && $this.attr('data-filter-path') != '') {
                    postData.filterPath = $this.attr('data-filter-path');
                }
                
                if ($this.hasAttr('data-rowid') && $this.attr('data-rowid') != '') {
                    isIndicator = true;
                    if ($this.attr('data-lookuptype') == 'indicator') {
                        autoCompleteUrl = 'mdform/autoCompleteById';
                    }
                }
                
                $.ajax({
                    type: 'post',
                    url: autoCompleteUrl,
                    data: postData,
                    dataType: 'json',
                    async: false,
                    success: function(data) {
                        if (data.META_VALUE_ID !== '') {
                            if (isIndicator == false) {
                                window['selectedRowsBpAddRow_'+$form.attr('data-bp-uniq-id')]($this, _processId, _paramRealPath, _lookupId, [data.rowData], 'autocomplete');
                            } else {
                                mvRowsDetailFillCall($this, _processId, _paramRealPath, _lookupId, [data.rowData], 'autocomplete');
                            } 
                            $this.val('');
                            setTimeout(function(){ $this.focus(); }, 5);
                        } else {
                            bpSoundPlay('error');
                            PNotify.removeAll();
                            new PNotify({
                                title: 'Warning',
                                text: 'Хайлтын үр дүн олдсонгүй!',
                                type: 'warning',
                                sticker: false
                            });
                        }
                    }, 
                    error: function() { alert('Error'); }
                });
            }
            
        } else if (keyCode === 114) {
            $(this).closest('.bp-add-ac-row').find('button').click();
            return e.preventDefault();
        }
    });
    $(document.body).on('focus', 'input.lookup-code-hard-autocomplete:not(disabled, readonly)', function(e){
        var $this = $(this), 
            $form = $this.closest('div[data-bp-uniq-id]'), 
            _lookupId = $this.attr("data-lookupid"), 
            _processId = $this.attr("data-processid"), 
            _paramRealPath = $this.attr("data-path"), 
            params = '', linkedPopup = '', filterType = 'code', filterPath = '', 
            isIndicator = false, autoCompleteUrl = 'mdwebservice/lookupAutoComplete';

        if (typeof $this.attr('data-in-param') !== 'undefined' && $this.attr('data-in-param') != '') {
            var _inputParam = $this.attr('data-in-param').split('|'), 
                _lookupParam = $this.attr('data-in-lookup-param').split('|');

            for (var i = 0; i < _inputParam.length; i++) {
                var $paramField = getBpElement($form, $this, _inputParam[i]);
                if ($paramField.length) {
                    var paramVal = '';
                    if ($paramField.prop('tagName') == 'SELECT') {
                        if ($paramField.hasClass('select2')) {
                            paramVal = $paramField.select2('val');
                        } else {
                            paramVal = $paramField.val();
                        }
                    } else {
                        paramVal = $paramField.val();
                    }
                    params += _lookupParam[i] + '=' + paramVal + '&';
                }
            }
            
            if (params != '') {
                linkedPopup = 'OK';
            } 
        }         
        
        if ($this.hasAttr('data-filter-type') && $this.attr('data-filter-type') == 'name') {
            filterType = 'name';
        }
        
        if ($this.hasAttr('data-rowid') && $this.attr('data-rowid') != '') {
            isIndicator = true;
            if ($this.attr('data-lookuptype') == 'indicator') {
                autoCompleteUrl = 'mdform/lookupAutoComplete';
            }
        }
        
        if ($this.hasAttr('data-filter-path') && $this.attr('data-filter-path') != '') {
            filterPath = $this.attr('data-filter-path');
        }

        $this.autocomplete({
            minLength: 1,
            maxShowItems: 30,
            delay: 300, 
            highlightClass: 'lookup-ac-highlight', 
            appendTo: 'body',
            position: {my : 'left top', at: 'left bottom', collision: 'flip flip'}, 
            autoSelect: false,
            source: function(request, response) {

                if (lookupAutoCompleteRequest != null) {
                    lookupAutoCompleteRequest.abort();
                    lookupAutoCompleteRequest = null;
                }

                lookupAutoCompleteRequest = $.ajax({
                    type: 'post',
                    url: autoCompleteUrl,
                    dataType: 'json',
                    data: {
                        lookupId: _lookupId, 
                        processId: _processId, 
                        paramRealPath: _paramRealPath, 
                        q: request.term, 
                        type: filterType,
                        where: 'hardComplete',
                        params: encodeURIComponent(params),
                        linkedPopup: linkedPopup, 
                        filterPath: filterPath
                    },
                    success: function(data) {
                        response($.map(data, function(item) {
                            var code = item.codeName.split('|'), isQs = item.hasOwnProperty('isQs');
                            return {
                                valueId: code[0], 
                                value: code[1],  
                                label: code[1],
                                name: code[2],
                                data: item.row, 
                                isqs: isQs
                            };
                        }));
                    }
                });
            },
            focus: function() {
                return false;
            },
            open: function() {
                var $this = $(this);
                var $onTopElem = $this.closest('.ui-front');
                if ($onTopElem.length > 0) {
                    var $widget = $this.autocomplete('widget');
                    $widget.css('z-index', $onTopElem.css('z-index') + 1);
                }
                return false;
            },
            close: function() {
                $(this).autocomplete('option', 'appendTo', 'body'); 
            }, 
            select: function(event, ui) {
                
                if (isIndicator == false) {
                    if (ui.item.isqs) {
                        selectedRowsBpAddRowByQuickSearch($form.attr('data-bp-uniq-id'), $this, _processId, _paramRealPath, _lookupId, ui.item.valueId, params);
                    } else {
                        window['selectedRowsBpAddRow_'+$form.attr('data-bp-uniq-id')]($this, _processId, _paramRealPath, _lookupId, [ui.item.data], 'autocomplete');
                    }
                } else {
                    mvRowsDetailFillCall($this, _processId, _paramRealPath, _lookupId, [ui.item.data], 'autocomplete');
                }

                $this.val('');
                setTimeout(function(){
                    $this.focus();
                }, 5);
                
                return false;
            }
        }).autocomplete("instance")._renderItem = function(ul, item) {
            ul.addClass('lookup-ac-render');

            var $qTerm = this.term;

            if ($qTerm.indexOf('*') !== -1) {
                var $leftSubstr = $qTerm.substring(0, 1), $rightSubstr = $qTerm.substring(-1);

                if ($leftSubstr == '*' && $rightSubstr == '*') {
                    $qTerm = $qTerm.substring(0, -1).substring(1);
                } else if ($leftSubstr == '*') {
                    $qTerm = $qTerm.substring(1);
                } else if ($rightSubstr == '*') {
                    $qTerm = $qTerm.substring(0, -1);
                }
            }

            var re = new RegExp("(" + $qTerm + ")", "gi"),
                cls = this.options.highlightClass,
                template = "<span class='" + cls + "'>$1</span>";
            
            if (filterType == 'name') {
                var labelCode = item.label, labelName = item.name.replace(re, template);
            } else {
                var labelCode = item.label.replace(re, template), labelName = item.name;
            }

            return $('<li>').append('<div class="lookup-ac-render-code">'+labelCode+'</div><div class="lookup-ac-render-name">'+labelName+'</div>').appendTo(ul);
        };
    });
    $(document.body).on('keydown', 'input.lookup-hard-autocomplete:not(disabled, readonly)', function(e){
        var code = (e.keyCode ? e.keyCode : e.which);
        var $this = $(this);
        if (code === 13) {
            if ($this.data('ui-autocomplete')) {
                $this.autocomplete("destroy");
            }
            return false;
        } else {
            if (!$this.data('ui-autocomplete')) {
                $this.focus();
            }
        }
    });
    
    $(document.body).on('click', 'table.bprocess-table-dtl:not([data-pager="true"]) > thead > tr > th.bp-head-sort', function(){
        
        var $this = $(this), $table = $this.closest('table'), $tbody = $table.find('tbody:eq(0)');
        
        if ($tbody.find('tr').length > 1) {
            var $colIndex = $this.index(), $fieldTypeElem = $tbody.find('tr:eq(0) > td:eq('+$colIndex+')'), $fieldType = '';
            
            if ($fieldTypeElem.find('input.bigdecimalInit:eq(0)').length > 0) {
                $fieldType = 'number';
            } else if ($fieldTypeElem.find('div.checker').length > 0) {
                $fieldType = 'checkbox';
            } else if ($fieldTypeElem.find('div.meta-autocomplete-wrap').length > 0) {
                $fieldType = 'lookup';
            } else if ($fieldTypeElem.find('input[type=text]:eq(0)').length > 0) {
                $fieldType = 'text';
            } else {
                $fieldType = 'text';
            }
            
            $table.find('thead:eq(0) > tr > th').removeClass('bp-head-sort-asc bp-head-sort-desc');
            
            var rows = $tbody.children('tr').toArray().sort(bpComparer($colIndex, $fieldType));
            this.asc = !this.asc;
            
            if (!this.asc) { 
                $this.removeClass('bp-head-sort-asc').addClass('bp-head-sort-desc');
                rows = rows.reverse(); 
            } else {
                $this.removeClass('bp-head-sort-desc').addClass('bp-head-sort-asc');
            }
            for (var i = 0; i < rows.length; i++) {
                $tbody.append(rows[i]);
            }
            
            if ($this.hasAttr('data-merge-cell')) {
                bpDetailMergeCells($tbody);
            }
            
            var el = $tbody.children('tr:visible'), len = el.length, i = 0;
            for (i; i < len; i++) { 
                $(el[i]).find('td:eq(0) > span').text(i + 1);
            }
        }
    });
    $(document.body).on('click', '.bp-head-lookup-sort-code', function(){
        
        var $this = $(this), $table = $this.closest('table'), $tbody = $table.find('tbody:eq(0)');
        
        if ($tbody.find('tr').length > 1) {
            var $column = $this.closest('th'), $colIndex = $column.index(), $fieldType = 'lookup-code';
            
            $table.find('thead:eq(0) > tr > th > button').removeClass('bp-head-lookup-sort-code-asc bp-head-lookup-sort-code-desc bp-head-lookup-sort-name-asc bp-head-lookup-sort-name-desc');
            
            var rows = $tbody.children('tr').toArray().sort(bpComparer($colIndex, $fieldType));
            this.asc = !this.asc;
            
            if (!this.asc) { 
                $this.removeClass('bp-head-lookup-sort-code-asc').addClass('bp-head-lookup-sort-code-desc');
                rows = rows.reverse(); 
            } else {
                $this.removeClass('bp-head-lookup-sort-code-desc').addClass('bp-head-lookup-sort-code-asc');
            }
            for (var i = 0; i < rows.length; i++) {
                $tbody.append(rows[i]);
            }
            
            var el = $tbody.children('tr:visible'), len = el.length, i = 0;
            for (i; i < len; i++) { 
                $(el[i]).find('td:eq(0) > span').text(i + 1);
            }
        }
    });
    $(document.body).on('click', '.bp-head-lookup-sort-name', function(){
        
        var $this = $(this), $table = $this.closest('table'), $tbody = $table.find('tbody:eq(0)');
        
        if ($tbody.find('tr').length > 1) {
            var $column = $this.closest('th'), $colIndex = $column.index(), $fieldType = 'lookup-name';
            
            $table.find('thead:eq(0) > tr > th > button').removeClass('bp-head-lookup-sort-code-asc bp-head-lookup-sort-code-desc bp-head-lookup-sort-name-asc bp-head-lookup-sort-name-desc');
            
            var rows = $tbody.children('tr').toArray().sort(bpComparer($colIndex, $fieldType));
            this.asc = !this.asc;
            
            if (!this.asc) { 
                $this.removeClass('bp-head-lookup-sort-name-asc').addClass('bp-head-lookup-sort-name-desc');
                rows = rows.reverse(); 
            } else {
                $this.removeClass('bp-head-lookup-sort-name-desc').addClass('bp-head-lookup-sort-name-asc');
            }
            for (var i = 0; i < rows.length; i++) {
                $tbody.append(rows[i]);
            }
            
            var el = $tbody.children('tr:visible'), len = el.length, i = 0;
            for (i; i < len; i++) { 
                $(el[i]).find('td:eq(0) > span').text(i + 1);
            }
        }
    });
    $(document.body).on('keyup', 'table.bprocess-table-dtl:not([data-pager="true"]) > thead > tr > th input', function(e){
        var code = e.keyCode || e.which;
        if (code == '9') return;

        var $input = $(this), $table = $input.closest('table'), $rows = $table.find('tbody:first > tr'), 
            $filterRow = $input.closest('tr'), 
            $filterInputs = $filterRow.find('th:visible > input, th:visible > div'), 
            objs = {}, rowObj = {}, i = 0, isDoneRows = true;
            
        $filterInputs.each(function(){
            var $thisTag = $(this);
            
            if ($thisTag.prop('tagName') == 'DIV') {
                
                var $thisTagInput = $thisTag.find('input'), 
                    $filterInputVal = $thisTagInput.val();
                    
                if ($filterInputVal != '') {
                    rowObj = {};
                    rowObj['value'] = $filterInputVal.toLowerCase();
                    rowObj['path'] = $thisTagInput.attr('data-path-code');
                    rowObj['type'] = $thisTagInput.attr('data-type-code');
                    objs[i] = rowObj;
                    i++;
                }
                
            } else {
                var $filterInputVal = $thisTag.val();
                if ($filterInputVal != '') {
                    rowObj = {};
                    rowObj['value'] = $filterInputVal.toLowerCase();
                    rowObj['path'] = $thisTag.attr('data-path-code');
                    rowObj['type'] = $thisTag.attr('data-type-code');
                    objs[i] = rowObj;
                    i++;
                }
            }
        });    
        
        if (Object.keys(objs).length) {
            var $filteredRows = $rows.filter(function(){
                var $thisRow = $(this), value = '', isDoneRows = true;

                for (var key in objs) {
                    if (objs[key]['type'] == 'popup-code') {
                        value = $thisRow.find('[data-section-path="'+objs[key]['path']+'"] input[type="text"]:first').val().toLowerCase();
                        if (value.indexOf(objs[key]['value']) === -1) {
                            isDoneRows = false;
                        }
                    } else if (objs[key]['type'] == 'popup-name') {
                        value = $thisRow.find('[data-section-path="'+objs[key]['path']+'"] input[type="text"]:last').val().toLowerCase();
                        if (value.indexOf(objs[key]['value']) === -1) {
                            isDoneRows = false;
                        }
                    } else if (objs[key]['type'] == 'text' || objs[key]['type'] == 'label') {
                        value = $thisRow.find('[data-path="'+objs[key]['path']+'"]').text().toLowerCase();
                        if (value.indexOf(objs[key]['value']) === -1) {
                            isDoneRows = false;
                        }
                    } else {
                        value = $thisRow.find('[data-path="'+objs[key]['path']+'"]').val().toLowerCase();
                        if (value.indexOf(objs[key]['value']) === -1) {
                            isDoneRows = false;
                        }
                    }
                }    

                return isDoneRows !== true;
            });
        } else {
            var $filteredRows = $([]);
        }

        $rows.css({display: ''});
        $filteredRows.css({display: 'none'});
        
        $table.parent().trigger('scroll');
        
        var el = $table.find('tbody:first > tr:visible');
        var len = el.length, i = 0;
        var $aggr = $table.find('> thead > tr > th[data-aggregate]:visible');
        
        if (len) {
            for (i; i < len; i++) { 
                $(el[i]).find('td:first > span').text(i + 1);
            }
        }
        
        if ($aggr.length) {
            
            var aggrLength = $aggr.length, a = 0;
        
            for (a; a < aggrLength; a++) { 
                
                var $aggrCol = $($aggr[a]);
                var $funcName = $aggrCol.attr('data-aggregate');
                var $path = $aggrCol.attr('data-cell-path');
                
                if ($funcName == 'sum') {
                    
                    var sum = $table.find('> tbody > tr:visible > td[data-cell-path="' + $path + '"] input[type="text"]').sum();
                    
                    $table.find('> tfoot > tr > td[data-cell-path="' + $path + '"]').autoNumeric('set', sum);
                    
                } else if ($funcName == 'avg') {
                    
                    var avg = $table.find('> tbody > tr:visible > td[data-cell-path="' + $path + '"] input[type="text"]').avg();
                    $table.find('> tfoot > tr > td[data-cell-path="' + $path + '"]').autoNumeric('set', avg);
                    
                } else if ($funcName == 'min') {
                    
                    var min = $table.find('> tbody > tr:visible > td[data-cell-path="' + $path + '"] input[type="text"]').min();
                    $table.find('> tfoot > tr > td[data-cell-path="' + $path + '"]').autoNumeric('set', min);
                    
                } else if ($funcName == 'max') {
                    
                    var max = $table.find('> tbody > tr:visible > td[data-cell-path="' + $path + '"] input[type="text"]').max();
                    $table.find('> tfoot > tr > td[data-cell-path="' + $path + '"]').autoNumeric('set', max);
                }
            }
        }
    });

    $(document.body).on('change', 'table.bprocess-table-dtl:not([data-pager="true"]) > thead > tr > th > select', function(e){

        var $input = $(this), selectValue = $input.val(), column = $input.closest('th').index(), 
            $table = $input.closest('table'), $rows = $table.find('tbody:first > tr');
        
        if (selectValue == '1') {
            var $filteredRows = $rows.filter(function(){
                var $getCellElement = $(this).children('td:eq('+column+')'), 
                    $filteringInput = $getCellElement.find('input[type=checkbox]');
                return !$filteringInput.is(':checked');
            });
        } else if (selectValue == '0') {
            var $filteredRows = $rows.filter(function(){
                var $getCellElement = $(this).children('td:eq('+column+')'), 
                    $filteringInput = $getCellElement.find('input[type=checkbox]');
                return $filteringInput.is(':checked');
            });
        } else if (selectValue == 'all') {
            var $filteredRows = $([]);
        }

        $rows.css({display: ''});
        $filteredRows.css({display: 'none'});
        
        var el = $table.find('tbody:first > tr:visible');
        var len = el.length, i = 0;
        for (i; i < len; i++) { 
            $(el[i]).find('td:first > span').text(i + 1);
        }
    });
    
    $(document.body).on('click', 'table.bprocess-table-dtl[data-pager="true"] > thead > tr > th.bp-head-sort', function(){
        var $this = $(this), $table = $this.closest('table'), $tbody = $table.find('tbody:first');
        
        if ($tbody.find('tr').length > 1) {
            
            $table.find('thead:eq(0) > tr > th').not($this).removeClass('bp-head-sort-asc bp-head-sort-desc');
            
            if (!$this.hasClass('bp-head-sort-desc') && !$this.hasClass('bp-head-sort-asc')) {
                $this.addClass('bp-head-sort-asc');
            } else {
                if ($this.hasClass('bp-head-sort-asc')) { 
                    $this.removeClass('bp-head-sort-asc').addClass('bp-head-sort-desc');
                } else {
                    $this.removeClass('bp-head-sort-desc').addClass('bp-head-sort-asc');
                }
            }
            
            $table.closest('form').find('[data-pg-grouppath="'+$table.attr('data-table-path')+'"] .pf-bp-pager-refresh').click(); 
        }
    });
    $(document.body).on('keydown', 'table.bprocess-table-dtl[data-pager="true"] > thead > tr > th input', function(e){
        var code = (e.keyCode ? e.keyCode : e.which);
        
        if (code == 13) {
            var $this = $(this), $table = $this.closest('table'), 
                $refreshBtn = $table.closest('form').find('[data-pg-grouppath="'+$table.attr('data-table-path')+'"] .pf-bp-pager-refresh'); 
            $refreshBtn.attr('data-filter-enter', '1');
            $refreshBtn.click(); 
        }
    });
    $(document.body).on('change', 'table.bprocess-table-dtl[data-pager="true"] > thead > tr > th > select', function(e){
        var $this = $(this), $table = $this.closest('table'), 
            $refreshBtn = $table.closest('form').find('[data-pg-grouppath="'+$table.attr('data-table-path')+'"] .pf-bp-pager-refresh'); 
        $refreshBtn.attr('data-filter-enter', '1');
        $refreshBtn.click(); 
    });
    
    $(document.body).on('click', '.bprocess-table-dtl > .tbody > .bp-detail-row .bp-remove-row', function(){
        var $this = $(this), 
            $parentTbl = $this.closest('.bprocess-table-dtl'), 
            $parentRow = $this.closest('.bp-detail-row'), 
            $processForm = $parentTbl.closest('form'), 
            $uniqId = $processForm.parent().attr('data-bp-uniq-id');
            
        var selectorSidebar = $this.closest('div[data-bp-detail-container="1"]').parent().find('div[data-bp-detail-sidebar="1"]:eq(0)');
        
        if (selectorSidebar.length) {
            selectorSidebar.find('input').val('');
            selectorSidebar.find('textarea').val('');
            selectorSidebar.find('select').select2('val', '');

            var sidebarSaveButton = '<button type="button" class="btn blue btn-sm float-right" onclick="bpEditSidebarAddRow(this);">Нэмэх</button><div class="clearfix w-100 mb5"></div>';
            selectorSidebar.find('.sidebar-buttons').empty().append(sidebarSaveButton);
            
            window['editSidebarLoad_'+$uniqId]();
        }
        
        if ($parentTbl.hasAttr('data-pager')) {

            if (window['isEditMode_'+$uniqId]) {
                bpDetailPagerRowRemoveConfirm($processForm, $parentTbl, $parentRow, $this);
            } else {
                bpDetailPagerRowRemove($processForm, $parentTbl, $parentRow, $this);
            }

        } else {

            if ($parentRow.hasClass('saved-bp-row') && window['isEditMode_'+$uniqId]) {

                bpDetailRemoveConfirm($uniqId, $parentTbl, $parentRow, $this);

            } else {
                
                var $nextRow = $parentRow.next('.bp-detail-row:visible:eq(0)'), 
                    $prevRow = $parentRow.prev('.bp-detail-row:visible:eq(0)');

                if ($parentTbl.hasClass('cool-row')) {
                    var $addButton = $parentRow.find('button.bp-add-one-row');
                    var $prevtr = $parentRow.prev('.bp-detail-row');
                    if ($addButton.length && $prevtr) {
                        $prevtr.find('a.bp-remove-row').after($addButton.clone());
                    }
                }
                
                $parentRow.addClass('d-none removed-tr');
                $this.trigger('change'); 
                
                $parentRow.remove();
                
                var $parent = $parentTbl.parent(), 
                    $el = $parent.find('.bprocess-table-dtl:eq(0) > .tbody > .bp-detail-row'), 
                    len = $el.length, i = 0;
                    
                for (i; i < len; i++) { 
                    $($el[i]).find('td:first > span').text(i + 1);
                }

                if ($parentTbl.hasClass('bprocess-table-subdtl')) {
                    bpSetRowIndexDepth($parentTbl.parent(), window['bp_window_'+$uniqId]);
                } else {
                    bpSetRowIndex($parentTbl.parent());
                }
                
                enableBpDetailFilterByElement($parentTbl);
                window['dtlAggregateFunction_'+$uniqId]();
                
                if ($nextRow.length) {
                    var $nextRowFocus = $nextRow.find('input:not([data-isdisabled], [readonly="readonly"], [readonly], readonly, [disabled="disabled"], [disabled], disabled, input.meta-name-autocomplete):visible:first');
                    
                    if ($nextRowFocus.length) {
                        $nextRowFocus.focus().select();
                    } else {
                        $nextRow.find('input:not(input.meta-name-autocomplete):visible:first').focus().select();
                    }
                } else if ($prevRow.length) {
                    var $prevRowFocus = $prevRow.find('input:not([data-isdisabled], [readonly="readonly"], [readonly], readonly, [disabled="disabled"], [disabled], disabled, input.meta-name-autocomplete):visible:first');
                    
                    if ($prevRowFocus.length) {
                        $prevRowFocus.focus().select();
                    } else {
                        $prevRow.find('input:not(input.meta-name-autocomplete):visible:first').focus().select();
                    }
                }
            }
            
            if ($parentTbl.find('[data-merge-cell="true"]:eq(0)').length) {
                bpDetailMergeCells($parentTbl.find('> tbody'));
            }
        }
    });
    $(document.body).on('click', 'a.bp-remove-theme-row', function() {
        var $this = $(this);
        var $dialogName = 'dialog-confirm-bp-detail';
        if (!$("#" + $dialogName).length) {
            $('<div id="' + $dialogName + '"></div>').appendTo('body');
        }
        var $dialog = $('#' + $dialogName);
        
        $dialog.empty().append('Та бичлэгийг устгахдаа итгэлтэй байна уу?');
        $dialog.dialog({
            cache: false,
            resizable: false,
            bgiframe: true,
            autoOpen: false,
            title: 'Анхааруулга',
            width: 370,
            height: "auto",
            modal: true,
            close: function () {
                $dialog.empty().dialog('destroy').remove();
            },
            buttons: [
                {text: plang.get('yes_btn'), class: 'btn green-meadow btn-sm', click: function () {
                    $this.closest('div.bp-new-dtltheme').remove();
                    $dialog.dialog('close');
                }},
                {text: plang.get('no_btn'), class: 'btn blue-madison btn-sm', click: function () {
                    $dialog.dialog('close');
                }}
            ]
        });
        $dialog.dialog('open');
    });
    
    $(document.body).on('click', '.bp-checklist-tbl-row', function(){
        var $this = $(this);
        
        if ($this.hasClass('bp-checklist-row-checked')) {
            if (typeof $this.attr('data-process-id') === 'undefined') {
                $this.removeClass('bp-checklist-row-checked');
                $this.find("input[name*='bp_checklist[']").val('0');
            }
        } else {
            if (typeof $this.attr('data-process-id') !== 'undefined') {
                var processId = $this.attr('data-process-id');
                var selectedRowData = $this.attr('data-process-selectedRowData');
                var dmDataViewId = $this.attr('data-view-id');
                checkListBusinessProcess(processId, $this, selectedRowData, dmDataViewId);
            } else {
                $this.addClass('bp-checklist-row-checked');
                $this.find("input[name*='bp_checklist[']").val('1');
            }
        }
    });
    
    $(document.body).on('change', 'select.bp-template-id', function(e){
        var $this = $(this);
        var templateId = $this.val();
        
        if (templateId !== '') {
            
            var _parent = $this.closest('.bp-template-mode');
            var _form = _parent.find('form:eq(0)');
            var parent = _parent.find('div.bp-template-wrap');
            var processId = _parent.attr('data-process-id');
			
            $.ajax({
                type: 'post',
                url: 'mdwebservice/callMethodByMeta', 
                data: {
                    metaDataId: processId, 
                    bpTemplateId: templateId, 
                    runProcessResponse: _form.serialize() 
                },
                dataType: 'json',
                beforeSend: function(){
                    Core.blockUI({message: 'Loading...', boxed: true});
                    parent.children().off();
                },
                success: function(data){
                    
                    _parent.attr('data-bp-uniq-id', data.uniqId);
                    
                    var responseHtml = $('<div />', {html: data.Html});
                    var fillHtml = responseHtml.find('div.bp-template-wrap').html();
                    parent.empty().append(fillHtml);
                    
                    Core.unblockUI();
                }
            });
        }
    });
    
    $(document.body).delegate('table.bprocess-table-dtl > tbody > tr > td input.stringInit:not([readonly], [disabled])', 'paste', function(e){
        var $start = $(this), source;

        if (window.clipboardData !== undefined) {
            source = window.clipboardData;
        } else {
            source = e.originalEvent.clipboardData;
        }
        var data = source.getData('Text');
        
        if (data.indexOf("\n") !== -1 && data.length) {
            
            var $rowCell = $start.closest('td'); 
            var $colIndex = $rowCell.index();
            var columns = data.split("\n");
            var i, columnsLength = columns.length;
            
            for (i = 0; i < columnsLength; i++) {
                if (columns[i]) {
                    $start.val(columns[i].trim()).trigger('change');
                    $start = $start.closest('tr').next('tr').find('td:eq('+$colIndex+') input[type=text]:visible:eq(0)');
                    if (!$start.length) {
                        return false;  
                    }
                }
            }
            
            e.preventDefault();
        }
    });
    $(document.body).delegate('table.bprocess-table-dtl > tbody > tr > td input.longInit:not([readonly], [disabled]), table.bprocess-table-dtl > tbody > tr > td input.numberInit:not([readonly], [disabled]), table.bprocess-table-dtl > tbody > tr > td input.decimalInit:not([readonly], [disabled])', 'paste', function(e){
        var $start = $(this), source;

        if (window.clipboardData !== undefined) {
            source = window.clipboardData;
        } else {
            source = e.originalEvent.clipboardData;
        }
        var data = source.getData('Text');
        
        if (data.indexOf("\n") !== -1 && data.length) {
            
            var $rowCell = $start.closest('td'); 
            var $colIndex = $rowCell.index();
            var columns = data.split("\n");
            var i, columnsLength = columns.length;
            
            for (i = 0; i < columnsLength; i++) {
                if (columns[i]) {
                    $start.autoNumeric('set', columns[i]).trigger('change');
                    $start = $start.closest('tr').next('tr').find('td:eq('+$colIndex+') input[type=text]:visible:eq(0)');
                    if (!$start.length) {
                        return false;  
                    }
                }
            }
            
            e.preventDefault();
        }
    });
    $(document.body).delegate('table.bprocess-table-dtl > tbody > tr > td input.bigdecimalInit:not([readonly], [disabled])', 'paste', function(e){
        var $start = $(this), source;

        if (window.clipboardData !== undefined) {
            source = window.clipboardData;
        } else {
            source = e.originalEvent.clipboardData;
        }
        var data = source.getData('Text');
        
        if (data.indexOf("\n") !== -1 && data.length) {
            
            var $rowCell = $start.closest('td'); 
            var $colIndex = $rowCell.index();
            var columns = data.split("\n");
            var i, columnsLength = columns.length, cellVal = '';
            
            for (i = 0; i < columnsLength; i++) {
                if (columns[i]) {
                    cellVal = columns[i].replace(/[,]/g, '');
                    $start.next('input[type=hidden]').val(cellVal);
                    $start.autoNumeric('set', cellVal).trigger('change');
                    $start = $start.closest('tr').next('tr').find('td:eq('+$colIndex+') input[type=text]:visible:eq(0)');
                    if (!$start.length) {
                        return false;  
                    }
                }
            }
            
            e.preventDefault();
        }
    });
    
    $(document.body).on('shown.bs.tab', '#wsForm .bp-tabs > ul.nav-tabs > li > a', function(e) {
        var $this = $(this), $parent = $this.closest('.tabbable-line'), $id = $this.attr('href').replace('#', '');
        var $tabPane = $parent.find('.tab-pane[id="'+$id+'"]');
        $tabPane.find('input[required="required"]:not([data-isdisabled], [readonly="readonly"], [readonly], readonly, [disabled="disabled"], [disabled], disabled):visible:first').focus().select();
        Core.initTextareaAutoHeight($tabPane);
    });
    
    ion.sound({
        sounds: [
            /*{name: 'computer_error'},
            {name: 'water_droplet_2'},*/
            {name: 'door_bell'}, 
            {name: 'bell_ring_new'}
        ],
        path: 'assets/custom/addon/plugins/sound/ionsound/sounds/',
        preload: true,
        volume: 1
    });
    
    $(document.body).on('change', 'button.icon_pickerInit', function(e) {
        var $this = $(this), $input = $this.prev('input:hidden');
        if (e.icon === 'empty' || e.icon === 'fa-empty') {
            $input.val('');
        } else {
            $input.val(e.icon);
        }
    });
    
    $(document.body).on('show.bs.dropdown', '.bp-btn-datatemplate', function() {
        
        var $this = $(this);
        var $dropdown = $this.find('ul.media-list');
        
        if ($dropdown.children().length == 0) {
            
            var selectedId = $this.attr('data-selected-id');
            
            $.ajax({
                type: 'post',
                url: 'mduser/getBpValueTemplate',
                data: {processId: $this.closest('[data-bp-uniq-id]').attr('data-process-id')}, 
                dataType: 'json',
                async: false, 
                success: function (data) {

                    if (data.length) {

                        var list = [];

                        for (var k in data) {
                            list.push(bpValueTemplateItem({id: data[k]['ID'], name: data[k]['NAME'], isEdit: data[k]['IS_EDIT'], selectedId: selectedId}));
                        }

                        $dropdown.empty().append(list.join(''));
                    }
                }
            });
        }
        
        var selfOffset = $this.offset();
        var $dropDownContent = $this.find('.dropdown-content');
        var selfOffsetLeft = selfOffset.left - $dropDownContent.width() + $this.find('a.dropdown-toggle').innerWidth();
        
        setTimeout(function() {
            $dropDownContent.css({position: 'fixed', top: selfOffset.top + $this.height(), left: selfOffsetLeft, display: '', transform: ''});
        }, 5);
    });
    $(document.body).on('hide.bs.dropdown', '.bp-btn-datatemplate', function() {
        $(this).find('.dropdown-content').hide();
    });
    
    $(document.body).on('click', '.bpv-row-action[data-action="edit"]', function() {
        
        var $row = $(this).closest('li');
        
        if ($row.find('input[type="text"]').length == 0) {
            var templateName = $row.find('.media-body').text();
            $row.find('.media-body').empty().append('<input type="text" class="form-control form-control-sm bpv-row-input" value="'+templateName+'"/>');
            $row.find('.media-body').find('input').focus().select();
        }   
    });
    
    $(document.body).on('click', '.bpv-row-action[data-action="delete"]', function() {
        
        var $dialogName = 'dialog-bp-msg-confirm';
        if (!$("#" + $dialogName).length) {
            $('<div id="' + $dialogName + '"></div>').appendTo('body');
        }
        var $dialog = $('#' + $dialogName);
        var $this = $(this);
        
        $dialog.empty().append(plang.get('msg_delete_confirm'));
        $dialog.dialog({
            resizable: true,
            bgiframe: true,
            autoOpen: false,
            title: 'Confirm',
            width: 350,
            height: 'auto',
            modal: true,
            buttons: [
                {text: plang.get('yes_btn'), class: 'btn green-meadow btn-sm', click: function () {
                    
                    var $row = $this.closest('li');
                    
                    $dialog.dialog('close');
                    
                    $.ajax({
                        type: 'post',
                        url: 'mduser/deleteBpValueTemplate',
                        data: {id: $row.attr('data-id'), processId: $this.closest('[data-bp-uniq-id]').attr('data-process-id')},
                        dataType: 'json',
                        success: function (dataSub) {

                            PNotify.removeAll();
                            new PNotify({
                                title: dataSub.status,
                                text: dataSub.message,
                                type: dataSub.status,
                                sticker: false
                            });
                                
                            if (dataSub.status == 'success') {
                                
                                var $ul = $row.closest('ul');
                                
                                if ($ul.children().length == 1) {
                                    $ul.closest('.bp-btn-datatemplate').find('> .dropdown-toggle > i')
                                        .removeClass('icon-stack3 text-success')
                                        .addClass('icon-stack2');
                                }
                                
                                $row.remove();
                            } 
                        },
                        error: function () { alert("Error"); }
                    });
                }},
                {text: plang.get('no_btn'), class: 'btn blue-madison btn-sm', click: function () {
                    $dialog.dialog('close');
                }}
            ]
        });
        $dialog.dialog('open');
    });
    
    $(document.body).on('change', '.bpv-row-input', function() {
        var $this = $(this);
        var $row = $this.closest('li');
        var templateName = $row.find('input').val();
        
        $.ajax({
            type: 'post',
            url: 'mduser/updateBpValueTemplate',
            data: {id: $row.attr('data-id'), templateName: templateName}, 
            dataType: 'json',
            success: function (data) {
                
                PNotify.removeAll();
                new PNotify({
                    title: data.status,
                    text: data.message,
                    type: data.status,
                    sticker: false
                });
                    
                if (data.status == 'success') {
                    console.log('success');
                } 
            }
        });
    });
    
    $(document.body).on('focusout', '.bpv-row-input', function() {
        
        var $this = $(this);
        var $row = $this.closest('li');
        var templateName = $this.val();
        $row.find('.media-body').empty().append(templateName);
    });
    
    $(document.body).on('keydown', '.bpv-row-input', function(e) {
        
        var code = (e.keyCode ? e.keyCode : e.which);
        
        if (code == 13) {
            $(this).trigger('focusout');
        }
    });
    
    $(document.body).on('click', '.bpv-row-action-active', function() {
        var $this = $(this);
        var $row = $this.closest('li');
        var $processElement = $this.closest('[data-bp-uniq-id]');
        var $parent = $processElement.parent();
        var isDialog = true;
        var dmMetaDataId = $parent.find('input[name="dmMetaDataId"]').val();
        var openParams = $parent.find('#openParams').val();
        var wfmStatusParams = $parent.find('input[name="wfmStatusParams"]').val(), 
            wfmStringRowParams = $parent.find('input[name="wfmStringRowParams"]').val(), 
            realSourceIdAutoMap = $parent.find('input[name="realSourceIdAutoMap"]').val(), 
            srcAutoMapPattern = $parent.find('textarea[name="srcAutoMapPattern"]').val(), 
            isSystemProcess = $parent.find('input[name="isSystemProcess"]').val();
        
        if (!$parent.hasClass('ui-dialog-content')) {
            isDialog = false;
            $parent = $parent.parent();
        }
        
        var postData = {
            metaDataId: $processElement.attr('data-process-id'), 
            prevUniqId: $processElement.attr('data-bp-uniq-id'), 
            dataTemplateId: $row.attr('data-id'), 
            isDialog: isDialog, 
            isSystemMeta: false
        };
        
        if (dmMetaDataId != '') {
            postData.dmMetaDataId = dmMetaDataId;
        }
        
        if (openParams != '') {
            openParams = openParams.replace('"}', '","isDataTemplate":"true"}');
            postData.openParams = openParams;
        } else {
            postData.openParams = '{"isDataTemplate":"true"}';
        }
        
        if (isSystemProcess == 'true') {
            postData.isSystemMeta = true;
        }
        
        $.ajax({
            type: 'post',
            url: 'mdwebservice/callMethodByMeta', 
            data: postData, 
            dataType: 'json',
            beforeSend: function() {
                Core.blockUI({boxed : true, message: 'Loading...'});  
            }, 
            success: function (data) {
                $parent.empty().append(data.Html).promise().done(function () {
                    if (wfmStatusParams) {
                        $parent.find('input[name="wfmStatusParams"]').val(wfmStatusParams);
                    }
                    if (wfmStringRowParams) {
                        $parent.find('input[name="wfmStringRowParams"]').val(wfmStringRowParams);
                    }
                    if (typeof realSourceIdAutoMap != 'undefined' && realSourceIdAutoMap) {
                        
                        var autoMapInputs = '';
                        
                        if ($parent.find('input[name="realSourceIdAutoMap"]').length) {
                            $parent.find('input[name="realSourceIdAutoMap"], textarea[name="srcAutoMapPattern"]').remove();
                        }
                        
                        autoMapInputs = '<input type="hidden" name="realSourceIdAutoMap" value="'+realSourceIdAutoMap+'">';
                        
                        if (typeof srcAutoMapPattern != 'undefined' && srcAutoMapPattern) {
                            autoMapInputs += '<textarea class="d-none" name="srcAutoMapPattern">'+realSourceIdAutoMap+'</textarea>';
                        }
                        
                        $parent.find('#bprocessCoreParam').append(autoMapInputs);
                    }
                    Core.unblockUI();  
                });
            }, 
            error: function() { alert('Error'); Core.unblockUI(); }
        });
    });
    
    $(document.body).on('click', '[data-bp-comment-rc]', function() {
        
        var $this = $(this);
        var $parent = $this.closest('[data-structureid]');
        var $comment = $this.closest('[data-comment-id]');
        var commentId = $comment.attr('data-comment-id');
        var structureId = $parent.attr('data-structureid');
        var recordId = $parent.attr('data-recordid');
        var reactionTypeId = $this.attr('data-rtid');
        
        $.ajax({
            type: 'post',
            url: 'mdcomment/saveCommentReaction', 
            data: {structureId: structureId, recordId: recordId, commentId: commentId, reactionTypeId: reactionTypeId}, 
            dataType: 'json',
            success: function (data) {
                
                if (data.status == 'success') {
                    
                    var isReaction = $this.attr('data-bp-comment-rc');
                    
                    if (isReaction == '1') {
                        
                        $this.removeClass('text-primary').addClass('text-secondary').attr('data-bp-comment-rc', '0')
                            .find('i').removeClass('fa-thumbs-up').addClass('fa-thumbs-o-up');
                    
                    } else {
                        $this.removeClass('text-secondary').addClass('text-primary').attr('data-bp-comment-rc', '1')
                            .find('i').removeClass('fa-thumbs-o-up').addClass('fa-thumbs-up');
                    }
                    
                } else {
                    console.log(data);
                }
            }, 
            error: function() { alert('Error'); }
        });
    });
    
    $(document.body).on('click', '[data-bp-comment-reply]', function() {
        var $this = $(this);
        var $ul = $this.closest('ul');
        
        if (!$this.hasAttr('data-reply-opened')) {
            
            var $parent = $this.closest('[data-structureid]');
            var chatFormHtml = $parent.find('.chat-form').html();
            var $scrollPanel = $parent.find('[data-scroll-panel]');
            var $chatHtml = $('<div />', {html: chatFormHtml});
            var uniqId = $this.closest('[data-comment-uniqid]').attr('data-comment-uniqid');
            
            $chatHtml.find('.mentions-input-box').remove();
            $chatHtml.find('.media-body').append('<textarea name="mdcomment_text" id="mdcomment_text" style="background-color: #fff" class="form-control p-1 bpaddon-mention-autocomplete mention" placeholder="'+plang.get('task_comment_write')+'" onkeypress="if(event.keyCode == 13) saveMdCommentProcessValue_'+uniqId+'(this);"></textarea>');
            
            $chatHtml.find('.chat-addcontrol').removeAttr('data-emoji');
            $scrollPanel.find('[data-reply-opened]').removeAttr('data-reply-opened');
            $scrollPanel.find('.chat-addcontrol').remove();
            
            $.when($ul.after($chatHtml.html())).then(function() {
                bpCommentMentionsInputInit($ul.next('.chat-addcontrol')); 
            });
            
            var $chatForm = $ul.next('.media');
            var $textArea = $chatForm.find('textarea');
            
            $textArea.attr('placeholder', plang.get('comment_reply'));
            $textArea.focus();
            
            $this.attr('data-reply-opened', '1');
            
        } else {
            $this.removeAttr('data-reply-opened');
            $ul.next('.media').remove();
        }
    });
    
    $(document.body).on('click', '[data-bp-comment-edit]', function() {
        
        var $this = $(this);
        var $parent = $this.closest('.media-body');
        var $p = $parent.find('> div > p:eq(0)');
            
        if (!$this.hasAttr('data-edit-opened')) {
            
            $p.hide();
            $p.after('<textarea class="form-control p-1 mb-1" rows="2" data-bp-comment-edit-textarea="1">'+bpHtmlToText($p.html())+'</textarea>');
            
            $this.attr('data-edit-opened', '1');
            
        } else {
            
            $this.removeAttr('data-edit-opened');
            $parent.find('> div > textarea:eq(0)').remove();
            $p.show();
        }
    });
    
    $(document.body).on('keydown', '[data-bp-comment-edit-textarea]', function(e) {
        
        if (e.which === 13) {
            var $this = $(this);
            var text = $.trim($this.val());
            
            if (text.length == 0) {
                return;
            }
            
            var $tempCommentRow = $this.closest('[data-temp-comment]');
            
            if ($tempCommentRow.length) {
                
                $tempCommentRow.find('input[name="bpCommentText[]"]').val(text);
                
                $this.next('.list-inline:eq(0)').find('[data-bp-comment-edit]').removeAttr('data-edit-opened');
                $this.prev('p:eq(0)').html(text).show();
                $this.remove();
                            
            } else {
                var $commentRow = $this.closest('[data-comment-id]');
            
                $.ajax({
                    type: 'post',
                    url: 'mdcomment/updateCommentProcess',
                    data: {commentId: $commentRow.attr('data-comment-id'), commentText: text},
                    dataType: 'json',
                    success:function(data) {
                        if (data.status === 'success') {
                            $this.next('.list-inline:eq(0)').find('[data-bp-comment-edit]').removeAttr('data-edit-opened');
                            $this.prev('p:eq(0)').html(text).show();
                            $this.remove();
                        }
                    },
                    error:function() { alert('Error'); }
                });
            }
        }
    });
    
    $(document.body).on('click', '[data-bp-comment-remove]', function() {
        
        var $this = $(this);
        var dialogName = '#dialog-delete-confirm';
        if (!$(dialogName).length) {
            $('<div id="' + dialogName.replace('#', '') + '"></div>').appendTo('body');
        }
        var $dialog = $(dialogName);

        $dialog.html(plang.get('msg_delete_confirm'));
        $dialog.dialog({
            cache: false,
            resizable: true,
            bgiframe: true,
            autoOpen: false,
            title: plang.get('msg_title_confirm'), 
            width: 300,
            height: 'auto',
            modal: true,
            close: function() {
                $dialog.empty().dialog('destroy').remove();
            },
            buttons: [
                {text: plang.get('yes_btn'), class: 'btn green-meadow btn-sm', click: function() {
                    
                    var $tempCommentRow = $this.closest('[data-temp-comment]');
                    
                    if ($tempCommentRow.length) {
                        
                        $tempCommentRow.remove();
                        $dialog.dialog('close');
                        
                    } else {
                        var $commentRow = $this.closest('[data-comment-id]');
                        var commentId = $commentRow.attr('data-comment-id');
                        var $commentMainRow = $this.closest('[data-structureid]');
                        var commentStructureId = $commentMainRow.attr('data-structureid');
                        var $commentRecordId = $this.closest('[data-recordid]');
                        var commentRecordId = $commentRecordId.attr('data-recordid');

                        if (typeof window['mdCommentCallback_' + commentStructureId] === 'function') {
                            window['mdCommentCallback_' + commentStructureId]('', commentRecordId);
                        }
                        $commentRow.remove();
                        $dialog.dialog('close');

                        $.ajax({
                            type: 'post',
                            url: 'mdcomment/removeCommentProcess',
                            data: {commentId: commentId},
                            dataType: 'json',
                            success:function(data) {                        
                                console.log(data);
                            },
                            error:function() { alert('Error'); }
                        });
                    }
                }},
                {text: plang.get('no_btn'), class: 'btn blue-madison btn-sm', click: function () {
                    $dialog.dialog('close');
                }}
            ]
        });
        $dialog.dialog('open');
    });
    
    $(document.body).on('change', '.register-number-combo, .register_numberInit', function() {
        var $this = $(this), $parent = $this.closest('.input-group'), 
            firstLetter = $parent.find('select:eq(0)').val(), 
            secondLetter = $parent.find('select:eq(1)').val(), 
            numbers = $parent.find('input[type="text"]').val();
            
        $parent.find('input[type="hidden"]').val(firstLetter + '' + secondLetter + '' + numbers).trigger('change');
    });
    
    $(document.body).on('change', '.fileInit.form-control-uniform', function() {
        var $this = $(this);
        var fileNames = $.map($this[0].files, function (file) {return file.name;}).join(', ');
        var $parent = $this.closest('.uniform-uploader');
        var $fileNameTag = $parent.find('.filename');
        
        if (fileNames) {
            fileNames = fileNames.split(/[\/\\]+/);
            fileNames = fileNames[(fileNames.length - 1)];
            $fileNameTag.attr('title', fileNames);
        } else {
            fileNames = $fileNameTag.attr('data-text');
            $fileNameTag.attr('title', '');
        }
        
        $parent.find('a').remove();
        
        $fileNameTag.text(fileNames);
    });
    
    $(document.body).on('change', '.dateInit[data-pf-rangemax]:not([readonly],[disabled])', function(e, isTriggered) {
        var $this = $(this);
        
        if (!isTriggered && $this.inputmask('isComplete')) {
            
            var rangeMax = $this.attr('data-pf-rangemax');
            var $parent = $this.closest('form');
            var matches = []; /*var matches = rangeMax.match(/\[(.*?)\]/g);*/
            
            rangeMax.replace(/\[(.*?)\]/g, function(g0, g1) { matches.push(g1); });
            
            var maxPath = (matches[0]).trim();
            
            if (maxPath) {
                
                var $maxPathElement = $parent.find('[data-path="'+maxPath+'"]');
            
                if ($maxPathElement.length && $maxPathElement.inputmask('isComplete')) {

                    var matchesCount = matches.length;
                    var thisDate = $this.val();
                    var thisNewDate = new Date(thisDate);
                    var maxDate = new Date($maxPathElement.val());

                    if (matchesCount == 2) {

                        var rangeDate = matches[1];
                        var thisDateModify = date('Y-m-d', strtotime(rangeDate, strtotime(thisDate)));
                        var thisNewDateModify = new Date(thisDateModify);

                        if (maxDate > thisNewDateModify) {
                            $maxPathElement.datepicker('update', thisDateModify);
                        } else if (maxDate < thisNewDate) {
                            $maxPathElement.datepicker('update', thisDate);
                        }

                    } else {
                        if (maxDate < thisNewDate) {
                            $maxPathElement.datepicker('update', thisDate);
                        }
                    }
                }
            }
        }
    });
    
    $(document.body).on('change', '.dateInit[data-pf-rangemin]:not([readonly],[disabled])', function(e, isTriggered) {
        var $this = $(this);
        
        if (!isTriggered && $this.inputmask('isComplete')) {
            
            var rangeMin = $this.attr('data-pf-rangemin');
            var $parent = $this.closest('form');
            var matches = []; /*var matches = rangeMax.match(/\[(.*?)\]/g);*/
            
            rangeMin.replace(/\[(.*?)\]/g, function(g0, g1) { matches.push(g1); });
            
            var minPath = (matches[0]).trim();
            
            if (minPath) {
                
                var $minPathElement = $parent.find('[data-path="'+minPath+'"]');
            
                if ($minPathElement.length && $minPathElement.inputmask('isComplete')) {

                    var matchesCount = matches.length;
                    var thisDate = $this.val();
                    var thisNewDate = new Date(thisDate);
                    var minDate = new Date($minPathElement.val());

                    if (matchesCount == 2 && matches[1]) {

                        var rangeDate = matches[1];
                        var thisDateModify = date('Y-m-d', strtotime(rangeDate, strtotime(thisDate)));
                        var thisNewDateModify = new Date(thisDateModify);

                        if (minDate < thisNewDateModify) {
                            $minPathElement.datepicker('update', thisDateModify);
                        } else if (minDate > thisNewDate) {
                            $minPathElement.datepicker('update', thisDate);
                        }

                    } else {
                        if (minDate > thisNewDate) {
                            $minPathElement.datepicker('update', thisDate);
                        }
                    }
                }
            }
        }
    });
    
    $(document.body).on('click', '.button-list .dv-button-inline', function() {
        var $this = $(this), $parent = $this.closest('.button-list'), 
            valId = $this.attr('data-criteria');
        
        $parent.find('input[type="hidden"]').attr('data-row-data', $this.attr('data-row-data')).val(valId).trigger('change');
        
        $parent.find('.dv-button-inline-active').removeClass('dv-button-inline-active');
        $this.addClass('dv-button-inline-active');
    });
    
    $(document.body).on('click', '.wordeditor-iframe-fullscreen-btn', function() {
        var $this = $(this);
        var $parent = $this.closest('.wordeditor-iframe-parent');
        var $iframe = $parent.find('iframe');
        var $openDialog = $parent.closest('.ui-dialog');
        var $isDialog = ($openDialog.length) ? true : false;
        
        if (!$this.hasAttr('data-fullscreen')) {
            
            if ($isDialog) {
                $openDialog.css('overflow', 'inherit');
            }
        
            $this.attr({'data-fullscreen': '1', 'title': 'Restore'});
            $this.find('i').removeClass('fa-expand').addClass('fa-compress');
            $parent.addClass('wordeditor-iframe-fullscreen');
            $('html').css('overflow', 'hidden');
            
            $iframe.css('height', $(window).height());
            
        } else {
            
            if ($isDialog) {
                $openDialog.css('overflow', '');
            }
        
            $this.attr({'title': 'Fullscreen'}).removeAttr('data-fullscreen');
            $this.find('i').removeClass('fa-compress').addClass('fa-expand');
            $parent.removeClass('wordeditor-iframe-fullscreen');
            $('html').css('overflow', '');
            
            $iframe.css('height', $iframe.attr('data-default-height'));
        }
    });
    
    $(document.body).on('shown.bs.dropdown', '.bp-comment-workflow-btn', function() {
        
        var $elem = $(this), $this = $elem.find('a.dropdown-toggle'), 
            $parentRow = $this.closest('[data-comment-id]'), 
            $parent = $this.closest('[data-commentstructureid]'), 
            commentStructureId = $parent.attr('data-commentstructureid'),
            currentStatusId = $this.attr('data-current-status-id'), 
            row = {};
            
        row.id = $parentRow.attr('data-comment-id');
        row.wfmstatusid = currentStatusId;
        row.commenttypeid = 1;
        
        var $dropdownMenu = $this.next('.dropdown-menu');
        $dropdownMenu.empty();
        
        $.ajax({
            type: 'post', 
            url: 'mdobject/getWorkflowNextStatus', 
            data: {metaDataId: commentStructureId, dataRow: row}, 
            dataType: 'json',
            beforeSend: function() {
                Core.blockUI({message: 'Loading...', boxed: true});
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
                            
                            if (typeof v.advancedCriteria !== 'undefined' && v.advancedCriteria !== null) {
                                advancedCriteria = ' data-advanced-criteria="' + v.advancedCriteria.replace(/\"/g, '') + '"';
                            }
                            
                            if (typeof v.wfmstatusname != 'undefined' && typeof v.processname != 'undefined' && v.processname != '') {
                                v.wfmstatusname = v.processname;
                            }

                            if (typeof v.wfmusedescriptionwindow != 'undefined' && v.wfmusedescriptionwindow == '0' && typeof v.wfmuseprocesswindow != 'undefined' && v.wfmuseprocesswindow == '0') {
                                $dropdownMenu.append('<a href="javascript:;" ' + advancedCriteria + ' onclick="changeWfmStatusId(this, \''+v.wfmstatusid+'\', \''+commentStructureId+'\', \''+commentStructureId+'\', \''+$.trim(v.wfmstatuscolor)+'\', \''+v.wfmstatusname+'\');" id="'+ v.wfmstatusid +'" data-row-element-id="'+row.id+'" data-current-status-id="'+currentStatusId+'" class="dropdown-item">'+ v.wfmstatusname +'</a>'); 
                            } else {
                                if (typeof v.wfmstatusname != 'undefined' && v.wfmstatusname != '' && (v.wfmstatusprocessid == '' || v.wfmstatusprocessid == 'null' || v.wfmstatusprocessid == null)) {
                                    if (v.wfmisneedsign == '1') {
                                        $dropdownMenu.append('<a href="javascript:;" ' + advancedCriteria + ' onclick="beforeSignChangeWfmStatusId(this, \''+v.wfmstatusid+'\', \''+commentStructureId+'\', \''+commentStructureId+'\', \''+$.trim(v.wfmstatuscolor)+'\', \''+v.wfmstatusname+'\');" id="'+ v.wfmstatusid +'" class="dropdown-item">'+ v.wfmstatusname +' <i class="fa fa-key"></i></a>'); 
                                    } else if (v.wfmisneedsign == '2') {
                                        $dropdownMenu.append('<a href="javascript:;" ' + advancedCriteria + ' onclick="beforeHardSignChangeWfmStatusId(this, \''+v.wfmstatusid+'\', \''+commentStructureId+'\', \''+commentStructureId+'\', \''+$.trim(v.wfmstatuscolor)+'\', \''+v.wfmstatusname+'\');" id="'+ v.wfmstatusid +'" class="dropdown-item">'+ v.wfmstatusname +' <i class="fa fa-key"></i></a>'); 
                                    } else if (v.wfmisneedsign == '3') {
                                        $dropdownMenu.append('<a href="javascript:;" ' + advancedCriteria + ' onclick="cloudSignChangeWfmStatusId(this, \''+v.wfmstatusid+'\', \''+commentStructureId+'\', \''+commentStructureId+'\', \''+$.trim(v.wfmstatuscolor)+'\', \''+v.wfmstatusname+'\');" id="'+ v.wfmstatusid +'" class="dropdown-item">'+ v.wfmstatusname +' <i class="fa fa-key"></i></a>'); 
                                    } else {
                                        $dropdownMenu.append('<a href="javascript:;" ' + advancedCriteria + ' onclick="changeWfmStatusId(this, \''+v.wfmstatusid+'\', \''+commentStructureId+'\', \''+commentStructureId+'\', \''+$.trim(v.wfmstatuscolor)+'\', \''+v.wfmstatusname+'\');" id="'+ v.wfmstatusid +'" data-row-element-id="'+row.id+'" data-current-status-id="'+currentStatusId+'" class="dropdown-item">'+ v.wfmstatusname +'</a>'); 
                                    }
                                } /*else if (v.wfmstatusprocessid != '' || v.wfmstatusprocessid != 'null' || v.wfmstatusprocessid != null) {
                                    var wfmStatusCode = ('wfmstatuscode' in Object(v)) ? v.wfmstatuscode : ''; 
                                    if (v.wfmisneedsign == '1') {
                                        $dropdownMenu.append('<li><a href="javascript:;" ' + advancedCriteria + ' onclick="transferProcessAction(\'signProcess\', \'<?php echo $this->metaDataId ?>\', \''+v.wfmstatusprocessid+'\', \''+v.metatypeid+'\', \'toolbar\', this, {callerType: \'<?php echo $this->metaDataCode ?>\', isWorkFlow: true, wfmStatusId: \''+v.wfmstatusid+'\', wfmStatusCode: \''+wfmStatusCode+'\', wfmStatusName: \''+v.wfmstatusname+'\'}, \'dataViewId=<?php echo $this->metaDataId ?>&refStructureId=<?php echo $this->refStructureId; ?>&statusId='+v.wfmstatusid+'&statusName='+v.wfmstatusname+'&statusColor='+$.trim(v.wfmstatuscolor)+'&rowId='+rowId+'\');">'+v.wfmstatusname+' <i class="fa fa-key"></i></a></li>');
                                    } else if (v.wfmisneedsign == '2') {
                                        $dropdownMenu.append('<li><a href="javascript:;" ' + advancedCriteria + ' onclick="transferProcessAction(\'hardSignProcess\', \'<?php echo $this->metaDataId ?>\', \''+v.wfmstatusprocessid+'\', \''+v.metatypeid+'\', \'toolbar\', this, {callerType: \'<?php echo $this->metaDataCode ?>\', isWorkFlow: true, wfmStatusId: \''+v.wfmstatusid+'\', wfmStatusCode: \''+wfmStatusCode+'\', wfmStatusName: \''+v.wfmstatusname+'\'}, \'dataViewId=<?php echo $this->metaDataId ?>&refStructureId=<?php echo $this->refStructureId; ?>&statusId='+v.wfmstatusid+'&statusName='+v.wfmstatusname+'&statusColor='+$.trim(v.wfmstatuscolor)+'&rowId='+rowId+'\');">'+v.wfmstatusname+' <i class="fa fa-key"></i></a></li>');
                                    } else {
                                        $dropdownMenu.append('<li><a href="javascript:;" ' + advancedCriteria + ' onclick="transferProcessAction(\'\', \'<?php echo $this->metaDataId ?>\', \''+v.wfmstatusprocessid+'\', \''+v.metatypeid+'\', \'toolbar\', this, {callerType: \'<?php echo $this->metaDataCode ?>\', isWorkFlow: true, wfmStatusId: \''+v.wfmstatusid+'\', wfmStatusCode: \''+wfmStatusCode+'\', wfmStatusName: \''+v.wfmstatusname+'\'}, \'dataViewId=<?php echo $this->metaDataId ?>&refStructureId=<?php echo $this->refStructureId; ?>&statusId='+v.wfmstatusid+'&statusName='+v.wfmstatusname+'&statusColor='+$.trim(v.wfmstatuscolor)+'&rowId='+rowId+'\');">'+v.wfmstatusname+'</a></li>');
                                    }
                                }*/
                            }
                        });    
                    } 
                    
                    /*if (!isIgnoreWfmHistory_<?php echo $this->metaDataId; ?>) {
                        $dropdownMenu.append('<li><a href="javascript:;" onclick="seeWfmStatusForm(this, \'<?php echo $this->metaDataId ?>\');">'+plang.getDefault('wfm_log_history', 'Өөрчлөлтийн түүх харах')+'</a></li>');
                    }*/
                } else {
                    PNotify.removeAll();
                    new PNotify({
                        title: 'Error',
                        text: response.message,
                        type: response.status,
                        addclass: pnotifyPosition,
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
    
    $(document.body).on('shown.bs.dropdown', '.bp-rowdata-workflow-btn', function() {
        
        var $elem = $(this), $this = $elem.find('a.dropdown-toggle'), 
            commentStructureId = $this.attr('data-dv-id'),
            currentStatusId = $this.attr('data-current-status-id'), 
            row = {};
            
        row.id = $this.attr('data-id');
        row.wfmstatusid = currentStatusId;
        row.commenttypeid = 1;
        
        var $dropdownMenu = $this.next('.dropdown-menu');
        $dropdownMenu.empty();
        
        $.ajax({
            type: 'post', 
            url: 'mdobject/getWorkflowNextStatus', 
            data: {metaDataId: commentStructureId, dataRow: row}, 
            dataType: 'json',
            beforeSend: function() {
                Core.blockUI({message: 'Loading...', boxed: true});
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
                            
                            if (typeof v.advancedCriteria !== 'undefined' && v.advancedCriteria !== null) {
                                advancedCriteria = ' data-advanced-criteria="' + v.advancedCriteria.replace(/\"/g, '') + '"';
                            }
                            
                            if (typeof v.wfmstatusname != 'undefined' && typeof v.processname != 'undefined' && v.processname != '') {
                                v.wfmstatusname = v.processname;
                            }

                            if (typeof v.wfmusedescriptionwindow != 'undefined' && v.wfmusedescriptionwindow == '0' && typeof v.wfmuseprocesswindow != 'undefined' && v.wfmuseprocesswindow == '0') {
                                $dropdownMenu.append('<a href="javascript:;" ' + advancedCriteria + ' onclick="changeWfmStatusId(this, \''+v.wfmstatusid+'\', \''+commentStructureId+'\', \''+commentStructureId+'\', \''+$.trim(v.wfmstatuscolor)+'\', \''+v.wfmstatusname+'\');" id="'+ v.wfmstatusid +'" data-row-element-id="'+row.id+'" data-current-status-id="'+currentStatusId+'" class="dropdown-item">'+ v.wfmstatusname +'</a>'); 
                            } else {
                                if (typeof v.wfmstatusname != 'undefined' && v.wfmstatusname != '' && (v.wfmstatusprocessid == '' || v.wfmstatusprocessid == 'null' || v.wfmstatusprocessid == null)) {
                                    if (v.wfmisneedsign == '1') {
                                        $dropdownMenu.append('<a href="javascript:;" ' + advancedCriteria + ' onclick="beforeSignChangeWfmStatusId(this, \''+v.wfmstatusid+'\', \''+commentStructureId+'\', \''+commentStructureId+'\', \''+$.trim(v.wfmstatuscolor)+'\', \''+v.wfmstatusname+'\');" id="'+ v.wfmstatusid +'" class="dropdown-item">'+ v.wfmstatusname +' <i class="fa fa-key"></i></a>'); 
                                    } else if (v.wfmisneedsign == '2') {
                                        $dropdownMenu.append('<a href="javascript:;" ' + advancedCriteria + ' onclick="beforeHardSignChangeWfmStatusId(this, \''+v.wfmstatusid+'\', \''+commentStructureId+'\', \''+commentStructureId+'\', \''+$.trim(v.wfmstatuscolor)+'\', \''+v.wfmstatusname+'\');" id="'+ v.wfmstatusid +'" class="dropdown-item">'+ v.wfmstatusname +' <i class="fa fa-key"></i></a>'); 
                                    } else if (v.wfmisneedsign == '3') {
                                        $dropdownMenu.append('<a href="javascript:;" ' + advancedCriteria + ' onclick="cloudSignChangeWfmStatusId(this, \''+v.wfmstatusid+'\', \''+commentStructureId+'\', \''+commentStructureId+'\', \''+$.trim(v.wfmstatuscolor)+'\', \''+v.wfmstatusname+'\');" id="'+ v.wfmstatusid +'" class="dropdown-item">'+ v.wfmstatusname +' <i class="fa fa-key"></i></a>'); 
                                    } else {
                                        $dropdownMenu.append('<a href="javascript:;" ' + advancedCriteria + ' onclick="changeWfmStatusId(this, \''+v.wfmstatusid+'\', \''+commentStructureId+'\', \''+commentStructureId+'\', \''+$.trim(v.wfmstatuscolor)+'\', \''+v.wfmstatusname+'\');" id="'+ v.wfmstatusid +'" data-row-element-id="'+row.id+'" data-current-status-id="'+currentStatusId+'" class="dropdown-item">'+ v.wfmstatusname +'</a>'); 
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
                        addclass: pnotifyPosition,
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
    
    $(document.body).on('input', '.texteditor_clicktoeditInit[contenteditable="true"]', function(){
        var $this = $(this), $parent = $this.closest('.input-group');  
        $parent.find('textarea').val($this.html());
    });
    
    $(document.body).on('input', '.texteditor_clicktoedit_tinymceInit[contenteditable="true"]', function(){
        var $this = $(this), $parent = $this.closest('.input-group');  
        $parent.find('textarea').val($this.html());
    });
    
    $(document.body).on('focus', '[data-qtip-focus]', function() {
        var $this = $(this), cellText = $this.attr('data-qtip-focus').trim();
        $this.qtip({
            show: 'focus',
            hide: 'blur',
            content: {
                text: '<div style="max-width:600px;max-height:450px;overflow-y:auto;overflow-x:hidden;">' + cellText + '</div>'
            },
            style: {
                classes: 'qtip-bootstrap',
                tip: {
                    width: 10,
                    height: 5
                }
            }, 
            events: {
                hidden: function(event, api) {
                    api.destroy(true);
                }
            }
        });
    });
    
    $(document.body).on('change', 'input[type="checkbox"][data-field-name][data-length!=""]', function(){
        var $this = $(this), dataLength = Number($this.attr('data-length')), 
            $parent = $this.closest('.checkInit'), 
            checkedLength = $parent.find('input[type="checkbox"]:checked').length;
        
        if (checkedLength > dataLength) {
            $this.prop('checked', false);
            PNotify.removeAll();
            new PNotify({
                title: 'Info',
                text: plang.getVar('MSG_VALIDATE_MAXIMUM_OPTION', { length: dataLength }),
                type: 'info',
                addclass: pnotifyPosition,
                sticker: false
            });
        }
    });
});

function bpValueTemplateItem(data) {
    var list = [], 
        edit_btn_globe = plang.get('edit_btn'), 
        delete_btn_globe = plang.get('delete_btn'), 
        choose_btn_globe = plang.get('choose_btn');
    
    list.push('<li class="media" data-id="'+data.id+'">');
        list.push('<div class="mr-2">');
            
            if (data.selectedId == data.id) {
                list.push('<span class="bpv-row-action-active text-success" title="'+choose_btn_globe+'"><i class="icon-checkmark-circle"></i></span>');
            } else {
                list.push('<span class="bpv-row-action-active text-default" title="'+choose_btn_globe+'"><i class="icon-circle"></i></span>');
            }
            
        list.push('</div>');
        list.push('<div class="media-body">'+data.name+'</div>');
        
        if (data.isEdit == '1') {
            list.push('<div class="ml-1">');
                list.push('<button type="button" class="btn btn-outline bg-primary text-primary-800 btn-icon rounded-round bpv-row-action" data-action="edit" title="'+edit_btn_globe+'"><i class="icon-pencil7"></i></button>');
                list.push('<button type="button" class="btn btn-outline bg-pink-400 text-pink-800 btn-icon rounded-round bpv-row-action" data-action="delete" title="'+delete_btn_globe+'"><i class="icon-trash"></i></button>');
            list.push('</div>');
        }
        
    list.push('</li>');
    
    return list.join('');
}

function bpComparer(index, fieldType) {
    return function(a, b) {
        var valA = getBPCellValue(a, index, fieldType), valB = getBPCellValue(b, index, fieldType);
        return $.isNumeric(valA) && $.isNumeric(valB) ? valA - valB : valA.localeCompare(valB);
    };
}
function getBPCellValue(row, index, fieldType) {
    if (fieldType == 'number') {
        return $(row).children('td').eq(index).find("input[type=text]:first").autoNumeric('get');
    } else if (fieldType == 'checkbox') {
        return ($(row).children('td').eq(index).find("input[type=checkbox]:first").is(':checked') == true ? '1' : '0');
    } else if (fieldType == 'lookup') {
        return $(row).children('td').eq(index).find("input[type=text]:last").val();
    } else if (fieldType == 'lookup-code') {
        return $(row).children('td').eq(index).find("input[type=text]:first").val();
    } else if (fieldType == 'lookup-name') {
        return $(row).children('td').eq(index).find("input[type=text]:last").val();
    } else if (fieldType == 'label') {
        return $(row).children('td').eq(index).text();
    } else {
        var $cell = $(row).children('td').eq(index);
        var $input = $cell.find("input[type=text]:first");
        
        if ($input.length) {
            return $input.val();
        } else {
            return $cell.text();
        }
    }
}
function bpDetailFreezeAll(mainSelector) {
    var $getDetail = mainSelector.find('table.bprocess-table-dtl:not(.bprocess-table-subdtl, .kpi-dtl-table)');
    
    if ($getDetail.length) {
        $getDetail.each(function(){
            var $thisDetail = $(this); 
            bpDetailFreeze($thisDetail);
        });
    }
    return;
}
function bpDetailFreeze(detailElement) {
    if (detailElement.prop('tagName') == 'TABLE') {
        var $freezeElement = detailElement.closest('.bp-overflow-xy-auto');
        if (!$freezeElement.hasAttr('data-setexp-height') && !$freezeElement.hasAttr('data-full-screen') && !$freezeElement.hasAttr('data-old-maxheight')) {
            $freezeElement.css('max-height', '450px');
        }
        if (detailElement.hasClass('bp-dtl-tworightcolfreeze')) {
            detailElement.tableHeadFixer({'head': true, 'foot': true, 'setBgColor': false, 'left': 2, 'right': 2, 'z-index': 9}); 
        } else {
            detailElement.tableHeadFixer({'head': true, 'foot': true, 'setBgColor': false, 'left': 2, 'right': 1, 'z-index': 9}); 
        }
    }
    return;
}
function bpDetailFreezeNoLeft(detailElement) {
    var $freezeElement = detailElement.closest('.bp-overflow-xy-auto');
    if (!$freezeElement.hasAttr('data-full-screen')) {
        $freezeElement.css('max-height', '450px');
    }
    detailElement.tableHeadFixer({'head': true, 'foot': true, 'left': 0, 'z-index': 9}); 
    return;
}

function bpDetailPager(mainSelector, cacheId) {
    var $getDetail = mainSelector.find('table[data-pager="true"]');
    
    if ($getDetail.length) {
        
        var $processId = mainSelector.attr('data-process-id');
        var $uniqId = mainSelector.attr('data-bp-uniq-id');
        
        $getDetail.each(function(){
            var $thisDetail = $(this);
            var $path = $thisDetail.attr('data-table-path');
            var $freezeElement = mainSelector.find("div[data-parent-path='"+$path+"']");
            var $total = (typeof $thisDetail.attr('data-pager-total') !== 'undefined' ? $thisDetail.attr('data-pager-total') : 0);
            var $aggregateStr = (typeof $thisDetail.attr('data-pager-aggregate') !== 'undefined' ? $thisDetail.attr('data-pager-aggregate') : '');
            var $pageSize = (typeof $thisDetail.attr('data-pager-default-size') !== 'undefined' ? $thisDetail.attr('data-pager-default-size') : 20); 
            
            bpDetailPagerSetTool($freezeElement, $path, $uniqId, $processId, cacheId, $total, $pageSize);
            bpDetailPagerSetFooterAmount($thisDetail, $aggregateStr);
        });
    }
    return;
}
function bpDetailPagerSetTool(detailElement, groupPath, uniqId, processId, cacheId, total, pageSize) {

    var pageNumber = Math.ceil(total / pageSize) || 1;
                        
    var html = '<div class="pf-bp-pager-tool" data-pg-pagesize="'+pageSize+'" data-pg-grouppath="'+groupPath+'" data-pg-uniqid="'+uniqId+'" data-pg-processid="'+processId+'" data-pg-cacheid="'+cacheId+'">'+
        '<div class="pf-bp-pager-buttons">'+
            '<select class="pf-bp-pager-page-list" onchange="bpDetailPagerListChange(this);"><option value="20">20</option><option value="30">30</option><option value="40">40</option><option value="50">50</option><option value="60">60</option><option value="70">70</option><option value="80">80</option><option value="90">90</option><option value="100">100</option></select>'+
            '<div class="pf-bp-pager-separator"></div>'+
            '<button type="button" class="pf-bp-pager-last-prev pf-bp-pager-disabled" onclick="bpDetailPagerLastPrev(this);">'+
                '<span></span>'+
            '</button>'+
            '<button type="button" class="pf-bp-pager-prev pf-bp-pager-disabled" onclick="bpDetailPagerPrev(this);">'+
                '<span></span>'+
            '</button>'+
            '<div class="pf-bp-pager-separator"></div>'+
            '<div class="pf-bp-pager-page-info">Хуудас <span><input type="text" size="2" value="1" data-gotopage="1" class="integerInit" onkeydown="if(event.keyCode==13) bpDetailPagerEnter(this);"></span> of <span data-pagenumber="1">'+pageNumber+'</span></div>'+
            '<div class="pf-bp-pager-separator"></div>'+
            '<button type="button" class="pf-bp-pager-next pf-bp-pager-disabled" onclick="bpDetailPagerNext(this);">'+
                '<span></span>'+
            '</button>'+
            '<button type="button" class="pf-bp-pager-last-next pf-bp-pager-disabled" onclick="bpDetailPagerLastNext(this);">'+
                '<span></span>'+
            '</button>'+
            '<div class="pf-bp-pager-separator"></div>'+
            '<button type="button" class="pf-bp-pager-refresh pf-bp-pager-disabled" onclick="bpDetailPagerRefresh(this);">'+
                '<span></span>'+
            '</button>'+
        '</div>'+
        '<div class="pf-bp-pager-total">Нийт <span>'+total+'</span> байна.</div>'+
    '</div>';
    
    detailElement.after(html);
    var $pagerElement = detailElement.next('.pf-bp-pager-tool:eq(0)');
    
    $pagerElement.find('select.pf-bp-pager-page-list').val(pageSize);
    
    if (Number(total) > Number(pageSize)) {
        $pagerElement.find('.pf-bp-pager-next, .pf-bp-pager-last-next, .pf-bp-pager-refresh').removeClass('pf-bp-pager-disabled');
    } else {
        $pagerElement.find('.pf-bp-pager-prev, .pf-bp-pager-last-prev, .pf-bp-pager-next, .pf-bp-pager-last-next, .pf-bp-pager-refresh').addClass('pf-bp-pager-disabled');
    }
    
    return;
}
function bpDetailPagerSetFooterAmount($tableElement, $aggregateStr) {
    
    if ($aggregateStr) {
        
        $tableElement.attr('data-pager-aggregate', $aggregateStr);
        
        var aggregateObj = qryStrToObj($aggregateStr);
        var $footElement = $tableElement.find('tfoot');
        
        Core.initNumberInput($footElement);
        
        for (var key in aggregateObj) {
            $footElement.find('td[data-cell-path="'+key+'"]').autoNumeric('set', aggregateObj[key]);
        }
    }

    return;
}
function bpDetailPagerNext(elem) {
    var $this = $(elem);
    
    if (!$this.hasClass('pf-bp-pager-disabled')) {
        var $pagerElement = $this.closest('.pf-bp-pager-tool');
        var $currentPageNumber = Number($pagerElement.find('input[data-gotopage]').val());

        bpDetailPagerGotoPage($this, $pagerElement, $currentPageNumber + 1);
    }
    return;
}
function bpDetailPagerNextTrigger(elem) {
    var $pagerElement = elem.closest('div[data-parent-path]').next('.pf-bp-pager-tool');
    
    if (!$pagerElement.hasAttr('data-go-pager')) {
        
        var $nextButton = $pagerElement.find('.pf-bp-pager-next');
    
        if (!$nextButton.hasClass('pf-bp-pager-disabled')) {
            var $currentPageNumber = Number($pagerElement.find('input[data-gotopage]').val());
            $pagerElement.attr('data-go-pager', '1');
            bpDetailPagerGotoPage($nextButton, $pagerElement, $currentPageNumber + 1);
        }
    }
    return;
}
function bpDetailPagerIsNextButtonActive(elem) {
    var $pagerElement = elem.closest('div[data-parent-path]').next('.pf-bp-pager-tool');
    var $nextButton = $pagerElement.find('.pf-bp-pager-next');
    return !$nextButton.hasClass('pf-bp-pager-disabled');
}
function bpDetailPagerLastPrev(elem) {
    var $this = $(elem);
    
    if (!$this.hasClass('pf-bp-pager-disabled')) {
        var $pagerElement = $this.closest('.pf-bp-pager-tool');

        bpDetailPagerGotoPage($this, $pagerElement, 1);
    }
    return;
}
function bpDetailPagerPrev(elem) {    
    var $this = $(elem);
    
    if (!$this.hasClass('pf-bp-pager-disabled')) {
        var $pagerElement = $this.closest('.pf-bp-pager-tool');
        var $currentPageNumber = Number($pagerElement.find('input[data-gotopage]').val());

        bpDetailPagerGotoPage($this, $pagerElement, $currentPageNumber - 1);
    }
    return;
}
function bpDetailPagerPrevTrigger(elem) {
    var $pagerElement = elem.closest('div[data-parent-path]').next('.pf-bp-pager-tool');
    
    if (!$pagerElement.hasAttr('data-go-pager')) {
        
        var $prevButton = $pagerElement.find('.pf-bp-pager-prev');

        if (!$prevButton.hasClass('pf-bp-pager-disabled')) {
            var $currentPageNumber = Number($pagerElement.find('input[data-gotopage]').val());
            $pagerElement.attr('data-go-pager', '1');
            bpDetailPagerGotoPage($prevButton, $pagerElement, $currentPageNumber - 1);
        }
    }
    return;
}
function bpDetailPagerIsPrevButtonActive(elem) {
    var $pagerElement = elem.closest('div[data-parent-path]').next('.pf-bp-pager-tool');
    var $prevButton = $pagerElement.find('.pf-bp-pager-prev');
    return !$prevButton.hasClass('pf-bp-pager-disabled');
}
function bpDetailPagerLastNext(elem) {  
    var $this = $(elem);
    
    if (!$this.hasClass('pf-bp-pager-disabled')) {
        var $pagerElement = $this.closest('.pf-bp-pager-tool');
        var $totalPageNumber = Number($pagerElement.find('span[data-pagenumber]').text());

        bpDetailPagerGotoPage($this, $pagerElement, $totalPageNumber);
    }
    return;
}
function bpDetailPagerRefresh(elem) {  
    var $this = $(elem);
    var $pagerElement = $this.closest('.pf-bp-pager-tool');
    var $currentPageNumber = Number($pagerElement.find('input[data-gotopage]').val());

    bpDetailPagerGotoPage($this, $pagerElement, $currentPageNumber);
    
    return;
}
function bpDetailPagerEnter(elem) {
    
    var $this = $(elem);
    var $pagerElement = $this.closest('.pf-bp-pager-tool');

    var $currentPageNumber = Number($pagerElement.find('input[data-gotopage]').val());
    var $totalPageNumber = Number($pagerElement.find('span[data-pagenumber]').text());

    if ($currentPageNumber === 0) {
        $currentPageNumber = 1;
    } else if ($currentPageNumber > $totalPageNumber) {
        $currentPageNumber = $totalPageNumber;
    }

    bpDetailPagerGotoPage($this, $pagerElement, $currentPageNumber);
            
    return;
}
function bpDetailPagerListChange(elem) {
    var $this = $(elem);
    var $pagerElement = $this.closest('.pf-bp-pager-tool');
    var $currentPageNumber = Number($pagerElement.find('input[data-gotopage]').val());
    
    $pagerElement.attr('data-pg-pagesize', $this.val());
    
    bpDetailPagerGotoPage($this, $pagerElement, $currentPageNumber);
    
    return;
}
var isAsyncCacheGrid = true;
function bpDetailPagerGotoPage(elem, $pagerElement, $pageNumber) {
    
    var $groupPath = $pagerElement.attr('data-pg-grouppath'), 
        $uniqId = $pagerElement.attr('data-pg-uniqid'), 
        $processId = $pagerElement.attr('data-pg-processid'), 
        $cacheId = $pagerElement.attr('data-pg-cacheid'), 
        $pageSize = $pagerElement.attr('data-pg-pagesize'), 
        $freezeElement = $pagerElement.prev("div[data-parent-path='"+$groupPath+"']:eq(0)"), 
        $detailElement = $freezeElement.find("table[data-table-path='"+$groupPath+"']"), 
        $filterRules = '', $sortField = '', $orderType = ''; 
    
    $detailElement.find('> thead > tr > th input').each(function(){
        var $input = $(this), $value = $input.val().trim();

        if ($value !== '') {
            
            var $filteringInput = $input.attr('data-type-code').toLowerCase(), 
                $filteringInputPath = $input.attr('data-path-code').toLowerCase(), 
                lookupType = $input.attr('data-lookup-type'), 
                $condition = 'like', $type = 'string';
            
            if ($filteringInput == 'popup-code') {
                $condition = 'like';
                $type = 'lookup-code';
            } else if ($filteringInput == 'popup-name' || lookupType == 'combo') {
                $condition = 'like';
                $type = 'lookup-name';
            } else if ($filteringInput == 'bigdecimal') {
                $condition = 'like';
                $type = 'bigdecimal';
            } else if ($filteringInput == 'date') {
                $condition = 'like';
                $type = 'date';
            } else if ($filteringInput == 'datetime') {
                $condition = 'like';
                $type = 'datetime';
            }

            $filterRules += '{"field":"'+$filteringInputPath+'","op":"'+$condition+'","value":"'+$value+'","type":"'+$type+'"},';
        }
    });
    
    $detailElement.find('> thead > tr > th > select').each(function(){
        var $input = $(this), $value = $input.val();
        if ($value !== 'all') {
            $filterRules += '{"field":"'+$input.closest('th').attr('data-cell-path').toLowerCase()+'","op":"=","value":"'+$value+'","type":"boolean"},';
        }
    });
    
    if ($filterRules) {
        $filterRules = rtrim($filterRules, ',');
        $filterRules = '['+$filterRules+']';
    }
    
    $detailElement.find('> thead > tr > th.bp-head-sort-asc, thead:eq(0) > tr > th.bp-head-sort-desc').each(function(){
        var $thisSort = $(this);
        $sortField = $thisSort.attr('data-cell-path').toLowerCase();
        
        if ($thisSort.hasClass('bp-head-sort-asc')) {
            $orderType = 'ASC';
        } else {
            $orderType = 'DESC';
        }
    });
    
    var $reloadFooter = 1;
    
    var postData = {
        processId: $processId, 
        uniqId: $uniqId, 
        cacheId: $cacheId, 
        groupPath: $groupPath, 
        page: $pageNumber, 
        rows: $pageSize, 
        filterRules: $filterRules, 
        sort: $sortField, 
        order: $orderType, 
        reloadFooter: $reloadFooter
    };
    
    if (!elem.hasAttr('data-ignore-modify')) {
        postData['params'] = $detailElement.find('input, select, textarea').serialize();
        
        var $lookupInputs = $detailElement.find('input.popupInit, select.select2');
        var $lookupInputsLen = $lookupInputs.length, $n = 0;
        var objs = {}, rowObj = {};
                
        for ($n; $n < $lookupInputsLen; $n++) { 
            
            var $lookupInput = $($lookupInputs[$n]);
            var $id = $lookupInput.val();
            
            if ($id != '') {
                
                var $row = $lookupInput.parents('tr'), rowObj = {}, 
                    $rowId = $row.find('input[name*=".mainRowCount"]').val(), 
                    $getPath = $lookupInput.attr('data-path');
                
                if ($lookupInput.hasClass('popupInit')) {
                    
                    var $parent = $lookupInput.closest('.double-between-input'), 
                        $code = $parent.find('input[id*="_displayField"]').val(), 
                        $name = $parent.find('input[id*="_nameField"]').val(), 
                        $rowData = $lookupInput.attr('data-row-data');
                
                } else {
                    var $selected = $lookupInput.find('option:selected'), 
                        $code = $selected.text(), 
                        $name = $code, 
                        $rowData = $selected.attr('data-row-data');
                }
                
                rowObj['rowId'] = $rowId;
                rowObj['path'] = $getPath.toLowerCase();
                
                rowObj['id'] = $id;
                rowObj['code'] = $code;
                rowObj['name'] = $name;
                
                if ($rowData !== '') {
                    if (typeof $rowData !== 'object') {
                        rowObj['rowdata'] = JSON.parse(html_entity_decode($rowData, 'ENT_QUOTES'));
                    } else {
                        rowObj['rowdata'] = $rowData;
                    }
                } else {
                    rowObj['rowdata'] = '';
                }
                
                objs[$n] = rowObj;
            }
        }
        
        postData['lookupParams'] = objs;
    }
    
    $.ajax({
        type: 'post',
        url: 'mdcache/detailPagerFillRows', 
        data: postData, 
        dataType: 'json', 
        async: isAsyncCacheGrid, 
        beforeSend: function() {
            Core.blockUI({boxed : true, message: 'Loading...'});  
        }, 
        success: function (data) {

            if (data.hasOwnProperty('gridBodyData')) {
                
                var $detailElementBody = $detailElement.find('> tbody');
                
                $detailElementBody[0].innerHTML = data.gridBodyData;
                
                $detailElementBody.promise().done(function() {
                    
                    Core.initBPDtlInputType($detailElementBody);
                    
                    var $rowNumEl = $detailElementBody.find('> tr');
                    var $rowNumLen = $rowNumEl.length, $ni = 0, $k = 0;

                    for ($ni; $ni < $rowNumLen; $ni++) { 
                        $k = $ni + 1;
                        $k += ($pageNumber - 1) * $pageSize;
                        $($rowNumEl[$ni]).find('td:first > span').text($k);
                    }
                    
                    bpDetailPagerSetFooterAmount($detailElement, data.aggregateStr);
                    
                    var $totalRowNumber = data.total;
                    
                    if (data.hasOwnProperty('pageNumber')) {
                        var $pageNumbers = data.pageNumber;
                        $pageNumber = $pageNumbers;
                        var $currentPageNumber = Number($pageNumber);
                    } else {
                        var $pageNumbers = Math.ceil($totalRowNumber / $pageSize) || 1;
                        var $currentPageNumber = Number($pagerElement.find('span[data-pagenumber]').text());
                    }

                    $pagerElement.find('.pf-bp-pager-total > span').text($totalRowNumber);
                    $pagerElement.find('input[data-gotopage]').val($pageNumber);
                    $pagerElement.find('span[data-pagenumber]').text($pageNumbers);
                    
                    window['bpFullScriptsWithoutEvent_'+$uniqId](undefined, $groupPath, false, true, 'pager');
                    
                    if ($freezeElement.hasAttr('data-full-screen')) {    
                        $detailElement.tableHeadFixer({'left': 2, 'z-index': 9}); 
                    } else {
                        var tblHeight = $(window).height() - $detailElement.offset().top - 60;
                        if (tblHeight > 450) {
                            $freezeElement.css('height', tblHeight+'px');
                            $freezeElement.css('max-height', tblHeight+'px');
                        } else {
                            $freezeElement.css('max-height', '450px');
                        }
                        $detailElement.tableHeadFixer({'head': true, 'foot': true, 'setBgColor': false, 'left': 2, 'right': 1, 'z-index': 9}); 
                        $freezeElement.trigger('scroll');
                    }
                    
                    bpDetailPagerToolbarVisibler($pagerElement, $currentPageNumber, $pageNumber, $pageNumbers);
                    
                    $($rowNumEl[0]).find('input:not([data-isdisabled], [readonly="readonly"], [readonly], readonly, [disabled="disabled"], [disabled], disabled, input.meta-name-autocomplete):visible:first').focus().select();
                });
                
                bpDetailHideShowFields($detailElement);  
                
            } else if (data.hasOwnProperty('status') && data.status === 'error') {
                
                PNotify.removeAll();
                new PNotify({
                    title: data.status,
                    text: data.message,
                    type: data.status,
                    sticker: false
                });
            }
            
            $pagerElement.removeAttr('data-go-pager');
            
            Core.unblockUI();
        }
    });
    
    return;
}
function bpDetailPagerToolbarVisibler($pagerElement, $currentPageNumber, $pageNumber, $pageNumbers) {
    
    if ($currentPageNumber == 1) {
        
        if ($pageNumber == 1 && $pageNumbers > 1) {
            $pagerElement.find('.pf-bp-pager-prev, .pf-bp-pager-last-prev').addClass('pf-bp-pager-disabled');
            $pagerElement.find('.pf-bp-pager-next, .pf-bp-pager-last-next, .pf-bp-pager-refresh').removeClass('pf-bp-pager-disabled');
        } else {
            $pagerElement.find('.pf-bp-pager-prev, .pf-bp-pager-last-prev, .pf-bp-pager-next, .pf-bp-pager-last-next').addClass('pf-bp-pager-disabled');
            $pagerElement.find('.pf-bp-pager-refresh').removeClass('pf-bp-pager-disabled');
        }
        
    } else {

        if ($pageNumber == $currentPageNumber) {
            $pagerElement.find('.pf-bp-pager-prev, .pf-bp-pager-last-prev, .pf-bp-pager-refresh').removeClass('pf-bp-pager-disabled');
            $pagerElement.find('.pf-bp-pager-next, .pf-bp-pager-last-next').addClass('pf-bp-pager-disabled');
        } else if ($pageNumber == 1 && $pageNumbers == 1) {
            $pagerElement.find('.pf-bp-pager-prev, .pf-bp-pager-last-prev, .pf-bp-pager-next, .pf-bp-pager-last-next').addClass('pf-bp-pager-disabled');
            $pagerElement.find('.pf-bp-pager-refresh').removeClass('pf-bp-pager-disabled');
        } else if ($pageNumber == 1) {
            $pagerElement.find('.pf-bp-pager-prev, .pf-bp-pager-last-prev').addClass('pf-bp-pager-disabled');
            $pagerElement.find('.pf-bp-pager-next, .pf-bp-pager-last-next, .pf-bp-pager-refresh').removeClass('pf-bp-pager-disabled');
        } else {
            $pagerElement.find('.pf-bp-pager-prev, .pf-bp-pager-last-prev, .pf-bp-pager-next, .pf-bp-pager-last-next, .pf-bp-pager-refresh').removeClass('pf-bp-pager-disabled');
        }
    }
    return;
}
function bpDetailPagerRowRemoveConfirm($processForm, $parentTbl, $parentRow, $this) {
    var $dialogName = 'dialog-detail-remove-confirm';
        
    if (!$("#" + $dialogName).length) {
        $('<div id="' + $dialogName + '"></div>').appendTo('body');
        
        $.ajax({
            type: 'post',
            url: 'mdcommon/rowRemoveConfirm',
            dataType: 'json',
            async: false, 
            success: function (data) {
                $("#" + $dialogName).empty().append(data.Html);
            },
            error: function () {
                alert("Error");
            }
        });
    }
    
    var $dialog = $('#' + $dialogName);
    
    $parentRow.addClass('removed-tr');
    
    $dialog.dialog({
        cache: false,
        resizable: false,
        bgiframe: true,
        autoOpen: false,
        title: plang.get('msg_title_confirm'),
        width: 330,
        height: 'auto',
        modal: true,
        buttons: [
            {text: plang.get('yes_btn'), class: 'btn green-meadow btn-sm', click: function () {
                
                $dialog.dialog('close');
                
                var $groupPath = $parentTbl.attr('data-table-path');
                var $isIgnoreRemovedRowState = $parentRow.find('input[data-path="'+$groupPath+'.isIgnoreRemovedRowState"]').length;
                
                $.ajax({
                    type: 'post',
                    url: 'mdcache/deleteRow', 
                    data: {
                        processId: $processForm.parent().attr('data-process-id'), 
                        cacheId: $parentTbl.attr('data-cacheid'), 
                        groupPath: $groupPath, 
                        rowIndex: $parentRow.find('input[name*=".mainRowCount]"]').val(), 
                        isIgnoreRemovedRowState: $isIgnoreRemovedRowState
                    },
                    dataType: 'json', 
                    beforeSend: function() {
                        Core.blockUI({boxed : true, message: 'Loading...'});  
                    }, 
                    success: function (data) {
                        
                        if (data.status == 'success') {
                            
                            $parentRow.remove();
                            $processForm.find('[data-pg-grouppath="'+$groupPath+'"] .pf-bp-pager-refresh').click(); 
                            bpDetailPagerSetFooterAmount($parentTbl, data.aggregateStr);
                            
                        } else {
                            Core.unblockUI();
                            
                            PNotify.removeAll();
                            new PNotify({
                                title: data.status,
                                text: data.message,
                                type: data.status,
                                sticker: false
                            });
                        }
                    }
                });
                
            }},
            {text: plang.get('no_btn'), class: 'btn blue-madison btn-sm', click: function () {
                $parentRow.removeClass('removed-tr'); 
                $dialog.dialog('close');
            }}
        ]
    });
    $dialog.dialog('open');
    bpSoundPlay('ring');
    
    return;
}
function bpDetailPagerRowRemove($processForm, $parentTbl, $parentRow, $this) {
    var $groupPath = $parentTbl.attr('data-table-path');
                
    $.ajax({
        type: 'post',
        url: 'mdcache/deleteRow', 
        data: {
            processId: $processForm.parent().attr('data-process-id'), 
            cacheId: $parentTbl.attr('data-cacheid'), 
            groupPath: $groupPath, 
            rowIndex: $parentRow.find('input[name*=".mainRowCount]"]').val(), 
            cacheRemove: 1 
        },
        dataType: 'json', 
        async: false, 
        beforeSend: function() {
            Core.blockUI({boxed : true, message: 'Loading...'});  
        }, 
        success: function (data) {

            if (data.status == 'success') {
                
                $parentRow.remove();
                $processForm.find('[data-pg-grouppath="'+$groupPath+'"] .pf-bp-pager-refresh').click(); 
                bpDetailPagerSetFooterAmount($parentTbl, data.aggregateStr);
                
            } else {
                Core.unblockUI();
                
                PNotify.removeAll();
                new PNotify({
                    title: data.status,
                    text: data.message,
                    type: data.status,
                    sticker: false
                });
            }
        }
    });
}
function bpDetailPagerRefreshNavigationBar($pagerElement, $total, $pageNumber) {
    
    var $pageSize = Number($pagerElement.attr('data-pg-pagesize'));
    var $totalRowNumber = $total;
    var $pageNumbers = Math.ceil($totalRowNumber / $pageSize) || 1;

    $pagerElement.find('.pf-bp-pager-total > span').text($totalRowNumber);
    $pagerElement.find('input[data-gotopage]').val($pageNumber);
    $pagerElement.find('span[data-pagenumber]').text($pageNumbers);
    
    var $currentPageNumber = Number($pagerElement.find('span[data-pagenumber]').text());

    if ($currentPageNumber == 1) {
        $pagerElement.find('.pf-bp-pager-prev, .pf-bp-pager-last-prev, .pf-bp-pager-next, .pf-bp-pager-last-next').addClass('pf-bp-pager-disabled');
        $pagerElement.find('.pf-bp-pager-refresh').removeClass('pf-bp-pager-disabled');
    } else {

        if ($pageNumber == $currentPageNumber) {
            $pagerElement.find('.pf-bp-pager-prev, .pf-bp-pager-last-prev, .pf-bp-pager-refresh').removeClass('pf-bp-pager-disabled');
            $pagerElement.find('.pf-bp-pager-next, .pf-bp-pager-last-next').addClass('pf-bp-pager-disabled');
        } else if ($pageNumber == 1 && $pageNumbers == 1) {
            $pagerElement.find('.pf-bp-pager-prev, .pf-bp-pager-last-prev, .pf-bp-pager-next, .pf-bp-pager-last-next').addClass('pf-bp-pager-disabled');
            $pagerElement.find('.pf-bp-pager-refresh').removeClass('pf-bp-pager-disabled');
        } else if ($pageNumber == 1) {
            $pagerElement.find('.pf-bp-pager-prev, .pf-bp-pager-last-prev').addClass('pf-bp-pager-disabled');
            $pagerElement.find('.pf-bp-pager-next, .pf-bp-pager-last-next, .pf-bp-pager-refresh').removeClass('pf-bp-pager-disabled');
        } else {
            $pagerElement.find('.pf-bp-pager-prev, .pf-bp-pager-last-prev, .pf-bp-pager-next, .pf-bp-pager-last-next, .pf-bp-pager-refresh').removeClass('pf-bp-pager-disabled');
        }
    }
    
    return;
}
function bpSidebarView(windowId, elem) {
    var $parentCell = $(elem).parent();
    var $dialogName = 'dialog-bp-sidebar';
    
    if (!$("#" + $dialogName).length) {
        $('<div id="' + $dialogName + '"></div>').appendTo('body');
    }

    var $dialog = $('#' + $dialogName);

    $dialog.empty().append($parentCell.find('.sidebarDetailSection').clone());
    $dialog.find('.sidebarDetailSection').removeClass('hide');
    
    $dialog.dialog({
        cache: false,
        resizable: true,
        bgiframe: true,
        autoOpen: false,
        title: 'More',
        width: 550,
        height: 'auto',
        maxHeight: 650,
        modal: true,
        buttons: [
            {text: plang.get('close_btn'), class: 'btn blue-madison btn-sm', click: function () {
                $dialog.dialog('close');
            }}
        ]
    }).dialogExtend({
        "closable": true,
        "maximizable": true,
        "minimizable": false,
        "collapsable": false,
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
    $dialog.dialog('open');
    Core.initBPInputType($dialog);
}
function bpRowsView(elem) {
    var $parentCell = $(elem).parent();
    var $dialogName = 'dialog-bp-rows';
    
    if (!$("#" + $dialogName).length) {
        $('<div id="' + $dialogName + '"></div>').appendTo('body');
    }

    var $dialog = $('#' + $dialogName);

    $dialog.empty().append($parentCell.find('.param-tree-container-tab').clone());
    $dialog.find('.param-tree-container-tab').removeClass('hide');
    
    $dialog.dialog({
        cache: false,
        resizable: true,
        bgiframe: true,
        autoOpen: false,
        title: 'More',
        width: 750,
        height: 'auto',
        maxHeight: 700,
        modal: true,
        buttons: [
            {text: plang.get('close_btn'), class: 'btn blue-madison btn-sm', click: function () {
                $dialog.dialog('close');
            }}
        ]
    }).dialogExtend({
        "closable": true,
        "maximizable": true,
        "minimizable": false,
        "collapsable": false,
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
    $dialog.dialog('open');
}
function dvFilterDateCheckInterval(mainSelector) {
    var $filterStartDate = mainSelector.find('input[data-path="filterStartDate"], input[data-path="startDate"], input[data-path="startdate"]');
    var $filterEndDate = mainSelector.find('input[data-path="filterEndDate"], input[data-path="endDate"], input[data-path="enddate"]');
    
    if ($filterStartDate.length && $filterEndDate.length) {
        
        $filterStartDate.on('changeDate', function(){
            
            if ($filterStartDate.val() != '' && $filterEndDate.val() != '') {
                var $thisStartDateVal = new Date($filterStartDate.val());
                var $thisEndDateVal = new Date($filterEndDate.val());

                if ($thisStartDateVal.getTime() > $thisEndDateVal.getTime()) {
                    $filterEndDate.datepicker('update', $filterStartDate.val());
                }
            }
        });
        
        $filterEndDate.on('changeDate', function(){
            
            if ($filterStartDate.val() != '' && $filterEndDate.val() != '') {
                var $thisStartDateVal = new Date($filterStartDate.val());
                var $thisEndDateVal = new Date($filterEndDate.val());

                if ($thisStartDateVal.getTime() > $thisEndDateVal.getTime()) {
                    $filterStartDate.datepicker('update', $filterEndDate.val());
                }
            }
        });
    }
    
    var $filterStartDateTime = mainSelector.find('input[data-path="startDateTime"]');
    var $filterEndDateTime = mainSelector.find('input[data-path="endDateTime"]');
    
    if ($filterStartDateTime.length && $filterEndDateTime.length) {
        
        $filterStartDateTime.on('changeDate', function(){
            
            if ($filterStartDateTime.val() != '' && $filterEndDateTime.val() != '') {
                var $thisStartDateTimeVal = new Date($filterStartDateTime.val());
                var $thisEndDateTimeVal = new Date($filterEndDateTime.val());

                if ($thisStartDateTimeVal.getTime() > $thisEndDateTimeVal.getTime()) {
                    $filterEndDateTime.datetimepicker('update', $filterStartDateTime.val());
                }
            }
        });
        
        $filterEndDateTime.on('changeDate', function(){
            
            if ($filterStartDateTime.val() != '' && $filterEndDateTime.val() != '') {
                var $thisStartDateTimeVal = new Date($filterStartDateTime.val());
                var $thisEndDateTimeVal = new Date($filterEndDateTime.val());

                if ($thisStartDateTimeVal.getTime() > $thisEndDateTimeVal.getTime()) {
                    $filterStartDateTime.datetimepicker('update', $filterEndDateTime.val());
                }
            }
        });
    }
    
    return;
}
function statementHeaderFreeze($statementWindow, freezeNumber) {
    var $table = $statementWindow.find('table:has(thead):not(.floatThead-table, .no-freeze):eq(0)');    
    
    if ($table.length) {
        var $reportDialog = $table.closest('.ui-dialog-content');

        if ($reportDialog.length) {
            $table.floatThead({
                position: 'fixed', 
                scrollContainer: function($table) {
                    return $reportDialog;
                }
            });
        } else {
            var $reportContent = $table.closest('.ea-content');
            
            if ($reportContent.length) {
                $table.floatThead({
                    position: 'fixed', 
                    scrollContainer: function($table) {
                        return $reportContent;
                    }
                });
            } else {
                $table.floatThead({position: 'absolute', zIndex: 97, top: $('.system-header').height()});
                if (typeof freezeNumber !== 'undefined') {
                    $table.stickyColumn({ columns: parseInt(freezeNumber, 10) });
//                    setTimeout(function() {
//                        $statementWindow.find('.sticky_column_statetment:has(thead):not(.floatThead-table, .no-freeze):eq(0)').floatThead({position: 'fixed', zIndex: 100, top: $('.system-header').height()})
//                    }, 300);
                }
            }
        }
    }
    return;
}
function statementHeaderFreezeReflow($statementWindow) {
    var $table = $('table:has(thead):not(.floatThead-table, .no-freeze):eq(0)', $statementWindow);
    
    if ($table.length) {
        
        $table.floatThead('destroy');
        $table.find('colgroup').remove();
        
        var $reportDialog = $table.closest('.ui-dialog-content');

        if ($reportDialog.length) {
            $table.floatThead({
                position: 'fixed', 
                scrollContainer: function($table) {
                    return $reportDialog;
                }
            });
        } else {
            var $reportContent = $table.closest('.ea-content');
            
            if ($reportContent.length) {
                $table.floatThead({
                    position: 'fixed', 
                    scrollContainer: function($table) {
                        return $reportContent;
                    }
                });
            } else {
                $table.floatThead({position: 'fixed', zIndex: 97, top: $('.system-header').height()});
            }
        }
    }
    return;
}
function statementHeaderFreezeDestroy($statementWindow) {
    var $table = $('table:has(thead):not(.floatThead-table, .no-freeze):eq(0)', $statementWindow);
    
    if ($table.length) {
        $table.floatThead('destroy');
    }
    return;
}
function bpDetailUserOption(elem, uniqId) {
    var $this = $(elem);
    var $form = $this.closest('div[data-bp-uniq-id]');
    var $parent = $this.closest('div[data-section-path]');
    var $groupPath = $parent.attr('data-section-path');
    var $parentTable = $form.find('table[data-table-path="'+$groupPath+'"]');
    var $parentId = $parentTable.attr('data-row-id');
    var $dialogName = 'dialog-bp-user-config'; 
    
    if (!$($dialogName).length) {
        $('<div id="' + $dialogName + '"></div>').appendTo('body');
    }
    var $dialog = $('#' + $dialogName);

    $.ajax({
        type: 'post',
        url: 'mdwebservice/detailUserOption',
        data: {
            processId: $form.attr('data-process-id'), 
            parentId: $parentId, 
            groupPath: $groupPath
        },
        dataType: 'json',
        beforeSend: function() {
            Core.blockUI({message: 'Loading...', boxed: true});
        },
        success: function(data) {
            $dialog.empty().append(data.html);
            $dialog.dialog({
                cache: false,
                resizable: true,
                bgiframe: true,
                autoOpen: false,
                title: plang.get('META_00112'),
                width: 400,
                height: 'auto',
                modal: true, 
                closeOnEscape: isCloseOnEscape, 
                close: function() {
                    $dialog.empty().dialog('destroy').remove();
                },
                buttons: [
                    {text: plang.get('save_btn'), class: 'btn btn-sm green-meadow', click: function() {
                        $.ajax({
                            type: 'post',
                            url: 'mdwebservice/detailUserOptionSave', 
                            data: $dialog.find('form').serialize(),
                            dataType: 'json', 
                            beforeSend: function() {
                                Core.blockUI({boxed : true, message: 'Loading...'});  
                            }, 
                            success: function (dataSub) {
                                
                                if (!$.isEmptyObject(dataSub)) {
                                    if (dataSub.hasOwnProperty('showFields') && dataSub.showFields !== '') {
                                        $parentTable.attr('data-show-fields', dataSub.showFields);
                                        bpDetailShowAll($parent, dataSub.showFields);
                                    }
                                    if (dataSub.hasOwnProperty('hideFields') && dataSub.hideFields !== '') {
                                        $parentTable.attr('data-hide-fields', dataSub.hideFields); 
                                        bpDetailHideAll($parent, dataSub.hideFields);
                                    }
                                }
                                
                                Core.unblockUI();
                                $dialog.dialog('close');
                            }
                        });
                    }}, 
                    {text: plang.get('close_btn'), class: 'btn btn-sm blue-hoki', click: function() {
                        $dialog.dialog('close');
                    }}
                ]
            });
            $dialog.dialog('open');
            Core.unblockUI();
        },
        error: function() {
            alert('Error');
        }
    }).done(function() {
        Core.initUniform($dialog);
    });
}
function bpDetailHideShowFields($table) {
    if ($table.hasAttr('data-show-fields') && $table.attr('data-show-fields')) {
        bpDetailShowAll($table, $table.attr('data-show-fields'));
    }
    if ($table.hasAttr('data-hide-fields') && $table.attr('data-hide-fields')) {
        bpDetailHideAll($table, $table.attr('data-hide-fields'));
    }
    return;
}
function bpLoadDetailHideShowFields(mainSelector) {
    if (mainSelector.find('table[data-show-fields][data-hide-fields]').length) {
        mainSelector.find('table[data-show-fields][data-hide-fields]').each(function(){
            bpDetailHideShowFields($(this));
        });
    }
    return;
}
function createProcessPopupByHotKey(isBatch) {
    
    var $dialogName = 'dialog-create-process-listpopup'; 
    if (!$($dialogName).length) {
        $('<div id="' + $dialogName + '"></div>').appendTo('body');
    }
    var $dialog = $('#' + $dialogName);
    
    var $buttonElements = $('body').find('div.dv-process-buttons:visible:last a[data-actiontype="insert"]');
    var $len = $buttonElements.length, $i = 0, $listHtml = [], 
        batchName = '', tmpBatchName = '', processName = '', marginClass = '';
    
    if (!isBatch) {
        marginClass = ' ml0';
    }
    
    for ($i; $i < $len; $i++) { 
        
        var $row = $($buttonElements[$i]);
        var $parent = $row.closest('.dv-buttons-batch');
        processName = $row.text().trim();
        
        if ($parent.length) {
            
            batchName = $parent.find('button.dropdown-toggle').text().trim();
        
            if (batchName != tmpBatchName) {
                tmpBatchName = batchName;
                $listHtml.push('<div class="process-hk-batch">' + batchName + '</div>');
            } 
        
        } else if (isBatch) {
            $listHtml.push('<br />');
        }
        
        if ($i == 0) {
            $listHtml.push('<div class="process-hk-parent"><a href="javascript:;" class="process-hk process-hk-active'+marginClass+'" onclick="callCreateProcessByIndex('+$i+');">' + processName + '</a></div>');
        } else {
            $listHtml.push('<div class="process-hk-parent"><a href="javascript:;" class="process-hk'+marginClass+'" onclick="callCreateProcessByIndex('+$i+');">' + processName + '</a></div>');
        }
    } 

    $dialog.empty().append($listHtml.join(''));
    $dialog.dialog({
        cache: false,
        resizable: true,
        bgiframe: true,
        autoOpen: false,
        title: '',
        dialogClass: 'altn-custom-dialog', 
        width: 400,
        height: 'auto',
        modal: true,
        closeOnEscape: isCloseOnEscape, 
        open: function() {
            disableScrolling();
            var $thisDialogButton = $(this).parent();
            $thisDialogButton.on('keydown', function(e){
                var keyCode = (e.keyCode ? e.keyCode : e.which);
                if (keyCode == 38) { /* up */
                    var $thisButton = $thisDialogButton.find('a.process-hk-active');
                    
                    if ($thisButton.length) {
                        var $thisParent = $thisButton.closest('.process-hk-parent').prevAll('.process-hk-parent:eq(0)');
                    
                        if ($thisParent.length) {
                            $thisDialogButton.find('a.process-hk-active').removeClass('process-hk-active');
                            $thisParent.find('a.process-hk').addClass('process-hk-active').focus();
                        }
                    } else {
                        $thisDialogButton.find('a.process-hk:eq(0)').addClass('process-hk-active').focus();
                    }
                    
                } else if (keyCode == 40) { /* down */
                    var $thisButton = $thisDialogButton.find('a.process-hk-active');
                    var $thisParent = $thisButton.closest('.process-hk-parent').nextAll('.process-hk-parent:eq(0)');
                    
                    if ($thisParent.length) {
                        $thisDialogButton.find('a.process-hk-active').removeClass('process-hk-active');
                        $thisParent.find('a.process-hk').addClass('process-hk-active').focus();
                    }
                }
            });
        }, 
        close: function() {
            enableScrolling();
            $dialog.empty().dialog('destroy').remove();
        }
    });
    $dialog.dialog('open');
}
function callCreateProcessByIndex(index) {
    $('#dialog-create-process-listpopup').dialog('close');
    
    var $buttonElement = $('body').find('div.dv-process-buttons:visible:last a[data-actiontype="insert"]');
    $buttonElement.eq(index).click();
}
function helpHotKeys() {
    var $dialogName = 'dialog-hotkeys'; 
    if (!$($dialogName).length) {
        $('<div id="' + $dialogName + '"></div>').appendTo('body');
    }
    var $dialog = $('#' + $dialogName);

    $.ajax({
        type: 'post',
        url: 'mdcommon/hotkeys',
        dataType: 'json',
        beforeSend: function() {
            Core.blockUI({message: 'Loading...', boxed: true});
        },
        success: function(data) {
            $dialog.empty().append(data.html);
            $dialog.dialog({
                cache: false,
                resizable: true,
                bgiframe: true,
                autoOpen: false,
                title: data.title,
                width: 900,
                height: 'auto',
                modal: true,
                closeOnEscape: isCloseOnEscape, 
                close: function() {
                    enableScrolling();
                    $dialog.empty().dialog('destroy').remove();
                },
                buttons: [
                    {text: data.close_btn, class: 'btn btn-sm blue-hoki', click: function() {
                        $dialog.dialog('close');
                    }}
                ]
            });
            $dialog.dialog('open');
            Core.unblockUI();
        },
        error: function() {
            alert('Error');
        }
    });
}
function bpFileInputPlugin(mainSelector) {
    
    var $bootFileWrap = mainSelector.find('.boot-file-input-wrap');
    
    if ($bootFileWrap.length) {
        
        var $infile = $bootFileWrap.find("input[type='file']"), 
            infilePath = $bootFileWrap.parent().find("input[name='updateFileData']").val(), 
            fileprev = '', 
            fileExtensions = ($infile.hasAttr('data-valid-extension') ? $infile.attr('data-valid-extension') : 'jpg,jpeg,png,gif').replace(/\s+/g, ''), 
            getExtension = fileExtensions.split(',');
    
        if (typeof infilePath !== 'undefined') {
            var ext = ['jpg', 'jpeg', 'png', 'gif'];
            if (ext.indexOf(infilePath.split('.').pop().toLowerCase()) !== -1) {
                fileprev = '<img src="' + infilePath + '" style="height: 120px" class="file-preview-image">';
            } else {
                fileprev = '<div class="file-preview-other"><span class="file-icon-4x"><i class="fa fa-file-o fa-2x text-success"></i></span></div>';
            }
        } else {
            fileprev = '<img src="assets/core/global/img/user.png" style="height: 120px" class="file-preview-image" alt="Default photo">';
        }

        $infile.fileinput({
            showCaption: false,
            showUpload: false,
            browseClass: "btn btn-xs btn-primary",
            removeClass: "btn btn-xs",
            removeLabel: "",
            defaultPreviewContent: '<img src="assets/core/global/img/user.png" style="height: 120px" class="file-preview-image" alt="Default photo">',
            previewFileIcon: '<i class="fa fa-file-o fa-2x text-success"></i>',
            allowedFileExtensions: getExtension,
            elErrorContainer: '#boot-fileinput-error-wrap',
            msgErrorClass: 'alert alert-block alert-danger',
            previewFileIconSettings: {
                'docx': '<i class="fa fa-file-word-o fa-2x text-success"></i>',
                'doc': '<i class="fa fa-file-word-o fa-2x text-success"></i>',
                'xlsx': '<i class="fa fa-file-excel-o fa-2x text-success"></i>',
                'pptx': '<i class="fa fa-file-powerpoint-o fa-2x text-success"></i>',
                'pdf': '<i class="fa fa-file-pdf-o fa-2x text-success"></i>',
                'zip': '<i class="fa fa-file-archive-o fa-2x text-success"></i>'
            },
            previewSettings: {
                image: {width: "auto", height: "120px"},
                text: {width: "120px", height: "120px"},
                other: {width: "120px", height: "120px"}
            },
            initialPreview: [
                fileprev
            ]
        });
    }
    return;
}
function toQuickMenu(metaDataId, metaType, elem, menuName) {
    var $this = $(elem), menuName = (typeof menuName !== 'undefined') ? menuName : '';
    
    if ($this.find('i').hasClass('fa-star')) {
        
        toQuickMenuSave(metaDataId, metaType, menuName, $this);
        
    } else {
        
        var $dialogName = 'dialog-quickmenu-confirm';
        if (!$("#" + $dialogName).length) {
            $('<div id="' + $dialogName + '"></div>').appendTo('body');
        }
        var $dialog = $('#' + $dialogName), html = [];
        
        html.push('<div class="alert alert-info alert-styled-left">Та товч сонгох хэсгээс HotKey сонгож өгснөөр хэрэгтэй цонх болон жагсаалтыг хурдан дуудаж ашиглах боломжтой. Сонгосон HotKey нь тухайн модуль дээр ажиллах болохыг анхаарна уу.</div>');
        html.push('<select class="form-control">');
            html.push('<option value="">- Товч сонгох -</option>');
            html.push('<option value="F4">F4</option>');
            html.push('<option value="F6">F6</option>');
            html.push('<option value="F7">F7</option>');
            html.push('<option value="F8">F8</option>');
            html.push('<option value="F9">F9</option>');
            html.push('<option value="F10">F10</option>');
        html.push('</select>');
        
        $dialog.empty().append(html.join(''));  
        $dialog.dialog({
            resizable: true,
            bgiframe: true,
            autoOpen: false,
            title: 'Quick Menu',
            width: 400,
            height: 'auto',
            modal: true,
            closeOnEscape: isCloseOnEscape,
            close: function() {
                $dialog.empty().dialog('destroy').remove();
            }, 
            buttons: [
                {text: plang.get('save_btn'), class: 'btn green-meadow btn-sm', click: function () {
                    Core.blockUI({boxed : true, message: 'Loading...'});  
                    toQuickMenuSave(metaDataId, metaType, menuName, $this, $dialog, $dialog.find('select').val());
                }}, 
                {text: plang.get('close_btn'), class: 'btn blue-madison btn-sm', click: function () {
                    $dialog.dialog('close');
                }}
            ]
        });
        $dialog.dialog('open');
    }
}
function toQuickMenuSave(metaDataId, metaType, menuName, $this, $dialog, hotKey) {
    
    var $pageActions = $('.page-actions'), isRenderMenu = $pageActions.length;

    $.ajax({
        type: 'post',
        url: 'mduser/toQuickMenu',
        data: {
            metaDataId: metaDataId, 
            metaType: metaType, 
            menuName: menuName, 
            url: window.location.href, 
            isRenderMenu: isRenderMenu, 
            hotKey: (typeof hotKey != 'undefined') ? hotKey : ''
        }, 
        dataType: 'json',
        success: function(data) {
            
            PNotify.removeAll();
            Core.unblockUI();
            
            if (data.status == 'success') {
                
                if (typeof $dialog != 'undefined') {
                    $dialog.dialog('close');
                }
                
                if (isRenderMenu) {
                    
                    if (data.hasOwnProperty('remove')) {
                        
                        metaDataId = metaDataId.replace(/\//g, '');
                        
                        $pageActions.find('.dropdown-menu').find('[data-qmid='+metaDataId+']').remove();
                        $this.find('i').removeClass('fa-star').addClass('fa-star-o');
                        
                        if ($pageActions.find('.dropdown-menu > a').length == 0) {
                            $pageActions.remove();
                        }
                        
                    } else {
                        
                        $pageActions.find('.dropdown-menu').prepend(data.menuHtml);
                        $this.find('i').removeClass('fa-star-o').addClass('fa-star');
                    }
                    
                } else {
                    
                    $('.page-module-name').after(data.menuHtml);
                    $this.find('i').removeClass('fa-star-o').addClass('fa-star'); 
                    
                    Core.initDVAjax($('.page-actions'));
                }
                
                $('.page-actions button').pulsate({
                    color: '#09f', 
                    reach: 100,
                    speed: 500,
                    glow: true, 
                    repeat: 1
                });
                
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
}
function bpFullScreen(elem) {
    var $this = $(elem), $parent = $this.closest('[data-bp-uniq-id]');
    
    if (!$this.hasAttr('data-fullscreen')) {
        var $oldHeight = $parent.height(), 
            $oldMaxHeight = $parent.css('max-height');
        $this.css('right', '10px').attr({'data-fullscreen': '1', 'data-old-height': $oldHeight, 'data-old-max-height': $oldMaxHeight, 'title': 'Restore'}).find('i').removeClass('fa-expand').addClass('fa-compress');
        $parent.addClass('bp-dtl-fullscreen');
    } else {
        $this.css('right', '0').attr('title', 'Fullscreen').removeAttr('data-fullscreen').find('i').removeClass('fa-compress').addClass('fa-expand');
        $parent.removeClass('bp-dtl-fullscreen');
    }
    
    return;
}
function bpDetailFullScreen(elem) {
    var $this = $(elem), 
        $parent = $this.closest('div[data-bp-detail-container="1"]'), 
        $scrollDiv = $parent.find('.bp-overflow-xy-auto'), 
        $isPager = ($parent.find('.pf-bp-pager-tool').length) ? true : false, 
        $openDialog = $parent.closest('.ui-dialog'), 
        $isDialog = ($openDialog.length) ? true : false;
    
    if (!$this.hasAttr('data-fullscreen')) {
        
        var $oldHeight = $scrollDiv.height(), 
            $oldMaxHeight = $scrollDiv.css('max-height');
        
        if ($isDialog) {
            $openDialog.css('overflow', 'inherit');
        }
        
        $this.attr({'data-fullscreen': '1', 'data-old-height': $oldHeight, 'data-old-max-height': $oldMaxHeight, 'title': 'Restore'}).find('i').removeClass('fa-expand').addClass('fa-compress');
        $parent.addClass('bp-dtl-fullscreen');
        
        if ($isPager) {
            var $windowHeight = $(window).height() - 85;
        } else {
            var $windowHeight = $(window).height() - 50;
        }
        
        $scrollDiv.css({'max-height': $windowHeight, 'min-height': $windowHeight}).attr('data-full-screen', '1');
        
    } else {
        var $oldHeight = $this.attr('data-old-height'), 
            $oldMaxHeight = $this.attr('data-old-max-height');
        
        if ($isDialog) {
            $openDialog.css('overflow', '');
        }
        
        $this.attr('title', 'Fullscreen').removeAttr('data-fullscreen').find('i').removeClass('fa-compress').addClass('fa-expand');
        $parent.removeClass('bp-dtl-fullscreen');
        
        $scrollDiv.css({'max-height': $oldMaxHeight, 'height': '', 'min-height': ''}).removeAttr('data-full-screen');
    }
    
    var scrollHeight = $scrollDiv[0].scrollHeight,
        clientHeight = $scrollDiv[0].clientHeight, 
        top          = $scrollDiv.scrollTop();

    $scrollDiv.find('> table[data-table-path] > tfoot > tr > td').css('bottom', scrollHeight - clientHeight - top);
    $scrollDiv.trigger('scroll');
    
    return;
}
function bpCleanFieldUserConfig(metaDataId, elem) {
    var $dialogName = 'dialog-bp-clean-user-config'; 
    if (!$($dialogName).length) {
        $('<div id="' + $dialogName + '"></div>').appendTo('body');
    }
    var $dialog = $('#' + $dialogName);

    $.ajax({
        type: 'post',
        url: 'mduser/cleanFieldUserConfig',
        data: {processId: metaDataId}, 
        dataType: 'json',
        beforeSend: function() {
            Core.blockUI({message: 'Loading...', boxed: true});
        },
        success: function(data) {
            $dialog.empty().append(data.html);
            $dialog.dialog({
                cache: false,
                resizable: true,
                bgiframe: true,
                autoOpen: false,
                title: data.title,
                width: 400,
                height: 'auto',
                modal: true, 
                closeOnEscape: isCloseOnEscape, 
                close: function() {
                    $dialog.empty().dialog('destroy').remove();
                },
                buttons: [
                    {text: data.save_btn, class: 'btn btn-sm green-meadow', click: function() {
                        $.ajax({
                            type: 'post',
                            url: 'mduser/cleanFieldUserConfigSave', 
                            data: $dialog.find('form').serialize(),
                            dataType: 'json', 
                            beforeSend: function() {
                                Core.blockUI({boxed : true, message: 'Loading...'});  
                            }, 
                            success: function(dataSub) {
                                $(elem).attr('data-clean-fields', dataSub.cleanParamPath);
                                $(elem).attr('data-ignore-clean-fields', dataSub.noCleanParamPath);
                                $dialog.dialog('close');
                                
                                Core.unblockUI();
                            }
                        });
                    }}, 
                    {text: data.close_btn, class: 'btn btn-sm blue-hoki', click: function() {
                        $dialog.dialog('close');
                    }}
                ]
            });
            $dialog.dialog('open');
            Core.unblockUI();
        },
        error: function() {
            alert('Error');
        }
    }).done(function() {
        Core.initUniform($dialog);
    });
}
function selectedRowsBpAddRowByQuickSearch(uniqId, $this, _processId, _paramRealPath, _lookupId, valueId, params) {
    
    $.ajax({
        type: 'post',
        url: 'mdobject/autoCompleteById', 
        data: {
            processMetaDataId: _processId,
            lookupId: _lookupId, 
            paramRealPath: _paramRealPath,
            code: valueId,
            isName: 'idselect', 
            params: encodeURIComponent(params) 
        },
        dataType: 'json', 
        async: false,
        success: function(data) {
            if (data.META_VALUE_ID !== '') {
                window['selectedRowsBpAddRow_'+uniqId]($this, _processId, _paramRealPath, _lookupId, [data.rowData], 'autocomplete');
                $this.val('');
            } else {
                bpSoundPlay('error');
                PNotify.removeAll();
                new PNotify({
                    title: 'Warning',
                    text: 'Хайлтын үр дүн олдсонгүй!',
                    type: 'warning',
                    sticker: false
                });
            }
        }, 
        error: function() {
            alert("Error");
        }
    });
}
function bpDetailExcel(elem) {
    
    Core.blockUI({message: 'Exporting...', boxed: true});
        
    var $this = $(elem);
    var $form = $this.closest('div[data-bp-uniq-id]');
    var $parent = $this.closest('div[data-section-path]');
    var $groupPath = $parent.attr('data-section-path');
    var $parentTable = $form.find('table[data-table-path="'+$groupPath+'"]');
    var $parentId = $parentTable.attr('data-row-id');
    var $processId = $form.attr('data-process-id');

    if ($parentTable.hasAttr('data-pager') && $parentTable.attr('data-pager') == 'true') {
        
        var $cacheId = $form.find('input[name="cacheId"]').val(), 
            $headers = $parentTable.find('thead > tr:eq(0) > th:visible:not(.rowNumber, .hide, .action)'), 
            $aggregates = $parentTable.find('thead > tr:eq(0) > th[data-aggregate!=""]:visible:not(.rowNumber, .hide, .action)'), 
            $footers = $parentTable.find('tfoot > tr:eq(0)'), 
            $this, labelName = '', path = '', 
            headerData = [], footerData = [];
        
        $headers.each(function() {
            $this = $(this);
            labelName = $this.text();
            path = $this.attr('data-cell-path');
            
            if (labelName != '' && path != '') {
                var $input = $parentTable.find('tbody > tr:eq(0) > td [data-path="'+path+'"]');
                headerData.push({
                    path: path, 
                    labelName: labelName, 
                    isLookup: $input.hasClass('popupInit')
                });
            }
        });
        
        if ($aggregates.length) {
            $aggregates.each(function() {
                $this = $(this);
                path = $this.attr('data-cell-path');

                if (path != '') {
                    footerData.push({
                        PARAM_REAL_PATH: path, 
                        PARAM_NAME: path.replace($groupPath+'.', ''), 
                        DATA_TYPE: 'number', 
                        COLUMN_AGGREGATE: $this.attr('data-aggregate')
                    });
                }
            });
        }
        
        var postData = {
            processId: $processId,
            rowId: $parentId, 
            cacheId: $cacheId, 
            groupPath: $groupPath, 
            headerData: headerData, 
            footerData: footerData 
        };
        
        postData['params'] = $parentTable.find('input, select, textarea').serialize();
        
        var $lookupInputs = $parentTable.find('input.popupInit');
        var $lookupInputsLen = $lookupInputs.length, $n = 0;
        var objs = {}, rowObj = {};
                
        for ($n; $n < $lookupInputsLen; $n++) { 
            var $lookupInput = $($lookupInputs[$n]);
            
            if ($lookupInput.val() != '') {
                
                rowObj = {};
                
                var $row = $lookupInput.parents('tr'), 
                    $parent = $lookupInput.closest('.double-between-input'), 
                    
                    $id = $lookupInput.val(), 
                    $code = $parent.find('input[id*="_displayField"]').val(), 
                    $name = $parent.find('input[id*="_nameField"]').val(), 
                    $rowData = $lookupInput.attr('data-row-data'), 
                    $rowId = $row.find('input[name*=".mainRowCount"]').val(), 
                    $getPath = $lookupInput.attr('data-path');
                
                rowObj['rowId'] = $rowId;
                rowObj['path'] = $getPath.toLowerCase();
                
                rowObj['id'] = $id;
                rowObj['code'] = $code;
                rowObj['name'] = $name;
                rowObj['rowdata'] = ($rowData !== '' ? JSON.parse($rowData) : '');
                
                objs[$n] = rowObj;
            }
        }
        
        postData['lookupParams'] = objs;
        
        $.fileDownload(URL_APP + 'mdcache/bpDetailExcelExport', {
            httpMethod: 'POST',
            data: postData 
        }).done(function() {
            Core.unblockUI();
        }).fail(function(response) {
            PNotify.removeAll();
            new PNotify({
                title: 'Info',
                text: response,
                type: 'info',
                sticker: false
            });
            Core.unblockUI();
        });
    } else {
        $headers = $parentTable.find('thead > tr:eq(0) > th:visible:not(.rowNumber, .hide, .action)');
        var $details = $parentTable.find(' > tbody > tr'),
            jsonSheetData = [], $trRow, $tdRow, jsonSheetSingleData,
            $getPathElement, resultNum = '';

        $details.each(function(){
            $trRow = $(this);
            jsonSheetSingleData = {};
            $trRow.find(' > td').each(function(){
                $tdRow = $(this);
                $headers.each(function(){
                    if ($(this).data('cell-path') == $tdRow.data('cell-path')) {

                        $getPathElement = $trRow.find("[data-path='" + $tdRow.data('cell-path') + "']");
                        if ($getPathElement.length) {
                            if ($getPathElement.prop("tagName") == 'SELECT') {
                                resultNum = $getPathElement.text();
                            } else {
                                if ($getPathElement.hasClass('numberInit') 
                                    || $getPathElement.hasClass('decimalInit') 
                                    || $getPathElement.hasClass('integerInit')) {

                                    var getNumber = $getPathElement.autoNumeric("get");
                                    if (isNaN(getNumber)) {
                                        resultNum = Number($getPathElement.val());
                                    } else {
                                        resultNum = Number(getNumber);
                                    }
                                } else if ($getPathElement.hasClass('bigdecimalInit')) {

                                    resultNum = Number($getPathElement.next("input[type=hidden]").val());

                                } else if ($getPathElement.hasClass('longInit')) {
                                    var getNumber = $getPathElement.autoNumeric("get");
                                    if (isNaN(getNumber)) {
                                        resultNum = $getPathElement.val();
                                    } else {
                                        resultNum = getNumber;
                                    }
                                } else if ($getPathElement.hasClass('booleanInit')) { 
                                    resultNum = $getPathElement.is(':checked') ? '✓' : '';
                                } else if ($getPathElement.hasClass('popupInit')) { 
                                    resultNum = $getPathElement.parent().find('.lookup-code-autocomplete').val();
                                    resultNum += ' - ' + $getPathElement.parent().find('.lookup-name-autocomplete').val();
                                } else {
                                    resultNum = $getPathElement.val();
                                }
                            }
                        }

                        jsonSheetSingleData[$(this).text()] = resultNum;
                    }
                });
            });
            jsonSheetData.push(jsonSheetSingleData);
        });
        
        jsonSheetSingleData = {};
        resultNum = '';
        $parentTable.find(' > tfoot > tr:eq(0) > td').each(function(){
            $tdRow = $(this);
            $headers.each(function(){
                if ($(this).data('cell-path') == $tdRow.data('cell-path')) {

                    $getPathElement = $parentTable.find(' > tfoot > tr:eq(0)').find("[data-cell-path='" + $tdRow.data('cell-path') + "']");
                    if ($getPathElement.length) {
                        resultNum = pureNumber($getPathElement.text());
                    }

                    jsonSheetSingleData[$(this).text()] = resultNum;
                }
            });
        });        
        
        var postData = {
            dtl: jsonSheetData,
            footerData: jsonSheetSingleData
        };
        $.fileDownload(URL_APP + 'mdwebservice/bpDetailExcelExport', {
            httpMethod: 'POST',
            data: postData 
        }).done(function() {
            Core.unblockUI();
        }).fail(function(response) {
            PNotify.removeAll();
            new PNotify({
                title: 'Info',
                text: response,
                type: 'info',
                sticker: false
            });
            Core.unblockUI();
        });        
//        if (typeof XLSX === 'undefined') {
//            $.getScript(URL_APP+'assets/custom/addon/plugins/sheetjs/xlsx.full.min.js').done(function() {             
//                jsonToSheetBpDtl();
//            }); 
//        } else {
//            jsonToSheetBpDtl();
//        }
        function jsonToSheetBpDtl() {
            $headers = $parentTable.find('thead > tr:eq(0) > th:visible:not(.rowNumber, .hide, .action)');
            var $details = $parentTable.find(' > tbody > tr'),
                jsonSheetData = [], $trRow, $tdRow, jsonSheetSingleData,
                $getPathElement, resultNum = '';

            $details.each(function(){
                $trRow = $(this);
                jsonSheetSingleData = {};
                $trRow.find(' > td').each(function(){
                    $tdRow = $(this);
                    $headers.each(function(){
                        if ($(this).data('cell-path') == $tdRow.data('cell-path')) {

                            $getPathElement = $trRow.find("[data-path='" + $tdRow.data('cell-path') + "']");
                            if ($getPathElement.length) {
                                if ($getPathElement.prop("tagName") == 'SELECT') {
                                    resultNum = $getPathElement.text();
                                } else {
                                    if ($getPathElement.hasClass('numberInit') 
                                        || $getPathElement.hasClass('decimalInit') 
                                        || $getPathElement.hasClass('integerInit')) {

                                        var getNumber = $getPathElement.autoNumeric("get");
                                        if (isNaN(getNumber)) {
                                            resultNum = Number($getPathElement.val());
                                        } else {
                                            resultNum = Number(getNumber);
                                        }
                                    } else if ($getPathElement.hasClass('bigdecimalInit')) {

                                        resultNum = Number($getPathElement.next("input[type=hidden]").val());

                                    } else if ($getPathElement.hasClass('longInit')) {
                                        var getNumber = $getPathElement.autoNumeric("get");
                                        if (isNaN(getNumber)) {
                                            resultNum = $getPathElement.val();
                                        } else {
                                            resultNum = getNumber;
                                        }
                                    } else if ($getPathElement.hasClass('booleanInit')) { 
                                        resultNum = $getPathElement.is(':checked') ? '•' : '';
                                    } else if ($getPathElement.hasClass('popupInit')) { 
                                        resultNum = $getPathElement.parent().find('.lookup-code-autocomplete').val();
                                        resultNum += ' - ' + $getPathElement.parent().find('.lookup-name-autocomplete').val();
                                    } else {
                                        resultNum = $getPathElement.val();
                                    }
                                }
                            }

                            jsonSheetSingleData[$(this).text()] = resultNum;
                        }
                    });
                });
                jsonSheetData.push(jsonSheetSingleData);
            });

            var exportExcels = XLSX.utils.json_to_sheet(jsonSheetData);

            var wb = XLSX.utils.book_new();
            XLSX.utils.book_append_sheet(wb, exportExcels, $groupPath);
            XLSX.writeFile(wb, $groupPath+(moment().format("YYYY-MM-DD_HH:MM"))+'.xlsx');             
        }
        Core.unblockUI();
    }
    
    return;
}
function bpDetailModifyMode($row, uniqId, realTable) {
    
    if (typeof realTable !== 'undefined' && realTable) {
        var $table = realTable, isRealTable = true;
    } else {
        var $table = $row.closest('table.bprocess-table-dtl'), isRealTable = false;
    }
    
    if ($table.hasAttr('data-detailmodify-mode') && $table.attr('data-detailmodify-mode') == 'sidebar') {
    
        var $parent = $table.closest('div.row[data-section-path]'), 
            $detailContainer = $parent.find('[data-bp-detail-container="1"]');

        if (!$detailContainer.hasClass('col-md-8')) {
            $detailContainer.before('<div class="col-md-4" data-bp-detail-sidebar="1"></div>');
            $detailContainer.removeClass('col-md-12').addClass('col-md-8');
        }
        
        var $detailSidebar = $parent.find('[data-bp-detail-sidebar="1"]'), sideBarHtml = '', 
            $head = $table.find('> thead > tr:eq(0)'), 
            $cells = $row.find('td[data-cell-path]:not(.hide)'), 
            $cellsLength = $cells.length, $n = 0;
        
        if (isRealTable) {
            var sidebarSaveButton = '<button type="button" class="btn blue btn-sm float-right" onclick="bpEditSidebarAddRow(this);">Нэмэх</button><div class="clearfix w-100 mb5"></div>';
        } else {
            var sidebarSaveButton = '';'<button type="button" class="btn blue btn-sm float-right ml5" onclick="bpEditSidebarSave(this);">Хадгалах</button>';
                sidebarSaveButton += '<button type="button" class="btn blue btn-sm float-right" onclick="bpEditSidebarSaveAdd(this);">Хадгалаад нэмэх</button><div class="clearfix w-100 mb5"></div>';
        }
        
        sideBarHtml = '<div class="sidebar-buttons">' + sidebarSaveButton + '</div>'+'<div class="panel panel-default bg-inverse bp-detail-sidebar">';
            sideBarHtml += '<table class="table sheetTable" style="table-layout: fixed">';
                sideBarHtml += '<tbody>';

        for ($n; $n < $cellsLength; $n++) { 
            var $cell = $($cells[$n]), 
                path = $cell.attr('data-cell-path'), 
                labelName = $head.find('th[data-cell-path="'+path+'"]').text(), 
                $input = $cell.find('[data-path]');

            sideBarHtml += '<tr data-r-path="'+path+'">';
                sideBarHtml += '<td style="height: 30px; width: 150px;" class="left-padding"><label>'+labelName+':</label></td>'; 
                sideBarHtml += '<td>';
                
                if ($input.hasClass('select2')) {
                    
                    var $selectedValue = $input.select2('val');
                    var $select2Cloned = $input.clone();
                    $select2Cloned.select2('destroy');
                    $select2Cloned.removeAttr('name');
                    $select2Cloned.removeAttr('data-path');
                    $select2Cloned.attr('data-c-path', path);
                    $select2Cloned.css({'display': ''});
                    
                    var $html = $('<div />', {html: $select2Cloned});
                    
                    if ($selectedValue != '') {
                        $html.find('option[value="'+$selectedValue+'"]').attr('selected', 'selected');
                    }
                
                    sideBarHtml += $html.html();
                    
                } else if ($input.hasClass('dateInit')) {
                    
                    var $selectedValue = $input.val();
                    var $dateParent = $input.closest('.dateElement');
                    var $dateCloned = $dateParent.clone();
                    
                    $dateCloned.removeAttr('name');
                    $dateCloned.removeAttr('data-path');
                    $dateCloned.attr('data-c-path', path);
                    $dateCloned.css({'display': ''});
                    
                    var $html = $('<div />', {html: $dateCloned});
                    
                    if ($selectedValue != '') {
                        $html.find('input[type="text"]').val($selectedValue);
                    }
                    
                    sideBarHtml += $html.html();
                    
                } else {
                    
                    var $selectedValue = $input.val();
                    var $inputCloned = $input.clone();
                    
                    $inputCloned.removeAttr('name');
                    $inputCloned.removeAttr('data-path');
                    $inputCloned.attr('data-c-path', path);
                    $inputCloned.css({'display': ''});
                    
                    var $html = $('<div />', {html: $inputCloned});
                    
                    if ($selectedValue != '') {
                        $html.find('input[type="text"]').val($selectedValue);
                    }
                    
                    sideBarHtml += $html.html();
                }
                
                sideBarHtml += '</td>';
            sideBarHtml += '</tr>';
        }

                sideBarHtml += '</tbody>'; 
            sideBarHtml += '</table>';
        sideBarHtml += '</div>';
        
        $detailSidebar.empty().append(sideBarHtml).promise().done(function () {
            $row.find('input, textarea').each(function ($index, row) {
                var $rows = $(row);
                var $bSelector = $detailSidebar.find('input[data-c-path="'+ $rows.attr('data-path') +'"]');
                
                if (typeof $rows.attr('data-path') !== 'undefined' && $bSelector.length < 1) {
                    $detailSidebar.find('[data-c-path="'+ $rows.attr('data-path') +'"] > input').val($rows.val());
                } else {
                    $bSelector.val($rows.val());
                }
                
            });
        });
        
        Core.initInputType($detailSidebar);
    }
    
    window['editSidebarLoad_'+uniqId]();
    
    return;
}
function bpEditSidebarSave(elem) {
    
    Core.blockUI({message: 'Loading...', boxed: true});
    
    setTimeout(function(){
        var $this = $(elem), 
            $parent = $this.closest('[data-section-path]'), 
            $sidebar = $parent.find('[data-bp-detail-sidebar="1"]'), 
            $detail = $parent.find('[data-bp-detail-container="1"]').find('.bprocess-table-dtl:eq(0)'), 
            $selectedRow = $detail.find('> tbody > tr.currentTarget'), 
            $rows = $sidebar.find('.bp-detail-sidebar > table > tbody > tr'), 
            $rowsLength = $rows.length, $n = 0;

        for ($n; $n < $rowsLength; $n++) { 

            var $row = $($rows[$n]), 
                path = $row.attr('data-r-path'),    
                $input = ($row.find('[data-c-path]').hasClass('input-group')) ? $row.find('[data-c-path] > input') : $row.find('[data-c-path]');

            var $getField = $selectedRow.find('[data-path="'+path+'"]');

            if ($input.hasClass('select2')) {
                $getField.trigger('select2-opening', [true]);
                $getField.select2('val', $input.select2('val'));
            } else if ($input.hasClass('longInit') 
                || $input.hasClass('numberInit') 
                || $input.hasClass('decimalInit') 
                || $input.hasClass('integerInit')) {
                $getField.autoNumeric('set', $input.autoNumeric('get'));                        
            } else if ($input.hasClass('dateInit')) {
                var val = $input.val();
                
                if (val !== '' && val !== null) {
                    $getField.datepicker('update', date('Y-m-d', strtotime(val)));
                } else {
                    $getField.datepicker('update', null);
                }
                
            } else if ($input.hasClass('datetimeInit')) {
                var val = $input.val();
                if (val !== '' && val !== null) {
                    $getField.val(date('Y-m-d H:i:s', strtotime(val)));
                } else {
                    $getField.val('');
                }
                
            } else if ($input.hasClass('popupInit')) {   
                setLookupPopupValue($getField, $input.val());
            } else if ($input.hasClass('booleanInit')) {   
                checkboxCheckerUpdate($getField, $input.is(':checked'));
            } else {                                               
                $getField.val($input.val());                        
            }
        }

        Core.unblockUI();
    }, 400);                      
    
    return;
}
function bpEditSidebarAddRow(elem) {
    
    Core.blockUI({message: 'Loading...', boxed: true});
    
    setTimeout(function() {
        var $this = $(elem), 
            uniqId = $this.closest('div[data-bp-uniq-id]').attr('data-bp-uniq-id'), 
            $parent = $this.closest('[data-section-path]'), 
            $sidebar = $parent.find('[data-bp-detail-sidebar="1"]'), 
            $detail = $parent.find('[data-bp-detail-container="1"]').find('.bprocess-table-dtl:eq(0)'),  
            $groupPath = $detail.attr('data-table-path'),  
            $rows = $sidebar.find('.bp-detail-sidebar > table > tbody > tr'), 
            $rowsLength = $rows.length, $n = 0, 
            $rowTemplate = $parent.find('[data-bp-detail-container="1"]').find('script[data-template="'+$groupPath+'"]').text(), 
            $html = $('<div />', {html: $rowTemplate}), 
            $getTableBody = $detail.find('> tbody');
        
        $html.find('> tr:eq(0)').addClass('display-none added-bp-row');
                    
        $getTableBody.append($html.html());
        
        var $selectedRow = $getTableBody.find('> tr:last-child');

        Core.initBPInputType($selectedRow);

        for ($n; $n < $rowsLength; $n++) { 

            var $row = $($rows[$n]), 
                path = $row.attr('data-r-path'),    
                
                $input = ($row.find('[data-c-path]').hasClass('input-group')) ? $row.find('[data-c-path] > input') : $row.find('[data-c-path]');

            var $getField = $selectedRow.find('[data-path="'+path+'"]');

            if ($input.hasClass('select2')) {
                $getField.trigger('select2-opening', [true]);
                $getField.select2('val', $input.select2('val'));
            } else if ($input.hasClass('longInit') 
                || $input.hasClass('numberInit') 
                || $input.hasClass('decimalInit') 
                || $input.hasClass('integerInit')) {
                $getField.autoNumeric('set', $input.autoNumeric('get'));                        
            } else if ($input.hasClass('dateInit')) {
                var val = $input.val();
                if (val !== '' && val !== null) {
                    $getField.datepicker('update', date('Y-m-d', strtotime(val)));
                } else {
                    $getField.datepicker('update', null);
                }
                
            } else if ($input.hasClass('datetimeInit')) {
                var val = $input.val();
                if (val !== '' && val !== null) {
                    $getField.val(date('Y-m-d H:i:s', strtotime(val)));
                } else {
                    $getField.val('');
                }
                
            } else if ($input.hasClass('popupInit')) {   
                setLookupPopupValue($getField, $input.val());
            } else if ($input.hasClass('booleanInit')) {   
                checkboxCheckerUpdate($getField, $input.is(':checked'));
            } else {                                               
                $getField.val($input.val());                        
            }
        }
        
        window['bpFullScriptsWithoutEvent_'+uniqId]($selectedRow, $groupPath, false, true);
        window['dtlAggregateFunction_'+uniqId]();

        var $el = $getTableBody.find('> tr:not(.removed-tr)'), 
            len = $el.length, i = 0;
        for (i; i < len; i++) { 
            $($el[i]).find('td:first > span').text(i + 1);
        }
        bpSetRowIndex($parent);

        $getTableBody.find('> tr.display-none').removeClass('display-none');

        bpDetailFreeze($detail);
        $detail.closest('.bp-overflow-xy-auto').animate({
            scrollTop: 10000
        }, 0);
        
        var $lastRow = $('<div />', {html: $rowTemplate});
        bpDetailModifyMode($lastRow.find('> tr:eq(0)'), uniqId, $detail);

        Core.unblockUI();
    }, 400);
    
    return;
}
function bpEditSidebarSaveAdd(elem) {
    
    Core.blockUI({message: 'Loading...', boxed: true});
    
    setTimeout(function() {
        var $this = $(elem), 
            uniqId = $this.closest('div[data-bp-uniq-id]').attr('data-bp-uniq-id'), 
            $parent = $this.closest('[data-section-path]'), 
            $sidebar = $parent.find('[data-bp-detail-sidebar="1"]'), 
            $detail = $parent.find('[data-bp-detail-container="1"]').find('.bprocess-table-dtl:eq(0)'), 
            $selectedRow = $detail.find('> tbody > tr.currentTarget'), 
            $rows = $sidebar.find('.bp-detail-sidebar > table > tbody > tr'), 
            $rowsLength = $rows.length, $n = 0;
            
        for ($n; $n < $rowsLength; $n++) { 

            var $row = $($rows[$n]), 
                path = $row.attr('data-r-path'),    
                $input = ($row.find('[data-c-path]').hasClass('input-group')) ? $row.find('[data-c-path] > input') : $row.find('[data-c-path]');

            var $getField = $selectedRow.find('[data-path="'+path+'"]');
            
            if ($input.hasClass('select2')) {
                $getField.trigger('select2-opening', [true]);
                $getField.select2('val', $input.select2('val'));
            } else if ($input.hasClass('longInit') 
                || $input.hasClass('numberInit') 
                || $input.hasClass('decimalInit') 
                || $input.hasClass('integerInit')) {
                $getField.autoNumeric('set', $input.autoNumeric('get'));                        
            } else if ($input.hasClass('dateInit')) {
                var val = $input.val();
                if (val !== '' && val !== null) {
                    $getField.datepicker('update', date('Y-m-d', strtotime(val)));
                } else {
                    $getField.datepicker('update', null);
                }
                
            } else if ($input.hasClass('datetimeInit')) {
                var val = $input.val();
                if (val !== '' && val !== null) {
                    $getField.val(date('Y-m-d H:i:s', strtotime(val)));
                } else {
                    $getField.val('');
                }
                
            } else if ($input.hasClass('popupInit')) {   
                setLookupPopupValue($getField, $input.val());
            } else if ($input.hasClass('booleanInit')) {   
                checkboxCheckerUpdate($getField, $input.is(':checked'));
            } else {                                               
                $getField.val($input.val());                        
            }
        }
        
        var $groupPath = $detail.attr('data-table-path');
        var $rowTemplate = $parent.find('[data-bp-detail-container="1"]').find('script[data-template="'+$groupPath+'"]').text();
        var $lastRow = $('<div />', {html: $rowTemplate});
        
        bpDetailModifyMode($lastRow.find('> tr:eq(0)'), uniqId, $detail);

        Core.unblockUI();
    }, 400);                      
    
    return;
}
function activeWsMenu(elem, menuId) {
    var $this = $(elem);
    var $wsArea = $this.closest('.ws-area');
    
    $wsArea.find('a[data-menu-id="'+menuId+'"]').click();
    $('html, body').animate({
        scrollTop: 0
    }, 0);
    
    return;
}
function bpDetailACModeToggle(elem) {
    var $this = $(elem), $parent = $this.closest('.bp-add-ac-row'), 
        $parentGroup = $this.closest('.input-group-btn'), 
        $button = $parentGroup.find('button.dropdown-toggle'), 
        $ul = $parentGroup.find('ul.dropdown-menu'), 
        $input = $parent.find('input.lookup-code-hard-autocomplete');   
        
    $ul.find('a[onclick*="bpDetailACModeToggle"]').closest('li').show();
    $this.closest('li').hide();

    $button.html($this.text());

    if ($this.hasAttr('data-filter-type')) {
        $input.removeAttr('data-filter-path').attr('data-filter-type', $this.attr('data-filter-type'));
    } else {
        $input.attr('data-filter-path', $this.attr('data-filter-path'));
    }
    
    $input.focus().select();
    
    return;
}
function bpExpressionEditor(elem) {
    
    Core.blockUI({message: 'Loading...', boxed: true});
    
    $.cachedScript('assets/custom/addon/plugins/codemirror/lib/codemirror.min.js?v=2').done(function() { 
        
        var $dialogName = 'dialog-bpExpressionEditor';
        if (!$("#" + $dialogName).length) {
            $('<div id="' + $dialogName + '"></div>').appendTo('body');
        }
        var $dialog = $('#' + $dialogName), 
            $this = $(elem).closest('.input-group').find('.expression_editorInit'), 
            $form = $this.closest('form'), 
            $expressionEditorTagsSource = $form.find('[data-path="expressionEditorTagsSource"]'), 
            postData = {expression: $this.val(), fieldPath: $this.attr('data-path').toLowerCase(), isJson: 0};
        if ($expressionEditorTagsSource.length) {
            
            if ($expressionEditorTagsSource.val() == 'kpiTemplate') {
                
                postData['sourceId'] = $form.find('[data-path="id"]').val();
                postData['tagsSource'] = 'kpiTemplate';
                
            } else if ($expressionEditorTagsSource.val() == 'kpiIndicator') {
                
                postData['sourceId'] = $form.find('[data-path="id"]').val();
                postData['tagsSource'] = 'kpiIndicator';
            }
        } 
        
        if ((postData.fieldPath).indexOf('json') !== -1 || (postData.fieldPath).indexOf('otherattr') !== -1 || (postData.fieldPath).indexOf('nemgoo') !== -1) {
            postData['isJson'] = 1;
        }
        
        $.ajax({
            type: 'post',
            url: 'mdmeta/bpFieldExpressionEditor',
            data: postData,
            dataType: 'json',
            beforeSend: function() {
                if ($("link[href='assets/custom/addon/plugins/codemirror/lib/codemirror.v2.css']").length == 0) {
                    $('head').append('<link rel="stylesheet" type="text/css" href="assets/custom/addon/plugins/codemirror/lib/codemirror.v2.css"/>');
                }
            },
            success: function(data) {
                $dialog.empty().append(data.html);
                $dialog.dialog({
                    cache: false,
                    resizable: true,
                    bgiframe: true,
                    autoOpen: false,
                    title: data.title,
                    width: 1200,
                    minWidth: 1200,
                    height: "auto",
                    modal: false,
                    close: function () {
                        $dialog.empty().dialog('destroy').remove();
                    },
                    buttons: [
                        {text: plang.get('save_btn'), class: 'btn btn-sm green bp-btn-subsave', click: function() {
                            
                            PNotify.removeAll();
                            
                            fieldExpressionEditor.save();
                            var fieldExpressionVal = fieldExpressionEditor.getValue().trim();
                            
                            if (postData['isJson'] == 1 && fieldExpressionVal) {

                                try {
                                    JSON.parse(fieldExpressionVal);
                                    $this.val(fieldExpressionVal);
                                    $dialog.dialog('close');
                                } catch (e) {
                                    new PNotify({
                                        title: 'Error',
                                        text: e,
                                        type: 'error',
                                        addclass: pnotifyPosition,
                                        sticker: false
                                    });
                                }
                                
                            } else {
                                $this.val(fieldExpressionVal);
                                $dialog.dialog('close');
                            }
                        }},
                        {text: plang.get('close_btn'), class: 'btn btn-sm blue-hoki', click: function() {
                            $dialog.dialog('close');
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
                    }, 
                    "maximize" : function() { 
                        var dialogHeight = $dialog.height();
                        $dialog.find('.CodeMirror').css('height', dialogHeight+'px');
                        $dialog.find('#expressionPathList-scroll').css('max-height', dialogHeight+'px');
                    }, 
                    "restore" : function() { 
                        var dialogHeight = $dialog.height();
                        $dialog.find('.CodeMirror').css('height', dialogHeight+'px');
                        $dialog.find('#expressionPathList-scroll').css('max-height', dialogHeight+'px');
                    }
                });
                $dialog.dialog('open');
                $dialog.dialogExtend('maximize');
                
                Core.unblockUI();
            }
        });
    });
        
    return;
}
function bpVExpressionEditor(elem) {
    
    Core.blockUI({message: 'Loading...', boxed: true});
    var $dialogName = 'dialog-bpvExpressionEditor';
    if (!$("#" + $dialogName).length) {
        $('<div id="' + $dialogName + '"></div>').appendTo('body');
    }
    var $dialog = $('#' + $dialogName), 
        $this = $(elem).closest('.input-group').find('.vexpression_editorInit'), 
        $form = $this.closest('form'), 
        postData = '';
        //$expressionEditorTagsSource = $form.find('[data-path="expressionEditorTagsSource"]'), 
        if ($form.find('[data-path="'+$this.attr('data-path')+'Raw"]').length) {
            postData = {expressionJson: $form.find('[data-path="'+$this.attr('data-path')+'Raw"]').val()};
        }


        $.ajax({
            type: 'post',
            url: 'mdform/kpiExpressionVisual',
            data: postData,
            beforeSend: function() {
            },
            success: function(data) {
                $dialog.empty().append(data);
                $dialog.find('.flowchart-savebtn-row').hide();
                $dialog.dialog({
                    cache: false,
                    resizable: true,
                    bgiframe: true,
                    autoOpen: false,
                    title: 'Flowchart',
                    width: 1200,
                    minWidth: 1200,
                    height: "auto",
                    modal: false,
                    close: function () {
                        $dialog.empty().dialog('destroy').remove();
                    },
                    buttons: [
                        {text: plang.get('save_btn'), class: 'btn btn-sm green bp-btn-subsave', click: function() {         
                            saveVexpression(function(data){
                                if ($form.find('[data-path="'+$this.attr('data-path')+'Raw"]').length) {
                                    $form.find('[data-path="'+$this.attr('data-path')+'Raw"]').val(data.expressionJson);
                                } else {                                    
                                    new PNotify({
                                        title: 'Warning',
                                        text: $this.attr('data-path')+'Raw path үүсгэнэ үү!',
                                        type: 'warning',
                                        addclass: pnotifyPosition,
                                        sticker: false
                                    });
                                    return;                                    
                                }                   
                                $this.val(data.expressionString);    
                                // Core.blockUI({message: 'Loading...', boxed: true});
//                                 $.ajax({
//                                     type: 'post',
//                                     url: 'mdexpression/clientMicroFlowExpression',
//                                     data: {
//                                        expressionString: data.expressionString,
//                                        expressionStringJson: data.expressionJson         
//                                     },
//                                     beforeSend: function() {
//                                     },
//                                     success: function(data) {
// //                                        var path = $form.find('[data-path="columnNamePath"]').val();
// //                                        var eventString = $form.find('[data-path="eventString"]').val();
// //                                        var expString = '['+path+'].'+eventString+'(){';
// //                                            expString += data;
// //                                            expString += '}';
//                                         $this.val(data);
//                                         Core.unblockUI();
//                                     },
//                                     error: function() {
//                                         alert('Error');
//                                     }
//                                  });                                    
                            });                                                   
                            $dialog.dialog('close');
                        }},
                        {text: plang.get('close_btn'), class: 'btn btn-sm blue-hoki', click: function() {
                            $dialog.dialog('close');
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
                    }, 
                    "maximize" : function() { 
                        var dialogHeight = $dialog.height();
                        $dialog.find('.app-body').css('height', dialogHeight+'px');
                        $dialog.find('.inspector-container').css('height', (dialogHeight-165)+'px');
                        // $dialog.find('#expressionPathList-scroll').css('max-height', dialogHeight+'px');
                    }, 
                    "restore" : function() { 
                        var dialogHeight = $dialog.height();
                        $dialog.find('.app-body').css('height', dialogHeight+'px');
                        $dialog.find('.inspector-container').css('height', (dialogHeight-165)+'px');
                        // $dialog.find('#expressionPathList-scroll').css('max-height', dialogHeight+'px');
                    }
                });
                $dialog.dialog('open');
                $dialog.dialogExtend('maximize');
                
                Core.unblockUI();
            }
        });
        
    return;
}
function setBpIsRowReload(isRowReload, mainMetaDataId, processMetaDataId, selectedRow) {
    
    if (isRowReload == '1') {
        bpIsRowReloadParams[mainMetaDataId+'_'+processMetaDataId] = selectedRow;
    } else {
        delete bpIsRowReloadParams[mainMetaDataId+'_'+processMetaDataId];
    }
    
    return;
}
function dataViewRowReload(elem, dataGrid, dvMetaDataId, processMetaDataId) {
    if (bpIsRowReloadParams.hasOwnProperty(dvMetaDataId+'_'+processMetaDataId)) {
        
        var row = bpIsRowReloadParams[dvMetaDataId+'_'+processMetaDataId];
        var opt = dataGrid.datagrid('options');
        var postParams = opt.queryParams;
        var sortFields = getDataGridSortFields($('div#object-value-list-'+dvMetaDataId));
        
        postParams['filterRules'] = getDataViewFilterRules(dvMetaDataId, false);
        postParams['prevRow'] = row;
        postParams['isNotUseReport'] = 1;
        
        if (sortFields != '') {
            postParams['sortFields'] = sortFields;
        }
        
        $.ajax({
            type: 'post',
            url: 'mddatamodel/dataViewRowReload',
            data: postParams,
            dataType: 'json',
            async: false, 
            success: function(rowData) {
                
                if (rowData.status == 'success') {
                    
                    var $selectedRows = dataGrid.datagrid('getPanel').find('tr.datagrid-row-selected:eq(0)');
                    
                    if (opt.idField === null) {
                        dataGrid.datagrid('updateRow', {
                            index: $selectedRows.attr('datagrid-row-index'),
                            row: rowData.row
                        });
                    } else {
                        dataGrid.treegrid('update', {
                            id: row.id,
                            row: rowData.row
                        });
                    }
                    
                    Core.initFancybox($selectedRows);
                    Core.initPulsate($selectedRows);
                
                } else {
                    dataViewReloadByElement(dataGrid);
                }
            }
        });
        
        delete bpIsRowReloadParams[dvMetaDataId+'_'+processMetaDataId];
        
    } else {
        if (typeof dataGrid == 'undefined') {
            dataViewReloadByRowElement(elem);
        } else {
            dataViewReloadByElement(dataGrid);
        }
    }
    return;
}
function accountSegmentCriteria(elem) {
    var $this = $(elem), $parent = $this.closest('.input-group'), 
        $dialogName = 'dialog-acc-seg-creteria';    
    
    if ($parent.find('.' + $dialogName).length) {
        $parent.find('.' + $dialogName).dialog('open');
    } else {
        $parent.append('<div class="' + $dialogName + '"></div>');
        
        var $dialog = $parent.find('.' + $dialogName), path = '', isTextInput = false,     
            $pathElem = $parent.find('.meta-autocomplete-wrap:eq(0)');
        
        if ($pathElem.length == 0) {
            $pathElem = $parent.closest('.input-group-criteria');
            path = $pathElem.find('[data-path]').attr('data-path');
            isTextInput = true;
        } else {
            path = $pathElem.attr('data-section-path');
        }
        
        $.ajax({
            type: 'post',
            url: 'mdgl/accountSegmentCriteria',
            data: {path: path}, 
            dataType: 'json',
            beforeSend: function() {
                Core.blockUI({message: 'Loading...', boxed: true});
            },
            success: function(data) {
                $dialog.empty().append(data.html);
                $dialog.dialog({
                    appendTo: $parent,
                    cache: false,
                    resizable: false,
                    draggable: false,
                    bgiframe: true,
                    autoOpen: false,
                    title: data.title,
                    width: 550,
                    minWidth: 550,
                    height: 'auto',
                    modal: true,
                    open: function() {
                        $('.system-header:eq(0)').css('z-index', 1);
                        $('.ui-dialog:last').css('z-index', 104);
                        $('.ui-widget-overlay:last').css('z-index', 103);
                        
                        if ($this.closest('.height-dynamic').length) {
                            $this.closest('.height-dynamic').css('overflow-y', '');
                        }
                        
                        disableScrolling();
                    },
                    close: function() {
                        $('.system-header:eq(0)').css('z-index', 98);
                        
                        if ($this.closest('.height-dynamic').length) {
                            $this.closest('.height-dynamic').css('overflow-y', 'auto');
                        }
                        
                        enableScrolling();
                        
                        if (isTextInput) {
                            $pathElem.find('input[data-path]').val($dialog.find('input[name*="accountSegmentFullCode["]').val());
                        }
                    },
                    buttons: [
                        {text: data.close_btn, class: 'btn btn-sm blue-hoki bp-btn-save', click: function() {
                            $dialog.dialog('close');
                        }}
                    ]
                });
                $dialog.parent().draggable({handle: '.ui-dialog-titlebar'});
                $dialog.dialog('open');
                Core.unblockUI();
            },
            error: function() {
                alert("Error");
                Core.unblockUI();
            }
        });
    }
}
function bpFilePreview(elem) {
    var $this = $(elem), fileExtension = ''; 
    
    if ($this.hasAttr('data-fileurl') && $this.hasAttr('data-filename')) {
        if ($this.hasAttr('data-extension') && $this.attr('data-extension')) {
            fileExtension = $this.attr('data-extension'); 
        } else {
            fileExtension = ($this.attr('data-fileurl')).split('.').pop().toLowerCase();
        }
        var opts = {rowId: '', fileExtension: fileExtension, fileName: $this.attr('data-filename'), fullPath: URL_APP + $this.attr('data-fileurl'), contentId: ''};
        initFileViewer(elem, opts);
        return;
    }
    
    var fileUrl = $this.attr('data-url');
    var addin = getConfigValue('addinWebUrl') === '1' ? URL_APP : '../../../';
    if ($this.hasAttr('data-extension')) {
        fileExtension = $this.attr('data-extension'); 
    } else {
        fileExtension = fileUrl.split('.').pop().toLowerCase();
    }
    
    if (fileExtension == 'pdf') {
        
        var $dialogName = 'dialog-pdf-'+getUniqueId(1);
        var windowHeight = $(window).height();

        $('<div class="modal pl0 fade" id="'+ $dialogName +'" role="dialog" aria-hidden="true">'+
            '<div class="modal-dialog modal-lg" style="margin-top: 10px;">'+
                '<div class="modal-content">'+
            '<div class="modal-header">'+
                '<h4 class="modal-title">PDF preview</h4>'+
                '<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>'+
            '</div>'+
            '<div class="modal-body">'+
                '<iframe src="'+URL_APP+'api/pdf/web/viewer.html?file='+ addin + fileUrl +'" frameborder="0" style="width: 100%;height: 550px;"></iframe>'+
            '</div>'+
            '</div></div></div>').appendTo('body');

        var $dialog = $('#' + $dialogName);

        $dialog.find('iframe').css({'height': windowHeight - 90});

        $dialog.modal();
        $dialog.on('shown.bs.modal', function () {
            disableScrolling();
        });            
        $dialog.on('hidden.bs.modal', function () {
            $dialog.remove();
            enableScrolling();
        });            
            
    } else if (fileExtension == 'doc' || fileExtension == 'docx' || fileExtension == 'xls' || fileExtension == 'xlsx') {
        var fileName = '';
        var opts = {rowId: '1', fileExtension: fileExtension, fileName: fileName, fullPath: URL_APP + fileUrl, contentId: ''};
        initFileViewer(elem, opts);
        
    } else if (fileExtension == 'mp4') {
       
        $.fancybox.open({
            src: fileUrl,
            type: 'video',
            opts: {
                prevEffect: 'none',
                nextEffect: 'none',
                titlePosition: 'over',
                closeBtn: true,
                caption : function( instance, item ) {
                },
                // afterLoad: function() {
                //     this.title = '<a href="mdobject/downloadFile?file=' + (fileUrl).replace(URL_APP, '') + '&fDownload=1" target="_blank">Татах</a>';
                // },
                helpers: {
                    overlay: {
                        locked: false
                    }
                }
            }
        });
    } else {
       
        $.fancybox.open({
            src: fileUrl,
            type: 'image',
            opts: {
                prevEffect: 'none',
                nextEffect: 'none',
                titlePosition: 'over',
                closeBtn: true,
                caption : function( instance, item ) {
                    var caption = $(this).data('caption') || '';
                        caption = (caption.length ? caption + '<br />' : '') + '<a href="mdobject/downloadFile?file=' + (fileUrl).replace(URL_APP, '') + '&fDownload=1">Татах</a>' ;
                
                    return caption;
                },
                // afterLoad: function() {
                //     this.title = '<a href="mdobject/downloadFile?file=' + (fileUrl).replace(URL_APP, '') + '&fDownload=1" target="_blank">Татах</a>';
                // },
                helpers: {
                    overlay: {
                        locked: false
                    }
                }
            }
        });
    }
    return;
}
function viewGL(elem, paramData) {
    var $dialogName = 'dialog-view-gl-fromprocessbtn';
    if (!$('#' + $dialogName).length) {
        $('<div id="' + $dialogName + '"></div>').appendTo('body');
    }
    var $dialog = $('#' + $dialogName);    
    
    $.ajax({
        type: 'post',
        url: 'mdgl/view_entry', 
        data: {dialogMode: true, id: paramData.id},
        dataType: 'json',
        async: false,
        success: function(data){
            $dialog.empty().append(data.html);
            $dialog.dialog({
                cache: false,
                resizable: true,
                bgiframe: true,
                autoOpen: false,
                title: data.title,
                width: 1300, 
                height: 'auto',
                modal: true, 
                close: function () {
                    $dialog.empty().dialog('destroy').remove();
                },                                
                buttons: [
                    {text: data.close_btn, class: 'btn blue-madison btn-sm', click: function() {
                        $dialog.dialog('close');
                    }}
                ]
            });
            $dialog.dialog('open');            
        }
    });    
}
function bpNfcCardReader(elem) {
    Core.blockUI({boxed: true, message: 'Reading...'});
    
    if ("WebSocket" in window) {

        var ws = new WebSocket("ws://localhost:58324/socket");
        var $field = $(elem).closest('.input-group').find('input[type="text"]');
        
        ws.onopen = function () {
            var currentDateTime = GetCurrentDateTime();
            ws.send('{"command":"nfc_card_read", "dateTime":"' + currentDateTime + '"}');
        };

        ws.onmessage = function (evt) {
            var received_msg = evt.data;
            var jsonData = JSON.parse(received_msg);
            
            PNotify.removeAll();
            
            if ('details' in Object(jsonData)) {    
                
                var cardObj = convertDataElementToArray(jsonData.details);
                
                if (cardObj.hasOwnProperty('CardNumber') && cardObj.CardNumber) {
                    $field.val(cardObj.CardNumber).trigger('change');
                } else {
                    $field.val('').trigger('change');
                }
                
            } else {
                new PNotify({
                    title: 'Error',
                    text: jsonData.message, 
                    type: 'error',
                    sticker: false
                });
            }
            
            Core.unblockUI();
        };

        ws.onerror = function (event) {
            PNotify.removeAll();
            new PNotify({
                title: 'Error',
                text: plang.get('client_not_working'),
                type: 'error',
                sticker: false
            });
            Core.unblockUI();
        };

        ws.onclose = function () {
            console.log("Connection is closed...");
            Core.unblockUI();
        };
        
    } else {
        PNotify.removeAll();
        new PNotify({
            title: 'Warning',
            text: 'WebSocket NOT supported by your Browser!', 
            type: 'warning',
            sticker: false
        });
        Core.unblockUI();
    }
}
function bpMifareCardReader(elem) {
    Core.blockUI({boxed: true, message: 'Reading...'});
    
    if ("WebSocket" in window) {

        var ws = new WebSocket("ws://localhost:58324/socket");
        var $field = $(elem).closest('.input-group').find('input[type="text"]');
        
        ws.onopen = function () {
            var currentDateTime = GetCurrentDateTime();
            ws.send('{"command":"nfc_card_read", "dateTime":"' + currentDateTime + '"}');
        };

        ws.onmessage = function (evt) {
            var received_msg = evt.data;
            var jsonData = JSON.parse(received_msg);
            
            PNotify.removeAll();
            
            if ('details' in Object(jsonData)) {    
                
                var cardObj = convertDataElementToArray(jsonData.details);
                
                if (cardObj.hasOwnProperty('CardNumberMifare') && cardObj.CardNumberMifare) {
                    $field.val(cardObj.CardNumberMifare).trigger('change');
                } else {
                    $field.val('').trigger('change');
                }
                
            } else {
                new PNotify({
                    title: 'Error',
                    text: jsonData.message, 
                    type: 'error',
                    sticker: false
                });
            }
            
            Core.unblockUI();
        };

        ws.onerror = function (event) {
            PNotify.removeAll();
            new PNotify({
                title: 'Error',
                text: plang.get('client_not_working'),
                type: 'error',
                sticker: false
            });
            Core.unblockUI();
        };

        ws.onclose = function () {
            console.log("Connection is closed...");
            Core.unblockUI();
        };
        
    } else {
        PNotify.removeAll();
        new PNotify({
            title: 'Warning',
            text: 'WebSocket NOT supported by your Browser!', 
            type: 'warning',
            sticker: false
        });
        Core.unblockUI();
    }
}

function getInfoCitizenCard(elem, callbackfunction) {
    Core.blockUI({boxed: true, message: 'Reading...'});
    
    if ("WebSocket" in window) {

        var ws = new WebSocket("ws://localhost:58324/socket");
        
        ws.onopen = function () {
            var currentDateTime = GetCurrentDateTime();
            ws.send('{"command":"card_info", "dateTime":"' + currentDateTime + '", details: [{"key": "get_image", "value": "1"}]}');
        };

        ws.onmessage = function (evt) {
            var received_msg = evt.data;
            var jsonData = JSON.parse(received_msg);
            
            PNotify.removeAll();
            if (typeof saveFptLog !== 'undefined' && saveFptLog === '1') {
                var bpUniq = $(elem).closest('div.xs-form').attr('data-bp-uniq-id');
                if (typeof callbackfunction !== 'undefined') {
                    window[callbackfunction + '_' + bpUniq](jsonData);
                } else {
                    window['bpInfoCitizenCard_' + bpUniq](jsonData);
                }
            } else {
                if ('details' in Object(jsonData)) {    
                    var cardObj = convertDataElementToArray(jsonData.details);
                    var bpUniq = $(elem).closest('div.xs-form').attr('data-bp-uniq-id');
                    if (typeof callbackfunction !== 'undefined') {
                        window[callbackfunction + '_' + bpUniq](cardObj);
                    } else {
                        window['bpInfoCitizenCard_' + bpUniq](cardObj);
                    }
                    
                } else {
                    new PNotify({
                        title: 'Error',
                        text: jsonData.message, 
                        type: 'error',
                        sticker: false
                    });
                    
                }
            }

            Core.unblockUI();
        };

        ws.onerror = function (event) {
            var resultJson = {
                type: 'Error',
                message: plang.get('client_not_working'),
                description: plang.get('client_not_working'),
                error: event.code
            }
            if (typeof saveFptLog !== 'undefined' && saveFptLog === '1') {
                var bpUniq = $(elem).closest('div.xs-form').attr('data-bp-uniq-id');
                if (typeof callbackfunction !== 'undefined') {
                    window[callbackfunction + '_' + bpUniq](resultJson);
                } else {
                    window['bpInfoCitizenCard_' + bpUniq](resultJson);
                }
            }

            PNotify.removeAll();
            new PNotify({
                title: 'Error',
                text: plang.get('client_not_working'),
                type: 'error',
                sticker: false
            });
            Core.unblockUI();
        };

        ws.onclose = function () {
            console.log("Connection is closed...");
            Core.unblockUI();
        };
        
    } else {
        PNotify.removeAll();
        new PNotify({
            title: 'Warning',
            text: 'WebSocket NOT supported by your Browser!', 
            type: 'warning',
            sticker: false
        });
        Core.unblockUI();
    }
}
function renderConnectionOnTabMobi(elem, paramData) {
    if (typeof IS_LOAD_ASSET_MOBI_SCRIPT === 'undefined') {
        $.getScript(URL_APP+"middleware/assets/js/mobi/assets.js").done(function() {
            connectionAssets.initOnTab(elem, paramData);
        });
    } else {
        connectionAssets.initOnTab(elem, paramData);
    }  
}
function bpWebCamera (elem) {
    var $this = $(elem);
    var dialogName = '#dialog-bp-photo-webcam';
    if (!$(dialogName).length) {
        $('<div id="' + dialogName.replace('#', '') + '"></div>').appendTo('body');
    }
            
    $.ajax({
        type: 'post',
        url: 'mdprocess/bpAddPhotoFromWebcam',
        data: {},
        dataType: 'json',
        beforeSend: function(){
            Core.blockUI({animate: true});
        },
        success: function(data) {
            $(dialogName).empty().append(data.html);
            $(dialogName).dialog({
                cache: false,
                resizable: true,
                bgiframe: true,
                autoOpen: false,
                title: data.title,
                width: 1300,
                height: 'auto',
                modal: true, 
                close: function () { 
                    $(dialogName).empty().dialog('destroy').remove(); 
                }, 
                buttons: [
                    {text: 'Оруулах', class: 'btn blue-madison btn-sm', click: function () {
                        var base64img = $('form#bpWebcam-form').find('input[name="base64Photo"]').val();
                        var $thisParent = $this.parent();
                        
                        if ($thisParent.find('a').length) {
                            $thisParent.find('a').remove();
                        }
                        $thisParent.append('<a href="data:image/png;base64,'+base64img+'" class="fancybox-button main ml5" title="Зураг үзэх" data-rel="fancybox-button"><img src="data:image/png;base64,'+base64img+'" width="32" style="1px solid #6e6e6e"></a>');
                        $thisParent.find('input[type="hidden"]').val(base64img).trigger('change');
                        Core.initFancybox($thisParent);
                        $(dialogName).dialog('close');
                    }}
                ]
            });
            $(dialogName).dialog('open');                
            Core.unblockUI();
        },
        error: function() {
            alert('Error');
        }
    });
}
function bpViewQRcode(elem) {
    PNotify.removeAll();
    var $this = $(elem);
    var $thisParent = $this.parent();
    var qrcodeString = $thisParent.find('input[type="hidden"]').val();
    
    if (qrcodeString == '') {
        
        new PNotify({
            title: 'Анхааруулга',
            text: 'QRcode үүсээгүй байна!',
            type: 'warning',
            sticker: false
        });       
        
    } else {
        
        $.fancybox.open({
            src: 'data:image/png;base64,' + qrcodeString,
            type: 'image',
            opts: {
                prevEffect: 'none',
                nextEffect: 'none',
                titlePosition: 'over',
                closeBtn: true,
                helpers: {
                    overlay: {
                        locked: false
                    }
                }
            }
        });
    }
}
function bpFingerImageData(elem, paramPath, uniqId) {
    var $command = "finger_image";
    if (getConfigValue('IS_ZK_FINGERPRINT') === '1') {
        if (confirm("ZK хурууны хээ уншигчаар ажиллуулах уу?")) {
            $command = "finger_image_zk";
        }
    }

    if ("WebSocket" in window) {
        console.log("WebSocket is supported by your Browser!");
        var ws = new WebSocket("ws://localhost:58324/socket");
        $(elem).closest('#bp-window-'+uniqId).find('input[data-path="'+ paramPath +'"]').val('');
        ws.onopen = function () {
            var currentDateTime = GetCurrentDateTime();
            ws.send('{"command": "' + $command +'", "dateTime":"' + currentDateTime + '", details: []}');
        };

        ws.onmessage = function (evt) {
            var received_msg = evt.data;
            var jsonData = JSON.parse(received_msg);
            if (jsonData.status == 'success') {
                $.ajax({
                    type: 'post',
                    url: 'mddoc/tempFileSave',
                    data: {
                        finger: jsonData.details[0].value
                    },
                    beforeSend: function () {
                        Core.blockUI({ boxed: true, message: 'Хуруу хээг уншиж байна...' });
                    },
                    dataType: 'json',
                    success: function (data) {
                        $(elem).closest('#bp-window-'+uniqId).find('input[data-path="'+ paramPath +'"]').val(data.filePath);
                        Core.unblockUI();
                    },
                    error: function () {
                        Core.unblockUI();
                    }
                });
                
            } else {
                var resultJson = {
                    Status: 'Error',
                    Error: jsonData.message
                }

                new PNotify({
                    title: jsonData.status,
                    text: (jsonData.description !== 'undefined') ? jsonData.description : 'Амжилтгүй боллоо',
                    type: jsonData.status,
                    sticker: false
                });
                console.log(JSON.stringify(resultJson));
            }
        };

        ws.onerror = function (event) {
            var resultJson = {
                Status: 'Error',
                Error: event.code
            }
            
            PNotify.removeAll();
            new PNotify({
                title: 'warning',
                text: plang.get('client_not_working'),
                type: 'warning',
                sticker: false
            });
            
            console.log(JSON.stringify(resultJson));
        };

        ws.onclose = function () {
            console.log("Connection is closed...");
        };
    } else {
        var resultJson = {
            Status: 'Error',
            Error: "WebSocket NOT supported by your Browser!"
        }

        console.log(JSON.stringify(resultJson));
    }
}
function bpAfisControl(elem, paramPath1Val, paramPath2Val, typeCode, callbackfunction) {
    var bpUniqId = $(elem).closest('div.xs-form').attr('data-bp-uniq-id');
    Core.blockUI({boxed: true, message: 'Loading...'});
    
    try {
        switch (typeCode) {
            case 'afis_finger':
                if ("WebSocket" in window) {
                    console.log("WebSocket is supported by your Browser!");
                    // Let us open a web socket
                    var ws = new WebSocket("ws://localhost:2801/socket");

                    ws.onopen = function () {
                        var currentDateTime = GetCurrentDateTime();
                        ws.send('{"command":"afis_finger", "dateTime":"' + currentDateTime + '", details: [{"key": "civil_afis_id", "value": "'+ paramPath1Val +'"},{"key": "register_num", "value": "'+ paramPath2Val +'"}]}');
                    };

                    ws.onmessage = function (evt) {
                        var received_msg = evt.data;

                        var jsonData = JSON.parse(received_msg);
                        if (jsonData.status == 'success') {
                            if (typeof callbackfunction === 'function') {
                                callbackfunction(jsonData.details, typeCode);
                            } else if (typeof (window[callbackfunction + '_' + bpUniqId]) === 'function') {
                                if (typeof issavePath !== 'undefined' && issavePath === '1') {
                                    $.ajax({
                                        type: 'post',
                                        dataType: 'json',
                                        url: "mddoc/saveTempFileByAfisFinger",
                                        data: { details: jsonData.details },
                                        beforeSend: function () {
                                            Core.blockUI();
                                        },
                                        success: function (response) {
                                            window[callbackfunction + '_' + bpUniqId](response.details, typeCode, $(elem).closest('div.xs-form'));
                                            Core.unblockUI();
                                        },
                                        error: function (jqXHR, exception) {
                                            Core.unblockUI();
                                            Core.showErrorMessage(jqXHR, exception);
                                        }
                                    });
                                } else {
                                    window[callbackfunction + '_' + bpUniqId](jsonData.details, typeCode, $(elem).closest('div.xs-form'));
                                }
                            } else {
                                alert('function олдсонгүй');
                                console.log(callbackfunction, bpUniqId);
                            }
                            
                        }
                        else {
                            PNotify.removeAll();
                            new PNotify({
                                title: 'warning',
                                text: 'Клент ажиллуулахад алдаа гарлаа. {1}',
                                type: 'warning',
                                sticker: false
                            });
                        }
                        
                        Core.unblockUI();
                    };

                    ws.onerror = function (event) {
                        
                        PNotify.removeAll();
                        new PNotify({
                            title: 'warning',
                            text: plang.get('client_not_working'),
                            type: 'warning',
                            sticker: false
                        });
                        
                        Core.unblockUI();
                    };

                    ws.onclose = function () {
                        Core.unblockUI();
                        console.log("Connection is closed...");
                    };
                }
                else {
                    var resultJson = {
                        Status: 'Error',
                        Error: "WebSocket NOT supported by your Browser!"
                    }
                    return resultJson;
                }
                break;
            case 'afis_photo':
                if ("WebSocket" in window) {
                    console.log("WebSocket is supported by your Browser!");
                    // Let us open a web socket
                    var ws = new WebSocket("ws://localhost:2801/socket");

                    ws.onopen = function () {
                        var currentDateTime = GetCurrentDateTime();
                        ws.send('{"command":"afis_photo", "dateTime":"' + currentDateTime + '", details: [{"key": "civil_afis_id", "value": "'+ paramPath1Val +'"},{"key": "register_num", "value": "'+ paramPath2Val +'"}]}');
                    };

                    ws.onmessage = function (evt) {
                        var received_msg = evt.data;
                        var jsonData = JSON.parse(received_msg);

                        if (jsonData.status == 'success') {
                            if (typeof callbackfunction === 'function') {
                                callbackfunction(jsonData.details, typeCode);
                            } else if (typeof(window[callbackfunction + '_' + bpUniqId]) === 'function') {
                                if (typeof issavePath !== 'undefined' && issavePath === '1') {
                                    $.ajax({
                                        type: 'post',
                                        dataType: 'json',
                                        url: "mddoc/saveTempFileByAfisPhoto",
                                        data: { details: jsonData.details },
                                        beforeSend: function () {
                                            Core.blockUI();
                                        },
                                        success: function (response) {
                                            window[callbackfunction + '_' + bpUniqId](response.details, typeCode, $(elem).closest('div.xs-form'));
                                            Core.unblockUI();
                                        },
                                        error: function (jqXHR, exception) {
                                            Core.unblockUI();
                                            Core.showErrorMessage(jqXHR, exception);
                                        }
                                    });
                                } else {
                                    window[callbackfunction + '_' + bpUniqId](jsonData.details, typeCode, $(elem).closest('div.xs-form'));
                                }
                            } else  {
                                alert('function олдсонгүй');
                                console.log(callbackfunction, bpUniqId);
                            }
                        }
                        else {
                            PNotify.removeAll();
                            new PNotify({
                                title: 'warning',
                                text: 'Клент ажиллуулахад алдаа гарлаа. {1}',
                                type: 'warning',
                                sticker: false
                            });
                        }
                        
                        Core.unblockUI();
                    };

                    ws.onerror = function (event) {
                        PNotify.removeAll();
                        new PNotify({
                            title: 'warning',
                            text: plang.get('client_not_working'),
                            type: 'warning',
                            sticker: false
                        });
                        
                        Core.unblockUI();
                    };

                    ws.onclose = function () {
                        Core.unblockUI();
                        console.log("Connection is closed...");
                    };
                }
                else {
                    var resultJson = {
                        Status: 'Error',
                        Error: "WebSocket NOT supported by your Browser!"
                    }
                    return resultJson;
                }
                break;
            default:
                var resultJson = {
                    Status: 'Error',
                    Error: "No control!"
                }
                console.log(resultJson);
                break;
        }   
    } catch (e) {
        var resultJson = {
            Status: 'Error',
            Error: e
        }

        console.log(resultJson);
    }
}
function initBpColorPicker(elem) {
    var $this = $(elem);
    if (typeof $.fn.colorpicker === 'undefined') {
        $.cachedScript("assets/custom/addon/plugins/bootstrap-colorpicker/js/bootstrap-colorpicker.js").done(function() {
            $("head").append('<link rel="stylesheet" type="text/css" href="assets/custom/addon/plugins/bootstrap-colorpicker/css/colorpicker.css"/>');    
            $this.closest(".bp-color-picker").colorpicker({
                format: 'hex'
            });
            $this.closest(".bp-color-picker").colorpicker('show');
        });            
    } else {
        $this.closest(".bp-color-picker").colorpicker({
            format: 'hex'
        });
        $this.closest(".bp-color-picker").colorpicker('show');        
    }
}
function timerAction(elem, other) {
    var $this = $(elem);
    var bpUniq = $this.closest('div[data-bp-uniq-id]').attr('data-bp-uniq-id');
    var type = $this.attr('data-type'), 
        $getInput = $this.parent().parent(),
        getPath = $getInput.find('input[type="hidden"]').attr('data-path');

    if (type === 'pause') {
        $getInput.find('.timerInit').countdown('pause');
        if (typeof window['timerPause_'+bpUniq] === 'function') {
            window['timerPause_'+bpUniq](getPath);
        }
        // $this.attr({'data-type': 'play', 'title': 'Үргэлжлүүлэх'});
        // $this.html('<i class="fa fa-play"></i>');
    } else if (type === 'play') {
        $getInput.find('.timerInit').countdown('resume');
        if (typeof window['timerPlay_'+bpUniq] === 'function') {
            window['timerPlay_'+bpUniq](getPath);
        }
        // $this.attr({'data-type': 'pause', 'title': 'Зогсоох'});
        // $this.html('<i class="fa fa-pause"></i>');
    } else if (type === 'start') {
        if ($getInput.find('input[type="hidden"]').val() == '-1') {
            $getInput.find('.timerInit').countdown('option', {since: 0});
        } else {
            $getInput.find('.timerInit').countdown('option', {until: +($getInput.find('input[type="hidden"]').val())}); 
        }
        if (typeof window['timerStart_'+bpUniq] === 'function') {
            window['timerStart_'+bpUniq](getPath);
        }
    } else if (type === 'stop') {
        $getInput.find('.timerInit').countdown('pause');
        if (typeof window['timerStop_'+bpUniq] === 'function') {
            window['timerStop_'+bpUniq](getPath);
        }
    }
}
function bpDtlMultiFileRemove(elem) {
    var $this = $(elem), $row = $this.closest('.btn-group'), rowId = $this.attr('data-id');
    var $cell = $this.closest('[data-cell-path]');
    var $cellInput = $cell.find('input[type="file"]:eq(0)');
    var name = $cellInput.attr('name');
    var path = name.replace('[]', '[removeFiles][]');
    
    $row.addClass('hidden');
    $row.append('<input type="hidden" name="'+path+'" value="'+rowId+'">');
    
    return;
}
function kpiDtlMultiFileRemove(elem) {
    var $this = $(elem);
    var $cell = $this.closest('[data-cell-path]');
    var $row = $this.closest('.btn-group');
    var $hidden = $cell.find('input[type="hidden"]');
    var prevPaths = $hidden.val();
    var thisUrl = $row.find('[data-url]').attr('data-url');
    
    prevPaths = prevPaths.replace(thisUrl+',', '').replace(','+thisUrl, '').replace(thisUrl, '');
    $hidden.val(prevPaths);
    $row.hide();
}
function bpKpiObjectSubTemplate(elem, templateId) {
    var $this = $(elem);
    var $cell = $this.parent();
    var $row = $this.closest('tr');
    var $dialogCell = $cell.find('.object-subkpi-dialog');
    var bookId = '';
    
    if ($cell.hasAttr('data-dmmartid') && $cell.data('dmmartid')) {
        bookId = $cell.data('dmmartid');
    }
    
    if (!$dialogCell.length) {        
        
        var postData = {
            templateId: templateId, 
            uniqId:     getUniqueId(1), 
            bookId:     bookId, 
            methodId:   $this.closest('div[data-process-id]').attr('data-process-id'), 
            viewMode:   'grid', 
            groupPath:  'kpObject.'
        };
        
        if ($row.hasAttr('data-relationid') && $row.attr('data-relationid')) {
            postData['bookId'] = $row.attr('data-relationid');
        }
        
        $.ajax({
            type: 'post',
            url: 'mdform/subKpiForm',
            data: postData,
            dataType: 'json',
            beforeSend: function() {
                Core.blockUI({message: 'Loading...', boxed: true});
            },
            success: function(data) {
                
                if (data.status == 'success') {
                    
                    $cell.append('<div class="hide object-subkpi-dialog"></div>');
                    var $dialog = $cell.find('.object-subkpi-dialog');
                    $dialog.append('<div class="row">'+data.html+'</div>');
                    
                    var $parent = $(elem).closest('.main-action-meta').parent();
                    var $centerSidebar = $parent.find("div.center-sidebar");
                    var $tabContent = $parent.find("form > .tabbable-line > .tab-content");
                    
                    $parent.css('position', 'static');
                    $centerSidebar.css('position', 'static');
                    $parent.parent().css('overflow', 'inherit');
                    $centerSidebar.parent().css('overflow', 'inherit');
                    $tabContent.css('overflow', 'inherit');
                    
                    $cell.closest('div[data-parent-path="kpiDmDtl"]').css('overflow', 'inherit');
                    $cell.parents('.content-wrapper').css('overflow', 'inherit');
                    $cell.closest('div.col-md-12').css('position', 'static');
            
                    $dialog.dialog({
                        appendTo: $cell,
                        cache: false,
                        resizable: true,
                        draggable: false,
                        bgiframe: true,
                        autoOpen: false,
                        dialogClass: 'sub-kpi-form',
                        title: 'Sub form',
                        width: 800, 
                        height: 'auto',
                        maxHeight: $(window).height() - 10, 
                        modal: true, 
                        closeOnEscape: isCloseOnEscape, 
                        close: function () { 
                            
                            PNotify.removeAll();
                            
                            bpSetIndexKpiObjDetail($this);
                            
                            $parent.css('position', '');
                            $centerSidebar.css('position', '');
                            $parent.parent().css('overflow', '');
                            $centerSidebar.parent().css('overflow', 'auto');
                            $tabContent.css('overflow', 'hidden auto');
                            
                            $cell.closest('div[data-parent-path="kpiDmDtl"]').css('overflow', '');
                            $cell.parents('.content-wrapper').css('overflow', '');
                            $cell.closest('div.col-md-12').css('position', '');
                        },                                
                        buttons: [
                            {text: plang.get('save_btn'), class: 'btn btn-sm green-meadow', click: function() {

                                var validDtl = true;
                                var $formElements = $dialog.find('input,textarea,select').filter('[required="required"]');
                                
                                $formElements.removeClass('error');

                                $formElements.each(function(){
                                    var $elThis = $(this);
                                    if (($elThis.attr('id') != 'accountId_displayField' && $elThis.attr('id') != 'accountId_nameField') && $elThis.val() == '') {
                                        $elThis.addClass('error');  
                                        validDtl = false;
                                    }
                                });

                                if (validDtl) {
                                    
                                    $dialog.dialog('close');
                                    
                                } else {
                                    PNotify.removeAll();
                                    new PNotify({
                                        title: 'Warning',
                                        text: 'Дэлгэрэнгүй үзүүлэлтийг бүрэн бөглөнө үү!',
                                        type: 'warning',
                                        addclass: pnotifyPosition,
                                        sticker: false
                                    });
                                }
                            }}
                        ]
                    });
                    
                    $dialog.parent().draggable();    
                    $dialog.dialog('open');
                    
                } else {
                    PNotify.removeAll();
                    new PNotify({
                        title: data.status,
                        text: data.message,
                        type: data.status,
                        addclass: pnotifyPosition,
                        sticker: false
                    });
                }
            },
            error: function () {
                alert("Error");
                Core.unblockUI();
            }
        }).done(function() {
            Core.unblockUI();
        });   
        
    } else {
        
        var $parent = $(elem).closest('.main-action-meta').parent();
        var $centerSidebar = $parent.find("div.center-sidebar");
        var $tabContent = $parent.find("form > .tabbable-line > .tab-content");
        
        $parent.css('position', 'static');
        $centerSidebar.css('position', 'static');
        $parent.parent().css('overflow', 'inherit');
        $centerSidebar.parent().css('overflow', 'inherit');
        $tabContent.css('overflow', 'inherit');
        
        $cell.closest('div[data-parent-path="kpiDmDtl"]').css('overflow', 'inherit');
        $cell.parents('.content-wrapper').css('overflow', 'inherit');
        $cell.closest('div.col-md-12').css('position', 'static');

        $dialogCell.dialog({
            appendTo: $cell,
            cache: false,
            resizable: true,
            draggable: false,
            bgiframe: true,
            autoOpen: false,
            dialogClass: 'sub-kpi-form',
            title: 'Sub form',
            width: 800, 
            height: 'auto',
            maxHeight: $(window).height() - 10, 
            modal: true, 
            closeOnEscape: isCloseOnEscape, 
            close: function () { 
                
                PNotify.removeAll();
                
                $parent.css('position', '');
                $centerSidebar.css('position', '');
                $parent.parent().css('overflow', '');
                $centerSidebar.parent().css('overflow', 'auto');
                $tabContent.css('overflow', 'hidden auto');
                
                $cell.closest('div[data-parent-path="kpiDmDtl"]').css('overflow', '');
                $cell.parents('.content-wrapper').css('overflow', '');
                $cell.closest('div.col-md-12').css('position', '');
            },                                
            buttons: [
                {text: plang.get('save_btn'), class: 'btn btn-sm green-meadow', click: function() {

                    var validDtl = true;
                    var $formElements = $dialogCell.find('input,textarea,select').filter('[required="required"]');
                    
                    $formElements.removeClass('error');

                    $formElements.each(function() {
                        var $elThis = $(this);
                        if (($elThis.attr('id') != 'accountId_displayField' && $elThis.attr('id') != 'accountId_nameField') && $elThis.val() == '') {
                            $elThis.addClass('error');  
                            validDtl = false;
                        }
                    });

                    if (validDtl) {
                        
                        $dialogCell.dialog('close');

                    } else {
                        PNotify.removeAll();
                        new PNotify({
                            title: 'Warning',
                            text: 'Дэлгэрэнгүй үзүүлэлтийг бүрэн бөглөнө үү!',
                            type: 'warning',
                            addclass: pnotifyPosition,
                            sticker: false
                        });
                    }
                }}
            ]
        });

        $dialogCell.parent().draggable();    
        $dialogCell.dialog('open');
    }
}
function bpSetIndexKpiObjDetail(elem) {
    var $row = elem.closest('tr'),  
        $el = $row.find('tr[data-is-input="1"]'), 
        parentIndex = $row.closest('table').attr('data-kpi-index'), 
        rowId = $row.attr('data-basketrowid'), 
        len = $el.length, i = 0, 
        $pfKpiTemplateId = $row.find('input[name="param[pfKpiTemplateId][]"]');    
    
    if ($pfKpiTemplateId.length) {
        $pfKpiTemplateId.attr('name', 'param[pfKpiTemplateId]['+parentIndex+']['+rowId+'][]');
        $row.find('input[name="param[pfKpiTemplateCode][]"]').attr('name', 'param[pfKpiTemplateCode]['+parentIndex+']['+rowId+'][]');
    }
    
    for (i; i < len; i++) { 
        var $subElement = $($el[i]).find('input[data-path], select[data-path], textarea[data-path]');
        var slen = $subElement.length, j = 0;
        for (j; j < slen; j++) { 
            var $inputThis = $($subElement[j]);
            var $inputName = $inputThis.attr('name');
            $inputThis.attr('name', $inputName.replace(/^(.*)(\[[0-9]+\])(\[\])$/, '$1['+parentIndex+']['+rowId+']$2$3'));
        }
    }
    
    return;
}
function bpSetIndexKpiFieldDetail(elem, indicatorId, isKpiDmDtl) {
    var $row = elem.closest('tr'),  
        $el = $row.find('tr[data-is-input="1"]'), 
        len = $el.length, i = 0;
    
    if (isKpiDmDtl) {
        
        var mainRowIndex = $row.attr('data-row-index');
        
        for (i; i < len; i++) { 
            var $subElement = $($el[i]).find('input[data-path], select[data-path], textarea[data-path]');
            var slen = $subElement.length, j = 0;
            for (j; j < slen; j++) { 
                var $inputThis = $($subElement[j]);
                var $inputName = $inputThis.attr('name');
                $inputThis.attr('name', $inputName.replace(/^(.*)(\[[0-9]+\])(\[\])$/, '$1['+mainRowIndex+']$3'));
            }
        }
        
    } else {
        
        var $pfKpiTemplateId = $row.find('input[name="param[pfKpiTemplateId][]"]');    
    
        if ($pfKpiTemplateId.length) {
            $pfKpiTemplateId.attr('name', 'param[fieldKpiTemplateId]['+indicatorId+'][]');
            $row.find('input[name="param[pfKpiTemplateCode][]"]').attr('name', 'param[fieldKpiTemplateCode]['+indicatorId+'][]');
        }

        for (i; i < len; i++) { 
            var $subElement = $($el[i]).find('input[data-path], select[data-path], textarea[data-path]');
            var slen = $subElement.length, j = 0;
            for (j; j < slen; j++) { 
                var $inputThis = $($subElement[j]);
                var $inputName = $inputThis.attr('name');
                $inputThis.attr('name', $inputName.replace(/^(.*)(\[[0-9]+\])(\[\])$/, '$1['+indicatorId+']$2$3'));
            }
        }
    }
    
    return;
}
function bpKpiObjectSubSubTemplate(elem, templateId) {
    var $this = $(elem);
    var $cell = $this.parent();
    var $row = $this.closest('tr');
    var bookId = '';
    var parentIndex = $row.closest('table').attr('data-kpi-index');
    var rowId = $row.attr('data-basketrowid');
    var uniqId = $this.attr('data-uniqid');
    var $dialogName = 'dialog-'+uniqId+'-'+parentIndex+'-'+rowId;
    
    if ($cell.hasAttr('data-dmmartid') && $cell.data('dmmartid')) {
        bookId = $cell.data('dmmartid');
    }
    
    if ($('#'+$dialogName).length) {

        $('#'+$dialogName).dialog('open');
        
    } else {
        
        var postData = {
            templateId: templateId, 
            uniqId:     getUniqueId(1), 
            bookId:     bookId, 
            methodId:   $this.closest('div[data-process-id]').attr('data-process-id'), 
            viewMode:   'grid', 
            groupPath:  ''
        };
        
        if ($row.hasAttr('data-relationid') && $row.attr('data-relationid')) {
            postData['bookId'] = $row.attr('data-relationid');
        }
        
        $.ajax({
            type: 'post',
            url: 'mdform/subKpiForm',
            data: postData,
            dataType: 'json',
            beforeSend: function() {
                Core.blockUI({message: 'Loading...', boxed: true});
            },
            success: function(data) {
                
                if (data.status == 'success') {
                    
                    $('<div id="' + $dialogName + '"></div>').appendTo('body');
                    var $dialog = $('#' + $dialogName);
                    
                    $dialog.dialog({
                        cache: false,
                        resizable: true,
                        draggable: true,
                        bgiframe: true,
                        autoOpen: false,
                        dialogClass: 'sub-kpi-form',
                        title: 'Sub sub form',
                        width: 700, 
                        height: 'auto',
                        maxHeight: $(window).height() - 10, 
                        modal: true, 
                        closeOnEscape: isCloseOnEscape, 
                        create: function() {
                            $dialog.empty().append(data.html);
                        }, 
                        open: function() {
                            $dialog.closest('.ui-dialog').css('zIndex', 102).nextAll('.ui-widget-overlay:first').css('zIndex', 101);
                        }, 
                        close: function() { 
                            PNotify.removeAll();
                        },                                
                        buttons: [
                            {text: plang.get('save_btn'), class: 'btn btn-sm green-meadow', click: function() {

                                var validDtl = true;
                                var $formElements = $dialog.find('input,textarea,select');
                                var $formElementsRequired = $formElements.filter('[required="required"]');
                                
                                $formElementsRequired.removeClass('error');

                                $formElementsRequired.each(function(){
                                    if (($(this).attr('id') != 'accountId_displayField' && $(this).attr('id') != 'accountId_nameField') && $(this).val() == '') {
                                        $(this).addClass('error');  
                                        validDtl = false;
                                    }
                                });

                                if (validDtl) {
                                    
                                    $cell.find('textarea').remove();
                                    $cell.append('<textarea name="param[subKpiParams]['+parentIndex+'][]" class="d-none" data-path="subKpiParams">'+encodeURIComponent($formElements.serialize())+'</textarea>');
                                    
                                    $dialog.dialog('close');
                                    
                                } else {
                                    PNotify.removeAll();
                                    new PNotify({
                                        title: 'Warning',
                                        text: 'Дэлгэрэнгүй үзүүлэлтийг бүрэн бөглөнө үү!',
                                        type: 'warning',
                                        addclass: pnotifyPosition,
                                        sticker: false
                                    });
                                }
                            }}
                        ]
                    });
                    
                    $dialog.dialog('open');
                    
                } else {
                    PNotify.removeAll();
                    new PNotify({
                        title: data.status,
                        text: data.message,
                        type: data.status,
                        addclass: pnotifyPosition,
                        sticker: false
                    });
                }
            },
            error: function() { alert("Error"); Core.unblockUI(); }
            
        }).done(function() { Core.unblockUI(); });   
    }
}
function bpFieldSubKpiForm(elem, rootTemplateId, templateId, bookId, isMart) {
    var $this = $(elem);
    var $cell = $this.closest('td');
    var $row = $this.closest('tr');
    var $dialogCell = $cell.find('.field-subkpi-dialog');
    var rootIndicatorId = $row.find('[data-path="kpiDmDtl.indicatorId"]').val();
    
    if (!$dialogCell.length) {        
        
        var $bpContainer = $this.closest('div[data-process-id]');
        var $subTmpIndctrByCriteria = $bpContainer.find('[data-path="kpiSubTemplateIndicatorByCriteria"]');
        var isKpiDmDtl = false;
        
        var postData = {
            rootTemplateId: rootTemplateId, 
            templateId:     templateId, 
            uniqId:         getUniqueId(1), 
            bookId:         bookId, 
            methodId:       $bpContainer.attr('data-process-id'), 
            indicatorId:    rootIndicatorId, 
            subKpiDmDtl:    1,
            viewMode:       'grid', 
            groupPath:      'field.'
        };
        
        if (typeof isMart !== 'undefined' && isMart != '2') {
            postData.groupPath = 'kpiDmDtl.';
            isKpiDmDtl = true;
        }
        
        if ($subTmpIndctrByCriteria.length && $subTmpIndctrByCriteria.val() != '') {
            postData.subTmpIndctrByCriteria = $subTmpIndctrByCriteria.val();
        }
        
        $.ajax({
            type: 'post',
            url: 'mdform/subKpiForm',
            data: postData,
            dataType: 'json',
            beforeSend: function() {
                Core.blockUI({message: 'Loading...', boxed: true});
            },
            success: function(data) {
                
                if (data.status == 'success') {
                    
                    $cell.append('<div class="hide field-subkpi-dialog"></div>');
                    var $dialog = $cell.find('.field-subkpi-dialog');
                    $dialog.append('<div class="row">'+data.html+'</div>');
                    
                    var $parent = $this.closest('.main-action-meta').parent();
                    var $centerSidebar = $parent.find("div.center-sidebar");
                    var $tabContent = $parent.find("form > .tabbable-line > .tab-content");
                    var dialogWidth = 800;
                    
                    $parent.css('position', 'static');
                    $centerSidebar.css('position', 'static');
                    $parent.parent().css('overflow', 'inherit');
                    $centerSidebar.parent().css('overflow', 'inherit');
                    $tabContent.css('overflow', 'inherit');
                    
                    $cell.closest('div[data-parent-path="kpiDmDtl"]').addClass('overflow-inherit');
                    $cell.parents('.content-wrapper').css('overflow', 'inherit');
                    $cell.closest('div.col-md-12').css('position', 'static');
                    
                    var $parentDialog = $this.closest('.ui-dialog-content'), $wsArea = $this.closest('.ws-area');
                    
                    if ($parentDialog.length) {  
                        $parentDialog.css({'position': 'static', 'overflow': 'inherit'});       
                        $parentDialog.parent().css('overflow', 'inherit');
                    }
                    
                    if ($wsArea.length) {
                        $wsArea.css('overflow', 'unset');
                        $wsArea.find('.workspace-main, .ws-page-content, .workspace-main-container, .workspace-part').css('overflow', 'unset');
                    }
                    
                    if ($cell.css('z-index') != 'auto' && $cell.css('position') == 'relative') {
                        $cell.css('z-index', 99);
                    }
                    
                    if (data.hasOwnProperty('width') && data.width) {
                        dialogWidth = data.width;
                        $this.attr('data-dialog-width', dialogWidth);
                    }
            
                    $dialog.dialog({
                        appendTo: $cell,
                        cache: false,
                        resizable: true,
                        draggable: false,
                        bgiframe: true,
                        autoOpen: false,
                        dialogClass: 'sub-kpi-form',
                        title: 'Sub form', 
                        width: dialogWidth, 
                        height: 'auto',
                        maxHeight: $(window).height() - 10, 
                        modal: true, 
                        closeOnEscape: isCloseOnEscape, 
                        close: function () { 
                            
                            PNotify.removeAll();
                            
                            bpSetIndexKpiFieldDetail($this, rootIndicatorId, isKpiDmDtl);
                            
                            if ($parentDialog.length) {      
                                $parentDialog.css({'position': '', 'overflow': ''});       
                                $parentDialog.parent().css('overflow', '');
                            }
                            
                            $parent.css('position', '');
                            $centerSidebar.css('position', '');
                            $parent.parent().css('overflow', '');
                            $centerSidebar.parent().css('overflow', 'auto');
                            $tabContent.css('overflow', 'hidden auto');
                            
                            $cell.closest('div[data-parent-path="kpiDmDtl"]').removeClass('overflow-inherit');
                            $cell.parents('.content-wrapper').css('overflow', '');
                            $cell.closest('div.col-md-12').css('position', '');
                            
                            if ($cell.css('z-index') == '99' && $cell.css('position') == 'relative') {
                                $cell.css('z-index', 9);
                            }
                            
                            if ($wsArea.length) {
                                $wsArea.css('overflow', '');
                                $wsArea.find('.workspace-main, .ws-page-content, .workspace-main-container, .workspace-part').css('overflow', '');
                            }
                        },                                
                        buttons: [
                            {text: plang.get('save_btn'), class: 'btn btn-sm green-meadow', click: function() {

                                var validDtl = true;
                                var $formElements = $dialog.find('input,textarea,select').filter('[required="required"]');
                                
                                $formElements.removeClass('error');

                                $formElements.each(function() {
                                    var $elThis = $(this);
                                    if (($elThis.attr('id') != 'accountId_displayField' && $elThis.attr('id') != 'accountId_nameField') && $elThis.val() == '') {
                                        $elThis.addClass('error');  
                                        validDtl = false;
                                    }
                                });

                                if (validDtl) {
                                    
                                    $this.trigger('change');
                                    $dialog.dialog('close');
                                    
                                } else {
                                    PNotify.removeAll();
                                    new PNotify({
                                        title: 'Warning',
                                        text: 'Дэлгэрэнгүй үзүүлэлтийг бүрэн бөглөнө үү!',
                                        type: 'warning',
                                        addclass: pnotifyPosition,
                                        sticker: false
                                    });
                                }
                            }}
                        ]
                    });
                    
                    $dialog.parent().draggable();    
                    $dialog.dialog('open');
                    
                } else {
                    PNotify.removeAll();
                    new PNotify({
                        title: data.status,
                        text: data.message,
                        type: data.status,
                        addclass: pnotifyPosition,
                        sticker: false
                    });
                }
            },
            error: function () {
                alert("Error");
                Core.unblockUI();
            }
        }).done(function() {
            Core.unblockUI();
        });   
        
    } else {
        
        var $parent = $this.closest('.main-action-meta').parent();
        var $centerSidebar = $parent.find('div.center-sidebar');
        var $tabContent = $parent.find("form > .tabbable-line > .tab-content");
        var dialogWidth = 800;
        
        $parent.css('position', 'static');
        $centerSidebar.css('position', 'static');
        $parent.parent().css('overflow', 'inherit');
        $centerSidebar.parent().css('overflow', 'inherit');
        $tabContent.css('overflow', 'inherit');
        
        $cell.closest('div[data-parent-path="kpiDmDtl"]').addClass('overflow-inherit');
        $cell.parents('.content-wrapper').css('overflow', 'inherit');
        $cell.closest('div.col-md-12').css('position', 'static');
        
        var $parentDialog = $this.closest('.ui-dialog-content'), $wsArea = $this.closest('.ws-area');
                    
        if ($parentDialog.length) {  
            $parentDialog.css({'position': 'static', 'overflow': 'inherit'});         
            $parentDialog.parent().css('overflow', 'inherit');
        }
        
        if ($wsArea.length) {
            $wsArea.css('overflow', 'unset');
            $wsArea.find('.workspace-main, .ws-page-content, .workspace-main-container, .workspace-part').css('overflow', 'unset');
        }
        
        if ($cell.css('z-index') != 'auto' && $cell.css('position') == 'relative') {
            $cell.css('z-index', 99);
        }
                    
        if ($this.hasAttr('data-dialog-width') && $this.attr('data-dialog-width')) {
            dialogWidth = $this.attr('data-dialog-width');
        }

        $dialogCell.dialog({
            appendTo: $cell,
            cache: false,
            resizable: true,
            draggable: false,
            bgiframe: true,
            autoOpen: false,
            dialogClass: 'sub-kpi-form',
            title: 'Sub form',
            width: dialogWidth, 
            height: 'auto',
            maxHeight: $(window).height() - 10, 
            modal: true, 
            closeOnEscape: isCloseOnEscape, 
            close: function () { 
                
                PNotify.removeAll();
                
                if ($parentDialog.length) {  
                    $parentDialog.css({'position': '', 'overflow': ''});           
                    $parentDialog.parent().css('overflow', '');
                }
                
                $parent.css('position', '');
                $centerSidebar.css('position', '');
                $parent.parent().css('overflow', '');
                $centerSidebar.parent().css('overflow', 'auto');
                $tabContent.css('overflow', 'hidden auto');
                
                $cell.closest('div[data-parent-path="kpiDmDtl"]').removeClass('overflow-inherit');
                $cell.parents('.content-wrapper').css('overflow', '');
                $cell.closest('div.col-md-12').css('position', '');
                
                if ($cell.css('z-index') == '99' && $cell.css('position') == 'relative') {
                    $cell.css('z-index', 9);
                }
                
                if ($wsArea.length) {
                    $wsArea.css('overflow', '');
                    $wsArea.find('.workspace-main, .ws-page-content, .workspace-main-container, .workspace-part').css('overflow', '');
                }
            },                                
            buttons: [
                {text: plang.get('save_btn'), class: 'btn btn-sm green-meadow', click: function() {

                    var validDtl = true;
                    var $formElements = $dialogCell.find('input,textarea,select').filter('[required="required"]');
                    
                    $formElements.removeClass('error');

                    $formElements.each(function() {
                        var $elThis = $(this);
                        if (($elThis.attr('id') != 'accountId_displayField' && $elThis.attr('id') != 'accountId_nameField') && $elThis.val() == '') {
                            $elThis.addClass('error');  
                            validDtl = false;
                        }
                    });

                    if (validDtl) {
                        
                        $this.trigger('change');
                        $dialogCell.dialog('close');

                    } else {
                        PNotify.removeAll();
                        new PNotify({
                            title: 'Warning',
                            text: 'Дэлгэрэнгүй үзүүлэлтийг бүрэн бөглөнө үү!',
                            type: 'warning',
                            addclass: pnotifyPosition,
                            sticker: false
                        });
                    }
                }}
            ]
        });

        $dialogCell.parent().draggable();    
        $dialogCell.dialog('open');
    }
}
function bpBasketDvWithPopupCombo(elem) {
    var $this = $(elem), 
        $parent = $this.closest('.input-group'), 
        lookupId = $this.attr('data-lookupid'), 
        paramCode = $this.attr('data-paramcode'), 
        processId = $this.attr('data-processid'), 
        chooseType = $this.attr('data-choosetype'), 
        $elem = $parent.find('select'), 
        path = $elem.attr('data-path');
    
    dataViewSelectableGrid(paramCode, processId, lookupId, chooseType, path, $elem, 'bpBasketDvWithPopupComboFill');
}
function bpRemoveAllBasketWithPopupCombo(elem) {
    var $this = $(elem), $parent = $this.closest('.input-group'), $button = $parent.find('button');
    
    if ($button.hasAttr('data-buttontext')) {
        $button.text($button.attr('data-buttontext'));
    } else {
        $button.text('..');
    }
        
    $this.html('<i class="fa fa-trash"></i>');
    $parent.find('select').select2('val', '');
    $this.hide();
}
function bpBasketDvWithPopupComboFill(metaDataCode, processMetaDataId, chooseType, elem, rows, paramRealPath, lookupMetaDataId, isMetaGroup) {
    var $this = elem, 
        $parent = $this.closest('.input-group'),
        $button = $parent.find('button'),
        idField = $button.data('idfield').toLowerCase(),
        nameField = $button.data('namefield').toLowerCase(), 
        idVal = '', nameVal = '';
    
    $this.val('').trigger('change');
    
    var oldOptions = $this.html(), options = oldOptions;
    
    options = options.replace('<option value=""></option>', '');
    options = options.replace(new RegExp(' selected="selected"', 'g'), '');

    if (rows) {
        
        if (chooseType == 'single' && rows.length > 1) {
            delete rows[0];
        }
        
        for (var key in rows) {
            
            idVal = rows[key][idField];
            nameVal = rows[key][nameField];

            if (oldOptions.indexOf('value="'+idVal+'"') !== -1) {
                options = options.replace('value="'+idVal+'"', 'value="'+idVal+'" selected="selected"');
            } else {
                options += '<option value="'+idVal+'" selected="selected" data-row-data="'+htmlentities(JSON.stringify(rows[key]))+'">'+nameVal+'</option>';
            }
        }
    }
    
    $this.select2('destroy').empty().append(options).select2({
        allowClear: true,
        dropdownAutoWidth: true,
        closeOnSelect: false,
        escapeMarkup: function(markup) {
            return markup;
        }
    }).trigger('change');

    $button.text($this.select2('data').length);
    $parent.find('button.removebtn').html('<i class="fa fa-trash"></i>');
    
    if (rows.length > 0) {
        $parent.find('button.removebtn').show();
        $parent.find('ul.select2-choices').get(0).scrollLeft = 1000000;
    } else {
        $parent.find('button.removebtn').hide();
    }
}
function comboOptionByGroupName(elem) {
    var $this     = $(elem), 
        groupName = $this.attr('data-group'), 
        $results  = $this.closest('.select2-drop'), 
        $combo    = $results.data('select2').select, 
        selected  = $combo.val() || [];
    
    $combo.find('option[data-row-data*=\'|'+groupName+'|\']').each(function(){
        selected.push($(this).attr('value'));
    });
    
    if (selected) {
        $combo.select2('val', selected);
    }
}

function getAddressArcgis(element, callback) {
    if (typeof isArcgisCalled === 'undefined') {
        $.getScript(URL_APP + 'assets/custom/gov/arcgis.js').done(function() {
            callArcgisForm(element, callback);
        });
    } else {
        callArcgisForm(element, callback);
    }
}

function onChangeBpAttachMultiFileAddMode(input) {
	var $this = $(input);
	var ticket = true;
	if ($this.attr('data-valid-extension') && ($this.val() != '' || $this.closest('.file-input').length)) {
		var getExtension = $this.attr('data-valid-extension');
		if ($.trim(getExtension) !== '') {
			var removeWhiteSpace = getExtension.replace(/\s+/g, '');
			if (!$this.hasExtension(removeWhiteSpace.split(','))) {
				alert('Та (' + getExtension + ') эдгээр файлаас сонгоно уу!');
				$this.val('');
				
				if ($this.closest('.file-input').length) {
					$this.closest('.file-input').find('.fileinput-remove-button').trigger('click');
				}
				ticket = false;
			}
		}
	}
	
	if (ticket) {
		if ($(input).hasExtension(["png", "gif", "jpeg", "pjpeg", "jpg", "x-png", "bmp", "doc", "docx", "xls", "xlsx", "pdf", "ppt", "pptx", "zip", "rar", "mp3", "mp4"])) {
			var ext = input.value.match(/\.([^\.]+)$/)[1];
			if (typeof ext !== "undefined") {
				var $closestTag = $(input).closest('div[data-section-path]');
				
				if (!$closestTag.find('.file-list').length) {
					$('<div class="file-list"></div>').appendTo($closestTag);
				}
							
				var $listViewFile = $closestTag.find('.file-list');
				
				for (var i = 0; i < input.files.length; i++) {
					
					var $liAfter =  '<div class="btn-group mt3 mb3 file-tag" f-index="'+ i +'">'
										+ '<button type="button" class="btn btn-outline-primary btn-sm text-one-line mr0" title="'+ input.files[i].name +'" style="height: 24px;padding: 1px 5px;">'+ input.files[i].name +'</button>'
										+ '<button type="button" class="btn btn-outline-primary btn-icon btn-sm" title="'+ plang.get('delete_btn') +'" onclick="deleteBpTabFileAddMode(this);" style="height: 24px;padding: 1px 5px; width: 20px;padding: 2px 2px 2px 1px;line-height: 18px;"><i class="icon-cross"></i></button>'
									+ '</div>';
	
					$listViewFile.append($liAfter);
					
				}
	
			}
		} else {
			alert('Файл сонгоно уу.');
			$(input).val('');
		}
	}
}

function bpFileFormatSizeUnits(bytes) {
    
    if (bytes >= 1073741824) {
        bytes = (bytes / 1073741824).toFixed(2) + " gb";
    } else if (bytes >= 1048576) {
        bytes = (bytes / 1048576).toFixed(2) + " mb";
    } else if (bytes >= 1024) {
        bytes = (bytes / 1024).toFixed(2) + " kb";
    } else if (bytes > 1) {
        bytes = bytes + " bytes";
    } else if (bytes == 1) {
        bytes = bytes + " byte";
    } else {
        bytes = "0 bytes";
    }
    
    return bytes;
}

function deleteBpTabFileAddMode(element) {
    const dt = new DataTransfer();
    var tag = $(element).closest('div.file-tag');
    var $fileInput = $(element).closest('div[data-section-path]').find('.multi_file_styleInit');
    
    for (let file of $fileInput[0].files) {
        if (file !== $fileInput[0].files[tag.attr('f-index')]) {
            dt.items.add(file)
        }
    }
    
    $fileInput[0].files = dt.files;
    $(element).closest('div.file-tag').remove();
}

function ntrGetData(element, callbackFunction, methodId) {
    uniqId = $(element).closest('div[id="bp-window-'+ methodId +'"]').attr('data-bp-uniq-id');
    try {
        
        switch (callbackFunction) {
            case 'changeCustomerInformation':
                break;
            case 'copyPrevData':
                break;
            case 'signatureWrite':
                bpSignatureWrite(element);
                break;
            case 'signatureRemove':
                bpSignatureRemove(element);
                break;
            case 'citizenData':
                bpCitizenData(element, undefined, undefined, callbackFunction, methodId, uniqId);
                break;
            case 'idCardReadWtemplate':
                bpIDCardReadWtemplate(element, undefined, undefined, callbackFunction, methodId, uniqId);
                break;
            default:
                break;
        }
        
    } catch (e) {
        console.log(e);
    }
}

function dataAccessPassword() {
    var $dialogName = '#dialog-data-accesspassword';
    if (!$($dialogName).length) {
        $('<div id="' + $dialogName.replace('#', '') + '"></div>').appendTo('body');
    }
    var $dialog = $($dialogName);
    var html = '<form class="form-horizontal" id="form-data-accesspassword" method="post" autocomplete="off">'+
        '<div class="col-md-12 xs-form">'+
            '<div class="form-group row">'+
                '<label for="accessPass" class="col-form-label col-md-3"><span class="required">*</span>'+plang.get('pass_word')+':</label>'+
                '<div class="col-md-9">'+
                '<input type="password" id="accessPass" name="accessPass" class="form-control input-sm readonly-white-bg" autocomplete="off" required="required" readonly="readonly" onfocus="this.removeAttribute(\'readonly\');">'+
                '</div>'+
            '</div>'+
        '</div>'+
    '</form>';

    $dialog.empty().append(html);
    $dialog.dialog({
        cache: false,
        resizable: false,
        bgiframe: true,
        autoOpen: false,
        title: 'Security password',
        width: 400,
        minWidth: 400,
        height: 'auto',
        modal: true,
        closeOnEscape: isCloseOnEscape,
        open: function() {
            $(this).keypress(function(e) {
                if (e.keyCode == $.ui.keyCode.ENTER) {
                    $(this).parent().find(".ui-dialog-buttonpane button:first").trigger("click");
                }
            });
        },
        close: function() {
            $dialog.empty().dialog('destroy').remove();
        },
        buttons: [{
                text: plang.get('save_btn'),
                class: 'btn btn-sm green-meadow',
                click: function() {
                    
                    $('#form-data-accesspassword').validate({ errorPlacement: function() {} });

                    if ($('#form-data-accesspassword').valid()) {
                        $.ajax({
                            type: 'post',
                            url: 'mduser/dataAccessPassword',
                            data: $('#form-data-accesspassword').serialize(),
                            dataType: 'json',
                            beforeSend: function() {
                                Core.blockUI({message: 'Loading...', boxed: true});
                            },
                            success: function(data) {
                                PNotify.removeAll();
                                new PNotify({
                                    title: data.status,
                                    text: data.message,
                                    type: data.status,
                                    sticker: false, 
                                    addclass: pnotifyPosition
                                });

                                if (data.status === 'success') {
                                    $dialog.dialog('close');
                                }
                                Core.unblockUI();
                            },
                            error: function() { alert("Error"); Core.unblockUI(); }
                        });
                    }
                }
            },
            {
                text: plang.get('close_btn'),
                class: 'btn btn-sm blue-hoki',
                click: function() {
                    $dialog.dialog('close');
                }
            }
        ]
    });
    $dialog.dialog('open');
}
function bpFieldGraphSaveImage(elem, id) {
    Core.blockUI({
        message: 'Loading...',
        boxed: true
    });

    $.ajax({
        type: 'post',
        url: 'api/mxgraph/export',
        data: {saveImage: id, xml: $('#graphview-' + id).html(), format: 'png'},
        async: false
    });
    $.ajax({
        type: 'post',
        url: 'api/callProcess',
        data: {processCode: 'eisProcessBpmnDV_001', paramData: {id: $(elem).closest('form').find('input[name="param[id]"]').val(), bpmn1FilePath: 'storage/uploads/process/mxgraph_capture'+id+'.png'}}, 
        async: false
    })    
    Core.unblockUI();
    new PNotify({
        title: 'Success',
        text: 'Зураг амжилттай хадгалагдлаа', 
        type: 'success', 
        sticker: false
    });        
}
function bpFieldGraphView(elem, id) {
    if (typeof isBpmEditorUiInit === 'undefined') {
        $.getScript('middleware/assets/js/bpm/addon.js').done(function() {
            bpmDiagramToolById(elem, id);
        });
    } else {
        bpmDiagramToolById(elem, id);
    }
}
function containsLocationMap(coordinate, dvid, callback) {    
    $("link[href='assets/custom/addon/plugins/google-map/googleMap.css']").remove();
    $('head').append('<link rel="stylesheet" href="assets/custom/addon/plugins/google-map/googleMap.css" type="text/css" />');        

    if (!$('#md-map-bp-background').length) {
        var $div = $('<div />', {
            "id": 'md-map-bp-background',
            "class": "d-none"
        });    
        $('body').append($div);
    }
    var latLngValue = coordinate.split('|');

    $.getScript("https://maps.google.com/maps/api/js?key=" + gmapApiKey + "&sensor=true&language=mn").done(function() {

        var map = new google.maps.Map(document.getElementById('md-map-bp-background'));

        var polygonData = [];
        var response = $.ajax({
            type: 'post',
            url: 'mdobject/getPolygonList',
            data: {metaDataId: dvid},
            dataType: 'json',
            async: false
        });
        
        var responseData = response.responseJSON, getLL;        

        if (responseData.status === 'error') {
            return '';
        }

        for (var ii = 0; ii < responseData.length; ii++) {
            getLL = JSON.parse(html_entity_decode(responseData[ii]['region'], 'ENT_QUOTES'));
            polygonData.push({
                'll': new google.maps.Polygon({paths: getLL.coordinates}),
                'row': responseData[ii]
            });
        }
  
        var resultPoly = '';
        setTimeout(function() {
            for(var i = 0; i < polygonData.length; i++) {
                resultPoly = google.maps.geometry.poly.containsLocation(new google.maps.LatLng(latLngValue[1], latLngValue[0]), polygonData[i]['ll']) ? true : false;
                if (resultPoly) {
                    callback(polygonData[i]['row']);
                    return;
                }
            }            
            callback('');
        }, 800);

    });
}
function changeContent() {
    var response = $.ajax({
        type: 'post',
        url: 'government/changeContent', 
        dataType: 'json',
        async: false
    });
    return response.responseJSON;
}

function loadMxGraphScripts() {
    
    if (typeof (EditDataDialog) == 'undefined') {
        
        mxBasePath = URL_APP + 'assets/custom/addon/plugins/diagram/mxgraph/src';
        mxDefaultLanguage = 'en';
        mxLoadResources = false;
        RESOURCES_PATH = URL_APP + 'assets/custom/addon/plugins/diagram/mxgraph/resources';
        STENCIL_PATH = URL_APP + 'assets/custom/addon/plugins/diagram/mxgraph/stencils';
        IMAGE_PATH = URL_APP + 'assets/custom/addon/plugins/diagram/mxgraph/images';
        STYLE_PATH = URL_APP + 'assets/custom/addon/plugins/diagram/mxgraph/styles';
        EXPORT_URL = 'api/mxgraph/export';
        isArcgisPlugnCalled = false;
        CSS_PATH = STYLE_PATH;
        
        window.mxGraphIgnoreIncludes = true;

        var mxGraphScripts = [
            'assets/custom/addon/plugins/diagram/mxgraph/js/Init.js',
            'assets/custom/addon/plugins/diagram/mxgraph/jscolor/jscolor.js',
            'assets/custom/addon/plugins/diagram/mxgraph/sanitizer/sanitizer.min.js',
            'assets/custom/addon/plugins/diagram/mxgraph/src/js/mxClient.js?v=1',
            'assets/custom/addon/plugins/diagram/mxgraph/src/js/util/mxUtils.js',
            'assets/custom/addon/plugins/diagram/mxgraph/src/js/util/mxLog.js',
            'assets/custom/addon/plugins/diagram/mxgraph/src/js/util/mxObjectIdentity.js',
            'assets/custom/addon/plugins/diagram/mxgraph/src/js/util/mxDictionary.js',
            'assets/custom/addon/plugins/diagram/mxgraph/src/js/util/mxResources.js',
            'assets/custom/addon/plugins/diagram/mxgraph/src/js/util/mxPoint.js',
            'assets/custom/addon/plugins/diagram/mxgraph/src/js/util/mxRectangle.js',
            'assets/custom/addon/plugins/diagram/mxgraph/src/js/util/mxEffects.js',
            'assets/custom/addon/plugins/diagram/mxgraph/src/js/util/mxEventObject.js',
            'assets/custom/addon/plugins/diagram/mxgraph/src/js/util/mxMouseEvent.js',
            'assets/custom/addon/plugins/diagram/mxgraph/src/js/util/mxEventSource.js',
            'assets/custom/addon/plugins/diagram/mxgraph/src/js/util/mxEvent.js',
            'assets/custom/addon/plugins/diagram/mxgraph/src/js/util/mxXmlRequest.js',
            'assets/custom/addon/plugins/diagram/mxgraph/src/js/util/mxClipboard.js',
            'assets/custom/addon/plugins/diagram/mxgraph/src/js/util/mxWindow.js',
            'assets/custom/addon/plugins/diagram/mxgraph/src/js/util/mxForm.js',
            'assets/custom/addon/plugins/diagram/mxgraph/src/js/util/mxImage.js',
            'assets/custom/addon/plugins/diagram/mxgraph/src/js/util/mxDivResizer.js',
            'assets/custom/addon/plugins/diagram/mxgraph/src/js/util/mxDragSource.js',
            'assets/custom/addon/plugins/diagram/mxgraph/src/js/util/mxToolbar.js',
            'assets/custom/addon/plugins/diagram/mxgraph/src/js/util/mxUndoableEdit.js',
            'assets/custom/addon/plugins/diagram/mxgraph/src/js/util/mxUndoManager.js',
            'assets/custom/addon/plugins/diagram/mxgraph/src/js/util/mxUrlConverter.js',
            'assets/custom/addon/plugins/diagram/mxgraph/src/js/util/mxPanningManager.js',
            'assets/custom/addon/plugins/diagram/mxgraph/src/js/util/mxPopupMenu.js',
            'assets/custom/addon/plugins/diagram/mxgraph/src/js/util/mxAutoSaveManager.js',
            'assets/custom/addon/plugins/diagram/mxgraph/src/js/util/mxAnimation.js',
            'assets/custom/addon/plugins/diagram/mxgraph/src/js/util/mxMorphing.js',
            'assets/custom/addon/plugins/diagram/mxgraph/src/js/util/mxImageBundle.js',
            'assets/custom/addon/plugins/diagram/mxgraph/src/js/util/mxImageExport.js',
            'assets/custom/addon/plugins/diagram/mxgraph/src/js/util/mxAbstractCanvas2D.js',
            'assets/custom/addon/plugins/diagram/mxgraph/src/js/util/mxXmlCanvas2D.js',
            'assets/custom/addon/plugins/diagram/mxgraph/src/js/util/mxSvgCanvas2D.js',
            'assets/custom/addon/plugins/diagram/mxgraph/src/js/util/mxVmlCanvas2D.js',
            'assets/custom/addon/plugins/diagram/mxgraph/src/js/util/mxGuide.js',
            'assets/custom/addon/plugins/diagram/mxgraph/src/js/util/mxConstants.js',
            'assets/custom/addon/plugins/diagram/mxgraph/src/js/shape/mxStencil.js',
            'assets/custom/addon/plugins/diagram/mxgraph/src/js/shape/mxShape.js',
            'assets/custom/addon/plugins/diagram/mxgraph/src/js/shape/mxStencilRegistry.js',
            'assets/custom/addon/plugins/diagram/mxgraph/src/js/shape/mxMarker.js',
            'assets/custom/addon/plugins/diagram/mxgraph/src/js/shape/mxActor.js',
            'assets/custom/addon/plugins/diagram/mxgraph/src/js/shape/mxCloud.js',
            'assets/custom/addon/plugins/diagram/mxgraph/src/js/shape/mxRectangleShape.js',
            'assets/custom/addon/plugins/diagram/mxgraph/src/js/shape/mxEllipse.js',
            'assets/custom/addon/plugins/diagram/mxgraph/src/js/shape/mxDoubleEllipse.js',
            'assets/custom/addon/plugins/diagram/mxgraph/src/js/shape/mxRhombus.js',
            'assets/custom/addon/plugins/diagram/mxgraph/src/js/shape/mxPolyline.js',
            'assets/custom/addon/plugins/diagram/mxgraph/src/js/shape/mxArrow.js',
            'assets/custom/addon/plugins/diagram/mxgraph/src/js/shape/mxArrowConnector.js',
            'assets/custom/addon/plugins/diagram/mxgraph/src/js/shape/mxText.js',
            'assets/custom/addon/plugins/diagram/mxgraph/src/js/shape/mxTriangle.js',
            'assets/custom/addon/plugins/diagram/mxgraph/src/js/shape/mxHexagon.js',
            'assets/custom/addon/plugins/diagram/mxgraph/src/js/shape/mxLine.js',
            'assets/custom/addon/plugins/diagram/mxgraph/src/js/shape/mxImageShape.js',
            'assets/custom/addon/plugins/diagram/mxgraph/src/js/shape/mxLabel.js',
            'assets/custom/addon/plugins/diagram/mxgraph/src/js/shape/mxCylinder.js',
            'assets/custom/addon/plugins/diagram/mxgraph/src/js/shape/mxConnector.js',
            'assets/custom/addon/plugins/diagram/mxgraph/src/js/shape/mxSwimlane.js',
            'assets/custom/addon/plugins/diagram/mxgraph/src/js/layout/mxGraphLayout.js',
            'assets/custom/addon/plugins/diagram/mxgraph/src/js/layout/mxStackLayout.js',
            'assets/custom/addon/plugins/diagram/mxgraph/src/js/layout/mxPartitionLayout.js',
            'assets/custom/addon/plugins/diagram/mxgraph/src/js/layout/mxCompactTreeLayout.js',
            'assets/custom/addon/plugins/diagram/mxgraph/src/js/layout/mxRadialTreeLayout.js',
            'assets/custom/addon/plugins/diagram/mxgraph/src/js/layout/mxFastOrganicLayout.js',
            'assets/custom/addon/plugins/diagram/mxgraph/src/js/layout/mxCircleLayout.js',
            'assets/custom/addon/plugins/diagram/mxgraph/src/js/layout/mxParallelEdgeLayout.js',
            'assets/custom/addon/plugins/diagram/mxgraph/src/js/layout/mxCompositeLayout.js',
            'assets/custom/addon/plugins/diagram/mxgraph/src/js/layout/mxEdgeLabelLayout.js',
            'assets/custom/addon/plugins/diagram/mxgraph/src/js/layout/hierarchical/model/mxGraphAbstractHierarchyCell.js',
            'assets/custom/addon/plugins/diagram/mxgraph/src/js/layout/hierarchical/model/mxGraphHierarchyNode.js',
            'assets/custom/addon/plugins/diagram/mxgraph/src/js/layout/hierarchical/model/mxGraphHierarchyEdge.js',
            'assets/custom/addon/plugins/diagram/mxgraph/src/js/layout/hierarchical/model/mxGraphHierarchyModel.js',
            'assets/custom/addon/plugins/diagram/mxgraph/src/js/layout/hierarchical/model/mxSwimlaneModel.js',
            'assets/custom/addon/plugins/diagram/mxgraph/src/js/layout/hierarchical/stage/mxHierarchicalLayoutStage.js',
            'assets/custom/addon/plugins/diagram/mxgraph/src/js/layout/hierarchical/stage/mxMedianHybridCrossingReduction.js',
            'assets/custom/addon/plugins/diagram/mxgraph/src/js/layout/hierarchical/stage/mxMinimumCycleRemover.js',
            'assets/custom/addon/plugins/diagram/mxgraph/src/js/layout/hierarchical/stage/mxCoordinateAssignment.js',
            'assets/custom/addon/plugins/diagram/mxgraph/src/js/layout/hierarchical/stage/mxSwimlaneOrdering.js',
            'assets/custom/addon/plugins/diagram/mxgraph/src/js/layout/hierarchical/mxHierarchicalLayout.js',
            'assets/custom/addon/plugins/diagram/mxgraph/src/js/layout/hierarchical/mxSwimlaneLayout.js',
            'assets/custom/addon/plugins/diagram/mxgraph/src/js/model/mxGraphModel.js',
            'assets/custom/addon/plugins/diagram/mxgraph/src/js/model/mxCell.js',
            'assets/custom/addon/plugins/diagram/mxgraph/src/js/model/mxGeometry.js',
            'assets/custom/addon/plugins/diagram/mxgraph/src/js/model/mxCellPath.js',
            'assets/custom/addon/plugins/diagram/mxgraph/src/js/view/mxPerimeter.js',
            'assets/custom/addon/plugins/diagram/mxgraph/src/js/view/mxPrintPreview.js',
            'assets/custom/addon/plugins/diagram/mxgraph/src/js/view/mxStylesheet.js',
            'assets/custom/addon/plugins/diagram/mxgraph/src/js/view/mxCellState.js',
            'assets/custom/addon/plugins/diagram/mxgraph/src/js/view/mxGraphSelectionModel.js',
            'assets/custom/addon/plugins/diagram/mxgraph/src/js/view/mxCellEditor.js',
            'assets/custom/addon/plugins/diagram/mxgraph/src/js/view/mxCellRenderer.js',
            'assets/custom/addon/plugins/diagram/mxgraph/src/js/view/mxEdgeStyle.js',
            'assets/custom/addon/plugins/diagram/mxgraph/src/js/view/mxStyleRegistry.js',
            'assets/custom/addon/plugins/diagram/mxgraph/src/js/view/mxGraphView.js',
            'assets/custom/addon/plugins/diagram/mxgraph/src/js/view/mxGraph.js',
            'assets/custom/addon/plugins/diagram/mxgraph/src/js/view/mxCellOverlay.js',
            'assets/custom/addon/plugins/diagram/mxgraph/src/js/view/mxOutline.js',
            'assets/custom/addon/plugins/diagram/mxgraph/src/js/view/mxMultiplicity.js',
            'assets/custom/addon/plugins/diagram/mxgraph/src/js/view/mxLayoutManager.js',
            'assets/custom/addon/plugins/diagram/mxgraph/src/js/view/mxSwimlaneManager.js',
            'assets/custom/addon/plugins/diagram/mxgraph/src/js/view/mxTemporaryCellStates.js',
            'assets/custom/addon/plugins/diagram/mxgraph/src/js/view/mxCellStatePreview.js',
            'assets/custom/addon/plugins/diagram/mxgraph/src/js/view/mxConnectionConstraint.js',
            'assets/custom/addon/plugins/diagram/mxgraph/src/js/handler/mxGraphHandler.js',
            'assets/custom/addon/plugins/diagram/mxgraph/src/js/handler/mxPanningHandler.js',
            'assets/custom/addon/plugins/diagram/mxgraph/src/js/handler/mxPopupMenuHandler.js',
            'assets/custom/addon/plugins/diagram/mxgraph/src/js/handler/mxCellMarker.js',
            'assets/custom/addon/plugins/diagram/mxgraph/src/js/handler/mxSelectionCellsHandler.js',
            'assets/custom/addon/plugins/diagram/mxgraph/src/js/handler/mxConnectionHandler.js',
            'assets/custom/addon/plugins/diagram/mxgraph/src/js/handler/mxConstraintHandler.js',
            'assets/custom/addon/plugins/diagram/mxgraph/src/js/handler/mxRubberband.js',
            'assets/custom/addon/plugins/diagram/mxgraph/src/js/handler/mxHandle.js',
            'assets/custom/addon/plugins/diagram/mxgraph/src/js/handler/mxVertexHandler.js',
            'assets/custom/addon/plugins/diagram/mxgraph/src/js/handler/mxEdgeHandler.js',
            'assets/custom/addon/plugins/diagram/mxgraph/src/js/handler/mxElbowEdgeHandler.js',
            'assets/custom/addon/plugins/diagram/mxgraph/src/js/handler/mxEdgeSegmentHandler.js',
            'assets/custom/addon/plugins/diagram/mxgraph/src/js/handler/mxKeyHandler.js',
            'assets/custom/addon/plugins/diagram/mxgraph/src/js/handler/mxTooltipHandler.js',
            'assets/custom/addon/plugins/diagram/mxgraph/src/js/handler/mxCellTracker.js',
            'assets/custom/addon/plugins/diagram/mxgraph/src/js/handler/mxCellHighlight.js',
            'assets/custom/addon/plugins/diagram/mxgraph/src/js/editor/mxDefaultKeyHandler.js',
            'assets/custom/addon/plugins/diagram/mxgraph/src/js/editor/mxDefaultPopupMenu.js',
            'assets/custom/addon/plugins/diagram/mxgraph/src/js/editor/mxDefaultToolbar.js',
            'assets/custom/addon/plugins/diagram/mxgraph/src/js/editor/mxEditor.js', 
            'assets/custom/addon/plugins/diagram/mxgraph/src/js/io/mxCodecRegistry.js',
            'assets/custom/addon/plugins/diagram/mxgraph/src/js/io/mxCodec.js',
            'assets/custom/addon/plugins/diagram/mxgraph/src/js/io/mxObjectCodec.js',
            'assets/custom/addon/plugins/diagram/mxgraph/src/js/io/mxCellCodec.js',
            'assets/custom/addon/plugins/diagram/mxgraph/src/js/io/mxModelCodec.js',
            'assets/custom/addon/plugins/diagram/mxgraph/src/js/io/mxRootChangeCodec.js',
            'assets/custom/addon/plugins/diagram/mxgraph/src/js/io/mxChildChangeCodec.js',
            'assets/custom/addon/plugins/diagram/mxgraph/src/js/io/mxTerminalChangeCodec.js',
            'assets/custom/addon/plugins/diagram/mxgraph/src/js/io/mxGenericChangeCodec.js',
            'assets/custom/addon/plugins/diagram/mxgraph/src/js/io/mxGraphCodec.js',
            'assets/custom/addon/plugins/diagram/mxgraph/src/js/io/mxGraphViewCodec.js',
            'assets/custom/addon/plugins/diagram/mxgraph/src/js/io/mxStylesheetCodec.js',
            'assets/custom/addon/plugins/diagram/mxgraph/src/js/io/mxDefaultKeyHandlerCodec.js',
            'assets/custom/addon/plugins/diagram/mxgraph/src/js/io/mxDefaultToolbarCodec.js',
            'assets/custom/addon/plugins/diagram/mxgraph/src/js/io/mxDefaultPopupMenuCodec.js',
            'assets/custom/addon/plugins/diagram/mxgraph/src/js/io/mxEditorCodec.js', 
            'assets/custom/addon/plugins/diagram/mxgraph/js/EditorUi.js',
            'assets/custom/addon/plugins/diagram/mxgraph/js/Editor.js',
            'assets/custom/addon/plugins/diagram/mxgraph/js/Sidebar.js',
            'assets/custom/addon/plugins/diagram/mxgraph/js/Graph.js',
            'assets/custom/addon/plugins/diagram/mxgraph/js/Format.js',
            'assets/custom/addon/plugins/diagram/mxgraph/js/Shapes.js',
            'assets/custom/addon/plugins/diagram/mxgraph/js/Actions.js',
            'assets/custom/addon/plugins/diagram/mxgraph/js/Menus.js',
            'assets/custom/addon/plugins/diagram/mxgraph/js/Toolbar.js',
            'assets/custom/addon/plugins/diagram/mxgraph/js/Dialogs.js', 
            'assets/custom/addon/plugins/diagram/mxgraph/deflate/pako.min.js', 
            'assets/custom/addon/plugins/diagram/mxgraph/deflate/base64.js'
        ];
        
        $('head').append('<link rel="stylesheet" type="text/css" href="assets/custom/addon/plugins/diagram/mxgraph/styles/grapheditor.css?v=1"/>');
        
        mxGraphScripts.forEach(function(url) { 
            $.ajax({
                type: 'GET',
                url: url,
                async: false,
                cache: true,
                dataType: 'script'
            });
        });
    }
    
    return true;
}
function bpCommentMentionsInputInit($wrap) {
    if ($().mentionsInput) {
        bpCommentMentionsInput($wrap);
    } else {
        $('head').append('<link rel="stylesheet" type="text/css" href="assets/custom/addon/plugins/jquery-mention-input/jquery.mentionsInput.css"/>');
        $.cachedScript('assets/custom/addon/plugins/jquery-mention-input/jquery.events.input.js').done(function() {
            $.cachedScript('assets/custom/addon/plugins/jquery-mention-input/underscore-min.js').done(function() {
                $.cachedScript('assets/custom/addon/plugins/jquery-mention-input/jquery.elastic.js').done(function() {
                    $.cachedScript('assets/custom/addon/plugins/jquery-mention-input/jquery.mentionsInput.js').done(function() {
                        bpCommentMentionsInput($wrap);
                    });
                });
            });
        });
    }
}
function bpCommentMentionsInput($wrap) {
    $wrap.find('textarea.bpaddon-mention-autocomplete').mentionsInput({
        minChars: '1',
        showAvatars: false, 
        elastic: false, 
        onCaret: true, 
        onDataRequest:function (mode, query, callback) {
            $.ajax({
                type: 'post',
                url: 'mdwebservice/lookupAutoComplete',
                dataType: 'json',
                data: {
                    lookupId: '1593052842998021', 
                    processId: '', 
                    paramRealPath: '',
                    isAutoCompleteText: 1, 
                    displayField: 'name', 
                    q: query, 
                    type: 'name'
                },
                success: function(responseData) {
                    callback.call(this, $.map(responseData, function(item) {
                        var code = item.codeName.split('|');
                        return {
                            id: code[0], 
                            name: code[2],
                            type: 'contact', 
                            tablename: (item.row).hasOwnProperty('tablename') ? item.row.tablename : ''
                        };
                    }));
                }
            });
        }
    });
}

function getChildFolder(element, id, parentId, srcId) {
    var $this = $(element),
        $parent = $this.closest('li._shadow');
    var __$selector = $this.closest('.main-bp-photo-container');
    
    if (__$selector.find('li[data-parentid="'+ id +'"]').length > 0) {
        __$selector.find('li._shadow').hide();
        __$selector.find('li[data-parentid="'+ id +'"]').show();
        __$selector.find('li.backbtn[data-id="'+ id +'"]').show();
        __$selector.attr('data-parentid', id);
        
        if (srcId) {
            __$selector.find('input[data-path="bp_folder_id"]').val(id);
        }
    } else {
        $.ajax({
            type: 'post',
            dataType: 'json',
            sync: true,
            url: 'mdwebservice/getChildBpFileFolder',
            data: {
                id: id,
                parentid: parentId,
                sourceId: srcId,
                refStructureId: $parent.attr('data-structureId'),
            },
            beforeSend: function() {
                Core.blockUI({
                    animate: true
                });
            },
            success: function(data) {
                __$selector.find('li[data-parentid="'+ $parent.attr('data-parentid') +'"]').hide();
                __$selector.attr('data-parentid', id);

                var li = '<li class="border-none p-1 mr-1 _shadow parent" data-parentid="'+ id +'" data-structureId="'+ $parent.attr('data-structureId') +'">';
                    li += '<a href="javascript:;" ondblclick="getParentFolder(this, \''+ id +'\', \''+ parentId +'\',\''+ srcId +'\')">';
                        li += '<img src="assets/core/global/img/meta/folder_back.png" class="text-center w-100 gotoParent"/>';
                    li += '</a>';
                li += '</li>';
                
                if (__$selector.find('.list-view-photo[data-id="'+ id +'"]').length == 0) {
                    __$selector.find('.list-view-photo').append(li);
                }

                if (data.hasOwnProperty('folderData')) {
                    li = '';
                    $.each(data.folderData, function (index, row) {
                        li += '<li class="border-none p-1 mr-1 _shadow parent" data-id="'+ row.ID +'" data-parentid="'+ id +'" data-structureId="'+ $parent.attr('data-structureId') +'">';
                            li += '<a href="javascript:;" ondblclick="getChildFolder(this, \''+ row.ID +'\', \''+ id +'\', \''+ srcId +'\')">';
                                li += '<img src="assets/core/global/img/meta/folder.png" class="text-center w-100 gotoChild"/>';
                            li += '</a>';
                            li += '<div class="btn-group float-left pt5">';
                                li += '<input type="text" name="bp_folder_name" onchange="bpFolderNameChange(this, \''+ row.ID +'\')" class="float-left w-100" placeholder="'+ plang.get('folder_name') +'" value="'+ row.NAME +'">'; 
                                li += '<a class="btn default btn-xs " onclick="deleteAddBpPhotoFolder(this, \''+ row.ID +'\')" type="button" ><i class="fa fa-trash"></i></a>';
                            li += '</div>';
                            if (!srcId) {
                                __$selector.find('input[data-path="bp_folder_id"]').val(row.ID);
                            }
                        li += '</li>';
                    });

                    __$selector.find('.list-view-photo').append(li);
                }
                
                if (data.hasOwnProperty('item')) {
                    var $li = '';
                    $.each(data.item, function (index, row) {
                        var $bigIcon = "assets/core/global/img/meta/photo.png";
                        var $smallIcon = "assets/core/global/img/meta/photo-mini.png";
                        
                        if (row.hasOwnProperty('ATTACH')) {
                            $bigIcon = row.ATTACH;
                            $smallIcon = row.ATTACH_THUMB;
                        }
                        
                        $li += '<li class="shadow _shadow parent '+ row.TRG_TAG_IDC +'" data-attach-id="'+ row.ATTACH_ID +'" data-src-id="'+ row.TRG_TAG_ID +'" data-parentid="'+ row.FOLDER_ID +'">';
                            $li += '<a href="'+ $bigIcon +'" class="fancybox-button main" data-rel="fancybox-button" data-fancybox="images" title="'+ row.ATTACH_NAME +'">';
                                $li += '<img src="'+ $smallIcon +'"/>';
                            $li += '</a>';
                            $li += '<div class="btn-group float-right padding-5">';
                                $li += '<button aria-expanded="false" class="btn default btn-xs dropdown-toggle" type="button" data-toggle="dropdown">';
                                $li += '</button>';
                                $li += '<ul class="dropdown-menu float-right" role="menu">';
                                    $li += '<li>';
                                        $li += '<a href="javascript:;" onclick="updateBpTabPhoto(this);"><i class="fa fa-edit"></i> '+ plang.get('edit_btn') +'</a>';
                                    $li += '</li>';
                                    $li += '<li>';
                                        $li += '<a href="javascript:;" onclick="deleteBpTabPhoto(this);"><i class="fa fa-trash"></i> '+ plang.get('delete_btn') +'</a>';
                                    $li += '</li>';
                                $li += '</ul>';
                            $li += '</div>';
                            $li += '<div class="title-photo">';
                                $li += (row.ATTACH_NAME ? row.ATTACH_NAME : '') ;
                            $li += '</div>';
                        $li += '</li>';
                    });

                    __$selector.find('.list-view-photo').append($li).promise().done(function () {
                        Core.initFancybox(__$selector);
                    });
                }
                
                if (srcId) {
                    __$selector.find('input[data-path="bp_folder_id"]').val(id);
                }

                Core.unblockUI();
            },
            error: function(jqXHR, exception) {
                Core.showErrorMessage(jqXHR, exception);
                Core.unblockUI();
            }
        });
    }
}

function getParentFolder(element, id, parentId, srcId) {
    var $this = $(element);
    var __$selector = $this.closest('.main-bp-photo-container');
    
    __$selector.find('li._shadow').hide();
    __$selector.find('li.parent[data-parentid="'+ parentId +'"]').show();
    __$selector.attr('data-parentid', parentId);
    
    if (srcId) {
        __$selector.find('input[data-path="bp_folder_id"]').val(parentId);
    }
    
};

function bpAddFolder(element, refStructureId, srcId) {
    var __$selector =  $(element).closest('.main-bp-photo-container');
    var $parentid = (typeof __$selector.attr('data-parentid') === 'undefined') ? '0' : __$selector.attr('data-parentid');
    $.ajax({
        type: 'post',
        dataType: 'json',
        url: 'mdwebservice/saveModeBpFileFolder',
        data: {
            refStructureId: refStructureId,
            parentid: $parentid,
            srcId: srcId
        },
        beforeSend: function() {
            Core.blockUI({
                animate: true
            });
        },
        success: function(data) {
            if (typeof data.ID !== 'undefined' && data.ID) {
                var li = '<li class="border-none p-1 mr-1 _shadow parent" data-id="'+ data.ID +'" data-structureId="'+ refStructureId +'" data-parentid="'+ $parentid +'">';
                    li += '<a href="javascript:;" ondblclick="getChildFolder(this, \''+ data.ID +'\', \''+ $parentid +'\')" >';
                        li += '<img src="assets/core/global/img/meta/folder.png" class="text-center w-100 gotoChild"/>';
                    li += '</a>';
                    li += '<div class="btn-group float-left pt5">';
                        li += '<input type="text" name="bp_folder_name" onchange="bpFolderNameChange(this, \''+ data.ID +'\')" class="float-left w-100" placeholder="'+ plang.get('folder_name') +'" value="'+ data.NAME +'">'; 
                        li += '<a class="btn default btn-xs " onclick="deleteAddBpPhotoFolder(this, \''+ data.ID +'\')" type="button" ><i class="fa fa-trash"></i></a>';
                    li += '</div>';
                    if (!srcId) {
                        li += '<input type="hidden" name="bp_folder_id[]" value="'+ data.ID +'"/>';
                    }
                li += '</li>';
                
                if (srcId) {
                    __$selector.find('input[data-path="bp_folder_id"]').val(data.ID);
                }
                __$selector.find('.list-view-photo').append(li);
            }
            
            Core.unblockUI();
        },
        error: function(jqXHR, exception) {
            Core.showErrorMessage(jqXHR, exception);
            Core.unblockUI();
        }
    });
}

function bpFolderNameChange(element, folderId) {
    var $this = $(element);
    
    $.ajax({
        type: 'post',
        dataType: 'json',
        url: 'mdwebservice/updateModeBpFileFolder',
        data: {
            folderId: folderId,
            folderName: $this.val()
        },
        beforeSend: function() {
            Core.blockUI({
                animate: true
            });
        },
        success: function(data) {
            Core.unblockUI();
        },
        error: function(jqXHR, exception) {
            Core.showErrorMessage(jqXHR, exception);
            Core.unblockUI();
        }
    });
}

function deleteAddBpPhotoFolder(element, folderId) {
    var dialogName = '#deleteConfirm';
    if (!$(dialogName).length) {
        $('<div id="' + dialogName.replace('#', '') + '"></div>').appendTo('body');
    }
    $(dialogName).html('Та устгахдаа итгэлтэй байна уу?');
    $(dialogName).dialog({
        cache: false,
        resizable: true,
        bgiframe: true,
        autoOpen: false,
        title: 'Сануулах',
        width: '350',
        height: 'auto',
        modal: true,
        buttons: [
            {text: 'Тийм', class: 'btn green-meadow btn-sm', click: function () {
                    var _this = $(element);
                    var li = _this.parents('li._shadow');
                    
                if (folderId) {
                    $.ajax({
                        type: 'post',
                        url: 'mdwebservice/renderBpTabDeletePhotoFolder',
                        data: {folderId: folderId},
                        dataType: "json",
                        success: function(data) {
                            if (data.status === 'success') {
                                new PNotify({
                                    title: 'Success',
                                    text: 'Амжилттай устгагдлаа.',
                                    type: 'success',
                                    sticker: false
                                });
                                li.remove();
                            } else {
                                new PNotify({
                                    title: 'Error',
                                    text: 'Алдаа гарлаа',
                                    type: 'error',
                                    sticker: false
                                });
                            }
                        },
                        error: function(jqXHR, exception) {
                            Core.showErrorMessage(jqXHR, exception);
                        }
                    });
                } else {
                    li.remove();
                }
                
                $(dialogName).dialog('close');
            }},
            {text: 'Үгүй', class: 'btn blue-madison btn-sm', click: function () {
                $(dialogName).dialog('close');
            }}
        ]
    });
    $(dialogName).dialog('open');
}
function proccessRenderPopup(windowId, elem) {
    var $this = $(elem);
    var $parent = $(windowId).parent();
    var $processPopupDtlTd = $this.parent();
    var isPositionRelative = false, isOverflowAutoParent = false;
    var $bpLayout = $this.closest('.bp-layout');
    
    if ($bpLayout.length) {
        var $overflowAutoParent = $this.closest('.overflow-auto');

        if ($overflowAutoParent.length) {
            isOverflowAutoParent = true;
        }
    }
    
    $parent.css('position', 'static');
    $parent.find("div.center-sidebar").css('position', 'static');
    $parent.parent().css('overflow', 'inherit');
    
    var $processChildDtlTd = $(elem).closest('td');
    
    if ($processChildDtlTd.css('position') == 'relative') {
        $processChildDtlTd.css('position', '');
        isPositionRelative = true;
    }
        
    $processChildDtlTd.closest("div.col-md-12").css('position', 'static');
    
    var hideSaveButton = '';
    if (typeof $(elem).closest('table.bprocess-table-dtl').attr('data-popup-ignore-save-button') !== 'undefined' 
        && $(elem).closest('table.bprocess-table-dtl').attr('data-popup-ignore-save-button') == '1') {
        hideSaveButton = ' hide';
    }
    
    if (isOverflowAutoParent) {
        $overflowAutoParent.addClass('overflow-inherit');
    }
    
    var $dialogName = 'div.sidebarDetailSection';
    var $dialog = $($dialogName, $processPopupDtlTd);
    
    $dialog.dialog({
        cache: false,
        resizable: true,
        appendTo: $processPopupDtlTd,
        bgiframe: true,
        autoOpen: false,
        title: 'More',
        width: 550,
        height: 'auto',
        maxHeight: 650,
        modal: true,
        closeOnEscape: isCloseOnEscape, 
        close: function() {
            if (isPositionRelative == true) {
                $processChildDtlTd.css('position', 'relative');
            }
            if (isOverflowAutoParent) {
                $overflowAutoParent.removeClass('overflow-inherit');
            }
        },
        buttons: [
            {text: plang.get('save_btn'), class: 'btn green-meadow btn-sm'+hideSaveButton, click: function () {
                $dialog.dialog('close');
            }},
            {text: plang.get('close_btn'), class: 'btn blue-madison btn-sm', click: function () {
                $dialog.dialog('close');
            }}
        ]
    }).dialogExtend({
        "closable": true,
        "maximizable": true,
        "minimizable": false,
        "collapsable": false,
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
    $dialog.dialog('open');
}
function showRenderSidebar(windowId, dataTable) {
    $(".stoggler", windowId).on("click", function () {
        var dataTableCheck = typeof dataTable;
        var _thisToggler = $(this);
        var centersidebar = $(".center-sidebar", windowId);
        var rightsidebar = $(".right-sidebar", windowId);
        var rightsidebarstatus = rightsidebar.attr("data-status");
        if (rightsidebarstatus === "closed") {
            centersidebar.removeClass("col-md-12").addClass("col-md-8");
            rightsidebar.addClass("col-md-4").css("margin-top: 18px;");
            rightsidebar.find(".glyphicon-chevron-right").parent().hide();
            rightsidebar.find(".glyphicon-chevron-left").hide();
            rightsidebar.find(".right-sidebar-content").show();
            rightsidebar.find(".glyphicon-chevron-right").parent().fadeIn();
            rightsidebar.find(".glyphicon-chevron-right").fadeIn();
            if (dataTableCheck !== 'undefined')
                dataTable.fnAdjustColumnSizing();
            rightsidebar.attr('data-status', 'opened');
            _thisToggler.addClass("sidebar-opened");
        } else {
            rightsidebar.find(".glyphicon-chevron-right").hide();
            rightsidebar.find(".glyphicon-chevron-right").parent().hide();
            rightsidebar.find(".right-sidebar-content").hide();
            centersidebar.removeClass("col-md-8").addClass("col-md-12");
            rightsidebar.removeClass("col-md-4");
            rightsidebar.find(".glyphicon-chevron-left").parent().fadeIn();
            rightsidebar.find(".glyphicon-chevron-left").fadeIn();
            if (dataTableCheck !== 'undefined')
                dataTable.fnAdjustColumnSizing();
            rightsidebar.attr('data-status', 'closed');
            _thisToggler.removeClass("sidebar-opened");
        }
    });
//    $(".stoggler", windowId).trigger('click');
    $(".stoggler", windowId).on("mouseover", function () {
        $(this).css({
            "background-color": "rgba(230, 230, 230, 0.80)",
            "border-right": "1px solid rgba(230, 230, 230, 0.80)"
        });
    });
    $(".stoggler", windowId).on("mouseleave", function () {
        $(this).css({
            "background-color": "#FFF",
            "border-right": "#FFF"
        });
    });
}
function showRenderSidebarNoTrigger(windowId, dataTable) {
    var dataTableCheck = typeof dataTable;
    var _thisToggler = $(".stoggler", windowId);
    var centersidebar = $(".center-sidebar", windowId);
    var rightsidebar = $(".right-sidebar", windowId);
    var rightsidebarstatus = rightsidebar.attr("data-status");
    if (rightsidebarstatus === "closed") {
        centersidebar.removeClass("col-md-12").addClass("col-md-9");
        rightsidebar.addClass("col-md-3").css("margin-top: 18px;");
        rightsidebar.find(".glyphicon-chevron-right").parent().hide();
        rightsidebar.find(".glyphicon-chevron-left").hide();
        rightsidebar.find(".right-sidebar-content").show();
        rightsidebar.find(".glyphicon-chevron-right").parent().fadeIn();
        rightsidebar.find(".glyphicon-chevron-right").fadeIn();
        if (dataTableCheck !== 'undefined')
            dataTable.fnAdjustColumnSizing();
        rightsidebar.attr('data-status', 'opened');
        _thisToggler.addClass("sidebar-opened");
    } else {
        rightsidebar.find(".glyphicon-chevron-right").hide();
        rightsidebar.find(".glyphicon-chevron-right").parent().hide();
        rightsidebar.find(".right-sidebar-content").hide();
        centersidebar.removeClass("col-md-9").addClass("col-md-12");
        rightsidebar.removeClass("col-md-3");
        rightsidebar.find(".glyphicon-chevron-left").parent().fadeIn();
        rightsidebar.find(".glyphicon-chevron-left").fadeIn();
        if (dataTableCheck !== 'undefined')
            dataTable.fnAdjustColumnSizing();
        rightsidebar.attr('data-status', 'closed');
        _thisToggler.removeClass("sidebar-opened");
    }
    $(".stoggler", windowId).on("mouseover", function () {
        $(this).css({
            "background-color": "rgba(230, 230, 230, 0.80)",
            "border-right": "1px solid rgba(230, 230, 230, 0.80)"
        });
    });
    $(".stoggler", windowId).on("mouseleave", function () {
        $(this).css({
            "background-color": "#FFF",
            "border-right": "#FFF"
        });
    });
}
function procPageIndex(id, url) {
    $.ajax({
        type: 'post',
        url: url,
        data: { id: id },
        dataType: 'json',
        beforeSend: function() {
            Core.blockUI({message: 'Loading...', boxed: true});
        },
        success: function(data) {
            var dialogName = '#dialog-procurement';
            if (!$(dialogName).length) {
                $('<div id="' + dialogName.replace('#', '') + '"></div>').appendTo('body');
            }

            $(dialogName).empty().append(data.Html);
            $(dialogName).dialog({
                cache: false,
                resizable: true,
                bgiframe: true,
                autoOpen: false,
                title: data.Title,
                width: 1200,
                height: 'auto',
                modal: true,
                close: function() {
                    $(dialogName).empty().dialog('destroy').remove();
                },
                buttons: [{
                    text: data.save_btn,
                    class: 'btn green-meadow btn-sm',
                    click: function() {
                        $(dialogName).find('form').validate({ errorPlacement: function() {} });

                        if ($(dialogName).find('form').valid()) {                              

                            if ($(dialogName).find('table.bprocess-theme1-proc > tbody > tr.item-main-row').length !== $(dialogName).find('table.bprocess-theme1-proc > tbody > tr > td.slc').length) {
                                PNotify.removeAll();
                                new PNotify({
                                    title: 'Warning',
                                    text: 'Нийлүүлэгч заавал сонгоно',
                                    type: 'warning',
                                    sticker: false
                                });                           
                                return;
                            } 

                            tinyMCE.triggerSave();
                            
                            if (!$(".headerMoreDescription").hasClass("hidden") && tinymce.get('headerMoreDescription').getContent() == "") {
                                PNotify.removeAll();
                                new PNotify({
                                    title: 'Warning',
                                    text: 'Дэлгэрэнгүй тайлбар заавал оруулна',
                                    type: 'warning',
                                    sticker: false
                                });                           
                                return;
                            }

                            if (!$(dialogName).find('.hiddenFileDiv').children().length) {
                              PNotify.removeAll();
                              new PNotify({
                                  title: 'Warning',
                                  text: 'Файл заавал оруулна',
                                  type: 'warning',
                                  sticker: false
                              });                           
                              return;
                          }                                                        
                            
                            $(dialogName).find('form').ajaxSubmit({
                                type: 'post',
                                url: 'mdproc/save',
                                dataType: 'json',
                                beforeSend: function() {
                                    Core.blockUI({message: 'Loading...', boxed: true});
                                },
                                success: function(data) {
                                    new PNotify({
                                        title: data.status,
                                        text: data.message,
                                        type: data.status,
                                        sticker: false
                                    });
                                    if (data.status == 'success') {
                                        $(dialogName).dialog('close');
                                        if (typeof window['objectdatagrid_1568623775681']  !== 'undefined') {
                                            dataViewReload('1568623775681');    
                                        } else {
                                            dataViewReload('1545116026034');
                                        }
                                    }                                        
                                    Core.unblockUI();
                                },
                                error: function() {
                                    alert('Error');
                                    Core.unblockUI();
                                }
                            });         
                        }
                    }
                },{   
                    text: data.close_btn,
                    class: 'btn blue-madison btn-sm',
                    click: function() {
                        $(dialogName).dialog('close');
                    }
                }]
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

            $(dialogName).dialog('open');
            $(dialogName).dialogExtend("maximize");

            Core.initDVAjax($(dialogName));
            Core.unblockUI();
        },
        error: function() {
            alert('Error');
        }
    });    
}
function procPageEdit(id, url, type) {
    var viewType = type === 'view' ? 'view' : '';    
    
    $.ajax({
        type: 'post',
        url: url,
        data: { id: id, viewType: viewType },
        dataType: 'json',
        beforeSend: function() {
            Core.blockUI({message: 'Loading...', boxed: true});
        },
        success: function(data) {
            var dialogName = '#dialog-procurement-edit';
            if (!$(dialogName).length) {
                $('<div id="' + dialogName.replace('#', '') + '"></div>').appendTo('body');
            }            

            if (viewType === 'view') {
                var btnObj = [{   
                    text: data.close_btn,
                    class: 'btn blue-madison btn-sm',
                    click: function() {
                        $(dialogName).dialog('close');
                    }
                }];
            } else {
                var btnObj = [{
                    text: data.save_btn,
                    class: 'btn green-meadow btn-sm',
                    click: function() {
                        $(dialogName).find('form').validate({ errorPlacement: function() {} });

                        if ($(dialogName).find('form').valid()) {                                
                            $(dialogName).find('form').ajaxSubmit({
                                type: 'post',
                                url: 'mdproc/save',
                                dataType: 'json',
                                beforeSend: function() {
                                    Core.blockUI({message: 'Loading...', boxed: true});
                                },
                                success: function(data) {
                                    new PNotify({
                                        title: data.status,
                                        text: data.message,
                                        type: data.status,
                                        sticker: false
                                    });
                                    if (data.status == 'success') {
                                        $(dialogName).dialog('close');
                                    }                                        
                                    Core.unblockUI();
                                },
                                error: function() {
                                    alert('Error');
                                    Core.unblockUI();
                                }
                            });         
                        }
                    }
                },{   
                    text: data.close_btn,
                    class: 'btn blue-madison btn-sm',
                    click: function() {
                        $(dialogName).dialog('close');
                    }
                }];                
            }            

            $(dialogName).empty().append(data.Html);
            $(dialogName).dialog({
                cache: false,
                resizable: true,
                bgiframe: true,
                autoOpen: false,
                title: data.Title,
                width: 1200,
                height: 'auto',
                modal: true,
                close: function() {
                    $(dialogName).empty().dialog('destroy').remove();
                },
                buttons: btnObj
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

            $(dialogName).dialog('open');
            $(dialogName).dialogExtend("maximize");

            Core.initDVAjax($(dialogName));
            Core.unblockUI();
        },
        error: function() {
            alert('Error');
        }
    });    
}
function procPageView(selectedRow, url, dataViewId) {
    $.ajax({
        type: 'post',
        url: url,
        data: { id: selectedRow.id, selectedRow: selectedRow, dataViewId: dataViewId },
        dataType: 'json',
        beforeSend: function() {
            Core.blockUI({message: 'Loading...', boxed: true});
        },
        success: function(data) {
            var dialogName = '#dialog-procurement-view';
            if (!$(dialogName).length) {
                $('<div id="' + dialogName.replace('#', '') + '"></div>').appendTo('body');
            }

            $(dialogName).empty().append(data.Html);
            $(dialogName).dialog({
                cache: false,
                resizable: true,
                bgiframe: true,
                autoOpen: false,
                title: data.Title,
                width: 1200,
                height: 'auto',
                modal: true,
                close: function() {
                    $(dialogName).empty().dialog('destroy').remove();
                },
                buttons: [{   
                    text: data.close_btn,
                    class: 'btn blue-madison btn-sm',
                    click: function() {
                        $(dialogName).dialog('close');
                    }
                }]
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

            $(dialogName).dialog('open');
            $(dialogName).dialogExtend('maximize');

            Core.initDVAjax($(dialogName));
            Core.unblockUI();
        },
        error: function() { alert('Error'); }
    });    
}
function bpFieldGraphFullScreen(elem) {
    var $this = $(elem), $parent = $this.closest('.bp-graph-field-parent');
    
    if (!$this.hasAttr('data-fullscreen')) {
        
        $this.addClass('bpfieldgraph-fullscreened').attr({'data-fullscreen': '1', 'title': 'Restore'}).find('i').removeClass('fa-expand').addClass('fa-compress');
        $parent.addClass('bp-dtl-fullscreen');
        
        if ($('#bp-field-graph-fs-svg-style').length == 0) {
            var width = $(window).width() - 30, height = $(window).height() - 60;
            $('head').append('<style id="bp-field-graph-fs-svg-style">.svg-d-inline{border:1px #ddd solid} .svg-d-inline svg {width:'+width+'px;height:'+height+'px}</style>');
        }
        
        $.cachedScript('assets/custom/addon/plugins/jquery.panzoom/svg-pan-zoom.min.js').done(function() {
            svgPanZoom($parent.find('svg')[0], {
                zoomEnabled: true,
                controlIconsEnabled: true,
                fit: true,
                center: true,
                zoomScaleSensitivity: 0.3
            });
        });
        
        $(document).bind('keydown', 'Esc', function() {
            if ($('.ui-dialog:visible').length == 0 && $('.bpfieldgraph-fullscreened').length) {
                $('.bpfieldgraph-fullscreened').click();
            }
        });
        $(document.body).on('keydown', 'input, select, textarea, a, button', 'Esc', function(e){
            if ($('.ui-dialog:visible').length == 0 && $('.bpfieldgraph-fullscreened').length) {
                $('.bpfieldgraph-fullscreened').click();
            }
        });
        
    } else {
        
        $this.removeClass('bpfieldgraph-fullscreened').attr('title', 'Fullscreen').removeAttr('data-fullscreen').find('i').removeClass('fa-compress').addClass('fa-expand');
        $parent.removeClass('bp-dtl-fullscreen');
        $('#bp-field-graph-fs-svg-style').remove();
        
        var panZoomSvg = svgPanZoom($parent.find('svg')[0]);
        
        panZoomSvg.resetZoom();
        panZoomSvg.destroy();
        delete panZoomSvg;
    }
    
    return;
}
function bpFileChoose(elem) {
    var $parent = $(elem).closest('.uniform-uploader');
    var $fileInput = $parent.find('input[type="file"]');
    if (!$fileInput.is('[readonly]')) {
        $fileInput.click();
    }
    return;
}
function bpFileChoosedRemove(elem) {
    var $parent = $(elem).closest('.uniform-uploader');
    var $fileInput = $parent.find('input[type="file"]');
    if (!$fileInput.is('[readonly]')) {
        var $fileName = $parent.find('.filename');
        $fileInput.val('');
        $parent.find('input[type="hidden"]').val('');
        $fileName.text($fileName.attr('data-text')).attr('title', $fileName.attr('data-text'));
        $parent.find('a').remove();
    }
    return;
}
function bpDetailFitHeight($window, windowMode) {
    
    if (windowMode == 'dialog') {
        var $visibleRowsDtl = $window.find('div[data-parent-path].bp-overflow-xy-auto:visible');
        var visibleCount = $visibleRowsDtl.length;

        if (visibleCount == 1) {

            var $rowsDtl = $visibleRowsDtl; //$window.find('div[data-parent-path].bp-overflow-xy-auto');
            var dialogContentHeight = $window.height();

            $rowsDtl.each(function() {
                var $dtl = $(this), maxHeight = $dtl.css('max-height');
                $dtl.attr('data-old-maxheight', maxHeight).css('max-height', dialogContentHeight - $dtl.offset().top + 35);
                $dtl.trigger('scroll');
            });
        }
    }
    return;
}
function bpDetailFitHeightRestore($window, windowMode) {
    
    if (windowMode == 'dialog') {
        var $rowsDtl = $window.find('div[data-old-maxheight].bp-overflow-xy-auto');

        if ($rowsDtl.length) {
            $rowsDtl.each(function() {
                var $dtl = $(this);
                $dtl.css('max-height', $dtl.attr('data-old-maxheight'));
                $dtl.removeAttr('data-old-maxheight');
                $dtl.trigger('scroll');
            });
        }
    }
    return;
}
function bpDetailExcelImport(elem) {
    var $this = $(elem);
    var dialogName = '#dialog-bpdtl-excelimport';
    if (!$(dialogName).length) {
        $('<div id="' + dialogName.replace('#', '') + '"></div>').appendTo('body');
    }
    var $dialog = $(dialogName), html = [];

    html.push('<form method="post" enctype="multipart/form-data">');
        html.push('<div class="form-group row mb-2">');
            html.push('<div class="col-md-12"><div class="alert alert-info">Та эхлээд загвар файл татна уу!</div></div>');
        html.push('</div>');
        html.push('<div class="form-group row mb-2">');
            html.push('<label class="col-md-3 col-form-label text-right pt-1 pr0"><span class="required">*</span> Эксель файл сонгох:</label>');
            html.push('<div class="col-md-9"><input type="file" class="form-control" name="excelFile" required="required" onchange="hasExcelExtension(this);"></div>');
        html.push('</div>');
    html.push('</form>');

    $dialog.empty().append(html.join(''));
    $dialog.dialog({
        cache: false,
        resizable: true,
        bgiframe: true,
        autoOpen: false,
        title: 'Excel import',
        width: 600,
        height: 'auto',
        modal: true,
        close: function () {
            $dialog.empty().dialog('destroy').remove();
        },
        buttons: [
            {text: plang.get('download_template_btn'), class: 'btn btn-danger btn-sm float-left', click: function () {
                    
                var $parent = $this.closest('div[data-section-path]'), 
                    groupPath = $parent.attr('data-section-path'), 
                    $parentTable = $parent.find('table[data-table-path="'+groupPath+'"]'), 
                    $headers = $parentTable.find('> thead > tr:eq(0) > th:visible:not(.rowNumber, .hide, .action)');
                
                if ($headers.length) {
                    
                    var labelName = '', path = '', headerData = [];
    
                    $headers.each(function() {
                        $this = $(this);
                        labelName = $this.text();
                        path = $this.attr('data-cell-path');

                        if (labelName != '' && path != '') {
                            path = path.replace(groupPath + '.', '');
                            headerData.push({path: path, labelName: labelName});
                        }
                    });

                    $.fileDownload(URL_APP + 'mdprocess/downloadDetailExcelTemplate', {
                        httpMethod: 'POST',
                        data: {headerData: headerData},
                        successCallback: function (url) {
                            Core.unblockUI();
                        },
                        prepareCallback: function (url) {},
                        failCallback: function (responseHtml, url) {
                            PNotify.removeAll();
                            new PNotify({
                                title: 'Error',
                                text: responseHtml,
                                type: 'error',
                                sticker: false
                            });
                            Core.unblockUI();
                        }
                    });  
                    
                } else {
                    PNotify.removeAll();
                    new PNotify({
                        title: 'Info',
                        text: 'Багана олдсонгүй!',
                        type: 'info',
                        sticker: false
                    });
                }
                
            }},
            {text: plang.get('do_import_btn'), class: 'btn green-meadow btn-sm', click: function () {
                    
                PNotify.removeAll();
                
                var $form = $this.closest('div[data-bp-uniq-id]'), 
                    uniqId = $form.attr('data-bp-uniq-id'), 
                    processId = $form.attr('data-process-id'), 
                    $parent = $this.closest('div[data-section-path]'), 
                    groupPath = $parent.attr('data-section-path');

                window['selectedRowsBpAddRow_'+uniqId]($this, processId, groupPath, '', [], 'excelimport', $dialog);

            }},
            {text: plang.get('close_btn'), class: 'btn blue-madison btn-sm', click: function () {
                $dialog.dialog('close');
            }}
        ]
    });
    $dialog.dialog('open');
}
function bpRecordIdMoreViewByMetaId(elem) {
    var $this = $(elem), $parent = $this.closest('.double-between-input');
    
    if ($parent.length) {
        var $hiddenInputs = $parent.find('input[type="hidden"]'), 
            ids = $hiddenInputs.map(function(){ return this.value; }).get().join(',');
        
        if (ids) {
            
            PNotify.removeAll();
            var moreMetaId = $this.attr('data-more-metaid');
            
            $.ajax({
                type: 'post',
                url: 'mdcommon/renderMoreMeta',
                data: {moreMetaId: moreMetaId, ids: ids},
                dataType: 'json',
                beforeSend: function() {
                    Core.blockUI({boxed: true, message: 'Loading...'});
                },
                success: function(data) {
                    
                    if (data.status == 'success') {
                        
                        var dialogTitle = plang.get('more');
                        
                        if (data.hasOwnProperty('title') && data.title) {
                            dialogTitle = plang.get(data.title);
                        } else if (data.hasOwnProperty('Title') && data.Title) {
                            dialogTitle = plang.get(data.Title);
                        }
                        
                        if (data.metaType == 'METAGROUP') {
                            
                            var $dialogName = 'dialog-recordidmoreview';
                            if (!$("#" + $dialogName).length) {
                                $('<div id="' + $dialogName + '"></div>').appendTo('body');
                            }
                            var $dialog = $('#' + $dialogName), rows = data.rows, columns = data.columns, 
                                html = [], dialogWidth = 1100;
                            
                            if (rows.length == 1) {
                                
                                var row = rows[0];
                                
                                html.push('<table class="table table-bordered table-hover">');
                                    html.push('<thead>');
                                        html.push('<tr>');
                                            html.push('<th style="width: 180px" class="text-center">'+plang.get('info_name')+'</th>');
                                            html.push('<th class="text-center">'+plang.get('value')+'</th>');
                                        html.push('</tr>');
                                    html.push('</thead>');
                                    html.push('<tbody>');
                                        
                                        for (var c in columns) {
                                            
                                            var val = dvFieldValueShow(row[columns[c]['FIELD_PATH']]);
                                            
                                            if (val) {
                                                if (columns[c]['META_TYPE_CODE'] == 'bigdecimal') {
                                                    val = gridAmountField(val);
                                                } else if (columns[c]['META_TYPE_CODE'] == 'boolean') {
                                                    val = gridBooleanField(val);
                                                } else if (columns[c]['META_TYPE_CODE'] == 'star') {
                                                    val = gridStarField(val);
                                                } else if (columns[c]['META_TYPE_CODE'] == 'decimal_to_time') {
                                                    val = gridNumberToTime(val);
                                                } else if (columns[c]['META_TYPE_CODE'] == 'html_decode') {
                                                    val = gridHtmlDecode(val);
                                                } else if (columns[c]['META_TYPE_CODE'] == 'file') {
                                                    val = gridFileField(val, row);
                                                } else if (columns[c]['META_TYPE_CODE'] == 'password') {
                                                    val = gridPasswordField(val);
                                                } else if (columns[c]['META_TYPE_CODE'] == 'time') {
                                                    val = dateFormatter('H:i', val);
                                                } else if (columns[c]['META_TYPE_CODE'] == 'bigdecimal_null') {
                                                    val = gridAmountNullField(val);
                                                } else if (columns[c]['META_TYPE_CODE'] == 'date') {
                                                    val = dateFormatter('Y-m-d', val);
                                                }
                                            }
                                            
                                            html.push('<tr>');
                                                html.push('<td class="text-right">'+plang.get(columns[c]['LABEL_NAME'])+'</td>');
                                                html.push('<td class="text-left">'+val+'</td>');
                                            html.push('</tr>');
                                        }
                                        
                                    html.push('</tbody>');
                                html.push('</table>');
                                
                                dialogWidth = 600;
                                
                            } else {
                                
                                html.push('<table class="table table-bordered table-hover">');
                                    html.push('<thead>');
                                        html.push('<tr>');
                                        
                                        for (var c in columns) {
                                            var styles = '';
                                            if (columns[c]['COLUMN_WIDTH']) {
                                                styles = ' style="width: '+columns[c]['COLUMN_WIDTH']+'"';
                                            }
                                            html.push('<th class="text-center"'+styles+'>'+plang.get(columns[c]['LABEL_NAME'])+'</th>');
                                        }
                                        
                                        html.push('</tr>');
                                    html.push('</thead>');
                                    html.push('<tbody>');
                                    
                                    for (var r in rows) {
                                        
                                        var row = rows[r];
                                        
                                        html.push('<tr>');
                                        
                                        for (var c in columns) {
                                            
                                            var val = dvFieldValueShow(row[columns[c]['FIELD_PATH']]);
                                            
                                            if (val) {
                                                if (columns[c]['META_TYPE_CODE'] == 'bigdecimal') {
                                                    val = gridAmountField(val);
                                                } else if (columns[c]['META_TYPE_CODE'] == 'boolean') {
                                                    val = gridBooleanField(val);
                                                } else if (columns[c]['META_TYPE_CODE'] == 'star') {
                                                    val = gridStarField(val);
                                                } else if (columns[c]['META_TYPE_CODE'] == 'decimal_to_time') {
                                                    val = gridNumberToTime(val);
                                                } else if (columns[c]['META_TYPE_CODE'] == 'html_decode') {
                                                    val = gridHtmlDecode(val);
                                                } else if (columns[c]['META_TYPE_CODE'] == 'file') {
                                                    val = gridFileField(val, row);
                                                } else if (columns[c]['META_TYPE_CODE'] == 'password') {
                                                    val = gridPasswordField(val);
                                                } else if (columns[c]['META_TYPE_CODE'] == 'time') {
                                                    val = dateFormatter('H:i', val);
                                                } else if (columns[c]['META_TYPE_CODE'] == 'bigdecimal_null') {
                                                    val = gridAmountNullField(val);
                                                } else if (columns[c]['META_TYPE_CODE'] == 'date') {
                                                    val = dateFormatter('Y-m-d', val);
                                                }
                                            }
                                            
                                            html.push('<td>'+val+'</td>');
                                        }
                                        
                                        html.push('</tr>');
                                    }
                                    
                                    html.push('</tbody>');
                                html.push('</table>');    
                            }

                            $dialog.empty().append(html.join(''));  
                            $dialog.dialog({
                                resizable: true,
                                bgiframe: true,
                                autoOpen: false,
                                title: dialogTitle,
                                width: dialogWidth,
                                height: 'auto',
                                modal: false,
                                close: function () {
                                    $dialog.empty().dialog('destroy').remove();
                                },
                                buttons: [ 
                                    {text: plang.get('close_btn'), class: 'btn blue-madison btn-sm', click: function () {
                                        $dialog.dialog('close');
                                    }}
                                ]
                            });
                            $dialog.dialog('open');
                            Core.unblockUI();
                            
                        } else if (data.metaType == 'PACKAGE') {
                            
                            var $dialogName = 'dialog-recordidmoreview';
                            if (!$("#" + $dialogName).length) {
                                $('<div id="' + $dialogName + '"></div>').appendTo('body');
                            }
                            var $dialog = $('#' + $dialogName);
                            
                            $dialog.empty().append(data.Html);  
                            $dialog.dialog({
                                resizable: true,
                                bgiframe: true,
                                autoOpen: false,
                                title: dialogTitle,
                                width: 1000,
                                height: 'auto',
                                modal: true,
                                position: {my: 'top', at: 'top+10'},
                                close: function () {
                                    $dialog.empty().dialog('destroy').remove();
                                },
                                buttons: [ 
                                    {text: plang.get('close_btn'), class: 'btn blue-madison btn-sm', click: function () {
                                        $dialog.dialog('close');
                                    }}
                                ]
                            });
                            $dialog.dialog('open');
                        }
                        
                    } else {
                        new PNotify({
                            title: data.status,
                            text: data.message,
                            type: data.status,
                            sticker: false
                        });
                        Core.unblockUI();
                    }
                }, 
                error: function() { alert('Error'); Core.unblockUI(); }
            });
        }
        
    } else {
        console.log('combo');
    }
    
    return;
}
function bpRecordHistoryLogList(elem, dvId, refStructureId) {
    if (typeof IS_LOAD_LOG_SCRIPT === 'undefined') {
        $.getScript('middleware/assets/js/log/script.js').done(function() {
            bpRecordHistoryLogListInit(elem, dvId, refStructureId);
        });
    } else {
        bpRecordHistoryLogListInit(elem, dvId, refStructureId);
    }
}
function bpRecordHistoryRemovedLogList(elem, dvId, refStructureId, isLogRecover) {
    if (typeof IS_LOAD_LOG_SCRIPT === 'undefined') {
        $.getScript('middleware/assets/js/log/script.js').done(function() {
            bpRecordHistoryRemovedLogListInit(elem, dvId, refStructureId, isLogRecover);
        });
    } else {
        bpRecordHistoryRemovedLogListInit(elem, dvId, refStructureId, isLogRecover);
    }
}
function bpRecordLogDetailView(elem, logId, isRemovedLog) {
    if (typeof IS_LOAD_LOG_SCRIPT === 'undefined') {
        $.getScript('middleware/assets/js/log/script.js').done(function() {
            bpRecordLogDetail(elem, logId, isRemovedLog);
        });
    } else {
        bpRecordLogDetail(elem, logId, isRemovedLog);
    }
}
function bpFieldTranslate(elem) {
    var $this = $(elem), $parent = $this.closest('.input-group');
    $parent = $parent.length ? $parent : $this.closest('.input-group-html-editor');
    
    $.ajax({
        type: 'post',
        url: 'mdlanguage/getLanguagePackage',
        dataType: 'json',
        beforeSend: function() {
            Core.blockUI({boxed: true, message: 'Loading...'});
        },
        success: function(data) {
            
            var $dialogName = 'dialog-bpfield-translate-'+getUniqueId('l');
            if (!$("#" + $dialogName).length) {
                $('<div id="' + $dialogName + '"></div>').appendTo('body');
            }
            var $dialog = $('#' + $dialogName), langTbl = [], 
                langCode = data.langCode, defaultLangCode = data.defaultLangCode, langList = data.list, 
                $inputBox = $parent.find('> [data-path]:eq(0)'), 
                isInputbox = false, /*$inputBox.prop('tagName') == 'INPUT' ? true : false, */
                dialogTitle = $inputBox.attr('placeholder'), mainSelector = $this.closest('[data-bp-uniq-id]'), 
                realPath = $inputBox.attr('data-path'), translationJson = 'pfTranslationValue', 
                $translatePath = $inputBox.next('[data-translate-path]');

            if ($translatePath.length) {
                var translationObj = JSON.parse($translatePath.val());
            } else {
                if (realPath.indexOf('.') !== -1) {
                    translationJson = realPath.substr(0, realPath.lastIndexOf('.')) + '.' + translationJson;
                } 
                
                var $bpElem = getBpElement(mainSelector, $inputBox, translationJson), translationObj = [];

                if (!$bpElem.length) {
                    if ($this.closest('#glEntryForm').length) {
                        $bpElem = $this.closest('#glEntryForm').find('[data-path="pfTranslationValue"]');
                    } else {
                        var $form = $this.closest('form');
                        $bpElem = $form.find('[data-path="pfTranslationValue"]');
                    }
                }

                if ($bpElem.length && $bpElem.val() != '') {
                    var colName = $inputBox.attr('data-c-name');
                    translationObj = JSON.parse($bpElem.val());

                    if (colName && translationObj.hasOwnProperty('value')) {
                        translationObj = translationObj.value;
                        if (translationObj.hasOwnProperty(colName)) {
                            translationObj = translationObj[colName];
                        }
                    }
                }
            }
            
            if ($inputBox.hasAttr('data-dl-value')) {
                translationObj[defaultLangCode] = $inputBox.attr('data-dl-value');
            }
            
            langTbl.push('<table class="table table-bordered table-hover">');
                langTbl.push('<tbody>');
                    
                    for (var l in langList) {
                        
                        if (langCode != langList[l]['SHORT_CODE']) {
                            
                            var translateText = translationObj.hasOwnProperty(langList[l]['SHORT_CODE']) ? translationObj[langList[l]['SHORT_CODE']] : '';
                            
                            langTbl.push('<tr>');
                                langTbl.push('<td style="width: 120px;"><img src="assets/core/global/img/flags/'+langList[l]['SHORT_CODE']+'.png"> '+langList[l]['LANGUAGE_NAME']+'</td>');
                                langTbl.push('<td class="p-0 pr8">');

                                    if (isInputbox) {
                                        langTbl.push('<input type="text" class="form-control rounded-0 border-transparent" placeholder="'+langList[l]['LANGUAGE_NAME']+'" value="'+translateText+'" data-langcode="'+langList[l]['SHORT_CODE']+'">');
                                    } else {
                                        langTbl.push('<textarea class="form-control rounded-0 border-transparent" rows="2" placeholder="'+langList[l]['LANGUAGE_NAME']+'" data-langcode="'+langList[l]['SHORT_CODE']+'">'+translateText+'</textarea>');
                                    }

                                langTbl.push('</td>');
                            langTbl.push('</tr>');
                        }
                    }
                    
                langTbl.push('</tbody>');
            langTbl.push('</table>');

            $dialog.empty().append(langTbl.join(''));  
            $dialog.dialog({
                dialogClass: 'no-padding-dialog',
                resizable: true,
                bgiframe: true,
                autoOpen: false,
                title: (dialogTitle ? dialogTitle + ' - Орчуулга' : 'Орчуулга'),
                width: 550,
                height: 'auto',
                modal: true,
                close: function () {
                    $dialog.empty().dialog('destroy').remove();
                },
                buttons: [ 
                    {text: plang.get('save_btn'), class: 'btn green-meadow btn-sm', click: function () {
                        
                        if ($dialog.find('[data-changed]').length) {
                            
                            var $langInputs = $dialog.find('[data-langcode]'), langSave = {};
                            
                            $langInputs.each(function() {
                                var $this = $(this), thisVal = $this.val();
                                if (thisVal != '') {
                                    langSave[$this.attr('data-langcode')] = thisVal.replace(/"/g, '\"');
                                }
                            });
                            
                            if ($translatePath.length) {
                                $translatePath.val(JSON.stringify(langSave));
                            } else {
                                var name = $inputBox.attr('name').replace('param['+realPath+']', 'param['+realPath+'_translation]');
                                if (name.indexOf(realPath+'_translation') === -1) {
                                    name = name.replace(realPath, realPath+'_translation');
                                }
                                $inputBox.after('<textarea name="'+name+'" style="display:none" spellcheck="false" aria-hidden="true" data-translate-path="'+realPath+'">'+JSON.stringify(langSave)+'</textarea>');
                            }
                            
                            if ($inputBox.hasAttr('data-dl-value')) {
                                $inputBox.removeAttr('data-dl-value');
                            }
                            
                            $parent.find('[data-path]').trigger('change');
                        }
                        
                        $dialog.dialog('close');
                    }}, 
                    {text: plang.get('close_btn'), class: 'btn blue-madison btn-sm', click: function () {
                        $dialog.dialog('close');
                    }}
                ]
            }).dialogExtend({
                "closable": true,
                "maximizable": true,
                "minimizable": false,
                "collapsable": false,
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
            $dialog.dialog('open');
            
            Core.unblockUI();
            
            $dialog.on('keydown', 'input[type="text"], textarea', function(e) {
                    
                var keyCode = (e.keyCode ? e.keyCode : e.which);

                if (keyCode == 38) { /*up*/

                    var $this = $(this), 
                        $row = $this.closest('tr'), 
                        $cell = $this.closest('td'), 
                        colIndex = $cell.index(), 
                        $prevRow = $row.prev('tr');

                    if ($prevRow.length) {
                        $prevRow.find('td:eq('+colIndex+') > input, td:eq('+colIndex+') > textarea').focus().select();
                        return e.preventDefault();
                    }
                    
                } else if (keyCode == 40) { /*down*/

                    var $this = $(this), 
                        $row = $this.closest('tr'), 
                        $cell = $this.closest('td'), 
                        colIndex = $cell.index(), 
                        $nextRow = $row.next('tr');

                    if ($nextRow.length) {
                        $nextRow.find('td:eq('+colIndex+') > input, td:eq('+colIndex+') > textarea').focus().select();
                        return e.preventDefault();
                    }
                }
            });
            
            $dialog.on('change', 'input[type="text"], textarea', function() {
                $(this).attr('data-changed', 1);
            });
        }
    });
}
function bpDataTemplateSave(elem) {
    PNotify.removeAll();
    
    var $this = $(elem), $parent = $this.closest('.input-group'), 
        $input = $parent.find('input[type="text"]');
    
    if (($input.val()).trim() != '') {
        
        var $form = $this.closest('form');
        
        $form.ajaxSubmit({
            type: 'post',
            url: 'mdwebservice/runProcess',
            dataType: 'json',
            beforeSubmit: function(formData, jqForm, options) {
                formData.push({name: 'isOnlyTemplate', value: 1});
            },
            beforeSend: function() {
                Core.blockUI({message: 'Loading...', boxed: true});
            },
            success: function(responseData) {
                
                new PNotify({
                    title: responseData.status,
                    text: responseData.message,
                    type: responseData.status,
                    addclass: pnotifyPosition,
                    sticker: false
                });
                
                if (responseData.status == 'success') {
                    
                    $input.val('');
                    
                    /*$form.closest('[data-bp-uniq-id]').attr('data-bp-uniq-id', responseData.uniqId);*/
                    $form.find('input[name="windowSessionId"]').val(responseData.uniqId);
                    
                    var $parentDropdown = $this.closest('.dropdown-menu'), 
                        $dropdown = $parentDropdown.find('ul.media-list');

                    $.ajax({
                        type: 'post',
                        url: 'mduser/getBpValueTemplate',
                        data: {processId: $this.closest('[data-bp-uniq-id]').attr('data-process-id')}, 
                        dataType: 'json',
                        async: false, 
                        success: function (data) {

                            if (data.length) {

                                var list = [];

                                for (var k in data) {
                                    list.push(bpValueTemplateItem({id: data[k]['ID'], name: data[k]['NAME'], isEdit: data[k]['IS_EDIT'], selectedId: null}));
                                }

                                $dropdown.empty().append(list.join(''));
                            }
                        }
                    });
                }
                
                Core.unblockUI();
            }
        });
        
    } else {
        new PNotify({
            title: 'Info',
            text: 'Та загварын нэрээ оруулна уу!',
            type: 'info',
            addclass: pnotifyPosition,
            sticker: false
        });
    }
}
function bpFieldPropertyGrid(elem) {
        
    var $dialogName = 'dialog-fieldpropertygrid';
    if (!$("#" + $dialogName).length) {
        $('<div id="' + $dialogName + '"></div>').appendTo('body');
    }
    var $dialog = $('#' + $dialogName), 
        $textarea = $(elem).closest('.input-group').find('textarea');

    $.ajax({
        type: 'post',
        url: 'mdprocess/bpFieldPropertyGrid',
        data: {type: $textarea.attr('data-type')},
        dataType: 'json',
        beforeSend: function() {
            Core.blockUI({message: 'Loading...', boxed: true});
        },
        success: function(data) {
            
            var tbl = [], propertyList = data.propertyList, jsonValue = {};
            
            if ($textarea.val() != '') {
                jsonValue = JSON.parse($textarea.val());
            }
            
            tbl.push('<table class="table table-bordered table-hover mb-2">');
                tbl.push('<tbody>');
                    
                    for (var p in propertyList) {
                        
                        var valueType = propertyList[p]['valueType'];
                        var propertyVal = jsonValue.hasOwnProperty(propertyList[p]['code']) ? jsonValue[propertyList[p]['code']] : '';

                        tbl.push('<tr>');
                            tbl.push('<td style="width: 180px;">'+propertyList[p]['label']+'</td>');
                            tbl.push('<td class="p-0">');
                                
                                if (valueType == 'boolean') {
                                    tbl.push('<input type="checkbox" class="notuniform ml-2" value="1" data-code="'+propertyList[p]['code']+'"'+(propertyVal == '1' ? ' checked' : '')+'>');
                                } else if (valueType == 'color') {
                                    tbl.push('<div class="input-group color bp-color-picker" data-color="'+propertyVal+'"><input type="text" class="form-control rounded-0 border-transparent" value="'+propertyVal+'" data-code="'+propertyList[p]['code']+'"><span class="input-group-btn"><button tabindex="-1" onclick="initBpColorPicker(this); return false;" class="btn default border-left-0 mr-0 p-0" style="height: 25px;border-radius: 0 3px 3px 0;"><i style="position:relative; rigth: 0; margin-left: 14px; background-color: '+propertyVal+'"></i></button></span></div>');
                                } else if (valueType == 'halign') {
                                    
                                    var centerOpt = '', leftOpt = '', rightOpt = '';
                                    if (propertyVal == 'center') {
                                        centerOpt = ' selected';
                                    } else if (propertyVal == 'left') {
                                        leftOpt = ' selected';
                                    } else if (propertyVal == 'right') {
                                        rightOpt = ' selected';
                                    }
                                    
                                    tbl.push('<select class="form-control rounded-0 border-transparent" data-code="'+propertyList[p]['code']+'"><option value=""></option><option value="center"'+centerOpt+'>Center</option><option value="left"'+leftOpt+'>Left</option><option value="right"'+rightOpt+'>Right</option></select>');
                                    
                                } else if (valueType == 'fontWeight') {
                                    
                                    var boldOpt = '';
                                    if (propertyVal == 'bold') {
                                        boldOpt = ' selected';
                                    } 
                                    
                                    tbl.push('<select class="form-control rounded-0 border-transparent" data-code="'+propertyList[p]['code']+'"><option value=""></option><option value="bold"'+boldOpt+'>Bold</option></select>');
                                    
                                } else if (valueType == 'long') {
                                    tbl.push('<input type="text" class="form-control rounded-0 border-transparent longInit" value="'+propertyVal+'" data-code="'+propertyList[p]['code']+'">');
                                } else {
                                    tbl.push('<input type="text" class="form-control rounded-0 border-transparent" value="'+propertyVal+'" data-code="'+propertyList[p]['code']+'">');
                                }

                            tbl.push('</td>');
                        tbl.push('</tr>');
                    }
                    
                tbl.push('</tbody>');
            tbl.push('</table>');
            
            $dialog.empty().append(tbl.join(''));
            $dialog.dialog({
                cache: false,
                resizable: true,
                bgiframe: true,
                autoOpen: false,
                title: 'Properties',
                width: 450,
                minWidth: 450,
                height: 'auto',
                modal: true,
                open: function () {
                    Core.initLongInput($dialog);
                },
                close: function () {
                    $dialog.empty().dialog('destroy').remove();
                },
                buttons: [
                    {text: plang.get('save_btn'), class: 'btn btn-sm green bp-btn-subsave', click: function() {
                            
                        var $propertyInputs = $dialog.find('.table input, .table select'), propertySave = {};
                            
                        $propertyInputs.each(function() {
                            var $this = $(this), value = '';
                            
                            if ($this.is(':checkbox')) {
                                value = $this.is(':checked') ? '1' : '';
                            } else {
                                value = $this.val();
                            }
                            
                            if (value != '') {
                                propertySave[$this.attr('data-code')] = value;
                            }
                        });
                        
                        $textarea.val(JSON.stringify(propertySave));
                        
                        $dialog.dialog('close');
                    }},
                    {text: plang.get('close_btn'), class: 'btn btn-sm blue-hoki', click: function() {
                        $dialog.dialog('close');
                    }}
                ]
            });
            $dialog.dialog('open');

            Core.unblockUI();
        }
    });
        
    return;
}
function bpResultConsole(status, message) {}
function bpRadioAllRowsByPopup(elem, lookupName, lookupMetaDataId, processMetaDataId) {
    var $dialogName = 'dialog-bpradioallrowsbypopup';
    var $element = $(elem);
    var $parent = $element.closest('[data-section-path]');
    var $radio = $parent.find('input[type=radio]'), selectedVal = '';
    
    if ($radio.is(':checked')) {
        selectedVal = $parent.find('input[type=radio]:checked').val();
    }
        
    $.uniform.restore($radio);
    var $parentClone = $parent.clone(true);
    
    Core.initUniform($parent);
    
    $parentClone.find('.bp-layout-anchor-label-color').remove();
    $parentClone.find('.d-none').removeClass('d-none');
    
    var $html = $parentClone.html();
    var windowHeight = $(window).height() - 110;
    
    $('<div class="modal fade" id="' + $dialogName + '" tabindex="-1" role="dialog" aria-hidden="true">' +
            '<div class="modal-dialog">' +
            '<div class="modal-content">' +
            '<div class="modal-header">' +
            '<h4 class="modal-title">' + lookupName + '</h4>' +
            '<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>' +
            '</div>' +
            '<div class="modal-body pt-3 pb-3 bp-control-row-break overflow-auto" style="max-height: '+windowHeight+'px">' + 
            $html + 
            '</div>' +
            '<div class="modal-footer">' +
            '<button type="button" data-dismiss="modal" class="btn blue-hoki btn-sm">' + plang.get('close_btn') + '</button>' +
            '</div></div></div></div>').appendTo('body');

    var $dialog = $('#' + $dialogName);
    
    Core.initUniform($dialog);
    
    if (selectedVal != '') {
        $dialog.find("input[type='radio'][value='"+selectedVal+"']").prop('checked', true);
        $.uniform.update($dialog.find("input[type='radio']"));
    }
        
    $dialog.modal();
    
    $dialog.on('shown.bs.modal', function() {
        disableScrolling();
    });
    $dialog.on('hidden.bs.modal', function() {
        $dialog.remove();
        enableScrolling();
    });
    
    $dialog.on('click', 'input[type="radio"]', function() {
        var $this = $(this), radioVal = $this.attr('value');
        $parent.find('input[type="radio"][value="'+radioVal+'"]').click();
        $dialog.modal('hide');
    });
}
function bpBpmnTool(elem) {
    alert("Процессийн нэмэлтүүд хэсэгт тохируулдаг болсон.");
    return;
    Core.blockUI({message: 'Loading...', boxed: true});
    $.cachedScript('https://unpkg.com/bpmn-js@8.9.1/dist/bpmn-modeler.development.js').done(function() { 
        var $dialogName = 'dialog-bpmnEditor';
        if (!$("#" + $dialogName).length) {
            $('<div id="' + $dialogName + '"></div>').appendTo('body');
        }
        var $dialog = $('#' + $dialogName);         
        $.ajax({
            type: 'post',
            url: 'mdbpmn/bpmn2',
            data: {
                bpmnxml: $(elem).parent().find('input[type="hidden"]').val(),
                bpUniqId: $(elem).closest('div.main-action-meta').attr('data-bp-uniq-id'),
                bpPath: $(elem).parent().find('input[type="hidden"]').data('path')
            },
            dataType: 'json',
            beforeSend: function() {
                if ($("link[href='https://unpkg.com/bpmn-js@8.9.1/dist/assets/diagram-js.css']").length == 0) {
                    $('head').append('<link rel="stylesheet" type="text/css" href="https://unpkg.com/bpmn-js@8.9.1/dist/assets/diagram-js.css"/>');
                }                
                if ($("link[href='https://unpkg.com/bpmn-js@8.9.1/dist/assets/bpmn-font/css/bpmn.css']").length == 0) {
                    $('head').append('<link rel="stylesheet" type="text/css" href="https://unpkg.com/bpmn-js@8.9.1/dist/assets/bpmn-font/css/bpmn.css"/>');
                }                
            },
            success: function(data) {
                $dialog.empty().append(data.html);
                $dialog.dialog({
                    cache: false,
                    resizable: true,
                    bgiframe: true,
                    autoOpen: false,
                    title: $("[data-path=\"code\"]").val()+' '+$("[data-path=\"name\"]").val(),
                    width: 1200,
                    minWidth: 1200,
                    height: "auto",
                    modal: false,
                    close: function () {
                        $dialog.empty().dialog('destroy').remove();
                    },
                    buttons: [
                        {text: plang.get('close_btn'), class: 'btn btn-sm blue-hoki', click: function() {
                            $dialog.dialog('close');
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
                    }, 
                    "maximize" : function() { 
                    }, 
                    "restore" : function() { 
                    }
                });
                $dialog.dialog('open');
                $dialog.dialogExtend('maximize');
                Core.initSelect2($dialog);
                
                Core.unblockUI();
            }
        });
    });
        
    return;
}
function bpHtmlToText (text) {
    return text.replace(/<style[^>]*>.*<\/style>/gm, '')
	// Remove script tags and content
	.replace(/<script[^>]*>.*<\/script>/gm, '')
	// Remove all opening, closing and orphan HTML tags
	.replace(/<[^>]+>/gm, '')
	// Remove leading spaces and repeated CR/LF
	.replace(/([\r\n]+ +)+/gm, '');
}
function previewReportTemplateFromBp(elem, bpId, uniqId, reportTemplateCode, processForm) {
    var $saveBtn = $(elem);
    var $parentForm = (typeof processForm == 'undefined' ? $saveBtn.closest('form') : processForm);

    Core.blockUI({message: 'Loading...', boxed: true});

    $saveBtn.attr({ 'disabled': 'disabled' }).prepend('<i class="fa fa-spinner fa-pulse fa-fw"></i>');

    setTimeout(function() {
        
        $parentForm.ajaxSubmit({
            type: 'post',
            url: 'mdtemplate/previewTempDataFromBp',
            dataType: 'json',
            async: false,
            beforeSubmit: function(formData, jqForm, options) {
                
                formData.push({name: 'metaDataCode', value: reportTemplateCode});
        
                if ($saveBtn.hasAttr('data-report-template-id') && $saveBtn.attr('data-report-template-id') != '') {
                    formData.push({name: 'metaDataId', value: $saveBtn.attr('data-report-template-id')});
                }
                
                var $kpiRows = $parentForm.find('tr[data-is-input="1"]');
                var kpiRowsLength = $kpiRows.length, i = 0;
                
                if (kpiRowsLength) {
                    
                    for (i; i < kpiRowsLength; i++) { 
                        
                        var $kpiRow = $($kpiRows[i]);
                        var $facts = $kpiRow.find('[data-cell-path]');
                        var factsLength = $facts.length, f = 0;
                        
                        if (factsLength) {
                            var rowCode = $kpiRow.attr('data-dtl-code');
                            
                            for (f; f < factsLength; f++) { 
                                var $kpiFact = $($facts[f]);
                                var $field = $kpiFact.find('[data-field-name]:eq(0)');
                                
                                if ($field.length) {
                                    var fieldVal = $field.val();
                                    
                                    if (fieldVal != '') {
                                        rowCode = rowCode.toLowerCase();
                                        var factCode = $field.attr('data-field-name');
                                        formData.push({name: 'kpiParam[kpi.'+rowCode+'.'+factCode+']', value: $field.val()});
                                    }
                                }
                            }
                        }
                    }
                }
            },
            success: function(data) {
                
                PNotify.removeAll();

                if (data.status == 'success') {
                    
                    if (data.hasOwnProperty('printData')) {
                        
                        var printElementClass = 'bp-response-print';
                        if (!$('.' + printElementClass).length) {
                            $('<div class="' + printElementClass + ' d-none"></div>').appendTo('body');
                        }
                        var $posPrintElement = $('.' + printElementClass);

                        $posPrintElement.html(data.printData).promise().done(function() {
                            $posPrintElement.printThis({
                                debug: false,
                                importCSS: false,
                                printContainer: false,
                                dataCSS: data.css,
                                removeInline: false
                            });
                        });
                        
                    } else {
                        
                        if (typeof printJS === 'function') {
                            printJS(URL_APP + data.message);           
                        } else {
                            $.cachedScript('assets/custom/addon/plugins/printjs/print.min.js').done(function() { 
                                $('head').append('<link rel="stylesheet" type="text/css" href="assets/custom/addon/plugins/printjs/print.min.css"/>');
                                printJS(URL_APP + data.message);           
                            });
                        }   
                    }

                } else {
                    new PNotify({
                        title: data.status,
                        text: data.message,
                        type: data.status,
                        addclass: pnotifyPosition,
                        sticker: false
                    });
                }
            }
        });
        
        Core.unblockUI();
        $saveBtn.removeAttr('disabled').find('i:eq(0)').remove();

    }, 200);
}
function bpFieldTextEditorClickToEdit(elem, triggerChange, callback) {    
    var $this = $(elem), 
        $parent = $this.closest('.input-group'), 
        $contenteditable = $parent.find('[contenteditable="true"]'), 
        $textarea = $parent.find('textarea');
    
    var dialogName = '#dialog-texteditor-clicktoedit';
    if (!$(dialogName).length) {
        $('<div id="' + dialogName.replace('#', '') + '"></div>').appendTo('body');
    }
    var $dialog = $(dialogName), html = [];

    html.push('<div class="row">');
        html.push('<div class="col-md-12">');
            html.push('<textarea class="text_editor_ckedtorInit" id="text_editor_ckedtorInit" spellcheck="false" name="text_editor_ckedtorInit"></textarea>');
        html.push('</div>');
    html.push('</div>');    

    $dialog.empty().append(html.join(''));
    $dialog.dialog({
        cache: false,
        resizable: true,
        bgiframe: true,
        autoOpen: false,
        title: 'HTML Editor',
        width: 600,
        height: 'auto',
        modal: true,
        open: function () {
            Core.initTinymceEditor($dialog);
            
            setTimeout(function() {
                var ckeditorIns = CKEDITOR.instances['text_editor_ckedtorInit'];
                var dialogHeight = $dialog.height();
                ckeditorIns.setData($textarea.val());
                ckeditorIns.resize('100%', dialogHeight - 10);
            }, 450);
        },
        close: function () {
            $dialog.empty().dialog('destroy').remove();
        },
        buttons: [
            {text: plang.get('save_btn'), class: 'btn green-meadow btn-sm bp-btn-subsave', click: function () {
                var ckeditorIns = CKEDITOR.instances['text_editor_ckedtorInit'];
                var ckeditorInsData = ckeditorIns.getData();
                
                $contenteditable.html(ckeditorInsData);
                $textarea.val(ckeditorInsData);
                
                if (typeof triggerChange !== 'undefined') {
                    $textarea.trigger('change');
                }

                if (typeof callback !== 'undefined') {
                    window[callback]($this, $textarea.val());
                }

                $contenteditable.trigger('customEventHtmlClickToEdit');
                
                $dialog.dialog('close');
            }},
            {text: plang.get('close_btn'), class: 'btn blue-madison btn-sm', click: function () {
                $dialog.dialog('close');
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
    $dialog.dialog('open');
    $dialog.dialogExtend('maximize');
}
function bpFieldTextEditorTinymceClickToEdit(elem) {
    var textEditorDefaultStyleString = '';
    if (typeof textEditorDefaultStyle !== 'undefined' && textEditorDefaultStyle) {
        textEditorDefaultStyleString = textEditorDefaultStyle;
    }
    var $this = $(elem), 
        $parent = $this.closest('.input-group'), 
        $contenteditable = $parent.find('[contenteditable="true"]'), 
        $textarea = $parent.find('textarea');
    
    var dialogName = '#dialog-texteditor-tinymceclicktoedit';
    if (!$(dialogName).length) {
        $('<div id="' + dialogName.replace('#', '') + '"></div>').appendTo('body');
    }
    var $dialog = $(dialogName), html = [];

    html.push('<div class="row">');
        html.push('<div class="col-md-12">');
            html.push('<textarea class="text_editorInit" id="text_editorInit" spellcheck="false" name="text_editorInit"></textarea>');
        html.push('</div>');
    html.push('</div>');    
    
    if(typeof tinymce === 'undefined') {
        $.cachedScript('assets/custom/addon/plugins/tinymce/tinymce.min.js', {async: false});
    }

    $dialog.empty().append(html.join(''));
    $dialog.dialog({
        cache: false,
        resizable: true,
        bgiframe: true,
        autoOpen: false,
        title: 'HTML Editor',
        width: 600,
        height: 'auto',
        modal: true,
        open: function () {
            /*Core.initTinymceEditor($dialog);
            
            setTimeout(function() {
                var tinymceEditorIns = tinymce.get('text_editorInit');
                var dialogHeight = $dialog.height();
                tinymceEditorIns.setContent($textarea.val());
                tinymceEditorIns.theme.resizeTo('100%', dialogHeight - 10);
            }, 400);*/

            var _tinymceHeight = $(window).height() - 250;
            _tinymceHeight = (_tinymceHeight <= 100) ? '400px' : _tinymceHeight+ 'px';

            tinymce.dom.Event.domLoaded = true;
            tinymce.baseURL = URL_APP+'assets/custom/addon/plugins/tinymce';
            tinymce.suffix = ".min";
            tinymce.init({
                selector: 'textarea#text_editorInit',
                height: _tinymceHeight,
                plugins: [
                    'advlist autolink lists link image charmap print preview hr anchor pagebreak',
                    'searchreplace wordcount visualblocks visualchars code fullscreen codemirror',
                    'insertdatetime media nonbreaking save table contextmenu directionality importcss',
                    'emoticons template paste textcolor colorpicker textpattern imagetools moxiemanager lineheight'
                ],
                toolbar1: 'undo redo | styleselect | bold italic underline | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image',
                toolbar2: 'print preview | forecolor backcolor | fontselect | fontsizeselect | lineheightselect | table | fullscreen | code',
                fontsize_formats: '8pt 9pt 10pt 11pt 12pt 13pt 14pt 15pt 16pt 17pt 18pt 19pt 20pt 21pt 22pt 23pt 24pt 25pt 36pt 8px 9px 10px 11px 12px 13px 14px 15px 16px 17px 18px 19px 20px 21px 22px 23px 24px 25px 36px', 
                lineheight_formats: '1.0 1.15 1.5 2.0 2.5 3.0 8px 9px 10px 11px 12px 13px 14px 15px 16px 17px 18px 19px 20px',
                image_advtab: true, 
                force_br_newlines: true,
                force_p_newlines: false, 
                apply_source_formatting: true,
                
                /*cleanup_on_startup: false,
                trim_span_elements: false,
                verify_html: false,
                cleanup: false,
                convert_urls: false,
                fix_table_elements: false,
                invalid_elements:'', 
                valid_elements: '*[*]',*/
                
                remove_linebreaks: false,
                forced_root_block: '', 
                paste_data_images: true, 
                importcss_append: true,  
                table_toolbar: '', 
                font_formats: "Andale Mono=andale mono,monospace;"+
                    "Arial=arial,helvetica,sans-serif;"+
                    "Arial Black=arial black,sans-serif;"+
                    "Book Antiqua=book antiqua,palatino,serif;"+
                    "Comic Sans MS=comic sans ms,sans-serif;"+
                    "Courier New=courier new,courier,monospace;"+
                    "Georgia=georgia,palatino,serif;"+
                    "Helvetica=helvetica,arial,sans-serif;"+
                    "Impact=impact,sans-serif;"+
                    "Symbol=symbol;"+
                    "Tahoma=tahoma,arial,helvetica,sans-serif;"+
                    "Terminal=terminal,monaco,monospace;"+
                    "Times New Roman=times new roman,times,serif;"+
                    "Calibri=Calibri, sans-serif;"+
                    "Trebuchet MS=trebuchet ms,geneva,sans-serif;"+
                    "Verdana=verdana,geneva,sans-serif;"+
                    "Webdings=webdings;"+
                    "Wingdings=wingdings,zapf dingbats;",
                table_class_list: [
                    {title: 'None', value: ''}, 
                    {title: 'No border', value: 'pf-report-table-none'}, 
                    {title: 'Dotted', value: 'pf-report-table-dotted'}, 
                    {title: 'Dashed', value: 'pf-report-table-dashed'},  
                    {title: 'Solid', value: 'pf-report-table-solid'}
                ], 
                object_resizing: 'img',
                paste_word_valid_elements: 'b,p,br,strong,i,em,h1,h2,h3,h4,ul,li,ol,table,span,div,font,page',
                codemirror: {
                    indentOnInit: true, 
                    fullscreen: false,   
                    path: 'codemirror', 
                    config: {           
                        mode: 'text/html',
                        styleActiveLine: true,
                        lineNumbers: true, 
                        lineWrapping: true,
                        matchBrackets: true,
                        autoCloseBrackets: true,
                        indentUnit: 2, 
                        foldGutter: true,
                        gutters: ["CodeMirror-linenumbers", "CodeMirror-foldgutter"], 
                        extraKeys: {
                            "F11": function(cm) {
                                cm.setOption("fullScreen", !cm.getOption("fullScreen"));
                            },
                            "Esc": function(cm) {
                                if (cm.getOption("fullScreen")) cm.setOption("fullScreen", false);
                            }, 
                            "Ctrl-Q": function(cm) { 
                                cm.foldCode(cm.getCursor()); 
                            }, 
                            "Ctrl-S": function(cm) { 
                                if ($('body').find('.mce-bp-btn-subsave').length > 0 && $('body').find('.mce-bp-btn-subsave').is(':visible')) {
                                    var $buttonElement = $('body').find('.mce-bp-btn-subsave:visible:last');
                                    if (!$buttonElement.is(':disabled')) {
                                        $buttonElement.click();
                                    }
                                }
                            }, 
                            "Ctrl-Space": "autocomplete"
                        }
                    },
                    width: ($(window).width() - 20),        
                    height: ($(window).height() - 120),        
                    saveCursorPosition: false,    
                    jsFiles: [          
                        'mode/clike/clike.js',
                        'mode/htmlmixed/htmlmixed.js', 
                        'mode/css/css.js', 
                        'mode/xml/xml.js', 
                        'addon/fold/foldcode.js', 
                        'addon/fold/foldgutter.js', 
                        'addon/fold/brace-fold.js', 
                        'addon/fold/xml-fold.js', 
                        'addon/fold/indent-fold.js', 
                        'addon/fold/comment-fold.js', 
                        'addon/hint/show-hint.js', 
                        'addon/hint/xml-hint.js', 
                        'addon/hint/html-hint.js', 
                        'addon/hint/css-hint.js'
                    ]
                },
                setup: function(editor) {              
                    editor.on('init', function() {
                        
                        var textEdata = $textarea.val();
                        if (!textEdata.startsWith('<div class="append-textstyle') && textEditorDefaultStyleString) {
                            editor.setContent('<div class="append-textstyle" style="'+textEditorDefaultStyleString+'">'+textEdata+'</div>');
                        } else {
                            editor.setContent(textEdata);
                        }
                        
                        $('textarea#text_editorInit').prev('.mce-container').find('.mce-edit-area')
                        .droppable({
                            drop: function(event, ui) {
                                tinymce.activeEditor.execCommand('mceInsertContent', false, '#'+ui.draggable.text()+'#');
                            }
                        });
                    });
                    editor.on('keydown', function(evt) {    
                        if (evt.keyCode == 9) {
                            editor.execCommand('mceInsertContent', false, '&emsp;&emsp;');
                            evt.preventDefault();
                            return false;
                        }
                    });
                    editor.shortcuts.add('ctrl+s', '', function() { 
                        if ($('body').find('.mce-bp-btn-subsave').length > 0 && $('body').find('.mce-bp-btn-subsave').is(':visible')) {
                            var $buttonElement = $('body').find('.mce-bp-btn-subsave:visible:last');
                            if (!$buttonElement.is(':disabled')) {
                                $buttonElement.click();
                                return false;
                            }
                        }

                        if ($('body').find('button.bp-btn-subsave').length > 0 && $('body').find('button.bp-btn-subsave').is(':visible')) {
                            var $buttonElement = $('body').find('button.bp-btn-subsave:visible:last');
                            if (!$buttonElement.is(':disabled')) {
                                $buttonElement.click();
                            }
                        }
                        return false;
                    });
                },  
                document_base_url: URL_APP, 
                content_css: URL_APP + 'assets/custom/css/print/tinymce.css'
            });
        },
        close: function () {
            tinymce.remove(tinymce.get('text_editorInit'));
            $dialog.empty().dialog('destroy').remove();
        },
        buttons: [
            {text: plang.get('save_btn'), class: 'btn green-meadow btn-sm bp-btn-subsave', click: function () {

                var tinymceEditorIns = tinymce.get('text_editorInit');
                var tinymceEditorInsData = tinymceEditorIns.getContent();
                
                $contenteditable.html(tinymceEditorInsData);
                $textarea.val(tinymceEditorInsData);
                
                $dialog.dialog('close');
            }},
            {text: plang.get('close_btn'), class: 'btn blue-madison btn-sm', click: function () {
                $dialog.dialog('close');
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
    $dialog.dialog('open');
    $dialog.dialogExtend('maximize');
}
function base64ToUint8Array(base64) {
    var raw = atob(base64);
    var uint8Array = new Uint8Array(raw.length);
    for (var i = 0; i < raw.length; i++) {
        uint8Array[i] = raw.charCodeAt(i);
    }
    return uint8Array;
}
function bpViewBase64Field(elem) {
    var $this = $(elem), $textarea = $this.prev('textarea');
    var base64Str = $textarea.val();
    
    if (base64Str != '') {
        
        var firstThreeChar = base64Str.substr(0, 3);
        
        if (['jpg', 'jpeg', 'png', 'gif', 'bmp'].indexOf(base64Str) !== -1) {
            
            $.fancybox.open(
                [{src: base64Str, opts: {caption: 'Base64 image'}}],
                {buttons: ['zoom', 'close']}
            );
    
        } else if (firstThreeChar == '/9j') {
            
            $.fancybox.open(
                [{src: 'data:image/jpg;base64,' + base64Str, opts: {caption: 'Base64 image'}}],
                {buttons: ['zoom', 'close']}
            );
    
        } else if (firstThreeChar == 'iVB') {
            
            $.fancybox.open(
                [{src: 'data:image/png;base64,' + base64Str, opts: {caption: 'Base64 image'}}],
                {buttons: ['zoom', 'close']}
            );
    
        } else if (firstThreeChar == 'Qk0') {
            
            $.fancybox.open(
                [{src: 'data:image/bmp;base64,' + base64Str, opts: {caption: 'Base64 image'}}],
                {buttons: ['zoom', 'close']}
            );
    
        } else if (firstThreeChar == 'R0l') {
            
            $.fancybox.open(
                [{src: 'data:image/gif;base64,' + base64Str, opts: {caption: 'Base64 image'}}],
                {buttons: ['zoom', 'close']}
            );
    
        } else if (base64Str.indexOf('JVBERi0') !== -1) {
            
            var $dialogName = 'dialog-base64-pdf';
            if (!$("#" + $dialogName).length) {
                $('<div id="' + $dialogName + '"></div>').appendTo('body');
            }
            var $dialog = $('#' + $dialogName), html = [];

            html.push('<div class="row"><iframe id="base64PdfFrame" style="width:100%;height:'+($(window).height() - 110)+'px; border: none" border="0"></iframe></div>');

            $dialog.empty().append(html.join(''));  
            $dialog.dialog({
                resizable: true,
                bgiframe: true,
                autoOpen: false,
                title: 'PDF Viewer',
                width: '100%',
                height: 'auto',
                modal: true,
                closeOnEscape: isCloseOnEscape,
                open: function() {
                    var pdfData = base64ToUint8Array(base64Str);
                    
                    var pdfViewerFrame = document.getElementById('base64PdfFrame');
                    pdfViewerFrame.onload = function() {
                        pdfViewerFrame.contentWindow.PDFViewerApplication.open(pdfData);
                    };
                    pdfViewerFrame.setAttribute('src', URL_APP + 'api/pdf/web/viewer.html?file=');     
                },
                close: function() {
                    $dialog.empty().dialog('destroy').remove();
                }, 
                buttons: [
                    {text: plang.get('close_btn'), class: 'btn blue-madison btn-sm', click: function () {
                        $dialog.dialog('close');
                    }}
                ]
            });
            $dialog.dialog('open');
            
        } else {
            base64Str = base64Str.replace(/[\r\n]/gm, '');
            window.location.href = 'data:application/octet-stream;base64,' + base64Str;
        }
    }
    
    return;
}

function stampedFileByProcess(pdfPath, position, getImageProcessCode, inputParam, stampProcessCode, fileName) {
    var response = $.ajax({
        type: 'post',
        url: 'mddoc/stampedFileByProcess', 
        dataType: 'json',
        data: {
            pdfPath: pdfPath, 
            position: position, 
            getProcessCode: getImageProcessCode,
            inputParam: inputParam,
            stampProcessCode: stampProcessCode,
            fileName: fileName,
        },
        async: false
    });
    return response.responseJSON;
}

function bpFileToPath(files) {
    
    var formData = new FormData();
    
    $.each(files, function (i, r) {
        formData.append('files[]', r);
    });
    var response = $.ajax({
        type: 'post',
        url: 'mddoc/bpFileToPath', 
        dataType: 'json',
        data: formData,
        contentType: false,
        processData: false,
        async: false
    });
    
    return response.responseJSON;
}

function bpFileCheckSize(files) {
    
    var formData = new FormData();
    
    $.each(files, function (i, r) {
        formData.append('files[]', r);
    });
        
    var response = $.ajax({
        type: 'post',
        url: 'mddoc/bpFileCheckSize', 
        dataType: 'json',
        data: formData,
        contentType: false,
        processData: false,
        async: false
    });

    return response.responseJSON;
}

function bpOnlyShowInputFieldCount($form) {
    
    setTimeout(function() {
        var $showInputs = $form.find('[data-path!="."]');
    }, 100);

    return;
}

function initPortLocationConfiguration(elem, id, linkRecordId) {
    var $this = $(elem);
    
    Core.blockUI({message: 'Loading...', boxed: true});
    var $dialogName = 'dialog-bpvPortEditor';
    if (!$("#" + $dialogName).length) {
        $('<div id="' + $dialogName + '"></div>').appendTo('body');
    }
    var $dialog = $('#' + $dialogName);


    $.ajax({
        type: 'post',
        url: 'mdform/kpiPortLocationVisual',
        data: {
            id: $('input[name="param[id]"]').val(),
            linkId: id,
            linkRecordId: linkRecordId
        },
        beforeSend: function() {
        },
        success: function(data) {
            $dialog.empty().append(data);
            $dialog.find('.flowchart-savebtn-row').hide();
            $dialog.dialog({
                cache: false,
                resizable: true,
                bgiframe: true,
                autoOpen: false,
                title: 'Port location configuration',
                width: 1000,
                minWidth: 1000,
                height: "auto",
                modal: true,
                close: function () {
                    $dialog.empty().dialog('destroy').remove();
                },
                open: function () {
                    $dialog.closest(".ui-dialog").css("z-index", 100);
                    $('.ui-widget-overlay').css("z-index", 99);
                },
                buttons: [
                    {text: plang.get('save_btn'), class: 'btn btn-sm green bp-btn-subsave', click: function() {         
                        if (id) {
                            saveDeviceConnecetion(linkRecordId, function(data){
                                if (data.status == 'success') {

                                    new PNotify({
                                        title: data.status,
                                        text: data.message,
                                        type: data.status,
                                        addclass: pnotifyPosition,
                                        sticker: false
                                    });
                                    $dialog.dialog('close');

                                } else {

                                    PNotify.removeAll();
                                    new PNotify({
                                        title: data.status,
                                        text: data.message,
                                        type: data.status,
                                        addclass: pnotifyPosition,
                                        sticker: false
                                    });                                            
                                }
                            });
                        } else {
                            saveVport(function(data){
                                $.ajax({
                                    type: 'post',
                                    url: 'mdform/updateGraphJsonKpiIndicator',
                                    data: {
                                        id: $this.closest('form').find('input[name="param[id]"]').val(), 
                                        json: JSON.stringify(data)
                                    }, 
                                    dataType: 'json', 
                                    beforeSend: function(){
                                        Core.blockUI({message: 'Loading...', boxed: true});
                                    },
                                    success: function (data) {

                                        if (data.status == 'success') {

                                            new PNotify({
                                                title: data.status,
                                                text: data.message,
                                                type: data.status,
                                                addclass: pnotifyPosition,
                                                sticker: false
                                            });
                                            $dialog.dialog('close');

                                        } else {

                                            PNotify.removeAll();
                                            new PNotify({
                                                title: data.status,
                                                text: data.message,
                                                type: data.status,
                                                addclass: pnotifyPosition,
                                                sticker: false
                                            });                                            
                                        }
                                        Core.unblockUI();
                                    }
                                });
                            });                  
                        }
                    }},
                    {text: plang.get('close_btn'), class: 'btn btn-sm blue-hoki', click: function() {
                        $dialog.dialog('close');
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
                }, 
//                    "maximize" : function() { 
//                        var dialogHeight = $dialog.height();
//                        $dialog.find('.app-body').css('height', dialogHeight+'px');
//                        $dialog.find('.inspector-container').css('height', (dialogHeight-165)+'px');
//                        // $dialog.find('#expressionPathList-scroll').css('max-height', dialogHeight+'px');
//                    }, 
//                    "restore" : function() { 
//                        var dialogHeight = $dialog.height();
//                        $dialog.find('.app-body').css('height', dialogHeight+'px');
//                        $dialog.find('.inspector-container').css('height', (dialogHeight-165)+'px');
//                        // $dialog.find('#expressionPathList-scroll').css('max-height', dialogHeight+'px');
//                    }
            });
            $dialog.dialog('open');
            if (id) {
                $dialog.dialogExtend('maximize');
            }

            Core.unblockUI();
        }
    });
        
    return;
}

function bpPassportReader(elem, paramPath, uniqId) {
    if ("WebSocket" in window) {
        console.log("WebSocket is supported by your Browser!");
        var ws = new WebSocket("ws://localhost:58324/socket");

        ws.onopen = function () {
            var currentDateTime = GetCurrentDateTime();
            ws.send('{"command":"passport_reader", "dateTime":"' + currentDateTime + '", details: [{"key": "devicetype", "value": "combo"}]}');
        };

        ws.onmessage = function (evt) {
            var received_msg = evt.data;
            var jsonData = JSON.parse(received_msg);
            if (jsonData.status == 'success') {
                $(elem).closest('#bp-window-'+uniqId).find('input[data-path="'+ paramPath +'"]').val(jsonData.details[0].value).trigger('change');
                $(elem).closest('.passport_reader').find('img').remove(); 
                $(elem).closest('.passport_reader').append('<img class="pf-filefield-imgpreview mt5 w-100 cursor-pointer" src="data:image/jpeg;base64,' + jsonData.details[0].value + '" >');
                /* $.ajax({
                    type: 'post',
                    url: 'mddoc/tempFileSave',
                    data: {
                        finger: jsonData.details[0].value
                    },
                    beforeSend: function () {
                        Core.blockUI({ boxed: true, message: 'Хуруу хээг уншиж байна...' });
                    },
                    dataType: 'json',
                    success: function (data) {
                        $(elem).closest('#bp-window-'+uniqId).find('input[data-path="'+ paramPath +'"]').val(data.filePath);
                        Core.unblockUI();
                    },
                    error: function () {
                        Core.unblockUI();
                    }
                }); */
            } else {
                var resultJson = {
                    Status: 'Error',
                    Error: jsonData.message
                }

                new PNotify({
                    title: jsonData.status,
                    text: (jsonData.description !== 'undefined') ? jsonData.description : 'Амжилтгүй боллоо',
                    type: jsonData.status,
                    sticker: false
                });

            }
        };

        ws.onerror = function (event) {
            var resultJson = {
                Status: 'Error',
                Error: event.code
            }

            PNotify.removeAll();
            new PNotify({
                title: 'warning',
                text: plang.get('client_not_working'),
                type: 'warning',
                sticker: false
            });
            
        };

        ws.onclose = function () {
            console.log("Connection is closed...");
        };
    } else {
        var resultJson = {
            Status: 'Error',
            Error: "WebSocket NOT supported by your Browser!"
        }
        
        new PNotify({
            title: jsonData.status,
            text: (jsonData.description !== 'undefined') ? jsonData.description : 'Амжилтгүй боллоо',
            type: jsonData.status,
            sticker: false
        });
    }
}
function senderWebsocket(data) {
    try {
        rtc.apiSendAllUser(data);
    } catch (e) {
        console.log(e);
    }
}
function pinPasswordShow(elem) {
    var $this = $(elem), $parent = $this.closest('.input-group'), 
        $input = $parent.find('input');
        
    if ($this.hasClass('show-password')) {
        $input.attr('type', 'password');
        $this.find('i').removeClass('fa-eye-slash').addClass('fa-eye');
        $this.removeClass('show-password');
    } else {
        $input.attr('type', 'text');
        $this.find('i').removeClass('fa-eye').addClass('fa-eye-slash');
        $this.addClass('show-password');
    }
}
function bpRenderViewLog(mainSelector) {
    
    var bpId = mainSelector.attr('data-process-id'), uniqId = mainSelector.attr('data-bp-uniq-id');
    var recordId = mainSelector.find('[data-path="id"]').val();
    $.ajax({
        type: 'post',
        url: 'mdprocess/saveRenderViewLog',
        data: {bpId: bpId, uniqId: uniqId, recordId: recordId},
        dataType: 'json',
        success: function(data) {},
        error: function(e) { console.log(e); }
    });
}
function mssSignature(phoneNumber) {
    var response = $.ajax({
        type: 'post',
        url: 'mdintegration/mssSignature',
        data: {phoneNumber: phoneNumber},
        dataType: 'json',
        async: false
    });
    
    return response.responseJSON;
}
function redirectHelpContent(elem, contentId, sourceId, fromType) {
    $.ajax({
        type: 'post',
        url: 'mdhelpdesk/cloud_user_help',
        data: {contentId: contentId, sourceId: sourceId, fromType: fromType},
        dataType: 'json',
        success: function(data) {
            if (data.status == 'success') {
                window.open(data.url, '_blank');
            }
        },
        error: function(e) { console.log(e); }
    });
}
function setHelpContent(elem, contentId, sourceId, fromType) {
    $.ajax({
        type: 'post',
        url: 'mdhelpdesk/setCloudHelpForm',
        data: {contentId: contentId, sourceId: sourceId, fromType: fromType},
        success: function(data) {
            var $dialogName = '#dialog-sethelp-content';
            if (!$($dialogName).length) {
                $('<div id="' + $dialogName.replace('#', '') + '"></div>').appendTo('body');
            }
            var $dialog = $($dialogName);

            $dialog.empty().append(data);
            $dialog.dialog({
                cache: false,
                resizable: false,
                bgiframe: true,
                autoOpen: false,
                title: 'Гарын авлага тохируулах',
                width: 860,
                minWidth: 860,
                height: 'auto',
                modal: true,
                closeOnEscape: isCloseOnEscape,
                close: function() {
                    $dialog.empty().dialog('destroy').remove();
                },
                buttons: [{
                        text: plang.get('save_btn'),
                        class: 'btn btn-sm green-meadow',
                        click: function() {
                            
                            var $form = $dialog.find('form');
                            $form.validate({ errorPlacement: function() {} });

                            if ($form.valid()) {
                                $form.ajaxSubmit({
                                    type: 'post',
                                    url: 'mdhelpdesk/setCloudHelpSave',
                                    dataType: 'json',
                                    beforeSubmit: function(formData, jqForm, options) {
                                        formData.push({name: 'contentId', value: contentId});
                                        formData.push({name: 'sourceId', value: sourceId});
                                        formData.push({name: 'fromType', value: fromType});
                                    },
                                    beforeSend: function() {
                                        Core.blockUI({message: 'Loading...', boxed: true});
                                    },
                                    success: function(dataJson) {
                                        PNotify.removeAll();
                                        new PNotify({
                                            title: dataJson.status,
                                            text: dataJson.message,
                                            type: dataJson.status,
                                            sticker: false, 
                                            addclass: pnotifyPosition
                                        });

                                        if (dataJson.status === 'success') {
                                            $dialog.dialog('close');
                                            
                                            var $clickButton = $(elem);
                                            
                                            if (fromType == 'mv_list' || fromType == 'meta_dv') {
                                                if ($clickButton.next('[onclick*="redirectHelpContent("]').length) {
                                                    $clickButton.next('[onclick*="redirectHelpContent("]').remove();
                                                }
                                                if ($clickButton.hasClass('dropdown-item')) {
                                                    $clickButton.after('<a href="javascript:;" onclick="redirectHelpContent(this, \''+dataJson.setContentId+'\', \''+sourceId+'\', \''+fromType+'\');" title="'+plang.get('menu_system_guide')+'" class="dropdown-item"><i class="far fa-info"></i> '+plang.get('menu_system_guide')+'</a>');
                                                } else {
                                                    $clickButton.after('<a href="javascript:;" onclick="redirectHelpContent(this, \''+dataJson.setContentId+'\', \''+sourceId+'\', \''+fromType+'\');" title="'+plang.get('menu_system_guide')+'" class="btn btn-secondary btn-circle btn-sm default"><i class="far fa-info"></i></a>');
                                                }
                                            } else {
                                            
                                                if ($clickButton.closest('.ui-dialog-buttonset').length) {
                                                    if ($clickButton.next('.bp-btn-help').length) {
                                                        $clickButton.next('.bp-btn-help').remove();
                                                    }
                                                    $clickButton.after('<button type="button" onclick="redirectHelpContent(this, \''+dataJson.setContentId+'\', \''+sourceId+'\', \''+fromType+'\');" class="btn btn-info btn-sm float-left bp-btn-help">'+plang.get('menu_system_guide')+'</button>');
                                                } else {
                                                    if ($clickButton.next('.bp-btn-help').length) {
                                                        $clickButton.next('.bp-btn-help').remove();
                                                    }
                                                    $clickButton.after('<button type="button" onclick="redirectHelpContent(this, \''+dataJson.setContentId+'\', \''+sourceId+'\', \''+fromType+'\');" class="btn btn-sm btn-circle btn-success bp-btn-help bpMainSaveButton mr-1">'+plang.get('menu_system_guide')+'</button>');
                                                }
                                            }
                                        }
                                        Core.unblockUI();
                                    },
                                    error: function() { alert("Error"); Core.unblockUI(); }
                                });
                            }
                        }
                    },
                    {
                        text: plang.get('close_btn'),
                        class: 'btn btn-sm blue-hoki',
                        click: function() {
                            $dialog.dialog('close');
                        }
                    }
                ]
            });
            $dialog.dialog('open');
        },
        error: function(e) { console.log(e); }
    });
}