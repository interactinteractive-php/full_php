<?php if(!defined('_VALID_PHP')) exit('Direct access to this location is not allowed.');
    
class Cron_Model extends Model {
    
    public function __construct() {
        parent::__construct();
    }
    
    public function getPosApiServiceAddr() {
        return CONFIG_POSAPI_SERVICE_ADDRESS;
    }
    
    public function posApiSendDataModel() {
        
        set_time_limit(0);
        ini_set('memory_limit', '-1');
        
        includeLib('Mail/PHPMailer/v2/PHPMailerAutoload');
        
        $data = $this->db->GetAll("
            SELECT 
                PLOG.* 
            FROM (
                SELECT 
                    SS.DEPARTMENT_ID, 
                    SS.STORE_ID, 
                    SS.CODE AS STORE_CODE,
                    CR.CODE AS POS_CODE, 
                    CR.NAME AS POS_NAME, 
                    SCR.CASH_REGISTER_ID AS POS_ID, 
                    (
                        SELECT 
                            MAX(CREATED_DATE) 
                        FROM SM_STORE_POSAPI_LOG 
                        WHERE STORE_ID = SS.STORE_ID 
                            AND CASH_REGISTER_ID = CR.CASH_REGISTER_ID 
                            AND LOWER(SUCCESS) = 'true' 
                    ) AS LOG_DATE, 
                    (
                        SELECT 
                            MIN(CREATED_DATE) 
                        FROM SM_SALES_INVOICE_HEADER 
                        WHERE STORE_ID = SS.STORE_ID 
                            AND CASH_REGISTER_ID = CR.CASH_REGISTER_ID
                    ) AS FIRST_INVOICE_DATE 
                FROM SM_STORE_CASH_REGISTER SCR 
                    INNER JOIN SM_STORE SS ON SS.STORE_ID = SCR.STORE_ID 
                    INNER JOIN SM_CASH_REGISTER CR ON CR.CASH_REGISTER_ID = SCR.CASH_REGISTER_ID 
                WHERE SS.DEPARTMENT_ID IS NOT NULL 
            ) PLOG 
            ORDER BY PLOG.LOG_DATE ASC, PLOG.FIRST_INVOICE_DATE ASC");
        
        if ($data) {
            
            $message = '';
            
            foreach ($data as $row) {
                
                $logDate          = $row['LOG_DATE'];
                $firstInvoiceDate = $row['FIRST_INVOICE_DATE'];
                $currentDate      = Date::currentDate('Y-m-d H:i:s');
                
                $departmentId     = $row['DEPARTMENT_ID'];
                
                $orgRow = $this->db->GetRow("
                    SELECT
                        DEPARTMENT_ID 
                    FROM ORG_DEPARTMENT 
                    WHERE VATSP_NUMBER IS NOT NULL 
                        AND ROWNUM = 1 
                    START WITH DEPARTMENT_ID = $departmentId   
                    CONNECT BY PRIOR PARENT_ID = DEPARTMENT_ID");

                if (isset($orgRow['DEPARTMENT_ID']) && $orgRow['DEPARTMENT_ID']) {
                    $departmentId = $orgRow['DEPARTMENT_ID'];
                }
                
                $organizationId = Config::get('OrganizationID', 'departmentId='.$departmentId.';');
                $posApiPath     = $organizationId.'\\'.$row['STORE_CODE'].'\\'.$row['POS_CODE'];
                    
                if ($logDate == '' && $firstInvoiceDate == '') {

                    $response = $this->ws->redirectPost(self::getPosApiServiceAddr(), array('function' => 'checkapi', 'vatNumber' => $posApiPath));
                    
                    if ($response == 'null') {
                        $message .= '(<strong>'.$row['POS_CODE'].' - '.$row['POS_NAME'].'</strong>) уг касс дээр PosApi тохиргоо хийгдээгүй мөн касс ажиллаж эхлээгүй.<br />';
                    } else {
                        $sendDataResponse = $this->ws->redirectPost(self::getPosApiServiceAddr(), array('function' => 'senddata', 'vatNumber' => $posApiPath));
                        $message .= '(<strong>'.$row['POS_CODE'].' - '.$row['POS_NAME'].'</strong>) уг касс дээр PosApi тохиргоо хийгдсэн мөн касс ажиллаж эхлээгүй.<br />';
                    }
                    
                } else {
                        
                    $successStatus = $successMsg = '';

                    $response = $this->ws->redirectPost(self::getPosApiServiceAddr(), array('function' => 'checkapi', 'vatNumber' => $posApiPath));

                    if ($response == 'null') {

                        $successStatus = 'false';
                        $successMsg = 'PosApi тохиргоо хийгдээгүй байна';
                        $message .= '(<strong>'.$row['POS_CODE'].' - '.$row['POS_NAME'].'</strong>) уг касс дээр PosApi тохиргоо хийгдээгүй байна.<br />';

                    } else {

                        $sendDataResponse = $this->ws->redirectPost(self::getPosApiServiceAddr(), array('function' => 'senddata', 'vatNumber' => $posApiPath));
                        $sendDataArray = json_decode($sendDataResponse, true);

                        if ($sendDataArray['status'] == 'success') {
                            $successStatus = 'true';
                            $message .= '(<strong>'.$row['POS_CODE'].' - '.$row['POS_NAME'].'</strong>) касс амжилттай илгээгдлээ.<br />';
                            $param = array(
                                'filterDepartmentId' => $departmentId, 
                                'vatspNumber' => $organizationId
                            );
                            WebService::$isDefaultSessionId = true;
                            $this->ws->runResponse(GF_SERVICE_ADDRESS, 'TTDnumberGetExternalProcess', $param);                            
                        } else {
                            $successStatus = 'false';
                            $successMsg = $sendDataArray['errorcode'].' '.$sendDataArray['message'];
                            $message .= '(<strong>'.$row['POS_CODE'].' - '.$row['POS_NAME'].'</strong>) касс амжилтгүй. '.$successMsg.'<br />';
                        }
                    }

                    $insertLogData = array(
                        'ID'                => getUID(), 
                        'STORE_ID'          => $row['STORE_ID'], 
                        'CASH_REGISTER_ID'  => $row['POS_ID'], 
                        'CREATED_DATE'      => $currentDate, 
                        'SUCCESS'           => $successStatus, 
                        'RESULT_JSON'       => $successMsg, 
                        'IS_CRONJOB'        => 1
                    );

                    $this->db->AutoExecute('SM_STORE_POSAPI_LOG', $insertLogData);
                }
            }
            
        } else {
            $message = 'Дэлгүүр дотор касс олдсонгүй.';
        }
        
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
        
        $mail->SMTPSecure = (defined('SMTP_SECURE') ? SMTP_SECURE : false);
        $mail->Host = SMTP_HOST;
        $mail->Port = SMTP_PORT;
        $mail->setFrom(EMAIL_FROM, EMAIL_FROM_NAME);
        $mail->Subject = 'Veritech - PosApi Auto SendData';
        $mail->isHTML(true);
        $mail->msgHTML($message);
        $mail->AltBody = 'Veritech - PosApi Auto SendData';
        
        $mail->addAddress('ulaankhuu@veritech.mn');
        
        if (!$mail->send()) {
            $message .= '<br />'.$mail->ErrorInfo;
        }
        
        self::emdSendDataModel();
        
        if (!defined('CONFIG_POS_TEMP_INVOICE_DELETE_IGNORE') || (defined('CONFIG_POS_TEMP_INVOICE_DELETE_IGNORE') && !CONFIG_POS_TEMP_INVOICE_DELETE_IGNORE)) {
            $this->db->Execute("DELETE FROM SDM_SALES_ORDER_ITEM_PACKAGE WHERE SALES_ORDER_DETAIL_ID IN (SELECT SALES_ORDER_DETAIL_ID FROM SDM_SALES_ORDER_ITEM_DTL WHERE SALES_ORDER_ID IN (SELECT SALES_ORDER_ID FROM SDM_ORDER_BOOK WHERE BOOK_TYPE_ID = 204))");
            $this->db->Execute("DELETE FROM SDM_SALES_ORDER_ITEM_DTL WHERE SALES_ORDER_ID IN (SELECT SALES_ORDER_ID FROM SDM_ORDER_BOOK WHERE BOOK_TYPE_ID = 204)");
            $this->db->Execute("DELETE FROM SDM_ORDER_BOOK WHERE SALES_ORDER_ID IN (SELECT SALES_ORDER_ID FROM SDM_ORDER_BOOK WHERE BOOK_TYPE_ID = 204)");
        }
        
        return $message;
    }
    
    public function posDiscountEmployeeSendMailModel() {
        
        set_time_limit(0);
        ini_set('memory_limit', '-1');
        
        $data = $this->db->GetAll("
            SELECT
                H.REF_NUMBER, 
                SS.NAME AS STORE_NAME, 
                H.DESCRIPTION, 
                CASE
                    WHEN D.PRODUCT_ID IS NULL
                    THEN MJ.JOB_CODE
                    ELSE II.ITEM_CODE
                END AS ITEM_CODE, 
                CASE
                    WHEN D.PRODUCT_ID IS NULL
                    THEN MJ.JOB_NAME
                    ELSE II.ITEM_NAME 
                END ITEM_NAME, 
                D.UNIT_PRICE, 
                D.INVOICE_QTY, 
                D.LINE_TOTAL_PRICE, 
                D.DISCOUNT_AMOUNT, 
                D.DISCOUNT_PERCENT, 
                DT.NAME AS DISCOUNT_TYPE_NAME, 
                SUBSTR(HE.LAST_NAME,0,2)||'.'||HE.FIRST_NAME AS DISCOUNT_EMPLOYEE_NAME, 
                SUBSTR(SP.LAST_NAME,0,2)||'.'||SP.FIRST_NAME AS SALES_PERSON_NAME, 
                US.EMAIL, 
                D.SALES_INVOICE_DETAIL_ID, 
                ".$this->db->SQLDate('Y-m-d', 'H.INVOICE_DATE')." AS INVOICE_DATE, 
                SUBSTR(HEC.LAST_NAME,0,2)||'.'||HEC.FIRST_NAME AS CASHIER_EMPLOYEE_NAME, 
                D.DESCRIPTION AS DISCOUNT_DESCRIPTION 
            FROM SM_SALES_INVOICE_HEADER H 
                INNER JOIN SM_SALES_INVOICE_DETAIL D ON H.SALES_INVOICE_ID = D.SALES_INVOICE_ID 
                INNER JOIN SM_STORE SS ON H.STORE_ID = SS.STORE_ID 
                INNER JOIN SM_CASHIER SC ON SC.CASHIER_ID = H.CREATED_CASHIER_ID 
                INNER JOIN HRM_EMPLOYEE HEC ON HEC.EMPLOYEE_ID = SC.EMPLOYEE_ID 
                LEFT JOIN HRM_EMPLOYEE SP ON D.EMPLOYEE_ID = SP.EMPLOYEE_ID 
                LEFT JOIN IM_ITEM II ON D.PRODUCT_ID = II.ITEM_ID 
                LEFT JOIN MES_JOB MJ ON D.JOB_ID = MJ.JOB_ID 
                LEFT JOIN SM_DISCOUNT_TYPE DT ON D.DISCOUNT_TYPE_ID = DT.ID 
                LEFT JOIN HRM_EMPLOYEE HE ON D.DISCOUNT_EMPLOYEE_ID = HE.EMPLOYEE_ID 
                INNER JOIN UM_SYSTEM_USER US ON HE.PERSON_ID = US.PERSON_ID 
            WHERE D.DISCOUNT_EMPLOYEE_ID IS NOT NULL 
                AND US.EMAIL IS NOT NULL 
                AND (D.IS_NOTIFIED IS NULL OR D.IS_NOTIFIED = 0) 
            ORDER BY H.CREATED_DATE ASC, D.SALES_INVOICE_DETAIL_ID ASC");
        
        $message = '';
        
        if ($data) {
            includeLib('Mail/PHPMailer/v2/PHPMailerAutoload');
            
            $groupedEmail = Arr::groupByArray($data, 'EMAIL');
            
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

            $mail->SMTPSecure = (defined('SMTP_SECURE') ? SMTP_SECURE : false);
            $mail->Host = SMTP_HOST;
            $mail->Port = SMTP_PORT;
            $mail->setFrom(EMAIL_FROM, EMAIL_FROM_NAME);
            $mail->isHTML(true);
            $mail->AltBody = 'Veritech ERP - Хямдрал хийсэн тухай мэдэгдэл';
            
            $bodyTemplate = file_get_contents('middleware/views/metadata/dataview/form/email_templates/selectionRows.html');
            $detailTableTemplate = file_get_contents('middleware/views/pos/print_template/email/detailTable.html');
            $errorMessage = '';
            
            foreach ($groupedEmail as $email => $rows) {
                
                $subjectDate = array();
                $updateIds   = array();
                $row         = $rows['row'];
                
                $emailBody = '<span style="font-size: 18px">Сайн байна уу, <span style="font-weight: bold; font-size: 18px">'.$row['DISCOUNT_EMPLOYEE_NAME'].'</span></span><br /><br />';
                $emailBody .= '<span style="font-size: 18px">Таны өгсөн зөвшөөрлөөр хямдрал хийсэн байна. Хямдралыг шалгаж зөрүүтэй хямдрал бол санхүү болон салбар нэгжид хариу мэдэгдэнэ үү.</span><br /><br />';
                
                $groupedInvoice = Arr::groupByArray($rows['rows'], 'REF_NUMBER');
                $groupedInvoiceCount = count($groupedInvoice);
                
                foreach ($groupedInvoice as $invoiceNumber => $invoiceRows) {
                    
                    $invoiceRow = $invoiceRows['row'];
                    $detailRows = $invoiceRows['rows'];
                    $detailTrs  = '';
                    
                    $emailBody .= 'Салбар нэгж: <strong>'.$invoiceRow['STORE_NAME'].'</strong><br />';
                    $emailBody .= 'Дотоод дугаар: <strong>'.$invoiceNumber.'</strong><br />';
                    $emailBody .= 'Гүйлгээний утга: <strong>'.$invoiceRow['DESCRIPTION'].'</strong><br />';
                    $emailBody .= 'Огноо: <strong>'.$invoiceRow['INVOICE_DATE'].'</strong><br />';
                    $emailBody .= 'Кассын нэр: <strong>'.$invoiceRow['CASHIER_EMPLOYEE_NAME'].'</strong><br /><br />';
                    
                    $subjectDate[$invoiceRow['INVOICE_DATE']] = $invoiceRow['INVOICE_DATE'];
                    
                    foreach ($detailRows as $k => $detailRow) {
                        
                        if ($detailRow['INVOICE_QTY'] > 0) {
                            $discountAmount = $detailRow['DISCOUNT_AMOUNT'] * $detailRow['INVOICE_QTY'];
                        } else {
                            $discountAmount = $detailRow['DISCOUNT_AMOUNT'];
                        }
                                
                        $detailTrs .= '<tr>
                            <td style="text-align: center" align="center">'.(++$k).'</td>
                            <td style="text-align: left" align="left">'.$detailRow['ITEM_CODE'].'</td>
                            <td style="text-align: left" align="left">'.$detailRow['ITEM_NAME'].'</td>
                            <td style="text-align: right" align="right">'.$detailRow['INVOICE_QTY'].'</td>
                            <td style="text-align: right" align="right">'.self::maskAmount($detailRow['UNIT_PRICE']).'</td>
                            <td style="text-align: right" align="right">'.self::maskAmount($detailRow['LINE_TOTAL_PRICE']).'</td>
                            <td style="text-align: right" align="right">'.self::maskAmount($detailRow['LINE_TOTAL_PRICE'] - ($detailRow['DISCOUNT_AMOUNT'] * $detailRow['INVOICE_QTY'])).'</td>    
                            <td style="text-align: right" align="right">'.self::maskAmount($discountAmount).'</td>
                            <td style="text-align: right" align="right">'.$detailRow['DISCOUNT_PERCENT'].'</td>
                            <td style="text-align: center" align="center">'.$detailRow['DISCOUNT_TYPE_NAME'].'</td>
                            <td style="text-align: left" align="left">'.$detailRow['SALES_PERSON_NAME'].'</td>
                            <td style="text-align: left" align="left">'.$detailRow['DISCOUNT_DESCRIPTION'].'</td>
                        </tr>';
                        
                        $updateIds[] = $detailRow['SALES_INVOICE_DETAIL_ID'];
                    }
                    
                    $emailBody .= str_replace('{detailRows}', $detailTrs, $detailTableTemplate);
                    
                    if ($groupedInvoiceCount > 1) {
                        $emailBody .= '<br /><hr /><br />';
                    }
                }
                
                $bodyContent = str_replace('{htmlTable}', rtrim($emailBody, '<br /><hr /><br />'), $bodyTemplate);
                
                $mail->Subject = '[Veritech POS] Хямдрал хийсэн тухай мэдэгдэл: '.Arr::implode_r(', ', $subjectDate, true);
                $mail->msgHTML($bodyContent);

                $mail->addAddress($email);
                
                if (!$mail->send()) {
                    
                    $message .= $email.' - '.$mail->ErrorInfo.'<br />';
                    $errorMessage .= $email.' - '.$mail->ErrorInfo.'<br />';
                    
                } else {
                    $message .= $email.' - Success<br />';
                    
                    if ($updateIds) {
                        foreach ($updateIds as $updateId) {
                            $this->db->AutoExecute('SM_SALES_INVOICE_DETAIL', array('IS_NOTIFIED' => 1), 'UPDATE', 'SALES_INVOICE_DETAIL_ID = '.$updateId);
                        }
                    }
                }
            
                $mail->clearAllRecipients();
            }
            
            if ($errorMessage) {
                $mail->Subject = '[Veritech POS] Хямдрал хийсэн тухай мэдэгдэл: Error message';
                $mail->msgHTML($errorMessage);

                $mail->addAddress('ochoo0909@gmail.com');
                $mail->send();
                $mail->clearAllRecipients();
            }
        }
        
        return $message;
    }
    
    public function emdSendDataModel() {
        
        if (Config::getFromCache('CONFIG_POS_HEALTHRECIPE')) {
            
            $this->load->model('mdpos', 'middleware/models/');
            
            $notSentEmdData = $this->db->GetAll("
                SELECT 
                    PRE.ID, 
                    BR.BILL_ID, 
                    PRE.SEND_JSON, 
                    ST.CLIENT_ID, 
                    ST.CLIENT_SECRET 
                FROM SM_SALES_INVOICE_PRESCRIPTION PRE 
                    INNER JOIN SM_BILL_RESULT_DATA BR ON BR.SALES_INVOICE_ID = PRE.SALES_INVOICE_ID 
                    INNER JOIN SM_SALES_INVOICE_HEADER H ON H.SALES_INVOICE_ID = PRE.SALES_INVOICE_ID 
                    INNER JOIN SM_STORE ST ON ST.STORE_ID = H.STORE_ID 
                WHERE PRE.IS_SENT = 0 OR PRE.IS_SENT IS NULL");
            
            if ($notSentEmdData) {
                
                $dataParamsArr = $updateIds = $successIds = array();
                
                foreach ($notSentEmdData as $notSentEmdRow) {
                    
                    $getToken = $this->model->emdGetToken($notSentEmdRow['CLIENT_ID'], $notSentEmdRow['CLIENT_SECRET']);

                    if (isset($getToken['access_token'])) {

                        $getEmdReturn = $this->model->emdCheckPosRno($getToken['access_token'], $notSentEmdRow['BILL_ID']);
                        
                        //($getEmdReturn['msg'] == 'success' || $getEmdReturn['msg'] == 'not found')
                        
                        if (isset($getEmdReturn['msg']) && $getEmdReturn['code'] != '200') {
                            
                            $keySet = $notSentEmdRow['CLIENT_ID'].'|-|'.$notSentEmdRow['CLIENT_SECRET'].'|-|'.$notSentEmdRow['ID'];
                            
                            if (isset($dataParamsArr[$keySet])) {
                                $dataParamsArr[$keySet] = $dataParamsArr[$keySet] . ',' . $notSentEmdRow['SEND_JSON'];
                            } else {
                                $dataParamsArr[$keySet] = $notSentEmdRow['SEND_JSON'];
                            }
                            
                            $updateIds[$keySet][] = $notSentEmdRow['ID'];
                            
                        } elseif (isset($getEmdReturn['msg']) && $getEmdReturn['msg'] == 'success' && $getEmdReturn['code'] == '200') {
                            $successIds[] = $notSentEmdRow['ID'];
                        }
                    }
                }
                
                if (count($dataParamsArr)) {

                    foreach ($dataParamsArr as $keyPass => $dataParams) {
                        
                        $keyPassArr = explode('|-|', $keyPass);

                        $clientId = $keyPassArr[0];
                        $clientPass = $keyPassArr[1];
                        
                        $getToken = $this->model->emdGetToken($clientId, $clientPass);                        
                        
                        if (isset($getToken['access_token'])) {
                            
                            $batchResult = $this->model->emdBatch($getToken['access_token'], '['.$dataParams.']');
                            
                            if (isset($batchResult['msg']) && $batchResult['msg'] == 'success' && $batchResult['code'] == '200') {
                                
                                $updateIdsArr = $updateIds[$keyPass];
                                
                                foreach ($updateIdsArr as $idKey => $updateId) {
                                    $this->db->AutoExecute('SM_SALES_INVOICE_PRESCRIPTION', array('IS_SENT' => 1), 'UPDATE', 'ID = '.$updateId);
                                }
                            } 
                        }
                    }
                }
                
                if (count($successIds)) {
                    foreach ($successIds as $successKey => $successId) {
                        $this->db->AutoExecute('SM_SALES_INVOICE_PRESCRIPTION', array('IS_SENT' => 1), 'UPDATE', 'ID = '.$successId);
                    }
                }
            }
            
            $notReturnEmdData = $this->db->GetAll("
                SELECT 
                    HDR.SALES_INVOICE_ID, 
                    RD.BILL_ID, 
                    ST.CLIENT_ID, 
                    ST.CLIENT_SECRET 
                FROM SM_SALES_INVOICE_HEADER HDR 
                    INNER JOIN SM_BILL_RESULT_DATA RD ON RD.SALES_INVOICE_ID = HDR.SALES_INVOICE_ID 
                    INNER JOIN SM_STORE ST ON ST.STORE_ID = HDR.STORE_ID 
                    LEFT JOIN SM_SALES_INVOICE_PRESCRIPTION IP ON IP.SALES_INVOICE_ID = HDR.SALES_INVOICE_ID AND IP.IS_REMOVED = 1 
                WHERE HDR.IS_REMOVED = 1 
                    AND HDR.PRESCRIPTION_NUMBER IS NOT NULL 
                    AND IP.RECEIPT_NUMBER IS NULL");
            
            if ($notReturnEmdData) {
                
                foreach ($notReturnEmdData as $notReturnEmdRow) {
                    
                    $getToken = $this->model->emdGetToken($notReturnEmdRow['CLIENT_ID'], $notReturnEmdRow['CLIENT_SECRET']);
                    
                    if (isset($getToken['access_token'])) {
                        
                        $getEmdReturn = $this->model->emdReturn($getToken['access_token'], $notReturnEmdRow['BILL_ID']);

                        if (isset($getEmdReturn['msg']) && $getEmdReturn['msg'] == 'success' 
                            && ($getEmdReturn['code'] == '200' || $getEmdReturn['code'] == '300')) {

                            $this->db->AutoExecute('SM_SALES_INVOICE_PRESCRIPTION', 
                                array(
                                    'IS_REMOVED'   => 1, 
                                    'REMOVED_DATE' => Date::currentDate('Y-m-d H:i:s')
                                ), 
                                'UPDATE', 'SALES_INVOICE_ID = '.$notReturnEmdRow['SALES_INVOICE_ID']
                            );
                            
                        } else {
                            file_put_contents('log/emdReturn.txt', var_export($getToken, true) . "\n", FILE_APPEND);
                        }
                    }
                }
            }
            
            return 'notSentEmdData: '.count($notSentEmdData).' notReturnEmdData: '.count($notReturnEmdData);
        }
        
        return 'null';
    }
    
    public function maskAmount($number) {
        $number = number_format($number, 2, '.', ',');
        $number = rtrim(rtrim(rtrim($number,'0'),'0'),'.');
        return $number;
    }
    
    public function testmailModel() {
        
        includeLib('Mail/PHPMailer/v2/PHPMailerAutoload');
        
        $message = 'Test mail';
        
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
        
        $mail->SMTPSecure = (defined('SMTP_SECURE') ? SMTP_SECURE : false);
        $mail->Host = SMTP_HOST;
        $mail->Port = SMTP_PORT;
        $mail->setFrom(EMAIL_FROM, EMAIL_FROM_NAME);
        $mail->Subject = 'Veritech - PosApi Auto Test';
        $mail->isHTML(true);
        $mail->msgHTML($message);
        $mail->AltBody = 'Veritech - PosApi Auto Test';
        
        $mail->addAddress('ochoo0909@gmail.com');
        
        if (!$mail->send()) {
            $message .= '<br />'.$mail->ErrorInfo;
        }
        
        return $message;
    }
    
    public function importBankBillingModel($date = '', $account = '', $bankCode = '') {
        
        $isGetBillAuto = Config::getFromCache('IS_GET_BILL_AUTO');
        $where = '';
        
        if ($isGetBillAuto) {
            $where = ' AND BANK.IS_GET_BILL_AUTO = 1';
        }
        
        if ($account) {
            $account = Input::param($account);
            $account = str_replace(',', "','", $account);
            $where .= " AND BANK.BANK_ACCOUNT_NUMBER IN ('$account')";
        }
        
        if ($bankCode) {
            
            if ($bankCode == 'khaan') {
                
                $khanBankId = Mdintegration::getBankId('khan'); 
                $where .= " AND COALESCE(RBP.BANK_ID, RB.BANK_ID) = $khanBankId";
                
            } elseif ($bankCode == 'golomt') {
                
                $golomtBankId = Mdintegration::getBankId('golomt');
                $where .= " AND COALESCE(RBP.BANK_ID, RB.BANK_ID) = $golomtBankId";
            }
        }
        
        $data = $this->db->GetAll("
            SELECT 
                COALESCE(RBP.BANK_ID, RB.BANK_ID) AS BANK_ID,
                BANK.BANK_ACCOUNT_CODE,
                BANK.BANK_ACCOUNT_NUMBER,
                BANK.BANK_ACCOUNT_DESC_L,
                BANK.IS_BANK_BILL_CHECK_DIFF, 
                RC.CURRENCY_CODE,
                ACC.ACCOUNT_ID AS BANK_ACCOUNT_ID,
                ACC.ACCOUNT_CODE,
                ACC.ACCOUNT_NAME,
                ACC.DEPARTMENT_ID, 
                OD.DEPARTMENT_CODE 
            FROM CM_BANK_ACCOUNT BANK 
                INNER JOIN REF_BANK RB ON RB.BANK_ID = BANK.BANK_ID 
                INNER JOIN FIN_ACCOUNT ACC ON ACC.ACCOUNT_ID = BANK.ACCOUNT_ID 
                INNER JOIN REF_CURRENCY RC ON RC.CURRENCY_ID = ACC.CURRENCY_ID 
                INNER JOIN ORG_DEPARTMENT OD ON OD.DEPARTMENT_ID = ACC.DEPARTMENT_ID 
                LEFT JOIN REF_BANK RBP ON RB.PARENT_ID = RBP.BANK_ID 
            WHERE 1 = 1 
            $where 
            GROUP BY 
                RBP.BANK_ID, 
                RB.BANK_ID, 
                BANK.BANK_ACCOUNT_CODE, 
                BANK.BANK_ACCOUNT_NUMBER, 
                BANK.BANK_ACCOUNT_DESC_L, 
                BANK.IS_BANK_BILL_CHECK_DIFF, 
                RC.CURRENCY_CODE, 
                ACC.ACCOUNT_ID, 
                ACC.ACCOUNT_CODE, 
                ACC.ACCOUNT_NAME, 
                ACC.DEPARTMENT_ID, 
                OD.DEPARTMENT_CODE");
        
        if ($data) {
            
            $intm = &getInstance();
            $intm->load->model('mdintegration', 'middleware/models/');
            
            $bankResponse = [];
            
            $khanBankId = Mdintegration::getBankId('khan');
            $golomtBankId = Mdintegration::getBankId('golomt');
            $phpUrl = Config::getFromCache('PHP_URL');
            
            if ($date && $date != 'sysdate') {
                
                if ($date == 'yesterday') {
                    $fromDate = Date::weekdayAfter('Y-m-d', Date::currentDate('Y-m-d'), '-1 day');
                } else {
                    $isIgnoreLastRecordId = true;
                    $fromDate = $date;
                }
            } else {
                $fromDate = Date::currentDate('Y-m-d');
            }
            
            foreach ($data as $row) {
                
                if ($row['BANK_ID'] == $khanBankId) {
                    
                    $paramData = array(
                        'account'             => $row['BANK_ACCOUNT_NUMBER'], 
                        'from'                => $fromDate, 
                        'to'                  => $fromDate, 
                        'departmentid'        => $row['DEPARTMENT_ID'], 
                        'isbankbillcheckdiff' => $row['IS_BANK_BILL_CHECK_DIFF'], 
                        'setcreateduserid'    => 1
                    );
                    
                    if (!isset($isIgnoreLastRecordId)) {
                        
                        $lastImportId = $intm->model->getLastImportIdModel($row['BANK_ID'], $row['BANK_ACCOUNT_NUMBER'], $fromDate);
                        
                        $paramData['lastimportid'] = $lastImportId;
                    }
                    
                    /*$bankResponse[] = $intm->model->khaanBankImportStatement(array('WS_URL' => ''), $paramData);*/
                    
                    $this->ws->curlQueue(URL.'cron/importBankBillingByBankCode/khaan', $paramData);
                    $bankResponse[] = array('status' => 'success', 'message' => 'khaan ' . $row['BANK_ACCOUNT_NUMBER'] . ' account - queue process');
                    
                } elseif ($isGetBillAuto && $row['BANK_ID'] == $golomtBankId) {
                    
                    $paramData = array(
                        'account'             => $row['BANK_ACCOUNT_NUMBER'], 
                        'from'                => $fromDate, 
                        'to'                  => $fromDate, 
                        'departmentid'        => $row['DEPARTMENT_ID'], 
                        'isbankbillcheckdiff' => $row['IS_BANK_BILL_CHECK_DIFF'], 
                        'setcreateduserid'    => 1
                    );
                    
                    /*$bankResponse[] = $intm->model->golomtBankImportStatement(array('WS_URL' => ''), $paramData);*/
                    
                    $this->ws->curlQueue(URL.'cron/importBankBillingByBankCode/golomt', $paramData);
                    $bankResponse[] = array('status' => 'success', 'message' => 'golomt ' . $row['BANK_ACCOUNT_NUMBER'] . ' account - queue process');
                }
            }
            
            if ($bankResponse) {
                $response = $bankResponse;
            } else {
                $response = array('status' => 'error', 'message' => 'Дансны мэдээлэл олдсонгүй!');
            }
            
        } else {
            $response = array('status' => 'error', 'message' => 'Дансны мэдээлэл олдсонгүй!');
        }
        
        return $response;
    }
    
    public function importBankBillingByBankCodeModel($bankCode) {
        
        $intm = &getInstance();
        $intm->load->model('mdintegration', 'middleware/models/');
        
        $jsonBody = file_get_contents('php://input');
        $paramData = json_decode($jsonBody, true);

        if ($bankCode == 'khaan') {
            
            $bankResponse = $intm->model->khaanBankImportStatement(array('WS_URL' => ''), $paramData);
            
        } elseif ($bankCode == 'golomt') {
            
            $bankResponse = $intm->model->golomtBankImportStatement(array('WS_URL' => ''), $paramData);
            
        } else {
            $bankResponse = array('status' => 'error', 'message' => 'error');
        }
        
        return $bankResponse;
    }
    
    public function clearKpiIndicatorCacheModel($mode) {
        
        try {
            
            $data = array();
            
            if ($mode == 'queryType') {
                
                $data = $this->db->GetAll("
                    SELECT 
                        T0.ID 
                    FROM KPI_INDICATOR T0 
                        INNER JOIN KPI_INDICATOR_INDICATOR_MAP T1 ON T1.MAIN_INDICATOR_ID = T0.ID 
                            AND T1.COLUMN_NAME IS NOT NULL 
                    WHERE T0.QUERY_STRING IS NOT NULL 
                    GROUP BY T0.ID");

                foreach ($data as $row) {
                    Mdform::clearCacheData($row['ID']);
                }
            }
            
            if (!$data) {
                $result = array('status' => 'error', 'message' => 'No data!');
            } else {
                $result = array('status' => 'success');
            }
            
        } catch (Exception $ex) {
            $result = array('status' => 'error', 'message' => $ex->getMessage());
        }
        
        return $result;
    }
    
    public function jsonToProcessModel() {
        
        try {
            
            $data = $this->db->GetAll("
                SELECT 
                    T0.ID, 
                    T0.META_PROCESS_ID, 
                    T0.INDICATOR_ID, 
                    T0.JSON_CONFIG, 
                    T1.META_DATA_CODE 
                FROM META_PROCESS_JSON T0 
                    LEFT JOIN META_DATA T1 ON T1.META_DATA_ID = T0.META_PROCESS_ID 
                    LEFT JOIN KPI_INDICATOR T2 ON T2.ID = T0.INDICATOR_ID 
                WHERE (T0.STATUS IS NULL OR T0.STATUS = 'error')  
                    AND T0.JSON_CONFIG IS NOT NULL 
                    AND (T1.META_DATA_ID IS NOT NULL OR T2.ID IS NOT NULL) 
                ORDER BY T0.CREATED_DATE ASC");
            
            if ($data) {
                
                $logged = Session::isCheck(SESSION_PREFIX.'LoggedIn');

                if ($logged == false) {
                    Session::set(SESSION_PREFIX . 'LoggedIn', true);
                    Session::set(SESSION_PREFIX . 'lastTime', time());
                }

                $_POST['nult'] = true;

                foreach ($data as $row) {
                    
                    $recordId    = $row['ID'];
                    $processId   = $row['META_PROCESS_ID'];
                    $indicatorId = $row['INDICATOR_ID'];
                    $param       = json_decode(html_entity_decode($row['JSON_CONFIG'], ENT_QUOTES, 'UTF-8'), true);

                    if ($processId != '') {
                        
                        $processResponse = $this->ws->runSerializeResponse(GF_SERVICE_ADDRESS, $row['META_DATA_CODE'], $param);
                        
                        if ($this->ws->isException()) {
                            
                            $updateData = ['STATUS' => 'error', 'ERROR_MESSAGE' => $this->ws->getErrorMessage()];
                            
                        } else {
                            if (isset($processResponse['status'])) {
                                if ($processResponse['status'] == 'success') {
                                    $updateData = ['STATUS' => 'success', 'ERROR_MESSAGE' => null];
                                } else {
                                    $updateData = ['STATUS' => 'error', 'ERROR_MESSAGE' => $this->ws->getResponseMessage($processResponse)];
                                }
                            } else {
                                $updateData = ['STATUS' => 'error', 'ERROR_MESSAGE' => WebService::$soapErrorNonNormal];
                            }
                        }
                        
                    } elseif ($indicatorId != '') {
                        
                        $_POST['kpiMainIndicatorId'] = $indicatorId;
                        Mdform::$mvSaveParams = Arr::changeKeyUpper($param);
                        
                        $this->load->model('mdform', 'middleware/models/');
                        
                        if (isset(Mdform::$mvSaveParams['SETROWWFMSTATUS'])) {
                            
                            $wfmStatusParams = Arr::changeKeyLower(Mdform::$mvSaveParams['SETROWWFMSTATUS']);
                            
                            if (isset($wfmStatusParams['systemmetagroupid']) && isset($wfmStatusParams['newwfmstatusid']) && isset($wfmStatusParams['wfmstatusid'])) {
                                
                                $currentStatusId = $wfmStatusParams['wfmstatusid'];
                                $newWfmStatusId  = $wfmStatusParams['newwfmstatusid'];
                                
                                unset($wfmStatusParams['wfmstatusid']);
                                unset($wfmStatusParams['newwfmstatusid']);
                                
                                $wfmStatusParams['wfmstatusid'] = $newWfmStatusId;
                                $wfmStatusParams['currentwfmstatusid'] = $currentStatusId;
                                
                                if (isset($wfmStatusParams['iskpiindicator']) && $wfmStatusParams['iskpiindicator'] == '1') {
                                    $wfmStatusParams['mainindicatorid'] = $wfmStatusParams['systemmetagroupid'];
                                    unset($wfmStatusParams['systemmetagroupid']);
                                }
                                
                                $wfmStatusParams['datarow'] = $wfmStatusParams;
                                $wfmStatusParams['datarow']['wfmstatusid'] = $currentStatusId;
                                
                                unset($wfmStatusParams['datarow']['currentwfmstatusid']);
                                unset($wfmStatusParams['datarow']['newwfmstatusid']);
                                unset($wfmStatusParams['datarow']['mainindicatorid']);
                                unset($wfmStatusParams['datarow']['systemmetagroupid']);
                                unset($wfmStatusParams['datarow']['iskpiindicator']);
                                
                                $_POST['wfmStatusParams'] = json_encode($wfmStatusParams, JSON_UNESCAPED_UNICODE);
                            }
                            
                            unset(Mdform::$mvSaveParams['SETROWWFMSTATUS']);
                        }
                        
                        $response = $this->model->saveMetaVerseDataModel();
                        
                        if (isset($response['status'])) {
                            if ($response['status'] == 'success') {
                                $updateData = ['STATUS' => 'success', 'ERROR_MESSAGE' => null];
                            } else {
                                $updateData = ['STATUS' => 'error', 'ERROR_MESSAGE' => $response['message']];
                            }
                        }
                    }
                    
                    if (isset($updateData)) {
                        
                        $this->db->AutoExecute('META_PROCESS_JSON', $updateData, 'UPDATE', "ID = $recordId");
                    }
                }
                
                $result = ['status' => 'success'];
                
            } else {
                $result = ['status' => 'error', 'message' => 'meta_process_json empty!'];
            }
            
        } catch (Exception $ex) {
            $result = ['status' => 'error', 'message' => $ex->getMessage()];
        }
        
        return $result;
    }
    
    public function cloudDbPrepareModel() {
        
        $logDtl = [];
        
        try {
            
            $bankBillingData = $this->db->GetAll("
                SELECT 
                    T1.ID, 
                    T1.DESCRIPTION, 
                    T1.AMOUNT, 
                    TT.INVOICE_ID, 
                    TT.BOOK_NUMBER, 
                    LL.COMPANY_NAME, 
                    LL.EMAIL1 AS EMAIL, 
                    LL.PHONE_NUMBER1 AS PHONE_NUMBER, 
                    LL.LEAD_ID 
                FROM CM_BANK_BILLING T1 
                    INNER JOIN FIN_INVOICE TT ON REGEXP_LIKE (T1.DESCRIPTION, TT.BOOK_NUMBER) 
                    INNER JOIN CRM_LEAD LL ON TT.LEAD_ID = LL.LEAD_ID 
                    LEFT JOIN META_DM_RECORD_MAP T2 ON T1.ID = T2.SRC_RECORD_ID 
                        AND TT.INVOICE_ID = T2.TRG_RECORD_ID 
                        AND T2.SRC_TABLE_NAME = 'CM_BANK_BILLING' 
                        AND T2.TRG_TABLE_NAME = 'FIN_INVOICE' 
                WHERE T2.ID IS NULL 
                    AND LL.COMPANY_NAME IS NOT NULL 
                    AND LL.EMAIL1 IS NOT NULL 
                    AND T1.AMOUNT > 0");
            
            if ($bankBillingData) {
                
                includeLib('Mail/Mail');
                
                $currentDate        = Date::currentDate();
                $customerGroupId    = 17141037295452;
                $finInvoiceStatusId = 1711607981610470;
                $finInvoiceStrId    = 1711607546700352;
                $apiEnvironmentId   = 1711935661499533;
                $ntfNotificationId  = 17116810804369;
                $dbType             = 'postgre';
                $dbPort             = '5432';
                $dbServiceName      = 'cloud_platform_uat';
                /*$dbUserName         = 'cloud_client';
                $dbUserPass         = '2sfu{r21>EaTF%kU';*/
                $dbUserName         = 'dummy';
                $dbUserPass         = 'Test123';
                
                $idPh               = $this->db->Param(0);
                $idTwoPh            = $this->db->Param(1);
                $emailTemplateRow   = self::getCloudPrepareEmailTemplate($idPh, $ntfNotificationId);
                
                if (!$emailTemplateRow) {
                    throw new Exception($ntfNotificationId . ' ntfNotificationId not found!'); 
                }
                
                foreach ($bankBillingData as $b => $bankBillingRow) {
                    
                    $this->db->BeginTrans(); 
                    
                    $billingId   = $bankBillingRow['ID'];
                    $description = $bankBillingRow['DESCRIPTION'];
                    $invoiceId   = $bankBillingRow['INVOICE_ID'];
                    $bookNumber  = $bankBillingRow['BOOK_NUMBER'];
                    $companyName = $bankBillingRow['COMPANY_NAME'];
                    $phoneNumber = $bankBillingRow['PHONE_NUMBER'];
                    $email       = $bankBillingRow['EMAIL'];
                    $crmLeadId   = $bankBillingRow['LEAD_ID'];
                    $domainName  = cyrillicToLatin($companyName);
                    $domainName  = trim(Str::remove_doublewhitespace(Str::remove_whitespace_feed(Str::remove_whitespace($domainName))));
                    $domainName  = strtolower($domainName);
                    $dbName      = $domainName.'-database-service';
                    
                    $recordMapData = [
                        'ID'             => getUIDAdd($b), 
                        'SRC_TABLE_NAME' => 'CM_BANK_BILLING', 
                        'TRG_TABLE_NAME' => 'FIN_INVOICE', 
                        'SRC_RECORD_ID'  => $billingId, 
                        'TRG_RECORD_ID'  => $invoiceId, 
                        'CREATED_DATE'   => $currentDate
                    ];
                    
                    $this->db->AutoExecute('META_DM_RECORD_MAP', $recordMapData);
                    
                    $finInvoiceChangeStatusData = [
                        'WFM_STATUS_ID'   => $finInvoiceStatusId, 
                        'WFM_DESCRIPTION' => 'Банкны хуулгаар төлбөр орж ирэв'
                    ];
                    
                    $this->db->AutoExecute('FIN_INVOICE', $finInvoiceChangeStatusData, 'UPDATE', "INVOICE_ID = $invoiceId");
                    
                    $wfmLogData = [
                        'ID'               => getUIDAdd($b), 
                        'REF_STRUCTURE_ID' => $finInvoiceStrId, 
                        'RECORD_ID'        => $invoiceId, 
                        'WFM_STATUS_ID'    => $finInvoiceStatusId, 
                        'WFM_DESCRIPTION'  => 'Төлбөр баталгаажиж эрх нээгдэв.', 
                        'CREATED_USER_ID'  => 1, 
                        'CREATED_DATE'     => $currentDate
                    ];
                    
                    $this->db->AutoExecute('META_WFM_LOG', $wfmLogData);
                    
                    $customerId = getUIDAdd($b + 1);
                    $customerData = [
                        'CUSTOMER_ID'       => $customerId, 
                        'CUSTOMER_CODE'     => $bookNumber, 
                        'CUSTOMER_NAME'     => $companyName, 
                        'PHONE_NUMBER'      => $phoneNumber, 
                        'EMAIL'             => $email, 
                        'DOMAIN_NAME'       => $domainName,
                        'DESCRIPTION'       => $description, 
                        'CUSTOMER_GROUP_ID' => $customerGroupId, 
                        'IS_ACTIVE'         => 1, 
                        'CREATED_DATE'      => $currentDate
                    ];
                    
                    $this->db->AutoExecute('CRM_CUSTOMER', $customerData);
                    
                    $departmentId = getUIDAdd($b + 2);
                    $departmentData = [
                        'DEPARTMENT_ID'   => $departmentId, 
                        'DEPARTMENT_CODE' => $bookNumber, 
                        'DEPARTMENT_NAME' => $companyName, 
                        'TYPE_ID'         => 4, 
                        'CUSTOMER_ID'     => $customerId, 
                        'IS_ACTIVE'       => 1, 
                        'CREATED_DATE'    => $currentDate
                    ];
                    
                    $this->db->AutoExecute('ORG_DEPARTMENT', $departmentData);
                    
                    $connectionId = getUIDAdd($b + 3);
                    $connectionData = [
                        'ID'            => $connectionId, 
                        'DB_TYPE'       => $dbType, 
                        'HOST_NAME'     => $dbName, 
                        'PORT'          => $dbPort, 
                        'SERVICE_NAME'  => $dbServiceName, 
                        'USER_NAME'     => $dbUserName,
                        'USER_PASSWORD' => $dbUserPass, 
                        'DB_NAME'       => $companyName, 
                        'IS_ACTIVE'     => 1, 
                        'IS_REPORT'     => 0, 
                        'CUSTOMER_ID'   => $customerId, 
                        'DEPARTMENT_ID' => $departmentId
                    ];
                    
                    $this->db->AutoExecute('MDM_CONNECTIONS', $connectionData);
                    
                    $this->db->AutoExecute('CRM_LEAD', ['CUSTOMER_ID' => $customerId], 'UPDATE', "LEAD_ID = $crmLeadId");
                    
                    $packageProductDtl = $this->db->GetAll("
                        SELECT 
                            PP.ID AS PRODUCT_ID, 
                            K.DURATION_TIME AS LICENSE_MONTH, 
                            FI.BOOK_DATE AS START_DATE, 
                            ADD_MONTHS(FI.BOOK_DATE, K.DURATION_TIME) AS END_DATE, 
                            1 AS LICENSE_TYPE_ID, 
                            1 AS LICENSE_QTY, 
                            1 AS IS_ACTIVE, 
                            NULL AS LICENSE_NUMBER, 
                            NULL AS CONTRACT_ID, 
                            NULL AS WFM_STATUS_ID 
                        FROM CRM_LEAD CL 
                            LEFT JOIN FIN_INVOICE FI ON CL.LEAD_ID = FI.LEAD_ID
                            LEFT JOIN FIN_INVOICE_DTL FID ON FI.INVOICE_ID = FID.INVOICE_ID
                            LEFT JOIN WH_SALE_PRICE_KEY K ON FID.RELATED_DTL_ID = K.ID
                            LEFT JOIN IM_PACKAGE IP ON FID.ITEM_ID = IP.ITEM_ID
                            LEFT JOIN IM_PACKAGE_DTL IPD ON IP.ID = IPD.PACKAGE_ID
                            LEFT JOIN PLM_PRODUCT PP ON IPD.ITEM_ID = PP.ITEM_ID 
                        WHERE CL.LEAD_ID = $idPh", [$crmLeadId]
                    );
                    
                    if ($packageProductDtl) {
                        
                        foreach ($packageProductDtl as $p => $packageProductRow) {
                            
                            $productId        = $packageProductRow['PRODUCT_ID'];
                            $contractId       = $packageProductRow['CONTRACT_ID'];
                            $licenseTypeId    = $packageProductRow['LICENSE_TYPE_ID'];
                            $licenseQty       = $packageProductRow['LICENSE_QTY'];
                            $licenseStartDate = $packageProductRow['START_DATE'];
                            $licenseEndDate   = $packageProductRow['END_DATE'];
                            $licenseDataCount = 1;
                            $licenseNumber    = '';
                            
                            $licenseKeyData = [
                                'LICENSE_KEY_ID'  => getUIDAdd(($b + 4).$p), 
                                'PRODUCT_ID'      => $productId, 
                                'CUSTOMER_ID'     => $customerId, 
                                'CONTRACT_ID'     => $contractId, 
                                'LICENSE_TYPE_ID' => $licenseTypeId, 
                                'LICENSE_QTY'     => $licenseQty, 
                                'LICENSE_NUMBER'  => $licenseNumber, 
                                'DATA_COUNT'      => $licenseDataCount, 
                                'START_DATE'      => $licenseStartDate, 
                                'END_DATE'        => $licenseEndDate, 
                                'IS_ACTIVE'       => 1, 
                                'CREATED_USER_ID' => 1, 
                                'CREATED_DATE'    => $currentDate
                            ];
                            
                            $this->db->AutoExecute('SYS_LICENSE_KEY', $licenseKeyData);
                        }
                        
                        $apiEnvironmentData = self::getApiEnvironment($idPh, $idTwoPh, $apiEnvironmentId, 'request'); 
                        
                        if ($apiEnvironmentData) {
                            
                            foreach ($apiEnvironmentData as $apiEnvironmentRow) {
                                
                                $apiDbRun    = true;
                                $apiUrl      = $apiEnvironmentRow['URL'];
                                $apiAuthType = $apiEnvironmentRow['AUTHORIZATION_TYPE'];
                                $apiToken    = $apiEnvironmentRow['TOKEN'];
                                $apiJson     = $apiEnvironmentRow['JSON_CONFIG'];
                                $apiJson     = str_replace('{{DOMAIN}}', $domainName, $apiJson);
                                
                                $curl = curl_init();

                                curl_setopt_array($curl, [
                                    CURLOPT_URL => $apiUrl,
                                    CURLOPT_RETURNTRANSFER => true,
                                    CURLOPT_MAXREDIRS => 10,
                                    CURLOPT_TIMEOUT => 30,
                                    CURLOPT_FOLLOWLOCATION => true,
                                    CURLOPT_SSL_VERIFYHOST => false,
                                    CURLOPT_SSL_VERIFYPEER => false,
                                    CURLOPT_POST => true,
                                    CURLOPT_CUSTOMREQUEST => 'POST',
                                    CURLOPT_POSTFIELDS => $apiJson,
                                    CURLOPT_HTTPHEADER => [
                                        'Content-Type: application/json;charset=UTF-8',
                                        'Authorization: '.$apiAuthType.' '.$apiToken
                                    ] 
                                ]);

                                $response = curl_exec($curl);
                                
                                if (curl_errno($curl)) {
                
                                    $msg = curl_error($curl);
                                    curl_close($curl);
                                    
                                    throw new Exception('api curl - '.$msg); 
                                    
                                } else {
                                    curl_close($curl);
                                    
                                    if ($response) {
                                        
                                        $result = json_decode($response, true);

                                        if (isset($result['status']) && is_string($result['status']) && (stripos($result['status'], 'fail') !== false)) {
                                            
                                            $logDtl[] = [
                                                'apiName'  => 'ssystems', 
                                                'apiUrl'   => $apiUrl, 
                                                'apiToken' => $apiAuthType.' '.$apiToken, 
                                                'apiJson'  => $apiJson, 
                                                'apiResponse' => $result['status']
                                            ];
                                            
                                            throw new Exception('api - '.$result['status']); 
                                        }
                                        
                                    } else {
                                        throw new Exception('api - no response'); 
                                    }
                                }
                            }
                            
                        } else {
                            throw new Exception('apiEnvironmentData - No data!'); 
                        }
                        
                    } else {
                        throw new Exception('packageProductDtl - No data!'); 
                    }
                    
                    $encryptCustomerId = Hash::encryption($customerId);
                    
                    $emailSubject      = $emailTemplateRow['SUBJECT'];
                    $emailSubject      = html_entity_decode($emailSubject, ENT_QUOTES, 'UTF-8');
                    $emailTemplate     = $emailTemplateRow['MESSAGE'];
                    $emailTemplate     = html_entity_decode($emailTemplate, ENT_QUOTES, 'UTF-8');
                    $emailTemplate     = str_replace('[domain]', 'cloud.veritech.mn', $emailTemplate);
                    $emailTemplate     = str_replace('[passcode]', $encryptCustomerId, $emailTemplate);
                    
                    $mailResult = Mail::sendPhpMailer([
                        'subject' => $emailSubject, 
                        'altBody' => 'CloudUser - ' . $emailSubject, 
                        'body'    => $emailTemplate, 
                        'toMail'  => $email 
                    ]);

                    if ($mailResult['status'] != 'success') {
                        throw new Exception('mail send - ' . $mailResult['message']);
                    }
                    
                    $this->db->CommitTrans();
                    
                    sleep(60);
                    
                    $this->load->model('login');
                
                    Session::set(SESSION_PREFIX.'isUseMultiDatabase', true);
                    $this->model->setSessionDatabaseConnection(null, $connectionId);

                    $this->load->model('mdlanguage', 'middleware/models/');
                    $this->model->generateLanguageFileModel();
                }
                
                $this->load->model('login');
                $this->model->deleteSessionDatabaseConnection();
                
                $this->load->model('mdmeta', 'middleware/models/');
                $this->model->serviceReloadConfigModel();
                
                $result = ['status' => 'success'];
                
            } else {
                $result = ['status' => 'info', 'message' => 'BankBilling, FinInvoice data empty!'];
            }
            
        } catch (Exception $ex) {
            
            $this->db->RollbackTrans();
            $exceptionMessage = $ex->getMessage();
            
            if (isset($apiDbRun)) {
                $apiEnvironmentData = self::getApiEnvironment($idPh, $idTwoPh, $apiEnvironmentId, 'rollback'); 
                
                if ($apiEnvironmentData) {
                            
                    foreach ($apiEnvironmentData as $apiEnvironmentRow) {

                        $apiUrl      = $apiEnvironmentRow['URL'];
                        $apiAuthType = $apiEnvironmentRow['AUTHORIZATION_TYPE'];
                        $apiToken    = $apiEnvironmentRow['TOKEN'];
                        $apiJson     = $apiEnvironmentRow['JSON_CONFIG'];
                        $apiJson     = str_replace('{{DOMAIN}}', $domainName, $apiJson);

                        $curl = curl_init();

                        curl_setopt_array($curl, [
                            CURLOPT_URL => $apiUrl,
                            CURLOPT_RETURNTRANSFER => true,
                            CURLOPT_MAXREDIRS => 10,
                            CURLOPT_TIMEOUT => 30,
                            CURLOPT_FOLLOWLOCATION => true,
                            CURLOPT_SSL_VERIFYHOST => false,
                            CURLOPT_SSL_VERIFYPEER => false,
                            CURLOPT_POST => true,
                            CURLOPT_CUSTOMREQUEST => 'POST',
                            CURLOPT_POSTFIELDS => $apiJson,
                            CURLOPT_HTTPHEADER => [
                                'Content-Type: application/json;charset=UTF-8',
                                'Authorization: '.$apiAuthType.' '.$apiToken
                            ] 
                        ]);

                        $response = curl_exec($curl);
                        curl_close($curl);
                    }
                }
            }
            
            if (!$logDtl) {
                $logDtl[] = [
                    'apiName'     => 'ssystems', 
                    'apiUrl'      => 'PHP - cloudDbPrepare', 
                    'apiToken'    => null, 
                    'apiJson'     => null, 
                    'apiResponse' => $exceptionMessage
                ];
            }
            
            self::createApiLog($logDtl);
            
            $result = ['status' => 'error', 'message' => $exceptionMessage];
        }
        
        return $result;
    }
    
    public function getApiEnvironment($idPh, $idTwoPh, $id, $typeName) {
        
        $data = $this->db->GetAll("
            SELECT 
                T0.API_ENVIRONMENT_ID, 
                T1.TOKEN, 
                T1.AUTHORIZATION_TYPE, 
                T0.METHOD_TYPE, 
                T0.IS_SAVE_LOG, 
                T0.IS_PARSE_TO_DATA_ELEMENT, 
                T0.PARSE_TO_JSON_FOR_PARAMETER_TYPE, 
                T1.URL||T0.SUB_URL AS URL, 
                T0.JSON_CONFIG 
            FROM API_DETAIL_INFO T0 
                INNER JOIN API_ENVIRONMENT T1 ON T1.ID = T0.API_ENVIRONMENT_ID 
            WHERE T0.API_ENVIRONMENT_ID = $idPh 
                AND LOWER(T0.TYPE_NAME) = $idTwoPh", [$id, strtolower($typeName)]
        );
        
        return $data;
    }
    
    public function getCloudPrepareEmailTemplate($idPh, $id) {
        
        $row = $this->db->GetRow("
            SELECT 
                SUBJECT, 
                MESSAGE 
            FROM NTF_NOTIFICATION 
            WHERE NOTIFICATION_ID = $idPh 
                AND SUBJECT IS NOT NULL 
                AND MESSAGE IS NOT NULL", 
            [$id]
        );
        
        return $row;
    }
    
    public function createApiLog($logDtl) {
        try {
            
            foreach ($logDtl as $l => $logRow) {
                
                $logData = [
                    'ID'               => getUIDAdd($l), 
                    'WEB_SERVICE_NAME' => $logRow['apiName'], 
                    'WEB_SERVICE_URL'  => $logRow['apiUrl'], 
                    'PARAMETER_DE'     => $logRow['apiToken'],
                    'RESPONSE_STRING'  => $logRow['apiResponse'], 
                    'CREATED_DATE'     => Date::currentDate()
                ];
                $this->db->AutoExecute('SYSINT_SERVICE_METHOD_LOG', $logData);
                $this->db->UpdateClob('SYSINT_SERVICE_METHOD_LOG', 'REQUEST_STRING', issetParam($logRow['apiJson']), 'ID = '.$logData['ID']);
            }
            
        } catch (Exception $ex) { }
        
        return true;
    }

}