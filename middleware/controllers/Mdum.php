<?php

if (!defined('_VALID_PHP')) exit('Direct access to this location is not allowed.');

/**
 * Mdum Class 
 * 
 * @package     IA PHPframework
 * @subpackage	Middleware
 * @category	User
 * @author	D.Janchiv <janchiv@interactive.mn>
 * @link	http://www.interactive.mn/PHPframework/Middleware/Mdum
 */
class Mdum extends Controller {

    private static $viewPath = 'middleware/views/um/';

    public function __construct() {
        parent::__construct();
        Auth::handleLogin();
    }

    // <editor-fold defaultstate="collapsed" desc="Дүрд хэрэглэгч, permission тохируулах">
    public function role() {

        $this->view->css = array(
            'custom/addon/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.css',
            'custom/addon/plugins/jquery-easyui/themes/metro/easyui.css',
            'custom/addon/plugins/jstree/dist/themes/default/style.min.css'
        );
        $this->view->js = array(
            'custom/addon/plugins/jquery-easyui/jquery.easyui.min.js',
            'custom/addon/plugins/jquery-easyui/locale/easyui-lang-' . $this->lang->getCode() . '.js',
            'custom/addon/plugins/phpjs/phpjs.min.js'
        );

        $this->view->fullUrlJs = array(
            'middleware/assets/js/mdgl.js',
            'middleware/assets/js/mdmetadata.js',
            'middleware/assets/js/mdbp.js',
            'middleware/assets/js/mdexpression.js'
        );

        $this->view->menuCode = 'ERP_MENU';
        $this->view->defaultType = '200101010000025';
        $this->view->metaDataId = '144304940363722';
        $this->view->roleOrUser = '';
        $this->view->uniqId = getUID();
        $this->view->isAjax = is_ajax_request();

        if (!is_null(Input::get('roleId'))) {
            $this->view->roleId = Input::get('roleId');
        } elseif (!is_null(Input::post('roleId'))) {
            $this->view->roleId = Input::post('roleId');
        } elseif (!is_null(Input::get('userId'))) {
            $this->view->userId = Input::get('userId');
        } elseif (!is_null(Input::post('userId'))) {
            $this->view->userId = Input::post('userId');
        } elseif (Input::post('selectedRow')) {
            $selectedRow = Arr::decode(Input::post('selectedRow'));
            if (issetParam($selectedRow['roleid'])) {
                $this->view->roleId = $selectedRow['roleid'];
            } elseif (issetParam($selectedRow['dataRow']['roleid'])) {
                $this->view->roleId = $selectedRow['dataRow']['roleid'];
            }
        }

        $this->load->model('mdum', 'middleware/models/');
        $viewPath = '';

        if (isset($this->view->roleId)) {
            
            $this->view->title = 'Дүрийн тохиргоо';
            $viewPath = 'user/index';
            
        } elseif (isset($this->view->userId)) {
            
            $this->view->title = 'Хэрэглэгчийн тохиргоо';
            $viewPath = 'user/index';
        }

        if (!$this->view->isAjax) $this->view->render('header');
        
        if ($viewPath) {
            $this->view->render($viewPath, self::$viewPath);
        } else {
            echo html_tag('div', array('class' => 'alert alert-info'), 'Параметр дутуу байна!');
        }

        if (!$this->view->isAjax) $this->view->render('footer');
    }

    public function roleUser() {
        $this->view->uniqId = getUID();

        if (!is_null(Input::get('roleId'))) {
            $this->view->roleId = Input::get('roleId');
        } else if (!is_null(Input::post('roleId'))) {
            $this->view->roleId = Input::post('roleId');
        }

        if (isset($this->view->roleId)) {
            $this->view->render('role/roleUser', self::$viewPath);
        }
    }

    public function dataPermission() {
        
        $this->view->uniqId = getUID();
        $this->view->selectedRow = Input::post('selectedRow');

        if (isset($this->view->selectedRow)) {
            
            $this->view->userPermissionTables = $this->model->getUserPermissionTablesModel($this->view->selectedRow['id']);
            $this->view->umAction = $this->model->getUmActionModel();

            if (isset($this->view->selectedRow['userid'])) {
                $this->view->userId = $this->view->selectedRow['userid'];
            } elseif (isset($this->view->selectedRow['roleid'])) {
                $this->view->roleId = $this->view->selectedRow['roleid'];
            }

            $response = array(
                'Title' => $this->view->selectedRow['metadataname'],
                'width' => '100%',
                'close_btn' => $this->lang->line('close_btn'),
                'save_btn' => $this->lang->line('save_btn'),
                'html' => $this->view->renderPrint('dataPermission', self::$viewPath . 'user/'),
            );
            echo json_encode($response); exit;
        }
    }

    public function dataPermissionToUser() {
        $this->view->uniqId = getUID();
        $this->view->dbStructureId = Input::post('dbStructureId');
        $this->view->recordId = Input::post('recordId');

        $this->view->render('dataPermissionToUser', self::$viewPath);
    }

    public function datePermissionCriteriaRender() {
        
        $this->view->uniqId = getUID();
        $this->view->selectedRow = Input::post('selectedRow');

        if (isset($this->view->selectedRow)) {
            
            $this->view->umMetaPermissionList = $this->model->getUmMetaPermissionModel($this->view->selectedRow);

            $this->load->model('mdobject', 'middleware/models/');
            $this->view->dvGridHeaderList = $this->model->getDataViewGridHeaderModel($this->view->selectedRow['tabledvid']);
            
            if (!is_null($this->view->dvGridHeaderList)) {
                foreach ($this->view->dvGridHeaderList as $key => $value) {
                    $this->view->dvGridHeaderList[$key]['LABEL_NAME'] = Lang::line($value['LABEL_NAME']);
                }
            }

            $viewType = 'dataPermissionCriteria';
            
            if (Input::postCheck('isSingleAddrow')) {
                $viewType = 'dataPermissionCriteriaSingle';
            }

            $response = array(
                'Title' => $this->view->selectedRow['metadataname'],
                'width' => '600',
                'close_btn' => $this->lang->line('close_btn'),
                'save_btn' => $this->lang->line('save_btn'),
                'html' => $this->view->renderPrint($viewType, self::$viewPath . 'user/'),
            );
            echo json_encode($response); exit;
        }
    }

    public function saveDataPermissionCriteria() {
        $result = $this->model->saveDataPermissionCriteriaModel();
        echo json_encode($result);exit;
    }

    public function getDataPermissionModal() {
        $this->view->umAction = $this->model->getUmActionModel();
        $this->view->render('dataPermissionModal', self::$viewPath);
    }

    public function getRoleUsers() {
        $data = $this->model->getRoleUsersModel(Input::post('roleId', null), Input::post('userId', null));
        jsonResponse($data);
    }

    public function getUsers() {
        $result = $this->model->getUserListModel(Input::post('roleId'), Input::post('q'));
        header('Content-Type: application/json');
        echo json_encode($result); exit;
    }

    public function saveRoleUser() {
        $response = $this->model->saveRoleUserModel(Input::post('roleId'), Input::post('userId'));
        header('Content-Type: application/json');
        echo json_encode($response);exit;
    }
    
    public function saveRoleUserMulti() {
        $response = $this->model->saveRoleUserMultiModel();
        header('Content-Type: application/json');
        echo json_encode($response);exit;
    }

    public function changeIsActive() {
        $response = $this->model->changeIsActiveModel(Input::post('roleUserId'), Input::post('isActive'));
        header('Content-Type: application/json');
        echo json_encode($response);exit;
    }

    public function saveRolePermission() {
        $response = $this->model->createMetaPermissionModel();
        echo json_encode($response); exit;
    }

    public function deleteRolePermission() {
        
        $result = $this->model->deleteMetaPermissionModel();

        if (!$result) {
            $response = array(
                'status' => 'error',
                'message' => Lang::line('msg_save_error')
            );
        } else {
            $response = array(
                'status' => 'success',
                'message' => Lang::line('msg_save_success')
            );
        }
        echo json_encode($response); exit;
    }

    public function getData() {
        
        if (Input::postCheck('tmpData')) {
            
            $tmpData = Input::post('tmpData');
            
            foreach (array_keys($tmpData) as $key) {
                
                unset($tmpData[$key]['type']);
                unset($tmpData[$key]['li_attr']);
                unset($tmpData[$key]['data']);
                unset($tmpData[$key]['a_attr']);
                unset($tmpData[$key]['parent']);
                
                $tmpData[$key]['state']['selected'] = $tmpData[$key]['state']['selected'] === 'true' ? true : false;
                $tmpData[$key]['state']['loaded'] = $tmpData[$key]['state']['loaded'] === 'true' ? true : false;
                $tmpData[$key]['state']['disabled'] = $tmpData[$key]['state']['disabled'] === 'true' ? true : false;
                $tmpData[$key]['state']['opened'] = $tmpData[$key]['state']['opened'] === 'true' ? true : false;
            }

            jsonResponse($tmpData);
        }
        
        $this->load->model('mdum', 'middleware/models/');
        
        $metaDataId = Input::post('metaDataId');
        $roleId = Input::post('roleId');
        $userId = Input::post('userId');
        $parent = Input::post('parent');
        $parentNode = Input::post('parentNode');
        $isSaved = Input::post('isSaved');
        $isDisabled = Input::post('isDisabled');
        $isDenied = Input::post('isDenied', 0);
        $isSelected = Input::post('isSelected', 0);
        $haveCriteria = Input::post('haveCriteria', 0);
        $searchText = Input::post('searchText', NULL);

        $splitedMetaDataId = explode('-', $metaDataId);
        $splitedParentNode = explode('-', $parentNode);

        $metaDataId = $splitedMetaDataId[0];
        $parentNode = $splitedMetaDataId[0];

        if (!is_null($metaDataId)) {
            
            $metaTypeId = $this->model->getMetaTypeIdModel($metaDataId);
            $metaTypeIdParent = '';
            
            if (!empty($parentNode)) $metaTypeIdParent = $this->model->getMetaTypeIdModel($parentNode);

            $response = $this->getChildData($isSaved, $isDisabled, $metaTypeId, $metaDataId, $roleId, $userId, $parent, $metaTypeIdParent, $isDenied, $isSelected, $haveCriteria, $searchText);
            jsonResponse($response);
            
        } else {
            jsonResponse($response);
        }
    }

    public function getChildData($isSaved, $isDisabled, $metaTypeId, $metaDataId, $roleId, $userId, $parent, $metaTypeIdParent, $isDenied, $isSelected, $haveCriteria, $searchText) {
        if ($metaTypeIdParent != Mdmetadata::$businessProcessMetaTypeId || $metaTypeId != Mdmetadata::$metaGroupMetaTypeId) {
            $childData = $this->model->getChildTreeMetasModel($isSaved, $metaDataId, $roleId, $userId, $metaTypeId, $isDenied, $searchText);
            $response = array();

            foreach ($childData as $data) {
                $disabled = false;
                if (is_null($isSaved)) {
                    $disabled = is_null($data['permissionid']) ? false : true;
                } else {
                    $disabled = $isDisabled == 1 ? true : false;
                }
                
                $icon = str_replace(
                    array('fa fa-folder icon-state-warning', 'fa fa-list icon-state-warning', 'fa fa-edit icon-state-warning'), 
                    array('icon-folder2 text-orange-300', 'icon-list icon-state-warning', 'icon-pencil7 icon-state-warning'), 
                    $data['icon']
                );

                $response[] = array(
                    'text' => $this->lang->line($data['trgmetadataname'])
                    . ($parent !== 'ok' && is_null($data['permissionid']) ? Form::hidden(array('name' => 'META_DATA_ID[]', 'value' => $data['trgmetadataid'])) : '')
                    . (($haveCriteria == 1 && $data['metatypeid'] == '200101010000016') ? (' <span class="fa fa-search criteriaListShower" metaId="' . $data['trgmetadataid'] . '"></span>') : ''),
                    'id' => $data['trgmetadataid'] . '-' . $metaDataId,
                    'icon' => $icon,
                    'state' => array(
                        'selected' => $isSelected == 1 ? (is_null($data['permissionid']) ? false : true) : false,
                        "loaded" => true,
                        "disabled" => $disabled,
                        "opened" => is_null($searchText) ? false : true,
                        'PERMISSION_ID' => is_null($data['permissionid']) ? getUID() . '_' : $data['permissionid'],
                        'META_TYPE_ID' => $data['metatypeid']
                    ),
                    'children' => $data['childrencount'] == 0 ? false : true
                );
            }
            return $response;
        } else return array();
    }

    public function saveUserDataPermission() {
        $response = $this->model->saveUserDataPermissionModel();
        echo json_encode($response); exit;
    }

    public function saveUserDataPermissionToUser() {
        $response = $this->model->saveUserDataPermissionToUserModel();
        echo json_encode($response); exit;
    }

    public function removeUserDataPermission() {
        $response = $this->model->removeUserDataPermissionModel($_POST['data']);
        echo json_encode($response); exit;
    }

    public function enableUserDataPermission() {
        $response = $this->model->enableUserDataPermissionModel($_POST['data']);
        echo json_encode($response); exit;
    }

    public function removeUserDataPermissionFin() {
        $response = $this->model->removeUserDataPermissionFinModel($_POST['data']);
        echo json_encode($response); exit;
    }

    public function savePermissionCriteria() {
        $this->load->model('mdpermission', 'middleware/models/');

        parse_str(Input::post('permissionCreateData'), $permissionCreateData);
        $defaultCriteriaParam = $permissionCreateData['criteriaId'];
        $permission_id = $permissionCreateData['permissionId'];
        if (strpos($permission_id, '_') !== false) $permission_id = str_replace('_', '', $permission_id);

        foreach ($defaultCriteriaParam as $key => $defParamVal) {
            $permCheck = $this->model->checkPermCriteriaModel($permission_id, $defParamVal);

            if ($permissionCreateData['checkedCriteria'][$key] === '1' && $permCheck === false) {
                $paramCriteria = array(
                    'ID' => getUID(),
                    'PERMISSION_ID' => $permission_id,
                    'CRITERIA_ID' => $defParamVal,
                    'BATCH_NUMBER' => Input::param($permissionCreateData['batchNumber'][$key])
                );
                $result = $this->model->umMetaPermCriteriaCreateModel($paramCriteria);
            } elseif ($permCheck !== false && $permissionCreateData['checkedCriteria'][$key] === '0') {
                $this->model->umMetaPermCriteriaDeleteModel($permCheck['ID']);
            } elseif ($permCheck !== false && $permissionCreateData['checkedCriteria'][$key] === '1') {
                $paramCriteria = array(
                    'BATCH_NUMBER' => Input::param($permissionCreateData['batchNumber'][$key])
                );
                $this->model->checkPermUpdateModel($paramCriteria, $permCheck['ID']);
            }
        }

        $this->load->model('mdmetadata', 'middleware/models/');
        $getMetaDataId = $this->model->getMetaDataByCodeModel(Mdpermission::$processMetaCode);

        $this->load->model('mdwebservice', 'middleware/models/');
        $row = $this->model->getMethodIdByMetaDataModel($getMetaDataId['META_DATA_ID']);

        $param['permissionId'] = $permission_id;
        $resultProcess = $this->ws->caller($row['SERVICE_LANGUAGE_CODE'], $row['WS_URL'], $row['META_DATA_CODE'], 'return', $param, 'serialize');

        if ($result) {
            $response = array(
                'status' => 'success',
                'message' => Lang::line('msg_save_success')
            );
            if ($resultProcess['status'] !== 'success') {
                $response = array(
                    'status' => 'error',
                    'message' => 'Pocess ажиллахад алдаа гарлаа! ' . isset($resultProcess['text']) ? $resultProcess['text'] : ''
                );
            }
        } else {
            $response = array(
                'status' => 'error',
                'message' => isset($resultProcess['text']) ? $resultProcess['text'] : Lang::line('msg_save_error')
            );
        }

        header('Content-Type: application/json');
        echo json_encode($response); exit;
    }

    public function getRoleAndPermission() {
        $this->load->model('mdum', 'middleware/models/');
        $metaDataId = Input::get('metaDataId');
        $userId = Input::get('userId');
        $roleId = Input::get('roleId');
        $parent = Input::get('parent');
        $parentNode = Input::get('parentNode');
        $roleOrUser = Input::get('roleOrUser');
        $isSavedRole = Input::get('isSavedRole');
        $isSaved = Input::get('isSaved');
        $isDisabled = Input::get('isDisabled');
        $isDenied = Input::get('isDenied', 0);
        $isSelected = Input::get('isSelected', 0);
        $haveCriteria = Input::get('haveCriteria', 0);
        $searchText = Input::get('searchText', 0);

        if (!is_null($userId)) {
            $metaTypeIdParent = '';
            if (!empty($parentNode)) {
                $metaTypeIdParent = $this->model->getMetaTypeIdModel($parentNode);
            }

            $response = array();
            if (is_null($metaDataId)) {
                $roles = $this->model->getRolesByUserIdModel($isSavedRole, $userId);
                foreach ($roles as $role) {
                    $response[] = array(
                        'text' => Lang::line($role['ROLE_NAME']),
                        'id' => $role['ROLE_ID'],
                        'icon' => 'fa fa-user text-orange-400',
                        'state' => array(
                            'selected' => false,
                            "loaded" => true,
                            "disabled" => false,
                            "opened" => false,
                            'ROLE_ID' => $role['ROLE_ID'],
                            'USER_ROLE_ID' => $role['USER_ROLE_ID']
                        ),
                        'children' => true
                    );
                }
            } else {
                $metaTypeId = $this->model->getMetaTypeIdModel($metaDataId);
                $response = $this->getChildData($isSaved, $isDisabled, $metaTypeId, $metaDataId, $roleId, $userId, $parent, $metaTypeIdParent, $isDenied, $isSelected, $haveCriteria, $searchText);
            }

            echo json_encode($response);
        } else {
            echo json_encode(array());
        }
        exit;
    }

    public function setRoleToUser() {
        $result = $this->model->setRoleToUserModel();

        $response = array(
            'status' => 'success',
            'message' => Lang::line('msg_save_success')
        );
        if (!$result) {
            $response = array(
                'status' => 'error',
                'message' => Lang::line('msg_save_error')
            );
        }
        echo json_encode($response); exit;
    }

    public function unSetRoleToUser() {
        $checkedDataList = isset($_POST['checkedDataList']) ? $_POST['checkedDataList'] : NULL;
        foreach ($checkedDataList as $value) {
            $this->model->changeIsActiveModel($value['USER_ROLE_ID'], 1);
        }

        $response = array(
            'status' => 'success',
            'message' => Lang::line('msg_save_success')
        );
        echo json_encode($response); exit;
    }

    public function getCriteriaListByDataview() {
        if (!isset($this->view)) {
            $this->view = new View();
        }

        $metaId = Input::post('metaId');
        (String) $html = "empty";
        $getCriteria = $this->model->getCriteriaListByDataviewModel($metaId);

        if (count($getCriteria) > 0) {
            $duplicateCriteriaCheck = '';
            $numbering = 1;

            foreach ($getCriteria as $key => $row) {
                if ($row['ID'] == $duplicateCriteriaCheck) continue;
                if (issetVar($getCriteria[++$key]['ID']) == $row['ID'] && $row['CHECKED_VAL'] === '0' && $row['BATCH_NUMBER'] === '0') continue;
                $duplicateCriteriaCheck = $row['ID'];

                $html .= '<tr>';
                $html .= '<td>
                              ' . $numbering++ . '
                            </td>
                            <td>
                              <input type="checkbox" class="rowCheckbox" name="checkCriteria[]"' . ($row['CHECKED_VAL'] !== '0' ? ' checked' : '') . ' value="1" />
                              <input type="hidden" name="criteriaId[]" value="' . $row['ID'] . '" />
                              <input type="hidden" class="checkedCriteria" name="checkedCriteria[]" value="' . ($row['CHECKED_VAL'] !== '0' ? '1' : '0') . '" />
                            </td>
                            <td>
                              <input type="text" class="form-control form-control-sm longInit text-right" name="batchNumber[]" value="' . (empty($row['BATCH_NUMBER']) ? '' : $row['BATCH_NUMBER']) . '" />
                            </td>
                            <td>
                              ' . $row['CODE'] . '
                            </td>
                            <td>
                              ' . $row['NAME'] . '
                            </td>
                            <td>
                              ' . $row['DESCRIPTION'] . '
                            </td>
                        </tr>';
            }
        }

        echo $html;
    }

    // </editor-fold>
    // <editor-fold defaultstate="collapsed" desc="Хэрэглэгчийн эрх шилжүүлэх">
    public function assignation() {
        if (!isset($this->view)) {
            $this->view = new View();
        }

        $this->view->title = 'Хэрэглэгчийн эрх шилжүүлэх';

        $this->view->css = array(
            'custom/addon/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.css',
            'custom/addon/plugins/jquery-easyui/themes/metro/easyui.css',
            'custom/addon/plugins/jstree/dist/themes/default/style.min.css'
        );
        $this->view->js = array(
            'custom/addon/plugins/jquery-easyui/jquery.easyui.min.js',
            'custom/addon/plugins/jquery-easyui/locale/easyui-lang-' . Lang::getCode() . '.js'
        );

        $this->view->menuCode = 'ERP_MENU';
        $this->view->defaultType = '200101010000025';
        
        $this->load->model('mdmetadata', 'middleware/models/');
        $this->view->metaDataId = $this->model->getMetaDataIdByCodeModel($this->view->menuCode);
        
        $this->view->roleOrUser = '';
        $this->view->uniqId = getUID();

        if (!is_ajax_request()) $this->view->render('header');

        $this->view->render('role/assignation', self::$viewPath);

        if (!is_ajax_request()) $this->view->render('footer');
    }

    public function getUserList() {
        $result = $this->model->getUsersModel(Input::post('q'));
        header('Content-Type: application/json');
        echo json_encode($result);
        exit;
    }

    // </editor-fold>

    public function randomPassword() {

        $this->view->title = 'Нууц үг оноох';

        $this->view->css = AssetNew::metaCss();
        $this->view->js = AssetNew::metaOtherJs();
        $this->view->fullUrlJs = AssetNew::amChartJs();

        $this->view->rows = $this->model->changePasswordRowsModel();

        $this->view->render('header');
        $this->view->render('user/changePassword', self::$viewPath);
        $this->view->render('footer');
    }
    
    public function roleUserRemove() {
        $response = $this->model->roleUserRemoveModel();
        jsonResponse($response);
    }

}
