<?php
if (isset($_GET['file']) && !empty($_GET['file'])) {
	
    $image_path = '../'.$_GET['file'];

    if (file_exists($image_path)) {

        require_once('../libs/Image/image-magician/php_image_magician.php');

        if (isset($_GET['action']) && $_GET['action'] == 'resize' && isset($_GET['width']) && (int) $_GET['width'] > 0) {

            $magicianObj = new imageLib($image_path);

            $magicianObj->resizeImage($_GET['width'], 200, 'landscape');
            $magicianObj->displayImage('png');
        }
    }
}