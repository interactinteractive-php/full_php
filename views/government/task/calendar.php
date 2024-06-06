<div class="mt-3 conf-room conf-room-<?php echo $this->uniqId; ?>">
<div class="row pl-2">
        <div class="p-2 mr-2" style="width: 280px;background: #dbe4f7;border-radius: 3px;">
            <div class="mb-3" id="external-events">
                <div class="fc-events-container mb-3">
                    <h4 class="text-uppercase font-size-14 mt-1 mb14">Хайлт</h4>
                   
                        <div class="mb-2 dv-criteria-row" id="accordion4-1579055688430">
                            <h4 class="panel-title">Эхлэх огноо</h4>
                            <div id="collapse_3_startDate_1579055688430" aria-expanded="true">
                                <div class="dateElement input-group" data-section-path="startDate"><input type="text" name="param[startDate]" class="form-control form-control-sm dateInit" data-path="startDate" data-field-name="startDate" data-isclear="" value="" placeholder="Эхлэх огноо"><span class="input-group-btn"><button tabindex="-1" onclick="return false;" class="btn"><i class="fa fa-calendar"></i></button></span></div><input type="hidden" name="criteriaCondition[startDate]" value="=">                            </div>
                        </div>
                    
                    
                        <div class="mb-2 dv-criteria-row" id="accordion4-1579055688430">
                            <h4 class="panel-title">Дуусах огноо</h4>
                            <div id="collapse_3_startDate_1579055688430" aria-expanded="true">
                                <div class="dateElement input-group" data-section-path="startDate"><input type="text" name="param[startDate]" class="form-control form-control-sm dateInit" data-path="startDate" data-field-name="startDate" data-isclear="" value="" placeholder="Дуусах огноо"><span class="input-group-btn"><button tabindex="-1" onclick="return false;" class="btn"><i class="fa fa-calendar"></i></button></span></div><input type="hidden" name="criteriaCondition[startDate]" value="=">                            </div>
                        </div>
                    
                    <div class="form-group">
                        <select id="" name="" class="form-control">
                            <option value="1">Төрөл</option>
                            <option value="2">09</option>
                            <option value="3">10</option>
                            <option value="3">11</option>
                            <option value="3">12</option>
                        </select>
                    </div>
                    <h4 class="text-uppercase font-size-14 mt-1 mb14">Төлөв</h4>
                    <div class="form-check">
                        <label class="form-check-label">
                            <input type="checkbox" class="form-check-input-styled-primary" checked data-fouc>
                            Бүгд
                        </label>
                    </div>
                    <div class="form-check">
                        <label class="form-check-label">
                            <input type="checkbox" class="form-check-input-styled-primary" data-fouc>
                            Шийдвэрлэсэн
                        </label>
                    </div>
                    <div class="form-check">
                        <label class="form-check-label">
                            <input type="checkbox" class="form-check-input-styled-primary" data-fouc>
                            Хойшлуулсан
                        </label>
                    </div>
                    <div class="form-check">
                        <label class="form-check-label">
                            <input type="checkbox" class="form-check-input-styled-primary" data-fouc>
                            Баталгаажсан
                        </label>
                    </div>
                    <div class="form-check">
                        <label class="form-check-label">
                            <input type="checkbox" class="form-check-input-styled-primary" data-fouc>
                            Тодруулах
                        </label>
                    </div>
                    <div class="form-check">
                        <label class="form-check-label">
                            <input type="checkbox" class="form-check-input-styled-primary" data-fouc>
                            Буцаасан
                        </label>
                    </div>
                </div>
            </div>
        </div>
        <div class="col">
            <div class="fullcalendar-external" id="calendar-<?php echo $this->uniqId; ?>"></div>
        </div>
    </div>
</div>
<style type="text/css">

    .ui-widget[aria-describedby="dialog-dataview-selectable-1565070936581248"] 

    {
        z-index: 1052 !important; 
    }

    .ui-widget-overlay[aria-describedby="dialog-dataview-selectable-1565070936581248"]
    {
        z-index: 1051 !important; 
    }

    #calendar-<?php echo $this->uniqId; ?> .spinner {
        width: initial !important;
        height: initial !important;
    }

    .conf-room-<?php echo $this->uniqId; ?> {
        background: #FFF;
    }
    .conf-room-<?php echo $this->uniqId; ?> .dropdown-item.form-check .form-check-label {
        padding-left: 1rem;
    }
    .conf-room-<?php echo $this->uniqId; ?> .btn-group {
        width: 235px !important;
    }
    .conf-room-<?php echo $this->uniqId; ?> .multiselect.btn-light,
    .conf-room-<?php echo $this->uniqId; ?> .input-group-text {
        border: 3px solid #FFD071;
        border-radius: 0;
    }
    .conf-room-<?php echo $this->uniqId; ?> .multiselect.btn-light {
        border-left-width: 1px;
    }
    .conf-room-<?php echo $this->uniqId; ?> .input-group-text {
        border-right: 0;
    }
    .conf-room-<?php echo $this->uniqId; ?> .btn-light:focus,
    .conf-room-<?php echo $this->uniqId; ?> .btn-light:hover,
    .conf-room-<?php echo $this->uniqId; ?> .btn-light:not([disabled]):not(.disabled).active,
    .conf-room-<?php echo $this->uniqId; ?> .btn-light:not([disabled]):not(.disabled):active,
    .conf-room-<?php echo $this->uniqId; ?> .show > .btn-light.dropdown-toggle {
        border-color: #FFD071;
    }
    .conf-room-<?php echo $this->uniqId; ?> .search-btn {
        height:41px;
        border: 3px solid #FFD071;
        border-left:0;
        border-radius: 0 3px 3px 0;
    }
    .conf-room-<?php echo $this->uniqId; ?> input.calendar {
        height:41px;
        border: 3px solid #FFD071;
        border-right: 0;
        border-left-width: 1px;
        outline: 0;
        padding: 0px 10px;
    }
    .conf-room-<?php echo $this->uniqId; ?> .fc-center {
        margin-left: 260px !important;
    }

    .fc-more-popover {
        width: 257px;
    }

    .fc-basic-view .fc-body .fc-row {
        height: 280px !important;
    }

    .fc-row fc-week .fc-widget-content {
        height: 300px !important;
    }
</style>

<script type="text/javascript">
    $(document).ready(function () {
        Core.init();
        reload<?php echo $this->uniqId; ?>('');
    });
    
    function addTask(){
        transferProcessAction('', '1579055688430', '1579055689406', '200101010000011', 'toolbar', this, {callerType: 'IA_TM_TASK_ALLSESSION_LIST'}, undefined, undefined, undefined, undefined, '');
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

    function reload<?php echo $this->uniqId; ?>(type) {
        if (typeof type !== 'undefined' && type === 'refetchEvents') {
            $('#calendar-<?php echo $this->uniqId; ?>').fullCalendar('refetchEvents');
            return;
        }
        
        if (type === 'filter') {
            $('#calendar-<?php echo $this->uniqId; ?>').fullCalendar('refetchEvents');
            return;
        }
        
        if (!$().fullCalendar || typeof Switchery === 'undefined' || !$().draggable) {
            console.warn('Warning - fullcalendar.min.js, switchery.min.js or jQuery UI is not loaded.');
            return;
        }

        blockContent_<?php echo $this->uniqId ?>('#calendar-<?php echo $this->uniqId; ?>');
        
        $('#calendar-<?php echo $this->uniqId; ?>').fullCalendar({
            // buttonText: {
            //     prev: '<',
            //     next: '>'
            // },
            customButtons: {
                add_event: {
                    text: 'Нэмэх',
                    click: function () {
                        addTask();
                    }
                },
                edit_event: {
                    text: 'Засах',
                    click: function () {
                        callomsconferenceAddForm('');
                    }
                }
            },
            header: {
                left: 'add_event',
                center: 'prev title next',
                right: 'month,agendaWeek,agendaDay,today'
            },
//            editable: true,
//            droppable: true,
            defaultDate: moment('<?php echo Date::currentDate('Y-m-d'); ?>'),
            defaultView: 'agendaWeek',
            fixedWeekCount: false,
            allDaySlot: true,
            allDay: true,
            minTime: '08:00:00',
            maxTime: '18:00:00',
            weekends: false,
            eventLimit: 4, // for all non-TimeGrid view
            eventLimitText: plang.get('more'), //sets the text for more events
            locale: 'mn',
            timeFormat: 'H(:mm)',
            agenda: 'h:mm{ - h:mm}', // 5:00 - 6:30
            selectable: true,
            views: {
                timeGrid: {
                    agenda: 0 // adjust to 6 only for timeGridWeek/timeGridDay
                },
                month: {
                    titleFormat: 'YYYY он MMMM'
                }
            },
            events: function (start, end, timezone, callback<?php echo $this->uniqId; ?>) {
                if (typeof param === "undefined") {
                    param = {};
                }

                var data = $.extend(param, {
                    metaDataId: '<?php echo $this->metaDataId ?>'
                });
                
                $.ajax({
                    url: 'mdobject/dataViewDataGrid/false',
                    dataType: 'json',
                    type: 'POST',
                    data: data,
                    success: function (data) {
                        if (typeof data.rows !== "undefined") {
                            callback<?php echo $this->uniqId; ?>(data.rows);    
                        }
                        Core.unblockUI('#calendar-<?php echo $this->uniqId; ?>');
                    }
                });
            },
            eventAfterRender: function (event, element, view) {
                
                var eventHtml = '';
                var $style = $(element).attr('style');
                var $class = $(element).attr('class');
                var editButton = '';
                var deleteButton = '';
                var approveButton = '';
                var cancelButton = '';
                var eventj = event;
                var emptyTime = '';
                var emptyTimeWeek = '';
                
                
                $.each(eventj, function (index, row) {
                    if (typeof row !== 'string') {
                        delete eventj[index];
                    }
                });
               
                console.log(event);
                switch (view.type) {
                    case 'month':
                        eventHtml = '<div class="hover-action-btns">' +
                                editButton + deleteButton + approveButton + cancelButton
                                + '</div>' 
                                + '<div '+emptyTime+' style="background-color:' + event.wfmstatuscolor  + ';border-color:' + event.wfmstatuscolor + ';">'
                                + '<a href="javascript:void(0);">'
                                + '<div class="fc-content d-flex flex-row align-items-center">'
                                + '<div class="mr-2">'
                                + '<button type="button" title="' + event.tooltiptext + '" class="btn btn-light btn-icon rounded-round status-btn" style="background-color: '+event.color+';">'
                                + '<i class="icon-gear"></i>'
                                + '</button>'
                                + '</div>'
                                + '<div>'
                                + '<div class="fc-title font-weight-bold text-white">'+ event.taskcode +' - ' + event.taskname + '</div>'
                                + '<div class="fc-title text-white">' + event.wfmstatusname + ' Дуусах хугацаа : ' + event.timeline +'</div>'
                                + '</a>'
                                + '</div>';

                        $(element).empty().append(eventHtml).promise().done(function () {
                            $(this).closest('a').attr('style', 'background-color:' + event.statuscolor  + ' !important;border-color:' + event.statuscolor + ' !important;');
                        });

                        break;
                    case 'agendaWeek':
//                        eventHtml = '<div class="tooltipright fc-content d-flex flex-row align-items-center">'
//                                + '<div class="mr-2">'
//                                + '<button type="button" class="btn btn-light btn-icon rounded-round status-btn">'
//                                + '<i class="' + event.icon + '"></i>'
//                                + '</button>'
//                                + '</div>'
//                                + '<div>'
//                                + '<div class="fc-title font-weight-bold">' + event.confname + '</div>'
//                                + '<div class="fc-title">' + event.starttime + '</div>';

                        $(element).attr('title', event.confname + '  ' +event.starttime).empty().append('<label><i class="' + event.icon + '"></i>' + event.taskcode + ' - ' + event.taskname + '</label>');
                        $(element).attr('onclick', emptyTimeWeek);
                        
                        break;
                    case 'agendaDay':
                        $(element).attr('title', event.confname + ' '+event.starttime);
                        $(element).attr('href','javascript:;');
                        $(element).empty().append('<div><i class="' + event.icon + '"></i> ' + event.confname + ' ' + event.starttime + '</div>').promise();
                        $(element).attr('onclick',emptyTimeWeek);
                        break;
                    default:
                        eventHtml = '<div id="id-' + event.id + '" class="fc-day-grid-event fc-h-event fc-event fc-start fc-end fc-draggable " style="background-color:' + event.statuscolor + ';border-color:' + event.statuscolor + '">'
                                + '<div class="hover-action-btns">' + 
                                editButton + deleteButton + approveButton + cancelButton + '</div>' 
                                + '<a href="javascript:void(0);">'
                                + '<div class="fc-content d-flex flex-row align-items-center">'
                                + '<div class="mr-2">'
                                + '<button type="button" title="' + event.tooltiptext + '" class="btn btn-light btn-icon rounded-round status-btn">'
                                + '<i class="' + event.icon + '"></i>'
                                + '</button>'
                                + '</div>'
                                + '<div>'
                                + '<div class="fc-title font-weight-bold"><a href="javascript:;" '+emptyTime+' >' + event.confname + '</a></div>'
                                + '<div class="fc-title">' + event.starttime + '</div>'
                                + '</a>'
                                + '</div>';
                        $(element).closest('.fc-event-container').find('a.fc-event').hide();
                        $(element).closest('.fc-event-container').append(eventHtml).promise().done(function () {

                        });
                        break;
                }

            },
            dayClick: function (date) {
            },
            select: function (startDate, endDate) {
                $('#calendar-<?php echo $this->uniqId; ?>').fullCalendar('gotoDate', startDate.format(), endDate.format());
            },
            eventDrop: function (info, dd, dd1, dd3) {
                if (!confirm("Та өөрчлөхдөө итгэлтэй байна уу?")) {
                    var filterStartDate = changeDateTimeFormat<?php echo $this->uniqId; ?>(new Date(info.start.unix() * 1000));
                    var filterEndDate = changeDateTimeFormat<?php echo $this->uniqId; ?>(new Date(info.end.unix() * 1000));
                    $('#calendar-<?php echo $this->uniqId; ?>').fullCalendar('refetchEvents');
                }
            }
        });
    }

    function blockContent_<?php echo $this->uniqId ?>(mainSelector) {
        $(mainSelector).block({
            message: '<i class="icon-spinner4 spinner"></i>',
            centerX: 0,
            centerY: 0,
            overlayCSS: {
                backgroundColor: '#fff',
                opacity: 0.8,
                cursor: 'wait'
            },
            css: {
                width: 16,
                top: '15px',
                left: '',
                right: '15px',
                border: 0,
                padding: 0,
                backgroundColor: 'transparent'
            }
        });
    }

</script>

<style type="text/css">

    .conf-room-<?php echo $this->uniqId ?> .hover-action-btns .btn { 
        padding: 0px 4px;
        border-radius: 0;
    }

    .conf-room-<?php echo $this->uniqId ?> .hover-action-btns {
        position: relative;
        height: 15px;
        top: 3px;
        float: right;
        right: -9px;
        display: none;
    }

    .conf-room-<?php echo $this->uniqId ?> a.fc-event:hover .hover-action-btns,
    .conf-room-<?php echo $this->uniqId ?> div.fc-event:hover .hover-action-btns {
        display: block !important;
    }

    .conf-room-<?php echo $this->uniqId ?> .data-tooltip {
        display:inline-block;
        position:relative;
        text-align:left;
    }

    .conf-room-<?php echo $this->uniqId ?> .data-tooltip h5 {
        padding: 10px;
        border-bottom: 1px dashed #FFF;
    }

    .conf-room-<?php echo $this->uniqId ?> .data-tooltip .tooltipright {
        min-width:293px;
        max-width:293px;
        top:50%;
        left:100%;
        margin-left:20px;
        transform:translate(0, -50%);
        padding:0;
        color:#EEEEEE;
        background-color:#444444;
        font-weight:normal;
        font-size:13px;
        border-radius:8px;
        position:absolute;
        z-index:99999999;
        box-sizing:border-box;
        box-shadow:0 1px 8px rgba(0,0,0,0.5);
        visibility:hidden; opacity:0; transition:opacity 0.8s;
    }

    .conf-room-<?php echo $this->uniqId ?> .data-tooltip:hover .tooltipright {
        visibility:visible; opacity:1;
    }

    .conf-room-<?php echo $this->uniqId ?> .data-tooltip .tooltipright label {
        width:400px;
        border-radius:8px 8px 0 0;
    }

    .conf-room-<?php echo $this->uniqId ?> .data-tooltip .text-content {
        padding:10px 20px;
    }

    .conf-room-<?php echo $this->uniqId ?> .data-tooltip .tooltipright i {
        position:absolute;
        top:50%;
        right:100%;
        margin-top:-12px;
        width:12px;
        height:24px;
        overflow:hidden;
    }
    .conf-room-<?php echo $this->uniqId ?> .data-tooltip .tooltipright i::after {
        content:'';
        position:absolute;
        width:12px;
        height:12px;
        left:0;
        top:50%;
        transform:translate(50%,-50%) rotate(-45deg);
        background-color:#444444;
        box-shadow:0 1px 8px rgba(0,0,0,0.5);
    }

    .custom_border_bottom{
        /*border-bottom: 1px solid red;*/
    }
    
    #tableAgree td, .table th {
        border:none;
    }
    
    .fc-time-grid .fc-slats td {
    height: 2.5em !important;
    }
</style>