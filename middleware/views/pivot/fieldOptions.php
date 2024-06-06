<div id="pv-field-<?php echo $this->uniqId; ?>">
    <div class="col-md-12 pl0 pr0 mb10">
        <div class="pv-caption-name">
            <i class="fa fa-th"></i> <span>Талбарууд</span>
        </div>
        <div class="pv-all-fields">
            <?php
            if ($this->allFields) {
                foreach ($this->allFields as $field) {
                    $allFieldLabel = $this->lang->line($field['LABEL_NAME']);
            ?>
            <div class="pv-field" data-field-name="<?php echo Str::lower($field['FIELD_PATH']); ?>" data-field-type="<?php echo $field['META_TYPE_CODE']; ?>" title="<?php echo $allFieldLabel; ?>">
                <i class="fa fa-caret-right"></i> <span><?php echo $allFieldLabel; ?></span>
            </div>
            <?php
                }
            }
            ?>
        </div>
    </div>
    <div class="tabbable-line">
        <ul class="nav nav-tabs">
            <li class="nav-item">
                <a href="#tab_pivot_option_grid<?php echo $this->uniqId ?>" id="pivot_option_grid<?php echo $this->uniqId ?>" data-toggle="tab" class="nav-link active">Грид</a>
            </li>
            <li class="nav-item">
                <a href="#tab_pivot_option_dashboard<?php echo $this->uniqId ?>" id="pivot_option_dashboard<?php echo $this->uniqId ?>" data-toggle="tab" class="nav-link">Дашбоард</a>
            </li>
        </ul>
        <div class="tab-content">
            <div class="tab-pane active" id="tab_pivot_option_grid<?php echo $this->uniqId ?>">
                <div class="col-md-6 pl0 pr0">
                    <div class="pv-caption-name">
                        <i class="fa fa-filter"></i> <span>Шүүлтүүр</span>
                    </div>
                    <div class="pv-part-fields pv-filters">
                        <?php echo $this->filterFieldsHtml; ?>
                    </div>
                    <div class="pv-caption-name mt10">
                        <i class="fa fa-bars"></i> <span>Мөр</span>
                    </div>
                    <div class="pv-part-fields pv-rows">
                        <?php echo $this->rowFieldsHtml; ?>
                    </div>
                </div>
                <div class="col-md-6 pl0 pr0">
                    <div class="pv-caption-name">
                        <i class="fa fa-columns"></i> <span>Багана</span>
                    </div>
                    <div class="pv-part-fields pv-columns">
                        <?php echo $this->columnFieldsHtml; ?>
                    </div>
                    <div class="pv-caption-name mt10">
                        <i class="fa fa-database"></i> <span>Утга</span>
                    </div>
                    <div class="pv-part-fields pv-values">
                        <?php echo $this->valueFieldsHtml; ?>
                    </div>
                </div>
            </div>
            <div class="tab-pane pvdashboard-field-<?php echo $this->uniqId; ?>" id="tab_pivot_option_dashboard<?php echo $this->uniqId ?>">
                <div class="col-md-12 pl0 pr0 mb5">
                    <div class="pv-caption-name">
                        <i class="fa fa-filter"></i> <span>Төрөл</span>
                    </div>
                    <?php 
                    echo Form::select(
                        array(
                            'name' => 'pv-dashboardtype', 
                            'id' => 'pv-dashboardtype_'. $this->uniqId, 
                            'class' => 'form-control form-control-sm select2me', 
                            'data' => Info::getDiagramType('amchart', '1'), 
                            'op_value' => 'CODE', 
                            'op_text' => 'NAME'
                        )
                    ); 
                    ?>
                </div>
                <div class="pvdashboard_<?php echo $this->uniqId ?> hidden">
                    <div class="col-md-6 pl0 pr0">
                        <div class="pv-caption-name">
                            <i class="fa fa-long-arrow-right"></i> <span>Х утга</span>
                        </div>
                        <div class="pv-part-fields pvdashboard-columns pv-dashboard">

                        </div>
                        <div class="pv-caption-name mt10 hidden">
                            <i class="fa fa-filter"></i> <span>Шүүлтүүр</span>
                        </div>
                        <div class="pv-part-fields pvdashboard-filters pv-dashboard hidden">

                        </div>
                    </div>
                    <div class="col-md-6 pl0 pr0">
                        <div class="pv-caption-name">
                            <i class="fa fa-long-arrow-up"></i> <span>У утга</span>
                        </div>
                        <div class="pv-part-fields pvdashboard-rows pv-dashboard">

                        </div>
                        <div class="pv-caption-name mt10 pvdashboard-groupvalues-<?php echo $this->uniqId ?> hidden">
                            <i class="fa fa-database"></i> <span>Бүлэглэх утга</span>
                        </div>
                        <div class="pv-part-fields pvdashboard-values pvdashboard-groupvalues-<?php echo $this->uniqId ?>  pv-dashboard hidden">

                        </div>
                    </div>
                </div>
                
            </div>
        </div>
    </div>
</div>
<style type="text/css">
    .pvdashboard-rows .pv-field {
        padding: 1px 0 1px 7px !important;
    }
</style>
<script type="text/javascript">

$(function() {
    $('.pv-part-fields', '#pv-field-<?php echo $this->uniqId; ?>').sortable({
        items: 'div.pv-field', 
        connectWith: '.pv-part-fields', 
        revert: 100, 
        over: function() {
            $(this).addClass('drop-hover');
        },
        out: function() {
            $(this).removeClass('drop-hover');
        }, 
        receive: function(event, ui) {
            var dthis = $(this);
            var fieldName = ui.item.attr('data-field-name');
            
            if ($(event.target).hasClass('pv-dashboard') && $(event.target).find('.pv-field').length > 1) {
                dthis.find("div[data-field-name='"+fieldName+"']:first").remove();  
                return;
            }
            
            var sumField = $('.pv-columns', '#pv-field-<?php echo $this->uniqId; ?>').find("div[data-field-name='"+fieldName+"']").length + 
                    $('.pv-rows', '#pv-field-<?php echo $this->uniqId; ?>').find("div[data-field-name='"+fieldName+"']").length + 
                    $('.pv-values', '#pv-field-<?php echo $this->uniqId; ?>').find("div[data-field-name='"+fieldName+"']").length;
            
            if (sumField > 1) {
                dthis.find("div[data-field-name='"+fieldName+"']:first").remove();  
                return;
            }
            
            if (dthis.find("div[data-field-name='"+fieldName+"']").length > 1) {
                dthis.find("div[data-field-name='"+fieldName+"']:not(:first)").remove();  
            }
        }, 
        update: function(event, ui) {
            if ($(ui.item).parent().hasClass('pv-values')) {
                $(ui.item).html('<span>' + $(ui.item).text() + '</span><div class="right-button"><i class="fa fa-caret-down"></i></div>');
            }
            if ($(ui.item).parent().hasClass('pvdashboard-rows')) {
                $(ui.item).html('<span>' + $(ui.item).text() + '</span><div class="right-button" onclick="rightBtnClickFuntion_<?php echo $this->uniqId ?>(this)"><i class="fa fa-caret-down"></i></div>');
            }
            
            if ($(event.target).hasClass('pv-dashboard')) {
                refreshPivotDashboardArea_<?php echo $this->uniqId; ?>();
            } else {
                refreshPivotArea_<?php echo $this->uniqId; ?>();
            }
        }
    });
    
    $('.pv-part-fields', '#pv-field-<?php echo $this->uniqId; ?>').disableSelection();
    
    $('.pv-field', '#pv-field-<?php echo $this->uniqId; ?> .pv-all-fields').draggable({
        connectToSortable: ".pv-part-fields",
        stack: '.pv-part-fields',
        revert: 'invalid',
        helper: 'clone',
        cursor: 'move',
        scroll: true,
        drag: function(event, ui) {
            ui.helper.width(116).height(22).css("z-index", "9999").addClass("shadow-dark bg-grey-steel card-rotate");
            $(ui.helper.prevObject).addClass("card-current");
        },
        stop: function(event, ui) {
            ui.helper.find('i').remove();
            ui.helper.removeAttr("style").removeClass("shadow-dark bg-grey-steel card-rotate");
            $(ui.helper.prevObject).removeClass("card-current");
        }
    });
    
    $('.pv-part-fields', '#pv-field-<?php echo $this->uniqId; ?>').droppable({
        accept: '.pv-field',
        /*hoverClass: 'drop-hover',*/
        /*drop: function(event, ui) {
            
            var dthis = $(this);
            var isAlready = false;
            var isAllFieldFrom = false;
            
            if ($(ui.helper.prevObject).parent().hasClass('pv-all-fields')) {
                var $element = ui.draggable.clone();
                isAllFieldFrom = true;
            } else {
                var $element = ui.draggable;
            }
            
            var fieldName = $element.attr('data-field-name');
            
            if (isAllFieldFrom && (dthis.hasClass('pv-columns') || dthis.hasClass('pv-rows') || dthis.hasClass('pv-values'))) {
                if (
                    $('.pv-columns', '#pv-field-<?php echo $this->uniqId; ?>').find("div[data-field-name='"+fieldName+"']").length > 0 || 
                    $('.pv-rows', '#pv-field-<?php echo $this->uniqId; ?>').find("div[data-field-name='"+fieldName+"']").length > 0 || 
                    $('.pv-values', '#pv-field-<?php echo $this->uniqId; ?>').find("div[data-field-name='"+fieldName+"']").length > 0
                    ) {
                    isAlready = true;
                }
            }
            alert(dthis.find("div[data-field-name='"+fieldName+"']").length);
            if (!isAlready && dthis.find("div[data-field-name='"+fieldName+"']").length === 0) {
                
                $element.find('i').remove();
                dthis.append($element);

                $element.draggable({
                    stack: '.pv-part-fields',
                    revert: 'invalid',
                    helper: 'clone',
                    cursor: 'move',
                    scroll: true,
                    drag: function(event, ui) {
                        ui.helper.width($(this).width());
                        ui.helper.css("z-index", "9999");
                        ui.helper.addClass("shadow-dark bg-grey-steel card-rotate");
                        $(ui.helper.prevObject).addClass("card-current");
                    },
                    stop: function(event, ui) {
                        ui.helper.width($(this).width());
                        $(ui.helper.prevObject).removeClass("card-current");
                        $(".card-column div.card").css("z-index", "auto");
                    }
                });
                
                $(ui.helper).remove();
                
                refreshPivotArea_<?php echo $this->uniqId; ?>();
            }
        }*/
    });
    
    $('.pv-all-fields', '#pv-field-<?php echo $this->uniqId; ?>').droppable({
        accept: '.pv-field',
        hoverClass: 'drop-hover',
        drop: function(event, ui) {
            if (!$(ui.draggable).parent().hasClass('pv-all-fields')) {
                $(ui.helper).remove();
                $(ui.draggable).remove();
                //refreshPivotArea_<?php echo $this->uniqId; ?>();
            } 
        }  
    });
    
    $('.right-button', '#pv-field-<?php echo $this->uniqId; ?> .pv-values').on('click', function() {
        var _this = $(this);
        var _parent = _this.closest('.pv-field');
        var _oldAggrName = _parent.attr('data-aggr-name');
        
        $.ajax({
            type: 'post',
            url: 'mdpivot/chooseAggregate',
            data: {aggrName: _oldAggrName},
            dataType: 'json',
            beforeSend: function(){
                Core.blockUI({
                    message: 'Loading...',
                    boxed: true
                });
            },
            success: function(data) {
                var dialogName = '#dialog-pivot-aggr';
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
                    width: 250,
                    height: 'auto',
                    modal: true,
                    close: function(){
                        $(dialogName).empty().dialog('destroy').remove();
                    },
                    buttons: [
                        {text: data.choose_btn, class: 'btn green-meadow btn-sm', click: function () {
                            var _newAggrName = $(dialogName).find('input[type=radio]:checked').val();
                            
                            $(dialogName).dialog('close');
                            
                            if (_oldAggrName !== _newAggrName) {
                                _parent.attr('data-aggr-name', _newAggrName);
                                refreshPivotArea_<?php echo $this->uniqId; ?>();
                            }
                        }}, 
                        {text: data.close_btn, class: 'btn blue-madison btn-sm', click: function () {
                            $(dialogName).dialog('close');
                        }}
                    ]
                });
                $(dialogName).dialog('open');                

                Core.initDVAjax($(dialogName));
                Core.unblockUI();
            },
            error: function() {
                alert("Error");
            }
        });
    });
    
});

function rightBtnClickFuntion_<?php echo $this->uniqId ?>(element) {
    var _this = $(element);
    var _parent = _this.closest('.pv-field');
    var _oldAggrName = _parent.attr('data-aggr-name');

    $.ajax({
        type: 'post',
        url: 'mdpivot/chooseAggregate',
        data: {aggrName: _oldAggrName},
        dataType: 'json',
        beforeSend: function(){
            Core.blockUI({
                message: 'Loading...',
                boxed: true
            });
        },
        success: function(data) {
            var dialogName = '#dialog-pivot-aggr';
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
                width: 250,
                height: 'auto',
                modal: true,
                close: function(){
                    $(dialogName).empty().dialog('destroy').remove();
                },
                buttons: [
                    {text: data.choose_btn, class: 'btn green-meadow btn-sm', click: function () {
                        var _newAggrName = $(dialogName).find('input[type=radio]:checked').val();
                        
                        $(dialogName).dialog('close');

                        if (_oldAggrName !== _newAggrName) {
                            _parent.attr('data-aggr-name', _newAggrName);
                            refreshPivotDashboardArea_<?php echo $this->uniqId; ?>();
                        }
                    }}, 
                    {text: data.close_btn, class: 'btn blue-madison btn-sm', click: function () {
                        $(dialogName).dialog('close');
                    }}
                ]
            });
            $(dialogName).dialog('open');                

            Core.initDVAjax($(dialogName));
            Core.unblockUI();
        },
        error: function() {
            alert("Error");
        }
    });
}

function refreshPivotArea_<?php echo $this->uniqId; ?>() {
    
    var _main = $('#pv-field-<?php echo $this->uniqId; ?>');
    var _wrap = _main.closest('.pivotgrid-table'); 
    var _wrapGrid = _wrap.find('.pv-grid'); 
    var dmReportId = _wrapGrid.attr('data-dm-report-id');
    var filters = {}, columns = {}, rows = {}, values = {};
    
    _main.find('.pv-filters').find('.pv-field').each(function(fi, fk){
        if ($(this).attr('data-field-name') !== '') {
            filters[fi] = $(this).attr('data-field-name');
        }
    });
    
    _main.find('.pv-columns').find('.pv-field').each(function(ci, ck){
        var _this = $(this);
        if ($.trim(_this.text()) !== '') {
            var columnRow = {
                'fieldName': _this.attr('data-field-name'), 
                'labelName': $.trim(_this.text()), 
                'fieldType': _this.attr('data-field-type')
            };
            columns[ci] = columnRow;
        }
    });
    
    _main.find('.pv-rows').find('.pv-field').each(function(ri, rk){
        var _this = $(this);
        if ($.trim(_this.text()) !== '') {
            var rowRow = {
                'fieldName': _this.attr('data-field-name'), 
                'labelName': $.trim(_this.text()), 
                'fieldType': _this.attr('data-field-type')
            };
            rows[ri] = rowRow;
        }
    });
    
    _main.find('.pv-values').find('.pv-field').each(function(vi, vk){
        var _this = $(this);
        if ($.trim(_this.text()) !== '') {
            var valRow = {
                'fieldName': _this.attr('data-field-name'), 
                'labelName': $.trim(_this.text()), 
                'aggrName': (typeof _this.attr('data-aggr-name') !== 'undefined') ? _this.attr('data-aggr-name') : 'sum', 
                'dataType': _this.attr('data-field-type') 
            };
            values[vi] = valRow;
        }
    });
    
    $.ajax({
        type: 'post',
        url: 'mdpivot/renderPivotGrid',
        data: {
            dmReportId: dmReportId, 
            defaultCriteriaData: _wrapGrid.find('form').serialize(), 
            filters: filters, 
            columns: columns, 
            rows: rows, 
            values: values, 
            runMode: '<?php echo $this->runMode; ?>', 
            fieldChooserMode: 1 
        }, 
        dataType: 'json',
        beforeSend: function () {
            Core.blockUI({
                boxed: true, 
                message: 'Loading...'
            });
        },
        success: function (data) {
            _wrapGrid.html(data.grid);
        },
        error: function () {
            alert('Error');
        }
    }).done(function() {
        Core.initDVAjax(_wrapGrid);
        Core.unblockUI();
    });
}

$('#pv-dashboardtype_<?php echo $this->uniqId ?>').on('change', function () {
    $('.pvdashboard-groupvalues-<?php echo $this->uniqId ?>').addClass('hidden');
    var _thisval = $(this).val();
    if (_thisval.length > 0) {
        $('.pvdashboard_<?php echo $this->uniqId ?>').addClass('hidden');
        
        switch (_thisval) { 
            case 'am_stacked_bar_chart' : 
            case 'am_reversed' : {
                
                break;
            }
            case 'am_zoomable_value_axis' : 
            case 'am_trend_lines' : {
                break;
            }
            case 'clustered_bar_chart' : 
            case 'am_3d_stacked_column_chart' : {
                $('.pvdashboard_<?php echo $this->uniqId ?>').removeClass('hidden');
                $('.pvdashboard-groupvalues-<?php echo $this->uniqId ?>').empty().removeClass('hidden');
                
                break;
            }
            case 'am_combined_bullet' : {
                break;
            }
            default : {
                $('.pvdashboard_<?php echo $this->uniqId ?>').removeClass('hidden');
            }
        }
        
        
        if ($('.pvdashboard-columns', '.pvdashboard-field-<?php echo $this->uniqId; ?>').length > 0 && $('.pvdashboard-rows', '.pvdashboard-field-<?php echo $this->uniqId; ?>').length > 0) {
            refreshPivotDashboardArea_<?php echo $this->uniqId; ?>();
        }
        Core.initAjax();
    }
    $('#pv-dashboard-render-<?php echo $this->uniqId ?>').empty();
});

function refreshPivotDashboardArea_<?php echo $this->uniqId; ?>() {
    var filters = {}, xaxis = {}, yaxis = {}, groupvalues = {};
    
    var _main = $('#pv-field-<?php echo $this->uniqId; ?>');
    var _wrap = _main.closest('.pivotgrid-table'); 
    var _wrapGrid = _wrap.find('.pv-grid'); 
    var dmReportId = _wrapGrid.attr('data-dm-report-id');
    
    _main.find('.pvdashboard-filters').find('.pv-field').each(function(fi, fk) {
        if ($(this).attr('data-field-name') !== '') {
            filters[fi] = $(this).attr('data-field-name');
        }
    });
    
    _main.find('.pvdashboard-columns').find('.pv-field').each(function(ci, ck) {
        var _this = $(this);
        if ($.trim(_this.text()) !== '') {
            var columnRow = {
                'fieldName': _this.attr('data-field-name'), 
                'labelName': $.trim(_this.text()), 
                'fieldType': _this.attr('data-field-type')
            };
            /* xaxis[ci] = columnRow; */
            xaxis = columnRow;
        }
    });
    
    _main.find('.pvdashboard-rows').find('.pv-field').each(function(ri, rk){
        var _this = $(this);
        if ($.trim(_this.text()) !== '') {
            var rowRow = {
                'fieldName': _this.attr('data-field-name'), 
                'labelName': $.trim(_this.text()), 
                'aggrName': (typeof _this.attr('data-aggr-name') !== 'undefined') ? _this.attr('data-aggr-name') : 'sum', 
                'fieldType': _this.attr('data-field-type')
            };
            /* yaxis[ri] = rowRow; */
            yaxis = rowRow;
        }
    });
    
    _main.find('.pvdashboard-values').find('.pv-field').each(function(vi, vk) {
        var _this = $(this);
        if ($.trim(_this.text()) !== '') {
            var valRow = {
                'fieldName': _this.attr('data-field-name'), 
                'labelName': $.trim(_this.text()), 
                'aggrName': (typeof _this.attr('data-aggr-name') !== 'undefined') ? _this.attr('data-aggr-name') : 'sum', 
                'dataType': _this.attr('data-field-type') 
            };
            /* groupvalues[vi] = valRow; */
            groupvalues = valRow;
        }
    });
    
    $.ajax({
        type: 'post',
        url: 'mdpivot/renderPivotDashboard',
        data: {
            uniqId: '<?php echo $this->uniqId; ?>', 
            dmReportId: dmReportId, 
            dashboardTypeId: $('#pv-dashboardtype_<?php echo $this->uniqId; ?>').val(), 
            defaultCriteriaData: _wrapGrid.find('form').serialize(), 
            filters: filters, 
            xaxis: xaxis, 
            yaxis: yaxis, 
            groupvalues: groupvalues, 
            runMode: '<?php echo $this->runMode; ?>', 
            fieldChooserMode: 1 
        }, 
        dataType: 'json',
        beforeSend: function () {
            Core.blockUI({
                boxed: true, 
                message: 'Loading...'
            });
            
            $('#pv-dashboard-render-<?php echo $this->uniqId ?>').empty().hide();
            $('.pv-dashboard-message-<?php echo $this->uniqId ?>').hide();
        },
        success: function (data) {
            $('#pivotdashboard<?php echo $this->uniqId ?>').trigger('click');
            if (data.status === 'success') {
                $('#pv-dashboard-render-<?php echo $this->uniqId ?>').show();
                $.getScript(URL_APP + 'assets/custom/addon/plugins/amcharts/amcharts/amChartMinify.js').done(function( script, textStatus2 ) {
                    if (textStatus2 === 'success') {
                        $.getScript(URL_APP + 'middleware/assets/js/dashboard/pvAmcharts.js').done(function( script, textStatus3 ) {
                            if (textStatus3 === 'success') {
                                $.getScript(URL_APP + 'assets/custom/addon/plugins/amcharts/amcharts/plugins/export/export.min.js').done(function( script, textStatus4) {
                                    if (textStatus4 === 'success') {
                                        $("head").append('<link rel="stylesheet" type="text/css" href="assets/custom/addon/plugins/amcharts/amcharts/plugins/export/export.css"/>');
                                        amChartMinify.init();
                                        pvAmcharts.renderPvDashboard(data, 'pv-dashboard-render-<?php echo $this->uniqId ?>');
                                    }
                                });
                            }
                        });
                    }
                });
            } else {
                $('.pv-dashboard-message-<?php echo $this->uniqId ?>').show();
            }
        },
        error: function (data) {
            Core.unblockUI();
            $('.pv-dashboard-message-<?php echo $this->uniqId ?>').show();
        }
    }).done(function() {
        Core.initDVAjax(_wrapGrid);
        Core.unblockUI();
    });
}

</script>