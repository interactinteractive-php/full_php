/* global $calendarFilterForm, Core, close_btn */

var mdCalendar=function(){

    //<editor-fold defaultstate="collapsed" desc="variables">
    var calendarMetaRow, tmpVal;
    //</editor-fold>

    //<editor-fold defaultstate="collapsed" desc="mdCalendar">
    var initEvent=function(calUniqId){
        var $calendarGoto=$('#calendar-goto-' + calUniqId);
        var $calendar=$('#calendar_' + calUniqId);

        $calendarGoto.find('#month-' + calUniqId).change(function(){
            var srcVal=$(this).val();
            if($calendarGoto.find('#year-' + calUniqId).val() !== '' && srcVal !== ''){
                $calendarFilterForm.find('input[name="param[filterStartDate]"]').val($calendarGoto.find('#year-' + calUniqId).val() + '-' + srcVal + '-01');
                var nextMonth=parseInt(srcVal) + 1;
                nextMonth=(nextMonth < 10) ? ("0" + nextMonth) : nextMonth;
                $calendarFilterForm.find('input[name="param[filterEndDate]"]').val($calendarGoto.find('#year-' + calUniqId).val() + '-' + nextMonth + '-01');
                $calendar.fullCalendar('gotoDate', $calendarGoto.find('#year-' + calUniqId).val() + '-' + srcVal);
                $calendarGoto.find('#title-' + calUniqId).prop('disabled', false);
            }
        });

        initCalendar(calUniqId, $calendar, $calendarGoto);
    };

    var initCalendar=function(calUniqId, $calendar, $calendarGoto, param){
        if(!jQuery().fullCalendar){
            return;
        }

        var localOptions={
            buttonText: {
                today: 'Өнөөдөр',
                month: 'Сар',
                day: 'Өдөр',
                week: '7 хоног'
            }
        };

        $calendar.fullCalendar('destroy');
        $calendar.fullCalendar($.extend({
            header: {
                left: 'title',
                center: '',
                right: 'prev,next,today,month,agendaWeek,agendaDay'
            },
            defaultView: calendarMetaRow.DEFAULT_VIEW,
            slotMinutes: 5,
            editable: false,
            allDaySlot: false,
            events: function(start, end, timezone, callback){
                if(typeof param === "undefined"){
                    param={};
                }

                var data=$.extend(param,
                        {
                            metaDataId: calendarMetaRow.TARGET_META_DATA_ID,
                            startParamPath: calendarMetaRow.START_PARAM_PATH,
                            endParamPath: calendarMetaRow.END_PARAM_PATH,
                            calendarStartDate: start.unix(),
                            calendarEndDate: end.unix(),
                            columnParamPath: calendarMetaRow.FILTER_GROUP_PARAM_PATH,
                            calendarColumnParam: $calendarGoto.find('#title-' + calUniqId).val(),
                            defaultCriteriaData: $calendarFilterForm.serialize()
                        }
                );

                var dateCalendarStart=new Date(start.unix() * 1000);

                $.ajax({
                    url: 'mdobject/dataViewDataGrid/false',
                    dataType: 'json',
                    type: 'POST',
                    data: data,
                    success: function(data){
                        if(typeof data.rows !== "undefined"){
                            setCalendarData(data.rows);
                        }
                    }
                });

                var setCalendarData=function(rows){
                    var events=[],
                            inFilterGroup=[],
                            htmlOption=
                            '<select name="title-' + calUniqId + '" id="title-' + calUniqId + '" class="form-control form-control-sm select2">' +
                            '<option value="">-Сонгох-</option>';

                    var cnt=0;
                    $.each(rows, function(key, value){
                        if(value !== null){
                            var dateDataStart=new Date(value[calendarMetaRow.START_PARAM_PATH]);
                            if((dateCalendarStart.getMonth() + 1) === (dateDataStart.getMonth()) &&
                                    typeof inFilterGroup[value[calendarMetaRow.FILTER_GROUP_PARAM_PATH]] === 'undefined'){
                                var fllterGroupVal=value[calendarMetaRow.FILTER_GROUP_PARAM_PATH];
                                cnt++;
                                if(fllterGroupVal !== null){
                                    inFilterGroup[fllterGroupVal]=fllterGroupVal;
                                    htmlOption+='<option value="' + value[calendarMetaRow.FILTER_GROUP_PARAM_PATH].replace(/((\s*\S+)*)\s*/, "$1") +
                                            '">' +
                                            value[calendarMetaRow.FILTER_GROUP_PARAM_PATH] + '</option>';
                                }

                            }
                            events.push({
                                title: value[calendarMetaRow.COLUMN_PARAM_PATH],
                                rowData: value,
                                start: value[calendarMetaRow.START_PARAM_PATH],
                                end: value[calendarMetaRow.END_PARAM_PATH],
                                color: value[calendarMetaRow.COLOR_PARAM_PATH],
                                icon: (value.hasOwnProperty('icon') ? value.icon : ''),
                                allDay: false
                            });
                        }
                    });

                    callback(events);

                    htmlOption+='</select>';
                    $calendarGoto.find('#month-' + calUniqId).select2('val', getSelectedMonth($calendar));
                    $calendarGoto.find('#htmlOption').html(htmlOption);
                    if($calendarGoto.find('#month-' + calUniqId).val() === ''){
                        $calendarGoto.find('#title-' + calUniqId).prop('disabled', true);
                    }

                    if(cnt === 1){
                        $calendarGoto.find('#title-' + calUniqId).val(tmpVal);
                    }
                    initCalendarLabelSearchEvent(calUniqId, $calendar, $calendarGoto);
                };
            },
            axisFormat: 'HH:mm',
            height: calendarMetaRow.HEIGHT !== null ? parseFloat(calendarMetaRow.HEIGHT.replace('px', '')) : 400,
            locale: 'mn',
            eventRender: function(event, element){
                element.find('.fc-title').attr('title', event.title);
                element.find('.fc-title').attr('data-row-data', JSON.stringify(event.rowData));
                if(event.hasOwnProperty('icon') && event.icon !== ''){
                    element.find('.fc-title').prepend('<i class="' + event.icon + '"></i> ');
                }
            },
            eventClick: function(event, jsEvent, view){
                if(calendarMetaRow.LINK_META_DATA_ID !== null){
                    drillDownTransferProcessAction('transferProcessAction', '1', '', '', calendarMetaRow.TARGET_META_DATA_ID, calendarMetaRow.LINK_META_DATA_ID, 'toolbar', '', this, '', 1, '',
                            event.rowData);
                    return false;
                }
            }
        }, localOptions));
    };

    var initCalendarLabelSearchEvent=function(calUniqId, $calendar, $calendarGoto){
        Core.initSelect2($calendarGoto.find('#htmlOption'));

        $calendarGoto.find('#title-' + calUniqId).change(function(){
            tmpVal=$(this).val();
            $calendar.fullCalendar('refetchEvents');
        });
    };

    var getSelectedMonth=function($calendar){
        var date=$calendar.fullCalendar('getDate')['_d'];
        var month=date.getMonth() + 1;
        return month < 10 ? '0' + month : '' + month;
    };
    //</editor-fold>

    return {
        init: function(calUniqId, tCalendarMetaRow){
            calendarMetaRow=tCalendarMetaRow;
            initEvent(calUniqId);
        }
    };
}();