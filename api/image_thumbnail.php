<?php
define('_VALID_PHP', true);

require '../libs/Security.php';
require '../libs/Str.php';
require '../libs/Input.php';

$src = Input::get('src');

if ($src) {
    
    $path = substr($src, 0, 8);
    
    if ($path == 'process/') {
        $src = 'storage/uploads/'.$src;
    }
    
    $src = str_replace('../', '', $src);
    $image_path = '../'.$src;

    if (file_exists($image_path) && filesize($image_path)) {
        
        $ext = pathinfo($image_path); $ext = strtolower($ext['extension']);
        
        if ($ext == 'jpg' || $ext == 'jpeg' || $ext == 'png' || $ext == 'gif' || $ext == 'bmp') {
            
            require_once('../libs/Image/gumlet/ImageResize.php');

            $image = new \Gumlet\ImageResize($image_path);

            if (isset($_GET['width']) && (int) $_GET['width'] > 0 && !isset($_GET['height'])) {
                $image->resizeToWidth((int) $_GET['width']);
            } elseif (isset($_GET['height']) && (int) $_GET['height'] > 0 && !isset($_GET['width'])) {
                $image->resizeToHeight((int) $_GET['height']);
            } elseif (isset($_GET['height']) && (int) $_GET['height'] > 0 && isset($_GET['width']) && (int) $_GET['width'] > 0) {
                $image->resizeToBestFit((int) $_GET['width'], (int) $_GET['height']);
            }

            header('Cache-Control: private, max-age=2419200, pre-check=2419200');
            header('Pragma: private');
            header('Expires: ' . date(DATE_RFC822, strtotime('30 day')));
            header('Last-Modified: ' . gmdate('D, d M Y H:i:s', filemtime($image_path)) . ' GMT');

            $image->output(IMAGETYPE_PNG, 4);
        }
    }
}