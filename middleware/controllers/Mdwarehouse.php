<?php if (!defined('_VALID_PHP')) exit('Direct access to this location is not allowed.');

/**
 * Mdwarehouse Class 
 * 
 * @package     IA PHPframework
 * @subpackage	Middleware
 * @category	Warehouse
 * @author	B.Och-Erdene <ocherdene@interactive.mn>, Ts.Ulaankhuu <ulaankhuu@veritech.mn>
 * @link	http://www.interactive.mn/PHPframework/Middleware/Warehouse
 */
class Mdwarehouse extends Controller {

    private static $viewPath = "middleware/views/warehouse/";

    public function __construct() {
        parent::__construct();
        Auth::handleLogin();
    }

    public function structure() {

        $this->view->title = "Агуулахын бүтэц зохион байгуулалт";

        $this->view->css = array(
            'custom/addon/plugins/jquery-ui/jquery-ui.min.css',
            'custom/addon/plugins/marker/hotspot-custom/css/hotspot-custom.css',
        );
        $this->view->js = array(
            'custom/addon/plugins/jquery-easyui/jquery.easyui.min.js',
            'custom/addon/plugins/jquery-easyui/locale/easyui-lang-' . Lang::getCode() . '.js'
        );

        $this->view->getActiveWareHouseList = $this->model->getActiveWareHouseListModel();
        $this->view->render('header');
        $this->view->render('structure/index', self::$viewPath);
        $this->view->render('footer');
    }

    public function whLocation() {
        $locationId = Input::post('LOCATION_ID');
        $warehouseId = Input::post('WAREHOUSE_ID');
        echo json_encode($this->model->getActiveWHLocationListModel($locationId, $warehouseId));
    }

    public function whLocationImage() {
        $response = array(
            'Image' => $this->model->getActiveWHLocationImageModel(),
            'Coordinate' => $this->model->getLocationPositionModel(),
            'Parent' => $this->model->getParentLocationIdModel()
        );
        echo json_encode($response);
    }

    public function locationInfoDialog() {
        $this->view->LOCATION_ID = Input::post('LOCATION_ID');
        $this->view->WAREHOUSE_ID = Input::post('WAREHOUSE_ID');
        $response = array(
            'Html' => $this->view->renderPrint('structure/location_info_dialog', self::$viewPath),
            'Title' => 'Агуулахын бүртгэл',
            'close_btn' => Lang::line('close_btn')
        );
        echo json_encode($response);
    }

    public function structureDialog() {

        $this->view->LOCATION_ID = Input::post('LOCATION_ID');
        $this->view->ISWAREHOUSE = Input::post('ISWAREHOUSE');
        $this->view->QUERYTYPE = Input::post('QUERYTYPE');
        $this->view->COORDINATE_X = Input::post('COORDINATE_X');
        $this->view->COORDINATE_Y = Input::post('COORDINATE_Y');
        $this->view->OLD_COORDINATE_X = Input::post('OLD_COORDINATE_X');
        $this->view->OLD_COORDINATE_Y = Input::post('OLD_COORDINATE_Y');
        $this->view->MARKER_NAME = Input::post('MARKER_NAME');
        $this->view->MARKER_ID = Input::post('MARKER_ID');

        $this->view->getOneWareHouse = $this->model->getOneWareHouseModel();
        $this->view->location = $this->model->getOneLocationModel();
        $_POST['WAREHOUSE_ID'] = $this->view->getOneWareHouse['WAREHOUSE_ID'];

        $this->view->getActiveWareHouseList = $this->model->getActiveWareHouseListModel();
        $this->view->getActiveWHLocationList = $this->model->getActiveWHLocationListModel($this->view->location, $_POST['WAREHOUSE_ID']);
        $this->view->getMarkerList = $this->model->getMarkerListModel();

        $response = array(
            'Html' => $this->view->renderPrint('structure/structure_dialog', self::$viewPath),
            'Title' => 'Агуулахын бүртгэл',
            'save_btn' => Lang::line('save_btn'),
            'save_close_btn' => 'Хадгалах/Хаах',
            'close_btn' => Lang::line('close_btn')
        );
        echo json_encode($response);
    }

    public function queryLocationPosition() {
        if (Input::post('QUERYTYPE') == 'add') {
            $result = $this->model->createLocationPositionModel();
        } elseif (Input::post('QUERYTYPE') == 'edit') {
            $result = $this->model->updateLocationPositionModel();
        }

        if ($result['status'] == 'success') {
            $response = array(
                'status' => 'success',
                'message' => Lang::line('msg_save_success')
            );
        } else {
            $response = array(
                'status' => 'error',
                'message' => $result['message']
            );
        }
        echo json_encode($response);
    }

    public function removeLocationPosition() {

        $result = $this->model->removeLocationPositionModel();

        if ($result['status'] == 'success') {
            $response = array(
                'status' => 'success',
                'message' => Lang::line('msg_save_success')
            );
        } elseif ($result['status'] == 'empty') {
            $response = array(
                'status' => 'empty',
                'message' => $result['message']
            );
        } else {
            $response = array(
                'status' => 'error',
                'message' => $result['message']
            );
        }
        echo json_encode($response);
    }

    public function getLastLocationId() {
        echo json_encode($this->model->getLastLocationIdModel());
    }

    function warehouseExtensions() {
        $this->view->css = array(
            'custom/addon/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.css',
            'custom/addon/plugins/datatables/extensions/FixedColumns/css/dataTables.fixedColumns.min.css',
            'custom/addon/plugins/jquery-easyui/themes/metro/easyui.css',
            'custom/addon/plugins/jstree/dist/themes/default/style.min.css',
            'global/css/fonts/oswald/oswald.css'
        );
        $this->view->js = array(
            'custom/addon/plugins/jquery-easyui/jquery.easyui.min.js',
            'custom/addon/plugins/jquery-easyui/locale/easyui-lang-' . Lang::getCode() . '.js',
            'custom/addon/plugins/datatables/media/js/jquery.dataTables.min.js',
            'custom/addon/plugins/datatables/extensions/FixedColumns/js/dataTables.fixedColumns.min.js',
            'custom/addon/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.js'
        );
        $this->view->fullUrlJs = array(
            'middleware/assets/js/mdgl.js',
            'middleware/assets/js/mdaccount.js'
        );
    }

}