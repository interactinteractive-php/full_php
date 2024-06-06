<?php if(!defined('_VALID_PHP')) exit('Direct access to this location is not allowed.');

class Index extends Controller {

    public function __construct() {
        parent::__construct();
	Auth::handleLogin();
    }
    
    public function index()
    {
        header("location: " . URL . Config::getFromCache('CONFIG_START_LINK'));
    }
    
}