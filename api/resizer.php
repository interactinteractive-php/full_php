<?php
if (isset($_GET['file']) && !empty($_GET['file'])) {
	
    $image_path = '../'.$_GET['file'];
    
    if (file_exists($image_path)) {
        
        include_once '../libs/Image/resizer/pjImage.component.php';
        $Image = new pjImage();

        $size = @getimagesize($image_path);
        $src_width = $size[0];
        $src_height = $size[1];

        if (isset($_GET['action']) && $_GET['action'] == 'resize') {
            
            if (isset($_GET['width']) && (int) $_GET['width'] > 0 && isset($_GET['height']) && (int) $_GET['height'] > 0) {
                
                $Image->loadImage($image_path);
                $resp = $Image->isConvertPossible();
                
                if ($resp['status'] === true) {
                    $Image->resize($_GET['width'], $_GET['height']);
                }
                
            } elseif (isset($_GET['width']) && (int) $_GET['width'] > 0) {

                $ratio = $src_height / $src_width;
                $newWidth = $_GET['width'];
                $newHeight = $newWidth * $ratio;

                $Image->loadImage($image_path);
                $resp = $Image->isConvertPossible();

                if ($resp['status'] === true) {
                    $Image->resize($newWidth, $newHeight);
                }
            }
            
        } elseif (isset($_GET['action']) && $_GET['action'] == 'crop') {

            if (isset($_GET['width']) && (int) $_GET['width'] > 0 & (int) $_GET['width'] < $src_width && isset($_GET['height']) && (int) $_GET['height'] > 0 && (int) $_GET['height'] < $src_height) {
                if (isset($_GET['crop_pos']) && !empty($_GET['crop_pos'])) {
                    $crop_position = $_GET['crop_pos'];
                    $x = 0;
                    $y = 0;
                    $w = (int) $_GET['width'];
                    $h  = (int) $_GET['height'];
                    switch ($crop_position) {
                        case 'center':
                            $x = round($src_width / 2) - round((int) $_GET['width'] / 2);
                            $y = round($src_height / 2) - round((int) $_GET['height'] / 2);
                        ;
                        break;

                        case 'top':
                            $x = round($src_width / 2) - round((int) $_GET['width'] / 2);
                        ;
                        break;
                        case 'bottom':
                            $x = round($src_width / 2) - round((int) $_GET['width'] / 2);
                            $y = $src_height - (int) $_GET['height'];
                        ;
                        break;
                        case 'left':
                            $y = round($src_height / 2) - round((int) $_GET['height'] / 2);
                        ;
                        break;
                        case 'right':
                            $x = $src_width - (int) $_GET['width'];
                            $y = round($src_height / 2) - round((int) $_GET['height'] / 2);
                        ;
                        break;
                    }
                    $Image->loadImage($image_path);
                    $Image->crop($x, $y, $w, $h, $w, $h);
                }
            }
        }
        if (isset($_GET['watermark']) && !empty($_GET['watermark'])) {
            $watermarkPosition = isset($_GET['watermark_pos']) ? $_GET['watermark_pos'] : 'cc';

            if (isset($_GET['color'])) {
                $color_arr = explode(",", $_GET['color']);
                $valid_color = true;
                if (count($color_arr) == 3) {
                    if ((int)$color_arr[0] < 0 || (int)$color_arr[0] > 255 || (int)$color_arr[1] < 0 || (int)$color_arr[1] > 255 || (int)$color_arr[2] < 0 || (int)$color_arr[2] > 255) {
                        $valid_color = false;
                    }
                } else {
                    $valid_color = false;
                }
                if ($valid_color == true) {
                    $Image->setColor($color_arr);
                }
            }

            $Image->setFontSize(18)->setFont('fonts/arialbd.ttf');
            $Image->setWatermark($_GET['watermark'], $watermarkPosition);
        }
        
        $quality = 100;
        if (isset($_GET['quality']) && (int) $_GET['quality'] > 0 && (int) $_GET['quality'] <= 100) {
            $quality = $_GET['quality'];
        }

        $Image->output($quality);		
    }
}