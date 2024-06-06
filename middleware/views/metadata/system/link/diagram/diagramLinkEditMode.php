<style type="text/css">
    .process-list {
        height: 26px;
    }
</style>
<?php
$className = ($this->diagram['IS_MULTIPLE_PROCESS'] == '1') ? '' : 'hidden'; 
?>
<div class="panel panel-default bg-inverse">
    <table class="table sheetTable">
        <tbody>
            <tr class="dataview">
                <td class="left-padding"><?php echo $this->lang->line('META_00046'); ?></td>
                <td colspan="2">
                    <div class="meta-autocomplete-wrap" data-params="autoSearch=1&grouptype=dataview&metaTypeId=<?php echo Mdmetadata::$metaGroupMetaTypeId.'|'.Mdmetadata::$businessProcessMetaTypeId; ?>">
                        <div class="input-group double-between-input">
                            <input id="processId" name="processId" type="hidden" value="<?php echo Arr::get($this->diagram, 'PROCESS_META_DATA_ID'); ?>">
                            <input id="_displayField" class="form-control form-control-sm md-code-autocomplete" placeholder="<?php echo $this->lang->line('META_00068'); ?>" type="text" value="<?php echo Arr::get($this->diagram, 'DATA_VIEW_CODE'); ?>">
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
                                <input id="_nameField" class="form-control form-control-sm md-name-autocomplete" placeholder="<?php echo $this->lang->line('META_00099'); ?>" type="text" value="<?php echo Arr::get($this->diagram, 'DATA_VIEW_NAME'); ?>">      
                            </span>     
                        </div>
                    </div>      
                </td>
            </tr>
            <tr class="dataview isCardProcess_multiple <?php echo $className ?>">
                <td class="left-padding">Оролтын параметр 2:</td>
                <td colspan="2">
                    <div class="meta-autocomplete-wrap" data-params="autoSearch=1&grouptype=dataview&metaTypeId=<?php echo Mdmetadata::$metaGroupMetaTypeId.'|'.Mdmetadata::$businessProcessMetaTypeId; ?>">
                        <div class="input-group double-between-input">
                            <input id="processId2" name="processId2" type="hidden" value="<?php echo Arr::get($this->diagram, 'PROCESS_META_DATA_ID2'); ?>">
                            <input id="_displayField" class="form-control form-control-sm md-code-autocomplete" placeholder="<?php echo $this->lang->line('META_00068'); ?>" type="text" value="<?php echo Arr::get($this->diagram, 'DATA_VIEW_CODE2'); ?>">
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
                                <input id="_nameField" class="form-control form-control-sm md-name-autocomplete" placeholder="<?php echo $this->lang->line('META_00099'); ?>" type="text" value="<?php echo Arr::get($this->diagram, 'DATA_VIEW_NAME2'); ?>">      
                            </span>     
                        </div>
                    </div>      
                </td>
            </tr>
            <tr class="dataview isCardProcess_multiple <?php echo $className ?>">
                <td class="left-padding">Оролтын параметр 3:</td>
                <td colspan="2">
                    <div class="meta-autocomplete-wrap" data-params="autoSearch=1&grouptype=dataview&metaTypeId=<?php echo Mdmetadata::$metaGroupMetaTypeId.'|'.Mdmetadata::$businessProcessMetaTypeId; ?>">
                        <div class="input-group double-between-input">
                            <input id="processId3" name="processId3" type="hidden" value="<?php echo Arr::get($this->diagram, 'PROCESS_META_DATA_ID3'); ?>">
                            <input id="_displayField" class="form-control form-control-sm md-code-autocomplete" placeholder="<?php echo $this->lang->line('META_00068'); ?>" type="text" value="<?php echo Arr::get($this->diagram, 'DATA_VIEW_CODE3'); ?>">
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
                                <input id="_nameField" class="form-control form-control-sm md-name-autocomplete" placeholder="<?php echo $this->lang->line('META_00099'); ?>" type="text" value="<?php echo Arr::get($this->diagram, 'DATA_VIEW_NAME3'); ?>">      
                            </span>     
                        </div>
                    </div>      
                </td>
            </tr>
            <tr class="dataview isCardProcess_multiple <?php echo $className ?>">
                <td class="left-padding">Оролтын параметр 4:</td>
                <td colspan="2">
                    <div class="meta-autocomplete-wrap" data-params="autoSearch=1&grouptype=dataview&metaTypeId=<?php echo Mdmetadata::$metaGroupMetaTypeId.'|'.Mdmetadata::$businessProcessMetaTypeId; ?>">
                        <div class="input-group double-between-input">
                            <input id="processId4" name="processId4" type="hidden" value="<?php echo Arr::get($this->diagram, 'PROCESS_META_DATA_ID4'); ?>">
                            <input id="_displayField" class="form-control form-control-sm md-code-autocomplete" placeholder="<?php echo $this->lang->line('META_00068'); ?>" type="text" value="<?php echo Arr::get($this->diagram, 'DATA_VIEW_CODE4'); ?>">
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
                                <input id="_nameField" class="form-control form-control-sm md-name-autocomplete" placeholder="<?php echo $this->lang->line('META_00099'); ?>" type="text" value="<?php echo Arr::get($this->diagram, 'DATA_VIEW_NAME4'); ?>">      
                            </span>     
                        </div>
                    </div>      
                </td>
            </tr>
            <tr>
                <td style="width: 170px" class="left-padding"><?php echo $this->lang->line('metadata_dashboard_template'); ?></td>
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
                            'value' => Arr::get($this->diagram, 'DASHBOARD_TYPE'),
                            'onchange' => 'changeDashboardType()'
                        )
                    );
                    ?>
                </td>
            </tr>
            <tr id="dashboard-type-tr">
                <td style="width: 170px" class="left-padding"><?php echo $this->lang->line('META_00145'); ?></td>
                <td colspan="2">
                    <?php
                        echo Form::select(
                            array(
                                'name' => 'chartType',
                                'id' => 'chartType',
                                'data' => Info::getDiagramType(Arr::get($this->diagram, 'DASHBOARD_TYPE')),
                                'op_value' => 'CODE',
                                'op_text' => 'NAME',
                                'class' => 'form-control select2',
                                'style' => 'width:370px',
                                'value' => Arr::get($this->diagram, 'DIAGRAM_TYPE'),
                                'onchange' => 'changeDiagramType()'
                            )
                        );
                    ?>                   
                    <a href="javascript:;" class="btn btn-sm purple-plum" onclick="metaThumbChoose(this,'<?php echo Arr::get($this->diagram, 'DASHBOARD_TYPE');?>',' <?php echo Arr::get($this->diagram, 'DIAGRAM_TYPE');?>')">...</a>
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
                                    'onclick' => 'checkBoxMultiProcess(this)',
                                    'value' => '1'
                                )
                            );
                        ?>
                    </div>
                </td>
            </tr>
            <tr id="dashboard-theme-tr">
                <td style="width: 170px" class="left-padding">THEME:</td>
                <td colspan="2">
                    <?php
                    echo Form::select(
                        array(
                            'name' => 'chartTheme',
                            'id' => 'chartTheme',
                            'data' => Info::dashboardColorTheme(),
                            'op_value' => 'value',
                            'op_text' => 'name',
                            'class' => 'form-control select2',
                            'value' => isset($this->diagram['DIAGRAM_THEME']) ? $this->diagram['DIAGRAM_THEME'] : '',
                        )
                    );
                    ?>
                </td>
            </tr>
            <tr class="isusemeta">
                <td colspan="3">
                <table style="width: 100%;"><tbody><tr>
                <td style="width: 170px" class="left-padding"><label for="isUseMeta">Is use meta</label></td>
                <td id="isusemeta">
                    <div class="checkbox-list">
                    <?php
                    echo Form::checkbox(
                        array(
                            'name' => 'isUseMeta',
                            'id' => 'isUseMeta',
                            'value' => '1',
                            'saved_val' => $this->diagram['IS_USE_META']
                        )
                    );
                    ?></div>
                </td>
                <td style="width: 170px" class="left-padding"><label for="isUseLegend">Is show legend</label></td>
                <td id="isUseLegend">
                    <div class="checkbox-list">
                    <?php
                    echo Form::checkbox(
                        array(
                            'name' => 'isUseLegend',
                            'id' => 'isUseLegend',
                            'value' => '1',
                            'saved_val' => $this->diagram['IS_USE_LEGEND']
                        )
                    );
                    ?></div>
                </td>
                </tr></tbody></table>
                </td>                
            </tr>
            <tr class="isusecriteria">
                <td colspan="3">
                <table style="width: 100%;"><tbody><tr>
                <td style="width: 170px" class="left-padding" for="isUseCriteria"><label for="isUseCriteria">Is use criteria</label></td>
                <td>
                    <div class="checkbox-list">
                    <?php
                    echo Form::checkbox(
                        array(
                            'name' => 'isUseCriteria',
                            'id' => 'isUseCriteria',
                            'value' => '1',
                            'saved_val' => $this->diagram['IS_USE_CRITERIA']
                        )
                    );
                    ?></div>
                </td>
                <td style="width: 170px" class="left-padding" for="isuseList"><label for="isuseList">Is use list</label></td>
                <td>
                    <div class="checkbox-list">
                    <?php
                    echo Form::checkbox(
                        array(
                            'name' => 'isuseList',
                            'id' => 'isuseList',
                            'value' => '1',
                            'saved_val' => $this->diagram['IS_USE_LIST']
                        )
                    );
                    ?></div>
                </td>
                </tr></tbody></table>
                </td>                
            </tr>
            <tr class="isuseGraph">
                <td style="width: 170px" class="left-padding" for="isuseGraph"><label for="isuseGraph">Is use graph</label></td>
                <td colspan="2">
                    <div class="checkbox-list">
                    <?php
                    echo Form::checkbox(
                        array(
                            'name' => 'isuseGraph',
                            'id' => 'isuseGraph',
                            'value' => '1',
                            'saved_val' => $this->diagram['IS_USE_GRAPH']
                        )
                    );
                    ?></div>
                </td>
            </tr>
            <tr class="chartWidth">
                <td colspan="3">
                <table style="width: 100%;"><tbody><tr>
                <td style="width: 170px" class="left-padding" for="width"><label for="isUseCriteria">Width</label></td>
                <td>
                    <?php
                    echo Form::text(
                        array(
                            'name' => 'width',
                            'id' => 'width',
                            'class' => 'form-control',
                            'value' => isset($this->diagram['WIDTH']) ? $this->diagram['WIDTH'] : '',
                            'placeholder' => 'Тоо оруулна уу'
                        )
                    );
                    ?>
                </td>
                <td style="width: 170px" class="left-padding" for="height"><label for="isuseList">Height</label></td>
                <td>
                    <?php
                    echo Form::text(
                        array(
                            'name' => 'height',
                            'id' => 'height',
                            'class' => 'form-control',
                            'value' => isset($this->diagram['HEIGHT']) ? $this->diagram['HEIGHT'] : '',
                            'placeholder' => 'Тоо оруулна уу'
                        )
                    );
                    ?>
                </td>
                </tr></tbody></table>
                </td>                
            </tr>
            <tr>
                <td style="width: 170px" class="left-padding"><?php echo $this->lang->line('metadata_dashboard_show_text'); ?>:</td>
                <td colspan="2">
                    <div class="checkbox-list">
                        <?php
                        if (isset($this->diagram['IS_SHOW_TITLE'])) {
                            if ($this->diagram['IS_SHOW_TITLE'] == 1) {
                                echo Form::checkbox(
                                    array(
                                        'name' => 'isShowTitle',
                                        'id' => 'isShowTitle',
                                        'value' => '1',
                                        'checked' => 'checked'
                                    )
                                );
                            } else {
                                echo Form::checkbox(
                                    array(
                                        'name' => 'isShowTitle',
                                        'id' => 'isShowTitle',
                                        'value' => '1'
                                    )
                                );
                            }
                        } else {
                            echo Form::checkbox(
                                array(
                                    'name' => 'isShowTitle',
                                    'id' => 'isShowTitle',
                                    'value' => '1'
                                )
                            );
                        }
                        ?>
                    </div>
                </td>
            </tr>            
            <tr id="chartTitleRow">
                <td style="width: 170px" class="left-padding"><?php echo $this->lang->line('MET_330477'); ?>:</td>
                <td colspan="2">
                    <?php
                    $titleAttr = array(
                        'name' => 'chartTitle',
                        'id' => 'chartTitle',
                        'class' => 'form-control globeCodeInput',
                        'value' => isset($this->diagram['TITLE']) ? $this->diagram['TITLE'] : '',
                        'placeholder' => 'Текст оруулна уу',
                        'maxlength' => 255
                    );
                    if ($titleAttr['value']) {
                        $titleAttr['title'] = Lang::lineEmpty($titleAttr['value']);
                    }
                    echo Form::text($titleAttr);
                    ?>
                </td>
            </tr>
            <tr class="chartExportBtn">
                <td style="width: 170px" class="left-padding"><?php echo $this->lang->line('metadata_isexport'); ?>:</td>
                <td colspan="2">
                    <div class="checkbox-list">
                        <?php
                        if (isset($this->diagram['IS_SHOW_EXPORT'])) {
                            if ($this->diagram['IS_SHOW_EXPORT'] == 1) {
                                echo Form::checkbox(
                                    array(
                                        'name' => 'isShowExport',
                                        'id' => 'isShowExport',
                                        'value' => '1',
                                        'checked' => 'checked'
                                    )
                                );
                            } else {
                                echo Form::checkbox(
                                    array(
                                        'name' => 'isShowExport',
                                        'id' => 'isShowExport',
                                        'value' => '1'
                                    )
                                );
                            }
                        } else {
                            echo Form::checkbox(
                                array(
                                    'name' => 'isShowExport',
                                    'id' => 'isShowExport',
                                    'value' => '1'
                                )
                            );
                        }
                        ?>
                    </div>
                </td>
            </tr>
            <tr class="amChartTr reversedValueAxis">
                <td style="width: 170px" class="left-padding isCardProcessTitleText"><?php echo $this->lang->line('metadata_dashboard_xaxis'); ?>:</td>
                <td colspan="2" id="xaxisTd"></td>
            </tr>
            <tr class="amChartTr reversedValueAxis">
                <td style="width: 170px" class="left-padding isCardProcessValueText"><?php echo $this->lang->line('metadata_dashboard_yaxis'); ?>:</td>
                <td colspan="2" id="yaxisTd"></td>
            </tr>
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
            <tr class="amChartTr reversedValueAxis twoValueAxis">
                <td style="width: 170px" class="left-padding"><?php echo $this->lang->line('metadata_dashboard_xgroup'); ?>:</td>
                <td colspan="2" id="xaxisGroupTd"></td>
            </tr>
            <tr class="amChartTr twoValueAxis">
                <td style="width: 170px" class="left-padding"><?php echo $this->lang->line('metadata_dashboard_ygroup'); ?>:</td>
                <td colspan="2"id="yaxisGroupTd"></td>
            </tr>
            <tr class="chartxLabelRotation">
                <td style="width: 170px" class="left-padding"><?php echo $this->lang->line('metadata_dashboard_xrotation'); ?>:</td>
                <td colspan="2" id="xaxisTd">
                    <?php
                    echo Form::text(
                        array(
                            'name' => 'xLabelRotation',
                            'id' => 'xLabelRotation',
                            'class' => 'form-control',
                            'value' => isset($this->diagram['X_LABEL_ROTATION']) ? $this->diagram['X_LABEL_ROTATION'] : '0',
                            'placeholder' => '',
                            'maxlength' => 3
                        )
                    );
                    ?>
                </td>
            </tr>
            <?php 
            if ($this->diagram['DIAGRAM_TYPE'] == 'line') { /* Дээр Dashboard уудын жагсаалт байгаа тэндээс line - г шалгаж байна. */ ?>
                <tr class="defaultTr">
                    <td style="width: 170px" class="left-padding"><?php echo $this->lang->line('metadata_dashboard_show_multiple'); ?>:</td>
                    <td colspan="2">
                        <div class="checkbox-list">              
                            <?php
                            if ($this->diagram['IS_MULTIPLE'] == 1) {
                                echo Form::checkbox(
                                    array(
                                        'name' => 'isMultiple',
                                        'id' => 'isMultiple',
                                        'value' => '1',
                                        'checked' => 'checked'
                                    )
                                );
                            } else {
                                echo Form::checkbox(
                                    array(
                                        'name' => 'isMultiple',
                                        'id' => 'isMultiple',
                                        'value' => '1'
                                    )
                                );
                            }
                            ?>
                        </div>
                    </td>
                </tr>
            <?php } 
            if ($this->diagram['DIAGRAM_TYPE'] == 'columnOne') { /* Дээр Dashboard уудын жагсаалт байгаа тэндээс line - г шалгаж байна. */ ?>
                <tr id="isLittleTr" class="defaultTr">
                    <td style="width: 170px" class="left-padding"><?php echo $this->lang->line('metadata_dashboard_is_small'); ?>:</td>
                    <td colspan="2" >
                        <div class="checkbox-list">              
                            <?php
                            if (isset($this->diagram['IS_LITTLE'])) {
                                if ($this->diagram['IS_LITTLE'] == 1) {
                                    echo Form::checkbox(
                                        array(
                                            'name' => 'isLittle',
                                            'id' => 'isLittle',
                                            'value' => '1',
                                            'checked' => 'checked'
                                        )
                                    );
                                } else {
                                    echo Form::checkbox(
                                        array(
                                            'name' => 'isLittle',
                                            'id' => 'isLittle',
                                            'value' => '1'
                                        )
                                    );
                                }
                            } else {
                                echo Form::checkbox(
                                    array(
                                        'name' => 'isLittle',
                                        'id' => 'isLittle',
                                        'value' => '1'
                                    )
                                );
                            }
                            ?>
                        </div>
                    </td>
                </tr>
            <?php } 
            ?>
            <tr class="defaultTr">
                <td style="width: 170px" class="left-padding"><?php echo $this->lang->line('metadata_dashboard_show_text'); ?>:</td>
                <td colspan="2">
                    <div class="checkbox-list">
                        <?php
                        if (isset($this->diagram['IS_DATA_LABEL'])) {
                            if ($this->diagram['IS_DATA_LABEL'] == 1) {
                                echo Form::checkbox(
                                    array(
                                        'name' => 'isDataLabel',
                                        'id' => 'isDataLabel',
                                        'value' => '1',
                                        'checked' => 'checked'
                                    )
                                );
                            } else {
                                echo Form::checkbox(
                                    array(
                                        'name' => 'isDataLabel',
                                        'id' => 'isDataLabel',
                                        'value' => '1'
                                    )
                                );
                            }
                        } else {
                            echo Form::checkbox(
                                array(
                                    'name' => 'isDataLabel',
                                    'id' => 'isDataLabel',
                                    'value' => '1'
                                )
                            );
                        }
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
                            'value' => isset($this->diagram['LABEL_STEP']) ? $this->diagram['LABEL_STEP'] : '',
                            'placeholder' => $this->lang->line('metadata_number')
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
                        if (isset($this->diagram['IS_X_LABEL'])) {
                            if ($this->diagram['IS_X_LABEL'] == 1) {
                                echo Form::checkbox(
                                    array(
                                        'name' => 'isXLabel',
                                        'id' => 'isXLabel',
                                        'value' => '1',
                                        'checked' => 'checked'
                                    )
                                );
                            } else {
                                echo Form::checkbox(
                                    array(
                                        'name' => 'isXLabel',
                                        'id' => 'isXLabel',
                                        'value' => '1'
                                    )
                                );
                            }
                        } else {
                            echo Form::checkbox(
                                array(
                                    'name' => 'isXLabel',
                                    'id' => 'isXLabel',
                                    'value' => '1'
                                )
                            );
                        }
                        ?>
                    </div>
                </td>
            </tr>
            <tr class="defaultTr">
                <td style="width: 170px" class="left-padding"><?php echo $this->lang->line('metadata_dashboard_ytext'); ?>:</td>
                <td colspan="2">
                    <div class="checkbox-list">
                        <?php
                        if (isset($this->diagram['IS_Y_LABEL'])) {
                            if ($this->diagram['IS_Y_LABEL'] == 1) {
                                echo Form::checkbox(
                                    array(
                                        'name' => 'isYLabel',
                                        'id' => 'isYLabel',
                                        'value' => '1',
                                        'checked' => 'checked'
                                    )
                                );
                            } else {
                                echo Form::checkbox(
                                    array(
                                        'name' => 'isYLabel',
                                        'id' => 'isYLabel',
                                        'value' => '1'
                                    )
                                );
                            }
                        } else {
                            echo Form::checkbox(
                                array(
                                    'name' => 'isYLabel',
                                    'id' => 'isYLabel',
                                    'value' => '1'
                                )
                            );
                        }
                        ?>
                    </div>
                </td>
            </tr>
            <tr class="defaultTr amChartTr">
                <td style="width: 170px" class="left-padding"><?php echo $this->lang->line('metadata_dashboard_show_desc'); ?>:</td>
                <td colspan="2">
                    <div class="checkbox-list">
                        <?php
                        if (isset($this->diagram['IS_SHOW_LABEL'])) {
                            if ($this->diagram['IS_SHOW_LABEL'] == 1) {
                                echo Form::checkbox(
                                    array(
                                        'name' => 'isShowLabel',
                                        'id' => 'isShowLabel',
                                        'value' => '1',
                                        'checked' => 'checked'
                                    )
                                );
                            } else {
                                echo Form::checkbox(
                                    array(
                                        'name' => 'isShowLabel',
                                        'id' => 'isShowLabel',
                                        'value' => '1'
                                    )
                                );
                            }
                        } else {
                            echo Form::checkbox(
                                array(
                                    'name' => 'isShowLabel',
                                    'id' => 'isShowLabel',
                                    'value' => '1'
                                )
                            );
                        }
                        ?>
                    </div>
                </td>
            </tr>
            <tr class="defaultTr">
                <td style="width: 170px" class="left-padding"><?php echo $this->lang->line('metadata_dashboard_show_bg'); ?>:</td>
                <td colspan="2">
                    <div class="checkbox-list">
                        <?php
                        if (isset($this->diagram['IS_BACKGROUND'])) {
                            if ($this->diagram['IS_BACKGROUND'] == 1) {
                                echo Form::checkbox(
                                    array(
                                        'name' => 'isBackground',
                                        'id' => 'isBackground',
                                        'value' => '1',
                                        'checked' => 'checked'
                                    )
                                );
                            } else {
                                echo Form::checkbox(
                                    array(
                                        'name' => 'isBackground',
                                        'id' => 'isBackground',
                                        'value' => '1'
                                    )
                                );
                            }
                        } else {
                            echo Form::checkbox(
                                array(
                                    'name' => 'isBackground',
                                    'id' => 'isBackground',
                                    'value' => '1'
                                )
                            );
                        }
                        ?>
                    </div>
                </td>
            </tr>
            <tr class="isShowDataviewChart">
                <td style="width: 170px" class="left-padding"><?php echo $this->lang->line('metadata_dashboard_show_dv'); ?>:</td>
                <td colspan="2">
                    <div class="checkbox-list">
                        <?php
                        if (isset($this->diagram['IS_VIEW_DATAGRID'])) {
                            if ($this->diagram['IS_VIEW_DATAGRID'] == 1) {
                                echo Form::checkbox(
                                    array(
                                        'name' => 'isViewDataGrid',
                                        'id' => 'isViewDataGrid',
                                        'value' => '1',
                                        'checked' => 'checked'
                                    )
                                );
                            } else {
                                echo Form::checkbox(
                                    array(
                                        'name' => 'isViewDataGrid',
                                        'id' => 'isViewDataGrid',
                                        'value' => '1'
                                    )
                                );
                            }
                        } else {
                            echo Form::checkbox(
                                array(
                                    'name' => 'isViewDataGrid',
                                    'id' => 'isViewDataGrid',
                                    'value' => '1'
                                )
                            );
                        }
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
                            'class' => 'form-control select2',
                            'value' => Arr::get($this->diagram, 'REAL_LEGEND_POSITION')
                        )
                    );
                    ?>
                </td>
            </tr>    
            <tr class="chartValueAxisTitle">
                <td style="width: 170px" class="left-padding"><?php echo $this->lang->line('metadata_dashboard_xtitle'); ?>:</td>
                <td colspan="2">
                    <?php
                    $valueAxisTitleAttr = array(
                        'name' => 'valueAxisTitle',
                        'id' => 'valueAxisTitle',
                        'class' => 'form-control globeCodeInput',
                        'value' => Arr::get($this->diagram, 'VALUE_AXIS_TITLE'),
                        'placeholder' => 'X тэнхлэгийн гарчиг'
                    );
                    if ($valueAxisTitleAttr['value']) {
                        $valueAxisTitleAttr['title'] = Lang::lineEmpty($valueAxisTitleAttr['value']);
                    }
                    echo Form::text($valueAxisTitleAttr);
                    ?>
                </td>
            </tr>
            <tr class="chartCategoryAxisTitle">
                <td style="width: 170px" class="left-padding"><?php echo $this->lang->line('metadata_dashboard_ytitle'); ?>:</td>
                <td colspan="2">
                    <?php
                    $categoryAxisTitleAttr = array(
                        'name' => 'categoryAxisTitle',
                        'id' => 'categoryAxisTitle',
                        'class' => 'form-control globeCodeInput',
                        'value' => Arr::get($this->diagram, 'CATEGORY_AXIS_TITLE'),
                        'placeholder' => 'Y тэнхлэгийн гарчиг'
                    );
                    if ($categoryAxisTitleAttr['value']) {
                        $categoryAxisTitleAttr['title'] = Lang::lineEmpty($categoryAxisTitleAttr['value']);
                    }
                    echo Form::text($categoryAxisTitleAttr);
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
                            'value' => (Arr::get($this->diagram, 'LABEL_TEXT_SUBSTR') == '10000' ? '' : Arr::get($this->diagram, 'LABEL_TEXT_SUBSTR')),
                            'placeholder' => 'Lebel Text Substr'
                        )
                    );
                    ?>
                </td>
            </tr>            
            <tr id="chart-color">
                <td style="width: 170px" class="left-padding"><?php echo $this->lang->line('metadata_dashboard_color'); ?>:</td>
                <td colspan="2">
                    <div class="input-group color chart-colorpicker-default" data-color="<?php echo Arr::get($this->diagram, 'COLOR'); ?>">
                        <input type="text" name="chartColor" id="chartColor" class="form-control" value="<?php echo Arr::get($this->diagram, 'COLOR'); ?>">
                        <span class="input-group-btn">
                            <button class="btn default colorpicker-input-addon px-1" type="button"><i style="background-color: <?php echo Arr::get($this->diagram, 'COLOR'); ?>;"></i>&nbsp;</button>
                        </span>
                    </div>
                </td>
            </tr>            
            <tr id="chart-color2">
                <td style="width: 170px" class="left-padding"><?php echo $this->lang->line('metadata_dashboard_color2'); ?>:</td>
                <td colspan="2">
                    <div class="input-group color chart-colorpicker-default" data-color="<?php echo Arr::get($this->diagram, 'COLOR2'); ?>">
                        <input type="text" name="chartColor2" id="chartColor2" class="form-control" value="<?php echo Arr::get($this->diagram, 'COLOR2'); ?>">
                        <span class="input-group-btn">
                            <button class="btn default colorpicker-input-addon px-1" type="button"><i style="background-color: <?php echo Arr::get($this->diagram, 'COLOR2'); ?>;"></i>&nbsp;</button>
                        </span>
                    </div>
                </td>
            </tr>           
            <tr>
                <td style="width: 170px" class="left-padding"><label for="isInlineLegend">Is inline legend : </label></td>
                <td colspan="2" id="isInlineLegend">
                    <div class="checkbox-list">
                    <?php
                    echo Form::checkbox(
                        array(
                            'name' => 'isInlineLegend',
                            'id' => 'isInlineLegend',
                            'value' => '1',
                            'saved_val' => $this->diagram['IS_INLINE_LEGEND']
                        )
                    );
                    ?></div>
                </td>
            </tr>            
            <tr>
                <td style="width: 170px" class="left-padding">Label Text Format:</td>
                <td colspan="2">
                    <?php
                    echo Form::text(
                        array(
                            'name' => 'legendFormat',
                            'id' => 'legendFormat',
                            'class' => 'form-control',
                            'value' => Arr::get($this->diagram, 'LEGEND_FORMAT'),
                            'placeholder' => '[[title]] [[value]] ([[percents]]%)'
                        )
                    );
                    ?>
                </td>
            </tr>            
            <tr>
                <td style="width: 170px" class="left-padding">Minimum | Maximum:</td>
                <td colspan="2">
                    <?php
                    echo Form::text(
                        array(
                            'name' => 'valueAxesMin',
                            'id' => 'valueAxesMin',
                            'class' => 'form-control',
                            'style' => 'width:50%;float:left',
                            'value' => Arr::get($this->diagram, 'MINIMUM_VALUE'),
                            'placeholder' => 'Minimum'
                        )
                    );
                    ?>
                    <?php
                    echo Form::text(
                        array(
                            'name' => 'valueAxesMax',
                            'id' => 'valueAxesMax',
                            'class' => 'form-control',
                            'style' => 'width:50%;float:left;border-left:1px solid',
                            'value' => Arr::get($this->diagram, 'MAXIMUM_VALUE'),
                            'placeholder' => 'Maximum'
                        )
                    );
                    ?>
                </td>
            </tr>                   
            <tr>
                <td style="width: 170px" class="left-padding" for="templateWidth">Template width:</td>
                <td colspan="2">
                    <?php
                    echo Form::text(
                        array(
                            'name' => 'templateWidth',
                            'id' => 'templateWidth',
                            'class' => 'form-control',
                            'style' => 'float:left',
                            'value' => Arr::get($this->diagram, 'TEMPLATE_WIDTH'),
                            'placeholder' => 'Minimum'
                        )
                    );
                    ?>
                </td>
            </tr>            
            <tr>
                <td style="width: 170px" class="left-padding">Color field:</td>
                <td colspan="2" id="colorFieldTd"></td>
            </tr>
            <tr>
                <?php
                $getAddonSettings = json_decode(Arr::get($this->diagram, 'ADDON_SETTINGS'), true);
                ?>                
                <td style="width: 170px" class="left-padding">Шүүлтүүр байрлал:</td>
                <td colspan="2">
                    <?php
                    echo Form::select(
                        array(
                            'name' => 'chartCriteriaPostion',
                            'id' => 'chartCriteriaPostion',
                            'data' => array(
                                array(
                                    'value' => 'top',
                                    'name' => 'Top',
                                ), 
                                array(
                                    'value' => 'topFilterButton',
                                    'name' => 'Top filter button',
                                )
                            ),
                            'op_value' => 'value',
                            'op_text' => 'name',
                            'class' => 'form-control',
                            'value' => Arr::get($getAddonSettings, 'criteriaPosition')
                        )
                    );
                    ?>                    
                </td>
            </tr>            
            <tr>
                <?php
                $getAddonSettings = json_decode(Arr::get($this->diagram, 'ADDON_SETTINGS'), true);
                ?>                
                <td style="width: 170px" class="left-padding">Шүүлтүүр багана хуваалт:</td>
                <td colspan="2">
                    <?php
                    echo Form::select(
                        array(
                            'name' => 'chartCriteriaSplitColumnCount',
                            'id' => 'chartCriteriaSplitColumnCount',
                            'data' => array( 
                                array(
                                    'value' => '2',
                                    'name' => '2',
                                ),
                                array(
                                    'value' => '3',
                                    'name' => '3',
                                ),
                                array(
                                    'value' => '4',
                                    'name' => '4',
                                ),
                                array(
                                    'value' => '6',
                                    'name' => '6',
                                ),
                            ),
                            'op_value' => 'value',
                            'op_text' => 'name',
                            'class' => 'form-control',
                            'value' => Arr::get($getAddonSettings, 'criteriaSplitColumnCount')
                        )
                    );
                    ?>                    
                </td>
            </tr>            
        </tbody>
    </table>
</div>
<div class="panel panel-default bg-inverse">
    <table class="table sheetTable">
        <tbody>
            <tr class="chart-addon-settings-name d-none">
                <td colspan="3" style="padding: 8px;" class=""><span class="addon-configuration"></span> тохиргоонууд</td>
            </tr>
            <tr class="pie_charts_bullets chart-addon-settings d-none">
                <td class="left-padding" style="width: 170px">Sub Pie chart value</td>
                <td colspan="2">
                    <input type="text" name="pie_charts_bullets_value" id="pie_charts_bullets_value" class="form-control" value="<?php echo Arr::get($getAddonSettings, 'value') ?>" placeholder="Утга">
                </td>
            </tr>    
            <tr class="pie_charts_bullets chart-addon-settings d-none">
                <td class="left-padding" style="width: 170px">Sub Pie chart title</td>
                <td colspan="2">
                    <input type="text" name="pie_charts_bullets_title" id="pie_charts_bullets_title" class="form-control" value="<?php echo Arr::get($getAddonSettings, 'title') ?>" placeholder="Нэр">
                </td>
            </tr>    
            <tr class="risk_heatmap chart-addon-settings d-none">
                <td class="left-padding" style="width: 170px">Value</td>
                <td colspan="2">
                    <input type="text" name="risk_heatmap_value" id="risk_heatmap_value" class="form-control" value="<?php echo Arr::get($getAddonSettings, 'risKvalue') ?>" placeholder="Утга">
                </td>
            </tr>                
            <tr class="animated_xy_bubble chart-addon-settings d-none">
                <td class="left-padding" style="width: 170px">Value</td>
                <td colspan="2">
                    <input type="text" name="animated_xy_bubble_value" id="animated_xy_bubble_value" class="form-control" value="<?php echo Arr::get($getAddonSettings, 'bubbleValue') ?>" placeholder="Утга">
                </td>
            </tr>                
            <tr class="animated_xy_bubble chart-addon-settings d-none">
                <td class="left-padding" style="width: 170px">Minimum</td>
                <td colspan="2">
                    <input type="text" name="chart_minimum" id="chart_minimum" class="form-control" value="<?php echo Arr::get($getAddonSettings, 'min') ?>" placeholder="Утга">
                </td>
            </tr>                
            <tr class="animated_xy_bubble chart-addon-settings d-none">
                <td class="left-padding" style="width: 170px">Maximum</td>
                <td colspan="2">
                    <input type="text" name="chart_maximum" id="chart_maximum" class="form-control" value="<?php echo Arr::get($getAddonSettings, 'max') ?>" placeholder="Утга">
                </td>
            </tr>                
            <tr class="variable_radius_radar chart-addon-settings d-none">
                <td class="left-padding" style="width: 170px">Category</td>
                <td colspan="2">
                    <input type="text" name="category" id="category" class="form-control" value="<?php echo Arr::get($getAddonSettings, 'category') ?>" placeholder="">
                </td>
            </tr>                
            <tr class="am_stacked_bar_chart chart-addon-settings d-none">
                <td class="left-padding pt10 pb10" style="width: 170px">Is vertical</td>
                <td colspan="2">
                    <input type="checkbox" name="isvertical" id="isvertical" class="form-control" value="1"<?php echo Arr::get($getAddonSettings, 'isvertical') == '1' ? ' checked' : '' ?> placeholder="">
                </td>
            </tr>                
            <tr class="am_stacked_bar_chart chart-addon-settings d-none">
                <td class="left-padding pt10 pb10" style="width: 170px">Stack Y тэнхлэг</td>
                <td colspan="2">
                    <input type="text" name="stacky" id="stacky" class="form-control" value="<?php echo Arr::get($getAddonSettings, 'stacky') ?>" placeholder="">
                </td>
            </tr>                
            <tr class="am_stacked_bar_chart chart-addon-settings d-none">
                <td class="left-padding pt10 pb10" style="width: 170px">Stack X тэнхлэг групплэх</td>
                <td colspan="2">
                    <input type="text" name="stackx" id="stackx" class="form-control" value="<?php echo Arr::get($getAddonSettings, 'stackx') ?>" placeholder="">
                </td>
            </tr>                
            <tr class="am_stacked_bar_chart chart-addon-settings d-none">
                <td class="left-padding pt10 pb10" style="width: 170px">Группын дараалал path</td>
                <td colspan="2">
                    <input type="text" name="stackxorder" id="stackxorder" class="form-control" value="<?php echo Arr::get($getAddonSettings, 'stackxorder') ?>" placeholder="">
                </td>
            </tr>              
            <tr class="am_donut chart-addon-settings d-none">
                <td class="left-padding pt10 pb10" style="width: 170px">Center label text</td>
                <td colspan="2">
                    <input type="text" name="centerlabeltext" id="centerlabeltext" class="form-control" value="<?php echo Arr::get($getAddonSettings, 'centerlabeltext') ?>" placeholder="">
                </td>
            </tr>                
            <tr class="am_donut chart-addon-settings d-none">
                <td class="left-padding pt10 pb10" style="width: 170px">Center label number path</td>
                <td colspan="2">
                    <input type="text" name="centerlabelnumber" id="centerlabelnumber" class="form-control" value="<?php echo Arr::get($getAddonSettings, 'centerlabelnumber') ?>" placeholder="">
                </td>
            </tr>                
            <tr class="am_donut chart-addon-settings d-none">
                <td class="left-padding pt10 pb10" style="width: 170px">Label font size</td>
                <td colspan="2">
                    <input type="text" name="labelfontsize" id="labelfontsize" class="form-control" value="<?php echo Arr::get($getAddonSettings, 'labelfontsize') ?>" placeholder="">
                </td>
            </tr>                
            <tr class="am_donut chart-addon-settings d-none">
                <td class="left-padding pt10 pb10" style="width: 170px">Label marker width</td>
                <td colspan="2">
                    <input type="text" name="labelmarkerwidth" id="labelmarkerwidth" class="form-control" value="<?php echo Arr::get($getAddonSettings, 'labelmarkerwidth') ?>" placeholder="">
                </td>
            </tr>                
            <tr class="am_donut chart-addon-settings d-none">
                <td class="left-padding pt10 pb10" style="width: 170px">Label marker height</td>
                <td colspan="2">
                    <input type="text" name="labelmarkerheight" id="labelmarkerheight" class="form-control" value="<?php echo Arr::get($getAddonSettings, 'labelmarkerheight') ?>" placeholder="">
                </td>
            </tr>                
        </tbody>
    </table>
</div>

<script type="text/javascript">
    $(function () {
        
        var isMultipleProcess = '<?php echo $this->diagram['IS_MULTIPLE_PROCESS'] ?>';
        
        if (isMultipleProcess == '1') {
            $('.isMultiple_processName').html('Оролтын параметр 1:');
            $('.isCardProcessTitleText').html('Гарчиг 1:');
            $('.isCardProcessValueText').html('Утга 1:');
            $('.isCardProcess_multiple').removeClass('hidden');
            $('.isCardProcess_multiple').css('display', 'table-row');
            
            $('#isMultipleProcess').attr('checked', 'checked');
            $('#isMultipleProcess').parent().addClass('checked');
        }
        
        $('#isShowTitle').click(function () {
            if (!$('#isShowTitle').is(':checked')) {
                $('#chartTitleRow').hide();
                $('#isShowTitle').attr('checked', false);
                $('#isShowTitle').val('0');
            } else {
                $('#chartTitleRow').css('display', 'table-row');
                $('#isShowTitle').attr('checked', true);
                $('#isShowTitle').val('1');
            }
        });

        $('#isShowLabel').click(function () {
            if ($('#isShowLabel').parent('span').hasClass('checked')) {
                $('#isShowLabel').attr('checked', true);
                $('#isShowLabel').val('1');
            } else {
                $('#isShowLabel').attr('checked', false);
                $('#isShowLabel').val('0');
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
            var $this = $(this),
                thisVal = $this.val();
                
            getColumnsAjax();            
            $('.chart-addon-settings').addClass('d-none');
            $('.addon-configuration').text('');
            
            if (thisVal == 'pie_charts_bullets') {
                $('.addon-configuration').text($this.find('option:selected').text());                
                $('.'+thisVal+', .chart-addon-settings-name').removeClass('d-none');
            }
            if (thisVal == 'risk_heatmap') {
                $('.addon-configuration').text($this.find('option:selected').text());                
                $('.'+thisVal+', .chart-addon-settings-name').removeClass('d-none');
            }
            if (thisVal == 'animated_xy_bubble') {
                $('.addon-configuration').text($this.find('option:selected').text());                
                $('.'+thisVal+', .chart-addon-settings-name').removeClass('d-none');
            }
            if (thisVal == 'variable_radius_radar') {
                $('.addon-configuration').text($this.find('option:selected').text());                
                $('.'+thisVal+', .chart-addon-settings-name').removeClass('d-none');
            }
            if (thisVal == 'am_stacked_bar_chart') {
                $('.addon-configuration').text($this.find('option:selected').text());                
                $('.'+thisVal+', .chart-addon-settings-name').removeClass('d-none');
            }
            if (thisVal == 'am_donut') {
                $('.addon-configuration').text($this.find('option:selected').text());                
                $('.'+thisVal+', .chart-addon-settings-name').removeClass('d-none');
            }
        });        
        
        changeDashboardType();
        
        changeDiagramType();
        
        $('.chart-colorpicker-default').colorpicker({
            format: 'hex'
        });        
    });
    
    function checkBoxMultiProcess(element) {
        $('.isCardProcess_multiple').hide();
        $('.isCardProcess_multiple').addClass('hidden');
        $('.isMultiple_processName').html('<?php echo $this->lang->line('META_00046'); ?>');
        $('.isCardProcessTitleText').html('X тэнхлэг:');
        $('.isCardProcessValueText').html('Y тэнхлэг:');
        
        if ($(element).is(':checked')) {
            $('.isMultiple_processName').html('Оролтын параметр 1:');
            $('.isCardProcessTitleText').html('Гарчиг 1:');
            $('.isCardProcessValueText').html('Утга 1:');
            $('.isCardProcess_multiple').css('display', 'table-row');
            $('.isCardProcess_multiple').removeClass('hidden');
        }
    }
    
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
                $('.amChartTr').css('display', 'table-row');
                $('#dashboard-type-tr').css('display', 'table-row');
                $('.twoValueAxis').hide();
                $('.isCardHiddenTr').hide();
                $('#isCardProcess').css('display', 'table-row');
                $('.isCardProcessTitleText').html('Гарчиг 1:');
                $('.isCardProcessValueText').html('Утга 1:');
                getColumnsAjax();
                getColumnsAjax2();
                getColumnsAjax3();
                getColumnsAjax4();
                break;
            }
            case 'amchart'  : {
                $('.defaultTr').hide();
                $('.amChartTr').css('display', 'table-row');
                $('#dashboard-type-tr').css('display', 'table-row');
                $('#dashboard-label-substr').css('display', 'table-row');
                
                getColumnsAjax();
                break;
            }
            case 'd3_sunburst'  : {
                $('.defaultTr').hide();
                $('.isShowDataviewChart').hide();
                $('.isShowDataviewChart').hide();
                $('.isShowDataviewChart').hide();
                $('.amChartTr').css('display', 'table-row');
                $('#dashboard-type-tr').css('display', 'table-row');
                $('#dashboard-label-substr').css('display', 'table-row');
                
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
                $('#dashboard-type-tr').css('display', 'table-row');
                $('.isCardHiddenTr').hide();
                $('.chartWidth').hide();
                $('.defaultTr').hide();
                $('.chartExportBtn').hide();
                $('.chartxLabelRotation').hide();
                $('.isShowDataviewChart').hide();
                $('.chartValueAxisTitle').hide();
                $('.chartCategoryAxisTitle').hide();
                $('#chart-color').css('display', 'table-row');
                $('#chart-color2').css('display', 'table-row');
                $('.amChartTr').css('display', 'table-row');
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
                $('.defaultTr').css('display', 'table-row');
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
                $('#chartType').val('<?php echo $this->diagram['DIAGRAM_TYPE'] ?>');
                $('#chartType').select2('val', '<?php echo $this->diagram['DIAGRAM_TYPE'] ?>');
                $('#chartType').trigger('change');
            }
        });
    }

    function changeDiagramType() {
        var dashboardType = $('#chartType').val();
        switch (dashboardType) { 
            case 'line' : {
                var html =   '<tr id="isMultipleTr">' 
                                + '<td style="width: 170px" class="left-padding">Олон график харагдах эсэх:</td>' 
                                + '<td><input type="checkbox" id="isMultiple" name="isMultiple" class="" value="1" /></td>' 
                              + '</tr>';
                if ($('#isMultipleTr').length == 0) {
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
                if ($('#isLittleTr').length == 0) {
                    $('#dashboard-type-tr').after(html);
                }
                break;
            }
            case 'am_stacked_bar_chart' :
            case 'am_3d_stacked_column_chart_2' :
            case 'am_reversed' : {
                $('.amChartTr').hide();
                $('.reversedValueAxis').css('display', 'table-row');
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
        Core.initAjax($("#objectTableLinks"));
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
                setXaxis(response, '<?php echo $this->diagram['XAXIS'] ?>');
                setYaxis(response, '<?php echo $this->diagram['YAXIS'] ?>');
                setColorField(response, '<?php echo $this->diagram['COLOR_FIELD'] ?>');
                setXaxisGroup(response, '<?php echo $this->diagram['XAXISGROUP'] ?>');
                setYaxisGroup(response, '<?php echo $this->diagram['YAXISGROUP'] ?>');
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
        var isMultiple = '';
        var html = '<select id="xaxis" name="xaxis" class="form-control select2" '+ isMultiple +' data-placeholder="- Сонгох -" tabindex="-1" title=""><option value="">- Сонгох -</option>';
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
        $('#xaxisTd').html(html);
        
        Core.initAjax($('#xaxisTd'));        
    }
    
    function setColorField(data, defaultValue) {     
        var isMultiple = '';
        var html = '<select id="colorField" name="colorField" class="form-control select2" '+ isMultiple +' data-placeholder="- Сонгох -" tabindex="-1" title=""><option value="">- Сонгох -</option>';
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
        $('#colorFieldTd').html(html);
        
        Core.initAjax($('#colorFieldTd'));        
    }
    
    function setYaxis(data, defaultValue){
        var isMultiple = '';
        switch ($("#chartType").val()) { 
            case 'am_dual' :
            case 'am_bar_axis' :
            case 'durarion_onvalue_axis' :
            case 'durarion_onvalue_axis2' :
            case 'multiple_value_axis' :
            case 'am_reversed' :
            case 'clustered_bar_chart' :
            case 'clustered_bar_chart_horizontal' :
            case 'am_3d_stacked_column_chart' :
            case 'am_3d_stacked_column_chart_2' :
            case 'am_stacked_bar_chart' :
            case 'am_radar_chart' :
            case 'am_combined_bullet' : {
                isMultiple= 'multiple="multiple"';
                break;
            }
            default : break
        }
        
        var html = '<select id="yaxis" name="yaxis[]" class="form-control select2" '+ isMultiple +' data-placeholder="- Сонгох -" tabindex="-1" title=""><option value="">- Сонгох -</option>';
        if (typeof data !== null) {
            var defaultValues = [];
            switch ($("#chartType").val()) { 
                case 'am_dual' :
                case 'am_bar_axis' :
                case 'durarion_onvalue_axis' :
                case 'durarion_onvalue_axis2' :
                case 'multiple_value_axis' :
                case 'am_reversed' :
                case 'clustered_bar_chart' :
                case 'clustered_bar_chart_horizontal' :
                case 'am_3d_stacked_column_chart' :
                case 'am_3d_stacked_column_chart_2' :
                case 'am_radar_chart' :
                case 'am_stacked_bar_chart' :
                case 'am_combined_bullet' : {
                    defaultValues = defaultValue.split(',');
                }
                default : break
            }
            $.each(data, function (key, value) {
                var isSelected = '';
                if(defaultValue !== 'undefined') {
                    if(value.FIELD_PATH === defaultValue && isMultiple != 'multiple="multiple"') {
                        isSelected = 'selected="selected"';
                    }
                    else {
                        if(jQuery.inArray( value.FIELD_PATH, defaultValues ) > -1) {
                            isSelected = 'selected="selected"';
                            
                        }
                    }
                }
                html += '<option value="'+value.FIELD_PATH+'" '+isSelected+'>'+value.LABEL_NAME+'</option>';
            });
        }
        html += '</select>'; 
        $('#yaxisTd').html(html);
        
        Core.initAjax($('#yaxisTd'));        
    }
    
    function setXaxisGroup(data, defaultValue){
        var html = '<select id="xaxisGroup" name="xaxisGroup" class="form-control select2" data-placeholder="- Сонгох -" tabindex="-1" title=""><option value="">- Сонгох -</option>';
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
        $('#xaxisGroupTd').html(html);
        
        Core.initAjax($('#xaxisGroupTd'));        
    }
    
    function setYaxisGroup(data, defaultValue){
        var html = '<select id="yaxisGroup" name="yaxisGroup" class="form-control select2" data-placeholder="- Сонгох -" tabindex="-1" title=""><option value="">- Сонгох -</option>';
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
        $('#yaxisGroupTd').html(html);
        
        Core.initAjax($('#yaxisGroupTd'));        
    }
    
    function setXaxis2(data, defaultValue) {
        var html = '<select id="xaxis" name="xaxis2" class="form-control select2" onchange="changeDiagramType()" data-placeholder="- Сонгох -" tabindex="-1" title=""><option value="">- Сонгох -</option>';
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
            case 'am_radar_chart' :
            case 'am_combined_bullet' : {
                isMultiple= 'multiple="multiple"';
                break;
            }
            default : break
        }
        
        console.log($("#chartType").val());
        console.log(isMultiple);
        
        var html = '<select id="yaxis" name="yaxis2" class="form-control select2" '+ isMultiple +' onchange="changeDiagramType()" data-placeholder="- Сонгох -" tabindex="-1" title=""><option value="">- Сонгох -</option>';
        
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
        $('#yaxisTd2').html(html);
        
        Core.initAjax($('#yaxisTd2'));        
    }
    
    function setXaxis3(data, defaultValue) {
        var html = '<select id="xaxis" name="xaxis3" class="form-control select2" onchange="changeDiagramType()" data-placeholder="- Сонгох -" tabindex="-1" title=""><option value="">- Сонгох -</option>';
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
                
                setXaxis2(response, '<?php echo $this->diagram['XAXIS2'] ?>');
                setYaxis2(response, '<?php echo $this->diagram['YAXIS2'] ?>');
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
                setXaxis3(response, '<?php echo $this->diagram['XAXIS3'] ?>');
                setYaxis3(response, '<?php echo $this->diagram['YAXIS3'] ?>');
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
                setXaxis4(response, '<?php echo $this->diagram['XAXIS4'] ?>');
                setYaxis4(response, '<?php echo $this->diagram['YAXIS4'] ?>');
            },
            error: function (jqXHR, exception) {
                Core.unblockUI();
            }
        }).complete(function () {
            Core.unblockUI();
        });

        return result;
    }
    
</script>

<style type="text/css">
    .amChartTr {
        display: none;
    }
</style>