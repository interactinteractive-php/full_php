<script type="text/javascript">
    var dv_search_<?php echo $this->metaDataId; ?> = $("div#dv-search-<?php echo $this->metaDataId; ?>");
    var objectdatagrid_<?php echo $this->metaDataId; ?> = $('#objectdatagrid-<?php echo $this->metaDataId; ?>');
    var windowId_<?php echo $this->metaDataId; ?> = 'div#object-value-list-<?php echo $this->metaDataId; ?>';
    var filterFieldList_<?php echo $this->metaDataId; ?> = <?php echo ($this->isTree) ? "JSON.parse('".json_encode($this->filterFieldList)."')" : '{}'; ?>;
    var dv_var_<?php echo $this->metaDataId; ?> = {};
    
    $(function() {
        
        <?php if (defined('CONFIG_TOP_MENU') && CONFIG_TOP_MENU) { ?>
            $('#default-criteria-form', "#object-value-list-<?php echo $this->metaDataId; ?>").find('div:first').removeAttr('style')
        <?php } ?>
        
        $(windowId_<?php echo $this->metaDataId; ?>).on('keyup copy paste cut', "form .bigdecimalInit", function(){
            var _this = $(this);
            _this.next("input[type=hidden]").val(pureNumber(_this.val()));
        });
        
        Core.initInputType($('#object-value-list-<?php echo $this->metaDataId; ?>'));
        
        <?php 
        if (count($this->dataViewProcessCommand['commandContext']) > 0) { ?>
        $.contextMenu({
            selector: '<?php echo $this->contextSelector; ?>',
            callback: function (key, opt) {
                transferProcessAction('', '<?php echo $this->metaDataId; ?>', key, '<?php echo Mdmetadata::$businessProcessMetaTypeId; ?>', 'grid', opt.$trigger, {callerType: '<?php echo $this->metaDataCode ?>'}, '');
            },
            events: {
                show: function(options){
                    explorerItemActive(options.$trigger.find('.selected-row-link'));           
                }
            }, 
            items: {
                <?php 
                $commandContextArray = Arr::sortBy('ORDER_NUM', $this->dataViewProcessCommand['commandContext'], 'asc');
                foreach ($commandContextArray as $cm => $row) {
                    if (isset($row['STANDART_ACTION'])) {
                        if ($row['STANDART_ACTION'] == 'criteria') {
                            echo '"' . $cm.$row['BATCH_NUMBER'] . '": {'
                            . 'name: "' . $this->lang->line($row['PROCESS_NAME']) . '", '
                            . 'icon: "' . str_replace('fa-', '', $row['ICON_NAME']) . '", '
                            . 'callback: function(key, options) {'
                            . 'transferProcessCriteria(\'' . $this->metaDataId . '\', \'' . $row['BATCH_NUMBER'] . '\', \'context\', options.$trigger, {callerType: \''.$this->metaDataCode.'\'});'
                            . '}'
                            . '},';
                        } elseif ($row['STANDART_ACTION'] == 'processCriteria') {
                            echo '"' . $row['PROCESS_META_DATA_ID'] . '": {'
                            . 'name: "' . $this->lang->line($row['PROCESS_NAME']) . '", '
                            . 'icon: "' . str_replace('fa-', '', $row['ICON_NAME']) . '", '
                            . 'callback: function(key, options) {'
                            . 'transferProcessAction(\'' . $row['PASSWORD_PATH'] . '\', \'' . $this->metaDataId . '\', \'' . $row['PROCESS_META_DATA_ID'] . '\', \'' . $row['META_TYPE_ID'] . '\', \'processCriteria\', options.$trigger, {callerType: \''.$this->metaDataCode.'\'}, \'\');'
                            . '}'
                            . '},';
                        } else {
                            echo '"' . $row['STANDART_ACTION'] . '": {name: "' . $this->lang->line($row['PROCESS_NAME']) . '", icon: "' . str_replace("fa-", "", $row['ICON_NAME']) . '"},';
                        }
                    } else {
                        echo '"' . $row['PROCESS_META_DATA_ID'] . '": {'
                            . 'name: "' . $this->lang->line($row['PROCESS_NAME']) . '", '
                            . 'icon: "' . str_replace('fa-', '', $row['ICON_NAME']) . '", '
                            . 'callback: function(key, options) {'
                            . 'transferProcessAction(\'' . $row['PASSWORD_PATH'] . '\', \'' . $this->metaDataId . '\', \'' . $row['PROCESS_META_DATA_ID'] . '\', \'' . $row['META_TYPE_ID'] . '\', \'grid\', options.$trigger, {callerType: \''.$this->metaDataCode.'\'}, \'\');'
                            . '}'
                            . '},';
                    }
                } 
                ?>
            }
        });
        <?php } ?>
        
        <?php echo $this->dvScripts['scripts']; ?>
        
        dv_search_<?php echo $this->metaDataId; ?>.on("change", "select.linked-combo", function(){
            var _this = $(this);
            var _outParam = _this.attr("data-out-param");

            var _outParamSplit = _outParam.split("|");
            for (var i = 0; i < _outParamSplit.length; i++) {

                var selfParam = _outParamSplit[i];
                var _cellSelect = dv_search_<?php echo $this->metaDataId; ?>.find("select[data-path='" + selfParam + "']");

                if (_cellSelect.length) {
                    var _inParam = _cellSelect.attr("data-in-param");
                    var _inParamSplit = _inParam.split("|");
                    var _inParams = "";
                    for (var j = 0; j < _inParamSplit.length; j++) {
                        var _lastCombo = dv_search_<?php echo $this->metaDataId; ?>.find("select[data-path='" + _inParamSplit[j] + "']");
                        if (_lastCombo.length) {
                            if (_lastCombo.val() !== '') {
                                _inParams += _inParamSplit[j] + '=' + encodeURIComponent(_lastCombo.val()) + '&';
                            }
                        } else {
                            var _lastCombo = dv_search_<?php echo $this->metaDataId; ?>.find("input[data-path='" + _inParamSplit[j] + "']");
                            if (_lastCombo.length) {
                                if (_lastCombo.val() !== "") {
                                    _inParams += _inParamSplit[j] + '=' + encodeURIComponent(_lastCombo.val()) + '&';
                                }
                            }
                        }
                    }
                }

                if (_inParams !== "") {
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
                            $("option:gt(0)", _cellSelect).remove();
                            var comboData = dataStr[selfParam];
                            $.each(comboData, function () {
                                _cellSelect.append($("<option />").val(this.META_VALUE_ID).text(this.META_VALUE_NAME));
                            });
                            _cellSelect.trigger("change");

                            Core.initSelect2(_cellSelect);
                            Core.unblockUI();
                        },
                        error: function () { alert("Error"); }
                    });
                } else {
                    _cellSelect.select2('val', '');
                    _cellSelect.select2('disable');
                    $("option:gt(0)", _cellSelect).remove();
                    Core.initSelect2(_cellSelect);
                }
            }
        });
    
        dv_search_<?php echo $this->metaDataId; ?>.on("change", "input.linked-combo", function(){
            var _this = $(this);
            var _outParam = _this.attr("data-out-param");
            var _outParamSplit = _outParam.split("|");

            for (var i = 0; i < _outParamSplit.length; i++) {
                var selfParam = _outParamSplit[i];
                var _cellSelect = dv_search_<?php echo $this->metaDataId; ?>.find("select[data-path='" + selfParam + "']");

                if (_cellSelect.length === 0) {
                    var _cellInp = dv_search_<?php echo $this->metaDataId; ?>.find("input[data-path='" + selfParam + "']");

                    if (_this.val().length > 0 && _cellInp.length > 0) {
                        _cellInp.closest(".meta-autocomplete-wrap").find("input").removeAttr("disabled");
                        _cellInp.parent().find("button").removeAttr("disabled");
                    }

                } else {

                    var _inParams = "";

                    if (_cellSelect.length) {
                        if (typeof _cellSelect.attr("data-in-param") !== 'undefined') {
                            var _inParam = _cellSelect.attr("data-in-param");
                            var _inParamSplit = _inParam.split("|");

                            for (var j = 0; j < _inParamSplit.length; j++) {
                                var _lastCombo = dv_search_<?php echo $this->metaDataId; ?>.find("input[data-path='" + _inParamSplit[j] + "']");
                                if (_lastCombo.length) {
                                    if (_lastCombo.val() !== "") {
                                        _inParams += _inParamSplit[j] + '=' + encodeURIComponent(_lastCombo.val()) + '&';
                                    }
                                } else {
                                    var _lastCombo = dv_search_<?php echo $this->metaDataId; ?>.find("input[data-path='" + _inParamSplit[j] + "']");
                                    if (_lastCombo.length) {
                                        if (_lastCombo.val() !== "") {
                                            _inParams += _inParamSplit[j] + '=' + encodeURIComponent(_lastCombo.val()) + '&';
                                        }
                                    }
                                }
                            }
                        }
                    }

                    if (_inParams !== "") {
                        $.ajax({
                            type: 'post',
                            url: 'mdobject/bpLinkedCombo',
                            data: {inputMetaDataId: '<?php echo $this->metaDataId; ?>', selfParam: selfParam, inputParams: _inParams},
                            dataType: "json",
                            async: false,
                            beforeSend: function () {
                                Core.blockUI({
                                    animate: true
                                });
                            },
                            success: function (dataStr) {
                                if (_cellSelect.hasClass("select2")) {
                                    _cellSelect.select2('val', '');
                                    _cellSelect.select2('enable');
                                } else {
                                    _cellSelect.val('');
                                    _cellSelect.removeAttr('disabled');
                                }
                                $("option:gt(0)", _cellSelect).remove();
                                var comboData = dataStr[selfParam];
                                _cellSelect.addClass("data-combo-set");
                                $.each(comboData, function () {
                                    _cellSelect.append($("<option />").val(this.META_VALUE_ID).text(this.META_VALUE_NAME));
                                });
                                Core.initSelect2(_cellSelect);
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
                        Core.initSelect2(_cellSelect);
                    }
                }
            }
        });
        
        if ($(".height-dynamic", windowId_<?php echo $this->metaDataId; ?>).length) {
            $(".height-dynamic", windowId_<?php echo $this->metaDataId; ?>).css({
                'height': $(window).height() - $(".height-dynamic", windowId_<?php echo $this->metaDataId; ?>).offset().top - 40,
                'overflow-y': 'auto',
                'padding-right': '8px'
            });
        }
        
        $(windowId_<?php echo $this->metaDataId; ?>).off('click').on('click', '.card > .card-header > .caption.card-collapse.dataview', function(e) {
            var $this = $(this);
            var $thisParent = $this.parent();
            var $el = $this.closest(".card").children(".card-body");
            var $element = $thisParent.find("a.tool-collapse");
            if ($element.hasClass("collapse")) {
                $element.removeClass("collapse").addClass("expand");
                $el.css({'display': 'none'}).addClass("display-none");
            } else {
                $element.removeClass("expand").addClass("collapse");
                $el.css({'display': ''}).removeClass("display-none");
            }
        });
        
        $("button.dataview-default-filter-btn", "#object-value-list-<?php echo $this->metaDataId; ?>").on("click", function() { 

            var _this = $(this);

            if ($("input[name='mandatoryNoSearch']", 'form.mandatory-criteria-form-<?php echo $this->metaDataId; ?>').is(':checked')) {
                var defaultCriteriaData = $("div#object-value-list-<?php echo $this->metaDataId; ?> form#default-criteria-form").serialize();
            } else {
                if (_this.closest('div.package-meta-tab').attr('data-realpack-id')) {
                    var $packageId = _this.closest('div.package-meta-tab').attr('data-realpack-id');
                    var defaultCriteriaData = $('#package-meta-' + $packageId).find("form.package-criteria-form-" + $packageId + '_<?php echo $this->metaDataId; ?>, form.mandatory-criteria-form-<?php echo $this->metaDataId; ?>').serialize();
                    defaultCriteriaData += $('body').find('form.adv-criteria-form-<?php echo $this->metaDataId; ?>').serialize();
                } else {
                    var defaultCriteriaData = $("div#object-value-list-<?php echo $this->metaDataId; ?> form#default-criteria-form, form.mandatory-criteria-form-<?php echo $this->metaDataId; ?>").serialize();
                }
            }
            
            var dvSearchParam = {
                metaDataId: '<?php echo $this->metaDataId; ?>',
                defaultCriteriaData: defaultCriteriaData, 
                workSpaceId: '<?php echo $this->workSpaceId; ?>', 
                workSpaceParams: '<?php echo $this->workSpaceParams; ?>', 
                uriParams: '<?php echo $this->uriParams; ?>', 
                drillDownDefaultCriteria: '<?php echo isset($this->drillDownDefaultCriteria) ? $this->drillDownDefaultCriteria : ''; ?>'
            };
            
            if ($('#dataViewStructureTreeView_<?php echo $this->metaDataId; ?>', windowId_<?php echo $this->metaDataId; ?>).length > 0) {
                var chosenCategory = $('#treeCategory', windowId_<?php echo $this->metaDataId; ?>).val();
                var filtedField = filterFieldList_<?php echo $this->metaDataId; ?>[chosenCategory];
                if (typeof $('#dataViewStructureTreeView_<?php echo $this->metaDataId; ?>', windowId_<?php echo $this->metaDataId; ?>).jstree('get_selected')[0] !== 'undefined') {
                    var cardFilterDataVar = filtedField + '=' + $('#dataViewStructureTreeView_<?php echo $this->metaDataId; ?>', windowId_<?php echo $this->metaDataId; ?>).jstree('get_selected')[0];
                    dvSearchParam['cardFilterData'] = cardFilterDataVar;
                }
            }
            
            dataViewLoadByElement(objectdatagrid_<?php echo $this->metaDataId; ?>, dvSearchParam);

            $(".search-sidebar-<?php echo $this->metaDataId; ?>", "#object-value-list-<?php echo $this->metaDataId; ?>").trigger('click');
        });
        
        $("button.dataview-default-filter-reset-btn", "#object-value-list-<?php echo $this->metaDataId; ?>").on("click", function() {
            var $this = $(this);
            var $thisForm = $this.closest('form#default-criteria-form');
            
            $thisForm.find("input[type=text], select:not(.right-radius-zero)")
                .not("input[name='inputMetaDataId']").val('');
            $thisForm.find('select.select2').select2('val', '');
            
            $("button.dataview-default-filter-btn", "#object-value-list-<?php echo $this->metaDataId; ?>").trigger('click');
        });
        
        $("input[name='mandatoryNoSearch']", "#object-value-list-<?php echo $this->metaDataId; ?>").on("click", function() {
            var _this = $(this);
            var _thisForm = _this.closest("form#default-mandatory-criteria-form");
            
            if (_this.is(':checked')) {
                
                _thisForm.find("input[type=text]").attr('readonly', 'readonly');
                _thisForm.find("select.select2").select2('readonly', true);
                _thisForm.find("button").attr('disabled', 'disabled');
                
                var dvSearchParam = {
                    metaDataId: '<?php echo $this->metaDataId; ?>',
                    defaultCriteriaData: $("div#object-value-list-<?php echo $this->metaDataId; ?> form#default-criteria-form").serialize()
                };
                if ($('#dataViewStructureTreeView_<?php echo $this->metaDataId; ?>', windowId_<?php echo $this->metaDataId; ?>).length > 0) {
                    var chosenCategory = $('#treeCategory', windowId_<?php echo $this->metaDataId; ?>).val();
                    var filtedField = filterFieldList_<?php echo $this->metaDataId; ?>[chosenCategory];
                    if (typeof $('#dataViewStructureTreeView_<?php echo $this->metaDataId; ?>', windowId_<?php echo $this->metaDataId; ?>).jstree('get_selected')[0] !== 'undefined') {
                        var cardFilterDataVar = filtedField + '=' + $('#dataViewStructureTreeView_<?php echo $this->metaDataId; ?>', windowId_<?php echo $this->metaDataId; ?>).jstree('get_selected')[0];
                        dvSearchParam['cardFilterData'] = cardFilterDataVar;
                    }
                }   
                
                dataViewLoadByElement(objectdatagrid_<?php echo $this->metaDataId; ?>, dvSearchParam);
            
            } else {
                _thisForm.find("input[type=text]").removeAttr('readonly');
                _thisForm.find("button").removeAttr('disabled');
                _thisForm.find("select.select2").select2('readonly', false);
            }
        });
        
        $(".search-sidebar-<?php echo $this->metaDataId; ?>").on("click", function () {
            var _thisToggler = $(this);
            var topsidebar = $(".top-sidebar", "#object-value-list-<?php echo $this->metaDataId; ?>");
            var topsidebarstatus = topsidebar.attr("data-status");
            if (topsidebarstatus === "closed") {
                topsidebar.find(".glyphicon-chevron-left").parent().hide();
                topsidebar.find(".glyphicon-chevron-right").hide();
                topsidebar.attr('style', 'top:' + ($(this).height() + 21) + 'px;');
                topsidebar.find(".top-sidebar-content").show();
                topsidebar.find(".glyphicon-chevron-left").parent().fadeIn("slow");
                topsidebar.find(".glyphicon-chevron-left").fadeIn("slow");
                topsidebar.attr('data-status', 'opened');
                _thisToggler.addClass("sidebar-opened");
            } else {
                topsidebar.find(".glyphicon-chevron-left").hide();
                topsidebar.find(".glyphicon-chevron-left").parent().hide();
                topsidebar.find(".top-sidebar-content").hide();
                topsidebar.find(".glyphicon-chevron-right").parent().fadeIn("slow");
                topsidebar.find(".glyphicon-chevron-right").fadeIn("slow");
                topsidebar.attr('data-status', 'closed');
                _thisToggler.removeClass("sidebar-opened");
            }
        });
        
        $('.workflow-btn-<?php echo $this->metaDataId ?>').on('click', function () {
            $('.workflow-dropdown-<?php echo $this->metaDataId ?>').empty();
            var rows = getDataViewSelectedRows('<?php echo $this->metaDataId ?>');
            if (rows.length === 0) {
                alert("Та мөр сонгоно уу!");
                return;
            }
            
            var row = rows[0];
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
                            var realWfmName = '';

                            if (typeof row.id !== 'undefined') {
                                rowId = row.id;
                            }
                            $.each(response.data, function (i, v) {
                                var advancedCriteria = '';
                                if (typeof v.advancedCriteria !== "undefined" && v.advancedCriteria !== null) {
                                    advancedCriteria = ' data-advanced-criteria="' + v.advancedCriteria.replace(/\"/g, '') + '"';
                                }
                                realWfmName = v.wfmstatusname;
                                
                                if (typeof v.wfmusedescriptionwindow != 'undefined' && v.wfmusedescriptionwindow == '0' && typeof v.wfmuseprocesswindow != 'undefined' && v.wfmuseprocesswindow == '0') {
                                    $('.workflow-dropdown-<?php echo $this->metaDataId ?>').append('<li><a href="javascript:;" ' + advancedCriteria + ' onclick="changeWfmStatusId(this, \''+v.wfmstatusid+'\', \'<?php echo $this->metaDataId ?>\', \'<?php echo $this->refStructureId ?>\', \''+$.trim(v.wfmstatuscolor)+'\', \''+v.wfmstatusname+'\');" id="'+ v.wfmstatusid +'">'+ v.wfmstatusname +'</a></li>'); 
                                } else {
                                    if (typeof v.wfmstatusname != 'undefined' && v.wfmstatusname != '' && (v.wfmstatusprocessid == '' || v.wfmstatusprocessid == 'null' || v.wfmstatusprocessid == null)) {
                                        if (v.wfmisneedsign == '1') {
                                            $('.workflow-dropdown-<?php echo $this->metaDataId ?>').append('<li><a href="javascript:;" ' + advancedCriteria + ' onclick="beforeSignChangeWfmStatusId(this, \''+v.wfmstatusid+'\', \'<?php echo $this->metaDataId ?>\', \'<?php echo $this->refStructureId ?>\', \''+$.trim(v.wfmstatuscolor)+'\', \''+v.wfmstatusname+'\');" id="'+ v.wfmstatusid +'">'+ v.wfmstatusname +' <i class="fa fa-key"></i></a></li>'); 
                                        } else if (v.wfmisneedsign == '2') {
                                            $('.workflow-dropdown-<?php echo $this->metaDataId ?>').append('<li><a href="javascript:;" ' + advancedCriteria + ' onclick="beforeHardSignChangeWfmStatusId(this, \''+v.wfmstatusid+'\', \'<?php echo $this->metaDataId ?>\', \'<?php echo $this->refStructureId ?>\', \''+$.trim(v.wfmstatuscolor)+'\', \''+v.wfmstatusname+'\');" id="'+ v.wfmstatusid +'">'+ v.wfmstatusname +' <i class="fa fa-key"></i></a></li>'); 
                                        } else {
                                            $('.workflow-dropdown-<?php echo $this->metaDataId ?>').append('<li><a href="javascript:;" ' + advancedCriteria + ' onclick="changeWfmStatusId(this, \''+v.wfmstatusid+'\', \'<?php echo $this->metaDataId ?>\', \'<?php echo $this->refStructureId ?>\', \''+$.trim(v.wfmstatuscolor)+'\', \''+v.wfmstatusname+'\');" id="'+ v.wfmstatusid +'">'+ v.wfmstatusname +'</a></li>'); 
                                        }
                                    } else if (v.wfmstatusprocessid != '' || v.wfmstatusprocessid != 'null' || v.wfmstatusprocessid != null) {
                                        var wfmStatusCode = ('wfmstatuscode' in Object(v)) ? v.wfmstatuscode : ''; 
                                        if (v.wfmisneedsign == '1') {
                                            $('.workflow-dropdown-<?php echo $this->metaDataId ?>').append('<li><a href="javascript:;" ' + advancedCriteria + ' onclick="transferProcessAction(\'signProcess\', \'<?php echo $this->metaDataId ?>\', \''+v.wfmstatusprocessid+'\', \'<?php echo Mdmetadata::$businessProcessMetaTypeId; ?>\', \'toolbar\', this, {callerType: \'<?php echo $this->metaDataCode ?>\', isWorkFlow: true, wfmStatusId: \''+v.wfmstatusid+'\', wfmStatusCode: \''+wfmStatusCode+'\'}, \'dataViewId=<?php echo $this->metaDataId ?>&refStructureId=<?php echo $this->refStructureId; ?>&statusId='+v.wfmstatusid+'&statusName='+v.wfmstatusname+'&statusColor='+$.trim(v.wfmstatuscolor)+'&rowId='+rowId+'\');">'+v.wfmstatusname+' <i class="fa fa-key"></i></a></li>');
                                        } else if (v.wfmisneedsign == '2') {
                                            $('.workflow-dropdown-<?php echo $this->metaDataId ?>').append('<li><a href="javascript:;" ' + advancedCriteria + ' onclick="transferProcessAction(\'hardSignProcess\', \'<?php echo $this->metaDataId ?>\', \''+v.wfmstatusprocessid+'\', \'<?php echo Mdmetadata::$businessProcessMetaTypeId; ?>\', \'toolbar\', this, {callerType: \'<?php echo $this->metaDataCode ?>\', isWorkFlow: true, wfmStatusId: \''+v.wfmstatusid+'\', wfmStatusCode: \''+wfmStatusCode+'\'}, \'dataViewId=<?php echo $this->metaDataId ?>&refStructureId=<?php echo $this->refStructureId; ?>&statusId='+v.wfmstatusid+'&statusName='+v.wfmstatusname+'&statusColor='+$.trim(v.wfmstatuscolor)+'&rowId='+rowId+'\');">'+v.wfmstatusname+' <i class="fa fa-key"></i></a></li>');
                                        } else {
                                            $('.workflow-dropdown-<?php echo $this->metaDataId ?>').append('<li><a href="javascript:;" ' + advancedCriteria + ' onclick="transferProcessAction(\'\', \'<?php echo $this->metaDataId ?>\', \''+v.wfmstatusprocessid+'\', \'<?php echo Mdmetadata::$businessProcessMetaTypeId; ?>\', \'toolbar\', this, {callerType: \'<?php echo $this->metaDataCode ?>\', isWorkFlow: true, wfmStatusId: \''+v.wfmstatusid+'\', wfmStatusCode: \''+wfmStatusCode+'\'}, \'dataViewId=<?php echo $this->metaDataId ?>&refStructureId=<?php echo $this->refStructureId; ?>&statusId='+v.wfmstatusid+'&statusName='+v.wfmstatusname+'&statusColor='+$.trim(v.wfmstatuscolor)+'&rowId='+rowId+'\');">'+v.wfmstatusname+'</a></li>');
                                        }
                                    }    
                                }
                            });    
                        } 
                        
                        $('.workflow-dropdown-<?php echo $this->metaDataId ?>').append('<li><a href="javascript:;" onclick="seeWfmStatusForm(this, \'<?php echo $this->metaDataId ?>\');">'+plang.getDefault('wfm_log_history', 'Өөрчлөлтийн түүх харах')+'</a></li>');
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
        
        drawTree_<?php echo $this->metaDataId; ?>();
        
        $(".mandatory-criteria-form-<?php echo $this->metaDataId; ?>").on("keydown", 'input:not(.meta-autocomplete, .dateInit, .meta-name-autocomplete)', function (e) {
            if (e.which === 13) {
                var _this = $(this);
                var dvSearchParam = {
                    metaDataId: '<?php echo $this->metaDataId; ?>',
                    defaultCriteriaData: $("div#object-value-list-<?php echo $this->metaDataId; ?> form#default-criteria-form, form.mandatory-criteria-form-<?php echo $this->metaDataId; ?>").serialize()
                };
                if ($('#dataViewStructureTreeView_<?php echo $this->metaDataId; ?>', windowId_<?php echo $this->metaDataId; ?>).length > 0) {
                    var chosenCategory = $('#treeCategory', windowId_<?php echo $this->metaDataId; ?>).val();
                    var filtedField = filterFieldList_<?php echo $this->metaDataId; ?>[chosenCategory];
                    if (typeof $('#dataViewStructureTreeView_<?php echo $this->metaDataId; ?>', windowId_<?php echo $this->metaDataId; ?>).jstree('get_selected')[0] !== 'undefined') {
                        var cardFilterDataVar = filtedField + '=' + $('#dataViewStructureTreeView_<?php echo $this->metaDataId; ?>', windowId_<?php echo $this->metaDataId; ?>).jstree('get_selected')[0];
                        dvSearchParam['cardFilterData'] = cardFilterDataVar;
                    }
                }
                
                dataViewLoadByElement(objectdatagrid_<?php echo $this->metaDataId; ?>, dvSearchParam);
            }
        });
        
        $(".mandatory-criteria-form-<?php echo $this->metaDataId; ?>").on("changeDate", 'input.dateInit', function (e) {
            var _this = $(this);
            var dvSearchParam = {
                metaDataId: '<?php echo $this->metaDataId; ?>',
                defaultCriteriaData: $("div#object-value-list-<?php echo $this->metaDataId; ?> form#default-criteria-form, form.mandatory-criteria-form-<?php echo $this->metaDataId; ?>").serialize()
            };
            if ($('#dataViewStructureTreeView_<?php echo $this->metaDataId; ?>', windowId_<?php echo $this->metaDataId; ?>).length > 0) {
                var chosenCategory = $('#treeCategory', windowId_<?php echo $this->metaDataId; ?>).val();
                var filtedField = filterFieldList_<?php echo $this->metaDataId; ?>[chosenCategory];
                if (typeof $('#dataViewStructureTreeView_<?php echo $this->metaDataId; ?>', windowId_<?php echo $this->metaDataId; ?>).jstree('get_selected')[0] !== 'undefined') {
                    var cardFilterDataVar = filtedField + '=' + $('#dataViewStructureTreeView_<?php echo $this->metaDataId; ?>', windowId_<?php echo $this->metaDataId; ?>).jstree('get_selected')[0];
                    dvSearchParam['cardFilterData'] = cardFilterDataVar;
                }
            }            
            
            dataViewLoadByElement(objectdatagrid_<?php echo $this->metaDataId; ?>, dvSearchParam);
        });
        
        $(".mandatory-criteria-form-<?php echo $this->metaDataId; ?>").on("change", '.dropdownInput', function (e) {
            var _this = $(this);
            var dvSearchParam = {
                metaDataId: '<?php echo $this->metaDataId; ?>',
                defaultCriteriaData: $("div#object-value-list-<?php echo $this->metaDataId; ?> form#default-criteria-form, form.mandatory-criteria-form-<?php echo $this->metaDataId; ?>").serialize()
            };
            if ($('#dataViewStructureTreeView_<?php echo $this->metaDataId; ?>', windowId_<?php echo $this->metaDataId; ?>).length > 0) {
                var chosenCategory = $('#treeCategory', windowId_<?php echo $this->metaDataId; ?>).val();
                var filtedField = filterFieldList_<?php echo $this->metaDataId; ?>[chosenCategory];
                if (typeof $('#dataViewStructureTreeView_<?php echo $this->metaDataId; ?>', windowId_<?php echo $this->metaDataId; ?>).jstree('get_selected')[0] !== 'undefined') {
                    var cardFilterDataVar = filtedField + '=' + $('#dataViewStructureTreeView_<?php echo $this->metaDataId; ?>', windowId_<?php echo $this->metaDataId; ?>).jstree('get_selected')[0];
                    dvSearchParam['cardFilterData'] = cardFilterDataVar;
                }
            }      
            
            dataViewLoadByElement(objectdatagrid_<?php echo $this->metaDataId; ?>, dvSearchParam);
        });
        
        $(".mandatory-criteria-form-<?php echo $this->metaDataId; ?>").on("change", 'input.popupInit', function (e) {
            var _this = $(this);
            var dvSearchParam = {
                metaDataId: '<?php echo $this->metaDataId; ?>',
                defaultCriteriaData: $("div#object-value-list-<?php echo $this->metaDataId; ?> form#default-criteria-form, form.mandatory-criteria-form-<?php echo $this->metaDataId; ?>").serialize()
            };
            if ($('#dataViewStructureTreeView_<?php echo $this->metaDataId; ?>', windowId_<?php echo $this->metaDataId; ?>).length > 0) {
                var chosenCategory = $('#treeCategory', windowId_<?php echo $this->metaDataId; ?>).val();
                var filtedField = filterFieldList_<?php echo $this->metaDataId; ?>[chosenCategory];
                if (typeof $('#dataViewStructureTreeView_<?php echo $this->metaDataId; ?>', windowId_<?php echo $this->metaDataId; ?>).jstree('get_selected')[0] !== 'undefined') {
                    var cardFilterDataVar = filtedField + '=' + $('#dataViewStructureTreeView_<?php echo $this->metaDataId; ?>', windowId_<?php echo $this->metaDataId; ?>).jstree('get_selected')[0];
                    dvSearchParam['cardFilterData'] = cardFilterDataVar;
                }
            }   
            
            dataViewLoadByElement(objectdatagrid_<?php echo $this->metaDataId; ?>, dvSearchParam);
        });
        
        /*if ($("#objectDataView_<?php echo $this->metaDataId; ?>").find('.left-sidebar-content').length > 0) {
            var $leftSidebarContent = $("#objectDataView_<?php echo $this->metaDataId; ?>").find('.left-sidebar-content'),
                    $rightSidebarContent = $("#objectDataView_<?php echo $this->metaDataId; ?>").find('.right-sidebar-content-for-resize'),
                    totalContentWidth = $leftSidebarContent.width() + $rightSidebarContent.width();

            if (!$leftSidebarContent.hasClass("ui-resizable") && $().resizable) {
                $leftSidebarContent.resizable({
                    autoHide: true,
                    start: function (event, ui) {
                        $(this).addClass("highliteShape");
                    },
                    stop: function (event, ui) {
                        $(this).removeClass("highliteShape");
                    }
                });
                
                $leftSidebarContent.on("resizestop", function( event, ui ) {
                    $rightSidebarContent.css('width', totalContentWidth - $(event.target).width()+ 'px');
                });
            }
        }*/
        
        <?php
        if (isset($this->dvIgnoreToolbar) && $this->dvIgnoreToolbar == 1) {
        ?>
        $("div#object-value-list-<?php echo $this->metaDataId; ?>").find('.remove-type-<?php echo $this->metaDataId; ?>').css({display: 'none'});
        $("div#object-value-list-<?php echo $this->metaDataId; ?>").parent().find('.meta-toolbar').css({display: 'none'});
        <?php
        }
        ?>
        
        /*$("button.dataview-default-filter-btn", "#object-value-list-<?php echo $this->metaDataId; ?>").trigger('click');*/
    });
    
    function changeWfmStatusByRow_<?php echo $this->metaDataId ?>(elem) {
        
        var _this = $(elem);
        var selectedRowElem = _this.closest('.selected-row-link');
        var row = JSON.parse(selectedRowElem.attr('data-row-data'));

        $.ajax({
            type: 'post',
            url: 'mdobject/getWorkflowNextStatus',
            data: {metaDataId: '<?php echo $this->metaDataId ?>', dataRow: row},
            dataType: "json",
            beforeSend: function() {
                Core.blockUI({message: 'Loading...', boxed: true});
            },
            success: function(response) {
                if (response.status === 'success') {
                    
                    var dropdownMenu = _this.next('ul.dropdown-menu:eq(0)');
                    
                    $('.workflow-dropdown-<?php echo $this->metaDataId ?>').empty();
                    dropdownMenu.empty();
                    
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
                                dropdownMenu.append('<li><a href="javascript:;" ' + advancedCriteria + ' onclick="changeWfmStatusId(this, \''+v.wfmstatusid+'\', \'<?php echo $this->metaDataId ?>\', \'<?php echo $this->refStructureId ?>\', \''+$.trim(v.wfmstatuscolor)+'\', \''+v.wfmstatusname+'\');" id="'+ v.wfmstatusid +'">'+ v.wfmstatusname +'</a></li>'); 
                            } else {
                                if (typeof v.wfmstatusname != 'undefined' && v.wfmstatusname != '' && (v.wfmstatusprocessid == '' || v.wfmstatusprocessid == 'null' || v.wfmstatusprocessid == null)) {
                                    if (v.wfmisneedsign == '1') {
                                        dropdownMenu.append('<li><a href="javascript:;" ' + advancedCriteria + ' onclick="beforeSignChangeWfmStatusId(this, \''+v.wfmstatusid+'\', \'<?php echo $this->metaDataId ?>\', \'<?php echo $this->refStructureId ?>\', \''+$.trim(v.wfmstatuscolor)+'\', \''+v.wfmstatusname+'\');" id="'+ v.wfmstatusid +'">'+ v.wfmstatusname +' <i class="fa fa-key"></i></a></li>'); 
                                    } else if (v.wfmisneedsign == '2') {
                                        dropdownMenu.append('<li><a href="javascript:;" ' + advancedCriteria + ' onclick="beforeHardSignChangeWfmStatusId(this, \''+v.wfmstatusid+'\', \'<?php echo $this->metaDataId ?>\', \'<?php echo $this->refStructureId ?>\', \''+$.trim(v.wfmstatuscolor)+'\', \''+v.wfmstatusname+'\');" id="'+ v.wfmstatusid +'">'+ v.wfmstatusname +' <i class="fa fa-key"></i></a></li>'); 
                                    } else {
                                        dropdownMenu.append('<li><a href="javascript:;" ' + advancedCriteria + ' onclick="changeWfmStatusId(this, \''+v.wfmstatusid+'\', \'<?php echo $this->metaDataId ?>\', \'<?php echo $this->refStructureId ?>\', \''+$.trim(v.wfmstatuscolor)+'\', \''+v.wfmstatusname+'\');" id="'+ v.wfmstatusid +'">'+ v.wfmstatusname +'</a></li>'); 
                                    }
                                } else if (v.wfmstatusprocessid != '' || v.wfmstatusprocessid != 'null' || v.wfmstatusprocessid != null) {
                                    var wfmStatusCode = ('wfmstatuscode' in Object(v)) ? v.wfmstatuscode : ''; 
                                    if (v.wfmisneedsign == '1') {
                                        dropdownMenu.append('<li><a href="javascript:;" ' + advancedCriteria + ' onclick="transferProcessAction(\'signProcess\', \'<?php echo $this->metaDataId ?>\', \''+v.wfmstatusprocessid+'\', \'<?php echo Mdmetadata::$businessProcessMetaTypeId; ?>\', \'toolbar\', this, {callerType: \'<?php echo $this->metaDataCode ?>\', isWorkFlow: true, wfmStatusId: \''+v.wfmstatusid+'\', wfmStatusCode: \''+wfmStatusCode+'\'}, \'dataViewId=<?php echo $this->metaDataId ?>&refStructureId=<?php echo $this->refStructureId; ?>&statusId='+v.wfmstatusid+'&statusName='+v.wfmstatusname+'&statusColor='+$.trim(v.wfmstatuscolor)+'&rowId='+rowId+'\');">'+v.wfmstatusname+' <i class="fa fa-key"></i></a></li>');
                                    } else if (v.wfmisneedsign == '2') {
                                        dropdownMenu.append('<li><a href="javascript:;" ' + advancedCriteria + ' onclick="transferProcessAction(\'hardSignProcess\', \'<?php echo $this->metaDataId ?>\', \''+v.wfmstatusprocessid+'\', \'<?php echo Mdmetadata::$businessProcessMetaTypeId; ?>\', \'toolbar\', this, {callerType: \'<?php echo $this->metaDataCode ?>\', isWorkFlow: true, wfmStatusId: \''+v.wfmstatusid+'\', wfmStatusCode: \''+wfmStatusCode+'\'}, \'dataViewId=<?php echo $this->metaDataId ?>&refStructureId=<?php echo $this->refStructureId; ?>&statusId='+v.wfmstatusid+'&statusName='+v.wfmstatusname+'&statusColor='+$.trim(v.wfmstatuscolor)+'&rowId='+rowId+'\');">'+v.wfmstatusname+' <i class="fa fa-key"></i></a></li>');
                                    } else {
                                        dropdownMenu.append('<li><a href="javascript:;" ' + advancedCriteria + ' onclick="transferProcessAction(\'\', \'<?php echo $this->metaDataId ?>\', \''+v.wfmstatusprocessid+'\', \'<?php echo Mdmetadata::$businessProcessMetaTypeId; ?>\', \'toolbar\', this, {callerType: \'<?php echo $this->metaDataCode ?>\', isWorkFlow: true, wfmStatusId: \''+v.wfmstatusid+'\', wfmStatusCode: \''+wfmStatusCode+'\'}, \'dataViewId=<?php echo $this->metaDataId ?>&refStructureId=<?php echo $this->refStructureId; ?>&statusId='+v.wfmstatusid+'&statusName='+v.wfmstatusname+'&statusColor='+$.trim(v.wfmstatuscolor)+'&rowId='+rowId+'\');">'+v.wfmstatusname+'</a></li>');
                                    }
                                }    
                            }
                        });    
                    } 

                    dropdownMenu.append('<li><a href="javascript:;" onclick="seeWfmStatusForm(this, \'<?php echo $this->metaDataId ?>\');">'+plang.getDefault('wfm_log_history', 'Өөрчлөлтийн түүх харах')+'</a></li>');
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
    }
    
    function dataViewFolderChildList_<?php echo $this->metaDataId; ?>(dataViewId, refStructureId, folderId, dvSearchParam, uriParams, loader, parentId) {

        var filtedField = '', 
            dvSearchParam = typeof dvSearchParam === 'undefined' ? '' : dvSearchParam,
            uriParams = typeof uriParams === 'undefined' ? '<?php echo $this->uriParams; ?>' : uriParams;

        if (filterFieldList_<?php echo $this->metaDataId; ?>) {
            var filtedField = filterFieldList_<?php echo $this->metaDataId; ?>[refStructureId];
        }      
        if (folderId !== '') {
            $('#objectdatagrid-' + dataViewId).attr('folder-id', folderId);            
        }
        
        var postData = {
            dataViewId: dataViewId, 
            refStructureId: refStructureId, 
            folderId: folderId, 
            filtedField: filtedField, 
            photoField: '<?php echo $this->photoField; ?>', 
            iconField: '<?php echo $this->iconField; ?>', 
            defaultImage: '<?php echo $this->defaultImage; ?>', 
            name1: '<?php echo $this->name1; ?>', 
            name2: '<?php echo $this->name2; ?>', 
            name3: '<?php echo $this->name3; ?>', 
            name4: '<?php echo isset($this->name4) ? $this->name4 : ''; ?>', 
            name5: '<?php echo isset($this->name5) ? $this->name5 : ''; ?>', 
            name6: '<?php echo isset($this->name6) ? $this->name6 : ''; ?>', 
            name7: '<?php echo isset($this->name7) ? $this->name7 : ''; ?>', 
            name8: '<?php echo isset($this->name8) ? $this->name8 : ''; ?>', 
            name9: '<?php echo isset($this->name9) ? $this->name9 : ''; ?>', 
            name10: '<?php echo isset($this->name10) ? $this->name10 : ''; ?>', 
            body: '<?php echo isset($this->body) ? $this->body : ''; ?>', 
            comment: '<?php echo isset($this->comment) ? $this->comment : ''; ?>', 
            groupName: '<?php echo $this->groupName; ?>', 
            clickRowFunction: "<?php echo $this->clickRowFunction; ?>", 
            layoutTheme: '<?php echo $this->layoutTheme; ?>', 
            workSpaceId: '<?php echo $this->workSpaceId; ?>', 
            workSpaceParams: '<?php echo $this->workSpaceParams; ?>', 
            uriParams: uriParams, 
            dataGridOptionData: <?php echo json_encode($this->dataGridOptionData); ?>, 
            drillDownDefaultCriteria: '<?php echo isset($this->drillDownDefaultCriteria) ? $this->drillDownDefaultCriteria : ''; ?>',
            defaultCriteriaData: dvSearchParam.defaultCriteriaData
        };
        
        if (typeof _isRunAfterProcessSave !== 'undefined') {
            delete postData.isNotUseReport;

            if (_isRunAfterProcessSave) {
                postData.isNotUseReport = 1;
                _isRunAfterProcessSave = false;
            }
        }
        
        if (typeof parentId !== 'undefined' && parentId) {
            postData.parentId = parentId;
        }
            
        $.ajax({
            type: 'post',
            url: 'mdobject/dataViewFolderChildList',
            async: <?php echo $this->ajaxSync ?>,
            data: postData,
            beforeSend: function() {
                if (typeof loader === 'undefined') {
                    Core.blockUI({animate: true});
                }
            },
            success: function(data) {
                if ($(".div-objectdatagrid-<?php echo $this->metaDataId; ?>:last").closest('.ui-dialog').length) {
                    $(".div-objectdatagrid-<?php echo $this->metaDataId; ?>:last").closest('.ui-dialog').find(".div-objectdatagrid-<?php echo $this->metaDataId; ?>").empty().append(data);
                } else {
                    $(".div-objectdatagrid-<?php echo $this->metaDataId; ?>:last").empty().append(data);
                }
            }
        }).done(function() {
            if(typeof loader === 'undefined')
                Core.unblockUI();
        });
    }
    
    function dataViewFilterCardViewForm_<?php echo $this->metaDataId; ?>(elem) {
        var _div = $("div.dataview-search-filter", "#object-value-list-<?php echo $this->metaDataId; ?>");
        if (_div.hasClass("display-none")) {
            $.ajax({
                type: 'post',
                url: 'mdobject/dataViewSearchFilterForm',
                data: {metaDataId: '<?php echo $this->metaDataId; ?>'},
                success: function(data) {
                    _div.html(data);
                    _div.removeClass("display-none");
                },
                error: function() {
                    alert("Error");
                }
            });
        } else {
            _div.addClass("display-none");
            if ($("input#cardViewerFieldPath", "#object-value-list-<?php echo $this->metaDataId; ?>").val() != "") {
                objectdatagrid_<?php echo $this->metaDataId; ?>.datagrid('load', {
                    metaDataId: '<?php echo $this->metaDataId; ?>'
                });
            }
            $("input#cardViewerFieldPath", "#object-value-list-<?php echo $this->metaDataId; ?>").val('');
            $("input#cardViewerValue", "#object-value-list-<?php echo $this->metaDataId; ?>").val('');
        }
    }
    
    function dataViewFilterCardFieldPath_<?php echo $this->metaDataId; ?>(fieldPath, fieldValue, elem) {
        objectdatagrid_<?php echo $this->metaDataId; ?>.datagrid('load', {
            metaDataId: '<?php echo $this->metaDataId; ?>',
            cardFilterData: fieldPath + "=" + fieldValue
        });
        $("input#cardViewerFieldPath", "#object-value-list-<?php echo $this->metaDataId; ?>").val(fieldPath);
        $("input#cardViewerValue", "#object-value-list-<?php echo $this->metaDataId; ?>").val(fieldValue);
    }

    function drawTree_<?php echo $this->metaDataId; ?>() {
        var dataViewId = '<?php echo $this->metaDataId; ?>';
        var metaDataId = '';
        if ($('select#treeCategory', windowId_<?php echo $this->metaDataId; ?>).val() > 0) {
            metaDataId = $('select#treeCategory', windowId_<?php echo $this->metaDataId; ?>).val();
        }
        
        $('#treeContainer', windowId_<?php echo $this->metaDataId; ?>).html('<div id="dataViewStructureTreeView_<?php echo $this->metaDataId; ?>" class="tree-demo"></div>');
        var dataViewStructureTreeView_<?php echo $this->metaDataId; ?> = $('div#dataViewStructureTreeView_<?php echo $this->metaDataId; ?>', windowId_<?php echo $this->metaDataId; ?>);
        dataViewStructureTreeView_<?php echo $this->metaDataId; ?>.jstree({
            "core": {
                "themes": {
                    "responsive": true
                },
                "check_callback": true,
                "data": {
                    "url": function (node) {
                        return 'mdobject/getAjaxTree';
                    },
                    "data": function (node) {
                        return {'parent': node.id, 'dataViewId' : dataViewId, 'structureMetaDataId': metaDataId};
                    }
                }
            },
            "types": {
                "default": {
                    "icon": "icon-folder2 text-orange-300"
                }
            },
            "plugins": ["types", "cookies"]
        }).bind("select_node.jstree", function (e, data) {
            var nid = data.node.id === 'null' ? '' : data.node.id;
            selectDataViewByCategory_<?php echo $this->metaDataId; ?>(nid);
        }).bind('loaded.jstree', function (e, data) {
            setTimeout(function(){
                var $jstreeClicked = dataViewStructureTreeView_<?php echo $this->metaDataId; ?>.find('.jstree-clicked');
                if ($jstreeClicked.length) {
                    $jstreeClicked.focus();
                    $jstreeClicked.trigger('click');
                }
            }, 1);
        });
        
        var filtedField = filterFieldList_<?php echo $this->metaDataId; ?>[metaDataId];    
        $("input#cardViewerFieldPath", "#object-value-list-<?php echo $this->metaDataId; ?>").val(filtedField);
        
        if ($("input[name='mandatoryNoSearch']", 'form.mandatory-criteria-form-<?php echo $this->metaDataId; ?>').is(':checked')) {
            var defaultCriteriaData = $("div#object-value-list-<?php echo $this->metaDataId; ?> form#default-criteria-form").serialize();
        } else {
            var defaultCriteriaData = $("div#object-value-list-<?php echo $this->metaDataId; ?> form#default-criteria-form, form.mandatory-criteria-form-<?php echo $this->metaDataId; ?>").serialize();
        }
        
        var uriParams = undefined;
        // if (defaultCriteriaData == '') {
            <?php if (is_array($this->dvDefaultCriteria)) { ?>
                uriParams = '<?php echo json_encode($this->dvDefaultCriteria); ?>'
            <?php } ?>
        // }

        var dvSearchParam = {
            defaultCriteriaData: defaultCriteriaData
        };
        
        dataViewFolderChildList_<?php echo $this->metaDataId; ?>('<?php echo $this->metaDataId; ?>', metaDataId, '', dvSearchParam, uriParams);
            
        $('.explorer-sidebar-<?php echo $this->metaDataId; ?>').empty().addClass('d-none');
    }

    function selectDataViewByCategory_<?php echo $this->metaDataId; ?>(folderId, parentId) {
        
        if ($("input[name='mandatoryNoSearch']", 'form.mandatory-criteria-form-<?php echo $this->metaDataId; ?>').is(':checked')) {
            var defaultCriteriaData = $("div#object-value-list-<?php echo $this->metaDataId; ?> form#default-criteria-form").serialize();
        } else {
            var defaultCriteriaData = $("div#object-value-list-<?php echo $this->metaDataId; ?> form#default-criteria-form, form.mandatory-criteria-form-<?php echo $this->metaDataId; ?>").serialize();
        }
        
        var dvSearchParam = {
            defaultCriteriaData: defaultCriteriaData
        };
        
        if (folderId == 'all') {
            var chosenCategory = $('select#treeCategory', windowId_<?php echo $this->metaDataId; ?>).val();
            dataViewFolderChildList_<?php echo $this->metaDataId; ?>('<?php echo $this->metaDataId; ?>', chosenCategory, '', dvSearchParam);
            
            $("input#cardViewerValue", "#object-value-list-<?php echo $this->metaDataId; ?>").val('');
            $("input#treeFolderValue", "#object-value-list-<?php echo $this->metaDataId; ?>").val('');
            
        } else {
            globalFolderId = folderId;
            var chosenCategory = $('select#treeCategory', windowId_<?php echo $this->metaDataId; ?>).val();
            
            dataViewFolderChildList_<?php echo $this->metaDataId; ?>('<?php echo $this->metaDataId; ?>', chosenCategory, folderId, dvSearchParam, undefined, undefined, parentId);
            
            $("input#cardViewerValue", "#object-value-list-<?php echo $this->metaDataId; ?>").val(folderId);
            $("input#treeFolderValue", "#object-value-list-<?php echo $this->metaDataId; ?>").val(folderId);
        }
    }
    
    function explorerRefresh_<?php echo $this->metaDataId; ?>(elem, dvSearchParam, uriParams) {
        var $dv = $(windowId_<?php echo $this->metaDataId; ?>);
        var chosenCategory = $dv.find('select#treeCategory').val();
        var folderId = $dv.find("input#treeFolderValue").val();
        var $parentId = $dv.find('.dv-explorer-row-back[data-parentid]');
        
        if ($("input[name='mandatoryNoSearch']", 'form.mandatory-criteria-form-<?php echo $this->metaDataId; ?>').is(':checked')) {
            var defaultCriteriaData = $dv.find("form#default-criteria-form").serialize();
        } else {
            var defaultCriteriaData = $dv.find("form#default-criteria-form, form.mandatory-criteria-form-<?php echo $this->metaDataId; ?>").serialize();
        }

        var dvSearchParam = typeof dvSearchParam === 'undefined' ? { defaultCriteriaData: defaultCriteriaData } : dvSearchParam;
        
        if ($parentId.length && $parentId.attr('data-parentid')) {
            dataViewFolderChildList_<?php echo $this->metaDataId; ?>('<?php echo $this->metaDataId; ?>', chosenCategory, folderId, dvSearchParam, uriParams, undefined, $parentId.attr('data-parentid'));
        } else {
            dataViewFolderChildList_<?php echo $this->metaDataId; ?>('<?php echo $this->metaDataId; ?>', chosenCategory, folderId, dvSearchParam, uriParams);
        }
        
        if (!$('.explorer-sidebar-<?php echo $this->metaDataId; ?>').hasClass('d-none')) {
            var linkElement = $(".div-objectdatagrid-<?php echo $this->metaDataId; ?>").find('li.selected-row .selected-row-link');
            explorerSideBar_<?php echo $this->metaDataId; ?>(linkElement);
        }
    }
    
    <?php 
    if (!is_null($this->refreshTimer)) { ?>
        function explorerAutoRefresh_<?php echo $this->metaDataId; ?>(elem, dvSearchParam, uriParams) {
            if (document.hidden) {
                return false;
            }
            if ($('#objectdatagrid-<?php echo $this->metaDataId; ?>').length > 0) {
                var chosenCategory = $('select#treeCategory', windowId_<?php echo $this->metaDataId; ?>).val();
                var folderId = $("input#treeFolderValue", "#object-value-list-<?php echo $this->metaDataId; ?>").val();
                var dvSearchParam = typeof dvSearchParam === 'undefined' ? '' : dvSearchParam;
                dataViewFolderChildList_<?php echo $this->metaDataId; ?>('<?php echo $this->metaDataId; ?>', chosenCategory, folderId, dvSearchParam, uriParams, false);

                if (!$('.explorer-sidebar-<?php echo $this->metaDataId; ?>').hasClass('d-none')) {
                    var linkElement = $(".div-objectdatagrid-<?php echo $this->metaDataId; ?>").find('li.selected-row .selected-row-link');
                    explorerSideBar_<?php echo $this->metaDataId; ?>(linkElement);
                }
            }
        }
        setInterval(explorerAutoRefresh_<?php echo $this->metaDataId; ?>, <?php echo $this->refreshTimer; ?> * 1000);
   <?php } ?>           
    
    function explorerBackList_<?php echo $this->metaDataId; ?>(id, isParent) {
        
        if ($("input[name='mandatoryNoSearch']", 'form.mandatory-criteria-form-<?php echo $this->metaDataId; ?>').is(':checked')) {
            var defaultCriteriaData = $("div#object-value-list-<?php echo $this->metaDataId; ?> form#default-criteria-form").serialize();
        } else {
            var defaultCriteriaData = $("div#object-value-list-<?php echo $this->metaDataId; ?> form#default-criteria-form, form.mandatory-criteria-form-<?php echo $this->metaDataId; ?>").serialize();
        }
        
        var dvSearchParam = {defaultCriteriaData: defaultCriteriaData};
        var chosenCategory = $('select#treeCategory', windowId_<?php echo $this->metaDataId; ?>).val();
        
        if (id === '') {
            dataViewFolderChildList_<?php echo $this->metaDataId; ?>('<?php echo $this->metaDataId; ?>', chosenCategory, '', dvSearchParam);
        } else if (id && typeof isParent !== 'undefined' && isParent) {
            dataViewFolderChildList_<?php echo $this->metaDataId; ?>('<?php echo $this->metaDataId; ?>', chosenCategory, id, dvSearchParam);
        } else {
            $.ajax({
                type: 'post',
                url: 'mdobject/explorerBackList',
                data: {folderId: id, refStructureId: chosenCategory},
                dataType: 'json',
                success: function (data) {
                    var folderId = data.folderId;
                    if (folderId !== null) {
                        dataViewFolderChildList_<?php echo $this->metaDataId; ?>('<?php echo $this->metaDataId; ?>', chosenCategory, folderId, dvSearchParam);
                    } else {
                        dataViewFolderChildList_<?php echo $this->metaDataId; ?>('<?php echo $this->metaDataId; ?>', chosenCategory, '', dvSearchParam);
                    }
                    $("input#cardViewerValue", "#object-value-list-<?php echo $this->metaDataId; ?>").val(folderId);
                    $("input#treeFolderValue", "#object-value-list-<?php echo $this->metaDataId; ?>").val(folderId);
                },
                error: function () {
                    alert("Error");
                }
            });
        }
    }
    
    function explorerSideBar_<?php echo $this->metaDataId; ?>(elem) {
        var _this = $(elem);
        
        if (typeof _this.attr('data-row-data') === 'undefined') {
            return;
        }
        
        var rowData = _this.attr('data-row-data');
        var jsonObj = JSON.parse(rowData);
        
        _this.closest('ul').find('li.selected-row').removeClass('selected-row');
        _this.closest('li').addClass('selected-row');
        
        $.ajax({
            type: 'post',
            url: 'mdobject/explorerSidebar',
            data: {
                dataViewId: '<?php echo $this->metaDataId; ?>', 
                refStructureId: '<?php echo $this->refStructureId; ?>', 
                selectedRow: jsonObj
            },
            success: function (data) {
                $('.explorer-sidebar-<?php echo $this->metaDataId; ?>').empty().html(data).removeClass('d-none');
            },
            error: function () {
                alert("Error");
            }
        }).done(function(){
            Core.initFancybox($('.explorer-sidebar-<?php echo $this->metaDataId; ?>'));
        });
    }
    
    function clickItem_<?php echo $this->metaDataId; ?>(elem) {
        setTimeout(function() {
            var _this = $(elem);
            var dblclick = parseInt(_this.data('double'), 10);
            if (dblclick !== 2) {
                _this.closest('ul').find('li.selected-row input[type="checkbox"]').parent('span').removeClass('checked');
                _this.closest('li').find('input[type="checkbox"]').parent('span').addClass('checked');
                _this.closest('ul').find('li.selected-row').removeClass('selected-row');
                _this.closest('li').addClass('selected-row');
                
                <?php echo $this->clickRowFunction; ?>
            }        
        }, 300);        
    }
    
    function explorerSideBarFolder_<?php echo $this->metaDataId; ?>(elem) {
        setTimeout(function() {
            var _this = $(elem);
            var dblclick = parseInt(_this.data('double'), 10);
            if (dblclick !== 2) {
                $.ajax({
                    type: 'post',
                    url: 'mdobject/getFolderRecord',
                    data: {
                        refStructureId: _this.closest('ul').data('ref-structure-id'),
                        metaValueId: _this.data('row-id')
                    },
                    success: function (rowData) {
                        if (rowData !== null && rowData !== '') {
                            _this.closest('ul').find('li.selected-row').removeClass('selected-row');
                            _this.closest('li').addClass('selected-row');

                            $.ajax({
                                type: 'post',
                                url: 'mdobject/explorerSidebar',
                                data: {
                                    dataViewId: _this.closest('ul').data('ref-structure-id'),
                                    selectedRow: rowData
                                },
                                success: function (data) {
                                    $('.explorer-sidebar-<?php echo $this->metaDataId; ?>').empty().html(data).removeClass('d-none');
                                },
                                error: function () {
                                    alert("Error");
                                }
                            }).done(function(){
                                Core.initFancybox($('.explorer-sidebar-<?php echo $this->metaDataId; ?>'));
                            });
                        }
                    },
                    error: function () {
                        alert("Error");
                    }
                });
            }
        }, 300);
    }
    
    function dblClickItem_<?php echo $this->metaDataId; ?>(elem, metaValueId) {
        $(elem).data('double', 2);
        $('.explorer-sidebar-<?php echo $this->metaDataId; ?>').empty().addClass('d-none');
        selectDataViewByCategory_<?php echo $this->metaDataId; ?>(metaValueId);
    }

    function showFileViewer(elem) {
        var _this = $(elem);
        var rowData = _this.attr('data-row-data');
        var jsonObj = JSON.parse(rowData);
        var physicalpath = jsonObj.physicalpath;

        if (physicalpath) {
            _this.data('double', 2);
            var fileExt = physicalpath.split('.').pop().toLowerCase();
            dataViewFileViewer(elem, jsonObj.id, fileExt, jsonObj.filename, URL_APP+physicalpath);
        }
    }
    function explorerPrint_<?php echo $this->metaDataId; ?>(elem) {

        var $this = $(elem); 
        Core.blockUI({message: 'Loading...', boxed: true}); 

        setTimeout(function() {

            $.when(
                $.getScript('assets/custom/addon/plugins/html2canvas/dom-to-image.js')
            ).then(function () {

                var node = $this.closest('[data-meta-type="dv"]').find('#objectdatagrid-<?php echo $this->metaDataId; ?>')[0];

                domtoimage.toPng(node, {filter: htmlToImageTagFilter}).then(function(dataUrl) {  

                    var $printHtml = $('<div />', {html: '<img src="' + dataUrl + '" style="width:100%;"/>'});

                    $printHtml.printThis({
                        debug: false,
                        importCSS: false,
                        printContainer: false,
                        removeInline: false
                    });

                    $printHtml.remove();

                }).catch(function (error) {
                    console.error('oops, something went wrong!', error);
                });

                Core.unblockUI();

            }, function () {
                console.log('an error occurred somewhere');
                Core.unblockUI();
            }); 
        }, 100);
    }
</script>