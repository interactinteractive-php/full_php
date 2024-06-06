<?php if (!defined('_VALID_PHP')) exit('Direct access to this location is not allowed.'); ?> 
<?php 
$tmparr = array();
$groupPathName = 'COURSE_NAME';
$headerPathArray = $rowsArray = $position1GroupArray = array();
if (issetParamArray($this->relationComponentsConfigData['header'])) {
    $headerPathArray = $this->relationComponentsConfigData['header'];
    $headerArray = Arr::groupByArrayOnlyRow($headerPathArray, 'trg_indicator_path', false);
}

if (issetParamArray($this->relationComponentsConfigData['rows'])) {
    $rowsPathArray = $this->relationComponentsConfigData['rows'];
    $rowsArray = Arr::groupByArrayOnlyRow($rowsPathArray, 'trg_indicator_path', false);
    
    $position1PathArr = explode('.', $rowsArray['position-1']['src_indicator_path']);
    $position1GroupArray = Arr::groupByArrayOnlyRows($this->rowData[$position1PathArr['0']], $position1PathArr['1']);
    $tmparr = array();

    foreach ($position1GroupArray as $position1Key =>  $position1Grp) { 
        $position1Group = $position1Grp['0'];
        foreach ($position1Grp as $rKey => $rVal) {
            
            for ($c=1; $c<=10; $c++) {
                if (issetParam($rowsArray['position-' . $c]['src_indicator_path'])) {
                    $position1PathArr = explode('.', $rowsArray['position-' . $c]['src_indicator_path']);
                    if (issetParam($position1PathArr['1'])) {
                        $tmp['position' . $c] = issetParam($rVal[$position1PathArr['1']]);
                    }
                }
            }
            
            $tmp['position-recordid'] = $tmp['position-starttime'] = $tmp['position-endtime'] = '';

            if (issetParam($rVal['START_TIME'])) {
                $parsed = explode(':', $rVal['START_TIME']);
                $timeSecond = $parsed['0'] * 3600 + $parsed['1'] * 60 + $parsed['2'];    
                $tmp['position-starttime'] = $timeSecond;
            }

            if (issetParam($rVal['CONTENT_ID'])) {
                $tmp['position-recordid'] = $rVal['CONTENT_ID'];
            }

            if (issetParam($rVal['END_TIME'])) {
                $parsed = explode(':', $rVal['END_TIME']);
                $timeSecond = $parsed['0'] * 3600 + $parsed['1'] * 60 + $parsed['2'];    

                $tmp['position-endtime'] = $timeSecond;
            }

            $tmp['rowdata'] = $rVal;
            array_push($tmparr, $tmp);
        }
    }
}
foreach ($tmparr as $key => $row) { 
    $uId = getUID();
    ?>
    <div class="card p-2 w-100">
        <div class="card-header" style="height: 25px; ">
            <h6 class="card-title">
                <a class="text-default font-weight-bold" data-toggle="collapse" href="#collapse-item-default<?php echo $uId ?>" aria-expanded="true"><?php echo issetParam($row['position1']) ?></a>
            </h6>
        </div>
        <div id="collapse-item-default<?php echo $uId ?>" class="pt-2 collapse show"`>
            <div class="card-body">
                <?php
                    $subTmparr = $subTmp = array();
                    if (issetParamArray($this->relationComponentsConfigData['subrows'])) {

                        $subRowsPathArray = $this->relationComponentsConfigData['subrows'];
                        $subRowsArray = Arr::groupByArrayOnlyRow($subRowsPathArray, 'trg_indicator_path', false);
                        $subPosition1PathArr = explode('.', $subRowsArray['position-1']['src_indicator_path']);
                        
                        $subPosition1GroupArray = Arr::groupByArrayOnlyRows($row['rowdata'][$subPosition1PathArr['1']], $subPosition1PathArr['2']);
                        
                        /* var_dump($subPosition1GroupArray);
                        die; */
                        $subTmparr = $subTmp = array();
                        if ($groupPathName) {
                            $subTmparr[$groupPathName] = array();
                        }
                        foreach ($subPosition1GroupArray as $subPosition1Key =>  $subPosition1Grp) { 
                            foreach ($subPosition1Grp as $rKey => $rVal) {
                                for ($c=1; $c<=10; $c++) {
                                    if (issetParam($subRowsArray['position-' . $c]['src_indicator_path'])) {
                                        $subPosition1PathArr = explode('.', $subRowsArray['position-' . $c]['src_indicator_path']);
                                        if (issetParam($subPosition1PathArr['2'])) {
                                            $subTmp['position' . $c] = issetParam($rVal[$subPosition1PathArr['2']]);
                                            $subTmp['position' . $c . '-label'] = issetParam($subRowsArray['position-' . $c . '-label']['default_value']);
                                        }
                                    }
                                }

                                if (issetParam($subRowsArray['position-group']['src_indicator_path'])) {
                                    $subGroupPosition1PathArr = explode('.', $subRowsArray['position-group']['src_indicator_path']);
                                    $subTmp['position-group'] = issetParam($rVal[$subGroupPosition1PathArr['2']]);
                                }

                                array_push($subTmparr, $subTmp);
                            }
                        }
                    }
                ?>
                <table class="table table-borderless"`>
                    <tbody>
                        <?php 
                        if ($subTmparr) {
                            foreach ($subTmparr as $key => $subRow) { 
                                if (issetParam($subRow['position-group'])) { ?> 
                                <tr>
                                    <td colspan="2" class="font-weight-bold" style="border-bottom: 1px solid #333; "><?php echo $subRow['position-group']; ?></td>
                                </tr>
                            <?php }
                            for ($ii = 1; $ii <= sizeOf($subRow)/2; $ii++) {
                                if (issetParam($subRow['position'. $ii .'-label']) !== '') { ?>
                                    <tr>
                                        <td><?php echo issetParam($subRow['position'. $ii .'-label']) ?> </td>
                                        <td><?php echo issetParam($subRow['position'. $ii]) ?> </td>
                                    </tr>
                                <?php }
                            }
                        }
                        } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <?php 
}
?>

<?php echo issetParam($this->uniqCss) ?>
<?php echo issetParam($this->uniqJs) ?>