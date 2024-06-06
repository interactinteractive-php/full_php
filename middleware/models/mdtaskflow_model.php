<?php if (!defined('_VALID_PHP')) exit('Direct access to this location is not allowed.');

class Mdtaskflow_model extends Model {

    private static $metaDatas = array();
    private static $lifeCycleList = array();
    private static $lifeCycleProcessList = array();
    private static $t = 0;
    private static $i = 0;

    public function __construct() {
        parent::__construct();
    }

    public function getAdminChildMetaByProcessModel($lifeCycleId, $sourceId = '') {
        $data = array();

        if ($lifeCycleId != '') {

            $result = $this->db->GetAll("
                SELECT 
                    MD.META_DATA_NAME,
                    MD.META_TYPE_ID,
                    MD.META_DATA_CODE,
                    MDLD.LIFECYCLE_ID,
                    MDLD.LIFECYCLE_DTL_ID,
                    MDLD.PROCESS_META_DATA_ID,
                    MDLD.WFM_STATUS_ID,
                    MDLD.IS_NONFLOW,
                    TFN.PREV_LIFECYCLE_ID,
                    TFN.PREV_PROCESS_ID,
                    TFN.TRG_LIFECYCLE_ID,
                    TFN.NEXT_PROCESS_ID
                FROM META_DM_LIFECYCLE_DTL MDLD
                INNER JOIN WFM_WORKFLOW_STATUS WWS ON MDLD.WFM_STATUS_ID = WWS.WFM_STATUS_ID
                INNER JOIN META_DATA MD ON MD.META_DATA_ID = MDLD.PROCESS_META_DATA_ID 
                LEFT JOIN META_DM_TASK_FLOW TFN ON TFN.NEXT_PROCESS_ID = MDLD.PROCESS_META_DATA_ID and TFN.LIFECYCLE_ID = MDLD.LIFECYCLE_ID 
                WHERE MDLD.LIFECYCLE_ID = " . $lifeCycleId);

            if (count($result) > 0) {
                foreach ($result as $key => $value) {
                    $data['object'][$key]['META_DATA_CODE'] = $value['META_DATA_CODE'];
                    $data['object'][$key]['META_DATA_NAME'] = $value['META_DATA_NAME'];
                    $data['object'][$key]['META_TYPE_ID'] = $value['META_TYPE_ID'];
                    $data['object'][$key]['PROCESS_META_DATA_ID'] = $value['PROCESS_META_DATA_ID'];
                    $data['object'][$key]['LIFECYCLE_DTL_ID'] = $value['LIFECYCLE_DTL_ID'];
                    $data['object'][$key]['WFM_STATUS_ID'] = $value['WFM_STATUS_ID'];
                    $data['object'][$key]['IS_NONFLOW'] = $value['IS_NONFLOW'];
                    $data['object'][$key]['IS_SOLVED'] = false;

                    $data['object'][$key]['PREV_RUN_PROCESS'] = '';
                    $data['object'][$key]['ACTION_DATE'] = '';

                    $objectType = '';
                    if ($value['META_TYPE_ID'] == Mdmetadata::$businessProcessMetaTypeId) {
                        $objectType = 'rectangle';
                    }
                    if ($value['META_TYPE_ID'] == Mdmetadata::$expressionMetaTypeId) {
                        $objectType = 'rombo';
                    }
                    $data['object'][$key]['OBJECT_TYPE'] = $objectType;
                }
            }

            $result = $this->db->GetAll("
                SELECT 
                    PREV_PROCESS_ID,
                    NEXT_PROCESS_ID,
                    LIFECYCLE_ID,
                    TRG_LIFECYCLE_ID
                FROM META_DM_TASK_FLOW
                WHERE LIFECYCLE_ID = $lifeCycleId 
                AND IS_ACTIVE = 1");

            if (count($result) > 0) {
                $data['connect'] = $result;
            }
        }

        return $data;
    }

    public function getChildMetaByProcessModel($lifeCycleId, $sourceId = '') {
        $data = array();

        if ($lifeCycleId != '') {

            $paramData = array('lifecycleId' => $lifeCycleId, 'sourceId' => $sourceId);
            $result = $this->ws->runResponse(GF_SERVICE_ADDRESS, "PL_BHVR_CRITERIA_003", $paramData);
            if ($result['status'] === 'success') {
                if (isset($result['result'])) {
                    $data = $result['result'];
                    if (count($data) > 0) {
                        foreach ($data as $k => $row) {
                            $data['object'][$k]['META_DATA_CODE'] = $row['code'];
                            $data['object'][$k]['META_DATA_NAME'] = $row['name'];
                            $data['object'][$k]['META_TYPE_ID'] = $row['typeid'];
                            $data['object'][$k]['PROCESS_META_DATA_ID'] = $row['processmetadataid'];
                            $data['object'][$k]['LIFECYCLE_DTL_ID'] = $row['lifecycledtlid'];
                            $data['object'][$k]['WFM_STATUS_ID'] = $row['wfmstatusid'];
                            $data['object'][$k]['IS_NONFLOW'] = $row['isnonflow'];
                            $data['object'][$k]['IS_SOLVED'] = $row['issolved'];
                            $objectType = '';
                            if ($row['typeid'] == Mdmetadata::$businessProcessMetaTypeId) {
                                $objectType = 'rectangle';
                            }
                            if ($row['typeid'] == Mdmetadata::$expressionMetaTypeId) {
                                $objectType = 'rombo';
                            }
                            $data['object'][$k]['OBJECT_TYPE'] = $objectType;
                        }
                    }
                }
            }
        }

        $result = $this->db->GetAll("
            SELECT 
                PREV_PROCESS_ID,
                NEXT_PROCESS_ID,
                LIFECYCLE_ID,
                TRG_LIFECYCLE_ID
            FROM META_DM_TASK_FLOW
            WHERE LIFECYCLE_ID = $lifeCycleId 
            AND IS_ACTIVE = 1");

        if (count($result) > 0) {
            $data['connect'] = $result;
        }
        return $data;
    }

    public function getClassNameProcessModel($metaDataId) {
        $row = $this->db->GetRow("SELECT CLASS_NAME FROM META_BUSINESS_PROCESS_LINK WHERE META_DATA_ID = $metaDataId");
        if ($row) {
            return $row['CLASS_NAME'];
        }
        return null;
    }

    public function getInputOutputMetaDataIdModel($metaDataId) {
        if ($metaDataId) {
            $data = $this->db->GetRow("
                SELECT 
                    A.INPUT_META_DATA_ID, A.OUTPUT_META_DATA_ID
                FROM META_BUSINESS_PROCESS_LINK A
                WHERE 
                    A.META_DATA_ID = $metaDataId");
            return $data;
        } 
        
        return array();
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
                ORDER BY MM.ORDER_NUM ASC");

        foreach ($data as $row) {

            $parentPath = $row['PARAM_PATH'];
            $pos = strripos($row['PARAM_PATH'], ".");

            if ($pos != false) {
                $parentPath = substr($row['PARAM_PATH'], 0, $pos);
            } else {
                $parentPath = "";
            }

            $metaDatas[$t]['META_DATA_ID'] = $row['META_DATA_ID'];
            $metaDatas[$t]['META_DATA_NAME'] = str_repeat("-", substr_count($row['PARAM_PATH'], '.')) . $row['META_DATA_NAME'];
            $metaDatas[$t]['META_TYPE_ID'] = $row['META_TYPE_ID'];
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
        $html .= Form::hidden(array('name' => 'inputDoBpId', 'id' => 'inputDoBpId', 'value' => $doBpId));
        foreach ($metaDatas as $row) {
            if (preg_match($pattern, $row['META_DATA_CODE'])) {

                $mainBpId = $mainBpId;
                $doneBpId = "";
                $doneBpParamIsInput = 0;
                $doneBpParamPath = "";
                $metaProcessParamLinkId = "";
                $defaultValue = "";

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
                if ($depth == 0) {
                    if ($row['META_TYPE_ID'] == Mdmetadata::$metaGroupMetaTypeId)
                        $html .= '<tr class="tabletree-' . $oneRowMetaDataCode . '" data-show="' . $isShow . '" ' . $rowStyle . '>';
                    else
                        $html .= '<tr data-show="' . $isShow . '" ' . $rowStyle . '>';
                } else {
                    $html .= '<tr class="tabletree-' . $oneRowMetaDataCode . ' tabletree-parent-' . $_parentMetaDataCode . '" data-show="' . $isShow . '" ' . $rowStyle . '>';
                }

                $html .= '<td class="middle">';
                $html .= $row['META_DATA_NAME'];
                $html .= Form::hidden(array('name' => $doBpId . 'inputMetaDataName[]', 'id' => $doBpId . 'inputMetaDataName', 'value' => $row['META_DATA_NAME']));
                $html .= Form::hidden(array('name' => $doBpId . 'inputDoBpParamId[]', 'id' => $doBpId . 'inputDoBpParamId', 'value' => $row['META_DATA_ID']));
                $html .= Form::hidden(array('name' => $doBpId . 'id[]', 'class' => 'id', 'value' => $metaProcessParamLinkId));
                $html .= '</td>';
                $html .= '<td>';
                $html .= Form::text(array('name' => $doBpId . 'inputDoBpParamPath[]', 'id' => $doBpId . 'inputDoBpParamPath', 'value' => $row['META_DATA_CODE'], 'class' => 'form-control', 'readonly' => 'readonly'));
                $html .= '</td>';
                $html .= '<td>';
                $html .= Form::select(array(
                            'name' => $doBpId . 'inputDoneBpId[]',
                            'id' => $doBpId . 'inputDoneBpId',
                            'class' => 'form-control select2me inputDoneBpId ' . $_parentMetaDataCode,
                            'data' => $doneBpList,
                            'op_value' => 'META_DATA_ID',
                            'op_text' => 'META_DATA_NAME',
                            'data-placeholder' => '...',
                            'value' => $doneBpId,
                            'text' => ' ',
                            'required' => 'required'
                ));
                $html .= '</td>';
                $html .= '<td class="middle text-center">';
                $html .= Form::hidden(
                                array(
                                    'name' => $doBpId . 'inputDoneBpParamIsInputHidden[]',
                                    'id' => $doBpId . 'inputDoneBpParamIsInputHidden',
                                    'value' => $doneBpParamIsInput
                                )
                );

                if (($doneBpId != "" and $mainBpId != "" and $doneBpId == $mainBpId) or ( $doBpId == $mainBpId)) {
                    $html .= Form::checkbox(
                                    array(
                                        'name' => $doBpId . 'inputDoneBpParamIsInput[]',
                                        'id' => $doBpId . 'inputDoneBpParamIsInput',
                                        'value' => 1,
                                        'saved_val' => $doneBpParamIsInput,
                                        'class=' => 'doneBpParamIsInput',
                                        'disabled' => true
                                    )
                    );
                } else {
                    $html .= Form::checkbox(
                                    array(
                                        'name' => $doBpId . 'inputDoneBpParamIsInput[]',
                                        'id' => $doBpId . 'inputDoneBpParamIsInput',
                                        'value' => 1,
                                        'saved_val' => $doneBpParamIsInput,
                                        'class=' => 'doneBpParamIsInput'
                                    )
                    );
                }

                $html .= '</td>';
                if (!empty($row['DONE_BP_ID'])) {
                    $html .= '<td>';
                    $html .= Form::select(
                                    array(
                                        'name' => $doBpId . 'inputDoneBpParamId[]',
                                        'id' => $doBpId . 'inputDoneBpParamId',
                                        'class' => 'form-control select2me',
                                        'data' => (new Mdtaskflow())->getParamList($row['DONE_BP_ID'], $row['DONE_BP_PARAM_IS_INPUT']),
                                        'op_value' => 'META_DATA_CODE',
                                        'op_text' => 'META_DATA_NAME',
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
                                        'class' => 'form-control select2me',
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
                $html .= Form::text(array('name' => $doBpId . 'inputDoneBpParamPath[]', 'id' => $doBpId . 'inputDoneBpParamPath', 'value' => $doneBpParamPath, 'class' => 'form-control', 'readonly' => 'readonly'));
                $html .= '</td>';
                $html .= '<td>';
                if ($row['META_TYPE_ID'] != Mdmetadata::$metaGroupMetaTypeId) {
                    $html .= Form::text(array('name' => $doBpId . 'defaultValue[]', 'id' => $doBpId . 'defaultValue', 'value' => $defaultValue, 'class' => 'form-control'));
                } else {
                    $html .= Form::hidden(array('name' => $doBpId . 'defaultValue[]', 'id' => $doBpId . 'defaultValue'));
                }
                $html .= '</td>';
                $html .= '<td class="middle text-center">';
                if ($row['META_TYPE_ID'] != Mdmetadata::$metaGroupMetaTypeId)
                    $html .= Form::button(array('class' => 'btn red btn-xs', 'onclick' => 'removeParameter(this)', 'value' => '<i class="fa fa-trash"></i>'));
                $html .= '</td>';
                $html .= '</tr>';

                if ($row['META_TYPE_ID'] == Mdmetadata::$metaGroupMetaTypeId) {
                    $html .= self::drawParameterList($metaDatas, $doneBpList, $mainBpId, $doBpId, $depth + 1, $row['META_DATA_CODE']);
                }
            }
        }
        return $html;
    }

    public function getBpParamTypeListModel() {
        return $this->db->GetAll("SELECT * FROM META_data where meta_type_id = 200101010000017"); //field turultei-g haruulna
    }

    public function saveMetaProcessModel() {
        $param = array();
        $data = Input::post('bpOrder');
        foreach ($data as $k => $val) {
            $isCheck = 0;
            if ($_POST['isStart'] == $_POST['doBpId'][$k]) {
                $isCheck = 1;
            }

            array_push($param, array(
                'id' => Input::param($_POST['id'][$k]),
                'mainBpId' => Input::param($_POST['mainBpId']),
                'doBpId' => Input::param(trim($_POST['doBpId'][$k])),
                'bpOrder' => Input::param($_POST['bpOrder'][$k]),
                'trueOrder' => Input::param($_POST['trueOrder'][$k]),
                'falseOrder' => Input::param($_POST['falseOrder'][$k]),
                'isStart' => Input::param($isCheck),
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
        $metaDataId = Input::numeric('metaDataId');
        $doBpId = Input::post('doProcessId');
        $doBpParamIsInput = Input::post('doBpParamIsInput');
        $data = $_POST[$doBpId . 'inputMetaDataName'];

        $param = array();
        foreach ($data as $k => $val) {
            if (
                    !empty($_POST[$doBpId . 'inputMetaDataName'][$k]) &&
                    !empty($_POST[$doBpId . 'inputDoBpParamPath'][$k])
            ) {

                if ($_POST[$doBpId . 'defaultValue'][$k] != "" ||
                        (
                        !empty($_POST[$doBpId . 'inputDoneBpId'][$k]) &&
                        !empty($_POST[$doBpId . 'inputDoneBpParamId'][$k]) &&
                        !empty($_POST[$doBpId . 'inputDoneBpParamPath'][$k])
                        )
                ) {
                    array_push($param, array(
                        'id' => ((empty($_POST[$doBpId . 'id'][$k])) ? "" : Input::param($_POST[$doBpId . 'id'][$k])),
                        'metaProcessLink' => array('id' => self::getBusinessProcessLinkId($metaDataId), 'rowState' => 'SELECTED'),
                        'doBpId' => $doBpId,
                        //'doBpParamId' => ((empty($_POST[$doBpId . 'inputDoBpParamId'][$k])) ? "" : Input::param($_POST[$doBpId . 'inputDoBpParamId'][$k])),
                        'doBpParamPath' => ((empty($_POST[$doBpId . 'inputDoBpParamPath'][$k])) ? "" : Input::param($_POST[$doBpId . 'inputDoBpParamPath'][$k])),
                        'doBpParamIsInput' => $doBpParamIsInput,
                        'doneBpId' => ((empty($_POST[$doBpId . 'inputDoneBpId'][$k])) ? "" : Input::param($_POST[$doBpId . 'inputDoneBpId'][$k])),
                        //'doneBpParamId' => "",
                        'doneBpParamPath' => ((empty($_POST[$doBpId . 'inputDoneBpParamPath'][$k])) ? "" : Input::param($_POST[$doBpId . 'inputDoneBpParamPath'][$k])),
                        'doneBpParamIsInput' => (($_POST[$doBpId . 'inputDoneBpParamIsInputHidden'][$k] == '1') ? 1 : 0),
                        'defaultValue' => ((empty($_POST[$doBpId . 'defaultValue'][$k])) ? "" : Input::param($_POST[$doBpId . 'defaultValue'][$k]))
                            )
                    );
                } else if (
                        !empty($_POST[$doBpId . 'id'][$k]) &&
                        $_POST[$doBpId . 'defaultValue'][$k] == "" &&
                        empty($_POST[$doBpId . 'inputDoneBpId'][$k]) &&
                        empty($_POST[$doBpId . 'inputDoneBpParamId'][$k]) &&
                        empty($_POST[$doBpId . 'inputDoneBpParamPath'][$k])
                ) {
                    $result = self::deleteProcessParameterModel($_POST[$doBpId . 'id'][$k]);
                }
            }
        }
        if (count($param) != 0) {
            $paramData = array(
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

    public function getMetaDoneBpListModel($mainBpId, $doProcessid) {
        $data = $this->db->GetAll("
        SELECT * FROM (
            SELECT
                MM.TRG_META_DATA_ID AS META_DATA_ID,
                MD.META_DATA_NAME, 
                MD.META_DATA_CODE, 
                MM.ORDER_NUM 
            FROM META_META_MAP MM
            INNER JOIN META_DATA MD ON MD.META_DATA_ID = MM.TRG_META_DATA_ID
            WHERE MD.META_TYPE_ID IN (200101010000011, 200101010000015) AND MD.IS_ACTIVE = 1 AND MM.SRC_META_DATA_ID = $mainBpId AND MM.TRG_META_DATA_ID != $doProcessid 
            UNION ALL 
            SELECT META_DATA_ID, META_DATA_NAME, META_DATA_CODE, 0 AS ORDER_NUM FROM META_DATA WHERE META_DATA_ID = $mainBpId AND $mainBpId != $doProcessid 
        )
        ORDER BY ORDER_NUM ASC");

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
        if ($mainBpId) {
            $row = $this->db->GetRow("
                SELECT 
                    ADDON_DATA
                FROM 
                    META_DM_LIFECYCLE
                WHERE LIFECYCLE_ID = " . $mainBpId);

            if ($row) {
                return $row['ADDON_DATA'];
            }
        }
        return false;
    }

    public function insertMetaMetaMapModel($lifeCycleId, $object) {
        $metaData = array();
        $order = 0;
        $id = 0;
        $result = /*mdm*/$this->db->GetAll("
            SELECT 
                LIFECYCLE_DTL_ID, 
                PROCESS_META_DATA_ID 
            FROM 
                META_DM_LIFECYCLE_DTL WHERE LIFECYCLE_ID = " . $lifeCycleId);
        foreach ($result as $key => $value) {
            foreach ($object as $key1 => $row) {
                if ($row['type'] == 'circle') {
                    unset($object[$key1]);
                }
                if ($value['PROCESS_META_DATA_ID'] == $row['processMetaDataId']) {
                    unset($object[$key1]);
                }
            }
        }
        if (count($object) > 0) {
            foreach ($object as $key => $value) {
                if ($value['type'] != 'circle') {
                    $data = array(
                        'LIFECYCLE_DTL_ID' => getUID(),
                        'LIFECYCLE_ID' => $lifeCycleId,
                        'PROCESS_META_DATA_ID' => $value['processMetaDataId'],
                        'WFM_STATUS_ID' => $value['statusId'],
                        'IS_NONFLOW' => $value['isNonFlow']
                    );
                    $result = /*mdm*/$this->db->AutoExecute('META_DM_LIFECYCLE_DTL', $data);
                }
            }
        }
    }

    public function deleteMetaMetaMapModel($lifeCycleId, $object) {
        $metaData = array();
        $order = 0;
        $id = 0;
        $result = /*mdm*/$this->db->GetAll("
                    SELECT 
                        LIFECYCLE_DTL_ID, 
                        LIFECYCLE_ID,
                        PROCESS_META_DATA_ID 
                    FROM 
                        META_DM_LIFECYCLE_DTL WHERE LIFECYCLE_ID = " . $lifeCycleId);
        foreach ($object as $key1 => $row) {
            foreach ($result as $key => $value) {
                if ($value['PROCESS_META_DATA_ID'] == $row['processMetaDataId']) {
                    unset($result[$key]);
                }
            }
        }
        if (count($result) > 0) {
            foreach ($result as $key => $value) {
                //PROCESS DELETE
                /*mdm*/$this->db->Execute('DELETE FROM META_DM_LIFECYCLE_DTL WHERE LIFECYCLE_ID = ' . $lifeCycleId . ' AND PROCESS_META_DATA_ID = ' . $value['PROCESS_META_DATA_ID']);

                //TASKFLOW CONFIG DELETE
                /*mdm*/$this->db->Execute('DELETE FROM META_DM_TASK_FLOW WHERE LIFECYCLE_ID = ' . $lifeCycleId . ' AND NEXT_PROCESS_ID=' . $value['PROCESS_META_DATA_ID']);

                //CRETIRIA CONFIG DELETE
                /*mdm*/$this->db->Execute('DELETE FROM META_DM_BEHAVIOUR_DTL WHERE MAIN_PROCESS_ID=' . $value['PROCESS_META_DATA_ID'] . ' AND MAIN_LIFECYCLE_ID=' . $value['LIFECYCLE_ID']);

                //PARAMETER MAP DELETE
                /*mdm*/$this->db->Execute('DELETE FROM META_DM_LC_BP_PARAM_LINK WHERE DO_LC_DTL_ID = ' . $value['LIFECYCLE_DTL_ID'] . ' AND DO_BP_ID = ' . $value['PROCESS_META_DATA_ID']);
            }
        }
    }

    public function insertTaskFlowModel($connect, $lifeCycleId) {
        $dbProcess = array(array('NEXT_PROCESS_ID' => 0));
        $result = /*mdm*/$this->db->GetAll("
            SELECT 
                NEXT_PROCESS_ID
            FROM 
                META_DM_TASK_FLOW WHERE LIFECYCLE_ID=" . $lifeCycleId);
        if (count($result) > 0) {
            foreach ($result as $k => $row) {
                $dbProcess[$k]['NEXT_PROCESS_ID'] = $row['NEXT_PROCESS_ID'];
            }
        }
        foreach ($connect as $row) {
            if ($row['pageTargetId'] != 'endObject001') {
                $isInsert = 1;
                foreach ($dbProcess as $key => $value) {
                    if ($row['pageTargetId'] === $value['NEXT_PROCESS_ID']) {
                        $isInsert = 0;
                    }
                }
                if ($isInsert === 1) {
                    $data = array(
                        'ID' => getUID(),
                        'PREV_PROCESS_ID' => ($row['pageSourceId'] === 'startObject001' ? null : $row['pageSourceId']),
                        'NEXT_PROCESS_ID' => ($row['pageTargetId'] === 'endObject001' ? null : $row['pageTargetId']),
                        'IS_ACTIVE' => 1,
                        'LIFECYCLE_ID' => $lifeCycleId,
                        'TRG_LIFECYCLE_ID' => $lifeCycleId,
                        'PREV_LIFECYCLE_ID' => $lifeCycleId
                    );
                    /*mdm*/$this->db->AutoExecute('META_DM_TASK_FLOW', $data);
                }
            }
        }
    }

    public function deleteTaskFlowModel($lifeCycleId, $startProcessId, $endProcessId) {

        $data = array(array('ID' => $startProcessId), array('ID' => $endProcessId));
        $result = $this->db->GetAll("
            SELECT 
                ID, 
                PREV_PROCESS_ID,
                NEXT_PROCESS_ID,
                LIFECYCLE_ID,
                TRG_LIFECYCLE_ID,
                PREV_LIFECYCLE_ID
            FROM 
                META_DM_TASK_FLOW WHERE LIFECYCLE_ID=" . $lifeCycleId);
        if (count($result) > 0) {
            foreach ($result as $k => $row) {
                $isDelete = 1;
                foreach ($data as $key => $value) {
                    if ($row['NEXT_PROCESS_ID'] == $value['ID']) {
                        $isDelete = 0;
                    }
                }
                if ($isDelete === 1) {
                    /*mdm*/$this->db->Execute('DELETE FROM META_DM_TASK_FLOW WHERE LIFECYCLE_ID = ' . $lifeCycleId . ' AND NEXT_PROCESS_ID=' . $row['NEXT_PROCESS_ID']);
                }
            }
        }
    }

    public function saveVisualMetaProcessModel($object = '', $connect = '') {
        $lifeCycleId = Input::post('lifeCycleId');
        $addOnData = json_encode(array('object' => $object, 'connect' => $connect));
        /*mdm*/$this->db->UpdateClob('META_DM_LIFECYCLE', 'ADDON_DATA', $addOnData, 'LIFECYCLE_ID=' . $lifeCycleId);

        self::insertMetaMetaMapModel($lifeCycleId, $object);
        self::deleteMetaMetaMapModel($lifeCycleId, $object);

        $startProcessId = '';
        $endProcessId = '';
        foreach ($connect as $row) {
            if ($row['pageSourceId'] === 'startObject001') {
                $startProcessId = $row['pageTargetId'];
            }
            if ($row['pageTargetId'] === 'endObject001') {
                $endProcessId = $row['pageSourceId'];
            }
        }
        self::deleteTaskFlowModel($lifeCycleId, $startProcessId, $endProcessId);
        self::insertTaskFlowModel($connect, $lifeCycleId);
        foreach ($object as $row) {

            if ($row['type'] != 'circle' and $row['lifeCycleDtlId'] != '') {
                $data = array(
                    'WFM_STATUS_ID' => $row['statusId'],
                    'IS_NONFLOW' => $row['isNonFlow']
                );
                /*mdm*/$this->db->AutoExecute('META_DM_LIFECYCLE_DTL', $data, 'UPDATE', 'LIFECYCLE_DTL_ID = ' . $row['lifeCycleDtlId']);
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
        $data = $this->db->GetAll("
            SELECT 
                MD.META_DATA_ID, 
                MD.META_DATA_CODE, 
                MD.META_DATA_NAME 
            FROM 
                META_DM_LIFECYCLE_MAP MDLM
            INNER JOIN META_DATA MD ON MD.META_DATA_ID = MDLM.DATA_MODEL_ID 
            GROUP BY MD.META_DATA_ID, MD.META_DATA_CODE, MD.META_DATA_NAME");
        return $data;
    }

    public function getBusinessProcessDMLifeCycleModel($dataModelId, $sourceId = 0) {
        $paramData = array(
            'dataModelId' => $dataModelId,
            'sourceId' => $sourceId
        );
        return $this->ws->runResponse(GF_SERVICE_ADDRESS, "PL_PARENTSTATUSMODEL_004", $paramData);
    }

    public function getDMLifeCycleModel($lcBookId, $sourceId = 0) {
        $paramData = array(
            'lifecycleBookId' => $lcBookId,
            'sourceId' => $sourceId
        );
        $result = $this->ws->runResponse(GF_SERVICE_ADDRESS, "PL_PARENTMODELSTATUSLIST_004", $paramData);
        return $result;
    }

    public function getDMLifeCycleChildModel($parentId, $sourceId, $trgLifecycleId = '') {

        $lifeCycleList = array();
        //echo $parentId."->".$trgLifecycleId."\n";
        $paramData = array(
            'lifecycleId' => $parentId,
            'sourceId' => $sourceId
        );
        $result = $this->ws->runResponse(GF_SERVICE_ADDRESS, "PL_CHILDMODELSTATUSLIST_004", $paramData);
        if ($result['status'] == "success" && !empty($result['result'])) {
            foreach ($result['result'] as $k => $row) {

                $lifeCycleList[$k]['processmetadataid'] = '';
                $lifeCycleList[$k]['code'] = '';
                $lifeCycleList[$k]['lifecycleid'] = $row['id'];
                $lifeCycleList[$k]['id'] = $row['id'];
                $lifeCycleList[$k]['name'] = $row['name'];
                $lifeCycleList[$k]['parentid'] = $row['parentid'];
                $lifeCycleList[$k]['displayorder'] = $row['displayorder'];
                $lifeCycleList[$k]['targetlifecycleid'] = $row['id'];
                $lifeCycleList[$k]['status'] = $row['status'];
                $lifeCycleList[$k]['lifecyclestatusid'] = $row['status'];
                $lifeCycleList[$k]['lifecycledtlid'] = '';
                $lifeCycleList[$k]['processstatusid'] = '';
                $lifeCycleList[$k]['isnonflow'] = '';
                $lifeCycleList[$k]['issolved'] = '';
                $lifeCycleList[$k]['type'] = 'lifecycle';

                $children = self::getDMLifeCycleChildModel($row['id'], $sourceId, $trgLifecycleId);
                if (count($children) > 0) {
                    $lifeCycleList[$k]['disabled'] = true;
                    $lifeCycleList[$k]['children'] = $children;
                }
                if ($row['id'] == $trgLifecycleId) {
                    $lifeCycleList[$k]['selected'] = true;
                }
                if ($row['status']) {
                    $trgLifecycleId = $row['id'];
                }
            }
            return $lifeCycleList;
        }
    }

    public function getLifecycleEntity004Model($entityId, $sourceId) {
        $paramData = array(
            'entityId' => $entityId,
            'sourceId' => $sourceId
        );
        //var_dump($paramData); die;
        return $result = $this->ws->runResponse(GF_SERVICE_ADDRESS, "META_LIFECYCLE_003", $paramData);
        //var_dump($result); die;
    }

    public function getWorkFlowStatusListModel() {
        return $this->db->GetAll("
                SELECT  
                    WWS.WFM_STATUS_ID,
                    WWS.WFM_STATUS_CODE,
                    WWS.WFM_STATUS_NAME,
                    WWS.WFM_STATUS_COLOR,
                    WWS.IS_ACTIVE,
                    WWS.WFM_WORKFLOW_ID
                FROM WFM_WORKFLOW WW
                INNER JOIN WFM_WORKFLOW_STATUS WWS ON WW.WFM_WORKFLOW_ID = WWS.WFM_WORKFLOW_ID
                WHERE 
                  WW.ENTITY_META_DATA_ID=".Input::numeric('metaDataId')." AND
                  WWS.IS_ACTIVE = 1");
    }

    public function writeLogModel() {
        $data = array(
            'DM_LOG_ID' => getUID(),
            'LIFECYCLE_DTL_ID' => Input::post('lifeCycleDtlId'),
            'SOURCE_ID' => Input::numeric('metaDataId'),
            'USER_ID' => Ue::sessionUserId(),
            'ACTION_DATE' => Date::currentDate("Y-m-d H:i:s")
        );
        $result = /*mdm*/$this->db->AutoExecute("META_DM_LOG", $data);
        return $result;
    }

    public function metaDmPeriodicLimitModel() {
        return $this->db->GetAll("
                SELECT 
                    WFM_STATUS_ID,
                    WFM_STATUS_CODE,
                    WFM_STATUS_NAME
                FROM 
                    WFM_WORKFLOW_STATUS
                WHERE 
                    IS_ACTIVE = 1");
    }

    public function getMetaDmLifeCycleProcessModel($lifeCycleId) {
        if ($lifeCycleId != '') {
            $result = $this->db->GetAll("
                SELECT 
                    MDLD.PROCESS_META_DATA_ID,
                    MD.META_DATA_CODE,
                    MD.META_DATA_NAME
                FROM 
                    META_DM_LIFECYCLE_DTL MDLD 
                INNER JOIN META_DATA MD ON MDLD.PROCESS_META_DATA_ID=MD.META_DATA_ID
                WHERE 
                    MDLD.LIFECYCLE_ID=" . $lifeCycleId);
            return $result;
        } else {
            return false;
        }
    }

    public function getMetaDmLifeCycleModel($lcBookId = '', $lifeCycleId = '') {
        if ($lcBookId != '') {
            $result = $this->db->GetAll("
                SELECT 
                    LIFECYCLE_ID,
                    LIFECYCLE_CODE,
                    LIFECYCLE_NAME
                FROM 
                    META_DM_LIFECYCLE 
                WHERE 
                    LC_BOOK_ID=$lcBookId AND PARENT_ID " . ($lifeCycleId != '' ? '=' . $lifeCycleId : 'is null') . " ORDER BY ORDER_NUM ASC");
            foreach ($result as $k => $row) {
                self::$lifeCycleList[self::$i]['LIFECYCLE_ID'] = $row['LIFECYCLE_ID'];
                self::$lifeCycleList[self::$i]['LIFECYCLE_CODE'] = $row['LIFECYCLE_CODE'];
                self::$lifeCycleList[self::$i]['LIFECYCLE_NAME'] = $row['LIFECYCLE_NAME'];
                self::$i++;
                self::getMetaDmLifeCycleDtlModel($row['LIFECYCLE_ID'], ' -');
            }
            return self::$lifeCycleList;
        } else {
            return false;
        }
    }

    public function getMetaDmLifeCycleDtlModel($lifeCycleId = '', $space) {
        if ($lifeCycleId != '') {
            $result = $this->db->GetAll("
                SELECT 
                    LIFECYCLE_ID,
                    LIFECYCLE_CODE,
                    LIFECYCLE_NAME
                FROM 
                    META_DM_LIFECYCLE 
                WHERE 
                    PARENT_ID=$lifeCycleId ORDER BY ORDER_NUM ASC");

            foreach ($result as $k => $row) {
                self::$lifeCycleList[self::$i]['LIFECYCLE_ID'] = $row['LIFECYCLE_ID'];
                self::$lifeCycleList[self::$i]['LIFECYCLE_CODE'] = $row['LIFECYCLE_CODE'];
                self::$lifeCycleList[self::$i]['LIFECYCLE_NAME'] = $space . ' ' . $row['LIFECYCLE_NAME'];
                self::$i++;
                self::getMetaDmLifeCycleDtlModel($row['LIFECYCLE_ID'], $space . '-');
            }
            return self::$lifeCycleList;
        } else {
            return false;
        }
    }

    public function getRefTimeTypeModel() {
        return $this->db->GetAll("
                SELECT 
                    TIME_TYPE_ID,
                    TIME_TYPE_NAME,
                    TIME_TYPE_SHORT
                FROM 
                    REF_TIME_TYPE");
    }

    public function metaDmPeriodicLimitListModel() {
        return $this->db->GetAll("
                SELECT 
                    MDPL.ID,
                    MDPL.SRC_LIFECYCLE_ID,
                    MDPL.SRC_PROCESS_ID,
                    MDPL.TRG_LIFECYCLE_ID,
                    MDPL.TRG_PROCESS_ID,
                    MDPL.TIME_PERIOD,
                    RTT.TIME_TYPE_NAME
                FROM META_DM_PERIODIC_LIMIT MDPL
                INNER JOIN REF_TIME_TYPE RTT ON MDPL.TIME_TYPE_ID = RTT.TIME_TYPE_ID");
    }

    public function saveMetaDmPeriodicLimitModel() {

        $data = array(
            'SRC_LIFECYCLE_ID' => Input::post('srcLifeCycleId'),
            'SRC_PROCESS_ID' => Input::post('srcProcessId'),
            'TRG_LIFECYCLE_ID' => Input::post('trgLifeCycleId'),
            'TRG_PROCESS_ID' => Input::post('trgProcessId'),
            'TIME_PERIOD' => Input::post('timePeriod'),
            'TIME_TYPE_ID' => Input::post('timeTypeId')
        );

        if (Input::isEmpty('id')) {
            $data = array_merge($data, array('ID' => getUID()));
            $result = /*mdm*/$this->db->AutoExecute("META_DM_PERIODIC_LIMIT", $data);
        } else {
            $result = /*mdm*/$this->db->AutoExecute("META_DM_PERIODIC_LIMIT", $data, "UPDATE", "ID = " . Input::post('id'));
        }

        if ($result) {
            $array = array('status' => 'success', 'message' => 'Амжилттай хадгаллаа');
        } else {
            $array = array('status' => 'error', 'message' => 'Алдаа гарлаа');
        }
        return $array;
    }

    public function metaDmPeriodicLimitDataGridModel($pagination = true) {
        $page = Input::postCheck('page') ? Input::post('page') : 1;
        $rows = Input::postCheck('rows') ? Input::post('rows') : 10;
        $offset = ($page - 1) * $rows;
        $lcBookId = Input::post('lcBookId');

        $condition = "";
        $result = $footerArr = array();
        if (
                Input::postCheck('srcLifeCycleId') or
                Input::postCheck('srcProcessId') or
                Input::postCheck('trgLifeCycleId') or
                Input::postCheck('trgProcessId') or
                Input::postCheck('timePeriod') or
                Input::postCheck('timeTypeId')
        ) {
            if (Input::post('srcLifeCycleId') != '') {
                $condition .= ' AND MDPL.SRC_LIFECYCLE_ID=' . Input::post('srcLifeCycleId') . ' ';
            }
            if (Input::post('srcProcessId') != '') {
                $condition .= ' AND MDPL.SRC_PROCESS_ID=' . Input::post('srcProcessId') . ' ';
            }
            if (Input::post('trgLifeCycleId') != '') {
                $condition .= ' AND MDPL.TRG_PROCESS_ID=' . Input::post('trgLifeCycleId') . ' ';
            }
            if (Input::post('trgProcessId') != '') {
                $condition .= ' AND MDPL.TRG_PROCESS_ID=' . Input::post('trgProcessId') . ' ';
            }
            if (Input::post('timePeriod') != '') {
                $condition .= ' AND MDPL.TIME_PERIOD=' . Input::post('timePeriod') . ' ';
            }
            if (Input::post('timeTypeId') != '') {
                $condition .= ' AND MDPL.TIME_TYPE_ID=' . Input::post('timeTypeId') . ' ';
            }
        }

        $selectCount = "
                    SELECT 
                        COUNT(ID) AS ROW_COUNT
                    FROM 
                        META_DM_PERIODIC_LIMIT MDPL 
                    INNER JOIN META_DM_LIFECYCLE SRCMDL ON MDPL.SRC_LIFECYCLE_ID = SRCMDL.LIFECYCLE_ID
                    WHERE SRCMDL.LC_BOOK_ID=" . $lcBookId . " " . $condition;
        $selectList = "
                    SELECT 
                        MDPL.ID,
                        MDPL.SRC_LIFECYCLE_ID,
                        SRCMDL.LIFECYCLE_NAME AS SRC_LIFECYCLE_NAME,
                        MDPL.SRC_PROCESS_ID,
                        SRCMD.META_DATA_NAME AS SRC_PROCESS_NAME,
                        MDPL.TRG_LIFECYCLE_ID,
                        TRGMDL.LIFECYCLE_NAME AS TRG_LIFECYCLE_NAME,
                        MDPL.TRG_PROCESS_ID,
                        TRGMD.META_DATA_NAME AS TRG_PROCESS_NAME,
                        MDPL.TIME_PERIOD,
                        MDPL.TIME_TYPE_ID,
                        RTT.TIME_TYPE_NAME
                    FROM META_DM_PERIODIC_LIMIT MDPL 
                    INNER JOIN META_DM_LIFECYCLE SRCMDL ON MDPL.SRC_LIFECYCLE_ID = SRCMDL.LIFECYCLE_ID
                    INNER JOIN META_DATA SRCMD ON MDPL.SRC_PROCESS_ID = SRCMD.META_DATA_ID
                    INNER JOIN META_DM_LIFECYCLE TRGMDL ON MDPL.TRG_LIFECYCLE_ID = TRGMDL.LIFECYCLE_ID
                    INNER JOIN META_DATA TRGMD ON MDPL.TRG_PROCESS_ID = TRGMD.META_DATA_ID
                    INNER JOIN REF_TIME_TYPE RTT ON MDPL.TIME_TYPE_ID = RTT.TIME_TYPE_ID 
                    WHERE SRCMDL.LC_BOOK_ID= " . $lcBookId . ' ' . $condition;
        $rowCount = $this->db->GetRow($selectCount);
        $result["total"] = $rowCount['ROW_COUNT'];
        $result["rows"] = array();

        if ($pagination) {
            if ($result["total"] > 0) {
                $rs = $this->db->SelectLimit($selectList, $rows, $offset);
                $result["rows"] = $rs->_array;
            }
        } else {
            $rs = $this->db->SelectLimit($selectList);
            if (isset($rs->_array)) {
                $result["rows"] = $rs->_array;
            }
        }
        return $result;
    }

    public function removeMetaDmPeriodicLimitModel() {
        if (/*mdm*/$this->db->Execute('DELETE FROM META_DM_PERIODIC_LIMIT WHERE ID = ' . Input::post('id'))) {
            return array('status' => 'success', 'message' => 'Амжилттай устгалаа');
        } else {
            return array('status' => 'error', 'message' => 'Алдаа гарлаа');
        }
    }

    public function metaDmRepeatDataGridModel($pagination = true) {
        $page = Input::postCheck('page') ? Input::post('page') : 1;
        $rows = Input::postCheck('rows') ? Input::post('rows') : 10;
        $offset = ($page - 1) * $rows;

        $condition = "";
        $result = $footerArr = array();
        if (
                Input::postCheck('srcLifeCycleId') or
                Input::postCheck('srcProcessId') or
                Input::postCheck('trgLifeCycleId') or
                Input::postCheck('trgProcessId') or
                Input::postCheck('maxRepeatCount')
        ) {
            $condition .= ' WHERE ';
            if (Input::post('srcLifeCycleId') != '') {
                $condition .= ' MDPL.SRC_LIFECYCLE_ID=' . Input::post('srcLifeCycleId') . ' ';
            }
            if (Input::post('srcProcessId') != '') {
                $condition .= ' AND MDPL.SRC_PROCESS_ID=' . Input::post('srcProcessId') . ' ';
            }
            if (Input::post('trgLifeCycleId') != '') {
                $condition .= ' AND MDPL.TRG_PROCESS_ID=' . Input::post('trgLifeCycleId') . ' ';
            }
            if (Input::post('trgProcessId') != '') {
                $condition .= ' AND MDPL.TRG_PROCESS_ID=' . Input::post('trgProcessId') . ' ';
            }
            if (Input::post('maxRepeatCount') != '') {
                $condition .= ' AND MDPL.MAX_REPEAT_COUNT=' . Input::post('maxRepeatCount') . ' ';
            }
        }

        $selectCount = "
                    SELECT 
                        COUNT(ID) AS ROW_COUNT
                    FROM 
                        META_DM_REPEAT_DTL MDPL " . $condition;
        $selectList = "
                    SELECT 
                        MDPL.ID,
                        MDPL.SRC_LIFECYCLE_ID,
                        SRCMDL.LIFECYCLE_NAME AS SRC_LIFECYCLE_NAME,
                        MDPL.SRC_PROCESS_ID,
                        SRCMD.META_DATA_NAME AS SRC_PROCESS_NAME,
                        MDPL.TRG_LIFECYCLE_ID,
                        TRGMDL.LIFECYCLE_NAME AS TRG_LIFECYCLE_NAME,
                        MDPL.TRG_PROCESS_ID,
                        TRGMD.META_DATA_NAME AS TRG_PROCESS_NAME,
                        MDPL.MAX_REPEAT_COUNT
                    FROM META_DM_REPEAT_DTL MDPL 
                    INNER JOIN META_DM_LIFECYCLE SRCMDL ON MDPL.SRC_LIFECYCLE_ID = SRCMDL.LIFECYCLE_ID
                    INNER JOIN META_DATA SRCMD ON MDPL.SRC_PROCESS_ID = SRCMD.META_DATA_ID
                    INNER JOIN META_DM_LIFECYCLE TRGMDL ON MDPL.TRG_LIFECYCLE_ID = TRGMDL.LIFECYCLE_ID
                    INNER JOIN META_DATA TRGMD ON MDPL.TRG_PROCESS_ID = TRGMD.META_DATA_ID " . $condition;
        $rowCount = $this->db->GetRow($selectCount);
        $result["total"] = $rowCount['ROW_COUNT'];
        $result["rows"] = array();

        if ($pagination) {
            if ($result["total"] > 0) {
                $rs = $this->db->SelectLimit($selectList, $rows, $offset);
                $result["rows"] = $rs->_array;
            }
        } else {
            $rs = $this->db->SelectLimit($selectList);
            if (isset($rs->_array)) {
                $result["rows"] = $rs->_array;
            }
        }
        return $result;
    }

    public function saveMetaDmRepeatModel() {
        $data = array(
            'SRC_LIFECYCLE_ID' => Input::post('srcLifeCycleId'),
            'SRC_PROCESS_ID' => Input::post('srcProcessId'),
            'TRG_LIFECYCLE_ID' => Input::post('trgLifeCycleId'),
            'TRG_PROCESS_ID' => Input::post('trgProcessId'),
            'MAX_REPEAT_COUNT' => Input::post('maxRepeatCount')
        );

        if (Input::isEmpty('id')) {
            $data = array_merge($data, array('ID' => getUID()));
            $result = /*mdm*/$this->db->AutoExecute("META_DM_REPEAT_DTL", $data);
        } else {
            $result = /*mdm*/$this->db->AutoExecute("META_DM_REPEAT_DTL", $data, "UPDATE", "ID = " . Input::post('id'));
        }
        if ($result) {
            $array = array('status' => 'success', 'message' => 'Амжилттай хадгаллаа');
        } else {
            $array = array('status' => 'error', 'message' => 'Алдаа гарлаа');
        }
        return $array;
    }

    public function removeMetaDmRepeatModel() {
        if (/*mdm*/$this->db->Execute('DELETE FROM META_DM_REPEAT_DTL WHERE ID = ' . Input::post('id'))) {
            return array('status' => 'success', 'message' => 'Амжилттай устгалаа');
        } else {
            return array('status' => 'error', 'message' => 'Алдаа гарлаа');
        }
    }

    public function metaDmEnabletDataGridModel($pagination = true) {
        $page = Input::postCheck('page') ? Input::post('page') : 1;
        $rows = Input::postCheck('rows') ? Input::post('rows') : 10;
        $offset = ($page - 1) * $rows;

        $condition = "";
        $result = $footerArr = array();
        if (
                Input::postCheck('srcLifeCycleId') or
                Input::postCheck('srcProcessId') or
                Input::postCheck('trgLifeCycleId') or
                Input::postCheck('trgProcessId') or
                Input::postCheck('maxRepeatCount')
        ) {
            $condition .= ' WHERE ';
            $conditionArray = array();
            if (Input::post('srcLifeCycleId') != '') {
                $conditionArray[] = ' MDPL.SRC_LIFECYCLE_ID=' . Input::post('srcLifeCycleId') . ' ';
            }
            if (Input::post('srcProcessId') != '') {
                $conditionArray[] = ' MDPL.SRC_PROCESS_ID=' . Input::post('srcProcessId') . ' ';
            }
            if (Input::post('trgLifeCycleId') != '') {
                $conditionArray[] = ' MDPL.TRG_PROCESS_ID=' . Input::post('trgLifeCycleId') . ' ';
            }
            if (Input::post('trgProcessId') != '') {
                $conditionArray[] = ' MDPL.TRG_PROCESS_ID=' . Input::post('trgProcessId') . ' ';
            }
            if (Input::post('maxRepeatCount') != '') {
                $conditionArray[] = ' MDPL.BATCH_NUMBER=' . Input::post('batchNumber') . ' ';
            }

            $conditionStr = '';
            foreach ($conditionArray as $key => $value) {
                if ($key === 0) {
                    $conditionStr .= $value;
                } else {
                    $conditionStr .= ' AND ' . $value;
                }
            }
            $condition .= $conditionStr;
        }
        $selectCount = "
                    SELECT 
                        COUNT(ID) AS ROW_COUNT
                    FROM 
                        META_DM_ENABLE_DTL MDPL " . $condition;
        $selectList = "
                    SELECT 
                        MDPL.ID,
                        MDPL.SRC_LIFECYCLE_ID,
                        SRCMDL.LIFECYCLE_NAME AS SRC_LIFECYCLE_NAME,
                        MDPL.SRC_PROCESS_ID,
                        SRCMD.META_DATA_NAME AS SRC_PROCESS_NAME,
                        MDPL.TRG_LIFECYCLE_ID,
                        TRGMDL.LIFECYCLE_NAME AS TRG_LIFECYCLE_NAME,
                        MDPL.TRG_PROCESS_ID,
                        TRGMD.META_DATA_NAME AS TRG_PROCESS_NAME,
                        MDPL.BATCH_NUMBER
                    FROM META_DM_ENABLE_DTL MDPL 
                    INNER JOIN META_DM_LIFECYCLE SRCMDL ON MDPL.SRC_LIFECYCLE_ID = SRCMDL.LIFECYCLE_ID
                    INNER JOIN META_DATA SRCMD ON MDPL.SRC_PROCESS_ID = SRCMD.META_DATA_ID
                    INNER JOIN META_DM_LIFECYCLE TRGMDL ON MDPL.TRG_LIFECYCLE_ID = TRGMDL.LIFECYCLE_ID
                    INNER JOIN META_DATA TRGMD ON MDPL.TRG_PROCESS_ID = TRGMD.META_DATA_ID " . $condition;

        $rowCount = $this->db->GetRow($selectCount);
        $result["total"] = $rowCount['ROW_COUNT'];
        $result["rows"] = array();

        if ($pagination) {
            if ($result["total"] > 0) {
                $rs = $this->db->SelectLimit($selectList, $rows, $offset);
                $result["rows"] = $rs->_array;
            }
        } else {
            $rs = $this->db->SelectLimit($selectList);
            if (isset($rs->_array)) {
                $result["rows"] = $rs->_array;
            }
        }
        return $result;
    }

    public function saveMetaDmEnableModel() {
        $data = array(
            'SRC_LIFECYCLE_ID' => Input::post('srcLifeCycleId'),
            'SRC_PROCESS_ID' => Input::post('srcProcessId'),
            'TRG_LIFECYCLE_ID' => Input::post('trgLifeCycleId'),
            'TRG_PROCESS_ID' => Input::post('trgProcessId'),
            'BATCH_NUMBER' => Input::post('batchNumber')
        );

        if (Input::isEmpty('id')) {
            $data = array_merge($data, array('ID' => getUID()));
            $result = /*mdm*/$this->db->AutoExecute("META_DM_ENABLE_DTL", $data);
        } else {
            $result = /*mdm*/$this->db->AutoExecute("META_DM_ENABLE_DTL", $data, "UPDATE", "ID = " . Input::post('id'));
        }
        if ($result) {
            $array = array('status' => 'success', 'message' => 'Амжилттай хадгаллаа');
        } else {
            $array = array('status' => 'error', 'message' => 'Алдаа гарлаа');
        }
        return $array;
    }

    public function removeMetaDmEnableModel() {
        if (/*mdm*/$this->db->Execute('DELETE FROM META_DM_ENABLE_DTL WHERE ID = ' . Input::post('id'))) {
            return array('status' => 'success', 'message' => 'Амжилттай устгалаа');
        } else {
            return array('status' => 'error', 'message' => 'Алдаа гарлаа');
        }
    }

    public function getProcessListModel($lifeCycleId, $processId = '') {
        $queryExtend = '';
        if ($lifeCycleId != '') {
            if ($processId != '') {
                $queryExtend = ' AND MDLD.PROCESS_META_DATA_ID <> ' . $processId;
            }
            $result = $this->db->GetAll("
                SELECT 
                    MDLD.PROCESS_META_DATA_ID,
                    MD.META_DATA_CODE,
                    MD.META_DATA_NAME
                FROM 
                    META_DM_LIFECYCLE_DTL MDLD 
                INNER JOIN META_DATA MD ON MDLD.PROCESS_META_DATA_ID=MD.META_DATA_ID
                WHERE 
                    MDLD.LIFECYCLE_ID=" . $lifeCycleId . $queryExtend);
            if (count($result) > 0) {
                return $result;
            }
            return array();
        } else {
            return array();
        }
    }

    public function saveMetaDmBehaviourModel() {
        $data = array(
            'SRC_PROCESS_ID' => Input::post('srcProcessId'),
            'TRG_PROCESS_ID' => Input::post('trgProcessId'),
            'MAX_REPEAT_COUNT' => Input::post('maxRepeatCount'),
            'IN_PARAM_CRITERIA' => Input::post('inParamCriteria'),
            'OUT_PARAM_CRITERIA' => Input::post('outParamCriteria')
        );

        if (Input::isEmpty('id')) {
            $data = array_merge($data, array('ID' => getUID()));
            $result = /*mdm*/$this->db->AutoExecute("META_DM_BEHAVIOUR_DTL", $data);
        } else {
            $result = /*mdm*/$this->db->AutoExecute("META_DM_BEHAVIOUR_DTL", $data, "UPDATE", "ID = " . Input::post('id'));
        }
        if ($result) {
            $array = array('status' => 'success', 'message' => 'Амжилттай хадгаллаа');
        } else {
            $array = array('status' => 'error', 'message' => 'Алдаа гарлаа');
        }
        return $array;
    }

    public function removeMetaDmBehaviourModel($id = '') {
        if ($id != '') {
            $result = $this->db->Execute('DELETE FROM META_DM_BEHAVIOUR_DTL WHERE BEHAVIOUR_ID = ' . Security::float($id));
            if ($result) {
                $array = array('status' => 'success', 'message' => 'Амжилттай устгалаа');
            } else {
                $array = array('status' => 'error', 'message' => 'Алдаа гарлаа');
            }
            return $array;
        }
    }

    public function getProcessNameModel($processId) {
        if ($processId) {
            return $this->db->GetOne("
                    SELECT 
                        META_DATA_NAME
                    FROM 
                        META_DATA
                    WHERE 
                        META_DATA_ID = " . $processId);
        }
        
        return null;
    }

    public function getBehaviourDtlListModel($lifeCycleDtlId) {
        
        if ($lifeCycleDtlId) {
            $result = $this->db->GetAll("
                SELECT 
                    MDBD.BEHAVIOUR_ID,
                    MDBD.MAIN_LIFECYCLE_ID,
                    MDBD.MAIN_PROCESS_ID,
                    MDBD.DONE_LIFECYCLE_ID,
                    MDBD.DONE_PROCESS_ID,
                    MDBD.MAX_REPEAT_COUNT,
                    MDBD.IN_PARAM_CRITERIA,
                    MDBD.OUT_PARAM_CRITERIA,
                    MDBD.BATCH_NUMBER
                FROM 
                    META_DM_LIFECYCLE_DTL MDLD
                INNER JOIN META_DM_BEHAVIOUR_DTL MDBD ON MDLD.LIFECYCLE_ID=MDBD.MAIN_LIFECYCLE_ID AND MDLD.PROCESS_META_DATA_ID=MDBD.MAIN_PROCESS_ID
                WHERE MDLD.LIFECYCLE_DTL_ID=" . $lifeCycleDtlId);
            if (count($result) > 0) {
                return array('count' => count($result), 'result' => $result);
            }
        }
        
        return array('count' => 0, 'result' => null);
    }

    public function saveVisualMetaProcessBehaviourDtlModel() {
        if (Input::postCheck('objectMetaId')) {
            $data = Input::post('objectMetaId');
            $index = 1;
            foreach ($data as $row) {
                if ($_POST['dtlCount' . $row] > 0) {
                    $itemData = $_POST['dtlRowId' . $row];
                    foreach ($itemData as $k => $dtl) {
                        $repeatCount = $_POST['maxRepeatCount' . $row][$k];
                        $data = array(
                            'MAIN_LIFECYCLE_ID' => $_POST['mainLifeCycle' . $row][$k],
                            'MAIN_PROCESS_ID' => $_POST['mainProcess' . $row][$k],
                            'DONE_LIFECYCLE_ID' => $_POST['doneLifeCycle' . $row][$k],
                            'DONE_PROCESS_ID' => $_POST['doneProcess' . $row][$k],
                            'MAX_REPEAT_COUNT' => ($repeatCount != '' ? $repeatCount : '-1'),
                            'IN_PARAM_CRITERIA' => $_POST['inParamCriteria' . $row][$k],
                            'OUT_PARAM_CRITERIA' => $_POST['outParamCriteria' . $row][$k],
                            'BATCH_NUMBER' => $_POST['batchNumber' . $row][$k]
                        );
                        if ($_POST['id' . $row][$k] === '0') {
                            $data = array_merge($data, array('BEHAVIOUR_ID' => getUID()));
                            $result = /*mdm*/$this->db->AutoExecute("META_DM_BEHAVIOUR_DTL", $data);
                        } else {
                            $result = /*mdm*/$this->db->AutoExecute("META_DM_BEHAVIOUR_DTL", $data, "UPDATE", "BEHAVIOUR_ID = " . $_POST['id' . $row][$k]);
                        }
                    }
                }
            }
            return array();
        }
    }

    public function getDMLifeCycleControlListModel($dataModelId) {
        
        if ($dataModelId) {
            $data = array();
            $result = $this->db->GetAll("
                SELECT 
                    LIFECYCLE_ID,
                    LIFECYCLE_NAME
                FROM 
                    META_DM_LIFECYCLE 
                WHERE 
                    DATA_MODEL_ID=" . $dataModelId . " 
                ORDER BY ORDER_NUM ASC");
            //AND 
            //PARENT_ID IS NULL 
            if (count($result) > 0) {
                foreach ($result as $k => $row) {
                    $data[$k]['id'] = $row['LIFECYCLE_ID'];
                    $data[$k]['name'] = $row['LIFECYCLE_NAME'];
                    $data[$k]['child'] = ''; //self::getDMLifeCycleControlChildListModel($row['LIFECYCLE_ID']);
                }
                return $data;
            }
        }
        return array('');
    }

    public function getDMLifeCycleControlChildListModel($parentId) {
        $data = array();
        $result = $this->db->GetAll("
            SELECT 
                LIFECYCLE_ID,
                LIFECYCLE_NAME
            FROM 
                META_DM_LIFECYCLE 
            WHERE 
                PARENT_ID =  " . $parentId . "
            ORDER BY ORDER_NUM ASC");
        if (count($result) > 0) {
            foreach ($result as $k => $row) {
                $data[$k]['id'] = $row['LIFECYCLE_ID'];
                $data[$k]['name'] = $row['LIFECYCLE_NAME'];
                $data[$k]['child'] = self::getDMLifeCycleControlChildListModel($row['LIFECYCLE_ID']);
            }
            return $data;
        }
        return array('');
    }

    public function getMetaDmLcBookListModel($dataModelId = null) {
        if ($dataModelId) {
            try {
                $result = $this->db->GetAll("
                    SELECT 
                        DISTINCT(MDLB.ID) LC_BOOK_ID, 
                        MDLB.LC_BOOK_CODE,
                        MDLB.LC_BOOK_NAME
                    FROM META_DM_LIFECYCLE_MAP MDLM 
                        INNER JOIN META_DM_LC_BOOK MDLB ON MDLM.LC_BOOK_ID = MDLB.ID 
                    WHERE MDLB.IS_MAIN = 0 
                        AND MDLM.DATA_MODEL_ID = " . $dataModelId);
                if (count($result) > 0) {
                    return $result;
                }
            } catch (Exception $ex) {
                return array();
            }
        }
        return array();
    }

    public function getLifeCycleNameModel($lifeCycleId) {
        if ($lifeCycleId) {
            $result = $this->db->GetOne("
                SELECT 
                    LIFECYCLE_NAME
                FROM META_DM_LIFECYCLE
                WHERE 
                    LIFECYCLE_ID=" . $lifeCycleId);
            if (count($result) > 0) {
                return $result;
            }
        }
        return array('');
    }

    public function getDoneMetaDmLifeCycleModel($lcBookId = '', $lifeCycleId = '', $isLifeCycle = false, $entityId = 0) {
        if ($lcBookId != '') {
            $queryExtend = '';
            if (!$isLifeCycle) {
                $queryExtend = ' LIFECYCLE_ID <> ' . $lifeCycleId . ' AND ';
            }
            $result = $this->db->GetAll("
                SELECT 
                    LIFECYCLE_ID,
                    LIFECYCLE_CODE,
                    LIFECYCLE_NAME,
                    ORDER_NUM
                FROM 
                    META_DM_LIFECYCLE 
                WHERE 
                    LC_BOOK_ID = $lcBookId AND 
                    " . $queryExtend . "
                    PARENT_ID IS NULL AND
                    ORDER_NUM <= (SELECT ORDER_NUM FROM META_DM_LIFECYCLE WHERE LIFECYCLE_ID = $lifeCycleId AND LC_BOOK_ID = $lcBookId)
                    ORDER BY ORDER_NUM ASC");
            foreach ($result as $k => $row) {
                self::$lifeCycleList[self::$i]['LIFECYCLE_ID'] = $row['LIFECYCLE_ID'];
                self::$lifeCycleList[self::$i]['LIFECYCLE_CODE'] = $row['LIFECYCLE_CODE'];
                self::$lifeCycleList[self::$i]['LIFECYCLE_NAME'] = $row['LIFECYCLE_NAME'];
                self::$lifeCycleList[self::$i]['ORDER_NUM'] = $row['ORDER_NUM'];
                self::$lifeCycleList[self::$i]['SPACE'] = '';
                self::$i++;
                self::getDoneMetaDmLifeCycleDtlModel($row['LIFECYCLE_ID'], $lifeCycleId, ' -');
            }
            self::$lifeCycleList[self::$i]['LIFECYCLE_ID'] = $entityId;
            self::$lifeCycleList[self::$i]['LIFECYCLE_CODE'] = 'DV';
            self::$lifeCycleList[self::$i]['LIFECYCLE_NAME'] = 'DataView';
            self::$lifeCycleList[self::$i]['ORDER_NUM'] = self::$i;
            self::$lifeCycleList[self::$i]['SPACE'] = '';
            return self::$lifeCycleList;
        } else {
            return false;
        }
    }

    public function getDoneMetaDmLifeCycleDtlModel($parentLifeCycleId = '', $lifeCycleId, $space) {
        if ($lifeCycleId != '') {
            $result = $this->db->GetAll("
                SELECT 
                    LIFECYCLE_ID,
                    LIFECYCLE_CODE,
                    LIFECYCLE_NAME,
                    ORDER_NUM
                FROM 
                    META_DM_LIFECYCLE 
                WHERE 
                    PARENT_ID = $parentLifeCycleId AND
                    LIFECYCLE_ID <> $lifeCycleId AND
                    ORDER_NUM < (SELECT ORDER_NUM FROM META_DM_LIFECYCLE WHERE LIFECYCLE_ID = $lifeCycleId)
                    ORDER BY ORDER_NUM ASC");

            foreach ($result as $k => $row) {
                self::$lifeCycleList[self::$i]['LIFECYCLE_ID'] = $row['LIFECYCLE_ID'];
                self::$lifeCycleList[self::$i]['LIFECYCLE_CODE'] = $row['LIFECYCLE_CODE'];
                self::$lifeCycleList[self::$i]['LIFECYCLE_NAME'] = $space . ' ' . $row['LIFECYCLE_NAME'];
                self::$lifeCycleList[self::$i]['ORDER_NUM'] = $row['ORDER_NUM'];
                self::$lifeCycleList[self::$i]['SPACE'] = $space . ' ';
                self::$i++;
                self::getDoneMetaDmLifeCycleDtlModel($row['LIFECYCLE_ID'], $lifeCycleId, $space . '-');
            }
            return self::$lifeCycleList;
        } else {
            return false;
        }
    }

    public function getDoneLastProcessModel($lifeCycleId) {
        $paramData = array(
            'lifecycleId' => $lifeCycleId
        );
        return $result = $this->ws->runResponse(GF_SERVICE_ADDRESS, "PL_METADATAMODELLIFECYCLE_004", $paramData);
    }

    public function updateStartEndConfigModel() {
        $currentLifeCycle = Input::post('currentLifeCycle');
        $currentProcessMetaDataId = Input::post('currentProcessMetaDataId');
        $doneData = Input::post('doneLifeCycle');

        $data = array(
            'rowState' => 'SELECTED',
            'metaDataModelLifecycle' => array(
                'id' => Input::post('currentLifeCycle')    //
            ),
            'prevouisProcessId' => Input::post('doneProcess'),
            'nextProcessId' => Input::post('currentProcessMetaDataId'),
            'trgMetaDataModelLifecycleId' => Input::post('doneLifeCycle')    //
        );
        $result = $this->ws->runResponse(GF_SERVICE_ADDRESS, "PL_METADATAMODEL_TASKFLOW_007", $data); //PL_METADATAMODELSTARTANDCONFIG_001
        if ($result['status'] == 'success') {
            return array('status' => 'success', 'message' => Lang::line('msg_save_success'));
        } else {
            $message = $result['text'];
            $message .= $this->ws->errorReport($result);

            return array('status' => 'error', 'message' => $message);
        }
    }

    public function startEndConfigListModel($lcBookId, $lifeCycleId, $processId) {
        $html = '';
        
        if ($lifeCycleId && $processId) {
            
            $result = $this->db->GetAll("
                SELECT 
                    PREV_PROCESS_ID, 
                    PREV_LIFECYCLE_ID 
                FROM 
                    META_DM_TASK_FLOW 
                WHERE 
                    LIFECYCLE_ID IS NOT NULL AND 
                    PREV_PROCESS_ID IS NOT NULL AND 
                    LIFECYCLE_ID=$lifeCycleId AND 
                    NEXT_PROCESS_ID=$processId");
            $count = count($result);
            if ($count > 0) {
                $i = 1;
                $html .= Form::hidden(array('name' => 'rowCounter', 'id' => 'rowCounter', 'value' => $count));
                foreach ($result as $k => $row) {
                    $html .= '<tr class="gradeX">';
                    $html .= '<td>' . $i . '</td>';
                    $html .= '<td>';
                    $html .= ($row['PREV_LIFECYCLE_ID'] != '' ? self::getLifeCycleNameModel($row['PREV_LIFECYCLE_ID']) : "");
                    $html .= Form::hidden(array('id' => 'LIFECYCLE_ID' . $i, 'name' => 'LIFECYCLE_ID[]', 'value' => $row['PREV_LIFECYCLE_ID']));
                    $html .= '</td>';
                    $html .= '<td>';
                    $html .= ($row['PREV_PROCESS_ID'] != '' ? self::getProcessNameModel($row['PREV_PROCESS_ID']) : "");
                    $html .= Form::hidden(array('id' => 'PREV_PROCESS_ID' . $i, 'name' => 'PREV_PROCESS_ID[]', 'value' => $row['PREV_PROCESS_ID']));
                    $html .= '</td>';
                    $html .= '<td><div class="btn btn-sm red" onclick="removeStartEndConfig(this)"><i class="fa fa-trash"></i></div></td>';
                    $html .= '</tr>';
                    $i++;
                }
                return $html;
            }
        }
        
        return $html .= Form::hidden(array('name' => 'rowCounter', 'id' => 'rowCounter', 'value' => $count));
    }

    public function removeStartEndConfigModel() {

        $data = array(
            'rowState' => 'SELECTED',
            'metaDataModelLifecycle' => array(
                'id' => Input::post('nextLifeCycleId')    //
            ),
            'prevouisProcessId' => Input::post('previewProcessId'),
            'nextProcessId' => Input::post('nextProcessId'),
            'trgMetaDataModelLifecycleId' => Input::post('previewLifeCycleId')    //
        );
        $result = $this->ws->runResponse(GF_SERVICE_ADDRESS, "PL_METADATAMODEL_TASKFLOW_008", $data); //PL_METADATAMODELSTARTANDCONFIG_001
        if ($result['status'] == 'success') {
            return array('status' => 'success', 'message' => Lang::line('msg_save_success'));
        } else {
            $message = $result['text'];
            $message .= $this->ws->errorReport($result);

            return array('status' => 'error', 'message' => $message);
        }
    }

    public function getProcessHistoryListModel($lifeCycleDtlId, $sourceId) {
        try {
            $result = $this->db->GetAll("
                SELECT 
                    SL.ID, 
                    MDL.ACTION_DATE,
                    SL.USER_NAME,
                    SL.IP_ADDRESS,
                    SL.COMMAND_NAME 
                FROM 
                  META_DM_LOG mdl
                inner join SYSTEM_LOG SL ON MDL.LOG_BOOK_ID = SL.ID
                where 
                    mdl.LIFECYCLE_DTL_ID=$lifeCycleDtlId and 
                    mdl.SOURCE_ID=$sourceId");
            $count = count($result);
            if ($count > 0) {
                return array("total" => $count, "rows" => $result);
            }
        } catch (Exception $ex) {
            return array();
        }
        return array();
    }

    public function getSystemLogDataModel($id) {
        if ($id) {
            $result = $this->db->GetRow("
                SELECT 
                    REQUEST_DATA_ELEMENT, 
                    RESPONSE_DATA_ELEMENT
                FROM 
                  SYSTEM_LOG
                where 
                    ID=$id");
            $count = count($result);
            if ($count > 0) {
                return $result;
            }
        }
        return array();
    }

    public function getDMLifeCycleParentChildIdModel($lifeCycleId) {
        if ($lifeCycleId != '') {
            $result = $this->db->GetRow("
                SELECT 
                    LIFECYCLE_ID,
                    PARENT_ID
                FROM META_DM_LIFECYCLE
                WHERE LIFECYCLE_ID = " . $lifeCycleId);
            $count = count($result);
            if ($count > 0) {
                return $result;
            }
        }
        return array();
    }

    public function getLifeCycleAllList($lcBookId, $sourceId = 0) {
        $lifeCycleParentList = self::getDMLifeCycleModel($lcBookId, $sourceId);

        $data = array();
        if ($lifeCycleParentList['status'] === 'success') {
            if (isset($lifeCycleParentList['result'])) {
                foreach ($lifeCycleParentList['result'] as $k => $row) {
                    $child = array();
                    $data[$k]['lifecycleid'] = $row['id'];
                    $data[$k]['processmetadataid'] = '';
                    $data[$k]['name'] = $row['name'];
                    $data[$k]['code'] = '';
                    $data[$k]['parentid'] = '';
                    $data[$k]['lifecyclestatusid'] = $row['status'];
                    $data[$k]['targetlifecycleid'] = $row['id'];
                    $data[$k]['lifecycledtlid'] = '';
                    $data[$k]['processstatusid'] = '';
                    $data[$k]['isnonflow'] = '';
                    $data[$k]['issolved'] = '';
                    $data[$k]['type'] = 'lifecycle';
                    $data[$k]['wfmstatusid'] = '';

                    $paramData = array('lifecycleId' => $row['id'], 'sourceId' => $sourceId);
                    $result = $this->ws->runResponse(GF_SERVICE_ADDRESS, "PL_BHVR_CRITERIA_003", $paramData);
                    if ($result['status'] === 'success') {
                        if (isset($result['result'])) {
                            $process = $result['result'];
                            if (count($process) > 0) {
                                foreach ($process as $key => $value) {
                                    $child[$key]['isstart'] = (empty($key) ? '1' : '0');
                                    $child[$key]['lifecycleid'] = $row['id'];
                                    $child[$key]['processmetadataid'] = $value['processmetadataid'];
                                    $child[$key]['name'] = $value['name'];
                                    $child[$key]['code'] = $value['code'];
                                    $child[$key]['parentid'] = $value['processmetadataid'];
                                    $child[$key]['lifecyclestatusid'] = '';
                                    $child[$key]['targetlifecycleid'] = '';
                                    $child[$key]['lifecycledtlid'] = $value['lifecycledtlid'];
                                    $child[$key]['processstatusid'] = $value['status']; //new, inProcess, done
                                    $child[$key]['isnonflow'] = $value['isnonflow'];
                                    $child[$key]['issolved'] = $value['issolved'];  //өмнө ажиллах ёстой criteria биелэсэн үед
                                    $child[$key]['type'] = 'process';
                                    $child[$key]['wfmstatusid'] = '1111111111';
                                }
                            }
                        }
                    }
                    if ($getChildLifeCycles = self::getDMLifeCycleChildModel($row['id'], $sourceId)) {
                        if (isset($key)) {
                            $child[$key + 1] = $getChildLifeCycles;
                        } else {
                            $child = $getChildLifeCycles;
                        }
                    }
                    $data[$k]['children'] = $child;
//                    $data[$k]['children']['process'] = $child;
//                    $data[$k]['children']['lifecycle'] = self::getDMLifeCycleChildModel($row['id'], $sourceId);
                }
            }
        }
        return $data;
    }

    public function getInputParameterLifeCycleProcessModel($lifeCycleDtlId) {
        $data = array();
        
        if ($lifeCycleDtlId) {
            $result = $this->db->GetRow("
                SELECT 
                    MDLD.LIFECYCLE_DTL_ID,
                    MDL.LIFECYCLE_ID,
                    MDL.LIFECYCLE_NAME,
                    MD.META_DATA_ID,
                    MD.META_DATA_NAME,
                    MDL.LC_BOOK_ID,
                    MDL.ORDER_NUM,
                    MDL.PARENT_ID
                FROM 
                    META_DM_LIFECYCLE_DTL MDLD
                INNER JOIN META_DM_LIFECYCLE MDL ON MDLD.LIFECYCLE_ID = MDL.LIFECYCLE_ID
                INNER JOIN META_DATA MD ON MDLD.PROCESS_META_DATA_ID = MD.META_DATA_ID
                WHERE 
                    MDLD.LIFECYCLE_DTL_ID=" . $lifeCycleDtlId);
            if ($result) {
                return $result;
            }
        }
        return $data;
    }

    public function parameterConfigListModel($data) {
        $resultData = array();
        $isSaveValue = false;
        $lifeCycleDtlId = $data['LIFECYCLE_DTL_ID'];
        $processMetaDataId = $data['META_DATA_ID'];
        $result = $this->db->GetAll("
            SELECT 
                MPPL.ID, 
                MPPL.PARENT_ID, 
                MD.META_DATA_ID, 
                MD.META_DATA_NAME, 
                MPPL.PARAM_NAME AS META_DATA_CODE, 
                MD.META_TYPE_ID,
                MPPL.IS_SHOW,
                MPPL.PARAM_PATH
            FROM META_PROCESS_PARAM_ATTR_LINK MPPL 
            INNER JOIN META_DATA MD ON MD.META_DATA_ID = MPPL.PARAM_META_DATA_ID 
            WHERE MPPL.PROCESS_META_DATA_ID=$processMetaDataId AND MPPL.IS_INPUT=1 ORDER BY MPPL.PARAM_PATH ASC"); //AND IS_SHOW=1 
        if (count($result) > 0) {
            foreach ($result as $k => $row) {
                $resultData[$k]['ID'] = $row['ID'];
                $resultData[$k]['PARENT_ID'] = $row['PARENT_ID'];
                $resultData[$k]['META_DATA_ID'] = $processMetaDataId;
                $resultData[$k]['META_DATA_NAME'] = $row['META_DATA_NAME'];
                $resultData[$k]['META_DATA_CODE'] = $row['META_DATA_CODE'];
                $resultData[$k]['META_TYPE_ID'] = $row['META_TYPE_ID'];
                $resultData[$k]['IS_SHOW'] = $row['IS_SHOW'];
                $resultData[$k]['PARAM_PATH'] = $row['PARAM_PATH'];
                $resultData[$k]['LC_BP_PARAM_LINK_ID'] = '';
                $resultData[$k]['DONE_LC_DTL_ID'] = '';
                $resultData[$k]['DONE_LC_ID'] = '';
                $resultData[$k]['DONE_BP_ID'] = '';
                $resultData[$k]['DONE_BP_PARAM_PATH'] = '';
                $resultData[$k]['DONE_BP_PARAM_IS_INPUT'] = '';
                $resultData[$k]['DEFAULT_VALUE'] = '';

                $done = $this->db->GetRow("
                    SELECT 
                        MDLBPL.ID,
                        MDLBPL.DO_LC_DTL_ID,
                        MDLD.LIFECYCLE_ID AS DONE_LC_ID,
                        MDLBPL.DO_BP_ID,
                        MDLBPL.DO_BP_PARAM_PATH,
                        MDLBPL.DONE_LC_DTL_ID,
                        MDLBPL.DONE_BP_PARAM_PATH,
                        MDLBPL.DONE_BP_ID,
                        MDLBPL.DONE_BP_PARAM_IS_INPUT,
                        MDLBPL.DEFAULT_VALUE
                    FROM META_DM_LC_BP_PARAM_LINK MDLBPL
                    LEFT JOIN META_DM_LIFECYCLE_DTL MDLD ON MDLBPL.DONE_LC_DTL_ID = MDLD.LIFECYCLE_DTL_ID
                    WHERE
                        DO_LC_DTL_ID =" . $lifeCycleDtlId . " AND
                        DO_BP_ID=" . $processMetaDataId . " AND 
                        DO_BP_PARAM_PATH='" . $row['PARAM_PATH'] . "'");
                if (count($done) > 0) {
                    $isSaveValue = true;
                    $resultData[$k]['LC_BP_PARAM_LINK_ID'] = $done['ID'];
                    $resultData[$k]['DO_BP_ID'] = $done['DO_BP_ID'];
                    $resultData[$k]['DONE_LC_DTL_ID'] = ($done['DONE_LC_DTL_ID'] != NULL ? $done['DONE_LC_DTL_ID'] : '');
                    $resultData[$k]['DONE_LC_ID'] = ($done['DONE_LC_ID'] != NULL ? $done['DONE_LC_ID'] : '');
                    $resultData[$k]['DONE_BP_ID'] = $done['DONE_BP_ID'];
                    $resultData[$k]['DONE_BP_PARAM_PATH'] = $done['DONE_BP_PARAM_PATH'];
                    $resultData[$k]['DONE_BP_PARAM_IS_INPUT'] = $done['DONE_BP_PARAM_IS_INPUT'];
                    $resultData[$k]['DEFAULT_VALUE'] = $done['DEFAULT_VALUE'];
                }
            }
        }
        return array('data'=>$resultData, 'isSaveValue'=>$isSaveValue);
    }

    public function inputOutputParameterConfigListModel($data, $entityId) {
        $html = '';
        $newResult = array();
        $lifeCycleId = $data['LIFECYCLE_ID'];
        $lcBookId = $data['LC_BOOK_ID'];
        $doneLifeCycleList = self::getDoneMetaDmLifeCycleModel($lcBookId, $lifeCycleId, true, $entityId);
        $result = self::parameterConfigListModel($data);
        $isSaveValue = $result['isSaveValue'];
        if (count($result['data']) > 0) {
            foreach ($result['data'] as $row) {
                $isEnabelRemoveBtn = false;
                $isShow = $row['IS_SHOW'];
                $rowStyle = '';
                if (empty($isShow)) {
                    $rowStyle = ' display-none';
                }

                $oneRowId = str_replace('.', '-', $row['PARAM_PATH']);
                $parentRow = explode('-', $oneRowId);

                $isChild = 0;
                $parentRowId = '';
                $parentCount = count($parentRow) - 1;
                foreach ($parentRow as $key => $r) {
                    if ($key != $parentCount)
                        $parentRowId .= $r . '-';
                }

                $oneRowId .= '-';

                $fullPath = $parentRowId . $row['META_DATA_CODE'] . '-';
                if ($fullPath === $oneRowId){
                    $isChild = 1;
                }

                $html .= '<tr class="tabletree-' . $oneRowId . ' ' . ($oneRowId != $parentRowId ? 'tabletree-parent-' . $parentRowId : '') . '" data-show="' . $isShow . '" data-row="' . $oneRowId . '" data-row-parent="' . $parentRowId . '" data-path="'.$row['META_DATA_CODE'].'" style="'.$rowStyle.'">';
                $html .= '<td class="middle">';
                $html .= $row['META_DATA_NAME'];
                $html .= Form::hidden(array('name' => 'lcBpParamLinkId[]', 'id' => 'lcBpParamLinkId', 'value' => $row['LC_BP_PARAM_LINK_ID']));
                $html .= Form::hidden(array('name' => 'metaDataType[]', 'id' => 'metaDataType', 'value' => $row['META_TYPE_ID']));
                $html .= '</td>';
                $html .= '<td class="middle">';
                $html .= $row['PARAM_PATH'];
                $html .= Form::hidden(array('name' => 'doParamPath[]', 'id' => 'doParamPath', 'value' => $row['PARAM_PATH']));
                $html .= '</td>';
                $html .= '<td>';
                $html .= Form::hidden(array('name' => 'doneLcDtlId[]', 'id' => 'doneLcDtlId', 'value' => $row['DONE_LC_DTL_ID']));
                $list = array(
                    'name' => 'doneLifeCycleId[]',
                    'id' => 'doneLifeCycleId',
                    'class' => 'form-control select2me doneLifeCycle',
                    'op_value' => 'LIFECYCLE_ID',
                    'op_text' => 'LIFECYCLE_NAME',
                    'data' => $doneLifeCycleList,
                    'onchange' => 'changeLifeCycle(this)'
                );
                if ($isSaveValue) {
                    $list = array_merge($list, array(
                        'value' => ($entityId == $row['DONE_BP_ID'] ? $row['DONE_BP_ID'] : $row['DONE_LC_ID'])
                    ));
                } else {
                    $list = array_merge($list, array(
                        'value' => $entityId
                    ));
                }
                $html .= Form::select($list);
                $html .= '</td>';
                $html .= '<td>';
                $doneBpId = $row['DONE_BP_ID'];
                $list = array(
                    'name' => 'doneProcessId[]',
                    'id' => 'doneProcessId',
                    'class' => 'form-control select2me',
                    'op_value' => 'PROCESS_META_DATA_ID',
                    'op_text' => 'META_DATA_NAME',
                    'onchange' => 'changeProcess(this)'
                );
                if ($doneBpId != "") {
                    if ($row['DONE_LC_DTL_ID'] != '' and $row['DONE_BP_ID'] != '') {
                        $doneProcessList = self::getProcessListModel($row['DONE_LC_ID']);
                        if (count($doneProcessList) > 0) {
                            $list = array_merge($list, array(
                                'data' => $doneProcessList
                            ));
                        }
                    } else {
                        $list = array_merge($list, array(
                            'data' => array(array('PROCESS_META_DATA_ID' => $doneBpId, 'META_DATA_NAME' => 'Dataview'))
                        ));
                    }
                    $list = array_merge($list, array('value' => $doneBpId));
                    if ($entityId == $doneBpId) {
                        $list = array_merge($list, array('readonly' => true));
                    }
                }
                if (!$isSaveValue) {
                    $list = array_merge($list, array(
                        'data' => array(array('PROCESS_META_DATA_ID' => $entityId, 'META_DATA_NAME' => 'Dataview')),
                        'value' => $entityId,
                        'readonly' => true
                    ));
                }
                $html .= Form::select($list);
                $html .= '</td>';
                $html .= '<td class="middle text-center">';
                $doneIsInput = $row['DONE_BP_PARAM_IS_INPUT'];
                $list = array(
                    'class' => 'form-control',
                    'onclick' => 'clickDoneIsInput(this)',
                );
                if ($doneIsInput != '' and $doneIsInput === '1') {
                    $html .= Form::hidden(array('name' => 'doneIsInput[]', 'id' => 'doneIsInput', 'value' => '1'));
                    $list = array_merge($list, array('checked' => true));
                } else {
                    $html .= Form::hidden(array('name' => 'doneIsInput[]', 'id' => 'doneIsInput', 'value' => '0'));
                }

                if ($entityId == $row['DONE_BP_ID']) {
                    $list = array_merge($list, array('readonly' => true));
                }
                $html .= Form::checkbox($list);
                $html .= '</td>';
                $html .= '<td>';
                $doneParamPath = $row['DONE_BP_PARAM_PATH'];
                $list = array(
                    'name' => 'doneProcessParam[]',
                    'id' => 'doneProcessParam',
                    'class' => 'form-control select2me',
                    'op_value' => 'PARAM_PATH',
                    'op_text' => 'PARAM_PATH',
                    'onchange' => 'changeProcessParamValue(this)'
                );
                if ($doneParamPath != "") {
                    $getProcessParameterList = '';
                    if ($row['DONE_LC_DTL_ID'] == '' and $row['DONE_BP_ID'] != '') {
                        $getProcessParameterList = self::initDataViewParametersModel($row['DONE_BP_ID']);
                    } else {
                        $getProcessParameterList = self::getProcessParameterListModel($row['DONE_BP_PARAM_IS_INPUT'], $row['DONE_BP_ID']);
                    }

                    $list = array_merge($list, array('value' => $doneParamPath));
                    if (count($getProcessParameterList) > 0) {
                        $list = array_merge($list, array(
                            'data' => $getProcessParameterList
                        ));
                    }
                } else {
                    $list = array_merge($list, array('readonly' => true));
                }
                if (!$isSaveValue) {
                    $getProcessParameterList = self::initDataViewParametersModel($entityId);
                    if (count($getProcessParameterList) > 0) {
                        $list = array_merge($list, array(
                            'data' => $getProcessParameterList
                        ));
                    }
                    foreach ($getProcessParameterList as $rEntity) {
                        if ($rEntity['PARAM_PATH'] == $row['PARAM_PATH'] AND !empty($row['IS_SHOW'])) {
                            $list = array_merge($list, array(
                                'value' => $row['PARAM_PATH']
                            ));    
                            $row['DONE_BP_PARAM_PATH'] = $row['PARAM_PATH'];
                            $isEnabelRemoveBtn = true;
                            break;
                        }
                    }
                }
                $html .= Form::select($list);
                $html .= '</td>';
//                    $html .= '<td style="display:none;">';
//                    $html .= Form::text(array('name' => 'doneProcessParamPath[]', 'id' => 'doneProcessParamPath', 'value' => $row['DONE_BP_PARAM_PATH'], 'class' => 'form-control', 'readonly' => 'readonly'));
//                    $html .= '</td>';
                $html .= '<td>';
                $html .= Form::text(array('name' => 'defaultValue[]', 'id' => 'defaultValue', 'value' => $row['DEFAULT_VALUE'], 'class' => 'form-control'));
                $html .= '</td>';
                $html .= '<td class="middle text-center">';
                $btnArray = array(
                    'class' => 'btn red btn-xs mr0 remove-btn', 
                    'onclick' => 'removeParameter(this)', 
                    'value' => '<i class="fa fa-trash"></i>');
                if ($row['LC_BP_PARAM_LINK_ID'] == "" AND $isEnabelRemoveBtn == false AND !empty($row['IS_SHOW'])) {
                    $btnArray = array_merge($btnArray, array('disabled' => true));
                }
                $html .= Form::button($btnArray);
                $html .= '</td>';
                $html .= '</tr>';
            }
        }
        return array('data'=>$html, 'isSaveValue'=>$result['isSaveValue']);
    }

    public function getProcessParameterListModel($isInput, $processMetaDataId) {
        $result = $this->db->GetAll("
            SELECT 
                MD.META_DATA_ID, 
                MD.META_DATA_NAME, 
                MPPL.PARAM_NAME AS META_DATA_CODE, 
                MD.META_TYPE_ID,
                MPPL.IS_SHOW,
                MPPL.PARAM_PATH
            FROM META_PROCESS_PARAM_ATTR_LINK MPPL 
            INNER JOIN META_DATA MD ON MD.META_DATA_ID = MPPL.PARAM_META_DATA_ID 
            WHERE 
                MPPL.PROCESS_META_DATA_ID=$processMetaDataId AND 
                MPPL.IS_SHOW=1 AND 
                MPPL.IS_INPUT=$isInput");
        if (count($result) > 0) {
            return $result;
        }
        return array();
    }

    public function saveBpParamLinkModel() {
        $dataparam = array();
        $result = array();
        $doneProcessId = $_POST['doneProcessId'];

        foreach ($doneProcessId as $k => $row) {
            if (
                ($_POST['doneLifeCycleId'][$k] != "" &&
                isset($_POST['doneProcessId'][$k]) &&
                isset($_POST['doneProcessParam'][$k]) &&
                $_POST['doneProcessId'][$k] != "" &&
                $_POST['doneProcessParam'][$k] != "") or
                $_POST['defaultValue'][$k] != ""
            ) {
                $doneLcDtlId = Input::param($_POST['doneLcDtlId'][$k]);
                $rowData = array(
                    'doLcDtlId' => Input::param($_POST['doLcDtlId']),
                    'doBpId' => Input::param($_POST['doBpId']),
                    'doBpParamPath' => Input::param($_POST['doParamPath'][$k]),
                    'doneLcDtlId' => ($doneLcDtlId === null ? '' : Input::param($doneLcDtlId)),
                    'doneBpId' => Input::param($_POST['doneProcessId'][$k]),
                    'doneBpParamPath' => Input::param($_POST['doneProcessParam'][$k]),
                    'doneBpParamIsInput' => Input::param($_POST['doneIsInput'][$k]),
                    'defaultValue' => Input::param($_POST['defaultValue'][$k]),
                );
                if ($_POST['lcBpParamLinkId'][$k] != "") {
                    $rowData = array_merge($rowData, array('id' => Input::param($_POST['lcBpParamLinkId'][$k])));
                    $r = $this->ws->runResponse(GF_SERVICE_ADDRESS, "PL_MDMLBPL_002", $rowData);
                    array_push($result, $r);
                } else {
                    $r = $this->ws->runResponse(GF_SERVICE_ADDRESS, "PL_MDMLBPL_001", $rowData);
                    array_push($result, $r);
                }
            } else {
                if ($_POST['lcBpParamLinkId'][$k] != "") {
                    $data = array('id' => Input::param($_POST['lcBpParamLinkId'][$k]));
                    $this->ws->runResponse(GF_SERVICE_ADDRESS, "PL_MDMLBPL_005", $data);
                }
            }
        }
        foreach ($result as $row) {
            if ($row['status'] == 'error') {
                return array('status' => 'error', 'message' => 'Алдаа гарлаа');
            }
        }
        return array('status' => 'success', 'message' => 'Амжилттай хадгаллаа');
    }

    public function deleteBpParamLinkModel() {
        $data = array('id' => Input::param(Input::post('lcBpParamLinkId')));
        $result = $this->ws->runResponse(GF_SERVICE_ADDRESS, "PL_MDMLBPL_005", $data);
        if ($result['status'] == 'success') {
            return array('status' => 'success', 'message' => 'Амжилттай устгалаа');
        } else {
            return array('status' => 'error', 'message' => $result['text']);
        }
    }

    public function getLifeCycleDtlIdModel($lifeCycleId, $processMetaDataId) {
        if ($lifeCycleId && $processMetaDataId) {
            $result = $this->db->GetOne("
                SELECT 
                    LIFECYCLE_DTL_ID
                FROM META_DM_LIFECYCLE_DTL
                WHERE 
                    LIFECYCLE_ID = $lifeCycleId AND
                    PROCESS_META_DATA_ID = $processMetaDataId");
            if (count($result) > 0) {
                return $result;
            }
        }
        return array();
    }

    public function initDataViewParametersModel($entityId) {
        if ($entityId) {
            $result = $this->db->GetAll("
                SELECT 
                    PARAM_NAME AS META_DATA_NAME, 
                    FIELD_PATH AS PARAM_PATH
                FROM META_GROUP_CONFIG 
                WHERE MAIN_META_DATA_ID = $entityId");

            if (count($result) > 0) {
                return $result;
            }
        }
        return array();
    }

}
