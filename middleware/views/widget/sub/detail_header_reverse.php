<?php
$row = $this->row;
$params = $row['data'];
$isData = $this->fillParamData ? true : false;

$tbl = [];
$tbl[] = '<table class="table table-sm table-bordered table-hover bprocess-table-dtl bprocess-theme1" style="table-layout: fixed">';
$tbl[] = '<tbody>';
    
    if ($isData) {
        
        $tbl[] = '<tr style="display: none">';
            $tbl[] = '<td>PF params</td>';
            foreach ($this->fillParamData as $rk => $rowData) {
                $tbl[] = '<td>'; 
                    $tbl[] = '<input type="hidden" name="param['.$row['code'].'.mainRowCount][]" value="0">';
                $tbl[] = '</td>';
            }
        $tbl[] = '</tr>';
        
        Mdwebservice::$detailFillData = $this->fillParamData;
    }
    
foreach ($params as $param) {
    
    if ($param['THEME_POSITION_NO'] == '1') {
        Mdwebservice::$paramRealPath = $param['LOWER_PARAM_NAME'];
    }
    
    $rowStyle = '';
    
    if ($param['IS_SHOW'] != '1') {
        $rowStyle = ' style="display: none"';
    }
    
    $tbl[] = '<tr'.$rowStyle.'>';
        $tbl[] = '<td style="width: 220px; font-weight: bold">'.$this->lang->line($param['META_DATA_NAME']).'</td>';
        
        foreach ($this->fillParamData as $rk => $rowData) {
            $tbl[] = '<td class="stretchInput text-center">'; 
                $tbl[] = Mdwebservice::renderParamControl($this->methodId, $param, 'param[' . $param['PARAM_REAL_PATH'] . ']['.$rk.'][]', $param['PARAM_REAL_PATH'], $rowData);
            $tbl[] = '</td>';
        }
        
    $tbl[] = '</tr>';
}

$tbl[] = '</tbody>';
$tbl[] = '</table>';

echo implode('', $tbl);