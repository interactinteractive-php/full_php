<div class="dashboard-render">
    <div id="dashboard-container-<?php echo $this->metaDataId; ?>" class="dashboard-container" processMetaDataId="<?php echo isset($this->processMetaDataId) ? $this->processMetaDataId : '0'; ?>">
        <div class="card light bordered mb0 pb5 mddashboard-card">
            <div class="card-title mddashboard-card-title d-flex" id="card-title-<?php echo $this->metaDataId; ?>">
                <div class="caption mddashboard-caption">
                    <span class="caption-subject font-weight-bold mddashboard-title" title="" id="dashboard-title-<?php echo $this->metaDataId; ?>"></span>
                    <span class="caption-helper mddashboard-helper" id="dashboard-helper-<?php echo $this->metaDataId; ?>"></span>
                </div>
                <div class="btn-group btn-group-circle pull-right mddashboard-actions">
                    <?php 
                    $getAddonSettings = json_decode(Arr::get($this->diagram, 'ADDON_SETTINGS'), true);
                    $criteriaPosition = Arr::get($getAddonSettings, 'criteriaPosition');
                    
                    if ($this->executeType != 'zoom') { 
                    ?>
                        <button type="button" tabindex="-1" class="btn btn-sm btn-light btn-primary mr-1" onclick="callDiagramByMeta('<?php echo explode('_', $this->metaDataId)[0]; ?>', '');"><i class="fa fa-expand"></i></button>
                        <?php 
                        if ($this->diagram['IS_USE_GRAPH'] || $this->diagram['IS_USE_META'] || $this->diagram['IS_USE_CRITERIA'] || $this->diagram['IS_USE_LIST']) { 
                        ?>
                            <div class="btn-group">
                                <button type="button" class="btn btn-primary btn-light btn-sm dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><?php echo $this->lang->line('dropdown_action') ?></button>
                                <div class="dropdown-menu dropdown-menu-right">
                                    <?php 
                                    if ($this->diagram['IS_USE_GRAPH']) { 
                                    ?>
                                    <a href="javascript:;" class="dropdown-item" id="graph-btn-<?php echo $this->metaDataId; ?>"><i class="icon-graph"></i> <?php echo $this->lang->line('dropdown_graph') ?></a>
                                    <?php 
                                    } 
                                    if ($this->diagram['IS_VIEW_DATAGRID'] != '1' && $this->diagram['IS_USE_LIST']) { 
                                    ?>
                                    <a href="javascript:;" class="dropdown-item" id="list-btn-<?php echo $this->metaDataId; ?>"><i class="icon-menu7"></i> <?php echo $this->lang->line('dropdown_list') ?></a>
                                    <?php 
                                    } 
                                    if ($this->diagram['IS_USE_CRITERIA'] && isset($this->defaultCriteria) && $this->defaultCriteria && $criteriaPosition !== 'top' && $criteriaPosition !== 'topFilterButton') { 
                                    ?>
                                    <a href="javascript:;" class="dropdown-item" id="search-btn-<?php echo $this->metaDataId; ?>"><i class="icon-filter4"></i> <?php echo $this->lang->line('dropdown_filter') ?></a>
                                    <?php 
                                    } 
                                    ?>
                                </div>
                            </div>       
                    <?php 
                        } 
                    } 
                    ?>
                </div>
            </div>
            <div class="card-body dashboard-content-<?php echo $this->metaDataId; ?>">
                <?php   
                if ($criteriaPosition === 'top' || $criteriaPosition === 'topFilterButton') {
                ?>
                    <div id="dashboard-filter-div-<?php echo $this->metaDataId; ?>">
                        <form class="dashboard-filter-form-<?php echo $this->metaDataId; ?>">
                            <?php echo isset($this->defaultCriteria) ? $this->defaultCriteria : ''; ?>
                        </form>
                    </div>     
                <?php 
                } 
                ?>
                <div id="dashboard-<?php echo $this->metaDataId; ?>">
                    <img src="assets/core/global/img/loading.gif" />
                </div>
                <div id="customLegendDiv-<?php echo $this->metaDataId; ?>" class="pf-chart-svg-ignore-position">
                </div>
            </div>
        </div>
    </div>
    <div class="clearfix w-100"></div>
    <div id="dashboard-datagrid-view-<?php echo $this->metaDataId; ?>"></div>    
</div>
<?php if ($criteriaPosition !== 'top' && $criteriaPosition !== 'topFilterButton') { ?>
    <div id="dashboard-filter-div-<?php echo $this->metaDataId; ?>" class="hidden">
        <form class="dashboard-filter-form-<?php echo $this->metaDataId; ?>">
            <?php echo isset($this->defaultCriteria) ? $this->defaultCriteria : ''; ?>
        </form>
    </div>
<?php } ?>


<style type="text/css">
  #dashboard-<?php echo $this->metaDataId; ?> {    
      <?php if ($this->diagram['HEIGHT'] == null) { ?>
          min-height: 450px;
          height: auto;
      <?php } else {
          ?>
          height: <?php echo (($this->diagram['HEIGHT'] < 300) ? '300' : $this->diagram['HEIGHT']) . 'px'; ?>;
      <?php }
      ?>
  }
  <?php if ($this->diagram['IS_VIEW_DATAGRID'] == '1') { ?>
    #dashboard-datagrid-view-<?php echo $this->metaDataId; ?> {    
        <?php if ($this->diagram['HEIGHT'] == null) { ?>
            min-height: 450px;
            margin-top: 15px;
            height: auto;
        <?php } else {
            ?>
            margin-top: 15px;
            height: <?php echo $this->diagram['HEIGHT'] . 'px'; ?>;
        <?php }
        ?>
    }
  <?php } ?>  
    .pf-chart-svg-ignore-position > svg {
        top: unset !important;
        left: unset !important;
    }
</style>

<script type="text/javascript">
    var isSubChart = 0;
    var selectedMetaDataId_<?php echo $this->metaDataId; ?> = '<?php echo $this->metaDataId; ?>';
    var selectedChartEvent = [], executeType = '<?php echo isset($this->executeType) ? $this->executeType : '' ?>';
    var chartCategoryAxisFontSize = 11;
    var chartValueAxesFontSize = 11;
    var chartValueFontSize = 11;

    $(document).ready(function(){
        amChartMinify.init();
        isSubChart = 0;
        var criteriaString = '';
        
        if (executeType != '' && executeType.indexOf('layout-') === -1) {
            if (executeType == 'gmap')
                criteriaString = 'param[id]=<?php echo $this->rowIdGmap; ?>';
            else if (executeType == 'chart')
                criteriaString = '<?php echo issetParam($this->criteriaString); ?>';
            else if (executeType.indexOf('layout-') !== -1) {
                criteriaString = $("div#"+executeType).find('form:eq(0)').serialize();
            } else
                criteriaString = $("div#dataview-statement-search-"+executeType+" > fieldset > form").serialize();
            
        } else {
            criteriaString = $('#dashboard-filter-div-<?php echo $this->metaDataId; ?>').find('.dashboard-filter-form-<?php echo $this->metaDataId; ?>').serialize();
        }
        
        var $topFilterForm = $('#dashboard-filter-div-<?php echo $this->metaDataId; ?>');
        
        <?php
        if (isset($this->setHeight) && $this->setHeight > 300) {
        ?>
        if ($topFilterForm.length) {
            var filterHeight = $topFilterForm.height();
            if (filterHeight) {
                var calcHeight = <?php echo $this->setHeight; ?> - filterHeight;
                if (calcHeight < 300) {
                    $('#dashboard-<?php echo $this->metaDataId; ?>').height(300);
                } else {
                    $('#dashboard-<?php echo $this->metaDataId; ?>').height(calcHeight);
                }
            }
        }
        <?php
        }
        ?>
        dvFilterDateCheckInterval($topFilterForm);
        $topFilterForm.find('input[type=text][readonly]').removeAttr('readonly');   
        
        ChartsAmcharts.drawChartAmchart(criteriaString, '<?php echo $this->diagram['DIAGRAM_TYPE']; ?>', '<?php echo $this->metaDataId; ?>', '', '<?php echo $this->workSpaceParams; ?>', '<?php echo $this->workSpaceId; ?>', '<?php echo $criteriaPosition; ?>');
    });

    $("#search-btn-<?php echo $this->metaDataId; ?>").on("click", function() {
        var $dialogName='dialog-search-dashboard-<?php echo $this->metaDataId; ?>-divid';
        if(!$("#" + $dialogName).length) {
            $('<div id="' + $dialogName + '"></div>').appendTo('body');
            $('#dashboard-filter-div-<?php echo $this->metaDataId; ?>').find('div.select2').remove();
            $("#" + $dialogName).empty().html($('#dashboard-filter-div-<?php echo $this->metaDataId; ?>').html());
            //ChartsAmcharts.drawChartAmchart($("#" + $dialogName).find('.dashboard-filter-form-<?php echo $this->metaDataId; ?>').serialize(), '<?php echo $this->diagram['DIAGRAM_TYPE']; ?>', '<?php echo $this->metaDataId; ?>');
            $("#" + $dialogName).dialog({
                cache: false,
                resizable: true,
                bgiframe: true,
                autoOpen: false,
                title: '<?php echo $this->diagram['TITLE']; ?> хайлт',
                width: 500,
                height: 'auto',
                clickOutside: true,
                modal: true,
                close: function(){
                    $("#" + $dialogName).dialog('close');
                },
                buttons: [
                    {html: '<i class="fa fa-search"></i> <?php echo $this->lang->line('do_filter') ?>',
                        class: 'btn btn-sm blue-madison', click: function() {
                            if (isSubChart === 0) {
                                var dashCriteriaData = $("#" + $dialogName).find('.dashboard-filter-form-<?php echo $this->metaDataId; ?>').serialize();
                                ChartsAmcharts.drawChartAmchart(dashCriteriaData, '<?php echo $this->diagram['DIAGRAM_TYPE']; ?>', '<?php echo $this->metaDataId; ?>');
                                <?php if ($this->diagram['IS_VIEW_DATAGRID'] == '1') { ?>
                                    var firstMetaDataId = $('#dashboard-container-<?php echo $this->metaDataId; ?>').attr('processMetaDataId');
                                    var secondMetaDataId = $('.process_metaDataId_<?php echo $this->metaDataId; ?>').attr('processMetaDataId');
                                    var processMetaDataId = (firstMetaDataId == '0') ? secondMetaDataId : firstMetaDataId;
                                    var $filterArea = $("#" + $dialogName).find('.dashboard-filter-form-<?php echo $this->metaDataId; ?>').find('[data-path]:visible, input.lookup-code-autocomplete:visible');
                                    var dvDefaultCriteria = {};    
                                    if ($filterArea.length) {
                                        $filterArea.each(function() {
                                            var $this = $(this);
                                            if ($this.val() != '') {
                                                if ($this.hasClass('lookup-code-autocomplete')) {
                                                    var $parent = $this.closest('.input-group');
                                                    var $hidden = $parent.find('input[type="hidden"]');
                                                    var val = $hidden.map(function() { return this.value; }).get().join(',');

                                                    dvDefaultCriteria[$hidden.attr('data-path').toLowerCase()] = [val];

                                                } else if ($this.hasClass('booleanInit')) {

                                                    dvDefaultCriteria[$this.attr('data-path').toLowerCase()] = $this.is(':checked') ? '1' : '0';              
                                                } else {
                                                    dvDefaultCriteria[$this.attr('data-path').toLowerCase()] = $this.val();
                                                }
                                            }
                                        });
                                    }                                

                                    $.ajax({
                                        type: 'post',
                                        url: URL_APP + 'mdobject/dataview/' + processMetaDataId + '/1',
                                        data: {uriParams: JSON.stringify(dvDefaultCriteria)},
                                        dataType: 'html',
                                        beforeSend: function(){
                                            Core.blockUI({
                                                animate: true
                                            });
                                        },
                                        success: function(response){
                                            var $height=$('#dashboard-datagrid-view-<?php echo $this->metaDataId; ?>').height();
                                            $('#dashboard-datagrid-view-<?php echo $this->metaDataId; ?>').attr('style', 'width:100%; height:' + $height + 'px !important; overflow-y:auto; overflow-x:hidden');
                                            $('#dashboard-datagrid-view-<?php echo $this->metaDataId; ?>').empty().html(response);
                                            Core.unblockUI();
                                        }
                                    });
                                <?php } ?>                                
                            } else {
                                ChartsAmcharts.subChartInit(selectedMetaDataId_<?php echo $this->metaDataId; ?>,selectedChartEvent, $("#" + $dialogName).find('.dashboard-filter-form-<?php echo $this->metaDataId; ?>').serialize());
                            }
                            $("#" + $dialogName).dialog('close');
                        }
                    },
                    {html: '<?php echo $this->lang->line('clear_btn') ?>', class: 'btn btn-sm grey-cascade', click: function() {
                            if(isSubChart === 0) {
                                ChartsAmcharts.drawChartAmchart([], '<?php echo $this->diagram['DIAGRAM_TYPE']; ?>', '<?php echo $this->metaDataId; ?>');
                            } else {
                                ChartsAmcharts.subChartInit(selectedMetaDataId_<?php echo $this->metaDataId; ?>, selectedChartEvent, []);
                            }
                        }
                    }
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
            //Core.initAjax($("#" + $dialogName));
        }
        $("#" + $dialogName).dialog('open');
    });
    $("#graph-btn-<?php echo $this->metaDataId; ?>").on("click", function() {
        $('#dashboard-filter-div-<?php echo $this->metaDataId; ?>').show();
        $('#customLegendDiv-<?php echo $this->metaDataId; ?>').empty().show();
        $('#dashboard-<?php echo $this->metaDataId; ?>').empty().css('height', '<?php echo (($this->diagram['HEIGHT'] < 300) ? '300' : $this->diagram['HEIGHT']) . 'px'; ?>');
        //if (isSubChart === 0) {
            ChartsAmcharts.drawChartAmchart($('#dashboard-filter-div-<?php echo $this->metaDataId; ?>').find('.dashboard-filter-form-<?php echo $this->metaDataId; ?>').serialize(), '<?php echo $this->diagram['DIAGRAM_TYPE']; ?>', '<?php echo $this->metaDataId; ?>', '', '', '', '<?php echo Arr::get($getAddonSettings, 'criteriaPosition') ?>');
        //} else {
        //    ChartsAmcharts.subChartInit(selectedMetaDataId_<?php echo $this->metaDataId; ?>, selectedChartEvent, $('.dashboard-filter-form-<?php echo $this->metaDataId; ?>').serialize());
        //}

    });
    
    <?php if ($criteriaPosition === 'top') { ?>
        $("#dashboard-filter-div-<?php echo $this->metaDataId; ?>").on('click', '.booleanInit', function (e) {
            var $_ = $(this);
            criteriaSerialize_<?php echo $this->metaDataId; ?>($_);
        });
        $("#dashboard-filter-div-<?php echo $this->metaDataId; ?>").on('change', 'input[data-path]:not(.booleanInit), select[data-path]:not(.booleanInit)', function (e) {
            var $_ = $(this);
            criteriaSerialize_<?php echo $this->metaDataId; ?>($_);
        });    
    <?php } elseif ($criteriaPosition === 'topFilterButton') { ?>
        
        $("#dashboard-filter-div-<?php echo $this->metaDataId; ?>").on('click', '.chart-filter-btn', function (e) {
            var $_ = $(this);
            criteriaSerialize_<?php echo $this->metaDataId; ?>($_);
        });
        
    <?php } ?>
        
    $("#list-btn-<?php echo $this->metaDataId; ?>").on("click", function() {
        var firstMetaDataId = $('#dashboard-container-<?php echo $this->metaDataId; ?>').attr('processMetaDataId');
        var secondMetaDataId = $('.process_metaDataId_<?php echo $this->metaDataId; ?>').attr('processMetaDataId');
        var processMetaDataId = (firstMetaDataId == '0') ? secondMetaDataId : firstMetaDataId;
        var $filterArea = $("#dashboard-filter-div-<?php echo $this->metaDataId; ?>").find('[data-path]:visible, input.lookup-code-autocomplete:visible');
        var dvDefaultCriteria = {};    
        
        if ($filterArea.length) {
            
            $filterArea.each(function() {
                var $this = $(this);
                
                if ($this.val() != '') {
                    
                    if ($this.hasClass('lookup-code-autocomplete')) {
                        
                        var $parent = $this.closest('.input-group');
                        var $hidden = $parent.find('input[type="hidden"]');
                        var val = $hidden.map(function() { return this.value; }).get().join(',');
                            
                        dvDefaultCriteria[$hidden.attr('data-path').toLowerCase()] = [val];
                        
                    } else if ($this.hasClass('booleanInit')) {
                        
                        dvDefaultCriteria[$this.attr('data-path').toLowerCase()] = $this.is(':checked') ? '1' : '0';              
                    } else {
                        dvDefaultCriteria[$this.attr('data-path').toLowerCase()] = $this.val();
                    }
                }
            });
        }
        
        var $dashboard = $('#dashboard-<?php echo $this->metaDataId; ?>');
        //var $height = $dashboard.height();
        if ($dashboard.find('.main-dataview-container').length) {
            return;
        }
        
        $.ajax({
            type: 'post',
            url: 'mdobject/dataview/' + processMetaDataId,
            data: {
                uriParams: JSON.stringify(dvDefaultCriteria), 
                //dataGridDefaultHeight: $height - 75
            }, 
            dataType: 'html',
            beforeSend: function(){
                Core.blockUI({animate: true});
            },
            success: function(response){
                
                $dashboard.attr('style', 'width:100%; height:100% !important; overflow-y:auto; overflow-x:hidden');
                $dashboard.empty().append(response);
                
                $('#customLegendDiv-<?php echo $this->metaDataId; ?>').hide();
                
                <?php if (Arr::get($getAddonSettings, 'criteriaPosition') === 'top') { ?>
                    $('.mandatory-criteria-form-'+processMetaDataId).hide();
                    $('.object-height-row2-minus-'+processMetaDataId).hide();
                <?php } else { ?>
                    $('#dashboard-filter-div-<?php echo $this->metaDataId; ?>').hide();
                <?php } ?>

                Core.unblockUI();
            }
        });
    });
    $('.back-btn-dashboard-<?php echo $this->metaDataId; ?>').on("click", function() {
        isSubChart = 0;
        ChartsAmcharts.drawChartAmchart($('#dashboard-filter-div-<?php echo $this->metaDataId; ?>').find('.dashboard-filter-form-<?php echo $this->metaDataId; ?>').serialize(), '<?php echo $this->diagram['DIAGRAM_TYPE']; ?>', '<?php echo $this->metaDataId; ?>', function(){
            $("#graph-btn-<?php echo $this->metaDataId; ?>").trigger('click');
        });
    });
    <?php if ($this->diagram['IS_VIEW_DATAGRID'] == '1') { ?>
        var firstMetaDataId = $('#dashboard-container-<?php echo $this->metaDataId; ?>').attr('processMetaDataId');
        var secondMetaDataId = $('.process_metaDataId_<?php echo $this->metaDataId; ?>').attr('processMetaDataId');
        var processMetaDataId = (firstMetaDataId == '0') ? secondMetaDataId : firstMetaDataId;
        
        $.ajax({
            type: 'post',
            url: URL_APP + 'mdobject/dataview/' + processMetaDataId + '/1',
            dataType: 'html',
            beforeSend: function(){
                Core.blockUI({
                    animate: true
                });
            },
            success: function(response){
                var $height=$('#dashboard-datagrid-view-<?php echo $this->metaDataId; ?>').height();
                $('#dashboard-datagrid-view-<?php echo $this->metaDataId; ?>').attr('style', 'width:100%; height:' + $height + 'px !important; overflow-y:auto; overflow-x:hidden');
                $('#dashboard-datagrid-view-<?php echo $this->metaDataId; ?>').empty().html(response);
                Core.unblockUI();
            }
        });
    <?php } ?>    

    function criteriaSerialize_<?php echo $this->metaDataId; ?>(e) {
        var firstMetaDataId=$('#dashboard-container-<?php echo $this->metaDataId; ?>').attr('processMetaDataId');
        var secondMetaDataId=$('.process_metaDataId_<?php echo $this->metaDataId; ?>').attr('processMetaDataId');
        var processMetaDataId=(firstMetaDataId == '0') ? secondMetaDataId : firstMetaDataId;
        var $dv = $('#object-value-list-'+processMetaDataId);
        var $_this = e;

        Core.blockUI({animate: true});

        if (!$dv.length) {

            if (isSubChart === 0) {
                ChartsAmcharts.drawChartAmchart($('.dashboard-filter-form-<?php echo $this->metaDataId; ?>').serialize(), '<?php echo $this->diagram['DIAGRAM_TYPE']; ?>', '<?php echo $this->metaDataId; ?>', '', '', '', 'top');
            } else {
                ChartsAmcharts.subChartInit(selectedMetaDataId_<?php echo $this->metaDataId; ?>,selectedChartEvent, $('.dashboard-filter-form-<?php echo $this->metaDataId; ?>').serialize());
            }

        } else {

            var $filterArea = $(".dashboard-filter-form-<?php echo $this->metaDataId; ?>").find('[data-path]:visible, input.lookup-code-autocomplete');
            var dvDefaultCriteria = {};    

            if ($filterArea.length) {
                $filterArea.each(function() {

                    var $this = $(this), isMulti = false;

                    if ($this.hasClass('lookup-code-autocomplete')) {

                        var $parent = $this.closest('.input-group');
                        var $hidden = $parent.find('input[type="hidden"]');
                        var path = $hidden.attr('data-path');
                        var $path = $dv.find('[data-path="'+path+'"]');
                        var val = $hidden.map(function() { return this.value; }).get().join(',');

                        $path.val(val);     

                        isMulti = true;

                    } else {

                        var path = $this.attr('data-path');
                        var $path = $dv.find('[data-path="'+path+'"]');
                        var val = $this.val();

                        if ($path.length) {

                            if ($path.hasClass('dateInit')) {

                                $path.val(val);
                                $path.data({ date: val });
                                $path.datepicker('update');

                            } else if ($path.hasClass('select2')) {

                                $path.trigger('select2-opening', [true]);
                                $path.select2('val', val);

                            } else if ($path.hasClass('longInit') 
                                || $path.hasClass('numberInit') 
                                || $path.hasClass('decimalInit') 
                                || $path.hasClass('integerInit')) {

                                if (val == null) {
                                    $path.autoNumeric('set', '');
                                } else {
                                    $path.autoNumeric('set', val);
                                }

                            } else if ($path.hasClass('bigdecimalInit')) {

                                $path.next('input[type=hidden]').val(setNumberToFixed(val));
                                $path.autoNumeric('set', val);                        

                            } else if ($path.hasClass('datetimeInit')) {
                                if (val !== '' && val !== null) {
                                    $path.val(date('Y-m-d H:i:s', strtotime(val)));
                                } else {
                                    $path.val('');
                                }
                            } else if ($path.hasClass('booleanInit')) {   
                                val = $path.is(':checked') ? '1' : '0';
                                checkboxCheckerUpdate($path, val);
                            } else if ($path.is(':radio')) {
                                var $getPathElement = $path.closest('.radio-list');

                                if (typeof $getPathElement !== 'undefined' && val !== '' && val !== null) {
                                    $getPathElement.find("input[type='radio'][value='"+val+"']").prop('checked', true);
                                    $.uniform.update($getPathElement.find("input[type='radio']"));
                                }
                            } else {
                                $path.val(val);          
                            } 
                        }
                    }

                    if (val != '') {
                        if (isMulti) {
                            dvDefaultCriteria[path.toLowerCase()] = [val];
                        } else {
                            dvDefaultCriteria[path.toLowerCase()] = val;
                        }
                    }
                });
            } 

            var $dataGrid = window['objectdatagrid_'+processMetaDataId], 
                $op = $dataGrid.datagrid('options'), 
                queryParams = $op.queryParams;

            if ($("input[name='mandatoryNoSearch']", 'form.mandatory-criteria-form-'+processMetaDataId).is(':checked')) {
                var defaultCriteriaData = $("div#object-value-list-"+processMetaDataId+" form#default-criteria-form").serialize();
            } else {
                var defaultCriteriaData = $("div#object-value-list-"+processMetaDataId+" form#default-criteria-form, form.mandatory-criteria-form-"+processMetaDataId).serialize();
            }   

            queryParams.defaultCriteriaData = defaultCriteriaData;
            queryParams.uriParams = JSON.stringify(dvDefaultCriteria);

            if ($op.idField === null) {
                $dataGrid.datagrid('load', queryParams);
            } else {
                $dataGrid.treegrid('load', queryParams);
            }
            Core.unblockUI();          
        }        
    }
</script>