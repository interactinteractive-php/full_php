<?php if (!defined('_VALID_PHP')) exit('Direct access to this location is not allowed.');

/**
 * Mdworkflow Class 
 * 
 * @package     IA PHPframework
 * @subpackage	Middleware
 * @category	WorkFlow (Ажлын урсгал)
 * @author	B.Och-Erdene <ocherdene@interactive.mn>
 * @link	http://www.interactive.mn/PHPframework/Middleware/Mdworkflow
 */

class Mdworkflow extends Controller {
    
    private static $viewPath = 'middleware/views/workflow/';

    public function __construct() {
        parent::__construct();
        Auth::handleLogin();
    }
    
    public function flowViewer($type = '1') {
        
        $this->view->refStructureId = Input::post('refStructureId');
        $this->view->uniqId = getUID();
        $this->view->rowId = Input::post('rowId');
        $this->view->newWfmStatusId = Input::post('wfmStatusId');
        $this->view->newWfmStatusColor = Input::post('wfmstatuscolor');
        $this->view->newWfmStatusName = Input::post('wfmStatusName');
        $this->view->metaDataId = Input::post('dataViewId');
        $this->view->dataRow = Input::post('dataRow');
        $this->view->userData = $this->model->getUserDataBySessionUserIdModel();
        
        $this->load->model('mdobject', 'middleware/models/');
        $this->view->wfmStatusAssignment = $this->model->getWfmStatusAssignmentModel($this->view->newWfmStatusId, $this->view->refStructureId, $this->view->rowId);
        $this->view->wfmLogData = $this->model->getWfmStatusLogDataModel($this->view->newWfmStatusId, $this->view->refStructureId, $this->view->rowId);
        
        $array_temp = $array_needle = array();
        
        if ($this->view->wfmLogData) {
            foreach ($this->view->wfmLogData as $row) {
                if (!in_array($row['WFM_STATUS_ID'], $array_temp)) {
                    array_push($array_temp, $row['WFM_STATUS_ID']);
                    array_push($array_needle, $row);
                }
            }
        }
        
        $this->view->wfmStatusList = $array_needle;
        
        if ($type === '1') {
            
            $response = array(
                'html' => $this->view->renderPrint('v2/flowViewer', $this->viewPath),
                'title' => 'Flow', 
                'uniqId' => $this->view->uniqId, 
                'save_btn' => Lang::line('save_btn'),
                'close_btn' => Lang::line('close_btn')
            );

            echo json_encode($response); exit;
        } else {
            $this->view->render('v2/flowViewer', $this->viewPath);
        }
    }
    
    public function renderflowViewer() {
        $this->view->refStructureId = Input::post('refStructureId');
        $this->view->uniqId = getUID();
        $this->view->rowId = Input::post('rowId');
        $this->view->newWfmStatusId = Input::post('wfmStatusId');
        $this->view->newWfmStatusColor = Input::post('wfmstatuscolor');
        $this->view->newWfmStatusName = Input::post('wfmStatusName');
        $this->view->metaDataId = Input::post('dataViewId');
        $this->view->userData = $this->model->getUserDataBySessionUserIdModel();
        
        $this->load->model('mdobject', 'middleware/models/');
        $this->view->wfmLogData = $this->model->getWfmStatusLogDataModel($this->view->newWfmStatusId, $this->view->refStructureId, $this->view->rowId);
        
        $response = array(
            'result' => $this->view->wfmLogData,
        );
        
        echo json_encode($response); exit;
    }
    
    public function getWorkflowNextStatus($metaDataId, $dataRow, $refStructureId, $process = true, $returnData = false) {
        $this->load->model('mdworkflow', 'middleware/models/');
        
        $btnHtml = $singleMenuHtml = $wfmStatusName = '';
        
        $result = $this->model->getWorkflowNextStatusModel($metaDataId, $dataRow);
        
        if ($result['status'] === 'success' && $result['datastatus']) {
        
            if ($returnData) {
                return $result['data'];
            }
            
            $this->load->model('mdobject', 'middleware/models/');
            $row = $this->model->getDataViewConfigRowModel($metaDataId);
            $metaDataCode = $row['META_DATA_CODE'];
            
            foreach ($result['data'] as $row) {
                
                $wfmStatusName = $row['wfmstatusname'];
                
                if (!empty($wfmStatusName) && empty($row['wfmstatusprocessid'])) {
                    
                    if ($row['wfmisneedsign'] == '1') {
                        
                        $singleMenuHtml = 'onclick="beforeSignChangeWfmStatusId(this, \''.$row['wfmstatusid'].'\', \''.$metaDataId.'\', \''.$refStructureId.'\', \''.trim($row['wfmstatuscolor']).'\', \''.$wfmStatusName.'\');"';
                        $btnHtml .= '<button type="button" '.$singleMenuHtml.' class="btn btn-sm blue btn-circle dropdown-toggle" style="background-color: '.$row['wfmstatuscolor'].'" data-toggle="dropdown" aria-expanded="false">'.$wfmStatusName.' <i class="fa fa-key"></i></button> ';
                        
                    } elseif ($row['wfmisneedsign'] == '2') {
                        
                        $singleMenuHtml = 'onclick="beforeHardSignChangeWfmStatusId(this, \''.$row['wfmstatusid'].'\', \''.$metaDataId.'\', \''.$refStructureId.'\', \''.trim($row['wfmstatuscolor']).'\', \''.$wfmStatusName.'\');"';
                        $btnHtml .= '<button type="button" '.$singleMenuHtml.' class="btn btn-sm blue btn-circle dropdown-toggle" style="background-color: '.$row['wfmstatuscolor'].'" data-toggle="dropdown" aria-expanded="false">'.$wfmStatusName.' <i class="fa fa-key"></i></button> ';
                    
                    } else {
                        
                        $singleMenuHtml = 'onclick="changeWfmStatusId(this, \''.$row['wfmstatusid'].'\', \''.$metaDataId.'\', \''.$refStructureId.'\', \''.trim($row['wfmstatuscolor']).'\', \''.$wfmStatusName.'\', \'\', \'\', \''.$row['wfmisdescrequired'].'\');"';
                        $btnHtml .= '<button type="button" '.$singleMenuHtml.' class="btn btn-sm blue btn-circle dropdown-toggle" style="background-color: '.$row['wfmstatuscolor'].'" data-toggle="dropdown" aria-expanded="false">'.$wfmStatusName.'</button> ';
                        
                    }
                    
                } elseif (!empty($row['wfmstatusprocessid']) && $process) {
                    
                    if ($row['wfmisneedsign'] == '1') {
                        
                        $singleMenuHtml = 'onclick="transferProcessAction(\'signProcess\', \''.$metaDataId.'\', \''.$row['wfmstatusprocessid'].'\', \''.Mdmetadata::$businessProcessMetaTypeId.'\', \'toolbar\', this, {callerType: \''.$metaDataCode.'\', isWorkFlow: true}, \'dataViewId='.$metaDataId.'&refStructureId='.$refStructureId.'&statusId='.$row['wfmstatusid'].'&statusName='.$wfmStatusName.'&statusColor='.trim($row['wfmstatuscolor']).'&rowId='.$dataRow['id'].'\');"';
                        $btnHtml .= '<button type="button" '.$singleMenuHtml.' class="btn btn-sm blue btn-circle dropdown-toggle" data-toggle="dropdown" style="background-color: '.$row['wfmstatuscolor'].'" aria-expanded="false">'.$wfmStatusName.' <i class="fa fa-key"></i></button> ';                        
                        
                    } elseif ($row['wfmisneedsign'] == '2') {
                        
                        $singleMenuHtml = 'onclick="transferProcessAction(\'hardSignProcess\', \''.$metaDataId.'\', \''.$row['wfmstatusprocessid'].'\', \''.Mdmetadata::$businessProcessMetaTypeId.'\', \'toolbar\', this, {callerType: \''.$metaDataCode.'\', isWorkFlow: true}, \'dataViewId='.$metaDataId.'&refStructureId='.$refStructureId.'&statusId='.$row['wfmstatusid'].'&statusName='.$wfmStatusName.'&statusColor='.trim($row['wfmstatuscolor']).'&rowId='.$dataRow['id'].'\');"';
                        $btnHtml .= '<button type="button" '.$singleMenuHtml.' class="btn btn-sm blue btn-circle dropdown-toggle" data-toggle="dropdown" style="background-color: '.$row['wfmstatuscolor'].'" aria-expanded="false">'.$wfmStatusName.' <i class="fa fa-key"></i></button> ';                        
                        
                    } else {
                        
                        $singleMenuHtml = 'onclick="transferProcessAction(\'\', \''.$metaDataId.'\', \''.$row['wfmstatusprocessid'].'\', \''.Mdmetadata::$businessProcessMetaTypeId.'\', \'toolbar\', this, {callerType: \''.$metaDataCode.'\', isWorkFlow: true}, \'dataViewId='.$metaDataId.'&refStructureId='.$refStructureId.'&statusId='.$row['wfmstatusid'].'&statusName='.$wfmStatusName.'&statusColor='.trim($row['wfmstatuscolor']).'&rowId='.$dataRow['id'].'\');"';
                        $btnHtml .= '<button type="button" '.$singleMenuHtml.' class="btn btn-sm blue btn-circle dropdown-toggle" style="background-color: '.$row['wfmstatuscolor'].'" data-toggle="dropdown" aria-expanded="false">'.$wfmStatusName.'</button> ';
                        
                    }
                    
                } elseif (!empty($row['wfmstatusprocessid']) && !$process) {
                    
                    $singleMenuHtml = 'onclick="changeWfmStatusId(this, \''.$row['wfmstatusid'].'\', \''.$metaDataId.'\', \''.$refStructureId.'\', \''.trim($row['wfmstatuscolor']).'\', \''.$wfmStatusName.'\', \'\', \'\', \''.$row['wfmisdescrequired'].'\');"';
                    $btnHtml .= '<button type="button" '.$singleMenuHtml.' class="btn btn-sm '.trim($row['wfmstatuscolor']).' btn-circle dropdown-toggle" id=\''.$row['wfmstatusid'].'\' data-toggle="dropdown" style="background-color: '.$row['wfmstatuscolor'].'" aria-expanded="false"><i class="fa fa-key"></i> '.$wfmStatusName.'</button> ';
                }                
            }
        }
        
        return $btnHtml;
    }    
    
    public function saveWfmassignment() {
        $result = $this->model->saveWfmassignmentModel();
        echo json_encode($result); exit;
    }
    
    public function deleteAssignment() {
        $response = $this->model->deleteAssignmentModel();
        echo json_encode($response); exit;
    }
    
    public function viewStatusStep($refStructureId, $recordId, $statusId) {
        if (!isset($this->view)) {
            $this->view = new View();
        }   
        
        $this->view->currentStatusId = $statusId;
        $this->view->statusList = $this->model->viewStatusFlowModel($refStructureId, $recordId, $statusId);
        
        return $this->view->renderPrint('status/viewStatusStep', self::$viewPath);
    }
    
    public function viewStatusAssignmentUsers($refStructureId, $recordId, $statusId) {
        if (!isset($this->view)) {
            $this->view = new View();
        }   
        
        $this->view->assignmentUsers = $this->model->getStatusAssignmentUsersModel($refStructureId, $recordId, $statusId);
        
        return $this->view->renderPrint('status/viewStatusAssignmentUsers', self::$viewPath);
    }
    
    public function wfmPanelViewer($refStructureId, $recordId, $statusId) {
        
        $this->load->model('mdworkflow', 'middleware/models/');
        
        $viewStatusStep = self::viewStatusStep($refStructureId, $recordId, $statusId);
        $viewStatusAssignmentUsers = self::viewStatusAssignmentUsers($refStructureId, $recordId, $statusId);
        
        return array('statusStep' => $viewStatusStep, 'assignmentUsers' => $viewStatusAssignmentUsers);
    }
    
    public function changeWfmStatusByStr() {
        $response = $this->model->changeWfmStatusByStrModel();
        echo json_encode($response); exit;
    }
    
    public function userDefAssignWfmStatus() {
        
        $this->load->model('mdobject', 'middleware/models/');
        
        $this->view->dataViewId = Input::numeric('dataViewId');
        $this->view->ruleId = Input::numeric('ruleId');
        
        $this->view->row = $this->model->getDataViewConfigRowModel($this->view->dataViewId);
        $this->view->refStructureId = $this->view->row['REF_STRUCTURE_ID'];
        
        $this->view->render('status/userDefAssignWfmStatus', self::$viewPath);
    }
    
    public function copyAssignment() {
        $response = $this->model->copyAssignmentModel();
        jsonResponse($response);
    }
    
}