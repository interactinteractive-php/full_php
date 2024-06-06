<div class="col-md-12 xs-form">
    <table class="table table-hover table-light" id="wfm-status-assigment-tbl-flow">
        <thead>
            <tr>
                <th colspan="2">Овог Нэр</th>
                <th>Гарын үсэг зурах огноо</th>
                <th>Гарын үсэг зурсан огноо</th>
                <th class="text-center">Хүчинтэй эсэх</th>
                <th>Байгууллага</th>
                <th>Албан тушаал</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if ($this->signedDataList) {
                
                $signedDataList = Arr::sortBy('CREATED_DATE', $this->signedDataList, 'asc');

                foreach ($signedDataList as $k => $row) {
                    
                    $picSrc = 'assets/core/global/img/images.jpg';
                    
                    if (isset($row['PICTURE'])) {
                        if ($row['PICTURE'] != '' && file_exists($row['PICTURE'])) {
                            $picSrc = $row['PICTURE'];
                        }
                    }
                    
                    $fullName = '';
                    
                    if (isset($row['fullName'])) {
                        $fullName = $row['fullName'];
                    } elseif (isset($row['FIRST_NAME'])) {
                        $fullName = '<a href="javascript:;">'.$row['LAST_NAME'].' '.$row['FIRST_NAME'].'</a>';  
                    }
                    
                    $isValidSign = '';
                    
                    if (isset($row['isValidSign']) && $row['isValidSign']) {
                        $isValidSign = '<i class="fa fa-check-circle text-success" style="font-size:16px"></i>';
                    } elseif (isset($row['isValidSign']) && !$row['isValidSign']) {
                        $isValidSign = '<i class="fa fa-times-circle text-danger" style="font-size:16px"></i>';
                    }
                    
                    $expiredDay = '';
                    
                    if (isset($row['DUE_DATE']) && isset($row['date']) && $row['DUE_DATE'] != '' && $row['date'] != '') {
                        
                        $signedDate = new DateTime(Date::formatter($row['date'], 'Y-m-d'));
                        $signDate = new DateTime(Date::formatter($row['DUE_DATE'], 'Y-m-d'));
                        
                        if ($signedDate > $signDate) {
                            $interval = $signedDate->diff($signDate);
                            $expiredDay = ' <span class="badge badge-danger">'.$interval->format('%a').'</span>';
                        }
                    }
            ?>
            <tr>
                <td class="fit">
                    <img class="user-pic" src="<?php echo $picSrc; ?>">
                </td>
                <td>
                    <?php echo $fullName; ?>
                </td>
                <td>
                    <i class="fa fa-clock-o"></i> <?php echo isset($row['DUE_DATE']) ? $row['DUE_DATE'] : ''; ?>
                </td>
                <td>
                    <i class="fa fa-clock-o"></i> <?php echo (isset($row['date']) ? $row['date'] : '') . $expiredDay; ?>
                </td>
                <td class="text-center">
                    <?php echo $isValidSign; ?>
                </td>
                <td>
                    <?php echo isset($row['organizationName']) ? $row['organizationName'] : ''; ?>
                </td>
                <td>
                    <?php echo isset($row['positionName']) ? $row['positionName'] : ''; ?>
                </td>
            </tr>
            <?php
                }
            }
            ?>
        </tbody>
    </table> 
</div>