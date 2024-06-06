<?php if(!defined('_VALID_PHP')) exit('Direct access to this location is not allowed.');

class Portal_model extends Model {

    private static $gfServiceAddress = GF_SERVICE_ADDRESS;
    private static $getDataViewCommand = 'PL_MDVIEW_004';
    
    public function __construct() {
        parent::__construct();
    }
    
} ?>