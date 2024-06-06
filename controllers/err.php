<?php if(!defined('_VALID_PHP')) exit('Direct access to this location is not allowed.');

class Err extends Controller {

    public function __construct()
    {
        parent::__construct(); 
    }
   
    public function index($isSelf = 0)
    {
        if (!$isSelf) {
            set_status_header(404);
        }
        
        $this->view->title = '404 Not Found';
        $this->view->render('error/index');
    }

}