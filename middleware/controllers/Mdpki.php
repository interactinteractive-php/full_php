<?php if (!defined('_VALID_PHP')) exit('Direct access to this location is not allowed.');

/**
 * Mdpki Class 
 * 
 * @package     IA PHPframework
 * @subpackage	Middleware
 * @category	PKI
 * @author	B.Munkh-Erdene <munkherdene@interactive.mn>
 * @link	http://www.interactive.mn/PHPframework/Middleware/Mdpki
 */
class Mdpki extends Controller {

    private static $signFileUploadUrlAddress = "token/uploadUrl";
    
    public function __construct() {
        parent::__construct();
        Auth::handleLogin();
    }
    
    /**
     * Жагсаалтын цонхон дээрээс get ажлуулж xml цуглуулж xml болон hash үүсгэх
     */
    public function signByGet() {
        $paramCode = Input::post('paramCode');
        
        (Array) $paramFilters = array();
        (Array) $param = array(
            'systemMetaGroupId' => Input::numeric('metaDataId'),
            'showQuery' => 0,
        );
        $response = array(
            'status' => 'warning',
            'message'=> 'Амжилтгүй боллоо'
        );

        if (Input::postCheck('dataRow') && !Input::isEmpty('dataRow')) {
            $dataRow = Input::post('dataRow');
            foreach ($dataRow as $path => $row) {
                $paramFilters[$path] = $row;
            }
        }

        $param = array_merge($param, $paramFilters);

        $result = $this->ws->runResponse(GF_SERVICE_ADDRESS, $paramCode, $param);

        if (!$result) {
            $response = array(
                'status' => 'warning',
                'message'=> 'Амжилтгүй боллоо'
            );
        } else {
            $xml = new SimpleXMLElement("<?xml version=\"1.0\"?><sign></sign>");
            $node = $xml->addChild('processData');        
            // хэрэглэгч ямар ч тэмдэгт sign хийж болох учраас албаар security sanitize хийсэнгүй 
            $this->array_to_xml($result, $node);
            $xmlString = $xml->asXML();

            $hash = hash('sha256', $xmlString);
            $response = array(
                'status' => 'success', 
                'hash' => $hash, 
                'plainText' => $xmlString, 
                'guid' => '9981a19b-d380-4dad-a167-c223175e9321' // Энэ статик user - г солих хэрэгтэй логин хийсэн user - н guid
            );
        }
        
        echo json_encode($response); exit;
    }
    
    public function saveCipherText() {
        /*$plainText = Input::post('plainText');
        $cyphertext = Input::post('cyphertext');
        $certificateSerialNumber = Input::post('certificateSerialNumber');*/
        
        echo json_encode(array('status' => 'success'));
    }
    
    /**
     * дурын пост -оос hash гаргаж авна
     * param талбар заавал байна.
     */
    public function generateHashFromPost() {
        if (!isset($_POST['param'])) {
            return false;
        }
        
        $xml = new SimpleXMLElement("<?xml version=\"1.0\"?><sign></sign>");
        $node = $xml->addChild('processData');        
        // хэрэглэгч ямар ч тэмдэгт sign хийж болох учраас албаар security sanitize хийсэнгүй 
        $this->array_to_xml($_POST['param'], $node);
        $xmlString = $xml->asXML();
        
        $hash = hash('sha256', $xmlString);
        
        echo json_encode(array('status' => 'success', 'hash' => $hash, 'plainText' => $xmlString, 'guid' => '9981a19b-d380-4dad-a167-c223175e9321'));
        exit;
    }
    
    public function generateHashFromFile() {
        $filePath = Input::post('filePath');
        $handle = fopen($filePath, "r");
        $contents = fread($handle, filesize($filePath));
        ob_flush(); 
        fclose($handle);        
        $hash = hash('sha256', $contents);
        
        echo json_encode(array('status' => 'success', 'hash' => $hash, 'plainText' => $contents, 'guid' => '9981a19b-d380-4dad-a167-c223175e9321'));
        exit;
    }
    
    public function array_to_xml($array, &$xml) {
        foreach ($array as $key => $value) {
            if (is_array($value)) {
                if (!is_numeric($key)) {
                    $subnode = $xml->addChild("$key");
                    // хэрэглэгч юу ч sign хийж болох учраас албаар security sanitize хийсэнгүй 
                    $this->array_to_xml($value, $subnode);
                } else {
                    // хэрэглэгч юу ч sign хийж болох учраас албаар security sanitize хийсэнгүй 
                    $this->array_to_xml($value, $xml);
                }
            } else {
                $xml->addChild("$key","$value");
            }
        }
    }
    
    public function generateHashFromFileByDataView() {
        
        $selectedRow = Input::post('selectedRow');
        $signatureImage = '';

        if (issetParam($selectedRow['signatureimage']) !== '') {
            $isBase64 = preg_match('/^[a-zA-Z0-9\/\r\n+]*={0,2}$/', $selectedRow['signatureimage']);
            if (!$isBase64) {
                if (@is_array(getimagesize($selectedRow['signatureimage']))){
                    $data = file_get_contents($selectedRow['signatureimage']);
                    $signatureImage = base64_encode($data);
                }
            } else {
                $signatureImage = $selectedRow['signatureimage'];
            }
        }

        if (issetParam($selectedRow['plaintextforcipher']) !== '') {
            
            $contents = $selectedRow['plaintextforcipher'];
            
        } else {
            
            if (isset($selectedRow['physicalpath']) && file_exists($selectedRow['physicalpath'])) {

                $filePath = $selectedRow['physicalpath'];

                $handle = fopen($filePath, 'r');
                $contents = fread($handle, filesize($filePath));

                ob_flush(); 
                fclose($handle);

            } else {

                unset($selectedRow['wfmstatusid']);
                unset($selectedRow['wfmstatusname']);
                unset($selectedRow['wfmstatuscolor']);
                unset($selectedRow['wfmdescription']);

                $contents = implode('', $selectedRow);
            }
        }
        
        $hash = hash('sha256', $contents);
        
        $response = array(
            'status'    => 'success', 
            'hash'      => $hash, 
            'plainText' => $hash, 
            'signatureImage' => $signatureImage, 
            'guid'      => Session::get(SESSION_PREFIX.'monpassGUID')
        );
        
        echo json_encode($response, JSON_UNESCAPED_UNICODE);
    }
    
    public function getCertificateSerialNumberByGUID($guid) {
        $this->load->model('mdpki', 'middleware/models/');
        return $this->model->getCertificateSerialNumberByGUIDModel($guid);
    }

    public function rowVerifyDataView() {
        
        $refStructureId = Input::post('refStructureId');
        $selectedRow = Input::post('selectedRow');
        $recordId = isset($selectedRow['id']) ? $selectedRow['id'] : null;
        
        $this->load->model('mdworkflow', 'middleware/models/');
        
        $cipherText = $this->model->getLastCreatedCipherTextModel($refStructureId, $recordId);
        
        if ($cipherText) {
            
            if (isset($selectedRow['wfmstatusid'])) {
                unset($selectedRow['wfmstatusid']);
            }
            
            if (isset($selectedRow['wfmstatusname'])) {
                unset($selectedRow['wfmstatusname']);
            }
            
            if (isset($selectedRow['wfmstatuscolor'])) {
                unset($selectedRow['wfmstatuscolor']);
            }
            
            if (isset($selectedRow['wfmdescription'])) {
                unset($selectedRow['wfmdescription']);
            }

            $contents = implode('', $selectedRow);

            $hash = hash('sha256', $contents);
            $guid = Session::get(SESSION_PREFIX.'monpassGUID');
            
            $response = array(
                'status' => 'success', 
                'plainText' => $hash, 
                'cipherText' => $cipherText, 
                'guid' => $guid,  
                'certificateSerialNumber' => self::getCertificateSerialNumberByGUID($guid) 
            );
            
        } else {
            $response = array(
                'status' => 'error', 
                'message' => 'Error'
            );
        }
        
        echo json_encode($response);
    }
    
    /**
     * Тухай файл path - д зориулж unique file upload хийх URL үүсгэнэ
     */
    public function getInformationForDocumentSign() {
        $ecmContentId = Input::post('ecmContentId');
        $filePath = Input::post('filePath');
        $hash = $this->genereateHashForDocumentSign($filePath, $ecmContentId);
        $userCertificateArray = $this->model->getCertificateInformation();
        $filePathInfo = pathinfo($filePath);

        if($userCertificateArray) {
            
            $date = Date::currentDate('Ym');
            $newPath = UPLOADPATH . 'signed_file/' . $date . '/';
            if (!is_dir($newPath)) {
                mkdir($newPath, 0777, true);
            }
            
            $result = array (
                'certificateSerialNumber' => $userCertificateArray['CERTIFICATE_SERIAL_NUMBER'],
                'userId' => $userCertificateArray['MONPASS_USER_ID'],
                'serverAddress' => Config::getFromCache('MONPASS_SERVER'),
                'uploadUrl' => URL . self::$signFileUploadUrlAddress . '/' . $hash,
                'newFileName' => 'signed_v_' . getUID() . '.' . $filePathInfo['extension'],
                'filePath' => $newPath,
                'status' => 'success'
            );
        }else{
            $result = array('status' => 'error');
        }
        
        
        echo json_encode($result);
    }
    
    /**
     * Тухайн file path - д зориулж давтагдашгүй hash үүсгэж авч байна.
     * @param type $filePath
     * @return type
     */
    private function genereateHashForDocumentSign($filePath, $ecmContentId) {        
        $time = time();
        // generate hash
        $hash = sha1($time.$filePath);
        // save hash
        $this->model->saveHashForDocumentSign($hash, $ecmContentId);
        
        return $hash;
    }
    
    public function fileAuthentication() {
        $certificateSerialNumber = Input::post('certificateSerialNumber');
        $cyphertext = Input::post('cyphertext');
        $fileName = Input::post('fileName');
        $plainText = Input::post('plainText');
        $ecmContentId = Input::post('ecmContentId');

        $filePath = Input::post('filePath', UPLOADPATH . 'signedDocument/') . $fileName;
        
        if (file_exists($filePath)) {
            
            $this->model->checkIsSigned($ecmContentId, $filePath);
            
            $response = array('status' => 'success');
        } else {
            $response = array('status' => 'error');
        }

        echo json_encode($response); exit;
    }

    public function cloudSignPrepare() {

        $jsonString = json_encode($_POST['param']);        
        $hash = hash('sha256', $jsonString);
        $postData = array(
            'ciphertext' => $hash,
            'stateRegNumber' => $this->model->getRegisterNumber()
        );
        $clientId  = Config::getFromCache('mp_client_id');
        $clientSecret  = Config::getFromCache('mp_client_secret');
        $userPass = base64_encode($clientId . ':' . $clientSecret);

        $url = 'https://sso.monpass.mn/sign/signRequest?client_id='.$clientId;
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POST => true, 
            CURLOPT_POSTFIELDS => json_encode($postData),
            CURLOPT_HEADER => false, 
            CURLOPT_HTTPHEADER => array(
                'Authorization: Basic ' . $userPass,
                'Content-Type: application/json',
            )
        ));            
        $response = curl_exec($curl);       
        $err = curl_error($curl);
        curl_close($curl);

        if ($err) {
            $response = array('status' => 'error', 'message' => $err);
            jsonResponse($response);
        } else {
            jsonResponse(json_decode($response));
        }  
    }

    public function cloudSign() {
        $token = Input::post('monpass_token');

        $url = 'https://sso.monpass.mn/sign/getSignInfo';
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POST => true, 
            CURLOPT_POSTFIELDS => '',
            CURLOPT_HEADER => false, 
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json',
                'Authorization: Bearer ' . $token
            )
        ));    
        
        $response = curl_exec($curl);       
        $err = curl_error($curl);
        curl_close($curl);

        if ($err) {
            $response = array('status' => 'error', 'message' => $err);
            jsonResponse($response);
        } else {
            jsonResponse(json_decode($response));
        }  
    }

    public function setDocumentSign () {
        
        $postData = Input::postData();
        $selectedRow = Input::post('selectedRow');
        
        try {
            
            $signatureImage = '';
            
            if (issetParam($postData['signatureimage']) !== '') {
                $isBase64 = preg_match('/^[a-zA-Z0-9\/\r\n+]*={0,2}$/', $postData['signatureimage']);
                if (!$isBase64) {
                    $imgfilePath = $postData['signatureimage'];
                } else {
                    $signatureImage = $postData['signatureimage'];
                    $imgfilePath = Mdwebservice::bpUploadGetPath('');
                    $imgfilePath .= getUID() . '.png';
                    @file_put_contents($imgfilePath, base64_decode($signatureImage));
                }
            }
            if ($imgfilePath === '') {
                $imgfilePath = Config::getFromCache('stamp_filepath');
            }
            
            if (!file_exists($imgfilePath)) {
                throw new Exception(Lang::line('STAMP_IMG_NOT_FOUND'));
            } 
            
            if (!file_exists($postData['filePath'])) {
                throw new Exception(Lang::line('STAMP_FILE_NOT_FOUND'));
            }

            $imgsize_arr = getimagesize($imgfilePath);
            if (150 < $imgsize_arr[0]) {
                $image = imagecreatefromjpeg($imgfilePath);
                $newFilePath = Mdwebservice::bpUploadGetPath('');
                $newFilePath .= getUID() . '.png';
    
                // Scale the image
                $new_width = 150; // New width for the image
                $new_height = 70; // New height for the image
                $resized_image = imagescale($image, $new_width, $new_height);
    
                // Save the resized image
                imagejpeg($resized_image, $newFilePath);
                imagedestroy($image);
                imagedestroy($resized_image);
                $imgfilePath = $newFilePath;
            }
            
            if (Input::post('signaturetext')) {
                $tmp = $imgfilePath;
                $imgfilePath = Mdwebservice::bpUploadGetPath('');
                $imgfilePath .= getUID() . '.png';
                self::imageAddText($tmp, $imgfilePath, Input::post('signaturetext'));
            }


            $pdfFilePath = $postData['filePath'];
            if (strpos($pdfFilePath, UPLOADPATH) === 0) { 
                $pdfFilePath = $_SERVER['DOCUMENT_ROOT'] . '/' . $postData['filePath'];
            }
            if (Input::post('pageNum') !== 'all') {

                $param = array(
                    'pdfFilePath' => $pdfFilePath,
                    'stampImagePath' =>   $_SERVER['DOCUMENT_ROOT'] . '/' . $imgfilePath,
                    'pageNumber' => Input::post('pageNum'),
                    'locationX' => Input::post('x'),
                    'locationY' => Input::post('y'),
                );
                $result = $this->ws->runResponse(GF_SERVICE_ADDRESS, 'PDF_WATERMARK', $param);

            }else {
                $param = array(
                    'pdfFilePath' => $pdfFilePath,
                    'stampImageB64' =>   base64_encode(file_get_contents($_SERVER['DOCUMENT_ROOT'] . '/' . $imgfilePath)),
                    'position' => Input::post('signatureposition', 'b_right'),
                    'locationX' => Input::post('x'),
                    'locationY' => Input::post('y'),
                );
                $result = $this->ws->runResponse(GF_SERVICE_ADDRESS, 'NTR_CONSUL_STAMP_BP', $param);
            }

            if (issetParam($result['result']['stampedpdfb64'])) {
                $pdf_b64 = base64_decode($result['result']['stampedpdfb64']);

                if (issetParam($postData['contentid']) !== '') { 
                    
                    $filePath = Mdwebservice::bpUploadGetPath('');
                    $filePath .= getUID() . '.pdf';
                    $fileWrite = @file_put_contents($filePath, $pdf_b64);
                    if ($fileWrite) {
                        $this->model->checkIsSigned($postData['contentid'], $filePath);
                    } else {
                        throw new Exception(Lang::line('CANT_WRITE_STAMPED_FILE'));
                    }
                } else {
                    file_put_contents($postData['filePath'], $pdf_b64);
                }

                $response = array(
                    'filePath'    => $filePath, 
                    'status'    => 'success', 
                    'message' => Lang::line('msg_stamped_success')
                );
            } elseif (issetParam($result['result']['stampedpdfpath'])) {
                if (issetParam($postData['contentid']) === '') { 
                    throw new Exception(Lang::line('NOT_FOUND_CONTENT_ID')); 
                } 
                else {
                    $filePath = str_replace($_SERVER['DOCUMENT_ROOT'] . '/', '', $result['result']['stampedpdfpath']);
                    $this->model->checkIsSigned($postData['contentid'], $filePath);
                    $response = array(
                        'filePath'    => $filePath, 
                        'status'    => 'success', 
                        'message' => Lang::line('msg_stamped_success')
                    );
                }
            } else {
                throw new Exception(Lang::line('CANT_STAMPED_FILE'));
            }

        }  catch (Exception $ex) {

            (Array) $result = array();

            $response['status'] = 'error';
            $response['message'] = $ex->getMessage();

        }  catch (Exception $ex) {

            (Array) $result = array();

            $response['status'] = 'error';
            $response['message'] = $ex->getMessage();

        }

        convJson($response);
        exit;

    }
    
    public function imageAddText ($src = 'storage/file_1699845962622998_16227981990065052.png', $dst = 'storage/1719197919423054.png', $text = "2024-06-21 15:00") {
        try {
            $info = getimagesize($src);
            $extension = image_type_to_extension($info[2]);
            
            if ($extension === '.png') {
                $jpg_image = imagecreatefrompng($src);
            } else {
                $jpg_image = imagecreatefromjpeg($src);
            }

            $orig_width = imagesx($jpg_image);
            $orig_height = imagesy($jpg_image);

            // Allocate A Color For The background
            $bcolor=imagecolorallocate($jpg_image, 255, 255, 255);

            //Create background
            imagefilledrectangle($jpg_image,  0, $orig_height+100, $orig_width, $orig_height, $bcolor);
            $orig_width = imagesx($jpg_image);
            $orig_height = imagesy($jpg_image);
            
            // Create a canvas containing both the image and text
            $canvas = imagecreatetruecolor($orig_width, $orig_height + 20); // Extra height for text
            $bcolor = imagecolorallocate($canvas, 255, 255, 255); // Background color
            imagefilledrectangle($canvas, 0, 0, $orig_width, $orig_height + 20, $bcolor); // Add background color
            
            // Save image to the new canvas
            imagecopyresampled($canvas, $jpg_image, 0, 0, 0, 0, $orig_width, $orig_height, $orig_width, $orig_height);
            
            // Add text below the image

            $font_path = $_SERVER['DOCUMENT_ROOT'] . "/assets/custom/css/font/Arial.ttf"; // Change to your own font file
            $color = imagecolorallocate($canvas, 0, 0, 0); // Text color
            imagettftext($canvas, 7, 0, 0, $orig_height + 10, $color, $font_path, $text);
            
            // Output the final image
            /* header('Content-type: image/png'); */
            imagejpeg($canvas, $dst);

            
            // Clean up memory
            imagedestroy($jpg_image);
            imagedestroy($canvas);   
        }   catch (Exception $ex) {

        }
    }
}
