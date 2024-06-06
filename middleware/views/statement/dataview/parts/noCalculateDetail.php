<?php
if (self::$isMultiDetail) {
                            
    foreach ($dataRows as $n => $row) {

        $numIterations++;

        $tableBodyRow = $tableBody;
        $addonTableBodyRow = $addonTableBody;

        eval($expressionArr['rowExp']);

        foreach ($row as $k => $v) {

            if (isset(self::$data[$k.'_sum'])) {
                self::$data[$k.'_sum'] += $v;
            }
            if (isset(self::$data[$k.'_avg']) && $v != '') {
                self::$data[$k.'_avg'] += $v;
                self::$data[$k.'_avgCount'] += 1;
            }
            if (isset(self::$data[$k.'_min'])) {
                array_push(self::$data[$k.'_min'], $v);
            }
            if (isset(self::$data[$k.'_max'])) {
                array_push(self::$data[$k.'_max'], $v);
            }

            if (isset($isRenderColumn[$k])) {

                $anchorStart = '';
                $anchorEnd = '';

                if (isset(self::$drillDownColumns[$k])) {
                    $anchorStart = '<a href="javascript:;" data-row-data="'.$n.'|'.$statementId.'|'.$row['rid'].'|'.self::$uniqId.'|'.$k.'|'.Mdstatement::$isStatementModeNum.'" onclick="drillDownStatement(this);">';
                    $anchorEnd = '</a>';
                }

                $typeCode = $this->model->getTypeCodeColumnModel(self::$dataViewColumnsType, $k);

                $v = self::stCellValue($typeCode, $k, $v);

                $tableBodyRow = str_replace('#'.$k.'#', $anchorStart.$v.$anchorEnd, $tableBodyRow);

                $addonTableBodyRow = str_replace('#'.$k.'#', $anchorStart.$v.$anchorEnd, $addonTableBodyRow);
            }
        }

        $tableBodyRow = str_replace('#rownum#', $numIterations, $tableBodyRow);

        $addonTableBodyRow = str_replace('#rownum#', $numIterations, $addonTableBodyRow);

        for ($i = 1; $i <= $loopCount; ++$i) {

            $addonTableBodyLoad = phpQuery::newDocumentHTML($addonTableBodyRow);
            $addonTableBodyRowHtml = $addonTableBodyLoad['div#body-'.$i]->html();

            $addonTableBodyRows[$i] = (isset($addonTableBodyRows[$i]) ? $addonTableBodyRows[$i] : '') . $addonTableBodyRowHtml;
        }

        $appendTableRow .= $tableBodyRow;
    }

} else {

    foreach ($dataRows as $n => $row) {

        $numIterations++;

        $tableBodyRow = $tableBody;
        $addonTableBodyRow = $addonTableBody;

        eval($expressionArr['rowExp']);

        foreach ($row as $k => $v) {

            if (isset(self::$data[$k.'_sum'])) {
                self::$data[$k.'_sum'] += $v;
            }
            if (isset(self::$data[$k.'_avg']) && $v != '') {
                self::$data[$k.'_avg'] += $v;
                self::$data[$k.'_avgCount'] += 1;
            }
            if (isset(self::$data[$k.'_min'])) {
                array_push(self::$data[$k.'_min'], $v);
            }
            if (isset(self::$data[$k.'_max'])) {
                array_push(self::$data[$k.'_max'], $v);
            }

            if (isset($isRenderColumn[$k])) {

                $anchorStart = '';
                $anchorEnd = '';

                if (isset(self::$drillDownColumns[$k])) {
                    $anchorStart = '<a href="javascript:;" data-row-data="'.$n.'|'.$statementId.'|'.$row['rid'].'|'.self::$uniqId.'|'.$k.'|'.Mdstatement::$isStatementModeNum.'" onclick="drillDownStatement(this);">';
                    $anchorEnd = '</a>';
                }

                $typeCode = $this->model->getTypeCodeColumnModel(self::$dataViewColumnsType, $k);

                $v = self::stCellValue($typeCode, $k, $v);

                $tableBodyRow = str_replace('#'.$k.'#', $anchorStart.$v.$anchorEnd, $tableBodyRow);
            }
        }

        $tableBodyRow = str_replace('#rownum#', $numIterations, $tableBodyRow);
        $appendTableRow .= $tableBodyRow;
    }
}