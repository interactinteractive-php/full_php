<?php if (!defined('_VALID_PHP')) exit('Direct access to this location is not allowed.'); ?>

<div class="row">
    <div class="col-md-12">
        <table class="table table-sm table-bordered table-hover mb10" id="st-user-option-tbl-<?php echo $this->statementId; ?>">
            <thead>
                <tr>
                    <th class="text-center" style="vertical-align: middle; width: 30px">д/д</th>
                    <th style="vertical-align: middle">Нэр</th>
                    <th style="width: 96px">
                        <label>
                            <input type="checkbox" class="st-column-check-all"> 
                            Харах эсэх
                        </label>
                    </th>
                </tr>
            </thead>
            <tbody>
                <?php
                $i = 1;
                foreach ($this->groupingFields as $param) {
                    
                    $checked = $unchecked = '';
                    
                    if ($param['FIELDNOTSELECTED'] != 'fieldnotselected') {
                        
                        if ($param['GROUP_ORDER'] != '') {
                            $isUserSaved = true;
                            $checked = ' checked="checked"';
                        }

                        if (!isset($isUserSaved) 
                            && (($param['GROUP_ORDER'] == '' && $param['IS_USER_OPTION'] == '') 
                            || ($param['GROUP_ORDER'] == '' && $param['IS_USER_OPTION'] == '0')     
                            || ($param['GROUP_ORDER'] == '' && $param['IS_USER_OPTION'] == '2'))         
                            ) {
                            $checked = ' checked="checked"';
                        }
                    }
                    
                    if ($checked == '') {
                        $unchecked = ' checked="checked"';
                    }
                ?>
                <tr>
                    <td class="text-center"><?php echo $i; ?></td>
                    <td><?php echo $this->lang->line($param['LABEL_NAME']); ?></td>
                    <td class="text-center">
                        <input type="checkbox" name="groupingUserOption[]" value="<?php echo $param['ID'].'|'.$param['GROUP_FIELD_PATH']; ?>"<?php echo $checked; ?>/>
                        <input type="checkbox" name="groupingUserOptionNotChecked[]" value="<?php echo $param['GROUP_FIELD_PATH']; ?>" class="notuniform hide"<?php echo $unchecked; ?>/>
                    </td>
                </tr>
                <?php
                    $i++;
                }
                ?>
            </tbody>
        </table>
    </div>    
</div>
<input type="hidden" name="detectGroupingUserOption" value="1"/>

<style type="text/css">
#st-user-option-tbl-<?php echo $this->statementId; ?> *::-moz-selection { background:transparent; }
#st-user-option-tbl-<?php echo $this->statementId; ?> *::selection { background:transparent; }
#st-user-option-tbl-<?php echo $this->statementId; ?> tbody td {
    padding: 8px 5px;
}    
tr.stRowDragClass td {
    background-color: #ddd;
    -webkit-box-shadow: 6px 3px 5px #555, 0 1px 0 #ccc inset, 0 -1px 0 #ccc inset;
}
tr.stRowDragClass td:last-child {
    -webkit-box-shadow: 1px 8px 6px -4px #555, 0 1px 0 #ccc inset, 0 -1px 0 #ccc inset;
}
</style>

<script type="text/javascript">
$(function() {
    
    $('#st-user-option-tbl-<?php echo $this->statementId; ?>').tableDnD({
        onDragClass: 'stRowDragClass',
        onDrop: function(table, row) {
            var $el = $(table).find('tbody > tr');
            var len = $el.length, i = 0;
            for (i; i < len; i++) { 
                $($el[i]).find('td:first').text(i + 1);
            }
        }
    });
    
    $('#st-user-option-tbl-<?php echo $this->statementId; ?>').on('click', 'input[name="groupingUserOption[]"]', function(){
        var $this = $(this);
        var $row = $this.closest('tr');
        if ($this.is(':checked')) {
            $row.find('input[name="groupingUserOptionNotChecked[]"]').prop('checked', false);
        } else {
            $row.find('input[name="groupingUserOptionNotChecked[]"]').prop('checked', true);
        }
    });
    
    $('.st-column-check-all').on('click', function() {
        var $this = $(this);
        var $stUserOptionTable = $this.closest('table');
        var outputParamCol = $this.closest('tr').children().index($this.closest('th'));
        var outputParamIndex = outputParamCol + 1;
        
        if ($this.is(':checked')) {
            $stUserOptionTable.find("td:nth-child(" + outputParamIndex + ") input:checkbox").attr('checked', false);
        }
        $stUserOptionTable.find("td:nth-child(" + outputParamIndex + ") input:checkbox").click();
        $.uniform.update();
    });
});
</script>

