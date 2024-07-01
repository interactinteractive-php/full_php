<?php if (!defined('_VALID_PHP')) exit('Direct access to this location is not allowed.');

class Mdstatement_model extends Model {

    private static $gfServiceAddress = GF_SERVICE_ADDRESS;

    public function __construct() {
        parent::__construct();
    }
    
    public function getDefaultStatementModel($metaDataId) {
        
        $srcMetaDataId = $metaDataId;
        $metaDataIdPh = $this->db->Param(0);
        
        $actionMetaDataId = $this->db->GetOne("
            SELECT 
                ACTION_META_DATA_ID 
            FROM CUSTOMER_DEFAULT_META 
            WHERE SRC_META_DATA_ID = $metaDataIdPh   
                AND IS_DEFAULT = 1 
                AND ACTION_META_DATA_ID IS NOT NULL", 
            array($srcMetaDataId)
        );
        
        if ($actionMetaDataId) {
            $metaDataId = $actionMetaDataId;
        }
        
        $row = $this->db->GetRow("
            SELECT 
                SL.META_DATA_ID,  
                ".$this->db->IfNull('SL.REPORT_NAME', 'MD.META_DATA_NAME')." AS REPORT_NAME, 
                SL.REPORT_TYPE, 
                SL.IS_AUTO_FILTER 
            FROM META_STATEMENT_LINK SL 
                INNER JOIN META_DATA MD ON MD.META_DATA_ID = SL.META_DATA_ID 
            WHERE SL.META_DATA_ID = $metaDataIdPh", 
            array($metaDataId)
        );

        if ($row) {
            $folder = $this->db->GetRow("SELECT FOLDER_ID FROM META_DATA_FOLDER_MAP WHERE META_DATA_ID = $metaDataIdPh", array($srcMetaDataId));
            
            if ($folder) {
                $row = array_merge($row, $folder);
            }
        }

        return $row;
    }

    public function getStatementRowModel($metaDataId) {
        
        $srcMetaDataId = $metaDataId;
        $metaDataIdPh  = $this->db->Param(0);
        
        $actionMetaDataId = $this->db->GetOne("
            SELECT 
                ACTION_META_DATA_ID 
            FROM CUSTOMER_DEFAULT_META 
            WHERE SRC_META_DATA_ID = $metaDataIdPh  
                AND IS_DEFAULT = 1 
                AND ACTION_META_DATA_ID IS NOT NULL", 
            array($srcMetaDataId) 
        );
        
        if ($actionMetaDataId) {
            $metaDataId = $actionMetaDataId;
        }
        
        if (Mdstatement::$isKpiIndicator) {
            
            $columnSelect = $this->db->IfNull('SL.REPORT_NAME', 'MD.NAME')." AS REPORT_NAME,";
            $columnSelect .= "KI.KPI_TYPE_ID,"; 
            
            $joinWhere = "INNER JOIN KPI_INDICATOR MD ON MD.ID = SL.MAIN_INDICATOR_ID 
                LEFT JOIN KPI_INDICATOR KI ON KI.ID = SL.DATA_INDICATOR_ID  
                LEFT JOIN RP_REPORT_LAYOUT RL ON RL.REPORT_LAYOUT_ID = SL.MAIN_INDICATOR_ID 
            WHERE ".(Mdstatement::$isKpiIndicator ? 'SL.MAIN_INDICATOR_ID' : 'SL.META_DATA_ID')." = $metaDataIdPh";
            
        } else {
            
            $columnSelect = $this->db->IfNull('SL.REPORT_NAME', 'MD.META_DATA_NAME')." AS REPORT_NAME,";
            
            $joinWhere = "INNER JOIN META_DATA MD ON MD.META_DATA_ID = SL.META_DATA_ID 
                LEFT JOIN RP_REPORT_LAYOUT RL ON RL.REPORT_LAYOUT_ID = SL.META_DATA_ID 
            WHERE ".(Mdstatement::$isKpiIndicator ? 'SL.MAIN_INDICATOR_ID' : 'SL.META_DATA_ID')." = $metaDataIdPh";
        }
        
        $row = $this->db->GetRow("
            SELECT 
                SL.META_DATA_ID,  
                SL.DATA_VIEW_ID, 
                SL.PROCESS_META_DATA_ID, 
                SL.GROUP_DATA_VIEW_ID, 
                SL.MAIN_INDICATOR_ID, 
                SL.DATA_INDICATOR_ID, 
                $columnSelect  
                SL.REPORT_TYPE, 
                ".$this->db->IfNull('SL.PAGE_SIZE', "'a4'")." AS PAGE_SIZE, 
                ".$this->db->IfNull('SL.PAGE_ORIENTATION', "'portrait'")." AS PAGE_ORIENTATION,  
                SL.PAGE_MARGIN_TOP, 
                SL.PAGE_MARGIN_LEFT, 
                SL.PAGE_MARGIN_RIGHT, 
                SL.PAGE_MARGIN_BOTTOM, 
                SL.PAGE_WIDTH,
                SL.PAGE_HEIGHT,
                SL.FONT_FAMILY, 
                null AS FONT_SIZE, 
                SL.IS_ARCHIVE,
                SL.IS_GROUP_MERGE,
                SL.IS_TIMETABLE,
                SL.ROW_EXPRESSION,
                SL.GLOBAL_EXPRESSION,
                SL.SUPER_GLOBAL_EXPRESSION, 
                SL.UI_EXPRESSION, 
                SL.UI_GROUP_EXPRESSION, 
                SL.UI_DETAIL_EXPRESSION, 
                SL.IS_HDR_REPEAT_PAGE,
                SL.IS_BLANK,
                SL.IS_SHOW_DV_BTN, 
                SL.IS_USE_SELF_DV, 
                SL.IS_AUTO_FILTER, 
                SL.DASHBOARD_META_ID, 
                (
                    SELECT 
                        COUNT(ID) 
                    FROM META_STATEMENT_LINK_GROUP 
                    WHERE META_STATEMENT_LINK_ID = SL.ID 
                        AND (IS_USER_OPTION = 1 OR IS_USER_OPTION = 2) 
                ) AS COUNT_USER_GROUPING, 
                RL.REPORT_LAYOUT_ID, 
                SL.IS_EXPORT_NO_FOOTER  
            FROM META_STATEMENT_LINK SL $joinWhere", 
            array($metaDataId)
        );

        return $row;
    }

    public function getStatementHtmlRowModel($metaDataId) {
        
        $row = $this->db->GetRow("
            SELECT 
                REPORT_HEADER, 
                PAGE_HEADER, 
                REPORT_DETAIL, 
                PAGE_FOOTER, 
                REPORT_FOOTER, 
                REPORT_DETAIL_FILE_PATH, 
                RENDER_TYPE, 
                FONT_SIZE, 
                FONT_FAMILY 
            FROM META_STATEMENT_LINK 
            WHERE ".(Mdstatement::$isKpiIndicator ? 'MAIN_INDICATOR_ID' : 'META_DATA_ID')." = " . $this->db->Param(0), 
            [$metaDataId]
        );
        
        $row['REPORT_HEADER'] = Str::cleanOut($row['REPORT_HEADER']);
        $row['PAGE_HEADER']   = Str::cleanOut($row['PAGE_HEADER']);
        $row['PAGE_FOOTER']   = Str::cleanOut($row['PAGE_FOOTER']);
        $row['REPORT_FOOTER'] = Str::cleanOut($row['REPORT_FOOTER']);
        
        if (!$row['REPORT_DETAIL'] && Mdstatement::$isKpiIndicator) {
            
            $generateTbl = self::renderKpiIndicatorHeaderColumns(Input::numeric('indicatorId'), ['fontSize' => $row['FONT_SIZE'], 'fontFamily' => $row['FONT_FAMILY']]);
            
            $row['REPORT_DETAIL'] = $generateTbl['reportDetail'];
            
            if (!$row['REPORT_FOOTER']) {
                $row['REPORT_FOOTER'] = $generateTbl['reportFooter'];
            }
        }
        
        $row['REPORT_DETAIL'] = str_replace('data-row-style=', 'style=', $row['REPORT_DETAIL']);
        
        return $row;
    }
    
    public function renderKpiIndicatorHeaderColumns($indicatorId, $attr = []) {
        
        if (Mdstatement::$isPivotView) {
            
            $schemaName = Config::getFromCache('kpiDbSchemaName');
            $schemaName = $schemaName ? rtrim($schemaName, '.').'.' : '';
            
            $jsonRow = $this->db->GetRow("SELECT * FROM ".$schemaName."V_16705727959689 WHERE SRC_RECORD_ID = ".$this->db->Param(0), [$indicatorId]);
            $jsonData = $jsonRow;
            $jsonObj = @json_decode($jsonRow['DATA'], true);
            
            if ($jsonObj) {
                $jsonData = array_merge($jsonData, $jsonObj);
                unset($jsonData['DATA']);
            }
            
            $orderBy          = 'KIIM.ORDER_NUMBER ASC, KIIM.COLUMN_NAME ASC';
            
            $allColAggregate  = strtolower(issetParam($jsonData['COLUMN_AGGREGATE']));
            $columnExpression = issetParamArray($jsonData['COLUMN_EXPRESSION']);
            $rowGroup         = Arr::sortBy('ROW_GROUP_ORDER', $jsonData['ROW_GROUP'], 'asc');
            $columnGroup      = issetParamArray($jsonData['COLUMN_GROUP']);
            $dataGroup        = issetParamArray($jsonData['DATA_GROUP']);
            $rowGroupMerge    = [];
            
            if ($rowGroupAggregate = issetParam($rowGroup[0]['ROW_GROUP_AGGREGATE'])) {
                
                $rowGroupAggregate = strtolower($rowGroupAggregate);
                $rowGroupField     = strtolower($rowGroup[0]['ROW_GROUP_FIELD']);
            }
            
            foreach ($rowGroup as $rowGroupRow) {
                
                $rowGroupFieldLower = strtolower($rowGroupRow['ROW_GROUP_FIELD']);
                
                if ($rowGroupFieldLower) {
                    if (array_key_exists('ROW_GROUP_IS_MERGE', $rowGroupRow)) {
                        $rowGroupMerge[$rowGroupFieldLower] = $rowGroupRow['ROW_GROUP_IS_MERGE'];
                    } else {
                        $rowGroupMerge[$rowGroupFieldLower] = 0;
                    }
                }
            }
            
            if ($columnExpression) {
                
                $tempColumnExpression = $tempDataGroup = [];
                
                foreach ($columnExpression as $columnExpressionRow) {
                    
                    $columnExpressionRow['EXPRESSION_FIELD'] = trim($columnExpressionRow['EXPRESSION_FIELD']);
                    $columnExpressionRow['EXPRESSION_STRING'] = trim($columnExpressionRow['EXPRESSION_STRING']);
                    
                    if ($columnExpressionRow['EXPRESSION_FIELD'] && $columnExpressionRow['EXPRESSION_STRING']) {
                        
                        $expressionFieldLower = strtolower($columnExpressionRow['EXPRESSION_FIELD']);
                        $tempColumnExpression[$expressionFieldLower] = $columnExpressionRow;
                    }
                }
                
                $columnExpression = $tempColumnExpression;
                
                foreach ($dataGroup as $dataGroupRow) {
                        
                    if ($dataGroupRow['DATA_GROUP_FIELD'] != '') {
                        $tempDataGroup[] = 1;
                    }
                }

                $dataGroup      = $tempDataGroup;
                $dataGroupCount = count($dataGroup);
            }
            
            if (count($columnGroup) == 1 && count($dataGroup) == 1) {
                $columnGroupRotate = strtolower(issetParam($columnGroup[0]['COLUMN_GROUP_ROTATE']));
            }
            
        } else {
            $orderBy = 'KIIM.ORDER_NUMBER ASC';
        }
        
        $where = '';
        
        if (Mdform::$mvPivotColumnFilter) {
            
            $where = ' AND (KIIM.SIDEBAR_NAME IS NULL OR ';
            
            foreach (Mdform::$mvPivotColumnFilter as $filterColName => $filterColData) {
                $where .= "KIIM.SIDEBAR_NAME IN ('".Arr::implode_r("','", $filterColData, true)."')";
            }
            
            $where .= ')';
        }
        
        $data = $this->db->GetAll("
            SELECT 
                LOWER(KIIM.COLUMN_NAME) AS COLUMN_NAME, 
                KIIM.LABEL_NAME, 
                KIIM.COLUMN_WIDTH, 
                KIIM.INPUT_NAME, 
                KIIM.SHOW_TYPE, 
                KIIM.TRG_INDICATOR_ID AS LOOKUP_META_DATA_ID, 
                KIIM.COLUMN_AGGREGATE, 
                KIIM.EXPRESSION_STRING, 
                KIIM.BODY_ALIGN, 
                KIIM.MERGE_TYPE, 
                KIIM.SIDEBAR_NAME, 
                KIIM.IS_EDITABLE_VALUE, 
                KIIM.SEMANTIC_TYPE_ID, 
                KIIM.SRC_INDICATOR_PATH, 
                KIIM.AGGREGATE_FUNCTION, 
                LOWER(KIIM.TRG_ALIAS_NAME) AS TRG_ALIAS_NAME 
            FROM KPI_INDICATOR_INDICATOR_MAP KIIM 
                LEFT JOIN KPI_INDICATOR KI ON KIIM.TRG_INDICATOR_ID = KI.ID 
                LEFT JOIN META_SEMANTIC_TYPE MST ON KIIM.SEMANTIC_TYPE_ID = MST.ID 
            WHERE KIIM.MAIN_INDICATOR_ID = ".$this->db->Param(0)." 
                AND KIIM.PARENT_ID IS NULL 
                AND ".$this->db->IfNull('KIIM.IS_INPUT', '0')." = 1 
                AND KIIM.IS_RENDER = 1 
                AND KIIM.SHOW_TYPE NOT IN ('row', 'rows') 
                AND KIIM.COLUMN_NAME IS NOT NULL 
                AND KIIM.COLUMN_NAME <> 'ID' 
                $where 
            ORDER BY $orderBy", 
            [$indicatorId]
        );
        
        $tbl = $tblBody = $tblFoot = $reportFooterTbl = $mergeHead = 
        $checkMerge = $pivotColumns = $groupingField = $allColAggregateField = [];
        
        $isRowStyle = false;
        $rowGroupCount = $pivotColumnCheckLoop = 0;
        $tblStyle = '';
        
        if ($fontFamily = issetParam($attr['fontFamily'])) {
            $tblStyle .= 'font-family: '.$fontFamily.';';
        }
        
        if ($fontSize = issetParam($attr['fontSize'])) {
            $tblStyle .= 'font-size: '.$fontSize.';';
        } 
        
        $tbl[] = '<table border="1" style="width: 100%;'.$tblStyle.'"[tableAttr]>';
        
            $tbl[] = '<thead>';
                $tbl[] = '<tr>';
                    $tbl[] = '<th style="width: 30px; text-align: center; background-color: #c6e0b3;"[rowspan2]>№</th>';
                    
                    $tblBody[] = '<td style="text-align: center">#rownum#</td>';
                    $tblFoot[] = '<td style="width: 30px"></td>';
                    
                    foreach ($data as $row) {
                        
                        $style = $attr = $hdrStyle = '';
                        
                        $columnWidth = trim($row['COLUMN_WIDTH']);
                        $bodyAlign = trim($row['BODY_ALIGN']);
                        $showType = $row['SHOW_TYPE'];
                        $mergeType = $row['MERGE_TYPE'];
                        $labelName = trim($row['LABEL_NAME']);
                        $columnName = trim($row['COLUMN_NAME']);
                        $sidebarName = trim($row['SIDEBAR_NAME']);
                        $isEditableValue = trim($row['IS_EDITABLE_VALUE']);
                        
                        if ($bodyAlign) {
                            $style = 'text-align: '.$bodyAlign.';';
                        } elseif ($showType == 'percent' || $showType == 'decimal' || $showType == 'bigdecimal' || $showType == 'bigdecimal_null' || $showType == 'number') {
                            $style = 'text-align: right;';
                        } 
                        
                        if ($columnWidth) {
                            if (is_numeric($columnWidth)) {
                                $hdrStyle .= 'width:'.$columnWidth.'px;';
                            } else {
                                $hdrStyle .= 'width:'.$columnWidth.';';
                            }
                        } 
                        
                        if ($mergeType == 'column') {
                            $attr = ' data-merge-cell="true"';
                        }
                        
                        if ($isEditableValue == '1') {
                            $attr .= ' contenteditable="true"';
                        }
                        
                        if (Mdstatement::$isPivotView) {
                            
                            $typeId = $row['SEMANTIC_TYPE_ID'];
                            $rowColumnVal = '#'.$columnName.'#';
                            $isMerge = issetParam($rowGroupMerge[$columnName]);
                            
                            $rowSpan = '';
                            
                            if ($typeId == '10000000') { /*Мөр*/
                                
                                if ($columnName == 'row_style') { 
                                    
                                    $isRowStyle = true;
                                    
                                    continue;
                                }
                                
                                if ($isMerge) {
                                    $attr .= ' data-merge-cell="true"';
                                    $rowSpan = '[rowspan2] data-merge-cell="true"';
                                } else {
                                    $rowSpan = '[rowspan2]';
                                }
                                
                                if (!$columnWidth) {
                                    $hdrStyle .= 'width: 150px;';
                                }
                                
                                $hdrStyle .= 'background-color: #c6e0b3;';
                                
                                $rowGroupCount ++;
                                
                            } else { /*Багана*/
                                
                                $trgAliasName = $row['TRG_ALIAS_NAME'];
                                $aggrFnc      = $row['AGGREGATE_FUNCTION'];
                                $srcPath      = $row['SRC_INDICATOR_PATH'];
                                $rowSpan      = ' data-merge-cell="true"';
                                
                                if (!$columnWidth) {
                                    $hdrStyle .= 'width: 100px;';
                                }
                                
                                if ($aggrFnc && $srcPath) {
                                    
                                    $hdrStyle .= 'background-color: #c6e0b3;';
                                    $style .= 'background-color: #c6e0b3;font-weight:bold;';
                                    
                                } elseif ($aggrFnc && !$srcPath) {
                                    
                                    $style .= 'background-color: #f4b084;font-weight:bold;';
                                    $hdrStyle .= 'background-color: #f4b084;font-weight:bold;';
                                    
                                } else {
                                    $hdrStyle .= 'background-color: #c6e0b3;';
                                }
                                
                                if (strpos($labelName, '|') === false) {
                                    
                                    $rowSpan = '[rowspan2]';
                                    
                                } else {
                                    
                                    $labelNameArr = explode('|', $labelName);
                                    $labelName = $labelNameArr[0];

                                    unset($labelNameArr[0]);

                                    foreach ($labelNameArr as $l => $lName) {
                                        
                                        $pivotColumns[$l][] = [
                                            'labelName' => $lName, 
                                            'style'     => $hdrStyle
                                        ];
                                    }
                                }
                                
                                if (isset($rowGroupField) && $rowGroupField) {
                                    
                                    if (isset($columnExpression[$trgAliasName])) {
                                        
                                        $expressionString = $columnExpression[$trgAliasName]['EXPRESSION_STRING'];
                                        
                                        $columnVal = self::pivotColumnExpression(
                                            [
                                                'data' => $data, 
                                                'sidebarName' => $sidebarName, 
                                                'expressionString' => $expressionString, 
                                                'aggregateFnc' => $rowGroupAggregate, 
                                                'columnName' => $columnName
                                            ]
                                        );
                                        
                                    } else {
                                        $columnVal = $rowGroupAggregate.'(#'.$columnName.'#)';
                                    }
                                    
                                    $groupingField[] = '<td style="font-weight:bold;'.$style.'"'.$attr.'>'.$columnVal.'</td>';
                                }
                                
                                if ($allColAggregate) {
                                    
                                    if (isset($columnExpression[$trgAliasName])) {
                                        
                                        $expressionString = $columnExpression[$trgAliasName]['EXPRESSION_STRING'];
                                        
                                        $columnVal = self::pivotColumnExpression(
                                            [
                                                'data' => $data, 
                                                'sidebarName' => $sidebarName, 
                                                'expressionString' => $expressionString, 
                                                'aggregateFnc' => $allColAggregate, 
                                                'columnName' => $columnName
                                            ]
                                        );
                                        
                                    } else {
                                        $columnVal = $allColAggregate.'(#'.$columnName.'#)';
                                    }
                                    
                                    $allColAggregateField[] = '<td style="font-weight:bold;background-color: #f4b084;'.$style.'"'.$attr.'>'.$columnVal.'</td>';
                                }
                                
                                if ($aggrFnc && !$srcPath && isset($columnExpression[$trgAliasName])) {
                                    
                                    $expressionString = $columnExpression[$trgAliasName]['EXPRESSION_STRING'];
                                        
                                    $rowColumnVal = self::pivotColumnExpression(
                                        [
                                            'data' => $data, 
                                            'sidebarName' => $sidebarName, 
                                            'expressionString' => $expressionString, 
                                            'aggregateFnc' => '', 
                                            'columnName' => $columnName
                                        ]
                                    );
                                }
                                
                                if (isset($columnGroupRotate) && $columnGroupRotate) {
                                    $hdrStyle .= 'vertical-align: bottom;text-align: center;';
                                    if ($columnGroupRotate == 'right') {
                                        $labelName = '<div class="right-rotate-span" style="display:inline;height:120px;-webkit-writing-mode:vertical-rl; -ms-writing-mode:tb-rl; writing-mode:vertical-rl;">'.$labelName.'</div>';
                                    } elseif ($columnGroupRotate == 'left') {
                                        $labelName = '<div class="left-rotate-span" style="display:inline;height:120px;-webkit-writing-mode:vertical-rl; -ms-writing-mode:tb-rl; writing-mode:vertical-rl;transform: rotate(180deg);">'.$labelName.'</div>';
                                    }
                                }
                            }
                             
                            $tblBody[] = '<td style="'.$style.'"'.$attr.'>'.$rowColumnVal.'</td>';
                            
                            $tbl[] = '<th style="text-align: center;'.$hdrStyle.'"'.$rowSpan.'>'.$labelName.'</th>';
                            
                        } else {
                            
                            if ($columnName == 'row_style') { 
                                    
                                $isRowStyle = true;

                                continue;
                            }
                            
                            if ($showType == 'combo' || $showType == 'radio') {
                                $columnName .= '_desc';
                            }
                            
                            $hdrStyle .= 'background-color: #c6e0b3;';
                            
                            if ($sidebarName != '') {
                            
                                $isMergeHead = true;
                                $mergeName = $sidebarName;

                                if (isset($checkMerge[$mergeName])) {

                                    $checkMerge[$mergeName] += 1;

                                } else {

                                    $checkMerge[$mergeName] = 1;

                                    $tbl[] = '<th style="text-align: center;'.$hdrStyle.'"[colspan'.$mergeName.']>'.$sidebarName.'</th>';
                                }

                                $mergeHead[] = '<th style="text-align: center;'.$hdrStyle.'">'.$labelName.'</th>';

                            } else {
                                $tbl[] = '<th style="text-align: center;'.$hdrStyle.'"[rowspan2]>'.$labelName.'</th>';
                            }

                            $tblBody[] = '<td style="'.$style.'"'.$attr.'>#'.$columnName.'#</td>';

                            $tblFoot[] = '<td style="text-align:right;font-weight:bold;'.$hdrStyle.'">';

                                if ($row['COLUMN_AGGREGATE']) {

                                    $idTblFoot = true;
                                    $tblFoot[] = $row['COLUMN_AGGREGATE'].'(#'.$columnName.'#)';
                                }

                            $tblFoot[] = '</td>';
                        }
                    }
            
                $tbl[] = '</tr>';
                
                if (Mdstatement::$isPivotView) {
                    
                    foreach ($pivotColumns as $pivotCols) {
                        
                        $tbl[] = '<tr>';
                            foreach ($pivotCols as $pivotCol) {
                                $tbl[] = '<th style="'.$pivotCol['style'].'text-align: center;" data-merge-cell="true">'.$pivotCol['labelName'].'</th>';
                            }
                        $tbl[] = '</tr>';
                    }
                }
                
                if (isset($isMergeHead)) {
                    $tbl[] = '<tr style="background-color: #c6e0b3">';
                        $tbl[] = implode('', $mergeHead);
                    $tbl[] = '</tr>';
                }
                
            $tbl[] = '</thead>';
            
            $tbl[] = '<tbody>';
                $tbl[] = '<tr'.($isRowStyle ? ' data-row-style="#row_style#"' : '').'>';
                    $tbl[] = implode('', $tblBody);
                $tbl[] = '</tr>';
            $tbl[] = '</tbody>';
            
            if (isset($idTblFoot)) {
                
                $tblFootCells = implode('', $tblFoot);
                
                $tbl[] = '<tfoot>';
                    $tbl[] = '<tr style="background-color: #c6e0b3">';
                        $tbl[] = $tblFootCells;
                    $tbl[] = '</tr>';
                $tbl[] = '</tfoot>';
                
                $reportFooterTbl[] = '<br /><br />';
                $reportFooterTbl[] = '<table border="1" style="width: 100%;'.$tblStyle.'">';
                    $reportFooterTbl[] = '<tbody>';
                        $reportFooterTbl[] = '<tr style="background-color: #c6e0b3">';
                            $reportFooterTbl[] = $tblFootCells;
                        $reportFooterTbl[] = '</tr>';
                    $reportFooterTbl[] = '</tbody>';
                $reportFooterTbl[] = '</table>';
            }
            
        $tbl[] = '</table>';
        
        $reportDetail = implode('', $tbl);
        
        if (isset($isMergeHead)) {
            
            $reportDetail = str_replace('[rowspan2]', ' rowspan="2"', $reportDetail);
            
            foreach ($checkMerge as $checkMergeName => $checkMergeCount) {
                $reportDetail = str_replace('[colspan'.$checkMergeName.']', ' colspan="'.$checkMergeCount.'"', $reportDetail);
            }
            
            $reportDetail = str_replace('[tableAttr]', ' class="no-freeze"', $reportDetail);
            
        } elseif (Mdstatement::$isPivotView) {
            
            $rowSpanCount = count($pivotColumns) + 1;
            
            $reportDetail = str_replace('[tableAttr]', ' class="no-freeze"', $reportDetail);
            $reportDetail = str_replace('[rowspan2]', ' rowspan="'.$rowSpanCount.'"', $reportDetail);
            
            Mdstatement::$freezeLeftColumnCount = ($rowGroupCount + 1);
            
            if ($groupingField) {
                
                $pivotViewGroupingFooter = [];
                
                $pivotViewGroupingFooter[] = '<tr style="background-color: #c6e0b3">';
                    $pivotViewGroupingFooter[] = '<td style="text-align: right;font-weight:bold;" colspan="'.Mdstatement::$freezeLeftColumnCount.'">Нийт:</td>';
                    $pivotViewGroupingFooter[] = implode('', $groupingField);
                $pivotViewGroupingFooter[] = '</tr>';
                
                Mdstatement::$pivotGrouping[] = [
                    'GROUP_FIELD_PATH' => $rowGroupField,
                    'GROUP_HEADER' => null,
                    'GROUP_FOOTER' => implode('', $pivotViewGroupingFooter), 
                    'HEADER_BG_COLOR' => null,
                    'FOOTER_BG_COLOR' => null,
                ];
            }
            
            if ($allColAggregate) {
                
                $allColAggregateMask = issetDefaultVal($jsonData['COLUMN_AGGREGATE_MASK'], 'Нийт');
                $reportFooterTbl     = [];
                
                $reportFooterTbl[] = '<tr>';
                    $reportFooterTbl[] = '<td style="background-color: #f4b084;text-align:right;font-weight:bold;" colspan="'.Mdstatement::$freezeLeftColumnCount.'">'.$allColAggregateMask.':</td>';
                    $reportFooterTbl[] = implode('', $allColAggregateField);
                $reportFooterTbl[] = '</tr>';
                
                Mdstatement::$tmpReportFooter = implode('', $reportFooterTbl);
                
                $reportFooterTbl = [];
            }
            
        } else {
            
            $reportDetail = str_replace('[rowspan2]', '', $reportDetail);
            $reportDetail = str_replace('[tableAttr]', '', $reportDetail);
        }
        
        return ['reportDetail' => $reportDetail, 'reportFooter' => implode('', $reportFooterTbl)];
    }
    
    public function pivotColumnExpression($arr) {
        
        $data = $arr['data'];
        $sidebarName = $arr['sidebarName'];
        $expressionString = $arr['expressionString'];
        $aggregateFnc = $arr['aggregateFnc'];
        $columnName = $arr['columnName'];
        
        preg_match_all('/\[(.*?)\]/', $expressionString, $expressionMatch);
        
        if (isset($expressionMatch[0][0])) {
            
            foreach ($expressionMatch[0] as $k => $v) {
                
                $fieldLower = strtolower($expressionMatch[1][$k]);
                $replacePath = '';
                
                foreach ($data as $row) {
                    if ($row['SIDEBAR_NAME'] == $sidebarName && $row['TRG_ALIAS_NAME'] == $fieldLower) {
                        $replacePath = $row['COLUMN_NAME'];
                        break;
                    }
                }
                
                if ($aggregateFnc) {
                    $expressionString = str_replace($v, 'defaultValue('.$aggregateFnc.'(#'.$replacePath.'#)|0)', $expressionString);
                } else {
                    $expressionString = str_replace($v, 'defaultValue(#'.$replacePath.'#|0)', $expressionString);
                }
            }
        }
        
        if (strpos($expressionString, 'return') !== false) {
            return "runExp[$expressionString]";
        } else {
            return "runExp[return $expressionString;]";
        }
    }
    
    public function getTypeCodeDataViewParamsModel($dataViewId) {
        
        if ($dataViewId) {
            
            if (Mdstatement::$isKpiIndicator) {
                
                $data = $this->db->GetAll("
                    SELECT 
                        LOWER(KIIM.COLUMN_NAME) AS FIELD_PATH, 
                        KIIM.SHOW_TYPE AS META_TYPE_CODE, 
                        null AS LOOKUP_TYPE, 
                        KIIM.TRG_INDICATOR_ID AS LOOKUP_META_DATA_ID, 
                        null AS FRACTION_RANGE 
                    FROM KPI_INDICATOR_INDICATOR_MAP KIIM 
                        LEFT JOIN KPI_INDICATOR KI ON KIIM.TRG_INDICATOR_ID = KI.ID 
                        LEFT JOIN META_SEMANTIC_TYPE MST ON KIIM.SEMANTIC_TYPE_ID = MST.ID 
                    WHERE KIIM.MAIN_INDICATOR_ID = ".$this->db->Param(0)." 
                        AND KIIM.PARENT_ID IS NULL 
                        AND ".$this->db->IfNull('KIIM.IS_INPUT', '0')." = 1 
                        AND KIIM.SHOW_TYPE NOT IN ('row', 'rows') 
                        AND KIIM.COLUMN_NAME IS NOT NULL 
                        AND KIIM.COLUMN_NAME <> 'ID' 
                    ORDER BY KIIM.ORDER_NUMBER ASC", 
                    array($dataViewId)
                );
                
            } else {
                $data = $this->db->GetAll("
                    SELECT 
                        LOWER(FIELD_PATH) AS FIELD_PATH,
                        DATA_TYPE AS META_TYPE_CODE, 
                        LOOKUP_TYPE,     
                        LOOKUP_META_DATA_ID, 
                        FRACTION_RANGE 
                    FROM META_GROUP_CONFIG 
                    WHERE MAIN_META_DATA_ID = ".$this->db->Param(0)." 
                        AND PARENT_ID IS NULL 
                        AND DATA_TYPE <> 'group'", 
                    array($dataViewId)
                ); 
            }

            $array['rid'] = ''; 

            foreach ($data as $row) {

                if ($row['FRACTION_RANGE'] != '') {

                    $array[$row['FIELD_PATH']] = 'scale';
                    Mdstatement::$dataViewColumnsTypeScale[$row['FIELD_PATH']] = $row['FRACTION_RANGE'];

                } else {
                    if ($row['META_TYPE_CODE'] == 'combo') {
                        $array[$row['FIELD_PATH'] . '_desc'] = 'string';
                    } else {
                        $array[$row['FIELD_PATH']] = ($row['META_TYPE_CODE'] == 'text') ? 'string' : $row['META_TYPE_CODE'];
                    }
                }
            }

            Mdstatement::$dataViewColumnsType = $array;
            
        } else {
            $data = array();
        }

        return $data;
    }
    
    public function getTypeCodeColumnModel($dataViewColumnsType, $column) {
        
        if (isset($dataViewColumnsType[$column])) {
            return $dataViewColumnsType[$column];
        }
        
        return 'string';
    }

    public function setParamsValueModel($dataViewColumnsType, $params) {
        
        $array = array();
        $params = array_change_key_case($params, CASE_LOWER);
        
        foreach ($dataViewColumnsType as $row) {

            if (isset($params[$row['FIELD_PATH']])) {

                $v = $params[$row['FIELD_PATH']];
                $k = $row['FIELD_PATH'];

                if ($row['LOOKUP_TYPE'] == 'popup' && $row['LOOKUP_META_DATA_ID'] != '' && $v) {
                    
                    if (is_array($v)) {
                        
                        if (count($v) > 1 && $v[0]) {
                            
                            $valueRow = (new Mddatamodel())->getIdCodeName($row['LOOKUP_META_DATA_ID'], $v);
                            $array[$k] = isset($valueRow['name']) ? $valueRow['code'].' - '.$valueRow['name'] : '';
                            
                        } elseif ($v[0]) {
                            
                            if (isset(Mdstatement::$filterParamsLower[$row['FIELD_PATH'].'_displayfield'])) {
                                $array[$k] = Mdstatement::$filterParamsLower[$row['FIELD_PATH'].'_displayfield'].' - '.Mdstatement::$filterParamsLower[$row['FIELD_PATH'].'_namefield'];
                            } else {
                                $array[$k] = '';
                            }
                            
                        } else {
                            $array[$k] = '';
                        }
                        
                    } else {
                        if (isset(Mdstatement::$filterParamsLower[$row['FIELD_PATH'].'_displayfield'])) {
                            
                            $array[$k] = Mdstatement::$filterParamsLower[$row['FIELD_PATH'].'_displayfield'].' - '.Mdstatement::$filterParamsLower[$row['FIELD_PATH'].'_namefield'];
                            
                        } else {
                            
                            $valueRow = (new Mddatamodel())->getIdCodeName($row['LOOKUP_META_DATA_ID'], $v);
                            $array[$k] = isset($valueRow['name']) ? $valueRow['code'].' - '.$valueRow['name'] : '';
                        }
                    }

                } elseif (($row['LOOKUP_TYPE'] == 'combo' || $row['LOOKUP_TYPE'] == 'combo_with_popup') && $row['LOOKUP_META_DATA_ID'] != '' && $v) {
                    
                    if (is_array($v)) {
                        
                        if (count($v) > 1 && $v[0]) {
                            
                            $valueRow = (new Mddatamodel())->getIdCodeName($row['LOOKUP_META_DATA_ID'], $v);
                            $array[$k] = isset($valueRow['name']) ? $valueRow['name'] : '';
                            
                        } elseif ($v[0]) {
                            
                            $valueRow = (new Mddatamodel())->getIdCodeName($row['LOOKUP_META_DATA_ID'], $v[0]);
                            $array[$k] = isset($valueRow['name']) ? $valueRow['name'] : '';
                            
                        } else {
                            $array[$k] = '';
                        }
                        
                    } else {
                        $valueRow = (new Mddatamodel())->getIdCodeName($row['LOOKUP_META_DATA_ID'], $v);
                        $array[$k] = isset($valueRow['name']) ? $valueRow['name'] : '';
                    }

                } elseif ($v != '') {
                    
                    $array[$k] = $v;
                    
                } else {
                    $array[$k] = '';
                }
            }
        }

        return $array;
    }

    public function getDataViewDataModel($dataViewId, $params, $getRowStatement = array()) {
        
        $this->load->model('mdobject', 'middleware/models/');

        $param = array(
            'systemMetaGroupId' => $dataViewId,
            'showQuery' => 1, 
            'showQueryWithParameter' => 1, 
            'ignorePermission' => 1 
        );
        
        /*if (Input::postCheck('detectGroupingUserOption') && Input::postCheck('groupingUserOption') && Input::postCheck('groupingUserOptionNotChecked')) {
            
            $groupingUserOptionNotChecked = $_POST['groupingUserOptionNotChecked'];

            foreach ($groupingUserOptionNotChecked as $groupingField) {
                $param['grouping'][] = $groupingField; 
            }
        }*/
        
        $gridOption = $this->model->getDVGridOptionsModel($dataViewId);
        
        if (!empty($gridOption['SORTNAME']) && !empty($gridOption['SORTORDER'])) {
            
            $defaultSort[$gridOption['SORTNAME']] = array('sortType' => $gridOption['SORTORDER']);
            
            if (isset($param['paging']['sortColumnNames'])) {
                $param['paging']['sortColumnNames'] = array_merge($param['paging']['sortColumnNames'], $defaultSort);
            } else {
                $param['paging']['sortColumnNames'] = $defaultSort;
            }
        }

        if (!empty($params)) {

            $paramCriteria = array();

            foreach ($params as $key => $value) {

                if (is_array($value)) {
                    
                    $paramVals = Arr::implode_r(',', $value, true);
                            
                    if ($paramVals !== '') {

                        $getTypeCode = $this->model->getDataViewGridCriteriaRowModel($dataViewId, $key);

                        if ($getTypeCode && $getTypeCode['DEFAULT_OPERATOR'] === '!=') {
                            $paramCriteria[$key][] = array(
                                'operator' => 'NOT IN',
                                'operand' => $paramVals
                            );                            
                        } else {
                            $paramCriteria[$key][] = array(
                                'operator' => 'IN',
                                'operand' => $paramVals
                            );
                        }
                    }

                } else {

                    $paramVal = Input::param(trim($value));

                    if ($paramVal != '') {

                        $getTypeCode = $this->model->getDataViewGridCriteriaRowModel($dataViewId, $key);

                        if ($getTypeCode) {

                            $typeLower = $getTypeCode['META_TYPE_CODE'];

                            if ($typeLower == 'date' || $typeLower == 'datetime') {

                                $paramVal = str_replace(
                                    array('____-__-__', '___-__-__', '__-__-__', '_-__-__', '-__-__', '-__', '_', '__:__', ':__'), '', $paramVal
                                );

                            } elseif ($typeLower == 'bigdecimal') {

                                $paramVal = str_replace('.00', '', Number::decimal($paramVal));
                            }
                            
                            if ($key == 'accountCode' || $key == 'filterAccountCode') {
                                $paramVal = trim(str_replace('_', '', str_replace('_-_', '', $paramVal)));
                            }

                            $operator = '=';

                            if (isset($_POST['criteriaCondition'][$key])) {
                                
                                $operator = strtolower($_POST['criteriaCondition'][$key]);
                                
                                if ($operator == 'like') {
                                    $paramVal = '%'.$paramVal.'%';
                                } elseif ($operator == 'start') {
                                    $operator = 'like';
                                    $paramVal = $paramVal.'%';
                                } elseif ($operator == 'end') {
                                    $operator = 'like';
                                    $paramVal = '%'.$paramVal;
                                } elseif ($operator == '!=') {
                                    if ($typeLower == 'bigdecimal' || $typeLower == 'decimal' 
                                        || $typeLower == 'integer' || $typeLower == 'long' || $typeLower == 'number') {
                                        $operator = '!=';
                                        $paramVal = $paramVal;
                                    } else {
                                        $operator = 'not like';
                                        $paramVal = '%'.$paramVal.'%';
                                    }
                                }
                            }

                            $paramCriteria[$key][] = array(
                                'operator' => $operator,
                                'operand' => $paramVal
                            );
                        }
                    }
                }
            }

            $param['criteria'] = $paramCriteria;
        }
        
        if (Input::postCheck('filterRules')) {
            
            $filterRules = json_decode(Str::cp1251_utf8($_POST['filterRules']), true);

            if (count($filterRules) > 0) {

                $paramFilter = array();

                foreach ($filterRules as $rule) {

                    $field = $rule['field'];
                    $value = Input::param(trim($rule['value']));

                    $getTypeCode = $this->model->getDataViewGridCriteriaRowModel($dataViewId, $field);
                    $getTypeCodeLower = $getTypeCode['META_TYPE_CODE'];
                    $operatorFilter = 'LIKE';

                    if ($getTypeCodeLower == 'date' || $getTypeCodeLower == 'datetime') {

                        $value = str_replace(
                            array('____-__-__', '___-__-__', '__-__-__', '_-__-__', '-__-__', '-__', '_', '__:__', ':__'), '', $value
                        );

                    } elseif ($getTypeCodeLower == 'bigdecimal') {

                        $value = str_replace('.00', '', Number::decimal($value));

                    } elseif ($field == 'accountcode') {

                        $value = trim(str_replace('_', '', str_replace('_-_', '', $value)));
                    }

                    $paramFilter[$field][] = array(
                        'operator' => $operatorFilter,
                        'operand' => $operatorFilter === 'LIKE' ? '%'.$value.'%' : $value
                    );
                }

                if (isset($param['criteria'])) {
                    $param['criteria'] = array_merge($param['criteria'], $paramFilter);
                } else {
                    $param['criteria'] = $paramFilter;
                }
            }
        }
        
        if (Config::getFromCache('CONFIG_ACCOUNT_SEGMENT') && Input::postCheck('accountSegmentFullCode')) {
                
            $accountSegmentFilter = array();
            $accountSegmentFullCode = $_POST['accountSegmentFullCode'];

            foreach ($accountSegmentFullCode as $accSgmtPath => $accSgmtVal) {
                $accountSegmentFilter[$accSgmtPath.'SegmentCode'][] = array(
                    'operator' => 'like',
                    'operand' => $accSgmtVal
                );
            }

            if (isset($param['criteria'])) {
                $param['criteria'] = array_merge($param['criteria'], $accountSegmentFilter);
            } else {
                $param['criteria'] = $accountSegmentFilter;
            }
        }
        
        if (Input::postCheck('idWithComma')) {
            
            includeLib('Compress/Compression');
            $idWithComma = Input::post('idWithComma');
            
            foreach ($idWithComma as $commaKey => $commaVal) {
                $param['criteria'][$commaKey][] = array(
                    'operator' => 'IN',
                    'operand' => Compression::gzinflate($commaVal)
                );
            }
        }
        
        if (Input::isEmpty('showPivot') == false) {
            
            unset($param['showQuery']);
            unset($param['showQueryWithParameter']);
            
            $param['showReport'] = 1;
            $param['__isUseReport'] = 1;
            
        } elseif (isset($gridOption['IS_USE_RESULT']) && $gridOption['IS_USE_RESULT'] == '1') {
            
            $listResult = self::dvListResult($param, $params);
            return $listResult;
        }
        
        if (DB_DRIVER == 'postgres9') {
            unset($param['showQueryWithParameter']);
        }
        
        $data = $this->ws->runResponse(self::$gfServiceAddress, Mddatamodel::$getDataViewCommand, $param);
        
        if ($data['status'] == 'success' && isset($data['result'])) {
            
            $this->load->model('mdstatement', 'middleware/models/');
            
            if (isset($param['showReport'])) {
                
                $response = array('status' => 'success', 'reportId' => $data['result']);
                
                if (Input::isEmpty('subDvId') == false) {
                    
                    $param['systemMetaGroupId'] = Input::post('subDvId');
                    
                    $expandDvResult = $this->ws->runResponse(self::$gfServiceAddress, Mddatamodel::$getDataViewCommand, $param);
                    
                    if ($expandDvResult['status'] == 'success' && isset($expandDvResult['result'])) {
                        
                        $response['expandReportId'] = $expandDvResult['result'];
                        
                    } else {
                        return array('status' => 'error', 'message' => $this->ws->getResponseMessage($expandDvResult));
                    }
                }
                
                return $response;
                
            } else {
                
                try {
                    
                    if (DB_DRIVER == 'postgres9') {
                        $query = $data['result'];
                        $bindParams = [];
                    } else {
                        $queryBindParam = DBSql::dataViewQueryBindParams($data['result']);
                    
                        $query          = $queryBindParam['query'];
                        $bindParams     = $queryBindParam['bindParams'];
                    }
                    
                    $reportConn     = (issetParam($getRowStatement['PROCESS_META_DATA_ID'])) ? [] : self::getReportDatabaseConnection();
                    
                    $sql = 'SELECT '. ((DB_DRIVER == 'postgres9') ? 'ROW_NUMBER () OVER ()' : 'ROWNUM') .' AS RID, PDD.* FROM ('.$query.') PDD';

                    if ($reportConn) {
                        
                        $reportDbDriver = $reportConn['DB_TYPE'] == 'oracle' ? 'oci8' : $reportConn['DB_TYPE'];
                        $reportDbSid = $reportConn['SID'];
                        $reportDbName = $reportDbSid ? $reportDbSid : $reportConn['SERVICE_NAME']; 
                        $reportDbHost = $reportConn['HOST_NAME']; 
                        $reportDbUserName = $reportConn['USER_NAME']; 
                        $reportDbUserPass = $reportConn['USER_PASSWORD']; 
                        
                        $rdb = ADONewConnection($reportDbDriver);
                        $rdb->debug = DB_DEBUG;
                        $rdb->connectSID = $reportDbSid ? true : false;
                        $rdb->autoRollback = true;
                        $rdb->datetime = true;

                        $rdb->Connect($reportDbHost, $reportDbUserName, $reportDbUserPass, $reportDbName);

                        $rdb->SetCharSet(DB_CHATSET);

                        $rdb->StartTrans(); 
                        
                        if (isset($param['criteria'])) {
                            
                            $param = Arr::changeKeyLower($param);
                            $groupParam = array();
                            
                            if (is_array($data['result']['parameters']) && isset($data['result']['parameters']['0']['name'])) {
                                $groupParam = Arr::groupByArray($data['result']['parameters'], 'name');
                            }

                            foreach ($param['criteria'] as $pkrey => $prow) {
                                if ($prow[0]['operator'] == 'IN') {
                                    $extractVal = explode(',', $prow[0]['operand']);
                                    if (count($extractVal) > 10 && array_key_exists($pkrey, $groupParam)) {
                                        foreach ($extractVal as $extVal) {
                                            $rdb->Execute("INSERT INTO TMP_IN_CRITERIA_TABLE (VALUE, FIELD_PATH) VALUES ('" . $extVal . "', '" . $pkrey . "')");
                                        }
                                    }
                                }
                            }
                        }

                        $sqlResult = $rdb->GetAll($sql, $bindParams);

                        $rdb->CompleteTrans();

                        $rdb->Close();
                        
                        Mdstatement::$isReportServer = true;

                    } else {

                        $this->db->StartTrans();
                        
                        if (DB_DRIVER != 'postgres9' && isset($param['criteria'])) {
                            
                            $param = Arr::changeKeyLower($param);
                            $groupParam = array();
                            
                            if (is_array($data['result']['parameters']) && isset($data['result']['parameters']['0']['name'])) {
                                $groupParam = Arr::groupByArray($data['result']['parameters'], 'name');
                            }

                            foreach ($param['criteria'] as $pkrey => $prow) {
                                if ($prow[0]['operator'] == 'IN') {
                                    $extractVal = explode(',', $prow[0]['operand']);
                                    if (count($extractVal) > 10 && array_key_exists($pkrey, $groupParam)) {
                                        foreach ($extractVal as $extVal) {
                                            $this->db->Execute("INSERT INTO TMP_IN_CRITERIA_TABLE (VALUE, FIELD_PATH) VALUES ('" . $extVal . "', '" . $pkrey . "')");
                                        }
                                    }
                                }
                            }
                        }
                        
                        if (issetParam($getRowStatement['IS_TIMETABLE']) === '1' || $dataViewId == Config::getFromCache('staticTmsReport')) { //'1581480427654'
                            
                            $datetime1 = date_create($param['criteria']['filterstartdate']['0']['operand']);
                            $datetime2 = date_create($param['criteria']['filterenddate']['0']['operand']);

                            $interval = date_diff($datetime1, $datetime2);
                            $days = $interval->days;
                            $days = ($days == 0) ? 1 : $days;
                            if ($days > 31) {
                                throw new Exception("31 өдрийн хязгаартайг анхаарна уу!");
                            }
                            
                            $otherWhere = '';
                            $i = 1;
                            for ($index = 0; $index <= $days; $index++) {
                                $workDates = Date::nextDate($param['criteria']['filterstartdate']['0']['operand'], $index);
                                $otherWhere .= "'". $workDates. "' AS " . '"D'. ($i > 9 ? $i : '0'.$i) .'"';
                                $otherWhere .= ($index < ($days) ? ',' : '');
                                $i++;
                            }

                            $sql = str_replace("ROWNUM AS RID, ", "", $sql);

                            $sql = "
                                SELECT 
                                    ROWNUM AS RID, 
                                    PDD.* 
                                FROM ( 
                                    SELECT 
                                        T1.* 
                                    FROM (
                                        SELECT DISTINCT T0.* FROM ( 
                                        $sql
                                    ) T0 PIVOT ( MIN(T0.OUTTIME) FOR DDA IN ( $otherWhere ) ) T0 ) T1 ".Config::getFromCache('staticTmsReportOrder')."
                                ) PDD";
                        }
                        
                        $sqlResult = $this->db->GetAll($sql, $bindParams);    
                        
                        $this->db->CompleteTrans();
                    }

                    $result['rows'] = Arr::changeKeyLower($sqlResult);

                    if (Input::postCheck('printCopies') && $result['rows']) {

                        $printCopies = $_POST['printCopies'];
                        $rows = $result['rows'];
                        $resultRows = array();

                        foreach ($printCopies as $pcField => $printCopiesData) {

                            $pcField = 'itemid'; //strtolower($pcField);

                            if (isset($result['rows'][0][$pcField])) {

                                foreach ($rows as $row) {
                                    if (isset($printCopiesData[$row[$pcField]]) && $row[$pcField] && $printCopiesData[$row[$pcField]]) {
                                        for ($i = 0; $i < $printCopiesData[$row[$pcField]]; $i++) {
                                            $resultRows[] = $row;
                                        }
                                    } else {
                                        $resultRows[] = $row;
                                    }
                                }
                                
                            } else {
                                $resultRows = $rows;
                            }
                        }

                        $result['rows'] = $resultRows;
                    }
                    
                    if (Mdstatement::$isWithDrillDown) {

                        $cacheTmpDir = Mdcommon::getCacheDirectory();
                        $tempdir = $cacheTmpDir.'/statement';

                        if (!is_dir($tempdir)) {

                            mkdir($tempdir, 0777);

                        } else {

                            $currentHour = (int) Date::currentDate('H');

                            /* Оройны 17 цагаас 19 цагийн хооронд шалгаж өмнө нь үүссэн файлуудыг устгана */
                            if ($currentHour >= 17 && $currentHour <= 19) { 

                                $files = glob($tempdir . '/*');
                                $now   = time();
                                $day   = 0.5;

                                foreach ($files as $file) {
                                    if (is_file($file) && ($now - filemtime($file) >= 60 * 60 * 24 * $day)) {
                                        @unlink($file);
                                    } 
                                }
                            }
                        }

                        $file_path = $tempdir.'/'.Mdstatement::$uniqId.'.txt';

                        $fileArray = array(
                            'rows' => $result['rows'], 
                            'params' => Arr::changeKeyLower($params)
                        );

                        $f = fopen($file_path, 'w+');
                        fwrite($f, var_export($fileArray, true));
                        fclose($f);
                    }

                    $result['status'] = 'success';

                } catch (ADODB_Exception $ex) {
                    
                    $result = array('status' => 'error', 'message' => $ex->getMessage(), 'rows' => array());
                    
                    if (isset($reportDbDriver)) {
                        $rdb->CompleteTrans();
                        $rdb->Close();
                    } else {
                        $this->db->CompleteTrans();
                    }
                    
                } catch (Exception $ex) {
                    
                    $result = array('status' => 'error', 'message' => $ex->getMessage(), 'rows' => array());
                    
                    if (isset($reportDbDriver)) {
                        $rdb->CompleteTrans();
                        $rdb->Close();
                    } else {
                        $this->db->CompleteTrans();
                    }
                }
            }

        } else {
            $result = array('status' => 'error', 'message' => $this->ws->getResponseMessage($data));
        }

        return $result;
    }
    
    public function dvListResult($param, $params) {
        
        $param['showQuery'] = 0;
        $param['showQueryWithParameter'] = 0;
        
        $data = $this->ws->runArrayResponse(self::$gfServiceAddress, Mddatamodel::$getDataViewCommand, $param);
        
        if ($data['status'] == 'success' && isset($data['result'])) {
            
            unset($data['result']['aggregatecolumns']);
            unset($data['result']['paging']);
            
            $result['rows'] = $data['result'];
            
            if (Mdstatement::$isWithDrillDown) {
                
                array_walk($result['rows'], function(&$value, $n) {  
                    $n++;
                    $value['rid'] = $n;
                }); 
                
                $cacheTmpDir = Mdcommon::getCacheDirectory();
                $tempdir = $cacheTmpDir.'/statement';

                if (!is_dir($tempdir)) {

                    mkdir($tempdir, 0777);

                } else {
                    
                    $currentHour = (int) Date::currentDate('H');
                        
                    /* Оройны 17 цагаас 19 цагийн хооронд шалгаж өмнө нь үүссэн файлуудыг устгана */
                    if ($currentHour >= 17 && $currentHour <= 19) { 
                        $files = glob($tempdir.'/*');
                        $now   = time();
                        $day   = 0.5;

                        foreach ($files as $file) {
                            if (is_file($file) && ($now - filemtime($file) >= 60 * 60 * 24 * $day)) {
                                unlink($file);
                            } 
                        }
                    }
                }

                $file_path = $tempdir.'/'.Mdstatement::$uniqId.'.txt';

                $fileArray = array(
                    'rows' => $result['rows'], 
                    'params' => Arr::changeKeyLower($params)
                );

                $f = fopen($file_path, "w+");
                fwrite($f, var_export($fileArray, true));
                fclose($f);
            }
                
            $result['status'] = 'success';
            
        } else {
            $result = array('status' => 'error', 'message' => $this->ws->getResponseMessage($data));
        }
        
        return $result;
    }
    
    public function getIndicatorDataModel($dataViewId, $params) {
        
        $ml = &getInstance();
        $ml->load->model('mdform', 'middleware/models/');                    

        $_POST['indicatorId'] = $dataViewId;
        $_POST['isShowQuery'] = 1;
        
        if (Mdstatement::$isPivotView) {
            
            $_POST['sort'] = 'ID';
            $_POST['order'] = 'ASC';
        }

        $result = $ml->model->indicatorDataGridModel();
        
        if ($result['status'] != 'success' && $result['message'] == '') {
            $result['status'] = 'success';
        }
        
        $result['rows'] = Arr::changeKeyLower($result['rows']);
        
        if (Mdstatement::$isWithDrillDown) {

            $cacheTmpDir = Mdcommon::getCacheDirectory();
            $tempdir = $cacheTmpDir.'/statement';

            if (!is_dir($tempdir)) {

                mkdir($tempdir, 0777);

            } else {

                $currentHour = (int) Date::currentDate('H');

                /* Оройны 17 цагаас 19 цагийн хооронд шалгаж өмнө нь үүссэн файлуудыг устгана */
                if ($currentHour >= 17 && $currentHour <= 19) { 

                    $files = glob($tempdir . '/*');
                    $now   = time();
                    $day   = 0.5;

                    foreach ($files as $file) {
                        if (is_file($file) && ($now - filemtime($file) >= 60 * 60 * 24 * $day)) {
                            @unlink($file);
                        } 
                    }
                }
            }

            $file_path = $tempdir.'/'.Mdstatement::$uniqId.'.txt';

            $fileArray = array(
                'rows' => $result['rows'], 
                'params' => $params
            );

            $f = fopen($file_path, 'w+');
            fwrite($f, var_export($fileArray, true));
            fclose($f);
        }
        
        return $result;
    }
    
    public function getReportDatabaseConnection() {
        
        if (strtolower($this->db->user) == strtolower(DB_USER)) {
            
            try {
                
                $row = $this->db->GetRow("
                    SELECT 
                        DB_TYPE, 
                        HOST_NAME, 
                        PORT, 
                        SID, 
                        SERVICE_NAME, 
                        USER_NAME, 
                        USER_PASSWORD 
                    FROM MDM_CONNECTIONS 
                    WHERE IS_REPORT = 1 
                        AND (IS_ACTIVE IS NULL OR IS_ACTIVE = 0)");

                if ($row 
                    && $row['DB_TYPE'] && $row['HOST_NAME'] 
                    && $row['PORT'] && $row['USER_NAME'] 
                    && $row['USER_PASSWORD'] 
                    && ($row['SID'] || $row['SERVICE_NAME'])) {

                    return $row;
                }
            
            } catch (Exception $ex) {
                return array();
            }
        } 
        
        return array();
    }
    
    public function isRenderColumnModel($rowHtml, $expRowFields = null, $dataViewId = '', $getRowStatement = []) {
        
        $dataViewColumnsType = Mdstatement::$dataViewColumnsType;
        $array = array();
        
        foreach ($dataViewColumnsType as $key => $val) {
            if (strpos($rowHtml, '#'.$key.'#') !== false) {
                $array[$key] = 1;
            }
        }
        
        if (issetParam($getRowStatement['IS_TIMETABLE']) === '1' || $dataViewId == Config::getFromCache('staticTmsReport')) {
            for ($i = 1; $i <= 31; $i++) {
                if (strpos($rowHtml, '#'. 'd'. ($i > 9 ? $i : '0'.$i) . '#') !== false) {
                    $array['d'. ($i > 9 ? $i : '0'.$i)] = 1;
                }
            }
        }
        
        if ($expRowFields) {
            
            $expRowFieldsArr = explode(',', $expRowFields);
            
            foreach ($expRowFieldsArr as $expRowField) {
                
                $expRowFieldArr = explode('=', $expRowField);
                
                $fieldName = $expRowFieldArr[0];
                $fieldType = $expRowFieldArr[1];
                
                $array[$fieldName] = 1;
                Mdstatement::$dataViewColumnsType[$fieldName] = $fieldType;
            }
        }
        
        return $array;
    }
    
    public function getUserGroupingOptionDataModel($statementId) {
        
        $sessionUserId = Ue::sessionUserId();
        $sessionUserIdPh = $this->db->Param(0);
        
        if ($sessionUserId) {
            
            $statementIdPh = $this->db->Param(1);
            
            $row = $this->db->GetRow("
                SELECT 
                    LOWER(GROUP_FIELD_PATH) AS GROUP_FIELD_PATH 
                FROM CUSTOMER_ST_GROUPING_CONFIG 
                WHERE USER_ID = $sessionUserIdPh 
                    AND STATEMENT_META_DATA_ID = $statementIdPh", 
                [$sessionUserId, $statementId]
            );
            
            if ($row) {
                
                if ($row['GROUP_FIELD_PATH'] == 'fieldnotselected') {
                    
                    $data = [];
                    
                } else {
                    
                    $data = $this->db->GetAll("
                        SELECT 
                            LOWER(GC.GROUP_FIELD_PATH) AS GROUP_FIELD_PATH, 
                            LG.GROUP_HEADER, 
                            LG.GROUP_FOOTER, 
                            LG.HEADER_BG_COLOR, 
                            LG.FOOTER_BG_COLOR, 
                            LG.IS_IGNORE_HDR_REPEAT_PAGE 
                        FROM CUSTOMER_ST_GROUPING_CONFIG GC 
                            INNER JOIN META_STATEMENT_LINK_GROUP LG ON LG.META_DATA_ID = GC.STATEMENT_META_DATA_ID 
                                AND LOWER(GC.GROUP_FIELD_PATH) = LOWER(LG.GROUP_FIELD_PATH)  
                        WHERE GC.USER_ID = $sessionUserIdPh 
                            AND GC.STATEMENT_META_DATA_ID = $statementIdPh  
                        ORDER BY GC.GROUP_ORDER ASC", 
                        [$sessionUserId, $statementId]
                    );
                }
            } 
        } 
        
        if (!isset($data)) {
            
            $data = $this->db->GetAll("
                SELECT 
                    LOWER(LG.GROUP_FIELD_PATH) AS GROUP_FIELD_PATH, 
                    LG.GROUP_HEADER, 
                    LG.GROUP_FOOTER, 
                    LG.HEADER_BG_COLOR, 
                    LG.FOOTER_BG_COLOR, 
                    LG.IS_IGNORE_HDR_REPEAT_PAGE 
                FROM META_STATEMENT_LINK_GROUP LG 
                    INNER JOIN META_STATEMENT_LINK SL ON SL.ID = LG.META_STATEMENT_LINK_ID 
                WHERE SL.".(Mdstatement::$isKpiIndicator ? 'MAIN_INDICATOR_ID' : 'META_DATA_ID')." = $sessionUserIdPh  
                    AND (LG.IS_USER_OPTION IS NULL OR LG.IS_USER_OPTION = 0 OR LG.IS_USER_OPTION = 2) 
                ORDER BY LG.GROUP_ORDER ASC", 
                array($statementId)
            );
            
            if (Mdstatement::$isKpiIndicator && count($data) == 1 && $data[0]['GROUP_FOOTER'] == '') {
                $data[0]['GROUP_FOOTER'] = Mdstatement::$autoGenerateGroupFooter;
            }
        }
        
        if (!$data && Input::postCheck('tempGroupingColumn')) {
            
            $data = array();
            $tempGroupingColumn = Input::post('tempGroupingColumn');
            
            if ($tempGroupingColumn) {
                
                foreach ($tempGroupingColumn as $colName => $val) {
                    
                    $colName = strtolower($colName);
                    
                    $data[] = array(
                        'GROUP_FIELD_PATH' => $colName,
                        'GROUP_HEADER' => '<div style="padding-top: 20px;padding-bottom: 10px;"><strong>#'.$colName.'#</strong></div>',
                        'GROUP_FOOTER' => null, 
                        'HEADER_BG_COLOR' => null,
                        'FOOTER_BG_COLOR' => null,
                        'IS_IGNORE_HDR_REPEAT_PAGE' => null
                    );
                }
            }
        }
        
        return $data;
    }
    
    public function getLinkGroupDataModel($statementId, $reportDetailHtml = '', $groupingCount = '') {
        
        if (Mdstatement::$pivotGrouping) {
            return Mdstatement::$pivotGrouping;
        }
        
        if (Input::postCheck('detectGroupingUserOption')) {
            
            if (Input::postCheck('groupingUserOption')) {
                
                $imploder = $orderByCase = '';
                $groupingUserOption = $_POST['groupingUserOption'];

                foreach ($groupingUserOption as $k => $id) {
                    
                    $id = explode('|', $id);
                    $id = $id[0];
                    
                    $imploder .= $id.', ';
                    $orderByCase .= 'WHEN '.$id.' THEN '.(++$k).' ';
                }
                
                $data = $this->db->GetAll("
                    SELECT 
                        LOWER(GROUP_FIELD_PATH) AS GROUP_FIELD_PATH, 
                        GROUP_HEADER, 
                        GROUP_FOOTER, 
                        HEADER_BG_COLOR, 
                        FOOTER_BG_COLOR, 
                        IS_IGNORE_HDR_REPEAT_PAGE 
                    FROM META_STATEMENT_LINK_GROUP  
                    WHERE META_DATA_ID = $statementId 
                        AND ID IN (".rtrim($imploder, ', ').") 
                    ORDER BY 
                        CASE ID  
                        $orderByCase 
                        END ASC"); 
            } else {
                $data = [];
            }

            $isDetectGroupingUserOption = true;
        } 
        
        if (!isset($isDetectGroupingUserOption)) {
            
            $data = self::getUserGroupingOptionDataModel($statementId);
        }

        if (Mdstatement::$isHdrRepeatPage && $data) {
            
            Mdstatement::$isIgnoreHdrRepeatPage = false;

            $detailHtml = phpQuery::newDocumentHTML($reportDetailHtml);
            $tbodyCellCount = $detailHtml['table > tbody td']->length - (($groupingCount) ? $groupingCount : 0);
            
            $array = [];

            foreach ($data as $k => $row) {
                
                if ($row['IS_IGNORE_HDR_REPEAT_PAGE'] == 1) {
                    
                    $row['isIgnoreHdrRepeatPage'] = 1;
                    $row['GROUP_HEADER'] = Str::cleanOut($row['GROUP_HEADER']);
                    
                    $array[$k] = $row;
                    Mdstatement::$isIgnoreHdrRepeatPage = true;
                    
                    continue;
                }
                
                $array[$k]['GROUP_FIELD_PATH'] = $row['GROUP_FIELD_PATH'];

                if ($row['GROUP_HEADER']) {

                    $style = '';

                    if ($k == 0) {
                        $style = 'padding-top: 6px !important;';
                    }

                    $row['GROUP_HEADER'] = Str::cleanOut($row['GROUP_HEADER']);

                    if (isset(Mdstatement::$UIExpression['group'])) {

                        $hdrExpEval = str_replace('$objHtmlReplace', '$hdrHtml', Mdstatement::$UIExpression['group']);

                        $hdrHtml = phpQuery::newDocument($row['GROUP_HEADER']);
                        eval($hdrExpEval);

                        $row['GROUP_HEADER'] = $hdrHtml;
                    }
                    
                    if ($row['HEADER_BG_COLOR']) {
                        $style .= 'background-color: '.$row['HEADER_BG_COLOR'].';';
                    }
                    
                    if (Mdstatement::$isIgnoreHdrRepeatPage) {
                        
                        $hdrHtml = phpQuery::newDocumentHTML($row['GROUP_HEADER']);
                        $tableRow = $hdrHtml['tbody']->html();
                        
                        $array[$k]['GROUP_HEADER'] = $tableRow;
                    } else {
                        $array[$k]['GROUP_HEADER'] = '<tr><td colspan="'.$tbodyCellCount.'" style="'.$style.'">'.$row['GROUP_HEADER'].'</td></tr>';
                    }

                } else {
                    $array[$k]['GROUP_HEADER'] = $row['GROUP_HEADER'];
                }

                if ($row['GROUP_FOOTER']) {
                    
                    $style = '';
                    
                    $row['GROUP_FOOTER'] = Str::cleanOut($row['GROUP_FOOTER']);

                    if (isset(Mdstatement::$UIExpression['group'])) {

                        $ftrExpEval = str_replace('$objHtmlReplace', '$ftrHtml', Mdstatement::$UIExpression['group']);

                        $ftrHtml = phpQuery::newDocument($row['GROUP_FOOTER']);
                        eval($ftrExpEval);

                        $row['GROUP_FOOTER'] = $ftrHtml;
                    }
                    
                    if ($row['FOOTER_BG_COLOR']) {
                        $style .= 'background-color: '.$row['FOOTER_BG_COLOR'].';';
                    }

                    $array[$k]['GROUP_FOOTER'] = '<tr data-path="group1Count_groupCount(1)"><td class="pf-st-group-tbl" colspan="'.$tbodyCellCount.'" style="'.$style.'">'.$row['GROUP_FOOTER'].'</td></tr>';

                } else {
                    $array[$k]['GROUP_FOOTER'] = $row['GROUP_FOOTER'];
                }
            }

            return $array;

        } else {

            if (isset(Mdstatement::$UIExpression['group'])) {

                $array = [];
                $hdrExpEval = str_replace('$objHtmlReplace', '$hdrHtml', Mdstatement::$UIExpression['group']);
                $ftrExpEval = str_replace('$objHtmlReplace', '$ftrHtml', Mdstatement::$UIExpression['group']);

                foreach ($data as $k => $row) {

                    $array[$k]['GROUP_FIELD_PATH'] = $row['GROUP_FIELD_PATH'];

                    if ($row['GROUP_HEADER']) {

                        $row['GROUP_HEADER'] = Str::cleanOut($row['GROUP_HEADER']);
                        $hdrHtml = phpQuery::newDocument($row['GROUP_HEADER']);

                        eval($hdrExpEval);

                        $array[$k]['GROUP_HEADER'] = $hdrHtml;
                    } else {
                        $array[$k]['GROUP_HEADER'] = $row['GROUP_HEADER'];
                    }

                    if ($row['GROUP_FOOTER']) {

                        $row['GROUP_FOOTER'] = Str::cleanOut($row['GROUP_FOOTER']);
                        $ftrHtml = phpQuery::newDocument($row['GROUP_FOOTER']);

                        eval($ftrExpEval);

                        $array[$k]['GROUP_FOOTER'] = $ftrHtml;
                    } else {
                        $array[$k]['GROUP_FOOTER'] = $row['GROUP_FOOTER'];
                    }
                }
            }
        }

        return $data;
    }

    public function getDataViewColumnsModel($metaGroupId) {
        $data = $this->db->GetAll("
            SELECT 
                LOWER(PARAM_NAME) AS META_DATA_CODE, 
                LABEL_NAME AS META_DATA_NAME 
            FROM META_GROUP_CONFIG 
            WHERE MAIN_META_DATA_ID = ".$this->db->Param(0)." 
                AND PARENT_ID IS NULL 
                AND (IS_SELECT = 1 OR IS_CRITERIA = 1) 
                AND DATA_TYPE <> 'group'", 
            array($metaGroupId)
        );

        return $data;
    }

    public function getSysKeysModel() {
        return array(
            array(
                'META_DATA_CODE' => 'sysdatetime', 
                'META_DATA_NAME' => 'Одоогийн бүтэн огноо', 
                'KEY_TYPE' => 'sys'
            ), 
            array(
                'META_DATA_CODE' => 'sysdate', 
                'META_DATA_NAME' => 'Одоогийн огноо', 
                'KEY_TYPE' => 'sys'
            ), 
            array(
                'META_DATA_CODE' => 'sysyear', 
                'META_DATA_NAME' => 'Одоогийн жил', 
                'KEY_TYPE' => 'sys'
            ), 
            array(
                'META_DATA_CODE' => 'sysmonth', 
                'META_DATA_NAME' => 'Одоогийн сар', 
                'KEY_TYPE' => 'sys'
            ), 
            array(
                'META_DATA_CODE' => 'sysday', 
                'META_DATA_NAME' => 'Одоогийн өдөр', 
                'KEY_TYPE' => 'sys'
            ),
            array(
                'META_DATA_CODE' => 'systime', 
                'META_DATA_NAME' => 'Одоогийн цаг минут', 
                'KEY_TYPE' => 'sys'
            ),
            array(
                'META_DATA_CODE' => 'rownum', 
                'META_DATA_NAME' => 'Мөрийн дугаар', 
                'KEY_TYPE' => 'sys'
            ),
            array(
                'META_DATA_CODE' => 'sessionPersonName', 
                'META_DATA_NAME' => 'Нэвтэрсэн хүний нэр', 
                'KEY_TYPE' => 'session'
            ),
            array(
                'META_DATA_CODE' => 'sessionUserName', 
                'META_DATA_NAME' => 'Нэвтэрсэн хэрэглэгчийн нэр', 
                'KEY_TYPE' => 'session'
            ),
            array(
                'META_DATA_CODE' => 'sessionPosition', 
                'META_DATA_NAME' => 'Албан тушаал', 
                'KEY_TYPE' => 'session'
            ),
            array(
                'META_DATA_CODE' => 'sessionPhone', 
                'META_DATA_NAME' => 'Нэвтэрсэн хэрэглэгчийн утасны дугаар', 
                'KEY_TYPE' => 'session'
            ),
            array(
                'META_DATA_CODE' => 'sessionEmail', 
                'META_DATA_NAME' => 'Нэвтэрсэн хэрэглэгчийн и-мэйл хаяг', 
                'KEY_TYPE' => 'session'
            ),
            array(
                'META_DATA_CODE' => 'sessionDepartmentName', 
                'META_DATA_NAME' => 'Нэвтэрсэн хэрэглэгчийн хэлтэсийн нэр', 
                'KEY_TYPE' => 'session'
            ),
            array(
                'META_DATA_CODE' => 'config_OrganizationTitle', 
                'META_DATA_NAME' => 'Байгууллагын нэр (нөхцөлгүй)', 
                'KEY_TYPE' => 'config'
            ),
            array(
                'META_DATA_CODE' => 'config_OrganizationName', 
                'META_DATA_NAME' => 'Байгууллагын нэр', 
                'KEY_TYPE' => 'config'
            ),
            array(
                'META_DATA_CODE' => 'config_OrganizationID', 
                'META_DATA_NAME' => 'Байгууллагын регистрийн дугаар', 
                'KEY_TYPE' => 'config'
            ), 
            array(
                'META_DATA_CODE' => 'config_OrganizationPhone', 
                'META_DATA_NAME' => 'Байгууллагын утас', 
                'KEY_TYPE' => 'config'
            ),
            array(
                'META_DATA_CODE' => 'config_OrganizationAddress', 
                'META_DATA_NAME' => 'Байгууллагын хаяг', 
                'KEY_TYPE' => 'config'
            ),
            array(
                'META_DATA_CODE' => 'config_OrganizationAddressCityName', 
                'META_DATA_NAME' => 'Байгууллагын хаяг /Аймаг, нийслэл/', 
                'KEY_TYPE' => 'config'
            ),
            array(
                'META_DATA_CODE' => 'config_OrganizationAddressCityCode', 
                'META_DATA_NAME' => 'Байгууллагын хаяг /Аймаг, нийслэл код/', 
                'KEY_TYPE' => 'config'
            ),
            array(
                'META_DATA_CODE' => 'config_OrganizationAddressDistrictName', 
                'META_DATA_NAME' => 'Байгууллагын хаяг /Сум, дүүрэг/', 
                'KEY_TYPE' => 'config'
            ),
            array(
                'META_DATA_CODE' => 'config_OrganizationAddressDistrictCode', 
                'META_DATA_NAME' => 'Байгууллагын хаяг /Сум, дүүрэг код/', 
                'KEY_TYPE' => 'config'
            ),
            array(
                'META_DATA_CODE' => 'config_OwnerNumber', 
                'META_DATA_NAME' => 'Өмч эзэмшигчийн дугаар', 
                'KEY_TYPE' => 'config'
            ),
            array(
                'META_DATA_CODE' => 'config_OrganizationFax', 
                'META_DATA_NAME' => 'Байгууллагын факс', 
                'KEY_TYPE' => 'config'
            ),
            array(
                'META_DATA_CODE' => 'config_1stPersonName', 
                'META_DATA_NAME' => '1-р албан тушаалтны нэр', 
                'KEY_TYPE' => 'config'
            ), 
            array(
                'META_DATA_CODE' => 'config_1stName', 
                'META_DATA_NAME' => '1-р албан тушаалын нэр', 
                'KEY_TYPE' => 'config'
            ), 
            array(
                'META_DATA_CODE' => 'config_2ndPersonName', 
                'META_DATA_NAME' => '2-р албан тушаалтны нэр', 
                'KEY_TYPE' => 'config'
            ), 
            array(
                'META_DATA_CODE' => 'config_2ndName', 
                'META_DATA_NAME' => '2-р албан тушаалын нэр', 
                'KEY_TYPE' => 'config'
            ), 
            array(
                'META_DATA_CODE' => 'config_3rdPersonName', 
                'META_DATA_NAME' => '3-р албан тушаалтны нэр', 
                'KEY_TYPE' => 'config'
            ), 
            array(
                'META_DATA_CODE' => 'config_3thName', 
                'META_DATA_NAME' => '3-р албан тушаалын нэр', 
                'KEY_TYPE' => 'config'
            ),
            array(
                'META_DATA_CODE' => 'config_4thPersonName', 
                'META_DATA_NAME' => '4-р албан тушаалтны нэр', 
                'KEY_TYPE' => 'config'
            ), 
            array(
                'META_DATA_CODE' => 'config_4thName', 
                'META_DATA_NAME' => '4-р албан тушаалын нэр', 
                'KEY_TYPE' => 'config'
            ),
            array(
                'META_DATA_CODE' => 'config_ConfigEmail',
                'META_DATA_NAME' => 'И-мэйл хаяг', 
                'KEY_TYPE' => 'config'
            ),
            array(
                'META_DATA_CODE' => 'config_Organization_logo_path',
                'META_DATA_NAME' => 'Байгууллагын лого', 
                'KEY_TYPE' => 'config'
            ),
            array(
                'META_DATA_CODE' => 'config_PayrollTempAccount',
                'META_DATA_NAME' => 'Цалингийн түр данс', 
                'KEY_TYPE' => 'config'
            ),
            array(
                'META_DATA_CODE' => 'config_PayrollTempAccountTDB',
                'META_DATA_NAME' => 'Худалдаа хөгжлийн данс', 
                'KEY_TYPE' => 'config'
            ),
            array(
                'META_DATA_CODE' => 'config_PayrollTempAccountGolomt',
                'META_DATA_NAME' => 'Голомтын данс', 
                'KEY_TYPE' => 'config'
            ),
            array(
                'META_DATA_CODE' => 'config_PayrollTempAccountKhaan',
                'META_DATA_NAME' => 'Хааны данс', 
                'KEY_TYPE' => 'config'
            ),
            array(
                'META_DATA_CODE' => 'config_OrganizationVascoNumber',
                'META_DATA_NAME' => 'OrganizationVascoNumber', 
                'KEY_TYPE' => 'config'
            ),
        );
    }

    public function getStatementDataListModel($metaDataId) {
        return $this->db->GetAll("
            SELECT 
                MDD.META_DATA_ID,
                MDD.META_DATA_NAME,
                MDD.META_DATA_CODE
            FROM META_DM_STATEMENT_DTL DTL
                INNER JOIN META_GROUP_LINK LINK ON LINK.ID = DTL.META_GROUP_LINK_ID
                INNER JOIN META_DATA MD ON MD.META_DATA_ID = LINK.META_DATA_ID
                INNER JOIN META_DATA MDD ON DTL.STATEMENT_META_DATA_ID = MDD.META_DATA_ID
            WHERE MD.META_DATA_ID = ".$this->db->Param(0), 
            array($metaDataId) 
        );
    }

    public function getDrillDownColumnDataModel($statementId, $dataViewId) {

        $result = [];
        
        if (Mdstatement::$isKpiIndicator) {
            $filterColumn = 'MAIN_INDICATOR_ID';
        } else {
            $filterColumn = 'STATEMENT_META_DATA_ID';
        }
        
        $columnData = $this->db->GetAll("
            SELECT 
                DTL.ID, 
                LOWER(DTL.MAIN_GROUP_LINK_PARAM) AS MAIN_GROUP_LINK_PARAM  
            FROM META_DM_DRILLDOWN_DTL DTL 
            WHERE DTL.$filterColumn = ".$this->db->Param(0)." 
                AND DTL.ID = (
                    SELECT 
                        DM_DRILLDOWN_DTL_ID 
                    FROM META_DM_DRILLDOWN_PARAM 
                    WHERE DM_DRILLDOWN_DTL_ID = DTL.ID 
                    GROUP BY DM_DRILLDOWN_DTL_ID 
                ) 
            GROUP BY 
                DTL.ID, 
                DTL.MAIN_GROUP_LINK_PARAM", 
            [$statementId]
        );
        
        if ($columnData) {
            foreach ($columnData as $column) {
                $result[$column['MAIN_GROUP_LINK_PARAM']] = 1;
            }
        }

        return $result;
    }

    public function getChildStatementListModel($srcStatementId) {
        
        $srcStatementIdPh = $this->db->Param(0);
        
        if (Mdstatement::$isKpiIndicator == false) {
            
            $data = $this->db->GetAll("
                SELECT 
                    MM.TRG_META_DATA_ID AS STATEMENT_META_ID, 
                    SL.DATA_VIEW_ID 
                FROM META_META_MAP MM 
                    INNER JOIN META_STATEMENT_LINK SL ON SL.META_DATA_ID = MM.TRG_META_DATA_ID 
                WHERE MM.SRC_META_DATA_ID = $srcStatementIdPh 
                    AND MM.TRG_META_DATA_ID <> $srcStatementIdPh  
                ORDER BY MM.ORDER_NUM ASC", 
                array($srcStatementId)
            );

            $srcRow = $this->db->GetRow("
                SELECT 
                    IS_NOT_PAGE_BREAK, 
                    REPORT_HEADER, 
                    PAGE_HEADER, 
                    PAGE_FOOTER, 
                    REPORT_FOOTER 
                FROM META_STATEMENT_LINK 
                WHERE META_DATA_ID = $srcStatementIdPh", 
                array($srcStatementId) 
            );
        
        } else {
            
            $data = $this->db->GetAll("
                SELECT 
                    T0.TRG_INDICATOR_ID AS STATEMENT_META_ID, 
                    T2.DATA_INDICATOR_ID AS DATA_VIEW_ID 
                FROM KPI_INDICATOR_INDICATOR_MAP T0 
                    INNER JOIN KPI_INDICATOR T1 ON T1.ID = T0.TRG_INDICATOR_ID 
                    INNER JOIN META_STATEMENT_LINK T2 ON T2.MAIN_INDICATOR_ID = T0.TRG_INDICATOR_ID 
                WHERE T0.SRC_INDICATOR_ID = $srcStatementIdPh 
                    AND T0.SEMANTIC_TYPE_ID = 28 
                    AND T0.TRG_INDICATOR_ID <> $srcStatementIdPh  
                GROUP BY 
                    T0.TRG_INDICATOR_ID, 
                    T2.DATA_INDICATOR_ID, 
                    T0.ORDER_NUMBER 
                ORDER BY T0.ORDER_NUMBER ASC", 
                array($srcStatementId)
            );
            
            $columnSelect = $this->db->IfNull('SL.REPORT_NAME', 'MD.NAME')." AS REPORT_NAME,";
            
            $srcRow = $this->db->GetRow("
                SELECT 
                    $columnSelect 
                    SL.IS_NOT_PAGE_BREAK, 
                    SL.REPORT_HEADER, 
                    SL.PAGE_HEADER, 
                    SL.PAGE_FOOTER, 
                    SL.REPORT_FOOTER 
                FROM META_STATEMENT_LINK SL 
                    INNER JOIN KPI_INDICATOR MD ON MD.ID = SL.MAIN_INDICATOR_ID 
                WHERE SL.MAIN_INDICATOR_ID = $srcStatementIdPh", 
                array($srcStatementId) 
            );
        }
        
        return array('child' => $data, 'isNotPageBreak' => $srcRow['IS_NOT_PAGE_BREAK'], 'srcRow' => $srcRow);
    }
    
    public function getChildCalculatingStatementModel($srcStatementId) {
        
        $srcStatementIdPh = $this->db->Param(0);
        
        $data = $this->db->GetAll("
            SELECT 
                TT.* 
            FROM (
                SELECT 
                    MM.TRG_META_DATA_ID AS STATEMENT_META_ID, 
                    SL.DATA_VIEW_ID, 
                    SL.PROCESS_META_DATA_ID, 
                    GC.DEFAULT_VALUE, 
                    MM.SECOND_ORDER_NUM, 
                    MM.ORDER_NUM 
                FROM META_META_MAP MM 
                    INNER JOIN META_STATEMENT_LINK SL ON SL.META_DATA_ID = MM.TRG_META_DATA_ID 
                        AND SL.PROCESS_META_DATA_ID IS NOT NULL 
                    LEFT JOIN META_GROUP_CONFIG GC ON GC.MAIN_META_DATA_ID = SL.DATA_VIEW_ID 
                        AND LOWER(GC.FIELD_PATH) = 'tablename' 
                WHERE MM.SRC_META_DATA_ID = $srcStatementIdPh  

                UNION ALL 

                SELECT 
                    SL.META_DATA_ID AS STATEMENT_META_ID, 
                    SL.DATA_VIEW_ID, 
                    SL.PROCESS_META_DATA_ID, 
                    GC.DEFAULT_VALUE, 
                    ".$this->db->IfNull('SL.CALC_ORDER_NUM', '1')." AS SECOND_ORDER_NUM, 
                    1 AS ORDER_NUM 
                FROM META_STATEMENT_LINK SL 
                    LEFT JOIN META_GROUP_CONFIG GC ON GC.MAIN_META_DATA_ID = SL.DATA_VIEW_ID 
                        AND LOWER(GC.FIELD_PATH) = 'tablename' 
                WHERE SL.META_DATA_ID = $srcStatementIdPh  
            ) TT  
            ORDER BY TT.SECOND_ORDER_NUM ASC, TT.ORDER_NUM ASC", 
            array($srcStatementId)
        );
        
        return $data;
    }
    
    public function isCheckOwnMetaMapModel($srcMetaId) {
        
        $count = (int) $this->db->GetOne("SELECT COUNT(ID) FROM META_META_MAP WHERE SRC_META_DATA_ID = ".$this->db->Param(0), array($srcMetaId));
        
        if ($count) {
            return true;
        } else {
            return false;
        }
    }

    public function getDrillDownParamsModel($id, $row, $drillParams) {

        $result = [];

        $data = $this->db->GetAll("
            SELECT 
                LOWER(SRC_PARAM) AS SRC_PARAM, 
                LOWER(TRG_PARAM) AS TRG_PARAM, 
                DEFAULT_VALUE 
            FROM META_DM_DRILLDOWN_PARAM 
            WHERE DM_DRILLDOWN_DTL_ID = ".$this->db->Param(0), 
            [$id] 
        ); 

        if ($data) {

            $constantKeys = Arr::changeKeyLower(Mdstatement::constantKeys());

            foreach ($data as $k => $filter) {

                $drillValue = '';

                if (isset($constantKeys[$filter['SRC_PARAM']])) {
                    $drillValue = $constantKeys[$filter['SRC_PARAM']];
                } elseif (isset($row[$filter['SRC_PARAM']])) {
                    $drillValue = $row[$filter['SRC_PARAM']];
                } elseif (isset($drillParams[$filter['SRC_PARAM']])) {
                    $drillValue = $drillParams[$filter['SRC_PARAM']];
                }

                if ($drillValue == '' && $filter['DEFAULT_VALUE'] != '') {
                    $drillValue = Mdmetadata::setDefaultValue($filter['DEFAULT_VALUE']);
                }

                $result[$filter['TRG_PARAM']] = $drillValue;
            }
        }

        return $result;
    }

    public function getDrillDownStatementCriteriaModel($statementId, $columnName, $rowData, $drillParams) {
        
        $statementMode = Input::post('statementMode');
        
        if ($statementMode == '1') {
            
            $data = $this->db->GetAll("
                SELECT 
                    DD.ID, 
                    MD.META_TYPE_ID, 
                    DD.LINK_META_DATA_ID, 
                    DD.LINK_INDICATOR_ID, 
                    KI.KPI_TYPE_ID, 
                    DD.CRITERIA 
                FROM META_DM_DRILLDOWN_DTL DD 
                    LEFT JOIN META_DATA MD ON MD.META_DATA_ID = DD.LINK_META_DATA_ID 
                    INNER JOIN META_STATEMENT_LINK SL ON SL.MAIN_INDICATOR_ID = DD.MAIN_INDICATOR_ID 
                    INNER JOIN KPI_INDICATOR KI ON KI.ID = DD.LINK_INDICATOR_ID 
                WHERE SL.MAIN_INDICATOR_ID = ".$this->db->Param(0)."  
                    AND LOWER(DD.MAIN_GROUP_LINK_PARAM) = ".$this->db->Param(1), 
                [$statementId, $columnName]
            ); 
            
        } else {
            
            $data = $this->db->GetAll("
                SELECT 
                    DD.ID, 
                    MD.META_TYPE_ID, 
                    DD.LINK_META_DATA_ID, 
                    NULL AS LINK_INDICATOR_ID, 
                    NULL AS KPI_TYPE_ID, 
                    DD.CRITERIA 
                FROM META_DM_DRILLDOWN_DTL DD 
                    LEFT JOIN META_DATA MD ON MD.META_DATA_ID = DD.LINK_META_DATA_ID  
                WHERE DD.STATEMENT_META_DATA_ID = ".$this->db->Param(0)." 
                    AND LOWER(DD.MAIN_GROUP_LINK_PARAM) = ".$this->db->Param(1), 
                [$statementId, $columnName]
            ); 
        }
        
        if (count($data) == 1 && empty($data[0]['CRITERIA'])) {

            $linkParam = self::getDrillDownParamsModel($data[0]['ID'], $rowData, $drillParams);

            return [
                'typeId' => ($data[0]['META_TYPE_ID'] ? $data[0]['META_TYPE_ID'] : 'kpi'), 
                'linkMetaId' => $data[0]['LINK_META_DATA_ID'], 
                'linkIndicatorId' => $data[0]['LINK_INDICATOR_ID'], 
                'kpiTypeId' => $data[0]['KPI_TYPE_ID'], 
                'linkParam' => $linkParam
            ];

        } else {

            $response = [];

            foreach ($data as $row) {

                $rules = Str::lower($row['CRITERIA']); 

                foreach ($rowData as $sk => $sv) {
                    if (!is_array($sv)) {
                        if (is_string($sv)) {
                            $sv = "'".Str::lower($sv)."'";
                        } elseif (is_null($sv)) {
                            $sv = "''";
                        }
                        $rules = preg_replace('/\b'.$sk.'\b/u', $sv, $rules); 
                    }
                }
                
                $rules = Mdmetadata::defaultKeywordReplacer($rules);
                $rules = Mdmetadata::criteriaMethodReplacer($rules);

                if (trim($rules) != '' && Mdcommon::expressionEvalFixWithReturn($rules)) { 
                    
                    $linkParam = self::getDrillDownParamsModel($row['ID'], $rowData, $drillParams);
                    
                    $response = [
                        'typeId' => ($row['META_TYPE_ID'] ? $row['META_TYPE_ID'] : 'kpi'), 
                        'linkMetaId' => $row['LINK_META_DATA_ID'], 
                        'linkIndicatorId' => $row['LINK_INDICATOR_ID'], 
                        'kpiTypeId' => $row['KPI_TYPE_ID'], 
                        'linkParam' => $linkParam
                    ];
                }
            }

            return $response;
        }
    }

    public function runProcessValueModel($postData) {
        
        $this->load->model('mdwebservice', 'middleware/models/');
        
        $processCode = $postData['processCode']; 
        $responsePath = $postData['responsePath'];

        $getProcess = $this->model->getProcessConfigByCode($processCode);

        if ($getProcess) {
            if ($getProcess['SUB_TYPE'] == 'internal' && $getProcess['ACTION_TYPE'] == 'get') {

                $isEmpty = true;
                $paramCriteria = array();

                foreach ($postData['paramData'] as $inputField) {
                    if ($inputField['value'] != '') {
                        $paramCriteria[$inputField['inputPath']][] = array(
                            'operator' => '=',
                            'operand' => $inputField['value']
                        );
                    } else {
                        $isEmpty = false;
                    }
                }

                if ($isEmpty) {
                    $param['criteria'] = $paramCriteria;

                    $result = $this->ws->caller($getProcess['SERVICE_LANGUAGE_CODE'], $getProcess['WS_URL'], $getProcess['COMMAND_NAME'], 'return', $param, 'array');                        

                    if ($result['status'] == 'success' && isset($result['result'])) {
                        if (isset($result[$responsePath])) {
                            return $result[$responsePath];
                        } else {
                            if (isset($result['result'][$responsePath])) {
                                return $result['result'][$responsePath];
                            }
                        }
                    }
                }
            } else {

                $isEmpty = true;
                $param = array();

                foreach ($postData['paramData'] as $inputField) {
                    if ($inputField['value'] != '') {
                        $param[$inputField['inputPath']] = $inputField['value'];
                    } else {
                        $isEmpty = false;
                    }
                }

                if ($isEmpty) {
                    $result = $this->ws->caller($getProcess['SERVICE_LANGUAGE_CODE'], $getProcess['WS_URL'], $getProcess['COMMAND_NAME'], 'return', $param, 'array');

                    if ($result['status'] == 'success' && isset($result['result'][$responsePath])) {
                        return $result['result'][$responsePath];
                    }
                }
            }
        }

        return null;
    }        

    public function runOneDataViewModel($dataViewCode, $params) {
        
        $this->load->model('mdmetadata', 'middleware/models/');
        $metaGroupId = $this->model->getMetaDataIdByCodeModel($dataViewCode);

        if ($metaGroupId) {
            
            $criteria = array();
            
            foreach ($params as $key => $val) {
                
                if (is_array($val) && $val) {
                    
                    if (count($val) > 1) {
                        
                        $criteria[$key] = array(
                            array(
                                'operator' => 'IN', 
                                'operand'  => Arr::implode_r(',', $val, true)
                            )
                        );
                        
                    } elseif ($val[0] != '') {
                        
                        $criteria[$key] = array(
                            array(
                                'operator' => '=', 
                                'operand'  => $val[0]
                            )
                        );
                    }
                    
                } elseif ($val != '') {
                    
                    $criteria[$key] = array(
                        array(
                            'operator' => '=', 
                            'operand'  => $val
                        )
                    );
                }
            }
            
            $param = array(
                'systemMetaGroupId' => $metaGroupId,
                'showQuery'         => 0, 
                'ignorePermission'  => 1,  
                'criteria'          => $criteria
            );

            $data = $this->ws->runArrayResponse(self::$gfServiceAddress, Mddatamodel::$getDataViewCommand, $param); 
            
            if ($data['status'] === 'success') {
                
                unset($data['result']['aggregatecolumns']);
                unset($data['result']['paging']);
                
                return $data['result'];
            }
        }

        return array();
    }        
    
    public function dataViewHeaderDataResolveModel($dataViewSearchData) {
        
        $array = $hidden = array();
        
        foreach ($dataViewSearchData as $k => $row) {
            
            if ($row['IS_NOT_SHOW_CRITERIA'] == '1') {
                
                $row['IS_SHOW'] = '0';
                $hidden[] = $row;
                unset($dataViewSearchData[$k]);
                
                continue;
            }
            
            $metaCode = strtolower($row['META_DATA_CODE']);
            
            if ($metaCode == 'filterdepartmentid') {
                
                $array[0] = $row;
                unset($dataViewSearchData[$k]);
                
            } elseif ($metaCode == 'departmentid') {
                
                $array[0] = $row;
                unset($dataViewSearchData[$k]);
                
            } elseif ($metaCode == 'filterstartdate') {
                
                $array[1] = array($row);
                unset($dataViewSearchData[$k]);
                
            } elseif ($metaCode == 'startdate') {
                
                $array[1] = array($row);
                unset($dataViewSearchData[$k]);
                
            } elseif ($metaCode == 'filterenddate') {
                
                if (array_key_exists(1, $array)) {
                    $array[1][1] = $row;
                } else {
                    $array[1] = array($row);
                }
                
                unset($dataViewSearchData[$k]);
                
            } elseif ($metaCode == 'enddate') {
                
                if (array_key_exists(1, $array)) {
                    $array[1][1] = $row;
                } else {
                    $array[1] = array($row);
                }
                
                unset($dataViewSearchData[$k]);
                
            } elseif ($metaCode == 'accountid') {
                
                $array[3] = $row;
                unset($dataViewSearchData[$k]);
                 
            } elseif ($metaCode == 'filteraccountid') {
                
                $array[4] = $row;
                unset($dataViewSearchData[$k]);
                 
            } elseif ($metaCode == 'customerid') {
                
                $array[5] = $row;
                unset($dataViewSearchData[$k]);
                
            } elseif ($metaCode == 'tablename') {
                
                $row['IS_SHOW'] = '0';
                $hidden[] = $row;
                unset($dataViewSearchData[$k]);
                
            }
        }
        
        if (count($array) > 0) {
            Arr::ksort_recursive($array);
            
            if (count($dataViewSearchData) > 0) {
                
                foreach ($dataViewSearchData as $lastRow) {
                    array_push($array, $lastRow);
                }
            }
            
            $dataViewSearchData = $array;
        }
        
        return array('visible' => $dataViewSearchData, 'hidden' => $hidden);
    }
    
    public function getRepFinStylesModel() {
        
        $data = $this->db->GetAll("SELECT ID, CELL_STYLE, ROW_STYLE FROM REP_FIN_STYLE");
        
        $array = array();
        
        foreach ($data as $row) {
            $array[$row['ID']]['cell'] = $row['CELL_STYLE'];
            $array[$row['ID']]['row'] = str_replace(
                array(
                    'padding-left: 20px !important;', 
                    'padding-left: 40px !important;', 
                    'padding-left: 60px !important;', 
                    'padding-left: 80px !important;'
                ), 
                '', 
                $row['CELL_STYLE']
            );
        }

        return $array;
    }
    
    public function getBlankOneRowModel($columns) {
        
        $array = array();
        
        foreach ($columns as $column) {
            
            if ($column['META_TYPE_CODE'] == 'bigdecimal' 
                    || $column['META_TYPE_CODE'] == 'long' 
                    || $column['META_TYPE_CODE'] == 'number' 
                    || $column['META_TYPE_CODE'] == 'integer' 
                    || $column['META_TYPE_CODE'] == 'decimal') {
                
                $array[$column['FIELD_PATH']] = '0';
                
            } else {
                $array[$column['FIELD_PATH']] = '';
            }
        }
        
        return $array;
    }
    
    public function getCostDepartmentNameModel($departmentId) {
        
        $name = '';
        
        if ($departmentId) {
            
            $row = $this->db->GetRow("
                SELECT 
                    HDR.DEPARTMENT_NAME, 
                    HDR.PARENT_ID, 
                    DTL.IS_COST_CENTER
                FROM ORG_DEPARTMENT HDR 
                    LEFT JOIN ORG_DEPARTMENT_DTL DTL ON DTL.DEPARTMENT_ID = HDR.DEPARTMENT_ID 
                        AND DTL.IS_COST_CENTER = 1 
                WHERE HDR.DEPARTMENT_ID = ".$this->db->Param(0), 
                array($departmentId) 
            );
            
            if ($row) {
                
                if ($row['IS_COST_CENTER'] == 1 || is_null($row['PARENT_ID'])) {
                    $name = $row['DEPARTMENT_NAME'];
                } else {
                    $name = self::getCostDepartmentNameModel($row['PARENT_ID']);
                }
            }
        }
        
        return $name;
    }
    
    public function writeStatementHtmlFile($htmlData, $isFromExport = false) {
        
        $fileId = getUID();
        
        if (isset(Mdstatement::$data['statementRealParams']) || $isFromExport) {
            
            $cacheTmpDir = Mdcommon::getCacheDirectory();
            $cacheDir    = $cacheTmpDir . '/statement_html';
            $cachePath   = $cacheDir . '/' . $fileId . '.html';

            if (!is_dir($cacheDir)) {

                mkdir($cacheDir, 0777);

            } else {

                $currentHour = (int) Date::currentDate('H');

                /* Оройны 17 цагаас 19 цагийн хооронд шалгаж өмнө нь үүссэн файлуудыг устгана */
                if ($currentHour >= 17 && $currentHour <= 19) { 

                    $files = glob($cacheDir.'/*');
                    $now   = time();
                    $day   = 0.5;

                    foreach ($files as $file) {
                        if (is_file($file) && ($now - filemtime($file) >= 60 * 60 * 24 * $day)) {
                            @unlink($file);
                        } 
                    }
                }
            }

            file_put_contents($cachePath, $htmlData);
            $htmlData = null;
        }
        
        return $fileId;
    }
    
    public function readStatementHtmlFile($fileId) {

        $cacheTmpDir = Mdcommon::getCacheDirectory();
        $cacheDir    = $cacheTmpDir . '/statement_html';
        $cachePath   = $cacheDir . '/' . $fileId . '.html';
        
        if (file_exists($cachePath)) {
            return file_get_contents($cachePath);
        } else {
            return null;
        }
    }
    
    public function getGroupingUserOptionModel($statementId) {
        
        $sessionUserId = Ue::sessionUserId();
        
        $statementIdPh = $this->db->Param(0);
        $sessionUserIdPh = $this->db->Param(1);
        
        $data = $this->db->GetAll("
            SELECT 
                LG.ID, 
                LOWER(LG.GROUP_FIELD_PATH) AS GROUP_FIELD_PATH,  
                GC.LABEL_NAME, 
                LG.IS_USER_OPTION, 
                UC.GROUP_ORDER, 
                (
                    SELECT 
                        GROUP_FIELD_PATH 
                    FROM CUSTOMER_ST_GROUPING_CONFIG 
                    WHERE STATEMENT_META_DATA_ID = $statementIdPh  
                        AND USER_ID = $sessionUserIdPh   
                        AND GROUP_FIELD_PATH = 'fieldnotselected' 
                ) AS FIELDNOTSELECTED 
            FROM META_STATEMENT_LINK_GROUP LG 
                INNER JOIN META_STATEMENT_LINK SL ON SL.ID = LG.META_STATEMENT_LINK_ID 
                INNER JOIN META_GROUP_CONFIG GC ON GC.MAIN_META_DATA_ID = SL.DATA_VIEW_ID 
                    AND LOWER(GC.FIELD_PATH) = LOWER(LG.GROUP_FIELD_PATH) 
                LEFT JOIN CUSTOMER_ST_GROUPING_CONFIG UC ON UC.STATEMENT_META_DATA_ID = LG.META_DATA_ID 
                    AND UC.USER_ID = $sessionUserIdPh  
                    AND UC.GROUP_FIELD_PATH = LOWER(LG.GROUP_FIELD_PATH)     
            WHERE LG.META_DATA_ID = $statementIdPh  
                AND (LG.IS_USER_OPTION = 1 OR LG.IS_USER_OPTION = 2) 
            ORDER BY UC.GROUP_ORDER ASC, LG.GROUP_ORDER ASC", 
            array($statementId, $sessionUserId)
        );
        
        return $data;
    }
    
    public function groupingUserOptionSaveModel() {
        
        $sessionUserId = Ue::sessionUserId();
        $statementId = Input::post('statementId');
        
        $this->db->Execute("DELETE FROM CUSTOMER_ST_GROUPING_CONFIG WHERE USER_ID = $sessionUserId AND STATEMENT_META_DATA_ID = $statementId");
        
        if (Input::postCheck('groupingUserOption')) {
            
            $groupingUserOption = $_POST['groupingUserOption'];

            foreach ($groupingUserOption as $k => $id) {
                
                $id = explode('|', $id);
                
                $data = array(
                    'ID'                     => getUID(), 
                    'USER_ID'                => $sessionUserId, 
                    'STATEMENT_META_DATA_ID' => $statementId, 
                    'GROUP_FIELD_PATH'       => $id[1], 
                    'GROUP_ORDER'            => (++$k)
                );
                $this->db->AutoExecute('CUSTOMER_ST_GROUPING_CONFIG', $data);
            }
            
        } else {
            $data = array(
                'ID'                     => getUID(), 
                'USER_ID'                => $sessionUserId, 
                'STATEMENT_META_DATA_ID' => $statementId, 
                'GROUP_FIELD_PATH'       => 'fieldnotselected'
            );
            $this->db->AutoExecute('CUSTOMER_ST_GROUPING_CONFIG', $data);
        }
        
        return true;
    }
    
    public function reportDetailEvalModel($reportDetailHtml) {
        
        $reportDetailHtml = Str::cleanOut($reportDetailHtml);
        
        if (isset(Mdstatement::$UIExpression['detail'])) {

            $dtlExpEval = str_replace('$objHtmlReplace', '$reportDetailHtml', Mdstatement::$UIExpression['detail']);

            $reportDetailHtml = phpQuery::newDocument($reportDetailHtml);
            eval($dtlExpEval);
        }
        
        return $reportDetailHtml;
    }
    
    public function getReportGenerateLayoutIdModel($dvId) {
        
        $layoutId = $this->db->GetOne("
            SELECT 
                REPORT_LAYOUT_ID 
            FROM RP_REPORT_LAYOUT 
            WHERE MAIN_META_DATA_ID = ".$this->db->Param(0)." 
            ORDER BY REPORT_LAYOUT_ID ASC", 
            array($dvId)
        );
        
        if (!$layoutId) {
            $layoutId = getUID();
        }
        return $layoutId;
    }
    
    public function getReportLayoutIdModel($statementId) {
        
        $layoutId = $this->db->GetOne("
            SELECT 
                REPORT_LAYOUT_ID 
            FROM RP_REPORT_LAYOUT 
            WHERE REPORT_LAYOUT_ID = ".$this->db->Param(0)."  
            ORDER BY REPORT_LAYOUT_ID ASC", 
            array($statementId)
        );
        
        return $layoutId;
    }
    
    public function getReportLayoutTemplateModel($statementId) { 
                
        $data = $this->db->GetAll("
            SELECT 
                ".$this->db->IfNull('SL.REPORT_NAME', 'MD.META_DATA_NAME')." AS REPORT_NAME, 
                ST.TRG_META_DATA_ID, 
                MD.META_DATA_CODE, 
                SL.GROUP_DATA_VIEW_ID 
            FROM META_STATEMENT_TEMPLATE ST 
                INNER JOIN META_STATEMENT_LINK SL ON SL.META_DATA_ID = ST.TRG_META_DATA_ID 
                INNER JOIN META_DATA MD ON MD.META_DATA_ID = ST.TRG_META_DATA_ID 
            WHERE ST.SRC_META_DATA_ID = ".$this->db->Param(0), 
            array($statementId)
        );
        
        if ($data) {
            return $data;
        }
        return null;
    }
    
    public function getReportIdModel($dvId) {
        
        $param = array(
            'systemMetaGroupId' => $dvId,
            'ignorePermission' => 1, 
            'showReport' => 1
        );

        $data = $this->ws->runSerializeResponse(self::$gfServiceAddress, Mddatamodel::$getDataViewCommand, $param);

        if ($data['status'] == 'success' && isset($data['result'])) {
            return array('status' => 'success', 'reportId' => $data['result']);
        } else {
            return array('status' => 'error', 'message' => $this->ws->getResponseMessage($data));
        }
    }
    
    public function setReportParamsModel($reportId, $dataViewId, $params) {
        
        $realParams          = Arr::changeKeyLower($params);
        $dataViewColumnsType = self::getTypeCodeDataViewParamsModel($dataViewId);    
        $params              = self::setParamsValueModel($dataViewColumnsType, $params);
        $constantKeys        = Mdstatement::constantKeys();
        $reportConn          = self::getReportDatabaseConnection();
        
        $defaultParams = $this->db->GetAll("
            SELECT
                LOWER(FIELD_PATH) AS FIELD_PATH, 
                'Шүүлт'           AS FIELD_TYPE, 
                DISPLAY_ORDER 
            FROM META_GROUP_CONFIG 
            WHERE MAIN_META_DATA_ID = ".$this->db->Param(0)." 
                AND PARENT_ID IS NULL 
                AND IS_CRITERIA = 1 
                AND DATA_TYPE <> 'group' 
            UNION 
            SELECT 
                FIELD_PATH, 
                FIELD_TYPE, 
                DISPLAY_ORDER 
            FROM VW_RP_KEYS 
            ORDER BY DISPLAY_ORDER ASC", 
            [$dataViewId]
        );
        
        $departmentId = null;

        if (isset($realParams['departmentid'])) {
            $departmentId = $realParams['departmentid'];
        } elseif (isset($realParams['filterdepartmentid'])) {
            $departmentId = $realParams['filterdepartmentid'];
        }

        if (!$departmentId) {
            $sessionUserKeyDepartmentId = Ue::sessionUserKeyDepartmentId();
            $departmentId = $sessionUserKeyDepartmentId ? $sessionUserKeyDepartmentId : Ue::sessionDepartmentId();
        }

        if (is_array($departmentId)) {
            if (count($departmentId) == 1) {
                $departmentId = $departmentId[0];
            } else {
                $departmentId = null;
            }
        }
        
        $currentDate = Date::currentDate();
        $dbConn = $this->db;
        $isReportServerDb = false;
        
        if ($reportConn) {
                        
            $reportDbDriver = $reportConn['DB_TYPE'] == 'oracle' ? 'oci8' : $reportConn['DB_TYPE'];
            $reportDbSid = $reportConn['SID'];
            $reportDbName = $reportDbSid ? $reportDbSid : $reportConn['SERVICE_NAME']; 
            $reportDbHost = $reportConn['HOST_NAME']; 
            $reportDbUserName = $reportConn['USER_NAME']; 
            $reportDbUserPass = $reportConn['USER_PASSWORD']; 

            $rdb = ADONewConnection($reportDbDriver);
            $rdb->debug = DB_DEBUG;
            $rdb->connectSID = $reportDbSid ? true : false;
            $rdb->autoRollback = true;
            $rdb->datetime = true;
            
            try {
                $rdb->Connect($reportDbHost, $reportDbUserName, $reportDbUserPass, $reportDbName);
                $dbConn = $rdb;
                $isReportServerDb = true;
            } catch (Exception $e) { } 

            $rdb->SetCharSet(DB_CHATSET);
        }
        
        foreach ($defaultParams as $k => $defaultParam) {
            
            $fieldType = $defaultParam['FIELD_TYPE'];
            $rparamId = getUIDAdd($k); 
            
            if ($fieldType == 'Шүүлт' && isset($params[$defaultParam['FIELD_PATH']])) {
                                
                $insertData = array(
                    'ID'           => $rparamId, 
                    'REPORT_ID'    => $reportId, 
                    'PARAM_NAME'   => $defaultParam['FIELD_PATH'],  
                    'CREATED_DATE' => $currentDate
                );
                $dbConn->AutoExecute('RP_REPORT_PARAMS', $insertData);
                $dbConn->UpdateClob('RP_REPORT_PARAMS', 'PARAM_VALUE', $params[$defaultParam['FIELD_PATH']], 'ID = '.$rparamId);
                
            } elseif ($fieldType == 'Тогтмол' && isset($constantKeys['#'.$defaultParam['FIELD_PATH'].'#'])) {

                $insertData = array(
                    'ID'           => $rparamId, 
                    'REPORT_ID'    => $reportId, 
                    'PARAM_NAME'   => $defaultParam['FIELD_PATH'],
                    'CREATED_DATE' => $currentDate
                );
                $dbConn->AutoExecute('RP_REPORT_PARAMS', $insertData);        
                $dbConn->UpdateClob('RP_REPORT_PARAMS', 'PARAM_VALUE', $constantKeys['#'.$defaultParam['FIELD_PATH'].'#'], " ID = ".$rparamId);
                
            } elseif ($fieldType == 'Тохиргооны утгууд') {
                
                $lowerFieldKey = strtolower($defaultParam['FIELD_PATH']);
                $paramValue    = Config::get($defaultParam['FIELD_PATH'], 'departmentId='.$departmentId.';');
                
                if ($lowerFieldKey == 'organization_logo_path') {
                    $paramValue = URL . $paramValue;
                }
                
                $insertData = array(
                    'ID'           => $rparamId, 
                    'REPORT_ID'    => $reportId, 
                    'PARAM_NAME'   => $defaultParam['FIELD_PATH'],
                    'CREATED_DATE' => $currentDate
                );
                $dbConn->AutoExecute('RP_REPORT_PARAMS', $insertData); 
                $dbConn->UpdateClob('RP_REPORT_PARAMS', 'PARAM_VALUE', $paramValue, " ID = ".$rparamId);
            }
        }
        
        $date = Date::currentDate('Y-m-d');
        
        $dbConn->Execute("DELETE FROM RP_REPORT_PARAMS WHERE CREATED_DATE < ".$this->db->ToDate("'$date'", 'YYYY-MM-DD'));

        if ($isReportServerDb) {
            
            for ($c = 1; $c <= 10; $c++) {
                    
                if ($c > 1) {
                    sleep(1);
                }

                $checkCount = $dbConn->GetOne("SELECT COUNT(1) FROM RP_DESIGN_HEADER WHERE ID = ".$dbConn->Param(0), [$reportId]);

                if ($checkCount) {
                    Mdstatement::$isReportServer = true;
                    break;
                }
            }
            
            $rdb->Close();
        }
        
        return true; 
    }
    
    public function iframeReportTemplateCopySaveModel() {
        
        try {
            
            $statementId = Input::post('statementId');
            
            $checkRow = $this->db->GetOne("
                SELECT 
                    REPORT_LAYOUT_ID 
                FROM RP_REPORT_LAYOUT 
                WHERE REPORT_LAYOUT_ID = ".$this->db->Param(0), 
                array($statementId)
            );
            
            if ($checkRow) {
                return array('status' => 'error', 'message' => 'Statement meta дээр сонгосон тайлан дээр өмнө нь загвар үүссэн байна!');
            }
            
            $srcStatementId = Input::post('srcStatementId');
            $dataViewId     = Input::post('dataViewId');
            $templateName   = Input::post('templateName');
            $userId         = Ue::sessionUserId();
            $currentDate    = Date::currentDate();
            
            $result = $this->db->Execute("
                INSERT INTO RP_REPORT_LAYOUT ( 
                    REPORT_LAYOUT_ID, 
                    MAIN_META_DATA_ID, 
                    REPORT_LAYOUT_NAME, 
                    LAYOUT_DATA, 
                    CREATED_USER_ID, 
                    CREATED_DATE 
                ) 
                SELECT 
                    $statementId AS REPORT_LAYOUT_ID, 
                    $dataViewId AS MAIN_META_DATA_ID, 
                    '$templateName' AS REPORT_LAYOUT_NAME, 
                    LAYOUT_DATA, 
                    $userId AS CREATED_USER_ID, 
                    '$currentDate' AS CREATED_DATE 
                FROM RP_REPORT_LAYOUT 
                WHERE REPORT_LAYOUT_ID = $srcStatementId");
            
            $response = array('status' => 'success', 'message' => 'Амжилттай хуулагдлаа.');
            
        } catch (ADODB_Exception $ex) {
            $response = array('status' => 'error', 'message' => $ex->getMessage());
        }
        
        return $response;
    }
    
    public function getExpandDataViewIdModel($statementId) {    
        
        $id = $this->db->GetOne("
            SELECT 
                GROUP_DATA_VIEW_ID 
            FROM META_STATEMENT_LINK 
            WHERE META_DATA_ID = ".$this->db->Param(0), 
            array($statementId)
        );
        
        return $id;
    }

    public function writeStatementRenderSysLog($dataViewId, $statementId, $type, $params, $resultCount){
        includeLib('Detect/Browser');
        $browser = new Browser();
        $inparams = array(
            // 'id' => $id,
            'requestKey' => Ue::sessionUserKeyDepartmentId(),
            'commandName' => $type,
            'dbUnitName' => $dataViewId,
            'userName' => Ue::getSessionUserName(),
            'requestDataElement' => json_encode($params),
            'responseDataElement' => $resultCount,
            'createdDate' => Date::currentDate(),
            'ipAddress' => get_client_ip(),
            'userId' => Ue::sessionUserKeyId(),
            'systemMetaGroupId' => $statementId,
            'userAgent' => $browser->getUserAgent()
        );
        $result = $this->ws->runResponse(GF_SERVICE_ADDRESS, "systemLogDV_001", $inparams);
        return array('result' => $result);
    }
    
}