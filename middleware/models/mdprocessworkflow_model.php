<?php

if (!defined('_VALID_PHP'))
    exit('Direct access to this location is not allowed.');

if (class_exists('mdprocessworkflow_model') != true) {

    class Mdprocessflow_model extends Model {

        private static $metaDatas = array();
        private static $t = 0;

        public function __construct() {
            parent::__construct();
        }

        public function getObjectList($mainBpId) {

            $metaData = $this->db->GetRow("
                                            SELECT 
                                                MD.META_DATA_ID, MD.META_DATA_CODE, MD.META_DATA_NAME,
                                                MD.ADDON_DATA, MBPL.INPUT_META_DATA_ID, MBPL.OUTPUT_META_DATA_ID 
                                            FROM 
                                                META_DATA MD
                                                INNER JOIN META_BUSINESS_PROCESS_LINK MBPL ON MD.META_DATA_ID = MBPL.META_DATA_ID
                                            WHERE 
                                                MD.META_DATA_ID = $mainBpId");
            
            $processWorkflowList = $this->db->GetAll("
                                            SELECT 
                                                MPW.META_PROCESS_WORKFLOW_ID,MD.META_DATA_CODE, MD.META_DATA_ID, MD.META_DATA_NAME, 
                                                MPW.MAIN_BP_ID, MPW.DO_BP_ID, MBPL.INPUT_META_DATA_ID, MBPL.OUTPUT_META_DATA_ID
                                            FROM 
                                                META_PROCESS_WORKFLOW MPW 
                                                INNER JOIN META_DATA MD ON MPW.DO_BP_ID = MD.META_DATA_ID
                                                INNER JOIN META_BUSINESS_PROCESS_LINK MBPL ON MD.META_DATA_ID = MBPL.META_DATA_ID
                                            WHERE 
                                                MPW.MAIN_BP_ID =  $mainBpId");

            $positionData = json_decode($metaData['ADDON_DATA']);
            (Array) $position = array();
            if ($positionData) {
                foreach ($positionData as $row) {
                    if (isset($row->id)) {
                        (Array) $position[$row->id] = array('positionTop' => $row->positionTop, 'positionLeft' => $row->positionLeft);
                    }
                }
            }
            
            (Array) $object = array();
            (Array) $connect = array('SOURCE' => '0', 'TARGET' => '0', 'CRITERIA' => '');
            
            $positionLeft = 110;
            $positionTop = 80;

            array_push($object, array(
                'id' => '0',
                'metaDataCode' => '',
                'title' => '',
                'type' => 'circle',
                'class' => 'wfIconCircle',
                'positionTop' => '100',
                'positionLeft' => '80',
                'borderColor' => '#f00a0a',
                'borderWidth' => '2',
                'background' => '#f00a0a',
                'width' => '30',
                'height' => '30'
            ));

            if ($processWorkflowList) {
                
                foreach ($processWorkflowList as $row) {
                    
                    $pId = $row['META_PROCESS_WORKFLOW_ID'];
                    array_push($object, array(
                        'id' => $pId,
                        'metaDataCode' => $row['META_DATA_CODE'],
                        'title' => $row['META_DATA_NAME'],
                        'doBpId' => $row['DO_BP_ID'],
                        'inputMetaDataId' => $row['INPUT_META_DATA_ID'],
                        'outputMetaDataId' => $row['OUTPUT_META_DATA_ID'],
                        'type' => 'rectangle',
                        'class' => 'wfIconRectangle ' . (($row['DO_BP_ID'] == $mainBpId) ? 'wfIconRectangleBackground' : ''),
                        'positionTop' => (isset($position[$pId]) ? $position[$pId]['positionTop'] : $positionTop),
                        'positionLeft' => (isset($position[$pId]) ? $position[$pId]['positionLeft'] : $positionLeft),
                        'width' => '160',
                        'height' => '70',
                    ));
                    $positionLeft += 300;
                }
                
                $connect = $this->db->GetAll("
                                        SELECT"
                                            . $this->db->ifNull('BEH.META_PROCESS_WF_ID', 0) . "AS SOURCE,  
                                            BEH.NEXT_META_PROCESS_WF_ID AS TARGET,
                                            BEH.CRITERIA
                                        FROM 
                                            META_PROCESS_WF_BEHAVIOUR BEH
                                        WHERE 
                                            BEH.MAIN_BP_ID = $mainBpId");
            } else {

                $pId = getUID();
                array_push($object, array(
                    'id' => $pId,
                    'metaDataCode' => $metaData['META_DATA_CODE'],
                    'title' => $metaData['META_DATA_NAME'],
                    'doBpId' => $metaData['META_DATA_ID'],
                    'inputMetaDataId' => $metaData['INPUT_META_DATA_ID'],
                    'outputMetaDataId' => $metaData['OUTPUT_META_DATA_ID'],
                    'type' => 'rectangle',
                    'class' => 'wfIconRectangle wfIconRectangleBackground',
                    'positionTop' => $positionTop,
                    'positionLeft' => $positionLeft + 170,
                    'width' => '160',
                    'height' => '70',
                ));

                array_push($connect, array(
                    'SOURCE' => '0',
                    'TARGET' => $pId,
                    'CRITERIA' => ''
                ));
            }
            
            return array('object' => $object, 'connect' => $connect);
        }

        public function save($object = '', $connect = '') {

            $mainBpId = Input::post('mainBpId');
            $currentDate = Date::currentDate();
            $sessionUserId = Ue::sessionUserId();
            try {

                $existStartBehaviour = '0';
                foreach ($connect as $row) {

                    $pageSourceId = Input::param($row['pageSourceId']);
                    if ($pageSourceId == '0') {
                        $existStartBehaviour = '1';
                    }
                }

                if ($existStartBehaviour == '0') {
                    return array('status' => 'warning', 'text' => 'Эхлэлийн процесс тодорхойгүй байна.', 'message' => 'Алдаа');
                } else {

                    $this->db->Execute("DELETE FROM META_PROCESS_WF_BEHAVIOUR WHERE MAIN_BP_ID = $mainBpId");
                    $this->db->Execute("DELETE FROM META_PROCESS_WORKFLOW WHERE MAIN_BP_ID = $mainBpId");

                    $addOnData = json_encode(array('object' => $object, 'connect' => $connect));
                    $result = $this->db->UpdateClob('META_DATA', 'ADDON_DATA', json_encode($_POST['conn']), 'META_DATA_ID=' . $mainBpId);
                    foreach ($object as $key => $row) {

                        $pId = Input::param($row['id']);
                        if ($pId != '0') {

                            $data = array(
                                'META_PROCESS_WORKFLOW_ID' => $pId,
                                'MAIN_BP_ID' => $mainBpId,
                                'DO_BP_ID' => Input::param($row['dobpid']),
                                'IS_ACTIVE' => 1,
                                'CREATED_DATE' => $currentDate,
                                'CREATED_USER_ID' => $sessionUserId
                            );
                            $insert = $this->db->AutoExecute('META_PROCESS_WORKFLOW', $data);
                        }
                    }

                    foreach ($connect as $row) {

                        $pageSourceId = Input::param($row['pageSourceId']);
                        $data = array(
                            'ID' => getUID(),
                            'META_PROCESS_WF_ID' => ($pageSourceId == '0' ? null : $pageSourceId),
                            'NEXT_META_PROCESS_WF_ID' => Input::param($row['pageTargetId']),
                            'CRITERIA' => Input::param($row['criteria']),
                            'MAIN_BP_ID' => $mainBpId
                        );
                        $this->db->AutoExecute('META_PROCESS_WF_BEHAVIOUR', $data);
                    }

                    return array('status' => 'success');
                }
            } catch (Exception $ex) {
                return array('status' => 'warning', 'text' => $ex->msg, 'message' => $ex);
            }
        }
        

        public function getParameterListModel($isOutput = 0, $mainBpId, $doBpId, $isShow = 0) {

            $parameters = self::getParameterListWithPathModel($doBpId, ($isOutput == 1 ? 0 : 1)); //all paramete
            $data = $this->db->GetAll("
                SELECT
                    MPPL.META_PROCESS_PARAM_LINK_ID,
                    MPPL.MAIN_BP_ID,
                    MPPL.DO_BP_ID,
                    MPPL.DO_BP_PARAM_PATH,
                    MPPL.DO_BP_PARAM_IS_INPUT,
                    MPPL.DONE_BP_ID,
                    MPPL.DONE_BP_PARAM_PATH,
                    MPPL.DONE_BP_PARAM_IS_INPUT,
                    MPPL.DEFAULT_VALUE
                FROM META_PROCESS_PARAM_LINK MPPL 
                    INNER JOIN META_DATA MD1 ON MD1.META_DATA_ID = MPPL.DO_BP_ID 
                    LEFT JOIN META_DATA MD2 ON MD2.META_DATA_ID = MPPL.DONE_BP_ID 
                WHERE 
                    MPPL.MAIN_BP_ID = $mainBpId AND 
                    MPPL.DO_BP_ID = $doBpId AND
                    MPPL.DO_BP_PARAM_IS_INPUT = " . ($isOutput == 1 ? 0 : 1));
            (Array) $array = array();

            foreach ($parameters as $key => $parameter) {

                $array[$key]['META_DATA_ID'] = $parameter['META_DATA_ID'];
                $name = explode('-', $parameters[$key]['META_DATA_NAME']);
                $array[$key]['META_DATA_NAME'] = $name[count($name) - 1];
                $array[$key]['META_DATA_CODE'] = $parameter['META_DATA_CODE'];
                $array[$key]['META_TYPE_ID'] = $parameter['META_TYPE_ID'];
                $array[$key]['RECORD_TYPE'] = $parameter['RECORD_TYPE'];
                $array[$key]['IS_SHOW'] = $parameter['IS_SHOW'];
                $array[$key]['PARENT_META_DATA_CODE'] = $parameter['PARENT_META_DATA_CODE'];
                $array[$key]['META_PROCESS_PARAM_LINK_ID'] = "";
                $array[$key]['MAIN_BP_ID'] = "";
                $array[$key]['DO_BP_ID'] = "";
                $array[$key]['DO_BP_PARAM_PATH'] = "";
                $array[$key]['DO_BP_PARAM_IS_INPUT'] = "";
                $array[$key]['DONE_BP_ID'] = "";
                $array[$key]['DONE_BP_PARAM_PATH'] = "";
                $array[$key]['DONE_BP_PARAM_IS_INPUT'] = "";
                $array[$key]['DEFAULT_VALUE'] = "";

                foreach ($data as $row) {
                    if (strtolower($row['DO_BP_PARAM_PATH']) == strtolower($parameter['META_DATA_CODE'])) {
                        $array[$key]['META_PROCESS_PARAM_LINK_ID'] = $row['META_PROCESS_PARAM_LINK_ID'];
                        $array[$key]['MAIN_BP_ID'] = $row['MAIN_BP_ID'];
                        $array[$key]['DO_BP_ID'] = $row['DO_BP_ID'];
                        $array[$key]['DO_BP_PARAM_PATH'] = $row['DO_BP_PARAM_PATH'];
                        $array[$key]['DO_BP_PARAM_IS_INPUT'] = $row['DO_BP_PARAM_IS_INPUT'];
                        $array[$key]['DONE_BP_ID'] = $row['DONE_BP_ID'];
                        $array[$key]['DONE_BP_PARAM_PATH'] = $row['DONE_BP_PARAM_PATH'];
                        $array[$key]['DONE_BP_PARAM_IS_INPUT'] = $row['DONE_BP_PARAM_IS_INPUT'];
                        $array[$key]['DEFAULT_VALUE'] = $row['DEFAULT_VALUE'];
                        break;
                    }
                }
            }

            return $array;
        }

        public function getParameterListWithPathModel($processBpId, $isInput) {
            $metaDatas = array();
            $t = 0;
            $data = $this->db->GetAll("
                SELECT 
                    MD.META_DATA_ID, 
                    MD.META_DATA_NAME, 
                    MPPL.PARAM_NAME AS META_DATA_CODE, 
                    MD.META_TYPE_ID,
                    MPPL.RECORD_TYPE,
                    MPPL.IS_SHOW,
                    MPPL.PARAM_PATH
                FROM 
                    META_PROCESS_PARAM_ATTR_LINK MPPL                                           
                    INNER JOIN META_DATA MD ON MD.META_DATA_ID = MPPL.PARAM_META_DATA_ID 
                    INNER JOIN META_META_MAP MM ON MM.SRC_META_DATA_ID = MPPL.GROUP_META_DATA_ID AND
                                                   MM.TRG_META_DATA_ID = MPPL.PARAM_META_DATA_ID
                WHERE 
                    MPPL.PROCESS_META_DATA_ID = $processBpId AND
                    ".$this->db->IfNull('MPPL.IS_INPUT', '1')." = $isInput AND 
                    MD.META_TYPE_ID IN (" . Mdmetadata::$fieldMetaTypeId . "," . Mdmetadata::$metaGroupMetaTypeId . ") AND
                    MD.IS_ACTIVE = 1
                ORDER BY MPPL.PARAM_PATH ASC");

            foreach ($data as $row) {

                $parentPath = $row['PARAM_PATH'];
                $pos = strripos($row['PARAM_PATH'], ".");

                if ($pos != false) {
                    $parentPath = substr($row['PARAM_PATH'], 0, $pos);
                } else {
                    $parentPath = '';
                }

                $metaDatas[$t]['META_DATA_ID'] = $row['META_DATA_ID'];
                $metaDatas[$t]['META_DATA_NAME'] = str_repeat("-", substr_count($row['PARAM_PATH'], '.')) . $row['META_DATA_NAME'];
                $metaDatas[$t]['META_TYPE_ID'] = $row['META_TYPE_ID'];
                $metaDatas[$t]['RECORD_TYPE_ID'] = $row['RECORD_TYPE'];
                $metaDatas[$t]['IS_SHOW'] = ($row['IS_SHOW'] == 1 ? $row['IS_SHOW'] : 0);
                $metaDatas[$t]['META_DATA_CODE'] = $row['PARAM_PATH'];
                $metaDatas[$t]['PARENT_META_DATA_CODE'] = $parentPath;

                $t++;
            }
            return $metaDatas;
        }

        public function getParameterList($isOutput = 0, $mainBpId, $doBpId, $isShow = 0) {

            $metaDatas = self::getParameterListModel($isOutput, $mainBpId, $doBpId, $isShow);
            $doneBpList = self::getMetaDoneBpListModel($mainBpId, $doBpId);

            return self::drawParameterList($metaDatas, $doneBpList, $mainBpId, $doBpId);
        }

        public function drawParameterList($metaDatas, $doneBpList, $mainBpId, $doBpId, $depth = 0, $path = "") {

            $html = '';
            $pattern = '/^' . ($path == '' ? '' : $path . '[.]') . '[a-zA-Z]*$/';

            foreach ($metaDatas as $row) {
                $mainBpId = $mainBpId;
                $doneBpId = '';
                $doneBpParamIsInput = 0;
                $doneBpParamPath = '';
                $metaProcessParamLinkId = '';
                $defaultValue = '';

                if (isset($row['META_PROCESS_PARAM_LINK_ID']))
                    $metaProcessParamLinkId = $row['META_PROCESS_PARAM_LINK_ID'];

                if (isset($row['DONE_BP_PARAM_PATH'])) {
                    $path = explode('.', $row['DONE_BP_PARAM_PATH']);
                    $parentDoneBpParamId = '';
                    $pathCount = count($path);
                    if ($pathCount > 1) {
                        $pathKey = $pathCount - 2;
                        $parentDoneBpParamId = $path[$pathKey];
                    }
                }

                if (!empty($row['META_PROCESS_PARAM_LINK_ID']) and ( $row['DO_BP_PARAM_PATH'] == $row['META_DATA_CODE'])) {
                    $mainBpId = $row['MAIN_BP_ID'];
                    $doneBpId = $row['DONE_BP_ID'];
                    $doneBpParamIsInput = $row['DONE_BP_PARAM_IS_INPUT'];
                    $doneBpParamPath = $row['DONE_BP_PARAM_PATH'];
                    $defaultValue = $row['DEFAULT_VALUE'];
                }
                
                $oneRowMetaDataCode = str_replace('.', '-', $row['META_DATA_CODE']);
                $_parentMetaDataCode = str_replace('.', '-', $row['PARENT_META_DATA_CODE']);
                $isShow = $row['IS_SHOW'];
                $rowStyle = '';
                
                if (empty($isShow)) {
                    $rowStyle = 'style="display:none;"';
                }
                
                $html .= '<tr class="tabletree-' . $oneRowMetaDataCode . ' tabletree-parent-' . $_parentMetaDataCode . '" data-show="' . $isShow . '" ' . $rowStyle . ' data-row="' . $oneRowMetaDataCode . '" data-row-parent="' . $_parentMetaDataCode . '">';
                $html .= '<td class="middle">';
                $html .= $row['META_DATA_NAME'];
                $html .= Form::hidden(array('name' => $doBpId . 'metaTypeId[]', 'id' => $doBpId . 'metaTypeId', 'value' => $row['META_TYPE_ID']));
                $html .= Form::hidden(array('name' => $doBpId . 'recordType[]', 'id' => $doBpId . 'recordType', 'value' => $row['RECORD_TYPE']));
                $html .= Form::hidden(array('name' => $doBpId . 'inputMetaDataName[]', 'id' => $doBpId . 'inputMetaDataName', 'value' => $row['META_DATA_NAME']));
                $html .= Form::hidden(array('name' => $doBpId . 'inputDoBpParamId[]', 'id' => $doBpId . 'inputDoBpParamId', 'value' => $row['META_DATA_ID']));
                $html .= Form::hidden(array('name' => $doBpId . 'id[]', 'class' => 'id', 'value' => $metaProcessParamLinkId));
                $html .= '</td>';
                $html .= '<td>';
                //Form::text(array('name' => $doBpId . 'inputDoBpParamPath[]', 'id' => $doBpId . 'inputDoBpParamPath', 'value' => $row['META_DATA_CODE'], 'class' => 'form-control', 'readonly' => 'readonly'));
                $html .= Form::text(array('name' => $doBpId . 'inputDoBpParamPath[]', 'id' => $doBpId . 'inputDoBpParamPath', 'value' => $row['META_DATA_CODE'], 'class' => 'form-control form-control-sm', 'readonly' => 'readonly'));
                $html .= '</td>';
                $html .= '<td>';
                $html .= Form::select(
                    array(
                        'name' => $doBpId . 'inputDoneBpId[]',
                        'id' => $doBpId . 'inputDoneBpId',
                        'class' => 'form-control form-control-sm select2me inputDoneBpId ' . $_parentMetaDataCode,
                        'data' => $doneBpList,
                        'op_value' => 'META_DATA_ID',
                        'op_text' => 'META_DATA_NAME',
                        'data-placeholder' => '...',
                        'value' => $doneBpId,
                        'text' => ' ',
                        'required' => 'required'
                    )
                );
                $html .= '</td>';
                $html .= '<td class="middle text-center">';
                $html .= Form::hidden(
                    array(
                        'name' => $doBpId . 'inputDoneBpParamIsInputHidden[]',
                        'id' => $doBpId . 'inputDoneBpParamIsInputHidden',
                        'value' => $doneBpParamIsInput
                    )
                );

                $html .= Form::checkbox(
                    array(
                        'name' => $doBpId . 'inputDoneBpParamIsInput[]',
                        'id' => $doBpId . 'inputDoneBpParamIsInput',
                        'value' => 1,
                        'saved_val' => $doneBpParamIsInput,
                        'class=' => 'doneBpParamIsInput'
                    )
                );

                $html .= '</td>';
                if (!empty($row['DONE_BP_ID'])) {
                    $html .= '<td>';
                    $html .= Form::select(
                        array(
                            'name' => $doBpId . 'inputDoneBpParamId[]',
                            'id' => $doBpId . 'inputDoneBpParamId',
                            'class' => 'form-control form-control-sm select2me',
                            'data' => self::getParameterListWithPathModel($row['DONE_BP_ID'], $row['DONE_BP_PARAM_IS_INPUT']),
                            'op_value' => 'META_DATA_CODE',
                            'op_text' => 'META_DATA_NAME| |-| |META_DATA_CODE',
                            'data-placeholder' => '...',
                            'value' => $doneBpParamPath,
                            'text' => ' ',
                            'required' => 'required'
                        )
                    );
                    $html .= '</td>';
                } else {
                    $html .= '<td>';
                    $html .= Form::select(
                        array(
                            'name' => $doBpId . 'inputDoneBpParamId[]',
                            'id' => $doBpId . 'inputDoneBpParamId',
                            'class' => 'form-control form-control-sm select2me',
                            'op_value' => 'META_DATA_CODE',
                            'op_text' => 'META_DATA_NAME',
                            'data-placeholder' => '...',
                            'value' => $doneBpParamPath,
                            'text' => ' ',
                            'required' => 'required'
                        )
                    );
                    $html .= '</td>';
                }

                $html .= '<td>';
                $html .= Form::text(array('name' => $doBpId . 'inputDoneBpParamPath[]', 'id' => $doBpId . 'inputDoneBpParamPath', 'value' => $doneBpParamPath, 'class' => 'form-control form-control-sm', 'readonly' => 'readonly'));
                $html .= '</td>';
                $html .= '<td>';
                if ($row['META_TYPE_ID'] != Mdmetadata::$metaGroupMetaTypeId) {
                    $html .= Form::text(array('name' => $doBpId . 'defaultValue[]', 'id' => $doBpId . 'defaultValue', 'value' => $defaultValue, 'class' => 'form-control form-control-sm'));
                } else {
                    $html .= Form::hidden(array('name' => $doBpId . 'defaultValue[]', 'id' => $doBpId . 'defaultValue'));
                }
                $html .= '</td>';
                $html .= '<td class="middle text-center">';
                if ($row['META_TYPE_ID'] != Mdmetadata::$metaGroupMetaTypeId)
                    $html .= Form::button(array('class' => 'btn red btn-xs', 'onclick' => 'removeParameter(this)', 'value' => '<i class="fa fa-trash"></i>'));
                $html .= '</td>';
                $html .= '</tr>';
            }
            return $html;
        }

        public function saveMetaProcessModel() {

            $param = array();
            foreach ($data as $k => $val) {

                array_push($param, array(
                    'id' => Input::param($_POST['id'][$k]),
                    'mainBpId' => Input::param($_POST['mainBpId']),
                    'doBpId' => Input::param(trim($_POST['doBpId'][$k])),
                    'isActive' => 1
                        )
                );
            }
            $param = array('sourceLink:MetaProcessWorkflow' => $param);
            $result = $this->ws->runResponse(GF_SERVICE_ADDRESS, "PL_MPW_011", $param);
            return $result;
        }

        public function saveMetaProcessParameterModel() {

            $result = array('status' => 'error', 'message' => Lang::line('msg_error'));
            $mainBpId = Input::numeric('metaDataId');
            $doBpId = Input::post('doProcessId');
            $doBpParamIsInput = Input::post('doBpParamIsInput');
            $data = $_POST[$doBpId . 'inputMetaDataName'];

            $param = array();
            foreach ($data as $k => $val) {
                array_push($param, array(
                    'id' => ((empty($_POST[$doBpId . 'id'][$k])) ? "" : Input::param($_POST[$doBpId . 'id'][$k])),
                    'metaProcessLink' => array('id' => self::getBusinessProcessLinkId($mainBpId), 'rowState' => 'SELECTED'),
                    'doBpId' => $doBpId,
                    'doBpParamPath' => ((empty($_POST[$doBpId . 'inputDoBpParamPath'][$k])) ? "" : Input::param($_POST[$doBpId . 'inputDoBpParamPath'][$k])),
                    'doBpParamIsInput' => $doBpParamIsInput,
                    'doneBpId' => ((empty($_POST[$doBpId . 'inputDoneBpId'][$k])) ? "" : Input::param($_POST[$doBpId . 'inputDoneBpId'][$k])),
                    'doneBpParamPath' => ((empty($_POST[$doBpId . 'inputDoneBpParamPath'][$k])) ? "" : Input::param($_POST[$doBpId . 'inputDoneBpParamPath'][$k])),
                    'doneBpParamIsInput' => (($_POST[$doBpId . 'inputDoneBpParamIsInputHidden'][$k] == '1') ? 1 : 0),
                    'defaultValue' => ((empty($_POST[$doBpId . 'defaultValue'][$k])) ? "" : Input::param($_POST[$doBpId . 'defaultValue'][$k]))
                        )
                );
            }

            if (count($param) != 0) {
                $paramData = array(
                    'mainBpId' => $mainBpId,
                    'sourcelink:metaprocessparam' => $param
                );

                $result = $this->ws->runResponse(GF_SERVICE_ADDRESS, "PL_MPP_011", $paramData);

                if ($result['status'] == 'success') {
                    return array('status' => 'success', 'message' => Lang::line('msg_save_success'));
                } else {
                    $message = $result['text'];
                    $message .= $this->ws->errorReport($result);

                    return array('status' => 'error', 'message' => $message);
                }
            }

            return $result;
        }

        public function getBusinessProcessLinkId($processMetaDataId) {
            return $this->db->GetOne("SELECT ID FROM META_BUSINESS_PROCESS_LINK WHERE META_DATA_ID = $processMetaDataId");
        }

        public function getMetaDoneBpListModel($mainBpId, $doProcessId) {
            $and = " AND MPW.META_PROCESS_WORKFLOW_ID IN (0)";
            $processWorkFlowIds = '0';
            if (Input::postCheck('connection')) {
                $connection = $_POST['connection'];
                foreach ($connection as $row) {
                    $processWorkFlowIds .= ', ' . $row['pageSourceId'];
                }
                $and = " AND MPW.META_PROCESS_WORKFLOW_ID IN ($processWorkFlowIds)";
            }

            if ($doProcessId != $mainBpId) {
                $and .= " OR MPW.DO_BP_ID = " . $mainBpId;
            }

            $data = $this->db->GetAll("
                SELECT DISTINCT
                    MD.META_DATA_ID AS META_DATA_ID,
                    MD.META_DATA_NAME,
                    MD.META_DATA_CODE
                FROM 
                    META_PROCESS_WORKFLOW MPW 
                INNER JOIN META_DATA MD ON MD.META_DATA_ID = MPW.DO_BP_ID
                WHERE 
                    MPW.MAIN_BP_ID = " . $mainBpId . " $and AND  
                    MPW.IS_ACTIVE = 1");
            return $data;
        }

        public function getParamCodeTreeModel($srtMetaDataId, $doneBpParamPath = '') {
            $data = $this->db->GetAll("
                                    SELECT 
                                        MMP.TRG_META_DATA_ID, 
                                        MD.META_DATA_CODE, 
                                        MD.META_TYPE_ID
                                    FROM 
                                        META_META_MAP MMP
                                    INNER JOIN META_DATA MD ON MMP.TRG_META_DATA_ID = MD.META_DATA_ID
                                    WHERE MMP.SRC_META_DATA_ID=" . $srtMetaDataId);

            foreach ($data as $row) {
                self::$metaDatas[self::$t]['META_DATA_ID'] = $row['TRG_META_DATA_ID'];
                self::$metaDatas[self::$t]['META_DATA_CODE'] = $doneBpParamPath . $row['META_DATA_CODE'];
                self::$t++;

                if ($row['META_TYPE_ID'] == Mdmetadata::$metaGroupMetaTypeId) {
                    self::getParamCodeTreeModel($row['TRG_META_DATA_ID'], $row['META_DATA_CODE'] . ".");
                }
            }
            return self::$metaDatas;
        }

        public function getParamCodeModel($srtMetaDataId, $doneBpParamPath = '') {

            self::$metaDatas = array();
            self::$t = 0;

            $data = $this->db->GetAll("
                                    SELECT 
                                        MMP.TRG_META_DATA_ID, 
                                        MD.META_DATA_CODE, 
                                        MD.META_TYPE_ID
                                    FROM 
                                        META_META_MAP MMP
                                    INNER JOIN META_DATA MD ON MMP.TRG_META_DATA_ID = MD.META_DATA_ID
                                    WHERE MMP.SRC_META_DATA_ID=" . $srtMetaDataId);

            foreach ($data as $row) {
                self::$metaDatas[self::$t]['META_DATA_ID'] = $row['TRG_META_DATA_ID'];
                self::$metaDatas[self::$t]['META_DATA_CODE'] = $row['META_DATA_CODE'];
                self::$t++;
                if ($row['META_TYPE_ID'] == Mdmetadata::$metaGroupMetaTypeId) {
                    self::getParamCodeTreeModel($row['TRG_META_DATA_ID'], $row['META_DATA_CODE'] . ".");
                }
            }
            return self::$metaDatas;
        }

        public function getVisualDataListModel($mainBpId) {
            $row = $this->db->GetRow(" SELECT  ADDON_DATA FROM  META_DATA WHERE META_DATA_ID = " . $mainBpId);

            if ($row) {
                return $row['ADDON_DATA'];
            }
            return false;
        }

        public function insertMetaMetaMapModel($srcMetaDataId, $object) {
            $metaData = array();
            $order = 0;
            $id = 0;
            $result = $this->db->GetAll("
                SELECT 
                    ID, TRG_META_DATA_ID, ORDER_NUM
                FROM 
                    META_META_MAP 
                WHERE
                    SRC_META_DATA_ID=" . $srcMetaDataId);
            foreach ($result as $key => $value) {

                if ($order <= $value['ORDER_NUM']) {
                    $order = $value['ORDER_NUM'];
                }

                if ($id <= $value['ID']) {
                    $id = $value['ID'];
                }

                foreach ($object as $key1 => $row) {

                    if ($value['TRG_META_DATA_ID'] == $row['dobpid']) {
                        unset($object[$key1]);
                    }
                }
            }
            foreach ($object as $key1 => $row) {
                if ($row['type'] == 'circle') {
                    unset($object[$key1]);
                }
            }

            if (count($object) > 0) {
                foreach ($object as $key => $value) {
                    $order = $order + 1;
                    $id = $id + 1;
                    $data = array(
                        'ID' => getUID(),
                        'SRC_META_DATA_ID' => $srcMetaDataId,
                        'TRG_META_DATA_ID' => $value['dobpid'],
                        'ORDER_NUM' => $order,
                        'PARAM_CODE' => $value['dobpid']
                    );
                    $result = /* mdm */$this->db->AutoExecute('META_META_MAP', $data);
                }
            }
        }

        public function deleteMetaMetaMapModel($srcMetaDataId, $object) {
            $metaData = array();
            $order = 0;
            $id = 0;
            $result = $this->db->GetAll("
                SELECT 
                    ID, TRG_META_DATA_ID, ORDER_NUM
                FROM 
                    META_META_MAP 
                WHERE
                    SRC_META_DATA_ID=" . $srcMetaDataId);

            foreach ($object as $key1 => $row) {
                foreach ($result as $key => $value) {
                    if ($value['TRG_META_DATA_ID'] == $row['dobpid']) {
                        unset($result[$key]);
                    }
                }
            }
            if (count($result) > 0) {
                foreach ($result as $key => $value) {
                    $this->db->Execute('DELETE FROM META_PROCESS_PARAM_LINK WHERE MAIN_BP_ID=' . $srcMetaDataId . ' and DO_BP_ID = ' . $value['TRG_META_DATA_ID']);
                    $this->db->Execute('DELETE FROM META_META_MAP WHERE ID = ' . $value['ID']);
                }
            }
        }

        public function saveVisualMetaProcessModel($object = '', $connect = '') {
            $mainBpId = Input::post('mainBpId');
//@ Erdenebaatar - 2016-01-27            
//Visual orchnoos process nemehed meta_meta_map ruu insert hiih shaardlagagui geed tailbar bolgov
//            self::insertMetaMetaMapModel($mainBpId, $object);
//            self::deleteMetaMetaMapModel($mainBpId, $object);
            $addOnData = json_encode(array('object' => $object, 'connect' => $connect));
            $result = /* mdm */$this->db->UpdateClob('META_DATA', 'ADDON_DATA', $addOnData, 'META_DATA_ID=' . $mainBpId);
            $param = array();
            $ProcessWorkFlow = $this->db->GetAll("SELECT META_PROCESS_WORKFLOW_ID, DO_BP_ID, MAIN_BP_ID, BP_ORDER FROM META_PROCESS_WORKFLOW WHERE MAIN_BP_ID=" . $mainBpId);
            foreach ($object as $key => $value) {
                $metaProcessWorkFlowId = Input::param($value['metaProcessWorkFlowId']);
                if ($value['type'] != 'circle') {
                    $result = $this->db->GetRow("SELECT META_PROCESS_WORKFLOW_ID FROM META_PROCESS_WORKFLOW WHERE MAIN_BP_ID=$mainBpId AND BP_ORDER=" . $value['bpOrder']);
                    $data = array(
                        'MAIN_BP_ID' => $mainBpId,
                        'DO_BP_ID' => Input::param($value['dobpid']),
                        'BP_ORDER' => Input::param($value['bpOrder']),
                        'IS_ACTIVE' => 1,
                        'CREATED_DATE' => Date::currentDate("Y-m-d H:i:s"),
                        'CREATED_USER_ID' => Ue::sessionUserId()
                    );
                    if (count($result) === 0 and $mainBpId != $value['dobpid']) {
                        $data = array_merge($data, array('META_PROCESS_WORKFLOW_ID' => getUID()));
                        $this->db->AutoExecute('META_PROCESS_WORKFLOW', $data);
                        $metaProcessWFID = '';
                        foreach ($connect as $r) {
                            if ($value['id'] === $r['pageTargetId']) {
                                foreach ($object as $rr) {
                                    if ($r['pageSourceId'] === $rr['id']) {
                                        $metaProcessWFID = $rr['metaProcessWorkFlowId'];
                                    }
                                }
                            }
                        }
                    } elseif ($mainBpId != $value['dobpid']) {
                        $this->db->AutoExecute('META_PROCESS_WORKFLOW', $data, 'UPDATE', "META_PROCESS_WORKFLOW_ID = " . $result['META_PROCESS_WORKFLOW_ID']);
                    }
                }

                foreach ($ProcessWorkFlow as $k => $row) {
                    if ($row['DO_BP_ID'] == $value['dobpid'] and $value['type'] != 'circle') {
                        unset($ProcessWorkFlow[$k]);
                    }
                    if ($row['DO_BP_ID'] === $row['MAIN_BP_ID']) {
                        unset($ProcessWorkFlow[$k]);
                    }
                }
            }
            if (count($ProcessWorkFlow) > 0) {
                foreach ($ProcessWorkFlow as $key => $row) {
                    $wfList = $this->db->GetAll("SELECT META_PROCESS_WORKFLOW_ID FROM META_PROCESS_WORKFLOW WHERE MAIN_BP_ID=" . $mainBpId);
                    foreach ($wfList as $wfr) {
                        $resultBehaviour = $this->db->GetRow("SELECT ID FROM META_PROCESS_WF_BEHAVIOUR WHERE META_PROCESS_WF_ID=" . $wfr['META_PROCESS_WORKFLOW_ID'] . " AND NEXT_ORDER=" . $row['BP_ORDER']);
                        if (count($resultBehaviour) > 0) {
                            $this->db->Execute('DELETE FROM META_PROCESS_WF_BEHAVIOUR WHERE ID=' . $resultBehaviour['ID']);
                        }
                    }
                    $this->db->Execute('DELETE FROM META_PROCESS_WF_BEHAVIOUR WHERE META_PROCESS_WF_ID=' . $row['META_PROCESS_WORKFLOW_ID']);

                    $this->db->Execute('DELETE FROM META_PROCESS_WORKFLOW WHERE MAIN_BP_ID=' . $mainBpId . ' and DO_BP_ID = ' . $row['DO_BP_ID']);
                }
            }
            return array('status' => 'success');
        }

        protected function getVisualMetaIndex($connect, $object, $val, $metaBoolen = 0) {
            $pageTargetId = '';
            if ($metaBoolen === 0) {
                foreach ($connect as $key => $value) {
                    if ($value['pageSourceId'] == $val) {
                        $pageTargetId = $value['pageTargetId'];
                    }
                }
            } else {
                $pageTargetId = $val;
            }

            if ($pageTargetId != '') {
                foreach ($object as $key => $value) {
                    if ($value['id'] == $pageTargetId) {
                        if (!empty($value['bpOrder'])) {
                            if ($value['bpOrder'] == 'undefined') {
                                return null;
                            }
                            return $value['bpOrder'];
                        } else {
                            return null;
                        }
                    }
                }
            }
            return null;
        }

        protected function checkIsStart($data, $id) {

            $startObject = '';

            foreach ($data as $key => $value) {
                if ($value['pageSourceId'] == 'startObject001') {
                    $startObject = $value['pageTargetId'];
                }
            }

            if ($startObject == $id) {
                return 1;
            }

            return 0;
        }

        public function getOneMetaDataName($metaDataId) {
            if ($metaDataId == 'startObject001' || $metaDataId == 'endObject001') {
                return '';
            }
            return $this->db->GetOne("SELECT META_DATA_NAME FROM META_DATA WHERE META_DATA_ID = " . Input::param($metaDataId));
        }

        public function getWorkFlowId($mainBpId, $bpOrder) {
            return $this->db->GetOne("SELECT META_PROCESS_WORKFLOW_ID FROM META_PROCESS_WORKFLOW WHERE MAIN_BP_ID = " . Input::param($mainBpId) . " AND BP_ORDER=" . Input::param($bpOrder));
        }

        public function deleteProcessParameterModel($id) {
            $param = array("id" => $id);
            $result = $this->ws->runResponse(GF_SERVICE_ADDRESS, "PL_MPP_005", $param);
            return $result;
        }

        public function metaTypeProcessListModel() {
//             AND 
//                META_DATA_ID IN (SELECT SRC_META_DATA_ID FROM META_META_MAP)
            $data = $this->db->GetAll("
                SELECT 
                    META_DATA_ID, 
                    META_DATA_NAME, 
                    META_DATA_CODE 
                FROM META_DATA
                WHERE 
                    META_TYPE_ID  IN(" . Mdmetadata::$businessProcessMetaTypeId . ", " . Mdmetadata::$expressionMetaTypeId . ") AND 
                    IS_ACTIVE = 1
                ORDER BY 
                    META_DATA_NAME 
                ASC");
            return $data;
        }

        public function getLifecycleEntity004Model($entityId, $sourceId) {
            $paramData = array(
                'entityId' => $entityId,
                'sourceId' => $sourceId
            );
            return $result = $this->ws->runResponse(GF_SERVICE_ADDRESS, "META_LIFECYCLE_003", $paramData);
        }

        public function saveBpCriteriaModel() {
            $data = array(
                'CRITERIA' => Input::postNonTags('bpCriteria')
            );
            $result = /* mdm */$this->db->AutoExecute('META_PROCESS_WF_BEHAVIOUR', $data, 'UPDATE', "ID = " . Input::post('bpCriteriaId'));
            if ($result) {
                return array(
                    'status' => 'success',
                    'message' => Lang::line('msg_save_success')
                );
            } else {
                return array(
                    'status' => 'error',
                    'message' => 'Алдаа гарлаа'
                );
            }
        }

        public function checkMetaProcessBehaviourProcess($mainBpId, $object, $result, $sourceId) {
            
        }

        public function getOneObject($object, $objectId) {
            if ($objectId === 'startObject001' or $objectId === 'endObject001') {
                return array('bpOrder' => 0);
            }
            foreach ($object as $row) {
                if ($row['id'] === $objectId) {
                    return $row;
                }
            }
            return array('bpOrder' => 0);
        }

        public function checkGenerateMetaProcessWfBehaviourModel($object, $mainBpId) {

            $result = $this->db->GetAll("SELECT DO_BP_ID, META_PROCESS_WORKFLOW_ID FROM META_PROCESS_WORKFLOW WHERE MAIN_BP_ID = $mainBpId");
            $mainArr = '0';
            if ($result) {
                foreach ($result as $row) {
                    $mainArr .= ',' . $row['META_PROCESS_WORKFLOW_ID'];
                }
            }

            $this->db->Execute('DELETE FROM META_PROCESS_WF_BEHAVIOUR WHERE META_PROCESS_WF_ID IN (' . $mainArr . ')');
            foreach ($object['connect'] as $row) {
                $metaProcessWfId = $this->db->GetOne("select META_PROCESS_WORKFLOW_ID From META_PROCESS_WORKFLOW where DO_BP_ID = " . $row['pageSourceId']);
                $data = array(
                    'ID' => getUID(),
                    'META_PROCESS_WF_ID' => $metaProcessWfId,
                    'NEXT_META_PROCESS_WF_ID' => $row['pageTargetId'],
                    'CRITERIA' => ''
                );
                $this->db->AutoExecute('META_PROCESS_WF_BEHAVIOUR', $data);
            }
        }

        public function deleteArrowModel($mainBpId, $doBpId) {
            $row = $this->db->GetRow("
                SELECT 
                    META_PROCESS_WORKFLOW_ID 
                FROM META_PROCESS_WORKFLOW 
                WHERE 
                    DO_BP_ID = $doBpId AND 
                    MAIN_BP_ID = $mainBpId");
            if ($row) {
                $result = $this->db->Execute('DELETE FROM META_PROCESS_WF_BEHAVIOUR WHERE META_PROCESS_WF_ID = ' . $row['META_PROCESS_WORKFLOW_ID']);
                if ($result) {
                    return array('status' => 'success', 'message' => Lang::line('msg_delete_success'));
                } else {
                    return array('status' => 'error', 'message' => Lang::line('msg_delete_error'));
                }
            }

            return array('status' => 'success', 'message' => Lang::line('msg_delete_success'));
        }

        public function getWorkFlowModel() {
            $metaDataId = Input::numeric('metaDataId');
            $data = $this->db->GetAll("
                SELECT 
                    WFM.ID, 
                    WFM.WFM_WORKFLOW_NAME 
                FROM META_WFM_WORKFLOW WFM
                WHERE WFM.IS_ACTIVE = 1 
                    AND WFM.REF_STRUCTURE_ID = $metaDataId 
                ORDER BY WFM.ID");

            $transitionId = $this->db->GetOne("SELECT DISTINCT WT.ID, WS.WFM_STATUS_NAME
                                        FROM META_WFM_WORKFLOW WW
                                        INNER JOIN META_WFM_STATUS WS ON WW.ID = WS.WFM_WORKFLOW_ID
                                        INNER JOIN META_WFM_TRANSITION WT ON WS.ID = WT.NEXT_WFM_STATUS_ID
                                        WHERE REF_STRUCTURE_ID = $metaDataId AND PREV_WFM_STATUS_ID IS NULL AND IS_TRANSITION = 0
                                        ORDER BY WS.WFM_STATUS_NAME");
            $wfmStatusArr = $this->db->GetAll("SELECT
                                                    WWS.ID AS WFM_STATUS_ID,
                                                    WWS.WFM_STATUS_NAME,
                                                    WWS.WFM_STATUS_CODE,
                                                    WWS.WFM_STATUS_COLOR
                                                FROM
                                                    META_WFM_WORKFLOW WFM
                                                INNER JOIN META_WFM_STATUS WWS ON WFM.ID = WWS.WFM_WORKFLOW_ID
                                                WHERE WFM.IS_ACTIVE = 1 AND WFM.REF_STRUCTURE_ID = $metaDataId AND WWS.IS_ACTIVE = 1
                                                ORDER BY WWS.WFM_STATUS_NAME");
            if ($transitionId) {
                return array('data' => $data, 'transitionId' => $transitionId, 'workFlowId' => $data[0]['ID'], 'workFlowStatus' => $wfmStatusArr, 'status' => 'success');
            } else
                return array('status' => 'success', 'data' => array(), 'workFlowStatus' => array());
        }

        public function getWorkFlowStatusModel() {
            $metaDataId = Input::numeric('metaDataId');
            $workFlowId = Input::post('workFlowId');

            return $this->db->GetAll("  SELECT DISTINCT WFMS.ID, WFMS.WFM_STATUS_NAME, WFMS.WFM_STATUS_COLOR, 'rectangle' AS TYPE, WFMS.WFM_STATUS_CODE
                                        FROM ( 
                                        SELECT WFM.ID FROM META_WFM_WORKFLOW WFM
                                            WHERE WFM.IS_ACTIVE = 1 AND WFM.REF_STRUCTURE_ID = $metaDataId AND WFM.ID = $workFlowId
                                        ) WFM
                                        INNER JOIN META_WFM_STATUS WFMS ON WFM.ID = WFMS.WFM_WORKFLOW_ID
                                        LEFT JOIN META_WFM_STATUS_PERMISSION WFMSP ON WFMS.ID  = WFMSP.WFM_STATUS_ID
                                        WHERE WFMS.IS_ACTIVE = 1  ORDER BY WFMS.ID ASC");
        }

        public function getWorkFlowStatusTransitionModel() {
            $metaDataId = Input::numeric('metaDataId');
            $workFlowId = Input::post('workFlowId');

            $data = $this->db->GetAll("SELECT 
                                            CASE WHEN TEMP.NEXT_WFM_STATUS_ID IS NULL THEN 'endObject001' ELSE TEMP.NEXT_WFM_STATUS_ID END AS NEXT_WFM_STATUS_ID,
                                            CASE WHEN TEMP.PREV_WFM_STATUS_ID IS NULL THEN 'startObject001' ELSE TEMP.PREV_WFM_STATUS_ID END AS PREV_WFM_STATUS_ID,
                                            TEMP.DESCRIPTION
                                            FROM
                                              ( SELECT  
                                                DISTINCT 
                                                TRAN.DESCRIPTION,
                                                TO_CHAR(TRAN.NEXT_WFM_STATUS_ID) AS NEXT_WFM_STATUS_ID,
                                                TO_CHAR(TRAN.PREV_WFM_STATUS_ID) AS PREV_WFM_STATUS_ID,
                                                STA.IS_ACTIVE  AS NEXT_ACTIVE ,
                                                STA1.IS_ACTIVE AS PREV_ACTIVE,
                                                CASE
                                                  WHEN TRAN.NEXT_WFM_STATUS_ID IS NULL
                                                  THEN 1
                                                  WHEN STA.IS_ACTIVE = 1
                                                  THEN 1
                                                  ELSE 0
                                                END AS NSTATUS,
                                                CASE
                                                  WHEN TRAN.PREV_WFM_STATUS_ID IS NULL
                                                  THEN 1
                                                  WHEN STA1.IS_ACTIVE = 1
                                                  THEN 1
                                                  ELSE 0
                                                END AS PSTATUS
                                              FROM META_WFM_TRANSITION TRAN
                                              LEFT JOIN META_WFM_STATUS STA ON TRAN.NEXT_WFM_STATUS_ID = STA.ID AND STA.IS_ACTIVE          = 1
                                              LEFT JOIN META_WFM_STATUS STA1
                                              ON TRAN.PREV_WFM_STATUS_ID     = STA1.ID AND STA1.IS_ACTIVE             = 1
                                              WHERE TRAN.PREV_WFM_STATUS_ID IN
                                                (
                                                SELECT WFMS.ID
                                                FROM META_WFM_WORKFLOW WFM
                                                INNER JOIN META_WFM_STATUS WFMS ON WFM.ID = WFMS.WFM_WORKFLOW_ID
                                                WHERE WFM.IS_ACTIVE      = 1
                                                  AND WFMS.IS_ACTIVE       = 1
                                                  AND WFM.REF_STRUCTURE_ID = $metaDataId
                                                  AND WFM.ID               = $workFlowId
                                                )
                                              OR TRAN.NEXT_WFM_STATUS_ID IN (   
                                                    SELECT WFMS.ID FROM META_WFM_WORKFLOW WFM
                                                    INNER JOIN META_WFM_STATUS WFMS ON WFM.ID = WFMS.WFM_WORKFLOW_ID
                                                    WHERE WFM.IS_ACTIVE      = 1
                                                      AND WFMS.IS_ACTIVE       = 1
                                                      AND WFM.REF_STRUCTURE_ID = $metaDataId
                                                      AND WFM.ID               = $workFlowId
                                                )
                                              ) TEMP
                                            WHERE TEMP.nstatus = 1
                                            AND TEMP.pstatus   = 1 ORDER BY TEMP.PREV_WFM_STATUS_ID DESC");
            return $data;
        }

        public function createWfmWorkFlowModel() {
            $response = array('status' => 'warning', 'message' => 'Амжилтгүй боллоо.');
            $metaDataId = Input::numeric('metaDataId');
            $workFlowId = $this->db->GetOne("SELECT ID FROM META_WFM_WORKFLOW WHERE REF_STRUCTURE_ID = $metaDataId");
            if (Input::postCheck('wfmStatusId') && Input::isEmpty('wfmStatusId') === false) {
                $wfmStatusId = Input::post('wfmStatusId');
            } else {
                if ($workFlowId) {
                    $workFlowId = $workFlow;
                } else {
                    $currentDate = Date::currentDate();
                    $sessionUserId = Ue::sessionUserKeyId();

                    $workFlowId = getUID();
                    $metaData = $this->db->GetRow("SELECT META_DATA_CODE, META_DATA_NAME FROM META_DATA WHERE META_DATA_ID = $metaDataId");
                    $workFlowData = array(
                        'ID' => $workFlowId,
                        'WFM_WORKFLOW_CODE' => $metaData['META_DATA_CODE'],
                        'WFM_WORKFLOW_NAME' => $metaData['META_DATA_NAME'] . ' ажлын урсгал',
                        'IS_ACTIVE' => '1',
                        'CREATED_USER_ID' => $sessionUserId,
                        'CREATED_DATE' => $currentDate,
                        'REF_STRUCTURE_ID' => $metaDataId,
                    );
                    $result = $this->db->AutoExecute("META_WFM_WORKFLOW", $workFlowData);
                    if (!$result) {
                        return $response;
                        die;
                    }
                    $wfmStatusId = getUID();
                    $data = array(
                        'ID' => $wfmStatusId,
                        'WFM_STATUS_CODE' => Input::post('wfmStatusCode'),
                        'WFM_STATUS_NAME' => Input::post('wfmStatusName'),
                        'PROCESS_NAME' => Input::post('wfmProcessName'),
                        'WFM_STATUS_COLOR' => Input::post('wfmStatusColor'),
                        'IS_ACTIVE' => '1',
                        'CREATED_USER_ID' => $sessionUserId,
                        'CREATED_DATE' => $currentDate,
                        'WFM_WORKFLOW_ID' => $workFlowId,
                        'PROCESS_META_DATA_ID' => Input::post('wfmProcessId'),
                        'IS_NEED_SIGN' => Input::postCheck('wfmIsSign') ? '1' : '0',
                        'IS_DESC_REQUIRED' => Input::postCheck('isDescRequired') ? '1' : '0',
                        'IS_SEND_MAIL' => Input::postCheck('isSendMail') ? '1' : '0',
                        'IS_CHECK_ASSIGN_CRITERIA' => 1
                    );
                    $result = $this->db->AutoExecute('META_WFM_STATUS', $data);
                }
            }
            $usedTransitionId = $this->db->GetOne("SELECT COUNT(*) AS COUNTT FROM META_WFM_TRANSITION WHERE (PREV_WFM_STATUS_ID IN ($wfmStatusId) OR NEXT_WFM_STATUS_ID IN ($wfmStatusId))");
            if ($usedTransitionId != '0') {
                return array('status' => 'warning', 'message' => 'Ашигласан төлөв сонгогдсон байна', 'workFlowId' => $workFlowId);
            } else {
                $transitionId = getUID();
                $data = array(
                    'ID' => $transitionId,
                    'NEXT_WFM_STATUS_ID' => $wfmStatusId,
                    'WFM_WORKFLOW_ID' => $workFlowId,
                    'TOP' => '',
                    'LEFT' => '',
                    'CRITERIA' => Input::post('bpCriteria'),
                    'DESCRIPTION' => Input::post('wfmStatusName'),
                );
                $result = $this->db->AutoExecute('META_WFM_TRANSITION', $data);
                if ($result) {
                    $response = array('status' => 'success', 'transitionId' => $transitionId, 'message' => 'Амжилттай хадгаллаа.');
                }
                return $response;
            }
        }

        public function updateWfmWorkFlowTransitionModel() {
            $response = array('status' => 'warning', 'message' => 'Амжилтгүй боллоо.');

            $data = array(
                'NEXT_WFM_STATUS_ID' => Input::post('wfmStatusId'),
                'CRITERIA' => Input::post('bpCriteria'),
                'DESCRIPTION' => Input::post('wfmStatusName'),
            );
            $result = $this->db->AutoExecute('META_WFM_TRANSITION', $data, 'UPDATE', ' ID = ' . Input::post('transitionId'));
            if ($result) {
                $response = array('status' => 'success', 'message' => 'Амжилттай хадгаллаа.');
            }
            return $response;
        }

        public function createWfmStatusModel() {
            $response = array('status' => 'warning', 'message' => 'Амжилтгүй боллоо.');
            if (Input::postCheck('metaDataId')) {
                if (Input::isEmpty('metaDataId') === false) {
                    $wfmStatusId = getUID();
                    $metaDataId = Input::numeric('metaDataId');
                    $workFlowId = $this->db->GetOne("SELECT ID FROM META_WFM_WORKFLOW WHERE REF_STRUCTURE_ID = $metaDataId");
                    $data = array(
                        'ID' => $wfmStatusId,
                        'WFM_STATUS_CODE' => Input::post('wfmStatusCode'),
                        'WFM_STATUS_NAME' => Input::post('wfmStatusName'),
                        'PROCESS_NAME' => Input::post('wfmProcessName'),
                        'WFM_STATUS_COLOR' => Input::post('wfmStatusColor'),
                        'IS_ACTIVE' => '1',
                        'CREATED_USER_ID' => Ue::sessionUserId(),
                        'CREATED_DATE' => Date::currentDate(),
                        'WFM_WORKFLOW_ID' => $workFlowId,
                        'PROCESS_META_DATA_ID' => Input::post('wfmProcessId'),
                        'IS_NEED_SIGN' => Input::postCheck('wfmIsSign') ? '1' : '0',
                        'IS_DESC_REQUIRED' => Input::postCheck('isDescRequired') ? '1' : '0',
                        'IS_SEND_MAIL' => Input::postCheck('isSendMail') ? '1' : '0',
                        'IS_CHECK_ASSIGN_CRITERIA' => 1
                    );
                    $result = $this->db->AutoExecute('META_WFM_STATUS', $data);
                    if ($result) {
                        $response = self::getTransitionNewListDataModel(Input::post('transitionId'));
                        $wfmStatusIds = implode(',', $response['wfmStatusArr']);
                        $workFlowIds = self::getMetaWfmStatusId($wfmStatusIds, $metaDataId);
                        $response = array('status' => 'success', 'wfmStatusId' => $wfmStatusId, 'workFlowStatus' => $workFlowIds, 'message' => 'Амжилттай хадгаллаа.');
                    }
                }
            }
            return $response;
        }

        public function createNewWfmStatusModel() {
            $response = array('status' => 'warning', 'message' => 'Амжилтгүй боллоо.');
            if (Input::postCheck('metaDataId')) {
                if (Input::isEmpty('metaDataId') === false) {
                    $wfmStatusId = getUID();
                    $metaDataId = Input::numeric('metaDataId');
                    $workFlowId = $this->db->GetOne("SELECT ID FROM META_WFM_WORKFLOW WHERE REF_STRUCTURE_ID = $metaDataId");
                    if (!$workFlowId) {
                        $workFlowId = getUID();
                        $metaData = $this->db->GetRow("SELECT META_DATA_CODE, META_DATA_NAME FROM META_DATA WHERE META_DATA_ID = $metaDataId");
                        $workFlowData = array(
                            'ID' => $workFlowId,
                            'WFM_WORKFLOW_CODE' => $metaData['META_DATA_CODE'],
                            'WFM_WORKFLOW_NAME' => $metaData['META_DATA_NAME'] . ' ажлын урсгал',
                            'IS_ACTIVE' => '1',
                            'CREATED_USER_ID' => $sessionUserId,
                            'CREATED_DATE' => $currentDate,
                            'REF_STRUCTURE_ID' => $metaDataId,
                        );
                        $result = $this->db->AutoExecute("META_WFM_WORKFLOW", $workFlowData);
                    }
                    $data = array(
                        'ID' => $wfmStatusId,
                        'WFM_STATUS_CODE' => Input::post('wfmStatusCode'),
                        'WFM_STATUS_NAME' => Input::post('wfmStatusName'),
                        'PROCESS_NAME' => Input::post('wfmProcessName'),
                        'WFM_STATUS_COLOR' => Input::post('wfmStatusColor'),
                        'IS_ACTIVE' => '1',
                        'CREATED_USER_ID' => Ue::sessionUserId(),
                        'CREATED_DATE' => Date::currentDate(),
                        'WFM_WORKFLOW_ID' => $workFlowId,
                        'PROCESS_META_DATA_ID' => Input::post('wfmProcessId'),
                        'IS_NEED_SIGN' => Input::postCheck('wfmIsSign') ? '1' : '0',
                        'IS_DESC_REQUIRED' => Input::postCheck('isDescRequired') ? '1' : '0',
                        'IS_SEND_MAIL' => Input::postCheck('isSendMail') ? '1' : '0',
                        'IS_CHECK_ASSIGN_CRITERIA' => 1
                    );
                    $result = $this->db->AutoExecute('META_WFM_STATUS', $data);
                    if ($result) {
                        $response = array('status' => 'success', 'wfmStatusId' => $wfmStatusId, 'workFlowStatus' => $data, 'message' => 'Амжилттай хадгаллаа.');
                    }
                }
            }
            return $response;
        }

        public function saveWfmStatusModel() {
            $response = array('status' => 'warning', 'message' => 'Амжилтгүй боллоо.');
            if (Input::postCheck('workFlowId')) {
                if (Input::isEmpty('workFlowId') === false) {
                    $wfmStatusId = getUID();
                    $data = array(
                        'ID' => $wfmStatusId,
                        'WFM_STATUS_CODE' => Input::post('wfmStatusCode'),
                        'WFM_STATUS_NAME' => Input::post('wfmStatusName'),
                        'PROCESS_NAME' => Input::post('wfmProcessName'),
                        'WFM_STATUS_COLOR' => Input::post('wfmStatusColor'),
                        'IS_ACTIVE' => '1',
                        'CREATED_USER_ID' => Ue::sessionUserId(),
                        'CREATED_DATE' => Date::currentDate(),
                        'WFM_WORKFLOW_ID' => Input::post('workFlowId'),
                        'PROCESS_META_DATA_ID' => Input::post('wfmProcessId'),
                        'IS_NEED_SIGN' => Input::postCheck('wfmIsSign') ? '1' : '0',
                        'IS_DESC_REQUIRED' => Input::postCheck('isDescRequired') ? '1' : '0',
                        'IS_SEND_MAIL' => Input::postCheck('isSendMail') ? '1' : '0',
                        'IS_CHECK_ASSIGN_CRITERIA' => 1
                    );
                    $result = $this->db->AutoExecute('META_WFM_STATUS', $data);
                    if ($result) {
                        $response = array('status' => 'success', 'wfmStatusId' => $wfmStatusId, 'message' => 'Амжилттай хадгаллаа.');
                    }
                }
            }
            return $response;
        }

        public function updatecModel() {
            $response = array('status' => 'warning', 'message' => 'Амжилтгүй боллоо.');
            if (Input::postCheck('wfmStatusId')) {
                if (Input::isEmpty('wfmStatusId') === false) {
                    $wfmStatusId = Input::post('wfmStatusId');
                    $data = array(
                        'LEFT' => Input::post('positionLeft'),
                        'TOP' => Input::post('positionTop'),
                    );
                    $result = $this->db->AutoExecute('META_WFM_STATUS', $data, 'UPDATE', "ID = $wfmStatusId");
                    if ($result) {
                        $response = array('status' => 'success', 'wfmStatusId' => $wfmStatusId, 'message' => 'Амжилттай хадгаллаа.');
                    }
                }
            }
            return $response;
        }

        public function updateWorkflowStatusTransitionModel() {
            $response = array('status' => 'warning', 'message' => 'Амжилтгүй боллоо.');
            if (Input::postCheck('source') || Input::postCheck('target')) {
                if (Input::isEmpty('source') === false || Input::isEmpty('target') === false) {
                    $data = array(
                        'ID' => getUID(),
                        'PREV_WFM_STATUS_ID' => Input::post('source'),
                        'NEXT_WFM_STATUS_ID' => Input::post('target'),
                    );
                    $result = $this->db->AutoExecute('META_WFM_TRANSITION', $data);
                    if ($result) {
                        $response = array('status' => 'success', 'transitionId' => $data['ID'], 'message' => 'Амжилттай хадгаллаа.');
                    }
                }
            }
            return $response;
        }

        public function deleteStatusArrowModel() {
            $response = array('status' => 'warning', 'message' => 'Амжилтгүй боллоо.');
            $ticket = false;

            $sourceId = Input::post('sourceId');
            $targetId = Input::post('targetId');
            $where = "WHERE 1 = 1";

            if ($sourceId === 'startObject001')
                $where .= " AND PREV_WFM_STATUS_ID IS NULL ";
            else
                $where .= " AND PREV_WFM_STATUS_ID = $sourceId ";
            if ($targetId === 'endObject001')
                $where .= " AND NEXT_WFM_STATUS_ID IS NULL ";
            else
                $where .= " AND NEXT_WFM_STATUS_ID = $targetId";

            $transitionIds = $this->db->GetAll("SELECT ID FROM META_WFM_TRANSITION $where");
            foreach ($transitionIds as $ids) {
                $ticket = false;
                $result = $this->db->Execute("DELETE FROM META_WFM_TRANSITION WHERE ID = " . $ids['ID']);
                if ($result)
                    $ticket = true;
            }

            if ($ticket) {
                $response = array('status' => 'success', 'message' => 'Амжилттай устгагдлаа.');
            }
            return $response;
        }

        public function deleteWorkflowStatusModel() {
            $response = array('status' => 'warning', 'message' => 'Амжилтгүй боллоо. /POST DATA ERROR/');

            if (Input::postCheck('wfmStatusId')) {
                $checkRes = $this->db->GetOne("SELECT FNC_GET_REFERENCED_RECORDS('META_WFM_STATUS', '" . Input::post('wfmStatusId') . "', '''META_WFM_TRANSITION'',''META_WFM_STATUS_PERMISSION'',''META_WFM_STATUS_ASSIGNMENT'',''META_WFM_LOG''') FROM DUAL");

                if ($checkRes == null) {
                    $result = $this->db->Execute("DELETE FROM META_WFM_STATUS WHERE ID = " . Input::post('wfmStatusId'));

                    if ($result) {
                        $response = array('status' => 'success', 'message' => 'Амжилттай устгагдлаа.');
                    }
                } else
                    $response = array('status' => 'warning', 'message' => 'Холбоотой өгөгдөл байна устгах боломжгүй! <br> /' . $checkRes . '/');
            }
            return $response;
        }

        public function wfmCriteriaModel($targetId, $sourceId, $transitionId) {
            return $this->db->GetRow("SELECT ID, CRITERIA, DESCRIPTION FROM META_WFM_TRANSITION WHERE 1 = 1 AND PREV_WFM_STATUS_ID = $sourceId AND NEXT_WFM_STATUS_ID = $targetId AND SOURCE_ID = $transitionId");
        }

        public function saveWfmCriteriaModel() {
            $data = array(
                'CRITERIA' => Input::postNonTags('bpCriteria'),
                'DESCRIPTION' => Input::postNonTags('transitionDescription'),
            );
            $result = $this->db->AutoExecute('META_WFM_TRANSITION', $data, 'UPDATE', "ID = " . Input::post('bpCriteriaId'));
            if ($result) {
                return array(
                    'status' => 'success',
                    'message' => Lang::line('msg_save_success'),
                    'data' => $data
                );
            } else {
                return array(
                    'status' => 'error',
                    'message' => 'Алдаа гарлаа'
                );
            }
        }

        public function getWfmStatusModel($wfmStatusId) {
            return $this->db->GetRow("SELECT ID, WFM_STATUS_CODE, WFM_STATUS_NAME, PROCESS_NAME, WFM_WORKFLOW_ID, IS_ACTIVE, WFM_STATUS_COLOR, PROCESS_META_DATA_ID FROM META_WFM_STATUS WHERE ID = $wfmStatusId");
        }

        public function filterUserInfoModel() {
            $response = array();
            (Array) $param = array(
                'systemMetaGroupId' => '1457267529956193',
                'showQuery' => 1
            );
            $data = $this->ws->runResponse(GF_SERVICE_ADDRESS, Mddatamodel::$getDataViewCommand, $param);
            if ($data['status'] == 'success') {
                if (isset($data['result'])) {
                    $result = $this->db->GetAll("SELECT * FROM (" . $data['result'] . ") TT WHERE LOWER(TT.USERNAME) LIKE LOWER('%" . Input::post('q') . "%')");
                    if ($result)
                        $response = array('items' => $result);
                }
            }
            return $response;
        }

        public function filterRoleInfoModel() {
            $response = array();
            (Array) $param = array(
                'systemMetaGroupId' => '1457174283509032',
                'showQuery' => 1
            );
            $data = $this->ws->runResponse(GF_SERVICE_ADDRESS, Mddatamodel::$getDataViewCommand, $param);
            if ($data['status'] == 'success') {
                if (isset($data['result'])) {
                    $result = $this->db->GetAll("SELECT * FROM (" . $data['result'] . ") TT WHERE LOWER(TT.ROLENAME) LIKE LOWER('%" . Input::post('q') . "%')");
                    if ($result)
                        $response = array('items' => $result);
                }
            }
            return $response;
        }

        public function addTransitionUserPermissionModel() {
            $response = array('status' => 'warning', 'message' => 'Амжилтгүй боллоо');

            $sourceId = Input::post('sourceId');
            $targetId = Input::post('targetId');
            $userId = Input::post('userId');
            $transitionRow = $this->wfmCriteriaModel($targetId, $sourceId);

            $data = array(
                'ID' => getUID(),
                'WFM_TRANSITION_ID' => $transitionRow['ID'],
                'USER_ID' => $userId,
            );
            $checkData = $this->getCheckTransitionPermissionModel('USER_ID', $userId, $transitionRow['ID']);
            if ($checkData) {
                $result = $this->db->AutoExecute('META_WFM_TRANSITION_PERMISSION', $data);
                if ($result) {
                    $response = array('status' => 'success', 'message' => 'Амжилттай хадгаллаа');
                }
            }
            return $response;
        }

        public function addTransitionRolePermissionModel() {
            $response = array('status' => 'warning', 'message' => 'Амжилтгүй боллоо');

            $sourceId = Input::post('sourceId');
            $targetId = Input::post('targetId');
            $roleId = Input::post('roleId');
            $transitionRow = $this->wfmCriteriaModel($targetId, $sourceId);

            $data = array(
                'ID' => getUID(),
                'WFM_TRANSITION_ID' => $transitionRow['ID'],
                'ROLE_ID' => $roleId,
            );
            $checkData = $this->getCheckTransitionPermissionModel('ROLE_ID', $roleId, $transitionRow['ID']);
            if ($checkData) {
                $result = $this->db->AutoExecute('META_WFM_TRANSITION_PERMISSION', $data);
                if ($result) {
                    $response = array('status' => 'success', 'message' => 'Амжилттай хадгаллаа');
                }
            }
            return $response;
        }

        public function addStatusUserPermissionModel() {
            $response = array('status' => 'warning', 'message' => 'Амжилтгүй боллоо');

            $wfmStatusId = Input::post('wfmStatusId');
            $userId = Input::post('userId');

            $data = array(
                'ID' => getUID(),
                'WFM_STATUS_ID' => $wfmStatusId,
                'USER_ID' => $userId,
            );
            $checkData = $this->getCheckStatusPermissionModel('USER_ID', $userId, $wfmStatusId);
            if ($checkData) {
                $result = $this->db->AutoExecute('META_WFM_STATUS_PERMISSION', $data);
                if ($result) {
                    $response = array('status' => 'success', 'message' => 'Амжилттай хадгаллаа');
                }
            }
            return $response;
        }

        public function addUserAssignmentModel() {
            $response = array('status' => 'warning', 'message' => 'Амжилтгүй боллоо.');

            $wfmStatusId = Input::post('wfmStatusId');
            $userId = $_POST['userId'];
            $isNeedSign = Input::post('is_need_sign');
            $duePeriod = Input::post('due_period');

            if (count($userId) > 0) {
                foreach ($userId as $row) {
                    $data = array(
                        'ID' => getUID(),
                        'WFM_STATUS_ID' => $wfmStatusId,
                        'USER_ID' => Input::param($row['id']),
                        'DUE_PERIOD' => $duePeriod,
                        'IS_NEED_SIGN' => $isNeedSign,
                    );
                    $result = $this->db->AutoExecute('META_WFM_STATUS_ASSIGNMENT', $data);
                    if ($result) {
                        $response = array('status' => 'success', 'message' => 'Амжилттай хадгаллаа.');
                    }
                }
            } else
                $response = array('status' => 'warning', 'message' => 'Хэрэглэгч сонгоогүй байна!');

            return $response;
        }

        public function addStatusRolePermissionModel() {
            $response = array('status' => 'warning', 'message' => 'Амжилтгүй боллоо');

            $wfmStatusId = Input::post('wfmStatusId');
            $roleId = Input::post('roleId');

            $data = array(
                'ID' => getUID(),
                'WFM_STATUS_ID' => $wfmStatusId,
                'ROLE_ID' => $roleId,
            );
            $checkData = $this->getCheckStatusPermissionModel('ROLE_ID', $roleId, $wfmStatusId);
            if ($checkData) {
                $result = $this->db->AutoExecute('META_WFM_STATUS_PERMISSION', $data);
                if ($result) {
                    $response = array('status' => 'success', 'message' => 'Амжилттай хадгаллаа');
                }
            }
            return $response;
        }

        public function getCheckTransitionPermissionModel($field, $fieldId, $transitionId) {
            $result = $this->db->GetRow("SELECT COUNT(ID) AS COUNTT FROM META_WFM_TRANSITION_PERMISSION WHERE $field = $fieldId AND WFM_TRANSITION_ID = $transitionId ");
            $return = true;
            if ($result['COUNTT'])
                $return = false;
            return $return;
        }

        public function getCheckStatusPermissionModel($field, $fieldId, $statusId) {
            $result = $this->db->GetRow("SELECT COUNT(ID) AS COUNTT FROM META_WFM_STATUS_PERMISSION WHERE $field = $fieldId AND WFM_STATUS_ID = $statusId ");
            $return = true;
            if ($result['COUNTT'])
                $return = false;
            return $return;
        }

        public function getWfmTransitionUserListModel() {
            $response = array();
            (Array) $param = array(
                'systemMetaGroupId' => '1457267529956193',
                'showQuery' => 1
            );
            if (Input::postCheck('transitionId')) {
                if (Input::isEmpty('transitionId') === false) {
                    
                }
            }

            $transitionId = Input::post('transitionId');
            $data = $this->ws->runResponse(GF_SERVICE_ADDRESS, Mddatamodel::$getDataViewCommand, $param);
            if ($data['status'] == 'success') {
                if (isset($data['result'])) {
                    $result = $this->db->GetAll("SELECT TT.USERNAME, per.ID FROM (" . $data['result'] . ") TT 
                        INNER JOIN META_WFM_TRANSITION_PERMISSION per ON TT.ID = per.USER_ID
                        WHERE per.WFM_TRANSITION_ID = $transitionId");
                    if ($result) {
                        $response["total"] = sizeof($result);
                        $response["rows"] = $result;
                    }
                }
            }
            return $response;
        }

        public function getWfmTransitionRoleListModel() {
            $response = array();
            $transitionId = Input::post('transitionId');

            (Array) $param = array(
                'systemMetaGroupId' => '1457174283509032',
                'showQuery' => 1
            );
            $data = $this->ws->runResponse(GF_SERVICE_ADDRESS, Mddatamodel::$getDataViewCommand, $param);
            if ($data['status'] == 'success') {
                if (isset($data['result'])) {
                    $result = $this->db->GetAll("SELECT DISTINCT TT.ROLENAME, per.ID FROM (" . $data['result'] . ") TT 
                        INNER JOIN META_WFM_TRANSITION_PERMISSION per ON TT.ID = per.ROLE_ID
                        WHERE per.WFM_TRANSITION_ID = $transitionId");
                    if ($result) {
                        $response["total"] = sizeof($result);
                        $response["rows"] = $result;
                    }
                }
            }
            return $response;
        }

        public function getWfmStatusUserListModel() {
            $response = array();
            (Array) $param = array(
                'systemMetaGroupId' => '1457267529956193',
                'showQuery' => 1
            );
            $wfmStatusId = Input::post('wfmStatusId');
            $data = $this->ws->runResponse(GF_SERVICE_ADDRESS, Mddatamodel::$getDataViewCommand, $param);
            if ($data['status'] == 'success') {
                if (isset($data['result'])) {
                    $result = $this->db->GetAll("SELECT DISTINCT TT.USERNAME, per.ID FROM (" . $data['result'] . ") TT 
                        INNER JOIN META_WFM_STATUS_PERMISSION per ON TT.ID = per.USER_ID
                        WHERE per.WFM_STATUS_ID = $wfmStatusId");
                    if ($result) {
                        $response["total"] = sizeof($result);
                        $response["rows"] = $result;
                    }
                }
            }
            return $response;
        }

        public function getWfmStatusAssignmentListModel() {
            $response = array();
            $this->load->model('mdmetadata', 'middleware/models/');
            $getMetaByCode = $this->model->getMetaDataByCodeModel('sysMetaWfmStatusAssignment');

            (Array) $criteria['wfmStatusId'][] = array(
                'operator' => '=',
                'operand' => Input::post('wfmStatusId')
            );
            (Array) $param = array(
                'systemMetaGroupId' => $getMetaByCode['META_DATA_ID'],
                'showQuery' => 0,
                'criteria' => $criteria
            );
            $wfmStatusId = Input::post('wfmStatusId');
            $data = $this->ws->runResponse(GF_SERVICE_ADDRESS, Mddatamodel::$getDataViewCommand, $param);

            if ($data['status'] == 'success' && isset($data['result'])) {
                unset($data['result']['aggregatecolumns']);
                unset($data['result']['paging']);

                $response["total"] = sizeof($data['result']);
                $response["rows"] = $data['result'];
            }
            
            return $response;
        }

        public function getWfmStatusRoleListModel() {
            $response = array();
            $wfmStatusId = Input::post('wfmStatusId');

            (Array) $param = array(
                'systemMetaGroupId' => '1457174283509032',
                'showQuery' => 1
            );
            $data = $this->ws->runResponse(GF_SERVICE_ADDRESS, Mddatamodel::$getDataViewCommand, $param);
            if ($data['status'] == 'success') {
                if (isset($data['result'])) {
                    $result = $this->db->GetAll("SELECT TT.ROLENAME, per.ID FROM (" . $data['result'] . ") TT 
                        INNER JOIN META_WFM_STATUS_PERMISSION per ON TT.ID = per.ROLE_ID
                        WHERE per.WFM_STATUS_ID = $wfmStatusId");
                    if ($result) {
                        $response["total"] = sizeof($result);
                        $response["rows"] = $result;
                    }
                }
            }
            return $response;
        }

        public function getMetaWfmStatusDataModel($id) {
            return $this->db->GetRow(
                            "SELECT 
                    AA.ID, 
                    AA.WFM_STATUS_CODE, 
                    AA.WFM_STATUS_NAME, 
                    AA.PROCESS_NAME, 
                    AA.WFM_STATUS_COLOR, 
                    AA.IS_NEED_SIGN, 
                    AA.PROCESS_META_DATA_ID, 
                    BB.META_DATA_CODE, 
                    BB.META_DATA_NAME, 
                    AA.IS_DESC_REQUIRED, 
                    AA.IS_SEND_MAIL,
                    AA.FROM_NOTIFICATION_ID, 
                    AA.TO_NOTIFICATION_ID, 
                    AA.IS_NOTIFY_TO_CREATED_USER
                FROM META_WFM_STATUS AA
                LEFT JOIN META_DATA BB ON BB.META_DATA_ID = AA.PROCESS_META_DATA_ID
                WHERE AA.ID = $id"
            );
        }

        public function updateWfmStatusModel() {
            $response = array('status' => 'warning', 'message' => 'Амжилтгүй боллоо.');
            if (Input::postCheck('metaWfmStatusId')) {
                if (Input::isEmpty('metaWfmStatusId') === false) {
                    $wfmStatusId = Input::post('metaWfmStatusId');
                    $data = array(
                        'WFM_STATUS_CODE' => Input::post('wfmStatusCode'),
                        'WFM_STATUS_NAME' => Input::post('wfmStatusName'),
                        'PROCESS_NAME' => Input::post('wfmProcessName'),
                        'WFM_STATUS_COLOR' => Input::post('wfmStatusColor'),
                        'PROCESS_META_DATA_ID' => Input::post('wfmProcessId'),
                        'IS_NEED_SIGN' => Input::postCheck('wfmIsSign') ? '1' : '0',
                        'IS_DESC_REQUIRED' => Input::postCheck('isDescRequired') ? '1' : '0',
                        'TO_NOTIFICATION_ID' => Input::post('toNotificationId'),
                        'FROM_NOTIFICATION_ID' => Input::post('fromNotificationId'),
                        'IS_NOTIFY_TO_CREATED_USER' => Input::postCheck('isNotifyToCreatedUser') ? '1' : '0',
                        'IS_SEND_MAIL' => Input::postCheck('isSendMail') ? '1' : '0',
                    );
                    $result = $this->db->AutoExecute('META_WFM_STATUS', $data, 'UPDATE', 'ID=' . $wfmStatusId);
                    $wfmWorkFlowId = $this->db->GetOne("SELECT WFM_WORKFLOW_ID FROM META_WFM_STATUS WHERE ID = $wfmStatusId");
                    if ($result) {
                        $wfmStatusArr = $this->db->GetAll("SELECT
                                                                WWS.ID AS WFM_STATUS_ID,
                                                                WWS.WFM_STATUS_NAME,
                                                                WWS.WFM_STATUS_CODE,
                                                                WWS.WFM_STATUS_COLOR
                                                            FROM
                                                                META_WFM_WORKFLOW WFM
                                                            INNER JOIN META_WFM_STATUS WWS ON WFM.ID = WWS.WFM_WORKFLOW_ID
                                                            WHERE WFM.IS_ACTIVE = 1 AND WFM.REF_STRUCTURE_ID = " . Input::numeric('metaDataId') . " AND WWS.IS_ACTIVE = 1
                                                            ORDER BY WWS.WFM_STATUS_NAME");
                        $response = array('status' => 'success', 'wfmWorkFlowId' => $wfmWorkFlowId, 'workFlowStatus' => $wfmStatusArr, 'message' => 'Амжилттай хадгаллаа.');
                    }
                }
            }
            return $response;
        }

        public function getMetaWfmWorkFlowDataModel() {
            $result = $this->db->GetRow("SELECT ID, WFM_WORKFLOW_CODE, WFM_WORKFLOW_NAME FROM META_WFM_WORKFLOW WHERE ID = " . Input::post('wfmWorkFlowId'));
            if ($result) {
                $response = array('data' => $result);
            } else {
                $response = array('data' => array());
            }
            return $response;
        }

        public function updateWfmWorkFlowModel() {
            $response = array('status' => 'warning', 'message' => 'Амжилтгүй боллоо.');
            if (Input::postCheck('workFlowId')) {
                if (Input::isEmpty('workFlowId') === false) {
                    $workFlowName = Input::post('workFlowName');
                    $workFlowCode = Input::post('workFlowCode');
                    $wfmWorkFlowId = Input::post('workFlowId');
                    $data = array(
                        'WFM_WORKFLOW_CODE' => $workFlowCode,
                        'WFM_WORKFLOW_NAME' => $workFlowName,
                    );
                    $result = $this->db->AutoExecute('META_WFM_WORKFLOW', $data, 'UPDATE', 'ID=' . $wfmWorkFlowId);
                    if ($result) {
                        $response = array('status' => 'success', 'wfmWorkFlowId' => $wfmWorkFlowId, 'message' => 'Амжилттай хадгаллаа.');
                    }
                }
            }
            return $response;
        }

        public function deleteWfmWorkFlowModel() {
            $response = array('status' => 'warning', 'title' => 'Warning', 'message' => 'Амжилтгүй боллоо.');
            if (Input::postCheck('workFlowId')) {
                if (Input::isEmpty('workFlowId') === false) {
                    $wfmWorkFlowId = Input::post('workFlowId');

                    $result = $this->db->AutoExecute('META_WFM_WORKFLOW', array('IS_ACTIVE' => '0'), 'UPDATE', 'ID = ' . $wfmWorkFlowId);
                    if ($result) {
                        $response = array('status' => 'success', 'title' => 'Success', 'wfmWorkFlowId' => $wfmWorkFlowId, 'message' => 'Амжилттай устгагдлаа.');
                    }
                }
            }
            return $response;
        }

        public function deleteCheckFirstProcessObjectModel($mainBpId, $bpOrder) {
            $bpOrder0 = $this->db->GetOne("
                SELECT 
                    META_PROCESS_WORKFLOW_ID
                FROM 
                    META_PROCESS_WORKFLOW 
                WHERE  AND 
                    MAIN_BP_ID = $mainBpId");
            if ($bpOrder0) {
                $resultBehaviour = $this->db->GetAll("
                    SELECT 
                        ID, 
                        NEXT_ORDER
                    FROM 
                        META_PROCESS_WF_BEHAVIOUR 
                    WHERE 
                        META_PROCESS_WF_ID = $bpOrder0");
                foreach ($resultBehaviour as $k => $row) {
                    if ($bpOrder == $row['NEXT_ORDER']) {
                        $this->db->Execute("DELETE FROM META_PROCESS_WF_BEHAVIOUR WHERE ID = " . $row['ID']);
                        break;
                    }
                }
            }
        }

        public function getTransitionListJtreeDataModel() {
            $rows = self::getPrevNullTransitionDataModel(Input::get('metaDataId'));
            (Array) $response = array();
            if ($rows) {
                foreach ($rows as $row) {
                    $response[] = array(
                        'text' => $row['DESCRIPTION'],
                        'id' => $row['ID'],
                        'icon' => 'fa fa-folder text-orange-400',
                        'state' => array(
                            'selected' => false,
                            'loaded' => true,
                            'disabled' => false,
                            'opened' => false,
                            'parentid' => '',
                        ),
                        'children' => false
                    );
                }
            }
            return $response;
        }

        public function getTransitionNewListDataModel($transitionId = '') {
            (Array) $object = $wfmStatusArr = $tempedArr = array();

            if ($transitionId) {
                $selfData = $this->db->GetRow(" SELECT DISTINCT
                                                    MT.ID,
                                                    MT.NEXT_WFM_STATUS_ID,
                                                    MT.PREV_WFM_STATUS_ID,
                                                    PREVS.WFM_STATUS_NAME AS PREV_WFM_STATUS_NAME,
                                                    NEXTS.WFM_STATUS_NAME AS NEXT_WFM_STATUS_NAME,
                                                    PREVS.WFM_STATUS_COLOR AS PREV_WFM_STATUS_COLOR,
                                                    NEXTS.WFM_STATUS_COLOR AS NEXT_WFM_STATUS_COLOR,
                                                    PREVS.WFM_STATUS_CODE AS PREV_WFM_STATUS_CODE,
                                                    NEXTS.WFM_STATUS_CODE AS NEXT_WFM_STATUS_CODE,
                                                    MT.CRITERIA,
                                                    MT.ASSIGNMENT_CRITERIA,
                                                    MT.TOP,
                                                    MT.LEFT,
                                                    MT.DESCRIPTION,
                                                    MT.TRANSITION_TIME,
                                                    MT.TIME_TYPE_ID,
                                                    MT.PREV_ID,
                                                    MT.NEXT_ID
                                                FROM META_WFM_TRANSITION MT
                                                LEFT JOIN META_WFM_STATUS PREVS ON MT.PREV_WFM_STATUS_ID = PREVS.ID
                                                LEFT JOIN META_WFM_STATUS NEXTS ON MT.NEXT_WFM_STATUS_ID = NEXTS.ID
                                                WHERE MT.ID =  $transitionId");
                $data = $this->db->GetAll(" SELECT DISTINCT
                                                MT.ID,
                                                MT.NEXT_WFM_STATUS_ID,
                                                MT.PREV_WFM_STATUS_ID,
                                                PREVS.WFM_STATUS_NAME AS PREV_WFM_STATUS_NAME,
                                                NEXTS.WFM_STATUS_NAME AS NEXT_WFM_STATUS_NAME,
                                                PREVS.WFM_STATUS_COLOR AS PREV_WFM_STATUS_COLOR,
                                                NEXTS.WFM_STATUS_COLOR AS NEXT_WFM_STATUS_COLOR,
                                                PREVS.WFM_STATUS_CODE AS PREV_WFM_STATUS_CODE,
                                                NEXTS.WFM_STATUS_CODE AS NEXT_WFM_STATUS_CODE,
                                                MT.CRITERIA,
                                                MT.ASSIGNMENT_CRITERIA,
                                                MT.TOP,
                                                MT.LEFT,
                                                MT.DESCRIPTION,
                                                MT.TRANSITION_TIME,
                                                MT.TIME_TYPE_ID,
                                                MT.PREV_ID,
                                                MT.NEXT_ID
                                            FROM META_WFM_TRANSITION MT
                                            LEFT JOIN META_WFM_STATUS PREVS ON MT.PREV_WFM_STATUS_ID = PREVS.ID
                                            LEFT JOIN META_WFM_STATUS NEXTS ON MT.NEXT_WFM_STATUS_ID = NEXTS.ID
                                            WHERE MT.PREV_ID =  $transitionId AND MT.SOURCE_ID = $transitionId ");
                /* array_push($object, $selfData);
                  array_push($tempedArr, $selfData['ID']);
                 */
                if (sizeOf($selfData) !== 0 && $selfData['PREV_WFM_STATUS_ID'] != '' && !in_array($selfData['PREV_WFM_STATUS_ID'], $wfmStatusArr)) {
                    array_push($wfmStatusArr, $selfData['PREV_WFM_STATUS_ID']);
                }
                if ($selfData['NEXT_WFM_STATUS_ID'] != '' && !in_array($selfData['NEXT_WFM_STATUS_ID'], $wfmStatusArr)) {
                    array_push($wfmStatusArr, $selfData['NEXT_WFM_STATUS_ID']);
                }

                if ($data) {
                    foreach ($data as $row) {
                        if ($row['NEXT_WFM_STATUS_ID'] != '' && !in_array($row['NEXT_WFM_STATUS_ID'], $wfmStatusArr)) {
                            array_push($wfmStatusArr, $row['NEXT_WFM_STATUS_ID']);
                        }
                        if ($row['PREV_WFM_STATUS_ID'] != '' && !in_array($row['PREV_WFM_STATUS_ID'], $wfmStatusArr)) {
                            array_push($wfmStatusArr, $row['PREV_WFM_STATUS_ID']);
                        }
                        if (!in_array($row['ID'], $tempedArr)) {
                            array_push($tempedArr, $row['ID']);
                        }
                        array_push($object, $row);
                        $resultData = self::getTransitionNextListDataModel($row['ID'], $object, $wfmStatusArr, $tempedArr, $transitionId);
                        if ($resultData) {
                            foreach ($resultData['object'] as $row) {
                                if (!in_array($row['ID'], $tempedArr)) {
                                    array_push($object, $row);
                                }
                                if ($row['NEXT_WFM_STATUS_ID'] != '' && !in_array($row['NEXT_WFM_STATUS_ID'], $wfmStatusArr)) {
                                    array_push($wfmStatusArr, $row['NEXT_WFM_STATUS_ID']);
                                }
                                if ($row['PREV_WFM_STATUS_ID'] != '' && !in_array($row['PREV_WFM_STATUS_ID'], $wfmStatusArr)) {
                                    array_push($wfmStatusArr, $row['PREV_WFM_STATUS_ID']);
                                }
                            }
                            /* array_merge($object, $resultData['object']); merge boldogguiee gej... */
                        }
                        if ($row['NEXT_ID'] != '') {
                            $result = self::getTransitionNextListDataModel($row['NEXT_ID'], $object, $wfmStatusArr, $tempedArr, $transitionId);
                            if ($result) {
                                foreach ($result['object'] as $row) {
                                    if (!in_array($row['ID'], $tempedArr)) {
                                        array_push($object, $row);
                                    }
                                    if ($row['NEXT_WFM_STATUS_ID'] != '' && !in_array($row['NEXT_WFM_STATUS_ID'], $wfmStatusArr)) {
                                        array_push($wfmStatusArr, $row['NEXT_WFM_STATUS_ID']);
                                    }
                                    if ($row['PREV_WFM_STATUS_ID'] != '' && !in_array($row['PREV_WFM_STATUS_ID'], $wfmStatusArr)) {
                                        array_push($wfmStatusArr, $row['PREV_WFM_STATUS_ID']);
                                    }
                                }
                                array_merge($wfmStatusArr, $result['wfmStatusArr']);
                            }
                        }
                    }
                }
            }

            return array('wfmStatusArr' => $wfmStatusArr, 'object' => $object);
        }

        public function getTransitionNextListDataModel($transitionId, $object, $wfmStatusArr, $tempedArr, $sourceId) {
            if ($transitionId) {
                $data = $this->db->GetAll(" SELECT DISTINCT
                                                MT.ID,
                                                MT.NEXT_WFM_STATUS_ID,
                                                MT.PREV_WFM_STATUS_ID,
                                                PREVS.WFM_STATUS_NAME AS PREV_WFM_STATUS_NAME,
                                                NEXTS.WFM_STATUS_NAME AS NEXT_WFM_STATUS_NAME,
                                                PREVS.WFM_STATUS_COLOR AS PREV_WFM_STATUS_COLOR,
                                                NEXTS.WFM_STATUS_COLOR AS NEXT_WFM_STATUS_COLOR,
                                                PREVS.WFM_STATUS_CODE AS PREV_WFM_STATUS_CODE,
                                                NEXTS.WFM_STATUS_CODE AS NEXT_WFM_STATUS_CODE,
                                                MT.CRITERIA,
                                                MT.ASSIGNMENT_CRITERIA,
                                                MT.TOP,
                                                MT.LEFT,
                                                MT.DESCRIPTION,
                                                MT.TRANSITION_TIME,
                                                MT.TIME_TYPE_ID,
                                                MT.PREV_ID,
                                                MT.NEXT_ID
                                            FROM META_WFM_TRANSITION MT
                                            LEFT JOIN META_WFM_STATUS PREVS ON MT.PREV_WFM_STATUS_ID = PREVS.ID
                                            LEFT JOIN META_WFM_STATUS NEXTS ON MT.NEXT_WFM_STATUS_ID = NEXTS.ID
                                            WHERE (MT.PREV_ID =  $transitionId OR MT.ID = $transitionId ) AND MT.SOURCE_ID = $sourceId ");

                if ($data) {
                    foreach ($data as $row) {
                        if (!in_array($row['ID'], $tempedArr)) {
                            array_push($tempedArr, $row['ID']);
                            array_push($object, $row);
                            if ($row['NEXT_WFM_STATUS_ID'] != '' && !in_array($row['NEXT_WFM_STATUS_ID'], $wfmStatusArr)) {
                                array_push($wfmStatusArr, $row['NEXT_WFM_STATUS_ID']);
                            }
                            if ($row['PREV_WFM_STATUS_ID'] != '' && !in_array($row['PREV_WFM_STATUS_ID'], $wfmStatusArr)) {
                                array_push($wfmStatusArr, $row['PREV_WFM_STATUS_ID']);
                            }
                            $resultData = self::getTransitionNextListDataModel($row['ID'], $object, $wfmStatusArr, $tempedArr, $sourceId);
                            if ($resultData) {
                                foreach ($resultData['object'] as $row) {
                                    if (!in_array($row['ID'], $tempedArr)) {
                                        array_push($object, $row);
                                    }
                                    if ($row['NEXT_WFM_STATUS_ID'] != '' && !in_array($row['NEXT_WFM_STATUS_ID'], $wfmStatusArr)) {
                                        array_push($wfmStatusArr, $row['NEXT_WFM_STATUS_ID']);
                                    }
                                    if ($row['PREV_WFM_STATUS_ID'] != '' && !in_array($row['PREV_WFM_STATUS_ID'], $wfmStatusArr)) {
                                        array_push($wfmStatusArr, $row['PREV_WFM_STATUS_ID']);
                                    }
                                }
                            }
                            if ($row['NEXT_ID'] != '') {
                                $result = self::getTransitionNextListDataModel($row['NEXT_ID'], $object, $wfmStatusArr, $tempedArr, $sourceId);
                                if ($result) {
                                    foreach ($result['object'] as $row) {
                                        if (!in_array($row['ID'], $tempedArr)) {
                                            array_push($object, $row);
                                        }
                                        if ($row['NEXT_WFM_STATUS_ID'] != '' && !in_array($row['NEXT_WFM_STATUS_ID'], $wfmStatusArr)) {
                                            array_push($wfmStatusArr, $row['NEXT_WFM_STATUS_ID']);
                                        }
                                        if ($row['PREV_WFM_STATUS_ID'] != '' && !in_array($row['PREV_WFM_STATUS_ID'], $wfmStatusArr)) {
                                            array_push($wfmStatusArr, $row['PREV_WFM_STATUS_ID']);
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }

            return array('object' => $object, 'wfmStatusArr' => $wfmStatusArr);
        }

        public function getTransitionStatusDataModel($statusIds, $transitionId = null) {
            $response = $this->db->GetAll("SELECT WFMS.ID, WFMS.WFM_STATUS_NAME, WFMS.WFM_STATUS_COLOR, 'rectangle' AS TYPE, WFMS.WFM_STATUS_CODE FROM META_WFM_STATUS WFMS  WHERE ID IN ($statusIds)");
            $item = $response;
            (Array) $tempArr = array();
            if ($transitionId) {
                $position = $this->db->GetOne("SELECT POSITION_SOURCE FROM META_WFM_TRANSITION WHERE ID = $transitionId");
                if ($position) {
                    $positionDecode = json_decode($position);
                    (Array) $item = array();
                    foreach ($response as $row) {
                        foreach ($positionDecode as $pos) {
                            if ($row['ID'] == $pos->id && !in_array($row['ID'], $tempArr)) {
                                $row['TOP'] = $pos->positionTop;
                                $row['LEFT'] = $pos->positionLeft;
                                array_push($item, $row);
                                array_push($tempArr, $row['ID']);
                            }
                        }
                    }
                }
            }
            return $item;
        }

        public function getMetaWfmStatusId($statusIds, $metaDataId) {
            return $this->db->GetAll("SELECT
                                            WWS.ID AS WFM_STATUS_ID,
                                            WWS.WFM_STATUS_NAME,
                                            WWS.WFM_STATUS_CODE,
                                            WWS.WFM_STATUS_COLOR
                                        FROM META_WFM_WORKFLOW WFM
                                        INNER JOIN META_WFM_STATUS WWS ON WFM.ID = WWS.WFM_WORKFLOW_ID
                                        WHERE WFM.IS_ACTIVE = 1 AND WFM.REF_STRUCTURE_ID = $metaDataId  AND WWS.IS_ACTIVE = 1 AND WWS.ID NOT IN ($statusIds)
                                        ORDER BY WWS.WFM_STATUS_NAME");
        }

        public function saveVisualMetaStatusDataModel() {
            $metaDataId = Input::numeric('metaDataId');
            $transitionId = Input::post('transitionId');
            (Array) $checkWfmStatusIdsArr = array();
            foreach ($_POST['objects'] as $objects) {
                if (!in_array($objects['id'], $checkWfmStatusIdsArr)) {
                    array_push($checkWfmStatusIdsArr, $objects['id']);
                }
            }
            $explodeWfmStatusArr = implode(',', $checkWfmStatusIdsArr);
            $workFlowId = $this->db->GetOne("SELECT ST.WFM_WORKFLOW_ID FROM META_WFM_TRANSITION  TRA  INNER JOIN META_WFM_STATUS ST ON TRA.NEXT_WFM_STATUS_ID = ST.ID WHERE TRA.ID = $transitionId");
            $usedTransitionId = $this->db->GetOne("
                SELECT
                    COUNT(*) AS COUNTT
                FROM META_WFM_TRANSITION
                WHERE (PREV_WFM_STATUS_ID IN ($explodeWfmStatusArr) OR NEXT_WFM_STATUS_ID IN ($explodeWfmStatusArr)) AND 
                (ID <> $transitionId AND SOURCE_ID <> $transitionId) AND 
                PREV_WFM_STATUS_ID IS NOT NULL");                    

            if ($usedTransitionId != '0') {
                return array('status' => 'warning', 'text' => 'Ашигласан төлөв сонгогдсон байна', 'transitionId' => $transitionId);
            } else {
                $transitionData = self::getPrevNullTransitionDataModel($metaDataId, $transitionId);
                $connections = isset($_POST['connections']) ? $_POST['connections'] : array();
                (Array) $workFlowArr = $transitionArr = $connection = $statusArr = array();


                foreach ($transitionData as $row) {
                    if (!in_array($row['ID'], $transitionArr)) {
                        array_push($transitionArr, $row['ID']);
                    }
                    if (!in_array($row['WORKFLOW_ID'], $workFlowArr)) {
                        array_push($workFlowArr, $row['WORKFLOW_ID']);
                    }
                }

                $deleteData = $this->db->GetAll("SELECT DISTINCT TEMP.ID, TEMP.TRANSITION_ID FROM (
                                                    (
                                                        SELECT WW.ID, PREV.ID AS TRANSITION_ID, WW.REF_STRUCTURE_ID FROM META_WFM_WORKFLOW WW 
                                                        INNER JOIN META_WFM_STATUS WS ON WW.ID = WS.WFM_WORKFLOW_ID
                                                        INNER JOIN META_WFM_TRANSITION PREV ON WS.ID = PREV.PREV_WFM_STATUS_ID
                                                        WHERE PREV.SOURCE_ID = $transitionId
                                                    )
                                                    UNION (
                                                        SELECT WW.ID, NEX.ID AS TRANSITION_ID, WW.REF_STRUCTURE_ID FROM META_WFM_WORKFLOW WW 
                                                        INNER JOIN META_WFM_STATUS WS ON WW.ID = WS.WFM_WORKFLOW_ID
                                                        INNER JOIN META_WFM_TRANSITION NEX ON WS.ID = NEX.NEXT_WFM_STATUS_ID
                                                        WHERE NEX.SOURCE_ID = $transitionId
                                                    )
                                                ) TEMP WHERE TEMP.REF_STRUCTURE_ID = $metaDataId");

                $currentDate = Date::currentDate();
                $sessionUserId = Ue::sessionUserKeyId();

                if (sizeof($workFlowArr) == 0) {
                    foreach ($connections as $status) {
                        if (!in_array($status['prevStatusId'], $statusArr)) {
                            array_push($statusArr, $status['prevStatusId']);
                        }
                        if (!in_array($status['nextStatusId'], $statusArr) && $status['nextStatusId'] != 'endObject001') {
                            array_push($statusArr, $status['nextStatusId']);
                        }
                    }
                    $workFlowId = getUID();
                    $metaData = $this->db->GetRow("SELECT META_DATA_CODE, META_DATA_NAME FROM META_DATA WHERE META_DATA_ID = $metaDataId");
                    $workFlowData = array(
                        'ID' => $workFlowId,
                        'WFM_WORKFLOW_CODE' => $metaData['META_DATA_CODE'],
                        'WFM_WORKFLOW_NAME' => $metaData['META_DATA_NAME'] . ' ажлын урсгал',
                        'IS_ACTIVE' => '1',
                        'CREATED_USER_ID' => $sessionUserId,
                        'CREATED_DATE' => $currentDate,
                        'REF_STRUCTURE_ID' => $metaDataId,
                    );
                    $result = $this->db->AutoExecute("META_WFM_WORKFLOW", $workFlowData);
                    if ($result) {
                        $statusImp = implode(',', $statusArr);
                        foreach ($statusArr as $status) {
                            $this->db->AutoExecute('META_WFM_STATUS', array('WFM_WORKFLOW_ID' => $workFlowId), 'UPDATE', "ID = $status");
                        }
                    }
                }

                foreach ($deleteData as $delete) {
                    if (!in_array($delete['TRANSITION_ID'], $transitionArr)) {
                        $this->db->Execute("DELETE FROM META_WFM_TRANSITION WHERE ID = " . $delete['TRANSITION_ID']);
                    }
                    if (!in_array($delete['ID'], $workFlowArr)) {
                        $this->db->Execute("DELETE FROM META_WFM_WORKFLOW WHERE ID = " . $delete['ID']);
                    }
                }

                $index = 0;
                $prevTransitionId = getUID();
                $ticket = true;
                $prevTransitionId = '';
                if (sizeOf($connections) != 0) {
                    foreach ($connections as $key => $row) {
                        $newTransitionId = getUID();
                        if ($index == 0) {
                            $index++;
                            $prevTransitionId = $transitionId;
                        }

                        $connection = array(
                            'PREV_WFM_STATUS_ID' => $row['prevStatusId'],
                            'NEXT_WFM_STATUS_ID' => ($row['nextStatusId'] == 'endObject001') ? null : $row['nextStatusId'],
                            'PREV_ID' => $prevTransitionId,
                            'DESCRIPTION' => isset($row['description']) ? $row['description'] : '',
                            'CRITERIA' => isset($row['criteria']) ? $row['criteria'] : '',
                            'ID' => $newTransitionId,
                            'IS_TRANSITION' => '1',
                            'WFM_WORKFLOW_ID' => $workFlowId,
                            'TOP' => $row['top'],
                            'LEFT' => $row['left'],
                            'SOURCE_ID' => $transitionId,
                        );
                        $ticket = $this->db->AutoExecute('META_WFM_TRANSITION', $connection);
                        if ($ticket) {
                            $this->db->AutoExecute('META_WFM_TRANSITION', array('NEXT_ID' => $newTransitionId), 'UPDATE', ' ID =' . $prevTransitionId);
                            $prevTransitionId = $newTransitionId;
                        }
                    }
                } else {
                    $connections = $_POST['objects'];
                    foreach ($connections as $key => $row) {
                        $newTransitionId = getUID();
                        if ($key == 0) {
                            $prevTransitionId = $transitionId;
                        }
                        if ($row['id'] != 'endObject001') {
                            $connection = array(
                                'PREV_WFM_STATUS_ID' => null,
                                'NEXT_WFM_STATUS_ID' => $row['id'],
                                'PREV_ID' => $prevTransitionId,
                                'ID' => $newTransitionId,
                                'IS_TRANSITION' => '1',
                                'TOP' => $row['positionTop'],
                                'LEFT' => $row['positionLeft'],
                                'SOURCE_ID' => $transitionId
                            );

                            $ticket = $this->db->AutoExecute('META_WFM_TRANSITION', $connection);
                            if ($ticket) {
                                $prevTransitionId = $newTransitionId;
                            }
                        }
                    }
                }
                $resultUpdate = $this->db->AutoExecute('META_WFM_TRANSITION', array('WFM_WORKFLOW_ID' => $workFlowId), 'UPDATE', ' ID = ' . $transitionId);
                $this->db->UpdateClob('META_WFM_TRANSITION', 'POSITION_SOURCE', json_encode($_POST['workFlowHtml']), 'ID = '.$transitionId);
                return array('status' => 'success', 'message' => 'Амжилттай хадгаллаа', 'update' => $resultUpdate, 'transitionId' => $transitionId);
            }
        }

        public function getPrevNullTransitionDataModel($metaDataId, $transitionId = null) {
            $and = '';
            if ($transitionId) {
                $and = "AND WT.ID = $transitionId";
            }

            return $this->db->GetAll("
                SELECT 
                    DISTINCT 
                    WT.ID, 
                    WS.WFM_STATUS_NAME, WT.PREV_WFM_STATUS_ID, WT.NEXT_WFM_STATUS_ID, 
                    WW.ID AS WORKFLOW_ID, WT.DESCRIPTION, WT.IS_LOCK  
                FROM META_WFM_WORKFLOW WW 
                    INNER JOIN META_WFM_STATUS WS ON WW.ID = WS.WFM_WORKFLOW_ID 
                    INNER JOIN META_WFM_TRANSITION WT ON WS.ID = WT.NEXT_WFM_STATUS_ID 
                WHERE WW.REF_STRUCTURE_ID = $metaDataId 
                    $and 
                    AND PREV_WFM_STATUS_ID IS NULL 
                    AND IS_TRANSITION = 0 
                ORDER BY WS.WFM_STATUS_NAME");
        }

        public function getWorkFlowStatusArrModel($metaDataId, $transitionId = null) {
            $andWhere = $andWhere1 = '';
            if ($transitionId) {
                $andWhere1 = " AND tr.PREV_WFM_STATUS_ID NOT IN (SELECT PREV_WFM_STATUS_ID FROM META_WFM_TRANSITION WHERE ID = $transitionId)";
                $andWhere = " AND tr.NEXT_WFM_STATUS_ID NOT IN (SELECT NEXT_WFM_STATUS_ID FROM META_WFM_TRANSITION WHERE ID = $transitionId)";
            }
            return $this->db->GetAll("SELECT WS.*
                                        FROM META_WFM_WORKFLOW WW
                                        INNER JOIN META_WFM_STATUS WS ON WW.ID  = WS.WFM_WORKFLOW_ID
                                        WHERE WW.REF_STRUCTURE_ID = $metaDataId AND WS.ID NOT IN ( 
                                            SELECT DISTINCT WFM_STATUS_ID
                                            FROM (
                                                  SELECT NEXT_WFM_STATUS_ID AS WFM_STATUS_ID FROM META_WFM_TRANSITION tr
                                                  inner join META_WFM_WORKFLOW w on tr.WFM_WORKFLOW_ID = w.ID
                                                  WHERE w.REF_STRUCTURE_ID = $metaDataId $andWhere
                                                  UNION 
                                                  SELECT PREV_WFM_STATUS_ID AS WFM_STATUS_ID 
                                                    FROM META_WFM_TRANSITION tr
                                                    inner join META_WFM_WORKFLOW w on tr.WFM_WORKFLOW_ID = w.ID
                                                  WHERE w.REF_STRUCTURE_ID = $metaDataId $andWhere1
                                            ) TEM
                                            WHERE WFM_STATUS_ID IS NOT NULL )");
        }

        public function getWorkFlowTransitionModel($transitionId) {
            return $this->db->GetRow("SELECT ID, CRITERIA, NEXT_WFM_STATUS_ID, DESCRIPTION FROM META_WFM_TRANSITION WHERE ID = $transitionId");
        }

        public function createWfmWorkFlowFromGlobalModel() {
            $response = array('status' => 'warning', 'message' => 'Амжилтгүй боллоо.');
            if (Input::postCheck('metaDataId')) {
                if (Input::isEmpty('metaDataId') === false) {
                    $sessionUserId = Ue::sessionUserId();
                    $currentDate = Date::currentDate();

                    $metaDataId = Input::numeric('metaDataId');
                    $workFlowId = $this->db->GetOne("SELECT ID FROM META_WFM_WORKFLOW WHERE REF_STRUCTURE_ID = $metaDataId");
                    if (!$workFlowId) {
                        $workFlowId = getUID();
                        $metaData = $this->db->GetRow("SELECT META_DATA_CODE, META_DATA_NAME FROM META_DATA WHERE META_DATA_ID = $metaDataId");
                        $workFlowData = array(
                            'ID' => $workFlowId,
                            'WFM_WORKFLOW_CODE' => $metaData['META_DATA_CODE'],
                            'WFM_WORKFLOW_NAME' => $metaData['META_DATA_NAME'] . ' ажлын урсгал',
                            'IS_ACTIVE' => '1',
                            'CREATED_USER_ID' => $sessionUserId,
                            'CREATED_DATE' => $currentDate,
                            'REF_STRUCTURE_ID' => $metaDataId,
                        );
                        $result = $this->db->AutoExecute("META_WFM_WORKFLOW", $workFlowData);
                    }
                    foreach ($_POST['params'] as $value) {
                        $data = array(
                            'ID' => getUID(),
                            'WFM_STATUS_CODE' => $value['wfmstatuscode'],
                            'WFM_STATUS_NAME' => $value['wfmstatusname'],
                            'PROCESS_NAME' => $value['wfmstatusname'],
                            'WFM_STATUS_COLOR' => $value['wfmstatuscolor'],
                            'WFM_GLOBAL_STATUS_ID' => $value['id'],
                            'IS_ACTIVE' => '1',
                            'CREATED_USER_ID' => $sessionUserId,
                            'CREATED_DATE' => $currentDate,
                            'WFM_WORKFLOW_ID' => $workFlowId
                        );
                        $result = $this->db->AutoExecute('META_WFM_STATUS', $data);
                    }

                    $wfmStatusArr = $this->db->GetAll("SELECT
                                                WWS.ID AS WFM_STATUS_ID,
                                                WWS.WFM_STATUS_NAME,
                                                WWS.WFM_STATUS_CODE,
                                                WWS.WFM_STATUS_COLOR
                                            FROM
                                                META_WFM_WORKFLOW WFM
                                            INNER JOIN META_WFM_STATUS WWS ON WFM.ID = WWS.WFM_WORKFLOW_ID
                                            WHERE WFM.IS_ACTIVE = 1 AND WFM.REF_STRUCTURE_ID = $metaDataId AND WWS.IS_ACTIVE = 1
                                            ORDER BY WWS.WFM_STATUS_NAME");
                    if ($wfmStatusArr) {
                        $response = array('status' => 'success', 'wfmStatusId' => '', 'workFlowStatus' => $wfmStatusArr, 'message' => 'Амжилттай хадгаллаа.');
                    }
                }
            }
            return $response;
        }

        public function deleteWfmTransitionModel() {
            $metaDataId = Input::numeric('metaDataId');

            $response = array('status' => 'warning', 'message' => 'Амжилтгүй боллоо.');
            if (Input::postCheck('transtionId')) {
                if (Input::isEmpty('transtionId') === false) {
                    $transtionId = Input::post('transtionId');
                    $result = $this->db->Execute("DELETE FROM META_WFM_TRANSITION WHERE ID = $transtionId OR SOURCE_ID = $transtionId");
                    if ($result) {
                        $transitionId = $this->db->GetOne("SELECT DISTINCT WT.ID, WS.WFM_STATUS_NAME
                                                            FROM META_WFM_WORKFLOW WW
                                                            INNER JOIN META_WFM_STATUS WS ON WW.ID = WS.WFM_WORKFLOW_ID
                                                            INNER JOIN META_WFM_TRANSITION WT ON WS.ID = WT.NEXT_WFM_STATUS_ID
                                                            WHERE REF_STRUCTURE_ID = $metaDataId AND PREV_WFM_STATUS_ID IS NULL AND IS_TRANSITION = 0
                                                            ORDER BY WS.WFM_STATUS_NAME");
                        $response = array('status' => 'success', 'message' => 'Амжилттай устгагдлаа.', 'transitionId' => $transitionId);
                    }
                }
            }
            return $response;
        }

        public function deleteWfmStatusModel() {
            $response = array('status' => 'warning', 'message' => 'Амжилтгүй боллоо.');
            if (Input::postCheck('statusId')) {
                if (Input::isEmpty('statusId') === false) {
                    $statusId = Input::post('statusId');

                    $result = $this->db->Execute("DELETE FROM META_WFM_TRANSITION WHERE ID IN (SELECT ID FROM META_WFM_TRANSITION where PREV_WFM_STATUS_ID = $statusId OR NEXT_WFM_STATUS_ID = $statusId) OR SOURCE_ID IN (SELECT ID FROM META_WFM_TRANSITION where PREV_WFM_STATUS_ID = 7002 OR NEXT_WFM_STATUS_ID = $statusId)");
                    if ($result) {
                        $result = $this->db->Execute("DELETE FROM META_WFM_STATUS WHERE ID = $statusId");
                        if ($result) {
                            $response = array('status' => 'success', 'message' => 'Амжилттай устгагдлаа.');
                        }
                    }
                }
            }
            return $response;
        }

        public function getApprovedWorkflowStatusIdsModel($transitionId, $metaDataId) {
            $workFlowId = $this->db->GetOne("SELECT ID FROM META_WFM_WORKFLOW WHERE REF_STRUCTURE_ID = $metaDataId");
            if ($workFlowId) {
                $data = $this->db->GetAll(" SELECT s.*, s.ID AS WFM_STATUS_ID FROM META_WFM_STATUS s
                                            WHERE ID NOT IN (
                                                SELECT DISTINCT WFM_STATUS_ID FROM (
                                                    SELECT NEXT_WFM_STATUS_ID AS WFM_STATUS_ID FROM META_WFM_TRANSITION WHERE WFM_WORKFLOW_ID = $workFlowId
                                                UNION 
                                                    SELECT PREV_WFM_STATUS_ID AS WFM_STATUS_ID FROM META_WFM_TRANSITION  WHERE WFM_WORKFLOW_ID = $workFlowId
                                            ) TEM WHERE WFM_STATUS_ID IS NOT NULL) AND WFM_WORKFLOW_ID = $workFlowId");
                return $data;
            } else {
                return array();
            }
        }

        public function deleteWfmStatusPermissionModel() {
            $response = array('status' => 'warning', 'message' => 'Амжилтгүй боллоо');
            $result = $this->db->Execute('DELETE FROM META_WFM_STATUS_PERMISSION WHERE ID = ' . Input::post('statusPermissionId'));
            if ($result) {
                $response = array('status' => 'success', 'message' => 'Амжилттай устгагдлаа');
            }
            return $response;
        }

        public function gMetaTypeProcessListModel($mainBpId) {
            $data = $this->db->GetAll("
                SELECT 
                    META_DATA_ID, 
                    META_DATA_NAME, 
                    META_DATA_CODE 
                FROM META_DATA 
                WHERE META_TYPE_ID IN (" . Mdmetadata::$businessProcessMetaTypeId . ", " . Mdmetadata::$expressionMetaTypeId . ") 
                    AND IS_ACTIVE = 1  AND META_DATA_ID = $mainBpId
                ORDER BY META_DATA_NAME ASC");

            return $data;
        }

       

        public function getMetaDataModel($metaDataId) {
            return $this->db->GetRow("SELECT META_DATA_ID, META_DATA_CODE, META_DATA_NAME FROM META_DATA WHERE META_DATA_ID = ".$this->db->Param(0), array($metaDataId));
        }

        public function filterBusinessProcessInfoModel() {

            $this->load->model('mdmetadata', 'middleware/models/');
            $dataViewId = $this->db->GetOne("SELECT META_DATA_ID FROM meta_data where meta_data_code = 'META_BUSINESS_PROCESS_LIST'");

            $param = array(
                'systemMetaGroupId' => $dataViewId,
                'showQuery' => 1
            );

            $data = $this->ws->runResponse(GF_SERVICE_ADDRESS, Mddatamodel::$getDataViewCommand, $param);

            if ($data['status'] == 'success' && isset($data['result'])) {
                $sql = $data['result'];
            }
            if ($sql) {

                $keyVal = Input::post('q');

                $this->db->StartTrans();
                $this->db->Execute(Ue::createSessionInfo());

                $sqlData = $this->db->SelectLimit("SELECT * FROM ($sql) TEMP WHERE TEMP.CODE LIKE '$keyVal%'", 30, -1);

                $this->db->CompleteTrans();

                if (isset($sqlData->_array)) {
                    return $sqlData->_array;
                }
            }

            return array();
        }

    }

}
