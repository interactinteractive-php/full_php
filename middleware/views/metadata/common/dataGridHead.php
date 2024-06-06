<?php
if ($this->dataGridHeadData) {
    $filterDateInit = array();
    $filterDateTimeInit = array();
    $filterBigDecimalInit = array();
    $filterNumberInit = array();
    $filterTimeInit = array();
    
    foreach ($this->dataGridHeadData as $row) {
        $width = ((empty($row['COLUMN_WIDTH'])) ? "width: '150'," : "width: '" . $row['COLUMN_WIDTH'] . "',");
        $cellStyle = '';
        $cellFormatter = '';
        $headerAlign = "halign: 'left',";
        $bodyAlign = "align: 'left',";
        $hidden = '';
        
        if (issetVar($row['IS_BOLD']) == '1') {
            $cellStyle .= 'font-weight: bold;';
        }
        if (issetVar($row['TEXT_COLOR']) != '') {
            $cellStyle .= 'color:' . $row['TEXT_COLOR'] . ';';
        }
        if (issetVar($row['TEXT_TRANSFORM']) != '') {
            $cellStyle .= 'text-transform:' . $row['TEXT_TRANSFORM'] . ';';
        }
        if (!empty($cellStyle)) {
            $cellStyle = "styler: function(v, r, i) {return '$cellStyle';},";
        }
        if (issetVar($row['HEADER_ALIGN']) != '') {
            $headerAlign = "halign: '" . $row['HEADER_ALIGN'] . "',";
        }
        if (issetVar($row['BODY_ALIGN']) != '') {
            $bodyAlign = "align: '" . $row['BODY_ALIGN'] . "',";
        }
        if ($row['META_TYPE_CODE'] != '') {
            if ($row['META_TYPE_CODE'] == 'date') {
                $cellFormatter = "formatter: function(v, r, i) {return dateFormatter('Y-m-d', v);},";
                $filterDateInit[] = "input[name=" . $row['FIELD_PATH'] . "]";
            } elseif ($row['META_TYPE_CODE'] == 'datetime') {
                $cellFormatter = "formatter: function(v, r, i) {return dateFormatter('Y-m-d H:i', v);},";
                $filterDateTimeInit[] = "input[name=" . $row['FIELD_PATH'] . "]";
            } elseif ($row['META_TYPE_CODE'] == 'time') {
                $cellFormatter = "formatter: function(v, r, i) {return dateFormatter('H:i', v);},";
                $filterTimeInit[] = "input[name=" . $row['FIELD_PATH'] . "]";
                $bodyAlign = "align: 'center',";
            } elseif ($row['META_TYPE_CODE'] == 'bigdecimal') {
                if (!is_null($row['FRACTION_RANGE'])) {
                    $cellFormatter = "formatter: function(v, r, i) {
                           if (v) {
                               return '<span class=\"decimalInit\" data-m-dec=\"".$row['FRACTION_RANGE']."\">'+ v + '</span>';
                           } else {
                               return '';
                           } 
                       },";
                } else {
                    $cellFormatter = "formatter: gridAmountField,";
                }
                $bodyAlign = "align: 'right',";
                $headerAlign = "halign: 'right',";
                $filterBigDecimalInit[] = "input[name=" . $row['FIELD_PATH'] . "]";
            } elseif ($row['META_TYPE_CODE'] == 'boolean') {
                $cellFormatter = "formatter: gridBooleanField,";
                $bodyAlign = "align: 'center',";
                $headerAlign = "halign: 'center',";
            } elseif ($row['FIELD_PATH'] == 'filename') {      
                $cellFormatter = "formatter: function(v, r, i) {return dataViewFileView(v, r, i);},";
            } elseif ($row['FIELD_PATH'] == 'wfmstatusname') {
                $cellFormatter = "formatter: function(v, r, i) {return dataViewWfmStatusName(v, r, i, '".$this->metaDataId."', '".$this->refStructureId."');},";
            } elseif ($row['META_TYPE_CODE'] == 'file') {
                $cellFormatter = "formatter: gridFileField,";
                $bodyAlign = "align: 'center',";
            } elseif ($row['META_TYPE_CODE'] == 'password') {
                $cellFormatter = "formatter: gridPasswordField,";
                $bodyAlign = "align: 'center',";
                $headerAlign = "halign: 'center',";
            }  
        }

        if ($row['IS_SHOW'] == '0') {
            $hidden = 'hidden: true,';
        }
        if (isset($row['FIELD_NAME'])) {
            echo "{field:'" . $row['FIELD_NAME'] . "',title:'" . $this->lang->line($row['LABEL_NAME']) . "',sortable:true," . $hidden . $width . $cellStyle . $headerAlign . $bodyAlign . $cellFormatter . "},";
        } else {
            echo "{field:'" . $row['FIELD_PATH'] . "',title:'" . $this->lang->line($row['LABEL_NAME']) . "',sortable:true," . $hidden . $width . $cellStyle . $headerAlign . $bodyAlign . $cellFormatter . "},";
        }
    }
    
    if (count($filterDateInit) > 0) {
        $filterDateInit = '_thisGrid.datagrid("getPanel").children("div.datagrid-view")
            .find(".datagrid-htable")
            .find(".datagrid-filter-row")
            .find("' . implode(",", $filterDateInit) . '").addClass("dateMaskInit");';
    }
    if (count($filterDateTimeInit) > 0) {
        $filterDateTimeInit = '_thisGrid.datagrid("getPanel").children("div.datagrid-view")
                                .find(".datagrid-htable")
                                .find(".datagrid-filter-row")
                                .find("' . implode(",", $filterDateTimeInit) . '").addClass("dateMinuteMaskInit");';
    }
    if (count($filterBigDecimalInit) > 0) {
        $filterBigDecimalInit = '_thisGrid.datagrid("getPanel").children("div.datagrid-view")
                .find(".datagrid-htable")
                .find(".datagrid-filter-row")
                .find("' . implode(",", $filterBigDecimalInit) . '").addClass("bigdecimalInit");';
    }
    if (count($filterNumberInit) > 0) {
        $filterNumberInit = '_thisGrid.datagrid("getPanel").children("div.datagrid-view")
                .find(".datagrid-htable")
                .find(".datagrid-filter-row")
                .find("' . implode(",", $filterNumberInit) . '").addClass("longInit");';
    }
    if (count($filterTimeInit) > 0) {
        $filterTimeInit = '_thisGrid.datagrid("getPanel").children("div.datagrid-view")
                .find(".datagrid-htable")
                .find(".datagrid-filter-row")
                .find("' . implode(",", $filterTimeInit) . '").addClass("timeMaskInit").addClass("text-center");';
    }
}