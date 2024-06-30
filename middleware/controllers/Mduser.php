<?php if (!defined('_VALID_PHP')) exit('Direct access to this location is not allowed.');

/**
 * Mduser Class 
 * 
 * @package     IA PHPframework
 * @subpackage	Middleware
 * @category	User
 * @author	B.Och-Erdene <ocherdene@interactive.mn>
 * @link	http://www.interactive.mn/PHPframework/Middleware/Mduser
 */
class Mduser extends Controller {

    private static $viewPath = 'middleware/views/user/';

    public function __construct() {
        parent::__construct();
        Auth::handleLogin();
    }

    public function index() {
        Message::add('s', '', URL . 'mduser/userList');
    }

    public function userList() {

        $this->view->title = 'Хэрэглэгчийн жагсаалт';

        $this->view->css = array(
            'custom/addon/plugins/jquery-easyui/themes/metro/easyui.css',
            'custom/addon/plugins/jstree/dist/themes/default/style.min.css'
        );
        $this->view->js = array(
            'custom/addon/plugins/jquery-easyui/jquery.easyui.min.js',
            'custom/addon/plugins/jquery-easyui/locale/easyui-lang-' . $this->lang->getCode() . '.js'
        );

        $this->view->isAdd = true;
        $this->view->isEdit = true;
        $this->view->isDelete = true;

        $this->view->render('header');
        $this->view->render('index', self::$viewPath);
        $this->view->render('footer');
    }

    public function chooseUserV3() {

        $this->view->chooseMode = Input::post('chooseMode');
        $this->view->appendId = Input::post('appendId');
        $this->view->selectedElementId = Input::post('selectedElementId');
        $this->view->objectId = Input::post('objectId');

        $response = array(
            'html' => $this->view->renderPrint('userGridV3', self::$viewPath),
            'title' => $this->lang->line('udep_user_choose'),
            'choose_btn' => $this->lang->line('choose_btn'),
            'close_btn' => $this->lang->line('close_btn'),
            'addbasket_btn' => $this->lang->line('addbasket_btn')
        );
        echo json_encode($response); exit;
    }

    public function userDataGrid() {
        $result = $this->model->userDataGridModel();
        echo json_encode($result); exit;
    }

    public function umUserDataGrid() {
        $result = $this->model->umUserDataGridModel();
        echo json_encode($result); exit;
    }
    
    public function getUserRowByCrtSerialNumber($crtSerialNumber) {
        $this->load->model('mduser', 'middleware/models/');
        $row = $this->model->getUserRowByCrtSerialNumberModel($crtSerialNumber);
        return $row;
    }

    // <editor-fold defaultstate="collapsed" desc="User Meta Permission">

    public function userPermission() {
        $this->load->model('mduser', 'middleware/models/');
        $this->view->title = 'Хэрэглэгчийн жагсаалт';

        $this->view->css = array(
            'custom/addon/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.css',
            'custom/addon/plugins/jquery-easyui/themes/metro/easyui.css',
            'custom/addon/plugins/jstree/dist/themes/default/style.min.css'
        );
        $this->view->js = array(
            'custom/addon/plugins/jquery-easyui/jquery.easyui.min.js',
            'custom/addon/plugins/jquery-easyui/locale/easyui-lang-' . Lang::getCode() . '.js',
            'custom/addon/plugins/phpjs/phpjs.min.js'
        );
        $this->view->menuCode = 'ERP_MENU';
        $this->view->defaultType = '200101010000025';
        $childData = array();
        
        $this->load->model('mdmetadata', 'middleware/models/');
        $getMetaId = $this->model->getMetaDataIdByCodeModel($this->view->menuCode);
        
        $this->load->model('mduser', 'middleware/models/');
        
        if ($getMetaId) {
            $childData = $this->model->getChildMenuMetasModel($getMetaId);
        }
        
        $this->view->menuTreeList = self::menuTreeView($childData, $getMetaId);

        if (!is_ajax_request())
            $this->view->render('header');

        $this->view->render('menu_permission/index', self::$viewPath);

        if (!is_ajax_request())
            $this->view->render('footer');
    }

    public function menuTreeView($data, $sourceId) {
        $view = '';
        
        foreach ($data as $row) {
            $hiddenValue = '<input type=\'hidden\' name=\'srcMetaDataId[]\' value=\'' . $row['SRC_META_DATA_ID'] . '\'>'
                    . '<input type=\'hidden\' name=\'srcMetaDataCode[]\' value=\'' . $row['SRC_META_DATA_CODE'] . '\'>'
                    . '<input type=\'hidden\' name=\'srcMetaDataName[]\' value=\'' . $row['SRC_META_DATA_NAME'] . '\'>'
                    . '<input type=\'hidden\' name=\'trgMetaDataId[]\' value=\'' . $row['TRG_META_DATA_ID'] . '\'>';

            $view .= '{';
            if ($row) {
                $view .= '"icon": "fa fa-folder text-orange-400",';
                $view .= '"state": {"opened": false},';
                $view .= '"text": "<span class=\'' . $row['SRC_META_DATA_ID'] . '\'>' . $row['TRG_META_DATA_NAME'] . '</span>' . $hiddenValue . '",';
            }
        
            if ($row['TRG_META_DATA_ID']) {
                $childData = $this->model->getChildMenuMetasModel($row['TRG_META_DATA_ID']);
                if (!empty($childData)) {
                    $view .= 'children: [';
                    $view .= self::menuTreeView($childData, $row['TRG_META_DATA_ID']);
                    $view .= '],';
                }
            }
            $view .= '},';
        }
        
        return $view;
    }

    public function userPermissionByMeta() {
        $this->view->metaDataId = Input::numeric('metaDataId');
        $metaData = (new Mdmetadata())->getMetaData($this->view->metaDataId);
        $this->view->metaType = self::getPermissionMetaTypeById($metaData['META_TYPE_ID']);
        
        $response = array(
            'Html' => $this->view->renderPrint('menu_permission/user_permission', self::$viewPath),
        );
        echo json_encode($response); exit;
    }

    public function userPermissionDataGridOnMain($id) {
        $this->load->model('mduser', 'middleware/models/');
        $data = $this->model->userPermissionDataGridOnMainModel($id);
        echo json_encode($data);
    }

    public function getMetaList() {
        $this->load->model('mduser', 'middleware/models/');
        $data = $this->model->getMetaListModel();
        echo json_encode($data);
    }

    public function loadController($name, $controllerPath = 'controllers/') {
        parent::loadController($name, $controllerPath);
    }

    public function viewCriteria() {
        $this->load->model('mduser', 'middleware/models/');
        if (!isset($this->view)) {
            $this->view = new View();
        }
        $this->view->fieldCriteria = Input::post('fieldCriteria');
        $this->view->recordCriteria = Input::post('recordCriteria');
        $response = array(
            'Html' => $this->view->renderPrint('menu_permission/view_criteria', self::$viewPath),
            'Title' => 'View criteria',
            'close_btn' => Lang::line('close_btn')
        );
        echo json_encode($response);
    }
    public function addMultiPermission(){
        
        $this->view->roleData = $this->model->userRoleDataModel();
        $this->view->groupData = $this->model->userGroupDataModel();
        $this->view->actionData = $this->model->permissionActionDataModel();
        
        $metaDataIds = Input::post('metaDataIds');
        $ids = $codes = '';
        
        $this->load->model('mdmetadata', 'middleware/models/');
        
        foreach ($metaDataIds as $value) {
            $metadata = $this->model->getMetaDataModel($value);
            $codes .= $metadata['META_DATA_CODE'] . ' ,';
            $ids .= $value . ' ,';
        }
        
        $this->view->metaDataIds = rtrim($ids, ',');
        $this->view->metaDataCodes = rtrim($codes, ',');

        $response = array(
            'Html' => $this->view->renderPrint('menu_permission/addMultiPermission', self::$viewPath),
            'Title' => Lang::line('META_00112'),
            'save_btn' => Lang::line('save_btn'),
            'close_btn' => Lang::line('close_btn')
        );
        echo json_encode($response); exit;
    }

    public function addPermission() {

        $this->view->metaDataId = Input::numeric('metaDataId');
        $this->view->roleData = $this->model->userRoleDataModel();
        $this->view->groupData = $this->model->userGroupDataModel();
        $this->view->actionData = $this->model->permissionActionDataModel();
        
        $getMetaData = (new Mdmetadata())->getMetaData($this->view->metaDataId);
        if ($getMetaData) {
            $this->view->metaData = $getMetaData;
        }

        $response = array(
            'Html' => $this->view->renderPrint('menu_permission/addPermission', self::$viewPath),
            'Title' => Lang::line('META_00112'),
            'save_btn' => Lang::line('save_btn'),
            'close_btn' => Lang::line('close_btn')
        );
        echo json_encode($response); exit;
    }

    public function editPermission() {

        $permissionId = Input::post('permissionId');
        
        $this->view->row = $this->model->getPermissionByIdModel($permissionId);
        $this->view->metaDataId = Input::numeric('metaDataId');
        $this->view->roleData = $this->model->userRoleDataModel();
        $this->view->groupData = $this->model->userGroupDataModel();
        $this->view->actionData = $this->model->permissionActionDataModel();
        $getMetaData = (new Mdmetadata())->getMetaData($this->view->metaDataId);
        
        if ($getMetaData) {
            $this->view->metaData = $getMetaData;
        }

        $response = array(
            'Html' => $this->view->renderPrint('menu_permission/editPermission', self::$viewPath),
            'Title' => Lang::line('META_00112'),
            'save_btn' => Lang::line('save_btn'),
            'close_btn' => Lang::line('close_btn')
        );
        echo json_encode($response); exit;
    }

    public function createPermission() {
        $result = $this->model->createPermissionModel();
        echo json_encode($result); exit;
    }

    public function updatePermission() {
        $result = $this->model->updatePermissionModel();
        echo json_encode($result); exit;
    }

    public function deletePermission() {
        $result = $this->model->deletePermissionModel();
        if ($result) {
            $response = array(
                'status' => 'success',
                'result' => 'Aмжилттай устгагдлаа'
            );
        } else {
            $response = array(
                'status' => 'success',
                'result' => 'Aмжилтгүй боллоо'
            );
        }
        echo json_encode($response); exit;
    }
    
    public function getPermissionMetaTypeList() {
        $this->load->model('mduser', 'middleware/models/');
        $rows = $this->model->getPermissionMetaTypeListModel();
        return $rows;
    }
    
    public function getPermissionMetaTypeById($id) {
        $this->load->model('mduser', 'middleware/models/');
        $rows = $this->model->getPermissionMetaTypeByIdModel($id);
        return $rows;
    }

    // </editor-fold>
    
    public function cloudLogin($cloudId) {
        
        $dataList = $this->model->getGlobalUserListByCloudIdModel($cloudId);
        
        if ($dataList) {
            
            includeLib('Compress/Compression');
            
            if (count($dataList) == 1) { 
                
                $row = $dataList[0];
                
                $authData = 'u='.$row['USERNAME'].'&p='.$row['PASSWORD'].'&d='.Date::currentDate('Y-m-d').'&t='.Date::currentDate('H:i:s');
                
                if ($row['AFTER_LOGIN_URL']) {
                    $authData .= '&l='.Str::urlCharAndReplace($row['AFTER_LOGIN_URL']);
                }
                
                $data = Compression::gzdeflate($authData);
                $data = Str::urlCharReplace($data);
            
                header('location: '.$row['DOMAIN_NAME'].'/login/cloud/'.$data);
                
            } else {
                echo 'Multi user'; 
            }
            
        } else {
            Message::add('s', '', 'back');
        }
    }
    
    public function loginByUserKeyId() {
        
        if (defined('CONFIG_USER_KEY_LOGIN_URL') && CONFIG_USER_KEY_LOGIN_URL) {
            
            includeLib('Compress/Compression');
                
            $authData = 'uk='.Ue::sessionUserKeyId().'&d='.Date::currentDate('Y-m-d').'&t='.Date::currentDate('H:i:s');

            if (Input::isEmptyGet('redirectUrl') == false) {
                $authData .= '&l='.Str::urlCharAndReplace(Input::get('redirectUrl'));
            }

            $data = Compression::gzdeflate($authData);
            $data = Str::urlCharReplace($data);

            header('location: '.CONFIG_USER_KEY_LOGIN_URL.'/login/cloud/'.$data);

        } else {
            Message::add('s', '', 'back');
        }
    }
    
    public function toQuickMenu() {
        $response = $this->model->toQuickMenuModel();
        echo json_encode($response); 
    }
    
    public function renderQuickMenu($systemId, $userId = null) {
        $this->load->model('mduser', 'middleware/models/');
        $this->view->menuList = $this->model->getQuickMenuListModel($systemId, $userId);
        
        return $this->view->renderPrint('quickmenu/menu', self::$viewPath);
    }
    
    public function renderQuickMenuItem($systemId, $userId, $metaDataId) {
        $this->load->model('mduser', 'middleware/models/');
        $menuRow = $this->model->getQuickMenuItemModel($systemId, $userId, $metaDataId);
        
        $attr = Mduser::renderQuickMenuAnchor($menuRow);
        
        $tagAttr = array(
            'href' => $attr['linkHref'], 
            'data-qmid' => $attr['linkId'], 
            'data-qm-hotkey' => $menuRow['HOT_KEY'], 
            'onclick' => $attr['linkOnClick'], 
            'class' => 'dropdown-item'
        );
        $rightHotKey = '';
        
        if ($menuRow['HOT_KEY']) {
            $rightHotKey = ' <span class="badge badge-pill bg-grey-300 ml-auto" title="Hotkey">'.$menuRow['HOT_KEY'].'</span>';
        }
        
        return html_tag('a', $tagAttr, '<i class="icon-arrow-right5"></i> '.Lang::line($menuRow['MENU_NAME']).$rightHotKey);
    }
    
    public static function renderQuickMenuAnchor($row) {
        
        if ($row['META_TYPE'] == 'dataview') {
            
            if ($row['SYSTEM_ID']) {
                $array['linkHref'] = 'javascript:;';
                $array['linkOnClick'] = "appMultiTab({metaDataId: '".$row['META_DATA_ID']."', title: '".Lang::line($row['MENU_NAME'])."', type: 'dataview'}, this);";
                $array['linkId'] = $row['META_DATA_ID'];
            } else {
                $array['linkHref'] = 'mdobject/dataview/'.$row['META_DATA_ID'];
                $array['linkOnClick'] = '';
                $array['linkId'] = $row['META_DATA_ID'];
            }
            
        } elseif ($row['META_TYPE'] == 'process') {
            
            $array['linkHref'] = 'javascript:;';
            $array['linkOnClick'] = "callWebServiceByMeta('" . $row['META_DATA_ID'] . "', true, '', false, {callerType: 'quickmenu', isMenu: true});";
            $array['linkId'] = $row['META_DATA_ID'];
            
        } elseif ($row['META_TYPE'] == 'urlcode') {
            
            $urlCode = str_replace('/', '', $row['URL_CODE']);
            
            $array['linkHref'] = 'javascript:;';
            $array['linkOnClick'] = "appMultiTab({weburl: '".$row['URL_CODE']."', metaDataId: '".$urlCode."', title: 'Журнал бичилт', type: 'selfurl'}, this);";
            $array['linkId'] = $urlCode;
        }
        
        return $array;
    }
    
    public function iconQuickMenu($metaDataId) {
        $this->load->model('mduser', 'middleware/models/');
        
        $isSaved = $this->model->isSavedQuickMenuItemModel($metaDataId);
        
        if ($isSaved) {
            return '<i class="fa fa-star"></i>';
        }
        return '<i class="fa fa-star-o"></i>';
    }

    public function iconHelpMenu($metaDataId) {
        $this->load->model('mduser', 'middleware/models/');

        return '<i class="icon-question7 font-size-10"></i>';
    }
    
    public function iconQuickMenuByUrlCode($urlCode) {
        $this->load->model('mduser', 'middleware/models/');
        
        $isSaved = $this->model->isSavedQuickMenuUrlCodeModel($urlCode);
        
        if ($isSaved) {
            return '<i class="fa fa-star"></i>';
        }
        return '<i class="fa fa-star-o"></i>';
    }
    
    public function anchorIconQuickMenu($metaDataId, $isShow = false, $isTab = '') {
        if ($isShow) {
            return '<button type="button" class="btn btn-secondary btn-sm btn-circle default bp-btn-quickmenu" title="Quick menu" onclick="toQuickMenu(\''.$metaDataId.'\', \'process\', this);" tabindex="-1">'.$this->iconQuickMenu($metaDataId).'</button>';
        }
        return '';
    }
    
    public static function linkAnchorIconQuickMenu($urlCode, $menuName) {
        return '<button type="button" class="btn btn-secondary btn-sm btn-circle default bp-btn-quickmenu" title="Quick menu" onclick="toQuickMenu(\''.$urlCode.'\', \'urlcode\', this, \''.$menuName.'\');" tabindex="-1">'.(new self())->iconQuickMenuByUrlCode($urlCode).'</button>';
    }
    
    public function anchorFieldClean($metaDataId, $isShow = false, $isTab = '') {
        if ($isShow) {
            
            $this->load->model('mduser', 'middleware/models/');
            
            $showFields = $this->model->getCleanFieldsConfigModel($metaDataId);
            
            if ($showFields) {
                
                $sessionUserId = Ue::sessionUserId();
                
                if ($sessionUserId) {

                    $cleanField = $this->db->GetRow("
                        SELECT 
                            CLEAN_PARAM_PATH, 
                            NOCLEAN_PARAM_PATH 
                        FROM CUSTOMER_BP_CLEAN 
                        WHERE META_DATA_ID = ".$this->db->Param(0)." 
                            AND USER_ID = ".$this->db->Param(1), 
                        array($metaDataId, $sessionUserId)
                    );

                    if ($cleanField) {
                        return '<button type="button" class="btn btn-secondary btn-sm btn-circle default bp-btn-fieldclean" title="'.Lang::line('META_00006').'" onclick="bpCleanFieldUserConfig(\''.$metaDataId.'\', this);" tabindex="-1" data-clean-fields="'.$cleanField['CLEAN_PARAM_PATH'].'" data-ignore-clean-fields="'.$cleanField['NOCLEAN_PARAM_PATH'].'"><i class="fa fa-check-circle-o"></i></button>';
                    }
                }

                return '<button type="button" class="btn btn-secondary btn-sm btn-circle default bp-btn-fieldclean" title="'.Lang::line('META_00006').'" onclick="bpCleanFieldUserConfig(\''.$metaDataId.'\', this);" tabindex="-1"><i class="fa fa-check-circle-o"></i></button>';
            }
        }
        return '';
    }
    
    public function anchorIconProcessTemplate($metaDataId, $isShow = false, $isTab = '') {
        
        if ($isShow) {
            
            $isSavedTmpl = $this->model->isBpSavedDataTmplModel($metaDataId);
            $iconName = $isSavedTmpl ? 'icon-stack3 text-success' : 'icon-stack2';
            
            return '<div class="bp-btn-datatemplate inline-block" data-selected-id="'.Input::numeric('dataTemplateId').'">
                <a href="javascript:;" class="btn btn-sm default dropdown-toggle" data-toggle="dropdown" title="Утгын загвар сонгох"><i class="'.$iconName.'"></i></a>

                <div class="dropdown-menu dropdown-content dropdown-menu-right" style="display:none">
                    <div class="dropdown-content-header font-weight-bold pt5 pb5">
                        Утгын загвар
                    </div>
                    <div class="dropdown-content-header">
                        <div class="input-group">
                            <span class="input-group-prepend">
                                <span class="input-group-text">
                                    <input type="checkbox" name="isOwnBpValueTemplate" class="notuniform" value="1" title="Зөвхөн өөрт харагдах бол чектэй байна" checked>
                                </span>
                            </span>
                            <input type="text" name="bpValueTemplateName" class="form-control chat-user-status-input rounded-0" placeholder="Шинэ загварын нэр">
                            <span class="input-group-append">
                                <button type="button" class="btn btn-light rounded-right ml0" title="Загвар үүсгэх" style="border-top-left-radius:0!important; border-bottom-left-radius:0!important;" onclick="bpDataTemplateSave(this);"><i class="icon-checkmark-circle2"></i></button>
                            </span>
                        </div>
                    </div>
                    <div class="dropdown-content-body dropdown-scrollable">
                        <ul class="media-list"></ul>
                    </div>
                </div>
            </div>';
        }
        
        return '';
    }
    
    public function cleanFieldUserConfig() {
        
        $this->view->metaDataId = Input::post('processId');
        
        $data = $this->model->bpCleanFieldUserConfigModel($this->view->metaDataId);
        $this->view->paths = $data['paths'];
        $this->view->userConfig = $data['savePath'];
        
        $response = array(
            'html' => $this->view->renderPrint('addon/user/cleanField', 'middleware/views/webservice/'),
            'title' => 'Талбар цэвэрлэх тохиргоо', 
            'save_btn' => $this->lang->line('save_btn'), 
            'close_btn' => $this->lang->line('close_btn')
        );
        echo json_encode($response); exit;
    }
    
    public function cleanFieldUserConfigSave() {
        $response = $this->model->cleanFieldUserConfigSaveModel();
        echo json_encode($response); exit;
    }
    
    public function getSessionInfo() {
        
        $key = Input::post('key');
        $value = Mdmetadata::setDefaultValue($key);
        
        echo $value; exit;
    }
    
    public function userKeys() {
        includeLib('Compress/Compression');
        
        $this->view->userKeys = $this->model->getUserKeysModel();
        $this->view->render('userKeys', self::$viewPath);
    }
    
    public function changeKey($encryptedUserKeyId) {
        
        $this->model->changeKeyModel($encryptedUserKeyId);
        
        $appMenu = Controller::loadController('Appmenu');
        $redirect_url = $appMenu->redirectModule(URL . 'appmenu');
        
        Message::add('s', '', $redirect_url);
    }
    
    public function getHash($pass) {
        echo html_tag('h1', array(), Hash::createMD5reverse($pass)); exit;
    }
    
    public function saveUserTouchMode() {
        $response = $this->model->saveUserTouchModeModel();
        echo json_encode($response); exit;
    }
    
    public function dataAccessPassword() {
        $response = $this->model->dataAccessPasswordModel();
        jsonResponse($response);
    }
    
    public function getBpValueTemplate() {
        $response = $this->model->getBpValueTemplateModel();
        jsonResponse($response);
    }
    
    public function updateBpValueTemplate() {
        $response = $this->model->updateBpValueTemplateModel();
        jsonResponse($response);
    }
    
    public function deleteBpValueTemplate() {
        $response = $this->model->deleteBpValueTemplateModel();
        jsonResponse($response);
    }
    
    public static function processToolsButton($methodId, $isEditMode, $runMode, $tabStart, $isLayout = false) {
        
        if ($tabStart) {
            $style = 'position: absolute;right: 7px;top: 0;margin-top:-46px;';
        } elseif (!$isLayout) {
            $style = 'position: absolute;right: 7px;top: 0;margin-top:-2px;margin-bottom:-30px;';
        } else {
            $style = 'margin-top: -5px;';
        }
        
        $user = new Mduser();
        
        $buttons = '<div class="dv-right-tools-btn" style="'.$style.'">';
            $buttons .= $user->anchorIconQuickMenu($methodId, !$isEditMode, $tabStart);
            $buttons .= $user->anchorFieldClean($methodId, (!$isEditMode && $runMode), $tabStart);
            $buttons .= $user->anchorIconProcessTemplate($methodId, !$isEditMode, $tabStart);
        $buttons .= '</div>';
        
        return $buttons;
    }
    
    public function getFiscalPeriod() {
        
        $langCode = Lang::getCode();
        $cache = phpFastCache();
            
        $dataYear = $cache->get('getFiscalPeriodYearList_' . $langCode);
        $allPeriodCacheData = $cache->get('getFiscalPeriodAllList_' . $langCode);
        $html = ''; 
        
        if ($dataYear == null || $allPeriodCacheData == null) {

            $allPeriodCacheData = $this->db->GetAll(
                "SELECT
                    ID,  
                    IS_CURRENT,
                    (FNC_TRANSLATE('$langCode', TRANSLATION_VALUE, 'PERIOD_NAME', PERIOD_NAME)) AS PERIOD_NAME,
                    PERIOD_CODE,
                    IS_CLOSED,
                    TYPE_ID,
                    PARENT_ID
                FROM FIN_FISCAL_PERIOD 
                WHERE TYPE_ID <> 5 
                    AND (IS_HIDE IS NULL OR IS_HIDE = 0) 
                ORDER BY START_DATE, END_DATE ASC");
            
            $cache->set('getFiscalPeriodAllList_' . $langCode, $allPeriodCacheData, 86400);

            $dataYear = $this->db->GetAll(
                "SELECT
                    AA.ID, 
                    (FNC_TRANSLATE('$langCode', TRANSLATION_VALUE, 'PERIOD_NAME', AA.PERIOD_NAME)) AS PERIOD_NAME, 
                    AA.IS_CURRENT
                FROM FIN_FISCAL_PERIOD AA
                WHERE AA.TYPE_ID = 4 
                    AND (AA.IS_HIDE IS NULL OR AA.IS_HIDE = 0) 
                ORDER BY AA.START_DATE, AA.END_DATE ASC");
            $cache->set('getFiscalPeriodYearList_' . $langCode, $dataYear, 86400);
        }

        if ($dataYear) {

            $periodYearId = Input::param(Session::get(SESSION_PREFIX.'periodYearId'));

            $groupResult = Info::groupByArrayDoubleKey($allPeriodCacheData, 'PARENT_ID', 'ID');

            Info::$periodListGroupByParent = $groupResult['PARENT_ID'];
            Info::$periodListGroupById = $groupResult['ID'];

            foreach ($dataYear as $year) {

                $currentYearPeriod = '';
                $currentPeriodYearIcon = '<i class="fa fa-angle-right"></i> ';
                $currentPeriodYearActiveIcon = '';

                if ($year['ID'] == $periodYearId) {
                    $currentPeriodYearIcon = '<i class="fa fa-angle-right"></i> ';
                    $currentPeriodYearActiveIcon = ' <i class="fa fa-check-circle"></i>';
                    $currentYearPeriod = ' current';
                }

                $html .= '<li data-id="'.$year['ID'].'" class="root-period nav-item '.$currentYearPeriod.'">
                    <a href="javascript:;" class="dropdown-item">
                    '.$currentPeriodYearIcon.$year['PERIOD_NAME'].$currentPeriodYearActiveIcon.'
                    </a>
                </li>';
                $html .= Info::childFiscalPeriodNewV2($year['ID'], 0, $year['ID'], $periodYearId);
            }
        }
        
        echo $html; exit;
    }
    
    public function checkPinCode() {
        $response = $this->model->checkPinCodeModel();
        jsonResponse($response);
    }
    
    public function pinCodeReset() {
        $response = $this->model->pinCodeResetModel();
        jsonResponse($response);
    }
    
    public function changePinCode() {
        $response = $this->model->changePinCodeModel();
        jsonResponse($response);
    }
    
    public function getUserInfoByContentId() {
        $this->view->row = $this->model->getUserInfoByContentIdModel();
        $this->view->render('ecm_content/userInfo', self::$viewPath);
    }
    
    public static function validatePassword($password, $userName = '') {
        
        if ($password == '') {
            return array('status' => 'error', 'message' => Lang::line('user_minlenght_password'));
        }
        
        $passwordMinLength = (int) Config::getFromCacheDefault('passwordMinLength', null, 8);
        
        if ($passwordMinLength > mb_strlen($password)) {
            
            return array('status' => 'error', 'message' => Lang::line('user_minlenght_password'));
        }
        
        $passwordBlacklistedWords = Config::getFromCache('passwordBlacklistedWords');
        
        if ($passwordBlacklistedWords) {
            
            $userName = trim($userName);
            
            $sessionUsername = $userName ? $userName : Session::get(SESSION_PREFIX . 'username');
            $passwordBlacklistedWords .= ','.$sessionUsername;
            $passwordBlacklistedWords = explode(',', $passwordBlacklistedWords);
            
            foreach ($passwordBlacklistedWords as $passwordBlacklistedWord) {
                
                if ($passwordBlacklistedWord && stripos($password, $passwordBlacklistedWord) !== false) {
                    
                    return array('status' => 'error', 'message' => Lang::line('Нийтлэг үгүүд ашиглах боломжгүй!'));
                }
            }
        }
        
        return array('status' => 'success');
    }
    
    public function validatePasswordFromExp() {
        
        $password = Input::post('password');
        $username = Input::post('username');
        
        $response = self::validatePassword($password, $username);
        convJson($response);
    }
    
    public static function systemModeActions() {
        
        $result = null;
        
        if (Config::getFromCache('IS_TESTCASE_MODE')) {
            
            $result = '<div class="form-check form-check-switchery form-check-inline form-check-right">
                <label class="form-check-label">
                    Testcase mode:
                    <input type="checkbox" class="form-check-input-switchery-warning notuniform" id="isHdrTestCaseMode"'.(Session::get(SESSION_PREFIX . 'testCaseMode') ? ' checked="checked"' : '').'>
                </label>
            </div>';
        }
        
        return $result;
    }
    
    public function setTestCaseMode() {
        if (Input::numeric('isChecked') == 1) {
            Session::set(SESSION_PREFIX . 'testCaseMode', 1);
        } else {
            Session::set(SESSION_PREFIX . 'testCaseMode', 0);
        }
    }
    
    public function otpForm() {
        
        $this->view->email = Session::get(SESSION_PREFIX . 'email');
        $this->view->phoneNumber = Session::get(SESSION_PREFIX . 'mobile');
        
        if (!$this->view->email && !$this->view->phoneNumber) {
            $response = array(
                'status' => 'error', 
                'message' => 'Нэг удаагийн нууц үг хүлээн авах сувгийн мэдээлэл олдсонгүй. Та өөрийн утасны дугаар болон и-мейл хаягаа шалгуулна уу!'
            );
        } else {
            $response = array(
                'status' => 'success', 
                'html' => $this->view->renderPrint('otp', self::$viewPath)
            );
        }
        
        convJson($response);
    }
    
    public function sendOtp() {
        $this->load->model('login');
        $response = $this->model->sendVerificationCodeModel();
        
        if ($response['status'] == 'success') {
            
            Session::set(SESSION_PREFIX . 'otp', $response['otp']);
            
            if ($response['sendType'] == 'email') {
                $message = 'Баталгаажуулах код таны и-мейл хаяг руу амжилттай илгээгдлээ.';
            } elseif ($response['sendType'] == 'phoneNumber') {
                $message = 'Баталгаажуулах код таны утас руу амжилттай илгээгдлээ.';
            }

            unset($response['otp']);
            unset($response['sendType']);
            
            $response['message'] = $message;
        }
        
        convJson($response);
    }
    
    public function startupMetaScriptFooter() {
        
        if (!isset($this->view)) {
            $this->view = new View();
        }        
        $this->load->model('mduser', 'middleware/models/');
        
        $this->view->getStartupMeta = $this->model->startupMetaModel();
        $this->view->getStartupMetaAllUser = $this->model->startupMetaAllUserModel();
        
        if ($this->view->getStartupMeta || $this->view->getStartupMetaAllUser) {
            
            $script = $this->view->renderPrint('startup/scriptFooter', self::$viewPath);
            
            if (issetParam($this->view->getStartupMeta['IS_ALWAYS_ACTIVE']) != '1') {
                Session::set(SESSION_PREFIX.'startupMeta', '1');
            }
            
            return $script;
        } 
        
        return null;
    }
    
    public function getCloudUserDbConnections() {
        $response = $this->model->getCloudUserDbConnectionsModel();
        convJson($response);
    }
    
    public function connectCloudUserDb() {
        $response = $this->model->connectCloudUserDbModel();
        convJson($response);
    }
    
}
