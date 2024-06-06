<?php echo Form::create(array('class' => 'form-horizontal', 'id' => 'pf-checklist-form', 'method' => 'post')); ?>
<div class="row xs-form">
    <div class="col-md-12">
        <?php
        if ($this->checkList) {
        ?>
            <div class="bp-checklist-tbl">
                <?php
                foreach ($this->checkList as $row) {

                    if (empty($row['GROUP_ID'])) {

                        $attr = '';
                        $checkedValue = '0';
                        $rowCheckedClass = '';

                        if ($row['IS_CHECKED'] == '1') {
                            $checkedValue = '1';
                            $rowCheckedClass = ' bp-checklist-row-checked';
                        }

                        if ($row['PROCESS_META_DATA_ID']) {
                            $attr = ' data-process-id="'.$row['PROCESS_META_DATA_ID'].'" data-process-selectedRowData ="'. $this->oneSelectedRow .'" data-view-id="'. $this->dataViewId .'"';
                        }
                ?>
                    <div class="bp-checklist-tbl-row<?php echo $rowCheckedClass; ?>"<?php echo $attr; ?>>
                        <div class="bp-checklist-tbl-left-cell">
                            <div class="bp-checklist-check"></div>
                            <input type="hidden" name="bp_checklist[<?php echo $row['CHECKLIST_ID']; ?>]" value="<?php echo $checkedValue; ?>">
                            <input type="hidden" name="bp_checkListTempId[<?php echo $row['CHECKLIST_ID']; ?>]" value="<?php echo $row['TEMPLATE_ID']; ?>">
                        </div>
                        <div class="bp-checklist-tbl-right-cell">
                            <?php echo $row['NAME']; ?>
                        </div>
                    </div>
                <?php
                    }
                }
                ?>
            </div>
        
            <?php
            if ($this->groupedCheckList) {  
                foreach ($this->groupedCheckList as $g => $group) {
                    
                    if ($g) {
                        
                    $groupRow = $group['row'];
                    $groupRows = $group['rows'];
            ?>
            <div class="bp-checklist-tbl bp-checklist-group">
                <div class="bp-checklist-tbl-row">
                    <div class="bp-checklist-tbl-right-cell">
                        <?php echo $groupRow['GROUP_NAME']; ?>
                    </div>
                </div>
            </div>   
        
            <?php
            if ($groupRows) {
            ?>
            <div class="bp-checklist-tbl">
                
            <?php
            foreach ($groupRows as $groupRow) {
                $attr = '';
                $checkedValue = '0';
                $rowCheckedClass = '';

                if ($groupRow['IS_CHECKED'] == '1') {
                    $checkedValue = '1';
                    $rowCheckedClass = ' bp-checklist-row-checked';
                }

                if ($groupRow['PROCESS_META_DATA_ID']) {
                    $attr = ' data-process-id="'.$groupRow['PROCESS_META_DATA_ID'].'"  data-process-selectedRowData ="'. $this->oneSelectedRow .'" data-view-id="'. $this->dataViewId .'"';
                }
            ?>
                    <div class="bp-checklist-tbl-row<?php echo $rowCheckedClass; ?>"<?php echo $attr; ?>>
                        <div class="bp-checklist-tbl-left-cell">
                            <div class="bp-checklist-check"></div>
                            <input type="hidden" name="bp_checklist[<?php echo $groupRow['CHECKLIST_ID']; ?>]" value="<?php echo $checkedValue; ?>">
                            <input type="hidden" name="bp_checkListTempId[<?php echo $groupRow['CHECKLIST_ID']; ?>]" value="<?php echo $groupRow['TEMPLATE_ID']; ?>">
                        </div>
                        <div class="bp-checklist-tbl-right-cell">
                            <?php echo $groupRow['NAME']; ?>
                        </div>
                    </div>
            <?php
            }
            ?>
            </div>
            <?php
                        }
                    }
                }
            }
            ?>
        </div>
        <?php
        }
        ?>
    </div>
</div>
<?php 
echo Form::hidden(array('name' => 'tempId', 'value' => $this->tempId)); 
echo Form::hidden(array('name' => 'refStructureId', 'value' => $this->refStructureId)); 
echo Form::hidden(array('name' => 'recordId', 'value' => $this->recordId)); 
echo Form::close(); 
?>