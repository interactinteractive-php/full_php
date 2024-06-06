<div class="col-md-12">
    <table class="table table-sm table-bordered table-hover bprocess-table-dtl bprocess-theme1">
        <thead>
            <tr>
                <th style="width: 20px;">№</th>
                <?php
                if ($this->isShowType == '1')
                    echo '<th>Төрөл</th>';
                ?>
                <th style="width: 70px;"><?php echo 'Эхлэх огноо' ?></th>
                <th style="width: 70px;"><?php echo 'Дуусах огноо' ?></th>
                <th style="width: 20px;"><?php echo 'Өдөр' ?></th>
                <th style="width: 20px;"><?php echo 'Цаг' ?></th>
                <th style="width: 20px;"><?php echo 'Минут' ?></th>
                <th style="width: 20px;"><?php echo 'Тайлбар' ?></th>
            </tr>
        </thead>
        <tbody>
            <?php
            if ($this->row) {
                $i = 1;
                foreach ($this->row as $row) {
                    ?>
                    <tr>
                        <td style="text-align: center"><?php echo $i++; ?></td>
                        <?php
                        if ($this->isShowType == '1')
                            echo '<td>' . $row['TYPE_NAME'] . '</td>';
                        ?>
                        <td style="text-align: center"><?php echo Date::format('Y-m-d', $row['START_DATE']); ?></td>
                        <td style="text-align: center"><?php echo Date::format('Y-m-d', $row['END_DATE']); ?></td>
                        <td style="text-align: center"><?php echo $row['LEAVE_DAY']; ?></td>
                        <td style="text-align: center"><?php echo $row['LEAVE_HOUR']; ?></td>
                        <td style="text-align: center"><?php echo $row['LEAVE_MINUTE']; ?></td>
                        <td style="text-align: center" title="<?php echo $row['DESCRIPTION']; ?>"><div style="    width: 70px !important; word-wrap: break-word; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;"><?php echo $row['DESCRIPTION']; ?></div></td>
                    </tr>
                <?php }
            }
            ?>
        </tbody>
    </table>
</div>