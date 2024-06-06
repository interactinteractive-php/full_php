<?php if (!defined('_VALID_PHP')) exit('Direct access to this location is not allowed.');

/**
 * Mdconfig Class 
 * 
 * @package     IA PHPframework
 * @subpackage	Middleware
 * @category	Config
 * @author	B.Och-Erdene <ocherdene@interactive.mn>
 * @link	http://www.interactive.mn/PHPframework/Middleware/Mdconfig
 */
class Mdconfig extends Controller {

    private static $viewPath = 'middleware/views/config/';

    public function __construct() {
        parent::__construct();
    }

    public function index() {
        Message::add('s', '', URL . 'mdconfig/main');
    }

    public function main() {
        Auth::handleLogin();
        Uri::isUrlAuth();
        
        $this->view->title = Lang::line('main_config');

        $this->view->css = AssetNew::metaCss();
        $this->view->js = AssetNew::metaOtherJs();
        $this->view->fullUrlJs = AssetNew::amChartJs();
        $this->view->isAjax = is_ajax_request();
        
        if (!$this->view->isAjax) {
            $this->view->render('header');
        }
        
        $this->view->render('main', self::$viewPath);

        if (!$this->view->isAjax) {
            $this->view->render('footer');
        }
    }

    public function configMainDataGrid() {
        Auth::handleLogin();
        $data = $this->model->getConfigMainDataGridModel();
        echo json_encode($data); exit;
    }

    public function configValueGridRender() {
        Auth::handleLogin();
        
        $this->view->isAdd = true;
        $this->view->isEdit = true;
        $this->view->isDelete = true;

        $this->view->params = Input::post('params');

        $this->view->render('loadValue', self::$viewPath);
    }

    public function configValueDataGrid() {
        Auth::handleLogin();
        $data = $this->model->getConfigValueDataGridModel();
        echo json_encode($data, JSON_UNESCAPED_UNICODE); exit;
    }

    public function addConfigValue() {
        Auth::handleLogin();
        
        $this->view->configId = null;
        $this->view->configKeyList = $this->model->getConfigKeyListModel();

        if (Input::postCheck('params') && Input::isEmpty('params') == false) {
            parse_str(Input::post('params'), $qryStrings);
            
            foreach ($qryStrings as $k => $v) {
                if (!empty($v) && $k == 'configId') {
                    $this->view->configId = Input::param($v);
                }
            }
        }
        
        $this->load->model('mddatamodel', 'middleware/models/');
        $this->view->companyDepList = $this->model->getDataMartDvRowsModel('1642568442321876');

        $response = array(
            'Html' => $this->view->renderPrint('addConfigValue', self::$viewPath),
            'Title' => 'Config Value',
            'save_btn' => $this->lang->line('save_btn'),
            'close_btn' => $this->lang->line('close_btn')
        );
        echo json_encode($response); exit;
    }

    public function createConfigValue() {
        Auth::handleLogin();
        $response = $this->model->createConfigValueModel();
        echo json_encode($response); exit;
    }

    public function editConfigValue() {
        Auth::handleLogin();
        
        $this->view->configKeyList = $this->model->getConfigKeyListModel();

        $id = Input::post('id');
        $this->view->row = $this->model->getConfigValueByIdModel($id);
        $this->view->criteriaList = (!is_null($this->view->row['CRITERIA']) ? $this->view->row['CRITERIA'] : null);
        
        $this->load->model('mddatamodel', 'middleware/models/');
        $this->view->companyDepList = $this->model->getDataMartDvRowsModel('1642568442321876');

        $response = array(
            'Html' => $this->view->renderPrint('editConfigValue', self::$viewPath),
            'Title' => 'Edit Config Value',
            'save_btn' => $this->lang->line('save_btn'),
            'close_btn' => $this->lang->line('close_btn')
        );
        echo json_encode($response); exit;
    }

    public function updateConfigValue() {
        Auth::handleLogin();
        $response = $this->model->updateConfigValueModel();
        echo json_encode($response); exit;
    }

    public function deleteConfigValue() {
        Auth::handleLogin();
        $id = Input::post('id');
        $response = $this->model->deleteConfigValueModel($id);
        echo json_encode($response); exit;
    }

    public function renderValueType($configId = '', $configValue = '') {

        if (empty($configId)) {
            return Form::text(
                array(
                    'name' => 'configValue',
                    'id' => 'configValue',
                    'class' => 'form-control',
                    'required' => 'required',
                    'value' => $configValue
                )
            );
        }
        
        $this->load->model('mdconfig', 'middleware/models/');

        $row = $this->model->getConfigByIdModel($configId);
        $value = $row['DEFAULT_VALUE'];
        
        if ($configValue != '') {
            $value = $configValue;
        }

        if ($row) {
            if ($row['VALUE_TYPE'] == 'textarea') {
                return Form::textArea(
                    array(
                        'name' => 'configValue',
                        'id' => 'configValue',
                        'class' => 'form-control',
                        'value' => $value
                    )
                );
            } elseif ($row['VALUE_TYPE'] == 'string') {
                return Form::text(
                    array(
                        'name' => 'configValue',
                        'id' => 'configValue',
                        'class' => 'form-control',
                        'required' => 'required',
                        'value' => $value
                    )
                );
            } elseif ($row['VALUE_TYPE'] == 'long' || $row['VALUE_TYPE'] == 'number' || $row['VALUE_TYPE'] == 'integer') {
                return Form::text(
                    array(
                        'name' => 'configValue',
                        'id' => 'configValue',
                        'class' => 'form-control longInit',
                        'required' => 'required',
                        'value' => $value
                    )
                );
            } elseif ($row['VALUE_TYPE'] == 'bigdecimal') {
                return Form::text(
                    array(
                        'name' => 'configValue',
                        'id' => 'configValue',
                        'class' => 'form-control bigdecimalInit',
                        'required' => 'required',
                        'value' => $value
                    )
                );
            } elseif ($row['VALUE_TYPE'] == 'image') {
                return Form::file(
                    array(
                        'name' => 'configValue',
                        'id' => 'configValue',
                        'data-valid-extension' => 'jpeg,jpg,png',
                        'class' => 'form-control fileInit',
                        'required' => 'required',
                        'value' => $value
                    )
                ) . ($value ? '<a href="'.URL.$value.'" target="_blank" style="position: absolute;right: -3px;top: 8px;"><i class="fa fa-image text-success" title="Зураг харах"></i></a><input type="hidden" name="configValueOld" value="'.$value.'">' : '');
            } elseif ($row['VALUE_TYPE'] == 'date') {
                return Form::text(
                    array(
                        'name' => 'configValue',
                        'id' => 'configValue',
                        'class' => 'form-control dateInit',
                        'required' => 'required',
                        'value' => $value
                    )
                );
            } elseif ($row['VALUE_TYPE'] == 'datetime') {
                return Form::text(
                    array(
                        'name' => 'configValue',
                        'id' => 'configValue',
                        'class' => 'form-control datetimeInit',
                        'required' => 'required',
                        'value' => $value
                    )
                );
            } elseif ($row['VALUE_TYPE'] == 'boolean') {
                return '<div class="radio-list">
                            <label class="radio-inline">
                                <input type="radio" name="configValue" required="required" value="1" ' . getChecked($value, '1') . '> ' . Lang::line('yes_btn') . '
                            </label>
                            <label class="radio-inline">
                                <input type="radio" name="configValue" value="0" ' . getChecked($value, '0') . '> ' . Lang::line('no_btn') . '
                            </label>
                        </div>';
            } elseif ($row['VALUE_TYPE'] == 'password') {
                return Form::password(
                    array(
                        'name' => 'configValue',
                        'id' => 'configValue',
                        'class' => 'form-control',
                        'required' => 'required'
                    )
                );
            } elseif ($row['VALUE_TYPE'] == 'password_hash') {
                return Form::password(
                    array(
                        'name' => 'configValue',
                        'id' => 'configValue',
                        'class' => 'form-control',
                        'required' => 'required'
                    )
                ) . Form::hidden(array('name' => 'configPasswordHash', 'value' => '1'));
            } else {
                return Form::text(
                    array(
                        'name' => 'configValue',
                        'id' => 'configValue',
                        'class' => 'form-control',
                        'value' => $value,
                        'required' => 'required'
                    )
                );
            }
        } else {
            return Form::text(
                array(
                    'name' => 'configValue',
                    'id' => 'configValue',
                    'class' => 'form-control',
                    'value' => $value,
                    'required' => 'required'
                )
            );
        }
    }

    public function printValueType() {
        $configId = Input::post('configId');
        echo self::renderValueType($configId); exit;
    }
    
    public function getConfigValue() {
        $response = $this->model->getConfigValueModel();
        echo json_encode($response, JSON_UNESCAPED_UNICODE);
    }
    
    public function getConfigValueFromCache() {
        $code = Input::post('key');
        echo json_encode(Config::getFromCache($code), JSON_UNESCAPED_UNICODE);
    }
    
    public function phptodb() {
        $response = $this->model->phpToDbModel();
        
        echo '<html>
            <head>
                <meta charset="utf-8" />
                <title>PHP Config</title>
                <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate" />
                <meta http-equiv="Pragma" content="no-cache" />
                <meta http-equiv="Expires" content="0" />
            </head>
            <body>'.json_encode($response, JSON_UNESCAPED_UNICODE).'</body>
        </html>'; exit;
    }
    
    public function valueEncryption() {

        $data = $_POST['valueEncryption'];
        $iv   = 'V6!)fTn7]n^eBrfy'; 
        $key  = 'PjEc~Q^D;4:*5v&D';

        $encodedEncryptedData = base64_encode(openssl_encrypt($data, 'aes-128-cbc', $key, OPENSSL_RAW_DATA, $iv));
        $encodedIV            = base64_encode($iv);
        $encryptedPayload     = $encodedEncryptedData.':'.$encodedIV;

        echo $encryptedPayload;
    }

}
