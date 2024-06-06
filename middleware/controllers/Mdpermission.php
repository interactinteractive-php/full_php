<?php 
if (!defined('_VALID_PHP'))
    exit('Direct access to this location is not allowed.');

/**
 * Mdlanguage Class 
 * 
 * @package     IA PHPframework
 * @subpackage	Middleware
 * @category	Permission
 * @author	Ts.Ulaankhuu <ulaankhuu@veritech.mn>
 * @link	http://www.interactive.mn/PHPframework/Middleware/Mdpermission
 */
class Mdpermission extends Controller {

    private static $viewPath = "middleware/views/permission/";
    private static $dataViewMetaCode = "sysMetaDataViewList";
    public static $processMetaCode = "umMetaPermissionUpdate";

    public function __construct() {
        parent::__construct();
        Auth::handleLogin();
    }

    public function createPermissionCriteria() {
        if (!isset($this->view)) {
            $this->view = new View();
        }
        $this->view->title = "Permission criteria үүсгэх";
        
        $this->view->dataViewMetacode = self::$dataViewMetaCode;
        $this->view->render('umcriteria/add', self::$viewPath);
    }    
    
    public function criteriaFilterAddForm() {
        $response = array(
            'html' => $this->view->renderPrint('umcriteria/filterSave', self::$viewPath),
            'title' => 'Шүүлт нэмэх',
            'save_btn' => Lang::line('save_btn'),
            'close_btn' => Lang::line('close_btn')
        );
        
        echo json_encode($response); exit;
    }    
    
    public function criteriaFilterCreate() {  
        $defaultCriteriaParam     = isset($_POST['param']) ? $_POST['param'] : array();
        $defaultCriteriaCondition = (isset($_POST['criteriaCondition']) && !is_null($_POST['criteriaCondition'])) ? $_POST['criteriaCondition'] : 'LIKE';
        $defaultCondition = (isset($_POST['criteriaCondition']) && !is_null($_POST['criteriaCondition'])) ? '1' : '0';
        $metaDataId = Input::post('permission_dataview_id');
        
        $this->load->model('mdmetadata', 'middleware/models/');
        foreach ($defaultCriteriaParam as $i => $v) {
            $row = $this->model->getGroupConfigRowByPath($metaDataId, $i);
            if($row['COLUMN_NAME'] === null){
              unset($defaultCriteriaParam[$i]);
            }  
        }
        
        $this->load->model('mdcommon', 'middleware/models/');
        (String) $paramDefaultCriteria = "";   
        
        foreach ($defaultCriteriaParam as $defParam => $defParamVal) {
            if (is_array($defParamVal)) {
                $arrayToString = "";
                $defParamValArr = $defParamVal;
                foreach ($defParamValArr as $defParam1 => $defParamVal) {
//                    $defParamVal = Input::param(Str::lower($defParamVal)); 
                    $defParamVal = Input::param($defParamVal); 
                    $defParamVal = Mdmetadata::setDefaultValue($defParamVal); 

                    $operator = ($defaultCondition === '0') ? $defaultCriteriaCondition : (isset($defaultCriteriaCondition[$defParam]) ? $defaultCriteriaCondition[$defParam] : (is_array($defaultCriteriaCondition) ? '=' : $defaultCriteriaCondition));
                    $defParamValue = isset($operator) ? $defParamVal : ((Str::upper($operator) === 'LIKE') ? "%".$defParamVal."%" : $defParamVal); 
                    $getTypeCode = $this->model->getMetaTypeMetaGroupGonfigModel($metaDataId, $defParam);
                    if (isset($getTypeCode['META_TYPE_CODE'])) {
                        if (Str::lower($getTypeCode['META_TYPE_CODE']) == 'long' 
                               || Str::lower($getTypeCode['META_TYPE_CODE']) == 'integer' 
                               || Str::lower($getTypeCode['META_TYPE_CODE']) == 'bigdecimal' 
                               || Str::lower($getTypeCode['META_TYPE_CODE']) == 'number') {

                           $defParamValue = Number::decimal($defParamValue);
                           if(!empty($defParamValue))
                               $arrayToString .= " [" . $defParam . "] " . $operator . " " . $defParamValue . " OR ";

                       } else {
                           if(!empty($defParamValue)) {
                               $arrayToString .= " [" . $defParam . "] " . $operator . " '" . $defParamValue . "' OR ";
                           }
                       }   
                    }  else {
                        if(!empty($defParamValue))
                            $arrayToString .= " [" . $defParam . "] " . $operator . " '" . $defParamValue . "' OR ";
                    }
                        
                }
                $arrayToString = rtrim(trim($arrayToString), " OR");
                if(trim($arrayToString) !== ""){
                    $paramDefaultCriteria .= " ( " . $arrayToString . " ) AND ";
                }
            }
            else {
//                $defParamVal = Input::param(Str::lower($defParamVal)); 
                $defParamVal = Input::param($defParamVal);
                $defParamVal = Mdmetadata::setDefaultValue($defParamVal); 

                $operator = ($defaultCondition === '0') ? $defaultCriteriaCondition : (isset($defaultCriteriaCondition[$defParam]) ? $defaultCriteriaCondition[$defParam] : $defaultCriteriaCondition);
                $defParamValue = isset($operator) ? $defParamVal : ((Str::upper($operator) === 'LIKE') ? "%".$defParamVal."%" : $defParamVal); 

                $getTypeCode = $this->model->getMetaTypeMetaGroupGonfigModel($metaDataId, $defParam);
                if (Str::lower($getTypeCode['META_TYPE_CODE']) == 'long' 
                        || Str::lower($getTypeCode['META_TYPE_CODE']) == 'integer' 
                        || Str::lower($getTypeCode['META_TYPE_CODE']) == 'bigdecimal' 
                        || Str::lower($getTypeCode['META_TYPE_CODE']) == 'number') {

                    $defParamValue = Number::decimal($defParamValue);
                    if(!empty($defParamValue)){
                        if(Str::upper($operator) === 'LIKE'){
                            $paramDefaultCriteria .= "[" . $defParam . "] " . $operator . " '%" . $defParamValue . "%' AND ";
                        }else{
                            $paramDefaultCriteria .= "[" . $defParam . "] " . $operator . " " . $defParamValue . " AND ";
                        }
                    }
                } else {
                    if(!empty($defParamValue)){
                        if(Str::upper($operator) === 'LIKE'){
                            $paramDefaultCriteria .= "[" . $defParam . "] " . $operator . " '%" . $defParamValue . "%' AND ";
                        }else{
                            $paramDefaultCriteria .= "[" . $defParam . "] " . $operator . " '" . $defParamValue . "' AND ";
                        }
                    }
                }       
            }
        }
        
        if(Input::post('cardViewerFieldPath')){
            $paramDefaultCriteria .= " [" . Input::post('cardViewerFieldPath') . "] ";
            if(Input::post('cardViewerValue')){
                $paramDefaultCriteria .= " = " . Mdmetadata::setDefaultValue(Input::post('cardViewerValue')) . " AND ";
            }else{
                $paramDefaultCriteria .= " IS NULL AND ";
            }
        }
        
        if(Input::post('currentSelectedRowValues')){
            $jsonCriteriaArray = json_decode($_POST['currentSelectedRowValues'], true);
            foreach($jsonCriteriaArray as $k => $v){
                $paramDefaultCriteria .= " [" . Input::param($k) . "] = '" . Input::param($v) . "' AND ";
            }
        }
        
        if($paramDefaultCriteria === "") {
            $response = array(
                'status' => 'info',
                'message' => 'Criteria хоосон байна!'
            );            
        } else {
            $paramDefaultCriteria = rtrim(trim($paramDefaultCriteria), " AND");

            $paramCriteria = array(
                'ID' => getUID(),
                'CODE' => Input::post('filterCode'),
                'NAME' => Input::post('filterName'),
                'DESCRIPTION' => Input::post('filterDesc'),
                'IS_SYSTEM' => '1',
                'META_DATA_ID' => $metaDataId,
                'CRITERIA_STRING' => $paramDefaultCriteria
            );

            $this->load->model('mdpermission', 'middleware/models/');
            $result = $this->model->filterCriteriaSaveModel($paramCriteria);

            if ($result) {
                $response = array(
                    'status' => 'success',
                    'message' => Lang::line('msg_save_success')
                );
            } else {
                $response = array(
                    'status' => 'error',
                    'message' => Lang::line('msg_save_error')
                );
            }
        }
        echo json_encode($response); exit;
    }    
    
    public function editPermissionCriteria() {
        if (!isset($this->view)) {
            $this->view = new View();
        }
        $this->load->model('mdpermission', 'middleware/models/');
        
        $this->view->title = "Permission criteria засах";
        $criteriaId = Input::post('criteriaId');
        $this->view->getCriteria = $this->model->getFilterCriteriaModel($criteriaId);
        
        $this->load->model('mdmetadata', 'middleware/models/');
        $getMetaDataId = $this->model->getMetaDataByCodeModel(self::$dataViewMetaCode);
        $this->view->metaCodeNameId = (new Mddatamodel())->getIdCodeName($getMetaDataId['META_DATA_ID'], $this->view->getCriteria['META_DATA_ID']);
        
        $this->view->dataViewMetacode = self::$dataViewMetaCode;
        $this->view->render('umcriteria/edit', self::$viewPath);
    }        
    
    public function criteriaFilterUpdate() {                
        $defaultCriteriaParam     = Input::post('param');
        $defaultCriteriaCondition = !is_null(Input::post('criteriaCondition')) ? Input::post('criteriaCondition') : 'LIKE';
        $defaultCondition = !is_null(Input::post('criteriaCondition')) ? '1' : '0';
        $metaDataId = Input::post('inputMetaDataId');

        $this->load->model('mdcommon', 'middleware/models/');
        (String) $paramDefaultCriteria = "";
        
        foreach ($defaultCriteriaParam as $defParam => $defParamVal) {

            $defParamVal = Input::param(Str::lower(trim($defParamVal)));
            $defParamVal = Mdmetadata::setDefaultValue($defParamVal);

            $operator = ($defaultCondition === '0') ? $defaultCriteriaCondition : (isset($defaultCriteriaCondition[$defParam]) ? $defaultCriteriaCondition[$defParam] : $defaultCriteriaCondition);
            $defParamValue = isset($operator) ? $defParamVal : ((Str::upper($operator) === 'LIKE') ? "%".$defParamVal."%" : $defParamVal); 
            
            $getTypeCode = $this->model->getMetaTypeMetaGroupGonfigModel($metaDataId, $defParam);
            if (Str::lower($getTypeCode['META_TYPE_CODE']) == 'long' 
                    || Str::lower($getTypeCode['META_TYPE_CODE']) == 'integer' 
                    || Str::lower($getTypeCode['META_TYPE_CODE']) == 'bigdecimal' 
                    || Str::lower($getTypeCode['META_TYPE_CODE']) == 'number') {
                
                $defParamValue = Number::decimal($defParamValue);
                if(!empty($defParamValue))
                    $paramDefaultCriteria .= "[" . $defParam . "] " . html_entity_decode($operator, ENT_QUOTES) . " " . $defParamValue . " AND ";

            } else {
                if(!empty($defParamValue))
                    $paramDefaultCriteria .= "[" . $defParam . "] " . html_entity_decode($operator, ENT_QUOTES) . " '" . $defParamValue . "' AND ";
            }            
        }
        
        if($paramDefaultCriteria === "") {
            $response = array(
                'status' => 'info',
                'message' => 'Criteria хоосон байна!'
            );            
        } else {        
            $paramDefaultCriteria = rtrim(trim($paramDefaultCriteria), " AND");

            $paramCriteria = array(
                'CODE' => Input::post('filterCode'),
                'NAME' => Input::post('filterName'),
                'DESCRIPTION' => Input::post('filterDesc'),
                'IS_SYSTEM' => '1',
                'META_DATA_ID' => $metaDataId,
                'CRITERIA_STRING' => $paramDefaultCriteria
            );

            $this->load->model('mdpermission', 'middleware/models/');
            $result = $this->model->filterCriteriaUpdateModel($paramCriteria, Input::post('criteriaId'));

            if ($result) {
                $response = array(
                    'status' => 'success',
                    'message' => Lang::line('msg_save_success')
                );
            } else {
                $response = array(
                    'status' => 'error',
                    'message' => Lang::line('msg_save_error')
                );
            }
        }
        
        echo json_encode($response); exit;
    }        
    
    public static function getCriteriaStringToArray($criteriaId) {
        
        global $db;
        
        $criteriaIdPh = $db->Param(0);
        $bindVars = array($db->addQ($criteriaId));
        
        $getCriteria = $db->GetRow("
            SELECT 
                ID, 
                CODE, 
                NAME, 
                DESCRIPTION, 
                META_DATA_ID, 
                CRITERIA_STRING 
            FROM UM_CRITERIA 
            WHERE ID = $criteriaIdPh",   
            $bindVars 
        );
        
        if ($getCriteria) {
        
            $getCriteria = explode("AND", $getCriteria['CRITERIA_STRING']);
            $result = array();

            foreach ($getCriteria as $row) {
                preg_match('/\[(.*?)\]/', $row, $metaDataCode);
                if (isset($metaDataCode[1])) {
                    preg_match('/\s(=|like|<>|>|<|>=|<=)\s(.*)/i', $row, $rowVal);

                    $metaDataCode[1] = Str::lower($metaDataCode[1]);
                    $metaValues[$metaDataCode[1]] = trim($rowVal[2]);
                    $metaOperand[$metaDataCode[1]] = $rowVal[1];
                }
            }
        } 
        
        $result = array('metaValues' => issetParam($metaValues), 'metaOperand' => issetParam($metaOperand)); 
        
        return $result;
    }         
    
    public function getCriteriaListByDataview() {
        if (!isset($this->view)) {
            $this->view = new View();
        }
        $this->load->model('mdpermission', 'middleware/models/');
        
        $metaId = Input::post('metaId');
        (String) $html = "empty";
        $getCriteria = $this->model->getCriteriaListByDataviewModel($metaId);
        
        if(count($getCriteria) > 0) {
            $duplicateCriteriaCheck = '';
            $numbering = 1;
            
            foreach ($getCriteria as $key => $row) {
                if($row['ID'] == $duplicateCriteriaCheck)
                    continue;
                if(issetVar($getCriteria[++$key]['ID']) == $row['ID'] && $row['CHECKED_VAL'] === '0' && $row['BATCH_NUMBER'] === '0')
                    continue;
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
        
        echo $html; exit;
    }          
    
    public function umMetaPermCriteriaCreate() {                            
        $this->load->model('mdpermission', 'middleware/models/');
        
        if(is_null(Input::post('permissionId')))
            $response = array(
                'status' => 'info',
                'message' => 'Энэ DataView-н permission-ээ эхлээд <br>үүсгэнэ үү.'
            );
        else {
            
            $defaultCriteriaParam = Input::post('criteriaId');
            foreach ($defaultCriteriaParam as $key => $defParamVal) {
                if($_POST['checkedCriteria'][$key] === '1') {
                    $paramCriteria = array(
                        'ID' => getUID(),
                        'PERMISSION_ID' => Input::post('permissionId'),
                        'CRITERIA_ID' => $defParamVal,
                        'BATCH_NUMBER' => Input::param($_POST['batchNumber'][$key])
                    );
                    $result = $this->model->umMetaPermCriteriaCreateModel($paramCriteria);
                }
            }
            
            $this->load->model('mdmetadata', 'middleware/models/');
            $getMetaDataId = $this->model->getMetaDataByCodeModel(self::$processMetaCode);        

            $this->load->model('mdwebservice', 'middleware/models/');
            $row = $this->model->getMethodIdByMetaDataModel($getMetaDataId['META_DATA_ID']);     
            
            $param['id'] = Input::post('permissionId');
            $resultProcess = $this->ws->caller($row['SERVICE_LANGUAGE_CODE'], $row['WS_URL'], $row['META_DATA_CODE'], 'return', $param, 'serialize');            

            if ($result && $resultProcess['status'] === 'success') {
                $response = array(
                    'status' => 'success',
                    'message' => Lang::line('msg_save_success')
                );
            } else {
                $response = array(
                    'status' => 'error',
                    'message' => isset($resultProcess['text']) ? $resultProcess['text'] : Lang::line('msg_save_error')
                );
            }
        }
        
        echo json_encode($response); exit;
    }        

}
