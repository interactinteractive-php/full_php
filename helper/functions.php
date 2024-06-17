<?php

if (!defined('_VALID_PHP')) exit('Direct access to this location is not allowed..');

function getChecked($row, $status) {
    if ($row == $status) {
        return 'checked="checked"';
    } else {
        return '';
    }
}

function isValidEmail($email) {
    return (boolean) preg_match("/^[_\-\.0-9a-zA-Z]+@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$/i", $email);
}

function isValidDate($date, $format = 'Y-m-d H:i:s') {
    $d = DateTime::createFromFormat($format, $date);
    return $d && $d->format($format) == $date;
}

function objectToArray($d) {

    if (is_object($d)) {
        // Gets the properties of the given object
        // with get_object_vars function
        $d = get_object_vars($d);
    }

    if (is_array($d)) {
        /*
         * Return array converted to object
         * Using __FUNCTION__ (Magic constant)
         * for recursive call
         */
        return array_map(__FUNCTION__, $d);
    } else {
        // Return array
        return $d;
    }
}

function isValidUrl($url) {
    if (@file_get_contents($url)) {
        return true;
    } else {
        return false;
    }
}

function array_find_val($array, $index, $value) {
    foreach ($array as $val) {
        if ($val[$index] == $value) {
            return true;
        }
    }
    return false;
}

function isActionChecked($arr, $access, $action) {

    if (isset($arr[$access])) {

        $pos = strpos($arr[$access], "," . $action . ",");

        if ($pos !== false) {
            return ' checked="checked"';
        } else {
            return null;
        }
    } else {
        return null;
    }
}

function isActive($status) {
    if ($status == 1) {
        return '<i class="icon-ok-sign"></i>';
    } else {
        return '<i class="icon-remove"></i>';
    }
}

function isActiveV3($status) {
    if ($status == 1) {
        return '<i class="fa fa-check-circle"></i>';
    } else {
        return '';
    }
}

function distActive($status) {
    if ($status == 0) {
        return '<i class="icon-ok-sign"></i>';
    } else {
        return '<i class="icon-remove"></i>';
    }
}

if (!function_exists('html_tag')) {
    function html_tag($tag, $attr = array(), $content = false, $show = true) {
        if ($show) {
            $html = '<' . $tag;
            $html .= (!empty($attr)) ? ' ' . (is_array($attr) ? array_to_attr($attr) : $attr) : '';
            $html .= '>';
            $html .= $content . '</' . $tag . '>';
            return $html;
        }
        return null;
    }
}

if (!function_exists('array_to_attr')) {

    function array_to_attr($attr) {
        $attr_str = '';

        foreach ($attr as $property => $value) {

            if ($value === null or $value === false) {
                continue;
            }

            if (is_numeric($property)) {
                $property = $value;
            }

            $attr_str .= $property . '="' . $value . '" ';
        }

        return trim($attr_str);
    }

}

function getUID() {
    return time() . str_shuffle(str_shuffle(substr((time() * rand()), 0, 6)));
}

function getUIDAdd($k) {
    return ((time() + $k) . str_shuffle(str_shuffle(substr((time() * rand()), 0, 6)))) + ($k * rand());
}

function getUIDStr($k) {
    return (time() . str_shuffle(str_shuffle(substr((time() * rand()), 0, 4)))) . $k;
}

function getUniqId() {
    $microtime = microtime(true);
    $microtime = substr(str_replace(".", "", $microtime), 0, 13);
    $rand = rand(1, 9);
    $key = $microtime . $rand;
    $key = (float) $key;
    return $key;
}

if (!function_exists('fileDownload')) {

    function fileDownload($filename = '', $data = '') {
        if ($filename == '' OR $data == '') {
            return false;
        }

        if (false === strpos($filename, '.')) {
            return false;
        }

        // Grab the file extension
        $x = explode('.', $filename);
        $extension = end($x);

        // Load the mime types
        if (is_file(BASEPATH . 'helper/mimes.php')) {
            include(BASEPATH . 'helper/mimes.php');
        }

        if (!isset($mimes[$extension])) {
            $mime = 'application/octet-stream';
        } else {
            $mime = (is_array($mimes[$extension])) ? $mimes[$extension][0] : $mimes[$extension];
        }
        
        includeLib('Detect/Browser');
        $browser = new Browser();
    
        if ($browser->isBrowser('Internet Explorer')) {
            
            header('Content-Type: "' . $mime . '"');
            header('Content-Disposition: attachment; filename="' . downloadFileName($filename) . '"');
            header('Expires: 0');
            header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
            header("Content-Transfer-Encoding: binary");
            header('Pragma: public');
            header("Content-Length: " . filesize($data));
        } else {
            header('Content-Description: File Transfer');
            header('Content-Type: "' . $mime . '"');
            header('Content-Disposition: attachment; filename="' . $filename . '"');
            header("Content-Transfer-Encoding: binary");
            header('Expires: 0');
            header('Pragma: public');
            header("Content-Length: " . filesize($data));
        }

        ob_clean();
        flush();
        readfile($data);
        exit;
    }

    function fileOutput($filename = '', $data = '') {
        if ($filename == '' OR $data == '') {
            return false;
        }

        if (false === strpos($filename, '.')) {
            return false;
        }

        // Grab the file extension
        $x = explode('.', $filename);
        $extension = end($x);

        // Load the mime types
        if (is_file(BASEPATH . 'helper/mimes.php')) {
            include(BASEPATH . 'helper/mimes.php');
        }

        if (!isset($mimes[$extension])) {
            $mime = 'application/octet-stream';
        } else {
            $mime = (is_array($mimes[$extension])) ? $mimes[$extension][0] : $mimes[$extension];
        }

        includeLib('Detect/Browser');
        $browser = new Browser();
    
        if ($browser->isBrowser('Internet Explorer')) {
            header('Content-Type: "' . $mime . '"');
            header('Content-Disposition: attachment; filename="' . downloadFileName($filename) . '"');
            header('Expires: 0');
            header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
            header("Content-Transfer-Encoding: binary");
            header('Pragma: public');
            header("Content-Length: " . strlen($data));
        } else {
            header('Content-Type: "' . $mime . '"');
            header('Content-Disposition: attachment; filename="' . $filename . '"');
            header("Content-Transfer-Encoding: binary");
            header('Expires: 0');
            header('Pragma: no-cache');
            header("Content-Length: " . strlen($data));
        }

        exit($data);
    }
    
    function fileRead($filename = '', $data = '') {
        if ($filename == '' OR $data == '') {
            return false;
        }

        if (false === strpos($filename, '.')) {
            return false;
        }

        // Grab the file extension
        $x = explode('.', $filename);
        $extension = end($x);

        // Load the mime types
        if (is_file(BASEPATH . 'helper/mimes.php')) {
            include(BASEPATH . 'helper/mimes.php');
        }

        if (!isset($mimes[$extension])) {
            $mime = 'application/octet-stream';
        } else {
            $mime = is_array($mimes[$extension]) ? $mimes[$extension][0] : $mimes[$extension];
        }

        includeLib('Detect/Browser');
        $browser = new Browser();
    
        if ($browser->isBrowser('Internet Explorer')) {
            header('Content-Type: '.$mime);
            header('Content-Disposition: inline; filename="' . downloadFileName($filename) . '"');
            header('Expires: 0');
            header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
            header("Content-Transfer-Encoding: binary");
            header('Pragma: public');
            header("Content-Length: " . filesize($data));
        } else {
            header('Content-Type: '.$mime);
            header('Content-Disposition: inline; filename="' . $filename . '"');
            header("Content-Transfer-Encoding: binary");
            header('Expires: 0');
            header('Pragma: no-cache');
            header("Content-Length: " . filesize($data));
        }

        readfile($data);
        exit;
    }

}

function base64ToDownloadFile($fileName, $fileExtension, $base64Str) {
    
    header('Pragma: no-cache');
    header('Expires: 0');
    header('Set-Cookie: fileDownload=true; path=/');
    header('Content-Description: File Transfer');
    header('Content-Disposition: attachment; filename="'.$fileName.'.'.$fileExtension.'"');
    header('Content-Type: application/force-download');
    header('Content-Type: application/octet-stream');
    header('Content-Type: application/download');
    header('Content-Transfer-Encoding: binary');
    
    if (ob_get_contents() || ob_get_length()) {
        ob_end_clean(); 
    }
    echo base64_decode($base64Str); exit;
}

function getMimetypeByExtension($extension) {
    
    $mime = 'application/octet-stream';
    
    if (is_file(BASEPATH . 'helper/mimes.php')) {
        require BASEPATH . 'helper/mimes.php';
    } else {
        return $mime;
    }

    if (isset($mimes[$extension])) {
        $mime = (is_array($mimes[$extension])) ? $mimes[$extension][0] : $mimes[$extension];
    } 

    return $mime;
}

function get_client_ip() {
    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {   //check ip from share internet
        $ip = $_SERVER['HTTP_CLIENT_IP'];
    } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {   //to check ip is pass from proxy
        $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
    } else {
        $ip = $_SERVER['REMOTE_ADDR'];
    }
    
    if ($ip == '::1') {
        $ip = getHostByName(getHostName());
    }
    
    return $ip;
}

function getIpAddress() {
    // check for shared internet/ISP IP
    if (!empty($_SERVER['HTTP_CLIENT_IP']) && validate_ip($_SERVER['HTTP_CLIENT_IP'])) {
        return $_SERVER['HTTP_CLIENT_IP'];
    }

    // check for IPs passing through proxies
    if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        // check if multiple ips exist in var
        if (strpos($_SERVER['HTTP_X_FORWARDED_FOR'], ',') !== false) {
            $iplist = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
            foreach ($iplist as $ip) {
                if (validate_ip($ip))
                    return $ip;
            }
        } else {
            if (validate_ip($_SERVER['HTTP_X_FORWARDED_FOR']))
                return $_SERVER['HTTP_X_FORWARDED_FOR'];
        }
    }
    if (!empty($_SERVER['HTTP_X_FORWARDED']) && validate_ip($_SERVER['HTTP_X_FORWARDED']))
        return $_SERVER['HTTP_X_FORWARDED'];
    if (!empty($_SERVER['HTTP_X_CLUSTER_CLIENT_IP']) && validate_ip($_SERVER['HTTP_X_CLUSTER_CLIENT_IP']))
        return $_SERVER['HTTP_X_CLUSTER_CLIENT_IP'];
    if (!empty($_SERVER['HTTP_FORWARDED_FOR']) && validate_ip($_SERVER['HTTP_FORWARDED_FOR']))
        return $_SERVER['HTTP_FORWARDED_FOR'];
    if (!empty($_SERVER['HTTP_FORWARDED']) && validate_ip($_SERVER['HTTP_FORWARDED']))
        return $_SERVER['HTTP_FORWARDED'];

    // return unreliable ip since all else failed
    return $_SERVER['REMOTE_ADDR'];
}

/**
 * Ensures an ip address is both a valid IP and does not fall within
 * a private network range.
 */
function validate_ip($ip) {
    if (strtolower($ip) === 'unknown')
        return false;

    // generate ipv4 network address
    $ip = ip2long($ip);

    // if the ip is set and not equivalent to 255.255.255.255
    if ($ip !== false && $ip !== -1) {
        // make sure to get unsigned long representation of ip
        // due to discrepancies between 32 and 64 bit OSes and
        // signed numbers (ints default to signed in PHP)
        $ip = sprintf('%u', $ip);
        // do private network range checking
        if ($ip >= 0 && $ip <= 50331647) return false;
        if ($ip >= 167772160 && $ip <= 184549375) return false;
        if ($ip >= 2130706432 && $ip <= 2147483647) return false;
        if ($ip >= 2851995648 && $ip <= 2852061183) return false;
        if ($ip >= 2886729728 && $ip <= 2887778303) return false;
        if ($ip >= 3221225984 && $ip <= 3221226239) return false;
        if ($ip >= 3232235520 && $ip <= 3232301055) return false;
        if ($ip >= 4294967040) return false;
    }
    return true;
}

function generate_xml_element($dom, $data) {
    if (empty($data['name'])) return false;

    // Create the element
    $element_value = (!empty($data['value']) ) ? $data['value'] : null;
    $element = $dom->createElement($data['name'], $element_value);

    // Add any attributes
    if (!empty($data['attributes']) && is_array($data['attributes'])) {
        foreach ($data['attributes'] as $attribute_key => $attribute_value) {
            $element->setAttribute($attribute_key, $attribute_value);
        }
    }

    foreach ($data as $data_key => $child_data) {
        if (!is_numeric($data_key)) continue;

        $child = generate_xml_element($dom, $child_data);
        if ($child) $element->appendChild($child);
    }

    return $element;
}

function cleanOut($text) {
    $text = strtr($text, array('\r\n' => "", '\r' => "", '\n' => ""));
    $text = html_entity_decode($text, ENT_QUOTES, 'UTF-8');
    $text = str_replace('<br>', '<br />', $text);
    return stripslashes($text);
}

/**
 * @example param1: dir/oldname.*, param2: dir/newname.*
 */
function fileCopy($oldfile, $newfile) {
    return copy($oldfile, $newfile);
}

function downloadFileName($fileName_string) {
    includeLib('Detect/Browser');

    $fileName = $fileName_string;
    $browser = new Browser();

    if ($browser->isBrowser('Internet Explorer')) {
        $fileName = rawurlencode($fileName);
    }
    return $fileName;
}

function print_array($arr) {
    echo "<pre>";
    print_r($arr);
    echo "</pre>";
}

function loadConfig($name) {
    require BASEPATH . "config/$name.php";
}

function loadHelper($name) {
    require BASEPATH . "helper/$name.php";
}

function includeLib($name) {
    require_once BASEPATH . LIBS . $name . '.php';
}

function is_ajax_request() {
    if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
        return true;
    }
    return false;
}

if (!function_exists('set_status_header')) {

    function set_status_header($code = 200, $text = '') {
        $stati = array(
            200 => 'OK',
            201 => 'Created',
            202 => 'Accepted',
            203 => 'Non-Authoritative Information',
            204 => 'No Content',
            205 => 'Reset Content',
            206 => 'Partial Content',
            300 => 'Multiple Choices',
            301 => 'Moved Permanently',
            302 => 'Found',
            304 => 'Not Modified',
            305 => 'Use Proxy',
            307 => 'Temporary Redirect',
            400 => 'Bad Request',
            401 => 'Unauthorized',
            403 => 'Forbidden',
            404 => 'Not Found',
            405 => 'Method Not Allowed',
            406 => 'Not Acceptable',
            407 => 'Proxy Authentication Required',
            408 => 'Request Timeout',
            409 => 'Conflict',
            410 => 'Gone',
            411 => 'Length Required',
            412 => 'Precondition Failed',
            413 => 'Request Entity Too Large',
            414 => 'Request-URI Too Long',
            415 => 'Unsupported Media Type',
            416 => 'Requested Range Not Satisfiable',
            417 => 'Expectation Failed',
            500 => 'Internal Server Error',
            501 => 'Not Implemented',
            502 => 'Bad Gateway',
            503 => 'Service Unavailable',
            504 => 'Gateway Timeout',
            505 => 'HTTP Version Not Supported'
        );

        if ($code == '' || ! is_numeric($code)) {
            die('Status codes must be numeric 500');
        }

        if (isset($stati[$code]) && $text == '') {
            $text = $stati[$code];
        }

        if ($text == '') {
            die('No status text available. Please check your status code number or supply your own message text. 500');
        }

        $server_protocol = (isset($_SERVER['SERVER_PROTOCOL'])) ? $_SERVER['SERVER_PROTOCOL'] : false;

        if (substr(php_sapi_name(), 0, 3) == 'cgi') {
            header("Status: {$code} {$text}", true);
        } elseif ($server_protocol == 'HTTP/1.1' || $server_protocol == 'HTTP/1.0') {
            header($server_protocol . " {$code} {$text}", true, $code);
        } else {
            header("HTTP/1.1 {$code} {$text}", true, $code);
        }
    }

}

function numToAlpha($num, $uppercase = true) {
    $letters = '';
    while ($num > 0) {
        $code = ($num % 26 == 0) ? 26 : $num % 26;
        $letters .= chr($code + 64);
        $num = ($num - $code) / 26;
    }
    return ($uppercase) ? strtoupper(strrev($letters)) : strrev($letters);
}

function alphaToNum($alphas) {
    $num = 0;
    $arr = array_reverse(str_split($alphas));

    for ($i = 0; $i < count($arr); $i++) {
        $num += (ord(strtolower($arr[$i])) - 96) * (pow(26, $i));
    }
    return $num;
}

function htmlToExcel($html, $filename) {
    header("Pragma: no-cache");
    header("Expires: 0");
    header("Pragma: public");
    header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
    header("Content-Type: application/force-download");
    header("Content-Type: application/octet-stream");
    header("Content-Type: application/download");
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet;charset:UTF-8');
    header("Content-type: application/vnd.ms-excel;charset:UTF-8");
    header('Content-Disposition: attachment; filename="' . downloadFileName($filename) . '.xls"');
    // header("Content-Transfer-Encoding: binary");
    echo $html;

//        xlsBOF();
//        xlsWriteLabel(1,1,$html);
//        xlsEOF();
}

if (!function_exists('htmltotext')) {

    function htmltotext($comment) {

        $search = array('@<script[^>]*?>.*?</script>@si',
            '@<[\/\!]*?[^<>]*?>@si',
            '@([\r\n])[\s]+@',
            '@&(quot|#34);@i',
            '@&(amp|#38);@i',
            '@&(lt|#60);@i',
            '@&(gt|#62);@i',
            '@&(nbsp|#160);@i',
            '@&(iexcl|#161);@i',
            '@&(cent|#162);@i',
            '@&(pound|#163);@i',
            '@&(copy|#169);@i',
            '@&#(\d+);@e');

        $replace = array('',
            '',
            '\1',
            '"',
            '&',
            '<',
            '>',
            ' ',
            chr(161) . ' ',
            chr(162) . ' ',
            chr(163) . ' ',
            chr(169) . ' ',
            'chr(\1) ');

        $comment = preg_replace($search, $replace, $comment);
        $comment = preg_replace("[ \t\n\r]", " ", $comment);
        $comment = trim($comment);
        $comment = (strlen($comment) > 795) ? substr($comment, 0, 795) . '...' : $comment;
        return $comment;
    }

}

function xlsBOF() {
    echo pack("ssssss", 0x809, 0x8, 0x0, 0x10, 0x0, 0x0);
    return;
}

//End of file...
function xlsEOF() {
    echo pack("ss", 0x0A, 0x00);
    return;
}

//Creates a heading...
function xlsWriteLabel($Row, $Col, $Value) {
    $L = strlen($Value);
    echo pack("ssssss", 0x204, 8 + $L, $Row, $Col, 0x0, $L);
    echo $Value;
    return;
}

function loadPhpFastCache() {
    require_once BASEPATH . LIBS . "Cache/phpfastcache/phpfastcache.php";
}

function loadPhpQuery() {
    require_once BASEPATH . LIBS . "DOM/phpquery/phpQuery/phpQuery.php";
}

function loadBarCode() {
    require_once BASEPATH . LIBS . "Barcode/lib1/barcode.class.php";
    /*
     * $bar = new BARCODE();
     * $link = $bar->BarCode_link(self::$barcodeType, $value, 50, 1.5);
     * $v = '<img src="'.URL.'libs/Barcode/'.$link.'" border="0"/>';
     */
}

function loadBarCodeImageData() {
    require_once BASEPATH . LIBS . "Barcode/lib2/BarcodeGenerator.php";
    require_once BASEPATH . LIBS . "Barcode/lib2/BarcodeGeneratorPNG.php";
    require_once BASEPATH . LIBS . "Barcode/lib2/BarcodeGeneratorSVG.php";
    /*
     * $generator = new Picqer\Barcode\BarcodeGeneratorPNG();
     * echo '<img src="data:image/png;base64,' . base64_encode($generator->getBarcode('5900649061928', $generator::TYPE_INTERLEAVED_2_5_CHECKSUM, 2, 130)) . '">';
     */
}

function numberToWords($number) {
    $conjunction = 'н ';
    $separator = ', ';
    $negative = 'сөрөг ';
    $dictionary = array(
        0 => 'тэг',
        1 => 'нэг',
        2 => 'хоёр',
        3 => 'гурав',
        4 => 'дөрөв',
        5 => 'тав',
        6 => 'зургаа',
        7 => 'долоо',
        8 => 'найм',
        9 => 'ес',
        10 => 'арав',
        11 => 'арван нэг',
        12 => 'арван хоёр',
        13 => 'арван гурав',
        14 => 'арван дөрөв',
        15 => 'арван тав',
        16 => 'арван зургаа',
        17 => 'арван долоо',
        18 => 'арван найм',
        19 => 'арван ес',
        20 => 'хорь',
        30 => 'гуч',
        40 => 'дөч',
        50 => 'тавь',
        60 => 'жар',
        70 => 'дал',
        80 => 'ная',
        90 => 'ер',
        100 => 'зуу',
        1000 => 'мянга',
        1000000 => 'сая',
        1000000000 => 'тэрбум',
        1000000000000 => 'их наяд',
        1000000000000000 => 'quadrillion',
        1000000000000000000 => 'quintillion'
    );

    $sep_dictionary = array(
        0 => 'тэг',
        1 => 'нэгэн',
        2 => 'хоёр',
        3 => 'гурван',
        4 => 'дөрвөн',
        5 => 'таван',
        6 => 'зургаан',
        7 => 'долоон',
        8 => 'найман',
        9 => 'есөн'
    );
    $hyphen_dictionary = array(
        10 => 'арван',
        20 => 'хорин',
        30 => 'гучин',
        40 => 'дөчин',
        50 => 'тавин',
        60 => 'жаран',
        70 => 'далан',
        80 => 'наян',
        90 => 'ерэн'
    );
    $decimal_places = array(
        1 => 'аравны',
        2 => 'зууны',
        3 => 'мянганы'
    );

    $number = ltrim(rtrim(Str::clearCommas($number), '.'), '0');

    if (!is_numeric($number)) {
        return false;
    }

    if (($number >= 0 && (int) $number < 0) || (int) $number < 0 - PHP_INT_MAX) {
        trigger_error(
            'numberToWords only accepts numbers between -' . PHP_INT_MAX . ' and ' . PHP_INT_MAX, E_USER_WARNING
        );
        return false;
    }

    if ($number < 0) {
        return $negative . numberToWords(abs($number));
    }

    $string = $fraction = null;

    if (strpos($number, '.') !== false) {
        $number = number_format((float) $number, 2, '.', '');
        list($number, $fraction) = explode('.', $number);
        $fraction = rtrim($fraction, '0');
    }

    switch (true) {
        case $number < 21:
            $string = $dictionary[$number];
            break;
        case $number < 100:
            $tens = ((int) ($number / 10)) * 10;
            $units = $number % 10;
            if ($units) {
                $string .= $hyphen_dictionary[$tens] . ' ' . $dictionary[$units];
            } else {
                $string .= $dictionary[$tens];
            }
            break;
        case $number < 1000:
            $hundreds = $number / 100;
            $remainder = $number % 100;
            $string = $sep_dictionary[$hundreds] . ' ' . $dictionary[100];
            if ($remainder) {
                $string .= $conjunction . numberToWords($remainder);
            }
            break;
        default:
            $baseUnit = pow(1000, floor(log($number, 1000)));
            $numBaseUnits = floor($number / $baseUnit);
            $remainder = $number - ($numBaseUnits * $baseUnit);
            $string = numberToWords($numBaseUnits) . ' ' . $dictionary[$baseUnit];
            if ($remainder) {
                $string .= $remainder < 100 ? ' ' : $separator;
                $string .= numberToWords($remainder);
            }
            break;
    }

    if (null !== $fraction && is_numeric($fraction)) {
        $string .= ' ' . $decimal_places[strlen($fraction)] . ' ';
        $string .= numberToWords($fraction);
    }

    foreach (range(1, 9) as $index) {
        if ($index == 2 || strpos($string, 'нэг') === 0) continue;

        $string = strtr($string,
            array(
                $dictionary[$index] . ' тэрбум' => $sep_dictionary[$index] . ' тэрбум',
                $dictionary[$index] . ' сая' => $sep_dictionary[$index] . ' сая',
                $dictionary[$index] . ' мянга' => $sep_dictionary[$index] . ' мянга'
            )
        );
    }
    foreach (range(1, 9) as $indext) {
        $string = strtr($string,
            array(
                $dictionary[$indext . '0'] . ' тэрбум' => $hyphen_dictionary[$indext . '0'] . ' тэрбум',
                $dictionary[$indext . '0'] . ' сая' => $hyphen_dictionary[$indext . '0'] . ' сая',
                $dictionary[$indext . '0'] . ' мянга' => $hyphen_dictionary[$indext . '0'] . ' мянга'
            )
        );
    }

    return $string;
}

function amountToWords($number, $currencyCode = 'mnt', $langCode = 'mn') {
    
    $words = '';
    
    if ($number != '') {
        
        if ($langCode == 'en') {
        
            $words = amountToWordsIntl($number, $langCode, $currencyCode);

        } else {

            $words = Str::firstUpper(helperAmountToWords($number, $currencyCode));
            $words = strtr($words, array(
                    'нэг мянга,' => 'нэгэн мянга,',
                    'нэг сая,' => 'нэгэн сая,',
                )
            );
        }
    }

    return $words;
}

function helperAmountToWords($number, $currencyCode) {

    $currencyCode = strtolower(trim($currencyCode));

    if ($currencyCode == 'usd') {
        $currencyName = 'доллар';
        $currencyPetty = 'цент';
    } elseif ($currencyCode == 'eur') {
        $currencyName = 'евро';
        $currencyPetty = 'цент';
    } elseif ($currencyCode == 'jpy') {
        $currencyName = 'иень';
        $currencyPetty = 'сен';
    } elseif ($currencyCode == 'cny') {
        $currencyName = 'юань';
        $currencyPetty = 'фен';
    } elseif ($currencyCode == 'gbp') {
        $currencyName = 'фунт';
        $currencyPetty = 'пенни';
    } elseif ($currencyCode == 'rub') {
        $currencyName = 'рубль';
        $currencyPetty = 'копек';
    } elseif ($currencyCode == 'krw') {
        $currencyName = 'вон';
        $currencyPetty = 'жеон';
    } elseif ($currencyCode == 'empty') {
        $currencyName = '';
        $currencyPetty = '';
    } else {
        $currencyName = 'төгрөг';
        $currencyPetty = 'мөнгө';
    }

    $conjunction = 'н ';
    $separator = ', ';
    $negative = 'сөрөг ';
    $dictionary = array(
        0 => 'тэг',
        1 => 'нэг',
        2 => 'хоёр',
        3 => 'гурав',
        4 => 'дөрөв',
        5 => 'тав',
        6 => 'зургаа',
        7 => 'долоо',
        8 => 'найм',
        9 => 'ес',
        10 => 'арав',
        11 => 'арван нэг',
        12 => 'арван хоёр',
        13 => 'арван гурав',
        14 => 'арван дөрөв',
        15 => 'арван тав',
        16 => 'арван зургаа',
        17 => 'арван долоо',
        18 => 'арван найм',
        19 => 'арван ес',
        20 => 'хорь',
        30 => 'гуч',
        40 => 'дөч',
        50 => 'тавь',
        60 => 'жар',
        70 => 'дал',
        80 => 'ная',
        90 => 'ер',
        100 => 'зуу',
        1000 => 'мянга',
        1000000 => 'сая',
        1000000000 => 'тэрбум',
        1000000000000 => 'их наяд',
        1000000000000000 => 'quadrillion',
        1000000000000000000 => 'quintillion'
    );

    $sep_dictionary = array(
        0 => 'тэг',
        1 => 'нэгэн',
        2 => 'хоёр',
        3 => 'гурван',
        4 => 'дөрвөн',
        5 => 'таван',
        6 => 'зургаан',
        7 => 'долоон',
        8 => 'найман',
        9 => 'есөн'
    );
    $hyphen_dictionary = array(
        10 => 'арван',
        20 => 'хорин',
        30 => 'гучин',
        40 => 'дөчин',
        50 => 'тавин',
        60 => 'жаран',
        70 => 'далан',
        80 => 'наян',
        90 => 'ерэн'
    );
    $decimal_places = array(
        1 => 'аравны',
        2 => 'зууны',
        3 => 'мянганы'
    );

    $number = ltrim(rtrim(Str::clearCommas($number), '.'), '0');

    if (!is_numeric($number)) {
        return false;
    }

    if (($number >= 0 && (int) $number < 0) || (int) $number < 0 - PHP_INT_MAX) {
        trigger_error(
            'amountToWords only accepts numbers between -' . PHP_INT_MAX . ' and ' . PHP_INT_MAX, E_USER_WARNING
        );
        return false;
    }

    if ($number < 0) {
        return $negative . helperAmountToWords(abs($number), $currencyCode);
    }

    $string = $fraction = null;

    if (strpos($number, '.') !== false) {
        $number = number_format((float) $number, 2, '.', '');
        list($number, $fraction) = explode('.', $number);
    }

    switch (true) {
        case $number < 21:
            $string = $dictionary[$number];
            break;
        case $number < 100:
            $tens = ((int) ($number / 10)) * 10;
            $units = $number % 10;
            if ($units) {
                $string .= $hyphen_dictionary[$tens] . ' ' . $dictionary[$units];
            } else {
                $string .= $dictionary[$tens];
            }
            break;
        case $number < 1000:
            $hundreds = $number / 100;
            $remainder = $number % 100;
            $string = $sep_dictionary[$hundreds] . ' ' . $dictionary[100];
            if ($remainder) {
                $string .= $conjunction . numberToWords($remainder);
            }
            break;
        default:
            $baseUnit = pow(1000, floor(log($number, 1000)));
            $numBaseUnits = floor($number / $baseUnit);
            $remainder = $number - ($numBaseUnits * $baseUnit);
            $string = numberToWords($numBaseUnits) . ' ' . $dictionary[$baseUnit];
            if ($remainder) {
                $string .= $remainder < 100 ? ' ' : $separator;
                $string .= numberToWords($remainder);
            }
            break;
    }

    $string = $string . ' ' . $currencyName;
    
    if (null !== $fraction && is_numeric($fraction) && ($fraction != '0' || $fraction != '00')) {
        $string .= ' ' . $decimal_places[strlen($fraction)] . ' ';
        $string .= ' ' . numberToWords($fraction) . ' ' . $currencyPetty;    
    }
    
    $string = preg_replace('/\s+/', ' ', $string);

    foreach (range(1, 9) as $index) {
        if ($index == 2 || strpos($string, 'нэг') === 0) continue;

        $string = strtr($string,
            array(
                $dictionary[$index] . ' тэрбум' => $sep_dictionary[$index] . ' тэрбум',
                $dictionary[$index] . ' сая' => $sep_dictionary[$index] . ' сая',
                $dictionary[$index] . ' мянга' => $sep_dictionary[$index] . ' мянга',
            )
        );
    }
    foreach (range(1, 9) as $indext) {
        $string = strtr($string,
            array(
                $dictionary[$indext . '0'] . ' тэрбум' => $hyphen_dictionary[$indext . '0'] . ' тэрбум',
                $dictionary[$indext . '0'] . ' сая' => $hyphen_dictionary[$indext . '0'] . ' сая',
                $dictionary[$indext . '0'] . ' мянга' => $hyphen_dictionary[$indext . '0'] . ' мянга'
            )
        );
    }

    $string = strtr($string, array(
            'зуу тэрбум' => 'зуун тэрбум',
            'зуу сая' => 'зуун сая',
            'зуу мянга' => 'зуун мянга'
        )
    );
    
    if ($currencyCode != 'empty') {
        $string = strtr($string,
            array(
                'зуу ' . $currencyName => 'зуун ' . $currencyName,
                'мянга ' . $currencyName => 'мянган ' . $currencyName,
                'нэг ' . $currencyName => 'нэгэн ' . $currencyName,
                'гурав ' . $currencyName => 'гурван ' . $currencyName,
                'дөрөв ' . $currencyName => 'дөрвөн ' . $currencyName,
                'тав ' . $currencyName => 'таван ' . $currencyName,
                'зургаа ' . $currencyName => 'зургаан ' . $currencyName,
                'долоо ' . $currencyName => 'долоон ' . $currencyName,
                'найм ' . $currencyName => 'найман ' . $currencyName,
                'ес ' . $currencyName => 'есөн ' . $currencyName,
                'арав ' . $currencyName => 'арван ' . $currencyName,
                'хорь ' . $currencyName => 'хорин ' . $currencyName,
                'гуч ' . $currencyName => 'гучин ' . $currencyName,
                'дөч ' . $currencyName => 'дөчин ' . $currencyName,
                'тавь ' . $currencyName => 'тавин ' . $currencyName,
                'жар ' . $currencyName => 'жаран ' . $currencyName,
                'дал ' . $currencyName => 'далан ' . $currencyName,
                'ная ' . $currencyName => 'наяан ' . $currencyName,
                'ер ' . $currencyName => 'ерэн ' . $currencyName,
                'зуу ' . $currencyPetty => 'зуун ' . $currencyPetty,
                'мянга ' . $currencyPetty => 'мянган ' . $currencyPetty,
                'нэг ' . $currencyPetty => 'нэгэн ' . $currencyPetty,
                'гурав ' . $currencyPetty => 'гурван ' . $currencyPetty,
                'дөрөв ' . $currencyPetty => 'дөрвөн ' . $currencyPetty,
                'тав ' . $currencyPetty => 'таван ' . $currencyPetty,
                'зургаа ' . $currencyPetty => 'зургаан ' . $currencyPetty,
                'долоо ' . $currencyPetty => 'долоон ' . $currencyPetty,
                'найм ' . $currencyPetty => 'найман ' . $currencyPetty,
                'ес ' . $currencyPetty => 'есөн ' . $currencyPetty,
                'арав ' . $currencyPetty => 'арван ' . $currencyPetty,
                'хорь ' . $currencyPetty => 'хорин ' . $currencyPetty,
                'гуч ' . $currencyPetty => 'гучин ' . $currencyPetty,
                'дөч ' . $currencyPetty => 'дөчин ' . $currencyPetty,
                'тавь ' . $currencyPetty => 'тавин ' . $currencyPetty,
                'жар ' . $currencyPetty => 'жаран ' . $currencyPetty,
                'дал ' . $currencyPetty => 'далан ' . $currencyPetty,
                'ная ' . $currencyPetty => 'наяан ' . $currencyPetty,
                'ер ' . $currencyPetty => 'ерэн ' . $currencyPetty
            )
        );
    }

    $string = preg_replace('/^нэгэн тэрбум/', 'нэг тэрбум', $string, 1);
    $string = preg_replace('/^нэгэн сая/', 'нэг сая', $string, 1);
    $string = preg_replace('/^нэгэн мянг/', 'нэг мянг', $string, 1);
    $string = preg_replace('/^нэгэн зуу/', 'нэг зуу', $string, 1);

    $string = str_replace('нэгэн зуун', 'нэг зуун', $string);
    $string = str_replace('нэгэн мянган', 'нэг мянган', $string);
    $string = str_replace('нэгэн сая', 'нэг сая', $string);
    $string = str_replace('нэгэн тэрбум', 'нэг тэрбум', $string);

    $string = str_replace('хоёрон зуун', 'хоёр зуун', $string);
    $string = str_replace('хоёрон мянган', 'хоёр мянган', $string);
    $string = str_replace('хоёрон сая', 'хоёр сая', $string);
    $string = str_replace('хоёрон тэрбум', 'хоёр тэрбум', $string);

    $string = str_replace('гурав зуу', 'гурван зуу', $string);
    $string = str_replace('гурав мянга', 'гурван мянга', $string);
    $string = str_replace('гурав сая', 'гурван сая', $string);
    $string = str_replace('гурав тэрбум', 'гурван тэрбум', $string);

    $string = str_replace('дөрөв зуу', 'дөрвөн зуу', $string);
    $string = str_replace('дөрөв мянга', 'дөрвөн мянга', $string);
    $string = str_replace('дөрөв сая', 'дөрвөн сая', $string);
    $string = str_replace('дөрөв тэрбум', 'дөрвөн тэрбум', $string);

    $string = str_replace('тав зуу', 'таван зуу', $string);
    $string = str_replace('тав мянга', 'таван мянга', $string);
    $string = str_replace('тав сая', 'таван сая', $string);
    $string = str_replace('тав тэрбум', 'таван тэрбум', $string);

    $string = str_replace('зургаа зуу', 'зургаан зуу', $string);
    $string = str_replace('зургаа мянга', 'зургаан мянга', $string);
    $string = str_replace('зургаа сая', 'зургаан сая', $string);
    $string = str_replace('зургаа тэрбум', 'зургаан тэрбум', $string);

    $string = str_replace('долоо зуу', 'долоон зуу', $string);
    $string = str_replace('долоо мянга', 'долоон мянга', $string);
    $string = str_replace('долоо сая', 'долоон сая', $string);
    $string = str_replace('долоо тэрбум', 'долоон тэрбум', $string);

    $string = str_replace('найм зуу', 'найман зуу', $string);
    $string = str_replace('найм мянга', 'найман мянга', $string);
    $string = str_replace('найм сая', 'найман сая', $string);
    $string = str_replace('найм тэрбум', 'найман тэрбум', $string);

    $string = str_replace('ес зуу', 'есөн зуу', $string);
    $string = str_replace('ес мянга', 'есөн мянга', $string);
    $string = str_replace('ес сая', 'есөн сая', $string);
    $string = str_replace('ес тэрбум', 'есөн тэрбум', $string);
    $string = trim($string);

    return $string;
}

function amountToWordsIntl($num, $langCode = 'en', $currencyCode = 'usd') { 
    
    $ones = array(     
        0 => '',   
        1 => 'one', 
        2 => 'two', 
        3 => 'three', 
        4 => 'four', 
        5 => 'five', 
        6 => 'six', 
        7 => 'seven', 
        8 => 'eight', 
        9 => 'nine', 
        10 => 'ten', 
        11 => 'eleven', 
        12 => 'twelve', 
        13 => 'thirteen', 
        14 => 'fourteen', 
        15 => 'fifteen', 
        16 => 'sixteen', 
        17 => 'seventeen', 
        18 => 'eighteen', 
        19 => 'nineteen' 
    ); 
    
    $tens = array( 
        0 => '', 
        1 => 'ten',
        2 => 'twenty', 
        3 => 'thirty', 
        4 => 'forty', 
        5 => 'fifty', 
        6 => 'sixty', 
        7 => 'seventy', 
        8 => 'eighty', 
        9 => 'ninety' 
    ); 

    $hundreds = array( 
        'hundred', 
        'thousand', 
        'million', 
        'billion', 
        'trillion', 
        'quadrillion' 
    );  
    
    $langCode = strtolower($langCode);
    $currencyCode = strtolower($currencyCode);
    $num = ltrim(rtrim(str_replace(',', '', $num), '.'), '0');
    $num = number_format($num, 2, '.', ','); 

    $num_arr = explode('.', $num); 
    $wholenum = $num_arr[0]; 
    $decnum = $num_arr[1]; 
    $whole_arr = array_reverse(explode(',', $wholenum)); 
    krsort($whole_arr); 
    $rettxt = ''; 

    foreach ($whole_arr as $key => $i) { 
        
        $i = ltrim($i, '0');
        
        if ($i == '') {
            continue;
        }
        
        if ($i < 20) { 
            
            if (isset($ones[$i])) {
                $rettxt .= $ones[$i]; 
            }
            
        } elseif ($i < 100) { 
            
            $rettxt .= $tens[substr($i, 0, 1)]; 
            @$rettxt .= ' '.$ones[substr($i, 1, 1)]; 
            
        } else { 
            
            $rettxt .= $ones[substr($i, 0, 1)].' '.$hundreds[0]; 
            
            $firstTwoNum = ltrim(substr($i, 1, 2), '0');
            
            if ($firstTwoNum <= 19) {
                if (isset($ones[$firstTwoNum])) {
                    $rettxt .= ' '.$ones[$firstTwoNum]; 
                }
            } else {
                $rettxt .= ' '.$tens[substr($i, 1, 1)]; 
                $rettxt .= ' '.$ones[substr($i, 2, 1)]; 
            }
        } 
        
        if ($key > 0) { 
            //$rettxt .= ' '.$hundreds[$key].', ';
            $rettxt .= ' '.$hundreds[$key].' '; 
        } 
    } 
    
    if ($currencyCode == 'usd') {
    
        if ($wholenum > 1) {
            $major = 'dollars'; 
        } else {
            $major = 'dollar'; 
        }

        $minor = 'cents';
        
    } elseif ($currencyCode == 'tug' || $currencyCode == 'mnt') {
    
        $major = 'tugrug'; 
        $minor = 'mungu';
        
    } elseif ($currencyCode == 'eur') {
    
        $major = 'euro'; 
        $minor = 'cent';
        
    } elseif ($currencyCode == 'aud') {
    
        if ($wholenum > 1) {
            $major = 'dollars'; 
        } else {
            $major = 'dollar'; 
        }

        $minor = 'cents';
        
    } elseif ($currencyCode == 'cny') {
    
        if ($wholenum > 1) {
            $major = 'yuan'; 
        } else {
            $major = 'yuan'; 
        }

        $minor = 'fen';
        
    } elseif ($currencyCode == 'gbp') {
    
        if ($wholenum > 1) {
            $major = 'pound'; 
        } else {
            $major = 'pound'; 
        }

        $minor = 'penny';
        
    } elseif ($currencyCode == 'jpy') {
    
        if ($wholenum > 1) {
            $major = 'yen'; 
        } else {
            $major = 'yen'; 
        }

        $minor = 'sen';
        
    } elseif ($currencyCode == 'rub') {
    
        if ($wholenum > 1) {
            $major = 'ruble'; 
        } else {
            $major = 'ruble'; 
        }

        $minor = 'kopek';
        
    } elseif ($currencyCode == 'sgd') {
    
        if ($wholenum > 1) {
            $major = 'dollars'; 
        } else {
            $major = 'dollar'; 
        }

        $minor = 'cents';
        
    } elseif ($currencyCode == 'empty') {
    
        $major = ''; 
        $minor = '';
        
    } else {
        
        if ($wholenum > 1) {
            $major = 'dollars'; 
        } else {
            $major = 'dollar'; 
        }

        $minor = 'cents';
    }
    
    if ($decnum > 0) { 
        
        $rettxt .= ' ' . $major . ' and '; 

        if ($decnum < 20) { 
            
            $rettxt .= $ones[intval( $decnum) ] . ' ' . $minor; 
            
        } elseif ($decnum < 100) { 
            
            $rettxt .= $tens[substr($decnum, 0, 1)]; 
            $rettxt .= ' '.$ones[substr($decnum, 1, 1)] . ' ' .$minor; 
        } 
        
    } else {
        
        $rettxt .= ' '.$major; 
    }
    
    $rettxt = Str::remove_doublewhitespace($rettxt);
    
    $rettxt = str_replace(', hundred,', ',', $rettxt);
    $rettxt = str_replace(', thousand,', ',', $rettxt);
    $rettxt = str_replace(', million,', ',', $rettxt);
    $rettxt = str_replace(', billion,', ',', $rettxt);
    $rettxt = str_replace(', trillion,', ',', $rettxt);
    $rettxt = str_replace(', quadrillion,', ',', $rettxt);
    $rettxt = trim($rettxt);
    $rettxt = rtrim($rettxt, ',');
    
    return ucfirst($rettxt); 
}

if (!function_exists('htmltotext')) {

    function htmltotext($comment) {

        /*$search = array('@<script[^>]*?>.*?</script>@si',
            '@<[\/\!]*?[^<>]*?>@si',
            '@([\r\n])[\s]+@',
            '@&(quot|#34);@i',
            '@&(amp|#38);@i',
            '@&(lt|#60);@i',
            '@&(gt|#62);@i',
            '@&(nbsp|#160);@i',
            '@&(iexcl|#161);@i',
            '@&(cent|#162);@i',
            '@&(pound|#163);@i',
            '@&(copy|#169);@i',
            '@&#(\d+);@e');

        $replace = array('',
            '',
            '\1',
            '"',
            '&',
            '<',
            '>',
            ' ',
            chr(161) . ' ',
            chr(162) . ' ',
            chr(163) . ' ',
            chr(169) . ' ',
            'chr(\1) ');

        $comment = preg_replace($search, $replace, $comment);
        $comment = preg_replace("[ \t\n\r]", " ", $comment);
        $comment = trim($comment);
        return $comment;*/
        
        return strip_tags($comment);
    }

}

function isNullOrZero($param) {
    if ($param == 0 || $param == null) {
        return true;
    }
    return false;
}

function convJson($response) {
    header('Content-Type: application/json');
    echo json_encode($response, JSON_UNESCAPED_UNICODE);
}

function issetVar(&$param) {
    return isset($param) ? Security::sanitize($param) : '';
}
function issetParam(&$param) {
    return isset($param) ? $param : '';
}
function issetParamZero(&$param) {
    return isset($param) ? $param : '0';
}
function issetParamArray(&$param) {
    return isset($param) ? $param : array();
}
function issetCount(&$param) {
    return (isset($param) && is_countable($param)) ? count($param) : 0;
}
function issetDefaultVal(&$param, $defaultValue) {
    return isset($param) ? $param : $defaultValue;
}
function checkDefaultVal(&$param, $defaultValue) {
    return (isset($param) && $param) ? $param : $defaultValue;
}
function checkFileDefaultVal(&$param, $defaultValue) {
    return (isset($param) && $param && file_exists($param)) ? $param : $defaultValue;
}
function issetJsonToArr(&$param) {
    return (isset($param) && $param) ? json_decode($param, true) : array();
}
function returnNull($param) {
    return (isset($param) && $param != '') ? $param : null;
}

function generatePasswordForCampus($length = 8) {
    $alphabet = "abcdefghijklmnopqrstuwxyzABCDEFGHIJKLMNOPQRSTUWXYZ0123456789";
    $pass = array(); //remember to declare $pass as an array
    $alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
    for ($i = 0; $i <= $length; $i++) {
        $n = rand(0, $alphaLength);
        $pass[] = $alphabet[$n];
    }
    return implode($pass); //turn the array into a string
}

function excelHeadTag($content, $sheetName = 'Excel Document Name', $editableObjs = array()) {
    
    loadPhpQuery();
    
    $content = str_replace('href="javascript:;" ', '', $content);
    $content = str_replace('<div class="left-rotate-span" style="display:inline;text-align:left;', '<div class="left-rotate-span" style="display:inline;text-align:center;', $content);
    
    $contentObject = phpQuery::newDocumentHTML($content);

    if ($contentObject->find('#ignore-excel')->length > 0) {
        $contentObject->find('#ignore-excel')->remove();
    }
    
    if ($editableObjs) {
        foreach ($editableObjs as $k => $v) {
            $contentObject->find('span[contenteditable="true"][id="'.$k.'"]')->html($v);
        }
    }

    $html = '<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40"><head>'
            . '<meta http-equiv="Content-Type" content="text/html;charset=UTF-8">'
            . '<!--[if gte mso 9]>'
            . '<xml>'
            . '<x:ExcelWorkbook><x:ExcelWorksheets><x:ExcelWorksheet><x:Name>' . $sheetName . '</x:Name>
            <x:WorksheetOptions>
            <x:DisplayGridlines/>
            </x:WorksheetOptions>
            </x:ExcelWorksheet>
            </x:ExcelWorksheets>
           </x:ExcelWorkbook>
           </xml>
           <![endif]-->
    <style type="text/css"> 
    body {
        line-height: 1.4em;
        font-size: 12px;
        color: #000;
        -webkit-print-color-adjust: exact;
    }
    a, a:visited, a:hover, a:active {
        color: #000;
        text-decoration: none; 
        cursor: default;
    } 
    a:after { content:\'\'; } 
    a[href]:after { content: none !important; }  
    table {table-layout: fixed;clear: both;border-collapse: collapse;word-wrap: break-word;} 
    table thead th, table thead td, table tbody td, table tfoot td {
        overflow: hidden;word-wrap: break-word;padding: 3px 6px
    } 
    .right-rotate, .right-rotate-span {mso-rotate: -90;}.left-rotate, .left-rotate-span {mso-rotate: 90;} 
    .xnum { 
        mso-number-format:"\#\,\#\#0\.00";
    } 
    .xtext { 
        mso-number-format:"\@";
    }
    </style> 
    </head>
    <body>' . $contentObject->html() . '</body>'
    . '</html>';

    return $html;
}

function wordHeadTag($content, $attr = array()) {

    $size = '595.45pt 841.7pt';
    $orientation = 'portrait';
    $top = '0.8in';
    $left = '0.6in';
    $right = '0.6in';
    $bottom = '0.8in';

    if (isset($attr['orientation']) && $attr['orientation'] == 'landscape') {
        $size = '841.7pt 595.45pt';
        $orientation = 'landscape';
    }

    $content = str_replace('href="javascript:;" ', '', $content);
    $content = str_replace('<div style="page-break-after: always;"></div>', '<br clear="all" style="page-break-before:always">', $content);

    loadPhpQuery();
    $contentObject = phpQuery::newDocumentHTML($content);

    if ($contentObject->find('.left-rotate')->length > 0) {
        $contentObject->find('.left-rotate')->parent('span')->parent('td')->addClass('left-rotate');
    }
    if ($contentObject->find('.right-rotate')->length > 0) {
        $contentObject->find('.right-rotate')->parent('span')->parent('td')->addClass('right-rotate');
    }
    
    if (isset($attr['editableObjs'])) {
        $editableObjs = $attr['editableObjs'];
        if (is_array($editableObjs)) {
            foreach ($editableObjs as $k => $v) {
                $contentObject->find('span[contenteditable="true"][id="'.$k.'"]')->html($v);
            }
        }
    }
    
    if (isset($attr['top']) && $attr['top']) {
        $top = $attr['top'];
    }
    if (isset($attr['left']) && $attr['left']) {
        $left = $attr['left'];
    }
    if (isset($attr['right']) && $attr['right']) {
        $right = $attr['right'];
    }
    if (isset($attr['bottom']) && $attr['bottom']) {
        $bottom = $attr['bottom'];
    }

    $wordHtml = '<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:doc" xmlns="http://www.w3.org/TR/REC-html40">' . "\n";
    $wordHtml .= '<head>' . "\n";
    $wordHtml .= '<!--[if gte mso 9]><xml><x:ExcelWorkbook><x:ExcelWorksheets><x:ExcelWorksheet><x:Name>{worksheet}</x:Name><x:WorksheetOptions><x:DisplayGridlines/></x:WorksheetOptions></x:ExcelWorksheet></x:ExcelWorksheets></x:ExcelWorkbook></xml><![endif]-->' . "\n";
    $wordHtml .= '<meta http-equiv="Content-Type" content="text/html;charset=UTF-8">' . "\n";
    $wordHtml .= '<style type="text/css">' . "\n";
    $wordHtml .= 'body {
            line-height: 12px; 
            font-size: 12px; 
            color: #000; 
            -webkit-print-color-adjust: exact;
        }' . "\n";
    $wordHtml .= issetParam($attr['css']);
    $wordHtml .= '@page {mso-page-border-surround-header: no;mso-page-border-surround-footer: no;}' . "\n";
    $wordHtml .= '@page Section1 {size:' . $size . ';mso-page-orientation:' . $orientation . ';margin:'.$top.' '.$right.' '.$bottom.' '.$left.';mso-header-margin:.2in;mso-footer-margin:.2in;mso-paper-source:0;} div.Section1 {page:Section1;}' . "\n";
    $wordHtml .= 'a, a:visited, a:hover, a:active {
            color: #000;
            text-decoration: none; 
            cursor: default;
        } 
        a:after { content:\'\'; } 
        a[href]:after { content: none !important; }' . "\n";
    $wordHtml .= 'table {table-layout: fixed;clear: both;border-collapse: collapse;word-wrap: break-word;}' . "\n";
    $wordHtml .= 'table thead th, table thead td, table tbody td, table tfoot td{overflow: hidden;word-wrap: break-word;padding: 2px 3px}' . "\n";
    $wordHtml .= '.right-rotate{mso-rotate: -90;}.left-rotate{mso-rotate: 90;}' . "\n";
    $wordHtml .= '</style>' . "\n";
    $wordHtml .= '</head>' . "\n";
    $wordHtml .= '<body><div class=Section1>' . $contentObject->html() . '</div></body>' . "\n";
    $wordHtml .= '</html>';

    return $wordHtml;
}

function ob_html_compress($buf) {
    return preg_replace(array('/<!--(?>(?!\[).)(.*)(?>(?!\]).)-->/Uis', '/[[:blank:]]+/'), array('', ' '), str_replace(array("\n", "\r", "\t"), '', $buf));
}

function repairArray($array, $columnCount) {

    $sizeOfArray = count($array);
    $reminder = $sizeOfArray % $columnCount;
    $rowCount = floor($sizeOfArray / $columnCount) + ($reminder > 0 ? 1 : 0);
    $newArray = array();

    for ($j = 0; $j < $rowCount; $j++) {

        $index = $j;
        $generatedColumnCount = (($reminder != 0 && $j + 1 == $rowCount) ? $reminder : $columnCount);
        for ($i = 0; $i < $generatedColumnCount; $i++) {
            array_push($newArray, $array[$index]);
            $index = $index + ($rowCount - ($reminder != 0 && $i >= $reminder ? 1 : 0));
        }
    }

    return $newArray;
}

if (!function_exists("imagecreatefrombmp")) {

    function imagecreatefrombmp($filename) {
        $f = fopen($filename, "rb");

        //read header
        $header = fread($f, 54);
        $header = unpack('c2identifier/Vfile_size/Vreserved/Vbitmap_data/Vheader_size/' .
                'Vwidth/Vheight/vplanes/vbits_per_pixel/Vcompression/Vdata_size/' .
                'Vh_resolution/Vv_resolution/Vcolors/Vimportant_colors', $header);

        if ($header['identifier1'] != 66 or $header['identifier2'] != 77) {
            die('Not a valid bmp file');
        }

        if ($header['bits_per_pixel'] != 24) {
            die('Only 24bit BMP images are supported');
        }

        $wid2 = ceil((3 * $header['width']) / 4) * 4;

        $wid = $header['width'];
        $hei = $header['height'];

        $img = imagecreatetruecolor($header['width'], $header['height']);

        //read pixels
        for ($y = $hei - 1; $y >= 0; $y--) {
            $row = fread($f, $wid2);
            $pixels = str_split($row, 3);
            for ($x = 0; $x < $wid; $x++) {
                imagesetpixel($img, $x, $y, dwordize($pixels[$x]));
            }
        }
        fclose($f);

        return $img;
    }
}

function dwordize($str) {
    $a = ord($str[0]);
    $b = ord($str[1]);
    $c = ord($str[2]);
    return $c * 256 * 256 + $b * 256 + $a;
}

function byte3($n) {
    return chr($n & 255) . chr(($n >> 8) & 255) . chr(($n >> 16) & 255);
}

function dword($n) {
    return pack("V", $n);
}

function word($n) {
    return pack("v", $n);
}

if (!function_exists('imagebmp')) {
    function imagebmp(&$img, $filename = false) {
        $wid = imagesx($img);
        $hei = imagesy($img);
        $wid_pad = str_pad('', $wid % 4, "\0");

        $size = 54 + ($wid + $wid_pad) * $hei;

        //prepare & save header
        $header['identifier'] = 'BM';
        $header['file_size'] = dword($size);
        $header['reserved'] = dword(0);
        $header['bitmap_data'] = dword(54);
        $header['header_size'] = dword(40);
        $header['width'] = dword($wid);
        $header['height'] = dword($hei);
        $header['planes'] = word(1);
        $header['bits_per_pixel'] = word(24);
        $header['compression'] = dword(0);
        $header['data_size'] = dword(0);
        $header['h_resolution'] = dword(0);
        $header['v_resolution'] = dword(0);
        $header['colors'] = dword(0);
        $header['important_colors'] = dword(0);

        if ($filename) {
            $f = fopen($filename, "wb");
            foreach ($header AS $h) {
                fwrite($f, $h);
            }

            //save pixels
            for ($y = $hei - 1; $y >= 0; $y--) {
                for ($x = 0; $x < $wid; $x++) {
                    $rgb = imagecolorat($img, $x, $y);
                    fwrite($f, byte3($rgb));
                }
                fwrite($f, $wid_pad);
            }
            fclose($f);
        } else {
            foreach ($header AS $h) {
                echo $h;
            }

            //save pixels
            for ($y = $hei - 1; $y >= 0; $y--) {
                for ($x = 0; $x < $wid; $x++) {
                    $rgb = imagecolorat($img, $x, $y);
                    echo byte3($rgb);
                }
                echo $wid_pad;
            }
        }
    }
}

function resizeAndCompressImagefunction($file, $w, $h, $crop = false) {
    list($width, $height) = getimagesize($file);
    $r = $width / $height;
    if ($crop) {
        if ($width > $height) {
            $width = ceil($width - ($width * ($r - $w / $h)));
        } else {
            $height = ceil($height - ($height * ($r - $w / $h)));
        }
        $newwidth = $w;
        $newheight = $h;
    } else {
        if ($w / $h > $r) {
            $newwidth = $h * $r;
            $newheight = $h;
        } else {
            $newheight = $w / $r;
            $newwidth = $w;
        }
    }
    $src = imagecreatefrompng($file);
    $dst = imagecreatetruecolor($newwidth, $newheight);
    imagecopyresampled($dst, $src, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);
    return $dst;
}

function integerToRoman($integer) {
    $integer = intval($integer);
    $result = '';

    $lookup = array(
        'M' => 1000,
        'CM' => 900,
        'D' => 500,
        'CD' => 400,
        'C' => 100,
        'XC' => 90,
        'L' => 50,
        'XL' => 40,
        'X' => 10,
        'IX' => 9,
        'V' => 5,
        'IV' => 4,
        'I' => 1);

    foreach ($lookup as $roman => $value) {
        $matches = intval($integer / $value);

        $result .= str_repeat($roman, $matches);

        $integer = $integer % $value;
    }

    return $result;
}

function menuOpen($pages) {
    $url = isset($_GET['url']) ? $_GET['url'] : null;
    $url = rtrim($url, '/');
    $url = filter_var($url, FILTER_SANITIZE_URL);
    $url = explode('/', $url);
    $do = $url[0];
    $pages = explode(",", $pages);
    foreach ($pages as $page) {
        if ($page == $do) {
            return ' class="start active"';
        }
    }
}

function menuArrowOpen($pages) {
    $url = isset($_GET['url']) ? $_GET['url'] : null;
    $url = rtrim($url, '/');
    $url = filter_var($url, FILTER_SANITIZE_URL);
    $url = explode('/', $url);
    $do = $url[0];
    $pages = explode(",", $pages);
    foreach ($pages as $page) {
        if ($page == $do) {
            return ' open';
        }
    }
}

function menuActive($pages) {
    $url = isset($_GET['url']) ? $_GET['url'] : null;
    $url = rtrim($url, '/');
    $url = filter_var($url, FILTER_SANITIZE_URL);
    $url = explode('/', $url);
    $do = $url[0];
    $pages = explode(",", $pages);
    foreach ($pages as $page) {
        if ($page == $do) {
            return ' class="active"';
        }
    }
}

function menuSubActive($pages) {
    $url = isset($_GET['url']) ? $_GET['url'] : null;
    $url = rtrim($url, '/');
    $url = filter_var($url, FILTER_SANITIZE_URL);
    $url = explode('/', $url);
    $do = $url[0];
    $pages = explode(",", $pages);
    foreach ($pages as $page) {
        if ($page == $do) {
            return ' active';
        }
    }
}

function menuSelected($pages) {
    $url = isset($_GET['url']) ? $_GET['url'] : null;
    $url = rtrim($url, '/');
    $url = filter_var($url, FILTER_SANITIZE_URL);
    $url = explode('/', $url);
    $do = $url[0];
    $pages = explode(",", $pages);
    foreach ($pages as $page) {
        if ($page == $do) {
            return '<span class="selected"></span>';
        }
    }
}

function menuOpenFull($pages) {
    $url = isset($_GET['url']) ? $_GET['url'] : null;
    $url = rtrim($url, '/');
    $url = filter_var($url, FILTER_SANITIZE_URL);
    $do = $url;
    $pages = explode(",", $pages);
    foreach ($pages as $page) {
        if ($page == $do) {
            return ' class="start active"';
        }
    }
}

function menuArrowOpenFull($pages) {
    $url = isset($_GET['url']) ? $_GET['url'] : null;
    $url = rtrim($url, '/');
    $url = filter_var($url, FILTER_SANITIZE_URL);
    $do = $url;
    $pages = explode(",", $pages);
    foreach ($pages as $page) {
        if ($page == $do) {
            return ' open';
        }
    }
}

function menuActiveFull($pages) {
    $url = isset($_GET['url']) ? $_GET['url'] : null;
    $url = rtrim($url, '/');
    $url = filter_var($url, FILTER_SANITIZE_URL);
    $do = $url;
    $pages = explode(",", $pages);
    foreach ($pages as $page) {
        if ($page == $do) {
            return ' class="active"';
        }
    }
}

function menuSelectedFull($pages) {
    $url = isset($_GET['url']) ? $_GET['url'] : null;
    $url = rtrim($url, '/');
    $url = filter_var($url, FILTER_SANITIZE_URL);
    $do = $url;
    $pages = explode(",", $pages);
    foreach ($pages as $page) {
        if ($page == $do) {
            return '<span class="selected"></span>';
        }
    }
}

function getParam($param) {
    $url = isset($_GET['url']) ? $_GET['url'] : null;
    $url = rtrim($url, '/');
    $url = filter_var($url, FILTER_SANITIZE_URL);
    $url = explode('/', $url);
    if (isset($url[$param])) {
        return empty($url[1]) ? "index" : $url[1 + $param];
    } else {
        return null;
    }
}

function getControllerName() {
    $url = isset($_GET['url']) ? $_GET['url'] : null;
    $url = rtrim($url, '/');
    $url = filter_var($url, FILTER_SANITIZE_URL);
    $url = explode('/', $url);
    if (isset($url[0])) {
        return $url[0];
    } else {
        return null;
    }
}

function post($var) {
    if (isset($_POST[$var])) return $_POST[$var];
}

function get($var) {
    if (isset($_GET[$var])) return $_GET[$var];
}

function autoVersion($file = '', $local_path = '') {
    if (!file_exists($file)) return $file;

    preg_match('/(.*)\.([a-zA-Z0-9]{2,4})/', $file, $matches);
    return $matches[1] . '.v' . filemtime($local_path . $file) . '.' . $matches[2];
}

function word_limiter($str, $limit = 100, $end_char = '&#8230;') {
    if (trim($str) === '') {
        return $str;
    }

    preg_match('/^\s*+(?:\S++\s*+){1,' . (int) $limit . '}/', $str, $matches);

    if (strlen($str) === strlen($matches[0])) {
        $end_char = '';
    }

    return rtrim($matches[0]) . $end_char;
}

function repairSql($pValue) {

    if (DB_DRIVER == 'mssqlnative') {
        $pValue = str_replace('||', '+', $pValue);
        $pValue = str_replace('SUBSTR(', 'SUBSTRING(', $pValue);
        $pValue = str_replace('LENGTH', 'LEN', $pValue);
        $pValue = str_replace('NVL(', 'COALESCE(', $pValue);
        $pValue = str_replace('TO_NUMBER(', 'CONVERT(CONVERT, ', $pValue);
        $pValue = str_replace('TO_CHAR(', 'CONVERT(NVARCHAR, ', $pValue);
        $pValue = str_replace('TO_DATE(', 'DBO.TO_DATE(', $pValue);
        $pValue = str_replace('TRIM(', 'DBO.TRIM(', $pValue);
        $pValue = str_replace('TRANSLATE(', 'DBO.TRANSLATE(', $pValue);
        $pValue = str_replace('SYSDATE', 'SYSDATETIME()', $pValue);
        $pValue = str_replace('FROM DUAL', '', $pValue);
        $pValue = str_replace("'YYYY-MM-DD'", '20', $pValue);
        $pValue = str_replace("'YYYY-MM-DD HH24:mi:ss'", '20', $pValue);
        $pValue = str_replace("'YYYYMMDD''", '112', $pValue);
        $pValue = str_replace("'YYYYMM''", '112', $pValue);
        $pValue = str_replace("'YYMM''", '12', $pValue);
        $pValue = str_replace("'YYYY/MM/DD''", '11', $pValue);
    }

    return $pValue;
}

if (!function_exists('jsonResponse')) {
    function jsonResponse($param) {
        header('Content-Type: application/json');
        echo json_encode($param, JSON_UNESCAPED_UNICODE); exit();
    }
}

if (!function_exists('json_last_error_msg')) {
    function json_last_error_msg() {
        static $ERRORS = array(
            JSON_ERROR_NONE => 'No error',
            JSON_ERROR_DEPTH => 'Maximum stack depth exceeded',
            JSON_ERROR_STATE_MISMATCH => 'State mismatch (invalid or malformed JSON)',
            JSON_ERROR_CTRL_CHAR => 'Control character error, possibly incorrectly encoded',
            JSON_ERROR_SYNTAX => 'Syntax error',
            JSON_ERROR_UTF8 => 'Malformed UTF-8 characters, possibly incorrectly encoded'
        );

        $error = json_last_error();
        return isset($ERRORS[$error]) ? $ERRORS[$error] : 'Unknown error';
    }
}

function compressedJsonResponse($response) {
    $compressed = gzencode(json_encode($response, JSON_UNESCAPED_UNICODE));
		
    header('Content-Type: application/json; charset=utf-8');
    header('Content-Encoding: gzip');
    header('Content-Length: ' . strlen($compressed));

    echo $compressed;
}

if (!function_exists('array_column')) {
    function array_column($array, $column_name) {
        return array_map(function($element) use($column_name){return $element[$column_name];}, $array);
    }
}

/* == Process helper functions == */

function helperGetLookupFieldValBp($row, $path, $field) {
    if (isset($row[$path]) && is_array($row[$path]) && isset($row[$path]['rowdata'][$field])) {
        return returnNull($row[$path]['rowdata'][$field]);
    } else {
        return null;
    }
}

function helperSumFieldBp($data, $field) {
    return array_sum(array_column($data, $field));
}

function helperMinFieldBp($data, $field) {
    return min(array_column($data, $field));
}

function helperMaxFieldBp($data, $field) {
    return max(array_column($data, $field));
}

function helperCheckEmptyRowGroup($row, $groupPath) {
    
    if (isset($row[$groupPath])) {
        
        if (is_array($row[$groupPath])) {
            $concatVals = implode('', array_filter($row[$groupPath], function($v, $k){return ($k == 'createduserid' || $k == 'createddate' || $k == 'modifieduserid' || $k == 'modifieddate') ? '' : !is_array($v); }, ARRAY_FILTER_USE_BOTH)); 
            if ($concatVals == '') {
                return null;
            } 
        }
        
        return returnNull($row[$groupPath]);
    } 
    
    return null;
}

/* == Process helper functions == */

function xmlPrettyPrint($xmlStrinig, $html_output = false) {
    $xml_obj = new SimpleXMLElement($xmlStrinig);
    $level = 4;
    $indent = 0; // current indentation level
    $pretty = array();

    // get an array containing each XML element
    $xml = explode("\n", preg_replace('/>\s*</', ">\n<", $xml_obj->asXML()));

    // shift off opening XML tag if present
    if (count($xml) && preg_match('/^<\?\s*xml/', $xml[0])) {
        $pretty[] = array_shift($xml);
    }

    foreach ($xml as $el) {
        if (preg_match('/^<([\w])+[^>\/]*>$/U', $el)) {
            $pretty[] = str_repeat(' ', $indent) . $el;
            $indent += $level;
        } else {
            if (preg_match('/^<\/.+>$/', $el)) {
                $indent -= $level;  // closing tag, decrease indent
            }
            if ($indent < 0) {
                $indent += $level;
            }
            $pretty[] = str_repeat(' ', $indent) . $el;
        }
    }
    
    $xml = implode("\n", $pretty);
    
    return ($html_output) ? htmlentities($xml) : $xml;
}

function urlToHtml($url) {

    $headers[] = "User-Agent: Mozilla/5.0 (Windows NT 6.3; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/59.0.3071.115 Safari/537.36";
    $headers[] = "Accept:text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8";
    $headers[] = "Accept-Language:en-US,en;q=0.8";
    $headers[] = "Accept-Encoding:gzip,deflate";
    $headers[] = "Accept-Charset:UTF-8,utf-8;q=0.7,*;q=0.7";
    $headers[] = "Keep-Alive:115";
    $headers[] = "Connection:keep-alive";
    $headers[] = "Cache-Control:max-age=0";

    $curl = curl_init();
    
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($curl, CURLOPT_ENCODING, 'gzip');
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
    
    $data = curl_exec($curl);
    
    curl_close($curl);
    
    return $data;
}

function countUpperLetter($string) {
    return strlen(preg_replace('/[^A-Z]+/', '', $string));
}

function countLowerLetter($string) {
    return strlen(preg_replace('/[^a-z]+/', '', $string));
}

function base64_to_jpeg($base64_string, $output_file) {
    $ifp = fopen( $output_file, "wb" ); 
    fwrite( $ifp, base64_decode( $base64_string) ); 
    fclose( $ifp ); 
    return( $output_file ); 
}

function randomPassword($maxValue = 8) {
    $alphabet = "abcdefghijklmnopqrstuwxyzABCDEFGHIJKLMNOPQRSTUWXYZ0123456789";
    (Array) $pass = array();
    $alphaLength = strlen($alphabet) - 1;
    for ($i = 0; $i < 8; $i++) {
        $n = rand(0, $alphaLength);
        $pass[] = $alphabet[$n];
    }
    return implode($pass);
}

function headerRefreshHtml($msg) {
    
    $server_protocol = (isset($_SERVER['SERVER_PROTOCOL'])) ? $_SERVER['SERVER_PROTOCOL'] : null;

    if (substr(php_sapi_name(), 0, 3) == 'cgi') {
        header("Status: 500 Internal Server Error", true);
    } elseif ($server_protocol == 'HTTP/1.1' || $server_protocol == 'HTTP/1.0') {
        header($server_protocol . " 500 Internal Server Error", true, 500);
    } else {
        header("HTTP/1.1 500 Internal Server Error", true, 500);
    }
            
    $html = '<html>
        <head>
            <meta charset="utf-8" />
            <title>500</title>
            <meta http-equiv="X-UA-Compatible" content="IE=edge" />
            <meta content="width=device-width, initial-scale=1" name="viewport" />
            <meta http-equiv="refresh" content="10" />
            <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate" />
            <meta http-equiv="Pragma" content="no-cache" />
            <meta http-equiv="Expires" content="0" />
            <style>
                body {
                    background-color: #fff;
                    font-family: arial, sans-serif;
                }
            </style>
        </head>
        <body>
            <div style="border:0px solid black;margin-top:50px;padding-left:50px">
                <div style="color:red;font-size:36px;font-weight:bold;">500!</div>
                <div style="color:black;font-weight:bold;">
                    Internal server error.
                </div>
            </div>
        </body>
    </html>';
    
    return $html;
}

function getLastDayOfDate($year, $month) {
    if (!$year || !$month) {
        $year = $month = '';
    }
    $date = $year.'-'.$month.'-01';
    $isDate = (bool) strtotime($date);
    
    if ($isDate) {
        return date('t', strtotime($date));
    } else {
        return null;
    }
}

if (!function_exists('hash_equals')) {
    defined('USE_MB_STRING') or define('USE_MB_STRING', function_exists('mb_strlen'));
    /**
     * hash_equals — Timing attack safe string comparison
     *
     * Arguments are null by default, so an appropriate warning can be triggered
     *
     * @param string $known_string
     * @param string $user_string
     *
     * @link http://php.net/manual/en/function.hash-equals.php
     *
     * @return boolean
     */
    function hash_equals($known_string = null, $user_string = null)
    {
        $argc = func_num_args();
        // Check the number of arguments
        if ($argc < 2) {
            //trigger_error(sprintf('hash_equals() expects exactly 2 parameters, %d given', $argc), E_USER_WARNING);
            return false;
        }
        // Check $known_string type
        if (!is_string($known_string)) {
            //trigger_error(sprintf('hash_equals(): Expected known_string to be a string, %s given', strtolower(gettype($known_string))), E_USER_WARNING);
            return false;
        }
        // Check $user_string type
        if (!is_string($user_string)) {
            //trigger_error(sprintf('hash_equals(): Expected user_string to be a string, %s given', strtolower(gettype($user_string))), E_USER_WARNING);
            return false;
        }
        // Ensures raw binary string length returned
        $strlen = function($string) {
            if (USE_MB_STRING) {
                return mb_strlen($string, '8bit');
            }
            return strlen($string);
        };
        // Compare string lengths
        if (($length = $strlen($known_string)) !== $strlen($user_string)) {
            return false;
        }
        $diff = 0;
        // Calculate differences
        for ($i = 0; $i < $length; $i++) {
            $diff |= ord($known_string[$i]) ^ ord($user_string[$i]);
        }
        return $diff === 0;
    }
}

function changeValueArray(&$value, $key)  {
    if (strtolower($key) == 'fingerprint' && !file_exists(URL . $value) && $value) { 
        $value = file_get_contents($value);
    }
}

function changeValueToInt(&$value, $key)  {
    if (strtolower($key) == 'value' || strtolower($key) == 'y') { 
        $value = ($value) ? floatval($value) : 0 ;
    }
}

function changeStrcleanout(&$value, $key)  {
    $value = Str::cleanOut($value);
}

function changeValueBase(&$value, $key)  {
    
    if (strtolower($key) == 'fingerprint') { 
        $value = base64_encode($value);
    }
    
    if (strtolower($key) == 'operatorFingerPrint') { 
        $value = base64_encode($value);
    }
    
    if (strtolower($key) == 'citizenFingerPrint') { 
        $value = base64_encode($value);
    }
    
    if (strtolower($key) == 'image') { 
        $value = base64_encode($value);
    }
}

function changeNullValueByImg(&$value, $key)  {
    if (strtolower($key) == 'image') { 
        $value = '';
    }
}

function changeNullValueByKey(&$value, $key)  {
    if (strtolower($key) == 'fingerprint') { 
        $value = '';
    }
    if (strtolower($key) == 'signature') { 
        $value = '';
    }
}

function changeValueByKey(&$value, $key)  {
    
    if (strtolower($key) == 'type' && $value === 'DIV') { 
        $value = 'VIEW';
    }

    if (strtolower($key) == 'type' && in_array($value, array('H1', 'SPAN', 'STRONG'))) { 
        $value = 'TEXT';
    }
    
}

function getYoutubeVideoID($youtube_video_url) {

    $pattern = '#([\/|\?|&]vi?[\/|=]|youtu\.be\/|embed\/)([a-zA-Z0-9_-]+)#';

    if (preg_match($pattern, $youtube_video_url, $match)) {
        return end($match);
    }

    return false;
}

function getAgeFromRegNumber($regNumber) {
    
    if ($regNumber) {
        
        $number = mb_substr($regNumber, 2, 10);
        $year   = substr($number, 0, 2);
        $month  = (int) substr($number, 2, 2);
        $day    = (int) substr($number, 4, 2);

        if ($month > 20) {
            $birthDate = '20'.$year.'-'.($month - 20).'-'.$day;
        } else {
            $birthDate = '19'.$year.'-'.$month.'-'.$day;
        }
        
        $birthDate = new DateTime($birthDate);
        $today     = new DateTime('today');
        $age       = $birthDate->diff($today)->y;
        
        return $age;
        
    } else {
        return 0;
    }
}

function pa($arr) {
    echo "<pre>";
    print_r($arr);
    echo "</pre>";
    die('__END__');
}

function dd($arr) {
    var_dump($arr);
    die();
}

if (!function_exists('is_countable')) {
    function is_countable($var) {
        return (is_array($var) || $var instanceof Countable);
    }
}

function turnUrlIntoHyperlink($text){

    $reg_exUrl = "/(?i)\b((?:https?:\/\/|www\d{0,3}[.]|[a-z0-9.\-]+[.][a-z]{2,4}\/)(?:[^\s()<>]+|\(([^\s()<>]+|(\([^\s()<>]+\)))*\))+(?:\(([^\s()<>]+|(\([^\s()<>]+\)))*\)|[^\s`!()\[\]{};:'\".,<>?«»“”‘’]))/";

    if (preg_match($reg_exUrl, $text, $url)) {

        if (strpos($url[0], ':') === false) {
            $link = 'http://'.$url[0];
        } else {
            $link = $url[0];
        }

        // make the urls hyper links
        return preg_replace($reg_exUrl, '<a href="'.$link.'" title="'.$url[0].'" target="_blank">'.$url[0].'</a>', $text);

    } else {
        // if no urls in the text just return the text
        return $text;
    }
}

if (!function_exists('random_color')) {
    function random_color() {
        return '#' . str_pad(dechex(mt_rand(0, 0xFFFFFF)), 6, '0', STR_PAD_LEFT);
    }
}

if (!function_exists('formatSizeUnits')) {
    function formatSizeUnits($bytes, $op = 2) {
        if ($bytes >= 1073741824) {
            $bytes = number_format($bytes / 1073741824, $op) . ' gb';
        } elseif ($bytes >= 1048576) {
            $bytes = number_format($bytes / 1048576, $op) . ' mb';
        } elseif ($bytes >= 1024) {
            $bytes = number_format($bytes / 1024, $op) . ' kb';
        } elseif ($bytes > 1) {
            $bytes = $bytes . ' bytes';
        } elseif ($bytes == 1) {
            $bytes = $bytes . ' byte';
        } else {
            $bytes = '0 bytes';
        }

        return $bytes;
    }
}

if (!function_exists('excelColumnRange')) {
    function excelColumnRange($firstLetter, $secondLetter) {
        $secondLetter++;
        $letters = array();
        $letter = $firstLetter;

        while ($letter !== $secondLetter) {
            $letters[] = $letter++;
        }

        return $letters;
    }
}

function remove_utf8_bom($text) {
    $bom = pack('H*','EFBBBF');
    $text = preg_replace("/^$bom/", '', $text);
    return $text;
}

function getImageDataByCurl($url) {
        
    try {

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Cache-Control: no-cache'));

        $data = curl_exec($ch);
        $err = curl_error($ch);
        $errNo = curl_errno($ch);

        $contentType = curl_getinfo($ch, CURLINFO_CONTENT_TYPE);
        
        curl_close($ch);
        
        if ($err) {

            if ($errNo == 28) { 
                $response = array('status' => 'error', 'message' => 'Хариу ирэхгүй удсан!');
            } else {
                $response = array('status' => 'error', 'message' => $err);
            }
            
        } else {
            
            if (strpos($contentType, 'image') !== false) {
                $response = array('status' => 'success', 'data' => $data);
            } else {
                $response = array('status' => 'error', 'message' => $data);
            }
        }

    } catch (Exception $ex) {
        $response = array('status' => 'error', 'message' => $ex->getMessage());
    }

    return $response;
}

function mimeToExt($mime) {
    $mime_map = array(
        'video/3gpp2'                                                               => '3g2',
        'video/3gp'                                                                 => '3gp',
        'video/3gpp'                                                                => '3gp',
        'application/x-compressed'                                                  => '7zip',
        'audio/x-acc'                                                               => 'aac',
        'audio/ac3'                                                                 => 'ac3',
        'application/postscript'                                                    => 'ai',
        'audio/x-aiff'                                                              => 'aif',
        'audio/aiff'                                                                => 'aif',
        'audio/x-au'                                                                => 'au',
        'video/x-msvideo'                                                           => 'avi',
        'video/msvideo'                                                             => 'avi',
        'video/avi'                                                                 => 'avi',
        'application/x-troff-msvideo'                                               => 'avi',
        'application/macbinary'                                                     => 'bin',
        'application/mac-binary'                                                    => 'bin',
        'application/x-binary'                                                      => 'bin',
        'application/x-macbinary'                                                   => 'bin',
        'image/bmp'                                                                 => 'bmp',
        'image/x-bmp'                                                               => 'bmp',
        'image/x-bitmap'                                                            => 'bmp',
        'image/x-xbitmap'                                                           => 'bmp',
        'image/x-win-bitmap'                                                        => 'bmp',
        'image/x-windows-bmp'                                                       => 'bmp',
        'image/ms-bmp'                                                              => 'bmp',
        'image/x-ms-bmp'                                                            => 'bmp',
        'application/bmp'                                                           => 'bmp',
        'application/x-bmp'                                                         => 'bmp',
        'application/x-win-bitmap'                                                  => 'bmp',
        'application/cdr'                                                           => 'cdr',
        'application/coreldraw'                                                     => 'cdr',
        'application/x-cdr'                                                         => 'cdr',
        'application/x-coreldraw'                                                   => 'cdr',
        'image/cdr'                                                                 => 'cdr',
        'image/x-cdr'                                                               => 'cdr',
        'zz-application/zz-winassoc-cdr'                                            => 'cdr',
        'application/mac-compactpro'                                                => 'cpt',
        'application/pkix-crl'                                                      => 'crl',
        'application/pkcs-crl'                                                      => 'crl',
        'application/x-x509-ca-cert'                                                => 'crt',
        'application/pkix-cert'                                                     => 'crt',
        'text/css'                                                                  => 'css',
        'text/x-comma-separated-values'                                             => 'csv',
        'text/comma-separated-values'                                               => 'csv',
        'application/vnd.msexcel'                                                   => 'csv',
        'application/x-director'                                                    => 'dcr',
        'application/vnd.openxmlformats-officedocument.wordprocessingml.document'   => 'docx',
        'application/x-dvi'                                                         => 'dvi',
        'message/rfc822'                                                            => 'eml',
        'application/x-msdownload'                                                  => 'exe',
        'video/x-f4v'                                                               => 'f4v',
        'audio/x-flac'                                                              => 'flac',
        'video/x-flv'                                                               => 'flv',
        'image/gif'                                                                 => 'gif',
        'application/gpg-keys'                                                      => 'gpg',
        'application/x-gtar'                                                        => 'gtar',
        'application/x-gzip'                                                        => 'gzip',
        'application/mac-binhex40'                                                  => 'hqx',
        'application/mac-binhex'                                                    => 'hqx',
        'application/x-binhex40'                                                    => 'hqx',
        'application/x-mac-binhex40'                                                => 'hqx',
        'text/html'                                                                 => 'html',
        'image/x-icon'                                                              => 'ico',
        'image/x-ico'                                                               => 'ico',
        'image/vnd.microsoft.icon'                                                  => 'ico',
        'text/calendar'                                                             => 'ics',
        'application/java-archive'                                                  => 'jar',
        'application/x-java-application'                                            => 'jar',
        'application/x-jar'                                                         => 'jar',
        'image/jp2'                                                                 => 'jp2',
        'video/mj2'                                                                 => 'jp2',
        'image/jpx'                                                                 => 'jp2',
        'image/jpm'                                                                 => 'jp2',
        'image/jpeg'                                                                => 'jpg',
        'image/pjpeg'                                                               => 'jpeg',
        'application/x-javascript'                                                  => 'js',
        'application/json'                                                          => 'json',
        'text/json'                                                                 => 'json',
        'application/vnd.google-earth.kml+xml'                                      => 'kml',
        'application/vnd.google-earth.kmz'                                          => 'kmz',
        'text/x-log'                                                                => 'log',
        'audio/x-m4a'                                                               => 'm4a',
        'application/vnd.mpegurl'                                                   => 'm4u',
        'audio/midi'                                                                => 'mid',
        'application/vnd.mif'                                                       => 'mif',
        'video/quicktime'                                                           => 'mov',
        'video/x-sgi-movie'                                                         => 'movie',
        'audio/mpeg'                                                                => 'mp3',
        'audio/mpg'                                                                 => 'mp3',
        'audio/mpeg3'                                                               => 'mp3',
        'audio/mp3'                                                                 => 'mp3',
        'video/mp4'                                                                 => 'mp4',
        'video/mpeg'                                                                => 'mpeg',
        'application/oda'                                                           => 'oda',
        'audio/ogg'                                                                 => 'ogg',
        'video/ogg'                                                                 => 'ogg',
        'application/ogg'                                                           => 'ogg',
        'application/x-pkcs10'                                                      => 'p10',
        'application/pkcs10'                                                        => 'p10',
        'application/x-pkcs12'                                                      => 'p12',
        'application/x-pkcs7-signature'                                             => 'p7a',
        'application/pkcs7-mime'                                                    => 'p7c',
        'application/x-pkcs7-mime'                                                  => 'p7c',
        'application/x-pkcs7-certreqresp'                                           => 'p7r',
        'application/pkcs7-signature'                                               => 'p7s',
        'application/pdf'                                                           => 'pdf',
        'application/octet-stream'                                                  => 'pdf',
        'application/x-x509-user-cert'                                              => 'pem',
        'application/x-pem-file'                                                    => 'pem',
        'application/pgp'                                                           => 'pgp',
        'application/x-httpd-php'                                                   => 'php',
        'application/php'                                                           => 'php',
        'application/x-php'                                                         => 'php',
        'text/php'                                                                  => 'php',
        'text/x-php'                                                                => 'php',
        'application/x-httpd-php-source'                                            => 'php',
        'image/png'                                                                 => 'png',
        'image/x-png'                                                               => 'png',
        'application/powerpoint'                                                    => 'ppt',
        'application/vnd.ms-powerpoint'                                             => 'ppt',
        'application/vnd.ms-office'                                                 => 'ppt',
        'application/msword'                                                        => 'doc',
        'application/vnd.openxmlformats-officedocument.presentationml.presentation' => 'pptx',
        'application/x-photoshop'                                                   => 'psd',
        'image/vnd.adobe.photoshop'                                                 => 'psd',
        'audio/x-realaudio'                                                         => 'ra',
        'audio/x-pn-realaudio'                                                      => 'ram',
        'application/x-rar'                                                         => 'rar',
        'application/rar'                                                           => 'rar',
        'application/x-rar-compressed'                                              => 'rar',
        'audio/x-pn-realaudio-plugin'                                               => 'rpm',
        'application/x-pkcs7'                                                       => 'rsa',
        'text/rtf'                                                                  => 'rtf',
        'text/richtext'                                                             => 'rtx',
        'video/vnd.rn-realvideo'                                                    => 'rv',
        'application/x-stuffit'                                                     => 'sit',
        'application/smil'                                                          => 'smil',
        'text/srt'                                                                  => 'srt',
        'image/svg+xml'                                                             => 'svg',
        'application/x-shockwave-flash'                                             => 'swf',
        'application/x-tar'                                                         => 'tar',
        'application/x-gzip-compressed'                                             => 'tgz',
        'image/tiff'                                                                => 'tiff',
        'text/plain'                                                                => 'txt',
        'text/x-vcard'                                                              => 'vcf',
        'application/videolan'                                                      => 'vlc',
        'text/vtt'                                                                  => 'vtt',
        'audio/x-wav'                                                               => 'wav',
        'audio/wave'                                                                => 'wav',
        'audio/wav'                                                                 => 'wav',
        'application/wbxml'                                                         => 'wbxml',
        'video/webm'                                                                => 'webm',
        'audio/x-ms-wma'                                                            => 'wma',
        'application/wmlc'                                                          => 'wmlc',
        'video/x-ms-wmv'                                                            => 'wmv',
        'video/x-ms-asf'                                                            => 'wmv',
        'application/xhtml+xml'                                                     => 'xhtml',
        'application/excel'                                                         => 'xl',
        'application/msexcel'                                                       => 'xls',
        'application/x-msexcel'                                                     => 'xls',
        'application/x-ms-excel'                                                    => 'xls',
        'application/x-excel'                                                       => 'xls',
        'application/x-dos_ms_excel'                                                => 'xls',
        'application/xls'                                                           => 'xls',
        'application/x-xls'                                                         => 'xls',
        'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'         => 'xlsx',
        'application/vnd.ms-excel'                                                  => 'xlsx',
        'application/xml'                                                           => 'xml',
        'text/xml'                                                                  => 'xml',
        'text/xsl'                                                                  => 'xsl',
        'application/xspf+xml'                                                      => 'xspf',
        'application/x-compress'                                                    => 'z',
        'application/x-zip'                                                         => 'zip',
        'application/zip'                                                           => 'zip',
        'application/x-zip-compressed'                                              => 'zip',
        'application/s-compressed'                                                  => 'zip',
        'multipart/x-zip'                                                           => 'zip',
        'text/x-scriptzsh'                                                          => 'zsh',
    );

    return isset($mime_map[$mime]) ? $mime_map[$mime] : null;
}

function getBasePath() {
    return isset($GLOBALS['MAIN_BASEPATH']) ? $GLOBALS['MAIN_BASEPATH'] : BASEPATH;
}

function maskEmail($email, $mask = '*') {
    $em   = explode('@', $email);
    $name = implode('@', array_slice($em, 0, count($em) - 1));
    $nlen = strlen($name);
    $len  = floor($nlen / 2);
    
    return substr($name, 0, $len) . str_repeat($mask, $nlen - $len) . '@' . end($em);   
}

function maskPhoneNumber($number, $mask = '*') {
    $mask_number = str_repeat($mask, strlen($number) - 4) . substr($number, -4);
    
    return $mask_number;
}
    
function asci2uni($text) {
    $vuni = array('а', 'б', 'в', 'г', 'д', 'е', 'ё', 'ж', 'з', 'и', 'й', 'к', 'л', 'м', 'н', 'о', 'ө',
        'п', 'р', 'с', 'т', 'у', 'ү', 'ф', 'х', 'ц', 'ч', 'ш', 'щ', 'ь', 'ъ', 'э', 'ю', 'я', 'А', 'Б',
        'В', 'Г', 'Д', 'Е', 'Ё', 'Ж', 'З', 'И', 'Й', 'К', 'Л', 'М', 'Н', 'О', 'Ө', 'П', 'Р', 'С', 'Т',
        'У', 'Ү', 'Ф', 'Х', 'Ц', 'Ч', 'Ш', 'Щ', 'Ь', 'Ъ', 'Э', 'Ю', 'Я', 'ы', 'Ы');
    $vasc = array('à', 'á', 'â', 'ã', 'ä', 'å', '¸', 'æ', 'ç', 'è', 'é', 'ê', 'ë', 'ì', 'í', 'î', 'º',
        'ï', 'ð', 'ñ', 'ò', 'ó', '¿', 'ô', 'õ', 'ö', '÷', 'ø', 'ù', 'ü', 'ú', 'ý', 'þ', 'ÿ', 'À', 'Á',
        'Â', 'Ã', 'Ä', 'Å', '¨', 'Æ', 'Ç', 'È', 'É', 'Ê', 'Ë', 'Ì', 'Í', 'Î', 'ª', 'Ï', 'Ð', 'Ñ', 'Ò',
        'Ó', '¯', 'Ô', 'Õ', 'Ö', '×', 'Ø', 'Ù', 'Ü', 'Ú', 'Ý', 'Þ', 'ß', 'û', 'Û');
    $text = str_replace($vasc, $vuni, $text);
    return $text;
}

function is_html($string) {
    return preg_match("/<[^<]+>/", $string, $m) != 0;
}

if (!function_exists('array_key_last')) {
    function array_key_last($array) {
        if (!is_array($array) || empty($array)) {
            return null;
        }
        return array_keys($array)[count($array)-1];
    }
}

if (!function_exists('array_key_first')) {
    function array_key_first(array $arr) {
        foreach ($arr as $key => $unused) {
            return $key;
        }
        return null;
    }
}

if (!function_exists('boolval')) {
    function boolval($val) {
        return (bool) $val;
    }
}

if (!function_exists('is_base64')) {
    function is_base64($data) {
        return base64_encode(base64_decode($data, true)) === $data;
    }
}
if (!function_exists('is_base64_encode')) {
    function is_base64_encode($data) {
        return is_base64($data) ? $data : base64_encode($data);
    }
}
if (!function_exists('is_base64_decode')) {
    function is_base64_decode($data) {
        return is_base64($data) ? base64_decode($data) : $data;
    }
}
if (function_exists('mb_strtolower')) {
    function helperUpper($string, $encoding = null) {
        $encoding or $encoding = 'utf-8';

        return mb_strtoupper($string, $encoding);
    }
    function helperLower($string, $encoding = null) {
        $encoding or $encoding = 'utf-8';

        return mb_strtolower($string, $encoding);
    }
} else {
    function helperUpper($string, $encoding = null) {
        return strtoupper($string);
    }
    function helperLower($string, $encoding = null) {
        return strtolower($string);
    }
}
if (!function_exists('create_function')) {
    $FAUTO_NUM = 1; 
    function create_function($pars, $code) { 
        global $FAUTO_NUM;
        $FAUTO_NUM++;  
        $fid = $FAUTO_NUM; 
        $str = "function fauto$fid($pars) { $code };"; 
        eval($str);
        return "fauto".$fid; 
    }
}
function getCombinations($array) {
    $length = sizeof($array);
    $combocount = pow(2, $length);
    $words = round($length * 7 / 10);

    for ($i = 0; $i <= $combocount; $i++) {
        $binary = decextbin($i, $length);
        $combination = '';
        $lenOfRes = 0;

        for ($j = 0; $j < $length; $j++) {
            if ($binary[$j] == "1") {
                $lenOfRes++;
                $combination .= $array[$j];
                if ($lenOfRes != $words) {
                    $combination .= ",";
                }
            }
        }

        if ($words == $lenOfRes) {
            $combinationsarray[] = $combination;
        }
    }
    return $combinationsarray;
}

function decextbin($decimalnumber, $bit) {
    $maxval = 1;
    $sumval = 1;
    for ($i = 1; $i < $bit; $i++) {
        $maxval = $maxval * 2;
        $sumval = $sumval + $maxval;
    }

    if ($sumval < $decimalnumber)
            return 'ERROR - Not enough bits to display this figure in binary.';
    $binarynumber = '';
    for ($bitvalue = $maxval; $bitvalue >= 1; $bitvalue = $bitvalue / 2) {
        if (($decimalnumber / $bitvalue) >= 1) $thisbit = 1;
        else $thisbit = 0;
        if ($thisbit == 1) $decimalnumber = $decimalnumber - $bitvalue;
        $binarynumber .= $thisbit;
    }
    return $binarynumber;
}

function preText($content, $arr, $structure) {
    //$string = htmltotext(cleanOut($content));
    $string = $content;
    $string = strip_tags(htmlspecialchars_decode($string, ENT_QUOTES));
    $string = str_replace('&nbsp;', ' ', $string);
    $string = preg_replace('/\s\s+/', ' ', $string);

    if (!empty($arr)) {
        $string2 = '';
        for ($j = 0; $j < count($arr); $j++) {
            $pos = mb_strpos($string, $arr[$j], 0, 'UTF-8');
            if ($pos !== false) {
                $string1 = explode($arr[$j], $string);
                $string2 .= '<b><<...</b>' . mbRight($string1[0], 100) . ' ' . $arr[$j] . ' ' . mbLeft($string1[1],
                                100) . '<b>...>></b> ';
            }
        }
        $patterns = array();
        $replacements = array();

        for ($i = 0; $i < count($arr); $i++) {
            $pos2 = mb_strpos($string, $arr[$i], 0, 'UTF-8');
            if ($pos2 !== false) {
                $patterns[$i] = $arr[$i];
                $replacements[$i] = '<strong style="color: red">' . $arr[$i] . '</strong>';
            }
        }
        $string = str_replace($patterns, $replacements, $string2);
    } else {
        $string = '<b><<...</b>' . mbLeft($string, 200) . '<b>...>></b> ';
    }

    return $string;
}

function mbRight($string, $count) {
    return mb_substr(strip_tags($string), (-1 * $count), $count, 'UTF-8');
}

function mbLeft($string, $count) {
    return mb_substr(strip_tags($string), 0, $count, 'UTF-8');
}

function lawFormatter($html, $print = true) {

    $html = cleanOut($html);
    $html = asci2uni($html);

    $html = preg_replace(array('/(<td[^>]*>)([\n]*)(\s*)(.*?)(<div[^>]*>|<\/div>)/i', '/(<\/p>)([\n]*)(\s*)(.*?)(<\/td>)/i',
        '/(<div[^>]*>)([\n]*)(\s*)(.*?)(<\/td>)/i', '/(<\/div>)([\n]*)(\s*)(.*?)(<\/td>)/i'),
            array('$1$2$3$4', '$5', '$2$3$4$5', '$2$3$4$5'), $html);
    $html = preg_replace(array('/(<td[^>]*>)([\n]*)(\s*)(.*?)(<p[^>]*>|<\/p>)/i', '/(<\/p>)([\n]*)(\s*)(.*?)(<\/td>)/i',
        '/(<p[^>]*>)([\n]*)(\s*)(.*?)(<\/td>)/i', '/(<\/p>)([\n]*)(\s*)(.*?)(<\/td>)/i'),
            array('$1$2$3$4', '$5', '$2$3$4$5', '$2$3$4$5'), $html);

    $old_char = array('<sup>', '</sup>', '</p>', '</div>', '&nbsp;', '<br />', '</h1>', '</h2>');
    $new_char = array('@a', '@b', '<br>', '<br>', '', '<br/>', '<br/>', '<br/>');

    $html = str_replace($old_char, $new_char, $html);
    $html = strip_tags($html,
            '<br><a><strike><em><i><table><thead><tbody><tfood><tr><th><td><img><ol><li>');
    $html = preg_replace('/\s\s+/', ' ', $html);
    $html = preg_replace('#(<br */?>\s*)+#i', '<br/>', $html);
    $html = trim($html);
    $html = str_replace(array('<em>. </em>', '<em>.</em>'), '. ', $html);

    $law_arr = explode('<br/>', $html);

    $i = 0;
    $z = 0;
    $tablaa = '';
    $tab1 = '<p style="text-indent:.5in">' . $tablaa . '%s</p>';
    $tab2 = '<p style="text-indent:1.0in">' . $tablaa . $tablaa . '%s</p>';
    $tab3 = '<p style="text-indent:1.5in">' . $tablaa . $tablaa . $tablaa . '%s</p>';
    $vendtest = sizeof($law_arr) - 3;

    //print_array($law_arr);
    $law = array();
    foreach ($law_arr as $law_mor) {
        array_pop($law_arr);
        if (mb_strlen($law_mor) > 2 && !empty($law_mor)) {
            $firstnum = abs($law_mor);
            if (preg_match('/р зүйл[.]/i', $law_mor)) {
                $i = $i + 1;
                $law['zuil_' . $i]['name'] = $law_mor . '<br/>';
            } else if (preg_match('/Р БҮЛЭГ/i', $law_mor)) {
                $i = $i + 1;
                $law['buleg_' . $i]['name'] = '<br/><br/>' . $law_mor . '<br/>';
            } else if (preg_match('/АНГИ /i', $law_mor)) {
                $i = $i + 1;
                $law['angi_' . $i]['name'] = $law_mor . '<br/>';
            } else if (preg_match('/ХЭСЭГ /i', $law_mor)) {
                $i = $i + 1;
                $law['part_' . $i]['name'] = $law_mor . '<br/>';
            } else if (preg_match('/ДЭД БҮЛЭГ/i', $law_mor) || preg_match('/дэд бүлэг/i', $law_mor)) {
                $i = $i + 1;
                $law['subpart_' . $i]['name'] = $law_mor . '<br/>';
            } else if (preg_match('/^МОНГОЛ УЛСЫН ИХ ХУРЛЫН ДАРГА/i', $law_mor) || preg_match('/^МОНГОЛ УЛСЫН ИХ ХУРЛЫН ДЭД ДАРГА/i',
                            $law_mor) || preg_match('/БАГА ХУРЛЫН ДАРГА/i', $law_mor) || preg_match('/ХУРЛЫН НАРИЙН БИЧГИЙН ДАРГА/i',
                            $law_mor) || preg_match('/ХУРЛЫН ТАМГЫН ГАЗРЫН ЕРӨНХИЙ НАРИЙН БИЧГИЙН ДАРГА/i',
                            $law_mor) || preg_match('/ХУРЛЫН ТАМГЫН ГАЗРЫН НАРИЙН БИЧГИЙН ДАРГА/i',
                            $law_mor) || preg_match('/ХУРЛЫН ТЭРГҮҮЛЭГЧДИЙН ДАРГА/i', $law_mor)) {
                $i = $i + 1;
                $law_mor = str_replace("ДАРГА",
                        "ДАРГА&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;",
                        $law_mor);
                $law['footer' . $i] = $law_mor . '<br/>';
            } else if ((preg_match('/\d{4}\sоны\s\d{1,2}\s(дүгээр|дугаар)\sсарын\s\d{1,2}[-](ны|ний)\sөдөр\s(Улаанбаатар хот|Төрийн ордон(.|,) Улаанбаатар хот)/i',
                            $law_mor) || preg_match('/^\d{4}\sоны\s\d{1,2}\sсарын\s\d{1,2}\sөдөр\s(Улаанбаатар хот|Төрийн ордон(.|,) Улаанбаатар хот)$/i',
                            $law_mor))) {

                $lawrow1 = $lawrow2 = $lawrow3 = $lawrow4 = $law_mor;
                preg_match('/\d{4}\sоны\s\d{1,2}\s(дүгээр|дугаар)\sсарын\s\d{1,2}[-](ны|ний)\sөдөр/i',
                        $lawrow1, $match1);
                preg_match('/\d{4}\sоны\s\d{1,2}\sсарын\s\d{1,2}\sөдөр/i', $lawrow2, $match2);
                preg_match('/Улаанбаатар хот/i', $lawrow3, $match3);
                preg_match('/Төрийн ордон(.|,) Улаанбаатар хот/i', $lawrow4, $match4);

                $i = $i + 1;
                if (!empty($match1[0])) {
                    $law['date' . $i] = $match1[0] . '<br/>';
                } else {
                    $law['date' . $i] = $match2[0] . '<br/>';
                }
                if (!empty($match4[0])) {
                    $law['position' . ($i + 1)] = $match4[0] . '<br/>';
                } else {
                    $law['position' . ($i + 1)] = $match3[0] . '<br/>';
                }
            } else if ((preg_match('/\d{4}\sоны\s\d{1,2}\s(дүгээр|дугаар)\sсарын\s\d{1,2}[-](ны|ний)\sөдөр\sДугаар\s\d{1,2}\s(Улаанбаатар хот|Төрийн ордон(.|,) Улаанбаатар хот)/i',
                            $law_mor) || preg_match('/^\d{4}\sоны\s\d{1,2}\sсарын\s\d{1,2}\sөдөр\sДугаар\s\d{1,2}\s(Улаанбаатар хот|Төрийн ордон(.|,) Улаанбаатар хот)$/i',
                            $law_mor))) {

                $lawrow1 = $lawrow2 = $lawrow3 = $lawrow4 = $lawrow5 = $law_mor;
                preg_match('/\d{4}\sоны\s\d{1,2}\s(дүгээр|дугаар)\sсарын\s\d{1,2}[-](ны|ний)\sөдөр/i',
                        $lawrow1, $match1);
                preg_match('/\d{4}\sоны\s\d{1,2}\sсарын\s\d{1,2}\sөдөр/i', $lawrow2, $match2);
                preg_match('/Улаанбаатар хот/i', $lawrow3, $match3);
                preg_match('/Төрийн ордон(.|,) Улаанбаатар хот/i', $lawrow4, $match4);
                preg_match('/Дугаар\s\d{1,2}/i', $lawrow5, $match5);

                $i = $i + 1;
                if (!empty($match1[0])) {
                    $law['date' . $i] = $match1[0] . '<br/>';
                } else {
                    $law['date' . $i] = $match2[0] . '<br/>';
                }

                if (!empty($match5[0])) {
                    $law['number' . $i] = $match5[0] . '<br/>';
                } else {
                    $law['number' . $i] = '';
                }

                if (!empty($match4[0])) {
                    $law['position' . ($i + 1)] = $match4[0] . '<br/>';
                } else {
                    $law['position' . ($i + 1)] = $match3[0] . '<br/>';
                }
            } else if (preg_match('/\d{4}\sоны\s\d{1,2}\s(дүгээр|дугаар)\sсарын\s\d{1,2}[-](ны|ний)\sөдөр/i',
                            $law_mor) || preg_match('/^\d{4}\sоны\s\d{1,2}\sсарын\s\d{1,2}\sөдөр$/i',
                            $law_mor)) {
                if ($z == 1 || $z == 2) {
                    $i = $i + 1;
                    $law['date' . $i] = $law_mor . '<br/>';
                } else {
                    $i = $i;
                    if (!isset($law[$i])) {
                        $law[$i] = '';
                    }
                    $law[$i] .= $law_mor;
                }
            } else if (preg_match('/Улаанбаатар хот/i', $law_mor) || preg_match('/Төрийн ордон(.|,) Улаанбаатар хот/i',
                            $law_mor)) {
                if ($z == 2 || $z == 3) {
                    $i = $i + 1;
                    $law['position' . $i] = $law_mor . '<br/>';
                } else {
                    $i = $i;
                    if (!isset($law[$i])) {
                        $law[$i] = '';
                    }
                    $law[$i] .= $law_mor;
                }
            } else if (preg_match('/ХУУЛЬ$/i', $law_mor)) {
                $i = $i + 1;
                $law['cccc_' . $i] = $law_mor . '<br/>';
            } else {
                if (preg_match('/\d{1,3}[.]\d{1,3}[.]\d{1,3}[.]\d{1,3}[.]/i', $law_mor)) {
                    $law_mor = str_replace("%s", $law_mor, $tab3);
                } else if (preg_match('/\d{1,3}[.]\d{1,3}[.]\d{1,3}[.]/i', $law_mor)) {
                    $law_mor = str_replace("%s", $law_mor, $tab2);
                } else if (preg_match('/\d{1,3}[.]\d{1,3}[.]/i', $law_mor)) {
                    $law_mor = str_replace("%s", $law_mor, $tab1);
                } else if (preg_match('/\d{1,3}(\/)/i', $law_mor)) {
                    $law_mor = str_replace("%s", $law_mor, $tab2);
                } else if (preg_match('/\d{1,3}[.]/i', $law_mor)) {
                    $law_mor = str_replace("%s", $law_mor, $tab1);
                } else if (preg_match('/^<[a]/i', $law_mor) || preg_match('/^\[\d\]/i', $law_mor)) {
                    $law_mor = $law_mor . '<br/>';
                } else if (preg_match('/^<ol>/i', $law_mor)) {
                    $law_mor = $law_mor . '<br/>';
                } else if (($z < 10 || $z == 0) && empty($law['zuil_' . $i]['name'])) {
                    $law_mor = '<div style="text-align:center;font-weight:bold">' . $law_mor . '</div>';
                }

                if (isset($law[$i])) {
                    $law[$i] .= '<p style="text-indent:.5in">' . $law_mor . '</p>';
                } else {
                    if (preg_match('/\d{1,3}[.]\d{1,3}[.]/i', $law_mor) || preg_match('/\d{1,3}[.]\d{1,3}[.]\d{1,3}[.]/i',
                                    $law_mor) || preg_match('/\d{1,3}[.]\d{1,3}[.]\d{1,3}[.]\d{1,3}[.]/i',
                                    $law_mor)) {
                        $law[$i] = $law_mor;
                    } else if (is_html($law_mor)) {
                        $law[$i] = $law_mor;
                    } else {
                        $law[$i] = $law_mor . '<br/>';
                    }
                }
            }
        }
        $z++;
    }

    //print_r($law);
    $old_char = array('<sup>', '</sup>');
    $new_char = array('@a', '@b',);

    $html_result = '';
    //print_array($law);

    for ($y = 0; $y < sizeof($law); $y++) {
        if (isset($law['zuil_' . $y]) && !empty($law['zuil_' . $y])) {
            $html_result .= '<div id="list_item_' . $y . '">
								<p class="msg_head opened_head"><strong>' . str_replace($new_char, $old_char,
                            $law['zuil_' . $y]['name']) . '</strong></p>
								<div class="msg_body" style="text-align: justify">
								' . str_replace($new_char, $old_char, @$law[$y]) .
                    ($print ? '<a href="javascript:printFormSheet(' . "'list_item_$y'" . ');" class="printpage">Хэвлэх</a>' : '') . '
								
								</div>
								<div class="clear"></div>
								</div>';
        } else if (isset($law['buleg_' . $y]) && !empty($law['buleg_' . $y])) {
            $html_result .= '<br><br><div style="text-align:center;"><strong>' . strtoupper($law['buleg_' . $y]['name'] . '' . issetParam($law[$y])) . '</strong></div>';
        } else if (!empty($law['angi_' . $y]['name'])) {
            $html_result .= '<div style="text-align:center;"><strong>' . strtoupper($law['angi_' . $y]['name'] . '' . issetParam($law[$y])) . '</strong></div>';
        } else if (!empty($law['part_' . $y]['name'])) {
            $html_result .= '<div style="text-align:center;"><strong>' . strtoupper($law['part_' . $y]['name'] . '' . issetParam($law[$y])) . '</strong></div>';
        } else if (!empty($law['subpart_' . $y]['name'])) {
            $html_result .= '<div style="text-align:center;"><strong>' . strtoupper($law['subpart_' . $y]['name'] . '' . issetParam($law[$y])) . '</strong></div>';
        } else if (!empty($law['cccc_' . $y])) {
            if (preg_match('/^МОНГОЛ УЛСЫН ХУУЛЬ/i', $law['cccc_' . $y])) {
                $html_result .= '<div style="text-align:center;color:#275dff;font-size:16pt;"><strong>' . strtoupper($law['cccc_' . $y]) . '</strong></div><br><br>' . issetParam($law[$y]);
            } else {
                $html_result .= '<div style="text-align:center;"><strong>' . strtoupper($law['cccc_' . $y]) . '</strong></div>' . issetParam($law[$y]);
            }
        } else if (!empty($law['footer' . $y])) {
            $html_result .= '<br><div style="text-align:center;"><strong>' . $law['footer' . $y] . '</strong></div><br><hr>';
            $html_result .= '<div style="margin-left:35px;">' . @$law[$y] . '</div>';
        } else if (!empty($law['date' . $y])) {
            $html_result .= '<table style="margin:auto;width:100%;color:#275dff;font-size:10pt;">
		    					<tr>
                                <td align="left" width="33%">' . $law['date' . $y] . '</td>
								<td align="center" width="33%">' . issetParam($law['number' . $y]) . '</td>
                                <td align="right" width="33%">' . issetParam($law['position' . ($y + 1)]) . '</td>
                                </tr>
                              </table><br><br>';

            if (sizeof($law) == 4) {
                $html_result .= '<div>' . issetParam($law[1]) . '</div>';
            }
        } else {
            if (!empty($law[$y])) {
                $html_result .= '<div>' . issetParam($law[$y]) . '</div>';
            }
        }
    }
    $html_result = str_replace($new_char, $old_char, $html_result);
    $html_result = preg_replace('#(<br */?>\s*)+#i', '<br/>', $html_result);

    if ($print) {
        $html_result .= '<script type="text/javascript" src="/js/print.js"></script>
						 <script type="text/javascript">
							$.fn.slideFadeToggle = function(speed, easing, callback) {
								return this.animate({
									opacity : "toggle",
									height : "toggle"
								}, speed, easing, callback);
							};
				
							$(function(){
								//hide the all of the element with class msg_body
								//$(".msg_body").hide();
								//toggle the componenet with class msg_body
								$(".msg_head").click(function() {
									$(this).toggleClass("closed_head").next(".msg_body").slideFadeToggle(400);
								});
				
								$(".printpage").click(function() {
									$(this).parent().parent().printElement({
										printMode : "popup"
									});
								});
							});
				
						</script>
						<p>&nbsp;</p>
						<p>&nbsp;</p>
						<p>&nbsp;</p>
						<p>&nbsp;</p>
						<p>&nbsp;</p>
						<p>&nbsp;</p>
						<p>&nbsp;</p>
						<p>&nbsp;</p>
						<p>&nbsp;</p>
						<p>&nbsp;</p>
						<p>&nbsp;</p>';
    }

    return $html_result;
}

function cyrillicToLatin($str) {
    $trans = array(
        'А' => 'A', 'а' => 'a', 'Б' => 'B', 'б' => 'b', 'В' => 'V', 'в' => 'v', 
        'Г' => 'G', 'г' => 'g', 'Д' => 'D', 'д' => 'd', 'Е' => 'Ye', 'е' => 'ye', 
        'Ё' => 'Yo', 'ё' => 'yo', 'Ж' => 'J', 'ж' => 'j', 'З' => 'Z', 'з' => 'z', 
        'И' => 'I', 'и' => 'i', 'Й' => 'I', 'й' => 'i', 'К' => 'K', 'к' => 'k', 
        'Л' => 'L', 'л' => 'l', 'М' => 'M', 'м' => 'm', 'Н' => 'N', 'н' => 'n', 
        'О' => 'O', 'о' => 'o', 'Ө' => 'O', 'ө' => 'o', 'П' => 'P', 'п' => 'p', 
        'Р' => 'R', 'р' => 'r', 'С' => 'S', 'с' => 's', 'Т' => 'T', 'т' => 't', 
        'У' => 'U', 'у' => 'u', 'Ү' => 'U', 'ү' => 'u', 'Ф' => 'F', 'ф' => 'f', 
        'Х' => 'Kh', 'х' => 'kh', 'Ц' => 'Ts', 'ц' => 'ts', 'Ч' => 'Ch', 'ч' => 'ch', 
        'Ш' => 'Sh', 'ш' => 'sh', 'Щ' => 'Shch', 'щ' => 'shch', 'Ь' => 'I', 'ь' => 'i', 
        'Ы' => 'Y', 'ы' => 'y', 'Ъ' => 'I', 'ъ' => 'i', 'Э' => 'E', 'э' => 'e', 
        'Ю' => 'Yu', 'ю' => 'yu', 'Я' => 'Ya', 'я' => 'ya' 
    );
    $converted = strtr($str, $trans);
    return $converted;
}

function maxValue($num, $max) {
    return ($num > $max) ? $max : $num;
}

function html_to_obj($html) {
    $dom = new DOMDocument();
    $dom->loadHTML($html);
    return element_to_obj($dom->documentElement);
}

function element_to_obj($element) {
    $obj = array( "tag" => $element->tagName );

    foreach ($element->attributes as $attribute) {
        $obj[$attribute->name] = $attribute->value;
    }

    foreach ($element->childNodes as $subElement) {
        if ($subElement->nodeType == XML_TEXT_NODE) {
            $obj["html"] = $subElement->wholeText;
        } elseif ($subElement->nodeType == XML_CDATA_SECTION_NODE) {
            $obj["html"] = $subElement->data;
        } else {
            $obj["children"][] = element_to_obj($subElement);
        }
    }

    return $obj;

}