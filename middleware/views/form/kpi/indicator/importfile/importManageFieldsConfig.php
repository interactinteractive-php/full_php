<form method="post">
    <table class="table table-border table-hover">
        <thead>
            <tr>
                <th class="font-weight-bold" style="width: 20px;">№</th>
                <th class="font-weight-bold" style="width: 340px;">Excel column</th>
                <th class="font-weight-bold">Main column</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            foreach ($this->trgFields as $k => $row) {
            ?>
            <tr>
                <td><?php echo ++$k; ?>.</td>
                <td>
                    <?php 
                    echo Form::hidden(['name' => 'mapId[]', 'value' => $row['ID']]);
                    echo Form::hidden(['name' => 'trgId[]', 'value' => $row['TRG_MAP_ID']]);
                    echo $row['TRG_LABEL_NAME']; 
                    ?>
                </td>
                <td>
                    <?php 
                    $srcComboTmp = $this->srcCombo;
                    $srcComboTmp = str_replace('<option value="'.$row['SRC_MAP_ID'].'-'.$row['INPUT_NAME'].'-', '<option selected value="'.$row['SRC_MAP_ID'].'-'.$row['INPUT_NAME'].'-', $srcComboTmp);
                    echo $srcComboTmp; 
                    ?>
                </td>
            </tr>
            <?php
            }
            ?>
        </tbody>
    </table>
    <?php
    echo Form::hidden(array('name' => 'indicatorId', 'value' => $this->indicatorId)); 
    echo Form::hidden(array('name' => 'mainIndicatorId', 'value' => $this->mainIndicatorId)); 
    ?>
</form>