<script type="text/javascript">
    feather.replace();
    
    var $data_<?php echo $this->uniqId ?> = [];
    var doubleClick_<?php echo $this->uniqId; ?> = false;
    var today<?php echo $this->uniqId; ?> = false;
    var $mainSelector<?php echo $this->uniqId; ?> = $('.dashboard<?php echo $this->uniqId ?>');
    var chart1color<?php echo $this->uniqId; ?>, chart2color<?php echo $this->uniqId; ?>, chart3color<?php echo $this->uniqId; ?>;
    var chart1percent<?php echo $this->uniqId; ?>, chart2percent<?php echo $this->uniqId; ?>, chart3percent<?php echo $this->uniqId; ?>; 

    $(document).ready(function() {
        console.clear();
        $(".main-table").clone(true).appendTo('#table-scroll-ex').addClass('clone'); 
        Core.initFancybox($('.list-view-photo'));
        amChartMinify.init();
        
        <?php 
        if (Config::getFromCache('isNotaryServer')) { ?>
        
            AmCharts.makeChart(
                "chartdiv_pie2", {
                "type": "pie",
                "balloonText": "[[title]]<br><span style='font-size:14px'><b>[[value]]</b> ([[percents]]%)</span>",
                "innerRadius": 60,
                "colors": [
                    "#708dde", 
                    "#7de83e",
                    "#fdc427"
                ],
                "labelsEnabled": false,
                "labelColorField": "#FF5F5F",
                "labelTickAlpha": 0,
                "outlineAlpha": 1,
                "autoResize": true,
                "width": '300',
                "align" : 'left',
                "height": '300',
                "outlineThickness": 5,
                "titleField": "gendername",
                "valueField": "gendercount",
                "allLabels": [],
                "balloon": {},
                "legend": {
                    "enabled": true,
                    "useGraphSettings": false,
                    "position": "right",
                    "markerType": "circle"
                },
                "titles": [],
                "dataProvider": <?php echo $this->genderChart ?>
            });

            var chart = AmCharts.makeChart( "chartdiv", {
                "type": "serial",
                "theme": "none",
                "dataProvider": <?php echo $this->notariasAge ?>,
                "valueAxes": [ {
                  "gridColor": "#FFFFFF",
                  "gridAlpha": 0,
                  "dashLength": 0,
                  "axisColor": "#fff"
                } ],
                "gridAboveGraphs": true,
                "startDuration": 1,
                "graphs": [ {
                  "balloonText": "[[category]]: <b>[[value]]</b>",
                  "fillAlphas": 0.8,
                  "lineAlpha": 0.2,
                  "type": "column",
                  "valueField": "count",
                  "fillColorsField": "color"
                } ],
                "chartCursor": {
                  "categoryBalloonEnabled": false,
                  "cursorAlpha": 0,
                  "zoomable": false
                },
                "categoryField": "age",
                "categoryAxis": {
                  "gridPosition": "start",
                  "gridAlpha": 0,
                  "tickPosition": "start",
                  "tickLength": 20,
                  "autoWrap": true,
                  "axisColor": "#fff"
                },
                "export": {
                    "enabled": true
                }
            });

            var chart = AmCharts.makeChart("notariasstatus", {
                "theme": "none",
                "type": "serial",
                    "startDuration": 2,
                "dataProvider": <?php echo $this->notariasStatus ?>,
                "graphs": [{
                    "balloonText": "[[category]]: <b>[[value]]</b>",
                    "fillColorsField": "color",
                    "fillAlphas": 1,
                    "lineAlpha": 0.1,
                    "type": "column",
                    "valueField": "cnt",
                    "customBulletField": "icon",
                    "bulletOffset": 10,
                    "bulletSize": 52,
                }],
                "depth3D": 20,
                    "angle": 30,
                "chartCursor": {
                    "categoryBalloonEnabled": false,
                    "cursorAlpha": 0,
                    "zoomable": false
                },
                "categoryField": "currrentstatusname",
                "categoryAxis": {
                    "gridPosition": "start",
                    "labelRotation": 0,
                    "autoWrap": true
                },
                "export": {
                    "enabled": true
                 }
              });
          
        <?php } ?>
        
        $('#calendar-<?php echo $this->uniqId ?>').fullCalendar({
            height: 'auto',
            contentHeight: 500,
            defaultView: 'month',
            fixedWeekCount: false,
            allDaySlot: true,
            allDay: false,
            minTime: '08:00:00',
            maxTime: '18:00:00',
            weekends: true,
            eventLimit: 2, // for all non-TimeGrid view
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
            events: function (start, end, timezone, callback) {
                var defaultCriteriaData = '';
                var filterStartDate = changeDateTimeFormat<?php echo $this->uniqId; ?>(new Date(start.unix() * 1000));
                var filterEndDate = changeDateTimeFormat<?php echo $this->uniqId; ?>(new Date(end.unix() * 1000));
                defaultCriteriaData = 'param%5BfilterStartDate%5D=' + filterStartDate + '&criteriaCondition%5BfilterStartDate%5D=%3D&param%5BfilterEndDate%5D=' + filterEndDate + '&criteriaCondition%5BfilterEndDate%5D=%3D&criteriaTemplates=&criteriaTemplateName=&criteriaTemplateDescription=&inputMetaDataId=<?php echo issetParam($this->layoutPositionArr['pos_6_dvid'])?>';

                if (typeof param === "undefined") {
                    param = {};
                }
                
                <?php if (Config::getFromCache('isNotaryServer')) { ?>
                    var calenderMeta = '1592355418142';
                <?php }  else { ?>
                    var calenderMeta = '1564466818975';
                <?php } ?>
                
                var data = $.extend(param, {
                    metaDataId: calenderMeta,
                    filterStartDate: filterStartDate,
                    filterEndDate: filterEndDate,
                    defaultCriteriaData: defaultCriteriaData
                });

                $.ajax({
                    url: 'mdobject/dataViewDataGrid/false',
                    dataType: 'json',
                    type: 'POST',
                    data: data,
                    success: function (data) {
                        
                        if (typeof data.rows !== "undefined") {
                            var $calendarData = [];
                            $.each(data.rows, function(index, row) {
                                $data_<?php echo $this->uniqId ?>[row['caldate']] = row;

                                var $data = row;
                                
                                $data['start'] = row.balancedate;
                                $data['end'] = row.startdate;
                                $calendarData.push($data);

                                if (row['caldate'] == '<?php echo $this->currentDate ?>') {
                                    
                                    $('.calendar-<?php echo $this->uniqId ?> p.font-italic').empty().append(row['caldate']);
                                    var $charintime = (row['charintime']) ? row['charintime'] : '--:--';
                                    var $charouttime = (row['charouttime']) ? row['charouttime'] : '--:--';
                                    var $cleantime = (row['cleantime']) ? row['cleantime'] : '--:--';

                                    $('.work<?php echo $this->uniqId ?>').find('.intime').empty().append($charintime);
                                    $('.work<?php echo $this->uniqId ?>').find('.outtime').empty().append($charouttime);
                                    $('.work<?php echo $this->uniqId ?>').find('.cleantime').empty().append($cleantime);
                                    
                                }
                            });

                            callback($calendarData);
                            //$('#countt').html(data.total);
                        }

                    }
                });
            },
            eventAfterRender: function (event, element, view) {
                var startDate = moment(event.start).format('YYYY-MM-DD');
                var eventj = event;

                $.each(eventj, function (index, row) {
                    if (typeof row !== 'string') {
                        delete eventj[index];
                    }
                });

                var $addinHtml = 'data-rowdata="'+ htmlentities(JSON.stringify(eventj), 'ENT_QUOTES', 'UTF-8') +'" target-date="'+ startDate +'"';

                var $dayCell = element.closest('.fc-row').find('td[data-date="'+startDate+'"].fc-day-top');
                var $addClass = (eventj.cause1 && event.cause1 !== '00:00') ? 'status-warning' : '';
                $addClass = (eventj.cause13 && event.cause13 !== '00:00') ? 'status-danger' : $addClass;
                var $preCell = '';

                if (eventj.icon) {
                    if(eventj.tooltipshow === "1") {
                        $dayCell.qtip({
                            content: {
                                text: function(event, api) {
                                    // var rowData = JSON.parse($(api.elements.target[0]).attr('data-taskdata'));
                                    var content = '<div class="qtip-taskdatacard pb0 mb0 border-0 shadow-0">' 
                                                    + '<div class="card-body">' 
                                                        + '<div class="d-sm-flex align-item-sm-center flex-sm-nowrap">' 
                                                            + '<div>' 
                                                                + '<h6 class="text-primary mb-1 font-family-Oswald" title="'+eventj.booktypename+'">'+ eventj.booktypename +'</h6>'
                                                                + '<p>'+eventj.feedbackdesc+'</p>'
                                                                + '<span>Төлөв: <b>'+ eventj.wfmstatusname +'</b>'
                                                                + '</span>'
                                                            + '</div>'
                                                        + '</div>' 
                                                        + '<div class="d-sm-flex align-item-sm-center flex-sm-nowrap mt10 ">'
                                                            + '<div class="pt10 w-100" style="border-top: 1px solid #e5e5e5;">'
                                                                + '<label class="mb-0"><!--<i class="fa fa-calendar"></i>-->Огноо: <b>' +  eventj.bookinfo + '</b></label>';
                                        content += (typeof eventj.reqstarttime !== 'undefined' ?  '<label class="mb-0 ml-3"><!--<i class="fa fa-calendar"></i>-->Эхлэх цаг: <b>' +  eventj.reqstarttime + '</b></label>' : '');
                                        content += (typeof eventj.reqendtime !== 'undefined' ?  '<label class="mb-0 ml-3"><!--<i class="fa fa-calendar"></i>-->Дуусах цаг: <b>' +  eventj.reqendtime + '</b></label>' : '');
                                                        content += '</div>'
                                                        + '</div>'
                                                    + '</div>' 
                                                + '</div>';
                                    return content;
                                }
                            },
                            position: {
                                effect: false,
                                my: 'bottom center',
                                at: 'top center',
                                viewport: $(window) 
                            },
                            show: {
                                effect: false, 
                                delay: 700
                            },
                            hide: {
                                effect: false, 
                                fixed: true,
                                delay: 70
                            }, 
                            style: {
                                classes: 'qtip-bootstrap',
                                width: 500, 
                                tip: {
                                    width: 12,
                                    height: 7
                                }
                            }
                        });
                    }
                    
                    if (eventj.taskicon == 'dot') {
                        $preCell = '<div data-one="1" class="eventj-icon"><i class="icon-primitive-dot text-warning"></i></div>';
                    } else {
                        $preCell = (eventj.tooltipshow === '1') ? '<div style="width: 100%;display:flex;position: absolute;bottom: 1px;left:3px;background: #4caf50; height: 3px;"></div>' : '<div style="width: 0;display:flex;position: absolute;top: 1px;left:3px;">'+eventj.icon+'</div>';
                    }
                }
                
                $dayCell.find('[data-one="1"]').remove();
                if (eventj.holidayname) {
                    $dayCell.qtip({
                        content: {
                            text: function(event, api) {
                                var content = '<div class="qtip-taskdatacard pb0 mb0 border-0 shadow-0">' 
                                                + '<div class="card-body">' 
                                                    + '<div class="d-sm-flex align-item-sm-center flex-sm-nowrap">' 
                                                        + '<div>' 
                                                            + '<h6 class="text-primary mb-1">'+eventj.holidayname +'</h6>'
                                                        + '</div>'
                                                    + '</div>' 
                                                + '</div>' 
                                            + '</div>';
                                return content;
                            }
                        },
                        position: {
                            effect: false,
                            my: 'bottom center',
                            at: 'top center',
                            viewport: $(window) 
                        },
                        show: {
                            effect: false, 
                            delay: 700
                        },
                        hide: {
                            effect: false, 
                            fixed: true,
                            delay: 70
                        }, 
                        style: {
                            classes: 'qtip-bootstrap',
                            width: 200, 
                            tip: {
                                width: 3,
                                height: 5
                            }
                        }
                    });
                
                    $dayCell.prepend('<div style="width: 0;display:flex;position: absolute;top: 4px;right: 18px;"><i class="icon-info22 text-primary font-size-15"></i> </div>');
                } else {
                    element.remove();
                }

                $dayCell.prepend($preCell);
                var $todaySelector = $('#calendar-<?php echo $this->uniqId ?>').find('.fc-today').find('.fc-day-number');
                var $todayHtml = $todaySelector.html();
                
                if (!today<?php echo $this->uniqId; ?>) {
                    today<?php echo $this->uniqId; ?> = true;
                    $todaySelector.empty().append('<span class="custom">'+ $todayHtml +'</span>');
                }
                
                $(element).empty().append('123');
                //$(element).remove();
            },
            dayClick: function (date) {
                var $date = changeDateTimeFormat<?php echo $this->uniqId; ?>(new Date(date));
                var $dataRow = typeof $data_<?php echo $this->uniqId ?>[$date] !== 'undefined' ? $data_<?php echo $this->uniqId ?>[$date] : {};

                var $charintime = ($dataRow['charintime']) ? $dataRow['charintime'] : '--:--';
                var $charouttime = ($dataRow['charouttime']) ? $dataRow['charouttime'] : '--:--';
                var $cleantime = ($dataRow['cleantime']) ? $dataRow['cleantime'] : '--:--';

                $('.work<?php echo $this->uniqId ?>').find('.intime').empty().append($charintime);
                $('.work<?php echo $this->uniqId ?>').find('.outtime').empty().append($charouttime);
                $('.work<?php echo $this->uniqId ?>').find('.cleantime').empty().append($cleantime);
                
                $('.calendar-<?php echo $this->uniqId ?> p.font-italic').empty().append($dataRow['caldate']);
                
                if ($date < '<?php echo $this->currentDate ?>') {
                    $('.work<?php echo $this->uniqId ?> a[href="#app-dashboard-work-cat-tab-3<?php echo $this->uniqId ?>"]').trigger('click');
                    $('.work<?php echo $this->uniqId ?> #app-dashboard-work-cat-tab-3<?php echo $this->uniqId ?> > .event-tab-content > .event-box').addClass('d-none').removeClass('d-flex');
                    $('.work<?php echo $this->uniqId ?> #app-dashboard-work-cat-tab-3<?php echo $this->uniqId ?> > .event-tab-content > .event-box[data-date="'+ $date +'"]').removeClass('d-none').addClass('d-flex');
                    
                    var $counter = $('.work<?php echo $this->uniqId ?> #app-dashboard-work-cat-tab-3<?php echo $this->uniqId ?> > .event-tab-content > .event-box:not(.d-none)').length;
                    
                    $('.timepast<?php echo $this->uniqId ?>').empty().append($counter);
                } else {
                    if ($date > '<?php echo $this->currentDate ?>') {
                        $('.work<?php echo $this->uniqId ?> a[href="#app-dashboard-work-cat-tab-4<?php echo $this->uniqId ?>"]').trigger('click');
                        $('.work<?php echo $this->uniqId ?> #app-dashboard-work-cat-tab-4<?php echo $this->uniqId ?> > .work_col_group > .work_col').addClass('d-none').removeClass('d-flex');
                        $('.work<?php echo $this->uniqId ?> #app-dashboard-work-cat-tab-4<?php echo $this->uniqId ?> > .work_col_group > .work_col[data-date="'+ $date +'"]').removeClass('d-none').addClass('d-flex');
                    }
                    else {
                        $('.work<?php echo $this->uniqId ?> a[href="#app-dashboard-work-cat-tab-1<?php echo $this->uniqId ?>"]').trigger('click');
                    }
                }

                if (!doubleClick_<?php echo $this->uniqId; ?>) {
                    doubleClick_<?php echo $this->uniqId; ?> = true;
                    setTimeout(function() { doubleClick_<?php echo $this->uniqId; ?> = false; }, 250);
                } else {
                    hrmTimesheetProcess<?php echo $this->uniqId ?>(this, $date, '');
                }
                
                timeRequestData<?php echo $this->uniqId ?>($date);
                
            },
            windowResize: function(view) {}
        });
        <?php if (!Config::getFromCache('isNotaryServer')) { ?>
        
        $.getScript(URL_APP + 'assets/custom/addon/plugins/owl-carousel/easypiechart.js', function() {
            
            chart1percent<?php echo $this->uniqId; ?> = $(".chart1<?php echo $this->uniqId ?>").attr('data-percent');     
            chart2percent<?php echo $this->uniqId; ?> = $(".chart2<?php echo $this->uniqId ?>").attr('data-percent');     
            chart3percent<?php echo $this->uniqId; ?> = $(".chart3<?php echo $this->uniqId ?>").attr('data-percent');   

            var last1Char<?php echo $this->uniqId; ?> = chart1percent<?php echo $this->uniqId; ?>.length > 1 ? chart1percent<?php echo $this->uniqId; ?>.substr(chart1percent<?php echo $this->uniqId; ?>.length - 1) : chart1percent<?php echo $this->uniqId; ?>;
            var last2Char<?php echo $this->uniqId; ?> = chart2percent<?php echo $this->uniqId; ?>.length > 1 ? chart2percent<?php echo $this->uniqId; ?>.substr(chart2percent<?php echo $this->uniqId; ?>.length - 1) : chart2percent<?php echo $this->uniqId; ?>;
            var last3Char<?php echo $this->uniqId; ?> = chart3percent<?php echo $this->uniqId; ?>.length > 1 ? chart3percent<?php echo $this->uniqId; ?>.substr(chart3percent<?php echo $this->uniqId; ?>.length - 1) : chart3percent<?php echo $this->uniqId; ?>;

            if(chart1percent<?php echo $this->uniqId; ?> != "undefined" ||  chart2percent<?php echo $this->uniqId; ?> != "undefined" ||  chart3percent<?php echo $this->uniqId; ?> != "undefined") {
                chart1percent<?php echo $this->uniqId; ?> = (last1Char<?php echo $this->uniqId; ?> === '%') ? parseFloat(substr_replace(chart1percent<?php echo $this->uniqId; ?>,"",-1)) : chart1percent<?php echo $this->uniqId; ?>;
                chart2percent<?php echo $this->uniqId; ?> = (last2Char<?php echo $this->uniqId; ?> === '%') ? parseFloat(substr_replace(chart2percent<?php echo $this->uniqId; ?>,"",-1)) : chart2percent<?php echo $this->uniqId; ?>;
                chart3percent<?php echo $this->uniqId; ?> = (last3Char<?php echo $this->uniqId; ?> === '%') ? parseFloat(substr_replace(chart3percent<?php echo $this->uniqId; ?>,"",-1)) : chart3percent<?php echo $this->uniqId; ?>;
            } else {
                chart1percent<?php echo $this->uniqId; ?> = 0;
                chart2percent<?php echo $this->uniqId; ?> = 0;
                chart3percent<?php echo $this->uniqId; ?> = 0;
            }

            if(chart1percent<?php echo $this->uniqId; ?> >= 0 && chart1percent<?php echo $this->uniqId; ?> < 50) {
                chart1color<?php echo $this->uniqId; ?> = '#f44336';
            } else if(chart1percent<?php echo $this->uniqId; ?> >= 50 && chart1percent<?php echo $this->uniqId; ?> < 80) {
                chart1color<?php echo $this->uniqId; ?> = '#2196F3';
            } else if(chart1percent<?php echo $this->uniqId; ?> >= 80) {
                chart1color<?php echo $this->uniqId; ?> = '#4caf50';
            } else {
                chart1color<?php echo $this->uniqId; ?> = '';
            }

            if(chart2percent<?php echo $this->uniqId; ?> >= 0 && chart2percent<?php echo $this->uniqId; ?> < 50) {
                chart2color<?php echo $this->uniqId; ?> = '#f44336';
            } else if(chart2percent<?php echo $this->uniqId; ?> >= 50 && chart2percent<?php echo $this->uniqId; ?> < 80) {
                chart2color<?php echo $this->uniqId; ?> = '#2196F3';
            } else if(chart2percent<?php echo $this->uniqId; ?> >= 80) {
                chart2color<?php echo $this->uniqId; ?> = '#4caf50';
            } else {
                chart2color<?php echo $this->uniqId; ?> = '';
            }

            if(chart3percent<?php echo $this->uniqId; ?> >= 0 && chart3percent<?php echo $this->uniqId; ?> < 50) {
                chart3color<?php echo $this->uniqId; ?> = '#f44336';
            } else if(chart3percent<?php echo $this->uniqId; ?> >= 50 && chart3percent<?php echo $this->uniqId; ?> < 80) {
                chart3color<?php echo $this->uniqId; ?> = '#2196F3';
            } else if(chart3percent<?php echo $this->uniqId; ?> >= 80) {
                chart3color<?php echo $this->uniqId; ?> = '#4caf50';
            } else {
                chart3color<?php echo $this->uniqId; ?> = '';
            }

            window.chart = new EasyPieChart(document.querySelector('.chart1<?php echo $this->uniqId ?>'), {
                    easing: 'easeOutElastic',
                    delay: 3000,
                    barColor: chart1color<?php echo $this->uniqId; ?>,
                    scaleColor: false,
                    lineWidth: 6,
                    lineCap: 'butt',
                    percentdd: chart1percent<?php echo $this->uniqId; ?>,
                    onStep: function(from, to, percent) {
                        var $this = $(this.el).find('.percent');
                        var $percent = Math.round(percent);

                        $percent = (($percent).toString() != 'NaN') ? $percent : 0;
                        if (0 < $percent) {
                            $this.empty().append(chart1percent<?php echo $this->uniqId; ?> + '%');
                        } else {
                            $this.empty().append('-');
                        }
                        
                    }
            });

            window.chart = new EasyPieChart(document.querySelector('.chart2<?php echo $this->uniqId ?>'), {
                    easing: 'easeOutElastic',
                    delay: 3000,
                    barColor: chart2color<?php echo $this->uniqId; ?>,
                    scaleColor: false,
                    lineWidth: 6,
                    lineCap: 'butt',
                    percentdd: chart2percent<?php echo $this->uniqId; ?>,
                    onStep: function(from, to, percent) {
                        var $this = $(this.el).find('.percent');
                        var $percent = Math.round(percent);

                        $percent = (($percent).toString() != 'NaN') ? $percent : 0; 
                        $this.empty().append(chart2percent<?php echo $this->uniqId; ?> + '%');
                    }
            });

            window.chart = new EasyPieChart(document.querySelector('.chart3<?php echo $this->uniqId ?>'), {
                    easing: 'easeOutElastic',
                    delay: 3000,
                    barColor: chart3color<?php echo $this->uniqId; ?>,
                    scaleColor: false,
                    lineWidth: 6,
                    lineCap: 'butt',
                    percentdd: chart3percent<?php echo $this->uniqId; ?>,
                    onStep: function(from, to, percent) {
                        var $this = $(this.el).find('.percent');
                        var $percent = Math.round(percent);

                        $percent = (($percent).toString() != 'NaN') ? $percent : 0; 
                        $this.empty().append(chart3percent<?php echo $this->uniqId; ?> + '%');
                    }
            });
        });
        
        <?php } ?>
        
        setTimeout(function() {
            
            $('.owl-simple<?php echo $this->uniqId ?>').attr('style', 'width: ' + ($('.width_dashboard_<?php echo $this->uniqId ?>').width() - 500) + 'px;' );
            _owlCarousel<?php echo $this->uniqId ?>();
            
        }, 500);

        setTimeout(function() {
            
            $('.dashboard<?php echo $this->uniqId ?>').find('.removeTag').remove();
            
        }, 2500);
        
        tooltipTaskList<?php echo $this->uniqId ?> ();
        reloadRequest<?php echo $this->uniqId ?>();
        reloadPoll<?php echo $this->uniqId ?>();
    });
    
    function _owlCarousel<?php echo $this->uniqId ?> () {
        if ( $.fn.owlCarousel ) {
            var owlSettings = {
                items: 1,
                loop: true,
                margin: 0,
                responsiveClass: true,
                nav: true,
                navText: ['<i class="fa fa-chevron-left">', '<i class="fa fa-chevron-right">'],
                dots: true,
                smartSpeed: 400,
                autoplay: true,
                autoplayTimeout: 15000
            };
            
            $('.dashboard<?php echo $this->uniqId ?>').find('[data-toggle="owl"]').each(function () {
                var $this = $(this),
                    newOwlSettings = $.extend({}, owlSettings, $this.data('owl-options'));
                
                $this.owlCarousel(newOwlSettings);

            });   
        }
    }
    
    function apiChatSendMessagev1<?php echo $this->uniqId ?>(userId, text, type) {
        
        var $dialogConfirm = 'dialog-confirm-<?php echo $this->uniqId ?>';
        if (!$("#" + $dialogConfirm).length) {
            $('<div id="' + $dialogConfirm + '"></div>').appendTo('.dashboard<?php echo $this->uniqId ?>');
        }
        
        var $dialog = $("#" + $dialogConfirm);
        var $html = '<textarea class="form-control w-100" style="padding-left: 0px; resize: none; font-size: 14px; padding: 6px 15px; border-radius: 0;">'+ (typeof text !== 'undefined' ? text : '') +'</textarea>';
        
        $dialog.empty().append($html);
        $dialog.dialog({
            cache: false,
            resizable: false,
            bgiframe: true,
            autoOpen: false,
            title: plang.get('birthday_confirm_title'),
            width: 400,
            height: "auto",
            modal: true,
            close: function () {
                $dialog.empty().dialog('close');
            },
            buttons: [
                {text: plang.get('send_btn'), class: 'btn green-meadow btn-sm', click: function () {
                    if ($dialog.find('textarea').val()) {
                        var $bhtml = '';
                        $bhtml += '<div class="bday">';
                            $bhtml += '<div class="d-flex align-items-center justify-content-center">';
                                $bhtml += '<img src="assets/custom/img/bday.jpg" class="img-fluid">';
                                $bhtml += '<span class="bday-text">';
                                    $bhtml += $dialog.find('textarea').val(); //'Төрсөн өдрийн мэнд хүргэе.';
                                $bhtml += '</span>';
                            $bhtml += '</div>';
                        $bhtml += '</div>';
                        apiChatSendMessage(userId, $bhtml, type, true);

                        $dialog.empty().dialog('close');
                        
                    } else { /*
                        PNotify.removeAll();
                        new PNotify({
                            title: 'Success',
                            text: 'Илгээх',
                            type: 'success', 
                            sticker: false
                        });*/
                    }
                }},
                {text: plang.get('no_btn'), class: 'btn blue-madison btn-sm', click: function () {
                    $dialog.dialog('close');
                }}
            ]
        });
        
        $dialog.dialog('open');
        
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
    
    function drilldownLinkCustome3_<?php echo $this->uniqId ?> (element) {
        var row = JSON.parse($(element).attr('data-row'));
        //gridDrillDownLink(element, 'DOC_DASHBOARD_CARDS_01', 'metagroup', '1', '', '1571474482320', 'cardvalue', '1554813741229', 'typeid='+ row.typeid + '&directionid='+ row.cardid + '&isnew1='+ row.isnew1, 'newrender', undefined,  '',  '')
        var criteria = 'defaultmetaid=1578488805200&isnew1='+ row.isnew1+'&typeid='+ row.typeid +',typeid='+ row.typeid +'&directionid=1&isnew1='+ row.isnew1+'&defaultmetaid=1554813741229,isnew1='+ row.isnew1+'&typeid='+ row.typeid +'&defaultmetaid=1578488805780,isnew1='+ row.isnew1+'&typeid='+ row.typeid +'&defaultmetaid=1578488807023';
        gridDrillDownLink(element, 'DOC_DASHBOARD_CARDS_01', 'package,package,package,package', '4', 'cardid==1,1==0,cardid==2,cardid==4', '1571474482320', 'cardvalue', '1580889642841862,1580889642841862,1580889642841862,1580889642841862', criteria, 'newrender', '',  '',  '');
    }
    
    $.contextMenu({
        selector: ".event-box<?php echo $this->uniqId ?>, .eventtt",
        build: function($trigger, e) {
            var $this = $(e.currentTarget);
            var contextMenuData = {
                "edit": {
                    name: "Засах", 
                    icon: "edit", 
                    callback: function(key, options) {
                        hrmTimesheetEditProcess<?php echo $this->uniqId ?>($this, $this.attr('data-startdate'), $this.attr('data-id'))
                    }
                },
                "delete": {
                    name: "Устгах", 
                    icon: "trash", 
                    callback: function(key, options) {
                        var wfmCode = strtolower($this.attr('data-wfmstatuscode'));
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
                                                'id': $this.attr('data-id')
                                            },
                                            dataType: 'json', 
                                            beforeSend: function() {
                                                Core.blockUI({
                                                    message: 'Loading...',
                                                    boxed: true
                                                });
                                            },
                                            success: function(data) {
                                                $this.remove();
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
                callback: function (key, opt) {},
                items: contextMenuData
            };

            return options;            
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
                        hrmTimesheetProcess<?php echo $this->uniqId ?>($this, dateStr, '');
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
                        hrmTimesheetProcess<?php echo $this->uniqId ?>($this, dateStr, '');
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
                        hrmTimesheetProcess<?php echo $this->uniqId ?>($this, dateStr, '');
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
                        hrmTimesheetProcess<?php echo $this->uniqId ?>(e.currentTarget, dateStr, '');
                    }
                },
                "orderList": {
                    name: "Засах", 
                    icon: "edit", 
                    callback: function(key, options) {
                        hrmTimesheetEditProcess<?php echo $this->uniqId ?>(e.currentTarget, dateStr, idStr);
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
                                                    $('#calendar-<?php echo $this->uniqId; ?>').fullCalendar('refetchEvents');
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
    
    function hrmTimesheetProcess<?php echo $this->uniqId ?>(elem, startDate, id) {
        var $dialogName = 'dialog-hrm-timesheet-bp';
        if (!$('#' + $dialogName).length) {
            $('<div id="' + $dialogName + '" class="display-none"></div>').appendTo('body');
        }
        var $dialog = $('#' + $dialogName);
        var fillDataParams = '';

        if (id) {
            fillDataParams = 'id='+id+'&defaultGetPf=1';
        }
        
        var $dataRow = typeof $data_<?php echo $this->uniqId ?>[startDate] !== 'undefined' ? $data_<?php echo $this->uniqId ?>[startDate] : {};
        fillDataParams += 'startDate='+startDate+'&endDate='+startDate;
        $.each($dataRow, function ($index, row) {
            if($index == "absenttime" || $index == "latetime" || $index == "earlytime" || $index == "cause1" || $index == "erttime" || $index == "charintime" || $index == "charouttime") {
                fillDataParams += '&' + $index + '='+row;
            }
            
        });
        
        var aMetaDataId = '';
        <?php if (Config::getFromCache('isNotaryServer')) { ?>
            aMetaDataId = '1591862706992';
        <?php } elseif (Config::getFromCache('isMohs')) { ?>
            aMetaDataId = '1599621891427';
        <?php } else { ?>
            aMetaDataId = '1564466865482';
        <?php } ?>
        
        $.ajax({
            type: 'post',
            url: 'mdwebservice/callMethodByMeta',
            data: {
                metaDataId: aMetaDataId, 
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
                                            $('#calendar-<?php echo $this->uniqId; ?>').fullCalendar('refetchEvents');
                                            $(elem).closest('.fc-hrm-time-duration').prepend('<div class="fc-hrm-time-descr fc-hrm-time-wfmstatus" title="'+responseData.resultData.description+'">'+responseData.resultData.description+'</div>');
                                            $dialog.dialog('close');
                                            var $html = '';
                                            
                                            var $responseData = responseData.resultData;
                                            var $startDate = ($responseData['startdate']).substr(0, 10);
                                            var $endDate = ($responseData['enddate']).substr(0, 10);
                                            
                                            <?php if(Config::getFromCache('isNotaryServer')) { ?>
                                                title = $responseData['description2'] ;
                                            <?php } else { ?> 
                                                title = $responseData['booktypename'] ;
                                            <?php } ?>
                                            
                                            $html += '<div class="row event-box event-box event-box<?php echo $this->uniqId ?>" data-id="'+ $responseData['id'] +'" data-startdate="'+ $startDate +'" data-wfmstatuscode="NEW">';
                                                $html += '<div class="col-2">';
                                                    $html += '<div class="calendar-date project-data-group">';
                                                        $html += '<div class="d-flex flex-column">';
                                                            $html += '<h3 class="text-black d-flex align-items-center">';
                                                                $html += '<i data-feather="calendar" class="svg-14 mr-1 text-muted"></i>';
                                                                $html += '<span>'+ $startDate +'</span>';
                                                            $html += '</h3>';
                                                        $html += '</div>';
                                                    $html += '</div>';
                                                $html += '</div>';
                                                $html += '<div class="col-10" style="left: -20px !important;">';
                                                    $html += '<div class="project-data-group">';
                                                        $html += '<div style="width:31px;">';
                                                            $html += '<div class="d-flex flex-column">';
                                                                $html += '<h3>'+ (($responseData['endtime']) ? ($responseData['endtime']).substr(11, 5) : '') +'</h3>';
                                                                $html += '<h3 class="text-danger">'+ (($responseData['starttime']) ? ($responseData['starttime']).substr(11, 5) : '') +'</h3>'; 
                                                            $html += '</div>';
                                                        $html += '</div>';
                                                        $html += '<div class="taskdesc">';
                                                            $html += '<div>'+ title + '</div>';
                                                            $html += '<div class="text-muted">'+ $responseData['description'] + '</div>';
                                                        $html += '</div>';
                                                    $html += '</div>';
                                                $html += '</div>';
                                            $html += '</div>';
                                            
                                            var $counter = $('.timereqCounter<?php echo $this->uniqId ?>').html();
                                            $counter = parseInt($counter) + 1
                                            $('div[id="app-dashboard-event-tab<?php echo $this->uniqId ?>"] > .without-three-tab').prepend($html);
                                            $('.timereqCounter<?php echo $this->uniqId ?>').empty().append($counter);
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

    function hrmTimesheetEditProcess<?php echo $this->uniqId ?>(elem, startDate, id) {
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

        var aMetaDataId = '';
        <?php if (Config::getFromCache('isNotaryServer')) { ?>
            aMetaDataId = '1592453016005';
        <?php } elseif (Config::getFromCache('isMohs')) { ?>
            aMetaDataId = '1586494503265';
        <?php } else { ?> 
            aMetaDataId = '1548836365306';
        <?php } ?>

        $.ajax({
            type: 'post',
            url: 'mdwebservice/callMethodByMeta',
            data: {
                metaDataId: aMetaDataId, 
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
                                            $('#calendar-<?php echo $this->uniqId; ?>').fullCalendar('refetchEvents');
                                            $dialog.dialog('close');
                                            
                                            var $html = '';
                                            var $responseData = responseData.resultData;
                                            var $startDate = ($responseData['startdate']).substr(0, 10);
                                            var $endDate = ($responseData['enddate']).substr(0, 10);
                                            var title = '';
                                            <?php if(Config::getFromCache('isNotaryServer')) { ?>
                                                title = $responseData['description2'] ;
                                            <?php } else { ?> 
                                                title = $responseData['booktypename'] ;
                                            <?php } ?>
                                            $html += '<div class="row event-box event-box event-box<?php echo $this->uniqId ?>" data-id="'+ $responseData['id'] +'" data-startdate="'+ $startDate +'" data-wfmstatuscode="NEW">';
                                                $html += '<div class="col-2">';
                                                    $html += '<div class="calendar-date project-data-group">';
                                                        $html += '<div class="d-flex flex-column">';
                                                            $html += '<h3 class="text-black d-flex align-items-center">';
                                                                $html += '<i data-feather="calendar" class="svg-14 mr-1 text-muted"></i>';
                                                                $html += '<span>'+ $startDate +'<br><font class="text-danger">'+ $endDate +'</font></span>';
                                                            $html += '</h3>';
                                                        $html += '</div>';
                                                    $html += '</div>';
                                                $html += '</div>';
                                                $html += '<div class="col-10">';
                                                    $html += '<div class="project-data-group">';
                                                        $html += '<div style="width:31px;">';
                                                            $html += '<div class="d-flex flex-column">';
                                                                $html += '<h3>'+ (($responseData['endtime']) ? ($responseData['endtime']).substr(11, 5) : '') +'</h3>';
                                                                $html += '<h3 class="text-danger">'+ (($responseData['starttime']) ? ($responseData['starttime']).substr(11, 5) : '') +'</h3>'; 
                                                            $html += '</div>';
                                                        $html += '</div>';
                                                        $html += '<div class="taskdesc">';
                                                            $html += '<div>'+ title+ '</div>';
                                                            $html += '<div class="text-muted">'+ $responseData['description'] + '</div>';
                                                        $html += '</div>';
                                                    $html += '</div>';
                                                $html += '</div>';
                                            $html += '</div>';
                                            
                                            $(elem).empty().append($html);
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
    
    function drilldownLinkCustome1_<?php echo $this->uniqId; ?> (element) {
        var row = JSON.parse($(element).attr('data-row'));
        gridDrillDownLink(element, 'tnaReportList18', 'metagroup', 1, '', '1567152804488', 'id', '1533787071544', 'filterstartdate='+ row.filterstartdate +'&filterenddate='+ row.filterenddate +'&employeeid='+ row.employeeid +'', true, true, ',', ','); 
    }
    
    function drilldownLink_intranet_<?php echo $this->uniqId; ?> (element) {
        
        var _this = $(element),
            selectedRow = JSON.parse(_this.attr('data-rowdata'));
        if (typeof _this.attr('data-showtype') !== 'undefined' && _this.attr('data-showtype') === 'dialog') {
            
            var $dialogName = 'dialog-confirm-' + getUniqueId(1);
            if (!$("#" + $dialogName).length) {
                $('<div id="' + $dialogName + '"></div>').appendTo('body');
            }

            $("#" + $dialogName).empty().append(selectedRow['body']);
            $("#" + $dialogName).dialog({
                cache: false,
                resizable: false,
                bgiframe: true,
                autoOpen: false,
                title: selectedRow['description'],
                width: 450,
                height: "auto",
                modal: true,
                close: function() {
                    $("#" + $dialogName).empty().dialog('destroy').remove();
                },
                buttons: [
                    {
                        text: plang.get('close_btn'),
                        class: 'btn blue-madison btn-sm',
                        click: function() {
                            $("#" + $dialogName).dialog('close');
                        }
                    }
                ]
            }).dialogExtend({
                "closable": false,
                "maximizable": false,
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

            $("#" + $dialogName).dialog('open');
        } else {
            appMultiTab({weburl: 'government/intranet', metaDataId: 'government/intranet', dataViewId: selectedRow.id, title: 'Олон нийт', type: 'selfurl', recordId: selectedRow.id, selectedRow: selectedRow, tabReload: true});
        }
    }
    
    function drilldownLink_hr_<?php echo $this->uniqId; ?> (metaid) {
        var selectedRow = JSON.parse($(element).attr('data-rowdata'));
        var defaultCriteriaParams = {};
        defaultCriteriaParams.id = selectedRow['id'];
        var $dialogName = 'dialog-dataview-hr-breaknews';
        if (!$("#" + $dialogName).length) {
            $('<div id="' + $dialogName + '"></div>').appendTo('body');
        }
        var $dialog = $('#' + $dialogName);

        $.ajax({
            type: 'post',
            url: 'mdobject/dataview/'+metaid+'/0/json',
            data: {
                uriParams: JSON.stringify(defaultCriteriaParams)
            },
            dataType: 'json',
            beforeSend: function () {
                Core.blockUI({
                    boxed: true, 
                    message: 'Loading...'
                });
            },
            success: function (data) {

                $dialog.empty().append(data.Html);
                $dialog.find('.meta-toolbar').remove();
                $dialog.dialog({
                    cache: false,
                    resizable: false,
                    bgiframe: true,
                    autoOpen: false,
                    title: data.Title,
                    width: 1100,
                    height: $(window).height() - 90,
                    modal: true,
                    position: {my:'top', at:'top+50'},
                    closeOnEscape: isCloseOnEscape, 
                    close: function () {
                        $dialog.empty().dialog('close');
                    },
                    buttons: [
                        {text: data.close_btn, class: 'btn blue-hoki btn-sm', click: function () {
                            $dialog.dialog('close');
                        }}
                    ]
                });
                $dialog.dialog('open');
                Core.unblockUI();
            },
            error: function () {
                alert('Error');
            }
        }).done(function () {
            Core.initDVAjax($dialog);
        });   
    }
    
    function drilldownLink_hrbp_<?php echo $this->uniqId; ?> (element) {
        var selectedRow = JSON.parse($(element).attr('data-rowdata'));
        var $dialogName = 'dialog-bp-hr-breaknews';
        if (!$('#' + $dialogName).length) {
            $('<div id="' + $dialogName + '" class="display-none"></div>').appendTo('body');
        }
        var $dialog = $('#' + $dialogName);

        $.ajax({
            type: 'post',
            url: 'mdwebservice/callMethodByMeta',
            data: {
                metaDataId: selectedRow['processid'], 
                isDialog: true, 
                isSystemMeta: false,
                fillDataParams: 'id='+selectedRow['id']+'&defaultGetPf=1', 
                responseType: '', 
                callerType: '', 
                openParams: '{"callerType":""}'
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

                var processForm = $('#wsForm', '#' + $dialogName);
                var processUniqId = processForm.parent().attr('data-bp-uniq-id');

                var buttons = [
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
    
    function drilldownLink_hr2_<?php echo $this->uniqId; ?> (element) {
        var selectedRow = JSON.parse($(element).attr('data-row'));
        var defaultCriteriaParams = {};
        var $dialogName = 'dialog-dataview-hr2-breaknews';
        if (!$("#" + $dialogName).length) {
            $('<div id="' + $dialogName + '"></div>').appendTo('body');
        }
        var $dialog = $('#' + $dialogName);

        $.ajax({
            type: 'post',
            url: 'mdobject/dataview/'+selectedRow['dataviewid']+'/0/json',
            data: {
                uriParams: JSON.stringify(selectedRow)
            },
            dataType: 'json',
            beforeSend: function () {
                Core.blockUI({
                    boxed: true, 
                    message: 'Loading...'
                });
            },
            success: function (data) {

                $dialog.empty().append(data.Html);
                $dialog.find('.meta-toolbar').remove();
                $dialog.dialog({
                    cache: false,
                    resizable: false,
                    bgiframe: true,
                    autoOpen: false,
                    title: data.Title,
                    width: 1100,
                    height: $(window).height() - 90,
                    modal: true,
                    position: {my:'top', at:'top+50'},
                    closeOnEscape: isCloseOnEscape, 
                    close: function () {
                        $dialog.empty().dialog('close');
                    },
                    buttons: [
                        {text: data.close_btn, class: 'btn blue-hoki btn-sm', click: function () {
                            $dialog.dialog('close');
                        }}
                    ]
                });
                $dialog.dialog('open');
                Core.unblockUI();
            },
            error: function () {
                alert('Error');
            }
        }).done(function () {
            Core.initDVAjax($dialog);
        });   
    }
    
    function drilldownLinkCustome2_<?php echo $this->uniqId; ?> (element) {
        var row = JSON.parse($(element).attr('data-row'));
        gridDrillDownLink(element, 'feedbackListByEmployee', 'process,process,process,process,process,process,process,process,process,process,process,process,process,process,process', 15, '(booktypeid==9029 || booktypeid==9030) && isMulti == 1,booktypeid==9008 && isMulti ==1,booktypeid==9009 && isMulti == 1,(booktypeid==9043 || booktypeid ==9042) && isMulti == 1,(booktypeid==9032 || booktypeid==9033) && isMulti == 1,(booktypeid==9062 || booktypeid==9063 || booktypeid==9035 || booktypeid==9036 || booktypeid==9037) && isMulti == 1,booktypeid==9009 && isMulti !=1,(booktypeid==9043 || booktypeid==9042)&& isMulti !=1,(booktypeid==9051 || booktypeid==9027 || booktypeid==9049 || booktypeid==9046 || booktypeid==9045 || booktypeid==9061 || booktypeid==9024 || booktypeid==9025 || booktypeid==9022 || booktypeid==9048 || booktypeid==9086 || booktypeid==9023 ) && isMulti !=1,(booktypeid==9029 || booktypeid==9030) && isMulti != 1,(booktypeid==9062 || booktypeid==9063 || booktypeid==9035 || booktypeid==9036 || booktypeid==9037) && isMulti != 1,booktypeid==9008 && isMulti !=1,(booktypeid==9032 || booktypeid==9033) && isMulti != 1,(booktypeid==9045 || booktypeid==9046) && isMulti ==1,booktypeid==9059', '1568362804484', 'id', '1488543422698,1486037585030,1487042384774,1490149675416,1492489015885,1491375778581,1571475544133,1571475513504,1571475567150,1571475558829,1571998694279,1571998700423,1571998703494,1529564437946,1490789761271', 'id='+ row.id +',id='+ row.id +',id='+ row.id +',id='+ row.id +',id='+ row.id +',id='+ row.id +',id='+ row.id +',id='+ row.id +',id='+ row.id +',id='+ row.id +',id='+ row.id +',id='+ row.id +',id='+ row.id +',id='+ row.id +',id='+ row.id +'', false, true, ',,,,,,,,,,,,,,,', ',,,,,,,,,,,,,,,'); 
    }
    
    setInterval(function () {
        if ($mainSelector<?php echo $this->uniqId; ?>.is(":visible")) {
            reloadRequest<?php echo $this->uniqId ?>();
            reloadPoll<?php echo $this->uniqId ?>();
        }
    }, 30000);
    
    function reloadRequest<?php echo $this->uniqId ?>() {
        $.ajax({
            type: 'post',
            dataType: 'json',
            url: 'government/request',
            data: {
                uniqId: '<?php echo $this->uniqId; ?>',
                isagent: '<?php echo issetParamZero($this->agent) ?>', 
                nult: 1
            },
            dataType: "json",
            beforeSend: function () {
                //blockContent_<?php echo $this->uniqId ?>('.hr-table<?php echo $this->uniqId ?> > tbody');
                //blockContent_<?php echo $this->uniqId ?>('.task-<?php echo $this->uniqId; ?> > tbody');
            },
            success: function (data) {
                
                var $mainselectorHr = $mainSelector<?php echo $this->uniqId; ?>.find('#app-dashboard-req-4<?php echo $this->uniqId ?> > .event-tab-content');
                var $mainselectorTask = $mainSelector<?php echo $this->uniqId; ?>.find('.task-<?php echo $this->uniqId ?> > tbody');
                var $mainselectorNews = $mainSelector<?php echo $this->uniqId; ?>.find('.newslist<?php echo $this->uniqId ?>');
                var $mainselectorYellowNews = $mainSelector<?php echo $this->uniqId; ?>.find('.yellownews<?php echo $this->uniqId ?>');
                var $approvedReq = $mainSelector<?php echo $this->uniqId; ?>.find('.approvedReq<?php echo $this->uniqId ?>').html();
                var $mainSelectorSlide = $mainSelector<?php echo $this->uniqId; ?>.find('.slideText<?php echo $this->uniqId ?>');
                var $mainSelectorFile = $mainSelector<?php echo $this->uniqId; ?>.find('.filelibrary<?php echo $this->uniqId ?>');
                var $mainselectorHelelts = $mainSelector<?php echo $this->uniqId; ?>.find('.heleltsuuleg<?php echo $this->uniqId ?>');
                
                var $index = 0;
                try {
    
                    $.each(data.hr_arr, function (index, row) {
                        if ($mainselectorHr.find('div[data-id="'+ index +'"]').length < 1) {
                            $index++;
                            $mainselectorHr.prepend(row);
                        }
                    });

                    $approvedReq = parseInt($approvedReq) + $index;

                    $('.approvedReq<?php echo $this->uniqId ?>').empty().append($approvedReq);

                    $.each(data.task_arr, function (index, row) {
                        if ($mainselectorTask.find('tr[data-id="'+ index +'"]').length < 1) {
                            $mainselectorTask.prepend(row);
                        }
                    });

                    $.each(data.news_arr, function (index, row) {
                        if ($mainselectorNews.find('li[data-id="'+ index +'"]').length < 1) {
                            $mainselectorNews.find('.removeTag').remove();
                            $mainselectorNews.prepend(row);
                        }
                    });

                    $.each(data.shuurhai_arr, function (index, row) {
                        if ($mainselectorYellowNews.find('li[data-id="'+ index +'"]').length < 1) {
                            $mainselectorYellowNews.find('.removeTag').remove();
                            $mainselectorYellowNews.prepend(row);
                        }
                    });

                    $.each(data.heleltsuuleg, function (index, row) {
                        $mainselectorHelelts.find('.removeTag').remove();
                        if ($mainselectorHelelts.find('li[data-id="'+ index +'"]').length < 1) {
                            $mainselectorHelelts.prepend(row);
                        }
                    });
                    
                    var $taskAllSelector = $('#app-dashboard-work-cat-tab-4<?php echo $this->uniqId ?> > .event-tab-content');
                    var $taskTodaySelector = $('#app-dashboard-work-cat-tab-1<?php echo $this->uniqId ?> > .event-tab-content');
                    var $taskPastSelector = $('#app-dashboard-work-cat-tab-3<?php echo $this->uniqId ?> > .event-tab-content');
                    $taskTodaySelector.find('.removeTag').remove();
                    $.each(data.pos06, function (keyi, prow) {
                        
                        switch (keyi) {
                            case 'past':
                                if (prow) {
                                    var $count = $('.timepast<?php echo $this->uniqId ?>').html();
                                    var $index = 0;
                                    $.each(prow, function (index, row) {
                                        if ($taskPastSelector.find('div[data-id="'+ index +'"]').length < 1) {
                                            $taskPastSelector.prepend(row);
                                            $index++;
                                        }
                                    });

                                    $count = parseInt($count) + $index;
                                    $('.timepast<?php echo $this->uniqId ?>').empty().append($count);
                                }
                                
                                break;
                                
                            case 'today':
                                if (prow) {
                                    var $count = $('.timeatoday<?php echo $this->uniqId ?>').html();
                                    var $index = 0;
                                    
                                    $.each(prow, function (index, row) {
                                        if ($taskTodaySelector.find('div[data-id="'+ index +'"]').length < 1) {
                                            $taskTodaySelector.prepend(row);
                                            $index++;
                                        }
                                    });

                                    $count = parseInt($count) + $index;
                                    $('.timeatoday<?php echo $this->uniqId ?>').empty().append($count);
                                }
                                
                                break;

                            default:
                                if (prow) {
                                    var $count = $('.timeall<?php echo $this->uniqId ?>').html();
                                    var $index = 0;

                                    $.each(prow, function (index, row) {
                                        if ($taskAllSelector.find('div[data-id="'+ index +'"]').length < 1) {
                                            $taskAllSelector.prepend(row);
                                            $index++;
                                        }
                                    });

                                    $count = parseInt($count) + $index;
                                    $('.timeall<?php echo $this->uniqId ?>').empty().append($count);
                                }
                                
                                break;
                        }
                        
                    });
                    
                    $mainSelectorSlide.find('.removeTag').remove();
                    $.each(data.pos12, function (index, row) {
                        if ($mainSelectorSlide.find('ol.carousel-indicators > li[data-id="'+ index +'"]').length < 1) {
                            $mainSelectorSlide.find('ol.carousel-indicators').append(row.target);
                            $mainSelectorSlide.find('div.carousel-inner').append(row.html).promise().done(function () {
                                $('.owl-simple<?php echo $this->uniqId ?>').attr('style', 'width: ' + ($('.width_dashboard_<?php echo $this->uniqId ?>').width() - 500) + 'px;' );
                                _owlCarousel<?php echo $this->uniqId ?>();
                            });
                        }
                    });
                    
                    $.each(data.pos08, function (key, prow) {
                        $mainSelectorFile.find('.removeTag').remove();
                        var $fileTab = $mainSelectorFile.find('#app-dashboard-file-tab-'+ key +'_<?php echo $this->uniqId; ?> ul.list-group-flush');
                        if ($fileTab.length > 0) {
                            $.each(prow, function (index, row) {
                                if ($fileTab.find('li[data-id="'+ index +'"]').length < 1) {
                                    $fileTab.prepend(row);
                                }
                            });
                        }
                    });
                    <?php if (!$this->agent) { ?>
                        if (typeof data.pos11 !=='undefined') {
                            $('.job<?php echo $this->uniqId ?> .table-responsive').empty().append(data.pos11)
                        }
                    <?php } ?>

                    $mainSelector<?php echo $this->uniqId; ?>.find('.documentrender<?php echo $this->uniqId ?>').empty().append(data.pos05);
                    
                } catch (e) {
                    console.log('try catching : ' + e);
                }
                
            },
            error:  function (jqXHR, exception) {
                var msg = '';
                if (jqXHR.status === 0) {
                    msg = 'Not connect.\n Verify Network.';
                } else if (jqXHR.status == 404) {
                    msg = 'Requested page not found. [404]';
                } else if (jqXHR.status == 500) {
                    msg = 'Internal Server Error [500].';
                } else if (exception === 'parsererror') {
                    msg = 'Requested JSON parse failed.';
                } else if (exception === 'timeout') {
                    msg = 'Time out error.';
                } else if (exception === 'abort') {
                    msg = 'Ajax request aborted.';
                } else {
                    msg = 'Uncaught Error.\n' + jqXHR.responseText;
                }
                
                console.log('request: ' + msg);
            }
        }).done(function () {
            //Core.initAjax($("div#layout-"));
            feather.replace();
        });
    }
    
    function reloadPoll<?php echo $this->uniqId ?>() {
        $.ajax({
            type: 'post',
            dataType: 'json',
            url: 'government/getSinglePoll',
            data: {
                uniqId: <?php echo $this->uniqId; ?>, 
                nult: 1
            },
            dataType: "json",
            beforeSend: function () {
                //blockContent_<?php echo $this->uniqId ?>('.hr-table<?php echo $this->uniqId ?> > tbody');
                //blockContent_<?php echo $this->uniqId ?>('.task-<?php echo $this->uniqId; ?> > tbody');
            },
            success: function (data) {
                
                var $mainSelector = $mainSelector<?php echo $this->uniqId; ?>.find('.poll<?php echo $this->uniqId ?> > .poll-refresh');
                
                try {
                    if (data) {
                        $.each(data, function (index, row) {
                            if ($mainSelector.attr('data-id') !== index) {
                                $mainSelector.parent().empty().append(row).promise().done(function () {
                                    
                                });
                            }
                        });
                    }
                    
                } catch (e) {
                    console.log('try catching : ' + e);
                }
                
            },
            error:  function (jqXHR, exception) {
                var msg = '';
                if (jqXHR.status === 0) {
                    msg = 'Not connect.\n Verify Network.';
                } else if (jqXHR.status == 404) {
                    msg = 'Requested page not found. [404]';
                } else if (jqXHR.status == 500) {
                    msg = 'Internal Server Error [500].';
                } else if (exception === 'parsererror') {
                    msg = 'Requested JSON parse failed.';
                } else if (exception === 'timeout') {
                    msg = 'Time out error.';
                } else if (exception === 'abort') {
                    msg = 'Ajax request aborted.';
                } else {
                    msg = 'Uncaught Error.\n' + jqXHR.responseText;
                }
                
                console.log('request: ' + msg);
            }
        }).done(function () {
            //Core.initAjax($("div#layout-"));
            feather.replace();
        });
    }
    
    function blockContent_<?php echo $this->uniqId; ?>(mainSelector) {
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
    
    function timeRequestData<?php echo $this->uniqId ?> (date) {
        
        var mainSelector = 'div[id="app-dashboard-event-tab<?php echo $this->uniqId ?>"]',
            $mainSelector = $('div[id="app-dashboard-event-tab<?php echo $this->uniqId ?>"] > .without-three-tab');
        $('.timereqCounter<?php echo $this->uniqId ?>').empty().append('0');
        
        $.ajax({
            url: 'government/request',
            type: 'post',
            data: {
                date: date,
                uniqId: '<?php echo $this->uniqId ?>',
                layoutPosition: 'pos14', 
                nult: 1
            },
            dataType: 'JSON',
            beforeSend: function () {
                blockContent_<?php echo $this->uniqId ?>(mainSelector);
            },
            success: function (response) {
                $mainSelector.empty();
                if (response.pos14) {
                    var $index = 0
                    $.each(response.pos14, function (index, row) {
                        $mainSelector.append(row);
                        $index++;
                    });
                    
                    $('.timereqCounter<?php echo $this->uniqId ?>').empty().append($index);
                }
                Core.unblockUI(mainSelector);
            },
            error: function (jqXHR, exception) {
                var msg = '';
                if (jqXHR.status === 0) {
                    msg = 'Not connect.\n Verify Network.';
                } else if (jqXHR.status == 404) {
                    msg = 'Requested page not found. [404]';
                } else if (jqXHR.status == 500) {
                    msg = 'Internal Server Error [500].';
                } else if (exception === 'parsererror') {
                    msg = 'Requested JSON parse failed.';
                } else if (exception === 'timeout') {
                    msg = 'Time out error.';
                } else if (exception === 'abort') {
                    msg = 'Ajax request aborted.';
                } else {
                    msg = 'Uncaught Error.\n' + jqXHR.responseText;
                }

                PNotify.removeAll();
                new PNotify({
                    title: 'Error',
                    text: msg,
                    type: 'error',
                    sticker: false
                });

                Core.unblockUI();
            }
        });
    }
    
    function tooltipTaskList<?php echo $this->uniqId; ?> () {
    
        $(".dashboard<?php echo $this->uniqId ?>").find('[data-userid]').each(function (index, row) {
            $(row).qtip({
                content: {
                    text: function(event1, api1) {
                        var response = $.ajax({
                            url: 'government/getUserData',
                            type: 'post',
                            data: {
                                userId: $(row).attr('data-userid')
                            },
                            dataType: 'JSON',
                            beforeSend: function () {},
                            success: function (data) {
                                var $data = (typeof data.result !== 'undefined') ? data.result : [];
                                var $html = '<div class="media-body d-flex align-items-center justify-content-center" style="padding: 8px;">';
                                        $html += '<div class="col-4 text-center border-right-1 border-gray mr-2">';
                                            $html += '<img src="'+ ((typeof $data.picture !== 'undefined' && $data.picture) ? $data.picture : 'assets/custom/addon/admin/layout4/img/user.png') +'" onerror="onUserImageError(this);" class="rounded-circle" style="width:50px;height:50px;">';
                                            $html += '<h6 class="text-blue font-weight-bold mt-1 mb-1 line-height-normal">'+ ((typeof $data.name !== 'undefined' && $data.name) ? $data.name : '') +'</h6>';
                                            $html += '<p class="text-blu mb-0 font-size-12 line-height-normal">'+ ((typeof $data.position !== 'undefined' && $data.position) ? $data.position : '') +'</p>';
                                        $html += '</div>';
                                        $html += '<div class="col-8">';
                                            $html += '<ul class="media-list font-size-13">';
                                                $html += '<li class="d-flex flex-row align-items-center">';
                                                    $html += '<i class="icon-mail5 text-blue mr-1"></i>';
                                                    $html += '<a href="mailto:">'+ ((typeof $data.employeeemail !== 'undefined' && $data.employeeemail) ? $data.employeeemail : '') +'</a>';
                                                $html += '</li>';
                                                $html += '<li class="d-flex flex-row align-items-center mt10">';
                                                    $html += '<i class="icon-mobile text-blue mr-1"></i>';
                                                    $html += '<span>'+ ((typeof $data.employeemobile !== 'undefined' && $data.employeemobile) ? $data.employeemobile : '') +'</span>';
                                                $html += '</li>';
                                                $html += '<li class="d-flex flex-row align-items-center mt10">';
                                                    $html += '<i class="icon-location3 text-blue mr-1"></i>';
                                                    $html += '<span class="line-height-normal">'+ ((typeof $data.departmentname !== 'undefined' && $data.departmentname) ? $data.departmentname: '') +'</span>';
                                                $html += '</li>';
                                            $html += '</ul>';
                                        $html += '</div>';
                                    $html += '</div>';
                                $('body').find('#' + api1['_id']).find('.qtip-content').html($html);
                            }
                        });
                    }
                },
                position:{
                    effect:!1,
                    at:"top right",
                    my: "left top",
                },
                show:{effect:!1,delay:500},
                hide:{effect:!1,fixed:!0,delay:70},
                style:{classes:"qtip-bootstrap",width:450,tip:{width:12,height:7}}
            });
        });
        
        $(".dashboard<?php echo $this->uniqId ?>").find('[data-birthuserid]').each(function (index, row) {
            $(row).qtip({
                content: {
                    text: function(event1, api1) {
                        var response = $.ajax({
                            url: 'government/getUserData',
                            type: 'post',
                            data: {
                                userId: $(row).attr('data-birthuserid')
                            },
                            dataType: 'JSON',
                            beforeSend: function () {},
                            success: function (data) {
                                var $data = (typeof data.result !== 'undefined') ? data.result : [];
                                var $html = '<div class="media-body d-flex align-items-center justify-content-center" style="padding: 8px;">';
                                        $html += '<div class="col-4 text-center border-right-1 border-gray mr-2">';
                                            $html += '<img src="'+ ((typeof $data.picture !== 'undefined' && $data.picture) ? $data.picture : 'assets/custom/addon/admin/layout4/img/user.png') +'" onerror="onUserImageError(this);" class="rounded-circle" style="width:50px;height:50px;">';
                                            $html += '<h6 class="text-blue font-weight-bold mt-1 mb-1 line-height-normal">'+ ((typeof $data.name !== 'undefined' && $data.name) ? $data.name : '') +'</h6>';
                                            $html += '<p class="text-blu mb-0 font-size-12 line-height-normal">'+ ((typeof $data.position !== 'undefined' && $data.position) ? $data.position : '') +'</p>';
                                        $html += '</div>';
                                        $html += '<div class="col-8">';
                                            $html += '<ul class="media-list font-size-13">';
                                                $html += '<li class="d-flex flex-row align-items-center">';
                                                    $html += '<i class="icon-mail5 text-blue mr-1"></i>';
                                                    $html += '<a href="mailto:">'+ ((typeof $data.employeeemail !== 'undefined' && $data.employeeemail) ? $data.employeeemail : '') +'</a>';
                                                $html += '</li>';
                                                $html += '<li class="d-flex flex-row align-items-center mt10">';
                                                    $html += '<i class="icon-mobile text-blue mr-1"></i>';
                                                    $html += '<span>'+ ((typeof $data.employeemobile !== 'undefined' && $data.employeemobile) ? $data.employeemobile : '') +'</span>';
                                                $html += '</li>';
                                                $html += '<li class="d-flex flex-row align-items-center mt10">';
                                                    $html += '<i class="icon-location3 text-blue mr-1"></i>';
                                                    $html += '<span class="line-height-normal">'+ ((typeof $data.departmentname !== 'undefined' && $data.departmentname) ? $data.departmentname: '') +'</span>';
                                                $html += '</li>';
                                            $html += '</ul>';
                                        $html += '</div>';
                                    $html += '</div>';
                                $('body').find('#' + api1['_id']).find('.qtip-content').html($html);
                            }
                        });
                    }
                },
                position:{
                    effect:!1,
                    at:"top left",
                    my: "right top",
                },
                show:{effect:!1,delay:500},
                hide:{effect:!1,fixed:!0,delay:70},
                style:{classes:"qtip-bootstrap",width:450,tip:{width:12,height:7}}
            });
        });
        
        $(".dashboard<?php echo $this->uniqId ?>").find('[data-taskdata]').each(function (index, row) {
            $(row).qtip({
                content: {
                    text: function(event, api) {
                        var rowData = JSON.parse($(api.elements.target[0]).attr('data-taskdata'));
                        var content = '<div class="qtip-taskdatacard pb0 mb0 border-0 shadow-0">' 
                                        + '<div class="card-body">' 
                                            + '<div class="d-sm-flex align-item-sm-center flex-sm-nowrap">' 
                                                + '<div>' 
                                                    + '<h6 class="text-primary mb-1 font-family-Oswald" title="Ажлын код">'+ rowData.tasktypename +'</h6>'
                                                    /* + '<p class="mb-1 text-muted text-justify line-height-normal">'+ rowData.taskname +'</p>'*/
                                                    + 'Гүйцэтгэгч: <!--<img src="'+ rowData.picture +'" onerror="onUserImageError(this);" class="rounded-circle mr5" width="22" height="22">--> <b class="text-blue">'+ rowData.assignusername +'</b>'
                                                    + '<br>'
                                                    + '<span>Төлөв: <b>'+ rowData.wfmstatusname +'</b>'
                                                    + '</span>'
                                                    + '<span class="ml-3">Зэрэглэл: <b>'+ rowData.priorityname +'</b>'
                                                    + '</span>'
                                                + '</div>'
                                            + '</div>' 
                                            + '<div class="d-sm-flex align-item-sm-center flex-sm-nowrap mt10 ">'
                                                + '<div class="pt10 w-100" style="border-top: 1px solid #e5e5e5;">'
                                                    + '<label class="mb-0">Үүсгэсэн хэрэглэгч: <!--<img src="'+ (typeof rowData.userpicture !== 'undefined' ? rowData.userpicture : '') +'" onerror="onUserImageError(this);" class="rounded-circle mr5" width="22" height="22">--><b class="text-blue">' +  rowData.username  + '</b></label>'
                                                    + '<br>'
                                                    + '<label class="mb-0"><!--<i class="fa fa-calendar"></i>-->Эхлэх огноо: <b>' +  (rowData.startdate).substr(0, 10) + '</b></label>'
                                                    + '<label class="mb-0 ml-3"><!--<i class="fa fa-calendar"></i>-->Дуусах огноо: <b>' +  (rowData.enddate).substr(0, 10)  + '</b></label>'
                                                + '</div>'
                                            + '</div>'
                                        + '</div>' 
                                    + '</div>';
                        return content;
                    }
                },
                position: {
                    effect: false,
                    my: 'bottom center',
                    at: 'top center',
                    viewport: $(window) 
                },
                show: {
                    effect: false, 
                    delay: 700
                },
                hide: {
                    effect: false, 
                    fixed: true,
                    delay: 70
                }, 
                style: {
                    classes: 'qtip-bootstrap',
                    width: 500, 
                    tip: {
                        width: 12,
                        height: 7
                    }
                }
            });
        });
    }
    
    function like_<?php echo $this->uniqId ?>(id, post, liketype, element) {
        $.ajax({
            url: 'government/saveLike',
            type: 'post',
            data: {
                postId: id,
                likeTypeId: liketype,
                targetType: post
            },
            dataType: 'JSON',
            beforeSend: function () {
                Core.blockUI({
                    message: 'Уншиж байна түр хүлээнэ үү...',
                    boxed: true
                });
            },
            success: function (result) {
                /*
                    var postid = $("#post_id").val();
                    getComments_<?php echo $this->uniqId ?>(postid);
                    getIntervalData(postid);
                */
                var $this = $(element);
                var $counter = $this.find('span').html();
                if ($this.find('i').hasClass('fa-heart-o')) {
                    if ($counter) {
                        $this.find('span').html(parseInt($counter)+1);
                    } else {
                        $this.find('span').html('1');
                    }
                    $this.find('i').removeClass('fa-heart-o').addClass('fa-heart');
                } else {
                    if ($counter) {
                        $this.find('span').html(parseInt($counter)-1);
                    }
                    $this.find('i').addClass('fa-heart-o').removeClass('fa-heart');
                }
                if (!result) {
                    PNotify.removeAll();
                    new PNotify({
                        title: 'Алдаа',
                        text: 'Алдаа',
                        type: 'danger',
                        sticker: false
                    });
                }

                Core.unblockUI();
            },
            error: function (jqXHR, exception) {
                var msg = '';
                if (jqXHR.status === 0) {
                    msg = 'Not connect.\n Verify Network.';
                } else if (jqXHR.status == 404) {
                    msg = 'Requested page not found. [404]';
                } else if (jqXHR.status == 500) {
                    msg = 'Internal Server Error [500].';
                } else if (exception === 'parsererror') {
                    msg = 'Requested JSON parse failed.';
                } else if (exception === 'timeout') {
                    msg = 'Time out error.';
                } else if (exception === 'abort') {
                    msg = 'Ajax request aborted.';
                } else {
                    msg = 'Uncaught Error.\n' + jqXHR.responseText;
                }

                PNotify.removeAll();
                new PNotify({
                    title: 'Error',
                    text: msg,
                    type: 'error',
                    sticker: false
                });

                Core.unblockUI();
            }
        });
    }

    function wordstrue<?php echo $this->uniqId ?>(element) {
        var $this = $(element),
            $parent = $this.closest('.carousel-item'),
            $row = $this.data('rowdata');
        
        if (typeof $row !== 'object') {
            $row = JSON.parse($row);
        }
        
        var $modalId_<?php echo $this->uniqId ?> = 'modal-wordstrue<?php echo $this->uniqId ?>';
        $('<div id="' + $modalId_<?php echo $this->uniqId ?> + '_'+ $row['id'] +'" class="modal fade" tabindex="-1" role="dialog">' +
                '<div class="modal-dialog app_dashboard">' +
                    '<div class="modal-content modalcontent<?php echo $this->uniqId ?>">' +
                        '<div class="modal-header">' +
                            '<h5 class="modal-title">'+ $row['description'] +'</h5>' +
                            '<button type="button" class="close" data-dismiss="modal">×</button>' +
                        '</div>' +
                        '<div class="modal-body">' +
                            '<img src="'+ $row['physicalpath'] +'" onerror="onUserImageError(this);" class="img-fluid w-100">' +
                            '<p>'+ (($row['body']) ? $row['body'] : '') +'</p>' +
                            '<div class="card-footer p-0 border-0 bg-transparent d-flex align-items-center">' +
                                '<a href="javascript:void(0);" class="mr-3 d-flex align-items-center">' +
                                    '<i data-feather="heart" class="font-size-13 mr5 fa fa-heart"></i>' +
                                    '<span class="text-grey">'+ $parent.find('span.likecc').html() +'</span>' +
                                '</a>' +
                                '<span class="tx-gray-500 ml-auto d-flex">' +
                                    '<i data-feather="calendar" class="svg-14 mr-1"></i>' +
                                    '<span>'+ $row['tsag'] +'</span>' +
                                '</span>' +
                            '</div>' +
                        '</div>' +
                        '<div class="modal-footer">' +
                            '<button type="button" class="btn btn-light" data-dismiss="modal">Хаах</button>' +
                        '</div>' +
                    '</div>' +
                '</div>' +
            '</div>').appendTo('body');
        var $dialog = $('#' + $modalId_<?php echo $this->uniqId ?> + '_'+ $row['id']);

        $dialog.modal({
            // show: false,
            // keyboard: false,
            // backdrop: 'static'
        });

        // $dialog.on('shown.bs.modal', function () {
        //     setTimeout(function () {

        //     }, 10);
        //     disableScrolling();
        // });

        // $dialog.draggable({
        //     handle: ".modal-header"
        // });

        $dialog.on('hidden.bs.modal', function () {
            $dialog.remove();
            enableScrolling();
        });

        $dialog.modal('show');
        Core.initAjax($dialog);

        // $dialog.find('.modal-backdrop').remove();
    }
    
    function list(page) {
        var departmentid = $("#departmentid").val();
        var scheduleid = $("#scheduleid").val();

        $.ajax({
            type: 'post',
            url: 'government/getNotaries',
            data: {notariassearch: 1,departmentid: departmentid, schedule: scheduleid, page: page},
            dataType: 'json',
            beforeSend: function () {
                Core.blockUI();
            },
            success: function (res) {
                $("#example<?php echo $this->uniqId ?>").find('tbody').empty().append(res);
                Core.unblockUI();
            }
        });
    }
    
</script>