<?php
if ($this->sidebarLabelName) {
    foreach ($this->sidebarLabelName as $row) {
        $val = issetParam($this->sidebarData[$row['FIELD_PATH']]);
        if ($val == ':') {
            $val = '';
        }
?>
<tr>
    <td><?php echo $this->lang->line($row['LABEL_NAME']); ?></td>
    <td class="text-right"><?php echo $val; ?></td>
</tr>
<?php
    }
}
?>