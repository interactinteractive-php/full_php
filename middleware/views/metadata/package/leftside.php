<?php
if ($this->packageChildMetas) {
    
    $tabHead = array();
    $tabContent = '';
    
    foreach ($this->packageChildMetas as $k => $row) {
        
        $iconName = null;
        $activeClass = (issetParam($this->row['DEFAULT_META_ID']) == $row['META_DATA_ID']) ? 'active' : ((!issetParam($this->row['DEFAULT_META_ID']) && $k == 0) ? 'active' : '');
        $counttHtml = issetParam($this->counttDataview[$row['META_DATA_ID']]['countt']) ? '<span class="badge badge-success border-radius-50 float-right">'. $this->counttDataview[$row['META_DATA_ID']]['countt'] .'</span>' : '';
        $icon = 'assets/core/global/img/appmenu.png';
        
        if (isset($this->counttDataview[$row['META_DATA_ID']]) && $this->counttDataview[$row['META_DATA_ID']]) {
            
            $countRow = $this->counttDataview[$row['META_DATA_ID']];
            
            if (isset($countRow['icon']) && file_exists($countRow['icon'])) {
                $icon = $dataRow[$in]['icon'];
            } elseif (isset($countRow['iconname']) && $countRow['iconname']) {
                $iconName = $countRow['iconname'];
            } 
        }

        if ($iconName) {
            if (strpos($iconName, '<i class="') !== false) {
                $iconImg = $iconName;
            } else {
                $iconImg = '<i class="'.$iconName.' mr-1"></i>';
            }
        } else {
            $iconImg = '<img src="'.$icon.'" onerror="onBankImgError(this);">';
        }
        
        $liClass = (isset($countRow['colorclass']) && $countRow['colorclass']) ? ' bp-icon-color-'.$countRow['colorclass'] : '';
        
        $tabHead[] = '<li class="w-100 '.$activeClass.$liClass.'" data-dvid="'.$row['META_DATA_ID'].'" title="'. $this->lang->line((new Mdobject())->getNameByType($row['META_DATA_ID'], $row['META_TYPE_ID'], $row['META_DATA_NAME'])).'">'
                        .'<a href="#package-tab-'.$row['META_DATA_ID'].'" class="'.$activeClass.' item-icon-selection w-100" data-toggle="tab" onclick="packageRenderType(\''.$row['META_DATA_ID'].'\', \''.$row['META_TYPE_ID'].'\', this, \''.$this->metaDataId.'\', undefined, undefined, undefined, \'1\');" data-rendertype="leftside" data-packagecode="'.$this->packageCode.'" data-metadatacode="'.$row['META_DATA_CODE'].'">'
                            .'<div>'.$iconImg.'</div>' 
                            .'<p>'. $this->lang->line((new Mdobject())->getNameByType($row['META_DATA_ID'], $row['META_TYPE_ID'], $row['META_DATA_NAME'])).' '. $counttHtml .'</p>'
                        .'</a>'
                    .'</li>';
        
        $tabContent .= '<div class="tab-pane '.$activeClass.' package-meta-tab" id="package-tab-'.$row['META_DATA_ID'].'" data-realpack-id="'. $this->metaDataId .'"></div>';
    }
?>
<div class="card-body card-package-body pt0 page-content dvecommerce bg-white package-criteria<?php echo $this->metaDataId ?> page-content">
    <?php
    if (defined('CONFIG_TOP_MENU') && CONFIG_TOP_MENU) {
        
        $inlineStyle = $addonClass = '';
        if (!empty($this->row['TAB_BACKGROUND_COLOR'])) {
            $inlineStyle = 'background: none !important; background-color: '.$this->row['TAB_BACKGROUND_COLOR'].' !important;';
            
            if ($this->row['IS_IGNORE_MAIN_TITLE'] == '1') {
                $inlineStyle .= 'min-height: 40px;';
                $addonClass = ' no-title-package'; 
            }            
        } else {
            if ($this->row['IS_IGNORE_MAIN_TITLE'] == '1') {
                $addonClass = ' no-title-package'; 
            }
        }
    }
    ?>
    <button type="button" class="btn btn-light bg-gray bg-grey-c0 border-0 p-1 pl-2 pr-2 text-white meta-package-dv-collapse-btn"><i class="far fa-arrow-alt-to-left"></i></button>
    <div class="sidebar-sticky sidebar sidebar-light sidebar-secondary sidebar-component-left sidebar-expand-md height-scroll ecommerce-left-sidebar<?php echo (isset($this->leftSideClass) ? ' '.$this->leftSideClass : ''); ?>">
        <div class="sidebar-content p-2 pl0">
            <?php
            if ($this->title) {
            ?>
            <div style="padding-bottom: 9px; text-transform: uppercase; font-weight: 700;"><?php echo $this->title; ?></div>
            <?php
            }
            ?>
            <ul class="nav nav-tabs bp-icon-selection bg-white" data-countdvid="<?php echo $this->row['COUNT_META_DATA_ID']; ?>">
                <?php echo implode('', $tabHead); ?>
            </ul>
            <?php 
            if (isset($this->usedDefCriteria) && $this->usedDefCriteria) {
                foreach ($this->packageChildMetas as $metas) {
                    if ($metas['DEFAULT_CRITERIA']) {
                        echo '<form class="form-horizontal xs-form package-criteria-form-'. $this->metaDataId . '_' . $metas['META_DATA_ID'] .'" method="post" id="default-criteria-form">';
                        ?>
                        <ul class="nav nav-tabs nav-tabs-bottom nav-justified mb-0 pack_default_criteria <?php echo $metas['META_DATA_ID']; ?>_default_criteria d-none">
                            <li class="nav-item">
                                <a href="javascript:;" class="nav-link v2" data-toggle="tab">
                                    <span><i class="icon-search4 mr-1"></i></span>
                                    <span>Шүүлтүүр</span>
                                </a>
                            </li>
                        </ul>
                        <div class="pack_default_criteria <?php echo $metas['META_DATA_ID']; ?>_default_criteria d-none" data-criteria-metadataid="<?php echo $metas['META_DATA_ID']; ?>">
                            <?php
                            foreach ($metas['DEFAULT_CRITERIA'] as $param) { 
                                if (isset($param['IS_COUNTCARD']) && $param['IS_COUNTCARD'] === '1') { ?>
                                    <div class="btn-group hidden">
                                    <?php echo '<button class="btn btn-secondary btn-lg tab-lookupcriteria-' . $metas['META_DATA_ID'] . '" type="button" data-type="card" data-metaid="'.$metas['META_DATA_ID'].'" data-path="' . $param['META_DATA_CODE'] . '" data-type="' . $param['META_TYPE_CODE'] . '" data-theme="" data-selection="">' . $this->lang->line($param['META_DATA_NAME']) . '</button>'; ?>
                                    </div>                                    
                                    <?php
                                    echo '<h4 class="panel-title">'.$this->lang->line($param['META_DATA_NAME']).'</h4>';
                                    echo '<div class="leftsidebar_button mt4">'.
                                        '<div class="topbutton" id="tab-lookupdata-'.$metas['META_DATA_ID'].'">'.
                                            '<div class="leftbutton">'.
                                                '<i class="fa fa-left-arrow"></i>'.
                                            '</div>'.
                                        '</div>'.
                                    '</div>';
                                    continue;
                                } ?>                            
                                <div>
                                    <div class="mb-2 dv-criteria-row" id="accordion4-<?php echo $this->metaDataId; ?>">
                                        <h4 class="panel-title"><?php echo $param['LOOKUP_TYPE'] !== 'icon' ? $this->lang->line($param['META_DATA_NAME']) : '' ?></h4>
                                        <div id="collapse_3_<?php echo $param['META_DATA_CODE'].'_'.$this->metaDataId; ?>" class="mandatory-criteria-param-<?php echo $param['IS_MANDATORY_CRITERIA']; ?>" aria-expanded="true">
                                            <?php
                                            if (isset($this->permissionCriteriaData['metaValues'])) {
                                                $fillData = $this->permissionCriteriaData['metaValues'];
                                            } else {
                                                $fillData = (isset($this->fillPath) ? $this->fillPath : false);
                                            } 

                                            echo Mdwebservice::renderParamControl($this->metaDataId, $param, 'param['.$param['META_DATA_CODE'].']', $param['META_DATA_CODE'], $fillData, '', true);

                                            $metaDataCode = $param['META_TYPE_CODE'];
                                            $operand = '=';

                                            if ($metaDataCode != 'long' && $metaDataCode != 'integer' && $metaDataCode != 'date' && $metaDataCode != 'datetime' && $metaDataCode != 'boolean') {
                                                $operand = $param['DEFAULT_OPERATOR'] ? $param['DEFAULT_OPERATOR'] : 'like';
                                            }              

                                            echo Form::hidden(array('name' => 'criteriaCondition['.$param['META_DATA_CODE'].']','value' => $operand));
                                            ?>
                                        </div>
                                    </div>
                                </div>
                                <?php
                            }
                            if (Config::getFromCache('saveCustomerFilter') === '1') {
                            ?>
                            <div class="form-group">
                                <label for="criteriaTemplatesEcommerce" class="col-form-label text-left"><?php echo $this->lang->line('criteriaTemplateList') ?></label>
                                <div>
                                    <?php
                                    echo Form::select(array(
                                        'name' => 'criteriaTemplates',
                                        'id' => 'criteriaTemplatesEcommerce',
                                        'data-metadataid' => $metas['META_DATA_ID'],
                                        'text' => $this->lang->line('choose'),
                                        'class' => 'form-control form-control-sm dropdownInput select2 refresh-criteria-ptemplate refresh-criteria-template-' . $metas['META_DATA_ID'],
                                        'data' => array(),
                                        'op_value' => 'ID',
                                        'op_text' => 'NAME'
                                    ));
                                    ?>                                        
                                </div>
                            </div>
                            <?php 
                            }
                            if (isset($this->isFilterShowButton) && $this->isFilterShowButton) { ?>
                                <div class="row mt-3 mb-2 filter-button">
                                    <div class="col-6">
                                        <?php 
                                        echo Form::button(array(
                                            'class' => 'btn btn-danger btn-block dataview-default-filter-reset-btn', 
                                            'value' => $this->lang->line('clear_btn')
                                        )); 
                                        ?>
                                    </div>
                                    <div class="col-6">
                                        <?php 
                                        echo Form::button(array(
                                            'class' => 'btn btn-info btn-block dataview-default-filter-btn', 
                                            'value' => $this->lang->line('do_filter')
                                        )); 
                                        ?>
                                    </div>
                                </div>
                            <?php } ?>
                        </div>
                    </form>
            <?php 
                    } 
                }
            } 
            ?>
        </div>
    </div>
    <div class="w-100 tab-content dvecommerce-package content-wrapper overflow-hidden">
        <div class="clearfix w-100"></div>
        <?php echo $tabContent; ?>
    </div>
</div>
<script type="text/javascript">
    $(function() {
        <?php if (issetParam($this->row['DEFAULT_META_ID'])) { ?>
            if ($("div#package-meta-<?php echo $this->metaDataId; ?>").find('a[href="#package-tab-<?php echo $this->row['DEFAULT_META_ID'] ?>"]')) {
                var element = 'div#package-meta-<?php echo $this->metaDataId; ?> a[href="#package-tab-<?php echo $this->row['DEFAULT_META_ID'] ?>"]';
                
                packageRenderType('<?php echo $this->row['DEFAULT_META_ID'] ?>', '<?php echo $this->row['DEFAULT_META_TYPE_ID'] ?>', element, '<?php echo $this->metaDataId; ?>', undefined, undefined, '<?php echo $this->drillDownDefaultCriteria; ?>', '1');
            } else {
                $("div#package-meta-<?php echo $this->metaDataId; ?>").find("ul.nav-tabs > li:eq(0) > a").trigger("click");
            }
        <?php } else { ?>
            $("div#package-meta-<?php echo $this->metaDataId; ?>").find("ul.nav-tabs > li:eq(0) > a").trigger("click");
        <?php } ?>
        Core.initAjax($("div.package-criteria<?php echo $this->metaDataId ?>"));
        
        $("div.package-criteria<?php echo $this->metaDataId ?>").on('click', '.meta-package-dv-collapse-btn', function(){
            var $this = $(this), $nextElem = $this.next('.sidebar');
            if ($this.hasAttr('data-collapse')) {
                $this.removeAttr('data-collapse');
                $nextElem.removeClass('d-none');
                $this.find('i').removeClass('fa-arrow-alt-to-right').addClass('fa-arrow-alt-to-left');
            } else {
                $this.attr('data-collapse', '1');
                $this.find('i').removeClass('fa-arrow-alt-to-left').addClass('fa-arrow-alt-to-right');
                $nextElem.addClass('d-none');
            }
            $(window).resize();
        });
    });   

    $("div.package-criteria<?php echo $this->metaDataId ?>").on('change', 'select.linked-combo', function(){
        
        var _this = $(this);
        var _outParam = _this.attr('data-out-param');
        var _outParamSplit = _outParam.split('|');
        var getDataViewId = _this.closest('form').find('.pack_default_criteria:last').attr('data-criteria-metadataid');
        
        for (var i = 0; i < _outParamSplit.length; i++) {
            
            var _inParams = '';
            var selfParam = _outParamSplit[i];
            var _cellSelect = $("div.package-criteria<?php echo $this->metaDataId ?>").find('.package-criteria-form-<?php echo $this->metaDataId ?>_'+getDataViewId).find("select[data-path='" + selfParam + "']");
            
            if (_cellSelect.length) {
                var _inParam = _cellSelect.attr('data-in-param');
                var _inParamSplit = _inParam.split('|');
                
                for (var j = 0; j < _inParamSplit.length; j++) {
                    var _lastCombo = $("div.package-criteria<?php echo $this->metaDataId ?>").find("[data-path='" + _inParamSplit[j] + "']");
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
                    data: {inputMetaDataId: getDataViewId, selfParam: selfParam, inputParams: _inParams},
                    dataType: 'json',
                    async: false,
                    beforeSend: function () {
                        Core.blockUI({animate: true});
                    },
                    success: function (dataStr) {
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
                        
                        var comboData = dataStr[selfParam];
                        
                        $.each(comboData, function () {
                            _cellSelect.append($("<option />").val(this.META_VALUE_ID).text(this.META_VALUE_NAME));
                        });

                        Core.initSelect2(_cellSelect.parent());
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
    });
    
    $('select.linked-combo', $("div.package-criteria<?php echo $this->metaDataId ?>")).trigger('change');
    
    $("div.package-criteria<?php echo $this->metaDataId ?>").on('change', 'input.linked-combo', function(){
        var _this = $(this);
        var _outParam = _this.attr('data-out-param');
        var _outParamSplit = _outParam.split('|');
        var getDataViewId = _this.closest('form').find('.pack_default_criteria:last').attr('data-criteria-metadataid');

        for (var i = 0; i < _outParamSplit.length; i++) {
            var selfParam = _outParamSplit[i];
            var _cellSelect = $("div.package-criteria<?php echo $this->metaDataId ?>").find("select[data-path='" + selfParam + "']");

            if (_cellSelect.length === 0) {
                var _cellInp = $("div.package-criteria<?php echo $this->metaDataId ?>").find("input[data-path='" + selfParam + "']");

                if (_this.val().length > 0 && _cellInp.length > 0) {
                    _cellInp.closest('.meta-autocomplete-wrap').find('input').removeAttr('readonly disabled');
                    _cellInp.parent().find('button').removeAttr('disabled');
                }

            } else {

                var _inParams = '';

                for (var j = 0; j < _inParamSplit.length; j++) {
                    var _lastCombo = $("div.package-criteria<?php echo $this->metaDataId ?>").find("[data-path='" + _inParamSplit[j] + "']");
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
                        data: {inputMetaDataId: getDataViewId, selfParam: selfParam, inputParams: _inParams},
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
                            _cellSelect.addClass("data-combo-set");
                            
                            var comboData = dataStr[selfParam];
                            
                            $.each(comboData, function () {
                                _cellSelect.append($("<option />").val(this.META_VALUE_ID).text(this.META_VALUE_NAME));
                            });
                            
                            Core.initSelect2(_cellSelect.parent());
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

    $("body").on("click", '.package-criteria<?php echo $this->metaDataId ?> .dataview-default-filter-btn', function () {
        var $this = $(this);
        var $metadataId = $this.closest('div.pack_default_criteria').attr('data-criteria-metadataid');
        
        if (typeof $("#calendar-searchform-" + $metadataId) !== 'undefined' && $("#calendar-searchform-" + $metadataId).length > 0) {
            window['filterClick_' + $metadataId] = true;
            if (typeof window['calendar' + $metadataId] !== 'undefined') {
                window['calendar' + $metadataId].refetchEvents();
            } else {
                $('#objectdatagrid-' + $metadataId).fullCalendar('refetchEvents');
            }
        } else {
            if (typeof window['dv_search_' + $metadataId] !== 'undefined' && window['dv_search_' + $metadataId].find('.dataview-default-filter-btn').length > 0) {
                window['dv_search_' + $metadataId].find('.dataview-default-filter-btn').click();
            }
        };
    });

    $("body").on("click", '.package-criteria<?php echo $this->metaDataId ?> .dataview-default-filter-reset-btn', function (e) {
        var $this = $(this),
        $thisForm = $this.closest("form#default-criteria-form");
                
        var $metadataId = $this.closest('div.pack_default_criteria').attr('data-criteria-metadataid');
        
        $thisForm.find("input[type=text], input[type=hidden], textarea").not("input[name='inputMetaDataId'], select.right-radius-zero").val('');
        $thisForm.find("input[type=radio], input[type=checkbox]").removeAttr('checked');
        $thisForm.find("input[type=radio], input[type=checkbox]").closest('span.checked').removeClass('checked');
        $thisForm.find("select.select2").select2('val', '');
        $thisForm.find('.bp-icon-selection > li.active').removeClass('active');
        $thisForm.find('.btn.removebtn[data-lookupid]').hide();
        $thisForm.find('.btn[data-lookupid][data-choosetype][data-idfield][onclick]').text('..');

        var $thisFormAdv = $("body").find("div#dialog-dvecommerce-criteria-"+ $metadataId +" form#adv-criteria-form");

        if ($thisFormAdv) {
            $thisFormAdv.find("input[type=text], input[type=hidden], textarea").not("input[name='inputMetaDataId'], select.right-radius-zero").val('');
            $thisFormAdv.find("input[type=radio], input[type=checkbox]").removeAttr('checked');
            $thisFormAdv.find("input[type=radio], input[type=checkbox]").closest('span.checked').removeClass('checked');
            $thisFormAdv.find("select.select2").select2('val', '');
            $thisFormAdv.find('.bp-icon-selection > li.active').removeClass('active');
            $thisFormAdv.find('.btn.removebtn[data-lookupid]').hide();
            $thisFormAdv.find('.btn[data-lookupid][data-choosetype][data-idfield][onclick]').text('..');
        }
        
        if (typeof $("#calendar-searchform-" + $metadataId) !== 'undefined' && $("#calendar-searchform-" + $metadataId).length > 0) {
            window['filterClick_' + $metadataId] = true;
            if (typeof window['calendar' + $metadataId] !== 'undefined') {
                window['calendar' + $metadataId].refetchEvents();
            } else {
                $('#objectdatagrid-' + $metadataId).fullCalendar('refetchEvents');
            }
        } else {
            if (typeof window['dv_search_' + $metadataId] !== 'undefined' && window['dv_search_' + $metadataId].find('.dataview-default-filter-reset-btn').length > 0) {
                window['dv_search_' + $metadataId].find('.dataview-default-filter-reset-btn').click();
            }
        };
    });

    $(".package-criteria<?php echo $this->metaDataId ?>").on("keydown", '.pack_default_criteria input:not(.meta-autocomplete, .pack_default_criteria .dateInit, .pack_default_criteria .meta-name-autocomplete)', function (e) {
        if (e.which === 13) {
            var $this = $(this);
            var $metadataId = $this.closest('div.pack_default_criteria').attr('data-criteria-metadataid')
            setTimeout(function () {
                if (typeof $("#calendar-searchform-" + $metadataId) !== 'undefined' && $("#calendar-searchform-" + $metadataId).length > 0) {
                    window['filterClick_' + $metadataId] = true;
                    if (typeof window['calendar' + $metadataId] !== 'undefined') {
                        window['calendar' + $metadataId].refetchEvents();
                    } else {
                        $('#objectdatagrid-' + $metadataId).fullCalendar('refetchEvents');
                    }
                } else {
                    if (typeof window['dv_search_' + $metadataId] !== 'undefined' && window['dv_search_' + $metadataId].find('.dataview-default-filter-btn').length > 0) {
                        window['dv_search_' + $metadataId].find('.dataview-default-filter-btn').click();
                    }
                }
            }, 1);
        }
    });

    $(".package-criteria<?php echo $this->metaDataId ?>").on("changeDate", '.pack_default_criteria input.dateInit', function (e) {
        var $this = $(this);
        var $metadataId = $this.closest('div.pack_default_criteria').attr('data-criteria-metadataid')
        setTimeout(function () {
            if (typeof $("#calendar-searchform-" + $metadataId) !== 'undefined' && $("#calendar-searchform-" + $metadataId).length > 0) {
                window['filterClick_' + $metadataId] = true;
                if (typeof window['calendar' + $metadataId] !== 'undefined') {
                    window['calendar' + $metadataId].refetchEvents();
                } else {
                    $('#objectdatagrid-' + $metadataId).fullCalendar('refetchEvents');
                }
            } else {
                if (typeof window['dv_search_' + $metadataId] !== 'undefined' && window['dv_search_' + $metadataId].find('.dataview-default-filter-btn').length > 0) {
                    window['dv_search_' + $metadataId].find('.dataview-default-filter-btn').click();
                }
            }
        }, 1);
    });

    $(".package-criteria<?php echo $this->metaDataId ?>").on('change', '.pack_default_criteria input.popupInit, .pack_default_criteria input.combogridInit, .dropdownInput:not(.refresh-criteria-ptemplate)', function (e) {
        var $this = $(this);
        var $metadataId = $this.closest('div.pack_default_criteria').attr('data-criteria-metadataid')
        setTimeout(function () {
            if (typeof $("#calendar-searchform-" + $metadataId) !== 'undefined' && $("#calendar-searchform-" + $metadataId).length > 0) {
                window['filterClick_' + $metadataId] = true;
                if (typeof window['calendar' + $metadataId] !== 'undefined') {
                    window['calendar' + $metadataId].refetchEvents();
                } else {
                    $('#objectdatagrid-' + $metadataId).fullCalendar('refetchEvents');
                }
            } else {
                if (typeof window['dv_search_' + $metadataId] !== 'undefined' && window['dv_search_' + $metadataId].find('.dataview-default-filter-btn').length > 0) {
                    window['dv_search_' + $metadataId].find('.dataview-default-filter-btn').click();
                }
            }
        }, 1);
    });

    $(".package-criteria<?php echo $this->metaDataId ?>").on("click", '.pack_default_criteria a.button-inline', function (e) {
        var $this = $(this);
        var $metadataId = $this.closest('div.pack_default_criteria').attr('data-criteria-metadataid')
        setTimeout(function () {
            if (typeof $("#calendar-searchform-" + $metadataId) !== 'undefined' && $("#calendar-searchform-" + $metadataId).length > 0) {
                window['filterClick_' + $metadataId] = true;
                if (typeof window['calendar' + $metadataId] !== 'undefined') {
                    window['calendar' + $metadataId].refetchEvents();
                } else {
                    $('#objectdatagrid-' + $metadataId).fullCalendar('refetchEvents');
                }
            } else {
                if (typeof window['dv_search_' + $metadataId] !== 'undefined' && window['dv_search_' + $metadataId].find('.dataview-default-filter-btn').length > 0) {
                    window['dv_search_' + $metadataId].find('.dataview-default-filter-btn').click();
                }
            }
        }, 1);
    });
    
    $(".package-criteria<?php echo $this->metaDataId ?>").on("click", '.pack_default_criteria .bp-icon-selection li', function (e) {
        var $this = $(this);
        var $metadataId = $this.closest('div.pack_default_criteria').attr('data-criteria-metadataid');
        setTimeout(function () {
            if (typeof $("#calendar-searchform-" + $metadataId) !== 'undefined' && $("#calendar-searchform-" + $metadataId).length > 0) {
                window['filterClick_' + $metadataId] = true;
                if (typeof window['calendar' + $metadataId] !== 'undefined') {
                    window['calendar' + $metadataId].refetchEvents();
                } else {
                    $('#objectdatagrid-' + $metadataId).fullCalendar('refetchEvents');
                }
            } else {
                if (typeof window['dv_search_' + $metadataId] !== 'undefined' && window['dv_search_' + $metadataId].find('.dataview-default-filter-btn').length > 0) {
                    window['dv_search_' + $metadataId].find('.dataview-default-filter-btn').click();
                }
            }
        }, 1);
    });
    
    $('#criteriaTemplatesEcommerce', '.package-criteria<?php echo $this->metaDataId ?>').on('change', function() {
        var $this = $(this);
        var $metaDataId = $this.attr('data-metadataid');
        var $lastCriteriaRow = $('.adv-criteria-' + $metaDataId);
        var $params = {
            'metaDataId': $metaDataId, 
            'id': $(this).val(), 
            'isadvancedCriteria': '1',
            'viewtype': 'ecommerce'
        };
            
        if (!$lastCriteriaRow.length) {
            try {
                window['dvecommerceAdvancedCriteria' + $metaDataId](this, '1', 'searchfilter' + $metaDataId, $params);
            } catch (e) {
                console.log(e);
            }
            return;
        } 
        
        $.ajax({
            type: 'post',
            url: 'mdobject/getDataviewCriteriaTemplate',
            data: $params,
            dataType: 'html',
            beforeSend: function() {
                Core.blockUI({message: 'Loading...', boxed: true});
            },
            success: function(data) {    
                if (data !== '') {
                    $lastCriteriaRow.empty().append(data).promise().done(function () {
                        Core.initDVAjax($lastCriteriaRow);
                        if (typeof $("#calendar-searchform-" + $metadataId) !== 'undefined' && $("#calendar-searchform-" + $metadataId).length > 0) {
                            window['filterClick_' + $metadataId] = true;
                            if (typeof window['calendar' + $metadataId] !== 'undefined') {
                                window['calendar' + $metadataId].refetchEvents();
                            } else {
                                $('#objectdatagrid-' + $metadataId).fullCalendar('refetchEvents');
                            }
                        } else {
                            if (typeof window['dv_search_' + $metadataId] !== 'undefined' && window['dv_search_' + $metadataId].find('.dataview-default-filter-btn').length > 0) {
                                window['dv_search_' + $metadataId].find('.dataview-default-filter-btn').click();
                            }
                        }
                    });                        
                }
                Core.unblockUI();
            },
            error: function() { alert('Error'); }   
        });
    });
    
    $(document.body).on('select2-opening', '.package-criteria<?php echo $this->metaDataId ?> select.refresh-criteria-ptemplate', function(e, isTrigger) {
        var $this = $(this), 
            $relateElement = $this.prev('.select2-container:eq(0)');
        var $metaDataId = $this.attr('data-metadataid');
        var $thisval = $this.val();
        
        if (!$this.hasClass("data-combo-set")) {
            var select2 = $this.data('select2');
            $this.addClass('data-combo-set');
            
            Core.blockUI({target: $relateElement, animate: false, icon2Only: true});

            var comboDatas = [];
            $.ajax({
                type: 'post',
                async: false,
                url: 'mdobject/getDataviewTemplateData',
                data: {'metaDataId': $metaDataId},
                dataType: 'json',
                success: function(data) {
                    if (data.length) { 
                        $this.empty();
                        $this.append($('<option />').val('').text(plang.get('choose')));  

                        $.each(data, function(){
                            $this.append($("<option />")
                                .val(this.ID)
                                .text(this.NAME));
                            comboDatas.push({
                                id: this.ID,
                                text: this.NAME
                            });                     
                        });
                    }
                },
                error: function() { alert("Ajax Error!"); } 
            }).done(function(){
                Core.unblockUI($relateElement);
                $this.select2({results: comboDatas, closeOnSelect: false});
                if (typeof isTrigger === 'undefined' && !select2.opened()) {
                    $this.select2('open');
                    if ($thisval) {
                        $this.select2('val', $thisval);
                    }
                    $this.removeClass("data-combo-set");
                }
            });
        }
    });   
function packageCountRefresh($packageMeta, dvId, criteria) {
    var $countList = $packageMeta.find('ul[data-countdvid]');
    if ($countList.length && $countList.attr('data-countdvid')) {
        $.ajax({
            type: 'post',
            url: 'mdobject/dataViewDataGrid', 
            data: {metaDataId: $countList.attr('data-countdvid'), defaultCriteriaData: criteria},
            dataType: 'json',
            success: function(data) {
                if (isObject(data) && Object.keys(data).length && data.rows) {
                    var rows = data.rows;
                    for (var i in rows) {
                        if (rows[i]['metadataid'] == dvId) {
                            var $p = $countList.find('li[data-dvid="'+dvId+'"] p');
                            $p.find('span').remove();
                            $p.append('<span class="badge badge-success border-radius-50 pull-right">'+(rows[i]['countt'] ? rows[i]['countt'] : '0')+'</span>');
                            break;
                        }
                    }
                }
            }
        });
    }
}
</script>
<style type="text/css">
    .web-process .merge-column .merge-column-content .row {
        display: inherit;
    }
    .dvecommerce-package .ecommerce-left-sidebar {
        display: none;
    }
    .card-package-body .dvecommerce {
        margin: 0px;
    }
    .package-meta .dvecommerce .main-dataview-container {
        margin-top: 0px;
    }
    .meta-package-dv-collapse-btn {
        position: absolute;
        left: 241px;
        z-index: 91;
        width: 20px;
        padding-left: 4px!important;
    }
    .meta-package-dv-collapse-btn[data-collapse="1"] {
        left: -17px;
    }
</style>
<?php    
}
?>