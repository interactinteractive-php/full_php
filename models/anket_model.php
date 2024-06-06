<?php if(!defined('_VALID_PHP')) exit('Direct access to this location is not allowed.');

class Anket_Model extends Model {

    private static $jobListDvId = '1500532124048';

    public function __construct() {
        parent::__construct();
    }

    public function getJobDataById($id) {
        $inparams = array(
            'id' => $id
        );
        $get = $this->ws->runResponse(GF_SERVICE_ADDRESS, "HRM_ACTIVE_RECRUIT_POSITION_LIST2_004", $inparams);
        $getRow = array();

        if ($get['status'] === 'success') {
            return $getRow = $get['result'];
        }

        return $getRow;
    }
    
    public function getJobDetailPrompModel($dvId, $criteria = array()) {
        
        $param = array(
            'systemMetaGroupId' => $dvId,
            'ignorePermission' => 1, 
            'showQuery' => 0,
            'criteria' => $criteria
        );

        $result = $this->ws->runSerializeResponse(GF_SERVICE_ADDRESS, Mddatamodel::$getDataViewCommand, $param);

        if (isset($result['result']) && isset($result['result'][0])) {
            unset($result['result']['aggregatecolumns']);
            unset($result['result']['paging']);
            $data = $result['result'];
        } else {
            $data = array();
        }
        
        return $data;
    }
    
    public function getJobListModel() {
        $page = Input::postCheck('page') ? Input::post('page') : 1;
        $rows = Input::postCheck('rows') ? Input::post('rows') : 20;
        $offset = ($page - 1) * $rows;

        $param = array(
            'systemMetaGroupId' => self::$jobListDvId,
            'showQuery' => 0,
            'paging' => array(
            'offset' => $page,
            'pageSize' => $rows,
            ),
            'criteria' => array()
        );

        $response = $this->ws->runSerializeResponse(GF_SERVICE_ADDRESS, Mddatamodel::$getDataViewCommand, $param);

        if ($response['status'] == 'success' && isset($response['result'])) {
            $response['totalCount'] = $response['result']['paging']['totalcount'];
            unset($response['result']['paging']);
            unset($response['result']['aggregatecolumns']);
        }
        
        return $response;
    }

    public function getJobDetailModel($id) {
        $param = array(
            'id' => $id
        );

        $result = $this->ws->runSerializeResponse(GF_SERVICE_ADDRESS, 'recruitPositionDescription_004', $param);

        if ($result['status'] == 'success' && isset($result['result'])) {
            return $result['result'];
        }

        return null;
    }

    public function checkIsEndCkModel($campaignKeyId = null) {

        $result = false;

        if ($campaignKeyId) {
            
            $resultEndDate = $this->db->GetOne("
                SELECT
                    HRCM.END_DATE 
                FROM HRM_RECRUIT_CHANNEL_MAP HRCM 
                    INNER JOIN HRM_JOB_AD_SUB_CHANNEL HJASC ON HRCM.SUB_CHANNEL_ID = HJASC.SUB_CHANNEL_ID 
                WHERE HRCM.CAMPAIGN_KEY_ID = " . $campaignKeyId);
            
            $resultEndDate = Date::formatter($resultEndDate, 'Y-m-d H:i');
            $date = Date::currentDate('Y-m-d H:i');

            if (!is_null($resultEndDate) && strtotime($date) > strtotime($resultEndDate)) {
                $result = true;
            }
        }

        return $result;
    }
    
    public function sendMailModel() {
            
        $emailTo = Input::post('email');
        $positionName = Input::post('positionName');
        $langCode = $this->lang->getCode();

        $emailBodyContent = file_get_contents('middleware/views/metadata/dataview/form/email_templates/blank.html');

        if ($langCode == 'mn') {
            
            $emailSubject = 'Оюу Толгой - Таны анкетыг хүлээн авлаа';
            
            $emailBody = 'Сайн байна уу?<br /><br />
            Таны "<strong>'.$positionName.'</strong>" ажлын байранд ирүүлсэн материалыг хүлээн авлаа.<br /><br />
            Хүндэтгэсэн,<br />
            Хүний нөөц бүрдүүлэлтийн баг<br />';
            
        } else {
            
            $emailSubject = 'Oyu Tolgoi - Your document was recieved';
            
            $emailBody = 'Dear Sir /Madam<br /><br />
            We have received your applications for the "<strong>'.$positionName.'</strong>" role.<br /><br />
            Regards,<br />
            OT Recruitment Team<br />';
        }
        
        $emailBodyContent = str_replace('{content}', $emailBody, $emailBodyContent);

        $emailFrom = EMAIL_FROM;        
        $emailFromName = EMAIL_FROM_NAME;
                
        includeLib('Mail/PHPMailer/v2/PHPMailerAutoload');

        $mail = new PHPMailer();
        $mail->CharSet = 'UTF-8';
        $mail->isSMTP();
        $mail->SMTPDebug = 0;
        $mail->Host = SMTP_HOST;
        $mail->Port = SMTP_PORT;

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
            $mail->SMTPAuth = true;
            $mail->Username = SMTP_USER; 
            $mail->Password = SMTP_PASS; 
        }

        $mail->setFrom($emailFrom, $emailFromName); 
        $mail->Subject = $emailSubject;
        $mail->isHTML(true);
        $mail->msgHTML($emailBodyContent);
        $mail->AltBody = $emailSubject;
        
        $mail->addAddress($emailTo);

        if (!$mail->send()) {
            $response = array('status' => 'error', 'message' => $mail->ErrorInfo);
        } else {
            $response = array('status' => 'success', 'message' => 'Амжилттай илгээгдлээ');
        }   

        return $response;
    }
    
}