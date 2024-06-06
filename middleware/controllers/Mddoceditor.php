<?php if (!defined('_VALID_PHP')) exit('Direct access to this location is not allowed.');

/**
 * Mdtime Class 
 * 
 * @package     IA PHPframework
 * @subpackage	Middleware
 * @category	Document Editor
 * @author	E.Ochirsugir <ochirsugir@veritech.mn>
 * @link	http://www.interactive.mn/mddoceditor
 */
class Mddoceditor extends Controller {

    const viewPath = 'middleware/views/doceditor/';

    public function __construct() {
        parent::__construct();
    }

    public function index() {

        if (!isset($this->view)) {
            $this->view = new View();
        }
        
        
        $this->view->filename = '';
        if (Input::get('filename')) {
            $this->view->filename = Input::get('filename');
        }
        
        $this->view->folder = '';
        if (Input::get('folder')) {
            $this->view->filename =  $this->view->filename . "&folder=". Input::get('folder');
        }

        $this->view->title = '';
        $this->view->css = array(
            'custom/addon/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.css',
            'custom/addon/plugins/datatables/extensions/FixedColumns/css/dataTables.fixedColumns.min.css',
            'custom/addon/plugins/jquery-easyui/themes/metro/easyui.css',
            'custom/addon/plugins/jstree/dist/themes/default/style.min.css'
        );
        $this->view->js = array(
            'custom/addon/plugins/jquery-easyui/jquery.easyui.min.js',
            'custom/addon/plugins/jquery-easyui/locale/easyui-lang-' . Lang::getCode() . '.js',
            'custom/addon/plugins/datatables/media/js/jquery.dataTables.min.js',
            'custom/addon/plugins/datatables/extensions/FixedColumns/js/dataTables.fixedColumns.min.js',
            'custom/addon/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.js'
        );

        if (!is_ajax_request())
            $this->view->render('header');

        $this->view->render('index', self::viewPath);

        if (!is_ajax_request())
            $this->view->render('footer');
    }
    
    public function fileUpload() {

        function outputJSON($msg, $status = 'error') {
            header('Content-Type: application/json');
            die(json_encode(array(
                'data' => $msg,
                'status' => $status
            )));
        }

        if ($_FILES['uplTheFile']['error'] > 0) {
            outputJSON('An error ocurred when uploading.');
        }
        if ($_FILES['uplTheFile']['size'] > 20000000) {
            outputJSON('File uploaded exceeds maximum upload size.');
        }
        
        $dir = Input::post('dir');
        $fileName = $dir . $_FILES['uplTheFile']['name'];
        
        if (file_exists($fileName)) {
            chmod($fileName, 0755);
            unlink($fileName);
        }

        if (!move_uploaded_file($_FILES['uplTheFile']['tmp_name'], $fileName)) {
            outputJSON('Error uploading file - check destination is writeable.');
        }
        outputJSON($_FILES['uplTheFile']['name'], 'success');
    }
    
    public function vrClientScannerUpload() {

        function outputJSON($msg, $status = 'error') {
            header('Content-Type: application/json');
            die(json_encode(array(
                'data' => $msg,
                'status' => $status
            )));
        }

        if ($_FILES['uplTheFile']['error'] > 0) {
            outputJSON('An error ocurred when uploading.');
        }
        if ($_FILES['uplTheFile']['size'] > 20000000) {
            outputJSON('File uploaded exceeds maximum upload size.');
        }
        
        $fileName = 'storage/uploads/metavalue/photo_temp/original/'.$_FILES['uplTheFile']['name'];
        
        if (file_exists($fileName)) {
            chmod($fileName, 0755);
            unlink($fileName);
        }

        if (!move_uploaded_file($_FILES['uplTheFile']['tmp_name'], $fileName)) {
            outputJSON('Error uploading file - check destination is writeable.');
        }
        outputJSON($_FILES['uplTheFile']['name'], 'success');
    }

}