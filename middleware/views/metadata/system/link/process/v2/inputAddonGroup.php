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
                    <button type="button" class="btn btn-sm purple-plum" onclick="setProcessExpressionCriteriaGroup(this);">...</button>
                    <?php
                    echo Form::hidden(
                        array(
                            'name' => 'inputParam['.$this->paramPath.'][visibleCriteria]',
                            'id' => 'visibleCriteria',
                            'value' => $this->paramRow['VISIBLE_CRITERIA']
                        )
                    );
                    echo Form::hidden(
                        array(
                            'name' => 'inputParam['.$this->paramPath.'][isGroupAddon]',
                            'value' => 1
                        )
                    );
                    ?>
                    <div class="lookup-param-configs display-none">
                        <?php echo (new Mdmetadata())->getGroupParamConfig($this->processMetaDataId, $this->paramPath); ?>
                    </div>
                </td>
            </tr>
            <tr>
                <td style="height: 30px;" class="left-padding">Lookup /config/:</td>
                <td class="pl5">
                    <button type="button" class="btn btn-sm purple-plum" onclick="paramDefaultValuesLookup(this);" title="Утга тохируулах">...</button>
                    <button type="button" class="btn btn-sm red-sunglo mr0" onclick="groupLookupFieldsMapping(this);" title="Lookup Field тохируулах">...</button>
                    <div class="param-values-config display-none">
                        <?php echo (new Mdmetadata())->getParamDefaultValues($this->processMetaDataId, $this->paramPath, $this->paramRow['LOOKUP_META_DATA_ID']); ?>
                    </div>
                    <div class="param-lookup-field-config display-none"></div>
                </td>
            </tr>
            <tr>
                <td style="height: 30px;" class="left-padding">Lookup key:</td>
                <td class="pl5">
                    <div class="meta-autocomplete-wrap" data-params="autoSearch=1&grouptype=dataview&metaTypeId=<?php echo Mdmetadata::$metaGroupMetaTypeId; ?>">
                        <div class="input-group double-between-input">
                            <input id="lookupKeyMetaDataId" name="inputParam[<?php echo $this->paramPath; ?>][lookupKeyMetaDataId]" type="hidden" value="<?php echo $this->paramRow['LOOKUP_KEY_META_DATA_ID']; ?>">
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
            <tr>
                <td style="height: 30px;" class="left-padding">Lookup key /config/:</td>
                <td class="pl5">
                    <button type="button" class="btn btn-sm purple-plum" onclick="paramDefaultValuesLookup(this, 'key');" title="Утга тохируулах">...</button>
                    <button type="button" class="btn btn-sm red-sunglo mr0" onclick="groupLookupFieldsMapping(this, 'key');" title="Lookup Field тохируулах">...</button>
                    <div class="param-values-config-key display-none">
                        <?php echo (new Mdmetadata())->getParamDefaultValues($this->processMetaDataId, $this->paramPath, $this->paramRow['LOOKUP_KEY_META_DATA_ID'], true); ?>
                    </div>
                    <div class="param-lookup-field-config-key display-none"></div>
                </td>
            </tr>
            <tr>
                <td style="height: 30px;" class="left-padding"><label for="sidebarName_<?php echo $this->paramPath; ?>"><?php echo $this->lang->line('META_00122'); ?></label></td>
                <td>
                    <input type="text" name="inputParam[<?php echo $this->paramPath; ?>][sidebarName]" value="<?php echo $this->paramRow['SIDEBAR_NAME']; ?>" id="sidebarName_<?php echo $this->paramPath; ?>" class="form-control form-control-sm stringInit" placeholder="<?php echo $this->lang->line('META_00122'); ?>">
                </td>
            </tr>
            <tr>
                <td style="height: 30px;" class="left-padding"><label for="isSave_<?php echo $this->paramPath; ?>">Is save:</label></td>
                <td class="pl5">
                    <?php
                    echo Form::checkbox(array('name' => 'inputParam['.$this->paramPath.'][isSave]', 'id' => 'isSave_'.$this->paramPath, 'value' => '1', 'saved_val' => $this->paramRow['IS_SAVE'], 'class' => 'notuniform'));
                    ?>
                </td>
            </tr>
            <tr>
                <td style="height: 30px;" class="left-padding"><label for="isShowAdd_<?php echo $this->paramPath; ?>">Мөр нэмэх:</label></td>
                <td class="pl5">
                    <?php
                    echo Form::checkbox(array('name' => 'inputParam['.$this->paramPath.'][isShowAdd]', 'id' => 'isShowAdd_'.$this->paramPath, 'value' => '1', 'saved_val' => $this->paramRow['IS_SHOW_ADD'], 'class' => 'notuniform'));
                    ?>
                </td>
            </tr>
            <tr>
                <td style="height: 30px;" class="left-padding"><label for="isShowMultiple_<?php echo $this->paramPath; ?>">Олноор нэмэх:</label></td>
                <td class="pl5">
                    <?php
                    echo Form::checkbox(array('name' => 'inputParam['.$this->paramPath.'][isShowMultiple]', 'id' => 'isShowMultiple_'.$this->paramPath, 'value' => '1', 'saved_val' => $this->paramRow['IS_SHOW_MULTIPLE'], 'class' => 'notuniform'));
                    ?>
                </td>
            </tr>
            <tr>
                <td style="height: 30px;" class="left-padding"><label for="isShowDelete_<?php echo $this->paramPath; ?>"><?php echo $this->lang->line('META_00002'); ?></label></td>
                <td class="pl5">
                    <?php
                    echo Form::checkbox(array('name' => 'inputParam['.$this->paramPath.'][isShowDelete]', 'id' => 'isShowDelete_'.$this->paramPath, 'value' => '1', 'saved_val' => $this->paramRow['IS_SHOW_DELETE'], 'class' => 'notuniform'));
                    ?>
                </td>
            </tr>
            <tr>
                <td style="height: 30px;" class="left-padding"><label for="isButton_<?php echo $this->paramPath; ?>">Is button:</label></td>
                <td class="pl5">
                    <?php
                    echo Form::checkbox(array('name' => 'inputParam['.$this->paramPath.'][isButton]', 'id' => 'isButton_'.$this->paramPath, 'value' => '1', 'saved_val' => $this->paramRow['IS_BUTTON'], 'class' => 'notuniform'));
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
                <td style="height: 30px;" class="left-padding"><label for="isFirstRow_<?php echo $this->paramPath; ?>">Default мөр:</label></td>
                <td class="pl5">
                    <?php
                    echo Form::checkbox(array('name' => 'inputParam['.$this->paramPath.'][isFirstRow]', 'id' => 'isFirstRow_'.$this->paramPath, 'value' => '1', 'saved_val' => $this->paramRow['IS_FIRST_ROW'], 'class' => 'notuniform'));
                    ?>
                </td>
            </tr>
            <?php
            if ($this->depth == 1 && $this->dataType == 'row') {
            ?>
            <tr>
                <td style="height: 30px;" class="left-padding"><label for="isPathDisplayOrder_<?php echo $this->paramPath; ?>">Is path display order:</label></td>
                <td class="pl5">
                    <?php
                    echo Form::checkbox(array('name' => 'inputParam['.$this->paramPath.'][isPathDisplayOrder]', 'id' => 'isPathDisplayOrder_'.$this->paramPath, 'value' => '1', 'saved_val' => $this->paramRow['IS_PATH_DISPLAY_ORDER'], 'class' => 'notuniform'));
                    ?>
                </td>
            </tr>
            <?php
            }
            if (true/*$this->depth == 0*/) {
            ?>
            <tr>
                <td style="height: 30px;" class="left-padding"><label for="dtlTheme_<?php echo $this->paramPath; ?>">Widget:</label></td>
                <td>
                    <div class="input-group">
                        <?php
                        echo Form::select(
                            array(
                                'name' => 'inputParam['.$this->paramPath.'][dtlTheme]',
                                'id' => 'dtlTheme_'.$this->paramPath,
                                'class' => 'form-control form-control-sm select2',
                                'data' => $this->widgetData,
                                'op_value' => 'ID',
                                'op_text' => 'NAME',
                                'op_custom_attr' => array(
                                    array(
                                        'attr' => 'data-preview-webimage', 
                                        'key' => 'PREVIEW_WEBIMAGE'
                                    ), 
                                    array(
                                        'attr' => 'data-preview-mobileimage', 
                                        'key' => 'PREVIEW_MOBILEIMAGE'
                                    )
                                ), 
                                'value' => $this->paramRow['DTL_THEME']
                            )
                        );
                        ?>
                        <span class="input-group-append">
                            <button type="button" class="btn purple-plum pl10 pr10" title="Widget харах" onclick="viewBpDetailWidget(this);"><i class="icon-design"></i></button>
                        </span>
                    </div>
                </td>
            </tr>
            <?php
            }
            if ($this->depth > 0) {
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
            <?php
            }
            if ($this->depth == 0 && $this->dataType == 'rows') {
            ?>
            <tr>
                <td style="height: 30px;" class="left-padding"><label for="isExcelExport_<?php echo $this->paramPath; ?>">Excel татах:</label></td>
                <td class="pl5">
                    <?php
                    echo Form::checkbox(array('name' => 'inputParam['.$this->paramPath.'][isExcelExport]', 'id' => 'isExcelExport_'.$this->paramPath, 'value' => '1', 'saved_val' => $this->paramRow['IS_EXCEL_EXPORT'], 'class' => 'notuniform'));
                    ?>
                </td>
            </tr>
            <tr>
                <td style="height: 30px;" class="left-padding"><label for="isExcelImport_<?php echo $this->paramPath; ?>">Excel импорт:</label></td>
                <td class="pl5">
                    <?php
                    echo Form::checkbox(array('name' => 'inputParam['.$this->paramPath.'][isExcelImport]', 'id' => 'isExcelImport_'.$this->paramPath, 'value' => '1', 'saved_val' => $this->paramRow['IS_EXCEL_IMPORT'], 'class' => 'notuniform'));
                    ?>
                </td>
            </tr>
            <tr>
                <td style="height: 30px;" class="left-padding"><label for="pagingConfig_<?php echo $this->paramPath; ?>">Paging config:</label></td>
                <td>
                    <input type="text" name="inputParam[<?php echo $this->paramPath; ?>][pagingConfig]" value="<?php echo $this->paramRow['PAGING_CONFIG']; ?>" id="pagingConfig_<?php echo $this->paramPath; ?>" class="form-control form-control-sm stringInit" placeholder="Paging config">
                </td>
            </tr>
            <tr>
                <td style="height: 30px;" class="left-padding"><label for="detailModifyMode_<?php echo $this->paramPath; ?>">Modify mode:</label></td>
                <td>
                    <?php
                    echo Form::select(
                        array(
                            'name' => 'inputParam['.$this->paramPath.'][detailModifyMode]',
                            'id' => 'detailModifyMode_'.$this->paramPath,
                            'class' => 'form-control form-control-sm',
                            'data' => array(
                                array(
                                    'id' => 'sidebar',
                                    'name' => 'Sidebar'
                                )
                            ),
                            'op_value' => 'id',
                            'op_text' => 'name',
                            'value' => $this->paramRow['DETAIL_MODIFY_MODE']
                        )
                    );
                    ?>
                </td>
            </tr>
            <?php
            }
            ?>
            <tr>
                <td style="height: 30px;" class="left-padding"><label for="rowColumnCount_<?php echo $this->paramPath; ?>"><?php echo $this->lang->line('META_00117'); ?></label></td>
                <td>
                    <input type="text" name="inputParam[<?php echo $this->paramPath; ?>][columnCount]" value="<?php echo $this->paramRow['COLUMN_COUNT']; ?>" id="rowColumnCount_<?php echo $this->paramPath; ?>" class="form-control form-control-sm longInit" placeholder="<?php echo $this->lang->line('META_00117'); ?>">
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
            if ($this->dataType != 'rows') {
            ?>            
            <tr>
                <td style="height: 30px;" class="left-padding"><label for="rowColumnWidth_<?php echo $this->paramPath; ?>"><?php echo $this->lang->line('META_00048'); ?></label></td>
                <td>
                    <input type="text" name="inputParam[<?php echo $this->paramPath; ?>][columnWidth]" value="<?php echo $this->paramRow['COLUMN_WIDTH']; ?>" id="rowColumnWidth_<?php echo $this->paramPath; ?>" class="form-control form-control-sm numberInit" placeholder="<?php echo $this->lang->line('META_00048'); ?>">
                </td>
            </tr>     
            <?php
            }
            if ($this->dataType == 'rows') {
            ?>
            <tr>
                <td style="height: 30px;" class="left-padding"><label for="groupingName_<?php echo $this->paramPath; ?>">Autocomplete тайлбар:</label></td>
                <td>
                    <input type="text" name="inputParam[<?php echo $this->paramPath; ?>][groupingName]" value="<?php echo $this->paramRow['GROUPING_NAME']; ?>" id="groupingName_<?php echo $this->paramPath; ?>" class="form-control form-control-sm stringInit globeCodeInput" placeholder="Autocomplete тайлбар">
                </td>
            </tr>
            <?php
            }
            ?>
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
