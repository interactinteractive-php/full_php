<div class="panel panel-default bg-inverse">
    <table class="table sheetTable sheetTableViewMode<?php echo isset($this->isLocked) ? ' sheetTable-lock' : ''; ?>">
        <tbody>
            <tr>
                <td style="width: 170px" class="left-padding"><?php echo $this->lang->line('META_00024'); ?></td>
                <td><?php echo $this->folderNames; ?></td>
            </tr>
            <tr>
                <td class="left-padding"><?php echo $this->lang->line('META_00197'); ?></td>
                <td>
                    <div class="metaChoosedIcon">
                        <div class="iconpath">
                            <?php
                            if (!empty($this->metaRow['META_ICON_ID'])) {
                                echo '<img src="assets/core/global/img/metaicon/small/'.$this->metaRow['META_ICON_CODE'].'">';
                            }
                            ?>
                        </div>
                    </div>
                </td>
            </tr>
            <tr>
                <td class="left-padding"><?php echo $this->lang->line('META_00145'); ?></td>
                <td><?php echo $this->metaRow['META_TYPE_NAME']; ?></td>
            </tr>
            <tr>
                <td class="left-padding"><?php echo $this->lang->line('META_00120'); ?></td>
                <td>
                    <button type="button" class="btn btn-sm purple-plum" onclick="metaPHPExportById('<?php echo $this->metaDataId; ?>');"><i class="far fa-download"></i></button>
                </td>
            </tr>
        </tbody>
    </table>
</div>