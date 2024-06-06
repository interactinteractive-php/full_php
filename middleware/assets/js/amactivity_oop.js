var activityIsWithChild = 0;

var Amactivity = function(activityKeyId) {
    this.metaDataId = activityKeyId;
    this.dataGridId = '#objectdatagrid_' + activityKeyId;
    this.dataGridIdAggregate = '#aggregatedatagrid_' + activityKeyId;
    this.dataGridIdAggregate2 = '#aggregate2datagrid_' + activityKeyId;
    this.mainWindowId = '#amactivityMaindWindow';
    this.mainTemplateWindowId = '#amactivityTemplateMaindWindow';
    this.mainAggregateWindowId = '#amactivityAggregateMaindWindow';
    this.mainAggregate2WindowId = '#amactivityAggregate2MaindWindow';
    this.activityKeyId = activityKeyId;
    this.periodId = null;
    this.headerConfig = {};
    this.headerDataGlobal = {};
    this.activeField = '';
    this.activeIndex = '';
    this.templateHeader = {};
    this.counterGlobal = '';
    this.columnFieldsHeaderFirst = [];
    this.columnFieldsHeader = [];
    this.dgFirstHeaderDataGlobal = [];
    this.headerDataFreezeGlobal = [];
    
    this.getRowIndex = function(target) {
        var tr = $(target).closest('tr.datagrid-row');
        return parseInt(tr.attr('datagrid-row-index'));
    };
    
    this.nextTR = function(tr, td) {
        var tr = tr.next('tr');
        var trIndex = tr.index();
        var td = tr.find("td:eq("+td.index()+")");        
        if(typeof td.attr('style') !== 'undefined')
            this.nextTR(tr, td);
        else if(trIndex !== -1) {
            td = td.attr("field");
            $(this.dataGridId, this.mainWindowId).datagrid('editCell', {
                index: trIndex,
                field: td
            });                
            
            var headerConfigVar = typeof this.headerConfig[td] !== 'undefined' ? this.headerConfig[td] : '';
            this.activeField = td;
            if((headerConfigVar.type === '0' || headerConfigVar.type === '2') && headerConfigVar.isattribute === 'false') {
                var ed = $(this.dataGridId, this.mainWindowId).datagrid('getEditor', {index: trIndex, field: td});
                $(".activity-expression-viewer", this.mainWindowId).find('span').empty();
                var rows = $(this.dataGridId, this.mainWindowId).datagrid('getRows');
                
            
                if(rows[trIndex].haschild === '1' || (rows[trIndex][td+'_formula'] !== undefined && rows[trIndex][td+'_formula'] !== null && rows[trIndex][td+'_formula'] !== '')) {
                    $(ed.target).numberbox('readonly');
                    $(".activity-expression-viewer", this.mainWindowId).find('span').append(rows[trIndex][td+'_formula']);
                }
                
                if (headerConfigVar.type === '0' && rows[trIndex].haschild !== '1') {
                    if(rows[trIndex].haschild === '1' || (rows[trIndex][td+'_formula'] !== undefined && rows[trIndex][td+'_formula'] !== null && rows[trIndex][td+'_formula'] !== '')) {
                        $(ed.target).parent().find('span').find('input[type="text"]').css('width', '100%');
                    } else {
                        $(ed.target).parent().find('span').find('input[type="text"]').css('width', '121px');
                        $(ed.target).parent().find('span').append('<a class="btn btn-warning btn-sm" title="Томъёо оруулах" onclick="amactivityObj.insertFormExpression(this);" href="javascript:;" style="width: 25px;border-radius: 0px;padding-left: 6px;"><i class="fa fa-calculator"></i></a>');    
                    }
                } else {
                    $(ed.target).parent().find('span').find('input[type="text"]').css('text-align', 'right');
                }
            }
        }
    };    
    
    this.prevTR = function(tr, td) {
        var tr = tr.prev('tr');
        var trIndex = tr.index();
        var td = tr.find("td:eq("+td.index()+")");        
        if(typeof td.attr('style') !== 'undefined')
            this.prevTR(tr, td);
        else if(trIndex !== -1) {
            td = td.attr("field");
            $(this.dataGridId, this.mainWindowId).datagrid('editCell', {
                index: trIndex,
                field: td
            });
            
            var headerConfigVar = typeof this.headerConfig[td] !== 'undefined' ? this.headerConfig[td] : '';
            this.activeField = td;
            if((headerConfigVar.type === '0' || headerConfigVar.type === '2') && headerConfigVar.isattribute === 'false') {            
                var ed = $(this.dataGridId, this.mainWindowId).datagrid('getEditor', {index: trIndex, field: td});
                $(".activity-expression-viewer", this.mainWindowId).find('span').empty();
                var rows = $(this.dataGridId, this.mainWindowId).datagrid('getRows');
                
                if (headerConfigVar.type === '0' && rows[trIndex].haschild !== '1') {
                    $(ed.target).parent().find('span').find('input[type="text"]').css('width', '121px');
                    $(ed.target).parent().find('span').append('<a class="btn btn-warning btn-sm" title="Томъёо оруулах" onclick="amactivityObj.insertFormExpression(this);" href="javascript:;" style="width: 25px;border-radius: 0px;padding-left: 6px;"><i class="fa fa-calculator"></i></a>');
                } else {
                    $(ed.target).parent().find('span').find('input[type="text"]').css('text-align', 'right');
                }
            
                
                if(rows[trIndex].haschild === '1' || (rows[trIndex][td+'_formula'] !== undefined && rows[trIndex][td+'_formula'] !== null && rows[trIndex][td+'_formula'] !== '')) {
                    $(ed.target).numberbox('readonly');
                    $(".activity-expression-viewer", this.mainWindowId).find('span').append(rows[trIndex][td+'_formula']);
                }   
                
                if (headerConfigVar.type === '0' && rows[trIndex].haschild !== '1') {
                    if(rows[trIndex].haschild === '1' || (rows[trIndex][td+'_formula'] !== undefined && rows[trIndex][td+'_formula'] !== null && rows[trIndex][td+'_formula'] !== '')) {
                        $(ed.target).parent().find('span').find('input[type="text"]').css('width', '100%');
                    } else {
                        $(ed.target).parent().find('span').find('input[type="text"]').css('width', '121px');
                        $(ed.target).parent().find('span').append('<a class="btn btn-warning btn-sm" title="Томъёо оруулах" onclick="amactivityObj.insertFormExpression(this);" href="javascript:;" style="width: 25px;border-radius: 0px;padding-left: 6px;"><i class="fa fa-calculator"></i></a>');    
                    }
                } else {
                    $(ed.target).parent().find('span').find('input[type="text"]').css('text-align', 'right');
                }
            }
            
        }
        
    };    
    
    this.dataExportToExcelAmactivity = function(elem) {
        Core.blockUI({
            message: 'Exporting...', 
            boxed: true
        });
        $.fileDownload(URL_APP + 'amactivity/dataAggregateExcelExport', {
            httpMethod: "POST",
            data: {
                activityKeyId: activityKeyId,
                fData: $(elem).attr('data-formdata')
            }
        }).done(function() {
            Core.unblockUI();
        }).fail(function(){
            alert("File download failed!");
            Core.unblockUI();
        });        
    };
    
    this.dataExportToExcelAmactivity2 = function() {
        Core.blockUI({
            message: 'Exporting...', 
            boxed: true
        });
        $.fileDownload(URL_APP + 'amactivity/dataAggregateExcelExport2', {
            httpMethod: "POST",
            data: {
                activityKeyId: activityKeyId
            }
        }).done(function() {
            Core.unblockUI();
        }).fail(function(){
            alert("File download failed!");
            Core.unblockUI();
        });        
    };
    
    var _thisObj = this;
    this.dataActivitySheetCtrlExcelExport = function() {
        Core.blockUI({
            message: 'Exporting...', 
            boxed: true
        });
        $.fileDownload(URL_APP + 'amactivity/getAllActivitySheetCtrlExcelExport', {
            httpMethod: "POST",
            data: {
                activityKeyId: activityKeyId,
                periodId: _thisObj.periodId
            }
        }).done(function() {
            Core.unblockUI();
        }).fail(function(){
            alert("File download failed!");
            Core.unblockUI();
        });        
    };
    
    this.dataExportToPdfAmactivity = function() {
        Core.blockUI({
            message: 'Exporting...', 
            boxed: true
        });
        $.fileDownload(URL_APP + 'amactivity/dataAggregatePdfExport', {
            httpMethod: "POST",
            data: {
                activityKeyId: activityKeyId
            }
        }).done(function() {
            Core.unblockUI();
        }).fail(function(){
            alert("File download failed!");
            Core.unblockUI();
        });        
    }
    
    this.removeActivityFile = function(element) {
        $(element).parent().remove();
    }        
};

Amactivity.prototype.initEventListener = function() {
    var _thisObj = this;

    $(".searchActivityInfo", _thisObj.mainWindowId).on("click", function(){
        _thisObj.periodId = $("#periodId", _thisObj.mainWindowId).val();        
        // <editor-fold defaultstate="collapsed" desc="Validation">
        $("#activityInfoForm", _thisObj.mainWindowId).validate({errorPlacement: function() {
        }});        
        if (!$("#activityInfoForm", _thisObj.mainWindowId).valid()) {
            PNotify.removeAll();
            new PNotify({
                title: 'Warning',
                text: 'Арга хэмжээгээ сонгоно уу!',
                type: 'warning',
                sticker: false
            });  
            return;
        } else if(_thisObj.periodId === null) {
            PNotify.removeAll();
            new PNotify({
                title: 'Warning',
                text: 'Period тохируулаагүй байна!',
                type: 'warning',
                sticker: false
            });          
            return;
        }
        // </editor-fold>        
        $(".activityBtnSection", _thisObj.mainWindowId).removeClass("hide");     
        _thisObj.activityKeyId = $("#activityInfoForm", _thisObj.mainWindowId).find("#activityKeyId_valueField").val();
        _thisObj.loadDataGrid();
    });    
    
    $(".saveActivitySheetBtn", _thisObj.mainWindowId).on("click", function(){
        _thisObj.saveActivity();
    });
    
    $("#periodId", _thisObj.mainWindowId).on("change", function(){
        _thisObj.editModeLoadDataGrid();
    });    
    
    $("#roundValue", _thisObj.mainWindowId).on("change", function(){
        _thisObj.loadDataGrid();
    });    
    
    var tabSelector = $("a[href='#bp_main_tab_1487128569927652__']", _thisObj.mainWindowId);
    if(tabSelector.length)
        tabSelector.trigger('click');
    
    var offsetTopExpression = $(".activity-expression-viewer", _thisObj.mainWindowId).offset().top - 65;
    /*
    $(window).scroll(function(){
        var scrollPos = offsetTopExpression - $(this).scrollTop();
        if(scrollPos <= 0) {
            var vWidth = $("#dataGridDiv", _thisObj.mainWindowId).children("div").width();
            $(".activity-expression-viewer", _thisObj.mainWindowId).addClass("activity-expression-viewer-class").css('width', vWidth+'px');
        } else
            $(".activity-expression-viewer", _thisObj.mainWindowId).removeClass("activity-expression-viewer-class").attr('style', 'background-color: #FFDEA5; padding: 6px 10px;');
    });   
    
    $(window).resize(function(){
        setTimeout(function(){
            var vSelector = $(".activity-expression-viewer", _thisObj.mainWindowId);
            if(vSelector.hasClass('activity-expression-viewer-class')) {
                vSelector.css('width', $("#dataGridDiv", _thisObj.mainWindowId).children("div").width()+'px');
            }
        }, 100);
    });
    */
    $("body").on("keydown", 'input.lookup-code-autocomplete-activity:not(disabled, readonly)', function(e){
        var code = (e.keyCode ? e.keyCode : e.which);
        var _this = $(this);
        if (code === 13) {
            if (_this.data("ui-autocomplete")) {
                _this.autocomplete("destroy");
            }
            return false;
        } else {
            if (!_this.data("ui-autocomplete")) {
                lookupAutoCompleteActivity(_this, 'code');
            }
        }
    });    
    
    $(_thisObj.mainWindowId).on('hover', '.datagrid-cell', function() {
        var _this = $(this), _thisColField = _this.parent().attr('field');
        
        if(_this.closest('tr').hasClass('datagrid-header-row') && typeof _thisObj.headerConfig[_thisColField + '_comment'] !== 'undefined') {
            if(_thisObj.headerConfig[_thisColField + '_comment'] !== '' && typeof _this.parent().data('hasqtip') === 'undefined') {
                _this.parent().qtip({
                    content: {
                        text: _thisObj.headerConfig[_thisColField + '_comment']
                    },
                    position: {
                        my: 'top center',
                        at: 'bottom center'
                    },
                    style: {
                        classes: 'qtip-blue qtip-rounded vr-qtip'
                    },
                    show: { ready: true, when: false }
                });            
            }
        }
        
        if(_thisColField === 'description') {
            var width = _this.find('span:first').width();
            
            $(".qtip").remove();
            if (width > _this.width()) {
                _this.parent().qtip({
                    content: {
                        text: _this.children().find('span:eq(1)').text()
                    },
                    position: {
                        my: 'top center',
                        at: 'bottom center'
                    },
                    style: {
                        classes: 'qtip-blue qtip-rounded vr-qtip'
                    },
                    show: { ready: true, when: false }
                });
            }
        }
    });
    $(".tool-collapse", _thisObj.mainAggregateWindowId).on('click', function () {
        var _this = $(this);
        if(_this.hasClass('collapse')) {
            $(".card-collapse", _thisObj.mainAggregateWindowId).removeClass('_collapse');
            var dynamicHeight = $(window).height() - 205;
            $(_thisObj.dataGridIdAggregate).attr('height', dynamicHeight);
            $(_thisObj.dataGridIdAggregate).datagrid('resize', {
                height: dynamicHeight
            });
        } else {
            $(".card-collapse", _thisObj.mainAggregateWindowId).addClass('_collapse');
            var dynamicHeight = $(window).height() - 300;
            $(_thisObj.dataGridIdAggregate).attr('height', dynamicHeight);
            $(_thisObj.dataGridIdAggregate).datagrid('resize', {
                height: dynamicHeight
            });
        }
    });
    
    $(".card-collapse", _thisObj.mainAggregateWindowId).on('click', function () {
        var _this = $(this);
        if(_this.hasClass('_collapse')) {
            _this.removeClass('_collapse');
            var dynamicHeight = $(window).height() - 205;
            $(_thisObj.dataGridIdAggregate).attr('height', dynamicHeight);
            $(_thisObj.dataGridIdAggregate).datagrid('resize', {
                height: dynamicHeight
            });
        } else {
            _this.addClass('_collapse');
            var dynamicHeight = $(window).height() - 300;
            $(_thisObj.dataGridIdAggregate).attr('height', dynamicHeight);
            $(_thisObj.dataGridIdAggregate).datagrid('resize', {
                height: dynamicHeight
            });
        }
    });
    
    //Core.initSelect2(_thisObj.mainAggregateWindowId);

    $(".bp-addon-tab > li > ", _thisObj.mainWindowId).on("click", function() {
        if($(this).attr('href') === '#commonSelectableTabBasket') {
            $('.activityMainTabArea', _thisObj.mainWindowId).hide();
            $('#commonSelectableBasketDataGrid', _thisObj.mainWindowId).datagrid('resize');
            setTimeout(function () {
                $('#commonSelectableBasketDataGrid', _thisObj.mainWindowId).datagrid('resize');
            }, 0);            
        } else
            $('.activityMainTabArea', _thisObj.mainWindowId).show();
    });
};

Amactivity.prototype.initEventListenerTemplate = function() {
    var _thisObj = this;
    var offsetTopExpression = $(".activity-expression-viewer", _thisObj.mainTemplateWindowId).offset().top - 65;
    
    $(window).scroll(function(){
        var scrollPos = offsetTopExpression - $(this).scrollTop();
        if(scrollPos <= 0) {
            var vWidth = $("#dataGridDiv", _thisObj.mainTemplateWindowId).children("div").width();
            $(".activity-expression-viewer", _thisObj.mainTemplateWindowId).addClass("activity-expression-viewer-class").css('width', vWidth+'px');
        } else
            $(".activity-expression-viewer", _thisObj.mainTemplateWindowId).removeClass("activity-expression-viewer-class").attr('style', 'background-color: #FFDEA5; padding: 6px 10px;');
    });    
    $(window).resize(function(){
        setTimeout(function(){
            var vSelector = $(".activity-expression-viewer", _thisObj.mainTemplateWindowId);
            if(vSelector.hasClass('activity-expression-viewer-class')) {
                vSelector.css('width', $("#dataGridDiv", _thisObj.mainTemplateWindowId).children("div").width()+'px');
            }
        }, 100);
    });
    $(document).on('focus', 'select.data-grid-activity-template-combo', function() {
        _thisObj.comboDataSet($(this));
    });
    $(document).on('change', 'select.data-grid-activity-template-combo', function() {
        var _this = $(this);
        var dgIndex = _thisObj.getRowIndex(_this);
        var rows = $(_thisObj.dataGridId, _thisObj.mainTemplateWindowId).datagrid('getRows');           
        var row = rows[dgIndex];
        var currField = _this.closest('td').attr('field');
        var dataRows = [];
        
        row[currField] = _this.val();
        dataRows.push(row);
        _thisObj.saveActivityAccountPartial(dataRows, 'template');
    });    
    
    $('.dg-custom-tooltip').tooltip();
};

Amactivity.prototype.loadDataGrid = function() {
    var _thisObj = this;

    $.ajax({
        type: 'POST',
        url: 'amactivity/getAllActivitySheetCtrl',
        data: { 
            activityKeyId: _thisObj.activityKeyId,
            periodId: _thisObj.periodId
        },
        dataType: "json",
        beforeSend: function() {
            var blockMsg='Түр хүлээнэ үү...';
            Core.blockUI({
                message: blockMsg,
                boxed: true
            });
        },        
        success: function(resp) {
            if(resp.status === 'success') {
                _thisObj.headerConfig = resp.getRows.headerConfig;
                var dgFirstHeaderData = resp.getRows.firstHeader;
                _thisObj.dgFirstHeaderDataGlobal = dgFirstHeaderData;
                var dgHeaderData = resp.getRows.header;
                _thisObj.headerDataGlobal = dgHeaderData;
                var headerLen = dgHeaderData.length;
                var roundVal = $("#roundValue", _thisObj.mainWindowId).val();
                
                for(var i = 0; i < headerLen; i++) {
                    if(dgHeaderData[i].attr === '0') {
                        
                        if(dgHeaderData[i].round === 'false') {
                                if(typeof _thisObj.headerConfig[dgHeaderData[i].field] !== 'undefined' && _thisObj.headerConfig[dgHeaderData[i].field].linkmetadataid !== '' && _thisObj.headerConfig[dgHeaderData[i].field].linkmetadataid != null)
                                    dgHeaderData[i].formatter = function(value, row, index){
                                        if(typeof value === 'undefined' || value === null)
                                            return '';
                                        return '<a href="javascript:;" onclick="amactivityObj.checkMetaDataTypeActivityFunction(\'' + _thisObj.headerConfig[dgHeaderData[i].field].linkmetadataid + '\', \''+encodeURIComponent(JSON.stringify(row))+'\')">' + pureNumberFormat(value) + '</a>';
                                    };
                                else
                                    dgHeaderData[i].formatter = function(value, row, index){
                                        if(typeof value === 'undefined' || value === null)
                                            return '';
                                        return pureNumberFormat(value);
                                    };
                        } else {
                            if(roundVal == '1') {
                                if(typeof _thisObj.headerConfig[dgHeaderData[i].field] !== 'undefined' && _thisObj.headerConfig[dgHeaderData[i].field].linkmetadataid !== '' && _thisObj.headerConfig[dgHeaderData[i].field].linkmetadataid != null) {
                                    _thisObj.counterGlobal = _thisObj.headerConfig[dgHeaderData[i].field].linkmetadataid;
                                    
                                    dgHeaderData[i].formatter = function(value, row, index){
                                        if(typeof value === 'undefined' || value === null)
                                            return '';
                                        return '<a href="javascript:;" onclick="amactivityObj.checkMetaDataTypeActivityFunction(\'' + _thisObj.counterGlobal + '\', \''+encodeURIComponent(JSON.stringify(row))+'\')">' + pureNumberFormat(value) + '</a>';
                                    };
                                } else
                                    dgHeaderData[i].formatter = function(value, row, index){
                                        if(typeof value === 'undefined' || value === null)
                                            return '';
                                        return pureNumberFormat(value);
                                    };
                            } else if(roundVal == '1000') {
                                if(typeof _thisObj.headerConfig[dgHeaderData[i].field] !== 'undefined' && _thisObj.headerConfig[dgHeaderData[i].field].linkmetadataid !== '' && _thisObj.headerConfig[dgHeaderData[i].field].linkmetadataid != null) {
                                    _thisObj.counterGlobal = _thisObj.headerConfig[dgHeaderData[i].field].linkmetadataid;
                                    
                                    dgHeaderData[i].formatter = function(value, row, index){
                                        if(typeof value === 'undefined' || value === null)
                                            return '';
                                        return '<a href="javascript:;" onclick="amactivityObj.checkMetaDataTypeActivityFunction(\'' + _thisObj.counterGlobal + '\', \''+encodeURIComponent(JSON.stringify(row))+'\')">' + pureNumberFormat(value / 1000) + '</a>';
                                    };
                                } else
                                    dgHeaderData[i].formatter = function(value, row, index){
                                        if(typeof value === 'undefined' || value === null)
                                            return '';
                                        return pureNumberFormat(value / 1000);                                    
                                    };                      
                            } else if(roundVal == '1000000')
                                dgHeaderData[i].formatter = dataGridFormatter_1000000;
                            else if(roundVal == '1000000000')
                                dgHeaderData[i].formatter = dataGridFormatter_1000000000;
                        }
                        dgHeaderData[i].styler = configedCellStyler;
                    }
                    
                    if(dgHeaderData[i].attr === '2') {
                        if(typeof _thisObj.headerConfig[dgHeaderData[i].field] !== 'undefined' && _thisObj.headerConfig[dgHeaderData[i].field].linkmetadataid !== '' && _thisObj.headerConfig[dgHeaderData[i].field].linkmetadataid != null) {
                            _thisObj.counterGlobal = _thisObj.headerConfig[dgHeaderData[i].field].linkmetadataid;

                            dgHeaderData[i].formatter = function(value, row, index){
                                if(typeof value === 'undefined' || value === null)
                                    return '';
                                return '<a href="javascript:;" onclick="amactivityObj.checkMetaDataTypeActivityFunction(\'' + _thisObj.counterGlobal + '\', \''+encodeURIComponent(JSON.stringify(row))+'\')"><span title="' + value + '" class="">' + value + '</span></a>';
                            };
                        } else
                            dgHeaderData[i].formatter = function(value, row, index){
                                if(typeof value === 'undefined' || value === null)
                                    return '';
                                return '<span title="' + value + '" class="">' + value + '</span>';                                
                            };    
                        dgHeaderData[i].styler = configedCellStyler;
                    }
                    
                    if(typeof _thisObj.headerConfig[dgHeaderData[i].field+'_code'] !== 'undefined' && _thisObj.headerConfig[dgHeaderData[i].field+'_code'].type === '3') {
                        dgHeaderData[i].editor.options.onSelect = function(record){
                            var dgIndex = _thisObj.getRowIndex($(this));
                            var rows = $(_thisObj.dataGridId, _thisObj.mainWindowId).datagrid('getRows');           
                            var row = rows[dgIndex];
                            var currField = $(this).closest('table').closest('td').attr('field');
                            var headerConfigVar = _thisObj.headerConfig[currField+'_code'];
                            var dataRows = [];  
                            var origField = headerConfigVar.id;
                            row[origField] = record.id;
                            dataRows.push(row);
                            _thisObj.saveActivityAccountPartial(dataRows, 'activity');
                        };
                        
                        var dtlKey = dgHeaderData[i].field+'_code';
                        if(dtlKey === 'countryid_code')
                            dgHeaderData[i].formatter = function(value, row){
                                return row.countryid_code;
                            };
                        else if(dtlKey === 'periodid_code')
                            dgHeaderData[i].formatter = function(value, row){
                                return row.periodid_code;
                            };
                        else if(dtlKey === 'measureid_code')
                            dgHeaderData[i].formatter = function(value, row){
                                return row.measureid_code;
                            };
                        else if(dtlKey === 'expenseaccountid_code')
                            dgHeaderData[i].formatter = function(value, row){
                                return row.expenseaccountid_code;
                            };
                        else if(typeof dtlKey !== 'undefined')
                            dgHeaderData[i].formatter = function(value, row){
                                return row[dtlKey];
                            };
                        
                        dgHeaderData[i].styler = configedCellStyler;
                    }
                    
                    if(typeof _thisObj.headerConfig[dgHeaderData[i].field] !== 'undefined' && _thisObj.headerConfig[dgHeaderData[i].field].expression !== null && _thisObj.headerConfig[dgHeaderData[i].field].expression !== '') {
                        dgHeaderData[i].styler = configedCellStyler;
                    }
                }
                
                resp.getRows.freeze[0].formatter = dataGridFormatterDescription;
                resp.getRows.freeze.push(resp.getRows.freeze[0]);
                resp.getRows.freeze[0] = {field: 'ck', checkbox: true};

                _thisObj.headerDataFreezeGlobal = resp.getRows.freeze;
                
                var tmpTr, tmpTr1;
                $(_thisObj.dataGridId).parents("div#dataGridDiv").html('<table class="no-border mt0" id="objectdatagrid_'+ _thisObj.activityKeyId+ '" style="width: 100%; height:600px"></table>');
                var DG = $(_thisObj.dataGridId, _thisObj.mainWindowId).datagrid({
                    nowrap: false,
                    data: resp.getRows.detail,
                    fit: false,
                    fitColumns: false,
                    rownumbers: true,
                    singleSelect: false,
                    ctrlSelect: true,
                    selectOnCheck: true,
                    checkOnSelect: true,
                    showFooter: true,
                    pagination: true,
                    pageList: [10,20,30,40,50,60,100,200],
                    pageSize: 200,
                    frozenColumns: [
                        _thisObj.headerDataFreezeGlobal
                    ],
                    columns: [dgFirstHeaderData, dgHeaderData],
                    onCheckAll: function() {
                        $.uniform.update();
                    },
                    onUncheckAll: function() {
                        $.uniform.update();
                    },  
                    onRowContextMenu: function (e, index, row) {
                        e.preventDefault();
                        $(this).datagrid('selectRow', index);
                        window.activityArgs = {
                            activityKeyId: _thisObj.activityKeyId,
                            contextRowValue: row
                        };                        
                        $.contextMenu({
                            selector: _thisObj.mainWindowId + " .datagrid .datagrid-view .datagrid-view2 .datagrid-body .datagrid-row, .datagrid .datagrid-view .datagrid-view1 .datagrid-body .datagrid-row",
                            items: {
                                "add": {
                                    name: "Нэмэх", 
                                    icon: "plus", 
                                    callback: function(key, options) {
                                        _thisObj.loadButtons(0);
                                    }
                                }, 
                                "addchild": {
                                    name: "Нэмэх (Child)", 
                                    icon: "plus", 
                                    callback: function(key, options) {
                                        _thisObj.loadButtons(1);
                                    }
                                }, 
                                "orderList": {
                                    name: "Дараалал тохируулах", 
                                    icon: "reorder", 
                                    callback: function(key, options) {
                                        _thisObj.reorderActivity('activity', activityArgs);
                                    }
                                }, 
                                "delete": {
                                    name: "Устгах", 
                                    icon: "trash", 
                                    callback: function(key, options) {
                                        _thisObj.deleteActivity('activity', 0);
                                    }
                                }, 
                                "deletechild": {
                                    name: "Устгах (Child)", 
                                    icon: "trash", 
                                    callback: function(key, options) {
                                        _thisObj.deleteActivity('activity', 1);
                                    }
                                },
                                "writecomment": {
                                    name: "Коммэнт", 
                                    icon: "comment", 
                                    callback: function(key, options) {
                                        _thisObj.commentActivity('activity', activityArgs);
                                    }
                                }
                            }
                        });
                        $.uniform.update();
                    },                                
                    onLoadSuccess: function(data) {
                        Core.unblockUI();
                        $('#dataGridDiv', _thisObj.mainWindowId).find('.datagrid-view .datagrid-view2 .datagrid-body').find('.datagrid-btable tbody tr').each(function(key, val){
                            $(this).find('td').each(function(){
                                var _this = $(this), field = _this.attr('field') + '_formula';
                                
                                if(typeof data.rows[key][field] !== 'undefined') {
                                    if(data.rows[key][field] !== '' || data.rows[key][field] !== null)
                                        _this.css('background-color', '#FFDEA5');
                                }
                            });
                        });
                        
                        $('#dataGridDiv tbody', _thisObj.mainWindowId).on('keydown', 'input', function(e) {
                            var key = e.which;
                            _thisObj.keyPressEvents(this, key);
                            Core.initUniform('#dataGridDiv tbody', _thisObj.mainWindowId);
                        });              
                        
                        var vWidth = $("#dataGridDiv", _thisObj.mainWindowId).children("div").width();
                        $(".activity-expression-viewer", _thisObj.mainWindowId).css('width', vWidth+'px');
                        Core.initUniform((_thisObj.dataGridId, _thisObj.mainWindowId));
                        
                        $.each(data.rows, function(key, row){
                            tmpTr = $(_thisObj.dataGridId).parents(".datagrid-view").find(".datagrid-view1 .datagrid-btable tbody tr").eq(key);
                            tmpTr1 = $(_thisObj.dataGridId).parents(".datagrid-view").find(".datagrid-view2 .datagrid-btable tbody tr").eq(key);
                            if(row.parentid !== null && (typeof $defaultOpen === 'undefined')) {
                                tmpTr.hide();
                                tmpTr1.hide();
                            }
                            
                            initEventOpenClose($(_thisObj.dataGridId), tmpTr.find(".tree-hit"), key);
                            if (typeof $defaultOpen !== 'undefined') {
                                tmpTr.find(".tree-hit").trigger('click');
                            }
                        });

                        _thisObj.dgFirstHeaderDataGlobal.push({
                            field: '',
                            title: '',
                            width: '50'
                        });
                        _thisObj.headerDataGlobal.push({
                            field: 'action',
                            title: '',
                            width: '50',
                            align: 'center'
                        });
                        $('#commonSelectableBasketDataGrid', _thisObj.mainWindowId).datagrid({
                            url:'',
                            rownumbers:true,
                            singleSelect:true,
                            pagination:false,
                            remoteSort:false,
                            height:600,
                            fitColumn:true,
                            showFooter:false,
                            frozenColumns: [
                                _thisObj.headerDataFreezeGlobal
                            ],
                            columns: [_thisObj.dgFirstHeaderDataGlobal, _thisObj.headerDataGlobal],
                            onLoadSuccess:function(data) {
                                var _thisGrid = $(this);
                                _thisGrid.promise().done(function() {
                                    _thisGrid.datagrid('resize');
                                });
                            }
                        });                        
                    },
                    onClickRow: function(index, row) {
                        $.uniform.update();
                    },
                    onClickCell: function(index, field, value) {
                        $.uniform.update();
                        var _thisDG = $(this);              
                        
                        _thisObj.activeField = field;
                        _thisObj.activeIndex = index;
                        var rows = _thisDG.datagrid('getRows');
                        var ed = _thisDG.datagrid('getEditor', {index: index, field: field});                    
                        var headerConfigVar = typeof _thisObj.headerConfig[field] !== 'undefined' ? _thisObj.headerConfig[field] : '';
                        
                        $(".activity-expression-viewer", _thisObj.mainWindowId).find('span').empty();
                        if(headerConfigVar.type === '1' && headerConfigVar.metadataid !== '') {
                            var _thisInput = $(ed.target).parent().find("span input[type='text']");                            
                            
                            _thisInput.autocomplete({
                                minLength: 1,
                                maxShowItems: 10,
                                delay: 500,
                                highlightClass: "lookup-ac-highlight", 
                                appendTo: "body",
                                position: {my : "left top", at: "left bottom", collision: "flip flip"}, 
                                autoFocus: true,
                                source: function(request, response) {                                    
                                    $.ajax({
                                        type: 'post',
                                        url: 'mdcommon/hardWindowAutoComplete',
                                        dataType: "json",
                                        data: {
                                            q: request.term,
                                            type: 'code',
                                            metaDataId: headerConfigVar.metadataid
                                        },
                                        success: function(data) {
                                            response($.map(data, function(item) {
                                                return {
                                                    label: item.code,
                                                    name: item.name,
                                                    data: item
                                                };
                                            }));
                                        }
                                    });
                                },
                                focus: function() {
                                    return false;
                                },
                                open: function() {
                                    $(this).autocomplete('widget').zIndex(99999999999999);
                                    return false;
                                },
                                close: function (event, ui){
                                    $(this).autocomplete("option","appendTo","body"); 
                                }, 
                                select: function(event, ui) {
                                    var data = ui.item.data;
                                    var dataRows = [];
                                    var selectedRowCell = $(_thisObj.dataGridId, _thisObj.mainWindowId).datagrid('cell');
                                    var getRowsPage = $(_thisObj.dataGridId, _thisObj.mainWindowId).datagrid('getRows');    
                                    var selectedRow = getRowsPage[selectedRowCell.index];        
                                    var origField = headerConfigVar.id;
                                    
                                    selectedRow[origField] = data.id;
                                    dataRows.push(selectedRow);
                                    _thisObj.saveActivityAccountPartial(dataRows, 'activity');
                                    _thisInput.val("");          
                                    return false;                    
                                }
                            }).autocomplete("instance")._renderItem = function(ul, item) {
                                ul.addClass('lookup-ac-render');

                                var re = new RegExp("(" + this.term + ")", "gi"),
                                    cls = this.options.highlightClass,
                                    template = "<span class='" + cls + "'>$1</span>",
                                    label = item.label.replace(re, template);

                                return $('<li>').append('<div class="lookup-ac-render-code">'+label+'</div><div class="lookup-ac-render-name">'+item.name+'</div>').appendTo(ul);
                            };  
                            
                        } else if((headerConfigVar.type === '0' || headerConfigVar.type === '2') && headerConfigVar.isattribute === 'false') {
                            
                            if(rows[index].haschild === '1' || (rows[index][field+'_formula'] !== undefined && rows[index][field+'_formula'] !== null && rows[index][field+'_formula'] !== '')) {
                                $(ed.target).numberbox('readonly');
                                $(".activity-expression-viewer", _thisObj.mainWindowId).find('span').append(rows[index][field+'_formula']);                                
                            } 
                            if(headerConfigVar.expression !== '' && headerConfigVar.expression !== null) {
                                $(".activity-expression-viewer", _thisObj.mainWindowId).find('span').empty();
                                $(".activity-expression-viewer", _thisObj.mainWindowId).find('span').append(headerConfigVar.expression);
                            }                            
                            
                            if (headerConfigVar.type === '0' && rows[index].haschild !== '1') {
//                                if(rows[index].haschild === '1' || (rows[index][field+'_formula'] !== undefined && rows[index][field+'_formula'] !== null && rows[index][field+'_formula'] !== '')) {
//                                    $(ed.target).parent().find('span').find('input[type="text"]').css('width', '100%');
//                                } else {
                                    $(ed.target).parent().find('span').find('input[type="text"]').css('width', '121px');
                                    $(ed.target).parent().find('span').append('<a class="btn btn-warning btn-sm" title="Томъёо оруулах" onclick="amactivityObj.insertFormExpression(this);" href="javascript:;" style="width: 25px;border-radius: 0px;padding-left: 6px;"><i class="fa fa-calculator"></i></a>');
//                                }
                            } else {
                                $(ed.target).parent().find('span').find('input[type="text"]').css('text-align', 'right');
                            }
                        }
                        Core.initUniform((_thisObj.dataGridId, _thisObj.mainWindowId));
                    },
                    onSelectCell: function(index, field, value) {
                        $.uniform.update();
                        Core.initUniform((_thisObj.dataGridId, _thisObj.mainWindowId));
                    },
                    onAfterEdit: function(index,row,changes){
                      tmpTr = $(_thisObj.dataGridId).parents(".datagrid-view").find(".datagrid-view1 .datagrid-btable tbody tr").eq(index);
                      initEventOpenClose($(_thisObj.dataGridId), tmpTr.find(".tree-hit"), index);
                    },
                    onDblClickRow: function(index, row) {
                        // if ($.cookie) {
                        //     var keyId = currTr.find('input[name="keyId[]"]').val();
                        //     var bookNumber = currTr.find('input[name="bookNumberRow[]"]').val();
                        //     $.cookie('BILL_RATE_' + keyId + '_' + bookNumber, moreNewRate);
                        // }    
                        row.action = '<a href="javascript:;" onclick="amactivityObj.deleteCommonSelectableBasket(this);" class="btn btn-xs red" title="Устгах"><i class="fa fa-trash"></i></a>';
                        $('#commonSelectableBasketDataGrid', _thisObj.mainWindowId).datagrid('appendRow', row);                        
                        $("#commonSelectedCount", _thisObj.mainWindowId).stop().css('opacity', '0').html(function (_, oldText) {
                            return $('#commonSelectableBasketDataGrid', _thisObj.mainWindowId).datagrid('getData').total;
                        }).animate({
                            opacity: 1,
                            fontWeight: "600",
                        }, 500);
                    }                    
//                    onEndEdit: function(index, row, changes){
//                        // Дансаа цэвэрлээд хадгалах үед ажиллана.
//                        if(event.which === 13) {
//                            if(typeof changes[_thisObj.activeField] !== 'undefined') {
//                                if(changes[_thisObj.activeField] === '') {
//                                    var headerConfigVar = _thisObj.headerConfig[_thisObj.activeField];
//                                    var dataRows = [];  
//                                    var origField = headerConfigVar.id;
//                                    row[origField] = null;
//                                    dataRows.push(row);
//                                    _thisObj.saveActivityAccountPartial(dataRows, 'activity');
//                                }
//                            }
//                        }
//                    }
                });
                DG.datagrid('enableFilter');
                DG.datagrid('enableCellEditing');                 
                
            } else {
                Core.unblockUI();
                PNotify.removeAll();
                new PNotify({
                  title: 'Warning',
                  text: 'Энэ арга хэмжээнд өгөгдөл байхгүй байна!',
                  type: 'warning',
                  sticker: false
                });                
            }
        },
        error: function(){
            alert("Ajax Error!");
        }
    });
};

Amactivity.prototype.loadButtons = function(isWithChild) {
    var _thisObj = this;
    var selectedRowCell = $(_thisObj.dataGridId, _thisObj.mainWindowId).datagrid('cell');
    var getRowsPage = $(_thisObj.dataGridId, _thisObj.mainWindowId).datagrid('getRows');    
    var maxDimValue = Number($("#maxDimension", _thisObj.mainWindowId).val()),
        minDimValue = Number($("#minDimension", _thisObj.mainWindowId).val()),
        levelNum = minDimValue,
        currentLevelNum = 'NOT_FOUND';
    activityIsWithChild = isWithChild;
    var selectedRow = [];
    
    if(selectedRowCell) {
        var selectedRow = getRowsPage[selectedRowCell.index];
        levelNum = Number(selectedRow.levelnum);
        if(levelNum >= maxDimValue)
            levelNum = maxDimValue;
        else {
            currentLevelNum = levelNum;
            levelNum++;
        }
    }
    
    $.ajax({
        type: 'POST',
        url: 'amactivity/getBtnActivityCtrl',
        data: { 
//            levelNum: levelNum, 
//            currentLevelNum: currentLevelNum, 
            activityKeyId: _thisObj.activityKeyId,
            selectedRow: selectedRow
        },
        dataType: "json",
        success: function(resp) {
            if (resp.status === 'success') {
//                if(resp.getRow.length > 1 && minDimValue != levelNum && maxDimValue != levelNum)
                if (resp.getRow.length > 1)
                    _thisObj.dimensionDVlist(resp.getRow, 'activity');
                else
                    dataViewCustomSelectableGrid(resp.getRow[0].META_DATA_CODE, 'multi', 'selectedActivityFunction', '', resp.getRow[0].CODE+'_'+resp.getRow[0].FIELD_PATH+'_'+resp.getRow[0].FIELD_PATH_CODE, '');
            } else {
                PNotify.removeAll();
                new PNotify({
                    title: 'Warning',
                    text: 'Төлөвлөлтийн үзүүлэлт тохируулна уу!',
                    type: 'warning',
                    sticker: false
                });       
            }
        },
        error: function(){
            alert("Failed Ajax!");
        }
    });
};

Amactivity.prototype.calculateExpression = function() {
    var _thisObj = this;
    $(_thisObj.dataGridId, _thisObj.mainWindowId).datagrid('editCell', {
        index: 0,
        field: 'description'
    });    
    var postData = $(_thisObj.dataGridId, _thisObj.mainWindowId).datagrid('getRows'), semTableName = '', semIds = [];
    
    $('ul.metas-div', _thisObj.mainWindowId).children().each(function(){
        var _this = $(this);
        semTableName = _this.find('i').attr('data-tablename');
        semIds.push(_this.find('i').attr('data-item-id'));
    });
    
    var _params = {id: _thisObj.activityKeyId};
    var _postData = {param: _params, methodId: '1490149575193', processSubType: 'external', create: '0', inputMetaDataId: '1461741942216', responseType: '', wfmStatusParams: '', wfmStringRowParams:'', isSystemProcess: 'false', dmMetaDataId: '1458997624847', cyphertext: '', plainText: '', realSourceIdAutoMap: '1485154889326_1458997624847'};
    
    $("#activityInfoForm", _thisObj.mainWindowId).ajaxSubmit({
        type: 'POST',
        url: 'amactivity/actionActivitySheetCalculate',
        data: {
            request: postData, 
            activityKeyId: _thisObj.activityKeyId,
            periodId: _thisObj.periodId,
            semantic: {
                tableName: semTableName,
                semIds: JSON.stringify(semIds),
            }
        },
        dataType: "json",
        beforeSend: function() {
            Core.blockUI( {
                message: 'Бодож байна, Түр хүлээнэ үү...',
                boxed: true
            });
        },
        success: function(data) {
            PNotify.removeAll();
            if(data.status === 'success') {
                _thisObj.loadDataGrid();
                $.ajax({
                    type: 'post',
                    data: _postData,
                    url: 'mdwebservice/runProcess',
                    dataType: 'json',
                    beforeSend: function () {
                        Core.blockUI({
                            boxed: true, 
                            message: 'Түр хүлээнэ үү'
                        });
                    },
                    success: function (responseData) {
                    },
                    error: function () {
                        alert("Error");
                    }
                });
            }
            
            Core.unblockUI();
            new PNotify({
                type: data.status,
                title: data.title,
                text: data.text,
                sticker: false
            });
        }
    });
};

Amactivity.prototype.saveActivity = function() {
    var _thisObj = this;

    $.ajax({
        type: 'POST',
        url: 'amactivity/saveActivityCtrl',
        data: { 
            activityKeyId: _thisObj.activityKeyId
        },
        dataType: "json",
        success: function(resp) {
            if(resp.status === 'success') {
                if(Number(resp.result) !== 0) 
                    _thisObj.saveActivityConfirmDialog(_thisObj.activityKeyId, resp.result);
                else {
                    PNotify.removeAll();
                    new PNotify({
                        type: resp.status,
                        title: resp.title,
                        text: resp.text,
                        sticker: false
                    });
                }
            }
        },
        error: function(){
            alert("Failed Ajax!");
        }
    });
};

Amactivity.prototype.saveActivityAccountPartial = function(postData, reloadType) {
    var _thisObj = this;

    $.ajax({
        type: 'POST',
        url: 'amactivity/saveActivityAccountCtrl',
        data: { 
            request: postData
        },
        dataType: "json",
        success: function(resp) {
            PNotify.removeAll();
            new PNotify({
                type: resp.status,
                title: resp.title,
                text: resp.text,
                sticker: false
            });
            
            if(resp.status === 'success') {
                if(reloadType === 'noreload')
                    return;
                
                if(reloadType === 'template')
                    _thisObj.loadDataGridTemplate();
                else
                    _thisObj.loadDataGrid();
            }            
        },
        error: function(){
            alert("Failed Ajax!");
        }
    });
};

Amactivity.prototype.keyPressEvents = function(element, keyCode) {
    var tr = $(element).closest('table').closest('tr');
    var td = $(element).closest('table').closest('td');
    
    switch (keyCode) {
        case 40:
            this.nextTR(tr, td);
            break;
        case 38:
            this.prevTR(tr, td)   
            break;
        /*case 13:
//            var rows = $(this.dataGridId, this.mainWindowId).datagrid('getRows');
//            var trIndex = tr.index();
//            
//            var activityArgs = {
//                activityKeyId: this.activityKeyId,
//                contextRowValue: rows[trIndex]
//            };      
//            Amactivity.prototype.commentActivity('activity', activityArgs, this);
            
            PNotify.removeAll();
            new PNotify({
                title: 'Warning',
                text: 'Өөрчлөлт оруулсан бол нүдээ идэвхжүүлэн коммэнт бичнэ үү?',
                type: 'warning',
                sticker: false
            }); 
            break;*/
        case 39:
            this.nextTR(tr, td);
            break;
            
            tr = tr.index();
            td = td.next('td').attr("field");
            if(td === undefined)
                break;
            
            this.activeField = td;
            $(this.dataGridId, this.mainWindowId).datagrid('editCell', {
                index: tr,
                field: td
            });
            
            /*
            var ed = $(this.dataGridId, this.mainWindowId).datagrid('getEditor', {index: tr, field: td});
            console.log($(ed.target).closest('table').closest('td').attr('field'));*/
            
            var headerConfigVar = typeof this.headerConfig[td] !== 'undefined' ? this.headerConfig[td] : '';
            
            if ((headerConfigVar.type === '0' || headerConfigVar.type === '2') && headerConfigVar.isattribute === 'false') {
                var ed = $(this.dataGridId, this.mainWindowId).datagrid('getEditor', {index: tr, field: td});
                
                $(".activity-expression-viewer", this.mainWindowId).find('span').empty();
                var rows = $(this.dataGridId, this.mainWindowId).datagrid('getRows');
                
                if(rows[tr].haschild === '1' || (rows[tr][td+'_formula'] !== undefined && rows[tr][td+'_formula'] !== null && rows[tr][td+'_formula'] !== '')) {
                    $(ed.target).numberbox('readonly');
                    $(".activity-expression-viewer", this.mainWindowId).find('span').append(rows[tr][td+'_formula']);
                }
                
                if (headerConfigVar.type === '0' && rows[tr].haschild !== '1') {
                    if(rows[tr].haschild === '1' || (rows[tr][td+'_formula'] !== undefined && rows[tr][td+'_formula'] !== null && rows[tr][td+'_formula'] !== '')) {
                        $(ed.target).parent().find('span').find('input[type="text"]').css('width', '100%');
                    } else {
                        $(ed.target).parent().find('span').find('input[type="text"]').css('width', '121px');
                        $(ed.target).parent().find('span').append('<a class="btn btn-warning btn-sm" title="Томъёо оруулах" onclick="amactivityObj.insertFormExpression(this);" href="javascript:;" style="width: 25px;border-radius: 0px;padding-left: 6px;"><i class="fa fa-calculator"></i></a>');            
                    }
                    
                } else {
                    $(ed.target).parent().find('span').find('input[type="text"]').css('text-align', 'right');
                }

                
            }
            break;
        case 37:
            var tr = $(element).closest('table').closest('tr').index();
            var td = $(element).closest('table').closest('td').prev('td').attr("field");
            if(td === undefined)
                break;            
            this.activeField = td;
            $(this.dataGridId, this.mainWindowId).datagrid('editCell', {
                index: tr,
                field: td
            });
            
            var headerConfigVar = typeof this.headerConfig[td] !== 'undefined' ? this.headerConfig[td] : '';
            if((headerConfigVar.type === '0' || headerConfigVar.type === '2') && headerConfigVar.isattribute === 'false') {
                var ed = $(this.dataGridId, this.mainWindowId).datagrid('getEditor', {index: tr, field: td});
                
                $(".activity-expression-viewer", this.mainWindowId).find('span').empty();
                var rows = $(this.dataGridId, this.mainWindowId).datagrid('getRows');

                
                if(rows[tr].haschild === '1' || (rows[tr][td+'_formula'] !== undefined && rows[tr][td+'_formula'] !== null && rows[tr][td+'_formula'] !== '')) {
                    $(ed.target).numberbox('readonly');
                    $(".activity-expression-viewer", this.mainWindowId).find('span').append(rows[tr][td+'_formula']);
                }               
                if (headerConfigVar.type === '0' && rows[tr].haschild !== '1') {
                    if(rows[tr].haschild === '1' || (rows[tr][td+'_formula'] !== undefined && rows[tr][td+'_formula'] !== null && rows[tr][td+'_formula'] !== '')) {
                        $(ed.target).parent().find('span').find('input[type="text"]').css('width', '100%');
                    }
                    else {
                        $(ed.target).parent().find('span').find('input[type="text"]').css('width', '121px');
                        $(ed.target).parent().find('span').append('<a class="btn btn-warning btn-sm" title="Томъёо оруулах" onclick="amactivityObj.insertFormExpression(this);" href="javascript:;" style="width: 25px;border-radius: 0px;padding-left: 6px;"><i class="fa fa-calculator"></i></a>');     
                    }
                } else {
                    $(ed.target).parent().find('span').find('input[type="text"]').css('text-align', 'right');
                }
            }
            break;
        default: 
            break;
    }
};

Amactivity.prototype.keyPressEventsTemplate = function(element, keyCode) {
    switch (keyCode) {
        case 40:
            var td = $(element).closest('tr').next('tr');
            var position = $(element).closest('td').index();
            var input = td.find("td:eq("+position+")").find("input");
            input.focus();
            input.select();
            input.trigger('click');
            break;
        case 38:
            var td = $(element).closest('tr').prev('tr');
            var position = $(element).closest('td').index();
            var input = td.find("td:eq("+position+")").find("input");
            input.focus();
            input.select();
            input.trigger('click');
            break;
        default: 
            break;
    }
};

Amactivity.prototype.saveActivityConfirmDialog = function(activityKey, amount) {
    var $dialogName = 'dialog-config-activity';
    if (!$($dialogName).length) {
        $('<div id="' + $dialogName + '"></div>').appendTo('body');
    }

    $("#" + $dialogName).empty().html("Дүн зөрүүтэй байна.<br> Тэнцүүлэхдээ итгэлтэй байна уу?");
    $("#" + $dialogName).dialog({
        appendTo: "body",
        cache: false,
        resizable: true,
        bgiframe: true,
        autoOpen: false,
        title: "Сануулга",
        width: 450,
        height: 'auto',
        modal: true,
        close: function(){
            $("#" + $dialogName).empty().dialog('destroy').remove();
        },                        
        buttons: [
            {text: 'Тийм', class: 'btn btn-sm blue', click: function() {                            
                $.ajax({
                    type: 'post',
                    url: 'amactivity/actionActivitySheetNotZeroCtrl',
                    data: { activityKeyId: activityKey, budgetAmount: amount },
                    dataType: "json",
                    success: function() {           
                    },
                    error: function() {
                        alert("Error");
                    }                            
                });
                $("#" + $dialogName).dialog('close');
            }},
            {text: 'Үгүй', class: 'btn btn-sm blue-hoki', click: function() {
                $("#" + $dialogName).dialog('close');
            }}
        ]
    });
    $("#" + $dialogName).dialog('open');
};

Amactivity.prototype.saveActivityPartial = function(metaDataCode, insertRow, selectedRow) {
    var _thisObj = this;
    var params = { 
        request: insertRow,
        activityKeyId: _thisObj.activityKeyId, 
        periodId: this.periodId, 
        metaDataCode: metaDataCode, 
        isWithChild: activityIsWithChild 
    };
    if (selectedRow.length != 0) {
        params['selectedRow'] = selectedRow;
    }
    $.ajax({
        type: 'POST',
        url: 'amactivity/saveActivityPartialCtrl',
        data: params,
        dataType: "json",
        beforeSend: function() {
            _thisObj.calculateExpression();
        },
        success: function(resp) {
            if(resp.status === 'success')
                _thisObj.loadDataGrid();

            PNotify.removeAll();
            new PNotify({
                type: resp.status,
                title: resp.title,
                text: resp.text,
                sticker: false
            });                
        },
        error: function() {
            alert("Failed Ajax Save Row Activity!");
        }
    });
};

Amactivity.prototype.saveActivityTemplatePartial = function(metaDataCode, insertRow, selectedRow) {
    var _thisObj = this;
    $.ajax({
        type: 'POST',
        url: 'amactivity/saveActivityTemplatePartialCtrl',
        data: { 
            request: insertRow,
            selectedRow: selectedRow, 
            metaDataCode: metaDataCode,
            activityKeyId: _thisObj.activityKeyId
        },
        dataType: "json",
        success: function(resp) {
            if(resp.status === 'success')
                _thisObj.loadDataGridTemplate();

            PNotify.removeAll();
            new PNotify({
                type: resp.status,
                title: resp.title,
                text: resp.text,
                sticker: false
            });                
        },
        error: function(){
            alert("Failed Ajax Save Row Activity Template!");
        }
    });
};

Amactivity.prototype.loadPeriod = function(row) {
    var _thisObj = this;    
    if(typeof row === 'object') {
        $("#activityInfoForm", _thisObj.mainWindowId).find("#activityKeyId_valueField").val(row.id);
        $("#activityInfoForm", _thisObj.mainWindowId).find("#activityKeyId_displayField").val(row.activitycode);
        $("#activityInfoForm", _thisObj.mainWindowId).find("#activityKeyId_nameField").text(row.description);    
    }

    $.ajax({
        type: 'POST',
        url: 'amactivity/getPeriodActivityCtrl',
        data: {
            activityKeyId: (typeof row === 'object' ? row.id : row)
        },
        dataType: "json",
        success: function(resp) {
            if(resp.status === 'success') {
                if(resp.getRows.period) {
                    var comboSelect = $("#periodId", _thisObj.mainWindowId);
                    if(comboSelect.parent().find("input:hidden").length)
                        comboSelect.parent().find("input:hidden").remove();
                    comboSelect.parent().append("<input type='hidden' id='maxDimension' value='"+resp.getRows.maxdimension+"'>");
                    comboSelect.parent().append("<input type='hidden' id='minDimension' value='"+resp.getRows.mindimension+"'>");

                    comboSelect.empty();
                    $.each(resp.getRows.period, function(key, value){
                        comboSelect.append($("<option/>", {
                            value: key,
                            text: value
                        }));
                    });
                    comboSelect.closest("#activityPeriodSelect").removeClass("hide");
                } else {
                    PNotify.removeAll();
                    new PNotify({
                        title: 'Warning',
                        text: 'Period тохируулаагүй байна!',
                        type: 'warning',
                        sticker: false
                    });                      
                }
            } else 
                alert("Period Not Found!!!");
        },
        error: function(){
            alert("Failed Ajax!");
        }
    });
};

Amactivity.prototype.insertActivityRow = function(metaDataCode, rows, code) {
    var _thisObj = this, code = code.split('_');
    var fieldCode = code[0], fieldPath = code[1].toLowerCase(), fieldPathCode = code[2].toLowerCase();
    
    var selectedRowCell = $(_thisObj.dataGridId, _thisObj.mainWindowId).datagrid('cell');
    if (selectedRowCell === null) {
        selectedRow = {};
    } else {
        /* var getRowsPage = $(_thisObj.dataGridId, _thisObj.mainWindowId).datagrid('getRows'); */
        var selectedRow = $(_thisObj.dataGridId, _thisObj.mainWindowId).datagrid('getSelected');    /*getRowsPage[selectedRowCell.index];*/
        if (selectedRow == null) {
            selectedRow = {};
        }
    }
    var insertRow = [];

    if (fieldPathCode !== 'hide') {
        $.each(rows, function(key, value) {
            insertRow.push({
                description: value[fieldPathCode] + ' - ' + value[fieldPath],
                fieldCode: fieldCode,
                fieldVal: value.id
            });
        });
    } else {
        $.each(rows, function(key, value) {
            insertRow.push({
                description: value[fieldPath],
                fieldCode: fieldCode,
                fieldVal: value.id
            });
        });
    }

    _thisObj.saveActivityPartial(metaDataCode, insertRow, selectedRow);
};

Amactivity.prototype.insertActivityRowTemplate = function(metaDataCode, rows, code) {
    var _thisObj = this, code = code.split('_');
    var fieldCode = code[0], fieldPath = code[1].toLowerCase(), fieldPathCode = code[2].toLowerCase();
    var selectedRow = $(_thisObj.dataGridId, _thisObj.mainTemplateWindowId).datagrid('getSelected');
    if (selectedRow === null) {
        selectedRow = '';
    }
    var insertRow = [];

    if(fieldPathCode !== 'hide') {
        $.each(rows, function(key, value) {
            insertRow.push({
                description: value[fieldPathCode] + ' - ' + value[fieldPath],
                fieldCode: fieldCode,
                fieldVal: value.id
            });
        });
    } else {
        $.each(rows, function(key, value) {
            insertRow.push({
                description: value[fieldPath],
                fieldCode: fieldCode,
                fieldVal: value.id
            });
        });
    }

    _thisObj.saveActivityTemplatePartial(metaDataCode, insertRow, selectedRow);
};

Amactivity.prototype.reorderActivity = function(requestType, activityArgs) {
    var _thisObj = this;
    var $dialogName = 'dialog-reorder-list';
    if (!$($dialogName).length) {
        $('<div id="' + $dialogName + '"></div>').appendTo("body");
    }

    $.ajax({
        type: 'post',
        url: 'amactivity/reorderActivityCtrl',
        data: {
            activityKeyId: activityArgs.activityKeyId,
            periodId: _thisObj.periodId,
            id: activityArgs.contextRowValue.id,
            parentId: activityArgs.contextRowValue.parentid,
            requestType: requestType
        },
        dataType: "json",
        beforeSend: function() {
            var blockMsg='Түр хүлээнэ үү...';
            Core.blockUI({
                message: blockMsg,
                boxed: true
            });
        },               
        success: function(data) {
            $("#" + $dialogName).empty().html(data.html);
            $("#" + $dialogName).dialog({
                appendTo: "body",
                cache: false,
                resizable: true,
                bgiframe: true,
                autoOpen: false,
                title: data.title,
                width: 560,
                height: 'auto',
                modal: true,
                close: function(){
                    $("#" + $dialogName).empty().dialog('destroy').remove();
                },                        
                buttons: [
                    {text: data.save_btn, class: 'btn btn-sm blue', click: function() {
                        var reorderRow = $("#reorderActivityList tbody tr.selected");
                        if(reorderRow.length === 0) {
                            PNotify.removeAll();
                            new PNotify({
                              title: 'Warning',
                              text: 'Тохируулах мөрөө сонгоно уу!',
                              type: 'warning',
                              sticker: false
                            });                              
                            return;
                        }
                        $.ajax({
                            type: 'post',
                            url: 'amactivity/reorderActivitySaveCtrl',
                            data: {
                                activityKeyId: activityArgs.activityKeyId,
                                selectedId: activityArgs.contextRowValue.id,
                                changeId: reorderRow.attr("id"),
                                parentId: activityArgs.contextRowValue.parentid,
                                requestType: requestType
                            },                 
                            dataType: "json",
                            success: function(resp) {
                                if(resp.status === 'success') {
                                    if(requestType === 'template')
                                        _thisObj.loadDataGridTemplate();
                                    else
                                        _thisObj.loadDataGrid();
                                }
                                new PNotify({
                                    type: resp.status,
                                    title: resp.title,
                                    text: resp.text,
                                    sticker: false
                                });                                
                            },
                            error: function() {
                                alert("Error");
                            }
                        });                            
                        $("#" + $dialogName).dialog('close');
                    }},
                    {text: data.close_btn, class: 'btn btn-sm blue-hoki', click: function() {
                        $("#" + $dialogName).dialog('close');
                    }}
                ]
            });
            $("#" + $dialogName).dialog('open');
            Core.unblockUI();
        },
        error: function() {
            alert("Error");
        }
    });
};

Amactivity.prototype.dimensionDVlist = function(data, type) {
    var _thisObj = this;
    var $dialogName = 'dialog-dimensiondv-list';
    if (!$($dialogName).length) {
        $('<div id="' + $dialogName + '"></div>').appendTo("body");
    }

    $.ajax({
        type: 'post',
        url: 'amactivity/dimensionDVCtrl',
        data: {
            sendRows: data
        },
        dataType: "json",
        beforeSend: function() {
            var blockMsg='Түр хүлээнэ үү...';
            Core.blockUI({
                message: blockMsg,
                boxed: true
            });
        },          
        success: function(data) {
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
                close: function(){
                    $("#" + $dialogName).empty().dialog('destroy').remove();
                },                        
                buttons: [
                    {text: data.choose_btn, class: 'btn btn-sm blue', click: function() {
                        var reorderRow = $("#reorderActivityList tbody tr.selected", "body");
                        if(reorderRow.length === 0) {
                            PNotify.removeAll();
                            new PNotify({
                              title: 'Warning',
                              text: 'Мөрөө сонгоно уу!',
                              type: 'warning',
                              sticker: false
                            });                              
                            return;
                        }                  
                        var funcName = type === 'template' ? 'selectedActivityTemplateFunction' : 'selectedActivityFunction';
                        dataViewCustomSelectableGrid(reorderRow.attr("data-metadatacode"), 'multi', funcName, '', reorderRow.attr("id"), '', '', reorderRow.attr('data-criteria'));
                        $("#" + $dialogName).dialog('close');
                    }},
                    {text: data.close_btn, class: 'btn btn-sm blue-hoki', click: function() {
                        $("#" + $dialogName).dialog('close');
                    }}
                ]
            });
            $("#" + $dialogName).dialog('open');
            Core.unblockUI();
        },
        error: function() {
            alert("Error");
        }
    });
};

Amactivity.prototype.dimensionDimlist = function(data, type) {
    var _thisObj = this;
    var $dialogName = 'dialog-dimensiondim-list';
    if (!$($dialogName).length) {
        $('<div id="' + $dialogName + '"></div>').appendTo("body");
    }

    $.ajax({
        type: 'post',
        url: 'amactivity/dimensionDimCtrl',
        data: {
            sendRows: data
        },
        dataType: "json",
        beforeSend: function() {
            var blockMsg='Түр хүлээнэ үү...';
            Core.blockUI({
                message: blockMsg,
                boxed: true
            });
        },          
        success: function(data) {
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
                close: function(){
                    $("#" + $dialogName).empty().dialog('destroy').remove();
                },                        
                buttons: [
                    {text: data.choose_btn, class: 'btn btn-sm blue', click: function() {
                        var serData = $("#dimInputData > tbody > tr"), insertRow = {}, iArr = [];
                        
                        serData.each(function() {
                            var _t = $(this);
                            
                            if(_t.find('textarea').length) {
                                insertRow['description'] = _t.find('textarea').val();
                                insertRow['dimOrder'] = _t.find('input').val();
                            } else {
                                insertRow[_t.find('input').attr('name')] = _t.find('input').val();
                            }
                        });
                        iArr.push(insertRow);
                        
                        var selectedRow = $(_thisObj.dataGridId, _thisObj.mainTemplateWindowId).datagrid('getSelected');
                        if (selectedRow === null) {
                            selectedRow = '';
                        }                        
                        
                        _thisObj.saveActivityTemplatePartial('', iArr, selectedRow);
                        $("#" + $dialogName).dialog('close');
                    }},
                    {text: data.close_btn, class: 'btn btn-sm blue-hoki', click: function() {
                        $("#" + $dialogName).dialog('close');
                    }}
                ]
            });
            $("#" + $dialogName).dialog('open');
            Core.unblockUI();
        },
        error: function() {
            alert("Error");
        }
    });
};

Amactivity.prototype.loadDataGridTemplate = function() {
    var _thisObj = this;        
    $.ajax({
        type: 'POST',
        url: 'amactivity/getAllActivityTemplateCtrl',
        data: { 
            templateId: _thisObj.activityKeyId
        },
        dataType: "json",
        beforeSend: function() {
            var blockMsg='Түр хүлээнэ үү...';
            Core.blockUI({
                message: blockMsg,
                boxed: true
            });
        },        
        success: function(resp) {
            if(resp.status === 'success') {
                _thisObj.headerConfig = resp.getRows.headerConfig;
                var dgFirstHeaderData = resp.getRows.firstHeader;
                var dgHeaderData = resp.getRows.header;
                _thisObj.headerDataGlobal = dgHeaderData;
                var headerLen = dgHeaderData.length;
                var roundVal = $("#roundValue", _thisObj.mainWindowId).val();
                
                for(var i = 0; i < headerLen; i++) {
                    if(dgHeaderData[i].attr === '0') {
                        
                        if(dgHeaderData[i].round === 'false') {
                                if(typeof _thisObj.headerConfig[dgHeaderData[i].field] !== 'undefined' && _thisObj.headerConfig[dgHeaderData[i].field].linkmetadataid !== '' && _thisObj.headerConfig[dgHeaderData[i].field].linkmetadataid != null)
                                    dgHeaderData[i].formatter = function(value, row, index){
                                        if(typeof value === 'undefined' || value === null)
                                            return '';
                                        return '<a href="javascript:;" onclick="checkMetaDataTypeFunction(\'' + _thisObj.headerConfig[dgHeaderData[i].field].linkmetadataid + '\')">' + pureNumberFormat(value) + '</a>';
                                    };
                                else
                                    dgHeaderData[i].formatter = function(value, row, index){
                                        if(typeof value === 'undefined' || value === null)
                                            return '';
                                        return pureNumberFormat(value);
                                    };
                        } else {
                            if(roundVal == '1') {
                                if(typeof _thisObj.headerConfig[dgHeaderData[i].field] !== 'undefined' && _thisObj.headerConfig[dgHeaderData[i].field].linkmetadataid !== '' && _thisObj.headerConfig[dgHeaderData[i].field].linkmetadataid != null) {
                                    _thisObj.counterGlobal = _thisObj.headerConfig[dgHeaderData[i].field].linkmetadataid;
                                    
                                    dgHeaderData[i].formatter = function(value, row, index){
                                        if(typeof value === 'undefined' || value === null)
                                            return '';
                                        return '<a href="javascript:;" onclick="checkMetaDataTypeFunction(\'' + _thisObj.counterGlobal + '\', null, \'Жагсаалт\', null, \'Хаах\')">' + pureNumberFormat(value) + '</a>';
                                    };
                                } else
                                    dgHeaderData[i].formatter = function(value, row, index){
                                        if(typeof value === 'undefined' || value === null)
                                            return '';
                                        return pureNumberFormat(value);
                                    };
                            } else if(roundVal == '1000') {
                                if(typeof _thisObj.headerConfig[dgHeaderData[i].field] !== 'undefined' && _thisObj.headerConfig[dgHeaderData[i].field].linkmetadataid !== '' && _thisObj.headerConfig[dgHeaderData[i].field].linkmetadataid != null) {
                                    _thisObj.counterGlobal = _thisObj.headerConfig[dgHeaderData[i].field].linkmetadataid;
                                    
                                    dgHeaderData[i].formatter = function(value, row, index){
                                        if(typeof value === 'undefined' || value === null)
                                            return '';
                                        return '<a href="javascript:;" onclick="checkMetaDataTypeFunction(\'' + _thisObj.counterGlobal + '\', null, \'Жагсаалт\', null, \'Хаах\')">' + pureNumberFormat(value / 1000) + '</a>';
                                    };
                                } else
                                    dgHeaderData[i].formatter = function(value, row, index){
                                        if(typeof value === 'undefined' || value === null)
                                            return '';
                                        return pureNumberFormat(value / 1000);                                    
                                    };                      
                            } else if(roundVal == '1000000')
                                dgHeaderData[i].formatter = dataGridFormatter_1000000;
                            else if(roundVal == '1000000000')
                                dgHeaderData[i].formatter = dataGridFormatter_1000000000;
                        }
                        dgHeaderData[i].styler = configedCellStyler;
                    }
                    
                    if(dgHeaderData[i].attr === '2') {
                        if(typeof _thisObj.headerConfig[dgHeaderData[i].field] !== 'undefined' && _thisObj.headerConfig[dgHeaderData[i].field].linkmetadataid !== '' && _thisObj.headerConfig[dgHeaderData[i].field].linkmetadataid != null) {
                            _thisObj.counterGlobal = _thisObj.headerConfig[dgHeaderData[i].field].linkmetadataid;

                            dgHeaderData[i].formatter = function(value, row, index){
                                if(typeof value === 'undefined' || value === null)
                                    return '';
                                return '<a href="javascript:;" onclick="checkMetaDataTypeFunction(\'' + _thisObj.counterGlobal + '\', null, \'Жагсаалт\', null, \'Хаах\')"><span title="' + value + '" class="">' + value + '</span></a>';
                            };
                        } else
                            dgHeaderData[i].formatter = function(value, row, index){
                                if(typeof value === 'undefined' || value === null)
                                    return '';
                                return '<span title="' + value + '" class="">' + value + '</span>';                                
                            };    
                        dgHeaderData[i].styler = configedCellStyler;
                    }
                    
                    if(typeof _thisObj.headerConfig[dgHeaderData[i].field+'_code'] !== 'undefined' && _thisObj.headerConfig[dgHeaderData[i].field+'_code'].type === '3') {
                        dgHeaderData[i].editor.options.onSelect = function(record){
                            var dgIndex = _thisObj.getRowIndex($(this));
                            var rows = $(_thisObj.dataGridId, _thisObj.mainWindowId).datagrid('getRows');           
                            var row = rows[dgIndex];
                            var currField = $(this).closest('table').closest('td').attr('field');
                            var headerConfigVar = _thisObj.headerConfig[currField+'_code'];
                            var dataRows = [];  
                            var origField = headerConfigVar.id;
                            row[origField] = record.id;
                            dataRows.push(row);
                            _thisObj.saveActivityAccountPartial(dataRows, 'activity');
                        };
                        var dtlKey = dgHeaderData[i].field+'_code';
//                        dgHeaderData[i].formatter = function(value, row){
//                            return row[dtlKey];
//                        };
                        if(dtlKey === 'countryid_code')
                            dgHeaderData[i].formatter = function(value, row){
                                return row.countryid_code;
                            };
                        else if(dtlKey === 'periodid_code')
                            dgHeaderData[i].formatter = function(value, row){
                                return row.periodid_code;
                            };
                        else if(dtlKey === 'measureid_code')
                            dgHeaderData[i].formatter = function(value, row){
                                return row.measureid_code;
                            };
                        dgHeaderData[i].styler = configedCellStyler;
                    }        
                    
                    if(typeof _thisObj.headerConfig[dgHeaderData[i].field] !== 'undefined' && _thisObj.headerConfig[dgHeaderData[i].field].expression !== null && _thisObj.headerConfig[dgHeaderData[i].field].expression !== '') {
                        dgHeaderData[i].styler = configedCellStyler;
                    }
                }
                
                resp.getRows.freeze[1].formatter = dataGridFormatterDescription;
                resp.getRows.freeze.push(resp.getRows.freeze[0]);
                resp.getRows.freeze[0] = {field: 'ck', checkbox: true};          
                
                var DG = $(_thisObj.dataGridId, _thisObj.mainTemplateWindowId).datagrid({
                    data: resp.getRows.detail,
                    fit: false,
                    fitColumns: false,
                    rownumbers: true,
                    singleSelect: true,
                    showFooter: false,
                    pagination: true,
                    pageList: [10,20,30,40,50,60,100,200],
                    pageSize: 200,
                    frozenColumns: [
                        resp.getRows.freeze
                    ],                    
                    columns: [dgFirstHeaderData, dgHeaderData],
                    onRowContextMenu: function (e, index, row) {
                        e.preventDefault();
                        $(this).datagrid('unselectAll');
                        $(this).datagrid('selectRow', index);                        
                        window.activityArgs = {
                            activityKeyId: _thisObj.activityKeyId,
                            contextRowValue: row
                        };
                        $.contextMenu({
                            selector: _thisObj.mainTemplateWindowId + " .datagrid .datagrid-view .datagrid-view2 .datagrid-body .datagrid-row, .datagrid .datagrid-view .datagrid-view1 .datagrid-body .datagrid-row",
                            items: {
                                "orderList": {
                                    name: "Дараалал тохируулах", 
                                    icon: "reorder", 
                                    callback: function(key, options) {
                                        _thisObj.reorderActivity('template', activityArgs);
                                    }
                                },
                                "create": {
                                    name: "Нэмэх", 
                                    icon: "plus", 
                                    callback: function(key, options) {
                                        _thisObj.loadButtonsTemplate();
                                    }
                                },
                                "account_config": {
                                    name: "Данс тохируулах", 
                                    icon: "calculator", 
                                    callback: function(key, options) {
                                        dataViewCustomSelectableGrid('FIN_ACCOUNT_LIST_AM', 'single', 'activitySelectabledGridTemplate4', '', this, undefined, undefined, undefined, [{'value': row.oppaccountid}]);
                                    }
                                },
                                "delete": {
                                    name: "Устгах", 
                                    icon: "trash", 
                                    callback: function(key, options) {
                                        _thisObj.deleteActivity();
                                    }
                                }
                            }
                        });
                        $.uniform.update();
                    },                    
                    onLoadSuccess: function(data) {
                        $('#dataGridDiv tbody', _thisObj.mainTemplateWindowId).on('keyup', 'input', function(e) {
                            var key = e.which;
                            var _thisInput = $(this);
                            
                            if(key === 13 && _thisInput.val() === '') {
                                var selectedRow = $(_thisObj.dataGridId, _thisObj.mainTemplateWindowId).datagrid('getSelections');
                                if(_thisInput[0].name === 'expenseAccountQuickCode')
                                    selectedRow[0].expenseaccountid = null;
                                else
                                    selectedRow[0].revenueaccountid = null;
                                _thisObj.saveActivityAccountPartial(selectedRow, 'template');                                  
                            } else
                                _thisObj.keyPressEventsTemplate(this, key);
                        });              
                        
                        $('.expenseAccountQuickCode, .revenueAccountQuickCode, .receivableAccountQuickCode', _thisObj.mainTemplateWindowId).on("focus", function(e) {
                            var _this = $(this), metaDataCode = 'finAccountListAndType';
                            
//                            if(_this[0].name === 'expenseAccountQuickCode')
//                                metaDataCode = 'exp_acc_list';
//                            else if(_this[0].name === 'revenueAccountQuickCode')
//                                metaDataCode = 'finAccountRevenueList';
//                            else
//                                metaDataCode = 'finAccountReceivable';
                            
                            _this.autocomplete({
                                minLength: 1,
                                maxShowItems: 10,
                                delay: 500,
                                highlightClass: "lookup-ac-highlight", 
                                appendTo: "body",
                                position: {my : "left top", at: "left bottom", collision: "flip flip"}, 
                                autoFocus: true,
                                source: function(request, response) {
                                    $.ajax({
                                        type: 'post',
                                        url: 'mdcommon/hardWindowAutoComplete',
                                        dataType: "json",
                                        data: {
                                            q: request.term,
                                            type: 'code',
                                            metaDataCode: metaDataCode
                                        },
                                        success: function(data) {
                                            response($.map(data, function(item) {
                                                return {
                                                    label: item.code,
                                                    name: item.name,
                                                    data: item
                                                };
                                            }));
                                        }
                                    });
                                },
                                focus: function() {
                                    return false;
                                },
                                open: function() {
                                    $(this).autocomplete('widget').zIndex(99999999999999);
                                    return false;
                                },
                                close: function (event, ui){
                                    $(this).autocomplete("option","appendTo","body"); 
                                }, 
                                select: function(event, ui) {
                                    var data = ui.item.data;
                                    var selectedRow = $(_thisObj.dataGridId, _thisObj.mainTemplateWindowId).datagrid('getSelections');
                                    
                                    if(_this[0].name === 'expenseAccountQuickCode')
                                        selectedRow[0].expenseaccountid = data.id
                                    else if(_this[0].name === 'receivableAccountQuickCode')
                                        selectedRow[0].receivableaccountid = data.id
                                    else
                                        selectedRow[0].revenueaccountid = data.id
                                    
                                    _thisObj.saveActivityAccountPartial(selectedRow, 'template');
                                    _this.val("");          
                                    return false;                    
                                }
                            }).autocomplete("instance")._renderItem = function(ul, item) {
                                ul.addClass('lookup-ac-render');

                                var re = new RegExp("(" + this.term + ")", "gi"),
                                    cls = this.options.highlightClass,
                                    template = "<span class='" + cls + "'>$1</span>",
                                    label = item.label.replace(re, template);

                                return $('<li>').append('<div class="lookup-ac-render-code">'+label+'</div><div class="lookup-ac-render-name">'+item.name+'</div>').appendTo(ul);
                            };    
                        });                        
                        
                        var vWidth = $("#dataGridDiv", _thisObj.mainTemplateWindowId).children("div").width();
                        $(".activity-expression-viewer", _thisObj.mainTemplateWindowId).css('width', vWidth+'px');               
                        
                        /*$('#dataGridDiv', _thisObj.mainTemplateWindowId).find('.datagrid-view .datagrid-view2 .datagrid-body').find('.datagrid-btable tbody tr').each(function(key, val){
                            $(this).find('td').each(function(){
                                var _this = $(this), field = _this.attr('field'), 
                                autoComp = typeof _thisObj.headerConfig[field] !== 'undefined' ? (typeof _thisObj.headerConfig[field].type === 'undefined' ? '' : _thisObj.headerConfig[field].type) : '',
                                comboData = typeof _thisObj.headerConfig[field+'_code'] !== 'undefined' ? (typeof _thisObj.headerConfig[field+'_code'].type === 'undefined' ? '' : _thisObj.headerConfig[field+'_code'].type) : '';
                                
                                if(autoComp !== '1' && comboData !== '3') {
                                    if(typeof data.rows[key][field] !== 'undefined') {
                                        if(data.rows[key][field] !== '' || data.rows[key][field] !== null)
                                            _this.css('background-color', '#FFDEA5');
                                    }
                                }
                            });
                        });*/
                        $('#dataGridDiv', _thisObj.mainTemplateWindowId).find('.datagrid-view .datagrid-view2 .datagrid-body').find('.datagrid-btable tbody tr').each(function(key, val){
                            $(this).find('td').each(function(){
                                var _this = $(this), field = _this.attr('field') + '_formula';
                                
                                if(typeof data.rows[key][field] !== 'undefined') {
                                    if(data.rows[key][field] !== '' || data.rows[key][field] !== null)
                                        _this.css('background-color', '#FFDEA5');
                                }
                            });
                        });                        
                        Core.initUniform((_thisObj.dataGridId, _thisObj.mainTemplateWindowId));
                    },
                    onClickRow: function(index, row) {
                        $.uniform.update();
                    },
                    onClickCell: function(index, field, value) {
                        $.uniform.update();
                        var _thisDG = $(this);              
                        
                        _thisObj.activeField = field;
                        _thisObj.activeIndex = index;
                        var rows = _thisDG.datagrid('getRows');
                        var ed = _thisDG.datagrid('getEditor', {index: index, field: field});                    
                        var headerConfigVar = typeof _thisObj.headerConfig[field] !== 'undefined' ? _thisObj.headerConfig[field] : '';
                        
                        $(".activity-expression-viewer", _thisObj.mainTemplateWindowId).find('span').empty();
                        if(headerConfigVar.type === '1' && headerConfigVar.metadataid !== '') {
                            var _thisInput = $(ed.target).parent().find("span input[type='text']");
                            
                            _thisInput.autocomplete({
                                minLength: 1,
                                maxShowItems: 10,
                                delay: 500,
                                highlightClass: "lookup-ac-highlight", 
                                appendTo: "body",
                                position: {my : "left top", at: "left bottom", collision: "flip flip"}, 
                                autoFocus: true,
                                source: function(request, response) {                                    
                                    $.ajax({
                                        type: 'post',
                                        url: 'mdcommon/hardWindowAutoComplete',
                                        dataType: "json",
                                        data: {
                                            q: request.term,
                                            type: 'code',
                                            metaDataId: headerConfigVar.metadataid
                                        },
                                        success: function(data) {
                                            response($.map(data, function(item) {
                                                return {
                                                    label: item.code,
                                                    name: item.name,
                                                    data: item
                                                };
                                            }));
                                        }
                                    });
                                },
                                focus: function() {
                                    return false;
                                },
                                open: function() {
                                    $(this).autocomplete('widget').zIndex(99999999999999);
                                    return false;
                                },
                                close: function (event, ui){
                                    $(this).autocomplete("option","appendTo","body"); 
                                }, 
                                select: function(event, ui) {
                                    var data = ui.item.data;
                                    var dataRows = [];
                                    var selectedRowCell = $(_thisObj.dataGridId, _thisObj.mainTemplateWindowId).datagrid('cell');
                                    var getRowsPage = $(_thisObj.dataGridId, _thisObj.mainTemplateWindowId).datagrid('getRows');    
                                    var selectedRow = getRowsPage[selectedRowCell.index];        
                                    var origField = headerConfigVar.id;
                                    
                                    selectedRow[origField] = data.id;
                                    dataRows.push(selectedRow);
                                    _thisObj.saveActivityAccountPartial(dataRows, 'activity');
                                    _thisInput.val("");          
                                    return false;                    
                                }
                            }).autocomplete("instance")._renderItem = function(ul, item) {
                                ul.addClass('lookup-ac-render');

                                var re = new RegExp("(" + this.term + ")", "gi"),
                                    cls = this.options.highlightClass,
                                    template = "<span class='" + cls + "'>$1</span>",
                                    label = item.label.replace(re, template);

                                return $('<li>').append('<div class="lookup-ac-render-code">'+label+'</div><div class="lookup-ac-render-name">'+item.name+'</div>').appendTo(ul);
                            };  
                            
                        } else if((headerConfigVar.type === '0' || headerConfigVar.type === '2') && headerConfigVar.isattribute === 'false') {
                            
                            if(rows[index][field+'_formula'] !== undefined && rows[index][field+'_formula'] !== null && rows[index][field+'_formula'] !== '') {
                                $(ed.target).parent().find('span').find('input[type="text"]').css('width', '121px').prop('readonly', true);
                                $(ed.target).parent().find('span').append('<a class="btn btn-warning btn-sm" title="Томъёо оруулах" onclick="amactivityObj.insertFormExpression(this, \'\');" href="javascript:;" style="width: 25px;border-radius: 0px;padding-left: 6px;"><i class="fa fa-calculator"></i></a>');
                                $(".activity-expression-viewer", _thisObj.mainTemplateWindowId).find('span').append(rows[index][field+'_formula']);            
                                
                            } else if(headerConfigVar.type === '0') {
                                $(ed.target).parent().find('span').find('input[type="text"]').css('width', '121px');
                                $(ed.target).parent().find('span').append('<a class="btn btn-warning btn-sm" title="Томъёо оруулах" onclick="amactivityObj.insertFormExpression(this, \'\');" href="javascript:;" style="width: 25px;border-radius: 0px;padding-left: 6px;"><i class="fa fa-calculator"></i></a>');                            
                                
                            } else
                                $(ed.target).parent().find('span').find('input[type="text"]').css('text-align', 'right');
                            
                            if(headerConfigVar.expression !== '' && headerConfigVar.expression !== null) {
                                $(".activity-expression-viewer", _thisObj.mainTemplateWindowId).find('span').empty();
                                $(".activity-expression-viewer", _thisObj.mainTemplateWindowId).find('span').append(headerConfigVar.expression);
                            }
                            
                            /*if (headerConfigVar.type === '0' && rows[index].haschild !== '1') {
                                if(rows[index].haschild === '1' || (rows[index][field+'_formula'] !== undefined && rows[index][field+'_formula'] !== null && rows[index][field+'_formula'] !== '')) {
                                    $(ed.target).parent().find('span').find('input[type="text"]').css('width', '100%');
                                }
                                else {
                                    $(ed.target).parent().find('span').find('input[type="text"]').css('width', '121px');
                                    $(ed.target).parent().find('span').append('<a class="btn btn-warning btn-sm" title="Томъёо оруулах" onclick="amactivityObj.insertFormExpression(this, \'\');" href="javascript:;" style="width: 25px;border-radius: 0px;padding-left: 6px;"><i class="fa fa-calculator"></i></a>');
                                }
                            } else {
                                $(ed.target).parent().find('span').find('input[type="text"]').css('text-align', 'right');
                            }*/
                        }
                        Core.initUniform((_thisObj.dataGridId, _thisObj.mainTemplateWindowId));
                    },
                    onSelectCell: function(index, field, value) {
                        $.uniform.update();
                        Core.initUniform((_thisObj.dataGridId, _thisObj.mainTemplateWindowId));
                    },
                    onAfterEdit: function(index,row,changes){
                      tmpTr = $(_thisObj.dataGridId).parents(".datagrid-view").find(".datagrid-view1 .datagrid-btable tbody tr").eq(index);
                      initEventOpenClose($(_thisObj.dataGridId), tmpTr.find(".tree-hit"), index);
                    }                    
                });
                DG.datagrid('enableCellEditing');
                
            } else {
                new PNotify({
                    type: resp.status,
                    title: resp.title,
                    text: resp.text,
                    sticker: false
                });                     
            }
            Core.unblockUI();
        },
        error: function(){
            alert("Ajax Error!");
        }
    });
};

Amactivity.prototype.loadDimensionTemplate = function(row) {
    var _thisObj = this;    
    $("#activityInfoForm", _thisObj.mainTemplateWindowId).find("#activityKeyId_valueField").val(row.id);
    $("#activityInfoForm", _thisObj.mainTemplateWindowId).find("#activityKeyId_displayField").val(row.activitycode);
    $("#activityInfoForm", _thisObj.mainTemplateWindowId).find("#activityKeyId_nameField").val(row.description);    

    $.ajax({
        type: 'POST',
        url: 'amactivity/getPeriodActivityCtrl',
        data: {
            activityKeyId: row.id
        },
        dataType: "json",
        success: function(resp) {
            if(resp.status === 'success') {
                var comboSelect = $(".templateNextButton", _thisObj.mainTemplateWindowId);
                if(comboSelect.find("input:hidden").length)
                    comboSelect.find("input:hidden").remove();
                comboSelect.append("<input type='hidden' id='maxDimension' value='"+resp.getRows.maxdimension+"'>");
                comboSelect.append("<input type='hidden' id='minDimension' value='"+resp.getRows.mindimension+"'>");
            } else 
                alert("Button Not Found!!!");
        },
        error: function(){
            alert("Failed Ajax!");
        }
    });
};

Amactivity.prototype.loadDimensionTemplate2 = function(row) {
    var _thisObj = this;
    var selectedRow = $(_thisObj.dataGridId, _thisObj.mainTemplateWindowId).datagrid('getSelected');

    $.ajax({
        type: 'POST',
        url: 'amactivity/updateTemplateActivityCtrl',
        data: {
            activityKeyId: row.id,
            id: selectedRow.id
        },
        success: function(resp) {
            new PNotify({
                title: 'Success',
                text: 'Амжилттай хадгалагдлаа',
                type: 'success',
                sticker: false
            });              
            _thisObj.loadDataGridTemplate();
        },
        error: function(){
            alert("Failed Ajax!");
        }
    });
};

Amactivity.prototype.accountConfigTemplate = function(row) {
    var _thisObj = this;    
    var selectedRow = $(_thisObj.dataGridId, _thisObj.mainTemplateWindowId).datagrid('getSelected');

    $.ajax({
        type: 'POST',
        url: 'amactivity/updateTemplateAccount2ActivityCtrl',
        data: {
            accountid: row.id,
            id: selectedRow.id,
            activitykeyid: selectedRow.activitykeyid,
            rowkey: selectedRow.rowkey
        },
        success: function(resp) {
            new PNotify({
                title: 'Success',
                text: 'Амжилттай хадгалагдлаа',
                type: 'success',
                sticker: false
            });              
            _thisObj.loadDataGridTemplate();
        },
        error: function(){
            alert("Failed Ajax!");
        }
    });
};

Amactivity.prototype.loadButtonsTemplate = function() {
    var _thisObj = this;
    var selectedRow = $(_thisObj.dataGridId, _thisObj.mainTemplateWindowId).datagrid('getSelected');
    var maxDimValue = Number($("#maxDimension", _thisObj.mainTemplateWindowId).val()),
        minDimValue = Number($("#minDimension", _thisObj.mainTemplateWindowId).val()),
        levelNum = minDimValue,        
        currentLevelNum = 'NOT_FOUND';

    if(selectedRow) {
        levelNum = Number(selectedRow.levelnum);    
        if(levelNum >= maxDimValue)
            levelNum = maxDimValue;
        else {
            currentLevelNum = levelNum;
            levelNum++;
        }
    }
    $.ajax({
        type: 'POST',
        url: 'amactivity/getBtnActivityCtrl',
        data: { 
//            levelNum: levelNum, 
//            currentLevelNum: currentLevelNum, 
            selectedRow: selectedRow,
            activityKeyId: _thisObj.activityKeyId
        },
        dataType: "json",
        success: function(resp) {
            if(resp.status === 'success') {
                if(resp.getRow.length > 1)
                    _thisObj.dimensionDVlist(resp.getRow, 'template');
                else if(resp.getRow[0].META_DATA_CODE != '')
                    dataViewCustomSelectableGrid(resp.getRow[0].META_DATA_CODE, 'multi', 'selectedActivityTemplateFunction', '', resp.getRow[0].CODE+'_'+resp.getRow[0].FIELD_PATH+'_'+resp.getRow[0].FIELD_PATH_CODE, '');
                else
                    _thisObj.dimensionDimlist(resp.getRow, 'template');
            } else {
                PNotify.removeAll();
                new PNotify({
                  title: 'Warning',
                  text: 'Темплейтийн үзүүлэлт тохируулна уу!',
                  type: 'warning',
                  sticker: false
                });       
            }
        },
        error: function(){
            alert("Failed Ajax!");
        }
    });
};    

Amactivity.prototype.editModeLoadDataGrid = function() {
    var _thisObj = this;
    
        _thisObj.periodId = $("#periodId", _thisObj.mainWindowId).val();        
        // <editor-fold defaultstate="collapsed" desc="Validation">
        $("#activityInfoForm", _thisObj.mainWindowId).validate({errorPlacement: function() {
        }});        
        if (!$("#activityInfoForm", _thisObj.mainWindowId).valid()) {
            PNotify.removeAll();
            new PNotify({
                title: 'Warning',
                text: 'Арга хэмжээгээ сонгоно уу!',
                type: 'warning',
                sticker: false
            });  
            return;
        } else if(_thisObj.periodId === '' || _thisObj.periodId === null) {
            PNotify.removeAll();
            new PNotify({
                title: 'Warning',
                text: 'Period тохируулаагүй байна!',
                type: 'warning',
                sticker: false
            });          
            return;
        }
        // </editor-fold>        
        _thisObj.loadDataGrid();
};    

Amactivity.prototype.deleteActivity = function(execType, isWithChild) {
    var _thisObj = this, selectedRow = [], selectedRow2 = [];
    var functionName = 'deleteActivitySheetCtrl';
    
    if(execType === 'activity') {        
        var getRowsPage = $(_thisObj.dataGridId, _thisObj.mainWindowId).datagrid('getRows');
        
        $(_thisObj.dataGridId, _thisObj.mainWindowId).datagrid("getPanel").children("div.datagrid-view").find('table.datagrid-btable tbody tr').each(function(){
            var _this = $(this), _thisTD = _this.find('td:eq(1)').find('input[type="checkbox"]');            
            if(_thisTD.is(':checked')) {
                var idRow = getRowsPage[_this.attr('datagrid-row-index')];
                selectedRow.push(idRow.id);
            }
        });                
    } else {        
        if (execType == 'template-delete') {
            functionName = 'deleteActivityTemplate2Ctrl';
        } else if (execType == 'template-delete2') {
            functionName = 'deleteActivityTemplate3Ctrl';
        } else if (execType == 'template-delete3') {
            functionName = 'deleteActivityTemplate4Ctrl';
        } else {
            functionName = 'deleteActivityTemplateCtrl';
        }

        var getRowsPage = $(_thisObj.dataGridId, _thisObj.mainTemplateWindowId).datagrid('getRows');
        
        $(_thisObj.dataGridId, _thisObj.mainTemplateWindowId).datagrid("getPanel").children("div.datagrid-view").find('table.datagrid-btable tbody tr').each(function(){
            var _this = $(this), _thisTD = _this.find('td:eq(1)').find('input[type="checkbox"]');            
            
            if(_thisTD.is(':checked')) {
                var idRow = getRowsPage[_this.attr('datagrid-row-index')];
                selectedRow.push(idRow.id);
                selectedRow2.push(idRow);
            }
        });        
    }    
    
    if(Object.keys(selectedRow).length === 0) {
        PNotify.removeAll();
        new PNotify({
            title: 'Warning',
            text: 'Устгах мөрөө сонгоно уу!',
            type: 'warning',
            sticker: false
        });  
        return;        
    }
    
    var $dialogName = 'dialog-confirm-activity';
    if (!$("#" + $dialogName).length) {
        $('<div id="' + $dialogName + '"></div>').appendTo('body');
    }    
    
    $("#" + $dialogName).empty().html("Та <strong>УСТГАХ</strong> үйлдэлийг хийхдээ итгэлтэй байна уу?");
    $("#" + $dialogName).dialog({
        cache: false,
        resizable: false,
        bgiframe: true,
        autoOpen: false,
        title: 'Сануулга',
        width: 370,
        height: "auto",
        modal: true,
        close: function () {
            $("#" + $dialogName).empty().dialog('close');
        },
        buttons: [
            {text: 'Тийм', class: 'btn green-meadow btn-sm', click: function () {
                $.ajax({
                    type: 'POST',
                    url: 'amactivity/'+functionName,
                    data: { 
                        isWithChild: isWithChild, 
                        idRows: selectedRow,
                        idRows2: selectedRow2
                    },
                    dataType: "json",
                    success: function(resp) {
                        if(resp.status === 'success') {
                            if(execType === 'template' || execType === 'template-delete' || execType === 'template-delete2' || execType === 'template-delete3')
                                _thisObj.loadDataGridTemplate();
                            else
                                _thisObj.loadDataGrid();                            
                        }
                        $("#" + $dialogName).dialog('close');

                        PNotify.removeAll();
                        new PNotify({
                            type: resp.status,
                            title: resp.title,
                            text: resp.text,
                            sticker: false
                        });                
                    },
                    error: function(){
                        alert("Failed Ajax Delete Activity!");
                    }
                });  
            }},
            {text: 'Үгүй', class: 'btn blue-madison btn-sm', click: function () {
                $("#" + $dialogName).dialog('close');
            }}
        ]
    });
    $("#" + $dialogName).dialog('open');      
};  

Amactivity.prototype.commentActivity = function(execType, activityArgs) {
    var _thisObj = this;
    
    if (typeof _thisObj.activeField === 'undefined') {
        PNotify.removeAll();
        new PNotify({
            title: 'Warning',
            text: 'Коммэнт бичих нүдээ идэвхжүүлнэ үү!',
            type: 'warning',
            sticker: false
        });  
        return ;
    }
    
    if((_thisObj.activeField).length === 0 ) {
        PNotify.removeAll();
        new PNotify({
            title: 'Warning',
            text: 'Коммэнт бичих нүдээ идэвхжүүлнэ үү!',
            type: 'warning',
            sticker: false
        });  
        return ;
    }
    
    var $dialogName = 'dialog-comment-activity';
    if (!$("#" + $dialogName).length) {
        $('<div id="' + $dialogName + '"></div>').appendTo('body');
    }    
    
    $.ajax({
        type: 'POST',
        url: 'amactivity/getCommentActivitySheetCtrl',
        data: {
            activityKeyId: activityArgs.activityKeyId, 
            path: _thisObj.activeField, 
            pathValue: activityArgs.contextRowValue[_thisObj.activeField],
            rowId: activityArgs.contextRowValue['id']
        },
        dataType: "json",
        success: function(data) {
            if(data.status !== 'success') {
                PNotify.removeAll();
                new PNotify({
                    type: resp.status,
                    title: resp.status,
                    text: resp.message,
                    sticker: false
                });  
                return;
            }
            
            var description = (typeof data.description !== 'undefined') ? data.description : '';
            
            var html = '<div class="row">'
                            + '<div class="col-md-12">'
                                + '<p><label for="newActivityDescription" data-label-path="newActivityDescription">Өөрчлөж буй утга:</label> <strong>'+ activityArgs.contextRowValue[_thisObj.activeField] +'</strong></p>'
                                + '<table class="table table-sm table-no-bordered bp-header-param">'
                                    + '<tbody>'
                                        + '<tr><td style="width:100%" colspan="2"><label class=""></label><textArea placeholder="Тайлбар бичнэ үү" name="newActivityDescription" style="width:100%; height:100px;" id="newActivityDescription">'+ description +'</textArea></td></tr>'
                                    + '</tbody>'
                                + '</table>'
                                + '<div class="table-scrollable" style="max-height: 400px; overflow-y: auto">'
                                    + '<table class="table table-hover table-striped" id="salarySheetLogList">'
                                        + '<thead>'
                                            + '<tr>'
                                                + '<th style="width: 20px">№</th>'
                                                + '<th>Утга</th>'
                                                + '<th>Огноо</th>'
                                                + '<th>Хэрэглэгч</th>'
                                                + '<th>Тайлбар</th>'
                                            + '</tr>'
                                        + '</thead>'
                                        + '<tbody>';
                var _number = 1;
                $.each(data.log, function (index, row) {
                    var _description = ((row.DESCRIPTION).length > 20) ? (row.DESCRIPTION).substring(1, 20) + '...' : row.DESCRIPTION;
                    html += '<tr>';
                        html += '<td>' + _number + '</td>';
                        html += '<td>' + row.VALUE + '</td>';
                        html += '<td>' + row.CREATED_DATE + '</td>';
                        html += '<td>' + row.USERNAME + '</td>';
                        html += '<td title="'+ row.DESCRIPTION +'">' + _description + '</td>';
                    html += '</tr>';
                    
                    _number++;
                });
                
                html += '</tbody>'
                            + '</table>'
                        + '</div>'
                    + '</div>'
                + '</div>';

            $("#" + $dialogName).empty().html(html);
            $("#" + $dialogName).dialog({
                cache: false,
                resizable: false,
                bgiframe: true,
                autoOpen: false,
                title: 'Коммэнт бичих',
                width: 700,
                height: "auto",
                modal: true,
                close: function () {
                    $("#" + $dialogName).empty().dialog('destroy').remove();
                },
                buttons: [
                    {text: 'Хадгалах', class: 'btn green-meadow btn-sm', click: function () {
                        $.ajax({
                            type: 'POST',
                            url: 'amactivity/commentActivitySheetCtrl',
                            data: {
                                activityKeyId: activityArgs.activityKeyId, 
                                path: _thisObj.activeField, 
                                pathValue: activityArgs.contextRowValue[_thisObj.activeField],
                                rowId: activityArgs.contextRowValue['id'],
                                description: $('#newActivityDescription').val()
                            },
                            dataType: "json",
                            success: function(resp) {
                                if(resp.status === 'success') {
                                    if(execType === 'template')
                                        _thisObj.loadDataGridTemplate();
                                    else
                                        _thisObj.loadDataGrid();                            
                                }
                                $("#" + $dialogName).empty().dialog('destroy').remove();

                                PNotify.removeAll();
                                new PNotify({
                                    type: resp.status,
                                    title: resp.status,
                                    text: resp.message,
                                    sticker: false
                                });                
                            },
                            error: function() {
                                alert("Failed Ajax Activity Comment!");
                            }
                        });  
                    }},
                    {text: 'Хаах', class: 'btn blue-madison btn-sm', click: function () {
                        $("#" + $dialogName).empty().dialog('destroy').remove();
                    }}
                ]
            });
            $("#" + $dialogName).dialog('open');
        },
        error: function() {
            alert("Failed Ajax Activity Comment!");
        }
    });  
};    

Amactivity.prototype.loadDataGridAggregate2 = function(fData) {
    var _thisObj = this;        
    
    $.ajax({
        type: 'POST',
        url: 'amactivity/getAllPeriodActivity2Ctrl',
        data: { 
            activityKeyId: _thisObj.activityKeyId,
            fData: fData
        },
        dataType: "json",
        beforeSend: function() {
            var blockMsg='Түр хүлээнэ үү...';
            Core.blockUI({
                message: blockMsg,
                boxed: true
            });
        },        
        success: function(resp) {
            if(resp.status === 'success') {
                var dgFirstHeaderData = resp.getRows.firstHeader;
                var dgHeaderData = resp.getRows.header;
                var headerLen = dgHeaderData.length;
                
                for(var i = 0; i < headerLen; i++) {
                    if(dgHeaderData[i].attr == 0) {
                        dgHeaderData[i].formatter = dataGridFormatterGeneral;
                    }
                }
                for(var i = 0; i < dgFirstHeaderData.length; i++) {
                    if(typeof dgFirstHeaderData[i].attr !== 'undefined') {
                        if(dgFirstHeaderData[i].attr == 0)
                            dgFirstHeaderData[i].formatter = dataGridFormatterGeneral;
                    }
                }
                resp.getRows.freeze[0].formatter = dataGridFormatterDescription;
                
                var DG = $(_thisObj.dataGridIdAggregate2, _thisObj.mainAggregate2WindowId).datagrid({
                    data: resp.getRows.detail,
                    fit: false,
                    fitColumns: false,
                    rownumbers: true,
                    singleSelect: true,
                    showFooter: true,
                    pagination: true,
                    pageList: [10,20,30,40,50,60,100,200],
                    pageSize: 100,
                    frozenColumns: [
                        resp.getRows.freeze
                    ],                    
                    columns: [dgFirstHeaderData, dgHeaderData],
                    onLoadSuccess: function(data) {                       
                        Core.unblockUI();
                    }
                });
            } else {
                PNotify.removeAll();
                new PNotify({
                    title: resp.title,
                    text: resp.text,
                    type: resp.status,
                    sticker: false
                });                  
            }
        },
        error: function(){
            alert("Ajax Error!");
        }
    });
};

Amactivity.prototype.loadDataGridAggregate = function(fData) {
    var _thisObj = this;
    fData = typeof fData === 'undefined' ? '' : fData;
    var dynamicHeight = $(window).height() - 180;
    $(_thisObj.dataGridIdAggregate, _thisObj.mainAggregateWindowId).attr('height', dynamicHeight);
    
    $.ajax({
        type: 'POST',
        url: 'amactivity/getAllPeriodActivityCtrl',
        data: { 
            activityKeyId: _thisObj.activityKeyId,
            fData: fData
        },
        dataType: "json",
        beforeSend: function() {
            var blockMsg='Түр хүлээнэ үү...';
            Core.blockUI({
                message: blockMsg,
                boxed: true
            });
        },        
        success: function(resp) {
            if(resp.status === 'success') {
                var dgFirstHeaderData = resp.getRows.firstHeader;
                var dgHeaderData = resp.getRows.header;
                var headerLen = dgHeaderData.length;
                _thisObj.columnFieldsHeaderFirst = dgFirstHeaderData;
                _thisObj.columnFieldsHeader = dgHeaderData;
                
                for(var i = 0; i < headerLen; i++) {
                    if(dgHeaderData[i].attr == 0) {
                        dgHeaderData[i].formatter = dataGridFormatterGeneral;
                    }
                }
                for(var i = 0; i < dgFirstHeaderData.length; i++) {
                    if(typeof dgFirstHeaderData[i].attr !== 'undefined') {
                        if(dgFirstHeaderData[i].attr == 0)
                            dgFirstHeaderData[i].formatter = dataGridFormatterGeneral;
                    }
                }
                resp.getRows.freeze[0].formatter = dataGridFormatterDescription;
                
                var DG = $(_thisObj.dataGridIdAggregate, _thisObj.mainAggregateWindowId).datagrid({
                    data: resp.getRows.detail,
                    fit: false,
                    fitColumns: false,
                    rownumbers: true,
                    singleSelect: true,
                    showFooter: true,
                    pagination: true,
                    pageList: [10,20,30,40,50,60,100,200],
                    pageSize: 100,
                    frozenColumns: [
                        resp.getRows.freeze
                    ],                    
                    columns: [dgFirstHeaderData, dgHeaderData],                  
                    onLoadSuccess: function(data) {                       
                        Core.unblockUI();
                    }
                });
            } else {
                PNotify.removeAll();
                new PNotify({
                    title: resp.title,
                    text: resp.text,
                    type: resp.status,
                    sticker: false
                });                  
                Core.unblockUI();
            }
        },
        error: function(){
            alert("Ajax Error!");
        }
    });
};

Amactivity.prototype.insertFormExpression = function(target, wid) {
    var _thisObj = this;
    var dgFieldTd = $(target).closest('table').closest('td');
    var dgIndex = _thisObj.getRowIndex(target), wid = typeof wid === 'undefined' ? _thisObj.mainWindowId : _thisObj.mainTemplateWindowId;
    var rows = $(_thisObj.dataGridId, wid).datagrid('getRows');             
    var dgField = dgFieldTd.attr("field");    
    var $dialogName = 'dialog-activity-expression';
    if (!$($dialogName).length) {
        $('<div id="' + $dialogName + '"></div>').appendTo("body");
    }
    var savedExpression = '';
    var savedProcess = '';
    if(rows[dgIndex][dgField+'_formula'] !== undefined && rows[dgIndex][dgField+'_formula'] !== null && rows[dgIndex][dgField+'_formula'] !== '')
        savedExpression = rows[dgIndex][dgField+'_formula'];
    
    if(rows[dgIndex][dgField+'_process'] !== undefined && rows[dgIndex][dgField+'_process'] !== null && rows[dgIndex][dgField+'_process'] !== '')
        savedProcess = rows[dgIndex][dgField+'_process'];

    var htmlForm = '<textarea id="expressionTextareaAmactivity" placeholder="Томъёо оруулах" name="expressionTextareaAmactivity" class="form-control form-control-sm" rows="3">'+savedExpression+'</textarea>';
        htmlForm += '<br>Процесс сонгох <select id="expressionProcessAmactivity" class="select2 col-md-8 form-control" name="expressionProcessAmactivity"></select>';
        
    $("#" + $dialogName).empty().html(htmlForm);    
    $("#" + $dialogName).dialog({
        appendTo: "body",
        cache: false,
        resizable: true,
        bgiframe: true,
        autoOpen: false,
        title: "Томъёо оруулах",
        width: 500,
        height: 'auto',
        modal: true,
        close: function(){
            $("#" + $dialogName).empty().dialog('destroy').remove();
        },                        
        buttons: [
            {text: plang.get('save_btn'), class: 'btn btn-sm blue', click: function() {
                var expressionCheck = $("#expressionTextareaAmactivity", "#" + $dialogName).val();
                var expressionProcess = $("#expressionProcessAmactivity", "#" + $dialogName).val();
                /*expressionCheck.keyup(function(){
                    if(expressionCheck.hasClass('error'))
                        expressionCheck.removeClass('error');
                });
                if(expressionCheck.val().trim() === '') {
                    expressionCheck.addClass('error').focus();
                    PNotify.removeAll();
                    new PNotify({
                      title: 'Warning',
                      text: 'Томъёогоо оруулна уу!',
                      type: 'warning',
                      sticker: false
                    });                              
                    return;
                }*/
                $.ajax({
                    type: 'post',
                    url: 'amactivity/expressionActivitySaveCtrl',
                    data: {
                        id: rows[dgIndex].id,
                        rowKey: rows[dgIndex].rowkey,
                        expression: expressionCheck,
                        processid: expressionProcess,
                        fact: dgField,
                        rows: rows
                    },                 
                    dataType: "json",
                    success: function(resp) {
                        /*if(resp.status === 'success') {
                            if(typeof wid === 'undefined')
                                _thisObj.loadDataGrid();
                            else
                                _thisObj.loadDataGridTemplate();
                        }*/
                        new PNotify({
                            type: resp.status,
                            title: resp.title,
                            text: resp.text,
                            sticker: false
                        });                                
                    },
                    error: function() {
                        alert("Error");
                    }
                });                      
                $("#" + $dialogName).dialog('close');
            }},
            {text: plang.get('close_btn'), class: 'btn btn-sm blue-hoki', click: function() {
                $("#" + $dialogName).dialog('close');
            }}
        ]
    });
    $("#" + $dialogName).dialog('open');
    Core.initSelect2($("#" + $dialogName));
    
    var comboDatas = [];
    var _this = $("#expressionProcessAmactivity", "#" + $dialogName);
    $.ajax({
        type: 'post',
        url: 'amactivity/processList',
        dataType: "json",
        success: function(resp) {
            if(resp.status === 'success') {                
                _this.empty();
                _this.append($('<option />').val('').text('- Cонгох -'));  

                $.each(resp.rows, function(){
                    if(savedProcess == this.trgmetadataid) {
                        _this.append($("<option />")
                             .val(this.trgmetadataid)
                             .text(this.metadataname)
                             .attr("selected", "selected"));
                    } else {                    
                        _this.append($("<option />")
                             .val(this.trgmetadataid)
                             .text(this.metadataname)); 
                    }
                    comboDatas.push({
                        id: this.trgmetadataid,
                        text: this.metadataname
                    });                     
                });
            }
            if(resp.status === 'error')
                new PNotify({
                    type: resp.status,
                    title: resp.status,
                    text: resp.text,
                    sticker: false
                });                                
        },
        error: function() {
            alert("Error");
        }
    }).done(function(){
        _this.select2({results: comboDatas});
    });
};

Amactivity.prototype.insertFormExpressionTemplate = function(target) {
    var _thisObj = this;
    var dgFieldTd = $(target).closest('td'), expTitle = '';
    var dgIndex = _thisObj.getRowIndex(target);
    var rows = $(_thisObj.dataGridId, _thisObj.mainTemplateWindowId).datagrid('getRows');        
    var dgField = dgFieldTd.attr("field");
    var $dialogName = 'dialog-activity-expression-template';
    if (!$($dialogName).length) {
        $('<div id="' + $dialogName + '"></div>').appendTo("body");
    }
    var savedExpression = '';
    var savedProcess = '';
    if(rows[dgIndex][dgField] !== undefined && rows[dgIndex][dgField] !== null && rows[dgIndex][dgField] !== '')
        savedExpression = rows[dgIndex][dgField];
    
    if(rows[dgIndex][dgField+'_process'] !== undefined && rows[dgIndex][dgField+'_process'] !== null && rows[dgIndex][dgField+'_process'] !== '')
        savedProcess = rows[dgIndex][dgField+'_process'];
        
    for(var ii = 0; ii < _thisObj.templateHeader.length; ii++) {
        if(_thisObj.templateHeader[ii].field == dgField) {
            expTitle = _thisObj.templateHeader[ii].title;
        }
    }
        
    $.ajax({
        type: 'post',
        url: 'amactivity/activityExpressionForm',
        data: {rowMetaCode: dgField, rowMetaName: expTitle, expression: savedExpression, templateId: _thisObj.activityKeyId}, 
        dataType: 'json',
        beforeSend: function(){
            if (!$("link[href='middleware/assets/css/salary/expression.css']").length){
                $("head").append('<link rel="stylesheet" type="text/css" href="middleware/assets/css/salary/expression.css"/>');
            }
            Core.blockUI({
                message: 'Loading...', 
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
                title: "Томъёо оруулах",
                width: 1100,
                height: 'auto',
                modal: true,
                close: function(){
                    $("#" + $dialogName).empty().dialog('destroy').remove();
                },                        
                buttons: [
                    {text: plang.get('save_btn'), class: 'btn btn-sm blue', click: function() {          
                        var expArea = $("#" + $dialogName).find('.p-exp-area');
                        var expAreaContent = $.trim(expArea.html());                    
                        var expressionProcess = $("#expressionProcessAmactivityTemplate", "#" + $dialogName).val();
                        var expressionTemp = $("select[name='templateList']", "#" + $dialogName).val();
                        var expressionFact = $("select[name='factList']", "#" + $dialogName).val();
                        var expressionFactRow = $("select[name='factRowList']", "#" + $dialogName).val();
                        /*expressionCheck.keyup(function(){
                            if(expressionCheck.hasClass('error'))
                                expressionCheck.removeClass('error');
                        });
                        if(expressionCheck.val().trim() === '') {
                            expressionCheck.addClass('error').focus();
                            PNotify.removeAll();
                            new PNotify({
                              title: 'Warning',
                              text: 'Томъёогоо оруулна уу!',
                              type: 'warning',
                              sticker: false
                            });                              
                            return;
                        }*/       
                        $.ajax({
                            type: 'post',
                            url: 'amactivity/expressionActivityTemplateSaveCtrl',
                            data: {
                                id: rows[dgIndex].id,
                                rowKey: rows[dgIndex].rowkey,
                                expressionContent: expAreaContent,
                                processid: expressionProcess,
                                templateId: expressionTemp,
                                colId: expressionFact,
                                rowId: expressionFactRow,
                                fact: dgField,
                                rows: rows
                            },
                            dataType: "json",
                            success: function(resp) {
                                if(resp.status === 'success') {
                                    _thisObj.loadDataGridTemplate();
                                }
                                new PNotify({
                                    type: resp.status,
                                    title: resp.title,
                                    text: resp.text,
                                    sticker: false
                                });                                
                            },
                            error: function() {
                                alert("Error");
                            }
                        });                      
                        $("#" + $dialogName).dialog('close');
                    }},
                    {text: plang.get('close_btn'), class: 'btn btn-sm blue-hoki', click: function() {
                        $("#" + $dialogName).dialog('close');
                    }}
                ]
            });
            $("#" + $dialogName).dialog('open');
            Core.initSelect2($("#" + $dialogName));

            var comboDatas = [];
            var _this = $("#expressionProcessAmactivityTemplate", "#" + $dialogName);
            $.ajax({
                type: 'post',
                url: 'amactivity/processList',
                dataType: "json",
                success: function(resp) {
                    if(resp.status === 'success') {                
                        _this.empty();
                        _this.append($('<option />').val('').text('- Cонгох -'));  

                        $.each(resp.rows, function(){
                            if(savedProcess == this.trgmetadataid) {
                                _this.append($("<option />")
                                    .val(this.trgmetadataid)
                                    .text(this.metadataname)
                                    .attr("selected", "selected"));
                            } else {                    
                                _this.append($("<option />")
                                    .val(this.trgmetadataid)
                                    .text(this.metadataname)); 
                            }
                            comboDatas.push({
                                id: this.trgmetadataid,
                                text: this.metadataname
                            });                     
                        });
                    }
                    if(resp.status === 'error')
                        new PNotify({
                            type: resp.status,
                            title: resp.status,
                            text: resp.text,
                            sticker: false
                        });                                
                },
                error: function() {
                    alert("Error");
                }
            }).done(function(){
                _this.select2({results: comboDatas});
            });
            Core.unblockUI();
        },
        error: function () {
            alert("Error");
        }
    });    
};

Amactivity.prototype.viewerExpressionTemplate = function(target) {
    var _thisObj = this;
    var dgFieldTd = $(target).closest('td');
    var dgIndex = _thisObj.getRowIndex(target);
    var rows = $(_thisObj.dataGridId, _thisObj.mainTemplateWindowId).datagrid('getRows');        
    var dgField = dgFieldTd.attr("field");
    
    $(".activity-expression-viewer", this.mainTemplateWindowId).find('span').empty();
    if(rows[dgIndex][dgField] !== undefined && rows[dgIndex][dgField] !== null && rows[dgIndex][dgField] !== '')
        $(".activity-expression-viewer", this.mainTemplateWindowId).find('span').append(rows[dgIndex][dgField]);
};

Amactivity.prototype.comboDataSet = function(target) {
    var _this = $(target);
    var metaId = this.headerConfig[_this.closest('td').attr('field')+'_code'].metadataid;

    if(!_this.hasClass("data-combo-set") && metaId !== "") {
        _this.addClass("data-combo-set");
        Core.blockUI({
            target: _this.parent(),
            animate: false,
            icon2Only: true
        });

        var dropDownHtml = "<option value=''>- Сонгох -</option>";
        $.ajax({
            type: "POST",
            async: false,
            url: 'mdcommon/hardWindowComboData',
            data: {metaDataId: metaId},
            dataType: "json",
            success: function(data) {
                _this.empty();
                var editVal = _this.attr("data-edit-value");                        

                $.each(data, function(){
                    if(editVal == this.id)
                        dropDownHtml += "<option selected='selected' value='"+this.id+"'>"+this.name+"</option>";   
                    else    
                        dropDownHtml += "<option value='"+this.id+"'>"+this.name+"</option>";   
                });
            },
            error: function (xhr, status, error){
                alert("Ajax Error!");
            }            
        }).done(function(){
            _this.html(dropDownHtml);
            Core.unblockUI(_this.parent());
        });
    }
};

Amactivity.prototype.onSelectDate = function(date) {
    var _thisObj = this;    
    var rows = $(_thisObj.dataGridId, _thisObj.mainWindowId).datagrid('getRows');           
    var row = rows[_thisObj.activeIndex], dataRows = [];
    var date = new Date(date);
    var yyyy = date.getFullYear();
    var MM = date.getMonth() + 1;
    var dd = date.getDate();

    if (MM < 10) { MM = '0' + MM }
    if (dd < 10) { dd = '0' + dd }
    /*date = MM + "/" + dd + "/" + yyyy;        */
    date = yyyy + "/" + MM + "/" + dd;
    
    row[_thisObj.activeField] = date;
    dataRows.push(row);
    _thisObj.saveActivityAccountPartial(dataRows, 'noreload');    
};    

Amactivity.prototype.addFileActivity = function(elem) {
    var getDiv = $(elem).parent().parent();
    $(getDiv).append(
            '<div class="clearfix w-100"></div><div class="mt5">' +
            '<input type="file" name="activity_file[]" class="float-left" onchange="hasFileExtension(this);">' +
            '<a href="javascript:;" class="btn btn-xs btn-danger" title="Устгах" onclick="amactivityObj.removeActivityFile(this);"><i class="icon-cross2 font-size-12"></i></a>' +
            '</div>'
    );  
};    

Amactivity.prototype.editFileRemove = function(elem) {
    var $dialogName = 'dialog-activity-fileremove';
    if (!$($dialogName).length) {
        $('<div id="' + $dialogName + '"></div>').appendTo('body');
    }

    $("#" + $dialogName).empty().html("Та устгахдаа итгэлтэй байна уу?");
    $("#" + $dialogName).dialog({
        appendTo: "body",
        cache: false,
        resizable: true,
        bgiframe: true,
        autoOpen: false,
        title: "Сануулга",
        width: 350,
        height: 'auto',
        modal: true,
        close: function(){
            $("#" + $dialogName).empty().dialog('destroy').remove();
        },                        
        buttons: [
            {text: 'Тийм', class: 'btn btn-sm blue', click: function() {                            
                var getDiv = $(elem).parent();
                
                getDiv.find('input[name="activity_file_action[]"]').val('removed');
                getDiv.hide();
                
                $("#" + $dialogName).dialog('close');
            }},
            {text: 'Үгүй', class: 'btn btn-sm blue-hoki', click: function() {
                $("#" + $dialogName).dialog('close');
            }}
        ]
    });
    $("#" + $dialogName).dialog('open');    
};    

Amactivity.prototype.refreshFileMainHeader = function() {
    var _thisObj = this;
    
    $.ajax({
        type: 'POST',
        url: 'amactivity/refreshFileMainHeaderCtrl',
        data: { activityKeyId: _thisObj.activityKeyId },
        dataType: "json",
        beforeSend: function() {
            Core.blockUI({
                target: (".main-header-file-area", _thisObj.mainWindowId),
                animate: false,
                icon2Only: true
            });
        },
        success: function(resp) {
            $(".main-header-file-area", _thisObj.mainWindowId).empty().append(resp);
            Core.unblockUI((".main-header-file-area", _thisObj.mainWindowId));
        },
        error: function() {
            alert("Failed Ajax!");
        }
    });
};

Amactivity.prototype.saveTemplate = function() {
    var _thisObj = this;
    $(_thisObj.dataGridId, _thisObj.mainTemplateWindowId).datagrid('editCell', {
        index: 0,
        field: 'description'
    });    
    var postData = $(_thisObj.dataGridId, _thisObj.mainTemplateWindowId).datagrid('getRows');
    _thisObj.saveActivityAccountPartial(postData, 'template');
};

Amactivity.prototype.deleteCommonSelectableBasket = function(target) {
    var _thisObj = this;
    $('#commonSelectableBasketDataGrid', _thisObj.mainWindowId).datagrid('deleteRow', _thisObj.getRowIndex(target));
    $("#commonSelectedCount", _thisObj.mainWindowId).text($('#commonSelectableBasketDataGrid', _thisObj.mainWindowId).datagrid('getData').total);
};

var configedCellStyler = function(val, row, rowIndex){
    if(typeof row.cellstyle !== "undefined" && row.cellstyle !== null){
        return row.cellstyle;
    } else {
        return "";
    }
};

var initEventOpenClose=function(dataGrid, el, rowIndex){
    el.click(function(){
        var hasRightEl = el.hasClass("tree-collapsed");
        var rows = dataGrid.datagrid('getRows');
        var selectedRow = rows[rowIndex];
        $.each(rows, function(key, row){
            if(row.parentid === selectedRow.id){
                if(hasRightEl){
                    dataGrid.parents(".datagrid-view").find(".datagrid-view1 .datagrid-btable > tbody > tr").eq(key).show();
                    dataGrid.parents(".datagrid-view").find(".datagrid-view2 .datagrid-btable > tbody > tr").eq(key).show();
                } else {
                    dataGrid.parents(".datagrid-view").find(".datagrid-view1 .datagrid-btable > tbody > tr").eq(key).hide();
                    dataGrid.parents(".datagrid-view").find(".datagrid-view2 .datagrid-btable > tbody > tr").eq(key).hide();
                }
            }
        });
        if(hasRightEl){
            el.removeClass("tree-collapsed");
            el.addClass("tree-expanded");
        } else {
            el.removeClass("tree-expanded");
            el.addClass("tree-collapsed");
        }
    });
    el.show();
};

Amactivity.prototype.checkMetaDataTypeActivityFunction = function(dv, row) {
    var _thisObj = this;
    
    $.ajax({
        type: 'POST',
        url: 'amactivity/getDrillParams',
        data: { activityKeyId: _thisObj.activityKeyId, dvId: dv, row: JSON.parse(decodeURIComponent(row)) },
        dataType: "json",
        success: function(resp) {
            checkMetaDataTypeFunction(dv, null, 'Жагсаалт', null, 'Хаах', '', 'tab', encodeURIComponent(JSON.stringify(resp)));
        },
        error: function() {
            alert("Failed Ajax!");
        }
    });
};

Amactivity.prototype.amactivityColumnConfig = function() {
    var $dialogname = 'dialog-amactivity-column-config';
    var data = '', _thisObj = this, typeAllFields = [];

    Core.blockUI({
        message: 'Түр хүлээнэ үү...',
        boxed: true
    });         
    
    setTimeout(function(){
        $.ajax({
            type: 'post',
            url: 'amactivity/getColumnListCtrl',
            data: {
                activityKeyId: _thisObj.activityKeyId
            },
            dataType: "json",
            async: false,
            success: function (data) {
                typeAllFields = data;
            }
        });
        
        data = '<div class="row">'+
            '<form>'+
            '<div class="col-md-6">'+
                '<table class="table table-sm table-bordered table-hover bprocess-table-dtl mb10">'+
                    '<thead>'+
                        '<tr>'+
                            '<th class="rowNumber">№</th>'+
                            '<th class="rowNumber"></th>'+
                            '<th style="text-align: left;">Баганы нэр</th>'+
                        '</tr>'+
                    '</thead>'+
                    '<tbody>';
                        var ii = 1;
                        $.each(typeAllFields.period, function(k, v){
                            data += '<tr class="" style="display: table-row;">';
                            data += '<td class="">' + ii + '</td>';
                            data += '<td><input type="checkbox" name="amActivityColumnPeriodConfig[]" value="' + v.PERIOD_NAME + '"/></td>';
                            data += '<td>' + v.PERIOD_NAME + '</td>';
                            data += '</tr>';
                            ii++;
                        });
        data += '</tbody>'+
                '</table>'+
            '</div>'+
            '<div class="col-md-6">'+
                '<form>'+
                '<table class="table table-sm table-bordered table-hover bprocess-table-dtl mb10">'+
                    '<thead>'+
                        '<tr>'+
                            '<th class="rowNumber">№</th>'+
                            '<th class="rowNumber"></th>'+
                            '<th style="text-align: left;">Баганы нэр</th>'+
                        '</tr>'+
                    '</thead>'+
                    '<tbody>';
                        var iii = 1;
                        $.each(typeAllFields.fact, function(k, v){
                            data += '<tr class="" style="display: table-row;">';
                            data += '<td class="">' + iii + '</td>';
                            data += '<td><input type="checkbox" name="amActivityColumnConfig[]" value="' + v.FACT_FIELD_NAME + '"/></td>';
                            data += '<td>' + v.DESCRIPTION + '</td>';
                            data += '</tr>';
                            iii++;
                        });
        data += '</tbody>'+
                '</table>'+
            '</div>'+
            '</form>'+
        '</div>';

        if (!$('#'+$dialogname).length) {
            $('<div id="' + $dialogname + '"></div>').appendTo(_thisObj.mainAggregateWindowId);
        }
        var dialogname = $('#dialog-amactivity-column-config');

        dialogname.empty().html(data);            
        dialogname.dialog({
            appendTo: _thisObj.mainAggregateWindowId,
            cache: false,
            resizable: true,
            bgiframe: true,
            autoOpen: false,
            title: 'Баганы тохиргоо',
            width: 600,
            height: 'auto',
            modal: true,
            open: function () {          
                $('div[aria-describedby=' + $dialogname + '] .ui-dialog-buttonset').addClass("btn-group float-right");
                $('div[aria-describedby=' + $dialogname + '] .ui-dialog-buttonset').find('button:eq(0)').addClass('btn btn-sm blue mr0 addEmployeeListToDataGrid');
                $('div[aria-describedby=' + $dialogname + '] .ui-dialog-buttonset').find('button:eq(1)').addClass('btn blue-hoki btn-sm ml5');
                dialogname.find('input[type="checkbox"]').uniform();
            },
            close: function (elem) {
                dialogname.dialog('close');
            },
            buttons: [
                {text: 'Харах', click: function (elem) {
                    Core.blockUI({
                        message: 'Түр хүлээнэ үү...',
                        boxed: true
                    });                            
                    
                    var formData = $(this).closest('.ui-dialog').find('form').serialize();
                    $('.dataExportExcelBtnAmactivity', _thisObj.mainAggregateWindowId).attr('data-formdata', formData);
                    _thisObj.loadDataGridAggregate(formData);
                    dialogname.dialog('close');
                }},
                {text: plang.get('close_btn'), click: function () {
                    dialogname.dialog('close');
                }}
            ]
        });
        dialogname.dialog('open');
        Core.unblockUI();
    }, 50);
}

Amactivity.prototype.amactivityColumnConfig2 = function() {
    var $dialogname = 'dialog-amactivity-column-config2';
    var data = '', _thisObj = this, typeAllFields = [];

    Core.blockUI({
        message: 'Түр хүлээнэ үү...',
        boxed: true
    });         
    
    setTimeout(function(){
        $.ajax({
            type: 'post',
            url: 'amactivity/getColumnListCtrl',
            data: {
                activityKeyId: _thisObj.activityKeyId
            },
            dataType: "json",
            async: false,
            success: function (data) {
                typeAllFields = data;
            }
        });
        
        data = '<div class="row">'+
            '<form class="w-100">'+
            '<div class="col-md-6">'+
                '<table class="table table-sm table-bordered table-hover bprocess-table-dtl mb10">'+
                    '<thead>'+
                        '<tr>'+
                            '<th class="rowNumber">№</th>'+
                            '<th class="rowNumber"></th>'+
                            '<th style="text-align: left;">Баганы нэр</th>'+
                        '</tr>'+
                    '</thead>'+
                    '<tbody>';
                        var ii = 1, pcheck = '';
                        $.each(typeAllFields.period, function(k, v){
                            pcheck = v.CHECKED == '1' ? ' checked' : '';
                            
                            data += '<tr class="" style="display: table-row;">';
                            data += '<td class="">' + ii + '</td>';
                            data += '<td><input type="checkbox"' + pcheck + ' name="amActivityColumnPeriodConfig[]" value="' + v.PERIOD_NAME + '"/></td>';
                            data += '<td>' + v.PERIOD_NAME + '</td>';
                            data += '</tr>';
                            ii++;
                        });
        data += '</tbody>'+
                '</table>'+
            '</div>'+
            '<div class="col-md-6">'+
                '<form class="w-100">'+
                '<table class="table table-sm table-bordered table-hover bprocess-table-dtl mb10 hidden">'+
                    '<thead>'+
                        '<tr>'+
                            '<th class="rowNumber">№</th>'+
                            '<th class="rowNumber"></th>'+
                            '<th style="text-align: left;">Баганы нэр</th>'+
                        '</tr>'+
                    '</thead>'+
                    '<tbody>';
                        var iii = 1, fcheck = '';
                        $.each(typeAllFields.fact, function(k, v){
                            fcheck = v.CHECKED == '1' ? ' checked' : '';
                            
                            data += '<tr class="" style="display: table-row;">';
                            data += '<td class="">' + iii + '</td>';
                            data += '<td><input type="checkbox"' + fcheck + ' name="amActivityColumnConfig[]" value="' + v.FACT_FIELD_NAME + '"/></td>';
                            data += '<td>' + v.DESCRIPTION + '</td>';
                            data += '</tr>';
                            iii++;
                        });
        data += '</tbody>'+
                '</table>'+
            '</div>'+
            '</form>'+
        '</div>';

        if (!$('#'+$dialogname).length) {
            $('<div id="' + $dialogname + '"></div>').appendTo(_thisObj.mainAggregate2WindowId);
        }
        var dialogname = $('#dialog-amactivity-column-config2');

        dialogname.empty().html(data);            
        dialogname.dialog({
            appendTo: _thisObj.mainAggregate2WindowId,
            cache: false,
            resizable: true,
            bgiframe: true,
            autoOpen: false,
            title: 'Баганы тохиргоо',
            width: 600,
            height: 'auto',
            modal: true,
            open: function () {          
                $('div[aria-describedby=' + $dialogname + '] .ui-dialog-buttonset').addClass("btn-group float-right");
                $('div[aria-describedby=' + $dialogname + '] .ui-dialog-buttonset').find('button:eq(0)').addClass('btn btn-sm blue mr0 addEmployeeListToDataGrid');
                $('div[aria-describedby=' + $dialogname + '] .ui-dialog-buttonset').find('button:eq(1)').addClass('btn blue-hoki btn-sm ml5');
                dialogname.find('input[type="checkbox"]').uniform();
            },
            close: function (elem) {
                dialogname.dialog('close');
            },
            buttons: [
                {text: 'Харах', click: function (elem) {
                    Core.blockUI({
                        message: 'Түр хүлээнэ үү...',
                        boxed: true
                    });                            
                    
                    var formData = $(this).closest('.ui-dialog').find('form').serialize();
                    $('.dataExportExcelBtnAmactivity', _thisObj.mainAggregate2WindowId).attr('data-formdata', formData);
                    _thisObj.loadDataGridAggregate2(formData);
                    dialogname.dialog('close');
                }},
                {text: plang.get('close_btn'), click: function () {
                    dialogname.dialog('close');
                }}
            ]
        });
        dialogname.dialog('open');
        Core.unblockUI();
    }, 50);
}