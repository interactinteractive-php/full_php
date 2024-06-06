<?php if(!defined('_VALID_PHP')) exit('Direct access to this location is not allowed.');

class Token extends Controller {
    
    public function __construct() {
        parent::__construct();
    }
    
    public function index() {
        
        set_status_header(404);
        
        $err = Controller::loadController('Err');
        $err->index();
        exit;
    }

    public function uploadUrl() {
        function outputJSON($msg, $status = 'error') {
            header('Content-Type: application/json');
            die(json_encode(array(
                'data' => $msg,
                'status' => $status
            )));
        }

        if ($_FILES['uplTheFile']['error'] > 0) {
            outputJSON('An error ocurred when uploading.');
        }

        if ($_FILES['uplTheFile']['size'] > 20000000) {
            outputJSON('File uploaded exceeds maximum upload size.');
        }

        $file = 'log/M004.txt';
        $newFileName = Str::lower($_FILES['uplTheFile']['name']);

        $current = file_get_contents($file);
        $current .= "\n signedFilePath : " . $newFileName . ' time: '. Date::currentDate();
        file_put_contents($file, $current);

        $date              = Date::currentDate('Ym');
        $newPath = UPLOADPATH . 'signed_file/' . $date . '/';

        if (!is_dir($newPath)) {
            mkdir($newPath, 0777, true);
        }

        $filePath = $newPath . $newFileName;
        if (file_exists($filePath)) {
            chmod($filePath, 0755);
            unlink($filePath);
        }

        if (!move_uploaded_file($_FILES['uplTheFile']['tmp_name'], $filePath)) {
            outputJSON('Error uploading file - check destination is writeable.');
        }
        outputJSON($newFileName, 'success');
    }

    public function tokenCheck() {
        $sessionId = Input::post('seasonId');
        $url = Input::post('loginUrl');
        $data = array('seasonId' => $sessionId);

        $options = array(
            'http' => array(
                'header' => "Content-type: application/x-www-form-urlencoded\r\n",
                'method' => 'POST',
                'content' => http_build_query($data)
            )
        );
        
        $context = stream_context_create($options);
        $resultUrl = file_get_contents($url, false, $context);

        if ($resultUrl === FALSE) {
            echo json_encode(array('message' => "Уучлаарай! Нэвтрэх үйлдэл амжилтгүй боллоо!"));
            die;
        }

        $res = json_decode($resultUrl);

        $status = false;
        $guid = null;

        if (isset($res->status)) {
            $status = $res->status;
        }

        if ($status == 'Success') {
            $guid = $res->message;
        } else if (isset($res->message)) {
            echo json_encode(array('message' => $res->message));
            die;
        }

        if ($guid == null) {
            $resultUrl = substr($resultUrl, 0, -1);
            $res = json_decode($resultUrl);

            if (isset($res->status)) {
                $status = $res->status;
            }

            if ($status == 'Success') {
                $guid = $res->message;
            }
        }

        echo json_encode(array('message' => $guid));
    }
    
    public function stressTest1() {
        $params = array ( 'id' => getUID(), 'code' => getUID(), 'name' => getUID(), 'createdDate' => Date::currentDate());
        $result = $this->ws->caller('WSDL-DE', SERVICE_FULL_ADDRESS, 'stress_test_001_001', 'return', $params, 'serialize');
        
        print_array($result);
        die;
    }
    
    public function stressTest2() {
        $result = $this->db->AutoExecute('TEMP_TABLE_TEST', array('ID' => getUID(), 'CODE' => getUID(), 'NAME' => getUID(), 'CREATED_DATE' => Date::currentDate()));
        print_array($result);
        die;
    }

    public function registerMonpassUser() {
        $result = $this->model->MapMonpassUser();

        if ($result) {
            echo json_encode(array('status' => 'success', 'message' => Lang::line('msg_save_success')));
        } else {
            echo json_encode(array('status' => 'Error', 'message' => Lang::line('msg_save_error')));
        }
    }

    public function runETokenMenu() {
        
        $url = MONPASS_SERVER.'/TokenLogin/LoginCheck';
        $data = array('seasonId' => Input::post("seasonId"));

        $options = array(
            'http' => array(
                'header' => "Content-type: application/x-www-form-urlencoded\r\n",
                'method' => 'POST',
                'content' => http_build_query($data)
            )
        );
		
        $context = stream_context_create($options);
        $result = file_get_contents($url, false, $context);

        if ($result === FALSE) { /* Handle error */ }

        $res = json_decode($result);

        $status = $res->status;

        if ($status == 'Success') {
            Message::add('i', '', URL . Input::post("redirecturl"));
        } else {
            Message::add('d', Lang::line('login_error_msg'), 'now');
        }
    }    
    
    public function dan() {
        
        $state = Input::get('state');
        $operationName = 'WS100101_getCitizenIDCardInfo';

        if ($state) {

            $service_structure = array( 
                array( 'services' => array("WS100101_getCitizenIDCardInfo"), 'wsdl' => "https://xyp.gov.mn/citizen-1.3.0/ws?WSDL" )
            );

            if(!empty($service_structure)) {
                $scope = base64_encode(json_encode($service_structure));
            
                if (is_array($_SESSION['danOauthState'])){
                    array_push($_SESSION['danOauthState'], $state);
                } 
                else{
                    $_SESSION['danOauthState'] = array();
                    array_push($_SESSION['danOauthState'], $state);
                }
                
                $clientId = Config::getFromCache('DAN_CLIENT_ID');
                $redirectUri = URL . Config::getFromCache('DAN_REDIRECT_URI');

                $newURL = 'https://sso.gov.mn/oauth2/authorize?response_type=code&client_id=' . $clientId . '&redirect_uri=' . $redirectUri . '&scope=' . $scope . '&state=' . $state;
                header('Location: '.$newURL);
            } 
            else{
                echo 'Error: No operation name sent';
            }

        }
        else {
            echo 'Error: No state value sent';
        }
    }

    public function ekhalamj() {
        
        $state = Input::get('state');

        if ($state) {
            
            if (is_array($_SESSION['danOauthState'])){
                array_push($_SESSION['danOauthState'], $state);
            } 
            else{
                $_SESSION['danOauthState'] = array();
                array_push($_SESSION['danOauthState'], $state);
            }

            $clientId = Config::getFromCache('KHALAMJ_CLIENT_ID');
            $redirectUri = URL . Config::getFromCache('KHALAMJ_REDIRECT_URI');
            
            $newURL = Config::getFromCache('KHALAMJ_MAIN_URL') . '/auth?state='. $state .'&response_type=code&client_id='. $clientId .'&redirect_uri=' . $redirectUri;

            header('Location: '.$newURL);

        }
        else {
            echo 'Error: No state value sent';
        }
    }

    public function create_table($table_name = 'service_reqs') {

        $data = $this->db->GetRow("SELECT * FROM $table_name");
        var_dump($data);
        $parseStr = json_decode($data['data'], true);
        
        /* s */

        self::table_columns($parseStr, $table_name);
        die;

    }

    public function table_columns($parseStr = array(), $table_name, $index = 1) {

        $columnsArr = array();
        foreach ($parseStr as $key => $row) {
            if (is_array($row)) {
                $index = $index + 1;
                $table_name = $table_name . '_' . $key;
                self::table_columns($row, $table_name, $index);
            } else {
                array_push($columnsArr, $key);
            }
        }

        echo '--------- index = ' . $index . ' ----- ' . $table_name . '<br>' ;
        var_dump($columnsArr);
        echo '<br>--------- index = ' . $index. '<br>' ;

    }
}
