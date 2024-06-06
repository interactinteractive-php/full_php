<?php
define('_VALID_PHP', true);

require_once '../helper/functions.php';
require_once '../libs/Security.php';
require_once '../libs/Str.php';
require_once '../libs/Input.php';

require_once '../libs/Barcode/lib2/BarcodeGenerator.php';
require_once '../libs/Barcode/lib2/BarcodeGeneratorSVG.php';

$value = Input::get('v');

$generator = new Picqer\Barcode\BarcodeGeneratorSVG();

header('Content-type: image/svg+xml');
echo $generator->getBarcode($value, $generator::TYPE_CODE_128, 3, 150);