<div class="row hrmtimelog-cal-parent">    
    <div class="col-md-9">
        <div id="calendar-<?php echo $this->uniqId; ?>" class="hrmtimelog-calendar"></div>
    </div>
    <div class="col-md-3">
        
        <div class="smalldate-float-left form-body xs-form pt5 pl0 pr0" id="cal-filter-form-<?php echo $this->uniqId; ?>">
            <div class="dateElement input-group float-left">
                <input type="text" data-path="filterStartDate" class="form-control form-control-sm dateInit" placeholder="Эхлэх огноо" title="Эхлэх огноо" value="<?php echo $this->filterStartDate; ?>">
                <span class="input-group-btn">
                    <button onclick="return false;" class="btn" tabindex="-1"><i class="fa fa-calendar"></i></button>
                </span>
            </div>
            <div class="dateElement input-group float-left">
                <input type="text" data-path="filterEndDate" class="form-control form-control-sm dateInit" placeholder="Дуусах огноо" title="Дуусах огноо" value="<?php echo $this->filterEndDate; ?>">
                <span class="input-group-btn">
                    <button onclick="return false;" class="btn" tabindex="-1"><i class="fa fa-calendar"></i></button>
                </span>
            </div>
        </div>
        <div class="float-left ml5">
            <button type="button" class="btn btn-xs btn-primary calendar-sidebar-filter-<?php echo $this->uniqId; ?>">Шүүх</button>
        </div>
        
        <div class="clearfix w-100"></div>
        
        <div class="card light bg-white" style="margin-top: 7px; padding: 0;">
            <table class="table table-sm table-hover hrmtimelog-cal-sidebar mb0" id="log-sidebar-<?php echo $this->uniqId; ?>">
                <thead>
                    <tr>
                        <th style="width: 170px">Төрөл</th>
                        <th class="text-right">Хугацаа</th>
                    </tr>
                </thead>
                <tbody>
                    <?php echo $this->sidebarHtml; ?>
                </tbody>
            </table>
        </div>
        <div class="clearfix w-100"></div>
        <div class="col-md-12" style="background-color: #fff">
            <div class="mt10" id="load-news-dataview-<?php echo $this->uniqId; ?>"></div>
        </div>
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
        background-color:#199ec7;
        font-weight: 600;
        color: #fff;
    }
    .hrmtimelog-calendar .fc-hrm-time-log-date.start{
        background-color: <?php echo $this->startBgColor; ?>;
    }
    .hrmtimelog-calendar .fc-hrm-time-log-date {
        padding: 1px 5px;
        border-radius: 4px;
        background-color: <?php echo $this->endBgColor; ?>;
        font-weight: 600;
        color: #fff;
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
        height: 100px;
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
        height: 78px;
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
        color: #fff;
    }
    .hrmtimelog-cal-parent .table th {
        padding: 8px 11px;
        background-color: #00c5dc;
        color: #fff;
        font-size: 12px;
        width: 175px;
        text-align: center;
    }
    .hrmtimelog-cal-parent .table td {
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
    #load-news-dataview-<?php echo $this->uniqId; ?> .meta-toolbar {
        background-color: #00c5dc !important;
        background: none;
        min-height: 26px;
        height: 39px;
        padding-bottom: 3px;
        margin-left: -10px;
        margin-right: -10px;
    }
    #load-news-dataview-<?php echo $this->uniqId; ?> .meta-toolbar span {
        color: #fff;
        padding-left: 6px;
    }
    #load-news-dataview-<?php echo $this->uniqId; ?> .table-scrollable {
        height: 800px;
        background: #fff;        
    }
    .hrmtimelog-calendar .fc-day.fc-sat, 
    .hrmtimelog-calendar .fc-day.fc-sun {  
        background-color: #fffacb; 
    }
</style>

<script type="text/javascript">
$(function(){
    
    var $form = $('#cal-filter-form-<?php echo $this->uniqId; ?>');
    
    $('#calendar-<?php echo $this->uniqId; ?>').fullCalendar('destroy');
    $('#calendar-<?php echo $this->uniqId; ?>').fullCalendar({
        header: {
            left: 'customPrevButton,customNextButton,customTodayButton',
            center: '',
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
                    fullCalendarGotoDate_<?php echo $this->uniqId; ?>();
                }
            },
            customNextButton: {
                bootstrapFontAwesome: 'fa-chevron-right', 
                click: function () {
                    $('#calendar-<?php echo $this->uniqId; ?>').fullCalendar('next');
                    fullCalendarGotoDate_<?php echo $this->uniqId; ?>();
                }
            }, 
            customTodayButton: {
                text: 'Өнөөдөр', 
                click: function () {
                    $('#calendar-<?php echo $this->uniqId; ?>').fullCalendar('today');
                    fullCalendarGotoDate_<?php echo $this->uniqId; ?>();
                }
            }
        }, 
        events: [
        <?php
        if ($this->calendarData) {
            $calendarGroupedData = Arr::groupByArray($this->calendarData, 'balancedate');

            foreach ($calendarGroupedData as $balanceDate => $row) {
                
                $evnt = $row['row'];
                $balanceDate = Date::formatter($balanceDate, 'Y-m-d');
                $startdate = $enddate = $plantime = $charintime = $charouttime = $requests = ''; 
                
                if ($evnt['starttime']) {
                    $startdate = $balanceDate.' '.$evnt['starttime'].':00';
                }
                if ($evnt['endtime']) {
                    $enddate = $balanceDate.' '.$evnt['endtime'].':00';
                }
                
                if ($startdate == '' && $evnt['charintime']) {
                    $startdate = $balanceDate.' '.$evnt['charintime'];
                }
                if ($enddate == '' && $evnt['charouttime']) {
                    $enddate = $balanceDate.' '.$evnt['charouttime'];
                } 
                if ($evnt['plantime']) {
                    $plantime = $evnt['plantime'];
                }
                
                if (isset($row['rows'])) {
                    $rows = $row['rows'];
                    
                    foreach ($rows as $child) {
                        if ($child['id']) {
                            $requests .= '{id:\''.$child['id'].'\', feedbackdesc:\''.str_replace("'", "\'", Str::nlToSpace($child['feedbackdesc'])).'\', wfmstatuscode:\''.$child['wfmstatuscode'].'\'},';
                        }
                    }
                }
        ?>
                {
                    start: '<?php echo $startdate; ?>', 
                    end: '<?php echo $enddate; ?>', 
                    plantime: '<?php echo $plantime; ?>', 
                    workingtime: '<?php echo $evnt['cleantime']; ?>', 
                    absenttime: '<?php echo $evnt['absenttime']; ?>', 
                    latetime: '<?php echo issetParam($evnt['latetime']); ?>', 
                    earlytime: '<?php echo issetParam($evnt['earlytime']); ?>', 
                    cause9: '<?php echo issetParam($evnt['cause9']); ?>', 
                    charintime: '<?php echo $evnt['charintime']; ?>', 
                    charouttime: '<?php echo $evnt['charouttime']; ?>', 
                    id: '<?php echo $evnt['id']; ?>', 
                    requests: [<?php echo $requests; ?>]
                }, 
        <?php
            }
        }
        ?>    
        ], 
        eventAfterRender: function(event, element, view) {
            var eventHtml = '', eventTimeHtml = '', maskedDate = moment(event.start).format('YYYY-MM-DD');
            
            eventHtml += '<div class="fc-hrm-time-log-startdate" title="Төлөвлөсөн"><i class="fa fa-clock-o"></i> '+event.plantime+'</div>';
            
            eventTimeHtml = '<div class="fc-hrm-time-duration">';
            
            if (Object.keys(event.requests).length){
                for (var i = 0; i < (event.requests).length; i++) {
                    if ((event.requests[i]['wfmstatuscode']).toLowerCase() == 'done') {
                        eventTimeHtml += '<div class="fc-hrm-time-descr-done" title="'+event.requests[i]['feedbackdesc']+'" onclick="hrmTimesheetProcess(this, \''+maskedDate+'\', \''+event.requests[i]['id']+'\');">'+event.requests[i]['feedbackdesc']+'</div>';
                    } else if ((event.requests[i]['wfmstatuscode']).toLowerCase() == 'cancel') {
                        eventTimeHtml += '<div class="fc-hrm-time-descr-cancel" title="'+event.requests[i]['feedbackdesc']+'" onclick="hrmTimesheetProcess(this, \''+maskedDate+'\', \''+event.requests[i]['id']+'\');">'+event.requests[i]['feedbackdesc']+'</div>';
                    } else {
                        eventTimeHtml += '<div class="fc-hrm-time-descr" title="'+event.requests[i]['feedbackdesc']+'" onclick="hrmTimesheetProcess(this, \''+maskedDate+'\', \''+event.requests[i]['id']+'\');">'+event.requests[i]['feedbackdesc']+'</div>';
                    }
                }
            } else {
                eventTimeHtml += '<div class="fc-hrm-time-descr-empty"></div>';
            }
            
                eventTimeHtml += '<table>';
                    eventTimeHtml += '<tbody>';
                        
                        if (event.workingtime && event.workingtime != '0' && event.workingtime != ':' && event.workingtime != '00:00') {
                            eventTimeHtml += '<tr>';
                                eventTimeHtml += '<td>Ажилласан</td>';
                                eventTimeHtml += '<td>'+event.workingtime+'</td>';
                            eventTimeHtml += '</tr>';
                        }
                        if (event.absenttime && event.absenttime != '0' && event.absenttime != ':' && event.absenttime != '00:00') {
                            eventTimeHtml += '<tr>';
                                eventTimeHtml += '<td><a href="javascript:;" onclick="hrmTimesheetHdrProcess(\'1526891112281\', \''+maskedDate+'\', this);">Тасалсан</a></td>';
                                eventTimeHtml += '<td>'+event.absenttime+'</td>';
                            eventTimeHtml += '</tr>';
                        }
                        if (event.latetime && event.latetime != '0' && event.latetime != ':' && event.latetime != '00:00') {
                            eventTimeHtml += '<tr>';
                                eventTimeHtml += '<td><a href="javascript:;" onclick="hrmTimesheetHdrProcess(\'1526891112281\', \''+maskedDate+'\', this);">Хоцорсон</a></td>';
                                eventTimeHtml += '<td>'+event.latetime+'</td>';
                            eventTimeHtml += '</tr>';
                        }
                        if (event.earlytime && event.earlytime != '0' && event.earlytime != ':' && event.earlytime != '00:00') {
                            eventTimeHtml += '<tr>';
                                eventTimeHtml += '<td><a href="javascript:;" onclick="hrmTimesheetHdrProcess(\'1526891112281\', \''+maskedDate+'\', this);">Эрт явсан</a></td>';
                                eventTimeHtml += '<td>'+event.earlytime+'</td>';
                            eventTimeHtml += '</tr>';
                        }
                        if (event.cause9 && event.cause9 != '0' && event.cause9 != ':' && event.cause9 != '00:00') {
                            eventTimeHtml += '<tr>';
                                eventTimeHtml += '<td>Баяраар ажилласан</td>';
                                eventTimeHtml += '<td>'+event.cause9+'</td>';
                            eventTimeHtml += '</tr>';
                        }
                        
                    eventTimeHtml += '</tbody>';
                eventTimeHtml += '</table>';
            eventTimeHtml += '</div>';
            
            eventHtml += eventTimeHtml;
            
            eventHtml += '<div class="fc-hrm-time-log-parent">';
            
            if (event.start != null && event.charintime != '') {
                eventHtml += '<div class="fc-hrm-time-log-date start" title="Орсон"><i class="fa fa-sign-in"></i> '+event.charintime+'</div>';
            }
        
            if (event.start != null && event.charouttime != '') {
                eventHtml += '<div class="fc-hrm-time-log-date end" title="Гарсан"><i class="fa fa-sign-out"></i> '+event.charouttime+'</div>';
            }
            
            eventHtml += '</div>';
            
            $(element).closest('td').empty().append(eventHtml);
        }, 
        eventAfterAllRender: function(view) {
            var $headerToolbar = $('.fc-header-toolbar');
            var $headerLeft = $headerToolbar.find('> .fc-left');
            
            $headerToolbar.find('.fc-left-request').remove();
            $headerLeft.after('<div class="fc-left-request">'+
                '<div class="btn-group">'+
                '<button class="btn btn-secondary btn-sm" type="button" onclick="fullCalendarGotoDate_<?php echo $this->uniqId; ?>();">Сэргээх</button>'+
                '<button class="btn btn-secondary btn-sm" type="button" onclick="hrmTimesheetHdrProcess(\'1526891112281\', \'\', this);">Нэмэх</button>'+
            '</div></div>');
        }    
    });
    
    dvFilterDateCheckInterval($form);
    
    $('.calendar-sidebar-filter-<?php echo $this->uniqId; ?>').on('click', function(){
        
        var $startDateElem = $form.find('input[data-path="filterStartDate"]');
        var $endDateElem = $form.find('input[data-path="filterEndDate"]');
        
        if (!$startDateElem.inputmask('isComplete')) {
            $startDateElem.addClass('error');
            return;
        }
        
        if (!$endDateElem.inputmask('isComplete')) {
            $endDateElem.addClass('error');
            return;
        }
        
        $startDateElem.removeClass('error');
        $endDateElem.removeClass('error');
        
        var startDate = $startDateElem.val();
        var endDate = $endDateElem.val();
        
        $.ajax({
            type: 'post',
            url: 'mdwidget/hrmTimesheetSidebar',
            data: {startDate: startDate, endDate: endDate},
            beforeSend: function() {
                Core.blockUI({
                    message: 'Loading...',
                    boxed: true
                });
            },
            success: function(data) {
                $('#log-sidebar-<?php echo $this->uniqId; ?> > tbody').empty().append(data);
                Core.unblockUI();
            }
        });
    });

    $.ajax({
        type: 'post',
        url: 'mdobject/dataview/1548219753368/0/json',
        data: {
            // uriParams: JSON.stringify(defaultCriteriaParams)
        },
        dataType: 'json',
        success: function (data) {
            $('#load-news-dataview-<?php echo $this->uniqId; ?>').append(data.Html);
        }
    });       
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
                            cause9: e.cause9, 
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
            metaDataId: '1526891112281', 
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
                                        $(elem).closest('.fc-hrm-time-duration').prepend('<div class="fc-hrm-time-descr" title="'+responseData.resultData.description+'">'+responseData.resultData.description+'</div>');
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
    var changeDate = $('#calendar-<?php echo $this->uniqId; ?>').fullCalendar('getDate').toDate();
    $.ajax({
        type: 'post',
        url: 'mdwidget/runWidget',
        data: {
            widgetCode: 'widgetHrmTimeSheetLog', 
            metaDataId: '1525924202191337', 
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
            var $parent = $('#calendar-<?php echo $this->uniqId; ?>').closest('.hrmtimelog-cal-parent');
            $parent.after(data.html);
            $parent.remove();
            Core.unblockUI();
        },
        error: function(){
            alert('Error');
        }
    });
} 
</script>
</div>