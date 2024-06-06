<?php
define('_VALID_PHP', true);

$realpath = str_replace('api\captcha.php', '', realpath(__FILE__));
$realpath = str_replace('api/captcha.php', '', $realpath);

define('BASEPATH', $realpath);

ini_set('display_errors', '0');
ini_set('display_startup_errors', '0');
error_reporting(E_ALL & ~E_STRICT & ~E_DEPRECATED);

require '../helper/functions.php';
require '../libs/Uri.php';
require '../libs/Session.php';
require '../libs/Captcha/Easy/Captcha.php';

ob_start('remove_utf8_bom');
require '../config/config.php';
ob_end_flush();

Session::init(false);

$captcha = new Captcha();
$captcha->chars_number = 4;
$captcha->font_size = 11;
$captcha->tt_font = BASEPATH . 'libs/Captcha/Easy/verdana.ttf';
$captcha->border_color = '228, 228, 228';
$captcha->show_image(75, 34);
