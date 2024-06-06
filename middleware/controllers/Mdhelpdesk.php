<?php if (!defined('_VALID_PHP')) exit('Direct access to this location is not allowed.');

/**
 * Mdhelpdesk Class 
 * 
 * @package     IA PHPframework
 * @subpackage	Middleware
 * @category	Mdhelpdesk (Санал хүсэлт)
 * @author	B.Och-Erdene <ocherdene@veritech.mn>
 * @link	http://www.veritech.mn/PHPframework/Middleware/Mdhelpdesk
 */

class Mdhelpdesk extends Controller {
    
    private $viewPath = 'middleware/views/helpdesk/';
    private $serviceAddress = GF_SERVICE_ADDRESS;
    
    public function __construct() {
        parent::__construct();
    }
    
    public function index(){}  
    
    public function ticket($orgId, $systemId)
    {
        if (ENVIRONMENT == 'development') {
            $this->view->fulljs = array('middleware/assets/js/mdhelpdesk.js');
            $this->view->footjs = array(
                '<script type="text/javascript">
                    helpDeskPopup('.$orgId.', '.$systemId.');    
                </script>'
            );
        }
    }
    
    public function viewTickets()
    {
        $orgId = Input::post('orgId');
        $sysId = Input::post('sysId');
        $this->view->tickets = json_decode(file_get_contents('http://www.veritech.mn/helpdesk/ticket_api/get/json/'.$orgId.'/'.$sysId));
        $this->view->render('viewTickets', $this->viewPath);
    }
    
    public function login() 
    {
        Auth::handleLogin();
        
        $row = $this->db->GetRow("SELECT EMAIL FROM UM_SYSTEM_USER WHERE EMAIL IS NOT NULL AND USER_ID = ".Ue::sessionUserId()); 
        
        if ($row) {
            $authData = 'email='.$row['EMAIL'].'&date='.Date::currentDate('Y-m-d').'&time='.Date::currentDate('H:i:s');
            header('location: http://helpdesk.veritech.mn/login/auth/'.Crypt::encrypt($authData, 'hd'));
        } else {
            header('location: http://helpdesk.veritech.mn/login');
        }
    }
    
    public function ssoLogin() 
    {
        Auth::handleLogin();
        $this->view->isAjax = is_ajax_request();
        
        $result = $this->ws->runArrayResponse(GF_SERVICE_ADDRESS, 'CHECK_UM_USER_004', ['filterUserId' => Ue::sessionUserId()]);
        
        try {
            
            if (issetParam($result['status']) == 'success' && isset($result['result'])) {
            
                if (!issetParam($result['result']['email'])) {
                    throw new Exception('И-мэйл тохируулаагүй байна!'); 
                }
    
                $result['result']['expiredate'] = Date::currentDate('Y-m-d H:i:s');
                
                $hashJson = json_encode($result['result'], JSON_UNESCAPED_UNICODE);
                $hash = Hash::encryption($hashJson);
                $hash = str_replace(['+', '='], ['tttnmhttt', 'ttttntsuttt'], $hash);
                $response = ['status' => 'success', 'href' => 'https://help.veritech.mn/login/authorization?user='. $hash];
                
            } else {
                throw new Exception('No data! CHECK_UM_USER_004'); 
            }

        } catch (Exception $ex) {
            $response = ['status' => 'error', 'message' => $ex->getMessage(), 'ex' => $ex];
        }

        if (!$this->view->isAjax) {
            if ($response['status'] === 'error') {
                echo $response['message'];
            } else {
                header('location: ' . $response['href']);
            }
        } else {
            convJson($response);
        }
    }
    
    public function getCustomer()
    {
        $param = array(
            'systemMetaGroupId' => '1486457828616554',
            'showQuery' => '0', 
            'ignorePermission' => '1', 
            'paging' => array(
                'sortColumnNames' => array(
                    'customername' => array(
                        'sortType' => 'asc'
                    )
                ), 
                'offset' => 1, 
                'pageSize' => 300
            )
        );
        
        $result = $this->ws->runSerializeResponse($this->serviceAddress, Mddatamodel::$getDataViewCommand, $param);
        
        unset($result['result']['paging']);
        unset($result['result']['aggregatecolumns']);

        echo json_encode($result); exit;
    }
    
    public function getTaskType()
    {
        $param = array(
            'systemMetaGroupId' => '1477069621342',
            'showQuery' => '0', 
            'ignorePermission' => '1'
        );
        
        $result = $this->ws->runSerializeResponse($this->serviceAddress, Mddatamodel::$getDataViewCommand, $param);
        
        unset($result['result']['paging']);
        unset($result['result']['aggregatecolumns']);
        
        echo json_encode($result); exit;
    }
    
    public function getTaskPriority()
    {
        $param = array(
            'systemMetaGroupId' => '1477069618976',
            'showQuery' => '0', 
            'ignorePermission' => '1'
        );
        
        $result = $this->ws->runSerializeResponse($this->serviceAddress, Mddatamodel::$getDataViewCommand, $param);

        echo json_encode($result); exit;
    }
    
    public function getTaskPerformer()
    {
        $param = array(
            'systemMetaGroupId' => '1477729865264662',
            'showQuery' => '0', 
            'ignorePermission' => '1', 
            'paging' => array(
                'sortColumnNames' => array(
                    'firstname' => array(
                        'sortType' => 'asc'
                    )
                ), 
                'offset' => 1, 
                'pageSize' => 300
            )
        );
        
        $result = $this->ws->runSerializeResponse($this->serviceAddress, Mddatamodel::$getDataViewCommand, $param);
        
        unset($result['result']['paging']);
        unset($result['result']['aggregatecolumns']);
        
        echo json_encode($result); exit;
    }
    
    public function getTaskCategory()
    {
        $param = array(
            'systemMetaGroupId' => '1478851908491',
            'showQuery' => '0', 
            'ignorePermission' => '1', 
            'paging' => array( 
                'offset' => 1, 
                'pageSize' => 300
            )
        );
        
        $result = $this->ws->runSerializeResponse($this->serviceAddress, Mddatamodel::$getDataViewCommand, $param);
        
        unset($result['result']['paging']);
        unset($result['result']['aggregatecolumns']);
        
        echo json_encode($result); exit;
    }
    
    public function getTaskSystem()
    {
        $param = array(
            'systemMetaGroupId' => '1484534102076',
            'showQuery' => '0', 
            'ignorePermission' => '1', 
            'paging' => array( 
                'offset' => 1, 
                'pageSize' => 300
            )
        );
        
        $result = $this->ws->runSerializeResponse($this->serviceAddress, Mddatamodel::$getDataViewCommand, $param);
        
        unset($result['result']['paging']);
        unset($result['result']['aggregatecolumns']);
        
        echo json_encode($result); exit;
    }
    
    public function getTaskModule()
    {
        $param = array(
            'systemMetaGroupId' => '1484617399211397',
            'showQuery' => '0', 
            'ignorePermission' => '1', 
            'paging' => array( 
                'offset' => 1, 
                'pageSize' => 300
            )
        );
        
        $result = $this->ws->runSerializeResponse($this->serviceAddress, Mddatamodel::$getDataViewCommand, $param);
        
        unset($result['result']['paging']);
        unset($result['result']['aggregatecolumns']);
        
        echo json_encode($result); exit;
    }
    
    public function saveSyncData() {
        
        $jsonBody = file_get_contents('php://input');
        $param = json_decode($jsonBody, true);
        
        $organizationId = $param['organizationId'];
        $taskName       = $param['taskName'];
        $typeId         = $param['typeId'];
        $priorityId     = $param['priorityId'];
        $categoryId     = $param['categoryId'];
        $description    = Security::html($param['description']);
        $ticketId       = $param['ticketId'];
        $taskCode       = '';
        
        $paramCode = array(
            'objectId' => '1477069618895' 
        );
        
        $resultCode = $this->ws->runSerializeResponse($this->serviceAddress, 'CRM_AUTONUMBER_BP', $paramCode);
        
        if ($resultCode['status'] == 'success' && isset($resultCode['result'])) {
            $taskCode = $this->ws->getValue($resultCode['result']);
        }
        
        $param = array(
            'taskCode' => $taskCode, 
            'taskName' => $taskName, 
            'description' => $description,
            'isArchived' => '1',
            'taskTypeId' => $typeId, 
            'priorityId' => $priorityId, 
            'isRepeatedTask' => '0', 
            'version' => '1', 
            'helpdeskId' => $ticketId, 
            'customerId' => $organizationId, 
            'createdUserId' => '1', 
            /*'TM_TASK_ASSIGNMENT_DATAVIEW' => array(
                array(
                    'userId' => $performerId, 
                    'isGrouptask' => '0', 
                    'isActive' => '0'
                )
            ),*/
            'TM_TASK_CATEGORY_MAP_DV' => array(
                array(
                    'categoryId' => $categoryId 
                )
            ), 
            'META_DM_RECORD_MAP_TASK' => array(
                array(
                    'srcTableName' => 'TM_TASK', 
                    'trgTableName' => 'CRM_CUSTOMER', 
                    'trgRecordId' => $organizationId, 
                    'semanticTypeId' => '1'
                )
            )
        );
        
        if (isset($param['systemId']) && $param['systemId'] != '') {
            
            $relationParams = array(
                array(
                    'tableName' => 'UM_SYSTEM',
                    'isSource' => 1,
                    'onDeleteCondition' => 'noaction',
                    'onUpdateCondition' => 'noaction',
                    'recordIds' => array(array('id' => Input::post('systemId'))),
                    'semanticTypeId' => 1 
                )
            );
            $param['autoMapParams'] = $relationParams;
        }
        
        if (isset($param['moduleId']) && $param['moduleId'] != '') {
            
            $relationParams = array(
                array(
                    'tableName' => 'UM_MODULE',
                    'isSource' => 1,
                    'onDeleteCondition' => 'noaction',
                    'onUpdateCondition' => 'noaction',
                    'recordIds' => array(array('id' => $param['moduleId'])),
                    'semanticTypeId' => 1 
                )
            );
            
            if (isset($param['autoMapParams'])) {
                $param['autoMapParams'] = array_merge($param['autoMapParams'], $relationParams);
            } else {
                $param['autoMapParams'] = $relationParams;
            }
        }
        
        $result = $this->ws->runSerializeResponse($this->serviceAddress, 'TM_TASK_HELP_SYNC_001', $param);

        echo json_encode($result); exit;
    }
    
    public function saveSyncDataV2() {
        
        $jsonBody = file_get_contents('php://input');
        $param = json_decode($jsonBody, true);
        $syncProcessCode = $param['syncProcessCode'];
        
        $result = $this->ws->runSerializeResponse($this->serviceAddress, $syncProcessCode, $param);
        
        if ($result['status'] == 'success' && isset($result['result']['id']) && $result['result']['id'] && isset($param['ecmContent'])) {
            
            $id = $result['result']['id'];
            $ecmContent = $param['ecmContent'];
            
            $this->load->model('mdwebservice', 'middleware/models/');
            
            $row = $this->model->getProcessConfigByCode($syncProcessCode);
            $refMetaGroupId = $row['REF_META_GROUP_ID'];
            
            if ($refMetaGroupId) {
                $currentDate = Date::currentDate('Y-m-d H:i:s');
                
                foreach ($ecmContent as $k => $content) {
                    
                    $fileName = $content['fileName'];
                    $fileExtension = strtolower($content['fileExtension']);
                    $fileBase64 = $content['fileBase64'];
                    $contentId = getUIDAdd($k);
                    
                    if ($fileExtension = 'jpeg' || $fileExtension = 'jpg' || $fileExtension = 'png' || $fileExtension = 'gif' || $fileExtension = 'bmp') {
                        $filePath = Mdwebservice::bpUploadCustomPath('/metavalue/photo_original/') . $contentId . '.' . $fileExtension;
                        $isPhoto = 1;
                    } else {
                        $filePath = Mdwebservice::bpUploadCustomPath('/metavalue/file/') . $contentId . '.' . $fileExtension;
                        $isPhoto = 0;
                    }
                    
                    file_put_contents($filePath, base64_decode($fileBase64));
                    
                    $dataContent = array(
                        'CONTENT_ID'           => $contentId,
                        'FILE_NAME'            => $fileName,
                        'PHYSICAL_PATH'        => $filePath, 
                        'FILE_EXTENSION'       => $fileExtension, 
                        'FILE_SIZE'            => filesize($filePath),
                        'CREATED_USER_ID'      => 1,
                        'CREATED_DATE'         => $currentDate,
                        'IS_PHOTO'             => $isPhoto
                    );
                    $dataContentFile = $this->db->AutoExecute('ECM_CONTENT', $dataContent);
                    
                    if ($dataContentFile) {
                        
                        $dataContentMap = array(
                            'ID'               => $contentId,
                            'REF_STRUCTURE_ID' => $refMetaGroupId,
                            'RECORD_ID'        => $id,
                            'CONTENT_ID'       => $contentId,
                            'ORDER_NUM'        => ($k + 1)
                        );
                        $this->db->AutoExecute('ECM_CONTENT_MAP', $dataContentMap);
                    }
                }
            }
        }

        echo json_encode($result, JSON_UNESCAPED_UNICODE);
    }
    
    public function testmail() {
        includeLib('Mail/PHPMailer/v2/PHPMailerAutoload');
            
        $mail = new PHPMailer();
        $mail->CharSet = 'UTF-8';
        $mail->isSMTP();
        $mail->SMTPDebug = 0;
        $mail->SMTPAuth = true;
        $mail->SMTPSecure = false;
        $mail->Host = 'host154.hostmonster.com';
        $mail->Port = 26;
        $mail->Username = 'noreply@interactive.mn';
        $mail->Password = 'RN$N2xMaOQi6';
        $mail->setFrom('noreply@interactive.mn', 'Test Mail');
        $mail->isHTML(true);
        $mail->Subject = 'Test Mail';
        $mail->Body = 'Test body mail';
        $mail->AltBody = 'Veritech Test Mail';

        $mail->addAddress('ochoo0909@gmail.com');

        if ($mail->send()) {
            $response = array('status' => 'success', 'message' => 'Амжилттай илгээгдлээ');
        } else {
            $response = array('status' => 'error', 'message' => $mail->ErrorInfo);
        }
        
        var_dump($response);die;
    }
    
    public function sendmail($protocol = '') {
        
        set_time_limit(0);
        
        includeLib('Mail/PHPMailer/v2/PHPMailerAutoload');

        $mail = new PHPMailer();
        $mail->CharSet = 'UTF-8';
        $mail->isSMTP();
        $mail->SMTPDebug = 1;
        
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
        if (defined('SMTP_AUTOTLS')) {
            $mail->SMTPAutoTLS = SMTP_AUTOTLS;
        }
        $mail->Port = SMTP_PORT;
        $mail->setFrom(EMAIL_FROM, EMAIL_FROM_NAME); 
        $mail->AddReplyTo(EMAIL_FROM, EMAIL_FROM_NAME);
        $mail->Subject = 'Test Mail';
        $mail->isHTML(true);
        $mail->Body = 'Test body mail';
        $mail->AltBody = 'Veritech Test Mail';
        
        $mail->addAddress('ochoo0909@gmail.com');
        $mail->addAddress('ulaankhuu@veritech.mn');

        if ($mail->send()) {
            $response = array('status' => 'success', 'message' => 'Амжилттай илгээгдлээ');
        } else {
            $response = array('status' => 'error', 'message' => $mail->ErrorInfo);
        }
        
        var_dump($response);die;
    }
    
    public function testics() {
        
        includeLib('Mail/PHPMailer/v2/PHPMailerAutoload');
        
        $event_id = 1234;
        $sequence = 0;
        $status = 'TENTATIVE';
        
        $summary = 'Summary of the event';
        $venue = 'Ulaanbaatar';
        $start = '20211117';
        $start_time = '160630';
        $end = '20211118';
        $end_time = '180630';

        //PHPMailer
        $mail = new PHPMailer();
        $mail->CharSet = 'UTF-8';
        $mail->isSMTP();
        $mail->SMTPDebug = 1;
        
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
        
        $mail->IsHTML(false);
        $mail->setFrom(EMAIL_FROM, EMAIL_FROM_NAME); 
        $mail->AddReplyTo(EMAIL_FROM, EMAIL_FROM_NAME);
        
        $mail->addAddress('ocherdene@veritech.mn');
        $mail->ContentType = 'text/calendar';

        $mail->Subject = "Outlooked Event";
        $mail->addCustomHeader('MIME-version',"1.0");
        $mail->addCustomHeader('Content-type',"text/calendar; method=REQUEST; charset=UTF-8");
        $mail->addCustomHeader('Content-Transfer-Encoding',"7bit");
        $mail->addCustomHeader('X-Mailer',"Microsoft Office Outlook 12.0");
        $mail->addCustomHeader("Content-class: urn:content-classes:calendarmessage");

        $ical = "BEGIN:VCALENDAR\r\n";
        $ical .= "VERSION:2.0\r\n";
        $ical .= "PRODID:-//YourCassavaLtd//EateriesDept//EN\r\n";
        $ical .= "METHOD:REQUEST\r\n";
        $ical .= "BEGIN:VEVENT\r\n";
        $ical .= "UID:".strtoupper(md5($event_id))."-kaserver.com\r\n";
        $ical .= "SEQUENCE:".$sequence."\r\n";
        $ical .= "STATUS:".$status."\r\n";
        $ical .= "DTSTAMPTZID=Asia/Ulaanbaatar:".date('Ymd').'T'.date('His')."\r\n";
        $ical .= "DTSTART:".$start."T".$start_time."\r\n";
        $ical .= "DTEND:".$end."T".$end_time."\r\n";
        $ical .= "LOCATION:".$venue."\r\n";
        $ical .= "SUMMARY:".$summary."\r\n";
        $ical .= "DESCRIPTION:test descr 123\r\n";
        $ical .= "BEGIN:VALARM\r\n";
        $ical .= "TRIGGER:-PT15M\r\n";
        $ical .= "ACTION:DISPLAY\r\n";
        $ical .= "DESCRIPTION:Reminder\r\n";
        $ical .= "END:VALARM\r\n";
        $ical .= "END:VEVENT\r\n";
        $ical .= "END:VCALENDAR\r\n";

        $mail->Body = $ical;

        //send the message, check for errors
        if (!$mail->send()) {
            echo "Mailer Error: " . $mail->ErrorInfo;
        } else {
            echo "Message sent!";
        }
        
        exit;
    }

    public function getUsers($name)
    {
        $param = array(
            'systemMetaGroupId' => '1584674637412',
            'showQuery' => '0', 
            'ignorePermission' => '1', 
            'criteria' => array(
                'externalCode' => array(
                    array(
                        'operator' => 'like',
                        'operand' => '%'.$name.'%'
                    )
                )
            ),            
            'paging' => array(
                'sortColumnNames' => array(
                    'customername' => array(
                        'sortType' => 'asc'
                    )
                ), 
                'offset' => 1, 
                'pageSize' => 300
            )
        );
        
        $result = $this->ws->runSerializeResponse($this->serviceAddress, Mddatamodel::$getDataViewCommand, $param);
        
        unset($result['result']['paging']);
        unset($result['result']['aggregatecolumns']);

        echo json_encode($result); exit;
    }    

    public function getProjects()
    {
        $jsonBody = file_get_contents('php://input');
        $input = json_decode($jsonBody, true);    

        $param = array(
            'systemMetaGroupId' => '1584674634799',
            'showQuery' => '0', 
            'ignorePermission' => '1', 
            'criteria' => array(
                'externalCode' => array(
                    array(
                        'operator' => 'like',
                        'operand' => '%'.$input['name'].'%'
                    )
                )
            ),            
            'paging' => array(
                'sortColumnNames' => array(
                    'customername' => array(
                        'sortType' => 'asc'
                    )
                ), 
                'offset' => 1, 
                'pageSize' => 300
            )
        );
                
        $result = $this->ws->runSerializeResponse($this->serviceAddress, Mddatamodel::$getDataViewCommand, $param);
        
        unset($result['result']['paging']);
        unset($result['result']['aggregatecolumns']);        

        echo json_encode($result); exit;
    }    

    public function getPriority()
    {
        $param = array(
            'systemMetaGroupId' => '1584674638909',
            'showQuery' => '0', 
            'ignorePermission' => '1', 
            'paging' => array(
                'sortColumnNames' => array(
                    'customername' => array(
                        'sortType' => 'asc'
                    )
                ), 
                'offset' => 1, 
                'pageSize' => 300
            )
        );
        
        $result = $this->ws->runSerializeResponse($this->serviceAddress, Mddatamodel::$getDataViewCommand, $param);
        
        unset($result['result']['paging']);
        unset($result['result']['aggregatecolumns']);

        echo json_encode($result); exit;
    }    

    public function sendSyncData() {
        
        $jsonBody = file_get_contents('php://input');
        $param = json_decode($jsonBody, true);   
        
        if ($assignUserId = issetVar($param['assignUserId'])) {
            $userInfo = self::getUserInfoModel($assignUserId);
            
            if (isset($userInfo['picture']) && isset($userInfo['firstname'])) {
                $param['assignPicture'] = $userInfo['picture'];
                $param['assignUserName'] = $userInfo['firstname'];
            }
        }
        
        $result = $this->ws->runSerializeResponse($this->serviceAddress, 'TM_TASK_NEW_001_helpdesk', $param);        

        echo json_encode($result); exit;
    }    
    
    public function getUserInfoModel($id) {
        
        $param = array(
            'systemMetaGroupId' => '1477729865264662',
            'showQuery' => '0', 
            'ignorePermission' => '1', 
            'criteria' => array(
                'id' =>  array(
                    array(
                        'operator' => '=',
                        'operand' => $id
                    )
                )
            ),      
        );
        
        $result = $this->ws->runSerializeResponse($this->serviceAddress, Mddatamodel::$getDataViewCommand, $param);
        
        unset($result['result']['paging']);
        unset($result['result']['aggregatecolumns']);

        if (isset($result['result'][0])) {
            return $result['result'][0];
        }
        
        return null;
    }    
    
    public function cloud_user_help($contentId = '') 
    {
        Auth::handleLogin();
        $isAjax = is_ajax_request();
        
        if ($isAjax == false && $contentId == '') {
            Message::add('d', '', URL . 'appmenu/indexnew');
        }
        
        if ($isAjax == true && $contentId == '') {
            $contentId = Input::numeric('contentId');
        }
        
        $result = [
            'expiredate'     => Date::currentDate('Y-m-d H:i:s'), 
            'username'       => 'clouduser', 
            'firstname'      => 'cloud', 
            'lastname'       => 'cloud', 
            'stateregnumber' => 'cloud', 
            'email'          => 'cloud@veritech.mn', 
            'phone'          => null, 
            'departmentcode' => 'CLOUD', 
            'roleid'         => null,
            'inactive'       => 0
        ];
        
        $hashJson = json_encode($result, JSON_UNESCAPED_UNICODE);
        $hash     = Hash::encryption($hashJson);
        $hash     = str_replace(['+', '='], ['tttnmhttt', 'ttttntsuttt'], $hash);
        
        $helpUrl  = 'https://help.veritech.mn/login/authorization?user='.$hash.'&filterid='.$contentId;
        
        if ($isAjax == false) {
            header('location: '.$helpUrl);
        } else {
            convJson(['status' => 'success', 'url' => $helpUrl]);
        }
    }
    
}