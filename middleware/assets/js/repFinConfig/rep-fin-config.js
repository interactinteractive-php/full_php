var FinReportConfig = function(windowId) {
    this.windowId = windowId;
    this.repFinConfigWindowId = "#repFinConfigWindow_" + windowId;
    this.repFinConfigFormId = "#repFinConfigForm_" + windowId;
    this.selectedDeps = [];
    this.fields = [];
    this.allShowFields = [];
    this.frozenFields = [];
    this.globalEmpObj = [];
    this.dataGridId = "#repFinConfigDatagrid_" + windowId;
    this.activeField = '';
    this.activeIndex = '';
    this.createSheetLogDatas = [];
    this.javaCacheId = '';
    this.dataIndex = 0;
    this.isEditMode = false;
    this.multifilterParams = [];
    this.linkMetaDataId = '';
    
    this.getRowIndex = function(target) {
        var tr = $(target).closest('tr.datagrid-row');
        return parseInt(tr.attr('datagrid-row-index'));
    };
    
    this.getField = function(target) {
        return $(target).closest('td').attr('field');
    };
    
    this.selectInput = function(target) {
        this.activeIndex = this.getRowIndex(target);
        this.activeField = this.getField(target);
        $(target).select();
    };
};

FinReportConfig.prototype.initEventListener = function() {
    var _self = this;
    
    var dynamicHeight = $(window).height() - 270;
    $(_self.dataGridId).attr('height', dynamicHeight);
    
    $("#repFinTable", _self.repFinConfigWindowId).on("change", function (e) {
        $(_self.repFinConfigFormId).validate({errorPlacement: function () {}});
        
        if ($(_self.repFinConfigFormId).valid()) {
            _self.prepareDataGridStructure();
        }
    });
};

FinReportConfig.prototype.prepareDataGridStructure = function() {
    var _self = this;
    _self.fields = [];
    _self.allShowFields = [];
    
    $.ajax({
        type: 'post',
        url: 'mdreport/repFinConfigHeaderCtrl',
        data: {
            repFindId: $("#repFinTable", _self.repFinConfigWindowId).val()
        },
        dataType: "json",
        beforeSend: function () {
            var blockMsg = 'Түр хүлээнэ үү...';

            Core.blockUI({
                message: blockMsg,
                boxed: true
            });
        },
        success: function (data) {        
            if(data.status === 'success') {
            
                for(var i = 0; i < data.fields.length; i++) {
                    var manageField = {};
                    manageField.field = data.fields[i].META_DATA_CODE;
                    manageField.fieldid = data.fields[i].META_DATA_ID;
                    manageField.sortable = true;
                    /**
                     * auto detect datagrid column width
                     */
                    var tmpMetaDataNameLength = data.fields[i].META_DATA_NAME.length;
                    tmpMetaDataNameLength = tmpMetaDataNameLength * 5.8;       
                    if (tmpMetaDataNameLength > 100) {
                        manageField.width = tmpMetaDataNameLength;
                    } else {
                        manageField.width = 100;
                    }                    
                    if(data.fields[i].COLUMN_SIZE != null && data.fields[i].COLUMN_SIZE.trim() != '' && data.fields[i].COLUMN_SIZE > 100)
                        manageField.width = data.fields[i].COLUMN_SIZE;
                    
                    manageField.disable = data.fields[i].IS_DISABLE;
                    if(data.fields[i].LABEL_NAME !== null && data.fields[i].LABEL_NAME !== '')
                        manageField.title = data.fields[i].LABEL_NAME;
                    else
                        manageField.title = data.fields[i].META_DATA_NAME;
                    
                    if (data.fields[i].IS_DISABLE != '1' && data.fields[i].DATA_TYPE == 'boolean') {
                        manageField.align = 'center';
                        manageField.formatter = _self.sheetCheckFormatter;
                        manageField.width = tmpMetaDataNameLength / 5.8 * 5.6;
                        
                    } else if(data.fields[i].IS_DISABLE != '1' && (data.fields[i].DATA_TYPE == 'bigdecimal' || data.fields[i].DATA_TYPE == 'number' || data.fields[i].DATA_TYPE == 'long')) {
                        if(data.fields[i].LINK_META_DATA_ID != null) {
                            _self.linkMetaDataId = data.fields[i].LINK_META_DATA_ID;
                            manageField.formatter = _self.sheetDataviewFormatter;
                        } else
                            manageField.formatter = _self.sheetNumberFormatter;
                        
                    } else if(data.fields[i].IS_DISABLE == '1' && (data.fields[i].DATA_TYPE == 'bigdecimal' || data.fields[i].DATA_TYPE == 'number' || data.fields[i].DATA_TYPE == 'long')) {
                        manageField.formatter = _self.sheetDisableFormatter;
                    } else if(data.fields[i].IS_DISABLE != '1' && (data.fields[i].DATA_TYPE == 'string')) {
                        manageField.formatter = _self.sheetStringFormatter;
                    } else if(data.fields[i].IS_DISABLE == '1' && (data.fields[i].DATA_TYPE == 'string')) {
                        manageField.formatter = _self.sheetStringDisableFormatter;
                    } else {
                        manageField.formatter = _self.sheetOtherFormatter;
                    }
                    
                    if(data.fields[i].IS_HIDE !== '1' && data.fields[i].IS_SIDEBAR !== '1')
                        _self.fields.push(manageField);
                    
                    if(data.fields[i].IS_HIDE !== '1') {
                        _self.allShowFields.push(data.fields[i]);
                    }
                }
                
                _self.callDataGridStructure(isTrigger);
                
            }
            Core.unblockUI();
        },
        error: function () {
            Core.unblockUI();
            PNotify.removeAll();
            new PNotify({
                title: 'Алдаа',
                text: 'Амжилтгүй боллоо.',
                type: 'error',
                sticker: false
            });
        }
    });
};

FinReportConfig.prototype.callDataGridStructure = function(isTrigger) {
    var _self = this;
    
    $(_self.dataGridId).datagrid({
        url: 'mdsalary/getSalaryListWebservice',
        queryParams: {
            params: $(_self.repFinConfigFormId).serialize(),
            javaCacheId: _self.javaCacheId
        },
        fit: false,
        fitColumns: false,
        rownumbers: true,
        singleSelect: true,
        showFooter: true,
        pagination: true,
        remoteFilter: false,
        loadMsg: 'Ажилтны мэдээлэл ачааллаж байна, Түр хүлээнэ үү',
        pageSize: 50,
        pageList: [
            20, 50, 100, 150, 200
        ],
        remoteSort: true,
        frozenColumns: [_self.frozenFields],
        columns: [_self.fields],
        sortName: 'firstname',
        sortOrder: 'asc',
        onRowContextMenu: function (e, index, row) {
            e.preventDefault();
            _self.selectInput(e.target);
            $.contextMenu({
                selector: ".datagrid .datagrid-view .datagrid-view2 .datagrid-body .datagrid-row, .datagrid .datagrid-view .datagrid-view1 .datagrid-body .datagrid-row",
                items: {
                    "same": {
                        name: PL_0130, 
                        icon: "clone", 
                        callback: function(key, options) {
                            _self.copyFieldRowSheet();
                            $.contextMenu('destroy');
                        }
                    },
                    "copy": {
                        name: MET_99990771, 
                        icon: "files-o", 
                        callback: function(key, options) {
                            _self.copyFieldColumnSheet();
                            $.contextMenu('destroy');
                        }
                    },
                    "calculate": {
                        name: MET_99990770, 
                        icon: "calculator", 
                        callback: function(key, options) {
                            _self.calculateSalary();
                            $.contextMenu('destroy');
                        }
                    }
                }
            });            
        },
        onLoadSuccess: function(data) {
            _self.onLoadSuccessActions(data);
            if(isTrigger !== undefined) {
                $(".tool-collapse", _self.repFinConfigWindowId).trigger('click');
               isTrigger = undefined;
            }
        },
        onLoadError: function () {
            alert('Ажилтны мэдээлэл ачааллахад алдаа гарлаа!');
        }
    });
    
    var $filterWidth1 = 0,
        $filterWidth2 = 0,
        $filterWidth3 = 0;
    $(_self.repFinConfigWindowId).find('.datagrid').find('.datagrid-view1').find('.datagrid-htable tbody').find('tr').find('td').each(function () {
        var $td = $(this);
        if ($td.attr('field') === 'employeecode') {
            $filterWidth1 = $td.width();
        }
        if ($td.attr('field') === 'lastname') {
            $filterWidth2 = $td.width();
        }
        if ($td.attr('field') === 'firstname') {
            $filterWidth3 = $td.width();
        }
    });
    
    var filters = '<tr class="datagrid-filter-row">';
    $.each(_self.fields, function (k, v) {
        if (v['field'] != "delete") {
            filters += '<td field="' + v['field'] + '" fieldid="' + v['fieldid'] + '" style="' + (v['hidden'] === "true" ? "display:none;" : "") + '"><div class="datagrid-filter-c"><input type="text" class="datagrid-editable-input datagrid-filter" name="' + v['field'] + '" style="width: ' + (v['width'] - 17) + 'px"> <a href="javascript:;" class="multipleFilterClass"><i style="font-size: 12px;" class="fa fa-filter"></i></a> </div></td>';
        } else {
            filters += '<td field="' + v['field'] + '" style="' + (v['hidden'] === "true" ? "display:none;" : "") + '"></div></td>';
        }
    });
    filters += '</tr>';
    
    var filterHtml =
            '<tr class="datagrid-header-row datagrid-filter-row"><td field="_"></td><td field="employeecode"><div class="datagrid-filter-c"><input type="text" class="datagrid-editable-input datagrid-filter" name="employeecode" style="width:' +
            $filterWidth1 +
            'px;"></div></td><td field="lastname"><div class="datagrid-filter-c"><input type="text" class="datagrid-editable-input datagrid-filter" name="lastname" style="width:' +
            $filterWidth2 +
            'px;"></div></td><td field="firstname"><div class="datagrid-filter-c"><input type="text" class="datagrid-editable-input datagrid-filter" name="firstname" style="width:' +
            $filterWidth3 +
            'px;"></div></td></tr>';
    $(_self.repFinConfigWindowId).find('.datagrid').find('.datagrid-view1').find('.datagrid-htable').find('tbody').append(filterHtml);
    $(_self.repFinConfigWindowId).find('.datagrid').find('.datagrid-view2').find('.datagrid-htable').find('tbody').append(filters);
};

FinReportConfig.prototype.sheetNumberFormatter = function(val, row, index) {
    if(typeof val === 'undefined')
        return;
    
    var value = 0;
    if (val !== null && val !== '') {
        value = val;
    }
    value = value.toString();
    var html = '<input type="text" class="form-control text-right form-control-inline m-wrap form-control-sm salaryNumberFormat" onChange="window[\'salaryObj' + varWindowId + '\'].setSheetValue(this)" onClick="window[\'salaryObj' + varWindowId + '\'].selectInput(this)" value="' + value + '" />';
    
    if(typeof row.loggedvalues !== 'undefined') {
        if (row.loggedvalues.search(new RegExp(this.field, 'g')) !== -1) {
            html = '<input type="text" class="saved-log-data-cell form-control text-right form-control-inline m-wrap form-control-sm salaryNumberFormat" onChange="window[\'salaryObj' + varWindowId + '\'].setSheetValue(this)" onClick="window[\'salaryObj' + varWindowId + '\'].selectInput(this)" value="' + value + '" />'+
                    '<a class="btn btn-xs btn-secondary" title="Өөрчлөлтийн түүх харах" href="javascript:;" onclick="window[\'salaryObj' + varWindowId + '\'].getLogData(this)"><i style="color:#ff2929;" class="fa fa-history"></i></a>';
        }
    }
    
    return html;
};

FinReportConfig.prototype.sheetDataviewFormatter = function(val, row, index) {
    if(typeof val === 'undefined')
        return;
    
    var value = 0;
    if (val !== null && val !== '') {
        value = val;
    }
    value = value.toString();
    var html = '<a href="javascript:void(0)" onClick="window[\'salaryObj' + varWindowId + '\'].sheetDataviewCall('+row.employeekeyid+')">'+pureNumberFormat(value)+'</a>';
    
    if(typeof row.loggedvalues !== 'undefined') {
        if (row.loggedvalues.search(new RegExp(this.field, 'g')) !== -1) {
            html = '<a href="javascript:void(0)" onClick="window[\'salaryObj' + varWindowId + '\'].sheetDataviewCall('+row.employeekeyid+')">'+pureNumberFormat(value)+'</a>'+
                    '<a class="btn btn-xs btn-secondary" title="Өөрчлөлтийн түүх харах" href="javascript:;" onclick="window[\'salaryObj' + varWindowId + '\'].getLogData(this)"><i style="color:#ff2929;" class="fa fa-history"></i></a>';
        }
    }
    
    return html;
};

FinReportConfig.prototype.sheetDataviewCall = function(employeeKeyId) {
    var dialogname = $('#dialog-prl-call-process_'+varWindowId);
    var $dialogname = 'dialog-prl-call-process_'+varWindowId;
    var bookDate = '';
    var _self = this;

    $.ajax({
        type: 'post',
        url: 'mdobject/dataview/' + _self.linkMetaDataId + '&dv[employeeKeyId]=' + employeeKeyId,
        dataType: "html",
        success: function (data) {
            dialogname.empty().html(data);
            dialogname.dialog({
                cache: false,
                resizable: true,
                bgiframe: true,
                autoOpen: false,
                title: 'Дэлгэрэнгүй',
                width: '800',
                height: '400',
                modal: true,
                open: function () {
                    dialogname.show();
                    $('div[aria-describedby=' + $dialogname + '] .ui-dialog-buttonset').addClass(
                            "btn-group float-right");
                    $('div[aria-describedby=' + $dialogname + '] .ui-dialog-buttonset').find(
                            'button:eq(0)').addClass('btn blue-hoki btn-sm ml5');
                },
                close: function () {
                    dialogname.empty().dialog('close');
                },
                buttons: [
                    {text: plang.get('close_btn'), click: function () {
                            dialogname.empty().dialog('close');
                        }}
                ]
            });
            dialogname.dialog('open');
        }
    });
};

FinReportConfig.prototype.sheetStringFormatter = function(val, row, index) {
    if(typeof val === 'undefined' || val == null)
        return '';
    
    var html = '<input type="text" class="form-control text-left form-control-inline m-wrap form-control-sm" value="' + val + '" />';
    
    return html;
};

FinReportConfig.prototype.sheetStringDisableFormatter = function(val, row, index) {
    if(typeof val === 'undefined' || val == null)
        return '';
    
    var html = '<input type="text" readonly class="form-control text-left form-control-inline m-wrap form-control-sm" value="' + val + '" />';
    
    return html;
};

FinReportConfig.prototype.sheetCheckFormatter = function(val, row) {
    var value = 0;
    if(typeof val !== 'undefined' && val != null && val != 0)
        value = val;
    
    var html = '<input type="checkbox" '+(value == 1 ? 'checked' : '')+' onChange="window[\'salaryObj' + varWindowId + '\'].setCheckboxValue(this)" class="form-control form-control-inline m-wrap form-control-sm" style="width: 16px; height: 16px;" value="' + value + '" />';
    
    return html;
};

FinReportConfig.prototype.sheetDisableFormatter = function(val, row) {
    if(typeof val === 'undefined')
        return;
    
    var value = 0;
    if (val !== null && val !== '') {
        value = val;
    }
    value = value.toString();
    var html = '<input type="text" readonly class="form-control text-right form-control-inline m-wrap form-control-sm salaryNumberFormat" onChange="window[\'salaryObj' + varWindowId + '\'].setSheetValue(this)" onClick="window[\'salaryObj' + varWindowId + '\'].selectInput(this)" value="' + value + '" />';
    
    if(typeof row.loggedvalues !== 'undefined') {
        if (row.loggedvalues.search(new RegExp(this.field, 'g')) !== -1) {
            html = '<input type="text" readonly class="saved-log-data-cell form-control text-right form-control-inline m-wrap form-control-sm salaryNumberFormat" onChange="window[\'salaryObj' + varWindowId + '\'].setSheetValue(this)" onClick="window[\'salaryObj' + varWindowId + '\'].selectInput(this)" value="' + value + '" />'+
                    '<a class="btn btn-xs btn-secondary" title="Өөрчлөлтийн түүх харах" href="javascript:;" onclick="window[\'salaryObj' + varWindowId + '\'].getLogData(this)"><i style="color:#ff2929;" class="fa fa-history"></i></a>';
        }    
    }    
    
    return html;
};

FinReportConfig.prototype.sheetOtherFormatter = function(val, row) {    
    if(typeof val === 'undefined')
        return;
    
    var html = '<span>' + val + '</span>';
    
    return html;
};

FinReportConfig.prototype.sheetDeleteFormatter = function(val, row) {
    var html = '<a class="btn btn-xs red" href="javascript:;" title="Ажилтан устгах" onClick="window[\'salaryObj' + varWindowId + '\'].deleteEmployeeSheet(this, \''+ encodeURIComponent(JSON.stringify(row)) + '\')"><i class="fa fa-trash"></i></a>';
    return html;
};

FinReportConfig.prototype.sheetEmployeeInfoFormatter = function(val, row) {
    var html = '<a href="javascript:void(0)" title="Ажилтны мэдээлэл харах" onClick="window[\'salaryObj' + varWindowId + '\'].employeeInformation(this)">' + row.firstname + '</a>';
    return html;
};

FinReportConfig.prototype.setSheetValue = function(elem) {
    var _self = this;
    var changedValue = $(elem).val().replace(/\,/g, ""),
        index = _self.getRowIndex(elem), field = _self.getField(elem);
    
    $(elem).val(pureNumberFormat(changedValue));
    _self.globalEmpObj[index][field] = changedValue;
    
    if (!(index in _self.createSheetLogDatas))
        _self.createSheetLogDatas[index] = {};
    _self.createSheetLogDatas[index][field] = {};
    
    _self.createSheetLogDatas[index][field]['metadatacode'] = _self.activeField;    
    _self.createSheetLogDatas[index][field]['value'] = changedValue;    
    _self.createSheetLogDatas[index][field]['employeekeyid'] = _self.globalEmpObj[index]['employeekeyid'];    
};

FinReportConfig.prototype.setCheckboxValue = function(elem) {
    var _self = this;
    var changedValue = $(elem).is(':checked') ? 1 : 0,
        index = _self.getRowIndex(elem), field = _self.getField(elem);
    
    _self.globalEmpObj[index][field] = changedValue;
};

FinReportConfig.prototype.onLoadSuccessActions = function(data) {
    var _self = this, execFunc = false;
    
    if(Object.keys(data.rows).length) {
        _self.globalEmpObj = data.rows;
        _self.dataIndex = data.dataIndex;
        
        var pager = $(_self.dataGridId).datagrid('getPager');
        var popts = pager.pagination('options');
        var onSelectPage = popts.onSelectPage;
        popts.onSelectPage = function(pageNumber, pageSize){
            if(!execFunc) {
                execFunc = true;
                _self.saveSalarySheetByPager();
            }
            onSelectPage.call(this, pageNumber, pageSize);
        }                
        
        $('.salarySheetActions', _self.repFinConfigWindowId).removeClass('hidden');
        $('.saveSalarySheet', _self.repFinConfigWindowId).removeClass('hidden');
        $(".stoggler", _self.repFinConfigWindowId).removeClass("hidden");
    } else {
        $(_self.repFinConfigWindowId + ' .salarySheetActions').addClass('hidden');
        $(_self.repFinConfigWindowId + ' .saveSalarySheet').addClass('hidden');       
        $(".stoggler", _self.repFinConfigWindowId).addClass("hidden");

        if(data.status == 'error') {
            PNotify.removeAll();
            new PNotify({
                title: 'Анхааруулга',
                text: data.text,
                type: 'warning',
                sticker: false
            });
        } else {
            PNotify.removeAll();
            new PNotify({
                title: 'Info',
                text: 'Тохирох үр дүн олдсонгүй!',
                type: 'info',
                sticker: false
            });
        }
    }
    
    $('.datagrid-footer table tbody tr', _self.repFinConfigWindowId).find('td:last-child').find('a').remove();
    $('.salaryNumberFormat', _self.repFinConfigWindowId).autoNumeric('init', {aPad: true, mDec: 2, vMin: '-999999999999999999999999999999.999999999999999999999999999999'});
    Core.initUniform(_self.repFinConfigWindowId);
    
    var searchFooterCheckbox = $('.datagrid-footer table tbody tr', _self.repFinConfigWindowId).find('input[type="checkbox"]');
    if(searchFooterCheckbox.length) {
        searchFooterCheckbox.closest('.checker').remove();
    }    
};

FinReportConfig.prototype.filterSalary = function(elem) {
    var _self = this, $input = $(elem);
    var filterVal = {};
    filterVal[$input.attr('name')] = $input.val();
    
    var filterParams = {
        salaryFilter: filterVal,
        javaCacheId: _self.javaCacheId
    };
    $(_self.dataGridId).datagrid('load', filterParams);
};

FinReportConfig.prototype.saveSalarySheet = function(elem) {
    var _self = this;
    
    $.ajax({
        type: 'post',
        url: 'Mdsalary/saveSalarySheetWebservice',
        data: {
            sheet: _self.globalEmpObj,
            sheetLog: _self.createSheetLogDatas,
            javaCacheId: _self.javaCacheId,
            dataIndex: _self.dataIndex
        },        
        dataType: "json",
        beforeSend: function () {
            Core.blockUI({
                message: 'Хадгалж байна, Түр хүлээнэ үү...',
                boxed: true
            });
        },
        success: function (resp) {
            if(resp.status === 'success') {
                _self.createSheetLogDatas = [];
                $(_self.repFinConfigWindowId).find('.searchCalcInfo').trigger('click', [true]);
                
                PNotify.removeAll();
                new PNotify({
                    title: 'Success',
                    text: 'Амжилттай хадгалагдлаа.',
                    type: 'success',
                    sticker: false
                }); 
                
            } else {
                PNotify.removeAll();
                new PNotify({
                    title: 'Анхааруулга',
                    text: resp.text,
                    type: 'warning',
                    sticker: false
                });           
            }
        }
    });
};

FinReportConfig.prototype.copyFieldRowSheet = function() {
    var _self = this;
    
    $.ajax({
        type: 'post',
        url: 'Mdsalary/copyFieldRowSheetWebservice',
        data: {
            metaDataCode: _self.activeField,
            value: _self.globalEmpObj[_self.activeIndex][_self.activeField],
            sheet: _self.globalEmpObj,
            javaCacheId: _self.javaCacheId,
            dataIndex: _self.dataIndex
        },
        dataType: "json",
        beforeSend: function () {
            Core.blockUI({
                message: 'Ижил утгатай болгож байна...',
                boxed: true
            });
        },
        success: function (resp) {
            if(resp.status === 'success') {
                $(_self.dataGridId).datagrid('reload');
            } else {
                PNotify.removeAll();
                new PNotify({
                    title: 'Анхааруулга',
                    text: resp.text,
                    type: 'warning',
                    sticker: false
                });           
            }
            Core.unblockUI();
        }
    });
};

FinReportConfig.prototype.calculateSalary = function() {
    var _self = this;
    
    $.ajax({
        type: 'post',
        url: 'Mdsalary/calculateSalaryListWebservice',
        data: {
            sheet: _self.globalEmpObj,
            javaCacheId: _self.javaCacheId,
            dataIndex: _self.dataIndex
        },
        dataType: "json",
        beforeSend: function () {
            Core.blockUI({
                message: 'Бодолт хийгдэж байна, Түр хүлээнэ үү',
                boxed: true
            });
        },
        success: function (resp) {
            if(resp.status === 'success') {
                $(_self.dataGridId).datagrid('reload');
                
            } else {
                PNotify.removeAll();
                new PNotify({
                    title: 'Анхааруулга',
                    text: resp.text,
                    type: 'warning',
                    sticker: false
                });           
            }
            Core.unblockUI();
        }
    });
};

FinReportConfig.prototype.selectedEmployeeSalary = function(rows) {
    var _self = this;
    
    if(_self.selectedDeps.length === 1) {
        _self.appendConfirmEmployeeSalarySheet(_self.selectedDeps[0], rows);

    } else if(_self.selectedDeps.length > 1) {
        
        var dialogname = $('#dialog-append-employee_'+_self.windowId);
        var $dialogname = 'dialog-append-employee_'+_self.windowId;
        var data = '', depNames = $("#departmentIdName", _self.repFinConfigWindowId).val().split('__');

        data += '<div><span>Ажилчидын мэдээллийг аль хэлтэсийн цалин бодолтод оноохыг зааж өгнө үү?</span></div><br>';
        data += '<select id="chooseDepartment_'+_self.windowId+'" name="chooseDepartment" class="form-control select2 form-control-sm input-xxlarge mt5" data-placeholder="- Сонгох -">';
            data += '<option value="">- Сонгох -</option>';
            $.each(_self.selectedDeps, function (key, value) {
                data += '<option value="' + value + '">' + depNames[key] + '</option>';
            });
        data += '</select>';
        data += '<span style="color: #A94442" id="chooseDepWarningMsg"></span>';

        dialogname.empty().html(data);
        dialogname.dialog({
            cache: false,
            resizable: true,
            bgiframe: true,
            autoOpen: false,
            title: 'Хэлтэс сонгох',
            width: 400,
            height: 200,
            modal: true,
            open: function () {
                $('div[aria-describedby=' + $dialogname + '] .ui-dialog-buttonset').addClass("btn-group float-right");
                $('div[aria-describedby=' + $dialogname + '] .ui-dialog-buttonset').find('button:eq(0)').addClass('btn btn-sm blue mr0 addEmployeeListToDataGrid');
                $('div[aria-describedby=' + $dialogname + '] .ui-dialog-buttonset').find('button:eq(1)').addClass('btn blue-hoki btn-sm ml5');
                $('#chooseDepartment_'+_self.windowId).select2();
            },
            close: function () {
                dialogname.empty().dialog('close');
            },
            buttons: [
                {text: 'Оноох', click: function () {
                    var chooseDepartment = $('#chooseDepartment_'+_self.windowId).val();
                    
                    if(chooseDepartment.trim() == '') {
                        $('#chooseDepartment_'+_self.windowId).addClass('error');
                        $('#chooseDepartment_'+_self.windowId).parent().find('#chooseDepWarningMsg').text('Хэлтэс нэгжээ сонгоно уу!');
                        return;
                    }
                    
                    _self.appendConfirmEmployeeSalarySheet(chooseDepartment, rows);

                    dialogname.dialog('close');
                }},
                {text: plang.get('close_btn'), click: function () {
                    dialogname.empty().dialog('close');
                }}
            ]
        });
        dialogname.dialog('open'); 

    }
};

FinReportConfig.prototype.appendConfirmEmployeeSalarySheet = function(department, employees) {
    var _self = this;
    
    $.ajax({
        url: "mdsalary/appendEmployeeSheetWebservice",
        type: "POST",
        data: {
            department: department, 
            employees: employees,
            javaCacheId: _self.javaCacheId
        },
        dataType: "json",
        beforeSend: function () {
            Core.blockUI({
                message: 'Ажилтан нэмж байна, түр хүлээнэ үү',
                boxed: true
            });
        },
        success: function (resp) {
            if(resp.status === 'success')
                $(_self.dataGridId).datagrid('reload');
            else {
                PNotify.removeAll();
                new PNotify({
                    title: 'Анхааруулга',
                    text: resp.text,
                    type: 'warning',
                    sticker: false
                });                    
            }
            Core.unblockUI();
        }
    });
}

FinReportConfig.prototype.copyFieldColumnSheet = function() {
    var _self = this;
    
    var dialogname = $('#dialog-copy-field_'+_self.windowId);
    var $dialogname = 'dialog-copy-field_'+_self.windowId;
    var data = '';
    
    data += '<div><span>Хуулах талбар сонгох:</span></div>';
    data += '<select id="copyFromField_'+_self.windowId+'" name="copyFromField" class="form-control select2 form-control-sm input-xxlarge mt5" required="required" data-placeholder="- Сонгох -">';
        data += '<option value="">- Сонгох -</option>';
        $.each(_self.fields, function (key, value) {
            if(value['title'] != '' && value['disable'] != '1')
                data += '<option value="' + value['field'] + '">' + value['title'] + '</option>';
        });
        data += '</select>';
    data += '<div class="mt10"><span>Буулгах талбар сонгох:</span></div>';
    data += '<select id="copyToField_'+_self.windowId+'" name="copyToField" disabled class="form-control select2 form-control-sm input-xxlarge mt5" required="required" data-placeholder="- Сонгох -">';
        data += '<option value="">- Сонгох -</option>';
    data += '</select>';
    
    $(document).on('change', '#copyFromField_'+_self.windowId, function(){
        var _thisField = $(this).val(), data2 = '<option value="" selected>- Сонгох -</option>';
        $.each(_self.fields, function (key, value) {
            if(value['field'] != _thisField && value['title'] != '' && value['disable'] != '1')
                data2 += '<option value="' + value['field'] + '">' + value['title'] + '</option>';
        });        
        $('#copyToField_'+_self.windowId).removeAttr('disabled');
        $('#copyToField_'+_self.windowId).empty().html(data2);
        $('#copyToField_'+_self.windowId).select2();
    });
    
    dialogname.empty().html(data);
    dialogname.dialog({
        cache: false,
        resizable: true,
        bgiframe: true,
        autoOpen: false,
        title: 'Багана хооронд утга хуулах',
        width: 320,
        height: 220,
        modal: true,
        open: function () {
            $('div[aria-describedby=' + $dialogname + '] .ui-dialog-buttonset').addClass("btn-group float-right");
            $('div[aria-describedby=' + $dialogname + '] .ui-dialog-buttonset').find('button:eq(0)').addClass('btn btn-sm blue mr0 addEmployeeListToDataGrid');
            $('div[aria-describedby=' + $dialogname + '] .ui-dialog-buttonset').find('button:eq(1)').addClass('btn blue-hoki btn-sm ml5');
            $('#copyFromField_'+_self.windowId).select2();
            $('#copyToField_'+_self.windowId).select2();
        },
        close: function () {
            dialogname.empty().dialog('close');
        },
        buttons: [
            {text: 'Хуулах', click: function () {
                var copyFromField = $('#copyFromField_'+_self.windowId).val(),
                    copyToField = $('#copyToField_'+_self.windowId).val();
                if(copyFromField == '' || copyToField == '') {
                    alert('Хуулах эсвэл Буулгах талбараа сонгоно уу!');
                    return;
                }

                $.ajax({
                    type: 'post',
                    url: 'Mdsalary/copyFieldColumnSheetWebservice',
                    data: {
                        srcMeta: copyFromField,
                        trgMeta: copyToField,
                        sheet: _self.globalEmpObj,
                        javaCacheId: _self.javaCacheId,
                        dataIndex: _self.dataIndex
                    },
                    dataType: "json",
                    beforeSend: function () {
                        Core.blockUI({
                            message: 'Утга хуулж байна...',
                            boxed: true
                        });
                    },
                    success: function (resp) {
                        if(resp.status === 'success') {
                            $(_self.dataGridId).datagrid('reload');

                        } else {
                            PNotify.removeAll();
                            new PNotify({
                                title: 'Анхааруулга',
                                text: resp.text,
                                type: 'warning',
                                sticker: false
                            });           
                        }
                        Core.unblockUI();
                    }
                });                    

                dialogname.dialog('close');
            }},
            {text: plang.get('close_btn'), click: function () {
                dialogname.empty().dialog('close');
            }}
        ]
    });
    dialogname.dialog('open');    
};

FinReportConfig.prototype.multipleFilter = function(elem) {
    var $fieldName = $(elem).closest('td').attr('field'), _self = this;
    var dialogname = $('#dialog-multiple-filter_' + _self.windowId + '_' + $fieldName);
    var $dialogname = 'dialog-multiple-filter_' + _self.windowId + '_' + $fieldName;
    var data = '';
    data = '<span><div>'+
        '<input type="text" name="multipleFilterData" style="width: 180px;" class="float-left ml20 form-control text-right form-control-sm numberInit2" placeholder="Утгаа оруулна уу">'+
        '<select class="form-control form-control-sm" name="multipleFilterCondition" style="width: 70px">\n\
            <option value="=">Тэнцүү</option>\n\
            <option value="!=">Ялгаатай</option>\n\
            <option value=">">Их</option>\n\
            <option value="<">Бага</option>\n\
        </select>'+
        '<a href="javascript:;" class="ml10 float-left btn btn-xs btn-success multipleAddFilterBtn" title="Нэмэх" onclick="multipleAddFilter(this);">'+
            '<i class="icon-plus3 font-size-12"></i>'+
        '</a>'+
    '</div></span>'+
    '<div style="overflow-y: auto; max-height: 280px;" class="salary-aggregate-function-datas mt15"></div>';

    if (!dialogname.length) {
        $('<div id="' + $dialogname + '"></div>').appendTo('body');
    }
    dialogname = $('#dialog-multiple-filter_' + _self.windowId + '_' + $fieldName);

    if (dialogname.children().length > 0) {
        dialogname.dialog({
            cache: false,
            resizable: true,
            bgiframe: true,
            autoOpen: false,
            title: 'Олон утгаар хайх',
            width: 380,
            height: 'auto',
            modal: true,
            open: function () {              
                $('div[aria-describedby=' + $dialogname + '] .ui-dialog-buttonset').addClass("btn-group float-right");
                $('div[aria-describedby=' + $dialogname + '] .ui-dialog-buttonset').find('button:eq(0)').addClass('btn btn-sm blue mr0 addEmployeeListToDataGrid');
                $('div[aria-describedby=' + $dialogname + '] .ui-dialog-buttonset').find('button:eq(1)').addClass('btn blue-hoki btn-sm ml5');
                $('div[aria-describedby=' + $dialogname + '] .ui-dialog-buttonset').find('button:eq(2)').addClass('btn blue-hoki btn-sm ml5');
            },
            close: function () {
                dialogname.dialog('close');
            },
            buttons: [
                {text: 'Хайх', click: function () {
                     if($(this).closest('div.ui-dialog').find('input[name="multipleFilterData"]').length === 0)
                         return;

                     var filterDataJoin = '(1 == 1)';
                     _self.multifilterParams = [];
                     $(this).closest('div.ui-dialog').find('input[name="multipleFilterData"]').each(function(k){
                        var filterCondition = $(this).closest('div.ui-dialog').find('select[name="multipleFilterCondition"]:eq('+k+')').val();
                        var filterVal = {};
                        
                        if($(this).val().trim() != '') {
                            filterVal['condition'] = filterCondition;
                            filterVal['field'] = $fieldName;
                            filterVal['value'] = $(this).val().trim().replace(/[,]/g, '');
                            _self.multifilterParams.push(filterVal);
                            filterDataJoin = '';
                        }
                        
                        dialogname.find('input[type="checkbox"]').each(function(){
                            var _thisV = $(this), filterVal2 = {};
                            if(_thisV.is(':checked')) {
                                filterVal2['condition'] = '=';
                                filterVal2['field'] = $fieldName;
                                filterVal2['value'] = _thisV.val();
                                filterDataJoin = '';
                                _self.multifilterParams.push(filterVal2);
                            }
                        });                        
                     }).promise().done(function () {
                         
                        var multiFilterSelector = $(_self.repFinConfigWindowId).find('.datagrid-view2').find('.datagrid-htable').find('tbody').find('tr:eq(1)').find('td[field="'+$fieldName+'"]');
                        if(filterDataJoin == '(1 == 1)') {
                            multiFilterSelector.find('.multipleFilterClass').children().css('color', '#30a2dd');
                            $(_self.dataGridId).datagrid('load', {salaryFilter: [], javaCacheId: _self.javaCacheId});
                        } else {                            
                            multiFilterSelector.find('.multipleFilterClass').children().css('color', '#ef2300');
                            $(_self.dataGridId).datagrid('load', {salaryFilter: _self.multifilterParams, javaCacheId: _self.javaCacheId});
                        }
                     });

                    dialogname.dialog('close');
                }},
                {text: 'Цэвэрлэх', click: function () {
                    $(this).closest('div.ui-dialog').find('input[name="multipleFilterData"]').val('');
                }},
                {text: plang.get('close_btn'), click: function () {
                    dialogname.dialog('close');
                }}
            ]
        });
        dialogname.dialog('open');
    } else {
        dialogname.empty().html(data);            
        $.ajax({
            type: 'post',
            url: 'Mdsalary/getFilterValuesWebservice',
            data: {
                metaDataId: $(elem).closest('td').attr('fieldid'),
                javaCacheId: _self.javaCacheId
            },
            dataType: "json",
            beforeSend: function () {
                Core.blockUI({
                    boxed: true
                });
            },
            success: function (resp) {
                if(resp.status === 'success') {
                    var filterHtml = '<table class="table table-sm table-bordered table-hover bprocess-table-dtl mb0"><tbody>';
                    $.each(resp.result, function(k, v){
                        filterHtml += '<tr><td><input type="checkbox" value="' + v + '" id="filter_' + v + '"/> <label for="filter_' + v + '">' + pureNumberFormat(v) + '</label></td></tr>';
                    });
                    filterHtml += '</tbody></table>';
                    $('.salary-aggregate-function-datas', '#'+$dialogname).empty().append(filterHtml);
                    dialogname.find('input[type="checkbox"]').uniform();
                    
                } else {
                    PNotify.removeAll();
                    new PNotify({
                        title: 'Анхааруулга',
                        text: resp.text,
                        type: 'warning',
                        sticker: false
                    });           
                }
                Core.unblockUI();
            }
        });        
        
        dialogname.dialog({
            cache: false,
            resizable: true,
            bgiframe: true,
            autoOpen: false,
            title: 'Олон утгаар хайх',
            width: 380,
            height: 'auto',
            modal: true,
            open: function () {
                $('div[aria-describedby=' + $dialogname + '] .ui-dialog-buttonset').addClass("btn-group float-right");
                $('div[aria-describedby=' + $dialogname + '] .ui-dialog-buttonset').find('button:eq(0)').addClass('btn btn-sm blue mr0 addEmployeeListToDataGrid');
                $('div[aria-describedby=' + $dialogname + '] .ui-dialog-buttonset').find('button:eq(1)').addClass('btn blue-hoki btn-sm ml5');                                                                                      
                $('div[aria-describedby=' + $dialogname + '] .ui-dialog-buttonset').find('button:eq(2)').addClass('btn blue-hoki btn-sm ml5');                
            },
            close: function () {
                dialogname.dialog('close');
            },
            buttons: [
                {text: 'Хайх', click: function () {
                     if($(this).closest('div.ui-dialog').find('input[name="multipleFilterData"]').length === 0)
                         return;

                     var filterDataJoin = '(1 == 1)';
                     _self.multifilterParams = [];
                     $(this).closest('div.ui-dialog').find('input[name="multipleFilterData"]').each(function(k){
                        var filterCondition = $(this).closest('div.ui-dialog').find('select[name="multipleFilterCondition"]:eq('+k+')').val();
                        var filterVal = {};
                        
                        if($(this).val().trim() != '') {
                            filterVal['condition'] = filterCondition;
                            filterVal['field'] = $fieldName;
                            filterVal['value'] = $(this).val().trim().replace(/[,]/g, '');
                            filterDataJoin = '';
                            _self.multifilterParams.push(filterVal);
                        }               
                        
                        dialogname.find('input[type="checkbox"]').each(function(){
                            var _thisV = $(this), filterVal2 = {};
                            if(_thisV.is(':checked')) {
                                filterVal2['condition'] = '=';
                                filterVal2['field'] = $fieldName;
                                filterVal2['value'] = _thisV.val();
                                filterDataJoin = '';
                                _self.multifilterParams.push(filterVal2);
                            }
                        });
                     }).promise().done(function () {
                         
                        var multiFilterSelector = $(_self.repFinConfigWindowId).find('.datagrid-view2').find('.datagrid-htable').find('tbody').find('tr:eq(1)').find('td[field="'+$fieldName+'"]');
                        if(filterDataJoin == '(1 == 1)') {
                            multiFilterSelector.find('.multipleFilterClass').children().css('color', '#30a2dd');
                            $(_self.dataGridId).datagrid('load', {salaryFilter: [], javaCacheId: _self.javaCacheId});
                        } else {
                            multiFilterSelector.find('.multipleFilterClass').children().css('color', '#ef2300');
                            $(_self.dataGridId).datagrid('load', {salaryFilter: _self.multifilterParams, javaCacheId: _self.javaCacheId});
                        }
                     });

                    dialogname.dialog('close');
                }},
                {text: 'Цэвэрлэх', click: function () {
                    $(this).closest('div.ui-dialog').find('input[name="multipleFilterData"]').val('');
                }},                    
                {text: plang.get('close_btn'), click: function () {
                    dialogname.dialog('close');
                }}
            ]
        });
        dialogname.dialog('open');
    }

    $('.numberInit2').autoNumeric('init',
        {aPad: true, mDec: 2, vMin: '-999999999999999999999999999999.999999999999999999999999999999'}
    );                
}

var multipleAddFilter = function(elem) {
    var getDiv = $(elem).parent().parent();
    $(getDiv).append(
            '<div class="clearfix w-100"></div><div class="mt5">' +
            '<input type="text" name="multipleFilterData" style="width: 180px;" class="float-left ml20 form-control text-right form-control-sm numberInit2" placeholder="Утгаа оруулна уу">' +
            '<select class="form-control form-control-sm" name="multipleFilterCondition" style="width: 70px">\n\
                <option value="=">Тэнцүү</option>\n\
                <option value="!=">Ялгаатай</option>\n\
                <option value=">">Их</option>\n\
                <option value="<">Бага</option>\n\
            </select>' +
            '<a href="javascript:;" class="ml10 float-left btn btn-xs btn-danger multipleAddFilterBtn" title="Устгах" onclick="multipleRemoveFilter(this);"><i class="icon-cross2 font-size-12"></i></a>' +
            '</div>'
    );  

    $('.numberInit2').autoNumeric('init',
        {aPad: true, mDec: 2, vMin: '-999999999999999999999999999999.999999999999999999999999999999'}
    );   
};        
    
var multipleRemoveFilter = function(element) {
    $(element).parent().remove();
};

FinReportConfig.prototype.saveSalarySheetByPager = function() {
    var _self = this;
    
    setTimeout(function(){
        $.ajax({
            url: "mdsalary/saveCacheSalarySheetWebservice",
            type: "POST",
            data: {
                sheetData: _self.globalEmpObj,
                javaCacheId: _self.javaCacheId,
                dataIndex: _self.dataIndex
            },
            dataType: "json",
            async: false,
            success: function (resp) {
                if(resp.status === 'error') {
                    PNotify.removeAll();
                    new PNotify({
                        title: 'Анхааруулга',
                        text: resp.text,
                        type: 'warning',
                        sticker: false
                    });                 
                }
            }
        });
    }, 0);
}

FinReportConfig.prototype.deleteEmployeeSheet = function(elem, row) {
    var _self = this;
    var $dialogName = 'dialog-delete-confirm-employee_' + _self.windowId;
    
    row = JSON.parse(decodeURIComponent(row));
    $("#" + $dialogName).empty().html(row.lastname + ' ' + row.firstname + ' <br>ажилтныг устгах гэж байна!<br> <strong>Устгахдаа итгэлтэй</strong> байна уу?');
    $("#" + $dialogName).dialog({
        cache: false,
        resizable: false,
        bgiframe: true,
        autoOpen: false,
        title: 'Сануулга',
        width: 330,
        height: "auto",
        modal: true,
        close: function () {
            $("#" + $dialogName).empty().dialog('close');
        },
        buttons: [
            {text: plang.get('yes_btn'), class: 'btn green-meadow btn-sm', click: function () {
                $.ajax({
                    url: "mdsalary/deleteEmployeeSheetWebservice",
                    type: "GET",
                    data: {
                        empKeyId: row.employeekeyid,
                        javaCacheId: _self.javaCacheId
                    },
                    dataType: "json",
                    beforeSend: function () {
                        Core.blockUI({
                            message: 'Ажилтан устгаж байна...',
                            boxed: true
                        });
                    },        
                    success: function (resp) {
                        if(resp.status === 'error') {
                            PNotify.removeAll();
                            new PNotify({
                                title: 'Анхааруулга',
                                text: resp.text,
                                type: 'warning',
                                sticker: false
                            });                 
                        } else
                            $(_self.dataGridId).datagrid('reload');

                        Core.unblockUI();
                    }
                });
                $("#" + $dialogName).dialog('close');
            }},
            {text: plang.get('no_btn'), class: 'btn blue-madison btn-sm', click: function () {
                $("#" + $dialogName).dialog('close');
            }}
        ]
    });
    $("#" + $dialogName).dialog('open');    
}

FinReportConfig.prototype.getLogData = function(elem) {
    var _self = this;
    var sheetRow = _self.globalEmpObj[_self.getRowIndex(elem)];
    var formData = $(_self.repFinConfigFormId).serialize();
    var $dialogName = 'dialog-sheetlog-list';

    if (!$($dialogName).length) {
        $('<div id="' + $dialogName + '"></div>').appendTo("body");
    }

    $.ajax({
        type: 'post',
        url: 'mdsalary/getLogInformation',
        data: {
            formData: formData,
            field: _self.getField(elem),
            empKeyId: sheetRow.employeekeyid,
            empDepId: sheetRow.departmentid
        },
        dataType: "json",
        beforeSend: function () {
            var blockMsg = 'Лог ачааллаж байна...';
            Core.blockUI({
                message: blockMsg,
                boxed: true
            });
        },
        success: function (data) {
            $("#" + $dialogName).empty().html(data.html);
            $("#" + $dialogName).dialog({
                appendTo: "body",
                cache: false,
                resizable: true,
                bgiframe: true,
                autoOpen: false,
                title: data.title,
                width: 500,
                height: 'auto',
                modal: true,
                close: function () {
                    $("#" + $dialogName).empty().dialog('destroy').remove();
                },
                buttons: [
                    {text: data.close_btn, class: 'btn btn-sm blue-hoki', click: function () {
                        $("#" + $dialogName).dialog('close');
                    }}
                ]
            });
            $("#" + $dialogName).dialog('open');
            Core.unblockUI();
        },
        error: function () {
            alert("Error");
        }
    });
}

FinReportConfig.prototype.employeeInformation = function(elem) {
    var _self = this;
    var sheetRow = _self.globalEmpObj[_self.getRowIndex(elem)];
    sheetRow.id = sheetRow.employeekeyid
    delete sheetRow.employeekeyid;
    
    runWorkSpaceWithDataView(elem, '1482213710825357', '1484732973842603', '', sheetRow);
}

FinReportConfig.prototype.salaryColumnConfigPosition = function() {
    var $dialogname = 'dialog-salary-column-config-position';
    var data = '', typeAllFields = [], _self = this;

    $.ajax({
        type: 'post',
        url: 'Mdsalary/prlCalcTypeDtlByTypeIdList',
        data: {
            calcTypeId: $(_self.repFinConfigWindowId).find('.calcTypeId_valueField').val()
        },
        dataType: "json",
        async: false,
        success: function (data) {
            typeAllFields = data;
        }
    });        

    data = '<div class="row">'+
        '<div class="col-md-12">'+
            '<form>'+
            '<table class="table table-sm table-bordered table-hover bprocess-table-dtl mb10 salaryColumnConfigPosTable">'+
                '<thead>'+
                    '<tr>'+
                        '<th class="rowNumber">№</th>'+
                        '<th>Баганы нэр</th>'+
                    '</tr>'+
                '</thead>'+
                '<tbody>';
                    var allFieldsLen = typeAllFields.length, ii = 1;
                    for(var i = 0; i < allFieldsLen; i++) {
                        data += '<tr id="config-' + typeAllFields[i]['META_DATA_CODE'] + '" style="display: table-row; cursor: move;">';
                        data += '<td class="ordernumber-' + typeAllFields[i]['META_DATA_CODE'] + ' dragHandle">' + ii + '</td>';
                        data += '<td>' + typeAllFields[i]['META_DATA_NAME'] + '<input type="hidden" name="SALARY_CONFIG_ORDER[]" id="order-' + typeAllFields[i]['META_DATA_CODE'] + '" value="' + ii + '"/>'+
                                '<input type="hidden" name="SALARY_CONFIG_ORDER_METACODE[]" value="' + typeAllFields[i]['META_DATA_CODE'] + '"/></td>';
                        data += '</tr>';
                        ii++;
                    };
    data += '</tbody>'+
            '</table>'+
            '</form>'+
        '</div>'+
    '</div>';

    if (!$('#'+$dialogname).length) {
        $('<div id="' + $dialogname + '"></div>').appendTo(_self.repFinConfigWindowId);
    }
    var dialogname = $('#dialog-salary-column-config-position');

    dialogname.empty().html(data);            
    dialogname.dialog({
        appendTo: _self.repFinConfigWindowId,
        cache: false,
        resizable: true,
        bgiframe: true,
        autoOpen: false,
        title: 'Баганы байршил өөрчлөх',
        width: 400,
        height: 'auto',
        modal: true,
        open: function () {          
            $('div[aria-describedby=' + $dialogname + '] .ui-dialog-buttonset').addClass("btn-group float-right");
            $('div[aria-describedby=' + $dialogname + '] .ui-dialog-buttonset').find('button:eq(0)').addClass('btn btn-sm blue mr0 addEmployeeListToDataGrid');
            $('div[aria-describedby=' + $dialogname + '] .ui-dialog-buttonset').find('button:eq(1)').addClass('btn blue-hoki btn-sm ml5');
        },
        close: function (elem) {
            dialogname.dialog('close');
        },
        buttons: [
            {text: plang.get('save_btn'), click: function (elem) {
                Core.blockUI({
                    message: 'Түр хүлээнэ үү...',
                    boxed: true
                });                            
                $.ajax({
                    type: 'post',
                    url: 'Mdsalary/setSalaryColumnOrder',
                    data: $(this).closest('.ui-dialog').find('form').serialize() + '&calcTypeId=' + $(_self.repFinConfigWindowId).find('.calcTypeId_valueField').val() + '&sheetData='+JSON.stringify(_self.globalEmpObj) + '&javaCacheId=' + _self.javaCacheId + '&dataIndex=' + _self.dataIndex,
                    dataType: "json",
                    success: function (data) {
                        PNotify.removeAll();
                        new PNotify({
                            title: data.status,
                            text: data.message,
                            type: data.status,
                            sticker: false
                        });
                        Core.blockUI();

                        if(data.status === 'success') {
                            $('input[name="fromCache"]', _self.repFinConfigFormId).val('1');
                            $(_self.repFinConfigWindowId).find('.reSearchCalcInfo').trigger('click', [true]);
                            $(_self.repFinConfigWindowId).find('.searchCalcInfo').trigger('click');
                        }
                    }
                });
                dialogname.dialog('close');
            }},
            {text: plang.get('close_btn'), click: function () {
                dialogname.dialog('close');
            }}
        ]
    });
    dialogname.dialog('open');

    $(_self.repFinConfigWindowId).find('.salaryColumnConfigPosTable tbody').tableDnD({
        onDragClass: "rowHighlight", 
        dragHandle: ".dragHandle", 
        onDrop: function(table, row) {
            var orders = $.tableDnD.serialize();
            var order = orders.split('[]=config-');
            var number = 1;

            $.each(order, function(i, dtl) {
                if (dtl.length != 0) {

                    var num = dtl.split('&');

                    $('.ordernumber-'+num[0]).html(number);
                    $('#order-'+num[0]).val(number);

                    number++;
                }
            });
        }
    });        
}

FinReportConfig.prototype.salaryDuplicateExcel = function(response) {
    var $dialogname = 'dialog-salary-duplicate-excel';
    var data = '', _self = this;

    data = '<div class="row">'+
        '<div class="col-md-12">'+
            '<form>';
            if(response.existEmployee !== '' && response.existEmployee !== null) {
                data += '<span>Дараах <strong>' + allFieldsLen + '</strong> ажилтан excel дээр давхардсан байна</span><br><br>'+
                '<table class="table table-sm table-bordered table-hover bprocess-table-dtl mb10">'+
                    '<thead>'+
                        '<tr>'+
                            '<th style="width: 25%">Код</th>'+
                            '<th>Овог</th>'+
                            '<th>Нэр</th>'+
                        '</tr>'+
                    '</thead>'+
                    '<tbody>';
                        var allFieldsLen = response.existEmployee.length;
                        data += '<tr>';
                        data += '<td colspan="3">Дараах <strong>' + allFieldsLen + '</strong> ажилтан excel дээр давхардсан байна</td>';
                        data += '</tr>';

                        for(var i = 0; i < allFieldsLen; i++) {
                            data += '<tr>';
                            data += '<td>' + response.existEmployee[i]['employeecode'] + '</td>';
                            data += '<td>' + response.existEmployee[i]['lastname'] + '</td>';
                            data += '<td>' + response.existEmployee[i]['firstname'] + '</td>';
                            data += '</tr>';
                        };                    
                data += '</tbody>'+
                        '</table>';
            }
            
            if(response.notfoundEmployee !== '' && response.notfoundEmployee !== null) {
                var allFieldsLen = response.notfoundEmployee.length;
                
                data += '<span>Дараах <strong>' + allFieldsLen + '</strong> ажилтан систем дээр үүсээгүй байна</span><br><br>'+
                '<table class="table table-sm table-bordered table-hover bprocess-table-dtl mb10">'+
                    '<thead>'+
                        '<tr>'+
                            '<th style="width: 25%">Код</th>'+
                            '<th>Овог</th>'+
                            '<th>Нэр</th>'+
                        '</tr>'+
                    '</thead>'+
                    '<tbody>';
                    for(var i = 0; i < allFieldsLen; i++) {
                        data += '<tr>';
                        data += '<td>' + response.notfoundEmployee[i]['employeecode'] + '</td>';
                        data += '<td>' + response.notfoundEmployee[i]['lastname'] + '</td>';
                        data += '<td>' + response.notfoundEmployee[i]['firstname'] + '</td>';
                        data += '</tr>';
                    };
                data += '</tbody>'+
                        '</table>';
            }
            data += '</form>'+
        '</div>'+
    '</div>';

    if (!$('#'+$dialogname).length) {
        $('<div id="' + $dialogname + '"></div>').appendTo(_self.repFinConfigWindowId);
    }
    var dialogname = $('#'+$dialogname);

    dialogname.empty().html(data);            
    dialogname.dialog({
        appendTo: _self.repFinConfigWindowId,
        cache: false,
        resizable: true,
        bgiframe: true,
        autoOpen: false,
        title: 'Excel дээр давхардсан ажилтнууд',
        width: 550,
        height: 'auto',
        "max-height": 450,
        modal: true,
        open: function () {   
            $('div[aria-describedby=' + $dialogname + '] .ui-dialog-buttonset').addClass("btn-group float-right");
            $('div[aria-describedby=' + $dialogname + '] .ui-dialog-buttonset').find('button:eq(0)').addClass('btn blue-hoki btn-sm ml5');
        },
        close: function (elem) {
            dialogname.dialog('close');
        },
        buttons: [
            {text: plang.get('close_btn'), click: function () {
                dialogname.dialog('close');
            }}
        ]
    });
    dialogname.dialog('open');
}

FinReportConfig.prototype.salaryDuplicateDatabaseEdit = function(response) {
    var $dialogname = 'dialog-salary-duplicate-database';
    var data = '', _self = this;

    if(response.duplicateEmployee !== '' && response.duplicateEmployee !== null) {
        var allFieldsLen = response.duplicateEmployee.length;
        
        data = '<div class="row">'+
            '<div class="col-md-12">'+
                '<form>'+
                '<span>Дараах <strong>' + allFieldsLen + '</strong> ажилтнаар өгөгдөл үүссэн байна, дарж хуулах уу</span><br><br>'+            
                '<table class="table table-sm table-bordered table-hover bprocess-table-dtl mb10">'+
                    '<thead>'+
                        '<tr>'+
                            '<th style="width: 25%">Код</th>'+
                            '<th>Овог</th>'+
                            '<th>Нэр</th>'+
                        '</tr>'+
                    '</thead>'+
                    '<tbody>';
                        for(var i = 0; i < allFieldsLen; i++) {
                            data += '<tr>';
                            data += '<td>' + response.duplicateEmployee[i]['employeecode']  + '<input type="hidden" name="duplicateEmployeesExcel[]" value=""/>' + '</td>';
                            data += '<td>' + response.duplicateEmployee[i]['lastname'] + '</td>';
                            data += '<td>' + response.duplicateEmployee[i]['firstname'] + '</td>';
                            data += '</tr>';
                        }
        data += '</tbody>'+
                '</table>'+
                '<input type="hidden" name="excelDatas" value="' + encodeURIComponent(JSON.stringify(response.excelDatas)) + '"/>'+
                '</form>'+
            '</div>'+
        '</div>';
    }

    if (!$('#'+$dialogname).length) {
        $('<div id="' + $dialogname + '"></div>').appendTo(_self.repFinConfigWindowId);
    }
    var dialogname = $('#'+$dialogname);

    dialogname.empty().html(data);            
    dialogname.dialog({
        appendTo: _self.repFinConfigWindowId,
        cache: false,
        resizable: true,
        bgiframe: true,
        autoOpen: false,
        title: 'Excel import',
        width: 550,
        height: 'auto',
        "max-height": 450,
        modal: true,
        open: function () {
            $('div[aria-describedby=' + $dialogname + '] .ui-dialog-buttonset').addClass("btn-group float-right");
            $('div[aria-describedby=' + $dialogname + '] .ui-dialog-buttonset').find('button:eq(0)').addClass('btn btn-sm blue mr0');
            $('div[aria-describedby=' + $dialogname + '] .ui-dialog-buttonset').find('button:eq(1)').addClass('btn blue-hoki btn-sm ml5');
        },
        close: function (elem) {
            dialogname.dialog('close');
        },
        buttons: [
            {text: plang.get('yes_btn'), click: function (elem) {
                Core.blockUI({
                    message: 'Түр хүлээнэ үү...',
                    boxed: true
                });
                $.ajax({
                    type: 'post',
                    url: 'Mdsalary/salaryDataImportDuplicateData',
                    data: $(this).closest('.ui-dialog').find('form').serialize(),
                    dataType: "json",
                    success: function (data) {
                        PNotify.removeAll();
                        new PNotify({
                            title: data.status,
                            text: data.text,
                            type: data.status,
                            sticker: false
                        });
                        Core.unblockUI();

                        if(data.status === 'success') {
                            $(_self.dataGridId).datagrid('reload');
                        }
                    }
                });
                dialogname.dialog('close');
            }},
            {text: plang.get('close_btn'), click: function () {
                dialogname.dialog('close');
            }}
        ]
    });
    dialogname.dialog('open');
}

FinReportConfig.prototype.salaryDuplicateDatabase = function(response) {
    var $dialogname = 'dialog-salary-duplicate-database';
    var data = '', _self = this;

    if(response.duplicateEmployee !== '' && response.duplicateEmployee !== null) {
        var allFieldsLen = response.duplicateEmployee.length;
        
        data = '<div class="row">'+
            '<div class="col-md-12">'+
                '<form>'+
                '<span>Дараах <strong>' + allFieldsLen + '</strong> ажилтан дээр импорт хийх гэж байна</span><br><br>'+
                '<table class="table table-sm table-bordered table-hover bprocess-table-dtl mb10">'+
                    '<thead>'+
                        '<tr>'+
                            '<th style="width: 25%">Код</th>'+
                            '<th>Овог</th>'+
                            '<th>Нэр</th>'+
                        '</tr>'+
                    '</thead>'+
                    '<tbody>';
                        for(var i = 0; i < allFieldsLen; i++) {
                            data += '<tr>';
                            data += '<td>' + response.duplicateEmployee[i]['employeecode']  + '<input type="hidden" name="duplicateEmployeesExcel[]" value=""/>' + '</td>';
                            data += '<td>' + response.duplicateEmployee[i]['lastname'] + '</td>';
                            data += '<td>' + response.duplicateEmployee[i]['firstname'] + '</td>';
                            data += '</tr>';
                        }
        data += '</tbody>'+
                '</table>'+
                '<input type="hidden" name="excelDatas" value="' + encodeURIComponent(JSON.stringify(response.excelDatas)) + '"/>'+
                '</form>'+
            '</div>'+
        '</div>';
    }

    if (!$('#'+$dialogname).length) {
        $('<div id="' + $dialogname + '"></div>').appendTo(_self.repFinConfigWindowId);
    }
    var dialogname = $('#'+$dialogname);

    dialogname.empty().html(data);            
    dialogname.dialog({
        appendTo: _self.repFinConfigWindowId,
        cache: false,
        resizable: true,
        bgiframe: true,
        autoOpen: false,
        title: 'Excel import',
        width: 550,
        height: 'auto',
        "max-height": 450,
        modal: true,
        open: function () {
            $('div[aria-describedby=' + $dialogname + '] .ui-dialog-buttonset').addClass("btn-group float-right");
            $('div[aria-describedby=' + $dialogname + '] .ui-dialog-buttonset').find('button:eq(0)').addClass('btn btn-sm blue mr0');
            $('div[aria-describedby=' + $dialogname + '] .ui-dialog-buttonset').find('button:eq(1)').addClass('btn blue-hoki btn-sm ml5');
        },
        close: function (elem) {
            dialogname.dialog('close');
        },
        buttons: [
            {text: plang.get('yes_btn'), click: function (elem) {
                Core.blockUI({
                    message: 'Түр хүлээнэ үү...',
                    boxed: true
                });
                $.ajax({
                    type: 'post',
                    url: 'Mdsalary/salaryDataImportDuplicateData',
                    data: $(this).closest('.ui-dialog').find('form').serialize(),
                    dataType: "json",
                    success: function (data) {
                        PNotify.removeAll();
                        new PNotify({
                            title: data.status,
                            text: data.text,
                            type: data.status,
                            sticker: false
                        });
                        Core.unblockUI();

                        if(data.status === 'success') {
                            $(_self.dataGridId).datagrid('reload');
                        }
                    }
                });
                dialogname.dialog('close');
            }},
            {text: plang.get('close_btn'), click: function () {
                dialogname.dialog('close');
            }}
        ]
    });
    dialogname.dialog('open');
}

FinReportConfig.prototype.salaryTemplateExcelImportFields = function(datas) {
    var $dialogname = 'dialog-template-excel-config-import';
    var data = '', _self = this;

    data = '<div class="row">'+
        '<div class="col-md-12">'+
            '<form>'+
            '<table class="table table-sm table-bordered table-hover bprocess-table-dtl mb10">'+
                '<thead>'+
                    '<tr>'+
                        '<th class="rowNumber">№</th>'+
                        '<th class="rowNumber"><input type="checkbox" name="SALARY_CONFIG_EXCEL_IMPORT_TOTAL" value=""/></th>'+
                        '<th>Үзүүлэлтийн нэр</th>'+
                        '<th>Үзүүлэлтийн код</th>'+
                    '</tr>'+
                '</thead>'+
                '<tbody>';
                    var allFieldsLen = datas.allData[0].length, ii = 1;
                    for(var i = 3; i < allFieldsLen; i++) {
                        data += '<tr>';
                        data += '<td>' + (ii++) + '</td>';
                        data += '<td><input type="checkbox" name="SALARY_CONFIG_EXCEL_IMPORT[]" value="' + i + '"/></td>';
                        data += '<td>' + datas.allData[1][i] + '</td>';
                        data += '<td>' + datas.allData[0][i] + '</td>';
                        data += '</tr>';
                    };
    data += '</tbody>'+
            '</table>'+
            '<input type="hidden" name="excelAllDatas" value="' + encodeURIComponent(JSON.stringify(datas.allData)) + '"/>'+
            '</form>'+
        '</div>'+
    '</div>';

    if (!$('#'+$dialogname, _self.repFinConfigWindowId).length) {
        $('<div id="' + $dialogname + '"></div>').appendTo(_self.repFinConfigWindowId);
    }
    var dialogname = $('#dialog-template-excel-config-import', _self.repFinConfigWindowId);

    dialogname.empty().html(data);            
    dialogname.dialog({
        appendTo: _self.repFinConfigWindowId,
        cache: false,
        resizable: true,
        bgiframe: true,
        autoOpen: false,
        title: 'Эксель импорт тохиргоо',
        width: 400,
        height: 'auto',
        modal: true,
        open: function () {          
            $('div[aria-describedby=' + $dialogname + '] .ui-dialog-buttonset').addClass("btn-group float-right");
            $('div[aria-describedby=' + $dialogname + '] .ui-dialog-buttonset').find('button:eq(0)').addClass('btn btn-sm blue mr0');
            $('div[aria-describedby=' + $dialogname + '] .ui-dialog-buttonset').find('button:eq(1)').addClass('btn blue-hoki btn-sm ml5');
            dialogname.find('input[type="checkbox"]').uniform();
        },
        close: function (elem) {
            dialogname.dialog('close');
        },
        buttons: [
            {text: plang.get('save_btn'), click: function (elem) {
                Core.blockUI({
                    message: 'Түр хүлээнэ үү...',
                    boxed: true
                });
                $.ajax({
                    type: 'post',
                    url: 'Mdsalary/salaryDataImportLoadCustomData',
                    data: $(this).closest('.ui-dialog').find('form').serialize() + '&calcTypeId=' + $(_self.repFinConfigWindowId).find('.calcTypeId_valueField').val() + '&javaCacheId=' + _self.javaCacheId,
                    dataType: "json",
                    success: function (data) {
                        if(data.status === 'success' || data.status === 'warning') {
                            PNotify.removeAll();
                            new PNotify({
                                title: data.status,
                                text: data.text,
                                type: data.status,
                                sticker: false
                            });
                        }
                        Core.unblockUI();

                        if(data.status === 'success') {
                            $(_self.dataGridId).datagrid('reload');
                        } else if(data.status === 'excel') {
                            _self.salaryDuplicateExcel(data.response);
                        } else if(data.status === 'database') {
                            if($('input[name="salaryBookId"]', _self.repFinConfigFormId).val() !== '')
                                _self.salaryDuplicateDatabaseEdit(data);
                            else
                                _self.salaryDuplicateDatabase(data);
                        }
                    }
                });
                dialogname.dialog('close');
            }},
            {text: plang.get('close_btn'), click: function () {
                dialogname.dialog('close');
            }}
        ]
    });
    dialogname.dialog('open');
    
    $(_self.repFinConfigWindowId).on('click', 'input[name="SALARY_CONFIG_EXCEL_IMPORT_TOTAL"]', function(){
        if($(this).is(':checked')) {
            dialogname.find('input[name="SALARY_CONFIG_EXCEL_IMPORT[]"]').prop('checked', true).parent().addClass('checked');
        } else
            dialogname.find('input[name="SALARY_CONFIG_EXCEL_IMPORT[]"]').prop('checked', false).parent().removeClass('checked');
    })
}