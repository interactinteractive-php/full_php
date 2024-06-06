<script type="text/javascript">
    
    <?php
    if (isset($isWizard) && $isWizard) {
    ?>
    initKpiWizardForm_<?php echo $this->methodId; ?>();
    <?php
    }
    ?>
            
    var bp_window_<?php echo $this->methodId; ?> = $("div[data-bp-uniq-id='<?php echo $this->uniqId; ?>']");
    var isEditMode_<?php echo $this->methodId; ?> = <?php echo (($this->isEditMode) ? 'true' : 'false'); ?>;
    var checkFullExp_<?php echo $this->methodId; ?> = <?php echo empty($this->bpFullScriptsEvent) ? 'false' : 'true'; ?>;
    var checkFullExpWithoutEvent_<?php echo $this->methodId; ?> = <?php echo empty($this->bpFullScriptsWithoutEvent) ? 'false' : 'true'; ?>;
    var addonJsonParam_<?php echo $this->methodId; ?> = JSON.parse('<?php echo $this->addonJsonParam; ?>');
    var $aggregate_<?php echo $this->methodId; ?> = bp_window_<?php echo $this->methodId; ?>.find('.bprocess-table-dtl:not(.bprocess-table-subdtl, [data-pager="true"]) > thead > tr > th[data-aggregate]:not([data-aggregate=""])');
    var tabMoveMode_<?php echo $this->methodId; ?> = 'right';
    var $counter_remove_<?php echo $this->methodId; ?> = 1;
    var isTouch = (typeof isTouchEnabled === 'undefined') ? false : isTouchEnabled;
    pfFullExpSetFieldValue = true;
    var textEditorDefaultStyle = "<?php echo html_entity_decode(Config::getFromCacheDefault('textEditorDefaultStyle', null, ''), ENT_QUOTES, 'UTF-8'); ?>";
    
    <?php 
    if (isset($this->cacheId)) {
        echo 'bpDetailPager(bp_window_'.$this->methodId.', \''.$this->cacheId.'\');';
    }
    ?>
        
    Core.initBPInputType(bp_window_<?php echo $this->methodId; ?>);
    enableBpDetailFilter(bp_window_<?php echo $this->methodId; ?>);
    
    $(function () {
        
        if (!isTouch) {
            setTimeout(function () {
                bp_window_<?php echo $this->methodId; ?>.find('input:not([data-isdisabled], [readonly="readonly"], [readonly], readonly, [disabled="disabled"], [disabled], disabled, input.meta-name-autocomplete):visible:first').focus().select();
            }, 100);
        }
        
        if (bp_window_<?php echo $this->methodId; ?>.length > 0 && bp_window_<?php echo $this->methodId; ?>.find('input[data-path="isUsedGl"]').is(':visible')) {
            var $getFields = bp_window_<?php echo $this->methodId; ?>.find('[data-field-name]:not([data-isdisabled], [readonly="readonly"], [readonly], readonly, [disabled="disabled"], [disabled], disabled):visible');
            var getFieldsLen = $getFields.length, tabIndexCounter = 1;

            for (var ii = 0; ii < getFieldsLen; ii++) {
                if ($($getFields[ii]).attr('data-field-name').toLowerCase() !== 'isusedgl') {
                    $($getFields[ii]).attr('tabindex', tabIndexCounter);
                    tabIndexCounter++;
                }
            }
            bp_window_<?php echo $this->methodId; ?>.find('input[data-path="isUsedGl"]').attr('tabIndex', $getFields.length);
        }

        bp_window_<?php echo $this->methodId; ?>.on('keyup paste cut', 'input.bigdecimalInit', function(e){
            var code = e.keyCode || e.which;
            if (code == 9 || code == 13 || code == 27 || code == 37 || code == 38 || code == 39 || code == 40) return false;
            var $this = $(this);
            $this.next('input[type=hidden]').val($this.val().replace(/[,]/g, ''));
        });
        bp_window_<?php echo $this->methodId; ?>.on('keydown', 'input.bigdecimalInit', function(e){
            var code = e.keyCode || e.which;
            if (code == 9 || code == 13 || code == 38 || code == 40) {
                var $this = $(this), $thisNext = $this.next('input[type=hidden]'), $thisVal = $this.val();
                if ($thisVal !== $thisNext.val()) {
                    $thisNext.val($thisVal.replace(/[,]/g, ''));
                }
            }
        });
        bp_window_<?php echo $this->methodId; ?>.on('click', '.show-bpdtl-comment-btn', function() {
            var $this = $(this); $parent = $this.closest('tr');
            var sourceId = $parent.find('> td > input[data-field-name="id"]').val();        
            var refId = $this.data('refstructureid');

            $.ajax({
                type: 'post',
                url: 'mdwebservice/renderEditModeBpCommentTab',
                data: {uniqId: getUniqueId(''), refStructureId: refId, sourceId: sourceId},
                beforeSend: function() {
                    Core.blockUI({
                      message: "Loading...",
                      boxed: true
                    });
                },
                success: function(data) {
                    Core.unblockUI();

                    var $dialogName2 = "dialog-bpdtl-comment";
                    if (!$($dialogName2).length) {
                      $('<div id="' + $dialogName2 + '"></div>').appendTo("body");
                    }
                    var $dialog2 = $("#" + $dialogName2);

                    $dialog2.empty().append(data);

                    $dialog2.dialog({
                      cache: false,
                      resizable: false,
                      bgiframe: true,
                      autoOpen: false,
                      title: 'Сэтгэгдэл',
                      width: 500,
                      height: "auto",
                      modal: true,
                      position: { my: "top", at: "top+100" },
                      open: function () {
                      },
                      close: function () {
                        $dialog2.empty().dialog("destroy").remove();
                      },
                      buttons: [{
                          text: plang.get("close_btn"),
                          class: "btn btn-sm blue-hoki",
                          click: function () {
                            $dialog2.dialog("close");
                          },
                        }]
                    });

                    $dialog2.dialog("open");
                },
                error: function() {
                    alert('Error');
                }
            });            
        });        
        bp_window_<?php echo $this->methodId; ?>.on('change', "select.linked-combo:not(.kpi-ind-combo), select[data-criteria-param]", function(e, isTrigger){
            var $this = $(this), attrToJson = '';
            
            if ((isTrigger === true && isTrigger !== 'linked-combo') || ($this.hasClass("linked-combo-worked") && isTrigger === "EDIT")) {
                return;
            }
            if (typeof $this.attr('data-criteria-param') !== 'undefined' && $this.attr('data-criteria-param') !== '' && $this.val() != '' && typeof e.val !== 'undefined') {
                return;
            }

            if (isTrigger === "EDIT") {
                $this.addClass("linked-combo-worked");
            }
            
            var _outParam = $this.attr("data-out-param");     
            
            if ($this.closest(".bprocess-table-dtl").hasClass("bprocess-table-dtl")) {
                
                var $thisRow = $this.closest('.bp-detail-row');
                
                if (typeof _outParam !== 'undefined') {
                    var _outParamSplit = _outParam.split('|');
                } else {
                    var _outParamSplit = [$this.attr('data-path')];
                    attrToJson = JSON.parse($this.attr("data-row-data"));
                }
                
                for (var i = 0; i < _outParamSplit.length; i++) {
                    var selfParam = _outParamSplit[i];
                    
                    $thisRow.find("[data-cell-path]").each(function () {
                        
                        var $thisCell = $(this);
                        var $cellSelect = $thisCell.find("select[data-path='" + selfParam + "']");
                        
                        if ($cellSelect.length) {
                            
                            var _inParams = '';
                            
                            if (typeof $cellSelect.attr("data-in-param") !== 'undefined' && $cellSelect.attr("data-in-param") !== '') {
                                var _inParam = $cellSelect.attr("data-in-param");
                                var $thisChildRow = $thisCell.closest(".bp-detail-row");
                                var _inParamSplit = _inParam.split("|");

                                for (var j = 0; j < _inParamSplit.length; j++) {
                                    $thisChildRow.find("[data-cell-path]").each(function () {
                                        var $thisChildCell = $(this);
                                        var _lastCombo = $thisChildCell.find("[data-path='" + _inParamSplit[j] + "']");
                                        if (_lastCombo.length && _lastCombo.val() !== '') {
                                            _inParams += _inParamSplit[j] + '=' + encodeURIComponent(_lastCombo.val()) + '&';
                                        }
                                    });
                                }
                            }
                            
                            if (typeof $cellSelect.attr("data-criteria-param") !== 'undefined' && $cellSelect.attr("data-criteria-param") !== '') {
                                var _inParam = $cellSelect.attr("data-criteria-param");
                                var $thisChildRow = $thisCell.closest(".bp-detail-row");
                                var _inParamSplit = _inParam.split("|");

                                for (var j = 0; j < _inParamSplit.length; j++) {
                                    var fieldPathArr = _inParamSplit[j].split("@");
                                    var fieldPath = fieldPathArr[0];
                                    var inputPath = fieldPathArr[1];
                                
                                    $thisChildRow.find("[data-cell-path]").each(function () {
                                        var $thisChildCell = $(this);
                                        var _lastCombo = $thisChildCell.find("[data-path='" + fieldPath + "']");
                                        if (_lastCombo.length && _lastCombo.val() !== '') {
                                            _inParams += inputPath + '=' + encodeURIComponent(_lastCombo.val()) + '&';
                                        }
                                    });
                                }
                            }
 
                            if (_inParams !== '') {
                                $.ajax({
                                    type: 'post',
                                    url: 'mdobject/bpLinkedCombo',
                                    data: {
                                        processMetaDataId: '<?php echo $this->methodId; ?>', 
                                        selfParam: selfParam, 
                                        inputParams: _inParams, 
                                        jsonAttr: attrToJson,
                                        requestType: 'linkedCombo'
                                    },
                                    dataType: 'json',
                                    async: false,
                                    beforeSend: function () {
                                        Core.blockUI({animate: true});
                                    },
                                    success: function (dataStr) {
                                        
                                        var isWithPopupCombo = false;
                                        
                                        if ($cellSelect.hasClass('select2')) {
                                            $cellSelect.select2('val', '');
                                            $cellSelect.select2('readonly', false).select2('enable');
                                            
                                            if ($cellSelect.hasClass('bp-field-with-popup-combo')) {
                                                var $withPopupComboParent = $cellSelect.closest('.input-group');
                                                $withPopupComboParent.find('button').removeAttr('disabled');
                                                isWithPopupCombo = true;
                                            }
                                        } else {
                                            $cellSelect.val('');
                                            $cellSelect.removeAttr('disabled readonly');
                                            $cellSelect.parent().find('input, button').removeAttr('disabled readonly');
                                        }
                                        
                                        if ($cellSelect.is('[multiple]')) {
                                            $cellSelect.find('option').remove();
                                        } else {
                                            $cellSelect.find('option:gt(0)').remove();
                                        }
                                        
                                        var comboData = (selfParam in dataStr) ? dataStr[selfParam] : dataStr;
                                        $cellSelect.addClass("data-combo-set");
                                        
                                        $.each(comboData, function () {
                                            if (isEditMode_<?php echo $this->methodId; ?>) {
                                                if ($cellSelect.attr("data-edit-value") == this.META_VALUE_ID) {
                                                    $cellSelect.append($("<option />").val(this.META_VALUE_ID).text(this.META_VALUE_NAME).attr("selected", "selected").attr("data-row-data", JSON.stringify(this.ROW_DATA)));
                                                } else {
                                                    $cellSelect.append($("<option />").val(this.META_VALUE_ID).text(this.META_VALUE_NAME).attr("data-row-data", JSON.stringify(this.ROW_DATA)));
                                                }
                                            } else
                                                $cellSelect.append($("<option />").val(this.META_VALUE_ID).text(this.META_VALUE_NAME).attr("data-row-data", JSON.stringify(this.ROW_DATA)));
                                        });
                                        
                                        if (typeof $this.attr('data-criteria-param') === 'undefined') {
                                            $cellSelect.trigger('change');
                                        }
                                        
                                        if (isWithPopupCombo) {
                                            var count = $cellSelect.find('option:selected').length;
                                            if (count > 0) {
                                                $withPopupComboParent.find('button:eq(0)').text(count);
                                                $withPopupComboParent.find('button:eq(1)').show();
                                            } else {
                                                $withPopupComboParent.find('button:eq(0)').text('..');
                                                $withPopupComboParent.find('button:eq(1)').hide();
                                            }
                                        }
                                        
                                        Core.initSelect2($cellSelect);
                                        Core.unblockUI();
                                    },
                                    error: function () {
                                        alert("Error");
                                    }
                                });
                            } else {
                                $cellSelect.select2('val', '');
                                $cellSelect.select2('disable');
                                
                                if ($cellSelect.is('[multiple]')) {
                                    $cellSelect.find('option').remove();
                                } else {
                                    $cellSelect.find('option:gt(0)').remove();
                                }
                                        
                                Core.initSelect2($cellSelect);
                                
                                if ($cellSelect.hasClass('bp-field-with-popup-combo')) {
                                    var $parent = $cellSelect.closest('.input-group');
                                    $parent.find('button:not(.removebtn)').attr('disabled', 'disabled').text('..');
                                    $parent.find('button.removebtn').hide();
                                }
                            }
                        }
                    });
                }
            } else {
            
                if ($this.hasAttr('data-criteria-param')) {
                    return;
                }
                
                var _outParamSplit = _outParam.split('|');
                
                try {
                    for (var i = 0; i < _outParamSplit.length; i++) {
                    
                        var selfParam = _outParamSplit[i];
                        var _inParams = '';
                        var $cellSelect = bp_window_<?php echo $this->methodId; ?>.find("select[data-path='" + selfParam + "'], input[type='radio'][data-path='" + selfParam + "']");
                        
                        if ($cellSelect.length === 0) {
                            var $cellInp = bp_window_<?php echo $this->methodId; ?>.find("input[data-path='" + selfParam + "']");

                            if ($this.val().length > 0 && $cellInp.length > 0) {
                                if ($cellInp.closest(".bprocess-table-dtl").hasClass("bprocess-table-dtl") && $cellInp.attr("data-edit-value") === undefined) {
                                    if (isTrigger === undefined) {
                                        $cellInp = $cellInp.closest(".bprocess-table-dtl").find(".bp-detail-row").find("input[data-path='" + selfParam + "']");
                                    } else {
                                        $cellInp = $cellInp.closest(".bprocess-table-dtl").find(".bp-detail-row:last-child").find("input[data-path='" + selfParam + "']");
                                    }
                                }
                                $cellInp.closest(".meta-autocomplete-wrap").find("input").removeAttr("readonly disabled");
                                $cellInp.parent().find("button").removeAttr("disabled");
                            }

                        } else {                    
                            
                            var isLinkedRadio = false;
                            
                            if ($cellSelect.closest(".bprocess-table-dtl").hasClass("bprocess-table-dtl") && $cellSelect.attr("data-edit-value") === undefined) {
                                if (isTrigger === undefined) {
                                    $cellSelect = $cellSelect.closest(".bprocess-table-dtl").find(".bp-detail-row").find("select[data-path='" + selfParam + "']");
                                } else {
                                    $cellSelect = $cellSelect.closest(".bprocess-table-dtl").find(".bp-detail-row:last-child").find("select[data-path='" + selfParam + "']");
                                }
                            }
                            
                            if ($cellSelect.is(':radio')) {
                                
                                var $radioParent = $cellSelect.closest('.radio-list');
                                var _inParam = $radioParent.attr('data-in-param');
                                var _inParamSplit = _inParam.split('|');
                                
                                for (var j = 0; j < _inParamSplit.length; j++) {
                                    var _lastCombo = bp_window_<?php echo $this->methodId; ?>.find("[data-path='" + _inParamSplit[j] + "']");
                                    if (_lastCombo.length && _lastCombo.val() !== '') {
                                        _inParams += _inParamSplit[j] + '=' + encodeURIComponent(_lastCombo.val()) + '&';
                                    }
                                }
                                
                                isLinkedRadio = true;
                                    
                            } else {
                                
                                if ($cellSelect.length && typeof $cellSelect.attr("data-in-param") !== 'undefined' && $cellSelect.attr("data-in-param") !== '') {
                                    var _inParam = $cellSelect.attr('data-in-param');
                                    var _inLookupParam = $cellSelect.attr('data-in-lookup-param').split('|');
                                    var _inParamSplit = _inParam.split('|');
                                    
                                    for (var j = 0; j < _inParamSplit.length; j++) {
                                        var _lastCombo = bp_window_<?php echo $this->methodId; ?>.find("[data-path='" + _inParamSplit[j] + "']");
                                        if (_lastCombo.length && _lastCombo.val() !== '') {
                                            if (_lastCombo.is('[multiple]')) {
                                                _inParams += _lastCombo.find(':selected').map(function(i, el) { return _inLookupParam[j] + '[]=' + $(el).val() + '&'; }).get().join('');
                                            } else {
                                                _inParams += _inLookupParam[j] + '=' + encodeURIComponent(_lastCombo.val()) + '&';
                                            }
                                        }
                                    }
                                }
                            }
                            
                            if (_inParams !== '') {
                                
                                var attrToJson = JSON.parse($cellSelect.attr("data-row-data"));
                                
                                $.ajax({
                                    type: 'post',
                                    url: 'mdobject/bpLinkedCombo',
                                    data: {
                                        processMetaDataId: '<?php echo $this->methodId; ?>', 
                                        selfParam: selfParam, 
                                        inputParams: _inParams, 
                                        jsonAttr: attrToJson, 
                                        requestType: 'linkedCombo'
                                    },
                                    dataType: 'json',
                                    async: false,
                                    beforeSend: function () {
                                        Core.blockUI({animate: true});
                                    },
                                    success: function (dataStr) {
                                        
                                        var comboData = (selfParam in dataStr) ? dataStr[selfParam] : dataStr;
                                        
                                        if (isLinkedRadio == false) {
                                            
                                            var isWithPopupCombo = false;
                                            
                                            if ($cellSelect.hasClass('select2')) {
                                                $cellSelect.select2('val', '');
                                                $cellSelect.select2('readonly', false).select2('enable');
                                                
                                                if ($cellSelect.hasClass('bp-field-with-popup-combo')) {
                                                    var $withPopupComboParent = $cellSelect.closest('.input-group');
                                                    $withPopupComboParent.find('button').removeAttr('disabled');
                                                    isWithPopupCombo = true;
                                                }
                                            } else {
                                                $cellSelect.val('');
                                                $cellSelect.removeAttr('disabled readonly');
                                            }
                                            
                                            if ($cellSelect.is('[multiple]')) {
                                                $cellSelect.find('option').remove();
                                            } else {
                                                $cellSelect.find('option:gt(0)').remove();
                                            }
                                            
                                            $cellSelect.addClass('data-combo-set');

                                            if (isEditMode_<?php echo $this->methodId; ?>) {
                                                $cellSelect.each(function(){
                                                    var _thisSelect = $(this);
                                                    $.each(comboData, function () {
                                                        if (_thisSelect.attr("data-edit-value") == this.META_VALUE_ID) {
                                                            _thisSelect.append($("<option />").val(this.META_VALUE_ID).text(this.META_VALUE_NAME).attr("selected", "selected").attr("data-row-data", JSON.stringify(this.ROW_DATA)));
                                                        } else {
                                                            _thisSelect.append($("<option />").val(this.META_VALUE_ID).text(this.META_VALUE_NAME).attr("data-row-data", JSON.stringify(this.ROW_DATA)));
                                                        }
                                                    });
                                                });
                                            } else { 
                                                $.each(comboData, function () {
                                                    $cellSelect.append($("<option />").val(this.META_VALUE_ID).text(this.META_VALUE_NAME).attr("data-row-data", JSON.stringify(this.ROW_DATA)));
                                                });
                                            }
                                            
                                            if (isWithPopupCombo) {
                                                var count = $cellSelect.find('option:selected').length;
                                                if (count > 0) {
                                                    $withPopupComboParent.find('button:eq(0)').text(count);
                                                    $withPopupComboParent.find('button:eq(1)').show();
                                                } else {
                                                    $withPopupComboParent.find('button:eq(0)').text('..');
                                                    $withPopupComboParent.find('button:eq(1)').hide();
                                                }
                                            }
                                        
                                            $cellSelect.trigger('change');
                                            Core.initSelect2($cellSelect);
                                            
                                        } else {
                                            
                                            var radioList = '';
                                            
                                            $.each(comboData, function () {
                                                radioList += '<label class="radio-inline">';
                                                radioList += '<input type="radio" name="param['+selfParam+']" data-path="'+selfParam+'" value="'+this.META_VALUE_ID+'"> '+this.META_VALUE_NAME;
                                                radioList += '</label>';
                                            });
                                            
                                            $radioParent.empty().append(radioList);
                                            
                                            Core.initUniform($radioParent);
                                        }
                                        
                                        Core.unblockUI();
                                    },
                                    error: function () { alert('Error'); Core.unblockUI(); }
                                });
                                
                            } else {
                            
                                if (isLinkedRadio == false) {
                                    $cellSelect.select2('val', '');
                                    $cellSelect.select2('disable');
                                    
                                    if ($cellSelect.is('[multiple]')) {
                                        $cellSelect.find('option').remove();
                                    } else {
                                        $cellSelect.find('option:gt(0)').remove();
                                    }
                                        
                                    Core.initSelect2($cellSelect);
                                    
                                    if ($cellSelect.hasClass('bp-field-with-popup-combo')) {
                                        var $parent = $cellSelect.closest('.input-group');
                                        $parent.find('button:not(.removebtn)').attr('disabled', 'disabled').text('..');
                                        $parent.find('button.removebtn').hide();
                                    }
                                }
                            }
                        }
                    }
                } catch(err) {
                    console.log('Комбоны хамаарал алдааны мэдээлэл: ' + err);
                    Core.unblockUI();
                }
            }
        });
        bp_window_<?php echo $this->methodId; ?>.on('change', 'input.linked-combo:not(.dateInit)', function (e, isTrigger) {

            var $this = $(this);
            if ((isTrigger == true && isTrigger !== 'linked-combo') || ($this.hasClass("linked-combo-worked") && isTrigger == "EDIT")) {
                return;
            }

            if (isTrigger == "EDIT") {
                $this.addClass("linked-combo-worked");
            }
            
            var _outParam = $this.attr("data-out-param");
            
            if ($this.closest(".bprocess-table-dtl").hasClass("bprocess-table-dtl")) {
                
                var $thisRow = $this.closest("tr");
                var _outParamSplit = _outParam.split("|");
                
                for (var i = 0; i < _outParamSplit.length; i++) {
                    var selfParam = _outParamSplit[i];
                    
                    $thisRow.find("[data-cell-path]").each(function () {
                        var $thisCell = $(this);
                        var $cellSelect = $thisCell.find("[data-path='" + selfParam + "']");
                        
                        if ($cellSelect.length && $thisCell.find('div.param-tree-container').length === 0) {

                            var _inParams = '';

                            if (typeof $cellSelect.attr("data-in-param") !== 'undefined' && $cellSelect.attr("data-in-param") !== '' && $this.closest("table").hasClass("bprocess-table-dtl")) {
                                var _inParam = $cellSelect.attr("data-in-param");
                                var $thisChildRow = $thisCell.closest(".bp-detail-row");
                                var _inParamSplit = _inParam.split("|");

                                for (var j = 0; j < _inParamSplit.length; j++) {
                                    $thisChildRow.find("[data-cell-path]").each(function () {
                                        var _thisChildCell = $(this);
                                        var _lastCombo = _thisChildCell.find("[data-path='" + _inParamSplit[j] + "']");
                                        if (_lastCombo.length && _lastCombo.val() !== '') {
                                            _inParams += _inParamSplit[j] + '=' + encodeURIComponent(_lastCombo.val()) + '&';
                                        } else if ($cellSelect.prop("tagName").toLowerCase() == 'input')
                                            _inParams = '_veritech_';
                                    });
                                }
                            }

                            if (_inParams !== '') {

                                if ($cellSelect.prop("tagName").toLowerCase() == 'select') {

                                    $.ajax({
                                        type: 'post',
                                        url: 'mdobject/bpLinkedCombo',
                                        data: {processMetaDataId: '<?php echo $this->methodId; ?>', selfParam: selfParam, inputParams: _inParams},
                                        dataType: 'json',
                                        async: false,
                                        beforeSend: function () {
                                            Core.blockUI({animate: true});
                                        },
                                        success: function (dataStr) {
                                            
                                            var isWithPopupCombo = false;
                                            
                                            if ($cellSelect.hasClass('select2')) {
                                                $cellSelect.select2('val', '');
                                                $cellSelect.select2('readonly', false).select2('enable');

                                                if ($cellSelect.hasClass('bp-field-with-popup-combo')) {
                                                    var $withPopupComboParent = $cellSelect.closest('.input-group');
                                                    $withPopupComboParent.find('button').removeAttr('disabled');
                                                    isWithPopupCombo = true;
                                                }
                                            } else {
                                                $cellSelect.val('');
                                                $cellSelect.removeAttr('disabled readonly');
                                                $cellSelect.parent().find('input, button').removeAttr('disabled readonly');
                                            }
                                            
                                            if ($cellSelect.is('[multiple]')) {
                                                $cellSelect.find('option').remove();
                                            } else {
                                                $cellSelect.find('option:gt(0)').remove();
                                            }
                                        
                                            var comboData = dataStr[selfParam];
                                            
                                            $cellSelect.addClass("data-combo-set");
                                            $.each(comboData, function () {
                                                $cellSelect.append($("<option />").val(this.META_VALUE_ID).text(this.META_VALUE_NAME).attr("data-row-data", JSON.stringify(this.ROW_DATA)));
                                            });
                                            
                                            if (isWithPopupCombo) {
                                                var count = $cellSelect.find('option:selected').length;
                                                if (count > 0) {
                                                    $withPopupComboParent.find('button:eq(0)').text(count);
                                                    $withPopupComboParent.find('button:eq(1)').show();
                                                } else {
                                                    $withPopupComboParent.find('button:eq(0)').text('..');
                                                    $withPopupComboParent.find('button:eq(1)').hide();
                                                }
                                            }
                                            
                                            Core.initSelect2($cellSelect);
                                            Core.unblockUI();
                                        },
                                        error: function () {
                                            alert("Error");
                                        }
                                    });

                                } else {
                                    $cellSelect.parent().find('input, button').removeAttr('disabled readonly');
                                }

                            } else {
                                if ($cellSelect.prop("tagName").toLowerCase() == 'select') {
                                    if ($cellSelect.hasClass("select2")) {
                                        $cellSelect.select2('val', '');
                                        $cellSelect.select2('disable');
                                    } else {
                                        $cellSelect.val('');
                                        $cellSelect.attr('disable', 'disable');
                                    }
                                    
                                    if ($cellSelect.is('[multiple]')) {
                                        $cellSelect.find('option').remove();
                                    } else {
                                        $cellSelect.find('option:gt(0)').remove();
                                    }
                                    
                                    Core.initSelect2($cellSelect);
                                } else {
                                    $cellSelect.val('');
                                    $cellSelect.parent().find('button').attr('disabled', 'disabled');
                                    $cellSelect.parent().find('input').attr('readonly', 'readonly');
                                }
                            }
                        } 
                    });
                }
            } else {
                var _outParamSplit = _outParam.split("|");
                
                for (var i = 0; i < _outParamSplit.length; i++) {
                
                    var selfParam = _outParamSplit[i];
                    var _inParams = '';
                    var $cellSelect = bp_window_<?php echo $this->methodId; ?>.find("select[data-path='" + selfParam + "'], input[type='radio'][data-path='" + selfParam + "']");
                    
                    if ($cellSelect.length === 0) {
                        var $cellInp = bp_window_<?php echo $this->methodId; ?>.find("input[data-path='" + selfParam + "']");
                        
                        if ($this.val().length > 0 && $cellInp.length > 0) {
                            if ($cellInp.closest(".bprocess-table-dtl").hasClass("bprocess-table-dtl") && $cellInp.attr("data-edit-value") === undefined) {
                                if (isTrigger === undefined) {
                                    $cellInp = $cellInp.closest(".bprocess-table-dtl").find(".bp-detail-row").find("input[data-path='" + selfParam + "']");
                                } else {
                                    $cellInp = $cellInp.closest(".bprocess-table-dtl").find(".bp-detail-row:last-child").find("input[data-path='" + selfParam + "']");
                                }
                            }
                            $cellInp.closest(".meta-autocomplete-wrap").find("input").removeAttr("readonly disabled");
                            $cellInp.parent().find("button").removeAttr("disabled");
                        }
                        
                    } else {
                        
                        var isLinkedRadio = false;
                        
                        if ($cellSelect.closest(".bprocess-table-dtl").hasClass("bprocess-table-dtl") && $cellSelect.attr("data-edit-value") === undefined) {
                            if (isTrigger === undefined) {
                                $cellSelect = $cellSelect.closest(".bprocess-table-dtl").find(".bp-detail-row").find("select[data-path='" + selfParam + "']");
                            } else {
                                $cellSelect = $cellSelect.closest(".bprocess-table-dtl").find(".bp-detail-row:last-child").find("select[data-path='" + selfParam + "']");
                            }
                        }
                        
                        if ($cellSelect.is(':radio')) {
                                
                            var $radioParent = $cellSelect.closest('.radio-list');
                            var _inParam = $radioParent.attr('data-in-param');
                            var _inParamSplit = _inParam.split('|');

                            for (var j = 0; j < _inParamSplit.length; j++) {
                                var _lastCombo = bp_window_<?php echo $this->methodId; ?>.find("[data-path='" + _inParamSplit[j] + "']");
                                if (_lastCombo.length && _lastCombo.val() !== '') {
                                    _inParams += _inParamSplit[j] + '=' + encodeURIComponent(_lastCombo.val()) + '&';
                                }
                            }

                            isLinkedRadio = true;
                                    
                        } else {
                        
                            if ($cellSelect.length && typeof $cellSelect.attr("data-in-param") !== 'undefined' && $cellSelect.attr("data-in-param") !== '') {
                                var _inParam = $cellSelect.attr("data-in-param");
                                var _inParamSplit = _inParam.split("|");

                                for (var j = 0; j < _inParamSplit.length; j++) {
                                    var _lastCombo = bp_window_<?php echo $this->methodId; ?>.find("[data-path='" + _inParamSplit[j] + "']");
                                    if (_lastCombo.length && _lastCombo.val() !== '') {
                                        _inParams += _inParamSplit[j] + '=' + encodeURIComponent(_lastCombo.val()) + '&';
                                    }
                                }
                            }
                        }

                        if (_inParams !== '') {
                            $.ajax({
                                type: 'post',
                                url: 'mdobject/bpLinkedCombo',
                                data: {processMetaDataId: '<?php echo $this->methodId; ?>', selfParam: selfParam, inputParams: _inParams},
                                dataType: 'json',
                                async: false,
                                beforeSend: function () {
                                    Core.blockUI({animate: true});
                                },
                                success: function (dataStr) {
                                    
                                    var comboData = dataStr[selfParam];
                                    
                                    if (isLinkedRadio == false) {
                                        
                                        var isWithPopupCombo = false;
                                        
                                        if ($cellSelect.hasClass("select2")) {
                                            $cellSelect.select2('val', '');
                                            $cellSelect.select2('readonly', false).select2('enable');
                                            
                                            if ($cellSelect.hasClass('bp-field-with-popup-combo')) {
                                                var $withPopupComboParent = $cellSelect.closest('.input-group');
                                                $withPopupComboParent.find('button').removeAttr('disabled');
                                                isWithPopupCombo = true;
                                            }
                                            
                                        } else {
                                            $cellSelect.val('');
                                            $cellSelect.removeAttr('disabled readonly');
                                            $cellSelect.parent().find('input, button').removeAttr('disabled readonly');
                                        }
                                        
                                        if ($cellSelect.is('[multiple]')) {
                                            $cellSelect.find('option').remove();
                                        } else {
                                            $cellSelect.find('option:gt(0)').remove();
                                        }
                                        
                                        $cellSelect.addClass("data-combo-set");
                                        $.each(comboData, function () {
                                            $cellSelect.append($("<option />").val(this.META_VALUE_ID).text(this.META_VALUE_NAME).attr("data-row-data", JSON.stringify(this.ROW_DATA)));
                                        });
                                        
                                        if (isWithPopupCombo) {
                                            var count = $cellSelect.find('option:selected').length;
                                            if (count > 0) {
                                                $withPopupComboParent.find('button:eq(0)').text(count);
                                                $withPopupComboParent.find('button:eq(1)').show();
                                            } else {
                                                $withPopupComboParent.find('button:eq(0)').text('..');
                                                $withPopupComboParent.find('button:eq(1)').hide();
                                            }
                                        }
                                        
                                        Core.initSelect2($cellSelect);
                                        
                                    } else {
                                        
                                        var radioList = '';
                                            
                                        $.each(comboData, function () {
                                            radioList += '<label class="radio-inline">';
                                            radioList += '<input type="radio" name="param['+selfParam+']" data-path="'+selfParam+'" value="'+this.META_VALUE_ID+'"> '+this.META_VALUE_NAME;
                                            radioList += '</label>';
                                        });

                                        $radioParent.empty().append(radioList);

                                        Core.initUniform($radioParent);
                                    }
                                    
                                    Core.unblockUI();
                                },
                                error: function () {
                                    alert("Error");
                                    Core.unblockUI();
                                }
                            });
                            
                        } else {
                            
                            if (isLinkedRadio == false) {
                                if ($cellSelect.prop("tagName").toLowerCase() == 'select') {
                                    if ($cellSelect.hasClass("select2")) {
                                        $cellSelect.select2('val', '');
                                        $cellSelect.select2('disable');
                                    } else {
                                        $cellSelect.val('');
                                        $cellSelect.attr('disable', 'disable');
                                    }
                                    
                                    if ($cellSelect.is('[multiple]')) {
                                        $cellSelect.find('option').remove();
                                    } else {
                                        $cellSelect.find('option:gt(0)').remove();
                                    }
                                        
                                    Core.initSelect2($cellSelect);
                                } else {
                                    $cellSelect.val('');
                                    $cellSelect.parent().find('button').attr('disabled', 'disabled');
                                    $cellSelect.parent().find('input').attr('readonly', 'readonly');
                                }
                            }
                        }
                    }
                }
            }
        });
        bp_window_<?php echo $this->methodId; ?>.on('changeDate', 'input.dateInit.linked-combo', function (e, isTrigger) {

            var $this = $(this);
            if ((isTrigger == true && isTrigger !== 'linked-combo') || ($this.hasClass("linked-combo-worked") && isTrigger == "EDIT")) {
                return;
            }

            if (isTrigger == "EDIT") {
                $this.addClass("linked-combo-worked");
            }
            
            var _outParam = $this.attr("data-out-param");
            
            if ($this.closest(".bprocess-table-dtl").hasClass("bprocess-table-dtl")) {
                
                var $thisRow = $this.closest("tr");
                var _outParamSplit = _outParam.split("|");
                
                for (var i = 0; i < _outParamSplit.length; i++) {
                    var selfParam = _outParamSplit[i];
                    
                    $thisRow.find("[data-cell-path]").each(function () {
                        var $thisCell = $(this);
                        var $cellSelect = $thisCell.find("[data-path='" + selfParam + "']");
                        
                        if ($cellSelect.length && $thisCell.find('div.param-tree-container').length === 0) {

                            var _inParams = '';

                            if (typeof $cellSelect.attr("data-in-param") !== 'undefined' && $cellSelect.attr("data-in-param") !== '' && $this.closest("table").hasClass("bprocess-table-dtl")) {
                                var _inParam = $cellSelect.attr("data-in-param");
                                var $thisChildRow = $thisCell.closest(".bp-detail-row");
                                var _inParamSplit = _inParam.split("|");

                                for (var j = 0; j < _inParamSplit.length; j++) {
                                    $thisChildRow.find("[data-cell-path]").each(function () {
                                        var _thisChildCell = $(this);
                                        var _lastCombo = _thisChildCell.find("[data-path='" + _inParamSplit[j] + "']");
                                        if (_lastCombo.length && _lastCombo.val() !== '') {
                                            _inParams += _inParamSplit[j] + '=' + encodeURIComponent(_lastCombo.val()) + '&';
                                        } else if ($cellSelect.prop("tagName").toLowerCase() == 'input')
                                            _inParams = '_veritech_';
                                    });
                                }
                            }

                            if (_inParams !== '') {

                                if ($cellSelect.prop("tagName").toLowerCase() == 'select') {

                                    $.ajax({
                                        type: 'post',
                                        url: 'mdobject/bpLinkedCombo',
                                        data: {processMetaDataId: '<?php echo $this->methodId; ?>', selfParam: selfParam, inputParams: _inParams},
                                        dataType: 'json',
                                        async: false,
                                        beforeSend: function () {
                                            Core.blockUI({animate: true});
                                        },
                                        success: function (dataStr) {
                                            
                                            var isWithPopupCombo = false;
                                            
                                            if ($cellSelect.hasClass("select2")) {
                                                $cellSelect.select2('val', '');
                                                $cellSelect.select2('readonly', false).select2('enable');

                                                if ($cellSelect.hasClass('bp-field-with-popup-combo')) {
                                                    var $withPopupComboParent = $cellSelect.closest('.input-group');
                                                    $withPopupComboParent.find('button').removeAttr('disabled');
                                                    isWithPopupCombo = true;
                                                }

                                            } else {
                                                $cellSelect.val('');
                                                $cellSelect.removeAttr('disabled readonly');
                                                $cellSelect.parent().find('input, button').removeAttr('disabled readonly');
                                            }
                                            
                                            if ($cellSelect.is('[multiple]')) {
                                                $cellSelect.find('option').remove();
                                            } else {
                                                $cellSelect.find('option:gt(0)').remove();
                                            }
                                            
                                            var comboData = dataStr[selfParam];
                                            $cellSelect.addClass("data-combo-set");
                                            $.each(comboData, function () {
                                                $cellSelect.append($("<option />").val(this.META_VALUE_ID).text(this.META_VALUE_NAME).attr("data-row-data", JSON.stringify(this.ROW_DATA)));
                                            });
                                            
                                            if (isWithPopupCombo) {
                                                var count = $cellSelect.find('option:selected').length;
                                                if (count > 0) {
                                                    $withPopupComboParent.find('button:eq(0)').text(count);
                                                    $withPopupComboParent.find('button:eq(1)').show();
                                                } else {
                                                    $withPopupComboParent.find('button:eq(0)').text('..');
                                                    $withPopupComboParent.find('button:eq(1)').hide();
                                                }
                                            }
                                        
                                            Core.initSelect2($cellSelect);
                                            Core.unblockUI();
                                        },
                                        error: function () {
                                            alert("Error");
                                        }
                                    });

                                } else {
                                    $cellSelect.parent().find('input, button').removeAttr('disabled readonly');
                                }

                            } else {
                                if ($cellSelect.prop("tagName").toLowerCase() == 'select') {
                                    if ($cellSelect.hasClass("select2")) {
                                        $cellSelect.select2('val', '');
                                        $cellSelect.select2('disable');
                                    } else {
                                        $cellSelect.val('');
                                        $cellSelect.attr('disable', 'disable');
                                    }
                                    
                                    if ($cellSelect.is('[multiple]')) {
                                        $cellSelect.find('option').remove();
                                    } else {
                                        $cellSelect.find('option:gt(0)').remove();
                                    }

                                    Core.initSelect2($cellSelect);
                                } else {
                                    $cellSelect.val('');
                                    $cellSelect.parent().find('button').attr('disabled', 'disabled');
                                    $cellSelect.parent().find('input').attr('readonly', 'readonly');
                                }
                            }
                        } 
                    });
                }
            } else {
                var _outParamSplit = _outParam.split("|");
                
                for (var i = 0; i < _outParamSplit.length; i++) {
                
                    var selfParam = _outParamSplit[i];
                    var _inParams = '';
                    var $cellSelect = bp_window_<?php echo $this->methodId; ?>.find("select[data-path='" + selfParam + "'], input[type='radio'][data-path='" + selfParam + "']");
                    
                    if ($cellSelect.length === 0) {
                        var $cellInp = bp_window_<?php echo $this->methodId; ?>.find("input[data-path='" + selfParam + "']");
                        
                        if ($this.val().length > 0 && $cellInp.length > 0) {
                            if ($cellInp.closest(".bprocess-table-dtl").hasClass("bprocess-table-dtl") && $cellInp.attr("data-edit-value") === undefined) {
                                if (isTrigger === undefined) {
                                    $cellInp = $cellInp.closest(".bprocess-table-dtl").find(".bp-detail-row").find("input[data-path='" + selfParam + "']");
                                } else {
                                    $cellInp = $cellInp.closest(".bprocess-table-dtl").find(".bp-detail-row:last-child").find("input[data-path='" + selfParam + "']");
                                }
                            }
                            $cellInp.closest(".meta-autocomplete-wrap").find("input").removeAttr("readonly disabled");
                            $cellInp.parent().find("button").removeAttr("disabled");
                        }
                        
                    } else {
                        
                        var isLinkedRadio = false;
                        
                        if ($cellSelect.closest(".bprocess-table-dtl").hasClass("bprocess-table-dtl") && $cellSelect.attr("data-edit-value") === undefined) {
                            if (isTrigger === undefined) {
                                $cellSelect = $cellSelect.closest(".bprocess-table-dtl").find(".bp-detail-row").find("select[data-path='" + selfParam + "']");
                            } else {
                                $cellSelect = $cellSelect.closest(".bprocess-table-dtl").find(".bp-detail-row:last-child").find("select[data-path='" + selfParam + "']");
                            }
                        }
                        
                        if ($cellSelect.is(':radio')) {
                                
                            var $radioParent = $cellSelect.closest('.radio-list');
                            var _inParam = $radioParent.attr('data-in-param');
                            var _inParamSplit = _inParam.split('|');

                            for (var j = 0; j < _inParamSplit.length; j++) {
                                var _lastCombo = bp_window_<?php echo $this->methodId; ?>.find("[data-path='" + _inParamSplit[j] + "']");
                                if (_lastCombo.length && _lastCombo.val() !== '') {
                                    _inParams += _inParamSplit[j] + '=' + encodeURIComponent(_lastCombo.val()) + '&';
                                }
                            }

                            isLinkedRadio = true;
                                    
                        } else {
                        
                            if ($cellSelect.length && typeof $cellSelect.attr("data-in-param") !== 'undefined' && $cellSelect.attr("data-in-param") !== '') {
                                var _inParam = $cellSelect.attr("data-in-param");
                                var _inParamSplit = _inParam.split("|");

                                for (var j = 0; j < _inParamSplit.length; j++) {
                                    var _lastCombo = bp_window_<?php echo $this->methodId; ?>.find("[data-path='" + _inParamSplit[j] + "']");
                                    if (_lastCombo.length && _lastCombo.val() !== '') {
                                        _inParams += _inParamSplit[j] + '=' + encodeURIComponent(_lastCombo.val()) + '&';
                                    }
                                }
                            }
                        }

                        if (_inParams !== '') {
                            $.ajax({
                                type: 'post',
                                url: 'mdobject/bpLinkedCombo',
                                data: {processMetaDataId: '<?php echo $this->methodId; ?>', selfParam: selfParam, inputParams: _inParams},
                                dataType: 'json',
                                async: false,
                                beforeSend: function () {
                                    Core.blockUI({animate: true});
                                },
                                success: function (dataStr) {
                                    
                                    var comboData = dataStr[selfParam];
                                    
                                    if (isLinkedRadio == false) {
                                        
                                        var isWithPopupCombo = false;
                                        
                                        if ($cellSelect.hasClass("select2")) {
                                            $cellSelect.select2('val', '');
                                            $cellSelect.select2('readonly', false).select2('enable');
                                            
                                            if ($cellSelect.hasClass('bp-field-with-popup-combo')) {
                                                var $withPopupComboParent = $cellSelect.closest('.input-group');
                                                $withPopupComboParent.find('button').removeAttr('disabled');
                                                isWithPopupCombo = true;
                                            }
                                        } else {
                                            $cellSelect.val('');
                                            $cellSelect.removeAttr('disabled readonly');
                                            $cellSelect.parent().find('input, button').removeAttr('disabled readonly');
                                        }
                                        
                                        if ($cellSelect.is('[multiple]')) {
                                            $cellSelect.find('option').remove();
                                        } else {
                                            $cellSelect.find('option:gt(0)').remove();
                                        }
                                        
                                        $cellSelect.addClass("data-combo-set");
                                        $.each(comboData, function () {
                                            $cellSelect.append($("<option />").val(this.META_VALUE_ID).text(this.META_VALUE_NAME).attr("data-row-data", JSON.stringify(this.ROW_DATA)));
                                        });
                                        
                                        if (isWithPopupCombo) {
                                            var count = $cellSelect.find('option:selected').length;
                                            if (count > 0) {
                                                $withPopupComboParent.find('button:eq(0)').text(count);
                                                $withPopupComboParent.find('button:eq(1)').show();
                                            } else {
                                                $withPopupComboParent.find('button:eq(0)').text('..');
                                                $withPopupComboParent.find('button:eq(1)').hide();
                                            }
                                        }
                                        
                                        Core.initSelect2($cellSelect);
                                        
                                    } else {
                                        
                                        var radioList = '';
                                            
                                        $.each(comboData, function () {
                                            radioList += '<label class="radio-inline">';
                                            radioList += '<input type="radio" name="param['+selfParam+']" data-path="'+selfParam+'" value="'+this.META_VALUE_ID+'"> '+this.META_VALUE_NAME;
                                            radioList += '</label>';
                                        });

                                        $radioParent.empty().append(radioList);

                                        Core.initUniform($radioParent);
                                    }
                                    
                                    Core.unblockUI();
                                },
                                error: function () {
                                    alert("Error");
                                    Core.unblockUI();
                                }
                            });
                            
                        } else {
                            
                            if (isLinkedRadio == false) {
                                if ($cellSelect.prop("tagName").toLowerCase() == 'select') {
                                    if ($cellSelect.hasClass("select2")) {
                                        $cellSelect.select2('val', '');
                                        $cellSelect.select2('disable');
                                    } else {
                                        $cellSelect.val('');
                                        $cellSelect.attr('disable', 'disable');
                                    }
                                    
                                    if ($cellSelect.is('[multiple]')) {
                                        $cellSelect.find('option').remove();
                                    } else {
                                        $cellSelect.find('option:gt(0)').remove();
                                    }
                                        
                                    Core.initSelect2($cellSelect);
                                } else {
                                    $cellSelect.val('');
                                    $cellSelect.parent().find('button').attr('disabled', 'disabled');
                                    $cellSelect.parent().find('input').attr('readonly', 'readonly');
                                }
                            }
                        }
                    }
                }
            }
        });
        bp_window_<?php echo $this->methodId; ?>.on('change', 'select.group-dtl-linked, input.group-dtl-linked:not(.dateInit)', function (e, isTriggered) {
            bpGroupLinkedDtl_<?php echo $this->methodId; ?>($(this), isTriggered);
        });
        bp_window_<?php echo $this->methodId; ?>.on('changeDate', 'input.group-dtl-linked.dateInit', function (e, isTriggered) {
            bpGroupLinkedDtl_<?php echo $this->methodId; ?>($(this), isTriggered);
        });
        bp_window_<?php echo $this->methodId; ?>.on('focus', '.bprocess-table-dtl > .tbody > .bp-detail-row', function(){
            var $row = $(this);
            bp_window_<?php echo $this->methodId; ?>.find('.bprocess-table-dtl > .tbody > .bp-detail-row.currentTarget').removeClass('currentTarget');
            $row.addClass('currentTarget');    
            
            <?php
            if (isset($isDetailModifyMode)) {
            ?>
            bpDetailModifyMode($row, '<?php echo $this->uniqId; ?>'); 
            <?php
            }
            ?>            
        });
                
        <?php if (isset($this->basketPath) && $this->basketPath) { ?>
            var _basketPath_<?php echo $this->methodId; ?> = <?php echo $this->basketPathJson ?>;
            var _rowData_<?php echo $this->methodId; ?> = <?php echo $this->rowDataJson ?>;
            $.each(_rowData_<?php echo $this->methodId; ?>, function (index, row) {
                $.each(_basketPath_<?php echo $this->methodId; ?>, function (pindex, prow) { 
                    if (typeof row[(prow.VIEW_FIELD_PATH).toLowerCase()] !== 'undefined') {
                        var _currentTarget_<?php echo $this->methodId; ?> = bp_window_<?php echo $this->methodId; ?>.find('input[data-path="'+ prow.BASKET_PATH +'"][value="'+ row[(prow.VIEW_FIELD_PATH).toLowerCase()] +'"]').closest('tr');
                        _currentTarget_<?php echo $this->methodId; ?>.find('input[data-path*=".'+ prow.BASKET_INPUTPATH +'"], input[data-path*="'+ prow.BASKET_INPUTPATH +'"]').val(row[(prow.BASKET_INPUTPATH).toLowerCase()]).trigger("change");
                    }
                });
            });
        <?php } ?>
        
        <?php 
        if (isset($this->hasMainProcess) && $this->hasMainProcess && isset($this->fillParamData) 
            && isset($this->wfmStatusBtns) && $this->wfmStatusBtns && isset($this->wfmStatusBtns['result']) && $this->wfmStatusBtns['result']) { 
            
            $isCallNextFunction = '1';
            if (isset($arrayToStrParam) && isset($this->newStatusParams) && $this->newStatusParams && $arrayToStrParam) {
                $isCallNextFunction = '0';
            } 
        ?>
            var _wfmStatusBtnArr = <?php echo json_encode($this->wfmStatusBtns['result']); ?>;
            var $bpWfmStatusBtn = $('.workflowBtn-<?php echo $this->methodId ?>');        
            $bpWfmStatusBtn.empty();
            
            $.each(_wfmStatusBtnArr, function(i, v) {
                
                var wfmStatusCode = ('wfmstatuscode' in Object(v)) ? v.wfmstatuscode : '';
                var isNotConfirm = ('isnotconfirm' in Object(v)) ? (v.isnotconfirm ? v.isnotconfirm : '') : '';
                
                if (typeof v.processname != 'undefined' && v.processname != '' && (v.wfmstatusprocessid == '' || v.wfmstatusprocessid == 'null' || v.wfmstatusprocessid == null)) {
                    if (v.wfmisneedsign == '1') {
                        $bpWfmStatusBtn.append(
                            '<button type="button" data-statuscode="' + wfmStatusCode + '" data-dm-id="<?php echo $this->dmMetaDataId; ?>" class="btn btn-sm purple-plum btn-circle mr5" style="background-color:'+ v.wfmstatuscolor +'"' 
                            + ' onclick="beforeSignChangeWfmStatusId(this, \'' + v.wfmstatusid + '\', \'<?php echo $this->dmMetaDataId; ?>\', \'<?php echo $this->refStructureId; ?>\', \'' + $.trim(v.wfmstatuscolor) + '\', \'' + v.processname + '\');" id="' + v.wfmstatusid + '">' + v.processname +
                            ' <i class="fa fa-key"></i></button>');
                    } else if (v.wfmisneedsign == '2') {
                        $bpWfmStatusBtn.append(
                            '<button type="button" data-statuscode="' + wfmStatusCode + '" data-dm-id="<?php echo $this->dmMetaDataId; ?>" class="btn btn-sm purple-plum btn-circle mr5" style="background-color:'+ v.wfmstatuscolor +'"' 
                            + ' onclick="beforeHardSignChangeWfmStatusId(this, \'' + v.wfmstatusid + '\', \'<?php echo $this->dmMetaDataId; ?>\', \'<?php echo $this->refStructureId; ?>\', \'' + $.trim(v.wfmstatuscolor) + '\', \'' + v.processname + '\');" id="' + v.wfmstatusid + '">' + v.processname + ' <i class="fa fa-key"></i></button>');
                    } else if (v.wfmisneedsign == '4') {
                        $bpWfmStatusBtn.append(
                            '<button type="button" data-isshowprevnext="<?php echo $this->isShowPrevNext; ?>" data-statuscode="' + wfmStatusCode + '" data-rowdata="<?php echo (isset($rowJson) ? $rowJson : ''); ?>" data-dm-id="<?php echo $this->dmMetaDataId; ?>" class="btn btn-sm purple-plum btn-circle mr5" style="background-color:'+ v.wfmstatuscolor +'"' 
                            + ' onclick="pinCodeChangeWfmStatusId(this, undefined, \'' + v.wfmstatusid + '\', \'<?php echo $this->dmMetaDataId; ?>\', \'<?php echo $this->refStructureId; ?>\', \'' + $.trim(v.wfmstatuscolor) + '\', \'' + v.processname + '\', \'\', \'\', \'\', \'<?php  echo $this->uniqId; ?>\', \'<?php  echo $this->methodId; ?>\', \'\', \'\', \'\', \'' + v.wfmisdescrequired + '\', undefined, undefined, undefined, \'<?php echo $isCallNextFunction ?>\', \'' + v.isformnotsubmit + '\', \'' + v.usedescriptionwindow + '\', \''+isNotConfirm+'\');" id="' + v.wfmstatusid + '">' + v.processname + ' <i class="fa fa-key"></i></button>');
                    } else {
                        $bpWfmStatusBtn.append(
                            '<button type="button" data-isshowprevnext="<?php echo $this->isShowPrevNext; ?>" data-statuscode="' + wfmStatusCode + '" data-rowdata="<?php echo (isset($rowJson) ? $rowJson : ''); ?>" data-dm-id="<?php echo $this->dmMetaDataId; ?>" class="btn btn-sm purple-plum btn-circle mr5" style="background-color:'+ v.wfmstatuscolor +'"' 
                              + ' onclick="changeWfmStatusId(this, \'' + v.wfmstatusid + '\', \'<?php echo $this->dmMetaDataId; ?>\', \'<?php echo $this->refStructureId; ?>\', \'' + $.trim(v.wfmstatuscolor) + '\', \'' + v.processname + '\', \'\', \'\', \'\', \'<?php  echo $this->uniqId; ?>\', \'<?php  echo $this->methodId; ?>\', \'\', \'\', \'\', \'' + v.wfmisdescrequired + '\', undefined, undefined, undefined, \'<?php echo $isCallNextFunction ?>\', \'' + v.isformnotsubmit + '\', \'' + v.usedescriptionwindow + '\', \''+isNotConfirm+'\');" id="' + v.wfmstatusid + '">' + v.processname +
                            '</button>');
                    }
                } else if (v.wfmstatusprocessid != '' || v.wfmstatusprocessid != 'null' || v.wfmstatusprocessid != null) {
                    if (typeof v.wfmuseprocesswindow !== 'undefined' && v.wfmuseprocesswindow !== '1') {
                        $bpWfmStatusBtn.append(
                            '<button type="button" data-isshowprevnext="<?php echo $this->isShowPrevNext; ?>" data-statuscode="' + wfmStatusCode + '" data-rowdata="<?php echo (isset($rowJson) ? $rowJson : '') ?>" data-dm-id="<?php echo $this->dmMetaDataId; ?>" class="btn btn-sm purple-plum btn-circle mr5" style="background-color:'+ v.wfmstatuscolor +'"' 
                                + ' onclick="changeWfmStatusId(this, \'' + v.wfmstatusid + '\', \'<?php echo $this->dmMetaDataId; ?>\', \'<?php echo $this->refStructureId; ?>\', \'' + $.trim(v.wfmstatuscolor) + '\', \'' + v.processname + '\', \'\', \'\', \'\', \'<?php  echo $this->uniqId; ?>\', \'<?php echo $this->methodId; ?>\', \'\', \'\', \'' + v.wfmstatusprocessid + '\', \'' + v.wfmisdescrequired + '\', undefined, undefined, \''+ v.wfmuseprocesswindow +'\', \'<?php echo $isCallNextFunction ?>\', \'' + v.isformnotsubmit + '\', \'' + v.usedescriptionwindow + '\', \''+isNotConfirm+'\');" id="' + v.wfmstatusid + '">' + v.processname +
                            '</button>');
                    } else {
                        if (v.wfmisneedsign == '1') {
                            $bpWfmStatusBtn.append(
                                '<button type="button" data-statuscode="' + wfmStatusCode + '" data-dm-id="<?php echo $this->dmMetaDataId; ?>" data-mainmetaDataId="<?php echo $this->methodId; ?>" data-mainuniqId="<?php echo $this->uniqId; ?>" class="btn btn-sm purple-plum btn-circle mr5" style="background-color:'+ v.wfmstatuscolor +'"' 
                                    + ' onclick="transferProcessAction(\'signProcess\', \'<?php echo $this->dmMetaDataId; ?>\', \'' + v.wfmstatusprocessid + '\', \''+v.metatypeid+'\', \'toolbar\', this, {callerType: \'<?php echo $this->metaDataCode; ?>\', isWorkFlow: true, wfmStatusId: \'' + v.wfmstatusid + '\', wfmStatusCode: \'' + wfmStatusCode + '\'}, \'dataViewId=<?php echo $this->dmMetaDataId ?>&refStructureId=<?php echo $this->refStructureId; ?>&statusId=' + v.wfmstatusid + '&statusName=' + v.processname + '&statusColor=' + $.trim(v.wfmstatuscolor) + '&rowId=' + '<?php echo $this->realSourceIdAutoMap ?>' + '\', \'\', \'' + encodeURIComponent(JSON.stringify(<?php echo json_encode($this->selectedRowData); ?>)) + '\');">' + v.processname +
                                ' <i class="fa fa-key"></i></button>');
                        } else if (v.wfmisneedsign == '2') {
                            $bpWfmStatusBtn.append(
                                '<button type="button" data-statuscode="' + wfmStatusCode + '" data-dm-id="<?php echo $this->dmMetaDataId; ?>" data-mainmetaDataId="<?php echo $this->methodId; ?>" data-mainuniqId="<?php echo $this->uniqId ?>" class="btn btn-sm purple-plum btn-circle mr5" style="background-color:'+ v.wfmstatuscolor +'"' 
                                    + ' onclick="transferProcessAction(\'hardSignProcess\', \'<?php echo $this->dmMetaDataId; ?>\', \'' + v.wfmstatusprocessid + '\', \''+v.metatypeid+'\', \'toolbar\', this, {callerType: \'<?php echo $this->metaDataCode ?>\', isWorkFlow: true, wfmStatusId: \'' + v.wfmstatusid + '\', wfmStatusCode: \'' + wfmStatusCode + '\'}, \'dataViewId=<?php echo $this->dmMetaDataId ?>&refStructureId=<?php echo $this->refStructureId; ?>&statusId=' + v.wfmstatusid + '&statusName=' + v.processname + '&statusColor=' + $.trim(v.wfmstatuscolor) + '&rowId=' + '<?php echo $this->realSourceIdAutoMap ?>' + '\', \'\', \'' + encodeURIComponent(JSON.stringify(<?php echo json_encode($this->selectedRowData); ?>)) + '\');">' + v.processname +
                                ' <i class="fa fa-key"></i></button>');
                        } else if (v.wfmisneedsign == '4') {
                            $bpWfmStatusBtn.append(
                                '<button type="button" data-statuscode="' + wfmStatusCode + '" data-dm-id="<?php echo $this->dmMetaDataId; ?>" data-mainmetaDataId="<?php echo $this->methodId; ?>" data-mainuniqId="<?php echo $this->uniqId ?>" class="btn btn-sm purple-plum btn-circle mr5" style="background-color:'+ v.wfmstatuscolor +'"' 
                                    + ' onclick="transferProcessAction(\'pinCode\', \'<?php echo $this->dmMetaDataId; ?>\', \'' + v.wfmstatusprocessid + '\', \''+v.metatypeid+'\', \'toolbar\', this, {callerType: \'<?php echo $this->metaDataCode ?>\', isWorkFlow: true, wfmStatusId: \'' + v.wfmstatusid + '\', wfmStatusCode: \'' + wfmStatusCode + '\'}, \'dataViewId=<?php echo $this->dmMetaDataId ?>&refStructureId=<?php echo $this->refStructureId; ?>&statusId=' + v.wfmstatusid + '&statusName=' + v.processname + '&statusColor=' + $.trim(v.wfmstatuscolor) + '&rowId=' + '<?php echo $this->realSourceIdAutoMap ?>' + '\', \'\', \'' + encodeURIComponent(JSON.stringify(<?php echo json_encode($this->selectedRowData); ?>)) + '\');">' + v.processname +
                                ' <i class="fa fa-key"></i></button>');
                        } else {
                            $bpWfmStatusBtn.append(
                                '<button type="button" data-isshowprevnext="<?php echo $this->isShowPrevNext; ?>" data-statuscode="' + wfmStatusCode + '" data-rowdata="<?php echo (isset($rowJson) ? $rowJson : '') ?>" data-dm-id="<?php echo $this->dmMetaDataId; ?>" class="btn btn-sm purple-plum btn-circle mr5" style="background-color:'+ v.wfmstatuscolor +'"' 
                                    + ' onclick="changeWfmStatusId(this, \'' + v.wfmstatusid + '\', \'<?php echo $this->dmMetaDataId; ?>\', \'<?php echo $this->refStructureId; ?>\', \''  + $.trim(v.wfmstatuscolor) + '\', \''  + v.processname + '\', \'\', \'\', \'\', \'<?php echo $this->uniqId; ?>\', \'<?php echo $this->methodId; ?>\', \'\', \'\', \'' + v.wfmstatusprocessid + '\', \'' + v.wfmisdescrequired + '\', undefined, undefined, undefined, \'<?php echo $isCallNextFunction ?>\', \'' + v.isformnotsubmit + '\', \'' + v.usedescriptionwindow + '\', \''+isNotConfirm+'\');" id="' + v.wfmstatusid + '">' + v.processname +
                                '</button>');
                        }
                    }
                }
            });
        <?php } ?>
        
        var $disablePopup = bp_window_<?php echo $this->methodId; ?>.find('input[type="text"][readonly].lookup-code-autocomplete');
        if ($disablePopup.length) {
            $disablePopup.each(function() {
                var $this = $(this), $parent = $this.closest('.input-group'), 
                    $hidden = $parent.find('input.popupInit[data-in-param]');
                if ($hidden.length) {
                    var relationInParam = $hidden.attr('data-in-param'), 
                        relationInParamVal = bp_window_<?php echo $this->methodId; ?>.find('[data-path="'+relationInParam+'"]').val();
                    if (relationInParamVal != '' && typeof relationInParamVal != 'undefined') {
                        $parent.find('.lookup-code-autocomplete, .lookup-name-autocomplete').removeAttr('readonly');
                    }    
                }    
            });
        }

        if (bp_window_<?php echo $this->methodId; ?>.find('input[name="processSubType"]').val() === 'endtoend') {
            bp_window_<?php echo $this->methodId; ?>.find('.center-sidebar').addClass('hidden');
            
            var response = $.ajax({
              type: "post",
              url: "Mdprocessflow/getTaskFlowType",
              data: {
                mainBpId: "<?php echo $this->methodId; ?>"
              },
              dataType: "json",
              async: false
            });
            var responseValue = response.responseJSON;      
            
            if (responseValue.TASKFLOW_TYPE === 'UI') {
                bp_window_<?php echo $this->methodId; ?>.find('.center-sidebar').removeClass('hidden');
            }
            
            var taskFlowSelectedRow = <?php echo json_encode($this->selectedRowData); ?>;
            if (responseValue.TASKFLOW_TYPE === 'UI') {
                bp_window_<?php echo $this->methodId; ?>.closest('.ui-dialog').find('.ui-dialog-buttonset').prepend('<button type="button" class="btn green-meadow btn-sm bp-continue-taskflow-btn-<?php echo $this->uniqId; ?>">Үргэлжлүүлэх</button>')
            } else {
                bp_window_<?php echo $this->methodId; ?>.closest('.ui-dialog').find('.ui-dialog-buttonset').prepend('<button type="button" class="d-none btn green-meadow btn-sm bp-continue-taskflow-btn-<?php echo $this->uniqId; ?>">Үргэлжлүүлэх</button>')
            }
            <?php if (Input::post('isTaskFlowView') != '1') { ?>
                $(document.body).on("click", ".bp-continue-taskflow-btn-<?php echo $this->uniqId; ?>", function (e) {
                    var $this = $(this);
                    $this.hide();
                    
                    if (!Object.keys(taskFlowSelectedRow).length && $("input[data-path='id']", bp_window_<?php echo $this->methodId; ?>).val()) {
                        taskFlowSelectedRow = [{id: $("input[data-path='id']", bp_window_<?php echo $this->methodId; ?>).val()}];
                    }

                    if (typeof _taskFlowSelectedRow === 'undefined') {                
                        $.getScript(URL_APP + 'assets/custom/addon/plugins/jsplumb/jsplumb.min.js', function() {
                            if ($("link[href='assets/custom/addon/plugins/jsplumb/css/style.v3.css']").length == 0) {
                                $("head").append('<link rel="stylesheet" type="text/css" href="assets/custom/addon/plugins/jsplumb/css/style.v3.css"/>');
                            }            
                            $.getScript(URL_APP + 'middleware/assets/js/mdprocessflowview.js', function() {      
                                _taskFlowSelectedRow = taskFlowSelectedRow[0];
                                runTaskFlowInsideBpRender('<?php echo $this->methodId; ?>', _taskFlowSelectedRow, bp_window_<?php echo $this->methodId; ?>);             
                            });
                        });            
                    } else {
                        _taskFlowSelectedRow = taskFlowSelectedRow[0];
                        runTaskFlowInsideBpRender('<?php echo $this->methodId; ?>', _taskFlowSelectedRow, bp_window_<?php echo $this->methodId; ?>);
                    }       

                    $this.closest('.ui-dialog').find('.center-sidebar').addClass('hidden');
                });   
                if (responseValue.TASKFLOW_TYPE !== 'UI') {
                    setTimeout(function () {
                    bp_window_<?php echo $this->methodId; ?>.closest('.ui-dialog').find('.ui-dialog-buttonset').find('.bp-continue-taskflow-btn-<?php echo $this->uniqId; ?>').trigger('click');
                    }, 100);
                }
                bp_window_<?php echo $this->methodId; ?>.closest('.ui-dialog').find('.ui-dialog-buttonset').find('.bp-btn-save').addClass('hide');
//                bp_window_<?php echo $this->methodId; ?>.closest('.ui-dialog').addClass('hidden');
            <?php } ?>
        }
    });
    
    function bpGroupLinkedDtl_<?php echo $this->methodId; ?>(elem, isTriggered) {
        if (!isTriggered) {
            Core.blockUI({boxed: true, message: 'Loading...'});

            setTimeout(function () {

                var $this = elem;
                var $groupPath = $this.attr('data-out-group');

                var postData = {
                    uniqId: '<?php echo $this->uniqId; ?>',
                    processMetaDataId: '<?php echo $this->methodId; ?>',
                    cacheId: '<?php echo $this->cacheId; ?>',
                    changedParamPath: $this.attr('data-path'),
                    headerData: bp_window_<?php echo $this->methodId; ?>.find('div.bp-header-param').find('input, select').serialize(),
                    groupPath: $groupPath
                };
                
                if ($this.hasAttr('data-ignore-dvfilter') && $this.attr('data-ignore-dvfilter') == '1') {
                    postData.ignoreDvFilter = 1;
                    postData.lookupMetaId = bp_window_<?php echo $this->methodId; ?>.find("[data-table-path='" + $groupPath + "']").attr('data-lookupmeta');
                }

                if (postData.cacheId !== '') {
                    var $pagerElement = $("div[data-pg-grouppath='" + $groupPath + "']", bp_window_<?php echo $this->methodId; ?>);
                    if ($pagerElement.length) {
                        postData['pageSize'] = $pagerElement.attr('data-pg-pagesize');
                        postData['rowId'] = bp_window_<?php echo $this->methodId; ?>.find("table[data-table-path='" + $groupPath + "']").attr('data-row-id');
                        postData['isEditMode'] = isEditMode_<?php echo $this->methodId; ?>;
                    }
                }

                $.ajax({
                    type: 'post',
                    url: 'mdwebservice/bpLinkedGroup',
                    data: postData,
                    dataType: 'json',
                    async: false,
                    success: function(dataStr) {
                        $.each(dataStr, function(tablePath, v) {

                            var $getTable = bp_window_<?php echo $this->methodId; ?>.find("[data-table-path='" + tablePath + "']");

                            if ($getTable.length) {

                                if (dataStr.hasOwnProperty(tablePath+'_count') && dataStr[tablePath+'_count'] > 200 && typeof $getTable.attr('data-pager') === 'undefined') {
                                    var isLargeRows = true;
                                } else {
                                    var isLargeRows = false;
                                }

                                var $getTableBody = $getTable.find('> .tbody:eq(0)');

                                $getTableBody.css({display: 'none'});
                                $getTableBody[0].innerHTML = v;

                                if (isLargeRows) {
                                    $getTableBody.find('.bigdecimalInit, .numberInit').autoNumeric('init', {aPad: true, mDec: 2, vMin: '-999999999999999999999999999999.999999999999999999999999999999', vMax: '999999999999999999999999999999.999999999999999999999999999999'});
                                    $getTableBody.find('.select2').removeClass('select2');
                                } else {
                                    Core.initBPDtlInputType($getTableBody);
                                }

                                var $rowEl = $getTableBody.find('> tr');
                                var $rowLen = $rowEl.length, $rowi = 0;

                                if ($rowLen === 1) {

                                    bpFullScriptsWithoutEvent_<?php echo $this->methodId; ?>($($rowEl[$rowi]), tablePath, true, true, 'autofill');

                                } else if ($rowLen > 1) {

                                    $rowLen = $rowLen - 1;

                                    for ($rowi; $rowi < $rowLen; $rowi++) { 
                                        bpFullScriptsWithoutEvent_<?php echo $this->methodId; ?>($($rowEl[$rowi]), tablePath, true, false, 'autofill');
                                    }

                                    bpFullScriptsWithoutEvent_<?php echo $this->methodId; ?>($($rowEl[$rowLen]), tablePath, true, true, 'autofill');
                                }

                                $getTableBody.css({display: ''});
                                enableBpDetailFilterByElement($getTable);
                                bpDetailHideShowFields($getTable);

                                if (isLargeRows) {
                                    bpDetailFreezeNoLeft($getTable);
                                } else {
                                    bpDetailFreeze($getTable);
                                }

                                if (postData.cacheId !== '') {
                                    bpDetailPagerRefreshNavigationBar($("div[data-pg-grouppath='" + tablePath + "']", bp_window_<?php echo $this->methodId; ?>), dataStr[tablePath+'_count'], 1);
                                    bpDetailPagerSetFooterAmount($getTable, dataStr[tablePath+'_aggregate']);
                                } else {
                                    dtlAggregateFunction_<?php echo $this->methodId; ?>();
                                }
                                
                                if (!postData.hasOwnProperty('ignoreDvFilter')) {
                                    $($rowEl[0]).find('input:not([data-isdisabled], [readonly="readonly"], [readonly], readonly, [disabled="disabled"], [disabled], disabled, input.meta-name-autocomplete):visible:first').focus().select();
                                }
                            }
                        });
                    },
                    error: function () {
                        alert("Error");
                        Core.unblockUI();
                    }
                    
                }).done(function () {
                    Core.unblockUI();
                });
            }, 500);
        }
    }
        
    <?php echo $this->bpFullScriptsVarFnc; ?>    
    
    $(function () { 
        
        bpFullScriptsWithoutEvent_<?php echo $this->methodId; ?>();
        <?php echo $this->bpFullScriptsEvent; ?>   
        
        bpLoadDetailHideShowFields(bp_window_<?php echo $this->methodId; ?>);
        
        dtlAggregateFunction_<?php echo $this->methodId; ?>();
        setVerticalBannerSize();

        showRenderSidebar(bp_window_<?php echo $this->methodId; ?>);
        bpFileInputPlugin(bp_window_<?php echo $this->methodId; ?>);

        var prevNextRowTop = null;

        bp_window_<?php echo $this->methodId; ?>.on('keydown', "input[type='text'][class]:visible, input[type='file'][class]:visible, input[type='checkbox']:not([data-isdisabled])", function (e) {
            
            try {
                
                var keyCode = (e.keyCode ? e.keyCode : e.which);
                var $this = $(this);
                
                if (tabMoveMode_<?php echo $this->methodId; ?> == 'right') {

                    if (!e.shiftKey && keyCode === 13) { // enter 
                        
                        if ($this.hasClass('meta-autocomplete')) {
                            if ($this.closest('div.bp-tab-table').length) {
                                var $tbl = $this.closest('div.bp-tab-table');
                                var $tblInput = $tbl.find('input:visible:not([data-isdisabled], [readonly="readonly"], [readonly], readonly, [disabled="disabled"], [disabled], disabled, input.meta-name-autocomplete, .select2-focusser)');
                                var $cellIndex = $tblInput.index($this);

                                if ($tblInput.length == ($cellIndex + 1)) {
                                    var $tableIndex = $("div.bp-tab-table", bp_window_<?php echo $this->methodId; ?>).index($tbl) + 1;
                                    var $tableNext = $("div.bp-tab-table", bp_window_<?php echo $this->methodId; ?>).eq($tableIndex);
                                    if ($tableNext.find('.bp-tab-table-control').length > 0) {
                                        $tableNext.find('.bp-tab-table-control:visible input:first:visible:not([data-isdisabled], [readonly="readonly"], [readonly], readonly, [disabled="disabled"], [disabled], disabled, input.meta-name-autocomplete)').focus().select();
                                    } 
                                } else {
                                    if ($this.hasClass('meta-name-autocomplete')) {
                                        var $thisRow = $this.closest('.bp-tab-table-cell');

                                        if ($thisRow.next('.bp-tab-table-cell').length > 0) {
                                            $thisRow.next('.bp-tab-table-cell').find('input:first:visible:not([data-isdisabled], [readonly="readonly"], [readonly], readonly, [disabled="disabled"], [disabled], disabled, input.meta-name-autocomplete)').focus().select();
                                        } else {
                                            $tblInput = $tbl.find('input:visible:not([data-isdisabled], [readonly="readonly"], [readonly], readonly, [disabled="disabled"], [disabled], disabled)');
                                            $cellIndex = $tblInput.index($this);
                                            if (typeof $tblInput[$cellIndex + 1] !== 'undefined') {
                                                $tblInput.eq($cellIndex + 1).focus().select();
                                            }
                                        }
                                    } else {
                                        $tblInput.eq($cellIndex + 1).focus().select();
                                    }
                                }

                            } else {
                                var $tbl = $this.closest('table');
                                var $parentDiv = $tbl.parent('div');
                                var $tblInput = $tbl.find('tbody:eq(0) > tr > td:visible input:not([data-isdisabled], [readonly="readonly"], [readonly], readonly, [disabled="disabled"], [disabled], disabled, input.meta-name-autocomplete, .select2-focusser):visible');
                                var $cellIndex = $tblInput.index($this);

                                if ($this.is('[readonly]')) { 
                                    $cellIndex = $cellIndex - 1;
                                }

                                if ($tblInput.length == ($cellIndex + 1)) {
                                    var $headerTbl = $parentDiv.hasClass('bp-header-param');

                                    if ($headerTbl) {
                                        var $tableIndex = $("table.table", bp_window_<?php echo $this->methodId; ?>).index($tbl) + 1;
                                        var $tableNext = $("table.table", bp_window_<?php echo $this->methodId; ?>).eq($tableIndex);
                                        if ($tableNext.find('tbody > tr').length > 0) {
                                            $tableNext.find('tbody > tr > td:visible input:not([data-isdisabled], [readonly="readonly"], [readonly], readonly, [disabled="disabled"], [disabled], disabled, input.meta-name-autocomplete):visible:first').focus().select();
                                        } 
                                    } else {
                                        var $thisRow = $this.closest('tr');

                                        if ($thisRow.next('tr').length > 0) {
                                            $thisRow.next('tr').find('input:not([data-isdisabled], [readonly="readonly"], [readonly], readonly, [disabled="disabled"], [disabled], disabled, input.meta-name-autocomplete):visible:first').focus().select();
                                        } else {

                                            if (typeof $tbl.attr('data-pager') !== 'undefined' && $tbl.attr('data-pager') === 'true' && bpDetailPagerIsNextButtonActive($tbl)) {
                                                bpDetailPagerNextTrigger($tbl);
                                                return;
                                            } 

                                            var $addBtn = '';
                                            if (!$tbl.hasAttr('data-disable-enter-addrow')) {
                                                if ($parentDiv.hasClass('param-tree-container')) {
                                                    $addBtn = $parentDiv.children('.btn:visible:first');
                                                } else {
                                                    if ($tbl.closest('fieldset').find('.bp-add-one-row:visible:first').length) {
                                                        $addBtn = $tbl.closest('fieldset').find('.bp-add-one-row:visible:first');
                                                    } else if ($parentDiv.prev('div').find('.bp-add-one-row:visible:first').length) {
                                                        $addBtn = $parentDiv.prev('div').find('.bp-add-one-row:visible:first');
                                                    } else if ($parentDiv.closest('.theme-grid-area').find('.bp-add-one-row:visible:first').length) {
                                                        $addBtn = $parentDiv.closest('.theme-grid-area').find('.bp-add-one-row:visible:first');
                                                    }
                                                }
                                            }
                                            if ($addBtn) {
                                                $.when(
                                                    $addBtn.trigger('click')
                                                ).done(function() {
                                                    $tbl.promise().done(function() {
                                                        $thisRow.next('tr').find('input:not([data-isdisabled], [readonly="readonly"], [readonly], readonly, [disabled="disabled"], [disabled], disabled, input.meta-name-autocomplete):visible:first').focus().select();
                                                    });
                                                });
                                            }
                                        }
                                    }
                                } else {
                                    if ($this.hasClass('meta-name-autocomplete')) {
                                        var $thisRow = $this.closest('tr');

                                        if ($thisRow.next('tr').length > 0) {
                                            $thisRow.next('tr').find('input:not([data-isdisabled], [readonly="readonly"], [readonly], readonly, [disabled="disabled"], [disabled], disabled, input.meta-name-autocomplete):visible:first').focus().select();
                                        } else {
                                            $tblInput = $tbl.find('tbody:eq(0) > tr > td:visible input:not([data-isdisabled], [readonly="readonly"], [readonly], readonly, [disabled="disabled"], [disabled], disabled, input.meta-name-autocomplete):visible');
                                            $cellIndex = $tblInput.index($this);
                                            if (typeof $tblInput[$cellIndex + 1] !== 'undefined') {
                                                $tblInput.eq($cellIndex + 1).focus().select();
                                            }
                                        }
                                    } else {
                                        $tblInput.eq($cellIndex + 1).focus().select();
                                    }
                                }
                            }

                        } else {
                            if ($this.closest('div.bp-tab-table').length) {
                                var $tbl = $this.closest('div.bp-tab-table');
                                var $tblInput = $tbl.find('input:visible:not([data-isdisabled], [readonly="readonly"], [readonly], readonly, [disabled="disabled"], [disabled], disabled, input.meta-name-autocomplete, .select2-focusser, input.meta-autocomplete[value!=""])');
                                var $cellIndex = $tblInput.index($this);

                                if ($tblInput.length == ($cellIndex + 1)) {
                                    var $tableIndex = $("div.bp-tab-table", bp_window_<?php echo $this->methodId; ?>).index($tbl) + 1;
                                    var $tableNext = $("div.bp-tab-table", bp_window_<?php echo $this->methodId; ?>).eq($tableIndex);
                                    if ($tableNext.find('.bp-tab-table-control').length > 0) {
                                        $tableNext.find('.bp-tab-table-control:visible input:first:visible:not([data-isdisabled], [readonly="readonly"], [readonly], readonly, [disabled="disabled"], [disabled], disabled, input.meta-name-autocomplete, input.meta-autocomplete[value!=""])').focus().select();
                                    } 
                                } else {
                                    if ($this.hasClass('meta-name-autocomplete')) {
                                        var $thisRow = $this.closest('.bp-tab-table-cell');

                                        if ($thisRow.next('.bp-tab-table-cell').length > 0) {
                                            $thisRow.next('.bp-tab-table-cell').find('input:first:visible:not([data-isdisabled], [readonly="readonly"], [readonly], readonly, [disabled="disabled"], [disabled], disabled, input.meta-autocomplete[value!=""])').focus().select();
                                        } else {
                                            $tblInput = $tbl.find('input:visible:not([data-isdisabled], [readonly="readonly"], [readonly], readonly, [disabled="disabled"], [disabled], disabled, input.meta-autocomplete[value!=""])');
                                            $cellIndex = $tblInput.index($this);
                                            if (typeof $tblInput[$cellIndex + 1] !== 'undefined') {
                                                $tblInput.eq($cellIndex + 1).focus().select();
                                            }
                                        }
                                    } else {
                                        $tblInput.eq($cellIndex + 1).focus().select();
                                    }
                                }
                            } else {
                                var $tbl = $this.closest('table');
                                var $parentDiv = $tbl.parent('div');
                                var $tblInput = $tbl.find('tbody:eq(0) > tr > td:visible input:not([data-isdisabled], [readonly="readonly"], [readonly], readonly, [disabled="disabled"], [disabled], disabled, input.meta-name-autocomplete, input.meta-autocomplete[value!=""], .select2-focusser):visible');
                                var $cellIndex = $tblInput.index($this), $cellIndex = $cellIndex > 0 ? $cellIndex : 0;

                                if ($this.is('[readonly]')) { 
                                    $cellIndex = $cellIndex - 1;
                                }

                                if ($tblInput.length == ($cellIndex + 1)) {

                                    var $headerTbl = $parentDiv.hasClass('bp-header-param');

                                    if ($headerTbl) {
                                        var $tableIndex = $("table.table", bp_window_<?php echo $this->methodId; ?>).index($tbl) + 1;
                                        var $tableNext = $("table.table", bp_window_<?php echo $this->methodId; ?>).eq($tableIndex);
                                        if ($tableNext.find('tbody > tr').length > 0) {
                                            $tableNext.find('tbody > tr > td:visible input:not([data-isdisabled], [readonly="readonly"], [readonly], readonly, [disabled="disabled"], [disabled], disabled, input.meta-name-autocomplete, input.meta-autocomplete[value!=""]):visible:first').focus().select();
                                        } 
                                    } else {
                                        var $thisRow = $this.closest('tr');
                                        var $nextRow = $thisRow.next('tr');

                                        if ($nextRow.length) {

                                            var $parentScrollDiv = $tbl.closest('.bp-overflow-xy-auto');
                                            var scrollHeight = $parentScrollDiv[0].scrollHeight;
                                            var clientHeight = $parentScrollDiv[0].clientHeight;

                                            if (scrollHeight !== clientHeight) {
                                                var nextRowTop = $nextRow.offset().top;
                                                var parentScrollTop = $parentScrollDiv.scrollTop();

                                                if (prevNextRowTop && nextRowTop > prevNextRowTop) {
                                                    $parentScrollDiv.scrollTop(parentScrollTop + $nextRow.height() +25);
                                                }
                                                prevNextRowTop = nextRowTop;
                                            }

                                            $nextRow.find('input:not([data-isdisabled], [readonly="readonly"], [readonly], readonly, [disabled="disabled"], [disabled], disabled, input.meta-name-autocomplete, input.meta-autocomplete[value!=""]):visible:first').focus().select();

                                        } else {
                                            
                                            if (typeof $tbl.attr('data-pager') !== 'undefined' && $tbl.attr('data-pager') === 'true' && bpDetailPagerIsNextButtonActive($tbl)) {
                                                bpDetailPagerNextTrigger($tbl);
                                                return;
                                            } 

                                            var $addBtn = '';
                                            
                                            if (!$tbl.hasAttr('data-disable-enter-addrow')) {
                                                if ($parentDiv.hasClass('param-tree-container')) {
                                                    $addBtn = $parentDiv.children('.btn:visible:first');
                                                } else {
                                                    if ($tbl.closest('fieldset').find('.bp-add-one-row:visible:first').length) {
                                                        $addBtn = $tbl.closest('fieldset').find('.bp-add-one-row:visible:first');
                                                    } else if ($parentDiv.prev('div').find('.bp-add-one-row:visible:first').length) {
                                                        $addBtn = $parentDiv.prev('div').find('.bp-add-one-row:visible:first');
                                                    } else if ($parentDiv.closest('.theme-grid-area').find('.bp-add-one-row:visible:first').length) {
                                                        $addBtn = $parentDiv.closest('.theme-grid-area').find('.bp-add-one-row:visible:first');
                                                    }
                                                }
                                            }
                                            if ($addBtn) {
                                                if (navigator.userAgent.toLowerCase().indexOf('firefox') > -1) {
                                                    $this.trigger('change');
                                                }
                                                $.when(
                                                    $addBtn.trigger('click') 
                                                ).done(function() {
                                                    $tbl.promise().done(function() {
                                                        $thisRow.next('tr').find('input:not([data-isdisabled], [readonly="readonly"], [readonly], readonly, [disabled="disabled"], [disabled], disabled, input.meta-name-autocomplete, input.meta-autocomplete[value!=""]):visible:first').focus().select();
                                                    });
                                                });
                                            } else {
                                                var $nextInput = $this.closest('td').next('td:visible').find('input:visible:first, textarea:visible:first');
                                                if ($nextInput.length) {
                                                    $nextInput.focus().select();
                                                } else {
                                                    $this.trigger('change');
                                                }
                                            }
                                        }
                                    }
                                } else {

                                    if ($this.hasClass('meta-name-autocomplete')) {
                                        var $thisRow = $this.closest('tr');
                                        $tblInput = $tbl.find('tbody:eq(0) > tr > td:visible input:not([data-isdisabled], [readonly="readonly"], [readonly], readonly, [disabled="disabled"], [disabled], disabled, input.meta-autocomplete[value!=""]):visible');
                                        $cellIndex = $tblInput.index($this);
                                        if (typeof $tblInput[$cellIndex + 1] !== 'undefined') {
                                            $tblInput.eq($cellIndex + 1).focus().select();
                                        }
                                    } else {

                                        var $parentScrollDiv = $tbl.closest('.bp-overflow-xy-auto');
                                        var $nextInput = $tblInput.eq($cellIndex + 1);

                                        if ($parentScrollDiv.length) {
                                            var scrollHeight = $parentScrollDiv[0].scrollHeight;
                                            var clientHeight = $parentScrollDiv[0].clientHeight;

                                            var $nextRow = $nextInput.closest('tr');

                                            if (scrollHeight !== clientHeight) {
                                                var nextRowTop = $nextRow.offset().top;
                                                var parentScrollTop = $parentScrollDiv.scrollTop();

                                                if (prevNextRowTop && nextRowTop > prevNextRowTop) {
                                                    $parentScrollDiv.scrollTop(parentScrollTop + $nextRow.height());
                                                    if ($nextRow.index() > 30) {
                                                        $(window).scrollTop($(window).scrollTop() + $nextRow.height());
                                                    }
                                                }
                                                prevNextRowTop = nextRowTop;
                                            }
                                        }

                                        $nextInput.focus().select();
                                    }
                                }
                            }
                        }

                        e.preventDefault();

                    } else if (keyCode === 38) { // up

                        if ($('.ui-autocomplete.ui-widget:visible').length == 0) {
                            var $dtlTbl = $this.closest('table');

                            if ($dtlTbl.hasClass('bprocess-table-dtl')) {
                                var $rowCell = $this.closest('td'); 
                                var $row = $this.closest('tr');
                                var $prevRow = $row.prev('tr:visible');
                                var $colIndex = $rowCell.index();

                                if ($prevRow.length) {

                                    var $parentScrollDiv = $dtlTbl.closest('.bp-overflow-xy-auto');
                                    var scrollHeight = $parentScrollDiv[0].scrollHeight;
                                    var clientHeight = $parentScrollDiv[0].clientHeight;

                                    if (scrollHeight !== clientHeight) {
                                        var parentScrollTop = $parentScrollDiv.scrollTop();

                                        if (parentScrollTop > 0) {
                                            var prevRowTop = $prevRow.offset().top;
                                            $parentScrollDiv.scrollTop(parentScrollTop - 19);
                                        }
                                    }

                                    $prevRow.find('td:eq('+$colIndex+') input:not(:hidden):first').focus().select();

                                } else if (typeof $dtlTbl.attr('data-pager') !== 'undefined' && $dtlTbl.attr('data-pager') == 'true') {
                                    $this.trigger('change');
                                    bpDetailPagerPrevTrigger($dtlTbl);
                                }
                            }

                            return e.preventDefault();
                        }
                    } else if (keyCode === 40) { // down

                        if ($('.ui-autocomplete.ui-widget:visible').length == 0) {
                            var $dtlTbl = $this.closest('table');

                            if ($dtlTbl.hasClass('bprocess-table-dtl')) {

                                var $rowCell = $this.closest('td'); 
                                var $row = $this.closest('tr');
                                var $nextRow = $row.next('tr:visible');
                                var $colIndex = $rowCell.index();

                                if ($nextRow.length) {

                                    var $parentScrollDiv = $dtlTbl.closest('.bp-overflow-xy-auto');
                                    var scrollHeight = $parentScrollDiv[0].scrollHeight;
                                    var clientHeight = $parentScrollDiv[0].clientHeight;

                                    if (scrollHeight !== clientHeight) {
                                        var nextRowTop = $nextRow.offset().top;
                                        var parentScrollTop = $parentScrollDiv.scrollTop();

                                        if (prevNextRowTop && nextRowTop > prevNextRowTop) {
                                            $parentScrollDiv.scrollTop(parentScrollTop + $nextRow.height() + 25);
                                        }
                                        prevNextRowTop = nextRowTop;
                                    }

                                    $nextRow.find('td:eq('+$colIndex+') input:not(:hidden):first').focus().select();

                                } else if (typeof $dtlTbl.attr('data-pager') !== 'undefined' && $dtlTbl.attr('data-pager') == 'true') {
                                    $this.trigger('change');
                                    bpDetailPagerNextTrigger($dtlTbl);
                                }
                            }

                            return e.preventDefault();
                        }
                    } else if (e.shiftKey && keyCode == 107) { // shift++

                        var $dtlTbl = $this.closest('table');

                        if ($dtlTbl.hasClass('bprocess-table-dtl')) {
                            var $groupPath = $dtlTbl.attr('data-table-path');
                            $("button.bp-add-one-row[data-action-path='"+$groupPath+"']:visible", bp_window_<?php echo $this->methodId; ?>).trigger('click');
                        }
                        return e.preventDefault();

                    } else if (e.shiftKey && keyCode === 13) { // shift+enter

                        if ($this.closest('div.bp-tab-table').length) {
                            var $tbl = $this.closest('div.bp-tab-table');
                            var $tblInput = $tbl.find('input:visible:not([data-isdisabled], [readonly="readonly"], [readonly], readonly, [disabled="disabled"], [disabled], disabled, input.meta-name-autocomplete, .select2-focusser, input.meta-autocomplete[value!=""])');
                            var $cellIndex = $tblInput.index($this);

                            if ($tblInput.length == ($cellIndex - 1)) {
                                var $tableIndex = $("div.bp-tab-table", bp_window_<?php echo $this->methodId; ?>).index($tbl) - 1;
                                var $tablePrev = $("div.bp-tab-table", bp_window_<?php echo $this->methodId; ?>).eq($tableIndex);
                                if ($tablePrev.find('.bp-tab-table-control').length > 0) {
                                    $tablePrev.find('.bp-tab-table-control:visible input:first:visible:not([data-isdisabled], [readonly="readonly"], [readonly], readonly, [disabled="disabled"], [disabled], disabled, input.meta-name-autocomplete, input.meta-autocomplete[value!=""])').focus().select();
                                } 
                            } else {
                                if ($this.hasClass('meta-name-autocomplete')) {
                                    var $thisRow = $this.closest('.bp-tab-table-cell');

                                    if ($thisRow.prev('.bp-tab-table-cell').length > 0) {
                                        $thisRow.prev('.bp-tab-table-cell').find('input:first:visible:not([data-isdisabled], [readonly="readonly"], [readonly], readonly, [disabled="disabled"], [disabled], disabled, input.meta-name-autocomplete, input.meta-autocomplete[value!=""])').focus().select();
                                    } else {
                                        $tblInput = $tbl.find('input:visible:not([data-isdisabled], [readonly="readonly"], [readonly], readonly, [disabled="disabled"], [disabled], disabled, input.meta-name-autocomplete, input.meta-autocomplete[value!=""])');
                                        $cellIndex = $tblInput.index($this);
                                        if (typeof $tblInput[$cellIndex - 1] !== 'undefined') {
                                            $tblInput.eq($cellIndex - 1).focus().select();
                                        }
                                    }
                                } else {
                                    $tblInput.eq($cellIndex - 1).focus().select();
                                }
                            }

                            e.preventDefault();
                            return false;

                        } else {

                            var $tbl = $this.closest('table');
                            var $parentDiv = $tbl.parent('div');
                            var $tblInput = $tbl.find('tbody:eq(0) > tr > td:visible input:not([data-isdisabled], [readonly="readonly"], [readonly], readonly, [disabled="disabled"], [disabled], disabled, input.meta-name-autocomplete, .select2-focusser, input.meta-autocomplete[value!=""]):visible');
                            var $cellIndex = $tblInput.index($this);

                            if ($tblInput.length == ($cellIndex - 1)) {
                                var $headerTbl = $parentDiv.hasClass('bp-header-param');

                                if ($headerTbl) {
                                    var $tableIndex = $("table.table", bp_window_<?php echo $this->methodId; ?>).index($tbl) - 1;
                                    var $tablePrev = $("table.table", bp_window_<?php echo $this->methodId; ?>).eq($tableIndex);
                                    if ($tablePrev.find('tbody > tr').length > 0) {
                                        $tablePrev.find('tbody > tr > td:visible input:not([data-isdisabled], [readonly="readonly"], [readonly], readonly, [disabled="disabled"], [disabled], disabled, input.meta-name-autocomplete, input.meta-autocomplete[value!=""]):visible:first').focus().select();
                                    } 
                                } else {
                                    var $thisRow = $this.closest('tr');

                                    if ($thisRow.prev('tr').length > 0) {
                                        $thisRow.prev('tr').find('input:not([data-isdisabled], [readonly="readonly"], [readonly], readonly, [disabled="disabled"], [disabled], disabled, input.meta-name-autocomplete, input.meta-autocomplete[value!=""]):visible:first').focus().select();
                                    } else {
                                        if (typeof $tbl.attr('data-pager') !== 'undefined' && $tbl.attr('data-pager') === 'true' && bpDetailPagerIsPrevButtonActive($tbl)) {
                                            bpDetailPagerPrevTrigger($tbl);
                                            return;
                                        } 
                                    }
                                }
                            } else {
                                if ($this.hasClass('meta-name-autocomplete')) {
                                    var $thisRow = $this.closest('tr');

                                    if ($thisRow.prev('tr').length > 0) {
                                        $thisRow.prev('tr').find('input:not([data-isdisabled], [readonly="readonly"], [readonly], readonly, [disabled="disabled"], [disabled], disabled, input.meta-name-autocomplete, input.meta-autocomplete[value!=""]):visible:first').focus().select();
                                    } else {
                                        $tblInput = $tbl.find('tbody:eq(0) > tr > td:visible input:not([data-isdisabled], [readonly="readonly"], [readonly], readonly, [disabled="disabled"], [disabled], disabled, input.meta-name-autocomplete, input.meta-autocomplete[value!=""]):visible');
                                        $cellIndex = $tblInput.index($this);
                                        if (typeof $tblInput[$cellIndex - 1] !== 'undefined') {
                                            $tblInput.eq($cellIndex - 1).focus().select();
                                        }
                                    }
                                } else {
                                    $tblInput.eq($cellIndex - 1).focus().select();
                                }
                            }
                        }

                        e.preventDefault();
                    }
                
                } else {
                    
                    if (!e.shiftKey && keyCode === 13) { // enter 

                        if ($this.hasClass('meta-autocomplete')) {
                            if ($this.closest('div.bp-tab-table').length) {
                                var $tbl = $this.closest('div.bp-tab-table');
                                var $tblInput = $tbl.find('input:visible:not([data-isdisabled], [readonly="readonly"], [readonly], readonly, [disabled="disabled"], [disabled], disabled, input.meta-name-autocomplete, .select2-focusser)');
                                var $cellIndex = $tblInput.index($this);

                                if ($tblInput.length == ($cellIndex + 1)) {
                                    var $tableIndex = $("div.bp-tab-table", bp_window_<?php echo $this->methodId; ?>).index($tbl) + 1;
                                    var $tableNext = $("div.bp-tab-table", bp_window_<?php echo $this->methodId; ?>).eq($tableIndex);
                                    if ($tableNext.find('.bp-tab-table-control').length > 0) {
                                        $tableNext.find('.bp-tab-table-control:visible input:first:visible:not([data-isdisabled], [readonly="readonly"], [readonly], readonly, [disabled="disabled"], [disabled], disabled, input.meta-name-autocomplete)').focus().select();
                                    } 
                                } else {
                                    if ($this.hasClass('meta-name-autocomplete')) {
                                        var $thisRow = $this.closest('.bp-tab-table-cell');

                                        if ($thisRow.next('.bp-tab-table-cell').length > 0) {
                                            $thisRow.next('.bp-tab-table-cell').find('input:first:visible:not([data-isdisabled], [readonly="readonly"], [readonly], readonly, [disabled="disabled"], [disabled], disabled, input.meta-name-autocomplete)').focus().select();
                                        } else {
                                            $tblInput = $tbl.find('input:visible:not([data-isdisabled], [readonly="readonly"], [readonly], readonly, [disabled="disabled"], [disabled], disabled)');
                                            $cellIndex = $tblInput.index($this);
                                            if (typeof $tblInput[$cellIndex + 1] !== 'undefined') {
                                                $tblInput.eq($cellIndex + 1).focus().select();
                                            }
                                        }
                                    } else {
                                        $tblInput.eq($cellIndex + 1).focus().select();
                                    }
                                }

                            } else {
                                var $tbl = $this.closest('table');
                                var $parentDiv = $tbl.parent('div');
                                var $tblInput = $tbl.find('tbody:eq(0) > tr > td:visible input:not([data-isdisabled], [readonly="readonly"], [readonly], readonly, [disabled="disabled"], [disabled], disabled, input.meta-name-autocomplete, .select2-focusser):visible');
                                var $cellIndex = $tblInput.index($this);

                                if ($this.is('[readonly]')) { 
                                    $cellIndex = $cellIndex - 1;
                                }

                                if ($tblInput.length == ($cellIndex + 1)) {
                                    var $headerTbl = $parentDiv.hasClass('bp-header-param');

                                    if ($headerTbl) {
                                        var $tableIndex = $("table.table", bp_window_<?php echo $this->methodId; ?>).index($tbl) + 1;
                                        var $tableNext = $("table.table", bp_window_<?php echo $this->methodId; ?>).eq($tableIndex);
                                        if ($tableNext.find('tbody > tr').length > 0) {
                                            $tableNext.find('tbody > tr > td:visible input:not([data-isdisabled], [readonly="readonly"], [readonly], readonly, [disabled="disabled"], [disabled], disabled, input.meta-name-autocomplete):visible:first').focus().select();
                                        } 
                                    } else {
                                        var $thisRow = $this.closest('tr');

                                        if ($thisRow.next('tr').length > 0) {
                                            $thisRow.next('tr').find('input:not([data-isdisabled], [readonly="readonly"], [readonly], readonly, [disabled="disabled"], [disabled], disabled, input.meta-name-autocomplete):visible:first').focus().select();
                                        } else {

                                            if (typeof $tbl.attr('data-pager') !== 'undefined' && $tbl.attr('data-pager') === 'true' && bpDetailPagerIsNextButtonActive($tbl)) {
                                                bpDetailPagerNextTrigger($tbl);
                                                return;
                                            } 

                                            var $addBtn = '';
                                            if (!$tbl.hasAttr('data-disable-enter-addrow')) {
                                                if ($parentDiv.hasClass('param-tree-container')) {
                                                    $addBtn = $parentDiv.children('.btn:visible:first');
                                                } else {
                                                    if ($tbl.closest('fieldset').find('.bp-add-one-row:visible:first').length) {
                                                        $addBtn = $tbl.closest('fieldset').find('.bp-add-one-row:visible:first');
                                                    } else if ($parentDiv.prev('div').find('.bp-add-one-row:visible:first').length) {
                                                        $addBtn = $parentDiv.prev('div').find('.bp-add-one-row:visible:first');
                                                    } else if ($parentDiv.closest('.theme-grid-area').find('.bp-add-one-row:visible:first').length) {
                                                        $addBtn = $parentDiv.closest('.theme-grid-area').find('.bp-add-one-row:visible:first');
                                                    }
                                                }
                                            }
                                            if ($addBtn) {
                                                if (navigator.userAgent.toLowerCase().indexOf('firefox') > -1) {
                                                    $this.trigger('change');
                                                }
                                                $.when(
                                                    $addBtn.trigger('click')
                                                ).done(function() {
                                                    $tbl.promise().done(function() {
                                                        $thisRow.next('tr').find('input:not([data-isdisabled], [readonly="readonly"], [readonly], readonly, [disabled="disabled"], [disabled], disabled, input.meta-name-autocomplete):visible:first').focus().select();
                                                    });
                                                });
                                            }
                                        }
                                    }
                                } else {
                                    if ($this.hasClass('meta-name-autocomplete')) {
                                        var $thisRow = $this.closest('tr');

                                        if ($thisRow.next('tr').length > 0) {
                                            $thisRow.next('tr').find('input:not([data-isdisabled], [readonly="readonly"], [readonly], readonly, [disabled="disabled"], [disabled], disabled, input.meta-name-autocomplete):visible:first').focus().select();
                                        } else {
                                            $tblInput = $tbl.find('tbody:eq(0) > tr > td:visible input:not([data-isdisabled], [readonly="readonly"], [readonly], readonly, [disabled="disabled"], [disabled], disabled, input.meta-name-autocomplete):visible');
                                            $cellIndex = $tblInput.index($this);
                                            if (typeof $tblInput[$cellIndex + 1] !== 'undefined') {
                                                $tblInput.eq($cellIndex + 1).focus().select();
                                            }
                                        }
                                    } else {
                                        $tblInput.eq($cellIndex + 1).focus().select();
                                    }
                                }
                            }

                        } else {
                            if ($this.closest('div.bp-tab-table').length) {
                                var $tbl = $this.closest('div.bp-tab-table');
                                var $tblInput = $tbl.find('input:visible:not([data-isdisabled], [readonly="readonly"], [readonly], readonly, [disabled="disabled"], [disabled], disabled, input.meta-name-autocomplete, .select2-focusser, input.meta-autocomplete[value!=""])');
                                var $cellIndex = $tblInput.index($this);

                                if ($tblInput.length == ($cellIndex + 1)) {
                                    var $tableIndex = $("div.bp-tab-table", bp_window_<?php echo $this->methodId; ?>).index($tbl) + 1;
                                    var $tableNext = $("div.bp-tab-table", bp_window_<?php echo $this->methodId; ?>).eq($tableIndex);
                                    if ($tableNext.find('.bp-tab-table-control').length > 0) {
                                        $tableNext.find('.bp-tab-table-control:visible input:first:visible:not([data-isdisabled], [readonly="readonly"], [readonly], readonly, [disabled="disabled"], [disabled], disabled, input.meta-name-autocomplete, input.meta-autocomplete[value!=""])').focus().select();
                                    } 
                                } else {
                                    if ($this.hasClass('meta-name-autocomplete')) {
                                        var $thisRow = $this.closest('.bp-tab-table-cell');

                                        if ($thisRow.next('.bp-tab-table-cell').length > 0) {
                                            $thisRow.next('.bp-tab-table-cell').find('input:first:visible:not([data-isdisabled], [readonly="readonly"], [readonly], readonly, [disabled="disabled"], [disabled], disabled, input.meta-autocomplete[value!=""])').focus().select();
                                        } else {
                                            $tblInput = $tbl.find('input:visible:not([data-isdisabled], [readonly="readonly"], [readonly], readonly, [disabled="disabled"], [disabled], disabled, input.meta-autocomplete[value!=""])');
                                            $cellIndex = $tblInput.index($this);
                                            if (typeof $tblInput[$cellIndex + 1] !== 'undefined') {
                                                $tblInput.eq($cellIndex + 1).focus().select();
                                            }
                                        }
                                    } else {
                                        $tblInput.eq($cellIndex + 1).focus().select();
                                    }
                                }
                            } else {
                                var $tbl = $this.closest('table');
                                var $parentDiv = $tbl.parent('div');
                                var $tblInput = $tbl.find('tbody:eq(0) > tr > td:visible input:not([data-isdisabled], [readonly="readonly"], [readonly], readonly, [disabled="disabled"], [disabled], disabled, input.meta-name-autocomplete, input.meta-autocomplete[value!=""], .select2-focusser):visible');
                                var $cellIndex = $tblInput.index($this), $cellIndex = $cellIndex > 0 ? $cellIndex : 0;

                                if ($this.is('[readonly]')) { 
                                    $cellIndex = $cellIndex - 1;
                                }

                                if ($tblInput.length == ($cellIndex + 1)) {

                                    var $headerTbl = $parentDiv.hasClass('bp-header-param');

                                    if ($headerTbl) {
                                        var $tableIndex = $("table.table", bp_window_<?php echo $this->methodId; ?>).index($tbl) + 1;
                                        var $tableNext = $("table.table", bp_window_<?php echo $this->methodId; ?>).eq($tableIndex);
                                        if ($tableNext.find('tbody > tr').length > 0) {
                                            $tableNext.find('tbody > tr > td:visible input:not([data-isdisabled], [readonly="readonly"], [readonly], readonly, [disabled="disabled"], [disabled], disabled, input.meta-name-autocomplete, input.meta-autocomplete[value!=""]):visible:first').focus().select();
                                        } 
                                    } else {
                                        var $thisRow = $this.closest('tr');
                                        var $nextRow = $thisRow.next('tr');

                                        if ($nextRow.length) {

                                            var $parentScrollDiv = $tbl.closest('.bp-overflow-xy-auto');
                                            var scrollHeight = $parentScrollDiv[0].scrollHeight;
                                            var clientHeight = $parentScrollDiv[0].clientHeight;

                                            if (scrollHeight !== clientHeight) {
                                                var nextRowTop = $nextRow.offset().top;
                                                var parentScrollTop = $parentScrollDiv.scrollTop();
                                                
                                                if (prevNextRowTop && nextRowTop > prevNextRowTop) {
                                                    $parentScrollDiv.scrollTop(parentScrollTop + $nextRow.height() + 25);
                                                }
                                                prevNextRowTop = nextRowTop;                                                
                                            }

                                            $nextRow.find('input:not([data-isdisabled], [readonly="readonly"], [readonly], readonly, [disabled="disabled"], [disabled], disabled, input.meta-name-autocomplete, input.meta-autocomplete[value!=""]):visible:first').focus().select();

                                        } else {

                                            if (typeof $tbl.attr('data-pager') !== 'undefined' && $tbl.attr('data-pager') === 'true' && bpDetailPagerIsNextButtonActive($tbl)) {
                                                bpDetailPagerNextTrigger($tbl);
                                                return;
                                            } 

                                            var $addBtn = '';
                                            if (!$tbl.hasAttr('data-disable-enter-addrow')) {
                                                if ($parentDiv.hasClass('param-tree-container')) {
                                                    $addBtn = $parentDiv.children('.btn:visible:first');
                                                } else {
                                                    if ($tbl.closest('fieldset').find('.bp-add-one-row:visible:first').length) {
                                                        $addBtn = $tbl.closest('fieldset').find('.bp-add-one-row:visible:first');
                                                    } else if ($parentDiv.prev('div').find('.bp-add-one-row:visible:first').length) {
                                                        $addBtn = $parentDiv.prev('div').find('.bp-add-one-row:visible:first');
                                                    } else if ($parentDiv.closest('.theme-grid-area').find('.bp-add-one-row:visible:first').length) {
                                                        $addBtn = $parentDiv.closest('.theme-grid-area').find('.bp-add-one-row:visible:first');
                                                    }
                                                }
                                            }
                                            if ($addBtn) {
                                                if (navigator.userAgent.toLowerCase().indexOf('firefox') > -1) {
                                                    $this.trigger('change');
                                                }
                                                $.when(
                                                    $addBtn.trigger('click')
                                                ).done(function() {
                                                    $tbl.promise().done(function() {
                                                        $thisRow.next('tr').find('input:not([data-isdisabled], [readonly="readonly"], [readonly], readonly, [disabled="disabled"], [disabled], disabled, input.meta-name-autocomplete, input.meta-autocomplete[value!=""]):visible:first').focus().select();
                                                    });
                                                });
                                            }
                                        }
                                    }
                                } else {

                                    if ($this.hasClass('meta-name-autocomplete')) {
                                        var $thisRow = $this.closest('tr');
                                        $tblInput = $tbl.find('tbody:eq(0) > tr > td:visible input:not([data-isdisabled], [readonly="readonly"], [readonly], readonly, [disabled="disabled"], [disabled], disabled, input.meta-autocomplete[value!=""]):visible');
                                        $cellIndex = $tblInput.index($this);
                                        if (typeof $tblInput[$cellIndex + 1] !== 'undefined') {
                                            $tblInput.eq($cellIndex + 1).focus().select();
                                        }
                                    } else {

                                        var $parentScrollDiv = $tbl.closest('.bp-overflow-xy-auto');
                                        var $nextInput = $tblInput.eq($cellIndex + 1);

                                        if ($parentScrollDiv.length) {
                                            var scrollHeight = $parentScrollDiv[0].scrollHeight;
                                            var clientHeight = $parentScrollDiv[0].clientHeight;

                                            var $nextRow = $nextInput.closest('tr');

                                            if (scrollHeight !== clientHeight) {
                                                var nextRowTop = $nextRow.offset().top;
                                                var parentScrollTop = $parentScrollDiv.scrollTop();

                                                if (prevNextRowTop && nextRowTop > prevNextRowTop) {
                                                    $parentScrollDiv.scrollTop(parentScrollTop + $nextRow.height() + 25);
                                                }
                                                prevNextRowTop = nextRowTop;
                                            }
                                        }

                                        $nextInput.focus().select();
                                    }
                                }
                            }
                        }

                        e.preventDefault();

                    } else if (keyCode === 38) { // up

                        if ($('.ui-autocomplete.ui-widget:visible').length == 0) {
                            var $dtlTbl = $this.closest('table');

                            if ($dtlTbl.hasClass('bprocess-table-dtl')) {
                                var $rowCell = $this.closest('td'); 
                                var $row = $this.closest('tr');
                                var $prevRow = $row.prev('tr:visible');
                                var $colIndex = $rowCell.index();

                                if ($prevRow.length) {

                                    var $parentScrollDiv = $dtlTbl.closest('.bp-overflow-xy-auto');
                                    var scrollHeight = $parentScrollDiv[0].scrollHeight;
                                    var clientHeight = $parentScrollDiv[0].clientHeight;

                                    if (scrollHeight !== clientHeight) {
                                        var parentScrollTop = $parentScrollDiv.scrollTop();

                                        if (parentScrollTop > 0) {
                                            var prevRowTop = $prevRow.offset().top;
                                            $parentScrollDiv.scrollTop(parentScrollTop - 19);
                                        }
                                    }

                                    $prevRow.find('td:eq('+$colIndex+') input:not(:hidden):first').focus().select();

                                } else if (typeof $dtlTbl.attr('data-pager') !== 'undefined' && $dtlTbl.attr('data-pager') == 'true') {
                                    $this.trigger('change');
                                    bpDetailPagerPrevTrigger($dtlTbl);
                                }
                            }

                            return e.preventDefault();
                        }
                    } else if (keyCode === 40) { // down

                        if ($('.ui-autocomplete.ui-widget:visible').length == 0) {
                            var $dtlTbl = $this.closest('table');

                            if ($dtlTbl.hasClass('bprocess-table-dtl')) {

                                var $rowCell = $this.closest('td'); 
                                var $row = $this.closest('tr');
                                var $nextRow = $row.next('tr:visible');
                                var $colIndex = $rowCell.index();

                                if ($nextRow.length) {

                                    var $parentScrollDiv = $dtlTbl.closest('.bp-overflow-xy-auto');
                                    var scrollHeight = $parentScrollDiv[0].scrollHeight;
                                    var clientHeight = $parentScrollDiv[0].clientHeight;

                                    if (scrollHeight !== clientHeight) {
                                        var nextRowTop = $nextRow.offset().top;
                                        var parentScrollTop = $parentScrollDiv.scrollTop();
                                        
                                        if (prevNextRowTop && nextRowTop > prevNextRowTop) {
                                            $parentScrollDiv.scrollTop(parentScrollTop + $nextRow.height() +25);
                                        }
                                        prevNextRowTop = nextRowTop;
                                    }

                                    $nextRow.find('td:eq('+$colIndex+') input:not(:hidden):first').focus().select();

                                } else if (typeof $dtlTbl.attr('data-pager') !== 'undefined' && $dtlTbl.attr('data-pager') == 'true') {
                                    $this.trigger('change');
                                    bpDetailPagerNextTrigger($dtlTbl);
                                }
                            }

                            return e.preventDefault();
                        }
                    } else if (e.shiftKey && keyCode == 107) { // shift++

                        var $dtlTbl = $this.closest('table');

                        if ($dtlTbl.hasClass('bprocess-table-dtl')) {
                            var $groupPath = $dtlTbl.attr('data-table-path');
                            $("button.bp-add-one-row[data-action-path='"+$groupPath+"']:visible", bp_window_<?php echo $this->methodId; ?>).trigger('click');
                        }
                        return e.preventDefault();

                    } else if (e.shiftKey && keyCode === 13) { // shift+enter

                        if ($this.closest('div.bp-tab-table').length) {
                            var $tbl = $this.closest('div.bp-tab-table');
                            var $tblInput = $tbl.find('input:visible:not([data-isdisabled], [readonly="readonly"], [readonly], readonly, [disabled="disabled"], [disabled], disabled, input.meta-name-autocomplete, .select2-focusser, input.meta-autocomplete[value!=""])');
                            var $cellIndex = $tblInput.index($this);

                            if ($tblInput.length == ($cellIndex - 1)) {
                                var $tableIndex = $("div.bp-tab-table", bp_window_<?php echo $this->methodId; ?>).index($tbl) - 1;
                                var $tablePrev = $("div.bp-tab-table", bp_window_<?php echo $this->methodId; ?>).eq($tableIndex);
                                if ($tablePrev.find('.bp-tab-table-control').length > 0) {
                                    $tablePrev.find('.bp-tab-table-control:visible input:first:visible:not([data-isdisabled], [readonly="readonly"], [readonly], readonly, [disabled="disabled"], [disabled], disabled, input.meta-name-autocomplete, input.meta-autocomplete[value!=""])').focus().select();
                                } 
                            } else {
                                if ($this.hasClass('meta-name-autocomplete')) {
                                    var $thisRow = $this.closest('.bp-tab-table-cell');

                                    if ($thisRow.prev('.bp-tab-table-cell').length > 0) {
                                        $thisRow.prev('.bp-tab-table-cell').find('input:first:visible:not([data-isdisabled], [readonly="readonly"], [readonly], readonly, [disabled="disabled"], [disabled], disabled, input.meta-name-autocomplete, input.meta-autocomplete[value!=""])').focus().select();
                                    } else {
                                        $tblInput = $tbl.find('input:visible:not([data-isdisabled], [readonly="readonly"], [readonly], readonly, [disabled="disabled"], [disabled], disabled, input.meta-name-autocomplete, input.meta-autocomplete[value!=""])');
                                        $cellIndex = $tblInput.index($this);
                                        if (typeof $tblInput[$cellIndex - 1] !== 'undefined') {
                                            $tblInput.eq($cellIndex - 1).focus().select();
                                        }
                                    }
                                } else {
                                    $tblInput.eq($cellIndex - 1).focus().select();
                                }
                            }

                            e.preventDefault();
                            return false;

                        } else {

                            var $tbl = $this.closest('table');
                            var $parentDiv = $tbl.parent('div');
                            var $tblInput = $tbl.find('tbody:eq(0) > tr > td:visible input:not([data-isdisabled], [readonly="readonly"], [readonly], readonly, [disabled="disabled"], [disabled], disabled, input.meta-name-autocomplete, .select2-focusser, input.meta-autocomplete[value!=""]):visible');
                            var $cellIndex = $tblInput.index($this);

                            if ($tblInput.length == ($cellIndex - 1)) {
                                var $headerTbl = $parentDiv.hasClass('bp-header-param');

                                if ($headerTbl) {
                                    var $tableIndex = $("table.table", bp_window_<?php echo $this->methodId; ?>).index($tbl) - 1;
                                    var $tablePrev = $("table.table", bp_window_<?php echo $this->methodId; ?>).eq($tableIndex);
                                    if ($tablePrev.find('tbody > tr').length > 0) {
                                        $tablePrev.find('tbody > tr > td:visible input:not([data-isdisabled], [readonly="readonly"], [readonly], readonly, [disabled="disabled"], [disabled], disabled, input.meta-name-autocomplete, input.meta-autocomplete[value!=""]):visible:first').focus().select();
                                    } 
                                } else {
                                    var $thisRow = $this.closest('tr');

                                    if ($thisRow.prev('tr').length > 0) {
                                        $thisRow.prev('tr').find('input:not([data-isdisabled], [readonly="readonly"], [readonly], readonly, [disabled="disabled"], [disabled], disabled, input.meta-name-autocomplete, input.meta-autocomplete[value!=""]):visible:first').focus().select();
                                    } else {
                                        if (typeof $tbl.attr('data-pager') !== 'undefined' && $tbl.attr('data-pager') === 'true' && bpDetailPagerIsPrevButtonActive($tbl)) {
                                            bpDetailPagerPrevTrigger($tbl);
                                            return;
                                        } 
                                    }
                                }
                            } else {
                                if ($this.hasClass('meta-name-autocomplete')) {
                                    var $thisRow = $this.closest('tr');

                                    if ($thisRow.prev('tr').length > 0) {
                                        $thisRow.prev('tr').find('input:not([data-isdisabled], [readonly="readonly"], [readonly], readonly, [disabled="disabled"], [disabled], disabled, input.meta-name-autocomplete, input.meta-autocomplete[value!=""]):visible:first').focus().select();
                                    } else {
                                        $tblInput = $tbl.find('tbody:eq(0) > tr > td:visible input:not([data-isdisabled], [readonly="readonly"], [readonly], readonly, [disabled="disabled"], [disabled], disabled, input.meta-name-autocomplete, input.meta-autocomplete[value!=""]):visible');
                                        $cellIndex = $tblInput.index($this);
                                        if (typeof $tblInput[$cellIndex - 1] !== 'undefined') {
                                            $tblInput.eq($cellIndex - 1).focus().select();
                                        }
                                    }
                                } else {
                                    $tblInput.eq($cellIndex - 1).focus().select();
                                }
                            }
                        }

                        e.preventDefault();

                    } else if (keyCode === 9 && !e.shiftKey) {

                        var $tbl = $this.closest('table');

                        if ($tbl.hasClass('bp-header-param')) {

                            var $row = $this.closest('tr');
                            var $cell = $this.closest('td');
                            var colIndex = $cell.index();
                            var $nextRow = $row.nextAll('tr:visible').has('> td:eq('+colIndex+') input:not([data-isdisabled], [readonly="readonly"], [readonly], readonly, [disabled="disabled"], [disabled], disabled, input.meta-name-autocomplete):visible, textarea:visible').first();
                            
                            if ($nextRow.length) {
      
                                var $focusInput = $nextRow.find('td:eq('+colIndex+') input:not([data-isdisabled], [readonly="readonly"], [readonly], readonly, [disabled="disabled"], [disabled], disabled, input.meta-name-autocomplete, input.meta-autocomplete[value!=""]):visible:first');
                                
                                if ($focusInput.length) {
                                    $focusInput.focus().select();
                                    return e.preventDefault();
                                }
                            } 
                            
                            var $secondRow = $tbl.find('tr:has(input:not([data-isdisabled], [readonly="readonly"], [readonly], readonly, [disabled="disabled"], [disabled], disabled, input.meta-name-autocomplete, .select2-focusser):visible, select:visible, textarea:visible):visible:first');
                            
                            if ($secondRow.length) {
                                colIndex = colIndex + 2;
                                var $focusInput = $secondRow.find('td:eq('+colIndex+') input:not([data-isdisabled], [readonly="readonly"], [readonly], readonly, [disabled="disabled"], [disabled], disabled, input.meta-name-autocomplete, input.meta-autocomplete[value!=""]):visible:first');
                                
                                if ($focusInput.length) {
                                    $focusInput.focus().select();
                                    return e.preventDefault();
                                }
                            }
                        }
                    }
                }
            
            } catch(err) {
                console.log('Input keydown listener error: ' + err);
            }            
        });
        bp_window_<?php echo $this->methodId; ?>.on('change', ".bprocess-table-dtl > .tbody > .bp-detail-row input[type='text']:visible", function(e){
            var $this = $(this);
            if (typeof $this.attr('data-prevent-change') !== 'undefined') {
                return;
            }
            dtlAggregateFunction_<?php echo $this->methodId; ?>();
        });        
        
        bpDetailFreezeAll(bp_window_<?php echo $this->methodId; ?>);
        /*bpOnlyShowInputFieldCount(bp_window_<?php echo $this->methodId; ?>);*/
        
        Core.initTextareaAutoHeight(bp_window_<?php echo $this->methodId; ?>);
        
        /*bpScriptLoadEnd*/
    });
    
    function bpFullScriptsWithoutEvent_<?php echo $this->methodId; ?>(elem, groupPath, isAddMulti, isLastRow, multiMode) {
        var element = typeof elem === 'undefined' ? 'open' : elem; 
        var groupPath = typeof groupPath === 'undefined' ? '' : groupPath; 
        var isAddMulti = typeof isAddMulti === 'undefined' ? false : isAddMulti; 
        var isLastRow = typeof isLastRow === 'undefined' ? false : isLastRow; 
        var multiMode = typeof multiMode === 'undefined' ? '' : multiMode; 
        
        <?php echo $this->bpFullScriptsWithoutEvent; ?>
    }
    
    <?php
    if ($isDtlTbl) {
    ?>
    function bpAddMainRow_<?php echo $this->methodId; ?>(elem, processId, rowId) {
        var $this = $(elem), $parent;
        
        if ($this.closest('div.theme-grid').length === 0) {
            $parent = $this.closest('fieldset');
            if ($parent.length === 0) {
                $parent = $this.closest('div[data-section-path]');
            } 
        } else {
            $parent = $this.closest('div.theme-grid');
        }
        
        var $getTable = $parent.find('.bprocess-table-dtl:eq(0)');
        var $getTableBody = $getTable.find('> .tbody');
        var $groupPath = $getTable.attr('data-table-path');
        var addRowNum = $this.prev('input.bp-add-one-row-num');
        
        var postData = {
            processId: processId, 
            uniqId: <?php echo $this->uniqId; ?>, 
            rowId: rowId, 
            isEditMode: isEditMode_<?php echo $this->methodId; ?>
        };
        
        var isCache = false, isAppend = false, dataType = 'html';
        
        if (typeof $getTable.attr('data-pager') !== 'undefined' && $getTable.attr('data-pager') === 'true') {
            
            var $pagerElement = bp_window_<?php echo $this->methodId; ?>.find("div[data-pg-grouppath='" + $groupPath + "']");
            var $getTableBodyLength = $getTableBody.find('> tr').length;
            
            postData['groupPath'] = $groupPath;
            postData['pageSize'] = $pagerElement.attr('data-pg-pagesize');

            if (Number(postData['pageSize']) <= $getTableBodyLength) {
                postData['params'] = $getTableBody.find('input, select, textarea').serialize();
            } else {
                postData['append'] = '1';
                isAppend = true;
            }
            
            postData['currentPageTotal'] = $pagerElement.find('.pf-bp-pager-total span').text();
            postData['headerData'] = bp_window_<?php echo $this->methodId; ?>.find('div.bp-header-param').find('input, select').serialize();
            
            isCache = true;
            dataType = 'json';
        }
        
        if (addRowNum.length && (addRowNum.val() !== '' && addRowNum.val() !== '0')) {
            
            var addRowNumType = addRowNum.attr('data-addrowtype'); 
            var addRowNumVal = Number(addRowNum.val());
            
            if (addRowNumType == 'new' || $getTableBody.children('tr').length == 0) {
                
                $.ajax({
                    type: 'post',
                    url: 'mdcommon/renderBpDtlRow',
                    data: {processId: processId, uniqId: <?php echo $this->uniqId; ?>, rowId: rowId},
                    beforeSend: function () {
                        Core.blockUI({animate: true});
                    },
                    success: function (dataStr) {
                        
                        var dataHtmlStr = dataStr.repeat(addRowNumVal); 
                        var $html = $('<div />', {html: dataHtmlStr});
                        
                        $html.children('.bp-detail-row').addClass('added-bp-row display-none multi-added-row');

                        $getTableBody.append($html.html());
                        
                        var $rowNumEl = $getTableBody.find('> .bp-detail-row');
                        var rowNumLen = $rowNumEl.length, ni = 0;

                        for (ni; ni < rowNumLen; ni++) { 
                            $($rowNumEl[ni]).find('td:first > span').text(ni + 1);
                        }
                        
                        bpSetRowIndex($parent);
                    },
                    error: function () {
                        alert('Error');
                    }

                }).done(function () {
                    
                    var $rowEl = $getTableBody.find('> .bp-detail-row.multi-added-row');
                    var rowLen = $rowEl.length, rowi = 0, tablePath = $getTable.attr('data-table-path');

                    if (rowLen === 1) {

                        Core.initBPDtlInputType($($rowEl[rowi]));
                        bpFullScriptsWithoutEvent_<?php echo $this->methodId; ?>($($rowEl[rowi]), tablePath, true, true);

                    } else if (rowLen > 1) {

                        var rowLen = rowLen - 1;

                        for (rowi; rowi < rowLen; rowi++) { 
                            Core.initBPDtlInputType($($rowEl[rowi]));
                            bpFullScriptsWithoutEvent_<?php echo $this->methodId; ?>($($rowEl[rowi]), tablePath, true, false);
                        }

                        Core.initBPDtlInputType($($rowEl[rowLen]));
                        bpFullScriptsWithoutEvent_<?php echo $this->methodId; ?>($($rowEl[rowLen]), tablePath, true, true);
                    }

                    $rowEl.removeClass('multi-added-row display-none');
                    
                    dtlAggregateFunction_<?php echo $this->methodId; ?>();
                    enableBpDetailFilterByElement($getTable);
                    bpDetailFreeze($getTable);

                    Core.unblockUI();
                });
                
            } else {
                
                Core.blockUI({animate: true});
                        
                if (addRowNumType === 'first') {
                    var $row = $getTableBody.find('> .bp-detail-row:eq(0)');
                } else if (addRowNumType === 'last') {
                    var $row = $getTableBody.find('> .bp-detail-row:last');
                } else if (addRowNumType === 'selectedrow') {
                    if ($getTableBody.find('> .bp-detail-row.currentTarget:eq(0)').length) {
                        var $row = $getTableBody.find('> .bp-detail-row.currentTarget:eq(0)');
                    } else {
                        var $row = $getTableBody.find('> .bp-detail-row:eq(0)');
                    }
                } else {
                    var $row = $getTableBody.find("> .bp-detail-row:eq("+(addRowNumType-1)+")");
                }
                
                $row.find('select.select2').select2('destroy');
                $row.removeClass('saved-bp-row currentTarget').addClass('added-bp-row display-none multi-added-row');
                $.uniform.restore($row.find('input[type=checkbox]'));
                
                for (i = 0; i < addRowNumVal; i++) {
                    $getTableBody.append($row.clone());
                }
                
                var $rowNumEl = $getTableBody.find('> .bp-detail-row');
                var rowNumLen = $rowNumEl.length, ni = 0;

                for (ni; ni < rowNumLen; ni++) { 
                    $($rowNumEl[ni]).find('td:first > span').text(ni + 1);
                }

                bpSetRowIndex($parent);
                
                var $rowEl = $getTableBody.find('> .bp-detail-row.multi-added-row');
                var rowLen = $rowEl.length, rowi = 0, tablePath = $getTable.attr('data-table-path');

                if (rowLen === 1) {

                    Core.initBPDtlInputType($($rowEl[rowi]));
                    bpFullScriptsWithoutEvent_<?php echo $this->methodId; ?>($($rowEl[rowi]), tablePath, true, true);

                } else if (rowLen > 1) {

                    var rowLen = rowLen - 1;

                    for (rowi; rowi < rowLen; rowi++) { 
                        Core.initBPDtlInputType($($rowEl[rowi]));
                        bpFullScriptsWithoutEvent_<?php echo $this->methodId; ?>($($rowEl[rowi]), tablePath, true, false);
                    }

                    Core.initBPDtlInputType($($rowEl[rowLen]));
                    bpFullScriptsWithoutEvent_<?php echo $this->methodId; ?>($($rowEl[rowLen]), tablePath, true, true);
                }
                
                $rowEl.removeClass('multi-added-row display-none');
                
                dtlAggregateFunction_<?php echo $this->methodId; ?>();
                enableBpDetailFilterByElement($getTable);
                bpDetailFreeze($getTable);
                
                Core.unblockUI();
            }
            
            return;
        } 
            
        $.ajax({
            type: 'post',
            url: 'mdcommon/renderBpDtlRow',
            data: postData, 
            dataType: dataType, 
            beforeSend: function () {
                Core.blockUI({animate: true});
            },
            success: function (dataStr) {
                
                if (isCache == false) {
                    
                    var $html = $('<div />', {html: dataStr});
                    $html.find('.bp-detail-row:eq(0)').addClass('display-none added-bp-row');

                    if (isEditMode_<?php echo $this->methodId; ?>) {
                        $html.find("input[data-path*='rowState']").val('added');   
                    }

                    $getTableBody.append($html.html());
                    
                    var $lastRow = $getTableBody.find('> .bp-detail-row:last-child');
                    Core.initBPDtlInputType($lastRow);
                    
                    if ($this.closest('.bprocess-table-dtl').hasClass('cool-row')) {
                        $lastRow.find('a.bp-remove-row').after($this.clone());
                        $this.remove();
                    }                    

                    if ($this.closest('.bp-template-wrap').length === 0) {

                        $lastRow.find('select.linked-combo').each(function () {
                            if ($(this).attr('data-out-param').indexOf('.') !== -1) {
                                $(this).trigger('change');
                            }
                        });
                        $lastRow.find('input.linked-combo').each(function () {
                            if ($(this).attr('data-out-param').indexOf('.') !== -1) {
                                $(this).trigger('change');
                            }
                        });
                        $lastRow.find('input[data-in-param]').each(function () {
                            var $thisLp = $(this);
                            var dataInParam = $thisLp.attr('data-in-param').split('|');
                            var dataInParamLength = dataInParam.length;
                            var linkedFieldIsEmpty = true;
                            for (var ip = 0; ip < dataInParamLength; ip++) {
                                if ($("input[data-path='"+dataInParam[ip]+"']", bp_window_<?php echo $this->methodId; ?>).val() === '') {
                                    linkedFieldIsEmpty = false;
                                }
                            }
                            if (linkedFieldIsEmpty) {
                                setBpRowParamEnable(bp_window_<?php echo $this->methodId; ?>, $thisLp, $thisLp.attr('data-path'));
                            }
                        });
                    }
            
                    partialExpressionStart_<?php echo $this->methodId; ?>($lastRow);
                    bpFullScriptsWithoutEvent_<?php echo $this->methodId; ?>($lastRow, $groupPath, false, true);
                    dtlAggregateFunction_<?php echo $this->methodId; ?>();

                    var $el = $getTableBody.find('> .bp-detail-row:not(.removed-tr)'), len = $el.length, i = 0;
                    for (i; i < len; i++) { 
                        $($el[i]).find('td:first > span').text(i + 1);
                    }
                    bpSetRowIndex($parent);

                    $getTableBody.find('> .bp-detail-row.display-none').removeClass('display-none');
                    enableBpDetailFilterByElement($getTable);
                    bpDetailHideShowFields($getTable);
                    bpDetailFreeze($getTable);
                    $getTable.closest('.bp-overflow-xy-auto').animate({ scrollTop: 10000 }, 0);
                    
                    var $focusElement = $lastRow.find('input:not([data-isdisabled], [readonly="readonly"], [readonly], readonly, [disabled="disabled"], [disabled], disabled, input.meta-name-autocomplete, select.select2):visible:first');
                    
                    if ($focusElement.length) {
                        $focusElement.focus().select();
                    } else {
                        $focusElement = $lastRow.find('textarea:not([data-isdisabled], [readonly="readonly"], [readonly], readonly, [disabled="disabled"], [disabled], disabled):visible:first');
                        if ($focusElement.length) {
                            $focusElement.focus().select();
                        }
                    }
                    
                } else {
                    
                    bpDetailPagerRefreshNavigationBar($("div[data-pg-grouppath='" + $groupPath + "']", bp_window_<?php echo $this->methodId; ?>), dataStr.count, dataStr.pageNumber);
                    bpDetailPagerSetFooterAmount($getTable, dataStr.aggregate);
                    
                    if (dataStr.append === '1') {
                        
                        $getTableBody.append(dataStr.html);
                        
                        if (isAppend) {
                            
                            var $el = $getTableBody.find('> .bp-detail-row');
                            var len = $el.length;
                            
                            var lastNum = Number($($el[(len-2)]).find('td:first > span').text());
                            var $lastRow = $($el[(len-1)]);
                            $lastRow.find('td:first > span').text(lastNum + 1);
                            
                        } else {
                            
                            var $el = $getTableBody.find('> .bp-detail-row');
                            var len = $el.length, i = 0;
                            for (i; i < len; i++) { 
                                $($el[i]).find('td:first > span').text(i + 1);
                            }

                            var $lastRow = $($el[i]);
                        }
                        
                        Core.initBPDtlInputType($lastRow);

                        bpFullScriptsWithoutEvent_<?php echo $this->methodId; ?>($lastRow, $groupPath, false, true);
                        
                        enableBpDetailFilterByElement($getTable);  
                        
                        bpDetailHideShowFields($getTable);
                        bpDetailFreeze($getTable);
                        
                        $getTable.closest('.bp-overflow-xy-auto').animate({
                            scrollTop: 10000
                        }, 0);
                        
                        $lastRow.find('input:not([data-isdisabled], [readonly="readonly"], [readonly], readonly, [disabled="disabled"], [disabled], disabled, input.meta-name-autocomplete, select.select2):visible:first').focus().select();
                    
                    } else {
                        
                        $getTableBody[0].innerHTML = dataStr.html;
                        Core.initBPDtlInputType($getTableBody);
                        
                        var $lastRow = $getTableBody.find('> .bp-detail-row:last-child');
                        
                        bpFullScriptsWithoutEvent_<?php echo $this->methodId; ?>($lastRow, $groupPath, false, true);
                        
                        /*
                        var $rowEl = $getTableBody.find('> .bp-detail-row');
                        var $rowLen = $rowEl.length, $rowi = 0;

                        if ($rowLen === 1) {

                            bpFullScriptsWithoutEvent_<?php echo $this->methodId; ?>($($rowEl[$rowi]), $groupPath, true, true);

                        } else if ($rowLen > 1) {

                            $rowLen = $rowLen - 1;

                            for ($rowi; $rowi < $rowLen; $rowi++) { 
                                bpFullScriptsWithoutEvent_<?php echo $this->methodId; ?>($($rowEl[$rowi]), $groupPath, true, false);
                            }

                            bpFullScriptsWithoutEvent_<?php echo $this->methodId; ?>($($rowEl[$rowLen]), $groupPath, true, true);
                        }*/
                        
                        enableBpDetailFilterByElement($getTable);        
                        
                        bpDetailHideShowFields($getTable);
                        bpDetailFreeze($getTable);
                        
                        $getTableBody.find('input:not([data-isdisabled], [readonly="readonly"], [readonly], readonly, [disabled="disabled"], [disabled], disabled, input.meta-name-autocomplete, select.select2):visible:first').focus().select();
                    }
                }
            },
            error: function () { alert('Error'); }

        }).done(function () { Core.unblockUI(); });
        
        return;
    }
    function bpAddDtlRow_<?php echo $this->methodId; ?>(elem, htmlStr) {
        var $this = $(elem), $parent = $this.parent(), 
            $table = $parent.find('.table:eq(0)'), 
            async = ($this.hasAttr('data-async') && $this.attr('data-async') == '0') ? false : true;
    
        $.ajax({ 
            type: 'post', 
            url: 'mdcommon/cryptEncodeToDecodeByPost', 
            data: {processId: '<?php echo $this->methodId; ?>', rowId: $table.attr('data-row-id'), string: htmlStr}, 
            async: async, 
            beforeSend: function() {
                Core.blockUI({animate: true});
            },
            success: function (dataStr) {
                var $html = $('<div />', {html: dataStr});
                $html.find("tr:eq(0)").addClass("display-none");
                
                if (isEditMode_<?php echo $this->methodId; ?>) {
                    $html.find("input[data-path*='rowState']").val('added');
                }
                $table.find('> .tbody').append($html.html());
                
                var el = $table.find('> .tbody > .bp-detail-row'), len = el.length, i = 0;
                for (i; i < len; i++) { 
                    $(el[i]).find('td:first > span').text(i + 1);
                }
                
                var $bpDtlFirstInput = bp_window_<?php echo $this->methodId; ?>.find('.bprocess-table-dtl:eq(0)> .tbody').find('.bp-detail-row.currentTarget').find("input[name][data-path]:first");
                
                if ($bpDtlFirstInput.length) {
                    if (/^(.*)(\[[0-9]+\])/.test($bpDtlFirstInput.attr('name'))) {
                        var matchBpIndex = $bpDtlFirstInput.attr('name').match(/^(.*)(\[[0-9]+\])/);
                    } else {
                        var matchBpIndex = $bpDtlFirstInput.parent().find("input:hidden:first").attr('name').match(/^(.*)(\[[0-9]+\])/);
                    }
                                        
                    if (typeof matchBpIndex[2] !== 'undefined') {
                        matchBpIndex = matchBpIndex[2].slice(1, -1);
                        bpSetRowIndexDepth($parent, bp_window_<?php echo $this->methodId; ?>, matchBpIndex);
                    } else {
                        bpSetRowIndexDepth($parent, bp_window_<?php echo $this->methodId; ?>);
                    }
                } else {
                    bpSetRowIndexDepth($parent, bp_window_<?php echo $this->methodId; ?>);
                }
                
                var $lastRow = $parent.find(".bprocess-table-dtl:eq(0) > .tbody > .bp-detail-row:last-child");
                
                Core.initBPDtlInputType($lastRow);
                
                if ($this.closest('.bp-template-wrap').length === 0) {

                    $lastRow.find('input[data-in-param], select[data-in-param]').each(function () {
                        
                        var $thisLp = $(this);
                        var dataInParam = $thisLp.attr('data-in-param').split('|');
                        var dataInParamLength = dataInParam.length;
                        var linkedFieldIsEmpty = true;
                        
                        for (var ip = 0; ip < dataInParamLength; ip++) {
                            
                            var $parentPath = $thisLp.parents('.bp-detail-row').find("[data-path='"+dataInParam[ip]+"']");
                            if ($parentPath.length) {
                                if ($parentPath.val() == '') {
                                    linkedFieldIsEmpty = false;
                                }
                            } else if ($("[data-path='"+dataInParam[ip]+"']", bp_window_<?php echo $this->methodId; ?>).val() === '') {
                                linkedFieldIsEmpty = false;
                            }
                        }
                        
                        if (linkedFieldIsEmpty) {
                            setBpRowParamEnable(bp_window_<?php echo $this->methodId; ?>, $thisLp, $thisLp.attr('data-path'));
                        }
                    });
                }
        
                dtlAggregateFunction_<?php echo $this->methodId; ?>();
                partialExpressionStart_<?php echo $this->methodId; ?>($lastRow);
                bpFullScriptsWithoutEvent_<?php echo $this->methodId; ?>($lastRow, $parent.find(".bprocess-table-dtl:eq(0)").attr('data-table-path'), false);
                $lastRow.removeClass('display-none');
            
                $lastRow.find("input:visible:first").focus();
                
                Core.unblockUI();
            },
            error: function () { alert("Error"); }
        });
    }
    function bpSaveMainRow(elem) {
        var $this = $(elem);
        var headerParam = false;
        var groupParam = false;

        if ($("div.bp-header-param", bp_window_<?php echo $this->methodId; ?>).length > 0) {
            var $thisHeaderParamElement = $("div.bp-header-param", bp_window_<?php echo $this->methodId; ?>);
            headerParam = true;
        }
        if ($this.closest("fieldset").length > 0) {
            var _thisGroupParamElement = $this.closest("fieldset");
            groupParam = true;
        }

        var formData;

        if (headerParam) {
            formData += $thisHeaderParamElement.find("input, select").serialize();
        }
        if (groupParam) {
            formData += '&' + _thisGroupParamElement.find("input, select").serialize();
        }
        if ($("#bprocessCoreParam", bp_window_<?php echo $this->methodId; ?>).length > 0) {
            formData += '&' + $("#bprocessCoreParam", bp_window_<?php echo $this->methodId; ?>).find("input").serialize();
        }        

        $.ajax({
            type: 'post',
            url: 'mdwebservice/runProcess',
            data: formData,
            dataType: 'json',
            beforeSend: function () {
                Core.blockUI({
                    message: 'Түр хүлээнэ үү',
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
                    var resultData = responseData.resultData;
                    
                    if (typeof resultData.id !== 'undefined') {
                        $("input[data-path='id']", bp_window_<?php echo $this->methodId; ?>).val(resultData.id);
                        $("input[data-path='rowState']", bp_window_<?php echo $this->methodId; ?>).val('modified');
                    }
                    
                    bp_window_<?php echo $this->methodId; ?>.find('input[name="windowSessionId"]').val(responseData.uniqId);
                    
                    <?php if ($this->dmMetaDataId) { echo 'window[\'objectdatagrid_'.$this->dmMetaDataId.'\'].datagrid(\'reload\');'; } ?>
                } else {
                    new PNotify({
                        title: 'Error',
                        text: responseData.message,
                        type: 'error',
                        sticker: false
                    });
                }   
                Core.unblockUI();
            },
            error: function () {
                alert("Error");
            }
        });
    }
    <?php
    if (isset($isDtlTheme)) {
    ?>
    function bpAddMainThemeRow_<?php echo $this->methodId; ?>(elem, processId, rowId, themeId) {
        var $this = $(elem);
        var $parent;
        
        if ($this.closest('div.theme-grid').length === 0) {
            $parent = $this.closest('fieldset');
            if ($parent.length === 0) {
                $parent = $this.closest('div[data-section-path]');
            } 
        } else {
            $parent = $this.closest('div.theme-grid');
        }
        
        var $getTableBody = getTable = $parent.find('.bprocess-table-dtl-theme');
        var addRowNum = $this.prev('input.bp-add-one-row-num');
        
        if (addRowNum.length && (addRowNum.val() !== '' && addRowNum.val() !== '0')) {
            
            var addRowNumType = addRowNum.attr('data-addrowtype'); 
            var addRowNumVal = Number(addRowNum.val());
            
            if (addRowNumType == 'new' || $getTableBody.children('tr').length == 0) {
                
                $.ajax({
                    type: 'post',
                    url: 'mdcommon/renderBpThemeDtlRow',
                    data: {processId: processId, rowId: rowId, themeId: themeId},
                    beforeSend: function () {
                        Core.blockUI({
                            animate: true
                        });
                    },
                    success: function (dataStr) {
                        console.log(dataStr);
                    },
                    error: function () {
                        alert('Error');
                    }

                }).done(function () {
                    Core.initBPDtlInputType($($getTableBody));
                    Core.unblockUI();
                });
                
            } else {
                
                Core.blockUI({
                    animate: true
                });
                        
                if (addRowNumType === 'first') {
                    var row = $getTableBody.find('> .bp-detail-row:eq(0)');
                } else if (addRowNumType === 'last') {
                    var row = $getTableBody.find('> .bp-detail-row:last');
                } else if (addRowNumType === 'selectedrow') {
                    if ($getTableBody.find('> .bp-detail-row.currentTarget:eq(0)').length) {
                        var row = $getTableBody.find('> .bp-detail-row.currentTarget:eq(0)');
                    } else {
                        var row = $getTableBody.find('> .bp-detail-row:eq(0)');
                    }
                } else {
                     var row = $getTableBody.find("> .bp-detail-row:eq("+(addRowNumType-1)+")");
                }
                
                row.find('select.select2').select2('destroy');
                row.removeClass('saved-bp-row currentTarget').addClass('added-bp-row display-none multi-added-row');
                $.uniform.restore(row.find('input[type=checkbox]'));
                
                for (i = 0; i < addRowNumVal; i++) {
                    $getTableBody.append(row.clone());
                }
                
                var rowNumEl = $getTableBody.find('> .bp-detail-row');
                var rowNumLen = rowNumEl.length, ni = 0;

                for (ni; ni < rowNumLen; ni++) { 
                    $(rowNumEl[ni]).find('[data-cell-path]:first > span').text(ni + 1);
                }

                bpSetRowIndex($parent);
                
                var $rowEl = $getTableBody.find('> .bp-detail-row.multi-added-row');
                var rowLen = $rowEl.length, rowi = 0;

                for (rowi; rowi < rowLen; rowi++) { 
                    Core.initBPDtlInputType($($rowEl[rowi]));
                    bpFullScriptsWithoutEvent_<?php echo $this->methodId; ?>($($rowEl[rowi]), getTable.attr('data-table-path'), true);
                }
                
                $rowEl.removeClass('multi-added-row display-none');
                
                dtlAggregateFunction_<?php echo $this->methodId; ?>();
                enableBpDetailFilterByElement(getTable);
                
                Core.unblockUI();
            }
            
            return;
        } 
            
        $.ajax({
            type: 'post',
            url: 'mdcommon/renderBpThemeDtlRow',
            data: {processId: processId, rowId: rowId},
            beforeSend: function () {
                Core.blockUI({
                    animate: true
                });
            },
            success: function (dataStr) {

                var $html = $('<div />', {html: dataStr});
                $html.find('.bp-detail-row:eq(0)').addClass('display-none added-bp-row');

                if (isEditMode_<?php echo $this->methodId; ?>) {
                    $html.find("input[data-path*='rowState']").val('added');   
                }

                $getTableBody.append($html.html());

                Core.initBPDtlInputType($getTableBody.find('> .bp-detail-row:last-child'));

                $getTableBody.find('> .bp-detail-row:last-child').find('select.linked-combo').each(function () {
                    if ($(this).attr('data-out-param').indexOf('.') !== -1) {
                        $(this).trigger('change');
                    }
                });
                $getTableBody.find('> .bp-detail-row:last-child').find('input.linked-combo').each(function () {
                    if ($(this).attr('data-out-param').indexOf('.') !== -1) {
                        $(this).trigger('change');
                    }
                });
                $getTableBody.find('> .bp-detail-row:last-child').find('input[data-in-param]').each(function () {
                    var $thisLp = $(this);
                    var dataInParam = $thisLp.attr('data-in-param').split('|');
                    var dataInParamLength = dataInParam.length;
                    var linkedFieldIsEmpty = true;
                    for (var ip = 0; ip < dataInParamLength; ip++) {
                        if ($("input[data-path='"+dataInParam[ip]+"']", bp_window_<?php echo $this->methodId; ?>).val() === "") {
                            linkedFieldIsEmpty = false;
                        }
                    }
                    if (linkedFieldIsEmpty) {
                        setBpRowParamEnable(bp_window_<?php echo $this->methodId; ?>, $thisLp, $thisLp.attr('data-path'));
                    }
                });
            },
            error: function () {
                alert('Error');
            }

        }).done(function () {
            Core.initBPDtlInputType($($getTableBody));
            
            bpSetRowIndex($parent, '1');
            
            partialExpressionStart_<?php echo $this->methodId; ?>($getTableBody.find('> .bp-detail-row:last-child'));
            bpFullScriptsWithoutEvent_<?php echo $this->methodId; ?>($getTableBody.find('> .bp-detail-row:last-child'), getTable.attr('data-table-path'), false, true);
            dtlAggregateFunction_<?php echo $this->methodId; ?>();
            $getTableBody.find('> .bp-detail-row.currentTarget').removeClass('currentTarget');

            var el = $getTableBody.find('> .bp-detail-row'), len = el.length, i = 0;
            for (i; i < len; i++) { 
                $(el[i]).find('[data-cell-path]:first > span').text(i + 1);
            }

            $getTableBody.find('> .bp-detail-row.display-none').removeClass('display-none');
            enableBpDetailFilterByElement(getTable);
            $getTableBody.find('> .bp-detail-row:last-child').addClass('currentTarget').find('input:visible:first').focus();

            Core.unblockUI();
        });
        
        return;
    }
    <?php
    }
    }
    ?>
    
    function partialExpressionStart_<?php echo $this->methodId; ?>(el, humanNotTriggered) {
        if (checkFullExp_<?php echo $this->methodId; ?> || checkFullExpWithoutEvent_<?php echo $this->methodId; ?>)
            return;
        
        if (typeof (humanNotTriggered) === 'undefined') {
            humanNotTriggered = true;
        }
        $("div.bp-header-param", bp_window_<?php echo $this->methodId; ?>).find("select.linked-combo").trigger("change", [humanNotTriggered]);
    }
    function setVerticalBannerSize() {
        var bannerHeight = 0;
        <?php
        if (($this->methodRow['WINDOW_SIZE'] == 'custom' && $this->methodRow['WINDOW_HEIGHT'] != null) && ($this->methodRow['WINDOW_SIZE'] == 'custom' && $this->methodRow['WINDOW_HEIGHT'] != 'auto')) {
            echo 'bannerHeight = Number(' . $this->methodRow['WINDOW_HEIGHT'] . ') - 120;';
            echo '$(".banner-position-dialog-left div.bp-banner-spacer, .banner-position-dialog-right div.bp-banner-spacer, .banner-position-left div.bp-banner-spacer, .banner-position-right div.bp-banner-spacer", bp_window_' . $this->methodId . ').height(bannerHeight);';
        } elseif ($this->methodRow['WINDOW_SIZE'] == 'standart') {
            echo 'bannerHeight = $(\'div[data-bp-uniq-id="'.$this->uniqId.'"] div.page-processs-main-content\').height();';
            echo '$(".banner-position-dialog-left div.bp-banner-spacer, .banner-position-dialog-right div.bp-banner-spacer, .banner-position-left div.bp-banner-spacer, .banner-position-right div.bp-banner-spacer", bp_window_' . $this->methodId . ').height(bannerHeight);';
        }
        ?>
    }
    function dtlAggregateFunction_<?php echo $this->methodId; ?>() {
        
        if ($aggregate_<?php echo $this->methodId; ?>.length) {
            var $el = $aggregate_<?php echo $this->methodId; ?>, $len = $el.length, $i = 0;
            
            for ($i; $i < $len; $i++) { 
                var $row = $($el[$i]);
                var $funcName = $row.attr('data-aggregate');
                var $path = $row.attr('data-cell-path');
                
                if ($row.hasAttr('data-pivot-colcode')) {
                    var pivotGroupNum = $row.attr('data-pivot-colcode');
                    var $gridBody = bp_window_<?php echo $this->methodId; ?>.find('.bprocess-table-dtl > .tbody > .bp-detail-row:not(.removed-tr) > [data-group-num="'+pivotGroupNum+'"][data-cell-path="' + $path + '"]');
                    var $footCell = bp_window_<?php echo $this->methodId; ?>.find('.bprocess-table-dtl > tfoot > tr > [data-pivot-colcode="'+pivotGroupNum+'"][data-cell-path="' + $path + '"]');
                } else {
                    var $gridBody = bp_window_<?php echo $this->methodId; ?>.find('.bprocess-table-dtl > .tbody > .bp-detail-row:not(.removed-tr) > [data-cell-path="' + $path + '"]');
                    var $footCell = bp_window_<?php echo $this->methodId; ?>.find('.bprocess-table-dtl > tfoot > tr > [data-cell-path="' + $path + '"]');
                }
                
                if ($funcName === 'sum') {
                    if ($gridBody.eq(0).find('input[type="text"]').hasClass('bigdecimalInit')) {
                        
                        var $sum = 0;
                        var $rows = $gridBody.find('input[type="hidden"][data-path*="_bigdecimal"]');
                        var $sumVal;
                        
                        $rows.each(function(){
                            $sumVal = $(this).val();
                            
                            if ($sumVal != '' && $sumVal != null) {
                                $sum += parseFloat($sumVal);
                            }
                        });
                    } else {
                        var $sum = $gridBody.find('input[type="text"]').sum();
                    }
                    $footCell.autoNumeric('set', $sum);
                    
                } else if ($funcName == 'avg') {
                
                    var $avg = $('.bprocess-table-dtl > .tbody > .bp-detail-row:not(.removed-tr) > [data-cell-path="' + $path + '"] input[type="text"]', bp_window_<?php echo $this->methodId; ?>).avg();
                    $footCell.autoNumeric('set', $avg);
                    
                } else if ($funcName == 'max') {
                    
                    var $max = $('.bprocess-table-dtl > .tbody > .bp-detail-row:not(.removed-tr) > [data-cell-path="' + $path + '"] input[type="text"]', bp_window_<?php echo $this->methodId; ?>).max();
                    $footCell.autoNumeric('set', $max);
                    
                } else if ($funcName == 'min') {
                    var $min = 0;
                    $gridBody.each(function (index) {
                        if (typeof $(this).find('input[type="text"]').val() != 'undefined') {
                            var $cellVal = $(this).find('input[type="text"]').autoNumeric('get');
                            if ($cellVal != '' || Number($cellVal) > 0) {
                                $cellVal = Number($cellVal);
                                if (index === 0) {
                                    $min = $cellVal;
                                }
                                if ($min > $cellVal) {
                                    $min = $cellVal;
                                }
                            }
                        }
                    });
                    $footCell.autoNumeric('set', $min);
                }
            }
        }
        
        var $aggregateSub = bp_window_<?php echo $this->methodId; ?>.find('table.bprocess-table-subdtl > thead > tr > th[data-aggregate]:not([data-aggregate=""])');
        
        if ($aggregateSub.length) {
            var $el = $aggregateSub;
            var $len = $el.length, $i = 0;
            for ($i; $i < $len; $i++) { 
                var $row = $($el[$i]);
                var $funcName = $row.attr('data-aggregate');
                var $path = $row.attr('data-cell-path');
                var $table = $row.closest('.bprocess-table-dtl');
                var $footCell = $table.find('tfoot > tr > [data-cell-path="' + $path + '"]');
                if ($funcName === 'sum') {
                    var $sum = $table.find('.tbody > .bp-detail-row:not(.removed-tr) > [data-cell-path="' + $path + '"] input[type="text"]').sum();
                    $footCell.autoNumeric('set', $sum);
                }
            }
        }
        
        return;
    }
    function bpAddMainMultiRow_<?php echo $this->methodId; ?>(elem, processMetaDataId, lookupMetaDataId, groupLookupMetaTypeId, paramRealPath, type, callback) {

        var $this = $(elem);
        var $parent = $this.closest('div.quick-item-process');
        var params = '', linkedPopup = '', chooseType = 'multi';
        var $thisHidden = $parent.find("input[type='text']");

        if (typeof $thisHidden.attr('data-in-param') !== 'undefined') {
            var _inputParam = $thisHidden.attr('data-in-param').split('|');
            var _lookupParam = $thisHidden.attr('data-in-lookup-param').split('|');

            for (var i = 0; i < _inputParam.length; i++) {
                var $paramField = getBpElement(bp_window_<?php echo $this->methodId; ?>, elem, _inputParam[i]);
                if ($paramField && $paramField.length) {
                    if ($paramField.length > 1) {
                        var $paramFieldArr = $paramField;
                        $paramFieldArr.each(function (_index, _row) {
                            var paramVal = '';
                            $paramField = $(_row);
                            
                            if ($paramField.prop('tagName') == 'SELECT') {
                                if ($paramField.hasClass('select2')) {
                                    paramVal = $paramField.select2('val');
                                } else {
                                    paramVal = $paramField.val();
                                }
                            } else {
                                paramVal = $paramField.val();
                            }     
                            if (paramVal.length > 0) {   
                                params += _lookupParam[i] + '[]=' + paramVal + '&';
                            }
                        });
                    } else {
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
                        if (paramVal.length > 0) {   
                            params += _lookupParam[i] + '=' + paramVal + '&';
                        }
                    }
                    
                }
            }
        }
        
        if (typeof $thisHidden.attr("data-criteria-param") !== 'undefined' && $thisHidden.attr("data-criteria-param") != '') {
            var paramsPathArr = $thisHidden.attr("data-criteria-param").split('|');
            for (var i = 0; i < paramsPathArr.length; i++) {
                var fieldPathArr = paramsPathArr[i].split('@');
                var fieldPath = fieldPathArr[0];
                var inputPath = fieldPathArr[1];
                var fieldValue = '', isCriteria = false;

                if (bp_window_<?php echo $this->methodId; ?>.find("[data-path='"+fieldPath+"']").length) {
                    fieldValue = getBpRowParamNum(bp_window_<?php echo $this->methodId; ?>, $thisHidden, fieldPath);
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
        
        if (params != '') {
            linkedPopup = 'OK';
            params = params + 'autoSearch=1';
        }            

        if (typeof $this.attr('data-choose-type') !== 'undefined' && $this.attr('data-choose-type') !== '') {
            chooseType = $this.attr('data-choose-type');
        }

        var $dialogName = 'dialog-dataview-selectable-'+lookupMetaDataId;
        if (!$("#" + $dialogName).length) {
            $('<div id="' + $dialogName + '"></div>').appendTo('body');
        }
        var $dialog = $('#' + $dialogName);

        $.ajax({
            type: 'post',
            url: 'mdmetadata/dataViewSelectableGrid',
            data: {
                chooseType: chooseType,
                metaDataId: lookupMetaDataId,
                processMetaDataId: processMetaDataId,
                paramRealPath: paramRealPath,
                selectedRows: $parent.find("input[type='text']").serializeArray(),
                params: encodeURIComponent(params),
                linkedPopup: linkedPopup
            },
            dataType: 'json',
            beforeSend: function () {
                Core.blockUI({message: 'Loading...', boxed: true});
            },
            success: function (data) {

                $dialog.empty().append(data.Html);
                
                if (typeof data.addbasket_btn !== 'undefined') {
                    $dialog.dialog({
                    cache: false,
                    resizable: false,
                    bgiframe: true,
                    autoOpen: false,
                    title: data.Title,
                    width: typeof data.Width !== 'undefined' ? data.Width : '1200',
                    height: typeof data.Height !== 'undefined' ? data.Height : 'auto',
                    modal: true,
                    closeOnEscape: isCloseOnEscape, 
                    close: function () {
                        enableScrolling();
                        $dialog.empty().dialog('destroy').remove();
                        
                        var $prevDataGridElem = $('#objectdatagrid-' + lookupMetaDataId);
                        
                        if ($prevDataGridElem.length) {
                            window['objectdatagrid_' + lookupMetaDataId] = $prevDataGridElem;
                        }
                    },
                    buttons: [
                        {text: data.addbasket_btn, class: 'btn green-meadow btn-sm float-left', click: function () {
                            window['basketCommonSelectableDataGrid_'+lookupMetaDataId]();
                        }},
                        {text: data.choose_btn, class: 'btn blue btn-sm datagrid-choose-btn', click: function () {
                            var countBasketList = $('#commonSelectableBasketDataGrid_'+lookupMetaDataId).datagrid('getData').total;
                            if (countBasketList > 0) {
                                var rows = $('#commonSelectableBasketDataGrid_'+lookupMetaDataId).datagrid('getRows'); /*dataViewSelectedRowsResolver($('#commonSelectableBasketDataGrid_'+lookupMetaDataId).datagrid('getRows'))*/
                                rows = rows.map(({action, ...item}) => item);
                                if (typeof callback !== 'function') {
                                    if (callback && (callback === 'detail_frame_paper_001_basket_function' || callback === 'detail_frame_paper_tree_basket_function')) {
                                        window[callback](rows);
                                    } else {
                                        selectedRowsBpAddRow_<?php echo $this->methodId; ?>(elem, processMetaDataId, paramRealPath, lookupMetaDataId, rows, type);
                                    }
                                } else {
                                    callback(rows);
                                }                                    
                                $dialog.dialog('close');
                            } else {
                                PNotify.removeAll();
                                new PNotify({
                                    title: 'Info',
                                    text: '<?php echo $this->lang->line('account-basket-not-null'); ?>',
                                    type: 'info',
                                    sticker: false
                                });
                            }
                        }},
                        {text: data.close_btn, class: 'btn blue-hoki btn-sm', click: function () {
                            $dialog.dialog('close');
                        }}
                    ]
                });
                
                } else {

                    $dialog.dialog({
                        cache: false,
                        resizable: false,
                        bgiframe: true,
                        autoOpen: false,
                        title: data.Title,
                        position: {my: 'top', at: 'top+50'},
                        width: typeof data.Width !== 'undefined' ? data.Width : '1100',
                        height: typeof data.Height !== 'undefined' ? data.Height : 'auto',
                        modal: true,
                        closeOnEscape: isCloseOnEscape, 
                        close: function () {
                            enableScrolling();
                            $dialog.empty().dialog('destroy').remove();
                        },
                        buttons: [
                            {text: plang.get('choose_btn'), class: 'btn blue btn-sm datagrid-choose-btn', click: function () {

                                var countBasketList = window['_selectedRows_'+lookupMetaDataId].length;
                                if (countBasketList > 0) {
                                    var rows = window['_selectedRows_'+lookupMetaDataId];
                                    if (typeof callback !== 'function') {
                                        selectedRowsBpAddRow_<?php echo $this->methodId; ?>(elem, processMetaDataId, paramRealPath, lookupMetaDataId, rows, type);
                                    } else {
                                        callback(rows);
                                    }                                    
                                    $dialog.dialog('close');
                                } else {
                                    PNotify.removeAll();
                                    new PNotify({
                                        title: 'Info',
                                        text: '<?php echo $this->lang->line('account-basket-not-null'); ?>',
                                        type: 'info',
                                        sticker: false
                                    });
                                }
                            }},
                            {text: plang.get('close_btn'), class: 'btn blue-hoki btn-sm', click: function () {
                                $dialog.dialog('close');
                            }}
                        ]
                    });            
                }
            
                $dialog.dialog('open');
                $dialog.css('overflow-x', 'hidden');
                Core.unblockUI();
            },
            error: function () {
                alert("Error");
            }
        }).done(function () {
            Core.initDVAjax($dialog);
        });
    }
    function selectedRowsBpAddRow_<?php echo $this->methodId; ?>(elem, processMetaDataId, paramRealPath, lookupMetaDataId, rows, type, dialogElement) {
        var $this = $(elem);
        var $parent = $this.closest('div[data-section-path]');
        if ($parent.length === 0) {
            $parent = $this.closest('fieldset');
        } 
        
        var $getTable = $parent.find('[data-table-path="'+paramRealPath+'"]:eq(0)');
        var $groupId = $getTable.attr('data-row-id');
        var $getTableBody = $getTable.find('> .tbody');
        var dtlTheme = '';
        
        if ($getTable.hasAttr('data-dtltheme')) {
            dtlTheme = $getTable.attr('data-dtltheme');
        }
        
        var postData = {
            processMetaDataId: processMetaDataId,
            paramRealPath: paramRealPath,
            lookupMetaDataId: lookupMetaDataId,
            selectedRows: rows,
            fillType: type,
            rowId: $groupId, 
            uniqId: '<?php echo $this->uniqId; ?>', 
            isEditMode: isEditMode_<?php echo $this->methodId; ?>, 
            dtlTheme: dtlTheme, 
            headerParams: $('div.bp-header-param', bp_window_<?php echo $this->methodId; ?>).find('input, select').serialize()
        };
        
        var isCache = false, dataType = 'html';
        
        if (typeof $getTable.attr('data-pager') !== 'undefined' && $getTable.attr('data-pager') === 'true') {
            
            var $pagerElement = $("div[data-pg-grouppath='" + paramRealPath + "']", bp_window_<?php echo $this->methodId; ?>);
            var $lookupInputs = $getTableBody.find('input.popupInit, select.select2');
            var $lookupInputsLen = $lookupInputs.length, $n = 0;
            var objs = {}, rowObj = {};
            
            postData['groupPath'] = paramRealPath;
            postData['pageSize'] = $pagerElement.attr('data-pg-pagesize');
            postData['currentPageTotal'] = $pagerElement.find('.pf-bp-pager-total span').text();
            postData['params'] = $getTableBody.find('input, select, textarea').serialize();
            postData['headerData'] = bp_window_<?php echo $this->methodId; ?>.find('div.bp-header-param').find('input, select').serialize();
                
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
                    rowObj['rowdata'] = ($rowData ? JSON.parse($rowData.replace(/\\&quot;/g, '&quot;')) : '');

                    objs[$n] = rowObj;
                }
            }

            postData['lookupParams'] = objs;
            
            if (typeof $getTable.attr('data-ignore-criteria-rows') !== 'undefined' && $getTable.attr('data-ignore-criteria-rows') != '') {
                postData['ignoreCriteriaRows'] = $getTable.attr('data-ignore-criteria-rows');
            }
            
            isCache = true;
            dataType = 'json';
        }
        
        if (typeof $getTable.attr('data-pivot-dtl') !== 'undefined') {
            
            var $pivotHdrs = $getTable.find('thead:eq(0)').find('[data-key-id]');
            var $pivotHdrsLen = $pivotHdrs.length;
            var p = 0, pivotObj = {};
            
            for (p; p < $pivotHdrsLen; p++) { 
                pivotObj[p] = $($pivotHdrs[p]).attr('data-key-id');
            }
            
            postData['pivotDtlId'] = $getTable.attr('data-dtl-id');
            postData['pivotPath'] = $getTable.attr('data-pivot-path');
            postData['pivotObj'] = pivotObj;
        }
        
        if (type == 'excelimport' && typeof dialogElement != 'undefined') {
            
            var $form = dialogElement.find('form');
            $form.validate({ errorPlacement: function() {} });
            
            if ($form.valid()) {
                $form.ajaxSubmit({
                    type: 'post',
                    url: 'mdprocess/importDetailExcel',
                    beforeSubmit: function(formData, jqForm, options) {
                        for (var pKey in postData) {
                            formData.push({name: pKey, value: postData[pKey]});
                        }
                    },
                    beforeSend: function() {
                        Core.blockUI({message: 'Loading...', boxed: true});
                    },
                    success: function(dataStr) {
                        rowsToBpDetailRender_<?php echo $this->methodId; ?>(dataStr);
                        dialogElement.dialog('close');
                    }, 
                    error: function(data) {
                        new PNotify({
                            title: data.responseJSON.status,
                            text: data.responseJSON.message,
                            type: data.responseJSON.status,
                            sticker: false
                        });
                        Core.unblockUI();
                    }
                });
            }
            
        } else {
            
            $.ajax({
                type: 'post',
                url: 'mdwebservice/renderDtlGroup',
                data: postData,
                dataType: dataType, 
                beforeSend: function() {
                    Core.blockUI({boxed: true, message: 'Loading...'});
                },
                success: function(dataStr) {
                    rowsToBpDetailRender_<?php echo $this->methodId; ?>(dataStr);
                },
                error: function() { alert('Error'); Core.unblockUI(); }
            });
        }
        
        function rowsToBpDetailRender_<?php echo $this->methodId; ?>(dataStr) {
            if (isCache == false) {
                    
                var subDtl = false;

                if ($getTable.hasClass('bprocess-table-subdtl')) {
                    subDtl = true;
                    dataStr = dataStr.replace(new RegExp('.mainRowCount]', 'g'), '.rowCount][0]');
                }

                var $html = $('<div />', {html: dataStr});

                if (!dtlTheme) {

                    $html.children('.bp-detail-row').addClass('added-bp-row display-none multi-added-row');

                    if (isEditMode_<?php echo $this->methodId; ?>) {
                        $html.find("input[data-path*='rowState']").val('added');   
                    }

                    $getTableBody.append($html.html());

                    var $rowEl = $getTableBody.find('> .bp-detail-row.multi-added-row'), rowLen = $rowEl.length, rowi = 0;

                    if (rowLen === 1) {

                        Core.initBPDtlInputType($($rowEl[rowi]));
                        bpFullScriptsWithoutEvent_<?php echo $this->methodId; ?>($($rowEl[rowi]), paramRealPath, true, true, 'autocomplete');

                    } else if (rowLen > 1) {

                        var rowLen = rowLen - 1;

                        for (rowi; rowi < rowLen; rowi++) { 
                            Core.initBPDtlInputType($($rowEl[rowi]));
                            bpFullScriptsWithoutEvent_<?php echo $this->methodId; ?>($($rowEl[rowi]), paramRealPath, true, false, 'autocomplete');
                        }

                        Core.initBPDtlInputType($($rowEl[rowLen]));
                        bpFullScriptsWithoutEvent_<?php echo $this->methodId; ?>($($rowEl[rowLen]), paramRealPath, true, true, 'autocomplete');
                    }

                    $rowEl.removeClass('multi-added-row display-none');

                    var $rowNumEl = $getTableBody.find('> .bp-detail-row:not(.removed-tr)'), $rowNumLen = $rowNumEl.length, ni = 0;

                    for (ni; ni < $rowNumLen; ni++) { 
                        $($rowNumEl[ni]).find('td:first > span').text(ni + 1);
                    }

                    if (subDtl) {
                        bpSetRowIndexDepth($parent, bp_window_<?php echo $this->methodId; ?>);
                    } else {
                        bpSetRowIndex($parent);
                    }

                    dtlAggregateFunction_<?php echo $this->methodId; ?>();

                    bpDetailHideShowFields($getTable);
                    enableBpDetailFilterByElement($getTable);
                    bpDetailFreeze($getTable);

                } else {

                    $html.children('.bp-detail-row').addClass('added-bp-row display-none multi-added-row');

                    if (isEditMode_<?php echo $this->methodId; ?>) {
                        $html.find("input[data-path*='rowState']").val('added');   
                    }

                    $getTableBody.append($html.html());

                    var $rowEl = $getTableBody.find('> .bp-detail-row.multi-added-row'), rowLen = $rowEl.length, rowi = 0;

                    if (rowLen === 1) {

                        Core.initBPDtlInputType($($rowEl[rowi]));
                        bpFullScriptsWithoutEvent_<?php echo $this->methodId; ?>($($rowEl[rowi]), paramRealPath, true, true, 'autocomplete');

                    } else if (rowLen > 1) {

                        var rowLen = rowLen - 1;

                        for (rowi; rowi < rowLen; rowi++) { 
                            Core.initBPDtlInputType($($rowEl[rowi]));
                            bpFullScriptsWithoutEvent_<?php echo $this->methodId; ?>($($rowEl[rowi]), paramRealPath, true, false, 'autocomplete');
                        }

                        Core.initBPDtlInputType($($rowEl[rowLen]));
                        bpFullScriptsWithoutEvent_<?php echo $this->methodId; ?>($($rowEl[rowLen]), paramRealPath, true, true, 'autocomplete');
                    }

                    $rowEl.removeClass('multi-added-row display-none');

                    if (subDtl) {
                        bpSetRowIndexDepth($parent, bp_window_<?php echo $this->methodId; ?>);
                    } else {
                        bpSetRowIndex($parent);
                    }
                }
                
                if (typeof(window['rowsFillAfterLoad_<?php echo $this->methodId; ?>_' + paramRealPath]) === 'function') {
                    window['rowsFillAfterLoad_<?php echo $this->methodId; ?>_' + paramRealPath]();
                }

            } else {

                $getTableBody.css({display: 'none'});
                $getTableBody[0].innerHTML = dataStr.html;

                Core.initBPDtlInputType($getTableBody);

                bpDetailPagerRefreshNavigationBar($("div[data-pg-grouppath='" + paramRealPath + "']", bp_window_<?php echo $this->methodId; ?>), dataStr.count, dataStr.pageNumber);
                bpDetailPagerSetFooterAmount($getTable, dataStr.aggregate);

                var $rowEl = $getTableBody.find('> tr'), $rowLen = $rowEl.length, $rowi = 0;

                if ($rowLen === 1) {

                    bpFullScriptsWithoutEvent_<?php echo $this->methodId; ?>($($rowEl[$rowi]), paramRealPath, true, true, 'autocomplete');

                } else if ($rowLen > 1) {

                    $rowLen = $rowLen - 1;

                    for ($rowi; $rowi < $rowLen; $rowi++) { 
                        bpFullScriptsWithoutEvent_<?php echo $this->methodId; ?>($($rowEl[$rowi]), paramRealPath, true, false, 'autocomplete');
                    }

                    bpFullScriptsWithoutEvent_<?php echo $this->methodId; ?>($($rowEl[$rowLen]), paramRealPath, true, true, 'autocomplete');
                }

                $getTableBody.css({display: ''});

                bpDetailHideShowFields($getTable);  
                enableBpDetailFilterByElement($getTable);             
                bpDetailFreeze($getTable);
            }
            
            Core.unblockUI();
        }
        
        return;
    }
    
    <?php
    if (isset($isWizard) && $isWizard) {
    ?>
    $('#wizard-<?php echo $this->uniqId; ?>').on('click', '.bp-icon-selection li', function(){
        $('#wizard-<?php echo $this->uniqId; ?>').find('.wizard-type-next-btn').closest('a').trigger('click');
    });        
    function initKpiWizardForm_<?php echo $this->methodId; ?>() {
        $('#wizard-<?php echo $this->uniqId; ?>').steps({
            headerTag: 'h3',
            bodyTag: 'section',
            transitionEffect: 'fade',
            autoFocus: true, 
            enableFinishButton: false, 
            enableKeyNavigation: false,
            titleTemplate: '<span class="number">#index#</span> #title#', 
            labels: {
                previous: '<i class="icon-arrow-left13 mr-2"></i> ' + plang.get('prev'),
                next: plang.get('next') + ' <i class="icon-arrow-right14 ml-2 wizard-type-next-btn"></i>',
                finish: plang.get('finish_btn') + ' <i class="icon-arrow-right14 ml-2"></i>'
            }, 
            onInit: function (event, currentIndex) { 
                var $this = $(this);
                $this.attr('data-step', currentIndex);
                $this.find('> .steps').hide();
                $this.find('> .actions').addClass('text-center');
            },
            onStepChanging: function (event, currentIndex, newIndex) {

                if (currentIndex > newIndex) {
                    return true;
                }
                
                return true;
            },
            onStepChanged: function (event, currentIndex, priorIndex) { 
                $(this).attr('data-step', currentIndex);
            }
        });
    }
    <?php
    }
    ?>
    
    var isSaveConfirm_<?php echo $this->methodId; ?> = false;
    
    function processBeforeSave_<?php echo $this->methodId; ?>(thisButton) {
        PNotify.removeAll();
        
        if (typeof (window['kpiBeforeSave_<?php echo $this->methodId; ?>']) === 'function' && window['kpiBeforeSave_<?php echo $this->methodId; ?>'](thisButton) == false) {    
            return false;
        }
        
        <?php echo $this->bpFullScriptsSave; ?>

        return true;
    }
    function processAfterSave_<?php echo $this->methodId; ?>(thisButton, responseStatus, responseData) {
        
        <?php echo $this->bpFullScriptsAfterSave; ?>

        return true;
    }
</script>
