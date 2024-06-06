<?php if (!defined('_VALID_PHP')) exit('Direct access to this location is not allowed.');

class Mdsalary_Model extends Model {

    // <editor-fold defaultstate="collapsed" desc="JAVA error message code">
    /*
     *
        01 = Edit Cache Java Error
        02 = Cache Java Error
        03 = CopyCalculateConfigValues Java Error
        04 = Salary List Java Error
        05 = Calculate Sheet Java Error
        06 = Copy Rows Java Error
        07 = Save Salary Java Error
        08 = Save Cache Java Error
        09 = Copy Column Sheet Java Error
        10 = Get Filter Values Java Error
        11 = Insert Employee Java Error
        12 = Delete Employee Java Error
        13 = Excel Import Java Error
        14 = Import Merge Java Error
     *
     */
    // </editor-fold>

    private static $gfServiceAddress = GF_SERVICE_ADDRESS;

    public function __construct() {
        parent::__construct();
    }

    public function getDepartmentList() {
        $departmentList = array();
        $this->load->model('mdmetadata', 'middleware/models/');

        $getMetaDataId = $this->model->getMetaDataByCodeModel('DepartmentPrl');            
        $metaDataId = $getMetaDataId['META_DATA_ID'];

        (Array) $param = array(
            'systemMetaGroupId' => $metaDataId,
            'showQuery' => 1, 
            'ignorePermission' => 1, 
            'paging' => array(
                'sortColumnNames' => array(
                    'code' => array(
                        'sortType' => 'asc',
                        'dataType' => 'string'
                    )
                )
            )
        );

        $data = $this->ws->runResponse(self::$gfServiceAddress, Mddatamodel::$getDataViewCommand, $param);

        if ($data['status'] === 'success' && isset($data['result'])) {

            $this->db->StartTrans(); 
            $this->db->Execute(Ue::createSessionInfo());

            $departmentList = $this->db->GetAll($data['result']);

            $this->db->CompleteTrans();
        }

        return $departmentList;
    }

    public function getCalcTypeListModel() {
        $response = array(
            'status' => 'Warning',
            'message' => 'Илэрц байхгүй байна.',
        );
        $qry = "SELECT ID, CALC_TYPE_CODE, CALC_TYPE_NAME FROM PRL_CALC_TYPE";
        $result = $this->db->GetAll($qry);
        if ($result) {
            $response = array(
                'status' => 'success',
                'data' => $result
            );
        }
        return $response;
    }

    public function getLookUpDepartmentModel($metaDataCode) {
        $qry = "SELECT META_DATA_ID, META_TYPE_ID 
    FROM META_DATA
    WHERE META_DATA_CODE = '$metaDataCode'
    ";
        $result = $this->db->GetRow($qry);
        return $result;
    }

    public function getAllChildDepartmentModel($ids, $isChild = 1) {

        if ($isChild == 1) {
            $where = "START WITH D.DEPARTMENT_ID IN ($ids)
                 CONNECT BY D.PARENT_ID = PRIOR D.DEPARTMENT_ID";
        } else {
            $where = "WHERE D.DEPARTMENT_ID IN ($ids)"; 
        }

        $this->db->StartTrans(); 
        $this->db->Execute(Ue::createSessionInfo());

        $result = $this->db->GetAll("
            SELECT 
                D.DEPARTMENT_ID AS DEPARTMENTID, 
                D.DEPARTMENT_NAME AS DEPARTMENTNAME 
            FROM VW_ORG_DEPARTMENT D
            $where");

        $this->db->CompleteTrans();    

        return $result;
    }

    public function getLookUpCalcModel($metaDataCode) {
        $qry = "SELECT META_DATA_ID, META_TYPE_ID 
    FROM META_DATA
    WHERE META_DATA_CODE = '$metaDataCode'
    ";
        $result = $this->db->GetRow($qry);
        return $result;
    }
    public function getCalcFieldListV3Model() {
        $result = array();
        $result["fields"] = array();

        if (Input::postCheck('params')) {                        

            parse_str(Input::post('params'), $params);
            $calcTypeId = (!empty($params['calcTypeId']) ? $params['calcTypeId'] : 0);
            $calcId = (!empty($params['calcId']) ? $params['calcId'] : 0);
            $bookSavedNumber = (!empty($params['bookNumber']) ? $params['bookNumber'] : 0);
            $isChild = isset($params['isChild']) ? (($params['isChild'] == 1) ? 1 : 0) : 0;
            $isEmployee = $params['prlCalculateType'] == 'employee' ? 1 : 0;
            
            $deps = $depStringIds = '';
            
            if (empty($params['departmentId']) && empty($params['employeeIds'])) {
                return array(
                    'status' => 'error',
                    'title' => 'Анхааруулга',
                    'text' => 'Хэлтэс эсвэл ажилтнаа сонгоно уу.'
                );
            }

            if(empty($params['salaryBookId']) && !empty($params['departmentId'])) {
                $deps = self::getAllChildDepartmentModel($params['departmentId'], $isChild);
                $result["recursiveDepartment"] = $deps;
            } elseif (!empty ($params['departmentId'])) {
                $deps = explode(',', $params['departmentId']);
                array_walk($deps, function(&$value) {
                    $value = array('DEPARTMENTID' => $value);
                });                    
            }
            
            if (!empty($params['employeeIds'])) {
                $getDepartmentList = $this->db->GetAll("SELECT DEPARTMENT_ID FROM HRM_EMPLOYEE_KEY WHERE EMPLOYEE_KEY_ID IN (" . $params['employeeIds'] . ")");
                
                foreach ($getDepartmentList as $drow) {
                    $depStringIds .= $drow['DEPARTMENT_ID'] . ',';
                }
            } else {
                foreach ($deps as $drow) {
                    $depStringIds .= $drow['DEPARTMENTID'] . ',';
                }
            }  
            
            $checkIgnore = $this->db->GetAll("SELECT DEPARTMENT_ID FROM PRL_CALC_TYPE_IGNORE WHERE CALC_TYPE_ID = " . $calcTypeId . " AND (" . self::whereDepertmentIds($depStringIds, 'DEPARTMENT_ID') . ")");
            
            if ($checkIgnore) {                
                $groupIgnore = Arr::groupByArray($checkIgnore, 'DEPARTMENT_ID');
                
                $checkIgnoreVar = false;
                foreach ($expDep as $erow) {
                    if (!array_key_exists($erow, $groupIgnore)) {
                        $checkIgnoreVar = true;
                    }
                }
                
                if ($checkIgnoreVar) {
                    $result = array(
                        'status' => 'error',
                        'title' => 'Анхааруулга',
                        'text' => $this->lang->line('PRL_IGNORE')
                    );
                    return $result;                
                }
            }

            $selectCalcTypeDtl = "SELECT
                MD.META_DATA_ID,
                LOWER(MD.META_DATA_CODE) AS META_DATA_CODE,
                MD.META_DATA_NAME,
                MFL.DATA_TYPE,
                PCTD.ID,
                PCTD.ORDER_NUM,
                PCTD.IS_HIDE,
                PCTD.IS_FREEZE,
                PCTD.IS_DISABLE,
                PCTD.IS_SIDEBAR,
                PCTD.LABEL_NAME,
                PCTD.CALL_PROCESS_ID,
                PCTD.IS_WHOLE,
                PCTD.LINK_META_DATA_ID, 
                PCTD.DRILLDOWN_META_DATA_ID, 
                PCTD.ICON, 
                PCTD.COLOR, 
                PCTD.EXPRESSION, 
                PCTD.IS_CARD, 
                PCTD.COLUMN_SIZE,
                " . $this->db->IfNull('PCTD.MERGE_NAME', "'$@$'") . " AS MERGE_NAME,
                MD.META_DATA_CODE AS GLOBE_CODE,
                MG.IS_SHOW AS IS_HIDE_USER_COLUMN,
                MG.HEADER_NAME AS USER_COLUMN_COLOR,
                " . $this->db->IfNull('CTI.META_DATA_ID', "'1'") . " AS IS_IGNORE
                FROM PRL_CALC_TYPE PCT
                INNER JOIN PRL_CALC_TYPE_DTL PCTD ON PCT.ID = PCTD.CALC_TYPE_ID
                INNER JOIN META_DATA MD ON PCTD.META_DATA_ID = MD.META_DATA_ID
                INNER JOIN META_FIELD_LINK MFL ON MD.META_DATA_ID = MFL.META_DATA_ID
                LEFT JOIN PRL_CALC_TYPE_IGNORE CTI ON (" . self::whereDepertmentIds($depStringIds, 'CTI.DEPARTMENT_ID') . ") 
                    AND CTI.CALC_TYPE_ID = PCT.ID 
                    AND CTI.META_DATA_ID = PCTD.META_DATA_ID
                LEFT JOIN META_GROUP_CONFIG_USER MG ON MD.META_DATA_ID = MG.MAIN_META_DATA_ID 
                    AND MG.CALC_TYPE_ID = " . $calcTypeId . " 
                    AND MG.CALC_ID = " . $calcId . " AND MG.USER_ID = " . Ue::sessionUserKeyId() . "  
                WHERE PCTD.IS_SHOW=1 AND PCT.ID = " . $calcTypeId . "
                ORDER BY PCTD.ORDER_NUM";
            $getCalcTypeDtl = $this->db->GetAll($selectCalcTypeDtl);

            if ($getCalcTypeDtl) {
                
                foreach ($getCalcTypeDtl as $dtlkey => $dtlRow) {
                    $dtlRow['META_DATA_NAME'] = Lang::line($dtlRow['GLOBE_CODE']);
                    $getCalcTypeDtl[$dtlkey] = $dtlRow;
                }

                $result['salBookId'] = '';
                $result['isSavedBook'] = '0';
                $result['batchNumber'] = '';
                $isExistSalary = false;

                if (Input::post('usebooknumber') == '1') {

                    $isExistSalary = false;

                } else {
                    if (empty($params['batchNumber']) && !empty($params['departmentId'])) {
                        $isExistSalary = self::getAllBookByDepCalcType($params['departmentId'], $calcId, $calcTypeId, $bookSavedNumber);         
                    } elseif (empty($params['batchNumber']) && !empty($params['employeeIds'])) {
                        $isExistSalary = self::getAllBookByEmpCalcType($params['employeeIds'], $calcId, $calcTypeId, $bookSavedNumber);         
                    } elseif (!empty ($params['batchNumber'])) {
                        $isExistSalary = self::getAllBookByBatchNumber($params['batchNumber']);         
                    }
                }
                
                $prlCalculateTemplateCretria = Config::getFromCache('prlCalculateTemplateCretria');

                if ($isExistSalary && $params['singleEditMode'] != '1' && $params['isBatchNumber'] == '0' && $params['isChange'] == '0' && $prlCalculateTemplateCretria === '1' && !empty($isExistSalary[0]['BATCH_NUMBER'])) {
                    
                    if ($params['fromCache'] == '0') {

                        $departmentIdsArrMap = $deps;
                        $empids = '';
                        if (!empty($params['employeeIds'])) {
                            $empids = explode(',', $params['employeeIds']);
                            array_walk($empids, function(&$value) {
                                $value = array('employeeKeyId' => $value);
                            });                                  
                        }
                        $inparams = array(
                            'prlCalcId' => $calcId,
                            'calcType' => $calcTypeId,
                            'departmentIds' => $departmentIdsArrMap, 
                            'employeeKeyIds' => $empids,
                            'hasChild' => $isChild,
                            'criteriaTemplateId' => issetParam($params['criteriaTemplateId']),
                            'isEmployeeKey' => $isEmployee
                        );

                        if (Input::post('usebooknumber') == '1') {
                            $getBookNumber = self::getBookNumberModel(50000);
                            if ($getBookNumber['status'] == 'success' && isset($getBookNumber['result'])) {
                                $inparams['bookNumber'] = $bookNumber = (int) $this->ws->getValue($getBookNumber['result']);
                            }
                        }

                        $getCacheId = $this->ws->runResponse(GF_SERVICE_ADDRESS, 'calculateCacheExecGetProcess', $inparams);  

                        if ($getCacheId['status'] === 'error') {
                            return $response = array('text' => 'Анхааруулга 02: ' . $getCacheId['text'], 'status' => 'error');
                        } elseif (empty($getCacheId['result']['id']) && empty($getCacheId['result']['result'])) {
                            return $response = array('text' => 'Ажилтны мэдээлэл олдсонгүй', 'status' => 'error');
                        }
                    }
                    
                } elseif ($isExistSalary) {
                    
                    $result["isSavedBook"] = '1';
                    $result['bookNumber'] = $isExistSalary[0]['BOOK_NUMBER'];
                    if(!empty($isExistSalary[0]['BATCH_NUMBER']) && $params['singleEditMode'] != '1') {
                        $result['batchNumber'] = $isExistSalary[0]['BATCH_NUMBER'];
                    }
                    $isExistSalary = Arr::changeKeyLower($isExistSalary);

                    $bookIds = '';
                    foreach($isExistSalary as $rval) {
                        $bookIds .= $rval['id'] . ',';
                    }
                    $result['salBookId'] = rtrim($bookIds, ',');

                    if($params['fromCache'] == '0' && $params['isBatchNumber'] == '0' && (empty($isExistSalary[0]['batch_number']) || $params['singleEditMode'] == '1')) {
                        $inparams = array(
                            'ids' => $isExistSalary,
                            'bookNumber' => $result['bookNumber'],
                            'isSaveChange' => $params['isChange']
                        );
                        
                        $getCacheId = $this->ws->runResponse(GF_SERVICE_ADDRESS, "payrollToCache", $inparams);                            
                        if ($getCacheId['status'] === 'error') {
                            return $response = array('text' => 'Анхааруулга 01: ' . $getCacheId['text'], 'status' => 'error');
                        } elseif (empty($getCacheId['result']['id']) && empty($getCacheId['result']['result'])) {
                            return $response = array('text' => 'Ажилтны мэдээлэл олдсонгүй', 'status' => 'error');
                        }

                    } elseif(($params['fromCache'] == '0' && $params['isBatchNumber'] == '1') || !empty($isExistSalary[0]['batch_number'])) {

                        if(!empty($isExistSalary[0]['batch_number'])) {
                            $inparams = array(
                                'batchNumber' => $isExistSalary[0]['batch_number'],
                                'bookNumber' => $isExistSalary[0]['book_number'],
                                'isSaveChange' => $params['isChange']
                            );                                                        
                            $getCacheId = $this->ws->runResponse(GF_SERVICE_ADDRESS, "batchToCache", $inparams);

                        } else {
                            $inparams = array(
                                'ids' => $isExistSalary,
                                'bookNumber' => $result['bookNumber'],
                                'isSaveChange' => $params['isChange']
                            );                            
                            $getCacheId = $this->ws->runResponse(GF_SERVICE_ADDRESS, "payrollToCache", $inparams);                        
                        }

                        if ($getCacheId['status'] === 'error') {
                            return $response = array('text' => 'Анхааруулга 01: ' . $getCacheId['text'], 'status' => 'error');
                        } elseif (empty($getCacheId['result']['id']) && empty($getCacheId['result']['result'])) {
                            return $response = array('text' => 'Ажилтны мэдээлэл олдсонгүй', 'status' => 'error');
                        }
                    }

                } else {

                    if ($params['fromCache'] == '0') {

                        $departmentIdsArrMap = $deps;
                        $empids = '';
                        if (!empty($params['employeeIds'])) {
                            $empids = explode(',', $params['employeeIds']);
                            array_walk($empids, function(&$value) {
                                $value = array('employeeKeyId' => $value);
                            });                                  
                        }
                        $inparams = array(
                            'prlCalcId' => $calcId,
                            'calcType' => $calcTypeId,
                            'departmentIds' => $departmentIdsArrMap, 
                            'employeeKeyIds' => $empids,
                            'hasChild' => $isChild,
                            'criteriaTemplateId' => issetParam($params['criteriaTemplateId']),
                            'isEmployeeKey' => $isEmployee
                        );

                        if (Input::post('usebooknumber') == '1') {
                            $getBookNumber = self::getBookNumberModel(50000);
                            if ($getBookNumber['status'] == 'success' && isset($getBookNumber['result'])) {
                                $inparams['bookNumber'] = $bookNumber = (int) $this->ws->getValue($getBookNumber['result']);
                            }
                        }

                        $getCacheId = $this->ws->runResponse(GF_SERVICE_ADDRESS, 'calculateCacheExecGetProcess', $inparams);  

                        if ($getCacheId['status'] === 'error') {
                            return $response = array('text' => 'Анхааруулга 02: ' . $getCacheId['text'], 'status' => 'error');
                        } elseif (empty($getCacheId['result']['id']) && empty($getCacheId['result']['result'])) {
                            return $response = array('text' => 'Ажилтны мэдээлэл олдсонгүй', 'status' => 'error');
                        }
                    }
                }

                if (!$isExistSalary) {
                    
                    $inparams = array(
                        'prlCalcId' => $calcId,
                        'calcType' => $calcTypeId,
                        'processCacheId' => isset($getCacheId) ? (empty($getCacheId['result']['id']) ? $getCacheId['result']['result'] : $getCacheId['result']['id']) : $params['javaCacheId']
                    );
                    
                    $getCalculateConfigValue = $this->ws->runResponse(GF_SERVICE_ADDRESS, "copyCalculateConfigValues", $inparams);                    
                    if ($getCalculateConfigValue['status'] === 'error') {
                        return $response = array('text' => 'Анхааруулга 03: ' . $getCalculateConfigValue['text'], 'status' => 'error');                    
                    }
                }

                $result["fields"] = $getCalcTypeDtl;
                $result["status"] = 'success';
                $result["javaCacheId"] = isset($getCacheId) ? (empty($getCacheId['result']['id']) ? $getCacheId['result']['result'] : $getCacheId['result']['id']) : $params['javaCacheId'];
                $result["isduplicate"] = isset($getCacheId) ? (isset($getCacheId['result']['isduplicate']) ? $getCacheId['result']['isduplicate'] : '') : '';

                if (isset($bookNumber)) {
                    $result['bookNumber'] = $bookNumber;
                }

                return $result;

            } else {
                $result = array(
                    'status' => 'error',
                    'title' => 'Анхааруулга',
                    'text' => 'Тухайн загварын тохиргоо хийгдээгүй байна.'
                );
                return $result;
            }
        }
    }

    public function getBookNumberModel($objectId) {

        $param = array(
            'objectId' => $objectId
        );

        return $this->ws->runResponse(GF_SERVICE_ADDRESS, 'FIN_AUNUM_004', $param);
    }

    public function getCalcFieldListImportExcelModel($export = true) {
        $result["fields"] = array();

        if (Input::postCheck('params')) {

            parse_str(Input::post('params'), $params);
            $calcTypeId = (!empty($params['calcTypeId']) ? $params['calcTypeId']:0);
            $calcId = (!empty($params['calcId']) ? $params['calcId']:0);
            $isChild = isset($params['isChild']) ? (($params['isChild'] == 1) ? 1 : 0) : 0;
            $deps = $depStringIds = '';
            
            if(empty($params['salaryBookId']) && !empty($params['departmentId'])) {
                $deps = self::getAllChildDepartmentModel($params['departmentId'], $isChild);
                $result["recursiveDepartment"] = $deps;
            } elseif (!empty ($params['departmentId'])) {
                $deps = explode(',', $params['departmentId']);
                array_walk($deps, function(&$value) {
                    $value = array('DEPARTMENTID' => $value);
                });                    
            }                   
            
            if (!empty($params['employeeIds'])) {
                $getDepartmentList = $this->db->GetAll("SELECT DEPARTMENT_ID FROM HRM_EMPLOYEE_KEY WHERE EMPLOYEE_KEY_ID IN (" . $params['employeeIds'] . ")");

                foreach ($getDepartmentList as $drow) {
                    $depStringIds .= $drow['DEPARTMENT_ID'] . ',';
                }
            } else {                    
                foreach ($deps as $drow) {
                    $depStringIds .= $drow['DEPARTMENTID'] . ',';
                }            
            }                     
            
            $columnName = $export ? 'IGNORE_XLS_EXPORT' : 'IGNORE_XLS_IMPORT';
            
            $selectCalcTypeDtl = "SELECT
                MD.META_DATA_ID,
                LOWER(MD.META_DATA_CODE) AS META_DATA_CODE,
                " . $this->db->IfNull("GD.CODE", "MD.META_DATA_NAME") . " AS META_DATA_NAME,
                MFL.DATA_TYPE,
                PCTD.ORDER_NUM,
                PCTD.IS_HIDE,
                PCTD.IS_FREEZE,
                PCTD.IS_DISABLE,
                PCTD.IS_SIDEBAR,
                PCTD.LABEL_NAME,
                PCTD.CALL_PROCESS_ID,
                PCTD.IS_WHOLE,
                PCTD.LINK_META_DATA_ID, 
                PCTD.EXPRESSION, 
                " . $this->db->IfNull("GD.CODE", "MD.META_DATA_CODE") . " AS GLOBE_CODE,
                " . $this->db->IfNull('CTI.META_DATA_ID', "'1'") . " AS IS_IGNORE
                FROM PRL_CALC_TYPE PCT
                INNER JOIN PRL_CALC_TYPE_DTL PCTD ON PCT.ID = PCTD.CALC_TYPE_ID
                INNER JOIN META_DATA MD ON PCTD.META_DATA_ID = MD.META_DATA_ID
                INNER JOIN META_FIELD_LINK MFL ON MD.META_DATA_ID = MFL.META_DATA_ID
                LEFT JOIN GLOBE_DICTIONARY GD ON GD.CODE=MD.META_DATA_CODE
                LEFT JOIN PRL_CALC_TYPE_IGNORE CTI ON CTI.DEPARTMENT_ID IN (" . rtrim($depStringIds, ',') . ") 
                    AND CTI.CALC_TYPE_ID = " . $calcTypeId . " 
                    AND CTI.META_DATA_ID = PCTD.META_DATA_ID                       
                WHERE PCT.ID = $calcTypeId AND PCTD.IS_SHOW = 1 AND (IS_SIDEBAR IS NULL OR IS_SIDEBAR = 0) AND (PCTD.IS_HIDE = 0 OR PCTD.IS_HIDE IS NULL) AND PCTD.$columnName = 1
                ORDER BY PCTD.ORDER_NUM";
            $getCalcTypeDtl = $this->db->GetAll($selectCalcTypeDtl);

            $result2 = array();
            foreach ($getCalcTypeDtl as $row) {
                if ($row['IS_IGNORE'] === '1') {
                    $row['META_DATA_NAME'] = Lang::line($row['GLOBE_CODE']);
                    $result2[] = $row;
                }
            }            

            $result["fields"] = $result2;
            $result["status"] = 'success';
            return $result;
        }
    }

    public function getSalarySheetWebserviceModel() {

        parse_str(Input::post('params'), $params);

        $offset = Input::postCheck('page') === true ? Input::post('page') : 1;
        $pageSize = Input::postCheck('rows') === true ? Input::post('rows') : 50;
        $inparams = array(
            'processCacheId' => Input::post('javaCacheId'),
            'groupPath' => 'dtl',
            'paging' => array(
                'offset' => $offset,
                'pageSize' => $pageSize
            )
        );

        if (Input::postCheck('sort') && Input::postCheck('order')) {
            $sortField = Input::post('sort');
            $sortOrder = Input::post('order');

            /*
            $sheetData = Input::post('sheet');
            $sheetIndex = Input::post('dataIndex');
            $this->saveCacheSalarySheetWebserviceModel($sheetData, $sheetIndex);
            */

            $getMtype = $this->getMetaFieldTypeByCodeModel($sortField);
            
            $inparams['paging']['sortColumnNames'] = array(
                $sortField => array(
                    'sortType' => $sortOrder,
                    'dataType' => $getMtype
                )
            );
            
            $inparams['paging']['sortColumnNames']['isgl'] = array(
                'sortType' => 'asc',
                'dataType' => 'string'
            );
            
            $inparams['paging']['sortColumnNames']['islock'] = array(
                'sortType' => 'asc',
                'dataType' => 'string'
            );
        } else {
            $inparams['paging']['sortColumnNames']['firstname'] = array(
                'sortType' => 'asc',
                'dataType' => 'string'
            );            
        }

        if (Input::postCheck('salaryFilter') && !empty($_POST['salaryFilter'])) {
            $criteria = $_POST['salaryFilter'];

            if (count($criteria) > 0) {

                if (isset($criteria[0])) {

                    foreach ($criteria as $value) {
                        if (!empty($value['value'])) {
                            $paramFilter[$value['field']][] = array('operator' => $value['condition'], 'operand' => $value['value']);
                            $paramFilter[$value['field']]['dataType'] = $this->getMetaFieldTypeByCodeModel($value['field']);
                        }
                    }

                } else {
                    foreach ($criteria as $key => $value) {

                        if (!empty($value)) {

                            if ($key === 'firstname' || $key === 'lastname' || $key === 'employeecode') {

                                $paramFilter[$key][] = array('operator' => 'like', 'operand' => $value);
                                $paramFilter[$key]['dataType'] = 'string';

                            } else {
                                $getMtype = $this->getMetaFieldTypeByCodeModel($key);
                                // $op = Str::lower($getMtype) === 'string' ? 'like' : '=';
                                $op = 'like';

                                $paramFilter[$key][] = array('operator' => $op, 'operand' => $value);
                                // $paramFilter[$key]['dataType'] = $this->getMetaFieldTypeByCodeModel($key);
                                $paramFilter[$key]['dataType'] = "string";
                            }
                        }
                    }
                }

                if (isset($paramFilter))
                    $inparams['criteria'] = $paramFilter;
            }
        }

        if (Input::postCheck('empKeys') && !empty($_POST['empKeys'])) {
            $criteria = explode(',', $_POST['empKeys']);

            if (count($criteria) > 0) {

                $empArr = array();
                foreach ($criteria as $value) {
                    if (!empty($value)) {
                        array_push($empArr, array('operator' => '=', 'operand' => $value));
                    }
                }

                $paramFilter['employeekeyid'] = $empArr;
                $paramFilter['employeekeyid']['dataType'] = 'bigdecimal';

                if (isset($paramFilter))
                    $inparams['criteria'] = $paramFilter;
            }
        }
    
        $getSalaryList = $this->ws->runSerializeResponse(GF_SERVICE_ADDRESS, 'get_list_from_cache', $inparams);
        
        if ($getSalaryList['status'] === 'error') {
            return $response = array('text' => 'Анхааруулга 04: ' . $getSalaryList['text'], 'status' => 'error', 'rows' => array());
        } 

        $footer = array();
        
        if (isset($getSalaryList['result']['aggregatecolumns']) 
            && is_countable($getSalaryList['result']['aggregatecolumns']) 
            && count($getSalaryList['result']['aggregatecolumns']) > 0) { 
            
            foreach ($getSalaryList['result']['aggregatecolumns'] as $fkey => $rowFooter) {
                if ($fkey !== 'firstname' && $fkey !== 'lastname' && $fkey !== 'employeecode')
                    $footer[$fkey] = $rowFooter['sum'];
            }
        }

        $totalCount = $getSalaryList['result']['paging']['filtercount'];
        unset($getSalaryList['result']['paging']);          
        unset($getSalaryList['result']['aggregatecolumns']);    

        $this->setIndex($getSalaryList['result'], $setDataIndex);

        return $response = array(
            'status' => 'success',
            'rows' => $getSalaryList['result'], 
            'footer' => array($footer), 
            'total' => $totalCount,
            'dataIndex' => $setDataIndex
        );
    }

    public function calculateSalarySheetWebserviceModel() {
        $sheetData = Input::post('sheet');
        $sheetIndex = Input::post('dataIndex');
        parse_str(Input::post('params'), $params);
        $params['metaCode'] = 'calculateCacheExpression';
        $this->saveCacheSalarySheetWebserviceModel($sheetData, $sheetIndex);

        $inparams = array(
            'processCacheId' => Input::post('javaCacheId')
        );
        $calculateSheet = $this->ws->runResponse(GF_SERVICE_ADDRESS, "calculateCacheExpression", $inparams);                
        if ($calculateSheet['status'] === 'error') {
            $params['message'] = 'Анхааруулга 05: ' . $calculateSheet['text'];
            $this->insertActionLog($params);
            return $response = array('text' => 'Анхааруулга 05: ' . $calculateSheet['text'], 'status' => 'error');
        }

        $this->insertActionLog($params);
        return $response = array(
            'status' => 'success'
        );
    }

    public function copyFieldRowSheetWebserviceModel() {
        $sheetData = Input::post('sheet');            
        $sheetIndex = Input::post('dataIndex');
        parse_str(Input::post('params'), $params);
        $params['metaCode'] = 'copyCacheRows';        
        $this->saveCacheSalarySheetWebserviceModel($sheetData, $sheetIndex);

        $inparams = array(
            'processCacheId' => Input::post('javaCacheId'),
            'metaDataCode' => Input::post('metaDataCode'),
            'value' => Input::post('value'),
        );        

        if (Input::postCheck('salaryFilter') && !empty($_POST['salaryFilter'])) {
            $criteria = $_POST['salaryFilter'];

            if (count($criteria) > 0) {

                if (isset($criteria[0])) {

                    foreach ($criteria as $value) {
                        if (!empty($value['value'])) {
                            $paramFilter[$value['field']][] = array('operator' => $value['condition'], 'operand' => $value['value']);
                            $paramFilter[$value['field']]['dataType'] = $this->getMetaFieldTypeByCodeModel($value['field']);
                        }
                    }

                } else {
                    foreach ($criteria as $key => $value) {

                        if (!empty($value)) {

                            if ($key === 'firstname' || $key === 'lastname' || $key === 'employeecode') {

                                $paramFilter[$key][] = array('operator' => 'like', 'operand' => $value);
                                $paramFilter[$key]['dataType'] = 'string';

                            } else {
                                $getMtype = $this->getMetaFieldTypeByCodeModel($key);
                                $op = Str::lower($getMtype) === 'string' ? 'like' : '=';

                                $paramFilter[$key][] = array('operator' => $op, 'operand' => $value);
                                $paramFilter[$key]['dataType'] = $this->getMetaFieldTypeByCodeModel($key);
                            }
                        }
                    }
                }

                if (isset($paramFilter))
                    $inparams['criteria'] = $paramFilter;
            }
        }        

        $calculateSheet = $this->ws->runResponse(GF_SERVICE_ADDRESS, "copyCacheRows", $inparams);                

        if ($calculateSheet['status'] === 'error') {
            $params['message'] = 'Анхааруулга 04: ' . $calculateSheet['text'];
            $this->insertActionLog($params);            
            return $response = array('text' => 'Анхааруулга 06: ' . $calculateSheet['text'], 'status' => 'error');
        }

        $this->insertActionLog($params);
        return $response = array(
            'status' => 'success'
        );
    }

    public function lockFieldRowSheetWebserviceModel() {
        $sheetData = Input::post('sheet');
        parse_str(Input::post('params'), $params);
        
        $paramFilter = array();
        $criteria = Input::post('filterParams');
        
        if ($criteria) {
            foreach ($criteria as $key => $value) {
                if ($key === 'firstname' || $key === 'lastname' || $key === 'employeecode') {

                    $paramFilter[$key][] = array('operator' => 'like', 'operand' => $value);
                    $paramFilter[$key]['dataType'] = 'string';

                } else {
                    $getMtype = $this->getMetaFieldTypeByCodeModel($key);
                    $op = Str::lower($getMtype) === 'string' ? 'like' : '=';

                    $paramFilter[$key][] = array('operator' => $op, 'operand' => $value);
                    $paramFilter[$key]['dataType'] = $this->getMetaFieldTypeByCodeModel($key);
                }
            }            
        }

        $inparams = array(
            'processCacheId' => Input::post('javaCacheId'),
            'criteria' => $paramFilter,
            'employeeKeyIds' => $sheetData,
            'isAllEmployee' => Input::post('isAllEmployee'),
            'isLock' => Input::post('isLock')
        );
        $calculateSheet = $this->ws->runResponse(GF_SERVICE_ADDRESS, "setLockPayrollCalculateSheet", $inparams);                

        if ($calculateSheet['status'] === 'error') {
            $params['message'] = 'Анхааруулга 04: ' . $calculateSheet['text'];
            return $response = array('text' => 'Анхааруулга 06: ' . $calculateSheet['text'], 'status' => 'error');
        }

        return $response = array(
            'status' => 'success'
        );
    }

    public function copyMultiFieldRowSheetWebserviceModel() {
        $criteria = Input::post('criteria');
        foreach ($criteria as $key => $value) {
            if ($key === 'firstname' || $key === 'lastname' || $key === 'employeecode') {

                $paramFilter[$key][] = array('operator' => 'like', 'operand' => $value);
                $paramFilter[$key]['dataType'] = 'string';

            } else {
                $getMtype = $this->getMetaFieldTypeByCodeModel($key);
                $op = Str::lower($getMtype) === 'string' ? 'like' : '=';

                $paramFilter[$key][] = array('operator' => $op, 'operand' => $value);
                $paramFilter[$key]['dataType'] = $this->getMetaFieldTypeByCodeModel($key);
            }
        }            

        parse_str(Input::post('params'), $params);
        $params['metaCode'] = 'copyMultipleCacheRows';                
        $inparams = array(
            'processCacheId' => Input::post('javaCacheId'),
            'values' => Input::post('values'),
            'criteria' => $paramFilter
        );
        $calculateSheet = $this->ws->runResponse(GF_SERVICE_ADDRESS, "copyMultipleCacheRows", $inparams);                

        if ($calculateSheet['status'] === 'error') {
            $params['message'] = 'Анхааруулга 06: ' . $calculateSheet['text'];
            $this->insertActionLog($params);            
            return $response = array('text' => 'Анхааруулга 06: ' . $calculateSheet['text'], 'status' => 'error');
        }

        $this->insertActionLog($params);
        return $response = array(
            'status' => 'success'
        );
    }

    public function saveSalarySheetWebserviceModel() {
        $sheetData = Input::post('sheet');            
        $sheetIndex = Input::post('dataIndex');
        $this->saveCacheSalarySheetWebserviceModel($sheetData, $sheetIndex);
        
        parse_str(Input::post('params'), $params);
        $params['metaCode'] = 'savePayrollSalary';
        
        /**
         * Insert Log All Data 
         */
        $offset = 1;
        $pageSize = 10000;
        $inparams = array(
            'processCacheId' => Input::post('javaCacheId'),
            'groupPath' => 'dtl',
            'paging' => array(
                'offset' => $offset,
                'pageSize' => $pageSize
            )
        );

        $getSalaryList = $this->ws->runSerializeResponse(GF_SERVICE_ADDRESS, 'get_list_from_cache', $inparams);

        if ($getSalaryList['status'] === 'error') {
            $params['jsonString'] = 'Анхааруулга 04: ' . $getSalaryList['text'];
        } else { 
            unset($getSalaryList['result']['paging']);          
            unset($getSalaryList['result']['aggregatecolumns']);    
            
            $setDataIndex = 0;
            $this->setIndex($getSalaryList['result'], $setDataIndex);
            $logJsonString = array(
                'rows' => json_encode($getSalaryList['result']),
                'footer' => json_encode(Input::post('datagridFooters')),
                'frozenColumn' => json_encode(Input::post('datagridFrozenColumns')),
                'column' => json_encode(Input::post('datagridColumns')),
            );
            $params['jsonString'] = Arr::encode(json_encode($logJsonString));
        }

        $inparams = array(
            'processCacheId' => Input::post('javaCacheId'),
            'bookNumber' => $params['bookNumber'],
            'salarySheetLogs' => Input::post('sheetLog')
        );

        $calculateSheet = $this->ws->runResponse(GF_SERVICE_ADDRESS, 'savePayrollSalary', $inparams);

        if ($calculateSheet['status'] === 'error') {
            $resJsonString = json_decode('{'.$calculateSheet['text'].'}', true);

            if (is_null($resJsonString)) {
                $params['message'] = 'Анхааруулга 07: ' . $calculateSheet['text'];
                $this->insertActionLog($params);                
                return $response = array('text' => 'Анхааруулга 07: ' . $calculateSheet['text'], 'status' => 'error');
            }

            $params['message'] = 'Шалгуурын алдаа';
            $this->insertActionLog($params);            
            return $response = array('result' => $resJsonString['result'], 'status' => 'errorExpression');
        }
        
        $this->insertActionLog($params);

        return $response = array(
            'status' => 'success',
            'batchNumber' => ''
        );
    }

    public function saveChangeSalarySheetWebserviceModel() {

        $inparams = array(
            'processCacheId' => Input::post('javaCacheId'),
            'salarySheetLogs' => Input::post('sheetLog'),
            'isSaveChange' => '1'
        );

        $calculateSheet = $this->ws->runResponse(GF_SERVICE_ADDRESS, 'savePayrollSalary', $inparams);

        if ($calculateSheet['status'] === 'error') {
            $resJsonString = json_decode('{'.$calculateSheet['text'].'}', true);

            if (is_null($resJsonString)) {
                return $response = array('text' => 'Анхааруулга 07: ' . $calculateSheet['text'], 'status' => 'error');
            }

            return $response = array('result' => $resJsonString['result'], 'status' => 'errorExpression');
        }

        return $response = array(
            'status' => 'success',
            'batchNumber' => isset($calculateSheet['result']['id']) ? $calculateSheet['result']['id'] : ''
        );
    }

    public function saveCacheSalarySheetWebserviceModel($sheetData, $sheetIndex) {
        if (empty($sheetData))
            return;

        $this->getIndex($sheetData, $sheetIndex);

        $inparams = array(
            'processCacheId' => Input::post('javaCacheId'), 
            'result' => $sheetData
        );

        if (Input::isEmpty('bookTypeId') == false) {
            $inparams['bookTypeId'] = Input::post('bookTypeId');
        }

        $saveCache = $this->ws->runResponse(GF_SERVICE_ADDRESS, 'savePayrollCache', $inparams);         

        if ($saveCache['status'] === 'error') {
            return array('text' => 'Анхааруулга 08: ' . $saveCache['text'], 'status' => 'error');
        }
    }

    public function copyFieldColumnSheetWebserviceModel() {
        $sheetData = Input::post('sheet');
        $sheetIndex = Input::post('dataIndex');
        parse_str(Input::post('params'), $params);
        $params['metaCode'] = 'copyCacheColumns';        
        $this->saveCacheSalarySheetWebserviceModel($sheetData, $sheetIndex);

        $inparams = array(
            'processCacheId' => Input::post('javaCacheId'),
            'srcMetaDataCode' => Input::post('srcMeta'),
            'trgMetaDataCode' => Input::post('trgMeta')
        );
        $calculateSheet = $this->ws->runResponse(GF_SERVICE_ADDRESS, "copyCacheColumns", $inparams);
        if ($calculateSheet['status'] === 'error') {
            $params['message'] = 'Анхааруулга 04: ' . $calculateSheet['text'];
            $this->insertActionLog($params);            
            return $response = array('text' => 'Анхааруулга 09: ' . $calculateSheet['text'], 'status' => 'error');
        }

        $this->insertActionLog($params);
        return $response = array(
            'status' => 'success'
        );
    }        

    public function getFilterValuesWebserviceModel() {
        $inparams = array(
            'processCacheId' => Input::post('javaCacheId'),
            'metaDataId' => Input::numeric('metaDataId')
        );
        $calculateSheet = $this->ws->runResponse(GF_SERVICE_ADDRESS, "getFilterValues", $inparams);
        if ($calculateSheet['status'] === 'error') {
            return $response = array('text' => 'Анхааруулга 10: ' . $calculateSheet['text'], 'status' => 'error');
        }

        $result = array();
        if(isset($calculateSheet['result']['average']))
            array_push ($result, $calculateSheet['result']['average']);
        elseif(isset($calculateSheet['result']['distinct']))
            $result = $calculateSheet['result']['distinct'];
        elseif(isset($calculateSheet['result']['maximum']))
            array_push ($result, $calculateSheet['result']['maximum']);
        elseif(isset($calculateSheet['result']['minimum']))
            array_push ($result, $calculateSheet['result']['minimum']);
        elseif(isset($calculateSheet['result']['sum']))
            array_push ($result, $calculateSheet['result']['sum']);

        return $response = array(
            'status' => 'success',
            'result' => $result
        );
    }        

    public function appendEmployeeSheetWebserviceModel() {
        $employees = array_map(function ($v) {
            return array('employeeKeyId' => $v['id']); 
        }, Input::post('employees'));
        parse_str(Input::post('params'), $params);
        $params['metaCode'] = 'addEmployeeToCache';

        $inparams = array(
            'processCacheId' => Input::post('javaCacheId'),
            'departmentId' => Input::post('department'),
            'employeeKeyIds' => $employees
        );
        $calculateSheet = $this->ws->runResponse(GF_SERVICE_ADDRESS, "addEmployeeToCache", $inparams);                

        $inparams = array(
            'processCacheId' => Input::post('javaCacheId')
        );        
        if (Input::post('allEmployee') == '0') {
            $inparams = array(
                'processCacheId' => Input::post('javaCacheId'),
                'employeeKeyIds' => $employees
            );
        }

        $getCalculateConfigValue = $this->ws->runResponse(GF_SERVICE_ADDRESS, "copyCalculateConfigValues", $inparams);            

        if ($calculateSheet['status'] === 'error') {
            $params['message'] = 'Анхааруулга 11: ' . $calculateSheet['text'];
            $this->insertActionLog($params);            
            return $response = array('text' => 'Анхааруулга 11: ' . $calculateSheet['text'], 'status' => 'error');
        }
        
        $this->insertActionLog($params);

        return $response = array(
            'status' => 'success'
        );
    }        

    public function deleteEmployeeSheetWebserviceModel() {
        $metaCode = 'removeEmployeeFromCache';
        
        $inparams = array(
            'processCacheId' => Input::get('javaCacheId'),
            'employeeKeyId' => Input::get('empKeyId')
        );
        
        $logParams = array(
            'metaCode' => $metaCode, 
            'calcTypeId' => Input::get('calcTypeId'), 
            'calcId' => Input::get('calcId')
        );
        
        $saveCache = $this->ws->runResponse(GF_SERVICE_ADDRESS, $metaCode, $inparams);         

        if ($saveCache['status'] === 'error') {
            $response = array('status' => 'error', 'text' => 'Анхааруулга 12: ' . $saveCache['text']);
            $logParams['message'] = $inparams['employeeKeyId'] . ' ' . $response['text'];
        } else {
            $response = array('status' => 'success');
            $logParams['message'] = $inparams['employeeKeyId'];
        }
        
        $this->insertActionLog($logParams);
        
        return $response;
    }

    public function deleteEmployeesSheetWebserviceModel() {
        $inparams = array(
            'processCacheId' => Input::post('javaCacheId')
        );

        $selectedRows = Input::post('selectedSheetRows');
        $saveCache['status'] = 'success';
        
        foreach ($selectedRows as $row) {
            if ($row['isgl'] != '1') {
                $inparams['employeeKeyId'] = $row['employeekeyid'];
                $saveCache = $this->ws->runResponse(GF_SERVICE_ADDRESS, "removeEmployeeFromCache", $inparams);         
            }
        }

        if ($saveCache['status'] === 'error') {
            return $response = array('text' => 'Анхааруулга 12: ' . $saveCache['text'], 'status' => 'error');
        }
        return $response = array(
            'status' => 'success'
        );            
    }        

    private function setIndex(&$dataArr, &$dataIndex) {
        $firstIndex = true;
        $resetArr = array();

        foreach ($dataArr as $key => $row) {
            if($firstIndex)
                $dataIndex = $key;
            array_push($resetArr, $row);
            $firstIndex = false;
        }
        $dataArr = $resetArr;
    }

    private function getIndex(&$dataArr, $sheetIndex) {
        $sheetIndex = 0;
        $resetArr = array();

        foreach ($dataArr as $key => $row) {
            if ($row) {
                $resetArr[$sheetIndex] = $row;
                $sheetIndex++;
            }
        }
        $dataArr = $resetArr;
    }

    public function editSalarySheetListModel($params, $post = null) {
        $metaDataNameList = '';
        $metaDataNameList2 = '';
        if($post==null){                
            $deep = Input::post('deep');
            $depId = Input::post('depId');
        }else{
            $params = $post['params'];
            $deep = 0;
            $depId = $post['depId'];
        }   

        if(isset($deep) && $deep == 1){
            $data = $this->getSalarySheetDeepListModel();
            return $data;
        }else{

//            $departmentId = $params['departmentId'];
        $departmentIds= is_array($params['departmentId']) ? implode($params['departmentId']):$params['departmentId'];
        //$departmentIds = explode(',', (isset($depId) ? $depId:$params['departmentId']));
        //echo $departmentIds;

        $calcType = $params['calcTypeId'];
        $getCalcId = $params['calcId'];
        $editSalaryBookId = array();
        $result["rows"] = "";
        $result["rows1"] = array();
        $result["sidebarList"] = "";
        $result["editSalaryBookId"] = "";
        $rows = array();
        $sidebarlist = array();
        $unCalcList = array();
        $NoSalaryBookdepartments = array();
        $deps = isset($depId) ? array($depId): (is_array($params['departmentId']) ? $params['departmentId']:explode(',',$params['departmentId']));
        //print_r($params);
        //Хэлтэсүүдийн ID-г бодогдсон бодогдоогүйгээр шүүх код эхлэл
        $selectCalcTypeSidebarList = "SELECT MD.META_DATA_ID, MD.META_DATA_CODE, 
              MD.META_DATA_NAME, PCTD.LABEL_NAME, PCTD.IS_DISABLE, MFL.DATA_TYPE
              FROM PRL_CALC_TYPE_DTL PCTD
              INNER JOIN META_DATA MD ON PCTD.META_DATA_ID = MD.META_DATA_ID
              INNER JOIN META_FIELD_LINK MFL ON MD.META_DATA_ID = MFL.META_DATA_ID
              WHERE PCTD.CALC_TYPE_ID = $calcType
              AND PCTD.IS_SHOW = 1 
              AND PCTD.IS_SIDEBAR = 1";
        $getCalcTypeSidebarList = $this->db->GetAll($selectCalcTypeSidebarList);

        $selectCalcTypeDtl =   "SELECT
                                    MD.META_DATA_ID, 
                                    MD.META_DATA_CODE, 
                                    MD.META_DATA_NAME
                                    FROM PRL_CALC_TYPE PCT
                                    INNER JOIN PRL_CALC_TYPE_DTL PCTD ON PCT.ID = PCTD.CALC_TYPE_ID
                                    INNER JOIN META_DATA MD ON PCTD.META_DATA_ID = MD.META_DATA_ID
                                    WHERE 1=1 AND PCTD.IS_SHOW=1 AND PCT.ID = $calcType
                                    ORDER BY PCTD.ORDER_NUM";
        $getCalcTypeDtl = $this->db->GetAll($selectCalcTypeDtl);
        $metaDataIdListNotIn = array();
        $count = 0;
        foreach ($getCalcTypeDtl as $key) {
            $selectIfExist = "SELECT column_name
                              FROM   all_tab_cols
                              WHERE  table_name = 'PRL_SALARY_SHEET'
                              AND column_name = UPPER('" . $key['META_DATA_CODE'] . "')";
            $getIfExist = $this->db->GetRow($selectIfExist);
            if ($getIfExist) {
                $metaDataNameList == '' ? $metaDataNameList .= $key['META_DATA_CODE'] : $metaDataNameList = $metaDataNameList . ',' . $key['META_DATA_CODE'];
                $metaDataNameList2 == '' ? $metaDataNameList2 .= "'" . $key['META_DATA_CODE'] . "'" : $metaDataNameList2 = $metaDataNameList2 . ',' . "'" . $key['META_DATA_CODE'] . "'";
            } else {
              $metaDataIdListNotIn[$count] = $key['META_DATA_ID'];
              $count++;
            }
        }
        $selectCalcTypeSidebarList2 = "SELECT MD.META_DATA_ID, MD.META_DATA_CODE, 
              MD.META_DATA_NAME, PCTD.LABEL_NAME, PCTD.IS_DISABLE, MFL.DATA_TYPE
              FROM PRL_CALC_TYPE_DTL PCTD
              INNER JOIN META_DATA MD ON PCTD.META_DATA_ID = MD.META_DATA_ID
              INNER JOIN META_FIELD_LINK MFL ON MD.META_DATA_ID = MFL.META_DATA_ID
              WHERE PCTD.CALC_TYPE_ID = $calcType
              AND PCTD.IS_SHOW = 1 
              AND MD.META_DATA_CODE NOT IN($metaDataNameList2)";
            //AND PCTD.IS_SIDEBAR = 1
            $getCalcTypeSidebarList2 = $this->db->GetAll($selectCalcTypeSidebarList2);
        foreach($deps as $d){
         $start = microtime(true);
         /* хэлтэсүүдээр ялгах давталт*/
        //Хадгалагдсан бодолт шүүх query 
        $selectEmployeeKeyIds = "SELECT
              HEK.EMPLOYEE_KEY_ID, OD.DEPARTMENT_ID,OD.DEPARTMENT_NAME, HE.EMPLOYEE_CODE, HE.LAST_NAME, HE.FIRST_NAME
              FROM ORG_DEPARTMENT OD
              INNER JOIN HRM_EMPLOYEE_KEY HEK ON OD.DEPARTMENT_ID = HEK.DEPARTMENT_ID
              INNER JOIN HRM_EMPLOYEE HE ON HEK.EMPLOYEE_ID = HE.EMPLOYEE_ID
              WHERE HEK.IS_ACTIVE = 1 AND HE.IS_ACTIVE = 1
              AND OD.DEPARTMENT_ID = $d OR HEK.IS_ACTIVE = 0 AND HE.IS_ACTIVE = 1
              AND OD.DEPARTMENT_ID = $d
              ORDER BY NLSSORT(HE.FIRST_NAME,'NLS_SORT = generic_m')";
        $getEmployeeKeyIds = $this->db->GetAll($selectEmployeeKeyIds);
        $selectSalaryBook = "SELECT * 
        FROM PRL_SALARY_BOOK PSB
        WHERE PSB.DEPARTMENT_ID = $d
        AND PSB.CALC_ID = $getCalcId
        AND PSB.CALC_TYPE_ID = $calcType";

        $getSalaryBook = $this->db->GetRow($selectSalaryBook);
        if ($getSalaryBook) {

            array_push($editSalaryBookId,$getSalaryBook['ID']);
            $salaryBookIds = implode(',', $editSalaryBookId);
            $salaryBookId = $d;

            $selectList = "SELECT
                      PST.ID AS SALARY_SHEET_ID, PST.SAL_BOOK_ID, HEK.DEPARTMENT_ID, HEK.EMPLOYEE_KEY_ID, HE.EMPLOYEE_CODE, HE.LAST_NAME, HE.FIRST_NAME,OD.DEPARTMENT_NAME,
                      $metaDataNameList 
                        FROM PRL_SALARY_SHEET PST
                        LEFT JOIN HRM_EMPLOYEE_KEY HEK ON PST.EMPLOYEE_KEY_ID = HEK.EMPLOYEE_KEY_ID
                        LEFT JOIN HRM_EMPLOYEE HE ON HEK.EMPLOYEE_ID = HE.EMPLOYEE_ID
                        INNER JOIN ORG_DEPARTMENT OD ON HEK.DEPARTMENT_ID = OD.DEPARTMENT_ID
                        WHERE SAL_BOOK_ID IN ($salaryBookIds)
                        ORDER BY NLSSORT(HE.FIRST_NAME,'NLS_SORT = generic_m'),
                        NLSSORT(HE.LAST_NAME,'NLS_SORT = generic_m')
                        ";

            $rs = $this->db->GetAll($selectList);

            $rows = array();

            foreach ($rs as $k => $row) {
                foreach ($row as $key => $value) {
                    $tmpKey = $key;                              

                    if ($key !== 'DEPARTMENT_ID' && $key !== 'EMPLOYEE_KEY_ID' && $key !== 'EMPLOYEE_CODE' &&
                        $key !== 'LAST_NAME' && $key !== 'FIRST_NAME' && $key !== 'SALARY_SHEET_ID') {

                        $tmpKey = strtolower($key);
                        $existSheetLog = $this->db->GetAll("SELECT ID FROM PRL_SALARY_SHEET_LOG WHERE SHEET_ID = " . $row['SALARY_SHEET_ID'] . " AND LOWER(META_DATA_CODE) = '" . $tmpKey . "'");

                        if($existSheetLog)
                            $value = $value . '_logvalue';
                        $rows[$k][$tmpKey] = $value;

                    } else
                        $rows[$k][$tmpKey] = $value;
                }                    
            }

            foreach ($getEmployeeKeyIds as $k => $row) {
                foreach ($getCalcTypeSidebarList2 as $key => $value) {
                    $lowerMetaDataCode = strtolower($value['META_DATA_CODE']);
                    $getEmployeeKeyIds[$k][$lowerMetaDataCode] = '0';
                }
            }

            $metaDataIdList = array();
            foreach ($getCalcTypeSidebarList2 as $k => $value) {
                $metaDataIdList[$k]['metaDataId'] = $value['META_DATA_ID'];
            }
            $employeeList = array();

            foreach ($rs as $k => $value) {
                foreach ($getCalcTypeSidebarList2 as $key => $val) {
                    $lowerMetaDataCode = strtolower($val['META_DATA_CODE']);
                    $getEmployeeKeyIds[$k][$lowerMetaDataCode] = '0';
                }
                $value = (array) $value;
                $employeeList[$k]['employeeKeyId'] = $value['EMPLOYEE_KEY_ID'];
                $employeeList[$k]['departmentId'] = $value['DEPARTMENT_ID'];
            }

            $param = array(
                'prlCalcId' => $getCalcId,
                'calcType' => $calcType,
                'employeelist' => $employeeList
            );

            // <editor-fold defaultstate="collapsed" desc="Засах үед сервис дээр бодолт хийхгүй">
            $sideBarValueList = array();
            $serviceResult = $this->ws->runResponse(GF_SERVICE_ADDRESS, "prlCalcExecGetProcess", $param);

            if ($serviceResult['status'] === 'success') {
                $serviceResult = $serviceResult['result']['employeelist'];
                foreach ($rs as $k => $row) {
                    foreach ($getCalcTypeDtl as $key => $value) {
                        $tmpMetaDataId = $value['META_DATA_ID'];
                        $lowerMetaDataCode = strtolower($value['META_DATA_CODE']);

                        if(in_array($tmpMetaDataId, $metaDataIdListNotIn)) {
                            if (isset($serviceResult[$k]['metadatalist'][$tmpMetaDataId])) {
                                    $rows[$k][$lowerMetaDataCode] = $serviceResult[$k]['metadatalist'][$tmpMetaDataId];
                            } else {
                                $rows[$k][$lowerMetaDataCode] = '0';
                            }
                        }
                    }
                }
            }
//                $returnDatas = array();
//                if (sizeof($sideBarValueList) > 0) {
//                    foreach ($rows as $k => $v) {
//                        $mergeArray = array_merge($rows[$k], $sideBarValueList[$k]);
//                        array_push($returnDatas, $mergeArray);
//                    }
//                }
            // </editor-fold>

            $returnDatas = $rows;

            $result["rows1"] = $returnDatas;
            $result["sidebarList1"] = $getCalcTypeSidebarList;
            $result["editSalaryBookId"] = $salaryBookIds;
            //1111
            } else {
            array_push($NoSalaryBookdepartments,$d);
            }
            /* давталт төгсгөл*/
            $end = microtime(true);
        }

        if(!empty($result['rows1']) && !empty($unCalcList)){
            $result['rows']= array_merge($result['rows1'],$unCalcList);
        }
        if(!empty($result['rows1']) && empty($unCalcList)){
            $result['rows'] = $result['rows1'];
        }
        if(empty($result['rows1']) && !empty($unCalcList)){
            $tmp = "";
            foreach($unCalcList as $uc):
                array_push($result['rows1'],$uc);
            endforeach;
            $result['rows'] = $result['rows1'];
        }
        if(empty($result['rows1']) && empty($unCalcList)){
            $result['rows'] = array();
        }
        if(!empty($result["sidebarList1"]) && !empty($result["sidebarList2"])){
            $result["sidebarList"] = array_merge($result["sidebarList1"],$result["sidebarList2"]);
        }
        if(!empty($result["sidebarList1"]) && empty($result["sidebarList2"])){
            $result["sidebarList"] = $result["sidebarList1"];
        }
        if(empty($result["sidebarList1"]) && !empty($result["sidebarList2"])){
            $result["sidebarList"] = $result["sidebarList2"];
        }
        if(empty($result["sidebarList1"]) && empty($result["sidebarList2"])){
            $result["sidebarList"] = array();
        }
        $result['editSalaryBookId'] = implode(",",$editSalaryBookId);
        unset($result['rows1']);
        unset($result['rows2']);
        unset($result["sidebarList1"]);
        unset($result["sidebarList2"]);
        return $result;
        }
    }

    public function retrievePrevEmpKeyIds($params,$d,$cc_emp_keys){
        if(!empty($params)){
        $calcId = $params['calcId'];
        $calcType = $params['calcTypeId'];
        $selectPrlCalc = "SELECT ID, START_DATE,CALC_ORDER,YEAR,MONTH
              FROM PRL_CALC
              WHERE 1=1 AND ID = $calcId
            ";
        $result = array();
        $getCalcStartDate = $this->db->GetRow($selectPrlCalc);

            if($getCalcStartDate){
                if($getCalcStartDate['CALC_ORDER'] == 2){
                    $id = $getCalcStartDate['ID'];
                    $year = $getCalcStartDate['YEAR'];
                    $month = $getCalcStartDate['MONTH'];
                    $selectPrev = "SELECT * FROM PRL_CALC WHERE YEAR = $year AND MONTH = $month AND ID != $id";
                    $getPrev = $this->db->GetRow($selectPrev);

                    if($getPrev){
                        $prevCalcId = $getPrev['ID'];
                        $depid = is_array($params['departmentId']) ? $params['departmentId'] : (strpos($params['departmentId'],",") ? explode(',',$params['departmentId']):array($params['departmentId']));

                        $selectPrevBook = "SELECT * FROM PRL_SALARY_BOOK WHERE CALC_ID = $prevCalcId AND DEPARTMENT_ID = $d";
                        $getPrevBook = $this->db->GetRow($selectPrevBook);

                        if($getPrevBook){
                            $selectOldSheet = "SELECT EMPLOYEE_KEY_ID FROM PRL_SALARY_SHEET WHERE SAL_BOOK_ID =".$getPrevBook['ID'];
                            $getOldSheet = $this->db->GetAll($selectOldSheet);           

                            if($getOldSheet){

                                $old_emp_keys = array();

                                foreach($getOldSheet as $os){
                                    array_push($old_emp_keys,$os['EMPLOYEE_KEY_ID']);
                                }
                                $need_emp_keys = array_diff($old_emp_keys,$cc_emp_keys); 

                                if(!empty($need_emp_keys)){
                                    $imp_keys = implode(',',$need_emp_keys);
                                    $selectEmployeeKeyIds = "SELECT
                                        HEK.EMPLOYEE_KEY_ID, OD.DEPARTMENT_ID,OD.DEPARTMENT_NAME, HE.EMPLOYEE_CODE, HE.LAST_NAME, HE.FIRST_NAME
                                        FROM ORG_DEPARTMENT OD
                                        INNER JOIN HRM_EMPLOYEE_KEY HEK ON OD.DEPARTMENT_ID = HEK.DEPARTMENT_ID
                                        INNER JOIN HRM_EMPLOYEE HE ON HEK.EMPLOYEE_ID = HE.EMPLOYEE_ID
                                        WHERE HEK.EMPLOYEE_KEY_ID IN (".Security::sanitize($imp_keys).") AND HEK.IS_ACTIVE = 1 AND HEK.WORK_END_DATE IS NULL
                                        ORDER BY NLSSORT(HE.FIRST_NAME,'NLS_SORT = generic_m'),
                                        NLSSORT(HE.LAST_NAME,'NLS_SORT = generic_m')";
                                  $getEmployeeKeyIds = $this->db->GetAll($selectEmployeeKeyIds);

                                  $selectCalcTypeDtl =   "SELECT
                                                          MD.META_DATA_ID, 
                                                          MD.META_DATA_CODE, 
                                                          MD.META_DATA_NAME
                                                          FROM PRL_CALC_TYPE PCT
                                                          INNER JOIN PRL_CALC_TYPE_DTL PCTD ON PCT.ID = PCTD.CALC_TYPE_ID
                                                          INNER JOIN META_DATA MD ON PCTD.META_DATA_ID = MD.META_DATA_ID
                                                          WHERE 1=1 AND PCTD.IS_SHOW=1 AND PCT.ID = $calcType
                                                          ORDER BY PCTD.ORDER_NUM";
                                  $getCalcTypeDtl = $this->db->GetAll($selectCalcTypeDtl);

                                  foreach ($getEmployeeKeyIds as $k => $row) {
                                      foreach ($getCalcTypeDtl as $key => $value) {
                                          $lowerMetaDataCode = strtolower($value['META_DATA_CODE']);
                                          $getEmployeeKeyIds[$k][$lowerMetaDataCode] = '0';
                                          $getEmployeeKeyIds[$k]['selectedDepId'] = $d;
                                      }
                                  }
                                  $metaDataIdList = array();
                                  foreach ($getCalcTypeDtl as $k => $value) {
                                      $metaDataIdList[$k]['metaDataId'] = $value['META_DATA_ID'];
                                  }
                                  $employeeList = array();
                                  foreach ($getEmployeeKeyIds as $k => $value) {
                                      $value = (array) $value;
                                      $employeeList[$k]['employeeKeyId'] = $value['EMPLOYEE_KEY_ID'];
                                      $employeeList[$k]['departmentId'] = $value['DEPARTMENT_ID'];
                                      $employeeList[$k]['metaDataIdList'] = $metaDataIdList;
                                  }
                                  if ($calcId) {
                                      $param = array(
                                          'prlCalcId' => $calcId,
                                          'calcType' => $calcType,
                                          'employeelist' => $employeeList
                                      );
                                      $param2 = array(
                                          'payrollCalculateId' => $calcId,
                                          'payrollCalculateTypeId' => $calcType,
                                      );
                                      $serviceResult = $this->ws->runResponse(GF_SERVICE_ADDRESS, "prlCalcExecGetProcess", $param);
                                      $serviceResult2 = $this->ws->runResponse(GF_SERVICE_ADDRESS, "getCalcValue", $param2);
                                      if ($serviceResult['status'] === 'success') {
                                          $serviceResult = $serviceResult['result']['employeelist'];
                                          foreach ($getEmployeeKeyIds as $k => $row) {
                                              foreach ($getCalcTypeDtl as $key => $value) {
                                                  $tmpMetaDataId = $value['META_DATA_ID'];
                                                  if (isset($serviceResult[$k]['metadatalist'][$tmpMetaDataId])) {
                                                      $lowerMetaDataCode = strtolower($value['META_DATA_CODE']);
                                                      $getEmployeeKeyIds[$k][$lowerMetaDataCode] = $serviceResult[$k]['metadatalist'][$tmpMetaDataId];
                                                  }
                                                  if (isset($serviceResult2['result'][$row['EMPLOYEE_KEY_ID']])) {
                                                      if (isset($serviceResult2['result'][$row['EMPLOYEE_KEY_ID']][$tmpMetaDataId])) {
                                                          $getEmployeeKeyIds[$k][$value['META_DATA_CODE']] = $serviceResult2['result'][$row['EMPLOYEE_KEY_ID']][$tmpMetaDataId];
                                                      }
                                                  }
                                              }
                                          }
                                      } 
                                  }

                                  $result['rows'] = $getEmployeeKeyIds;
                                  return $result;
                                } else
                                    return false;
                            }
                        } else {
                            return false;
                        }
                        return $result;
                    }
                } else {
                    return false;
                }
            }
        } else {
            return false;
        }
    }
    public function getSalarySheetDeepListModel() {
        ini_set('max_execution_time',300);
        $metaDataNameList = '';
        $metaDataNameList2 = '';
        parse_str(Input::post('params'), $params);
        $parent = (Input::postCheck('parent') ? Input::post('parent'):false);
        $calcType = $params['calcTypeId'];
        $getCalcId = $params['calcId'];
//            $departmentId = $params['departmentId'];
        $departmentIds = explode(',', $params['departmentId']);

        $resultTreeList = array();            
        $resultTreeStr = '';
        $existed_deps = array();
        foreach ($departmentIds as $row) {
            if(!in_array($row,$existed_deps)){
                array_push($existed_deps,$row);
                $this->getSalarySheetDeepListRecursiveModel($row, $resultTreeList, $resultTreeStr, $params);
            }
        }
        return $resultTreeList;
    }

    public function getSalarySheetDeepListRecursiveModel($departmentId, &$resultTreeList, $resultTreeStr, $params,$stored_deps=null) {
        $parent_department = $this->db->GetRow("SELECT DEPARTMENT_ID, DEPARTMENT_NAME FROM ORG_DEPARTMENT WHERE DEPARTMENT_ID = $departmentId");
        $child_departments = $this->db->GetAll("SELECT DEPARTMENT_ID, DEPARTMENT_NAME FROM ORG_DEPARTMENT WHERE PARENT_ID = $departmentId");
        $calcType = $params['calcTypeId'];
        $selectCalcTypeDtl =   "SELECT
                                    MD.META_DATA_ID, 
                                    MD.META_DATA_CODE, 
                                    MD.META_DATA_NAME
                                    FROM PRL_CALC_TYPE PCT
                                    INNER JOIN PRL_CALC_TYPE_DTL PCTD ON PCT.ID = PCTD.CALC_TYPE_ID
                                    INNER JOIN META_DATA MD ON PCTD.META_DATA_ID = MD.META_DATA_ID
                                    WHERE 1=1 AND PCTD.IS_SHOW=1 AND PCT.ID = $calcType
                                    ORDER BY PCTD.ORDER_NUM";
        $getCalcTypeDtl = $this->db->GetAll($selectCalcTypeDtl);
        $selectCalcTypeSidebarList = "SELECT MD.META_DATA_ID, MD.META_DATA_CODE, 
              MD.META_DATA_NAME, PCTD.LABEL_NAME, PCTD.IS_DISABLE, MFL.DATA_TYPE
              FROM PRL_CALC_TYPE_DTL PCTD
              INNER JOIN META_DATA MD ON PCTD.META_DATA_ID = MD.META_DATA_ID
              INNER JOIN META_FIELD_LINK MFL ON MD.META_DATA_ID = MFL.META_DATA_ID
              WHERE PCTD.CALC_TYPE_ID = $calcType
              AND PCTD.IS_SHOW = 1 
              AND PCTD.IS_SIDEBAR = 1";
        $getCalcTypeSidebarList = $this->db->GetAll($selectCalcTypeSidebarList);
        $p['calctypedtl'] = $getCalcTypeDtl;
        $p['getcalctypesidebarlist'] = $getCalcTypeSidebarList;

        $countEmployeeKeyIds = "SELECT
              COUNT(HEK.EMPLOYEE_KEY_ID) TOTAL_EMPLOYEES
              FROM ORG_DEPARTMENT OD
              INNER JOIN HRM_EMPLOYEE_KEY HEK ON OD.DEPARTMENT_ID = HEK.DEPARTMENT_ID
              INNER JOIN HRM_EMPLOYEE HE ON HEK.EMPLOYEE_ID = HE.EMPLOYEE_ID
              WHERE HEK.IS_ACTIVE = 1 AND HE.IS_ACTIVE = 1
              AND OD.DEPARTMENT_ID = $departmentId";            
        $getCountEmployees = $this->db->GetRow($countEmployeeKeyIds);

        if($getCountEmployees['TOTAL_EMPLOYEES'] > 0){
            $employees = $this->getDepartmentEmployees($departmentId, $params, $p);

            $resultTreeStr .= $parent_department['DEPARTMENT_NAME'] . ' - ';
            if(empty($child_departments)){
                $resultTreeStr = rtrim($resultTreeStr," - ");
            }

            $appendDepartmentArr = array(
                'appendDepRow' => ''
            );
            $appendDepartment = array_keys($employees['rows'][0]);
            foreach ($appendDepartment as $rowVal) {
                $appendDepartmentArr[$rowVal] = '';
                if($rowVal === 'EMPLOYEE_CODE')
                    $appendDepartmentArr[$rowVal] = $resultTreeStr;
            }
            array_push($resultTreeList, $appendDepartmentArr);
            foreach ($employees['rows'] as $rowVal) {
                array_push($resultTreeList, $rowVal);
            }
        }

        if(empty($stored_deps)){
            $stored_deps = array($departmentId);
        }else if(!empty($stored_deps)){
            array_push($stored_deps,$departmentId);
        }         
        if(count($child_departments) > 0) {

            foreach ($child_departments as $row) {
                if(!in_array($row['DEPARTMENT_ID'],$stored_deps)){
                    $this->getSalarySheetDeepListRecursiveModel($row['DEPARTMENT_ID'], $resultTreeList, $resultTreeStr, $params,$stored_deps);
                }
            }
        }
    }
    public function getDepartmentEmployees($depId,$params,$p){
        $calcType = $params['calcTypeId'];
        $getCalcId = $params['calcId'];
        $NoSalaryBookdepartments = array();  
        $editSalaryBookId = array();
        $d = $depId;
        $result = array();
        $metaDataNameList = '';
        $metaDataNameList2 = '';
         /* хэлтэсүүдээр ялгах давталт*/
        //Хадгалагдсан бодолт шүүх query 

        //хэлтэсийн ажилчидын key id 
        $selectEmployeeKeyIds = "SELECT
              HEK.EMPLOYEE_KEY_ID, OD.DEPARTMENT_ID, HE.EMPLOYEE_CODE, HE.LAST_NAME, HE.FIRST_NAME
              FROM ORG_DEPARTMENT OD
              INNER JOIN HRM_EMPLOYEE_KEY HEK ON OD.DEPARTMENT_ID = HEK.DEPARTMENT_ID
              INNER JOIN HRM_EMPLOYEE HE ON HEK.EMPLOYEE_ID = HE.EMPLOYEE_ID
              WHERE HEK.IS_ACTIVE = 1 AND HE.IS_ACTIVE = 1
              AND OD.DEPARTMENT_ID = $d
              ORDER BY NLSSORT(HE.FIRST_NAME,'NLS_SORT = generic_m')
        ";
        $getEmployeeKeyIds = $this->db->GetAll($selectEmployeeKeyIds);
        //хэлтэсийн ажилчидын key id 
        $getCalcTypeDtl = $p['calctypedtl'];
        $getCalcTypeSidebarList = $p['getcalctypesidebarlist'];
        //цалингийн бодолт байгаа эсэх
        $selectSalaryBook = "SELECT * 
        FROM PRL_SALARY_BOOK PSB
        WHERE PSB.DEPARTMENT_ID = $d
        AND PSB.CALC_ID = $getCalcId
        AND PSB.CALC_TYPE_ID = $calcType";
        $count = 0;
        foreach ($getCalcTypeDtl as $key) {
            $selectIfExist = "SELECT column_name
                              FROM   all_tab_cols
                              WHERE  table_name = 'PRL_SALARY_SHEET'
                              AND column_name = UPPER('" . $key['META_DATA_CODE'] . "')";
            $getIfExist = $this->db->GetRow($selectIfExist);
            if ($getIfExist) {
                $metaDataNameList == '' ? $metaDataNameList .= $key['META_DATA_CODE'] : $metaDataNameList = $metaDataNameList . ',' . $key['META_DATA_CODE'];
                $metaDataNameList2 == '' ? $metaDataNameList2 .= "'" . $key['META_DATA_CODE'] . "'" : $metaDataNameList2 = $metaDataNameList2 . ',' . "'" . $key['META_DATA_CODE'] . "'";
            } else {
              $metaDataIdListNotIn[$count]['metaDataId'] = $key['META_DATA_ID'];
              $count++;
            }
        }
        $getSalaryBook = $this->db->GetRow($selectSalaryBook);
        if ($getSalaryBook) {
            array_push($editSalaryBookId,$getSalaryBook['ID']);
            $salaryBookIds = implode(',', $editSalaryBookId);
            $salaryBookId = $d;
            $metaDataIdListNotIn = array();
            $count = 0;
             //хажуугийн үзүүлэлт харуулах талбарууд
            $selectCalcTypeSidebarList2 = "SELECT MD.META_DATA_ID, MD.META_DATA_CODE, 
              MD.META_DATA_NAME, PCTD.LABEL_NAME, PCTD.IS_DISABLE, MFL.DATA_TYPE
              FROM PRL_CALC_TYPE_DTL PCTD
              INNER JOIN META_DATA MD ON PCTD.META_DATA_ID = MD.META_DATA_ID
              INNER JOIN META_FIELD_LINK MFL ON MD.META_DATA_ID = MFL.META_DATA_ID
              WHERE PCTD.CALC_TYPE_ID = $calcType
              AND PCTD.IS_SHOW = 1 
              AND MD.META_DATA_CODE NOT IN($metaDataNameList2)";
            //AND PCTD.IS_SIDEBAR = 1
            $getCalcTypeSidebarList2 = $this->db->GetAll($selectCalcTypeSidebarList2);
            //хажуугийн үзүүлэлт харуулах талбарууд төгсгөл
            //цалингийн хүснэгтээс $metaDataNameList хувьсагчид ирсэн утгын дагуу хэрэгцээт талбарын мэдээллийг шүүн гаргах
            $selectList = "SELECT
                      PST.ID AS SALARY_SHEET_ID, PST.SAL_BOOK_ID, HEK.DEPARTMENT_ID, HEK.EMPLOYEE_KEY_ID, HE.EMPLOYEE_CODE, HE.LAST_NAME, HE.FIRST_NAME,
                      $metaDataNameList 
                        FROM PRL_SALARY_SHEET PST
                        LEFT JOIN HRM_EMPLOYEE_KEY HEK ON PST.EMPLOYEE_KEY_ID = HEK.EMPLOYEE_KEY_ID
                        LEFT JOIN HRM_EMPLOYEE HE ON HEK.EMPLOYEE_ID = HE.EMPLOYEE_ID
                        WHERE SAL_BOOK_ID IN ($salaryBookIds)
                        ORDER BY NLSSORT(HE.FIRST_NAME,'NLS_SORT = generic_m'),
                        NLSSORT(HE.LAST_NAME,'NLS_SORT = generic_m')
                        ";

            $rs = $this->db->GetAll($selectList);
            //цалингийн хүснэгтээс $metaDataNameList хувьсагчид ирсэн утгын дагуу хэрэгцээт талбарын мэдээллийг шүүн гаргах төгсгөл
            foreach ($getEmployeeKeyIds as $k => $row) {
                foreach ($getCalcTypeSidebarList2 as $key => $value) {
                    $lowerMetaDataCode = strtolower($value['META_DATA_CODE']);
                    $getEmployeeKeyIds[$k][$lowerMetaDataCode] = '0';
                }
            }

            $metaDataIdList = array();
            foreach ($getCalcTypeSidebarList2 as $k => $value) {
                $metaDataIdList[$k]['metaDataId'] = $value['META_DATA_ID'];
            }
            $employeeList = array();

            foreach ($getEmployeeKeyIds as $k => $value) {
                foreach ($getCalcTypeDtl as $key => $val) {
                    $lowerMetaDataCode = strtolower($val['META_DATA_CODE']);
                    $getEmployeeKeyIds[$k][$lowerMetaDataCode] = '0';
                }
                $value = (array) $value;
                $employeeList[$k]['employeeKeyId'] = $value['EMPLOYEE_KEY_ID'];
                $employeeList[$k]['departmentId'] = $value['DEPARTMENT_ID'];
            }
            $employeeList = array();
                $param = array(
                    'prlCalcId' => $getCalcId,
                    'calcType' => $calcType,
                    'employeelist' => $employeeList
                );
                $serviceResult = $this->ws->runResponse(GF_SERVICE_ADDRESS, "prlCalcExecGetProcess", $param);
                if ($serviceResult['status'] === 'success') {
                    $serviceResult = $serviceResult['result']['employeelist'];
                    foreach ($getEmployeeKeyIds as $k => $row) {
                        $val = (array) $row;
                        $employeeList[$k]['employeeKeyId'] = $val['EMPLOYEE_KEY_ID'];
                        $employeeList[$k]['departmentId'] = $val['DEPARTMENT_ID'];
                        $employeeList[$k]['metaDataIdList'] = $metaDataIdListNotIn;
                        foreach ($getCalcTypeDtl as $key => $value) {
                            $tmpMetaDataId = $value['META_DATA_ID'];
                            if (isset($serviceResult[$k]['metadatalist'][$tmpMetaDataId])) {
                                $lowerMetaDataCode = strtolower($value['META_DATA_CODE']);
                                $getEmployeeKeyIds[$k][$lowerMetaDataCode] = $serviceResult[$k]['metadatalist'][$tmpMetaDataId];
                            }
                        }
                    }
                }

            $result = array();
            $employeeList = array();
            /*
            foreach ($rs as $k => $row) {
                  foreach ($row as $key => $value) {
                      $tmpKey = $key;
                      if ($key !== 'DEPARTMENT_ID' && $key !== 'EMPLOYEE_KEY_ID' && $key !== 'EMPLOYEE_CODE' &&
                              $key !== 'LAST_NAME' && $key !== 'FIRST_NAME' && $key !== 'SALARY_SHEET_ID') {
                          $tmpKey = strtolower($key);
                      }
                      $rows[$k][$tmpKey] = $value;
                }
            }
             * */
            foreach ($rs as $k => $value) {
                $value = (array) $value;
                foreach ($value as $key => $v) {
                      $tmpKey = $key;
                      if ($key !== 'DEPARTMENT_ID' && $key !== 'EMPLOYEE_KEY_ID' && $key !== 'EMPLOYEE_CODE' &&
                              $key !== 'LAST_NAME' && $key !== 'FIRST_NAME' && $key !== 'SALARY_SHEET_ID') {
                          $tmpKey = strtolower($key);
                      }
                      $rows[$k][$tmpKey] = $v;
                }

                $employeeList[$k]['employeeKeyId'] = $value['EMPLOYEE_KEY_ID'];
                $employeeList[$k]['departmentId'] = $value['DEPARTMENT_ID'];
                $employeeList[$k]['metaDataIdList'] = $metaDataIdList;
            }
            $param = array(
                'prlCalcId' => $getCalcId,
                'calcType' => $calcType,
                'employeelist' => $employeeList
            );
            $sideBarValueList = array();
            $serviceResult = $this->ws->runResponse(GF_SERVICE_ADDRESS, "prlCalcExecGetProcess", $param);

            if ($serviceResult['status'] === 'success') {
                $serviceResult = $serviceResult['result']['employeelist'];
                foreach ($rs as $k => $row) {
                    foreach ($getCalcTypeSidebarList2 as $key => $value) {
                        $tmpMetaDataId = $value['META_DATA_ID'];
                        $lowerMetaDataCode = strtolower($value['META_DATA_CODE']);
                        if (isset($serviceResult[$k]['metadatalist'][$tmpMetaDataId])) {
                            $sideBarValueList[$k][$lowerMetaDataCode] = $serviceResult[$k]['metadatalist'][$tmpMetaDataId];
                        } else {
                            $sideBarValueList[$k][$lowerMetaDataCode] = '0';
                        }
                    }
                }
            }
            $returnDatas = array();
            if (sizeof($sideBarValueList) > 0) {
                foreach ($rows as $k => $v) {
//                      if(isset($rows[$k]) && isset($sideBarValueList[$k])){
                    $mergeArray = array_merge($rows[$k], $sideBarValueList[$k]);
                    array_push($returnDatas, $mergeArray);
//                      }
                }
            } else {
                $returnDatas = $rows;
            }

            $result["rows"] = $returnDatas;
            $result["sidebarList"] = $getCalcTypeSidebarList;
            $result["editSalaryBookId"] = implode(",",$editSalaryBookId);
            //1111
            } else {
                array_push($NoSalaryBookdepartments,$d);
            }
            //цалингийн бодолт байгаа эсэх төгсгөл
            //цалингийн бодолтгүй хэлтэс дээр хийгдэх үйлдлүүд
             if(!empty($NoSalaryBookdepartments)){
                $NoSalaryBookdepartments = implode(',',$NoSalaryBookdepartments);
                $selectEmployeeKeyIds = "SELECT
                      HEK.EMPLOYEE_KEY_ID, OD.DEPARTMENT_ID, HE.EMPLOYEE_CODE, HE.LAST_NAME, HE.FIRST_NAME
                      FROM ORG_DEPARTMENT OD
                      INNER JOIN HRM_EMPLOYEE_KEY HEK ON OD.DEPARTMENT_ID = HEK.DEPARTMENT_ID
                      INNER JOIN HRM_EMPLOYEE HE ON HEK.EMPLOYEE_ID = HE.EMPLOYEE_ID
                      WHERE HEK.IS_ACTIVE = 1 AND HE.IS_ACTIVE = 1
                      AND OD.DEPARTMENT_ID IN ($NoSalaryBookdepartments)
                      ORDER BY NLSSORT(HE.FIRST_NAME,'NLS_SORT = generic_m'),
                      NLSSORT(HE.LAST_NAME,'NLS_SORT = generic_m')
                ";
                $getEmployeeKeyIds = $this->db->GetAll($selectEmployeeKeyIds);
                   $metaDataIdList = array();
                    foreach ($getCalcTypeDtl as $k => $value) {
                        $metaDataIdList[$k]['metaDataId'] = $value['META_DATA_ID'];
                    }

                    $employeeList = array();

                    foreach ($getEmployeeKeyIds as $k => $value) {
                        $value = (array) $value;
                        $employeeList[$k]['employeeKeyId'] = $value['EMPLOYEE_KEY_ID'];
                        $employeeList[$k]['departmentId'] = $value['DEPARTMENT_ID'];

                        foreach ($getCalcTypeDtl as $key => $value) {
                            $lowerMetaDataCode = strtolower($value['META_DATA_CODE']);
                            $getEmployeeKeyIds[$k][$lowerMetaDataCode] = '0';
                        }
                    }

                    if ($getCalcId) {
                        $param = array(
                            'prlCalcId' => $getCalcId,
                            'calcType' => $calcType,
                            'employeelist' => $employeeList
                        );
                        $param2 = array(
                            'payrollCalculateId' => $getCalcId,
                            'payrollCalculateTypeId' => $calcType,
                        );
                        $serviceResult = $this->ws->runResponse(GF_SERVICE_ADDRESS, "prlCalcExecGetProcess", $param);
                        //print_r($serviceResult);
                        $serviceResult2 = $this->ws->runResponse(GF_SERVICE_ADDRESS, "getCalcValue", $param2);
                        if ($serviceResult['status'] === 'success') {
                            $serviceResult = $serviceResult['result']['employeelist'];
                            foreach ($getEmployeeKeyIds as $k => $row) {
                                $val = (array) $row;
                                $employeeList[$k]['employeeKeyId'] = $val['EMPLOYEE_KEY_ID'];
                                $employeeList[$k]['departmentId'] = $val['DEPARTMENT_ID'];

                                foreach ($getCalcTypeDtl as $key => $value) {
                                    $lowerMetaDataCode = strtolower($value['META_DATA_CODE']);
                                    $getEmployeeKeyIds[$k][$lowerMetaDataCode] = '0';
                                }
                                foreach ($getCalcTypeDtl as $key => $value) {
                                    $tmpMetaDataId = $value['META_DATA_ID'];
                                    if (isset($serviceResult[$k]['metadatalist'][$tmpMetaDataId])) {
                                        $lowerMetaDataCode = strtolower($value['META_DATA_CODE']);
                                        $getEmployeeKeyIds[$k][$lowerMetaDataCode] = $serviceResult[$k]['metadatalist'][$tmpMetaDataId];
                                    }
                                    if (isset($serviceResult2['result'][$row['EMPLOYEE_KEY_ID']])) {
                                        if (isset($serviceResult2['result'][$row['EMPLOYEE_KEY_ID']][$tmpMetaDataId])) {
                                            $getEmployeeKeyIds[$k][$value['META_DATA_CODE']] = $serviceResult2['result'][$row['EMPLOYEE_KEY_ID']][$tmpMetaDataId];
                                        }
                                    }
                                }
                            }
                            $result['rows'] = $getEmployeeKeyIds;
                            $result["sidebarList"] = $getCalcTypeSidebarList;
                        } else {
                            $result['rows'] = $getEmployeeKeyIds;
                            $result["sidebarList"] = $getCalcTypeSidebarList;
                        }
                    }
                }
                //цалингийн бодолтгүй хэлтэс дээр хийгдэх үйлдлүүд төгсгөл
            return $result;
    }
    public function confirmSelectedEmployeeRowsModel(){
        $metaDataNameList = '';
        $metaDataNameList2 = '';
        parse_str(Input::post('params'), $params);
//            $departmentId = $params['departmentId'];
        //echo $departmentIds;
        $calcType = $params['calcTypeId'];
        $getCalcId = $params['calcId'];
        $result["rows"] = "";
        $rows = array();
        $selectedDepartmentId = Input::post('sdId');

        $SavedEmployeeKeyId = Input::post('SAVED_EMPLOYEE_KEY_ID');
        $selectEmployeeKeyIds = "SELECT
          HEK.EMPLOYEE_KEY_ID, OD.DEPARTMENT_ID,OD.DEPARTMENT_NAME, HE.EMPLOYEE_CODE, HE.LAST_NAME, HE.FIRST_NAME
          FROM ORG_DEPARTMENT OD
          INNER JOIN HRM_EMPLOYEE_KEY HEK ON OD.DEPARTMENT_ID = HEK.DEPARTMENT_ID
          INNER JOIN HRM_EMPLOYEE HE ON HEK.EMPLOYEE_ID = HE.EMPLOYEE_ID
          WHERE HEK.IS_ACTIVE = 1 AND HE.IS_ACTIVE = 1 AND HEK.WORK_END_DATE IS NOT NULL
          AND EMPLOYEE_KEY_ID IN (".Security::sanitize($SavedEmployeeKeyId).") OR
          HEK.IS_ACTIVE = 1 AND HE.IS_ACTIVE = 1 AND HEK.WORK_END_DATE IS NULL
          AND EMPLOYEE_KEY_ID IN (".Security::sanitize($SavedEmployeeKeyId).")
          ORDER BY NLSSORT(HE.FIRST_NAME,'NLS_SORT = generic_m'),
          NLSSORT(HE.LAST_NAME,'NLS_SORT = generic_m')
        ";
        $getEmployeeKeyIds = $this->db->GetAll($selectEmployeeKeyIds);
        $selectCalcTypeDtl =   "SELECT
                                MD.META_DATA_ID, 
                                MD.META_DATA_CODE, 
                                MD.META_DATA_NAME
                                FROM PRL_CALC_TYPE PCT
                                INNER JOIN PRL_CALC_TYPE_DTL PCTD ON PCT.ID = PCTD.CALC_TYPE_ID
                                INNER JOIN META_DATA MD ON PCTD.META_DATA_ID = MD.META_DATA_ID
                                WHERE 1=1 AND PCTD.IS_SHOW=1 AND PCT.ID = $calcType
                                ORDER BY PCTD.ORDER_NUM";
        $getCalcTypeDtl = $this->db->GetAll($selectCalcTypeDtl);
        foreach ($getEmployeeKeyIds as $k => $row) {
            foreach ($getCalcTypeDtl as $key => $value) {
                $lowerMetaDataCode = strtolower($value['META_DATA_CODE']);
                $getEmployeeKeyIds[$k][$lowerMetaDataCode] = '0';
                $getEmployeeKeyIds[$k]['selectedDepId'] = $selectedDepartmentId;
            }
        }
        $metaDataIdList = array();
        foreach ($getCalcTypeDtl as $k => $value) {
            $metaDataIdList[$k]['metaDataId'] = $value['META_DATA_ID'];
        }
        $employeeList = array();
        foreach ($getEmployeeKeyIds as $k => $value) {
            $value = (array) $value;
            $employeeList[$k]['employeeKeyId'] = $value['EMPLOYEE_KEY_ID'];
            $employeeList[$k]['departmentId'] = $value['DEPARTMENT_ID'];
            $employeeList[$k]['metaDataIdList'] = $metaDataIdList;
        }
        if ($getCalcId) {
            $param = array(
                'prlCalcId' => $getCalcId,
                'calcType' => $calcType,
                'employeelist' => $employeeList
            );
            $param2 = array(
                'payrollCalculateId' => $getCalcId,
                'payrollCalculateTypeId' => $calcType,
            );
            $serviceResult = $this->ws->runResponse(GF_SERVICE_ADDRESS, "prlCalcExecGetProcess", $param);
            //print_r($serviceResult);
            $serviceResult2 = $this->ws->runResponse(GF_SERVICE_ADDRESS, "getCalcValue", $param2);
            if ($serviceResult['status'] === 'success') {
                $serviceResult = $serviceResult['result']['employeelist'];
                foreach ($getEmployeeKeyIds as $k => $row) {
                    foreach ($getCalcTypeDtl as $key => $value) {
                        $tmpMetaDataId = $value['META_DATA_ID'];
                        if (isset($serviceResult[$k]['metadatalist'][$tmpMetaDataId])) {
                            $lowerMetaDataCode = strtolower($value['META_DATA_CODE']);
                            $getEmployeeKeyIds[$k][$lowerMetaDataCode] = $serviceResult[$k]['metadatalist'][$tmpMetaDataId];
                        }
                        if (isset($serviceResult2['result'][$row['EMPLOYEE_KEY_ID']])) {
                            if (isset($serviceResult2['result'][$row['EMPLOYEE_KEY_ID']][$tmpMetaDataId])) {
                                $getEmployeeKeyIds[$k][$value['META_DATA_CODE']] = $serviceResult2['result'][$row['EMPLOYEE_KEY_ID']][$tmpMetaDataId];
                            }
                        }
                    }
                }
            } 
        }

        $result['rows'] = $getEmployeeKeyIds;
        return $result;
    }
    public function getCalculatedDeepSalarySheetListModel(){
        $result = array();
        $rows = array();
        $returnDatas = array();
        $service_message = "";
        $tmp = array();
        $data = array();
        if (Input::postCheck('params')) {
            parse_str(Input::post('params'), $params);
            $datas = $_POST['datas'];
            if ($params['calcTypeId'] !== null) {
                $calcType = $params['calcTypeId'];
            }
            $calcId = $params['calcId'];
            $employeeList = array();
            $tempEmployeeList = array();
            $currentDatas = $datas;
            //echo count($datas);
            foreach ($datas as $key =>$value) {
                $get_dep = $this->db->GetRow("SELECT DEPARTMENT_NAME FROM ORG_DEPARTMENT WHERE DEPARTMENT_ID = $key");

                foreach($value as $k=>$v ){
                    //$tempEmployeeList[$key][$k]['DEPARTMENT_NAME']  = $get_dep['DEPARTMENT_NAME'];                                                                                                                        
                    $tempEmployeeList[$key][$k]['EMPLOYEE_KEY_ID'] = $v['EMPLOYEE_KEY_ID'];
                    $tempEmployeeList[$key][$k]['DEPARTMENT_ID'] = $key;
                    $tempEmployeeList[$key][$k]['EMPLOYEE_CODE'] = $v['EMPLOYEE_CODE'];
                    $tempEmployeeList[$key][$k]['LAST_NAME'] = $v['LAST_NAME'];
                    $tempEmployeeList[$key][$k]['FIRST_NAME'] = $v['FIRST_NAME'];

                    $metaDataList = array();
                    $employeeList[$key][$k]['employeeKeyId'] = $v['EMPLOYEE_KEY_ID'];
                    $employeeList[$key][$k]['departmentId'] = $v['DEPARTMENT_ID'];

                    unset($datas[$key][$k]['EMPLOYEE_KEY_ID']);
                    unset($datas[$key][$k]['EMPLOYEE_CODE']);
                    unset($datas[$key][$k]['DEPARTMENT_ID']);
                    unset($datas[$key][$k]['LAST_NAME']);
                    unset($datas[$key][$k]['FIRST_NAME']);
                    $metaDataList = $datas[$key][$k];
                    $employeeList[$key][$k]['metadatalist'] = $metaDataList;
                }
                $param = array(
                    'prlCalcId' => $calcId,
                    'calcType' => $calcType,
                    'employeelist' => $employeeList[$key]
                );
                $serviceResult = $this->ws->runResponse(GF_SERVICE_ADDRESS, "prlCalcExpression", $param);

                 if ($serviceResult['status'] === 'success') {
                    foreach ($tempEmployeeList[$key] as $sk => $sv) {    
                        $employee = $tempEmployeeList[$key][$sk];
                        $metaDataList = $serviceResult['result']['employeelist'][$sk]['metadatalist'];
                        //print_r($employee);
                        //print_r($metaDataList);
                        $mergeArray = array_merge($employee, $metaDataList);
                        //print_r($mergeArray);
                        $rows[$key][] = $mergeArray;
                    }
                     $tmp['depName'] = $get_dep['DEPARTMENT_NAME'];
                     $tmp['depId'] = $key;
                     $tmp['employees'] = $rows[$key];
                     $data[] = $tmp;
                     unset($tmp);
                 }else{
                     $tmp['depName'] = $get_dep['DEPARTMENT_NAME'];
                     $tmp['depId'] = $key;
                     $tmp['employees'] =  $currentDatas;
                     $data[] = $tmp;
                     unset($tmp);
                     $service_message = $serviceResult;
                 }
            }
             /*   


                if ($serviceResult['status'] === 'success') {
                $i = 1;
                foreach ($tempEmployeeList as $k => $value) {
                    $employee = $tempEmployeeList[$k];
                    $metaDataList = $serviceResult['result']['employeelist'][$k]['metaDataList'];
                    $mergeArray = array_merge($employee, $metaDataList);
                    array_push($returnDatas, $mergeArray);
                    $i++;
                }
                array_push($rows,$returnDatas);
            } else {
                array_push($rows,$currentDatas);
                 $result["serviceMessage"] = $service_message;
            }
              * 
              */
        }
        return $data;
    }
    public function getCalculatedSalarySheetListModel() {
        if (Input::postCheck('params')) {
            parse_str(Input::post('params'), $params);
            $datas = $_POST['datas'];
            if ($params['calcTypeId'] !== null) {
                $calcType = $params['calcTypeId'];
            }
            $calcId = $params['calcId'];

            $employeeList = array();
            $tempEmployeeList = array();
            $currentDatas = $datas;

            foreach ($datas as $k => $value) {
                $tempEmployeeList[$k]['EMPLOYEE_KEY_ID'] = $value['EMPLOYEE_KEY_ID'];
                $tempEmployeeList[$k]['DEPARTMENT_ID'] = $value['DEPARTMENT_ID'];
                $tempEmployeeList[$k]['EMPLOYEE_CODE'] = $value['EMPLOYEE_CODE'];
                $tempEmployeeList[$k]['LAST_NAME'] = $value['LAST_NAME'];
                $tempEmployeeList[$k]['FIRST_NAME'] = $value['FIRST_NAME'];
                if(array_key_exists('selectedDepId', $value))
                    $tempEmployeeList[$k]['selectedDepId'] = $value['selectedDepId'];

                $metaDataList = array();
                $employeeList[$k]['employeeKeyId'] = $value['EMPLOYEE_KEY_ID'];
                $employeeList[$k]['departmentId'] = $value['DEPARTMENT_ID'];

                unset($datas[$k]['EMPLOYEE_KEY_ID']);
                unset($datas[$k]['EMPLOYEE_CODE']);
                unset($datas[$k]['DEPARTMENT_ID']);
                unset($datas[$k]['LAST_NAME']);
                unset($datas[$k]['FIRST_NAME']);
                if(array_key_exists('selectedDepId', $datas[$k]))
                    unset($datas[$k]['selectedDepId']);

                $metaDataList = $datas[$k];
                // Log той эсэхээ мэдэж байсан string remove хийж байна.
                $metaDataList = array_map(function($val){ return str_replace(array('_logvalue', '-.'), array('', '-0.'), $val); }, $metaDataList);             

                $employeeList[$k]['metadatalist'] = $metaDataList;
            }

            $param = array(
                'prlCalcId' => $calcId,
                'calcType' => $calcType,
                'employeelist' => $employeeList
            );
        }
        $serviceResult = $this->ws->runResponse(GF_SERVICE_ADDRESS, "prlCalcExpression", $param);

        $returnDatas = array();
        if ($serviceResult['status'] === 'success') {
            foreach ($tempEmployeeList as $k => $value) {
                $employee = $tempEmployeeList[$k];
                $metaDataList = $serviceResult['result']['employeelist'][$k]['metadatalist'];
                $mergeArray = array_merge($employee, $metaDataList);
                array_push($returnDatas, $mergeArray);
            }
            $result["rows"] = $returnDatas;
        } else {
            $result["rows"] = $currentDatas;
            $result["serviceMessage"] = $serviceResult;
        }

        return $result;
    }
    public function saveDeepSalarySheetListModel() {
        $result = array(
            'type' => 'error',
            'title' => 'Анхааруулга',
            'text' => 'Амжилтгүй боллоо.'
        );

        if (Input::postCheck('params') && Input::postCheck('datas')) {
            parse_str(Input::post('params'), $params);
            $datas = $_POST['datas'];
            $calcType = $params['calcTypeId'];
            $new_saved = array();
            $i = 0;
            foreach($datas as $d){
                $i++;

                $data = array(
                    'datas' => $d['datagrid_rows'], 
                    'params' => $params,
                    'type' => $d['type'],
                    'dep' => $d['dep'],
                    'editsalarybookid' => $d['editsalarybookid']
                );
                $result = $this->saveSalarySheetListModel($data);
            }
            $selectCalcTypeDtl =   "SELECT
                                    MD.META_DATA_ID, 
                                    MD.META_DATA_CODE, 
                                    MD.META_DATA_NAME
                                    FROM PRL_CALC_TYPE PCT
                                    INNER JOIN PRL_CALC_TYPE_DTL PCTD ON PCT.ID = PCTD.CALC_TYPE_ID
                                    INNER JOIN META_DATA MD ON PCTD.META_DATA_ID = MD.META_DATA_ID
                                    WHERE 1=1 AND PCTD.IS_SHOW=1 AND PCT.ID = $calcType
                                    ORDER BY PCTD.ORDER_NUM";
            $getCalcTypeDtl = $this->db->GetAll($selectCalcTypeDtl);
            $selectCalcTypeSidebarList = "SELECT MD.META_DATA_ID, MD.META_DATA_CODE, 
              MD.META_DATA_NAME, PCTD.LABEL_NAME, PCTD.IS_DISABLE, MFL.DATA_TYPE
              FROM PRL_CALC_TYPE_DTL PCTD
              INNER JOIN META_DATA MD ON PCTD.META_DATA_ID = MD.META_DATA_ID
              INNER JOIN META_FIELD_LINK MFL ON MD.META_DATA_ID = MFL.META_DATA_ID
              WHERE PCTD.CALC_TYPE_ID = $calcType
              AND PCTD.IS_SHOW = 1 
              AND PCTD.IS_SIDEBAR = 1";
            $getCalcTypeSidebarList = $this->db->GetAll($selectCalcTypeSidebarList);
            $p['calctypedtl'] = $getCalcTypeDtl;
            $p['getcalctypesidebarlist'] = $getCalcTypeSidebarList;
            foreach($datas as $d){
                 if($d['type']=='save'){
                    $new_saved[$d['dep']] = $this->getDepartmentEmployees($d['dep'],$params,$p);
                }
            }
        }
        $result['updates'] = $new_saved;
        return $result;
    }
    public function saveSalarySheetListModel($post=null) {   
        //print_r($_POST); die();

       /* LOG txt -оос дата сэргээх код
        * 
       $logString = '{"params":"editSalaryBookId=&departmentId%5B%5D=15130911&calcTypeId=1464472890582&calcTypeCode=0303&calcTypeName=%D0%A1%D2%AF%D2%AF%D0%BB+%D1%86%D0%B0%D0%BB%D0%B8%D0%BD+-+%D0%A7%D0%A5%D0%9E%D0%A3%D0%9D%D0%91&calcId=1462424313664&calcCode=2016092&calcName=2016%2F09+%D1%81%D0%B0%D1%80%D1%8B%D0%BD+%D1%81%D2%AF%D2%AF%D0%BB+%D1%86%D0%B0%D0%BB%D0%B8%D0%BD&prlCalcStartDate=2016-09-01+00%3A00%3A00","datas":[{"EMPLOYEE_KEY_ID":"791100576","DEPARTMENT_ID":"15130911","EMPLOYEE_CODE":"0576","LAST_NAME":"\u0411\u0430\u0437\u0430\u0440\u0440\u0430\u0433\u0447\u0430\u0430","FIRST_NAME":"\u0411\u0430\u0430\u0441\u0430\u043d","department_name":"\u041c\u044d\u0434\u044d\u044d\u043b\u044d\u043b \u0442\u0435\u0445\u043d\u043e\u043b\u043e\u0433\u0438\u0439\u043d \u0430\u043b\u0431\u0430","f181":"0","f309":"0","f105":"0","f106":"0.000000","f335":"0","f100":"765494","f101":"22.00","f103":"176.000000","f102":"4","f104":"32.000000","f279":"0","f141":"0","f108":"139180.727272","prworkedyear":"6.8","f314":"5.000000","f269":"6959.036364","f336":"1.50","f142":"0.000000","f342":"600.000000","f280":"0.000000","f266":"0","f267":"0.000000","f111":"18000.000000","f231":"0","f232":"0.000000","f277":"0","f187":"18.000000","f188":"1136293.000000","f157":"0.000000","f268":"0.000000","f290":"0","f332":"0","f281":"0.000000","f282":"0","f167":"0","f120":"0","f171":"0","f289":"1161252.036364","f308":"1300432.763636","insuredtypename":"1","insuredtypecode":"1.000000","f246":"1300432.763636","f247":"26008.655273","f248":"91030.293455","f249":"10403.462109","f250":"2600.865527","f251":"0","f244":"130043.276364","f159":"130043.276364","f295":"1800.000000","f288":"0.000000","f161":"110218.948727","f323":"0","f271":"0.000000","f283":"0","f137":"0","f285":"927397.330000","f330":"0","f239":"0","f304":"0","f162":"1167659.555091","f178":"132773","f130":"132773","f118":"0","f252":"1300432.763636","f253":"26008.655273","f254":"91030.293455","f255":"10403.462109","f256":"2600.865527","f257":"26008.655273","f245":"156051.931637","f258":"286095.208001","f339":"2160.000000","f341":"153891.931636","f346":"0","medicalinsurancenumber":"0","socialinsurancenumber":"0","accountnumber":"451023702"},{"EMPLOYEE_KEY_ID":"791100566","DEPARTMENT_ID":"15130911","EMPLOYEE_CODE":"0566","LAST_NAME":"\u041f\u0430\u0433\u0432\u0430","FIRST_NAME":"\u0411\u0430\u0434\u0430\u043c\u0434\u043e\u0440\u0436","department_name":"\u041c\u044d\u0434\u044d\u044d\u043b\u044d\u043b \u0442\u0435\u0445\u043d\u043e\u043b\u043e\u0433\u0438\u0439\u043d \u0430\u043b\u0431\u0430","f181":"0","f309":"0","f105":"0","f106":"0.000000","f335":"0","f100":"1052289","f101":"22.00","f103":"176.000000","f102":"0","f104":"0.000000","f279":"0","f141":"0","f108":"0.000000","prworkedyear":"30.4","f314":"0.000000","f269":"0.000000","f336":"1.50","f142":"0.000000","f342":"600.000000","f280":"0.000000","f266":"0","f267":"0.000000","f111":"0.000000","f231":"0","f232":"0.000000","f277":"0","f187":"29.000000","f188":"2550971.000000","f157":"0.000000","f268":"0.000000","f290":"0","f332":"0","f281":"0.000000","f282":"0","f167":"0","f120":"0","f171":"0","f289":"2550971.000000","f308":"2550971.000000","insuredtypename":"1","insuredtypecode":"1.000000","f246":"2550971.000000","f247":"51019.420000","f248":"178567.970000","f249":"20407.768000","f250":"5101.942000","f251":"0","f244":"255097.100000","f159":"192000.000000","f295":"0.000000","f288":"0.000000","f161":"228897.100000","f323":"0","f271":"0.000000","f283":"0","f137":"0","f285":"2130073.900000","f330":"0","f239":"0","f304":"0","f162":"2550971.000000","f178":"0","f130":"0","f118":"0","f252":"2550971.000000","f253":"51019.420000","f254":"178567.970000","f255":"20407.768000","f256":"5101.942000","f257":"51019.420000","f245":"306116.520000","f258":"498116.520000","f339":"0.000000","f341":"306116.520000","f346":"0","medicalinsurancenumber":"0","socialinsurancenumber":"0","accountnumber":"451021355"},{"EMPLOYEE_KEY_ID":"791100585","DEPARTMENT_ID":"15130911","EMPLOYEE_CODE":"0585","LAST_NAME":"\u0426\u043e\u043e\u0434\u043e\u043b","FIRST_NAME":"\u0411\u0430\u0442\u0447\u0438\u043c\u044d\u0433","department_name":"\u041c\u044d\u0434\u044d\u044d\u043b\u044d\u043b \u0442\u0435\u0445\u043d\u043e\u043b\u043e\u0433\u0438\u0439\u043d \u0430\u043b\u0431\u0430","f181":"0","f309":"0","f105":"0","f106":"0.000000","f335":"0","f100":"765494","f101":"22.00","f103":"176.000000","f102":"22","f104":"176.000000","f279":"0","f141":"0","f108":"765493.999996","prworkedyear":"24.6","f314":"20.000000","f269":"153098.799999","f336":"1.50","f142":"0.000000","f342":"600.000000","f280":"0.000000","f266":"0","f267":"0.000000","f111":"99000.000000","f231":"0","f232":"0.000000","f277":"0","f187":"0.000000","f188":"0.000000","f157":"382746.999998","f268":"0.000000","f290":"0","f332":"0","f281":"0.000000","f282":"0","f167":"0","f120":"0","f171":"0","f289":"252098.799999","f308":"1017592.799995","insuredtypename":"1","insuredtypecode":"1.000000","f246":"1017592.799995","f247":"20351.856000","f248":"71231.496000","f249":"8140.742400","f250":"2035.185600","f251":"0","f244":"101759.280000","f159":"101759.280000","f295":"9900.000000","f288":"0.000000","f161":"85573.352000","f323":"0","f271":"0.000000","f283":"0","f137":"0","f285":"0.000000","f330":"0","f239":"0","f304":"0","f162":"570079.631998","f178":"447513","f130":"447513","f118":"0","f252":"1017592.799995","f253":"20351.856000","f254":"71231.496000","f255":"8140.742400","f256":"2035.185600","f257":"20351.856000","f245":"122111.136000","f258":"223870.416000","f339":"11880.000000","f341":"110231.135999","f346":"0","medicalinsurancenumber":"0","socialinsurancenumber":"0","accountnumber":"451027828"},{"EMPLOYEE_KEY_ID":"791100584","DEPARTMENT_ID":"15130911","EMPLOYEE_CODE":"0584","LAST_NAME":"\u0411\u043e\u0440\u0445\u04af\u04af","FIRST_NAME":"\u0411\u0430\u0442-\u042d\u0440\u0434\u044d\u043d\u044d","department_name":"\u041c\u044d\u0434\u044d\u044d\u043b\u044d\u043b \u0442\u0435\u0445\u043d\u043e\u043b\u043e\u0433\u0438\u0439\u043d \u0430\u043b\u0431\u0430","f181":"0","f309":"0","f105":"0","f106":"0.000000","f335":"0","f100":"765494","f101":"22.00","f103":"176.000000","f102":"22","f104":"176.000000","f279":"56","f141":"0","f108":"765493.999996","prworkedyear":"21.7","f314":"20.000000","f269":"153098.799999","f336":"1.50","f142":"0.000000","f342":"600.000000","f280":"33600.000000","f266":"0","f267":"0.000000","f111":"99000.000000","f231":"0","f232":"0.000000","f277":"0","f187":"0.000000","f188":"0.000000","f157":"382746.999998","f268":"0.000000","f290":"0","f332":"0","f281":"0.000000","f282":"0","f167":"0","f120":"0","f171":"0","f289":"285698.799999","f308":"1051192.799995","insuredtypename":"1","insuredtypecode":"1.000000","f246":"1051192.799995","f247":"21023.856000","f248":"73583.496000","f249":"8409.542400","f250":"2102.385600","f251":"0","f244":"105119.280000","f159":"105119.280000","f295":"9900.000000","f288":"0.000000","f161":"88597.352000","f323":"0","f271":"0.000000","f283":"0","f137":"0","f285":"0.000000","f330":"-10000","f239":"0","f304":"0","f162":"576463.631998","f178":"474729","f130":"474729","f118":"0","f252":"1051192.799995","f253":"21023.856000","f254":"73583.496000","f255":"8409.542400","f256":"2102.385600","f257":"21023.856000","f245":"126143.136000","f258":"231262.416000","f339":"11880.000000","f341":"114263.135999","f346":"0","medicalinsurancenumber":"0","socialinsurancenumber":"0","accountnumber":"451021350"},{"EMPLOYEE_KEY_ID":"791100578","DEPARTMENT_ID":"15130911","EMPLOYEE_CODE":"0578","LAST_NAME":"\u0426\u043e\u0433\u0442\u043d\u0430\u0440\u0430\u043d","FIRST_NAME":"\u0411\u0430\u044f\u0440\u043c\u0430\u0430","department_name":"\u041c\u044d\u0434\u044d\u044d\u043b\u044d\u043b \u0442\u0435\u0445\u043d\u043e\u043b\u043e\u0433\u0438\u0439\u043d \u0430\u043b\u0431\u0430","f181":"0","f309":"0","f105":"0","f106":"0.000000","f335":"0","f100":"765494","f101":"22.00","f103":"176.000000","f102":"7","f104":"56.000000","f279":"0","f141":"0","f108":"243566.272726","prworkedyear":"3.9","f314":"0.000000","f269":"0.000000","f336":"1.50","f142":"0.000000","f342":"600.000000","f280":"0.000000","f266":"0","f267":"0.000000","f111":"31500.000000","f231":"0","f232":"0.000000","f277":"0","f187":"15.000000","f188":"925474.000000","f157":"0.000000","f268":"0.000000","f290":"0","f332":"0","f281":"0.000000","f282":"0","f167":"0","f120":"0","f171":"0","f289":"956974.000000","f308":"1200540.272726","insuredtypename":"1","insuredtypecode":"1.000000","f246":"1200540.272726","f247":"24010.805455","f248":"84037.819091","f249":"9604.322182","f250":"2401.080545","f251":"0","f244":"120054.027273","f159":"120054.027273","f295":"3150.000000","f288":"0.000000","f161":"101363.624545","f323":"0","f271":"0.000000","f283":"0","f137":"0","f285":"756633.940000","f330":"0","f239":"0","f304":"0","f162":"978051.591818","f178":"222489","f130":"222489","f118":"0","f252":"1200540.272726","f253":"24010.805455","f254":"84037.819091","f255":"9604.322182","f256":"2401.080545","f257":"24010.805455","f245":"144064.832728","f258":"264118.860001","f339":"3780.000000","f341":"140284.832727","f346":"0","medicalinsurancenumber":"0","socialinsurancenumber":"0","accountnumber":"1309305841"},{"EMPLOYEE_KEY_ID":"791100577","DEPARTMENT_ID":"15130911","EMPLOYEE_CODE":"0577","LAST_NAME":"\u041b\u0445\u0430\u0433\u0432\u0430\u0441\u04af\u0440\u044d\u043d","FIRST_NAME":"\u0411\u0430\u044f\u0440\u043c\u0430\u0430","department_name":"\u041c\u044d\u0434\u044d\u044d\u043b\u044d\u043b \u0442\u0435\u0445\u043d\u043e\u043b\u043e\u0433\u0438\u0439\u043d \u0430\u043b\u0431\u0430","f181":"0","f309":"0","f105":"0","f106":"0.000000","f335":"0","f100":"765494","f101":"22.00","f103":"176.000000","f102":"22","f104":"176.000000","f279":"64","f141":"0","f108":"765493.999996","prworkedyear":"3.9","f314":"0.000000","f269":"0.000000","f336":"1.50","f142":"0.000000","f342":"600.000000","f280":"38400.000000","f266":"0","f267":"0.000000","f111":"99000.000000","f231":"0","f232":"0.000000","f277":"0","f187":"0.000000","f188":"0.000000","f157":"382746.999998","f268":"0.000000","f290":"0","f332":"0","f281":"0.000000","f282":"0","f167":"0","f120":"0","f171":"0","f289":"137400.000000","f308":"902893.999996","insuredtypename":"1","insuredtypecode":"1.000000","f246":"902893.999996","f247":"18057.880000","f248":"63202.580000","f249":"7223.152000","f250":"1805.788000","f251":"0","f244":"90289.400000","f159":"90289.400000","f295":"9900.000000","f288":"0.000000","f161":"75250.460000","f323":"0","f271":"0.000000","f283":"0","f137":"0","f285":"0.000000","f330":"0","f239":"0","f304":"0","f162":"548286.859998","f178":"354607","f130":"354607","f118":"0","f252":"902893.999996","f253":"18057.880000","f254":"63202.580000","f255":"7223.152000","f256":"1805.788000","f257":"18057.880000","f245":"108347.280000","f258":"198636.680000","f339":"11880.000000","f341":"96467.280000","f346":"0","medicalinsurancenumber":"0","socialinsurancenumber":"0","accountnumber":"1309288282"},{"EMPLOYEE_KEY_ID":"791100571","DEPARTMENT_ID":"15130911","EMPLOYEE_CODE":"0571","LAST_NAME":"\u0411\u0430\u0430\u0441\u0430\u043d\u0445\u04af\u04af","FIRST_NAME":"\u0411\u0430\u044f\u0440\u043c\u0430\u0430","department_name":"\u041c\u044d\u0434\u044d\u044d\u043b\u044d\u043b \u0442\u0435\u0445\u043d\u043e\u043b\u043e\u0433\u0438\u0439\u043d \u0430\u043b\u0431\u0430","f181":"0","f309":"0","f105":"0","f106":"0.000000","f335":"0","f100":"988691","f101":"22.00","f103":"176.000000","f102":"0","f104":"0.000000","f279":"0","f141":"0","f108":"0.000000","prworkedyear":"3.6","f314":"0.000000","f269":"0.000000","f336":"1.50","f142":"0.000000","f342":"600.000000","f280":"0.000000","f266":"0","f267":"0.000000","f111":"0.000000","f231":"0","f232":"0.000000","f277":"0","f187":"0.000000","f188":"0.000000","f157":"0.000000","f268":"0.000000","f290":"0","f332":"0","f281":"0.000000","f282":"0","f167":"0","f120":"0","f171":"0","f289":"0.000000","f308":"0.000000","insuredtypename":"6","insuredtypecode":"6.000000","f246":"0.000000","f247":"0.000000","f248":"0.000000","f249":"0.000000","f250":"0.000000","f251":"0","f244":"0.000000","f159":"0.000000","f295":"0.000000","f288":"0.000000","f161":"0.000000","f323":"0","f271":"0.000000","f283":"0","f137":"0","f285":"0.000000","f330":"0","f239":"0","f304":"0","f162":"0.000000","f178":"0","f130":"0","f118":"0","f252":"0.000000","f253":"0.000000","f254":"0.000000","f255":"0.000000","f256":"0.000000","f257":"0.000000","f245":"0.000000","f258":"0.000000","f339":"0.000000","f341":"0.000000","f346":"0","medicalinsurancenumber":"0","socialinsurancenumber":"0","accountnumber":"1305012098"},{"EMPLOYEE_KEY_ID":"791100582","DEPARTMENT_ID":"15130911","EMPLOYEE_CODE":"0582","LAST_NAME":"\u0421\u0430\u043c\u0431\u0443\u0443","FIRST_NAME":"\u0411\u0430\u044f\u0440\u0441\u0430\u0439\u0445\u0430\u043d","department_name":"\u041c\u044d\u0434\u044d\u044d\u043b\u044d\u043b \u0442\u0435\u0445\u043d\u043e\u043b\u043e\u0433\u0438\u0439\u043d \u0430\u043b\u0431\u0430","f181":"0","f309":"0","f105":"0","f106":"0.000000","f335":"0","f100":"765494","f101":"22.00","f103":"176.000000","f102":"22","f104":"176.000000","f279":"0","f141":"0","f108":"765493.999996","prworkedyear":"19.1","f314":"15.000000","f269":"114824.099999","f336":"1.50","f142":"0.000000","f342":"600.000000","f280":"0.000000","f266":"0","f267":"0.000000","f111":"99000.000000","f231":"0","f232":"0.000000","f277":"0","f187":"0.000000","f188":"0.000000","f157":"382746.999998","f268":"0.000000","f290":"0","f332":"0","f281":"0.000000","f282":"0","f167":"0","f120":"0","f171":"0","f289":"213824.099999","f308":"979318.099995","insuredtypename":"1","insuredtypecode":"1.000000","f246":"979318.099995","f247":"19586.362000","f248":"68552.267000","f249":"7834.544800","f250":"1958.636200","f251":"0","f244":"97931.810000","f159":"97931.810000","f295":"9900.000000","f288":"0.000000","f161":"82128.629000","f323":"0","f271":"0.000000","f283":"0","f137":"0","f285":"0.000000","f330":"-10000","f239":"0","f304":"0","f162":"562807.438998","f178":"416511","f130":"416511","f118":"0","f252":"979318.099995","f253":"19586.362000","f254":"68552.267000","f255":"7834.544800","f256":"1958.636200","f257":"19586.362000","f245":"117518.172000","f258":"215449.982000","f339":"11880.000000","f341":"105638.171999","f346":"0","medicalinsurancenumber":"0","socialinsurancenumber":"0","accountnumber":"1309143409"},{"EMPLOYEE_KEY_ID":"791100572","DEPARTMENT_ID":"15130911","EMPLOYEE_CODE":"0572","LAST_NAME":"\u041f\u04af\u0440\u044d\u0432\u0436\u0430\u043b","FIRST_NAME":"\u0411\u0443\u043b\u0433\u0430\u043d\u0442\u0430\u043c\u0438\u0440","department_name":"\u041c\u044d\u0434\u044d\u044d\u043b\u044d\u043b \u0442\u0435\u0445\u043d\u043e\u043b\u043e\u0433\u0438\u0439\u043d \u0430\u043b\u0431\u0430","f181":"0","f309":"0","f105":"0","f106":"0.000000","f335":"0","f100":"988691","f101":"22.00","f103":"176.000000","f102":"22","f104":"176.000000","f279":"80","f141":"64","f108":"988691.000000","prworkedyear":"18.1","f314":"15.000000","f269":"148303.650000","f336":"1.50","f142":"539286.000000","f342":"600.000000","f280":"48000.000000","f266":"0","f267":"0.000000","f111":"99000.000000","f231":"0","f232":"0.000000","f277":"0","f187":"0.000000","f188":"0.000000","f157":"494345.500000","f268":"0.000000","f290":"0","f332":"0","f281":"0.000000","f282":"0","f167":"0","f120":"0","f171":"0","f289":"834589.650000","f308":"1823280.650000","insuredtypename":"1","insuredtypecode":"1.000000","f246":"1823280.650000","f247":"36465.613000","f248":"127629.645500","f249":"14586.245200","f250":"3646.561300","f251":"0","f244":"182328.065000","f159":"182328.065000","f295":"9900.000000","f288":"0.000000","f161":"158085.258500","f323":"0","f271":"0.000000","f283":"0","f137":"0","f285":"0.000000","f330":"3300","f239":"0","f304":"0","f162":"834758.823500","f178":"988522","f130":"988522","f118":"0","f252":"1823280.650000","f253":"36465.613000","f254":"127629.645500","f255":"14586.245200","f256":"3646.561300","f257":"36465.613000","f245":"218793.678000","f258":"401121.743000","f339":"11880.000000","f341":"206913.678000","f346":"0","medicalinsurancenumber":"0","socialinsurancenumber":"0","accountnumber":"451021353"},{"EMPLOYEE_KEY_ID":"791100586","DEPARTMENT_ID":"15130911","EMPLOYEE_CODE":"0586","LAST_NAME":"\u0427\u0438\u043c\u044d\u0434\u0434\u043e\u0440\u0436","FIRST_NAME":"\u0413\u0430\u043d\u0431\u043e\u043b\u0434","department_name":"\u041c\u044d\u0434\u044d\u044d\u043b\u044d\u043b \u0442\u0435\u0445\u043d\u043e\u043b\u043e\u0433\u0438\u0439\u043d \u0430\u043b\u0431\u0430","f181":"0","f309":"0","f105":"0","f106":"0.000000","f335":"0","f100":"665258","f101":"22.00","f103":"176.000000","f102":"22","f104":"176.000000","f279":"56","f141":"0","f108":"665258.000000","prworkedyear":"10.1","f314":"8.000000","f269":"53220.640000","f336":"1.50","f142":"0.000000","f342":"600.000000","f280":"33600.000000","f266":"0","f267":"0.000000","f111":"99000.000000","f231":"0","f232":"0.000000","f277":"0","f187":"0.000000","f188":"0.000000","f157":"332629.000000","f268":"0.000000","f290":"0","f332":"0","f281":"0.000000","f282":"0","f167":"0","f120":"0","f171":"0","f289":"185820.640000","f308":"851078.640000","insuredtypename":"1","insuredtypecode":"1.000000","f246":"851078.640000","f247":"17021.572800","f248":"59575.504800","f249":"6808.629120","f250":"1702.157280","f251":"0","f244":"85107.864000","f159":"85107.864000","f295":"9900.000000","f288":"0.000000","f161":"70587.077600","f323":"0","f271":"0.000000","f283":"0","f137":"0","f285":"0.000000","f330":"0","f239":"0","f304":"0","f162":"488323.941600","f178":"362755","f130":"362755","f118":"0","f252":"851078.640000","f253":"17021.572800","f254":"59575.504800","f255":"6808.629120","f256":"1702.157280","f257":"17021.572800","f245":"102129.436800","f258":"187237.300800","f339":"11880.000000","f341":"90249.436800","f346":"0","medicalinsurancenumber":"0","socialinsurancenumber":"0","accountnumber":"451024310"},{"EMPLOYEE_KEY_ID":"791100567","DEPARTMENT_ID":"15130911","EMPLOYEE_CODE":"0567","LAST_NAME":"\u0414\u043e\u0440\u043b\u0438\u0433","FIRST_NAME":"\u0414\u0430\u0432\u0430\u0430\u0434\u043e\u0440\u0436","department_name":"\u041c\u044d\u0434\u044d\u044d\u043b\u044d\u043b \u0442\u0435\u0445\u043d\u043e\u043b\u043e\u0433\u0438\u0439\u043d \u0430\u043b\u0431\u0430","f181":"0","f309":"0","f105":"0","f106":"0.000000","f335":"0","f100":"1021313","f101":"22.00","f103":"176.000000","f102":"19","f104":"152.000000","f279":"0","f141":"0","f108":"882043.045458","prworkedyear":"28.2","f314":"25.000000","f269":"220510.761365","f336":"1.50","f142":"0.000000","f342":"600.000000","f280":"0.000000","f266":"0","f267":"0.000000","f111":"85500.000000","f231":"0","f232":"0.000000","f277":"0","f187":"0.000000","f188":"0.000000","f157":"510656.500002","f268":"0.000000","f290":"0","f332":"0","f281":"0.000000","f282":"0","f167":"0","f120":"0","f171":"0","f289":"306010.761365","f308":"1188053.806823","insuredtypename":"1","insuredtypecode":"1.000000","f246":"1188053.806823","f247":"23761.076136","f248":"83163.766478","f249":"9504.430455","f250":"2376.107614","f251":"0","f244":"118805.380683","f159":"118805.380683","f295":"8550.000000","f288":"0.000000","f161":"100779.842614","f323":"0","f271":"0.000000","f283":"0","f137":"0","f285":"0.000000","f330":"177000","f239":"0","f304":"0","f162":"730241.723299","f178":"457812","f130":"457812","f118":"0","f252":"1188053.806823","f253":"23761.076136","f254":"83163.766478","f255":"9504.430455","f256":"2376.107614","f257":"23761.076136","f245":"142566.456819","f258":"261371.837502","f339":"10260.000000","f341":"132306.456819","f346":"0","medicalinsurancenumber":"0","socialinsurancenumber":"0","accountnumber":"1305103432"},{"EMPLOYEE_KEY_ID":"791100583","DEPARTMENT_ID":"15130911","EMPLOYEE_CODE":"0583","LAST_NAME":"\u0422\u0443\u0432\u0430\u0430\u043d\u0436\u0430\u0432","FIRST_NAME":"\u0414\u044d\u043b\u0433\u044d\u0440\u043c\u04e9\u0440\u04e9\u043d","department_name":"\u041c\u044d\u0434\u044d\u044d\u043b\u044d\u043b \u0442\u0435\u0445\u043d\u043e\u043b\u043e\u0433\u0438\u0439\u043d \u0430\u043b\u0431\u0430","f181":"0","f309":"0","f105":"0","f106":"0.000000","f335":"0","f100":"765494","f101":"22.00","f103":"176.000000","f102":"22","f104":"176.000000","f279":"0","f141":"0","f108":"765493.999996","prworkedyear":"20.4","f314":"20.000000","f269":"153098.799999","f336":"1.50","f142":"0.000000","f342":"600.000000","f280":"0.000000","f266":"0","f267":"0.000000","f111":"99000.000000","f231":"0","f232":"0.000000","f277":"0","f187":"0.000000","f188":"0.000000","f157":"382746.999998","f268":"0.000000","f290":"0","f332":"0","f281":"0.000000","f282":"0","f167":"0","f120":"0","f171":"0","f289":"252098.799999","f308":"1017592.799995","insuredtypename":"1","insuredtypecode":"1.000000","f246":"1017592.799995","f247":"20351.856000","f248":"71231.496000","f249":"8140.742400","f250":"2035.185600","f251":"0","f244":"101759.280000","f159":"101759.280000","f295":"9900.000000","f288":"0.000000","f161":"85573.352000","f323":"0","f271":"0.000000","f283":"0","f137":"0","f285":"0.000000","f330":"0","f239":"0","f304":"0","f162":"570079.631998","f178":"447513","f130":"447513","f118":"0","f252":"1017592.799995","f253":"20351.856000","f254":"71231.496000","f255":"8140.742400","f256":"2035.185600","f257":"20351.856000","f245":"122111.136000","f258":"223870.416000","f339":"11880.000000","f341":"110231.135999","f346":"0","medicalinsurancenumber":"0","socialinsurancenumber":"0","accountnumber":"1309101390"},{"EMPLOYEE_KEY_ID":"1458822077195","DEPARTMENT_ID":"15130911","EMPLOYEE_CODE":"1943","LAST_NAME":"\u0422\u04e9\u0433\u0441\u0436\u0430\u0440\u0433\u0430\u043b","FIRST_NAME":"\u0416\u0430\u0440\u0433\u0430\u043b\u0441\u0430\u0439\u0445\u0430\u043d","department_name":"\u041c\u044d\u0434\u044d\u044d\u043b\u044d\u043b \u0442\u0435\u0445\u043d\u043e\u043b\u043e\u0433\u0438\u0439\u043d \u0430\u043b\u0431\u0430","f181":"0","f309":"0","f105":"0","f106":"0.000000","f335":"0","f100":"588038","f101":"22.00","f103":"176.000000","f102":"14","f104":"112.000000","f279":"40","f141":"8","f108":"374206.000000","prworkedyear":"1.1","f314":"0.000000","f269":"0.000000","f336":"1.50","f142":"40093.500000","f342":"600.000000","f280":"24000.000000","f266":"0","f267":"0.000000","f111":"63000.000000","f231":"0","f232":"0.000000","f277":"0","f187":"0.000000","f188":"0.000000","f157":"294019.000000","f268":"0.000000","f290":"0","f332":"0","f281":"0.000000","f282":"0","f167":"0","f120":"0","f171":"0","f289":"127093.500000","f308":"501299.500000","insuredtypename":"1","insuredtypecode":"1.000000","f246":"501299.500000","f247":"10025.990000","f248":"35090.965000","f249":"4010.396000","f250":"1002.599000","f251":"0","f244":"50129.950000","f159":"50129.950000","f295":"6300.000000","f288":"0.000000","f161":"38746.955000","f323":"0","f271":"0.000000","f283":"0","f137":"0","f285":"0.000000","f330":"37125","f239":"0","f304":"0","f162":"382895.905000","f178":"118404","f130":"118404","f118":"0","f252":"501299.500000","f253":"10025.990000","f254":"35090.965000","f255":"4010.396000","f256":"1002.599000","f257":"10025.990000","f245":"60155.940000","f258":"110285.890000","f339":"7560.000000","f341":"52595.940000","f346":"0","medicalinsurancenumber":"0","socialinsurancenumber":"0","accountnumber":"457046479"},{"EMPLOYEE_KEY_ID":"791100573","DEPARTMENT_ID":"15130911","EMPLOYEE_CODE":"0573","LAST_NAME":"\u0427\u043e\u0439\u0436\u0438\u043b\u0441\u04af\u0440\u044d\u043d","FIRST_NAME":"\u0417\u043e\u0440\u0438\u0433\u043e\u043e","department_name":"\u041c\u044d\u0434\u044d\u044d\u043b\u044d\u043b \u0442\u0435\u0445\u043d\u043e\u043b\u043e\u0433\u0438\u0439\u043d \u0430\u043b\u0431\u0430","f181":"0","f309":"0","f105":"0","f106":"0.000000","f335":"0","f100":"988691","f101":"22.00","f103":"176.000000","f102":"22","f104":"176.000000","f279":"80","f141":"64","f108":"988691.000000","prworkedyear":"25.1","f314":"25.000000","f269":"247172.750000","f336":"1.50","f142":"539286.000000","f342":"600.000000","f280":"48000.000000","f266":"0","f267":"0.000000","f111":"99000.000000","f231":"0","f232":"0.000000","f277":"0","f187":"0.000000","f188":"0.000000","f157":"494345.500000","f268":"0.000000","f290":"0","f332":"0","f281":"0.000000","f282":"0","f167":"0","f120":"0","f171":"0","f289":"933458.750000","f308":"1922149.750000","insuredtypename":"1","insuredtypecode":"1.000000","f246":"1922149.750000","f247":"38442.995000","f248":"134550.482500","f249":"15377.198000","f250":"3844.299500","f251":"0","f244":"192214.975000","f159":"192000.000000","f295":"9900.000000","f288":"0.000000","f161":"167004.975000","f323":"0","f271":"0.000000","f283":"0","f137":"0","f285":"0.000000","f330":"0","f239":"0","f304":"0","f162":"853350.475000","f178":"1068799","f130":"1068799","f118":"0","f252":"1922149.750000","f253":"38442.995000","f254":"134550.482500","f255":"15377.198000","f256":"3844.299500","f257":"38442.995000","f245":"230657.970000","f258":"422657.970000","f339":"11880.000000","f341":"218777.970000","f346":"0","medicalinsurancenumber":"0","socialinsurancenumber":"0","accountnumber":"451021438"},{"EMPLOYEE_KEY_ID":"1458822088115","DEPARTMENT_ID":"15130911","EMPLOYEE_CODE":"1947","LAST_NAME":"\u041d\u044d\u0440\u0433\u04af\u0439","FIRST_NAME":"\u0417\u04af\u0447\u0438","department_name":"\u041c\u044d\u0434\u044d\u044d\u043b\u044d\u043b \u0442\u0435\u0445\u043d\u043e\u043b\u043e\u0433\u0438\u0439\u043d \u0430\u043b\u0431\u0430","f181":"0","f309":"0","f105":"0","f106":"0.000000","f335":"0","f100":"911471","f101":"22.00","f103":"176.000000","f102":"22","f104":"176.000000","f279":"0","f141":"0","f108":"911471.000000","prworkedyear":"2.2","f314":"0.000000","f269":"0.000000","f336":"1.50","f142":"0.000000","f342":"600.000000","f280":"0.000000","f266":"0","f267":"0.000000","f111":"99000.000000","f231":"0","f232":"0.000000","f277":"0","f187":"0.000000","f188":"0.000000","f157":"455735.500000","f268":"0.000000","f290":"0","f332":"0","f281":"0.000000","f282":"0","f167":"0","f120":"0","f171":"0","f289":"99000.000000","f308":"1010471.000000","insuredtypename":"1","insuredtypecode":"1.000000","f246":"1010471.000000","f247":"20209.420000","f248":"70732.970000","f249":"8083.768000","f250":"2020.942000","f251":"0","f244":"101047.100000","f159":"101047.100000","f295":"9900.000000","f288":"0.000000","f161":"84932.390000","f323":"0","f271":"0.000000","f283":"0","f137":"0","f285":"0.000000","f330":"0","f239":"0","f304":"0","f162":"641714.990000","f178":"368756","f130":"368756","f118":"0","f252":"1010471.000000","f253":"20209.420000","f254":"70732.970000","f255":"8083.768000","f256":"2020.942000","f257":"20209.420000","f245":"121256.520000","f258":"222303.620000","f339":"11880.000000","f341":"109376.520000","f346":"0","medicalinsurancenumber":"0","socialinsurancenumber":"0","accountnumber":"4009314090"},{"EMPLOYEE_KEY_ID":"791100587","DEPARTMENT_ID":"15130911","EMPLOYEE_CODE":"0587","LAST_NAME":"\u041c\u04e9\u043d\u0441\u04e9\u043b","FIRST_NAME":"\u041c\u0430\u043d\u043b\u0430\u0439\u0445\u04af\u04af","department_name":"\u041c\u044d\u0434\u044d\u044d\u043b\u044d\u043b \u0442\u0435\u0445\u043d\u043e\u043b\u043e\u0433\u0438\u0439\u043d \u0430\u043b\u0431\u0430","f181":"0","f309":"0","f105":"0","f106":"0.000000","f335":"0","f100":"665258","f101":"22.00","f103":"176.000000","f102":"2","f104":"16.000000","f279":"8","f141":"8","f108":"60478.000000","prworkedyear":"10.4","f314":"12.000000","f269":"7257.360000","f336":"1.50","f142":"45358.500000","f342":"600.000000","f280":"4800.000000","f266":"0","f267":"0.000000","f111":"9000.000000","f231":"0","f232":"0.000000","f277":"0","f187":"20.000000","f188":"1218083.000000","f157":"0.000000","f268":"0.000000","f290":"0","f332":"0","f281":"0.000000","f282":"0","f167":"0","f120":"0","f171":"0","f289":"1284498.860000","f308":"1344976.860000","insuredtypename":"1","insuredtypecode":"1.000000","f246":"1344976.860000","f247":"26899.537200","f248":"94148.380200","f249":"10759.814880","f250":"2689.953720","f251":"0","f244":"134497.686000","f159":"134497.686000","f295":"900.000000","f288":"0.000000","f161":"114137.917400","f323":"0","f271":"0.000000","f283":"0","f137":"0","f285":"993647.230000","f330":"0","f239":"0","f304":"0","f162":"1242282.833400","f178":"102694","f130":"102694","f118":"0","f252":"1344976.860000","f253":"26899.537200","f254":"94148.380200","f255":"10759.814880","f256":"2689.953720","f257":"26899.537200","f245":"161397.223200","f258":"295894.909200","f339":"1080.000000","f341":"160317.223200","f346":"0","medicalinsurancenumber":"0","socialinsurancenumber":"0","accountnumber":"451022765"},{"EMPLOYEE_KEY_ID":"791100569","DEPARTMENT_ID":"15130911","EMPLOYEE_CODE":"0569","LAST_NAME":"\u0421\u0430\u043d\u0445\u04af\u04af","FIRST_NAME":"\u041d\u0430\u043d\u0434\u0438\u043d\u0445\u04af\u04af","department_name":"\u041c\u044d\u0434\u044d\u044d\u043b\u044d\u043b \u0442\u0435\u0445\u043d\u043e\u043b\u043e\u0433\u0438\u0439\u043d \u0430\u043b\u0431\u0430","f181":"0","f309":"0","f105":"0","f106":"0.000000","f335":"0","f100":"1021313","f101":"22.00","f103":"176.000000","f102":"22","f104":"176.000000","f279":"0","f141":"0","f108":"1021313.000004","prworkedyear":"6.0","f314":"5.000000","f269":"51065.650000","f336":"1.50","f142":"0.000000","f342":"600.000000","f280":"0.000000","f266":"0","f267":"0.000000","f111":"99000.000000","f231":"0","f232":"0.000000","f277":"0","f187":"0.000000","f188":"0.000000","f157":"510656.500002","f268":"0.000000","f290":"0","f332":"0","f281":"0.000000","f282":"0","f167":"0","f120":"0","f171":"0","f289":"150065.650000","f308":"1171378.650004","insuredtypename":"1","insuredtypecode":"1.000000","f246":"1171378.650004","f247":"23427.573000","f248":"81996.505500","f249":"9371.029200","f250":"2342.757300","f251":"0","f244":"117137.865000","f159":"117137.865000","f295":"9900.000000","f288":"0.000000","f161":"99414.078500","f323":"0","f271":"0.000000","f283":"0","f137":"0","f285":"0.000000","f330":"0","f239":"0","f304":"0","f162":"727208.443502","f178":"444170","f130":"444170","f118":"0","f252":"1171378.650004","f253":"23427.573000","f254":"81996.505500","f255":"9371.029200","f256":"2342.757300","f257":"23427.573000","f245":"140565.438000","f258":"257703.303000","f339":"11880.000000","f341":"128685.438000","f346":"0","medicalinsurancenumber":"0","socialinsurancenumber":"0","accountnumber":"451025263"},{"EMPLOYEE_KEY_ID":"791100565","DEPARTMENT_ID":"15130911","EMPLOYEE_CODE":"0565","LAST_NAME":"\u041d\u0430\u0439\u0433\u0430\u043b","FIRST_NAME":"\u041d\u0430\u0440\u0430\u043d\u0431\u0430\u0430\u0442\u0430\u0440","department_name":"\u041c\u044d\u0434\u044d\u044d\u043b\u044d\u043b \u0442\u0435\u0445\u043d\u043e\u043b\u043e\u0433\u0438\u0439\u043d \u0430\u043b\u0431\u0430","f181":"0","f309":"0","f105":"0","f106":"0.000000","f335":"0","f100":"1034528","f101":"22.00","f103":"176.000000","f102":"22","f104":"176.000000","f279":"0","f141":"0","f108":"1034528.000000","prworkedyear":"1.7","f314":"0.000000","f269":"0.000000","f336":"1.50","f142":"0.000000","f342":"600.000000","f280":"0.000000","f266":"0","f267":"0.000000","f111":"99000.000000","f231":"0","f232":"0.000000","f277":"0","f187":"0.000000","f188":"0.000000","f157":"517264.000000","f268":"0.000000","f290":"0","f332":"0","f281":"0.000000","f282":"0","f167":"0","f120":"0","f171":"0","f289":"99000.000000","f308":"1133528.000000","insuredtypename":"1","insuredtypecode":"1.000000","f246":"1133528.000000","f247":"22670.560000","f248":"79346.960000","f249":"9068.224000","f250":"2267.056000","f251":"0","f244":"113352.800000","f159":"113352.800000","f295":"9900.000000","f288":"0.000000","f161":"96007.520000","f323":"0","f271":"0.000000","f283":"0","f137":"0","f285":"0.000000","f330":"0","f239":"0","f304":"0","f162":"726624.320000","f178":"406904","f130":"406904","f118":"0","f252":"1133528.000000","f253":"22670.560000","f254":"79346.960000","f255":"9068.224000","f256":"2267.056000","f257":"22670.560000","f245":"136023.360000","f258":"249376.160000","f339":"11880.000000","f341":"124143.360000","f346":"0","medicalinsurancenumber":"0","socialinsurancenumber":"0","accountnumber":"1109239770"},{"EMPLOYEE_KEY_ID":"791100579","DEPARTMENT_ID":"15130911","EMPLOYEE_CODE":"0579","LAST_NAME":"\u0410\u043b\u0433\u0430\u0430","FIRST_NAME":"\u041d\u044d\u0440\u0433\u04af\u0439","department_name":"\u041c\u044d\u0434\u044d\u044d\u043b\u044d\u043b \u0442\u0435\u0445\u043d\u043e\u043b\u043e\u0433\u0438\u0439\u043d \u0430\u043b\u0431\u0430","f181":"0","f309":"0","f105":"0","f106":"0.000000","f335":"0","f100":"765494","f101":"22.00","f103":"176.000000","f102":"22","f104":"176.000000","f279":"0","f141":"0","f108":"765493.999996","prworkedyear":"22.1","f314":"20.000000","f269":"153098.799999","f336":"1.50","f142":"0.000000","f342":"600.000000","f280":"0.000000","f266":"0","f267":"0.000000","f111":"99000.000000","f231":"0","f232":"0.000000","f277":"0","f187":"0.000000","f188":"0.000000","f157":"382746.999998","f268":"0.000000","f290":"0","f332":"0","f281":"0.000000","f282":"0","f167":"0","f120":"0","f171":"0","f289":"252098.799999","f308":"1017592.799995","insuredtypename":"1","insuredtypecode":"1.000000","f246":"1017592.799995","f247":"20351.856000","f248":"71231.496000","f249":"8140.742400","f250":"2035.185600","f251":"0","f244":"101759.280000","f159":"101759.280000","f295":"9900.000000","f288":"0.000000","f161":"85573.352000","f323":"0","f271":"0.000000","f283":"0","f137":"0","f285":"0.000000","f330":"0","f239":"0","f304":"0","f162":"570079.631998","f178":"447513","f130":"447513","f118":"0","f252":"1017592.799995","f253":"20351.856000","f254":"71231.496000","f255":"8140.742400","f256":"2035.185600","f257":"20351.856000","f245":"122111.136000","f258":"223870.416000","f339":"11880.000000","f341":"110231.135999","f346":"0","medicalinsurancenumber":"0","socialinsurancenumber":"0","accountnumber":"1309203293"},{"EMPLOYEE_KEY_ID":"791100570","DEPARTMENT_ID":"15130911","EMPLOYEE_CODE":"0570","LAST_NAME":"\u0414\u04af\u0433\u044d\u0440\u0436\u0430\u0432","FIRST_NAME":"\u041e\u0447\u0438\u0440\u0441\u04af\u0440\u044d\u043d","department_name":"\u041c\u044d\u0434\u044d\u044d\u043b\u044d\u043b \u0442\u0435\u0445\u043d\u043e\u043b\u043e\u0433\u0438\u0439\u043d \u0430\u043b\u0431\u0430","f181":"0","f309":"0","f105":"0","f106":"0.000000","f335":"0","f100":"988691","f101":"22.00","f103":"176.000000","f102":"22","f104":"176.000000","f279":"0","f141":"0","f108":"988691.000000","prworkedyear":"13.5","f314":"12.000000","f269":"118642.920000","f336":"1.50","f142":"0.000000","f342":"600.000000","f280":"0.000000","f266":"0","f267":"0.000000","f111":"99000.000000","f231":"0","f232":"0.000000","f277":"0","f187":"0.000000","f188":"0.000000","f157":"494345.500000","f268":"0.000000","f290":"0","f332":"0","f281":"0.000000","f282":"0","f167":"0","f120":"0","f171":"0","f289":"217642.920000","f308":"1206333.920000","insuredtypename":"1","insuredtypecode":"1.000000","f246":"1206333.920000","f247":"24126.678400","f248":"84443.374400","f249":"9650.671360","f250":"2412.667840","f251":"0","f244":"120633.392000","f159":"120633.392000","f295":"9900.000000","f288":"0.000000","f161":"102560.052800","f323":"0","f271":"0.000000","f283":"0","f137":"0","f285":"0.000000","f330":"0","f239":"0","f304":"0","f162":"717538.944800","f178":"488795","f130":"488795","f118":"0","f252":"1206333.920000","f253":"24126.678400","f254":"84443.374400","f255":"9650.671360","f256":"2412.667840","f257":"24126.678400","f245":"144760.070400","f258":"265393.462400","f339":"11880.000000","f341":"132880.070400","f346":"0","medicalinsurancenumber":"0","socialinsurancenumber":"0","accountnumber":"451021357"},{"EMPLOYEE_KEY_ID":"791100574","DEPARTMENT_ID":"15130911","EMPLOYEE_CODE":"0574","LAST_NAME":"\u0422\u043e\u0433\u0442\u043e\u0445\u0441\u04af\u0440\u044d\u043d","FIRST_NAME":"\u041e\u044e\u0443\u043d\u0447\u0438\u043c\u044d\u0433","department_name":"\u041c\u044d\u0434\u044d\u044d\u043b\u044d\u043b \u0442\u0435\u0445\u043d\u043e\u043b\u043e\u0433\u0438\u0439\u043d \u0430\u043b\u0431\u0430","f181":"0","f309":"0","f105":"0","f106":"0.000000","f335":"0","f100":"988691","f101":"22.00","f103":"176.000000","f102":"0","f104":"0.000000","f279":"0","f141":"0","f108":"0.000000","prworkedyear":"26.9","f314":"0.000000","f269":"0.000000","f336":"1.50","f142":"0.000000","f342":"600.000000","f280":"0.000000","f266":"0","f267":"0.000000","f111":"0.000000","f231":"0","f232":"0.000000","f277":"0","f187":"26.000000","f188":"2553751.000000","f157":"0.000000","f268":"0.000000","f290":"0","f332":"0","f281":"0.000000","f282":"0","f167":"0","f120":"0","f171":"0","f289":"2553751.000000","f308":"2553751.000000","insuredtypename":"1","insuredtypecode":"1.000000","f246":"2553751.000000","f247":"51075.020000","f248":"178762.570000","f249":"20430.008000","f250":"5107.502000","f251":"0","f244":"255375.100000","f159":"192000.000000","f295":"0.000000","f288":"0.000000","f161":"229175.100000","f323":"0","f271":"0.000000","f283":"0","f137":"0","f285":"2132575.900000","f330":"0","f239":"0","f304":"0","f162":"2553751.000000","f178":"0","f130":"0","f118":"0","f252":"2553751.000000","f253":"51075.020000","f254":"178762.570000","f255":"20430.008000","f256":"5107.502000","f257":"51075.020000","f245":"306450.120000","f258":"498450.120000","f339":"0.000000","f341":"306450.120000","f346":"0","medicalinsurancenumber":"0","socialinsurancenumber":"0","accountnumber":"1309086372"},{"EMPLOYEE_KEY_ID":"791100580","DEPARTMENT_ID":"15130911","EMPLOYEE_CODE":"0580","LAST_NAME":"\u041c\u044f\u0433\u043c\u0430\u0440\u0431\u0430\u044f\u0440","FIRST_NAME":"\u04e8\u043d\u04e9\u0440\u0436\u0430\u0440\u0433\u0430\u043b","department_name":"\u041c\u044d\u0434\u044d\u044d\u043b\u044d\u043b \u0442\u0435\u0445\u043d\u043e\u043b\u043e\u0433\u0438\u0439\u043d \u0430\u043b\u0431\u0430","f181":"0","f309":"0","f105":"0","f106":"0.000000","f335":"0","f100":"765494","f101":"22.00","f103":"176.000000","f102":"22","f104":"176.000000","f279":"64","f141":"8","f108":"765493.999996","prworkedyear":"3.9","f314":"0.000000","f269":"0.000000","f336":"1.50","f142":"52192.772724","f342":"600.000000","f280":"38400.000000","f266":"0","f267":"0.000000","f111":"99000.000000","f231":"0","f232":"0.000000","f277":"0","f187":"0.000000","f188":"0.000000","f157":"382746.999998","f268":"0.000000","f290":"0","f332":"0","f281":"0.000000","f282":"0","f167":"0","f120":"0","f171":"0","f289":"189592.772724","f308":"955086.772720","insuredtypename":"1","insuredtypecode":"1.000000","f246":"955086.772720","f247":"19101.735454","f248":"66856.074090","f249":"7640.694182","f250":"1910.173545","f251":"0","f244":"95508.677271","f159":"95508.677271","f295":"9900.000000","f288":"0.000000","f161":"79947.809545","f323":"0","f271":"0.000000","f283":"0","f137":"0","f285":"0.000000","f330":"0","f239":"0","f304":"0","f162":"558203.486814","f178":"396883","f130":"396883","f118":"0","f252":"955086.772720","f253":"19101.735454","f254":"66856.074090","f255":"7640.694182","f256":"1910.173545","f257":"19101.735454","f245":"114610.412725","f258":"210119.089996","f339":"11880.000000","f341":"102730.412726","f346":"0","medicalinsurancenumber":"0","socialinsurancenumber":"0","accountnumber":"413002423"},{"EMPLOYEE_KEY_ID":"791100588","DEPARTMENT_ID":"15130911","EMPLOYEE_CODE":"0588","LAST_NAME":"\u0411\u043e\u044e\u0443","FIRST_NAME":"\u0421\u043e\u0434-\u042d\u0440\u0434\u044d\u043d\u044d","department_name":"\u041c\u044d\u0434\u044d\u044d\u043b\u044d\u043b \u0442\u0435\u0445\u043d\u043e\u043b\u043e\u0433\u0438\u0439\u043d \u0430\u043b\u0431\u0430","f181":"0","f309":"0","f105":"0","f106":"0.000000","f335":"0","f100":"665258","f101":"22.00","f103":"176.000000","f102":"22","f104":"176.000000","f279":"64","f141":"16","f108":"665258.000000","prworkedyear":"11.1","f314":"12.000000","f269":"79830.960000","f336":"1.50","f142":"90717.000000","f342":"600.000000","f280":"38400.000000","f266":"0","f267":"0.000000","f111":"99000.000000","f231":"0","f232":"0.000000","f277":"0","f187":"0.000000","f188":"0.000000","f157":"332629.000000","f268":"0.000000","f290":"0","f332":"0","f281":"0.000000","f282":"0","f167":"0","f120":"0","f171":"0","f289":"307947.960000","f308":"973205.960000","insuredtypename":"1","insuredtypecode":"1.000000","f246":"973205.960000","f247":"19464.119200","f248":"68124.417200","f249":"7785.647680","f250":"1946.411920","f251":"0","f244":"97320.596000","f159":"97320.596000","f295":"9900.000000","f288":"0.000000","f161":"81578.536400","f323":"0","f271":"0.000000","f283":"0","f137":"0","f285":"0.000000","f330":"0","f239":"0","f304":"0","f162":"511528.132400","f178":"461678","f130":"461678","f118":"0","f252":"973205.960000","f253":"19464.119200","f254":"68124.417200","f255":"7785.647680","f256":"1946.411920","f257":"19464.119200","f245":"116784.715200","f258":"214105.311200","f339":"11880.000000","f341":"104904.715200","f346":"0","medicalinsurancenumber":"0","socialinsurancenumber":"0","accountnumber":"451022538"},{"EMPLOYEE_KEY_ID":"791100581","DEPARTMENT_ID":"15130911","EMPLOYEE_CODE":"0581","LAST_NAME":"\u0427\u043e\u0439\u0432\u0430\u043d\u0447\u0438\u0433","FIRST_NAME":"\u0421\u044d\u0440\u0433\u044d\u043b\u044d\u043d\u0431\u0430\u0430\u0442\u0430\u0440","department_name":"\u041c\u044d\u0434\u044d\u044d\u043b\u044d\u043b \u0442\u0435\u0445\u043d\u043e\u043b\u043e\u0433\u0438\u0439\u043d \u0430\u043b\u0431\u0430","f181":"0","f309":"0","f105":"0","f106":"0.000000","f335":"0","f100":"765494","f101":"22.00","f103":"176.000000","f102":"19","f104":"152.000000","f279":"40","f141":"0","f108":"661108.454542","prworkedyear":"3.3","f314":"0.000000","f269":"0.000000","f336":"1.50","f142":"0.000000","f342":"600.000000","f280":"24000.000000","f266":"0","f267":"0.000000","f111":"85500.000000","f231":"0","f232":"0.000000","f277":"0","f187":"0.000000","f188":"0.000000","f157":"382746.999998","f268":"0.000000","f290":"0","f332":"0","f281":"0.000000","f282":"0","f167":"0","f120":"0","f171":"0","f289":"109500.000000","f308":"770608.454542","insuredtypename":"1","insuredtypecode":"1.000000","f246":"770608.454542","f247":"15412.169091","f248":"53942.591818","f249":"6164.867636","f250":"1541.216909","f251":"0","f244":"77060.845454","f159":"77060.845454","f295":"8550.000000","f288":"0.000000","f161":"63209.760909","f323":"0","f271":"0.000000","f283":"0","f137":"0","f285":"0.000000","f330":"0","f239":"0","f304":"0","f162":"523017.606361","f178":"247591","f130":"247591","f118":"0","f252":"770608.454542","f253":"15412.169091","f254":"53942.591818","f255":"6164.867636","f256":"1541.216909","f257":"15412.169091","f245":"92473.014545","f258":"169533.859999","f339":"10260.000000","f341":"82213.014545","f346":"0","medicalinsurancenumber":"0","socialinsurancenumber":"0","accountnumber":"456078070"},{"EMPLOYEE_KEY_ID":"791100575","DEPARTMENT_ID":"15130911","EMPLOYEE_CODE":"0575","LAST_NAME":"\u0414\u0430\u043c\u0431\u0430","FIRST_NAME":"\u0422\u04af\u0432\u0448\u0438\u043d\u0431\u0430\u044f\u0440","department_name":"\u041c\u044d\u0434\u044d\u044d\u043b\u044d\u043b \u0442\u0435\u0445\u043d\u043e\u043b\u043e\u0433\u0438\u0439\u043d \u0430\u043b\u0431\u0430","f181":"0","f309":"0","f105":"0","f106":"0.000000","f335":"0","f100":"988691","f101":"22.00","f103":"176.000000","f102":"22","f104":"176.000000","f279":"80","f141":"64","f108":"988691.000000","prworkedyear":"16.2","f314":"15.000000","f269":"148303.650000","f336":"1.50","f142":"539286.000000","f342":"600.000000","f280":"48000.000000","f266":"0","f267":"0.000000","f111":"99000.000000","f231":"0","f232":"0.000000","f277":"0","f187":"0.000000","f188":"0.000000","f157":"494345.500000","f268":"0.000000","f290":"0","f332":"0","f281":"0.000000","f282":"0","f167":"0","f120":"0","f171":"0","f289":"834589.650000","f308":"1823280.650000","insuredtypename":"1","insuredtypecode":"1.000000","f246":"1823280.650000","f247":"36465.613000","f248":"127629.645500","f249":"14586.245200","f250":"3646.561300","f251":"0","f244":"182328.065000","f159":"182328.065000","f295":"9900.000000","f288":"0.000000","f161":"158085.258500","f323":"0","f271":"0.000000","f283":"185400","f137":"0","f285":"0.000000","f330":"0","f239":"0","f304":"0","f162":"1020158.823500","f178":"803122","f130":"803122","f118":"0","f252":"1823280.650000","f253":"36465.613000","f254":"127629.645500","f255":"14586.245200","f256":"3646.561300","f257":"36465.613000","f245":"218793.678000","f258":"401121.743000","f339":"11880.000000","f341":"206913.678000","f346":"0","medicalinsurancenumber":"0","socialinsurancenumber":"0","accountnumber":"1305101970"},{"EMPLOYEE_KEY_ID":"791100589","DEPARTMENT_ID":"15130911","EMPLOYEE_CODE":"0589","LAST_NAME":"\u0414\u0430\u0432\u0430\u0430\u0446\u044d\u0440\u044d\u043d","FIRST_NAME":"\u0425\u0430\u0442\u0430\u043d\u0431\u0430\u0430\u0442\u0430\u0440","department_name":"\u041c\u044d\u0434\u044d\u044d\u043b\u044d\u043b \u0442\u0435\u0445\u043d\u043e\u043b\u043e\u0433\u0438\u0439\u043d \u0430\u043b\u0431\u0430","f181":"0","f309":"0","f105":"0","f106":"0.000000","f335":"0","f100":"665258","f101":"22.00","f103":"176.000000","f102":"8","f104":"64.000000","f279":"16","f141":"0","f108":"241912.000000","prworkedyear":"19.5","f314":"15.000000","f269":"36286.800000","f336":"1.50","f142":"0.000000","f342":"600.000000","f280":"9600.000000","f266":"0","f267":"0.000000","f111":"36000.000000","f231":"0","f232":"0.000000","f277":"0","f187":"0.000000","f188":"0.000000","f157":"332629.000000","f268":"0.000000","f290":"0","f332":"0","f281":"0.000000","f282":"0","f167":"0","f120":"0","f171":"0","f289":"81886.800000","f308":"323798.800000","insuredtypename":"1","insuredtypecode":"1.000000","f246":"323798.800000","f247":"6475.976000","f248":"22665.916000","f249":"2590.390400","f250":"647.597600","f251":"0","f244":"32379.880000","f159":"32379.880000","f295":"3600.000000","f288":"0.000000","f161":"22501.892000","f323":"0","f271":"0.000000","f283":"0","f137":"0","f285":"0.000000","f330":"0","f239":"0","f304":"0","f162":"387510.772000","f178":"-63712","f130":"-63712","f118":"0","f252":"323798.800000","f253":"6475.976000","f254":"22665.916000","f255":"2590.390400","f256":"647.597600","f257":"6475.976000","f245":"38855.856000","f258":"71235.736000","f339":"4320.000000","f341":"34535.856000","f346":"0","medicalinsurancenumber":"0","socialinsurancenumber":"0","accountnumber":"1309088922"}],"type":"save","editSalaryBookId":""}';
       $logStringToArr = json_decode($logString, true);
       $_POST['params'] = $logStringToArr['params'];
       $_POST['datas'] = $logStringToArr['datas'];
       $_POST['type'] = $logStringToArr['type'];
       $_POST['editSalaryBookId'] = $logStringToArr['editSalaryBookId'];
        */

        @file_put_contents(BASEPATH.'log/custom_access.log', Date::currentDate()."\r\n".Session::get(SESSION_PREFIX.'username')."\r\n".json_encode($_POST)."\r\n\r\n", FILE_APPEND);

        $result = array(
            'type' => 'error',
            'title' => 'Анхааруулга',
            'text' => 'Амжилтгүй боллоо.'
        );

        if (Input::postCheck('params') && Input::postCheck('datas') && Input::postCheck('type') || !empty($post)) {

            if(empty($post)){
               parse_str(Input::post('params'), $params);
            }else{
                $params = $post['params'];
            }

            $calcTypeId = $params['calcTypeId'];
            $calcId = $params['calcId'];
            $type = (empty($post) ? Input::post('type'):$post['type']);
            $datas = (empty($post) ? $_POST['datas']:$post['datas']);
            $departmentId = empty($post) ? (is_array($params['departmentId']) ? $params['departmentId']:explode(',',$params['departmentId'])):$post['dep'];
            $departmentIds = array();
            foreach ($datas as $k => $val) {
                array_push($departmentIds, $val['DEPARTMENT_ID']);
            }
            $sheetLogDatas = array_filter(json_decode($_POST['paramSheetLogDatas'], true));

            $convertDepartmentIdArray = implode(',', $departmentIds);
            $removeDuplicateDepartmentIds = implode(',', array_unique(explode(',', $convertDepartmentIdArray)));
            $departmentIds = explode(',', $removeDuplicateDepartmentIds);

            if ($type == 'edit') {
                $salaryBookId = empty($post) ? Input::post('editSalaryBookId'):$post['editsalarybookid'];
                $bookIdArray = explode(",",$salaryBookId);
                if (strpos(',', $salaryBookId) === false) {
                    $selectSalarySheetEmployeeList = "SELECT ID, EMPLOYEE_KEY_ID FROM PRL_SALARY_SHEET WHERE SAL_BOOK_ID IN ($salaryBookId)";
                } else {
                    $selectSalarySheetEmployeeList = "SELECT ID, EMPLOYEE_KEY_ID FROM PRL_SALARY_SHEET WHERE SAL_BOOK_ID = $salaryBookId";
                }

                $getSalarySheetEmployeeList = $this->db->GetAll($selectSalarySheetEmployeeList);
                $existSalarySheetArray = array();
                foreach ($getSalarySheetEmployeeList as $k => $v) {
                    $existSalarySheetArray[$v['EMPLOYEE_KEY_ID']] = $v['ID'];
                }
                $names = array();
                $str = "";
                foreach ($datas as $k => $value) {
                    // Log той эсэхээ мэдэж байсан string remove хийж байна.
                    $datas[$k] = array_map(function($val){ return str_replace('_logvalue', '', $val); }, $datas[$k]);

                    if ((array_key_exists('SALARY_SHEET_ID', $value) || array_key_exists('salary_sheet_id', $value)) && (array_key_exists('sal_book_id',$value) || array_key_exists('SAL_BOOK_ID',$value))) {

                        if (array_key_exists($datas[$k]['EMPLOYEE_KEY_ID'], $existSalarySheetArray)) {    

                            unset($existSalarySheetArray[$datas[$k]['EMPLOYEE_KEY_ID']]);                                
                            if(isset($datas[$k]['SALARY_SHEET_ID'])) {
                                $salarySheetId = $value['SALARY_SHEET_ID'];
                                unset($datas[$k]['SALARY_SHEET_ID']);
                            } else {
                                $salarySheetId = $value['salary_sheet_id'];
                                unset($datas[$k]['salary_sheet_id']);
                            }
                            $depId = $datas[$k]['DEPARTMENT_ID'];
                            $empKeyId = $datas[$k]['EMPLOYEE_KEY_ID'];
                            unset($datas[$k]['EMPLOYEE_KEY_ID']);
                            unset($datas[$k]['EMPLOYEE_CODE']);
                            unset($datas[$k]['DEPARTMENT_ID']);
                            unset($datas[$k]['LAST_NAME']);
                            unset($datas[$k]['FIRST_NAME']);
                            $response = $this->db->AutoExecute("PRL_SALARY_SHEET", $datas[$k], "UPDATE", 'ID = ' . $salarySheetId);

                            // Create Sheet Log Table
                            if($response && !empty($sheetLogDatas)) {
                                self::createSheetLogTable($sheetLogDatas, $k, $salarySheetId, $calcId, $calcTypeId, $depId, $empKeyId);
                            }                                
                        }
                    } elseif(array_key_exists('selectedDepId', $value)){

                        $selectSalaryBook = "SELECT ID FROM PRL_SALARY_BOOK WHERE DEPARTMENT_ID = ".$datas[$k]['selectedDepId']." AND CALC_ID = $calcId AND CALC_TYPE_ID = $calcTypeId";
                        $getSalaryBook = $this->db->GetRow($selectSalaryBook);

                        if($getSalaryBook){
                            $depId = $datas[$k]['DEPARTMENT_ID'];
                            $empKeyId = $datas[$k]['EMPLOYEE_KEY_ID'];
                            $employeeKeyId = $value['EMPLOYEE_KEY_ID'];
                            unset($datas[$k]['EMPLOYEE_KEY_ID']);
                            unset($datas[$k]['EMPLOYEE_CODE']);
                            unset($datas[$k]['DEPARTMENT_ID']);
                            unset($datas[$k]['LAST_NAME']);
                            unset($datas[$k]['FIRST_NAME']);
                            $salarySheetData = array(
                                'ID' => getUID(),
                                'EMPLOYEE_KEY_ID' => $employeeKeyId,
                                'SAL_BOOK_ID' => $getSalaryBook['ID'],
                                'CALC_TYPE_ID' => $calcTypeId,
                                'CREATED_USER_ID' => Ue::sessionUserKeyId(),
                                'CREATED_DATE' => Date::currentDate()
                            );
                            $salarySheetRows = array_merge($salarySheetData, $datas[$k]);
                            $response = $this->db->AutoExecute("PRL_SALARY_SHEET", $salarySheetRows, "INSERT");

                            // Create Sheet Log Table
                            if($response && !empty($sheetLogDatas)) {
                                self::createSheetLogTable($sheetLogDatas, $k, $salarySheetData['ID'], $calcId, $calcTypeId, $depId, $empKeyId);
                            }                                    
                        }
                    } else {
                        $str .= "insert block entered ";
                        $check = "SELECT ID FROM PRL_SALARY_BOOK WHERE DEPARTMENT_ID = ".$datas[$k]['DEPARTMENT_ID']." AND CALC_ID = $calcId AND CALC_TYPE_ID = $calcTypeId";
                        $salaryBookCheck = $this->db->GetRow($check);

                        if (empty($salaryBookCheck)) {
                            $str .= " b1 ";
                            if(in_array($datas[$k]['DEPARTMENT_ID'],$departmentId)){
                                $str .= "new insert ".print_r($departmentIds,1).$check;
                                $getUniqId = getUID();
                                $salaryBookData = array(
                                    'ID' => $getUniqId,
                                    'DEPARTMENT_ID' => $datas[$k]['DEPARTMENT_ID'],
                                    'CALC_ID' => $calcId,
                                    'CALC_TYPE_ID' => $calcTypeId,
                                    'CREATED_USER_ID' => Ue::sessionUserId(),
                                    'CREATED_DATE' => Date::currentDate()
                                );
                                $response = $this->db->AutoExecute("PRL_SALARY_BOOK", $salaryBookData);
                                if ($response) {
                                    $depId = $datas[$k]['DEPARTMENT_ID'];
                                    $employeeKeyId = $datas[$k]['EMPLOYEE_KEY_ID'];
                                    unset($datas[$k]['EMPLOYEE_KEY_ID']);
                                    unset($datas[$k]['EMPLOYEE_CODE']);
                                    unset($datas[$k]['DEPARTMENT_ID']);
                                    unset($datas[$k]['LAST_NAME']);
                                    unset($datas[$k]['FIRST_NAME']);
                                    $salarySheetData = array(
                                        'ID' => $salaryBookData['ID'],
                                        'EMPLOYEE_KEY_ID' => $employeeKeyId,
                                        'SAL_BOOK_ID' => $salaryBookData['ID'],
                                        'CALC_TYPE_ID' => $calcTypeId,
                                        'CREATED_USER_ID' => Ue::sessionUserKeyId(),
                                        'CREATED_DATE' => Date::currentDate()
                                    );
                                    $salarySheetRows = array_merge($salarySheetData, $datas[$k]);
                                    $response = $this->db->AutoExecute("PRL_SALARY_SHEET", $salarySheetRows); 

                                    // Create Sheet Log Table
                                    if($response && !empty($sheetLogDatas)) {
                                        self::createSheetLogTable($sheetLogDatas, $k, $salaryBookData['ID'], $calcId, $calcTypeId, $depId, $employeeKeyId);
                                    }                                             
                                }
                            }else{
                                $checkSheet = "SELECT EMPLOYEE_KEY_ID FROM PRL_SALARY_SHEET WHERE SAL_BOOK_ID = $salaryBookId AND EMPLOYEE_KEY_ID = ".$datas[$k]['EMPLOYEE_KEY_ID'];
                                $salarySheetCheck = $this->db->GetRow($checkSheet);

                                $depId = $datas[$k]['DEPARTMENT_ID'];
                                $employeeKeyId = $value['EMPLOYEE_KEY_ID'];
                                unset($datas[$k]['EMPLOYEE_KEY_ID']);
                                unset($datas[$k]['EMPLOYEE_CODE']);
                                unset($datas[$k]['DEPARTMENT_ID']);
                                unset($datas[$k]['LAST_NAME']);
                                unset($datas[$k]['FIRST_NAME']);
                                $salarySheetData = array(
                                    'ID' => getUID(),
                                    'EMPLOYEE_KEY_ID' => $employeeKeyId,
                                    'SAL_BOOK_ID' => $salaryBookId,
                                    'CALC_TYPE_ID' => $calcTypeId,
                                    'CREATED_USER_ID' => Ue::sessionUserKeyId(),
                                    'CREATED_DATE' => Date::currentDate()
                                );
                                $salarySheetRows = array_merge($salarySheetData, $datas[$k]);
                                $response = $this->db->AutoExecute("PRL_SALARY_SHEET", $salarySheetRows, "INSERT");

                                // Create Sheet Log Table
                                if($response && !empty($sheetLogDatas)) {
                                    self::createSheetLogTable($sheetLogDatas, $k, $salarySheetData['ID'], $calcId, $calcTypeId, $depId, $employeeKeyId);
                                }                                       
                            }
                        } else {
                            $salBook = $salaryBookCheck['ID'];
                            $checkSheet = "SELECT EMPLOYEE_KEY_ID FROM PRL_SALARY_SHEET WHERE SAL_BOOK_ID = $salBook AND EMPLOYEE_KEY_ID = ".$datas[$k]['EMPLOYEE_KEY_ID'];
                            $salarySheetCheck = $this->db->GetRow($checkSheet);

                            if(empty($salarySheetCheck)){
                                if(in_array($salBook,$bookIdArray) && count($bookIdArray)==1){
                                    $str .= " b2-1 ";
                                    $depId = $datas[$k]['DEPARTMENT_ID'];
                                    $employeeKeyId = $datas[$k]['EMPLOYEE_KEY_ID'];
                                    unset($datas[$k]['EMPLOYEE_KEY_ID']);
                                    unset($datas[$k]['EMPLOYEE_CODE']);
                                    unset($datas[$k]['DEPARTMENT_ID']);
                                    unset($datas[$k]['LAST_NAME']);
                                    unset($datas[$k]['FIRST_NAME']);

                                    $salarySheetData = array(
                                        'ID' => getUID(),
                                        'EMPLOYEE_KEY_ID' => $employeeKeyId,
                                        'SAL_BOOK_ID' => $salBook,
                                        'CALC_TYPE_ID' => $calcTypeId,
                                        'CREATED_USER_ID' => Ue::sessionUserKeyId(),
                                        'CREATED_DATE' => Date::currentDate()
                                    );
                                    $salarySheetRows = array_merge($salarySheetData, $datas[$k]);
                                    $response = $this->db->AutoExecute("PRL_SALARY_SHEET", $salarySheetRows);

                                    // Create Sheet Log Table
                                    if($response && !empty($sheetLogDatas)) {
                                        self::createSheetLogTable($sheetLogDatas, $k, $salarySheetData['ID'], $calcId, $calcTypeId, $depId, $employeeKeyId);
                                    }                                          

                                } elseif(count($bookIdArray)==1){
                                    $str .= " b2-2 ";
                                    $depId = $datas[$k]['DEPARTMENT_ID'];
                                    $employeeKeyId = $datas[$k]['EMPLOYEE_KEY_ID'];
                                    unset($datas[$k]['EMPLOYEE_KEY_ID']);
                                    unset($datas[$k]['EMPLOYEE_CODE']);
                                    unset($datas[$k]['DEPARTMENT_ID']);
                                    unset($datas[$k]['LAST_NAME']);
                                    unset($datas[$k]['FIRST_NAME']);

                                    $salarySheetData = array(
                                        'ID' => getUID(),
                                        'EMPLOYEE_KEY_ID' => $employeeKeyId,
                                        'SAL_BOOK_ID' => $bookIdArray[0],
                                        'CALC_TYPE_ID' => $calcTypeId,
                                        'CREATED_USER_ID' => Ue::sessionUserKeyId(),
                                        'CREATED_DATE' => Date::currentDate()
                                    );
                                    $salarySheetRows = array_merge($salarySheetData, $datas[$k]);
                                    $response = $this->db->AutoExecute("PRL_SALARY_SHEET", $salarySheetRows);

                                    // Create Sheet Log Table
                                    if($response && !empty($sheetLogDatas)) {
                                        self::createSheetLogTable($sheetLogDatas, $k, $salarySheetData['ID'], $calcId, $calcTypeId, $depId, $employeeKeyId);
                                    }     

                                }elseif(count($bookIdArray)>1){
                                    if(array_key_exists('DEPARTMENT_ID', $value) && array_key_exists('selectedDepId', $value)){
                                        $str .= " b2-3 ";
                                        $selectSalBook = "SELECT ID FROM PRL_SALARY_BOOK WHERE DEPARTMENT_ID = ".$datas[$k]['selectedDepId']." AND CALC_ID = $calcId AND CALC_TYPE_ID = $calcTypeId";
                                        $getSalBook = $this->db->GetRow($selectSalBook);

                                        if($getSalBook){
                                            $depId = $datas[$k]['DEPARTMENT_ID'];
                                            $employeeKeyId = $value['EMPLOYEE_KEY_ID'];
                                             unset($datas[$k]['EMPLOYEE_KEY_ID']);
                                             unset($datas[$k]['EMPLOYEE_CODE']);
                                             unset($datas[$k]['DEPARTMENT_ID']);
                                             unset($datas[$k]['LAST_NAME']);
                                             unset($datas[$k]['FIRST_NAME']);
                                             $salarySheetData = array(
                                                 'ID' => getUID(),
                                                 'EMPLOYEE_KEY_ID' => $employeeKeyId,
                                                 'SAL_BOOK_ID' => $getSalBook['ID'],
                                                 'CALC_TYPE_ID' => $calcTypeId,
                                                 'CREATED_USER_ID' => Ue::sessionUserKeyId(),
                                                 'CREATED_DATE' => Date::currentDate()
                                             );
                                             $salarySheetRows = array_merge($salarySheetData, $datas[$k]);
                                             $response = $this->db->AutoExecute("PRL_SALARY_SHEET", $salarySheetRows, "INSERT");

                                            // Create Sheet Log Table
                                            if($response && !empty($sheetLogDatas)) {
                                                self::createSheetLogTable($sheetLogDatas, $k, $salarySheetData['ID'], $calcId, $calcTypeId, $depId, $employeeKeyId);
                                            }                                                  
                                        }
                                    }
                                }

                            } else {

                                if(array_key_exists('DEPARTMENT_ID', $value) && array_key_exists('selectedDepId', $value)){
                                 $str .= " b3 ";
                                       $selectSalBook = "SELECT ID FROM PRL_SALARY_BOOK WHERE DEPARTMENT_ID = ".$datas[$k]['selectedDepId']." AND CALC_ID = $calcId AND CALC_TYPE_ID = $calcTypeId";
                                       $getSalBook = $this->db->GetRow($selectSalBook);

                                       if($getSalBook){
                                           $depId = $datas[$k]['DEPARTMENT_ID'];
                                           $employeeKeyId = $value['EMPLOYEE_KEY_ID'];
                                            unset($datas[$k]['EMPLOYEE_KEY_ID']);
                                            unset($datas[$k]['EMPLOYEE_CODE']);
                                            unset($datas[$k]['DEPARTMENT_ID']);
                                            unset($datas[$k]['LAST_NAME']);
                                            unset($datas[$k]['FIRST_NAME']);
                                            $salarySheetData = array(
                                                'ID' => getUID(),
                                                'EMPLOYEE_KEY_ID' => $employeeKeyId,
                                                'SAL_BOOK_ID' => $getSalBook['ID'],
                                                'CALC_TYPE_ID' => $calcTypeId,
                                                'CREATED_USER_ID' => Ue::sessionUserKeyId(),
                                                'CREATED_DATE' => Date::currentDate()
                                            );
                                            $salarySheetRows = array_merge($salarySheetData, $datas[$k]);
                                            $response = $this->db->AutoExecute("PRL_SALARY_SHEET", $salarySheetRows, "INSERT");

                                            // Create Sheet Log Table
                                            if($response && !empty($sheetLogDatas)) {
                                                self::createSheetLogTable($sheetLogDatas, $k, $salarySheetData['ID'], $calcId, $calcTypeId, $depId, $employeeKeyId);
                                            }                                                      
                                       }

                                } 
//                                    else {
//                                        
//                                        $str .= " b4 ";
//                                        $employeeKeyId = $value['EMPLOYEE_KEY_ID'];
//                                        unset($datas[$k]['EMPLOYEE_KEY_ID']);
//                                        unset($datas[$k]['EMPLOYEE_CODE']);
//                                        unset($datas[$k]['DEPARTMENT_ID']);
//                                        unset($datas[$k]['LAST_NAME']);
//                                        unset($datas[$k]['FIRST_NAME']);
//                                        $salarySheetData = array(
//                                            'ID' => getUID(),
//                                            'EMPLOYEE_KEY_ID' => $employeeKeyId,
//                                            'SAL_BOOK_ID' => $salaryBookId,
//                                            'CALC_TYPE_ID' => $calcTypeId,
//                                            'CREATED_USER_ID' => Ue::sessionUserKeyId(),
//                                            'CREATED_DATE' => Date::currentDate()
//                                        );
//                                        $salarySheetRows = array_merge($salarySheetData, $datas[$k]);
//                                        $response = $this->db->AutoExecute("PRL_SALARY_SHEET", $salarySheetRows, "INSERT");
//                                    }
                            }
                        }
                    }
                    array_push($names,$str);
                }

                if (sizeof($existSalarySheetArray) > 0) {
                    foreach ($existSalarySheetArray as $key => $val) {
                        $this->db->Execute("DELETE FROM PRL_SALARY_SHEET WHERE ID = $val");
                    }
                }
                $result = array(
                    'str' => $names,
                    'type' => 'success',
                    'title' => 'Амжилттай',
                    'text' => 'Амжилттай хадгалагдлаа.',
                    'savedSalBookId' => $salaryBookId
                );
                return $result;
            } else if ($type == 'save') {
                $savedSalBookId = array();

                foreach ($departmentId as $k => $depId) {
                    $getUniqId = getUID();
                    array_push($savedSalBookId, $getUniqId);
                    $salaryBookData = array(
                        'ID' => $getUniqId,
                        'DEPARTMENT_ID' => $depId,
                        'CALC_ID' => $calcId,
                        'CALC_TYPE_ID' => $calcTypeId,
                        'CREATED_USER_ID' => Ue::sessionUserId(),
                        'CREATED_DATE' => Date::currentDate(),
                        'IS_LOCKED' => 0
                    );
                    $response = $this->db->AutoExecute("PRL_SALARY_BOOK", $salaryBookData, "INSERT");
                    if ($response) {
                        foreach ($datas as $k => $value) {
                            if (array_key_exists('DEPARTMENT_ID', $value) && !array_key_exists('selectedDepId', $value)) {

                                if($value['DEPARTMENT_ID'] == $depId) {
                                    $employeeKeyId = $value['EMPLOYEE_KEY_ID'];
                                    unset($datas[$k]['EMPLOYEE_KEY_ID']);
                                    unset($datas[$k]['EMPLOYEE_CODE']);
                                    unset($datas[$k]['DEPARTMENT_ID']);
                                    unset($datas[$k]['LAST_NAME']);
                                    unset($datas[$k]['FIRST_NAME']);

                                    $sheetId = getUID();
                                    $salarySheetData = array(
                                        'ID' => $sheetId,
                                        'EMPLOYEE_KEY_ID' => $employeeKeyId,
                                        'SAL_BOOK_ID' => $salaryBookData['ID'],
                                        'CALC_TYPE_ID' => $calcTypeId,
                                        'CREATED_USER_ID' => Ue::sessionUserKeyId(),
                                        'CREATED_DATE' => Date::currentDate()
                                    );
                                    $salarySheetRows = array_merge($salarySheetData, $datas[$k]);
                                    $response = $this->db->AutoExecute("PRL_SALARY_SHEET", $salarySheetRows, "INSERT");

                                    // Create Sheet Log Table
                                    if($response && !empty($sheetLogDatas)) {
                                        self::createSheetLogTable($sheetLogDatas, $k, $sheetId, $calcId, $calcTypeId, $depId, $employeeKeyId);
                                    }
                                }

                            } elseif(array_key_exists('selectedDepId', $value)){
                                if($value['selectedDepId'] == $depId) {

                                    $employeeKeyId = $value['EMPLOYEE_KEY_ID'];
                                    unset($datas[$k]['EMPLOYEE_KEY_ID']);
                                    unset($datas[$k]['EMPLOYEE_CODE']);
                                    unset($datas[$k]['DEPARTMENT_ID']);
                                    unset($datas[$k]['LAST_NAME']);
                                    unset($datas[$k]['FIRST_NAME']);

                                    $sheetId = getUID();
                                    $salarySheetData = array(
                                        'ID' => $sheetId,
                                        'EMPLOYEE_KEY_ID' => $employeeKeyId,
                                        'SAL_BOOK_ID' => $getUniqId,
                                        'CALC_TYPE_ID' => $calcTypeId,
                                        'CREATED_USER_ID' => Ue::sessionUserKeyId(),
                                        'CREATED_DATE' => Date::currentDate()
                                    );
                                    $salarySheetRows = array_merge($salarySheetData, $datas[$k]);
                                    $response = $this->db->AutoExecute("PRL_SALARY_SHEET", $salarySheetRows, "INSERT");

                                    // Create Sheet Log Table
                                    if($response && !empty($sheetLogDatas)) {
                                        self::createSheetLogTable($sheetLogDatas, $k, $sheetId, $calcId, $calcTypeId, $depId, $employeeKeyId);
                                    }                                        
                                }
                            }                                
                        }

                        $result = array(
                            'type' => 'success',
                            'title' => 'Амжилттай',
                            'text' => 'Амжилттай хадгалагдлаа.',
                            'savedSalBookId' => join(',', $savedSalBookId)
                        );
                    } else {
                        $result = array(
                            'type' => 'error',
                            'title' => 'Анхааруулга',
                            'text' => 'Амжилтгүй боллоо.'
                        );
                    }
                }
                return $result;
            }
        }
        return $result;
    }
    public function selectDepartmentModel(){
        $depIds = Input::post("depIds");
        $implode = is_array($depIds) ?  implode(',',$depIds):$depIds;
        $selectDepartments = "SELECT * FROM ORG_DEPARTMENT WHERE DEPARTMENT_ID IN ($implode)";
        $getDepartments = $this->db->GetAll($selectDepartments);
        return $getDepartments;
    }
    public function getSideBarEmployeeInfoModel() {
        $employeeKeyId = Input::post('employeeKeyId');
        $selectEmployeeInfo = "SELECT HE.LAST_NAME || ' ' || HE.FIRST_NAME AS NAME,
        OD.DEPARTMENT_NAME,
        HP.POSITION_NAME
        FROM HRM_EMPLOYEE_KEY HEK
        INNER JOIN HRM_EMPLOYEE HE ON HEK.EMPLOYEE_ID = HE.EMPLOYEE_ID
        INNER JOIN ORG_DEPARTMENT OD ON HEK.DEPARTMENT_ID = OD.DEPARTMENT_ID
        INNER JOIN HRM_POSITION_KEY HPK ON HEK.POSITION_KEY_ID = HPK.POSITION_KEY_ID
        INNER JOIN HRM_POSITION HP ON HPK.POSITION_ID = HP.POSITION_ID
        WHERE HEK.EMPLOYEE_KEY_ID = $employeeKeyId
    ";
        $getEmployeeInfo = $this->db->GetRow($selectEmployeeInfo);
        $data = array(
            'employeeInfo' => $getEmployeeInfo
        );
        return $data;
    }

    public function getEmployeeListModel() {
        $page = Input::postCheck('page') ? Input::post('page') : 1;
        $rows = Input::postCheck('rows') ? Input::post('rows') : 10;
        $offset = ($page - 1) * $rows;
        $is_active_field = Input::post('IS_ACTIVE');
        $is_active = (isset($is_active_field) ? $is_active_field:1);
        $sortField = ' EMPLOYEE_KEY_ID ';
        $sortOrder = 'DESC';
        $where = '';
        if (Input::postCheck('sort') && Input::postCheck('order')) {
            $sortField = Input::post('sort');
            $sortOrder = Input::post('order');
        }

        if (Input::postCheck('DEPARTMENT_ID')) {
            if (Input::isEmpty('DEPARTMENT_ID') == false)
                $where .= " AND OD.DEPARTMENT_ID = " . Security::sanitize($_POST['DEPARTMENT_ID']);
        }
        if (Input::postCheck('EMPLOYEE_CODE')) {
            if (Input::isEmpty('EMPLOYEE_CODE') == false)
                $where .= " AND LOWER(HE.EMPLOYEE_CODE) LIKE LOWER('%" . Security::sanitize($_POST['EMPLOYEE_CODE']) . "%')";
        }
        if (Input::postCheck('EMPLOYEE_LAST_NAME')) {
            if (Input::isEmpty('EMPLOYEE_LAST_NAME') == false)
                $where .= " AND LOWER(HE.LAST_NAME) LIKE LOWER('%" . Security::sanitize($_POST['EMPLOYEE_LAST_NAME']) . "%')";
        }
        if (Input::postCheck('EMPLOYEE_FIRST_NAME')) {
            if (Input::isEmpty('EMPLOYEE_FIRST_NAME') == false)
                $where .= " AND LOWER(HE.FIRST_NAME) LIKE LOWER('%" . Security::sanitize($_POST['EMPLOYEE_FIRST_NAME']) . "%')";
        }
        $existEmployeeKeyList = " AND HEK.EMPLOYEE_KEY_ID NOT IN(" . Input::post('EXIST_EMPLOYEE_KEY_ID') . ")";

        if($is_active == 1) {
                $selectCount = "SELECT
                          COUNT(DISTINCT EMPLOYEE_KEY_ID) AS ROW_COUNT
                          FROM ORG_DEPARTMENT OD
                          INNER JOIN HRM_EMPLOYEE_KEY HEK ON OD.DEPARTMENT_ID = HEK.DEPARTMENT_ID
                          INNER JOIN HRM_EMPLOYEE HE ON HEK.EMPLOYEE_ID = HE.EMPLOYEE_ID
                          WHERE HEK.IS_ACTIVE = $is_active AND HE.IS_ACTIVE = 1
                          $where 
                          $existEmployeeKeyList
                          AND HEK.WORK_END_DATE IS NULL
                          ORDER BY $sortField $sortOrder";
                $selectList = "SELECT
                          HEK.EMPLOYEE_KEY_ID, HE.EMPLOYEE_CODE, HE.LAST_NAME, HE.FIRST_NAME, OD.DEPARTMENT_ID, OD.DEPARTMENT_NAME
                          FROM ORG_DEPARTMENT OD
                          INNER JOIN HRM_EMPLOYEE_KEY HEK ON OD.DEPARTMENT_ID = HEK.DEPARTMENT_ID
                          INNER JOIN HRM_EMPLOYEE HE ON HEK.EMPLOYEE_ID = HE.EMPLOYEE_ID
                          WHERE HEK.IS_ACTIVE = $is_active AND HE.IS_ACTIVE = 1 
                          $where
                          $existEmployeeKeyList  
                          AND HEK.WORK_END_DATE IS NULL
                          ORDER BY $sortField $sortOrder";
        } else {
                $selectCount = "SELECT
                          COUNT(DISTINCT EMPLOYEE_KEY_ID) AS ROW_COUNT
                          FROM ORG_DEPARTMENT OD
                          INNER JOIN HRM_EMPLOYEE_KEY HEK ON OD.DEPARTMENT_ID = HEK.DEPARTMENT_ID
                          INNER JOIN HRM_EMPLOYEE HE ON HEK.EMPLOYEE_ID = HE.EMPLOYEE_ID
                          WHERE HEK.IS_ACTIVE = $is_active
                          $where 
                          $existEmployeeKeyList
                          ORDER BY $sortField $sortOrder";
                $selectList = "SELECT
                          HEK.EMPLOYEE_KEY_ID, HE.EMPLOYEE_CODE, HE.LAST_NAME, HE.FIRST_NAME, OD.DEPARTMENT_ID, OD.DEPARTMENT_NAME
                          FROM ORG_DEPARTMENT OD
                          INNER JOIN HRM_EMPLOYEE_KEY HEK ON OD.DEPARTMENT_ID = HEK.DEPARTMENT_ID
                          INNER JOIN HRM_EMPLOYEE HE ON HEK.EMPLOYEE_ID = HE.EMPLOYEE_ID
                          WHERE HEK.IS_ACTIVE = $is_active 
                          $where
                          $existEmployeeKeyList
                          ORDER BY $sortField $sortOrder";

        }

        $rowCount = $this->db->GetRow($selectCount);
        $result = $items = array();
        $result['query'] = $selectList;
        $result["total"] = $rowCount['ROW_COUNT'];
        $rs = $this->db->SelectLimit($selectList, $rows, $offset);

        foreach ($rs as $row) {
            array_push($items, $row);
        }
        $result["rows"] = $items;
        return $result;
    }

    public function getSelectedEmployeeListModel() {
        $page = Input::postCheck('page') ? Input::post('page') : 1;
        $rows = Input::postCheck('rows') ? Input::post('rows') : 10;
        $offset = ($page - 1) * $rows;
        $sortField = ' EMPLOYEE_KEY_ID ';
        $sortOrder = 'DESC';
        $where = '';
        if (Input::postCheck('sort') && Input::postCheck('order')) {
            $sortField = Input::post('sort');
            $sortOrder = Input::post('order');
        }

        $where .= "HEK.EMPLOYEE_KEY_ID IN (" . Security::sanitize($_POST['SAVED_EMPLOYEE_KEY_ID']) . ")";

        $selectCount = "SELECT
              COUNT(DISTINCT EMPLOYEE_KEY_ID) AS ROW_COUNT
              FROM ORG_DEPARTMENT OD
              INNER JOIN HRM_EMPLOYEE_KEY HEK ON OD.DEPARTMENT_ID = HEK.DEPARTMENT_ID
              INNER JOIN HRM_EMPLOYEE HE ON HEK.EMPLOYEE_ID = HE.EMPLOYEE_ID
              WHERE $where
              ORDER BY $sortField $sortOrder";

        $selectList = "SELECT
              HEK.EMPLOYEE_KEY_ID, HE.EMPLOYEE_CODE, HE.LAST_NAME, HE.FIRST_NAME, OD.DEPARTMENT_ID, OD.DEPARTMENT_NAME
              FROM ORG_DEPARTMENT OD
              INNER JOIN HRM_EMPLOYEE_KEY HEK ON OD.DEPARTMENT_ID = HEK.DEPARTMENT_ID
              INNER JOIN HRM_EMPLOYEE HE ON HEK.EMPLOYEE_ID = HE.EMPLOYEE_ID
              WHERE $where
              ORDER BY $sortField $sortOrder";
        $rowCount = $this->db->GetRow($selectCount);

        $result = $items = array();
        $result["total"] = $rowCount['ROW_COUNT'];
        $result['query'] = $selectList;
        $rs = $this->db->SelectLimit($selectList, $rows, $offset);

        foreach ($rs as $row) {
            array_push($items, $row);
        }
        $result["rows"] = $items;
        return $result;
    }

    public function getSelectedEmployeeRowsModel() {
        $selectList = "SELECT
              HEK.EMPLOYEE_KEY_ID, OD.DEPARTMENT_ID,HE.EMPLOYEE_ID, HE.EMPLOYEE_CODE, HE.LAST_NAME, HE.FIRST_NAME
              FROM ORG_DEPARTMENT OD
              INNER JOIN HRM_EMPLOYEE_KEY HEK ON OD.DEPARTMENT_ID = HEK.DEPARTMENT_ID
              INNER JOIN HRM_EMPLOYEE HE ON HEK.EMPLOYEE_ID = HE.EMPLOYEE_ID
              WHERE HEK.EMPLOYEE_KEY_ID IN (" . Security::sanitize($_POST['SAVED_EMPLOYEE_KEY_ID']) . ")";
        $getEmployeeKeyIds = $this->db->GetAll($selectList);

        if (Input::postCheck('params')) {
            parse_str(Input::post('params'), $params);
            $selectedDepartmentId = $params['departmentId'][0];

            if ($params['calcTypeId'] !== null) {
                $calcTypeId = $params['calcTypeId'];
            }
            $calcId = $params['calcId'];

            $selectCalcTypeDtl = "SELECT
                MD.META_DATA_ID,
                MD.META_DATA_CODE
                FROM PRL_CALC_TYPE PCT
                INNER JOIN PRL_CALC_TYPE_DTL PCTD ON PCT.ID = PCTD.CALC_TYPE_ID
                INNER JOIN META_DATA MD ON PCTD.META_DATA_ID = MD.META_DATA_ID
                WHERE 1=1 AND PCTD.IS_SHOW=1 AND PCT.ID = $calcTypeId
                ORDER BY PCTD.ORDER_NUM";
            $getCalcTypeDtl = $this->db->GetAll($selectCalcTypeDtl);
             //print_r($getCalcTypeDtl);
            $employeeList = array();
            $tempEmployeeList = array();
            $metaDataList = array();
            foreach ($getCalcTypeDtl as $key => $value) {
                $metaDataList[$key]['metaDataId'] = $value['META_DATA_ID'];
            }
            //print_r($metaDataList);
            foreach ($getEmployeeKeyIds as $k => $row) {
                foreach ($getCalcTypeDtl as $key => $value) {
                    $lowerMetaDataCode = strtolower($value['META_DATA_CODE']);
                    $getEmployeeKeyIds[$k][$lowerMetaDataCode] = '0';
                    $getEmployeeKeyIds[$k]['selectedDepId'] = $selectedDepartmentId;
                }
            }
            foreach ($getEmployeeKeyIds as $k => $row) {
                $employeeList[$k]['employeeKeyId'] = $row['EMPLOYEE_KEY_ID'];
                $employeeList[$k]['departmentId'] = $row['DEPARTMENT_ID'];
                $employeeList[$k]['metaDataIdList'] = $metaDataList;
            }

            $param = array(
                'prlCalcId' => $calcId,
                'calcType' => $calcTypeId,
                'employeelist' => $employeeList
            );
            $serviceResult = $this->ws->runResponse(GF_SERVICE_ADDRESS, "prlCalcExecGetProcess", $param);
            if ($serviceResult['status'] === 'success') {
                $result["service_result"] = $serviceResult;
                $serviceResult = $serviceResult['result']['employeelist'];
                foreach ($getEmployeeKeyIds as $k => $row) {
                    foreach ($getCalcTypeDtl as $key => $value) {
                        $tmpMetaDataId = $value['META_DATA_ID'];
                        $lowerMetaDataCode = strtolower($value['META_DATA_CODE']);
                        if (isset($serviceResult[$k]['metadatalist'][$tmpMetaDataId])) {
                            $getEmployeeKeyIds[$k][$lowerMetaDataCode] = $serviceResult[$k]['metadatalist'][$tmpMetaDataId];
                        } else {
                            $getEmployeeKeyIds[$k][$lowerMetaDataCode] = '0';
                        }
                    }
                }
                $result["rows"] = $getEmployeeKeyIds;
                return $result;
            } else {
                $result["rows"] = $getEmployeeKeyIds;
                $result["serviceMessage"] = $serviceResult;
                return $result;
            }
        }
        $result['query'] = $selectList;
        $result["rows"] = $getEmployeeKeyIds;
        return $result;
    }

    public function savedSalaryBookInfoModel($salaryBookId = null) {
        $select = "SELECT PSB.ID,
            OD.DEPARTMENT_ID,
            OD.DEPARTMENT_CODE,
            OD.DEPARTMENT_NAME,
            PSB.CALC_ID,
            PC.CALC_ORDER,
            (PC.YEAR || '-' || PC.MONTH) AS CALC_YEARMONTH,
            PC.CALC_CODE,
            PC.CALC_NAME,
            TO_CHAR(PC.END_DATE, 'YYYY-MM-DD') AS END_DATE,
            TO_CHAR(PC.START_DATE, 'YYYY-MM-DD') AS START_DATE,
            PSB.CALC_TYPE_ID,
            PCT.CALC_TYPE_CODE,
            PCT.CALC_TYPE_NAME,
            PSB.BOOK_NUMBER,
            PSB.EMPLOYEE_ID,
            PSB.CRITERIA_TEMPLATE_ID,
            HE.EMPLOYEE_CODE,
            HE.FIRST_NAME,
            CT.NAME AS CRITERIA_TEMPLATE_NAME 
          FROM PRL_SALARY_BOOK PSB
          INNER JOIN PRL_CALC PC ON PSB.CALC_ID = PC.ID
          INNER JOIN PRL_CALC_TYPE PCT ON PSB.CALC_TYPE_ID = PCT.ID
          INNER JOIN ORG_DEPARTMENT OD ON PSB.DEPARTMENT_ID = OD.DEPARTMENT_ID
          LEFT JOIN META_GROUP_CRITERIA_TEMPLATE CT ON PSB.CRITERIA_TEMPLATE_ID = CT.ID
          LEFT JOIN HRM_EMPLOYEE HE ON HE.EMPLOYEE_ID = PSB.EMPLOYEE_ID
          WHERE PSB.ID = $salaryBookId";
        $result = $this->db->GetRow($select);

        return $result;
    }

    public function savedSalaryBookInfoPackageModel($salaryBookId = null) {
        $select = "SELECT PSB.ID,
            OD.DEPARTMENT_ID,
            OD.DEPARTMENT_CODE,
            OD.DEPARTMENT_NAME,
            PSB.CALC_ID,
            PC.CALC_ORDER,
            (PC.YEAR || '-' || PC.MONTH) AS CALC_YEARMONTH,
            TO_CHAR(PC.END_DATE, 'YYYY-MM-DD') AS END_DATE,
            TO_CHAR(PC.START_DATE, 'YYYY-MM-DD') AS START_DATE,            
            PC.CALC_CODE,
            PC.CALC_NAME,
            PSB.CALC_TYPE_ID,
            PCT.CALC_TYPE_CODE,
            PCT.CALC_TYPE_NAME,
            PSB.BATCH_NUMBER
          FROM PRL_SALARY_BOOK PSB
          INNER JOIN PRL_CALC PC
          ON PSB.CALC_ID = PC.ID
          INNER JOIN PRL_CALC_TYPE PCT
          ON PSB.CALC_TYPE_ID = PCT.ID
          INNER JOIN ORG_DEPARTMENT OD
          ON PSB.DEPARTMENT_ID = OD.DEPARTMENT_ID
          WHERE PSB.ID = $salaryBookId";
        $result = $this->db->GetAll($select);

        if(!empty($result[0]['BATCH_NUMBER'])) {
            $select = "SELECT PSB.ID,
                OD.DEPARTMENT_ID,
                OD.DEPARTMENT_CODE,
                OD.DEPARTMENT_NAME,
                PSB.CALC_ID,
                PC.CALC_ORDER,
                (PC.YEAR || '-' || PC.MONTH) AS CALC_YEARMONTH,
                PC.CALC_CODE,
                PC.CALC_NAME,
                TO_CHAR(PC.END_DATE, 'YYYY-MM-DD') AS END_DATE,
                TO_CHAR(PC.START_DATE, 'YYYY-MM-DD') AS START_DATE,
                PSB.CALC_TYPE_ID,
                PCT.CALC_TYPE_CODE,
                PCT.CALC_TYPE_NAME,
                PSB.BATCH_NUMBER
              FROM PRL_SALARY_BOOK PSB
              INNER JOIN PRL_CALC PC
              ON PSB.CALC_ID = PC.ID
              INNER JOIN PRL_CALC_TYPE PCT
              ON PSB.CALC_TYPE_ID = PCT.ID
              INNER JOIN ORG_DEPARTMENT OD
              ON PSB.DEPARTMENT_ID = OD.DEPARTMENT_ID
              WHERE PSB.BATCH_NUMBER = " . $result[0]['BATCH_NUMBER'];
            $result = $this->db->GetAll($select);
        }

        return $result;
    }

    public function salaryBookInfoModel($salaryBookId = null) {
        $select = "SELECT
                      PSB.ID,                         
                      PSB.DEPARTMENT_ID,
                      PSB.CALC_ID,
                      PSB.CALC_TYPE_ID,
                      PC.YEAR,
                      PC.MONTH,
                      PC.CALC_ORDER,
                      PCT.CALC_TYPE_NAME
                      FROM PRL_SALARY_BOOK PSB
                      LEFT JOIN PRL_CALC PC ON PSB.CALC_ID = PC.ID
                      LEFT JOIN PRL_CALC_TYPE PCT ON PSB.CALC_TYPE_ID = PCT.ID
                      WHERE PSB.ID = $salaryBookId";
        $result = $this->db->GetRow($select);
        return $result;
    }

    public function getFieldExpressionModel() {
        $calcTypeId = '';
        $metaDataId = '';
        $metaDataCode = '';
        if (Input::post('calcTypeId')) {
            $calcTypeId = Input::post('calcTypeId');
        }
        if (Input::post('metaDataCode')) {
            $metaDataCode = Input::post('metaDataCode');
        }
        $getMetaDataId = $this->db->GetRow("SELECT MD.META_DATA_ID, " . $this->db->IfNull("GD.CODE", "MD.META_DATA_NAME") . " AS META_DATA_NAME
                                            FROM META_DATA MD
                                            LEFT JOIN GLOBE_DICTIONARY GD ON GD.CODE = MD.META_DATA_CODE
                                            WHERE LOWER(MD.META_DATA_CODE) = LOWER('$metaDataCode') AND MD.META_DATA_CODE != MD.META_DATA_NAME");
        if ($getMetaDataId) {
            $metaDataId = $getMetaDataId['META_DATA_ID'];
        }

        if($metaDataId != '') {
            $selectExpression = "SELECT EXPRESSION FROM PRL_CALC_TYPE_DTL WHERE CALC_TYPE_ID = $calcTypeId AND META_DATA_ID = $metaDataId";
            $resultExpression = $this->db->GetRow($selectExpression);
        } else
            $resultExpression = false;

        if ($resultExpression) {
            $selectAllField = "SELECT MD.META_DATA_CODE, " . $this->db->IfNull("GD.CODE", "MD.META_DATA_NAME") . " AS META_DATA_NAME
                                FROM PRL_CALC_TYPE_DTL PCTD 
                                INNER JOIN META_DATA MD ON PCTD.META_DATA_ID = MD.META_DATA_ID 
                                LEFT JOIN GLOBE_DICTIONARY GD ON GD.CODE=MD.META_DATA_CODE                                    
                                WHERE CALC_TYPE_ID = $calcTypeId";
            $getAllField = $this->db->GetAll($selectAllField);

            foreach ($getAllField as $key => $value) {
                $resultExpression = str_replace($value['META_DATA_CODE'], Lang::line($value['META_DATA_NAME']), $resultExpression);
            }
            $resultExpression['FIELD'] = Lang::line($getMetaDataId['META_DATA_NAME']);
            return $resultExpression;
        } else {
            $result = array(
                'FIELD' => $getMetaDataId['META_DATA_NAME'],
                'EXPRESSION' => '');
            return $result;
        }
    }
     public function getDeparmentListJtreeDataModel($departmentId = null, $note= false, $notParent = '') {
        $criteriaValue = array(
            array(
                'operator' => 'IS NULL',
                'operand' => ''
            )
        );
        if ($departmentId) {
            $criteriaValue = array(
                array(
                    'operator' => '=',
                    'operand' => $departmentId
                )
            );
        }

        $isParentChildResolve = false;
        $departmentList = array();
        $this->load->model('mdmetadata', 'middleware/models/');
        $getMetaDataId = $this->model->getMetaDataByCodeModel('DepartmentPrl');            

        if ($departmentId) {
            (Array) $param = array(
                'systemMetaGroupId' => $getMetaDataId['META_DATA_ID'],
                'showQuery' => 0, 
                'ignorePermission' => 1, 
                'treeGrid' => 1,
                'paging' => array(
                    'sortColumnNames' => array(
                        'code' => array(
                            'sortType' => 'asc',
                            'dataType' => 'string'
                        )
                    )                
                ),
                'criteria' => array(
                    'parentId' =>  array(
                        array(
                            'operator' => '=',
                            'operand' => $departmentId
                        )
                    )
                )
            );
        } else {
            (Array) $param = array(
                'systemMetaGroupId' => $getMetaDataId['META_DATA_ID'],
                'showQuery' => 0, 
                'ignorePermission' => 1, 
                'treeGrid' => 1,
                'paging' => array(
                    'sortColumnNames' => array(
                        'code' => array(
                            'sortType' => 'asc',
                            'dataType' => 'string'
                        )
                    )                
                ),
                'criteria' => array(
                    'parentId' =>  array(
                        array(
                            'operator' => 'IS NULL',
                            'operand' => ''
                        )
                    )
                )
            );
        }

        //if($notParent !== '') {
        //    unset($param['criteria']['parentId']);
        //}

        if(Input::get('str') && Input::get('str') !== '___') {
            (Array) $param = array(
                'systemMetaGroupId' => $getMetaDataId['META_DATA_ID'],
                'showQuery' => 0, 
                'ignorePermission' => 1, 
                'treeGrid' => 1,
                'paging' => array(
                    'sortColumnNames' => array(
                        'code' => array(
                            'sortType' => 'asc',
                            'dataType' => 'string'
                        )
                    )                
                ),
                'criteria' => array(
                    'departmentname' =>  array(
                        array(
                            'operator' => 'like',
                            'operand' =>'%'.Input::get('str').'%'
                        )
                    )
                )
            );
        }
        $data = $this->ws->runResponse(self::$gfServiceAddress, Mddatamodel::$getDataViewCommand, $param);

        unset($data['result']['paging']);
        unset($data['result']['aggregatecolumns']);

        if ($data['status'] === 'success' && count($data['result']) == 0) {
            $param['criteria'] = array(
                'parentId' => array()                
            );
            if(Input::get('str') && Input::get('str') !== '___') {            
                $param['criteria']['departmentname'] = array(
                    array(
                        'operator' => 'like',
                        'operand' =>'%'.Input::get('str').'%'
                    )
                );                
            }
            $data = $this->ws->runResponse(self::$gfServiceAddress, Mddatamodel::$getDataViewCommand, $param);
            $isParentChildResolve = true;
        }

        if ($data['status'] === 'success' && isset($data['result'])) {
            $result['total'] = (isset($data['result']['paging']) ? $data['result']['paging']['totalcount'] : 0);
            unset($data['result']['paging']);
            unset($data['result']['aggregatecolumns']);

            if ($isParentChildResolve && count($data['result']) > 0) {
                $parentIds = array();
                $childIds = array();

                foreach ($data['result'] as $dk => $dataRow) {
                    if (isset($dataRow['childrecordcount'])) {
                        $data['result'][$dk]['state'] = 'closed';
                        $parentIds[] = $data['result'][$dk]['id'];
                    } else {
                        $data['result'][$dk]['state'] = 'open';
                    }

                    if ($data['result'][$dk]['parentid'] != '') {
                        $childIds[] = array(
                            'k' => $dk, 
                            'parentId' => $data['result'][$dk]['parentid']
                        );
                    }
                }

                if ($parentIds && $childIds) {

                    foreach ($childIds as $childId) {

                        if (in_array($childId['parentId'], $parentIds)) {
                            unset($data['result'][$childId['k']]);
                            $result['total'] = $result['total'] - 1;
                        }
                    }

                    $data['result'] = array_values($data['result']);
                }
            }

            $departmentList = $data['result'];
        }

        $response = array();
        
        if ($departmentList) {
            $depIds = isset($_GET['depIds']) ? Input::get('depIds') : array();
            $pSelected = isset($_GET['pSelected']) ? Input::get('pSelected') : '0';
            $preDepId = '';

            foreach ($departmentList as $row) {
                if($preDepId == $row['departmentid'])
                    continue;

                $response[] = array(
                    'text'     => $row['code'] . ' - ' . (isset($row['depname']) ? $row['depname'] : issetParam($row['departmentname'])),
                    'id'       => $row['departmentid'],
                    'icon'     => 'fa fa-folder text-orange-400',
                    'state'    => array(
                        'selected' => (in_array($row['departmentid'], $depIds) || $pSelected == '1') ? true : false,
                        'loaded'   => true,
                        'disabled' => false,
                        'opened'   => false,
                        'parentid' => $row['parentid'],
                    ),
                    'children' => isset($row['children']) ? $this->childDepartmentData($row['children'], $depIds, $pSelected) : (isset($row['childrecordcount']) ? true : false)
                );
                $preDepId = $row['departmentid'];
            }
        }
        return $response;
    }

    public function childDepartmentData($departmentChildList, $depIds, $pSelected) {
        foreach ($departmentChildList as $row) {
            $responseChild[] = array(
                'text'     => $row['code'] . ' - ' . (isset($row['depname']) ? $row['depname'] : issetParam($row['departmentname'])),
                'id'       => $row['departmentid'],
                'icon'     => 'fa fa-folder text-orange-400',
                'state'    => array(
                    'selected' => (in_array($row['departmentid'], $depIds) || $pSelected == '1') ? true : false,
                    'loaded'   => true,
                    'disabled' => false,
                    'opened'   => false,
                    'parentid' => $row['parentid'],
                ),
                'children' => isset($row['children']) ? $this->childDepartmentData($row['children'], $depIds, $pSelected) : (isset($row['childrecordcount']) ? true : false)
            );
        }      
        return $responseChild;
    }

    public function getSearchDepartmentListModel() {
        $result = $this->db->GetAll("SELECT 
                                        DEPARTMENT_ID,
                                        DEPARTMENT_CODE,
                                        DEPARTMENT_NAME,
                                        PARENT_ID
                                    FROM ORG_DEPARTMENT");
        return $result;
    }

    public function prlCalcTypeDtlByTypeIdModel() {
        $deps = $depStringIds = '';
        parse_str(Input::post('params'), $params);
        $isChild = isset($params['isChild']) ? (($params['isChild'] == 1) ? 1 : 0) : 0;
        
        if(empty($params['salaryBookId']) && !empty($params['departmentId'])) {
            $deps = self::getAllChildDepartmentModel($params['departmentId'], $isChild);
            $result["recursiveDepartment"] = $deps;
        } elseif (!empty ($params['departmentId'])) {
            $deps = explode(',', $params['departmentId']);
            array_walk($deps, function(&$value) {
                $value = array('DEPARTMENTID' => $value);
            });                    
        }       
        
        if (!empty($params['employeeIds'])) {
            $getDepartmentList = $this->db->GetAll("SELECT DEPARTMENT_ID FROM HRM_EMPLOYEE_KEY WHERE EMPLOYEE_KEY_ID IN (" . $params['employeeIds'] . ")");

            foreach ($getDepartmentList as $drow) {
                $depStringIds .= $drow['DEPARTMENT_ID'] . ',';
            }
        } else {        
            foreach ($deps as $drow) {
                $depStringIds .= $drow['DEPARTMENTID'] . ',';
            }
        }
            
        $result = $this->db->GetAll('SELECT MD.META_DATA_CODE, MD.META_DATA_NAME, MG.IS_SHOW, MG.HEADER_NAME AS USER_COLUMN_COLOR, ' . $this->db->IfNull('CTI.META_DATA_ID', "'1'") . ' AS IS_IGNORE
                                FROM PRL_CALC_TYPE_DTL CT 
                                INNER JOIN META_DATA MD ON MD.META_DATA_ID = CT.META_DATA_ID
                                LEFT JOIN META_GROUP_CONFIG_USER MG ON MD.META_DATA_ID = MG.MAIN_META_DATA_ID AND MG.CALC_TYPE_ID = ' . $params['calcTypeId'] . ' 
                                     AND MG.CALC_ID = ' . Input::post('calcId') . ' AND MG.USER_ID = ' . Ue::sessionUserKeyId() . ' 
                                LEFT JOIN PRL_CALC_TYPE_IGNORE CTI ON CTI.DEPARTMENT_ID IN (' . rtrim($depStringIds, ',') . ') 
                                    AND CTI.CALC_TYPE_ID = ' . $params['calcTypeId'] . ' 
                                    AND CTI.META_DATA_ID = CT.META_DATA_ID                                
                                WHERE CT.IS_SHOW = 1 AND '  . $this->db->IfNull('CT.IS_HIDE', "'0'") . ' = 0 AND CT.CALC_TYPE_ID = ' . $params['calcTypeId'] .
                                ' ORDER BY CT.ORDER_NUM');

        $result2 = array();
        foreach ($result as $row) {
            if ($row['IS_IGNORE'] === '1') {
                $row['META_DATA_NAME'] = Lang::lineDefault($row['META_DATA_CODE'], $row['META_DATA_NAME']);
                $result2[] = $row;
            }
        }

        return $result2;
    }

    public function activeEmployeeByDepartment($depId) {
        $selectEmployeeKeyIds = "SELECT
              HEK.EMPLOYEE_KEY_ID, OD.DEPARTMENT_ID,OD.DEPARTMENT_NAME, HE.EMPLOYEE_CODE, HE.LAST_NAME, HE.FIRST_NAME
              FROM ORG_DEPARTMENT OD
              INNER JOIN HRM_EMPLOYEE_KEY HEK ON OD.DEPARTMENT_ID = HEK.DEPARTMENT_ID
              INNER JOIN HRM_EMPLOYEE HE ON HEK.EMPLOYEE_ID = HE.EMPLOYEE_ID
              WHERE HEK.IS_ACTIVE = 1 AND HEK.WORK_END_DATE IS NULL AND OD.DEPARTMENT_ID = $depId
              ORDER BY NLSSORT(HE.FIRST_NAME,'NLS_SORT = generic_m')";
        $getEmployeeKeyIds = $this->db->GetAll($selectEmployeeKeyIds);            

        return $getEmployeeKeyIds;
    }

    public function resetSheetBookIdModel() {
        $getResetBookIds = "SELECT ID, DEPARTMENT_ID FROM PRL_SALARY_BOOK WHERE CALC_ID = 1460947831247 AND CALC_TYPE_ID = 1457582404401 AND DEPARTMENT_ID IN (
                            SELECT DEPARTMENT_ID FROM PRL_SALARY_BOOK WHERE CALC_ID = 1460947831247
                          )";
        $getResetBookIds = $this->db->GetAll($getResetBookIds);

        foreach ($getResetBookIds as $bookIds) {
            $getSheetByBookId = $this->db->GetAll("SELECT AA.ID FROM PRL_SALARY_SHEET AA
                                                    WHERE AA.SAL_BOOK_ID = " . $bookIds['ID']);   

            if(!empty($getSheetByBookId)) {
                foreach ($getResetBookIds as $bookIdsChild) {
                    $getSheetIds = $this->db->GetAll("SELECT AA.ID FROM PRL_SALARY_SHEET AA
                                                        INNER JOIN HRM_EMPLOYEE_KEY BB ON BB.EMPLOYEE_KEY_ID = AA.EMPLOYEE_KEY_ID
                                                        WHERE AA.SAL_BOOK_ID = " . $bookIds['ID'] . " AND BB.DEPARTMENT_ID = " . $bookIdsChild['DEPARTMENT_ID']);   

                    if(!empty($getSheetIds)) {
                        $getRow = $this->db->GetRow("SELECT ID, DEPARTMENT_ID FROM PRL_SALARY_BOOK WHERE CALC_ID = 1460947831247 AND CALC_TYPE_ID = 1457582404401 AND DEPARTMENT_ID = " . $bookIdsChild['DEPARTMENT_ID']);
//                            if($bookIdsChild['DEPARTMENT_ID'] == '1458563132641') {
//                                var_dump($bookIds['ID']);
//                                var_dump($getRow);
//                                print_r($getSheetIds); die;
//                            }
                        if($getRow) {
                            foreach ($getSheetIds as $sheetId) {
                                $this->db->AutoExecute("PRL_SALARY_SHEET", array('SAL_BOOK_ID' => $getRow['ID']), "UPDATE", 'ID = ' . $sheetId['ID']);
                            }            
                        }
                    }
                }
            }

        }

        die('END');
    }

    public function createSheetLogTable($sheetLogDatas, $k, $sheetId, $calcId, $calcTypeId, $depId, $empKeyId) {
        if(isset($sheetLogDatas[$k])) {
            $arrayKeys = array_keys($sheetLogDatas[$k]);

            foreach ($arrayKeys as $keyVal) {
                if(strpos($keyVal, '_getprocessvalue') !== false) {
                    $sheetLogData = array(
                        'ID' => getUID(),
                        'SHEET_ID' => $sheetId,
                        'VALUE' => $sheetLogDatas[$k][$keyVal],
                        'META_DATA_CODE' => str_replace('_getprocessvalue', '', $keyVal),
                        'CALC_ID' => $calcId,
                        'DEPARTMENT_ID' => $depId,
                        'EMPLOYEE_KEY_ID' => $empKeyId,
                        'CALC_TYPE_ID' => $calcTypeId,
                        'CREATED_DATE' => Date::currentDate(),
                        'CREATED_USER_ID' => null,
                        'IS_SYSTEM' => '1',
                    );
                } else {                    
                    $sheetLogData = array(
                        'ID' => getUID(),
                        'SHEET_ID' => $sheetId,
                        'META_DATA_CODE' => $keyVal,
                        'CALC_ID' => $calcId,
                        'DEPARTMENT_ID' => $depId,
                        'EMPLOYEE_KEY_ID' => $empKeyId,
                        'CALC_TYPE_ID' => $calcTypeId,
                        'VALUE' => $sheetLogDatas[$k][$keyVal],
                        'CREATED_DATE' => Date::currentDate(),
                        'CREATED_USER_ID' => Ue::sessionUserKeyId(),
                        'IS_SYSTEM' => '0',
                    );
                }
                $this->db->AutoExecute("PRL_SALARY_SHEET_LOG", $sheetLogData, "INSERT");                                              
            }
        }
    }

    public function getSheetLogListModel() {
        parse_str(Input::post('formData'), $parsedformData);
        $field = Input::post('field');
        $empKeyId = Input::post('empKeyId');
        $departmentId = Input::post('empDepId');
        $logResult = array();

        $depInfo = $this->db->GetRow("SELECT DEPARTMENT_NAME FROM ORG_DEPARTMENT WHERE DEPARTMENT_ID = " . $departmentId);

        $query = "SELECT
                    ROUND(SL.VALUE, 2) AS VALUE,
                    SL.CREATED_DATE,
                    SL.CREATED_USER_ID,
                    " . $this->db->IfNull('US.USERNAME', "'---'") . " AS USERNAME
                FROM PRL_SALARY_SHEET_LOG SL
                LEFT JOIN UM_USER UM ON UM.USER_ID = SL.CREATED_USER_ID
                LEFT JOIN UM_SYSTEM_USER US ON US.USER_ID = UM.SYSTEM_USER_ID
                WHERE SL.CALC_ID = " . $parsedformData['calcId'] . " AND SL.CALC_TYPE_ID = " . $parsedformData['calcTypeId'] .
                    " AND LOWER(SL.META_DATA_CODE) = '" . strtolower($field) . "' AND SL.EMPLOYEE_KEY_ID = " . $empKeyId . " AND SL.DEPARTMENT_ID = " . $departmentId .
                " ORDER BY SL.CREATED_DATE";
        $result = $this->db->GetAll($query);

        $logResult['depName'] = $depInfo['DEPARTMENT_NAME'];
        $logResult['sheetLogs'] = $result;

        return $logResult;
    }        

    public function setSalaryColumnOrderModel() {
        $result = array(
            'status' => 'warning',
            'message' => 'Алдаа гарлаа',
        );

        $datas = $_POST['SALARY_CONFIG_ORDER'];
        $calcTypeId = Security::sanitize($_POST['calcTypeId']);
        $sheetIndex = Input::post('dataIndex');
        
        $this->db->Execute("DELETE FROM META_GROUP_CONFIG_USER WHERE USER_ID = " . Ue::sessionUserKeyId() . " AND CALC_TYPE_ID = $calcTypeId AND CALC_ID = " . Input::post('calcId'));

        foreach ($datas as $key => $row) {
            $getMetaDataId = self::getMetaDataByCodeByPrlTypeDtlModel($_POST['SALARY_CONFIG_ORDER_METACODE'][$key], $calcTypeId);
            $typeDtlData = array(
                'ORDER_NUM' => Security::sanitize($row)
            );
            $response = $this->db->AutoExecute('PRL_CALC_TYPE_DTL', $typeDtlData, 'UPDATE', 'CALC_TYPE_ID = ' . $calcTypeId . ' AND META_DATA_ID = ' . $getMetaDataId['META_DATA_ID']);

            if ($_POST['IS_HIDE_USER_COL'][$key] === '1' || $_POST['columnColorSet'][$key]) {
                $insertData = array(
                    'ID' => getUID(),
                    'MAIN_META_DATA_ID' => $getMetaDataId['META_DATA_ID'],
                    'CALC_TYPE_ID' => $calcTypeId,
                    'CALC_ID' => Input::post('calcId'),
                    'IS_SHOW' => $_POST['IS_HIDE_USER_COL'][$key] ? $_POST['IS_HIDE_USER_COL'][$key] : null,
                    'USER_ID' => Ue::sessionUserKeyId(),
                    'PARAM_NAME' => $_POST['SALARY_CONFIG_ORDER_METACODE'][$key],
                    'HEADER_NAME' => $_POST['columnColorSet'][$key]
                );
                $this->db->AutoExecute("META_GROUP_CONFIG_USER", $insertData);            
            }
        }
        if ($response) {
            $deserializeJson = json_decode($_POST['sheetData'], true);
            $resCache = self::saveCacheSalarySheetWebserviceModel($deserializeJson, $sheetIndex);
            $resCache = is_null($resCache) ? '' : ' [ ' . $resCache['text'] . ' ]';

            $result = array(
                'status' => 'success',
                'message' => 'Амжилттай өөрчлөгдлөө' . $resCache
            );
        }            
        return $result;
    }        

    public function getMetaDataByCodeByPrlTypeDtlModel($code, $calcTypeId) {
        return $this->db->GetRow(
                "SELECT MD.META_DATA_ID, MD.META_DATA_CODE, MD.META_DATA_NAME FROM META_DATA MD
                 INNER JOIN PRL_CALC_TYPE_DTL PT ON PT.META_DATA_ID = MD.META_DATA_ID
                 WHERE PT.CALC_TYPE_ID = $calcTypeId AND LOWER(MD.META_DATA_CODE) = '" . Str::lower($code) . "'"
            );
    }

    public function isExistSalary($depId, $calcId, $calcTypeId) {

        if (is_array($depId)) {
            $depId = implode(',', $depId);
        }

        $selectSalBook = "SELECT ID, BATCH_NUMBER FROM PRL_SALARY_BOOK WHERE DEPARTMENT_ID IN ($depId) AND CALC_ID = $calcId AND CALC_TYPE_ID = $calcTypeId";
        $getSalBook = $this->db->GetRow($selectSalBook);

        if($getSalBook) {
            return $getSalBook;
        } else
            return false;
    }

    public function getAllBookByDepCalcType($depId, $calcId, $calcTypeId, $bookSavedNumber) {

        if (is_array($depId)) {
            $depId = implode(',', $depId);
        }

        if ($bookSavedNumber) 
            $selectSalBook = "SELECT ID, BATCH_NUMBER, BOOK_NUMBER FROM PRL_SALARY_BOOK WHERE DEPARTMENT_ID IN ($depId) AND CALC_ID = $calcId AND CALC_TYPE_ID = $calcTypeId AND BOOK_NUMBER = $bookSavedNumber";
        else
            $selectSalBook = "SELECT ID, BATCH_NUMBER, BOOK_NUMBER FROM PRL_SALARY_BOOK WHERE DEPARTMENT_ID IN ($depId) AND CALC_ID = $calcId AND CALC_TYPE_ID = $calcTypeId AND EMPLOYEE_ID IS NULL";

        $getSalBook = $this->db->GetAll($selectSalBook);

        if ($getSalBook) {
            return $getSalBook;
        } else
            return false;
    }

    public function getAllBookByEmpCalcType($empId, $calcId, $calcTypeId, $bookSavedNumber) {

        if (is_array($empId)) {
            $empId = implode(',', $empId);
        }

        if ($bookSavedNumber) 
            $selectSalBook = "SELECT ID, BATCH_NUMBER, BOOK_NUMBER FROM PRL_SALARY_BOOK WHERE EMPLOYEE_KEY_ID IN ($empId) AND CALC_ID = $calcId AND CALC_TYPE_ID = $calcTypeId AND BOOK_NUMBER = $bookSavedNumber";
        else
            $selectSalBook = "SELECT ID, BATCH_NUMBER, BOOK_NUMBER FROM PRL_SALARY_BOOK WHERE EMPLOYEE_KEY_ID IN ($empId) AND CALC_ID = $calcId AND CALC_TYPE_ID = $calcTypeId";

        $getSalBook = $this->db->GetAll($selectSalBook);

        if ($getSalBook) {
            return $getSalBook;
        } else
            return false;
    }

    public function getAllBookByBatchNumber($batch) {
        $selectSalBook = "SELECT ID, BATCH_NUMBER, BOOK_NUMBER FROM PRL_SALARY_BOOK WHERE BATCH_NUMBER = $batch";
        $getSalBook = $this->db->GetAll($selectSalBook);

        if ($getSalBook) {
            return $getSalBook;
        } else
            return false;
    }

    public function salaryDataImportLoadDataCustomFieldModel($file, $params) {
        
        set_time_limit(0);
        ini_set('memory_limit', '-1');
        
        require_once BASEPATH . LIBS . 'Office/Excel/PHPExcel/IOFactory.php';
        require_once BASEPATH . LIBS . 'Office/Excel/PHPExcel.php';

        $allowedExts  = array("xls", "xlsx");
        $fileName     = $file['name'];
        $fileType     = $file['type'];
        $size         = $file['size'];
        $error        = $file['error'];
        $explodeArray = explode(".", $fileName);
        $extension = end($explodeArray);

        if (!(($size < 204800000) && in_array($extension, $allowedExts))) {
            return array("text" => "Файлын төрөл тохирохгүй эсвэл хэмжээ 200mb аас хэтэрсэн байна.", 'status' => 'warning');
        } elseif ($error > 0) {
            return array("text" => "Error!!! " . $error, 'status' => 'warning');
        }
        
        $result = $this->getCalcFieldListImportExcelModel(false); 
        $headerData = $result['fields'];        

        try {
            $objPHPExcel = PHPExcel_IOFactory::load($file['tmp_name']);

            $sheetData = $objPHPExcel->getAllSheets();
            $sheetData = $sheetData[0]->toArray();
            
            $headerGroup = Arr::groupByArray($headerData, 'META_DATA_CODE');
            foreach ($sheetData[0] as $key => $row) {
                if (array_key_exists($row, $headerGroup)) {
                    $sheetData[0][$key] = $row.'$$$1';
                } else {
                    $sheetData[0][$key] = $row.'$$$0';
                }
            }

            return array(
                'allData' => $sheetData
            );

        } catch (Exception $e) {
            return array("text" => $e->getMessage(), 'status' => 'warning');
        }
    }        

    public function salaryDataImportLoadDataModel() {
        
        set_time_limit(0);
        ini_set('memory_limit', '-1');
        
        $excelAllDatas = json_decode(urldecode($_POST['excelAllDatas']), true);
        $configCustomExcel = $_POST['SALARY_CONFIG_EXCEL_IMPORT'];
        $configCustomExcel = array_merge($configCustomExcel, array(0, 1, 2));

        $sheetData = $excelAllDatas;
        $sheetHeaderData = $sheetData[0];
        $fGlobe = array();

        foreach($sheetHeaderData as $kk => $rrow) {
            $sheetHeaderData[$kk] = explode('$$$', $rrow)[0];
        }

        foreach($sheetHeaderData as $kk => $rrow) {
            $fGlobe[$rrow] = $sheetData[1][$kk];
        }

        unset($sheetData[0]);
        unset($sheetData[1]);

        $sheetData = array_values($sheetData);
        $keySheetData = array();
        $icount = 0;

        foreach($sheetData as $fkey => $row) {
            foreach ($row as $ckey => $childRow) {
                if(in_array($ckey, $configCustomExcel)) {
                    $keySheetData[$icount][$sheetHeaderData[$ckey]] = $childRow;                        
                    $keySheetData[$icount][$sheetHeaderData[$ckey].'_globetext'] = $fGlobe[$sheetHeaderData[$ckey]];                        
                }
            }
            $icount++;
        }

        $inparams = array(
            'processCacheId' => Input::post('javaCacheId'),
            'excelDatas' => $keySheetData,
            'isMerge' => true
        );
        $updateExcelData = array();
        
        $params['metaCode'] = 'addEmployeeFromExcelTemplate';
        $params['calcTypeId'] = Input::post('calcTypeId');
        $params['calcId'] = Input::post('calcId');
        $params['isChild'] = Input::post('isChild');
        $params['departmentId'] = Input::post('departmentId');                    
        $params['message'] = 'Импорт хийсэн нийт өгөгдөл';
        $params['jsonString'] = Arr::encode(json_encode($keySheetData));
        $this->insertActionLog($params);
        
        $importResult = $this->ws->runResponse(GF_SERVICE_ADDRESS, "addEmployeeFromExcelTemplate", $inparams);
        
        if ($importResult['status'] === 'error') {
            return array('text' => 'Анхааруулга 13: ' . $importResult['text'], 'status' => 'error');
        }

        if(empty($importResult['result']['duplicatedemployees']) && empty($importResult['result']['notfoundemployees']) && !isset($importResult['result']['invaliddataemployees'])) {
            
            return array('text' => 'Амжилттай импорт хийлээ', 'status' => 'success');
            
        } 
        
        $responseArr = array('status' => 'error', 'text' => 'Импорт хийхэд алдаа гарлаа.');
        if (is_array($importResult['result']['notfoundemployees'])) {
            $responseArr['notfoundemployees'] = $importResult['result']['notfoundemployees'];
            $responseArr['notfoundemployees_encode'] = Arr::encode($importResult['result']['notfoundemployees']);
            
            $params['jsonString'] = Arr::encode(json_encode($importResult['result']['notfoundemployees']));
            $params['message'] = 'Олдоогүй ажилтан';
            $this->insertActionLog($params);            
        }
        
        if (is_array($importResult['result']['duplicatedemployees'])) {
            $responseArr['duplicatedemployees'] = $importResult['result']['duplicatedemployees'];
            $responseArr['duplicatedemployees_encode'] = Arr::encode($importResult['result']['duplicatedemployees']);
            
            $params['jsonString'] = Arr::encode(json_encode($importResult['result']['duplicatedemployees']));
            $params['message'] = 'Давхардсан ажилтан';
            $this->insertActionLog($params);                     
        }
        
        if (is_array($importResult['result']['invaliddataemployees'])) {
            $responseArr['invaliddataemployees'] = $importResult['result']['invaliddataemployees'];
            $responseArr['invaliddataemployees_encode'] = Arr::encode($importResult['result']['invaliddataemployees']);
            
            $params['jsonString'] = Arr::encode(json_encode($importResult['result']['invaliddataemployees']));
            $params['message'] = 'Систем дээр үүсээгүй';
            $this->insertActionLog($params);                 
        }
        
        return $responseArr;
    }        

    public function validateExpressionModel($value) {

        $value = trim($value);      
        $param = array('value' => $value);

        $result = $this->ws->runResponse(self::$gfServiceAddress, 'check_expression', $param);    

        if ($result['status'] == 'success') {
            $response = array('status' => 'success', 'message' => 'Success', 'expression' => $value);
        } else {
            $response = array('status' => 'warning', 'message' => $this->ws->getResponseMessage($result));
        }

        return $response;
    }

    public function getMetaFieldTypeByCodeModel($code) {
        return $this->db->GetOne("
            SELECT 
                MT.DATA_TYPE
            FROM META_DATA MD 
                INNER JOIN META_FIELD_LINK MT ON MT.META_DATA_ID = MD.META_DATA_ID 
            WHERE LOWER(MD.META_DATA_CODE) = '" . Str::lower($code) . "'");
    }

    public function getProcessRunListModel() {
        $result["fields"] = array();

        if (Input::postCheck('params')) {

            parse_str(Input::post('params'), $params);
            $calcTypeId = $params['calcTypeId'];
            $deps = $depStringIds = '';
            $isChild = isset($params['isChild']) ? (($params['isChild'] == 1) ? 1 : 0) : 0;

            if(empty($params['salaryBookId']) && !empty($params['departmentId'])) {
                $deps = self::getAllChildDepartmentModel($params['departmentId'], $isChild);
                $result["recursiveDepartment"] = $deps;
            } elseif (!empty ($params['departmentId'])) {
                $deps = explode(',', $params['departmentId']);
                array_walk($deps, function(&$value) {
                    $value = array('DEPARTMENTID' => $value);
                });                    
            }       
            
            if (!empty($params['employeeIds'])) {
                $getDepartmentList = $this->db->GetAll("SELECT DEPARTMENT_ID FROM HRM_EMPLOYEE_KEY WHERE EMPLOYEE_KEY_ID IN (" . $params['employeeIds'] . ")");

                foreach ($getDepartmentList as $drow) {
                    $depStringIds .= $drow['DEPARTMENT_ID'] . ',';
                }
            } else {                    
                foreach ($deps as $drow) {
                    $depStringIds .= $drow['DEPARTMENTID'] . ',';
                }            
            }            

            if(!empty($calcTypeId)) {
                $selectCalcTypeDtl = "SELECT
                    MD.META_DATA_ID,
                    PCTD.GET_PROCESS_ID AS META_DATA_CODE,
                    MD.META_DATA_NAME,
                    MFL.DATA_TYPE,
                    PCTD.ORDER_NUM,
                    PCTD.IS_HIDE,
                    PCTD.IS_FREEZE,
                    PCTD.IS_DISABLE,
                    PCTD.IS_SIDEBAR,
                    PCTD.LABEL_NAME,
                    PCTD.CALL_PROCESS_ID,
                    PCTD.IS_WHOLE,
                    PCTD.LINK_META_DATA_ID, 
                    PCTD.EXPRESSION, 
                    PCTD.COLUMN_SIZE, 
                    MD.META_DATA_CODE AS FIELD_CODE,
                    " . $this->db->IfNull("GD.CODE", "MD.META_DATA_CODE") . " AS GLOBE_CODE,
                    " . $this->db->IfNull('CTI.META_DATA_ID', "'1'") . " AS IS_IGNORE
                    FROM PRL_CALC_TYPE PCT
                    INNER JOIN PRL_CALC_TYPE_DTL PCTD ON PCT.ID = PCTD.CALC_TYPE_ID
                    INNER JOIN META_DATA MD ON PCTD.META_DATA_ID = MD.META_DATA_ID
                    INNER JOIN META_FIELD_LINK MFL ON MD.META_DATA_ID = MFL.META_DATA_ID
                    LEFT JOIN GLOBE_DICTIONARY GD ON GD.CODE=MD.META_DATA_CODE
                    LEFT JOIN PRL_CALC_TYPE_IGNORE CTI ON CTI.DEPARTMENT_ID IN (" . rtrim($depStringIds, ',') . ") 
                        AND CTI.CALC_TYPE_ID = " . $calcTypeId . " 
                        AND CTI.META_DATA_ID = PCTD.META_DATA_ID                        
                    WHERE PCTD.GET_PROCESS_ID IS NOT NULL AND PCTD.IS_SHOW=1 AND "  . $this->db->IfNull('PCTD.IS_HIDE', "'0'") . " = 0 AND PCT.ID = $calcTypeId
                    ORDER BY PCTD.ORDER_NUM";
                $getCalcTypeDtl = $this->db->GetAll($selectCalcTypeDtl);

                if($getCalcTypeDtl) {
                    $result2 = array();
                    foreach ($getCalcTypeDtl as $row) {
                        if ($row['IS_IGNORE'] === '1') {
                            $row['META_DATA_NAME'] = Lang::line($row['GLOBE_CODE']);
                            $result2[] = $row;
                        }
                    }  
                
                    $result = array(
                        'status' => 'success',
                        'getRows' => $result2,
                        'title' => '',
                        'text' => ''
                    );
                } else
                    $result = array(
                        'status' => 'warning',
                        'title' => 'Анхааруулга',
                        'text' => 'GET PROCESS тохируулаагүй загвар байна!'
                    );

            } else {
                $result = array(
                    'status' => 'warning',
                    'title' => 'Анхааруулга',
                    'text' => 'GET PROCESS тохируулаагүй загвар байна!'
                );
            }
        } else {
            $result = array(
                'status' => 'warning',
                'title' => 'Анхааруулга',
                'text' => 'GET PROCESS тохируулаагүй загвар байна!'
            );
        }
        return $result;                
    }        

    public function getProcessRunModel() {
        parse_str(Input::post('formData'), $params2);
        parse_str(Input::post('params'), $params);
        $params['metaCode'] = 'runGetProcessOnly';               
        $employeeNames = '';

        $inparams = array(
            'processCacheId' => Input::post('javaCacheId'),
            'getRunCommands' => $params2['getProccesRunCode']
        );
        
        if (Input::isEmpty('selectedEmployees') === false) {
            $pushEmployee = array();
            $employees = Input::post('selectedEmployees');
            
            foreach ($employees as $rowEmp) {
                array_push($pushEmployee, array(
                    'employeeId' => $rowEmp['employeeid'],
                    'employeeKeyId' => $rowEmp['employeekeyid'],
                    'departmentId' => $rowEmp['departmentid'],
                ));
                $employeeNames .= $rowEmp['employeecode'] . ' ' . $rowEmp['lastname'] . ' ' . $rowEmp['firstname'] . '<br>';
            }
            
            $inparams['employeeKeyIds'] = $pushEmployee;
        }
        
        $employeeNames .= '[ ';
        foreach ($params2['fieldCode'] as $fval) {
            $employeeNames .= $fval . ' ';
        }
        $employeeNames .= ']';
        
        $calculateSheet = $this->ws->runResponse(GF_SERVICE_ADDRESS, 'runGetProcessOnly', $inparams);

        if ($calculateSheet['status'] === 'error') {
            $params['message'] = 'GET PROCESS ажиллуулахад алдаа гарлаа: ' . $calculateSheet['text'] . '<br>' . $employeeNames;
            $this->insertActionLog($params);            
            return array('text' => 'GET PROCESS ажиллуулахад алдаа гарлаа: ' . $calculateSheet['text'], 'status' => 'error');
        }

        $offset = 1;
        $pageSize = 10000;
        $inparams = array(
            'processCacheId' => Input::post('javaCacheId'),
            'groupPath' => 'dtl',
            'paging' => array(
                'offset' => $offset,
                'pageSize' => $pageSize
            )
        );

        $getSalaryList = $this->ws->runSerializeResponse(GF_SERVICE_ADDRESS, 'get_list_from_cache', $inparams);

        if ($getSalaryList['status'] === 'error') {
            $params['jsonString'] = 'Анхааруулга 04: ' . $getSalaryList['text'];
        } else { 
            unset($getSalaryList['result']['paging']);          
            unset($getSalaryList['result']['aggregatecolumns']);    
            
            $setDataIndex = 0;
            $this->setIndex($getSalaryList['result'], $setDataIndex);
            $logJsonString = array(
                'rows' => json_encode($getSalaryList['result']),
                'footer' => json_encode(Input::post('datagridFooters')),
                'frozenColumn' => json_encode(Input::post('datagridFrozenColumns')),
                'column' => json_encode(Input::post('datagridColumns')),
            );
            $params['jsonString'] = Arr::encode(json_encode($logJsonString));
        }        
        $params['message'] = $employeeNames;
        $this->insertActionLog($params);
        
        return array(
            'status' => 'success',
            'text' => 'Амжилттай шинэчлэгдлээ'
        );
    }

    public function updateKeyEmployeeWebserviceModel() {
        parse_str(Input::post('params'), $params);
        $params['metaCode'] = 'replaceEmployeeKeysSalary';        
        $result = $this->ws->runResponse(GF_SERVICE_ADDRESS, 'replaceEmployeeKeysSalary', Input::postData());         

        if ($result['status'] === 'error') {
            $params['message'] = 'Анхааруулга 08: ' . $result['text'];
            $this->insertActionLog($params);            
            return array('text' => 'Анхааруулга 08: ' . $result['text'], 'status' => 'error');
        }
        $this->insertActionLog($params);
    } 
    
    public function getCalcTypeMetaListByIdModel($calcTypeId) {
        
        $data = $this->db->GetAll("
            SELECT 
                MD.META_DATA_CODE 
            FROM PRL_CALC_TYPE_DTL DTL 
                INNER JOIN META_DATA MD ON MD.META_DATA_ID = DTL.META_DATA_ID
            WHERE DTL.CALC_TYPE_ID = $calcTypeId 
            ORDER BY MD.META_DATA_CODE ASC");
        
        return $data;
    }
    
    public function insertActionLog($params) {
        
        try {
            
            $depsString = '';
            $isChild = isset($params['isChild']) ? (($params['isChild'] == 1) ? 1 : 0) : 0;
            $isMessageClobUpdate = false;

            if (isset($params['departmentId']) && !empty($params['departmentId'])) {
                $deps = self::getAllChildDepartmentModel($params['departmentId'], $isChild);
                foreach ($deps as $row) {
                    $depsString .= $row['DEPARTMENTID'] . ',';
                }
            } elseif (isset($params['employeeIds']) && !empty($params['employeeIds'])) {
                $depsString = $params['employeeIds'];
            }

            $logData = array(
                'ID' => getUID(),
                'PROCESS_META_DATA_CODE' => $params['metaCode'],
                'CALC_TYPE_ID' => $params['calcTypeId'],
                'CALC_ID' => $params['calcId'],
                'CREATED_USER_ID' => Ue::sessionUserKeyId(),
                'CREATED_DATE' => Date::currentDate()
            );

            if (isset($params['message']) && !empty($params['message'])) {
                if (mb_strlen($params['message']) > 4000) {
                    $isMessageClobUpdate = true;
                } else {
                    $logData['MESSAGE_DESCRIPTION'] = $params['message'];
                }
            }

            $this->db->AutoExecute('PRL_SALARY_ACTION_LOG', $logData);         

            if (isset($params['jsonString']) && !empty($params['jsonString'])) {
                $this->db->UpdateClob('PRL_SALARY_ACTION_LOG', 'JSON_STRING', $params['jsonString'], 'ID = '.$logData['ID']);
            }
            if ($isMessageClobUpdate) {
                $this->db->UpdateClob('PRL_SALARY_ACTION_LOG', 'MESSAGE_DESCRIPTION', $params['message'], 'ID = '.$logData['ID']);
            }
            if ($depsString) {
                $this->db->UpdateClob('PRL_SALARY_ACTION_LOG', 'DEPARTMENT_ID', rtrim($depsString, ','), 'ID = '.$logData['ID']);
            }
            
            return true;
        
        } catch (Exception $ex) {
            return false;
        }
    }
    
    public function getSalaryAllSheetWebserviceModel() {
        $offset = 1;
        $pageSize = 10000;
        $inparams = array(
            'processCacheId' => Input::post('javaCacheId'),
            'groupPath' => 'dtl',
            'paging' => array(
                'offset' => $offset,
                'pageSize' => $pageSize
            )
        );

        $getSalaryList = $this->ws->runSerializeResponse(GF_SERVICE_ADDRESS, 'get_list_from_cache', $inparams);

        if ($getSalaryList['status'] === 'error') {
            return $response = array('text' => 'Анхааруулга 04: ' . $getSalaryList['text'], 'status' => 'error', 'rows' => array());
        } 

        unset($getSalaryList['result']['paging']);          
        unset($getSalaryList['result']['aggregatecolumns']);    
        
        $listResult = $getSalaryList['result'];
        
        $fields = Input::post('fieldPath');
        $groupedParent = array();
        
        foreach ($fields as $frow) {
            $groupedArr = Arr::groupByArray($listResult, $frow['fieldCode']);
            $groupedResult = array();
            foreach ($groupedArr as $key => $row) {
                if (!empty($key)) {

                    $empKeys = '';
                    foreach ($row['rows'] as $ek) {
                        $empKeys .= $ek['employeekeyid'] . ',';
                    }

                    array_push($groupedResult, array(
                        'fieldValue' => Number::formatMoney($key),
                        'fieldCount' => Number::formatMoney(count($row['rows'])),
                        'empKeys' => rtrim($empKeys, ',')
                    ));
                }
            }
            
            if (count($groupedResult)) {
                array_push($groupedParent, array(
                    'fieldName' => $frow['fieldName'],
                    'fieldCode' => $frow['fieldCode'],
                    'calcDtlId' => $frow['calcDtlId'],
                    'rows' => $groupedResult
                ));
            }
        }

        return $response = array(
            'status' => 'success',
            'rows' => $groupedParent
        );
    }

    public function getActionLogModel($id) {
        $qry = "SELECT ID, JSON_STRING, LOWER(PROCESS_META_DATA_CODE) AS PROCESS_META_DATA_CODE
                FROM 
                PRL_SALARY_ACTION_LOG WHERE ID = $id";
        
        return $this->db->GetRow($qry);
    }

    public function getCalcTypeDtlCardModel($calcTypeId) {
        
        $data = $this->db->GetAll("
            SELECT 
                LOWER(MD.META_DATA_CODE) AS META_DATA_CODE 
            FROM PRL_CALC_TYPE_DTL_CARD DTL 
                INNER JOIN META_DATA MD ON MD.META_DATA_ID = DTL.META_DATA_ID
            WHERE DTL.CALC_TYPE_DTL_ID = $calcTypeId 
            ORDER BY DTL.ORDER_NUMBER ASC");

        $result = array();
        foreach ($data as $row) {
            $result[$row['META_DATA_CODE']] = $row['META_DATA_CODE'];
        }
        
        return $result;
    }    

    public function everyRequestSalaryModel() {
        $qry = "SELECT ID, MESSAGE 
                FROM NTF_NOTIFY 
                WHERE USER_ID = " . Ue::sessionUserKeyId() . " AND UPPER(CODE)=UPPER('PRL')";
        
        return $this->db->GetAll($qry);
    }    

    public function deleteEveryRequestSalaryModel() {
        $this->db->Execute("DELETE FROM NTF_NOTIFY WHERE USER_ID = " . Ue::sessionUserKeyId() . " AND UPPER(CODE)=UPPER('PRL')");
    }         
    
    public function lookUpCalcTypeModel($code) {
        $dm = &getInstance();
        $dm->load->model('mdmetadata', 'middleware/models/');

        $getMetaDataId = $dm->model->getMetaDataByCodeModel($code);            
        $metaDataId = $getMetaDataId['META_DATA_ID'];

        (Array) $param = array(
            'systemMetaGroupId' => $metaDataId,
            'showQuery' => 0, 
            'ignorePermission' => 1
        );

        $data = $this->ws->runResponse(self::$gfServiceAddress, Mddatamodel::$getDataViewCommand, $param);

        if ($data['status'] === 'success' && isset($data['result'])) {
            unset($data['result']['paging']);
            unset($data['result']['aggregatecolumns']);            

            return $data['result'];
        } else {
            return array();
        }

    }    
    
    public function getSuggestionCalcRowModel() {
        
        $inparams = array(
            'date1' => Date::currentDate('Y-m-d')
        );
        if (Input::postCheck('calcTypeId')) {
            $inparams['calcTypeId'] = Input::post('calcTypeId');
        }

        $getResult = $this->ws->runResponse(GF_SERVICE_ADDRESS, "prlCalcDVFilter_004", $inparams);
        
        if ($getResult['status'] !== 'error') {
            $getResult = $getResult['result'];
        } else {
            $getResult = array();
        }
        
        return $getResult;
    }        
    
    public function dataviewSavedCriteria1Model() {
        (Array) $param = array(
            'systemMetaGroupId' => '1573011644276429',
            'showQuery' => 0, 
        );

        $data = $this->ws->runResponse(self::$gfServiceAddress, Mddatamodel::$getDataViewCommand, $param);

        if ($data['status'] === 'success' && isset($data['result'])) {
            unset($data['result']['paging']);
            unset($data['result']['aggregatecolumns']);            

            return $data['result'];
        } else {
            return array();
        }
    }    
    
    public function dataviewSavedCriteriaModel() {
        (Array) $param = array(
            'systemMetaGroupId' => '1573011644276429',
            'showQuery' => 0, 
            'criteria' => array(
                'booktypeid' =>  array(
                    array(
                        'operator' => '=',
                        'operand' => Input::post('bookTypeId')
                    )
                ),
                'calctypeid' =>  array(
                    array(
                        'operator' => '=',
                        'operand' => Input::post('calcTypeId')
                    )
                )
            )            
        );

        $data = $this->ws->runResponse(self::$gfServiceAddress, Mddatamodel::$getDataViewCommand, $param);

        if ($data['status'] === 'success' && isset($data['result'])) {
            unset($data['result']['paging']);
            unset($data['result']['aggregatecolumns']);            

            return $data['result'];
        } else {
            return array();
        }
    }    

    public function whereDepertmentIds ($depStringIds, $columnName) {
        $whereDepartmentId = '';
        $expDep = explode(',', rtrim($depStringIds, ','));
        $idsSplit = array_chunk($expDep, 500); 
                        
        foreach ($idsSplit as $idsArr) {
            $whereDepartmentId .= " $columnName IN (" . implode(',', $idsArr) . ") OR";
        }        
        $whereDepartmentId = rtrim($whereDepartmentId, ' OR');

        return $whereDepartmentId;
    }

}