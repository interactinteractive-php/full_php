<?php
$isData = $this->fillParamData ? true : false;

if ($isData && Mdwebservice::$detailFillData && Mdwebservice::$paramRealPath) {
    
    $row = $this->row;
    $params = $row['data'];
    $fillParamDatas = $this->fillParamData;
    $groupedData = [];
    $detectPath = Mdwebservice::$paramRealPath;
    
    unset($this->fillParamData);
    
    foreach ($fillParamDatas as $fillParamData) {
        $groupedData[$fillParamData[$detectPath]][] = $fillParamData;
    }
    
    foreach ($groupedData as $groupedRow) {
        
        if (!$fillParamDatas) {
            continue;
        }
        
        $tbl = $removeKeys = [];
        $tbl[] = '<table class="table table-sm table-bordered table-hover bprocess-table-dtl bprocess-theme1 mb-2" style="table-layout: fixed">';
            $tbl[] = '<tbody>';
                
            foreach ($params as $p => $param) {
                
                $rowStyle = '';
    
                if ($param['IS_SHOW'] != '1') {
                    $rowStyle = ' style="display: none"';
                }

                $tbl[] = '<tr'.$rowStyle.'>';
                    $tbl[] = '<td style="width: 220px; font-weight: bold">'.$this->lang->line($param['META_DATA_NAME']).'</td>';
                    
                    foreach (Mdwebservice::$detailFillData as $rk => $detailFillData) {

                        $lowerName = Str::lower($detailFillData[$detectPath]);

                        $arr = array_filter($fillParamDatas, function($ar) use($lowerName, $detectPath) {
                            return (Str::lower($ar[$detectPath]) == $lowerName);
                        });

                        if ($arr) {

                            $firstKey = array_key_first($arr);
                            $rowData = $arr[$firstKey];
                            $removeKeys[] = $firstKey;

                            $tbl[] = '<td class="stretchInput text-center">'; 
                                
                                if ($p == 0) {
                                    $tbl[] = '<input type="hidden" name="param['.$row['code'].'.mainRowCount][]" value="0">';
                                }
                                
                                $tbl[] = Mdwebservice::renderParamControl($this->methodId, $param, 'param[' . $param['PARAM_REAL_PATH'] . ']['.$firstKey.'][]', $param['PARAM_REAL_PATH'], $rowData);
                                
                            $tbl[] = '</td>';
                        } else {
                            $tbl[] = '<td></td>'; 
                        }
                    }

                $tbl[] = '</tr>';
            }

            $tbl[] = '</tbody>';
        $tbl[] = '</table>';
        
        if ($removeKeys) {
            foreach ($removeKeys as $removeKey) {
                unset($fillParamDatas[$removeKey]);
            }
        }
        
        echo implode('', $tbl);
    }
}