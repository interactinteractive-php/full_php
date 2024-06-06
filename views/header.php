<?php
if (defined('CONFIG_TOP_MENU') && CONFIG_TOP_MENU) {
    require 'views/header/top-menu.php';
} else {
    require 'views/header/default.php';
}