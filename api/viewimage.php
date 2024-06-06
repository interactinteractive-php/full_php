<?php
define('_VALID_PHP', true);

require '../libs/Security.php';
require '../libs/Str.php';
require '../libs/Input.php';

$src = Input::get('src');

if ($src) {
    
    $src = str_replace('../', '', $src);
    
    $image_path = '../storage/uploads/'.$src;

    if (file_exists($image_path)) {
        
        $mimes = array(
            'png' => 'image/png',
            'jpg' => 'image/jpeg',
            'jpeg' => 'image/jpeg',
            'gif' => 'image/gif',
            'bmp' => 'image/bmp'
        );        
        $fileExtension = strtolower(substr($image_path, strrpos($image_path, '.') + 1));
        
        if (isset($mimes[$fileExtension])) {
            header('Cache-Control: private, max-age=2419200, pre-check=2419200');
            header('Pragma: private');
            header('Expires: ' . date(DATE_RFC822, strtotime('30 day')));
            header('Last-Modified: ' . gmdate('D, d M Y H:i:s', filemtime($image_path)) . ' GMT');
            header('Content-Type:' . $mimes[$fileExtension]);
            header('Content-Length: ' . filesize($image_path));
            readfile($image_path);
        }
    }
}