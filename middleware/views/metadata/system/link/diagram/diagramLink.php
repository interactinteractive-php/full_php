<style type="text/css">
    .amChartTr {
        display: none;
    }
    .process-list {
        height: 26px;
    }
</style>

<div class="panel panel-default bg-inverse">
    <table class="table sheetTable">
        <tbody>
            <tr class="dataview">
                <td class="left-padding isMultiple_processName"><?php echo $this->lang->line('META_00046'); ?></td>
                <td colspan="2">
                    <div class="meta-autocomplete-wrap" data-params="autoSearch=1&grouptype=dataview&metaTypeId=<?php echo Mdmetadata::$metaGroupMetaTypeId.'|'.Mdmetadata::$businessProcessMetaTypeId; ?>">
                        <div class="input-group double-between-input">
                            <input id="processId" name="processId" type="hidden">
                            <input id="_displayField" class="form-control form-control-sm md-code-autocomplete" placeholder="<?php echo $this->lang->line('META_00068'); ?>" type="text">
                            <span class="input-group-btn">
                                <button type="button" class="btn default btn-bordered form-control-sm mr0" onclick="commonMetaDataSelectableGrid('single', '', this);"><i class="fa fa-search"></i></button>
                            </span>
                            <span class="input-group-btn not-group-btn">
                                <div class="btn-group pf-meta-manage-dropdown">
                                    <button class="btn grey-cascade btn-bordered form-control-sm mr0 dropdown-toggle" type="button" data-toggle="dropdown"></button>
                                    <ul class="dropdown-menu dropdown-menu-right" style="min-width: 126px;" role="menu"></ul>
                                </div>
                            </span>  
                            <span class="input-group-btn flex-col-group-btn">
                                <input id="_nameField" class="form-control form-control-sm md-name-autocomplete" placeholder="<?php echo $this->lang->line('META_00099'); ?>" type="text">      
                            </span>     
                        </div>
                    </div> 
                </td>
            </tr>
            <tr class="isCardProcess_multiple">
                <td class="left-padding">Оролтын параметр 2:</td>
                <td colspan="2">
                    <div class="meta-autocomplete-wrap" data-params="autoSearch=1&grouptype=dataview&metaTypeId=<?php echo Mdmetadata::$metaGroupMetaTypeId.'|'.Mdmetadata::$businessProcessMetaTypeId; ?>">
                        <div class="input-group double-between-input">
                            <input id="processId2" name="processId2" type="hidden">
                            <input id="_displayField" class="form-control form-control-sm md-code-autocomplete" placeholder="<?php echo $this->lang->line('META_00068'); ?>" type="text">
                            <span class="input-group-btn">
                                <button type="button" class="btn default btn-bordered form-control-sm mr0" onclick="commonMetaDataSelectableGrid('single', '', this);"><i class="fa fa-search"></i></button>
                            </span>    
                            <span class="input-group-btn not-group-btn">
                                <div class="btn-group pf-meta-manage-dropdown">
                                    <button class="btn grey-cascade btn-bordered form-control-sm mr0 dropdown-toggle" type="button" data-toggle="dropdown"></button>
                                    <ul class="dropdown-menu dropdown-menu-right" style="min-width: 126px;" role="menu"></ul>
                                </div>
                            </span>  
                            <span class="input-group-btn flex-col-group-btn">
                                <input id="_nameField" class="form-control form-control-sm md-name-autocomplete" placeholder="<?php echo $this->lang->line('META_00099'); ?>" type="text">      
                            </span>     
                        </div>
                    </div> 
                </td>
            </tr>
            <tr class="isCardProcess_multiple">
                <td class="left-padding">Оролтын параметр 3:</td>
                <td colspan="2">
                    <div class="meta-autocomplete-wrap" data-params="autoSearch=1&grouptype=dataview&metaTypeId=<?php echo Mdmetadata::$metaGroupMetaTypeId.'|'.Mdmetadata::$businessProcessMetaTypeId; ?>">
                        <div class="input-group double-between-input">
                            <input id="processId3" name="processId3" type="hidden">
                            <input id="_displayField" class="form-control form-control-sm md-code-autocomplete" placeholder="<?php echo $this->lang->line('META_00068'); ?>" type="text">
                            <span class="input-group-btn">
                                <button type="button" class="btn default btn-bordered form-control-sm mr0" onclick="commonMetaDataSelectableGrid('single', '', this);"><i class="fa fa-search"></i></button>
                            </span>     
                            <span class="input-group-btn not-group-btn">
                                <div class="btn-group pf-meta-manage-dropdown">
                                    <button class="btn grey-cascade btn-bordered form-control-sm mr0 dropdown-toggle" type="button" data-toggle="dropdown"></button>
                                    <ul class="dropdown-menu dropdown-menu-right" style="min-width: 126px;" role="menu"></ul>
                                </div>
                            </span>  
                            <span class="input-group-btn flex-col-group-btn">
                                <input id="_nameField" class="form-control form-control-sm md-name-autocomplete" placeholder="<?php echo $this->lang->line('META_00099'); ?>" type="text">      
                            </span>     
                        </div>
                    </div> 
                </td>
            </tr>
            <tr class="isCardProcess_multiple">
                <td class="left-padding">Оролтын параметр 4:</td>
                <td colspan="2">
                    <div class="meta-autocomplete-wrap" data-params="autoSearch=1&grouptype=dataview&metaTypeId=<?php echo Mdmetadata::$metaGroupMetaTypeId.'|'.Mdmetadata::$businessProcessMetaTypeId; ?>">
                        <div class="input-group double-between-input">
                            <input id="processId4" name="processId4" type="hidden">
                            <input id="_displayField" class="form-control form-control-sm md-code-autocomplete" placeholder="<?php echo $this->lang->line('META_00068'); ?>" type="text">
                            <span class="input-group-btn">
                                <button type="button" class="btn default btn-bordered form-control-sm mr0" onclick="commonMetaDataSelectableGrid('single', '', this);"><i class="fa fa-search"></i></button>
                            </span> 
                            <span class="input-group-btn not-group-btn">
                                <div class="btn-group pf-meta-manage-dropdown">
                                    <button class="btn grey-cascade btn-bordered form-control-sm mr0 dropdown-toggle" type="button" data-toggle="dropdown"></button>
                                    <ul class="dropdown-menu dropdown-menu-right" style="min-width: 126px;" role="menu"></ul>
                                </div>
                            </span>  
                            <span class="input-group-btn flex-col-group-btn">
                                <input id="_nameField" class="form-control form-control-sm md-name-autocomplete" placeholder="<?php echo $this->lang->line('META_00099'); ?>" type="text">      
                            </span>     
                        </div>
                    </div> 
                </td>
            </tr>
            <tr id="">
                <td style="width: 170px" class="left-padding"><?php echo $this->lang->line('metadata_dashboard_template'); ?>:</td>
                <td colspan="2">
                    <?php
                    echo Form::select(
                        array(
                            'name' => 'dashboardType',
                            'id' => 'dashboardType',
                            'data' => Info::getDashboardType(),
                            'op_value' => 'CODE',
                            'op_text' => 'NAME',
                            'class' => 'form-control select2',
                            'value' => '',
                            'onchange' => 'changeDashboardType()'
                        )
                    );
                    ?>
                </td>
            </tr>
            <tr id="dashboard-type-tr">
                <td style="width: 170px" class="left-padding"><?php echo $this->lang->line('META_00145'); ?></td>
                <td>
                    <?php
                    echo Form::select(
                        array(
                            'name' => 'chartType',
                            'id' => 'chartType',
                            'data' => Info::getDiagramType(),
                            'op_value' => 'CODE',
                            'op_text' => 'NAME',
                            'class' => 'form-control select2',
                            'value' => '',
                            'onchange' => 'changeDiagramType(this)'
                        )
                    );
                    ?>
                </td>
                <td style="width: 15px; text-align: right">
                    <button type="button" class="btn btn-sm purple-plum" onclick="viewDiagramTheme(this);">...</button>
                </td>
            </tr>
            <tr id="isCardProcess">
                <td style="width: 170px" class="left-padding" for="isMultipleProcess"><?php echo $this->lang->line('metadata_dashboard_multiple_process'); ?>:</td>
                <td colspan="2">
                    <div class="checkbox-list">
                        <?php
                        echo Form::checkbox(
                            array(
                                'name' => 'isMultipleProcess',
                                'id' => 'isMultipleProcess',
                                'value' => '1'
                            )
                        );
                        ?>
                    </div>
                </td>
            </tr>
            <tr id="dashboard-theme-tr">
                <td style="width: 170px" class="left-padding">Theme :</td>
                <td colspan="2">
                    <?php
                    echo Form::select(
                        array(
                            'name'  => 'chartTheme',
                            'id'    => 'chartTheme',
                            'data'  => Info::dashboardColorTheme(),
                            'op_value'  => 'value',
                            'op_text'   => 'name',
                            'class'     => 'form-control select2',
                            'value'     => isset($this->diagram['DIAGRAM_THEME']) ? $this->diagram['DIAGRAM_THEME'] : '',
                        )
                    );
                    ?>
                </td>
            </tr><tr class="isusemeta">
                <td style="width: 170px" class="left-padding"><label for="isUseMeta"><?php echo $this->lang->line('metadata_dashboard_isusemeta'); ?> : </label></td>
                <td colspan="2" id="isusemeta">
                    <div class="checkbox-list">
                    <?php
                    echo Form::checkbox(
                        array(
                            'name' => 'isUseMeta',
                            'id' => 'isUseMeta',
                            'value' => '1',
                            'checked' => 'checked'
                        )
                    );
                    ?></div>
                </td>
            </tr>
            <tr class="isusecriteria">
                <td style="width: 170px" class="left-padding" for="isUseCriteria"><label for="isUseCriteria"><?php echo $this->lang->line('metadata_dashboard_isusecriteria'); ?> : </label></td>
                <td colspan="2">
                    <div class="checkbox-list">
                    <?php
                    echo Form::checkbox(
                        array(
                            'name' => 'isUseCriteria',
                            'id' => 'isUseCriteria',
                            'value' => '1',
                            'checked' => 'checked'
                        )
                    );
                    ?></div>
                </td>
            </tr>
            <tr class="isuseList">
                <td style="width: 170px" class="left-padding" for="isuseList"><label for="isuseList"><?php echo $this->lang->line('metadata_dashboard_isuseList'); ?> : </label></td>
                <td colspan="2">
                    <div class="checkbox-list">
                    <?php
                    echo Form::checkbox(
                        array(
                            'name' => 'isuseList',
                            'id' => 'isuseList',
                            'value' => '1',
                            'checked' => 'checked'
                        )
                    );
                    ?></div>
                </td>
            </tr>
            <tr class="isuseGraph">
                <td style="width: 170px" class="left-padding" for="isuseGraph"><label for="isuseGraph"><?php echo $this->lang->line('metadata_dashboard_isusegraph'); ?> : </label></td>
                <td colspan="2">
                    <div class="checkbox-list">
                    <?php
                    echo Form::checkbox(
                        array(
                            'name' => 'isuseGraph',
                            'id' => 'isuseGraph',
                            'value' => '1',
                            'checked' => 'checked'
                        )
                    );
                    ?></div>
                </td>
            </tr>
            <tr class="chartWidth">
                <td style="width: 170px" class="left-padding"><?php echo $this->lang->line('width'); ?>:</td>
                <td colspan="2">
                    <?php
                    echo Form::text(
                        array(
                            'name' => 'width',
                            'id' => 'width',
                            'class' => 'form-control',
                            'value' => '',
                            'placeholder' => 'Тоо оруулна уу'
                        )
                    );
                    ?>
                </td>
            </tr>
            <tr class="chartHeight">
                <td style="width: 170px" class="left-padding"><?php echo $this->lang->line('height'); ?>:</td>
                <td colspan="2">
                    <?php
                    echo Form::text(
                        array(
                            'name' => 'height',
                            'id' => 'height',
                            'class' => 'form-control',
                            'value' => '',
                            'placeholder' => 'Тоо оруулна уу'
                        )
                    );
                    ?>
                </td>
            </tr>
            <tr class="">
                <td style="width: 170px" class="left-padding"><?php echo $this->lang->line('metadata_dashboard_show_text'); ?>:</td>
                <td colspan="2">
                    <div class="checkbox-list">
                        <?php
                        echo Form::checkbox(
                            array(
                                'name' => 'isShowTitle',
                                'id' => 'isShowTitle',
                                'value' => '1'
                            )
                        );
                        ?>
                    </div>
                </td>
            </tr>            
            <tr id="chartTitleRow" class="">
                <td style="width: 170px" class="left-padding"><?php echo $this->lang->line('MET_330477'); ?>:</td>
                <td colspan="2">
                    <?php
                    echo Form::text(
                        array(
                            'name' => 'chartTitle',
                            'id' => 'chartTitle',
                            'class' => 'form-control',
                            'value' => '',
                            'placeholder' => 'Текст оруулна уу',
                            'maxlength' => 255
                        )
                    );
                    ?>
                </td>
            </tr>
            <tr class="chartExportBtn">
                <td style="width: 170px" class="left-padding"><?php echo $this->lang->line('metadata_isexport'); ?>:</td>
                <td colspan="2">
                    <div class="checkbox-list">
                        <?php
                        echo Form::checkbox(
                            array(
                                'name' => 'isShowExport',
                                'id' => 'isShowExport',
                                'value' => '1'
                            )
                        );
                        ?>
                    </div>
                </td>
            </tr>
            <tr class="amChartTr reversedValueAxis">
                <td style="width: 170px" class="left-padding isCardProcessTitleText"><?php echo $this->lang->line('metadata_dashboard_xaxis'); ?>:</td>
                <td colspan="2" id="xaxisTd"><div class="process-list"></div></td>
            </tr>
            <tr class="amChartTr reversedValueAxis">
                <td style="width: 170px" class="left-padding isCardProcessValueText"><?php echo $this->lang->line('metadata_dashboard_yaxis'); ?>:</td>
                <td id="yaxisTd" colspan="2"><div class="process-list"></div></td>
            </tr>

         
            <?php $className = ''; ?>
            <tr class="isCardProcess_multiple <?php echo $className ?>">
                <td style="width: 170px" class="left-padding"><?php echo $this->lang->line('metadata_dashboard_title2'); ?>:</td>
                <td colspan="2" id="xaxisTd2"><div class="process-list"></div></td>
            </tr>
            <tr class="isCardProcess_multiple <?php echo $className ?>">
                <td style="width: 170px" class="left-padding"><?php echo $this->lang->line('metadata_dashboard_value2'); ?>:</td>
                <td id="yaxisTd2" colspan="2"><div class="process-list"></div></td>
            </tr>
            <tr class="isCardProcess_multiple <?php echo $className ?>">
                <td style="width: 170px" class="left-padding"><?php echo $this->lang->line('metadata_dashboard_title3'); ?>:</td>
                <td colspan="2" id="xaxisTd3"><div class="process-list"></div></td>
            </tr>
            <tr class="isCardProcess_multiple <?php echo $className ?>">
                <td style="width: 170px" class="left-padding"><?php echo $this->lang->line('metadata_dashboard_value3'); ?>:</td>
                <td id="yaxisTd3" colspan="2"><div class="process-list"></div></td>
            </tr>
            <tr class="isCardProcess_multiple <?php echo $className ?>">
                <td style="width: 170px" class="left-padding"><?php echo $this->lang->line('metadata_dashboard_title4'); ?>:</td>
                <td colspan="2" id="xaxisTd4"><div class="process-list"></div></td>
            </tr>
            <tr class="isCardProcess_multiple <?php echo $className ?>">
                <td style="width: 170px" class="left-padding"><?php echo $this->lang->line('metadata_dashboard_value4'); ?>:</td>
                <td id="yaxisTd4" colspan="2"><div class="process-list"></div></td>
            </tr>
            <tr class="amChartTr reversedValueAxis twoValueAxis isCardHiddenTr">
                <td style="width: 170px" class="left-padding"><?php echo $this->lang->line('metadata_dashboard_xgroup'); ?>:</td>
                <td id="xaxisGroupTd" colspan="2">
                </td>
            </tr>
            <tr class="amChartTr twoValueAxis isCardHiddenTr">
                <td style="width: 170px" class="left-padding"><?php echo $this->lang->line('metadata_dashboard_ygroup'); ?>:</td>
                <td id="yaxisGroupTd" colspan="2">
                </td>
            </tr>
            <tr class="amChartTr isCardHiddenTr chartxLabelRotation">
                <td style="width: 170px" class="left-padding"><?php echo $this->lang->line('metadata_dashboard_xrotation'); ?>:</td>
                <td id="xaxisTd" colspan="2">
                    <?php
                    echo Form::text(
                            array(
                                'name' => 'xLabelRotation',
                                'id' => 'xLabelRotation',
                                'class' => 'form-control',
                                'value' => '0',
                                'placeholder' => '',
                                'maxlength' => 3
                            )
                    );
                    ?>
                </td>
            </tr>
            <tr class="defaultTr">
                <td style="width: 170px" class="left-padding"><?php echo $this->lang->line('metadata_dashboard_show_multiple'); ?>:</td>
                <td colspan="2">
                    <div class="checkbox-list">              
                        <?php
                            echo Form::checkbox(
                                    array(
                                        'name' => 'isMultiple',
                                        'id' => 'isMultiple',
                                        'value' => '1'
                                    )
                            );
                        ?>
                    </div>
                </td>
            </tr>
            <tr id="isLittleTr" class="defaultTr">
                <td style="width: 170px" class="left-padding"><?php echo $this->lang->line('metadata_dashboard_is_small'); ?>:</td>
                <td colspan="2">
                    <div class="checkbox-list">              
                        <?php
                            echo Form::checkbox(
                                array(
                                    'name' => 'isLittle',
                                    'id' => 'isLittle',
                                    'value' => '1'
                                )
                            );
                        ?>
                    </div>
                </td>
            </tr>
            <tr class="defaultTr">
                <td style="width: 170px" class="left-padding"><?php echo $this->lang->line('metadata_dashboard_show_text'); ?>:</td>
                <td colspan="2">
                    <div class="checkbox-list">
                        <?php
                            echo Form::checkbox(
                                    array(
                                        'name' => 'isDataLabel',
                                        'id' => 'isDataLabel',
                                        'value' => '1'
                                    )
                            );
                        ?>
                        (<i><?php echo $this->lang->line('metadata_dashboard_show_text1'); ?></i>)
                    </div>
                </td>
            </tr>
            <tr class="defaultTr">
                <td style="width: 170px" class="left-padding"><?php echo $this->lang->line('metadata_dashboard_xaxis_step'); ?>:</td>
                <td colspan="2">
                    <?php
                    echo Form::text(
                            array(
                                'name' => 'labelStep',
                                'id' => 'labelStep',
                                'class' => 'form-control',
                                'value' => '',
                                'placeholder' => 'Тоо оруулна уу'
                            )
                    );
                    ?>
                </td>
            </tr>
            <tr class="defaultTr">
                <td style="width: 170px" class="left-padding"><?php echo $this->lang->line('metadata_dashboard_xtext'); ?>:</td>
                <td colspan="2">
                    <div class="checkbox-list">
                        <?php
                            echo Form::checkbox(
                                    array(
                                        'name' => 'isXLabel',
                                        'id' => 'isXLabel',
                                        'value' => '1'
                                    )
                            );                        
                        ?>
                    </div>
                </td>
            </tr>
            <tr class="defaultTr">
                <td style="width: 170px" class="left-padding"><?php echo $this->lang->line('metadata_dashboard_ytext'); ?>:</td>
                <td colspan="2">
                    <div class="checkbox-list">
                        <?php
                            echo Form::checkbox(
                                array(
                                    'name' => 'isYLabel',
                                    'id' => 'isYLabel',
                                    'value' => '1'
                                )
                            );
                        ?>
                    </div>
                </td>
            </tr>
            <tr class="defaultTr">
                <td style="width: 170px" class="left-padding"><?php echo $this->lang->line('metadata_dashboard_show_desc'); ?>:</td>
                <td colspan="2">
                    <div class="checkbox-list">
                        <?php
                            echo Form::checkbox(
                                array(
                                    'name' => 'isShowLabel',
                                    'id' => 'isShowLabel',
                                    'value' => '1'
                                )
                            );
                        ?>
                    </div>
                </td>
            </tr>
            <tr class="defaultTr">
                <td style="width: 170px" class="left-padding"><?php echo $this->lang->line('metadata_dashboard_show_bg'); ?>:</td>
                <td colspan="2">
                    <div class="checkbox-list">
                        <?php
                            echo Form::checkbox(
                                array(
                                    'name' => 'isBackground',
                                    'id' => 'isBackground',
                                    'value' => '1'
                                )
                            );
                        ?>
                    </div>
                </td>
            </tr>
            <tr class="isShowDataviewChart">
                <td style="width: 170px" class="left-padding"><?php echo $this->lang->line('metadata_dashboard_show_dv'); ?>:</td>
                <td colspan="2">
                    <div class="checkbox-list">
                        <?php
                        echo Form::checkbox(
                            array(
                                'name' => 'isViewDataGrid',
                                'id' => 'isViewDataGrid',
                                'value' => '1'
                            )
                        );
                        ?>
                    </div>
                </td>
            </tr>             
            <tr id="dashboard-theme-position-tr">
                <td style="width: 170px" class="left-padding"><?php echo $this->lang->line('metadata_dashboard_desc_position'); ?>:</td>
                <td colspan="2">
                    <?php
                    echo Form::select(
                            array(
                                'name' => 'chartLegendPos',
                                'id' => 'chartLegendPos',
                                'data' => Info::dashboardLegendPosition(),
                                'op_value' => 'value',
                                'op_text' => 'name',
                                'class' => 'form-control select2'
                            )
                    );
                    ?>
                </td>
            </tr>     
            <tr id="dashboard-label-substr">
                <td style="width: 170px" class="left-padding"><?php echo $this->lang->line('metadata_dashboard_split_label'); ?>:</td>
                <td colspan="2">
                    <?php
                    echo Form::text(
                        array(
                            'name' => 'labelTextSubstr',
                            'id' => 'labelTextSubstr',
                            'class' => 'form-control',
                            'value' => '',
                            'placeholder' => 'Lebel Text Substr'
                        )
                    );
                    ?>
                </td>
            </tr>      
            <tr id="chart-color">
                <td style="width: 170px" class="left-padding"><?php echo $this->lang->line('metadata_dashboard_color'); ?>:</td>
                <td colspan="2">
                    <div class="input-group color chart-colorpicker-default" data-color="">
                        <input type="text" name="chartColor" id="chartColor" class="form-control" value="">
                        <span class="input-group-btn">
                            <button class="btn default colorpicker-input-addon px-1" type="button"><i style=""></i>&nbsp;</button>
                        </span>
                    </div>
                </td>
            </tr>             
            <tr id="chart-color2">
                <td style="width: 170px" class="left-padding"><?php echo $this->lang->line('metadata_dashboard_color2'); ?>:</td>
                <td colspan="2">
                    <div class="input-group color chart-colorpicker-default" data-color="">
                        <input type="text" name="chartColor2" id="chartColor2" class="form-control" value="">
                        <span class="input-group-btn">
                            <button class="btn default colorpicker-input-addon px-1" type="button"><i style=""></i>&nbsp;</button>
                        </span>
                    </div>
                </td>
            </tr>             
        </tbody>
    </table>
</div>
<script type="text/javascript">
  
    $(function () {
        $('#isShowTitle').click(function () {
            if ($('#isShowTitle').parent('span').hasClass('checked')) {
                $('#chartTitleRow').hide();
                $('#isShowTitle').attr('checked', false);
                $('#isShowTitle').val('0');
            } else {
                $('#chartTitleRow').show();
                $('#isShowTitle').attr('checked', true);
                $('#isShowTitle').val('1');
            }
        });

        $('#isShowLabel').click(function () {
            if ($('#isShowLabel').parent('span').hasClass('checked')) {
                $('#isShowLabel').attr('checked', false);
                $('#isShowLabel').val('0');
            } else {
                $('#isShowLabel').attr('checked', true);
                $('#isShowLabel').val('1');
            }
        });

        $('#isShowExport').click(function () {
            if ($('#isShowExport').parent('span').hasClass('checked')) {
                $('#isShowExport').attr('checked', false);
                $('#isShowExport').val('0');
            } else {
                $('#isShowExport').attr('checked', true);
                $('#isShowExport').val('1');
            }
        });

        $('#processId').on('change', function () {
            if ($(this).val() !== '') {
                $('#text').attr('readOnly', 'readOnly');
                changeDashboardType();
            } else {
                $('#text').removeAttr('readOnly');
            }
            
            getColumnsAjax();
        });
        
        $('#processId2').on('change', function () {
            
            if ($(this).val() == '') {
                $('#xaxisTd2').html('<div class="process-list"></div>');
                $('#yaxisTd2').html('<div class="process-list"></div>');
            }
            
            getColumnsAjax2();
        });
        
        $('#processId3').on('change', function () {
            
            if ($(this).val() == '') {
                $('#xaxisTd3').html('<div class="process-list"></div>');
                $('#yaxisTd3').html('<div class="process-list"></div>');
            }
            
            getColumnsAjax3();
        });
        
        $('#processId4').on('change', function () {
            
            if ($(this).val() == '') {
                $('#xaxisTd4').html('<div class="process-list"></div>');
                $('#yaxisTd4').html('<div class="process-list"></div>');
            }
            
            getColumnsAjax4();
        });
        
        $("#chartType").on("change", function(){
            getColumnsAjax();
        });        
        
        changeDashboardType();
        
        $('.chart-colorpicker-default').colorpicker({
            format: 'hex'
        });        
    });
    
    $('#isMultipleProcess').click(function() {
        $('.isCardProcess_multiple').hide();
        $('.isMultiple_processName').html('<?php echo $this->lang->line('META_00046'); ?>');
        $('.isCardProcessTitleText').html('X тэнхлэг:');
        $('.isCardProcessValueText').html('Y тэнхлэг:');
        
        if ($(this).is(':checked')) {
            $('.isMultiple_processName').html('Оролтын параметр 1:');
            $('.isCardProcessTitleText').html('Гарчиг 1:');
            $('.isCardProcessValueText').html('Утга 1:');
            $('.isCardProcess_multiple').show();
        }
    });

    function changeDashboardType() {
        var dashboardType = $('#dashboardType').val();
        $('#isCardProcess').hide();
        $('#dashboard-label-substr').hide();
        $('#chart-color').hide();
        $('#chart-color2').hide();
        
        $('.isCardProcessTitleText').html('X тэнхлэг:');
        $('.isCardProcessValueText').html('Y тэнхлэг:');
        
        switch (dashboardType) { 
            case 'card'  :  {
                $('.defaultTr').hide();
                $('.amChartTr').show();
                $('#dashboard-type-tr').show();
                $('.twoValueAxis').hide();
                $('.isCardHiddenTr').hide();
                $('#isCardProcess').show();
                $('.isCardProcessTitleText').html('Гарчиг 1:');
                $('.isCardProcessValueText').html('Утга 1:');
                getColumnsAjax();
                break;
            }
            case 'amchart'  : {
                $('.defaultTr').hide();
                $('.amChartTr').show();
                $('#dashboard-type-tr').show();
                $('#dashboard-label-substr').show();
                
                getColumnsAjax();
                break;
            }
            case 'orgchart' : {
                $('.defaultTr').hide();
                $('.amChartTr').hide();
                $('#dashboard-type-tr').hide();
                break;
            }
            case 'custom' : {
                $('#dashboard-theme-tr').hide();
                $('#dashboard-theme-position-tr').hide();
                $('#dashboard-type-tr').show();
                $('.isCardHiddenTr').hide();
                $('.chartWidth').hide();
                $('.defaultTr').hide();
                $('.chartHeight').hide();
                $('.chartExportBtn').hide();
                $('.isShowDataviewChart').hide();
                $('.chartValueAxisTitle').hide();
                $('.chartCategoryAxisTitle').hide();
                $('#chart-color').show();
                $('#chart-color2').show();
                $('.amChartTr').show();
                $('.chartxLabelRotation').hide();
                $('.twoValueAxis').hide();
                $('.isCardProcessTitleText').html('Утга 1:');
                $('.isCardProcessValueText').html('Утга 2:');
                $('.isCardProcessValueText3').html('Утга 3:');
                $('.isCardProcessValueText4').html('Утга 4:');
                getColumnsAjax();
                break;
            }            
            default : {
                $('.amChartTr').hide();
                $('.defaultTr').show();
                $('.isCardProcess_multiple').hide();
                $('#isCardProcess').hide();
            }
        }
        $.ajax({
            type: 'post',
            url: 'mddashboard/getDashboardType',
            dataType: 'json',
            data: {type: dashboardType},
            async: false,
            success: function(data) {
                var options=$("#chartType").empty().append($("<option />").val('').text('<?php echo $this->lang->line('choose') ?>'));
                $.each(data, function() {
                    options.append($("<option />").val(this.CODE).text(this.NAME));
                });
            }
        });
    }

    function changeDiagramType(element) {
        var dashboardType = $('#chartType').val();
        
        switch (dashboardType) { 
            case 'line' : {
                var html =   '<tr id="isMultipleTr">' 
                                + '<td style="width: 170px" class="left-padding">Олон график харагдах эсэх:</td>' 
                                + '<td><input type="checkbox" id="isMultiple" name="isMultiple" class="" value="1" /></td>' 
                              + '</tr>';
                if ($('#isMultipleTr').length === 0) {
                    $('#dashboard-type-tr').after(html);
                }
                break;
            }
            case 'columnOne' :
            case 'dualAxes' : {
                var html = '<tr id="isLittleTr">' 
                                + '<td style="width: 170px" class="left-padding">Жижиг эсэх:</td>' 
                                + '<td><input type="checkbox" id="isLittle" name="isLittle" class="" value="1"></td>'
                            + '</tr>';
                if ($('#isLittleTr').length === 0) {
                    $('#dashboard-type-tr').after(html);
                }
                break;
            }
            case 'am_stacked_bar_chart' : 
            case 'am_3d_stacked_column_chart_2' :
            case 'am_reversed' : {
                $('.amChartTr').hide();
                $('.reversedValueAxis').show();
                break;
            }
            case 'am_zoomable_value_axis' : 
            case 'am_trend_lines' : {
                $('.twoValueAxis').hide();
                break;
            }
            case 'clustered_bar_chart' : 
            case 'clustered_bar_chart_horizontal' : 
            case 'am_3d_stacked_column_chart' : {
                $('.twoValueAxis').hide();
                break;
            }
            case 'am_combined_bullet' : {
                $('.twoValueAxis').hide();
                break;
            }
            default : {
                if ($("#isMultipleTr").length > 0) {
                    $("#isMultipleTr").remove();
                }
                if ($("#isLittleTr").length > 0) {
                    $("#isLittleTr").remove();
                }
            }
        }
        Core.initAjax();
    }
    
    function viewDiagramTheme(elem) {
        var _row = $(elem).closest("tr");
        var _themeCode = _row.find("select[name='chartType']").val();
        
        if (_themeCode.length > 0) {

            var $dialogName = 'dialog-themeview';
            if (!$($dialogName).length) {
                $('<div id="' + $dialogName + '"></div>').appendTo('body');
            }
            
            $("#" + $dialogName).empty().html("<img src=\"middleware/views/dashboard/themes/" + _themeCode + ".jpg\" style=\"max-width:100%;\">");
            $("#" + $dialogName).dialog({
                appendTo: "body",
                cache: false,
                resizable: true,
                bgiframe: true,
                autoOpen: false,
                title: _themeCode,
                width: 600,
                minWidth: 600,
                height: 400,
                modal: false,
                buttons: [
                    {text: '<?php echo $this->lang->line('META_00033'); ?>', class: 'btn btn-sm blue-hoki', click: function () {
                        $("#" + $dialogName).empty().dialog('close');
                        $("#" + $dialogName).dialog('destroy').remove();
                    }}
                ]
            });
            $("#" + $dialogName).dialog('open');
        }
    }

    function selectAmchartTheme(theme, thisObject) {
        $('#theme').val(theme);
        $('.am-chart-theme-li-a-chosen').removeClass('am-chart-theme-li-a-chosen');
        $(thisObject).addClass('am-chart-theme-li-a-chosen');
    }
    
    function getColumnsAjax() {
        var dataViewId = $('#processId').val();
        var result = null;
        $.ajax({
            url: "mddashboard/getColumnsAjax",
            data: {dataViewId: dataViewId},
            type: "POST",
            dataType: 'json',
            success: function (response) {                
                result = response;
                setXaxis(response, '');
                setYaxis(response, '');
                setXaxisGroup(response, '');
                setYaxisGroup(response, '');
            },
            error: function (jqXHR, exception) {
                Core.unblockUI();
            }
        }).complete(function () {
            Core.unblockUI();
        });

        return result;
    }
    
    function getColumnsAjax2() {
        var dataViewId = $('#processId2').val();
        var result = null;
        
        $.ajax({
            url: "mddashboard/getColumnsAjax",
            data: {dataViewId: dataViewId},
            type: "POST",
            dataType: 'json',
            success: function (response) {                
                result = response;
                setXaxis2(response, '');
                setYaxis2(response, '');
            },
            error: function (jqXHR, exception) {
                Core.unblockUI();
            }
        }).complete(function () {
            Core.unblockUI();
        });

        return result;
    }
    
    function getColumnsAjax3() {
        var dataViewId = $('#processId3').val();
        var result = null;
        $.ajax({
            url: "mddashboard/getColumnsAjax",
            data: {dataViewId: dataViewId},
            type: "POST",
            dataType: 'json',
            success: function (response) {                
                result = response;
                setXaxis3(response, '');
                setYaxis3(response, '');
            },
            error: function (jqXHR, exception) {
                Core.unblockUI();
            }
        }).complete(function () {
            Core.unblockUI();
        });

        return result;
    }
    
    function getColumnsAjax4() {
        var dataViewId = $('#processId4').val();
        var result = null;
        $.ajax({
            url: "mddashboard/getColumnsAjax",
            data: {dataViewId: dataViewId},
            type: "POST",
            dataType: 'json',
            success: function (response) {                
                result = response;
                setXaxis4(response, '');
                setYaxis4(response, '');
            },
            error: function (jqXHR, exception) {
                Core.unblockUI();
            }
        }).complete(function () {
            Core.unblockUI();
        });

        return result;
    }
    
    function setXaxis(data, defaultValue) {
        var html = '<select id="xaxis" name="xaxis" class="form-control select2" onchange="changeDiagramType()" data-placeholder="- Сонгох -" tabindex="-1" title=""><option value="">- Сонгох -</option>';
        if (typeof data !== null) {
            $.each(data, function (key, value) {
                var isSelected = '';
                if(defaultValue !== 'undefined') {
                    if(value.FIELD_PATH === defaultValue) {
                        isSelected = 'selected="selected"';
                    }
                }
                html += '<option value="'+value.FIELD_PATH+'" '+isSelected+'>'+value.LABEL_NAME+'</option>';
            });
        }
        html += '</select>'; 
        $('#xaxisTd').html(html);
        Core.initAjax();        
    }
    
    function setYaxis(data, defaultValue) {
        var isMultiple = '';
                
        switch ($("#chartType").val()) { 
            case 'am_dual' :
            case 'am_bar_axis' :
            case 'durarion_onvalue_axis' :
            case 'durarion_onvalue_axis2' :
            case 'multiple_value_axis' :
            case 'am_stacked_bar_chart' :
            case 'am_3d_stacked_column_chart_2' :
            case 'clustered_bar_chart' :
            case 'clustered_bar_chart_horizontal' :
            case 'am_3d_stacked_column_chart' :
            case 'am_reversed' :
            case 'am_radar_chart' :
            case 'am_combined_bullet' : {
                isMultiple= 'multiple="multiple"';
                break;
            }
            default : break
        }
        
        var html = '<select id="yaxis" name="yaxis[]" class="form-control select2" '+ isMultiple +' onchange="changeDiagramType()" data-placeholder="- Сонгох -" tabindex="-1" title=""><option value="">- Сонгох -</option>';
        
        if (typeof data !== null) {
            $.each(data, function (key, value) {
                var isSelected = '';
                if (defaultValue !== 'undefined') {
                    if (value.FIELD_PATH === defaultValue) {
                        isSelected = 'selected="selected"';
                    }
                }
                html += '<option value="'+value.FIELD_PATH+'" '+isSelected+'>'+value.LABEL_NAME+'</option>';
            });
        }
        html += '</select>'; 
        $('#yaxisTd').html(html);
        
        Core.initAjax($('#yaxisTd'));        
    }
    
    function setXaxisGroup(data, defaultValue) {
        var html = '<select id="xaxisGroup" name="xaxisGroup" class="form-control select2" onchange="changeDiagramType()" data-placeholder="- Сонгох -" tabindex="-1" title=""><option value="">- Сонгох -</option>';
        if (typeof data !== null) {
            $.each(data, function (key, value) {
                var isSelected = '';
                if (defaultValue !== 'undefined') {
                    if (value.FIELD_PATH === defaultValue) {
                        isSelected = 'selected="selected"';
                    }
                }
                html += '<option value="'+value.FIELD_PATH+'" '+isSelected+'>'+value.LABEL_NAME+'</option>';
            });
        }
        html += '</select>'; 
        $('#xaxisGroupTd').html(html);
        
        Core.initAjax($('#xaxisGroupTd'));        
    }
    
    function setYaxisGroup(data, defaultValue){
        var html = '<select id="yaxisGroup" name="yaxisGroup" class="form-control select2" onchange="changeDiagramType()" data-placeholder="- Сонгох -" tabindex="-1" title=""><option value="">- Сонгох -</option>';
        if (typeof data !== null) {
            $.each(data, function (key, value) {
                var isSelected = '';
                if(defaultValue !== 'undefined') {
                    if(value.FIELD_PATH === defaultValue) {
                        isSelected = 'selected="selected"';
                    }
                }
                html += '<option value="'+value.FIELD_PATH+'" '+isSelected+'>'+value.LABEL_NAME+'</option>';
            });
        }
        html += '</select>'; 
        $('#yaxisGroupTd').html(html);
        
        Core.initAjax($('#yaxisGroupTd'));        
    }
    
    function setXaxis2(data, defaultValue) {
        var html = '<select id="xaxis" name="xaxis2" class="form-control select2" onchange="changeDiagramType()" data-placeholder="- Сонгох -" tabindex="-1" title=""><option value="">- Сонгох -</option>';
        if (typeof data !== null) {
            $.each(data, function (key, value) {
                var isSelected = '';
                if(defaultValue !== 'undefined') {
                    if(value.FIELD_PATH === defaultValue) {
                        isSelected = 'selected="selected"';
                    }
                }
                html += '<option value="'+value.FIELD_PATH+'" '+isSelected+'>'+value.LABEL_NAME+'</option>';
            });
        }
        html += '</select>'; 
        $('#xaxisTd2').html(html);
        
        Core.initAjax($('#xaxisTd2'));        
    }
    
    function setYaxis2(data, defaultValue) {
        var isMultiple = '';
        switch ($("#chartType").val()) { 
            case 'am_dual' :
            case 'am_bar_axis' :
            case 'durarion_onvalue_axis' :
            case 'durarion_onvalue_axis2' :
            case 'multiple_value_axis' :
            case 'am_stacked_bar_chart' :
            case 'am_3d_stacked_column_chart_2' :
            case 'am_reversed' :
            case 'am_combined_bullet' : {
                isMultiple= 'multiple="multiple"';
                break;
            }
            default : break
        }
        
        var html = '<select id="yaxis" name="yaxis2" class="form-control select2" '+ isMultiple +' onchange="changeDiagramType()" data-placeholder="- Сонгох -" tabindex="-1" title=""><option value="">- Сонгох -</option>';
        
        if (typeof data !== null) {
            $.each(data, function (key, value) {
                var isSelected = '';
                if(defaultValue !== 'undefined') {
                    if(value.FIELD_PATH === defaultValue) {
                        isSelected = 'selected="selected"';
                    }
                }
                html += '<option value="'+value.FIELD_PATH+'" '+isSelected+'>'+value.LABEL_NAME+'</option>';
            });
        }
        html += '</select>'; 
        $('#yaxisTd2').html(html);
        Core.initAjax();        
    }
    
    function setXaxis3(data, defaultValue) {
        var html = '<select id="xaxis" name="xaxis3" class="form-control select2" onchange="changeDiagramType()" data-placeholder="- Сонгох -" tabindex="-1" title=""><option value="">- Сонгох -</option>';
        if (typeof data !== null) {
            $.each(data, function (key, value) {
                var isSelected = '';
                if(defaultValue !== 'undefined') {
                    if(value.FIELD_PATH === defaultValue) {
                        isSelected = 'selected="selected"';
                    }
                }
                html += '<option value="'+value.FIELD_PATH+'" '+isSelected+'>'+value.LABEL_NAME+'</option>';
            });
        }
        html += '</select>'; 
        $('#xaxisTd3').html(html);
        
        Core.initAjax($('#xaxisTd3'));        
    }
    
    function setYaxis3(data, defaultValue) {
        var isMultiple = '';
        switch ($("#chartType").val()) { 
            case 'am_dual' :
            case 'am_bar_axis' :
            case 'durarion_onvalue_axis' :
            case 'durarion_onvalue_axis2' :
            case 'multiple_value_axis' :
            case 'am_stacked_bar_chart' :
            case 'am_3d_stacked_column_chart_2' :
            case 'am_reversed' :
            case 'am_combined_bullet' : {
                isMultiple= 'multiple="multiple"';
                break;
            }
            default : break
        }
        
        var html = '<select id="yaxis" name="yaxis3" class="form-control select2" '+ isMultiple +' onchange="changeDiagramType()" data-placeholder="- Сонгох -" tabindex="-1" title=""><option value="">- Сонгох -</option>';
        
        if (typeof data !== null) {
            $.each(data, function (key, value) {
                var isSelected = '';
                if (defaultValue !== 'undefined' && value.FIELD_PATH === defaultValue) {
                    isSelected = 'selected="selected"';
                }
                html += '<option value="'+value.FIELD_PATH+'" '+isSelected+'>'+value.LABEL_NAME+'</option>';
            });
        }
        html += '</select>'; 
        $('#yaxisTd3').html(html);
        
        Core.initAjax($('#yaxisTd3'));        
    }
    
    function setXaxis4(data, defaultValue) {
        var html = '<select id="xaxis" name="xaxis4" class="form-control select2" onchange="changeDiagramType()" data-placeholder="- Сонгох -" tabindex="-1" title=""><option value="">- Сонгох -</option>';
        if (typeof data !== null) {
            $.each(data, function (key, value) {
                var isSelected = '';
                if (defaultValue !== 'undefined' && value.FIELD_PATH === defaultValue) {
                    isSelected = 'selected="selected"';
                }
                html += '<option value="'+value.FIELD_PATH+'" '+isSelected+'>'+value.LABEL_NAME+'</option>';
            });
        }
        html += '</select>'; 
        $('#xaxisTd4').html(html);
        
        Core.initAjax($('#xaxisTd4'));        
    }
    
    function setYaxis4(data, defaultValue) {
        var isMultiple = '';
        switch ($("#chartType").val()) { 
            case 'am_dual' :
            case 'am_bar_axis' :
            case 'durarion_onvalue_axis' :
            case 'durarion_onvalue_axis2' :
            case 'multiple_value_axis' :
            case 'am_stacked_bar_chart' :
            case 'am_3d_stacked_column_chart_2' :
            case 'am_reversed' :
            case 'am_combined_bullet' : {
                isMultiple= 'multiple="multiple"';
                break;
            }
            default : break
        }
        
        var html = '<select id="yaxis" name="yaxis4" class="form-control select2" '+ isMultiple +' onchange="changeDiagramType()" data-placeholder="- Сонгох -" tabindex="-1" title=""><option value="">- Сонгох -</option>';
        
        if (typeof data !== null) {
            $.each(data, function (key, value) {
                var isSelected = '';
                if (defaultValue !== 'undefined' && value.FIELD_PATH === defaultValue) {
                    isSelected = 'selected="selected"';
                }
                html += '<option value="'+value.FIELD_PATH+'" '+isSelected+'>'+value.LABEL_NAME+'</option>';
            });
        }
        html += '</select>'; 
        $('#yaxisTd4').html(html);
        
        Core.initAjax($('#yaxisTd4'));        
    }
</script>