<div class="panel panel-default bg-inverse mb0">
    <table class="table sheetTable" style="table-layout: fixed">
        <tbody>
            <tr>
                <td style="width: 118px; height: 30px;" class="left-padding">Path:</td>
                <td class="pl5" style="word-wrap: break-word;"><?php echo $this->paramPath; ?></td>
            </tr>    
            <tr>
                <td style="height: 30px;" class="left-padding"><label for="expressionString_<?php echo $this->paramPath; ?>">Set expression:</label></td>
                <td>
                    <input type="text" name="groupParam[<?php echo $this->paramPath; ?>][expressionString]" id="expressionString" value="<?php echo $this->paramRow['EXPRESSION_STRING']; ?>" id="expressionString_<?php echo $this->paramPath; ?>" class="form-control form-control-sm stringInit" placeholder="Set expression">
                </td>
            </tr>
            <tr>
                <td style="height: 30px;" class="left-padding">Exp/Criteria:</td>
                <td class="pl5">
                    <button type="button" class="btn btn-sm purple-plum" onclick="setProcessExpressionCriteria(this);">...</button>
                    <?php
                    echo Form::hidden(
                        array(
                            'name' => 'groupParam['.$this->paramPath.'][valueCriteria]',
                            'id' => 'valueCriteria',
                            'value' => $this->paramRow['VALUE_CRITERIA']
                        )
                    );
                    echo Form::hidden(
                        array(
                            'name' => 'groupParam['.$this->paramPath.'][styleCriteria]',
                            'id' => 'styleCriteria',
                            'value' => $this->paramRow['STYLE_CRITERIA']
                        )
                    );
                    echo Form::hidden(
                        array(
                            'name' => 'groupParam['.$this->paramPath.'][processMetaDataId]',
                            'id' => 'processMetaDataId',
                            'value' => $this->paramRow['PROCESS_META_DATA_ID']
                        )
                    );
                    echo Form::hidden(
                        array(
                            'name' => 'groupParam['.$this->paramPath.'][processGetParamPath]',
                            'id' => 'processGetParamPath',
                            'value' => $this->paramRow['PROCESS_GET_PARAM_PATH']
                        )
                    );
                    echo Form::hidden(
                        array(
                            'name' => 'groupParam['.$this->paramPath.'][processMetaDataIdPath]',
                            'id' => 'processMetaDataIdPath',
                            'value' => $this->paramRow['INLINE_PROCESS_ID']
                        )
                    );
                    ?>
                    <div class="lookup-param-configs display-none">
                        <?php echo (new Mdmetadata())->getGroupParamConfig($this->groupMetaDataId, $this->paramPath, true); ?>
                    </div>
                    <div class="process-param-configs display-none">
                        <?php echo (new Mdmetadata())->getGroupProcessParamConfig($this->groupMetaDataId, $this->paramPath, true); ?>
                    </div>
                </td>
            </tr>
            <tr>
                <td style="height: 30px;" class="left-padding">Lookup /<?php echo $this->lang->line('metadata_const_value'); ?>/:</td>
                <td class="pl5">
                    <button type="button" class="btn btn-sm purple-plum" onclick="paramDefaultValuesLookup(this);" title="Утга тохируулах">...</button>
                    <div class="param-values-config display-none">
                        <?php echo (new Mdmetadata())->getParamDefaultValues($this->groupMetaDataId, $this->paramPath, $this->paramRow['LOOKUP_META_DATA_ID']); ?>
                    </div>
                </td>
            </tr> 
            <tr>
                <td style="height: 30px;" class="left-padding">RefStructure:</td>
                <td>
                    <div class="meta-autocomplete-wrap" data-params="autoSearch=1&grouptype=tablestructure|dataview&metaTypeId=<?php echo Mdmetadata::$metaGroupMetaTypeId; ?>">
                        <div class="input-group double-between-input">
                            <input id="refStructureId" name="groupParam[<?php echo $this->paramPath; ?>][refStructureId]" type="hidden" value="<?php echo $this->paramRow['REF_STRUCTURE_ID']; ?>">
                            <input id="_displayField" class="form-control form-control-sm md-code-autocomplete" value="<?php echo $this->paramRow['STRUCTURE_META_DATA_CODE']; ?>" title="<?php echo $this->paramRow['STRUCTURE_META_DATA_CODE']; ?>" placeholder="<?php echo $this->lang->line('META_00068'); ?>" type="text">
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
                                <input id="_nameField" class="form-control form-control-sm md-name-autocomplete" value="<?php echo $this->paramRow['STRUCTURE_META_DATA_NAME']; ?>" title="<?php echo $this->paramRow['STRUCTURE_META_DATA_NAME']; ?>" placeholder="<?php echo $this->lang->line('META_00099'); ?>" type="text">      
                            </span>     
                        </div>
                    </div>
                </td>
            </tr>
            <tr>
                <td style="height: 30px;" class="left-padding"><label for="refParamName_<?php echo $this->paramPath; ?>">RefParamName:</label></td>
                <td>
                    <div class="input-group float-left">
                        <input type="text" name="groupParam[<?php echo $this->paramPath; ?>][refParamName]" value="<?php echo $this->paramRow['REF_PARAM_NAME']; ?>" id="refParamName_<?php echo $this->paramPath; ?>" placeholder="Ref param name" class="form-control form-control-sm" style="min-width: 150px;">
                        <span class="input-group-btn">
                            <button type="button" class="btn btn-sm purple-plum mr0" onclick="setParamRelation(this);">...</button>
                        </span>
                    </div>
                    <div class="relation-param-configs display-none">
                        <?php //echo (new Mdmetadata())->getGroupRelationConfig($this->groupMetaDataId, $this->paramPath); ?>
                    </div>
                </td>
            </tr>
            <tr>
                <td style="height: 30px;" class="left-padding"><label for="orderNumber_<?php echo $this->paramPath; ?>">Order number:</label></td>
                <td>
                    <input type="text" name="groupParam[<?php echo $this->paramPath; ?>][orderNumber]" value="<?php echo $this->paramRow['ORDER_NUMBER']; ?>" id="orderNumber_<?php echo $this->paramPath; ?>" class="form-control form-control-sm stringInit" placeholder="Order number">
                </td>
            </tr>
            <tr>
                <td style="height: 30px;" class="left-padding"><label for="isTranslate_<?php echo $this->paramPath; ?>">Is translate:</label></td>
                <td class="pl5">
                    <?php
                    echo Form::checkbox(array('name' => 'groupParam['.$this->paramPath.'][isTranslate]', 'id' => 'isTranslate_'.$this->paramPath, 'value' => '1', 'saved_val' => $this->paramRow['IS_TRANSLATE'], 'class' => 'notuniform'));
                    ?>
                </td>
            </tr>
            <tr>
                <td style="height: 30px;" class="left-padding"><label for="isShowMobile_<?php echo $this->paramPath; ?>">Is mobile:</label></td>
                <td class="pl5">
                    <?php
                    echo Form::checkbox(array('name' => 'groupParam['.$this->paramPath.'][isShowMobile]', 'id' => 'isShowMobile_'.$this->paramPath, 'value' => '1', 'saved_val' => $this->paramRow['IS_SHOW_MOBILE'], 'class' => 'notuniform'));
                    ?>
                </td>
            </tr>
            <tr>
                <td style="height: 30px;" class="left-padding"><label for="isMerge_<?php echo $this->paramPath; ?>">Is merge:</label></td>
                <td class="pl5">
                    <?php
                    echo Form::checkbox(array('name' => 'groupParam['.$this->paramPath.'][isMerge]', 'id' => 'isMerge_'.$this->paramPath, 'value' => '1', 'saved_val' => $this->paramRow['IS_MERGE'], 'class' => 'notuniform'));
                    ?>
                </td>
            </tr>
            <tr>
                <td style="height: 30px;" class="left-padding"><label for="isCriteriaShowBasket_<?php echo $this->paramPath; ?>">Is criteria show basket:</label></td>
                <td class="pl5">
                    <?php
                    echo Form::checkbox(array('name' => 'groupParam['.$this->paramPath.'][isCriteriaShowBasket]', 'id' => 'isCriteriaShowBasket_'.$this->paramPath, 'value' => '1', 'saved_val' => $this->paramRow['IS_CRITERIA_SHOW_BASKET'], 'class' => 'notuniform'));
                    ?>
                </td>
            </tr>
            <tr>
                <td style="height: 30px;" class="left-padding"><label for="isMandatoryCriteria_<?php echo $this->paramPath; ?>">Is mandatory criteria:</label></td>
                <td class="pl5">
                    <?php
                    echo Form::checkbox(array('name' => 'groupParam['.$this->paramPath.'][isMandatoryCriteria]', 'id' => 'isMandatoryCriteria_'.$this->paramPath, 'value' => '1', 'saved_val' => $this->paramRow['IS_MANDATORY_CRITERIA'], 'class' => 'notuniform'));
                    ?>
                </td>
            </tr>
            <tr>
                <td style="height: 30px;" class="left-padding"><label for="isUnique_<?php echo $this->paramPath; ?>">Is unique:</label></td>
                <td class="pl5">
                    <?php
                    echo Form::checkbox(array('name' => 'groupParam['.$this->paramPath.'][isUnique]', 'id' => 'isUnique_'.$this->paramPath, 'value' => '1', 'saved_val' => $this->paramRow['IS_UNIQUE'], 'class' => 'notuniform'));
                    ?>
                </td>
            </tr>
            <tr>
                <td style="height: 30px;" class="left-padding"><label for="isGroup_<?php echo $this->paramPath; ?>"><?php echo $this->lang->line('metadata_is_group'); ?>:</label></td>
                <td class="pl5">
                    <?php
                    echo Form::checkbox(array('name' => 'groupParam['.$this->paramPath.'][isGroup]', 'id' => 'isGroup_'.$this->paramPath, 'value' => '1', 'saved_val' => $this->paramRow['IS_GROUP'], 'class' => 'notuniform'));
                    ?>
                </td>
            </tr>
            <tr>
                <td style="height: 30px;" class="left-padding"><label for="columnWidth_<?php echo $this->paramPath; ?>"><?php echo $this->lang->line('META_00048'); ?> <i class="fa fa-info-circle" title="<?php echo $this->lang->line('META_00123'); ?>"></i></label></td>
                <td>
                    <input type="text" name="groupParam[<?php echo $this->paramPath; ?>][columnWidth]" value="<?php echo $this->paramRow['COLUMN_WIDTH']; ?>" id="columnWidth_<?php echo $this->paramPath; ?>" class="form-control form-control-sm" placeholder="<?php echo $this->lang->line('META_00048'); ?>">
                </td>
            </tr>
            <tr>
                <td style="height: 30px;" class="left-padding"><label for="sidebarName_<?php echo $this->paramPath; ?>"><?php echo $this->lang->line('META_00122'); ?></label></td>
                <td>
                    <input type="text" name="groupParam[<?php echo $this->paramPath; ?>][sidebarName]" value="<?php echo $this->paramRow['SIDEBAR_NAME']; ?>" id="sidebarName_<?php echo $this->paramPath; ?>" class="form-control form-control-sm stringInit" placeholder="<?php echo $this->lang->line('META_00122'); ?>">
                </td>
            </tr>
            <tr>
                <td style="height: 30px;" class="left-padding"><label for="searchGroupName_<?php echo $this->paramPath; ?>"><?php echo $this->lang->line('metadata_group_name'); ?>:</label></td>
                <td>
                    <input type="text" name="groupParam[<?php echo $this->paramPath; ?>][searchGroupName]" value="<?php echo $this->paramRow['SEARCH_GROUPING_NAME']; ?>" id="searchGroupName_<?php echo $this->paramPath; ?>" class="form-control form-control-sm stringInit" placeholder="<?php echo $this->lang->line('metadata_group_name'); ?>">
                </td>
            </tr>
            <tr>
                <td style="height: 30px;" class="left-padding"><label for="aggregateFunction_<?php echo $this->paramPath; ?>">Aggregate:</label></td>
                <td>
                    <?php
                    echo Form::select(
                        array(
                            'name' => 'groupParam['.$this->paramPath.'][aggregateFunction]',
                            'id' => 'aggregateFunction_'.$this->paramPath,
                            'class' => 'form-control form-control-sm',
                            'data' => array(
                                array(
                                    'id' => 'sum',
                                    'name' => 'SUM'
                                ),
                                array(
                                    'id' => 'wm_concat',
                                    'name' => 'WM_CONCAT'
                                ),
                                array(
                                    'id' => 'group',
                                    'name' => 'GROUP'
                                ),
                                array(
                                    'id' => 'min',
                                    'name' => 'MIN'
                                ),
                                array(
                                    'id' => 'max',
                                    'name' => 'MAX'
                                ),
                                array(
                                    'id' => 'count',
                                    'name' => 'COUNT'
                                ),
                                array(
                                    'id' => 'avg',
                                    'name' => 'AVG'
                                ), 
                                array(
                                    'id' => 'text',
                                    'name' => 'TEXT'
                                ), 
                                array(
                                    'id' => 'distinct_count',
                                    'name' => 'Distinct count'
                                ) 
                            ),
                            'op_value' => 'id',
                            'op_text' => 'name',
                            'value' => $this->paramRow['AGGREGATE_FUNCTION']
                        )
                    );
                    ?>
                </td>
            </tr>
            <tr>
                <td style="height: 30px;" class="left-padding"><label for="themePosition_<?php echo $this->paramPath; ?>">Widget position:</label></td>
                <td>
                    <?php
                    echo Form::select(
                        array(
                            'name' => 'groupParam['.$this->paramPath.'][themePosition]',
                            'id' => 'themePosition_'.$this->paramPath,
                            'class' => 'form-control form-control-sm',
                            'data' => $this->positionList,
                            'op_value' => 'id',
                            'op_text' => 'name',
                            'value' => $this->paramRow['THEME_POSITION_NO']
                        )
                    );
                    ?>
                </td>
            </tr>
            <tr>
                <td style="height: 30px;" class="left-padding"><label for="renderType_<?php echo $this->paramPath; ?>">Control subtype:</label></td>
                <td>
                    <?php
                    echo Form::select(
                        array(
                            'name' => 'groupParam['.$this->paramPath.'][renderType]',
                            'id' => 'renderType_'.$this->paramPath,
                            'class' => 'form-control form-control-sm',
                            'data' => $this->controlSubType,
                            'op_value' => 'TYPE_CODE',
                            'op_text' => 'TYPE_CODE',
                            'value' => $this->paramRow['RENDER_TYPE']
                        )
                    );
                    ?>
                </td>
            </tr>
            <tr>
                <td style="height: 30px;" class="left-padding"><label for="separatorType_<?php echo $this->paramPath; ?>"><?php echo $this->lang->line('META_00030'); ?></label></td>
                <td>
                    <?php
                    echo Form::select(
                        array(
                            'name' => 'groupParam['.$this->paramPath.'][separatorType]',
                            'id' => 'separatorType_'.$this->paramPath,
                            'class' => 'form-control form-control-sm',
                            'data' => array(
                                array(
                                    'id' => 'solid',
                                    'name' => 'Solid'
                                ),
                                array(
                                    'id' => 'dotted',
                                    'name' => 'Dotted'
                                ),
                                array(
                                    'id' => 'double',
                                    'name' => 'Double'
                                )
                            ),
                            'op_value' => 'id',
                            'op_text' => 'name',
                            'value' => $this->paramRow['SEPARATOR_TYPE']
                        )
                    );
                    ?>
                </td>
            </tr>
            <tr>
                <td style="height: 30px;" class="left-padding"><label for="patternId_<?php echo $this->paramPath; ?>"><?php echo $this->lang->line('META_00183'); ?></label></td>
                <td>
                    <?php
                    echo Form::select(
                        array(
                            'name' => 'groupParam['.$this->paramPath.'][patternId]',
                            'id' => 'patternId_'.$this->paramPath,
                            'class' => 'form-control form-control-sm',
                            'data' => $this->maskData,
                            'op_value' => 'PATTERN_ID',
                            'op_text' => 'PATTERN_NAME',
                            'value' => $this->paramRow['PATTERN_ID']
                        )
                    );
                    ?>
                </td>
            </tr>
            <tr>
                <td style="height: 30px;" class="left-padding"><label for="dataMask_<?php echo $this->paramPath; ?>"><?php echo $this->lang->line('Дата маск'); ?></label></td>
                <td>
                    <?php
                    echo Form::select(
                        array(
                            'name' => 'groupParam['.$this->paramPath.'][dataMask]',
                            'id' => 'dataMask_'.$this->paramPath,
                            'class' => 'form-control form-control-sm',
                            'data' => $this->dataMask,
                            'op_value' => 'PATTERN_ID',
                            'op_text' => 'PATTERN_NAME',
                            'value' => $this->paramRow['DATA_MASK']
                        )
                    );
                    ?>
                </td>
            </tr>
            <tr>
                <td style="height: 30px;" class="left-padding"><label for="placeholderName_<?php echo $this->paramPath; ?>"><?php echo $this->lang->line('Placeholder'); ?></label></td>
                <td>
                    <input type="text" name="groupParam[<?php echo $this->paramPath; ?>][placeholderName]" value="<?php echo $this->paramRow['PLACEHOLDER_NAME']; ?>" id="placeholderName_<?php echo $this->paramPath; ?>" class="form-control form-control-sm globeCodeInput" placeholder="<?php echo $this->lang->line('Placeholder'); ?>">
                </td>
            </tr>
            <tr>
                <td style="height: 30px;" class="left-padding"><label for="minValue_<?php echo $this->paramPath; ?>"><?php echo $this->lang->line('META_00101'); ?></label></td>
                <td>
                    <input type="text" name="groupParam[<?php echo $this->paramPath; ?>][minValue]" value="<?php echo $this->paramRow['MIN_VALUE']; ?>" id="minValue_<?php echo $this->paramPath; ?>" class="form-control form-control-sm stringInit" placeholder="<?php echo $this->lang->line('META_00101'); ?>">
                </td>
            </tr>
            <tr>
                <td style="height: 30px;" class="left-padding"><label for="maxValue_<?php echo $this->paramPath; ?>"><?php echo $this->lang->line('META_00182'); ?></label></td>
                <td>
                    <input type="text" name="groupParam[<?php echo $this->paramPath; ?>][maxValue]" value="<?php echo $this->paramRow['MAX_VALUE']; ?>" id="maxValue_<?php echo $this->paramPath; ?>" class="form-control form-control-sm stringInit" placeholder="<?php echo $this->lang->line('META_00182'); ?>">
                </td>
            </tr>
            <tr>
                <td style="height: 30px;" class="left-padding"><label for="featureNum_<?php echo $this->paramPath; ?>"><?php echo $this->lang->line('META_00077'); ?></label></td>
                <td>
                    <input type="text" name="groupParam[<?php echo $this->paramPath; ?>][featureNum]" value="<?php echo $this->paramRow['FEATURE_NUM']; ?>" id="featureNum_<?php echo $this->paramPath; ?>" class="form-control form-control-sm stringInit" placeholder="<?php echo $this->lang->line('META_00077'); ?>">
                </td>
            </tr>
            <?php
            if ($this->dataType == 'bigdecimal') {
            ?>
            <tr>
                <td style="height: 30px;" class="left-padding"><label for="fractionRange_<?php echo $this->paramPath; ?>">Бутархай орон:</label></td>
                <td>
                    <input type="text" name="groupParam[<?php echo $this->paramPath; ?>][fractionRange]" value="<?php echo $this->paramRow['FRACTION_RANGE']; ?>" id="fractionRange_<?php echo $this->paramPath; ?>" class="form-control form-control-sm setFractionRangeInit" placeholder="Бутархай орон">
                </td>
            </tr>
            <?php
            }
            ?>
            <tr>
                <td style="height: 30px;" class="left-padding"><label for="isCountCard_<?php echo $this->paramPath; ?>">Is card:</label></td>
                <td class="pl5">
                    <?php
                    echo Form::checkbox(array('name' => 'groupParam['.$this->paramPath.'][isCountCard]', 'id' => 'isCountCard_'.$this->paramPath, 'value' => '1', 'saved_val' => $this->paramRow['IS_COUNTCARD'], 'class' => 'notuniform'));
                    ?>
                </td>
            </tr>
            <tr>
                <td style="height: 30px;" class="left-padding"><label for="countCardOrderNumber_<?php echo $this->paramPath; ?>">Card order number:</label></td>
                <td>
                    <input type="text" name="groupParam[<?php echo $this->paramPath; ?>][countCardOrderNumber]" value="<?php echo $this->paramRow['COUNTCARD_ORDER_NUMBER']; ?>" id="countCardOrderNumber_<?php echo $this->paramPath; ?>" class="form-control form-control-sm stringInit" placeholder="Card order number">
                </td>
            </tr>
            <tr>
                <td style="height: 30px;" class="left-padding"><label for="isUmCriteria_<?php echo $this->paramPath; ?>">Is um criteria:</label></td>
                <td class="pl5">
                    <?php
                    echo Form::checkbox(array('name' => 'groupParam['.$this->paramPath.'][isUmCriteria]', 'id' => 'isUmCriteria_'.$this->paramPath, 'value' => '1', 'saved_val' => $this->paramRow['IS_UM_CRITERIA'], 'class' => 'notuniform'));
                    ?>
                </td>
            </tr>
            <tr>
                <td style="height: 30px;" class="left-padding"><label for="isSidebar_<?php echo $this->paramPath; ?>">Is sidebar:</label></td>
                <td class="pl5">
                    <?php
                    echo Form::checkbox(array('name' => 'groupParam['.$this->paramPath.'][isSidebar]', 'id' => 'isSidebar_'.$this->paramPath, 'value' => '1', 'saved_val' => $this->paramRow['IS_SIDEBAR'], 'class' => 'notuniform'));
                    ?>
                </td>
            </tr>
            <tr>
                <td style="height: 30px;" class="left-padding"><label for="isCrypted_<?php echo $this->paramPath; ?>">Is crypted:</label></td>
                <td class="pl5">
                    <?php
                    echo Form::checkbox(array('name' => 'groupParam['.$this->paramPath.'][isCrypted]', 'id' => 'isCrypted_'.$this->paramPath, 'value' => '1', 'saved_val' => $this->paramRow['IS_CRYPTED'], 'class' => 'notuniform'));
                    ?>
                </td>
            </tr>
            <tr>
                <td style="height: 30px;" class="left-padding"><label for="isBasket_<?php echo $this->paramPath; ?>">Is basket:</label></td>
                <td class="pl5">
                    <?php
                    echo Form::checkbox(array('name' => 'groupParam['.$this->paramPath.'][isBasket]', 'id' => 'isBasket_'.$this->paramPath, 'value' => '1', 'saved_val' => $this->paramRow['IS_BASKET'], 'class' => 'notuniform'));
                    ?>
                </td>
            </tr>
            <tr>
                <td style="height: 30px;" class="left-padding"><label for="isBasketEdit_<?php echo $this->paramPath; ?>">Is basket edit:</label></td>
                <td class="pl5">
                    <?php
                    echo Form::checkbox(array('name' => 'groupParam['.$this->paramPath.'][isBasketEdit]', 'id' => 'isBasketEdit_'.$this->paramPath, 'value' => '1', 'saved_val' => $this->paramRow['IS_BASKET_EDIT'], 'class' => 'notuniform'));
                    ?>
                </td>
            </tr>
            <?php
            if ($this->depth == 0) {
            ?>
            <tr>
                <td style="height: 30px;" class="left-padding"><label for="isIgnoreExcel_<?php echo $this->paramPath; ?>">Is ignore excel:</label></td>
                <td class="pl5">
                    <?php
                    echo Form::checkbox(array('name' => 'groupParam['.$this->paramPath.'][isIgnoreExcel]', 'id' => 'isIgnoreExcel_'.$this->paramPath, 'value' => '1', 'saved_val' => $this->paramRow['IS_IGNORE_EXCEL'], 'class' => 'notuniform'));
                    ?>
                </td>
            </tr>
            <tr>
                <td style="height: 30px;" class="left-padding"><label for="excelColumnWidth_<?php echo $this->paramPath; ?>"><?php echo $this->lang->line('metadata_excel_width'); ?>:</label></td>
                <td>
                    <input type="text" name="groupParam[<?php echo $this->paramPath; ?>][excelColumnWidth]" value="<?php echo $this->paramRow['EXCEL_COLUMN_WIDTH']; ?>" id="excelColumnWidth_<?php echo $this->paramPath; ?>" class="form-control form-control-sm longInit" placeholder="<?php echo $this->lang->line('metadata_excel_width'); ?>">
                </td>
            </tr>
            <tr>
                <td style="height: 30px;" class="left-padding"><label for="excelRotate_<?php echo $this->paramPath; ?>">Excel rotate:</label></td>
                <td>
                    <input type="text" name="groupParam[<?php echo $this->paramPath; ?>][excelRotate]" value="<?php echo $this->paramRow['EXCEL_ROTATE']; ?>" id="excelRotate_<?php echo $this->paramPath; ?>" class="form-control form-control-sm" placeholder="Excel rotate">
                </td>
            </tr>
            <tr>
                <td style="height: 30px;" class="left-padding"><label for="countcardTheme_<?php echo $this->paramPath; ?>">Countcard theme:</label></td>
                <td>
                    <?php
                    echo Form::select(
                        array(
                            'name' => 'groupParam['.$this->paramPath.'][countcardTheme]',
                            'id' => 'countcardTheme_'.$this->paramPath,
                            'class' => 'form-control form-control-sm',
                            'data' => array(
                                array(
                                    'id' => 'wfmstatus',
                                    'name' => 'Wfm status'
                                ),
                                array(
                                    'id' => 'card',
                                    'name' => 'Card'
                                ),
                            ),
                            'op_value' => 'id',
                            'op_text' => 'name',
                            'value' => $this->paramRow['COUNTCARD_THEME']
                        )
                    );
                    ?>
                </td>
            </tr>
            <tr>
                <td style="height: 30px;" class="left-padding"><label for="countcardSelection_<?php echo $this->paramPath; ?>">Countcard default:</label></td>
                <td>
                    <input type="text" name="groupParam[<?php echo $this->paramPath; ?>][countcardSelection]" value="<?php echo $this->paramRow['COUNTCARD_SELECTION']; ?>" id="countcardSelection_<?php echo $this->paramPath; ?>" class="form-control form-control-sm" placeholder="Countcard default selection">
                </td>
            </tr>
            <tr>
                <td style="height: 30px;" class="left-padding"><label for="secondDisplayNumber_<?php echo $this->paramPath; ?>">Second display number:</label></td>
                <td>
                    <input type="text" name="groupParam[<?php echo $this->paramPath; ?>][secondDisplayNumber]" value="<?php echo $this->paramRow['SECOND_DISPLAY_ORDER']; ?>" id="secondDisplayNumber_<?php echo $this->paramPath; ?>" class="form-control form-control-sm stringInit" placeholder="Second display number">
                </td>
            </tr>
            <tr>
                <td style="height: 30px;" class="left-padding"><label for="logColumnName_<?php echo $this->paramPath; ?>">Log column name:</label></td>
                <td>
                    <input type="text" name="groupParam[<?php echo $this->paramPath; ?>][logColumnName]" value="<?php echo $this->paramRow['LOG_COLUMN_NAME']; ?>" id="logColumnName_<?php echo $this->paramPath; ?>" class="form-control form-control-sm stringInit" placeholder="Log column name">
                </td>
            </tr>
            <tr>
                <td style="height: 30px;" class="left-padding"><label for="isAdvancedCriteria_<?php echo $this->paramPath; ?>">Is advanced criteria:</label></td>
                <td class="pl5">
                    <?php
                    echo Form::checkbox(array('name' => 'groupParam['.$this->paramPath.'][isAdvanced]', 'id' => 'isAdvancedCriteria_'.$this->paramPath, 'value' => '1', 'saved_val' => $this->paramRow['IS_ADVANCED'], 'class' => 'notuniform'));
                    ?>
                </td>
            </tr>
            <tr>
                <td style="height: 30px;" class="left-padding"><label for="isKpiCriteria_<?php echo $this->paramPath; ?>">Is kpi criteria:</label></td>
                <td class="pl5">
                    <?php
                    echo Form::checkbox(array('name' => 'groupParam['.$this->paramPath.'][isKpiCriteria]', 'id' => 'isKpiCriteria_'.$this->paramPath, 'value' => '1', 'saved_val' => $this->paramRow['IS_KPI_CRITERIA'], 'class' => 'notuniform'));
                    ?>
                </td>
            </tr>      
            <tr>
                <td style="height: 30px;" class="left-padding"><label for="isFreeze_<?php echo $this->paramPath; ?>">Is freeze:</label></td>
                <td class="pl5">
                    <?php
                    echo Form::checkbox(array('name' => 'groupParam['.$this->paramPath.'][isFreeze]', 'id' => 'isFreeze_'.$this->paramPath, 'value' => '1', 'saved_val' => $this->paramRow['IS_FREEZE'], 'class' => 'notuniform'));
                    ?>
                </td>
            </tr>
            <tr>
                <td style="height: 30px;" class="left-padding"><label for="isPassFilter_<?php echo $this->paramPath; ?>">Is pass filter:</label></td>
                <td class="pl5">
                    <?php
                    echo Form::checkbox(array('name' => 'groupParam['.$this->paramPath.'][isPassFilter]', 'id' => 'isPassFilter_'.$this->paramPath, 'value' => '1', 'saved_val' => $this->paramRow['IS_PASS_FILTER'], 'class' => 'notuniform'));
                    ?>
                </td>
            </tr>
            <tr>
                <td style="height: 30px;" class="left-padding"><label for="isNotShowCriteria_<?php echo $this->paramPath; ?>">Is not show criteria:</label></td>
                <td class="pl5">
                    <?php
                    echo Form::checkbox(array('name' => 'groupParam['.$this->paramPath.'][isNotShowCriteria]', 'id' => 'isNotShowCriteria_'.$this->paramPath, 'value' => '1', 'saved_val' => $this->paramRow['IS_NOT_SHOW_CRITERIA'], 'class' => 'notuniform'));
                    ?>
                </td>
            </tr>
            <tr>
                <td style="height: 30px;" class="left-padding"><label for="defaultOperator_<?php echo $this->paramPath; ?>">Default operator:</label></td>
                <td>
                    <?php
                    echo Form::select(
                        array(
                            'name' => 'groupParam['.$this->paramPath.'][defaultOperator]',
                            'id' => 'defaultOperator_'.$this->paramPath,
                            'class' => 'form-control form-control-sm',
                            'data' => Info::defaultCriteriaCondition($this->dataType),
                            'op_value' => 'value',
                            'op_text' => 'code',
                            'value' => $this->paramRow['DEFAULT_OPERATOR']
                        )
                    );
                    ?>
                </td>
            </tr>
            <?php
            }
            ?>
            <tr>
                <td style="height: 30px;" class="left-padding"><label for="iconName_<?php echo $this->paramPath; ?>">Icon:</label></td>
                <td>
                    <button data-name="inputaddon-iconpicker" class="btn btn-secondary btn-sm" data-search-text="<?php echo $this->lang->line('META_00109'); ?>" data-placement="top" data-iconset="fontawesome5" data-cols="6" data-rows="6" data-icon="<?php echo $this->paramRow['ICON_NAME']; ?>" name="name" role="iconpicker"></button>
                    <input data-name="inputaddon-iconpicker" type="hidden" name="groupParam[<?php echo $this->paramPath; ?>][iconName]" value="<?php echo $this->paramRow['ICON_NAME']; ?>">
                </td>
            </tr>
            <tr>
                <td style="height: 30px;" class="left-padding">Анализ тэмдэглэл:</td>
                <td class="pl5">
                    <button type="button" class="btn btn-sm purple-plum" onclick="setGroupAnalysisCriteria(this);">...</button>
                    <?php
                    echo Form::hidden(
                        array(
                            'name' => 'groupParam['.$this->paramPath.'][validationCriteria]',
                            'id' => 'validationCriteria',
                            'value' => $this->paramRow['VALIDATION_CRITERIA']
                        )
                    );
                    echo Form::hidden(
                        array(
                            'name' => 'groupParam['.$this->paramPath.'][analysisDescription]',
                            'id' => 'analysisDescription',
                            'value' => $this->paramRow['ANALYSIS_DESCRIPTION']
                        )
                    );
                    echo Form::hidden(
                        array(
                            'name' => 'groupParam['.$this->paramPath.'][analysisExpression]',
                            'id' => 'analysisExpression',
                            'value' => $this->paramRow['ANALYSIS_EXPRESSION']
                        )
                    );
                    ?>
                </td>
            </tr>
            <tr>
                <td style="height: 30px;" class="left-padding">JSON config:</td>
                <td class="pl5">
                    <button type="button" class="btn btn-sm purple-plum" onclick="groupJsonConfig(this);">...</button>
                    <?php
                    echo Form::textArea(
                        array(
                            'class' => 'd-none', 
                            'name' => 'groupParam['.$this->paramPath.'][jsonConfig]',
                            'value' => $this->paramRow['JSON_CONFIG']
                        )
                    );
                    ?>
                </td>
            </tr>
        </tbody>
    </table>
</div>
