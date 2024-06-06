<div class="table-scrollable table-scrollable-borderless" style="margin-top: 0 !important">
    <table class="table table-hover table-light">
        <tbody>
            <?php
            if ($this->recordList) {
                foreach ($this->recordList as $row) {
            ?>
            <tr>
                <td style="width: 20px"><input type="checkbox" value="1"/></td>
                <?php
                foreach ($this->header as $hdr) {
                    if ($hdr['META_TYPE_CODE'] == 'file') {
                        echo '<td><img src="'.$row[$hdr['FIELD_PATH']].'" class="rounded-circle dataview-list-icon" onerror="onUserImgError(this);" height="32" width="32"></td>';
                    } else {
                        echo '<td>'.$row[$hdr['FIELD_PATH']].'</td>';
                    }
                }
                ?>
            </tr>
            <?php
                }
            }
            ?>
        </tbody>
    </table>
</div>