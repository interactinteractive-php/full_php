<div class="panel panel-default bg-inverse mb0">
    <table class="table sheetTable" style="table-layout: fixed">
        <tbody>
            <tr>
                <td style="width: 118px; height: 30px;" class="left-padding">Path:</td>
                <td class="pl5" style="word-wrap: break-word;"><?php echo $this->paramPath; ?></td>
            </tr>    
            <tr>
                <td style="height: 30px;" class="left-padding">Exp/Criteria:</td>
                <td class="pl5">
                    <button type="button" class="btn btn-sm purple-plum" onclick="setProcessExpressionCriteria(this);">...</button>
                    <?php
                    echo Form::hidden(
                        array(
                            'name' => 'inputParam['.$this->paramPath.'][expressionString]',
                            'id' => 'expressionString',
                            'value' => $this->paramRow['EXPRESSION_STRING']
                        )
                    );
                    echo Form::hidden(
                        array(
                            'name' => 'inputParam['.$this->paramPath.'][valueCriteria]',
                            'id' => 'valueCriteria',
                            'value' => $this->paramRow['VALUE_CRITERIA']
                        )
                    );
                    echo Form::hidden(
                        array(
                            'name' => 'inputParam['.$this->paramPath.'][processMetaDataId]',
                            'id' => 'processMetaDataId',
                            'value' => $this->paramRow['GET_PROCESS_META_DATA_ID']
                        )
                    );
                    echo Form::hidden(
                        array(
                            'name' => 'inputParam['.$this->paramPath.'][processGetParamPath]',
                            'id' => 'processGetParamPath',
                            'value' => $this->paramRow['PROCESS_GET_PARAM_PATH']
                        )
                    );
                    ?>
                    <div class="lookup-param-configs display-none">
                        <?php echo (new Mdmetadata())->getGroupParamConfig($this->processMetaDataId, $this->paramPath); ?>
                    </div>
                    <div class="process-param-configs display-none">
                        <?php echo (new Mdmetadata())->getGroupProcessParamConfig($this->processMetaDataId, $this->paramPath); ?>
                    </div>
                </td>
            </tr>
            <tr>
                <td style="height: 30px;" class="left-padding">Lookup /тогтмол утга/:</td>
                <td class="pl5">
                    <button type="button" class="btn btn-sm purple-plum" onclick="paramDefaultValuesLookup(this);" title="Утга тохируулах">...</button>
                    <div class="param-values-config display-none">
                        <?php echo (new Mdmetadata())->getParamDefaultValues($this->processMetaDataId, $this->paramPath, $this->paramRow['LOOKUP_META_DATA_ID']); ?>
                    </div>
                </td>
            </tr>    
            <tr>
                <td style="height: 30px;" class="left-padding"><label for="columnWidth_<?php echo $this->paramPath; ?>"><?php echo $this->lang->line('META_00048'); ?> <i class="fa fa-info-circle" title="<?php echo $this->lang->line('META_00123'); ?>"></i></label></td>
                <td>
                    <input type="text" name="inputParam[<?php echo $this->paramPath; ?>][columnWidth]" value="<?php echo $this->paramRow['COLUMN_WIDTH']; ?>" id="columnWidth_<?php echo $this->paramPath; ?>" class="form-control form-control-sm" placeholder="<?php echo $this->lang->line('META_00048'); ?>">
                </td>
            </tr>
            <tr>
                <td style="height: 30px;" class="left-padding"><label for="minValue_<?php echo $this->paramPath; ?>"><?php echo $this->lang->line('META_00101'); ?></label></td>
                <td>
                    <input type="text" name="inputParam[<?php echo $this->paramPath; ?>][minValue]" value="<?php echo $this->paramRow['MIN_VALUE']; ?>" id="minValue_<?php echo $this->paramPath; ?>" class="form-control form-control-sm stringInit" placeholder="<?php echo $this->lang->line('META_00101'); ?>">
                </td>
            </tr>
            <tr>
                <td style="height: 30px;" class="left-padding"><label for="maxValue_<?php echo $this->paramPath; ?>"><?php echo $this->lang->line('META_00182'); ?></label></td>
                <td>
                    <input type="text" name="inputParam[<?php echo $this->paramPath; ?>][maxValue]" value="<?php echo $this->paramRow['MAX_VALUE']; ?>" id="maxValue_<?php echo $this->paramPath; ?>" class="form-control form-control-sm stringInit" placeholder="<?php echo $this->lang->line('META_00182'); ?>">
                </td>
            </tr>
            <tr>
                <td style="height: 30px;" class="left-padding"><label for="sidebarName_<?php echo $this->paramPath; ?>"><?php echo $this->lang->line('META_00122'); ?></label></td>
                <td>
                    <input type="text" name="inputParam[<?php echo $this->paramPath; ?>][sidebarName]" value="<?php echo $this->paramRow['SIDEBAR_NAME']; ?>" id="sidebarName_<?php echo $this->paramPath; ?>" class="form-control form-control-sm stringInit" placeholder="<?php echo $this->lang->line('META_00122'); ?>">
                </td>
            </tr>
            <tr>
                <td style="height: 30px;" class="left-padding"><label for="placeholderName_<?php echo $this->paramPath; ?>"><?php echo $this->lang->line('Placeholder'); ?></label></td>
                <td>
                    <input type="text" name="inputParam[<?php echo $this->paramPath; ?>][placeholderName]" value="<?php echo $this->paramRow['PLACEHOLDER_NAME']; ?>" id="placeholderName_<?php echo $this->paramPath; ?>" class="form-control form-control-sm globeCodeInput" placeholder="<?php echo $this->lang->line('Placeholder'); ?>">
                </td>
            </tr>
            <tr>
                <td style="height: 30px;" class="left-padding"><label for="separatorType_<?php echo $this->paramPath; ?>"><?php echo $this->lang->line('META_00030'); ?></label></td>
                <td>
                    <?php
                    echo Form::select(
                        array(
                            'name' => 'inputParam['.$this->paramPath.'][separatorType]',
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
                                ), 
                                array(
                                    'id' => 'stepline',
                                    'name' => 'StepLine'
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
                            'name' => 'inputParam['.$this->paramPath.'][patternId]',
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
                <td style="height: 30px;" class="left-padding"><label for="columnAggregate_<?php echo $this->paramPath; ?>"><?php echo $this->lang->line('META_00124'); ?></label></td>
                <td>
                    <?php
                    echo Form::select(
                        array(
                            'name' => 'inputParam['.$this->paramPath.'][columnAggregate]',
                            'id' => 'columnAggregate_'.$this->paramPath,
                            'class' => 'form-control form-control-sm',
                            'data' => array(
                                array(
                                    'code' => 'sum', 
                                    'name' => $this->lang->line('META_00031')
                                ), 
                                array(
                                    'code' => 'avg', 
                                    'name' => $this->lang->line('META_00157')
                                ),
                                array(
                                    'code' => 'min', 
                                    'name' => $this->lang->line('META_00078')
                                ),
                                array(
                                    'code' => 'max', 
                                    'name' => $this->lang->line('META_00184')
                                )
                            ), 
                            'op_value' => 'code', 
                            'op_text' => 'name',
                            'value' => $this->paramRow['COLUMN_AGGREGATE']
                        )
                    );
                    ?>
                </td>
            </tr>
            <tr>
                <td style="height: 30px;" class="left-padding"><label for="isRefresh_<?php echo $this->paramPath; ?>"><?php echo $this->lang->line('META_00006'); ?></label></td>
                <td class="pl5">
                    <?php
                    echo Form::checkbox(array('name' => 'inputParam['.$this->paramPath.'][isRefresh]', 'id' => 'isRefresh_'.$this->paramPath, 'value' => '1', 'saved_val' => $this->paramRow['IS_REFRESH'], 'class' => 'notuniform'));
                    ?>
                </td>
            </tr>
            <tr>
                <td style="height: 30px;" class="left-padding"><label for="isThumbnail_<?php echo $this->paramPath; ?>">Is thumbnail</label></td>
                <td class="pl5">
                    <?php
                    echo Form::checkbox(array('name' => 'inputParam['.$this->paramPath.'][isThumbnail]', 'id' => 'isThumbnail_'.$this->paramPath, 'value' => '1', 'saved_val' => $this->paramRow['IS_THUMBNAIL'], 'class' => 'notuniform'));
                    ?>
                </td>
            </tr>
            <?php
            if ($this->depth != 0) {
            ?>
            <tr>
                <td style="height: 30px;" class="left-padding"><label for="isUserConfig_<?php echo $this->paramPath; ?>">User config:</label></td>
                <td class="pl5">
                    <?php
                    echo Form::checkbox(array('name' => 'inputParam['.$this->paramPath.'][isUserConfig]', 'id' => 'isUserConfig_'.$this->paramPath, 'value' => '1', 'saved_val' => $this->paramRow['IS_USER_CONFIG'], 'class' => 'notuniform'));
                    ?>
                </td>
            </tr>
            <?php
            }
            if ($this->dataType == 'bigdecimal') {
            ?>
            <tr>
                <td style="height: 30px;" class="left-padding"><label for="fractionRange_<?php echo $this->paramPath; ?>">Бутархай орон:</label></td>
                <td>
                    <input type="text" name="inputParam[<?php echo $this->paramPath; ?>][fractionRange]" value="<?php echo $this->paramRow['FRACTION_RANGE']; ?>" id="fractionRange_<?php echo $this->paramPath; ?>" class="form-control form-control-sm setFractionRangeInit" placeholder="Бутархай орон">
                </td>
            </tr>
            <?php
            }
            ?>
            <tr>
                <td style="height: 30px;" class="left-padding"><label for="groupingName_<?php echo $this->paramPath; ?>"><?php echo $this->lang->line('META_00159'); ?></label></td>
                <td>
                    <input type="text" name="inputParam[<?php echo $this->paramPath; ?>][groupingName]" value="<?php echo $this->paramRow['GROUPING_NAME']; ?>" id="groupingName_<?php echo $this->paramPath; ?>" class="form-control form-control-sm stringInit" placeholder="<?php echo $this->lang->line('META_00159'); ?>">
                </td>
            </tr>
            <tr>
                <td style="height: 30px;" class="left-padding"><label for="featureNum_<?php echo $this->paramPath; ?>"><?php echo $this->lang->line('META_00077'); ?></label></td>
                <td>
                    <input type="text" name="inputParam[<?php echo $this->paramPath; ?>][featureNum]" value="<?php echo $this->paramRow['FEATURE_NUM']; ?>" id="featureNum_<?php echo $this->paramPath; ?>" class="form-control form-control-sm stringInit" placeholder="<?php echo $this->lang->line('META_00077'); ?>">
                </td>
            </tr>
            <?php
            if ($this->dataType == 'file' || $this->dataType == 'base64' || $this->dataType == 'multi_file' || $this->dataType == 'multi_file_styleInit') {
            ?>
            <tr>
                <td style="height: 30px;" class="left-padding">Файлын өргөтгөл:</td>
                <td>
                    <input type="text" name="inputParam[<?php echo $this->paramPath; ?>][fileExtension]" value="<?php echo $this->paramRow['FILE_EXTENSION']; ?>" class="form-control form-control-sm stringInit" placeholder="Файлын өргөтгөл">
                </td>
            </tr>
            <?php
            }
            ?>
            <tr>
                <td style="height: 30px;" class="left-padding"><label for="themePosition_<?php echo $this->paramPath; ?>">Widget position:</label></td>
                <td>
                    <?php
                    echo Form::select(
                        array(
                            'name' => 'inputParam['.$this->paramPath.'][themePosition]',
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
                            'name' => 'inputParam['.$this->paramPath.'][renderType]',
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
                <td style="height: 30px;" class="left-padding">Icon:</td>
                <td>
                    <?php echo Form::hidden(array('name' => 'inputParam[' . $this->paramPath . '][iconName]', 'value' => $this->paramRow['ICON_NAME'])); ?>
                    <button type="button" class="btn btn-secondary btn-sm process-field-icon" data-search-text="<?php echo $this->lang->line('META_00109'); ?>" data-placement="top" data-iconset="fontawesome5" data-cols="5" data-icon="<?php echo $this->paramRow['ICON_NAME']; ?>" role="iconpicker"></button>
                </td>
            </tr>
            <?php
            if ($this->lookupType == 'combo_with_popup') {
            ?>
            <tr data-addonlookupmetadata="1">
                <td style="height: 30px;" class="left-padding">Нэмэлт lookup:</td>
                <td>
                    <div class="meta-autocomplete-wrap" data-params="autoSearch=1&grouptype=dataview&metaTypeId=<?php echo Mdmetadata::$metaGroupMetaTypeId; ?>">
                        <div class="input-group double-between-input">
                            <input id="lookupMetaDataId" name="inputParam[<?php echo $this->paramPath; ?>][addonLookupMetaDataId]" type="hidden" value="<?php echo $this->paramRow['LOOKUP_KEY_META_DATA_ID']; ?>">
                            <input id="_displayField" class="form-control form-control-sm md-code-autocomplete" value="<?php echo $this->paramRow['LOOKUP_KEY_META_DATA_CODE']; ?>" title="<?php echo $this->paramRow['LOOKUP_KEY_META_DATA_CODE']; ?>" placeholder="<?php echo $this->lang->line('META_00068'); ?>" type="text">
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
                                <input id="_nameField" class="form-control form-control-sm md-name-autocomplete" value="<?php echo $this->paramRow['LOOKUP_KEY_META_DATA_NAME']; ?>" title="<?php echo $this->paramRow['LOOKUP_KEY_META_DATA_NAME']; ?>" placeholder="<?php echo $this->lang->line('META_00099'); ?>" type="text">      
                            </span>     
                        </div>
                    </div>
                </td>
            </tr>
            <?php
            }
            ?>
            <tr>
                <td style="height: 30px;" class="left-padding"><label for="autoNumber_<?php echo $this->paramPath; ?>">Auto number</label></td>
                <td class="pl5">
                    <?php
                    echo Form::checkbox(array('name' => 'inputParam['.$this->paramPath.'][autoNumber]', 'id' => 'autoNumber_'.$this->paramPath, 'value' => '1', 'saved_val' => $this->paramRow['AUTO_NUMBER'], 'class' => 'notuniform autonumber'));
                    ?>
                </td>
            </tr>            
            <tr class="autoNumber hidden">
                <td style="height: 30px;" class="left-padding"><label for="autoNumberCodeFormat_<?php echo $this->paramPath; ?>">Code format</label></td>
                <td class="pl5">
                    <?php
                    echo Form::text(array('name' => 'inputParam['.$this->paramPath.'][autoNumberCodeFormat]', 'id' => 'autoNumberCodeFormat_'.$this->paramPath, 'value' => $this->paramRow['CODE_FORMAT'], 'class' => 'form-control form-control-sm stringInit'));
                    ?>
                </td>
            </tr>            
            <tr class="autoNumber hidden">
                <td style="height: 30px;" class="left-padding"><label for="autoNumberSequenceFormat_<?php echo $this->paramPath; ?>">Sequence format</label></td>
                <td class="pl5">
                    <?php
                    echo Form::text(array('name' => 'inputParam['.$this->paramPath.'][autoNumberSequenceFormat]', 'id' => 'autoNumberSequenceFormat_'.$this->paramPath, 'value' => $this->paramRow['SEQUENCE_FORMAT'], 'class' => 'form-control form-control-sm stringInit'));
                    ?>
                </td>
            </tr>            
            <tr class="autoNumber hidden">
                <td style="height: 30px;" class="left-padding"><label for="autoNumberIsUnique_<?php echo $this->paramPath; ?>">Is unique</label></td>
                <td class="pl5">
                    <?php
                    echo Form::checkbox(array('name' => 'inputParam['.$this->paramPath.'][autoNumberIsUnique]', 'id' => 'autoNumberIsUnique_'.$this->paramPath, 'value' => '1', 'saved_val' => $this->paramRow['AUTO_IS_UNIQUE'], 'class' => 'notuniform'));
                    ?>
                </td>
            </tr>      
            <tr>
                <td style="height: 30px;" class="left-padding"><label for="offlineOrder_<?php echo $this->paramPath; ?>">Mobile offline order</label></td>
                <td>
                    <input type="text" name="inputParam[<?php echo $this->paramPath; ?>][offlineOrder]" value="<?php echo $this->paramRow['OFFLINE_ORDER']; ?>" id="offlineOrder_<?php echo $this->paramPath; ?>" class="form-control form-control-sm longInit" placeholder="Mobile offline order">
                </td>
            </tr>    
            <tr>
                <td style="height: 30px;" class="left-padding"><label for="tabIndex_<?php echo $this->paramPath; ?>">Tab index</label></td>
                <td>
                    <input type="text" name="inputParam[<?php echo $this->paramPath; ?>][tabIndex]" value="<?php echo $this->paramRow['TAB_INDEX']; ?>" id="tabIndex_<?php echo $this->paramPath; ?>" class="form-control form-control-sm longInit" placeholder="tab Index">
                </td>
            </tr>
            <?php
            if ($this->depth == 0) {
            ?>
            <tr>
                <td style="height: 30px;" class="left-padding"><label for="wizardStep_<?php echo $this->paramPath; ?>">Wizard step</label></td>
                <td>
                    <input type="text" name="inputParam[<?php echo $this->paramPath; ?>][wizardStep]" value="<?php echo $this->paramRow['WIZARD_STEP']; ?>" id="wizardStep_<?php echo $this->paramPath; ?>" class="form-control form-control-sm longInit" placeholder="Wizard step">
                </td>
            </tr>
            <?php
            }
            ?>
            <tr>
                <td style="height: 30px;" class="left-padding">More meta:</td>
                <td class="pl5">
                    <div class="meta-autocomplete-wrap" data-params="autoSearch=1&grouptype=dataview&metaTypeId=<?php echo Mdmetadata::$metaGroupMetaTypeId; ?>">
                        <div class="input-group double-between-input">
                            <input id="moreMetaDataId" name="inputParam[<?php echo $this->paramPath; ?>][moreMetaDataId]" type="hidden" value="<?php echo $this->paramRow['MORE_META_DATA_ID']; ?>">
                            <input id="_displayField" class="form-control form-control-sm md-code-autocomplete" value="<?php echo $this->paramRow['MORE_META_DATA_CODE']; ?>" title="<?php echo $this->paramRow['MORE_META_DATA_CODE']; ?>" placeholder="<?php echo $this->lang->line('META_00068'); ?>" type="text">
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
                                <input id="_nameField" class="form-control form-control-sm md-name-autocomplete" value="<?php echo $this->paramRow['MORE_META_DATA_NAME']; ?>" title="<?php echo $this->paramRow['MORE_META_DATA_NAME']; ?>" placeholder="<?php echo $this->lang->line('META_00099'); ?>" type="text">      
                            </span>     
                        </div>
                    </div>
                </td>
            </tr>
            <?php
            if ($this->depth == 1) {
            ?>
            <tr>
                <td style="height: 30px;" class="left-padding"><label for="dtlButtonName_<?php echo $this->paramPath; ?>"><?php echo $this->lang->line('pf_detail_button_name'); ?></label></td>
                <td>
                    <input type="text" name="inputParam[<?php echo $this->paramPath; ?>][dtlButtonName]" value="<?php echo $this->paramRow['DTL_BUTTON_NAME']; ?>" id="dtlButtonName_<?php echo $this->paramPath; ?>" class="form-control form-control-sm globeCodeInput" placeholder="<?php echo $this->lang->line('pf_detail_button_name'); ?>">
                </td>
            </tr>    
            <tr>
                <td style="height: 30px;" class="left-padding"><label for="groupingName_<?php echo $this->paramPath; ?>">Баганыг нэгтгэх нэр:</label></td>
                <td>
                    <input type="text" name="inputParam[<?php echo $this->paramPath; ?>][groupingName]" value="<?php echo $this->paramRow['GROUPING_NAME']; ?>" id="groupingName_<?php echo $this->paramPath; ?>" class="form-control form-control-sm stringInit globeCodeInput" placeholder="Баганыг нэгтгэх нэр">
                </td>
            </tr>
            <?php
            }
            ?>
            <tr>
                <td style="height: 30px;" class="left-padding"><label for="isFreeze_<?php echo $this->paramPath; ?>"><?php echo $this->lang->line('META_00158'); ?></label></td>
                <td class="pl5">
                    <?php
                    echo Form::checkbox(array('name' => 'inputParam['.$this->paramPath.'][isFreeze]', 'id' => 'isFreeze_'.$this->paramPath, 'value' => '1', 'saved_val' => $this->paramRow['IS_FREEZE'], 'class' => 'notuniform'));
                    ?>
                </td>
            </tr>
            <tr>
                <td style="height: 30px;" class="left-padding">JSON config:</td>
                <td class="pl5">
                    <button type="button" class="btn btn-sm purple-plum" onclick="paramJsonConfig(this);">...</button>
                    <?php
                    echo Form::textArea(
                        array(
                            'class' => 'd-none', 
                            'name' => 'inputParam['.$this->paramPath.'][jsonConfig]',
                            'value' => $this->paramRow['JSON_CONFIG']
                        )
                    );
                    ?>
                </td>
            </tr>
        </tbody>
    </table>
</div>
