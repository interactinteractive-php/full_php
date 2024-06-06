<?php
if ($this->paramList) {
    foreach ($this->paramList as $value) {
        $collapse = '';
        if ($value['RECORD_TYPE'] == 'row') {
            $collapse = '<a href="javascript:;" class="param-collapser">X</a>';
        }
        
        $labelName = $this->lang->line($value['META_DATA_NAME']);
?>
    <tr>
        <td><?php echo $labelName; ?></td>
        <td><?php echo $collapse; ?> #<?php echo strtolower($value['META_DATA_CODE']); ?>#</td>
    </tr>
<?php
    }
}
?>