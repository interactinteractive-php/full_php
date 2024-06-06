<?php

if (!defined('_VALID_PHP'))
    exit('Direct access to this location is not allowed.');

if (class_exists('Mddoceditor_Model') != true) {

    class Mddoceditor_Model extends Model {

        public function __construct() {
            parent::__construct();
        }
    }

}
