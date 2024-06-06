<div class="row hrmtimelogv2-cal-parent calendar-<?php echo $this->uniqId; ?>">    
    <div class="col-md-12">
        <div id="calendar-<?php echo $this->uniqId; ?>" class="hrmtimelog-calendar"></div>
    </div>

    <style type="text/css">
        .hrmtimelog-calendar.fc .fc-row .fc-content-skeleton td.fc-day-top {
            height: 30px;
        }
        .hrmtimelog-calendar.fc .fc-row .fc-content-skeleton td.fc-event-container {
            height: 60px;
            padding: 0 5px;
        }
        .hrmtimelog-calendar .fc-basic-view .fc-body .fc-row {
            min-height: 242px;
        }
        .hrmtimelog-calendar .fc-hrm-time-log-btn {
            position: absolute; 
            bottom: 0;
            padding: 5px;
        }
        .hrmtimelog-calendar .fc-hrm-time-log-startdate {
            padding: 1px 5px;
            border-radius: 4px;
            background-color: #199ec7;
            font-weight: 600;
            color: #fff;
        }
        .hrmtimelog-calendar .fc-hrm-time-log-date {
            padding: 1px 5px;
            border-radius: 4px;
            background-color: #1BBC9B;
            font-weight: 600;
            color: #fff;
            font-size: 10px;
            margin-top: 2px;
        }
        .hrmtimelog-calendar .fc-hrm-time-log-enddate {
            margin-top: 3px;
            padding: 1px 5px;
            border-radius: 4px;
            background-color: #e54b1b;
            font-weight: 600;
            color: #fff;
        }
        .hrmtimelog-calendar .fc-hrm-time-log-date i, 
        .hrmtimelog-calendar .fc-hrm-time-log-startdate i, 
        .hrmtimelog-calendar .fc-hrm-time-log-enddate i {
            font-weight: normal;
            width: 15px;
        }
        .hrmtimelog-calendar .fc-row.panel-default {
            border: 0 !important;
            -webkit-border-radius: 0;
            -moz-border-radius: 0;
            -ms-border-radius: 0;
            -o-border-radius: 0;
            border-radius: 0;
        }
        .hrmtimelog-calendar .fc-body .fc-row.panel-default {
            height: 200px !important;
        }
        .hrmtimelog-calendar .fc-hrm-time-duration {
            height: 150px;
            font-size: 10px;
        }
        .hrmtimelog-calendar .fc-hrm-time-duration table {
            width: 100%;
            margin-top: 10px;
        }
        .hrmtimelog-calendar .fc-hrm-time-duration table > tbody > tr > td:nth-child(1n+2) {
            width: 50px;
            text-align: right;
            padding: 0;
        }
        .hrmtimelog-calendar .fc-hrm-time-log-parent {
            height: 70px;
        }
        .hrmtimelog-calendar .fc-hrm-time-descr-done {
            overflow: hidden;
            white-space: normal;
            height: 14px;
            padding: 1px 5px;
            border-radius: 4px;
            background-color: green;
            color: #fff;
            margin-top: 2px;
            text-overflow: ellipsis;
            white-space: nowrap;
            cursor: pointer;
            font-size: 11px;
            line-height: 13px;
        }
        .hrmtimelog-calendar .fc-hrm-time-descr-cancel {
            overflow: hidden;
            white-space: normal;
            height: 14px;
            padding: 1px 5px;
            border-radius: 4px;
            background-color: #F3565D;
            color: #fff;
            margin-top: 2px;
            text-overflow: ellipsis;
            white-space: nowrap;
            cursor: pointer;
            font-size: 11px;
            line-height: 13px;
        }
        .hrmtimelog-calendar .fc-hrm-time-descr {
            overflow: hidden;
            height: 14px;
            padding: 1px 5px;
            border-radius: 4px;
            background-color: #666;
            color: #fff;
            margin-top: 2px;
            text-overflow: ellipsis;
            white-space: nowrap;
            cursor: pointer;
            font-size: 11px;
            line-height: 13px;
        }
        .hrmtimelog-calendar .fc-hrm-time-descr:hover {
            background-color: #a0a0a0;
        }
        .hrmtimelog-calendar .fc-hrm-time-descr-done:hover {
            background-color: #4a964a;
        }
        .hrmtimelog-calendar .fc-hrm-time-descr-cancel:hover {
            background-color: #f76f74;
        }
        .hrmtimelog-calendar .fc-hrm-time-descr-empty {
            height: 22px;
        }
        .hrmtimelog-calendar .fc-hrm-time-duration table > tbody > tr > td > a:hover {
            text-decoration: underline;
        }
        .hrmtimelog-calendar .fc-left-request {
            float: left;
            margin-left: 10px;
        }
        .hrmtimelog-calendar .fc-day-header {
            padding: 6px 0;
            background-color: #00c5dc;
            color: #000;
        }
        .hrmtimelogv2-cal-parent .table th {
            padding: 8px 11px;
            background-color: #00c5dc;
            color: #fff;
            font-size: 12px;
            width: 175px;
            text-align: center;
        }
        .hrmtimelogv2-cal-parent .table td {
            padding: 7px 12px;
        }
        .hrmtimelog-cal-sidebar thead > tr > th {
            color: #287fb5;
            font-size: 15px;
            font-weight: 700;
        }
        .hrmtimelog-cal-sidebar > tbody > tr > td {
            border-top: 0;
            border-bottom: 0;
            padding: 7px 4px;
            font-weight: 600;
        }
        .hrmtimelog-calendar .fc-day.fc-sat, 
        .hrmtimelog-calendar .fc-day.fc-sun {  
            background-color: #fffacb; 
        }
    </style>
    <?php $currentDate = Date::currentDate('Y-m-d'); ?>
    <script type="text/javascript">
        
        $(document).ready(function () {

            $('#calendar-<?php echo $this->uniqId; ?>').fullCalendar({
                // buttonText: {
                //     prev: '<',
                //     next: '>'
                // },
                header: {
                    left: 'customPrevButton,customNextButton,customTodayButton',
                    center: '', //prev title next',
                    right: 'title'
                },
                defaultDate: moment('<?php echo $this->filterStartDate; ?>', 'YYYY-MM-DD'), 
                themeSystem: 'bootstrap4',
                buttonIcons: true,
                locale: 'mn', 
                defaultView: 'month',
                slotMinutes: 15,
                editable: false,
                droppable: false,  
                fixedWeekCount: false, 
                //showNonCurrentDates: false, 
                views: {
                    month: {
                      columnFormat: 'dddd'
                    }
                },   
                customButtons: {
                    customPrevButton: {
                        bootstrapFontAwesome: 'fa-chevron-left', 
                        click: function () {
                            $('#calendar-<?php echo $this->uniqId; ?>').fullCalendar('prev');
                            //$('#calendar-<?php echo $this->uniqId; ?>').fullCalendar('refetchEvents');
                            //fullCalendarGotoDate_<?php echo $this->uniqId; ?>();
                        }
                    },
                    customNextButton: {
                        bootstrapFontAwesome: 'fa-chevron-right', 
                        click: function () {
                            //$('#calendar-<?php echo $this->uniqId; ?>').fullCalendar('refetchEvents');
                            $('#calendar-<?php echo $this->uniqId; ?>').fullCalendar('next');
                            //fullCalendarGotoDate_<?php echo $this->uniqId; ?>();
                        }
                    }, 
                    customTodayButton: {
                        text: 'Өнөөдөр', 
                        click: function () {
                            $('#calendar-<?php echo $this->uniqId; ?>').fullCalendar('refetchEvents');
                            $('#calendar-<?php echo $this->uniqId; ?>').fullCalendar('today');
                            //fullCalendarGotoDate_<?php echo $this->uniqId; ?>();
                        }
                    }
                }, 
                events: function (start, end, timezone, callback) {
                    var filterStartDate = changeDateTimeFormat<?php echo $this->uniqId; ?>(new Date(start.unix() * 1000));
                    var filterEndDate = changeDateTimeFormat<?php echo $this->uniqId; ?>(new Date(end.unix() * 1000));
                    var param = {};

                    var data = $.extend(param, {
                        filterStartDate: filterStartDate,
                        filterEndDate: filterEndDate
                    });

                    $.ajax({
                        url: 'mdwidget/ajaxHrmTimesheetLogLoad',
                        dataType: 'json',
                        type: 'POST',
                        data: data,
                        success: function (response) {
                            if (typeof response.data !== "undefined") {
                                callback(response.data);
                            }
                            Core.unblockUI('#calendar-<?php echo $this->uniqId; ?>');
                        }
                    });
                },
                eventAfterRender: function(event, element, view) {
                    
                    var eventHtml = '', eventTimeHtml = '', maskedDate = moment(event.start).format('YYYY-MM-DD');

                    if (event.plantime != '') {
                         eventHtml += '<div class="fc-hrm-time-log-startdate" title="Төлөвлөсөн"><i class="fa fa-clock-o"></i> '+event.plantime+'</div>';
                    }

                    eventTimeHtml = '<div class="fc-hrm-time-duration" data-rowdata="'+encodeURIComponent(JSON.stringify(event.rowdata))+'" data-row="'+ htmlentities(JSON.stringify(event.requests), 'ENT_QUOTES', 'UTF-8') +'" data-date="'+maskedDate+'">';

                    if (maskedDate == '2020-02-13') {
                        console.log(event.requests);
                    }
                    if (Object.keys(event.requests).length){
                        for (var i = 0; i < (event.requests).length; i++) {
                            if ((event.requests[i]['wfmstatuscode']).toLowerCase() == 'done') {
                                eventTimeHtml += '<div class="fc-hrm-time-descr-done" data-wfmcode="" style="background-color: '+event.requests[i]['wfmstatuscolor']+'" data-id="'+event.requests[i]['id']+'" data-wfmid="'+event.requests[i]['wfmstatusid']+'" title="" data-toggle="tooltip" data-placement="bottom" title="" data-original-title="'+event.requests[i]['feedbackdesc']+'" onclick="hrmTimesheetViewProcess(this, \''+maskedDate+'\', \''+event.requests[i]['id']+'\', \''+event.requests[i]['wfmstatusid']+'\');"><i class="'+event.requests[i]['wfmstatusicon']+'"></i> '+ event.requests[i]['feedbackdesc']+'</div>';
                            } else if ((event.requests[i]['wfmstatuscode']).toLowerCase() == 'cancel') {
                                eventTimeHtml += '<div class="fc-hrm-time-descr-cancel" data-wfmcode="" style="background-color: '+event.requests[i]['wfmstatuscolor']+'" data-id="'+event.requests[i]['id']+'" data-wfmid="'+event.requests[i]['wfmstatusid']+'" title="" data-toggle="tooltip" data-placement="bottom" title="" data-original-title="'+event.requests[i]['feedbackdesc']+'" onclick="hrmTimesheetViewProcess(this, \''+maskedDate+'\', \''+event.requests[i]['id']+'\', \''+event.requests[i]['wfmstatusid']+'\');"><i class="'+event.requests[i]['wfmstatusicon']+'"></i> '+ event.requests[i]['feedbackdesc']+'</div>';
                            } else if(event.requests[i]['wfmstatusid']){
                                eventTimeHtml += '<div class="fc-hrm-time-descr fc-hrm-time-wfmstatus text-nowrap" style="background-color: '+event.requests[i]['wfmstatuscolor']+'" data-wfmcode="'+(event.requests[i]['wfmstatuscode']).toLowerCase()+'" data-wfmid="'+event.requests[i]['wfmstatusid']+'" data-id="'+event.requests[i]['id']+'" title="" data-toggle="tooltip" data-placement="bottom" title="" data-original-title="'+event.requests[i]['feedbackdesc']+'" onclick="hrmTimesheetViewProcess(this, \''+maskedDate+'\', \''+event.requests[i]['id']+'\', \''+event.requests[i]['wfmstatusid']+'\');"><i class="'+event.requests[i]['wfmstatusicon']+'"></i> '+ event.requests[i]['feedbackdesc']+'</div>';
                            }
                        }
                    } else {
                        eventTimeHtml += '<div class="fc-hrm-time-descr-empty"></div>';
                    }

                        eventTimeHtml += '<table>';
                            eventTimeHtml += '<tbody>';

                                if (event.workingtime && event.workingtime != '0' && event.workingtime != ':' && event.workingtime != '00:00') {
                                    eventTimeHtml += '<tr>';
                                        eventTimeHtml += '<td><a href="javascript:;" class="text-nowrap" data-toggle="tooltip" data-placement="bottom" title="Ажилласан" data-original-title="Ажилласан">Ажилласан</a></td>';
                                        eventTimeHtml += '<td>'+event.workingtime+'</td>';
                                    eventTimeHtml += '</tr>';
                                }
                                if (event.rowdata.balancedate !== '<?php echo $currentDate ?>' &&  event.absenttime && event.absenttime != '0' && event.absenttime != ':' && event.absenttime != '00:00') {
                                    eventTimeHtml += '<tr>';
                                        eventTimeHtml += '<td><a style="color: <?php echo $this->getDataviewConfig['absenttime']; ?>" href="javascript:;" class="text-nowrap" onclick="hrmTimesheetHdrProcess(\'<?php echo $this->procId; ?>\', \''+maskedDate+'\', this);"  data-toggle="tooltip" data-placement="bottom" title="" data-original-title="Тасалсан">Тасалсан</a></td>';
                                        eventTimeHtml += '<td style="color: <?php echo $this->getDataviewConfig['absenttime']; ?>">'+event.absenttime+'</td>';
                                    eventTimeHtml += '</tr>';
                                }
                                if (event.latetime && event.latetime != '0' && event.latetime != ':' && event.latetime != '00:00') {
                                    eventTimeHtml += '<tr>';
                                        eventTimeHtml += '<td><a style="color: <?php echo $this->getDataviewConfig['latetime']; ?>" href="javascript:;" class="text-nowrap" onclick="hrmTimesheetHdrProcess(\'<?php echo $this->procId; ?>\', \''+maskedDate+'\', this);"  data-toggle="tooltip" data-placement="bottom" title="" data-original-title="Хоцорсон">Хоцорсон</a></td>';
                                        eventTimeHtml += '<td style="color: <?php echo $this->getDataviewConfig['latetime']; ?>">'+event.latetime+'</td>';
                                    eventTimeHtml += '</tr>';
                                }
                                if (event.earlytime && event.earlytime != '0' && event.earlytime != ':' && event.earlytime != '00:00') {
                                    eventTimeHtml += '<tr>';
                                        eventTimeHtml += '<td><a style="color: <?php echo $this->getDataviewConfig['earlytime']; ?>" href="javascript:;" class="text-nowrap" onclick="hrmTimesheetHdrProcess(\'<?php echo $this->procId; ?>\', \''+maskedDate+'\', this);"  data-toggle="tooltip" data-placement="bottom" title="" data-original-title="Эрт явсан">Эрт явсан</a></td>';
                                        eventTimeHtml += '<td style="color: <?php echo $this->getDataviewConfig['earlytime']; ?>">'+event.earlytime+'</td>';
                                    eventTimeHtml += '</tr>';
                                }
                                if (typeof event.cause4 !== 'undefined' && event.cause4 && event.cause4 != '0' && event.cause4 != ':' && event.cause4 != '00:00') {
                                    eventTimeHtml += '<tr>';
                                        eventTimeHtml += '<td style="color: <?php echo isset($this->getDataviewConfig['cause4']) ? $this->getDataviewConfig['cause4'] : ''; ?>"><a href="javascript:;" class="text-nowrap" data-toggle="tooltip" data-placement="bottom" title="" data-original-title="Илүү цаг">Илүү цаг</a></td>';
                                        eventTimeHtml += '<td style="color: <?php echo isset($this->getDataviewConfig['cause4']) ? $this->getDataviewConfig['cause4'] : ''; ?>">'+event.cause4+'</td>';
                                    eventTimeHtml += '</tr>';
                                }
                                if (event.cause3 && event.cause3 != '0' && event.cause3 != ':' && event.cause3 != '00:00') {
                                    eventTimeHtml += '<tr>';
                                        eventTimeHtml += '<td style="color: <?php echo isset($this->getDataviewConfig['cause3']) ? $this->getDataviewConfig['cause3'] : ''; ?>"><a href="javascript:;" class="text-nowrap" data-toggle="tooltip" data-placement="bottom" title="" data-original-title="Гадуур ажилласан">Гадуур ажилласан</a></td>';
                                        eventTimeHtml += '<td style="color: <?php echo isset($this->getDataviewConfig['cause3']) ? $this->getDataviewConfig['cause3'] : ''; ?>">'+event.cause3+'</td>';
                                    eventTimeHtml += '</tr>';
                                }
                                if (event.cause5 && event.cause5 != '0' && event.cause5 != ':' && event.cause5 != '00:00') {
                                    eventTimeHtml += '<tr>';
                                        eventTimeHtml += '<td style="color: <?php echo isset($this->getDataviewConfig['cause5']) ? $this->getDataviewConfig['cause5'] : ''; ?>"><a href="javascript:;" class="text-nowrap" data-toggle="tooltip" data-placement="bottom" title="" data-original-title="Өвчтэй">Өвчтэй</a></td>';
                                        eventTimeHtml += '<td style="color: <?php echo isset($this->getDataviewConfig['cause5']) ? $this->getDataviewConfig['cause5'] : ''; ?>">'+event.cause5+'</td>';
                                    eventTimeHtml += '</tr>';
                                }
                                if (event.cause6 && event.cause6 != '0' && event.cause6 != ':' && event.cause6 != '00:00') {
                                    eventTimeHtml += '<tr>';
                                        eventTimeHtml += '<td style="color: <?php echo isset($this->getDataviewConfig['cause6']) ? $this->getDataviewConfig['cause6'] : '';?>"><a href="javascript:;" class="text-nowrap" data-toggle="tooltip" data-placement="bottom" title="" data-original-title="Цалингүй чөлөө">Цалингүй чөлөө</a></td>';
                                        eventTimeHtml += '<td style="color: <?php echo isset($this->getDataviewConfig['cause6']) ? $this->getDataviewConfig['cause6'] : ''; ?>">'+event.cause6+'</td>';
                                    eventTimeHtml += '</tr>';
                                }
                                if (event.cause20 && event.cause20 != '0' && event.cause20 != ':' && event.cause20 != '00:00') {
                                    eventTimeHtml += '<tr>';
                                        eventTimeHtml += '<td style="color: <?php echo isset($this->getDataviewConfig['cause20']) ? $this->getDataviewConfig['cause20'] : ''; ?>"><a href="javascript:;" class="text-nowrap" data-toggle="tooltip" data-placement="bottom" title="" data-original-title="Цалинтай чөлөө">Цалинтай чөлөө</a></td>';
                                        eventTimeHtml += '<td style="color: <?php echo isset($this->getDataviewConfig['cause20']) ? $this->getDataviewConfig['cause20'] : ''; ?>">'+event.cause20+'</td>';
                                    eventTimeHtml += '</tr>';
                                }
                                if (event.cause7 && event.cause7 != '0' && event.cause7 != ':' && event.cause7 != '00:00') {
                                    eventTimeHtml += '<tr>';
                                        eventTimeHtml += '<td style="color: <?php echo isset($this->getDataviewConfig['cause7']) ? $this->getDataviewConfig['cause7'] : ''; ?>"><a href="javascript:;" class="text-nowrap" data-toggle="tooltip" data-placement="bottom" title="" data-original-title="Ээлжийн амралт">Ээлжийн амралт</a></td>';
                                        eventTimeHtml += '<td style="color: <?php echo isset($this->getDataviewConfig['cause7']) ? $this->getDataviewConfig['cause7'] : ''; ?>">'+event.cause7+'</td>';
                                    eventTimeHtml += '</tr>';
                                }
                                if (event.cause8 && event.cause8 != '0' && event.cause8 != ':' && event.cause8 != '00:00') {
                                    eventTimeHtml += '<tr>';
                                        eventTimeHtml += '<td style="color: <?php echo isset($this->getDataviewConfig['cause8']) ? $this->getDataviewConfig['cause8'] : ''; ?>"><a href="javascript:;" class="text-nowrap" data-toggle="tooltip" data-placement="bottom" title="" data-original-title="Томилолт">Томилолт</a></td>';
                                        eventTimeHtml += '<td style="color: <?php echo isset($this->getDataviewConfig['cause8']) ? $this->getDataviewConfig['cause8'] : ''; ?>">'+event.cause8+'</td>';
                                    eventTimeHtml += '</tr>';
                                }
                                if (event.cause10 && event.cause10 != '0' && event.cause10 != ':' && event.cause10 != '00:00') {
                                    eventTimeHtml += '<tr>';
                                        eventTimeHtml += '<td style="color: <?php echo isset($this->getDataviewConfig['cause10']) ? $this->getDataviewConfig['cause10'] : ''; ?>"><a href="javascript:;" class="text-nowrap" data-toggle="tooltip" data-placement="bottom" title="" data-original-title="Сургалт">Сургалт</a></td>';
                                        eventTimeHtml += '<td style="color: <?php echo isset($this->getDataviewConfig['cause10']) ? $this->getDataviewConfig['cause10'] : '';  ?>">'+event.cause10+'</td>';
                                    eventTimeHtml += '</tr>';
                                }
                                if (event.cause11 && event.cause11 != '0' && event.cause11 != ':' && event.cause11 != '00:00') {
                                    eventTimeHtml += '<tr>';
                                        eventTimeHtml += '<td style="color: <?php echo isset($this->getDataviewConfig['cause11']) ? $this->getDataviewConfig['cause11'] : ''; ?>"><a href="javascript:;" class="text-nowrap" data-toggle="tooltip" data-placement="bottom" title="" data-original-title="Ирц нөхөх">Ирц нөхөх</a></td>';
                                        eventTimeHtml += '<td style="color: <?php echo isset($this->getDataviewConfig['cause11']) ? $this->getDataviewConfig['cause11'] : '';  ?>">'+event.cause11+'</td>';
                                    eventTimeHtml += '</tr>';
                                }
                                if (event.cause12 && event.cause12 != '0' && event.cause12 != ':' && event.cause12 != '00:00') {
                                    eventTimeHtml += '<tr>';
                                        eventTimeHtml += '<td style="color: <?php echo isset($this->getDataviewConfig['cause12']) ? $this->getDataviewConfig['cause12'] : ''; ?>"><a href="javascript:;" class="text-nowrap" data-toggle="tooltip" data-placement="bottom" title="" data-original-title="Нөхөж амрах">Нөхөж амрах</a></td>';
                                        eventTimeHtml += '<td style="color: <?php echo isset($this->getDataviewConfig['cause12']) ? $this->getDataviewConfig['cause12'] : '';  ?>">'+event.cause12+'</td>';
                                    eventTimeHtml += '</tr>';
                                }
                                if (event.holidayname && event.holidayname != '' && event.holidaycolor && event.holidaycolor != '') {
                                    eventTimeHtml += '<tr>';
                                        eventTimeHtml += '<td style="background-color: '+ event.holidaycolor +'; padding:1px 10px; border-radius: 5px">'+event.holidayname+'</td>';
                                    eventTimeHtml += '</tr>';
                                }
                                if (event.rowdata.balancedate !== '<?php echo $currentDate ?>' && event.cause1 && event.cause1 != '0' && event.cause1 != ':' && event.cause1 != '00:00') {
                                    eventTimeHtml += '<tr>';
                                        eventTimeHtml += '<td><a style="color: <?php echo isset($this->getDataviewConfig['cause1']) ? $this->getDataviewConfig['cause1'] : '';  ?>" href="javascript:;" class="text-nowrap" onclick="hrmTimesheetHdrProcess(\'<?php echo $this->procId; ?>\', \''+maskedDate+'\', this);" data-toggle="tooltip" data-placement="bottom" title="" data-original-title="Бүртгэл дутуу">Бүртгэл дутуу</a></td>';
                                        eventTimeHtml += '<td style="color: <?php echo isset($this->getDataviewConfig['cause1']) ? $this->getDataviewConfig['cause1'] : ''; ?>">'+event.cause1+'</td>';
                                    eventTimeHtml += '</tr>';
                                }                        

                            eventTimeHtml += '</tbody>';
                        eventTimeHtml += '</table>';
                    eventTimeHtml += '</div>';

                    eventHtml += eventTimeHtml;
                    
                    eventHtml += '<div class="fc-hrm-time-log-parent">';

                    
                    if (event.start != null && event.charintime != '' && event.end != null && event.charouttime != '') {
                        var $eventintime = substr(event.charintime, 0, 5);
                        var $eventouttime = substr(event.charouttime, 0, 5);
                        eventHtml += '<div class="fc-hrm-time-log-date" title="" data-toggle="tooltip" data-placement="bottom" data-original-title="Ирсэн: '+$eventintime+ '  Гарсан: ' + $eventouttime +'">'+$eventintime+ ' - ' + $eventouttime +'</div>';
                    } else {
                        if (event.start != null && event.charintime != '') {
                            eventHtml += '<div class="fc-hrm-time-log-date text-left" data-toggle="tooltip" data-placement="bottom" title=""  data-original-title="Ирсэн: '+event.charintime +'">'+event.charintime+'</div>';
                        }

                        if (event.end != null && event.charouttime != '') {
                            eventHtml += '<div class="fc-hrm-time-log-date text-right" data-toggle="tooltip" data-placement="bottom" title=""  data-original-title="Гарсан: '+event.charouttime +'" > '+event.charouttime+'</div>';
                        }
                    }
                    
                    eventHtml += '</div>';
                    
                    if (event.holidaycolor && event.holidaycolor !== '') {
                        $(element).closest('div.fc-row.fc-week.table-bordered').find('.fc-bg').find('td:eq('+ $(element).closest('td').index() +')').attr('style', 'background:' + event.holidaycolor + ';')
                    }
                    $(element).closest('td').empty().append(eventHtml);
                }, 
                eventAfterAllRender: function(view) {
                    var $headerToolbar = $('.fc-header-toolbar');
                    var $headerLeft = $headerToolbar.find('> .fc-left');

                    $headerToolbar.find('.fc-left-request').remove();
                    $headerLeft.after('<div class="fc-left-request">'+
                        '<div class="btn-group">'+
                        '<button class="btn btn-secondary btn-sm" type="button" onclick="fullCalendarGotoDate_<?php echo $this->uniqId; ?>();">Сэргээх</button>'+
                        '<button class="btn btn-secondary btn-sm" type="button" onclick="hrmTimesheetHdrProcess(\'<?php echo $this->procId; ?>\', \'\', this);">Нэмэх</button>'+
                    '</div></div>');
                }
            });

            $.contextMenu({
                selector: "#calendar-<?php echo $this->uniqId; ?> .fc-view-container .fc-body .fc-day-grid-container .fc-day, #calendar-<?php echo $this->uniqId; ?> .fc-view-container .fc-body .fc-day-grid-container .fc-day-top",
                build: function($trigger, e) {
                    var $this = $(e.currentTarget);
                    var dateStr = $this.data('date');
                    var contextMenuData = {
                        "create": {
                            name: "Нэмэх", 
                            icon: "plus", 
                            callback: function(key, options) {
                                hrmTimesheetProcess($this, dateStr, '');
                            }
                        }
                    };

                    var options =  {
                        callback: function (key, opt) {},
                        items: contextMenuData
                    };

                    return options;            
                }
            });    

            $.contextMenu({
                selector: "#calendar-<?php echo $this->uniqId; ?> .fc-view-container .fc-body .fc-content-skeleton tbody td",
                build: function($trigger, e) {
                    var $this = $(e.currentTarget);
                    var dateStr = $this.closest('.fc-week').find('.fc-bg > table > tbody > tr > td:eq('+$this.index()+')').data('date');
                    var contextMenuData = {
                        "create": {
                            name: "Нэмэх", 
                            icon: "plus", 
                            callback: function(key, options) {
                                hrmTimesheetProcess($this, dateStr, '');
                            }
                        }
                    };

                    var options =  {
                        callback: function (key, opt) {
                        },
                        items: contextMenuData
                    };

                    return options;            
                }
            });    

            $.contextMenu({
                selector: "#calendar-<?php echo $this->uniqId; ?> .fc-view-container .fc-body .fc-day-grid .fc-bg .fc-day",
                build: function($trigger, e) {
                    var $this = $(e.currentTarget);
                    var dateStr = $this.data('date');
                    var contextMenuData = {
                        "create": {
                            name: "Нэмэх", 
                            icon: "plus", 
                            callback: function(key, options) {
                                hrmTimesheetProcess($this, dateStr, '');
                            }
                        }
                    };

                    var options =  {
                        callback: function (key, opt) {},
                        items: contextMenuData
                    };

                    return options;            
                }
            });    

            $.contextMenu({
                selector: "#calendar-<?php echo $this->uniqId; ?> .fc-view-container .fc-body .fc-event-container .fc-hrm-time-wfmstatus",
                build: function($trigger, e) {
                    var $this = $(e.currentTarget);
                    var dateStr = $this.closest('.fc-hrm-time-duration').data('date');
                    var idStr = $this.data('id'), wfmCode = $this.data('wfmcode');
                    var contextMenuData = {
                        "create": {
                            name: "Нэмэх", 
                            icon: "plus", 
                            callback: function(key, options) {
                                hrmTimesheetProcess(e.currentTarget, dateStr, '');
                            }
                        },
                        "orderList": {
                            name: "Засах", 
                            icon: "edit", 
                            callback: function(key, options) {
                                hrmTimesheetEditProcess(e.currentTarget, dateStr, idStr);
                            }
                        },            
                        "delete": {
                            name: "Устгах", 
                            icon: "trash", 
                            callback: function(key, options) {
                                if (wfmCode == 'new') {
                                    var $dialogName = 'dialog-hrmsheet-fileremove';
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
                                                $.ajax({
                                                    type: 'post',
                                                    url: 'mdwidget/removeHrmTimeSheet',
                                                    data: {
                                                        'id': idStr
                                                    },
                                                    dataType: 'json', 
                                                    beforeSend: function() {
                                                        Core.blockUI({
                                                            message: 'Loading...',
                                                            boxed: true
                                                        });
                                                    },
                                                    success: function(data) {
                                                        if (data.status === 'success') {
                                                            fullCalendarGotoDate_<?php echo $this->uniqId; ?>();
                                                        }
                                                        new PNotify({
                                                            title: data.status,
                                                            text: data.message,
                                                            type: data.status, 
                                                            sticker: false
                                                        });                                                  
                                                        Core.unblockUI();
                                                    },
                                                    error: function() {
                                                        alert('Error');
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
                                } else {
                                    PNotify.removeAll();
                                    new PNotify({
                                        title: 'Warning',
                                        text: 'Зөвхөн шинэ төлөвтэйг устгана.',
                                        type: 'warning', 
                                        sticker: false
                                    });                            
                                }
                            }
                        }
                    };

                    var options =  {
                        callback: function (key, opt) {
                        },
                        items: contextMenuData
                    };

                    return options;            
                }
            });    

            $('.calendar-<?php echo $this->uniqId; ?> [data-toggle="tooltip"]').tooltip();
            
            var $fcToday = $('.fc-content-skeleton').find('.fc-today');
            
            if ($fcToday.length) {
                $('.calendar-<?php echo $this->uniqId; ?> .fc-day-grid-container').stop().animate({
                    scrollTop: eval($('.fc-content-skeleton').find('.fc-today').offset().top - 600)
                }, 2000);
            }
        });

        function fullCalendarChangeDate_<?php echo $this->uniqId; ?>() {
            var changeDate = $('#calendar-<?php echo $this->uniqId; ?>').fullCalendar('getDate').toDate();

            $.ajax({
                type: 'post',
                url: 'mdwidget/hrmTimesheetLogJson',
                data: {yearMonth: moment(changeDate).format('YYYY-MM')},
                dataType: 'json', 
                beforeSend: function(){
                    Core.blockUI({
                        message: 'Loading...',
                        boxed: true
                    });
                },
                success: function(data) {
                    var events = [], dataEvents = data.events;

                    if (dataEvents.length) {
                        $.each(data.events, function(idx, e) {
                            if (e.balancedate) {
                                var startdate = '', enddate = '', charintime = '', charouttime = '', plantime = ''; 

                                if (e.charintime) {
                                    startdate = e.balancedate + ' ' + e.charintime;
                                    charintime = e.charintime;
                                }
                                if (e.charouttime) {
                                    enddate = e.balancedate + ' ' + e.charouttime;
                                    charouttime = e.charouttime;
                                }
                                if (e.plantime) {
                                    plantime = e.plantime;
                                }

                                events.push({
                                    start: startdate,
                                    end: enddate, 
                                    plantime: plantime, 
                                    feedbackdesc: e.feedbackdesc, 
                                    workingtime: e.cleantime, 
                                    absenttime: e.absenttime, 
                                    latetime: e.latetime, 
                                    earlytime: e.earlytime, 
                                    missiontime: e.missiontime,
                                    delayedtime: e.delayedtime,
                                    wfmstatuscode: e.wfmstatuscode, 
                                    id: e.id, 
                                    charintime: charintime, 
                                    charouttime: charouttime
                                });
                            }
                        });
                    }

                    $('#calendar-<?php echo $this->uniqId; ?>').fullCalendar({
                        events: events
                    });

                    $('#log-sidebar-<?php echo $this->uniqId; ?> > tbody').empty().append(data.sidebarHtml);
                    Core.unblockUI();
                },
                error: function(){
                    Core.unblockUI();
                    alert('Error');
                }
            });
        }
        
        function hrmTimesheetViewProcess(elem, startDate, id, wfmId) {
            var $dialogName = 'dialog-hrm-timesheet-bp';
            if (!$('#' + $dialogName).length) {
                $('<div id="' + $dialogName + '" class="display-none"></div>').appendTo('body');
            }
            var $dataRow = JSON.parse($(elem).parent().attr('data-row'));
            var $dialog = $('#' + $dialogName);
            var fillDataParams = '';

            if (id) {
                fillDataParams = 'id='+id+'&defaultGetPf=1';
            } else {
                fillDataParams = 'startDate='+startDate+'&endDate='+startDate;
            }

            $.ajax({
                type: 'post',
                url: 'mdwebservice/callMethodByMeta',
                data: {
                    metaDataId: '<?php echo $this->procViewId; ?>', 
                    dmMetaDataId: '1523865772046',
                    isDialog: true, 
                    isSystemMeta: false, 
                    fillDataParams: fillDataParams,  
                    isGetConsolidate: false,
                    oneSelectedRow: $dataRow,
                    workSpaceId: '',
                    workSpaceParams: '',
                    wfmStatusParams: '',
                    signerParams: false,
                    batchNumber: false,
                    openParams: '{"callerType":"HCM_LABOUR_FEEDBACK_SELF_LIST","isDrillDown":true}',
                    isBasketWindow: '',

                    isBpOpen: 0
                },
                dataType: 'json',
                beforeSend: function () {
                    Core.blockUI({
                        message: 'Loading...', 
                        boxed: true
                    });
                },
                success: function (data) {

                    $dialog.empty().append(data.Html);

                    var $processForm = $('#wsForm', '#' + $dialogName), 
                        processUniqId = $processForm.parent().attr('data-bp-uniq-id');

                    var buttons = [
                        /*{text: data.run_btn, class: 'btn green-meadow btn-sm bp-btn-save', click: function (e) {
                            if (window['processBeforeSave_'+processUniqId]($(e.target))) {     

                                $processForm.validate({ 
                                    ignore: '', 
                                    highlight: function(element) {
                                        $(element).addClass('error');
                                        $(element).parent().addClass('error');
                                        if ($processForm.find("div.tab-pane:hidden:has(.error)").length) {
                                            $processForm.find("div.tab-pane:hidden:has(.error)").each(function(index, tab){
                                                var tabId = $(tab).attr('id');
                                                $processForm.find('a[href="#'+tabId+'"]').tab('show');
                                            });
                                        }
                                    },
                                    unhighlight: function(element) {
                                        $(element).removeClass('error');
                                        $(element).parent().removeClass('error');
                                    },
                                    errorPlacement: function(){} 
                                });

                                var isValidPattern = initBusinessProcessMaskEvent($processForm);

                                if ($processForm.valid() && isValidPattern.length === 0) {
                                    $processForm.ajaxSubmit({
                                        type: 'post',
                                        url: 'mdwebservice/runProcess',
                                        dataType: 'json',
                                        beforeSend: function () {
                                            Core.blockUI({
                                                boxed: true, 
                                                message: 'Түр хүлээнэ үү'
                                            });
                                        },
                                        success: function (responseData) {
                                            PNotify.removeAll();
                                            new PNotify({
                                                title: responseData.status,
                                                text: responseData.message,
                                                type: responseData.status, 
                                                sticker: false
                                            });

                                            if (responseData.status === 'success') {
                                                $(elem).closest('.fc-hrm-time-duration').prepend('<div class="fc-hrm-time-descr fc-hrm-time-wfmstatus" title="'+responseData.resultData.description+'">'+responseData.resultData.description+'</div>');
                                                $dialog.dialog('close');
                                            } 
                                            Core.unblockUI();
                                        },
                                        error: function () {
                                            alert("Error");
                                        }
                                    });
                                }
                            }    
                        }},*/
                        {text: data.close_btn, class: 'btn blue-madison btn-sm', click: function () {
                            $dialog.dialog('close');
                        }}
                    ];

                    var dialogWidth = data.dialogWidth, dialogHeight = data.dialogHeight;

                    if (data.isDialogSize === 'auto') {
                        dialogWidth = 1200;
                        dialogHeight = 'auto';
                    }
                    $dialog.dialog({
                        cache: false,
                        resizable: true,
                        bgiframe: true,
                        autoOpen: false,
                        title: data.Title,
                        width: dialogWidth,
                        height: dialogHeight,
                        modal: true,
                        closeOnEscape: (typeof isCloseOnEscape == 'undefined' ? true : isCloseOnEscape), 
                        close: function () {
                            $dialog.empty().dialog('destroy').remove();
                        },
                        buttons: buttons
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
                        $dialog.dialogExtend("maximize");
                    }
                    $dialog.dialog('open');
                },
                error: function () {
                    alert("Error");
                }
            }).done(function () {
                Core.initBPAjax($dialog);
                Core.unblockUI();
            });
        }
        
        function hrmTimesheetProcess(elem, startDate, id) {
            var $dialogName = 'dialog-hrm-timesheet-bp';
            if (!$('#' + $dialogName).length) {
                $('<div id="' + $dialogName + '" class="display-none"></div>').appendTo('body');
            }
            var $dialog = $('#' + $dialogName);
            var fillDataParams = '';

            if (id) {
                fillDataParams = 'id='+id+'&defaultGetPf=1';
            } else {
                fillDataParams = 'startDate='+startDate+'&endDate='+startDate;
            }    

            $.ajax({
                type: 'post',
                url: 'mdwebservice/callMethodByMeta',
                data: {
                    metaDataId: '<?php echo $this->procId; ?>', 
                    isDialog: true, 
                    isSystemMeta: false, 
                    fillDataParams: fillDataParams,  
                    callerType: 'hrmTimesheet', 
                    openParams: '{"callerType":"hrmTimesheet"}'
                },
                dataType: 'json',
                beforeSend: function () {
                    Core.blockUI({
                        message: 'Loading...', 
                        boxed: true
                    });
                },
                success: function (data) {

                    $dialog.empty().append(data.Html);

                    var $processForm = $('#wsForm', '#' + $dialogName), 
                        processUniqId = $processForm.parent().attr('data-bp-uniq-id');

                    var buttons = [
                        {text: data.run_btn, class: 'btn green-meadow btn-sm bp-btn-save', click: function (e) {
                            if (window['processBeforeSave_'+processUniqId]($(e.target))) {     

                                $processForm.validate({ 
                                    ignore: '', 
                                    highlight: function(element) {
                                        $(element).addClass('error');
                                        $(element).parent().addClass('error');
                                        if ($processForm.find("div.tab-pane:hidden:has(.error)").length) {
                                            $processForm.find("div.tab-pane:hidden:has(.error)").each(function(index, tab){
                                                var tabId = $(tab).attr('id');
                                                $processForm.find('a[href="#'+tabId+'"]').tab('show');
                                            });
                                        }
                                    },
                                    unhighlight: function(element) {
                                        $(element).removeClass('error');
                                        $(element).parent().removeClass('error');
                                    },
                                    errorPlacement: function(){} 
                                });

                                var isValidPattern = initBusinessProcessMaskEvent($processForm);

                                if ($processForm.valid() && isValidPattern.length === 0) {
                                    $processForm.ajaxSubmit({
                                        type: 'post',
                                        url: 'mdwebservice/runProcess',
                                        dataType: 'json',
                                        beforeSend: function () {
                                            Core.blockUI({
                                                boxed: true, 
                                                message: 'Түр хүлээнэ үү'
                                            });
                                        },
                                        success: function (responseData) {
                                            PNotify.removeAll();
                                            new PNotify({
                                                title: responseData.status,
                                                text: responseData.message,
                                                type: responseData.status, 
                                                sticker: false
                                            });

                                            if (responseData.status === 'success') {
                                                fullCalendarGotoDate_<?php echo $this->uniqId; ?>();
                                                $(elem).closest('.fc-hrm-time-duration').prepend('<div class="fc-hrm-time-descr fc-hrm-time-wfmstatus" title="'+responseData.resultData.description+'">'+responseData.resultData.description+'</div>');
                                                $dialog.dialog('close');
                                            } 
                                            Core.unblockUI();
                                        },
                                        error: function () {
                                            alert("Error");
                                        }
                                    });
                                }
                            }    
                        }},
                        {text: data.close_btn, class: 'btn blue-madison btn-sm', click: function () {
                            $dialog.dialog('close');
                        }}
                    ];

                    var dialogWidth = data.dialogWidth, dialogHeight = data.dialogHeight;

                    if (data.isDialogSize === 'auto') {
                        dialogWidth = 1200;
                        dialogHeight = 'auto';
                    }

                    $dialog.dialog({
                        cache: false,
                        resizable: true,
                        bgiframe: true,
                        autoOpen: false,
                        title: data.Title,
                        width: dialogWidth,
                        height: dialogHeight,
                        modal: true,
                        closeOnEscape: (typeof isCloseOnEscape == 'undefined' ? true : isCloseOnEscape), 
                        close: function () {
                            $dialog.empty().dialog('destroy').remove();
                        },
                        buttons: buttons
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
                        $dialog.dialogExtend("maximize");
                    }
                    $dialog.dialog('open');
                },
                error: function () {
                    alert("Error");
                }
            }).done(function () {
                Core.initBPAjax($dialog);
                Core.unblockUI();
            });
        }
        
        function hrmTimesheetEditProcess(elem, startDate, id) {
            var $dialogName = 'dialog-hrm-timesheet-bp';
            if (!$('#' + $dialogName).length) {
                $('<div id="' + $dialogName + '" class="display-none"></div>').appendTo('body');
            }
            var $dialog = $('#' + $dialogName);
            var fillDataParams = '';

            if (id) {
                fillDataParams = 'id='+id+'&defaultGetPf=1';
            } else {
                fillDataParams = 'startDate='+startDate+'&endDate='+startDate;
            }

            $.ajax({
                type: 'post',
                url: 'mdwebservice/callMethodByMeta',
                data: {
                    metaDataId: '<?php echo $this->procEditId; ?>', 
                    isDialog: true, 
                    isSystemMeta: false, 
                    fillDataParams: fillDataParams,  
                    callerType: 'hrmTimesheet', 
                    openParams: '{"callerType":"hrmTimesheet"}'
                },
                dataType: 'json',
                beforeSend: function () {
                    Core.blockUI({
                        message: 'Loading...', 
                        boxed: true
                    });
                },
                success: function (data) {

                    $dialog.empty().append(data.Html);

                    var $processForm = $('#wsForm', '#' + $dialogName), 
                        processUniqId = $processForm.parent().attr('data-bp-uniq-id');

                    var buttons = [
                        {text: data.run_btn, class: 'btn green-meadow btn-sm bp-btn-save', click: function (e) {
                            if (window['processBeforeSave_'+processUniqId]($(e.target))) {     

                                $processForm.validate({ 
                                    ignore: '', 
                                    highlight: function(element) {
                                        $(element).addClass('error');
                                        $(element).parent().addClass('error');
                                        if ($processForm.find("div.tab-pane:hidden:has(.error)").length) {
                                            $processForm.find("div.tab-pane:hidden:has(.error)").each(function(index, tab){
                                                var tabId = $(tab).attr('id');
                                                $processForm.find('a[href="#'+tabId+'"]').tab('show');
                                            });
                                        }
                                    },
                                    unhighlight: function(element) {
                                        $(element).removeClass('error');
                                        $(element).parent().removeClass('error');
                                    },
                                    errorPlacement: function(){} 
                                });

                                var isValidPattern = initBusinessProcessMaskEvent($processForm);

                                if ($processForm.valid() && isValidPattern.length === 0) {
                                    $processForm.ajaxSubmit({
                                        type: 'post',
                                        url: 'mdwebservice/runProcess',
                                        dataType: 'json',
                                        beforeSend: function () {
                                            Core.blockUI({
                                                boxed: true, 
                                                message: 'Түр хүлээнэ үү'
                                            });
                                        },
                                        success: function (responseData) {
                                            PNotify.removeAll();
                                            new PNotify({
                                                title: responseData.status,
                                                text: responseData.message,
                                                type: responseData.status, 
                                                sticker: false
                                            });

                                            if (responseData.status === 'success') {
                                                fullCalendarGotoDate_<?php echo $this->uniqId; ?>();
                                                $(elem).text(responseData.resultData.description);
                                                $dialog.dialog('close');
                                            } 
                                            Core.unblockUI();
                                        },
                                        error: function () {
                                            alert("Error");
                                        }
                                    });
                                }
                            }    
                        }},
                        {text: data.close_btn, class: 'btn blue-madison btn-sm', click: function () {
                            $dialog.dialog('close');
                        }}
                    ];

                    var dialogWidth = data.dialogWidth, dialogHeight = data.dialogHeight;

                    if (data.isDialogSize === 'auto') {
                        dialogWidth = 1200;
                        dialogHeight = 'auto';
                    }

                    $dialog.dialog({
                        cache: false,
                        resizable: true,
                        bgiframe: true,
                        autoOpen: false,
                        title: data.Title,
                        width: dialogWidth,
                        height: dialogHeight,
                        modal: true,
                        closeOnEscape: (typeof isCloseOnEscape == 'undefined' ? true : isCloseOnEscape), 
                        close: function () {
                            $dialog.empty().dialog('destroy').remove();
                        },
                        buttons: buttons
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
                        $dialog.dialogExtend("maximize");
                    }
                    $dialog.dialog('open');
                },
                error: function () {
                    alert("Error");
                }
            }).done(function () {
                Core.initBPAjax($dialog);
                Core.unblockUI();
            });
        }
        
        function hrmTimesheetHdrProcess(processId, currentDate, elem) {
            var $dialogName = 'dialog-hrm-timesheet-hdrbp';
            if (!$('#' + $dialogName).length) {
                $('<div id="' + $dialogName + '" class="display-none"></div>').appendTo('body');
            }
            var $dialog = $('#' + $dialogName);

            if (!currentDate) {
                var changeDate = $('#calendar-<?php echo $this->uniqId; ?>').fullCalendar('getDate').toDate();
                currentDate = moment(changeDate).format('YYYY-MM-DD');
            }
            var rowdata = decodeURIComponent($(elem).closest('.fc-hrm-time-duration').data('rowdata'));
            var fillDataParams = 'startDate='+currentDate+'&endDate='+currentDate;

            $.ajax({
                type: 'post',
                url: 'mdwebservice/callMethodByMeta',
                data: {
                    metaDataId: processId, 
                    isDialog: true, 
                    isSystemMeta: false, 
                    callerType: 'hrmTimesheetRequest', 
                    fillDataParams: fillDataParams,
                    addonJsonParam: rowdata,
                    openParams: '{"callerType":"hrmTimesheetRequest"}'
                },
                dataType: 'json',
                beforeSend: function () {
                    Core.blockUI({
                        message: 'Loading...', 
                        boxed: true
                    });
                },
                success: function (data) {

                    $dialog.empty().append(data.Html);

                    var $processForm = $('#wsForm', '#' + $dialogName), 
                        processUniqId = $processForm.parent().attr('data-bp-uniq-id');

                    var buttons = [
                        {text: data.run_btn, class: 'btn green-meadow btn-sm bp-btn-save', click: function (e) {
                            if (window['processBeforeSave_'+processUniqId]($(e.target))) {

                                $processForm.validate({ 
                                    ignore: '', 
                                    highlight: function(element) {
                                        $(element).addClass('error');
                                        $(element).parent().addClass('error');
                                        if ($processForm.find("div.tab-pane:hidden:has(.error)").length) {
                                            $processForm.find("div.tab-pane:hidden:has(.error)").each(function(index, tab){
                                                var tabId = $(tab).attr('id');
                                                $processForm.find('a[href="#'+tabId+'"]').tab('show');
                                            });
                                        }
                                    },
                                    unhighlight: function(element) {
                                        $(element).removeClass('error');
                                        $(element).parent().removeClass('error');
                                    },
                                    errorPlacement: function(){} 
                                });

                                var isValidPattern = initBusinessProcessMaskEvent($processForm);

                                if ($processForm.valid() && isValidPattern.length === 0) {

                                    if (typeof window[processUniqId+'_dialog'] !== 'undefined' && typeof window[processUniqId+'_note'] !== 'undefined' || typeof window[processUniqId+'_title'] !== 'undefined' ) {
                                        $("#" + window[processUniqId+'_dialog']).empty().append(window[processUniqId+'_note']);
                                        $("#" + window[processUniqId+'_dialog']).dialog({
                                            cache: false,
                                            resizable: false,
                                            bgiframe: true,
                                            autoOpen: false,
                                            title: window[processUniqId+'_title'],
                                            width: 370,
                                            height: "auto",
                                            modal: true,
                                            close: function () {
                                                $("#" + window[processUniqId+'_dialog']).empty().dialog('destroy').remove();
                                            },
                                            buttons: [
                                                {text: 'Тийм', class: 'btn green-meadow btn-sm', click: function () {
                                                    if (typeof window[processUniqId+'_message'] !== 'undefined' && typeof window[processUniqId+'_messageType'] !== 'undefined' ) {
                                                        PNotify.removeAll();
                                                        new PNotify({
                                                            title: window[processUniqId+'_messageType'],
                                                            text: window[processUniqId+'_message'],
                                                            type: window[processUniqId+'_messageType'],
                                                            sticker: false
                                                        });
                                                    }

                                                    $processForm.ajaxSubmit({
                                                        type: 'post',
                                                        url: 'mdwebservice/runProcess',
                                                        dataType: 'json',
                                                        beforeSend: function () {
                                                            Core.blockUI({
                                                                boxed: true, 
                                                                message: 'Түр хүлээнэ үү'
                                                            });
                                                        },
                                                        success: function (responseData) {
                                                            PNotify.removeAll();
                                                            new PNotify({
                                                                title: responseData.status,
                                                                text: responseData.message,
                                                                type: responseData.status, 
                                                                addclass: pnotifyPosition,
                                                                sticker: false
                                                            });

                                                            if (responseData.status === 'success') {
                                                                $dialog.dialog('close');
                                                                fullCalendarGotoDate_<?php echo $this->uniqId; ?>();
                                                            } 
                                                            Core.unblockUI();
                                                        },
                                                        error: function () {
                                                            alert("Error");
                                                        }
                                                    });

                                                    $("#" + window[processUniqId+'_dialog']).empty().dialog('destroy').remove();
                                                }},
                                                {text: 'Үгүй', class: 'btn blue-madison btn-sm', click: function () {
                                                    $("#" + window[processUniqId+'_dialog']).empty().dialog('destroy').remove();
                                                }}
                                            ]
                                        }); 
                                        $("#" + window[processUniqId+'_dialog']).dialog('open');

                                    } else {

                                        $processForm.ajaxSubmit({
                                            type: 'post',
                                            url: 'mdwebservice/runProcess',
                                            dataType: 'json',
                                            beforeSend: function () {
                                                Core.blockUI({
                                                    boxed: true, 
                                                    message: 'Түр хүлээнэ үү'
                                                });
                                            },
                                            success: function (responseData) {
                                                PNotify.removeAll();
                                                new PNotify({
                                                    title: responseData.status,
                                                    text: responseData.message,
                                                    type: responseData.status, 
                                                    addclass: pnotifyPosition,
                                                    sticker: false
                                                });

                                                window['processAfterSave_'+processUniqId]($(e.target), responseData.status, responseData);

                                                if (responseData.status == 'success') {
                                                    $dialog.dialog('close');
                                                    fullCalendarGotoDate_<?php echo $this->uniqId; ?>();
                                                } 
                                                
                                                Core.unblockUI();
                                            },
                                            error: function () {
                                                alert("Error");
                                            }
                                        });                            
                                    }                                                        
                                }
                            }    
                        }},
                        {text: data.close_btn, class: 'btn blue-madison btn-sm', click: function () {
                            $dialog.dialog('close');
                        }}
                    ];

                    var dialogWidth = data.dialogWidth, dialogHeight = data.dialogHeight;

                    if (data.isDialogSize === 'auto') {
                        dialogWidth = 1200;
                        dialogHeight = 'auto';
                    }

                    $dialog.dialog({
                        cache: false,
                        resizable: true,
                        bgiframe: true,
                        autoOpen: false,
                        title: data.Title,
                        width: dialogWidth,
                        height: dialogHeight,
                        modal: true,
                        closeOnEscape: (typeof isCloseOnEscape == 'undefined' ? true : isCloseOnEscape), 
                        close: function () {
                            $dialog.empty().dialog('destroy').remove();
                        },
                        buttons: buttons
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
                        $dialog.dialogExtend("maximize");
                    }
                    $dialog.dialog('open');
                },
                error: function () {
                    alert("Error");
                }
            }).done(function () {
                Core.initBPAjax($dialog);
                Core.unblockUI();
            });
        }
        
        function fullCalendarGotoDate_<?php echo $this->uniqId; ?>() {
            $('#calendar-<?php echo $this->uniqId; ?>').fullCalendar('refetchEvents');
            return;
            var changeDate = $('#calendar-<?php echo $this->uniqId; ?>').fullCalendar('getDate').toDate();
            $.ajax({
                type: 'post',
                url: 'mdwidget/runWidget',
                data: {
                    widgetCode: 'widgethrmtimesheetlogv3', 
                    metaDataId: '1564466822400', 
                    uniqId: getUniqueId(1), 
                    yearMonth: moment(changeDate).format('YYYY-MM')
                },
                dataType: 'json',
                beforeSend: function () {
                    Core.blockUI({
                        boxed: true, 
                        message: 'Loading...'
                    });
                },
                success: function(data){
                    var $parent = $('#calendar-<?php echo $this->uniqId; ?>').closest('.hrmtimelogv2-cal-parent');
                    $parent.after(data.html);
                    $parent.remove();
                    Core.unblockUI();
                },
                error: function(){
                    alert('Error');
                }
            });
        } 
        
        function changeDateTimeFormat<?php echo $this->uniqId; ?>(date) {
            var yyyy = date.getFullYear();
            var MM = date.getMonth() + 1;
            var dd = date.getDate();

            if (MM < 10) {
                MM = '0' + MM
            }
            if (dd < 10) {
                dd = '0' + dd
            }
            /*date = MM + "/" + dd + "/" + yyyy;        */
            return yyyy + "-" + MM + "-" + dd;
        }
    </script>
    
</div>