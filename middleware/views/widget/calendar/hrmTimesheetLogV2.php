<?php 
$hrmCalendarDrillProcessId = Config::getFromCache('hrmCalendarDrillProcessId'); 
?>
<div class="row hrmtimelogv2-cal-parent hrmlog-<?php echo $this->uniqId; ?>">    
    <div class="col-md-9">
        <div id="calendar-<?php echo $this->uniqId; ?>" class="hrmtimelog-calendar"></div>
    </div>
    <div class="col-md-3">
        
        <div class="smalldate-float-left form-body xs-form pt5 pl0 pr0" id="cal-filter-form-<?php echo $this->uniqId; ?>">
            <div class="dateElement input-group float-left">
                <input type="text" data-path="filterStartDate" class="form-control form-control-sm dateInit" placeholder="<?php echo $this->lang->line('start_date'); ?>" title="Эхлэх огноо" value="<?php echo $this->filterStartDate; ?>">
                <span class="input-group-btn">
                    <button onclick="return false;" class="btn" tabindex="-1"><i class="fa fa-calendar"></i></button>
                </span>
            </div>
            <div class="dateElement input-group float-left">
                <input type="text" data-path="filterEndDate" class="form-control form-control-sm dateInit" placeholder="<?php echo $this->lang->line('end_date'); ?>" title="Дуусах огноо" value="<?php echo $this->filterEndDate; ?>">
                <span class="input-group-btn">
                    <button onclick="return false;" class="btn" tabindex="-1"><i class="fa fa-calendar"></i></button>
                </span>
            </div>
        </div>
        <div class="float-left ml5">
            <button type="button" class="btn btn-xs btn-primary calendar-sidebar-filter-<?php echo $this->uniqId; ?>"><?php echo $this->lang->line('do_filter'); ?></button>
        </div>
        
        <div class="clearfix w-100"></div>
        
        <div class="card light bg-white" style="margin-top: 7px; padding: 0; max-height: 250px; overflow: auto;">
            <table class="table table-sm table-hover hrmtimelog-cal-sidebar mb0" id="log-sidebar-<?php echo $this->uniqId; ?>">
                <thead>
                    <tr>
                        <th style="width: 170px"><?php echo $this->lang->line('type'); ?></th>
                        <th class="text-right"><?php echo $this->lang->line('wf_duration'); ?></th>
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
        background-color: <?php echo $this->startBgColor; ?>;
        font-weight: 600;
        color: #fff;
    }
    .hrmtimelog-calendar .fc-hrm-time-log-date {
        padding: 1px 5px;
        border-radius: 4px;
        background-color: <?php echo $this->endBgColor; ?>;
        font-weight: 600;
        color: #fff;
        font-size: 10px;
        margin-top: 2px;
        <?php
        if ($hrmCalendarDrillProcessId) {
        ?>
        cursor: pointer;
        <?php
        }
        ?>
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
        height: 0;
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
        /*color: #fff;*/
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
    #load-news-dataview-<?php echo $this->uniqId; ?> .card-multi-tab-content .tab-pane .meta-toolbar span{
        color: #000 !important;
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
    .hrmtimelog-calendar .fc-day.fc-sat, 
    .hrmtimelog-calendar .fc-day.fc-sun {  
        background-color: #fffacb; 
    }
</style>

<script type="text/javascript">
$(function() {        

    $('.layout-manual-refresh-btn').css({top:'0',right:'2px'})
    
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
        locale: '<?php echo Lang::getCode() ?>', 
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
                text: plang.get("calendar_globe_today"), 
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
                $startdate = $enddate = $plantime = $charintime = $charouttime = $requests = $rowdata = ''; 
                
                if ($evnt['starttime']) {
                    $startdate = $balanceDate.' '.$evnt['starttime'].':00';
                }
                if ($evnt['endtime']) {
                    $enddate = $balanceDate.' '.$evnt['endtime'].':00';
                }
                
                if ($startdate == '' && $evnt['charintime']) {
                    $startdate = $balanceDate.' '.$evnt['charintime'];
                } elseif ($startdate == '') {
                    $startdate = $balanceDate.' 00:00:00';
                }
                if ($enddate == '' && $evnt['charouttime']) {
                    $enddate = $balanceDate.' '.$evnt['charouttime'];
                }  elseif ($enddate == '') {
                    $enddate = $balanceDate.' 00:00:00';
                }
                if ($evnt['plantime']) {
                    $plantime = $evnt['plantime'];
                }
                
                if (isset($row['rows'])) {
                    $rows = $row['rows'];
                    
                    foreach ($rows as $child) {
                        if ($child['id']) {
                            $requests .= '{id:\''.$child['id'].'\', feedbackdesc:\''.str_replace("'", "\'", Str::nlToSpace($child['feedbackdesc'])).'\', wfmstatuscode:\''.$child['wfmstatuscode'].'\', wfmstatusid:\''.$child['wfmstatusid'].'\', wfmstatuscolor:\''.$child['wfmstatuscolor'].'\'},';
                        }
                    }
                }
                $rowdata .= '{';
                foreach ($evnt as $ck => $crow) {
                    if ($ck != 'id') {
                        $rowdata .= $ck.':\''.str_replace("'", "\'", Str::nlToSpace($crow)) . '\',';
                    }
                }
                $rowdata .= '}';                
        ?>
                {
                    start: '<?php echo $startdate; ?>', 
                    end: '<?php echo $enddate; ?>', 
                    plantime: '<?php echo $plantime; ?>', 
                    workingtime: '<?php echo $evnt['cleantime']; ?>', 
                    holidayname: '<?php echo issetParam($evnt['holidayname']); ?>', 
                    absenttime: '<?php echo $evnt['absenttime']; ?>', 
                    latetime: '<?php echo issetParam($evnt['latetime']); ?>', 
                    earlytime: '<?php echo issetParam($evnt['earlytime']); ?>', 
                    cause3: '<?php echo issetParam($evnt['cause3']); ?>', 
                    cause4: '<?php echo issetParam($evnt['cause4']); ?>', 
                    cause5: '<?php echo issetParam($evnt['cause5']); ?>', 
                    cause6: '<?php echo issetParam($evnt['cause6']); ?>', 
                    cause20: '<?php echo issetParam($evnt['cause20']); ?>', 
                    cause7: '<?php echo issetParam($evnt['cause7']); ?>', 
                    cause8: '<?php echo issetParam($evnt['cause8']); ?>', 
                    cause10: '<?php echo issetParam($evnt['cause10']); ?>', 
                    cause1: '<?php echo issetParam($evnt['cause1']); ?>', 
                    rowcolor: '<?php echo issetParam($evnt['rowcolor']); ?>', 
                    charintime: '<?php echo $evnt['charintime']; ?>', 
                    charouttime: '<?php echo $evnt['charouttime']; ?>', 
                    id: '<?php echo $evnt['id']; ?>', 
                    requests: [<?php echo $requests; ?>],
                    rowdata: <?php echo $rowdata; ?>
                }, 
        <?php
            }
        }
        ?>    
        ], 
        eventAfterRender: function(event, element, view) {
            var eventHtml = '', eventTimeHtml = '', maskedDate = moment(event.start).format('YYYY-MM-DD');
            
            if (event.plantime != '') {
                 eventHtml += '<div class="fc-hrm-time-log-startdate" title="Төлөвлөсөн"><i class="fa fa-clock-o"></i> '+event.plantime+'</div>';
            }
            
            eventTimeHtml = '<div class="fc-hrm-time-duration" data-rowdata="'+encodeURIComponent(JSON.stringify(event.rowdata))+'" data-date="'+maskedDate+'">';
            
            if (Object.keys(event.requests).length){
                for (var i = 0; i < (event.requests).length; i++) {
                    if ((event.requests[i]['wfmstatuscode']).toLowerCase() == 'done') {
                        eventTimeHtml += '<div class="fc-hrm-time-descr-done" data-wfmcode="" style="background-color: '+event.requests[i]['wfmstatuscolor']+'" data-id="'+event.requests[i]['id']+'" data-wfmid="'+event.requests[i]['wfmstatusid']+'" title="" data-toggle="tooltip" data-placement="bottom" title="" data-original-title="'+event.requests[i]['feedbackdesc']+'" onclick="hrmTimesheetViewProcess(this, \''+maskedDate+'\', \''+event.requests[i]['id']+'\', \''+event.requests[i]['wfmstatusid']+'\');">'+event.requests[i]['feedbackdesc']+'</div>';
                    } else if ((event.requests[i]['wfmstatuscode']).toLowerCase() == 'cancel') {
                        eventTimeHtml += '<div class="fc-hrm-time-descr-cancel" data-wfmcode="" style="background-color: '+event.requests[i]['wfmstatuscolor']+'" data-id="'+event.requests[i]['id']+'" data-wfmid="'+event.requests[i]['wfmstatusid']+'" title="" data-toggle="tooltip" data-placement="bottom" title="" data-original-title="'+event.requests[i]['feedbackdesc']+'" onclick="hrmTimesheetViewProcess(this, \''+maskedDate+'\', \''+event.requests[i]['id']+'\', \''+event.requests[i]['wfmstatusid']+'\');">'+event.requests[i]['feedbackdesc']+'</div>';
                    } else if(event.requests[i]['wfmstatusid']){
                        eventTimeHtml += '<div class="fc-hrm-time-descr fc-hrm-time-wfmstatus text-nowrap" style="background-color: '+event.requests[i]['wfmstatuscolor']+'" data-wfmcode="'+(event.requests[i]['wfmstatuscode']).toLowerCase()+'" data-wfmid="'+event.requests[i]['wfmstatusid']+'" data-id="'+event.requests[i]['id']+'" title="" data-toggle="tooltip" data-placement="bottom" title="" data-original-title="'+event.requests[i]['feedbackdesc']+'" onclick="hrmTimesheetViewProcess(this, \''+maskedDate+'\', \''+event.requests[i]['id']+'\', \''+event.requests[i]['wfmstatusid']+'\');">'+event.requests[i]['feedbackdesc']+'</div>';
                    }
                }
            } else {
                eventTimeHtml += '<div class="fc-hrm-time-descr-empty"></div>';
            }
            
                eventTimeHtml += '<table>';
                    eventTimeHtml += '<tbody>';
                        
                        if (event.holidayname && event.holidayname != '') {
                            eventTimeHtml += '<tr>';
                                eventTimeHtml += '<td>'+event.holidayname+'</td>';
                                eventTimeHtml += '<td></td>';
                            eventTimeHtml += '</tr>';
                        }
                        if (event.workingtime && event.workingtime != '0' && event.workingtime != ':' && event.workingtime != '00:00') {
                            eventTimeHtml += '<tr>';
                                eventTimeHtml += '<td><a href="javascript:;" class="text-nowrap" data-toggle="tooltip" data-placement="bottom" title="Ажилласан" data-original-title="Ажилласан">Ажилласан</a></td>';
                                eventTimeHtml += '<td>'+event.workingtime+'</td>';
                            eventTimeHtml += '</tr>';
                        }
                        if (event.absenttime && event.absenttime != '0' && event.absenttime != ':' && event.absenttime != '00:00') {
                            eventTimeHtml += '<tr>';
                                eventTimeHtml += '<td><a style="color: <?php echo $this->getDataviewConfig['absenttime']; ?>" href="javascript:;" class="text-nowrap" onclick="hrmTimesheetChooseProcess(\''+maskedDate+'\', this);" data-toggle="tooltip" data-placement="bottom" title="" data-original-title="Тасалсан">Тасалсан</a></td>';
                                eventTimeHtml += '<td style="color: <?php echo $this->getDataviewConfig['absenttime']; ?>">'+event.absenttime+'</td>';
                            eventTimeHtml += '</tr>';
                        }
                        if (event.latetime && event.latetime != '0' && event.latetime != ':' && event.latetime != '00:00') {
                            eventTimeHtml += '<tr>';
                                eventTimeHtml += '<td><a style="color: <?php echo $this->getDataviewConfig['latetime']; ?>" href="javascript:;" class="text-nowrap" onclick="hrmTimesheetChooseProcess(\''+maskedDate+'\', this);" data-toggle="tooltip" data-placement="bottom" title="" data-original-title="Хоцорсон">Хоцорсон</a></td>';
                                eventTimeHtml += '<td style="color: <?php echo $this->getDataviewConfig['latetime']; ?>">'+event.latetime+'</td>';
                            eventTimeHtml += '</tr>';
                        }
                        if (event.earlytime && event.earlytime != '0' && event.earlytime != ':' && event.earlytime != '00:00') {
                            eventTimeHtml += '<tr>';
                                eventTimeHtml += '<td><a style="color: <?php echo $this->getDataviewConfig['earlytime']; ?>" href="javascript:;" class="text-nowrap" onclick="hrmTimesheetChooseProcess(\''+maskedDate+'\', this);" data-toggle="tooltip" data-placement="bottom" title="" data-original-title="Эрт явсан">Эрт явсан</a></td>';
                                eventTimeHtml += '<td style="color: <?php echo $this->getDataviewConfig['earlytime']; ?>">'+event.earlytime+'</td>';
                            eventTimeHtml += '</tr>';
                        }
                        if (event.cause4 && event.cause4 != '0' && event.cause4 != ':' && event.cause4 != '00:00') {
                            eventTimeHtml += '<tr>';
                                eventTimeHtml += '<td style="color: <?php echo issetParam($this->getDataviewConfig['cause4']); ?>"><a href="javascript:;" class="text-nowrap" data-toggle="tooltip" data-placement="bottom" title="" data-original-title="Илүү цаг">Илүү цаг</a></td>';
                                eventTimeHtml += '<td style="color: <?php echo issetParam($this->getDataviewConfig['cause4']); ?>">'+event.cause4+'</td>';
                            eventTimeHtml += '</tr>';
                        }
                        if (event.cause3 && event.cause3 != '0' && event.cause3 != ':' && event.cause3 != '00:00') {
                            eventTimeHtml += '<tr>';
                                eventTimeHtml += '<td style="color: <?php echo issetParam($this->getDataviewConfig['cause3']); ?>"><a href="javascript:;" class="text-nowrap" data-toggle="tooltip" data-placement="bottom" title="" data-original-title="Гадуур ажилласан">Гадуур ажилласан</a></td>';
                                eventTimeHtml += '<td style="color: <?php echo issetParam($this->getDataviewConfig['cause3']); ?>">'+event.cause3+'</td>';
                            eventTimeHtml += '</tr>';
                        }
                        if (event.cause5 && event.cause5 != '0' && event.cause5 != ':' && event.cause5 != '00:00') {
                            eventTimeHtml += '<tr>';
                                eventTimeHtml += '<td style="color: <?php echo issetParam($this->getDataviewConfig['cause5']); ?>"><a href="javascript:;" class="text-nowrap" data-toggle="tooltip" data-placement="bottom" title="" data-original-title="Өвчтэй">Өвчтэй</a></td>';
                                eventTimeHtml += '<td style="color: <?php echo issetParam($this->getDataviewConfig['cause5']); ?>">'+event.cause5+'</td>';
                            eventTimeHtml += '</tr>';
                        }
                        if (event.cause6 && event.cause6 != '0' && event.cause6 != ':' && event.cause6 != '00:00') {
                            eventTimeHtml += '<tr>';
                                eventTimeHtml += '<td style="color: <?php echo issetParam($this->getDataviewConfig['cause6']); ?>"><a href="javascript:;" class="text-nowrap" data-toggle="tooltip" data-placement="bottom" title="" data-original-title="Цалингүй чөлөө">Цалингүй чөлөө</a></td>';
                                eventTimeHtml += '<td style="color: <?php echo issetParam($this->getDataviewConfig['cause6']); ?>">'+event.cause6+'</td>';
                            eventTimeHtml += '</tr>';
                        }
                        if (event.cause20 && event.cause20 != '0' && event.cause20 != ':' && event.cause20 != '00:00') {
                            eventTimeHtml += '<tr>';
                                eventTimeHtml += '<td style="color: <?php echo issetParam($this->getDataviewConfig['cause20']); ?>"><a href="javascript:;" class="text-nowrap" data-toggle="tooltip" data-placement="bottom" title="" data-original-title="Цалинтай чөлөө">Цалинтай чөлөө</a></td>';
                                eventTimeHtml += '<td style="color: <?php echo issetParam($this->getDataviewConfig['cause20']); ?>">'+event.cause20+'</td>';
                            eventTimeHtml += '</tr>';
                        }
                        if (event.cause7 && event.cause7 != '0' && event.cause7 != ':' && event.cause7 != '00:00') {
                            eventTimeHtml += '<tr>';
                                eventTimeHtml += '<td style="color: <?php echo issetParam($this->getDataviewConfig['cause7']); ?>"><a href="javascript:;" class="text-nowrap" data-toggle="tooltip" data-placement="bottom" title="" data-original-title="Ээлжийн амралт">Ээлжийн амралт</a></td>';
                                eventTimeHtml += '<td style="color: <?php echo issetParam($this->getDataviewConfig['cause7']); ?>">'+event.cause7+'</td>';
                            eventTimeHtml += '</tr>';
                        }
                        if (event.cause8 && event.cause8 != '0' && event.cause8 != ':' && event.cause8 != '00:00') {
                            eventTimeHtml += '<tr>';
                                eventTimeHtml += '<td style="color: <?php echo issetParam($this->getDataviewConfig['cause8']); ?>"><a href="javascript:;" class="text-nowrap" data-toggle="tooltip" data-placement="bottom" title="" data-original-title="Томилолт">Томилолт</a></td>';
                                eventTimeHtml += '<td style="color: <?php echo issetParam($this->getDataviewConfig['cause8']); ?>">'+event.cause8+'</td>';
                            eventTimeHtml += '</tr>';
                        }
                        if (event.cause10 && event.cause10 != '0' && event.cause10 != ':' && event.cause10 != '00:00') {
                            eventTimeHtml += '<tr>';
                                eventTimeHtml += '<td style="color: <?php echo issetParam($this->getDataviewConfig['cause10']); ?>"><a href="javascript:;" class="text-nowrap" data-toggle="tooltip" data-placement="bottom" title="" data-original-title="Онлайн ажилласан">Онлайн ажилласан</a></td>';
                                eventTimeHtml += '<td style="color: <?php echo issetParam($this->getDataviewConfig['cause10']); ?>">'+event.cause10+'</td>';
                            eventTimeHtml += '</tr>';
                        }
                        if (event.cause1 && event.cause1 != '0' && event.cause1 != ':' && event.cause1 != '00:00') {
                            eventTimeHtml += '<tr>';
                                eventTimeHtml += '<td><a style="color: <?php echo issetParam($this->getDataviewConfig['cause1']); ?>" href="javascript:;" class="text-nowrap" onclick="hrmTimesheetHdrProcess(\'<?php echo $this->procId; ?>\', \''+maskedDate+'\', this);" data-toggle="tooltip" data-placement="bottom" title="" data-original-title="Бүртгэл дутуу">Бүртгэл дутуу</a></td>';
                                eventTimeHtml += '<td style="color: <?php echo issetParam($this->getDataviewConfig['cause1']); ?>">'+event.cause1+'</td>';
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
                eventHtml += '<div class="fc-hrm-time-log-date" onclick="hrmTimeSheetLogDrillByDate(this, \''+maskedDate+'\');" title="" data-toggle="tooltip" data-placement="bottom" data-original-title="Ирсэн: '+$eventintime+ ' Гарсан: ' + $eventouttime +'">'+$eventintime+ ' - ' + $eventouttime +'</div>';
            } else {
                if (event.start != null && event.charintime != '') {
                    eventHtml += '<div class="fc-hrm-time-log-date" onclick="hrmTimeSheetLogDrillByDate(this, \''+maskedDate+'\');" data-toggle="tooltip" data-placement="bottom" title="" data-original-title="Ирсэн: '+event.charintime +'">'+event.charintime+'</div>';
                }

                if (event.end != null && event.charouttime != '') {
                    eventHtml += '<div class="fc-hrm-time-log-date" onclick="hrmTimeSheetLogDrillByDate(this, \''+maskedDate+'\');" data-toggle="tooltip" data-placement="bottom" title="" data-original-title="Гарсан: '+event.charouttime +'">'+event.charouttime+'</div>';
                }
            }
            
            eventHtml += '</div>';
            
            $(element).closest('td').empty().append(eventHtml);
            
            var dateString = event.start.format("YYYY-MM-DD");
            $(view.el[0]).find('.fc-day[data-date=' + dateString + ']').css('background-color', event.rowcolor);            
        },
        eventAfterAllRender: function(view) {
            var $headerToolbar = $('.fc-header-toolbar', '.hrmlog-<?php echo $this->uniqId; ?>');
            var $headerLeft = $headerToolbar.find('> .fc-left');
            
            $headerToolbar.find('.fc-left-request').remove();
            
            <?php
            if (!isset($this->addProcessIds)) {
            ?>
            $headerLeft.after('<div class="fc-left-request">'+
                '<div class="btn-group">'+
                    '<button class="btn btn-secondary btn-sm" type="button" onclick="fullCalendarGotoDate_<?php echo $this->uniqId; ?>();">'+plang.get("calendar_globe_refresh")+'</button>'+
                    '<button class="btn btn-secondary btn-sm" type="button" onclick="hrmTimesheetHdrProcess(\'<?php echo $this->procId; ?>\', \'\', this);">Нэмэх</button>'+
                '</div>'+
            '</div>');
            <?php
            } else {
            ?>
            $headerLeft.after('<div class="fc-left-request">'+
                '<div class="btn-group">'+
                    '<button class="btn btn-secondary btn-sm" type="button" onclick="fullCalendarGotoDate_<?php echo $this->uniqId; ?>();">'+plang.get("calendar_globe_refresh")+'</button>'+
                    '<div class="btn-group">'+
                        '<button type="button" class="btn btn-secondary btn-sm btn-icon dropdown-toggle" data-toggle="dropdown">'+plang.get("Hr_19")+
                        '</button>'+
                        '<div class="dropdown-menu dropdown-menu-left">'+
                            <?php
                            foreach ($this->addProcessIds as $addProcessRow) { 
                            ?>
                            '<a href="javascript:;" class="dropdown-item" onclick="hrmTimesheetHdrProcess(\'<?php echo $addProcessRow['id']; ?>\', \'\', this);"><i class="icon-arrow-right5"></i> <?php echo $addProcessRow['title']; ?></a>'+
                            <?php
                            }
                            ?>
                        '</div>'+
                    '</div>'+
                    
                '</div>'+
            '</div>');
            <?php
            }
            ?>
        }    
    });
    
    $('.hrmlog-<?php echo $this->uniqId; ?>').find('style[type="text/css"]').append('#calendar-<?php echo $this->uniqId; ?> .fc-month-view .fc-scroller { height:' + ($(window).height() - 190) + 'px !important; overflow: auto !important; }'); 
    $('.hrmlog-<?php echo $this->uniqId; ?>').find('style[type="text/css"]').append('#load-news-dataview-<?php echo $this->uniqId; ?> .right-sidebar-content-for-resize { height:' + ($(window).height() - 485) + 'px !important; overflow: auto !important; }'); 
    
    $(window).bind('resize', function() {    
        $('.hrmlog-<?php echo $this->uniqId; ?>').find('style[type="text/css"]').append('#calendar-<?php echo $this->uniqId; ?> .fc-month-view .fc-scroller { height:' + ($(window).height() - 190) + 'px !important; overflow: auto !important; }'); 
        $('.hrmlog-<?php echo $this->uniqId; ?>').find('style[type="text/css"]').append('#load-news-dataview-<?php echo $this->uniqId; ?> .right-sidebar-content-for-resize { height:' + ($(window).height() - 485) + 'px !important; min-height: 130px !important; overflow: auto !important; }');         
    });
    
    if ($().tableHeadFixer) {
        $('#log-sidebar-<?php echo $this->uniqId; ?>').tableHeadFixer({'head': true, 'foot': true, 'left': 4, 'z-index': 9}); 
    }    
    
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
            $('#load-news-dataview-<?php echo $this->uniqId; ?>').find('.meta-toolbar > span').css('color', 'white').addClass('pl12').removeClass('text-uppercase');
        },
        error: function() {
            $('#log-sidebar-<?php echo $this->uniqId; ?>').parent().css('max-height', '100%');
        }        
    });       

    <?php if (!isset($this->addProcessIds)) { ?>
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
                callback: function (key, opt) {
                },
                items: contextMenuData
            };
            
            return options;            
        }
    });    
    <?php } ?>

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
                                                    $this.remove();
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
    
    $('[data-toggle="tooltip"]').tooltip();
    
    var $fcToday = $('.fc-content-skeleton').find('.fc-today');
    
    if ($fcToday.length) {
        $('.fc-day-grid-container').stop().animate({
            scrollTop: eval($fcToday.offset().top - 600)
        }, 1500);
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
                            holidayname: e.holidayname, 
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
            var dataRow = {'id': id, 'wfmstatusid': wfmId};
            var $departmentIdElem = $dialog.find('[data-path="departmentId"]');

            if (data.isDialogSize === 'auto') {
                dialogWidth = 1200;
                dialogHeight = 'auto';
            }
            
            if ($departmentIdElem.length && $departmentIdElem.val() != '') {
                dataRow.departmentId = $departmentIdElem.val();
            }

            $.ajax({
                type: 'post',
                url: 'mdobject/getRowWfmStatusForm',
                data: {
                    metaDataId: '<?php echo $this->calendarDvId; ?>', 
                    uniqId: getUniqueId(1), 
                    dataRow: dataRow
                },
                dataType: 'json',
                success: function(data){
                    $dialog.find('#wsForm').append(data.Html);
                    var $tWfmtable = $dialog.find('#wsForm').find('.wfm-header-table-<?php echo $this->calendarDvId; ?>');
                    $dialog.find('#wsForm').find('.wfm-header-table-<?php echo $this->calendarDvId; ?>').find('thead > tr > td:eq(4)').hide();
                    $dialog.find('#wsForm').find('.wfm-header-table-<?php echo $this->calendarDvId; ?>').find('thead > tr > td:eq(5)').hide();
                    $dialog.find('#wsForm').find('.wfm-header-table-<?php echo $this->calendarDvId; ?>').find('tbody > tr > td:eq(4)').hide();
                    $dialog.find('#wsForm').find('.wfm-header-table-<?php echo $this->calendarDvId; ?>').find('tbody > tr > td:eq(5)').hide();
                },
                error: function(){
                    alert('Error');
                }
            });            

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
    
    if (rowdata == 'undefined') {
        rowdata = '';
    } else {
        var rowObj = JSON.parse(rowdata);
        if (rowObj.hasOwnProperty('wfmstatusid')) {
            delete rowObj.wfmstatusid;
            rowdata = JSON.stringify(rowObj);
        }
    }
    
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
    var changeDate = $('#calendar-<?php echo $this->uniqId; ?>').fullCalendar('getDate').toDate();
    $.ajax({
        type: 'post',
        url: 'mdwidget/runWidget',
        data: {
            widgetCode: 'widgethrmtimesheetlogv2', 
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

var selectedDateElement;

function hrmTimesheetChooseProcess(currentDate, elem) {
    <?php
    if (!isset($this->addProcessIds)) {
    ?>
            
    hrmTimesheetHdrProcess('<?php echo $this->procId; ?>', currentDate, elem);
    
    <?php
    } else {
    ?>
    
    selectedDateElement = elem;
    var $dialogName = 'dialog-timesheet-calendar-chooseprocess'; 
    if (!$($dialogName).length) {
        $('<div id="' + $dialogName + '"></div>').appendTo('body');
    }
    var $dialog = $('#' + $dialogName);
    var addProcessIds = <?php echo json_encode($this->addProcessIds); ?>, $listHtml = [];
    
    for (var i in addProcessIds) { 
        
        var activeClass = '';
        
        if (i == 0) {
            activeClass = ' process-hk-active';
        } 
        
        $listHtml.push('<div class="process-hk-parent"><a href="javascript:;" class="process-hk'+activeClass+'" onclick="hrmTimesheetSelectedProcess(' + addProcessIds[i]['id'] + ', \''+currentDate+'\');" style="margin: 0 0 -1px 0;padding: 10px 0 8px 10px;">' + addProcessIds[i]['title'] + '</a></div>');
    } 

    $dialog.empty().append($listHtml.join(''));
    $dialog.dialog({
        cache: false,
        resizable: true,
        bgiframe: true,
        autoOpen: false,
        title: '',
        dialogClass: 'altn-custom-dialog', 
        width: 400,
        height: 'auto',
        modal: true,
        closeOnEscape: isCloseOnEscape, 
        open: function() {
            disableScrolling();
            var $thisDialogButton = $(this).parent();
            $thisDialogButton.on('keydown', function(e){
                var keyCode = (e.keyCode ? e.keyCode : e.which);
                if (keyCode == 38) { /* up */
                    var $thisButton = $thisDialogButton.find('a.process-hk-active');
                    
                    if ($thisButton.length) {
                        var $thisParent = $thisButton.closest('.process-hk-parent').prevAll('.process-hk-parent:eq(0)');
                    
                        if ($thisParent.length) {
                            $thisDialogButton.find('a.process-hk-active').removeClass('process-hk-active');
                            $thisParent.find('a.process-hk').addClass('process-hk-active').focus();
                        }
                    } else {
                        $thisDialogButton.find('a.process-hk:eq(0)').addClass('process-hk-active').focus();
                    }
                    
                } else if (keyCode == 40) { /* down */
                    var $thisButton = $thisDialogButton.find('a.process-hk-active');
                    var $thisParent = $thisButton.closest('.process-hk-parent').nextAll('.process-hk-parent:eq(0)');
                    
                    if ($thisParent.length) {
                        $thisDialogButton.find('a.process-hk-active').removeClass('process-hk-active');
                        $thisParent.find('a.process-hk').addClass('process-hk-active').focus();
                    }
                }
            });
        }, 
        close: function() {
            enableScrolling();
            $dialog.empty().dialog('destroy').remove();
        }
    });
    $dialog.dialog('open');
    
    <?php
    }
    ?>
}
function hrmTimesheetSelectedProcess(procId, date) {
    $('#dialog-timesheet-calendar-chooseprocess').dialog('close');
    hrmTimesheetHdrProcess(procId, date, selectedDateElement);
}
function hrmTimeSheetLogDrillByDate(elem, date) {
    <?php 
    if ($hrmCalendarDrillProcessId) {
    ?>
    bpCallDataViewByExp($(elem), $(elem), '<?php echo $hrmCalendarDrillProcessId; ?>', date+'@filterDate', 'width:1000');
    <?php
    }
    ?>
    return;
}
</script>