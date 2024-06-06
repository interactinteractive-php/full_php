<?php
$colorField = '';

$image1 = issetParam($this->row['dataViewLayoutTypes']['explorer']['fields']['image1']);
$eventResizeProcessCode = issetParam($this->row['dataViewLayoutTypes']['explorer']['fields']['eventResizeProcessCode']);
$eventDropProcessCode = issetParam($this->row['dataViewLayoutTypes']['explorer']['fields']['eventDropProcessCode']);
$todayFocus = issetParam($this->row['dataViewLayoutTypes']['explorer']['fields']['todayFocus']);

$isAddonField = false;
$topLeft1 = issetParam($this->row['dataViewLayoutTypes']['explorer']['fields']['topLeft1']);
$topLeft2 = issetParam($this->row['dataViewLayoutTypes']['explorer']['fields']['topLeft2']);
$topLeft3 = issetParam($this->row['dataViewLayoutTypes']['explorer']['fields']['topLeft3']);
$borderLeftColor = issetParam($this->row['dataViewLayoutTypes']['explorer']['fields']['borderLeftColor']);
$borderRightColor = issetParam($this->row['dataViewLayoutTypes']['explorer']['fields']['borderRightColor']);

if ($topLeft1 || $topLeft2 || $topLeft3) {
    $isAddonField = true;
}

$isTooltipField = false;
$tooltipPos1 = strtolower(issetParam($this->row['dataViewLayoutTypes']['explorer']['fields']['tooltipPos1']));
$tooltipPos2 = strtolower(issetParam($this->row['dataViewLayoutTypes']['explorer']['fields']['tooltipPos2']));
$tooltipPos3 = strtolower(issetParam($this->row['dataViewLayoutTypes']['explorer']['fields']['tooltipPos3']));
$tooltipImage1 = strtolower(issetParam($this->row['dataViewLayoutTypes']['explorer']['fields']['tooltipImage1']));
$tooltipPos4 = strtolower(issetParam($this->row['dataViewLayoutTypes']['explorer']['fields']['tooltipPos4']));
$tooltipPos5 = strtolower(issetParam($this->row['dataViewLayoutTypes']['explorer']['fields']['tooltipPos5']));
$tooltipPos6 = strtolower(issetParam($this->row['dataViewLayoutTypes']['explorer']['fields']['tooltipPos6']));
$tooltipPos6Color = strtolower(issetParam($this->row['dataViewLayoutTypes']['explorer']['fields']['tooltipPos6Color']));
$tooltipPos6TextColor = issetParam($this->row['dataViewLayoutTypes']['explorer']['fields']['tooltipPos6TextColor']);
$tooltipPos7 = strtolower(issetParam($this->row['dataViewLayoutTypes']['explorer']['fields']['tooltipPos7']));
$tooltipPos7Color = strtolower(issetParam($this->row['dataViewLayoutTypes']['explorer']['fields']['tooltipPos7Color']));
$tooltipPos8 = strtolower(issetParam($this->row['dataViewLayoutTypes']['explorer']['fields']['tooltipPos8']));
$tooltipPos8_labelname = issetParam($this->row['dataViewLayoutTypes']['explorer']['fields']['tooltipPos8_labelname']);
$tooltipPos9 = strtolower(issetParam($this->row['dataViewLayoutTypes']['explorer']['fields']['tooltipPos9']));
$tooltipPos9_labelname = issetParam($this->row['dataViewLayoutTypes']['explorer']['fields']['tooltipPos9_labelname']);
$defaultview_calendar = issetParam($this->row['dataViewLayoutTypes']['explorer']['fields']['defaultview_calendar']);

$tooltipPos10 = strtolower(issetParam($this->row['dataViewLayoutTypes']['explorer']['fields']['tooltipPos10']));
$tooltipPos10_labelname = issetParam($this->row['dataViewLayoutTypes']['explorer']['fields']['tooltipPos10_labelname']);

$tooltipPos11 = strtolower(issetParam($this->row['dataViewLayoutTypes']['explorer']['fields']['tooltipPos11']));
$tooltipPos11_labelname = issetParam($this->row['dataViewLayoutTypes']['explorer']['fields']['tooltipPos11_labelname']);

$tooltipPos12 = strtolower(issetParam($this->row['dataViewLayoutTypes']['explorer']['fields']['tooltipPos12']));
$tooltipPos12_labelname = issetParam($this->row['dataViewLayoutTypes']['explorer']['fields']['tooltipPos12_labelname']);

if ($tooltipPos1 || $tooltipPos2 || $tooltipPos3 || $tooltipImage1 
    || $tooltipPos4 || $tooltipPos5 || $tooltipPos6 || $tooltipPos6Color || $tooltipPos7 || $tooltipPos7Color || $tooltipPos8) {
    $isTooltipField = true;
}

$defaultLevel = issetParam($this->row['dataViewLayoutTypes']['explorer']['fields']['defaultLevel']);
$hideParent = issetParam($this->row['dataViewLayoutTypes']['explorer']['fields']['hideParent']);
$name1 = strtolower(issetParam($this->row['dataViewLayoutTypes']['explorer']['fields']['name1']));
$name4 = strtolower(issetParam($this->row['dataViewLayoutTypes']['explorer']['fields']['name4']));
$name5 = strtolower(issetParam($this->row['dataViewLayoutTypes']['explorer']['fields']['name5']));
$name6 = strtolower(issetParam($this->row['dataViewLayoutTypes']['explorer']['fields']['name6']));
$name7 = strtolower(issetParam($this->row['dataViewLayoutTypes']['explorer']['fields']['name7']));
$columns_width = strtolower(issetParam($this->row['dataViewLayoutTypes']['explorer']['fields']['columns_width']));
?>

<form class="gantt_control form-control form-control-sm" style="text-align: right;">
    <!--<button type="button" class="gantt_export">Export</button>-->
    <label>
        <input type="radio" id="scale1" class="gantt_radio" name="scale" value="day"<?php echo (!$defaultLevel || $defaultLevel == 'day') ? ' checked' : ''; ?>>
        <?php echo $this->lang->line('date_day'); ?>
    </label>
    <label>
        <input type="radio" id="scale2" class="gantt_radio" name="scale" value="week"<?php echo ($defaultLevel == 'week') ? ' checked' : ''; ?>>
        <?php echo $this->lang->line('date_week'); ?>
    </label>
    <label>
        <input type="radio" id="scale3" class="gantt_radio" name="scale" value="month"<?php echo ($defaultLevel == 'month') ? ' checked' : ''; ?>>
        <?php echo $this->lang->line('date_month'); ?>
    </label>
    <label>
        <input type="radio" id="scale4" class="gantt_radio" name="scale" value="year"<?php echo ($defaultLevel == 'year') ? ' checked' : ''; ?>>
        <?php echo $this->lang->line('date_year'); ?>
    </label>
</form>
<div class="d-none">
    <input type="checkbox" value="1" checked="checked" id="checkAll_<?php echo $this->dataViewId ?>" />
</div>
<div id="ganttchart-container-<?php echo $this->uid; ?>"></div>

<style type="text/css">
.div-objectdatagrid-<?php echo $this->dataViewId; ?>.explorer-table-cell {
    background-color: transparent!important;
    border: 0!important;
}
.gantt-hidetask{visibility: hidden;}
.wheat_color {
    background-color: white;
}
.wheat_color.odd {
    background-color: #eee;
}
.gantt_export_size {
    width: auto!important;
    height: auto!important;
}
.gantt_export_height {
    height: auto!important;
}
</style>

<script type="text/javascript">
    var date_week = plang.get('date_week');
    var date_month = plang.get('date_month');
    
    $(function () {
        var $ganttElement = $('#ganttchart-container-<?php echo $this->uid; ?>');
        var ganttElementHeight = $(window).height() - $ganttElement.offset().top - 40;
        $ganttElement.css('height', ganttElementHeight);
        
        loadDHtmlXScripts();
                
        var zoomConfig = {
            levels: [
                {
                    name: "day",
                    scale_height: 50,
                    min_column_width: 80,
                    scales: [
                        {unit: "month", step: 1, format: "%m " + date_month},
                        {unit: "day", step: 1, format: "%j %D"}
                    ]
                },
                {
                    name: "week",
                    scale_height: 50,
                    min_column_width: 80,
                    scales: [
                        {unit: "month", step: 1, format: "%m " + date_month},
                        {unit: "monthweek", step: 1, template: monthweekLabel},
                        {unit: "day", step: 1, format: "%j %D"}
                    ]
                },
                {
                    name: "month",
                    scale_height: 50,
                    min_column_width: 80,
                    scales: [
                        {unit: "month", format: "%Y / %m"},
                        {unit: "monthweek", step: 1, template: monthweekLabel},
                    ]
                }, 
                {
                    name: "year",
                    scale_height: 50,
                    min_column_width: 80,
                    scales: [
                        {unit: "year", step: 1, format: "%Y"}
                    ]
                }
            ]
        };

        function monthweekLabel(date) {
            var weekNum = 1;
            var currentDate = gantt.date.month_start(new Date(date));
            while (currentDate < date) {
                currentDate = gantt.date.add(currentDate, 1, "week");
                weekNum++;
            }
            return weekNum + ' ' + date_week;
        }
        
        gantt.clearAll(); 
        gantt.plugins({
            tooltip: true
	});

        gantt.ext.zoom.init(zoomConfig);
        gantt.ext.zoom.attachEvent("onAfterZoom", function (level, config) {
            document.querySelector(".gantt_radio[value='" + config.name + "']").checked = true;
        });
        
        gantt.templates.grid_row_class = function(start, end, task){
            return 'wheat_color';
        };

        gantt.config.columns = [
            <?php 
            if ($name5) {
                $name5Width = issetParam($this->fieldConfigs[$name5]['COLUMN_WIDTH']);
                $name5Width = $name5Width ? (int)$name5Width : '*';
                
                echo '{name: "' . $name5 . '", label: "' . (isset($this->fieldConfigs[$name5]) ? $this->lang->line(issetParam($this->fieldConfigs[$name5]['LABEL_NAME'])) : $name5) . '", align: "center", width: "'.$name5Width.'"},' . "\n";
            }
            if ($name6) {
                $name6Width = issetParam($this->fieldConfigs[$name6]['COLUMN_WIDTH']);
                $name6Width = $name6Width ? (int)$name6Width : '*';
                
                echo '{name: "' . $name6 . '", label: "' . (isset($this->fieldConfigs[$name6]) ? $this->lang->line(issetParam($this->fieldConfigs[$name6]['LABEL_NAME'])) : $name6) . '", align: "center", width: "'.$name6Width.'"},' . "\n";
            }
            if ($name7) {
                $name7Width = issetParam($this->fieldConfigs[$name7]['COLUMN_WIDTH']);
                $name7Width = $name7Width ? (int)$name7Width : '*';
                
                echo '{name: "' . $name7 . '", label: "' . (isset($this->fieldConfigs[$name7]) ? $this->lang->line(issetParam($this->fieldConfigs[$name7]['LABEL_NAME'])) : $name7) . '", align: "center", width: "'.$name7Width.'"},' . "\n";
            }
            
            if ($this->name1) { 
                $name1Width = issetParam($this->fieldConfigs[$name1]['COLUMN_WIDTH']);
                $name1Width = $name1Width ? (int)$name1Width : '*';
            ?>
            {name: "taskname", label: '<?php echo isset($this->fieldConfigs[$name1]) ? $this->lang->line($this->fieldConfigs[$name1]['LABEL_NAME']) : $name1; ?>', tree: true, resize: true, width: '<?php echo $name1Width; ?>' },
            <?php 
            }
            if ($this->name2) { 
                $name2Width = issetParam($this->fieldConfigs[$this->name2]['COLUMN_WIDTH']);
                $name2Width = $name2Width ? (int)$name2Width : '*';
            ?>
                {name: "start_date", label: '<?php echo isset($this->fieldConfigs[$this->name2]) ? $this->lang->line(issetParam($this->fieldConfigs[$this->name2]['LABEL_NAME'])) : $this->name2; ?>', align: "center", width: "<?php echo $name2Width; ?>"},
            <?php 
            }
            if ($this->name3) {
                $name3Width = issetParam($this->fieldConfigs[$this->name3]['COLUMN_WIDTH']);
                $name3Width = $name3Width ? (int)$name3Width : '*';
                
                echo '{name: "' . $this->name3 . '", label: "' . (isset($this->fieldConfigs[$this->name3]) ? $this->lang->line(issetParam($this->fieldConfigs[$this->name3]['LABEL_NAME'])) : $this->name3) . '", align: "center", width: "'.$name3Width.'"},' . "\n";
            }
            if ($name4) {
                $name4Width = issetParam($this->fieldConfigs[$name4]['COLUMN_WIDTH']);
                $name4Width = $name4Width ? (int)$name4Width : '*';

                echo '{name: "' . $name4 . '", label: "' . (isset($this->fieldConfigs[$name4]) ? $this->lang->line(issetParam($this->fieldConfigs[$name4]['LABEL_NAME'])) : $name4) . '", align: "center", width: "'.$name4Width.'"},' . "\n";
            }
            ?>
        ];
        gantt.templates.scale_cell_class = function (date) {
            if (date.getDay() == 0 || date.getDay() == 6) {
                return "weekend";
            }
        };
        gantt.config.drag_resize = <?php echo ($eventResizeProcessCode) ? 'true' : 'false'; ?>;
        gantt.config.drag_move = false;
        gantt.config.drag_progress = false;
        gantt.config.drag_links = false;
        gantt.config.select_task = false;
        gantt.config.details_on_dblclick = false;
        gantt.config.show_progress = false;
        gantt.config.fit_tasks = true;
        gantt.config.scale_height = 50;
        gantt.config.task_height = 20;
        gantt.config.row_height = 28;
        
        gantt.ext.zoom.setLevel('<?php echo ($defaultLevel ? $defaultLevel : 'day'); ?>');

        gantt.ignore_time = function (date) {
            if (date.getDay() == 0 || date.getDay() == 6)
                return true;
        };
        gantt.date.monthweek_start = function (date) {
            var prevDate = gantt.date.week_start(new Date(date));
            if (prevDate.getMonth() != date.getMonth()) {
                prevDate = gantt.date.month_start(new Date(date));
            }

            return prevDate;
        };
        gantt.date.add_monthweek = function (date, inc) {
            var next = gantt.date.add(date, inc, "week");
            if (inc > 0) {
                if (next.getMonth() != date.getMonth()) {
                    next = gantt.date.month_start(next);
                }
            }
            return next;
        };

        var radios = document.getElementsByName("scale");

        for (var i = 0; i < radios.length; i++) {
            radios[i].onclick = function (event) {
                gantt.ext.zoom.setLevel(event.target.value);
            };
        }
        
        <?php
        if ($hideParent == '1') {
        ?>
        gantt.templates.task_class = function(start, end, task){
            var children = gantt.getChildren(task.id);
            if (children.length) {
                return 'gantt-hidetask';
            }
            return '';
        };
        <?php
        }
        
        if ($columns_width) {
        ?>
                
        gantt.config.layout = {
            css: "gantt_container",
            cols: [
                {
                    width: <?php echo $columns_width; ?>,
                    min_width: <?php echo $columns_width; ?>,
                    rows: [
                        {view: "grid", scrollX: "gridScroll", scrollable: true, scrollY: "scrollVer"},
                        {view: "scrollbar", id: "gridScroll", group: "horizontal"}
                    ]
                },
                {resizer: true, width: 1},
                {
                    rows: [
                        {view: "timeline", scrollX: "scrollHor", scrollY: "scrollVer"},
                        {view: "scrollbar", id: "scrollHor", group: "horizontal"}
                    ]
                },
                {view: "scrollbar", id: "scrollVer"}
            ]
	};
        <?php
        }
        ?>

        gantt.attachEvent("onBeforeTaskSelected", function (id) {
            console.log('onBeforeTaskSelected');
        });
       
        gantt.attachEvent("onGanttRender", function(){
            
            var styleId = 'dynamicGanttStyles_<?php echo $this->dataViewId; ?>';
            var element = document.getElementById(styleId);
            var css = [];
            
            if (element) {
                document.getElementById(styleId).remove();
            } 
            
            element = document.createElement('style');
            element.id = styleId;
            document.querySelector('head').appendChild(element);
            
            var tasks = gantt.getTaskByTime();
    
            $.each(tasks, function (index, task) {
                
                var $element = $ganttElement.find('div.gantt_task_line[data-task-id="'+task['id']+'"]');
                var rData = task['row'], event = rData;
                $element.attr({'data-rid': event.id, 'data-rowdata': JSON.stringify(rData)});
                
                css.push('div.gantt_task_line[data-task-id="'+task['id']+'"]{ background-color:'+rData.color+'!important; }');
        
                <?php
                if ($isTooltipField) {
                    $tooltipPos6_labelname = issetParam($this->row['dataViewLayoutTypes']['explorer']['fields']['tooltipPos6_labelname']);
                    $tooltipPos7_labelname = issetParam($this->row['dataViewLayoutTypes']['explorer']['fields']['tooltipPos7_labelname']);
                    $tooltipPos8_labelname = issetParam($this->row['dataViewLayoutTypes']['explorer']['fields']['tooltipPos8_labelname']);
                ?>

                $element.qtip({
                    content: {
                        text: function(event, api) {

                            var rowData = api.elements.target.data('rowdata');

                            var content = '<div class="card pb0 mb0 border-0 shadow-0">'+
                                '<div class="card-body">'+
                                    '<div class="d-sm-flex align-item-sm-center flex-sm-nowrap">'+
                                        '<div>'+
                                            <?php
                                            if ($tooltipPos1) {
                                            ?>
                                            '<h6 class="text-primary mb-1" title="<?php echo $this->lang->line(issetParam($this->row['dataViewLayoutTypes']['calendar']['fields']['tooltipPos1_labelname'])); ?>">'+dvFieldValueShow(rowData.<?php echo $tooltipPos1; ?>)+'</h6>'+
                                            <?php
                                            }
                                            if ($tooltipPos2) {
                                            ?>
                                            '<p class="mb-2 font-weight-bold">'+dvFieldValueShow(rowData.<?php echo $tooltipPos2; ?>)+'</p>'+
                                            <?php
                                            }
                                            if ($tooltipPos3) {
                                            ?>
                                            '<p class="mb-2">'+dvFieldValueShow(rowData.<?php echo $tooltipPos3; ?>)+'</p>'+                            
                                            <?php
                                            }
                                            if ($tooltipImage1) {
                                            ?>
                                            '<img src="api/image_thumbnail?width=50&src='+rowData.<?php echo $tooltipImage1; ?>+'" onerror="onUserImgError(this);" class="rounded-circle border-gray border-1" width="50" height="50"/> '+
                                            <?php
                                            }
                                            if ($tooltipPos4) {
                                            ?>
                                            dvFieldValueShow(rowData.<?php echo $tooltipPos4; ?>)+
                                            <?php
                                            }
                                            ?>
                                            '</div>'+
                                            '<ul class="list list-unstyled mb-0 mt-3 mt-sm-0 ml-auto" style="min-width: 100px">'+
                                                <?php
                                                if ($tooltipPos5) {
                                                ?>
                                                '<li><span class="text-muted">'+moment(rowData.<?php echo $tooltipPos5; ?>).format('YYYY-MM-DD')+'</span></li>'+
                                                <?php
                                                }
                                                if ($tooltipPos8) {
                                                ?>
                                                '<li>'+
                                                    '<?php echo $tooltipPos8_labelname ? $this->lang->line($tooltipPos8_labelname).':<br />' : ''; ?><span class="badge bg-warning-400 ml-auto" style="background:#AAA;color:#FFF;">'+dvFieldValueShow(rowData.<?php echo $tooltipPos8; ?>)+'</span>'+
                                                '</li>'+
                                                <?php
                                                }
                                                if ($tooltipPos6) {
                                                ?>
                                                '<li>'+
                                                    '<?php echo $tooltipPos6_labelname ? $this->lang->line($tooltipPos6_labelname).':<br />' : ''; ?><span class="badge bg-warning-400 ml-auto" style="<?php echo $tooltipPos6TextColor ? 'color: '.$tooltipPos6TextColor.';' : ''; echo $tooltipPos6Color ? 'background-color: \'+rowData.'.$tooltipPos6Color.'+\'' : ''; ?>">'+dvFieldValueShow(rowData.<?php echo $tooltipPos6; ?>)+'</span>'+
                                                '</li>'+
                                                <?php
                                                }
                                                if ($tooltipPos7) {
                                                ?>
                                                '<li>'+
                                                    '<?php echo $tooltipPos7_labelname ? $this->lang->line($tooltipPos7_labelname).':<br />' : ''; ?><span class="badge bg-success-400 ml-auto" <?php echo $tooltipPos7Color ? 'style="background-color: \'+rowData.'.$tooltipPos7Color.'+\'"' : ''; ?>>'+dvFieldValueShow(rowData.<?php echo $tooltipPos7; ?>)+'</span>'+
                                                '</li>'+
                                                 <?php
                                                }
                                                ?>
                                            '</ul>'+
                                        '</div>'+
                                        <?php if ($tooltipPos10 && $tooltipPos9) { ?>
                                            '<div class="d-sm-flex align-item-sm-center flex-sm-nowrap mt10 ">'+
                                                '<div class="pt10 w-100" style="border-top: 1px solid #e5e5e5;">'+
                                                    '<label class="w-100"><i class="fa fa-calendar"></i> <?php echo $tooltipPos9_labelname ? $tooltipPos9_labelname : ''; ?>: ' + dvFieldValueShow(rowData.<?php echo $tooltipPos9; ?>) + '</label>'+
                                                    '<label class="w-100"><i class="fa fa-calendar"></i> <?php echo $tooltipPos10_labelname ? $tooltipPos10_labelname : ''; ?>: ' + dvFieldValueShow(rowData.<?php echo $tooltipPos10; ?>) + '</label>'+
                                                '</div>'+
                                            '</div>'+
                                        <?php } ?>
                                        <?php if ($tooltipPos11 && $tooltipPos11_labelname) { ?>
                                            '<div class="d-sm-flex align-item-sm-center flex-sm-nowrap mt10 ">'+
                                                '<div class="pt10 w-100" style="border-top: 1px solid #e5e5e5;"><?php echo $tooltipPos11_labelname; ?><hr class="mt0 mb5 border-0">'+
                                                    dvFieldValueShow(rowData.<?php echo $tooltipPos11; ?>) +
                                                '</div>'+
                                            '</div>'+
                                        <?php } ?>
                                        <?php if ($tooltipPos12 && $tooltipPos12_labelname) { ?>
                                            '<div class="d-sm-flex align-item-sm-center flex-sm-nowrap mt10 ">'+
                                                '<div class="pt10 w-100" style="border-top: 1px solid #e5e5e5;"><?php echo $tooltipPos12_labelname; ?><hr class="mt0 mb5 border-0">'+
                                                    dvFieldValueShow(rowData.<?php echo $tooltipPos12; ?>) +
                                                '</div>'+
                                            '</div>'+
                                        <?php } ?>
                                    '</div>'+
                                '</div>';
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
                <?php
                }
                ?>
            });
            
            element.innerHTML = css.join('');
        });
        
        gantt.attachEvent("onGanttScroll", function(left, top) {
            
            var styleId = 'dynamicGanttStyles_<?php echo $this->dataViewId; ?>';
            var element = document.getElementById(styleId);
            var css = [];
            
            if (element) {
                document.getElementById(styleId).remove();
            } 
            
            element = document.createElement('style');
            element.id = styleId;
            document.querySelector('head').appendChild(element);
            
            var tasks = gantt.getTaskByTime();
            
            $.each(tasks, function (index, task) {
                
                var $element = $ganttElement.find('div.gantt_task_line[data-task-id="'+task['id']+'"]');
                
                if ($element.length) {
                    
                var rData = task['row'], event = rData;
                $element.attr({'data-rid': event.id, 'data-rowdata': JSON.stringify(rData)});
                
                css.push('div.gantt_task_line[data-task-id="'+task['id']+'"]{ background-color:'+rData.color+'!important; }');
        
                <?php
                if ($isTooltipField) {
                    $tooltipPos6_labelname = issetParam($this->row['dataViewLayoutTypes']['explorer']['fields']['tooltipPos6_labelname']);
                    $tooltipPos7_labelname = issetParam($this->row['dataViewLayoutTypes']['explorer']['fields']['tooltipPos7_labelname']);
                    $tooltipPos8_labelname = issetParam($this->row['dataViewLayoutTypes']['explorer']['fields']['tooltipPos8_labelname']);
                ?>
                $element.qtip({
                    content: {
                        text: function(event, api) {

                            var rowData = api.elements.target.data('rowdata');

                            var content = '<div class="card pb0 mb0 border-0 shadow-0">'+
                                '<div class="card-body">'+
                                    '<div class="d-sm-flex align-item-sm-center flex-sm-nowrap">'+
                                        '<div>'+
                                            <?php
                                            if ($tooltipPos1) {
                                            ?>
                                            '<h6 class="text-primary mb-1" title="<?php echo $this->lang->line(issetParam($this->row['dataViewLayoutTypes']['calendar']['fields']['tooltipPos1_labelname'])); ?>">'+dvFieldValueShow(rowData.<?php echo $tooltipPos1; ?>)+'</h6>'+
                                            <?php
                                            }
                                            if ($tooltipPos2) {
                                            ?>
                                            '<p class="mb-2 font-weight-bold">'+dvFieldValueShow(rowData.<?php echo $tooltipPos2; ?>)+'</p>'+
                                            <?php
                                            }
                                            if ($tooltipPos3) {
                                            ?>
                                            '<p class="mb-2">'+dvFieldValueShow(rowData.<?php echo $tooltipPos3; ?>)+'</p>'+                            
                                            <?php
                                            }
                                            if ($tooltipImage1) {
                                            ?>
                                            '<img src="api/image_thumbnail?width=50&src='+rowData.<?php echo $tooltipImage1; ?>+'" onerror="onUserImgError(this);" class="rounded-circle border-gray border-1" width="50" height="50"/> '+
                                            <?php
                                            }
                                            if ($tooltipPos4) {
                                            ?>
                                            dvFieldValueShow(rowData.<?php echo $tooltipPos4; ?>)+
                                            <?php
                                            }
                                            ?>
                                            '</div>'+
                                            '<ul class="list list-unstyled mb-0 mt-3 mt-sm-0 ml-auto" style="min-width: 100px">'+
                                                <?php
                                                if ($tooltipPos5) {
                                                ?>
                                                '<li><span class="text-muted">'+moment(rowData.<?php echo $tooltipPos5; ?>).format('YYYY-MM-DD')+'</span></li>'+
                                                <?php
                                                }
                                                if ($tooltipPos8) {
                                                ?>
                                                '<li>'+
                                                    '<?php echo $tooltipPos8_labelname ? $this->lang->line($tooltipPos8_labelname).':<br />' : ''; ?><span class="badge bg-warning-400 ml-auto" style="background:#AAA;color:#FFF;">'+dvFieldValueShow(rowData.<?php echo $tooltipPos8; ?>)+'</span>'+
                                                '</li>'+
                                                <?php
                                                }
                                                if ($tooltipPos6) {
                                                ?>
                                                '<li>'+
                                                    '<?php echo $tooltipPos6_labelname ? $this->lang->line($tooltipPos6_labelname).':<br />' : ''; ?><span class="badge bg-warning-400 ml-auto" style="<?php echo $tooltipPos6TextColor ? 'color: '.$tooltipPos6TextColor.';' : ''; echo $tooltipPos6Color ? 'background-color: \'+rowData.'.$tooltipPos6Color.'+\'' : ''; ?>">'+dvFieldValueShow(rowData.<?php echo $tooltipPos6; ?>)+'</span>'+
                                                '</li>'+
                                                <?php
                                                }
                                                if ($tooltipPos7) {
                                                ?>
                                                '<li>'+
                                                    '<?php echo $tooltipPos7_labelname ? $this->lang->line($tooltipPos7_labelname).':<br />' : ''; ?><span class="badge bg-success-400 ml-auto" <?php echo $tooltipPos7Color ? 'style="background-color: \'+rowData.'.$tooltipPos7Color.'+\'"' : ''; ?>>'+dvFieldValueShow(rowData.<?php echo $tooltipPos7; ?>)+'</span>'+
                                                '</li>'+
                                                 <?php
                                                }
                                                ?>
                                            '</ul>'+
                                        '</div>'+
                                        <?php if ($tooltipPos10 && $tooltipPos9) { ?>
                                            '<div class="d-sm-flex align-item-sm-center flex-sm-nowrap mt10 ">'+
                                                '<div class="pt10 w-100" style="border-top: 1px solid #e5e5e5;">'+
                                                    '<label class="w-100"><i class="fa fa-calendar"></i> <?php echo $tooltipPos9_labelname ? $tooltipPos9_labelname : ''; ?>: ' + dvFieldValueShow(rowData.<?php echo $tooltipPos9; ?>) + '</label>'+
                                                    '<label class="w-100"><i class="fa fa-calendar"></i> <?php echo $tooltipPos10_labelname ? $tooltipPos10_labelname : ''; ?>: ' + dvFieldValueShow(rowData.<?php echo $tooltipPos10; ?>) + '</label>'+
                                                '</div>'+
                                            '</div>'+
                                        <?php } ?>
                                        <?php if ($tooltipPos11 && $tooltipPos11_labelname) { ?>
                                            '<div class="d-sm-flex align-item-sm-center flex-sm-nowrap mt10 ">'+
                                                '<div class="pt10 w-100" style="border-top: 1px solid #e5e5e5;"><?php echo $tooltipPos11_labelname; ?><hr class="mt0 mb5 border-0">'+
                                                    dvFieldValueShow(rowData.<?php echo $tooltipPos11; ?>) +
                                                '</div>'+
                                            '</div>'+
                                        <?php } ?>
                                        <?php if ($tooltipPos12 && $tooltipPos12_labelname) { ?>
                                            '<div class="d-sm-flex align-item-sm-center flex-sm-nowrap mt10 ">'+
                                                '<div class="pt10 w-100" style="border-top: 1px solid #e5e5e5;"><?php echo $tooltipPos12_labelname; ?><hr class="mt0 mb5 border-0">'+
                                                    dvFieldValueShow(rowData.<?php echo $tooltipPos12; ?>) +
                                                '</div>'+
                                            '</div>'+
                                        <?php } ?>    
                                    '</div>'+
                                '</div>';
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
                <?php
                }
                ?>
                }
            });
            
            element.innerHTML = css.join('');
        });
        
        <?php
        if ($eventResizeProcessCode) {
        ?>
        gantt.attachEvent("onAfterTaskUpdate", function(id, item) {
            var paramData = [];
            paramData.push({
                fieldPath: 'id', 
                inputPath: 'id', 
                value: id
            }, {
                fieldPath: 'startDate', 
                inputPath: 'startDate', 
                value: moment(item.start_date).format('YYYY-MM-DD')
            });
            
            if (item.end_date) {
                paramData.push({
                    fieldPath: 'endDate', 
                    inputPath: 'endDate', 
                    value: moment(item.end_date).format('YYYY-MM-DD')
                });
            }
        
            $.ajax({
                type: 'post',
                url: 'mdwebservice/execProcess', 
                data: {processCode: '<?php echo $eventResizeProcessCode; ?>', paramData: paramData},
                dataType: 'json',
                async: false, 
                success: function(response) {
                    if (response.status != 'success') {
                        PNotify.removeAll();
                        new PNotify({
                            title: response.status,
                            text: response.text,
                            type: response.status,
                            sticker: false, 
                            addclass: pnotifyPosition
                        });
                    } 
                }
            });
        });
        <?php
        }
        ?>
        
        gantt.init("ganttchart-container-<?php echo $this->uid; ?>");
        gantt.config.branch_loading = true;
        gantt.load('mdobject/getGanttChartData/<?php echo $this->dataViewId; ?>?isParentFilter=1&param=<?php echo $this->defaultCriteriaData; ?>');

        gantt.templates.tooltip_text = function(start, end, task) {
            return '';
        };
        
        $.contextMenu({
            selector: '#objectdatagrid-<?php echo $this->dataViewId; ?> .gantt_task_line[data-task-id]',
            events: {
                show: function(opt) {
                    var $this = opt.$trigger;
                    var $parent = $this.closest('.not-datagrid');
                    $parent.find('.paneldv-selected-row').removeClass('paneldv-selected-row');
                    $this.addClass('paneldv-selected-row');
                }
            },
            build: function($trigger, e) {

                var rows = $trigger.data('rowdata');
                var contextMenuData = {};

                contextMenuData = {
                    <?php 
                    $commandContextArray = Arr::sortBy('ORDER_NUM', $this->dataViewProcessCommand['commandContext'], 'asc');
                    $cmi = 1;
                    foreach ($commandContextArray as $cm => $row) {

                        $contextMenuIcon = str_replace('fa-', '', $row['ICON_NAME']);

                        if (isset($row['STANDART_ACTION'])) {

                            if ($row['STANDART_ACTION'] == 'criteria') {

                                echo '"' . $cmi . '": {'
                                . 'name: "' . $this->lang->line($row['PROCESS_NAME']) . '", '
                                . 'icon: "' . $contextMenuIcon . '", ';

                                if (isset($row['CRITERIA']) && $row['CRITERIA'] != '') {
                                    echo '_dvSimpleCriteria: "'.$row['CRITERIA'].'",';
                                }

                                echo 'callback: function(key, options) {'
                                . 'transferProcessCriteria(\'' . $this->dataViewId . '\', \'' . $row['BATCH_NUMBER'] . '\', \'context\', options.$trigger, {callerType: \''.$this->metaDataCode.'\'});'
                                . '}'
                                . '},';

                            } elseif ($row['STANDART_ACTION'] == 'processCriteria') {

                                echo '"' . $cmi . '": {'
                                . 'name: "' . $this->lang->line($row['PROCESS_NAME']) . '", '
                                . 'icon: "' . $contextMenuIcon . '", ';

                                if (isset($row['CRITERIA']) && $row['CRITERIA'] != '') {
                                    echo '_dvSimpleCriteria: "'.$row['CRITERIA'].'",';
                                }

                                echo 'callback: function(key, options) {';

                                if ($row['ADVANCED_CRITERIA'] != '') {
                                    echo '_dvAdvancedCriteria = "'.$row['ADVANCED_CRITERIA'].'";';
                                }

                                echo 'transferProcessAction(\'' . $row['PASSWORD_PATH'] . '\', \'' . $this->dataViewId . '\', \'' . $row['PROCESS_META_DATA_ID'] . '\', \'' . $row['META_TYPE_ID'] . '\', \'processCriteria\', options.$trigger, {callerType: \''.$this->metaDataCode.'\'}, \'\');'
                                . '}'
                                . '},';

                            } else {

                                echo '"' . $cmi. '": {'
                                . 'name: "' . $this->lang->line($row['PROCESS_NAME']) . '", '
                                . 'icon: "' . $contextMenuIcon . '", ';

                                if (isset($row['CRITERIA']) && $row['CRITERIA'] != '') {
                                    echo '_dvSimpleCriteria: "'.$row['CRITERIA'].'",';
                                }

                                echo 'callback: function(key, options) {'
                                . 'transferProcessAction(\'\', \'' . $this->dataViewId . '\', \'' . $row['STANDART_ACTION'] . '\', \'' . Mdmetadata::$businessProcessMetaTypeId . '\', \'grid\', options.$trigger, {callerType: \''.$this->metaDataCode.'\'}, \'\');'
                                . '}'
                                . '},';
                            }

                        } else {

                            echo '"' . $cmi. '": {'
                                . 'name: "' . $this->lang->line($row['PROCESS_NAME']) . '", '
                                . 'icon: "' . $contextMenuIcon . '", ';

                                if (isset($row['CRITERIA']) && $row['CRITERIA'] != '') {
                                    echo '_dvSimpleCriteria: "'.$row['CRITERIA'].'",';
                                }

                                echo 'callback: function(key, options) {'
                                . 'transferProcessAction(\'' . $row['PASSWORD_PATH'] . '\', \'' . $this->dataViewId . '\', \'' . $row['PROCESS_META_DATA_ID'] . '\', \'' . $row['META_TYPE_ID'] . '\', \'grid\', options.$trigger, {callerType: \''.$this->metaDataCode.'\'}, \'\');'
                                . '}'
                                . '},';
                        }
                        $cmi++;
                    }
                    ?>
                };

                $.each(contextMenuData, function ($indexCn, $contextR) {
                    if (typeof $contextR['_dvSimpleCriteria'] !== 'undefined' && $contextR['_dvSimpleCriteria']) {
                        var evalcriteria = $contextR['_dvSimpleCriteria'].toLowerCase();

                        if (evalcriteria.indexOf('#') > -1) {
                            var criteriaSplit = evalcriteria.split('#');
                            evalcriteria = trim(criteriaSplit[0]);
                        }

                        $.each(rows, function(index, row) {
                            if (evalcriteria.indexOf(index) > -1) {
                                row = (row === null) ? '' : row.toLowerCase();
                                var regex = new RegExp('\\b' + index + '\\b', 'g');
                                evalcriteria = evalcriteria.replace(regex, "'" + row.toString() + "'");
                            }
                        });

                        try {
                            if (!eval(evalcriteria)) {
                                ticket = false;
                                delete contextMenuData[$indexCn];
                            }
                        } catch (err) {
                            delete contextMenuData[$indexCn];
                            console.log(evalcriteria);
                        }
                    }
                });

                <?php
                if (isset($this->dataViewWorkFlowBtn) && $this->dataViewWorkFlowBtn == true) { 
                ?>

                contextMenuData['sep1'] = "---------";

                $.ajax({
                    type: 'post',
                    url: 'mdobject/getWorkflowNextStatus',
                    data: {metaDataId: '<?php echo $this->dataViewId ?>', dataRow: rows},
                    dataType: 'json',
                    async: false,
                    success: function(response) {
                        if (response.status === 'success' && response.datastatus && response.data) {

                            var rowId = '', realWfmName = '', advancedCriteria = '', wfmIcon = '';

                            if (typeof rows.id !== 'undefined') {
                                rowId = rows.id;
                            }

                            $.each(response.data, function (i, v) {

                                if (typeof v.wfmstatusname != 'undefined' && typeof v.processname != 'undefined' && v.processname != '') {
                                    v.wfmstatusname = plang.get(v.processname);
                                }

                                if (v.wfmstatusicon) {
                                    wfmIcon = '<i class="fa '+v.wfmstatusicon+'" style="color: '+v.wfmstatuscolor+'"></i> ';
                                }

                                if (typeof v.usedescriptionwindow != 'undefined' && !v.usedescriptionwindow && typeof v.wfmuseprocesswindow != 'undefined' && !v.wfmuseprocesswindow) {

                                    contextMenuData[v.wfmstatusid] = {
                                        name: wfmIcon + v.wfmstatusname, 
                                        isHtmlName: true,  
                                        callback: function(key, options) {

                                            var $el = $('<span />', {text: v.wfmstatusname});

                                            if (typeof v.advancedCriteria !== "undefined" && v.advancedCriteria !== null) {
                                                $el.attr('data-advanced-criteria', v.advancedCriteria.replace(/\"/g, ''));
                                            }

                                            changeWfmStatusId($el, v.wfmstatusid, '<?php echo $this->dataViewId ?>', '<?php echo $this->refStructureId ?>', v.wfmstatuscolor, v.wfmstatusname, '', '', '');
                                        }
                                    };

                                } else {
                                    if (typeof v.wfmstatusname != 'undefined' && v.wfmstatusname != '' && (v.wfmstatusprocessid == '' || v.wfmstatusprocessid == 'null' || v.wfmstatusprocessid == null)) {

                                        if (v.wfmisneedsign == '1') {

                                            contextMenuData[v.wfmstatusid] = {
                                                name: wfmIcon + v.wfmstatusname + ' <i class="fa fa-key"></i>', 
                                                isHtmlName: true,  
                                                callback: function(key, options) {

                                                    var $el = $('<span />', {text: v.wfmstatusname});
                                                    $el.attr('id', v.wfmstatusid);

                                                    if (typeof v.advancedCriteria !== "undefined" && v.advancedCriteria !== null) {
                                                        $el.attr('data-advanced-criteria', v.advancedCriteria.replace(/\"/g, ''));
                                                    }

                                                    beforeSignChangeWfmStatusId($el, v.wfmstatusid, '<?php echo $this->dataViewId ?>', '<?php echo $this->refStructureId ?>', v.wfmstatuscolor, v.wfmstatusname);
                                                }
                                            };

                                        } else if (v.wfmisneedsign == '2') {

                                            contextMenuData[v.wfmstatusid] = {
                                                name: wfmIcon + v.wfmstatusname +' <i class="fa fa-key"></i>', 
                                                isHtmlName: true,  
                                                callback: function(key, options) {

                                                    var $el = $('<span />', {text: v.wfmstatusname});
                                                    $el.attr('id', v.wfmstatusid);

                                                    if (typeof v.advancedCriteria !== "undefined" && v.advancedCriteria !== null) {
                                                        $el.attr('data-advanced-criteria', v.advancedCriteria.replace(/\"/g, ''));
                                                    }

                                                    beforeHardSignChangeWfmStatusId($el, v.wfmstatusid, '<?php echo $this->dataViewId ?>', '<?php echo $this->refStructureId ?>', v.wfmstatuscolor, v.wfmstatusname);
                                                }
                                            };

                                        } else {

                                            contextMenuData[v.wfmstatusid] = {
                                                name: wfmIcon + v.wfmstatusname, 
                                                isHtmlName: true,  
                                                callback: function(key, options) {

                                                    var $el = $('<span />', {text: v.wfmstatusname});

                                                    if (typeof v.advancedCriteria !== "undefined" && v.advancedCriteria !== null) {
                                                        $el.attr('data-advanced-criteria', v.advancedCriteria.replace(/\"/g, ''));
                                                    }

                                                    changeWfmStatusId($el, v.wfmstatusid, '<?php echo $this->dataViewId ?>', '<?php echo $this->refStructureId ?>', v.wfmstatuscolor, v.wfmstatusname);
                                                }
                                            };

                                        }
                                    } else if (v.wfmstatusprocessid != '' && v.wfmstatusprocessid != 'null' && v.wfmstatusprocessid != null) {

                                        var wfmStatusCode = ('wfmstatuscode' in Object(v)) ? v.wfmstatuscode : ''; 
                                        var metaTypeId = ('metatypeid' in Object(v)) ? v.metatypeid : '200101010000011';

                                        if (v.wfmisneedsign == '1') {

                                            contextMenuData[v.wfmstatusid] = {
                                                name: wfmIcon+v.wfmstatusname+' <i class="fa fa-key"></i>', 
                                                isHtmlName: true,  
                                                callback: function(key, options) {

                                                    var $el = options.$trigger;

                                                    if (typeof v.advancedCriteria !== "undefined" && v.advancedCriteria !== null) {
                                                        $el.attr('data-advanced-criteria', v.advancedCriteria.replace(/\"/g, ''));
                                                    }

                                                    transferProcessAction('signProcess', '<?php echo $this->dataViewId ?>', v.wfmstatusprocessid, metaTypeId, 'toolbar', $el, {callerType: '<?php echo $this->metaDataCode ?>', isWorkFlow: true, wfmStatusId: v.wfmstatusid, wfmStatusCode: wfmStatusCode}, 'dataViewId=<?php echo $this->dataViewId; ?>&refStructureId=<?php echo $this->refStructureId; ?>&statusId='+v.wfmstatusid+'&statusName='+v.wfmstatusname+'&statusColor='+v.wfmstatuscolor+'&rowId='+rowId);
                                                }
                                            };

                                        } else if (v.wfmisneedsign == '2') {

                                            contextMenuData[v.wfmstatusid] = {
                                                name: wfmIcon+v.wfmstatusname+' <i class="fa fa-key"></i>', 
                                                isHtmlName: true,  
                                                callback: function(key, options) {

                                                    var $el = options.$trigger;

                                                    if (typeof v.advancedCriteria !== "undefined" && v.advancedCriteria !== null) {
                                                        $el.attr('data-advanced-criteria', v.advancedCriteria.replace(/\"/g, ''));
                                                    }

                                                    transferProcessAction('hardSignProcess', '<?php echo $this->dataViewId ?>', v.wfmstatusprocessid, metaTypeId, 'toolbar', $el, {callerType: '<?php echo $this->metaDataCode ?>', isWorkFlow: true, wfmStatusId: +v.wfmstatusid, wfmStatusCode: wfmStatusCode}, 'dataViewId=<?php echo $this->dataViewId; ?>&refStructureId=<?php echo $this->refStructureId; ?>&statusId='+v.wfmstatusid+'&statusName='+v.wfmstatusname+'&statusColor='+v.wfmstatuscolor+'&rowId='+rowId);
                                                }
                                            };

                                        } else {

                                            contextMenuData[v.wfmstatusid] = {
                                                name: wfmIcon + v.wfmstatusname, 
                                                isHtmlName: true,  
                                                callback: function(key, options) {

                                                    var $el = options.$trigger;

                                                    if (typeof v.advancedCriteria !== "undefined" && v.advancedCriteria !== null) {
                                                        $el.attr('data-advanced-criteria', v.advancedCriteria.replace(/\"/g, ''));
                                                    }

                                                    transferProcessAction('', '<?php echo $this->dataViewId; ?>', v.wfmstatusprocessid, metaTypeId, 'toolbar', $el, {callerType: '<?php echo $this->metaDataCode ?>', isWorkFlow: true, wfmStatusId: v.wfmstatusid, wfmStatusCode: wfmStatusCode}, 'dataViewId=<?php echo $this->dataViewId; ?>&refStructureId=<?php echo $this->refStructureId; ?>&statusId='+v.wfmstatusid+'&statusName='+v.wfmstatusname+'&statusColor='+v.wfmstatuscolor+'&rowId='+rowId);
                                                }
                                            };

                                        }
                                    }    
                                }

                            });
                        }
                    }
                });

                if (!isIgnoreWfmHistory_<?php echo $this->dataViewId; ?>) {

                    contextMenuData['wfmHistory'] = {
                        name: plang.getDefault('wfm_log_history', 'Өөрчлөлтийн түүх харах'), 
                        isHtmlName: true,  
                        callback: function(key, options) {
                            seeWfmStatusForm(this, '<?php echo $this->dataViewId; ?>');
                        }
                    };
                }
                <?php
                }

                $firstMetaId = isset($this->dataViewProcessCommand['commandAddMeta'][0]['PROCESS_META_DATA_ID']) ? $this->dataViewProcessCommand['commandAddMeta'][0]['PROCESS_META_DATA_ID'] : '';
                $firstMetaTypeId = isset($this->dataViewProcessCommand['commandAddMeta'][0]['META_TYPE_ID']) ? $this->dataViewProcessCommand['commandAddMeta'][0]['META_TYPE_ID'] : '';
                if ($firstMetaId) { ?>

                    contextMenuData['0'] = {
                        name: plang.get('add_btn'), 
                        icon: 'plus',
                        isHtmlName: true,  
                        callback: function(key, options) {
                            _processAddonParam['addonJsonParam'] = JSON.stringify({"startDate":$(this).attr('data-date'),"endDate":$(this).attr('data-date')});
                            privateTransferProcessAction('<?php echo $this->dataViewId; ?>', '<?php echo $firstMetaId; ?>', '<?php echo $firstMetaTypeId; ?>', 'toolbar', this, {callerType: "IA_CREATED_MY_TASK_LIST"}, $('div[id="objectdatagrid-<?php echo $this->dataViewId; ?>"]'), false, undefined, undefined, undefined, undefined, undefined);
                        }
                    };

                <?php } ?>

                var options =  {
                    callback: function (key, opt) {
                        eval(key);
                    },
                    items: contextMenuData
                };

                return options;            
            }
        });    
        
        var timerGanttCellHover;
    
        $(document.body).on('mouseenter', '.gantt_tree_content', function() {

            var self = this;

            timerGanttCellHover = setTimeout(function() {

                var $this = $(self);
                var cellText = $this.text().trim();

                if (cellText != '') {
                    $this.qtip({
                        content: {
                            text: '<div style="max-width:600px;max-height:450px;overflow-y:auto;overflow-x:hidden;">' + cellText + '</div>'
                        },
                        position: {
                            effect: false,
                            at: 'top center',
                            my: 'bottom center',
                            viewport: $(window) 
                        }, 
                        show: {
                            ready: true,
                            effect: false
                        },
                        hide: {
                            effect: false, 
                            fixed: true,
                            delay: 70
                        },
                        style: {
                            classes: 'qtip-bootstrap',
                            tip: {
                                width: 10,
                                height: 5
                            }
                        }, 
                        events: {
                            hidden: function(event, api) {
                                api.destroy(true);
                            }
                        }
                    });
                }
            }, 600);
        });

        $(document.body).on('mouseleave', '.gantt_tree_content', function() {
            if (timerGanttCellHover) {
                clearTimeout(timerGanttCellHover);
            }
        }); 
    
    });
    
    function explorerRefresh_<?php echo $this->dataViewId; ?>(elem, dvSearchParam) {
        gantt.clearAll();
        gantt.load('mdobject/getGanttChartData/<?php echo $this->dataViewId; ?>?param='+decodeURIComponent(escape(btoa(dvSearchParam.defaultCriteriaData))));
        gantt.refreshData();
        //dataViewFolderChildList_<?php echo $this->dataViewId; ?>('<?php echo $this->dataViewId; ?>', '<?php echo $this->refStructureId; ?>', '');
    }
    
    function dvRowSelector_<?php echo $this->dataViewId ?>(e, type, isIgnoreAlert) {
        console.log('dvRowSelector_');
    }
    
    function onFCUserImgError(source) {
        source.src = "assets/custom/addon/admin/layout4/img/user.png";
        source.onerror = "";
        return true;
    }
    
    function loadDHtmlXScripts() {
        if (typeof (gantt) == 'undefined') {
            $('head').append('<link rel="stylesheet" type="text/css" href="assets/custom/addon/plugins/dhtmlx/gantt/7.0.10/skins/dhtmlxgantt_material.css?v=9"/>');
        }
        
        var scripts = [
            'assets/custom/addon/plugins/dhtmlx/gantt/7.1.12/dhtmlxgantt.js',
            'assets/custom/addon/plugins/dhtmlx/gantt/7.0.10/locale/locale_<?php echo $this->lang->getCode(); ?>.js'
        ];

        scripts.forEach(function(url) { 
            $.ajax({
                type: 'GET',
                url: url,
                async: false,
                cache: true,
                dataType: 'script'
            });
        });
        
        return true;
    }
</script>