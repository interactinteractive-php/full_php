<?php
if ($this->params) {
    
    foreach ($this->params as $n => $row) {
        
        $paramPath = $row['PARAM_REAL_PATH'];
        
        $rowId = $rowClass = $rowExpand = $rowMetaDataId = '';
        $rowType = 'field';
        
        if ($row['RECORD_TYPE'] != '') {
            
            $rowId = ' data-id="'.$row['ID'].'"';
            $rowType = 'group';
            
            $rowClass = ($this->depth == 0) ? ' class="tabletree-'.$row['ID'].'"' : ' class="tabletree-'.$row['ID'].' tabletree-parent-'.$this->rowId.'"';
            $rowExpand = '<span class="tabletree-expander fa fa-plus"></span>';
            
            $dataType = str_replace('value="'.$row['RECORD_TYPE'].'"', 'value="'.$row['RECORD_TYPE'].'" selected="selected"', $this->fieldDataTypeOptions);
            
            if (isset($row['META_DATA_ID'])) {
                $rowMetaDataId = '<input type="hidden" name="outputParam['.$paramPath.'][metaDataId]" value="'.$row['META_DATA_ID'].'">';
                $rowId .= ' data-metadataid="'.$row['META_DATA_ID'].'"';
            }
            
        } else {
            
            if ($this->depth != 0) {
                $rowClass = ' class="tabletree-'.$row['ID'].' tabletree-parent-'.$this->rowId.'"';
            }

            $dataType = str_replace('value="'.$row['DATA_TYPE'].'"', 'value="'.$row['DATA_TYPE'].'" selected="selected"', $this->fieldDataTypeOptions);
        }
        
        if ($this->isNew == '1' && $row['PARENT_ID'] != '') {
            $rowMetaDataId .= '<input type="hidden" class="process-param-newrowid" name="outputParam['.$paramPath.'][newRowId]" value="'.getUID().'">';
        }
        
        if ($this->isNew == '1' && isset($this->newRowId)) {
            $rowMetaDataId .= '<input type="hidden" class="process-param-newparentid" name="outputParam['.$paramPath.'][newParentId]" value="'.$this->newRowId.'">';
        }
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
            <input type="hidden" class="process-param-rowid" name="outputParam[<?php echo $paramPath; ?>][rowId]" value="<?php echo $row['ID']; ?>">
            <input type="hidden" class="process-param-parentid" name="outputParam[<?php echo $paramPath; ?>][parentId]" value="<?php echo $row['PARENT_ID']; ?>">
            <input type="hidden" class="process-param-isdelete" name="outputParam[<?php echo $paramPath; ?>][isDelete]" value="0">
            <input type="hidden" class="process-param-path" name="outputParam[<?php echo $paramPath; ?>][paramPath]" value="<?php echo $paramPath; ?>">
            <input type="hidden" class="process-param-isnew" name="outputParam[<?php echo $paramPath; ?>][isNew]" value="<?php echo $this->isNew; ?>">
            <input type="hidden" class="process-param-ispathchange" name="outputParam[<?php echo $paramPath; ?>][isPathChange]" value="0">
            <input type="hidden" class="process-param-oldparamname" name="outputParam[<?php echo $paramPath; ?>][oldParamName]" value="<?php echo $row['PARAM_NAME']; ?>">
            <input type="text" class="form-control form-control-sm stringInit process-param-name" name="outputParam[<?php echo $paramPath; ?>][paramName]" value="<?php echo $row['PARAM_NAME']; ?>" placeholder="<?php echo $this->lang->line('META_00075'); ?>">
            <?php echo $rowMetaDataId; ?>
        </td>
        <td>
            <select name="outputParam[<?php echo $paramPath; ?>][dataType]" class="form-control form-control-sm process-param-datatype">
                <?php echo $dataType; ?>
            </select>
        </td>
        <td>
            <input type="text" name="outputParam[<?php echo $paramPath; ?>][labelName]" value="<?php echo $row['LABEL_NAME']; ?>" title="<?php echo Lang::lineEmpty($row['LABEL_NAME']); ?>" class="form-control form-control-sm globeCodeInput" placeholder="Label name">
        </td>
        <td class="text-center">
            <input name="outputParam[<?php echo $paramPath; ?>][isShow]" class="notuniform" value="1" <?php echo getChecked($row['IS_SHOW'], '1'); ?> type="checkbox">
        </td>
    </tr>
<?php 
    }
} 
?>
