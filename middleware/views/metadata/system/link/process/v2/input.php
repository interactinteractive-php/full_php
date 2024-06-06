<?php
if ($this->params) {
    
    foreach ($this->params as $row) {
        
        $paramPath = $row['PARAM_REAL_PATH'];
        
        $lookupType = $chooseType = $tabName = $defaultValue = $displayField = 
        $valueField = $rowId = $rowClass = $rowExpand = $rowMetaDataId = '';
        $rowType = 'field';
        
        $lookupMetaDataId = '
        <div class="meta-autocomplete-wrap" data-params="autoSearch=1&grouptype=dataview&metaTypeId='.Mdmetadata::$metaGroupMetaTypeId.'">
            <div class="input-group double-between-input">
                <input id="lookupMetaDataId" name="inputParam['.$paramPath.'][lookupMetaDataId]" type="hidden" value="'.$row['LOOKUP_META_DATA_ID'].'">
                <input id="_displayField" class="form-control form-control-sm md-code-autocomplete" value="'.$row['LOOKUP_META_DATA_CODE'].'" title="'.$row['LOOKUP_META_DATA_CODE'].'" placeholder="'.$this->lang->line('META_00068').'" type="text">
                <span class="input-group-btn">
                    <button type="button" class="btn default btn-bordered form-control-sm mr0" onclick="commonMetaDataSelectableGrid(\'single\', \'\', this);"><i class="fa fa-search"></i></button>
                </span>     
                <span class="input-group-btn not-group-btn">
                    <div class="btn-group pf-meta-manage-dropdown">
                        <button class="btn grey-cascade btn-bordered form-control-sm mr0 dropdown-toggle" type="button" data-toggle="dropdown"></button>
                        <ul class="dropdown-menu dropdown-menu-right" style="min-width: 126px;" role="menu"></ul>
                    </div>
                </span>  
                <span class="input-group-btn flex-col-group-btn">
                    <input id="_nameField" class="form-control form-control-sm md-name-autocomplete" value="'.$row['LOOKUP_META_DATA_NAME'].'" title="'.$row['LOOKUP_META_DATA_NAME'].'" placeholder="'.$this->lang->line('META_00099').'" type="text">      
                </span>     
            </div>
        </div>';
        
        if ($row['RECORD_TYPE'] != '') {
            
            $rowId = ' data-id="'.$row['ID'].'"';
            $rowType = 'group';
            
            $rowClass = ($this->depth == 0) ? ' class="tabletree-'.$row['ID'].'"' : ' class="tabletree-'.$row['ID'].' tabletree-parent-'.$this->rowId.'"';
            $rowExpand = '<span class="tabletree-expander fa fa-plus"></span>';
            
            $dataType = str_replace('value="'.$row['RECORD_TYPE'].'"', 'value="'.$row['RECORD_TYPE'].'" selected="selected"', $this->fieldDataTypeOptions);
            
            if (isset($row['META_DATA_ID'])) {
                $rowMetaDataId = '<input type="hidden" name="inputParam['.$paramPath.'][metaDataId]" value="'.$row['META_DATA_ID'].'">';
                $rowId .= ' data-metadataid="'.$row['META_DATA_ID'].'"';
            }
            
        } else {
            
            if ($this->depth != 0) {
                $rowClass = ' class="tabletree-'.$row['ID'].' tabletree-parent-'.$this->rowId.'"';
            }
            
            $lookupType = '<select name="inputParam['.$paramPath.'][lookupType]" class="form-control form-control-sm bp-input-field-lookuptype">
                <option value="">---</option>
                <option value="combo">Combo</option>
                <option value="combogrid">Combo Grid</option>
                <option value="popup">Popup</option>
                <option value="radio">Radio</option>
                <option value="checkbox">Checkbox</option>
                <option value="star">Star</option>
                <option value="label">Label</option>
                <option value="icon">Icon</option>
                <option value="autocomplete_text">Autocomplete text</option>
                <option value="combo_with_popup">Combo with popup</option>
                <option value="autocomplete_mention">Mention</option>
                <option value="range_slider">Range slider</option>
                <option value="button">Button</option>
            </select>';
            
            $lookupType = str_replace('value="'.$row['LOOKUP_TYPE'].'"', 'value="'.$row['LOOKUP_TYPE'].'" selected="selected"', $lookupType);
            
            $chooseType = '<select name="inputParam['.$paramPath.'][chooseType]" class="form-control form-control-sm">
                <option value="">---</option>
                <option value="single">Single</option>
                <option value="multi">Multi</option>
                <option value="multicomma">MultiComma</option>
            </select>';
            
            $chooseType = str_replace('value="'.$row['CHOOSE_TYPE'].'"', 'value="'.$row['CHOOSE_TYPE'].'" selected="selected"', $chooseType);
            
            if ($row['LOOKUP_META_DATA_ID'] != '') {
                
                $lookupData = (new Mdmetadata())->getObjectFieldName($row['LOOKUP_META_DATA_ID']);

                $displayField = Form::select(
                    array(
                        'name' => 'inputParam['.$paramPath.'][displayField]',
                        'id' => 'displayField',
                        'class' => 'form-control form-control-sm paramDisplayField',
                        'style' => 'width: 160px',
                        'data' => $lookupData,
                        'op_value' => 'FIELD_NAME',
                        'op_text' => 'FIELD_NAME',
                        'value' => $row['DISPLAY_FIELD']
                    )
                );

                $valueField = Form::select(
                    array(
                        'name' => 'inputParam['.$paramPath.'][valueField]',
                        'id' => 'valueField',
                        'class' => 'form-control form-control-sm paramValueField',
                        'style' => 'width: 160px',
                        'data' => $lookupData,
                        'op_value' => 'FIELD_NAME',
                        'op_text' => 'FIELD_NAME',
                        'value' => $row['VALUE_FIELD']
                    )
                );

            } else {

                $displayField = Form::select(
                    array(
                        'name' => 'inputParam['.$paramPath.'][displayField]',
                        'id' => 'displayField',
                        'class' => 'form-control form-control-sm paramDisplayField',
                        'style' => 'width: 160px'
                    )
                );

                $valueField = Form::select(
                    array(
                        'name' => 'inputParam['.$paramPath.'][valueField]',
                        'id' => 'valueField',
                        'class' => 'form-control form-control-sm paramValueField',
                        'style' => 'width: 160px'
                    )
                );
            }
            
            $defaultValue = '<input type="text" name="inputParam['.$paramPath.'][defaultValue]" value="'.$row['DEFAULT_VALUE'].'" class="form-control form-control-sm" placeholder="'.$this->lang->line('META_00005').'">';
            $dataType = str_replace('value="'.$row['DATA_TYPE'].'"', 'value="'.$row['DATA_TYPE'].'" selected="selected"', $this->fieldDataTypeOptions);
        }
        
        if ($this->depth == 0) {
            $tabName = '<input type="text" name="inputParam['.$paramPath.'][tabName]" value="'.$row['TAB_NAME'].'" class="form-control form-control-sm globeCodeInput" placeholder="'.$this->lang->line('META_00156').'">';
        }
        
        if ($this->isNew == '1' && $row['PARENT_ID'] != '') {
            $rowMetaDataId .= '<input type="hidden" class="process-param-newrowid" name="inputParam['.$paramPath.'][newRowId]" value="'.getUID().'">';
        }
        
        if ($this->isNew == '1' && isset($this->newRowId)) {
            $rowMetaDataId .= '<input type="hidden" class="process-param-newparentid" name="inputParam['.$paramPath.'][newParentId]" value="'.$this->newRowId.'">';
        }
        
        $labelNameGlobe = Lang::line($row['LABEL_NAME']);
?>
    <tr data-path="<?php echo $paramPath; ?>" data-row-type="<?php echo $rowType; ?>" data-parent-id="<?php echo $this->rowId; ?>" data-depth="<?php echo $this->depth; ?>"<?php echo $rowId.$rowClass; ?>>
        <td>
            <input class="notuniform process-param-isdeletecheck" value="1" type="checkbox">
            <button type="button" class="btn btn-xs default param-row-up-down"><i class="fa fa-arrows"></i></button>
        </td>
        <td class="depth-padding-left-<?php echo $this->depth; ?>">
            <?php 
            echo $rowExpand;
            echo '<span class="process-path-name">'.$paramPath.'</span>'; 
            ?>
        </td>
        <td>
            <input type="hidden" class="process-param-rowid" name="inputParam[<?php echo $paramPath; ?>][rowId]" value="<?php echo $row['ID']; ?>">
            <input type="hidden" class="process-param-parentid" name="inputParam[<?php echo $paramPath; ?>][parentId]" value="<?php echo $row['PARENT_ID']; ?>">
            <input type="hidden" class="process-param-isdelete" name="inputParam[<?php echo $paramPath; ?>][isDelete]" value="0">
            <input type="hidden" class="process-param-path" name="inputParam[<?php echo $paramPath; ?>][paramPath]" value="<?php echo $paramPath; ?>">
            <input type="hidden" class="process-param-isnew" name="inputParam[<?php echo $paramPath; ?>][isNew]" value="<?php echo $this->isNew; ?>">
            <input type="hidden" class="process-param-ispathchange" name="inputParam[<?php echo $paramPath; ?>][isPathChange]" value="0">
            <input type="hidden" class="process-param-ischange" name="inputParam[<?php echo $paramPath; ?>][isChange]" value="0">
            <input type="hidden" class="process-param-oldparamname" name="inputParam[<?php echo $paramPath; ?>][oldParamName]" value="<?php echo $row['PARAM_NAME']; ?>">
            <input type="text" class="form-control form-control-sm stringInit process-param-name" name="inputParam[<?php echo $paramPath; ?>][paramName]" value="<?php echo $row['PARAM_NAME']; ?>" placeholder="<?php echo $this->lang->line('META_00075'); ?>">
            <?php echo $rowMetaDataId; ?>
        </td>
        <td>
            <select name="inputParam[<?php echo $paramPath; ?>][dataType]" class="form-control form-control-sm process-param-datatype">
                <?php echo $dataType; ?>
            </select>
        </td>
        <td>
            <span class="pf-params-labelname-globe d-none"><?php echo $labelNameGlobe; ?></span>
            <span class="pf-params-labelname-input">
                <input type="text" name="inputParam[<?php echo $paramPath; ?>][labelName]" value="<?php echo $row['LABEL_NAME']; ?>" title="<?php echo $labelNameGlobe; ?>" class="form-control form-control-sm globeCodeInput" placeholder="Label name">
            </span>
        </td>
        <td class="text-center">
            <input name="inputParam[<?php echo $paramPath; ?>][isShow]" class="notuniform" value="1" <?php echo getChecked($row['IS_SHOW'], '1'); ?> type="checkbox">
        </td>
        <td class="text-center">
            <input name="inputParam[<?php echo $paramPath; ?>][isRequired]" class="notuniform" value="1" <?php echo getChecked($row['IS_REQUIRED'], '1'); ?> type="checkbox">
        </td>
        <td data-c-name="lookupType"><?php echo $lookupType; ?></td>
        <td data-c-name="chooseType"><?php echo $chooseType; ?></td>
        <td data-c-name="lookupMetaDataId"><?php echo $lookupMetaDataId; ?></td>
        <td data-c-name="displayField"><?php echo $displayField; ?></td>
        <td data-c-name="valueField"><?php echo $valueField; ?></td>
        <td data-c-name="defaultValue"><?php echo $defaultValue; ?></td>
        <td data-c-name="tabName"><?php echo $tabName; ?></td>
    </tr>
<?php 
    }
} 
?>