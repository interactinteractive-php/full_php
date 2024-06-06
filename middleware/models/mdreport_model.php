<?php

if (!defined('_VALID_PHP'))
    exit('Direct access to this location is not allowed.');

if (class_exists('Mdworkflow_Model') != true) {

    class Mdreport_model extends Model {

        public function __construct() {
            parent::__construct();
        }

        public function getDataMartList() {
            if (DB_DRIVER == 'mysql') {
                $data = $this->db->GetAll("select table_name NAME from information_schema.tables where TABLE_SCHEMA = '" . DB_NAME . "' AND table_name LIKE 'VW_DM%' ORDER BY table_name");
            }
            if (DB_DRIVER == 'oci8') {
                $data = $this->db->GetAll("SELECT DISTINCT NAME FROM USER_DEPENDENCIES WHERE REFERENCED_OWNER=UPPER('" . DB_USER . "') AND (TYPE = 'VIEW' OR TYPE='TABLE') AND NAME LIKE 'VW_DM%' ORDER BY NAME");
            }

            $datas = array();
            foreach ($data as &$d) {
                $dd = array(
                    'name' => $d['NAME'],
                    'id' => $d['NAME']
                );
                array_push($datas, $dd);
            }

            return $datas;
        }

        public function getColumnList($tableName) {

//AND OWNER='VERITECH_DEV_ORG'
            if (DB_DRIVER == 'mysql') {
                $data = $this->db->GetAll("SELECT DISTINCT ORDINAL_POSITION AS COLUMN_ID, TABLE_NAME, COLUMN_NAME, DATA_TYPE FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = '" . DB_NAME . "' AND TABLE_NAME = '" . $tableName . "' ORDER BY ORDINAL_POSITION");
            }
            if (DB_DRIVER == 'oci8') {
                $data = $this->db->GetAll("select DISTINCT COLUMN_ID, TABLE_NAME, COLUMN_NAME, DATA_TYPE from ALL_TAB_COLUMNS where TABLE_NAME = UPPER('" . $tableName . "') and DATA_TYPE != 'UNDEFINED' AND OWNER=UPPER('" . DB_USER . "') order by COLUMN_ID");
            }
//$data = $this->db->GetAll("select DISTINCT COLUMN_ID, TABLE_NAME, COLUMN_NAME, DATA_TYPE from ALL_TAB_COLUMNS where TABLE_NAME = UPPER('" . $tableName . "') and DATA_TYPE != 'UNDEFINED' order by COLUMN_ID");
            $datas = array();
            foreach ($data as &$d) {
                $dd = array(
                    'tableName' => $d['TABLE_NAME'],
                    'id' => $d['COLUMN_NAME'],
                    'fieldType' => $d['DATA_TYPE']
                );

                array_push($datas, $dd);
            }

            return $datas;
        }

        public function getColumn($tableName, $columnName) {


            if (DB_DRIVER == 'mysql') {
                $data = $this->db->GetAll("SELECT DISTINCT ORDINAL_POSITION AS COLUMN_ID, TABLE_NAME, COLUMN_NAME, DATA_TYPE FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = '" . DB_NAME . "' AND TABLE_NAME = '" . $tableName . "' AND COLUMN_NAME ='" . $columnName . "' ORDER BY ORDINAL_POSITION");
            }
            if (DB_DRIVER == 'oci8') {
                $data = $this->db->GetAll("select DISTINCT COLUMN_ID, TABLE_NAME, COLUMN_NAME, DATA_TYPE from ALL_TAB_COLUMNS where TABLE_NAME = UPPER('" . $tableName . "') and DATA_TYPE != 'UNDEFINED' AND COLUMN_NAME ='" . $columnName . "' order by COLUMN_ID");
            }
            foreach ($data as &$d) {
                $dd = array(
                    'tableName' => $d['TABLE_NAME'],
                    'id' => $d['COLUMN_NAME'],
                    'fieldType' => $d['DATA_TYPE']
                );
                return $dd;
            }

            return null;
        }

        public function getAllData($tableName) {
            if (DB_DRIVER == 'mysql') {
                $query = "select * from " . $tableName . " ";
            }
            if (DB_DRIVER == 'oci8') {
                $query = "SELECT * FROM " . $tableName . "";
            }
            $data = $this->db->GetAll($query);
            return $data;
        }

        public function getPreviewData($data) {
            if (DB_DRIVER == 'mysql') {
                $getReportCode = $this->db->GetRow("SELECT * FROM rep_report WHERE view_name ='".$data['tableName']."'");
                $query = "select " . $data["select"] . " from rep_greport_".$getReportCode['REPORT_CODE']." WHERE REPORT_MONTH = '".$getReportCode['LAST_DATA_UPDATE_BEGIN_DATE']."' GROUP BY " . $data['group'] . " order by " . $data['group'] . " LIMIT 10 ";
              // Хурдан ажиллах
//                $query = "SELECT * FROM (select " . $data["select"] . " from " . $data["tableName"] . " LIMIT 10 ) DATAS GROUP BY " . $data['group'] . " order by " . $data['group'] . " ";
            }
            if (DB_DRIVER == 'oci8') {
                $query = "SELECT * FROM (select " . $data["select"] . " from " . $data["tableName"] . "  GROUP BY " . $data['group'] . " order by " . $data['group'] . ") WHERE ROWNUM<=10";
            }
//            var_dump($query);die;
            $data = $this->db->GetAll($query);
            return $data;
        }

        public function saveChart($model) {
            $userId = Ue::sessionUserId();
            $chartId = $model['chartId'] == '0' ? getUID() : $model['chartId'];
            $data = array(
                'CHART_ID' => $chartId,
                'CHART_NAME' => $model['chartName'],
                'CHART_TYPE' => $model['chartType'],
                'REPORT_MODEL_ID' => $model['modelId'],
                'VALUE_COLUMN_ID' => $model['valueColumnId'],
                'CREATED_USER_ID' => ($userId == null ? 1 : $userId));

            if ($model['chartId'] == '0')
                $result = $this->db->AutoExecute('dm_chart', $data, 'INSERT');
            else
                $result = $this->db->AutoExecute('dm_chart', $data, 'UPDATE', 'CHART_ID = ' . $chartId);
            if ($result) {
                return $chartId;
            }
            return false;
        }

        public function getChart($chartId) {
            $data = $this->db->GetAll("SELECT * FROM DM_CHART WHERE CHART_ID=" . $chartId);

            $datas = array();
            foreach ($data as &$d) {
                $dd = array(
                    'id' => $d['CHART_ID'],
                    'chartName' => $d['CHART_NAME'],
                    'chartType' => $d['CHART_TYPE'],
                    'modelId' => $d['REPORT_MODEL_ID'],
                    'valueColumnId' => $d['VALUE_COLUMN_ID']
                );

                return $dd;
            }
            return null;
        }

        public function saveReport($model, $rows, $columns, $facts, $filter, $headerHtml, $footerHtml) {

//            var_dump($columns);
//            var_dump($rows);
//                        die();

            $userId = Ue::sessionUserId();
            $hdrId = $model['modelId'] == '0' ? getUID() : $model['modelId'];
            $data = array(
                'REPORT_MODEL_ID' => $hdrId,
                'REPORT_MODEL_NAME' => $model['modelName'],
                'DATA_MART_NAME' => $model['tableName'],
                'CREATED_USER_ID' => $userId,
                'HEADER_HTML' => $headerHtml,
                'FOOTER_HTML' => $footerHtml,
                'IS_ACTIVE' => 1);


            if ($model['modelId'] == '0')
                $result = $this->db->AutoExecute('dm_report_model', $data, 'INSERT');
            else
                $result = $this->db->AutoExecute('dm_report_model', $data, 'UPDATE', 'REPORT_MODEL_ID = ' . $hdrId);

            if ($result) {
                $result = $this->db->Execute("DELETE FROM dm_report_model_row WHERE REPORT_MODEL_ID = " . $hdrId . "");
                if ($result) {

                    for ($j = 0; $j < sizeof($rows); $j++) {

                        $data = array(
                            'REPORT_MODEL_ROW_ID' => getUID(),
                            'REPORT_MODEL_ID' => $hdrId,
                            'FIELD_NAME' => $rows[$j]['field'],
                            'HEADER' => $rows[$j]['title'],
                            'FORMAT' => $rows[$j]['format'],
                            'ALIGN' => $rows[$j]['align'],
                            'MASK' => $rows[$j]['mask'],
                            'IS_VISIBLE' => $rows[$j]['isVisible'] == 'true' ? 1 : 0,
                            'VIEW_ORDER' => $rows[$j]['viewOrder']);

                        $result = $this->db->AutoExecute('dm_report_model_row', $data, 'INSERT');
                    }
                }

                $result = $this->db->Execute("DELETE FROM dm_report_model_column WHERE REPORT_MODEL_ID = " . $hdrId . "");
                if ($result) {
                    for ($j = 0; $j < sizeof($columns); $j++) {

                        $data = array(
                            'REPORT_MODEL_COLUMN_ID' => getUID(),
                            'REPORT_MODEL_ID' => $hdrId,
                            'FIELD_NAME' => $columns[$j]['field'],
                            'HEADER' => $columns[$j]['title'],
                            'FORMAT' => $columns[$j]['format'],
                            'ALIGN' => $columns[$j]['align'],
                            'MASK' => $columns[$j]['mask'],
                            'IS_VISIBLE' => $columns[$j]['isVisible'] == 'true' ? 1 : 0,
                            'VIEW_ORDER' => $columns[$j]['viewOrder']);

                        $result = $this->db->AutoExecute('dm_report_model_column', $data, 'INSERT');
                    }
                }

                $result = $this->db->Execute("DELETE FROM dm_report_model_fact WHERE REPORT_MODEL_ID = " . $hdrId . "");
                if ($result) {
                    for ($j = 0; $j < sizeof($facts); $j++) {

                        $data = array(
                            'REPORT_MODEL_FACT_ID' => getUID(),
                            'REPORT_MODEL_ID' => $hdrId,
                            'FIELD_NAME' => $facts[$j]['field'],
                            'VIEW_ORDER' => $j + 1);

                        $result = $this->db->AutoExecute('dm_report_model_fact', $data, 'INSERT');
                    }
                }


                $result = $this->db->Execute("DELETE FROM dm_report_model_filter WHERE REPORT_MODEL_ID = " . $hdrId . "");
                if ($result) {
                    for ($j = 0; $j < sizeof($filter); $j++) {

                        $data = array(
                            'REPORT_MODEL_FILTER_ID' => getUID(),
                            'REPORT_MODEL_ID' => $hdrId,
                            'FIELD_NAME' => $filter[$j]['field'],
                            'FIELD_HEADER' => $filter[$j]['title'],
                            'META_DATA_ID' => $filter[$j]['metadata']);

                        $result = $this->db->AutoExecute('dm_report_model_filter', $data, 'INSERT');
                    }
                }

                return $hdrId;
            }
            return false;
        }

        public function reportDataGridModel() {
            $page = Input::postCheck('page') ? Input::post('page') : 1;
            $rows = Input::postCheck('rows') ? Input::post('rows') : 10;
            $offset = ($page - 1) * $rows;
            $condition = "";

            $sortField = 'CREATED_DATE';
            $sortOrder = 'DESC';
            if (Input::postCheck('sort') && Input::postCheck('order')) {
                $sortField = Input::post('sort');
                $sortOrder = Input::post('order');
            }

            $selectCount = "SELECT 
                                COUNT(*) AS ROW_COUNT   
                            FROM dm_report_model
                            WHERE IS_ACTIVE = 1";

            $selectList = " SELECT * 
                        FROM dm_report_model
                            ORDER BY $sortField $sortOrder 
                        ";

            $rowCount = $this->db->GetRow($selectCount);

            $result = $items = array();
            $result["total"] = $rowCount['ROW_COUNT'];

            $rs = $this->db->SelectLimit($selectList, $rows, $offset);

            foreach ($rs as $row) {
                array_push($items, $row);
                unset($row);
            }
            $result["rows"] = $items;

            return $result;
        }

        public function chartDataGridModel() {
            $page = Input::postCheck('page') ? Input::post('page') : 1;
            $rows = Input::postCheck('rows') ? Input::post('rows') : 10;
            $offset = ($page - 1) * $rows;
            $condition = "";

            $sortField = 'DC.CREATED_DATE';
            $sortOrder = 'DESC';
            if (Input::postCheck('sort') && Input::postCheck('order')) {
                $sortField = Input::post('sort');
                $sortOrder = Input::post('order');
            }

            $selectCount = "SELECT 
                                COUNT(*) AS ROW_COUNT   
                            FROM DM_CHART
                            WHERE IS_ACTIVE = 1";

            $selectList = " SELECT DC.* , RM.REPORT_MODEL_NAME, RM.DATA_MART_NAME
                        FROM DM_CHART DC
                        INNER JOIN DM_REPORT_MODEL RM ON DC.REPORT_MODEL_ID=RM.REPORT_MODEL_ID
                            ORDER BY $sortField $sortOrder 
                        ";

            $rowCount = $this->db->GetRow($selectCount);

            $result = $items = array();
            $result["total"] = $rowCount['ROW_COUNT'];

            $rs = $this->db->SelectLimit($selectList, $rows, $offset);

            foreach ($rs as $row) {
                array_push($items, $row);
                unset($row);
            }
            $result["rows"] = $items;

            return $result;
        }

        function array_delete($array, $element) {

            $i = 0;
            foreach ($array as $key) {
                if ($key['id'] == $element['id'])
                    unset($array[$i]);
                $i++;
            }
            $array = array_values($array);
//            var_dump($element);
//            var_dump($array);
//            die();

            return $array;
        }

        public function getReport($modelId) {

            $data = $this->db->GetAll("SELECT * FROM dm_report_model WHERE REPORT_MODEL_ID = " . $modelId . "");
            $rowlist = array();
            $factlist = array();
            $collist = array();
            $filterlist = array();
            $headerHtml = '';
            $footerHtml = '';
            $whereString = "WHERE 1=1 ";


            if ($data != null) {
                $data = $data[0];
                $modelId = $data['REPORT_MODEL_ID'];
                $modelName = $data['REPORT_MODEL_NAME'];
                $viewName = $data['DATA_MART_NAME'];
                $headerHtml = $data['HEADER_HTML'];
                $footerHtml = $data['FOOTER_HTML'];

                $viewCols = $this->getColumnList($viewName);
                $allRowsCols = $this->getColumnList($viewName);

                $facts = $this->db->GetAll("SELECT * FROM dm_report_model_fact WHERE  REPORT_MODEL_ID = " . $modelId . " ORDER BY VIEW_ORDER");

                foreach ($facts as $key) {
                    $tempArray = array(
                        'id' => $key['REPORT_MODEL_FACT_ID'],
                        'field' => $key['FIELD_NAME']
                    );

                    array_push($factlist, $tempArray);
                }

                $rows = $this->db->GetAll("SELECT * FROM dm_report_model_row WHERE  REPORT_MODEL_ID = " . $modelId . " ORDER BY VIEW_ORDER");
                foreach ($rows as $key) {
                    $col = $this->getColumn($viewName, $key['FIELD_NAME']);


                    //
                    $viewCols = $this->array_delete($viewCols, $col);
                    $tempArray = array(
                        'id' => $key['REPORT_MODEL_ROW_ID'],
                        'modelId' => $key['REPORT_MODEL_ID'],
                        'colType' => $col['fieldType'],
                        'field' => $key['FIELD_NAME'],
                        'title' => $key['HEADER'],
                        'format' => $key['FORMAT'],
                        'align' => $key['ALIGN'],
                        'mask' => $key['MASK'],
                        'isVisible' => $key['IS_VISIBLE'] == 1
                    );

                    array_push($rowlist, $tempArray);
                }




                $cols = $this->db->GetAll("SELECT * FROM dm_report_model_column WHERE  REPORT_MODEL_ID = " . $modelId . "  ORDER BY VIEW_ORDER");

                foreach ($cols as $key) {
                    $col = $this->getColumn($viewName, $key['FIELD_NAME']);

                    $viewCols = $this->array_delete($viewCols, $col);
                    $tempArray = array(
                        'id' => $key['REPORT_MODEL_COLUMN_ID'],
                        'colType' => $col['fieldType'],
                        'field' => $key['FIELD_NAME'],
                        'title' => $key['HEADER'],
                        'format' => $key['FORMAT'],
                        'align' => $key['ALIGN'],
                        'mask' => $key['MASK'],
                        'isVisible' => $key['IS_VISIBLE'] == 1
                    );

                    array_push($collist, $tempArray);
                }

                $filters = $this->db->GetAll("SELECT * FROM dm_report_model_filter WHERE  REPORT_MODEL_ID = " . $modelId . "");

                foreach ($filters as $key) {
                  
                    $filter = $this->getColumn($viewName, $key['FIELD_NAME']);
                    $allRowsCols = $this->array_delete($allRowsCols, $filter);
                    
                    $tempArray = array(
                        'id' => $key['REPORT_MODEL_FILTER_ID'],
                        'field' => $key['FIELD_NAME'],
                        'title' => $key['FIELD_HEADER'],
                        'metadata' => $key['META_DATA_ID']
                    );
                    array_push($filterlist, $tempArray);
                }


                $rdata = array(
                    'modelId' => $modelId,
                    'modelName' => $modelName,
                    'viewName' => $viewName,
                    'headerHtml' => $headerHtml,
                    'footerHtml' => $footerHtml,
                    'viewCols' => $viewCols,
                    'allRowsCols' => $allRowsCols,
                    'rows' => $rowlist,
                    'cols' => $collist,
                    'facts' => $factlist,
                    'filters' => $filterlist
                );
            }


            return $rdata;
        }

        public function getReportSourceList() {
            $data = $this->db->GetAll("SELECT * FROM dm_report_model WHERE IS_ACTIVE=1 ORDER BY REPORT_MODEL_NAME ");
            $datas = array();
            foreach ($data as &$d) {
                $dd = array(
                    'id' => $d['REPORT_MODEL_ID'],
                    'modelName' => $d['REPORT_MODEL_NAME'],
                    'martName' => $d['DATA_MART_NAME']
                );

                array_push($datas, $dd);
            }
            return $datas;
        }

        public function getReportFilter($modelId) {
            $filterlist = array();
            $filters = array();
            try {
                $filters = $this->db->GetAll("SELECT
                    MF.REPORT_MODEL_FILTER_ID,
                    MF.FIELD_NAME,
                    MF.FIELD_HEADER,
                    RM.DATA_MART_NAME
                    FROM dm_report_model_filter MF
                    INNER JOIN dm_report_model RM ON  MF.REPORT_MODEL_ID = RM.REPORT_MODEL_ID
                    WHERE MF.REPORT_MODEL_ID = " . $modelId . "");
            } catch (Exception $e) {
                
            }
            
            if (sizeof($filters) == 0) {
                $filters = $this->db->GetAll("SELECT
                    MF.REPORT_MODEL_FILTER_ID,
                    MF.FIELD_NAME,
                    MF.FIELD_HEADER,
                    MF.REPORT_MODEL_FILTER_ID as META_DATA_ID,
                    MF.FIELD_NAME as META_DATA_CODE,
                    MF.FIELD_HEADER as META_DATA_NAME,
                    null as META_TYPE_CODE,
                    null as META_OBJECT_NAME,
                    null as META_OBJECT_QUERY,
                    RM.DATA_MART_NAME
                    FROM dm_report_model_filter MF
                    INNER JOIN dm_report_model RM ON RM.REPORT_MODEL_ID = MF.REPORT_MODEL_ID
                    WHERE MF.REPORT_MODEL_ID = " . $modelId . "");
            }

            foreach ($filters as $key) {

//                if ($key['META_TYPE_CODE'] == null) {
                    $col = $this->getColumn($key['DATA_MART_NAME'], $key['FIELD_NAME']);

                    $type = 'TEXT';
                    switch (strtolower($col['fieldType'])) {
                        case 'number':
                        case 'bigint':
                        case 'tinyint':
                        case 'bigint':
                        case 'bigint':
                        case 'bigint':
                            $type = 'NUMBER';
                            break;
                        case 'date':
                            $type = 'DATE';
                            break;
                    }
                    $key['META_TYPE_CODE'] = $type;
//                }

//                if ($key['META_DATA_ID'] == null || $key['META_DATA_ID'] == '0' || $key['META_DATA_ID'] == '') {
                    $key['META_DATA_ID'] = $key['REPORT_MODEL_FILTER_ID'];
                    $key['META_DATA_NAME'] = $key['FIELD_HEADER'];
                    $key['META_DATA_CODE'] = $key['FIELD_NAME'];
//                }



                $tempArray = array(
                    'filterId' => $key['REPORT_MODEL_FILTER_ID'],
                    'field' => $key['FIELD_NAME'],
                    'metadata' => $key['META_DATA_ID'],
                    'metadataCode' => $key['META_DATA_CODE'],
                    'metadataName' => $key['META_DATA_NAME'],
                    'metatype' => $key['META_TYPE_CODE']
                );

                array_push($filterlist, $tempArray);
            }
            
            return $filterlist;
        }

        public function getReportSource($modelId, $modelfilters, $rowFilterValues) {
            
            $page = Input::postCheck('page') ? Input::post('page') : 1;
            $rowNum = Input::postCheck('rowNum') ? Input::post('rowNum') : 1000;
            $offset = ($page - 1) * $rowNum;
            
            $reportMonth = $modelfilters['reportMonth'];
            
            $result = array();
            $items = array();

            $data = $this->db->GetAll("SELECT * FROM dm_report_model WHERE REPORT_MODEL_ID = " . $modelId . "");
            $rowlist = array();
            $factlist = array();
            $collist = array();
            $filterlist = array();
            $headerHtml = '';
            $footerHtml = '';
            $whereString = "WHERE 1=1 AND REPORT_MONTH = '".$reportMonth."' ";


            if ($data != null) {
                $data = $data[0];
                $modelId = $data['REPORT_MODEL_ID'];
                $headerHtml = $data['HEADER_HTML'];
                $footerHtml = $data['FOOTER_HTML'];

                $groupBy = '-';
                $selectBy = '-';
                $selectByDrill = '-';

                $facts = $this->db->GetAll("SELECT * FROM dm_report_model_fact WHERE  REPORT_MODEL_ID = " . $modelId . " ORDER BY VIEW_ORDER");

                foreach ($facts as $key) {
                    $tempArray = array(
                        'id' => $key['REPORT_MODEL_FACT_ID'],
                        'field' => $key['FIELD_NAME']
                    );

                    array_push($factlist, $tempArray);

                    $groupBy = $groupBy . ', ' . $key['FIELD_NAME'];
                    $selectBy = $selectBy . ', ' . $key['FIELD_NAME'] . ' AS "' . $key['FIELD_NAME'] . '"';
                    $selectByDrill = $selectByDrill . ', ' . $key['FIELD_NAME'] . ' AS "' . $key['FIELD_NAME'] . '"';
                }

                $rows = $this->db->GetAll("SELECT * FROM dm_report_model_row WHERE  REPORT_MODEL_ID = " . $modelId . " ORDER BY VIEW_ORDER");
                foreach ($rows as $key) {
                    $tempArray = array(
                        'id' => $key['REPORT_MODEL_ROW_ID'],
                        'modelId' => $key['REPORT_MODEL_ID'],
                        'field' => $key['FIELD_NAME'],
                        'title' => $key['HEADER'],
                        'format' => $key['FORMAT'],
                        'align' => $key['ALIGN'],
                        'mask' => $key['MASK'],
                        'isVisible' => $key['IS_VISIBLE'] == 1
                    );

                    array_push($rowlist, $tempArray);

                    $groupBy = $groupBy . ', ' . $key['FIELD_NAME'];
                    $selectBy = $selectBy . ', ' . $key['FIELD_NAME'] . ' AS "' . $key['FIELD_NAME'] . '"';
                    $selectByDrill = $selectByDrill . ', ' . $key['FIELD_NAME'] . ' AS "' . $key['FIELD_NAME'] . '"';

                    if ($rowFilterValues != null) {


                        //die();

                        foreach ($rowFilterValues as $rowFilter) {
                            if ($rowFilter[0] == $tempArray['id']) {
                                if ($rowFilter[1] != 'null')
                                    $whereString.=' AND ' . $tempArray['field'] . "='" . $rowFilter[1] . "' ";
                                else
                                    $whereString.=' AND ' . $tempArray['field'] . " is null ";

//                                var_dump($rowFilter);
//                                var_dump($whereString);
                            }
                        }
                    }
                }





                $cols = $this->db->GetAll("SELECT * FROM dm_report_model_column WHERE  REPORT_MODEL_ID = " . $modelId . "  ORDER BY VIEW_ORDER");

                foreach ($cols as $key) {
                    $tempArray = array(
                        'id' => $key['REPORT_MODEL_COLUMN_ID'],
                        'field' => $key['FIELD_NAME'],
                        'title' => $key['HEADER'],
                        'format' => $key['FORMAT'],
                        'align' => $key['ALIGN'],
                        'mask' => $key['MASK'],
                        'isVisible' => $key['IS_VISIBLE'] == 1
                    );

                    array_push($collist, $tempArray);

                    $selectBy = $selectBy . ', ' . $key['FORMAT'] . '(' . $key['FIELD_NAME'] . ') AS "' . $key['FIELD_NAME'] . '"';
                    $selectByDrill = $selectByDrill . ', ' . $key['FIELD_NAME'] . ' AS "' . $key['FIELD_NAME'] . '"';
                }

                $groupBy = str_replace('-, ', '', $groupBy);
                $selectBy = str_replace('-, ', '', $selectBy);
                $selectByDrill = str_replace('-, ', '', $selectByDrill);

                
                if (isset($modelfilters['filter'])) {
                    $modelfilters = $modelfilters['filter'];


                    foreach ($modelfilters as $modelfilter) {

                        $modelfilter['value1'] = trim($modelfilter['value1']);
                        $modelfilter['value2'] = trim($modelfilter['value2']);

                        if ($modelfilter['filterId'] == '0') {
                            $status = $this->db->GetAll("select set_param(':" . $modelfilter['value2'] . "', '" . $modelfilter['value1'] . "') FROM DUAL");
                            //var_dump($status);
                        } else {
                            $filter = $this->db->GetAll("SELECT * FROM dm_report_model_filter WHERE REPORT_MODEL_FILTER_ID = " . $modelfilter['filterId'] . "");

                            if ($filter != null && isset($filter[0]) && $modelfilter['value1'] != "") {
//                              var_dump($modelfilter['value1']);die;
                                $filter = $filter[0];
//                                var_dump($modelfilter);
                                switch ($modelfilter['metatype']) {
                                
                                    case 'TEXT':
                                    case 'TEXTAREA':
                                    case 'COMBO':
                                    case 'LOV':
                                    case 'TABLE':
                                    case 'GRID_TABLE':
                                    case 'VIEW':
                                        switch ($modelfilter['type']) {
                                            case '=': $whereString.=' AND LOWER(' . $filter['FIELD_NAME'] . ")=LOWER('" . $modelfilter['value1'] . "') ";
                                                break;
                                            case '<>': $whereString.=' AND LOWER(' . $filter['FIELD_NAME'] . ")<>LOWER('" . $modelfilter['value1'] . "') ";
                                                break;
                                            case '>': $whereString.=' AND LOWER(' . $filter['FIELD_NAME'] . ")>LOWER('" . $modelfilter['value1'] . "') ";
                                                break;
                                            case '<': $whereString.=' AND LOWER(' . $filter['FIELD_NAME'] . ")<LOWER('" . $modelfilter['value1'] . "') ";
                                                break;
                                            case 'like': $whereString.=' AND LOWER(' . $filter['FIELD_NAME'] . ") LIKE LOWER('%" . $modelfilter['value1'] . "%') ";
                                                break;
                                            case 'between':
                                                if ($modelfilter['value2'] != "") {
                                                    $whereString.=' AND LOWER(' . $filter['FIELD_NAME'] . ") BETWEEN LOWER('" . $modelfilter['value1'] . "') AND LOWER('" . $modelfilter['value2'] . "') ";
                                                }
                                                break;
                                            default: break;
                                        }
                                        break;
                                    case 'NUMBER':
                                        $modelfilter['value1'] = str_replace(",", "", $modelfilter['value1']);
                                        $modelfilter['value2'] = str_replace(",", "", $modelfilter['value2']);
                                        switch ($modelfilter['type']) {
                                            case '=': $whereString.=' AND ' . $filter['FIELD_NAME'] . "=" . $modelfilter['value1'] . " ";
                                                break;
                                            case '<>': $whereString.=' AND ' . $filter['FIELD_NAME'] . "<>" . $modelfilter['value1'] . " ";
                                                break;
                                            case '>': $whereString.=' AND ' . $filter['FIELD_NAME'] . ">" . $modelfilter['value1'] . " ";
                                                break;
                                            case '<': $whereString.=' AND ' . $filter['FIELD_NAME'] . "<" . $modelfilter['value1'] . " ";
                                                break;
                                            case 'like': $whereString.=' AND LOWER(' . $filter['FIELD_NAME'] . ") LIKE LOWER('%" . $modelfilter['value1'] . "%') ";
                                                break;
                                            case 'between':
                                                if ($modelfilter['value2'] != "") {
                                                    $whereString.=' AND ' . $filter['FIELD_NAME'] . " BETWEEN " . $modelfilter['value1'] . " AND " . $modelfilter['value2'] . " ";
                                                }
                                                break;
                                            default: break;
                                        }
                                        break;
                                    case 'DATE':
                                        switch ($modelfilter['type']) {
                                            case '=': $whereString.=' AND TO_CHAR(' . $filter['FIELD_NAME'] . ",'YYYY-MM-DD')='" . $modelfilter['value1'] . "' ";
                                                break;
                                            case '<>': $whereString.=' AND TO_CHAR(' . $filter['FIELD_NAME'] . ",'YYYY-MM-DD')<>'" . $modelfilter['value1'] . "' ";
                                                break;
                                            case '>': $whereString.=' AND ' . $filter['FIELD_NAME'] . ">TO_DATE('" . $modelfilter['value1'] . "','YYYY-MM-DD') ";
                                                break;
                                            case '<': $whereString.=' AND ' . $filter['FIELD_NAME'] . "<TO_DATE('" . $modelfilter['value1'] . "','YYYY-MM-DD') ";
                                                break;
                                            case 'like':
                                                break;
                                            case 'between':
                                                if ($modelfilter['value2'] != "") {
                                                    $whereString.=' AND ' . $filter['FIELD_NAME'] . " BETWEEN TO_DATE('" . $modelfilter['value1'] . "','YYYY-MM-DD') AND TO_DATE('" . $modelfilter['value2'] . "','YYYY-MM-DD') ";
                                                }
                                                break;
                                            default: break;
                                        }
                                        break;
                                }
                            }
                        }
                    }
                }

                if ($rowFilterValues != null) {
                    $query = "select " . $selectByDrill . " from " . $data['DATA_MART_NAME'] . " " . $whereString . " order by " . $groupBy . "";
                    //   var_dump($query);
                    //   die();
                } else {
                    $getReportCode = $this->db->GetRow("SELECT * FROM rep_report WHERE view_name ='".$data['DATA_MART_NAME']."'");
                    
                    Session::init();
                    $departmentLevelCode = Session::get(SESSION_PREFIX.'levelCode');
                    $departmentId = Session::get(SESSION_PREFIX.'departmentid');
//                    var_dump($departmentLevelCode);die;
                    $departmentIds = array();
                    switch ($departmentLevelCode) {
                        case '1':
                          $query = "select " . $selectBy . " from rep_greport_".$getReportCode['REPORT_CODE']." " . $whereString . "  GROUP BY " . $groupBy . " order by " . $groupBy . "";
                          $queryCount = "SELECT COUNT(*) AS ROW_COUNT FROM (select " . $selectBy . " from rep_greport_".$getReportCode['REPORT_CODE']." " . $whereString . "  GROUP BY " . $groupBy . " order by " . $groupBy . " ) DATAS";
                          break;
                        case '2':
                          array_push($departmentIds, $departmentId);
                          $getSoumIds = $this->db->GetAll("SELECT DEPARTMENT_ID FROM sw_department WHERE IS_ACTIVE = 1 AND PARENT_ID = ". $departmentId);
                          foreach ($getSoumIds as $val){
                            array_push($departmentIds, $val['DEPARTMENT_ID']);
                            $childIds = $this->db->GetAll("SELECT DEPARTMENT_ID FROM sw_department WHERE IS_ACTIVE = 1 AND PARENT_ID = ". $val['DEPARTMENT_ID']);
                            foreach($childIds as $v){
                              array_push($departmentIds, $v['DEPARTMENT_ID']);
                            }
                          }
                          
                          $departmentIds = implode(",", $departmentIds);
                          $whereString .=" AND DEPARTMENT_ID IN(".$departmentIds.")";
                          $query = "select " . $selectBy . " from rep_greport_".$getReportCode['REPORT_CODE']." " . $whereString . "  GROUP BY " . $groupBy . " order by " . $groupBy . "";
                          $queryCount = "SELECT COUNT(*) AS ROW_COUNT FROM (select " . $selectBy . " from rep_greport_".$getReportCode['REPORT_CODE']." " . $whereString . "  GROUP BY " . $groupBy . " order by " . $groupBy . " ) DATAS";
//                          var_dump($query);die;
                          break;
                        case '3':
                          array_push($departmentIds, $departmentId);
                          $childIds = $this->db->GetAll("SELECT DEPARTMENT_ID FROM sw_department WHERE IS_ACTIVE = 1 AND PARENT_ID = ". $departmentId);
                          foreach ($childIds as $val){
                            array_push($departmentIds, $val['DEPARTMENT_ID']);
                          }
                          $departmentIds = implode(",", $departmentIds);
                          $whereString .=" AND DEPARTMENT_ID IN(".$departmentIds.")";
                          $query = "select " . $selectBy . " from rep_greport_".$getReportCode['REPORT_CODE']." " . $whereString . "  GROUP BY " . $groupBy . " order by " . $groupBy . "";
                          $queryCount = "SELECT COUNT(*) AS ROW_COUNT FROM (select " . $selectBy . " from rep_greport_".$getReportCode['REPORT_CODE']." " . $whereString . "  GROUP BY " . $groupBy . " order by " . $groupBy . " ) DATAS";
                          break;
                        case '4':
                          $whereString.=" AND DEPARTMENT_ID=" . $departmentId . "";
                          $query = "select " . $selectBy . " from rep_greport_".$getReportCode['REPORT_CODE']." " . $whereString . "  GROUP BY " . $groupBy . " order by " . $groupBy . "";
                          $queryCount = "SELECT COUNT(*) AS ROW_COUNT FROM (select " . $selectBy . " from rep_greport_".$getReportCode['REPORT_CODE']." " . $whereString . "  GROUP BY " . $groupBy . " order by " . $groupBy . " ) DATAS";
                          break;
                    }
                    
//                    $query = "select " . $selectBy . " from " . $data['DATA_MART_NAME'] . " " . $whereString . "  GROUP BY " . $groupBy . " order by " . $groupBy . "";
//                    $queryCount = "SELECT COUNT(*) AS ROW_COUNT FROM (select " . $selectBy . " from " . $data['DATA_MART_NAME'] . " " . $whereString . "  GROUP BY " . $groupBy . " order by " . $groupBy . " ) DATAS";
                    
                }
                  
//                var_dump($this->db->GetRow("SELECT * FROM social_welfare_report.rep_greport_0003_service_and_person"));die;
                $datasCount = $this->db->GetRow($queryCount);
//                var_dump($datasCount);
                $result["total"] = $datasCount['ROW_COUNT'];
//                var_dump($datasCount);die;
//                $data = $this->db->GetAll($query);
//                var_dump($data); die;
//                var_dump($query);die; 
//                var_dump($rowNum, $offset);die;
                $rs = $this->db->SelectLimit($query, $rowNum, $offset);
//                var_dump($rs);die;
                foreach ($rs as $row) {
                  array_push($items, $row);
                }
//                var_dump($items);die;
                $result["datas"] = $items;
                $result["page"] = $page;
                $rowNums = array('1000', '100', '10');
                $result["rowNums"] = $rowNums;
                $result["rowNum"] = $rowNum;
//                VAR_dump($query); 
//                var_dump($data);
//                die;
//                $data = $this->db->GetAll($query);
            }
            
            $dataElement = array('username' => Session::get(SESSION_PREFIX.'username'), 
                                'departmentname' => Session::get(SESSION_PREFIX.'departmentname'),
                                'lastname' => SUBSTR(Session::get(SESSION_PREFIX.'lastname') ,0 ,6),
                                'firstname' => Session::get(SESSION_PREFIX.'firstname'),
                                'rolename' => Session::get(SESSION_PREFIX.'rolename'),
                                'date' => Date::currentDate());
//                            var_dump($dataElement);die;
            $templateHeaderHTML = htmlspecialchars_decode($headerHtml);
            $templateFooterHTML = htmlspecialchars_decode($footerHtml);
//            var_dump($templateHeaderHTML); die;
            foreach ($dataElement as $key => $value) {
                if (!is_array($value)) {
                    $templateHeaderHTML = str_replace('#' . $key . '#', $dataElement[$key], $templateHeaderHTML);
                } else {
                    $templateHeaderHTML = self::parseTemplateDtl($templateHeaderHTML, $key, $value, $template['DATA_MODEL_ID']);
                }
            }
//            die;
//            var_dump($templateHeaderHTML); die;

            foreach ($dataElement as $key => $value) {
                if (!is_array($value)) {
                    $templateFooterHTML = str_replace('#' . $key . '#', $dataElement[$key], $templateFooterHTML);
                } else {
                    $templateFooterHTML = self::parseTemplateDtl($templateFooterHTML, $key, $value, $template['DATA_MODEL_ID']);
                }
            }
        $headerHtml = $templateHeaderHTML;
        $footerHtml = $templateFooterHTML;
        
            $data = array(
                'headerHtml' => $headerHtml,
                'footerHtml' => $footerHtml,
//                'data' => $data,
                'data' => $result,
                'rows' => $rowlist,
                'cols' => $collist,
                'facts' => $factlist
            );

            return $data;
        }

        public function saveReportTemplate($template) {
            $userId = Ue::sessionUserId();
            $id = $template['templateId'] == '0' ? getUID() : $template['templateId'];
            $data = array(
                'REPORT_TEMPLATE_ID' => $id,
                'REPORT_TEMPLATE_NAME' => $template['templateName'],
                'REPORT_HEADER_HTML' => $template['headerHtml'],
                'REPORT_FOOTER_HTML' => $template['footerHtml'],
                'CREATED_USER_ID' => '1');
//                'CREATED_USER_ID' => $userId);


            if ($template['templateId'] == '0')
                $result = $this->db->AutoExecute('dm_report_template', $data, 'INSERT');
            else {
                $result = $this->db->AutoExecute('dm_report_template', $data, 'UPDATE', 'REPORT_TEMPLATE_ID = ' . $id);
            }

            if ($result) {
                return $id;
            }
            return false;
        }

        public function getReportTemplateList() {
            $data = $this->db->GetAll("SELECT * FROM dm_report_template");

            $datas = array();
            foreach ($data as &$d) {
                $dd = array(
                    'id' => $d['REPORT_TEMPLATE_ID'],
                    'name' => $d['REPORT_TEMPLATE_NAME']
                );

                array_push($datas, $dd);
            }
            return $datas;
        }

        public function getReportTemplate($id) {

            $data = $this->db->GetAll("SELECT * FROM dm_report_template WHERE REPORT_TEMPLATE_ID = " . $id . "");

            $d = $data[0];
            $data = array(
                'id' => $d['REPORT_TEMPLATE_ID'],
                'Name' => $d['REPORT_TEMPLATE_NAME'],
                'headerHtml' => $d['REPORT_HEADER_HTML'],
                'footerHtml' => $d['REPORT_FOOTER_HTML'],
                'createdUserId' => $d['CREATED_USER_ID'],
                'createdDate' => $d['CREATED_DATE'],
                'modifiedUserId' => $d['MODIFIED_USER_ID'],
                'modifiedDate' => $d['MODIFIED_DATE']
            );
            
            return $data;
        }
        
        public function getExportExcelSource($modelId, $modelfilters, $rowFilterValues) {
          
            $reportMonth = $modelfilters['reportMonth'];
//            var_dump($reportMonth);die;
            $data = $this->db->GetAll("SELECT * FROM dm_report_model WHERE REPORT_MODEL_ID = " . $modelId . "");
            $rowlist = array();
            $factlist = array();
            $collist = array();
            $filterlist = array();
            $headerHtml = '';
            $footerHtml = '';
            $whereString = "WHERE 1=1 AND REPORT_MONTH = '".$reportMonth."' ";


            if ($data != null) {
                $data = $data[0];
                $modelId = $data['REPORT_MODEL_ID'];
                $headerHtml = $data['HEADER_HTML'];
                $footerHtml = $data['FOOTER_HTML'];

                $groupBy = '-';
                $selectBy = '-';
                $selectByDrill = '-';

                $facts = $this->db->GetAll("SELECT * FROM dm_report_model_fact WHERE  REPORT_MODEL_ID = " . $modelId . " ORDER BY VIEW_ORDER");

                foreach ($facts as $key) {
                    $tempArray = array(
                        'id' => $key['REPORT_MODEL_FACT_ID'],
                        'field' => $key['FIELD_NAME']
                    );

                    array_push($factlist, $tempArray);

                    $groupBy = $groupBy . ', ' . $key['FIELD_NAME'];
                    $selectBy = $selectBy . ', ' . $key['FIELD_NAME'] . ' AS "' . $key['FIELD_NAME'] . '"';
                    $selectByDrill = $selectByDrill . ', ' . $key['FIELD_NAME'] . ' AS "' . $key['FIELD_NAME'] . '"';
                }

                $rows = $this->db->GetAll("SELECT * FROM dm_report_model_row WHERE  REPORT_MODEL_ID = " . $modelId . " ORDER BY VIEW_ORDER");
                foreach ($rows as $key) {
                    $tempArray = array(
                        'id' => $key['REPORT_MODEL_ROW_ID'],
                        'modelId' => $key['REPORT_MODEL_ID'],
                        'field' => $key['FIELD_NAME'],
                        'title' => $key['HEADER'],
                        'format' => $key['FORMAT'],
                        'align' => $key['ALIGN'],
                        'mask' => $key['MASK'],
                        'isVisible' => $key['IS_VISIBLE'] == 1
                    );

                    array_push($rowlist, $tempArray);

                    $groupBy = $groupBy . ', ' . $key['FIELD_NAME'];
                    $selectBy = $selectBy . ', ' . $key['FIELD_NAME'] . ' AS "' . $key['FIELD_NAME'] . '"';
                    $selectByDrill = $selectByDrill . ', ' . $key['FIELD_NAME'] . ' AS "' . $key['FIELD_NAME'] . '"';

                    if ($rowFilterValues != null) {


                        //die();

                        foreach ($rowFilterValues as $rowFilter) {
                            if ($rowFilter[0] == $tempArray['id']) {
                                if ($rowFilter[1] != 'null')
                                    $whereString.=' AND ' . $tempArray['field'] . "='" . $rowFilter[1] . "' ";
                                else
                                    $whereString.=' AND ' . $tempArray['field'] . " is null ";

//                                var_dump($rowFilter);
//                                var_dump($whereString);
                            }
                        }
                    }
                }





                $cols = $this->db->GetAll("SELECT * FROM dm_report_model_column WHERE  REPORT_MODEL_ID = " . $modelId . "  ORDER BY VIEW_ORDER");

                foreach ($cols as $key) {
                    $tempArray = array(
                        'id' => $key['REPORT_MODEL_COLUMN_ID'],
                        'field' => $key['FIELD_NAME'],
                        'title' => $key['HEADER'],
                        'format' => $key['FORMAT'],
                        'align' => $key['ALIGN'],
                        'mask' => $key['MASK'],
                        'isVisible' => $key['IS_VISIBLE'] == 1
                    );

                    array_push($collist, $tempArray);

                    $selectBy = $selectBy . ', ' . $key['FORMAT'] . '(' . $key['FIELD_NAME'] . ') AS "' . $key['FIELD_NAME'] . '"';
                    $selectByDrill = $selectByDrill . ', ' . $key['FIELD_NAME'] . ' AS "' . $key['FIELD_NAME'] . '"';
                }

                $groupBy = str_replace('-, ', '', $groupBy);
                $selectBy = str_replace('-, ', '', $selectBy);
                $selectByDrill = str_replace('-, ', '', $selectByDrill);


                if (isset($modelfilters['filter'])) {
                    $modelfilters = $modelfilters['filter'];


                    foreach ($modelfilters as $modelfilter) {

                        $modelfilter['value1'] = trim($modelfilter['value1']);
                        $modelfilter['value2'] = trim($modelfilter['value2']);

                        if ($modelfilter['filterId'] == '0') {
                            $status = $this->db->GetAll("select set_param(':" . $modelfilter['value2'] . "', '" . $modelfilter['value1'] . "') FROM DUAL");
                            //var_dump($status);
                        } else {
                            $filter = $this->db->GetAll("SELECT * FROM dm_report_model_filter WHERE REPORT_MODEL_FILTER_ID = " . $modelfilter['filterId'] . "");

                            if ($filter != null && isset($filter[0]) && $modelfilter['value1'] != "") {
                                $filter = $filter[0];
//                                var_dump($modelfilter);
                                switch ($modelfilter['metatype']) {
                                    case 'TEXT':
                                    case 'TEXTAREA':
                                    case 'COMBO':
                                    case 'LOV':
                                    case 'TABLE':
                                    case 'GRID_TABLE':
                                    case 'VIEW':
                                        switch ($modelfilter['type']) {
                                            case '=': $whereString.=' AND LOWER(' . $filter['FIELD_NAME'] . ")=LOWER('" . $modelfilter['value1'] . "') ";
                                                break;
                                            case '<>': $whereString.=' AND LOWER(' . $filter['FIELD_NAME'] . ")<>LOWER('" . $modelfilter['value1'] . "') ";
                                                break;
                                            case '>': $whereString.=' AND LOWER(' . $filter['FIELD_NAME'] . ")>LOWER('" . $modelfilter['value1'] . "') ";
                                                break;
                                            case '<': $whereString.=' AND LOWER(' . $filter['FIELD_NAME'] . ")<LOWER('" . $modelfilter['value1'] . "') ";
                                                break;
                                            case 'like': $whereString.=' AND LOWER(' . $filter['FIELD_NAME'] . ") LIKE LOWER('%" . $modelfilter['value1'] . "%') ";
                                                break;
                                            case 'between':
                                                if ($modelfilter['value2'] != "") {
                                                    $whereString.=' AND LOWER(' . $filter['FIELD_NAME'] . ") BETWEEN LOWER('" . $modelfilter['value1'] . "') AND LOWER('" . $modelfilter['value2'] . "') ";
                                                }
                                                break;
                                            default: break;
                                        }
                                        break;
                                    case 'NUMBER':
                                        $modelfilter['value1'] = str_replace(",", "", $modelfilter['value1']);
                                        $modelfilter['value2'] = str_replace(",", "", $modelfilter['value2']);
                                        switch ($modelfilter['type']) {
                                            case '=': $whereString.=' AND ' . $filter['FIELD_NAME'] . "=" . $modelfilter['value1'] . " ";
                                                break;
                                            case '<>': $whereString.=' AND ' . $filter['FIELD_NAME'] . "<>" . $modelfilter['value1'] . " ";
                                                break;
                                            case '>': $whereString.=' AND ' . $filter['FIELD_NAME'] . ">" . $modelfilter['value1'] . " ";
                                                break;
                                            case '<': $whereString.=' AND ' . $filter['FIELD_NAME'] . "<" . $modelfilter['value1'] . " ";
                                                break;
                                            case 'like': $whereString.=' AND LOWER(' . $filter['FIELD_NAME'] . ") LIKE LOWER('%" . $modelfilter['value1'] . "%') ";
                                                break;
                                            case 'between':
                                                if ($modelfilter['value2'] != "") {
                                                    $whereString.=' AND ' . $filter['FIELD_NAME'] . " BETWEEN " . $modelfilter['value1'] . " AND " . $modelfilter['value2'] . " ";
                                                }
                                                break;
                                            default: break;
                                        }
                                        break;
                                    case 'DATE':
                                        switch ($modelfilter['type']) {
                                            case '=': $whereString.=' AND TO_CHAR(' . $filter['FIELD_NAME'] . ",'YYYY-MM-DD')='" . $modelfilter['value1'] . "' ";
                                                break;
                                            case '<>': $whereString.=' AND TO_CHAR(' . $filter['FIELD_NAME'] . ",'YYYY-MM-DD')<>'" . $modelfilter['value1'] . "' ";
                                                break;
                                            case '>': $whereString.=' AND ' . $filter['FIELD_NAME'] . ">TO_DATE('" . $modelfilter['value1'] . "','YYYY-MM-DD') ";
                                                break;
                                            case '<': $whereString.=' AND ' . $filter['FIELD_NAME'] . "<TO_DATE('" . $modelfilter['value1'] . "','YYYY-MM-DD') ";
                                                break;
                                            case 'like':
                                                break;
                                            case 'between':
                                                if ($modelfilter['value2'] != "") {
                                                    $whereString.=' AND ' . $filter['FIELD_NAME'] . " BETWEEN TO_DATE('" . $modelfilter['value1'] . "','YYYY-MM-DD') AND TO_DATE('" . $modelfilter['value2'] . "','YYYY-MM-DD') ";
                                                }
                                                break;
                                            default: break;
                                        }
                                        break;
                                }
                            }
                        }
                    }
                }

                if ($rowFilterValues != null) {
                    $query = "select " . $selectByDrill . " from " . $data['DATA_MART_NAME'] . " " . $whereString . " order by " . $groupBy . "";
                    //   var_dump($query);
                    //   die();
                } else {
//                    $query = "select " . $selectBy . " from rep_greport_0001 " . $whereString . "  GROUP BY " . $groupBy . " order by " . $groupBy . "";
//                    $query = "select " . $selectBy . " from " . $data['DATA_MART_NAME'] . " " . $whereString . "  GROUP BY " . $groupBy . " order by " . $groupBy . "";
                  $getReportCode = $this->db->GetRow("SELECT * FROM rep_report WHERE view_name ='".$data['DATA_MART_NAME']."'");
                    
                    Session::init();
                    $departmentLevelCode = Session::get(SESSION_PREFIX.'levelCode');
                    $departmentId = Session::get(SESSION_PREFIX.'departmentid');
                    $departmentIds = array();
                    switch ($departmentLevelCode) {
                        case '1':
                          $query = "select " . $selectBy . " from rep_greport_".$getReportCode['REPORT_CODE']." " . $whereString . "  GROUP BY " . $groupBy . " order by " . $groupBy . "";
                          break;
                        case '2':
                          array_push($departmentIds, $departmentId);
                          $getSoumIds = $this->db->GetAll("SELECT DEPARTMENT_ID FROM sw_department WHERE IS_ACTIVE = 1 AND PARENT_ID = ". $departmentId);
                          foreach ($getSoumIds as $val){
                            array_push($departmentIds, $val['DEPARTMENT_ID']);
                            $childIds = $this->db->GetAll("SELECT DEPARTMENT_ID FROM sw_department WHERE IS_ACTIVE = 1 AND PARENT_ID = ". $val['DEPARTMENT_ID']);
                            foreach($childIds as $v){
                              array_push($departmentIds, $v['DEPARTMENT_ID']);
                            }
                          }
                          $departmentIds = implode(",", $departmentIds);
                          $whereString .=" AND DEPARTMENT_ID IN(".$departmentIds.")";
                          $query = "select " . $selectBy . " from rep_greport_".$getReportCode['REPORT_CODE']." " . $whereString . "  GROUP BY " . $groupBy . " order by " . $groupBy . "";
                          break;
                        case '3':
                          array_push($departmentIds, $departmentId);
                          $childIds = $this->db->GetAll("SELECT DEPARTMENT_ID FROM sw_department WHERE IS_ACTIVE = 1 AND PARENT_ID = ". $departmentId);
                          foreach ($childIds as $val){
                            array_push($departmentIds, $val['DEPARTMENT_ID']);
                          }
                          $departmentIds = implode(",", $departmentIds);
                          $whereString .=" AND DEPARTMENT_ID IN(".$departmentIds.")";
                          $query = "select " . $selectBy . " from rep_greport_".$getReportCode['REPORT_CODE']." " . $whereString . "  GROUP BY " . $groupBy . " order by " . $groupBy . "";
                          break;
                        case '4':
                          $whereString.=" AND DEPARTMENT_ID=" . $departmentId . "";
                          $query = "select " . $selectBy . " from rep_greport_".$getReportCode['REPORT_CODE']." " . $whereString . "  GROUP BY " . $groupBy . " order by " . $groupBy . "";
                          break;
                    }
                }
                $data = $this->db->GetAll($query);
//                var_dump($data);die;
            }
            
            $dataElement = array('username' => Session::get(SESSION_PREFIX.'username'), 
                                'departmentname' => Session::get(SESSION_PREFIX.'departmentname'),
                                'lastname' => SUBSTR(Session::get(SESSION_PREFIX.'lastname') ,0 ,6),
                                'firstname' => Session::get(SESSION_PREFIX.'firstname'),
                                'rolename' => Session::get(SESSION_PREFIX.'rolename'),
                                'date' => Date::currentDate());

            $templateHeaderHTML = htmlspecialchars_decode($headerHtml);
            $templateFooterHTML = htmlspecialchars_decode($footerHtml);
            foreach ($dataElement as $key => $value) {
                if (!is_array($value)) {
                    $templateHeaderHTML = str_replace('#' . $key . '#', $dataElement[$key], $templateHeaderHTML);
                } else {
                    $templateHeaderHTML = self::parseTemplateDtl($templateHeaderHTML, $key, $value, $template['DATA_MODEL_ID']);
                }
            }
            foreach ($dataElement as $key => $value) {
                if (!is_array($value)) {
                    $templateFooterHTML = str_replace('#' . $key . '#', $dataElement[$key], $templateFooterHTML);
                } else {
                    $templateFooterHTML = self::parseTemplateDtl($templateFooterHTML, $key, $value, $template['DATA_MODEL_ID']);
                }
            }
        $headerHtml = $templateHeaderHTML;
        $footerHtml = $templateFooterHTML;
            $data = array(
                'headerHtml' => $headerHtml,
                'footerHtml' => $footerHtml,
//                'data' => $data,
                'data' => $data,
                'rows' => $rowlist,
                'cols' => $collist,
                'facts' => $factlist
            );

            return $data;
            // Excel гаргах
//            require_once BASEPATH . LIBS . 'Office/Excel/PHPExcel.php';
//            require_once BASEPATH . LIBS . 'Office/Excel/PHPExcel/Writer/Excel2007.php';
//            $objPHPExcel = new PHPExcel();
//            $objPHPExcel->getActiveSheet()->setTitle('Test excel');
//            $objPHPExcel->setActiveSheetIndex(0)
//                    ->setCellValue('A1', '№')
//                    ->setCellValue('B1', 'Регистр')
//                    ->setCellValue('C1', 'Эцэг (Эх)-ийн нэр')
//                    ->setCellValue('D1', 'Нэр')
//                    ->setCellValue('E1', 'Шифр')
//                    ->setCellValue('F1', 'Үйлчилгээний нэр')
//                    ->setCellValue('G1', 'Төлөв')
//                    ->setCellValue('H1', 'Тайлбар')
//                    ->setCellValue('I1', 'Олгох мөнгө')
//                    ->setCellValue('J1', 'Олгосон мөнгө')
//                    ->setCellValue('K1', 'Олгосон огноо')
//                    ->setCellValue('L1', 'Харицах банк')
//                    ->setCellValue('M1', 'Хэлтэс, нэгж')
//                    ->setCellValue('N1', 'Олгох сар')
//                    ->setCellValue('O1', 'Жагсаалт гаргасан огноо')
//                    ->setCellValue('P1', 'Жагсаалт гаргасан хэрэглэгч')
//                    ->setCellValue('Q1', 'Илгээсэн хэрэглэгч')
//                    ->setCellValue('R1', 'Илгээсэн огноо')
//                    ->setCellValue('S1', 'Хаалт татсан хэрэглэгч')
//                    ->setCellValue('T1', 'Хаалт татсан огноо');
//            
//            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet;charset=utf-8');
//    header('Content-Disposition: attachment;filename="testExcel.xlsx"');
//    flush();
//    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
//    $objWriter->save('php://output');
        }
      
      public function getPersonStatusList(){
        $getAll = $this->db->GetAll("SELECT PERSON_STATUS_CODE, PERSON_STATUS_NAME FROM person_status WHERE IS_ACTIVE = 1");
        return $getAll;
      }
      
      public function getNationalityList(){
        $getAll = $this->db->GetAll("SELECT NATIONALITY_CODE, NATIONALITY_NAME FROM nationality WHERE IS_ACTIVE = 1");
        return $getAll;
      }
      
      public function getOriginList(){
        $getAll = $this->db->GetAll("SELECT ORIGIN_CODE, ORIGIN_NAME FROM origin WHERE IS_ACTIVE = 1");
        return $getAll;
      }
      
      public function getGenderList(){
        $getAll = $this->db->GetAll("SELECT GENDER_CODE, GENDER_NAME FROM gender WHERE IS_ACTIVE = 1");
        return $getAll;
      }
      
      public function getHousingConditionList(){
        $getAll = $this->db->GetAll("SELECT HOUSING_CONDITION_CODE, HOUSING_CONDITION_NAME FROM sw_housing_condition_type WHERE IS_ACTIVE = 1");
        return $getAll;
      }
      
      public function getEducationList(){
        $getAll = $this->db->GetAll("SELECT EDUCATION_CODE, EDUCATION_NAME FROM sw_education WHERE IS_ACTIVE = 1");
        return $getAll;
      }
      
      public function getLivingStandardList(){
        $getAll = $this->db->GetAll("SELECT LIVING_STANDARD_CODE, LIVING_STANDARD_NAME FROM sw_living_standard_type WHERE IS_ACTIVE = 1");
        return $getAll;
      }
      
      public function getAimagCityList(){
        $getAll = $this->db->GetAll("SELECT AIMAG_CITY_CODE, AIMAG_CITY_NAME FROM aimag_city WHERE IS_ACTIVE = 1");
        return $getAll;
      }
      
      public function getServiceCodeList(){
        $getAll = $this->db->GetAll("SELECT SERVICE_ID, SERVICE_CODE, SERVICE_NAME FROM sw_service WHERE IS_ACTIVE = 1");
        return $getAll;
      }
      
      public function getWfmStatusList(){
        $getAll = $this->db->GetAll("SELECT WFM_STATUS_NAME FROM wfm_status WHERE IS_ACTIVE = 1");
        return $getAll;
      }
      
        public function repFinListModel() {
            $this->load->model('mdmetadata', 'middleware/models/');

            $getMetaDataId = $this->model->getMetaDataByCodeModel('REP_FIN_DV');            

            $param = array(
                'systemMetaGroupId' => $getMetaDataId['META_DATA_ID'],
                'ignorePermission'  => 1,
                'showQuery'         => 0
            );

            $data = $this->ws->runResponse(GF_SERVICE_ADDRESS, Mddatamodel::$getDataViewCommand, $param);

            if ($data['status'] == 'success') {
                unset($data['result']['aggregatecolumns']);
                unset($data['result']['paging']);
                return $data['result'];
            }
            return array();
        }
        
        public function repFinConfigHeaderModel(){
            $getAll = $this->db->GetAll("SELECT ID, NAME, COLUMN_NAME FROM REP_FIN_COLUMNS WHERE REP_FIN_ID = " . Input::post('repFindId'));
            return $getAll;
        }        

    }

}