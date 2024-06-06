<?php if(!defined('_VALID_PHP')) exit('Direct access to this location is not allowed.');

class Portal extends Controller {
  
    public function __construct() {
        parent::__construct();
    }
    
    public function index() 
    {
        Message::add('s', '', URL . Config::getFromCache('CONFIG_START_LINK'));
    }
}