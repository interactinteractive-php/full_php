<script type="text/javascript">
    var criteria_search_<?php echo $this->metaDataId; ?> = $("div#dv-search-<?php echo $this->metaDataId; ?>");

    criteria_search_<?php echo $this->metaDataId; ?>.on('change', 'select.linked-combo', function(){
        
        var _this = $(this);
        var _outParam = _this.attr('data-out-param');
        var _outParamSplit = _outParam.split('|');
        
        for (var i = 0; i < _outParamSplit.length; i++) {
            
            var _inParams = '';
            var selfParam = _outParamSplit[i];
            var _cellSelect = criteria_search_<?php echo $this->metaDataId; ?>.find("select[data-path='" + selfParam + "'], input[data-path='" + selfParam + "']");
            var isCombo = _cellSelect.hasClass('select2') ? true : false;
            
            if (_cellSelect.length) {
                var _inParam = _cellSelect.attr('data-in-param');
                var _inParamSplit = _inParam.split('|');
                
                for (var j = 0; j < _inParamSplit.length; j++) {
                    var _lastCombo = criteria_search_<?php echo $this->metaDataId; ?>.find("[data-path='" + _inParamSplit[j] + "']");
                    if (_lastCombo.length) {
                        if (_lastCombo.prop('tagName') == 'SELECT' && _lastCombo.prop('multiple') && _lastCombo.val()) {
                            _inParams += _inParamSplit[j] + '=' + _lastCombo.val().toString() + '&';
                        } else if (_lastCombo.val() != '') {
                            _inParams += _inParamSplit[j] + '=' + _lastCombo.val() + '&';
                        }
                    }
                }
            }

            if (_inParams !== '') {
                $.ajax({
                    type: 'post',
                    url: 'mdobject/bpLinkedCombo',
                    data: {inputMetaDataId: '<?php echo $this->metaDataId; ?>', selfParam: selfParam, inputParams: _inParams},
                    dataType: 'json',
                    async: false,
                    beforeSend: function () {
                        Core.blockUI({animate: true});
                    },
                    success: function (dataStr) {
                        
                        var comboData = dataStr[selfParam];
                        
                        if (isCombo) {
                            
                            if (_cellSelect.hasClass('select2')) {
                                _cellSelect.select2('val', '').select2('destroy');
                                _cellSelect.prop('disabled', false);
                                $("option:gt(0)", _cellSelect).remove();
                            } else {
                                _cellSelect.val('');
                                _cellSelect.removeAttr('disabled');
                                $("option", _cellSelect).remove();
                            }

                            _cellSelect.addClass("data-combo-set");

                            $.each(comboData, function () {
                                _cellSelect.append($("<option />").val(this.META_VALUE_ID).text(this.META_VALUE_NAME));
                            });

                            Core.initSelect2(_cellSelect.parent());
                            
                        } else {
                            
                            if (_cellSelect.hasClass('iconInit')) {
                                
                                var $iconParent = _cellSelect.closest('ul.bp-icon-selection');
                                var iconList = '';
                                            
                                $.each(comboData, function () {
                                    
                                    var iconName = '<img src="assets/core/global/img/appmenu.png" onerror="onBankImgError(this);">';
                                    
                                    if ((this.ROW_DATA).hasOwnProperty('iconname') && this.ROW_DATA.iconname != '') {
                                        iconName = this.ROW_DATA.iconname;
                                    }
                                    
                                    iconList += '<li data-id="'+this.META_VALUE_ID+'" title="'+this.META_VALUE_NAME+'">';
                                        iconList += '<div class="item-icon-selection"><div>'+iconName+'</div>';
                                            iconList += '<p>'+this.META_VALUE_NAME+'</p>';
                                        iconList += '</div>';
                                    iconList += '</li>';
                                });
                                
                                $iconParent.find('> li[data-id], [see-more-status]').remove();
                                $iconParent.prepend(iconList);
                            }
                        }
                        
                        Core.unblockUI();
                    },
                    error: function () {
                        alert("Error");
                    }
                });
                
            } else {
                
                if (isCombo) {
                    _cellSelect.select2('val', '');
                    _cellSelect.select2('disable');
                    $("option:gt(0)", _cellSelect).remove();
                    Core.initSelect2(_cellSelect.parent());
                }
            }
        }
    });
    
    $('select.linked-combo', criteria_search_<?php echo $this->metaDataId; ?>).trigger('change');
    
    criteria_search_<?php echo $this->metaDataId; ?>.on('change', 'input.linked-combo', function(){
        var _this = $(this);
        var _outParam = _this.attr('data-out-param');
        var _outParamSplit = _outParam.split('|');

        for (var i = 0; i < _outParamSplit.length; i++) {
            var selfParam = _outParamSplit[i];
            var _cellSelect = criteria_search_<?php echo $this->metaDataId; ?>.find("select[data-path='" + selfParam + "'], input.iconInit[data-path='" + selfParam + "']");

            if (_cellSelect.length === 0) {
                var _cellInp = criteria_search_<?php echo $this->metaDataId; ?>.find("input[data-path='" + selfParam + "']");

                if (_this.val().length > 0 && _cellInp.length > 0) {
                    _cellInp.closest('.meta-autocomplete-wrap').find('input').removeAttr('readonly disabled');
                    _cellInp.parent().find('button').removeAttr('disabled');
                }

            } else {

                var _inParams = '';
                
                var _inParam = _cellSelect.attr("data-in-param");
                var _inParamSplit = _inParam.split("|");

                for (var j = 0; j < _inParamSplit.length; j++) {
                    var _lastCombo = criteria_search_<?php echo $this->metaDataId; ?>.find("[data-path='" + _inParamSplit[j] + "']");
                    if (_lastCombo.length) {
                        if (_lastCombo.prop('tagName') == 'SELECT' && _lastCombo.prop('multiple') && _lastCombo.val()) {
                            _inParams += _inParamSplit[j] + '=' + _lastCombo.val().toString() + '&';
                        } else if (_lastCombo.val() != '') {
                            _inParams += _inParamSplit[j] + '=' + _lastCombo.val() + '&';
                        }
                    }
                }

                if (_inParams !== '') {
                    $.ajax({
                        type: 'post',
                        url: 'mdobject/bpLinkedCombo',
                        data: {inputMetaDataId: '<?php echo $this->metaDataId; ?>', selfParam: selfParam, inputParams: _inParams},
                        dataType: "json",
                        async: false,
                        beforeSend: function () {
                            Core.blockUI({animate: true});
                        },
                        success: function (dataStr) {
                            if (_cellSelect.hasClass("select2")) {
                                _cellSelect.select2('val', '');
                                _cellSelect.select2('enable');
                            } else {
                                _cellSelect.val('');
                                _cellSelect.removeAttr('disabled');
                            }
                            
                            var comboData = dataStr[selfParam];
                            
                            if (_cellSelect.hasClass('iconInit')) {
                                
                                var $iconParent = _cellSelect.closest('ul.bp-icon-selection');
                                var iconList = '';
                                            
                                $.each(comboData, function () {
                                    var iconName = ((this.ROW_DATA).hasOwnProperty('iconname') ? this.ROW_DATA.iconname : '');
                                    
                                    iconList += '<li data-id="'+this.META_VALUE_ID+'" title="'+this.META_VALUE_NAME+'">';
                                        iconList += '<div class="item-icon-selection"><div>'+iconName+'</div>';
                                            iconList += '<p>'+this.META_VALUE_NAME+'</p>';
                                        iconList += '</div>';
                                    iconList += '</li>';
                                });
                                
                                $iconParent.find('> li[data-id], [see-more-status]').remove();
                                $iconParent.prepend(iconList);
                                
                            } else {
                                
                                 $("option:gt(0)", _cellSelect).remove();
                                _cellSelect.addClass("data-combo-set");

                                $.each(comboData, function () {
                                    _cellSelect.append($("<option />").val(this.META_VALUE_ID).text(this.META_VALUE_NAME));
                                });

                                Core.initSelect2(_cellSelect.parent());
                            }
                            
                            Core.unblockUI();
                        },
                        error: function () {
                            alert("Error");
                        }
                    });
                    
                } else {
                    _cellSelect.select2('val', '');
                    _cellSelect.select2('disable');
                    $("option:gt(0)", _cellSelect).remove();
                    Core.initSelect2(_cellSelect.parent());
                }
            }
        }
    });
    
    criteria_search_<?php echo $this->metaDataId; ?>.on('click', 'input.linked-combo-checkbox', function(){
        
        var _this = $(this);
        var _outParam = _this.attr('data-out-param');
        var _outParamSplit = _outParam.split('|');
        
        for (var i = 0; i < _outParamSplit.length; i++) {
            
            var _inParams = '';
            var selfParam = _outParamSplit[i];
            var _cellSelect = criteria_search_<?php echo $this->metaDataId; ?>.find("input[data-path='" + selfParam + "']");
            
            if (_cellSelect.length) {
                var _inParam = _cellSelect.attr('data-in-param');
                var _inParamSplit = _inParam.split('|');
                
                for (var j = 0; j < _inParamSplit.length; j++) {
                    var _lastCombo = criteria_search_<?php echo $this->metaDataId; ?>.find("[data-path='" + _inParamSplit[j] + "']"),
                        strVals = '';
                    if (_lastCombo.length) {
                        _lastCombo.each(function(){
                            if ($(this).is(':checked')) {
                                strVals += $(this).val() + ',';
                            }
                        });
                        _inParams += _inParamSplit[j] + '=' + strVals;
                    }
                }
            }

            if (_inParams !== '') {
                $.ajax({
                    type: 'post',
                    url: 'mdwebservice/renderCheckboxControl',
                    data: {
                        groupMetaDataId: '<?php echo $this->metaDataId; ?>', 
                        selfParam: selfParam, 
                        inputParams: _inParams,
                        params: JSON.parse(_cellSelect.closest('.radio-list-main').attr('data-param')),
                        paramName: _cellSelect.closest('.radio-list-main').attr('data-paramname'),
                        controlName: _cellSelect.closest('.radio-list-main').attr('data-controlname'),
                        linkedCombo: '1',
                        processMetaDataId: _cellSelect.closest('.radio-list-main').attr('data-processmetadataid')
                    },
                    dataType: 'json',
                    async: false,
                    beforeSend: function () {
                        Core.blockUI({
                            animate: true
                        });
                    },
                    success: function (dataStr) {
                        _cellSelect.closest('.col-md-12').empty().append(dataStr.Html);
                        Core.initInputType($('#object-value-list-<?php echo $this->metaDataId; ?>'));
                        Core.unblockUI();
                    },
                    error: function () {
                        alert("Error");
                    }
                });
            }
        }
    });

    $('.multiSelectOpen', criteria_search_<?php echo $this->metaDataId; ?>).on('mousedown', 'option', function(e) {
        var $self = $(this);
        var $this = $self.parent();
        e.preventDefault();

        var originalScrollTop = $self.parent().scrollTop();
        $self.prop('selected', $self.prop('selected') ? false : true);            
        $self.parent().focus();
        setTimeout(function() {
            $self.parent().scrollTop(originalScrollTop);
        }, 0);

        if ($this.parent().hasClass('input-group')) {
            if ($this.parent().parent().find('.multiSelectOpenContainer').length) {
                $this.parent().parent().find('.multiSelectOpenContainer').remove();
            }                
        } else {
            if ($this.parent().find('.multiSelectOpenContainer').length) {
                $this.parent().find('.multiSelectOpenContainer').remove();
            }
        }

        var divHtml = '<div class="multiSelectOpenContainer">';
        $this.find('option:selected').each(function () {
            divHtml += '<a href="javascript:;" class="mt1 mb2 ml2 badge badge-flat border-grey-800 text-default" onclick="multiSelectOpenRemove_<?php echo $this->metaDataId; ?>(this, \'' + $(this).val() + '\')">' + $(this).text() + '</a>';
        });
        divHtml += '</div>';

        if ($this.parent().hasClass('input-group')) {
            $this.parent().before(divHtml);            
        } else {
            $this.before(divHtml);
        }

        <?php if (isset($this->layoutLinkId)) { ?>
            $('#layout-id-<?php echo $this->layoutLinkId; ?> .layout-criteria-div').find('.dataview-default-filter-btn').trigger('click');
            $('#layout-id-<?php echo $this->layoutLinkId; ?> .layout-criteria-div').find('#default-mandatory-criteria-form').trigger('click');
        <?php } ?>
        $this.trigger('change');
        
        return false;            
    });      

    <?php 
    if (issetParam($this->row['IS_ENTER_FILTER']) == '1') { 
    ?>
    criteria_search_<?php echo $this->metaDataId; ?>.on('keydown', 'input[data-path]', function(e){
        var code = e.keyCode || e.which;

        if (code == '13') {
            criteria_search_<?php echo $this->metaDataId; ?>.find('button.dataview-default-filter-btn').trigger('click');
        }
    });

    criteria_search_<?php echo $this->metaDataId; ?>.on('change', 'select[data-path], input.popupInit', function(){
        criteria_search_<?php echo $this->metaDataId; ?>.find('button.dataview-default-filter-btn').trigger('click');
    });        
    <?php
    }
    ?>    

    function multiSelectOpenRemove_<?php echo $this->metaDataId; ?>(elem, id) {
        // var originalScrollTop = $(elem).parent().parent().find("option[value="+id+"]").scrollTop();
        // $(elem).parent().parent().find("option[value="+id+"]").focus();
        // setTimeout(function() {
        //     $(elem).parent().parent().find("option[value="+id+"]").scrollTop(originalScrollTop);
        // }, 0);

        $(elem).parent().parent().find("option[value="+id+"]").prop("selected", false);
        <?php if (isset($this->layoutLinkId)) { ?>
            $('#layout-id-<?php echo $this->layoutLinkId; ?> .layout-criteria-div').find('.dataview-default-filter-btn').trigger('click');
        <?php } ?>
        $(elem).parent().parent().find("select").trigger('change');
        $(elem).remove();
    }    

    function dvecommerceAdvancedCriteria<?php echo $this->metaDataId; ?>(elem, type, callback, $params) {
        var $thisP = $('#dvecommerce-advanced-criteria-<?php echo $this->metaDataId; ?>'),
            $dialogname = 'dialog-dvecommerce-criteria-<?php echo $this->metaDataId; ?>';
    
        if (!$('#' + $dialogname).length) {
            $.ajax({
                type: 'post',
                dataType: 'json',
                url: 'mdobject/advancedCriteriaForm',
                data: {criteria: '<?php echo isset($this->advancedCriteria) ? $this->advancedCriteria : '' ?>', metaDataId: '<?php echo $this->metaDataId ?>'},
                beforeSend: function () {
                    Core.blockUI({
                        message: 'Loading...',
                        boxed: true
                    });
                },
                success: function (data) {
                    $('<div class="row dvecommerce has-padding m-0 sidebar" id="' + $dialogname + '"></div>').appendTo('body');
                    var dialogname = $('#' + $dialogname);
                    dialogname.empty().append(data.Html).promise().done(function () {
                        Core.initAjax(dialogname);
                        dialogname.dialog({
                            cache: false,
                            resizable: false,
                            bgiframe: true,
                            autoOpen: false,
                            title: 'ДЭЛГЭРЭНГҮЙ ХАЙЛТ',
                            height: 'auto',
                            minWidth: 500,
                            modal: true,
                            open: function () {
                                $('div[aria-describedby=' + $dialogname + '] .ui-dialog-title').css("text-align", "left");
                                $('div[aria-describedby=' + $dialogname + '] .ui-dialog-buttonpane').attr("style", "background: none !important");
                                $('div[aria-describedby=' + $dialogname + '] .ui-dialog-buttonset').addClass("btn-group float-right");
                                $('div[aria-describedby=' + $dialogname + '] .ui-dialog-buttonset').find('button:eq(0)').addClass('btn btn-sm blue mr0');
                                $('div[aria-describedby=' + $dialogname + '] .ui-dialog-buttonset').find('button:eq(1)').addClass('btn blue-hoki btn-sm ml5');
                                $('div[aria-describedby=' + $dialogname + '] .ui-dialog-buttonset').find('button:eq(2)').addClass('btn blue-hoki btn-sm ml5');
                            },
                            close: function (elem) {
                                dialogname.dialog('close');
                            },
                            buttons: [
                                {text: plang.get('do_filter'), class: 'searchDv<?php echo $this->metaDataId ?>', click: function () {
                                    if (dialogname.find('input[name="isSaveCriteriaTemplate"]').is(':checked')) {
                                        var $dialogName = 'dialog-popup-<?php echo $this->metaDataId ?>';
                                        var $dialogConfirm = 'dialog-confirm-<?php echo $this->metaDataId ?>';
                                        if (!$("#" + $dialogConfirm).length) {
                                            $('<div id="' + $dialogConfirm + '"></div>').appendTo('body');
                                        }
                                        var $dialogC = $("#" + $dialogConfirm);
                                        
                                        $dialogC.empty().append('<input type="text" name="templateName" class="form-control form-control-sm stringInit" />');
                                        $dialogC.dialog({
                                            cache: false,
                                            resizable: false,
                                            bgiframe: true,
                                            autoOpen: false,
                                            title: 'Загварын нэр',
                                            width: 300,
                                            height: "auto",
                                            modal: true,
                                            close: function () {
                                                $dialogC.empty().dialog('close');
                                            },
                                            buttons: [
                                                {text: plang.get('yes_btn'), class: 'btn green-meadow btn-sm', click: function () {
                                                    var templateName = $dialogC.find('input[name="templateName"]').val();
                                                    if (!templateName) {
                                                        alert('Загварын нэрийг оруулна уу?');
                                                        return;
                                                    }
                                                    
                                                    dialogname.find('input[name="criteriaTemplateName"]').val(templateName);
                                                    $dialogC.dialog('close');
                                                    criteria_search_<?php echo $this->metaDataId; ?>.find('.dataview-default-filter-btn').click();
                                                    dialogname.dialog('close');
                                                }},
                                                {text: plang.get('no_btn'), class: 'btn blue-madison btn-sm', click: function () {
                                                    $dialogC.dialog('close');
                                                    
                                                    dialogname.find('.switchery').trigger('click');
                                                    criteria_search_<?php echo $this->metaDataId; ?>.find('.dataview-default-filter-btn').click();
                                                    dialogname.dialog('close');
                                                }}
                                            ]
                                        });
                                        $dialogC.dialog('open');
                                    } else {
                                    
                                        if (dialogname.find('select[name="criteriaTemplates"]').val()) { 
                                            dialogname.find('.switchery').trigger('click');
                                        }
                                        
                                        criteria_search_<?php echo $this->metaDataId; ?>.find('.dataview-default-filter-btn').click();
                                        criteria_search_<?php echo $this->metaDataId; ?>.find('#default-mandatory-criteria-form').click();
                                        dialogname.dialog('close');
                                    }

                                }},
                                {text: plang.get('clear_btn'), click: function () {
                                    dialogname.find("input[type=text], input[type=hidden], select:not(.right-radius-zero)").not("input[name='inputMetaDataId']").val('');
                                    dialogname.find("input[type=radio]").removeAttr('checked');
                                    dialogname.find("input[type=radio]").closest('span.checked').removeClass('checked');
                                    dialogname.find("input[type=checkbox]").removeAttr('checked');
                                    dialogname.find("input[type=checkbox]").closest('span.checked').removeClass('checked');
                                    dialogname.find("select.select2").select2("val", '');
                                    dialogname.find('.bp-icon-selection > li.active').removeClass('active');

                                    criteria_search_<?php echo $this->metaDataId; ?>.find('.dataview-default-filter-reset-btn').click();
                                }},
                                {text: plang.get('close_btn'), click: function () {
                                    dialogname.dialog('close');
                                }}
                            ]
                        }).dialogExtend({
                            'closable': true,
                            'maximizable': false,
                            'minimizable': false,
                            'collapsable': false,
                            'dblclick': 'maximize',
                            'minimizeLocation': 'left',
                            'icons': {
                                'close': 'ui-icon-circle-close',
                                'maximize': 'ui-icon-extlink',
                                'minimize': 'ui-icon-minus',
                                'collapse': 'ui-icon-triangle-1-s',
                                'restore': 'ui-icon-newwin'
                            }
                        });
                        
                        if (typeof type === 'undefined') {
                            dialogname.dialog('open');
                        } else {
                            searchfilter<?php echo $this->metaDataId ?>($params);
                        }
                    });
                }
            }).done(function () {
                Core.unblockUI();
            });
        } else {
            var dialogname = $('#' + $dialogname);
            
            if (dialogname.find('input[name="isSaveCriteriaTemplate"]').is(':checked')) { 
                dialogname.find('.switchery').trigger('click');
                dialogname.find('input[name="criteriaTemplateName"]').val('');
            }
            
            if (typeof type === 'undefined') {
                dialogname.dialog('open');
            }
        }
    }    

    function searchfilter<?php echo $this->metaDataId ?> ($params) {
        var $lastCriteriaRow = $('.adv-criteria-<?php echo $this->metaDataId ?>');
        
        $.ajax({
            type: 'post',
            url: 'mdobject/getDataviewCriteriaTemplate',
            data: $params,
            dataType: 'html',
            beforeSend: function() {
                Core.blockUI({
                    message: 'Loading...',
                    boxed: true
                });
            },
            success: function(data) {    
                if (data !== '') {                        
                    $lastCriteriaRow.empty().append(data).promise().done(function () {
                        Core.initDVAjax($lastCriteriaRow);
                        criteria_search_<?php echo $this->metaDataId; ?>.find('.dataview-default-filter-btn').click();
                    });                        
                }
                Core.unblockUI();
            },
            error: function() {
                alert('Error');
            }   
        });
    }    
</script>