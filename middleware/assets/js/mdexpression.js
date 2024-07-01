var sysConfigTmpObj = {}, pfFullExpSetFieldValue = true;
function checkFiscalPeriod(date) {
    var result = false;
    $.ajax({
        type: 'post',
        url: 'mdwebservice/checkFiscalPeriod',
        data: {date: date},
        async: false,
        success: function(data){
            if ($.trim(data) === 'true') {
                result = true;
            }
        }
    });
    return result;
}
function isClosedFiscalPeriod(date, accountORdepartment) {
    var result = false, accountORdepartment = typeof accountORdepartment === 'undefined' ? '' : accountORdepartment;
    $.ajax({
        type: 'post',
        url: 'mdcommon/isClosedFiscalPeriodByPost',
        data: {date: date, accountORdepartment: accountORdepartment},
        async: false,
        success: function(data){
            if ($.trim(data) === 'true') {
                result = true;
            }
        }
    });
    return result;
}
function dateDiff(type, fromDate, toDate){
    type = type.toLowerCase();
    if (fromDate !== '' && toDate !== '') {
        
        if (type === 'day') {
            
            if (fromDate == 'sysdate') {
                var dateFrom = strtotime(date('Y-m-d'));
            } else {
                var dateFrom = strtotime(fromDate);
            }
            if (toDate == 'sysdate') {
                var dateTo = strtotime(date('Y-m-d'));
            } else {
                var dateTo = strtotime(toDate);
            }
            
            var difference = dateFrom - dateTo;
            var datediff = Math.floor(difference / 86400);
            
            return datediff;
            
        } else if (type === 'workingday') {
            
            if (fromDate == 'sysdate') {
                var dateFrom = strtotime(date('Y-m-d'));
            } else {
                var dateFrom = strtotime(fromDate);
            }
            if (toDate == 'sysdate') {
                var dateTo = strtotime(date('Y-m-d'));
            } else {
                var dateTo = strtotime(toDate);
            }
            
            var fromYear = date('Y', dateFrom);
            var fromMonth = date('m', dateFrom);
            var fromDay = date('d', dateFrom);
            var toYear = date('Y', dateTo);
            var toMonth = date('m', dateTo);
            var toDay = date('d', dateTo);
            
            var startDate = new Date(toYear, toMonth, toDay);
            var endDate = new Date(fromYear, fromMonth, fromDay);
            var count = 0;
            var curDate = new Date(startDate.getTime());
            
            while (curDate <= endDate) {
                var dayOfWeek = curDate.getDay();
                if (dayOfWeek !== 0 && dayOfWeek !== 6) count++;
                curDate.setDate(curDate.getDate() + 1);
            }
            
            return count;
            
        } else if (type === 'month') {
            
            if (fromDate == 'sysdate') {
                var dateFrom = strtotime(date('Y-m-d'));
            } else {
                var dateFrom = strtotime(fromDate);
            }
            if (toDate == 'sysdate') {
                var dateTo = strtotime(date('Y-m-d'));
            } else {
                var dateTo = strtotime(toDate);
            }
            
            var fromYear = date('Y', dateFrom);
            var fromMonth = date('m', dateFrom);
            var fromDay = date('d', dateFrom);
            var toYear = date('Y', dateTo);
            var toMonth = date('m', dateTo);
            var toDay = date('d', dateTo);
            
            var date1 = new Date(fromYear, fromMonth, fromDay);
            var date2 = new Date(toYear, toMonth, toDay);
            
            var year1 = date1.getFullYear();
            var year2 = date2.getFullYear();
            var month1 = date1.getMonth();
            var month2 = date2.getMonth();
            if (month1 === 0) { 
                month1++;
                month2++;
            }
            var numberOfMonths = (year2 - year1) * 12 + (month2 - month1);
            
            return numberOfMonths;
    
        } else if (type === 'monthfraction') {
            
            if (fromDate == 'sysdate') {
                var dateFrom = new Date(date('Y/m/d'));
            } else {
                var dateFrom = new Date(date('Y/m/d', strtotime(fromDate)));
            }
            
            if (toDate == 'sysdate') {
                var dateTo = new Date(date('Y/m/d'));
            } else {
                var dateTo = new Date(date('Y/m/d', strtotime(toDate)));
            }
            
            var differenceMonths = (dateTo.getDate() - dateFrom.getDate()) / 30 + dateTo.getMonth() - dateFrom.getMonth() + (12 * (dateTo.getFullYear() - dateFrom.getFullYear()));
            
            return Number(Math.round(differenceMonths+'e2')+'e-2');
            
        } else if (type === 'year') {
            
            if (fromDate == 'sysdate') {
                var dateFrom = strtotime(date('Y-m-d'));
            } else {
                var dateFrom = strtotime(fromDate);
            }
            if (toDate == 'sysdate') {
                var dateTo = strtotime(date('Y-m-d'));
            } else {
                var dateTo = strtotime(toDate);
            }
            
            var difference = dateFrom - dateTo;
            var datediff = Math.floor(difference / 31536000);
            
            return datediff;
            
        } else if (type === 'yearfraction') {
            
            if (fromDate == 'sysdate') {
                var dateFrom = strtotime(date('Y-m-d'));
            } else {
                var dateFrom = strtotime(fromDate);
            }
            if (toDate == 'sysdate') {
                var dateTo = strtotime(date('Y-m-d'));
            } else {
                var dateTo = strtotime(toDate);
            }
            
            var difference = dateFrom - dateTo;
            var datediff = difference / 31536000;
            
            return Number(Math.round(datediff+'e2')+'e-2');
            
        } else if (type === 'hourminute') {
            
            if (fromDate == 'sysdate') {
                var dateFrom = new Date(date('Y/m/d H:i:s')).getTime();
            } else {
                var dateFrom = new Date('2007/01/01 '+fromDate+':00').getTime();
            }
            
            if (toDate == 'sysdate') {
                var dateTo = new Date(date('Y/m/d H:i:s')).getTime();
            } else {
                var dateTo = new Date('2007/01/01 '+toDate+':00').getTime();
            }
            
            var hourDiff = dateTo - dateFrom; //in ms
            var minDiff = hourDiff / 60 / 1000; //in minutes
            var hDiff = hourDiff / 3600 / 1000; //in hours
            var humanReadable = {};
            humanReadable.hours = Math.floor(hDiff);
            humanReadable.minutes = minDiff - 60 * humanReadable.hours;

            return humanReadable;
            
        } else if (type === 'hour') {
            
            if (fromDate == 'sysdate') {
                var dateFrom = new Date(date('Y-m-d'));
            } else {
                var dateFrom = new Date(fromDate);
            }
            if (toDate == 'sysdate') {
                var dateTo = new Date(date('Y-m-d'));
            } else {
                var dateTo = new Date(toDate);
            }
            
            if (dateFrom <= dateTo) {
                return Number(Math.round((Math.abs(dateFrom - dateTo) / 36e5)+'e2')+'e-2');
            }
        }
    }
    return null;
}
function dateModify($date, days) {
    
    if ($date == 'sysdate') {
        var $date = bpGetDate('sysdate');
    }
    
    if (days.indexOf('.') !== -1) {
        
        var daysLower = days.toLowerCase();
        
        if (daysLower.indexOf('year') !== -1) {
            var firstChar = daysLower.substr(0, 1);
            var dayNumber = Number(daysLower.replace(/[^\d\.]*/g, ''));
            var weekNumber = Math.ceil(52.177457 * dayNumber * 168);
            
            if (firstChar == '+' || firstChar == '-') {
                days = firstChar + '' + weekNumber + ' hour';
            } else {
                days = '+' + weekNumber + ' hour';
            }
        }
    }

    var modify = strtotime(days, strtotime($date));
    return date('Y-m-d', modify);
}
function bpDateTimeModify(dateTime, days) {
    if (dateTime == 'sysdatetime') {
        var dateTime = bpGetDate('sysdatetime');
    }
    
    if (days.indexOf('.') !== -1) {
        
        var daysLower = days.toLowerCase();
        
        if (daysLower.indexOf('year') !== -1) {
            var firstChar = daysLower.substr(0, 1);
            var dayNumber = Number(daysLower.replace(/[^\d\.]*/g, ''));
            var weekNumber = Math.ceil(52.177457 * dayNumber * 168);
            
            if (firstChar == '+' || firstChar == '-') {
                days = firstChar + '' + weekNumber + ' hour';
            } else {
                days = '+' + weekNumber + ' hour';
            }
        } else if (daysLower.indexOf('hour') !== -1) {
            
            var firstChar = daysLower.substr(0, 1);
            var hourNumber = Number(daysLower.replace(/[^\d\.]*/g, ''));
            var minutes = Math.ceil(hourNumber * 60);
            
            if (firstChar == '+' || firstChar == '-') {
                days = firstChar + '' + minutes + ' minutes';
            } else {
                days = '+' + minutes + ' minutes';
            }
        }
    }
    
    var modify = strtotime(days, strtotime(dateTime));
    return date('Y-m-d H:i:s', modify);
}
function bpGetDate(dateType) {
    var result = '';
    $.ajax({
        type: 'post',
        url: 'api/getdate',
        data: {dateType: dateType},
        async: false,
        success: function(data){
            result = data;
        }
    });
    return result;
}
function bpGetUid() {
    var result = '';
    $.ajax({
        type: 'post',
        url: 'api/getuid',
        async: false,
        success: function(data){
            result = data;
        }
    });
    return result;
}
function bpTimeAddMinute(startTime, durationInMinutes) {
    if (startTime == '') {
        return '';
    }
    
    var endTime = moment(startTime, 'HH:mm').add(durationInMinutes, 'minutes').format('HH:mm');
    if (endTime) {
        return endTime;
    } 
    return '';
}
function getBpElement(mainSelector, elem, fieldPath) {
    if (elem === 'open') {
        var $getPathElement = mainSelector.find("[data-path='" + fieldPath + "']");
        if ($getPathElement.length) {
            return $getPathElement;
        }
    } else {
        var $elem = $(elem);    
        if ($elem.closest('.sidebar_detail').length) {
            var $oneLevelRow = mainSelector.find('.bprocess-table-dtl > .tbody > .currentTarget');
        } else {
            var $oneLevelRow = $elem.closest('.bp-detail-row');   
            if ($oneLevelRow.find("[data-path='" + fieldPath + "']").length == 0) {
                $oneLevelRow = $oneLevelRow.parents('.bp-detail-row');
            }
        }

        var $getPathElement = $oneLevelRow.find("[data-path='" + fieldPath + "']");

        if ($getPathElement.length) {
            return $getPathElement;
        } else {   
            var $getPathMainElement = mainSelector.find("[data-path='" + fieldPath + "']");
            if ($getPathMainElement.length) {
                return $getPathMainElement;
            }
        }
    }
    return false;
}
function showGL(mainSelector, glParam, elem) {
    var $bpWindow = mainSelector.children('form'), $bpTabs = $bpWindow.find('.bp-tabs'), bpTabLength = $bpTabs.length;
            
    if (glParam == 1) {
        $bpWindow.validate({ 
            ignore: '', 
            highlight: function(element) {
                
                var $vel = $(element);
                if ($vel.is(':radio')) {
                    $vel.closest('.radio-list').addClass('error');
                } else {
                    $vel.addClass('error');
                    $vel.parent().addClass('error');
                }
            
                if ($bpWindow.find("div.tab-pane:hidden:has(.error)").length) {
                    $bpWindow.find("div.tab-pane:hidden:has(.error)").each(function(index, tab){
                        var tabId = $(tab).attr("id");
                        $bpWindow.find('a[href="#'+tabId+'"]').tab('show');
                    });
                }
            },
            unhighlight: function(element) {
                var $vel = $(element);
                if ($vel.is(':radio')) {
                    $vel.closest('.radio-list').removeClass('error');
                } else {
                    $vel.removeClass('error');
                    $vel.parent().removeClass('error');
                }
            },
            errorPlacement: function(){} 
        });
        
        if ($bpWindow.valid()) {
            
            var $requiredInputs = $bpWindow.find('.bprocess-table-dtl > .tbody [required]:not(:radio)').filter(function() { return this.value == ''; }); 
            if ($requiredInputs.length) {
                $requiredInputs.each(function(){
                    var $requiredInput = $(this);
                    $requiredInput.parent().addClass('error');
                    $requiredInput.addClass('error');
                    if ($bpWindow.find("div.tab-pane:hidden:has(.error)").length) {
                        $bpWindow.find("div.tab-pane:hidden:has(.error):eq(0)").each(function(index, tab) {
                            var tabId = $(tab).attr("id");
                            $bpWindow.find('a[href="#'+tabId+'"]').tab('show');
                        });
                    }
                });
                return;
            }
                                        
            $.ajax({
                type: 'post',
                url: 'mdgl/getTemplate',
                data: $bpWindow.serialize() + "&glBpMainWindowIdProcess=_&bpTabLength="+bpTabLength,
                dataType: 'json',
                beforeSend: function () {
                    Core.blockUI({message: 'Loading...', boxed: true});
                },
                success: function (data) {
                    /*PNotify.removeAll();*/
                    
                    if (data.status == 'success') {
                        
                        if ($bpWindow.find('.glTemplateSectionProcess').length) {
                            $bpWindow.find('.glTemplateSectionProcess').remove();
                        }
                        
                        if (bpTabLength) {
                            
                            if ($bpTabs.find(".nav-tabs:eq(0)").find('li.glTabLi').length == 0) {
                                
                                var uqId = getUniqueId(1);
                                
                                $bpTabs.find(".nav-tabs:eq(0)").append('<li class="nav-item glTabLi">'+
                                    '<a href="#gl_tab_'+uqId+'" data-toggle="tab" class="nav-link">'+plang.get('FIN_01030')+'</a>'+
                                '</li>');
                                $bpTabs.find(".tab-content:eq(0)").append('<div id="gl_tab_'+uqId+'" class="tab-pane glTabPane">'+
                                    data.Html+
                                '</div>');
                                
                                $bpTabs.find('.nav-tabs:eq(0) > li > a[href="#gl_tab_'+uqId+'"]').tab('show');
                                
                            } else {
                                var $glTabLiAnchor = $bpTabs.find(".nav-tabs:eq(0)").find("li.glTabLi > a");
                                var glTabLiAnchorId = $glTabLiAnchor.attr('href');
                                $bpTabs.find(".tab-content:eq(0) > div"+glTabLiAnchorId).html(data.Html);
                                
                                $bpTabs.find('.nav-tabs:eq(0) > li > a[href="'+glTabLiAnchorId+'"]').tab('show');
                            }
                            
                        } else {
                            var html = data.Html;
                            if (mainSelector.hasClass('bp-layout')) {
                                html = html.replace('class="glTemplateSectionProcess"', 'class="glTemplateSectionProcess d-none"');
                            }
                            $bpWindow.find('div#bprocessCoreParam').before(html);
                        }
                    } else {
                        new PNotify({
                            title: 'Error',
                            text: data.text,
                            type: 'error',
                            addclass: pnotifyPosition,
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
    } else if (glParam == 0) {
        if ($bpWindow.find(".glTemplateSectionProcess").length) {
            $bpWindow.find(".glTemplateSectionProcess").remove();
            $bpTabs.find(".nav-tabs:eq(0)").find("li.glTabLi").remove();
            $bpTabs.find(".tab-content:eq(0)").find("div.glTabPane").remove();
        }
    } else {
        $bpWindow.validate({ 
            ignore: '', 
            highlight: function(element) {
                var $vel = $(element);
                if ($vel.is(':radio')) {
                    $vel.closest('.radio-list').addClass('error');
                } else {
                    $vel.addClass('error');
                    $vel.parent().addClass('error');
                }
                if ($bpWindow.find("div.tab-pane:hidden:has(.error)").length) {
                    $bpWindow.find("div.tab-pane:hidden:has(.error)").each(function(index, tab){
                        var tabId = $(tab).attr("id");
                        $bpWindow.find('a[href="#'+tabId+'"]').tab('show');
                    });
                }
            },
            unhighlight: function(element) {
                var $vel = $(element);
                if ($vel.is(':radio')) {
                    $vel.closest('.radio-list').removeClass('error');
                } else {
                    $vel.removeClass('error');
                    $vel.parent().removeClass('error');
                }
            },
            errorPlacement: function(){} 
        });
        
        if ($bpWindow.valid()) {
            
            var $requiredInputs = $bpWindow.find('.bprocess-table-dtl > .tbody [required]:not(:radio)').filter(function() { return this.value == ''; }); 
            if ($requiredInputs.length) {
                $requiredInputs.each(function(){
                    var $requiredInput = $(this);
                    $requiredInput.parent().addClass('error');
                    $requiredInput.addClass('error');
                    if ($bpWindow.find("div.tab-pane:hidden:has(.error)").length) {
                        $bpWindow.find("div.tab-pane:hidden:has(.error):eq(0)").each(function(index, tab) {
                            var tabId = $(tab).attr("id");
                            $bpWindow.find('a[href="#'+tabId+'"]').tab('show');
                        });
                    }
                });
                return;
            }
            
            var bookId = glParam, bookObject = $bpWindow.find("input[data-path='objectId']").val();
            
            $.ajax({
                type: 'post',
                url: 'mdgl/getTemplateByEditMode',
                data: {
                    id: bookId, 
                    objectid: bookObject,
                    glBpMainWindowIdProcess: '_', 
                    bpTabLength: bpTabLength, 
                    isFromBook: '1' 
                },
                dataType: 'json',
                beforeSend: function () {
                    Core.blockUI({message: 'Loading...', boxed: true});
                },
                success: function (data) {
                    /*PNotify.removeAll();*/
                    
                    if (data.status == 'success') {
                        if ($bpWindow.find(".glTemplateSectionProcess").length) {
                            $bpWindow.find(".glTemplateSectionProcess").remove();
                        }
                        
                        if (bpTabLength) {
                            
                            var uqId = getUniqueId(1);
                            
                            if ($bpTabs.find(".nav-tabs:eq(0)").find('li.glTabLi').length == 0) {
                                $bpTabs.find(".nav-tabs:eq(0)").append('<li class="nav-item glTabLi">'+
                                    '<a href="#gl_tab_'+uqId+'" class="nav-link" data-toggle="tab">'+plang.get('FIN_01030')+'</a>'+
                                '</li>');
                                $bpTabs.find(".tab-content:eq(0)").append('<div id="gl_tab_'+uqId+'" class="tab-pane glTabPane">'+
                                    data.Html+
                                '</div>');
                            }
                            
                            $bpTabs.find('.nav-tabs:eq(0) > li > a[href="#gl_tab_'+uqId+'"]').tab('show');
                            
                        } else {
                            var html = data.Html;
                            if (mainSelector.hasClass('bp-layout')) {
                                html = html.replace('class="glTemplateSectionProcess"', 'class="glTemplateSectionProcess d-none"');
                            }
                            $bpWindow.find('div#bprocessCoreParam').before(html);
                        }
                        
                    } else {
                        new PNotify({
                            title: 'Error',
                            text: data.text,
                            type: 'error',
                            addclass: pnotifyPosition,
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
    }
    return;
}
function bpGetOpenParam(mainSelector, code) {
    var openParams = mainSelector.find("input[id='openParams']").val();
    
    if (openParams !== '') {
        
        openParams = JSON.parse(openParams.replace('&quot;', '"'));
        code = code.toLowerCase();
        
        if (code == 'code') {
            return (openParams.hasOwnProperty('callerType')) ? openParams.callerType : null; 
        } else if (code == 'isworkflow') {
            if (openParams.hasOwnProperty('isWorkFlow') && (openParams.isWorkFlow == 'true' || openParams.isWorkFlow == true)) {
                return true;
            }
            return false;
        } else if (code == 'ismenu') {
            if (openParams.hasOwnProperty('isMenu') && (openParams.isMenu == 'true' || openParams.isMenu == true)) {
                return true;
            }
            return false;
        } else if (code == 'isdatatemplate') {
            if (openParams.hasOwnProperty('isDataTemplate') && (openParams.isDataTemplate == 'true' || openParams.isDataTemplate == true)) {
                return true;
            }
            return false;
        } else if (code == 'wfmstatusid') {
            if (openParams.hasOwnProperty('wfmStatusId')) {
                return openParams.wfmStatusId;
            }
            return null;
        } else if (code == 'wfmstatuscode') {
            if (openParams.hasOwnProperty('wfmStatusCode')) {
                return openParams.wfmStatusCode;
            }
            return null;
        } else if (code == 'wfmstatusname') {
            if (openParams.hasOwnProperty('wfmStatusName')) {
                return openParams.wfmStatusName;
            }
            return null;
        } else if (code == 'isdrilldown') {
            if (openParams.hasOwnProperty('isDrillDown') && (openParams.isDrillDown == 'true' || openParams.isDrillDown == true)) {
                return true;
            }
            return false;
        } else if (code == 'drilldownpath') {
            if (openParams.hasOwnProperty('drillDownPath')) {
                return openParams.drillDownPath;
            }
            return null;
        } else if (code == 'taskflowid') {
            if (openParams.hasOwnProperty('taskFlowId')) {
                return openParams.taskFlowId;
            }
            return null;
        } else if (code == 'taskflowcode') {
            if (openParams.hasOwnProperty('taskFlowCode')) {
                return openParams.taskFlowCode;
            }
            return null;
        } 
    }
    return null;
}
function bpClearCacheMetaFullExp(mainSelector, elem, metaDataId) {
    var response = $.ajax({
        type: 'post',
        url: 'mdmeta/clearCacheMetaFullExp', 
        data: {metaDataId: metaDataId},
        dataType: 'json',
        async: false
    });

    return response.responseJSON;
}
function bpClearCacheKpiTemplate(mainSelector, elem, templateId) {
    var response = $.ajax({
        type: 'post',
        url: 'mdmeta/clearCacheKpiTemplate', 
        data: {templateId: templateId},
        dataType: 'json',
        async: false
    });

    return response.responseJSON;
}
function bpClearCacheFiscalPeriod() {
    var response = $.ajax({
        type: 'post',
        url: 'mdmeta/clearCacheFiscalPeriod', 
        dataType: 'json',
        async: false
    });

    return response.responseJSON;
}
function bpSetRowIndex(elem, typeTheme) {
    if (typeof typeTheme !== 'undefined' && typeTheme == '1') {
        var $el = elem.find('.bprocess-table-dtl-theme:eq(0) > .bp-new-dtltheme');
    } else {
        var $el = elem.find('.bprocess-table-dtl:eq(0) > .tbody > .bp-detail-row');
    }
    
    var len = $el.length, i = 0;
    
    for (i; i < len; i++) { 
        var $subElement = $($el[i]).find('input, select, textarea');
        var slen = $subElement.length, j = 0;
        for (j; j < slen; j++) { 
            var $inputThis = $($subElement[j]);
            var $inputName = $inputThis.attr('name');
            if (typeof $inputName !== 'undefined') {
                $inputThis.attr('name', $inputName.replace(/^(.*)(\[[0-9]+\])(.*)$/, '$1[' + i + ']$3'));
            }
        }
    }
    
    return;
}
function bpSetRowIndexDepth(elem, window, rowIndex) {
    var activeTRindex = (typeof rowIndex === 'undefined') ? (window.find('.bprocess-table-dtl > .tbody').find('.currentTarget').length > 0 ? window.find('.bprocess-table-dtl > .tbody').find('.currentTarget').index() : 0) : rowIndex;
    var $parentElement = $('.bprocess-table-dtl', elem).length ? $('.bprocess-table-dtl', elem) : elem, rrrows = false;

    if ($(elem).parents('.bprocess-table-dtl').length === 2 && !$(elem).closest('.bprocess-table-dtl').hasClass('bprocess-table-row') && !$(elem).closest('.bprocess-table-dtl').closest('.bprocess-table-dtl').hasClass('bprocess-table-row')) {
        rrrows = true;
    }
    
    $parentElement.each(function () {
        var $tblThis = $(this);
        var isRows = true;
        if ($tblThis.closest(".bprocess-table-row").length > 0) {
            isRows = false;
        }
        
        $tblThis.find(".tbody:eq(0) > .bp-detail-row").each(function (i) {
            var $rowThis = $(this);
            $rowThis.find("input, select, textarea").each(function () {
                var $inputThis = $(this);
                var $inputName = $inputThis.attr('name');

                if (typeof $inputName !== 'undefined') {
                    if (isRows) {
                        if (rrrows) {
                            $inputThis.attr('name', $inputName.replace(/^(.*)(\[[0-9]+\])(\[\])$/, '$1[' + activeTRindex + '][' + $(elem).closest('tr').index() + ']$3'));
                        } else {
                            if ($inputThis.attr('type') == 'file') {
                                $inputThis.attr('name', $inputName.replace(/^(.*)(\[[0-9]+\])(\[\])$/, '$1[' + activeTRindex + ']['+i+']'));
                            } else {
                                $inputThis.attr('name', $inputName.replace(/^(.*)(\[[0-9]+\])(.*)$/, '$1[' + activeTRindex + ']$3'));
                            }
                        }
                    } else {
                        $inputThis.attr('name', $inputName.replace(/^(.*)(\[[0-9]+\])(.*)$/, '$1[' + i + ']$3'));
                    }
                }
            });
        });
    });
}
function bpChangeColumnName(mainSelector, elem, fieldName, changeName) {
    var $getColumnHead = mainSelector.find("th[data-cell-path='"+fieldName+"']:eq(0)");
    if ($getColumnHead.length) {
        if ($getColumnHead.find('button').length) {
            $getColumnHead.find('span').text(changeName);
        } else {
            $getColumnHead.text(changeName);
        }
        mainSelector.find("input[type='text'][data-path='"+fieldName+"'], textarea[data-path='"+fieldName+"']").attr('placeholder', changeName);
    }
    return;
}
function bpChangeLabelName(mainSelector, elem, fieldName, changeName) {
    var $getLabelName = mainSelector.find("label[data-label-path='"+fieldName+"']");
    if ($getLabelName.length) {
        if ($getLabelName.find('.required').length) {
            $getLabelName.empty().append('<span class="required">*</span> ' + changeName + (changeName !== '' ? ':' : ''));
        } else {
            $getLabelName.text(changeName + (changeName !== '' ? ':' : ''));
        }
        mainSelector.find("input[type='text'][data-path='"+fieldName+"'], textarea[data-path='"+fieldName+"']").attr('placeholder', changeName);
    }
    return;
}
function bpChangeTitleName(mainSelector, elem, fieldName, changeName) {
    var $getTitleName = mainSelector.find("[data-path='"+fieldName+"']");
    if ($getTitleName.length) {
        $getTitleName.attr('title', changeName);
    }
    return;
}
function bpChangeGroupName(mainSelector, fieldName, changeName) {
    var $getGroupName = mainSelector.find("div[data-section-path='"+fieldName+"']"),
        $getGroupNameTreeView = mainSelector.find("li[data-li-path='"+fieldName+"']");
    
    if ($getGroupName.length > 0 && $getGroupName.find('.theme-grid-title').length > 0) {
        $getGroupName.find('.theme-grid-title').children('.section-title').text(changeName);
        
    } else if ($getGroupNameTreeView.length > 0) {
        var getIcon = $getGroupNameTreeView.children('a').children()[0].outerHTML;
        var getIcon2 = $getGroupName.children('p').children()[0].outerHTML;
        $getGroupNameTreeView.children('a').html(getIcon + ' ' + changeName);
        $getGroupName.children('p').html(getIcon2 + ' ' + changeName);
    }
    return;
}
function bpChangeTabName(mainSelector, tabIndex, changeName) {
    tabIndex = Number(tabIndex) - 1;
    var $getTab = mainSelector.find("div.bp-tabs > ul > li > a[data-toggle='tab']:eq("+tabIndex+")");
    if ($getTab.length > 0) {
        $getTab.text(changeName);
    }
    return;
}
function bpDisableEnterAddRow(mainSelector, groupPath) {
    mainSelector.find("[data-table-path='"+groupPath+"']").attr('data-disable-enter-addrow', 'true');
    return;
}
function bpEnableEnterAddRow(mainSelector, groupPath) {
    mainSelector.find("[data-table-path='"+groupPath+"']").removeAttr('data-disable-enter-addrow');
    return;
}
function bpDetailGroupBtn(mainSelector, elem, groupPath, displayType) {
    
    if (displayType == 'show') {
        if (elem == 'open') {
            if (groupPath == 'pfSubDetailButton') {
                mainSelector.find('.bp-btn-subdtl:eq(0)').css({'display': ''});
            } else { 
                mainSelector.find("td[data-cell-path='" + groupPath + "'] > .btn:eq(0)").css({'display': ''});
            }
        } else {
            if (elem.hasClass('bp-detail-row')) {
                var $oneLevelRow = elem;
            } else {
                var $oneLevelRow = elem.closest('.bp-detail-row');
            }
            
            if (groupPath == 'pfSubDetailButton') {
                $oneLevelRow.find('.bp-btn-subdtl:eq(0)').css({'display': ''});
            } else {
                if ($oneLevelRow.find("[data-path='" + groupPath + "']").length) {
                    $oneLevelRow.find("td[data-cell-path='" + groupPath + "'] > .btn:eq(0)").css({'display': ''});
                } else {
                    mainSelector.find("td[data-cell-path='" + groupPath + "'] > .btn:eq(0)").css({'display': ''});
                }
            }
        }
    } else {
        if (elem == 'open') {
            if (groupPath == 'pfSubDetailButton') {
                mainSelector.find('.bp-btn-subdtl:eq(0)').css({'display': 'none'});
            } else {
                mainSelector.find("td[data-cell-path='" + groupPath + "'] > .btn:eq(0)").css({'display': 'none'});
            }
        } else {
            if (elem.hasClass('bp-detail-row')) {
                var $oneLevelRow = elem;
            } else {
                var $oneLevelRow = elem.closest('.bp-detail-row');
            }
            
            if (groupPath == 'pfSubDetailButton') {
                $oneLevelRow.find('.bp-btn-subdtl:eq(0)').css({'display': 'none'});
            } else {
                if ($oneLevelRow.find("[data-path='" + groupPath + "']").length) {
                    $oneLevelRow.find("td[data-cell-path='" + groupPath + "'] > .btn:eq(0)").css({'display': 'none'});
                } else {
                    mainSelector.find("td[data-cell-path='" + groupPath + "'] > .btn:eq(0)").css({'display': 'none'});
                }
            }
        }
    }
    
    return;
}
function bpClickRowsBtn(mainSelector, elem) {
    if (elem !== 'open' && typeof $(elem).prop('tagName') !== 'undefined') {
        var $this = $(elem);
        if ($this.prop('tagName') == 'TR') {
            $this.find('.bp-btn-subdtl').click();
        } else {
            $this.closest('.bp-detail-row').find('.bp-btn-subdtl').click();
        }
    }
    return;
}
function bpClickSideBarBtn(mainSelector, elem) {
    if (elem !== 'open' && typeof $(elem).prop('tagName') !== 'undefined') {
        var $this = $(elem);
        if ($this.prop('tagName') == 'TR') {
            $this.find('.bp-btn-sidebar').click();
        } else {
            $this.closest('.bp-detail-row').find('.bp-btn-sidebar').click();
        }
    }
    return;
}
function bpPopupIgnoreSaveButton(mainSelector, elem, groupPath) {
    mainSelector.find('[data-table-path="'+groupPath+'"]').attr('data-popup-ignore-save-button', '1');
    return;
}
function paramTreePopup(elem, uniqId, _window, type) {
    var $thisElem = $(elem), isPositionRelative = false, 
        isOverflowAutoParent = false, isPositionStaticParent = false, 
        isWsPageContentParent = false;
        
    if (typeof type !== 'undefined' && type === '1') {
        
        var $parent = $(_window).parent();
        var $processChildDtlTd = $thisElem.closest('.bp-new-dtltheme');
        
        $parent.find("div.center-sidebar").css('position', 'static');
        $parent.parent().css('overflow', 'inherit');
        
    } else {
        
        var $parent = $(_window).parent();
        var $layoutControl = $thisElem.closest('.bp-layout-rowcontrol');
        var $bpLayout = $thisElem.closest('.bp-layout');
        var $wsPageContent = $thisElem.closest('.ws-page-content');
        
        isPositionStaticParent = true;
        
        if ($layoutControl.length) {
            var $processChildDtlTd = $layoutControl;
        } else {
            var $processChildDtlTd = $thisElem.closest('td');
        }
        
        if ($bpLayout.length) {
            var $overflowAutoParent = $thisElem.closest('.overflow-auto');
            
            if ($overflowAutoParent.length) {
                isOverflowAutoParent = true;
            }
        } else {
            var $overflowAutoParent = $thisElem.closest('.bprocess-div-dtl-14');
            if ($overflowAutoParent.length) {
                $overflowAutoParent = $overflowAutoParent.find('> div.tbody');
                isOverflowAutoParent = true;
            }
        }
        
        if ($processChildDtlTd.css('position') == 'relative') {
            $processChildDtlTd.css('position', '');
            isPositionRelative = true;
        }
        
        if ($wsPageContent.length) {
            isWsPageContentParent = true;
            $wsPageContent.css('overflow', 'inherit');
            $wsPageContent.closest('.workspace-main').css('overflow', 'inherit');
            $wsPageContent.closest('.ws-area').css('overflow', 'inherit');
        }
        
        $parent.css('position', 'static');
        $parent.find("div.center-sidebar").css('position', 'static');
        $processChildDtlTd.closest("div.col-md-12").css('position', 'static');
        $parent.parent().css('overflow', 'inherit');
        
        if ($thisElem.closest('.param-tree-container').length) {
            $thisElem.closest('.param-tree-container').css('position', 'static');
            $thisElem.closest('.param-tree-container').parent().css('overflow', 'inherit');
        }
    }
    
    var hideSaveButton = '';
    if (typeof $thisElem.closest('.bprocess-table-dtl').attr('data-popup-ignore-save-button') !== 'undefined' 
        && $thisElem.closest('.bprocess-table-dtl').attr('data-popup-ignore-save-button') == '1') {
        hideSaveButton = ' hide';
    }
    
    if (isOverflowAutoParent) {
        $overflowAutoParent.addClass('overflow-inherit');
    }
    
    var $dialogName = 'div.param-tree-container:eq(0)';	
    
    $($dialogName, $processChildDtlTd).dialog({
        cache: false,
        resizable: true, 
        appendTo: $processChildDtlTd,
        bgiframe: true,
        autoOpen: false,
        title: $thisElem.attr('title'),
        width: (typeof childDtlDialogWidth !== 'undefined' ? childDtlDialogWidth : 850),
        height: 'auto',
        maxHeight: 700,
        modal: true,
        draggable: false,
        closeOnEscape: isCloseOnEscape, 
        create: function() {
            $(this).css({'min-width': 500, 'max-width': $(window).width()});
        }, 
        close: function() {
            if (isPositionStaticParent) {
                $parent.css('position', '');
                $parent.find("div.center-sidebar").css('position', '');
                $processChildDtlTd.closest("div.col-md-12").css('position', '');
                $parent.parent().css('overflow', '');
            } else {
                $parent.find("div.center-sidebar").css('position', '');
                $parent.parent().css('overflow', '');
            }
            if (isPositionRelative == true) {
                $processChildDtlTd.css('position', 'relative');
            }
            if (isOverflowAutoParent) {
                $overflowAutoParent.removeClass('overflow-inherit');
            }
            if (isWsPageContentParent) {
                $wsPageContent.css('overflow', '');
                $wsPageContent.closest('.workspace-main').css('overflow', '');
                $wsPageContent.closest('.ws-area').css('overflow', '');
            }
        },
        buttons: [
            {text: plang.get('save_btn'), class: 'btn green-meadow btn-sm'+hideSaveButton, click: function () {
               
                $('.bp-btn-subdtl:eq(0)', $processChildDtlTd).trigger('change'); 
                
                if (typeof type !== 'undefined' && type === '1') {
                    $($dialogName, $processChildDtlTd).find('tbody > tr').each(function (index, row) {
                        var _rowFound = $(row);
                        var _selectFound = _rowFound.find('select');
                        var _inputFound = _rowFound.find('input');
                        var _textAreaFound = _rowFound.find('textarea');
                        
                        if (typeof _selectFound.val() !== 'undefined') {
                            var _selectFoundAttr = _selectFound.attr('name');
                            
                            $(_window).find('select[name="'+ _selectFoundAttr +'"]').select2('val', _selectFound.val());
                            $(_window).find('select[name="'+ _selectFoundAttr +'"]').val(_selectFound.val());
                        }
                        if (typeof _inputFound.val() !== 'undefined') {
                            var _inputFoundAttr = _inputFound.attr('name');
                            $(_window).find('input[name="'+ _inputFoundAttr +'"]').val(_inputFound.val());
                        }
                        if (typeof _textAreaFound.val() !== 'undefined') {
                            var _textAreaFoundAttr = _textAreaFound.attr('name');
                            $(_window).find('textarea[name="'+ _textAreaFoundAttr +'"]').val(_textAreaFound.val());
                        }
                    });
                }
                $($dialogName, $processChildDtlTd).dialog('close');
            }},
            {text: plang.get('close_btn'), class: 'btn blue-madison btn-sm', click: function () {
                $($dialogName, $processChildDtlTd).dialog('close');
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
    
    $($dialogName, $processChildDtlTd).parent().draggable();    
    
    $($dialogName, $processChildDtlTd).dialog('open');
    $processChildDtlTd.find(".ui-dialog-titlebar").css("text-align", "left");
    
    $($dialogName, $processChildDtlTd).find(".nav-tabs").on("click", "a", function (e) {
        var hashStr = this.hash.substr(1);
        $(this).closest(".tabbable-line").find(".tab-content").find(".tab-pane").removeClass("active");
        $(this).closest(".tabbable-line").find(".tab-content").children("#" + hashStr).addClass("active");
    });
    
    Core.initBPAjax($($dialogName, $processChildDtlTd));
}
function pasteDetailRow(mainSelector, groupPath, rowElement, rowIndex, afterElement) {
    
    var $getTableBody = mainSelector.find("[data-table-path='"+groupPath+"'] > .tbody");
            
    if ($getTableBody.find('> .bp-detail-row').length > 1) {
        var rowIndexLower = rowIndex.toLowerCase();
    
        if (rowIndexLower === 'first') {
            var $row = $getTableBody.find('> .bp-detail-row:first');
        } else if (rowIndexLower === 'last') {
            var $row = $getTableBody.find('> .bp-detail-row:last-child').prev();
        } else if (rowIndexLower === 'selectedrow') {
            if ($getTableBody.find("> .currentTarget:eq(0)").length > 0) {
                var $row = $getTableBody.find("> .currentTarget:eq(0)");
            } else {
                var $row = $getTableBody.find("> .bp-detail-row:first");
            }
        } else {
            var $row = $getTableBody.find("> .bp-detail-row:eq("+(rowIndex-1)+")");
        }
        
        $row.find('select.select2').select2('destroy');
        $row.removeClass('saved-bp-row');
        $.uniform.restore($row.find('input[type=checkbox]'));
        var $clonedRow = $row.clone();

        $getTableBody.find("> .bp-detail-row:last").remove();
        $getTableBody.append($clonedRow);
        
        Core.initInputType($getTableBody);
    }
    return;
}
function bpAddRow(mainSelector, elem, groupPath, rowCount, fillType, async, fromWhere) {
    var $addButton = mainSelector.find("button.bp-add-one-row[data-action-path='"+groupPath+"']:eq(0)");
    var addButtonLength = $addButton.length, isCustomDtl = false;

    if (addButtonLength == 0) {
        var $parent = mainSelector.find("[data-table-path='"+groupPath+"']");
        if ($parent.hasClass('cool-row') || (typeof fromWhere != 'undefined' && fromWhere == 'transferSplit')) {
            var rowId = $parent.attr('data-row-id');
            isCustomDtl = true;
        } 
    }
    
    if (addButtonLength > 0 || isCustomDtl) {
        
        var isSubDtl = false;
        var getClickAttr = $addButton.attr('onclick');
        var uniqId = mainSelector.attr('data-bp-uniq-id');
        
        if (getClickAttr.indexOf('addRowKpiIndicatorTemplate') !== -1) {
            
            var $this = $addButton, $parent = $this.closest('div'), 
                $nextDiv = $parent.next('div'), 
                $table = $nextDiv.find('table.table:eq(0)'), 
                $tbody = $table.find('> tbody');
            
            var $script = $('script[data-template="rows"][data-uniqid="'+uniqId+'"][data-rows-path="'+groupPath+'"]');
            
            var $html = $('<div />', {html: $script.text()});
            $html.find('.bp-detail-row:eq(0)').addClass('display-none fullexp-addrow multi-added-row');
            var insideHtml = $html.html();
        
            var addingRows = (insideHtml).repeat(rowCount);
            
            if (typeof fillType !== 'undefined' && fillType == 'empty') {
                $tbody.empty();
            }

            $tbody.append(addingRows).promise().done(function() {
                
                var $rowEl = $tbody.find('> .multi-added-row');
                var rowLen = $rowEl.length, rowi = 0;

                for (rowi; rowi < rowLen; rowi++) { 
                    var $lastRow = $($rowEl[rowi]);
                    
                    Core.initNumberInput($lastRow);
                    Core.initLongInput($lastRow);
                    Core.initDateInput($lastRow);
                    Core.initDateTimeInput($lastRow);
                    Core.initSelect2($lastRow);
                    Core.initUniform($lastRow);
                    Core.initDateMinuteInput($lastRow);
                    Core.initTimeInput($lastRow);
                }

                $rowEl.removeClass('multi-added-row display-none');
                
                if (isSubDtl) {
                    kpiSetRowIndex($tbody, rowIndex);
                } else {
                    kpiSetRowIndex($tbody);
                }

                setRowNumKpiIndicatorTemplate($tbody);
                bpDetailFreeze($table);
            });
            
            return;
        }
        
        if (isCustomDtl) {
            
            var processId = mainSelector.attr('data-process-id');
            var addUrl = 'renderBpDtlRow';
            var urlData = {processId: processId, rowId: rowId, uniqId: uniqId};
                
        } else {

            if ($addButton.hasClass('bp-subdtl-addrow')) {
                if (elem === 'open') {
                    if (typeof async !== 'undefined') {
                        $addButton.attr('data-async', async);
                    }
                    $addButton.trigger('click');
                    return;
                }

                var rowHtmlStr = getClickAttr.replace("bpAddDtlRow_"+uniqId+"(this, '", '').replace("');", '');
                var addUrl = 'cryptEncodeToDecodeByPost';

                if (elem.prop('tagName') == 'TR') {

                    if (elem.find("[data-table-path='"+groupPath+"']").length) {
                        var $parent = elem.find("[data-table-path='"+groupPath+"']");
                    } else {
                        var $parent = elem.closest("[data-table-path='"+groupPath+"']");
                    }

                } else {
                    var $parentRow = elem.closest('.bp-detail-row');
                    var $parent = $parentRow.find("[data-table-path='"+groupPath+"']");

                    if (!$parent.length) {
                        $parent = mainSelector.find("[data-table-path='"+groupPath+"']");
                    }
                }

                var rowIndex = $parent.closest('.bp-detail-row').index();
                var urlData = {string: rowHtmlStr};

                isSubDtl = true;

            } else {

                var clickStr = getClickAttr.replace("bpAddMainRow_"+uniqId+"(this, '", '').replace("');", '');
                clickStr = clickStr.replace(/'/g, '');

                var clickStrArr = clickStr.split(',');
                var processId = clickStrArr[0].trim();
                var rowId = clickStrArr[1].trim();
                var $parent = mainSelector.find("[data-table-path='"+groupPath+"']");
                var addUrl = 'renderBpDtlRow';
                var urlData = {processId: processId, rowId: rowId, uniqId: uniqId};
            }
        }
        
        var $getTableBody = $parent.find('.tbody:eq(0)');
        var isAsync = (typeof isAddRowAsync !== 'undefined' ? isAddRowAsync : false);
        
        if (typeof async !== 'undefined') {
            isAsync = async;
        }
        
        $.ajax({
            type: 'post',
            url: 'mdcommon/'+addUrl,
            data: urlData,
            async: isAsync, 
            success: function (dataStr) {
                
                var $html = $('<div />', {html: dataStr});
                $html.find('.bp-detail-row:eq(0)').addClass('display-none fullexp-addrow multi-added-row');
                
                if (window['isEditMode_'+uniqId]) {
                    $html.find("input[data-path*='rowState']").val('added');   
                }
                
                var len = rowCount, i = 0;
                var htmlToInsert = [];
                var insideHtml = $html.html();
                
                for (i; i < len; i++) { 
                    htmlToInsert[i] = insideHtml;
                }
                
                if (typeof fillType !== 'undefined' && fillType == 'empty') {
                    $getTableBody.empty();
                }
                $getTableBody.append(htmlToInsert.join(''));
                
                var $el = $getTableBody.find('> .bp-detail-row');
                var len = $el.length, i = 0;
                for (i; i < len; i++) { 
                    $($el[i]).find('td:first > span').text(i + 1);
                }
                
                /*---done---*/
                var $rowEl = $getTableBody.find('> .multi-added-row');
                var rowLen = $rowEl.length, rowi = 0;

                for (rowi; rowi < rowLen; rowi++) { 
                    Core.initBPInputType($($rowEl[rowi]));
                    window['bpFullScriptsWithoutEvent_'+uniqId]($($rowEl[rowi]), groupPath, false);
                }

                if ($getTableBody.parent().hasClass('cool-row')) {
                    $addButton.addClass('pull-right').removeClass('float-left');
                    $getTableBody.find('> tr:last-child').find('a.bp-remove-row').after($addButton.clone());
                    if ($getTableBody.find('> tr').length > 1) {
                        $getTableBody.find('> tr:last-child').find('a.bp-remove-row').trigger('click');
                    }
                    $addButton.remove();
                }

                window['dtlAggregateFunction_'+uniqId]();

                $rowEl.removeClass('multi-added-row display-none');
                /*---done---*/
                
                if (isSubDtl) {
                    bpSetRowIndexDepth($parent, mainSelector, rowIndex);
                } else {
                    bpSetRowIndex($parent.parent());
                    enableBpDetailFilterByElement($parent);
                    bpDetailFreeze($parent);
                }
            }
        });
    }
    
    return;
}
function bpIsAddRowRun(mainSelector, elem, groupPath) {
    if (elem.prop('tagName') == 'TR') {
                
        if (elem.find("[data-table-path='"+groupPath+"']").length) {
            var $parent = elem.find("[data-table-path='"+groupPath+"']");
        } else {
            var $parent = elem.closest("[data-table-path='"+groupPath+"']");
        }

    } else {
        var $parentRow = elem.closest('.bp-detail-row');
        
        if ($parentRow.find("[data-table-path='"+groupPath+"']").length) {
            var $parent = $parentRow.find("[data-table-path='"+groupPath+"']");
        } else {
            var $parent = mainSelector.find("[data-table-path='"+groupPath+"']");
        }
    }
    
    if ($parent.find('tr.fullexp-addrow').length) {
        return true;
    }
    return false;
}
function bpIgnoreGroup(mainSelector, elem, groupPath, mode) {
    
    if (groupPath.indexOf('.') !== -1) {
        var $thisGroup = mainSelector.find("div[data-section-path='"+groupPath+"']"); 

        if (mode && mode === 'child') {
            var $childGroup = elem.find("div[data-section-path='"+groupPath+"']");

            if ($childGroup.length) {
                $childGroup.append('<input type="hidden" name="param['+groupPath+'.ignoreGroup]" class="bp-ignore-group" value="1">');
            } else {
                $childGroup = elem.find("tr[data-cell-path^='"+groupPath+"']:eq(0)"); 
                if ($childGroup.length) {
                    $childGroup.find('td:eq(0)').append('<input type="hidden" name="param['+groupPath+'.ignoreGroup]" class="bp-ignore-group" value="1">');
                } else {
                    $childGroup = elem.find("td[data-cell-path^='"+groupPath+"']"); 
                    if ($childGroup.length) {
                        $childGroup.append('<input type="hidden" name="param['+groupPath+'.ignoreGroup]" class="bp-ignore-group" value="1">');
                    }
                }
            }
        } else if ($thisGroup.length) {
            $thisGroup.append('<input type="hidden" name="param['+groupPath+'.ignoreGroup]" class="bp-ignore-group" value="1">');
        } else {
            $thisGroup = mainSelector.find("tr[data-cell-path='"+groupPath+"']:eq(0)"); 
            if ($thisGroup.length) {
                $thisGroup.find('td:eq(0)').append('<input type="hidden" name="param['+groupPath+'.ignoreGroup]" class="bp-ignore-group" value="1">');
            } else {
                $thisGroup = mainSelector.find("td[data-cell-path='"+groupPath+"']:eq(0)"); 
                if ($thisGroup.length) {
                    $thisGroup.append('<input type="hidden" name="param['+groupPath+'.ignoreGroup]" class="bp-ignore-group" value="1">');
                } else {
                    mainSelector.find('form').append('<input type="hidden" name="param['+groupPath+'.ignoreGroup]" class="bp-ignore-group" value="1">');
                }
            }
        }
        
    } else {
        mainSelector.find('form').append('<input type="hidden" name="param['+groupPath+'.ignoreGroup]" class="bp-ignore-group" value="1">');
    }
    
    return;
}
function bpAcceptGroup(mainSelector, elem, groupPath) {
    mainSelector.find("input[name='param["+groupPath+".ignoreGroup]']").remove(); 
    return;
}
function bpIgnoreGroupRemove(mainSelector) {
    mainSelector.find('input.bp-ignore-group').remove(); 
    return;
}
function bpGetDetailValueByIndex(mainSelector, elem, groupPath, rowIndex, fieldPath) {
    
    if (elem !== 'open' && typeof elem.prop('tagName') !== 'undefined' && elem.hasClass('bp-detail-row')) {
        
        if (elem.find("[data-table-path='"+groupPath+"']").length) {
            var $parent = elem.find("[data-table-path='"+groupPath+"']");
        } else {
            var $parentRow = elem.parents('.bp-detail-row');
        
            if ($parentRow.find("[data-table-path='"+groupPath+"']").length) {
                var $parent = $parentRow.find("[data-table-path='"+groupPath+"']");
            } else {
                var $parent = mainSelector.find("[data-table-path='"+groupPath+"']");
            }
        }

    } else if (elem === 'open') {
        
        var $parent = mainSelector.find("[data-table-path='"+groupPath+"']");
        
    } else {
        
        var $parentRow = elem.closest('.bp-detail-row');
        
        if ($parentRow.find("[data-table-path='"+groupPath+"']").length) {
            var $parent = $parentRow.find("[data-table-path='"+groupPath+"']");
        } else {
            var $parent = mainSelector.find("[data-table-path='"+groupPath+"']");
        }
    }
    
    if (rowIndex == 'first') {
        var $getRow = $parent.find('.tbody:eq(0) > .bp-detail-row:first');
    } else if (rowIndex == 'last') {
        var $getRow = $parent.find('.tbody:eq(0) > .bp-detail-row:last-child');
    } else {
        rowIndex = rowIndex - 1;
        var $getRow = $parent.find('.tbody:eq(0) > .bp-detail-row:eq('+rowIndex+')');
    }
    
    var fieldValue = '';
    if ($getRow.length) {
        fieldValue = getBpRowParamNum(mainSelector, $getRow.find('input:first'), fieldPath);
    }
    
    return fieldValue;
}
function bpSetDetailValueByIndex(mainSelector, elem, groupPath, rowIndex, fieldPath, value) {
    
    if (elem !== 'open' && typeof elem.prop('tagName') !== 'undefined' && elem.hasClass('bp-detail-row')) {
        
        if (elem.find("[data-table-path='"+groupPath+"']").length) {
            var $parent = elem.find("[data-table-path='"+groupPath+"']");
        } else {
            var $parentRow = elem.parents('.bp-detail-row');
        
            if ($parentRow.find("[data-table-path='"+groupPath+"']").length) {
                var $parent = $parentRow.find("[data-table-path='"+groupPath+"']");
            } else {
                var $parent = mainSelector.find("[data-table-path='"+groupPath+"']");
            }
        }

    } else if (elem === 'open') {
        
        var $parent = mainSelector.find("[data-table-path='"+groupPath+"']");
        
    } else {
        
        var $parentRow = elem.closest('.bp-detail-row');
        
        if ($parentRow.find("[data-table-path='"+groupPath+"']").length) {
            var $parent = $parentRow.find("[data-table-path='"+groupPath+"']");
        } else {
            var $parent = mainSelector.find("[data-table-path='"+groupPath+"']");
        }
    }
    
    if (rowIndex == 'first') {
        var $getRow = $parent.find('.tbody:eq(0) > .bp-detail-row:visible:first');
    } else if (rowIndex == 'last') {
        var $getRow = $parent.find('.tbody:eq(0) > .bp-detail-row:visible:last-child');
    } else {
        rowIndex = Number(rowIndex) - 1;
        var $getRow = $parent.find('.tbody:eq(0) > .bp-detail-row:visible:eq('+rowIndex+')');
        if (!$getRow.length) {
            $getRow = $parent.find('.tbody:eq(0) > .bp-detail-row:eq('+rowIndex+')');
        }
    }
    
    if ($getRow.length) {
        setBpRowParamNum(mainSelector, $getRow.find('input:first'), fieldPath, value);
    }
    
    return;
}
function bpSetDetailBySameValue(mainSelector, elem, paths, val) {
    if (paths != '') {
        var pathsArr = paths.split(',');
        
        for (var i = 0; i < pathsArr.length; i++) {
            var fieldPath = pathsArr[i].trim();
            
            if (mainSelector.find("[data-path='"+fieldPath+"']").length) {
                
                var paramRealPathFirst = mainSelector.find("[data-path='"+fieldPath+"']:eq(0)");
                var paramRealPath = mainSelector.find("[data-path='"+fieldPath+"']");
                
                if (paramRealPathFirst.prop('tagName') == 'SELECT') {
                    if (paramRealPathFirst.hasClass('select2')) {
                        paramRealPath.trigger("select2-opening", [true]);
                        paramRealPath.select2('val', val);
                    } else {
                        paramRealPath.trigger('blur');
                        paramRealPath.val(val);
                    }
                } else {
                    if (paramRealPathFirst.hasClass('longInit') 
                        || paramRealPathFirst.hasClass('numberInit') 
                        || paramRealPathFirst.hasClass('decimalInit') 
                        || paramRealPathFirst.hasClass('integerInit')) {

                        paramRealPath.autoNumeric('set', val);                        

                    } else if (paramRealPathFirst.hasClass('bigdecimalInit')) {
                        paramRealPath.next("input[type=hidden]").val(setNumberToFixed(val));
                        paramRealPath.autoNumeric('set', val);   
                    } else if (paramRealPathFirst.hasClass('dateInit')) {
                        if (val !== '' && val !== null) {
                            paramRealPath.datepicker('update', date('Y-m-d', strtotime(val)));
                        } else {
                            paramRealPath.datepicker('update', null);
                        }
                    } else if (paramRealPathFirst.hasClass('datetimeInit')) {
                        if (val !== '' && val !== null) {
                            paramRealPath.val(date('Y-m-d H:i:s', strtotime(val)));
                        } else {
                            paramRealPath.val('');
                        }
                    } else if (paramRealPathFirst.hasClass('popupInit')) {   
                        setLookupPopupValue(paramRealPath, val);
                    } else if (paramRealPathFirst.hasClass('booleanInit')) {   
                        checkboxCheckerUpdate(paramRealPath, val);
                    } else {                                               
                        paramRealPath.val(val);                        
                    }
                }
            }
        }
    }
    return;
}
function bpFillGroupByDv(mainSelector, elem, dataViewCode, groupPath, inputParams, mappingParams, fillType) {
    
    var $getTable = mainSelector.find("[data-table-path='"+groupPath+"']:eq(0)");
    
    if ($getTable.length) {
        
        var inputParamsData = [], mappingParamsData = []; 
        var inputParamsArr = inputParams.split('|');
        var mappingParamsArr = mappingParams.toLowerCase().split('|');
        var processId = mainSelector.attr('data-process-id');
        var uniqId = mainSelector.attr('data-bp-uniq-id');
        var actionType = mainSelector.hasClass('bp-view-process') ? 'view' : '';
        var isSubDtl = false;

        if (inputParams != '') {
            for (var i = 0; i < inputParamsArr.length; i++) { 
                var fieldPathArr = inputParamsArr[i].split('@');
                var fieldPath = fieldPathArr[0];
                var inputPath = fieldPathArr[1];
                var fieldValue = '';

                var $bpElem = getBpElement(mainSelector, elem, fieldPath);

                if ($bpElem) {
                    if ($bpElem.hasClass('bigdecimalInit')) {
                        if ($bpElem.val() != '') {
                            fieldValue = getBpRowParamNum(mainSelector, elem, fieldPath);
                        }
                    } else {
                        fieldValue = getBpRowParamNum(mainSelector, elem, fieldPath);
                    }
                } else {
                    fieldValue = fieldPath;
                }

                inputParamsData.push({
                    inputPath: inputPath, 
                    value: fieldValue
                });
            }
        }

        for (var j = 0; j < mappingParamsArr.length; j++) {
            var mappingPathArr = mappingParamsArr[j].split('@');
            var dataviewPath = mappingPathArr[0];
            var processPath = mappingPathArr[1];

            mappingParamsData.push({
                dataviewPath: dataviewPath, 
                processPath: processPath
            });
        }
        
        if ($getTable.hasClass('bprocess-table-subdtl')) {
            
            if (elem != 'open') {
                if (elem.prop('tagName') == 'TR') {

                    if (elem.find("[data-table-path='"+groupPath+"']").length) {
                        var $parent = elem.find("[data-table-path='"+groupPath+"']");
                    } else {
                        var $parent = elem.closest("[data-table-path='"+groupPath+"']");
                    }

                } else {
                    var $parentRow = elem.closest('.bp-detail-row');
                    var $parent = $parentRow.find("[data-table-path='"+groupPath+"']");
                }

                var rowIndex = $parent.closest('.bp-detail-row').index();
                
            } else {
                var $parent = $getTable;
                var rowIndex = 0;
            }
            
            isSubDtl = true;
            
        } else {
            var $parent = $getTable;
        }
        
        var $getTableBody = $parent.find('.tbody:eq(0)');
        var postData = {
            uniqId: uniqId, 
            processId: processId, 
            actionType: actionType, 
            dataViewCode: dataViewCode, 
            groupPath: groupPath, 
            inputParamsData: inputParamsData, 
            mappingParamsData: mappingParamsData
        };
        
        if ($getTable.hasAttr('data-col-name')) {
            postData.isIndicator = 1;
        }
            
        $.ajax({
            type: 'post',
            url: 'mdwebservice/fillGroupByDv', 
            data: postData,
            async: false, 
            success: function(dataStr) {
                
                if (isSubDtl) {
                    dataStr = dataStr.replace(new RegExp('.mainRowCount]', 'g'), '.rowCount][0]');
                }
                
                var $html = $('<div />', {html: dataStr});
                $html.children('.bp-detail-row').addClass('added-bp-row display-none multi-added-row');

                if (window['isEditMode_'+uniqId]) {
                    $html.find("input[data-path*='rowState']").val('added');   
                }
                    
                fillType = fillType.toLowerCase().trim();
                
                if (fillType == 'empty') {
                    
                    var $savedRows = $getTableBody.find('> .bp-detail-row.saved-bp-row:not(.added-bp-row)');
                    var $unSavedRows = $getTableBody.find('> .bp-detail-row:not(.saved-bp-row), > .bp-detail-row.added-bp-row');

                    if ($unSavedRows.length) {
                        $unSavedRows.remove();
                    }

                    if ($savedRows.length) {
                        $savedRows.addClass('removed-tr d-none');
                        $savedRows.find('input[data-field-name="rowState"]').val('removed');
                        $savedRows.find("input.bigdecimalInit, input.decimalInit, input.numberInit, input.integerInit, input.longInit, input[data-path*='_bigdecimal']").attr('data-not-aggregate', '1');
                    }
                }
                
                $getTableBody.append($html.html());
                
                var $rowNumEl = $getTableBody.find('> .bp-detail-row:not(.removed-tr)');
                var rowNumLen = $rowNumEl.length, ni = 0;
                
                for (ni; ni < rowNumLen; ni++) { 
                    $($rowNumEl[ni]).find('td:first > span').text(ni + 1);
                }
                
                if (isSubDtl) {
                    bpSetRowIndexDepth($parent, mainSelector, rowIndex);
                } else {
                    bpSetRowIndex($parent.parent());
                }
                
                var $rowEl = $getTableBody.find('> .bp-detail-row.multi-added-row');
                var rowLen = $rowEl.length, rowi = 0;

                for (rowi; rowi < rowLen; rowi++) { 
                    Core.initBPDtlInputType($($rowEl[rowi]));
                    window['bpFullScriptsWithoutEvent_'+uniqId]($($rowEl[rowi]), groupPath, true);
                }

                $rowEl.removeClass('multi-added-row display-none');
                
                if (typeof window['rowsDtlPathReplacer_' + uniqId] === 'function') {
                    window['rowsDtlPathReplacer_'+uniqId](groupPath);
                }
                
                window['dtlAggregateFunction_'+uniqId]();
                enableBpDetailFilterByElement($parent);

                if (isSubDtl == false) {
                    bpDetailFreeze($parent);
                }

                Core.unblockUI();
            }
        });
    }
    
    return;
}
function bpFillGroupByData(mainSelector, elem, dataObj, groupPath, mappingParams, fillType) {
    
    if (mainSelector.find("[data-table-path='"+groupPath+"']").length && dataObj) {
        
        var mappingParamsData = []; 
        var mappingParamsArr = mappingParams.toLowerCase().split('|');
        var processId = mainSelector.attr('data-process-id');
        var uniqId = mainSelector.attr('data-bp-uniq-id');
        var actionType = mainSelector.hasClass('bp-view-process') ? 'view' : '';
        var isSubDtl = false;

        for (var j = 0; j < mappingParamsArr.length; j++) {
            var mappingPathArr = mappingParamsArr[j].split('@');
            var dataviewPath = mappingPathArr[0];
            var processPath = mappingPathArr[1];

            mappingParamsData.push({
                dataviewPath: dataviewPath, 
                processPath: processPath
            });
        }
        
        var $getTable = mainSelector.find("[data-table-path='"+groupPath+"']:eq(0)");
        
        if ($getTable.hasClass('bprocess-table-subdtl')) {
            
            if (elem != 'open') {
                if (elem.prop('tagName') == 'TR') {

                    if (elem.find("[data-table-path='"+groupPath+"']").length) {
                        var $parent = elem.find("[data-table-path='"+groupPath+"']");
                    } else {
                        var $parent = elem.closest("[data-table-path='"+groupPath+"']");
                    }

                } else {
                    var $parentRow = elem.closest('.bp-detail-row');
                    var $parent = $parentRow.find("[data-table-path='"+groupPath+"']");
                }

                var rowIndex = $parent.closest('.bp-detail-row').index();
                
            } else {
                var $parent = $getTable;
                var rowIndex = 0;
            }
            
            isSubDtl = true;
            
        } else {
            var $parent = $getTable;
        }
        
        var $getTableBody = $parent.find('.tbody:eq(0)');
            
        $.ajax({
            type: 'post',
            url: 'mdwebservice/fillGroupByDv', 
            data: {
                uniqId: uniqId, 
                processId: processId, 
                actionType: actionType, 
                groupPath: groupPath,  
                mappingParamsData: mappingParamsData, 
                dataObj: dataObj
            },
            async: false, 
            success: function(dataStr) {
                
                if (isSubDtl) {
                    dataStr = dataStr.replace(new RegExp('.mainRowCount]', 'g'), '.rowCount][0]');
                }
                
                var $html = $('<div />', {html: dataStr});
                $html.children('.bp-detail-row').addClass('added-bp-row display-none multi-added-row');

                if (window['isEditMode_'+uniqId]) {
                    $html.find("input[data-path*='rowState']").val('added');   
                }
                    
                fillType = fillType.toLowerCase().trim();
                
                if (fillType == 'empty') {
                    
                    var $savedRows = $getTableBody.find('> .bp-detail-row.saved-bp-row:not(.added-bp-row)');
                    var $unSavedRows = $getTableBody.find('> .bp-detail-row:not(.saved-bp-row), > .bp-detail-row.added-bp-row');

                    if ($unSavedRows.length) {
                        $unSavedRows.remove();
                    }

                    if ($savedRows.length) {
                        $savedRows.addClass('removed-tr d-none');
                        $savedRows.find('input[data-field-name="rowState"]').val('removed');
                        $savedRows.find("input.bigdecimalInit, input.decimalInit, input.numberInit, input.integerInit, input.longInit, input[data-path*='_bigdecimal']").attr('data-not-aggregate', '1');
                    }
                }
                
                $getTableBody.append($html.html());
                
                var $rowNumEl = $getTableBody.find('> .bp-detail-row:not(.removed-tr)');
                var rowNumLen = $rowNumEl.length, ni = 0;
                
                for (ni; ni < rowNumLen; ni++) { 
                    $($rowNumEl[ni]).find('td:first > span').text(ni + 1);
                }
                
                if (isSubDtl) {
                    bpSetRowIndexDepth($parent, mainSelector, rowIndex);
                } else {
                    bpSetRowIndex($parent.parent());
                }
                
                var $rowEl = $getTableBody.find('> .bp-detail-row.multi-added-row');
                var rowLen = $rowEl.length, rowi = 0;

                for (rowi; rowi < rowLen; rowi++) { 
                    Core.initBPDtlInputType($($rowEl[rowi]));
                    window['bpFullScriptsWithoutEvent_'+uniqId]($($rowEl[rowi]), groupPath, true);
                }

                $rowEl.removeClass('multi-added-row display-none');
                
                if (typeof window['rowsDtlPathReplacer_' + uniqId] === 'function') {
                    window['rowsDtlPathReplacer_'+uniqId](groupPath);
                }
                
                window['dtlAggregateFunction_'+uniqId]();
                enableBpDetailFilterByElement($parent);

                if (isSubDtl == false) {
                    bpDetailFreeze($parent);
                }

                Core.unblockUI();
            }
        });
    }
    
    return;
}
function bpFillGroupByGroup(mainSelector, elem, strGroupPath, groupPath, mappingParams, fillType = '') {
    
    if (mainSelector.find("[data-table-path='"+groupPath+"']").length && mainSelector.find("[data-table-path='"+strGroupPath+"']").length) {
        
        var mappingParamsData = []; 
        var mappingParamsArr = mappingParams.toLowerCase().split('|');
        var processId = mainSelector.attr('data-process-id');
        var uniqId = mainSelector.attr('data-bp-uniq-id');
        var isSubDtl = false;

        for (var j = 0; j < mappingParamsArr.length; j++) {
            var mappingPathArr = mappingParamsArr[j].split('@');
            var dataviewPath = mappingPathArr[0];
            var processPath = mappingPathArr[1];

            mappingParamsData.push({
                dataviewPath: dataviewPath, 
                processPath: processPath
            });
        }

        if (strGroupPath.indexOf('.') !== -1 && typeof elem !== 'undefined' && elem !== 'open') {
            if ($(elem).hasClass('bp-detail-row')) {
                var $row = $(elem);
                if ($row.find("[data-table-path='"+strGroupPath+"']").length == 0) {
                    $row = $(elem).closest('.bp-detail-row').parents('.bp-detail-row');
                }
            } else {
                var $row = $(elem).closest('.bp-detail-row').parents('.bp-detail-row');
            }
            var $el = $row.find("[data-table-path='"+strGroupPath+"'] > .tbody > .bp-detail-row:not(.removed-tr)");
        } else {
            var $el = mainSelector.find("[data-table-path='"+strGroupPath+"'] > .tbody > .bp-detail-row:not(.removed-tr)");
        }
        
        var $len = $el.length, $i = 0, dataObj = [], tempDataObj, $tempSelectorValue;
        
        for ($i; $i < $len; $i++) { 
            tempDataObj = {};

            $($el[$i]).find(' > td').each(function(){
                var $thistd = $(this)

                if (typeof $thistd.attr('data-cell-path') !== 'undefined') {
                    $tempSelectorValue = $($el[$i]).find("[data-path='" + $thistd.attr('data-cell-path') + "']");
                    tempDataObj[$tempSelectorValue.attr('data-field-name').toLowerCase()] = $tempSelectorValue.val();
                }
            });

            dataObj.push(tempDataObj);
        }   
        
        var $getTable = mainSelector.find("[data-table-path='"+groupPath+"']:eq(0)");
        
        if ($getTable.hasClass('bprocess-table-subdtl')) {
            
            if (elem.prop('tagName') == 'TR') {

                if (elem.find("[data-table-path='"+groupPath+"']").length) {
                    var $parent = elem.find("[data-table-path='"+groupPath+"']");
                } else {
                    var $parent = elem.closest("[data-table-path='"+groupPath+"']");
                }

            } else {
                var $parentRow = elem.closest('.bp-detail-row');

                if ($parentRow.length) {
                    var $parent = $parentRow.find("[data-table-path='"+groupPath+"']");
                } else {
                    var $parent = mainSelector.find("[data-table-path='"+groupPath+"']");
                }
            }
            
            var rowIndex = $parent.closest('.bp-detail-row').index();
            isSubDtl = true;
            
        } else {
            var $parent = $getTable;
        }
        
        var $getTableBody = $parent.find('.tbody:eq(0)');
            
        $.ajax({
            type: 'post',
            url: 'mdwebservice/fillGroupByDv', 
            data: {
                uniqId: uniqId, 
                processId: processId, 
                groupPath: groupPath,  
                mappingParamsData: mappingParamsData, 
                dataObj: dataObj
            },
            async: false, 
            success: function(dataStr) {
                
                if (isSubDtl) {
                    dataStr = dataStr.replace(new RegExp('.mainRowCount]', 'g'), '.rowCount][0]');
                }
                
                var $html = $('<div />', {html: dataStr});
                $html.children('.bp-detail-row').addClass('added-bp-row display-none multi-added-row');

                if (window['isEditMode_'+uniqId]) {
                    $html.find("input[data-path*='rowState']").val('added');   
                }
                    
                fillType = fillType.toLowerCase();
                
                if (fillType == 'append') {
                    $getTableBody.append($html.html());
                } else {
                    $getTableBody.empty().append($html.html());
                }
                
                if (!$getTableBody.closest('td').length) {
                    var $rowNumEl = $getTableBody.find('> .bp-detail-row');
                    var rowNumLen = $rowNumEl.length, ni = 0;
                    
                    for (ni; ni < rowNumLen; ni++) { 
                        $($rowNumEl[ni]).find('td:first > span').text(ni + 1);
                    }
                    
                    if (isSubDtl) {
                        bpSetRowIndexDepth($parent, mainSelector, rowIndex);
                    } else {
                        bpSetRowIndex($parent);
                    }
                } else {

                    $getTableBody.closest('table').find(' > tr').each(function(){
                        var $rowNumEl = $(this).find("[data-table-path='"+groupPath+"']").find('> .bp-detail-row');
                        var rowNumLen = $rowNumEl.length, ni = 0;
                        
                        for (ni; ni < rowNumLen; ni++) { 
                            $($rowNumEl[ni]).find('td:first > span').text(ni + 1);
                        }
                        
                        if (isSubDtl) {
                            bpSetRowIndexDepth($(this).find("[data-table-path='"+groupPath+"']"), mainSelector, rowIndex);
                        } else {
                            bpSetRowIndex($(this).find("[data-table-path='"+groupPath+"']"));
                        } 
                    });
                }
                
            }
        }).done(function(){
            
            var $rowEl = $getTableBody.find('> .bp-detail-row.multi-added-row');
            var rowLen = $rowEl.length, rowi = 0;
            
            for (rowi; rowi < rowLen; rowi++) { 
                Core.initBPDtlInputType($($rowEl[rowi]));
                window['bpFullScriptsWithoutEvent_'+uniqId]($($rowEl[rowi]), groupPath, true);
            }

            $rowEl.removeClass('multi-added-row display-none');
            
            if (typeof window['rowsDtlPathReplacer_' + uniqId] === 'function') {
                window['rowsDtlPathReplacer_'+uniqId](groupPath);
            }
                
            window['dtlAggregateFunction_'+uniqId]();
            enableBpDetailFilterByElement($parent);
            
            if (isSubDtl == false) {
                bpDetailFreeze($parent);
            }
            
            Core.unblockUI();
        });
    }
    
    return;
}
function bpFillGroupAndDtlByData(mainSelector, elem, dataObj, groupPath, mappingParams, fillType, fillIndex, parentTableBody) {
    groupPath = groupPath.toLowerCase();
    if (mainSelector.find("[data-table-path-lower='"+groupPath+"']").length && dataObj) {
        
        var mappingParamsData = []; 
        var mappingParamsArr = mappingParams.toLowerCase().split('|');
        var processId = mainSelector.attr('data-process-id');
        var uniqId = mainSelector.attr('data-bp-uniq-id');
        var isSubDtl = false;
        var $dtl = false;
        var $dtldataObj = [];
        var $dtlgroupPath = [];
        var kk = 0;
        
        for (var j = 0; j < dataObj.length; j++) {
            $.each(dataObj[j], function ($path, $value) {
                $dtldataObj.push([]);
                $dtlgroupPath[kk] = '';
                if (typeof $value !== 'object') {
                    $dtldataObj[kk].push([]);
                    mappingParamsData.push({
                        dataviewPath: $path.toLowerCase(),
                        processPath: (groupPath + '.' + $path).toLowerCase()
                    });
                } else {
                    $dtl = true;
                    $dtldataObj[kk].push($value);
                    $dtlgroupPath[kk] = (groupPath + '.' + $path).toLowerCase();
                }
                kk++;
            });
        }

        var $getTable = mainSelector.find("[data-table-path-lower='"+groupPath+"']:eq(0)");
        
        if (typeof fillIndex === 'undefined' && $getTable.hasClass('bprocess-table-subdtl')) {
            
            if (elem.prop('tagName') == 'TR') {

                if (elem.find("[data-table-path-lower='"+groupPath+"']").length) {
                    var $parent = elem.find("[data-table-path-lower='"+groupPath+"']");
                } else {
                    var $parent = elem.closest("[data-table-path-lower='"+groupPath+"']");
                }

            } else {
                var $parentRow = $getTable.closest('.bp-detail-row');
                var $parent = $parentRow.find("[data-table-path-lower='"+groupPath+"']");
            }
            
            var rowIndex = $parent.closest('.bp-detail-row').index();
            isSubDtl = true;
            
        } else {
            if (typeof fillIndex !== 'undefined') {
                
                var $parent = parentTableBody.find('> tr:eq('+ fillIndex +')').find("[data-table-path-lower='"+groupPath+"']");

                if ($parent.length < 1) {
                    $parent = $getTable;
                }
            } else {
                
                var $parent = $getTable;
            }
        }
        
        var $getTableBody = $parent.find('.tbody:eq(0)');

        $.ajax({
            type: 'post',
            url: 'mdwebservice/fillGroupByDv', 
            data: {
                uniqId: uniqId, 
                processId: processId, 
                groupPath: groupPath,  
                mappingParamsData: mappingParamsData, 
                dataObj: dataObj
            },
            async: false, 
            success: function(dataStr) {
                
                if (isSubDtl) {
                    dataStr = dataStr.replace(new RegExp('.mainRowCount]', 'g'), '.rowCount][0]');
                }
                
                var $html = $('<div />', {html: dataStr});
                $html.children('.bp-detail-row').addClass('added-bp-row display-none multi-added-row');

                if (window['isEditMode_'+uniqId]) {
                    $html.find("input[data-path*='rowState']").val('added');   
                }
                    
                fillType = fillType.toLowerCase();
                
                if (fillType == 'append') {
                    $getTableBody.append($html.html()).promise().done(function () {
                        if ($dtl) {
                            for (var $index_ = 0; $index_ < $dtldataObj.length; $index_++) {
                                $.each($dtldataObj[$index_], function ($dtlObjIndex, $dtlObjRow) {
                                    if ($dtlObjRow.length > 0) {
                                        bpFillGroupAndDtlByData(mainSelector, elem, $dtlObjRow, $dtlgroupPath[$index_], mappingParams, fillType, $index_, $getTableBody)
                                    }
                                });
                            } 
                        }
                    });
                } else {
                    $getTableBody.empty().append($html.html()).promise().done(function () {
                        if ($dtl) {
                            for (var $index_ = 0; $index_ < $dtldataObj.length; $index_++) {
                                $.each($dtldataObj[$index_], function ($dtlObjIndex, $dtlObjRow) {
                                    if ($dtlObjRow.length > 0) {
                                        bpFillGroupAndDtlByData(mainSelector, elem, $dtlObjRow, $dtlgroupPath[$index_], mappingParams, fillType, $index_, $getTableBody)
                                    }
                                });
                            }
                        }
                    });
                }
                
                var $rowNumEl = $getTableBody.find('> .bp-detail-row:not(.removed-tr)');
                var rowNumLen = $rowNumEl.length, ni = 0;
                
                for (ni; ni < rowNumLen; ni++) { 
                    $($rowNumEl[ni]).find('td:first > span').text(ni + 1);
                }
                
                if (isSubDtl) {
                    bpSetRowIndexDepth($parent, mainSelector, rowIndex);
                } else {
                    bpSetRowIndex($parent.parent());
                }
            }
        }).done(function() {
            
            var $rowEl = $getTableBody.find('> .bp-detail-row.multi-added-row');
            var rowLen = $rowEl.length, rowi = 0;
            
            for (rowi; rowi < rowLen; rowi++) { 
                Core.initBPInputType($($rowEl[rowi]));
                window['bpFullScriptsWithoutEvent_'+uniqId]($($rowEl[rowi]), groupPath, true);
            }

            $rowEl.removeClass('multi-added-row display-none');

            window['dtlAggregateFunction_'+uniqId]();
            enableBpDetailFilterByElement($parent);
            
            if (isSubDtl == false) {
                bpDetailFreeze($parent);
            }
            
            Core.unblockUI();
        });
    }
    
    return;
}
function bpFillGroupByIndicator(mainSelector, elem, dataViewCode, groupPath, inputParams, mappingParams, fillType) {

    var $getTable = mainSelector.find("[data-table-path='"+groupPath+"']:eq(0)");
    
    if ($getTable.length) {
        
        var inputParamsData = [], mappingParamsData = []; 
        var inputParamsArr = inputParams.split('|');
        var mappingParamsArr = mappingParams.toLowerCase().split('|');
        var processId = mainSelector.attr('data-process-id');
        var uniqId = mainSelector.attr('data-bp-uniq-id');
        var actionType = mainSelector.hasClass('bp-view-process') ? 'view' : '';
        var isSubDtl = false;

        if (inputParams != '') {
            for (var i = 0; i < inputParamsArr.length; i++) { 
                var fieldPathArr = inputParamsArr[i].split('@');
                var fieldPath = fieldPathArr[0];
                var inputPath = fieldPathArr[1];
                var fieldValue = '';

                var $bpElem = getBpElement(mainSelector, elem, fieldPath);

                if ($bpElem) {
                    if ($bpElem.hasClass('bigdecimalInit')) {
                        if ($bpElem.val() != '') {
                            fieldValue = getBpRowParamNum(mainSelector, elem, fieldPath);
                        }
                    } else {
                        fieldValue = getBpRowParamNum(mainSelector, elem, fieldPath);
                    }
                } else {
                    fieldValue = fieldPath;
                }

                inputParamsData.push({
                    inputPath: inputPath, 
                    value: fieldValue
                });
            }
        }

        for (var j = 0; j < mappingParamsArr.length; j++) {
            var mappingPathArr = mappingParamsArr[j].split('@');
            var dataviewPath = mappingPathArr[0];
            var processPath = mappingPathArr[1];

            mappingParamsData.push({
                dataviewPath: dataviewPath, 
                processPath: processPath
            });
        }
        
        if ($getTable.hasClass('bprocess-table-subdtl')) {
            
            if (elem != 'open') {
                if (elem.prop('tagName') == 'TR') {

                    if (elem.find("[data-table-path='"+groupPath+"']").length) {
                        var $parent = elem.find("[data-table-path='"+groupPath+"']");
                    } else {
                        var $parent = elem.closest("[data-table-path='"+groupPath+"']");
                    }

                } else {
                    var $parentRow = elem.closest('.bp-detail-row');
                    var $parent = $parentRow.find("[data-table-path='"+groupPath+"']");
                }

                var rowIndex = $parent.closest('.bp-detail-row').index();
                
            } else {
                var $parent = $getTable;
                var rowIndex = 0;
            }
            
            isSubDtl = true;
            
        } else {
            var $parent = $getTable;
        }
        
        var $getTableBody = $parent.find('.tbody:eq(0)');
        var postData = {
            uniqId: uniqId, 
            processId: processId, 
            actionType: actionType, 
            dataViewCode: dataViewCode, 
            groupPath: groupPath, 
            inputParamsData: inputParamsData, 
            mappingParamsData: mappingParamsData
        };
        
        if ($getTable.hasAttr('data-col-name')) {
            postData.isIndicator = 1;
        }
            
        $.ajax({
            type: 'post',
            url: 'mdform/fillGroupByIndicator', 
            data: postData,
            async: false, 
            success: function(dataStr) {
                
                if (isSubDtl) {
                    dataStr = dataStr.replace(new RegExp('.mainRowCount]', 'g'), '.rowCount][0]');
                }
                
                if (dataStr.length > 0) {

                    var $html = $('<div />', {html: dataStr});
                    $html.children('.bp-detail-row').addClass('added-bp-row display-none multi-added-row');

                    if (window['isEditMode_'+uniqId]) {
                        $html.find("input[data-path*='rowState']").val('added');   
                    }

                    fillType = fillType.toLowerCase().trim();

                    if (fillType == 'empty') {

                        var $savedRows = $getTableBody.find('> .bp-detail-row');
                        $savedRows.remove();
                    }

                    $getTableBody.append($html.html());

                    var $rowNumEl = $getTableBody.find('> .bp-detail-row:not(.removed-tr)');
                    var rowNumLen = $rowNumEl.length, ni = 0;

                    for (ni; ni < rowNumLen; ni++) { 
                        $($rowNumEl[ni]).find('td:first > span').text(ni + 1);
                    }
                    
                    if (isSubDtl) {
                        kpiSetRowIndex($getTableBody, rowIndex);
                    } else {
                        kpiSetRowIndex($getTableBody);
                    }

                    var $rowEl = $getTableBody.find('> .bp-detail-row.multi-added-row');
                    var rowLen = $rowEl.length, rowi = 0;

                    for (rowi; rowi < rowLen; rowi++) { 
                        Core.initBPDtlInputType($($rowEl[rowi]));
                        window['bpFullScriptsWithoutEvent_'+uniqId]($($rowEl[rowi]), groupPath, true);
                    }

                    $rowEl.removeClass('multi-added-row display-none');

                    if (typeof window['rowsDtlPathReplacer_' + uniqId] === 'function') {
                        window['rowsDtlPathReplacer_'+uniqId](groupPath);
                    }

                    window['dtlAggregateFunction_'+uniqId]();
                    enableBpDetailFilterByElement($parent);

                    if (isSubDtl == false) {
                        bpDetailFreeze($parent);
                    }
                }
                
                Core.unblockUI();
            }
        });
    }
    
    return;
}
function execProcess(mainSelector, elem, processCode, paramsPath) {
    var paramData = [];
    var paramsPathArr = paramsPath.split('|');

    for (var i = 0; i < paramsPathArr.length; i++) {
        var fieldPathArr = paramsPathArr[i].split('@');
        var fieldPath = (fieldPathArr[0]).trim();
        var inputPath = fieldPathArr[1];
        var fieldValue = '';

        var $bpElem = getBpElement(mainSelector, elem, fieldPath);

        if ($bpElem) {
            fieldValue = getBpRowParamNum(mainSelector, elem, fieldPath);
        } else {
            var $bpViewElem = getBpRowViewElem(mainSelector, elem, fieldPath);
            if ($bpViewElem) {
                fieldValue = getBpRowParamNum(mainSelector, elem, fieldPath);
            } else {
                fieldValue = fieldPath;
            }
        }

        paramData.push({
            fieldPath: fieldPath, 
            inputPath: inputPath, 
            value: fieldValue
        });
    }

    var response = $.ajax({
        type: 'post',
        url: 'mdwebservice/execProcess', 
        data: {
            processCode: processCode, 
            paramData: paramData
        },
        dataType: 'json',
        async: false
    });

    return response.responseJSON;
}
function bpExecIndicator(mainSelector, elem, processCode, paramsPath, async) {
    var paramData = {kpiMainIndicatorId: processCode, mvParam: {}};
    var mvParam = {};
    var paramsPathArr = paramsPath.split('|');
    var isAsync = (typeof async != 'undefined' ? async : true);

    for (var i = 0; i < paramsPathArr.length; i++) {
        var fieldPathArr = paramsPathArr[i].split('@');
        var fieldPath = (fieldPathArr[0]).trim();
        var inputPath = (fieldPathArr[1]).trim();
        var fieldValue = '';

        var $bpElem = getBpElement(mainSelector, elem, fieldPath);

        if ($bpElem) {
            fieldValue = getBpRowParamNum(mainSelector, elem, fieldPath);
        } else {
            var $bpViewElem = getBpRowViewElem(mainSelector, elem, fieldPath);
            if ($bpViewElem) {
                fieldValue = getBpRowParamNum(mainSelector, elem, fieldPath);
            } else {
                fieldValue = fieldPath;
            }
        }
        
        mvParam[inputPath] = fieldValue;
    }
    
    paramData.mvParam = mvParam;

    if (isAsync == false) {
        var response = $.ajax({
            type: 'post',
            url: 'mdform/saveKpiDynamicDataByList', 
            data: paramData,
            dataType: 'json',
            async: false
        });

        return response.responseJSON;
    } else {
        $.ajax({
            type: 'post',
            url: 'mdform/saveKpiDynamicDataByList', 
            data: paramData,
            dataType: 'json',
            success: function(data) {
                console.log(data);
            }
        });
    }
}
function repeatFunction(mainSelector, groupPath, funcName, elem) {
    
    if (groupPath.indexOf('.') !== -1 && typeof elem !== 'undefined' && elem !== 'open') {
        if ($(elem).hasClass('bp-detail-row')) {
            var $row = $(elem);
            if ($row.find("[data-table-path='"+groupPath+"']").length == 0) {
                $row = $(elem).closest('.bp-detail-row').parents('.bp-detail-row');
            }
        } else {
            var $row = $(elem).closest('.bp-detail-row').parents('.bp-detail-row');
        }
        var $el = $row.find("[data-table-path='"+groupPath+"'] > .tbody > .bp-detail-row:not(.removed-tr)");
    } else {
        var $el = mainSelector.find("[data-table-path='"+groupPath+"'] > .tbody > .bp-detail-row:not(.removed-tr)");
    }
    
    var $len = $el.length, $i = 0;
    
    if (funcName.indexOf(',') !== -1) {
        
        var funcNameArr = funcName.split(','), evalFunc = '';
        
        for (var i = 0; i < funcNameArr.length; i++) {
            var funcName = funcNameArr[i].trim();
            evalFunc += "window['"+funcName+"']($($el[$i])); ";
        }
        
        for ($i; $i < $len; $i++) { 
            eval(evalFunc);
        }
        
    } else {
        for ($i; $i < $len; $i++) { 
            window[funcName]($($el[$i]));
        }
    }
    
    return;
}
function bpRepeatColumnFunction(mainSelector, groupPath, funcName, elem) {
    
    var $el = mainSelector.find("[data-cols-path='"+groupPath+"']");
    var $table = $el.closest('table');
    var $rows = $table.find('> tbody > tr:not(.removed-tr)');
    var $len = $el.length, i = 0;
    var $rowsLen = $rows.length, j = 0;
    
    for (j; j < $rowsLen; j++) { 
        
        var $row = $($rows[j]);
        i = 0;
        
        for (i; i < $len; i++) {
            
            var colNum = $($el[i]).attr('data-header-pivot-num');
            var $cols = $row.find('[data-group-num="'+colNum+'"]');
            
            window[funcName]($cols);
        }
    }
    
    return;
}
function bpKpiRepeatFunction(mainSelector, elem, funcName) {
    
    var $el = mainSelector.find('tr[data-is-input="1"]');
    var $len = $el.length, $i = 0;
    
    if (funcName.indexOf(',') !== -1) {
        
        var funcNameArr = funcName.split(','), evalFunc = '';
        
        for (var i = 0; i < funcNameArr.length; i++) {
            var funcName = funcNameArr[i].trim();
            evalFunc += "window['"+funcName+"']($($el[$i]).children(':first')); ";
        }
        
        for ($i; $i < $len; $i++) { 
            eval(evalFunc);
        }
        
    } else {
        for ($i; $i < $len; $i++) { 
            window[funcName]($($el[$i]).children(':first'));
        }
    }
    
    return;
}
function bpGroupFieldImploder(mainSelector, elem, groupPath, field, glue) {
    var $el = mainSelector.find("[data-table-path='"+groupPath+"'] > .tbody > .bp-detail-row:not(.removed-tr)");
    var $len = $el.length;
    
    if ($len) {
        var $i = 0, $newArray = [];
        for ($i; $i < $len; $i++) { 
            var value = $($el[$i]).find("[data-path='"+field+"']").val();
            if (value != '') {
                $newArray.push(value);
            }
        }
        return $newArray.join(glue);
    } else {
        return '';
    }
}
function bpDetailRowHide(mainSelector, elem) {
    $(elem).css({display: 'none'});
    return;
}
function bpDetailRowShow(mainSelector, elem) {
    $(elem).css({display: ''});
    return;
}
function bpDetailRowRemove(mainSelector, elem) {
    var $this = $(elem);
    var $parentTbl = $this.closest('.bprocess-table-dtl');
    var $processForm = $parentTbl.closest('form');
    var $uniqId = $processForm.parent().attr('data-bp-uniq-id'); 
    
    if (typeof $this.prop('tagName') !== 'undefined' && $this.hasClass('bp-detail-row')) {
        $this.remove();
    } else {
        $this.closest('.bp-detail-row').remove();
    }
    
    if ($parentTbl.hasClass('bprocess-table-subdtl')) {
        bpSetRowIndexDepth($parentTbl.parent(), window['bp_window_'+$uniqId]);
    } else {
        bpSetRowIndex($parentTbl.parent());
    }            
    return;
}
function bpPagingDetailRowRemove(mainSelector, elem) {
    var $row = $(elem);
    var $table = $row.closest('table[data-pager="true"]');
    $.ajax({
        type: 'post',
        url: 'mdcache/deleteRow', 
        data: {
            processId: mainSelector.attr('data-process-id'), 
            cacheId: $table.attr('data-cacheid'), 
            groupPath: $table.attr('data-table-path'), 
            rowIndex: $row.find('input[name*=".mainRowCount]"]').val()
        },
        async: false, 
        dataType: 'json', 
        success: function (data) {
            if (data.status == 'success') {
                $row.remove();
                bpDetailPagerSetFooterAmount($table, data.aggregateStr);
            }
        }
    });
    return;
}
function bpDetailRowsRemove(mainSelector, groupPath, elem) {
    if (groupPath.indexOf('.') !== -1 && typeof elem !== 'undefined' && elem !== 'open') {
        if ($(elem).hasClass('bp-detail-row')) {
            var $table = $(elem);
            if ($table.find("[data-table-path='"+groupPath+"']").length == 0) {
                $table = $(elem).closest('.bp-detail-row').parents('.bp-detail-row');
            }
        } else {
            var $table = $(elem).closest('.bp-detail-row').parents('.bp-detail-row');
            
            if ($table.length == 0) {
                var $table = $(elem).closest('.bp-detail-row');
            }
        }
        $table.find("[data-table-path='"+groupPath+"'] > .tbody").empty();
        
    } else {
        
        var $table = mainSelector.find("[data-table-path='"+groupPath+"']");
        var $savedRows = $table.find("> .tbody > .bp-detail-row.saved-bp-row");
        var $unSavedRows = $table.find("> .tbody > .bp-detail-row:not(.saved-bp-row)");
        
        if ($unSavedRows.length) {
            $unSavedRows.remove();
        }
        
        if ($savedRows.length) {
            var $rowState = $savedRows.find('input[data-field-name="rowState"]');
            
            if ($rowState.length) {
                $savedRows.addClass('removed-tr d-none');
                $rowState.val('removed');
                $savedRows.find("input.bigdecimalInit, input.decimalInit, input.numberInit, input.integerInit, input.longInit, input[data-path*='_bigdecimal']").attr('data-not-aggregate', '1');
            } else {
                $savedRows.remove();
            }
            bpDetailRowNumbering($table);
        }
    }

    return;
}
function bpDetailRowHighlight(mainSelector, elem, addRemove, colorName) {
    if (elem !== 'open') {
        
        var $elem = $(elem);
        
        if (typeof $elem.prop('tagName') !== 'undefined' && $elem.hasClass('bp-detail-row')) {
            var $row = $elem;
        } else {
            var $row = $elem.closest('.bp-detail-row');
        }
        
        if (typeof colorName !== 'undefined') {
            if (addRemove == 'set') {
                $row.addClass('bp-highlight-row-'+colorName);
            } else {
                $row.removeClass('bp-highlight-row-'+colorName);
            }
        } else {
            if (addRemove == 'set') {
                $row.addClass('bp-highlight-row');
            } else {
                $row.removeClass('bp-highlight-row');
            }
        }
    }
    return;
}
function getDetailRowCount(mainSelector, elem, groupPath) {
    
    if (elem === 'open') {
        var rowCount = 0;
        if (groupPath == 'pfProcessFileWidget') {
            var $section = mainSelector.find('[data-section-path="pfProcessFileWidget"] ul.list-view-file-new > li:not([data-attach-id="0"])');
            rowCount = $section.length;
        } else if (groupPath == 'pfProcessPhotoWidget') {
            var $section = mainSelector.find('[data-section-path="pfProcessPhotoWidget"] ul.list-view-photo > li[data-attach-id]');
            rowCount = $section.length;
        } else {
            rowCount = mainSelector.find("[data-table-path='"+groupPath+"'] > .tbody > .bp-detail-row:not(.removed-tr)").length;
        }
        return rowCount;
    } else {
        
        var $this = $(elem, mainSelector);
        
        if ($this.closest('.sidebar_detail').length) {
            
            var $oneLevelRow = mainSelector.find('.bprocess-table-dtl > .tbody > .currentTarget');
            
        } else {
            
            if (typeof $this.prop('tagName') !== 'undefined' && $this.hasClass('bp-detail-row')) {
                
                if ($this.find("[data-table-path='"+groupPath+"']").length) {
                    
                    var $oneLevelRow = $this;
                    
                } else {
                    
                    var $table = mainSelector.find("[data-table-path='"+groupPath+"']:eq(0)");
                    if ($table.hasClass('bprocess-table-subdtl')) {
                        var $oneLevelRow = mainSelector.find('.bprocess-table-dtl > .tbody > .currentTarget');
                    } else {
                        var $oneLevelRow = $table;
                    }
                }
                
            } else {
                var $oneLevelRow = $this.closest('.bp-detail-row');
            }
        }
        
        var $getPathElement = $oneLevelRow.find("[data-table-path='"+groupPath+"']"), 
            $getPathMainElement = mainSelector.find("[data-table-path='"+groupPath+"']");
        
        if ($getPathElement.length == 0 && $getPathMainElement.length) {
            return $getPathMainElement.find("> .tbody > .bp-detail-row:not(.removed-tr)").length;
        } else if ($getPathElement.length) {   
            return $getPathElement.find("> .tbody > .bp-detail-row:not(.removed-tr)").length;
        }
    }
    return;
}
function detailRowContains(mainSelector, elem, groupPath, lookupField, checkField) {
    var _ticket = false;
    mainSelector.find("[data-table-path='"+groupPath+"'] > .tbody > .bp-detail-row").each(function (_index, _row) {
        var $currentRow = $(_row);
        var $currentRowCellPath = $currentRow.find('td[data-cell-path="'+ lookupField +'"]');
        var _checkFieldValue = mainSelector.find('input[data-path="' + checkField + '"]').val();
        _ticket = false;
        if (typeof $currentRowCellPath.find('span[data-view-path="'+ lookupField +'"]') != 'undefined' && $currentRowCellPath.find('span[data-view-path="'+ lookupField +'"]').html() === _checkFieldValue) {
            _ticket = true;
        } else {
            if (typeof $currentRowCellPath.find('input[data-path="'+ lookupField +'"]') != 'undefined' && $currentRowCellPath.find('input[data-path="'+ lookupField +'"]').val() === _checkFieldValue) { 
                _ticket = true;
            }
        }
        if (_ticket) {
            return false;
        }
    });
    return _ticket;
}
function showConfirmDialog(mainSelector, elem, note, title, type, message, messageType) {
    var uniqId = mainSelector.attr('data-bp-uniq-id');
    var $dialogName = 'dialog-showconfirm-'+ uniqId;
    if (!$("#" + $dialogName).length) {
        $('<div id="' + $dialogName + '"></div>').appendTo('body');
    }
        
    if (type == 'open') {
        var $dialog = $("#" + $dialogName);
        
        $dialog.empty().append(note);
        $dialog.dialog({
            cache: false,
            resizable: false,
            bgiframe: true,
            autoOpen: false,
            title: title,
            width: 370,
            height: "auto",
            modal: true,
            open: function () {
                setTimeout(function(){
                    $dialog.dialog("option", "position", {my: "center", at: "center", of: window});
                }, 100);
            }, 
            close: function () {
                $dialog.empty().dialog('destroy').remove();
                uiDialogOverlayRemove();
            },
            buttons: [
                {text: plang.get('yes_btn'), class: 'btn green-meadow btn-sm', click: function () {
                    $dialog.dialog('close');
                    return true;
                }},
                {text: plang.get('no_btn'), class: 'btn blue-madison btn-sm', click: function () {
                    $dialog.dialog('close');
                    return false;
                }}
            ]
        });
        
        $dialog.dialog('open');
        
    } else {
        window[uniqId+'_dialog'] = $dialogName;
        window[uniqId+'_note'] = note;
        window[uniqId+'_title'] = title;
        window[uniqId+'_type'] = type;
        if (typeof message !== 'undefined' && typeof messageType != 'undefined') {
            window[uniqId+'_message'] = message;
            window[uniqId+'_messageType'] = messageType;
        }
    }
}
function setLookupCriteria(mainSelector, elem, lookupField, params) {
    bpSetLookupCriteria(mainSelector, elem, lookupField, params);
    return;
}
function bpSetLookupCriteria(mainSelector, elem, lookupField, params) {
    var $bpElem = getBpElement(mainSelector, elem, lookupField);
    if ($bpElem) {
        $bpElem.attr('data-criteria-param', params);
    }
    return;
}
function setKpiLookupCriteria(mainSelector, elem, lookupField, params) {
    var $getField = '', fieldPath = lookupField.split('.');
    var dtlCode = fieldPath[0].toLowerCase().trim();
    var $getRow = mainSelector.find("[data-dtl-code='"+dtlCode+"']");

    if ($getRow.length) {
        var $table = $getRow.closest("[data-table-path='kpiDmDtl']");
        var factName = fieldPath[1].trim();
        var groupPath = $table.attr('data-group-path');
        
        if (groupPath) {
            $getField = $getRow.find('[data-path="'+groupPath+'kpiDmDtl.'+factName+'"]:eq(0)');
        } else {
            $getField = $getRow.find('[data-path="kpiDmDtl.'+factName+'"]:eq(0)');
        }        
    }    
    if ($getField) {
        $getField.attr('data-criteria-param', params).removeClass('data-combo-set');
    }
    return;
}
function bpUnsetLookupCriteria(mainSelector, elem, lookupField) {
    var $bpElem = getBpElement(mainSelector, elem, lookupField);
    if ($bpElem) {
        $bpElem.removeAttr('data-criteria-param').removeClass('data-combo-set');
    }
    return;
}
function bpUnSetLookupCriteria(mainSelector, elem, lookupField) {
    var $bpElem = getBpElement(mainSelector, elem, lookupField);
    if ($bpElem) {
        $bpElem.removeAttr('data-criteria-param').removeClass('data-combo-set');
    }
    return;
}
function setLookupFieldCode(mainSelector, elem, lookupField, code) {
    var $bpElem = getBpElement(mainSelector, elem, lookupField);
    if ($bpElem) {
        $bpElem.parent().find("input[id*='_displayField']").val(code);
    }
    return;
}
function setLookupFieldName(mainSelector, elem, lookupField, name) {
    var $bpElem = getBpElement(mainSelector, elem, lookupField);
    if ($bpElem) {
        $bpElem.parent().find("input[id*='_nameField']").val(name);
    }
    return;
}
function bpSetLookupFieldCodeEnter(mainSelector, elem, lookupField, code) {
    var $bpElem = getBpElement(mainSelector, elem, lookupField);
    if ($bpElem) {
        var $codeField = $bpElem.parent().find("input[id*='_displayField']");
        $codeField.val(code);
        
        setLookupPopupCode($codeField, code);
    }
    return;
}
function setBpRowViewStyle(mainSelector, elem, fieldPath, styles) {
    var $this = $(elem, mainSelector), $oneLevelRow = $this.closest('tr');
    if ($oneLevelRow.find("[data-view-path='" + fieldPath + "']").length) {
        $oneLevelRow.find("[data-view-path='" + fieldPath + "']").attr('style', styles);
    } else {
        mainSelector.find("[data-view-path='" + fieldPath + "']").attr('style', styles);
    }
    return;
}
function setBpWGFieldValue(mainSelector, elem, widgetCode, fieldName, val) {
    if (widgetCode == 'payment') {
        mainSelector.find("input.bp-bill-"+fieldName.toLowerCase()).val(val);
    }
    return;
}
function openLookupPopup(mainSelector, elem, path) {
    
    var $bpSrcElem = getBpElement(mainSelector, elem, path);
    
    if (typeof $bpSrcElem == 'undefined' || $bpSrcElem == false) {
        return;
    }
    
    if (elem !== 'open' && typeof elem.prop('tagName') !== 'undefined' && elem.hasClass('bp-detail-row')) {
        var $parent = elem;
    } else if (elem === 'open' || elem.closest("table").parent().hasClass("bp-header-param")) {
        var $parent = mainSelector;         
    } else {
        var $parent = elem.closest('.bp-detail-row');
    }
    
    var $popupBtnSelector = $parent.find("div.meta-autocomplete-wrap[data-section-path='"+path+"'] button");
    var $popupLookupKeyBtnSelector = mainSelector.find("div.bp-add-ac-row[data-action-path='"+path+"'] button");
    if ($popupBtnSelector.length) {
        setTimeout(function() {
            $popupBtnSelector.click();
        }, 5);
    } else if ($popupLookupKeyBtnSelector.length) {
        setTimeout(function() {
            $popupLookupKeyBtnSelector.click();
        }, 5);
    } else {
        setTimeout(function() {
            mainSelector.find("div.meta-autocomplete-wrap[data-section-path='"+path+"'] button").click();
        }, 5);
    }
    
    return;
}
function bpOpenLookupCombo(mainSelector, elem, path) {
    var $bpSrcElem = getBpElement(mainSelector, elem, path);
    
    if (typeof $bpSrcElem == 'undefined' || $bpSrcElem == false) {
        return;
    }
    
    $bpSrcElem.select2('open');
    
    return;
}
function bpActiveTab(mainSelector, elem, tabIndex) {
    tabIndex = tabIndex - 1;
    var $tab = mainSelector.find("div.bp-tabs > ul > li > a[data-toggle='tab']:eq("+tabIndex+")");
    
    if ($tab.length) {
        $tab.removeClass('active').tab('show');
    }
    
    return;
}
function bpSetTabOrder(mainSelector, elem, tabIndexes) {
    var indexArr = tabIndexes.split(','), orderedTabArr = [];
    
    mainSelector.find("div.bp-tabs > ul > li > a[data-toggle='tab']").each(function(key){
        if (key in indexArr) {
            var tabIndex = indexArr[key].trim() - 1;
            
            orderedTabArr.push({
                tabHeader: mainSelector.find("div.bp-tabs > ul > li > a[data-toggle='tab']:eq("+tabIndex+")").parent().html()
            });
        }
    });
    mainSelector.find("div.bp-tabs > ul > li > a[data-toggle='tab']").parent().remove();
    mainSelector.find("div.bp-tabs > div.tab-content > div.tab-pane").removeClass('active');
    
    for (var i = 0; i < orderedTabArr.length; i++) {
        if (i === 0) {
            mainSelector.find("div.bp-tabs > ul.nav-tabs").append('<li>' + (orderedTabArr[i].tabHeader).replace('class=""', 'class="active"').replace('class=" "', 'class="active"') + '</li>');
            mainSelector.find("div.bp-tabs > div.tab-content > div#" + $(orderedTabArr[i].tabHeader).attr('href').replace('#', '')).addClass('active');
        } else {
            mainSelector.find("div.bp-tabs > ul.nav-tabs").append('<li>' + (orderedTabArr[i].tabHeader).replace('class=" active"', '').replace('class="active"', '') + '</li>');
        }
    }
    return;
}
function bpHideTab(mainSelector, elem, tabIndex) {
    tabIndex = tabIndex - 1;
    mainSelector.find("div.bp-tabs > ul > li > a[data-toggle='tab']:eq("+tabIndex+")").css({'display': 'none'});
    mainSelector.find("div.bp-tabs > div.tab-content > div.tab-pane:eq("+tabIndex+")").removeClass('active');
    return;
}
function bpShowTab(mainSelector, elem, tabIndex) {
    var tabs = mainSelector.find("div.bp-tabs > ul > li > a[data-toggle='tab']");
    tabIndex = tabIndex - 1;
    
    tabs.eq(tabIndex).css({'display': ''});
    
    if (tabs.length == 1) {
        tabs.eq(tabIndex).addClass('active');
        mainSelector.find("div.bp-tabs > div.tab-content > div.tab-pane:eq("+tabIndex+")").addClass('active');
    }
    return;
}
function bpSetTab(mainSelector, elem, fieldPath, tabName, appendCode) {
    
    var $getField = mainSelector.find("[data-path='"+fieldPath+"']");
    
    if ($getField.length == 0) {
        return;
    }
    
    if ($getField.prop('tagName') == 'INPUT' && $getField.attr('type') == 'hidden' && !$getField.hasClass('popupInit')) {
        return;
    }
    
    var appendType = (typeof appendCode === 'undefined') ? 'append' : appendCode; 
    var $parentTable = $getField.closest('table');
    
    if ($getField.hasClass('select2')) {
        var $getControl = mainSelector.find("div[data-section-path='"+fieldPath+"']:eq(0)");
        $getControl.find('select.select2').select2('destroy');
        var $getControlClone = $getControl.clone();
    } else {
        var $getControl = mainSelector.find("div[data-section-path='"+fieldPath+"']:eq(0)");
        var $getControlClone = $getControl.clone();
    }
    
    $getControl.remove();
    
    if ($parentTable.hasClass('bp-header-param')) {
        var $getLabel = mainSelector.find("label[data-label-path='"+fieldPath+"']");
        var $getLabelClone = $getLabel.clone();
    }
    
    $getLabel.remove();
    
    var $getTab = mainSelector.find("div.bp-tabs a[data-toggle='tab']:contains('"+tabName+"')"); 
    
    if ($getTab.length) {
        
        var $tabId = $getTab.attr('href').replace('#', '');
        var $tabContent = mainSelector.find("div#"+$tabId);
        
    } else {
        
        var $tabs = mainSelector.find("div.bp-tabs");
        var $tabId = getUniqueId(1);
        
        if ($tabs.length == 0) {
            
            var createTabHtml = '<div class="tabbable-line tabbable-tabdrop mt10 bp-tabs">'+
                '<ul class="nav nav-tabs">'+
                    '<li class="nav-item"><a href="#tab_'+$tabId+'" class="nav-link" data-toggle="tab" aria-expanded="true">'+tabName+'</a></li>'+
                '</ul>'+
                '<div class="tab-content">'+
                    '<div id="tab_'+$tabId+'" class="tab-pane"></div>'+
                '</div>'+
            '</div>';

            mainSelector.find("div.bp-header-param:eq(0)").after(createTabHtml);
            
        } else {
            
            var liHtml = '<li class="nav-item"><a href="#tab_'+$tabId+'" class="nav-link" data-toggle="tab" aria-expanded="true">'+tabName+'</a></li>';
            var paneHtml = '<div id="tab_'+$tabId+'" class="tab-pane"></div>';
            
            mainSelector.find("div.bp-tabs ul.nav-tabs").append(liHtml); 
            mainSelector.find("div.bp-tabs div.tab-content").append(paneHtml); 
        }
        
        var $getTab = mainSelector.find("div.bp-tabs a[data-toggle='tab']:contains('"+tabName+"')"); 
        
        var $tabId = $getTab.attr('href').replace('#', '');
        var $tabContent = mainSelector.find("div#"+$tabId);
    }
    
    var $tabContentTable = $tabContent.find("table.bp-header-param");
        
    var html = '<tr data-cell-path="'+fieldPath+'"><td class="text-right middle" style="width: 8.5%">'+$getLabelClone.wrap('<div>').parent().html()+'</td><td class="middle" style="width: 55%">'+$getControlClone.wrap('<div>').parent().html()+'</td></tr>';

    if ($tabContentTable.length) {
        var $tabContentTableBody = $tabContentTable.children('tbody');

        if (appendType == 'append') {
            $tabContentTableBody.append(html);
        } else {
            $tabContentTableBody.prepend(html);
        }

        Core.initBPInputType($tabContentTableBody);

    } else {

        var parentHtml = '<div class="table-scrollable table-scrollable-borderless bp-header-param">'+
                '<table class="table table-sm table-no-bordered bp-header-param"><tbody>'+html+'</tbody></table>'+
                '</div>';

        $tabContent.prepend(parentHtml);

        Core.initBPInputType($tabContent);
    }
    
    return;
}
function bpActiveProcessTab(mainSelector, elem, tabName) {
    var $tab = mainSelector.find('a[href*="bp_'+tabName+'"]');
    $tab.click();
    return;
}
function bpHideProcessTab(mainSelector, elem, tabName) {
    var $tab = mainSelector.find('a[href*="bp_'+tabName+'"]');
    $tab.closest('li.nav-item').hide();
    return;
}
function bpMessageChooseMeta(mainSelector, processId, responseData, msgPrc) {
    
    var $dialogName = 'dialog-choosemeta';
    if (!$("#" + $dialogName).length) {
        $('<div id="' + $dialogName + '"></div>').appendTo('body');
    }
    var $dialog = $('#' + $dialogName), isHideCloseButton = false;
    
    if (msgPrc.indexOf('[hideCloseButton]') !== -1) {
        msgPrc = msgPrc.replace('[hideCloseButton]', '');
        isHideCloseButton = true;
    }
    
    $.ajax({
        type: 'post',
        url: 'mdcommon/chooseMetaByExp',
        data: {processId: processId, msgPrc: msgPrc, responseData: responseData},
        dataType: 'json',
        beforeSend: function () {
            Core.blockUI({message: 'Loading...', boxed: true});
        },
        success: function (data) {
            $dialog.empty().append(data.html);
            
            var dialogOpts = {
                cache: false,
                resizable: true,
                bgiframe: true,
                autoOpen: false,
                title: data.title,
                width: 550,
                maxHeight: 550, 
                modal: true,
                closeOnEscape: isCloseOnEscape, 
                close: function () {
                    $dialog.empty().dialog('destroy').remove();
                },
                buttons: [
                    {text: plang.get('close_btn'), class: 'btn blue-hoki btn-sm', click: function () {
                        $dialog.dialog('close');
                    }}
                ]
            };
            
            if (isHideCloseButton) {
                dialogOpts.closeOnEscape = false;
                dialogOpts.open = function(event, ui) {
                    $('.ui-dialog-titlebar-close', ui.dialog || ui).remove();
                };
                delete dialogOpts.buttons;
            }
            
            $dialog.dialog(dialogOpts);
            $dialog.dialog('open');
            
            Core.unblockUI();
        },
        error: function () { alert("Error"); }
    });
    return;
}
function bpChooseMetaCaller(elem, param, mappingParams) {
    var typeCode = param['typeCode'].toLowerCase();
    var metaDataId = param['metaDataId'];
    var srcMetaCode = param['srcMetaCode'];
    
    if (typeCode == 'process') {
        $('#dialog-choosemeta').dialog('close');
        
        if (mappingParams != '') {
            _processPostParam = mappingParams;
        }
        if (param['isDefaultGet'] != '') {
            _processPostParam += '&defaultGetPf=1';
        }
        
        callWebServiceByMeta(metaDataId, true, '', false, {callerType: srcMetaCode, isMenu: false});
    }
    return;
}
function bpCallProcessByExp(mainSelector, elem, srcMetaCode, processId, paramsPath, renderType) {
    
    if (processId) {
        
        var params = ''; elem = (elem !== 'open') ? $(elem) : elem;
        
        if (paramsPath != '') {
            
            var paramsPathArr = paramsPath.split('|');

            for (var i = 0; i < paramsPathArr.length; i++) {
                var fieldPathArr = paramsPathArr[i].split('@');
                var fieldPath = fieldPathArr[0].trim();
                var inputPath = fieldPathArr[1].trim();
                var fieldValue = '';
                
                if (elem !== 'open' && typeof elem.prop('tagName') !== 'undefined' 
                    && elem.prop('tagName') == 'BUTTON' && elem.closest('td[data-group-num]').length) {
                    
                    var $cell = elem.closest('td[data-group-num]');
                    var $row = $cell.closest('tr');
                    var groupNum = $cell.attr('data-group-num');
                    var $field = $row.find('td[data-group-num="'+groupNum+'"]').find('[data-path="'+fieldPath+'"]');
                    
                    if ($field.length) {
                        fieldValue = $field.val();
                    }
                    
                } else {
                
                    var bpElem = getBpElement(mainSelector, elem, fieldPath);

                    if (bpElem) {
                        fieldValue = getBpRowParamNum(mainSelector, elem, fieldPath);
                    } else {
                        var bpViewElem = getBpRowViewElem(mainSelector, elem, fieldPath);
                        if (bpViewElem) {
                            if (bpViewElem.hasAttr('data-row-view-data')) {
                                fieldValue = bpGetLookupFieldValue(mainSelector, elem, fieldPath, 'id');
                            } else {
                                fieldValue = getBpRowParamNum(mainSelector, elem, fieldPath);
                            }
                        } else {
                            fieldValue = fieldPath;
                        }
                    }
                }

                params += inputPath + '=' + fieldValue + '&';
            }
        }
        
        if (params != '') {
            _processPostParam = params;
        }
        
        if (typeof renderType !== 'undefined' && renderType === 'main') {
            var callProcessId = elem.closest('.main-action-meta').data('process-id');
            var $dialogNameFromCallProcess = $('#dialog-businessprocess-' + callProcessId);
            $dialogNameFromCallProcess.empty().dialog('destroy').remove();
        }

        callWebServiceByMeta(processId, true, '', false, {callerType: srcMetaCode, isMenu: false});
    }
    
    return;
}
function bpCallProcessDefaultGetByExp(mainSelector, elem, srcMetaCode, processId, paramsPath) {
    
    if (processId) {
        
        var params = '';
        
        if (paramsPath != '') {
            
            var paramsPathArr = paramsPath.split('|');

            for (var i = 0; i < paramsPathArr.length; i++) {
                var fieldPathArr = paramsPathArr[i].split('@');
                var fieldPath = fieldPathArr[0].trim();
                var inputPath = fieldPathArr[1].trim();
                var fieldValue = '';

                var bpElem = getBpElement(mainSelector, elem, fieldPath); 

                if (bpElem) {
                    fieldValue = getBpRowParamNum(mainSelector, elem, fieldPath);
                } else {
                    var $bpViewElem = getBpRowViewElem(mainSelector, elem, fieldPath);
                    if ($bpViewElem) {
                        fieldValue = getBpRowParamNum(mainSelector, elem, fieldPath);
                    } else {
                        var viewElement = mainSelector.find('[data-view-path="'+fieldPath+'"]');
                        if (viewElement.length) {
                            fieldValue = viewElement.text();
                        } else {
                            fieldValue = fieldPath;
                        }
                    }
                }

                params += inputPath + '=' + fieldValue + '&';
            }
            
            params += 'defaultGetPf=1';
        }
        
        if (params != '') {
            _processPostParam = params;
        }
        
        callWebServiceByMeta(processId, true, '', false, {callerType: srcMetaCode, isMenu: false});
        
    } else {
        PNotify.removeAll();
        new PNotify({
            title: 'Warning',
            text: 'ProcessId олдсонгүй!',
            type: 'warning',
            sticker: false
        });        
    }
    
    return;
}
function bpCloseProcessByExp(mainSelector, processId) {
    
    if (processId == 'this') {
        setTimeout(function() {
            if (mainSelector.closest('.ui-dialog-content').length) {
                mainSelector.closest('.ui-dialog-content').dialog('close');
            } else {
                mainSelector.find('.bp-btn-back:eq(0)').click();
            }
        }, 100);
    } else if (processId != '') {
        var $processParent = $('div[data-process-id="'+processId+'"]:last');
        if ($processParent.length) {
            var $dialog = $processParent.closest('.ui-dialog-content');
            if ($dialog.length) {
                $dialog.dialog('close');
            } else {
                $processParent.find('.bp-btn-back:eq(0)').click();
            }
        }
    }
    
    return;
} 
function bpCallIndicatorProcessByExp(mainSelector, elem, srcMetaCode, processId, paramsPath, renderType) {
    
    if (processId) {
        
        var params = ''; elem = (elem !== 'open') ? $(elem) : elem;
        
        if (paramsPath != '') {
            
            var paramsPathArr = paramsPath.split('|');

            for (var i = 0; i < paramsPathArr.length; i++) {
                var fieldPathArr = paramsPathArr[i].split('@');
                var fieldPath = fieldPathArr[0].trim();
                var inputPath = fieldPathArr[1].trim();
                var fieldValue = '';
                
                if (elem !== 'open' && typeof elem.prop('tagName') !== 'undefined' 
                    && elem.prop('tagName') == 'BUTTON' && elem.closest('td[data-group-num]').length) {
                    
                    var $cell = elem.closest('td[data-group-num]');
                    var $row = $cell.closest('tr');
                    var groupNum = $cell.attr('data-group-num');
                    var $field = $row.find('td[data-group-num="'+groupNum+'"]').find('[data-path="'+fieldPath+'"]');
                    
                    if ($field.length) {
                        fieldValue = $field.val();
                    }
                    
                } else {
                
                    var bpElem = getBpElement(mainSelector, elem, fieldPath);

                    if (bpElem) {
                        fieldValue = getBpRowParamNum(mainSelector, elem, fieldPath);
                    } else {
                        var bpViewElem = getBpRowViewElem(mainSelector, elem, fieldPath);
                        if (bpViewElem) {
                            if (bpViewElem.hasAttr('data-row-view-data')) {
                                fieldValue = bpGetLookupFieldValue(mainSelector, elem, fieldPath, 'id');
                            } else {
                                fieldValue = getBpRowParamNum(mainSelector, elem, fieldPath);
                            }
                        } else {
                            fieldValue = fieldPath;
                        }
                    }
                }

                params += inputPath + '=' + fieldValue + '&';
            }
        }
        
        if (typeof isKpiIndicatorScript === 'undefined') {
            $.getScript('middleware/assets/js/addon/indicator.js').done(function() {
                bpCallIndicatorProcess(mainSelector, elem, processId, params);
            });
        } else {
            bpCallIndicatorProcess(mainSelector, elem, processId, params);
        }
    }
    
    return;
}
function bpFormValidateByExp(mainSelector) {
    var processForm = mainSelector.find('form:eq(0)');
    return bpFormValidate(processForm);
}
function bpClickButtonByExp(mainSelector, processId, buttonCode) {
    
    if (processId == 'this') {
        var $processElement = mainSelector;
    } else {
        var $processElement = $('div[data-process-id="'+processId+'"]:last');
    }
    
    if ($processElement.length) {
        buttonCode = buttonCode.toLowerCase();
        
        if ($processElement.closest('.ui-dialog').length) {
            var $processParent = $processElement.closest('.ui-dialog');
        } else {
            var $processParent = $processElement;
        }
        
        $processParent.find('.bp-btn-'+buttonCode+':eq(0)').click();
    }
    return;
}
function bpShowButton(mainSelector, buttonCode) {
    
    setTimeout(function() {
        var $dialog = mainSelector.closest('.ui-dialog');
        buttonCode = buttonCode.toLowerCase();
        
        if ($dialog.length) {

            $dialog.find('.bp-btn-'+buttonCode+':eq(0)').css({display:''});
            
            if (buttonCode == 'close') {
                $dialog.find('.ui-dialog-titlebar-'+buttonCode+':eq(0)').css({display:''});
            }
            
        } else {
            
            var $parent = mainSelector.parent();
            var $id = $parent.attr('id');
        
            $('#'+$id).bind('dialogopen', function(){
                var $processParent = mainSelector.closest('.ui-dialog');
                $processParent.find('.bp-btn-'+buttonCode+':eq(0)').css({display:''});
                
                if (buttonCode == 'close') {
                    $processParent.find('.ui-dialog-titlebar-'+buttonCode+':eq(0)').css({display:''});
                }
                return;
            });

            mainSelector.find('.bp-btn-'+buttonCode+':eq(0)').css({display:''});
        }
    }, 5);
    
    return;
}
function bpDeleteHideButton($dialog, loop) {
    if (loop <= 10) {
        setTimeout(function() {
            var $dialogButton = $dialog.find('.bp-btn-delete:eq(0)');
            if ($dialogButton.length) {
                $dialogButton.css({display:'none'});
            } else {
                bpDeleteHideButton($dialog, loop + 1);
            }
        }, 50);
    }
    return;
}
function bpHideButton(mainSelector, buttonCode) {
    
    buttonCode = buttonCode.toLowerCase();
    
    setTimeout(function() {
        var bpId = mainSelector.attr('data-process-id');
        var $dialog = mainSelector.closest('.ui-dialog').find('#dialog-businessprocess-'+bpId);
        
        if ($dialog.length) {
            $dialog = $dialog.parent('.ui-dialog');
            var $dialogButton = $dialog.find('.bp-btn-'+buttonCode+':eq(0)');
            
            if ($dialogButton.length) {
                $dialogButton.css({display:'none'});
            } else if (buttonCode == 'delete') {
                bpDeleteHideButton($dialog, 1);
            }
            
            if (buttonCode == 'close') {
                $dialog.find('.ui-dialog-titlebar-'+buttonCode+':eq(0)').css({display:'none'});
            }

        } else {

            var $parent = mainSelector.parent(), $id = $parent.attr('id');

            $('#' + $id).bind('dialogopen', function() {
                var $processParent = mainSelector.closest('.ui-dialog');
                $processParent.find('.bp-btn-'+buttonCode+':eq(0)').css({display:'none'});
                
                if (buttonCode == 'close') {
                    $processParent.find('.ui-dialog-titlebar-'+buttonCode+':eq(0)').css({display:'none'});
                }
                return;
            });

            mainSelector.find('.bp-btn-'+buttonCode+':eq(0)').css({display:'none'});
        }
    }, 10);
    
    return;
}
function bpRenameButton(mainSelector, buttonCode, rename) {
    setTimeout(function() {
        var $dialog = mainSelector.closest('.ui-dialog');
        buttonCode = buttonCode.toLowerCase();
        
        if ($dialog.length) {

            $dialog.find('.bp-btn-'+buttonCode+':eq(0)').text(plang.get(rename));
            
        } else {
            
            var $parent = mainSelector.parent();
            var $id = $parent.attr('id');
        
            $('#'+$id).bind('dialogopen', function(){
                var $processParent = mainSelector.closest('.ui-dialog');
                $processParent.find('.bp-btn-'+buttonCode+':eq(0)').text(plang.get(rename));
                return;
            });

            mainSelector.find('.bp-btn-'+buttonCode+':eq(0)').text(plang.get(rename));
        }
    }, 5);
    
    return;
}
function bpChangeProcessName(mainSelector, processName) {
    setTimeout(function() {
        var $dialog = mainSelector.closest('.ui-dialog');
        
        if ($dialog.length) {

            $dialog.find('.ui-dialog-title').html(plang.get(processName));
            
        } else {
            
            var $parent = mainSelector.parent(), $id = $parent.attr('id');
        
            $('#'+$id).bind('dialogopen', function() {
                var $processParent = mainSelector.closest('.ui-dialog');
                $processParent.find('.ui-dialog-title').html(plang.get(processName));
                return;
            });

            mainSelector.find('> form > .meta-toolbar .text-uppercase').html(plang.get(rename));
        }
    }, 5);
    
    return;
}
function bpSetFieldValueOtherProcessByExp(mainSelector, processId, setPath, value) {
    var $processElement = $('div[data-process-id="'+processId+'"]:last');
    setBpRowParamNum($processElement, $processElement.find('input:first'), setPath, value);
    return;
}
function bpGetFieldValueOtherProcessByExp(mainSelector, processId, getPath) {
    var $processElement = $('div[data-process-id="'+processId+'"]:last');
    
    if ($processElement.length) {
        return getBpRowParamNum($processElement, 'open', getPath);
    }
    
    return '';
}
function bpSetComboValueOtherProcessByExp(mainSelector, processId, setPath, value) {
    var $processElement = $('div[data-process-id="'+processId+'"]:last');
    var $bpElem = getBpElement($processElement, 'open', setPath);
    
    if ($bpElem && $bpElem.length && $bpElem.hasClass('select2')) {
        
        if ($bpElem.is('[multiple]')) {
            
            if (value != '') {
                var $zeroValue = $bpElem.find('option[value="-0"]');
                
                if ($zeroValue.length) {
                    $zeroValue.val(value);
                } else {
                    $bpElem.find('option:selected').val(value);
                }
            } else {
                var $zeroValue = $bpElem.find('option[value="-0"]');
                if ($zeroValue.length) {
                    $zeroValue.remove();
                }
            }
            
        } else {
            
            if (value != '') {
                $bpElem.find('option:selected').val(value);
            } else {
                var selectedVal = $bpElem.find('option:selected').val();
                if (selectedVal != '') {
                    $bpElem.find('option[value="'+selectedVal+'"]').remove();
                }
            }
        }
        
        $bpElem.trigger('change');
        
        if ($bpElem.hasClass('bp-field-with-popup-combo')) {
            var $button = $bpElem.closest('.input-group').find('button[data-choosetype]');
            $button.text($bpElem.select2('data').length);
        }
    }
    
    return;
}
function bpSetComboTextOtherProcessByExp(mainSelector, processId, setPath, value) {
    var $processElement = $('div[data-process-id="'+processId+'"]:last');
    var $bpElem = getBpElement($processElement, 'open', setPath);
    
    if ($bpElem && $bpElem.length && $bpElem.hasClass('select2')) {
        
        if ($bpElem.is('[multiple]')) {
            var $zeroValue = $bpElem.find('option[value="-0"]');
            if ($zeroValue.length) {
                $zeroValue.text(value);
            } else {
                $bpElem.find('option:selected').text(value);
            }
        } else {
            $bpElem.find('option:selected').text(value);
        }
        
        $bpElem.trigger('change');
    }
    
    return;
}
function bpChangeLookupByExp(mainSelector, elem, processId, lookupType, fieldPath, lookupId) {
    var $bpElem = getBpElement(mainSelector, elem, fieldPath);
    if ($bpElem) {
        var getAttrName = $bpElem.attr('name'), 
            getAttrPlaceholder = $bpElem.attr('placeholder'), 
            getAttrIsClear = $bpElem.attr('data-isclear'), 
            control = '';
        
        if ($bpElem.hasClass('fileInit')) {
            
        }
        
        if (lookupType == 'popup' || lookupType == 'popup_multicomma') {
            
            var currentValue = $bpElem.val(), addonAttr = '',  
                getOnClick = $bpElem.parent().find('button').attr('onclick'), 
                chooseType = "'single'";
            
            if (typeof getOnClick !== 'undefined') {
                getOnClick = getOnClick.split(',');
                if (getOnClick.hasOwnProperty(3)) {
                    chooseType = getOnClick[3].trim();
                }
            }
            
            if (lookupType == 'popup_multicomma') {
                chooseType = "'multicomma'";
                if (getAttrName.indexOf('[]') === -1) {   
                    getAttrName += '[]';
                }
            }
            
            if ($bpElem.hasAttr('data-out-param') && $bpElem.attr('data-out-param')) {
                addonAttr += ' data-out-param="'+$bpElem.attr('data-out-param')+'"';
            }
            
            if ($bpElem.hasAttr('data-in-param') && $bpElem.attr('data-in-param')) {
                addonAttr += ' data-in-param="'+$bpElem.attr('data-in-param')+'"';
            }
            
            if ($bpElem.hasAttr('data-in-lookup-param') && $bpElem.attr('data-in-lookup-param')) {
                addonAttr += ' data-in-lookup-param="'+$bpElem.attr('data-in-lookup-param')+'"';
            }

            control = '<div class="meta-autocomplete-wrap" data-section-path="'+fieldPath+'">'+
                '<div class="input-group double-between-input">'+
                    '<input id="'+fieldPath+'_valueField" name="'+getAttrName+'" class="popupInit" data-path="'+fieldPath+'" placeholder="'+getAttrPlaceholder+'" type="hidden"'+addonAttr+'>'+
                    '<input id="'+fieldPath+'_displayField" name="'+fieldPath+'_displayField" class="form-control input-sm meta-autocomplete lookup-code-autocomplete" placeholder="'+plang.get('code_search')+'" data-processid="'+processId+'" data-lookupid="'+lookupId+'" data-field-name="'+fieldPath+'" data-isclear="'+getAttrIsClear+'" type="text">'+
                    '<span class="input-group-btn">'+
                        '<button type="button" class="btn default btn-bordered input-sm mr0" onclick="dataViewSelectableGrid(\''+fieldPath+'\', \''+processId+'\', \''+lookupId+'\', '+chooseType+', \''+fieldPath+'\', this);"><i class="fa fa-search"></i></button>'+
                    '</span>'+     
                    '<span class="input-group-btn">'+
                        '<input id="'+fieldPath+'_nameField" name="'+fieldPath+'_nameField" class="form-control input-sm meta-name-autocomplete lookup-name-autocomplete" placeholder="'+plang.get('name_search')+'" data-processid="'+processId+'" data-lookupid="'+lookupId+'" data-field-name="'+fieldPath+'" data-isclear="'+getAttrIsClear+'" type="text">'+      
                    '</span>'+     
                '</div>'+
            '</div>';

            if ($bpElem.hasClass('popupInit')) {
                $bpElem.parent().parent().replaceWith(control);
            } else {
                $bpElem.replaceWith(control);
            }

            if (currentValue !== '') {
                var $bpElemLast = getBpElement(mainSelector, elem, fieldPath);
                setLookupPopupValue($bpElemLast, currentValue);
            }
            
        } else if (lookupType == 'string') {
            
            var labelName = mainSelector.find('label[data-label-path="'+fieldPath+'"]').text();
            control = '<input type="text" id="'+getAttrName+'" name="'+getAttrName+'" class="form-control form-control-sm stringInit" placeholder="'+labelName+'" data-path="'+fieldPath+'" data-field-name="'+fieldPath+'" data-isclear="'+getAttrIsClear+'">';
    
            if ($bpElem.hasClass('popupInit')) {
                $bpElem.parent().parent().replaceWith(control);
            } else {
                $bpElem.replaceWith(control);
            }
            
        } else if (lookupType == 'bigdecimal') {
            
            var labelName = mainSelector.find('label[data-label-path="'+fieldPath+'"]').text();
            control = '<input type="text" id="'+getAttrName+'" name="'+getAttrName+'" class="form-control form-control-sm bigdecimalInit" placeholder="'+labelName+'" data-path="'+fieldPath+'" data-field-name="'+fieldPath+'" data-isclear="'+getAttrIsClear+'">';
    
            if ($bpElem.hasClass('popupInit')) {
                $bpElem.parent().parent().replaceWith(control);
            } else {
                $bpElem.replaceWith(control);
            }
            
        } else if (lookupType == 'boolean') {
            
            var currentValue = $bpElem.val(), attrs = '', $parent = $bpElem.parent();
            
            if (currentValue == '1') {
                attrs = ' checked="checked"';
            }
            
            control = '<input type="checkbox" id="'+getAttrName+'" name="'+getAttrName+'" value="1" class="booleanInit" data-path="'+fieldPath+'" data-field-name="'+fieldPath+'" data-isclear="'+getAttrIsClear+'"'+attrs+'>';
    
            if ($bpElem.hasClass('popupInit')) {
                $bpElem.parent().parent().replaceWith(control);
            } else {
                $bpElem.replaceWith(control);
            }
            
            Core.initUniform($parent);
            
        } else if (lookupType == 'date') {
            
            var currentValue = $bpElem.val(), attrs = '', $parent = $bpElem.parent();
            
            if (currentValue != '') {
                attrs = ' value="'+currentValue+'"';
            }
            
            control = '<div class="dateElement input-group" data-section-path="'+fieldPath+'">';
                control += '<input type="text" name="'+getAttrName+'" class="form-control form-control-sm dateInit" data-path="'+fieldPath+'" data-field-name="'+fieldPath+'" data-isclear="0" placeholder="'+getAttrPlaceholder+'"'+attrs+'>';
                control += '<span class="input-group-btn"><button tabindex="-1" onclick="return false;" class="btn"><i class="fal fa-calendar"></i></button></span>';
            control += '</div>';
    
            if ($bpElem.hasClass('popupInit')) {
                $bpElem.parent().parent().replaceWith(control);
            } else {
                $bpElem.replaceWith(control);
            }
            
            Core.initDateInput($parent);
            
        } else if (lookupType == 'long') {
            
            var currentValue = $bpElem.val(), attrs = '', $parent = $bpElem.parent();
            
            if (currentValue != '') {
                attrs = ' value="'+currentValue+'"';
            }
            
            control = '<input type="text" id="'+getAttrName+'" name="'+getAttrName+'" class="form-control form-control-sm longInit" data-path="'+fieldPath+'" data-field-name="'+fieldPath+'" placeholder="'+getAttrPlaceholder+'" data-isclear="'+getAttrIsClear+'"'+attrs+'>';
    
            if ($bpElem.hasClass('popupInit')) {
                $bpElem.parent().parent().replaceWith(control);
            } else {
                $bpElem.replaceWith(control);
            }
            
            Core.initLongInput($parent);
            
        } else if (lookupType == 'file') {
            
            var currentValue = $bpElem.val(), title = 'No file selected', 
                attrs = '', hidden = '', buttons = '', $parent = $bpElem.parent();
            
            if (currentValue != '') {
                var fileExt = currentValue.split('.').pop().toLowerCase();
                
                title = currentValue;
                attrs = ' title="'+title+'"';
                hidden = '<input type="hidden" name="editfile_param['+fieldPath+']" value="'+currentValue+'">';
                
                buttons = '<a href="mdobject/downloadFile?fDownload=1&file='+currentValue+'" title="'+plang.get('download_btn')+'" class="btn btn-sm btn-light rounded-0"><i class="icon-download"></i></a>';
                buttons += '<a href="javascript:;" title="'+plang.get('view_btn')+'" data-url="'+currentValue+'" data-extension="'+fileExt+'" onclick="bpFilePreview(this);" class="btn btn-sm btn-light rounded-0"><i class="icon-file-text2"></i></a>';
                buttons += '<a href="javascript:;" title="'+plang.get('delete_btn')+'" onclick="bpFileChoosedRemove(this);" class="btn btn-sm btn-light rounded-0"><i class="icon-trash-alt"></i></a>';
            }
            
            control = '<div class="uniform-uploader" data-section-path="'+fieldPath+'">';
                control += '<input type="file" name="'+getAttrName+'" class="form-control form-control-sm fileInit form-control-uniform" data-path="'+fieldPath+'" data-field-name="'+fieldPath+'" data-isclear="'+getAttrIsClear+'"'+attrs+'>';
                control += hidden;
                control += '<span class="filename" data-text="No file selected" title="'+title+'">'+title+'</span>';
                control += buttons;
                control += '<button type="button" class="action btn btn-sm btn-light bp-file-choose-btn" onclick="bpFileChoose(this);">'+plang.get('select_file_btn')+'</button>';
            control += '</div>';
    
            if ($bpElem.hasClass('popupInit')) {
                $bpElem.parent().parent().replaceWith(control);
            } else {
                $bpElem.replaceWith(control);
            }
            
        } else if (lookupType == 'accountCode') {
            
            var $parent = $bpElem.parent();
            $bpElem.addClass('accountCodeMask');
            
            Core.initAccountCodeMask($parent);
        }
    }
    return;
}
function bpRunKpiProcessValue(mainSelector, elem, processCode, paramsPath, responsePath) {
    
    var paramData = [];
    var paramsPathArr = paramsPath.split('|');

    for (var i = 0; i < paramsPathArr.length; i++) {
        var fieldPathArr = paramsPathArr[i].split('@');
        var fieldPath = fieldPathArr[0].trim();
        var inputPath = fieldPathArr[1].trim();
        var fieldValue = '';

        var bpElem = getBpElement(mainSelector, elem, fieldPath);
        
        if (bpElem) {
            fieldValue = getBpRowParamNum(mainSelector, elem, fieldPath);
        } else {
            var kpiFieldPath = fieldPath.split('.');
            
            if (kpiFieldPath.length == 2 
                && mainSelector.find("tr[data-dtl-code='"+kpiFieldPath[0].toLowerCase()+"'] [data-path='kpiDmDtl."+kpiFieldPath[1]+"']").length) {
                
                fieldValue = bpGetKpiRowVal(mainSelector, elem, fieldPath);
                
            } else {
                fieldValue = fieldPath;
            }
        }

        paramData.push({
            fieldPath: fieldPath, 
            inputPath: inputPath, 
            value: fieldValue
        });
    }

    var response = $.ajax({
        type: 'post',
        url: 'mdwebservice/runProcessValue', 
        data: {
            processCode: processCode, 
            responsePath: responsePath, 
            paramData: paramData
        },
        dataType: 'json',
        async: false
    });

    return response.responseJSON;
}
function bpKpiPathChangeEvent(mainSelector, elem, fieldPath) {
    var fieldPath = fieldPath.split('.'), 
        dtlCode = fieldPath[0].toLowerCase(), 
        $getRow = mainSelector.find("[data-dtl-code='"+dtlCode+"']");

    if ($getRow.length) {
        var factName = fieldPath[1].toLowerCase(), paramRealPath = 'kpiDmDtl.'+factName;
        var groupPath = $getRow.closest('[data-group-path]').attr('data-group-path');
        
        if (groupPath) {
            var $getField = $getRow.find('[data-path="'+groupPath + paramRealPath+'"]:eq(0)');
        } else {
            var $getField = $getRow.find('[data-path="'+paramRealPath+'"]:eq(0)');
        }
        
        if ($getField.length) {
            $getField.trigger('change');
        }
    }
    
    return;
}
function bpSetKpiRowVal(mainSelector, elem, fieldPath, val) {
    
    var fieldPath = fieldPath.split('.'), 
        dtlCode = fieldPath[0].toLowerCase(), 
        $getRow = mainSelector.find("[data-dtl-code='"+dtlCode+"']");

    if ($getRow.length) {
        var factName = fieldPath[1].toLowerCase(), paramRealPath = 'kpiDmDtl.'+factName;
        var groupPath = $getRow.closest('[data-group-path]').attr('data-group-path');
        
        if (groupPath) {
            var $getField = $getRow.find('[data-path="'+groupPath + paramRealPath+'"]:eq(0)');
        } else {
            var $getField = $getRow.find('[data-path="'+paramRealPath+'"]:eq(0)');
        }

        if ($getField.hasClass('radioInit')) {

            $("input[type='radio'][value='"+val+"']", $getField).prop('checked', true);
            $.uniform.update($("input[type='radio']", $getField));

        } else if ($getField.hasClass('longInit') 
            || $getField.hasClass('numberInit') 
            || $getField.hasClass('decimalInit') 
            || $getField.hasClass('integerInit') 
            || $getField.hasClass('bigdecimalInit') 
            || $getField.hasClass('kpiDecimalInit')) { 

            if (val !== '' && val !== null) {
                $getField.autoNumeric('set', val);
            } else {
                $getField.autoNumeric('set', '');
            }

        } else if ($getField.hasClass('dateInit')) {

            if (val !== '' && val !== null) {
                $getField.datepicker('update', date('Y-m-d', strtotime(val)));
            } else {
                $getField.datepicker('update', null);
            }

        } else if ($getField.hasClass('datetimeInit')) {

            if (val !== '' && val !== null) {
                $getField.val(date('Y-m-d H:i:s', strtotime(val)));
            } else {
                $getField.val('');
            }

        } else if ($getField.hasClass('popupInit')) {   

            setLookupPopupValue($getField, val);

        } else if ($getField.hasClass('booleanInit')) {  

            checkboxCheckerUpdate($getField, val);

        } else if ($getField.hasClass('select2')) {

            $getField.select2('val', val);

        } else {                                              
            $getField.val(val);                        
        }
    }
    
    return;
}
function bpGetKpiRowVal(mainSelector, elem, fieldPath) {
    
    var selectedVal = '', fieldPath = fieldPath.split('.');
        
    var dtlCode = fieldPath[0].toLowerCase().trim();
    var $getRow = mainSelector.find("[data-dtl-code='"+dtlCode+"']");

    if ($getRow.length) {
        
        var $table = $getRow.closest("[data-table-path='kpiDmDtl']");
        var factName = fieldPath[1].trim();
        var groupPath = $table.attr('data-group-path');
        
        if (groupPath) {
            var $getField = $getRow.find('[data-path="'+groupPath+'kpiDmDtl.'+factName+'"]:eq(0)');
        } else {
            var $getField = $getRow.find('[data-path="kpiDmDtl.'+factName+'"]:eq(0)');
        }

        if ($getField.hasClass('radioInit')) {

            var selected = $("input[type='radio']:checked", $getField);
            if (selected.length > 0) {
                selectedVal = selected.val();
            }
 
        } else if ($getField.hasClass('numberInit') 
                || $getField.hasClass('decimalInit') 
                || $getField.hasClass('integerInit') 
                || $getField.hasClass('bigdecimalInit') 
                || $getField.hasClass('kpiDecimalInit') 
                || $getField.hasClass('longInit')) {   

            selectedVal = Number($getField.autoNumeric('get'));

        } else if ($getField.hasClass('booleanInit')) {
            selectedVal = $getField.is(':checked');
        } else {
            selectedVal = $getField.val();
        } 
    }
    
    return selectedVal;
}
function bpGetKpiControlCode(mainSelector, elem, fieldPath) {
    
    var selectedVal = '', fieldPath = fieldPath.split('.');
    
    if (fieldPath.length == 2) {
        
        var dtlCode = fieldPath[0].toLowerCase().trim(), factName = fieldPath[1].trim();
        var $getRow = mainSelector.find("table[data-table-path='kpiDmDtl'] > tbody > tr[data-dtl-code='"+dtlCode+"']");
        
        if ($getRow.length) {
            
            var $getField = $getRow.find('[data-field-name="'+factName+'"]:eq(0)');
            
            if ($getField.hasClass('radioInit')) {

                var selected = $("input[type='radio']:checked", $getField);
                if (selected.length > 0) {
                    selectedVal = selected.val();
                }

            } else if ($getField.hasClass('numberInit') 
                    || $getField.hasClass('decimalInit') 
                    || $getField.hasClass('integerInit') 
                    || $getField.hasClass('bigdecimalInit') 
                    || $getField.hasClass('kpiDecimalInit') 
                    || $getField.hasClass('longInit')) { 

                selectedVal = Number($getField.autoNumeric('get'));

            } else if ($getField.hasClass('dropdownInput')) {
                
                var combobox = $('option:selected', $getField);
                selectedVal = (combobox.val() != '') ? combobox.attr('param') : '';
                
            } else if ($getField.hasClass('popupInit')) {
                
                var $parent = $getField.closest('.meta-autocomplete-wrap');
                selectedVal = $parent.find('input.lookup-code-autocomplete').val();
                
            } else {
                selectedVal = $getField.val();
            }
        }
    }
    
    return selectedVal;
}
function bpKpiEnable(mainSelector, elem, fieldPath) {
    var fieldPath = fieldPath.split('.');
    
    if (fieldPath.length == 2) {
        
        var dtlCode = fieldPath[0].toLowerCase();
        var factName = fieldPath[1].toLowerCase();
        var $getRow = mainSelector.find("[data-dtl-code='"+dtlCode+"']");

        if ($getRow.length) {
            var $getField = $getRow.find('[data-field-name="'+factName+'"]:eq(0)');

            if ($getField.hasClass('md-radio')) { 
                
                var $elements = $getRow.find('[data-field-name="'+factName+'"]');
                if ($elements.length) {
                    $elements.removeAttr('onclick style data-isdisabled tabindex');
                    $elements.closest('.radio').removeClass('disabled');
                    $.uniform.update($elements);
                }
        
            } else if ($getField.hasClass('select2')) {  
                $getRow.find("div[data-s-path='"+$getField.attr('data-path')+"']").removeClass('select2-container-disabled');
                $getField.select2('readonly', false).select2('enable');
            } else if ($getField.hasClass('fileInit')) {
                $getField.removeAttr('readonly disabled onkeydown').removeClass('disable-click');
            } else if ($getField.hasClass('popupInit')) {

                var $codeName = $getField.closest('div.meta-autocomplete-wrap');

                if ($codeName.length) {
                    $codeName.find("input[type='text']").removeAttr('readonly disabled tabindex');
                    $codeName.find("button").removeAttr('style disabled');                
                }
            } else {
                $getField.removeAttr('readonly disabled');
            }
        }
    }
    return;
}
function bpKpiDisable(mainSelector, elem, fieldPath) {
    
    var fieldPath = fieldPath.split('.');
    
    if (fieldPath.length == 2) {
        
        var dtlCode = fieldPath[0].toLowerCase();
        var factName = fieldPath[1].toLowerCase();
        var $getRow = mainSelector.find("[data-dtl-code='"+dtlCode+"']");
        
        if ($getRow.length) {
            
            var $getField = $getRow.find('[data-field-name="'+factName+'"]:eq(0)');

            if ($getField.hasClass('md-radio')) {
                
                var $elements = $getRow.find('[data-field-name="'+factName+'"]');
                if ($elements.length) {
                    $elements.attr({'data-isdisabled': 'true', style: "cursor: not-allowed", 'tabindex': '-1'});
                    $elements.closest('.radio').addClass('disabled');
                }

            } else if ($getField.hasClass('select2')) {  

                //$getField.select2('readonly', true);
                $getRow.find("div[data-s-path='"+$getField.attr('data-path')+"']").addClass('select2-container-disabled');

            } else if ($getField.hasClass('fileInit')) {  

                $getField.attr({'readonly': 'readonly', 'onkeydown': 'return false;'}).addClass('disable-click');
                
            } else if ($getField.hasClass('popupInit')) {

                var $codeName = $getField.closest('div.meta-autocomplete-wrap');

                if ($codeName.length) {
                    $codeName.find("input[type='text']").attr({'readonly': 'readonly', 'tabindex': '-1'});
                    $codeName.find("button").attr('style', 'pointer-events: none; background-color: #eeeeee !important').prop("disabled", true);
                }

            } else if ($getField.hasClass('md-check')) { 
                $getField.closest('.checkInit').find('input[type="checkbox"]').attr('readonly', 'readonly');
            } else {
                $getField.attr('readonly', 'readonly');
            }
        }
    }
    return;
}
function bpKpiShow(mainSelector, elem, fieldPath) {
    var fieldPath = fieldPath.split('.');
    
    if (fieldPath.length == 2) {
        var dtlCode = fieldPath[0].toLowerCase();
        var factName = fieldPath[1].toLowerCase();
        
        var $getRow = mainSelector.find("table[data-table-path='kpiDmDtl'] > tbody > tr[data-dtl-code='"+dtlCode+"']");

        if ($getRow.length) {
            
            if ($getRow.hasAttr('data-formkpi-row')) {
                $getRow.show();
            } else {
                var $getField = $getRow.find('[data-path="kpiDmDtl.'+factName+'"]:eq(0), [data-kpi-path="kpiDmDtl.'+factName+'"]');

                if ($getField.hasClass('radioInit')) {
                    $getField.css({'display': ''});
                } else {
                    $getField.css({'display': ''});
                }
            }
        }
    }
    return;
}
function bpKpiHide(mainSelector, elem, fieldPath) {
    var fieldPath = fieldPath.split('.');
    
    if (fieldPath.length == 2) {
        var dtlCode = fieldPath[0].toLowerCase();
        var factName = fieldPath[1].toLowerCase();
        
        var $getRow = mainSelector.find("table[data-table-path='kpiDmDtl'] > tbody > tr[data-dtl-code='"+dtlCode+"']");

        if ($getRow.length) {
            
            if ($getRow.hasAttr('data-formkpi-row')) {
                $getRow.hide();
            } else {
                var $getField = $getRow.find('[data-path="kpiDmDtl.'+factName+'"]:eq(0), [data-kpi-path="kpiDmDtl.'+factName+'"]');

                if ($getField.hasClass('radioInit')) {
                    $getField.css({'display': 'none'});
                } else {
                    $getField.css({'display': 'none'});
                }
            }
        }
    }
    return;
}
function bpKpiRequired(mainSelector, elem, fieldPath) {
    var fieldPath = fieldPath.split('.');
    
    if (fieldPath.length == 2) {
        var dtlCode = fieldPath[0].toLowerCase();
        var factName = fieldPath[1].toLowerCase();
        var $getRow = mainSelector.find("table[data-table-path='kpiDmDtl'] > tbody > tr[data-dtl-code='"+dtlCode+"']");

        if ($getRow.length) {
            var $getField = $getRow.find('[data-field-name="'+factName+'"]:eq(0)');
            var $titleName = $getField.closest('td').find('.title-name');
            
            if ($getField.is(':radio')) {
                $getRow.find('input[data-field-name="'+factName+'"]').attr('required', 'required');
            } else if (!$getField.parent().find('.select2-container-disabled').length) {
                $getField.attr('required', 'required');
            }
            
            if ($titleName.length) {
                $titleName.find('span.required').remove();
                $titleName.prepend('<span class="required">*</span>');
            }
        }
    }
    return;    
}
function bpKpiNonRequired(mainSelector, elem, fieldPath) {
    var fieldPath = fieldPath.split('.');
    
    if (fieldPath.length == 2) {
        var dtlCode = fieldPath[0].toLowerCase();
        var factName = fieldPath[1].toLowerCase();
        
        var $getRow = mainSelector.find("table[data-table-path='kpiDmDtl'] > tbody > tr[data-dtl-code='"+dtlCode+"']");

        if ($getRow.length) {
            var $getField = $getRow.find('[data-field-name="'+factName+'"]:eq(0)');

            $getField.removeAttr('required');
            $getField.closest('td').find('.title-name').find('span.required').remove();
            
            /*if (getField.hasClass('radioInit')) {

                $("input[type='radio']", getField).prop('disabled', true);
                $.uniform.update($("input[type='radio']", getField));

            } else if (getField.hasClass('select2')) {  

                getField.select2('required', true);

            } else {
                getField.attr('required', 'required');
            }*/
        }
    }
    return;
}
function bpKpiDetailButton(mainSelector, elem, buttonPath, buttonText, buttonIcon, templateId, groupPath, bookIdPath, readonly) {
    if (elem !== 'open') {
        
        if (elem.prop('tagName') == 'TR') {
            var $row = elem;
        } else {
            var $row = elem.closest('tr');
        }
        
        if (typeof bookIdPath == 'undefined') {
            var bookIdPath = '';
        }
        
        if (typeof readonly == 'undefined') {
            var readonly = false;
        }
        
        $row.find('button[data-path="'+buttonPath+'"]')
            .text(buttonText)
            .attr('onclick', "bpKpiSubDetail(this, '"+templateId+"', '"+groupPath+"', '"+bookIdPath+"', "+readonly+");")
            .addClass('bp-btn-subkpi');
    }
    
    return;
}
function bpSetRowIndexKpiDetail(row) {
    var rowIndex = row.index(), 
        $el = row.find('tr[data-is-input="1"]'), 
        len = $el.length, i = 0;    
    
    for (i; i < len; i++) { 
        var $subElement = $($el[i]).find('input[data-path], select[data-path], textarea[data-path]');
        var slen = $subElement.length, j = 0;
        for (j; j < slen; j++) { 
            var $inputThis = $($subElement[j]);
            var $inputName = $inputThis.attr('name');
            //$inputThis.attr('name', $inputName.replace(/^(.*)(\[[0-9]+\])(.*)$/, '$1[' + rowIndex + ']$3'));
            $inputThis.attr('name', $inputName.replace(/^(.*)(\[[0-9]+\])(\[\])$/, '$1[' + rowIndex + ']['+i+']'));
        }
    }
    
    return;
}
function bpKpiSubDetail(elem, templateId, groupPath, bookIdPath, readonly) {
    
    var $this = $(elem), $cell = $this.closest('td'), $row = $this.closest('tr'), 
        $dialogCell = $cell.find('.kpi-sub-dialog'), 
        buttonName = plang.get('save_btn'), buttonClass = 'btn btn-sm green-meadow', 
        isReadonly = false;
    
    if (typeof readonly != 'undefined' && readonly) {
        buttonName = plang.get('close_btn');
        buttonClass = 'btn btn-sm blue-madison';
        isReadonly = true;
    }
                    
    if (!$dialogCell.length) { 
        
        var postData = {
            templateId: templateId, 
            uniqId: getUniqueId(1), 
            viewMode: 'grid', 
            groupPath: groupPath + '.', 
            bookId: (bookIdPath !== '' ? $row.find('input[data-path="'+bookIdPath+'"]').val() : ''), 
            methodId: $this.closest('div[data-process-id]').attr('data-process-id')
        };
        
        if (postData['bookId'] !== '') {
            var $getProcessPath = $row.closest('form').find('input[data-path="kpiSubDtlGetProcess"]');
            
            if ($getProcessPath.length && $getProcessPath.val() !== '') {
                postData['getProcessCode'] = $getProcessPath.val();
            }
        }
        
        if (isReadonly) {
            postData.viewMode = 'print';
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
                    
                    $cell.append('<div class="hide kpi-sub-dialog"></div>');
                    var $dialog = $cell.find('.kpi-sub-dialog');
                    $dialog.append('<div class="row">'+data.html+'</div>');
                    
                    var $parent = $(elem).closest('.main-action-meta').parent();
                    var $centerSidebar = $parent.find('div.center-sidebar');
                    var $blSection = $cell.closest('.overflow-auto.bl-section');
                    
                    $parent.css('position', 'static');
                    $centerSidebar.css('position', 'static');
                    $parent.parent().css('overflow', 'inherit');
                    $centerSidebar.parent().css('overflow', 'inherit');
                    
                    $cell.closest('div[data-parent-path="kpiDmDtl"]').css('overflow', 'inherit');
                    $cell.parents('.content-wrapper').css('overflow', 'inherit');
                    $cell.closest('div.col-md-12').css('position', 'static');
                    
                    if ($blSection.length) {
                        $blSection.addClass('overflow-inherit');
                    }
                    
                    if ($parent.hasClass('ui-dialog-content')) {
                        $parent.addClass('overflow-inherit');
                    }
            
                    $dialog.dialog({
                        appendTo: $cell,
                        cache: false,
                        resizable: true,
                        draggable: false,
                        bgiframe: true,
                        autoOpen: false,
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
                            
                            $cell.closest('div[data-parent-path="kpiDmDtl"]').css('overflow', '');
                            $cell.parents('.content-wrapper').css('overflow', '');
                            $cell.closest('div.col-md-12').css('position', '');
                            
                            if ($blSection.length) {
                                $blSection.removeClass('overflow-inherit');
                            }
                            
                            if ($parent.hasClass('ui-dialog-content')) {
                                $parent.removeClass('overflow-inherit');
                            }
                            
                            $row.find('table[data-table-path="'+groupPath+'.kpiDmDtl"] > tbody').empty();
                            
                            if (isReadonly) {
                                $dialog.dialog('destroy').remove();
                            } else {
                                bpSetRowIndexKpiDetail($row);
                            }
                        },                                
                        buttons: [
                            {text: buttonName, class: buttonClass, click: function() {
                                
                                if (isReadonly) {
                                    
                                    $dialog.dialog('close');
                                    
                                } else {
                                    
                                    var validDtl = true, 
                                        $requiredFields = $dialog.find('input:not(:radio),textarea,select').filter('[required="required"]'), 
                                        $requiredRadios = $dialog.find('input[type="radio"][required="required"]').closest('.radioInit');

                                    if ($requiredFields.length) {
                                        $requiredFields.removeClass('error');
                                        $requiredFields.each(function() {
                                            var $requiredField = $(this);
                                            if ($requiredField.val() == '') {
                                                $requiredField.addClass('error');  
                                                validDtl = false;
                                            }
                                        });
                                    }

                                    if ($requiredRadios.length) {
                                        $requiredRadios.removeClass('error');
                                        $requiredRadios.each(function() {
                                            var $requiredRadio = $(this);
                                            if ($requiredRadio.find('input:checked').length == 0) {
                                                $requiredRadio.addClass('error');  
                                                validDtl = false;
                                            }
                                        });
                                    }

                                    if (validDtl) {

                                        $this.trigger('change');
                                        $dialog.dialog('close');

                                    } else {
                                        PNotify.removeAll();
                                        new PNotify({
                                            title: 'Warning',
                                            text: 'Дэлгэрэнгүй үзүүлэлтийг бүрэн бөглөнө үү',
                                            type: 'warning',
                                            addclass: pnotifyPosition,
                                            sticker: false
                                        });
                                    }
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
            error: function () { alert("Error"); Core.unblockUI(); }
        }).done(function() { Core.unblockUI(); });   
        
    } else {
        
        var $parent = $(elem).closest('.main-action-meta').parent();
        var $centerSidebar = $parent.find('div.center-sidebar');
        var $blSection = $cell.closest('.overflow-auto.bl-section');

        $parent.css('position', 'static');
        $centerSidebar.css('position', 'static');
        $parent.parent().css('overflow', 'inherit');
        $centerSidebar.parent().css('overflow', 'inherit');

        $cell.closest('div[data-parent-path="kpiDmDtl"]').css('overflow', 'inherit');
        $cell.parents('.content-wrapper').css('overflow', 'inherit');
        $cell.closest('div.col-md-12').css('position', 'static');
        
        if ($blSection.length) {
            $blSection.addClass('overflow-inherit');
        }
        
        if ($parent.hasClass('ui-dialog-content')) {
            $parent.addClass('overflow-inherit');
        }

        $dialogCell.dialog({
            appendTo: $cell,
            cache: false,
            resizable: true,
            draggable: false,
            bgiframe: true,
            autoOpen: false,
            title: 'Form',
            width: 800, 
            height: 'auto',
            maxHeight: $(window).height() - 10, 
            modal: true, 
            closeOnEscape: isCloseOnEscape, 
            close: function () { 

                $parent.css('position', '');
                $centerSidebar.css('position', '');
                $parent.parent().css('overflow', '');
                $centerSidebar.parent().css('overflow', 'auto');

                $cell.closest('div[data-parent-path="kpiDmDtl"]').css('overflow', '');
                $cell.parents('.content-wrapper').css('overflow', '');
                $cell.closest('div.col-md-12').css('position', '');
                
                if ($blSection.length) {
                    $blSection.removeClass('overflow-inherit');
                }
                
                if ($parent.hasClass('ui-dialog-content')) {
                    $parent.removeClass('overflow-inherit');
                }

                bpSetRowIndexKpiDetail($row);
            },                                
            buttons: [
                {text: plang.get('save_btn'), class: 'btn btn-sm green-meadow', click: function() {

                    var validDtl = true;
                    $dialogCell.find('input,textarea,select').filter('[required="required"]').removeClass('error');

                    $dialogCell.find('input,textarea,select').filter('[required="required"]').each(function(){
                        if (($(this).attr('id') != 'accountId_displayField' && $(this).attr('id') != 'accountId_nameField') && $(this).val() == '') {
                            $(this).addClass('error');  
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
                            text: 'Дэлгэрэнгүй үзүүлэлтийг бүрэн бөглөнө үү',
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
function bpHideTreeview(mainSelector) {
    mainSelector.find("div.bp-treeview-panel").css({'display': 'none'});
    mainSelector.find("div.bp-treeview-body").removeClass('col-md-9').addClass('col-md-12');
    return;
}
function bpShowTreeview(mainSelector) {
    mainSelector.find("div.bp-treeview-panel").css({'display': ''});
    mainSelector.find("div.bp-treeview-body").removeClass('col-md-12').addClass('col-md-9');
    return;
}
function bpHideSidebar(mainSelector, elem, groupPath, sidebarIndex) {
    
    sidebarIndex = sidebarIndex - 1;
    
    if (elem !== 'open') {
        
        elem = $(elem);
        var _this = $(elem, mainSelector);
        
        if (_this.prop('tagName') === 'TR') {
            _this.find('.sidebarDetailSection').find('p.property_page_title:eq('+sidebarIndex+'), div.panel:eq('+sidebarIndex+')').css({'display': 'none'});
            return;
        }

        var _oneLevelRow = _this.closest('.bp-detail-row');    
        _oneLevelRow.find('.sidebarDetailSection').find('p.property_page_title:eq('+sidebarIndex+'), div.panel:eq('+sidebarIndex+')').css({'display': 'none'});
            
        return;
        
    } else {  
        var el = mainSelector.find("[data-table-path='"+groupPath+"'] > .tbody > .bp-detail-row");
        var len = el.length, i = 0;
        for (i; i < len; i++) { 
            $(el[i]).find('.sidebarDetailSection').find('p.property_page_title:eq('+sidebarIndex+'), div.panel:eq('+sidebarIndex+')').css({'display': 'none'});
        }
    }   
    
    return;
}
function bpShowSidebar(mainSelector, elem, groupPath, sidebarIndex) {
    
    sidebarIndex = sidebarIndex - 1;
    
    if (elem !== 'open') {
        
        elem = $(elem);
        var _this = $(elem, mainSelector);
        
        if (_this.prop('tagName') === 'TR') {
            _this.find('.sidebarDetailSection').find('p.property_page_title:eq('+sidebarIndex+'), div.panel:eq('+sidebarIndex+')').css({'display': ''});
            return;
        }
        
        var _oneLevelRow = _this.closest('tr');    
        _oneLevelRow.find('.sidebarDetailSection').find('p.property_page_title:eq('+sidebarIndex+'), div.panel:eq('+sidebarIndex+')').css({'display': ''});
            
        return;
        
    } else {  
        var el = mainSelector.find("[data-table-path='"+groupPath+"'] > .tbody > .bp-detail-row");
        var len = el.length, i = 0;
        for (i; i < len; i++) { 
            $(el[i]).find('.sidebarDetailSection').find('p.property_page_title:eq('+sidebarIndex+'), div.panel:eq('+sidebarIndex+')').css({'display': ''});
        }
    }   
    
    return;
}
function hideDetailFilter(mainSelector, elem, groupPath) {
    mainSelector.find("table[data-table-path='"+groupPath+"'] > thead > tr.bp-filter-row").css({'display': 'none'});
    return;
}
function showDetailFilter(mainSelector, elem, groupPath) {
    mainSelector.find("table[data-table-path='"+groupPath+"'] > thead > tr.bp-filter-row").css({'display': ''});
    return;
}
function bpHideDetailHeader(mainSelector, elem, groupPath) {
    mainSelector.find("table[data-table-path='"+groupPath+"'] > thead").css({'display': 'none'});
    return;
}
function bpShowDetailHeader(mainSelector, elem, groupPath) {
    mainSelector.find("table[data-table-path='"+groupPath+"'] > thead").css({'display': ''});
    return;
}
function detailActionCriteria(mainSelector, elem, groupPath, actionName, criteria, rowType) {
    actionName = actionName.trim().toLowerCase();
    criteria = criteria.trim().toLowerCase();
    var actionClass = actionName.replace('addrownum', 'bp-add-one-row-num')
                                .replace('addrow', 'bp-add-one-row') 
                                .replace('multirow', 'bp-add-multi-row')
                                .replace('autocomplete', 'bp-add-ac-row')
                                .replace('save', 'bp-group-save')
                                .replace('remove', 'bp-remove-row')
                                .replace('fullscreen', 'bp-detail-fullscreen');
    
    if (actionClass === 'bp-remove-row') {
        if (criteria === 'hide') {
            mainSelector.find("[data-table-path='"+groupPath+"'] .bp-remove-row").css({display: 'none'});
        } else if (criteria === 'show') {
            mainSelector.find("[data-table-path='"+groupPath+"'] .bp-remove-row").css({display: ''});
        }
    } else if (actionClass === 'rownumber') {
        
        if (criteria === 'hide') {
            mainSelector.find("[data-table-path='"+groupPath+"'] .bp-dtl-rownumber").css({display: 'none'});
        } else if (criteria === 'show') {
            mainSelector.find("[data-table-path='"+groupPath+"'] .bp-dtl-rownumber").css({display: ''});
        }
        
    } else {
        if (actionClass === 'bp-add-one-row-num') {
            
            var addRowBtn = mainSelector.find("[data-action-path='"+groupPath+"'][class*='bp-add-one-row']");
            var addRowNum = addRowBtn.prev('input.bp-add-one-row-num');
            
            if (addRowBtn.length && addRowNum.length == 0) {
                
                rowType = (typeof rowType === 'undefined') ? 'new' : rowType;
                
                if (addRowBtn.css('display') != 'none') {
                    addRowBtn.before('<input type="text" class="form-control input-xs float-left bp-add-one-row-num integerInit" data-v-min="1" data-v-max="1000" data-addrowtype="'+rowType+'">');
                } else {
                    addRowBtn.before('<input type="text" class="form-control input-xs float-left bp-add-one-row-num integerInit" data-v-min="1" data-v-max="1000" data-addrowtype="'+rowType+'" style="display: none">');
                }
                
                Core.initLongInput(addRowBtn.prev('input.bp-add-one-row-num').parent());
                
            } else if (addRowBtn.length && addRowNum.length) {
                if (criteria === 'hide') {
                    addRowNum.css({'display': 'none'});
                } else if (criteria === 'show') {
                    addRowNum.css({'display': ''});
                }
            }
            
        } else {
            if (criteria === 'hide') {
                mainSelector.find("[data-action-path='"+groupPath+"'][class*='"+actionClass+"']").css({display: 'none'});
            } else if (criteria === 'show') {
                mainSelector.find("[data-action-path='"+groupPath+"'][class*='"+actionClass+"']").css({display: ''});
            }
        }
    }
    return;
}
function bpDetailRemoveHide(elem, groupPath) {
    var $this = $(elem);
    if ($this.hasClass('bp-detail-row')) {
        $this.find('.bp-remove-row').css({display: 'none'});
    } else {
        $this.closest('.bp-detail-row').find('.bp-remove-row').css({display: 'none'});
    }
    return;
}
function bpDetailRemoveShow(elem) {
    var $this = $(elem);
    if ($this.hasClass('bp-detail-row')) {
        $this.find('.bp-remove-row').css({display: ''});
    } else {
        $this.closest('.bp-detail-row').find('.bp-remove-row').css({display: ''});
    }
    return;
}
function getLookupRowIndex(mainSelector, elem, lookupField, rowIndex) {
    var bpElem = getBpElement(mainSelector, elem, lookupField), dataRowData = {};

    if (typeof bpElem == 'undefined' || bpElem == false) {
        return;
    }
    
    var $parent = bpElem.closest(".input-group");
    var lookupCodeField = $parent.find("input[id*='_displayField']");
    var processId = lookupCodeField.attr("data-processid");
    var lookupId = lookupCodeField.attr("data-lookupid");
    var paramRealPath = bpElem.attr("data-path");
    var _metaDataCode = lookupCodeField.attr("data-field-name");
    var params = '';
    
    if (bpElem.prop("tagName") == 'SELECT') {
        dataRowData = bpElem.attr('data-row-data');
    }
    
    if (typeof bpElem.attr("data-criteria-param") !== 'undefined' && bpElem.attr("data-criteria-param") != '') {
        var paramsPathArr = bpElem.attr("data-criteria-param").split("|");
        for (var i = 0; i < paramsPathArr.length; i++) {
            var fieldPathArr = paramsPathArr[i].split("@");
            var fieldPath = fieldPathArr[0];
            var inputPath = fieldPathArr[1];
            var fieldValue = '';
            
            if ($("[data-path='"+fieldPath+"']", mainSelector).length > 0) {
                fieldValue = getBpRowParamNum(mainSelector, elem, fieldPath);
            } else {
                fieldValue = fieldPath;
            }
            
            params += inputPath + '=' + fieldValue + '&';
        }
        
    } else if (typeof bpElem.attr("data-in-param") !== 'undefined') {
        
        var _inputParam = bpElem.attr('data-in-param').split('|');
        var _lookupParam = bpElem.attr('data-in-lookup-param').split('|');
        
        for (var i = 0; i < _inputParam.length; i++) {
            
            if ($("[data-path='"+_inputParam[i]+"']", mainSelector).length > 0) {
                var paramVal = getBpRowParamNum(mainSelector, elem, _inputParam[i]);
            } else {
                var paramVal = _inputParam[i];
            }
            
            params += _lookupParam[i] + '=' + paramVal + '&';
        }
    }
    
    dataRowData = Object.keys(dataRowData).length ? JSON.parse(dataRowData) : '';
    var lookupUrl = 'mdwebservice/getLookupRowIndex';
    var isMvLookup = false;
    var controlClass = bpElem.attr('class');
    
    if (controlClass.indexOf('mv-popup-value') !== -1 || controlClass.indexOf('mv-ind-combo') !== -1) { 
        lookupUrl = 'mdform/getLookupRowIndex';
        isMvLookup = true;
    }
    
    $.ajax({
        type: 'post',
        url: lookupUrl,
        dataType: 'json',
        data : {
            processId : processId, 
            lookupId: lookupId, 
            paramRealPath: paramRealPath, 
            criteriaParams: encodeURIComponent(params), 
            rowIndex: rowIndex,
            dataRowData: dataRowData
        },
        async: false, 
        success: function (data) {
            var controlsData;
            var rowData;
            if (typeof (data.controlsData) !== 'undefined') {
                controlsData = data.controlsData;
            }
            if (typeof (data.rowData) !== 'undefined') {
                rowData = data.rowData;
            }

            if ($parent.closest("div.bp-param-cell").length > 0) {
                var parentCell = $parent.closest("div.bp-param-cell");
                var parentTable = $parent.closest("div.xs-form");
            } else if ($parent.closest("div.form-md-line-input").length > 0) {
                var parentCell = $parent.closest("div.form-md-line-input");
                var parentTable = $parent.closest("div.xs-form");
            } else {
                if ($parent.closest("div.meta-autocomplete-wrap").length > 0) {
                    var parentCell = $parent.closest("div.meta-autocomplete-wrap");
                } else {
                    var parentCell = $parent.closest("td");
                }

                if ($parent.closest(".bprocess-table-dtl").length > 0) {
                    var parentTable = $parent.closest(".bp-detail-row");
                } else {
                    var parentTable = $parent.closest("form");
                }
            }

            if (controlsData !== undefined) {
                $.each(controlsData, function (i, v) {
                    if (typeof rowData[v.FIELD_NAME] !== 'undefined' && _metaDataCode !== v.META_DATA_CODE) {
                        var getPathElement = parentTable.find("[data-field-name='" + v.META_DATA_CODE + "']");
                        if (getPathElement.length > 0) {
                            if (getPathElement.prop("tagName") == 'SELECT') {
                                if (getPathElement.hasClass('select2')) {
                                    getPathElement.trigger("select2-opening", 'notdisabled');
                                    getPathElement.select2('val', rowData[v.FIELD_NAME]);
                                } else {
                                    getPathElement.trigger("blur");
                                    getPathElement.val(rowData[v.FIELD_NAME]);
                                }
                            } else if (getPathElement.hasClass('dateInit')) {
                                getPathElement.datepicker('update', date('Y-m-d', strtotime(rowData[v.FIELD_NAME])));
                            } else if (getPathElement.hasClass('bigdecimalInit')) {
                                getPathElement.next("input[type=hidden]").val(setNumberToFixed(rowData[v.FIELD_NAME]));
                                getPathElement.val(rowData[v.FIELD_NAME]).trigger('change');
                            } else {
                                getPathElement.val(rowData[v.FIELD_NAME]).trigger('change');
                            }
                        }
                    }
                });
            }

            if (bpElem.prop("tagName") == 'SELECT') {
                if (data.META_VALUE_ID !== '') {
                    if (bpElem.hasClass('select2')) {
                        bpElem.select2('val', data.META_VALUE_ID);    
                    } else {
                        bpElem.val(data.META_VALUE_ID);
                    }
                } else {
                    bpElem.val('');
                }
                
                if (isMvLookup) {
                    bpSetMetaVerseFieldValue(mainSelector, elem, lookupField, data.META_VALUE_ID, bpElem);
                }
                
            } else {
                
                if (data.META_VALUE_ID !== '') {
                    if (isMvLookup) {
                        rowData = Object.fromEntries(
                            Object.entries(rowData).map(([key, val]) => [key.toLowerCase(), val])
                        );
                    }
                    var jsonStr = JSON.stringify(rowData).replace(/&quot;/g, '\\&quot;');
                    
                    $parent.find("input[id*='_valueField']").attr('data-row-data', jsonStr);
                    $parent.find("input[id*='_valueField']").val(data.META_VALUE_ID);
                    $parent.find("input[id*='_displayField']").val(data.META_VALUE_CODE).attr('title', data.META_VALUE_CODE);
                    $parent.find("input[id*='_nameField']").val(data.META_VALUE_NAME).attr('title', data.META_VALUE_NAME);
                } else {
                    $parent.find("input[id*='_valueField']").val('');
                    $parent.find("input[id*='_nameField']").val('').attr('title', '');
                }
            }

            /**
             * 
             * @description Sidebar үед ашиглаж байгаа
             * @author  Ulaankhuu Ts
             */
            var selectedTR = $('.bprocess-table-dtl > .tbody').find('.currentTarget');
            var fieldPath = $parent.attr('data-section-path');
            if (selectedTR.find("td:last-child").find("i.input_html").find("div[data-section-path='" + fieldPath + "']").length > 0) {
                $parent.find("input").removeClass("spinner2");
                selectedTR.find("td:last-child").find("i.input_html").find("div[data-section-path='" + fieldPath + "']").empty().append($parent.html());
            }
        },
        error: function () {
            alert("Error");
        }
    });
    return;
}
function showFiscalPeriodMessage(type, message) {

    var fiscalPeriodDropdown = '<li class="nav-item dropdown dropdown-language dropdown-dark fiscal-period-child-container">'+$(".system-header .fiscal-period-child-container").html()+'</li>';

    new PNotify({
        title: type,
        text: message+'<ul class="message-fiscal-period">'+fiscalPeriodDropdown+'</ul>',
        type: type,
        insert_brs: false, 
        addclass: pnotifyPosition,
        sticker: false
    });
    return;
}
function getConfigValue(key, criteria) {
    var key = key.toLowerCase();
    var postData = {key: key}, tmpCriteria = '';
    
    if (typeof criteria != 'undefined' && criteria) {
        postData.criteria = criteria;
        tmpCriteria = criteria.toLowerCase();
    }
    
    var tmpKey = key + '' + tmpCriteria;
    
    if (sysConfigTmpObj.hasOwnProperty(tmpKey)) {
        
        var result = sysConfigTmpObj[tmpKey];
        
    } else {
        var response = $.ajax({
            type: 'post',
            url: 'mdconfig/getConfigValue', 
            data: postData,
            dataType: 'json',
            async: false
        });
        var result = response.responseJSON;
        sysConfigTmpObj[tmpKey] = result;
    }

    return result;
}
function enableBpDetailFilter(mainSelector) {
    mainSelector.find('table.bprocess-table-dtl > thead > tr.bp-filter-row').each(function(){
        var $thisRow = $(this), $tbody = $thisRow.closest('table').find('tbody:eq(0)');
        if ($tbody.find('tr').length > 0) {
            $thisRow.find('input[type=text], select').removeAttr('disabled');
        } else {
            $thisRow.find('input[type=text], select').prop('disabled', true);
        }
    });
    return;
}
function enableBpDetailFilterByElement(element) {
    var $filterRow = element.find('tr.bp-filter-row'), $tbody = element.find('tbody:eq(0)');
    if ($tbody.find('tr:not(.removed-tr)').length) {
        $filterRow.find('input[type=text], select').removeAttr('disabled');
    } else {
        $filterRow.find('input[type=text], select').prop('disabled', true);
    }
    return;
}
function getTemplateValue(mainSelector) {
    return mainSelector.find("select.bp-template-id").val();
}
function setBpHdrParamNum(mainSelector, fieldPath, val) {  
    if (!pfFullExpSetFieldValue) {
        return;
    }
    
    var $getPathElement = mainSelector.find("input[data-path='" + fieldPath + "']");
    if ($getPathElement.length) {
        $getPathElement.next("input[type=hidden]").val(setNumberToFixed(val));
        $getPathElement.autoNumeric("set", val);   
    }
    return;
}
function setBpHdrParamInteger(mainSelector, fieldPath, val) {  
    if (!pfFullExpSetFieldValue) {
        return;
    }
    
    var $getPathElement = mainSelector.find("input[data-path='" + fieldPath + "']");
    if ($getPathElement.length) {
        $getPathElement.autoNumeric('set', val);   
    }
    return;
}
function setBpHdrParamString(mainSelector, fieldPath, val) {  
    if (!pfFullExpSetFieldValue) {
        return;
    }
    
    var $getPathElement = mainSelector.find("input[data-path='" + fieldPath + "']");
    if ($getPathElement.length) {
        $getPathElement.val(val);   
    }
    return;
}
function setBpHdrParamDate(mainSelector, fieldPath, val) {  
    if (!pfFullExpSetFieldValue) {
        return;
    }
    
    var $getPathElement = mainSelector.find("input[data-path='" + fieldPath + "']");
    
    if (typeof $getPathElement !== 'undefined') {
        if (val !== '' && val !== null) {
            $getPathElement.datepicker('update', date('Y-m-d', strtotime(val)));
        } else {
            $getPathElement.datepicker('update', null);
        }  
    }
    return;
}
function setBpHdrParamDateTime(mainSelector, fieldPath, val) {  
    if (!pfFullExpSetFieldValue) {
        return;
    }
    
    var $getPathElement = mainSelector.find("input[data-path='" + fieldPath + "']");
    
    if (typeof $getPathElement !== 'undefined') {
        if (val !== '') {
            //$getPathElement.datetimepicker('update', date('Y-m-d H:i:s', strtotime(val)));
            $getPathElement.val(date('Y-m-d H:i:s', strtotime(val)));
        } else {
            //$getPathElement.datetimepicker('update', null);
            $getPathElement.val('');
        }  
    }
    return;
}
function setBpHdrRadio(mainSelector, fieldPath, val) {
    var $getPathElement = mainSelector.find("div[data-section-path='" + fieldPath + "']");
    
    if (typeof $getPathElement !== 'undefined' && val !== '' && val !== null) {
        $getPathElement.find("input[type='radio'][value='"+val+"']").prop('checked', true);
        $.uniform.update($getPathElement.find("input[type='radio']"));
    }
    return;
}
function setBpHdrParamLabel(mainSelector, fieldPath, val) {  
    var $getPathElement = mainSelector.find("[data-path='" + fieldPath + "']");
    
    if (typeof $getPathElement !== 'undefined') {
        $getPathElement.html(val); 
    }
    return;
}
function setBpHdrParamTextEditor(mainSelector, fieldPath, content) {  
    
    if (!pfFullExpSetFieldValue) {
        return;
    }
    
    setTimeout(function() {
        var $getPathElement = mainSelector.find("[data-path='" + fieldPath + "']");
    
        if ($getPathElement.length) {
            if (typeof tinymce != 'undefined') {
                if (fieldPath.indexOf('.') !== -1) {
                    var editorPath = 'param[' + fieldPath + '][0][]';
                } else {
                    var editorPath = 'param[' + fieldPath + ']';
                }
                var tinymceEditor = tinymce.get(editorPath);
                if (tinymceEditor === null) {
                    $getPathElement.val(content);
                } else {
                    tinymceEditor.setContent(html_entity_decode(content, "ENT_QUOTES"));
                }
            } else {
                $getPathElement.val(content);
            }
        }
    }, 1800);
    
    return;
}
function setBpHdrParamRuleExpression(mainSelector, fieldPath, expression) {  
    
    if (!pfFullExpSetFieldValue) {
        return;
    }
    
    var $getPathElement = mainSelector.find("textarea[data-path='" + fieldPath + "']");
    
    if (typeof $getPathElement !== 'undefined' && $getPathElement.length == 1) {
        $getPathElement.val(expression);
        var $parent = $getPathElement.closest('.input-group');
        if (expression != '') {
            $.ajax({
                type: 'post',
                url: 'mdform/ruleExpressionForm', 
                data: {isFromExpression: 1, expression: expression}, 
                dataType: 'json',
                async: false,
                beforeSend: function(){
                    if (!$("link[href='middleware/assets/css/salary/expression.css?v=12']").length){
                        $("head").append('<link rel="stylesheet" type="text/css" href="middleware/assets/css/salary/expression.css?v=12"/>');
                    }
                },
                success: function (data) {
                    if (data.status == 'success') {
                        $parent.find('.p-exp-area').html(data.expression);
                    }
                }
            });
        } else {
            $parent.find('.p-exp-area').empty();
        }
    }
    return;
}
function setBpHdrRangeSlider(mainSelector, fieldPath, val) {  
    if (!pfFullExpSetFieldValue) {
        return;
    }
    
    var $getPathElement = mainSelector.find("input[data-path='" + fieldPath + "']");
    
    if (typeof $getPathElement !== 'undefined') {
        var rangeSlider = $getPathElement.data('ionRangeSlider');
        val = val.toString();
        if (val !== '') {
            var rsIds = $getPathElement.attr('data-rs-ids');
            var values = rsIds.split('|$|');
            var default_from = values.indexOf(val);
            rangeSlider.update({from: default_from});
        } else {
            rangeSlider.update({from: 0});
        }  
    }
    
    return;
}
function setBpRowParamLabel(mainSelector, elem, fieldPath, val) {  
    mainSelector.find('span[data-path="'+ fieldPath +'"]').empty().append(val);
    return;
}
function getBpHdrParamNum(mainSelector, fieldPath) {  
    var $getPathElement = mainSelector.find("input[data-path='" + fieldPath + "']");
    
    if (typeof $getPathElement !== 'undefined') { 
        return Number($getPathElement.next("input[type=hidden]").val());
    }
    return '';
}
function getBpDtlRadioParam(mainSelector, elem, fieldPath) { 
    
    var selectedVal = '';
    
    if (elem === 'open') {
        var $getPathElement = mainSelector.find("[data-path='" + fieldPath + "']:checked");
        if ($getPathElement.length) {
            selectedVal = $getPathElement.val();
        }
    } else {
        elem = $(elem);    
        
        var $oneLevelRow = elem.closest('.bp-detail-row');   
        if ($oneLevelRow.find("[data-path='" + fieldPath + "']").length == 0) {
            $oneLevelRow = $oneLevelRow.parents('.bp-detail-row');
        }
        
        var $getPathElement = $oneLevelRow.find("[data-path='" + fieldPath + "']");
        
        if ($getPathElement.length) {
            var $getPathElementChecked = $oneLevelRow.find("[data-path='" + fieldPath + "']:checked");
            if ($getPathElementChecked.length) {
                selectedVal = $getPathElementChecked.val();
            }
        } else {
            var $getPathMainElement = mainSelector.find("[data-path='" + fieldPath + "']:checked");
            if ($getPathMainElement.length) {
                selectedVal = $getPathMainElement.val();
            }
        }
    }

    return selectedVal;
}
function setBpHdrMultipleCombo(mainSelector, elem, fieldPath, val) {
    if (!pfFullExpSetFieldValue) {
        return;
    }
    
    var $path = mainSelector.find("select[data-path='" + fieldPath + "']");
    if ($path.length) {
        if (val) {
            $path.trigger('select2-opening', [true]);
            if (isObject(val)) {
                $path.select2('val', val);
            } else {
                val = val + '';
                $path.select2('val', val.split(','));
            }
        } else {
            $path.select2('val', '');
        }
    }
    return;
}
function setBpRowParamNum(mainSelector, elem, fieldPath, val) {    
    
    if (!pfFullExpSetFieldValue) {
        return;
    }
    
    if (elem !== 'open') {
        elem = $(elem);
        var $this = $(elem, mainSelector);
        var $oneLevelRow = $this.closest('.bp-detail-row');
        var $pathElement = $oneLevelRow.find("[data-path='" + fieldPath + "']");
        
        if ($pathElement.length == 0) {

            if (mainSelector.find('.bprocess-table-dtl > .tbody > .currentTarget').find("[data-path='" + fieldPath + "']").length) {     
                var $getPathElement = mainSelector.find('.bprocess-table-dtl > .tbody > .currentTarget').find("[data-path='" + fieldPath + "']");
            } else {
                var $getPathElement = mainSelector.find("[data-path='" + fieldPath + "']");
            }
            
            if ($getPathElement.length) {
                if ($getPathElement.prop("tagName") == 'SELECT') {

                    if ($getPathElement.hasClass('select2')) {
                        $getPathElement.trigger("select2-opening", [true]);
                        $getPathElement.select2('val', val);
                    } else {
                        $getPathElement.trigger("blur");
                        $getPathElement.val(val);
                    }
                } else {
                    if ($getPathElement.hasClass('longInit') 
                        || $getPathElement.hasClass('numberInit') 
                        || $getPathElement.hasClass('decimalInit') 
                        || $getPathElement.hasClass('integerInit')) {
                        
                        if (val == null) {
                            $getPathElement.autoNumeric('set', '');
                        } else {
                            $getPathElement.autoNumeric('set', val);
                        }

                    } else if ($getPathElement.hasClass('bigdecimalInit')) {
                        
                        $getPathElement.next("input[type=hidden]").val(setNumberToFixed(val));
                        $getPathElement.autoNumeric("set", val);                        

                    } else if ($getPathElement.hasClass('dateInit')) {
                        if (val !== '' && val !== null) {
                            $getPathElement.datepicker('update', date('Y-m-d', strtotime(val)));
                        } else {
                            $getPathElement.datepicker('update', null);
                        }
                    } else if ($getPathElement.hasClass('datetimeInit')) {
                        if (val !== '' && val !== null) {
                            $getPathElement.val(date('Y-m-d H:i:s', strtotime(val)));
                        } else {
                            $getPathElement.val('');
                        }
                    } else if ($getPathElement.hasClass('popupInit')) {   
                        setLookupPopupValue($getPathElement, val);
                    } else if ($getPathElement.hasClass('booleanInit')) {   
                        checkboxCheckerUpdate($getPathElement, val);
                    } else if ($getPathElement.hasClass('radioInit')) {   
                        radioButtonCheckerUpdate($getPathElement, val);
                    } else {
                        $getPathElement.val(val);                        
                    }
                }
            }
            return;
            
        } else if ($oneLevelRow.children().last().find("[data-path='" + fieldPath + "']").length == 0) {          
            var $getPathElement = $pathElement;
            
            if ($getPathElement.length) {
            
                if ($getPathElement.prop("tagName") == 'SELECT') {
                    if ($getPathElement.hasClass('select2')) {
                        $getPathElement.trigger('select2-opening', [true]);
                        $getPathElement.select2('val', val);
                    } else {
                        $getPathElement.trigger('blur');
                        $getPathElement.val(val);
                    }
                } else {
                    if ($getPathElement.hasClass('longInit') 
                        || $getPathElement.hasClass('numberInit') 
                        || $getPathElement.hasClass('decimalInit') 
                        || $getPathElement.hasClass('integerInit')) {
                        
                        if (val == null) {
                            $getPathElement.autoNumeric('set', '');
                        } else {
                            $getPathElement.autoNumeric('set', val);
                        }                       

                    } else if ($getPathElement.hasClass('bigdecimalInit')) {
                        $getPathElement.next("input[type=hidden]").val(setNumberToFixed(val));
                        $getPathElement.autoNumeric('set', val);   
                    } else if ($getPathElement.hasClass('dateInit')) {
                        if (val !== '' && val !== null) {
                            $getPathElement.datepicker('update', date('Y-m-d', strtotime(val)));
                        } else {
                            $getPathElement.datepicker('update', null);
                        }
                    } else if ($getPathElement.hasClass('datetimeInit')) {
                        if (val !== '' && val !== null) {
                            $getPathElement.val(date('Y-m-d H:i:s', strtotime(val)));
                        } else {
                            $getPathElement.val('');
                        }
                    } else if ($getPathElement.hasClass('popupInit')) {   
                        setLookupPopupValue($getPathElement, val);
                    } else if ($getPathElement.hasClass('booleanInit')) {   
                        checkboxCheckerUpdate($getPathElement, val);
                    } else if ($getPathElement.hasClass('radioInit')) {
                        radioButtonCheckerUpdate($getPathElement, val);
                    } else {                                               
                        $getPathElement.val(val);                        
                    }
                }
            }
            return;
            
        } else {

            var $getPathElement = $pathElement;
            if ($getPathElement.length) {                     
                if ($getPathElement.prop('tagName') == 'SELECT') {
                    if ($getPathElement.hasClass('mv-ind-combo')) {
                        $getPathElement.select2('val', val).trigger('mvChange');
                    } else if ($getPathElement.hasClass('select2')) {
                        $getPathElement.trigger('select2-opening', [true]);
                        $getPathElement.select2('val', val);
                    } else {
                        $getPathElement.trigger('blur');
                        $getPathElement.val(val);
                    }
                } else {
                    if ($getPathElement.hasClass('longInit') 
                        || $getPathElement.hasClass('numberInit') 
                        || $getPathElement.hasClass('decimalInit') 
                        || $getPathElement.hasClass('integerInit')) {
                        
                        if (val == null) {
                            $getPathElement.autoNumeric('set', '');
                        } else {
                            $getPathElement.autoNumeric('set', val);
                        }  

                    } else if ($getPathElement.hasClass('bigdecimalInit')) {   
                        $getPathElement.next("input[type=hidden]").val(setNumberToFixed(val));
                        $getPathElement.autoNumeric("set", val);       

                    } else if ($getPathElement.hasClass('dateInit')) {
                        if (val !== '' && val !== null) {
                            $getPathElement.datepicker('update', date('Y-m-d', strtotime(val)));
                        } else {
                            $getPathElement.datepicker('update', null);
                        }
                    } else if ($getPathElement.hasClass('datetimeInit')) {
                        if (val !== '' && val !== null) {
                            $getPathElement.val(date('Y-m-d H:i:s', strtotime(val)));
                        } else {
                            $getPathElement.val('');
                        }
                    } else if ($getPathElement.hasClass('popupInit')) {   
                        setLookupPopupValue($getPathElement, val);
                    } else if ($getPathElement.hasClass('booleanInit')) {   
                        checkboxCheckerUpdate($getPathElement, val);
                    } else if ($getPathElement.hasClass('radioInit')) {   
                        radioButtonCheckerUpdate($getPathElement, val);
                    } else {                       
                        $getPathElement.val(val);
                    }
                }
            }
            $oneLevelRow.find("td:last-child").find("[data-path='" + fieldPath + "']").val(val);  
            
            return;
        }    
    } else {        
        var $getPathElement = mainSelector.find("[data-path='" + fieldPath + "']");
        if ($getPathElement.length) {
            if ($getPathElement.prop("tagName") == 'SELECT') {
                if ($getPathElement.hasClass('select2')) {
                    if (val == null || val == '') {
                        $getPathElement.select2('val', '');
                    } 
                    if ($getPathElement.find("option").length > 2) {
                        $getPathElement.select2('val', val);
                    } else if ($getPathElement.attr("data-row-data") !== "undefined") {
                        comboSingleDataSet($getPathElement, val);
                    }
                } else {
                    $getPathElement.trigger("blur");
                    $getPathElement.find("option").filter('[value="' + val + '"]').attr("selected", "selected");
                }
            } else {
                if ($getPathElement.hasClass('longInit') 
                    || $getPathElement.hasClass('numberInit') 
                    || $getPathElement.hasClass('decimalInit') 
                    || $getPathElement.hasClass('integerInit')) {
                    
                    if (val == null) {
                        $getPathElement.autoNumeric('set', '');
                    } else {
                        $getPathElement.autoNumeric('set', val);
                    } 
                        
                } else if ($getPathElement.hasClass('bigdecimalInit')) {               
                    $getPathElement.next("input[type=hidden]").val(setNumberToFixed(val));
                    $getPathElement.autoNumeric("set", val);      
                } else if ($getPathElement.hasClass('dateInit')) {
                    if (val !== '' && val !== null) {
                        $getPathElement.datepicker('update', date('Y-m-d', strtotime(val)));
                    } else {
                        $getPathElement.datepicker('update', null);
                    }
                } else if ($getPathElement.hasClass('datetimeInit')) {
                    if (val !== '' && val !== null) {
                        $getPathElement.val(date('Y-m-d H:i:s', strtotime(val)));
                    } else {
                        $getPathElement.val('');
                    }
                } else if ($getPathElement.hasClass('popupInit')) {   
                    setLookupPopupValue($getPathElement, val);
                } else if ($getPathElement.hasClass('combogridInit')) {   
                    setLookupComboGridValue($getPathElement, val);
                } else if ($getPathElement.hasClass('booleanInit')) {   
                    checkboxCheckerUpdate($getPathElement, val);
                } else if ($getPathElement.hasClass('radioInit')) {   
                    radioButtonCheckerUpdate($getPathElement, val);
                } else if ($getPathElement.hasClass('iconInit')) {   
                    setIconInput($getPathElement, val);
                } else {                                            
                    $getPathElement.val(val);                        
                }
            }
            return;
            
        } else {
            var $getPathElement = mainSelector.find("[data-view-path='" + fieldPath + "']");
            if ($getPathElement.length) {
                if ($getPathElement.hasClass('dropdownInput')) {
                    comboSingleDataSetView($getPathElement, val);
                } else if ($getPathElement.hasClass('popupInput')) {
                    setLookupPopupValueView($getPathElement, val);
                } else
                    $getPathElement.text(val);
            }
            return;
        }
    }
    return;
}
function setBpRowParamView(mainSelector, elem, fieldPath, val) {    
    
    if (elem !== 'open') {
        elem = $(elem);
        var $this = $(elem, mainSelector);
        var $oneLevelRow = $this.closest('tr');        
    
        if ($oneLevelRow.find("[data-view-path='" + fieldPath + "']").length == 0) {

            if (mainSelector.find('.bprocess-table-dtl > .tbody > .currentTarget').find("[data-view-path='" + fieldPath + "']").length) {     
                var $getPathElement = mainSelector.find('.bprocess-table-dtl > .tbody > .currentTarget').find("[data-view-path='" + fieldPath + "']");
            } else {
                var $getPathElement = mainSelector.find("[data-view-path='" + fieldPath + "']");
            }
            
            if ($getPathElement.length) {
                $getPathElement.text(val);
            }
            return;
            
        } else if ($oneLevelRow.find("td:last-child").find("[data-view-path='" + fieldPath + "']").length == 0) {          
            var $getPathElement = $oneLevelRow.find("[data-view-path='" + fieldPath + "']");
            
            if ($getPathElement.length) {
                $getPathElement.text(val);
            }
            return;
            
        } else {

            var $getPathElement = $oneLevelRow.find("[data-view-path='" + fieldPath + "']");
            
            if ($getPathElement.length) {                     
                $getPathElement.text(val);
            }
            $oneLevelRow.find("[data-cell-path]:last-child").find("[data-view-path='" + fieldPath + "']").text(val);  
            
            return;
        }    
    } else {       
        
        var $getPathElement = mainSelector.find("[data-view-path='" + fieldPath + "']");
        
        if ($getPathElement.length) {
            if ($getPathElement.hasClass('dropdownInput')) {
                comboSingleDataSetView($getPathElement, val);
            } else if ($getPathElement.hasClass('popupInput')) {
                setLookupPopupValueView($getPathElement, val);
            } else {
                $getPathElement.text(val);
            }
        }
        return;
    }
    return;
}
/*-----------------------*/
function setBpRowParamBigdecimal(mainSelector, elem, fieldPath, val) {     
    
    if (!pfFullExpSetFieldValue) {
        return;
    }
    
    if (elem !== 'open') {
    
        var $this = $(elem);
        var $oneLevelRow = $this.closest('.bp-detail-row');        

        if ($oneLevelRow.find("[data-view-path='" + fieldPath + "']").length) {
            $oneLevelRow.find("[data-view-path='" + fieldPath + "']").text(val);
        } else if ($oneLevelRow.find("[data-path='" + fieldPath + "']").length === 0) {
        
            if (mainSelector.find('.bprocess-table-dtl > .tbody > .currentTarget').find("[data-path='" + fieldPath + "']").length) {     
                var $getPathElement = mainSelector.find('.bprocess-table-dtl > .tbody > .currentTarget').find("[data-path='" + fieldPath + "']");
            } else {
                var $getPathElement = mainSelector.find("[data-path='" + fieldPath + "']");
            }

            if ($getPathElement.length) {
                if (val != '' && val != null) { 
                    $getPathElement.next('input[type=hidden]').val(setNumberToFixed(val));
                    $getPathElement.autoNumeric('set', val);  
                } else if (val == 0 || val == '0') {
                    $getPathElement.next('input[type=hidden]').val('0');
                    $getPathElement.autoNumeric('set', 0);
                } else {
                    $getPathElement.next('input[type=hidden]').val('');
                    $getPathElement.autoNumeric('set', '');  
                }
            }
            return;
            
        } else {
            var $getPathElement = $oneLevelRow.find("[data-path='" + fieldPath + "']");
            
            if ($getPathElement.length) {                     
                
                if (val != '' && val != null) { 
                    $getPathElement.next('input[type=hidden]').val(setNumberToFixed(val));
                    $getPathElement.autoNumeric('set', val);
                } else if (val == 0 || val == '0') {
                    $getPathElement.next('input[type=hidden]').val('0');
                    $getPathElement.autoNumeric('set', 0);
                } else {
                    $getPathElement.next('input[type=hidden]').val('');
                    $getPathElement.autoNumeric('set', '');  
                }
            }
            return;
        }   
        
    } else {        
                
        var $getPathElement = mainSelector.find("[data-path='" + fieldPath + "']");
        
        if ($getPathElement.length) {
            if (val != '' && val != null) { 
                $getPathElement.next('input[type=hidden]').val(setNumberToFixed(val));
                $getPathElement.autoNumeric('set', val);
            } else if (val == 0 || val == '0') {
                $getPathElement.next('input[type=hidden]').val('0');
                $getPathElement.autoNumeric('set', 0);
            } else {
                $getPathElement.next('input[type=hidden]').val('');
                $getPathElement.autoNumeric('set', '');  
            }
            return;
        } 
    }
    return;
}
function bpSetBigDecimalNull(mainSelector, elem, fieldPath, val) {     
    if (elem !== 'open') {
        var $this = $(elem);
        var $oneLevelRow = $this.closest('tr');        

        if ($oneLevelRow.find("[data-path='" + fieldPath + "']").length === 0) {

            if (mainSelector.find('.bprocess-table-dtl > .tbody > .currentTarget').find("[data-path='" + fieldPath + "']").length) {     
                var $getPathElement = mainSelector.find('.bprocess-table-dtl > .tbody > .currentTarget').find("[data-path='" + fieldPath + "']");
            } else {
                var $getPathElement = mainSelector.find("[data-path='" + fieldPath + "']");
            }

            if ($getPathElement.length) {
                if (val !== '') {
                    $getPathElement.next('input[type=hidden]').val(setNumberToFixed(val));
                } else {
                    $getPathElement.next('input[type=hidden]').val('');
                }
                $getPathElement.autoNumeric('set', val);  
            }
            return;
            
        } else {
            var $getPathElement = $oneLevelRow.find("[data-path='" + fieldPath + "']");
            if ($getPathElement.length) {        
                if (val !== '') {
                    $getPathElement.next('input[type=hidden]').val(setNumberToFixed(val));
                } else {
                    $getPathElement.next('input[type=hidden]').val('');
                }
                $getPathElement.autoNumeric('set', val);
            }
            return;
        }   
        
    } else {        
        var $getPathElement = mainSelector.find("[data-path='" + fieldPath + "']");
        
        if ($getPathElement.length) {
            if (val !== '') {
                $getPathElement.next('input[type=hidden]').val(setNumberToFixed(val));
            } else {
                $getPathElement.next('input[type=hidden]').val('');
            }
            $getPathElement.autoNumeric('set', val);
            return;
        } 
    }
    return;
}
function setLookupPopupValue(element, valId) {
    
    if (!pfFullExpSetFieldValue) {
        return;
    }
    
    var $this = element;
    var $parentForm = $this.closest('form');
    var $parent = $this.parent();
    var $codeField = $parent.find("input[id*='_displayField']");
    var _processId = $codeField.attr("data-processid");
    var _lookupId = $codeField.attr("data-lookupid");
    var _metaDataCode = $codeField.attr("data-field-name");
    var _paramRealPath = $this.attr("data-path");
    var params = '';
    
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
            var $parentCell = $parent.closest("[data-cell-path]");
        }

        if ($parent.closest(".bprocess-table-dtl").length > 0) {
            var $parentTable = $parent.closest(".bp-detail-row");
        } else {
            var $parentTable = $parentForm;
        }
    }
    
    if (valId == null || valId == '') {
        
        $parent.find("input[id*='_valueField']:not(:eq(0))").remove();
        $parent.find("input[id*='_valueField']").val('');
        $parent.find("input[id*='_nameField']").val('').attr('title', '');
        $parent.find("input[id*='_displayField']").val('').attr('title', '');
        
    } else {
    
        if (typeof $this.attr('data-in-param') !== 'undefined' && $this.attr('data-in-param') !== '') {
            
            var $inputParam = $this.attr('data-in-param').split('|'), 
                $inputLookupParam = $this.attr('data-in-lookup-param').split('|'), 
                $fieldValue = '';
            
            for (var i = 0; i < $inputParam.length; i++) {
                
                var $bpElem = getBpElement($parentForm, $this, $inputParam[i]);

                if ($bpElem) {
                    $fieldValue = getBpRowParamNum($parentForm, $this, $inputParam[i]);
                    params += $inputLookupParam[i] + '=' + $fieldValue + '&';
                }
            }
        }
        
        if (typeof $this.attr("data-criteria-param") !== 'undefined' && $this.attr("data-criteria-param") != '') {
            var paramsPathArr = $this.attr("data-criteria-param").split('|');
            for (var i = 0; i < paramsPathArr.length; i++) {
                var fieldPathArr = paramsPathArr[i].split('@');
                var fieldPath = fieldPathArr[0];
                var inputPath = fieldPathArr[1];
                var fieldValue = '', isCriteria = false;

                if ($parentForm.find("[data-path='"+fieldPath+"']").length) {
                    fieldValue = getBpRowParamNum($parentForm, $this, fieldPath);
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
        
        var valIdStr = valId.toString();

        if (valIdStr.indexOf(',') !== -1) {
            setLookupPopupValueMulti($parent, $codeField, _processId, _lookupId, _paramRealPath, valId);
            return;
        }
        
        if ((valId == '' || valId == null) && (params == '' || params == null)) {
            $parent.find("input[id*='_valueField']:not(:eq(0))").remove();
            $parent.find("input[id*='_valueField']").val('');
            $parent.find("input[id*='_nameField']").val('').attr('title', '');
            $parent.find("input[id*='_displayField']").val('').attr('title', '');
            return;
        }
        
        var lookupUrl = 'mdobject/autoCompleteById';
        var $valueField = $parent.find("input[id*='_valueField']");
        var isMvLookup = false;

        if ($valueField.attr('class').indexOf('mv-popup-value') !== -1) { 
            lookupUrl = 'mdform/autoCompleteById';
            isMvLookup = true;
        }

        $.ajax({
            type: 'post',
            url: lookupUrl,
            data: {
                processMetaDataId: _processId,
                lookupId: _lookupId,
                paramRealPath: _paramRealPath,
                code: valId,
                isName: 'idselect', 
                params: encodeURIComponent(params)
            },
            dataType: 'json',
            async: false,
            beforeSend: function () {
                $codeField.addClass('spinner2');
            },
            success: function (data) {
                var controlsData;
                var rowData;

                if (typeof (data.controlsData) !== 'undefined') {
                    controlsData = data.controlsData;
                }
                if (typeof (data.rowData) !== 'undefined') {
                    rowData = data.rowData;
                }

                if (controlsData !== undefined) {
                    $.each(controlsData, function (i, v) {
                        if (typeof rowData[v.FIELD_NAME] !== 'undefined' && _metaDataCode !== v.META_DATA_CODE) {
                            var getPathElement = $parentTable.find("[data-field-name='" + v.META_DATA_CODE + "']");
                            if (getPathElement.length > 0) {
                                if (getPathElement.prop("tagName") == 'SELECT') {
                                    if (getPathElement.hasClass('select2')) {
                                        getPathElement.trigger("select2-opening", 'notdisabled');
                                        getPathElement.select2('val', rowData[v.FIELD_NAME]);
                                    } else {
                                        getPathElement.trigger("blur");
                                        getPathElement.val(rowData[v.FIELD_NAME]);
                                    }
                                } else if (getPathElement.hasClass('dateInit')) {
                                    if (rowData[v.FIELD_NAME] !== '' && rowData[v.FIELD_NAME] !== null) {
                                        getPathElement.datepicker('update', date('Y-m-d', strtotime(rowData[v.FIELD_NAME])));
                                    } else {
                                        getPathElement.datepicker('update', null);
                                    }
                                } else if (getPathElement.hasClass('bigdecimalInit')) {
                                    getPathElement.next("input[type=hidden]").val(setNumberToFixed(rowData[v.FIELD_NAME]));
                                    getPathElement.val(rowData[v.FIELD_NAME]).trigger('change');
                                } else {
                                    getPathElement.val(rowData[v.FIELD_NAME]).trigger('change');
                                }
                            }
                        }
                    });
                }

                if (data.META_VALUE_ID !== '') {
                    if (isMvLookup) {
                        rowData = Object.fromEntries(
                            Object.entries(rowData).map(([key, val]) => [key.toLowerCase(), val])
                        );
                    }
                    var jsonStr = JSON.stringify(rowData).replace(/&quot;/g, '\\&quot;');
                    $valueField.val(data.META_VALUE_ID).attr('data-row-data', jsonStr);                    
                    if ($this.hasClass('combogridInit')) { 
                        $parent.find("input[id*='_displayField']").val(data.META_VALUE_NAME).attr('title', data.META_VALUE_NAME);
                    } else {
                        $parent.find("input[id*='_displayField']").val(data.META_VALUE_CODE).attr('title', data.META_VALUE_CODE);
                        $parent.find("input[id*='_nameField']").val(data.META_VALUE_NAME).attr('title', data.META_VALUE_NAME);
                    }
                } else {
                    $parent.find("input[id*='_valueField']:not(:eq(0))").remove();
                    $valueField.val('');
                    $parent.find("input[id*='_nameField']").val('').attr('title', '');
                    $parent.find("input[id*='_displayField']").val('').attr('title', '');
                }

                /**
                 * 
                 * @description Sidebar үед ашиглаж байгаа
                 * @author  Ulaankhuu Ts
                 */
                var $selectedTR = $('.bprocess-table-dtl > .tbody > .currentTarget');
                var $fieldPath = $parent.attr('data-section-path');
                if ($selectedTR.find("td:last-child").find("i.input_html").find("div[data-section-path='" + $fieldPath + "']").length > 0) {
                    $parent.find("input").removeClass("spinner2");
                    $selectedTR.find("td:last-child").find("i.input_html").find("div[data-section-path='" + $fieldPath + "']").empty().append($parent.html());
                }
            },
            error: function () {
                alert("Error");
            }
        }).done(function(){
            $codeField.removeClass('spinner2');
        });
    }
    
    return;
}
function setLookupComboGridValue(element, valId) {
    
    if (!pfFullExpSetFieldValue) {
        return;
    }
    
    var $this = element;
    var $parentForm = $this.closest('form');
    var $parent = $this.parent();
    var $codeField = $parent.find("input[id*='_displayField']");
    var _processId = $codeField.attr("data-processid");
    var _lookupId = $codeField.attr("data-lookupid");
    var _metaDataCode = $codeField.attr("data-field-name");
    var _paramRealPath = $this.attr("data-path");
    var params = '';
    
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
            var $parentCell = $parent.closest("[data-cell-path]");
        }

        if ($parent.closest(".bprocess-table-dtl").length > 0) {
            var $parentTable = $parent.closest(".bp-detail-row");
        } else {
            var $parentTable = $parentForm;
        }
    }
    
    if (valId == null || valId == '') {
        
        $parent.find("input[id*='_valueField']:not(:eq(0))").remove();
        $parent.find("input[id*='_valueField']").val('');
        //$parent.find("input[id*='_nameField']").val('').attr('title', '');
        $parent.find("input[id*='_displayField']").val('').attr('title', '');
        
    } else {
    
        if (typeof $this.attr('data-in-param') !== 'undefined' && $this.attr('data-in-param') !== '') {
            
            var $inputParam = $this.attr('data-in-param').split('|'), 
                $inputLookupParam = $this.attr('data-in-lookup-param').split('|'), 
                $fieldValue = '';
            
            for (var i = 0; i < $inputParam.length; i++) {
                
                var $bpElem = getBpElement($parentForm, $this, $inputParam[i]);

                if ($bpElem) {
                    $fieldValue = getBpRowParamNum($parentForm, $this, $inputParam[i]);
                    params += $inputLookupParam[i] + '=' + $fieldValue + '&';
                }
            }
        }
        
        if (typeof $this.attr("data-criteria-param") !== 'undefined' && $this.attr("data-criteria-param") != '') {
            var paramsPathArr = $this.attr("data-criteria-param").split('|');
            for (var i = 0; i < paramsPathArr.length; i++) {
                var fieldPathArr = paramsPathArr[i].split('@');
                var fieldPath = fieldPathArr[0];
                var inputPath = fieldPathArr[1];
                var fieldValue = '', isCriteria = false;

                if ($parentForm.find("[data-path='"+fieldPath+"']").length) {
                    fieldValue = getBpRowParamNum($parentForm, $this, fieldPath);
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
        
        var valIdStr = valId.toString();

        if (valIdStr.indexOf(',') !== -1) {
            setLookupPopupValueMulti($parent, $codeField, _processId, _lookupId, _paramRealPath, valId);
            return;
        }
        
        if ((valId == '' || valId == null) && (params == '' || params == null)) {
            $parent.find("input[id*='_valueField']:not(:eq(0))").remove();
            $parent.find("input[id*='_valueField']").val('');
            //$parent.find("input[id*='_nameField']").val('').attr('title', '');
            $parent.find("input[id*='_displayField']").val('').attr('title', '');
            return;
        }

        $.ajax({
            type: 'post',
            url: 'mdobject/autoCompleteById',
            data: {
                processMetaDataId: _processId,
                lookupId: _lookupId,
                paramRealPath: _paramRealPath,
                code: valId,
                isName: 'idselect', 
                params: encodeURIComponent(params)
            },
            dataType: 'json',
            async: false,
            beforeSend: function () {
                $codeField.addClass('spinner2');
            },
            success: function (data) {
                var controlsData;
                var rowData;

                if (typeof (data.controlsData) !== 'undefined') {
                    controlsData = data.controlsData;
                }
                if (typeof (data.rowData) !== 'undefined') {
                    rowData = data.rowData;
                }

                if (controlsData !== undefined) {
                    $.each(controlsData, function (i, v) {
                        if (typeof rowData[v.FIELD_NAME] !== 'undefined' && _metaDataCode !== v.META_DATA_CODE) {
                            var getPathElement = $parentTable.find("[data-field-name='" + v.META_DATA_CODE + "']");
                            if (getPathElement.length > 0) {
                                if (getPathElement.prop("tagName") == 'SELECT') {
                                    if (getPathElement.hasClass('select2')) {
                                        getPathElement.trigger("select2-opening", 'notdisabled');
                                        getPathElement.select2('val', rowData[v.FIELD_NAME]);
                                    } else {
                                        getPathElement.trigger("blur");
                                        getPathElement.val(rowData[v.FIELD_NAME]);
                                    }
                                } else if (getPathElement.hasClass('dateInit')) {
                                    if (rowData[v.FIELD_NAME] !== '' && rowData[v.FIELD_NAME] !== null) {
                                        getPathElement.datepicker('update', date('Y-m-d', strtotime(rowData[v.FIELD_NAME])));
                                    } else {
                                        getPathElement.datepicker('update', null);
                                    }
                                } else if (getPathElement.hasClass('bigdecimalInit')) {
                                    getPathElement.next("input[type=hidden]").val(setNumberToFixed(rowData[v.FIELD_NAME]));
                                    getPathElement.val(rowData[v.FIELD_NAME]).trigger('change');
                                } else {
                                    getPathElement.val(rowData[v.FIELD_NAME]).trigger('change');
                                }
                            }
                        }
                    });
                }

                if (data.META_VALUE_ID !== '') {
                    var jsonStr = JSON.stringify(rowData).replace(/&quot;/g, '\\&quot;');
                    $parent.find("input[id*='_valueField']").val(data.META_VALUE_ID).attr('data-row-data', jsonStr);
                    $parent.find("input[id*='_displayField']").val(data.META_VALUE_NAME).attr('title', data.META_VALUE_NAME);
                    $parent.find("input[id*='_nameField']").val(data.META_VALUE_NAME).attr('title', data.META_VALUE_NAME);
                } else {
                    $parent.find("input[id*='_valueField']:not(:eq(0))").remove();
                    $parent.find("input[id*='_valueField']").val('');
                    $parent.find("input[id*='_nameField']").val('').attr('title', '');
                    $parent.find("input[id*='_displayField']").val('').attr('title', '');
                }

                /**
                 * 
                 * @description Sidebar үед ашиглаж байгаа
                 * @author  Ulaankhuu Ts
                 */
                var $selectedTR = $('.bprocess-table-dtl > .tbody > .currentTarget');
                var $fieldPath = $parent.attr('data-section-path');
                if ($selectedTR.find("td:last-child").find("i.input_html").find("div[data-section-path='" + $fieldPath + "']").length > 0) {
                    $parent.find("input").removeClass("spinner2");
                    $selectedTR.find("td:last-child").find("i.input_html").find("div[data-section-path='" + $fieldPath + "']").empty().append($parent.html());
                }
            },
            error: function () {
                alert("Error");
            }
        }).done(function(){
            $codeField.removeClass('spinner2');
        });
    }
    
    return;
}
function setLookupPopupValueMulti($parent, $codeField, processId, lookupId, paramRealPath, valId) {
    $.ajax({
        type: 'post',
        url: 'mdobject/autoCompleteByIdMulti',
        data: {
            processMetaDataId: processId,
            lookupId: lookupId,
            paramRealPath: paramRealPath,
            code: valId
        },
        dataType: 'json',
        async: false,
        beforeSend: function () {
            $codeField.addClass('spinner2');
        },
        success: function (data) {
            
            if (data.hasOwnProperty('ids')) {
                $parent.find("input[id*='_valueField']:not(:eq(0))").remove();
                if ($parent.find('.combogridInit').length) {
                    $parent.find("input[id*='_displayField']").val(data.names).attr('title', data.names);
                } else {
                    $parent.find("input[id*='_displayField']").val(data.codes).attr('title', data.codes);
                }
                $parent.find("input[id*='_nameField']").val(data.names).attr('title', data.names);
                
                var $getFirstHidden = $parent.find('input[type=hidden]:eq(0)');
                var $getFirstHiddenId = $getFirstHidden.attr('id');
                var $getFirstHiddenDataPath = $getFirstHidden.attr('data-path');
                var $getFirstHiddenName = $getFirstHidden.attr('name');
                
                var idsArr = (data.ids).split(','), i = 0;

                for (i; i < idsArr.length; i++) {
                    if (i == 0) {
                        $parent.find("input[id*='_valueField']:eq(0)").val(idsArr[i]);
                    } else {
                        var $html = '<input type="hidden" name="' + $getFirstHiddenName + '" class="popupInit" id="' + $getFirstHiddenId + '" data-path="' + $getFirstHiddenDataPath + '" value="' + idsArr[i] + '">';
                        $parent.find("input[id*='_valueField']:eq(0)").parent().prepend($html);
                    }
                }
                
            } else {
                $parent.find("input[id*='_valueField']:not(:eq(0))").remove();
                $parent.find("input[id*='_valueField']").val('');
                $parent.find("input[id*='_nameField']").val('').attr('title', '');
                $parent.find("input[id*='_displayField']").val('').attr('title', '');
            }
        }
    }).done(function(){
        $codeField.removeClass('spinner2');
    });
    
    return;
}
function setLookupPopupValueView(element, val) {
    if (val != '' && val != null && val != 'null') {
        var _this = element;
        var params = '';
    
        if (typeof _this.attr("data-row-data") !== "undefined") {
            var attrToJson = JSON.parse(_this.attr("data-row-data"));      
    
            $.ajax({
                type: 'post',
                url: 'mdobject/autoCompleteById',
                data: {
                    processMetaDataId: attrToJson.PROCESS_META_DATA_ID,
                    metaDataId: attrToJson.PARAM_META_DATA_ID,
                    lookupId: attrToJson.LOOKUP_ID,
                    paramRealPath: attrToJson.PARAM_REAL_PATH,
                    code: val,
                    isName: 'idselect', 
                    params: encodeURIComponent(params)
                },
                dataType: "json",
                async: false,
                success: function (data) {
                    if (typeof data.META_VALUE_CODE !== 'undefined' && typeof data.META_VALUE_NAME !== 'undefined') {
                        if ((data.META_VALUE_CODE !== '' && data.META_VALUE_NAME !== '') || (data.META_VALUE_CODE !== null && data.META_VALUE_NAME !== null))
                            _this.text(data.META_VALUE_CODE + ' | ' + data.META_VALUE_NAME);
                        else if ((data.META_VALUE_CODE !== '' && data.META_VALUE_NAME === '') || (data.META_VALUE_CODE !== null && data.META_VALUE_NAME === null))
                            _this.text(data.META_VALUE_CODE);
                        else if ((data.META_VALUE_CODE === '' && data.META_VALUE_NAME !== '') || (data.META_VALUE_CODE === null && data.META_VALUE_NAME !== null))
                            _this.text(data.META_VALUE_NAME);
                    }
                },
                error: function () {
                    alert("Error");
                }
            });
        }
    }
    
    return;
}
function setLookupPopupCode(element, valCode) {

    var $this = element;
    var $parentForm = $this.closest('form');
    var $parent = $this.parent();
    var $codeField = $parent.find("input[id*='_displayField']");
    var _processId = $codeField.attr("data-processid");
    var _lookupId = $codeField.attr("data-lookupid");
    var _metaDataCode = $codeField.attr("data-field-name");
    var _paramRealPath = $this.attr("data-path");
    var params = '';
    
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
            var $parentCell = $parent.closest("[data-cell-path]");
        }

        if ($parent.closest(".bprocess-table-dtl").length > 0) {
            var $parentTable = $parent.closest(".bp-detail-row");
        } else {
            var $parentTable = $parentForm;
        }
    }
    
    if (valCode == null) {
        
        $parent.find("input[id*='_valueField']:not(:eq(0))").remove();
        $parent.find("input[id*='_valueField']").val('');
        $parent.find("input[id*='_nameField']").val('').attr('title', '');
        $parent.find("input[id*='_displayField']").val('').attr('title', '');
        
    } else {
        
        var $valueField = $parent.find("input[type='hidden']");
        
        if (typeof $valueField.attr('data-in-param') !== 'undefined' && $valueField.attr('data-in-param') !== '') {
            
            var $inputParam = $valueField.attr('data-in-param').split('|'), 
                $inputLookupParam = $valueField.attr('data-in-lookup-param').split('|'), 
                $fieldValue = '';
            
            for (var i = 0; i < $inputParam.length; i++) {
                
                var $bpElem = getBpElement($parentForm, $valueField, $inputParam[i]);

                if ($bpElem) {
                    $fieldValue = getBpRowParamNum($parentForm, $valueField, $inputParam[i]);
                    params += $inputLookupParam[i] + '=' + $fieldValue + '&';
                }
            }
        }
        
        if (typeof $valueField.attr("data-criteria-param") !== 'undefined' && $valueField.attr("data-criteria-param") != '') {
            var paramsPathArr = $valueField.attr("data-criteria-param").split('|');
            for (var i = 0; i < paramsPathArr.length; i++) {
                var fieldPathArr = paramsPathArr[i].split('@');
                var fieldPath = fieldPathArr[0];
                var inputPath = fieldPathArr[1];
                var fieldValue = '', isCriteria = false;

                if ($parentForm.find("[data-path='"+fieldPath+"']").length) {
                    fieldValue = getBpRowParamNum($parentForm, $valueField, fieldPath);
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

        $.ajax({
            type: 'post',
            url: 'mdobject/autoCompleteById',
            data: {
                processMetaDataId: _processId,
                lookupId: _lookupId,
                paramRealPath: _paramRealPath,
                code: valCode,
                isName: false, 
                params: encodeURIComponent(params)
            },
            dataType: 'json',
            async: false,
            beforeSend: function () {
                $codeField.addClass('spinner2');
            },
            success: function (data) {

                var rowData;

                if (typeof (data.rowData) !== 'undefined') {
                    rowData = data.rowData;
                }

                if (data.META_VALUE_ID !== '') { 
                    $parent.find("input[id*='_valueField']").val(data.META_VALUE_ID).attr('data-row-data', JSON.stringify(rowData).replace(/&quot;/g, '\\&quot;'));
                    $parent.find("input[id*='_displayField']").val(data.META_VALUE_CODE).attr('title', data.META_VALUE_CODE);
                    $parent.find("input[id*='_nameField']").val(data.META_VALUE_NAME).attr('title', data.META_VALUE_NAME);
                } else {
                    $parent.find("input[id*='_valueField']:not(:eq(0))").remove();
                    $parent.find("input[id*='_valueField']").val('');
                    $parent.find("input[id*='_nameField']").val('').attr('title', '');
                    $parent.find("input[id*='_displayField']").val('').attr('title', '');
                }
            },
            error: function () {
                alert("Error");
            }
        }).done(function(){
            $codeField.removeClass('spinner2');
        });
    }
    
    return;
}
function bpSetColCellVal(mainSelector, elem, fieldPath, val) {    
      
    var $getPathElement = elem.find("[data-path='" + fieldPath + "']");
    
    if ($getPathElement.length) {
        if ($getPathElement.prop("tagName") == 'SELECT') {
            if ($getPathElement.hasClass('select2')) {
                if (val == null || val == '') {
                    $getPathElement.select2('val', '');
                } 
                if ($getPathElement.find("option").length > 2) {
                    $getPathElement.select2('val', val);
                } else if ($getPathElement.attr("data-row-data") !== "undefined") {
                    comboSingleDataSet($getPathElement, val);
                }
            } else {
                $getPathElement.trigger("blur");
                $getPathElement.find("option").filter('[value="' + val + '"]').attr("selected", "selected");
            }
        } else {
            if ($getPathElement.hasClass('longInit') 
                || $getPathElement.hasClass('numberInit') 
                || $getPathElement.hasClass('decimalInit') 
                || $getPathElement.hasClass('integerInit')) {

                $getPathElement.autoNumeric("set", val);

            } else if ($getPathElement.hasClass('bigdecimalInit')) {               
                $getPathElement.next("input[type=hidden]").val(setNumberToFixed(val));
                $getPathElement.autoNumeric("set", val);      
            } else if ($getPathElement.hasClass('dateInit')) {
                if (val !== '' && val !== null) {
                    $getPathElement.datepicker('update', date('Y-m-d', strtotime(val)));
                } else {
                    $getPathElement.datepicker('update', null);
                }
            } else if ($getPathElement.hasClass('datetimeInit')) {
                if (val !== '' && val !== null) {
                    $getPathElement.val(date('Y-m-d H:i:s', strtotime(val)));
                } else {
                    $getPathElement.val('');
                }
            } else if ($getPathElement.hasClass('popupInit')) {   
                setLookupPopupValue($getPathElement, val);
            } else if ($getPathElement.hasClass('booleanInit')) {   
                checkboxCheckerUpdate($getPathElement, val);
            } else {                                              
                $getPathElement.val(val);                        
            }
        }
    } 
    return;
}
function getBpHdrHiddenVal(mainSelector, fieldPath) {
    var $getPathElement = mainSelector.find("input[data-path='" + fieldPath + "']");
    return $getPathElement.val();
}
function getBpRowParamViewVal(mainSelector, elem, fieldPath) {
    var resultNum = '';
    
    if (elem === 'open') {
        
        var $getPathElement = mainSelector.find("[data-view-path='" + fieldPath + "']");
        if ($getPathElement.length > 0) {
            resultNum = $getPathElement.text();
        }
        
    } else {
        var $elem = $(elem);    
        var $this = $($elem, mainSelector);
        var $oneLevelRow = $this.closest('.bp-detail-row');   

        if ($oneLevelRow.find("[data-view-path='" + fieldPath + "']").length == 0) {
            $oneLevelRow = $oneLevelRow.parents('.bp-detail-row');
        }
        
        var $getPathElement = $oneLevelRow.find("[data-view-path='" + fieldPath + "']");
        var $getPathMainElement = mainSelector.find("[data-view-path='" + fieldPath + "']");
        
        if ($getPathElement.length === 0 && $getPathMainElement.length > 0) {
            resultNum = $getPathMainElement.text();
        } else if ($getPathElement.length > 0) {
            resultNum = $getPathElement.text();
        }
    }

    return resultNum;
}
function getBpRowParamViewNum(mainSelector, elem, fieldPath) {
    var resultNum = '';
    
    if (elem === 'open') {
        
        var $getPathElement = mainSelector.find("[data-view-path='" + fieldPath + "']");
        if ($getPathElement.length > 0) {
            resultNum = pureNumber($getPathElement.text());
        }
        
    } else {
        var $elem = $(elem);    
        var $this = $($elem, mainSelector);
        var $oneLevelRow = $this.closest('.bp-detail-row');   

        if ($oneLevelRow.find("[data-view-path='" + fieldPath + "']").length == 0) {
            $oneLevelRow = $oneLevelRow.parents('.bp-detail-row');
        }
        
        var $getPathElement = $oneLevelRow.find("[data-view-path='" + fieldPath + "']");
        var $getPathMainElement = mainSelector.find("[data-view-path='" + fieldPath + "']");
        
        if ($getPathElement.length === 0 && $getPathMainElement.length > 0) {
            resultNum = pureNumber($getPathMainElement.text());
        } else if ($getPathElement.length > 0) {
            resultNum = pureNumber($getPathElement.text());
        }
    }

    return resultNum;
}
function bpGetColCellVal(mainSelector, elem, fieldPath) {
    var resultNum = '';
    
    if (elem.prop('tagName') == 'INPUT') {
        var $cell = elem.closest('td');
        var groupNum = $cell.attr('data-group-num');
        var $row = $cell.closest('tr');
        var $getPathElement = $row.find('[data-group-num="'+groupNum+'"]').find("[data-path='" + fieldPath + "']");
    } else {
        var $getPathElement = elem.find("[data-path='" + fieldPath + "']");
    }
    
    if ($getPathElement.prop("tagName") == 'SELECT') {
        resultNum = $getPathElement.val();
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
            resultNum = $getPathElement.is(':checked') ? 1 : 0;
        } else {
            resultNum = $getPathElement.val();                        
        }
    }
    
    return resultNum;
}
function bpGetColCellBigdecimal(mainSelector, elem, fieldPath) {
    var resultNum = '';
    
    if (elem.prop('tagName') == 'INPUT') {
        var $cell = elem.closest('td');
        var groupNum = $cell.attr('data-group-num');
        var $row = $cell.closest('tr');
        var $getPathElement = $row.find('[data-group-num="'+groupNum+'"]').find("[data-path='" + fieldPath + "']");
    } else {
        var $getPathElement = elem.find("[data-path='" + fieldPath + "']");
    }
    
    if ($getPathElement.prop("tagName") == 'SELECT') {
        resultNum = Number($getPathElement.val());
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
                resultNum = Number($getPathElement.val());
            } else {
                resultNum = Number(getNumber);
            }
        } else if ($getPathElement.hasClass('booleanInit')) { 
            resultNum = $getPathElement.is(':checked') ? 1 : 0;
        } else {
            resultNum = Number($getPathElement.val());                        
        }
    }
    
    return resultNum;
}
function getBpRowParamNum(mainSelector, elem, fieldPath) {
    var resultNum = '';
    
    if (elem === 'open') {
        var $getPathElement = mainSelector.find("[data-path='" + fieldPath + "']");
        if ($getPathElement.length) {
            if ($getPathElement.prop("tagName") == 'SELECT') {
                
                if ($getPathElement.is('[multiple]') && $getPathElement.hasClass('select2')) {
                        
                    var selectedValue = $getPathElement.select2('data');

                    if (selectedValue.length) {
                        var result = new Array(), checkArr = {};
                        for (var s in selectedValue) {
                            var sId = selectedValue[s]['id'];
                            if (!checkArr.hasOwnProperty(sId)) {
                                checkArr[sId] = 1;
                                result.push(sId);
                            }
                        }
                        resultNum = result;
                    } else {
                        resultNum = '';
                    }
                } else {
                    resultNum = $getPathElement.val();
                } 
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
                    resultNum = $getPathElement.is(':checked') ? 1 : 0;
                } else {
                    resultNum = $getPathElement.val();                        
                }
            }
        } else {
            var $getPathElement = mainSelector.find("[data-view-path='" + fieldPath + "']");
            if ($getPathElement.length) {
                resultNum = $getPathElement.text();
            }
        }
        
    } else {
        elem = $(elem);    
        
        if (elem.closest('.sidebar_detail').length) {
            var $oneLevelRow = mainSelector.find('.bprocess-table-dtl > .tbody > .currentTarget');
        } else {
            var $oneLevelRow = elem.closest('.bp-detail-row');   
            if ($oneLevelRow.find("[data-path='" + fieldPath + "']").length == 0) {
                var $parentsRow = $oneLevelRow.parents('.bp-detail-row');
                if ($parentsRow.length) {
                    $oneLevelRow = $parentsRow;
                }
            }
        }
        
        var $getPathElement = $oneLevelRow.find("[data-path='" + fieldPath + "']");
        
        if ($getPathElement.length) {
            if ($getPathElement.prop('tagName') == 'SELECT') {
                
                if ($getPathElement.is('[multiple]') && $getPathElement.hasClass('select2')) {
                        
                    var selectedValue = $getPathElement.select2('data');

                    if (selectedValue.length) {
                        var result = new Array(), checkArr = {};
                        for (var s in selectedValue) {
                            var sId = selectedValue[s]['id'];
                            if (!checkArr.hasOwnProperty(sId)) {
                                checkArr[sId] = 1;
                                result.push(sId);
                            }
                        }
                        resultNum = result;
                    } else {
                        resultNum = '';
                    }
                } else {
                    resultNum = $getPathElement.val();
                } 
                
            } else {
                if ($getPathElement.hasClass('numberInit') 
                    || $getPathElement.hasClass('decimalInit')
                    || $getPathElement.hasClass('integerInit')) {

                    var getNumber = $getPathElement.autoNumeric('get');
                    if (isNaN(getNumber)) {
                        resultNum = Number($getPathElement.val());
                    } else {
                        resultNum = Number(getNumber);
                    }                        
                } else if ($getPathElement.hasClass('bigdecimalInit')) {

                    resultNum = Number($getPathElement.next('input[type=hidden]').val());

                } else if ($getPathElement.hasClass('longInit')) {
                    var getNumber = $getPathElement.autoNumeric('get');
                    if (isNaN(getNumber)) {
                        resultNum = $getPathElement.val();
                    } else {
                        resultNum = getNumber;
                    }
                } else if ($getPathElement.hasClass('booleanInit')) { 
                    resultNum = $getPathElement.is(':checked') ? 1 : 0;
                } else {
                    resultNum = $getPathElement.val();
                }
            }
        } else {
            
            var $getPathElement = $oneLevelRow.find("[data-view-path='" + fieldPath + "']");
            if ($getPathElement.length) {
                resultNum = $getPathElement.text();
                return resultNum;
            }
            
            var $getPathMainElement = mainSelector.find("[data-path='" + fieldPath + "']");
            
            if ($getPathMainElement.length) {
                
                if ($getPathMainElement.prop('tagName') == 'SELECT') {
                    
                    if ($getPathMainElement.is('[multiple]') && $getPathMainElement.hasClass('select2')) {
                        
                        var selectedValue = $getPathMainElement.select2('data');
                        
                        if (selectedValue.length) {
                            var result = new Array(), checkArr = {};
                            for (var s in selectedValue) {
                                var sId = selectedValue[s]['id'];
                                if (!checkArr.hasOwnProperty(sId)) {
                                    checkArr[sId] = 1;
                                    result.push(sId);
                                }
                            }
                            //resultNum = result.join(',');
                            resultNum = result;
                        } else {
                            resultNum = '';
                        }
                    } else {
                        resultNum = $getPathMainElement.val();
                    } 
    
                } else {
                    if ($getPathMainElement.hasClass('numberInit') 
                        || $getPathMainElement.hasClass('decimalInit') 
                        || $getPathMainElement.hasClass('integerInit')) {
                        
                        var getNumber = $getPathMainElement.autoNumeric("get");
                        if (isNaN(getNumber)) {
                            resultNum = Number($getPathMainElement.val());
                        } else {
                            resultNum = Number(getNumber);
                        }
                    } else if ($getPathMainElement.hasClass('bigdecimalInit')) {
                        
                        resultNum = Number($getPathMainElement.next("input[type=hidden]").val());
                        
                    } else if ($getPathMainElement.hasClass('longInit')) {
                        var getNumber = $getPathMainElement.autoNumeric("get");
                        if (isNaN(getNumber)) {
                            resultNum = $getPathMainElement.val();
                        } else {
                            resultNum = getNumber;
                        }
                    } else if ($getPathElement.hasClass('booleanInit')) { 
                        resultNum = $getPathElement.is(':checked') ? 1 : 0;
                    } else {
                        resultNum = $getPathMainElement.val();
                    }
                }
            } else {
                var $getPathElement = mainSelector.find("[data-view-path='" + fieldPath + "']");
                if ($getPathElement.length) {
                    resultNum = $getPathElement.text();
                }
            }
        }
    }

    return resultNum;
}
function getBpRowParamBigdecimal(mainSelector, elem, fieldPath) {
    var resultNum = '';
    
    if (elem === 'open') {
        var $getPathElement = mainSelector.find("[data-path='" + fieldPath + "']");
        if ($getPathElement.length) {
            resultNum = Number($getPathElement.next("input[type=hidden]").val());
        }
    } else {
        elem = $(elem);    
        
        var $oneLevelRow = elem.closest('.bp-detail-row');   
        if ($oneLevelRow.find("[data-path='" + fieldPath + "']").length == 0) {
            $oneLevelRow = $oneLevelRow.parents('.bp-detail-row');
        }
        
        var $getPathElement = $oneLevelRow.find("[data-path='" + fieldPath + "']");
        
        if ($getPathElement.length) {
            resultNum = Number($getPathElement.next('input[type=hidden]').val());
        } else {
            var $getPathMainElement = mainSelector.find("[data-path='" + fieldPath + "']");
            if ($getPathMainElement.length) {
                resultNum = Number($getPathMainElement.next("input[type=hidden]").val());
            }
        }
    }

    return resultNum;
}
function getBpRowParamInteger(mainSelector, elem, fieldPath) {
    var resultNum = '';
    
    if (elem === 'open') {
        var $getPathElement = mainSelector.find("[data-path='" + fieldPath + "']");
        if ($getPathElement.length) {
            var getNumber = $getPathElement.autoNumeric('get');
            if (isNaN(getNumber)) {
                resultNum = Number($getPathElement.val());
            } else {
                resultNum = Number(getNumber);
            }
        }
    } else {
        elem = $(elem);    
        
        var $oneLevelRow = elem.closest('.bp-detail-row');   
        if ($oneLevelRow.find("[data-path='" + fieldPath + "']").length == 0) {
            $oneLevelRow = $oneLevelRow.parents('.bp-detail-row');
        }
        
        var $getPathElement = $oneLevelRow.find("[data-path='" + fieldPath + "']");
        
        if ($getPathElement.length) {
            var getNumber = $getPathElement.autoNumeric('get');
            if (isNaN(getNumber)) {
                resultNum = Number($getPathElement.val());
            } else {
                resultNum = Number(getNumber);
            }
        } else {
            var $getPathMainElement = mainSelector.find("[data-path='" + fieldPath + "']");
            if ($getPathMainElement.length) {
                var getNumber = $getPathMainElement.autoNumeric('get');
                if (isNaN(getNumber)) {
                    resultNum = Number($getPathMainElement.val());
                } else {
                    resultNum = Number(getNumber);
                }
            }
        }
    }

    return resultNum;
}
function getBpLookupValue(mainSelector, elem, fieldPath) {
    var $bpElem = getBpElement(mainSelector, elem, fieldPath);
    
    if (typeof $bpElem == 'undefined' || $bpElem == false) {
        return '';
    }
    
    if ($bpElem.prop('tagName') == 'SELECT') {
        if ($bpElem.is('[multiple]')) {
            
            var selectedValue = $bpElem.find('option:selected');
            
            if (selectedValue.length) {
                var result = new Array();

                selectedValue.each(function() {
                    result.push($(this).val());
                });

                return result.join(',');
            } else {
                return '';
            }
        }
    } else if ($bpElem.hasClass('popupInit') || $bpElem.hasClass('combogridInit')) {
        
        var $parent = $bpElem.closest('.input-group');
        var $hidden = $parent.find("input[type='hidden']");

        if ($hidden.length > 1) {
            var result = new Array();

            $hidden.each(function() {
                result.push($(this).val());
            });

            return result.join(',');
        }
    }
    
    return $bpElem.val();
}
function getBpComboValue(mainSelector, elem, fieldPath) {
    var $bpElem = getBpElement(mainSelector, elem, fieldPath);
    
    if (typeof $bpElem == 'undefined' || $bpElem == false) {
        return '';
    }
    
    if ($bpElem.prop('tagName') == 'SELECT' && $bpElem.is('[multiple]')) {
            
        var selectedValue = $bpElem.find('option:selected');

        if (selectedValue.length) {
            var result = new Array();

            selectedValue.each(function() {
                result.push($(this).val());
            });

            return result.join(',');
        } else {
            return '';
        }
    }
    
    return $bpElem.val();
}
function getBpComboText(mainSelector, elem, fieldPath) {
    var resultNum = '';
    
    if (elem === 'open') {
        var getPathElement = mainSelector.find("[data-path='" + fieldPath + "']");
        if (getPathElement.length > 0) {
            if (getPathElement.prop("tagName") == 'SELECT') {
                var combobox = $("option:selected", getPathElement);
                resultNum = (combobox.val() != '') ? combobox.text() : '';
            }
        } else {
            var getPathElement = mainSelector.find("[data-view-path='" + fieldPath + "']");
            if (getPathElement.length > 0) {
                var combobox = $("option:selected", getPathElement);
                resultNum = (combobox.val() != '') ? combobox.text() : '';
            }
        }
        
    } else {
        elem = $(elem);    
        if (elem.closest(".sidebar_detail").length > 0) {
            var _oneLevelRow = mainSelector.find('.bprocess-table-dtl > .tbody > .currentTarget');
        } else {
            var _this = $(elem, mainSelector);
            var _oneLevelRow = _this.closest(".bp-detail-row");   
            
            if (_oneLevelRow.find("[data-path='" + fieldPath + "']").length == 0) {
                _oneLevelRow = _oneLevelRow.parents('.bp-detail-row');
            }
        }
        
        var getPathElement = _oneLevelRow.find("[data-path='" + fieldPath + "']");
        var getPathMainElement = mainSelector.find("[data-path='" + fieldPath + "']");
        
        if (getPathElement.length === 0) {
            if (getPathMainElement.length > 0 && getPathMainElement.prop("tagName") == 'SELECT') {
                var combobox = $("option:selected", getPathMainElement);
                resultNum = (combobox.val() != '') ? combobox.text() : '';
            }
        } else {        
            if (getPathElement.length > 0 && getPathElement.prop("tagName") == 'SELECT') {
                var combobox = $("option:selected", getPathElement);
                resultNum = (combobox.val() != '') ? combobox.text() : '';
            }
        }
    }

    return resultNum;
}
function setBpRowParamNumSidebar(mainSelector, elem, fieldPath, val) {
    
    if (!pfFullExpSetFieldValue) {
        return;
    }
    
    if (elem !== 'open') {
        
        elem = $(elem);
        var _this = $(elem, mainSelector);
        var _oneLevelRow = _this.closest(".bp-detail-row");        
        var selectedTR = mainSelector.find('.bprocess-table-dtl > .tbody > .currentTarget');
        
        if (typeof _this.prop('tagName') !== 'undefined' && _this.hasClass('bp-detail-row') && _this.find("[data-path='" + fieldPath + "']").length) {
            var getPathElement = _this.find("[data-path='" + fieldPath + "']");
            
            if (getPathElement.prop('tagName') == 'SELECT') {

                if (getPathElement.hasClass('select2')) {
                    if (getPathElement.find("option").length > 2) {
                        getPathElement.find("option").filter('[value="' + val + '"]').attr("selected", "selected");
                    } else if (getPathElement.attr("data-row-data") !== "undefined") {
                        comboSingleDataSet(getPathElement, val);
                    }
                } else {
                    getPathElement.trigger("blur");
                    getPathElement.find("option").filter('[value="' + val + '"]').attr("selected", "selected");
                }
            } else {
                if (getPathElement.hasClass('numberInit') 
                    || getPathElement.hasClass('decimalInit')
                    || getPathElement.hasClass('integerInit')) { 
                
                    getPathElement.autoNumeric("set", val);        
                } else if (getPathElement.hasClass('bigdecimalInit')) {  
                    
                    getPathElement.next("input[type=hidden]").val(setNumberToFixed(val));
                    getPathElement.autoNumeric("set", val);         
                    
                } else if (getPathElement.hasClass('dateInit')) {
                    if (val !== '' && val !== null) {
                        getPathElement.datepicker('update', date('Y-m-d', strtotime(val)));
                    } else {
                        getPathElement.datepicker('update', null);
                    }
                } else if (getPathElement.hasClass('datetimeInit')) {
                    if (val !== '' && val !== null) {
                        getPathElement.val(date('Y-m-d H:i:s', strtotime(val)));
                    } else {
                        getPathElement.val('');
                    }
                } else if (getPathElement.hasClass('popupInit')) {   
                    setLookupPopupValue(getPathElement, val);
                } else {
                    getPathElement.val(val);        
                }
            }
            return;
        }
        
        if (_oneLevelRow.find("[data-path='" + fieldPath + "']").length === 0 && selectedTR.find("[data-path='" + fieldPath + "']").length === 0) {
            
            var getPathElement = mainSelector.find("[data-path='" + fieldPath + "']");
            
            if (getPathElement.length) {
                if (getPathElement.prop("tagName") == 'SELECT') {

                    if (getPathElement.hasClass('select2')) {
                        if (getPathElement.find("option").length > 2) {
                            getPathElement.find("option").filter('[value="' + val + '"]').attr("selected", "selected");
                        } else if (getPathElement.attr("data-row-data") !== "undefined") {
                            comboSingleDataSet(getPathElement, val);
                        }
                    } else {
                        getPathElement.trigger("blur");
                        getPathElement.find("option").filter('[value="' + val + '"]').attr("selected", "selected");
                    }
                } else {
                    if (getPathElement.hasClass('numberInit') 
                        || getPathElement.hasClass('decimalInit')
                        || getPathElement.hasClass('integerInit')) { 
                        getPathElement.autoNumeric("set", val);        

                    } else if (getPathElement.hasClass('bigdecimalInit')) {            
                        getPathElement.next("input[type=hidden]").val(setNumberToFixed(val));
                        getPathElement.autoNumeric("set", val);         
                    } else if (getPathElement.hasClass('dateInit')) {
                        if (val !== '' && val !== null) {
                            getPathElement.datepicker('update', date('Y-m-d', strtotime(val)));
                        } else {
                            getPathElement.datepicker('update', null);
                        }
                    } else if (getPathElement.hasClass('datetimeInit')) {
                        if (val !== '' && val !== null) {
                            getPathElement.val(date('Y-m-d H:i:s', strtotime(val)));
                        } else {
                            getPathElement.val('');
                        }
                    } else if (getPathElement.hasClass('popupInit')) {   
                        setLookupPopupValue(getPathElement, val);
                    } else {
                        getPathElement.val(val);        
                    }
                }
            }
            
            return;

        } else if (_oneLevelRow.find("[data-path='" + fieldPath + "']").length === 1 && selectedTR.find("[data-path='" + fieldPath + "']").length === 1) {
            
            var getPathElement = selectedTR.find("[data-path='" + fieldPath + "']");

            if (getPathElement.prop("tagName") == 'SELECT') {

                if (getPathElement.hasClass('select2')) {
                    if (getPathElement.find("option").length > 2) {
                        getPathElement.find("option").filter('[value="' + val + '"]').attr("selected", "selected");
                    } else if(getPathElement.attr("data-row-data") !== "undefined") {
                        comboSingleDataSet(getPathElement, val);
                    }
                } else {
                    getPathElement.trigger("blur");
                    getPathElement.find("option").filter('[value="' + val + '"]').attr("selected", "selected");
                }
            } else {
                if (getPathElement.hasClass('popupInit')) {   
                    setLookupPopupValue(getPathElement, val);
                } else if (getPathElement.hasClass('numberInit') 
                            || getPathElement.hasClass('decimalInit')
                            || getPathElement.hasClass('integerInit')) { 
                    getPathElement.autoNumeric("set", val);    

                } else if (getPathElement.hasClass('bigdecimalInit')) { 
                    
                    getPathElement.next("input[type=hidden]").val(setNumberToFixed(val));
                    getPathElement.autoNumeric("set", val);   

                } else if (getPathElement.hasClass('dateInit')) {
                    if (val !== '' && val !== null) {
                        getPathElement.datepicker('update', date('Y-m-d', strtotime(val)));
                    } else {
                        getPathElement.datepicker('update', null);
                    }
                } else if (getPathElement.hasClass('datetimeInit')) {
                    if (val !== '' && val !== null) {
                        getPathElement.val(date('Y-m-d H:i:s', strtotime(val)));
                    } else {
                        getPathElement.val('');
                    }
                } else {
                    getPathElement.val(val);
                }
            }
            return;
            
        } else if (_oneLevelRow.find("[data-path='" + fieldPath + "']").length === 0) {
            
            var getPathElement = selectedTR.find("[data-path='" + fieldPath + "']");
            
            if (getPathElement.hasClass('popupInit')) {   
                setLookupPopupValue(getPathElement, val);
            } else if (getPathElement.hasClass('numberInit') 
                        || getPathElement.hasClass('decimalInit')
                        || getPathElement.hasClass('integerInit')) { 
                getPathElement.autoNumeric("set", val);    

            } else if (getPathElement.hasClass('bigdecimalInit')) { 

                getPathElement.next("input[type=hidden]").val(setNumberToFixed(val));
                getPathElement.autoNumeric("set", val);   

            } else if (getPathElement.hasClass('dateInit')) {
                if (val !== '' && val !== null) {
                    getPathElement.datepicker('update', date('Y-m-d', strtotime(val)));
                } else {
                    getPathElement.datepicker('update', null);
                }
            } else if (getPathElement.hasClass('datetimeInit')) {
                if (val !== '' && val !== null) {
                    getPathElement.val(date('Y-m-d H:i:s', strtotime(val)));
                } else {
                    getPathElement.val('');
                }
            } else {
                getPathElement.val(val);
            }

            return;
            
        } else {
            
            if (_this.hasClass("bp-detail-row")) {
                var $row = _this;
            } else {
                var $parentRow = _this.closest(".bp-detail-row");

                if ($parentRow.find("[data-path='" + fieldPath + "']").length > 0) {
                    var $row = $parentRow;
                } else {
                    if (_oneLevelRow.find("[data-path='" + fieldPath + "']").length > 1) {
                        var $row = selectedTR;
                    } else {
                        var $row = _oneLevelRow;
                    } 
                }
            }

            var getPathElement = $row.find("[data-path='" + fieldPath + "']");

            if (getPathElement.prop("tagName") == 'SELECT') {

                if (getPathElement.hasClass('select2')) {
                    if (getPathElement.find("option").length > 2) {
                        getPathElement.find("option").filter('[value="' + val + '"]').attr("selected", "selected");
                    } else if (getPathElement.attr("data-row-data") !== "undefined") {
                        comboSingleDataSet(getPathElement, val);
                    }
                } else {
                    getPathElement.trigger("blur");
                    getPathElement.find("option").filter('[value="' + val + '"]').attr("selected", "selected");
                }
                
            } else {

                if (getPathElement.hasClass('popupInit')) { 
                    
                    setLookupPopupValue(getPathElement, val);
                    
                } else if (getPathElement.hasClass('numberInit') 
                            || getPathElement.hasClass('decimalInit')
                            || getPathElement.hasClass('integerInit')) { 

                    getPathElement.autoNumeric("set", val);    

                } else if (getPathElement.hasClass('bigdecimalInit')) {          
                    getPathElement.next("input[type=hidden]").val(setNumberToFixed(val));
                    getPathElement.autoNumeric("set", val);                                                      
                } else if (getPathElement.hasClass('dateInit')) {
                    if (val !== '' && val !== null) {
                        getPathElement.datepicker('update', date('Y-m-d', strtotime(val)));
                    } else {
                        getPathElement.datepicker('update', null);
                    }
                } else if (getPathElement.hasClass('datetimeInit')) {
                    if (val !== '' && val !== null) {
                        getPathElement.val(date('Y-m-d H:i:s', strtotime(val)));
                    } else {
                        getPathElement.val('');
                    }
                } else {
                    getPathElement.val(val);
                } 
            }
            
            return;
        }
    } else {
        var getPathElement = mainSelector.find("[data-path='" + fieldPath + "']");
        
        if (getPathElement.length > 0) {
            if (getPathElement.prop("tagName") == 'SELECT') {
                if (getPathElement.hasClass('select2')) {
                    if (getPathElement.find("option").length > 2) {
                        getPathElement.find("option").filter('[value="' + val + '"]').attr("selected", "selected");
                    } else if (getPathElement.attr("data-row-data") !== "undefined") {
                        comboSingleDataSet(getPathElement, val);
                    }
                } else {
                    getPathElement.trigger("blur");
                    getPathElement.find("option").filter('[value="' + val + '"]').attr("selected", "selected");
                }
            } else {
                if (getPathElement.hasClass('longInit') 
                    || getPathElement.hasClass('numberInit') 
                    || getPathElement.hasClass('decimalInit') 
                    || getPathElement.hasClass('integerInit')) {

                    getPathElement.autoNumeric("set", val);
                    
                } else if (getPathElement.hasClass('bigdecimalInit')) {               
                    getPathElement.next("input[type=hidden]").val(setNumberToFixed(val));
                    getPathElement.autoNumeric("set", val);      
                } else if (getPathElement.hasClass('dateInit')) {
                    if (val !== '' && val !== null) {
                        getPathElement.datepicker('update', date('Y-m-d', strtotime(val)));
                    } else {
                        getPathElement.datepicker('update', null);
                    }
                } else if (getPathElement.hasClass('datetimeInit')) {
                    if (val !== '' && val !== null) {
                        getPathElement.val(date('Y-m-d H:i:s', strtotime(val)));
                    } else {
                        getPathElement.val('');
                    }
                } else if (getPathElement.hasClass('popupInit')) {   
                    setLookupPopupValue(getPathElement, val);
                } else {                                              
                    getPathElement.val(val);                        
                }
            }
        } else {
            var getPathElement = mainSelector.find("[data-view-path='" + fieldPath + "']");
            if (getPathElement.length > 0) {
                if (getPathElement.hasClass('dropdownInput')) {
                    comboSingleDataSetView(getPathElement, val);
                } else if (getPathElement.hasClass('popupInput')) {
                    setLookupPopupValueView(getPathElement, val);
                } else {
                    getPathElement.text(val);
                }
            }
        }
        
        return;
    }
}
function setBpRowParamBigdecimalSidebar(mainSelector, elem, fieldPath, val) {
    
    if (!pfFullExpSetFieldValue) {
        return;
    }
    
    if (elem !== 'open') {
        
        var $this = $(elem);
        
        if (typeof $this.prop('tagName') !== 'undefined' && $this.hasClass('bp-detail-row') && $this.find("[data-path='" + fieldPath + "']").length) {
            var $getPathElement = $this.find("[data-path='" + fieldPath + "']");

            $getPathElement.next('input[type=hidden]').val(setNumberToFixed(val));
            $getPathElement.autoNumeric('set', val);  
            
            return;
        }
        
        var $oneLevelRow = $this.closest('.bp-detail-row');        
        var $selectedTR = mainSelector.find('.bprocess-table-dtl > .tbody > .currentTarget');
        
        if ($oneLevelRow.find("[data-path='" + fieldPath + "']").length === 0 && $selectedTR.find("[data-path='" + fieldPath + "']").length === 0) {
            
            var $getPathElement = mainSelector.find("[data-path='" + fieldPath + "']");
            
            if ($getPathElement.length) {
                $getPathElement.next('input[type=hidden]').val(setNumberToFixed(val));
                $getPathElement.autoNumeric('set', val);  
            }
            
            return;

        } else if ($oneLevelRow.find("[data-path='" + fieldPath + "']").length === 1 && $selectedTR.find("[data-path='" + fieldPath + "']").length === 1) {
            
            var $getPathElement = $selectedTR.find("[data-path='" + fieldPath + "']");

            $getPathElement.next('input[type=hidden]').val(setNumberToFixed(val));
            $getPathElement.autoNumeric('set', val);  
            
            return;
            
        } else if ($oneLevelRow.find("[data-path='" + fieldPath + "']").length === 0) {
            
            var $getPathElement = $selectedTR.find("[data-path='" + fieldPath + "']");
            
            $getPathElement.next('input[type=hidden]').val(setNumberToFixed(val));
            $getPathElement.autoNumeric('set', val);     

            return;
            
        } else {
            
            if ($this.hasClass('bp-detail-row')) {
                var $v = $this;
            } else {
                var $parentRow = $this.closest('.bp-detail-row');

                if ($parentRow.find("[data-path='" + fieldPath + "']").length > 0) {
                    var $v = $parentRow;
                } else {
                    if ($oneLevelRow.find("[data-path='" + fieldPath + "']").length > 1) {
                        var $v = $selectedTR;
                    } else {
                        var $v = $oneLevelRow;
                    } 
                }
            }

            var $getPathElement = $v.find("[data-path='" + fieldPath + "']");

            $getPathElement.next('input[type=hidden]').val(setNumberToFixed(val));
            $getPathElement.autoNumeric('set', val);          
            
            return;
        }
    } else {
        var $getPathElement = mainSelector.find("[data-path='" + fieldPath + "']");
        
        if ($getPathElement.length) {
            $getPathElement.next('input[type=hidden]').val(setNumberToFixed(val));
            $getPathElement.autoNumeric('set', val); 
        } 
        return;
    }
}
function getBpRowParamNumSidebar(mainSelector, elem, fieldPath) {
    var $this = $(elem);
    var $oneLevelRow = $this.closest(".tbody");
    var $selectedTR = mainSelector.find('.bprocess-table-dtl > .tbody > .currentTarget');
    var $oneLevelRowLength = $oneLevelRow.find("[data-path='" + fieldPath + "']").length;
    var $selectedTRLength = $selectedTR.find("[data-path='" + fieldPath + "']").length;
        
    if ($oneLevelRowLength === 0 && $selectedTRLength === 0) {
        if (mainSelector.find("input[data-path='" + fieldPath + "']").length) {
            var $v = mainSelector.find("input[data-path='" + fieldPath + "']");
            if ($v.length > 0) {
                if ($v.hasClass("numberInit") || $v.hasClass("decimalInit")) {
                    var resultNum = Number($v.autoNumeric("get"));
                } else if ($v.hasClass("bigdecimalInit")) {
                    var resultNum = Number($v.next("input[type=hidden]").val());
                } else
                    var resultNum = $v.val();
            }              
        } else if (mainSelector.find("select[data-path='" + fieldPath + "']").length) {
            var resultNum = mainSelector.find("select[data-path='" + fieldPath + "']").select2('val');
            if (resultNum != '') {
                resultNum = mainSelector.find("select[data-path='" + fieldPath + "']").val();
            }            
        } else {
            var resultNum = mainSelector.find("[data-path='" + fieldPath + "']").val();           
        }            

    } else if (($oneLevelRowLength === 0 && $selectedTRLength === 1) || ($oneLevelRowLength === 1 && $selectedTRLength === 1)) {
        
        var $v = $selectedTR.find("[data-path='" + fieldPath + "']");
        
        if ($v.hasClass('popupInit')) {   
            var resultNum = $v.val();
        } else if ($v.hasClass('numberInit') || $v.hasClass('integerInit')) { 
            var resultNum = Number($v.autoNumeric("get"));
        } else if ($v.hasClass('bigdecimalInit')) {
            var resultNum = Number($v.next("input[type=hidden]").val());               
        } else {
            var resultNum = $v.val();   
        } 

    } else {

        if ($this.hasClass('bp-detail-row')) {
            var $v = $this;
        } else {
            var $parentRow = $this.closest('.bp-detail-row');

            if ($parentRow.find("[data-path='" + fieldPath + "']").length) {
                var $v = $parentRow;
            } else {
                if ($oneLevelRow.find("[data-path='" + fieldPath + "']").length > 1) {
                    var $v = $selectedTR;
                } else {
                    var $v = $oneLevelRow;
                } 
            }
        }
        
        var $getElement = $v.find("[data-path='" + fieldPath + "']");

        if ($getElement.length) {
            if ($getElement.hasClass("numberInit") || $getElement.hasClass("decimalInit")) {
                var resultNum = Number($getElement.autoNumeric('get'));
            } else if ($getElement.hasClass('bigdecimalInit')) {
                var resultNum = Number($getElement.next('input[type=hidden]').val());                  
            } else
                var resultNum = $getElement.val();
        }
    } 
    return resultNum;
}
function setBpRowParamOnlyHidden(mainSelector, elem, fieldPath) {
    var $this = $(elem);
    var $selectedTR = mainSelector.find('.bprocess-table-dtl > .tbody > .currentTarget');

    $selectedTR.find("td:last-child").find("input[data-path='" + fieldPath + "']").val($this.val().replace(/[,]/g, ''));
    $selectedTR.find("td:last-child").find("select[data-path='" + fieldPath + "'] option").removeAttr("selected");
    $selectedTR.find("td:last-child").find("select[data-path='" + fieldPath + "'] option").filter('[value="' + $this.val() + '"]').attr("selected", "selected");
    return;
}
function getBpRowParamVal(mainSelector, elem, fieldPath) {
    var resultVal = '';
    var $this = $(elem, mainSelector);
    var $oneLevelRow = $this.closest('.bp-detail-row');
    var $getPathElement = $oneLevelRow.find("[data-path='" + fieldPath + "']:not([onclick*='bpWebCamera('])");
    var $getPathMainElementView = mainSelector.find("[data-view-path='" + fieldPath + "']");
    
    if ($getPathElement.length === 0) {
        
        var fieldPathArr = fieldPath.split('.');
        var $getPathMainElement = mainSelector.find("[data-path='" + fieldPath + "']:not([onclick*='bpWebCamera('])");
        
        if (fieldPathArr.length > 0) {
            
            var $parentRow = $oneLevelRow.parents('.bp-detail-row');
            $getPathElement = $parentRow.find("[data-path='" + fieldPath + "']");
            
            if ($getPathElement.length) {
                resultVal = $getPathElement.val();
            } else if ($getPathMainElement.length) {
                resultVal = $getPathMainElement.val();
            } else {
                var $getPathElement = $oneLevelRow.find("[data-view-path='" + fieldPath + "']:not([onclick*='bpWebCamera('])");
                if ($getPathElement.length) {
                    return $getPathElement.text();
                }
            }
        } else if ($getPathMainElement.length > 0) {
            resultVal = $getPathMainElement.val();
        } 
        
    } else {
        resultVal = $getPathElement.val();
        if (resultVal == '' && $getPathElement.hasClass('fileInit')) {
            var $fileHidden = $getPathElement.next('input[type="hidden"]');
            if ($fileHidden.length) {
                resultVal = $fileHidden.val();
            }
        }
    }
    
    if ($getPathMainElementView.length > 0) {
        resultVal = $getPathMainElementView.text();
    }

    return resultVal;
}
function getBpRowParamCheckBox(mainSelector, elem, fieldPath) {
    if (elem === 'open') {
        var $getPathElement = mainSelector.find("input[data-path='" + fieldPath + "']");
        var $resultCheckBox = $getPathElement.is(':checked');
    } else {
        var $this = $(elem, mainSelector);
        var $oneLevelRow = $this.closest(".bp-detail-row");

        if ($oneLevelRow.find("input[data-path='" + fieldPath + "']").length === 0) {
            var $selectedTR = mainSelector.find('.bprocess-table-dtl > .tbody > .currentTarget');
            if ($selectedTR.find("input[data-path='" + fieldPath + "']").length === 0) {
                var $resultCheckBox = mainSelector.find("input[data-path='" + fieldPath + "']").is(':checked');
            } else
                var $resultCheckBox = $selectedTR.find("input[data-path='" + fieldPath + "']").is(':checked');
        } else {
            var $resultCheckBox = $oneLevelRow.find("input[data-path='" + fieldPath + "']").is(':checked');
        }
    }

    return $resultCheckBox;
}
function setBpRowParamEnable(mainSelector, elem, fieldPath) {
    var _this = $(elem, mainSelector);
    
    if (elem === 'open') {
        
        var $getPathElement = mainSelector.find("[data-path='" + fieldPath + "']");
        
        if ($getPathElement.length) {
            if ($getPathElement.prop('tagName') == 'SELECT') {
                if ($getPathElement.hasClass('select2')) {
                    $getPathElement.select2('readonly', false).select2('enable');
                } else {
                    $getPathElement.removeAttr('style');
                }
            } else {
                $getPathElement.removeAttr('readonly disabled onkeydown').removeClass('disable-click');
                var $codeName = $getPathElement.closest('div.meta-autocomplete-wrap');
                if ($codeName.length) {
                    $codeName.find("input[type='text']").removeAttr('readonly disabled tabindex');
                    $codeName.find('button').removeAttr('style').prop('disabled', false);
                } else if ($getPathElement.hasClass('fileInit')) {
                    var $parent = $getPathElement.closest('.uniform-uploader');
                    $parent.find('button').prop('disabled', false);
                    $parent.removeClass('disabled');
                }               
            }
        }
        return;    
    }    
    
    var $oneLevelRow = _this.closest('.bp-detail-row'), $selectedTR = mainSelector.find('.bprocess-table-dtl > .tbody > .currentTarget');
    
    if ($oneLevelRow.find("[data-path='" + fieldPath + "']").length === 0) { 
        if ($selectedTR.find("[data-path='" + fieldPath + "']").length) {
            var $getField = $selectedTR.find("[data-path='" + fieldPath + "']");
            if ($getField.prop('tagName') == 'SELECT') {
                if ($getField.hasClass("select2")) {
                    $getField.select2('readonly', false).select2('enable');
                } else {
                    $getField.removeAttr('style disabled');
                }
            } else {
                $getField.removeAttr("readonly disabled onkeydown").removeClass('disable-click');
                var $codeName = $getField.closest("div.meta-autocomplete-wrap");
                if ($codeName.length > 0) {
                    $codeName.find("input[type='text']").removeAttr('readonly disabled tabindex');
                    $codeName.find("button").removeAttr("style").prop("disabled", false);
                } else if ($getField.hasClass('fileInit')) {
                    var $parent = $getField.closest('.uniform-uploader');
                    $parent.find('button').prop('disabled', false);
                    $parent.removeClass('disabled');
                }                   
            }
        } else {
            var $getField = mainSelector.find("[data-path='" + fieldPath + "']");
            if ($getField.prop('tagName') == 'SELECT') {
                if ($getField.hasClass("select2")) {
                    $getField.select2('readonly', false).select2('enable');
                    $getField.parent().find("div[data-s-path='"+fieldPath+"']").removeClass('select2-container-disabled');
                } else {
                    $getField.removeAttr('style disabled');
                }
            } else {
                $getField.removeAttr("readonly disabled onkeydown").removeClass('disable-click');
                
                var $codeName = $getField.closest('div.meta-autocomplete-wrap');
                if ($codeName.length) {
                    $codeName.find("input[type='text']").removeAttr('readonly disabled tabindex');
                    $codeName.find('button').removeAttr('style').prop('disabled', false);
                } else if ($getField.hasClass('fileInit')) {
                    var $parent = $getField.closest('.uniform-uploader');
                    $parent.find('button').prop('disabled', false);
                    $parent.removeClass('disabled');
                }     
            }
        }
    } else if ($selectedTR.find("td:last-child").find("i.input_html").find("input[data-path='" + fieldPath + "']").length) {
        mainSelector.find("div.right-sidebar-content").find("input[data-path='" + fieldPath + "']").removeAttr("readonly disabled");
        $selectedTR.find("td:last-child").find("i.input_html").find("input[data-path='" + fieldPath + "']").closest('span').removeClass('found_disable');
    } else {
        
        var $getField = $oneLevelRow.find("[data-path='" + fieldPath + "']");
        
        if ($getField.length) {
            
            if ($getField.prop('tagName') == 'SELECT') {
                
                if ($getField.hasClass('select2')) {
                    
                    /**
                    * DTL комбоны хамаарал ажиллахгүй байсан тул comment хийв.
                    *
                    *_oneLevelRow.find("div[data-s-path='"+fieldPath+"']").removeClass('select2-container-disabled');    
                    */
                    $oneLevelRow.find("div[data-s-path='"+fieldPath+"']").removeClass('select2-container-disabled');
                    $getField.select2('readonly', false).select2('enable');  
                
                } else {
                    $getField.removeAttr('style disabled');
                }
               
            } else {
                $getField.removeAttr('readonly disabled onkeydown').removeClass('disable-click');
                var $codeName = $getField.closest('div.meta-autocomplete-wrap');
                if ($codeName.length) {
                    $codeName.find("input[type='text']").removeAttr('readonly disabled tabindex');
                    $codeName.find('button').removeAttr('style').prop('disabled', false);
                } else if ($getField.hasClass('fileInit')) {
                    var $parent = $getField.closest('.uniform-uploader');
                    $parent.find('button').prop('disabled', false);
                    $parent.removeClass('disabled');
                }   
            }
        }
    }
    return;
}
function setBpRowButtonEnable(mainSelector, elem, fieldPath) {
    
    if (elem === 'open') {
        var $getPathElement = mainSelector.find("[data-path='" + fieldPath + "']");
        if ($getPathElement.length) {
            $getPathElement.prop('disabled', false);
        }
        return;    
    }    
    
    var $this = $(elem, mainSelector);
    var $oneLevelRow = $this.closest('.bp-detail-row'), $selectedTR = mainSelector.find('.bprocess-table-dtl > .tbody > .currentTarget');
    
    if ($oneLevelRow.find("[data-path='" + fieldPath + "']").length === 0) { 
        if ($selectedTR.find("[data-path='" + fieldPath + "']").length) {
            var $getField = $selectedTR.find("[data-path='" + fieldPath + "']");
            $getField.prop('disabled', false);
        } else {
            var $getField = mainSelector.find("[data-path='" + fieldPath + "']");
            $getField.prop('disabled', false);
        }
    } else {
        var $getField = $oneLevelRow.find("[data-path='" + fieldPath + "']");
        
        if ($getField.length) {
            $getField.prop('disabled', false);
        }
    }
    return;
}
function setBpHeaderFieldDisable(mainSelector, fieldPath) {
    mainSelector.find('[data-path="'+fieldPath+'"]').prop('readonly', true).attr('tabindex', '-1');
    return;
}
function setBpHeaderFieldEnable(mainSelector, fieldPath) {
    mainSelector.find('[data-path="'+fieldPath+'"]').removeAttr('readonly tabindex');
    return;
}
function setBpHeaderPopupDisable(mainSelector, fieldPath) {
    var $parent = mainSelector.find('input[data-path="'+fieldPath+'"]').closest('div.meta-autocomplete-wrap');
    $parent.find('input[type="text"]').attr({'readonly': 'readonly', 'tabindex': '-1'});
    $parent.find('button').attr('style', 'pointer-events: none').prop('disabled', true);
    return;
}
function setBpHeaderPopupEnable(mainSelector, fieldPath) {
    var $parent = mainSelector.find('input[data-path="'+fieldPath+'"]').closest('div.meta-autocomplete-wrap');
    $parent.find('input[type="text"]').removeAttr('readonly tabindex');
    $parent.find('button').removeAttr('style disabled');
    return;
}
function setBpHeaderFileFieldDisable(mainSelector, fieldPath) {
    var $field = mainSelector.find('[data-path="'+fieldPath+'"]');
    var $parent = $field.closest('.uniform-uploader');
    $field.prop('readonly', true).attr('tabindex', '-1');
    
    if ($parent.length) {
        $parent.find('.btn[onclick*="bpFileChoosedRemove"]').hide();
        $parent.addClass('disabled');
        $parent.find('button').prop('disabled', true).attr('tabindex', '-1');
    }
    
    return;
}
function setBpHeaderFileFieldEnable(mainSelector, fieldPath) {
    
    var $field = mainSelector.find('[data-path="'+fieldPath+'"]');
    var $parent = $field.closest('.uniform-uploader');
    $field.removeAttr('readonly tabindex');
    
    if ($parent.length) {
        $parent.find('.btn[onclick*="bpFileChoosedRemove"]').show();
        $parent.removeClass('disabled');
        $parent.find('button').removeAttr('disabled tabindex');
    }
    
    return;
}
function setBpHeaderParamDisable(mainSelector, fieldPath) {
    
    var $field = mainSelector.find("[data-path='"+fieldPath+"']");
    
    if ($field.hasClass('select2')) {
        mainSelector.find("[id='s2id_param["+fieldPath+"]']").addClass('select2-container-disabled');    
        $field.select2('readonly', true);
    } else if ($field.hasClass('combogridInit')) {
        var $parent = $field.closest('.input-group');
        var $display = $parent.find('.combo-grid-autocomplete');
        $display.prop('readonly', true).attr('tabindex', '-1');
        $parent.find('[onclick*="removeSelectableComboGrid"]').hide();
    } else {
        $field.attr('style', 'pointer-events: none; background-color: #eeeeee !important;');
    }
    return;
}
function setBpHeaderParamEnable(mainSelector, fieldPath) {
    var $field = mainSelector.find("[data-path='"+fieldPath+"']");
    
    if ($field.hasClass('select2')) {
        mainSelector.find("[id='s2id_param["+fieldPath+"]']").removeClass('select2-container-disabled');    
        $field.select2('readonly', false).select2('enable');
        
        if ($field.hasClass('bp-field-with-popup-combo')) {
            var $parent = $field.closest('.input-group');
            $parent.find('.btn').prop('disabled', false);
        }
    } else if ($field.hasClass('combogridInit')) {
        var $parent = $field.closest('.input-group');
        var $display = $parent.find('.combo-grid-autocomplete');
        $display.prop('readonly', false).removeAttr('tabindex');
        $parent.find('[onclick*="removeSelectableComboGrid"]').show();
    } else {
        $field.removeAttr('style');
    }
    $field.removeAttr('disabled readonly');
    
    return;
}
function setBpHeaderComboWithPopupDisable(mainSelector, fieldPath) {
    var $combo = mainSelector.find("select[data-path='"+fieldPath+"']");
    
    if ($combo.length) {
        mainSelector.find("[id='s2id_param["+fieldPath+"]']").addClass('select2-container-disabled');    
        $combo.select2('readonly', true);
        $combo.parent().find('button').prop('disabled', true);
    } 
    return;
}
function setBpHeaderComboWithPopupEnable(mainSelector, fieldPath) {
    var $combo = mainSelector.find("select[data-path='"+fieldPath+"']");
    
    if ($combo.length) {
        mainSelector.find("[id='s2id_param["+fieldPath+"]']").removeClass('select2-container-disabled');    
        $combo.select2('readonly', false).select2('enable');
        $combo.parent().find('button').prop('disabled', false);
    } 
    return;
}
function setBpRowParamDisable(mainSelector, elem, fieldPath) {
    var $selectPath = mainSelector.find("[data-path='" + fieldPath + "']");
    
    if (elem === 'open' && $selectPath.length) {
        if ($selectPath.prop('tagName') == 'SELECT') {
            if ($selectPath.hasClass('select2')) {                
                //$selectPath.select2('readonly', true);
                mainSelector.find("div[data-s-path='"+fieldPath+"']").addClass('select2-container-disabled');
            } else {
                $selectPath.attr('style', 'pointer-events: none; background-color: #eeeeee !important;');
            }
        } else {
            $selectPath.attr('readonly', 'readonly');
            var $codeName = $selectPath.closest("div.meta-autocomplete-wrap");
            
            if ($codeName.length) {
                $codeName.find("input[type='text']").attr({'readonly': 'readonly', 'tabindex': '-1'});
                $codeName.find("button").attr('style', 'pointer-events: none; background-color: #eeeeee !important').prop("disabled", true);
            } else if ($selectPath.hasClass('fileInit')) {
                var $parent = $selectPath.closest('.uniform-uploader');
                $parent.find('button').prop('disabled', true);
                $parent.addClass('disabled');
            }
        }
        return;    
    }
    
    var $this = $(elem, mainSelector), 
        $oneLevelRow = $this.closest('.bp-detail-row'), 
        $selectedTR = mainSelector.find('.bprocess-table-dtl > .tbody > .currentTarget');
    
    if ($oneLevelRow.find("[data-path='" + fieldPath + "']").length === 0) {
        if ($selectPath.hasClass('select2')) {                
            //$selectPath.select2('readonly', true);
            mainSelector.find("div[data-s-path='"+fieldPath+"']").addClass('select2-container-disabled');
        } else {
            $selectPath.prop('readonly', true);
            var $codeName = $selectPath.closest("div.meta-autocomplete-wrap");
            
            if ($codeName.length) {
                $codeName.find("input[type='text']").attr({'readonly': 'readonly', 'tabindex': '-1'});
            } else if ($selectPath.hasClass('fileInit')) {
                var $parent = $selectPath.closest('.uniform-uploader');
                $parent.find('button').prop('disabled', true);
                $parent.addClass('disabled');
            }      
        }
    } else if ($selectedTR.find("td:last-child").find("i.input_html").find("input[data-path='" + fieldPath + "']").length) {
        mainSelector.find("div.right-sidebar-content").find("input[data-path='" + fieldPath + "']").prop("readonly", true);
        $selectedTR.find("td:last-child").find("i.input_html").find("input[data-path='" + fieldPath + "']").closest('span').addClass('found_disable');
    } else {
        var $fieldPath = $oneLevelRow.find("[data-path='" + fieldPath + "']");
        if ($fieldPath.prop('tagName') == 'SELECT') {
            if ($fieldPath.hasClass('select2')) {
                $oneLevelRow.find("div[data-s-path='"+fieldPath+"']").addClass('select2-container-disabled');    
                //$oneLevelRow.find("select[data-path='" + fieldPath + "']").select2('readonly', true);
            } else {
                $fieldPath.attr('style', 'pointer-events: none; background-color: #eeeeee !important;');
            }
        } else {
            $fieldPath.attr("readonly", "readonly");   
            var $codeName = $fieldPath.closest("div.meta-autocomplete-wrap");
            
            if ($codeName.length) {
                $codeName.find("input[type='text']").attr({'readonly': 'readonly', 'tabindex': '-1'});
            } else if ($fieldPath.hasClass('fileInit')) {
                var $parent = $fieldPath.closest('.uniform-uploader');
                $parent.find('button').prop('disabled', true);
                $parent.addClass('disabled');
            }            
        }
    }
    
    return; 
}
function setBpRowCheckboxDisable(mainSelector, elem, fieldPath) {
    
    if (elem === 'open') {
        var $elements = mainSelector.find("[data-path='" + fieldPath + "']");
        if ($elements.length > 0) {
            $elements.attr({'data-isdisabled': 'true', style: "cursor: not-allowed", 'tabindex': '-1'});
            $elements.closest('.checker').addClass('disabled');
        }
        return;
    }
    
    var $this = $(elem);
    
    if ($this.hasClass('bp-detail-row')) {
        var $elements = $this.find("[data-path='" + fieldPath + "']");
    } else {
        var $elements = $this.closest('.bp-detail-row').find("[data-path='" + fieldPath + "']");
    }
    
    if ($elements.length == 0) {
        var $elements = mainSelector.find("[data-path='" + fieldPath + "']");
    }
    
    if ($elements.length) {
        $elements.attr({'data-isdisabled': 'true', style: 'cursor: not-allowed', 'tabindex': '-1'});
        $elements.closest('.checker').addClass('disabled');
    }
    return;
}
function setBpRowCheckboxEnable(mainSelector, elem, fieldPath) {
    
    if (elem === 'open') {
        var $elements = mainSelector.find("[data-path='" + fieldPath + "']");
        if ($elements.length > 0) {
            $elements.removeAttr('onclick style data-isdisabled tabindex');
            $elements.closest('.checker').removeClass('disabled');
            $.uniform.update($elements);
        }
        return;
    }
    
    var $this = $(elem);
    
    if ($this.hasClass('bp-detail-row')) {
        var $elements = $this.find("[data-path='" + fieldPath + "']");
    } else {
        var $elements = $this.closest('.bp-detail-row').find("[data-path='" + fieldPath + "']");
    }
    
    if ($elements.length == 0) {
        var $elements = mainSelector.find("[data-path='" + fieldPath + "']");
    }
    
    if ($elements.length) {
        $elements.removeAttr('onclick style data-isdisabled tabindex');
        $elements.closest('.checker').removeClass('disabled');
        $.uniform.update($elements);
    }
    return;
}
function setBpHeaderRadioDisable(mainSelector, fieldPath) {
    var $elements = mainSelector.find("[data-path='" + fieldPath + "']");
    if ($elements.length) {
        $elements.attr({'data-isdisabled': 'true', style: 'cursor: not-allowed', 'tabindex': '-1'});
        $elements.closest('.radio').addClass('disabled');
    }
    return;
}
function setBpHeaderRadioEnable(mainSelector, fieldPath) {
    var $elements = mainSelector.find("[data-path='" + fieldPath + "']");
    if ($elements.length) {
        $elements.removeAttr('onclick style data-isdisabled tabindex');
        $elements.closest('.radio').removeClass('disabled');
        $.uniform.update($elements);
    }
    return;
}
function setBpRowRadioDisable(mainSelector, elem, fieldPath) {
    if (elem === 'open') {
        var $elements = mainSelector.find("[data-path='" + fieldPath + "']");
        if ($elements.length) {
            $elements.attr({'data-isdisabled': 'true', style: "cursor: not-allowed", 'tabindex': '-1'});
            $elements.closest('.radio').addClass('disabled');
        }
        return;
    }
    
    var $this = $(elem);
    
    if ($this.hasClass('bp-detail-row')) {
        var $elements = $this.find("[data-path='" + fieldPath + "']");
    } else {
        var $elements = $this.closest('.bp-detail-row').find("[data-path='" + fieldPath + "']");
    }
    
    if ($elements.length == 0) {
        var $elements = mainSelector.find("[data-path='" + fieldPath + "']");
    }
    
    if ($elements.length) {
        $elements.attr({'data-isdisabled': 'true', style: 'cursor: not-allowed', 'tabindex': '-1'});
        $elements.closest('.radio').addClass('disabled');
    }
    return;
}
function setBpRowRadioEnable(mainSelector, elem, fieldPath) {
    
    if (elem === 'open') {
        var $elements = mainSelector.find("[data-path='" + fieldPath + "']");
        if ($elements.length) {
            $elements.removeAttr('onclick style data-isdisabled tabindex');
            $elements.closest('.radio').removeClass('disabled');
            $.uniform.update($elements);
        }
        return;
    }
    
    var $this = $(elem);
    
    if ($this.hasClass('bp-detail-row')) {
        var $elements = $this.find("[data-path='" + fieldPath + "']");
    } else {
        var $elements = $this.closest('.bp-detail-row').find("[data-path='" + fieldPath + "']");
    }
    
    if ($elements.length == 0) {
        var $elements = mainSelector.find("[data-path='" + fieldPath + "']");
    }
    
    if ($elements.length) {
        $elements.removeAttr('onclick style data-isdisabled tabindex');
        $elements.closest('.radio').removeClass('disabled');
        $.uniform.update($elements);
    }
    return;
}
function setBpRowParamShow(mainSelector, elem, fieldPath) {
    if (elem == 'open') {
        mainSelector.find("[data-path='" + fieldPath + "'], tr[data-cell-path='" + fieldPath + "'], th[data-cell-path='" + fieldPath + "'], td[data-cell-path='" + fieldPath + "'], div[data-cell-path='" + fieldPath + "'], label[data-label-path='" + fieldPath + "'], div[data-section-path='" + fieldPath + "'], li[data-li-path='" + fieldPath + "'], [data-b-path='" + fieldPath + "']").css({'display': ''});
    } else {
        var _this = $(elem, mainSelector);
        
        if (_this.hasClass('bp-detail-row')) {
            var _oneLevelRow = _this;
        } else {
            var _oneLevelRow = _this.closest('.bp-detail-row');
        }
    
        if (_this.closest("table").parent().hasClass("bp-header-param") || typeof $(elem).context === 'undefined')
            var selectedTR = mainSelector.find('.bprocess-table-dtl .tbody').find('.added-bp-row');
        else
            var selectedTR = mainSelector.find('.bprocess-table-dtl > .tbody > .currentTarget');

        if (_oneLevelRow.find("[data-li-path='" + fieldPath + "']").length) {
            mainSelector.find("thead > tr > th[data-cell-path='" + fieldPath + "'], tfoot > tr > td[data-cell-path='" + fieldPath + "']").removeClass('hide').css({'display': ''});
            _oneLevelRow.find("li[data-li-path='" + fieldPath + "']").closest('td').removeClass('hide').css({'display': ''});
            _oneLevelRow.find("li[data-li-path='" + fieldPath + "']").css({'display': ''});
        } else if (_oneLevelRow.find("[data-path='" + fieldPath + "']").length === 0 && selectedTR.find("td:last-child").find("i.input_html").find("[data-path='" + fieldPath + "']").length === 0) {
            mainSelector.find("[data-path='" + fieldPath + "'], tr[data-cell-path='" + fieldPath + "'], th[data-cell-path='" + fieldPath + "'], td[data-cell-path='" + fieldPath + "'], div[data-cell-path='" + fieldPath + "'], label[data-label-path='" + fieldPath + "'], div[data-section-path='" + fieldPath + "'], li[data-li-path='" + fieldPath + "']").css({'display': ''});
        } else if(selectedTR.find("td:last-child").find("i.input_html").find("[data-path='" + fieldPath + "']").length > 0) {
            mainSelector.find("[data-path='" + fieldPath + "'], label[data-label-path='" + fieldPath + "']").css({'display': ''});
            mainSelector.find("div.right-sidebar-content").find("[data-path='" + fieldPath + "']").closest("tr").css({'display': ''});
            selectedTR.find("td:last-child").find("i.input_html").find("[data-path='" + fieldPath + "']").closest('span').removeClass('found_hide');
        } else {
            _oneLevelRow.find("[data-path='" + fieldPath + "'], tr[data-cell-path='" + fieldPath + "'], th[data-cell-path='" + fieldPath + "'], td[data-cell-path='" + fieldPath + "'], div[data-cell-path='" + fieldPath + "'], label[data-label-path='" + fieldPath + "'], div[data-section-path='" + fieldPath + "'], li[data-li-path='" + fieldPath + "'], [data-b-path='" + fieldPath + "']").css({'display': ''});
            mainSelector.find("thead > tr > th[data-cell-path='" + fieldPath + "'], tfoot > tr > td[data-cell-path='" + fieldPath + "'], tr[data-cell-path='" + fieldPath + "']").css({'display': ''});
        }
    }
    
    return;
}
function setBpRowParamHide(mainSelector, elem, fieldPath) {
    var _this = $(elem, mainSelector);
    var _oneLevelRow = _this.closest(".bp-detail-row");
    
    if (_this.closest("table").parent().hasClass("bp-header-param") || typeof $(elem).context === 'undefined')
        var selectedTR = mainSelector.find('.bprocess-table-dtl .tbody').find('.added-bp-row');        
    else
        var selectedTR = mainSelector.find('.bprocess-table-dtl > .tbody > .currentTarget');
    
    if (_oneLevelRow.find("[data-path='" + fieldPath + "']").length === 0 && selectedTR.find("td:last-child").find("i.input_html").find("[data-path='" + fieldPath + "']").length === 0) {
        if (!mainSelector.find("div[data-section-path='" + fieldPath + "']").hasClass("hide")) {
            mainSelector.find("div[data-section-path='" + fieldPath + "']").css({'display': 'none'});
        }
        mainSelector.find("[data-path='" + fieldPath + "'], div[data-cell-path='" + fieldPath + "'], th[data-cell-path='" + fieldPath + "'], th[data-row-path='" + fieldPath + "'], tr[data-cell-path='" + fieldPath + "'], td[data-cell-path='" + fieldPath + "'], td[data-row-path='" + fieldPath + "'], label[data-label-path='" + fieldPath + "'], li[data-li-path='" + fieldPath + "']").css({'display': 'none'});
        
        if (_oneLevelRow.find("li[data-li-path='" + fieldPath + "']").length === 0) {
            var $tdButton = mainSelector.find("li[data-li-path='" + fieldPath + "']").closest('td');
            if ($tdButton.find("div.param-tree-container").length) {
                var ul = $tdButton.find("div.param-tree-container").find('ul:eq(0)');
                var isGroupsHide = true;
                ul.each(function(){
                    $(this).find('li').each(function(){
                        var displayStyle = $(this).attr('style');
                        if (displayStyle != 'display: none;') {
                            isGroupsHide = false;
                        }
                    });
                });
                if (isGroupsHide) {
                    var $table = $tdButton.closest("table");
                    var $cellPath = $tdButton.attr('data-cell-path');
                    $table.find("th[data-cell-path='"+$cellPath+"'], td[data-cell-path='"+$cellPath+"']").css({'display': 'none'});
                }            
            }
        } else {
            var $tdButton = _oneLevelRow.find("li[data-li-path='" + fieldPath + "']").closest("td");
            if ($tdButton.find("div.param-tree-container").length) {
                var $ul = $tdButton.find('> div.param-tree-container').find('ul:eq(0) > li');
                var isGroupsHide = true;
                $ul.each(function(){
                    var displayStyle = $(this).attr('style');
                    if (displayStyle != 'display: none;') {
                        isGroupsHide = false;
                    }
                });
                    
                if (isGroupsHide) {
                    var $table = $tdButton.closest("table");
                    var $cellPath = $tdButton.attr('data-cell-path');
                    $table.find("th[data-cell-path='"+$cellPath+"'], td[data-cell-path='"+$cellPath+"'], div[data-cell-path='"+$cellPath+"']").css({'display': 'none'});
                } else {
                    _oneLevelRow.find("li[data-li-path='" + fieldPath + "']").css({'display': 'none'});
                    $tdButton.find('> div.param-tree-container').find('ul:eq(0) > li:not([style="display: none;"]):eq(0)').addClass('active');
                }                
            }
        }
    } else if (selectedTR.find("td:last-child").find("i.input_html").find("[data-path='" + fieldPath + "']").length > 0) {
        mainSelector.find("div.right-sidebar-content").find("[data-path='" + fieldPath + "']").closest("tr").css({'display': 'none'});
        selectedTR.find("td:last-child").find("i.input_html").find("[data-path='" + fieldPath + "']").closest('span').addClass('found_hide');
        mainSelector.find("thead > tr > th[data-cell-path='" + fieldPath + "'], tfoot > tr > td[data-cell-path='" + fieldPath + "'], tr[data-cell-path='" + fieldPath + "']").css({'display': 'none'});
    } else {
        if (!mainSelector.find("div[data-section-path='" + fieldPath + "']").hasClass("hide")) {
            _oneLevelRow.find("div[data-section-path='" + fieldPath + "']").css({'display': 'none'});
        }
        _oneLevelRow.find("[data-path='" + fieldPath + "'], td[data-cell-path='" + fieldPath + "'], label[data-label-path='" + fieldPath + "'], li[data-li-path='" + fieldPath + "'], tr[data-cell-path='" + fieldPath + "'], div[data-cell-path='" + fieldPath + "']").css({'display': 'none'});
        mainSelector.find("thead > tr > th[data-cell-path='" + fieldPath + "'], tfoot > tr > td[data-cell-path='" + fieldPath + "'], tr[data-cell-path='" + fieldPath + "']").css({'display': 'none'});
    }
    
    return;
}
function setBpRowParamSoftHide(mainSelector, elem, fieldPath) {
    
    if (elem == 'open') {
        mainSelector.find("[data-path='" + fieldPath + "'], label[data-label-path='" + fieldPath + "'], [data-b-path='" + fieldPath + "']").css('display', 'none');
    } else {
        var _this = $(elem, mainSelector);
        
        if (_this.hasClass('bp-detail-row')) {
            var _oneLevelRow = _this;
        } else {
            var _oneLevelRow = _this.closest('.bp-detail-row');
        }

        if (_this.closest("table").parent().hasClass("bp-header-param") || typeof $(elem).context === 'undefined')
            var selectedTR = mainSelector.find('.bprocess-table-dtl .tbody').find('.added-bp-row');        
        else
            var selectedTR = mainSelector.find('.bprocess-table-dtl > .tbody > .currentTarget');

        if (_oneLevelRow.find("[data-path='" + fieldPath + "']").length === 0 && selectedTR.find("td:last-child").find("i.input_html").find("[data-path='" + fieldPath + "']").length === 0) {
            if (!mainSelector.find("div[data-section-path='" + fieldPath + "']").hasClass("hide")) {
                mainSelector.find("div[data-section-path='" + fieldPath + "']").css({'display': 'none'});
            }
            mainSelector.find("[data-path='" + fieldPath + "'], label[data-label-path='" + fieldPath + "']").css({'display': 'none'});

            if (_oneLevelRow.find("li[data-li-path='" + fieldPath + "']").length === 0) {
                var $tdButton = mainSelector.find("li[data-li-path='" + fieldPath + "']").closest('td');
                if ($tdButton.find("div.param-tree-container").length) {
                    var ul = $tdButton.find("div.param-tree-container").find('ul:eq(0)');
                    var isGroupsHide = true;
                    ul.each(function(){
                        $(this).find('li').each(function(){
                            var displayStyle = $(this).attr('style');
                            if (displayStyle != 'display: none;') {
                                isGroupsHide = false;
                            }
                        });
                    });
                    if (isGroupsHide) {
                        var $table = $tdButton.closest("table");
                        var $cellPath = $tdButton.attr('data-cell-path');
                        $table.find("[data-path='"+$cellPath+"'], label[data-label-path='" + fieldPath + "']").css({'display': 'none'});
                    }            
                }
            } else {
                var $tdButton = _oneLevelRow.find("li[data-li-path='" + fieldPath + "']").closest("td");
                if ($tdButton.find("div.param-tree-container").length) {
                    var $ul = $tdButton.find('> div.param-tree-container').find('ul:eq(0) > li');
                    var isGroupsHide = true;
                    $ul.each(function(){
                        var displayStyle = $(this).attr('style');
                        if (displayStyle != 'display: none;') {
                            isGroupsHide = false;
                        }
                    });

                    if (isGroupsHide) {
                        var $table = $tdButton.closest("table");
                        var $cellPath = $tdButton.attr('data-cell-path');
                        $table.find("[data-path='"+$cellPath+"'], label[data-label-path='" + fieldPath + "']").css({'display': 'none'});
                    } else {
                        _oneLevelRow.find("li[data-li-path='" + fieldPath + "']").css({'display': 'none'});
                        $tdButton.find('> div.param-tree-container').find('ul:eq(0) > li:not([style="display: none;"]):eq(0)').addClass('active');
                    }                
                }
            }
        } else if (selectedTR.find("td:last-child").find("i.input_html").find("[data-path='" + fieldPath + "']").length > 0) {
            mainSelector.find("div.right-sidebar-content").find("[data-path='" + fieldPath + "']").closest("tr").css({'display': 'none'});
            selectedTR.find("td:last-child").find("i.input_html").find("[data-path='" + fieldPath + "']").closest('span').addClass('found_hide');
            mainSelector.find("[data-path='" + fieldPath + "']").css({'display': 'none'});
        } else {
            if (!mainSelector.find("div[data-section-path='" + fieldPath + "']").hasClass("hide")) {
                _oneLevelRow.find("[data-path='" + fieldPath + "']").css('display', 'none');
            }

            _oneLevelRow.find("[data-path='" + fieldPath + "'], label[data-label-path='" + fieldPath + "'], [data-b-path='" + fieldPath + "']").css('display', 'none');
        }
    }
    
    return;
}
function setBpRowGroupDisable(mainSelector, elem, fieldPath) {
    var $thisGroup = $("div[data-section-path='"+fieldPath+"']", mainSelector); 
    $thisGroup.find('input[type=text]:not(th > input), textarea, select:not(.select2)').attr({'readonly': 'readonly', 'tabindex': '-1'});
    $thisGroup.find('select.select2').select2('readonly', true);
    $thisGroup.find('button').attr('disabled', 'disabled');
    return;
}
function setBpRowGroupEnable(mainSelector, elem, fieldPath) {
    var $thisGroup = $("div[data-section-path='"+fieldPath+"']", mainSelector); 
    $thisGroup.find('input[type=text]:not(th > input), textarea, select:not(.select2)').removeAttr('readonly tabindex');
    $thisGroup.find('select.select2').select2('readonly', false);
    $thisGroup.find('button').removeAttr('disabled');
    return;
}
function setBpRowParamRemoveStyle(mainSelector, elem, fieldPath) {
    var $this = $(elem, mainSelector);
    var $oneLevelRow = $this.closest(".bp-detail-row");
    if ($oneLevelRow.find("[data-path='" + fieldPath + "']").length === 0) {
        mainSelector.find("[data-path='" + fieldPath + "']").removeAttr("style");
    } else {
        $oneLevelRow.find("[data-path='" + fieldPath + "']").removeAttr("style");
    }
    return;
}
function setBpRowParamStyle(mainSelector, elem, fieldPath, styles) {
    
    var $this = $(elem, mainSelector);
    var $oneLevelRow = $this.closest('.bp-detail-row');
    
    if ($oneLevelRow.length && $oneLevelRow.find("[data-path='" + fieldPath + "']").length) {
        $oneLevelRow.find("[data-path='" + fieldPath + "']").attr('style', styles);
    } else {
        mainSelector.find("[data-path='" + fieldPath + "']").attr('style', styles);
    }
    return;
}
function setBpRowPopupParamStyle(mainSelector, elem, fieldPath, styles) {
    var $this = $(elem, mainSelector);
    var $oneLevelRow = $this.closest('.bp-detail-row');
    if ($oneLevelRow.find("input[data-path='" + fieldPath + "']").length) {
        $oneLevelRow.find("div.meta-autocomplete-wrap[data-section-path='" + fieldPath + "']").find("input.meta-autocomplete, input.meta-name-autocomplete").attr('style', styles);
    } else {
        mainSelector.find("div.meta-autocomplete-wrap[data-section-path='" + fieldPath + "']").find("input.meta-autocomplete, input.meta-name-autocomplete").attr('style', styles);
    }
    return;
}
function setBpRowPopupParamRemoveStyle(mainSelector, elem, fieldPath) {
    var $this = $(elem, mainSelector);
    var $oneLevelRow = $this.closest('.bp-detail-row');
    if ($oneLevelRow.find("input[data-path='" + fieldPath + "']").length) {
        $oneLevelRow.find("div.meta-autocomplete-wrap[data-section-path='" + fieldPath + "']").find("input.meta-autocomplete, input.meta-name-autocomplete").removeAttr('style');
    } else {
        mainSelector.find("div.meta-autocomplete-wrap[data-section-path='" + fieldPath + "']").find("input.meta-autocomplete, input.meta-name-autocomplete").removeAttr('style');
    }
    return;
}
function setBpRowParamLabelRemoveStyle(mainSelector, elem, fieldPath) {
    var $this = $(elem, mainSelector);
    var $oneLevelRow = $this.closest(".bp-detail-row");
    if ($oneLevelRow.find("label[data-label-path='" + fieldPath + "']").length === 0) {
        mainSelector.find("label[data-label-path='" + fieldPath + "']").removeAttr("style");
    } else {
        $oneLevelRow.find("label[data-label-path='" + fieldPath + "']").removeAttr("style");
    }
    return;
}
function setBpRowParamLabelStyle(mainSelector, elem, fieldPath, styles) {
    var $this = $(elem, mainSelector);
    var $oneLevelRow = $this.closest(".bp-detail-row");
    if ($oneLevelRow.find("label[data-label-path='" + fieldPath + "']").length === 0) {
        mainSelector.find("label[data-label-path='" + fieldPath + "']").attr("style", styles);
    } else {
        $oneLevelRow.find("label[data-label-path='" + fieldPath + "']").attr("style", styles);
    }
    return;
}
function setBpGroupRemoveStyle(mainSelector, elem, fieldPath) {
    mainSelector.find("[data-section-path='" + fieldPath + "'] > fieldset > legend").removeAttr('style');
    return;
}
function setBpGroupStyle(mainSelector, elem, fieldPath, styles) {
    mainSelector.find("[data-section-path='" + fieldPath + "'] > fieldset > legend").attr('style', styles);
    return;
}
function setBpRowParamRequired(mainSelector, elem, fieldPath) {
    var $this = $(elem, mainSelector), $oneLevelRow = $this.closest(".bp-detail-row");
    
    if ($oneLevelRow.find("[data-path='" + fieldPath + "']").length === 0) {
        mainSelector.find("[data-path='" + fieldPath + "']").attr('required', 'required');  
        
        if (mainSelector.find("label[data-label-path='" + fieldPath + "']").find("span.required").length === 0) {
            mainSelector.find("label[data-label-path='" + fieldPath + "']").prepend('<span class="required">*</span>');        
        }
        
    } else {
        
        $oneLevelRow.find("[data-path='" + fieldPath + "']").attr('required', 'required');  
        
        if ($oneLevelRow.find("label[data-label-path='" + fieldPath + "']").find("span.required").length === 0) {
            $oneLevelRow.find("label[data-label-path='" + fieldPath + "']").prepend('<span class="required">*</span>');
        }
    }    
    return;
}
function setBpHeaderParamRequired(mainSelector, elem, fieldPath) {
    var $getPathElement = mainSelector.find("[data-path='"+fieldPath+"']:not(.lookup-hard-autocomplete)");
    if ($getPathElement.length) {
        
        if (mainSelector.find("label[data-label-path='"+fieldPath+"']").find("span.required").length === 0) {
            mainSelector.find("label[data-label-path='"+fieldPath+"']").prepend("<span class='required'>*</span>"); 
        }
        
        $getPathElement.attr('required', 'required');
        
        if ($getPathElement.prop('tagName') == 'SELECT') {
            
            if ($getPathElement.hasClass('select2') && $getPathElement.prev('.select2-container').hasClass('select2-container-disabled')) {
                $getPathElement.attr('required', 'required').select2();
                $getPathElement.select2('readonly', true);
            }                     
            
        } else if ($getPathElement.hasClass('popupInit')) {
            $getPathElement.closest('.double-between-input').find('input[type=text]').attr('required', 'required');
        }
    }
    return;
}
function bpSetHeaderParamRequired(mainSelector, fieldPath) {
    var $getPathElement = mainSelector.find("[data-path='"+fieldPath+"']:not(.lookup-hard-autocomplete)");
    if ($getPathElement.length) {
        
        var tagName = $getPathElement.prop('tagName');
        
        if (mainSelector.find("label[data-label-path='"+fieldPath+"']").find("span.required").length === 0) {
            mainSelector.find("label[data-label-path='"+fieldPath+"']").prepend("<span class='required'>*</span>"); 
        }
        
        if (tagName == 'INPUT' && $getPathElement.attr('type') == 'file' 
            && $getPathElement.next('input[type="hidden"]').length 
            && $getPathElement.next('input[type="hidden"]').val() != '') {
            $getPathElement.removeAttr('required');
            return;
        }
        
        $getPathElement.attr('required', 'required');
        
        if ($getPathElement.prop('tagName') == 'SELECT') {
            
            if ($getPathElement.hasClass('select2') && $getPathElement.prev('.select2-container').hasClass('select2-container-disabled')) {
                $getPathElement.attr('required', 'required').select2();
                $getPathElement.select2('readonly', true);
            }
            
        } else if ($getPathElement.hasClass('popupInit')) {
            $getPathElement.closest('.double-between-input').find('input[type=text]').attr('required', 'required');
        }
    }
    return;
}
function setBpHeaderParamNonRequired(mainSelector, elem, fieldPath) {
    var $getPathElement = mainSelector.find("[data-path='"+fieldPath+"']:not(.lookup-hard-autocomplete)");
    
    if ($getPathElement.length) {
        
        $getPathElement.removeAttr('required').removeClass('error');
        mainSelector.find("label[data-label-path='"+fieldPath+"']").find('span').remove();
        
        if ($getPathElement.hasClass('popupInit')) {
            $getPathElement.closest('.double-between-input').find('input[type=text]').removeAttr('required').removeClass('error');
        }
    }
    return;
}
function bpSetHeaderParamNonRequired(mainSelector, fieldPath) {
    var $getPathElement = mainSelector.find("[data-path='"+fieldPath+"']:not(.lookup-hard-autocomplete)");
    
    if ($getPathElement.length) {
        
        $getPathElement.removeAttr('required').removeClass('error');
        mainSelector.find("label[data-label-path='"+fieldPath+"']").find('span').remove();
        
        if ($getPathElement.hasClass('popupInit')) {
            $getPathElement.closest('.double-between-input').find('input[type=text]').removeAttr('required').removeClass('error');
        }
    }
    return;
}
function setBpRowParamNonRequired(mainSelector, elem, fieldPath) {
    if (elem === 'open') {
        
        mainSelector.find("[data-path='" + fieldPath + "']").removeAttr('required').removeClass("error");
        mainSelector.find("label[data-label-path='" + fieldPath + "']").find("span.required").remove();
        
        return;    
    }
    
    var $this = $(elem, mainSelector), $oneLevelRow = $this.closest('.bp-detail-row');
    
    if ($oneLevelRow.find("input[data-path='" + fieldPath + "']").length === 0 && $oneLevelRow.find("select[data-path='" + fieldPath + "']").length === 0) {
        mainSelector.find("[data-path='" + fieldPath + "']").removeAttr("required").removeClass("error");
        mainSelector.find("label[data-label-path='" + fieldPath + "']").find("span.required").remove();
    } else {
        var $getField = $oneLevelRow.find("[data-path='" + fieldPath + "']");
        $oneLevelRow.find("label[data-label-path='" + fieldPath + "']").find("span.required").remove();
        
        if ($getField.prop('tagName') == 'SELECT') {
            $getField.removeAttr('required aria-required').removeClass('error');
            $oneLevelRow.find("div[data-s-path='"+fieldPath+"']").removeClass('error').removeAttr('data-readonly');
        } else {
            $getField.removeAttr('required aria-required');
        }
    }
    
    return;
}
function setBpHeaderParamEmpty(mainSelector, elem, fieldPath) {
    var $getPathElement = $("[data-path='"+fieldPath+"']", mainSelector);
    if ($getPathElement.length > 0) {
        $("label[data-label-path='"+fieldPath+"'], div[data-section-path='"+fieldPath+"']", mainSelector).css({'display': 'none'});
    }
    return;
}
function checkboxCheckerUpdate(elem, value) {
    if (typeof elem === 'undefined' || !elem || !pfFullExpSetFieldValue) {
        return;
    } 
    
    if (value == 'false' || value === false 
        || value == '' || value === null 
        || value == 'null' || value == '0') {
        elem.prop('checked', false);
    } else {
        elem.removeAttr('onclick style');
        elem.prop('checked', true);
    }
    $.uniform.update(elem);
    return;
}
function setIconInput(elem, value) {
    if (typeof elem === 'undefined' || !elem) {
        return;
    } 
    elem.parent().find('> li').each(function(){
        var $this = $(this);
        if ($this.data('id') == value) {
            elem.val(value);
            $this.addClass('active');
            return;
        }
    });

    return;
}
function checkboxEnableUpdate(elem) {
    elem.removeAttr('onclick style data-isdisabled tabindex');
    elem.closest('.checker').removeClass('disabled');
    $.uniform.update(elem);
    return;
}
function checkboxDisableUpdate(elem) {
    elem.attr({'data-isdisabled': 'true', style: 'cursor: not-allowed', 'tabindex': '-1'});
    elem.closest('.checker').addClass('disabled');
    return;
}
function radioButtonCheckerUpdate(elem, value) {
    if (typeof elem === 'undefined' || !elem || !pfFullExpSetFieldValue) {
        return;
    } 
    
    if (value == '' || value == null) {
        var $parent = elem.closest('.radio-list');
        $parent.find('input:checked').each(function() {
            $(this).prop('checked', false);
        });
    } else {
        elem.filter("[value='"+value+"']").prop('checked', true);
    }
    
    $.uniform.update(elem);
    return;
}
function getBpHdrRadioParam(mainSelector, path) {
    var selectedVal = '';
    var $selected = mainSelector.find("input[data-path='"+path+"']:checked");
    if ($selected.length > 0) {
        selectedVal = $selected.val();
    }
    return selectedVal;
}
function getBpHdrRangeSliderParam(mainSelector, path) {
    var selectedVal = '';
    var $path = mainSelector.find("input[data-path='"+path+"']");
    if ($path.length) {
        selectedVal = $path.val();
        if (selectedVal == '-0') {
            selectedVal = '';
        }
    }
    return selectedVal;
}
function getBpHdrMultiCheckboxParam(mainSelector, elem, fieldPath) {
    var selectedVal = '';
    if (elem !== 'open') {
        var $elem = $(elem);
        if ($elem.is(':checked')) {
            selectedVal = $elem.val();
        } else {
            selectedVal = '';
        }
    } else {
        selectedVal = $('input[data-path="'+fieldPath+'"]', mainSelector).val();
    }
    return selectedVal;
}
function getBpBigDecimalFieldSum(fieldPath, elem, mainSelector) {
    
    if (elem !== 'open') {
        
        var $elem = $(elem);
        
        if (typeof $elem.prop('tagName') !== 'undefined' && $elem.prop('tagName') == 'TR') {
            var $field = $elem.find("input[data-path='"+fieldPath+"_bigdecimal']:not([data-not-aggregate]):eq(0)");
            var $parent = $field.closest('tbody');
        } else if (typeof $elem.prop('tagName') !== 'undefined' && $elem.prop('tagName') == 'A') {

            var $selectedTR = mainSelector.find('table.bprocess-table-dtl > tbody > tr.currentTarget');
            var $sumField = $selectedTR.find("input[data-path='"+fieldPath+"_bigdecimal']:not([data-not-aggregate]):eq(0)");

            if ($sumField.length) {
                return setNumberToFixed($sumField.closest('tbody').find("input[data-path='"+fieldPath+"_bigdecimal']:not([data-not-aggregate])").sum());
            } else {
                return 0;
            }
            
        } else {
            var $parent = $elem.closest('tbody');
        }
        
    } else if (elem == 'open') {
        var $parent = mainSelector;
    } else {
        var $parent = elem.closest('tbody');
    }
    
    if (typeof $parent['prevObject'] !== 'undefined' && fieldPath.indexOf('.') !== -1) {
        var period = fieldPath.lastIndexOf('.');
        var groupPath = fieldPath.substring(0, period);
        
        if (mainSelector.find('table[data-table-path="'+groupPath+'"]:visible:last').length) {
            var $parent = mainSelector.find('table[data-table-path="'+groupPath+'"]:visible:last');
        }
    }
    
    return setNumberToFixed($parent.find("input[data-path='"+fieldPath+"_bigdecimal']:not([data-not-aggregate])").sum());
}
function getBpViewFieldSum(fieldPath, elem, mainSelector) {
    
    if (elem !== 'open') {
        
        var $elem = $(elem);
        
        if (typeof $elem.prop('tagName') !== 'undefined' && $elem.prop('tagName') == 'TR') {
            var $field = $elem.find("input[data-path='"+fieldPath+"_bigdecimal']:not([data-not-aggregate]):eq(0)");
            var $parent = $field.closest('tbody');
        } else if (typeof $elem.prop('tagName') !== 'undefined' && $elem.prop('tagName') == 'A') {

            var $selectedTR = mainSelector.find('table.bprocess-table-dtl > tbody > tr.currentTarget');
            var $sumField = $selectedTR.find("input[data-path='"+fieldPath+"_bigdecimal']:not([data-not-aggregate]):eq(0)");

            if ($sumField.length) {
                return setNumberToFixed($sumField.closest('tbody').find("input[data-path='"+fieldPath+"_bigdecimal']:not([data-not-aggregate])").sum());
            } else {
                return 0;
            }
            
        } else {
            var $parent = $elem.closest('tbody');
        }
        
    } else if (elem == 'open') {
        var $parent = mainSelector;
    } else {
        var $parent = elem.closest('tbody');
    }
    
    if (typeof $parent['prevObject'] !== 'undefined' && fieldPath.indexOf('.') !== -1) {
        var period = fieldPath.lastIndexOf('.');
        var groupPath = fieldPath.substring(0, period);
        
        if (mainSelector.find('table[data-table-path="'+groupPath+'"]:visible:last').length) {
            var $parent = mainSelector.find('table[data-table-path="'+groupPath+'"]:visible:last');
        }
    }
    
    return setNumberToFixed($parent.find("[data-view-path='"+fieldPath+"']:not([data-not-aggregate])").sum());
}
function getBpIntegerFieldSum(fieldPath, elem, mainSelector) {
    
    if (elem !== 'open' && typeof elem.prop('tagName') !== 'undefined' && elem.prop('tagName') == 'TR') {
        var $field = elem.find("input[data-path='"+fieldPath+"']:not([data-not-aggregate]):eq(0)");
        var $parent = $field.closest('tbody');
    } else if (elem !== 'open' && typeof elem.prop('tagName') !== 'undefined' && elem.prop('tagName') == 'A') {
        
        var $selectedTR = mainSelector.find('table.bprocess-table-dtl > tbody > tr.currentTarget');
        var $sumField = $selectedTR.find("input[data-path='"+fieldPath+"']:not([data-not-aggregate]):eq(0)");
        
        if ($sumField.length) {
            return setNumberToFixed($sumField.closest('tbody').find("input[data-path='"+fieldPath+"']:not([data-not-aggregate])").sum());
        } else {
            return 0;
        }

    } else if (elem === 'open') {
        var $parent = mainSelector;
    } else {
        var $parent = elem.closest('tbody');
    }
    
    if (typeof $parent['prevObject'] !== 'undefined' && fieldPath.indexOf('.') !== -1) {
        var period = fieldPath.lastIndexOf('.');
        var groupPath = fieldPath.substring(0, period);
        
        if (mainSelector.find('table[data-table-path="'+groupPath+'"]:visible:last').length) {
            var $parent = mainSelector.find('table[data-table-path="'+groupPath+'"]:visible:last');
        }
    }
    
    return setNumberToFixed($parent.find("input[data-path='"+fieldPath+"']:not([data-not-aggregate])").sum());
}
function bpGetPagerDetailSum(mainSelector, elem, fieldPath) {
    var fieldPathArr = fieldPath.split('.');
    var groupPath = fieldPathArr[0];
    var $table = mainSelector.find('[data-table-path="'+groupPath+'"]');
    
    if ($table.length) {
        if ($table.hasAttr('data-pager')) {
            var aggrObj = qryStrToObj($table.attr('data-pager-aggregate'));
            if (aggrObj && aggrObj.hasOwnProperty(fieldPath)) {
                var sumAmount = setNumberToFixed(aggrObj[fieldPath]);
                if (sumAmount == 0) {
                    return setNumberToFixed($table.find("input[data-path='"+fieldPath+"']:not([data-not-aggregate])").sum());
                } else {
                    return sumAmount;
                }
            }
        } else {
            return setNumberToFixed($table.find("input[data-path='"+fieldPath+"']:not([data-not-aggregate])").sum());
        }
    }
    
    return 0;
}
function bpGetPagerDetailAllRowsAggr(mainSelector, elem, aggregate, fieldPath) {
    var result = 0;
    var fieldPathArr = fieldPath.split('.');
    var groupPath = fieldPathArr[0];
    var $table = mainSelector.find('[data-table-path="'+groupPath+'"]');
    
    if ($table.length) {
        $.ajax({
            type: 'post',
            url: 'api/fullexpression',
            data: {
                expCode: 'pagerDetailAllRowsSum', 
                aggregate: aggregate, 
                cacheId: $table.attr('data-cacheid'), 
                groupPath: groupPath, 
                fieldPath: fieldPathArr[1]
            },
            async: false,
            success: function(data) {
                result = data;
            }
        });
    }
    
    return Number(result);
}
function bpGetPagerTotalPageNum(mainSelector, groupPath) {
    var $toolbar = mainSelector.find('div[data-pg-grouppath="'+groupPath+'"]');
    if ($toolbar.length) {
        return Number($toolbar.find('span[data-pagenumber]').text());
    }
    return 1;
}
function bpDetailRemoveConfirm(uniqId, tbl, row, elem) {
    
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
    
    row.addClass('removed-tr');
    var $dialog = $('#' + $dialogName);
    
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
                bpRowBeforeRemoveInputsSetValue(row);
                row.addClass('d-none');
                
                enableBpDetailFilterByElement(tbl);
                window['dtlAggregateFunction_'+uniqId]();
                
                bpDetailRowNumberReset(tbl);
                
                elem.trigger('click'); 
                elem.trigger('change'); 
                $dialog.dialog('close');
                
                var $nextRow = row.next('tr:visible:eq(0)'), $prevRow = row.prev('tr:visible:eq(0)');

                if (row.closest('.bprocess-table-dtl').hasClass('cool-row')) {
                    var $addButton = row.find('button.bp-add-one-row');
                    var $prevtr = row.prev('.bp-detail-row');
                    if ($addButton.length && $prevtr) {
                        $prevtr.find('a.bp-remove-row').after($addButton.clone());
                    }
                }                
                
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
            }},
            {text: plang.get('no_btn'), class: 'btn blue-madison btn-sm', click: function () {
                row.removeClass('removed-tr'); 
                
                $dialog.dialog('close');
                
                row.find('input:not([data-isdisabled], [readonly="readonly"], [readonly], readonly, [disabled="disabled"], [disabled], disabled, input.meta-name-autocomplete):visible:first').focus().select();
            }}
        ]
    });
    $dialog.dialog('open');
    bpSoundPlay('ring');
    
    return;
}
function bpRowBeforeRemoveInputsSetValue($row) {
    var $requiredInputs = $row.find('[required]').filter(function() { return this.value == ''; }); 
    var $isIgnoreRemovedRowState = $row.find('input[data-field-name="isIgnoreRemovedRowState"]');
    
    if ($requiredInputs.length) {
        $requiredInputs.removeAttr('required');
    }
    
    $row.find("input.bigdecimalInit, input.decimalInit, input.numberInit, input.integerInit, input.longInit, input[data-path*='_bigdecimal']").attr('data-not-aggregate', '1');
    
    if ($isIgnoreRemovedRowState.length) {
        $isIgnoreRemovedRowState.val('1');
    } else {
        $row.find("input[data-path*='.rowState']").val('removed');
    }
    
    return;
}
function bpCheckedDetailRemoveConfirm($rows) {
    var $dialogName = 'dialog-multi-remove-confirm';
        
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
    
    $rows.addClass('removed-tr');
    var $dialog = $('#' + $dialogName);
    
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
                
                var $parentTbl = $rows.eq(0).closest('table');
                var $processForm = $parentTbl.closest('form');
                var $uniqId = $processForm.parent().attr('data-bp-uniq-id');
                var $scrollDiv = $parentTbl.closest('.bp-overflow-xy-auto');
                
                $rows.each(function(){
                    var $thisRow = $(this);
                    if ($thisRow.hasClass('saved-bp-row')) {
                        $thisRow.addClass('d-none');
                        $thisRow.find("input[data-path*='rowState']").val('removed');
                        $thisRow.find("input.bigdecimalInit:visible, input.decimalInit:visible, input.numberInit:visible, input.longInit:visible, input[data-path*='_bigdecimal']").attr('data-not-aggregate', '1');
                    } else {
                        $thisRow.remove();
                    }
                });
                
                enableBpDetailFilterByElement($parentTbl);
                window['dtlAggregateFunction_'+$uniqId]();
                
                if ($parentTbl.hasClass('bprocess-table-subdtl')) {
                    bpSetRowIndexDepth($parentTbl.parent(), window['bp_window_'+$uniqId]);
                } else {
                    bpSetRowIndex($parentTbl.parent());
                }
                bpDetailRowNumberReset($parentTbl);
                
                $dialog.dialog('close');
                
                var scrollHeight = $scrollDiv[0].scrollHeight,
                    clientHeight = $scrollDiv[0].clientHeight, 
                    top          = $scrollDiv.scrollTop();

                $scrollDiv.find('> table[data-table-path] > tfoot > tr > td').css('bottom', scrollHeight - clientHeight - top);
    
                var $nextRow = $parentTbl.find('> tbody > tr:visible:eq(0)');
                
                if ($nextRow.length) {
                    var $nextRowFocus = $nextRow.find('input:not([data-isdisabled], [readonly="readonly"], [readonly], readonly, [disabled="disabled"], [disabled], disabled, input.meta-name-autocomplete):visible:first');
                    
                    if ($nextRowFocus.length) {
                        $nextRowFocus.focus().select();
                    } else {
                        $nextRow.find('input:not(input.meta-name-autocomplete):visible:first').focus().select();
                    }
                }
            }},
            {text: plang.get('no_btn'), class: 'btn blue-madison btn-sm', click: function () {
                $rows.removeClass('removed-tr'); 
                
                $dialog.dialog('close');
                
                $rows.eq(0).find('input:not([data-isdisabled], [readonly="readonly"], [readonly], readonly, [disabled="disabled"], [disabled], disabled, input.meta-name-autocomplete):visible:first').focus().select();
            }}
        ]
    });
    $dialog.dialog('open');
    bpSoundPlay('ring');
    
    return;
}
function bpDetailRowNumberReset(tbl) {
    var $el = tbl.find('> tbody > tr:visible:not(.d-none, .hide, .display-none)');
    var len = $el.length, i = 0;
    for (i; i < len; i++) { 
        $($el[i]).find('td:first > span').text(i + 1);
    }
    return;
}
function bpDetailRowsNumberReset(mainSelector, elem, groupPath) {
    var $tbl = mainSelector.find("table[data-table-path='"+groupPath+"']");
    bpDetailRowNumberReset($tbl);
    return;
}
function bpGetDataViewColumnVal(dataViewId, rowIndex, columnName) {
    if (typeof window['objectdatagrid_'+dataViewId] !== 'undefined') {
        
        columnName = columnName.toLowerCase();
        
        if (rowIndex == 'selected') {
            
            var selectedRows = getDataViewSelectedRows(dataViewId);

            if (selectedRows.length) {
                var origRow = selectedRows[0];
                var row = Object.fromEntries(
                    Object.entries(origRow).map(([key, val]) => [key.toLowerCase(), val])
                );
                
                if (typeof row !== 'undefined' && typeof row === 'object' && row.hasOwnProperty(columnName)) {
                    return (row[columnName] == null ? '' : row[columnName]);
                }
            }
            
        } else {
            var rows = getRowsDataView(dataViewId); 
    
            if (rows.length) {

                rowIndex = parseInt(rowIndex) - 1;
                var row = rows[rowIndex];

                if (typeof row !== 'undefined' && typeof row === 'object' && row.hasOwnProperty(columnName)) {
                    return (row[columnName] == null ? '' : row[columnName]);
                }
            }
        }
    }
    
    return '';
}
function bpGetDataViewFilterVal(dataViewId, fieldPath) {
    
    if (typeof window['objectdatagrid_'+dataViewId] !== 'undefined') {
        
        var $dv = $('#object-value-list-'+dataViewId);
        var $dvPath = $dv.find('[data-path="'+fieldPath+'"]');
        
        if ($dvPath.length) {
            
            var resultNum = '';
            
            if ($dvPath.prop("tagName") == 'SELECT') {
                resultNum = $dvPath.val();
            } else {
                if ($dvPath.hasClass('numberInit') 
                    || $dvPath.hasClass('decimalInit')
                    || $dvPath.hasClass('integerInit')) {                  
                    
                    var getNumber = $dvPath.autoNumeric("get");
                    if (isNaN(getNumber)) {
                        resultNum = Number($dvPath.val());
                    } else {
                        resultNum = Number(getNumber);
                    }
                } else if ($dvPath.hasClass('bigdecimalInit')) {
                    
                    resultNum = Number($dvPath.next("input[type=hidden]").val());
                    
                } else if ($dvPath.hasClass('longInit')) {
                    var getNumber = $dvPath.autoNumeric("get");
                    if (isNaN(getNumber)) {
                        resultNum = $dvPath.val();
                    } else {
                        resultNum = getNumber;
                    }
                } else if ($dvPath.hasClass('booleanInit')) { 
                    resultNum = $dvPath.is(':checked') ? 1 : 0;
                } else {
                    resultNum = $dvPath.val();                        
                }
            }
            
            return resultNum;
        }
    }
    
    return '';
}
function bpGetVisibleDataViewColumnVal(dataViewId, rowIndex, columnName) {
    var $visibleDv = $('#object-value-list-'+dataViewId+':visible:eq(0)');

    if ($visibleDv.length && typeof window['objectdatagrid_'+dataViewId] !== 'undefined') {
        
        columnName = columnName.toLowerCase();
        
        if (rowIndex == 'selected') {
            
            var selectedRows = getDataViewSelectedRows(dataViewId);

            if (selectedRows.length) {
                var row = selectedRows[0];
                
                if (typeof row !== 'undefined' && typeof row === 'object' && row.hasOwnProperty(columnName)) {
                    return row[columnName];
                }
            }
            
        } else {
            var rows = getRowsDataView(dataViewId); 
    
            if (rows.length) {

                rowIndex = parseInt(rowIndex) - 1;
                var row = rows[rowIndex];

                if (typeof row !== 'undefined' && typeof row === 'object' && row.hasOwnProperty(columnName)) {
                    return row[columnName];
                }
            }
        }
    }
    
    return '';
}
function bpSetListToNewline(mainSelector, elem, fieldPath, value) {
    
    var bpElem = getBpElement(mainSelector, elem, fieldPath);
    
    if (typeof bpElem == 'undefined' || bpElem == false) {
        return;
    }
    
    if (value != '' && value != null && bpElem.prop('tagName') == 'TEXTAREA') {
        var valueLower = value.toLowerCase();

        if (valueLower.indexOf('<li>') !== -1) {
            var ulList = $('<div />', {html: value});
            var ulListElements = ulList.find('li');
            var newLineStr = '', ulListLength = ulListElements.length;

            ulListElements.each(function(i){
                var liTxt = $.trim($(this).text());
                if (liTxt != '') {
                    newLineStr += liTxt + (ulListLength == (i + 1) ? '' : "\n");
                }
            }); 

            bpElem.val(newLineStr).trigger('input');
            return;
        } 
    }
    
    bpElem.val(value);
    return;
}
function bpCopyFileFieldByIndex(mainSelector, elem, srcPath, groupPath, rowIndex, trgPath) {
    
    var $bpSrcElem = getBpElement(mainSelector, elem, srcPath);
    
    if (typeof $bpSrcElem == 'undefined' || $bpSrcElem == false) {
        return;
    }
    
    var $bpTrgElem = getBpElement(mainSelector, elem, trgPath);
    
    if (typeof $bpTrgElem == 'undefined' || $bpTrgElem == false) {
        return;
    }
    
    if (elem !== 'open' && typeof elem.prop('tagName') !== 'undefined' && elem.prop('tagName') == 'TR') {
                
        if (elem.find("[data-table-path='"+groupPath+"']").length) {
            var $parent = elem.find("[data-table-path='"+groupPath+"']");
        } else {
            var $parent = mainSelector.find("[data-table-path='"+groupPath+"']");
        }

    } else if (elem === 'open') {
        
        var $parent = mainSelector.find("[data-table-path='"+groupPath+"']");
        
    } else {
        
        var $parentRow = elem.closest('.bp-detail-row');
        
        if ($parentRow.find("[data-table-path='"+groupPath+"']").length) {
            var $parent = $parentRow.find("[data-table-path='"+groupPath+"']");
        } else {
            var $parent = mainSelector.find("[data-table-path='"+groupPath+"']");
        }
    }
    
    if (rowIndex == 'first') {
        var $getRow = $parent.find('.tbody > .bp-detail-row:first');
    } else if (rowIndex == 'last') {
        var $getRow = $parent.find('.tbody > .bp-detail-row:last-child');
    } else {
        rowIndex = rowIndex - 1;
        var $getRow = $parent.find('.tbody > .bp-detail-row:eq('+rowIndex+')');
    }
    
    $bpTrgElem = $getRow.find("input[data-path='"+trgPath+"']");
    
    var getId = $bpTrgElem.attr('id');
    var getName = $bpTrgElem.attr('name');
    var getClass = $bpTrgElem.attr('class');
    var getPath = $bpTrgElem.attr('data-path');
    var getFieldName = $bpTrgElem.attr('data-field-name');
    var getValidExtension = $bpTrgElem.attr('data-valid-extension');   
    var $bpTrgElemParent = $bpTrgElem.parent(),
        $bpSrcElemParent = $bpSrcElem.parent();

    $bpTrgElem.remove();
    $bpSrcElem.removeAttr('style').appendTo($bpTrgElemParent);
    $bpSrcElemParent.append($bpSrcElem[0].outerHTML);
    $bpSrcElemParent.find('input[type="file"]').parent().children('span').remove();
    $bpSrcElemParent.find('input[type="file"]').css('color', 'transparent').parent().append('<span style="position:absolute; margin-left:-48px; margin-top:8px; font-size:10px; color:#000;">Файл сонгосон</span>');
    
    $bpTrgElemParent = $bpTrgElemParent.children();
    $bpTrgElemParent.attr('id', getId);
    $bpTrgElemParent.attr('name', getName);
    $bpTrgElemParent.attr('class', getClass);
    $bpTrgElemParent.attr('data-path', getPath);
    $bpTrgElemParent.attr('data-field-name', getFieldName);
    $bpTrgElemParent.attr('data-valid-extension', getValidExtension);
    
    $bpTrgElemParent.replaceWith($bpTrgElemParent);
    
    return;
}
function bpExpEvaluator(mainSelector, elem, processId, eventType, expStr) {
    var uniqId = mainSelector.attr('data-bp-uniq-id');
    
    var response = $.ajax({
        type: 'post',
        url: 'mdexpression/strToScript', 
        data: {processId: processId, uniqId: uniqId, expStr: expStr, eventType: eventType},
        dataType: 'json',
        async: false
    });

    return response.responseJSON;
}
function payrollExpression(elem) {
    
    PNotify.removeAll();
    var $dialogName = 'dialog-payrollexp-'+getUniqueId(1);
    if (!$("#" + $dialogName).length) {
        $('<div id="' + $dialogName + '"></div>').appendTo('body');
    }
    var $dialog = $('#' + $dialogName);
    
    var $this = $(elem);
    var $parent = $this.closest('.input-group');
    var _expression = $parent.find('input').val();
    var $row = $this.closest('tr');
    var $table = $this.closest('table');
    var groupPath = ($table.hasAttr('data-table-path') ? $table.attr('data-table-path') : '');
    var isMetaList = false;
    var metas = [];
    var hideButtonClass = '', rowMetaId = '', dialogWidth = 1100, tagsSource = '';
    
    if (groupPath == 'PRL_CALC_TYPE_DTL_DV') {
        
        var $rowMetaIdElement = $row.find("input[data-path='PRL_CALC_TYPE_DTL_DV.metaDataId']");
        var keyFieldName = 'PRL_CALC_TYPE_DTL_DV.metaDataId';
        isMetaList = true;
        
    } else if (groupPath == 'TMS_TEMPLATE_DTL') {
        
        var $rowMetaIdElement = $row.find("input[data-path='TMS_TEMPLATE_DTL.fieldPath']");
        var keyFieldName = 'TMS_TEMPLATE_DTL.fieldPath';
        isMetaList = true;
        
    } else if (groupPath == 'PRL_VALIDATION_DTL_DV') {
        
        var $rowMetaIdElement = $row.find("input[data-path='PRL_VALIDATION_DTL_DV.calcTypeId']");
        
        if ($rowMetaIdElement.val() == '') {
            PNotify.removeAll();
            new PNotify({ 
                title: 'Info',
                text: 'Та бодолтын загвар сонгоно уу!',
                type: 'info',
                addclass: pnotifyPosition,
                sticker: false
            });
            return;
        }
    }
    
    var postData = {expression: _expression, isMetaList: isMetaList};
    
    if (typeof $rowMetaIdElement !== 'undefined') {
        rowMetaId = $rowMetaIdElement.val();
        postData['rowMetaId'] = rowMetaId;
        postData['rowMetaCode'] = $row.find('input.lookup-code-autocomplete').val();
        postData['rowMetaName'] = $row.find('input.lookup-name-autocomplete').val();
    }
    
    if (isMetaList) {
        
        var $rows = $table.find('> tbody > tr');
        
        if ($rows.length) {
            var i = 0;
            $rows.each(function(){

                var $thisRow = $(this);
                var $metaIdElement = $thisRow.find("input[data-path='"+keyFieldName+"']");
                var metaId = $metaIdElement.val();

                if (metaId != '' && rowMetaId != metaId) {
                    var metaCode = $thisRow.find('input.lookup-code-autocomplete').val();
                    var metaName = $thisRow.find('input.lookup-name-autocomplete').val();
                    var rowObj = {metaId: metaId, metaCode: metaCode, metaName: metaName};
                    metas[i] = rowObj;
                    i++;
                }
            });
        }
    } else {
        var $form = $this.closest('form'), $payrollExpressionTagsSource = $form.find('[data-path="payrollExpressionTagsSource"]');
            
        if ($payrollExpressionTagsSource.length && $payrollExpressionTagsSource.val() == 'kpiTemplate') {
            tagsSource = 'kpiTemplate';
            postData['tagsSource'] = tagsSource;
            dialogWidth = 1200;
        } 
    }
    
    postData['metas'] = metas;
    
    $.ajax({
        type: 'post',
        url: 'mdsalary/payrollExpressionForm',
        data: postData, 
        dataType: 'json',
        beforeSend: function(){
            if (!$("link[href='middleware/assets/css/salary/expression.css?v=12']").length){
                $("head").append('<link rel="stylesheet" type="text/css" href="middleware/assets/css/salary/expression.css?v=12"/>');
            }
            Core.blockUI({message: 'Loading...', boxed: true});
        },
        success: function (data) {
            $dialog.empty().append(data.html);
            $dialog.dialog({
                cache: false,
                resizable: true,
                bgiframe: true,
                autoOpen: false,
                title: data.title,
                width: dialogWidth,
                height: 'auto',
                modal: true,
                close: function () {
                    $dialog.dialog('destroy').remove();
                },
                buttons: [
                    {text: plang.get('Шалгах'), class: 'btn red-sunglo btn-sm ' + hideButtonClass, click: function () {
                        
                        PNotify.removeAll();
                        
                        var expArea = $dialog.find('.p-exp-area');
                        var expAreaContent = $.trim(expArea.html());
                        
                        if (expAreaContent != '') {
                            
                            $dialog.find('form').validate({ errorPlacement: function() {} });

                            if ($dialog.find('form').valid()) {
                                $.ajax({
                                    type: 'post',
                                    url: 'mdsalary/validateExpression', 
                                    data: {tagsSource: tagsSource, expressionContent: expAreaContent}, 
                                    dataType: 'json',
                                    beforeSend: function () {
                                        Core.blockUI({message: 'Checking...', boxed: true});
                                    },
                                    success: function (checkData) {
                                        new PNotify({
                                            title: checkData.status,
                                            text: checkData.message,
                                            type: checkData.status,
                                            addclass: pnotifyPosition,
                                            sticker: false
                                        });
                                        Core.unblockUI();
                                    }
                                });
                            }
                            
                        } else {
                            new PNotify({
                                title: 'Error',
                                text: 'Томъёо бичигдээгүй байна',
                                type: 'error',
                                addclass: pnotifyPosition,
                                sticker: false
                            });
                        } 
                    }},
                    {text: plang.get('save_btn'), class: 'btn green-meadow btn-sm', click: function () {
                        
                        PNotify.removeAll();
                        
                        var expArea = $dialog.find('.p-exp-area');
                        var expAreaContent = $.trim(expArea.html());
                        
                        if (expAreaContent != '') {
                            
                            $dialog.find('form').validate({ errorPlacement: function() {} });
                            
                            if ($dialog.find('form').valid()) {
                                
                                $.ajax({
                                    type: 'post',
                                    url: 'mdsalary/validateExpression', 
                                    data: {tagsSource: tagsSource, expressionContent: expAreaContent, isRun: hideButtonClass}, 
                                    dataType: 'json',
                                    beforeSend: function () {
                                        Core.blockUI({message: 'Checking...', boxed: true});
                                    },
                                    success: function (checkData) {
                                        new PNotify({
                                            title: checkData.status,
                                            text: checkData.message,
                                            type: checkData.status,
                                            addclass: pnotifyPosition,
                                            sticker: false
                                        });

                                        if (checkData.status == 'success') {
                                            $parent.find('input').val($.trim(checkData.expression));
                                            $dialog.dialog('close');
                                        }
                                        Core.unblockUI();
                                    }
                                });
                            }
                            
                        } else {
                            $parent.find('input').val('');
                            $dialog.dialog('close');
                        }
                    }}, 
                    {text: plang.get('close_btn'), class: 'btn blue-hoki btn-sm', click: function () {
                        $dialog.dialog('close');
                    }}
                ]
            });
            $dialog.dialog('open');
            
            Core.unblockUI();
        },
        error: function () { alert("Error"); }
    });
}
function expenseExpression(elem) {
    
    var $dialogName = 'dialog-expense-exp-'+getUniqueId(1);
    if (!$("#" + $dialogName).length) {
        $('<div id="' + $dialogName + '"></div>').appendTo('body');
    }
    var $dialog = $('#' + $dialogName);
    
    var $this = $(elem);
    var $parent = $this.closest('.input-group');
    var _expression = $parent.find('input').val();
    var $row = $this.closest('tr');
    var hideButtonClass = 'hide';
    var rowMetaCode = $row.find('input.lookup-code-autocomplete').val();
    var rowMetaName = $row.find('input.lookup-name-autocomplete').val();
    var bpMetaDataId = $this.closest('form').find('input[data-path="metaDataId"]').val();
    
    PNotify.removeAll();
    if (bpMetaDataId == '') {
        new PNotify({
            title: 'Warning',
            text: 'Аргачлал эсвэл Татах процесс хоосон байна!',
            type: 'warning',
            addclass: pnotifyPosition,
            sticker: false
        });
        return;
    }
    
    $.ajax({
        type: 'post',
        url: 'mdgl/expenseExpressionForm',
        data: {bpMetaDataId: bpMetaDataId, rowMetaCode: rowMetaCode, rowMetaName: rowMetaName, expression: _expression}, 
        dataType: 'json',
        beforeSend: function(){
            if (!$("link[href='middleware/assets/css/salary/expression.css?v=1']").length){
                $("head").append('<link rel="stylesheet" type="text/css" href="middleware/assets/css/salary/expression.css?v=1"/>');
            }
            Core.blockUI({
                message: 'Loading...', 
                boxed: true 
            });
        },
        success: function (data) {
            $dialog.empty().append(data.html);
            $dialog.dialog({
                cache: false,
                resizable: true,
                bgiframe: true,
                autoOpen: false,
                title: data.title,
                width: 1100,
                height: "auto",
                modal: true,
                close: function () {
                    $dialog.dialog('destroy').remove();
                },
                buttons: [
                    {text: data.save_btn, class: 'btn green-meadow btn-sm', click: function () {
                        
                        PNotify.removeAll();
                        
                        var expArea = $dialog.find('.p-exp-area');
                        var expAreaContent = $.trim(expArea.html());
                        
                        if (expAreaContent != '') {
                            
                            $.ajax({
                                type: 'post',
                                url: 'mdgl/validateExpression', 
                                data: {expressionContent: expAreaContent, isRun: hideButtonClass}, 
                                dataType: 'json',
                                beforeSend: function () {
                                    Core.blockUI({
                                        message: 'Checking...',
                                        boxed: true
                                    });
                                },
                                success: function (checkData) {
                                    new PNotify({
                                        title: checkData.status,
                                        text: checkData.message,
                                        type: checkData.status,
                                        addclass: pnotifyPosition,
                                        sticker: false
                                    });
                                    
                                    if (checkData.status == 'success') {
                                        $parent.find('input').val($.trim(checkData.expression)).trigger('change');
                                        $dialog.dialog('close');
                                    }
                                    Core.unblockUI();
                                }
                            });
                            
                        } else {
                            $parent.find('input').val('');
                            $dialog.dialog('close');
                        }
                    }}, 
                    {text: data.close_btn, class: 'btn blue-hoki btn-sm', click: function () {
                        $dialog.dialog('close');
                    }}
                ]
            });
            $dialog.dialog('open');
            
            Core.unblockUI();
        },
        error: function () {
            alert("Error");
        }
    });
}
function bpRuleExpressionEditor(elem) {
    PNotify.removeAll();
    var $dialogName = 'dialog-ruleexp-'+getUniqueId(1);
    if (!$("#" + $dialogName).length) {
        $('<div id="' + $dialogName + '"></div>').appendTo('body');
    }
    var $dialog = $('#' + $dialogName);
    
    var $this = $(elem);
    var $parent = $this.closest('.input-group');
    var _expression = $parent.find('textarea').val();
    var dialogWidth = 1100;
    
    var postData = {expression: _expression};
    
    $.ajax({
        type: 'post',
        url: 'mdform/ruleExpressionForm',
        data: postData, 
        dataType: 'json',
        beforeSend: function(){
            if (!$("link[href='middleware/assets/css/salary/expression.css?v=12']").length){
                $("head").append('<link rel="stylesheet" type="text/css" href="middleware/assets/css/salary/expression.css?v=12"/>');
            }
            Core.blockUI({message: 'Loading...', boxed: true});
        },
        success: function (data) {
            $dialog.empty().append(data.html);
            $dialog.dialog({
                cache: false,
                resizable: true,
                bgiframe: true,
                autoOpen: false,
                title: 'Rule expression',
                width: dialogWidth,
                height: 'auto',
                modal: true,
                close: function () {
                    $dialog.dialog('destroy').remove();
                },
                buttons: [
                    {text: plang.get('save_btn'), class: 'btn green-meadow btn-sm', click: function () {
                        
                        PNotify.removeAll();
                        
                        var expArea = $dialog.find('.p-exp-area');
                        var expAreaContent = $.trim(expArea.html());
                        
                        if (expAreaContent != '') {
                            
                            $dialog.find('form').validate({ errorPlacement: function() {} });
                            
                            if ($dialog.find('form').valid()) {
                                
                                $.ajax({
                                    type: 'post',
                                    url: 'mdform/validateRuleExpression', 
                                    data: {expressionContent: expAreaContent}, 
                                    dataType: 'json',
                                    beforeSend: function () {
                                        Core.blockUI({message: 'Checking...', boxed: true});
                                    },
                                    success: function (checkData) {
                                        new PNotify({
                                            title: checkData.status,
                                            text: checkData.message,
                                            type: checkData.status,
                                            addclass: pnotifyPosition,
                                            sticker: false
                                        });

                                        if (checkData.status == 'success') {
                                            $parent.find('textarea').val($.trim(checkData.expression)).trigger('change');
                                            $parent.find('.p-exp-area').html(expAreaContent);
                                            $dialog.dialog('close');
                                        }
                                        Core.unblockUI();
                                    }
                                });
                            }
                            
                        } else {
                            $parent.find('textarea').val('').trigger('change');
                            $parent.find('.p-exp-area').empty();
                            $dialog.dialog('close');
                        }
                    }}, 
                    {text: plang.get('close_btn'), class: 'btn blue-hoki btn-sm', click: function () {
                        $dialog.dialog('close');
                    }}
                ]
            });
            $dialog.dialog('open');
            
            Core.unblockUI();
        },
        error: function () { alert("Error"); }
    });
}
function bpCopyAllSubDetail(mainSelector, elem, groupPath) {

    var uniqId = mainSelector.attr('data-bp-uniq-id');
    var parentTable = elem.closest('table');
    var parentRow = elem.closest('tr');
    var parentRowIndex = parentRow.index();
    var tableBody = parentRow.find("table[data-table-path='"+groupPath+"'] > tbody");
    
    tableBody.find('select.select2').select2('destroy');
    tableBody.removeClass('saved-bp-row');
    $.uniform.restore(tableBody.find('input[type=checkbox]'));
    
    var tableBodyClone = tableBody.clone();
    var $originalSelects = tableBody.find('select');

    tableBodyClone.find('select').each(function(index, item) {
        $(item).val($originalSelects.eq(index).val());
    });
        
    parentTable.find('> tbody > tr:not(:eq('+parentRowIndex+'))').each(function(){
        
        var tableBodyCloneClone = tableBodyClone.clone();

        tableBodyCloneClone.find('select').each(function(index, item) {
            $(item).val($originalSelects.eq(index).val());
        });
    
        var _thisRow = $(this);
        var _thisTable = _thisRow.find("table[data-table-path='"+groupPath+"']");
        var _thisBody = _thisTable.find('> tbody');

        _thisBody.empty().replaceWith(tableBodyCloneClone);
        
        Core.initInputType(_thisTable.find('> tbody'));
        
        var rowEl = _thisTable.find('> tbody > tr');
        var rowLen = rowEl.length, rowi = 0;

        for (rowi; rowi < rowLen; rowi++) { 
            window['bpFullScriptsWithoutEvent_'+uniqId]($(rowEl[rowi]), groupPath, true);
        }
        
        bpSetRowIndexDepth(_thisTable.parent(), mainSelector, _thisRow.index());
        
        bpDetailFreeze(_thisTable);
    });
    
    Core.initInputType(tableBody);
    
    return;
}
function bpGetJsonParamVal(mainSelector, paramPath) {
    var uniqId = mainSelector.attr('data-bp-uniq-id');
    var addonJsonParam = window['addonJsonParam_'+uniqId];
    
    try {
        return eval('addonJsonParam.'+paramPath);
    } catch(e) {
        return '';
    }
}
function bpCheckJsonParam(mainSelector, paramPath) {
    var uniqId = mainSelector.attr('data-bp-uniq-id');
    var addonJsonParam = window['addonJsonParam_'+uniqId];
    
    try {
        var checkPath = eval('addonJsonParam.'+paramPath);
        return true;
    } catch(e) {
        return false;
    }
}
function showTooltip(processId, dataPath, messageCode, position, type, color, textColor, icon, pstyle, padditionText, piconStyle, addintionSectionStyle, addintionMsgStyle) {
    
    var dataPaths = dataPath.split(',');
    var style = (typeof pstyle != 'undefined') ? pstyle : '';
    var additionText = (typeof padditionText != 'undefined') ? padditionText : '';
    var $addintionSectionStyle = (typeof addintionSectionStyle != 'undefined') ? addintionSectionStyle : '';
    var $addintionMsgStyle = (typeof addintionMsgStyle != 'undefined') ? addintionMsgStyle : '';
    var iconStyle = (typeof piconStyle != 'undefined') ? piconStyle : '';
    
    var message = plang.get(messageCode);
    
    if (position == 'windowbottom') {
        
        if (messageCode == '') {
            
            var $bp = $('div[data-process-id="'+ processId +'"]');
            $bp.find('[data-path-message="'+dataPath+'"]').remove();
            
        } else {
            var html = '<div data-path-message="'+dataPath+'" style="background-color:'+ color +'; color:'+ textColor +'; padding: 5px; '+ style +'">'
                + '<strong '+ iconStyle +'><i class="fa '+ icon +'"></i> '+ additionText +' </strong>' + message
            + '</div>';

            $('div[data-process-id="'+ processId +'"]').append(html);
        }

    } else if (position == 'windowtop') {
        
        if (messageCode == '') {
            
            var $bp = $('div[data-process-id="'+ processId +'"]');
            $bp.find('[data-path-message="'+dataPath+'"]').remove();
            
        } else {
            var html = '<div data-path-message="'+dataPath+'" style="background-color:'+ color +'; color:'+ textColor +'; padding: 5px; '+ style +'">'
                + '<strong '+ iconStyle +'><i class="fa '+ icon +'"></i> '+ additionText +' </strong>' + message
            + '</div>';

            $('div[data-process-id="'+ processId +'"]').prepend(html);
        }

    } else {
        
        var $bp = $('div[data-process-id="'+ processId +'"]');
        
        $.each(dataPaths, function (index, dataPath) {
            
            var $this = $bp.find('div[data-section-path="'+ dataPath +'"]');
            $this = ($this.length > 1) ? $($this[0]) : $this;
            
            if (position == 'right' && $addintionSectionStyle !== '') {
                $this.attr('style', $addintionSectionStyle);
            } 
            
            var $parent = $this.parent();
            var html = '<div data-path-message="'+dataPath+'" style="background-color:'+ color +'; color:'+ textColor +'; padding: 5px; '+ style +'; '+ $addintionMsgStyle +'">'
                            + '<strong '+ iconStyle +'><i class="fa '+ icon +'"></i> '+ additionText +' </strong>' + message
                        + '</div>';
            
            $parent.find('div[data-path-message="'+dataPath+'"]').remove();
            
            if (messageCode != '') {
                $parent.append(html);
            }

            if (type == 'hint') {
                setTimeout(function () {
                    $parent.find('div[data-path-message="'+dataPath+'"]').remove();
                }, 200);
            }
        });
    }
    
    return;
}
function showKpiTooltip(processId, dataPath, messageCode, position, type, color, textColor, icon, pstyle, padditionText, piconStyle, addintionSectionStyle, addintionMsgStyle) {
    
    var dataPaths = dataPath.split(',');
    var style = (typeof pstyle != 'undefined') ? pstyle : '';
    var additionText = (typeof padditionText != 'undefined') ? padditionText : '';
    var $addintionSectionStyle = (typeof addintionSectionStyle != 'undefined') ? addintionSectionStyle : '';
    var $addintionMsgStyle = (typeof addintionMsgStyle != 'undefined') ? addintionMsgStyle : '';
    var iconStyle = (typeof piconStyle != 'undefined') ? piconStyle : '';
    
    var message = plang.get(messageCode);
    
    if (position == 'windowbottom') {

        var html = '<div data-path-message="'+dataPath+'" style="background-color:'+ color +'; color:'+ textColor +'; padding: 5px; '+ style +'">'
            + '<strong '+ iconStyle +'><i class="fa '+ icon +'"></i> '+ additionText +' </strong>' + message
        + '</div>';

        $('div[data-process-id="'+ processId +'"]').append(html);

    } else if (position == 'windowtop') {

        var html = '<div data-path-message="'+dataPath+'" style="background-color:'+ color +'; color:'+ textColor +'; padding: 5px; '+ style +'">'
            + '<strong '+ iconStyle +'><i class="fa '+ icon +'"></i> '+ additionText +' </strong>' + message
        + '</div>';

        $('div[data-process-id="'+ processId +'"]').prepend(html);

    } else {
        
        $.each(dataPaths, function (index, dataPath) {
            
            var fieldPath = dataPath.split('.');
            var dtlCode = fieldPath[0].toLowerCase().trim();
            var $getRow = $('div[data-process-id="'+ processId +'"]').find("table[data-table-path='kpiDmDtl'] > tbody > tr[data-dtl-code='"+dtlCode+"']");
            
            if ($getRow.length) {
                
                var $table = $getRow.closest('table[data-table-path="kpiDmDtl"]');
                var groupPath = $table.attr('data-group-path');
                var factName = fieldPath[1].trim();
                
                if (groupPath) {
                    var $getField = $getRow.find('[data-path="'+groupPath+'kpiDmDtl.'+factName+'"]:eq(0)');
                } else {
                    var $getField = $getRow.find('[data-path="kpiDmDtl.'+factName+'"]:eq(0)');
                }     

                if (position == 'right' && $addintionSectionStyle !== '') {
                    $getField.attr('style', $addintionSectionStyle);
                } 

                var html = '<div data-path-message="'+dataPath+'" style="background-color:'+ color +'; color:'+ textColor +'; padding: 5px; '+ style +'; '+ $addintionMsgStyle +'">'
                            + '<strong '+ iconStyle +'><i class="fa '+ icon +'"></i> '+ additionText +' </strong>' + message
                            + '</div>';

                var $parent = $getField.parent();

                $parent.find('div[data-path-message="'+dataPath+'"]').remove();
                $parent.append(html);

                if (type == 'hint') {
                    setTimeout(function () {
                        $parent.find('div[data-path-message="'+dataPath+'"]').remove();
                    }, 200);
                }
            }
        });
    }
    
    return;    
}
function removeTagName(mainSelector, $processIds, $type, $allPackageNameRemove) {
    var $processIdList = $processIds.split(', ');
    
    if ($type === 'package') {
        if (typeof $allPackageNameRemove !== 'undefined') {
            mainSelector.closest('.package-meta').find('.package-tab-name').remove();
        } else {
            $.each($processIdList, function($index, processId) {

                var $mainRemoveSelector = mainSelector.closest('div[id="package-tab-' + processId + '"]').prev();

                if (processId && $mainRemoveSelector.hasClass('package-tab-name')) {
                    $mainRemoveSelector.remove();
                }
            });
        }
    }
}
function bpSetFieldValueAll(mainSelector, fieldPath, val) {
    var $getPathElement = mainSelector.find("[data-path='" + fieldPath + "']");

    if ($getPathElement.length > 0) {

        var $getPathElementFirst = mainSelector.find("[data-path='" + fieldPath + "']:eq(0)");

        if ($getPathElementFirst.prop('tagName') == 'SELECT') {

            if ($getPathElementFirst.hasClass('select2')) {
                $getPathElement.trigger("select2-opening", [true]);
                $getPathElement.select2('val', val);
            } else {
                $getPathElement.trigger("blur");
                $getPathElement.val(val);
            }
        } else {
            if ($getPathElementFirst.hasClass('longInit') 
                || $getPathElementFirst.hasClass('numberInit') 
                || $getPathElementFirst.hasClass('decimalInit') 
                || $getPathElementFirst.hasClass('integerInit')) {

                $getPathElement.autoNumeric("set", val);

            } else if ($getPathElementFirst.hasClass('bigdecimalInit')) {
                $getPathElement.next("input[type=hidden]").val(setNumberToFixed(val));
                $getPathElement.autoNumeric("set", val);                        

            } else if ($getPathElementFirst.hasClass('dateInit')) {
                if (val !== '' && val !== null) {
                    $getPathElement.datepicker('update', date('Y-m-d', strtotime(val)));
                } else {
                    $getPathElement.datepicker('update', null);
                }
            } else if ($getPathElementFirst.hasClass('datetimeInit')) {
                if (val !== '' && val !== null) {
                    $getPathElement.val(date('Y-m-d H:i:s', strtotime(val)));
                } else {
                    $getPathElement.val('');
                }
            } else if ($getPathElementFirst.hasClass('popupInit')) {   
                setLookupPopupValue($getPathElement, val);
            } else if ($getPathElementFirst.hasClass('booleanInit')) {   
                checkboxCheckerUpdate($getPathElement, val);
            } else {
                $getPathElement.val(val);                        
            }
        }
    }

    return;
}
function showKpiForm(mainSelector, elem, param, viewMode, isShowName) {
    var $bpWindow = mainSelector.find('form');
    
    if (param == 1) {
        
        var processUniqId = mainSelector.attr('data-bp-uniq-id');
        var viewMode = typeof viewMode !== 'undefined' ? viewMode.toLowerCase() : '';
        var isShowName = typeof isShowName !== 'undefined' ? isShowName : 0;
                
        $.ajax({
            type: 'post',
            url: 'mdform/showKpiForm', 
            data: $bpWindow.find("input:not([name*='.']), select:not([name*='.'])").serialize()+'&uniqId='+processUniqId+'&viewMode='+viewMode+'&isShowName='+isShowName,
            dataType: 'json',
            async: false, 
            success: function (data) {
                
                PNotify.removeAll();

                if (data.status === 'success') {
                    
                    var $renderElement = $bpWindow.find('div[data-section-path="kpiDmDtl"]:eq(0)');
                    
                    if ($renderElement.length > 0) {
                        
                        $renderElement.empty().append(data.html).promise().done(function() {
                            $renderElement.css({'display': 'block', 'width': '100%'});
                            bpBlockMessageStop();
                        });
                        
                    } else {
                        bpBlockMessageStop();
                    }
                    
                } else {
                    new PNotify({
                        title: data.status,
                        text: data.message,
                        type: data.status,
                        addclass: pnotifyPosition,
                        sticker: false
                    });
                    bpBlockMessageStop();
                }
            },
            error: function () { alert("Error"); bpBlockMessageStop(); }
        });
        
    } else if ($bpWindow.find('.kpiFormSection').length > 0) {
        $bpWindow.find('.kpiFormSection').remove();
        bpBlockMessageStop();
    } 
    
    return;
}
function showIndicatorForm(mainSelector, elem, param, viewMode, isShowName) {
    var $bpWindow = mainSelector.find('form');
    
    if (param == 1) {
        
        var processUniqId = mainSelector.attr('data-bp-uniq-id');
        var viewMode = typeof viewMode !== 'undefined' ? viewMode.toLowerCase() : '';
        var isShowName = typeof isShowName !== 'undefined' ? isShowName : 0;
                
        $.ajax({
            type: 'post',
            url: 'mdform/showIndicatorForm', 
            data: $bpWindow.find("input:not([name*='.']), select:not([name*='.'])").serialize()+'&uniqId='+processUniqId+'&viewMode='+viewMode+'&isShowName='+isShowName,
            dataType: 'json',
            async: false, 
            success: function (data) {
                
                PNotify.removeAll();

                if (data.status === 'success') {
                    
                    if ($bpWindow.find('div[data-section-path="kpiDmMartReverse"]').length > 0) {
                        
                        var $renderElement = $bpWindow.find('div[data-section-path="kpiDmMartReverse"]:eq(0)');
                        $renderElement.empty().append(data.html).promise().done(function() {
                            $renderElement.css({'display': 'block', 'width': '100%'});
                            bpBlockMessageStop();
                        });
                        
                    } else {
                        bpBlockMessageStop();
                    }
                    
                } else {
                    new PNotify({
                        title: data.status,
                        text: data.message,
                        type: data.status,
                        addclass: pnotifyPosition,
                        sticker: false
                    });
                    bpBlockMessageStop();
                }
            },
            error: function () { alert("Error"); bpBlockMessageStop(); }
        });
        
    } else if ($bpWindow.find('.kpiFormSection').length > 0) {
        $bpWindow.find('.kpiFormSection').remove();
        bpBlockMessageStop();
    } 
    
    return;
}
function bpSaveEdit(mainSelector, processCode) {
    mainSelector.find("button.bp-btn-saveedit").removeClass('hide').attr('data-processbtn-processcode', processCode);
    return;
}
function bpMultiPathVisibler(mainSelector, elem, mode, splitChar, paths) {
    splitChar = splitChar.trim();
    mode = mode.trim().toLowerCase();
    var pathsArr = paths.split(splitChar);
    var i = 0, pathsCount = pathsArr.length, selectorNames = '';
    
    if (mode == 'hide') {
        
        for (i; i < pathsCount; i++) {
            var path = pathsArr[i].trim();
            
            if (path.indexOf('.') !== -1) {
                setBpRowParamHide(mainSelector, elem, path);
            } else {
                selectorNames += "th[data-cell-path='"+path+"'], td[data-cell-path='"+path+"'], th[data-row-path='"+path+"'], td[data-row-path='"+path+"'], ";
            }
        }
        
        if (selectorNames != '') {
            mainSelector.find(rtrim(selectorNames, ', ')).css({display: 'none'});
        }
    
    } else if (mode == 'show') {
        
        for (i; i < pathsCount; i++) {
            var path = pathsArr[i].trim();
            
            if (path.indexOf('.') !== -1) {
                setBpRowParamShow(mainSelector, elem, path);
            } else {
                selectorNames += "th[data-cell-path='"+path+"'], td[data-cell-path='"+path+"'], th[data-row-path='"+path+"'], td[data-row-path='"+path+"'], [data-label-path='"+path+"'], [data-section-path='"+path+"'], [data-path='"+path+"'], ";
            }
        }
        
        if (selectorNames != '') {
            mainSelector.find(rtrim(selectorNames, ', ')).css({display: ''});
        }
    }
    
    return;
}
function bpDetailHideAll(mainSelector, fieldNames) {
    
    var fieldNamesArr = fieldNames.split(',');
    var i = 0, fieldName = '', selectorNames = '';
    
    for (i; i < fieldNamesArr.length; i++) {
        fieldName = fieldNamesArr[i].trim();
        selectorNames += "th[data-cell-path='"+fieldName+"'], td[data-cell-path='"+fieldName+"'], th[data-row-path='"+fieldName+"'], td[data-row-path='"+fieldName+"'], ";
    }
    
    mainSelector.find(rtrim(selectorNames, ', ')).css({display: 'none'});
    return;
} 
function bpDetailShowAll(mainSelector, fieldNames) {
    
    var fieldNamesArr = fieldNames.split(',');
    var i = 0, fieldName = '', selectorNames = '';
    
    for (i; i < fieldNamesArr.length; i++) {
        fieldName = fieldNamesArr[i].trim();
        selectorNames += "th[data-cell-path='"+fieldName+"'], td[data-cell-path='"+fieldName+"'], th[data-row-path='"+fieldName+"'], td[data-row-path='"+fieldName+"'], ";
    }
    
    mainSelector.find(rtrim(selectorNames, ', ')).css({display: ''});
    return;
} 
function bpDetailEnableAll(mainSelector, fieldNames) {
    
    var fieldNamesArr = fieldNames.split(',');
    var i = 0, fieldName = '', comboNames = '', popupNames = '', textboxNames = '', booleanNames = '';
    
    for (i; i < fieldNamesArr.length; i++) {
        fieldName = fieldNamesArr[i].trim();
        
        var $field = mainSelector.find("[data-path='"+fieldName+"']:eq(0)");
        
        if ($field.hasClass('select2')) {
            comboNames += "[data-path='"+fieldName+"'], ";
        } else if ($field.hasClass('popupInit')) {
            popupNames += "[data-section-path='"+fieldName+"'] input[type='text'], ";
        } else if ($field.hasClass('booleanInit')) {
            booleanNames += "[data-path='"+fieldName+"'], ";
        } else {
            textboxNames += "[data-path='"+fieldName+"'], ";
        }
    }
    
    if (comboNames !== '') {
        mainSelector.find(rtrim(comboNames, ', ')).select2('readonly', false).select2('enable');
    }
    if (popupNames !== '') {
        mainSelector.find(rtrim(popupNames, ', ')).removeAttr('readonly disabled');
    }
    if (textboxNames !== '') {
        mainSelector.find(rtrim(textboxNames, ', ')).removeAttr('readonly disabled');
    }
    if (booleanNames !== '') {
        var $checkBoxes = mainSelector.find(rtrim(booleanNames, ', '));
        
        $checkBoxes.removeAttr('onclick style data-isdisabled');
        $checkBoxes.closest('.checker').removeClass('disabled');
        $.uniform.update($checkBoxes);
    }
    
    return;
} 
function bpDetailDisableAll(mainSelector, fieldNames) {
    
    var fieldNamesArr = fieldNames.split(',');
    var i = 0, fieldName = '', comboNames = '', popupNames = '', textboxNames = '', booleanNames = '';
    
    for (i; i < fieldNamesArr.length; i++) {
        fieldName = fieldNamesArr[i].trim();
        
        var $field = mainSelector.find("[data-path='"+fieldName+"']:eq(0)");
        
        if ($field.hasClass('select2')) {
            comboNames += "[data-path='"+fieldName+"'], ";
        } else if ($field.hasClass('popupInit')) {
            popupNames += "[data-section-path='"+fieldName+"'] input[type='text'], ";
        } else if ($field.hasClass('booleanInit')) {
            booleanNames += "[data-path='"+fieldName+"'], ";
        } else {
            textboxNames += "[data-path='"+fieldName+"'], ";
        }
    }
    
    if (comboNames !== '') {
        mainSelector.find(rtrim(comboNames, ', ')).select2('readonly', true);
    }
    if (popupNames !== '') {
        mainSelector.find(rtrim(popupNames, ', ')).prop('readonly', true);
    }
    if (textboxNames !== '') {
        mainSelector.find(rtrim(textboxNames, ', ')).prop('readonly', true);
    }
    if (booleanNames !== '') {
        var $checkBoxes = mainSelector.find(rtrim(booleanNames, ', '));
        
        $checkBoxes.attr({'data-isdisabled': 'true', style: 'cursor: not-allowed'});
        $checkBoxes.closest('.checker').addClass('disabled');
    }
    
    return;
} 
function bpBlockMessageStart(message) {
    
    $('.exp-blockui-overlay, .exp-blockui-blockmsg').remove();
    $('body').append('<div class="exp-blockui-overlay"></div><div class="exp-blockui-blockmsg"><div class="loading-message loading-message-boxed"><img src="' + Core.getGlobalImgPath() + 'loading-spinner-grey.gif"><span>&nbsp;&nbsp;'+message+'</span></div></div>');
    
    return true;
}
function bpBlockMessageStop() {
    $('.exp-blockui-overlay, .exp-blockui-blockmsg').remove();
    return true;
}
function bpPackageProcessResolver(mainSelector, type) {
    if (type == 'detail') {
        
        var $saveBtn = mainSelector.find('button.bp-btn-save').clone();
        var $toolbarButton = mainSelector.find('.table-toolbar > div.row > div.col-md-8');
        var $fullscreenButton = mainSelector.find('.table-toolbar > div.row > div.col-md-4 > button.bp-detail-fullscreen');
        
        mainSelector.find('.meta-toolbar').css({display: 'none'});
        mainSelector.find('div.bp-header-param').css({display: 'none'});
        mainSelector.find('.bp-tabs > ul.nav-tabs').css({display: 'none'});
        mainSelector.find('.bp-tabs > div.tab-content').css({'padding': '0px', 'border-top': '0px'});
        
        $saveBtn.addClass('float-right'); 
        
        $toolbarButton.removeClass('col-md-8').addClass('col-md-12');
        
        if ($fullscreenButton.length) {
            
            var $fullscreenButtonClone = $fullscreenButton.clone();
            
            $fullscreenButtonClone.addClass('float-right'); 
            $toolbarButton.find('.btn:last').after($fullscreenButtonClone);
            
            $fullscreenButton.remove();
        }
        
        $toolbarButton.find('.btn:last').after($saveBtn);
    }
    
    return;
}
function bpIsDisabled(mainSelector, elem, fieldPath) {
    var $bpElem = getBpElement(mainSelector, elem, fieldPath);
    
    if (typeof $bpElem == 'undefined' || $bpElem == false) {
        return false;
    }
    
    if ($bpElem.hasClass('popupInit')) {
        if ($bpElem.parent().find('.lookup-code-autocomplete').is('[readonly]')) {
            return true;
        }
    } else if ($bpElem.hasClass('select2')) {
        if ($bpElem.prev('.select2-container').hasClass('select2-container-disabled')) {
            return true;
        }
    } else {
        if ($bpElem.is('[readonly]')) {
            return true;
        }
    }
    
    return false;
}
function bpFullMessage(mainSelector, type, message) {
    if (mainSelector.hasClass('kpi-ind-tmplt-section')) {
        var $form = mainSelector.closest('form');
    } else {
        var $form = mainSelector.find('form#wsForm');
    }
    type = type.replace('error', 'danger');
    $form.css({display: 'none'});
    $form.after('<div class="alert alert-'+type+'">'+message+'</div>');
    return;
}
function bpButtonEnable(path, mainSelector) {
    $("button[data-path='"+path+"']", mainSelector).prop('disabled', false);
    return;
}
function bpButtonDisable(path, mainSelector) {
    $("button[data-path='"+path+"']", mainSelector).prop('disabled', true);
    return;
}
function setBpRowButtonDisable(mainSelector, elem, fieldPath) {
    
    if (elem === 'open') {
        var $selectPath = mainSelector.find("[data-path='" + fieldPath + "']");
        if ($selectPath.length) {
            $selectPath.prop('disabled', true);
        }
        return;    
    }
    
    var $this = $(elem, mainSelector), $oneLevelRow = $this.closest('tr');
    
    if ($oneLevelRow.find("[data-path='" + fieldPath + "']").length === 0) {
        mainSelector.find("[data-path='" + fieldPath + "']").prop('disabled', true);
    } else {
        $oneLevelRow.find("[data-path='" + fieldPath + "']").prop('disabled', true);   
    }
    
    return; 
}
function bpHideProcessDialog(mainSelector) {
    
    var processId = mainSelector.attr('data-process-id');
    
    $('#dialog-businessprocess-'+processId).on('dialogopen', function(){
        var $dialog = mainSelector.closest('.ui-dialog');
        $dialog.addClass('d-none');
        $dialog.nextAll('.ui-widget-overlay:first').addClass('d-none');
    }); 
    
    return;
} 
function bpThisClickButton(mainSelector, buttonCode) {
    
    var processId = mainSelector.attr('data-process-id');
    buttonCode = buttonCode.toLowerCase();
    
    $('#dialog-businessprocess-'+processId).on('dialogopen', function() {
        if (mainSelector.closest('.ui-dialog').length) {
            var $processParent = mainSelector.closest('.ui-dialog');
        } else {
            var $processParent = mainSelector;
        }

        $processParent.find('.bp-btn-'+buttonCode+':eq(0)').click();
    }); 
    
    if (buttonCode == 'close') {
        var $backButton = mainSelector.find('.bp-btn-back:eq(0)');
        
        if ($backButton.length) {
            $backButton.click();
        }
    }

    return;
}
function bpSetPVScroll(mainSelector, vHeight) {
    var $packageTab = mainSelector.closest('.package-tab');
    
    if ($packageTab.length) {
        $packageTab.css({'max-height': vHeight + 'px', 'overflow': 'auto'});
    }
    return;
}
function bpSetPHScroll(mainSelector, vWidth) {
    var $packageTab = mainSelector.closest('.package-tab');
    
    if ($packageTab.length) {
        $packageTab.css({'max-width': vWidth + 'px', 'overflow': 'auto'});
    }
    return;
}
function bpSetDetailHeight(mainSelector, groupPath, sHeight) {
    var $dtl = mainSelector.find('div.bp-overflow-xy-auto[data-parent-path="'+groupPath+'"]');
    if ($dtl.length) {
        var setHeight = (sHeight == 'auto') ? 'auto' : parseInt(sHeight) + 'px';
        $dtl.css('max-height', setHeight).attr('data-setexp-height', 1);
    }
    return;
}
function bpGetFileFieldSize(mainSelector, elem, fieldPath) {
    var $bpElem = getBpElement(mainSelector, elem, fieldPath);
    
    if (typeof $bpElem == 'undefined' || $bpElem == false) {
        return 0;
    }
    
    if (typeof $bpElem.attr('data-valid-extension') !== 'undefined') {
        var getExtension = $bpElem.attr('data-valid-extension');
        
        if ($.trim(getExtension) !== '') {
            var removeWhiteSpace = getExtension.replace(/\s+/g, '');
            if ($bpElem.hasExtension(removeWhiteSpace.split(','))) {
                return $bpElem[0].files[0].size;
            }
            
        } else {
            return $bpElem[0].files[0].size;
        }
        
    } else {
        return $bpElem[0].files[0].size;
    }
    
    return 0;
}
function bpSetAddControlChooseType(mainSelector, elem, groupPath, controlName, chooseType) {
                        
    if (controlName == 'multirow') {
        mainSelector.find("button.bp-add-multi-row[data-action-path='"+groupPath+"']").attr('data-choose-type', chooseType);
    } else if (controlName == 'autocomplete') {
        mainSelector.find("div.bp-add-ac-row[data-action-path='"+groupPath+"'] button").attr('data-choose-type', chooseType);
    }
    
    return;
}
function bpSetAutoCompleteFilterType(mainSelector, elem, groupPath, filterType) {
    var $acControl = mainSelector.find("div.bp-add-ac-row[data-action-path='"+groupPath+"']");
    
    if ($acControl.length && filterType != '') {
        var $ul = $acControl.find('ul.dropdown-menu'), $item = $([]);

        if (filterType == 'code') {
            $item = $ul.find('[data-filter-type="code"]');
        } else if (filterType == 'name') {
            $item = $ul.find('[data-filter-type="name"]');
        } else {
            $item = $ul.find('[data-filter-path="'+filterType+'"]');
        }

        if ($item.length) {
            bpDetailACModeToggle($item[0]);
        }
    }
    return;
}
function bpAddAutoCompleteFilterType(mainSelector, elem, groupPath, fieldPath, labelName) {
    var $acControl = mainSelector.find("div.bp-add-ac-row[data-action-path='"+groupPath+"']");
    if ($acControl.length) {
        var $ul = $acControl.find('ul.dropdown-menu');
        if ($ul.find('[data-filter-path="'+fieldPath+'"]').length == 0) {
            var addItem = '<li><a href="javascript:;" onclick="bpDetailACModeToggle(this);" data-filter-path="'+fieldPath+'">'+plang.get(labelName)+'</a></li>';
            $ul.append(addItem);
        }
    }
    return;
}
function bpCalcFooter(mainSelector, elem, groupPath) {
    var $el = mainSelector.find('table[data-table-path="'+groupPath+'"] > thead > tr > th[data-aggregate]');
    
    if ($el.length) {
            
        var $len = $el.length, $i = 0;
        
        for ($i; $i < $len; $i++) { 
            
            var $row = $($el[$i]);
            var $funcName = $row.attr('data-aggregate');
            var $path = $row.attr('data-cell-path');
            
            if ($row.hasAttr('data-pivot-colcode')) {
                var pivotGroupNum = $row.attr('data-pivot-colcode');
                var $gridBody = mainSelector.find('table.bprocess-table-dtl > tbody > tr:not(.removed-tr) > td[data-group-num="'+pivotGroupNum+'"][data-cell-path="' + $path + '"]');
                var $footCell = mainSelector.find('table.bprocess-table-dtl > tfoot > tr > td[data-pivot-colcode="'+pivotGroupNum+'"][data-cell-path="' + $path + '"]');
            } else {
                var $gridBody = mainSelector.find('table.bprocess-table-dtl > tbody > tr:not(.removed-tr) > td[data-cell-path="' + $path + '"]');
                var $footCell = mainSelector.find('table.bprocess-table-dtl > tfoot > tr > td[data-cell-path="' + $path + '"]');
            }
            
            if ($funcName === 'sum') {
                if ($gridBody.eq(0).find('input[type="text"]').hasClass('bigdecimalInit')) {
                    var $sum = $gridBody.find('input[type="hidden"][data-path*="_bigdecimal"]').sum();
                } else {
                    var $sum = $gridBody.find('input[type="text"]').sum();
                }
                $footCell.autoNumeric('set', $sum);
                
            } else if ($funcName == 'avg') {
                
                var $avg = mainSelector.find('table.bprocess-table-dtl > tbody > tr:not(.removed-tr) > td[data-cell-path="' + $path + '"] input[type="text"]').avg();
                $footCell.autoNumeric('set', $avg);
                
            } else if ($funcName == 'max') {
                
                var $max = mainSelector.find('table.bprocess-table-dtl > tbody > tr:not(.removed-tr) > td[data-cell-path="' + $path + '"] input[type="text"]').max();
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
    
    return;
}
function bpSetScaleFooter(mainSelector, footerPath, scaleNum) {
    
    var $cell = mainSelector.find('tfoot > tr > td[data-cell-path="'+footerPath+'"]');
    
    if ($cell.length) {
        var setOption = JSON.parse('{"mDec": '+scaleNum+'}');
        $cell.autoNumeric('update', setOption);
    }
    
    return;
}
function bpSetMaxlength(mainSelector, elem, fieldPath, length) {
    var $bpElem = getBpElement(mainSelector, elem, fieldPath);
    
    if (typeof $bpElem == 'undefined' || $bpElem == false) {
        return;
    }
    
    var $tagName = $bpElem.prop('tagName');
    
    if ($tagName === 'INPUT' || $tagName === 'TEXTAREA') {
        
        $bpElem.attr({
            "data-maxlength": "true", 
            "maxlength": length
        });
        
        $bpElem.maxlength({
            warningClass: "badge badge-success",
            limitReachedClass: "badge badge-danger"
        });
    }
    
    return;
}
function bpSetUnMaxlength(mainSelector, elem, fieldPath) {
    var $bpElem = getBpElement(mainSelector, elem, fieldPath);
    
    if (typeof $bpElem == 'undefined' || $bpElem == false) {
        return;
    }
    
    $bpElem.removeAttr('data-maxlength maxlength');
    $bpElem.maxlength('destroyed');
        
    return;
}
function bpSetMinDate(mainSelector, elem, fieldPath, minDate) {
    var $bpElem = getBpElement(mainSelector, elem, fieldPath);
    
    if (typeof $bpElem == 'undefined' || $bpElem == false) {
        return;
    }
    
    if ($bpElem.hasClass('dateInit')) {
        if (minDate == '-1') {
            $bpElem.datepicker('setStartDate', null).removeAttr('data-mindate');
        } else {
            $bpElem.datepicker('setStartDate', new Date(minDate)).attr('data-mindate', minDate);
        }
    }
    
    return;
}
function bpSetMaxDate(mainSelector, elem, fieldPath, maxDate) {
    var $bpElem = getBpElement(mainSelector, elem, fieldPath);
    
    if (typeof $bpElem == 'undefined' || $bpElem == false) {
        return;
    }
    
    if ($bpElem.hasClass('dateInit')) {
        if (maxDate == '-1') {
            $bpElem.datepicker('setEndDate', null).removeAttr('data-maxdate');
        } else {
            $bpElem.datepicker('setEndDate', new Date(maxDate)).attr('data-maxdate', maxDate);
        }
    }
    
    return;
}
function bpRound(num) {
    return Number(Math.round(num+'e'+round_scale)+'e-'+round_scale);
}
function bpGetLookupFieldValue(mainSelector, elem, lookupfield, column) {
    try {
        var $bpElem = getBpElement(mainSelector, elem, lookupfield);

        if ($bpElem === false) {
            $bpElem = getBpRowViewElem(mainSelector, elem, lookupfield);
        }

        if ($bpElem && typeof $bpElem.attr('data-row-data') !== 'undefined') {
                
            if (typeof $bpElem.attr('data-view-path') !== 'undefined') {
                var rowData = $bpElem.attr('data-row-view-data');
            } else if ($bpElem.prop('tagName') == 'SELECT' && typeof $bpElem.find('option:selected').attr('data-row-data') !== 'undefined') {
                
                if ($bpElem.attr('name').indexOf('mvParam[') !== -1 && $bpElem.hasClass('mv-ind-combo')) {
                    /*түр шийдэл*/
                    var lookupRowData = JSON.parse($bpElem.attr('data-row-data'));
                    var response = $.ajax({
                        type: 'post',
                        url: 'mdform/autoCompleteById', 
                        data: {lookupId: lookupRowData.META_DATA_ID, isName: 'idselect', code: $bpElem.val()},
                        dataType: 'json',
                        async: false
                    });
                    var responseObj = response.responseJSON;
                    if (responseObj && responseObj.hasOwnProperty('rowData')) {
                        var realRowData = responseObj.rowData;
                        var rowData = Object.fromEntries(
                            Object.entries(realRowData).map(([key, val]) => [key.toLowerCase(), val])
                        );
                    } else {
                        var rowData = {};
                    }
                } else {
                    var rowData = $bpElem.find('option:selected').attr('data-row-data');
                }
                
            } else if ($bpElem.prop('type') == 'radio' && typeof $bpElem.closest('.radio-list').find('span.checked > input').attr('data-row-data') !== 'undefined') {
                var rowData = $bpElem.closest('.radio-list').find('span.checked > input').attr('data-row-data');
            } else if ($bpElem.hasClass('rangeSliderInit')) {
                var selectedVal = $bpElem.val();
                var rowData = '';

                if (selectedVal != '') {

                    var idField = $bpElem.attr('data-idfield');
                    var rowDatas = $bpElem.attr('data-row-data');

                    if (typeof rowDatas !== 'object') {
                        var jsonObj = JSON.parse(html_entity_decode(rowDatas, 'ENT_QUOTES'));
                    } else {
                        var jsonObj = rowDatas;
                    }

                    if (jsonObj) {
                        for (var j in jsonObj) {
                            if (jsonObj[j][idField] == selectedVal) {
                                rowData = jsonObj[j];
                                break;
                            }
                        }
                    }
                }
            } else {
                var rowData = $bpElem.attr('data-row-data');
            }
            
            if (rowData !== '') {
                if (typeof rowData !== 'object') {
                    var jsonObj = JSON.parse(html_entity_decode(rowData, "ENT_QUOTES"));
                } else {
                    var jsonObj = rowData;
                }
		
                var lowerColumn = column.toLowerCase(); 
                
                if (lowerColumn in Object(jsonObj)) {
                    return jsonObj[lowerColumn];
                } else {
                    var upperColumn = column.toUpperCase(); 
                    if (upperColumn in Object(jsonObj)) {
                        return jsonObj[upperColumn];
                    }
                }
            }
        }
        
        return null;
        
    } catch(e) {
        console.log(e);
        return null;
    }    
}
function bpGetLookupFieldMultiValue(mainSelector, elem, lookupfield, column, aggregateCode) {
    var resultVal = (aggregateCode == 'sum') ? 0 : '';
    
    try {
        var $bpElem = getBpElement(mainSelector, elem, lookupfield);
        
        if ($bpElem === false) {
            $bpElem = getBpRowViewElem(mainSelector, elem, lookupfield);
        }

        if ($bpElem && typeof $bpElem.attr('data-row-data') !== 'undefined') {
            
            if (typeof $bpElem.attr('data-view-path') !== 'undefined') {
                var $rowData = $bpElem;
            } else if ($bpElem.prop('tagName') == 'SELECT') {
                if (typeof $bpElem.find('option:selected').attr('data-row-data') !== 'undefined') {
                    var $rowData = $bpElem.find('option:selected');
                } else {
                    var $rowData = $();
                }
            } else if ($bpElem.prop('type') == 'radio') {
                if (typeof $bpElem.closest('.radio-list').find('span.checked > input').attr('data-row-data') !== 'undefined') {
                    var $rowData = $bpElem.closest('.radio-list').find('span.checked > input');
                } else {
                    var $rowData = $();
                }
            } else if ($bpElem.prop('type') == 'checkbox') {
                if (typeof $bpElem.closest('.radio-list').find('span.checked > input').attr('data-row-data') !== 'undefined') {
                    var $rowData = $bpElem.closest('.radio-list').find('span.checked > input');
                } else {
                    var $rowData = $();
                }
            } else {
                var $rowData = $bpElem;
            }
            
            if ($rowData.length) {
                
                var lowerColumn = column.toLowerCase(); 
                aggregateCode = aggregateCode.toLowerCase();
                
                $rowData.each(function() {
                    var $this = $(this), rowData = $this.attr('data-row-data');
                    
                    if (rowData !== '') {
                        if (typeof rowData !== 'object') {
                            var jsonObj = JSON.parse(html_entity_decode(rowData, 'ENT_QUOTES'));
                        } else {
                            var jsonObj = rowData;
                        }
                        
                        if (jsonObj.hasOwnProperty(0)) {
                            
                            for (var j in jsonObj) {
                                
                                var jsonObjRow = jsonObj[j];
                                
                                if (lowerColumn in Object(jsonObjRow)) {

                                    if (aggregateCode == 'sum') {
                                        resultVal += Number(jsonObjRow[lowerColumn]);
                                    } else if (jsonObjRow[lowerColumn] != '') {
                                        resultVal += jsonObjRow[lowerColumn] + aggregateCode;
                                    } 
                                }
                            }
                            
                        } else {

                            if (lowerColumn in Object(jsonObj)) {

                                if (aggregateCode == 'sum') {
                                    resultVal += Number(jsonObj[lowerColumn]);
                                } else if (jsonObj[lowerColumn] != '') {
                                    resultVal += jsonObj[lowerColumn] + aggregateCode;
                                } 
                            }
                        }
                    }
                });
                
                if (aggregateCode != 'sum') {
                    resultVal = rtrim(resultVal, aggregateCode);
                }
            }
        }
        
        return resultVal;
    } catch(e) {
        console.log(e);
        return resultVal;
    }    
}
function bpGetCallerType(mainSelector) {
    var openParams = mainSelector.find("input[id='openParams']").val();
    if (openParams !== '') {
        openParams = JSON.parse(openParams.replace('&quot;', '"'));
        return (openParams.hasOwnProperty('callerType')) ? openParams.callerType : null; 
    } 
    return null;
}
function bpRunProcessValue(mainSelector, elem, processCode, paramsPath, responsePath) {
    var paramData = [], paramsPathArr = paramsPath.split('|');

    for (var i = 0; i < paramsPathArr.length; i++) {
        var fieldPathArr = paramsPathArr[i].split('@');
        var fieldPath = fieldPathArr[0].trim();
        var inputPath = fieldPathArr[1].trim();
        var fieldValue = '';

        var $bpElem = getBpElement(mainSelector, elem, fieldPath);
        var $bpViewElem = getBpRowViewElem(mainSelector, elem, fieldPath);

        if ($bpElem || $bpViewElem) {
            fieldValue = getBpRowParamNum(mainSelector, elem, fieldPath);
        } else {
            fieldValue = fieldPath;
        }

        paramData.push({
            fieldPath: fieldPath, 
            inputPath: inputPath, 
            value: fieldValue
        });
    }

    var response = $.ajax({
        type: 'post',
        url: 'mdwebservice/runProcessValue', 
        data: {
            processCode: processCode, 
            responsePath: responsePath, 
            paramData: paramData
        },
        dataType: 'json',
        async: false
    });

    return response.responseJSON;
}
function bpGetProcessParam(mainSelector, elem, processCode, paramsPath, isShowErrorMsg) {
    var paramData = [];
    var paramsPathArr = paramsPath.split('|');
    var paramsLength = paramsPathArr.length;
    var isShowErrorMessage = false;
    
    for (var i = 0; i < paramsLength; i++) {
        var fieldPathArr = paramsPathArr[i].split('@');
        var fieldPath = fieldPathArr[0].trim();
        var inputPath = fieldPathArr[1].trim();
        var fieldValue = '';

        var $bpElem = getBpElement(mainSelector, elem, fieldPath);

        if ($bpElem) {
            if ($bpElem.hasClass('base64Init') || $bpElem.hasClass('fileInit')) {
                var fileUrl = $bpElem.val();
                if (fileUrl) {
                    var ext = fileUrl.substring(fileUrl.lastIndexOf('.') + 1).toLowerCase();
                    var formData = new FormData();
                    formData.append('file_1', $bpElem.get(0).files[0]); 
                    $.ajax({
                        type: 'post',
                        url: 'api/getBase64FromFile',
                        data: formData,
                        processData: false,
                        contentType: false,
                        async: false,
                        success: function(data) {
                            fieldValue = ext + '♠' + data;
                        }
                    });
                }
            } else {
                fieldValue = getBpRowParamNum(mainSelector, elem, fieldPath);
            }
        } else {
            fieldValue = fieldPath;
        }

        paramData.push({
            fieldPath: fieldPath, 
            inputPath: inputPath, 
            value: fieldValue
        });
    }
    
    var postData = {
        processCode: processCode, 
        paramData: paramData
    };
    
    if (typeof isShowErrorMsg !== 'undefined' && isShowErrorMsg) {
        isShowErrorMessage = true;
        postData['isShowErrorMsg'] = 1;
    }

    var response = $.ajax({
        type: 'post',
        url: 'mdwebservice/getProcessParam', 
        data: postData, 
        dataType: 'json',
        async: false
    });
    var result = response.responseJSON;
    
    if (isShowErrorMessage && isObject(result) && result.hasOwnProperty('exceptionStatus')) {
        PNotify.removeAll();
        new PNotify({
            title: 'Error',
            text: result.message,
            type: 'error',
            addclass: pnotifyPosition,
            sticker: false
        });    
        return null;
    }
    
    return result;
}
function bpGetDataViewParam(mainSelector, elem, processCode, paramsPath) {
    var paramData = [];
    var paramsPathArr = paramsPath.split('|');

    for (var i = 0; i < paramsPathArr.length; i++) {
        var fieldPathArr = paramsPathArr[i].split('@');
        var fieldPath = fieldPathArr[0].trim();
        var inputPath = fieldPathArr[1].trim();
        var fieldValue = '';

        var $bpElem = getBpElement(mainSelector, elem, fieldPath);

        if ($bpElem) {
            fieldValue = getBpRowParamNum(mainSelector, elem, fieldPath);
        } else {
            fieldValue = fieldPath;
        }

        paramData.push({
            fieldPath: fieldPath, 
            inputPath: inputPath, 
            value: fieldValue
        });
    }

    var response = $.ajax({
        type: 'post',
        url: 'mdwebservice/getProcessParam', 
        data: {
            processCode: processCode, 
            paramData: paramData
        },
        dataType: 'json',
        async: false
    });

    return response.responseJSON;
}
function bpGetIndicatorParam(mainSelector, elem, processCode, paramsPath, isShowErrorMsg) {
    var paramData = [];
    var paramsPathArr = paramsPath.split('|');
    var paramsLength = paramsPathArr.length;
    var isShowErrorMessage = false;
    
    for (var i = 0; i < paramsLength; i++) {
        var fieldPathArr = paramsPathArr[i].split('@');
        var fieldPath = fieldPathArr[0].trim();
        var inputPath = fieldPathArr[1].trim();
        var fieldValue = '';

        var $bpElem = getBpElement(mainSelector, elem, fieldPath);

        if ($bpElem) {
            if ($bpElem.hasClass('base64Init') || $bpElem.hasClass('fileInit')) {
                var fileUrl = $bpElem.val();
                if (fileUrl) {
                    var ext = fileUrl.substring(fileUrl.lastIndexOf('.') + 1).toLowerCase();
                    var formData = new FormData();
                    formData.append('file_1', $bpElem.get(0).files[0]); 
                    $.ajax({
                        type: 'post',
                        url: 'api/getBase64FromFile',
                        data: formData,
                        processData: false,
                        contentType: false,
                        async: false,
                        success: function(data) {
                            fieldValue = ext + '♠' + data;
                        }
                    });
                }
            } else {
                fieldValue = getBpRowParamNum(mainSelector, elem, fieldPath);
            }
        } else {
            fieldValue = fieldPath;
        }

        paramData.push({
            fieldPath: fieldPath, 
            inputPath: inputPath, 
            value: fieldValue
        });
    }
    
    var postData = {
        processCode: processCode, 
        paramData: paramData
    };
    
    if (typeof isShowErrorMsg !== 'undefined' && isShowErrorMsg) {
        isShowErrorMessage = true;
        postData['isShowErrorMsg'] = 1;
    }

    var response = $.ajax({
        type: 'post',
        url: 'mdform/getIndicatorParam', 
        data: postData, 
        dataType: 'json',
        async: false
    });
    var result = response.responseJSON;
    
    if (isShowErrorMessage && isObject(result) && result.hasOwnProperty('exceptionStatus')) {
        PNotify.removeAll();
        new PNotify({
            title: 'Error',
            text: result.message,
            type: 'error',
            addclass: pnotifyPosition,
            sticker: false
        });    
        return null;
    }
    
    return result;
}
function bpCheckDataPermission(objectCode, actionCode, recordId) {
    var result = false, actionId = '300101010000005';
    
    if (actionCode == 'get') {
        actionId = '300101010000004';
    } else if (actionCode == 'create') {
        actionId = '300101010000001';
    } else if (actionCode == 'update') {
        actionId = '300101010000002';
    } else if (actionCode == 'delete') {
        actionId = '300101010000003';
    }
    
    $.ajax({
        type: 'post',
        url: 'mdcommon/checkDataPermissionByPost',
        data: {objectCode: objectCode, actionId: actionId, recordId: recordId},
        async: false,
        success: function(data) {
            if (data === 'true') {
                result = true;
            }
        }
    });
    return result;
}
function bpRunWidget(mainSelector, elem, uniqId, widgetCode, paramsPath) {
    var uniqId = uniqId.replace('_', '');
    var paramData = [];
    
    if (typeof paramsPath !== 'undefined') {
        var paramsPathArr = paramsPath.split('|');

        for (var i = 0; i < paramsPathArr.length; i++) {
            var fieldPathArr = paramsPathArr[i].split('@');
            var fieldPath = fieldPathArr[0].trim();
            var inputPath = fieldPathArr[1].trim();
            var fieldValue = '';

            var $bpElem = getBpElement(mainSelector, elem, fieldPath);

            if ($bpElem) {
                fieldValue = getBpRowParamNum(mainSelector, elem, fieldPath);
            } else {
                fieldValue = fieldPath;
            }

            paramData.push({
                fieldPath: fieldPath, 
                inputPath: inputPath, 
                value: fieldValue
            });
        }
    }
    
    var $widgetContainer = mainSelector.find('.bp-checklist-table-cell-right');
            
    if ($widgetContainer.find('#widgetExchangeRate_'+uniqId).length) {
        
        $.ajax({
            type: 'post',
            url: 'mdwidget/getWidgetDataSource',
            data: {widgetCode: widgetCode, paramData: paramData},
            dataType: 'json', 
            success: function(data) {
                
                $widgetContainer.find('#widgetExchangeRate_'+uniqId).find('#currency-name').text(data.title);
                
                window['widgetChart_'+uniqId].dataProvider = data.widgetData;    
                window['widgetChart_'+uniqId].validateData();
            }
        });
        
    } else {
        
        $.ajax({
            type: 'post',
            url: 'mdwidget/runWidget',
            data: {widgetCode: widgetCode, uniqId: uniqId, paramData: paramData},
            dataType: 'json', 
            success: function(data) {
                $widgetContainer.append(data.html);
            }
        });
    }
    
    return;
}
function bpCacheCallExpression(mainSelector, cacheId, processId, expCode, groupPath) {
    
    if (expCode !== '') {
        
        var result = false;
        var $getTableBody = mainSelector.find('table[data-table-path="'+groupPath+'"] > tbody');
        
        var $lookupInputs = $getTableBody.find('input.popupInit, select.select2');
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
                rowObj['rowdata'] = ($rowData !== '' ? JSON.parse(html_entity_decode($rowData, "ENT_QUOTES")) : '');
                
                objs[$n] = rowObj;
            }
        }
        
        $.ajax({
            type: 'post',
            url: 'mdcache/allRowsCalculate',
            data: {
                processId: processId, 
                cacheId: cacheId.replace('_', ''), 
                code: expCode, 
                groupPath: groupPath, 
                params: $getTableBody.find('input, select, textarea').serialize(), 
                lookupParams: objs, 
                headerData: mainSelector.find('div.bp-header-param').find('input, select').serialize()
            }, 
            async: false,
            success: function(data) {
                if (data === 'success') {
                    result = true;
                }
            }
        });
        
        if (result) {
            var $pagerRefreshBtn = mainSelector.find('div[data-pg-grouppath="'+groupPath+'"] > .pf-bp-pager-buttons > .pf-bp-pager-refresh');
            
            $pagerRefreshBtn.attr('data-ignore-modify', '1');
            bpDetailPagerRefresh($pagerRefreshBtn);
            $pagerRefreshBtn.removeAttr('data-ignore-modify');
            
            return true;
        }
    }
    
    return false;
}
function bpCacheCallExpressionVar(mainSelector, cacheId, processId, expCode, groupPath, varNames) {
    
    if (expCode !== '') {
        
        var result = {};
        var $getTableBody = mainSelector.find('table[data-table-path="'+groupPath+'"] > tbody');
        
        var $lookupInputs = $getTableBody.find('input.popupInit');
        var $lookupInputsLen = $lookupInputs.length, $n = 0;
        var objs = {}, rowObj = {};
                
        for ($n; $n < $lookupInputsLen; $n++) { 
            var $lookupInput = $($lookupInputs[$n]);
            
            if ($lookupInput.val() !== '') {
                
                rowObj = {};
                
                var $row = $lookupInput.parents('tr');
                var $parent = $lookupInput.closest('.double-between-input');
                
                var $id = $lookupInput.val();
                var $code = $parent.find('input[id*="_displayField"]').val();
                var $name = $parent.find('input[id*="_nameField"]').val();
                var $rowData = $lookupInput.attr('data-row-data');
                var $rowId = $row.find('input[name*=".mainRowCount"]').val();
                
                var $getPath = $lookupInput.attr('data-path');
                
                rowObj['rowId'] = $rowId;
                rowObj['path'] = $getPath.toLowerCase();
                
                rowObj['id'] = $id;
                rowObj['code'] = $code;
                rowObj['name'] = $name;
                rowObj['rowdata'] = ($rowData !== '' ? JSON.parse($rowData) : '');
                
                objs[$n] = rowObj;
            }
        }
        
        $.ajax({
            type: 'post',
            url: 'mdcache/allRowsCalculate',
            data: {
                processId: processId, 
                cacheId: cacheId.replace('_', ''), 
                code: expCode, 
                groupPath: groupPath, 
                params: $getTableBody.find('input, select, textarea').serialize(), 
                lookupParams: objs, 
                headerData: mainSelector.find('div.bp-header-param').find('input, select').serialize(), 
                varNames: varNames
            }, 
            dataType: 'json',  
            async: false,
            success: function(data) {
                if (data.status == 'success') {
                    result = data;
                }
            }
        });
    }
    
    return result;
}
function bpPagerDetailReload(mainSelector, groupPath) {
    var $pagerRefreshBtn = mainSelector.find('div[data-pg-grouppath="'+groupPath+'"] > .pf-bp-pager-buttons > .pf-bp-pager-refresh');
    
    isAsyncCacheGrid = false;
    bpDetailPagerRefresh($pagerRefreshBtn);
    isAsyncCacheGrid = true;
        
    return;
}
function bpSetScaleBigDecimal(mainSelector, elem, fieldPath, scaleNum) {
    var $bpElem = getBpElement(mainSelector, elem, fieldPath);
    
    if (typeof $bpElem == 'undefined' || $bpElem == false) {
        return;
    }
    
    $bpElem.attr('data-mdec', scaleNum+'.'+scaleNum);
    $bpElem.autoNumeric('update', {"mDec": scaleNum});
    $bpElem.autoNumeric('set', $bpElem.next("input[type=hidden]").val());
    
    return;
}
function bpDetailRowNumbering(tbl) { 
    var $el = tbl.find('> .tbody > .bp-detail-row:visible');
    var len = $el.length, i = 0;
    for (i; i < len; i++) { 
        $($el[i]).find('td:first > span').text(i + 1);
    }
    return;
}
function bpSetFieldValueByVisibleRows(mainSelector, elem, fieldPath, val) {
    if (fieldPath) {
        var fieldPathArr = fieldPath.split('.');
        var groupPath = fieldPathArr[0];
        var $table = mainSelector.find('[data-table-path="'+groupPath+'"]');
        if ($table.length) {
            var $rows = $table.find('> .tbody > .bp-detail-row:visible');
            if ($rows.length) {
                var $getPathElement = $rows.find("[data-path='" + fieldPath + "']");
                if ($getPathElement.length) {
                    if ($getPathElement.prop('tagName') == 'SELECT') {
                        if ($getPathElement.hasClass('select2')) {
                            if (val == null || val == '') {
                                $getPathElement.select2('val', '');
                            } 
                            if ($getPathElement.find('option').length > 2) {
                                $getPathElement.select2('val', val);
                            } else if ($getPathElement.attr('data-row-data') !== "undefined") {
                                comboSingleDataSet($getPathElement, val);
                            }
                        } else {
                            $getPathElement.trigger('blur');
                            $getPathElement.find('option').filter('[value="' + val + '"]').attr('selected', 'selected');
                        }
                    } else {
                        if ($getPathElement.hasClass('longInit') 
                            || $getPathElement.hasClass('numberInit') 
                            || $getPathElement.hasClass('decimalInit') 
                            || $getPathElement.hasClass('integerInit')) {

                            if (val == null) {
                                $getPathElement.autoNumeric('set', '');
                            } else {
                                $getPathElement.autoNumeric('set', val);
                            } 

                        } else if ($getPathElement.hasClass('bigdecimalInit')) {               
                            $getPathElement.next("input[type=hidden]").val(setNumberToFixed(val));
                            $getPathElement.autoNumeric('set', val);      
                        } else if ($getPathElement.hasClass('dateInit')) {
                            if (val !== '' && val !== null) {
                                $getPathElement.datepicker('update', date('Y-m-d', strtotime(val)));
                            } else {
                                $getPathElement.datepicker('update', null);
                            }
                        } else if ($getPathElement.hasClass('datetimeInit')) {
                            if (val !== '' && val !== null) {
                                $getPathElement.val(date('Y-m-d H:i:s', strtotime(val)));
                            } else {
                                $getPathElement.val('');
                            }
                        } else if ($getPathElement.hasClass('popupInit')) {   
                            setLookupPopupValue($getPathElement, val);
                        } else if ($getPathElement.hasClass('booleanInit')) {   
                            checkboxCheckerUpdate($getPathElement, val);
                        } else if ($getPathElement.hasClass('radioInit')) {   
                            radioButtonCheckerUpdate($getPathElement, val);
                        } else if ($getPathElement.hasClass('iconInit')) {   
                            setIconInput($getPathElement, val);
                        } else {                                            
                            $getPathElement.val(val);                        
                        }
                    }
                }
            }
        }
    }
    return;
}
function bpDetailRowOrdering(tbl) { 
    var $el = tbl.find('> .tbody > .bp-detail-row:not(.removed-tr):not(.d-none)');
    var len = $el.length, i = 0;
    for (i; i < len; i++) { 
        $($el[i]).find('td:first > span').text(i + 1);
    }
    return;
}
function bpSetRowIndexing(tbl) {

    var $el = tbl.find('> .tbody > .bp-detail-row');
    var len = $el.length, i = 0;
    
    for (i; i < len; i++) { 
        var $subElement = $($el[i]).find('input, select, textarea');
        var slen = $subElement.length, j = 0;
        for (j; j < slen; j++) { 
            var $inputThis = $($subElement[j]);
            var $inputName = $inputThis.attr('name');
            if (typeof $inputName !== 'undefined') {
                $inputThis.attr('name', $inputName.replace(/^(.*)(\[[0-9]+\])(.*)$/, '$1[' + i + ']$3'));
            }
        }
    }
    
    return;
}
function bpSetRowIndexingByGroup(mainSelector, groupPath) {
    var $table = mainSelector.find('[data-table-path="'+groupPath+'"]');
    bpSetRowIndexing($table);
    return;
}
function bpRemoveDuplicateRows(mainSelector, elem, groupPath, fieldPath) {
    
    var $table = mainSelector.find('[data-table-path="'+groupPath+'"]');
    
    if ($table.find('> .tbody > .bp-detail-row:not(.removed-tr)').length) {
        
        var fieldPaths = '';
        
        if (fieldPath.indexOf(',') !== -1) {
        
            var fieldPathArr = fieldPath.split(',');

            for (var i = 0; i < fieldPathArr.length; i++) {
                fieldPaths += '[data-path="'+groupPath+'.'+fieldPathArr[i].trim()+'"],';
            }
            
            fieldPaths = rtrim(fieldPaths, ',');
            
            function getVisibleRowValue($row, fieldPath) {
                
                var values = $row.find(fieldPath).map(function() {
                    return this.value;
                }).get();

                return values.join();
            }
        
        } else {
            fieldPaths = '[data-path="'+groupPath+'.'+fieldPath+'"]';
            
            function getVisibleRowValue($row, fieldPath) {
                return $row.find(fieldPath).val();
            }
        }

        $table.find('> .tbody > .bp-detail-row:not(.removed-tr)').each(function(index, row){
            var $row = $(row), $firstValue = getVisibleRowValue($row, fieldPaths);
            
            if ($firstValue != '') {
                $row.nextAll('.bp-detail-row:not(.removed-tr)').each(function(index, next){
                    var $next = $(next), $nextValue = getVisibleRowValue($next, fieldPaths);
                    
                    if ($nextValue != '' && $firstValue == $nextValue) {
                        $next.remove();
                    }
                });
            }
        });
        
        if (!$table.hasClass('bprocess-table-subdtl')) {
            bpSetRowIndexing($table);
            bpDetailRowNumbering($table);
        }
    }
    return;
}
function bpHideDuplicateRows(mainSelector, elem, groupPath, fieldPath) {
    
    var $table = mainSelector.find('[data-table-path="'+groupPath+'"]');
    
    if ($table.find('> .tbody > .bp-detail-row').length) {
        
        fieldPath = groupPath+'.'+fieldPath;
        
        function getVisibleRowValue($row, fieldPath) {
            return $row.find('[data-path="'+fieldPath+'"]').val();
        }

        $table.find('> .tbody > .bp-detail-row').each(function(index, row){
            var $row = $(row);
            var $firstValue = getVisibleRowValue($row, fieldPath);
            
            if ($firstValue != '') {
                $row.nextAll('tr').each(function(index, next){
                    var $next = $(next);
                    var $nextValue = getVisibleRowValue($next, fieldPath);
                    if ($nextValue != '' && $firstValue == $nextValue) {
                        $next.css({'display': 'none'});
                    }
                });
            }
        });
        
        bpDetailRowNumbering($table);
    }
    return;
}
function bpStyleDuplicateRows(mainSelector, elem, groupPath, fieldPath, styles) {
    var $table = mainSelector.find('[data-table-path="'+groupPath+'"]'), result = 0;
    
    if ($table.find('> .tbody > .bp-detail-row:visible').length) {
        
        fieldPath = groupPath+'.'+fieldPath;
        
        function getVisibleRowValue($row, fieldPath) {
            return $row.find('[data-path="'+fieldPath+'"]').val();
        }
        
        $table.find('> .tbody input[data-set-styles]').removeAttr('style data-set-styles');
        
        if ($table.find('> .tbody > .bp-detail-row:visible:eq(0)').find('[data-path="'+fieldPath+'"]').hasClass('popupInit')) {
            
            $table.find('> .tbody > .bp-detail-row:visible').each(function(index, row){
                var $row = $(row);
                var $firstValue = getVisibleRowValue($row, fieldPath);

                if ($firstValue != '') {
                    $row.nextAll('.bp-detail-row:visible').each(function(index, next){
                        var $next = $(next);
                        var $nextValue = getVisibleRowValue($next, fieldPath);
                        if ($nextValue != '' && $firstValue == $nextValue) {
                            $next.closest('.bp-detail-row').find("div.meta-autocomplete-wrap[data-section-path='" + fieldPath + "']").find('input.meta-autocomplete, input.meta-name-autocomplete').attr({'style': styles, 'data-set-styles': '1'});
                            result += 1;
                        }
                    });
                }
            });
            
        } else {
            $table.find('> .tbody > .bp-detail-row:visible').each(function(index, row){
                var $row = $(row);
                var $firstValue = getVisibleRowValue($row, fieldPath);

                if ($firstValue != '') {
                    $row.nextAll('.bp-detail-row:visible').each(function(index, next){
                        var $next = $(next);
                        var $nextValue = getVisibleRowValue($next, fieldPath);
                        if ($nextValue != '' && $firstValue == $nextValue) {
                            $next.attr({'style': styles, 'data-set-styles': '1'});
                            result += 1;
                        }
                    });
                }
            });
        }
    }
    return result;
}
function bpCountDuplicateRows(mainSelector, elem, groupPath, fieldPath) {
    
    var $table = mainSelector.find('[data-table-path="'+groupPath+'"]'), count = 0;
    
    if ($table.find('> .tbody > .bp-detail-row').length) {
        
        fieldPath = groupPath+'.'+fieldPath;
        
        function getVisibleRowValue($row, fieldPath) {
            return $row.find('[data-path="'+fieldPath+'"]').val();
        }

        $table.find('> .tbody > .bp-detail-row').each(function(index, row){
            var $row = $(row), 
                $firstValue = getVisibleRowValue($row, fieldPath);
            
            if ($firstValue != '') {
                $row.nextAll('.bp-detail-row').each(function(index, next){
                    var $next = $(next), 
                        $nextValue = getVisibleRowValue($next, fieldPath);
                    if ($nextValue != '' && $firstValue == $nextValue) {
                        count++;
                    }
                });
            }
        });
    }
    return count;
}
function bpDetailRowsShow(mainSelector, elem, groupPath) {
    var $table = mainSelector.find('[data-table-path="'+groupPath+'"]');
    
    if ($table.find('> .tbody > .bp-detail-row').length) {
        $table.find('> .tbody > .bp-detail-row').css({'display': ''});
        bpDetailRowNumbering($table);
    }
    return;
}
function bpRemoveCriteriaRows(mainSelector, groupPath, criterias) {
    /* [id] == null */
    var $table = mainSelector.find('[data-table-path="'+groupPath+'"]');
    
    if ($table.find('> .tbody > .bp-detail-row').length) {
        
        var criteriasMatch = criterias.match(/\[(.*?)\]/g), fieldPath = '';
        criteriasMatch = criteriasMatch.map(function(match) { return match.slice(1, -1); });

        for (var i = 0; i < criteriasMatch.length; i++) {
            fieldPath = criteriasMatch[i];
        }
        
        fieldPath = groupPath+'.'+fieldPath;
        
        var $detectedRows = $table.find("input[data-path='"+fieldPath+"']").filter(function() { return this.value == '0.00' || this.value == ''; }); 
        
        if ($detectedRows.length) {
            $detectedRows.parents('.bp-detail-row').remove();
            
            bpDetailRowNumbering($table);
            bpSetRowIndexing($table);
        }
    }
    return;
}
function bpIgnoreHdrDtlAutoChange(mainSelector, fieldNames) {
    var fieldNamesArr = fieldNames.split(','), i = 0, fieldName = '', selectorNames = '';
    
    for (i; i < fieldNamesArr.length; i++) {
        fieldName = fieldNamesArr[i].trim();
        selectorNames += "[data-path='"+fieldName+"'], ";
    }
    
    $(rtrim(selectorNames, ', '), mainSelector).removeClass('group-dtl-linked');
    return;
}
function bpCallHdrDtlRelation(mainSelector, groupPath) {
    var $uniqId = mainSelector.attr('data-bp-uniq-id');
    if (mainSelector.find('[data-out-group="'+groupPath+'"]').length) {
        var $elem = mainSelector.find('[data-out-group="'+groupPath+'"]:eq(0)');
    } else {
        var $elem = mainSelector.find('[data-path]:eq(0)');
        $elem.attr({'data-out-group': groupPath, 'data-ignore-dvfilter': 1});
    }
    window['bpGroupLinkedDtl_'+$uniqId]($elem, false);
    return;
}

function transferSplitValueToDtlFunction(mainSelector, srcSplitPath, trgGroupPath, trgFillPath, $type, $removedRowState, $selector, $criteria, $isCommaValue, $realValue) {
    
    try {
        if (typeof $type !== 'undefined') {
            var getPathGroup = srcSplitPath.split('.');
            var $body = mainSelector.find('table[data-table-path="'+getPathGroup[0]+'"] > tbody > tr');
            $body.each(function ($index, $tr) {
                var $thisAutho = $($tr).find('select[data-path="'+ srcSplitPath +'"]').val();
                if ($thisAutho) {
                    var $spliAuthoryIds = $thisAutho;
                    var $parent = $($tr).find('table[data-table-path="'+ trgGroupPath +'"]').parent();

                    if ($spliAuthoryIds) {
                        var $mainTableBody = $($tr).find('table[data-table-path="'+ trgGroupPath +'"] > tbody');
                        var $mainCopyTr = '<tr class="bp-detail-row">' + $($tr).find('table[data-table-path="'+ trgGroupPath +'"] > tbody > tr:eq(0)').html() + '</tr>';
                        
                        $($tr).find('table[data-table-path="'+ trgGroupPath +'"] > tbody > tr').each(function(){
                            $(this).find('input[data-path="'+ trgGroupPath + '.rowState"]').val('removed');
                        });
                        
                        $.each($spliAuthoryIds, function (sindex, srow) {
                            var $sindex = sindex;

                            $mainTableBody.append($mainCopyTr).promise().done(function () {
                                if (srow !== '#') {
                                    var $rowThis = $($tr).find('table[data-table-path="'+ trgGroupPath +'"] > tbody > tr:eq('+ $sindex +')');
                                    $rowThis.find('input[data-path="'+ trgGroupPath + '.' + trgFillPath +'"]').val(srow);                                        
                                }
                            }); 
                        });
                    }
                }
            });
        } else {
                
            if (typeof $criteria !== 'undefined' && $criteria) {
                var $thisAutho = mainSelector.find('[data-path="'+srcSplitPath+'"]');
                var $selectedIds = $thisAutho.val();
                var $trgFillPathArr = trgFillPath.split('|');
                var $trgGroupPathArr = trgGroupPath.split('|');
                var $trgCriteria = $criteria.split('|');
                var $setFillValueArr = [];
                
                $.each($trgCriteria, function ($ic, $rc) {
                    $setFillValueArr[$ic] = [];
                });
                
                $.each($selectedIds, function ($index, $row) {
                    
                    if (typeof $thisAutho.find('option[value="'+ $row +'"]').attr('data-row-data') !== 'undefined') {
                        var $selectedRowData = JSON.parse($thisAutho.find('option[value="'+ $row +'"]').attr('data-row-data'));
                        
                        if (typeof $selectedRowData !== 'undefined' && $selectedRowData) {
                            $.each($trgCriteria, function ($ic, $rc) {
                                var evalcriteria = $rc.toLowerCase();
                                $.each($selectedRowData, function ($rci, $rcr) {
                                    
                                    if ($rc.indexOf($rci) > -1) {
                                        var regex = new RegExp('\\b' + $rci + '\\b', 'g');
                                        evalcriteria = evalcriteria.replace(regex, "'" + $rcr.toString() + "'");
                                        
                                        if (eval(evalcriteria)) {
                                            $setFillValueArr[$ic].push($row);
                                        }
                                    }
                                });
                            });
                        }
                    }
                });
                
                $.each($trgGroupPathArr, function ($index, $row) {
                    if ($setFillValueArr[$index]) {
                        var $parent = mainSelector.find('table[data-table-path="'+ $row +'"]').parent();
                        var $mainTableBody = mainSelector.find('table[data-table-path="'+ $row +'"] > tbody');
                        var $firstRow = $mainTableBody.find('> tr:eq(0)');
                        var $sindex = 1;

                        if ($firstRow.length == 0) {
                            bpAddRow(mainSelector, this, $trgGroupPathArr[$index], 1);
                            $firstRow = $mainTableBody.find('> tr:eq(0)');
                        }

                        var $mainCopyTr = '<tr class="bp-detail-row">' + $firstRow.html() + '</tr>';
                        
                        if (typeof $removedRowState !== 'undefined' && $removedRowState == '1') {
                            $mainTableBody.find('input[data-path="'+ $trgGroupPathArr[$index] +'.rowState"]').val('removed');
                            $sindex = mainSelector.find('table[data-table-path="'+ $trgGroupPathArr[$index] +'"] > tbody > tr').length;
                        } else {
                            $mainTableBody.find('tr:not(.saved-bp-row)').remove();
                            $mainTableBody.find('tr.saved-bp-row').find('input[data-path="'+ $trgGroupPathArr[$index] +'.rowState"]').val('removed');//.addClass('hidden')
                        }
                        
                        $.each($setFillValueArr[$index], function (sindex, srow) {

                            if (typeof $removedRowState !== 'undefined' && $removedRowState == '1') {

                                $mainTableBody.append($mainCopyTr).promise().done(function () {
                                    if (srow !== '#') {
                                        var $rowThis = mainSelector.find('table[data-table-path="'+ $trgGroupPathArr[$index] +'"] > tbody > tr:eq('+ $sindex +')');
                                        $rowThis.find('[data-path="'+ $trgGroupPathArr[$index] +'.'+ $trgFillPathArr[$index] +'"]').val(srow);
                                        $rowThis.find('input[data-path="'+ $trgGroupPathArr[$index] +'.rowState"]').val('added');
                                        $rowThis.find('input[data-path="'+ $trgGroupPathArr[$index] +'.id"]').val('');
                                    }
                                }); 

                            } else {

                                var $detectInput = $mainTableBody.find('input[data-path="'+ $trgGroupPathArr[$index] +'.'+ $trgFillPathArr[$index] +'"]').filter(function() { return ($(this).val() == srow); });

                                if ($detectInput.length == 0) {

                                    var $setInput;

                                    $mainTableBody.append($mainCopyTr).promise().done(function () {

                                        if (srow !== '#') {

                                            var $rowThis = mainSelector.find('table[data-table-path="'+ $trgGroupPathArr[$index] +'"] > tbody > tr:last');

                                            $setInput = $rowThis.find('input[data-path="'+ $trgGroupPathArr[$index] +'.'+ $trgFillPathArr[$index] +'"]');

                                            if ($setInput.length) {

                                                $setInput.val(srow);

                                                $rowThis.find('input[data-path="'+ $trgGroupPathArr[$index] +'.id"]').val('');
                                                $rowThis.find('input[data-path="'+ $trgGroupPathArr[$index] +'.rowState"]').val('added');
                                            }
                                        }
                                    }); 
                                } else {
                                    $detectInput.closest('tr').find('input[data-path="'+ $trgGroupPathArr[$index] +'.rowState"]').val('unchanged');
                                }
                            }

                            $sindex++;
                        });
                        
                        bpSetRowIndex($parent);
                    }
                });

            } else {
            
                var isCommaValue = true;
                var $thisAutho = mainSelector.find('.bprocess-table-dtl').find('select[data-path="'+ srcSplitPath +'"]').children().attr('value');
                
                if (typeof $realValue !== 'undefined' && $realValue === 'rows-rows') {
                    $thisAutho = mainSelector.find('[data-path="'+ srcSplitPath +'"]');
                }
                
                if (srcSplitPath.indexOf('.') === -1) {
                    
                    if (srcSplitPath.indexOf(',') !== -1) {
                        var srcSplitPathArr = srcSplitPath.split(','), srcSplitPaths = '';

                        for (var i = 0; i < srcSplitPathArr.length; i++) {
                            srcSplitPaths += '[data-path="'+srcSplitPathArr[i].trim()+'"], ';
                        }

                        $thisAutho = mainSelector.find(rtrim(srcSplitPaths, ', '));
                    } else {
                        $thisAutho = mainSelector.find('[data-path="'+srcSplitPath+'"]');
                    }
                    
                    isCommaValue = false;
                }
                
                if ($thisAutho) {
                    var $spliAuthoryIds = '';

                    if (typeof $thisAutho !== 'string' && ($thisAutho.hasClass('popupInit') || $thisAutho.hasClass('combogridInit'))) {
                        
                        if ($thisAutho.length == 1) {
                            var $spliAuthoryIds = ($thisAutho.val()).split(',');
                        } else {
                            var $spliAuthoryIds = $thisAutho.map(function(idx, elem){ return $(elem).val(); }).get();
                        }
                        
                    } else if (typeof $thisAutho !== 'string' && $thisAutho.is(':checkbox')) {
                        
                        $thisAutho = mainSelector.find('[data-path="'+srcSplitPath+'"]:checked');
                        var $spliAuthoryIds = $thisAutho.map(function(){ return this.value; }).get();

                    } else {
                        
                        if (isCommaValue && typeof $realValue === 'undefined') {
                            $spliAuthoryIds = $thisAutho.split(',');
                        } else {
                            if ($thisAutho.length > 1) {
                                var $spliAuthoryIds = new Array();
                            
                                $thisAutho.each(function() {
                                    var $thisGet = $(this);
                                    if ($thisGet.is('[multiple]') && $thisGet.hasClass('select2')) {
                                        
                                        var selectedValue = $thisGet.select2('data');

                                        if (selectedValue.length) {
                                            for (var s in selectedValue) {
                                                var sId = selectedValue[s]['id'];
                                                $spliAuthoryIds.push(sId);
                                            }
                                        } 
                                    } else {
                                        var selectedId = $thisAutho.val();
                                        if (selectedId != '') {
                                            $spliAuthoryIds.push(selectedId);
                                        }
                                    }
                                });
                            } else if ($thisAutho.is('[multiple]') && $thisAutho.hasClass('select2')) {
                                
                                var selectedValue = $thisAutho.select2('data');

                                if (selectedValue.length) {
                                    var $spliAuthoryIds = new Array();
                                    for (var s in selectedValue) {
                                        var sId = selectedValue[s]['id'];
                                        $spliAuthoryIds.push(sId);
                                    }
                                } 
                                
                            } else {
                                $spliAuthoryIds = $thisAutho.val();
                            }
                        }
                    }
                    
                    var $parent = mainSelector.find('table[data-table-path="'+ trgGroupPath +'"]').parent();
                    
                    if ($spliAuthoryIds) {
                        
                        var $mainTableBody = mainSelector.find('table[data-table-path="'+ trgGroupPath +'"] > tbody');
                        var $firstRow = $mainTableBody.find('> tr:eq(0)');
                        var $sindex = 1;

                        if ($firstRow.length == 0) {
                            bpAddRow(mainSelector, 'open', trgGroupPath, 1, 'empty', 0, 'transferSplit');
                            $firstRow = $mainTableBody.find('> tr:eq(0)');
                        }
                        
                        var $mainCopyTr = '<tr class="bp-detail-row">' + $firstRow.html() + '</tr>';
                        var $rowState = $mainTableBody.find('input[data-path="'+ trgGroupPath +'.rowState"]');
                        
                        $sindex = mainSelector.find('table[data-table-path="'+ trgGroupPath +'"] > tbody > tr').length;
                        $rowState.val('removed');
                        
                        if (isCommaValue || $isCommaValue) {
                            if (typeof $realValue !== 'undefined' && $realValue === 'rows-rows') {
                                
                                $mainTableBody.each(function (indexTab, rowTab) {
                                    
                                    var $notSavedRows = $(rowTab).find('tr.saved-bp-row');
                                    $(rowTab).find('tr:not(.saved-bp-row)').remove();

                                    if ($notSavedRows.length) {
                                        $notSavedRows.each(function() {
                                            var $notSavedRow = $(this);
                                            if ($notSavedRow.find('input[data-path="'+ trgGroupPath +'.id"]').val() == '') {
                                                $notSavedRow.remove();
                                            }
                                        });
                                    }
                                    
                                    $spliAuthoryIds = $($thisAutho[indexTab]).val();
                                    var $sindex = 0;
                                    
                                    $.each($spliAuthoryIds, function (sindex, srow) {
                                        if (typeof $removedRowState !== 'undefined' && $removedRowState == '1') {

                                            $(rowTab).append($mainCopyTr).promise().done(function () {
                                                if (srow !== '#') {
                                                    var $rowThis = mainSelector.find('table[data-table-path="'+ trgGroupPath +'"] > tbody:eq('+ indexTab +') > tr:eq('+ $sindex +')');
                                                    $rowThis.find('[data-path="'+ trgGroupPath + '.' + trgFillPath +'"]').val(srow);
                                                    $rowThis.find('input[data-path="'+ trgGroupPath +'.rowState"]').val('added');
                                                    $rowThis.find('input[data-path="'+ trgGroupPath +'.id"]').val('');
                                                    Core.initBPDtlInputType($rowThis);
                                                }
                                            }); 

                                        } else {
                                            
                                            var $detectInput = $(rowTab).find('input[data-path="'+trgGroupPath+'.'+trgFillPath+'"]').filter(function(){ return ($(this).val() == srow); });
                                            
                                            if ($detectInput.length == 0) {
                                                $(rowTab).append($mainCopyTr).promise().done(function () {
                                                    if (srow !== '#') {
                                                        var $rowThis = mainSelector.find('table[data-table-path="'+ trgGroupPath +'"]:eq('+ indexTab +') > tbody > tr:eq('+ $sindex +')');
                                                        $rowThis.find('input[data-path="'+ trgGroupPath + '.' + trgFillPath +'"]').val(srow);
                                                        $rowThis.find('input[data-path="'+ trgGroupPath + '.id"]').val('');
                                                        $rowThis.find('input[data-path="'+ trgGroupPath + '.rowState"]').val('added');
                                                        Core.initBPDtlInputType($rowThis);
                                                    }
                                                }); 
                                            }
                                        }

                                        $sindex++;
                                    });
                                    
                                    bpSetRowIndexDepth(mainSelector.find('table[data-table-path="'+ trgGroupPath +'"]:eq('+ indexTab +')'), mainSelector, indexTab);
                                });
                                
                            } else {
                                
                                $mainTableBody.append($mainCopyTr).promise().done(function () {
                                    $.each($spliAuthoryIds, function (sindex, srow) {

                                        if (typeof $removedRowState !== 'undefined' && $removedRowState == '1') {

                                            $mainTableBody.append($mainCopyTr).promise().done(function () {
                                                if (srow !== '#') {
                                                    var $rowThis = mainSelector.find('table[data-table-path="'+ trgGroupPath +'"] > tbody > tr:eq('+ $sindex +')');
                                                    $rowThis.find('[data-path="'+ trgGroupPath + '.' + trgFillPath +'"]').val(srow);
                                                    $rowThis.find('input[data-path="'+ trgGroupPath +'.rowState"]').val('added');
                                                    $rowThis.find('input[data-path="'+ trgGroupPath +'.id"]').val('');
                                                    Core.initBPDtlInputType($rowThis);
                                                }
                                            }); 

                                        } else {
                                            $mainTableBody.append($mainCopyTr).promise().done(function () {
                                                if (srow !== '#') {
                                                    var $rowThis = mainSelector.find('table[data-table-path="'+ trgGroupPath +'"] > tbody > tr:eq('+ $sindex +')');
                                                    
                                                    $rowThis.find('input[data-path="'+ trgGroupPath + '.' + trgFillPath +'"]').val(srow);
                                                    $rowThis.find('input[data-path="'+ trgGroupPath +'.rowState"]').val('added');
                                                    $rowThis.find('input[data-path="'+ trgGroupPath +'.id"]').val('');
                                                    
                                                    Core.initBPDtlInputType($rowThis);
                                                }
                                            }); 
                                        } 

                                        $sindex++;
                                    });
                                }); 
                                
                                if ($rowState.attr('name').indexOf('mvParam[') !== -1) { 
                                    kpiSetRowIndex($mainTableBody);
                                } else {
                                    bpSetRowIndexDepth($parent, mainSelector);
                                }
                                
                                mainSelector.find('table[data-table-path="'+ trgGroupPath +'"] > tbody > tr').each(function (sindex, srow) {
                                    if ($(srow).find('input[data-field-name="rowState"]:eq(0)').val() == '') {
                                        $(srow).find('input[data-field-name="rowState"]:eq(0)').val('removed');
                                    }
                                });
                            }
                        } else {
                        
                            if (typeof $selector !== 'undefined' && $selector === 'input') {
                                $.each($thisAutho, function ($index, $row) {
                                    var srow = $($row).val();

                                    if (typeof $removedRowState !== 'undefined' && $removedRowState == '1') {

                                        $mainTableBody.append($mainCopyTr).promise().done(function () {
                                            if (srow !== '#') {
                                                var $rowThis = mainSelector.find('table[data-table-path="'+ trgGroupPath +'"] > tbody > tr:eq('+ $sindex +')');
                                                $rowThis.find('[data-path="'+ trgGroupPath + '.' + trgFillPath +'"]').val(srow);
                                                $rowThis.find('input[data-path="'+ trgGroupPath +'.rowState"]').val('added');
                                                $rowThis.find('input[data-path="'+ trgGroupPath +'.id"]').val('');
                                                Core.initBPDtlInputType($rowThis);
                                            }
                                        }); 

                                    } else {

                                        var $detectInput = $mainTableBody.find('input[data-path="'+trgGroupPath+'.'+trgFillPath+'"]').filter(function(){ return ($(this).val() == srow); });

                                        if ($detectInput.length == 0) {

                                            var $setInput;

                                            $mainTableBody.append($mainCopyTr).promise().done(function () {

                                                if (srow !== '#') {

                                                    var $rowThis = mainSelector.find('table[data-table-path="'+ trgGroupPath +'"] > tbody > tr:last');
                                                    $setInput = $rowThis.find('input[data-path="'+ trgGroupPath + '.' + trgFillPath +'"]');

                                                    if ($setInput.length) {

                                                        $setInput.val(srow);

                                                        $rowThis.find('input[data-path="'+ trgGroupPath +'.id"]').val('');
                                                        $rowThis.find('input[data-path="'+ trgGroupPath +'.rowState"]').val('added');
                                                    }
                                                    Core.initBPDtlInputType($rowThis);
                                                }
                                            }); 
                                        } else {
                                            $detectInput.closest('tr').find('input[data-path="'+ trgGroupPath +'.rowState"]').val('unchanged');
                                        }
                                    }

                                    $sindex++;
                                });
                            } else {
                                
                                var $notSavedRows = $mainTableBody.find('tr.saved-bp-row');
                                $mainTableBody.find('tr:not(.saved-bp-row)').remove();
                                
                                if ($notSavedRows.length) {
                                    $notSavedRows.each(function() {
                                        var $notSavedRow = $(this);
                                        if ($notSavedRow.find('input[data-path="'+ trgGroupPath +'.id"]').val() == '') {
                                            $notSavedRow.remove();
                                        }
                                    });
                                }
                                
                                $.each($spliAuthoryIds, function (sindex, srow) {

                                    if (typeof $removedRowState !== 'undefined' && $removedRowState == '1') {

                                        $mainTableBody.append($mainCopyTr).promise().done(function () {
                                            if (srow !== '#') {
                                                var $rowThis = mainSelector.find('table[data-table-path="'+ trgGroupPath +'"] > tbody > tr:eq('+ $sindex +')');
                                                $rowThis.find('[data-path="'+ trgGroupPath + '.' + trgFillPath +'"]').val(srow);
                                                $rowThis.find('input[data-path="'+ trgGroupPath +'.rowState"]').val('added');
                                                $rowThis.find('input[data-path="'+ trgGroupPath +'.id"]').val('');
                                                $rowThis.find('td:first > span').text(sindex + 1);
                                                Core.initBPDtlInputType($rowThis);
                                            }
                                        }); 

                                    } else {

                                        var $detectInput = $mainTableBody.find('input[data-path="'+trgGroupPath+'.'+trgFillPath+'"]').filter(function(){ return ($(this).val() == srow); });
                                
                                        if ($detectInput.length == 0) {
                                            var $setInput;

                                            $mainTableBody.append($mainCopyTr).promise().done(function () {

                                                if (srow !== '#') {

                                                    var $rowThis = mainSelector.find('table[data-table-path="'+ trgGroupPath +'"] > tbody > tr:last');
                                                    $setInput = $rowThis.find('input[data-path="'+ trgGroupPath + '.' + trgFillPath +'"]');

                                                    if ($setInput.length) {

                                                        $setInput.val(srow);

                                                        $rowThis.find('input[data-path="'+ trgGroupPath +'.id"]').val('');
                                                        $rowThis.find('input[data-path="'+ trgGroupPath +'.rowState"]').val('added');
                                                    }
                                                    
                                                    $rowThis.find('td:first > span').text(sindex + 1);
                                                    Core.initBPDtlInputType($rowThis);
                                                }
                                            }); 
                                        } else {
                                            $detectInput.closest('tr').find('input[data-path="'+ trgGroupPath +'.rowState"]').val('unchanged');
                                        }
                                    }

                                    $sindex++;
                                });
                            }
                            
                            if ($mainTableBody.closest('table').hasClass('kpi-dtl-table')) { 
                                kpiSetRowIndex($mainTableBody);
                            } else {
                                bpSetRowIndex($parent);
                            }
                            /*bpDetailRowOrdering($mainTableBody);*/
                        }
                    }
                }    
            }
            
        }
        
    } catch(err) {
        console.log('transferSplitValueToDtlFunction_ : ' + err);
    }
    return;
}
function selectorTooltipFunction(mainSelector, srcSplitPath, tooltipText, tooltipPosition) {
    tooltipPosition = (typeof tooltipPosition !== 'undefined') ? tooltipPosition : 's';
    
    setTimeout(function () {
        mainSelector.find('[data-path="'+ srcSplitPath +'"]').attr('title', tooltipText);
        mainSelector.find('[data-path="'+ srcSplitPath +'"]').powerTip({placement: tooltipPosition});
    }, 900);
    
    return;
}
function bpGetGLRowField(elem, fieldName) {
    var $row = $(elem).closest('tr[data-row-index]');
    
    if (fieldName == 'gl_debitAmountSum' || fieldName == 'gl_creditAmountSum' 
        || fieldName == 'gl_debitAmountBaseSum' || fieldName == 'gl_creditAmountBaseSum') { 
    
        var accountId = $row.find('input[name*="gl_accountId["]').val(), 
            $tbody = $row.closest('tbody'), sum = 0, 
            sumFieldName = fieldName.replace('Sum', '');
    
        $tbody.find('> tr').each(function(){
            var $thisRow = $(this);
            if ($thisRow.find('input[name*="gl_accountId["]').val() == accountId) {
                sum += parseFloat($thisRow.find('input[name*="'+sumFieldName+'["]').val());
            }
        });
        
        return sum;
    } 
    
    var result = '', $field = $row.find('input[name*="'+fieldName+'["]');
    
    if ($field.length) {
        
        if (fieldName == 'gl_debitAmount' || fieldName == 'gl_creditAmount' 
            || fieldName == 'gl_debitAmountBase' || fieldName == 'gl_creditAmountBase') { 
            result = Number($field.val());
        } else if (fieldName == 'gl_rate') { 
            result = Number($field.autoNumeric('get'));
        } else {
            result = $field.val();
        }
    }
    
    return result;
}
function bpSetGLRowField(elem, fieldName, value) {
    var $field = $(elem).closest('tr[data-row-index]').find('input[name*="'+fieldName+'["]');
    
    if ($field.length) {
        
        if (fieldName == 'gl_debitAmount' || fieldName == 'gl_creditAmount' 
            || fieldName == 'gl_debitAmountBase' || fieldName == 'gl_creditAmountBase') {
            var $fieldTyping = $(elem).closest('tr[data-row-index]').find('input[id="'+fieldName+'"]'); 
            $fieldTyping.autoNumeric('set', value);
            var e = jQuery.Event('keyup');
            e.keyCode = e.which = 97;
            $fieldTyping.trigger(e);
        } else {
            $field.val(value);
        }
    }
    
    return;
}
function bpGetActiveGroupPath(mainSelector) {
    var groupPath = '';
    if (mainSelector.find('[data-table-path]:visible').length) {
        groupPath = mainSelector.find('[data-table-path]:visible:first').attr('data-table-path');
    }
    return groupPath;
}
function bpRunPHP(mainSelector, elem, url, paramsPath, responsePath) {
    var paramData = [], paramsPathArr = paramsPath.split('|');

    for (var i = 0; i < paramsPathArr.length; i++) {
        var fieldPathArr = paramsPathArr[i].split('@');
        var fieldPath = fieldPathArr[0].trim();
        var inputPath = fieldPathArr[1].trim();
        var fieldValue = '';

        var $bpElem = getBpElement(mainSelector, elem, fieldPath);

        if ($bpElem) {
            fieldValue = getBpRowParamNum(mainSelector, elem, fieldPath);
        } else {
            fieldValue = fieldPath;
        }

        paramData.push({
            postParam: inputPath, 
            value: fieldValue
        });
    }

    var response = $.ajax({
        type: 'post',
        url: url, 
        data: {
            responsePath: responsePath, 
            paramData: paramData
        },
        dataType: 'json',
        async: false
    });

    return response;
}
function bpCallPhpService(mainSelector, elem, wsUrl, paramsPath) {
    var paramData = [], paramsPathArr = paramsPath.split('|'), paramArr = '';

    for (var i = 0; i < paramsPathArr.length; i++) {
        var fieldPathArr = paramsPathArr[i].split('@');
        var fieldPath = fieldPathArr[0].trim();
        var inputPath = fieldPathArr[1].trim();
        var fieldValue = '';

        var $bpElem = getBpElement(mainSelector, elem, fieldPath);

        if ($bpElem) {
            fieldValue = getBpRowParamNum(mainSelector, elem, fieldPath);
        } else {
            fieldValue = fieldPath;
        }
        
        paramArr += inputPath+'='+fieldValue+'&';
    }
    
    paramArr = rtrim(paramArr, '&');
    
    var response = $.ajax({
        type: 'post',
        url: wsUrl, 
        data: paramArr, 
        dataType: 'json',
        async: false
    });

    return response;
}
function bpIgnoreDuplicateRows(mainSelector, elem, groupPath, fieldPaths) {
    mainSelector.find('[data-table-path="'+groupPath+'"]').attr('data-ignore-criteria-rows', fieldPaths);
    return;
}
function bpSoundPlay(code) {
    if (code == 'error') {
        ion.sound.play('door_bell');
    } else if (code == 'ring') {
        ion.sound.play('bell_ring_new');
    } else if (code == 'bell') {
        ion.sound.play('door_bell');
    } else if (code == 'water') {
        ion.sound.play('bell_ring_new');
    }
    return;
}
function bpLookupFieldReload(mainSelector, processId, cacheId, processPath, matchPath) {
    var result = false, processPathArr = processPath.split('.'), groupPath = processPathArr[0];
    
    $.ajax({
        type: 'post',
        url: 'mdcache/lookupFieldReload',
        data: {
            processId: processId, 
            cacheId: cacheId.replace('_', ''), 
            groupPath: groupPath, 
            fieldPath: processPath, 
            matchPath: matchPath, 
            headerData: mainSelector.find('div.bp-header-param').find('input, select').serialize()
        }, 
        async: false,
        success: function(data) {
            if (data === 'success') {
                result = true;
            }
        }
    });
    
    if (result) {
        var $pagerRefreshBtn = mainSelector.find('div[data-pg-grouppath="'+groupPath+'"] > .pf-bp-pager-buttons > .pf-bp-pager-refresh');
        
        isAsyncCacheGrid = false;
        
        $pagerRefreshBtn.attr('data-ignore-modify', '1');
        bpDetailPagerRefresh($pagerRefreshBtn);
        $pagerRefreshBtn.removeAttr('data-ignore-modify');
        
        isAsyncCacheGrid = true;
    }
    
    return result;
}
function bpGetSessionInfo(key) {
    var result = '';
    $.ajax({
        type: 'post',
        url: 'mduser/getSessionInfo',
        data: {key: key},
        async: false,
        success: function(data){
            result = data;
        }
    });
    return result;
}
function bpGetEditSidebarValue(mainSelector, fieldPath) {
    var $getField = mainSelector.find('[data-c-path="'+fieldPath+'"]'), result = '';
    if ($getField.length) {
        if ($getField.hasClass('booleanInit')) {
            result = $getField.is(':checked');
        } else if ($getField.hasClass('numberInit') 
                || $getField.hasClass('decimalInit')
                || $getField.hasClass('integerInit') 
                || $getField.hasClass('bigdecimalInit')) {  
            result = Number($getField.autoNumeric('get'));
        } else if ($getField.hasClass('longInit')) {
            result = $getField.autoNumeric('get');
        } else if ($getField.hasClass('select2')) {
            result = $getField.select2('val');
        } else if ($getField.hasClass('popupInit')) {
            var $parent = $getField.closest('.meta-autocomplete-wrap');
            result = $parent.find('input.lookup-code-autocomplete').val();
        } else {
            result = $getField.val();
        }
    }
    return result;
}
function bpSetEditSidebarValue(mainSelector, fieldPath, val) {
    var $getField = mainSelector.find('[data-c-path="'+fieldPath+'"]');
    if ($getField.length) {
        if ($getField.hasClass('select2')) {
            $getField.trigger("select2-opening", [true]);
            $getField.select2('val', val);
        } else if ($getField.hasClass('longInit') 
            || $getField.hasClass('numberInit') 
            || $getField.hasClass('decimalInit') 
            || $getField.hasClass('integerInit')) {

            $getField.autoNumeric('set', val);                        

        } else if ($getField.hasClass('bigdecimalInit')) {
            $getField.next("input[type=hidden]").val(setNumberToFixed(val));
            $getField.autoNumeric('set', val);   
        } else if ($getField.hasClass('dateInit')) {
            if (val !== '' && val !== null) {
                $getField.datepicker('update', date('Y-m-d', strtotime(val)));
            } else {
                $getField.datepicker('update', null);
            }
        } else if ($getField.hasClass('datetimeInit')) {
            if (val !== '' && val !== null) {
                $getField.val(date('Y-m-d H:i:s', strtotime(val)));
            } else {
                $getField.val('');
            }
        } else if ($getField.hasClass('popupInit')) {   
            setLookupPopupValue($getField, val);
        } else if ($getField.hasClass('booleanInit')) {   
            checkboxCheckerUpdate($getField, val);
        } else {                                               
            $getField.val(val);                        
        }
    }
    return;
}
function bpHideEditSidebar(mainSelector, fieldPath) {
    var processPathArr = fieldPath.split('.'), $processPath = processPathArr[0];
    var $getRow = mainSelector.find('[data-r-path="'+fieldPath+'"]');
    $getRow.find('input[name]').removeAttr('name');
    var $sideSelector = mainSelector.find('div[data-section-path="'+ $processPath +'"] > div[data-bp-detail-sidebar="1"]');
    
    setTimeout(function () {
        $sideSelector.find('[disabled="disabled"]').removeAttr('disabled');
        $sideSelector.find('[readonly="readonly"]').removeAttr('readonly');
        $sideSelector.find('[readonly]').removeAttr('readonly');
        $sideSelector.find('.select2').select2('readonly', false);
    }, 2000);
    
    if ($getRow.length) {
        $getRow.css({'display': 'none'});
    }
    
    var $getRow1 = mainSelector.find('[data-cell-path="'+fieldPath+'"]');
    if ($getRow1.length) {
        $getRow1.css({'display': 'none'});
    }
    
    return;
}
function bpShowEditSidebar(mainSelector, fieldPath) {
    var processPathArr = fieldPath.split('.'), $processPath = processPathArr[0];
    var $getRow = mainSelector.find('[data-r-path="'+fieldPath+'"]');
    var $sideSelector = mainSelector.find('div[data-section-path="'+ $processPath +'"] > div[data-bp-detail-sidebar="1"]');
    
    setTimeout(function () {
        $sideSelector.find('[disabled="disabled"]').removeAttr('disabled');
        $sideSelector.find('[readonly="readonly"]').removeAttr('readonly');
        $sideSelector.find('[readonly]').removeAttr('readonly');
        $sideSelector.find('.select2').select2('readonly', false);
    }, 2000);
    
    if ($getRow.length) {
        $getRow.css({'display': ''});
        $getRow.find('.input-group').css({'display': ''});
        $getRow.find('.input-group').children().css({'display': ''});
    }
    
    return;
}
function bpMessageClose() {
    PNotify.removeAll();
    return;
}
function bpDefaultEditSidebar(mainSelector, groupPath) {
    
    var $detail = mainSelector.find('[data-table-path="'+groupPath+'"]');
    
    if ($detail.length) {
        
        var uniqId = mainSelector.attr('data-bp-uniq-id'), 
            processId = mainSelector.attr('data-process-id'), 
            rowId = $detail.attr('data-row-id');
        
        var postData = {
            processId: processId, 
            uniqId: uniqId, 
            rowId: rowId, 
            isEditMode: window['isEditMode_' + uniqId]
        };
        
        $.ajax({
            type: 'post',
            url: 'mdcommon/renderBpDtlRow',
            data: postData, 
            dataType: 'html', 
            async: false, 
            beforeSend: function () {
                Core.blockUI({animate: true});
            },
            success: function (dataStr) {
                var $row = $('<div />', {html: dataStr});
                
                $detail.after('<script type="text/template" data-template="'+groupPath+'">'+dataStr+'</script>');
                
                bpDetailModifyMode($row.find('> .bp-detail-row:eq(0)'), uniqId, $detail);
            }
        });
    }
    
    return;
}
function bpGetProcessTabItemCount(mainSelector, tabName) {
    
    var count = 0, tabName = tabName.toLowerCase();
    
    if (tabName == 'photo') {
        
        var $elements = mainSelector.find('ul.list-view-photo > li');
        count = $elements.length;
        
    } else if (tabName == 'file') {

        var $elements = mainSelector.find('ul.grid div.img-precontainer');
        count = $elements.length;
    }
    
    return count;
}
function bpGetRowIndex(elem) {
    return Number($(elem).parents('.bp-detail-row').index()) + 1;
}
function bpActivePopupNameTabIndex(mainSelector, elem, fieldPath) {
    var $field = mainSelector.find('[data-path="'+fieldPath+'"]');
    if ($field.length) {
        if ($field.hasClass('popupInit')) {
            var $parent = $field.closest('.double-between-input');
            $parent.find('.meta-name-autocomplete').removeAttr('tabindex');
        }
    }
    return;
}
function bpGetGlDtlRowCode(mainSelector, elem) {
    var $parentRow = elem.closest('tr'), 
        subId = $parentRow.attr('data-sub-id'), 
        rowId = Number($parentRow.index('[data-sub-id="'+subId+'"]')) + 1;
    
    return subId+'.'+rowId;
}
function bpGetGlDtlDebitAmount(mainSelector, subId, rowId) {
    var result = 0, $glDtl = mainSelector.find('table#glDtl > tbody > tr[data-sub-id="'+subId+'"]:eq('+(rowId - 1)+')');
    
    if ($glDtl.length) {
        result = Number($glDtl.find('input[name="gl_debitAmount[]"]').val());
    }
    
    return result;
}
function bpGetGlDtlCreditAmount(mainSelector, subId, rowId) {
    var result = 0, $glDtl = mainSelector.find('table#glDtl > tbody > tr[data-sub-id="'+subId+'"]:eq('+(rowId - 1)+')');
    
    if ($glDtl.length) {
        result = Number($glDtl.find('input[name="gl_creditAmount[]"]').val());
    }
    
    return result;
}
function bpSetGlDtlDebitAmount(mainSelector, subId, rowId, val) {
    var $glDtl = mainSelector.find('table#glDtl > tbody > tr[data-sub-id="'+subId+'"]:eq('+(rowId - 1)+')');
    
    if ($glDtl.length) {
        $glDtl.find('input[name="gl_debitAmount[]"]').val(val);
        $glDtl.find('input[data-input-name="debitAmount"]').autoNumeric('set', val).trigger('keyup');
    }
    return;
}
function bpSetGlDtlCreditAmount(mainSelector, subId, rowId, val) {
    var $glDtl = mainSelector.find('table#glDtl > tbody > tr[data-sub-id="'+subId+'"]:eq('+(rowId - 1)+')');
    
    if ($glDtl.length) {
        $glDtl.find('input[name="gl_creditAmount[]"]').val(val);
        $glDtl.find('input[data-input-name="creditAmount"]').autoNumeric('set', val).trigger('keyup');
    }
    return;
}
function bpSetKpiColField(mainSelector, elem, path, val) {
    
    if (elem !== 'open') {
        
        var $elem = $(elem);
        
        if (typeof $elem.prop('tagName') !== 'undefined' && $elem.prop('tagName') == 'TR') {
            var $row = $elem; 
        } else {
            var $row = $elem.closest('tr'); 
        }
    } else {
        var $row = mainSelector;
    }
    
    var $getPath = $row.find('[data-col-path="'+path+'"]'); 

    if ($getPath.length) {
        
        if ($getPath.hasClass('longInit') || $getPath.hasClass('integerInit') || $getPath.hasClass('decimalInit')) {
            if (val !== '' && val !== null) {
                $getPath.autoNumeric('set', val);
            } else {
                $getPath.autoNumeric('set', '');
            }
        } else {
            $getPath.val(val);
        }
        
        $getPath.trigger('change');
    }
    return;
}
function bpGetKpiColField(mainSelector, elem, path) {
    
    if (elem !== 'open') {
        
        var $elem = $(elem);
        
        if (typeof $elem.prop('tagName') !== 'undefined' && $elem.prop('tagName') == 'TR') {
            var $row = $elem; 
        } else {
            var $row = $elem.closest('tr'); 
        }
    } else {
        var $row = mainSelector;
    }
    
    var $getPath = $row.find('[data-col-path="'+path+'"]'); 
    
    if ($getPath.length) {
        if ($getPath.hasClass('longInit') || $getPath.hasClass('integerInit') || $getPath.hasClass('decimalInit')) {
            return Number($getPath.autoNumeric('get'));
        } else if ($getPath.hasClass('booleanInit')) {
            return $getPath.is(':checked');
        } else {
            return $getPath.val();
        }
    }
    
    return '';
}
function bpGetKpiColFieldNull(mainSelector, elem, path) {
    
    if (elem !== 'open') {
        
        var $elem = $(elem);
        
        if (typeof $elem.prop('tagName') !== 'undefined' && $elem.prop('tagName') == 'TR') {
            var $row = $elem; 
        } else {
            var $row = $elem.closest('tr'); 
        }
    } else {
        var $row = mainSelector;
    }
    
    var $getPath = $row.find('[data-col-path="'+path+'"]'); 
    
    if ($getPath.length) {
        if ($getPath.hasClass('longInit') || $getPath.hasClass('integerInit') || $getPath.hasClass('decimalInit')) {
            if ($getPath.val() != '') {
                return Number($getPath.autoNumeric('get'));
            } else {
                return '';
            }
        } else if ($getPath.hasClass('booleanInit')) {
            return $getPath.is(':checked');
        } else {
            return $getPath.val();
        }
    }
    
    return '';
}
function bpGetKpiColLookupFieldValue(mainSelector, elem, path, column) {
    try {
        var elem = $(elem), $row = elem.closest('tr'), 
            $getPath = $row.find('[data-col-path="'+path+'"]'); 

        if ($getPath.length) {
            
            if ($getPath.prop('tagName') == 'SELECT' && typeof $getPath.find('option:selected').attr('data-row-data') !== 'undefined') {
                var rowData = $getPath.find('option:selected').attr('data-row-data');
            } else if ($getPath.prop('type') == 'radio' && typeof $getPath.closest('.radio-list').find('span.checked > input').attr('data-row-data') !== 'undefined') {
                var rowData = $getPath.closest('.radio-list').find('span.checked > input').attr('data-row-data');
            } else {
                var rowData = $getPath.attr('data-row-data');
            }
            
            if (rowData !== '') {
                if (typeof rowData !== 'object') {
                    var jsonObj = JSON.parse(html_entity_decode(rowData, "ENT_QUOTES"));
                } else {
                    var jsonObj = rowData;
                }
		
                var lowerColumn = column.toLowerCase(); 
                
                if (lowerColumn in Object(jsonObj)) {
                    return jsonObj[lowerColumn];
                }
            }
        }

        return '';
    
    } catch(e) {
        console.log(e);
        return '';
    }   
}
function bpSetKpiRowField(mainSelector, elem, path, val) {
    var $row = mainSelector.find('tr[data-dtl-code="'+path+'"]'); 
    
    if ($row.length) {
        var colIndex = elem.closest('td').index();
        if (val !== '' && val !== null) {
            $row.find('> td:eq('+colIndex+') > input').autoNumeric('set', val);
        } else {
            $row.find('> td:eq('+colIndex+') > input').autoNumeric('set', '');
        }
    }
    return;
}
function bpGetKpiRowField(mainSelector, elem, path) {
    var $row = mainSelector.find('tr[data-dtl-code="'+path+'"]'); 
    
    if ($row.length) {
        var colIndex = elem.closest('td').index();
        return Number($row.find('> td:eq('+colIndex+') > input').autoNumeric('get'));
    }
    return '';
}
function bpGetKpiColSum(mainSelector, elem, path, indicatorLevel) {
    
    if (path.indexOf('col') !== -1) {
        var $getPath = mainSelector.find("[data-colcode='"+path+"']");
    } else {
        if (typeof indicatorLevel != 'undefined' && indicatorLevel) {
            var $getPath = mainSelector.find('[data-col-path="'+path+'"][data-indicator-level="'+indicatorLevel+'"]'); 
        } else {
            var $getPath = mainSelector.find('[data-col-path="'+path+'"]'); 
        }
    }
    
    if ($getPath.length) {
        return Number($getPath.sum());
    }
    
    return 0;
}
function bpGetKpiSubColSum(mainSelector, elem, path) {
    
    var $row = elem.closest('td'), 
        $getPath = $row.find('.kpi-dtl-table [data-col-path="'+path+'"]'); 

    if ($getPath.length) {
        return Number($getPath.sum());
    }

    return 0;
}
function bpGetKpiColCount(mainSelector, elem, path, indicatorLevel) {
    if (typeof indicatorLevel != 'undefined' && indicatorLevel) {
        var $getPath = mainSelector.find('[data-col-path="'+path+'"][data-indicator-level="'+indicatorLevel+'"]'); 
    } else {
        var $getPath = mainSelector.find('[data-col-path="'+path+'"]'); 
    }
    return Number($getPath.length);
}
function bpGetKpiDtlCode(mainSelector, elem) {
    
    var $elem = $(elem);
    
    if (typeof $elem.prop('tagName') !== 'undefined' && $elem.prop('tagName') == 'TR') {
        var $row = $elem; 
    } else {
        var $row = $elem.closest('tr[data-dtl-code]'); 
    }
    
    if ($row.length && $row.hasAttr('data-dtl-code')) {
        return $row.attr('data-dtl-code');
    }

    return '';
}
function bpGetKpiObjectDtlId(mainSelector, elem) {
    
    var $row = mainSelector.closest('[data-basketrowid]'); 

    if ($row.length) {
        return $row.attr('data-basketrowid');
    }

    return '';
}
function bpGetKpiIndicatorId(mainSelector, elem) {
    var $elem = $(elem);
    
    if (typeof $elem.prop('tagName') !== 'undefined' && $elem.prop('tagName') == 'TR') {
        var $row = $elem; 
    } else {
        var $row = $elem.closest('tr[data-is-input]'); 
    }
    
    if ($row.length) {
        var $indicator = $row.find('[data-path="kpiDmDtl.indicatorId"]');
        if ($indicator.length) {
            return $indicator.val();
        }
    }

    return '';
}
function bpKpiColDisable(mainSelector, path) {
    var $getFields = mainSelector.find('[data-col-path="'+path+'"]');

    if ($getFields.length) {
        
        $getFields.each(function() {
            
            var $getField = $(this);
            
            if ($getField.hasClass('radioInit')) {
            
                var $radios = $getField.find("input[type='radio']");
                $radios.prop('disabled', true);
                $.uniform.update($radios);

            } else if ($getField.hasClass('select2')) {  

                $getField.select2('readonly', true);

            } else if ($getField.hasClass('popupInit')) {

                var $codeName = $getField.closest('div.meta-autocomplete-wrap');

                if ($codeName.length) {
                    $codeName.find("input[type='text']").attr({'readonly': 'readonly', 'tabindex': '-1'});
                    $codeName.find("button").attr('style', 'pointer-events: none; background-color: #eeeeee !important').prop("disabled", true);
                }

            } else if ($getField.hasClass('booleanInit')) {  

                $getField.attr({'data-isdisabled': 'true', style: 'cursor: not-allowed', 'tabindex': '-1'});
                $getField.closest('.checker').addClass('disabled');

            } else {
                $getField.attr('readonly', 'readonly');
            }
        });
    }
    return;
}
function bpKpiColHide(mainSelector, path) {
    var $getField = mainSelector.find('[data-col-path="'+path+'"]');

    if ($getField.length) {
        var $getFirstField = $getField.eq(0);
        if ($getFirstField.hasAttr('data-cell-path')) {
            var getPath = $getFirstField.attr('data-cell-path');
        } else {
            var getPath = $getFirstField.closest('td').attr('data-cell-path');
        }
        mainSelector.find('th[data-cell-path="'+getPath+'"], td[data-cell-path="'+getPath+'"]').hide();
    }
    return;
}
function bpKpiColShow(mainSelector, path) {
    var $getField = mainSelector.find('[data-col-path="'+path+'"]');

    if ($getField.length) {
        var $getFirstField = $getField.eq(0);
        if ($getFirstField.hasAttr('data-cell-path')) {
            var getPath = $getFirstField.attr('data-cell-path');
        } else {
            var getPath = $getFirstField.closest('td').attr('data-cell-path');
        }
        mainSelector.find('th[data-cell-path="'+getPath+'"], td[data-cell-path="'+getPath+'"]').show();
    }
    return;
}
function bpKpiRowHide(mainSelector, path) {
    var $row = mainSelector.find('[data-dtl-code="'+path+'"]');

    if ($row.length) {
        $row.hide();
        $row.find('.kpiDecimalInit').attr('data-not-aggregate', '1');
    }
    return;
}
function bpKpiRowShow(mainSelector, path) {
    var $row = mainSelector.find('[data-dtl-code="'+path+'"]');

    if ($row.length) {
        $row.show();
        $row.find('.kpiDecimalInit').removeAttr('data-not-aggregate');
    }
    return;
}
function bpKpiColRequired(mainSelector, path) {
    var $getField = mainSelector.find('[data-col-path="'+path+'"]');

    if ($getField.length) {
        $getField.attr('required', 'required');
    }
    return;
}
function bpKpiColNonRequired(mainSelector, path) {
    var $getField = mainSelector.find('[data-col-path="'+path+'"]');

    if ($getField.length) {
        $getField.removeAttr('required');
    }
    return;
}
function bpKpiRowDisable(mainSelector, path) {
    var $row = mainSelector.find('tr[data-dtl-code="'+path+'"]'); 
    
    if ($row.length) {
        $row.find('> td input[data-path]').attr('readonly', 'readonly');
        $row.find('> td input[data-path].fileInit').attr({'readonly': 'readonly', 'onkeydown': 'return false;'}).addClass('disable-click');
        $row.find('> td textarea[data-path]').attr('readonly', 'readonly');
        $row.find('> td button').attr('disabled', 'disabled');
        $row.find('> td select.select2').select2('readonly', true);
    }
    return;
}
function isElement(obj) {
    try {
        //Using W3 DOM2 (works for FF, Opera and Chrome)
        return obj instanceof HTMLElement;
    } catch(e) {
        return (typeof obj==="object") &&
          (obj.nodeType===1) && (typeof obj.style === "object") &&
          (typeof obj.ownerDocument ==="object");
    }
}
function bpKpiRowColDisable(mainSelector, elem, colPath) {
    
    if (elem !== 'open') {
        if (typeof elem.prop('tagName') !== 'undefined' && elem.prop('tagName') == 'TR') {
            var $row = elem; 
        } else {
            var $row = elem.closest('tr'); 
        }
    } else {
        var $row = $();
    }
    
    if ($row.length) {
        var $field = $row.find('[data-col-path="'+colPath+'"]');
        
        if ($field.length) {
            
            if ($field.hasClass('radioInit')) {

                $("input[type='radio']", $field).prop('disabled', true);
                $.uniform.update($("input[type='radio']", $field));

            } else if ($field.hasClass('select2')) {  

                $field.select2('readonly', true);

            } else if ($field.hasClass('popupInit')) {
            
                var $codeName = $field.closest('div.meta-autocomplete-wrap');

                if ($codeName.length) {
                    $codeName.find("input[type='text']").removeAttr('readonly disabled tabindex');
                    $codeName.find("button").removeAttr('style disabled');
                }
            } else if ($field.hasClass('booleanInit')) {  

                $field.attr({'data-isdisabled': 'true', style: 'cursor: not-allowed', 'tabindex': '-1'});
                $field.closest('.checker').addClass('disabled');

            } else {
                $field.attr('readonly', 'readonly');
            }
        }
    }
    return;
}
function bpKpiRowColEnable(mainSelector, elem, colPath) {
    
    if (elem !== 'open') {
        if (typeof elem.prop('tagName') !== 'undefined' && elem.prop('tagName') == 'TR') {
            var $row = elem; 
        } else {
            var $row = elem.closest('tr'); 
        }
    } else {
        var $row = $();
    }
    
    if ($row.length) {
        var $field = $row.find('[data-col-path="'+colPath+'"]');
        
        if ($field.length) {
            
            if ($field.hasClass('radioInit')) {

                $("input[type='radio']", $field).removeAttr('disabled');
                $.uniform.update($("input[type='radio']", $field));

            } else if ($field.hasClass('select2')) {  

                $field.select2('readonly', false).select2('enable');

            } else if ($field.hasClass('popupInit')) {
            
                var $codeName = $field.closest('div.meta-autocomplete-wrap');

                if ($codeName.length) {
                    $codeName.find("input[type='text']").removeAttr('readonly disabled tabindex');
                    $codeName.find("button").removeAttr('style disabled');
                }
            } else if ($field.hasClass('booleanInit')) {  

                $field.removeAttr('onclick style data-isdisabled tabindex');
                $field.closest('.checker').removeClass('disabled');
                $.uniform.update($field);

            } else {
                $field.removeAttr('readonly disabled');
            }
        }
    }
    return;
}
function bpKpiCellDisable(mainSelector, elem, path) {
    var $field = mainSelector.find('[data-path-cell="'+path+'"]'); 
    if ($field.length) {
        $field.attr('readonly', 'readonly');
    }
    return;
}
function bpKpiColEnable(mainSelector, elem, path) {
    var $getField = mainSelector.find('[data-col-path="'+path+'"]');

    if ($getField.length) {
        
        if ($getField.hasClass('radioInit')) {
            
            var $radios = $getField.find("input[type='radio']");
            $radios.prop('disabled', false);
            $.uniform.update($radios);
            
        } else if ($getField.hasClass('select2')) {  
            
            $getField.select2('readonly', false).select2('enable');
            
        } else if ($getField.hasClass('popupInit')) {
            
            var $codeName = $getField.closest('div.meta-autocomplete-wrap');
            
            if ($codeName.length) {
                $codeName.find("input[type='text']").removeAttr('readonly disabled tabindex');
                $codeName.find("button").removeAttr('style disabled');
            }
        } else if ($getField.hasClass('booleanInit')) {  

            $getField.removeAttr('onclick style data-isdisabled tabindex');
            $getField.closest('.checker').removeClass('disabled');
            $.uniform.update($getField);

        } else {
            $getField.prop('readonly', false);
        }
    }
    return;
}
function bpKpiRowEnable(mainSelector, elem, path) {
    var $row = mainSelector.find('tr[data-dtl-code="'+path+'"]'); 
    
    if ($row.length) {
        $row.find('> td input[data-path]').removeAttr('readonly disabled tabindex onkeydown').removeClass('disable-click');
        $row.find('> td textarea[data-path]').prop('readonly', false);
        $row.find('> td select.select2').select2('readonly', false).select2('enable');
        $row.find('button').removeAttr('style disabled');
    }
    return;
}
function bpKpiCellEnable(mainSelector, elem, path) {
    var $field = mainSelector.find('[data-path-cell="'+path+'"]'); 
    if ($field.length) {
        $field.prop('readonly', false);
    }
    return;
}
function bpSetKpiCellVal(mainSelector, elem, fieldPath, val) {
    
    var $getField = mainSelector.find("[data-path-cell='"+fieldPath+"']");

    if ($getField.length) {

        if ($getField.hasClass('radioInit')) {

            $("input[type='radio'][value='"+val+"']", $getField).prop('checked', true);
            $.uniform.update($("input[type='radio']", $getField));

        } else if ($getField.hasClass('longInit') 
            || $getField.hasClass('numberInit') 
            || $getField.hasClass('decimalInit') 
            || $getField.hasClass('integerInit') 
            || $getField.hasClass('bigdecimalInit') 
            || $getField.hasClass('kpiDecimalInit')) { 

            if (val !== '' && val !== null) {
                $getField.autoNumeric('set', val);
            } else {
                $getField.autoNumeric('set', '');
            }

        } else if ($getField.hasClass('dateInit')) {

            if (val !== '' && val !== null) {
                $getField.datepicker('update', date('Y-m-d', strtotime(val)));
            } else {
                $getField.datepicker('update', null);
            }

        } else if ($getField.hasClass('datetimeInit')) {

            if (val !== '' && val !== null) {
                $getField.val(date('Y-m-d H:i:s', strtotime(val)));
            } else {
                $getField.val('');
            }

        } else if ($getField.hasClass('popupInit')) {   

            setLookupPopupValue($getField, val);

        } else if ($getField.hasClass('booleanInit')) {  

            checkboxCheckerUpdate($getField, val);

        } else if ($getField.hasClass('select2')) {

            if ($getField.find("option").length > 2) {
                $getField.find("option").filter('[value="' + val + '"]').attr('selected', 'selected');
            } else if ($getField.attr('data-row-data') !== 'undefined') {
                comboSingleDataSet($getField, val);
            }

        } else {                                              
            $getField.val(val);                        
        }
    }
    
    return;
}
function bpGetKpiTemplateCellVal(uniqId, fieldPath) {
    
    var mainSelector = $('#kpiDmDtl-' + uniqId), selectedVal = '';
    
    if (typeof mainSelector !== 'undefined') {
        
        var fieldPath = fieldPath.split('.'), 
            dtlCode = fieldPath[0].toLowerCase(), 
            $table = mainSelector.find('table[data-table-path="kpiDmDtl"]:eq(0)'), 
            $getRow = $table.find("tbody > tr[data-dtl-code='"+dtlCode+"']");

        if ($getRow.length) {

            var factName = fieldPath[1].toLowerCase(), paramRealPath = 'kpiDmDtl.'+factName;
            var groupPath = $table.attr('data-group-path');

            if (groupPath) {
                var $getField = $getRow.find('[data-path="'+groupPath + paramRealPath+'"]:eq(0)');
            } else {
                var $getField = $getRow.find('[data-path="'+paramRealPath+'"]:eq(0)');
            }

            if ($getField.length) {

                if ($getField.hasClass('radioInit')) {

                    var selected = $("input[type='radio']:checked", $getField);
                    if (selected.length > 0) {
                        selectedVal = selected.val();
                    }

                } else if ($getField.hasClass('numberInit') 
                        || $getField.hasClass('decimalInit')
                        || $getField.hasClass('integerInit') 
                        || $getField.hasClass('bigdecimalInit') 
                        || $getField.hasClass('kpiDecimalInit')) {   

                    selectedVal = Number($getField.autoNumeric('get'));

                } else if ($getField.hasClass('longInit')) {
                    selectedVal = $getField.autoNumeric('get');
                } else if ($getField.hasClass('booleanInit')) {
                    selectedVal = $getField.is(':checked');
                } else {
                    selectedVal = $getField.val();
                }
            }
        }
    }
    
    return selectedVal;
}
function bpSetKpiTemplateCellVal(uniqId, fieldPath, val) {
    
    var mainSelector = $('#kpiDmDtl-' + uniqId);
    
    if (typeof mainSelector !== 'undefined') {
        
        var fieldPath = fieldPath.split('.'), 
            dtlCode = fieldPath[0].toLowerCase(), 
            $table = mainSelector.find('table[data-table-path="kpiDmDtl"]:eq(0)'), 
            $getRow = $table.find("tbody > tr[data-dtl-code='"+dtlCode+"']");

        if ($getRow.length) {

            var factName = fieldPath[1].toLowerCase(), paramRealPath = 'kpiDmDtl.'+factName;
            var groupPath = $table.attr('data-group-path');

            if (groupPath) {
                var $getField = $getRow.find('[data-path="'+groupPath + paramRealPath+'"]:eq(0)');
            } else {
                var $getField = $getRow.find('[data-path="'+paramRealPath+'"]:eq(0)');
            }

            if ($getField.length) {

                if ($getField.hasClass('radioInit')) {

                    $("input[type='radio'][value='"+val+"']", $getField).prop('checked', true);
                    $.uniform.update($("input[type='radio']", $getField));

                } else if ($getField.hasClass('longInit') 
                    || $getField.hasClass('numberInit') 
                    || $getField.hasClass('decimalInit') 
                    || $getField.hasClass('integerInit') 
                    || $getField.hasClass('bigdecimalInit') 
                    || $getField.hasClass('kpiDecimalInit')) { 

                    if (val !== '' && val !== null) {
                        $getField.autoNumeric('set', val);
                    } else {
                        $getField.autoNumeric('set', '');
                    }

                } else if ($getField.hasClass('dateInit')) {

                    if (val !== '' && val !== null) {
                        $getField.datepicker('update', date('Y-m-d', strtotime(val)));
                    } else {
                        $getField.datepicker('update', null);
                    }

                } else if ($getField.hasClass('datetimeInit')) {

                    if (val !== '' && val !== null) {
                        $getField.val(date('Y-m-d H:i:s', strtotime(val)));
                    } else {
                        $getField.val('');
                    }

                } else if ($getField.hasClass('popupInit')) {   

                    setLookupPopupValue($getField, val);

                } else if ($getField.hasClass('booleanInit')) {  

                    checkboxCheckerUpdate($getField, val);

                } else if ($getField.hasClass('select2')) {

                    if ($getField.find("option").length > 2) {
                        $getField.find("option").filter('[value="' + val + '"]').attr('selected', 'selected');
                    } else if ($getField.attr('data-row-data') !== 'undefined') {
                        comboSingleDataSet($getField, val);
                    }

                } else {                                              
                    $getField.val(val);                        
                }
            }
        }
    }
    
    return;
}
function bpGetKpiCellVal(mainSelector, elem, fieldPath) {
    
    var selectedVal = '', $getField = mainSelector.find("[data-path-cell='"+fieldPath+"']");

    if ($getField.length) {

        if ($getField.hasClass('radioInit')) {

            var selected = $("input[type='radio']:checked", $getField);
            if (selected.length > 0) {
                selectedVal = selected.val();
            }

        } else if ($getField.hasClass('numberInit') 
                || $getField.hasClass('decimalInit')
                || $getField.hasClass('integerInit') 
                || $getField.hasClass('bigdecimalInit') 
                || $getField.hasClass('kpiDecimalInit')) {   

            selectedVal = Number($getField.autoNumeric('get'));

        } else if ($getField.hasClass('longInit')) {
            selectedVal = $getField.autoNumeric('get');
        } else if ($getField.hasClass('booleanInit')) {
            selectedVal = $getField.is(':checked');
        } else {
            selectedVal = $getField.val();
        }
    }
    
    return selectedVal;
}
function bpGetKpiData(mainSelector, elem, tmpCode, criteria) {
    var result = [], paramData = [], paramsPathArr = criteria.split('|');

    for (var i = 0; i < paramsPathArr.length; i++) {
        var fieldPathArr = paramsPathArr[i].split('@'), 
            fieldPath = fieldPathArr[0].trim(), 
            inputPath = fieldPathArr[1].trim(), 
            fieldValue = '';

        var $bpElem = getBpElement(mainSelector, elem, fieldPath);

        if ($bpElem) {
            fieldValue = getBpRowParamNum(mainSelector, elem, fieldPath);
        } else {
            fieldValue = fieldPath;
        }

        paramData.push({
            fieldPath: fieldPath, 
            inputPath: inputPath, 
            value: fieldValue
        });
    }
    
    $.ajax({
        type: 'post',
        url: 'api/getKpiData',
        data: {tmpCode: tmpCode, paramData: paramData},
        dataType: 'json', 
        async: false,
        success: function(data){
            result = data;
        }
    });
    return result;
}
function bpGetKpiDataSum(data, type, path) {
    var result = '';
    
    if (type == 'column') {
        //002.fact1.1
        var dc = new DataCollection(data);
        var fields = path.split('.'), 
            tmpCode = fields[0], factCode = fields[1], colIndex = fields[2];
    
        var result = Number(dc.query().filter({templatecode: tmpCode, factcode: factCode, colindex: colIndex, val__not: null}).sum('val'));

    } else if (type == 'allcolumn') {
        
        var dc = new DataCollection(data);
        var fields = path.split('.'), 
            factCode = fields[0], colIndex = fields[1];
    
        var result = Number(dc.query().filter({factcode: factCode, colindex: colIndex, val__not: null}).sum('val'));
        
    } else if (type == 'columnrange') {
        
        var dc = new DataCollection(data);
        var fields = path.split('.'), 
            tmpCode = fields[0],     
            factCode = fields[1], colIndex = fields[2], 
            rangeIndex = fields[3].split(':'), 
            firstIndex = Number(rangeIndex[0]) - 1, lastIndex = Number(rangeIndex[1]);
            
        var dcValues = dc.query().filter({templatecode: tmpCode, factcode: factCode, colindex: colIndex, val__not: null}).values(), 
            dcValuesLen = dcValues.length;
        
        if (dcValuesLen == 1) {
            
            result = Number(dcValues[0]['val']);
            
        } else if (dcValuesLen > 1) {
            
            var sliceValues = dcValues.slice(firstIndex, lastIndex), 
                sum = 0, sliceValuesLen = sliceValues.length, i = 0;

            for (i; i < sliceValuesLen; i++) {
                sum += Number(sliceValues[i]['val']);
            }

            result = sum;
        }
        
    } else if (type == 'allcolumnrange') {
        
        var dc = new DataCollection(data);
        var fields = path.split('.'),     
            factCode = fields[0], colIndex = fields[1], 
            rangeIndex = fields[2].split(':'), 
            firstIndex = Number(rangeIndex[0]) - 1, lastIndex = Number(rangeIndex[1]);
            
        var dcValues = dc.query().filter({factcode: factCode, colindex: colIndex, val__not: null}).values(), 
            dcValuesLen = dcValues.length;
        
        if (dcValuesLen == 1) {
            
            result = Number(dcValues[0]['val']);
            
        } else if (dcValuesLen > 1) {
            
            var sliceValues = dcValues.slice(firstIndex, lastIndex), 
                sum = 0, sliceValuesLen = sliceValues.length, i = 0;

            for (i; i < sliceValuesLen; i++) {
                sum += Number(sliceValues[i]['val']);
            }

            result = sum;
        }
        
    } else if (type == 'row') {
        //002.r-001
        var dc = new DataCollection(data);
        var fields = path.split('.'), 
            tmpCode = fields[0], rowCode = fields[1];
    
        var result = Number(dc.query().filter({templatecode: tmpCode, rowcode: rowCode, val__not: null}).sum('val'));
        
    } else if (type == 'allrow') {
        
        var dc = new DataCollection(data);
        var result = Number(dc.query().filter({rowcode: path, val__not: null}).sum('val'));
        
    } else if (type == 'rowrange') {
        
        var dc = new DataCollection(data);
        var fields = path.split('.'), 
            tmpCode = fields[0],     
            rowCode = fields[1], 
            rangeIndex = fields[2].split(':'), 
            firstIndex = Number(rangeIndex[0]) - 1, lastIndex = Number(rangeIndex[1]);
            
        var dcValues = dc.query().filter({templatecode: tmpCode, rowcode: rowCode, val__not: null}).values(), 
            dcValuesLen = dcValues.length;
        
        if (dcValuesLen == 1) {
            
            result = Number(dcValues[0]['val']);
            
        } else if (dcValuesLen > 1) {
            
            var sliceValues = dcValues.slice(firstIndex, lastIndex), 
                sum = 0, sliceValuesLen = sliceValues.length, i = 0;

            for (i; i < sliceValuesLen; i++) {
                sum += Number(sliceValues[i]['val']);
            }

            result = sum;
        }
        
    } else if (type == 'allrowrange') {
        
        var dc = new DataCollection(data);
        var fields = path.split('.'),     
            rowCode = fields[0], 
            rangeIndex = fields[1].split(':'), 
            firstIndex = Number(rangeIndex[0]) - 1, lastIndex = Number(rangeIndex[1]);
            
        var dcValues = dc.query().filter({rowcode: rowCode, val__not: null}).values(), 
            dcValuesLen = dcValues.length;
        
        if (dcValuesLen == 1) {
            
            result = Number(dcValues[0]['val']);
            
        } else if (dcValuesLen > 1) {
            
            var sliceValues = dcValues.slice(firstIndex, lastIndex), 
                sum = 0, sliceValuesLen = sliceValues.length, i = 0;

            for (i; i < sliceValuesLen; i++) {
                sum += Number(sliceValues[i]['val']);
            }

            result = sum;
        }
    }
    
    return result;
}
function bpGetKpiDataMax(data, type, path) {
    var result = '';
    
    if (type == 'column') {

        var dc = new DataCollection(data);
        var fields = path.split('.'), 
            tmpCode = fields[0], factCode = fields[1], colIndex = fields[2];
    
        var result = Number(dc.query().filter({templatecode: tmpCode, factcode: factCode, colindex: colIndex, val__not: null}).max('val'));
        
    } else if (type == 'allcolumn') {
        
        var dc = new DataCollection(data);
        var fields = path.split('.'), 
            factCode = fields[0], colIndex = fields[1];
    
        var result = Number(dc.query().filter({factcode: factCode, colindex: colIndex, val__not: null}).max('val'));
        
    } else if (type == 'columnrange') {
        
        var dc = new DataCollection(data);
        var fields = path.split('.'), 
            tmpCode = fields[0],     
            factCode = fields[1], colIndex = fields[2], 
            rangeIndex = fields[3].split(':'), 
            firstIndex = Number(rangeIndex[0]) - 1, lastIndex = Number(rangeIndex[1]);
            
        var dcValues = dc.query().filter({templatecode: tmpCode, factcode: factCode, colindex: colIndex, val__not: null}).values(), 
            dcValuesLen = dcValues.length;
        
        if (dcValuesLen == 1) {
            
            result = Number(dcValues[0]['val']);
            
        } else if (dcValuesLen > 1) {
            
            var sliceValues = dcValues.slice(firstIndex, lastIndex), 
                max = 0, v = 0, sliceValuesLen = sliceValues.length, i = 0;

            for (i; i < sliceValuesLen; i++) {
                v = Number(sliceValues[i]['val']);
                if (max < v){max = v;}
            }

            result = max;
        }
        
    } else if (type == 'allcolumnrange') {
        
        var dc = new DataCollection(data);
        var fields = path.split('.'),     
            factCode = fields[0], colIndex = fields[1], 
            rangeIndex = fields[2].split(':'), 
            firstIndex = Number(rangeIndex[0]) - 1, lastIndex = Number(rangeIndex[1]);
            
        var dcValues = dc.query().filter({factcode: factCode, colindex: colIndex, val__not: null}).values(), 
            dcValuesLen = dcValues.length;
        
        if (dcValuesLen == 1) {
            
            result = Number(dcValues[0]['val']);
            
        } else if (dcValuesLen > 1) {
            
            var sliceValues = dcValues.slice(firstIndex, lastIndex), 
                max = 0, v = 0, sliceValuesLen = sliceValues.length, i = 0;

            for (i; i < sliceValuesLen; i++) {
                v = Number(sliceValues[i]['val']);
                if (max < v){max = v;}
            }

            result = max;
        }
        
    } else if (type == 'row') {

        var dc = new DataCollection(data);
        var fields = path.split('.'), 
            tmpCode = fields[0], rowCode = fields[1];
    
        var result = Number(dc.query().filter({templatecode: tmpCode, rowcode: rowCode, val__not: null}).max('val'));
        
    } else if (type == 'allrow') {
        
        var dc = new DataCollection(data);
        var result = Number(dc.query().filter({rowcode: path, val__not: null}).max('val'));
        
    } else if (type == 'rowrange') {
        
        var dc = new DataCollection(data);
        var fields = path.split('.'), 
            tmpCode = fields[0],     
            rowCode = fields[1], 
            rangeIndex = fields[2].split(':'), 
            firstIndex = Number(rangeIndex[0]) - 1, lastIndex = Number(rangeIndex[1]);
            
        var dcValues = dc.query().filter({templatecode: tmpCode, rowcode: rowCode, val__not: null}).values(), 
            dcValuesLen = dcValues.length;
        
        if (dcValuesLen == 1) {
            
            result = Number(dcValues[0]['val']);
            
        } else if (dcValuesLen > 1) {
            
            var sliceValues = dcValues.slice(firstIndex, lastIndex), 
                max = 0, v = 0, sliceValuesLen = sliceValues.length, i = 0;

            for (i; i < sliceValuesLen; i++) {
                v = Number(sliceValues[i]['val']);
                if (max < v){max = v;}
            }

            result = max;
        }
        
    } else if (type == 'allrowrange') {
        
        var dc = new DataCollection(data);
        var fields = path.split('.'),     
            rowCode = fields[0], 
            rangeIndex = fields[1].split(':'), 
            firstIndex = Number(rangeIndex[0]) - 1, lastIndex = Number(rangeIndex[1]);
            
        var dcValues = dc.query().filter({rowcode: rowCode, val__not: null}).values(), 
            dcValuesLen = dcValues.length;
        
        if (dcValuesLen == 1) {
            
            result = Number(dcValues[0]['val']);
            
        } else if (dcValuesLen > 1) {
            
            var sliceValues = dcValues.slice(firstIndex, lastIndex), 
                max = 0, v = 0, sliceValuesLen = sliceValues.length, i = 0;

            for (i; i < sliceValuesLen; i++) {
                v = Number(sliceValues[i]['val']);
                if (max < v){max = v;}
            }

            result = max;
        }
    }
    
    return result;
}
function bpGetKpiDataMin(data, type, path) {
    var result = '';
    
    if (type == 'column') {

        var dc = new DataCollection(data);
        var fields = path.split('.'), 
            tmpCode = fields[0], factCode = fields[1], colIndex = fields[2];
    
        var result = Number(dc.query().filter({templatecode: tmpCode, factcode: factCode, colindex: colIndex, val__not: null}).min('val'));
        
    } else if (type == 'allcolumn') {
        
        var dc = new DataCollection(data);
        var fields = path.split('.'), 
            factCode = fields[0], colIndex = fields[1];
    
        var result = Number(dc.query().filter({factcode: factCode, colindex: colIndex, val__not: null}).min('val'));
        
    } else if (type == 'columnrange') {
        
        var dc = new DataCollection(data);
        var fields = path.split('.'), 
            tmpCode = fields[0],     
            factCode = fields[1], colIndex = fields[2], 
            rangeIndex = fields[3].split(':'), 
            firstIndex = Number(rangeIndex[0]) - 1, lastIndex = Number(rangeIndex[1]);
            
        var dcValues = dc.query().filter({templatecode: tmpCode, factcode: factCode, colindex: colIndex, val__not: null}).values(), 
            dcValuesLen = dcValues.length;
        
        if (dcValuesLen == 1) {
            
            result = Number(dcValues[0]['val']);
            
        } else if (dcValuesLen > 1) {
            
            var sliceValues = dcValues.slice(firstIndex, lastIndex), 
                min = 0, v = 0, sliceValuesLen = sliceValues.length, i = 0;

            for (i; i < sliceValuesLen; i++) {
                v = Number(sliceValues[i]['val']);
                if(min > v){min = v;}
            }

            result = min;
        }
        
    } else if (type == 'allcolumnrange') {
        
        var dc = new DataCollection(data);
        var fields = path.split('.'),     
            factCode = fields[0], colIndex = fields[1], 
            rangeIndex = fields[2].split(':'), 
            firstIndex = Number(rangeIndex[0]) - 1, lastIndex = Number(rangeIndex[1]);
            
        var dcValues = dc.query().filter({factcode: factCode, colindex: colIndex, val__not: null}).values(), 
            dcValuesLen = dcValues.length;
        
        if (dcValuesLen == 1) {
            
            result = Number(dcValues[0]['val']);
            
        } else if (dcValuesLen > 1) {
            
            var sliceValues = dcValues.slice(firstIndex, lastIndex), 
                min = 0, v = 0, sliceValuesLen = sliceValues.length, i = 0;

            for (i; i < sliceValuesLen; i++) {
                v = Number(sliceValues[i]['val']);
                if(min > v){min = v;}
            }

            result = min;
        }
        
    } else if (type == 'row') {

        var dc = new DataCollection(data);
        var fields = path.split('.'), 
            tmpCode = fields[0], rowCode = fields[1];
    
        var result = Number(dc.query().filter({templatecode: tmpCode, rowcode: rowCode, val__not: null}).min('val'));
        
    } else if (type == 'allrow') {
        
        var dc = new DataCollection(data);
        var result = Number(dc.query().filter({rowcode: path, val__not: null}).min('val'));
        
    } else if (type == 'rowrange') {
        
        var dc = new DataCollection(data);
        var fields = path.split('.'), 
            tmpCode = fields[0],     
            rowCode = fields[1], 
            rangeIndex = fields[2].split(':'), 
            firstIndex = Number(rangeIndex[0]) - 1, lastIndex = Number(rangeIndex[1]);
            
        var dcValues = dc.query().filter({templatecode: tmpCode, rowcode: rowCode, val__not: null}).values(), 
            dcValuesLen = dcValues.length;
        
        if (dcValuesLen == 1) {
            
            result = Number(dcValues[0]['val']);
            
        } else if (dcValuesLen > 1) {
            
            var sliceValues = dcValues.slice(firstIndex, lastIndex), 
                min = 0, v = 0, sliceValuesLen = sliceValues.length, i = 0;

            for (i; i < sliceValuesLen; i++) {
                v = Number(sliceValues[i]['val']);
                if(min > v){min = v;}
            }

            result = min;
        }
        
    } else if (type == 'allrowrange') {
        
        var dc = new DataCollection(data);
        var fields = path.split('.'),     
            rowCode = fields[0], 
            rangeIndex = fields[1].split(':'), 
            firstIndex = Number(rangeIndex[0]) - 1, lastIndex = Number(rangeIndex[1]);
            
        var dcValues = dc.query().filter({rowcode: rowCode, val__not: null}).values(), 
            dcValuesLen = dcValues.length;
        
        if (dcValuesLen == 1) {
            
            result = Number(dcValues[0]['val']);
            
        } else if (dcValuesLen > 1) {
            
            var sliceValues = dcValues.slice(firstIndex, lastIndex), 
                min = 0, v = 0, sliceValuesLen = sliceValues.length, i = 0;

            for (i; i < sliceValuesLen; i++) {
                v = Number(sliceValues[i]['val']);
                if(min > v){min = v;}
            }

            result = min;
        }
    }
    
    return result;
}
function bpGetKpiDataAvg(data, type, path) {
    var result = '';
    
    if (type == 'column') {

        var dc = new DataCollection(data);
        var fields = path.split('.'), 
            tmpCode = fields[0], factCode = fields[1], colIndex = fields[2];
    
        var result = Number(dc.query().filter({templatecode: tmpCode, factcode: factCode, colindex: colIndex, val__not: null}).avg('val'));
        
    } else if (type == 'allcolumn') {
        
        var dc = new DataCollection(data);
        var fields = path.split('.'), 
            factCode = fields[0], colIndex = fields[1];
    
        var result = Number(dc.query().filter({factcode: factCode, colindex: colIndex, val__not: null}).avg('val'));
        
    } else if (type == 'columnrange') {
        
        var dc = new DataCollection(data);
        var fields = path.split('.'), 
            tmpCode = fields[0],     
            factCode = fields[1], colIndex = fields[2], 
            rangeIndex = fields[3].split(':'), 
            firstIndex = Number(rangeIndex[0]) - 1, lastIndex = Number(rangeIndex[1]);
            
        var dcValues = dc.query().filter({templatecode: tmpCode, factcode: factCode, colindex: colIndex, val__not: null}).values(), 
            dcValuesLen = dcValues.length;
        
        if (dcValuesLen == 1) {
            
            result = Number(dcValues[0]['val']);
            
        } else if (dcValuesLen > 1) {
            
            var sliceValues = dcValues.slice(firstIndex, lastIndex), 
                sum = 0, sliceValuesLen = sliceValues.length, i = 0;

            for (i; i < sliceValuesLen; i++) {
                sum += Number(sliceValues[i]['val']);
            }

            result = sum / sliceValuesLen;
        }
        
    } else if (type == 'allcolumnrange') {
        
        var dc = new DataCollection(data);
        var fields = path.split('.'),     
            factCode = fields[0], colIndex = fields[1], 
            rangeIndex = fields[2].split(':'), 
            firstIndex = Number(rangeIndex[0]) - 1, lastIndex = Number(rangeIndex[1]);
            
        var dcValues = dc.query().filter({factcode: factCode, colindex: colIndex, val__not: null}).values(), 
            dcValuesLen = dcValues.length;
        
        if (dcValuesLen == 1) {
            
            result = Number(dcValues[0]['val']);
            
        } else if (dcValuesLen > 1) {
            
            var sliceValues = dcValues.slice(firstIndex, lastIndex), 
                sum = 0, sliceValuesLen = sliceValues.length, i = 0;

            for (i; i < sliceValuesLen; i++) {
                sum += Number(sliceValues[i]['val']);
            }

            result = sum / sliceValuesLen;
        }
        
    } else if (type == 'row') {

        var dc = new DataCollection(data);
        var fields = path.split('.'), 
            tmpCode = fields[0], rowCode = fields[1];
    
        var result = Number(dc.query().filter({templatecode: tmpCode, rowcode: rowCode, val__not: null}).avg('val'));
        
    } else if (type == 'allrow') {
        
        var dc = new DataCollection(data);
        var result = Number(dc.query().filter({rowcode: path, val__not: null}).avg('val'));
        
    } else if (type == 'rowrange') {
        
        var dc = new DataCollection(data);
        var fields = path.split('.'), 
            tmpCode = fields[0],     
            rowCode = fields[1], 
            rangeIndex = fields[2].split(':'), 
            firstIndex = Number(rangeIndex[0]) - 1, lastIndex = Number(rangeIndex[1]);
            
        var dcValues = dc.query().filter({templatecode: tmpCode, rowcode: rowCode, val__not: null}).values(), 
            dcValuesLen = dcValues.length;
        
        if (dcValuesLen == 1) {
            
            result = Number(dcValues[0]['val']);
            
        } else if (dcValuesLen > 1) {
            
            var sliceValues = dcValues.slice(firstIndex, lastIndex), 
                sum = 0, sliceValuesLen = sliceValues.length, i = 0;

            for (i; i < sliceValuesLen; i++) {
                sum += Number(sliceValues[i]['val']);
            }

            result = sum / sliceValuesLen;
        }
        
    } else if (type == 'allrowrange') {
        
        var dc = new DataCollection(data);
        var fields = path.split('.'),     
            rowCode = fields[0], 
            rangeIndex = fields[1].split(':'), 
            firstIndex = Number(rangeIndex[0]) - 1, lastIndex = Number(rangeIndex[1]);
            
        var dcValues = dc.query().filter({rowcode: rowCode, val__not: null}).values(), 
            dcValuesLen = dcValues.length;
        
        if (dcValuesLen == 1) {
            
            result = Number(dcValues[0]['val']);
            
        } else if (dcValuesLen > 1) {
            
            var sliceValues = dcValues.slice(firstIndex, lastIndex), 
                sum = 0, sliceValuesLen = sliceValues.length, i = 0;

            for (i; i < sliceValuesLen; i++) {
                sum += Number(sliceValues[i]['val']);
            }

            result = sum / sliceValuesLen;
        }
    }
    
    return result;
}
function bpGetKpiDataCellVal(data, path) {
    var dc = new DataCollection(data);
    var fields = path.split('.'), 
        tmpCode = fields[0], rowCode = fields[1], factCode = fields[2], colIndex = fields[3], result = '';
    var dcValues = dc.query().filter({templatecode: tmpCode, rowcode: rowCode, factcode: factCode, colindex: colIndex}).values();

    if (dcValues && dcValues.hasOwnProperty(0)) {
        result = Number(dcValues[0].val);
    }
    
    return result;
}
function bpSetKpiCellStyle(mainSelector, fieldPath, styles) {
    /*var $getField = mainSelector.find("[data-path-cell='"+path+"']");

    if ($getField.length) {
        $getField.attr('style', styles);
    }*/
    
    var fieldPath = fieldPath.split('.');
    
    if (fieldPath.length == 2) {
        var dtlCode = fieldPath[0].toLowerCase();
        var factName = fieldPath[1].toLowerCase();
        var $getRow = mainSelector.find("[data-dtl-code='"+dtlCode+"']");

        if ($getRow.length) {
            var $getField = $getRow.find('[data-path="kpiDmDtl.'+factName+'"]:eq(0)');
            $getField.attr('style', styles);
        }
    }
    return;
}
function bpUnsetKpiCellStyle(mainSelector, fieldPath) {
    /*var $getField = mainSelector.find("[data-path-cell='"+path+"']");

    if ($getField.length) {
        $getField.removeAttr('style');
    }*/
    
    var fieldPath = fieldPath.split('.');
    
    if (fieldPath.length == 2) {
        var dtlCode = fieldPath[0].toLowerCase();
        var factName = fieldPath[1].toLowerCase();
        var $getRow = mainSelector.find("[data-dtl-code='"+dtlCode+"']");

        if ($getRow.length) {
            var $getField = $getRow.find('[data-path="kpiDmDtl.'+factName+'"]:eq(0)');
            $getField.removeAttr('style');
        }
    }
    
    return;
}
function bpSetKpiColStyle(mainSelector, path, styles) {
    var $getField = mainSelector.find('[data-col-path="'+path+'"]');

    if ($getField.length) {
        $getField.attr('style', styles);
    }
    return;
}
function bpUnsetKpiColStyle(mainSelector, path) {
    var $getField = mainSelector.find('[data-col-path="'+path+'"]');

    if ($getField.length) {
        $getField.removeAttr('style');
    }
    return;
}
function bpSetKpiRowColStyle(mainSelector, elem, colPath, styles) {
    if (elem !== 'open') {
        if (typeof elem.prop('tagName') !== 'undefined' && elem.prop('tagName') == 'TR') {
            var $row = elem; 
        } else {
            var $row = elem.closest('tr'); 
        }
    } else {
        var $row = $();
    }
    
    if ($row.length) {
        var $field = $row.find('[data-col-path="'+colPath+'"]');
        
        if ($field.length) {
            $field.attr('style', styles);
        }
    }
    return;
}
function bpSetKpiRowStyle(mainSelector, path, styles) {
    var $row = mainSelector.find('tr[data-dtl-code="'+path+'"]'); 

    if ($row.length) {
        $row.find('> td > input').attr('style', styles);
    }
    return;
}
function bpUnsetKpiRowStyle(mainSelector, path) {
    var $row = mainSelector.find('tr[data-dtl-code="'+path+'"]'); 

    if ($row.length) {
        $row.find('> td > input').removeAttr('style');
    }
    return;
}
function bpSetKpiRowColRequired(mainSelector, elem, colPath) {
    if (elem !== 'open') {
        if (typeof elem.prop('tagName') !== 'undefined' && elem.prop('tagName') == 'TR') {
            var $row = elem; 
        } else {
            var $row = elem.closest('tr'); 
        }
    } else {
        var $row = $();
    }
    
    if ($row.length) {
        var $field = $row.find('[data-col-path="'+colPath+'"]');
        
        if ($field.length) {
            var $titleName = $field.closest('td').find('.title-name');
            
            if ($field.is(':radio')) {
                $field.attr('required', 'required');
            } else if (!$field.parent().find('.select2-container-disabled').length) {
                $field.attr('required', 'required');
            }
            
            if ($titleName.length) {
                $titleName.find('span.required').remove();
                $titleName.prepend('<span class="required">*</span>');
            }
        }
    }
    return;
}
function bpSetKpiRowColNonRequired(mainSelector, elem, colPath) {
    if (elem !== 'open') {
        if (typeof elem.prop('tagName') !== 'undefined' && elem.prop('tagName') == 'TR') {
            var $row = elem; 
        } else {
            var $row = elem.closest('tr'); 
        }
    } else {
        var $row = $();
    }
    
    if ($row.length) {
        var $field = $row.find('[data-col-path="'+colPath+'"]');
        
        if ($field.length) {
            $field.removeAttr('required');
            $field.closest('td').find('.title-name').find('span.required').remove();
        }
    }
    return;
}
function bpKpiCheckGroupSum(mainSelector) {
    
    var $rows = mainSelector.find('[data-aggregate-indicator]'); 
    
    if ($rows.length) {
        var len = $rows.length, i = 0;
    
        for (i; i < len; i++) { 
            var $row = $($rows[i]);
            var sumAmount = Number($row.attr('data-max-val'));
            
            if (sumAmount > 0) {
                
                var factCode = $row.attr('data-fact-code');
                var dtlId    = $row.attr('data-aggregate-indicator');
                var checkSum = Number(mainSelector.find('input[data-col-path="'+factCode+'"][data-parent-dtlid="'+dtlId+'"]').sum()); 
                
                if (sumAmount < checkSum) {
                    return false;
                }
            }
        }
    }
    
    return true;
}
function bpKpiCheckFactSum(mainSelector) {
    
    var $columns = mainSelector.find('[data-indicator-criteria="1"]'); 
    
    if ($columns.length) {
        
        var $table = $columns.closest('table');
        var len = $columns.length, i = 0;
    
        for (i; i < len; i++) { 
            
            var $col = $($columns[i]);
            var sumAmount = Number($col.attr('data-max-value'));
            
            if (sumAmount > 0) {
                
                var colPath = $col.attr('data-cell-path');
                var checkSum = Number($table.find('input[data-path="'+colPath+'"]').sum()); 
                
                if (sumAmount < checkSum) {
                    return false;
                }
            }
        }
    }
    
    return true;
}
function bpAccountSegmentConfig(elem) {
    
    var $dialogName = 'dialog-segmentconfig-'+getUniqueId(1);
    if (!$("#" + $dialogName).length) {
        $('<div id="' + $dialogName + '"></div>').appendTo('body');
    }
    var $dialog = $('#' + $dialogName), 
        $this = $(elem), 
        $parent = $this.closest('.input-group'), 
        $segmentInput = $parent.find('input'), 
        segmentCode = $segmentInput.val();
    
    $.ajax({
        type: 'post',
        url: 'mdgl/accountSegmentCriteria',
        data: {path: 'bp', segmentCode: segmentCode}, 
        dataType: 'json',
        beforeSend: function(){
            Core.blockUI({
                message: 'Loading...', 
                boxed: true 
            });
        },
        success: function (data) {
            $dialog.empty().append(data.html);
            $dialog.dialog({
                cache: false,
                resizable: true,
                bgiframe: true,
                autoOpen: false,
                title: data.title,
                width: 550,
                minWidth: 550,
                height: 'auto',
                modal: true,
                close: function () {
                    $dialog.dialog('destroy').remove();
                },
                buttons: [
                    {text: data.save_btn, class: 'btn green-meadow btn-sm', click: function () {
                        var accountSegmentFullCode = $dialog.find('input[name*="accountSegmentFullCode["]').val();
                        $segmentInput.val(accountSegmentFullCode);
                        $dialog.dialog('close');
                    }}, 
                    {text: data.close_btn, class: 'btn blue-hoki btn-sm', click: function () {
                        $dialog.dialog('close');
                    }}
                ]
            });
            $dialog.dialog('open');
            Core.unblockUI();
        },
        error: function () {
            alert("Error");
        }
    });
}
function bpAccountSegmentConfigAdd(elem) {
    var $pselect = $(elem).closest('div.input-group').parent();
    $pselect.parent().append('<div class="mt5"></div>' + $pselect[0].outerHTML);
}
function bpRandomFieldValue(mainSelector, elem, $length, $selector1, $srcValue1, $selector2, $srcValue2) {
    var response = $.ajax({
        type: 'post',
        url: 'mddoc/randomFieldValue', 
        data: {length: $length},
        dataType: 'json',
        async: false,
        success: function(data){
            if (typeof $selector1 !== 'undefined' && typeof $srcValue1 !== 'undefined') {
                setBpRowParamNum(mainSelector, (typeof elem === 'undefined' ? 'open' : elem), $selector1, data[$srcValue1]);
            }
            if (typeof $selector2 !== 'undefined' && typeof $srcValue2 !== 'undefined') {
                setBpRowParamNum(mainSelector, (typeof elem === 'undefined' ? 'open' : elem), $selector2, data[$srcValue2]);
            }
        }
    });
    
    return response.responseJSON;
}
function bpNumberExponent(base, exponent) {
    if (base && exponent) {
        return Math.pow(base, exponent);
    } else if (base && exponent < 0) {
        return 1 / Math.pow(base, exponent);
    } else {
        return 0;
    }
}
function bpRunJSFunction(mainSelector, elem, jsfunction, paramsPath) {
    var paramData = {};
    
    if (typeof paramsPath != 'undefined' && paramsPath != '') {
        
        var paramsPathArr = paramsPath.split('|');
        
        for (var i = 0; i < paramsPathArr.length; i++) {
            var fieldPathArr = paramsPathArr[i].split('@');
            var fieldPath = fieldPathArr[0].trim();
            var inputPath = fieldPathArr[1].trim();
            var fieldValue = '';

            var $bpElem = getBpElement(mainSelector, elem, fieldPath);

            if ($bpElem) {
                fieldValue = getBpRowParamNum(mainSelector, elem, fieldPath);
            } else {
                fieldValue = fieldPath;
            }

            paramData[inputPath] = fieldValue;
        }
    }

    window[jsfunction](elem, paramData);
    
    return;
}
function bpCallDataViewByExp(mainSelector, elem, dvId, params, dialogSize) {
    var $dialogName = 'dialog-bp-dataview';
    if (!$("#" + $dialogName).length) {
        $('<div id="' + $dialogName + '"></div>').appendTo('body');
    }
    var $dialog = $('#' + $dialogName), paramData = {};
    
    if (params) {
        var paramsArr = params.split('|'), paramsLength = paramsArr.length;
        
        for (var i = 0; i < paramsLength; i++) {
            
            var fieldPathArr = paramsArr[i].split('@'), 
                fieldPath = fieldPathArr[0].trim(), 
                inputPath = fieldPathArr[1].trim(), 
                fieldValue = '', 
                $bpElem = getBpElement(mainSelector, elem, fieldPath),
                $bpViewElem = getBpRowParamViewVal(mainSelector, elem, fieldPath);

            if ($bpElem) {
                if ($bpElem.hasClass('popupInit')) {
                    var $parent = $bpElem.closest('.meta-autocomplete-wrap'), 
                        $hiddenInputs = $parent.find('input[type=hidden]');

                    fieldValue = $hiddenInputs.map(function(){ return this.value; }).get().join(',');
                } else {
                    fieldValue = getBpRowParamNum(mainSelector, elem, fieldPath);
                }
            } else if ($bpViewElem) {
                fieldValue = $bpViewElem;
            } else {
                fieldValue = fieldPath;
            }
            paramData[inputPath] = fieldValue;
        }
    }
    
    var isFullScreen = false, dialogWidth = 800, dataGridDefaultHeight = 450, isTab = false;
            
    if (typeof dialogSize !== 'undefined' && dialogSize != '') {
        if (dialogSize == 'fullscreen') {
            isFullScreen = true;
            dataGridDefaultHeight = $(window).height() - 160;
        } else if (dialogSize.indexOf('width:') !== -1) {
            dialogWidth = dialogSize.replace('width:', '').trim();
        } else if (dialogSize == 'tab') {
            isTab = true;
        }
    }
    
    $.ajax({
        type: 'post',
        url: 'mdobject/dataViewConfigRow/0/true',
        data: {
            metaDataId: dvId, 
            viewType: 'detail', 
            dataGridDefaultHeight: dataGridDefaultHeight, 
            uriParams: JSON.stringify(paramData),
            ignorePermission: 1
        },
        dataType: 'json', 
        beforeSend: function () {
            Core.blockUI({message: 'Loading...', boxed: true});
        },
        success: function (data) { 

            if (isTab) {
                appMultiTabByContent({
                    'metaDataId': dvId,
                    'title': data.row.title,
                    'content': '<div class="col-md-12 main-dataview-container main-action-meta" id="object-value-list-'+dvId+'">'+data.html+'</div>'
                });
            } else {
                $dialog.empty().append('<div class="row" id="object-value-list-'+dvId+'">' + data.html + '</div>');
                
                if (!isFullScreen) {
                    var dialogPosition = { my: 'top', at: 'top+30' };
                } else {
                    var dialogPosition = { my: 'top', at: 'top+0' };
                }
                
                $dialog.dialog({
                    cache: false,
                    resizable: true,
                    bgiframe: true,
                    autoOpen: false,
                    title: data.row.title, 
                    width: dialogWidth,
                    height: 'auto',
                    modal: true,
                    position: dialogPosition,
                    open: function () {
                        $dialog.find('.top-sidebar-content:eq(0)').attr('style', 'padding-left: 15px !important');
                        $dialog.find('a[onclick*="toQuickMenu"]').remove(); 
                        
                        /*if (!isFullScreen) {
                            setTimeout(function() {
                                $dialog.dialog('option', 'position', {my: 'center', at: 'center', of: window});
                            }, 200);
                        }*/
                    }, 
                    close: function () {
                        $dialog.empty().dialog('destroy').remove();
                    },
                    buttons: [
                        {text: plang.get('close_btn'), class: 'btn blue-madison btn-sm', click: function () {
                            $dialog.dialog('close');
                        }}
                    ]
                });
                
                if (isFullScreen) {
                    $dialog.dialogExtend({
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
                    $dialog.dialogExtend('maximize');
                }
                
                $dialog.dialog('open');
            }
            Core.unblockUI();
        },
        error: function () {
            alert('Error');
            Core.unblockUI();
        }
    });
}
function bpCallStatementByExp(mainSelector, elem, statementId, params, dialogSize) {
    var $dialogName = 'dialog-dv-statement-' + getUniqueId(1);
    if (!$("#" + $dialogName).length) {
        $('<div id="' + $dialogName + '"></div>').appendTo('body');
    }
    var $dialog = $('#' + $dialogName);
    var $detachedChildren = $dialog.children().detach(), paramData = {};
    
    if (params) {
        var paramsArr = params.split('|'), paramsLength = paramsArr.length;
        
        for (var i = 0; i < paramsLength; i++) {
            
            var fieldPathArr = paramsArr[i].split('@'), 
                fieldPath = fieldPathArr[0].trim(), 
                inputPath = fieldPathArr[1].trim(), 
                fieldValue = '', 
                $bpElem = getBpElement(mainSelector, elem, fieldPath),
                $bpViewElem = getBpRowParamViewVal(mainSelector, elem, fieldPath);

            if ($bpElem) {
                if ($bpElem.hasClass('popupInit')) {
                    var $parent = $bpElem.closest('.meta-autocomplete-wrap'), 
                        $hiddenInputs = $parent.find('input[type=hidden]');

                    fieldValue = $hiddenInputs.map(function(){ return this.value; }).get().join(',');
                } else {
                    fieldValue = getBpRowParamNum(mainSelector, elem, fieldPath);
                }
            } else if ($bpViewElem) {
                fieldValue = $bpViewElem;
            } else {
                fieldValue = fieldPath;
            }
            paramData[inputPath] = fieldValue;
        }
    }
    
    var isFullScreen = false, dialogWidth = 800, isTab = false;
            
    if (typeof dialogSize !== 'undefined' && dialogSize != '') {
        if (dialogSize == 'fullscreen') {
            isFullScreen = true;
        } else if (dialogSize.indexOf('width:') !== -1) {
            dialogWidth = dialogSize.replace('width:', '').trim();
        } else if (dialogSize == 'tab') {
            isTab = true;
        }
    }

    $dialog.dialog({
        cache: false,
        resizable: false,
        draggable: false,
        bgiframe: true,
        autoOpen: false,
        title: '',
        width: dialogWidth,
        height: 'auto',
        modal: true,
        closeOnEscape: isCloseOnEscape,
        open: function() {
            $detachedChildren.appendTo($dialog);

            $.ajax({
                type: 'post',
                url: 'mdstatement/fromDvToDrilldown',
                data: {statementId: statementId, params: $.param(paramData)},
                dataType: 'json',
                beforeSend: function() {
                    Core.blockUI({message: 'Loading...', boxed: true});
                },
                success: function(data) {
                    if (data.status == 'success') {
                        $dialog.dialog('option', 'title', data.title);
                        $dialog.empty().append(data.html);
                    } else {
                        $dialog.dialog('close');
                        PNotify.removeAll();
                        new PNotify({
                            title: data.status,
                            text: data.message,
                            type: data.status,
                            sticker: false
                        });
                    }
                }
            }).done(function() {
                Core.initDVAjax($dialog);
                Core.unblockUI();
            });
        },
        close: function() {
            $dialog.empty().dialog('destroy').remove();
        },
        buttons: [{
            text: plang.get('close_btn'),
            class: 'btn blue-hoki btn-sm',
            click: function() {
                $dialog.dialog('close');
            }
        }]
    });
    
    if (isFullScreen) {
        $dialog.dialogExtend({
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
        $dialog.dialogExtend('maximize');
    }
    
    $dialog.dialog('open');
}
function bpCallWorkspaceByExp(mainSelector, elem, workSpaceId, workSpaceName) {
    appMultiTab({metaDataId: workSpaceId, title: workSpaceName, type: 'workspace'}, elem);
}
function bpCallIndicatorDataViewByExp(mainSelector, elem, dvId, params, dialogSize) {
    var drillDownCriteria = '';
    
    if (params) {
        var paramsArr = params.split('|'), paramsLength = paramsArr.length;
        
        for (var i = 0; i < paramsLength; i++) {
            
            var fieldPathArr = paramsArr[i].split('@'), 
                fieldPath = fieldPathArr[0].trim(), 
                inputPath = fieldPathArr[1].trim(), 
                fieldValue = '', 
                $bpElem = getBpElement(mainSelector, elem, fieldPath),
                $bpViewElem = getBpRowParamViewVal(mainSelector, elem, fieldPath);

            if ($bpElem) {
                if ($bpElem.hasClass('popupInit')) {
                    var $parent = $bpElem.closest('.meta-autocomplete-wrap'), 
                        $hiddenInputs = $parent.find('input[type=hidden]');

                    fieldValue = $hiddenInputs.map(function(){ return this.value; }).get().join(',');
                } else {
                    fieldValue = getBpRowParamNum(mainSelector, elem, fieldPath);
                }
            } else if ($bpViewElem) {
                fieldValue = $bpViewElem;
            } else {
                fieldValue = fieldPath;
            }
            drillDownCriteria += inputPath + '=' + fieldValue + '&';
        }
        
        drillDownCriteria = rtrim(drillDownCriteria, '&');
    }
    
    var isFullScreen = false, dialogWidth = 0, isNewTab = false;
            
    if (typeof dialogSize !== 'undefined' && dialogSize != '') {
        if (dialogSize == 'fullscreen') {
            isFullScreen = true;
        } else if (dialogSize.indexOf('width:') !== -1) {
            dialogWidth = dialogSize.replace('width:', '').trim();
        } else if (dialogSize == 'tab') {
            isNewTab = true;
        }
    }
    
    var drillPostParam = {indicatorId: dvId, drillDownCriteria: drillDownCriteria, isJson: 1};
                                
    if (isNewTab == false) {
        drillPostParam.isDrilldown = 1;
        drillPostParam.isIgnoreTitle = 1;
    }

    $.ajax({
        type: 'post',
        url: 'mdform/indicatorList/'+dvId+'/1',
        data: drillPostParam,
        dataType: 'json', 
        success: function(content) {
            if (isNewTab) {
                appMultiTabByContent({metaDataId: dvId, title: content.title, type: 'indicator', content: content.html});
            } else {
                var opts = {metaDataId: dvId, title: content.title, type: 'indicatorList', content: content.html};
                if (isFullScreen) {
                    opts.isFullScreen = true;
                }
                if (dialogWidth > 0) {
                    opts.dialogWidth = dialogWidth;
                }
                mvOpenDialog(opts);
            }
        }
    });
}
function bpGetWhat3words(mainSelector, elem, coordinate) {
    
    if (typeof coordinate === 'undefined' || coordinate == null || coordinate == '') {
        new PNotify({
            title: 'Error',
            text: 'Координатын мэдээлэлээ оруулна уу!',
            type: 'error',
            addclass: pnotifyPosition,
            sticker: false
        });    
        return '';
    }
    
    /*setTimeout(function () {    
        Core.blockUI({
            message: 'Whar3words-оос хаягийн мэдээлэл хүлээж байна...',
            boxed: true
        });    
     }, 0);*/
    var response = $.ajax({
        type: 'post',
        url: 'api/gmap',
        data: {method: 'what3words', coordinate: coordinate}, 
        dataType: 'json',
        async: false
    });
    
    var responseValue = response.responseJSON;
    
    if (responseValue.hasOwnProperty('status') && responseValue.status == 'error') {
        PNotify.removeAll();
        new PNotify({
            title: 'Error',
            text: responseValue.message,
            type: 'error',
            addclass: pnotifyPosition,
            sticker: false
        });
    } else {
        return responseValue;
    }
}
function bpGetGoogleGeoData(mainSelector, elem, coordinate) {
    if (typeof coordinate === 'undefined' || coordinate == null || coordinate == '') {
        new PNotify({
            title: 'Error',
            text: 'Координатын мэдээлэлээ оруулна уу!',
            type: 'error',
            addclass: pnotifyPosition,
            sticker: false
        });    
        return '';
    }
    var response = $.ajax({
        type: 'post',
        url: 'api/gmap',
        data: {method: 'geocode', coordinate: coordinate, googleApiKey: gmapApiKey}, 
        dataType: 'json',
        async: false
    });
    
    var responseValue = response.responseJSON;
    
    if (responseValue.hasOwnProperty('status') && responseValue.status == 'error') {
        PNotify.removeAll();
        new PNotify({
            title: 'Error',
            text: responseValue.message,
            type: 'error',
            addclass: pnotifyPosition,
            sticker: false
        });
    } else {
        return responseValue;
    }
}
function bpGetOpenCageData(mainSelector, elem, coordinate) {
    if (typeof coordinate === 'undefined' || coordinate == null || coordinate == '') {
        new PNotify({
            title: 'Error',
            text: 'Координатын мэдээлэлээ оруулна уу!',
            type: 'error',
            addclass: pnotifyPosition,
            sticker: false
        });    
        return '';
    }
    var response = $.ajax({
        type: 'post',
        url: 'api/gmap',
        data: {method: 'opencagedata', coordinate: coordinate}, 
        dataType: 'json',
        async: false
    });
    
    var responseValue = response.responseJSON;
    
    if (responseValue && responseValue.hasOwnProperty('status') && responseValue.status == 'error') {
        PNotify.removeAll();
        new PNotify({
            title: 'Error',
            text: responseValue.message,
            type: 'error',
            addclass: pnotifyPosition,
            sticker: false
        });
    } else {
        return responseValue;
    }
}
function bpGetWorkspaceParam(mainSelector, elem, workspaceid, workspaceparam) {
    if ($('#workspace-id-'+workspaceid).length) {
        var getSerialize = $('#workspace-id-'+workspaceid).find('div.ws-hidden-params > input').serializeArray();

        for (var i = 0; i < getSerialize.length; i++) {
            if (getSerialize[i].name === 'workSpaceParam['+workspaceparam+']') {
                return getSerialize[i].value;
            }
        }
    }
    return '';
}
function bpGoogleDMStoDD(mainSelector, elem, coordinate) {
    var response = $.ajax({
        type: 'post',
        url: 'api/gmap', 
        data: {method: 'googleDMStoDD', coordinate: coordinate}, 
        dataType: 'json',
        async: false
    });

    return response.responseJSON;
}
function bpGoogleDDtoUTM(coordinate) {
    var response = $.ajax({
        type: 'post',
        url: 'api/gmap', 
        data: {method: 'googleDDtoUTM', coordinate: coordinate}, 
        dataType: 'json',
        async: false
    });

    return response.responseJSON;
}
function bpGoogleCoordinatetoDMS(coordinate) {
    var response = $.ajax({
        type: 'post',
        url: 'api/gmap', 
        data: {method: 'googleDectoDMS', coordinate: coordinate}, 
        dataType: 'json',
        async: false
    });

    return response.responseJSON;
}
function bpGoogleCoordinateDistanceBetween(from, to, mode) {
    if (from && to) {
        
        if (from.indexOf('|') !== -1) {
            var fromArr = from.split('|');
            var from1 = (fromArr[0]).trim();
            var from2 = (fromArr[1]).trim();
            var fromLat = from1;
            var fromLng = from2;
            if (Number(from1) > Number(from2)) {
                fromLat = from2;
                fromLng = from1;
            } 
        } else if (from.indexOf(',') !== -1) {
            var fromArr = from.split(',');
            var from1 = (fromArr[0]).trim();
            var from2 = (fromArr[1]).trim();
            var fromLat = from1;
            var fromLng = from2;
            if (Number(from1) > Number(from2)) {
                fromLat = from2;
                fromLng = from1;
            } 
        }
        
        if (to.indexOf('|') !== -1) {
            var toArr = to.split('|');
            var to1 = (toArr[0]).trim();
            var to2 = (toArr[1]).trim();
            var toLat = to1;
            var toLng = to2;
            if (Number(to1) > Number(to2)) {
                toLat = to2;
                toLng = to1;
            } 
        } else if (to.indexOf(',') !== -1) {
            var toArr = to.split(',');
            var to1 = (toArr[0]).trim();
            var to2 = (toArr[1]).trim();
            var toLat = to1;
            var toLng = to2;
            if (Number(to1) > Number(to2)) {
                toLat = to2;
                toLng = to1;
            } 
        }
        
        var response = $.ajax({
            type: 'post',
            url: 'api/gmap', 
            data: {method: 'googleDistanceBetween', mode: (typeof mode != 'undefined' ? mode : 'driving'), gmapApiKey: gmapApiKey, fromCoordinate: fromLat+','+fromLng, toCoordinate: toLat+','+toLng}, 
            dataType: 'json',
            async: false
        });

        var jsonObj = response.responseJSON;
        
        if (jsonObj && jsonObj.hasOwnProperty('distance')) {
            return jsonObj;
        }
    }
    
    return {"distance":null, "meter":0, "time":null, "second":0, "destination_addresses":null, "origin_addresses":null};
}
function bpPasswordStrength(mainSelector, elem, fieldPath, params) {
    var $bpElem = getBpElement(mainSelector, elem, fieldPath);
    
    if ($bpElem) {
        var passwordMinLength = getConfigValue('passwordMinLength'), minLength = 8;
        
        if (passwordMinLength) {
            minLength = passwordMinLength;
        }
        
        var newOptions = {}, newRegex = '^(?=.*?])(?=.{'+minLength+',})', 
            isSpecialChars = false, isLowercase = false, isUppercase = false, isNumbers = false;
        
        if (params.hasOwnProperty('minlength')) {
            
            newOptions['minlength'] = params.minlength;
            
            if (!newOptions['minlength'].hasOwnProperty('text')) {
                newOptions['minlength']['text'] = plang.get('p_character');
            }
            
            if (passwordMinLength) {
                newOptions['minlength']['minLength'] = passwordMinLength;
            } 
            
            newRegex = '^(?=.*?])(?=.{'+newOptions['minlength']['minLength']+',})';
            
        } else {
            newOptions['minlength']['minLength'] = minLength;
            newOptions['minlength']['text'] = plang.get('p_character');
        }
        
        var minLength = newOptions['minlength']['minLength'];
        
        if (params.hasOwnProperty('specialChars')) {
            newOptions['containSpecialChars'] = params.specialChars;
            if (!newOptions['containSpecialChars'].hasOwnProperty('text')) {
                newOptions['containSpecialChars']['text'] = plang.get('p_special_character');
            }
            newOptions['containSpecialChars']['regex'] = new RegExp('([^!,%,&,@,#,$,^,*,?,_,~])', 'g');
            isSpecialChars = true;
        }
        
        if (params.hasOwnProperty('lowercase')) {
            newOptions['containLowercase'] = params.lowercase;
            if (!newOptions['containLowercase'].hasOwnProperty('text')) {
                newOptions['containLowercase']['text'] = plang.get('p_lower_character');
            }
            newOptions['containLowercase']['regex'] = new RegExp('[^a-zа-яөү]', 'g');
            isLowercase = true;
        }
        
        if (params.hasOwnProperty('uppercase')) {
            newOptions['containUppercase'] = params.uppercase;
            if (!newOptions['containUppercase'].hasOwnProperty('text')) {
                newOptions['containUppercase']['text'] = plang.get('p_upper_character');
            }
            newOptions['containUppercase']['regex'] = new RegExp('[^A-ZА-ЯӨҮ]', 'g');
            isUppercase = true;
        }
        
        if (params.hasOwnProperty('numbers')) {
            newOptions['containNumbers'] = params.numbers;
            if (!newOptions['containNumbers'].hasOwnProperty('text')) {
                newOptions['containNumbers']['text'] = plang.get('p_number');
            }
            newOptions['containNumbers']['regex'] = new RegExp('[^0-9]', 'g');
            isNumbers = true;
        }
        
        $bpElem.PassRequirements({
            defaults: false, 
            popoverPlacement: 'left',     
            rules: newOptions 
        });
        
        if (isSpecialChars && !isLowercase && !isUppercase && !isNumbers) {
            newRegex = '^(?=.*?])(?=.{'+newOptions['minlength']['minLength']+',})';
        } else if (isSpecialChars && isLowercase && isUppercase && isNumbers) {
            newRegex = '^(?=.*[a-zа-яөү])(?=.*[A-ZА-ЯӨҮ])(?=.*[0-9])(?=.*[!@#\$%\^&\*_])(?=.{'+minLength+',})'; /*all*/
        } else if (!isSpecialChars && isLowercase && isUppercase && isNumbers) {
            newRegex = '^(?=.*[a-zа-яөү])(?=.*[A-ZА-ЯӨҮ])(?=.*[0-9])(?=.{'+minLength+',})';
        } else if (isSpecialChars && !isLowercase && isUppercase && isNumbers) {
            newRegex = '^(?=.*[A-ZА-ЯӨҮ])(?=.*[0-9])(?=.*[!@#\$%\^&\*_])(?=.{'+minLength+',})';
        } else if (isSpecialChars && isLowercase && !isUppercase && isNumbers) {
            newRegex = '^(?=.*[a-zа-яөү])(?=.*[0-9])(?=.*[!@#\$%\^&\*_])(?=.{'+minLength+',})';
        } else if (isSpecialChars && isLowercase && isUppercase && !isNumbers) {
            newRegex = '^(?=.*[a-zа-яөү])(?=.*[A-ZА-ЯӨҮ])(?=.*[!@#\$%\^&\*_])(?=.{'+minLength+',})';
        } else if (!isSpecialChars && !isLowercase && isUppercase && isNumbers) {
            newRegex = '^(?=.*[A-ZА-ЯӨҮ])(?=.*[0-9])(?=.{'+minLength+',})'; 
        } else if (!isSpecialChars && !isLowercase && !isUppercase && isNumbers) {
            newRegex = '^(?=.*[0-9])(?=.{'+minLength+',})'; 
        } else if (!isSpecialChars && isLowercase && !isUppercase && !isNumbers) {
            newRegex = '^(?=.*[a-zа-яөү])(?=.{'+minLength+',})';
        } else if (!isSpecialChars && !isLowercase && isUppercase && !isNumbers) {
            newRegex = '^(?=.*[A-ZА-ЯӨҮ])(?=.{'+minLength+',})';
        } else if (isSpecialChars && !isLowercase && !isUppercase && !isNumbers) {
            newRegex = '^(?=.*[!@#\$%\^&\*_])(?=.{'+minLength+',})';
        }
        
        $bpElem.attr('data-regex', newRegex);
    }
    return;
}
function bpValidateUserPassword(mainSelector, elem, pass, username) {
    var response = $.ajax({
        type: 'post',
        url: 'mduser/validatePasswordFromExp', 
        data: {password: pass, username: username},
        dataType: 'json',
        async: false
    });
    return response.responseJSON;
}
function bpNumberToTime(minutes) {
    var h = Math.floor(minutes / 60);
    var m = minutes % 60;
    h = h < 10 ? '0' + h : h;
    m = m < 10 ? '0' + m : m;
    return h + ':' + m;
}
function bpTimeToNumber(cTime) {
    var aHHMM = cTime.split(':');
    var nMinutes = 0;
    nMinutes = aHHMM[0] * 60;
    nMinutes += Number(aHHMM[1]);
    return nMinutes;
}
function bpCallSolidWindow(mainSelector, elem, methodUrl, paramsPath, openType, windowSize) {
    var paramData = {}, isFullScreen = false, dialogWidth = 800;
    
    if (paramsPath) {
        var paramsPathArr = paramsPath.split('|');
        
        for (var i = 0; i < paramsPathArr.length; i++) {
            var fieldPathArr = paramsPathArr[i].split('@');
            var fieldPath = fieldPathArr[0].trim();
            var inputPath = fieldPathArr[1].trim();
            var fieldValue = '';

            var $bpElem = getBpElement(mainSelector, elem, fieldPath);
            var $bpViewElem = getBpRowParamVal(mainSelector, elem, fieldPath);

            if ($bpElem) {
                fieldValue = getBpRowParamNum(mainSelector, elem, fieldPath);
            } else if ($bpViewElem) {
                fieldValue = $bpViewElem;
            } else {
                fieldValue = fieldPath;
            }

            paramData[inputPath] = fieldValue;
        }
    }

    $.ajax({
        type: 'post',
        url: methodUrl,
        data: paramData,
        dataType: 'json', 
        beforeSend: function () {
            Core.blockUI({
                message: 'Loading...',
                boxed: true
            });
        },
        success: function (data) { 
            
            if (openType == 'dialog') {
                
                if (typeof windowSize !== 'undefined' && windowSize != '') {
                    if (windowSize == 'fullscreen') {
                        isFullScreen = true;
                    } else if (windowSize.indexOf('width:') !== -1) {
                        dialogWidth = windowSize.replace('width:', '').trim();
                    }
                }
                $('html, body').scrollTop(0);
                
                var $dialogName = 'dialog-bp-solidwindow';
                if (!$("#" + $dialogName).length) { $('<div id="' + $dialogName + '"></div>').appendTo('body'); }
                var $dialog = $('#' + $dialogName);
                
                $dialog.empty().append(data.html);
                $dialog.dialog({
                    cache: false,
                    resizable: true,
                    bgiframe: true,
                    autoOpen: false,
                    title: (typeof data.title !== 'undefined' ? data.title : ''), 
                    width: dialogWidth,
                    height: 'auto',
                    modal: true,
                    open: function(){                 
                        $(this).parent().promise().done(function () {
                            $('html, body').scrollTop(0);
                        });                                          
                    },                    
                    close: function () {
                        $dialog.empty().dialog('destroy').remove();
                    },
                    buttons: [
                        {text: plang.get('close_btn'), class: 'btn blue-madison btn-sm', click: function () {
                            $dialog.dialog('close');
                        }}
                    ]
                });

                if (isFullScreen) {
                    $dialog.dialogExtend({
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
                    $dialog.dialogExtend('maximize');
                }

                $dialog.dialog('open');
                Core.unblockUI();
            } 
        },
        error: function () {
            alert('Error');
            Core.unblockUI();
        }
    });
    
    return;
}
function bpGetCivilInfo(registerNumber) {
    var response = $.ajax({
        type: 'post',
        url: 'api/getCivilInfo', 
        data: {methodCode: 'GetCivilInfo', registerNumber: registerNumber},
        dataType: 'json',
        async: false
    });

    return response.responseJSON;
}
function bpGetBankAccountBalance(bankCode, bankAccount, departmentId) {
    
    var configDepartmentId = (typeof departmentId == 'undefined' ? '' : departmentId);
    
    var response = $.ajax({
        type: 'post',
        url: 'mdintegration/getBankAccountBalance', 
        data: {bankCode: bankCode, bankAccount: bankAccount, departmentId: configDepartmentId},
        dataType: 'json',
        async: false
    });
    
    return response.responseJSON;
}
function bpGetBankAccountInfo(bankCode, bankAccount, departmentId) {
    
    var configDepartmentId = (typeof departmentId == 'undefined' ? '' : departmentId);
    
    var response = $.ajax({
        type: 'post',
        url: 'mdintegration/getBankAccountInfo', 
        data: {bankCode: bankCode, bankAccount: bankAccount, departmentId: configDepartmentId},
        dataType: 'json',
        async: false
    });
    
    return response.responseJSON;
}
function bpGetBankTransactionStatement(bankCode, departmentId, transDate, transId) {
    
    var response = $.ajax({
        type: 'post',
        url: 'mdintegration/getBankTransactionStatement', 
        data: {bankCode: bankCode, departmentId: departmentId, transDate: transDate, transId: transId},
        dataType: 'json',
        async: false
    });
    
    return response.responseJSON;
}
function bpUnlimitTimeMessage(messageType, messageTxt) {
    PNotify.removeAll();
    new PNotify({
        title: messageType,
        text: messageTxt,
        type: messageType,
        sticker: false, 
        hide: true,  
        addclass: pnotifyPosition,
        delay: 10000000000
    });
    return;
}
function bpGetWfmNextStatusBySelectedRow(dvId) {
    
    var rows = getDataViewSelectedRows(dvId), row = rows[0], 
        statusList = [], isManyRows = '';
    
    if (rows.length > 1) {
        row = rows;
        isManyRows = '1';
    }
    
    var response = $.ajax({
        type: 'post',
        url: 'mdobject/getWorkflowNextStatus',
        data: {metaDataId: dvId, dataRow: row, isManyRows: isManyRows},
        dataType: 'json',
        async: false
    });
    
    var responseData = response.responseJSON;
    
    if (responseData.status == 'success') {
        statusList = responseData.data; 
    }
    
    return statusList;
}
function bpGetWfmNextStatusByRowDataQryStr(dvId, rowDataQryStr) {
    
    var row = qryStrToObj(rowDataQryStr), statusList = [];
    
    var response = $.ajax({
        type: 'post',
        url: 'mdobject/getWorkflowNextStatus',
        data: {metaDataId: dvId, dataRow: row, isManyRows: ''},
        dataType: 'json',
        async: false
    });
    
    var responseData = response.responseJSON;
    
    if (responseData.status == 'success') {
        statusList = responseData.data; 
    }
    
    return statusList;
}
function bpChangeWfmStatusByRowDataQryStr(mainSelector, elem, dvId, rowDataQryStr, callbackFnc) {
    var row = qryStrToObj(rowDataQryStr);
    if (row) {
        var bpUniqId = mainSelector.attr('data-bp-uniq-id');
        row.callbackFnc = callbackFnc + '_' + bpUniqId;
        
        if (row.wfmstatusprocessid != '' && row.wfmstatusprocessid != 'null' && row.wfmstatusprocessid != null) {
            if (row.wfmisneedsign == '1') {
                transferProcessAction('signProcess', dvId, row.wfmstatusprocessid, '200101010000011', 'toolbar', elem, {callerType: 'expression', isWorkFlow: true, wfmStatusId: row.wfmstatusid, wfmStatusCode: '', selectedRow: row}, 'dataViewId='+dvId+'&refStructureId=&statusId='+row.newwfmstatusid+'&statusName='+row.wfmstatusname+'&statusColor='+row.wfmstatuscolor+'&rowId='+row.id);
            } else if (row.wfmisneedsign == '2') {
                transferProcessAction('hardSignProcess', dvId, row.wfmstatusprocessid, '200101010000011', 'toolbar', elem, {callerType: 'expression', isWorkFlow: true, wfmStatusId: row.wfmstatusid, wfmStatusCode: '', selectedRow: row}, 'dataViewId='+dvId+'&refStructureId=&statusId='+row.newwfmstatusid+'&statusName='+row.wfmstatusname+'&statusColor='+row.wfmstatuscolor+'&rowId='+row.id);
            } 
        } else {   
            if (row.wfmisneedsign == '1') {
                beforeSignChangeWfmStatusId(elem, row.newwfmstatusid, dvId, row, row.wfmstatuscolor, row.wfmstatusname);
            } else if (row.wfmisneedsign == '2') {
                beforeHardSignChangeWfmStatusId(elem, row.newwfmstatusid, dvId, row, row.wfmstatuscolor, row.wfmstatusname);
            }
        }
    }
    return;
}
function bpComboFillData(mainSelector, elem, comboPath, comboData, optionId, optionText) {
    
    if (comboData) {
        var $bpElem = getBpElement(mainSelector, elem, comboPath);
    
        if ($bpElem) {

            var comboDatas = [];

            $bpElem.addClass('data-combo-set');
            $bpElem.empty();
            $bpElem.append($('<option />').val('').text(plang.get('choose')));  

            for (var key in comboData) {
                $bpElem.append($('<option />').val(comboData[key][optionId]).text(comboData[key][optionText]));
                comboDatas.push({
                    id: comboData[key][optionId],
                    text: comboData[key][optionText]
                });    
            }

            $bpElem.select2({results: comboDatas, closeOnSelect: false});
        }
    }
    
    return;
}
function bpSetValueComboByIndex(mainSelector, elem, comboPath, valIndex) {
    var $bpElem = getBpElement(mainSelector, elem, comboPath);
    
    if ($bpElem.hasClass('data-combo-set')) {
        var val = $bpElem.find('option:eq('+valIndex+')').attr('value');
        $bpElem.select2('val', val);
    }
    
    return;
}
function bpGenerateQRcode(string, nobase64) {
    
    var response = $.ajax({
        type: 'post',
        url: 'mdprocess/bpGenerateQRcode', 
        data: {qrcodeString: (typeof nobase64 !== 'undefined' && nobase64 == '1') ? string : base64_encode(string)},
        dataType: 'json',
        async: false
    });

    return response.responseJSON;
}
function bpPreviewQRCode(string, showText) {
    var captionText = (typeof showText !== 'undefined') ? showText : 'QR Code';
    var firstThreeChar = string.substr(0, 3);
    
    if (firstThreeChar == 'iVB') {
        var qrCode = 'data:image/png;base64,' + string;
    } else if (firstThreeChar == '/9j') {
        var qrCode = 'data:image/jpg;base64,' + string;
    } else if (firstThreeChar == 'Qk0') {
        var qrCode = 'data:image/bmp;base64,' + string;
    } else if (firstThreeChar == 'R0l') {
        var qrCode = 'data:image/gif;base64,' + string;
    } else {
        var qrCode = 'data:image/png;base64,' + bpGenerateQRcode(string, '1');
    }
    
    if (qrCode) {
        $.fancybox.open(
            [{src: qrCode, opts: {caption: captionText}}],
            {buttons: ['zoom', 'close']}
        );
    }
    
    return;
}
function bpSetMask(mainSelector, elem, fieldPath, regexStr) {
    var $bpElem = getBpElement(mainSelector, elem, fieldPath);
    
    if ($bpElem) {
        $bpElem.attr({'data-regex': regexStr, 'data-inputmask-regex': regexStr});
        $bpElem.inputmask('Regex');
    }
    
    return;
}
function bpUnSetMask(mainSelector, elem, fieldPath) {
    var $bpElem = getBpElement(mainSelector, elem, fieldPath);
    
    if ($bpElem) {
        $bpElem.removeAttr('data-regex data-inputmask-regex');
        $bpElem.inputmask('remove');
    }
    
    return;
}
function bpSetLookupCodeMask(mainSelector, elem, fieldPath, regexStr) {
    var $bpElem = getBpElement(mainSelector, elem, fieldPath);
    
    if ($bpElem) {
        var $codeField = $bpElem.parent().find('input.lookup-code-autocomplete');
        
        $codeField.attr({'data-regex': regexStr, 'data-inputmask-regex': regexStr});
        $codeField.inputmask('Regex');
    }
    return;
}
function bpSetLookupNameMask(mainSelector, elem, fieldPath, regexStr) {
    var $bpElem = getBpElement(mainSelector, elem, fieldPath);
    
    if ($bpElem) {
        var $nameField = $bpElem.parent().find('input.lookup-name-autocomplete');
        
        $nameField.attr({'data-regex': regexStr, 'data-inputmask-regex': regexStr});
        $nameField.inputmask('Regex');
    }
    return;
}
function bpUnSetLookupCodeMask(mainSelector, elem, fieldPath) {
    var $bpElem = getBpElement(mainSelector, elem, fieldPath);
    
    if ($bpElem) {
        var $codeField = $bpElem.parent().find('input.lookup-code-autocomplete');
        
        $codeField.removeAttr('data-regex data-inputmask-regex');
        $codeField.inputmask('remove');
    }
    
    return;
}
function bpUnSetLookupNameMask(mainSelector, elem, fieldPath) {
    var $bpElem = getBpElement(mainSelector, elem, fieldPath);
    
    if ($bpElem) {
        var $nameField = $bpElem.parent().find('input.lookup-name-autocomplete');
        
        $nameField.removeAttr('data-regex data-inputmask-regex');
        $nameField.inputmask('remove');
    }
    
    return;
}
function bpChangeLookupPlaceHolder(mainSelector, elem, fieldPath, inputCode, labelName) {
    var $bpElem = getBpElement(mainSelector, elem, fieldPath);
    
    if ($bpElem) {
        if (inputCode == 'code') {
            var $changeField = $bpElem.parent().find('input.lookup-code-autocomplete');
        } else {
            var $changeField = $bpElem.parent().find('input.lookup-name-autocomplete');
        }
        $changeField.attr('placeholder', plang.get(labelName));
    }
    return;
}
function bpChangePlaceHolder(mainSelector, elem, fieldPath, labelName) {
    var $bpElem = getBpElement(mainSelector, elem, fieldPath);
    
    if ($bpElem) {
        $bpElem.attr('placeholder', plang.get(labelName));
    }
    return;
}
function bpWorkSpaceReload(workSpaceId, responseData) {
    
    var $wsContainer = $('#workspace-id-' + workSpaceId);
    
    if ($wsContainer.length) {
        
        var dvId = $wsContainer.attr('data-dm-id');
        var rows = getDataViewSelectedRows(dvId);
        var selectedRow = rows[0];

        $.ajax({
            type: 'post',
            url: 'mdworkspace/workSpaceReload',
            data: {metaDataId: workSpaceId, dmMetaDataId: dvId, rowId: (('id' in Object(selectedRow)) ? selectedRow.id : responseData.rowId)},
            dataType: 'json',
            success: function (data) {
                $wsContainer.empty().append(data.html);
            }
        });
    }
}
function getBpRowViewElem(mainSelector, elem, fieldPath) {
    var resultNum = '';
    
    if (elem === 'open') {
        
        var $getPathElement = mainSelector.find("[data-view-path='" + fieldPath + "']");
        if ($getPathElement.length > 0) {
            resultNum = $getPathElement;
        }
        
    } else {
        var $elem = $(elem);    
        var $this = $($elem, mainSelector);
        var $oneLevelRow = $this.closest('.bp-detail-row');   

        if ($oneLevelRow.find("[data-view-path='" + fieldPath + "']").length == 0) {
            $oneLevelRow = $oneLevelRow.parents('.bp-detail-row');
        }
        
        var $getPathElement = $oneLevelRow.find("[data-view-path='" + fieldPath + "']");
        var $getPathMainElement = mainSelector.find("[data-view-path='" + fieldPath + "']");
        
        if ($getPathElement.length === 0 && $getPathMainElement.length > 0) {
            resultNum = $getPathMainElement;
        } else if ($getPathElement.length > 0) {
            resultNum = $getPathElement;
        }
    }

    return resultNum;
}
function bpCheckFingerPrint(elem, fingerIp) {
    Core.blockUI({
        boxed: true, 
        message: 'Loading...'
    });
    
    if ("WebSocket" in window) {
        var ws = new WebSocket("ws://localhost:58324/socket");

        ws.onopen = function () {
            ws.send('{"command":"finger_scan_zk", details: [{"key": "server", "value": "'+fingerIp+'"}]}');
        };

        ws.onmessage = function (evt) { 
            
            var received_msg = evt.data;
            var jsonData = JSON.parse(received_msg);
            
            PNotify.removeAll();

            if (jsonData.status == 'success' && 'details' in Object(jsonData)) {
                
                var fingerPrintObj = convertDataElementToArray(jsonData.details);
                var $parent = $(elem).closest('.input-group');
                
                $parent.find('input[type="text"]').val(fingerPrintObj.userId).trigger('change');
                
            } else {
                new PNotify({
                    title: 'Error',
                    text: jsonData.description, 
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
                text: event.code, 
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
            title: 'Error',
            text: 'WebSocket NOT supported by your Browser!', 
            type: 'error',
            sticker: false
        });
        
        Core.unblockUI();
    }
}
function bpContentViewerById(elem, recordIds, contentIds, obj, usestampedfileview) {
    
    PNotify.removeAll();
    
    var realRecordIds = null, realContentIds = null;
    
    if (typeof recordIds != 'undefined' && recordIds) {
        realRecordIds = recordIds.trim();
    }
    
    if (typeof contentIds != 'undefined' && contentIds) {
        realContentIds = contentIds.trim();
    }

    if (realRecordIds || realContentIds) {

        var uniqId = getUniqueId(1);

        $.ajax({
            type: 'post',
            url: 'mdpreview/contentViewerById',
            data: {uniqId: uniqId, recordIds: realRecordIds, contentIds: realContentIds, useStampedFileView: usestampedfileview},
            dataType: 'json', 
            beforeSend: function() {
                Core.blockUI({message: 'Loading...', boxed: true});
            },
            success: function(data) { 

                if (data.status == 'success') {
                    
                    var isTab = false;

                    if (typeof obj !== 'undefined' && isObject(obj) && Object.keys(obj).length) {
                        
                        if (obj.hasOwnProperty('tabName') && obj.tabName) {
                            isTab = true;
                        }
                    }
                    
                    if (isTab) {
                        
                        var tabId = str_replace(Array('\'', '/', '"', "'", '#', ' '), '', obj.tabName);
                        appMultiTabByContent({metaDataId: tabId, title: obj.tabName, type: 'filepreview', content: data.html});
                        
                    } else {
                        
                        var $dialogName = 'dialog-multi-file-viewer-' + uniqId;
                        if (!$("#" + $dialogName).length) { $('<div id="' + $dialogName + '"></div>').appendTo('body'); }
                        var $dialog = $('#' + $dialogName);

                        $dialog.empty().append(data.html);
                        $dialog.dialog({
                            dialogClass: 'no-padding-dialog',
                            cache: false,
                            resizable: true,
                            bgiframe: true,
                            autoOpen: false,
                            title: plang.get('file_view'), 
                            width: 1000,
                            height: 'auto',
                            minHeight: 500,
                            modal: true,       
                            resize: function() {
                                var dialogHeight = $dialog.height();
                                $dialog.find('.mfm-thumbnails, .mfm-viewer').css('height', dialogHeight);
                            }, 
                            close: function() {
                                $.contextMenu('destroy', '#multi-file-viewer-'+uniqId+' .mfm-thumbnail');
                                $dialog.empty().dialog('destroy').remove();
                            },
                            buttons: [
                                {text: plang.get('close_btn'), class: 'btn blue-madison btn-sm', click: function () {
                                    $dialog.dialog('close');
                                }}
                            ]
                        }).dialogExtend({
                            "closable": true,
                            "maximizable": true,
                            "minimizable": true,
                            "collapsable": false,
                            "dblclick": "maximize",
                            "minimizeLocation": "left",
                            "icons": {
                                "close": "ui-icon-circle-close",
                                "maximize": "ui-icon-extlink",
                                "minimize": "ui-icon-minus",
                                "collapse": "ui-icon-triangle-1-s",
                                "restore": "ui-icon-newwin"
                            }, 
                            "maximize": function() { 
                                var dialogHeight = $dialog.height();
                                $dialog.find('.mfm-thumbnails, .mfm-viewer').css('height', dialogHeight);

                                $dialog.closest(".ui-dialog").nextAll('.ui-widget-overlay:first').removeClass('display-none');
                            }, 
                            "minimize": function() { 
                                $dialog.closest('.ui-dialog').nextAll('.ui-widget-overlay:first').addClass('display-none');
                            }, 
                            "restore": function() { 
                                var dialogHeight = $dialog.height();
                                $dialog.find('.mfm-thumbnails, .mfm-viewer').css('height', dialogHeight);

                                $dialog.closest('.ui-dialog').nextAll('.ui-widget-overlay:first').removeClass('display-none');
                            }
                        });
                        $dialog.dialog('open');
                        $dialog.dialogExtend('maximize');
                    }

                } else {
                    new PNotify({
                        title: data.status,
                        text: data.message, 
                        type: data.status,
                        sticker: false, 
                        hide: true,  
                        addclass: pnotifyPosition
                    });
                }

                Core.unblockUI();
            },
            error: function() { alert('Error'); Core.unblockUI(); }
        });

        return;
    }
    
    new PNotify({
        title: 'Info',
        text: 'Record ID is null!',
        type: 'info',
        sticker: false, 
        hide: true,  
        addclass: pnotifyPosition
    });
    
    return;
}
function bpColsSetOneCheck(mainSelector, elem) {
    
    var path = elem.attr('data-path');
    var $row = elem.closest('tr');
    var $checkboxs = $row.find('[data-path="'+path+'"]').not(elem);
    
    $checkboxs.prop('checked', false);
    $.uniform.update($checkboxs);
    
    return;
}
function bpColsSetOneVal(mainSelector, elem, path, unsetVal, setVal) {
    
    var $cell = elem.closest('td');
    var $row = elem.closest('tr');
    var groupNum = $cell.attr('data-group-num');
    
    $row.find('[data-path="'+path+'"]').val(unsetVal);
    $row.find('td[data-group-num="'+groupNum+'"]').find('[data-path="'+path+'"]').val(setVal);
    
    return;
}
function bpColsSetOneStyle(mainSelector, elem, style) {
    
    var $firstElem = elem.eq(0);
    
    if ($firstElem.prop('tagName') == 'TD') {
        var $cell = $firstElem;
    } else {
        var $cell = elem.closest('td');
    }
    
    var $row = elem.closest('tr');
    var groupNum = $cell.attr('data-group-num');
    var $cells = $row.find('td[data-group-num]');
    
    $cells.removeAttr('style');
    $cells.find('input[style]').removeAttr('style');
        
    if (style != 'reset') {
        var $setCells = $row.find('td[data-group-num="'+groupNum+'"]');
        $setCells.attr('style', style);
        $setCells.find('input[type="text"]').attr('style', style);
    } 
    
    return;
}
function bpCenterMessage(status, message) {
    PNotify.removeAll();
    new PNotify({
        title: status,
        text: message,
        type: status,
        hide: false,
        addclass: 'stack-modal',
        width: '400px',
        confirm: {
            confirm: true,
            buttons: [{
                text: 'Ok',
                addClass: 'btn btn-secondary',
                click: function(notice) {
                    notice.remove();
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
    return;
}
function bpDateFormat(format, dateStr) {
    if (format == 'S') {
                
        var getMonth = date('m', strtotime(dateStr));
        var season = '';
        
        if (getMonth == '01' || getMonth == '02' || getMonth == '03') {
            season = 1; 
        } else if (getMonth == '04' || getMonth == '05' || getMonth == '06') {
            season = 2; 
        } else if (getMonth == '07' || getMonth == '08' || getMonth == '09') {
            season = 3;
        } else {
            season = 4;
        }

        return season;
    } else {
        return date(format, strtotime(dateStr));
    }
}
function bpSetDateNoTrigger(mainSelector, elem, fieldPath, val) {

    if (elem !== 'open') {
        elem = $(elem);
        var $this = $(elem, mainSelector);
        var $oneLevelRow = $this.closest('.bp-detail-row');        
    
        if ($oneLevelRow.find("[data-path='" + fieldPath + "']").length == 0) {

            if (mainSelector.find('.bprocess-table-dtl > .tbody > .currentTarget').find("[data-path='" + fieldPath + "']").length) {     
                var $getPathElement = mainSelector.find('.bprocess-table-dtl > .tbody > .currentTarget').find("[data-path='" + fieldPath + "']");
            } else {
                var $getPathElement = mainSelector.find("[data-path='" + fieldPath + "']");
            }
            
            if ($getPathElement.length) {
                if ($getPathElement.hasClass('dateInit')) {
                    if (val !== '' && val !== null) {
                        $getPathElement.datepicker('updateNoTrigger', date('Y-m-d', strtotime(val)));
                    } else {
                        $getPathElement.datepicker('updateNoTrigger', null);
                    }
                }
            }
            return;
            
        } else if ($oneLevelRow.find("td:last-child").find("[data-path='" + fieldPath + "']").length == 0) {          
            var $getPathElement = $oneLevelRow.find("[data-path='" + fieldPath + "']");
            
            if ($getPathElement.length) {
            
                if ($getPathElement.hasClass('dateInit')) {
                    if (val !== '' && val !== null) {
                        $getPathElement.datepicker('updateNoTrigger', date('Y-m-d', strtotime(val)));
                    } else {
                        $getPathElement.datepicker('updateNoTrigger', null);
                    }
                }
            }
            return;
            
        } else {
            var $getPathElement = $oneLevelRow.find("[data-path='" + fieldPath + "']");
            if ($getPathElement.length) {
                if ($getPathElement.hasClass('dateInit')) {
                    if (val !== '' && val !== null) {
                        $getPathElement.datepicker('updateNoTrigger', date('Y-m-d', strtotime(val)));
                    } else {
                        $getPathElement.datepicker('updateNoTrigger', null);
                    }
                }                
            }
            $oneLevelRow.find("td:last-child").find("[data-path='" + fieldPath + "']").val(val);  
            
            return;
        }   

    } else {        

        var $getPathElement = mainSelector.find("[data-path='" + fieldPath + "']");
        if ($getPathElement.length) {
            if ($getPathElement.hasClass('dateInit')) {
                if (val !== '' && val !== null) {
                    $getPathElement.datepicker('updateNoTrigger', date('Y-m-d', strtotime(val)));
                } else {
                    $getPathElement.datepicker('updateNoTrigger', null);
                }
            }
            return;
            
        }
    }
    return;    
}
/*
 * Mobicom SAP LOCATION NAME
 */
function sapLocationSetName(locationName) {
    
    var response = $.ajax({
        type: 'post',
        url: 'mdintegration/getSapLocationSetName/' + locationName,
        dataType: 'json',
        async: false
    });

    return response.responseJSON;
}
function sapEquipmentDtlBySiteName(locationName, listName) {
    
    var response = $.ajax({
        type: 'post',
        url: 'mdintegration/sapEquipmentDtlBySiteName/',
        dataType: 'json',
        data: {'locationName': locationName, 'listName': listName},
        async: false
    });

    return response.responseJSON;
}
function uiDialogOverlayRemove() {
    $('.ui-widget-overlay').remove();
    return; 
}
function bpDetailColumnMerge(mainSelector, elem, fieldPath) {
    var fieldPathArr = fieldPath.split('.');
    var tablePath = fieldPathArr[0];
    var $table = mainSelector.find("[data-table-path='" + tablePath + "']");
  
    if ($table.length) {
        var $tbody = $table.find('> .tbody');
        
        if ($tbody.find('> .bp-detail-row').length) {
            
            $table.find('> thead').find('th[data-cell-path="'+fieldPath+'"]').attr('data-merge-cell', 'true');
            $tbody.find('td[data-cell-path="'+fieldPath+'"]').attr('data-merge-cell', 'true');

            bpDetailMergeCells($tbody);
        }
    }
    
    return;
}
function bpDetailMergeCells($tbody) {
    $tbody.find('.merge-cell-hidden').removeClass('merge-cell-hidden');
    $tbody.find('td[rowspan]').removeAttr('rowspan');
    $tbody.TableSpan('verticalmergehide');
    return;
}
function bpDetailSorting($tbody, $this, colIndex, fieldType, isAsc) {
    var rows = $tbody.children('tr').toArray().sort(bpComparer(colIndex, fieldType));
            
    if (!isAsc) { 
        $this.removeClass('bp-head-sort-asc').addClass('bp-head-sort-desc');
        rows = rows.reverse(); 
    } else {
        $this.removeClass('bp-head-sort-desc').addClass('bp-head-sort-asc');
    }
    for (var i = 0; i < rows.length; i++) {
        $tbody.append(rows[i]);
    }

    var el = $tbody.children('tr:not(.d-none,.removed-tr)'), len = el.length, i = 0;
    for (i; i < len; i++) { 
        $(el[i]).find('td:eq(0) > span').text(i + 1);
    }
    
    return;
}
function bpDetailColumnSort(mainSelector, elem, fieldPath, sortType) {
    var fieldPathArr = fieldPath.split('.');
    var tablePath = fieldPathArr[0];
    var $table = mainSelector.find("table[data-table-path='" + tablePath + "']");
    
    if ($table.length) {
        
        if (sortType == 'hide') {
            var $headerCol = $table.find('> thead > tr > th[data-cell-path="'+fieldPath+'"]');
            if ($headerCol.length) {
                if ($headerCol.find('.bp-head-lookup-sort-code').length) {
                    $headerCol.find('.bp-head-lookup-sort-code, .bp-head-lookup-sort-name').hide();
                } else {
                    $headerCol.removeClass('bp-head-sort');
                }
            }
        } else if (sortType == 'show') {
            var $headerCol = $table.find('> thead > tr > th[data-cell-path="'+fieldPath+'"]');
            if ($headerCol.length) {
                if ($headerCol.find('.bp-head-lookup-sort-code').length) {
                    $headerCol.find('.bp-head-lookup-sort-code, .bp-head-lookup-sort-name').show();
                } else {
                    $headerCol.addClass('bp-head-sort');
                }
            }
        } else {
        
            var $tbody = $table.find('> tbody');

            if ($tbody.find('> tr').length) {

                var $this = $table.find('> thead > tr > th[data-cell-path="'+fieldPath+'"]');
                var colIndex = $this.index();
                var $fieldTypeElem = $tbody.find('tr:eq(0) > td:eq('+colIndex+')'), fieldType = '';

                if ($fieldTypeElem.find('input.bigdecimalInit:eq(0)').length > 0) {
                    fieldType = 'number';
                } else if ($fieldTypeElem.find('div.checker').length > 0) {
                    fieldType = 'checkbox';
                } else if ($fieldTypeElem.find('div.meta-autocomplete-wrap').length > 0) {
                    fieldType = 'lookup';
                } else if ($fieldTypeElem.find('input[type=text]:eq(0)').length > 0) {
                    fieldType = 'text';
                } else {
                    fieldType = 'text';
                }

                bpDetailSorting($tbody, $this, colIndex, fieldType, (sortType == 'asc' ? true : false));
            }
        }
    }
    
    return;
}
function bpWorkSpaceClose(mainSelector, confirmMsg) {
    
    if (typeof isAppMultiTab !== 'undefined' && isAppMultiTab) {
        
        var $wsArea = mainSelector.closest('.ws-area');

        if ($wsArea.length) {
            
            if (confirmMsg === 'confirm') {
                var $dialogName = 'dialog-window-close-confirm';
                var $dialog = $('#' + $dialogName);
                
                if (!$("#" + $dialogName).length) {
                    $('<div id="' + $dialogName + '"></div>').appendTo('body');
                    var $dialog = $('#' + $dialogName);
                    $dialog.empty().append('Та хаахдаа итгэлтэй байна уу?');
                } else {
                    var $dialog = $('#' + $dialogName);
                }
                
                $dialog.dialog({
                    cache: false,
                    resizable: false,
                    bgiframe: true,
                    autoOpen: false,
                    title: plang.get('msg_title_confirm'),
                    width: 330,
                    height: "auto",
                    modal: true,
                    buttons: [{
                            text: plang.get('yes_btn'),
                            'class': 'btn green-meadow btn-sm',
                            click: function() {
                                if (typeof isAlwaysNewTab !== 'undefined' && isAlwaysNewTab && 
                                    typeof $wsArea.attr('data-dm-id') !== 'undefined' && $wsArea.attr('data-dm-id') != '') {

                                    var $appTab = $wsArea.closest("div[id*='app_tab_']");
                                    var dmId = $wsArea.attr('data-dm-id');
                                    var appTabId = $appTab.attr('id');
                                    var $li = $('body').find("a[href='#"+appTabId+"']").closest('li');

                                    $appTab.empty().remove(); $li.remove();

                                    var $dmAppTab = $('body').find("#object-value-list-"+dmId).closest("div[id*='app_tab_']");

                                    if ($dmAppTab.length) {
                                        var dmAppTabId = $dmAppTab.attr('id');
                                        $('body').find("a[href='#"+dmAppTabId+"']").tab('show');
                                    }

                                } else {
                                    var $container = $wsArea.closest(".dv-process");
                                    $container.empty().hide();
                                    $container.closest(".tab-pane").find("div.row:eq(0)").show();
                                    $container.closest(".tab-pane").find("div.main-dataview-container").show();
                                }

                                $(window).trigger('resize');
                                $dialog.dialog('close');
                            }
                        },
                        {
                            text: plang.get('no_btn'),
                            'class': 'btn blue-madison btn-sm',
                            click: function() {
                                $dialog.dialog('close');
                            }
                        }
                    ]
                });
                $dialog.dialog('open');            
            
            } else {
        
                if (typeof isAlwaysNewTab !== 'undefined' && isAlwaysNewTab && 
                    typeof $wsArea.attr('data-dm-id') !== 'undefined' && $wsArea.attr('data-dm-id') != '') {

                    var $appTab = $wsArea.closest("div[id*='app_tab_']");
                    var dmId = $wsArea.attr('data-dm-id');
                    var appTabId = $appTab.attr('id');
                    var $li = $('body').find("a[href='#"+appTabId+"']").closest('li');

                    $appTab.empty().remove(); $li.remove();

                    var $dmAppTab = $('body').find("#object-value-list-"+dmId).closest("div[id*='app_tab_']");

                    if ($dmAppTab.length) {
                        var dmAppTabId = $dmAppTab.attr('id');
                        $('body').find("a[href='#"+dmAppTabId+"']").tab('show');
                    }

                } else {
                    var $container = $wsArea.closest(".dv-process");
                    $container.empty().hide();
                    $container.closest(".tab-pane").find("div.row:eq(0)").show();
                    $container.closest(".tab-pane").find("div.main-dataview-container").show();
                }

                $(window).trigger('resize');        
            }   

        }

        return;
    }
    
    var $pageContent = $("div.pf-header-main-content:first");
    $(".second-content-", $pageContent).addClass("display-none").hide();
    $(".first-content-", $pageContent).removeClass("display-none").show();
    $(window).trigger('resize');
}
function bpPrintPosByInvoiceId(invoiceId, response, isMulti) {
    if (typeof isPosAddonScript === 'undefined') {
        $.getScript(URL_APP+'middleware/assets/js/pos/addon.js').done(function() {
            printPosByInvoiceId(invoiceId, response, isMulti);
        });
    } else {
        printPosByInvoiceId(invoiceId, response, isMulti);
    }  
}
function bpPrintTemplatePosByInvoiceId(invoiceId, response, templateId) {
    if (typeof isPosAddonScript === 'undefined') {
        $.getScript(URL_APP+'middleware/assets/js/pos/addon.js').done(function() {
            printTemplatePosByInvoiceId(invoiceId, response, templateId);
        });
    } else {
        printTemplatePosByInvoiceId(invoiceId, response, templateId);
    }  
}
function bpPrintTemplateByResponse(mainSelector, metaDataCode, response) {
    
    if (typeof response !== 'undefined' && isObject(response) && Object.keys(response).length) {
        
        $.ajax({
            type: 'post',
            url: 'mdtemplate/printTemplateByResponse',
            data: {metaDataCode: metaDataCode, responseData: response}, 
            dataType: 'json',
            beforeSend: function() {
                Core.blockUI({message: 'Printing...', boxed: true});
            },
            success: function(data) {

                PNotify.removeAll();

                if (data.status == 'success') {

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
                    
    } else {
        new PNotify({
            title: 'Error',
            text: 'No data!',
            type: 'error',
            addclass: pnotifyPosition,
            sticker: false
        });
    }
}
function bpButtonTimer(mainSelector, fieldPath, btnType, hideshow, text) {
    var $getPathElement = mainSelector.find("[data-path='" + fieldPath + "']");
    if ($getPathElement.length) {
        if (hideshow !== '') {
            hideshow == 'hide' ? $getPathElement.parent().find('button.'+btnType).hide() : $getPathElement.parent().find('button.'+btnType).show();
        }
        if (typeof text !== 'undefined') {
            $getPathElement.parent().find('button.'+btnType).text(text);
        }
    }
}
function bpButtonTimerAction(mainSelector, fieldPath, btnType) {
    var $getPathElement = mainSelector.find("[data-path='" + fieldPath + "']");
    if ($getPathElement.length) {
        timerAction($getPathElement.parent().find('button.'+btnType));
    }
}
function bpChangeButtonName(mainSelector, elem, fieldName, changeName) {
    var $getButtonName = mainSelector.find("button[data-path='"+fieldName+"']");
    var $selectedTR = mainSelector.find('.bprocess-table-dtl > .tbody > .bp-detail-row.currentTarget');

    if ($selectedTR) {
        $selectedTR.find("button[data-path='"+fieldName+"']").text(changeName);
    } else if ($getButtonName.length) {
        $getButtonName.text(changeName);
    }
    return;
}
function bpMultiTabClose(mainSelector, metaDataId, type) {
    $tabMainContainer = $('body').find("div.m-tab > div.tabbable-line > ul.card-multi-tab-navtabs");
    multiTabCloseConfirm($tabMainContainer.find("a[href='#app_tab_" + metaDataId + "']"), type);
}
function bpPrintTemplateByProcess(mainSelector, elem, processId, templateId) {
    var $getProForm = $('#wsForm', '#dialog-businessprocess-' + processId);
    callWebServiceByMetaPrintAjaxSubmit($getProForm, '', $getProForm.parent().attr('data-bp-uniq-id'), elem, processId, '', templateId);
    return;
}
function bpGetPanelSelectedRowVal(dvId, panelList, field) {
    var $dv = $('#object-value-list-'+dvId);
    
    if ($dv.length) {
        panelList = panelList.toLowerCase();
        
        if (panelList == 'firstlist') {
            var $a = $dv.find('ul[data-part="dv-twocol-first-list"] .dv-twocol-f-selected');
        } else if (panelList == 'secondlist') {
            var $a = $dv.find('#dv-twocol-second-list .dv-twocol-f-selected');
            if ($a.length == 0) {
                $a = $dv.find('#dv-twocol-second-list a.jstree-clicked span[data-second-id]');
            }
            
            if ($a.length == 0 && typeof isDataViewPanelTwoColReloadRow != 'undefined' && isDataViewPanelTwoColReloadRow) {
                $a = isDataViewPanelTwoColReloadRow;
            }
        }

        if (typeof $a != 'undefined' && $a.length && field) {
            
            field = field.toLowerCase();
            var rowData = $a.data('rowdata');

            if (typeof rowData !== 'object') {
                rowData = JSON.parse(rowData);
            }

            if (rowData.hasOwnProperty(field)) {
                return rowData[field];
            }
        }
    }
    
    return '';
}
function bpGetVisiblePanelSelectedRowVal(panelList, field) {
    var $panelTypeDv = $('.pf-paneltype-dataview:visible:eq(0)');
    
    if ($panelTypeDv.length) {
        
        panelList = panelList.toLowerCase();
        
        if (panelList == 'firstlist') {
            var $a = $panelTypeDv.find('ul[data-part="dv-twocol-first-list"] .dv-twocol-f-selected');
        } else if (panelList == 'secondlist') {
            var $a = $panelTypeDv.find('#dv-twocol-second-list .dv-twocol-f-selected');
            if ($a.length == 0) {
                $a = $panelTypeDv.find('#dv-twocol-second-list a.jstree-clicked span[data-second-id]');
            }
        }
        
        if (typeof $a != 'undefined' && $a.length && field) {
            
            field = field.toLowerCase();
            var rowData = $a.data('rowdata');

            if (typeof rowData !== 'object') {
                rowData = rowData.replace(/\\&quot;/g, '&quot;');
                rowData = JSON.parse(rowData);
            }

            if (rowData.hasOwnProperty(field)) {
                return rowData[field];
            }
        }
    }
    
    return '';
}
function bpVisiblePanelDataViewReload(panelList) {
    
    var $panelTypeDv = $('.pf-paneltype-dataview:visible:eq(0)');
    
    if ($panelTypeDv.length) {
        
        panelList = panelList.toLowerCase();
        
        if (panelList == 'firstlist' || panelList == '') {
            var $list = $panelTypeDv.find('ul[data-part="dv-twocol-first-list"]');
        } else if (panelList == 'secondlist') {
            var $list = $panelTypeDv.find('#dv-twocol-second-list');
        }
        
        if ($list.length) {
            
            if (panelList == 'secondlist') {
                
                if (typeof panelDvRefreshSecondList === 'function') {
                    var uniqId = $panelTypeDv.data('uniqid');
                    panelDvRefreshSecondList(uniqId);
                }
                
            } else {
                
                var $lastMenu = $list.find('.nav-item-open:visible:last');
            
                if ($lastMenu.length) {

                    var uniqId = $panelTypeDv.data('uniqid');
                    var dvId = $panelTypeDv.data('process-id');
                    var $firstMenu = $lastMenu.find('> a.nav-link:eq(0)');
                    var $selectedRow = $lastMenu.find('.dv-twocol-f-selected');
                    var prevRowId = $selectedRow.data('id');

                    $.ajax({
                        type: 'post',
                        url: 'mdobject/dvPanelChildDataList',
                        data: {dvId: dvId, id: $firstMenu.data('id')}, 
                        dataType: 'json', 
                        success: function(data) {

                            var treeData = data.treeData;

                            if (treeData.length) {

                                var subMenu = '', subMenuClass = '', selectMenuClass = '', icon = '', listMetaDataCriteria = '', metaTypeId = '';

                                for (var key in treeData) {

                                    subMenuClass = '';
                                    selectMenuClass = '';
                                    icon = '';
                                    listMetaDataCriteria = '';
                                    metaTypeId = '';

                                    if (treeData[key].hasOwnProperty('childrecordcount') && treeData[key]['childrecordcount']) {
                                        subMenuClass = ' nav-item-submenu';
                                    } 

                                    if (treeData[key].hasOwnProperty('icon') && treeData[key]['icon']) {
                                        icon = '<i class="'+treeData[key]['icon']+' font-weight-bold" style="color: '+treeData[key]['color']+';"></i> ';
                                        subMenuClass += ' with-icon';
                                    }

                                    if (treeData[key].hasOwnProperty('listmetadatacriteria') && treeData[key]['listmetadatacriteria']) {
                                        listMetaDataCriteria = treeData[key]['listmetadatacriteria'];
                                    }

                                    if (treeData[key].hasOwnProperty('metatypeid') && treeData[key]['metatypeid']) {
                                        metaTypeId = treeData[key]['metatypeid'];
                                    }

                                    if (prevRowId == treeData[key][window['idField_'+uniqId]]) {

                                        selectMenuClass = ' dv-twocol-f-selected';

                                        if (panelList == 'firstlist') {
                                            var $secondListName = $panelTypeDv.find('[data-secondlist-name="1"]');
                                            if ($secondListName.length) {
                                                $secondListName.text(treeData[key][window['nameField_'+uniqId]]);
                                            }
                                        }
                                    }

                                    subMenu += '<li class="nav-item'+subMenuClass+'"><a href="javascript:void(0);" data-id="' + treeData[key][window['idField_'+uniqId]] + '" data-listmetadataid="' + treeData[key]['metadataid'] + '" data-listmetadatacriteria="'+listMetaDataCriteria+'" data-metatypeid="'+metaTypeId+'" data-rowdata="'+htmlentities(JSON.stringify(treeData[key]), 'ENT_QUOTES', 'UTF-8')+'" class="nav-link v2'+selectMenuClass+'">' + icon + treeData[key][window['nameField_'+uniqId]] + '</a></li>';
                                }

                                $lastMenu.find('ul.nav-group-sub').remove();
                                $lastMenu.append('<ul class="nav nav-group-sub" style="display: block;">'+subMenu+'</ul>');
                            }
                        }
                    });
                }
            }
        }
    }
    
    return;
}
function bpSetVisiblePanelClickRowId(rowId) {
    
    var $panelTypeDv = $('.pf-paneltype-dataview:visible:eq(0)');
    
    if ($panelTypeDv.length) {
        var uniqId = $panelTypeDv.data('uniqid');
        window['clickRowId_' + uniqId] = rowId;
    }
    
    return;
}

function saveBtnPositionFixed(mainSelector) {
    $(window).scroll(function() {
		
        var scrollPos = $(this).scrollTop();
        var $systemHeader = $('body').find('.system-header').height();
        var scrollPosTop =  mainSelector.find('.meta-toolbar').offset().top - $systemHeader;
        var leftPosition = 50;

        if (mainSelector.closest('div.tab-pane').find('.sidebar.sidebar-right').length > 0) {
            leftPosition += mainSelector.closest('div.tab-pane').find('.sidebar.sidebar-right').width();
        }
		
        if (scrollPos > scrollPosTop) {
            mainSelector.find('.meta-toolbar').find('.float-right').css({
                position: 'fixed',
                right: leftPosition + 'px',
                top: '99px'
            });
        } else {
            mainSelector.find('.meta-toolbar').find('.float-right').removeAttr('style');
            mainSelector.find('.meta-toolbar').parent().find('.row:eq(0)').addClass('w-100');
        }
    });
}

function runFunctionOtherProcess(mainSelector, processId, callbackFunction, inputFieldPath) {
    var uniqId = $('body').find('div[id="bp-window-'+ processId +'"]').attr('data-bp-uniq-id');
    try {
        if (!uniqId) {
            uniqId = $('body').find('div[data-process-id="'+ processId +'"]').attr('data-bp-uniq-id');;
        }
        
        if (typeof inputFieldPath !== 'undefined') {
            window[callbackFunction + '_' + uniqId](inputFieldPath);
        } else {
            window[callbackFunction + '_' + uniqId]();
        }
    } catch(err) {
        console.log('runFunctionOtherProcess : ' + err);
    }
}

function bpProcessPrintPreview(mainSelector, elem, processId, templateId, rowId, rowData, dataViewId) {
    if (elem == 'open') {
        elem = mainSelector.find('input:eq(0)');
    }
    processPrintPreview($(elem), processId, rowId, '', rowData, templateId);
    if (typeof dataViewId !== 'undefined' && dataViewId) {
        dataViewReload(dataViewId);
    }
}

function bpCloseProcessIsOpenedBp(mainSelector) {
    
    var processId = mainSelector.attr('data-process-id');
    var $renderProcessPageTag = mainSelector.closest('div.render-process-page');
    
    if (typeof $renderProcessPageTag !== 'undefined' && $renderProcessPageTag.length) {
        $renderProcessPageTag.empty();
    }
    
    return;
} 

function bpCallProcessBpOpenByExp(mainSelector, elem, srcMetaCode, processId, paramsPath, isBpOpenMetaDataId) {
    
    if (processId) {
        
        var params = '';
        
        if (paramsPath != '') {
            
            var paramsPathArr = paramsPath.split('|');

            for (var i = 0; i < paramsPathArr.length; i++) {
                
                if (paramsPathArr[i] === '1@defaultGetPf') {
                    params += 'defaultGetPf=1';
                } else {
                    var fieldPathArr = paramsPathArr[i].split('@');
                    var fieldPath = fieldPathArr[0].trim();
                    var inputPath = fieldPathArr[1].trim();

                    var fieldValue = '';

                    if (elem !== 'open' && typeof elem.prop('tagName') !== 'undefined'  && elem.prop('tagName') == 'BUTTON' && elem.closest('td[data-group-num]').length) {

                        var $cell = elem.closest('td[data-group-num]');
                        var $row = $cell.closest('tr');
                        var groupNum = $cell.attr('data-group-num');
                        var $field = $row.find('td[data-group-num="'+groupNum+'"]').find('[data-path="'+fieldPath+'"]');

                        if ($field.length) {
                            fieldValue = $field.val();
                        }

                    } else {

                        var bpElem = getBpElement(mainSelector, elem, fieldPath);
                        var bpViewElem = getBpRowViewElem(mainSelector, elem, fieldPath);

                        if (bpElem || bpViewElem) {
                            fieldValue = getBpRowParamNum(mainSelector, elem, fieldPath);
                        } else {
                            fieldValue = fieldPath;
                        }
                    }

                    params += inputPath + '=' + fieldValue + '&';
                }
            }
        }
        
        if (params != '') {
            _processPostParam = params;
        }
        
        if (typeof isBpOpenMetaDataId !== 'undefined') {
            
            var $htmlTag = '', findMainSelector = $('body').find('div.div-objectdatagrid-' + isBpOpenMetaDataId).parent();
            
            if (!findMainSelector.find('.render-process-page').length) {
                findMainSelector.prepend('<div class="render-process-page pl-2 pr-2"></div>');
                $htmlTag = $('div.div-objectdatagrid-' + isBpOpenMetaDataId).parent().find('.render-process-page');
            }
            
            $htmlTag = findMainSelector.find('.render-process-page');
            
            if ($htmlTag.length) {
                callWebServiceByMeta(processId, false, '', false, {callerType: srcMetaCode, isMenu: false}, undefined, undefined, function (data) {
                    $htmlTag.empty().append(data.Html).promise().done(function() {
                        Core.initBPAjax($htmlTag);
                    });
                }, undefined, undefined, isBpOpenMetaDataId);
            } else {
                callWebServiceByMeta(processId, true, '', false, {callerType: srcMetaCode, isMenu: false});
            }
            
        } else {
           callWebServiceByMeta(processId, true, '', false, {callerType: srcMetaCode, isMenu: false});
        }
    }
    
    return;
}

function bpRowBtnText(mainSelector, groupPath, text) {
    mainSelector.find('button.bp-add-one-row[data-action-path="'+ groupPath +'"]').empty().html(text);
}

function bpAddBtnPositionBottom(mainSelector, groupPath) {
    $(window).scroll(function() {
        var scrollPos = $(this).scrollTop();
        var scrollPosTop =  mainSelector.find('button.bp-add-one-row[data-action-path="'+ groupPath +'"]').offset().top - 20;
        return false;
        
        if (scrollPos > scrollPosTop) {
            mainSelector.find('button.bp-add-one-row[data-action-path="'+ groupPath +'"]').css({
                position: 'fixed',
                right: '50px',
                top: '99px'
            });
        } else {
            mainSelector.find('button.bp-add-one-row[data-action-path="'+ groupPath +'"]').removeAttr('style');
        }
    });
}

function bpGetSearchNoResultText(mainSelector, elem, fieldPath) {
    var $bpElem = getBpElement(mainSelector, elem, fieldPath);
    
    if (typeof $bpElem == 'undefined' || $bpElem == false) {
        return '';
    }
    
    if ($bpElem.prop('tagName') == 'SELECT' && $bpElem.hasAttr('data-snoresult-text')) {
        return $bpElem.attr('data-snoresult-text');
    } 
    
    return '';
}
function bpStatusBtnChangePosition(mainSelector, positionCode) {
    
    setTimeout(function() {
        var $wfmStatusBtn = mainSelector.find('.bp-wfmstatus-btns');
        var $wfmStatusBtns = $wfmStatusBtn.find('> button');
    
        if ($wfmStatusBtns.length) {
            
            var $wfmStatusBtnsClone = $wfmStatusBtns.clone();
            var $dialog = mainSelector.closest('.ui-dialog');
            var $buttonSet = $dialog.find('.ui-dialog-buttonset');
            
            $wfmStatusBtnsClone.each(function() {
                var $this = $(this);
                var bgColor = $this.css('background-color');
                $this.css({'background-color': '', 'border-color': bgColor, 'color': bgColor});
                $this.attr('onmouseenter', "$(this).css({'background-color': '"+bgColor+"', 'color': '#fff'})");
                $this.attr('onmouseleave', "$(this).css({'background-color': '', 'color': '"+bgColor+"'})");
            });
            
            if (positionCode == 'bottom-right') {
                
                var $rightButton = $buttonSet.find('button:not(.float-left):first');
                
                if ($rightButton.length) {
                    $rightButton.before($wfmStatusBtnsClone);
                } else {
                    $buttonSet.append($wfmStatusBtnsClone);
                }
                
            } else if (positionCode == 'bottom-left') {
                
                var $leftButton = $buttonSet.find('button.float-left:last');
                $wfmStatusBtnsClone.addClass('float-left');
                
                if ($leftButton.length) {
                    $leftButton.after($wfmStatusBtnsClone);
                } else {
                    $buttonSet.prepend($wfmStatusBtnsClone);
                }
            }
            
            $wfmStatusBtn.nextAll('.bp-top-hr').remove();
            $wfmStatusBtn.remove();
        }
    }, 1);
    
    return;
}
/**
 * Одоохондоо зөвхөн файл дээр хийлээ.
 * Цаашдаа бусад төрлүүд дээр нь оруулаад явна
 */
function bpAddonRequired(mainSelector, type) {
    if (type === 'file') {
        var $tab = mainSelector.find('ul.bp-addon-tab > li > a[data-addon-type="'+type+'"]');
        if ($tab.length < 1) {
            $tab = mainSelector.find('a[data-addon-type="'+type+'"]');
        }

        $tab.attr({'data-only-required': '1', 'data-required': '2'});
        $tab.prepend('<span class="required">*</span>');
    } else if (type === 'mv_relation') {
        var $tab = mainSelector.find('ul.bp-addon-tab > li > a[data-addon-type="'+type+'"]');
        if ($tab.length < 1) {
            $tab = mainSelector.find('a[data-addon-type="'+type+'"]');
        }
        
        $tab.attr({'data-only-required': '1', 'data-required': '2'});
        $tab.prepend('<span class="required">*</span>');
    }
    return;
}
function bpAddonNonRequired(mainSelector, type) {
    if (type === 'file') {
        var $tab = mainSelector.find('ul.bp-addon-tab > li > a[data-addon-type="'+type+'"]');
        if ($tab.length < 1) {
            $tab = mainSelector.find('a[data-addon-type="'+type+'"]');
        }
        
        $tab.attr('data-only-required', '0');
        $tab.find('span.required').remove();
    } else if (type === 'mv_relation') {
        var $tab = mainSelector.find('ul.bp-addon-tab > li > a[data-addon-type="'+type+'"]');
        if ($tab.length < 1) {
            $tab = mainSelector.find('a[data-addon-type="'+type+'"]');
        }
        
        $tab.attr({'data-only-required': '0', 'data-required': '0'});
        $tab.find('span.required').remove();
    }
    return;
}
function bpAddonCountRequired(mainSelector, type, count) {
    if (type === 'file') {
        var $tab = mainSelector.find('ul.bp-addon-tab > li > a[data-addon-type="'+type+'"]');
        $tab.attr({'data-only-required': '1', 'data-required': '2', 'data-count-required': count});
        if ($tab.find('.required').length == 0) {
            $tab.prepend('<span class="required">*</span>');
        }
    }
    return;
}
function bpAddonNonCountRequired(mainSelector, type) {
    if (type === 'file') {
        var $tab = mainSelector.find('ul.bp-addon-tab > li > a[data-addon-type="'+type+'"]');
        $tab.attr('data-only-required', '0').removeAttr('data-count-required');
        $tab.find('span.required').remove();
    }
    return;
}
function bpSetAddonTabFileExtension(mainSelector, tabName, fileExt) {
    var $tab = mainSelector.find('.bp-addon-tab > li > a[data-addon-type="'+tabName+'"]');
    if ($tab.length) {
        $tab.attr('data-ext', fileExt);
    }
    return;
}
function bpSetAddonTabMessage(mainSelector, tabName, position, type, message) {
    var tabName = tabName.toLowerCase();
    var $tabScript = mainSelector.find('script[data-msg-tab="'+tabName+'"]');
    if ($tabScript.length) {
        $tabScript.empty().append('<div class="d-flex w-100"></div><div class="alert alert-'+type+' alert-styled-left mb10">'+message+'</div>');
    } else {
        var messageScript = ['<script type="text/template" data-msg-tab="'+tabName+'" data-position="'+position+'">'];
        
        messageScript.push('<div class="d-flex w-100"></div><div class="alert alert-'+type+' alert-styled-left mb10">'+message+'</div>');
        messageScript.push('</script>');
        
        mainSelector.append(messageScript.join(''));
    }
    return;
}
function bpSetAddonActionControl(mainSelector, tabName, controls) {
    var $tab = mainSelector.find('ul.bp-addon-tab > li > a[data-addon-type="'+tabName+'"]');
    var $tabContent = $($tab.attr('href'));
    $tabContent.attr('data-controls', controls);
    
    if ($tabContent.find('[data-refstructureid]').length) {
        
        var mvRelationControls = controls;
        if (mvRelationControls.indexOf('addIndicator=hide') !== -1) {
            $tabContent.find('[data-action-name="addIndicator"]').hide();
        }
        if (mvRelationControls.indexOf('addIndicatorValue=hide') !== -1) {
            $tabContent.find('[data-action-name="addIndicatorValue"]').hide();
        }
        if (mvRelationControls.indexOf('removeIndicator=hide') !== -1) {
            $tabContent.find('[data-action-name="removeIndicator"]').hide();
        }
        if (mvRelationControls.indexOf('removeIndicatorValue=hide') !== -1) {
            $tabContent.find('[data-action-name="removeIndicatorValue"]').hide();
        }
        
        if (mvRelationControls.indexOf('addIndicator=show') !== -1) {
            $tabContent.find('[data-action-name="addIndicator"]').show();
        }
        if (mvRelationControls.indexOf('addIndicatorValue=show') !== -1) {
            $tabContent.find('[data-action-name="addIndicatorValue"]').show();
        }
        if (mvRelationControls.indexOf('removeIndicator=show') !== -1) {
            $tabContent.find('[data-action-name="removeIndicator"]').show();
        }
        if (mvRelationControls.indexOf('removeIndicatorValue=show') !== -1) {
            $tabContent.find('[data-action-name="removeIndicatorValue"]').show();
        }
        
        if (mvRelationControls.indexOf('allaction=hide') !== -1) {
            $tabContent.find('[data-action-name="addIndicator"], [data-action-name="addIndicatorValue"], [data-action-name="removeIndicator"], [data-action-name="removeIndicatorValue"]').hide();
        }
        if (mvRelationControls.indexOf('allaction=show') !== -1) {
            $tabContent.find('[data-action-name="addIndicator"], [data-action-name="addIndicatorValue"], [data-action-name="removeIndicator"], [data-action-name="removeIndicatorValue"]').show();
        }
    }
    
    return;
}
function bpSetAddonTabFileSize(mainSelector, tabName, fileSize) {
    var $tab = mainSelector.find('.bp-addon-tab > li > a[data-addon-type="'+tabName+'"]');
    if ($tab.length) {
        fileSize = fileSize.toString();
        fileSize = fileSize.replace(/\s/g, '');
        fileSize = fileSize.toLowerCase();
        var byteSize = fileSize;
        if (fileSize.indexOf('mb') !== -1) {
            byteSize = (parseFloat(fileSize) * 1000000).toFixed(2);
        }
        $tab.attr('data-file-size', byteSize);
    }
    return;
}
function bpGetAddonTabCount(mainSelector, tabName) {
    var $tab = mainSelector.find('.bp-addon-tab > li > a[data-addon-type="'+tabName+'"]');
    if ($tab.length) {
        return Number($tab.find('[data-file-count]').attr('data-file-count'));
    }
    return 0;
}
function bpSetComboSelectedValue(mainSelector, elem, fieldPath, id, name) {
    
    var $bpElem = getBpElement(mainSelector, elem, fieldPath);
    
    if ($bpElem && id != '' && name != '') {
        
        var selectedValue = $bpElem.val();
        
        if (selectedValue != id) {
            
            var comboDatas = [];
        
            $bpElem.append($("<option />").val(id).text(name).attr({'selected': 'selected', 'data-row-data': '{&quot;id&quot;:&quot;'+id+'&quot;,&quot;name&quot;:&quot;'+name+'&quot;}'}));

            comboDatas.push({
                id: id,
                text: name
            });

            $bpElem.select2({ results: comboDatas });
        }
    }
    
    return;
}
function bpFileChangeImage(element, callback, width, height) {
    if (typeof element.files !== 'undefined' && typeof element.files[0] !== 'undefined') {
        getBase64(element, element.files[0], callback, width, height);
        return;
    }
    
    PNotify.removeAll();
    new PNotify({
        title: 'Warning',
        text: 'File олдсонгүй!',
        type: 'warning',
        sticker: false
    });    
}
function bpCropByImage(element, base64img, callback, width, height) {
    $.cachedScript('assets/custom/addon/plugins/jcrop/js/jquery.Jcrop.min.js').done(function() {
        $.ajax({
            type: 'post',
            url: 'mddoc/cropByImageForm',
            dataType: 'json',
            data: {
                'base64img': base64img,
                'width': width,
                'height': height,
            },
            beforeSend: function() {
                Core.blockUI({message: 'Loading...', boxed: true});
                $('head').append('<link rel="stylesheet" href="assets/custom/addon/plugins/jcrop/css/jquery.Jcrop.min.css" type="text/css" />');
                $('head').append('<link rel="stylesheet" href="assets/custom/addon/admin/pages/css/image-crop.css" type="text/css" />');
            },
            success: function(data) {
                
                var dialogName='#dialog-crop-image' + data.uniqId;
                if (!$(dialogName).length) {
                    $('<div id="' + dialogName.replace('#', '') + '"></div>').appendTo('body');
                }

                $(dialogName).html(data.html);
                $(dialogName).dialog({
                    cache: false,
                    resizable: true,
                    bgiframe: true,
                    autoOpen: false,
                    title: data.title,
                    width: (950 < data.imageWidth) ? 950 : data.imageWidth+50,
                    height: (450 < data.imageHeigth) ? 450 : data.imageHeigth+110,
                    modal: true,
                    close: function() {
                        var bpUniqId = $(element).closest('div.xs-form').attr('data-bp-uniq-id');
                        if (typeof callback === 'function') {
                            callback(base64img);
                            $(dialogName).dialog('close');
                        } else if (typeof(window[callback + '_' + bpUniqId]) === 'function') {
                            window[callback + '_' + bpUniqId](base64img);
                            $(dialogName).dialog('close');
                        } else  {
                            alert('function олдсонгүй');
                            console.log(callback, bpUniqId);
                        }
                        $(dialogName).empty().dialog('destroy').remove();
                    },
                    buttons: [
                        {text: plang.get('crop_btn'), class: 'btn btn-success btn-sm', click: function() {
                            $.ajax({
                                type: 'post',
                                url: 'mddoc/cropImg',
                                data: {
                                    x: $('#crop_x_' + data.uniqId).val(),
                                    y: $('#crop_y_' + data.uniqId).val(),
                                    w: $('#crop_w_' + data.uniqId).val(),
                                    h: $('#crop_h_' + data.uniqId).val(),
                                    image_path: $('#crop_image_path_' + data.uniqId).val(),
                                },
                                dataType: 'json',
                                beforeSend: function() {
                                    Core.blockUI({message: 'Loading...', boxed: true});
                                },
                                success: function(data) {
                                    if (typeof data.status !== 'undefined' && data.status === 'success') {
                                        var bpUniqId = $(element).closest('div.xs-form').attr('data-bp-uniq-id');
                                        if (typeof callback === 'function') {
                                            callback(data, bpUniqId);
                                            $(dialogName).dialog('close');
                                        } else if (typeof(window[callback + '_' + bpUniqId]) === 'function') {
                                            window[callback + '_' + bpUniqId](data, bpUniqId);
                                            $(dialogName).dialog('close');
                                        } else  {
                                            alert('function олдсонгүй');
                                            console.log(callback, bpUniqId);
                                        }
                                    } else {
                                        console.log(data);
                                    }
                                    Core.unblockUI();
                                }
                            });
                        }},
                        {text: plang.get('refresh_btn'), class: 'btn btn-danger btn-sm pull-left', click: function() {
                            $('.crop' + data.uniqId).find('.jcrop-holder img').attr('src', $('#main_image_path_' + data.uniqId).val());
                            $('#crop_image_path_' + data.uniqId).val($('#main_image_path_' + data.uniqId).val());
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
                $(dialogName).dialog('open');

                Core.unblockUI();
            },
            error: function() { alert("Error"); }
        });
    });
}
function getBase64(element, file, callback, width, height) {
    var $data = false;
    var reader = new FileReader();
    reader.readAsDataURL(file);
    reader.onload = function () {
        bpCropByImage(element, reader.result, callback, width, height)
    };
    reader.onerror = function (error) {
        PNotify.removeAll();
        new PNotify({
            title: 'Warning',
            text: 'File олдсонгүй!',
            type: 'warning',
            sticker: false
        });    
    };
}
function bpMoveBetweenCell(uniqId, mode) {
    if (mode == 'down') {
        uniqId = uniqId.replace('_', '');
        window['tabMoveMode_' + uniqId] = 'down';
    }
    return;
}
/*
 * Transdep GET DATA
 */
function getTransdepDataIntegration(params) {
    
    var response = $.ajax({
        type: 'post',
        url: 'mdintegration/getTransdepDataIntegration/' + params,
        dataType: 'json',
        async: false
    });

    return response.responseJSON;
}

function getMongolbankValue(params) {
    
    var response = $.ajax({
        type: 'post',
        url: 'mdintegration/getMongolbankValue/' + params,
        dataType: 'json',
        async: false
    });

    return response.responseJSON;
}

function getCirsIntegrationData(law_number, contract_number) {

    var response = $.ajax({
        type: 'post',
        url: 'mdintegration/getCirsIntegrationData/',
        data: { law_number: law_number, contract_number: contract_number },
        dataType: 'json',
        async: false
    });

    return response.responseJSON;
}

function getCirsIntegrationDataById(id) {

    var response = $.ajax({
        type: 'post',
        url: 'mdintegration/getCirsIntegrationDataById/',
        data: { id: id },
        dataType: 'json',
        async: false
    });

    return response.responseJSON;
}

function getEbarimtVat(regNumber) {
    Core.blockUI({message: 'Түр хүлээнэ үү...', boxed: true});

    var response = $.ajax({
        type: 'post',
        url: 'mdintegration/getEbarimtVat/',
        data: {regNumber: regNumber},
        dataType: 'json',
        async: false
    });

    Core.unblockUI();
    return response.responseJSON;
}

function getEbarimtLoginName(regNumber) {
    Core.blockUI({message: 'Түр хүлээнэ үү...', boxed: true});
    
    var response = $.ajax({
        type: 'post',
        url: 'mdintegration/getEbarimtLoginName/',
        data: {regNumber: regNumber},
        dataType: 'json',
        async: false
    });
    
    Core.unblockUI();
    
    return response.responseJSON;
}

function transferSplitValueToHdrFunction(mainSelector, srcSplitPath, trgGroupPath, trgFillPath) {
    
    try {
        
        var $body = mainSelector.find('[data-table-path="'+trgGroupPath+'"] > .tbody > .bp-detail-row'),
            $comboValues = [];
            
        $body.each(function ($index, $tr) {
            var $bpElem = getBpElement(mainSelector, $tr, srcSplitPath);

            if ($bpElem) {
                $comboValues.push($bpElem.val());
            }
        });

        if ($comboValues.length == 0) {
            return;
        }
        
        var $bpElemHdr = getBpElement(mainSelector, 'open', trgFillPath);
        
        if ($bpElemHdr.prop("tagName") == 'SELECT') {
            
            if ($bpElemHdr.hasClass('select2')) {
                if (!$bpElemHdr.hasClass('data-combo-set')) {
                    $bpElemHdr.trigger("select2-opening", [true]);
                }
                $bpElemHdr.select2('val', $comboValues);
            } 
            
        } else {
            var $comboValuesTmp = [];
            $.each($comboValues, function (ci, cr) {
                if (cr) {
                    $comboValuesTmp.push(cr);
                }
            });
            
            $comboValues = $comboValuesTmp;
            if ($comboValues) {
                if ($bpElemHdr.hasClass('popupInit') || $bpElemHdr.hasClass('combogridInit')) {
                    var valIds = $comboValues.join(',');
                    /*if (valIds.indexOf(',') !== -1) {
                        setLookupPopupValueMulti($parent, $codeField, _processId, _lookupId, _paramRealPath, valId);
                    } else {
                        setLookupPopupValue($bpElemHdr, valIds);
                    }*/
                    setLookupPopupValue($bpElemHdr, valIds);
                } else {
                    $bpElemHdr.val($comboValues.join(','));
                }
            }
        } 
        
    } catch(err) {
        console.log('transferSplitValueToHdrFunction_ : ' + err);
    }
    return;
}
function bpCyrillicToLatin(str) {
    var trans = {
        'А': 'A', 'а': 'a', 'Б': 'B', 'б': 'b', 'В': 'V', 'в': 'v', 
        'Г': 'G', 'г': 'g', 'Д': 'D', 'д': 'd', 'Е': 'Ye', 'е': 'ye', 
        'Ё': 'Yo', 'ё': 'yo', 'Ж': 'J', 'ж': 'j', 'З': 'Z', 'з': 'z', 
        'И': 'I', 'и': 'i', 'Й': 'I', 'й': 'i', 'К': 'K', 'к': 'k', 
        'Л': 'L', 'л': 'l', 'М': 'M', 'м': 'm', 'Н': 'N', 'н': 'n', 
        'О': 'O', 'о': 'o', 'Ө': 'O', 'ө': 'o', 'П': 'P', 'п': 'p', 
        'Р': 'R', 'р': 'r', 'С': 'S', 'с': 's', 'Т': 'T', 'т': 't', 
        'У': 'U', 'у': 'u', 'Ү': 'U', 'ү': 'u', 'Ф': 'F', 'ф': 'f', 
        'Х': 'Kh', 'х': 'kh', 'Ц': 'Ts', 'ц': 'ts', 'Ч': 'Ch', 'ч': 'ch', 
        'Ш': 'Sh', 'ш': 'sh', 'Щ': 'Shch', 'щ': 'shch', 'Ь': 'I', 'ь': 'i', 
        'Ы': 'Y', 'ы': 'y', 'Ъ': 'I', 'ъ': 'i', 'Э': 'E', 'э': 'e', 
        'Ю': 'Yu', 'ю': 'yu', 'Я': 'Ya', 'я': 'ya' 
    };
    var converted = strtr(str, trans);
    return converted;
}
function bpSetMetaPopupField(mainSelector, elem, fieldPath) {
    var $bpElem = getBpElement(mainSelector, elem, fieldPath);
    if ($bpElem) {
        $bpElem.each(function(){
            var $this = $(this);
            var $parent = $this.closest('.input-group');
            if ($parent.find('.input-group-btn').length == 2) {
                $parent.find('.input-group-btn:eq(1)').addClass('flex-col-group-btn');
                $parent.find('.input-group-btn:eq(0)').after('<span class="input-group-btn not-group-btn">'+
                    '<div class="btn-group pf-meta-manage-dropdown">'+
                        '<button class="btn grey-cascade btn-bordered form-control-sm mr0 dropdown-toggle" type="button" data-toggle="dropdown"></button>'+
                        '<ul class="dropdown-menu dropdown-menu-right" role="menu" style="min-width: 126px;"></ul>'+
                    '</div>'+
                '</span>');
            }
        });
    }
    return;
}
function bpHideWfmStatusButton(mainSelector, statusCode) {
    if (statusCode) {
        setTimeout(function() {
            var $btn = mainSelector.find('.bp-wfmstatus-btns [data-statuscode="'+statusCode+'"]');
            if ($btn.length) {
                $btn.hide();
            }
        }, 50);
    }
    return;
}
function bpShowWfmStatusButton(mainSelector, statusCode) {
    if (statusCode) {
        setTimeout(function() {
            var $btn = mainSelector.find('.bp-wfmstatus-btns [data-statuscode="'+statusCode+'"]');
            if ($btn.length) {
                $btn.show();
            }
        }, 50);
    }
    return;
}
function bpKpiObjectHideButton(mainSelector, dtlCode, actionCode) {
    dtlCode = dtlCode.toLowerCase().trim();
    var $row = mainSelector.find('[data-dtlcode="'+dtlCode+'"]');
    
    if ($row.length) {
        actionCode = actionCode.toLowerCase().trim();
        $row.find('[data-objtype-action="'+actionCode+'"]').hide();
        $row.attr('data-objtype-ignore-action', actionCode);
    }
    
    return;
}
function bpGetBpMetaDataId(mainSelector) {
    if (mainSelector.hasAttr('data-process-id')) {
        return mainSelector.attr('data-process-id');
    }
    return '';
}
function bpKpiWizardGotoStep(mainSelector, step) {
    mainSelector.find('.wizard > .steps > ul[role="tablist"] > li:eq('+(step - 1)+') > a').click();
}
function bpGetSaveButtonCode(thisButton) {
    if (thisButton.hasAttr('data-savebuttoncode')) {
        return thisButton.attr('data-savebuttoncode');
    } else {
        if (thisButton.hasClass('bp-btn-saveadd')) {
            return 'saveadd';
        } else if (thisButton.hasClass('bp-btn-save')) {
            return 'save';
        }
    }
    return '';
}
function bpChangeDialogPosition(mainSelector, my, at) {
    var processId = mainSelector.attr('data-process-id');
    var $dialog = $('#dialog-businessprocess-'+processId);
    
    $dialog.on('dialogopen', function(){
        $dialog.dialog('option', 'position', {my: my, at: at, of: window});
    }); 
    return;
}
function bpIsBpOpened(mainSelector) {
    var processId = mainSelector.attr('data-process-id');
    var bpCount = $('#bp-window-' + processId).length;
    
    if (bpCount > 1) {
        return true;
    }
    return false;
}
function bpCloseActiveSysTab(mainSelector) {
    multiTabActiveAutoClose();
}
function bpFileFieldPreviewImage(mainSelector, elem, fieldPath) {
    var $bpElem = getBpElement(mainSelector, elem, fieldPath);
    
    if ($bpElem) {
        
        var $hidden = $bpElem.next('input[type="hidden"]');
        
        if ($hidden.length && $hidden.val() != '') {
            
            var $loadSection = $bpElem.closest('[data-section-path]:not(.uniform-uploader)');
            $loadSection.append('<img src="'+$hidden.val()+'" class="pf-filefield-imgpreview mt5 w-100 cursor-pointer"/>');
            
            var $thumb = $loadSection.find('img.pf-filefield-imgpreview');
            
            $thumb.on('click', function() {
                $.fancybox.open(
                    [{src: $(this).attr('src'), opts: {caption: $hidden.val().split('/').pop()}}],
                    {buttons: ['zoom', 'close']}
                );
            });
        }
        
        $bpElem.on('change', function() {
            
            var self = this, $this = $(self), 
                $section = $this.closest('[data-section-path]:not(.uniform-uploader)'), 
                fileUrl = $this.val(), ext = fileUrl.substring(fileUrl.lastIndexOf('.') + 1).toLowerCase();
            
            if (self.files && self.files[0] && (ext == 'gif' || ext == 'png' || ext == 'jpeg' || ext == 'jpg')) {
                
                var $thumb = $section.find('img.pf-filefield-imgpreview');
                var reader = new FileReader();
                
                if ($thumb.length == 0) {
                    $section.append('<img class="pf-filefield-imgpreview mt5 w-100 cursor-pointer"/>');
                    $thumb = $section.find('img.pf-filefield-imgpreview');
                }

                reader.onload = function(e) {
                    $thumb.attr('src', e.target.result);
                    
                    $thumb.on('click', function() {
                        $.fancybox.open(
                            [{src: $(this).attr('src'), opts: {caption: fileUrl.split('\\').pop()}}],
                            {buttons: ['zoom', 'close']}
                        );
                    });
                };

                reader.readAsDataURL(self.files[0]);

            } else {
                $section.find('.pf-filefield-imgpreview').remove();
            }
        });
        
    } else {
        var $bpElem = getBpRowViewElem(mainSelector, elem, fieldPath);
        if ($bpElem) {
            var $a = $bpElem.find('a[data-url]');
            if ($a.length) {
                var $loadSection = $bpElem.closest('[data-section-path]:not(.uniform-uploader)');
                $loadSection.empty().append('<img src="'+$a.attr('data-url')+'" class="pf-filefield-imgpreview w-100 cursor-pointer"/>');

                var $thumb = $loadSection.find('img.pf-filefield-imgpreview');

                $thumb.on('click', function() {
                    $.fancybox.open(
                        [{src: $(this).attr('src'), opts: {caption: $a.attr('data-url').split('/').pop()}}],
                        {buttons: ['zoom', 'close']}
                    );
                });
            }
        }
    }
    
    return;
}
function bpAddFullscreenButton(mainSelector) {
    if (!mainSelector.closest('.ui-dialog-content').length) {
        mainSelector.prepend('<span class="dv-right-tools-btn" style="position:absolute;z-index:1;right:0;margin-top:6px;">'+
            '<button type="button" class="btn btn-secondary btn-sm btn-circle default" title="Fullscreen" onclick="bpFullScreen(this);"><i class="fa fa-expand"></i></button>'+
        '</span>');
    }
    return;
}
function bpFilePathPreview(mainSelector, elem, fieldPath, physicalPath, styles) {
    var $bpElem = getBpElement(mainSelector, elem, fieldPath);
    
    if ($bpElem && physicalPath) {
        
        var fileExt = physicalPath.split('.').pop().toLowerCase();
        var $loadSection = $bpElem.closest('[data-section-path]:not(.uniform-uploader, .meta-autocomplete-wrap)');
        
        if (fileExt == 'jpeg' || fileExt == 'jpg' || fileExt == 'png' || fileExt == 'gif' || fileExt == 'bmp') {
            
            var inlineStyles = (typeof styles !== 'undefined' ? ' style="'+styles+'"' : '');
            
            $loadSection.find('.pf-filefield-imgpreview').remove();
            $loadSection.append('<img src="'+physicalPath+'" class="pf-filefield-imgpreview mt5 w-100 cursor-pointer"'+inlineStyles+'/>');
            
            var $thumb = $loadSection.find('img.pf-filefield-imgpreview');
            
            $thumb.on('click', function() {
                $.fancybox.open(
                    [{src: $(this).attr('src'), opts: {caption: physicalPath.split('/').pop()}}],
                    {buttons: ['zoom', 'close']}
                );
            });
            
        } else {
            
            var fileIcon = '', buttonName = plang.get('see_btn'), href = 'javascript:;', onClickAttr = ' onclick="bpFilePreview(this);"', 
                fileViewerAddress = getConfigValue('CONFIG_FILE_VIEWER_ADDRESS');
            
            if (fileViewerAddress) {
                $.ajax({
                    type: 'GET',
                    url: fileViewerAddress,
                    dataType: 'text',
                    async: false, 
                    complete: function (xhr) {
                        if (xhr.status != '200') {
                            fileViewerAddress = null;
                        }
                    }
                });
            }
    
            if (!fileViewerAddress) {
                buttonName = plang.get('download_btn');
                href = 'mdobject/downloadFile?fDownload=1&file=' + physicalPath;
                onClickAttr = '';
            }
            
            if (fileExt == 'pdf') {
                fileIcon = 'icon-file-pdf';
            } else if (fileExt == 'doc' || fileExt == 'docx') {
                fileIcon = 'icon-file-word';
            } else if (fileExt == 'xls' || fileExt == 'xlsx') {
                fileIcon = 'icon-file-excel';
            } else if (fileExt == 'zip' || fileExt == 'rar') {
                buttonName = plang.get('download_btn');
                href = 'mdobject/downloadFile?fDownload=1&file=' + physicalPath;
                onClickAttr = '';
                fileIcon = 'icon-file-zip';
            } else {
                buttonName = plang.get('download_btn');
                href = 'mdobject/downloadFile?fDownload=1&file=' + physicalPath;
                onClickAttr = '';
                fileIcon = 'icon-file-text2';
            }
            
            $loadSection.find('a[data-extension]').remove();
                        
            $loadSection.append('<a href="'+href+'" data-url="'+physicalPath+'" data-extension="'+fileExt+'"'+onClickAttr+' class="btn btn-sm btn-light"><i class="'+fileIcon+'"></i> '+buttonName+'</a>');
        }
    }
    return;
}
function bpFilePreviewByUrl(mainSelector, fileUrl) {
    if (fileUrl) {
        var fileExt = fileUrl.split('.').pop().toLowerCase();
        if (fileExt == 'pdf') {
            var opts = {rowId: '', fileExtension: fileExt, fileName: '', fullPath: fileUrl, contentId: ''};
            initFileViewer(mainSelector, opts);
        }
    }
    return;
}
function bpGetKpiAddonColVal(mainSelector, elem, fieldPath) {
    var selectedVal = ''; fieldPath = fieldPath.toLowerCase().trim();
    
    if (fieldPath.indexOf('.') !== -1) {
        var fieldPath = fieldPath.split('.');
        var dtlCode = fieldPath[0].trim();
        var $getRow = mainSelector.find("[data-dtl-code='"+dtlCode+"']");

        if ($getRow.length) {

            var colName = fieldPath[1].trim();
            var $getField = $getRow.find('[data-colcode="'+colName+'"]:eq(0)');

            if ($getField.length) {
                
                selectedVal = $getField.text().trim();
                
                if ($getField.hasClass('kpi-colcell-amount')) {
                    selectedVal = selectedVal.replace(/,/g, '');
                }
                
                if (!isNaN(parseFloat(selectedVal)) && !isNaN(selectedVal - 0)) {
                    return Number(selectedVal);
                }
            }
        }
        
    } else {
        
        var elem = $(elem), $row = elem.closest('tr'), 
        $getPath = $row.find('[data-colcode="'+fieldPath+'"]'); 
    
        if ($getPath.length) {
            selectedVal = $getPath.text().trim();
            if ($getPath.hasClass('kpi-colcell-amount')) {
                selectedVal = selectedVal.replace(/,/g, '');
            }
            if (!isNaN(parseFloat(selectedVal)) && !isNaN(selectedVal - 0)) {
                return Number(selectedVal);
            }
        }
    }
    
    return selectedVal;
}
function bpHideSection(mainSelector, sectionCode) {
    var $section = mainSelector.find('[data-section-code="'+sectionCode+'"]');
    if ($section.length) {
        $section.closest('.bl-section').hide();
    }
    return;
}
function bpShowSection(mainSelector, sectionCode) {
    var $section = mainSelector.find('[data-section-code="'+sectionCode+'"]');
    if ($section.length) {
        $section.closest('.bl-section').show();
    }
    return;
}
function setBpTranslateFieldVal(mainSelector, elem, fieldPath, getElement) {
    
    var $bpElem = getBpElement(mainSelector, elem, fieldPath);
    
    if ($bpElem) {
        if (getElement) {
            
            $bpElem.val(getElement.val());
            
            var $getElementTranslation = getElement.next('[data-translate-path]');
            
            if ($getElementTranslation.length) {
                var $setElementTranslation = $bpElem.next('[data-translate-path]');
                var translationJson = $getElementTranslation.val();
                
                if ($setElementTranslation.length) {
                    $setElementTranslation.val(translationJson);
                } else {
                    var name = $bpElem.attr('name').replace('param['+fieldPath+']', 'param['+fieldPath+'_translation]');
                    $bpElem.after('<textarea name="'+name+'" style="display:none" data-translate-path="'+fieldPath+'">'+translationJson+'</textarea>');
                }
            } else {
                
                var realPath = getElement.attr('data-path');
                var translationJson = 'pfTranslationValue';
                if (realPath.indexOf('.') !== -1) {
                    translationJson = realPath.substr(0, realPath.lastIndexOf('.')) + '.' + translationJson;
                } 
                
                var $getTranslateElem = getBpElement(mainSelector, elem, translationJson);
                
                if ($getTranslateElem.length && $getTranslateElem.val() != '') {
                    
                    var columnName = getElement.attr('[data-c-name]');
                    var translationObj = JSON.parse($getTranslateElem.val());
                    
                    if (columnName && translationObj.hasOwnProperty('value')) {
                        
                        translationObj = translationObj.value;
                        
                        if (translationObj.hasOwnProperty(columnName)) {
                            translationObj = translationObj[columnName];
                            
                            var $setElementTranslation = $bpElem.next('[data-translate-path]');
                            
                            if ($setElementTranslation.length) {
                                
                                $setElementTranslation.val(JSON.stringify(translationObj));
                                
                            } else {
                                
                                if (fieldPath.indexOf('.') !== -1) {
                                    translationJson = fieldPath.substr(0, fieldPath.lastIndexOf('.')) + '.' + translationJson;
                                } 
                            
                                var $setTranslateElem = getBpElement(mainSelector, elem, translationJson);
                                var columnName = $bpElem.next('[data-c-name]');

                                if (columnName && $setTranslateElem.length && $setTranslateElem.val() != '') {
                                    var setTranslationObj = JSON.parse($setTranslateElem.val());

                                    if (setTranslationObj.hasOwnProperty('value')) {
                                        setTranslationObj = setTranslationObj.value;
                                        if (setTranslationObj.hasOwnProperty(columnName)) {
                                            setTranslationObj[columnName] = translationObj;
                                        }

                                        var setTranslationObjNew = {};
                                        setTranslationObjNew.value = setTranslationObj;

                                        $setTranslateElem.val(JSON.stringify(setTranslationObj));
                                    }

                                } else if (columnName) {

                                    var setTranslationObj = {};

                                    setTranslationObj.value = {columnName: translationObj};
                                    $setTranslateElem.val(JSON.stringify(setTranslationObj));
                                }
                            }
                        }
                    }
                }
            }
        } else {
            $bpElem.val('');
        }
    }
    
    return;
}
function bpSetDataViewFilter(mainSelector, elem, dvId, filterParams) {

    if (typeof window['objectdatagrid_'+dvId] !== 'undefined') {
        
        var opt = window['objectdatagrid_'+dvId].datagrid('options'), queryParams = opt.queryParams;
        
        if (filterParams != '') {
            
            var filterParamsData = {}, filterParamsArr = filterParams.split('|');
        
            for (var i = 0; i < filterParamsArr.length; i++) { 
                var fieldPathArr = filterParamsArr[i].split('@');
                var fieldPath = fieldPathArr[0];
                var inputPath = fieldPathArr[1];
                var fieldValue = '';

                var $bpElem = getBpElement(mainSelector, elem, fieldPath);

                if ($bpElem) {
                    if ($bpElem.hasClass('bigdecimalInit')) {
                        if ($bpElem.val() != '') {
                            fieldValue = getBpRowParamNum(mainSelector, elem, fieldPath);
                        }
                    } else {
                        fieldValue = getBpRowParamNum(mainSelector, elem, fieldPath);
                    }
                } else {
                    fieldValue = fieldPath;
                }

                filterParamsData[inputPath] = fieldValue;
            }
            
            queryParams.uriParams = JSON.stringify(filterParamsData);
        } else {
            queryParams.uriParams = '';
        }
        
        if (opt.idField === null) {
            window['objectdatagrid_'+dvId].datagrid('load', queryParams);
        } else {
            window['objectdatagrid_'+dvId].treegrid('load', queryParams);
        }
    }
    
    return;
}
function bpDataViewRefresh(mainSelector, elem, dvId, filterParams) {
    var dataGrid = window['objectdatagrid_' + dvId];
    
    if (typeof dataGrid !== 'undefined') {
        
        if (dataGrid.hasClass('not-datagrid')) {
            window['explorerRefresh_' + dvId](this);
        } else {
            var opt = dataGrid.datagrid('options'), isLoad = false;
            
            if (typeof filterParams != 'undefined' && filterParams != 'allrows') {
                var queryParams = opt.queryParams, filterParamsData = {}, filterParamsArr = filterParams.split('|');
        
                for (var i = 0; i < filterParamsArr.length; i++) { 
                    var fieldPathArr = filterParamsArr[i].split('@');
                    var fieldPath = fieldPathArr[0];
                    var inputPath = fieldPathArr[1];
                    var fieldValue = '';

                    var $bpElem = getBpElement(mainSelector, elem, fieldPath);

                    if ($bpElem) {
                        if ($bpElem.hasClass('bigdecimalInit')) {
                            if ($bpElem.val() != '') {
                                fieldValue = getBpRowParamNum(mainSelector, elem, fieldPath);
                            }
                        } else {
                            fieldValue = getBpRowParamNum(mainSelector, elem, fieldPath);
                        }
                    } else {
                        fieldValue = fieldPath;
                    }

                    filterParamsData[inputPath] = fieldValue;
                }

                queryParams.uriParams = JSON.stringify(filterParamsData);
            
                isLoad = true;
            }
            
            _isRunAfterProcessSave = true;
            
            if (opt.idField === null) {
                if (isLoad) {
                    dataGrid.datagrid('load', queryParams);
                } else {
                    dataGrid.datagrid('reload');
                }
            } else if (typeof filterParams != 'undefined' && filterParams == 'allrows') {
                dataGrid.treegrid('reload');
            } else {
                var node = dataGrid.treegrid('getSelected');
                if (node && node.hasOwnProperty('_parentId') && (node._parentId != '' || node._parentId != null)) {
                    dataGrid.treegrid('reload', node._parentId);
                } else {
                    if (isLoad) {
                        dataGrid.treegrid('load', queryParams);
                    } else {
                        dataGrid.treegrid('reload');
                    }
                }
            }
        }
    }
    return;
}
function bpDataViewExpandAll(mainSelector, elem, dvId) {
    var dataGrid = window['objectdatagrid_' + dvId];
    
    if (typeof dataGrid !== 'undefined' && !dataGrid.hasClass('not-datagrid')) {
        var opt = dataGrid.datagrid('options');
        if (opt.idField) {
            window['dvTreeGridOpenMode_' + dvId] = 'expandAll';
        }
    }
    return;
}
function bpDataViewCollapseAll(mainSelector, elem, dvId) {
    var dataGrid = window['objectdatagrid_' + dvId];
    
    if (typeof dataGrid !== 'undefined' && !dataGrid.hasClass('not-datagrid')) {
        var opt = dataGrid.datagrid('options');
        if (opt.idField) {
            window['dvTreeGridOpenMode_' + dvId] = 'collapseAll';
        }
    }
    return;
}
function setBpRowParamFocus(mainSelector, elem, fieldPath) {
    
    setTimeout(function() {
        var $this = $(elem, mainSelector);
        var $oneLevelRow = $this.closest('.bp-detail-row');
        if ($oneLevelRow.find("input[data-path='" + fieldPath + "']").length) {
            $oneLevelRow.find("input[data-path='" + fieldPath + "']").focus().select();
        } else {
            if (mainSelector.find("input[data-path='" + fieldPath + "']").length) {
                mainSelector.find("input[data-path='" + fieldPath + "']").focus().select();
            } else if (mainSelector.find("textarea[data-path='" + fieldPath + "']").length) {
                mainSelector.find("textarea[data-path='" + fieldPath + "']").focus();
            }
        }
    }, 1);
    
    return;
}
function bpCallProcessFromAnotherServer(mainSelector, elem, metaDataId) {
    if (metaDataId) {
        $.ajax({
            type: 'post',
            url: 'mdprocess/callProcessFromAnotherServer',
            data: {metaDataId: metaDataId},
            dataType: 'json',
            beforeSend: function() {
                Core.blockUI({message: 'Loading...', boxed: true});
            },
            success: function(data) {
                
                PNotify.removeAll();
                
                if (data.hasOwnProperty('Html')) {
                    
                    var $dialogName = 'dialog-anotherbprocess-' + metaDataId;
                    if (!$('#' + $dialogName).length) {
                        $('<div id="' + $dialogName + '" class="display-none"></div>').appendTo('body');
                    } else {
                        $('#' + $dialogName).dialogExtend('restore');
                        Core.unblockUI();
                        return;
                    }
                    var $dialog = $('#' + $dialogName);
                    var bpAlertMsg = ['<div class="alert alert-info p-2">'];
                    
                    bpAlertMsg.push('Уг процесс эндээс бүрэн ажиллахгүй тул та дараах хаяг руу орж үзнэ үү. ');
                    bpAlertMsg.push('<a href="'+data.serverUrl+'" target="_blank">'+data.serverUrl+'</a> ');
                    bpAlertMsg.push('Процесс код: '+data.processCode+'</div>');

                    $dialog.empty().append(bpAlertMsg.join('') + data.Html);

                    $dialog.dialog({
                        cache: false,
                        resizable: true,
                        bgiframe: true,
                        autoOpen: false,
                        title: data.Title,
                        width: data.dialogWidth,
                        height: data.dialogHeight,
                        modal: true,
                        closeOnEscape: isCloseOnEscape,
                        open: function() {
                            enableScrolling();
                        },
                        close: function() {
                            $dialog.empty().dialog('destroy').remove();
                        },
                        buttons: [{
                            text: plang.get('close_btn'),
                            class: 'btn blue-madison btn-sm bp-btn-close',
                            click: function() {
                                $dialog.dialog('close');
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
                    if (data.dialogSize === 'fullscreen') {
                        $dialog.dialogExtend('maximize');
                    }
                    $dialog.dialog('open');

                    $dialog.bind('dialogextendminimize', function() {
                        $dialog.closest('.ui-dialog').nextAll('.ui-widget-overlay:first').addClass('display-none');
                    });
                    $dialog.bind('dialogextendmaximize', function() {
                        $dialog.closest('.ui-dialog').nextAll('.ui-widget-overlay:first').removeClass('display-none');
                    });
                    $dialog.bind('dialogextendrestore', function() {
                        $dialog.closest('.ui-dialog').nextAll('.ui-widget-overlay:first').removeClass('display-none');
                    });

                    Core.initBPAjax($dialog);
                    
                } else {
                    new PNotify({
                        title: data.status,
                        text: data.message,
                        type: data.status,
                        addclass: pnotifyPosition,
                        sticker: false
                    });
                }
                
                Core.unblockUI();
            },
            error: function() { alert('Error'); }
        });
    }
    
    return;
}
function bpSetDetailMergeCount(mainSelector, elem, groupPath, colIndex, colCount) {
    var $dtl = mainSelector.find("[data-table-path='"+groupPath+"']");
    
    if ($dtl.length) {
        var $col = $dtl.find('> thead > tr > th[data-mergegroup-name="1"]:eq('+(colIndex - 1)+')');
        if ($col.length) {
            $col.attr('colspan', colCount);
        }
    }
    return;
}
function bpSetDetailMergeVisibler(mainSelector, elem, groupPath, colIndex, hideShow) {
    var $dtl = mainSelector.find("[data-table-path='"+groupPath+"']");
    
    if ($dtl.length) {
        var $col = $dtl.find('> thead > tr > th[data-mergegroup-name="1"]:eq('+(colIndex - 1)+')');
        if ($col.length) {
            if (hideShow == 'hide') {
                $col.hide();
            } else {
                $col.show();
            }
        }
    }
    return;
}
function bpFileRowsToProcessTab(mainSelector, elem, uniqId, tabName, groupPath, maps) {
    var $dtl = mainSelector.find('[data-table-path="'+groupPath+'"] > .tbody > .bp-detail-row');
    
    if ($dtl.length && tabName) {
        
        tabName = tabName.toLowerCase();
        var $tab = mainSelector.find("a[data-addon-type='"+tabName+"']");
        
        if ($tab.length) {
            if (tabName == 'file') {
                uniqId = uniqId.replace('_', '');
                var $tabContent = mainSelector.find('#bp_file_tab_' + uniqId);
                
                if ($tabContent.children().length) {
                    console.log('already tab content!');
                } else {
                    var onClick = $tab.attr('onclick');
                    
                    if (onClick.indexOf('renderAddModeBpTab(') !== -1) {
                        $.ajax({
                            type: 'post',
                            url: 'mdwebservice/renderAddModeBpFileTab',
                            data: {uniqId: uniqId},
                            beforeSend: function() {
                                if (!$("link[href='assets/custom/addon/plugins/jquery-file-upload/css/jquery.fileupload.css']").length) {
                                    $("head").prepend('<link rel="stylesheet" type="text/css" href="assets/custom/addon/plugins/jquery-file-upload/css/jquery.fileupload.css"/>');
                                }
                            },
                            success: function(data) {
                                bpFileRowsToProcessTabAppend($tabContent, data, maps, $dtl, $tab);
                            },
                            error: function() { console.log('Error: fileRowsToProcessTab'); }
                        });
                        
                    } else {
                        
                        onClick = onClick.replace('renderEditModeBpTab(', '');
                        var onClickArr = onClick.split(',');
                        var refStructureId = (onClickArr[1]).replace(/'/g, '').trim();
                        var sourceId = (onClickArr[2]).replace(/'/g, '').trim();
                        
                        $.ajax({
                            type: 'post',
                            url: 'mdwebservice/renderEditModeBpFileTab',
                            data: {uniqId: uniqId, refStructureId: refStructureId, sourceId: sourceId, actionType: $tab.attr('data-actiontype')},
                            beforeSend: function() {
                                if (!$("link[href='assets/custom/addon/plugins/jquery-file-upload/css/jquery.fileupload.css']").length) {
                                    $("head").prepend('<link rel="stylesheet" type="text/css" href="assets/custom/addon/plugins/jquery-file-upload/css/jquery.fileupload.css"/>');
                                }
                            },
                            success: function(data) {
                                bpFileRowsToProcessTabAppend($tabContent, data, maps, $dtl, $tab);
                            },
                            error: function() { console.log('Error: fileRowsToProcessTab'); }
                        });
                    }
                }
            }
        }
    }
    return;
}
function bpFileRowsToProcessTabAppend($tabContent, data, maps, $dtl, $tab) {
    $tabContent.append(data).promise().done(function() {
                                    
        var mapsArr = maps.split('|'), mapKey = {};

        for (var i = 0; i < mapsArr.length; i++) {
            var fieldPathArr = mapsArr[i].split('@');
            var fieldPath = fieldPathArr[0].trim();
            var inputPath = fieldPathArr[1].trim().toLowerCase();

            mapKey[inputPath] = fieldPath;
        }

        var $fileWrap = $tabContent.find('.list-view-file-new'), fileCount = 0;

        $dtl.each(function() {
            var $row = $(this), $physicalPath = $row.find('[data-path="'+mapKey['physicalpath']+'"]');

            if ($physicalPath.length) {

                if ($physicalPath.is(':file')) {
                    $physicalPath = $physicalPath.next('input');
                }

                if ($physicalPath.val() != '' && UrlExists($physicalPath.val())) {

                    var fileName = $row.find('[data-path="'+mapKey['filename']+'"]').length ? $row.find('[data-path="'+mapKey['filename']+'"]').val() : '';
                    var fileSize = $row.find('[data-path="'+mapKey['filesize']+'"]').length ? $row.find('[data-path="'+mapKey['filesize']+'"]').val() : '';
                    var fileExt = $physicalPath.val().split(/[#?]/)[0].split('.').pop().trim().toLowerCase();
                    
                    if (fileExt == 'jpg' || fileExt == 'jpeg' || fileExt == 'png' || fileExt == 'gif' || fileExt == 'bmp') {
                        var fileLink = '<a href="'+$physicalPath.val()+'" class="fancybox-img">'+
                            '<img src="'+$physicalPath.val()+'">'+
                        '</a>';
                    } else {
                        var fileLink = '<a href="'+$physicalPath.val()+'" target="_blank">'+
                            '<img src="assets/core/global/img/filetype/64/'+fileExt+'.png">'+
                        '</a>';
                    }

                    $fileWrap.append('<li class="meta">'+
                        '<figure class="directory">'+
                            '<div class="img-precontainer">'+
                                '<div class="img-container directory">'+fileLink+'</div>'+
                            '</div>'+
                            '<div class="box">'+
                                '<h4 class="ellipsis">'+
                                    '<input type="text" name="bp_copy_file_name[]" value="'+fileName+'" class="form-control col-md-12 bp_file_name" placeholder="Тайлбар">'+
                                    '<input type="hidden" name="bp_copy_physical_path[]" value="'+$physicalPath.val()+'">'+
                                    '<input type="hidden" name="bp_copy_file_size[]" value="'+fileSize+'">'+
                                    '<input type="hidden" name="bp_copy_file_extension[]" value="'+fileExt+'">'+
                                '</h4>'+
                            '</div>'+
                        '</figure>'+
                        '<a href="javascript:;" class="btn red btn-xs file-remove-row" title="'+plang.get('delete_btn')+'"><i class="icon-cross3"></i></a>'+
                    '</li>');

                    fileCount++;
                }
            }
        });

        if (fileCount > 0) {
            if ($tab.find('[data-file-count]').length) {
                $tab.find('[data-file-count]').text('('+(Number($tab.find('[data-file-count]').attr('data-file-count')) + fileCount)+')');
            } else {
                $tab.append(' <span data-file-count="'+fileCount+'">('+fileCount+')</span>');
            }
        }

        Core.initFancybox($tabContent);
    });
}
function UrlExists(url) {
    var http = new XMLHttpRequest();
    http.open('HEAD', url, false);
    http.send();
    return http.status!=404;
}
function bpCallAddMeta(mainSelector, elem, code, name, typeIds, folderId, callbackFncName) {
    $.ajax({
        type: 'post',
        url: 'mdmetadata/addMetaBySystem',
        data: {metaCode: code, metaName: name, typeIds: typeIds, folderId: folderId, isDialog: 'true'},
        beforeSend: function() {
            Core.blockUI({message: 'Loading...', boxed: true});
        },
        success: function(dataHtml) {
            
            var $dialogName = 'dialog-create-meta';
            if (!$("#" + $dialogName).length) {
                $('<div id="' + $dialogName + '"></div>').appendTo('body');
            }
            var $dialog = $('#' + $dialogName);
            
            $dialog.empty().append(dataHtml); 
            Core.initAjax($dialog);
            
            $dialog.dialog({
                cache: false,
                resizable: true,
                bgiframe: true,
                autoOpen: false,
                title: plang.get('metadata_add'),
                width: 1200,
                minWidth: 1200,
                height: 'auto',
                modal: false,
                open: function() {
                    Core.unblockUI();
                },
                close: function() {
                    $dialog.empty().dialog('destroy').remove();
                },
                buttons: [
                    {text: plang.get('save_btn'), class: 'btn btn-sm green bp-btn-save', click: function() {
                            
                        var $form = $('#addMetaSystemForm');
                        $form.validate({ errorPlacement: function() {} });
                        
                        if ($form.valid()) {
                            $form.ajaxSubmit({
                                type: 'post',
                                url: 'mdmetadata/createMetaSystemModuleForm',
                                dataType: 'json',
                                beforeSend: function() {
                                    Core.blockUI({message: plang.get('msg_saving_block'), boxed: true});
                                },
                                success: function(data) {
                                    PNotify.removeAll();
                                    new PNotify({
                                        title: data.status,
                                        text: data.message,
                                        type: data.status,
                                        sticker: false
                                    });
                    
                                    if (data.status === 'success') {
                                        $dialog.dialog('close');
                                        
                                        if (typeof callbackFncName !== 'undefined' && callbackFncName) {
                                            var bpUniqId = mainSelector.attr('data-bp-uniq-id');
                                            if (typeof window[callbackFncName + '_' + bpUniqId] === 'function') {
                                                window[callbackFncName + '_' + bpUniqId](data.metaDataId);
                                            }
                                        }
                                    }
                                    
                                    Core.unblockUI();
                                }
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
                }
            });
            $dialog.dialog('open');
            $dialog.dialogExtend('maximize');
        }
    });
}
function bpFieldToDetailToolbar(mainSelector, elem, groupPath, fieldPath) {
    var $dtl = mainSelector.find('[data-section-path="'+groupPath+'"]');
    if ($dtl.length) {
        var $field = mainSelector.find('[data-path="'+fieldPath+'"]');
        if ($field.length) {
            var $toolbar = $dtl.find('.table-toolbar > .row > .col');
            if ($toolbar.length && $toolbar.find('[data-path="'+fieldPath+'"]').length == 0) {  
                
                if ($field.prop('tagName') == 'BUTTON') {
                    var $fieldClone = $field.clone(true);
                    var $bpHeaderTbl = $field.closest('.bp-header-param');
                    $toolbar.append($fieldClone);
                    
                    if ($bpHeaderTbl.length) {
                        $field.closest('tr').remove();
                        if ($bpHeaderTbl.find('tbody > tr').length == 0) {
                            if ($bpHeaderTbl.parent('.bp-header-param').length) {
                                $bpHeaderTbl.parent('.bp-header-param').remove();
                            } else {
                                $bpHeaderTbl.remove();
                            }
                        }
                    }
                }
            }
        }
    }
    return;
}
function bpGetBase64FromFile(mainSelector, elem, fieldPath, isWithExtension) {
    
    var $bpElem = getBpElement(mainSelector, elem, fieldPath);
    var fieldValue = null;
    
    if ($bpElem && ($bpElem.hasClass('base64Init') || $bpElem.hasClass('fileInit'))) {
        var fileUrl = $bpElem.val();
        if (fileUrl) {
            var ext = fileUrl.substring(fileUrl.lastIndexOf('.') + 1).toLowerCase();
            var formData = new FormData();
            
            formData.append('file_1', $bpElem.get(0).files[0]); 
            $.ajax({
                type: 'post',
                url: 'api/getBase64FromFile',
                data: formData,
                processData: false,
                contentType: false,
                async: false,
                success: function(data) {
                    if (typeof isWithExtension !== 'undefined' && isWithExtension) {
                        fieldValue = ext + '♠' + data;
                    } else {
                        fieldValue = data;
                    }
                }
            });
        }
    }
    
    return fieldValue;
}
function bpKpiChangeColumnName(mainSelector, elem, colName, changeName) {
    var $getColumnHead = mainSelector.find("th[data-col-name='"+colName+"']");
    if ($getColumnHead.length) {
        $getColumnHead.text(plang.get(changeName));
    }
    return;
}
function bpKpiTemplateVisibler(mainSelector, elem, templateId, mode) {
    var $bpForm = mainSelector.find('form');
    if ($bpForm.length == 0) {
        $bpForm = mainSelector.closest('form');
    }
    
    if ($bpForm.length) {
        var $tmpId = $bpForm.find('input[data-kpiheader-input="kpiTemplateId"][value="'+templateId+'"]');
        if ($tmpId.length) {
            var $kpiFormSection = $tmpId.closest('.kpiFormSection');
            if (mode == 'hide') {
                $kpiFormSection.css({'display': 'none'});
            } else {
                $kpiFormSection.css({'display': ''});
            }
        }
    }
    
    return;
}
function bpWorkspaceMenuReload(mainSelector, elem) {
    var $workSpacePart = mainSelector.closest('.workspace-part');
    
    if ($workSpacePart.length) {
        var menuId = $workSpacePart.attr('data-menu-id');
        var $wsArea = $workSpacePart.closest('.ws-area');
        var $wsMenu = $wsArea.find('.ws-menu').find('[data-menu-id="'+menuId+'"]');
        if ($wsMenu.length) {
            $workSpacePart.remove();
            $wsMenu.click();
        }
    }
    
    return;
}
function bpKpiIndicatorCellMerge(mainSelector) {
    var $tbody = mainSelector.find('.kpi-dtl-table > tbody');
    if ($tbody.length) {
        $tbody.find('.kpi-lbl-cell').attr('data-merge-cell', 'true');
        $tbody.TableSpan('verticalstatement');
    }
    return;
}
function bpKpiIndicatorCellMergeByColName(mainSelector, colNames) {
    var $tbody = mainSelector.find('.kpi-dtl-table > tbody');
    if ($tbody.length && colNames != '') {
        var colNamesArr = colNames.toLowerCase().trim().split(',');
        var len = colNamesArr.length, i = 0;
        var $columns = $();
        
        for (i; i < len; i++) { 
            var colName = (colNamesArr[i]).trim();
            if (colName == 'indicator') {
                $columns = $columns.add($tbody.find('.kpi-indicator-cell'));
            } else {
                $columns = $columns.add($tbody.find('[data-colcode="'+colName+'"]'));
            }
        }
        
        if ($columns.length) {
            $columns.attr('data-merge-cell', 'true');
            $tbody.TableSpan('verticalstatement');
        }
    }
    return;
}
function bpMessageByExp(opts) {
    if (typeof opts.hasOwnProperty('isconsole') != 'undefined' && opts.isconsole && opts.status != 'success') {
        bpResultConsole(opts.status, opts.message);
    }
    
    var notifOpts = {
        title: opts.status, 
        text: opts.message, 
        type: opts.status, 
        sticker: false, 
        addclass: pnotifyPosition
    };
    
    if (typeof opts.hasOwnProperty('delay') != 'undefined' && opts.delay) {
        notifOpts.delay = opts.delay;
    }
    
    PNotify.removeAll(); 
    new PNotify(notifOpts);
    return;
}
function bpChangeSectionTitle(mainSelector, elem, sectionCode, title) {
    var $section = mainSelector.find('[data-section-code="'+sectionCode+'"]');
    if ($section.length) {
        var $parent = $section.closest('.bl-section');
        var $title = $parent.find('.card-header .card-title');
        if ($title.length) {
            $title.html(title);
        }
    }
    return;
}
function convertColorNameToHex(color) {
    var colours = {
        "aliceblue":"#f0f8ff", "antiquewhite":"#faebd7", "aqua":"#00ffff", "aquamarine":"#7fffd4", "azure":"#f0ffff", "beige":"#f5f5dc", "bisque":"#ffe4c4", "black":"#000000", "blanchedalmond":"#ffebcd", "blue":"#0000ff", "blueviolet":"#8a2be2", "brown":"#a52a2a", "burlywood":"#deb887", "cadetblue":"#5f9ea0", "chartreuse":"#7fff00", "chocolate":"#d2691e", "coral":"#ff7f50", "cornflowerblue":"#6495ed", "cornsilk":"#fff8dc", "crimson":"#dc143c", "cyan":"#00ffff", "darkblue":"#00008b", "darkcyan":"#008b8b", "darkgoldenrod":"#b8860b", "darkgray":"#a9a9a9", "darkgreen":"#006400", "darkkhaki":"#bdb76b", "darkmagenta":"#8b008b", "darkolivegreen":"#556b2f", "darkorange":"#ff8c00", "darkorchid":"#9932cc", "darkred":"#8b0000", "darksalmon":"#e9967a", "darkseagreen":"#8fbc8f", "darkslateblue":"#483d8b", "darkslategray":"#2f4f4f", "darkturquoise":"#00ced1", "darkviolet":"#9400d3", "deeppink":"#ff1493", "deepskyblue":"#00bfff", "dimgray":"#696969", "dodgerblue":"#1e90ff", "firebrick":"#b22222", "floralwhite":"#fffaf0", "forestgreen":"#228b22", "fuchsia":"#ff00ff",  "gainsboro":"#dcdcdc", "ghostwhite":"#f8f8ff", "gold":"#ffd700", "goldenrod":"#daa520", "gray":"#808080", "green":"#008000", "greenyellow":"#adff2f",
        "honeydew":"#f0fff0", "hotpink":"#ff69b4", "indianred ":"#cd5c5c", "indigo":"#4b0082", "ivory":"#fffff0", "khaki":"#f0e68c", "lavender":"#e6e6fa", "lavenderblush":"#fff0f5", "lawngreen":"#7cfc00", "lemonchiffon":"#fffacd", "lightblue":"#add8e6", "lightcoral":"#f08080", "lightcyan":"#e0ffff", "lightgoldenrodyellow":"#fafad2", "lightgrey":"#d3d3d3", "lightgreen":"#90ee90", "lightpink":"#ffb6c1", "lightsalmon":"#ffa07a", "lightseagreen":"#20b2aa", "lightskyblue":"#87cefa", "lightslategray":"#778899", "lightsteelblue":"#b0c4de", "lightyellow":"#ffffe0", "lime":"#00ff00", "limegreen":"#32cd32", "linen":"#faf0e6", "magenta":"#ff00ff", "maroon":"#800000", "mediumaquamarine":"#66cdaa", "mediumblue":"#0000cd", "mediumorchid":"#ba55d3", "mediumpurple":"#9370d8", "mediumseagreen":"#3cb371", "mediumslateblue":"#7b68ee", "mediumspringgreen":"#00fa9a", "mediumturquoise":"#48d1cc", "mediumvioletred":"#c71585", "midnightblue":"#191970", "mintcream":"#f5fffa", "mistyrose":"#ffe4e1", "moccasin":"#ffe4b5", "navajowhite":"#ffdead", "navy":"#000080",  "oldlace":"#fdf5e6", "olive":"#808000", "olivedrab":"#6b8e23", "orange":"#ffa500", "orangered":"#ff4500", "orchid":"#da70d6", "palegoldenrod":"#eee8aa",
        "palegreen":"#98fb98", "paleturquoise":"#afeeee", "palevioletred":"#d87093", "papayawhip":"#ffefd5", "peachpuff":"#ffdab9", "peru":"#cd853f", "pink":"#ffc0cb", "plum":"#dda0dd", "powderblue":"#b0e0e6", "purple":"#800080", "rebeccapurple":"#663399", "red":"#ff0000", "rosybrown":"#bc8f8f", "royalblue":"#4169e1",  "saddlebrown":"#8b4513", "salmon":"#fa8072", "sandybrown":"#f4a460", "seagreen":"#2e8b57", "seashell":"#fff5ee", "sienna":"#a0522d", "silver":"#c0c0c0", "skyblue":"#87ceeb", "slateblue":"#6a5acd", "slategray":"#708090", "snow":"#fffafa", "springgreen":"#00ff7f", "steelblue":"#4682b4", "tan":"#d2b48c", "teal":"#008080", "thistle":"#d8bfd8", "tomato":"#ff6347", "turquoise":"#40e0d0", "violet":"#ee82ee", "wheat":"#f5deb3", "white":"#ffffff", "whitesmoke":"#f5f5f5", "yellow":"#ffff00", "yellowgreen":"#9acd32"
    };

    if (typeof colours[color.toLowerCase()] != 'undefined') {
        return colours[color.toLowerCase()];
    }
    
    return false;
}
function bpSetFlashingField(mainSelector, elem, path, color, repeat) {
    if (path) {
        var paths = path.split(',');
        var convertedColor = convertColorNameToHex(color);
        color = convertedColor ? convertedColor : color;
            
        for (var i = 0; i < paths.length; i++) {
            var fieldPath = paths[i].trim();
            var $bpElem = getBpElement(mainSelector, elem, fieldPath);

            if ($bpElem) {
                var $elem = $bpElem;

                if ($bpElem.hasClass('popupInit')) {
                    $elem = $bpElem.closest('.meta-autocomplete-wrap');
                } else if ($bpElem.hasClass('dateInit')) {
                    $elem = $bpElem.closest('.dateElement');
                }

                $elem.pulsate({
                    color: color, 
                    reach: 9,
                    speed: 600,
                    glow: false, 
                    repeat: repeat
                });    
            }
        }
    }

    return;
}
function bpGetFromListByKeyVal(list, criteria, key) {
    if (list) {
        var criteriaArr = criteria.split('='), 
            criteriaKey = criteriaArr[0].toLowerCase(), 
            criteriaVal = criteriaArr[1];
        
        for (var i in list) {
            var row = list[i];
            if (row.hasOwnProperty(criteriaKey)) {
                if (row[criteriaKey] == criteriaVal && row.hasOwnProperty(key)) {
                    return row[key];
                }
            } else {
                return null;
            }
        }
    }
    
    return null;
}
function bpSetSectionSize(mainSelector, sectionCode, width, height) {
    var $section = mainSelector.find('[data-bl-col="'+sectionCode+'"]');
    if ($section.length) {
        var cssAttr = {};
        
        if (width) {
            cssAttr.width = width;
            cssAttr.minWidth = width;
            cssAttr.maxWidth = width;
        }
        
        if (height) {
            cssAttr.height = height;
        }
        
        if (cssAttr) {
            $section.css(cssAttr);
        }
    }
    return;
}
function bpSetLayoutSidebarWidth(mainSelector, sidebarCode, width) {
    if (width) {
        sidebarCode = Number(sidebarCode);
        var $sidebar = mainSelector.find('.process-layout-sidebar:eq('+(sidebarCode-1)+')');
        if ($sidebar.length) {
            $sidebar.css('width', width);
        }
    }
    return;
}
function bpCheckEqualValMultiLookup(mainSelector, elem, lookupPath, val) {
    if (!val) {
        return false;
    }
    var $bpElem = getBpElement(mainSelector, elem, lookupPath);

    if (typeof $bpElem == 'undefined' || $bpElem == false) {
        return false;
    }
    
    if ($bpElem.prop('tagName') == 'SELECT') {
        var $selectedValue = $bpElem.find('option:selected');
        if ($selectedValue.length) {
            if ($bpElem.is('[multiple]')) {
                var len = $selectedValue.length, i = 0;
                for (i; i < len; i++) { 
                    if ($($selectedValue[i]).val() == val) {
                        return true;
                    }
                }
            } else if ($bpElem.val() == val) {
                return true;
            }
        }
    } else if ($bpElem.prop('tagName') == 'INPUT' && $bpElem.is(':checkbox')) {
        
        var $checkedInputs = $bpElem.filter(function() { return $(this).is(':checked'); });
        
        if ($checkedInputs.length) {
            var len = $checkedInputs.length, i = 0;
            for (i; i < len; i++) { 
                if ($($checkedInputs[i]).val() == val) {
                    return true;
                }
            }
        }
    }
    
    return false;
}
function bpSetEqualValMultiLookup(mainSelector, elem, lookupPath, val, checkMode) {

    var $bpElem = getBpElement(mainSelector, elem, lookupPath);

    if (typeof $bpElem == 'undefined' || $bpElem == false) {
        return false;
    }
    
    if ($bpElem.prop('tagName') == 'SELECT') {
        var $selectedValue = $bpElem.find('option:selected');
        if ($selectedValue.length) {
            if ($bpElem.is('[multiple]')) {
                var len = $selectedValue.length, i = 0;
                for (i; i < len; i++) { 
                    if ($($selectedValue[i]).val() == val) {
                        return true;
                    }
                }
            } else if ($bpElem.val() == val) {
                return true;
            }
        }
    } else if ($bpElem.prop('tagName') == 'INPUT' && $bpElem.is(':checkbox')) {
        
        if ($bpElem.length) {
            var len = $bpElem.length, i = 0;
            for (i; i < len; i++) { 
                var $checkbox = $($bpElem[i]);
                if ($checkbox.val() == val) {
                    if (checkMode == 'check') {
                        $checkbox.prop('checked', true);
                    } else {
                        $checkbox.prop('checked', false);
                    }
                    $.uniform.update($checkbox);
                    return true;
                }
            }
        }
    }
    
    return false;
}
function bpGetLifeCycleColumnVal(mainSelector, elem, field) {
    var $tree = $('.lifecycle-common-div.jstree');
    if ($tree.length && field) {
        var nodeId = $tree.jstree('get_selected');
        var nodeData = $tree.jstree(true).get_node(nodeId);
        if (nodeData.hasOwnProperty('data')) {
            var rowData = nodeData.data, field = field.toLowerCase();
            if (rowData.hasOwnProperty(field)) {
                return rowData[field];
            }
        }
    }
    return '';
}
function bpNumberToWords(number, currencyCode, langCode) {
    var result = '';
    var lCode = typeof langCode === 'undefined' ? 'mn' : langCode;
    
    $.ajax({
        type: 'post',
        url: 'api/fullexpression',
        data: {expCode: 'numberToWords', number: number, currencyCode: currencyCode, langCode: lCode},
        async: false,
        success: function(data) {
            result = data;
        }
    });
    
    return result;
}
function convertBrToNL(str) {
    var replaceStr = "\n";
    return str.replace(/<\s*\/?br\s*[\/]?>/gi, replaceStr);
}
function convertNlToBr(str) {
    var breakTag = '<br />';
    var replaceStr = '$1'+ breakTag +'$2';
    return (str + '').replace(/([^>\r\n]?)(\r\n|\n\r|\r|\n)/g, replaceStr);
}
function bpGetBasketColumnVal(mainSelector, equalField, equalVal, column) {
    var $dv = $('[data-meta-type="dv"]:visible:eq(0)');
    
    if ($dv.length) {
        
        var dvId = $dv.attr('data-process-id'), selectedRows = window['_selectedRows_' + dvId];
        
        if (typeof selectedRows != 'undefined' && selectedRows.length) {
            
            var firstRow = selectedRows[0], column = column.toLowerCase(), 
                equalField = equalField.toLowerCase();
        
            if (firstRow.hasOwnProperty(column) && firstRow.hasOwnProperty(equalField)) {
                
                for (var s in selectedRows) {
                    if (selectedRows[s][equalField] == equalVal) {
                        return isNumeric(selectedRows[s][column]) ? Number(selectedRows[s][column]) : selectedRows[s][column];
                    }
                }
            }
        }
    }
    
    return '';
}
function bpShowIndicatorTemplate(mainSelector, param) {
    var $bpWindow = mainSelector.find('form');
    
    if (param == 1) {
        
        var processUniqId = mainSelector.attr('data-bp-uniq-id'), qryStr = '';
        
        if (mainSelector.hasClass('bp-view-process')) {
            var $idViewField = mainSelector.find('span[data-view-path="id"]');
            var $indicatorIdViewField = mainSelector.find('span[data-view-path="indicatorId"]');
            if ($idViewField.length && $idViewField.text()) {
                qryStr += '&param[id]='+$idViewField.text();
            }
            if ($indicatorIdViewField.length && $indicatorIdViewField.text()) {
                qryStr += '&param[indicatorId]='+$indicatorIdViewField.text();
            }
        }
                
        $.ajax({
            type: 'post',
            url: 'mdform/kpiIndicatorTemplateRender', 
            data: $bpWindow.find("input:not([name*='.']), select:not([name*='.']), textarea:not([name*='.'])").serialize()+qryStr+'&uniqId='+processUniqId,
            dataType: 'json',
            async: false, 
            success: function (data) {

                if (data.status === 'success') {
                    
                    if ($bpWindow.find('div[data-section-path="kpiDynamicDtl"]').length > 0) {
                        
                        var $renderElement = $bpWindow.find('div[data-section-path="kpiDynamicDtl"]:eq(0)');
                        $renderElement.empty().append(data.html).promise().done(function() {
                            $renderElement.css({'display': 'block', 'width': '100%', 'margin-left': '0', 'margin-right': '0'});
                            bpBlockMessageStop();
                        });
                        
                    } else {
                        bpBlockMessageStop();
                    }
                    
                } else {
                    PNotify.removeAll();
                    new PNotify({
                        title: data.status,
                        text: data.message,
                        type: data.status,
                        addclass: pnotifyPosition,
                        sticker: false
                    });
                    bpBlockMessageStop();
                }
            },
            error: function () { alert("Error"); bpBlockMessageStop(); }
        });
        
    } else if ($bpWindow.find('.kpi-ind-tmplt-section').length > 0) {
        $bpWindow.find('.kpi-ind-tmplt-section').remove();
        bpBlockMessageStop();
    } 
    
    return;
}
function bpClearBasket(mainSelector) {
    var $dv = $('[data-meta-type="dv"]:visible:eq(0)');
    
    if ($dv.length) {
        var dvId = $dv.attr('data-process-id');
        bpClearBasketByDvId(dvId);
    }
    
    return;
}
function bpClearBasketByDvId(dvId) {
    
    var selectedRows = window['_selectedRows_' + dvId];
    
    if (typeof selectedRows != 'undefined' && selectedRows.length) {
            
        window['_selectedRows_' + dvId] = [];
        $('.save-database-' + dvId).text('0');
        $('.div-objectdatagrid-' + dvId).find('.addedbasket, .addbasket-opened').removeClass('addedbasket addbasket-opened');

        var $dialog = $('#dataViewBasket-dialog-' + dvId);

        if ($dialog.length) {
            $dialog.dialog('close');
        }
    }
    
    return;
}
function bpPanelSelectedRowRemoveBoldStyle(mainSelector) {
    var $panelTypeDv = $('.pf-paneltype-dataview:visible:eq(0)');
    
    if ($panelTypeDv.length) {
        var $row = $panelTypeDv.find('#dv-twocol-second-list .dv-twocol-f-selected .p-row-title');
        if ($row.length) {
            var html = $row.html();
            html = html.replace(/<\/?b>/g, '');
            html = html.replace(/<\/?strong>/g, '');
            $row.html(html);
        }
    }
    return;
}
function bpGetLookupFieldValueCheckVal(mainSelector, elem, lookupPath, column, checkPath, checkVal) {

    var $bpElem = getBpElement(mainSelector, elem, lookupPath);

    if (typeof $bpElem == 'undefined' || $bpElem == false) {
        return '';
    }
    
    if ($bpElem.prop('tagName') == 'SELECT') {
        
        var $selectedValue = $bpElem.find('option:selected');
        
        if ($selectedValue.length) {
            
            checkPath = checkPath.toLowerCase();
            var len = $selectedValue.length, i = 0;
            
            for (i; i < len; i++) { 
                var rowData = $($selectedValue[i]).data('row-data');
                if (rowData.hasOwnProperty(checkPath) && rowData[checkPath] == checkVal) {
                    return rowData.hasOwnProperty(column) ? rowData[column] : '';
                }
            }
        }
        
    } else if ($bpElem.prop('tagName') == 'INPUT') {
        
        if ($bpElem.is(':checkbox')) {
            var $checkedInputs = $bpElem.filter(function() { return $(this).is(':checked'); });

            if ($checkedInputs.length) {
                var len = $checkedInputs.length, i = 0;
                for (i; i < len; i++) { 
                    if ($($checkedInputs[i]).val() == checkVal) {
                        return true;
                    }
                }
            }
            
        } else if ($bpElem.hasClass('popupInit') || $bpElem.hasClass('combogridInit')) {
            
            checkPath = checkPath.toLowerCase();
            var len = $bpElem.length, i = 0;
            
            for (i; i < len; i++) { 
                var rowData = $($bpElem[i]).data('row-data');
                if (rowData.hasOwnProperty(checkPath) && rowData[checkPath] == checkVal) {
                    return rowData.hasOwnProperty(column) ? rowData[column] : '';
                }
            }
        } 
    }
    
    return '';
}
function bpSetTabLabelWidth(mainSelector, setWidth, tabIndex) {
    
    if (typeof setWidth != 'undefined' && typeof tabIndex != 'undefined') {
        
        //end tabindex r label width set hiine
        
    } else {
        var $controlLabels = mainSelector.find('.bp-tabs > .tab-content > .tab-pane table.bp-header-param > tbody > tr > td.text-right');
    
        if ($controlLabels.length) {
            if (isNumber(setWidth) || isNumeric(setWidth)) {
                setWidth = setWidth + '%';
            }
            $controlLabels.css('width', setWidth);
        }
    }
    
    return;
}
function bpSetHeaderFieldStyle(mainSelector, field, styles) {
    var $cell = mainSelector.find('td[data-cell-path="'+field+'"]:eq(0)');
    if ($cell.length) {
        $cell.attr('style', styles);
    }
    return;
}
function bpSendToMetaFromDetail(mainSelector, elem, fieldPath) {
    var $dtls = mainSelector.find('[data-path="'+fieldPath+'"]');
    
    if ($dtls.length) {
        
        var metaId = [];

        $dtls.each(function() {
            if ($(this).val() != '') {
                metaId.push($(this).val());
            }
        });
        
        $.ajax({
            type: 'post',
            url: 'mdupgrade/sendToMetaByIds',
            data: {metaId: metaId},
            success: function(data){
                console.log(data);
            }
        });
    }
    
    return;
}
function bpGetValueEncryption(val) {
    var result = '';
    
    $.ajax({
        type: 'post',
        url: 'mdconfig/valueEncryption',
        data: {valueEncryption: val},
        async: false,
        success: function(data) {
            result = data;
        }
    });
    
    return result;
}
function bpGetExternalIpAddress() {
    var result = '';
    
    $.ajax({
        type: 'get',
        url: 'https://ipecho.net/plain',
        async: false,
        success: function(data) {
            result = data;
        }
    });
    
    return result;
}
function bpSetWordEditorFilePath(mainSelector, elem, fieldPath, filePath) {
    var $iframe = mainSelector.find('iframe[data-view-path="'+fieldPath+'"]');
    
    if ($iframe.length) {
        
        Core.unblockUI();
        bpBlockMessageStart('Loading...');
        
        var newFilePath = filePath.replace('storage/', '');
        var WORD_EDITOR_FOLDER_MODE = getConfigValue('WORD_EDITOR_FOLDER_MODE');
        
        if (WORD_EDITOR_FOLDER_MODE == null || WORD_EDITOR_FOLDER_MODE == '') {
            WORD_EDITOR_FOLDER_MODE = 'storagedev';
        }
        
        var buildUrl = $iframe.attr('data-root-url') + 'DocEdit.aspx?folder='+WORD_EDITOR_FOLDER_MODE+'&file=' + newFilePath;
        
        $iframe.attr('src', buildUrl);
        $iframe.on('load', function () {
            bpBlockMessageStop();
        });
    }
    
    return;
}
function bpSetWordEditorReadOnly(mainSelector, elem, fieldPath, isReadOnly) {
    var $iframe = mainSelector.find('iframe[data-view-path="'+fieldPath+'"]');
    
    if ($iframe.length) {
        
        Core.unblockUI();
        bpBlockMessageStart('Loading...');
        
        var srcUrl = $iframe.attr('src');
        
        $iframe.attr('data-default-url', srcUrl);
        
        if (isReadOnly) {
            
            var srcUrlArr = srcUrl.split('?');
            var srcPathUrl = srcUrlArr[0];
            var srcPath = srcUrlArr[1];
            var srcUrlObj = qryStrToObj(srcPath);
            var fileUrl = srcUrlObj.file;
            
            srcUrl = srcPathUrl + '?url=' + URL_APP + 'storage/' + fileUrl + '&showRb=0';
            
        } else {
            
            var defaultUrl = $iframe.attr('data-default-url');
            
            srcUrl = defaultUrl;
            srcUrl = srcUrl.replace('&showRb=0', '').replace('showRb=0&', '');
        }
        
        $iframe.attr('src', srcUrl);
        $iframe.on('load', function () {
            bpBlockMessageStop();
        });
    }
    
    return;
}
function bpDocToPdfByDotNet(mainSelector, elem, docPath) {
    var result = '';
    $.ajax({
        type: 'post',
        url: 'mddoc/docToPdfByDotNet',
        data: {docPath: docPath},
        dataType: 'json',
        async: false,
        success: function(data) {
            if (data.status == 'success') {
                result = data.filePath;
            }
        }
    });
    return result;
}

function bpClone(obj) {
    var copy;

    if (null == obj || "object" != typeof obj) return obj;

    if (obj instanceof Date) {
        copy = new Date();
        copy.setTime(obj.getTime());
        return copy;
    }

    if (obj instanceof Array) {
        copy = [];
        for (var i = 0, len = obj.length; i < len; i++) {
            copy[i] = bpClone(obj[i]);
        }
        return copy;
    }

    if (obj instanceof Object) {
        copy = {};
        for (var attr in obj) {
            if (obj.hasOwnProperty(attr)) copy[attr] = bpClone(obj[attr]);
        }
        return copy;
    }

    throw new Error("Unable to copy obj! Its type isn't supported.");
}

function getDateOfWeeks(numOfWeeks, weekday, tense, fromDate) { 

    var targetWeekday = -1;  
    var dateAdjustment = bpClone(fromDate);  
    var result = bpClone(fromDate);

    switch (weekday) {
    case "Monday": targetWeekday = 8; break;
    case "Tuesday": targetWeekday = 2; break;
    case "Wednesday": targetWeekday = 3; break;
    case "Thursday": targetWeekday = 4; break;
    case "Friday": targetWeekday = 5; break;
    case "Saturday": targetWeekday = 6; break;
    case "Sunday": targetWeekday = 7;
    }

    var adjustment = 7 * (numOfWeeks - 1);
    if (tense == "after") adjustment = -7 * numOfWeeks;

    dateAdjustment.setDate(fromDate.getDate() - targetWeekday);
    var weekday = dateAdjustment.getDay();

    result.setDate(fromDate.getDate() - weekday - adjustment);
    result.setHours(0,0,0,0);
    return result;
}

function GetCurrentDateTime(str) {
    var syntax = (typeof str !== 'undefined') ? str : '/';
    var today = new Date();
    var dd = today.getDate();
    var MM = today.getMonth() + 1; //January is 0!
    var yyyy = (typeof str !== 'undefined') ? today.getFullYear() : today.getFullYear;
    var HH = today.getHours();
    var mm = today.getMinutes();
    var ss = today.getSeconds();
  
    if (dd < 10) {
      dd = "0" + dd;
    }
    if (MM < 10) {
      MM = "0" + MM;
    }
    if (HH < 10) {
      HH = "0" + HH;
    }
    if (mm < 10) {
      mm = "0" + mm;
    }
    if (ss < 10) {
      ss = "0" + ss;
    }
  
    var datetime = yyyy + syntax + MM + syntax + dd + " " + HH + ":" + mm + ":" + ss;
    return datetime;
}

function bpBankIpTerminalTransfer(mainSelector, elem, amount, terminalId, deviceType, callbackFncName) {
    bankIpTerminalTransfer(amount, terminalId, deviceType, function(response) {
        window[callbackFncName](response, elem);
    });
}

function tdbPosSale(amount, callback) {
    setTimeout(function () {
        var response = $.ajax({
            type: 'post',
            url: 'http://127.0.0.1:8088/ecrt1000',
            data: {
                amount: amount,
                operation: "Sale"
            },
            dataType: 'json',
            async: false
        });
        var result = response.responseJSON;
        Core.unblockUI();
        var resultIpTerminal = {status:'success'};

        if (result.ecrResult['RespCode'] == 00) {
            resultIpTerminal['rrn'] = result.ecrResult['RRN'];
            resultIpTerminal['pan'] = result.ecrResult['PAN'];
            resultIpTerminal['authcode'] = result.ecrResult['AuthCode'];
            resultIpTerminal['terminalid'] = result.ecrResult['TerminalID'];
            resultIpTerminal['traceno'] = result.ecrResult['TraceNumber'];
            callback(resultIpTerminal);
            return;
        } else {
            callback({status:"error", code:result.ecrResult['RespCode'], text:'TDB terminal: '+result.ecrResult['RespCode']});
            return;
        }        
    }, 100);
}

/**
 * Amount
 * Terminal Id
 * Bank type
 * Callback function
 */
function bankIpTerminalTransfer(amount, terminalId, deviceType, callback) {    
    if ("WebSocket" in window) {
      console.log("WebSocket is supported by your Browser!");
      // Let us open a web socket
      var ws = new WebSocket("ws://localhost:58324/socket");
      var dvctype = '';

      if (deviceType == 'khanbank') {
        dvctype = 'databank';
      } else if (deviceType == 'golomtbank') {
        dvctype = 'glmt';
      } else if (deviceType == 'xacbank') {
          dvctype = 'khas_paxA35';        
          terminalId = '123';
      } else if (deviceType == 'tdbank') {
        dvctype = 'tdb_paxs300';
      }

      if (typeof callback === 'undefined') {
        return {status:'error', text:'Not found callback function!'};
      }

      if (!amount) {
        callback({status:"error", code:'', text:'Дүн хоосон байна!'});
        return;                   
      }

      if (!terminalId) {
        callback({status:"error", code:'', text:'TerminalId хоосон байна!'});
        return;                   
      }

      if (!dvctype) {
        callback({status:"error", code:'', text:'Банкны төрөл хоосон байна!'});
        return;                   
      }

      Core.blockUI({
        message: "МЭДЭЭЛЭЛ ДАМЖУУЛЖ БАЙНА...",
        boxed: true,
      });
      
    if (deviceType == 'tdbank') {
        tdbPosSale(amount, callback);
        return;
    }      

      ws.onopen = function () {
        var currentDateTime = GetCurrentDateTime();
        ws.send('{"command":"bank_terminal_pos_sale", "dateTime":"' + currentDateTime + '", details: [{"key": "devicetype", "value": "' + dvctype + '"},{"key": "terminalid", "value": "' + terminalId + '"},{"key": "totalamount", "value": "' + amount + '"}]}');
      };
      isAcceptPrintPos = false;
  
      ws.onmessage = function (evt) {
        Core.unblockUI();
        var received_msg = evt.data;
        var jsonData = JSON.parse(received_msg);
  
        if (jsonData.status == "success") {
          var getParse = JSON.parse(jsonData.details[0].value);
          var resultIpTerminal = {status:'success'};
  
          if (dvctype === "databank") {
            if (!getParse["response"]) {
                callback({status:"error", code:'', text:'Алдааны мэдээлэл ирээгүй'});
                return;
            }
            if (getParse.status && getParse["response"]["response_code"] == "000") {
                resultIpTerminal['rrn'] = getParse["response"]['rrn'];
                resultIpTerminal['pan'] = getParse["response"]['pan'];
                resultIpTerminal['authcode'] = getParse["response"]['auth_code'];
                resultIpTerminal['terminalid'] = getParse["response"]['terminal_id'];
                resultIpTerminal['traceno'] = getParse["response"]['trace_no'];
                callback(resultIpTerminal);
                return;
            } else {
                callback({status:"error", code:getParse["response"]["response_code"], text:getParse["response"]["response_msg"]});
                return;
            }
          }
  
          if (dvctype === "tdb_paxs300") {
            if (getParse["code"] == "0") {
                resultIpTerminal['rrn'] = getParse["data"]['RRN'];
                resultIpTerminal['pan'] = getParse["data"]['PAN'];
                resultIpTerminal['authcode'] = getParse["data"]['Trace'];
                resultIpTerminal['terminalid'] = getParse["data"]['TerminalID'];
                resultIpTerminal['traceno'] = getParse["data"]['ApprovalCode'];
                callback(resultIpTerminal);
                return;
            } else {
                callback({status:"error", code:getParse["code"], text:'TDB terminal: '+getParse["message"]});
                return;
            }
          }
  
          if (dvctype === "khas_paxA35") {
            if (getParse["Code"] == "0") {
                resultIpTerminal['rrn'] = getParse['RRN'];
                resultIpTerminal['pan'] = getParse['CardNumber'];
                resultIpTerminal['authcode'] = getParse['ApprovalCode'];
                resultIpTerminal['terminalid'] = getParse['Terminal'];
                resultIpTerminal['traceno'] = getParse['ApprovalCode'];
                callback(resultIpTerminal);
                return;
            } else {
                callback({status:"error", code:getParse["Code"], text:'TDB terminal: '+getParse["Description"]});
                return;
            }
          }
  
          if (dvctype === "glmt") {
            resultIpTerminal['rrn'] = getParse['RRN'];
            resultIpTerminal['pan'] = getParse['PAN'];
            resultIpTerminal['authcode'] = getParse['AuthCode'];
            resultIpTerminal['terminalid'] = getParse['TerminalId'];
            resultIpTerminal['traceno'] = "";            
            callback(resultIpTerminal);
          }
        } else {
            callback({status:"error", code:'', text:jsonData.description});
            return;            
        }        
      };
  
      ws.onerror = function (event) {
        var resultJson = {
          Status: "Error",
          Error: event.code,
        };
        console.log(JSON.stringify(resultJson));
      };
  
      ws.onclose = function () {
        console.log("Connection is closed...");
      };
    } else {
      var resultJson = {
        Status: "Error",
        Error: "WebSocket NOT supported by your Browser!",
      };
      console.log(JSON.stringify(resultJson));
    }
}

/**
 * Terminal Id
 * Bank type
 * Callback function
 */
function bankCheckIpTerminal(terminalId, deviceType, callback) {
    if ("WebSocket" in window) {
        var dvctype = '';

        if (deviceType == 'khanbank') {
          dvctype = 'databank';
        } else if (deviceType == 'golomtbank') {
          dvctype = 'glmt';
        } else if (deviceType == 'xacbank') {
          dvctype = 'khas_paxA35';
          terminalId = '123';
          callback({status:"success", text:"IPPOS terminal холболт амжилттай хийгдлээ. [" + deviceType + "]"});
          return;
        } else if (deviceType == 'tdbank') {
          dvctype = 'tdb_paxs300';
          callback({status:"success", text:"IPPOS terminal холболт амжилттай хийгдлээ. [" + deviceType + "]"});
        }
  
        if (typeof callback === 'undefined') {
          return {status:'error', text:'Not found callback function!'};
        }
  
        if (!terminalId) {
          callback({status:"error", text:'TerminalId хоосон байна!'});
          return;                   
        }
  
        if (!dvctype) {
          callback({status:"error", text:'Банкны төрөл хоосон байна!'});
          return;                   
        }

        console.log("WebSocket is supported by your Browser!");
        // Let us open a web socket
        var ws = new WebSocket("ws://localhost:58324/socket");
    
        ws.onopen = function () {
          var currentDateTime = GetCurrentDateTime();
          ws.send('{"command":"bank_terminal_pos_connect", "dateTime":"' + currentDateTime + '", details: [{"key": "devicetype", "value": "' + dvctype + '"},{"key": "terminalid", "value": "' + terminalId + '"}]}');
        };
    
        ws.onmessage = function (evt) {
            Core.unblockUI();
          var received_msg = evt.data;
          var jsonData = JSON.parse(received_msg);
    
          if (jsonData.status == "success") {
            callback({status:"success", text:"IPPOS terminal холболт амжилттай хийгдлээ. [" + deviceType + "]"});
            return;
          } else {
            callback({status:"error", text:"Bank terminal error [" + deviceType + "]: " + jsonData.description});
            return;
          }          
        };
    
        ws.onerror = function (event) {
          var resultJson = {
            Status: "Error",
            Error: event.code,
          };
    
          Core.unblockUI();
          callback({status:"error", text:"Bank terminal error [" + deviceType + "]: Veritech Client асаагүй байна!!!"});
          return;
        };
    
        ws.onclose = function () {
          console.log("Connection is closed...");
        };
      } else {
        var resultJson = {
          Status: "Error",
          Error: "WebSocket NOT supported by your Browser!",
        };
    
        console.log(JSON.stringify(resultJson));
      }    
}
function bankSetlementIpTerminal(terminalId, deviceType, callback) {
    if ("WebSocket" in window) {
        var dvctype = '';

        if (deviceType == 'khanbank') {
          dvctype = 'databank';
        } else if (deviceType == 'golomtbank') {
          dvctype = 'glmt';
        } else if (deviceType == 'xacbank') {
          dvctype = 'khas_paxA35';          
          terminalId = '123';
        } else if (deviceType == 'tdbank') {
          dvctype = 'tdb_paxs300';
        }
  
        if (typeof callback === 'undefined') {
          return {status:'error', text:'Not found callback function!'};
        }
  
        if (!terminalId) {
          callback({status:"error", text:'TerminalId хоосон байна!'});
          return;                   
        }
  
        if (!dvctype) {
          callback({status:"error", text:'Банкны төрөл хоосон байна!'});
          return;                   
        }

        console.log("WebSocket is supported by your Browser!");
        // Let us open a web socket
        var ws = new WebSocket("ws://localhost:58324/socket");
    
        ws.onopen = function () {
          var currentDateTime = GetCurrentDateTime();
          ws.send('{"command":"bank_terminal_pos_settlement", "dateTime":"' + currentDateTime + '", details: [{"key": "devicetype", "value": "' + dvctype + '"},{"key": "terminalid", "value": "' + terminalId + '"}]}');
        };
    
        ws.onmessage = function (evt) {
            Core.unblockUI();
          var received_msg = evt.data;
          var jsonData = JSON.parse(received_msg);
    
          if (jsonData.status == "success") {
            var getParse = JSON.parse(jsonData.details[0].value);
            var $dialogName = 'pos-preview-print-setlement';
            
            if (getParse.ReceiptData == '') {
                PNotify.removeAll();
                new PNotify({
                    title: 'Warning',
                    text: 'Нэгтгэл хийх гүйлгээ байхгүй',
                    type: 'warning', 
                    sticker: false, 
                    addclass: 'pnotify-center'
                });       
                return;
            }
            
            if (!$("#" + $dialogName).length) {
                $('<div id="' + $dialogName + '" class="hidden"></div>').appendTo('body');
            }
            var $dialog = $('#' + $dialogName);
            $dialog.html(getParse.ReceiptData.replace(/(?:\r\n|\r|\n)/g, '<br>')).promise().done(function() {

                $dialog.printThis({
                    debug: false,
                    importCSS: false,
                    printContainer: false,
                    dataCSS: data.css,
                    removeInline: false
                });
            });
            return;
          } else {
            callback({status:"error", text:"Bank terminal error [" + deviceType + "]: " + jsonData.description});
            return;
          }          
        };
    
        ws.onerror = function (event) {
          var resultJson = {
            Status: "Error",
            Error: event.code,
          };
    
          Core.unblockUI();
          callback({status:"error", text:"Bank terminal error [" + deviceType + "]: Veritech Client асаагүй байна!!!"});
          return;
        };
    
        ws.onclose = function () {
          console.log("Connection is closed...");
        };
      } else {
        var resultJson = {
          Status: "Error",
          Error: "WebSocket NOT supported by your Browser!",
        };
    
        console.log(JSON.stringify(resultJson));
      }    
}
function bpRunKpiIndicatorDataMart(mainSelector, elem, indicatorId, isAsync, paramsPath) {
    
    var paramData = {};
    
    if (typeof paramsPath != 'undefined' && paramsPath != '') {
        
        var paramsPathArr = paramsPath.split('|');
        var paramsLength = paramsPathArr.length;

        for (var i = 0; i < paramsLength; i++) {
            var fieldPathArr = paramsPathArr[i].split('@');
            var fieldPath = fieldPathArr[0].trim();
            var inputPath = fieldPathArr[1].trim();
            var fieldValue = '';

            var $bpElem = getBpElement(mainSelector, elem, fieldPath);

            if ($bpElem) {
                if ($bpElem.hasClass('base64Init') || $bpElem.hasClass('fileInit')) {
                    var fileUrl = $bpElem.val();
                    if (fileUrl) {
                        var ext = fileUrl.substring(fileUrl.lastIndexOf('.') + 1).toLowerCase();
                        var formData = new FormData();
                        formData.append('file_1', $bpElem.get(0).files[0]); 
                        $.ajax({
                            type: 'post',
                            url: 'api/getBase64FromFile',
                            data: formData,
                            processData: false,
                            contentType: false,
                            async: false,
                            success: function(data) {
                                fieldValue = ext + '♠' + data;
                            }
                        });
                    }
                } else {
                    fieldValue = getBpRowParamNum(mainSelector, elem, fieldPath);
                }
            } else {
                fieldValue = fieldPath;
            }
            
            paramData[inputPath] = fieldValue;
        }
    }
    
    if (isAsync) {
        
        var response = $.ajax({
            type: 'post',
            url: 'mdform/generateKpiDataMartByPost',
            data: {indicatorId: indicatorId, isFromRunExpression: 1, inputData: paramData},
            dataType: 'json',
            async: false
        });
    
        return response.responseJSON;
        
    } else {
        
        $.ajax({
            type: 'post',
            url: 'mdform/generateKpiDataMartByPost',
            data: {indicatorId: indicatorId, isFromRunExpression: 1, inputData: paramData},
            dataType: 'json',
            success: function(data) {
                console.log('runKpiIndicatorDataMart = ' + indicatorId);
                console.log(data);
            }
        });
    }
}
function bpSetPreviewReportTemplateId(mainSelector, reportTemplateId) {
    setTimeout(function() {
        var $dialog = mainSelector.closest('.ui-dialog');
            
        if ($dialog.length) {
            
            $dialog.find('.bp-btn-preview:eq(0)').attr('data-report-template-id', reportTemplateId);

        } else {

            var $parent = mainSelector.parent(), $id = $parent.attr('id');

            $('#' + $id).bind('dialogopen', function() {
                var $processParent = mainSelector.closest('.ui-dialog');
                $processParent.find('.bp-btn-preview:eq(0)').attr('data-report-template-id', reportTemplateId);
                return;
            });

            mainSelector.find('.bp-btn-preview:eq(0)').attr('data-report-template-id', reportTemplateId);
        }
    }, 10);
    
    return;
}
function bpFindText(string, findText) {
    if (string.search(findText) !== -1) {  
        return true;
    } else {
        return false;
    }
}
function bpSaveProcess(mainSelector) {
    var $parentForm = mainSelector.find('form:eq(0)');
    
    var response = $.ajax({
        type: 'post',
        url: 'mdwebservice/runProcess', 
        data: $parentForm.serialize(), 
        dataType: 'json',
        async: false
    });
    
    var responseData = response.responseJSON;
    $parentForm.find('input[name="windowSessionId"]').val(responseData.uniqId);
    
    return responseData;
}
function bpGetParamAuthenticationUrl(mainSelector, uri, w, h, callbackFnc) {
    
    var dualScreenLeft = window.screenLeft !== undefined ? window.screenLeft : window.screenX;
    var dualScreenTop = window.screenTop !== undefined ? window.screenTop : window.screenY;

    var width = window.innerWidth ? window.innerWidth : document.documentElement.clientWidth ? document.documentElement.clientWidth : screen.width;
    var height = window.innerHeight ? window.innerHeight : document.documentElement.clientHeight ? document.documentElement.clientHeight : screen.height;

    var systemZoom = width / window.screen.availWidth;
    var top = (height - h) / 2 / systemZoom + dualScreenTop;
    var left = (width - w) / 2 / systemZoom + dualScreenLeft;
    
    var win = window.open(uri, 'mw', 'width='+(w / systemZoom)+',height='+h / systemZoom+',top='+top+',left='+left+',toolbar=1,scrollbars=1');
	
    var interval = window.setInterval(function() {
        
        try {
            
            if (typeof win.location.href == 'undefined' || win == null) {
            
                console.log('not href..');

            } else if (window.location.hostname == win.location.hostname) {

                var bpUniqId = mainSelector.attr('data-bp-uniq-id');

                if (typeof window[callbackFnc + '_' + bpUniqId] === 'function') {
                    var qryStr = win.location.search;
                    
                    if (qryStr.substr(0, 1) == '?') {
                        qryStr = qryStr.substr(1);
                    }
                    
                    var qtyObj = qryStrToObj(qryStr);
                    window[callbackFnc + '_' + bpUniqId](qtyObj);
                }

                window.clearInterval(interval);
                win.close();
                
            } else if (win.closed) {
                window.clearInterval(interval);
            }
        
        } catch (e) { }
        
    }, 500);
}
function bpGeneratePassword(len = 8, minUpper = 0, minLower = 0, minNumber = -1, minSpecial = -1) {
    var chars = String.fromCharCode(...Array(127).keys()).slice(33),//chars
        A2Z = String.fromCharCode(...Array(91).keys()).slice(65),//A-Z
        a2z = String.fromCharCode(...Array(123).keys()).slice(97),//a-z
        zero2nine = String.fromCharCode(...Array(58).keys()).slice(48),//0-9
        specials = chars.replace(/\w/g, '');
    if (minSpecial < 0) chars = zero2nine + A2Z + a2z;
    if (minNumber < 0) chars = chars.replace(zero2nine, '');
    var minRequired = minSpecial + minUpper + minLower + minNumber;
    var rs = [].concat(
        Array.from({length: minSpecial ? minSpecial : 0}, () => specials[Math.floor(Math.random() * specials.length)]),
        Array.from({length: minUpper ? minUpper : 0}, () => A2Z[Math.floor(Math.random() * A2Z.length)]),
        Array.from({length: minLower ? minLower : 0}, () => a2z[Math.floor(Math.random() * a2z.length)]),
        Array.from({length: minNumber ? minNumber : 0}, () => zero2nine[Math.floor(Math.random() * zero2nine.length)]),
        Array.from({length: Math.max(len, minRequired) - (minRequired ? minRequired : 0)}, () => chars[Math.floor(Math.random() * chars.length)]),
    );
    return rs.sort(() => Math.random() > Math.random()).join('');
}
function bpCallKpiIndicatorForm(mainSelector, elem, indicatorId, recordId, mode) {
    if (typeof isKpiIndicatorScript === 'undefined') {
        $.getScript('middleware/assets/js/addon/indicator.js').done(function() {
            bpExpCallKpiIndicatorForm(mainSelector, elem, indicatorId, recordId, mode);
        });
    } else {
        bpExpCallKpiIndicatorForm(mainSelector, elem, indicatorId, recordId, mode);
    }
}
function bpGetMetaVerseMethodAction(mainSelector) {
    return mainSelector.find('input[name="kpiActionType"]').val();
}
function codeFormatter(type, sourceXml) {
    if (sourceXml != '' && sourceXml != null) {
        if (type === 'xml') {
            var xmlDoc = new DOMParser().parseFromString(sourceXml, 'application/xml');
            var xsltDoc = new DOMParser().parseFromString([
                '<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform">',
                '  <xsl:strip-space elements="*"/>',
                '  <xsl:template match="para[content-style][not(text())]">',
                '    <xsl:value-of select="normalize-space(.)"/>',
                '  </xsl:template>',
                '  <xsl:template match="node()|@*">',
                '    <xsl:copy><xsl:apply-templates select="node()|@*"/></xsl:copy>',
                '  </xsl:template>',
                '  <xsl:output indent="yes"/>',
                '</xsl:stylesheet>'
            ].join('\n'), 'application/xml');

            var xsltProcessor = new XSLTProcessor();    
            xsltProcessor.importStylesheet(xsltDoc);
            var resultDoc = xsltProcessor.transformToDocument(xmlDoc);
            var resultXml = new XMLSerializer().serializeToString(resultDoc);
            return resultXml;    
        } else if (type === 'json') {
            return JSON.stringify(JSON.parse(sourceXml), null, 2);
        }
    }
    
    return null;
}
function detectCustomerFromRegion(lookupKeyDv, dtlPath, region, elem) {
    if (!region) return;
    
    var $this = $(elem);
    var googleMapDrawStaticDataList = [], customerIds = [];
    var regionRows = JSON.parse(decodeURIComponent(region));
    var $bpWrapper = $this.closest('form').parent();
    var bpUniqId = $bpWrapper.attr('data-bp-uniq-id');
    var bpId = $bpWrapper.attr('data-process-id');
    
    if (window.google && google.maps && currentPolygon && window['kpiMarkerObject']) {
        for (var i = 0; i < window['kpiMarkerObject'].length; i++) {
            if (window['kpiMarkerObject'][i].visible && google.maps.geometry.poly.containsLocation(window['kpiMarkerObject'][i].getPosition(), currentPolygon)) {
                customerIds.push({"customerid":window['kpiMarkerObject'][i]['rowData']['CUSTOMERID']});
            }
        }       
        window['selectedRowsBpAddRow_'+bpUniqId]($("table[data-table-path='" + dtlPath + "']", "div[data-bp-uniq-id='"+bpUniqId+"']"), bpId, dtlPath, lookupKeyDv, customerIds, 'autocomplete');
        return;
    }
}
function refreshCustomerRegion(segmentid, segmentnamepath, segmentnamevalue, segmentcolorvaluepath, segmentcolorvalue) {
    if (segmentid && currentPolygon && currentPolygonIndicatorId) {
        window['kpiMapLayer_' + currentPolygonIndicatorId][segmentid] = currentPolygon;
        currentPolygon.set('fillColor', segmentcolorvalue);
        currentPolygon.set('strokeColor', segmentcolorvalue);
        currentPolygon.setMap(map);        
        
        var rowData = {
            [segmentnamepath]: segmentnamevalue,
            [segmentcolorvaluepath]: segmentcolorvalue,
            'segmentation_id': segmentid
        };
        var $getSegment = $('.indicator-polygon-data').find('div[data-id="'+segmentid+'"]');
        
        if ($getSegment.length) {
            $getSegment.attr('data-rowdata', encodeURIComponent(JSON.stringify(rowData)));
            $getSegment.find('label').text(segmentnamevalue);
            $getSegment.css('border-left-color', segmentcolorvalue);
            $getSegment.find('.edit_polygon_btn').css('color', segmentcolorvalue);
        } else {        
            $('.indicator-polygon-data').append('<div class="mb10 mr-3 cursor-pointer ml1 polygon-row" data-rowdata="'+encodeURIComponent(JSON.stringify(rowData))+'" style="border-left: 4px solid '+segmentcolorvalue+';" data-id="'+segmentid+'">\n\
                <div class="d-flex justify-content-between pt-1">\n\
                    <div class="ml-1"><input type="checkbox" checked id="visible_polygon_btn_'+segmentid+'" class="notuniform visible_polygon_btn"/> <label class="ml-1" for="visible_polygon_btn_'+segmentid+'">'+segmentnamevalue+'</label></div> \n\
                    <i class="edit_polygon_btn fa fa-edit" style="color:'+(segmentcolorvalue ? segmentcolorvalue : '#575757')+'" title="засах"></i>\n\
                </div>\n\
            </div>');
        }
    }
}
function bpSetFieldPrecisionScale(mainSelector, elem, setPath, getPath) {
     
    var $getField = getBpElement(mainSelector, elem, getPath);
    
    if ($getField) {
        var $setField = getBpElement(mainSelector, elem, setPath);
        if ($setField) {
            var precisionScale = '';
            
            if ($getField.hasClass('popupInit')) {
                
                try {
                    if ($getField && typeof $getField.attr('data-row-data') !== 'undefined') {

                        if ($getField.prop('tagName') == 'SELECT' && typeof $getField.find('option:selected').attr('data-row-data') !== 'undefined') {
                            var rowData = $getField.find('option:selected').attr('data-row-data');
                        }  else {
                            var rowData = $getField.attr('data-row-data');
                        }

                        if (rowData !== '') {
                            var column = 'precisionscale';
                            if (typeof rowData !== 'object') {
                                var jsonObj = JSON.parse(html_entity_decode(rowData, "ENT_QUOTES"));
                            } else {
                                var jsonObj = rowData;
                            }

                            if (column in Object(jsonObj)) {
                                precisionScale = jsonObj[column];
                            }
                        }
                    }

                } catch(e) {
                    precisionScale = '';
                } 
                
            } else {
                precisionScale = $setField.val();
            }
            
            if (precisionScale != '' && isNumeric(precisionScale)) {
                
                $setField.attr('data-mdec', precisionScale+'.'+precisionScale);
                $setField.autoNumeric('update', {"mDec": precisionScale});
                $setField.autoNumeric('set', $setField.next("input[type=hidden]").val());
            }
        }
    }
    
    return;
}
function bpPanelSelectedRowRemoveBoldStyle(mainSelector) {
    var $panelTypeDv = $('.pf-paneltype-dataview:visible:eq(0)');
    
    if ($panelTypeDv.length) {
        var $row = $panelTypeDv.find('#dv-twocol-second-list .dv-twocol-f-selected .p-row-title');
        if ($row.length) {
            var html = $row.html();
            html = html.replace(/<\/?b>/g, '');
            html = html.replace(/<\/?strong>/g, '');
            $row.html(html);
        }
    }
    return;
}
function bpSetMetaVerseFieldValue(mainSelector, elem, field, val, $setField) {
    if (mainSelector.hasClass('kpi-ind-tmplt-section')) {
        var $form = mainSelector;
    } else {
        var $form = mainSelector.find('.kpi-ind-tmplt-section');
    }
    
    if ($form.length) {
        if (field.toLowerCase() == 'primaryid') {
            $form.find('input[name="kpiTblId"], input[name="sf[ID]"]').val(val);
        } else {
            var $field = (typeof $setField != 'undefined') ? $setField : $form.find('[data-path="'+field+'"]');
            if ($field.length) { 
                if ($field.hasClass('select2')) {
                    $field.select2('val', val);
                    var $descName = $field.next('input[name*="_DESC]"]');
                    if ($descName.length) {
                        var descName = '';
                        if ($field.val() != '') {
                            if ($field.hasAttr('data-name')) {
                                var $option = $field.find('option:selected');
                                var rowData = $option.data('row-data');

                                if (typeof rowData !== 'object') {
                                    rowData = JSON.parse(html_entity_decode(rowData, 'ENT_QUOTES'));
                                } 

                                descName = rowData[$field.attr('data-name')];
                            } else {
                                descName = $field.find('option:selected').text();
                            }
                        }
                        $descName.val(descName);
                    }
                } else {
                    $field.val(val);
                }
            }
        }
    }
    return;
}
function bpSaveReportTemplateToFile(mainSelector, recordId, fileType, fileName) {
    if (recordId != '' && fileType != '') {
        var fileTypeLower = fileType.toLowerCase();
        if (fileTypeLower == 'pdf' || fileTypeLower == 'doc' || fileTypeLower == 'docx') {
            fileName = (typeof fileName != 'undefined' && fileName != '') ? fileName : '';
            var $parent = mainSelector.find('.report-preview');
            if ($parent.length) {
                var $externalContent = $parent.find('#externalContent');
                if ($externalContent.length) {
                    
                    var divide = Math.ceil(copies / 2);
                    var reportMetaDataId = $parent.find('.report-preview-container').attr('data-report-metadataid');
                    var selectedRow = {id: recordId};

                    $parent.find('div#contentRepeat').empty();
                    
                    if (copies >= 1) {
                        var $page = $parent.find('page'), pageLength = $page.length, divideTag = '';
                        if (pageLength > 1 || divide > 1) {
                            divideTag = '<div style="page-break-after: always;"></div>'; 
                        }
                        $page.each(function() {
                            var $thisPage = $(this);
                            if (pageType == '2col') {
                                $parent.find('#contentRepeat').append($thisPage.find("#exContent").get(0).outerHTML + divideTag);
                            } else {
                                for (var i = 0; i < divide; i++) {
                                    $parent.find('#contentRepeat').append($thisPage.find("#externalContent").get(0).outerHTML + divideTag);
                                }
                            }
                        });
                    }

                    var postData = {
                        content: $parent.find('div#contentRepeat').html(),
                        orientation: pageOrientation,
                        size: pageSize,
                        top: pageRtTop,
                        left: pageRtLeft,
                        bottom: pageRtBottom,
                        right: pageRtRight,
                        wfmStatusId: 'isnull',
                        typeId: 'isnull',
                        params: {recordId: recordId, metaDataId: null, archiveName: fileName, defaultDirectoryId: null, fileType: fileType}, 
                        selectedRow: selectedRow, 
                        reportMetaDataId: reportMetaDataId, 
                        processMetaDataId: mainSelector.attr('data-process-id'), 
                        headerHtml: $parent.find('script[data-template="templateHeader"]').text(),
                        footerHtml: $parent.find('script[data-template="templateFooter"]').text()       
                    };
                    
                    $.ajax({
                        type: 'post',
                        url: 'mdtemplate/saveEcmContentReportTemplateToFile',
                        data: postData,
                        dataType: 'json',
                        async: false, 
                        success: function(data) {
                            console.log(data);
                        }
                    });
                }
            }
        }
    }
    
    return;
}
function bpSetReportTemplateFieldValue(mainSelector, field, value) {
    var $field = mainSelector.find('span[data-reporttemplate-field="'+field+'"]');
    
    if ($field.length) {
        $field.html(value);
    } else {
        var $reportContainer = mainSelector.find('.report-preview-container');
        var reportHtml = $reportContainer.html();
        
        if (reportHtml.indexOf('['+field+']') !== -1) {
            reportHtml = str_ireplace('['+field+']', '<span data-reporttemplate-field="'+field+'">' + value + '</span>', reportHtml);
            $reportContainer.html(reportHtml);
        }
    }
    
    return;
}
function bpReportTemplatePreview(mainSelector, metaDataId, pageSize, pageOrientation, qryStr) {
    
    var $element = mainSelector.find('.report-preview');
    
    if ($element.length) {
        
        var processId = mainSelector.attr('data-process-id');
        var $parent = $element.parent();
        
        $.ajax({
            type: 'post',
            url: 'mdtemplate/getReportTemplateHtml',
            data: {processId: processId, templateMetaId: metaDataId, pageSize: pageSize.toLowerCase(), pageOrientation: pageOrientation.toLowerCase(), qryStr: qryStr},
            dataType: 'html',
            async: false,
            success: function(dataHtml) {
                $parent.empty().append(dataHtml);
            }
        });
    }
                    
    return;
}
function bpGetDataPointInPolygon(mainSelector, elem, dsId, polygonField, coordinate, paramsPath) {
    
    var paramData = [];
    
    if (typeof paramsPath != 'undefined' && paramsPath != '') {
        var paramsPathArr = paramsPath.split('|');
        var paramsLength = paramsPathArr.length;

        for (var i = 0; i < paramsLength; i++) {
            var fieldPathArr = paramsPathArr[i].split('@');
            var fieldPath = fieldPathArr[0].trim();
            var inputPath = fieldPathArr[1].trim();
            var fieldValue = '';

            var $bpElem = getBpElement(mainSelector, elem, fieldPath);

            if ($bpElem) {
                if ($bpElem.hasClass('base64Init') || $bpElem.hasClass('fileInit')) {
                    var fileUrl = $bpElem.val();
                    if (fileUrl) {
                        var ext = fileUrl.substring(fileUrl.lastIndexOf('.') + 1).toLowerCase();
                        var formData = new FormData();
                        formData.append('file_1', $bpElem.get(0).files[0]); 
                        $.ajax({
                            type: 'post',
                            url: 'api/getBase64FromFile',
                            data: formData,
                            processData: false,
                            contentType: false,
                            async: false,
                            success: function(data) {
                                fieldValue = ext + '♠' + data;
                            }
                        });
                    }
                } else {
                    fieldValue = getBpRowParamNum(mainSelector, elem, fieldPath);
                }
            } else {
                fieldValue = fieldPath;
            }

            paramData.push({
                fieldPath: fieldPath, 
                inputPath: inputPath, 
                value: fieldValue
            });
        }
    }
    
    var postData = {
        indicatorId: dsId, 
        polygonField: polygonField,
        coordinate: coordinate, 
        uniqId: mainSelector.attr('data-bp-uniq-id'), 
        paramData: paramData
    };

    var response = $.ajax({
        type: 'post',
        url: 'mdform/getDataPointInPolygon', 
        data: postData, 
        dataType: 'json',
        async: false
    });
    var result = response.responseJSON;
    
    if (result.status != 'success') {
        console.log(result);
        return null;
    } else {
        return result.data;
    }
}
function bpSetFileExtension(mainSelector, elem, fieldPath, extensions) {
    var $getField = getBpElement(mainSelector, elem, fieldPath);
    if ($getField) {
        $getField.attr('data-valid-extension', extensions);
    }
    return;
}