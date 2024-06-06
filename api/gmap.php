<?php 
define('_VALID_PHP', true);

require '../config/config.php';

date_default_timezone_set(CONFIG_TIMEZONE);

require '../helper/functions.php';
require '../libs/Security.php';
require '../libs/Str.php';
require '../libs/Input.php';

$method = Input::post('method');

if ($method == 'what3words') {
    
    $coordinate = Input::post('coordinate');
    if (strpos($coordinate, '|') !== false) {
        $seperatorChar = '|';
    } else {
        $seperatorChar = ',';
    }    
    $coordinateArr = explode($seperatorChar, $coordinate);
    $latitude = trim($coordinateArr[0]);
    $longitude = trim($coordinateArr[1]);
    
    if ((float) $latitude > (float) $longitude) {
        $latitudeTmp = $latitude;
        $latitude = $longitude;
        $longitude = $latitudeTmp;
    }
            
    $coords = $latitude.','.$longitude;    
    
    $curl = curl_init();
        
    curl_setopt_array($curl, array(
        CURLOPT_URL => "https://api.what3words.com/v2/reverse?coords=$coords&key=QEWJBT12&lang=en&format=json&display=full",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_CUSTOMREQUEST => 'GET',
        CURLOPT_SSL_VERIFYHOST => 0,
        CURLOPT_SSL_VERIFYPEER => 0
    ));

    $response = curl_exec($curl);
    $err = curl_error($curl);

    curl_close($curl);

    if ($err) {
        echo json_encode(array('status' => 'error', 'message' => 'Амжилтгүй боллоо. cURL Error #:' . $err));
    } else {
        $decode = json_decode($response, true);
        echo json_encode($decode['words']);
    }
    exit;
} elseif ($method == 'geocode') {
    
    $coordinate = Input::post('coordinate');
    if (strpos($coordinate, '|') !== false) {
        $seperatorChar = '|';
    } else {
        $seperatorChar = ',';
    }
    
    $coordinateArr = explode($seperatorChar, $coordinate);
    
    $latitude = trim($coordinateArr[0]);
    $longitude = trim($coordinateArr[1]);
    
    if ((float) $latitude > (float) $longitude) {
        $latitudeTmp = $latitude;
        $latitude = $longitude;
        $longitude = $latitudeTmp;
    }
            
    $coords = $latitude.','.$longitude;
    $apiKey = Input::post('googleApiKey');
    
    $curl = curl_init();
        
    curl_setopt_array($curl, array(
        CURLOPT_URL => "https://maps.googleapis.com/maps/api/geocode/json?latlng=$coords&sensor=true&language=mn&key=".$apiKey,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_CUSTOMREQUEST => 'GET',
        CURLOPT_SSL_VERIFYHOST => 0,
        CURLOPT_SSL_VERIFYPEER => 0
    ));

    $response = curl_exec($curl);
    $err = curl_error($curl);

    curl_close($curl);

    if ($err) {
        echo json_encode(array('status' => 'error', 'message' => 'Амжилтгүй боллоо. cURL Error #:' . $err), JSON_UNESCAPED_UNICODE);
    } else {
        echo $response;
    }
    exit;
    
} elseif ($method == 'opencagedata') {
    
    $coordinate = Input::post('coordinate');
    if (strpos($coordinate, '|') !== false) {
        $seperatorChar = '|';
    } else {
        $seperatorChar = ',';
    }
    
    $coordinateArr = explode($seperatorChar, $coordinate);
    
    $latitude = trim($coordinateArr[0]);
    $longitude = trim($coordinateArr[1]);
    
    if ((float) $latitude > (float) $longitude) {
        $latitudeTmp = $latitude;
        $latitude = $longitude;
        $longitude = $latitudeTmp;
    }
            
    $coords = $latitude.','.$longitude;
    $curl = curl_init();
        
    curl_setopt_array($curl, array(
        CURLOPT_USERAGENT => "Mozilla/5.0 (Windows; U; Windows NT 5.1; rv:1.7.3) Gecko/20041001 Firefox/0.10.1", 
        CURLOPT_URL => "https://api.opencagedata.com/geocode/v1/json?q=$coords&key=03c48dae07364cabb7f121d8c1519492&no_annotations=1&language=en",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_CUSTOMREQUEST => 'GET',
        CURLOPT_SSL_VERIFYHOST => 0,
        CURLOPT_SSL_VERIFYPEER => 0
    ));

    $response = curl_exec($curl);
    $err = curl_error($curl);
    $errNo = curl_errno($curl);

    curl_close($curl);

    if ($err) {
        echo json_encode(array('status' => 'error', 'message' => 'Амжилтгүй боллоо. cURL Error #:' . $err), JSON_UNESCAPED_UNICODE);
    } else {
        echo $response;
    }
    exit;
    
} elseif ($method == 'googleDMStoDD') {
    
    function convertDMSToDecimal($latlng) {
        $valid = false;
        $decimal_degrees = 0;
        $degrees = 0; $minutes = 0; $seconds = 0; $direction = 1;
        // Determine if there are extra periods in the input string
        $num_periods = substr_count($latlng, '.');
        if ($num_periods > 1) {
            $temp = preg_replace('/\./', ' ', $latlng, $num_periods - 1); // replace all but last period with delimiter
            $temp = trim(preg_replace('/[a-zA-Z]/','',$temp)); // when counting chunks we only want numbers
            $chunk_count = count(explode(" ",$temp));
            if ($chunk_count > 2) {
                $latlng = preg_replace('/\./', ' ', $latlng, $num_periods - 1); // remove last period
            } else {
                $latlng = str_replace("."," ",$latlng); // remove all periods, not enough chunks left by keeping last one
            }
        }

        // Remove unneeded characters
        $latlng = trim($latlng);
        $latlng = str_replace("º"," ",$latlng);
        $latlng = str_replace("°"," ",$latlng);
        $latlng = str_replace("'"," ",$latlng);
        $latlng = str_replace("\""," ",$latlng);
        $latlng = str_replace("  "," ",$latlng);
        $latlng = substr($latlng,0,1) . str_replace('-', ' ', substr($latlng,1)); // remove all but first dash
        if ($latlng != "") {
            // DMS with the direction at the start of the string
            if (preg_match("/^([nsewNSEW]?)\s*(\d{1,3})\s+(\d{1,3})\s+(\d+\.?\d*)$/",$latlng,$matches)) {
                $valid = true;
                $degrees = intval($matches[2]);
                $minutes = intval($matches[3]);
                $seconds = floatval($matches[4]);
                if (strtoupper($matches[1]) == "S" || strtoupper($matches[1]) == "W")
                    $direction = -1;
            }
            // DMS with the direction at the end of the string
            elseif (preg_match("/^(-?\d{1,3})\s+(\d{1,3})\s+(\d+(?:\.\d+)?)\s*([nsewNSEW]?)$/",$latlng,$matches)) {
                $valid = true;
                $degrees = intval($matches[1]);
                $minutes = intval($matches[2]);
                $seconds = floatval($matches[3]);
                if (strtoupper($matches[4]) == "S" || strtoupper($matches[4]) == "W" || $degrees < 0) {
                    $direction = -1;
                    $degrees = abs($degrees);
                }
            }
            if ($valid) {
                // A match was found, do the calculation
                $decimal_degrees = ($degrees + ($minutes / 60) + ($seconds / 3600)) * $direction;
            } else {
                // Decimal degrees with a direction at the start of the string
                if (preg_match("/^([nsewNSEW]?)\s*(\d+(?:\.\d+)?)$/",$latlng,$matches)) {
                    $valid = true;
                    if (strtoupper($matches[1]) == "S" || strtoupper($matches[1]) == "W")
                        $direction = -1;
                    $decimal_degrees = $matches[2] * $direction;
                }
                // Decimal degrees with a direction at the end of the string
                elseif (preg_match("/^(-?\d+(?:\.\d+)?)\s*([nsewNSEW]?)$/",$latlng,$matches)) {
                    $valid = true;
                    if (strtoupper($matches[2]) == "S" || strtoupper($matches[2]) == "W" || $degrees < 0) {
                        $direction = -1;
                        $degrees = abs($degrees);
                    }
                    $decimal_degrees = $matches[1] * $direction;
                }
            }
        }
        if ($valid) {
            return $decimal_degrees;
        } else {
            return false;
        }
    }

    $input = $_POST['coordinate'];
    $arr = explode('N', $input);
    
    $longitude = trim($arr[0]).'N';
    $latitude = trim($arr[1]);
    
    $response = convertDMSToDecimal($latitude).'|'.convertDMSToDecimal($longitude);
    
    echo json_encode($response); exit;
    
} elseif ($method == 'googleDDtoUTM') {
    
    require '../libs/Address/googlemap/phpcoord-2.3.php';
    
    $coordinate = Input::post('coordinate');
    $coordinateArr = explode('|', $coordinate);
    
    $ll = new LatLng($coordinateArr[1], $coordinateArr[0]);
    $utm = $ll->toUTMRef();
    
    echo json_encode($utm->toString()); exit;
    
} elseif ($method == 'googleDectoDMS') {
    
    $coordinate = Input::post('coordinate');
    $coordinateArr = explode('|', $coordinate);    
    $latitude = $coordinateArr[1];
    $longitude = $coordinateArr[0];
    
    $latitudeDirection = $latitude < 0 ? 'S': 'N';
    $longitudeDirection = $longitude < 0 ? 'W': 'E';

    $latitudeNotation = $latitude < 0 ? '-': '';
    $longitudeNotation = $longitude < 0 ? '-': '';

    $latitudeInDegrees = floor(abs($latitude));
    $longitudeInDegrees = floor(abs($longitude));

    $latitudeDecimal = abs($latitude)-$latitudeInDegrees;
    $longitudeDecimal = abs($longitude)-$longitudeInDegrees;

    $_precision = 3;
    $latitudeMinutes = round($latitudeDecimal*60,$_precision);
    $longitudeMinutes = round($longitudeDecimal*60,$_precision);

    echo json_encode(sprintf('%s%s° %s %s|%s%s° %s %s',
        $latitudeNotation,
        $latitudeInDegrees,
        $latitudeMinutes,
        $latitudeDirection,
        $longitudeNotation,
        $longitudeInDegrees,
        $longitudeMinutes,
        $longitudeDirection
    )); exit;
    
} elseif ($method == 'googleDistanceBetween') {
    
    $gmapApiKey = Input::post('gmapApiKey');
    $fromCoordinate = Input::post('fromCoordinate');
    $toCoordinate = Input::post('toCoordinate');
    $mode = Input::post('mode');
    $url = "https://maps.googleapis.com/maps/api/distancematrix/json?key=".$gmapApiKey."&origins=".$fromCoordinate."&destinations=".$toCoordinate."&mode=".$mode."&language=mn";
    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_PROXYPORT, 3128);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    $response = curl_exec($ch);
    curl_close($ch);
    
    $response_a = json_decode($response, true);
    
    $destination_addresses = $response_a['destination_addresses'][0];
    $origin_addresses = $response_a['origin_addresses'][0];
    $dist = $response_a['rows'][0]['elements'][0]['distance']['text'];
    $meter = $response_a['rows'][0]['elements'][0]['distance']['value'];
    $time = $response_a['rows'][0]['elements'][0]['duration']['text'];
    $second = $response_a['rows'][0]['elements'][0]['duration']['value'];
    
    echo json_encode(array(
        'meter' => $meter, 
        'second' => $second, 
        'distance' => $dist, 
        'time' => $time, 
        'destination_addresses' => $destination_addresses, 
        'origin_addresses' => $origin_addresses
    ), JSON_UNESCAPED_UNICODE); exit;
}