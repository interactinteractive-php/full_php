var SalaryV3 = function(windowId) {
    this.windowId = windowId;
    this.calcInfoWindowId = "#calcInfoWindow_" + windowId;
    this.calcInfoFormId = "#calcInfoForm_" + windowId;
    this.selectedDeps = [];
    this.selectedDeepDeps = [];
    this.fields = [];
    this.fieldsColspan = [];
    this.dataGridfields = [];
    this.allShowFields = [];
    this.frozenFields = [];
    this.globalEmpObj = [];
    this.changedGlobalEmpObj = [];
    this.dataGridId = "#salaryDatagrid_" + windowId;
    this.activeField = '';
    this.activeIndex = '';
    this.createSheetLogDatas = [];
    this.javaCacheId = '';
    this.dataIndex = 0;
    this.isEditMode = false;
    this.multifilterParams = [];
    this.linkMetaDataId = '';
    this.salaryGridStyle = '';
    this.selectedPage = configSelectedPage;
    this.unlimitPage = '200';
    this.tabNameLi = [];
    this.checkDuplicateEmployee = false;
    
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
    
    /*if(configPager == '1') {
        this.selectedPage = '1000';
        this.unlimitPage = '1000';        
    }*/
};

SalaryV3.prototype.initEventListener = function() {
    var _self = this;
    
    var dynamicHeight = $(window).height() - 275;
    $(_self.dataGridId).attr('height', dynamicHeight);
    
    $(document).on('change', 'select[name="columnColorSet[]"]', function(){
        $(this).css('background-color', $(this).val());
    });    
    
    $(".searchCalcInfo", _self.calcInfoWindowId).on("click", function (e, isTrigger) {
        $(_self.calcInfoFormId).validate({errorPlacement: function () {}});
        
        if ($(_self.calcInfoFormId).valid()) {
            
            if ($('input[name="singleEditMode"]', _self.calcInfoWindowId).val() != '1' &&
                $('input[name="isBatchNumber"]', _self.calcInfoWindowId).val() != '1' &&
                $('input[name="isChange"]', _self.calcInfoWindowId).val() != '1') {
                    if ($('select[name="criteriaTemplateId"]', _self.calcInfoWindowId).val() === '' && configCalculateTemplateCretria === '1') {
                        $('select[name="criteriaTemplateId"]', _self.calcInfoWindowId).parent().find('.select2-container.select2').addClass('error');
                        return;
                    } else {
                        $('select[name="criteriaTemplateId"]', _self.calcInfoWindowId).parent().find('.select2-container.select2').removeClass('error');
                    }
                }
            
            _self.createSheetLogDatas = [];
            _self.changedGlobalEmpObj = [];
            _self.selectedDeps = $("#departmentId", _self.calcInfoWindowId).val().split(',');
            $("#salaryDatagrid_" + _self.windowId).empty();
            $(".searchCalcInfo", _self.calcInfoWindowId).hide();
            $(".reSearchCalcInfo", _self.calcInfoWindowId).show();
            $('.selectedDepartment_' + _self.windowId).attr('disabled', true);
            $(_self.calcInfoWindowId + ' .calcCode_displayField').attr('disabled', true);
            $(_self.calcInfoWindowId).find('.calcTypeId_valueField').prop('readonly', true).select2('readonly', true);
            $("select[name=\"criteriaTemplateId\"]", _self.calcInfoWindowId).prop('readonly', true).select2('readonly', true);
            $(_self.calcInfoWindowId + ' .searchCalcButton').attr('disabled', 'disabled');
            $(_self.calcInfoWindowId + ' .calcName_nameField').attr('disabled', true);
            $(_self.calcInfoWindowId + ' .prlCalculateType').attr('readonly', true);
            
            $('.salarySheetActions', _self.calcInfoWindowId).removeClass('hidden');
            $('.saveSalarySheet', _self.calcInfoWindowId).removeClass('hidden');
            $(".stoggler", _self.calcInfoWindowId).removeClass("hidden");            
            
            var infoText = $(_self.calcInfoWindowId).find('.calcTypeId_valueField').find("option:selected").text() + ' <span style="font-size: 10px;" class="fa fa-chevron-right mx-1">&nbsp;</span> ';
            infoText += $(_self.calcInfoWindowId).find('select[name="criteriaTemplateId"]').find("option:selected").text() + ' <span style="font-size: 10px;" class="fa fa-chevron-right mx-1">&nbsp;</span> ';
            infoText += $(_self.calcInfoWindowId).find('input[name="calcCode"]').closest(".next-generation-input-wrap").find(".next-generation-input-body").text();
            $(_self.calcInfoWindowId + ' .salary-filter-header-info').html(infoText);            
            
            if (isTrigger === undefined) {
                $('.searchCalcInfo', _self.calcInfoWindowId).attr('data-search-calc', '1');
                isTrigger = '';
            } else if (isTrigger) {
                var isTrigger = undefined;
                $('.searchCalcInfo', _self.calcInfoWindowId).attr('data-search-trigger-calc', '1');
            }
            _self.prepareDataGridStructure(isTrigger);
        }
    });
    
    $(".reSearchCalcInfo", _self.calcInfoWindowId).on("click", function (e, isTrigger) {
        if(_self.createSheetLogDatas.length > 0 && isTrigger === undefined) {
            var $dialognameConfirm = 'dialog-salary-newcalculate-confirm';
            if (!$('#'+$dialognameConfirm).length) {
                $('<div id="' + $dialognameConfirm + '"></div>').appendTo(_self.calcInfoWindowId);
            }            

            var $confirmText = 'Та цалин бодолт хадгалаагүй байна. <br> Шинэ бодолт хийхдээ итгэлтэй байна уу?';

            $("#" + $dialognameConfirm).empty().html($confirmText);
            $("#" + $dialognameConfirm).dialog({
                appendTo: "body",
                cache: false,
                resizable: true,
                bgiframe: true,
                autoOpen: false,
                title: 'Сануулга',
                width: 400,
                height: 'auto',
                modal: true,
                close: function () {
                    $("#" + $dialognameConfirm).empty().dialog('destroy').remove();
                },
                buttons: [
                    {text: plang.get('yes_btn'), class: 'btn btn-circle btn-sm btn-success', click: function () {
                        $("#" + $dialognameConfirm).dialog('close');
                        $(_self.calcInfoWindowId).find('.reSearchCalcInfo').trigger('click', [true]);                    
                    }},
                    {text: plang.get('no_btn'), class: 'btn blue-hoki btn-sm ml5', click: function () {
                        $("#" + $dialognameConfirm).dialog('close');
                    }}
                ]
            });
            $("#" + $dialognameConfirm).dialog('open');

        } else {
            
            $(".searchCalcInfo", _self.calcInfoWindowId).show();
            $(".reSearchCalcInfo", _self.calcInfoWindowId).hide();
            $('.selectedDepartment_' + _self.windowId).attr('disabled', false);
            $(_self.calcInfoWindowId + ' .calcCode_displayField').attr('disabled', false);
            $(_self.calcInfoWindowId + ' .searchCalcButton').removeAttr('disabled');
            $(_self.calcInfoWindowId + ' .calcName_nameField').attr('disabled', false);
            $(_self.calcInfoWindowId + ' .prlCalculateType').attr('readonly', false);
            $(_self.calcInfoWindowId).find('.calcTypeId_valueField').select2('readonly', false);
            $("select[name=\"criteriaTemplateId\"]", _self.calcInfoWindowId).select2('readonly', false);
        }
    });

    $(_self.calcInfoWindowId).on('click', '.salary-datarid-fullscreen-btn', function() {
        var $this = $(this);
        var $parent = $this.closest('.center-sidebar');
        
        if (!$this.hasAttr('data-fullscreen')) {        
            $(_self.calcInfoFormId).find('.form-body').hide();
            $(_self.calcInfoFormId).find('.justify-content-end').css('margin-top', '10px');
            $this.attr({'data-fullscreen': '1', 'title': 'Restore'});
            $this.find('i').removeClass('fa-expand').addClass('fa-compress');
            $parent.addClass('wordeditor-iframe-fullscreen');
            $('html').css('overflow', 'hidden');
            var dynamicHeight = $(window).height() - 85;
            $(_self.dataGridId).attr('height', dynamicHeight);
            $(_self.dataGridId).datagrid('resize', {
                height: dynamicHeight
            });            
            
        } else {
            $(_self.calcInfoFormId).find('.form-body').show();
            $(_self.calcInfoFormId).find('.justify-content-end').css('margin-top', '0px');
            $this.attr({'title': 'Fullscreen'}).removeAttr('data-fullscreen');
            $this.find('i').removeClass('fa-compress').addClass('fa-expand');
            $parent.removeClass('wordeditor-iframe-fullscreen');
            $('html').css('overflow', '');
            var dynamicHeight = $(window).height() - 275;
            $(_self.dataGridId).attr('height', dynamicHeight);
            $(_self.dataGridId).datagrid('resize', {
                height: dynamicHeight
            });                   
        }
    });    

    $(_self.calcInfoWindowId).on('click', '.salary-datarid-collapsed-btn', function() {
        var $this = $(this);
        
        if (!$this.hasAttr('data-fullscreen')) {        
            $(_self.calcInfoFormId).find('.form-body').hide();
            $(_self.calcInfoFormId).find('.justify-content-end').css('margin-top', '4px');
            $this.find('i').removeClass('fa-chevron-up').addClass('fa-chevron-down');
            $this.attr({'data-fullscreen': '1'});
            var dynamicHeight = $(window).height() - 270;
            $(_self.dataGridId).attr('height', dynamicHeight);
            $(_self.dataGridId).datagrid('resize', {
                height: dynamicHeight
            });            
            
        } else {
            $(_self.calcInfoFormId).find('.form-body').show();
            $this.removeAttr('data-fullscreen');
            $(_self.calcInfoFormId).find('.justify-content-end').css('margin-top', '0px');
            $this.find('i').removeClass('fa-chevron-down').addClass('fa-chevron-up');
            var dynamicHeight = $(window).height() - 340;
            $(_self.dataGridId).attr('height', dynamicHeight);
            $(_self.dataGridId).datagrid('resize', {
                height: dynamicHeight
            });                   
        }
    });    
    
    if($('input[name="salaryBookId"]', _self.calcInfoFormId).val() !== '') {
        setTimeout(function(){
            Core.blockUI({
                message: 'Засах горимд дуудаж байна, Түр хүлээнэ үү...',
                boxed: true
            });
        }, 0);
        $(".searchCalcInfo", _self.calcInfoWindowId).trigger('click', [true]);
    }
    
    $('#deep_' + _self.windowId).on('click', function () {
        var is_checked = $(this).is(':checked') ? 1 : 0;
        $("#deep1_" + _self.windowId).val(is_checked);
    });        
    
    $(_self.calcInfoWindowId).on('change', "input.datagrid-filter", function (e) {
        _self.filterSalary(this);
    });    
    
    $(".calculateSalarySheet", _self.calcInfoWindowId).on("click", function () {
        $(_self.calcInfoFormId).validate({errorPlacement: function () {}});

        if ($(_self.calcInfoFormId).valid()) {
            _self.calculateSalary();
        }
    });    
    
    $(".saveSalarySheet", _self.calcInfoWindowId).on("click", function () {
        $(_self.calcInfoFormId).validate({errorPlacement: function () {}});
        
        if ($(_self.calcInfoFormId).valid()) {
            _self.saveSalarySheet();
            $(".saveSalarySheet", _self.calcInfoWindowId).pulse('destroy');
        }
    });    
    
    $(".setColumnSameValue", _self.calcInfoWindowId).on("click", function () {
        _self.copyFieldRowSheet();
    });
    
    $(".copyColumn", _self.calcInfoWindowId).on("click", function () {
        _self.copyFieldColumnSheet();
    });
    
    $(".duplicateColumn", _self.calcInfoWindowId).on("click", function () {
        _self.multipleDuplicateValue();
    });
    
    $(".saveChange", _self.calcInfoWindowId).on("click", function () {
        $(_self.calcInfoFormId).validate({errorPlacement: function () {}});
        
        if ($(_self.calcInfoFormId).valid()) {
            _self.saveChangeSalarySheet();
        }        
    });  
    
    $(_self.calcInfoWindowId).on('click', '.datagrid-htable .multipleFilterClass', function () {                
        _self.multipleFilter(this);
    });    
    
    $(_self.calcInfoWindowId).on('click', '.datagrid-body td', function () {
        var i = _self.getRowIndex($(this));
        var f = $(this).attr('field'), sheetRow = _self.globalEmpObj[i], sHtml = '';
        
        $(".sheetExpression", _self.calcInfoWindowId).html('');
        _self.allShowFields.forEach(function(row){
            if(f == row.META_DATA_CODE && row.EXPRESSION != null && row.EXPRESSION != '' && row.EXPRESSION != 'null') {
                var rowExpToNamed = row.EXPRESSION;
                
                _self.allShowFields.forEach(function(row2){
                    rowExpToNamed = rowExpToNamed.replace(new RegExp(row2.META_DATA_CODE + '(?![0-9])', 'g'), row2.META_DATA_NAME);
                });
                $(".sheetExpression", _self.calcInfoWindowId).html(PL_0239 + ': <span style="font-weight: bold;">' + row.META_DATA_NAME + ' = ' + rowExpToNamed + '</span>');
            }
            
            var cellStyle = '';
            if (sheetRow.isgl === '1' && sheetRow.islock === '1') {
                cellStyle = ' readonly style="background-color: '+configRowGLColor+'"';
            } else if (sheetRow.islock === '1') {
                cellStyle = ' readonly style="background-color: '+configRowLockColor+'"';
            }                    
            
            if(row.IS_SIDEBAR === '1') {
                sHtml += '<tr class="datagrid-row" datagrid-row-index="'+i+'">';
                sHtml += '<td class="left-padding">';
                sHtml += row.META_DATA_NAME;
                sHtml += '</td>';
                sHtml += '<td field="'+row.META_DATA_CODE+'">';
                sHtml += '<input type="text"'+cellStyle+' onChange="window[\'salaryObj' + varWindowId + '\'].setSheetValue(this)" class="form-control form-control-sm" ' + (row.IS_DISABLE == '1' ? 'disabled' : '') + ' data-oldValue="' + (sheetRow[row.META_DATA_CODE] == null ? '' : sheetRow[row.META_DATA_CODE]) + '" value="' + (sheetRow[row.META_DATA_CODE] == null ? '' : sheetRow[row.META_DATA_CODE]) + '" />';
                sHtml += '</td>';
                sHtml += '</tr>';
            }
        });
        
        $('.sidebar-employee-name', _self.calcInfoWindowId).html(sheetRow.lastname + ' ' + sheetRow.firstname);
        $('.sidebar-department-name', _self.calcInfoWindowId).html(sheetRow.departmentname);
        $('.sidebar-position-name', _self.calcInfoWindowId).html(sheetRow.positionname);        
        $('.calc-sidebar', _self.calcInfoWindowId).find('tbody').html(sHtml);
    });
    
    $(_self.calcInfoWindowId).on('keyup', '.salaryNumberFormat', function (e) {
        
        function nextInput(ele) {
            var td = $(ele).closest('td').next('td');
            e.stopPropagation();
            e.preventDefault();
            var position = $(ele).index('input');
            var input = $("input").eq(position + 1);
            if (input.is('[readonly]') || td.is(":hidden") === true) {
                nextInput(input);
            } else {
                input.focus();
                input.select();
                input.trigger('click');
            }
        }

        var key = e.which;
        if (key === 13) {
            nextInput(this);
        } else if (key === 40) {
            var fieldName = $(this).closest('td').attr('field');
            var tr = $(this).closest('td').closest('tr').next('tr').find('td[field=' + fieldName + ']').find('input');
            tr.focus();
            tr.select();
            tr.trigger('click');
        } else if (key === 38) {
            var fieldName = $(this).closest('td').attr('field');
            var tr = $(this).closest('td').closest('tr').prev('tr').find('td[field=' + fieldName + ']').find('input');
            tr.focus();
            tr.select();
            tr.trigger('click');
        }
    });
    
    $("#filterDepartmentBtn_" + _self.windowId).on('click', function(){
        var filterDepValues = [], formValues = $("#filterDepartment_" + _self.windowId).val();
        if(!formValues) {
            var filterParams = {
                salaryFilter: filterDepValues,
                javaCacheId: _self.javaCacheId,
                sheet: _self.changedGlobalEmpObj,
                dataIndex: _self.dataIndex
            };
            $(_self.dataGridId).datagrid('load', filterParams);           
            return;
        }
        
        var formValuesLen = formValues.length;
        for(var i = 0; i < formValuesLen; i++)
            filterDepValues.push({
                field: 'departmentid',
                value: formValues[i],
                condition: '='
            });
        
        var filterParams = {
            salaryFilter: filterDepValues,
            javaCacheId: _self.javaCacheId,
            sheet: _self.changedGlobalEmpObj,
            dataIndex: _self.dataIndex            
        };
        $(_self.dataGridId).datagrid('load', filterParams);
    });
    
    $(_self.calcInfoWindowId).on("click", ".stoggler", function () {
        var _thisToggler = $(this);
        var centersidebar = $(".center-sidebar", _self.calcInfoWindowId);
        var rightsidebar = $(".right-sidebar", _self.calcInfoWindowId);
        var rightsidebarstatus = rightsidebar.attr("data-status");
        
        if (rightsidebarstatus === "closed") {
            centersidebar.removeClass("col-md-12").addClass("col-md-9");
            rightsidebar.addClass("col-md-3");
            rightsidebar.find(".fa-chevron-right").parent().hide();
            rightsidebar.find(".fa-chevron-left").hide();
            rightsidebar.find(".right-sidebar-content").show();
            rightsidebar.find(".fa-chevron-right").parent().fadeIn();
            rightsidebar.find(".fa-chevron-right").fadeIn();
            rightsidebar.attr('data-status', 'opened');
            _thisToggler.addClass("sidebar-opened");
            $(_self.calcInfoWindowId).find(_self.dataGridId).datagrid('resize');
        } else {
            rightsidebar.find(".fa-chevron-right").hide();
            rightsidebar.find(".fa-chevron-right").parent().hide();
            rightsidebar.find(".right-sidebar-content").hide();
            centersidebar.removeClass("col-md-9").addClass("col-md-12");
            rightsidebar.removeClass("col-md-3");
            rightsidebar.find(".fa-chevron-left").parent().fadeIn();
            rightsidebar.find(".fa-chevron-left").fadeIn();
            $(_self.calcInfoWindowId).find(_self.dataGridId).datagrid('resize');
            rightsidebar.attr('data-status', 'closed');
            _thisToggler.removeClass("sidebar-opened");
        }
    });        
    
    $(".importTemplateExcelSalary", _self.calcInfoWindowId).on("click", function () {
        var dialogname = $('#dialog-confirm-template-excel_'+_self.windowId);
        var $dialogname = 'dialog-confirm-template-excel_'+_self.windowId;
        var data = '';

        data += '<div><br><input type="checkbox" id="exportTemplate_'+_self.windowId + '"> <label for="exportTemplate_'+_self.windowId + '">Ажилтны мэдээлэлтэй гаргах эсэх</label></div>';
        dialogname.empty().html(data);
        dialogname.dialog({
            cache: false,
            resizable: true,
            bgiframe: true,
            autoOpen: false,
            title: template_excel_output,
            width: 350,
            height: 150,
            modal: true,
            open: function () {
                $('div[aria-describedby=' + $dialogname + '] .ui-dialog-buttonset').addClass("btn-group float-right");
                $('div[aria-describedby=' + $dialogname + '] .ui-dialog-buttonset').find('button:eq(0)').addClass('btn btn-sm blue mr0');
                dialogname.find('input[type="checkbox"]').uniform();
            },
            close: function () {
                dialogname.empty().dialog('close');
            },
            buttons: [
                {text: 'Үргэлжлүүлэх', click: function () {
                    if($(this).closest('div.ui-dialog').find('input[type="checkbox"]').is(':checked'))
                        var isEmployeeData = '1';
                    else
                        var isEmployeeData = '0';
                    
                    window.location.href = URL_APP + 'mdsalary/getTemplateSheetExcelCtrl?' + $(_self.calcInfoFormId).serialize() + '&javaCacheId=' + _self.javaCacheId + '&isEmployeeData=' + isEmployeeData;
                    dialogname.dialog('close');
                }}
            ]
        });
        dialogname.dialog('open');                 
    });    
    
    $(".getProcessRunSalary", _self.calcInfoWindowId).on("click", function () {
        $.ajax({
            type: 'POST',
            url: 'mdsalary/getProcessRunList',
            data: {
                params: $(_self.calcInfoFormId).serialize()
            },
            dataType: "json",
            beforeSend: function() {
                Core.blockUI( {
                    message: 'Түр хүлээнэ үү...',
                    boxed: true
                });
            },
            success: function(data) {                                
                Core.unblockUI();
                if(data.status === 'warning') {
                    PNotify.removeAll();
                    new PNotify({
                        type: data.status,
                        title: data.status,
                        text: data.text,
                        sticker: false
                    });
                    return;
                }
                _self.salaryGetProcessRun(data.getRows);
            }
        });             
    });    
    
    $(".importExcelSalary", _self.calcInfoWindowId).on("click", function () {
        $('#salary_fileupload_' + _self.windowId).find('.selectedExcelFile').val('');
        $('#salary_fileupload_' + _self.windowId).find('.selectedExcelFile').trigger('click');
    }); 
    
    $('#salary_fileupload_' + _self.windowId).on('change', '.selectedExcelFile', function(){
        $($(this).closest('form')).ajaxSubmit({
            type: 'POST',
            url: 'mdsalary/salaryDataImportLoadData',
            data: {
                params: $(_self.calcInfoFormId).serialize(),
                javaCacheId: _self.javaCacheId
            },
            dataType: "json",
            beforeSend: function() {
                Core.blockUI( {
                    message: 'Түр хүлээнэ үү...',
                    boxed: true
                });
            },
            success: function(data) {                                
                Core.unblockUI();
                if(data.status === 'warning') {
                    PNotify.removeAll();
                    new PNotify({
                        type: data.status,
                        title: data.status,
                        text: data.text,
                        sticker: false
                    });
                } else
                    _self.salaryTemplateExcelImportFields(data);
            }
        });
    });
    
    $(".tool-collapse", _self.calcInfoWindowId).on('click', function () {
        var _this = $(this);
        if(_this.hasClass('collapse')) {
            $(".card-collapse", _self.calcInfoWindowId).removeClass('_collapse');
            var dynamicHeight = $(window).height() - 205;
            $(_self.dataGridId).attr('height', dynamicHeight);
            $(_self.dataGridId).datagrid('resize', {
                height: dynamicHeight
            });
        } else {
            $(".card-collapse", _self.calcInfoWindowId).addClass('_collapse');
            var dynamicHeight = $(window).height() - 300;
            $(_self.dataGridId).attr('height', dynamicHeight);
            $(_self.dataGridId).datagrid('resize', {
                height: dynamicHeight
            });
        }
    });
    
    $(".card-collapse", _self.calcInfoWindowId).on('click', function () {
        var _this = $(this);
        if(_this.hasClass('_collapse')) {
            _this.removeClass('_collapse');
            var dynamicHeight = $(window).height() - 205;
            $(_self.dataGridId).attr('height', dynamicHeight);
            $(_self.dataGridId).datagrid('resize', {
                height: dynamicHeight
            });
        } else {
            _this.addClass('_collapse');
            var dynamicHeight = $(window).height() - 300;
            $(_self.dataGridId).attr('height', dynamicHeight);
            $(_self.dataGridId).datagrid('resize', {
                height: dynamicHeight
            });
        }
    });
    
    $(".exportExcelSalary", _self.calcInfoWindowId).on("click", function () {
        
        if(_self.isEditMode) {
            Core.blockUI({
                boxed: true
            });            
            var footer = $(_self.dataGridId).datagrid('getFooterRows');
            var footers = JSON.stringify(footer[0]);
            window.location.href = URL_APP + 'mdsalary/export_excel_v4?' + $(_self.calcInfoFormId).serialize() + '&footers=' + footers + '&javaCacheId=' + _self.javaCacheId;
            Core.unblockUI();
        } else {
            PNotify.removeAll();
            new PNotify({
                title: 'Анхааруулга',
                text: 'Та хадгалсны дараа Excel хэлбэрээр авах боломжтой.',
                type: 'warning',
                sticker: false
            });
        }
    });    
    
    $(".selectedDepartmentNamesContainerBtn", _self.calcInfoWindowId).toggle(function(){
        $(this).text('Хураах');
        $('.next-generation-input-wrap-1', _self.calcInfoWindowId).animate({"height": 140}, 200, function(){
            $('#selectedDepartmentNamesContainer_' + _self.windowId).css({"max-height": 120, "overflow": "auto"});
        });        
    },function(){
        $(this).text('Дэлгэрэнгүй харах');
        $('.next-generation-input-wrap-1', _self.calcInfoWindowId).animate({"height": 60}, 200, function(){
            $('#selectedDepartmentNamesContainer_' + _self.windowId).css({"max-height": 50, "overflow": "auto"});
        });
    });    
    
    if($('.departmentIdName_' + _self.windowId).val() !== '') {
        var depNames = $('.departmentIdName_' + _self.windowId).val().split('__');
        $('#selectedDepartmentNamesContainer_' + _self.windowId).text(depNames.join(', '));
        $('.selectedDepartmentNamesWrap', _self.calcInfoWindowId).removeClass('hidden');
    }
    
    // Start Select2 ----->
    var singleClick = 0;
    $('.selectedDepartment_' + _self.windowId).on('click', function () {
        if($('.selectedDepartmentIco_' + _self.windowId).hasClass('fa-angle-up'))
            return;
        
        if (singleClick == 0) {
            singleClick = 1;
            var _jtreewidth = 550 - 1;
            $('.departmentlist-jtree-' + _self.windowId).width(_jtreewidth);
            $('.departmentlist-jtree-' + _self.windowId).find('.jstree-container-ul').width(_jtreewidth - 12);
            $('.departmentlist-jtree-' + _self.windowId).removeClass('hidden');
            $('.selectedDepartmentIco_' + _self.windowId).removeClass('fa-angle-down').addClass('fa-angle-up');
        } else {
            singleClick = 0;
            $('.departmentlist-jtree-' + _self.windowId).addClass('hidden');
            $('.selectedDepartmentIco_' + _self.windowId).removeClass('fa-angle-up').addClass('fa-angle-down');
        }
    });    
    $(document).keyup(function (e) {
        if (e.which == 27) {
            closeselectedDepartmentJtree();
        }
    });
    $(document).click(function (e) {
        if ($(e.target)[0].className != 'jstree-icon jstree-ocl') {
            if ($(e.target).parents('.departmentlist-jtree-' + _self.windowId).length === 0) {
                closeselectedDepartmentJtree();
            }
        }
    });
    function closeselectedDepartmentJtree() {
        singleClick = 0;
        $('.departmentlist-jtree-' + _self.windowId).addClass('hidden');
        $('.selectedDepartmentIco_' + _self.windowId).removeClass('fa-angle-up').addClass('fa-angle-down');
    }
    $('.selectedDepartmentIco_' + _self.windowId).on('click', function () {
        if (singleClick == 0) {
            singleClick = 1;
            var _jtreewidth = 550 - 1;
            $('.departmentlist-jtree-' + _self.windowId).width(_jtreewidth);
            $('.departmentlist-jtree-' + _self.windowId).find('.jstree-container-ul').width(_jtreewidth - 12);
            $('.departmentlist-jtree-' + _self.windowId).removeClass('hidden');
            $('.selectedDepartmentIco_' + _self.windowId).removeClass('fa-angle-down').addClass('fa-angle-up');
        } else {
            singleClick = 0;
            $('.departmentlist-jtree-' + _self.windowId).addClass('hidden');
            $('.selectedDepartmentIco_' + _self.windowId).removeClass('fa-angle-up').addClass('fa-angle-down');
        }
    });    
    var selectedDepartmentId = [],
        selectedDepartmentText = [],
        selectedDepartment = false;

    $.jstree.defaults.search.ajax = true
    $('.list-jtree-' + _self.windowId).on("changed.jstree", function (e, data) {
        if (data.action === "select_node") {
            if ($.inArray(data.node.id, selectedDepartmentId) < 0) {
                selectedDepartment = true;
                selectedDepartmentId.push(data.node.id);
                selectedDepartmentText.push('<span data-id="'+data.node.id+'">'+data.node.text+' <i class="fa fa-times-circle hidden"></i></span>');
            }
            selectNode(data.node.id);
            $.each(data.node.children_d, function (key, value) {
                selectNode(value);
                if (value != '#') {
                    if ($.inArray(value, selectedDepartmentId) < 0) {
                        selectedDepartmentId.push(value);
                        var getDepName = $('.list-jtree-' + _self.windowId).jstree("get_node", value);
                        selectedDepartmentText.push('<span data-id="'+value+'">'+getDepName.text+' <i class="fa fa-times-circle hidden"></i></span>');
                    }
                }
            });
        } else if (data.action === "deselect_node") {
            var _index = selectedDepartmentId.indexOf(data.node.id);
            selectedDepartment = true;
            selectedDepartmentId.splice(_index, 1);
            selectedDepartmentText.splice(_index, 1);
            deSelectNode(data.node.id);
            $.each(data.node.children_d, function (key, value) {
                var indexMid = selectedDepartmentId.indexOf(value);
                selectedDepartmentId.splice(indexMid, 1);
                selectedDepartmentText.splice(indexMid, 1);
                deSelectNode(value);
            });
        } else if (data.action === "select_all") {
            selectedDepartment = true;
            $(this).children().children().each(function(){
                selectedDepartmentId.push($(this).attr('id'));
                selectedDepartmentText.push('<span data-id="'+$(this).attr('id')+'">'+$(this).text()+' <i class="fa fa-times-circle hidden"></i></span>');
            });
        }
        if(!selectedDepartment)
            return;
        
        $('.departmentId_' + _self.windowId).val(selectedDepartmentId);
        $('.departmentIdName_' + _self.windowId).val(selectedDepartmentText.join('__'));        
        $('#selectedDepartmentNamesContainer_' + _self.windowId).empty().append(selectedDepartmentText.join(', '));
        if(selectedDepartmentText.length > 0)
            $('.selectedDepartmentNamesWrap', _self.calcInfoWindowId).removeClass('hidden');
        else
            $('.selectedDepartmentNamesWrap', _self.calcInfoWindowId).addClass('hidden');
    }).jstree({
        'core': {
            expand_selected_onload: false,
            "open_parents": false,
            "load_open": false,
            'data': {
                url: URL_APP + 'mdsalary/getDeparmentListJtreeData',
                dataType: "json",
                data: function (node) {
                    return {
                        parentId: (node.id === "#" ? '' : node.id),
                        parentNode: 0,
                        depIds: _self.selectedDeps,
                        pSelected: node.state.selected ? '1' : '0'
                    };
                }
            },
            'themes': {
                'responsive': false,
                'stripes': true
            }
        },
        "checkbox": {
            keep_selected_style: false,
            real_checkboxes: true,
            real_checkboxes_names: function (n) {
                var nid = 0;
                $(n).each(function (data) {
                    nid = $(this).attr("nodeid");
                });
                return (["check_" + nid, nid]);
            },
            three_state: false,
            two_state: true,
            whole_node: true
        },
        /*"search": {
            "case_insensitive": true,
            'fuzzy': false,
            "ajax": {
                "url": URL_APP + 'mdsalary/getDeparmentListJtreeData',
                "dataType": "json",
                "success": function(data){
                    $('.list-jtree-' + _self.windowId).jstree(true).settings.core.data = data;
                    $('.list-jtree-' + _self.windowId).jstree(true).refresh();
                }
            }
        },*/
        'types': {
            "default": {
                "icon": "fa fa-play-circle text-orange-400"
            },
            "file": {
                "icon": "fa fa-play-circle text-orange-400"
            }
        },
        'unique': {
            'duplicate': function (name, counter) {
                return name + ' ' + counter;
            }
        },
        'plugins': [
            'changed', 'types', 'unique', 'wholerow', 'checkbox', 'search'
        ]
    });
    
    $('#selectedDepartmentNamesContainer_' + _self.windowId).on('click', 'i', function(){
        var depId = $(this).parent().attr('data-id');
        var _index = selectedDepartmentId.indexOf(depId);
        
        selectedDepartmentId.splice(_index, 1);
        selectedDepartmentText.splice(_index, 1); 
        deSelectNode(depId);
        
        $('.departmentId_' + _self.windowId).val(selectedDepartmentId);
        $('.departmentIdName_' + _self.windowId).val(selectedDepartmentText.join('__'));        
        $('#selectedDepartmentNamesContainer_' + _self.windowId).empty().append(selectedDepartmentText.join(', '));
        if(selectedDepartmentText.length > 0)
            $('.selectedDepartmentNamesWrap', _self.calcInfoWindowId).removeClass('hidden');
        else
            $('.selectedDepartmentNamesWrap', _self.calcInfoWindowId).addClass('hidden');        
    });
    
    var to = null;
    $(".departmentList_search_" + _self.windowId).on('keydown', function (e) {
        var keyCode = (e.keyCode ? e.keyCode : e.which);
        
        if (keyCode === 13) {
            setTimeout(function() {
                Core.blockUI({
                    target: $('.groupDepartmentId_' + _self.windowId),
                    animate: false,
                    icon2Only: true
                });
            }, 1);
            
            var _thisVal = $(this).val();
            if (to != null) {
                to.abort();
                to = null;
            }

            to = $.ajax({
                type: 'get',
                url: URL_APP + 'mdsalary/getDeparmentListJtreeData',
                dataType: 'json',
                data: {
                    str: _thisVal
                },
                success: function(data) {
                    $('.list-jtree-' + _self.windowId).jstree(true).settings.core.data = data;
                    $('.list-jtree-' + _self.windowId).jstree(true).refresh();
                    Core.unblockUI($('.groupDepartmentId_' + _self.windowId));
                }
            });

            e.preventDefault();
            return false;        
        }
    });
    $('.department-multiselect-all-' + _self.windowId).on('click', function () {
        if($(this).hasClass('allCheckedData'))
            return;
        $(this).addClass('allCheckedData');
        $('.list-jtree-' + _self.windowId).jstree("select_all");
    });
    $('.department-multiselect-none-' + _self.windowId).on('click', function () {
        $('.department-multiselect-all-' + _self.windowId).removeClass('allCheckedData');
        selectedDepartmentId = [];
        selectedDepartmentText = [];        
        $('.list-jtree-' + _self.windowId).jstree("deselect_all");
    });
    var selectNode = function (id) {
        $('.list-jtree-' + _self.windowId).jstree("select_node", id, true, true);
    };
    var deSelectNode = function (id) {
        $('.list-jtree-' + _self.windowId).jstree("deselect_node", id, true, true);
    };    
    // End Select2 <-----
    
    $(".salaryBackBtn", _self.calcInfoWindowId).on("click", function () {
        var _t = $(this);
        $('a[href="#'+_t.closest('.tab-pane').parent().closest('.tab-pane').attr('id')+'"] > span').trigger('click');
    });
    
    $('body').on('click', 'div.card-multi-tab > .tabbable-line > .card-multi-tab-navtabs > li > a > span, div.card-multi-tab > .tabbable-line > .card-multi-tab-navtabs > li > ul > li > a > span', function (e) {
        e.stopPropagation();
        e.preventDefault();
        
        if(_self.createSheetLogDatas.length > 0) {
            var $confirmText = 'Та цалин бодолт хадгалаагүй байна. <br> Хаахдаа итгэлтэй байна уу?';
        } else
            var $confirmText = 'Та хаахдаа итгэлтэй байна уу?';
        
        var _this = $(this);
        var _li = _this.closest('a').closest('li');
        var tabType = _li.attr('data-type');

        if (tabType == 'dataview' || tabType == 'layout' || tabType == 'content' || tabType == 'package') {
            $("div.card-multi-tab > div.card-body > div.card-multi-tab-content").find("div"+_this.closest('a').attr('href')).empty().remove();
            var prevLi = _li.prev('li:not(.tabdrop)');
            if (prevLi.length === 0) {
                var prevLi = _li.next('li:not(.tabdrop)');
            }
            _li.remove();
            prevLi.find('a').tab('show');

            return;
        }

        var $dialogName = 'dialog-window-close-confirm-salary';

        if (!$("#" + $dialogName).length) {
            $('<div id="' + $dialogName + '"></div>').appendTo('body');            
            $("#" + $dialogName).empty().html($confirmText);
        }

        $("#" + $dialogName).dialog({
            cache: false,
            resizable: false,
            bgiframe: true,
            autoOpen: false,
            title: plang.get('msg_title_confirm'),
            width: 330,
            height: "auto",
            modal: true,
            buttons: [
                {text: plang.get('yes_btn'), "class": 'btn green-meadow btn-sm', click: function () {
                    $("div.card-multi-tab > div.card-body > div.card-multi-tab-content").find("div"+_this.closest('a').attr('href')).empty().remove();
                    var prevLi = _li.prev('li:not(.tabdrop)');
                    if (prevLi.length === 0) {
                        var prevLi = _li.next('li:not(.tabdrop)');
                    }
                    
                    _li.remove();
                    _self.createSheetLogDatas = [];
                    _self.changedGlobalEmpObj = [];
                    prevLi.find('a').tab('show');
                    $("#" + $dialogName).dialog('close');
                }},
                {text: plang.get('no_btn'), "class": 'btn blue-madison btn-sm', click: function () {
                    $("#" + $dialogName).dialog('close');
                }}
            ]
        });
        $("#" + $dialogName).dialog('open');
    });
    
    $('body').on('dblclick', 'div.card-multi-tab > .tabbable-line > .card-multi-tab-navtabs > li > a, div.card-multi-tab > .tabbable-line > .card-multi-tab-navtabs > li > ul > li > a', function (e) {
        e.stopPropagation();
        e.preventDefault();
        
        if(_self.createSheetLogDatas.length > 0) {
            var $confirmText = 'Та цалин бодолт хадгалаагүй байна. <br> Хаахдаа итгэлтэй байна уу?';
        } else
            var $confirmText = 'Та хаахдаа итгэлтэй байна уу?';
        
        var _this = $(this);
        var _li = _this.closest('a').closest('li');
        var tabType = _li.attr('data-type');

        if (tabType == 'dataview' || tabType == 'layout' || tabType == 'content' || tabType == 'package') {
            $("div.card-multi-tab > div.card-body > div.card-multi-tab-content").find("div"+_this.closest('a').attr('href')).empty().remove();
            var prevLi = _li.prev('li:not(.tabdrop)');
            if (prevLi.length === 0) {
                var prevLi = _li.next('li:not(.tabdrop)');
            }
            _li.remove();
            prevLi.find('a').tab('show');

            return;
        }

        var $dialogName = 'dialog-window-close-confirm-salary';

        if (!$("#" + $dialogName).length) {
            $('<div id="' + $dialogName + '"></div>').appendTo('body');            
            $("#" + $dialogName).empty().html($confirmText);
        }

        $("#" + $dialogName).dialog({
            cache: false,
            resizable: false,
            bgiframe: true,
            autoOpen: false,
            title: plang.get('msg_title_confirm'),
            width: 330,
            height: "auto",
            modal: true,
            buttons: [
                {text: plang.get('yes_btn'), "class": 'btn green-meadow btn-sm', click: function () {
                    $("div.card-multi-tab > div.card-body > div.card-multi-tab-content").find("div"+_this.closest('a').attr('href')).empty().remove();
                    var prevLi = _li.prev('li:not(.tabdrop)');
                    if (prevLi.length === 0) {
                        var prevLi = _li.next('li:not(.tabdrop)');
                    }
                    
                    _li.remove();
                    _self.createSheetLogDatas = [];
                    _self.changedGlobalEmpObj = [];
                    prevLi.find('a').tab('show');
                    $("#" + $dialogName).dialog('close');
                }},
                {text: plang.get('no_btn'), "class": 'btn blue-madison btn-sm', click: function () {
                    $("#" + $dialogName).dialog('close');
                }}
            ]
        });
        $("#" + $dialogName).dialog('open');
    });
    
    var multiTabId = $(_self.calcInfoFormId).closest('.tab-pane').attr('id');
    $('div.card-multi-tab > .tabbable-line > .card-multi-tab-navtabs > li').find('> a[href="#'+multiTabId+'"] > span').addClass('custom-close-tab');
    
    window.onbeforeunload = function () {
        if(_self.createSheetLogDatas.length > 0) {
            return true;
        } else
            return undefined;
    };    
    
    $('#prl_salary_tname_' + _self.windowId).on('click', 'li', function(){
        var _this = $(this), tabId = _this.find('a').attr('href');        
        
        if(typeof _this.attr('data-fetch') === 'undefined') {
            Core.blockUI({
                animate: true
            });                 
            $.ajax({
                type: 'post',
                url: 'mdsalary/getSalaryAllListWebservice',
                data: {
                    javaCacheId: _self.javaCacheId,
                    fieldPath: _self.tabNameLi
                },
                dataType: "json",
                success: function (data) {
                    if (data.status == 'error') {
                        PNotify.removeAll();
                        new PNotify({
                            title: 'Анхааруулга',
                            text: data.text,
                            type: 'warning',
                            sticker: false
                        });
                    } else {                
                        _this.attr('data-fetch', '');
                        var htmlStr = '', 
                            rowsLength = data.rows.length, 
                            ii = 0;
                        
                        for(var i = 0; i < rowsLength; i++) {
                            for(ii; ii < data.rows[i]['rows'].length; ii++) {
                                htmlStr += '<div class="appmenu-table-cell-right mix-grid">'+
                                '<a href="javascript:;" class="vr-menu-tile mix 001 mix_all prlSalaryLinkedCard" data-fieldpath="'+data.rows[i]['fieldCode']+'" data-calc-dtl-id="'+data.rows[i]['calcDtlId']+'" data-empkeys="'+ data.rows[i]['rows'][ii]['empKeys'] +'" style="display: block;  opacity: 1;">'+
                                '<div class="salary-cart-title">'+data.rows[i]['fieldName']+'</div>'+
                                '<div class="vr-menu-cell">'+
                                '<div class="vr-menu-img">'+
                                    '<img src="assets/core/global/img/user2.png">'+
                                '</div>'+
                                '</div>'+
                                '<div class="vr-menu-title"><div class="vr-menu-row">'+
                                '<div class="vr-menu-name" style="height:35px;" data-app-name="true">' + data.rows[i]['rows'][ii]['fieldCount'] + '</div></div></div>'+
                                /*'<div class="vr-menu-descr">' + data.rows[i]['rows'][ii]['fieldValue'] + '</div>'+*/
                                '</a></div>';
                            }
                        }
                        
                        $(tabId).find('div:first').empty().append(htmlStr);
                    }
                    Core.unblockUI();
                }
            });
        }
    });
    
    $(_self.calcInfoWindowId).on('click', '.prlSalaryLinkedCard', function () {
        var _this = $(this);
        var $dialogname = 'dialog-salary-prlSalaryLinkedCard';
    
        if (!$('#'+$dialogname).length) {
            $('<div id="' + $dialogname + '"></div>').appendTo('#calculateSalarySheetDiv_' + _self.windowId);
        }
        var dialogname = $('#dialog-salary-prlSalaryLinkedCard', '#calculateSalarySheetDiv_' + _self.windowId);        
        var data = '<div class="row">'+
                '<div class="col-md-12 jeasyuiTheme3 mt5">'+
                    '<table class="no-border" id="prlSalaryLinkedCardDatagrid_' + _self.windowId + '" style="width: 100%;"></table>'+
                '</div>'+
            '</div>';
        var dtlCardFields = {};

        $.ajax({
            type: 'post',
            url: 'mdsalary/getCalcTypeDtlCard/' + _this.data('calc-dtl-id'),
            async: false,
            dataType: "json",
            success: function (data) {
                dtlCardFields = data;
            }
        });                    
            
        dialogname.empty().html(data);
        dialogname.dialog({
            cache: false,
            appendTo: '#calculateSalarySheetDiv_' + _self.windowId,
            resizable: true,
            bgiframe: true,
            autoOpen: false,
            title: 'Жагсаалт',
            width: 950,
            height: 'auto',
            modal: true,
            position: {my: 'top', at: 'top+50'},
            open: function () {
                $('div[aria-describedby=' + $dialogname + '] .ui-dialog-buttonset').addClass("btn-group float-right");
                $('div[aria-describedby=' + $dialogname + '] .ui-dialog-buttonset').find('button:eq(0)').addClass('btn blue-hoki btn-sm ml5');
            },
            close: function () {
                dialogname.empty().dialog('destroy').remove();
            },
            buttons: [
                {text: plang.get('close_btn'), click: function () {
                    dialogname.empty().dialog('close');
                }}
            ]
        });
        dialogname.dialog('open');

        var cardFields = [], cardLoop = 0;

        for (cardLoop; cardLoop < _self.fields.length; cardLoop++) {
            if (dtlCardFields.hasOwnProperty(_self.fields[cardLoop]['field'])) {
                cardFields.push(_self.fields[cardLoop]);
            }
        }
        
        $("#prlSalaryLinkedCardDatagrid_" + _self.windowId).datagrid({
            url: 'mdsalary/getSalaryListWebservice',
            queryParams: {
                javaCacheId: _self.javaCacheId,
                empKeys: _this.data('empkeys')
            },
            fit: false,
            fitColumns: false,
            rownumbers: true,
            singleSelect: false,
            showFooter: false,
            ctrlSelect: true,
            pagination: true,
            remoteFilter: false,
            loadMsg: 'Ажилтны мэдээлэл ачааллаж байна, Түр хүлээнэ үү',
            pageSize: _self.selectedPage,
            pageList: [
                50, 100, 150, _self.unlimitPage, 400, 600, 800, 1000, 2000
            ],
            remoteSort: true,
            frozenColumns: [_self.frozenFields],
            columns: [cardFields],
            sortName: 'firstname',
            sortOrder: 'asc',
            onLoadSuccess: function(data) {
            },            
            onLoadError: function () {
                alert('Ажилтны мэдээлэл ачааллахад алдаа гарлаа!');
            }
        });
        
        /*var $filterWidth1 = 0,
            $filterWidth2 = 0,
            $filterWidth3 = 0;
        $(_self.calcInfoWindowId).find('.datagrid').find('.datagrid-view1').find('.datagrid-htable tbody').find('tr').find('td').each(function () {
            var $td = $(this);
            if ($td.attr('field') === 'employeecode') {
                $filterWidth1 = $td.width() + 6;
            }
            if ($td.attr('field') === 'lastname') {
                $filterWidth2 = $td.width() + 6;
            }
            if ($td.attr('field') === 'firstname') {
                $filterWidth3 = $td.width() + 6;
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
        $(_self.calcInfoWindowId).find('.datagrid').find('.datagrid-view1').find('.datagrid-htable').find('tbody').append(filterHtml);
        $(_self.calcInfoWindowId).find('.datagrid').find('.datagrid-view2').find('.datagrid-htable').find('tbody').append(filters);       
        */
    }); 

    function everyRequestSalary() {
        $.ajax({
            url: "mdsalary/everyRequestSalary",
            type: "GET",
            dataType: "json",
            beforeSend: function () {
            },        
            success: function (resp) {
                if(resp.status === 'success') {
                    if ($('.ui-pnotify-modal-overlay:visible').length) {
                        return;
                    }

                    PNotify.removeAll();
                    var msgTxt = "<ul>", i;

                    for (i = 0; i < resp.message.length; i++) {
                        msgTxt += "<li>";
                        msgTxt += resp.message[i]['MESSAGE'].toString();
                        msgTxt += "</li>";
                    }
                    msgTxt += "</ul>"                    

                    new PNotify({
                        title: resp.status,
                        text: msgTxt,
                        type: resp.status,
                        hide: false,
                        addclass: 'stack-modal',
                        width: '400px',
                        confirm: {
                            confirm: true,
                            buttons: [{
                                text: plang.get('close_btn'),
                                addClass: 'btn btn-primary',
                                click: function(notice) {
                                    notice.remove();
                                    $.ajax({
                                        url: "mdsalary/deleteEveryRequestSalary",
                                        type: "GET"
                                    });
                                }
                            },
                            null]
                        },
                        after_open: function (notify) {
                            setTimeout(function() {
                                $('.btn:first-child', notify.container).focus();
                            }, 100);
                        }, 
                        stack: {
                            "dir1": "down",
                            "dir2": "right",
                            "modal": true,
                            "overlay_close": false
                        }, 
                        buttons: {
                            closer: false,
                            sticker: false
                        },
                        history: {
                            history: false
                        }
                    });               
                }
            }
        });
    }

    setInterval(everyRequestSalary, 300000);    
    
    $('select.prlCalculateType', _self.calcInfoFormId).on('change', function(){
        var $thisck = $(this).val();
        
        if ($thisck === 'employee') {
            $('.calculate-employee-cls', _self.calcInfoFormId).removeClass('hidden');
            $('.calculate-department-cls', _self.calcInfoFormId).addClass('hidden');
            $('.prlCalculateType:last', _self.calcInfoFormId).attr('name', 'prlCalculateType').val('employee');
            $('.prlCalculateType:first', _self.calcInfoFormId).removeAttr('name');
            
            $('.departmentId_' + _self.windowId).val('');
            $('.departmentIdName_' + _self.windowId).val('');
            $('#selectedDepartmentNamesContainer_' + _self.windowId).text('');      
            
        } else {
            
            $('#employee_valueField', _self.calcInfoFormId).val('');
            $('#employeeCode_displayField', _self.calcInfoFormId).val('');
            $('.tms-departmentname-'+_self.windowId).closest('.next-generation-input-body').text('');
            $('.calculate-employee-cls', _self.calcInfoFormId).addClass('hidden');
            $('.calculate-department-cls', _self.calcInfoFormId).removeClass('hidden');
            $('.prlCalculateType:first', _self.calcInfoFormId).attr('name', 'prlCalculateType').val('department');
            $('.prlCalculateType:last', _self.calcInfoFormId).removeAttr('name');            
        }
    });    
    
    $('body').on('click', '.prl-salary-custom-column', function(){
        var $this = $(this);
        
        if ($this.is(':checked')) {
            $this.closest('tr').find('input[name="IS_HIDE_USER_COL[]"]').val('1');
        } else {
            $this.closest('tr').find('input[name="IS_HIDE_USER_COL[]"]').val('0');
        }
    });    
    
    $('body').on('click', '.prl-salary-custom-column-check-all', function(){
        var $this = $(this);
        var $tbody = $this.closest('table').find('tbody > tr');
        
        $tbody.each(function(){
            if ($this.is(':checked')) {
                $(this).find('input[name="IS_HIDE_USER_COL[]"]').val('1');
                $(this).find('.prl-salary-custom-column').prop('checked', true);
            } else {
                $(this).find('input[name="IS_HIDE_USER_COL[]"]').val('0');
                $(this).find('.prl-salary-custom-column').prop('checked', false);
            }            
        });
        
        $.uniform.update();
    });    
    
    $(_self.calcInfoWindowId).on('keydown', 'table tbody input', function(e) {
        var key = e.which;
        var tr = $(this).closest('tr');
        var td = $(this).closest('td');        
        
        if (key == 39) {
            td = td.next('td');
            if (typeof td !== 'undefined') {
                _self.selectInput(td.find("input"));
                setTimeout(function () {
                    td.find("input").select();
                }, 2);
            }
        }
        
        if (key == 37) {
            td = td.prev('td');
            if (typeof td !== 'undefined') {
                _self.selectInput(td.find("input"));
                setTimeout(function () {
                    td.find("input").select();
                }, 2);
            }
        }
    });                  
    
    $("select[name=\"calcTypeId\"]", _self.calcInfoWindowId).on("change", function () {
        if (configCriteriaTemplateJS === '1') {
            $("select[name=\"criteriaTemplateId\"]", _self.calcInfoWindowId).prop('readonly', false).prop('disabled', false).select2('readonly', false).removeClass('data-combo-set');            
            $("input[name=\"calcCode\"]", _self.calcInfoWindowId).prop('disabled', false);
            $("input[name=\"calcCode\"]", _self.calcInfoWindowId).parent().find('button').prop('disabled', false);
        }
        if (configCriteriaTemplateJS2 === '1') {
            $("input[name=\"calcCode\"]", _self.calcInfoWindowId).prop('disabled', false);
            $("input[name=\"calcCode\"]", _self.calcInfoWindowId).parent().find('button').prop('disabled', false);
        }        

        $("input[name=\"calcId\"]", _self.calcInfoWindowId).val("").attr("data-startdate", "").attr("data-enddate", "");
        $("input[name=\"calcId\"]", _self.calcInfoWindowId).closest(".next-generation-input-wrap").find(".next-generation-input-body").text("");
        $("input[name=\"calcCode\"]", _self.calcInfoWindowId).val("");        

        $.ajax({
            url: "mdsalary/getSuggestionCalcRow",
            type: "POST",
            data: {
                calcTypeId: $(this).val()
            },
            dataType: "json",
            success: function (resp) {
                if (resp && resp.hasOwnProperty('id')) {
                    $("input[name=\"calcId\"]", _self.calcInfoWindowId).val(resp.id).attr("data-startdate", resp.startdate).attr("data-enddate", resp.enddate);
                    $("input[name=\"calcId\"]", _self.calcInfoWindowId).closest(".next-generation-input-wrap").find(".next-generation-input-body").text(resp.calcname);
                    $("input[name=\"calcCode\"]", _self.calcInfoWindowId).val(resp.calccode);
                }
            }
        });          
    });    
    
    $(_self.calcInfoWindowId).on('select2-opening', 'select[name="criteriaTemplateId"]', function(e, isTrigger) {
        if (configCriteriaTemplateJS === '1') {
            var $this = $(this), 
                $relateElement = $this.prev('.select2-container:eq(0)');

            if (!$this.hasClass("data-combo-set")) {
                var select2 = $this.data('select2');

                $this.addClass("data-combo-set");
                Core.blockUI({
                    target: $relateElement,
                    animate: false,
                    icon2Only: true
                });

                var comboDatas = [];
                $.ajax({
                    type: 'post',
                    async: false,
                    url: 'mdsalary/getDataviewTemplateData',
                    data: {'bookTypeId': '15000', 'calcTypeId': $("select[name=\"calcTypeId\"]", _self.calcInfoWindowId).val()},
                    dataType: 'json',
                    success: function(data) {
                        $this.empty();
                        if (data.length) { 
                            $this.append($('<option />').val('').text(plang.get('choose')));  

                            $.each(data, function(){
                                $this.append($("<option />")
                                    .val(this.criteriatemplateid)
                                    .text(this.criteriatemplatename));
                                comboDatas.push({
                                    id: this.criteriatemplateid,
                                    text: this.criteriatemplatename
                                });                     
                            });
                        }
                    },
                    error: function () {
                        alert("Ajax Error!");
                    } 
                }).done(function(){

                    Core.unblockUI($relateElement);
                    $this.select2({results: comboDatas, closeOnSelect: false});
                    if (typeof isTrigger === 'undefined' && !select2.opened()) {
                        $this.select2('open');
                    }
                });
            }
        }
    });            
    
    console.log("%cVeritech ERP - Salary", "background: #000; color: yellow; font-size: 32px");
};

SalaryV3.prototype.prepareDataGridStructure = function(isTrigger) {
    var _self = this;
    _self.fields = [];
    _self.fieldsColspan = [];
    _self.dataGridfields = [];
    _self.allShowFields = [];

//    if (_self.selectedDeps.length === 0) {
//        return; 
//    }
    
    var isUseBookNumber = false, searchTriggerClick = false;
    
    if ($(_self.calcInfoFormId).find("select[name=\"calcTypeId\"]").find('option:selected').hasAttr('data-usebooknumber') 
            && $(_self.calcInfoFormId).find("select[name=\"calcTypeId\"]").find('option:selected').attr('data-usebooknumber') != '' 
            && $('.searchCalcInfo', _self.calcInfoWindowId).hasAttr('data-search-calc')) {
        
        if ($(_self.calcInfoFormId).find("select[name=\"calcTypeId\"]").find('option:selected').attr('data-usebooknumber') == '1') {
            
            $(_self.calcInfoFormId).find('input[name="javaCacheId"], input[name="salaryBookId"], input[name="batchNumber"], input[name="bookNumber"]').val('');
            $(_self.calcInfoFormId).find('input[name="fromCache"], input[name="isBatchNumber"]').val('0');
            
            isUseBookNumber = true;
        }
    }
    
    if($('.searchCalcInfo', _self.calcInfoWindowId).hasAttr('data-search-trigger-calc')) {
        isUseBookNumber = false;
        searchTriggerClick = true;
    }
    
    $('.searchCalcInfo', _self.calcInfoWindowId).removeAttr('data-search-calc');
    $('.searchCalcInfo', _self.calcInfoWindowId).removeAttr('data-search-trigger-calc');
    
    var postData = {
        params: $(_self.calcInfoFormId).serialize()
    };
    
    if (isUseBookNumber) {
        postData['usebooknumber'] = 1;
    }
    
    $.ajax({
        type: 'post',
        url: 'mdsalary/getCalcFieldV3List', 
        data: postData, 
        dataType: "json",
        beforeSend: function () {
            var blockMsg = 'Цалин бодолтод бэлдэж байна, Түр хүлээнэ үү...';

            Core.blockUI({
                message: blockMsg,
                boxed: true
            });
        },
        success: function (data) {        
            if (data.status === 'success') {
                
                if(data.batchNumber != '' && (data.bookNumber == '' || data.bookNumber == null) && !searchTriggerClick && configCalculation == '1') {
                    _self.warningBatchNumber();
                    
                    $('.salarySheetActions', _self.calcInfoWindowId).addClass('hidden');
                    $('.saveSalarySheet', _self.calcInfoWindowId).addClass('hidden');
                    Core.unblockUI();
                    return;
                }
                
                _self.frozenFields = [{
                    field: 'ck', 
                    checkbox: true                        
                }, {
                    field: 'employeecode', 
                    title: code_globecode, 
                    width: 115, 
                    sortable: true,
                    styler: function (value, row) {
                        if (row && row.isgl === '1' && row.islock === '1') {
                            return "background-color: "+configRowGLColor+"; padding-left:6px;";                            
                        } else if (row && row.islock === '1') {
                            return "background-color: "+configRowLockColor+"; padding-left:6px;";
                        }
                        return "background-color: #ffddd3;padding-left:6px;";
                    },
                    formatter: _self.sheetEmployeeCodeFormatter
                }, {
                    field: 'lastname',
                    title: lname_globecode, 
                    width: 145, 
                    sortable: true,
                    styler: function (value, row) {
                        if (row && row.isgl === '1' && row.islock === '1') {
                            return "background-color: "+configRowGLColor+"; padding-left:6px;";                            
                        } else if (row && row.islock === '1') {
                            return "background-color: "+configRowLockColor+"; padding-left:6px;";
                        }            
                        return "background-color: #ffddd3;padding-left:6px;";
                    }                    
                }, {
                    field: 'firstname',
                    title: fname_globecode, 
                    width: 145, 
                    sortable: true,
                    styler: function (value, row) {
                        if (row && row.isgl === '1' && row.islock === '1') {
                            return "background-color: "+configRowGLColor+"; padding-left:6px;";                            
                        } else if (row && row.islock === '1') {
                            return "background-color: "+configRowLockColor+"; padding-left:6px;";
                        }              
                        return "background-color: #ffddd3;padding-left:6px;";
                    },                    
                    formatter: _self.sheetEmployeeInfoFormatter
                }];           
            
                _self.javaCacheId = data.javaCacheId;
                
                if (data.isduplicate) {
                    _self.checkDuplicateEmployee = true;

                    PNotify.removeAll();
                    new PNotify({
                        title: 'Анхааруулга',
                        text: plang.get('prlGlobeDuplicatioEmployeeMsg'),
                        type: 'info',
                        sticker: false
                    });               
                } else {
                    _self.checkDuplicateEmployee = false;
                }                
                
                $('input[name="fromCache"]', _self.calcInfoFormId).val('0');
                $('input[name="javaCacheId"]', _self.calcInfoFormId).val(_self.javaCacheId);
                
                if(data.salBookId !== '' || data.isSavedBook == '1') {
                    $('.existSalaryBook', _self.calcInfoFormId).show();
                    $('select[name="prlCalculateType"]', _self.calcInfoFormId).prop('readonly', true);
                    $('input[name="salaryBookId"]', _self.calcInfoFormId).val(data.salBookId);
                    _self.isEditMode = true;
                } else {
                    $('select[name="prlCalculateType"]', _self.calcInfoFormId).prop('readonly', false);
                    $('input[name="salaryBookId"]', _self.calcInfoFormId).val('');       
                    _self.isEditMode = false;
                }
                
                if(typeof data.recursiveDepartment === 'object') {
                    var dataDepHtml = '', dlen = data.recursiveDepartment.length, i = 0;
                    
                    if(dlen > 1) {
                        dataDepHtml += '<option value="">- Хэлтсээр хайх -</option>';
                        for(i; i < dlen; i++) {
                            _self.selectedDeepDeps.push({
                                depName: data.recursiveDepartment[i].DEPARTMENTNAME,
                                depId: data.recursiveDepartment[i].DEPARTMENTID
                            })
                            dataDepHtml += '<option value="' + data.recursiveDepartment[i].DEPARTMENTID + '">' + data.recursiveDepartment[i].DEPARTMENTNAME + '</option>';
                        }
                        $("#filterDepartment_" + _self.windowId).removeClass('hidden').html(dataDepHtml);
                        $("#filterDepartmentBtn_" + _self.windowId).removeClass('hidden');
                    }
                }                
                _self.tabNameLi = [];
            
                var colspanCounter = 0, 
                    checkMergeName = data.fields[0].MERGE_NAME, 
                    dataField, 
                    i = 0;   

                for(i; i < data.fields.length; i++) {
                    dataField = data.fields[i];
                                                                
                    var manageField = {};
                    manageField.field = dataField.META_DATA_CODE;
                    manageField.fieldid = dataField.META_DATA_ID;
                    manageField.sortable = true;
                    manageField.columndatatype = '';                                                

                    /**
                     * auto detect datagrid column width
                     */
                    var tmpMetaDataNameLength = dataField.META_DATA_NAME.length;
                    tmpMetaDataNameLength = tmpMetaDataNameLength * 5.8;       
                    if (tmpMetaDataNameLength > 60) {
                        manageField.width = tmpMetaDataNameLength;
                    } else {
                        manageField.width = 60;
                    }                    
                    if(dataField.COLUMN_SIZE != null && dataField.COLUMN_SIZE.trim() != '' && dataField.COLUMN_SIZE > 60)
                        manageField.width = dataField.COLUMN_SIZE;

                    manageField.disable = dataField.IS_DISABLE;
                    if(dataField.LABEL_NAME !== null && dataField.LABEL_NAME !== '')
                        manageField.title = dataField.LABEL_NAME;
                    else
                        manageField.title = dataField.META_DATA_NAME;

                    if (dataField.DATA_TYPE == 'boolean') {
                        manageField.align = 'center';
                        manageField.formatter = _self.sheetCheckFormatter;
                        manageField.width = tmpMetaDataNameLength / 2 * 5.6;

                    } else if(dataField.IS_DISABLE != '1' && (dataField.DATA_TYPE == 'bigdecimal' || dataField.DATA_TYPE == 'number' || dataField.DATA_TYPE == 'long')) {
                        if(dataField.LINK_META_DATA_ID != null) {
                            _self.linkMetaDataId = dataField.LINK_META_DATA_ID;

                            if(dataField.DATA_TYPE == 'bigdecimal' || dataField.DATA_TYPE == 'number' || dataField.DATA_TYPE == 'long') {
                                manageField.align = 'right';
                            }
                            manageField.formatter = _self.sheetDataviewFormatter;

                        } else
                            manageField.formatter = window['sheetNumberFormatter_' + _self.windowId];
                        manageField.columndatatype = 'number';

                    } else if(dataField.IS_DISABLE == '1' && (dataField.DATA_TYPE == 'bigdecimal' || dataField.DATA_TYPE == 'number' || dataField.DATA_TYPE == 'long')) {
                        manageField.formatter = _self.sheetDisableFormatter;
                        manageField.columndatatype = 'number';
                    } else if(dataField.IS_DISABLE != '1' && (dataField.DATA_TYPE == 'string')) {
                        manageField.formatter = _self.sheetStringFormatter;
                    } else if(dataField.IS_DISABLE != '1' && (dataField.DATA_TYPE == 'integer')) {
                        manageField.formatter = _self.sheetIntegerFormatter;
                    } else if(dataField.IS_DISABLE == '1' && (dataField.DATA_TYPE == 'string' || dataField.DATA_TYPE == 'integer')) {
                        manageField.formatter = _self.sheetStringDisableFormatter;
                    } else {
                        manageField.formatter = _self.sheetOtherFormatter;
                    }

                    if (dataField.USER_COLUMN_COLOR) {
                        _self.salaryGridStyle += '.datagrid-view .datagrid-view2 .datagrid-body td[field="'+dataField.META_DATA_CODE+'"] {background-color:'+dataField.USER_COLUMN_COLOR+'}';          
                        _self.salaryGridStyle += '.datagrid-view .datagrid-view2 .datagrid-body td[field="'+dataField.META_DATA_CODE+'"] input {background-color:'+dataField.USER_COLUMN_COLOR+' !important}';          
                    }
                    
                    if (dataField.MERGE_NAME != checkMergeName) {
                        if (checkMergeName != '$@$') {
                            _self.fieldsColspan.push({
                                title: checkMergeName,
                                colspan: colspanCounter
                            });                          
                        }
                        colspanCounter = 0; 
                    }                         

                    if(dataField.IS_HIDE !== '1' && dataField.IS_SIDEBAR !== '1' && dataField.IS_FREEZE !== '1' && !dataField.IS_HIDE_USER_COLUMN && dataField.IS_IGNORE == '1') {

                        _self.fields.push(manageField);
                        if (dataField.MERGE_NAME == '$@$') {
                            manageField.rowspan = 2;
                            _self.fieldsColspan.push(manageField);                          
                        } else {
                            _self.dataGridfields.push(manageField);
                        }
                        colspanCounter++;
                    }                            

                    if(dataField.IS_HIDE !== '1' && !dataField.IS_HIDE_USER_COLUMN && dataField.IS_IGNORE == '1') {
                        _self.allShowFields.push(dataField);
                    }

                    if(dataField.IS_FREEZE === '1') {
                        manageField.styler = function (value, row) {
                            return "background-color: #ffddd3;";
                        };
                        _self.frozenFields.push(manageField);
                    }

                    /**
                     * IS_CARD INIT
                     */
                    if (dataField.IS_CARD === '1') {
                        _self.tabNameLi.push({
                            fieldName: manageField.title,
                            fieldCode: manageField.field,
                            calcDtlId: dataField.ID
                        });
                    }
                                       
                    checkMergeName = dataField.MERGE_NAME;                    
                }
                
                if ($('#salary-datagrid-filter-style').length) {
                    $('#salary-datagrid-filter-style').remove();
                }
                $('head').append('<style id="salary-datagrid-filter-style">'+_self.salaryGridStyle+'</style>');
                
                _self.fieldsColspan.push({
                    field: 'delete', 
                    title: '', 
                    sortable: false, 
                    align: 'center',
                    width: 40,
                    rowspan: 2,
                    formatter: _self.sheetDeleteFormatter
                });
                
                _self.fields.push({
                    field: 'delete', 
                    title: '', 
                    sortable: false, 
                    align: 'center',
                    width: 40,
                    rowspan: 2,
                    formatter: _self.sheetDeleteFormatter
                });
                
                _self.callDataGridStructure(isTrigger);
                
                var $addonToolbarPart = $('.addon-toolbar-part', _self.calcInfoWindowId);
        
                if (data.hasOwnProperty('bookNumber') && data.bookNumber !== '' && data.bookNumber != null) {
                    $addonToolbarPart.show();
                    $addonToolbarPart.find('span[data-path="bookNumber"]').text(data.bookNumber);
                    $(_self.calcInfoFormId).find('input[name="bookNumber"]').val(data.bookNumber);
                } else {
                    $addonToolbarPart.hide();
                    $addonToolbarPart.find('span[data-path="bookNumber"]').text('');
                    $addonToolbarPart.find('div[data-section-path="bookTypeId"]').find('input').val('');
                }
        
            } else {
                Core.unblockUI();
                PNotify.removeAll();
                new PNotify({
                    title: 'Анхааруулга',
                    text: data.text,
                    type: 'warning',
                    sticker: false
                });           
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

SalaryV3.prototype.callDataGridStructure = function(isTrigger) {
    var _self = this;
    var colsdata = [_self.fieldsColspan, _self.dataGridfields];
    
    $(_self.dataGridId).datagrid({
        url: 'mdsalary/getSalaryListWebservice',
        queryParams: {
            params: $(_self.calcInfoFormId).serialize(),
            javaCacheId: _self.javaCacheId
        },
        fit: false,
        fitColumns: false,
        rownumbers: true,
        singleSelect: false,
        showFooter: true,
        ctrlSelect: true,
        pagination: true,
        remoteFilter: false,
        loadMsg: 'Ажилтны мэдээлэл ачааллаж байна, Түр хүлээнэ үү',
        pageSize: _self.selectedPage,
        pageList: [
            50, 100, 150, _self.unlimitPage, 400, 600, 800, 1000, 2000
        ],
        remoteSort: true,
        frozenColumns: [_self.frozenFields],
        columns: colsdata,
        sortName: 'firstname',
        sortOrder: 'asc',
        onRowContextMenu: function (e, index, row) {
            e.preventDefault();
            _self.selectInput(e.target);
            
            if (configCalculateTemplateCretria === '1') {
                var contextMenuObj = {
                    /*"sheetLock": {
                        name: 'Түгжих', 
                        icon: "lock",
                        callback: function(key, options) {
                            _self.lockRowSheet('1');
                            $.contextMenu('destroy');
                        }
                    },*/
                    "sheetUnLock": {
                        name: 'Түгжээг тайлах', 
                        icon: "unlock", 
                        callback: function(key, options) {
                            _self.lockRowSheet('0');
                            $.contextMenu('destroy');
                        }
                    },
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
                    "searchcolumn": {
                        name: 'Багана хайх', 
                        icon: "search", 
                        callback: function(key, options) {
                            _self.searchFieldColumnSheet();
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
                    },
                    "selectedDownloadValue": {
                        name: MET_999990992, 
                        icon: "download", 
                        callback: function(key, options) {
                            $.contextMenu('destroy');
                            $.ajax({
                                type: 'POST',
                                url: 'mdsalary/getProcessRunList',
                                data: {
                                    params: $(_self.calcInfoFormId).serialize()
                                },
                                dataType: "json",
                                beforeSend: function() {
                                    Core.blockUI( {
                                        message: 'Түр хүлээнэ үү...',
                                        boxed: true
                                    });
                                },
                                success: function(data) {                                
                                    Core.unblockUI();
                                    if(data.status === 'warning') {
                                        PNotify.removeAll();
                                        new PNotify({
                                            type: data.status,
                                            title: data.status,
                                            text: data.text,
                                            sticker: false
                                        });
                                        return;
                                    }
                                    _self.salaryGetProcessRun(data.getRows, 'selectedEmployee');
                                }
                            });                              
                        }
                    },
                    "calculate": {
                        name: 'Сагсанд нэмэх', 
                        icon: "shopping-cart", 
                        callback: function(key, options) {
                            _self.addtoBasket();
                            $.contextMenu('destroy');
                        }
                    },
                    "trash": {
                        name: 'Олноор устгах',
                        icon: "trash", 
                        callback: function(key, options) {
                            var selectedSheetRows = $(_self.dataGridId).datagrid('getSelections');        
                            var $dialogName = 'dialog-delete-confirm-employee_' + _self.windowId;                    
                            $.contextMenu('destroy');

                            if (selectedSheetRows.length) {
                                $("#" + $dialogName).empty().html('Устгахдаа итгэлтэй байна уу?');
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
                                            if (selectedSheetRows.length === 1) {
                                                if (selectedSheetRows[0].isgl == '1') {
                                                    PNotify.removeAll();
                                                    new PNotify({
                                                        title: 'Анхааруулга',
                                                        text: 'Гүйлгээнд холбогдсон байна.',
                                                        type: 'warning',
                                                        sticker: false
                                                    });  
                                                    return;
                                                }                                
                                            }
                                            
                                            $.ajax({
                                                url: "mdsalary/deleteEmployeesSheetWebservice",
                                                type: "POST",
                                                data: {
                                                    selectedSheetRows: selectedSheetRows,
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
                        }
                    }
                };
            } else {
            var contextMenuObj = {
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
                    "searchcolumn": {
                        name: 'Багана хайх', 
                        icon: "search", 
                        callback: function(key, options) {
                            _self.searchFieldColumnSheet();
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
                    },
                    "selectedDownloadValue": {
                        name: MET_999990992, 
                        icon: "download", 
                        callback: function(key, options) {
                            $.contextMenu('destroy');
                            $.ajax({
                                type: 'POST',
                                url: 'mdsalary/getProcessRunList',
                                data: {
                                    params: $(_self.calcInfoFormId).serialize()
                                },
                                dataType: "json",
                                beforeSend: function() {
                                    Core.blockUI( {
                                        message: 'Түр хүлээнэ үү...',
                                        boxed: true
                                    });
                                },
                                success: function(data) {                                
                                    Core.unblockUI();
                                    if(data.status === 'warning') {
                                        PNotify.removeAll();
                                        new PNotify({
                                            type: data.status,
                                            title: data.status,
                                            text: data.text,
                                            sticker: false
                                        });
                                        return;
                                    }
                                    _self.salaryGetProcessRun(data.getRows, 'selectedEmployee');
                                }
                            });                              
                        }
                    },
                    "calculate": {
                        name: 'Сагсанд нэмэх', 
                        icon: "shopping-cart", 
                        callback: function(key, options) {
                            _self.addtoBasket();
                            $.contextMenu('destroy');
                        }
                    },
                    "trash": {
                        name: 'Олноор устгах', 
                        icon: "trash", 
                        callback: function(key, options) {
                            var selectedSheetRows = $(_self.dataGridId).datagrid('getSelections');                            
                            var $dialogName = 'dialog-delete-confirm-employee_' + _self.windowId;
                            $.contextMenu('destroy');

                            if (selectedSheetRows.length) {
                                $("#" + $dialogName).empty().html('Устгахдаа итгэлтэй байна уу?');
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
                                            if (selectedSheetRows.length === 1) {
                                                if (selectedSheetRows[0].isgl == '1') {
                                                    PNotify.removeAll();
                                                    new PNotify({
                                                        title: 'Анхааруулга',
                                                        text: 'Гүйлгээнд холбогдсон байна.',
                                                        type: 'warning',
                                                        sticker: false
                                                    });  
                                                    return;
                                                }                                
                                            }
                                            
                                            $.ajax({
                                                url: "mdsalary/deleteEmployeesSheetWebservice",
                                                type: "POST",
                                                data: {
                                                    selectedSheetRows: selectedSheetRows,
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
                        }
                    }                    
                };                
            }
            
            $.contextMenu({
                selector: ".datagrid .datagrid-view .datagrid-view2 .datagrid-body .datagrid-row, .datagrid .datagrid-view .datagrid-view1 .datagrid-body .datagrid-row",
                items: contextMenuObj
            });            
        },
        onLoadSuccess: function(data) {
            _self.onLoadSuccessActions(data);
            if(isTrigger !== undefined) {
                //$(".tool-collapse", _self.calcInfoWindowId).trigger('click');
               isTrigger = undefined;
            }
        },   
        onCheckAll: function(){
            var $getCheckboxs = $(_self.dataGridId).datagrid('getPanel').children('div.datagrid-view').find('input[type="checkbox"]');
            //$.uniform.update($getCheckboxs);
            $('input[name="isAllEmployeeSelected"]', _self.calcInfoWindowId).val('1');
        },
        onUncheckAll: function(){
            var $getCheckboxs = $(_self.dataGridId).datagrid('getPanel').children('div.datagrid-view').find('input[type="checkbox"]');
            //$.uniform.update($getCheckboxs);
            $('input[name="isAllEmployeeSelected"]', _self.calcInfoWindowId).val('0');
        },        
        onClickRow: function(index, row) {
            var $getCheckboxs = $(_self.dataGridId).datagrid('getPanel').children('div.datagrid-view').find('input[type="checkbox"]');
            //$.uniform.update($getCheckboxs);
        },
        onDblClickRow: function(index, row) {
            var $getCheckboxs = $(_self.dataGridId).datagrid('getPanel').children('div.datagrid-view').find('input[type="checkbox"]');
            //$.uniform.update($getCheckboxs);
            var isAdded = false, rowId = row['employeekeyid']; 

            for (var key in window['_selectedRows' + _self.windowId]) {
                var basketRow = window['_selectedRows' + _self.windowId][key], 
                    childId = basketRow['employeekeyid'];

                if (rowId == childId) {
                    isAdded = true;
                    break;
                } 
            }            
            
            if (!isAdded) {
                window['_selectedRows' + _self.windowId].push(row);
                $('.save-database-'+_self.windowId).text(window['_selectedRows' + _self.windowId].length).pulsate({
                    color: '#F3565D', 
                    reach: 9,
                    speed: 500,
                    glow: false, 
                    repeat: 1
                });   
            } else {
                $('.save-database-'+_self.windowId).pulsate({
                    color: '#4caf50', 
                    reach: 9,
                    speed: 500,
                    glow: false, 
                    repeat: 1
                });   
            }                
        },
        /*onBeforeLoad:function(param){
            // Sort хийж байхад cache рүү хадгалах үйлдэл
            param.sheet = _self.globalEmpObj,
            param.dataIndex = _self.dataIndex
        },*/
        onResizeColumn:function(field, width){
            $("input[name='" + field + "']", _self.calcInfoWindowId).css('width', (width - 17) + 'px');
        },
        onLoadError: function () {
            alert('Ажилтны мэдээлэл ачааллахад алдаа гарлаа!');
        }
    });
    
    var $filterWidth1 = 0,
        $filterWidth2 = 0,
        $filterWidth3 = 0;
    $(_self.calcInfoWindowId).find('.datagrid').find('.datagrid-view1').find('.datagrid-htable tbody').find('tr').find('td').each(function () {
        var $td = $(this);
        if ($td.attr('field') === 'employeecode') {
            $filterWidth1 = $td.width() + 6;
        }
        if ($td.attr('field') === 'lastname') {
            $filterWidth2 = $td.width() + 6;
        }
        if ($td.attr('field') === 'firstname') {
            $filterWidth3 = $td.width() + 6;
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
            '<tr class="datagrid-header-row datagrid-filter-row"><td field="_"></td><td field="_"></td><td field="employeecode"><div class="datagrid-filter-c"><input type="text" class="datagrid-editable-input datagrid-filter" name="employeecode" style="width:' +
            $filterWidth1 +
            'px;"></div></td><td field="lastname"><div class="datagrid-filter-c"><input type="text" class="datagrid-editable-input datagrid-filter" name="lastname" style="width:' +
            $filterWidth2 +
            'px;"></div></td><td field="firstname"><div class="datagrid-filter-c"><input type="text" class="datagrid-editable-input datagrid-filter" name="firstname" style="width:' +
            $filterWidth3 +
            'px;"></div></td>';
    
    $.each(_self.frozenFields, function (k, v) {        
        if (v['field'] != "employeecode" && v['field'] != "lastname" && v['field'] != "firstname" && v['field'] != "ck") {
            filterHtml += '<td field="' + v['field'] + '" fieldid="' + v['fieldid'] + '" style="' + (v['hidden'] === "true" ? "display:none;" : "") + '"><div class="datagrid-filter-c"><input type="text" class="datagrid-editable-input datagrid-filter" name="' + v['field'] + '" style="width: ' + v['width'] + 'px"></div></td>';
        }
    });    
    filterHtml += '</tr>'

    $(_self.calcInfoWindowId).find('.datagrid').find('.datagrid-view1').find('.datagrid-htable').find('tbody').append(filterHtml);
    $(_self.calcInfoWindowId).find('.datagrid').find('.datagrid-view2').find('.datagrid-htable').find('tbody').append(filters);
};

SalaryV3.prototype.sheetDataviewFormatter = function(val, row, index) {
    if(typeof val === 'undefined')
        return;
    
    var value = 0;
    if (val !== null && val !== '') {
        value = val;
    }
    value = value.toString();
    var html = '<a href="javascript:void(0)" style="padding-right: 6px;" onClick="window[\'salaryObj' + varWindowId + '\'].sheetDataviewCall('+row.employeeid+')">'+pureNumberFormat(value)+'</a>';
    
    if(typeof row.loggedvalues !== 'undefined' && row.loggedvalues) {
        if (row.loggedvalues.search(new RegExp(this.field+'\]', 'g')) !== -1) {
            html = '<a href="javascript:void(0)" style="padding-right: 6px;" onClick="window[\'salaryObj' + varWindowId + '\'].sheetDataviewCall('+row.employeeid+')">'+pureNumberFormat(value)+'</a>'+
                    '<a class="btn btn-xs btn-secondary" title="Өөрчлөлтийн түүх харах" href="javascript:;" onclick="window[\'salaryObj' + varWindowId + '\'].getLogData(this)"><i style="color:#ff2929;" class="fa fa-history"></i></a>';
        }
    }
    
    return html;
};

SalaryV3.prototype.sheetDataviewCall = function(employeeid) {
    var _self = this;

    $.ajax({
        type: 'post',
        url: 'mdsalary/getMetaData/' + _self.linkMetaDataId,
        dataType: "json",
        success: function (data) {
            dataViewCustomSelectableGrid(data.META_DATA_CODE, 'single', '', 'param[employeeid]='+employeeid, this);
        }
    });
};

SalaryV3.prototype.sheetStringFormatter = function(val, row, index) {
    if(typeof val === 'undefined' || val == null)
        return '';
    
    var cellStyle = '';
    if (row.isgl === '1' && row.islock === '1') {
        cellStyle = ' readonly style="background-color: '+configRowGLColor+'"';
    } else if (row.islock === '1') {
        cellStyle = ' readonly style="background-color: '+configRowLockColor+'"';
    }      
    
    var html = '<input type="text"'+cellStyle+' class="form-control text-left form-control-inline m-wrap form-control-sm" onChange="window[\'salaryObj' + varWindowId + '\'].setSheetStrValue(this)" onClick="window[\'salaryObj' + varWindowId + '\'].selectInput(this)" data-oldValue="' + val + '" value="' + val + '" />';
    
    return html;
};

SalaryV3.prototype.sheetIntegerFormatter = function(val, row, index) {
    if(typeof val === 'undefined' || val == null)
        return '';
    
    var cellStyle = '';
    if (row.isgl === '1' && row.islock === '1') {
        cellStyle = ' readonly style="background-color: '+configRowGLColor+'"';
    } else if (row.islock === '1') {
        cellStyle = ' readonly style="background-color: '+configRowLockColor+'"';
    }          
    
    var html = '<input type="text"'+cellStyle+' class="form-control text-right form-control-inline m-wrap form-control-sm" onChange="window[\'salaryObj' + varWindowId + '\'].setSheetStrValue(this)" onClick="window[\'salaryObj' + varWindowId + '\'].selectInput(this)" data-oldValue="' + val + '" value="' + val + '" />';
    
    return html;
};

SalaryV3.prototype.sheetStringDisableFormatter = function(val, row, index) {
    if(typeof val === 'undefined' || val == null)
        return '';
    
    var cellStyle = '';
    if (row.isgl === '1' && row.islock === '1') {
        cellStyle = ' style="background-color: '+configRowGLColor+'"';
    } else if (row.islock === '1') {
        cellStyle = ' style="background-color: '+configRowLockColor+'"';
    }          
    
    var html = '<input type="text"'+cellStyle+' readonly class="form-control text-left form-control-inline m-wrap form-control-sm" value="' + val + '" />';
    
    return html;
};

SalaryV3.prototype.sheetCheckFormatter = function(val, row) {
    var value = 0, checkDisable = '';
    var cellStyle = ' style="width: 16px; height: 16px;"';
    var cellDivStyle = '';
    if(typeof val !== 'undefined' && val != null && val != 0)
        value = val;
    
    if (row.IS_DISABLE === '1') {
        checkDisable = ' disabled';
    }
    if (row.islock === '1') {
        cellStyle = ' readonly style="width: 16px; height: 16px;background-color: '+configRowLockColor+'"';
        cellDivStyle = ' style="width:100%;background-color: '+configRowLockColor+'"';
    }              
    
    var html = '<div'+cellDivStyle+'><input'+cellStyle+' type="checkbox"'+checkDisable+' '+(value == 1 ? 'checked' : '')+' onChange="window[\'salaryObj' + varWindowId + '\'].setCheckboxValue(this)" class="form-control form-control-inline m-wrap form-control-sm ml-1" value="' + value + '" /></div>';
    
    return html;
};

SalaryV3.prototype.sheetDisableFormatter = function(val, row) {
    if(typeof val === 'undefined')
        return;
    
    var cellStyle = '';
    if (row.isgl === '1' && row.islock === '1') {
        cellStyle = ' style="background-color: '+configRowGLColor+'"';
    } else if (row.islock === '1') {
        cellStyle = ' style="background-color: '+configRowLockColor+'"';
    }          
    
    var value = 0;
    if (val !== null && val !== '') {
        value = val;
    }
    value = value.toString();
    var html = '<input type="text"'+cellStyle+' readonly class="form-control text-right form-control-inline m-wrap form-control-sm salaryNumberFormat w-100" onChange="window[\'salaryObj' + varWindowId + '\'].setSheetValue(this)" onClick="window[\'salaryObj' + varWindowId + '\'].selectInput(this)" data-oldValue="' + value + '" value="' + value + '" title="' + pureNumberFormat(parseFloat(value).toFixed(2)) + '" />';
    
    if(typeof row.loggedvalues !== 'undefined' && row.loggedvalues) {
        if (row.loggedvalues.search(new RegExp(this.field+'\]', 'g')) !== -1) {
            html = '<input type="text"'+cellStyle+' readonly class="saved-log-data-cell form-control text-right form-control-inline m-wrap form-control-sm salaryNumberFormat w-100" onChange="window[\'salaryObj' + varWindowId + '\'].setSheetValue(this)" onClick="window[\'salaryObj' + varWindowId + '\'].selectInput(this)" data-oldValue="' + value + '" value="' + value + '" title="' + pureNumberFormat(parseFloat(value).toFixed(2)) + '" />'+
                    '<a class="btn btn-xs btn-secondary" title="Өөрчлөлтийн түүх харах" href="javascript:;" onclick="window[\'salaryObj' + varWindowId + '\'].getLogData(this)"><i style="color:#ff2929;" class="fa fa-history"></i></a>';
        }    
    }    
    
    return html;
};

SalaryV3.prototype.sheetOtherFormatter = function(val, row) {    
    if(typeof val === 'undefined')
        return;
    
    var cellStyle = '';
    if (row.isgl === '1' && row.islock === '1') {
        cellStyle = ' style="background-color: '+configRowGLColor+'"';
    } else if (row.islock === '1') {
        cellStyle = ' style="background-color: '+configRowLockColor+'"';
    }             
    
    var html = '<span'+cellStyle+'>' + val + '</span>';
    
    return html;
};

SalaryV3.prototype.sheetDeleteFormatter = function(val, row) {
    var html = '<a class="btn btn-xs red" href="javascript:;" title="Ажилтан устгах" onClick="window[\'salaryObj' + varWindowId + '\'].deleteEmployeeSheet(this, \''+ encodeURIComponent(JSON.stringify(row)) + '\')"><i class="fa fa-trash"></i></a>';
    return html;
};

SalaryV3.prototype.sheetEmployeeInfoFormatter = function(val, row) {
    var html = '<a href="javascript:void(0)" title="Ажилтны мэдээлэл харах" onClick="window[\'salaryObj' + varWindowId + '\'].employeeInformation(this)">' + row.firstname + '</a>';
    return html;
};

SalaryV3.prototype.sheetEmployeeCodeFormatter = function(val, row) {
    var html = '<a href="javascript:void(0)" title="" onClick="window[\'salaryObj' + varWindowId + '\'].employeeCalculateBp(this)">' + row.employeecode + '</a>';
    return html;
};

SalaryV3.prototype.setSheetValue = function(elem) {
    var _self = this;
    var changedValue = $(elem).val().replace(/\,/g, ""),
        index = _self.getRowIndex(elem), field = _self.getField(elem);
    
    $(elem).val(pureNumberFormat(changedValue));
    _self.globalEmpObj[index][field] = changedValue;
    _self.changedGlobalEmpObj[index] = _self.globalEmpObj[index];
    
    if (!(index in _self.createSheetLogDatas)) {
        _self.createSheetLogDatas[index] = {};
    }
    _self.createSheetLogDatas[index][field] = {};
    
    _self.createSheetLogDatas[index][field]['metadatacode'] = _self.activeField;    
    if(typeof $(elem).attr('data-oldValue') !== 'undefined') {
        _self.createSheetLogDatas[index][field]['oldValue'] = $(elem).attr('data-oldValue');    
    }
    _self.createSheetLogDatas[index][field]['value'] = changedValue;    
    _self.createSheetLogDatas[index][field]['employeekeyid'] = _self.globalEmpObj[index]['employeekeyid'];    
};

SalaryV3.prototype.setSheetStrValue = function(elem) {
    var _self = this;
    var changedValue = $(elem).val(),
        index = _self.getRowIndex(elem), field = _self.getField(elem);
    
    _self.globalEmpObj[index][field] = changedValue;
    _self.changedGlobalEmpObj[index] = _self.globalEmpObj[index];
    
    if (!(index in _self.createSheetLogDatas)) {
        _self.createSheetLogDatas[index] = {};
    }
    _self.createSheetLogDatas[index][field] = {};
    
    _self.createSheetLogDatas[index][field]['metadatacode'] = _self.activeField;    
    if(typeof $(elem).attr('data-oldValue') !== 'undefined') {
        _self.createSheetLogDatas[index][field]['oldValue'] = $(elem).attr('data-oldValue');    
    }
    _self.createSheetLogDatas[index][field]['value'] = changedValue;    
    _self.createSheetLogDatas[index][field]['employeekeyid'] = _self.globalEmpObj[index]['employeekeyid'];    
};

SalaryV3.prototype.setCheckboxValue = function(elem) {
    var _self = this;
    var changedValue = $(elem).is(':checked') ? 1 : 0,
        index = _self.getRowIndex(elem), field = _self.getField(elem);
    
    _self.globalEmpObj[index][field] = changedValue; 
    _self.changedGlobalEmpObj[index] = _self.globalEmpObj[index];
    
    if (!(index in _self.createSheetLogDatas)) {
        _self.createSheetLogDatas[index] = {};
    }
    _self.createSheetLogDatas[index][field] = {};
    
    _self.createSheetLogDatas[index][field]['metadatacode'] = _self.activeField;    
    if(typeof $(elem).attr('data-oldValue') !== 'undefined') {
        _self.createSheetLogDatas[index][field]['oldValue'] = $(elem).attr('data-oldValue');    
    }
    _self.createSheetLogDatas[index][field]['value'] = changedValue;    
    _self.createSheetLogDatas[index][field]['employeekeyid'] = _self.globalEmpObj[index]['employeekeyid'];    
};

SalaryV3.prototype.onLoadSuccessActions = function(data) {
    var _self = this, execFunc = false;
    
    if (Object.keys(data.rows).length) {
        _self.globalEmpObj = data.rows;
        _self.dataIndex = data.dataIndex;
        
        var pager = $(_self.dataGridId).datagrid('getPager');
        var popts = pager.pagination('options');
        var onSelectPage = popts.onSelectPage;
        
        popts.onSelectPage = function(pageNumber, pageSize){
            if (!execFunc) {
                execFunc = true;
                _self.saveSalarySheetByPager();
            }
            onSelectPage.call(this, pageNumber, pageSize);
        };                        
        
        if (_self.tabNameLi.length) {
            if ($('#prl_salary_tab_cart_' + _self.windowId).length) {
                $('#prl_salary_tname_' + _self.windowId).find('li:last').remove();
                $('#prl_salary_tab_cart_' + _self.windowId).remove();
            }
            
            $('#prl_salary_tname_' + _self.windowId).append(
            '<li>'+
                '<a href="#prl_salary_tab_cart_' + _self.windowId + '" data-toggle="tab">Нэмэлт мэдээлэл</a>'+
            '</li>');
            $('#prl_salary_tcontent_' + _self.windowId).append('<div class="tab-pane" id="prl_salary_tab_cart_' + _self.windowId + '"><div class="col-md-12"><img src="' + URL_APP + 'assets/core/global/img/input-spinner.gif' + '"> Loading</div></div>');                
        }    
        
    } else {

        if (data.status == 'error') {
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

    $('.datagrid-footer table tbody tr', _self.calcInfoWindowId).find('td:last-child').find('a').remove();
    $('.datagrid-view1 .datagrid-footer table tbody tr', _self.calcInfoWindowId).find('td:eq(2)').find('a').remove();
    $('.datagrid-footer .datagrid-ftable tbody tr', _self.calcInfoWindowId).find('td').css('background-color', '#ffffff');
    $('.salaryNumberFormat', _self.calcInfoWindowId).autoNumeric('init', {aPad: true, mDec: configNumDec, vMin: '-999999999999999999999999999999.999999999999999999999999999999', vMax: '999999999999999999999999999999.999999999999999999999999999999'});
    //Core.initUniform(_self.calcInfoWindowId);
    $(_self.calcInfoWindowId + ' .salary-datarid-collapsed-btn').trigger("click");    
    
    var searchFooterCheckbox = $('.datagrid-footer table tbody tr', _self.calcInfoWindowId).find('input[type="checkbox"]');
    if (searchFooterCheckbox.length) {
        searchFooterCheckbox.remove();
    }    
    
    Core.unblockUI();
};

SalaryV3.prototype.filterSalary = function(elem) {
    var _self = this, $input = $(elem);
    var filterVal = {};
    var filValue = $("input.datagrid-filter", _self.calcInfoWindowId);
                
    $.each(filValue, function(){
        var $this = $(this);
        
        if($this.val() != '') {
            filterVal[$this.attr('name')] = $this.val();
        }
    });    
    
    var filterParams = {
        salaryFilter: filterVal,
        javaCacheId: _self.javaCacheId,
        sheet: _self.changedGlobalEmpObj,
        dataIndex: _self.dataIndex
    };
    
    if(_self.createSheetLogDatas.length > 0) {
        _self.saveSalarySheetByPager();
    }
    
    $(_self.dataGridId).datagrid('load', filterParams);
};

SalaryV3.prototype.saveSalarySheet = function(elem) {
    var _self = this,
        logFrozenFields = _self.frozenFields,
        logFields = _self.fields;
    
    for(var i = 0; i < logFrozenFields.length; i++) {
        if (logFrozenFields[i].hasOwnProperty('formatter')) {
            delete logFrozenFields[i]['formatter'];
        }
    }
    for(var i = 0; i < logFields.length; i++) {
        if (logFields[i].hasOwnProperty('formatter')) {
            delete logFields[i]['formatter'];
        }
    }
    
    var getFooter = $(_self.dataGridId).datagrid('getFooterRows');
        
    $.ajax({
        type: 'post',
        url: 'mdsalary/saveSalarySheetWebservice',
        data: {
            sheet: _self.changedGlobalEmpObj,
            sheetLog: _self.createSheetLogDatas,
            javaCacheId: _self.javaCacheId,
            dataIndex: _self.dataIndex, 
            bookTypeId: $('.addon-toolbar-part', _self.calcInfoWindowId).find('input[data-path="bookTypeId"]').val(),
            params: $(_self.calcInfoFormId).serialize(),
            datagridFrozenColumns: logFrozenFields,
            datagridColumns: logFields,
            datagridFooters: getFooter
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
                _self.changedGlobalEmpObj = [];
                _self.isEditMode = true;
                
                var filterParams = {
                    refreshFromSave: '-'
                };
                                
                //$(_self.calcInfoWindowId).find('input[name="batchNumber"]').val(resp.batchNumber);
                
                if (_self.checkDuplicateEmployee) {
                    setTimeout(function() {
                        var blockMsg = 'Цалин бодолтод бэлдэж байна, Түр хүлээнэ үү...';

                        Core.blockUI({
                            message: blockMsg,
                            boxed: true
                        });                    
                    }, 10);
                    $(_self.calcInfoWindowId).find('.searchCalcInfo').trigger('click', [true]);
                } else {
                    $(_self.dataGridId).datagrid('reload');
                }
                
                PNotify.removeAll();
                new PNotify({
                    title: 'Success',
                    text: 'Амжилттай хадгалагдлаа.',
                    type: 'success',
                    sticker: false
                }); 
                
                Core.blockUI({
                    message: 'Loading...',
                    boxed: true
                });                
                
            } else if(resp.status === 'error') {
                PNotify.removeAll();
                new PNotify({
                    title: 'Анхааруулга',
                    text: resp.text,
                    type: 'warning',
                    sticker: false
                });           
                
            } else {
                var dialogname = $('#dialog-expression-error_'+_self.windowId);
                var $dialogname = 'dialog-expression-error_'+_self.windowId;
                var data = '', ee = 0;

                data += '<ul class="list-group">';
                $.each(resp.result, function(k, v){
                    data += '<li class="list-group-item-action list-group-item-danger" style="border: 1px solid #d29797;">'+capitalizeFirstLetter(k);
                    data += '<div class="hidden" style="max-height: 212px; overflow-y: auto; color: #333;">';
                        data += '<table class="table table-sm table-bordered table-hover bprocess-table-dtl mb5 mt5"><thead><tr><th class="rowNumber">№</th><th style="text-align: left;">Овог</th><th style="text-align: left;">Нэр</th></tr></thead><tbody>';
                        for(var e = 0; e < v.length; e++) {
                            ee = e;
                            data += '<tr>';
                            data += '<td>'+(++ee)+'</td>';
                            data += '<td>'+v[e].lastname+'</td>';
                            data += '<td>'+v[e].firstname+'</td>';
                            data += '</tr>';
                        }
                        data += '</tbody></table>';
                    data += '</div>';
                    data += '<button type="button" class="btn btn-warning btn-xs float-right" title="Ажилтны мэдээлэл харах" onclick="window[\'salaryObj' + varWindowId + '\'].expressionErrorEmployee(this)" style="top: 6px;right: 14px;position: absolute;"><i class="fa fa-eye"></i></button></li>';
                });
                data += '</ul>';

                dialogname.empty().html(data);
                dialogname.dialog({
                    cache: false,
                    resizable: true,
                    bgiframe: true,
                    autoOpen: false,
                    title: 'Шалгуур',
                    width: 450,
                    height: 'auto',
                    modal: true,
                    open: function () {
                        $('div[aria-describedby=' + $dialogname + '] .ui-dialog-buttonset').addClass("btn-group float-right");
                        $('div[aria-describedby=' + $dialogname + '] .ui-dialog-buttonset').find('button:eq(0)').addClass('btn blue-hoki btn-sm ml5');
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
            Core.unblockUI();
        }
    });
};

SalaryV3.prototype.saveChangeSalarySheet = function(elem) {
    var _self = this;
        
    $.ajax({
        type: 'post',
        url: 'mdsalary/saveChangeSalarySheetWebservice',
        data: {
            sheetLog: _self.createSheetLogDatas,
            javaCacheId: _self.javaCacheId
        },        
        dataType: "json",
        beforeSend: function () {
            Core.blockUI({
                message: 'Өөрчлөлт хадгалж байна, Түр хүлээнэ үү...',
                boxed: true
            });
        },
        success: function (resp) {
            if(resp.status === 'success') {
                PNotify.removeAll();
                new PNotify({
                    title: 'Success',
                    text: 'Өөрчлөлтийг амжилттай хадгаллаа.',
                    type: 'success',
                    sticker: false
                }); 
                $(_self.calcInfoWindowId).find('.searchCalcInfo').trigger('click', [true]);
                
            } else if(resp.status === 'error') {
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

SalaryV3.prototype.copyFieldRowSheet = function() {
    var _self = this;
    var filterVal = {};
    var filValue = $("input.datagrid-filter", _self.calcInfoWindowId);
                
    $.each(filValue, function(){
        var $this = $(this);
        
        if($this.val() != '') {
            filterVal[$this.attr('name')] = $this.val();
        }
    });    
    
    $.ajax({
        type: 'post',
        url: 'Mdsalary/copyFieldRowSheetWebservice',
        data: {
            metaDataCode: _self.activeField,
            value: _self.globalEmpObj[_self.activeIndex][_self.activeField],
            sheet: _self.changedGlobalEmpObj,
            javaCacheId: _self.javaCacheId,
            dataIndex: _self.dataIndex,
            params: $(_self.calcInfoFormId).serialize(),
            salaryFilter: filterVal
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
                _self.changedGlobalEmpObj = [];
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

SalaryV3.prototype.lockRowSheet = function(val) {
    var _self = this;
    var filterVal = {};
    var filValue = $("input.datagrid-filter", _self.calcInfoWindowId);
                
    $.each(filValue, function(){
        var $this = $(this);
        
        if($this.val() != '') {
            filterVal[$this.attr('name')] = $this.val();
        }
    });    
    
    var selectedEmployees = $(_self.dataGridId).datagrid('getSelections');
    
    if (!selectedEmployees.length) {
        PNotify.removeAll();
        new PNotify({
            title: 'Warning',
            text: 'Ажилтан сонгоно уу.',
            type: 'warning',
            sticker: false
        });        
        return;
    }
    
    $.ajax({
        type: 'post',
        url: 'Mdsalary/lockFieldRowSheetWebservice',
        data: {
            sheet: selectedEmployees,
            javaCacheId: _self.javaCacheId,
            isAllEmployee: $('input[name="isAllEmployeeSelected"]', _self.calcInfoWindowId).val(),
            params: $(_self.calcInfoFormId).serialize(),
            isLock: val,
            filterParams: filterVal
        },
        dataType: "json",
        beforeSend: function () {
            Core.blockUI({
                message: 'Түр хүлээнэ үү...',
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

SalaryV3.prototype.calculateSalary = function() {
    var _self = this;
    
    $.ajax({
        type: 'post',
        url: 'Mdsalary/calculateSalaryListWebservice',
        data: {
            sheet: _self.changedGlobalEmpObj,
            javaCacheId: _self.javaCacheId,
            dataIndex: _self.dataIndex,
            params: $(_self.calcInfoFormId).serialize()
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
                
                _self.changedGlobalEmpObj = [];
                var filValue = $("input.datagrid-filter", _self.calcInfoWindowId), isLoad = false;
                
                $.each(filValue, function(){
                    var $this = $(this);
                    
                    if($this.val() != '' && !isLoad) {
                        var filterVal = {};
                        filterVal[$this.attr('name')] = $this.val();

                        var filterParams = {
                            salaryFilter: filterVal,
                            javaCacheId: _self.javaCacheId
                        };
                        
                        isLoad = true;
                        $(_self.dataGridId).datagrid('load', filterParams);
                    }
                });
                if(!isLoad) {
                    var filterParams = {
                        javaCacheId: _self.javaCacheId
                    };                    
                    $(_self.dataGridId).datagrid('load', filterParams);
                }
                
                if ($().pulse) {
                    var properties = {
                        backgroundColor: '#DD0B0B'
                    };

                    $(".saveSalarySheet", _self.calcInfoWindowId).pulse(properties, {
                        duration : 800,
                        pulses   : 20000,
                        interval : 1
                    });
                }                
                
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

SalaryV3.prototype.addtoBasket = function() {
    var _self = this;
    var selectedSheetRows = $(_self.dataGridId).datagrid('getSelections');
    
    for (var i = 0; i < selectedSheetRows.length; i++) {
        var isAdded = false, rowId = selectedSheetRows[i]['employeekeyid']; 

        for (var key in window['_selectedRows' + _self.windowId]) {
            var basketRow = window['_selectedRows' + _self.windowId][key], 
                childId = basketRow['employeekeyid'];

            if (rowId == childId) {
                isAdded = true;
                break;
            } 
        }            

        if (!isAdded) {
            window['_selectedRows' + _self.windowId].push(selectedSheetRows[i]);
            $('.save-database-'+_self.windowId).text(window['_selectedRows' + _self.windowId].length).pulsate({
                color: '#F3565D', 
                reach: 9,
                speed: 500,
                glow: false, 
                repeat: 1
            });   
        } else {
            $('.save-database-'+_self.windowId).pulsate({
                color: '#4caf50', 
                reach: 9,
                speed: 500,
                glow: false, 
                repeat: 1
            });   
        }         
    }
};

SalaryV3.prototype.selectedEmployeeSalary = function(rows) {
    var _self = this;
    
    if(_self.selectedDeps.length === 1 && !_self.selectedDeepDeps.length) {
        _self.appendConfirmEmployeeSalarySheet(_self.selectedDeps[0], rows);

    } else if(_self.selectedDeps.length > 1 || _self.selectedDeepDeps.length) {
        
        var dialogname = $('#dialog-append-employee_'+_self.windowId);
        var $dialogname = 'dialog-append-employee_'+_self.windowId;
        var data = '', depNames = $("#departmentIdName", _self.calcInfoWindowId).val().split('__');

        data += '<div><span>Ажилчидын мэдээллийг аль хэлтэсийн цалин бодолтод оноохыг зааж өгнө үү?</span></div><br>';
        data += '<select id="chooseDepartment_'+_self.windowId+'" name="chooseDepartment" class="form-control select2 form-control-sm input-xxlarge mt5" data-placeholder="- Сонгох -">';
            data += '<option value="">- Сонгох -</option>';
            if (_self.selectedDeepDeps.length) {
                $.each(_self.selectedDeepDeps, function (key, value) {
                    data += '<option value="' + _self.selectedDeepDeps[key]['depId'] + '">' + _self.selectedDeepDeps[key]['depName'] + '</option>';
                });                                
            } else {
                $.each(_self.selectedDeps, function (key, value) {
                    data += '<option value="' + value + '">' + depNames[key] + '</option>';
                });
            }
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

SalaryV3.prototype.appendConfirmEmployeeSalarySheet = function(department, employees) {
    var _self = this, $dialogname = 'dialog-salary-append-employee';

    if (configAddEmployee == '0') {
        $.ajax({
            url: "mdsalary/saveCacheSalarySheetWebservice",
            type: "POST",
            data: {
                sheetData: _self.changedGlobalEmpObj,
                javaCacheId: _self.javaCacheId,
                dataIndex: _self.dataIndex
            },
            dataType: "json",
            async: false,
            success: function (resp) {                    
                if(resp.status !== 'error') {    
                    _self.changedGlobalEmpObj = [];
                    
                    $.ajax({
                        url: "mdsalary/appendEmployeeSheetWebservice",
                        type: "POST",
                        data: {
                            department: department, 
                            employees: employees,
                            javaCacheId: _self.javaCacheId,
                            params: $(_self.calcInfoFormId).serialize(),
                            allEmployee: '1'
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
        
    } else {
    
        if (!$('#'+$dialogname).length) {
            $('<div id="' + $dialogname + '"></div>').appendTo(_self.calcInfoWindowId);
        }
        var dialogname = $('#dialog-salary-append-employee', _self.calcInfoWindowId);

        dialogname.empty().html('<p>ЗӨВХӨН НЭМСЭН АЖИЛТНААР УТГА ТАТУУЛЪЯ?</p>');            
        dialogname.dialog({
            appendTo: _self.calcInfoWindowId,
            cache: false,
            resizable: true,
            bgiframe: true,
            autoOpen: false,
            title: 'Утга татах',
            width: 400,
            height: 'auto',
            modal: true,
            open: function () {          
                $('div[aria-describedby=' + $dialogname + '] .ui-dialog-buttonset').find('button:eq(0)').addClass('btn btn-sm blue mr0');
                $('div[aria-describedby=' + $dialogname + '] .ui-dialog-buttonset').find('button:eq(1)').addClass('btn blue-hoki btn-sm ml5');            
            },
            close: function (elem) {
                dialogname.dialog('close');
            },
            buttons: [
                {text: plang.get('yes_btn'), click: function (elem) {
                    dialogname.empty().dialog('close');
                    $.ajax({
                        url: "mdsalary/saveCacheSalarySheetWebservice",
                        type: "POST",
                        data: {
                            sheetData: _self.changedGlobalEmpObj,
                            javaCacheId: _self.javaCacheId,
                            dataIndex: _self.dataIndex
                        },
                        dataType: "json",
                        async: false,
                        success: function (resp) {                    
                            if(resp.status !== 'error') {    
                                _self.changedGlobalEmpObj = [];
                                $.ajax({
                                    url: "mdsalary/appendEmployeeSheetWebservice",
                                    type: "POST",
                                    data: {
                                        department: department, 
                                        employees: employees,
                                        javaCacheId: _self.javaCacheId,
                                        params: $(_self.calcInfoFormId).serialize(),
                                        allEmployee: '0'
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
                }},
                {text: plang.get('no_btn'), click: function (elem) {
                    dialogname.empty().dialog('close');
                    $.ajax({
                        url: "mdsalary/saveCacheSalarySheetWebservice",
                        type: "POST",
                        data: {
                            sheetData: _self.changedGlobalEmpObj,
                            javaCacheId: _self.javaCacheId,
                            dataIndex: _self.dataIndex
                        },
                        dataType: "json",
                        async: false,
                        success: function (resp) {                    
                            if(resp.status !== 'error') {    
                                _self.changedGlobalEmpObj = [];
                                $.ajax({
                                    url: "mdsalary/appendEmployeeSheetWebservice",
                                    type: "POST",
                                    data: {
                                        department: department, 
                                        employees: employees,
                                        javaCacheId: _self.javaCacheId,
                                        params: $(_self.calcInfoFormId).serialize(),
                                        allEmployee: '1'
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
                }}
            ]
        });
        dialogname.dialog('open');                  
    }         
}

SalaryV3.prototype.copyFieldColumnSheet = function() {
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
        $.each(_self.frozenFields, function (key, value) {
            if(typeof value['disable'] !== 'undefined' && value['title'] != '' && value['disable'] != '1')
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
        $.each(_self.frozenFields, function (key, value) {
            if(typeof value['disable'] !== 'undefined' && value['field'] != _thisField && value['title'] != '' && value['disable'] != '1')
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
                        sheet: _self.changedGlobalEmpObj,
                        javaCacheId: _self.javaCacheId,
                        dataIndex: _self.dataIndex,
                        params: $(_self.calcInfoFormId).serialize()
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
                            _self.changedGlobalEmpObj = [];
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

SalaryV3.prototype.searchFieldColumnSheet = function() {
    var _self = this;
    
    var dialogname = $('#dialog-copy-field_'+_self.windowId);
    var $dialogname = 'dialog-copy-field_'+_self.windowId;
    var data = '';
    
    data += '<div><span>Багана сонгох:</span></div>';
    data += '<select id="searchFromField_'+_self.windowId+'" name="" class="form-control select2 form-control-sm input-xxlarge mt5" required="required" data-placeholder="- Сонгох -">';
        data += '<option value="">- Сонгох -</option>';
        $.each(_self.fields, function (key, value) {
            if(value['title'] != '' && value['disable'] != '1')
                data += '<option value="' + value['field'] + '">' + value['title'] + '</option>';
        });
        data += '</select>';   
    
    dialogname.empty().html(data);
    dialogname.dialog({
        cache: false,
        resizable: true,
        bgiframe: true,
        autoOpen: false,
        title: 'Багана хайх',
        width: 320,
        height: "auto",
        modal: true,
        open: function () {
            $('div[aria-describedby=' + $dialogname + '] .ui-dialog-buttonset').addClass("btn-group float-right");
            $('div[aria-describedby=' + $dialogname + '] .ui-dialog-buttonset').find('button:eq(0)').addClass('btn blue-hoki btn-sm ml5');
            $('#searchFromField_'+_self.windowId).select2();
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
    
    $(document).on('change', '#searchFromField_'+_self.windowId, function() {
        var _thisField = $(this).val();
        $('.datagrid-view .datagrid-view2 .datagrid-body td[field="'+_thisField+'"] input:first').focus();
        $('.datagrid-view .datagrid-view2 .datagrid-header td[field="'+_thisField+'"] span:first').css({"background-color":"yellow","color":"#000"});
        setTimeout(function () {
            $('.datagrid-view .datagrid-view2 .datagrid-header td[field="'+_thisField+'"] span:first').removeAttr("style");
        }, 2000);
        dialogname.empty().dialog('close');
    });     
};

SalaryV3.prototype.multipleFilter = function(elem) {
    var $fieldName = $(elem).closest('td').attr('field'), _self = this;
    var dialogname = $('#dialog-multiple-filter_' + _self.windowId + '_' + $fieldName);
    var $dialogname = 'dialog-multiple-filter_' + _self.windowId + '_' + $fieldName;
    var data = '';
    data = '<span><div>'+
        '<input type="text" name="multipleFilterData" style="width: 180px;" class="float-left ml20 form-control form-control-sm" placeholder="Утгаа оруулна уу">'+
        '<select class="form-control form-control-sm" name="multipleFilterCondition" style="width: 70px">\n\
            <option value="=">Тэнцүү</option>\n\
            <option value="!=">Ялгаатай</option>\n\
            <option value=">">Их</option>\n\
            <option value="<">Бага</option>\n\
            <option value="like">Like</option>\n\
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
                         
                        var multiFilterSelector = $(_self.calcInfoWindowId).find('.datagrid-view2').find('.datagrid-htable').find('tbody').find('tr:eq(1)').find('td[field="'+$fieldName+'"]');
                        if(filterDataJoin == '(1 == 1)') {
                            multiFilterSelector.find('.multipleFilterClass').children().css('color', '#30a2dd');
                            $(_self.dataGridId).datagrid('load', {salaryFilter: [], javaCacheId: _self.javaCacheId});
                        } else {                            
                            multiFilterSelector.find('.multipleFilterClass').children().css('color', '#ef2300');
                            $(_self.dataGridId).datagrid('load', {salaryFilter: _self.multifilterParams, javaCacheId: _self.javaCacheId, sheet: _self.changedGlobalEmpObj, dataIndex: _self.dataIndex});
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
                         
                        var multiFilterSelector = $(_self.calcInfoWindowId).find('.datagrid-view2').find('.datagrid-htable').find('tbody').find('tr:eq(1)').find('td[field="'+$fieldName+'"]');
                        if(filterDataJoin == '(1 == 1)') {
                            multiFilterSelector.find('.multipleFilterClass').children().css('color', '#30a2dd');
                            $(_self.dataGridId).datagrid('load', {salaryFilter: [], javaCacheId: _self.javaCacheId});
                        } else {
                            multiFilterSelector.find('.multipleFilterClass').children().css('color', '#ef2300');
                            $(_self.dataGridId).datagrid('load', {salaryFilter: _self.multifilterParams, javaCacheId: _self.javaCacheId, sheet: _self.changedGlobalEmpObj, dataIndex: _self.dataIndex});
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
            '<input type="text" name="multipleFilterData" style="width: 180px;" class="float-left ml20 form-control form-control-sm" placeholder="Утгаа оруулна уу">' +
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

SalaryV3.prototype.saveSalarySheetByPager = function() {
    var _self = this;
    
    setTimeout(function(){
        $.ajax({
            url: "mdsalary/saveCacheSalarySheetWebservice",
            type: "POST",
            data: {
                sheetData: _self.changedGlobalEmpObj,
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
                } else {
                    _self.changedGlobalEmpObj = [];
                }
            }
        });
    }, 0);
}

SalaryV3.prototype.deleteEmployeeSheet = function(elem, row) {
    var _self = this;
    var $dialogName = 'dialog-delete-confirm-employee_' + _self.windowId;
    
    row = JSON.parse(decodeURIComponent(row));

    if (row.isgl == '1') {
        PNotify.removeAll();
        new PNotify({
            title: 'Анхааруулга',
            text: 'Гүйлгээнд холбогдсон байна.',
            type: 'warning',
            sticker: false
        });  
        return;
    }

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
                    url: "mdsalary/saveCacheSalarySheetWebservice",
                    type: "POST",
                    data: {
                        sheetData: _self.changedGlobalEmpObj,
                        javaCacheId: _self.javaCacheId,
                        dataIndex: _self.dataIndex
                    },
                    dataType: "json",
                    async: false,
                    success: function (resp) {                    
                        if(resp.status !== 'error') {
                            _self.changedGlobalEmpObj = [];
                            
                            $.ajax({
                                url: "mdsalary/deleteEmployeeSheetWebservice",
                                type: "GET",
                                data: {
                                    empKeyId: row.employeekeyid,
                                    javaCacheId: _self.javaCacheId, 
                                    calcTypeId: $(_self.calcInfoWindowId).find("select[name=\"calcTypeId\"]").val(), 
                                    calcId: $(_self.calcInfoWindowId).find('.calcId_valueField').val()
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
                $("#" + $dialogName).dialog('close');
            }},
            {text: plang.get('no_btn'), class: 'btn blue-madison btn-sm', click: function () {
                $("#" + $dialogName).dialog('close');
            }}
        ]
    });
    $("#" + $dialogName).dialog('open');    
}

SalaryV3.prototype.getLogData = function(elem) {
    var _self = this;
    var sheetRow = _self.globalEmpObj[_self.getRowIndex(elem)];
    var formData = $(_self.calcInfoFormId).serialize();
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

SalaryV3.prototype.employeeInformation = function(elem) {
    var _self = this;
    var sheetRow = _self.globalEmpObj[_self.getRowIndex(elem)];
    sheetRow.id = sheetRow.employeekeyid
    delete sheetRow.employeekeyid;
    
    runWorkSpaceWithDataView(elem, '1482213710825357', '1484732973842603', '', sheetRow);
}

SalaryV3.prototype.employeeCalculateBp = function(elem) {
    var _self = this;
    var indexSheet = _self.getRowIndex(elem);
    var sheetRow = _self.globalEmpObj[indexSheet];

    var $dialogName = "dialog-salary-employee-calculate";
    if (!$("#" + $dialogName).length) {
      $('<div id="' + $dialogName + '" class="display-none"></div>').appendTo(
        "body"
      );
    }
    
    var $dialog = $("#" + $dialogName),
      jsonParam = '';

      jsonParam = JSON.stringify({
        employeeId: sheetRow.employeeid
      });

    $.ajax({
      type: "post",
      url: "mdwebservice/callMethodByMeta",
      data: {
        metaDataId: "1469942750893",
        isDialog: true,
        isSystemMeta: false,
        fillJsonParam: jsonParam,
        responseType: "json"
      },
      dataType: "json",
      beforeSend: function () {
        Core.blockUI({
          message: "Loading...",
          boxed: true,
        });
      },
      success: function (data) {
        $dialog.empty().append(data.Html);

        var $processForm = $("#wsForm", "#" + $dialogName),
          processUniqId = $processForm.parent().attr("data-bp-uniq-id");

        var buttons = [
          {
            text: data.run_btn,
            class: "btn green-meadow btn-sm bp-btn-save",
            click: function (e) {
              if (window["processBeforeSave_" + processUniqId]($(e.target))) {
                $processForm.validate({
                  ignore: "",
                  highlight: function (element) {
                    $(element).addClass("error");
                    $(element).parent().addClass("error");
                    if (
                      $processForm.find("div.tab-pane:hidden:has(.error)").length
                    ) {
                      $processForm
                        .find("div.tab-pane:hidden:has(.error)")
                        .each(function (index, tab) {
                          var tabId = $(tab).attr("id");
                          $processForm
                            .find('a[href="#' + tabId + '"]')
                            .tab("show");
                        });
                    }
                  },
                  unhighlight: function (element) {
                    $(element).removeClass("error");
                    $(element).parent().removeClass("error");
                  },
                  errorPlacement: function () { },
                });

                var isValidPattern = initBusinessProcessMaskEvent($processForm);

                if ($processForm.valid() && isValidPattern.length === 0) {
                  $processForm.ajaxSubmit({
                    type: "post",
                    url: "mdwebservice/runProcess",
                    dataType: "json",
                    beforeSend: function () {
                      Core.blockUI({
                        boxed: true,
                        message: "Loading..."
                      });
                    },
                    success: function (responseData) {
                      if (responseData.status === "success") {
                        var responseParam = responseData.paramData;
                        
                        for(var key in responseParam) {
                            if (key.search(new RegExp('f[0-9]', 'g')) !== -1) {
                                console.log(key);
                                _self.globalEmpObj[indexSheet][key] = responseParam[key];              
                            }
                        }                         
                        _self.changedGlobalEmpObj[indexSheet] = _self.globalEmpObj[indexSheet];
                        
                        $.ajax({
                            url: "mdsalary/saveCacheSalarySheetWebservice",
                            type: "POST",
                            data: {
                                sheetData: _self.changedGlobalEmpObj,
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
                                } else {
                                    _self.changedGlobalEmpObj = [];
                                    $(_self.dataGridId).datagrid('reload');
                                }
                            }
                        });                        
                        $dialog.dialog("close");
                      }
                      Core.unblockUI();
                    },
                    error: function () {
                      alert("Error");
                    },
                  });
                }
              }
            },
          },
          {
            text: data.close_btn,
            class: "btn blue-madison btn-sm",
            click: function () {
              $dialog.dialog("close");
            },
          },
        ];

        var dialogWidth = data.dialogWidth,
          dialogHeight = data.dialogHeight;

        if (data.isDialogSize === "auto") {
          dialogWidth = 1200;
          dialogHeight = "auto";
        }

        $dialog
          .dialog({
            cache: false,
            resizable: true,
            bgiframe: true,
            autoOpen: false,
            title: data.Title,
            width: dialogWidth,
            height: dialogHeight,
            modal: true,
            closeOnEscape:
              typeof isCloseOnEscape == "undefined" ? true : isCloseOnEscape,
            close: function () {
              $dialog.empty().dialog("destroy").remove();
            },
            buttons: buttons,
          })
          .dialogExtend({
            closable: true,
            maximizable: true,
            minimizable: true,
            collapsable: true,
            dblclick: "maximize",
            minimizeLocation: "left",
            icons: {
              close: "ui-icon-circle-close",
              maximize: "ui-icon-extlink",
              minimize: "ui-icon-minus",
              collapse: "ui-icon-triangle-1-s",
              restore: "ui-icon-newwin",
            },
          });
        if (data.dialogSize === "fullscreen") {
          $dialog.dialogExtend("maximize");
        }
        $dialog.dialog("open");
      },
      error: function () {
        alert("Error");
      },
    }).done(function () {
      Core.initBPAjax($dialog);
      Core.unblockUI();
    });
}

SalaryV3.prototype.salaryColumnConfigPosition = function() {
    var $dialogname = 'dialog-salary-column-config-position';
    var data = '', typeAllFields = [], _self = this, columnColors = [];

    $.ajax({
        type: 'post',
        url: 'Mdsalary/prlCalcTypeDtlByTypeIdList',
        data: {
            calcId: $(_self.calcInfoWindowId).find('.calcId_valueField').val(),
            params: $(_self.calcInfoFormId).serialize()
        },
        dataType: "json",
        async: false,
        success: function (data) {
            typeAllFields = data;
        }
    });        
    
    $.ajax({
      type: "post",
      url: "api/callDataview",
      data: {
        dataviewId: '1715155351623641'
      },
      dataType: "json",
      async: false,
      beforeSend: function () {
        Core.blockUI({
          message: "Loading...",
          boxed: true,
        });          
      },
      success: function (dataSub) {
        if (dataSub.status == "success" && dataSub.result.length) {
          isPinSuccess = true;
          columnColors = dataSub.result;
        }
        Core.unblockUI();
      }
    });    
    
//    var colorHtml = '';
//    colorHtml += '<select name="columnColorSet[]" class="form-control form-control-sm">';
//    colorHtml += '<option value="">'+plang.get("choose")+'</option>';
//    for(var i = 0; i < columnColors.length; i++) {
//        colorHtml += '<option value="'+columnColors[i]['color']+'">'+columnColors[i]['code']+'</option>';
//    };    
//    colorHtml += '</select>';

    data = '<div class="row">'+
        '<div class="col-md-12">'+
            '<form>'+
            '<table class="table table-sm table-bordered table-hover bprocess-table-dtl mb10 salaryColumnConfigPosTable">'+
                '<thead>'+
                    '<tr>'+
                        '<th class="rowNumber">№</th>'+
                        '<th>Баганы нэр</th>'+
                        '<th class="text-center">'+
                            '<label>'+
                                '<input type="checkbox" class="prl-salary-custom-column-check-all"> '+
                                'Харуулахгүй эсэх'+
                            '</label>'+
                        '</th>'+
                        '<th>Баганы өнгө</th>'+
                    '</tr>'+
                '</thead>'+
                '<tbody>';
                    var allFieldsLen = typeAllFields.length, ii = 1;
                    var colorHtml = '', colorSelected = '';
                    for(var i = 0; i < allFieldsLen; i++) {
                        data += '<tr id="config-' + typeAllFields[i]['META_DATA_CODE'] + '" style="display: table-row; cursor: move;">';
                        data += '<td class="ordernumber-' + typeAllFields[i]['META_DATA_CODE'] + ' dragHandle">' + ii + '</td>';
                        data += '<td>' + typeAllFields[i]['META_DATA_NAME'] + '<input type="hidden" name="SALARY_CONFIG_ORDER[]" id="order-' + typeAllFields[i]['META_DATA_CODE'] + '" value="' + ii + '"/>'+
                                '<input type="hidden" name="SALARY_CONFIG_ORDER_METACODE[]" value="' + typeAllFields[i]['META_DATA_CODE'] + '"/></td>';
                        data += '<td class="text-center">';
                        data += '<input type="hidden" name="IS_HIDE_USER_COL[]" id="show-' + typeAllFields[i]['META_DATA_CODE'] + '" value="' + (typeAllFields[i]['IS_SHOW'] == '1' ? '1' : '') + '"/>';
                        data += '<input type="checkbox" ' + (typeAllFields[i]['IS_SHOW'] == '1' ? 'checked' : '') + ' class="prl-salary-custom-column" id="' + typeAllFields[i]['META_DATA_CODE'] + '" />';
                        data += '</td>';
                        data += '<td class="text-center">';
                        
                        colorHtml = '';
                        colorHtml += '<select name="columnColorSet[]" style="background-color:'+typeAllFields[i]['USER_COLUMN_COLOR']+'" class="form-control form-control-sm">';
                        colorHtml += '<option value="">'+plang.get("choose")+'</option>';
                        for(var iii = 0; iii < columnColors.length; iii++) {
                            colorSelected = '';
                            if (typeAllFields[i]['USER_COLUMN_COLOR'] == columnColors[iii]['color']) {
                                colorSelected = ' selected';
                            }
                            colorHtml += '<option'+colorSelected+' value="'+columnColors[iii]['color']+'">'+columnColors[iii]['code']+'</option>';
                        };    
                        colorHtml += '</select>';        
                        data += colorHtml;
                        
                        data += '</td>';
                        data += '</tr>';
                        ii++;
                    };
    data += '</tbody>'+
            '</table>'+
            '</form>'+
        '</div>'+
    '</div>';

    if (!$('#'+$dialogname).length) {
        $('<div id="' + $dialogname + '"></div>').appendTo('body');
    }
    var dialogname = $('#dialog-salary-column-config-position');

    dialogname.empty().html(data);            
    dialogname.dialog({
        appendTo: 'body',
        cache: false,
        resizable: true,
        bgiframe: true,
        autoOpen: false,
        title: 'Баганы байршил өөрчлөх',
        width: 400,
        height: 'auto',
        modal: true,
        open: function () {
        },
        close: function (elem) {
            dialogname.dialog('close');
        },
        buttons: [
            {text: plang.get('save_btn'), class: 'btn btn-sm blue addEmployeeListToDataGrid', click: function (elem) {
                Core.blockUI({
                    message: 'Түр хүлээнэ үү...',
                    boxed: true
                });                            
                $.ajax({
                    type: 'post',
                    url: 'Mdsalary/setSalaryColumnOrder',
                    data: $(this).closest('.ui-dialog').find('form').serialize() + '&params=' + $(_self.calcInfoFormId).serialize() + '&calcId=' + $(_self.calcInfoWindowId).find('.calcId_valueField').val() + '&sheetData='+JSON.stringify(_self.changedGlobalEmpObj) + '&javaCacheId=' + _self.javaCacheId + '&dataIndex=' + _self.dataIndex,
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
                            _self.changedGlobalEmpObj = [];
                            $('input[name="fromCache"]', _self.calcInfoFormId).val('0');
                            $(_self.calcInfoWindowId).find('.reSearchCalcInfo').trigger('click', [true]);
                            $(_self.calcInfoWindowId).find('.searchCalcInfo').trigger('click');
                        }
                    }
                });
                dialogname.dialog('close'); 
            }},
            {text: plang.get('close_btn'), class: 'btn blue-hoki btn-sm', click: function () {
                dialogname.dialog('close');
            }}
        ]
    });
    dialogname.dialog('open');
    Core.initUniform(dialogname);

    dialogname.find('.salaryColumnConfigPosTable tbody').tableDnD({
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

SalaryV3.prototype.salaryDuplicateExcel = function(response) {
    var $dialogname = 'dialog-salary-duplicate-excel';
    var data = '', _self = this, activeCls = false;

    data = '<div class="row">'+
        '<div class="col-md-12">'+
            '<form><div class="tabbable-line">';
    data += '<ul class="nav nav-tabs">';
            if (response.hasOwnProperty('duplicatedemployees')) {
                data += '<li class="nav-item">'+
                    '<a href="#prl_salary_import_duplicate" class="nav-link active" data-toggle="tab">Давхардсан</a>'+
                '</li>';            
                activeCls = true;
            }
            if (response.hasOwnProperty('notfoundemployees')) {
                data += '<li class="nav-item">'+
                    '<a href="#prl_salary_import_notfound" class="nav-link '+(!activeCls ? 'active' : '')+'" data-toggle="tab">Олдоогүй</a>'+
                '</li>';            
                activeCls = true;
            }
            if (response.hasOwnProperty('invaliddataemployees')) {
                data += '<li class="nav-item">'+
                    '<a href="#prl_salary_import_invalid" class="nav-link '+(!activeCls ? 'active' : '')+'" data-toggle="tab">Алдаатай</a>'+
                '</li>';            
                activeCls = true;
            }
        data += '</ul><div class="tab-content">';
            
            activeCls = false;
            if (response.hasOwnProperty('duplicatedemployees')) {
                data += '<div class="tab-pane active" id="prl_salary_import_duplicate">' +
                '<table class="table table-sm table-bordered table-hover bprocess-table-dtl mb10">'+
                    '<thead>'+
                        '<tr>'+
                            '<th style="width: 25%">Код</th>'+
                            '<th>Овог</th>'+
                            '<th>Нэр</th>'+
                        '</tr>'+
                    '</thead>'+
                    '<tbody>';
                        var allFieldsLen = Object.keys(response['duplicatedemployees']).length;
                        data += '<tr>';
                        data += '<td colspan="3">Дараах <strong>' + allFieldsLen + '</strong> ажилтан excel дээр давхардсан байна</td>';
                        data += '</tr>';

                        for (var key of Object.keys(response['duplicatedemployees'])) {
                            data += '<tr>';
                            data += '<td>' + response['duplicatedemployees'][key]['employeecode'] + '</td>';
                            data += '<td>' + response['duplicatedemployees'][key]['lastname'] + '</td>';
                            data += '<td>' + response['duplicatedemployees'][key]['firstname'] + '</td>';
                            data += '</tr>';
                        };                    
                data += '</tbody>'+
                        '</table></div>';
                activeCls = true;
            }
            
            if (response.hasOwnProperty('notfoundemployees')) {
                var allFieldsLen = Object.keys(response['notfoundemployees']).length;
                
                data += '<div class="tab-pane'+(!activeCls ? ' active' : '')+'" id="prl_salary_import_notfound">';
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
                    for (var key of Object.keys(response['notfoundemployees'])) {
                        data += '<tr>';
                        data += '<td>' + response['notfoundemployees'][key]['employeecode'] + '</td>';
                        data += '<td>' + response['notfoundemployees'][key]['lastname'] + '</td>';
                        data += '<td>' + response['notfoundemployees'][key]['firstname'] + '</td>';
                        data += '</tr>';
                    };
                data += '</tbody>'+
                        '</table></div>';
            }
            
            if (response.hasOwnProperty('invaliddataemployees')) {
                var allFieldsLen = Object.keys(response['invaliddataemployees']).length;
                
                data += '<div class="tab-pane'+(!activeCls ? ' active' : '')+'" id="prl_salary_import_invalid">';
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
                    for (var key of Object.keys(response['invaliddataemployees'])) {
                        data += '<tr>';
                        data += '<td>' + response['invaliddataemployees'][key]['employeecode'] + '</td>';
                        data += '<td>' + response['invaliddataemployees'][key]['lastname'] + '</td>';
                        data += '<td>' + response['invaliddataemployees'][key]['firstname'] + '</td>';
                        data += '</tr>';
                    };
                data += '</tbody>'+
                        '</table></div>';
            }
            
            if (response.hasOwnProperty('duplicatedemployees_encode')) {
                data += '<input type="hidden" name="exceldatas_duplicatedemployees" value="' + response.duplicatedemployees_encode + '"/>';
            }
            if (response.hasOwnProperty('notfoundemployees_encode')) {
                data += '<input type="hidden" name="exceldatas_notfoundemployees" value="' + response.notfoundemployees_encode + '"/>';
            }
            if (response.hasOwnProperty('invaliddataemployees_encode')) {
                data += '<input type="hidden" name="exceldatas_invaliddataemployees" value="' + response.invaliddataemployees_encode + '"/>';
            }
            data += '</div></div></form>'+
        '</div>'+
    '</div>';

    if (!$('#'+$dialogname).length) {
        $('<div id="' + $dialogname + '"></div>').appendTo('body');
    }
    var dialogname = $('#'+$dialogname);

    dialogname.empty().html(data);            
    dialogname.dialog({
        appendTo: _self.calcInfoWindowId,
        cache: false,
        resizable: true,
        bgiframe: true,
        autoOpen: false,
        title: 'ЭКСЕЛЬ ИМПОРТ',
        width: 600,
        height: 'auto',
        "max-height": 450,
        modal: true,
        close: function (elem) {
            dialogname.dialog('close');
        },
        buttons: [{text: 'Экселээр татах', class: 'btn btn-sm blue', click: function (elem) {
                Core.blockUI({
                    message: 'Түр хүлээнэ үү...',
                    boxed: true
                });
                
                window.location.href = URL_APP + 'mdsalary/getDownloadSheetExcelCtrl?' + $(this).closest('.ui-dialog').find('form').serialize();
                dialogname.dialog('close');
                Core.unblockUI();
            }},            
            {text: plang.get('close_btn'), class: 'btn blue-hoki btn-sm', click: function () {
                dialogname.dialog('close');
            }}
        ]
    });
    dialogname.dialog('open');
}

SalaryV3.prototype.salaryTemplateExcelImportFields = function(datas) {
    var $dialogname = 'dialog-template-excel-config-import';
    var data = '', _self = this;

    data = '<div class="row">'+
        '<div class="col-md-12">'+
            '<form>'+
            '<div style="max-height:400px;"><table class="table table-sm table-bordered table-hover bprocess-table-dtl mb10">'+
                '<thead style="background-color: #fff;border-bottom: 1px solid #ddd;">'+
                    '<tr>'+
                        '<th class="rowNumber">№</th>'+
                        '<th class="rowNumber"><input type="checkbox" name="SALARY_CONFIG_EXCEL_IMPORT_TOTAL" value=""/></th>'+
                        '<th>Үзүүлэлтийн нэр</th>'+
                        '<th>Үзүүлэлтийн код</th>'+
                    '</tr>'+
                '</thead>'+
                '<tbody>';
                    var allFieldsLen = datas.allData[0].length, ii = 1, splitData;
                    for(var i = 3; i < allFieldsLen; i++) {
                        splitData = datas.allData[0][i].split('$$$');
                        data += '<tr class="'+(splitData[0] === 'employeecode' || splitData[0] === 'lastname' || splitData[0] === 'firstname' || splitData[1] === '1' ? '' : 'hidden')+'">';
                        data += '<td>' + (ii++) + '</td>';
                        if (splitData[0] === 'employeekeyid') {
                            data += '<td><input type="checkbox" checked name="SALARY_CONFIG_EXCEL_IMPORT[]" value="' + i + '"/></td>';
                        } else {
                            data += '<td><input type="checkbox" name="SALARY_CONFIG_EXCEL_IMPORT[]" value="' + i + '"/></td>';
                        }
                        data += '<td>' + datas.allData[1][i] + '</td>';
                        data += '<td>' + splitData[0] + '</td>';
                        data += '</tr>';
                    };
    data += '</tbody>'+
            '</table></div>'+
            '<input type="hidden" name="excelAllDatas" value="' + encodeURIComponent(JSON.stringify(datas.allData)) + '"/>'+
            '</form>'+
        '</div>'+
    '</div>';

    if (!$('#'+$dialogname, _self.calcInfoWindowId).length) {
        $('<div id="' + $dialogname + '"></div>').appendTo(_self.calcInfoWindowId);
    }
    var dialogname = $('#dialog-template-excel-config-import', _self.calcInfoWindowId);

    dialogname.empty().html(data);            
    dialogname.dialog({
        create: function(event, ui) {
            $(event.target).parent().css('position', 'fixed');
        },        
        appendTo: _self.calcInfoWindowId,
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
                    data: $(this).closest('.ui-dialog').find('form').serialize() + '&calcTypeId=' + $("select[name=\"calcTypeId\"]", _self.calcInfoWindowId).val() + '&javaCacheId=' + _self.javaCacheId + '&calcId=' + $(_self.calcInfoWindowId).find('.calcId_valueField').val() + '&departmentId=' + $('.departmentId_' + _self.windowId).val() + '&isChild=' + ($(_self.calcInfoWindowId).find('input[name="isChild"]').is(':checked') ? 1 : 0),
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

                        if(data.status === 'error') {
                            _self.salaryDuplicateExcel(data);
                        }
                        
                        $(_self.dataGridId).datagrid('reload');
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
    statementHeaderFreeze(dialogname);
    
    $(_self.calcInfoWindowId).on('click', 'input[name="SALARY_CONFIG_EXCEL_IMPORT_TOTAL"]', function(){
        if($(this).is(':checked')) {
            dialogname.find('tbody > tr').each(function(){
                if (!$(this).hasClass('hidden')) {
                    $(this).find('input[name="SALARY_CONFIG_EXCEL_IMPORT[]"]').prop('checked', true).parent().addClass('checked');
                }
            });
        } else {
            dialogname.find('tbody > tr').each(function(){
                if (!$(this).hasClass('hidden')) {
                    $(this).find('input[name="SALARY_CONFIG_EXCEL_IMPORT[]"]').prop('checked', false).parent().removeClass('checked');
                }
            });            
        }
    });
}

SalaryV3.prototype.salaryGetProcessRun = function(datas, selectedEmployee) {
    var $dialogname = 'dialog-template-salaryget-processrun';
    var data = '', _self = this;

    data = '<div class="row">'+
        '<div class="col-md-12">'+
            '<div class="alert alert-info mb10"><i>Дараах багануудын утгыг шинэчлэх гэж байна итгэлтэй байна уу?</i></div>'+
            '<form><table class="table table-sm table-bordered table-hover bprocess-table-dtl mb10">'+
                '<thead>'+
                    '<tr>'+
                        '<th style="text-align:left" class="rowNumber">№</th>'+
                        '<th style="text-align:left" class="rowNumber"><input type="checkbox"/></th>'+
                        '<th style="text-align:left">Үзүүлэлтийн нэр</th>'+
                    '</tr>'+
                '</thead>'+
                '<tbody>';
                    var allFieldsLen = datas.length, ii = 1;
                    for(var i = 0; i < allFieldsLen; i++) {
                        data += '<tr>';
                        data += '<td>' + (ii++) + '</td>';
                        data += '<td><input type="checkbox"/><input type="hidden" value="'+datas[i].META_DATA_ID+'"/>';
                        data += '<input type="hidden" value="'+datas[i].FIELD_CODE+'"/></td>';
                        data += '<td>' + datas[i].META_DATA_NAME + '</td>';
                        data += '</tr>';
                    };
    data += '</tbody>'+
            '</table></form>'+
        '</div>'+
    '</div>';

    if (!$('#'+$dialogname, _self.calcInfoWindowId).length) {
        $('<div id="' + $dialogname + '"></div>').appendTo('body');
    }
    var dialogname = $('#dialog-template-salaryget-processrun');
    
    dialogname.on('click', 'input[type="checkbox"]:not(table thead input[type="checkbox"])', function(){
        if($(this).is(':checked')) {
            $(this).parent().find('input[type="hidden"]:first').attr('name', 'getProccesRunCode[]');
            $(this).parent().find('input[type="hidden"]:last').attr('name', 'fieldCode[]');
        } else
            $(this).parent().find('input[type="hidden"]').removeAttr('name');
    });
    
    dialogname.on('click', 'table thead input[type="checkbox"]', function(){
        if($(this).is(':checked')) {
            $(this).closest('table').find('tbody tr').each(function(){
                $(this).find('input[type="hidden"]:first').attr('name', 'getProccesRunCode[]');
                $(this).find('input[type="hidden"]:last').attr('name', 'fieldCode[]');
                $(this).find('input[type="checkbox"]').prop('checked', true);
            });
        } else {
            $(this).closest('table').find('tbody tr').each(function(){
                $(this).find('input[type="hidden"]').removeAttr('name');
                $(this).find('input[type="checkbox"]').prop('checked', false);
            });
        }
    });

    dialogname.empty().html(data); 
    dialogname.dialog({
        appendTo: 'body',
        cache: false,
        resizable: true,
        bgiframe: true,
        autoOpen: false,
        title: 'Анхааруулга',
        width: 400,
        height: 'auto',
        modal: true,
        close: function (elem) {
            dialogname.dialog('close');
        },
        buttons: [
            {text: plang.get('yes_btn'), class: 'btn btn-sm blue', click: function (elem) {
                if(dialogname.find('form').find('input[type="checkbox"]:checked').length === 0) {
                    PNotify.removeAll();
                    new PNotify({
                        title: 'warning',
                        text: 'Баганы утга сонгоно уу!',
                        type: 'warning',
                        sticker: false
                    });
                    return;
                }
                
                Core.blockUI({
                    message: 'Утга дахин татуулж байна...',
                    boxed: true
                });
                
                /*var localFroFields = _self.frozenFields;
                var localFields = _self.fields;

                for(var i = 0; i < localFroFields.length; i++) {
                    if (localFroFields[i].hasOwnProperty('formatter')) {
                        delete localFroFields[i]['formatter'];
                    }
                }
                for(var i = 0; i < localFields.length; i++) {
                    if (localFields[i].hasOwnProperty('formatter')) {
                        delete localFields[i]['formatter'];
                    }
                }*/
                
                var getFooter = $(_self.dataGridId).datagrid('getFooterRows');
                var params = {
                    javaCacheId: _self.javaCacheId,
                    formData: dialogname.find('form').serialize(),
                    params: $(_self.calcInfoFormId).serialize(),
                    datagridFrozenColumns: {},
                    datagridColumns: {},                    
                    datagridFooters: getFooter
                };
                if (typeof selectedEmployee !== 'undefined') {
                    var selectedRows = $(_self.dataGridId).datagrid('getSelections');
                    params.selectedEmployees = selectedRows;
                }
                
                $.ajax({
                    type: 'post',
                    url: 'Mdsalary/getProcessRun',
                    data: params,
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
            {text: plang.get('no_btn'), class: 'btn blue-hoki btn-sm', click: function () {
                dialogname.dialog('close');
            }}
        ]
    });
    dialogname.dialog('open');
    
    $(_self.calcInfoWindowId).on('click', 'input[name="SALARY_CONFIG_EXCEL_IMPORT_TOTAL"]', function(){
        if($(this).is(':checked')) {
            dialogname.find('input[name="SALARY_CONFIG_EXCEL_IMPORT[]"]').prop('checked', true).parent().addClass('checked');
        } else
            dialogname.find('input[name="SALARY_CONFIG_EXCEL_IMPORT[]"]').prop('checked', false).parent().removeClass('checked');
    })
}

SalaryV3.prototype.expressionErrorEmployee = function(elem) {
    $(elem).closest('ul').children('li').removeClass('current');
    $(elem).closest('li').addClass('current');
    
    if($(elem).closest('li').children('div').hasClass('hidden'))
        $(elem).closest('li').children('div').removeClass('hidden');
    else
        $(elem).closest('li').children('div').addClass('hidden');
    
    $(elem).closest('ul').children('li').each(function(){
        if(!$(this).hasClass('current'))
            $(this).children('div').addClass('hidden');
    });
}

SalaryV3.prototype.actionBtnListener = function(elem) {
    var _this = $(elem), _self = this;
    
    if(configKeyUpdate == '1') {
        _this.parent().find('ul > li:last').empty().append('<a class="keyUpdateEmployeeButton" onclick="dataViewCustomSelectableGrid(\'Keyslist\', \'multi\', \'selectedKeyEmployeeSalary\', \'criteriaCondition[salBookId]==&criteriaCondition[filterEndDate]==&param[salBookId]=' + $(_self.calcInfoWindowId + ' input[name=salaryBookId]').val() + '&param[filterEndDate]=' + $(_self.calcInfoWindowId + ' input[name=calcId]').attr('data-enddate') + '\', this)" href="javascript:;">Key Update</a>');
    }
}

SalaryV3.prototype.selectedKeyEmployeeSalary = function(rows) {
    var _self = this, rowsObj = [];
    
    if(rows.length > 0) {
        for(var i = 0; i < rows.length; i++) {
            rowsObj.push({
                'newid': rows[i].newid,
                'oldid': rows[i].oldid
            });
        }
    }
    
    $.ajax({
        url: "mdsalary/updateKeyEmployeeWebservice",
        type: "POST",
        data: {
            processCacheId: _self.javaCacheId,
            employeeKeyIds: rowsObj,
            params: $(_self.calcInfoFormId).serialize()
        },
        dataType: "json",
        async: false,
        success: function (resp) {                    
            if(resp.status !== 'error') {    
                $(_self.dataGridId).datagrid('reload');
                PNotify.removeAll();
                new PNotify({
                    title: 'Амжилттай',
                    text: 'Амжилттай шинэчлэгдлээ.',
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
}

SalaryV3.prototype.warningBatchNumber = function() {
    var _self = this;
    var $dialogName = 'dialog-batchNumber-confirm-employee_' + _self.windowId;
    
    $("#" + $dialogName).empty().html('<strong>' + $('input[name="calcId"]', _self.calcInfoFormId).closest('.next-generation-input-wrap').find('.next-generation-input-body').text() + '</strong>-н бодолт хийгдсэн байна<br>Та бодолтын жагсаалтнаас засахаар дуудна уу.');
    $("#" + $dialogName).dialog({
        cache: false,
        resizable: false,
        bgiframe: true,
        autoOpen: false,
        title: 'Анхааруулга',
        width: 330,
        height: "auto",
        modal: true,
        close: function () {
            $("#" + $dialogName).empty().dialog('close');
        },
        buttons: [
            {text: plang.get('close_btn'), class: 'btn blue-madison btn-sm', click: function () {
                $("#" + $dialogName).dialog('close');
            }}
        ]
    });
    $("#" + $dialogName).dialog('open');    
}

SalaryV3.prototype.multipleDuplicateValue = function(elem) {
    var $fieldName = $(elem).closest('td').attr('field'), _self = this;
    var dialogname = $('#dialog-multiple-duplicate_' + _self.windowId + '_' + $fieldName);
    var $dialogname = 'dialog-multiple-duplicate_' + _self.windowId + '_' + $fieldName;
    var data = '';

    data = '<span><div>';
        data += '<select id="duplicateColumnField_'+_self.windowId+'" name="multipleDuplicateField" class="form-control select2 form-control-sm ml15" required="required" data-placeholder="- Сонгох -" style="width: 260px">';
        data += '<option value="">- Сонгох -</option>';
        $.each(_self.fields, function (key, value) {
            if(value['title'] != '' && value['disable'] != '1')
                data += '<option value="' + value['field'] + '">' + value['title'] + '</option>';
        });
        data += '</select>';
        data += '<input type="text" name="multipleMultiFilterData" style="width: 130px;" class="float-left ml20 form-control form-control-sm" placeholder="Утгаа оруулна уу">'+
        '<a href="javascript:;" class="float-right btn mt0 btn-xs btn-success multipleDuplicateValueBtn" title="Нэмэх" onclick="multipleDuplicateValueFn(this, \''+encodeURIComponent(JSON.stringify(_self.fields))+'\', '+_self.windowId+');">'+
            '<i class="icon-plus3 font-size-12"></i>'+
        '</a>'+
    '</div></span>';

    if (!dialogname.length) {
        $('<div id="' + $dialogname + '"></div>').appendTo('body');
    }
    dialogname = $('#dialog-multiple-duplicate_' + _self.windowId + '_' + $fieldName);

    if (dialogname.children().length > 0) {
        dialogname.dialog({
            cache: false,
            resizable: true,
            bgiframe: true,
            autoOpen: false,
            title: 'Олон баганаар ижилсүүлэх',
            width: 500,
            height: 'auto',
            modal: true,
            open: function () {              
                $('div[aria-describedby=' + $dialogname + '] .ui-dialog-buttonset').addClass("btn-group float-right");
                $('div[aria-describedby=' + $dialogname + '] .ui-dialog-buttonset').find('button:eq(0)').addClass('btn btn-sm blue mr0 addEmployeeListToDataGrid');
                $('div[aria-describedby=' + $dialogname + '] .ui-dialog-buttonset').find('button:eq(1)').addClass('btn blue-hoki btn-sm ml5');
                $('div[aria-describedby=' + $dialogname + '] .ui-dialog-buttonset').find('button:eq(2)').addClass('btn blue-hoki btn-sm ml5');
                $('#duplicateColumnField_'+_self.windowId).select2();
            },
            close: function () {
                dialogname.dialog('close');
            },
            buttons: [
                {text: 'Ижилсүүлэх', click: function () {

                     var multifilterDatas = [], filterParamsMulti = {};
                     $(this).closest('div.ui-dialog').find('input[name="multipleMultiFilterData"]').each(function(k){
                        var getField = $(this).closest('div.ui-dialog').find('select[name="multipleDuplicateField"]:eq('+k+')').val();
                        var filterVal = {};
                        
                        if($(this).val().trim() != '') {
                            filterVal['metaDataCode'] = getField;
                            filterVal['value'] = $(this).val().trim().replace(/[,]/g, '');
                            multifilterDatas.push(filterVal);
                        }        
                     }).promise().done(function () {
                        var filValue = $("input.datagrid-filter", _self.calcInfoWindowId);
                
                        $.each(filValue, function(){
                            var $this = $(this);
                            
                            if($this.val() != '') {
                                filterParamsMulti[$this.attr('name')] = $this.val();
                            }
                        });
   
                        $.ajax({
                           type: 'post',
                           url: 'Mdsalary/copyMultiFieldRowSheetWebservice',
                           data: {
                               criteria: filterParamsMulti,
                               values: multifilterDatas,
                               javaCacheId: _self.javaCacheId,
                               params: $(_self.calcInfoFormId).serialize()
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

                     });

                    dialogname.dialog('close');
                }},
                {text: plang.get('close_btn'), click: function () {
                    dialogname.dialog('close');
                }}
            ]
        });
        dialogname.dialog('open');
    } else {
        dialogname.empty().html(data);           
        dialogname.dialog({
            cache: false,
            resizable: true,
            bgiframe: true,
            autoOpen: false,
            title: 'Олон баганаар ижилсүүлэх',
            width: 500,
            height: 'auto',
            modal: true,
            open: function () {
                $('div[aria-describedby=' + $dialogname + '] .ui-dialog-buttonset').addClass("btn-group float-right");
                $('div[aria-describedby=' + $dialogname + '] .ui-dialog-buttonset').find('button:eq(0)').addClass('btn btn-sm blue mr0 addEmployeeListToDataGrid');
                $('div[aria-describedby=' + $dialogname + '] .ui-dialog-buttonset').find('button:eq(1)').addClass('btn blue-hoki btn-sm ml5');                                                                                      
                $('div[aria-describedby=' + $dialogname + '] .ui-dialog-buttonset').find('button:eq(2)').addClass('btn blue-hoki btn-sm ml5');   
                $('#duplicateColumnField_'+_self.windowId).select2();             
            },
            close: function () {
                dialogname.dialog('close');
            },
            buttons: [
                {text: 'Ижилсүүлэх', click: function () {

                    var multifilterDatas = [], filterParamsMulti = {};
                    $(this).closest('div.ui-dialog').find('input[name="multipleMultiFilterData"]').each(function(k){
                       var getField = $(this).closest('div.ui-dialog').find('select[name="multipleDuplicateField"]:eq('+k+')').val();
                       var filterVal = {};
                       
                       if($(this).val().trim() != '') {
                           filterVal['metaDataCode'] = getField;
                           filterVal['value'] = $(this).val().trim().replace(/[,]/g, '');
                           multifilterDatas.push(filterVal);
                       }        
                    }).promise().done(function () {
                       var filValue = $("input.datagrid-filter", _self.calcInfoWindowId);
               
                       $.each(filValue, function(){
                           var $this = $(this);
                           
                           if($this.val() != '') {                               
                                filterParamsMulti[$this.attr('name')] = $this.val();
                           }
                       });
  
                       $.ajax({
                          type: 'post',
                          url: 'Mdsalary/copyMultiFieldRowSheetWebservice',
                          data: {
                              criteria: filterParamsMulti,
                              values: multifilterDatas,
                              javaCacheId: _self.javaCacheId,
                              params: $(_self.calcInfoFormId).serialize()
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

    $('.numberInit2').autoNumeric('init',
        {aPad: true, mDec: 2, vMin: '-999999999999999999999999999999.999999999999999999999999999999'}
    );                
}

var multipleDuplicateValueFn = function(elem, fields, wId) {
    var getDiv = $(elem).parent().parent(), data = '', 
        parseFields = JSON.parse(decodeURIComponent(fields));

    data = '<select name="multipleDuplicateField" class="form-control select2 form-control-sm ml15 duplicateColumnField_'+wId+'" required="required" data-placeholder="- Сонгох -" style="width: 260px">';
    data += '<option value="">- Сонгох -</option>';
    $.each(parseFields, function (key, value) {
        if(value['title'] != '' && value['disable'] != '1')
            data += '<option value="' + value['field'] + '">' + value['title'] + '</option>';
    });
    data += '</select>';    
    $(getDiv).append(
            '<div class="clearfix w-100"></div><div class="mt5">' + data + 
            '<input type="text" name="multipleMultiFilterData" style="width: 130px;" class="float-left ml20 form-control form-control-sm" placeholder="Утгаа оруулна уу">'+
            '<a href="javascript:;" class="mt0 float-right btn btn-xs btn-danger multipleDuplicateValueBtn" title="Устгах" onclick="multipleRemoveDuplicate(this);"><i class="icon-cross2 font-size-12"></i></a>' +
            '</div>'
    );  

    $('.numberInit2').autoNumeric('init',
        {aPad: true, mDec: 2, vMin: '-999999999999999999999999999999.999999999999999999999999999999'}
    );   
    $('.duplicateColumnField_' + wId + ':last').select2();    
}; 

var multipleRemoveDuplicate = function(element) {
    $(element).parent().remove();
};