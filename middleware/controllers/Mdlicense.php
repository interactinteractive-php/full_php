<?php if (!defined('_VALID_PHP')) exit('Direct access to this location is not allowed.');

/**
 * Mdlicense Class 
 * 
 * @package     IA PHPframework
 * @subpackage	Middleware
 * @category	License
 * @author	B.Munkh-Erdene <ocherdene@veritech.mn>
 * @link	http://www.interactive.mn/PHPframework/Middleware/Mdlicense
 */
class Mdlicense extends Controller {

    private static $viewPath = 'middleware/views/license/';

    public function __construct() {
        parent::__construct();
        Auth::handleLogin();
    }

    public function importLicense() {
        $response = $this->model->importLicenseModel();
        echo json_encode($response); exit;
    }
}
