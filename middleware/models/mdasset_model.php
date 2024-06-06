<?php

if (!defined('_VALID_PHP'))
    exit('Direct access to this location is not allowed.');

class Mdasset_Model extends Model {

    private $glServiceAddress = GF_SERVICE_ADDRESS;
    private static $gfServiceAddress = GF_SERVICE_ADDRESS;
    private static $getDataViewCommand = 'PL_MDVIEW_004';
    private $AutoNumberWebservice = 'AutoNumberWebservice/AutoNumberWebservice';

    public function __construct() {
        parent::__construct();
    }

    public function generateBookNumber() {
        $param = array(
            'objectId' => '40002',
            'dtos' => array(
                'key' => '#BKT#',
                'value' => '100'
            )
        );
        $result = $this->ws->soapCallListAddr($this->glServiceAddress, $this->AutoNumberWebservice, 'getCode', 'return', $param);

        if ($result != null) {
            return array('return' => 'code');
        } else {
            return array('return' => 'code');
        }
    }

    public function createAssetBookModel() {

        $generalLedgers = array();

        if (Input::postCheck('gl_accountId')) {
            $generalLedgersData = Input::post('gl_accountId');

            foreach ($generalLedgersData as $k => $accountId) {

                if ($accountId != '') {

                    $currencyCode = strtolower(Input::param($_POST['gl_rate_currency'][$k]));

                    if ($currencyCode == 'mnt') {
                        $rate = 1;
                        $creditAmount = Number::decimal(Input::param($_POST['gl_creditAmount'][$k]));
                        $creditAmountBase = $creditAmount;
                        $debitAmount = Number::decimal(Input::param($_POST['gl_debitAmount'][$k]));
                        $debitAmountBase = $debitAmount;
                    } else {
                        $rate = Number::decimal(Input::param($_POST['gl_rate'][$k]));
                        $creditAmount = Number::decimal(Input::param($_POST['gl_creditAmount'][$k]));
                        $creditAmountBase = Number::decimal(Input::param($_POST['gl_creditAmountBase'][$k]));
                        $debitAmount = Number::decimal(Input::param($_POST['gl_debitAmount'][$k]));
                        $debitAmountBase = Number::decimal(Input::param($_POST['gl_debitAmountBase'][$k]));
                    }

                    $generalLedgers[$k] = array(
                        'subid' => Input::param($_POST['gl_subid'][$k]),
                        'accountId' => $accountId,
                        'description' => Input::param($_POST['gl_rowdescription'][$k]),
                        'rate' => $rate,
                        'creditAmount' => $creditAmount,
                        'creditAmountBase' => $creditAmountBase,
                        'debitAmount' => $debitAmount,
                        'debitAmountBase' => $debitAmountBase,
                        'customerId' => Input::param($_POST['gl_customerId'][$k])
                    );

                    if (isset($_POST['gl_invoiceBookId'][$k])) {

                        $accountBook = array();
                        $accbooks = Input::param($_POST['gl_invoiceBookId'][$k]);

                        if ($accbooks != '') {
                            $books = explode(', ', $accbooks);
                            foreach ($books as $key => $value) {
                                if (!empty($value)) {
                                    $accountBook[$key] = array(
                                        'invoiceId' => trim($value),
                                        'objectId' => Input::param($_POST['gl_objectId'][$k])
                                    );
                                }
                            }
                            $generalLedgers[$k]['generalLedgerMaps'] = $accountBook;
                        }
                    }

                    if (isset($_POST['defaultInvoiceBook'][$k]) && $_POST['defaultInvoiceBook'][$k] != '') {
                        $defaultInvoices = json_decode($_POST['defaultInvoiceBook'][$k], true);
                        $generalLedgers[$k]['invoicebook'] = array_key_exists(0, $defaultInvoices) ? $defaultInvoices : array($defaultInvoices);
                    }

                    if (isset($_POST['gl_metas'][$k]) && $_POST['gl_metas'][$k] != '') {
                        $glMetas = json_decode(html_entity_decode($_POST['gl_metas'][$k], ENT_QUOTES, 'UTF-8'), true);
                        if (is_array($glMetas)) {
                            $generalLedgers[$k] = array_merge($generalLedgers[$k], $glMetas);
                        }
                    }

                    if (isset($_POST['accountMeta'][$k][$accountId])) {
                        $accountMetaDatas = Input::param($_POST['accountMeta'][$k][$accountId]);
                        $accountSegmentShortCode = $accountSegmentName = '';

                        foreach ($accountMetaDatas as $metaKey => $metaValue) {
                            if (strpos($metaKey, '_segmentCode') === false && strpos($metaKey, '_segmentSeparator') === false && strpos($metaKey, '_segmentReplaceValue') === false) {

                                $generalLedgers[$k][$metaKey] = $metaValue;

                                if (array_key_exists($metaKey . '_segmentCode', $accountMetaDatas)) {

                                    $segCode = $accountMetaDatas[$metaKey . '_segmentCode'];
                                    $segSeparator = $accountMetaDatas[$metaKey . '_segmentSeparator'];

                                    if ($segCode) {
                                        $metaValueExp = explode('|', $accountMetaDatas[$metaKey . '_segmentCode']);
                                        $segmentCode = $metaValueExp[0];
                                        $segmentName = $metaValueExp[1];
                                        $generalLedgers[$k][$metaKey . '_segmentCode'] = $segmentCode;
                                        $generalLedgers[$k][$metaKey . '_segmentName'] = $segmentName;
                                        $accountSegmentName .= $segSeparator . $segmentName;
                                    } else {
                                        $segmentCode = $accountMetaDatas[$metaKey . '_segmentReplaceValue'];
                                        $segmentName = $segmentCode;
                                        $generalLedgers[$k][$metaKey . '_segmentCode'] = '';
                                        $generalLedgers[$k][$metaKey . '_segmentName'] = '';
                                    }

                                    $accountSegmentShortCode .= $segSeparator . $segmentCode;
                                }
                            }
                        }

                        if ($accountSegmentShortCode) {
                            $generalLedgers[$k]['accountsegmentshortcode'] = $accountSegmentShortCode;
                        }

                        if ($accountSegmentName) {
                            $generalLedgers[$k]['accountsegmentname'] = $accountSegmentName;
                        }
                    }
                }
            }
        }

        $generalLedgerParams = array(
            'id' => 'vid1',
            'bookDate' => Input::post('bookDate'),
            'bookTypeId' => Mdasset::$faAssetDeprBookTypeId,
            'objectId' => Mdasset::$faAssetDeprObjectId,
            'assetKeeperKeyId' => Input::post('cashierKeeperId'),
            'calcStandardAmt' => Input::post('calcstandardamt'),
            'deprMethod' => Input::post('calcMethod'),
            'deprValue' => Input::post('depMonth'),
            'description' => Input::post('description'),
            'cacheLockId' => Input::post('uniqId'),
            'generalLedgerBookParams' => array(
                'bookTypeId' => Input::postCheck('glBookTypeId') ? Input::post('glBookTypeId') : '2',
                'bookDate' => Input::postCheck('glbookDate') ? Input::post('glbookDate') : Input::post('hidden_glbookDate'),
                'bookNumber' => Input::postCheck('glbookNumber') ? Input::post('glbookNumber') : Input::post('hidden_glbookNumber'),
                'objectId' => (Input::isEmpty('hidden_globject') == false) ? Input::post('hidden_globject') : '20000',
                'description' => Input::postCheck('gldescription') ? Input::post('gldescription') : Input::post('hidden_gldescription'),
                'generalLedgerBookDtls' => $generalLedgers
            )
        );
        
        if (Config::getFromCache('IS_NOT_USE_GL_ASSET')) {
            unset($generalLedgerParams['generalLedgerBookParams']);
        }

        self::modifyCacheRows();

        $result = $this->ws->runSerializeResponse(GF_SERVICE_ADDRESS, 'fad_save', $generalLedgerParams);

        if ($result['status'] == 'success') {
            $response = array('status' => 'success', 'message' => Lang::line('msg_save_success'));
        } else {
            $response = array('status' => 'error', 'message' => $this->ws->getResponseMessage($result));
        }

        return $response;
    }

    public function getDepreciationAssetsCacheModel() {

        $this->load->model('mdobject', 'middleware/models/');

        $result = array();

        $page = Input::postCheck('page') ? Input::post('page') : 1;
        $rows = Input::postCheck('rows') ? Input::post('rows') : 10;

        $metaDataId = Input::numeric('metaDataId');

        $param = array(
            'systemMetaGroupId' => $metaDataId,
            'showQuery' => 1,
            'ignorePermission' => 1,
            'deprMethod' => Input::post('deprmethod'),
            'deprValue' => Input::post('deprvalue'),
            'calcStandardAmt' => Input::post('calcstandardamt'),
            'cacheLockId' => Input::post('uniqId'),
            'paging' => array(
                'offset' => $page,
                'pageSize' => $rows
            )
        );

        if (Input::postCheck('sort') && Input::postCheck('order')) {
            $sortField = Input::post('sort');
            $sortOrder = Input::post('order');

            if (strpos($sortField, ',') === false) {
                $param['paging']['sortColumnNames'] = array(
                    $sortField => array(
                        'sortType' => $sortOrder
                    )
                );
            } else {
                $sortFieldArr = explode(',', $sortField);
                $sortOrderArr = explode(',', $sortOrder);
                foreach ($sortFieldArr as $sortK => $sortF) {
                    $sortColumnNames[$sortF] = array('sortType' => $sortOrderArr[$sortK]);
                }
                $param['paging']['sortColumnNames'] = $sortColumnNames;
            }
        }

        if (Input::postCheck('sortFields')) {
            parse_str(Input::post('sortFields'), $sortFields);
            if (count($sortFields) > 0) {
                foreach ($sortFields as $sortKey => $sortType) {
                    $param['paging']['sortColumnNames'] = array(
                        $sortKey => array(
                            'sortType' => $sortType
                        )
                    );
                }
            }
        }

        if (Input::postCheck('defaultCriteriaData')) {

            parse_str(Input::post('defaultCriteriaData'), $defaultCriteriaData);

            if (isset($defaultCriteriaData['param'])) {
                $defaultCriteriaParam = $defaultCriteriaData['param'];

                if (isset($defaultCriteriaData['criteriaCondition'])) {
                    $defaultCriteriaCondition = $defaultCriteriaData['criteriaCondition'];
                    $defaultCondition = '1';
                } else {
                    $defaultCriteriaCondition = 'LIKE';
                    $defaultCondition = '0';
                }

                $paramDefaultCriteria = array();

                foreach ($defaultCriteriaParam as $defParam => $defParamVal) {

                    if (is_array($defParamVal)) {

                        foreach ($defParamVal as $x => $paramVal) {

                            if ($paramVal != '') {

                                $op = '=';

                                if (strpos($paramVal, ',') !== false) {
                                    $op = 'IN';
                                }

                                $paramDefaultCriteria[$defParam][] = array(
                                    'operator' => $op,
                                    'operand' => (strtolower($op) == 'like') ? '%' . $paramVal . '%' : $paramVal
                                );
                            }
                        }
                    } else {

                        $defParamVal = Input::param(trim($defParamVal));
                        $defParamVal = Mdmetadata::setDefaultValue($defParamVal);
                        $mandatoryCriteria = isset($defaultCriteriaData['mandatoryCriteria'][$defParam]) ? '1' : '0';

                        if ($defParamVal != '' || $mandatoryCriteria == '1') {

                            $operator = ($defaultCondition === '0') ? $defaultCriteriaCondition : (isset($defaultCriteriaCondition[$defParam]) ? $defaultCriteriaCondition[$defParam] : '=');
                            $defParamValue = (strtolower($operator) == 'like') ? '%' . $defParamVal . '%' : $defParamVal;

                            $getTypeCode = $this->model->getDataViewGridCriteriaRowModel($metaDataId, $defParam);
                            $getTypeCodeLower = strtolower($getTypeCode['META_TYPE_CODE']);

                            if ($getTypeCodeLower == 'date' || $getTypeCodeLower == 'datetime') {

                                $defParamVal = str_replace(
                                        array('____-__-__', '___-__-__', '__-__-__', '_-__-__', '-__-__', '-__', '_', '__:__', ':__'), '', $defParamVal
                                );

                                $operator = ($defaultCondition === '0') ? '=' : (isset($defaultCriteriaCondition[$defParam]) ? $defaultCriteriaCondition[$defParam] : '=');
                                $defParamValue = $defParamVal;
                            } elseif ($getTypeCodeLower == 'long' || $getTypeCodeLower == 'integer' || $getTypeCodeLower == 'number') {

                                $defParamVal = Number::decimal($defParamVal);

                                $operator = ($defaultCondition === '0') ? '=' : (isset($defaultCriteriaCondition[$defParam]) ? $defaultCriteriaCondition[$defParam] : '=');
                                $defParamValue = $defParamVal;
                            } elseif ($getTypeCodeLower == 'bigdecimal') {
                                $defParamVal = Number::decimal($defParamVal);
                            }

                            if ($defParam == 'booktypename') {
                                $operator = ($defaultCondition === '0') ? '!=' : (isset($defaultCriteriaCondition[$defParam]) ? $defaultCriteriaCondition[$defParam] : '!=');
                                $defParamValue = $defParamVal;
                            }
                            $paramDefaultCriteria[$defParam][] = array(
                                'operator' => $operator,
                                'operand' => ($defParamValue) ? $defParamValue : '0'
                            );
                        }
                    }
                }

                if (isset($param['criteria'])) {
                    $param['criteria'] = array_merge($param['criteria'], $paramDefaultCriteria);
                } else {
                    $param['criteria'] = $paramDefaultCriteria;
                }
            }
        }

        $data = $this->ws->runSerializeResponse($this->glServiceAddress, 'fad_create_cache', $param);

        if ($data['status'] == 'success' && isset($data['result'])) {

            $result['total'] = (isset($data['result']['paging']) ? $data['result']['paging']['totalcount'] : 0);

            unset($data['result']['paging']);

            if (isset($data['result']['aggregatecolumns']) && $data['result']['aggregatecolumns']) {
                $result['footer'] = $data['result']['aggregatecolumns'];
            }
            unset($data['result']['aggregatecolumns']);

            $result['rows'] = $data['result'];
            $result['status'] = 'success';
        } else {
            $result = array('status' => 'error', 'message' => $this->ws->getResponseMessage($data), 'rows' => array(), 'total' => 0);
        }

        return $result;
    }

    public function getDepreciationAssetsNavigationModel() {

        $this->load->model('mdobject', 'middleware/models/');

        $result = array();

        $page = Input::postCheck('page') ? Input::post('page') : 1;
        $rows = Input::postCheck('rows') ? Input::post('rows') : 10;

        $metaDataId = Input::numeric('metaDataId');

        $param = array(
            'deprMethod' => Input::post('deprmethod'),
            'deprValue' => Input::post('deprvalue'),
            'calcStandardAmt' => Input::post('calcstandardamt'),
            'cacheLockId' => Input::post('uniqId'),
            'systemMetaGroupId' => $metaDataId,
            'showQuery' => 0,
            'ignorePermission' => 1,
            'paging' => array(
                'offset' => $page,
                'pageSize' => $rows
            )
        );

        if (Input::postCheck('sort') && Input::postCheck('order')) {
            $sortField = Input::post('sort');
            $sortOrder = Input::post('order');

            if (strpos($sortField, ',') === false) {
                $param['paging']['sortColumnNames'] = array(
                    $sortField => array(
                        'sortType' => $sortOrder
                    )
                );
            } else {
                $sortFieldArr = explode(',', $sortField);
                $sortOrderArr = explode(',', $sortOrder);
                foreach ($sortFieldArr as $sortK => $sortF) {
                    $sortColumnNames[$sortF] = array('sortType' => $sortOrderArr[$sortK]);
                }
                $param['paging']['sortColumnNames'] = $sortColumnNames;
            }
        }

        if (Input::postCheck('sortFields')) {
            parse_str(Input::post('sortFields'), $sortFields);
            if (count($sortFields) > 0) {
                foreach ($sortFields as $sortKey => $sortType) {
                    $param['paging']['sortColumnNames'] = array(
                        $sortKey => array(
                            'sortType' => $sortType
                        )
                    );
                }
            }
        }

        if (Input::postCheck('filterRules')) {

            $filterRules = json_decode(Str::cp1251_utf8($_POST['filterRules']), true);

            if (is_countable($filterRules) && count($filterRules) > 0) {

                $paramFilter = array();

                foreach ($filterRules as $rule) {

                    $field = $rule['field'];
                    $condition = $rule['op'];
                    $value = Input::param(trim($rule['value']));

                    $getTypeCode = $this->model->getDataViewGridCriteriaRowModel($metaDataId, $field);
                    $getTypeCodeLower = strtolower($getTypeCode['META_TYPE_CODE']);

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
                        'operator' => $condition,
                        'operand' => ($condition == 'like') ? '%' . $value . '%' : $value
                    );
                }

                if (isset($param['criteria'])) {
                    $param['criteria'] = array_merge($param['criteria'], $paramFilter);
                } else {
                    $param['criteria'] = $paramFilter;
                }
            }
        }

        self::modifyCacheRows();

        $data = $this->ws->runSerializeResponse($this->glServiceAddress, 'fad_get_list', $param);

        if ($data['status'] == 'success' && isset($data['result'])) {

            $result['total'] = (isset($data['result']['paging']) ? $data['result']['paging']['totalcount'] : 0);

            unset($data['result']['paging']);

            if (isset($data['result']['aggregatecolumns']) && $data['result']['aggregatecolumns']) {
                $result['footer'] = $data['result']['aggregatecolumns'];
            }
            unset($data['result']['aggregatecolumns']);

            $result['rows'] = $data['result'];
            $result['status'] = 'success';
        } else {
            $result = array('status' => 'error', 'message' => $this->ws->getResponseMessage($data), 'rows' => array(), 'total' => 0);
        }

        return $result;
    }

    public function modifyCacheRows() {

        $paramModify = array();
        $isParamModify = false;

        if (Input::postCheck('rowRemovedChanged') && is_array(Input::post('rowRemovedChanged'))) {

            $rowRemovedChanged = Input::post('rowRemovedChanged');

            foreach ($rowRemovedChanged as $rowRemovedChangedKey => $rowRemovedChangedVal) {
                $paramModify['removed'][] = array('assetKeyId' => $rowRemovedChangedKey);
            }

            $isParamModify = true;
        }

        if (Input::postCheck('rowCheckChanged') && is_array(Input::post('rowCheckChanged'))) {

            $rowCheckChanged = Input::post('rowCheckChanged');

            foreach ($rowCheckChanged as $rowCheckChangedKey => $rowCheckChangedVal) {
                $paramModify['checkchanged'][] = array(
                    'assetKeyId' => $rowCheckChangedKey,
                    'checked' => $rowCheckChangedVal
                );
            }

            $isParamModify = true;
        }

        if (Input::postCheck('rowValueChanged') && is_array($_POST['rowValueChanged'])) {

            $rowValueChanged = $_POST['rowValueChanged'];

            foreach ($rowValueChanged as $rowValueChangedKey => $rowValueChangedVal) {

                if (isset($rowValueChangedVal['inDeprAmt'])) {

                    $paramModify['valuechanged'][] = array(
                        'assetKeyId' => $rowValueChangedVal['id'],
                        'inDeprAmt' => $rowValueChangedVal['inDeprAmt']
                    );
                }

                if (isset($rowValueChangedVal['stInDeprAmt'])) {

                    $paramModify['valuechanged'][] = array(
                        'assetKeyId' => $rowValueChangedVal['id'],
                        'inDeprAmt' => $rowValueChangedVal['stInDeprAmt']
                    );
                }
            }

            $isParamModify = true;
        }

        if ($isParamModify) {

            $paramModify['cacheLockId'] = Input::post('uniqId');

            $this->ws->runSerializeResponse($this->glServiceAddress, 'fad_modify', $paramModify);
        }

        return true;
    }

    public function getAssetsListTreeModel($dataViewId, $lifecycleId = null, $recordId = null, $param = array(), $lifecycletaskId = null) {

        $criteria = $param;

        if ($lifecycletaskId) {
            $criteria = array_merge($criteria, array(
                'lifecycletaskId' => array(
                    array(
                        'operator' => '=',
                        'operand' => $lifecycletaskId
                    ),
                ),
            ));
        }

        $param = array(
            'systemMetaGroupId' => $dataViewId,
            'showQuery' => 1,
            'ignorePermission' => 1,
            'criteria' => $criteria
        );

        $this->load->model('mdobject', 'middleware/models/');
        $dataGridOptionData = $this->model->getDVGridOptionsModel($dataViewId);

        if (isset($dataGridOptionData['SORTNAME']) && $dataGridOptionData['SORTNAME'] != '') {
            $param['paging']['sortColumnNames'] = array(
                $dataGridOptionData['SORTNAME'] => array(
                    'sortType' => $dataGridOptionData['SORTORDER']
                )
            );
        }

        $data = $this->ws->runSerializeResponse(self::$gfServiceAddress, Mddatamodel::$getDataViewCommand, $param);

        $this->load->model('mdasset', 'middleware/models/');

        if ($data['status'] == 'success' && isset($data['result'])) {
            $dataResult = $this->db->GetAll($data['result']);
            $dataReturn = array();
            $dataReturn['result'] = Arr::changeKeyLower($dataResult);

            if (isset($dataReturn['result'][0]['wfmstatusname'])) {
                array_walk($dataReturn['result'], function (&$value) {
                    $value['wfmstatusname'] = $this->lang->line($value['wfmstatusname']);
                });
            }

            return $dataReturn['result'];
        }

        return array();
    }

    public function getConnectionDataModel($assetId, $locationId = null, $checkkeyid = null) {

        $connection = $this->db->GetAll("SELECT
                                            T0.PORT_QTY,
                                            T0.ASSET_ID,
                                            T0.ASSET_PORT_ID,
                                            T0.PORT_TYPE_ID,
                                            T0.START_PORT_NUM,
                                            T1.PORT_TYPE_NAME,
                                            T1.PORT_TYPE_CODE,
                                            T1.IS_ACTIVE,
                                            T1.DESCRIPTION,
                                            T1.ICON
                                        FROM
                                            FA_ASSET_PORT T0
                                            INNER JOIN REF_PORT_TYPE T1 ON T0.PORT_TYPE_ID = T1.ID
                                        WHERE
                                            T0.ASSET_ID = $assetId AND T1.IS_ACTIVE = 1 ORDER BY T0.PORT_TYPE_ID");

        (array) $connectionPort = array();
        (array) $installationPort = array();
        (array) $installation = array();

        if ($locationId && $checkkeyid) {
            $installationPort = $this->db->GetAll("SELECT DISTINCT
                                                      F.CONNECTION_ID,
                                                      F.SRC_LOCATION_ID AS LOCATION_ID,
                                                      F.SRC_RECORD_ID RECORD_ID,
                                                      F.INSTALLATION_ID,
                                                      F.SRC_PORT_ID,
                                                      F.TRG_PORT_ID,
                                                      F.SRC_PORT_TYPE_ID,
                                                      F.TRG_PORT_TYPE_ID,
                                                      F.SRC_PORT,
                                                      F.TRG_PORT,
                                                      F.ORDER_NUM
                                                  FROM
                                                      FA_CONNECTION F
                                                      INNER JOIN FA_INSTALLATION I ON F.INSTALLATION_ID = I.INSTALLATION_ID
                                                      INNER JOIN FA_ASSET_PORT FAP ON F.SRC_PORT_ID = FAP.ASSET_PORT_ID  AND F.SRC_PORT_TYPE_ID = FAP.PORT_TYPE_ID
                                                  WHERE
                                                      F.IS_ACTIVE = 1
                                                      AND   I.IS_ACTIVE = 1
                                                      AND   F.SRC_LOCATION_ID = $locationId
                                                      AND   F.SRC_RECORD_ID = $checkkeyid
                                                  UNION ALL
                                                  SELECT DISTINCT
                                                      F.CONNECTION_ID,
                                                      F.TRG_LOCATION_ID AS LOCATION_ID,
                                                      F.TRG_RECORD_ID RECORD_ID,
                                                      F.INSTALLATION_ID,
                                                      F.SRC_PORT_ID,
                                                      F.TRG_PORT_ID,
                                                      F.SRC_PORT_TYPE_ID,
                                                      F.TRG_PORT_TYPE_ID,
                                                      F.SRC_PORT,
                                                      F.TRG_PORT,
                                                      F.ORDER_NUM
                                                  FROM
                                                      FA_CONNECTION F
                                                      INNER JOIN FA_INSTALLATION I ON F.INSTALLATION_ID = I.INSTALLATION_ID
                                                      INNER JOIN FA_ASSET_PORT FAP ON F.TRG_PORT_ID = FAP.ASSET_PORT_ID AND F.TRG_PORT_TYPE_ID = FAP.PORT_TYPE_ID
                                                  WHERE
                                                      F.IS_ACTIVE = 1
                                                      AND   I.IS_ACTIVE = 1
                                                      AND   F.TRG_LOCATION_ID = $locationId
                                                      AND   F.TRG_RECORD_ID = $checkkeyid ");
        }

        if ($installationPort) {
            (array) $tmp = array();
            foreach ($installationPort as $value) {
                if ($value['INSTALLATION_ID']) {
                    $installationId = $value['INSTALLATION_ID'];
                    $connectionId = $value['CONNECTION_ID'];

                    $value['INSTALLATION_DTL'] = $this->db->GetAll("SELECT
                                                                     I.INSTALLATION_ID,
                                                                     I.INSTALLATION_DATE,
                                                                     I.SOURCE_ADDRESS,
                                                                     I.DESTINATION_ADDRESS,
                                                                     I.CIRCUIT_ID,
                                                                     I.TASK_ID,
                                                                     F.CONNECTION_ID,
                                                                     F.TRG_LOCATION_ID AS LOCATION_ID,
                                                                     F.TRG_PORT_TYPE_ID AS PORT_TYPE_ID,
                                                                     F.TRG_RECORD_ID RECORD_ID,
                                                                     F.TRG_PORT AS PORT,
                                                                     F.SRC_PORT,
                                                                     F.INSTALLATION_ID,
                                                                     F.ORDER_NUM,
                                                                     PT.PORT_TYPE_NAME,
                                                                     F.TRG_PORT_INFO|| ' '|| PT.PORT_TYPE_NAME|| '#' || F.TRG_PORT AS PORT_INFO,
                                                                     F.SRC_PORT_INFO|| ' '|| SR.PORT_TYPE_NAME|| '#' || F.SRC_PORT AS SR_PORT_INFO
                                                                 FROM
                                                                     FA_INSTALLATION I
                                                                     INNER JOIN FA_CONNECTION F ON I.INSTALLATION_ID = F.INSTALLATION_ID
                                                                     LEFT JOIN REF_PORT_TYPE PT ON F.TRG_PORT_TYPE_ID = PT.ID
                                                                     INNER JOIN (
                                                                         SELECT
                                                                             ID,
                                                                             PORT_TYPE_NAME
                                                                         FROM
                                                                             REF_PORT_TYPE
                                                                     ) SR ON F.SRC_PORT_TYPE_ID = SR.ID
                                                                 WHERE
                                                                     I.INSTALLATION_ID = $installationId AND F.CONNECTION_ID = $connectionId
                                                                     AND   I.IS_ACTIVE = 1");
                    array_push($tmp, $value);
                }
                $installation = $tmp;
            }
        }

        return array('connection' => $connection, 'connectionPort' => $connectionPort, 'installation' => $installation);
    }

    public function getConnectionFormDataModel($assetId, $locationId = null, $checkkeyid = null) {
        $connection = $this->db->GetAll("SELECT
                                            T0.PORT_QTY,
                                            T0.ASSET_ID,
                                            T0.ASSET_PORT_ID,
                                            T0.PORT_TYPE_ID,
                                            T0.START_PORT_NUM,
                                            T1.PORT_TYPE_NAME,
                                            T1.PORT_TYPE_CODE,
                                            T1.IS_ACTIVE,
                                            T1.DESCRIPTION,
                                            T1.ICON
                                        FROM
                                            FA_ASSET_PORT T0
                                            INNER JOIN REF_PORT_TYPE T1 ON T0.PORT_TYPE_ID = T1.ID
                                        WHERE
                                            T0.ASSET_ID = $assetId AND T1.IS_ACTIVE = 1 ORDER BY T0.PORT_TYPE_ID");

        return $connection;
    }

    public function getConnectionTypeListModel() {
        return $this->db->GetAll("SELECT * FROM FA_CONNECTION_TYPE");
    }

    public function getRowConnectionDataModel($conId, $isstart) {
        return $this->db->GetRow("SELECT CN.* FROM (
                                                    SELECT
                                                        F.CONNECTION_ID,
                                                        F.CONNECTION_TYPE_ID,
                                                        F.CABLE_ID,
                                                        F.TUBE_COLOR_ID,
                                                        F.CORE_COLOR_ID,
                                                        F.SRC_RECORD_ID,
                                                        F.SRC_PORT,
                                                        F.TRG_RECORD_ID,
                                                        F.TRG_PORT,
                                                        F.CABLE_LENGTH,
                                                        F.DESCRIPTION,
                                                        F.CREATED_DATE,
                                                        F.CREATED_USER_ID,
                                                        F.MODIFIED_DATE,
                                                        F.MODIFIED_USER_ID,
                                                        F.SRC_LOCATION_ID,
                                                        F.TRG_LOCATION_ID,
                                                        F.SRC_PORT_TYPE_ID,
                                                        F.TRG_PORT_TYPE_ID,
                                                        F.IS_ACTIVE,
                                                        F.TRG_PORT_INFO,
                                                        F.SRC_PORT_INFO,
                                                        F.TRG_PORT_INFO || ' ' || PT.PORT_TYPE_NAME || '#' || F.TRG_PORT AS TRG_PORT_INF,
                                                        '1' AS IS_START
                                                    FROM
                                                        FA_CONNECTION F
                                                        INNER JOIN REF_PORT_TYPE PT ON F.TRG_PORT_TYPE_ID = PT.ID
                                                    WHERE
                                                        F.IS_ACTIVE = 1
                                                    UNION ALL
                                                    SELECT
                                                        F.CONNECTION_ID,
                                                        F.CONNECTION_TYPE_ID,
                                                        F.CABLE_ID,
                                                        F.TUBE_COLOR_ID,
                                                        F.CORE_COLOR_ID,
                                                        F.TRG_RECORD_ID AS SRC_RECORD_ID,
                                                        F.TRG_PORT AS SRC_PORT,
                                                        F.SRC_RECORD_ID AS TRG_RECORD_ID,
                                                        F.SRC_PORT AS TRG_PORT,
                                                        F.CABLE_LENGTH,
                                                        F.DESCRIPTION,
                                                        F.CREATED_DATE,
                                                        F.CREATED_USER_ID,
                                                        F.MODIFIED_DATE,
                                                        F.MODIFIED_USER_ID,
                                                        F.TRG_LOCATION_ID AS SRC_LOCATION_ID,
                                                        F.SRC_LOCATION_ID AS TRG_LOCATION_ID,
                                                        F.TRG_PORT_TYPE_ID AS SRC_PORT_TYPE_ID,
                                                        F.SRC_PORT_TYPE_ID AS TRG_PORT_TYPE_ID,
                                                        F.IS_ACTIVE,
                                                        F.SRC_PORT_INFO AS TRG_PORT_INFO,
                                                        F.TRG_PORT_INFO AS SRC_PORT_INFO,
                                                        F.SRC_PORT_INFO|| ' '|| PT.PORT_TYPE_NAME|| '#' || F.SRC_PORT AS TRG_PORT_INF,
                                                        '0' AS IS_START
                                                    FROM
                                                        FA_CONNECTION F
                                                        INNER JOIN REF_PORT_TYPE PT ON F.SRC_PORT_TYPE_ID = PT.ID
                                                    WHERE
                                                        F.IS_ACTIVE = 1
                                                ) CN WHERE CN.CONNECTION_ID = $conId AND CN.IS_START = $isstart");
    }

    public function getConnectionFormRowsDataModel($insId = '', $isSrc = false) {
        (array) $result = array();
        
        if ($insId) {
            $result = $this->db->GetAll("SELECT CN.* FROM (
                                                    SELECT
                                                        F.*, 
                                                        PT.PORT_TYPE_NAME,
                                                        REPLACE(F.SRC_PORT_INFO || '->' || SR.PORT_TYPE_NAME || '#' || F.SRC_PORT, 'undefined', '') AS SRC_PORT_INF,
                                                        REPLACE(F.TRG_PORT_INFO || '->' || PT.PORT_TYPE_NAME || '#' || F.TRG_PORT, 'undefined', '') AS TRG_PORT_INF
                                                    FROM
                                                        FA_CONNECTION F
                                                        INNER JOIN (
                                                                        SELECT
                                                                            ID,
                                                                            PORT_TYPE_NAME
                                                                        FROM
                                                                            REF_PORT_TYPE
                                                                    ) SR ON F.SRC_PORT_TYPE_ID = SR.ID
                                                        LEFT JOIN REF_PORT_TYPE PT ON F.TRG_PORT_TYPE_ID = PT.ID
                                                    WHERE
                                                        F.IS_ACTIVE = 1
                                                    ORDER BY F.ORDER_NUM ASC    
                                                ) CN WHERE CN.INSTALLATION_ID = $insId");

            if ($result) {
                (array) $tmp = array();
                foreach ($result as $value) {
                    (array) $portTypeList = array();
                    (array) $trgportTypeList = array();
                    if (!empty($value['SRC_RECORD_ID'])) {
                        $checkKeyId = $value['SRC_RECORD_ID'];
                        $portTypeList = $this->db->GetAll("SELECT DISTINCT
                                        T3.PORT_QTY,
                                        T3.ASSET_ID,
                                        T3.ASSET_PORT_ID,
                                        T3.PORT_TYPE_ID,
                                        T3.START_PORT_NUM,
                                        T4.PORT_TYPE_NAME,
                                        T4.PORT_TYPE_CODE,
                                        T4.IS_ACTIVE,
                                        T4.DESCRIPTION,
                                        T4.ICON ,
                                        T1.ID
                                        FROM FA_CONNECTION T0
                                    INNER JOIN IM_CHECK_KEY T1 ON T0.SRC_RECORD_ID = T1.ID
                                    INNER JOIN FA_ASSET T2 ON T1.ASSET_ID = T2.ASSET_ID
                                    INNER JOIN FA_ASSET_PORT T3 ON T2.ASSET_ID = T3.ASSET_ID
                                    INNER JOIN REF_PORT_TYPE T4 ON T3.PORT_TYPE_ID = T4.ID
                                    WHERE T1.ID = $checkKeyId AND T4.IS_ACTIVE = 1 ORDER BY T3.PORT_TYPE_ID");
                    }
                    $value['srcPortTypeList'] = $portTypeList;


                    if (!empty($value['TRG_RECORD_ID'])) {
                        $checkKeyId = $value['TRG_RECORD_ID'];
                        $trgportTypeList = $this->db->GetAll("SELECT DISTINCT
                                                            T3.PORT_QTY,
                                                            T3.ASSET_ID,
                                                            T3.ASSET_PORT_ID,
                                                            T3.PORT_TYPE_ID,
                                                            T3.START_PORT_NUM,
                                                            T4.PORT_TYPE_NAME,
                                                            T4.PORT_TYPE_CODE,
                                                            T4.IS_ACTIVE,
                                                            T4.DESCRIPTION,
                                                            T4.ICON ,
                                                            T1.ID
                                                            FROM FA_CONNECTION T0
                                                        INNER JOIN IM_CHECK_KEY T1 ON T0.TRG_RECORD_ID = T1.ID
                                                        INNER JOIN FA_ASSET T2 ON T1.ASSET_ID = T2.ASSET_ID
                                                        INNER JOIN FA_ASSET_PORT T3 ON T2.ASSET_ID = T3.ASSET_ID
                                                        INNER JOIN REF_PORT_TYPE T4 ON T3.PORT_TYPE_ID = T4.ID
                                                        WHERE T1.ID = $checkKeyId AND T4.IS_ACTIVE = 1 ORDER BY T3.PORT_TYPE_ID");
                    }
                    $value['trgPortTypeList'] = $trgportTypeList;
                    array_push($tmp, $value);
                }
                $result = $tmp;
            }
        }
        
        return $result;
    }

    public function getRowInstallationDataModel($installationId) {
        (array) $installation = array();
        if ($installationId) {

            $installation = $this->db->GetAll("SELECT DISTINCT  
                                                   I.INSTALLATION_ID,
                                                   I.INSTALLATION_DATE,
                                                   I.SOURCE_ADDRESS,
                                                   I.DESTINATION_ADDRESS,
                                                   I.CIRCUIT_ID,
                                                   SUBSTR(BP.LAST_NAME, 1, 2) || '.' || BP.FIRST_NAME AS INSTALLATION_USER
                                               FROM
                                                   FA_INSTALLATION I
                                                   INNER JOIN FA_CONNECTION C ON I.INSTALLATION_ID = C.INSTALLATION_ID
                                                   INNER JOIN UM_USER UU ON I.INSTALLATION_USER_ID = UU.USER_ID
                                                   INNER JOIN UM_SYSTEM_USER USU ON UU.SYSTEM_USER_ID = USU.USER_ID
                                                   INNER JOIN BASE_PERSON BP ON BP.PERSON_ID = USU.PERSON_ID
                                               WHERE
                                                   I.INSTALLATION_ID IN( $installationId )
                                                   AND I.IS_ACTIVE = 1 AND C.IS_ACTIVE = 1 AND UU.IS_ACTIVE = 1");
        }
        return isset($installation[0]) ? $installation[0] : $installation;
    }

    public function getConnectionDataByCheckKeyIdModel($checkKeyId) {
        return $this->db->GetAll("SELECT DISTINCT
                                        T3.PORT_QTY,
                                        T3.ASSET_ID,
                                        T3.ASSET_PORT_ID,
                                        T3.PORT_TYPE_ID,
                                        T3.START_PORT_NUM,
                                        T4.PORT_TYPE_NAME,
                                        T4.PORT_TYPE_CODE,
                                        T4.IS_ACTIVE,
                                        T4.DESCRIPTION,
                                        T4.ICON ,
                                        T1.ID
                                        FROM FA_CONNECTION T0
                                    INNER JOIN IM_CHECK_KEY T1 ON T0.TRG_RECORD_ID = T1.ID
                                    INNER JOIN FA_ASSET T2 ON T1.ASSET_ID = T2.ASSET_ID
                                    INNER JOIN FA_ASSET_PORT T3 ON T2.ASSET_ID = T3.ASSET_ID
                                    INNER JOIN REF_PORT_TYPE T4 ON T3.PORT_TYPE_ID = T4.ID
                                    WHERE T1.ID = $checkKeyId AND T4.IS_ACTIVE = 1 ORDER BY T3.PORT_TYPE_ID");
    }

    public function getConnectionDataByPortTypeModel($checkKeyId, $portType, $selfPort, $assetPortId = null) {
        $list = $this->db->GetAll("SELECT DISTINCT
                                        T3.ASSET_PORT_ID,
                                        T3.PORT_QTY,
                                        T3.PORT_TYPE_ID,
                                        T3.START_PORT_NUM
                                        FROM IM_CHECK_KEY T1 
                                    INNER JOIN FA_ASSET T2 ON T1.ASSET_ID = T2.ASSET_ID
                                    INNER JOIN FA_ASSET_PORT T3 ON T2.ASSET_ID = T3.ASSET_ID
                                    INNER JOIN REF_PORT_TYPE T4 ON T3.PORT_TYPE_ID = T4.ID
                                    WHERE T1.ID = $checkKeyId AND T3.PORT_TYPE_ID = $portType AND T3.ASSET_PORT_ID = $assetPortId AND T4.IS_ACTIVE = 1 AND T2.IS_ACTIVE = 1 ");

        (array) $portList = array();
        if (!empty($list[0])) {
            $startPortNum = $list[0]['START_PORT_NUM'];
            $connectedPort = $this->db->GetAll("SELECT DISTINCT
                                                      F.SRC_PORT PORT
                                                  FROM
                                                      FA_INSTALLATION I
                                                      INNER JOIN FA_CONNECTION F ON I.INSTALLATION_ID = F.INSTALLATION_ID
                                                      INNER JOIN IM_CHECK_KEY CK ON F.SRC_RECORD_ID = CK.ID
                                                      INNER JOIN FA_ASSET_PORT FAP ON CK.ASSET_ID = FAP.ASSET_ID
                                                                                      AND F.SRC_PORT_TYPE_ID = FAP.PORT_TYPE_ID
                                                                                      AND F.SRC_PORT_ID = FAP.ASSET_PORT_ID 
                                                      WHERE I.IS_ACTIVE = 1 AND F.IS_ACTIVE = 1 AND FAP.ASSET_PORT_ID = $assetPortId AND F.SRC_PORT != $selfPort
                                                  UNION ALL
                                                  SELECT DISTINCT
                                                      F.TRG_PORT PORT
                                                  FROM
                                                      FA_INSTALLATION I
                                                      INNER JOIN FA_CONNECTION F ON I.INSTALLATION_ID = F.INSTALLATION_ID
                                                      INNER JOIN IM_CHECK_KEY CK ON F.TRG_RECORD_ID = CK.ID
                                                      INNER JOIN FA_ASSET_PORT FAP ON CK.ASSET_ID = FAP.ASSET_ID
                                                                                      AND F.TRG_PORT_TYPE_ID = FAP.PORT_TYPE_ID
                                                                                      AND F.TRG_PORT_ID = FAP.ASSET_PORT_ID 
                                                      WHERE I.IS_ACTIVE = 1 AND F.IS_ACTIVE = 1 AND FAP.ASSET_PORT_ID = $assetPortId AND F.TRG_PORT != $selfPort ");
            (array) $connectedPortList = array();
            foreach ($connectedPort as $value) {
                array_push($connectedPortList, $value['PORT']);
            }
            for ($i = $startPortNum; $i <= $list[0]['PORT_QTY']; $i++) {
                if ($startPortNum == 0 && $i == $list[0]['PORT_QTY']) {
                    break;
                }
                if (!in_array($i, $connectedPortList)) {
                    array_push($portList, array('ID' => $i, 'NAME' => $i));
                }
            }
        }

        return $portList;
    }

    public function getConnectionDataByPortModel($checkKeyId, $portType, $selfPort = '', $assetPortId = null) {
        $list = $this->db->GetAll("SELECT DISTINCT
                                        T3.ASSET_PORT_ID,
                                        T3.PORT_QTY,
                                        T3.PORT_TYPE_ID,
                                        T3.START_PORT_NUM
                                        FROM IM_CHECK_KEY T1 
                                    INNER JOIN FA_ASSET T2 ON T1.ASSET_ID = T2.ASSET_ID
                                    LEFT JOIN FA_ASSET_CARD FAC ON T1.ASSET_CARD_ID = FAC.ASSET_CARD_ID
                                    INNER JOIN FA_ASSET_PORT T3 ON COALESCE(FAC.ASSET_ID,T2.ASSET_ID) = T3.ASSET_ID
                                    INNER JOIN REF_PORT_TYPE T4 ON T3.PORT_TYPE_ID = T4.ID
                                    WHERE T1.ID = $checkKeyId AND T3.PORT_TYPE_ID = $portType AND T3.ASSET_PORT_ID = $assetPortId AND T4.IS_ACTIVE = 1 AND T2.IS_ACTIVE = 1");

        (array) $portList = array();
        if (!empty($list[0])) {
            $startPortNum = $list[0]['START_PORT_NUM'];
            $connectedPort = $this->db->GetAll("SELECT DISTINCT F.SRC_PORT PORT
                                                FROM FA_INSTALLATION I
                                                INNER JOIN FA_CONNECTION F   ON I.INSTALLATION_ID = F.INSTALLATION_ID
                                                INNER JOIN IM_CHECK_KEY CK   ON F.SRC_RECORD_ID = CK.ID
                                                LEFT JOIN FA_ASSET_PORT FAP ON CK.ASSET_ID = FAP.ASSET_ID AND F.SRC_PORT_TYPE_ID = FAP.PORT_TYPE_ID AND F.SRC_PORT_ID = FAP.ASSET_PORT_ID
                                                LEFT JOIN FA_ASSET_CARD FAC ON CK.ASSET_CARD_ID = FAC.ASSET_CARD_ID
                                                LEFT JOIN FA_ASSET_PORT FAP1 ON FAC.ASSET_ID = FAP1.ASSET_ID AND F.TRG_PORT_TYPE_ID = FAP1.PORT_TYPE_ID AND F.TRG_PORT_ID = FAP1.ASSET_PORT_ID
                                                WHERE I.IS_ACTIVE = 1 AND F.IS_ACTIVE = 1 AND COALESCE(FAP1.ASSET_PORT_ID,FAP.ASSET_PORT_ID) = $assetPortId
                                                UNION ALL
                                                SELECT DISTINCT F.TRG_PORT PORT
                                                FROM FA_INSTALLATION I
                                                INNER JOIN FA_CONNECTION F   ON I.INSTALLATION_ID = F.INSTALLATION_ID
                                                INNER JOIN IM_CHECK_KEY CK   ON F.TRG_RECORD_ID = CK.ID
                                                LEFT JOIN FA_ASSET_PORT FAP ON CK.ASSET_ID = FAP.ASSET_ID AND F.TRG_PORT_TYPE_ID = FAP.PORT_TYPE_ID AND F.TRG_PORT_ID = FAP.ASSET_PORT_ID
                                                LEFT JOIN FA_ASSET_CARD FAC ON CK.ASSET_CARD_ID = FAC.ASSET_CARD_ID
                                                LEFT JOIN FA_ASSET_PORT FAP1 ON FAC.ASSET_ID = FAP1.ASSET_ID AND F.TRG_PORT_TYPE_ID = FAP1.PORT_TYPE_ID AND F.TRG_PORT_ID = FAP1.ASSET_PORT_ID
                                                WHERE I.IS_ACTIVE = 1 AND F.IS_ACTIVE = 1 AND COALESCE(FAP1.ASSET_PORT_ID,FAP.ASSET_PORT_ID) = $assetPortId");
            (array) $connectedPortList = array();
            if (!empty($selfPort)) {
                array_push($connectedPortList, $selfPort);
            }
            foreach ($connectedPort as $value) {
                array_push($connectedPortList, $value['PORT']);
            }
            for ($i = $startPortNum; $i <= $list[0]['PORT_QTY']; $i++) {
                if ($startPortNum == 0 && $i == $list[0]['PORT_QTY']) {
                    break;
                }
                if (!in_array($i, $connectedPortList)) {
                    array_push($portList, array('ID' => $i, 'NAME' => $i, 'ASSET_PORT_ID' => $assetPortId));
                }
            }
        }

        return $portList;
    }

    public function savePortconnectionModel() {
        try {

            $installationId = getUID();

            $sourceAddress = Input::post('sourceAddress');
            $destinationAddress = Input::post('destinationAddress');
            $circuitId = Input::post('circuitId');
            $taskId = Input::post('taskId');
            $trgExternal = (Input::postCheck('trgPortInfoExternal') && Input::post('trgPortInfoExternal')) ? Input::post('trgPortInfoExternal') : '';

            $assetPortId = Input::post('assetPortId');

            $dataInstallation = array(
                'INSTALLATION_ID' => $installationId,
                'INSTALLATION_DATE' => Date::currentDate(),
                'INSTALLATION_USER_ID' => Ue::sessionUserKeyId(),
                'SOURCE_ADDRESS' => $sourceAddress,
                'DESTINATION_ADDRESS' => $destinationAddress,
                'CIRCUIT_ID' => $circuitId,
                'TASK_ID' => $taskId,
                'CREATED_DATE' => Date::currentDate(),
                'CREATED_USER_ID' => Ue::sessionUserKeyId(),
                'IS_ACTIVE' => '1',
            );

            if (Input::postCheck('installationId') && Input::post('installationId')) {
                $this->db->AutoExecute("FA_INSTALLATION", array('IS_ACTIVE' => '0'), "UPDATE", "INSTALLATION_ID = " . Input::post('installationId'));
            }

            $dataI = $this->db->AutoExecute("FA_INSTALLATION", $dataInstallation);
            if ($dataI) {
                $response = array('status' => 'success', 'message' => Lang::line('msg_save_success'));
            } else {
                $response = array('status' => 'error', 'message' => Lang::line('msg_save_error'));
            }
            if (Input::postCheck('srclocationid')) {

                $srclocationid = Input::post('srclocationid');
                $trgclocationids = Input::post('trgclocationid');
                $trgcrecordids = Input::post('trgcrecordid');
                $srcrecordids = Input::post('srcrecordid');
                $trgPortTypes = Input::post('trgPortType');
                $srcPortTypes = Input::post('srcPortType');
                $trgPortNumbers = Input::post('trgPortNumber');
                $srcPortNumbers = Input::post('srcPortNumber');
                $trgcPortInfos = Input::post('trgcPortInfo');
                $srcPortInfos = Input::post('srcPortInfo');
                $trgportids = Input::post('trgportid');
                $srcportids = Input::post('srcportid');
                $orderNums = Input::post('orderNum');

                $locationList = $srclocationid[0];

                foreach ($locationList as $key => $value) {

                    $connectionId = getUID();
                    $trgclocationid = $trgclocationids[0][$key];

                    $trgcrecordid = $trgcrecordids[0][$key];
                    $srcrecordid = $srcrecordids[0][$key];

                    $trgPortType = isset($trgPortTypes[0][$key]) ? $trgPortTypes[0][$key] : 1;
                    $srcPortType = $srcPortTypes[0][$key];

                    $trgPortNumber = isset($trgPortNumbers[0][$key]) ? $trgPortNumbers[0][$key] : 1;
                    $srcPortNumber = $srcPortNumbers[0][$key];

                    $trgPortInfo = $trgcPortInfos[0][$key];
                    $srcPortInfo = $srcPortInfos[0][$key];

                    $trgAssetPortId = $trgportids[0][$key];
                    $srcAssetPortId = $srcportids[0][$key];

                    $orderNum = $orderNums[0][$key];

                    if (!empty($value)) {
                        if ($srcrecordid == $trgcrecordid && $srcPortNumber == $trgPortNumber && $srcPortType == $trgPortType) {
                            $response = array('status' => 'error', 'message' => Lang::line('msg_save_error_001'));
                        } else {

                            $data = array(
                                'CONNECTION_ID' => $connectionId,
                                'CONNECTION_TYPE_ID' => Input::post('cableType'),
                                'CABLE_LENGTH' => Input::post('cableLen'),
                                'CABLE_ID' => '',
                                'TUBE_COLOR_ID' => '',
                                'CORE_COLOR_ID' => '',
                                'DESCRIPTION' => '',
                                'SRC_RECORD_ID' => $srcrecordid,
                                'TRG_RECORD_ID' => $trgcrecordid,
                                'SRC_PORT_TYPE_ID' => $srcPortType,
                                'TRG_PORT_TYPE_ID' => (isset($trgPortTypes[0][$key]) ? $trgPortType : null),
                                'SRC_PORT' => $srcPortNumber,
                                'TRG_PORT' => (isset($trgPortNumbers[0][$key]) ? $trgPortNumber : null),
                                'SRC_LOCATION_ID' => $value,
                                'TRG_LOCATION_ID' => $trgclocationid,
                                'CREATED_DATE' => Date::currentDate(),
                                'CREATED_USER_ID' => Ue::sessionUserKeyId(),
                                'TRG_PORT_INFO' => $trgPortInfo . (isset($trgPortTypes[0][$key]) ? '' : $trgExternal),
                                'SRC_PORT_INFO' => $srcPortInfo,
                                'IS_ACTIVE' => '1',
                                'INSTALLATION_ID' => $installationId,
                                'TRG_PORT_ID' => $trgAssetPortId,
                                'SRC_PORT_ID' => $srcAssetPortId,
                                'TRG_DESCRIPTION' => (isset($trgPortTypes[0][$key]) ? null : $trgExternal),
                                'ORDER_NUM' => $orderNum
                            );

                            if (Input::postCheck('installationId') && Input::post('installationId')) {
                                $this->db->AutoExecute("FA_CONNECTION", array('IS_ACTIVE' => '0'), "UPDATE", "INSTALLATION_ID = " . Input::post('installationId'));
                            }

                            $data1 = $this->db->AutoExecute("FA_CONNECTION", $data);
                            if ($data1) {
                                $response = array('status' => 'success', 'message' => Lang::line('msg_save_success'));
                            } else {
                                $response = array('status' => 'error', 'message' => Lang::line('msg_save_error'));
                            }
                        }
                    }
                }
            }
        } catch (Exception $ex) {
            $response = array('status' => 'error', 'message' => Lang::line('msg_save_error'));
        }
        return $response;
    }

    public function getConnectionInstallationDataModel($connectionId, $locationId = null, $nextlocationId = null) {

        $connection = $this->db->GetAll("SELECT
                                            T0.PORT_QTY,
                                            T0.ASSET_ID,
                                            T0.ASSET_PORT_ID,
                                            T0.PORT_TYPE_ID,
                                            T0.START_PORT_NUM,
                                            T1.PORT_TYPE_NAME,
                                            T1.PORT_TYPE_CODE,
                                            T1.IS_ACTIVE,
                                            T1.DESCRIPTION,
                                            T1.ICON
                                        FROM
                                            FA_ASSET_PORT T0
                                            INNER JOIN REF_PORT_TYPE T1 ON T0.PORT_TYPE_ID = T1.ID
                                            INNER JOIN FA_CONNECTION F ON T0.ASSET_PORT_ID = F.SRC_PORT_ID
                                        WHERE
                                            F.CONNECTION_ID = $connectionId AND T1.IS_ACTIVE = 1 ORDER BY T0.PORT_TYPE_ID");

        (array) $connectionPort = array();
        (array) $installationPort = array();
        (array) $installation = array();

        if ($locationId && $nextlocationId) {
            $installationPort = $this->db->GetAll("SELECT DISTINCT
                                                      F.CONNECTION_ID,
                                                      F.SRC_LOCATION_ID AS LOCATION_ID,
                                                      F.SRC_RECORD_ID RECORD_ID,
                                                      F.INSTALLATION_ID,
                                                      F.SRC_PORT_ID,
                                                      F.TRG_PORT_ID,
                                                      F.SRC_PORT_TYPE_ID,
                                                      F.TRG_PORT_TYPE_ID,
                                                      F.SRC_PORT,
                                                      F.TRG_PORT,
                                                      F.ORDER_NUM
                                                  FROM
                                                      FA_CONNECTION F
                                                      INNER JOIN FA_INSTALLATION I ON F.INSTALLATION_ID = I.INSTALLATION_ID
                                                      INNER JOIN FA_ASSET_PORT FAP ON F.SRC_PORT_ID = FAP.ASSET_PORT_ID
                                                                                      AND F.SRC_PORT_TYPE_ID = FAP.PORT_TYPE_ID
                                                  WHERE
                                                      F.IS_ACTIVE = 1
                                                      AND   I.IS_ACTIVE = 1
                                                      AND   F.SRC_LOCATION_ID = $locationId
                                                      AND   F.TRG_LOCATION_ID = $nextlocationId
                                                      AND   F.CONNECTION_ID = $connectionId
                                                  UNION ALL
                                                  SELECT DISTINCT
                                                      F.CONNECTION_ID,
                                                      F.TRG_LOCATION_ID AS LOCATION_ID,
                                                      F.TRG_RECORD_ID RECORD_ID,
                                                      F.INSTALLATION_ID,
                                                      F.SRC_PORT_ID,
                                                      F.TRG_PORT_ID,
                                                      F.SRC_PORT_TYPE_ID,
                                                      F.TRG_PORT_TYPE_ID,
                                                      F.SRC_PORT,
                                                      F.TRG_PORT,
                                                      F.ORDER_NUM
                                                  FROM
                                                      FA_CONNECTION F
                                                      INNER JOIN FA_INSTALLATION I ON F.INSTALLATION_ID = I.INSTALLATION_ID
                                                      INNER JOIN FA_ASSET_PORT FAP ON F.TRG_PORT_ID = FAP.ASSET_PORT_ID
                                                                                      AND F.TRG_PORT_TYPE_ID = FAP.PORT_TYPE_ID
                                                  WHERE
                                                      F.IS_ACTIVE = 1
                                                      AND   I.IS_ACTIVE = 1
                                                      AND   F.SRC_LOCATION_ID = $locationId
                                                      AND   F.TRG_LOCATION_ID = $nextlocationId 
                                                      AND   F.CONNECTION_ID = $connectionId");
        }
        $installationId = '';
        $checkKeyIdId = '';
        if ($installationPort) {
            (array) $tmp = array();
            foreach ($installationPort as $value) {
                if ($value['INSTALLATION_ID']) {
                    $installationId = $value['INSTALLATION_ID'];
                    $checkKeyIdId = $installationPort[0]['RECORD_ID'];
                    $connectionId = $value['CONNECTION_ID'];

                    $value['INSTALLATION_DTL'] = $this->db->GetAll("SELECT
                                                                     I.INSTALLATION_ID,
                                                                     I.INSTALLATION_DATE,
                                                                     I.SOURCE_ADDRESS,
                                                                     I.DESTINATION_ADDRESS,
                                                                     I.CIRCUIT_ID,
                                                                     I.TASK_ID,
                                                                     F.CONNECTION_ID,
                                                                     F.TRG_LOCATION_ID AS LOCATION_ID,
                                                                     F.TRG_PORT_TYPE_ID AS PORT_TYPE_ID,
                                                                     F.TRG_RECORD_ID RECORD_ID,
                                                                     F.TRG_PORT AS PORT,
                                                                     F.SRC_PORT,
                                                                     F.ORDER_NUM,
                                                                     PT.PORT_TYPE_NAME,
                                                                     F.TRG_PORT_INFO|| ' '|| PT.PORT_TYPE_NAME|| '#' || F.TRG_PORT AS PORT_INFO,
                                                                     F.SRC_PORT_INFO|| ' '|| SR.PORT_TYPE_NAME|| '#' || F.SRC_PORT AS SR_PORT_INFO
                                                                 FROM
                                                                     FA_INSTALLATION I
                                                                     INNER JOIN FA_CONNECTION F ON I.INSTALLATION_ID = F.INSTALLATION_ID
                                                                     INNER JOIN REF_PORT_TYPE PT ON F.TRG_PORT_TYPE_ID = PT.ID
                                                                     INNER JOIN (
                                                                         SELECT
                                                                             ID,
                                                                             PORT_TYPE_NAME
                                                                         FROM
                                                                             REF_PORT_TYPE
                                                                     ) SR ON F.SRC_PORT_TYPE_ID = SR.ID
                                                                 WHERE
                                                                     I.INSTALLATION_ID = $installationId AND F.CONNECTION_ID = $connectionId
                                                                     AND   I.IS_ACTIVE = 1");
                    array_push($tmp, $value);
                }
                $installation = $tmp;
            }
        }

        return array('connection' => $connection, 'connectionPort' => $connectionPort, 'installation' => $installation, 'installationId' => $installationId, 'checkKeyIdId' => $checkKeyIdId);
    }

    public function getLeftSidebarModel($dataviewId, $categoryid = null) {
        $param = array(
            'systemMetaGroupId' => $dataviewId,
            'showQuery' => '0',
            'ignorePermission' => 1,
            'criteria' => array()
        );

        if ($categoryid) {
            $param['criteria'] = array(
                'parentid' => array(
                    array(
                        'operator' => '=',
                        'operand' => $categoryid
                    )
                )
            );
        } else {
            $param['criteria'] = array(
                'parentid' => array(
                    array(
                        'operator' => '=',
                        'operand' => '0'
                    )
                )
            );
        }

        $data = $this->ws->runSerializeResponse(GF_SERVICE_ADDRESS, Mddatamodel::$getDataViewCommand, $param);

        if (isset($data['result']) && $data['result']) {

            unset($data['result']['paging']);
            unset($data['result']['aggregatecolumns']);
            return $data['result'];
        }
    }

    public function getSidebarContentModel($dataviewId, $filterParam = array()) {
        (Array) $criteria = array();

        $param = array(
            'systemMetaGroupId' => $dataviewId,
            'showQuery' => '0',
            'ignorePermission' => 1,
            'criteria' => $criteria
        );

        if ($filterParam) {
            if (isset($filterParam['param']) && $filterParam['param']) {
                foreach ($filterParam['param'] as $key => $row) {
                    $criteria[$key][] = array(
                        'operator' => '=',
                        'operand' => $row
                    );
                }
            }
        }

        $param['criteria'] = $criteria;

        $data = $this->ws->runSerializeResponse(GF_SERVICE_ADDRESS, Mddatamodel::$getDataViewCommand, $param);

        if (isset($data['result']) && $data['result']) {

            unset($data['result']['paging']);
            unset($data['result']['aggregatecolumns']);
            return $data['result'];
        }
    }

    public function getProcessCodeResult($processId, $params) {

        $processData = $this->db->GetRow("SELECT * FROM META_DATA WHERE META_DATA_ID = " . $processId);
        if (isset($processData['META_DATA_CODE']) && $processData['META_DATA_CODE']) {

            $result = $this->ws->caller('WSDL-DE', GF_SERVICE_ADDRESS, $processData['META_DATA_CODE'], 'return', $params, 'serialize');

            if (isset($result['result']) && $result['result']) {
                unset($result['result']['aggregatecolumns']);
                return $result['result'];
                die;
            }
        }

        return array();
    } 
    
    public function getDataMartDvRowsModel($dvId, $criteria = array()) {
        
        $param = array(
            'systemMetaGroupId' => $dvId,
            'ignorePermission' => 1, 
            'showQuery' => 0,
            'criteria' => $criteria
        );

        $result = $this->ws->runSerializeResponse(self::$gfServiceAddress, Mddatamodel::$getDataViewCommand, $param);

        if (isset($result['result']) && isset($result['result'][0])) {
            unset($result['result']['aggregatecolumns']);
            unset($result['result']['paging']);
            $data = $result['result'];
        } else {
            $data = array();
        }
        
        return $data;
    }


    /* HR  summery dashboard DV  */

    public function loadListModel($systemMetaGroupId, $criteria = array(), $paging = array(), $isShowQuery = 0) {
        
        $result = array();
        $param = array(
            'systemMetaGroupId' => $systemMetaGroupId, 
            'showQuery' => $isShowQuery, 
            'ignorePermission' => 1, 
            'criteria' => $criteria,
            'paging' => $paging
        );
        
        $dataResult = $this->ws->runSerializeResponse(GF_SERVICE_ADDRESS, self::$getDataViewCommand, $param);

        if ($dataResult['status'] === 'success') {
            
            if (isset($dataResult['result']['paging'])) {
                unset($dataResult['result']['paging']);
            }
            
            unset($dataResult['result']['aggregatecolumns']);
            
            $result = $dataResult['result'];
        }

        return $result;
    }
    
    public function filterData($addDate = '', $useFilterStartDate = null) {
        $currentDate = Date::currentDate('Y-m-d');
        
        $data = array(
            'filterEndDate' => array(
                array(
                    'operator' => '=',
                    'operand' => ($addDate) ? Date::formatter($currentDate, 'Y-m') . $addDate : $currentDate
                )
            ),
            'sessionDepartmentId' => array(
                array(
                    'operator' => '=',
                    'operand' => Ue::sessionDepartmentId()
                )
            ),
            'filterDepartmentId' => array(
                array(
                    'operator' => '=',
                    'operand' => Ue::sessionDepartmentId()
                )
            ),
            'sessionUserId' => array(
                array(
                    'operator' => '=',
                    'operand' => Ue::sessionUserId()
                )
            ),
            'filterUserId' => array(
                array(
                    'operator' => '=',
                    'operand' => Ue::sessionUserId()
                )
            ),
            'sessionUserKeyId' => array(
                array(
                    'operator' => '=',
                    'operand' => Ue::sessionUserKeyId()
                )
            ),
            'filterNextWfmUserId' => array(
                array(
                    'operator' => '=',
                    'operand' => Ue::sessionUserKeyId()
                )
            ),
        );
        
        if ($useFilterStartDate) {
            $data['filterstartdate'] = array(
                array(
                    'operator' => '=',
                    'operand' => Date::weekdayAfter('Y-m-d', $currentDate, ' -30 days')
                )
            );
        }
        
        return $data;
    }
  
    public function fncRunDataview($dataviewId, $field = "", $criteria , $operator = "", $paramFilter = "", $sortField = 'createddate', $sortK = 'desc', $iscriteriaOnly = "0", $pagination = false, $pageSize = false) {
        
        
        includeLib('Utils/Functions');
        
        $paging = array();
        
        if ($pagination || $pageSize) {
            $paging = array(
                'offset' => Input::post('offset') ? Input::post('offset') : '1',
                'pageSize' => $pageSize ? $pageSize : '50'
            );
        }
        
        if ($sortField) {
            $sortColumnNames[$sortField] = array('sortType' => $sortK);
            $paging['sortColumnNames'] = $sortColumnNames;
        }
        // pa($criteria);
        $data = Functions::runDataViewWithoutLogin($dataviewId, $criteria, '0', $paging);
        
        (Array) $response = array();
        if ($pagination) {
            $response = $data;
        } elseif (isset($data['result']) && $data['result']) {
            unset($data['result']['aggregatecolumns']);
            unset($data['result']['paging']);
            $response = $data['result'];
        }
        
        return $response;
    }

    public function dashboardLayoutDataModel($layoutCode, $paramFilter = array(), $request = '0', $agent = '0') {
        
        $currentDate = Date::currentDate('Y-m-d');
        (Array) $dashboardArr = array();

        if($layoutCode == 'main1'){
            $dashboardArr['main1_1'] =  $this->fncRunDataview('1592475627552381', '', $paramFilter, "", "", "0"); 
            $dashboardArr['main1_2'] =  $this->fncRunDataview('1592453115147', '', $paramFilter, "", "", "0");
            $dashboardArr['main1_3'] =  $this->fncRunDataview('1592453115263', '', $paramFilter, "", "", "0");
            $dashboardArr['main1_4'] =  $this->fncRunDataview('1592453115370', '', $paramFilter, "", "", "0");
            $dashboardArr['main1_5'] =  $this->fncRunDataview('1592453115424', '', $paramFilter, "", "", "0");
            $dashboardArr['main1_6'] =  $this->fncRunDataview('1592453115493', '', $paramFilter, "", "", "0");
            $dashboardArr['main1_7'] =  $this->fncRunDataview('1592453115658', '', $paramFilter, "", "", "0");
            $dashboardArr['main1_8'] =  $this->fncRunDataview('1592453115765', '', $paramFilter, "", "", "0");
            $dashboardArr['main1_9'] =  $this->fncRunDataview('1592453115834', '', $paramFilter, "", "", "0"); 
            $dashboardArr['main1_10'] =  $this->fncRunDataview('1592453115875', '', $paramFilter, "", "", "0");
            $dashboardArr['main1_11'] =  $this->fncRunDataview('1592453115957', '', $paramFilter, "", "", "0");
            $dashboardArr['main1_12'] =  $this->fncRunDataview('1592453116035', '', $paramFilter, "", "", "0");
            $dashboardArr['main1_13'] =  $this->fncRunDataview('1592453116090', '', $paramFilter, "", "", "0");
        }elseif($layoutCode == 'main2'){
            $dashboardArr['main2_1'] =  $this->fncRunDataview('1592453116642', '', $paramFilter, "", "", "0");          // картууд 
            $dashboardArr['main2_2'] =  $this->fncRunDataview('1592453116656', '', $paramFilter, "", "", "0");          // ажилтаны тоо бүлгээр
            $dashboardArr['main2_3'] =  $this->fncRunDataview('1592453116670', '', $paramFilter, "", "", "0");          // хүйсийн харьцаа
            $dashboardArr['main2_4'] =  $this->fncRunDataview('1592453116684', '', $paramFilter, "", "", "0");          // ажилтаны түүх
            $dashboardArr['main2_5'] =  $this->fncRunDataview('1592453116698', '', $paramFilter, "", "", "0");          // албан тушаал зэрэглэлээр 
            $dashboardArr['main2_6'] =  $this->fncRunDataview('1592453116712', '', $paramFilter, "", "", "0");          // албан тушаал зэрэглэлээр 
            $dashboardArr['main2_7'] =  $this->fncRunDataview('1592453116727', '', $paramFilter, "", "", "0");          // байгуулга 
            $dashboardArr['main2_8'] =  $this->fncRunDataview('1593075390613', '', $paramFilter, "", "", "0");          // байгуулга 
        }elseif($layoutCode == 'recruitment1'){
            $dashboardArr['rec1_0'] =  $this->fncRunDataview('1592453305034', '', $paramFilter, "", "", "0");         // картууд 
            $dashboardArr['rec1_1'] =  $this->fncRunDataview('1592453305075', '', $paramFilter, "", "", "0");         // ажилтаны авах хүсэлт
            $dashboardArr['rec1_2'] =  $this->fncRunDataview('1592453305094', '', $paramFilter, "", "", "0");         // Зарлагдсан зар
            $dashboardArr['rec1_3'] =  $this->fncRunDataview('1592453305110', '', $paramFilter, "", "", "0");         // анкетийн эх сурвалж
            $dashboardArr['rec1_4'] =  $this->fncRunDataview('1592453305124', '', $paramFilter, "", "", "0");         // үе шат
            $dashboardArr['rec1_5'] =  $this->fncRunDataview('1592453305139', '', $paramFilter, "", "", "0");         // хийгдсэн СШ тоо
            $dashboardArr['rec1_6'] =  $this->fncRunDataview('1592453305153', '', $paramFilter, "", "", "0");         //  АЖИЛ ГОРИЛОГЧ
            $dashboardArr['rec1_7'] =  $this->fncRunDataview('1592453305167', '', $paramFilter, "", "", "0");         //  ажил горилогч  нас
            $dashboardArr['rec1_8'] =  $this->fncRunDataview('1592453305181', '', $paramFilter, "", "", "0");         // ажил горилогч 
        }elseif($layoutCode == 'recruitment2'){
            $dashboardArr['rec2_0'] =  $this->fncRunDataview('1592453309040', '', $paramFilter, "", "", "0");            // картууд 
            $dashboardArr['rec2_1'] =  $this->fncRunDataview('1592453306732', '', $paramFilter, "", "", "0");           // ажилтаны авах хүсэл
            $dashboardArr['rec2_2'] =  $this->fncRunDataview('1592453306747', '', $paramFilter, "", "", "0");           // Зарлагдсан зар
            $dashboardArr['rec2_3'] =  $this->fncRunDataview('1592453306761', '', $paramFilter, "", "", "0");           // анкетийн эх сурвалж
            $dashboardArr['rec2_4'] =  $this->fncRunDataview('1592453306775', '', $paramFilter, "", "", "0");           // үе шат
            $dashboardArr['rec2_5'] =  $this->fncRunDataview('1592453306789', '', $paramFilter, "", "", "0");           // хийгдсэн СШ тоо
            $dashboardArr['rec2_6'] =  $this->fncRunDataview('1592453306804', '', $paramFilter, "", "", "0");           //  АЖИЛ ГОРИЛОГЧ
            $dashboardArr['rec2_7'] =  $this->fncRunDataview('1592453306818', '', $paramFilter, "", "", "0");           //  ажил горилогч  нас
            $dashboardArr['rec2_8'] =  $this->fncRunDataview('1592453306832', '', $paramFilter, "", "", "0");           // cонгон шалгаруулалт
        }elseif($layoutCode == 'ceo'){
            $dashboardArr['ceo_1'] =  $this->fncRunDataview('1592453741474', '', $paramFilter, "", "", "0");
            $dashboardArr['ceo_2'] =  $this->fncRunDataview('1592453741503', '', $paramFilter, "", "", "0");
            $dashboardArr['ceo_3'] =  $this->fncRunDataview('1592453741518', '', $paramFilter, "", "", "0");
            $dashboardArr['ceo_4'] =  $this->fncRunDataview('1592453741532', '', $paramFilter, "", "", "0");
            $dashboardArr['ceo_5'] =  $this->fncRunDataview('1592453741546', '', $paramFilter, "", "", "0");
            $dashboardArr['ceo_6'] =  $this->fncRunDataview('1592453741560', '', $paramFilter, "", "", "0");
            $dashboardArr['ceo_7'] =  $this->fncRunDataview('1592453741574', '', $paramFilter, "", "", "0");
            $dashboardArr['ceo_8'] =  $this->fncRunDataview('1592453741588', '', $paramFilter, "", "", "0");
            $dashboardArr['ceo_9'] =  $this->fncRunDataview('1592453741602', '', $paramFilter, "", "", "0");
            $dashboardArr['ceo_10'] =  $this->fncRunDataview('1592453741488', '', $paramFilter, "", "", "0");
        }elseif($layoutCode == 'vp'){
            $dashboardArr['vp_1'] =  $this->fncRunDataview('1592453742133', '', $paramFilter, "", "", "0");
            $dashboardArr['vp_2'] =  $this->fncRunDataview('1592453742205', '', $paramFilter, "", "", "0");
            $dashboardArr['vp_3'] =  $this->fncRunDataview('1592453742220', '', $paramFilter, "", "", "0");
            $dashboardArr['vp_4'] =  $this->fncRunDataview('1592453742234', '', $paramFilter, "", "", "0");
            $dashboardArr['vp_5'] =  $this->fncRunDataview('1592453742248', '', $paramFilter, "", "", "0");
            $dashboardArr['vp_6'] =  $this->fncRunDataview('1592453742262', '', $paramFilter, "", "", "0");
            $dashboardArr['vp_7'] =  $this->fncRunDataview('1592453742276', '', $paramFilter, "", "", "0");
            $dashboardArr['vp_8'] =  $this->fncRunDataview('1592453742290', '', $paramFilter, "", "", "0");
            $dashboardArr['vp_9'] =  $this->fncRunDataview('1592453742304', '', $paramFilter, "", "", "0");
            $dashboardArr['vp_10'] =  $this->fncRunDataview('1592453742148', '', $paramFilter, "", "", "0");
            $dashboardArr['vp_11'] =  $this->fncRunDataview('1592453742162', '', $paramFilter, "", "", "0");
            $dashboardArr['vp_12'] =  $this->fncRunDataview('1592453742177', '', $paramFilter, "", "", "0");
            $dashboardArr['vp_13'] =  $this->fncRunDataview('1606810395911', '', $paramFilter, "", "", "0");
        }elseif($layoutCode == 'request'){
            $dashboardArr['req_1'] =  $this->fncRunDataview('1592453740588', '', $paramFilter, "", "", "0");      // хүсэлт төрөлөөр  
            $dashboardArr['req_2'] =  $this->fncRunDataview('1592453740658', '', $paramFilter, "", "", "0");      // P5Section2 - Хүсэлт төлөвөөр  
            $dashboardArr['req_3'] =  $this->fncRunDataview('1592453740673', '', $paramFilter, "", "", "0");      // P5Section3 - Цагийн хүсэлт төрлөөр  
            $dashboardArr['req_4'] =  $this->fncRunDataview('1592453740687', '', $paramFilter, "", "", "0");      // P5Section4 - Цагийн хүсэлт хугацааагаар  
            $dashboardArr['req_5'] =  $this->fncRunDataview('1592453740701', '', $paramFilter, "", "", "0");      // P5Section5 - Цагийн хүсэлт ажилтнаар  
            $dashboardArr['req_6'] =  $this->fncRunDataview('1592453740716', '', $paramFilter, "", "", "0");      // P5Section6 - Тодорхойлолтын хүсэлт төрлөөр  
            $dashboardArr['req_7'] =  $this->fncRunDataview('1592453740730', '', $paramFilter, "", "", "0");      // P5Section7 - Арга хэмжээ төрлөөр  
            $dashboardArr['req_8'] =  $this->fncRunDataview('1592453740744', '', $paramFilter, "", "", "0");      // P5Section8 - Гаргасан зөрчлийн төрлөөр
            $dashboardArr['req_9'] =  $this->fncRunDataview('1592453740758', '', $paramFilter, "", "", "0");      // Арга хэмжээ ажилтнаар  
            $dashboardArr['req_10'] =  $this->fncRunDataview('1592453740602', '', $paramFilter, "", "", "0");     // P5Section10 - Арга хэмжээ албан тушааалаар
            $dashboardArr['req_11'] =  $this->fncRunDataview('1592453740616', '', $paramFilter, "", "", "0");    // P5Section11 - Гаралт төрлөөр
            $dashboardArr['req_12'] =  $this->fncRunDataview('1592453740630', '', $paramFilter, "", "", "0");   // P5Section12 - Гаралт шалтгаанаар  
            $dashboardArr['req_13'] =  $this->fncRunDataview('1592453740644', '', $paramFilter, "", "", "0");  // P5Section13 - Орон тооны хүсэлт - Төрлөөр 
            $dashboardArr['req_14'] =  $this->fncRunDataview('1592453741366', '', $paramFilter, "", "", "0");     // P5Section14 - Орон тооны хүсэлт-Шинэ,Шилжүлж авсан 
        }elseif($layoutCode == 'relation2'){
            $dashboardArr['relation2_1'] =  $this->fncRunDataview('1592453743843', '', $paramFilter, "", "", "0");
            $dashboardArr['relation2_2'] =  $this->fncRunDataview('1592453743857', '', $paramFilter, "", "", "0");
            $dashboardArr['relation2_3'] =  $this->fncRunDataview('1592453743872', '', $paramFilter, "", "", "0");
            $dashboardArr['relation2_4'] =  $this->fncRunDataview('1592453743886', '', $paramFilter, "", "", "0");
            $dashboardArr['relation2_5'] =  $this->fncRunDataview('1592453743900', '', $paramFilter, "", "", "0");
            $dashboardArr['relation2_6'] =  $this->fncRunDataview('1592453743914', '', $paramFilter, "", "", "0");
            $dashboardArr['relation2_7'] =  $this->fncRunDataview('1592453743928', '', $paramFilter, "", "", "0");
            $dashboardArr['relation2_8'] =  $this->fncRunDataview('1592453743942', '', $paramFilter, "", "", "0");
            $dashboardArr['relation2_9'] =  $this->fncRunDataview('1592453743956', '', $paramFilter, "", "", "0");
            $dashboardArr['relation2_10'] = $this->fncRunDataview('16127926569262', '', $paramFilter, "", "", "0");
        }elseif($layoutCode == 'relation1'){
            $dashboardArr['relation1_1'] =  $this->fncRunDataview('1592453742903', '', $paramFilter, "", "", "0");
            $dashboardArr['relation1_2'] =  $this->fncRunDataview('1592453742975', '', $paramFilter, "", "", "0");
            $dashboardArr['relation1_3'] =  $this->fncRunDataview('1592453742990', '', $paramFilter, "", "", "0");
            $dashboardArr['relation1_4'] =  $this->fncRunDataview('1592453743004', '', $paramFilter, "", "", "0");
            $dashboardArr['relation1_5'] =  $this->fncRunDataview('1592453743018', '', $paramFilter, "", "", "0");
            $dashboardArr['relation1_6'] =  $this->fncRunDataview('1592453743032', '', $paramFilter, "", "", "0");
            $dashboardArr['relation1_7'] =  $this->fncRunDataview('1592453743047', '', $paramFilter, "", "", "0");
            $dashboardArr['relation1_8'] =  $this->fncRunDataview('1592453743061', '', $paramFilter, "", "", "0");
            $dashboardArr['relation1_9'] =  $this->fncRunDataview('1592453743075', '', $paramFilter, "", "", "0");
            $dashboardArr['relation1_10'] =  $this->fncRunDataview('1592453742917', '', $paramFilter, "", "", "0");
            $dashboardArr['relation1_11'] =  $this->fncRunDataview('1592453742931', '', $paramFilter, "", "", "0");
            $dashboardArr['relation1_12'] =  $this->fncRunDataview('1592453742945', '', $paramFilter, "", "", "0");
            $dashboardArr['relation1_13'] =  $this->fncRunDataview('1592453742959', '', $paramFilter, "", "", "0");
        }elseif($layoutCode == 'theme2'){
            $dashboardArr['cloud_1'] =  $this->fncRunDataview('1590028187864998', '', $paramFilter, "", "", "0");
            $dashboardArr['cloud_2'] =  $this->fncRunDataview('1590028520285338', '', $paramFilter, "", "", "0");
            $dashboardArr['cloud_3'] =  $this->fncRunDataview('1585575586672746', '', $paramFilter, "", "", "0");
            $dashboardArr['cloud_4'] =  $this->fncRunDataview('1585909978812', '', $paramFilter, "", "", "0");
            $dashboardArr['cloud_5'] =  $this->fncRunDataview('1590113791149681', '', $paramFilter, "", "", "0");
            $dashboardArr['cloud_6'] =  $this->fncRunDataview('1590130138246872', '', $paramFilter, "", "", "0");
            $dashboardArr['cloud_7'] =  $this->fncRunDataview('', '', $paramFilter, "", "", "0");
        }elseif($layoutCode == 'headmanagment'){
            $dashboardArr['main1_1'] =  $this->fncRunDataview('1592475627552381', '', $paramFilter, "", "", "0"); 
            $dashboardArr['main2_2'] =  $this->fncRunDataview('1592453116656', '', $paramFilter, "", "", "0");          // ажилтаны тоо бүлгээр
            $dashboardArr['main2_4'] =  $this->fncRunDataview('1592453116684', '', $paramFilter, "", "", "0");          // ажилтаны түүх
            $dashboardArr['main2_3'] =  $this->fncRunDataview('1592453116670', '', $paramFilter, "", "", "0");          // хүйсийн харьцаа
            $dashboardArr['main2_5'] =  $this->fncRunDataview('1592453116698', '', $paramFilter, "", "", "0");          // албан тушаал зэрэглэлээр 
            $dashboardArr['ceo_2'] =  $this->fncRunDataview('1592453741503', '', $paramFilter, "", "", "0");
        }elseif($layoutCode == 'finance'){
            $dashboardArr['fin_1'] =  $this->fncRunDataview('1590028187864998', '', $paramFilter, "", "", "0");
            $dashboardArr['fin_2'] =  $this->fncRunDataview('1590028520285338', '', $paramFilter, "", "", "0");
            $dashboardArr['fin_3'] =  $this->fncRunDataview('1585575586672746', '', $paramFilter, "", "", "0");
            $dashboardArr['fin_4'] =  $this->fncRunDataview('1585909978812', '', $paramFilter, "", "", "0");
            $dashboardArr['fin_5'] =  $this->fncRunDataview('1590113791149681', '', $paramFilter, "", "", "0");
            $dashboardArr['fin_6'] =  $this->fncRunDataview('1590130138246872', '', $paramFilter, "", "", "0");
            $dashboardArr['fin_7'] =  $this->fncRunDataview('', '', $paramFilter, "", "", "0");
        } elseif($layoutCode == 'performance'){
            $dashboardArr['main1_1'] =  $this->fncRunDataview('1605706109854', '', $paramFilter, "", "", "0"); 
            $dashboardArr['main1_2'] =  $this->fncRunDataview('1605706112538', '', $paramFilter, "", "", "0");
            $dashboardArr['main1_3'] =  $this->fncRunDataview('1605706112614', '', $paramFilter, "", "", "0");
            $dashboardArr['main1_5'] =  $this->fncRunDataview('1605706112734', '', $paramFilter, "", "", "0");
            $dashboardArr['main1_6'] =  $this->fncRunDataview('1605706112790', '', $paramFilter, "", "", "0");
            $dashboardArr['main1_7'] =  $this->fncRunDataview('1605706112856', '', $paramFilter, "", "", "0");
        } elseif($layoutCode == 'performance2'){
            $dashboardArr['main1_1'] =  $this->fncRunDataview('1605706113450', '', $paramFilter, "", "", "0"); 
            $dashboardArr['main1_2'] =  $this->fncRunDataview('1605706109900', '', $paramFilter, "", "", "0");
            $dashboardArr['main1_3'] =  $this->fncRunDataview('1605706113133', '', $paramFilter, "", "", "0");
            $dashboardArr['main1_5'] =  $this->fncRunDataview('1605706113286', '', $paramFilter, "", "", "0");
            $dashboardArr['main1_6'] =  $this->fncRunDataview('1605706113390', '', $paramFilter, "", "", "0");
            $dashboardArr['main1_7'] =  $this->fncRunDataview('1605706113450', '', $paramFilter, "", "", "0");
        }else{
            $dashboardArr['hr_pos_1'] =  $this->fncRunDataview('1592017252231563', '', $paramFilter, "", "", "0");
            $dashboardArr['hr_pos_2'] =  $this->fncRunDataview('1591862881948', '', $paramFilter, "", "", "0");
            $dashboardArr['hr_pos_3'] =  $this->fncRunDataview('1591862882178', '', $paramFilter, "", "", "0");
            $dashboardArr['hr_pos_4'] =  $this->fncRunDataview('1591862882268', '', $paramFilter, "", "", "0");
            $dashboardArr['hr_pos_5'] =  $this->fncRunDataview('1591862882323', '', $paramFilter, "", "", "0");
            $dashboardArr['hr_pos_6'] =  $this->fncRunDataview('1591862882673', '', $paramFilter, "", "", "0");
            $dashboardArr['hr_pos_7'] =  $this->fncRunDataview('', '', $paramFilter, "", "", "0");
        }



        return $dashboardArr;
    }
    /* summery dashboard */
    
    public function sendMailModel($emailTo, $emailToCc, $emailSubject, $emailBody) {
        
        $emailBody = html_entity_decode($emailBody);

        $emailBodyContent = file_get_contents('middleware/views/metadata/dataview/form/email_templates/selectionRows.html');
        $emailBodyContent = str_replace('{htmlTable}', $emailBody, $emailBodyContent);
            
        includeLib('Mail/PHPMailer/v2/PHPMailerAutoload');

        $mail = new PHPMailer();
        $mail->CharSet = 'UTF-8';
        $mail->isSMTP();
        $mail->SMTPDebug = 0;
        
        if (!defined('SMTP_USER')) {
                
            $mail->SMTPAuth = false;
            $mail->SMTPOptions = array(
                'ssl' => array(
                    'verify_peer' => false,
                    'verify_peer_name' => false,
                    'allow_self_signed' => true
                )
            );

        } else {
            $mail->SMTPAuth = (defined('SMTP_AUTH') ? SMTP_AUTH : true);
            
            if ($mail->SMTPAuth) {
                $mail->Username = SMTP_USER; 
                $mail->Password = SMTP_PASS; 
            } else {
                $mail->SMTPOptions = array(
                    'ssl' => array(
                        'verify_peer' => false,
                        'verify_peer_name' => false,
                        'allow_self_signed' => true
                    )
                );
            }
        }
        
        if (defined('SMTP_SSL_VERIFY') && !SMTP_SSL_VERIFY) {
            
            $mail->SMTPOptions = array(
                'ssl' => array(
                    'verify_peer' => false,
                    'verify_peer_name' => false,
                    'allow_self_signed' => true
                )
            );
        }
        
        $mail->SMTPSecure = (defined('SMTP_SECURE') ? SMTP_SECURE : false);
        $mail->Host = SMTP_HOST;
        if (defined('SMTP_HOSTNAME') && SMTP_HOSTNAME) {
            $mail->Hostname = SMTP_HOSTNAME;
        }        
        $mail->Port = SMTP_PORT;
        $mail->setFrom(EMAIL_FROM, EMAIL_FROM_NAME);
        $mail->Subject = $emailSubject;
        $mail->isHTML(true);
        $mail->msgHTML($emailBodyContent);
        $mail->AltBody = 'Veritech ERP - ' . $emailSubject;
        
        $response = array('status' => 'success', 'message' => Lang::line('msg_mail_success'));
        
        $emailToArr = array_map('trim', explode(';', rtrim($emailTo, ';')));
        $emailList = $emailToArr;
        
        if (!empty($emailToCc)) {
            
            $emailToCcArr = array_map('trim', explode(';', rtrim($emailToCc, ';')));
            $emailList = array_merge($emailList, $emailToCcArr);
        }        
        
        foreach ($emailList as $email) {
                
            $email = trim($email);
            
            if ($email) {
                
                $mail->addAddress($email);

                if (!$mail->send()) {
                    $response = array('status' => 'error', 'message' => $mail->ErrorInfo);
                }
            }

            $mail->clearAllRecipients();
        }

        return $response;
    }    
}