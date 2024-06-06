<?php if (!defined('_VALID_PHP')) exit('Direct access to this location is not allowed.');

/**
 * Mdintegration Class 
 * 
 * @package     IA PHPframework
 * @subpackage	Middleware
 * @category	Bank Integration
 * @author	B.Och-Erdene <och-erdene@veritech.mn>
 * @link	http://www.interactive.mn/bank/integration
 */

class Mdintegration extends Controller {
    
    private static $viewPath = 'middleware/views/integration/';

    public function __construct() {
        parent::__construct();
    }
    
    public static function getBankId($bankCode) {
        
        $id = null;
        
        if ($bankCode == 'golomt') {
            
            if ($bankId = Config::getFromCache('CONFIG_BANK_ID_GOLOMT')) {
                $id = $bankId;
            } else {
                $id = 150000;
            }
            
        } elseif ($bankCode == 'khan') {
            
            if ($bankId = Config::getFromCache('CONFIG_BANK_ID_KHAN')) {
                $id = $bankId;
            } else {
                $id = 500000;
            }
        }
        
        return $id;
    }

    public function GolomtBilling() {
        
        $entityBody = file_get_contents('php://input');
        
        if ($entityBody) {
            
            $currDate = Date::currentDate('Y_m_d_H_i_s').'_'.getUID();
            @file_put_contents(BASEPATH.'log/bnk_'.$currDate.'.log', $entityBody); 
            
            try {
                
                $value = Xml::createArray($entityBody);

            } catch (Exception $ex) {

                echo $ex->getMessage(); exit;
            }

            if (isset($value['notif']) && is_array($value['notif']) 
                && count($value['notif']) > 0 && isset($value['notif']['transaction']) 
                && count($value['notif']['transaction']) > 0) {

                $transaction = '';
                $currentDate = Date::currentDate('Y-m-d H:i:s');
                $datas       = array();

                if (!array_key_exists(0, $value['notif']['transaction'])) {
                    $datas[0] = $value['notif']['transaction'];
                } else {
                    $datas = $value['notif']['transaction'];
                }
                
                $bankId = Mdintegration::getBankId('golomt');

                foreach ($datas as $d) {
                    
                    $posteddate = isset($d['posteddate']) ? $d['posteddate'] : $currentDate;
                    
                    $data = array(
                        'ID'          => getUID(),
                        'BANK_ID'     => $bankId,
                        'BILL_DATE'   => $posteddate,
                        'TYPE'        => $d['type'],
                        'ACCOUNT'     => $d['account'],
                        'JOURNAL_ID'  => $d['journalid'],
                        'AMOUNT'      => $d['amount'], 
                        'DESCRIPTION' => Str::remove_doublewhitespace($d['description']), 
                        'RATE'        => 1, 
                        'IS_AUTO'     => 1 
                    );
                    
                    $result = $this->model->saveBilling($data);
                    
                    if ($result['status'] == 'error') {
                        echo $result['message']; exit;
                    }

                    $transaction .= '<transaction>'.$d['journalid'].'</transaction>';
                }

                header('Content-Type: application/xml');

                $xml = '<?xml version="1.0" encoding="utf-8"?>';
                $xml .= '<notif>';
                    $xml .= $transaction;
                $xml .= '</notif>';

                echo $xml; exit;
            } 
            
        } else {
            echo 'Error parsing the XML string.'; exit;
        }
    }
    
    public function getBankAccountBalance() {
        Auth::handleLogin();
        $result = $this->model->getBankAccountBalanceModel();
        echo json_encode($result); exit;
    }
    
    public function getBankAccountInfo() {
        Auth::handleLogin();
        $result = $this->model->getBankAccountInfoModel();
        echo json_encode($result); exit;
    }
    
    public function getBankTransactionStatement() {
        Auth::handleLogin();
        $result = $this->model->getBankTransactionStatementModel();
        echo json_encode($result); exit;
    }
    
    public function importBankStatement() {
        
        Auth::handleLogin();
        
        $postData  = Input::postData();
        $paramData = Arr::changeKeyLower(Input::param($postData));
        $bankCode  = strtolower($paramData['bankcode']);
        
        if ($bankCode == 'golomt') {
            
            $response = $this->model->golomtBankImportStatement(array('WS_URL' => ''), $paramData);
            
        } elseif ($bankCode == 'khan') {
            
            $response = $this->model->khaanBankImportStatement(array('WS_URL' => ''), $paramData);
        }
        
        echo json_encode($response); exit;
    }
    
    public function getRowDataToEncryptHash() {
        $rowData = Input::post('rowData');
        $rowData['expiredate'] = Date::currentDate('Y-m-d H:i:s');
        
        $jsonStr = json_encode($rowData, JSON_UNESCAPED_UNICODE);
        
        echo Hash::encryption($jsonStr); exit;
    }
    
    public function getHelpHashStr() {
        
        $rowData = array('username' => 'togtokhsuren.ts', 'passwordhash' => '70a58c3ffd9a41b79e44d7522480da1beb2bacdf96d9330a56ad577bb073c085');
        $rowData['expiredate'] = Date::currentDate('Y-m-d H:i:s');
        
        $jsonStr = json_encode($rowData, JSON_UNESCAPED_UNICODE);
        $jsonStr = Hash::encryption($jsonStr);
        $jsonStr = str_replace(array('+', '='), array('tttnmhttt', 'ttttntsuttt'), $jsonStr);
        
        echo $jsonStr; exit;
    }
    
    public function getAuthorizationParam() {
        echo 'Loading...'; 
    }
    
    public function getLoanRequestResponse($bankCode = '') {
        
        if ($bankCode == '') {
            set_status_header(404);
        
            $err = Controller::loadController('Err');
            $err->index();
            
        } elseif ($bankCode == 'golomt') {
            
            $requestBody = file_get_contents('php://input');
            @file_put_contents('log/getLoanRequestResponseParam.log', "\n" . $requestBody, FILE_APPEND);
            
            $requestBody = @json_decode($requestBody, true);
            
            if (isset($requestBody['requestId'])) {
                
                $param = $requestBody;
                $param['bookDate'] = Date::currentDate('Y-m-d');
                $param['bookTypeId'] = 311;
                $param['scoringDate'] = Date::formatter($param['scoringDate'], 'Y-m-d H:i:s');
                
                $result = $this->ws->runResponse(GF_SERVICE_ADDRESS, 'callbackUrlResponse', $param); 
                
                if ($result['status'] != 'success') {
                    @file_put_contents('log/getLoanRequestResponse.log', "\n" . $this->ws->getResponseMessage($result), FILE_APPEND);
                }
            }
        }
    }

    public function callDocumentEvent() {
        
        $entityBody = file_get_contents('php://input');
        
        if ($entityBody) {
            
            $currentDate = Date::currentDate('Y-m-d H:i:s');
            @file_put_contents(BASEPATH.'log/mobicom_integration_' . Date::format('YmdHis', $currentDate) .'.txt', $entityBody); 
            
            try {
                
                parse_str($entityBody, $datas);
                $datas = (object) $datas;
                
            } catch (Exception $ex) {

                echo $ex->getMessage(); exit;
            }
            
            if (isset($datas->externalrecordid)) {
                $temp = $datas;
                (Array) $datas = array(); 
                array_push($datas, $temp);
            }
            
            if (isset($datas[0]) && is_array($datas) && count($datas) > 0) {

                foreach ($datas as $key => $d) {
                    
                    (String) $integration = '';
                    (String) $taskTypeId = '';
                    (String) $lifecycleTaskId = '';
                    (String) $assetgroupId = '';
                    
                    $pdata = array('bookdate' => $currentDate, 'objectId' => '1531119965119');
                    
                    includeLib('Utils/Functions');
                    $result = Functions::runProcess('CRM_AUTONUMBER_BP', $pdata);
                    $taskCode = isset($result['result']['result']) ? $result['result']['result'] : '';
                    
                    switch ($d->applicationid) {
                        case '14':
                            $taskTypeId = '14';/*-------------FRS-------------------*/
                            break;
                        case '15':
                            $taskTypeId = '15'; /*-------------RFC-------------------*/
                            break;
                        case '16':
                            //$taskTypeId = '16'; /*-------------SAR-------------------*/
                            break;
                        case '4':
                            $taskTypeId = '17'; /*-------------TTS-------------------*/
                            break;
                        default:
                            break;
                    }
                    
                    /* gesen 3n talbar irj bgaa esehiig shalgah */
                    //$d->subassetgroupname
                    //$d->assetgroupname
                    //$d->sitename
                    
                    if ($taskTypeId) {

                        $lifecycleTaskId = $this->db->GetOne("SELECT 
                                                                MAX(L.ID) AS LIFECYCLE_TASK_ID
                                                                FROM BLCM_LIFECYCLE_TASK L
                                                                INNER JOIN TM_TASK_TYPE T ON L.TASK_TYPE_ID = T.ID
                                                                WHERE T.ID = $taskTypeId ");
                        
                    }
                    
                    if (isset($d->assetgroupid)) {
                        $assetgroupId = $this->db->GetOne("SELECT ASSET_GROUP_ID FROM FA_ASSET_GROUP WHERE ASSET_GROUP_NAME = '" .$d->assetgroupid."'");
                        
                        if (!$assetgroupId) {
                            
                            $assetgroupCode = $this->db->GetOne("SELECT MAX(CODE) + 1 FROM FA_ASSET_GROUP");
                            $newassetgroupId = getUID();
                            
                            $data = array(
                                'ASSET_GROUP_ID' => $newassetgroupId,
                                'CODE' => $assetgroupCode,
                                'ASSET_GROUP_NAME' => isset($d->subassetgroupname) ? $d->subassetgroupname : $d->subassetgroupid,
                                'ASSET_GROUP_PARENT_ID' => isset($d->assetgroupname) ? $d->assetgroupname : $d->assetgroupid,
                                'IS_ACTIVE' => '1',
                            );
                            $result = $this->db->AutoExecute('FA_ASSET_GROUP', $data);
                            
                            if ($result) {
                                $assetgroupId = $newassetgroupId;
                            }
                        }
                    }
                    
                    $priorityid = null;
                    (Array) $diffDate = $dataRefAsset = array();
                    
                    if (isset($d->sitename)) {
                        
                        $dataRefAsset = $this->db->GetRow("SELECT 
                                                                F.ASSET_ID,
                                                                K.ID AS ASSET_KEY_ID
                                                            FROM FA_ASSET F
                                                            INNER JOIN IM_CHECK_KEY K ON F.ASSET_ID = K.ASSET_ID
                                                            WHERE LOWER(F.ASSET_NAME) = LOWER('" . $d->sitename ."')");
                        
                    }
                    
                    if (isset($d->startdate) && isset($d->enddate)) {
                        $sparam = array(
                                        'startDate' => Date::format('Y-m-d', $d->startdate), 
                                        'endDate' => Date::format('Y-m-d', $d->enddate), 
                                        'startTime' => '00:00', 
                                        'endTime' => '00:00'
                                    );
                        $diffDate = Functions::runProcess('mobDateDifference_004', $sparam);
                    }
                    
                    
                    if (isset($d->priorityid)) {
                        $priorityid = $this->db->GetOne("SELECT PRIORITY_ID FROM TM_TASK_PRIORITY where LOWER(PRIORITY_NAME) = lower('". $d->priorityid ."')");
                    }
                    
                    $data = array(
                        'TASK_ID' => getUID(),
                        'TASK_CODE' => $d->taskcode,
                        'TASK_NAME' => $d->taskname,
                        'PRIORITY_ID' => $priorityid,
                        'START_DATE' => isset($d->startdate) ? $d->startdate : '',
                        'END_DATE' => isset($d->enddate) ? $d->enddate : '',
                        'CREATED_DATE' => $currentDate,
                        'CREATED_USER_ID' => Ue::sessionUserKeyId(),
                        'IS_ACTIVE' => '1',
                        'WFM_STATUS_ID' => '1526272698320824',
                        'TASK_TYPE_ID' => $taskTypeId,
                        'DESCRIPTION' => $d->description,
                        'LIFECYCLE_TASK_ID' => $lifecycleTaskId,
                        'FUNDING_SOURCE_ID' => $assetgroupId,
                        'REF_ASSET_ID' => isset($dataRefAsset['ASSET_ID']) ? $dataRefAsset['ASSET_ID'] : '',
                        'REF_ASSET_KEY_ID' => isset($dataRefAsset['ASSET_KEY_ID']) ? $dataRefAsset['ASSET_KEY_ID'] : '',
                        'EXTERNAL_RECORD_ID' => isset($d->externalrecordid) ? $d->externalrecordid : '',
                        'DIFF_DAY' => isset($diffDate['result']['diffdays']) ? $diffDate['result']['diffdays'] : '0',
                        'DIFF_HOUR' => isset($diffDate['result']['diffhours']) ? $diffDate['result']['diffhours'] : '0', 
                        'DIFF_MINUTE' => isset($diffDate['result']['diffminutes']) ? $diffDate['result']['diffminutes'] : '0', 

                    );
                    
                    $result = $this->model->saveMobicomIntegration($data);
                    
                    if ($result['status'] == 'error') {
                        echo $result['message']; exit;
                    }

                    $integration .= '{"id":"'.$d->externalrecordid.'"}';
                    
                    if (sizeof($datas)-1 > $key) {
                        $integration .= ',';
                    }
                }

                header('Content-Type: application/json');
                $xml = '[';
                    $xml .= $integration;
                $xml .= ']';

                echo $xml; exit;
            } 
            
        } else {
            echo 'Error parsing the JSON string.'; exit;
        }
    }
    
    public function getSapLocationSetName($locationSetName = '') {
        
        if (Config::isCode('mobiSapUrl')) {
            $response = json_encode(array('status' => 'error', 'code' => 'curl', 'message' => "Холбогдож чадсангүй! <br>"));
            $url = Config::getFromCache('mobiSapUrl') .'/sap/opu/odata/sap/ZGW_SM_FL_SRV/FunctionalLocationSet(\''. $locationSetName .'\')?$format=json';
            $ch = curl_init($url);

            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array('Cache-Control: no-cache', 'Content-Length: 0')); //, 'Content-Type: text/html'
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);

            $output = curl_exec($ch);
            $err = curl_error($ch);

            curl_close($ch);

            if ($err) {
                $response = json_encode(array('status' => 'error', 'code' => 'curl', 'message' => "Холбогдож чадсангүй! <br>" . "error: <br>" . $err));
            } else {
                if ($output) {
                    header('Content-Type: application/json');
                    $response = $output;
                }
            }
        } else {
            $response = json_encode(array('status' => 'error', 'code' => 'curl', 'message' => "mobiSapUrl тохиргоо олдсонгүй <br>"));
        }
        
        echo $response; exit;
    }

    public function sapEquipmentDtlBySiteName() {

        if (Config::isCode('mobiSapUrl')) {
            $listName = Input::post('listName', 'FLEqui_ListSet');
            $locationSetName = Input::post('locationSetName');

            $response = json_encode(array('status' => 'error', 'code' => 'curl', 'message' => "Холбогдож чадсангүй! <br>"));
            $url = Config::getFromCache('mobiSapUrl') . '/sap/opu/odata/sap/ZGW_SM_FL_SRV/'. $listName .'?$filter=Pltxt%20eq%20\''. $locationSetName .'\'&$format=json';
            $ch = curl_init($url);

            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array('Cache-Control: no-cache', 'Content-Length: 0')); //, 'Content-Type: text/html'
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);

            $output = curl_exec($ch);
            $err = curl_error($ch);

            curl_close($ch);

            if ($err) {
                $response = json_encode(array('status' => 'error', 'code' => 'curl', 'message' => "Холбогдож чадсангүй! <br>" . "error: <br>" . $err));
            } else {
                if ($output) {
                    header('Content-Type: application/json');
                    $response = $output;
                }
            }
        } else {
            $response = json_encode(array('status' => 'error', 'code' => 'curl', 'message' => "mobiSapUrl тохиргоо олдсонгүй <br>"));
        }

        echo $response; exit;
    }
    
    public function saveAirSms($airSmsParam = null, $tryIt = '') {
        
        $response = array(
            'status' => 'error',
            'message' => 'Хадгалах өгөгдөл олдсонгүй'
        );
        
        try {
            
            $sessionUserKeyId = null;
            $currentDate = Date::currentDate();
            
            $airSmsData = ($airSmsParam) ? $airSmsParam : $_POST['air_message'];
            $airArrData = preg_split('/\r\n|\r|\n/', $airSmsData); 
            /* $airSmsData = ($airSmsParam) ? $airSmsParam : $_POST['air_message'];
            $airSmsDataReplace = preg_replace("/\n/", "#break#", $airSmsData);
            $airArrData = explode('#break#', $airSmsDataReplace); */
            
            $index = 1;
            if (issetParam($airArrData[0])) {

                $templateCode = explode(';', $airArrData[0]);
                if (issetParam($tryIt) === '') {

                    $otherTemplateCode = '';
                    if (issetParamArray($airArrData)) {
                        foreach ($airArrData as $key => $row) {
                            $tic = strpos($row, 'EMD');
                            if ($tic !== false && $tic === 0) {
                                $otherTemplateCode = '7D';
                            }
                        }
                    }
                    
                    if ($otherTemplateCode) {
                        $airSmsData = preg_replace('/'. $templateCode[1] .'/', $otherTemplateCode, $airSmsData, 1);
                        self::saveAirSms($airSmsData, $otherTemplateCode);
                    }

                }

                if (!isset($templateCode[1])) {
                    throw new Exception("Процесс команд тохируулаагүй байна!"); 
                }
                
                $templateCodeStr = issetParam($tryIt) !== '' ? $tryIt : $templateCode[1];
                $query = $this->db->GetRow("SELECT
                                                ID,
                                                QUERY_HDR,
                                                QUERY_DTL,
                                                QUERY_DTL_KEY,
                                                UPDATE_QUERY
                                            FROM AIR_MSG_TEMPLATE 
                                            WHERE CODE = '". $templateCodeStr ."'");

                if ($query) {

                    $templateArr = $this->db->GetAll($query['QUERY_HDR']);
                    $templateDtlArr = $this->db->GetAll($query['QUERY_DTL']);
                    $templateDtlKeyArr = $this->db->GetAll($query['QUERY_DTL_KEY']);
                    $updateArr = Arr::groupByArrayOnlyRow($templateArr, 'FACT6', 'FACT18');
                    
                    if ($templateArr) {

                        $smsId = getUID();

                        (Array) $inputParam = array(
                                                    'ID' => $smsId, 
                                                    'FILE_PATH' => '',
                                                    'TEMPLATE_BOOK_ID' => $query['ID'],
                                                    'CREATED_USER_ID' => $sessionUserKeyId,
                                                    'CREATED_DATE' => $currentDate
                                                );
                        (Array) $manyTagArr = $manyTagTemp = $tempManyArr = array();
                        (Array) $keyManyArr = $rcManyArr = $rcTagTemp = $rcTagArr = array();
                        (Array) $dtlInManyArr = $dtlInTagTemp = $dtlInTagArr = $dtlInMainTagTemp = $dtlInAllTagTemp = array();

                        $result = $this->db->AutoExecute('AIR_MESSAGE_HEADER', $inputParam);

                        if ($result) {

                            foreach ($templateArr as $row) {

                                if ($row['FACT13'] !== '1' && $row['FACT9'] === '1' && !in_array($row, $manyTagTemp)) {
                                    array_push($manyTagArr, $row['FACT1']);
                                    array_push($manyTagTemp, $row);
                                } 
                                elseif ($row['FACT13'] === '1' && $row['FACT9'] === '1' && !in_array($row, $manyTagTemp)) {
                                    array_push($dtlInManyArr, $row['FACT1']);
                                    array_push($dtlInTagTemp, $row);

                                    if ($row['FACT14'] === '1') {
                                        array_push($dtlInMainTagTemp, $row['FACT1']);
                                    }
                                    if (!in_array($row['FACT1'], $dtlInAllTagTemp)) {
                                        array_push($dtlInAllTagTemp, $row['FACT1']);
                                    }
                                }

                            }
            
                            $this->db->UpdateClob('AIR_MESSAGE_HEADER', 'AIR_MESSAGE', $airSmsData, " ID = '$smsId' ");
                            $inputParam = array();

                            foreach ($airArrData as $airKey => $airRow) {
                                
                                foreach ($templateArr as $tKey => $tRow) {

                                    $ticket = strpos($airRow, $tRow['FACT1']);

                                    if ($ticket !== false && $ticket === (int) $tRow['FACT12']) {

                                        if (!in_array($tRow['FACT1'], $dtlInManyArr) && !in_array($tRow['FACT1'], $manyTagArr)) {

                                            $explodeRow = explode(Str::sanitize($tRow['FACT10']), $airRow);

                                            if (isset($explodeRow[$tRow['FACT2']])) {
                                                $inputParam[$tRow['FACT6']] = (isset($tRow['FACT4']) && $tRow['FACT4']) ? Str::sanitize(substr($explodeRow[$tRow['FACT2']], $tRow['FACT3'], $tRow['FACT4'])) : Str::sanitize(substr($explodeRow[$tRow['FACT2']], $tRow['FACT3']));
                                            }

                                        } 
                                        elseif (in_array($tRow['FACT1'], $dtlInManyArr)) {
                                            if (!in_array($airRow, $dtlInTagArr)) {
                                                array_push($dtlInTagArr, $airRow);
                                            }
                                        } else {
                                            if (!in_array($airRow, $tempManyArr)) {
                                                array_push($tempManyArr, $airRow);
                                            }
                                        }
                                    }
                                }

                                if ($templateDtlArr) {
                                    $ticket = strpos($airRow, $tRow['FACT1']);

                                    foreach ($templateDtlArr as $tKey1 => $tRow1) {

                                        $ticket = strpos($airRow, $tRow1['FACT1']);

                                        if ($ticket !== false && $ticket === (int) $tRow1['FACT12'] && !in_array($airRow, $rcManyArr)) {
                                            array_push($rcManyArr, $airRow);
                                        } 
                                    }

                                }

                                if ($templateDtlKeyArr) {

                                    foreach ($templateDtlKeyArr as $tKey1 => $tRow1) {

                                        $ticketKey = strpos($airRow, $tRow1['FACT1']);

                                        if ($ticketKey !== false && $ticketKey === (int) $tRow1['FACT12'] && !in_array($airRow, $keyManyArr)) {
                                            array_push($keyManyArr, $airRow);
                                        } 
                                    }

                                }
                                
                            }

                            (Array) $inParamDtl = $dtlInAllTagTem = array();
                            (Int) $ccindex = $dindex = $hindex = 0;

                            foreach ($dtlInTagArr as $airKey => $airRow) {
                                $tiket = false;
                                $strinTag = '';
                                foreach ($dtlInAllTagTemp as $tRow) {
                                    $ticket = strpos($airRow, $tRow);
                                    if ($ticket !== false) {
                                        $tiket = true;
                                        $strinTag = $tRow;
                                    }
                                }
                                if ($tiket && !in_array($strinTag, $dtlInAllTagTem)) {
                                    array_push($dtlInAllTagTem, $strinTag);
                                }
                            }
                            
                            $indexI = 1;
                            foreach ($dtlInTagArr as $airKey => $airRow) {
                                
                                foreach ($dtlInTagTemp as $tRow) {
                                    
                                    $ticket = strpos($airRow, $tRow['FACT1']);

                                    if ($ticket !== false && $ticket === (int) $tRow['FACT12'] && $tRow['FACT1']) {

                                        $explodeRow = explode(Str::sanitize($tRow['FACT10']), $airRow);
                                        
                                        if (isset($explodeRow[$tRow['FACT2']])) {
                                            $inParamDtl[$ccindex][$tRow['FACT6']] = (isset($tRow['FACT4']) && $tRow['FACT4']) ? Str::sanitize(substr($explodeRow[$tRow['FACT2']], $tRow['FACT3'], $tRow['FACT4'])) : Str::sanitize(substr($explodeRow[$tRow['FACT2']], $tRow['FACT3']));
                                        }
                                    }

                                }
                                
                                if ($indexI === sizeof($dtlInAllTagTem)) {
                                    $indexI = 1;
                                    $ccindex++;
                                } else {
                                    $indexI++;
                                }

                            }
                            
                            if ($inParamDtl) {

                                foreach ($inParamDtl as $paramDl) {

                                    foreach ($paramDl as $key => $pp) {
                                        if (issetParam($updateArr[$key]) !== '') {
                                            $result = $this->db->Execute($updateArr[$key], array($pp, $smsId));
                                        }
                                    }
                                    $paramDl['ID'] = getUID();
                                    $paramDl['MESSAGE_HDR_ID'] = $smsId;
                                    $paramDl['CREATED_USER_ID'] = $sessionUserKeyId;
                                    $paramDl['CREATED_DATE'] = $currentDate;
                                    $paramDl['TAG_NAME'] = isset($dtlInMainTagTemp[0]) ? $dtlInMainTagTemp[0] : 'I-';

                                    $this->db->AutoExecute('AIR_MESSAGE_DETAIL', $paramDl);
                                }

                            }

                            if ($rcManyArr && $templateDtlArr) {

                                foreach ($rcManyArr as $airKey => $airRow1) {

                                    $inputParamDtl = array(
                                                        'ID' => getUID(), 
                                                        'MESSAGE_HDR_ID' => $smsId,
                                                        'CREATED_USER_ID' => $sessionUserKeyId,
                                                        'CREATED_DATE' => $currentDate,
                                                        'ORDER_NUM' => $dindex++,
                                                    );


                                    $tagEqual = explode(';', $airRow1);
                                    $inputParamDtl['TAG_EQUAL'] = isset($tagEqual[1]) ? Str::sanitize($tagEqual[1]) : '';
                                    $airRow = isset($tagEqual[0]) ? $tagEqual[0] : '';
                                    $ticketIn = false;

                                    foreach ($templateDtlArr as $tRow) {
                                        $inputParamDtl['TAG_NAME']  = $tRow['FACT1'];
                                        $ticket = strpos($airRow, $tRow['FACT1']);

                                        if ($ticket !== false && $ticket === (int) $tRow['FACT12'] && $tRow['FACT1']) {

                                            $tagEqual = explode(';', $airRow);
                                            $explodeRow = explode(Str::sanitize($tRow['FACT10']), $airRow);
                                            $paymentTypeId = Str::sanitize(substr($explodeRow[1], 0, 1));

                                            $inputParamDtl['PAYMENT_TYPE'] = $paymentTypeId;

                                            switch ($inputParamDtl['PAYMENT_TYPE']) {
                                                case 'B':
                                                    $inputParamDtl['PAYMENT_TYPE'] = 'BANK';
                                                    break;
                                                case 'C':
                                                    $inputParamDtl['PAYMENT_TYPE'] = 'CASH';
                                                    break;
                                                case 'E':
                                                    $inputParamDtl['PAYMENT_TYPE'] = 'BARTER';
                                                    break;
                                                case 'P':
                                                    $inputParamDtl['PAYMENT_TYPE'] = 'POS';
                                                    break;
                                                default :
                                                    $inputParamDtl['PAYMENT_TYPE'] = '';
                                                    break;
                                            }

                                            if (isset($explodeRow[$tRow['FACT2']])) {
                                                $ticketIn = true;

                                                $inputParamDtl[$tRow['FACT6']] = (isset($tRow['FACT4']) && $tRow['FACT4']) ? Str::sanitize(substr($explodeRow[$tRow['FACT2']], $tRow['FACT3'], $tRow['FACT4'])) : Str::sanitize(substr($explodeRow[$tRow['FACT2']], $tRow['FACT3']));
                                                $inputParamDtl[$tRow['FACT6'] . '_' . $paymentTypeId] = ($inputParamDtl[$tRow['FACT6']]) ? Str::sanitize($inputParamDtl[$tRow['FACT6']]) : '';
                                            }

                                        }

                                    }

                                    if ($ticketIn) {
                                        $smsDtlData = $this->db->GetRow("SELECT ID, PAYMENT_TYPE FROM AIR_MESSAGE_DETAIL WHERE MESSAGE_HDR_ID = '". $smsId ."' AND TAG_EQUAL = '". $inputParamDtl['TAG_EQUAL'] ."'");

                                        if ($smsDtlData) {
                                            if (Str::upper($inputParamDtl['PAYMENT_TYPE']) === 'D' || Str::upper($inputParamDtl['PAYMENT_TYPE']) === 'M') {
                                                unset($inputParamDtl['PAYMENT_TYPE']);
                                            } else {
                                                if ($inputParamDtl['PAYMENT_TYPE']) {
                                                    $inputParamDtl['PAYMENT_TYPE'] = (isset($smsDtlData['PAYMENT_TYPE']) && $smsDtlData['PAYMENT_TYPE']) ? ($smsDtlData['PAYMENT_TYPE'] . '+' . $inputParamDtl['PAYMENT_TYPE'])  : $inputParamDtl['PAYMENT_TYPE'];
                                                } else {
                                                    $inputParamDtl['PAYMENT_TYPE'] = (isset($smsDtlData['PAYMENT_TYPE']) && $smsDtlData['PAYMENT_TYPE']) ? $smsDtlData['PAYMENT_TYPE']  : $inputParamDtl['PAYMENT_TYPE'];
                                                }
                                            }

                                            $result = $this->db->AutoExecute('AIR_MESSAGE_DETAIL', $inputParamDtl, 'UPDATE', " ID = '". $smsDtlData['ID'] ."'");
                                        } else {
                                            $result = $this->db->AutoExecute('AIR_MESSAGE_DETAIL', $inputParamDtl);
                                        }

                                    }
                                }
                            }

                            $hindex = 1;
                            foreach ($tempManyArr as $airKey => $airRow) {
                                foreach ($manyTagTemp as $aKey => $tRow) {

                                    if ($aKey%2 == 1) {
                                        $hindex = $hindex + 1;
                                    }

                                    $ticket = strpos($airRow, $tRow['FACT1']);

                                    if ($ticket !== false && $ticket === (int) $tRow['FACT12'] && $tRow['FACT1']) {

                                        $explodeRow = explode(Str::sanitize($tRow['FACT10']), $airRow);

                                        if (isset($explodeRow[$tRow['FACT2']])) {
                                            $inputParamDtl = array(
                                                        'ID' => getUID(), 
                                                        'MESSAGE_HDR_ID' => $smsId,
                                                        'CREATED_USER_ID' => $sessionUserKeyId,
                                                        'CREATED_DATE' => $currentDate,
                                                        'ORDER_NUM' => $hindex
                                                    );

                                            $inputParamDtl['TAG_NAME'] = $tRow['FACT1'];
                                            $inputParamDtl['TAG_VALUE'] = (isset($tRow['FACT4']) && $tRow['FACT4']) ? Str::sanitize(substr($explodeRow[$tRow['FACT2']], $tRow['FACT3'], $tRow['FACT4'])) : Str::sanitize(substr($explodeRow[$tRow['FACT2']], $tRow['FACT3']));

                                            $this->db->AutoExecute('AIR_MESSAGE_DETAIL', $inputParamDtl);
                                        }

                                        if ($tRow['FACT1'] === 'TAX-') {

                                            $airRowReplace = explode('TAX-', $airRow);
                                            $explodeAirSmsTax = explode(Str::sanitize($tRow['FACT10']), $airRowReplace[1]);

                                            foreach ($explodeAirSmsTax as $taxRow) {
                                                $ticketTax = strpos(Str::upper($taxRow), 'PD');
                                                if ($ticketTax === false) {
                                                    
                                                    $explodeTaxRow = explode(' ', $taxRow);
                                                    $inputParamDtl = array(
                                                                        'ID' => getUID(), 
                                                                        'MESSAGE_HDR_ID' => $smsId,
                                                                        'CREATED_USER_ID' => $sessionUserKeyId,
                                                                        'CREATED_DATE' => $currentDate,
                                                                        'ORDER_NUM' => $index
                                                                    );

                                                    foreach ($explodeTaxRow as $eTaxRow) {
                                                        if ($eTaxRow) {
                                                            if (strlen($eTaxRow) > 3) {
                                                                $inputParamDtl['TAG_NAME'] = 'TAX-';
                                                                if(trim(Str::upper($eTaxRow)) === 'EXEMPT') {
                                                                    $inputParamDtl['TAG_VALUE'] = '0';    
                                                                } else {
                                                                    $inputParamDtl['TAG_VALUE'] = Str::sanitize(substr($eTaxRow, '3'));
                                                                }                                                                
                                                                // $inputParamDtl['TAG_NAME'] = 'TAX-';
                                                                // $inputParamDtl['TAG_VALUE'] = Str::sanitize(substr($eTaxRow, '3'));
                                                            } else {
                                                                $inputParamDtl['TAG_TYPE'] = Str::sanitize($eTaxRow);
                                                            }
                                                        }
                                                    }

                                                    if (isset($inputParamDtl['TAG_NAME'])) {
                                                        $this->db->AutoExecute('AIR_MESSAGE_DETAIL', $inputParamDtl);
                                                    }
                                                    
                                                }
                                            }

                                        }
                                    }

                                }

                            }

                            $inputHeader = $inputParam;
                            if ($inputParam) {

                                if (isset($query['UPDATE_QUERY']) && $query['UPDATE_QUERY']) {
                                    $updateData = $this->db->GetAll("SELECT * FROM (" .$query['UPDATE_QUERY'].") t0 WHERE t0.ID = '$smsId'");
                                    if ($updateData) {
                                        foreach ($updateData as $row) {

                                            $dataUp = array(
                                                'BSP_PRICE' => $row['BSP_PRICE'],
                                                'TAX_AMOUNT' => $row['TAX'],
                                                'RECEIVABLE' => $row['RECEIVABLE'],
                                                'REVEBUE_LOSS' => $row['REVEBUE_LOSS'],
                                                'PAYABLE_AMOUNT' => $row['PAYABLE_AMOUNT'],
                                                'PAYMENT_TYPE' => $row['PAYMENT_TYPE'],
                                                'PAID_AMOUNT_B' => $row['PAID_AMOUNT_B'],
                                                'PAID_AMOUNT_C' => $row['PAID_AMOUNT_C'],
                                                'PAID_AMOUNT_E' => $row['PAID_AMOUNT_E'],
                                                'PAID_AMOUNT_P' => $row['PAID_AMOUNT_P'],
                                                'PAID_AMOUNT_D' => $row['PAID_AMOUNT_D'],
                                                'PAID_AMOUNT_M' => $row['PAID_AMOUNT_M'],
                                                'PAID_DATE_B' => $row['PAID_DATE_B'],
                                                'PAID_DATE_C' => $row['PAID_DATE_C'],
                                                'PAID_DATE_E' => $row['PAID_DATE_E'],
                                                'PAID_DATE_P' => $row['PAID_DATE_P'],
                                                'BANK_NAME_B' => $row['BANK_NAME_B'],
                                                'BANK_NAME_P' => $row['BANK_NAME_P'],
                                                'WFM_STATUS_ID' => $row['WFM_STATUS_ID'],         
                                                'EQUIVALENT_MNT' => $row['EQUIVALENT_MNT'],
                                                'FARE' => $row['FARE'],
                                                'TOTAL' => $row['TOTAL'],
                                                'TAX' => $row['TAX'],
                                                'COMMISSION' => $row['COMMISSION'],                                                        
                                                'CANCELLATION_PENALTY' => $row['CANCELLATION_PENALTY'],                                                                                                      
                                            );

                                            $hdrUpdateData = array(
                                                'WFM_STATUS_ID' => $row['WFM_STATUS_ID'],
                                                'ITINERARY' => $row['ITINERARY'],
                                                'SEGMENT_NUMBER' => $row['SEGMENT_NUMBER']
                                            );

                                            $this->db->AutoExecute("AIR_MESSAGE_DETAIL", $dataUp, "UPDATE", "ID = '". $row['SMS_DTL_ID'] ."'");
                                            $this->db->AutoExecute('AIR_MESSAGE_HEADER', $hdrUpdateData, 'UPDATE', " ID = '$smsId' ");

                                        }
                                    }
                                }

                                $documentTypeCode = Str::upper($templateCodeStr);
                                switch ($documentTypeCode) {
                                    case 'RF':
                                        $rfDtlData = $this->db->GetRow("SELECT t1.* FROM AIR_MESSAGE_HEADER t0 
                                                            INNER JOIN AIR_MESSAGE_DETAIL t1 ON t0.ID = t1.MESSAGE_HDR_ID
                                                            WHERE t0.ID = '$smsId'");

                                        if ($rfDtlData) {
                                            $smsBfData = $this->db->GetRow("SELECT t0.* FROM AIR_MESSAGE_HEADER t0 
                                                                            INNER JOIN AIR_MESSAGE_DETAIL t1 ON t0.ID = t1.MESSAGE_HDR_ID
                                                                            WHERE t1.DOCUMENT_NUMBER = '". $rfDtlData['DOCUMENT_NUMBER'] ."' AND UPPER(t0.DOCUMENT_TYPE) = '7A' AND t0.ID <> '$smsId' ORDER BY t1.ID DESC");

                                            if (isset($smsBfData['VALIDATING_AIRLINE']) && $smsBfData['VALIDATING_AIRLINE']) {
                                                $this->db->AutoExecute('AIR_MESSAGE_HEADER', array('VALIDATING_AIRLINE' => $smsBfData['VALIDATING_AIRLINE'], 'RF_MESSAGE_HDR_ID' => $smsBfData['ID']), "UPDATE", "ID = '$smsId'");
                                            }

                                        }

                                        break;
                                    case 'MA':
                                        $maDtlData = $this->db->GetRow("SELECT t1.* FROM AIR_MESSAGE_HEADER t0 
                                                            INNER JOIN AIR_MESSAGE_DETAIL t1 ON t0.ID = t1.MESSAGE_HDR_ID
                                                            WHERE t0.ID = '$smsId'");

                                        if ($maDtlData) {

                                            $smsBfData = $this->db->GetRow("SELECT t0.* FROM AIR_MESSAGE_HEADER t0 
                                                                            INNER JOIN AIR_MESSAGE_DETAIL t1 ON t0.ID = t1.MESSAGE_HDR_ID
                                                                            WHERE t1.DOCUMENT_NUMBER = '". $maDtlData['DOCUMENT_NUMBER'] ."' AND UPPER(t0.DOCUMENT_TYPE) = '7A' AND t0.ID <> '$smsId' ORDER BY t1.ID DESC");

                                            if (isset($smsBfData['VALIDATING_AIRLINE']) && $smsBfData['VALIDATING_AIRLINE']) {

                                                $smsBfDtlData = $this->db->GetAll("SELECT 
                                                                                        DISTINCT 
                                                                                        t1.ID AS HEADER_ID,
                                                                                        t2.* FROM AIR_MESSAGE_HEADER t0 
                                                                                    INNER JOIN AIR_MESSAGE_DETAIL t1 ON t0.ID = t1.MESSAGE_HDR_ID
                                                                                    INNER JOIN AIR_MESSAGE_DETAIL t2 ON t1.MESSAGE_HDR_ID = t2.MESSAGE_HDR_ID
                                                                                    WHERE t1.DOCUMENT_NUMBER = '". $maDtlData['DOCUMENT_NUMBER'] ."' AND UPPER(t0.DOCUMENT_TYPE) = '7A' AND t0.ID <> '$smsId' 
                                                                                    ORDER BY t1.ID DESC");
                                                $dataUpdateMa = array(
                                                    'BOOKED_AGENT_SIGN' => $smsBfData['BOOKED_AGENT_SIGN'],
                                                    'COMMISSION_PERCENT' => $smsBfData['COMMISSION_PERCENT'],
                                                    'ISSUED_CURRENCY' => $smsBfData['ISSUED_CURRENCY'],
                                                    'I_D_INDICATOR' => $smsBfData['I_D_INDICATOR'],
                                                    'CORPORATE_CODE' => $smsBfData['CORPORATE_CODE'],
                                                    'ITINERARY' => $smsBfData['ITINERARY'],
                                                    'SEGMENT_NUMBER' => $smsBfData['SEGMENT_NUMBER'],
                                                    'RF_MESSAGE_HDR_ID' => $smsBfData['ID']
                                                );

                                                $this->db->AutoExecute('AIR_MESSAGE_HEADER', $dataUpdateMa, "UPDATE", "ID = '$smsId'");

                                                if ($smsBfDtlData) {

                                                    foreach ($smsBfDtlData as $maRow) {

                                                        $dataMa = array(
                                                            'ID' => getUID(),
                                                            'MESSAGE_HDR_ID' => $smsId,
                                                            'CREATED_USER_ID' => $sessionUserKeyId,
                                                            'CREATED_DATE' => $currentDate,
                                                            'TAG_NAME' => $maRow['TAG_NAME'],
                                                            'TAG_TYPE' => $maRow['TAG_TYPE'],
                                                            'TAG_VALUE' => $maRow['TAG_VALUE'],
                                                            'ORDER_NUM' => $maRow['ORDER_NUM'],
                                                            'TAG_EQUAL' => $maRow['TAG_EQUAL'],
                                                            'PAID_DATE_B' => $maRow['PAID_DATE_B'],
                                                            'PAID_DATE_C' => $maRow['PAID_DATE_C'],
                                                            'PAID_DATE_E' => $maRow['PAID_DATE_E'],
                                                            'PAID_DATE_P' => $maRow['PAID_DATE_P'],
                                                            'BANK_NAME_B' => $maRow['BANK_NAME_B'],
                                                            'BANK_NAME_P' => $maRow['BANK_NAME_P'],
                                                            'PAYMENT_TYPE' => $maRow['PAYMENT_TYPE'],
                                                        );

                                                        $this->db->AutoExecute('AIR_MESSAGE_DETAIL', $dataMa);
                                                    }

                                                    $this->db->AutoExecute('AIR_MESSAGE_HEADER', array('IS_ACTIVE' => '0'), "UPDATE", "ID = '". $smsBfData['ID'] ."'");
                                                    $this->db->AutoExecute('AIR_MESSAGE_DETAIL', array('IS_ACTIVE' => '0'), "UPDATE", "MESSAGE_HDR_ID = '". $smsBfData['ID'] ."'");

                                                }
                                            }

                                        }

                                        break;
                                }

                                $this->db->AutoExecute('AIR_MESSAGE_HEADER', $inputParam, 'UPDATE', " ID = '$smsId' ");
                                $this->db->UpdateClob('AIR_MESSAGE_HEADER', 'AIR_MESSAGE_JSON', json_encode($inputParam), " ID = '$smsId' ");

                                foreach ($inputParam as $key => $pp) {
                                    if (issetParam($updateArr[$key]) !== '') {
                                        $result = $this->db->Execute($updateArr[$key], array($pp, $smsId));
                                    }
                                }
                                if ($keyManyArr && $templateDtlKeyArr) {

                                    (Array) $inputParamKey1 = $inputParamKey = array();
                                    (Int) $keyIndex = 0;
                                    foreach ($keyManyArr as $airKey => $airRow) {
                                        $inputParamKey = array(
                                            'ID' => getUID(), 
                                            'MESSAGE_HDR_ID' => $smsId,
                                            'CREATED_USER_ID' => $sessionUserKeyId,
                                            'CREATED_DATE' => $currentDate,
                                            'ORDER_NUM' => $keyIndex++,
                                        );

                                        foreach ($templateDtlKeyArr as $tKey => $tRow) {
                                            $inputParamKey['TAG_NAME'] = 'H-KEY-'; //$tRow['FACT1']
                                            $ticket = strpos($airRow, $tRow['FACT1']);

                                            if ($ticket !== false && $ticket === (int) $tRow['FACT12']) {

                                                $explodeRow = explode(Str::sanitize($tRow['FACT10']), $airRow);

                                                if (isset($explodeRow[$tRow['FACT2']])) {
                                                    $inputParamKey[$tRow['FACT6']] = (isset($tRow['FACT4']) && $tRow['FACT4']) ? Str::sanitize(substr($explodeRow[$tRow['FACT2']], $tRow['FACT3'], $tRow['FACT4'])) : Str::sanitize(substr($explodeRow[$tRow['FACT2']], $tRow['FACT3']));
                                                }

                                            }

                                        }

                                        $this->db->AutoExecute('AIR_MESSAGE_DETAIL', $inputParamKey);
                                        /* array_push($inputParamKey1, $inputParamKey); */

                                    }

                                }

                                $response = array(
                                    'status' => 'success',
                                    'message' => Lang::line('msg_save_success')
                                );
                            }

                            /**
                             * Insert air message log data
                             */
                            (Array) $inputParam = array(
                                'ID' => getUID(), 
                                'FILE_NAME' => 'Insert from system',
                                'UPLOAD_DATE' => $currentDate,
                                'UPLOAD_USER_ID' => $sessionUserKeyId,
                                'IP_ADDRESS' => get_client_ip()
                            );
                            $this->db->AutoExecute('AIR_MESSAGE_LOG', $inputParam);        
                            $this->db->UpdateClob('AIR_MESSAGE_LOG', 'FILE_CONTENT', base64_encode($airSmsData), 'ID = '.$inputParam['ID']);                               
                            
                            if (isset($query['UPDATE_QUERY']) && $query['UPDATE_QUERY']) {
                                $updateData = $this->db->GetRow("SELECT 
                                                                    DISTINCT 
                                                                    t0.ID,  
                                                                    t0.AMOUNT, 
                                                                    t0.ITINERARY,
                                                                    t0.BALANCE 
                                                                FROM (" .$query['UPDATE_QUERY'].") t0 
                                                                    WHERE t0.ID = '$smsId'");
                                
                                if ($updateData) {

                                    $hdrUpdateData = array(
                                        'AMOUNT_TOTAL' => $updateData['AMOUNT'],
                                        'BALANCE_AMOUNT' => $updateData['BALANCE'],
                                        'ITINERARY' => $updateData['ITINERARY']
                                    );
                                    
                                    $corporateCode = $this->db->GetOne("SELECT CORPORATE_CODE FROM AIR_MESSAGE_HEADER WHERE ID = '$smsId'");
                                    
                                    if ($corporateCode) {
                                        
                                        $totalAmount = $this->db->GetOne("SELECT t1.BALANCE_AMOUNT
                                                                            FROM (
                                                                                SELECT 
                                                                                    MAX(ID) AS MIN_ID,
                                                                                    CORPORATE_CODE
                                                                                FROM AIR_MESSAGE_HEADER
                                                                                WHERE ID <> '$smsId'
                                                                                GROUP BY CORPORATE_CODE
                                                                            ) t0
                                                                            inner join AIR_MESSAGE_HEADER t1 ON t0.MIN_ID = t1.ID        
                                                                            WHERE t1.CORPORATE_CODE = '$corporateCode' ");
                                        
                                        if ($totalAmount) {
                                            $hdrUpdateData['AMOUNT_TOTAL'] = $totalAmount;
                                        }
                                        
                                    }
                                    $this->db->AutoExecute('AIR_MESSAGE_HEADER', $hdrUpdateData, 'UPDATE', " ID = '$smsId' ");

                                    if (isset($query['UPDATE_QUERY']) && $query['UPDATE_QUERY']) {
                                        $updateData = $this->db->GetAll("SELECT * FROM (" .$query['UPDATE_QUERY'].") t0 WHERE t0.ID = '$smsId'");
                                        if ($updateData) {
                                            foreach ($updateData as $row) {

                                                $dataUp = array(          
                                                    'EQUIVALENT_MNT' => $row['EQUIVALENT_MNT'],
                                                    'FARE' => $row['FARE'],
                                                    'TOTAL' => $row['TOTAL'],
                                                    'TAX' => $row['TAX'],
                                                    'COMMISSION' => $row['COMMISSION'],                                                        
                                                    'COMMISSION_PERCENT' => $row['COMMISSION_PERCENT'],                                                        
                                                    'CANCELLATION_PENALTY' => $row['CANCELLATION_PENALTY'],                                                                                                    
                                                );

                                                $this->db->AutoExecute("AIR_MESSAGE_DETAIL", $dataUp, "UPDATE", "ID = '". $row['SMS_DTL_ID'] ."'");

                                            }
                                        }
                                    }                                    

                                }
                            }

                            foreach ($inputHeader as $key => $pp) {
                                if (issetParam($updateArr[$key]) !== '') {
                                    $result = $this->db->Execute($updateArr[$key], array($pp, $smsId));
                                }
                            }
                        }

                    } 
                    else {
                        throw new Exception( "<strong>" . $templateCodeStr . "</strong> sms-ны загвар олдсонгүй!"); 

                    }

                } 
                else {
                    throw new Exception( "<strong>" . $templateCodeStr . "</strong> sms тохиргоо хийгдээгүй байна!"); 
                }
            }
            else {
                throw new Exception("Процесс команд тохируулаагүй байна!"); 
            }
            
        } catch (Exception $e) {
            $response = array('status' => 'warning', 'message' => $e->getMessage());
        }
        
        if ($airSmsParam) { 
            if ($response['status'] === 'success') {
                return 'yes';
            } else {
                return isset($response['message']) ? $response['message'] : 'Өгөгдөл орсонгүй';
            }
            
        } else {
            if ($response['status'] === 'success') {
                echo 'Success';

            } else {
                echo json_encode($response); exit;
            }
        }
        
    }
    
    public function saveAirSmsSystem() {
        
        $response = array(
            'status' => 'error',
            'message' => 'Хадгалах өгөгдөл олдсонгүй'
        );
        
        try {
            
            $sessionUserKeyId = Ue::sessionUserKeyId();
            $currentDate = Date::currentDate();

            $fileDataArr = Input::fileData();
            $postData = Input::postData();
            
            $response = array(
                'status' => 'success',
                'message' => ''
            );
            
            foreach ($fileDataArr['airSmsFile']['name'] as $fileKey => $fileData) {
                
                $newFName   = 'airsms_' . getUID();
                $fileExtension = strtolower(substr($fileDataArr['airSmsFile']['name'][$fileKey], strrpos($fileDataArr['airSmsFile']['name'][$fileKey], '.') + 1));
                $fileName   = $newFName . '.' . $fileExtension;
                $filePath   = UPLOADPATH . 'airsms/';

                if (!is_dir($filePath)) {
                    mkdir($filePath, 0777, true);
                }
        
                FileUpload::SetFileName($fileName);
                FileUpload::SetTempName($fileDataArr['airSmsFile']['tmp_name'][$fileKey]);
                FileUpload::SetUploadDirectory($filePath);
                FileUpload::SetValidExtensions(explode(',', 'txt'));
                FileUpload::SetMaximumFileSize(FileUpload::GetConfigFileMaxSize());
                $uploadResult  = FileUpload::UploadFile();

                if ($uploadResult && !file_exists(URL . $filePath . $fileName)) {
                    $airSmsData = file_get_contents($filePath . $fileName);
                    $resultD = self::saveAirSms($airSmsData);
                    if ($resultD !== 'yes') {
                        $response['message'] .= '<br> <strong>Error: </strong>' . $fileDataArr['airSmsFile']['name'][$fileKey] . ' <br><p style="padding:10px;">' . $resultD . '</p>';
                    } else {
                        $response['message'] .= '<br> <strong>Success: </strong>' . $fileDataArr['airSmsFile']['name'][$fileKey] . ' <br><p style="padding:10px;">' . Lang::line('msg_save_success') . '</p>';
                    }
                    
                } else {
                    throw new Exception("Процесс команд тохируулаагүй байна!"); 
                }
                
            }
            
        } catch (Exception $e) {
            $response = array('status' => 'warning', 'message' => $e->getMessage());
        }
        
        echo json_encode($response); exit;
        
    }
 
    public function getTransdepDataIntegration($param = '') {
        
        if (Config::isCode('transdepServiceUrl')) {
            $getData = Input::getData();
            $params = '';
            foreach ($getData as $key => $get) {
                switch ($key) {
                    case 'url': break;
                    case 'methodName':
                        $params = $get . '?';

                        break;

                    default:
                        $key = ($key === '_register') ? 'register' : $key;
                        $params .=  $key . '=' . urlencode($get)  . '&';
                        break;
                }
            }
            
            $response = json_encode(array('status' => 'error', 'code' => 'curl', 'message' => "Холбогдож чадсангүй! <br>"));
            $url = Config::getFromCache('transdepServiceUrl') . ($params ? $params : 'get_Dispatcher_by_date?direction_id=3&from_country=0&out_date=2019-04-11');
            
            $ch = curl_init();
            curl_setopt_array($ch, array(
                CURLOPT_URL => $url,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "GET"
              )
            );
            
            $output = curl_exec($ch);
            $err = curl_error($ch);

            curl_close($ch);
            
            if ($err) {
                $response = json_encode(array('status' => 'error', 'code' => 'curl', 'message' => "Холбогдож чадсангүй! <br>" . "error: <br>" . $err));
            } else {
                if ($output) {
                    $responseData = self::_isValidXML($output);
                    
                    if ($responseData) {
                        $response = json_decode($responseData, true);
                        if (is_array($response)) {
                            $response = json_encode(Arr::changeKeyLower($response));
                        } else {
                            $response = json_encode(array('status' => 'error', 'code' => 'curl', 'message' => "Хувиргалтанд алдаа гарлаа!"));
                        }
                    } else {
                        $response = json_encode(array('status' => 'error', 'code' => 'curl', 'message' => "Өгөгдөл олдсонгүй!", 'url' => $url));
                    }
                } else {
                    $response = json_encode(array('status' => 'error', 'code' => 'curl', 'message' => "Мэдээлэл авч чадсангүй!"));
                }
            }
        } else {
            $response = json_encode(array('status' => 'error', 'code' => 'curl', 'message' => "transdepServiceUrl тохиргоо олдсонгүй <br>"));
        }
        
        echo $response; exit;
    }
    
    public function getMongolbankValue($value = '') {
        
        $getData = Input::getData();
        $params = '';
        $example = 'USD|EUR|KRW|CNY|RUB|GBP|JPY';

        $url = "http://monxansh.appspot.com/xansh.json?currency=" . $value;
        
        $data = file_get_contents($url);
        $xchange = json_decode($data, true);


        if (!$xchange) {
            $response = json_encode(array('status' => 'error', 'code' => 'curl', 'message' => "Холбогдож чадсангүй! <br>" . "error: <br>"));
        } else {
            $response = json_encode($xchange);
        }
        
        echo $response; exit;
    }
    
    public function _isValidXML($xml) {
        $doc = @simplexml_load_string($xml);
        if ($doc) {
            return simplexml_load_string($xml); 
        } else {
            return false;
        }
    }
    
    public function getEbarimtVat() {
        
        $regNumber = Str::upper(Input::post('regNumber'));
        
        try {
            
            $regNumber = urlencode($regNumber);
            
            if ($posGetMerchantRegnoUrl = Config::getFromCache('posGetMerchantRegnoUrl')) {
                $url = $posGetMerchantRegnoUrl . $regNumber;
            } else {
                $url = 'http://info.ebarimt.mn/rest/merchant/info?regno=' . $regNumber;
            }
            
            $ch = curl_init($url);

            curl_setopt($ch, CURLOPT_HTTPHEADER, array('Accept: application/json', 'Content-Type: application/json;charset=UTF-8'));
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

            $str = curl_exec($ch);
            curl_close($ch); 

            echo $str;
            
        } catch (Exception $ex) {
            echo json_encode(array('name' => '', 'message' => $ex->getMessage()));
        }
    }

    public function getCirsIntegrationData() {
        
        $law_number = Input::post('law_number');
        $contract_number = Input::post('contract_number');
        
        try {
            
            $url = Config::getFromCacheDefault('cirsIntegrationUrl', null, '203.98.76.24/index.php/api/job');
            $curl = curl_init();
            curl_setopt_array($curl, array(
                CURLOPT_URL => $url,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS =>'{"law_number": "'. $law_number .'", "contract_number": "'. $contract_number .'"}',
                CURLOPT_HTTPHEADER => array(
                    'X_INTERACTIVE_USERNAME: Interactive',
                    'X_INTERACTIVE_PASSWORD: Interactive123456+',
                    'Content-Type: application/json',
                    'Cookie: PHPSESSID=at3dg54nnj7uolouvgd364vea1'
                ),
            ));

            $response = curl_exec($curl);
            $err = curl_error($curl);

            curl_close($curl);

            if ($err) {
                $response = $err;
                $result = array('status' => 'error', 'code' => 'curl', 'message' => $err);
            } else {
                $result = json_decode($response, true);
            }
            echo json_encode($result);
            
        } catch (Exception $ex) {
            echo json_encode(array('name' => '', 'message' => $ex->getMessage()));
        }
    }
    
    public function getCirsIntegrationDataById() {
        
        $id = Input::post('id');
        
        try {
            
            $url = 'http://203.98.76.24/index.php/api/images/' . $id;
            $curl = curl_init();
            curl_setopt_array($curl, array(
                CURLOPT_URL => $url,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'GET',
                CURLOPT_HTTPHEADER => array(
                    'X_INTERACTIVE_USERNAME: Interactive',
                    'X_INTERACTIVE_PASSWORD: Interactive123456+',
                    'Content-Type: application/json',
                    'Cookie: PHPSESSID=at3dg54nnj7uolouvgd364vea1'
                ),
            ));

            $response = curl_exec($curl);
            $err = curl_error($curl);

            curl_close($curl);

            if ($err) {
                $response = $err;
                $result = array('status' => 'error', 'code' => 'curl', 'message' => $err);
            } else {
                $result = json_decode($response, true);
            }
            echo json_encode($result);
            
        } catch (Exception $ex) {
            echo json_encode(array('name' => '', 'message' => $ex->getMessage()));
        }
    }
    
    public function qpaywebhook($invId = '') {        
        //@file_put_contents(BASEPATH.'log/qpayget.log', Date::currentDate()." ".json_encode($_GET)."\r\nGET\r\n".$id."\r\n", FILE_APPEND);
        
        $id = getUID();
        $params = array(
            'ID'               => $id, 
            'WEB_SERVICE_NAME' => "PHPWebhook Qpay", 
            'WEB_SERVICE_URL'  => '', 
            'CREATED_DATE'     => Date::currentDate(), 
            'USER_ID'          => ''
        );

        $this->db->AutoExecute('SYSINT_SERVICE_METHOD_LOG', $params);
        $this->db->UpdateClob('SYSINT_SERVICE_METHOD_LOG', 'RESPONSE_STRING', json_encode($_GET).' invId = '.$invId, 'ID = '.$id);         
        
        $param = array(
            'bill_no' => $invId
        );
        $result = $this->ws->runSerializeResponse(GF_SERVICE_ADDRESS, 'QPay_payment_check_post', $param);

        if ($result['status'] == 'success' && isset($result['result'])) {
            $param2 = $result['result'];
            $param2['orderNumber'] = $invId;
            
            if ($param2['payment_info']['payment_status'] === 'PAID') {
                $this->ws->runSerializeResponse(GF_SERVICE_ADDRESS, 'newIntQpayPayment_DV_002', $param2);
            } else {
                $this->ws->runSerializeResponse(GF_SERVICE_ADDRESS, 'payment_info_001', $param2);
            }
        }        
        echo "Success";
    }
    
    public function qpaywebhook2() {        
        //@file_put_contents(BASEPATH.'log/qpayget.log', Date::currentDate()." ".json_encode($_GET)."\r\nGET\r\n".$id."\r\n", FILE_APPEND);
        
        $id = getUID();
        $params = array(
            'ID'               => $id, 
            'WEB_SERVICE_NAME' => "PHPWebhook Qpay", 
            'WEB_SERVICE_URL'  => '', 
            'CREATED_DATE'     => Date::currentDate(), 
            'USER_ID'          => ''
        );

        $this->db->AutoExecute('SYSINT_SERVICE_METHOD_LOG', $params);
        $this->db->UpdateClob('SYSINT_SERVICE_METHOD_LOG', 'RESPONSE_STRING', json_encode($_GET), 'ID = '.$id);         
        
        $param2 = array(
            'salesorderid ' => $_GET['sender_invoice_no']
        );
        $result = $this->ws->runSerializeResponse(GF_SERVICE_ADDRESS, 'czSdmTransactionDtlDv_004', $param2); 

        // if ($result['status'] == 'success' && isset($result['result'])) {
        //     $param = array(
        //         'object_id ' => $result['result']['qpayCode']
        //     );
        //     $result = $this->ws->runSerializeResponse(GF_SERVICE_ADDRESS, 'qpay_v2_checkPayment', $param); 

        //     if ($result['status'] == 'success' && isset($result['result'])) {
        //         $param2 = $result['result'];
        //         $param2['orderNumber'] = $invId;
                
        //         if ($param2['payment_info']['payment_status'] === 'PAID') {
        //             $this->ws->runSerializeResponse(GF_SERVICE_ADDRESS, 'newIntQpayPayment_DV_002', $param2);
        //         } else {
        //             $this->ws->runSerializeResponse(GF_SERVICE_ADDRESS, 'payment_info_001', $param2);
        //         }

        //         echo "Success"; exit;
        //     }    
        // }


        echo "Error";
    }
    
    public function socialpaywebhook() {        
        $jsonBody = file_get_contents('php://input');        
        @file_put_contents(BASEPATH.'log/socialpayget.log', Date::currentDate()." ".$jsonBody."\r\nInput\r\n\r\n", FILE_APPEND);
        
        $id = getUID();
        $params = array(
            'ID'               => $id, 
            'WEB_SERVICE_NAME' => "PHPWebhook Socialpay", 
            'WEB_SERVICE_URL'  => '', 
            'CREATED_DATE'     => Date::currentDate(), 
            'USER_ID'          => ''
        );

        $this->db->AutoExecute('SYSINT_SERVICE_METHOD_LOG', $params);
        $this->db->UpdateClob('SYSINT_SERVICE_METHOD_LOG', 'RESPONSE_STRING', $jsonBody, 'ID = '.$id);        
        
        $param = json_decode($jsonBody, true);
        
        if ($param['errorCode'] === '000') {
            $this->ws->runSerializeResponse(GF_SERVICE_ADDRESS, 'newIntSocialPayPayment_DV_002', $param);        
        } else {
            $this->ws->runSerializeResponse(GF_SERVICE_ADDRESS, 'payment_info_v2_001', $param);        
        }
        echo "Success";
    }
    
    public function getEbarimtLoginName() {
        $response = json_encode(array('status' => 'error', 'code' => 'curl', 'message' => "Холбогдож чадсангүй!" ));
        $regNumber = Str::upper(Input::post('regNumber')); //УС86040817
        
        $username = 'notary_mn'; //$regNumber
        $password = "~{M6\'(R-Res_9JVS*{j?}Rv('?vd3"; //'nasaa@123'

        $url = 'https://auth.itc.gov.mn/auth/realms/ITC/protocol/openid-connect/token';// 'https://st.auth.itc.gov.mn/auth/realms/Staging/protocol/openid-connect/token';
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => 'client_id=vatps&grant_type=password&username='. urlencode($username) .'&password=' . urlencode($password),
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/x-www-form-urlencoded'
            ),
            CURLOPT_SSL_VERIFYPEER => false,
        ));

        $output = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            $response = json_encode(array('status' => 'error', 'code' => 'curl', 'message' => "Холбогдож чадсангүй! <br>" . "error: <br>" . $err));
        } else {
            if ($output) {
                $responseData = json_decode($output, true);

                if (isset($responseData['access_token'])) {
                    $accessToken = $responseData['access_token'];

                    $curl = curl_init();
                    curl_setopt_array($curl, array(
                        CURLOPT_URL =>  'https://api.ebarimt.mn/api/info/consumer/' . urlencode(Str::upper($regNumber)), //'https://api.itc.gov.mn/api/local/info/consumer/' . urlencode($regNumber), // 'https://st-api.ebarimt.mn/api/info/consumer/' . urlencode($regNumber),
                        CURLOPT_RETURNTRANSFER => true,
                        CURLOPT_ENCODING => '',
                        CURLOPT_MAXREDIRS => 10,
                        CURLOPT_TIMEOUT => 30,
                        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                        CURLOPT_CUSTOMREQUEST => 'GET',
                        CURLOPT_HTTPHEADER => array(
                            'Authorization: Bearer ' . $accessToken,
                            'Content-Type: application/x-www-form-urlencoded',
                            'Cookie: JSESSIONID=kgNuPQjuHrieB4jbKa8x5tcMdG0vk0JjdZ-OUBiB'
                        ),
                        CURLOPT_SSL_VERIFYPEER => false,
                    ));

                    $output = curl_exec($curl);
                    $err = curl_error($curl);

                    curl_close($curl);

                    if ($err) {
                        $response = json_encode(array('status' => 'error', 'code' => 'curl', 'message' => "Холбогдож чадсангүй! <br>" . "error: <br>" . $err));
                    } else {
                        if ($output) {
                            $responseData = json_decode($output, true);

                            if (isset($responseData['loginName'])) {
                                $response = json_encode(array('status' => 'success', 'customerno' => $responseData['loginName']));
                            } else {
                                $response = json_encode(array('status' => 'error', 'code' => 'curl', 'message' => "Өгөгдөл олдсонгүй!"));
                            }
                        } else {
                            $response = json_encode(array('status' => 'error', 'code' => 'curl', 'message' => "11Мэдээлэл авч чадсангүй!"));
                        }
                    }
                } else {
                    $response = json_encode(array('status' => 'error', 'code' => 'curl', 'message' => "Өгөгдөл олдсонгүй!"));
                }
            } else {
                $response = json_encode(array('status' => 'error', 'code' => 'curl', 'message' => "Мэдээлэл авч чадсангүй!"));
            }
        }
        
        echo $response;
    }

    public function gazarApi () {
        $postData = Input::postData();
        $curl = curl_init();
        curl_setopt_array($curl, 
            array(
                CURLOPT_URL => 'http://egazar.gov.mn:8060/api/person/parcel/by/id?parcel_id='. $postData['parcel_id'] .'&register_no=' . $postData['register_no'],
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_HTTPHEADER => array(
                    'Authorization: Basic bm90YXJ5Om5vdGFyeSFAIzEyMw==',
                ),
                /* CURLOPT_USERPWD => 'notary:notary!@#123', */
            )
        );

        $response = curl_exec($curl);
        $result = json_decode($response, true);
        curl_close($curl);

        if (issetParam($result['status']) === true) {
            echo json_encode(array('status' => 'success', 'data' => $result));
        } else {
            echo json_encode(array('status' => 'error', 'message' => 'Холбогдож чадсангүй'));
        }
    }
    
    /* Dan duudah function
    State ni unique random identifier
    Operation name ni duudah service-iin ner
    
    Oauth 2.0-oor zuvshuurul avaad door baigaa danRedirect function ruu data butsaadag (Zuvshuurultei ued)
    Zuvshuurulgui ued login hiisnii daraa zogsdog*/
    
    public function danredirect() {

        $state = Input::get('state');
        
        if (in_array($state, $_SESSION['danOauthState'])) {
            $code = Input::get('code');
            
            if ($code) {
                $this->view->state = $state;
                $this->view->response = $code;
                $this->view->render('token/result');
            }
            else{
                echo $respArr['error'];
            }
        } else{
            echo $respArr['error'];
        }
      
    }

    public function khalamjredirect() {
        
        $state = Input::get('state');
        if (in_array($state, $_SESSION['danOauthState'])) {
            $session_state = Input::get('session_state');
            $code = Input::get('code');
            
            if ($code) {
                
                $this->view->state = $state;
                $this->view->response = $services;
                $this->view->render('token/result');
                
            }
        }
    }
     
     public function bolorduran_replacement($text) {
        
       // var_dump($_POST["command"]);
       // var_dump($_POST["text"]);
       
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL =>  'https://api.bolor.net/v1.2/spell-suggest', 
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_HTTPHEADER => array(
                'Content-Type: text/plain',
                'token: 85ecaa430e184a4efae10db79442e135eb1a7652ff3024ef07d672397bb5865d'
            ),
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_POSTFIELDS => $text,
        ));

        $output = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);
        //echo $output;
        if ($err) {
            return null;
        } else {
            if ($output) {
                $responseData = json_decode($output, true);
                $index = 0;
                $matchs= array();
                foreach ($responseData as &$value) {
                    
                    $match = array('value' => $value);
                    $matchs[] = $match;
                    //array_push($matchs, (object)$match );
                    //$matchs->append($match );
                }

                if (count($responseData)>0) {
                    return $matchs;
                } else {
                    return null;
                }
            } else {
                return null;
            }
        }
     }

    public function bolorduran() {
       
        $text = $_POST["text"];
        
        $data = ['name'=>'Hardik', 'email'=>'itsolutionstuff@gmail.com'];
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL =>  'https://api.bolor.net/v1.2/spell-check', 
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_HTTPHEADER => array(
                'Content-Type: text/plain',
                'token: af119793e7f1db34aec5e963de127e1cb8ab63c352a2fbe06641c16fc198ed69'
            ),
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_POSTFIELDS => $text,
        ));

        $output = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);
        //echo $output;
        if ($err) {
            $response = json_encode(array('status' => 'error', 'code' => 'curl', 'message' => "Холбогдож чадсангүй! <br>" . "error: <br>" . $err));
        } else {
            if ($output) {
                $responseData = json_decode($output, true);
                $index = 0;
                $matchs= array();
                foreach ($responseData as &$value) {
                    
                    $match = array('message' => $value, 'replacements'=>self::bolorduran_replacement($value), 'index' => $index++, 'shortMessage' => "", 'length' => "0", 'offset' => "0");
                    $matchs[] = $match;
                    //array_push($matchs, (object)$match );
                    //$matchs->append($match );
                }

                if (count($responseData)>0) {
                    $response = json_encode(array('matches' => $matchs));
                } else {
                    $response = json_encode(array('status' => 'error', 'code' => 'curl', 'message' => "11"));
                }
            } else {
                $response = json_encode(array('status' => 'error', 'code' => 'curl', 'message' => "22"));
            }
        }

        echo $response;
    }

    public function getweatherFileIcon($id = '') {
        $data = array(
            array('id' => '2', 'name' => 'Цэлмэг', 'filepath' => 'assets/custom/img/weather/weather-01.png',),
            array('id' => '3', 'name' => 'Үүлэрхэг', 'filepath' => 'assets/custom/img/weather/weather-02.png',),
            array('id' => '5', 'name' => 'Багавтар үүлтэй', 'filepath' => 'assets/custom/img/weather/weather-02.png',),
            array('id' => '7', 'name' => 'Багавтар үүлтэй', 'filepath' => 'assets/custom/img/weather/weather-02.png',),
            array('id' => '9', 'name' => 'Үүлшинэ', 'filepath' => 'assets/custom/img/weather/weather-03.png',),
            array('id' => '10', 'name' => 'Үүлшинэ', 'filepath' => 'assets/custom/img/weather/weather-03.png',),
            array('id' => '20', 'name' => 'Үүл багаснa', 'filepath' => 'assets/custom/img/weather/weather-02.png',),
            array('id' => '23', 'name' => 'Ялимгүй цас', 'filepath' => 'assets/custom/img/weather/weather-04.png',),
            array('id' => '24', 'name' => 'Ялимгүй цас', 'filepath' => 'assets/custom/img/weather/weather-04.png',),
            array('id' => '27', 'name' => 'Ялимгүй хур тунадас', 'filepath' => 'assets/custom/img/weather/weather-04.png',),
            array('id' => '28', 'name' => 'Ялимгүй хур тунадас', 'filepath' => 'assets/custom/img/weather/weather-04.png',),
            array('id' => '60', 'name' => 'Бага зэргийн бороо', 'filepath' => 'assets/custom/img/weather/weather-06.png',),
            array('id' => '61', 'name' => 'Бороо', 'filepath' => 'assets/custom/img/weather/weather-06.png',),
            array('id' => '63', 'name' => 'Их бороо', 'filepath' => 'assets/custom/img/weather/weather-06.png',),
            array('id' => '65', 'name' => 'Хур тунадас', 'filepath' => 'assets/custom/img/weather/weather-06.png',),
            array('id' => '66', 'name' => 'Их хур тунадас', 'filepath' => 'assets/custom/img/weather/weather-06.png',),
            array('id' => '67', 'name' => 'Аадар их хур тунадас', 'filepath' => 'assets/custom/img/weather/weather-06.png',),
            array('id' => '68', 'name' => 'Их усархаг бороо', 'filepath' => 'assets/custom/img/weather/weather-06.png',),
            array('id' => '71', 'name' => 'Цас', 'filepath' => 'assets/custom/img/weather/weather-08.png',),
            array('id' => '73', 'name' => 'Их цас', 'filepath' => 'assets/custom/img/weather/weather-08.png',),
            array('id' => '75', 'name' => 'Аадар их цас', 'filepath' => 'assets/custom/img/weather/weather-08.png',),
            array('id' => '80', 'name' => 'Бага зэргийн аадар', 'filepath' => 'assets/custom/img/weather/weather-05.png',),
            array('id' => '81', 'name' => 'Бага зэргийн аадар', 'filepath' => 'assets/custom/img/weather/weather-05.png',),
            array('id' => '82', 'name' => 'Аадар бороо', 'filepath' => 'assets/custom/img/weather/weather-05.png',),
            array('id' => '83', 'name' => 'Аадар бороо', 'filepath' => 'assets/custom/img/weather/weather-05.png',),
            array('id' => '84', 'name' => 'Усархаг аадар бороо', 'filepath' => 'assets/custom/img/weather/weather-05.png',),
            array('id' => '85', 'name' => 'Усархаг аадар бороо', 'filepath' => 'assets/custom/img/weather/weather-05.png',),
            array('id' => '86', 'name' => 'Усархаг ширүүн аадар бороо', 'filepath' => 'assets/custom/img/weather/weather-05.png',),
            array('id' => '87', 'name' => 'Усархаг ширүүн аадар бороо', 'filepath' => 'assets/custom/img/weather/weather-05.png',),
            array('id' => '90', 'name' => 'Аянга цахилгаантай бага зэргийн аадар бороо', 'filepath' => 'assets/custom/img/weather/weather-05.png',),
            array('id' => '91', 'name' => 'Аянга цахилгаантай бага зэргийн аадар бороо', 'filepath' => 'assets/custom/img/weather/weather-05.png',),
            array('id' => '92', 'name' => 'Аянга цахилгаантай аадар бороо', 'filepath' => 'assets/custom/img/weather/weather-05.png',),
            array('id' => '93', 'name' => 'Аянга цахилгаантай аадар бороо', 'filepath' => 'assets/custom/img/weather/weather-05.png',),
            array('id' => '94', 'name' => 'Аянга цахилгаантай усархаг аадар бороо', 'filepath' => 'assets/custom/img/weather/weather-05.png',),
            array('id' => '95', 'name' => 'Аянга цахилгаантай усархаг аадар бороо', 'filepath' => 'assets/custom/img/weather/weather-05.png',),
            array('id' => '96', 'name' => 'Аянга цахилгаантай усархаг ширүүн аадар бороо', 'filepath' => 'assets/custom/img/weather/weather-05.png',),
            array('id' => '97', 'name' => 'Аянга цахилгаантай усархаг ширүүн аадар бороо', 'filepath' => 'assets/custom/img/weather/weather-05.png',)
        );
        $data = Arr::groupByArrayOnlyRow($data, 'id', false);
        $response = $data;

        if ($id) {
            $response = isset($data[$id]) ? $data[$id]['filepath'] : 'assets/custom/img/weather/weather-01.png';
        }

        return $response;
    }
    
    public function getForecast5day($cityName = 'Улаанбаатар') {
        $currentDate = Date::currentDate('y_m_d');

        $cache = phpFastCache();
        $data = $cache->get('bpForecast5day_' . $currentDate);

        if ($data == null  && Config::getFromCache('noUseTsagAgaarApi') !== '1') {

            $prevDate = date('y_m_d',strtotime("-1 days"));
            @unlink(Mdcommon::getCacheDirectory()."/*/bp/bpForecast5day_".$prevDate.".txt");

            $url = 'http://tsag-agaar.gov.mn/forecast_xml';
            $ch = curl_init($url);

            curl_setopt($ch, CURLOPT_HTTPHEADER, array('Accept: application/xml', 'Content-Type: application/xml;charset=UTF-8'));
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

            $str = curl_exec($ch);
            if (curl_errno($ch)) {
                return null;
            }            
            curl_close($ch);             

            if ($str) {
                $data = Xml::createArray($str);

                if ($data) {
                    $cache->set('bpForecast5day_' . $currentDate, $data, '144000000');
                }
            }            
        }

        (array) $mainData = array();
        if (isset($data['xml']['forecast5day'])) {
            foreach ($data['xml']['forecast5day'] as $key => $row) {
                if (isset($row['city']) && $row['city'] === $cityName && isset($row['data']['weather'])) {
                    foreach ($row['data']['weather'] as $row) {
                        $row['filepath'] = self::getweatherFileIcon($row['phenoIdDay']);
                        array_push($mainData, $row);
                    }
                }
            }
        }

        return $mainData;
    }
    
    public function mssSignature () {
        try {
            if (!Input::post('phoneNumber')) {
                throw new Exception("PhoneNumber хоосон байна!"); 
            }

            $curl = curl_init();
            $phoneNumber = Input::post('phoneNumber');
            $username = Config::getFromCacheDefault('MssSignatureUser', null, '5296722-ap');
            $password = Config::getFromCacheDefault('MssSignaturePass', null, 'LiuIudOz4lbLolI886qd');
            $userPass = base64_encode($username . ':' . $password);
            $url = Config::getFromCacheDefault('MssSignatureUrl', null, 'https://10.10.50.163:9061/rest/service');

            curl_setopt_array($curl, array(
                CURLOPT_URL => $url,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_SSL_VERIFYHOST => 0,
                CURLOPT_SSL_VERIFYPEER => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS =>'{ 
                "MSS_SignatureReq":{ 
                    "AdditionalServices":[ 
                        {
                                "Description": "http://uri.etsi.org/TS102204/v1.1.2#validate"
                            },
                            {
                                "Description": "http://www.methics.fi/KiuruMSSP/v5.0.0#signingCertificate"
                            },
                            {
                                "Description": "http://mss.ficom.fi/TS102204/v1.0.0#userLang",
                                "UserLang": {
                                    "Value": "MN"
                                }
                            }
                    ],
                    "DataToBeDisplayed":{ 
                        "Data":"Та гарын үсгээ оруулна уу",
                        "Encoding":"UTF-8",
                        "MimeType":"text/plain"
                    },
                    "DataToBeSigned":{ 
                        "Data":"data",
                        "Encoding":"UTF-8",
                        "MimeType":"text/plain"
                    },
                    "MessagingMode":"synch",
                    "MobileUser":{ 
                        "MSISDN":"976'. $phoneNumber .'"
                    },
                    "SignatureProfile":"http://alauda.mobi/nonRepudiation",
                    "MSS_Format": "http://www.methics.fi/KiuruMSSP/v3.2.0#PKCS1"
                }
                }',
                CURLOPT_HTTPHEADER => array(
                    'Content-Type: application/json',
                    'Authorization: Basic ' . $userPass
                ),
            ));
            
            $response = curl_exec($curl);       
            $err = curl_error($curl);
            curl_close($curl);
            $registerNumber = '';
            
            if (!is_dir(UPLOADPATH . 'temp')) {
                mkdir(UPLOADPATH . 'temp', 0777, true);
            }

            if ($err) {
                $response = array('status' => 'error', 'message' => $err);
            } else {
                $response = json_decode($response, true);
                if (!issetParam($response['Fault'])) {
                    $response['cert_data'] = array();
                    if (issetParamArray($response['MSS_SignatureResp']['ServiceResponses'][0]['SigningCertificate']['Certificates'])) {
                        $tmp = array();
                        foreach ($response['MSS_SignatureResp']['ServiceResponses'][0]['SigningCertificate']['Certificates'] as $key => $row) {
                            $filetPath = UPLOADPATH . 'temp/cert.crt';
                            $cert_txt = '-----BEGIN CERTIFICATE-----' . "\n";
                            $cert_txt .= $row . "\n";
                            $cert_txt .= '-----END CERTIFICATE-----';

                            $certFile = fopen($filetPath, "w");
                            fwrite($certFile, $cert_txt);
                            fclose($certFile);

                            $ssl = openssl_x509_parse(file_get_contents($filetPath));
                            array_push($tmp, issetParamArray($ssl['subject']));
                            if (issetParam($ssl['subject']['UID']) !== '')
                                $registerNumber = issetParam($ssl['subject']['UID']);

                            @unlink($filetPath);
                        }

                        $response['cert_data'] = $tmp;
                    }
                }
            }

            
            if (!$registerNumber) {
                throw new Exception("Мэдээлэл олдсонгүй!"); 
            }

            jsonResponse($response);

        } catch (Exception $e) {
            jsonResponse(array('status' => 'warning', 'message' => $e->getMessage()));
        }
    }
    
    public function metaVerseCommandPromptIframeUrl() {
        Auth::handleLogin();
        
        $getConfigUrl = Config::getFromCache('PF_METAVERSE_COMMAND_PROMPT_URL');
        
        if ($getConfigUrl) {
            $getConfigUrl = rtrim($getConfigUrl, '/').'/';
            $url = $getConfigUrl.'?ssid='.Ue::appUserSessionId();
            $response = ['status' => 'success', 'url' => $url];
        } else {
            $response = ['status' => 'error', 'message' => 'No config! /PF_METAVERSE_COMMAND_PROMPT_URL/'];
        }
        
        convJson($response);
    }
    
}