<?php
if ($this->recordList) {
    
    $this->color = Str::lower($this->color);
    
    if (isset($this->columnData)) {
        $columns = Arr::groupByArray($this->columnData, $this->columnName);
    } else {
        $columns = Arr::groupByArray($this->pureRecordList, $this->columnName);
    }
    
    $isAllShowField = issetParam($this->row['dataViewLayoutTypes']['explorer']['fields']['allshowfield']);
?>
<thead>
    <tr>
        <th>№</th>
        <?php
        if (!$isAllShowField) {
        ?>
        <th><?php echo Lang::line($this->header[$this->groupName]['row']['LABEL_NAME']); ?></th>
        <?php
        } else {
            
            foreach ($this->header as $header) {
                
                $headerRow = $header['row'];
                
                if ($headerRow['LABEL_NAME'] == '' || $headerRow['FIELD_PATH'] == $this->name1 || $headerRow['FIELD_PATH'] == $this->columnName || ($this->color && $headerRow['FIELD_PATH'] == $this->color)) {
                    continue;
                }
                
                $width = $headerRow['COLUMN_WIDTH'];
                            
                if ($width) {
                    $width = 'width: '.$width.'; min-width: '.$width;
                }
        ?>
            <th style="<?php echo $width; ?>"><?php echo Lang::line($headerRow['LABEL_NAME']); ?></th>
        <?php
            }
        }
        foreach ($columns as $colRow) {
        ?>
            <th><?php echo $colRow['row'][$this->columnName] ?></th>
        <?php 
        } 
        ?>
    </tr> 
</thead>
<tbody>
    <?php
    $n = 1;
    
    if (!$isAllShowField) {
        
        foreach ($this->recordList as $recordRow) {
            $groupName = $recordRow['row'][$this->groupName];
            $rows      = $recordRow['rows'];
            $rowData   = htmlentities(json_encode($recordRow['row'], JSON_UNESCAPED_UNICODE), ENT_QUOTES, 'UTF-8');
    ?>
        <tr data-rowdata="<?php echo $rowData; ?>">
            <td style="background-color:#fff;text-align: center"><?php echo $n; ?></td>
            <td style="background-color:#fff"><?php echo $groupName; ?></td>
            <?php
            foreach ($columns as $colRow) {
                echo '<td style="background-color:#fff">';
                    foreach ($rows as $rs) {
                        if ($rs[$this->columnName] == $colRow['row'][$this->columnName]) {
                            $rowJson = htmlentities(json_encode($rs), ENT_QUOTES, 'UTF-8');
                            echo '<div data-row-data="'.$rowJson.'" style="background-color: '.issetParam($rs[$this->color]).';padding: 3px;">' . $rs[$this->name1] . '</div>';
                        }
                    }
                echo '</td>';
            } 
            ?>
        </tr>
    <?php 
            $n ++;
        }
        
    } else {
                
        foreach ($this->recordList as $record) {

            $recordRow = $record['row'];
            $rows      = $record['rows'];
            $rowData   = htmlentities(json_encode($recordRow, JSON_UNESCAPED_UNICODE), ENT_QUOTES, 'UTF-8');
    ?>
        <tr data-rowdata="<?php echo $rowData; ?>">
            <td style="background-color:#fff;text-align: center"><?php echo $n; ?></td>
            <?php
            $pivotColDataType = '';
            
            foreach ($this->header as $header) {
                
                $headerRow = $header['row'];
                
                if ($headerRow['FIELD_PATH'] == $this->name1) {
                    $pivotColDataType = $headerRow['META_TYPE_CODE'];
                }
                        
                if ($headerRow['LABEL_NAME'] == '' || $headerRow['FIELD_PATH'] == $this->name1 || $headerRow['FIELD_PATH'] == $this->columnName || ($this->color && $headerRow['FIELD_PATH'] == $this->color)) {
                    continue;
                }
                
                $dataType = $headerRow['META_TYPE_CODE'];
                $style = '';

                if ($dataType == 'bigdecimal') {
                    $val = Number::fractionRange($recordRow[$headerRow['FIELD_PATH']], 2);
                    $style = 'text-align: right;';
                } else {
                    $val = $recordRow[$headerRow['FIELD_PATH']];
                }
            ?>
            <td style="background-color:#fff;<?php echo $style; ?>"><?php echo $val; ?></td>
            <?php
            }

            foreach ($columns as $colRow) {
                
                echo '<td style="background-color:#fff">';
                
                    foreach ($rows as $rs) {
                        
                        if ($rs[$this->columnName] == $colRow['row'][$this->columnName]) {
                            $rowJson = htmlentities(json_encode($rs), ENT_QUOTES, 'UTF-8');
                            $rVal = $rs[$this->name1];
                            $cellStyle = '';

                            if ($pivotColDataType == 'bigdecimal') {
                                $rVal = Number::fractionRange($rVal, 2);
                                $cellStyle = 'text-align: right;';
                            } 

                            echo '<div data-row-data="'.$rowJson.'" style="background-color: '.issetParam($rs[$this->color]).';padding: 3px;'.$cellStyle.'">' . $rVal . '</div>';
                            break;
                        }
                    }
                    
                echo '</td>';
            } 
            ?>
        </tr>
    <?php
            $n ++;
        }
    }
    ?>
</tbody>
<?php
} 
?>
