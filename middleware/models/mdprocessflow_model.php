<?php if (!defined('_VALID_PHP')) exit('Direct access to this location is not allowed.');

class Mdprocessflow_model extends Model {
    
    private static $doBpParamList = array();

    public function __construct() {
        parent::__construct();
    }

    public function getChildMetaByProcessModel($metaDataId) {
        
        if ($metaDataId != '') {    
            $result = $this->db->GetAll("
                SELECT 
                    MPW.META_PROCESS_WORKFLOW_ID,
                    MPW.DO_BP_ID AS META_DATA_ID, 
                    MD.META_DATA_NAME, 
                    MT.META_TYPE_CODE,
                    MD.META_DATA_CODE,
                    MPW.IS_ACTIVE 
                FROM META_PROCESS_WORKFLOW MPW 
                    INNER JOIN META_DATA MD ON MD.META_DATA_ID = MPW.DO_BP_ID 
                    INNER JOIN META_BUSINESS_PROCESS_LINK MBPL ON MBPL.META_DATA_ID = MD.META_DATA_ID 
                    LEFT JOIN META_TYPE MT ON MT.META_TYPE_ID = MD.META_TYPE_ID
                WHERE MPW.MAIN_BP_ID = $metaDataId 
                    AND MD.META_TYPE_ID IN (" . Mdmetadata::$businessProcessMetaTypeId . ', ' . Mdmetadata::$expressionMetaTypeId . ") 
                    AND MD.IS_ACTIVE = 1");

            if (count($result) > 0) {
                return $result;
            }
        }
        return array();
    }

    public function getMainMetaByProcessModel($metaDataId) {
        if ($metaDataId != '') {
            $result = $this->db->GetRow("
                SELECT 
                    MPW.META_PROCESS_WORKFLOW_ID,
                    MPW.DO_BP_ID AS META_DATA_ID,
                    MD.META_DATA_NAME, 
                    MT.META_TYPE_CODE,
                    MD.META_DATA_CODE,
                    MPW.IS_START, 
                    MPW.BP_ORDER, 
                    MPW.TRUE_ORDER, 
                    MPW.FALSE_ORDER, 
                    MPW.IS_ACTIVE,
                    MPW.BP_ORDER AS ORDER_NUM
                FROM META_DATA MD
                    LEFT JOIN META_PROCESS_WORKFLOW MPW ON MPW.DO_BP_ID = MD.META_DATA_ID
                    INNER JOIN META_BUSINESS_PROCESS_LINK MBPL ON MD.META_DATA_ID = MBPL.META_DATA_ID
                    LEFT JOIN META_TYPE MT ON MT.META_TYPE_ID = MD.META_TYPE_ID
                WHERE MD.META_DATA_ID = $metaDataId 
                    AND MD.META_TYPE_ID IN (".Mdmetadata::$businessProcessMetaTypeId.", ".Mdmetadata::$expressionMetaTypeId.") 
                    AND MD.IS_ACTIVE = 1");

            if (count($result) > 0) {
                return $result;
            }
        }
        return array();
    }

    public function getClassNameProcessModel($metaDataId) {
        $row = $this->db->GetRow("SELECT CLASS_NAME FROM META_BUSINESS_PROCESS_LINK WHERE META_DATA_ID = ".$this->db->Param(0), array($metaDataId));
        if ($row) {
            return $row['CLASS_NAME'];
        }
        return null;
    }

    public function getParameterListModel($isOutput = 0, $mainBpId, $doBpId, $isShow = 0) {

        $parameters = self::getParameterListWithPathModel($doBpId, ($isOutput == 1 ? 0 : 1)); //all parameter

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
            WHERE MPPL.MAIN_BP_ID = $mainBpId 
                AND MPPL.DO_BP_ID = $doBpId 
                AND MPPL.DO_BP_PARAM_IS_INPUT = " . ($isOutput == 1 ? 0 : 1));
        
        $paramData = $array = array();
        
        foreach ($data as $row) {
            $paramData[strtolower($row['DO_BP_PARAM_PATH'])] = $row;
        }

        foreach ($parameters as $key => $parameter) {

            $name = explode('-', $parameters[$key]['META_DATA_NAME']);
            $lowerPath = strtolower($parameter['META_DATA_CODE']);
            
            $array[$key]['META_DATA_NAME'] = $name[count($name) - 1];
            $array[$key]['META_DATA_CODE'] = $parameter['META_DATA_CODE'];
            $array[$key]['DATA_TYPE'] = $parameter['DATA_TYPE'];
            $array[$key]['RECORD_TYPE'] = $parameter['RECORD_TYPE'];
            $array[$key]['IS_SHOW'] = $parameter['IS_SHOW'];
            $array[$key]['PARENT_META_DATA_CODE'] = $parameter['PARENT_META_DATA_CODE'];
            $array[$key]['META_PROCESS_PARAM_LINK_ID'] = '';
            $array[$key]['MAIN_BP_ID'] = '';
            $array[$key]['DO_BP_ID'] = '';
            $array[$key]['DO_BP_PARAM_PATH'] = '';
            $array[$key]['DO_BP_PARAM_IS_INPUT'] = '';
            $array[$key]['DONE_BP_ID'] = '';
            $array[$key]['DONE_BP_PARAM_PATH'] = '';
            $array[$key]['DONE_BP_PARAM_IS_INPUT'] = '';
            $array[$key]['DEFAULT_VALUE'] = '';
            
            if (isset($paramData[$lowerPath])) {
                
                $row = $paramData[$lowerPath];
                
                $array[$key]['META_PROCESS_PARAM_LINK_ID'] = $row['META_PROCESS_PARAM_LINK_ID'];
                $array[$key]['MAIN_BP_ID'] = $row['MAIN_BP_ID'];
                $array[$key]['DO_BP_ID'] = $row['DO_BP_ID'];
                $array[$key]['DO_BP_PARAM_PATH'] = $row['DO_BP_PARAM_PATH'];
                $array[$key]['DO_BP_PARAM_IS_INPUT'] = $row['DO_BP_PARAM_IS_INPUT'];
                $array[$key]['DONE_BP_ID'] = $row['DONE_BP_ID'];
                $array[$key]['DONE_BP_PARAM_PATH'] = $row['DONE_BP_PARAM_PATH'];
                $array[$key]['DONE_BP_PARAM_IS_INPUT'] = $row['DONE_BP_PARAM_IS_INPUT'];
                $array[$key]['DEFAULT_VALUE'] = $row['DEFAULT_VALUE'];
            }
        }

        return $array;
    }

    public function getParameterList2Model($isOutput = 0, $mainBpId, $doBpId, $isShow = 0, $mainDomainBpId) {

        $parameters = self::getParameterListWithPathModel($doBpId, ($isOutput == 1 ? 0 : 1)); //all parameter

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
                MPPL.DONE_MODEL_ID,
                MPPL.DONE_MODEL_PARAM_PATH,
                MPPL.DONE_MODEL_PARAM_IS_INPUT,
                MPPL.DEFAULT_VALUE
            FROM META_PROCESS_PARAM_LINK MPPL 
                INNER JOIN META_DATA MD1 ON MD1.META_DATA_ID = MPPL.DO_BP_ID 
            WHERE MPPL.MAIN_BP_ID = $mainBpId 
                AND MPPL.DO_BP_ID = $doBpId 
                AND MPPL.DO_BP_PARAM_IS_INPUT = " . ($isOutput == 1 ? 0 : 1));
        
        $array = array();

        foreach ($parameters as $key => $parameter) {

            $name = explode('-', $parameters[$key]['META_DATA_NAME']);
            $array[$key]['META_DATA_NAME'] = $name[count($name) - 1];
            $array[$key]['META_DATA_CODE'] = $parameter['META_DATA_CODE'];
            $array[$key]['DATA_TYPE'] = $parameter['DATA_TYPE'];
            $array[$key]['IS_SHOW'] = $parameter['IS_SHOW'];
            $array[$key]['PARENT_META_DATA_CODE'] = $parameter['PARENT_META_DATA_CODE'];
            $array[$key]['META_PROCESS_PARAM_LINK_ID'] = '';
            $array[$key]['MAIN_BP_ID'] = '';
            $array[$key]['DO_BP_ID'] = '';
            $array[$key]['DO_BP_PARAM_PATH'] = '';
            $array[$key]['DO_BP_PARAM_IS_INPUT'] = '';
            $array[$key]['DONE_BP_ID'] = '';
            $array[$key]['DONE_MODEL_ID'] = '';
            $array[$key]['DONE_BP_PARAM_PATH'] = '';
            $array[$key]['DONE_BP_PARAM_IS_INPUT'] = '';
            $array[$key]['DEFAULT_VALUE'] = '';

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
                    $array[$key]['DONE_MODEL_ID'] = $row['DONE_MODEL_ID'];
                    $array[$key]['DONE_MODEL_PARAM_PATH'] = $row['DONE_MODEL_PARAM_PATH'];
                    $array[$key]['DONE_MODEL_PARAM_IS_INPUT'] = $row['DONE_MODEL_PARAM_IS_INPUT'];
                    $array[$key]['DEFAULT_VALUE'] = $row['DEFAULT_VALUE'];
                    break;
                }
            }
        }

        return $array;
    }

    public function getParameterListWithPathModel($processBpId, $isInput) {
        
        if (isset(self::$doBpParamList[$processBpId . '_' . $isInput])) {
                       
            return self::$doBpParamList[$processBpId . '_' . $isInput];
            
        } else {
            
            $metaDatas = array();
            $t = 0;

            $data = $this->db->GetAll("
                SELECT 
                    TMP.* 
                FROM (
                    SELECT 
                        T0.PARAM_NAME AS META_DATA_CODE,  
                        T0.LABEL_NAME AS META_DATA_NAME, 
                        T0.DATA_TYPE,
                        T0.RECORD_TYPE,
                        T0.IS_SHOW,
                        T0.PARAM_PATH, 
                        
                        CASE 
                            WHEN T1.ORDER_NUMBER IS NULL AND T2.ORDER_NUMBER IS NULL 
                            THEN T0.ORDER_NUMBER 
                            WHEN T1.ORDER_NUMBER IS NOT NULL AND T2.ORDER_NUMBER IS NULL 
                            THEN T1.ORDER_NUMBER 
                            WHEN T0.ORDER_NUMBER IS NOT NULL AND T1.ORDER_NUMBER IS NOT NULL AND T2.ORDER_NUMBER IS NOT NULL 
                            THEN T2.ORDER_NUMBER 
                        ELSE 0 END AS L1_ORDER, 
                        
                        CASE 
                            WHEN T1.ORDER_NUMBER IS NULL AND T2.ORDER_NUMBER IS NULL 
                            THEN 0 
                            WHEN T1.ORDER_NUMBER IS NOT NULL AND T2.ORDER_NUMBER IS NULL 
                            THEN T1.ORDER_NUMBER 
                            WHEN T0.ORDER_NUMBER IS NOT NULL AND T1.ORDER_NUMBER IS NOT NULL AND T2.ORDER_NUMBER IS NOT NULL 
                            THEN T2.ORDER_NUMBER 
                        ELSE 0 END AS L2_ORDER, 
                        
                        CASE 
                            WHEN T1.ORDER_NUMBER IS NULL AND T2.ORDER_NUMBER IS NULL 
                            THEN 0 
                            WHEN T1.ORDER_NUMBER IS NOT NULL AND T2.ORDER_NUMBER IS NULL 
                            THEN T0.ORDER_NUMBER 
                            WHEN T0.ORDER_NUMBER IS NOT NULL AND T1.ORDER_NUMBER IS NOT NULL AND T2.ORDER_NUMBER IS NOT NULL 
                            THEN T0.ORDER_NUMBER 
                        ELSE 0 END AS L3_ORDER, 
                        
                        CASE 
                            WHEN T1.ORDER_NUMBER IS NULL AND T2.ORDER_NUMBER IS NULL 
                            THEN 0 
                            WHEN T1.ORDER_NUMBER IS NOT NULL AND T2.ORDER_NUMBER IS NULL 
                            THEN T0.ORDER_NUMBER 
                            WHEN T0.ORDER_NUMBER IS NOT NULL AND T1.ORDER_NUMBER IS NOT NULL AND T2.ORDER_NUMBER IS NOT NULL AND T3.ORDER_NUMBER IS NULL 
                            THEN T2.ORDER_NUMBER 
                            WHEN T0.ORDER_NUMBER IS NOT NULL AND T1.ORDER_NUMBER IS NOT NULL AND T2.ORDER_NUMBER IS NOT NULL AND T3.ORDER_NUMBER IS NOT NULL 
                            THEN T0.ORDER_NUMBER 
                        ELSE 0 END AS L4_ORDER, 
                        
                        CASE 
                            WHEN T1.ORDER_NUMBER IS NULL AND T2.ORDER_NUMBER IS NULL 
                            THEN 0 
                            WHEN T1.ORDER_NUMBER IS NOT NULL AND T2.ORDER_NUMBER IS NULL 
                            THEN T0.ORDER_NUMBER 
                            WHEN T0.ORDER_NUMBER IS NOT NULL AND T1.ORDER_NUMBER IS NOT NULL AND T2.ORDER_NUMBER IS NOT NULL AND T3.ORDER_NUMBER IS NULL 
                            THEN T2.ORDER_NUMBER 
                            WHEN T0.ORDER_NUMBER IS NOT NULL AND T1.ORDER_NUMBER IS NOT NULL AND T2.ORDER_NUMBER IS NOT NULL AND T3.ORDER_NUMBER IS NOT NULL AND T4.ORDER_NUMBER IS NULL 
                            THEN T3.ORDER_NUMBER 
                            WHEN T0.ORDER_NUMBER IS NOT NULL AND T1.ORDER_NUMBER IS NOT NULL AND T2.ORDER_NUMBER IS NOT NULL AND T3.ORDER_NUMBER IS NOT NULL AND T4.ORDER_NUMBER IS NOT NULL 
                            THEN T0.ORDER_NUMBER 
                        ELSE 0 END AS L5_ORDER, 
                        
                        CASE 
                            WHEN T1.ORDER_NUMBER IS NULL AND T2.ORDER_NUMBER IS NULL 
                            THEN 0 
                            WHEN T1.ORDER_NUMBER IS NOT NULL AND T2.ORDER_NUMBER IS NULL 
                            THEN T0.ORDER_NUMBER 
                            WHEN T0.ORDER_NUMBER IS NOT NULL AND T1.ORDER_NUMBER IS NOT NULL AND T2.ORDER_NUMBER IS NOT NULL AND T3.ORDER_NUMBER IS NULL 
                            THEN T2.ORDER_NUMBER 
                            WHEN T0.ORDER_NUMBER IS NOT NULL AND T1.ORDER_NUMBER IS NOT NULL AND T2.ORDER_NUMBER IS NOT NULL AND T3.ORDER_NUMBER IS NOT NULL AND T4.ORDER_NUMBER IS NULL 
                            THEN T3.ORDER_NUMBER 
                            WHEN T0.ORDER_NUMBER IS NOT NULL AND T1.ORDER_NUMBER IS NOT NULL AND T2.ORDER_NUMBER IS NOT NULL AND T3.ORDER_NUMBER IS NOT NULL AND T4.ORDER_NUMBER IS NOT NULL AND T5.ORDER_NUMBER IS NULL
                            THEN T4.ORDER_NUMBER 
                            WHEN T0.ORDER_NUMBER IS NOT NULL AND T1.ORDER_NUMBER IS NOT NULL AND T2.ORDER_NUMBER IS NOT NULL AND T3.ORDER_NUMBER IS NOT NULL AND T4.ORDER_NUMBER IS NOT NULL AND T5.ORDER_NUMBER IS NOT NULL
                            THEN T0.ORDER_NUMBER 
                        ELSE 0 END AS L6_ORDER 
                        
                    FROM META_PROCESS_PARAM_ATTR_LINK T0 
                    
                        LEFT JOIN META_PROCESS_PARAM_ATTR_LINK T1 ON T1.ID = T0.PARENT_ID 
                        LEFT JOIN META_PROCESS_PARAM_ATTR_LINK T2 ON T2.ID = T1.PARENT_ID 
                        LEFT JOIN META_PROCESS_PARAM_ATTR_LINK T3 ON T3.ID = T2.PARENT_ID 
                        LEFT JOIN META_PROCESS_PARAM_ATTR_LINK T4 ON T4.ID = T3.PARENT_ID 
                        LEFT JOIN META_PROCESS_PARAM_ATTR_LINK T5 ON T5.ID = T4.PARENT_ID 
                        
                    WHERE T0.PROCESS_META_DATA_ID = ".$this->db->Param(0)." 
                        AND ".$this->db->IfNull('T0.IS_INPUT', '1')." = ".$this->db->Param(1)." 
                ) TMP 
                ORDER BY 
                    TMP.L1_ORDER ASC, 
                    TMP.L2_ORDER ASC, 
                    TMP.L3_ORDER ASC, 
                    TMP.L4_ORDER ASC, 
                    TMP.L5_ORDER ASC, 
                    TMP.L6_ORDER ASC", 
                array($processBpId, $isInput)
            );

            foreach ($data as $row) {

                $parentPath = $row['PARAM_PATH'];
                $pos = strripos($row['PARAM_PATH'], '.');

                if ($pos != false) {
                    $parentPath = substr($row['PARAM_PATH'], 0, $pos);
                } else {
                    $parentPath = '';
                }

                $metaDatas[$t]['META_DATA_NAME'] = str_repeat('-', substr_count($row['PARAM_PATH'], '.')) . Lang::line($row['META_DATA_NAME']);
                $metaDatas[$t]['DATA_TYPE'] = $row['DATA_TYPE'];
                $metaDatas[$t]['RECORD_TYPE'] = $row['RECORD_TYPE'];
                $metaDatas[$t]['IS_SHOW'] = ($row['IS_SHOW'] == 1 ? $row['IS_SHOW'] : 0);
                $metaDatas[$t]['META_DATA_CODE'] = $row['PARAM_PATH'];
                $metaDatas[$t]['PARENT_META_DATA_CODE'] = $parentPath;

                $t++;
            }
            
            self::$doBpParamList[$processBpId . '_' . $isInput] = $metaDatas;
            
            return $metaDatas;
        }
    }

    public function getParameterListWithPath2Model($processBpId) {
        $param = array(
            'systemMetaGroupId' => '1642419374729118',
            'ignorePermission' => 1, 
            'showQuery' => 0,
            'criteria' => array(
                'filterMainId' => array(
                    array(
                        'operator' => '=',
                        'operand' => $processBpId
                    )
                )
            )
        );

        $data = $this->ws->runSerializeResponse(Mdwebservice::$gfServiceAddress, Mddatamodel::$getDataViewCommand, $param);

        unset($data['result']['aggregatecolumns']);
        unset($data['result']['paging']);
        return $data['result'];
    }

    public function getParameterList($isOutput = 0, $mainBpId, $doBpId, $isShow = 0) {

        $metaDatas = self::getParameterListModel($isOutput, $mainBpId, $doBpId, $isShow);
        $doneBpList = self::getMetaDoneBpListModel($mainBpId, $doBpId);
        
        Mdprocessflow::$doneBpList = $doneBpList;

        return self::drawParameterList($metaDatas, $doneBpList, $mainBpId, $doBpId);
    }

    public function getParameterList2($isOutput = 0, $mainBpId, $doBpId, $isShow = 0, $mainDomainBpId) {

        $metaDatas = self::getParameterList2Model($isOutput, $mainBpId, $doBpId, $isShow, $mainDomainBpId);
        $doneBpList = self::getMetaDoneBpListModel($mainBpId, $doBpId);
        $doneBpList = array_merge($doneBpList, [[
            'META_DATA_ID' => Input::post("mainBpId"),
            'META_DATA_NAME' => Input::post("indicatorName").' /indicator/',
        ]]);
        
        Mdprocessflow::$doneBpList = $doneBpList;

        return self::drawParameterList2($metaDatas, $doneBpList, $mainBpId, $doBpId);
    }

    public function drawParameterList2($metaDatas, $doneBpList, $mainBpId, $doBpId, $depth = 0, $path = "") {

        $html = array();

        foreach ($metaDatas as $row) {
            
            $mainBpId = $mainBpId;
            $doneBpId = '';
            $doneBpParamIsInput = 0;
            $doneBpParamPath = '';
            $metaProcessParamLinkId = '';
            $defaultValue = '';

            if (isset($row['META_PROCESS_PARAM_LINK_ID'])) {
                $metaProcessParamLinkId = $row['META_PROCESS_PARAM_LINK_ID'];
            }
            
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
                $doneBpId = $row['DONE_MODEL_ID'] ? $row['DONE_MODEL_ID'] : $row['DONE_BP_ID'];
                $doneBpParamIsInput = $row['DONE_MODEL_PARAM_IS_INPUT'] ? $row['DONE_MODEL_PARAM_IS_INPUT'] : $row['DONE_BP_PARAM_IS_INPUT'];
                $doneBpParamPath = $row['DONE_MODEL_PARAM_PATH'] ? $row['DONE_MODEL_PARAM_PATH'] : $row['DONE_BP_PARAM_PATH'];
                $defaultValue = $row['DEFAULT_VALUE'];
            }

            $oneRowMetaDataCode = str_replace('.', '-', $row['META_DATA_CODE']);
            $_parentMetaDataCode = str_replace('.', '-', $row['PARENT_META_DATA_CODE']);
            $isShow = $row['IS_SHOW'];
            $rowStyle = '';

            if (empty($isShow)) {
                $rowStyle = 'style="display:none;"';
            }

            $html[] = '<tr class="tabletree-' . $oneRowMetaDataCode . ' tabletree-parent-' . $_parentMetaDataCode . '" data-show="' . $isShow . '" ' . $rowStyle . ' data-row="' . $oneRowMetaDataCode . '" data-row-parent="' . $_parentMetaDataCode . '">';
            $html[] = '<td class="middle">';
            $html[] = $row['META_DATA_NAME'];
            $html[] = Form::hidden(array('name' => $doBpId . 'dataType[]', 'id' => $doBpId . 'dataType', 'value' => $row['DATA_TYPE']));
            $html[] = Form::hidden(array('name' => $doBpId . 'inputMetaDataName[]', 'id' => $doBpId . 'inputMetaDataName', 'value' => $row['META_DATA_NAME']));
            $html[] = Form::hidden(array('name' => $doBpId . 'inputMetaDataIsProcess[]', 'id' => $doBpId . 'inputMetaDataIsProcess', 'value' => $row['DONE_MODEL_ID'] ? 0 : 1));
            $html[] = Form::hidden(array('name' => $doBpId . 'id[]', 'class' => 'id', 'value' => $metaProcessParamLinkId));
            $html[] = '</td>';
            $html[] = '<td>';
            $html[] = Form::text(array('name' => $doBpId . 'inputDoBpParamPath[]', 'id' => $doBpId . 'inputDoBpParamPath', 'value' => $row['META_DATA_CODE'], 'class' => 'form-control form-control-sm', 'readonly' => 'readonly'));
            $html[] = '</td>';
            $html[] = '<td><div class="d-flex"><i class="far fa-arrow-alt-right mt5 ml3" style="font-size: 16px;"></i> ';
            $html[] = Form::select(array(
                'name' => $doBpId . 'inputDoneBpId[]',
                'id' => $doBpId . 'inputDoneBpId',
                'class' => 'form-control form-control-sm ml10 inputDoneBpId ' . $_parentMetaDataCode,
                'data' => $doneBpList,
                'op_value' => 'META_DATA_ID',
                'op_text' => 'META_DATA_NAME',
                'op_custom_attr' => array(array(
                    'attr' => 'data-metacode',
                    'key' => 'META_DATA_CODE'
                )),                
                'data-placeholder' => '...',
                'value' => $doneBpId,
                'text' => ' ',
                'required' => 'required'
            ));
            $html[] = '</div></td>';
            $html[] = '<td class="middle text-center">';
            
            $html[] = Form::hidden(array(
                'name' => $doBpId . 'inputDoneBpParamIsInputHidden[]',
                'id' => $doBpId . 'inputDoneBpParamIsInputHidden',
                'value' => $doneBpParamIsInput
            ));

            $html[] = Form::checkbox(array(
                'name' => $doBpId . 'inputDoneBpParamIsInput[]',
                'id' => $doBpId . 'inputDoneBpParamIsInput',
                'value' => 1,
                'saved_val' => $doneBpParamIsInput,
                'class' => 'doneBpParamIsInput notuniform'
            ));

            $html[] = '</td>';
            
            if (!empty($row['DONE_MODEL_ID'])) {
                
                $html[] = '<td>';
                $html[] = Form::select(array(
                    'name' => $doBpId . 'inputDoneBpParamId[]',
                    'id' => $doBpId . 'inputDoneBpParamId',
                    'class' => 'form-control form-control-sm',
                    'data' => self::getParameterListWithPath2Model($row['DONE_MODEL_ID']),
                    'op_value' => 'columnnamepath',
                    'op_text' => 'labelname',
                    'data-placeholder' => '...',
                    'value' => $doneBpParamPath,
                    'text' => ' ',
                    'required' => 'required'
                ));
                $html[] = '</td>';
            
            } elseif (!empty($row['DONE_BP_ID'])) {
                
                $html[] = '<td>';
                $html[] = Form::select(array(
                    'name' => $doBpId . 'inputDoneBpParamId[]',
                    'id' => $doBpId . 'inputDoneBpParamId',
                    'class' => 'form-control form-control-sm',
                    'data' => self::getParameterListWithPathModel($row['DONE_BP_ID'], $row['DONE_BP_PARAM_IS_INPUT']),
                    'op_value' => 'META_DATA_CODE',
                    'op_text' => 'META_DATA_NAME| |-| |META_DATA_CODE',
                    'data-placeholder' => '...',
                    'value' => $doneBpParamPath,
                    'text' => ' ',
                    'required' => 'required'
                ));
                $html[] = '</td>';
                
            } else {
                $html[] = '<td>';
                $html[] = Form::select(array(
                    'name' => $doBpId . 'inputDoneBpParamId[]',
                    'id' => $doBpId . 'inputDoneBpParamId',
                    'class' => 'form-control form-control-sm',
                    'op_value' => 'META_DATA_CODE',
                    'op_text' => 'META_DATA_NAME',
                    'data-placeholder' => '...',
                    'value' => $doneBpParamPath,
                    'text' => ' ',
                    'required' => 'required'
                ));
                $html[] = '</td>';
            }

            $html[] = '<td>';
            $html[] = Form::text(array('name' => $doBpId . 'inputDoneBpParamPath[]', 'id' => $doBpId . 'inputDoneBpParamPath', 'value' => $doneBpParamPath, 'class' => 'form-control form-control-sm', 'readonly' => 'readonly'));
            $html[] = '</td>';
            $html[] = '<td>';
            
            if ($row['DATA_TYPE'] != 'group') {
                $html[] = Form::text(array('name' => $doBpId . 'defaultValue[]', 'id' => $doBpId . 'defaultValue', 'value' => $defaultValue, 'class' => 'form-control form-control-sm'));
            } else {
                $html[] = Form::hidden(array('name' => $doBpId . 'defaultValue[]', 'id' => $doBpId . 'defaultValue'));
            }
            
            $html[] = '</td>';
            $html[] = '<td class="middle text-center">';
            
            if ($row['DATA_TYPE'] != 'group') {
                $html[] = Form::button(array('class' => 'btn red btn-xs', 'onclick' => 'removeParameter(this)', 'value' => '<i class="far fa-trash"></i>'));
            }
            
            $html[] = '</td>';
            $html[] = '</tr>';
        }
        
        return implode('', $html);
    }
    
    public function drawParameterList($metaDatas, $doneBpList, $mainBpId, $doBpId, $depth = 0, $path = "") {

        $html = array();

        foreach ($metaDatas as $row) {
            
            $mainBpId = $mainBpId;
            $doneBpId = '';
            $doneBpParamIsInput = 0;
            $doneBpParamPath = '';
            $metaProcessParamLinkId = '';
            $defaultValue = '';

            if (isset($row['META_PROCESS_PARAM_LINK_ID'])) {
                $metaProcessParamLinkId = $row['META_PROCESS_PARAM_LINK_ID'];
            }
            
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

            $html[] = '<tr class="tabletree-' . $oneRowMetaDataCode . ' tabletree-parent-' . $_parentMetaDataCode . '" data-show="' . $isShow . '" ' . $rowStyle . ' data-row="' . $oneRowMetaDataCode . '" data-row-parent="' . $_parentMetaDataCode . '">';
            $html[] = '<td class="middle">';
            $html[] = $row['META_DATA_NAME'];
            $html[] = Form::hidden(array('name' => $doBpId . 'dataType[]', 'id' => $doBpId . 'dataType', 'value' => $row['DATA_TYPE']));
            $html[] = Form::hidden(array('name' => $doBpId . 'recordType[]', 'id' => $doBpId . 'recordType', 'value' => $row['RECORD_TYPE']));
            $html[] = Form::hidden(array('name' => $doBpId . 'inputMetaDataName[]', 'id' => $doBpId . 'inputMetaDataName', 'value' => $row['META_DATA_NAME']));
            $html[] = Form::hidden(array('name' => $doBpId . 'id[]', 'class' => 'id', 'value' => $metaProcessParamLinkId));
            $html[] = '</td>';
            $html[] = '<td>';
            $html[] = Form::text(array('name' => $doBpId . 'inputDoBpParamPath[]', 'id' => $doBpId . 'inputDoBpParamPath', 'value' => $row['META_DATA_CODE'], 'class' => 'form-control form-control-sm', 'readonly' => 'readonly'));
            $html[] = '</td>';
            $html[] = '<td><div class="d-flex"><i class="far fa-arrow-alt-right mt5 ml3" style="font-size: 16px;"></i> ';
            $html[] = Form::select(array(
                'name' => $doBpId . 'inputDoneBpId[]',
                'id' => $doBpId . 'inputDoneBpId',
                'class' => 'form-control form-control-sm ml10 inputDoneBpId ' . $_parentMetaDataCode,
                'data' => $doneBpList,
                'op_value' => 'META_DATA_ID',
                'op_text' => 'META_DATA_NAME',
                'data-placeholder' => '...',
                'value' => $doneBpId,
                'text' => ' ',
                'required' => 'required'
            ));
            $html[] = '</div></td>';
            $html[] = '<td class="middle text-center">';
            
            $html[] = Form::hidden(array(
                'name' => $doBpId . 'inputDoneBpParamIsInputHidden[]',
                'id' => $doBpId . 'inputDoneBpParamIsInputHidden',
                'value' => $doneBpParamIsInput
            ));

            $html[] = Form::checkbox(array(
                'name' => $doBpId . 'inputDoneBpParamIsInput[]',
                'id' => $doBpId . 'inputDoneBpParamIsInput',
                'value' => 1,
                'saved_val' => $doneBpParamIsInput,
                'class' => 'doneBpParamIsInput notuniform'
            ));

            $html[] = '</td>';
            
            if (!empty($row['DONE_BP_ID'])) {
                
                $html[] = '<td>';
                $html[] = Form::select(array(
                    'name' => $doBpId . 'inputDoneBpParamId[]',
                    'id' => $doBpId . 'inputDoneBpParamId',
                    'class' => 'form-control form-control-sm',
                    'data' => self::getParameterListWithPathModel($row['DONE_BP_ID'], $row['DONE_BP_PARAM_IS_INPUT']),
                    'op_value' => 'META_DATA_CODE',
                    'op_text' => 'META_DATA_NAME| |-| |META_DATA_CODE',
                    'data-placeholder' => '...',
                    'value' => $doneBpParamPath,
                    'text' => ' ',
                    'required' => 'required'
                ));
                $html[] = '</td>';
                
            } else {
                $html[] = '<td>';
                $html[] = Form::select(array(
                    'name' => $doBpId . 'inputDoneBpParamId[]',
                    'id' => $doBpId . 'inputDoneBpParamId',
                    'class' => 'form-control form-control-sm',
                    'op_value' => 'META_DATA_CODE',
                    'op_text' => 'META_DATA_NAME',
                    'data-placeholder' => '...',
                    'value' => $doneBpParamPath,
                    'text' => ' ',
                    'required' => 'required'
                ));
                $html[] = '</td>';
            }

            $html[] = '<td>';
            $html[] = Form::text(array('name' => $doBpId . 'inputDoneBpParamPath[]', 'id' => $doBpId . 'inputDoneBpParamPath', 'value' => $doneBpParamPath, 'class' => 'form-control form-control-sm', 'readonly' => 'readonly'));
            $html[] = '</td>';
            $html[] = '<td>';
            
            if ($row['DATA_TYPE'] != 'group') {
                $html[] = Form::text(array('name' => $doBpId . 'defaultValue[]', 'id' => $doBpId . 'defaultValue', 'value' => $defaultValue, 'class' => 'form-control form-control-sm'));
            } else {
                $html[] = Form::hidden(array('name' => $doBpId . 'defaultValue[]', 'id' => $doBpId . 'defaultValue'));
            }
            
            $html[] = '</td>';
            $html[] = '<td class="middle text-center">';
            
            if ($row['DATA_TYPE'] != 'group') {
                $html[] = Form::button(array('class' => 'btn red btn-xs', 'onclick' => 'removeParameter(this)', 'value' => '<i class="far fa-trash"></i>'));
            }
            
            $html[] = '</td>';
            $html[] = '</tr>';
        }
        
        return implode('', $html);
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
        $result = $this->ws->runResponse(GF_SERVICE_ADDRESS, 'PL_MPW_011', $param);
        
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
                'id' => (empty($_POST[$doBpId . 'id'][$k]) ? '' : Input::param($_POST[$doBpId . 'id'][$k])),
                'metaProcessLink' => array('id' => self::getBusinessProcessLinkId($mainBpId), 'rowState' => 'SELECTED'),
                'doBpId' => $doBpId,
                'doBpParamPath' => (empty($_POST[$doBpId . 'inputDoBpParamPath'][$k]) ? '' : Input::param($_POST[$doBpId . 'inputDoBpParamPath'][$k])),
                'doBpParamIsInput' => $doBpParamIsInput,
                'doneBpId' => (empty($_POST[$doBpId . 'inputDoneBpId'][$k]) ? '' : Input::param($_POST[$doBpId . 'inputDoneBpId'][$k])),
                'recordType' => (empty($_POST[$doBpId . 'recordType'][$k]) ? '' : Input::param($_POST[$doBpId . 'recordType'][$k])),
                'doneBpParamPath' => (empty($_POST[$doBpId . 'inputDoneBpParamPath'][$k]) ? '' : Input::param($_POST[$doBpId . 'inputDoneBpParamPath'][$k])),
                'doneBpParamIsInput' => (($_POST[$doBpId . 'inputDoneBpParamIsInputHidden'][$k] == '1') ? 1 : 0),
                'defaultValue' => $_POST[$doBpId . 'defaultValue'][$k]
                )
            );
        }

        if (count($param) != 0) {

            $paramData = array(
                'mainBpId' => $mainBpId,
                'doBpId' => $doBpId,
                'sourcelink:metaprocessparam' => $param
            );

            $result = $this->ws->runResponse(GF_SERVICE_ADDRESS, 'PL_MPP_011', $paramData);

            if ($result['status'] == 'success') {
                return array('status' => 'success', 'message' => Lang::line('msg_save_success'));
            } else {
                return array('status' => 'error', 'message' => $this->ws->getResponseMessage($result));
            }
        }

        return $result;
    }

    public function saveMetaProcessParameter2Model() {

        $result = array('status' => 'error', 'message' => Lang::line('msg_error'));
        $mainBpId = Input::numeric('metaDataId');
        $doBpId = Input::post('doProcessId');
        $doBpParamIsInput = Input::post('doBpParamIsInput');
        
        $data = $_POST[$doBpId . 'inputMetaDataName'];

        $param = array();

        $this->db->Execute('DELETE FROM META_PROCESS_PARAM_LINK WHERE MAIN_DOMAIN_BP_ID = ' . $mainBpId);
        foreach ($data as $k => $val) {
            $param = array(
                'META_PROCESS_PARAM_LINK_ID' => getUIDAdd($k),
                'MAIN_DOMAIN_BP_ID' => $mainBpId,
                'DO_BP_ID' => $doBpId,
                'DO_BP_PARAM_PATH' => (empty($_POST[$doBpId . 'inputDoBpParamPath'][$k]) ? '' : Input::param($_POST[$doBpId . 'inputDoBpParamPath'][$k])),
                'DO_BP_PARAM_IS_INPUT' => $doBpParamIsInput,
                'DONE_MODEL_ID' => (empty($_POST[$doBpId . 'inputDoneBpId'][$k]) ? '' : Input::param($_POST[$doBpId . 'inputDoneBpId'][$k])),
                'DONE_MODEL_PARAM_PATH' => (empty($_POST[$doBpId . 'inputDoneBpParamPath'][$k]) ? '' : Input::param($_POST[$doBpId . 'inputDoneBpParamPath'][$k])),
                'DONE_MODEL_PARAM_IS_INPUT' => (($_POST[$doBpId . 'inputDoneBpParamIsInputHidden'][$k] == '1') ? 1 : 0),
                'DEFAULT_VALUE' => $_POST[$doBpId . 'defaultValue'][$k]
            );
            
            if ($_POST[$doBpId . 'inputMetaDataIsProcess'][$k]) {
                $param['DONE_BP_ID'] = (empty($_POST[$doBpId . 'inputDoneBpId'][$k]) ? '' : Input::param($_POST[$doBpId . 'inputDoneBpId'][$k]));
                $param['DONE_BP_PARAM_PATH'] = (empty($_POST[$doBpId . 'inputDoneBpParamPath'][$k]) ? '' : Input::param($_POST[$doBpId . 'inputDoneBpParamPath'][$k]));
                $param['DONE_BP_PARAM_IS_INPUT'] = (($_POST[$doBpId . 'inputDoneBpParamIsInputHidden'][$k] == '1') ? 1 : 0);
                unset($param['DONE_MODEL_ID']);
                unset($param['DONE_MODEL_PARAM_PATH']);
                unset($param['DONE_MODEL_PARAM_IS_INPUT']);
            }
            $this->db->AutoExecute("META_PROCESS_PARAM_LINK", $param);
        }

        return array('status' => 'success', 'message' => Lang::line('msg_save_success'));

        // if (count($param) != 0) {

        //     $paramData = array(
        //         'mainBpId' => $mainBpId,
        //         'doBpId' => $doBpId,
        //         'sourcelink:metaprocessparam' => $param
        //     );

        //     $result = $this->ws->runResponse(GF_SERVICE_ADDRESS, 'PL_MPP_011', $paramData);

        //     if ($result['status'] == 'success') {
        //         return array('status' => 'success', 'message' => Lang::line('msg_save_success'));
        //     } else {
        //         return array('status' => 'error', 'message' => $this->ws->getResponseMessage($result));
        //     }
        // }

        // return $result;
    }

    public function saveMetaProcessParameterIndicatorModel() {

        $result = array('status' => 'error', 'message' => Lang::line('msg_error'));
        $mainBpId = Input::numeric('metaDataId');
        $doBpId = Input::post('doProcessId');
        $doBpParamIsInput = Input::post('doBpParamIsInput');
        
        $data = $_POST[$doBpId . 'inputMetaDataName'];

        $param = array();

        $this->db->Execute('DELETE FROM META_PROCESS_PARAM_LINK WHERE MAIN_BP_ID = ' . $mainBpId . ' AND DO_BP_ID = ' . $doBpId);
        foreach ($data as $k => $val) {
            $doneBpId = (empty($_POST[$doBpId . 'inputDoneBpId'][$k]) ? '' : Input::param($_POST[$doBpId . 'inputDoneBpId'][$k]));
            $doneBpParam = (empty($_POST[$doBpId . 'inputDoneBpParamPath'][$k]) ? '' : Input::param($_POST[$doBpId . 'inputDoneBpParamPath'][$k]));
            if ($doneBpId && $doneBpParam || $_POST[$doBpId . 'defaultValue'][$k]) {
                $param = array(
                    'META_PROCESS_PARAM_LINK_ID' => getUIDAdd($k),
                    'MAIN_BP_ID' => $mainBpId,
                    'DO_BP_ID' => $doBpId,
                    'DO_BP_PARAM_PATH' => (empty($_POST[$doBpId . 'inputDoBpParamPath'][$k]) ? '' : Input::param($_POST[$doBpId . 'inputDoBpParamPath'][$k])),
                    'DO_BP_PARAM_IS_INPUT' => $doBpParamIsInput,
                    'DONE_BP_ID' => $doneBpId,
                    'DONE_BP_PARAM_PATH' => $doneBpParam,
                    'DONE_BP_PARAM_IS_INPUT' => (($_POST[$doBpId . 'inputDoneBpParamIsInputHidden'][$k] == '1') ? 1 : 0),                
                    'DEFAULT_VALUE' => $_POST[$doBpId . 'defaultValue'][$k],
                    'CREATED_DATE'     => Date::currentDate(), 
                    'CREATED_USER_ID'  => Ue::sessionUserKeyId()                
                );
                
                if (!$_POST[$doBpId . 'inputMetaDataIsProcess'][$k]) {
                    $param['DONE_MODEL_ID'] = (empty($_POST[$doBpId . 'inputDoneBpId'][$k]) ? '' : Input::param($_POST[$doBpId . 'inputDoneBpId'][$k]));
                }
                $this->db->AutoExecute("META_PROCESS_PARAM_LINK", $param);
            }
        }

        return array('status' => 'success', 'message' => Lang::line('msg_save_success'));

        // if (count($param) != 0) {

        //     $paramData = array(
        //         'mainBpId' => $mainBpId,
        //         'doBpId' => $doBpId,
        //         'sourcelink:metaprocessparam' => $param
        //     );

        //     $result = $this->ws->runResponse(GF_SERVICE_ADDRESS, 'PL_MPP_011', $paramData);

        //     if ($result['status'] == 'success') {
        //         return array('status' => 'success', 'message' => Lang::line('msg_save_success'));
        //     } else {
        //         return array('status' => 'error', 'message' => $this->ws->getResponseMessage($result));
        //     }
        // }

        // return $result;
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
                if ($row['pageSourceId']) {
                    $processWorkFlowIds .= ', ' . $row['pageSourceId'];
                }
            }
            
            if ($processWorkFlowIds) {
                $and = " AND MPW.META_PROCESS_WORKFLOW_ID IN ($processWorkFlowIds)";
            } else {
                $and = '';
            }
        } elseif (Input::postCheck('connection2')) {
            $connection = $_POST['connection2'];
            $resultXml = self::extractBpmnXml($connection, true); 
            foreach ($resultXml["task"] as $row) {
                if ($row['@attributes']["processid"] && $getmetaid = self::getBpIndicatorModel($row['@attributes']["processid"])) {
                    $processWorkFlowIds .= ', ' . $getmetaid;
                }
            }
            
            if ($processWorkFlowIds) {
                $and = " AND MD.META_DATA_ID IN ($processWorkFlowIds)";

                if ($doProcessId != $mainBpId) {
                    $and .= " OR MPW.DO_BP_ID = " . $mainBpId;
                }
        
                $data = $this->db->GetAll("
                    SELECT 
                        MD.META_DATA_ID, 
                        MD.META_DATA_NAME, 
                        MD.META_DATA_CODE 
                    FROM META_PROCESS_WORKFLOW MPW 
                        INNER JOIN META_DATA MD ON MD.META_DATA_ID = MPW.DO_BP_ID 
                    WHERE MPW.MAIN_BP_ID = $mainBpId  
                        $and 
                        AND MPW.IS_ACTIVE = 1 
                    GROUP BY 
                        MD.META_DATA_ID,
                        MD.META_DATA_NAME,
                        MD.META_DATA_CODE");
        
                return $data;                

            } else {
                $and = '';
            }
        }

        if ($doProcessId != $mainBpId) {
            $and .= " OR MPW.DO_BP_ID = " . $mainBpId;
        }

        $data = $this->db->GetAll("
            SELECT 
                MD.META_DATA_ID, 
                MD.META_DATA_NAME, 
                MD.META_DATA_CODE 
            FROM META_PROCESS_WORKFLOW MPW 
                INNER JOIN META_DATA MD ON MD.META_DATA_ID = MPW.DO_BP_ID 
            WHERE MPW.MAIN_BP_ID = $mainBpId  
                $and 
                AND MPW.IS_ACTIVE = 1 
            GROUP BY 
                MD.META_DATA_ID,
                MD.META_DATA_NAME,
                MD.META_DATA_CODE");

        return $data;
    }

    public function getVisualDataListModel($mainBpId) {
        $row = $this->db->GetRow("SELECT ADDON_DATA FROM META_DATA WHERE META_DATA_ID = " . $mainBpId);

        if ($row) {
            return $row['ADDON_DATA'];
        }
        return false;
    }

    public function getBpmnXmlModel($mainBpId) {
        $row = $this->db->GetRow("SELECT * FROM EIS_BPM_PROCESS WHERE ID = " . $mainBpId);

        if ($row) {
            return $row;
        }
        return false;
    }

    public function getBpmnXmlIndicatorModel($mainBpId) {
        $row = $this->db->GetRow("SELECT * FROM KPI_INDICATOR WHERE ID = " . $mainBpId);

        if ($row) {
            return $row;
        }
        return false;
    }

    public function saveBpmnDraftModel() {
        $mainBpId = Input::post('mainBpId');
        $xml = Input::post('xml');

        $data = array(
            'DATA_MODEL_ID' => Input::post('dataModelId')
        );
        $this->db->AutoExecute('EIS_BPM_PROCESS', $data, 'UPDATE', "ID = " . $mainBpId);        
        $this->db->UpdateClob('EIS_BPM_PROCESS', 'BPMN', $xml, 'ID=' . $mainBpId);
        return array('status' => 'success');
    }

    public function saveBpmnIndicatorDraftModel() {
        $mainBpId = Input::post('mainBpId');
        $xml = Input::post('xml');
        $this->db->UpdateClob('KPI_INDICATOR', 'GRAPH_JSON', $xml, 'ID=' . $mainBpId);
        return array('status' => 'success');
    }

    public function saveVisualMetaProcessModel($object = '', $connect = '') {
        $mainBpId = Input::post('mainBpId');

        $addOnData = json_encode(array('object' => $object, 'connect' => $connect));
        $result = $this->db->UpdateClob('META_DATA', 'ADDON_DATA', $addOnData, 'META_DATA_ID=' . $mainBpId);
        $param = array();
        $ProcessWorkFlow = $this->db->GetAll("SELECT META_PROCESS_WORKFLOW_ID, DO_BP_ID, MAIN_BP_ID, BP_ORDER FROM META_PROCESS_WORKFLOW WHERE MAIN_BP_ID = $mainBpId");
        
        foreach ($object as $key => $value) {
            
            $metaProcessWorkFlowId = Input::param($value['metaProcessWorkFlowId']);
            
            if ($value['type'] != 'circle') {
                
                $result = $this->db->GetRow("SELECT META_PROCESS_WORKFLOW_ID FROM META_PROCESS_WORKFLOW WHERE MAIN_BP_ID = $mainBpId AND BP_ORDER = " . $value['bpOrder']);
                
                $data = array(
                    'MAIN_BP_ID' => $mainBpId,
                    'DO_BP_ID' => Input::param($value['dobpid']),
                    'BP_ORDER' => Input::param($value['bpOrder']),
                    'IS_ACTIVE' => 1,
                    'CREATED_DATE' => Date::currentDate("Y-m-d H:i:s"),
                    'CREATED_USER_ID' => Ue::sessionUserKeyId()
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
                
                $wfList = $this->db->GetAll("SELECT META_PROCESS_WORKFLOW_ID FROM META_PROCESS_WORKFLOW WHERE MAIN_BP_ID = $mainBpId");
                
                foreach ($wfList as $wfr) {
                    $resultBehaviour = $this->db->GetRow("SELECT ID FROM META_PROCESS_WF_BEHAVIOUR WHERE META_PROCESS_WF_ID = " . $wfr['META_PROCESS_WORKFLOW_ID'] . " AND NEXT_ORDER = " . $row['BP_ORDER']);
                    if (count($resultBehaviour) > 0) {
                        $this->db->Execute('DELETE FROM META_PROCESS_WF_BEHAVIOUR WHERE ID = ' . $resultBehaviour['ID']);
                    }
                } 
                
                $this->db->Execute('DELETE FROM META_PROCESS_WF_BEHAVIOUR WHERE META_PROCESS_WF_ID = ' . $row['META_PROCESS_WORKFLOW_ID']);
                $this->db->Execute('DELETE FROM META_PROCESS_WORKFLOW WHERE MAIN_BP_ID = ' . $mainBpId . ' AND DO_BP_ID = ' . $row['DO_BP_ID']);
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
        $param = array('id' => $id);
        $result = $this->ws->runResponse(GF_SERVICE_ADDRESS, 'PL_MPP_005', $param);
        return $result;
    }

    public function metaTypeProcessListModel() {
        $data = $this->db->GetAll("
            SELECT 
                META_DATA_ID, 
                META_DATA_NAME, 
                META_DATA_CODE 
            FROM META_DATA
            WHERE META_TYPE_ID IN (" . Mdmetadata::$businessProcessMetaTypeId . ", " . Mdmetadata::$expressionMetaTypeId . ") 
                AND IS_ACTIVE = 1
            ORDER BY META_DATA_NAME ASC");
        
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
        $result = $this->db->AutoExecute('META_PROCESS_WF_BEHAVIOUR', $data, 'UPDATE', "ID = " . Input::post('bpCriteriaId'));
        
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

    public function deleteArrowModel($mainBpId, $doBpId) {
        $row = $this->db->GetRow("
            SELECT 
                META_PROCESS_WORKFLOW_ID 
            FROM META_PROCESS_WORKFLOW 
            WHERE DO_BP_ID = $doBpId 
                AND MAIN_BP_ID = $mainBpId");
        
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
                WFM.ARROW_SHAPE, 
                WFM.WFM_WORKFLOW_NAME 
            FROM META_WFM_WORKFLOW WFM
            WHERE WFM.IS_ACTIVE = 1 
                AND WFM.REF_STRUCTURE_ID = $metaDataId 
            ORDER BY WFM.ID");

        $transitionId = $this->db->GetOne("
            SELECT 
                DISTINCT WT.ID, 
                WS.WFM_STATUS_NAME
            FROM META_WFM_WORKFLOW WW
                INNER JOIN META_WFM_STATUS WS ON WW.ID = WS.WFM_WORKFLOW_ID
                INNER JOIN META_WFM_TRANSITION WT ON WS.ID = WT.NEXT_WFM_STATUS_ID
            WHERE REF_STRUCTURE_ID = $metaDataId 
                AND PREV_WFM_STATUS_ID IS NULL 
                AND IS_TRANSITION = 0 
            ORDER BY WS.WFM_STATUS_NAME ASC");

        $wfmStatusArr = $this->db->GetAll("
            SELECT
                WWS.ID AS WFM_STATUS_ID,
                WWS.WFM_STATUS_NAME,
                WWS.WFM_STATUS_CODE,
                WWS.WFM_STATUS_COLOR
            FROM META_WFM_WORKFLOW WFM
                INNER JOIN META_WFM_STATUS WWS ON WFM.ID = WWS.WFM_WORKFLOW_ID
            WHERE WFM.IS_ACTIVE = 1 
                AND WFM.REF_STRUCTURE_ID = $metaDataId 
                AND WWS.IS_ACTIVE = 1 
            ORDER BY WWS.WFM_STATUS_NAME ASC");
        
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
                    'MOBILE_PROCESS_META_DATA_ID' => Input::post('wfmMobileProcessId'),
                    'IS_NEED_SIGN' => Input::post('wfmIsSign'),
                    'IS_DESC_REQUIRED' => Input::postCheck('isDescRequired') ? '1' : '0',
                    'IS_SEND_MAIL' => Input::postCheck('isSendMail') ? '1' : '0',
                    'IS_MAIL_ACTION' => Input::postCheck('isMailAction') ? '1' : '0',
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

    public function createWfmWorkFlowPackModel() {
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
                    'MOBILE_PROCESS_META_DATA_ID' => Input::post('wfmMobileProcessId'),
                    'IS_NEED_SIGN' => Input::postCheck('wfmIsSign') ? '1' : '0',
                    'IS_DESC_REQUIRED' => Input::postCheck('isDescRequired') ? '1' : '0',
                    'IS_SEND_MAIL' => Input::postCheck('isSendMail') ? '1' : '0',
                    'IS_MAIL_ACTION' => Input::postCheck('isMailAction') ? '1' : '0',
                    'IS_CHECK_ASSIGN_CRITERIA' => 1
                );
                $result = $this->db->AutoExecute('META_WFM_STATUS', $data);
            }
        }
        $usedTransitionId = $this->db->GetOne("SELECT COUNT(*) AS COUNTT FROM META_WFM_TRANSITION_PACK WHERE (PREV_WFM_STATUS_ID IN ($wfmStatusId) OR NEXT_WFM_STATUS_ID IN ($wfmStatusId))");
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
            $result = $this->db->AutoExecute('META_WFM_TRANSITION_PACK', $data);
            if ($result) {
                $response = array('status' => 'success', 'transitionId' => $transitionId, 'message' => 'Амжилттай хадгаллаа.');
            }
            return $response;
        }
    }

    public function updateWfmWorkFlowTransitionModel() {
        
        $response = array('status' => 'warning', 'message' => 'Амжилтгүй боллоо.');

        loadPhpQuery();
        
        $expressionContent = Input::postNonTags('bpCriteria');
        $expressionContent = str_replace('&nbsp;', '', $expressionContent);
        
        $htmlObj = phpQuery::newDocumentHTML($expressionContent);  
        $matches = $htmlObj->find('span.p-exp-meta:not(:empty)');
        
        if ($matches->length) {
            
            foreach ($matches as $tag) {
                $metaCode = pq($tag)->attr('data-code');
                pq($tag)->replaceWith($metaCode);
            }
            
            $expressionContent = $htmlObj->html();
        }        
        $search  = array('\r\n', '\r', '\n', "\r\n", "\r", "\n");
        $replace = array('',     '',   '',   '',     '',   ''); 
            
        $expressionContent = html_entity_decode(trim(str_replace($search, $replace, strip_tags($expressionContent))));        

        $param = array('value' => $expressionContent);        
        $checkExpression = $this->ws->runResponse(GF_SERVICE_ADDRESS, 'check_expression', $param);    

        if (isset($checkExpression['status']) && $checkExpression['status'] != 'success') {
            return array('status' => 'error', 'message' => $this->ws->getResponseMessage($checkExpression));
        }        

        $data = array(
            'NEXT_WFM_STATUS_ID' => Input::post('wfmStatusId'),
            'CRITERIA' => $expressionContent,
            'DESCRIPTION' => Input::post('wfmStatusName')
        );
        $result = $this->db->AutoExecute('META_WFM_TRANSITION', $data, 'UPDATE', ' ID = ' . Input::post('transitionId'));
        
        if ($result) {
            
            $metaDataId = Input::numeric('metaDataId');
            (new Mdmeta())->serverReloadByDataView($metaDataId);
            
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
                    'MOBILE_PROCESS_META_DATA_ID' => Input::post('wfmMobileProcessId'),
                    'IS_NEED_SIGN' => Input::post('wfmIsSign'),
                    'IS_DESC_REQUIRED' => Input::postCheck('isDescRequired') ? '1' : '0',
                    'IS_SEND_MAIL' => Input::postCheck('isSendMail') ? '1' : '0',
                    'IS_MAIL_ACTION' => Input::postCheck('isMailAction') ? '1' : '0',
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
                    'MOBILE_PROCESS_META_DATA_ID' => Input::post('wfmMobileProcessId'),
                    'IS_NEED_SIGN' => Input::post('wfmIsSign'),
                    'IS_DESC_REQUIRED' => Input::postCheck('isDescRequired') ? '1' : '0',
                    'IS_SEND_MAIL' => Input::postCheck('isSendMail') ? '1' : '0',
                    'IS_MAIL_ACTION' => Input::postCheck('isMailAction') ? '1' : '0',
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
                    'MOBILE_PROCESS_META_DATA_ID' => Input::post('wfmMobileProcessId'),
                    'IS_NEED_SIGN' => Input::post('wfmIsSign'),
                    'IS_DESC_REQUIRED' => Input::postCheck('isDescRequired') ? '1' : '0',
                    'IS_SEND_MAIL' => Input::postCheck('isSendMail') ? '1' : '0',
                    'IS_MAIL_ACTION' => Input::postCheck('isMailAction') ? '1' : '0',
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
        return $this->db->GetRow("
            SELECT 
                ID, 
                CRITERIA, 
                DESCRIPTION, 
                TRANSITION_TIME, 
                TIME_TYPE_ID, 
                TRANSITION_COST, 
                TRANSITION_DISTANCE, 
                NEW_WFM_DESCRIPTION 
            FROM META_WFM_TRANSITION 
            WHERE PREV_WFM_STATUS_ID = $sourceId 
                AND NEXT_WFM_STATUS_ID = $targetId 
                AND SOURCE_ID = $transitionId");
    }

    public function saveWfmCriteriaModel() {
        
        try {
            
            $transitionId = Input::numeric('chooseTransitionId');
            $isLock = self::checkWfmLockModel($transitionId);

            if ($isLock) {
                return array('status' => 'info', 'message' => 'Түгжигдсэн тул хадгалах боломжгүй!');
            }
            
            loadPhpQuery();
        
            $expressionContent = Input::postNonTags('bpCriteria');
            $expressionContent = str_replace('&nbsp;', '', $expressionContent);
            
            $htmlObj = phpQuery::newDocumentHTML($expressionContent);  
            $matches = $htmlObj->find('span.p-exp-meta:not(:empty)');
            
            if ($matches->length) {
                
                foreach ($matches as $tag) {
                    $metaCode = pq($tag)->attr('data-code');
                    pq($tag)->replaceWith($metaCode);
                }
                
                $expressionContent = $htmlObj->html();
            }        
            $search  = array('\r\n', '\r', '\n', "\r\n", "\r", "\n");
            $replace = array('',     '',   '',   '',     '',   ''); 
                
            $expressionContent = html_entity_decode(trim(str_replace($search, $replace, strip_tags($expressionContent))));     
            $param = array('value' => $expressionContent);        
            
            $checkExpression = $this->ws->runResponse(GF_SERVICE_ADDRESS, 'check_expression', $param);    

            if (isset($checkExpression['status']) && $checkExpression['status'] != 'success') {
                return array('status' => 'error', 'message' => $this->ws->getResponseMessage($checkExpression));
            }

            $data = array(
                'CRITERIA'            => $expressionContent,
                'DESCRIPTION'         => Input::post('transitionDescription'),
                'TRANSITION_TIME'     => Input::post('transitionTime'),
                'TIME_TYPE_ID'        => Input::post('transitionTimeTypeId'),
                'TRANSITION_COST'     => Number::decimal(Input::post('transitionCost')),
                'TRANSITION_DISTANCE' => Number::decimal(Input::post('transitionDistance')), 
                'NEW_WFM_DESCRIPTION' => Input::post('newWfmDescription')
            );
            $result = $this->db->AutoExecute('META_WFM_TRANSITION', $data, 'UPDATE', 'ID = ' . Input::post('bpCriteriaId'));

            if ($result) {
                
                $metaDataId = Input::numeric('metaDataId');
                (new Mdmeta())->serverReloadByDataView($metaDataId);
            
                return array('status' => 'success', 'message' => $this->lang->line('msg_save_success'), 'data' => $data);
            } else {
                return array('status' => 'error', 'message' => 'Алдаа гарлаа');
            }
            
        } catch (Exception $ex) {
            return array('status' => 'error', 'message' => $ex->getMessage());
        }
    }

    public function getWfmStatusModel($wfmStatusId) {
        return $this->db->GetRow("SELECT 
                                        ID, 
                                        WFM_STATUS_CODE, 
                                        WFM_STATUS_NAME, 
                                        PROCESS_NAME, 
                                        WFM_WORKFLOW_ID, 
                                        IS_ACTIVE, 
                                        WFM_STATUS_COLOR, 
                                        PROCESS_META_DATA_ID ,
                                        IS_USERDEF_ASSIGN,
                                        IS_USERDEF_RULE,
                                        IS_INHERIT_ASSIGN,
                                        FROM_STATUS_NAME,
                                        TO_STATUS_NAME,
                                        DEFAULT_RULE_ID,
                                        DEFAULT_RULE_STATUS_ID,
                                        ASSIGNED_TO_NOTIF_ID,
                                        ASSIGNED_FROM_NOTIF_ID,
                                        ALIAS_NAME
                                    FROM META_WFM_STATUS 
                                    WHERE ID = $wfmStatusId");
    }

    public function filterUserInfoModel() {
        $response = array();
        $param = array(
            'systemMetaGroupId' => '1457267529956193',
            'showQuery' => 1, 
            'ignorePermission' => 1 
        );
        $data = $this->ws->runResponse(GF_SERVICE_ADDRESS, Mddatamodel::$getDataViewCommand, $param);
        
        if ($data['status'] == 'success' && isset($data['result'])) {
            $result = $this->db->GetAll("SELECT * FROM (" . $data['result'] . ") TT WHERE LOWER(TT.USERNAME) LIKE LOWER('%" . Input::post('q') . "%')");
            if ($result) {
                $response = array('items' => $result);
            }
        }
        
        return $response;
    }

    public function filterRoleInfoModel() {
        $response = array();
        $param = array(
            'systemMetaGroupId' => '1457174283509032',
            'showQuery' => 1, 
            'ignorePermission' => 1 
        );
        $data = $this->ws->runResponse(GF_SERVICE_ADDRESS, Mddatamodel::$getDataViewCommand, $param);
        
        if ($data['status'] == 'success' && isset($data['result'])) {
            $result = $this->db->GetAll("SELECT * FROM (" . $data['result'] . ") TT WHERE LOWER(TT.ROLENAME) LIKE LOWER('%" . Input::post('q') . "%')");
            if ($result) {
                $response = array('items' => $result);
            }
        }
        return $response;
    }

    public function filterStatusInfoModel() {
        $response = array();
        $param = array(
            'systemMetaGroupId' => '1679034660105620',
            'showQuery' => 1, 
            'ignorePermission' => 1 
        );
        $data = $this->ws->runResponse(GF_SERVICE_ADDRESS, Mddatamodel::$getDataViewCommand, $param);
        
        if ($data['status'] == 'success' && isset($data['result'])) {
            $result = $this->db->GetAll("SELECT * FROM (" . $data['result'] . ") TT WHERE LOWER(TT.STATUSNAME) LIKE LOWER('%" . Input::post('q') . "%')");
            if ($result) {
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
        
        if (Input::isEmpty('userId') == false) {
            $rows[]['id'] = Input::post('userId');
        } else {
            $rows = $_POST['rows'];
        }
        
        foreach ($rows as $row) {
            
            $userId = $row['id'];
            $checkData = $this->getCheckStatusPermissionModel('USER_ID', $userId, $wfmStatusId);
            
            if ($checkData) {
                $data = array(
                    'ID' => getUID(),
                    'WFM_STATUS_ID' => $wfmStatusId,
                    'USER_ID' => $userId,
                    'MODIFIED_DATE' => Date::currentDate(),
                    'MODIFIED_USER_ID' => Ue::sessionUserKeyId()                     
                );
                $result = $this->db->AutoExecute('META_WFM_STATUS_PERMISSION', $data);
                if ($result) {
                    $response = array('status' => 'success', 'message' => 'Амжилттай хадгаллаа');
                }
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
        
        if (Input::isEmpty('roleId') == false) {
            $rows[]['id'] = Input::post('roleId');
        } else {
            $rows = $_POST['rows'];
        }
        
        foreach ($rows as $row) {
            
            $roleId = $row['id'];
            $checkData = $this->getCheckStatusPermissionModel('ROLE_ID', $roleId, $wfmStatusId);
            
            if ($checkData) {
                
                $data = array(
                    'ID' => getUID(),
                    'WFM_STATUS_ID' => $wfmStatusId,
                    'ROLE_ID' => $roleId,
                    'MODIFIED_DATE' => Date::currentDate(),
                    'MODIFIED_USER_ID' => Ue::sessionUserKeyId(),                    
                );
                
                $result = $this->db->AutoExecute('META_WFM_STATUS_PERMISSION', $data);
                if ($result) {
                    $response = array('status' => 'success', 'message' => 'Амжилттай хадгаллаа');
                }
            }
        }
        
        return $response;
    }

    public function addStatusStatusPermissionModel() {
        
        $response = array('status' => 'warning', 'message' => 'Амжилтгүй боллоо');
        $wfmStatusId = Input::post('wfmStatusId');
        
        if (Input::isEmpty('roleId') == false) {
            $rows[]['id'] = Input::post('roleId');
        } else {
            $rows = $_POST['rows'];
        }
        
        $getInfoWfm = $this->db->GetRow("
            SELECT 
                W.ID AS WORKFLOW_ID,
                W.REF_STRUCTURE_ID
            FROM META_WFM_STATUS S
            INNER JOIN META_WFM_WORKFLOW W ON S.WFM_WORKFLOW_ID = W.ID
            WHERE S.ID = ".$this->db->Param(0), array($wfmStatusId)
        );                
        
        $rows = Arr::changeKeyLower($rows);
        $checkData = $this->getCheckStatus2PermissionModel($wfmStatusId);
        $checkData = Arr::groupByArray($checkData, 'TRG_WFM_STATUS_ID');
                
        if ($getInfoWfm) {
            foreach ($rows as $key => $row) {

                if (!array_key_exists($row['wfmstatusid'], $checkData)) {
                    $data = array(
                        'ID' => getUIDAdd($key),
                        'SRC_REF_STRUCTURE_ID' => $getInfoWfm['REF_STRUCTURE_ID'],
                        'SRC_WFM_WORKFLOW_ID' => $getInfoWfm['WORKFLOW_ID'],
                        'SRC_WFM_STATUS_ID' => $wfmStatusId,
                        'TRG_REF_STRUCTURE_ID' => $row['structureid'],                    
                        'TRG_WFM_WORKFLOW_ID' => $row['workflowid'],                    
                        'TRG_WFM_STATUS_ID' => $row['wfmstatusid'],                    
                    );

                    $result = $this->db->AutoExecute('META_WFM_INHERITANCE', $data);
                    if ($result) {
                        $response = array('status' => 'success', 'message' => 'Амжилттай хадгаллаа');
                    }                
                } else {
                    $response = array('status' => 'success', 'message' => 'Амжилттай хадгаллаа');
                }
            }
        }
        
        return $response;
    }

    public function getCheckTransitionPermissionModel($field, $fieldId, $transitionId) {
        $result = $this->db->GetRow("SELECT COUNT(ID) AS COUNTT FROM META_WFM_TRANSITION_PERMISSION WHERE $field = $fieldId AND WFM_TRANSITION_ID = $transitionId ");
        
        if ($result['COUNTT']) {
            $return = false;
        } else {
            $return = true;
        }
        return $return;
    }

    public function getCheckStatusPermissionModel($field, $fieldId, $statusId) {
        $result = $this->db->GetRow("SELECT COUNT(ID) AS COUNTT FROM META_WFM_STATUS_PERMISSION WHERE $field = $fieldId AND WFM_STATUS_ID = $statusId");
        
        if ($result['COUNTT']) {
            $return = false;
        } else {
            $return = true;
        }
        return $return;
    }

    public function getCheckStatus2PermissionModel($wfmStatusId) {
        $result = $this->db->GetAll("SELECT * FROM META_WFM_INHERITANCE WHERE SRC_WFM_STATUS_ID = ".$this->db->Param(0), array($wfmStatusId));        
        
        if (!$result) return [];
        return $result;
    }

    public function getWfmTransitionUserListModel() {
        $response = array();
        $param = array(
            'systemMetaGroupId' => '1457267529956193',
            'showQuery' => 1, 
            'ignorePermission' => 1 
        );

        $data = $this->ws->runResponse(GF_SERVICE_ADDRESS, Mddatamodel::$getDataViewCommand, $param);
        
        if ($data['status'] == 'success' && isset($data['result'])) {
            
            $transitionId = Input::post('transitionId');
            $result = $this->db->GetAll("
                SELECT 
                    TT.USERNAME, 
                    per.ID 
                FROM (" . $data['result'] . ") TT 
                    INNER JOIN META_WFM_TRANSITION_PERMISSION per ON TT.ID = per.USER_ID
                WHERE per.WFM_TRANSITION_ID = $transitionId");
            
            if ($result) {
                $response['total'] = count($result);
                $response['rows'] = $result;
            }
        }
        
        return $response;
    }

    public function getWfmTransitionRoleListModel() {
        $response = array();
        $param = array(
            'systemMetaGroupId' => '1457174283509032',
            'showQuery' => 1, 
            'ignorePermission' => 1 
        );
        
        $data = $this->ws->runResponse(GF_SERVICE_ADDRESS, Mddatamodel::$getDataViewCommand, $param);
        
        if ($data['status'] == 'success' && isset($data['result'])) {
            
            $transitionId = Input::post('transitionId');
            $result = $this->db->GetAll("
                SELECT 
                    DISTINCT TT.ROLENAME, 
                    per.ID 
                FROM (" . $data['result'] . ") TT 
                    INNER JOIN META_WFM_TRANSITION_PERMISSION per ON TT.ID = per.ROLE_ID
                WHERE per.WFM_TRANSITION_ID = $transitionId");
            
            if ($result) {
                $response['total'] = count($result);
                $response['rows'] = $result;
            }
        }
        
        return $response;
    }

    public function getWfmStatusUserListModel() {
        $response = array();
        $param = array(
            'systemMetaGroupId' => '1457267529956193',
            'showQuery' => 1, 
            'ignorePermission' => 1 
        );
        
        $data = $this->ws->runResponse(GF_SERVICE_ADDRESS, Mddatamodel::$getDataViewCommand, $param);
        
        if ($data['status'] == 'success' && isset($data['result'])) {
            
            $wfmStatusId = Input::numeric('wfmStatusId');
            $subCondition = '';
        
            if (Input::postCheck('filterRules')) {

                $filterRules = json_decode($_POST['filterRules'], true);

                foreach ($filterRules as $rule) {

                    $field = $rule['field'];
                    $value = Input::param(Str::lower($rule['value']));

                    if ($value != '') {
                        $subCondition .= " AND (LOWER(UL.$field) LIKE '%$value%')";
                    }
                }
            }
        
            $result = $this->db->GetAll("
                SELECT 
                    UL.* 
                FROM (
                    SELECT 
                        DISTINCT TT.USERNAME, 
                        per.ID, 
                        per.IS_EDIT 
                    FROM (" . $data['result'] . ") TT 
                        INNER JOIN META_WFM_STATUS_PERMISSION per ON TT.ID = per.USER_ID
                    WHERE per.WFM_STATUS_ID = $wfmStatusId
                ) UL 
                WHERE 1 = 1 $subCondition");
            
            if ($result) {
                $response['total'] = count($result);
                $response['rows'] = $result;
            }
        }
        
        return $response;
    }

    public function getWfmStatusAssignmentListModel() {
        $response = array();

        $criteria['wfmStatusId'][] = array(
            'operator' => '=',
            'operand' => Input::post('wfmStatusId')
        );
        $param = array(
            'systemMetaGroupId' => '1464077580259',
            'showQuery' => 0,
            'ignorePermission' => 1,  
            'criteria' => $criteria
        );
        
        $data = $this->ws->runResponse(GF_SERVICE_ADDRESS, Mddatamodel::$getDataViewCommand, $param);

        if ($data['status'] == 'success' && isset($data['result'])) {
            
            unset($data['result']['aggregatecolumns']);
            unset($data['result']['paging']);
            
            $response['total'] = count($data['result']);
            $response['rows'] = $data['result'];
        }
        
        return $response;
    }

    public function getWfmStatusRoleListModel() {
        
        $wfmStatusId = Input::numeric('wfmStatusId');
        $subCondition = '';
        
        if (Input::postCheck('filterRules')) {

            $filterRules = json_decode($_POST['filterRules'], true);

            foreach ($filterRules as $rule) {

                $field = $rule['field'];
                $value = Input::param(Str::lower($rule['value']));

                if ($value != '') {
                    $subCondition .= " AND (LOWER(RL.$field) LIKE '%$value%')";
                }
            }
        }
            
        $result = $this->db->GetAll("
            SELECT 
                RL.*  
            FROM (
                SELECT 
                    PER.ID, 
                    RO.ROLE_CODE ||' - '||RO.ROLE_NAME AS ROLENAME,  
                    PER.IS_EDIT,
                    RO.ROLE_ID
                FROM META_WFM_STATUS_PERMISSION PER 
                    INNER JOIN UM_ROLE RO ON RO.ROLE_ID = PER.ROLE_ID 
                WHERE PER.WFM_STATUS_ID = $wfmStatusId
            ) RL 
            WHERE 1 = 1 $subCondition");

        if ($result) {
            $response['total'] = count($result);
            $response['rows'] = $result;
        } else {
            $response = array('rows' => array(), 'total' => 0);
        }
        
        return $response;
    }

    public function getWfmStatusUserListByRoleModel() {
            
        $roleId = Input::post('roleId');
        $result = $this->db->GetAll("
            SELECT 
                R.ROLE_NAME,
                COALESCE(SU.USER_FULL_NAME, UU.USERNAME, SU.USERNAME) AS USERNAME,
                OD.DEPARTMENT_NAME
              FROM UM_USER_ROLE UR 
                INNER JOIN UM_USER UU ON UR.USER_ID = UU.USER_ID
                LEFT JOIN UM_SYSTEM_USER SU ON UU.SYSTEM_USER_ID = SU.USER_ID
                LEFT JOIN ORG_DEPARTMENT OD ON UU.DEPARTMENT_ID = OD.DEPARTMENT_ID
                LEFT JOIN UM_ROLE R ON UR.ROLE_ID = R.ROLE_ID
              WHERE UR.ROLE_ID = ".$this->db->Param(0)."
                ORDER BY COALESCE(SU.USER_FULL_NAME, UU.USERNAME, SU.USERNAME) ASC",
                array($roleId)
        );

        if ($result) {
            $response['total'] = count($result);
            $response['rows'] = $result;
        } else {
            $response = array('rows' => array(), 'total' => 0);
        }
        
        return $response;
    }

    public function getWfmStatusStatusListModel() {
        
        $wfmStatusId = Input::numeric('wfmStatusId');
        $subCondition = '';
        
        $param = array(
            'systemMetaGroupId' => '16793820751679',
            'ignorePermission' => 1, 
            'showQuery' => 0,
            'criteria' => array(
                'srcwfmstatusid' => array(
                    array(
                        'operator' => '=',
                        'operand' => $wfmStatusId
                    )
                )
            )
        );

        $result = $this->ws->runSerializeResponse(Mdwebservice::$gfServiceAddress, Mddatamodel::$getDataViewCommand, $param);

        if (isset($result['result'][0])) {
            
            unset($result['result']['aggregatecolumns']);
            unset($result['result']['paging']);
            
            $response['total'] = count($result['result']);
            $response['rows'] = $result['result'];
        } else {
            $response = array('rows' => array(), 'total' => 0);
        }
        
        return $response;
    }

    public function getMetaWfmStatusDataModel($id) {
        return $this->db->GetRow(
            "SELECT 
                AA.ID, 
                AA.WFM_WORKFLOW_ID, 
                AA.WFM_STATUS_CODE, 
                AA.WFM_STATUS_NAME, 
                AA.PROCESS_NAME, 
                AA.WFM_STATUS_COLOR, 
                AA.IS_NEED_SIGN, 
                AA.PROCESS_META_DATA_ID, 
                AA.MOBILE_PROCESS_META_DATA_ID, 
                CASE WHEN BB.META_DATA_CODE IS NULL THEN '' ELSE BB.META_DATA_CODE||' | '||BB.META_DATA_NAME END AS PROCESS_CODENAME, 
                CASE WHEN MB.META_DATA_CODE IS NULL THEN '' ELSE MB.META_DATA_CODE||' | '||MB.META_DATA_NAME END AS MOBILE_PROCESS_CODENAME, 
                AA.IS_DESC_REQUIRED, 
                AA.IS_SEND_MAIL,
                AA.IS_MAIL_ACTION,
                AA.IS_SEND_SMS, 
                AA.FROM_NOTIFICATION_ID, 
                AA.TO_NOTIFICATION_ID, 
                AA.CREATED_USER_NOTIFICATION_ID, 
                AA.IS_NOTIFY_TO_CREATED_USER ,
                AA.IS_USERDEF_ASSIGN,
                AA.IS_USERDEF_RULE,
                AA.IS_INHERIT_ASSIGN,
                AA.FROM_STATUS_NAME,
                AA.TO_STATUS_NAME,
                AA.DEFAULT_RULE_ID,
                AA.DEFAULT_RULE_STATUS_ID, 
                AA.ASSIGNED_TO_NOTIF_ID, 
                AA.ASSIGNED_FROM_NOTIF_ID, 
                AA.ALIAS_NAME, 
                AA.IS_FORM_NOTSUBMIT, 
                AA.USE_PROCESS_WINDOW, 
                AA.USE_DESCRIPTION_WINDOW, 
                AA.IS_FILTER_USERS_BY_DEPARTMENT, 
                AA.IS_HIDE_NEXT_USER, 
                AA.IS_IGNORE_ROW, 
                AA.IS_IGNORE_SORTING, 
                AA.IS_CHECK_ASSIGN_CRITERIA, 
                AA.IS_DIRECT, 
                AA.IS_FILTER_LOG, 
                AA.IS_HIDE_FILE, 
                AA.IS_FILE_PREVIEW, 
                AA.IS_NOT_CONFIRM, 
                AA.IS_IGNORE_MULTIROW_RUN_BP, 
                AA.ORDER_NUMBER, 
                AA.WFM_STATUS_ICON, 
                CASE WHEN NCC.NOTIFICATION_TYPE_NAME IS NULL THEN '' ELSE NCC.NOTIFICATION_TYPE_NAME||' | ' END AS FROMNOTICATION_NAME, 
                CASE WHEN DCC.NOTIFICATION_TYPE_NAME IS NULL THEN '' ELSE DCC.NOTIFICATION_TYPE_NAME||' | ' END AS TONOTICATION_NAME, 
                CASE WHEN FCC.NOTIFICATION_TYPE_NAME IS NULL THEN '' ELSE FCC.NOTIFICATION_TYPE_NAME||' | ' END  AS TO_ASSIGNNOTICATION_NAME, 
                CASE WHEN ECC.NOTIFICATION_TYPE_NAME IS NULL THEN '' ELSE ECC.NOTIFICATION_TYPE_NAME||' | 'END AS FROM_ASSIGNNOTICATION_NAME,
                CASE WHEN CUNT.NOTIFICATION_TYPE_NAME IS NULL THEN '' ELSE CUNT.NOTIFICATION_TYPE_NAME||' | 'END AS CREATEDUSER_NOTIFICATION_NAME, 
                AA.ASSIGN_DATAVIEW_ID, 
                AD.META_DATA_CODE AS ASSIGN_DATAVIEW_CODE, 
                AD.META_DATA_NAME AS ASSIGN_DATAVIEW_NAME, 
                AA.TRANSLATION_VALUE, 
                AA.TRANSITION_TIME, 
                AA.TIME_TYPE_ID, 
                AA.IS_SEND_NOTIF_WITH_EMAIL, 
                AA.INDICATOR_ID 
            FROM META_WFM_STATUS AA
                LEFT JOIN META_DATA BB ON BB.META_DATA_ID = AA.PROCESS_META_DATA_ID 
                LEFT JOIN META_DATA MB ON MB.META_DATA_ID = AA.MOBILE_PROCESS_META_DATA_ID 
                LEFT JOIN META_DATA AD ON AD.META_DATA_ID = AA.ASSIGN_DATAVIEW_ID 
                LEFT JOIN NTF_NOTIFICATION CC ON CC.NOTIFICATION_ID = AA.FROM_NOTIFICATION_ID
                LEFT JOIN NTF_NOTIFICATION_TYPE NCC ON CC.NOTIFICATION_TYPE_ID = NCC.NOTIFICATION_TYPE_ID
                LEFT JOIN NTF_NOTIFICATION DD ON DD.NOTIFICATION_ID = AA.TO_NOTIFICATION_ID
                LEFT JOIN NTF_NOTIFICATION_TYPE DCC ON DD.NOTIFICATION_TYPE_ID = DCC.NOTIFICATION_TYPE_ID
                LEFT JOIN NTF_NOTIFICATION FF ON FF.NOTIFICATION_ID = AA.ASSIGNED_TO_NOTIF_ID
                LEFT JOIN NTF_NOTIFICATION_TYPE FCC ON FF.NOTIFICATION_TYPE_ID = FCC.NOTIFICATION_TYPE_ID
                LEFT JOIN NTF_NOTIFICATION EE ON EE.NOTIFICATION_ID = AA.ASSIGNED_FROM_NOTIF_ID
                LEFT JOIN NTF_NOTIFICATION_TYPE ECC ON EE.NOTIFICATION_TYPE_ID = ECC.NOTIFICATION_TYPE_ID
                LEFT JOIN NTF_NOTIFICATION CUN ON CUN.NOTIFICATION_ID = AA.CREATED_USER_NOTIFICATION_ID
                LEFT JOIN NTF_NOTIFICATION_TYPE CUNT ON CUN.NOTIFICATION_TYPE_ID = CUNT.NOTIFICATION_TYPE_ID
            WHERE AA.ID = ".$this->db->Param(0), array($id));
    }

    public function updateWfmStatusModel() {
        
        $response = array('status' => 'warning', 'message' => 'Амжилтгүй боллоо.');
        
        if (Input::isEmpty('metaWfmStatusId') === false) {

            $wfmStatusId = Input::post('metaWfmStatusId');
            $isLock = self::isWfmLockByStatusIdModel($wfmStatusId);
            
            if ($isLock) {
                return array('status' => 'info', 'message' => 'Түгжигдсэн тул хадгалах боломжгүй!');
            }
            
            $idPh = $this->db->Param(0);
            
            $data = array(
                'WFM_STATUS_CODE' => Input::post('wfmStatusCode'),
                'WFM_STATUS_COLOR' => Input::post('wfmStatusColor'),
                'PROCESS_META_DATA_ID' => Input::post('wfmProcessId'),
                'MOBILE_PROCESS_META_DATA_ID' => Input::post('wfmMobileProcessId'),
                'IS_NEED_SIGN' => Input::post('wfmIsSign'),
                'IS_DESC_REQUIRED' => Input::postCheck('isDescRequired') ? '1' : '0',
                'TO_NOTIFICATION_ID' => Input::post('toNotificationId'),
                'FROM_NOTIFICATION_ID' => Input::post('fromNotificationId'),
                'CREATED_USER_NOTIFICATION_ID' => Input::post('createdUserNotificationId'),
                'IS_NOTIFY_TO_CREATED_USER' => Input::postCheck('isNotifyToCreatedUser') ? '1' : '0',
                'IS_SEND_MAIL' => Input::postCheck('isSendMail') ? 1 : null,
                'IS_MAIL_ACTION' => Input::postCheck('isMailAction') ? 1 : null,
                'IS_SEND_SMS' => Input::postCheck('isSendSms') ? 1 : null,
                'IS_USERDEF_ASSIGN' => Input::postCheck('isUserDefAssign') ? '1' : '0',
                'IS_USERDEF_RULE' => Input::postCheck('isUserdefRule') ? '1' : '0',
                'IS_INHERIT_ASSIGN' => Input::postCheck('isInheritAssign') ? '1' : '0',
                'DEFAULT_RULE_ID' => Input::post('defaultRuleId'),
                'ASSIGNED_TO_NOTIF_ID' => Input::post('assignedToNotifId'),
                'ASSIGNED_FROM_NOTIF_ID' => Input::post('assignedFromNotifId'),
                'USE_PROCESS_WINDOW' => Input::post('useprocessWindow'),
                'IS_FORM_NOTSUBMIT' => Input::post('useprocessFormSubmit'),
                'USE_DESCRIPTION_WINDOW' => Input::post('usedescriptionWindow'),
                'IS_FILTER_USERS_BY_DEPARTMENT' => Input::post('filterUsersByDepartment'),
                'IS_HIDE_NEXT_USER' => Input::post('wfmIsHideNextUser'),
                'IS_IGNORE_ROW' => Input::post('wfmIsIgnoreRow'),
                'IS_IGNORE_SORTING' => Input::post('wfmIsIgnoreSorting'),
                'IS_CHECK_ASSIGN_CRITERIA' => Input::post('wfmIsCheckAssignCriteria'),
                'IS_FILTER_LOG' => Input::post('wfmIsFilterLog'),
                'IS_DIRECT' => Input::post('wfmIsDirect'),
                'IS_HIDE_FILE' => Input::post('wfmIsHideFile'),
                'IS_FILE_PREVIEW' => Input::post('wfmIsFilePreview'),
                'IS_NOT_CONFIRM' => Input::post('wfmIsNotConfirm'),
                'IS_IGNORE_MULTIROW_RUN_BP' => Input::post('wfmIsIgnoreMultirowRunBp'),
                'ORDER_NUMBER' => Input::post('orderNum'), 
                'WFM_STATUS_ICON' => Input::post('wfmStatusIcon'),
                'FROM_STATUS_NAME' => Input::post('fromStatusName'),
                'MODIFIED_DATE' => Date::currentDate(),
                'MODIFIED_USER_ID' => Ue::sessionUserKeyId(),
                'TO_STATUS_NAME' => Input::post('toStatusName'), 
                'ASSIGN_DATAVIEW_ID' => Input::numeric('assignDataviewId'), 
                'TIME_TYPE_ID' => Input::numeric('timeTypeId'), 
                'TRANSITION_TIME' => Input::numeric('transitionTime'), 
                'INDICATOR_ID' => Input::numeric('indicatorId'), 
                'IS_SEND_NOTIF_WITH_EMAIL' => Input::postCheck('isSendNotifWithEmail') ? 1 : null
            );
            
            if (Lang::isUseMultiLang() && Input::postCheck('pfTranslationValue')) {
                
                $defaultLangCode = Lang::getDefaultLangCode(); 
                $currentLangCode = Lang::getCode(); 
                $pfTranslationValue = Input::postNonTags('pfTranslationValue');
                $pfTranslationValue = json_decode($pfTranslationValue, true);
                
                /* WFM_STATUS_NAME */
                
                if (Input::postCheck('wfmStatusName_translation')) {
                    
                    $translation = Input::postNonTags('wfmStatusName_translation');
                    $translationJson = json_decode($translation, true);

                    if (isset($translationJson[$defaultLangCode])) {

                        $value = Input::param($translationJson[$defaultLangCode]);
                        $translationJson[$currentLangCode] = Input::post('wfmStatusName');

                        unset($translationJson[$defaultLangCode]);

                    } else {
                        $value = Input::post('wfmStatusName');
                    }

                    $data['WFM_STATUS_NAME'] = $value;

                    if ($translationJson) {
                        $pfTranslationValue['value']['WFM_STATUS_NAME'] = $translationJson;
                    }
                    
                } elseif ($defaultLangCode != $currentLangCode) {
                    $pfTranslationValue['value']['WFM_STATUS_NAME'][$currentLangCode] = Input::post('wfmStatusName');
                } else {
                    $data['WFM_STATUS_NAME'] = Input::post('wfmStatusName');
                }
                
                /* PROCESS_NAME */
                
                if (Input::postCheck('wfmProcessName_translation')) {
                    
                    $translation = Input::postNonTags('wfmProcessName_translation');
                    $translationJson = json_decode($translation, true);

                    if (isset($translationJson[$defaultLangCode])) {

                        $value = Input::param($translationJson[$defaultLangCode]);
                        $translationJson[$currentLangCode] = Input::post('wfmProcessName');

                        unset($translationJson[$defaultLangCode]);

                    } else {
                        $value = Input::post('wfmProcessName');
                    }

                    $data['PROCESS_NAME'] = $value;

                    if ($translationJson) {
                        $pfTranslationValue['value']['PROCESS_NAME'] = $translationJson;
                    }
                    
                } elseif ($defaultLangCode != $currentLangCode) {
                    $pfTranslationValue['value']['PROCESS_NAME'][$currentLangCode] = Input::post('wfmProcessName');
                } else {
                    $data['PROCESS_NAME'] = Input::post('wfmProcessName');
                }
                
                /* ALIAS_NAME */
                
                if (Input::postCheck('statusAliasName_translation')) {
                    
                    $translation = Input::postNonTags('statusAliasName_translation');
                    $translationJson = json_decode($translation, true);

                    if (isset($translationJson[$defaultLangCode])) {

                        $value = Input::param($translationJson[$defaultLangCode]);
                        $translationJson[$currentLangCode] = Input::post('statusAliasName');

                        unset($translationJson[$defaultLangCode]);

                    } else {
                        $value = Input::post('statusAliasName');
                    }

                    $data['ALIAS_NAME'] = $value;

                    if ($translationJson) {
                        $pfTranslationValue['value']['ALIAS_NAME'] = $translationJson;
                    }
                    
                } elseif ($defaultLangCode != $currentLangCode) {
                    $pfTranslationValue['value']['ALIAS_NAME'][$currentLangCode] = Input::post('statusAliasName');
                } else {
                    $data['ALIAS_NAME'] = Input::post('statusAliasName');
                }
                
            } else {
                $data['WFM_STATUS_NAME'] = Input::post('wfmStatusName');
                $data['PROCESS_NAME'] = Input::post('wfmProcessName');
                $data['ALIAS_NAME'] = Input::post('statusAliasName');
            }
            
            $result = $this->db->AutoExecute('META_WFM_STATUS', $data, 'UPDATE', 'ID = ' . $wfmStatusId);
            
            if (isset($pfTranslationValue)) {
                $this->db->UpdateClob('META_WFM_STATUS', 'TRANSLATION_VALUE', json_encode($pfTranslationValue, JSON_UNESCAPED_UNICODE), 'ID = '.$wfmStatusId);
            }
            
            $wfmWorkFlowId = $this->db->GetOne("SELECT WFM_WORKFLOW_ID FROM META_WFM_STATUS WHERE ID = $idPh", array($wfmStatusId));
            
            if ($result) {
                
                $metaDataId = Input::numeric('metaDataId');
                
                $wfmStatusArr = $this->db->GetAll("
                    SELECT
                        WWS.ID AS WFM_STATUS_ID,
                        WWS.WFM_STATUS_NAME,
                        WWS.WFM_STATUS_CODE,
                        WWS.WFM_STATUS_COLOR
                    FROM META_WFM_WORKFLOW WFM
                        INNER JOIN META_WFM_STATUS WWS ON WFM.ID = WWS.WFM_WORKFLOW_ID
                    WHERE WFM.IS_ACTIVE = 1 
                        AND WFM.REF_STRUCTURE_ID = $idPh 
                        AND WWS.IS_ACTIVE = 1 
                    ORDER BY WWS.WFM_STATUS_NAME", array($metaDataId));
                
                if (Input::postCheck('linkCriteria') && Input::postCheck('linkDescription')) {
                    
                    $linkId = $this->db->GetOne("SELECT ID FROM META_WFM_STATUS_LINK WHERE WFM_STATUS_ID = $idPh", array($wfmStatusId));
                    $ldata = array(
                        'CRITERIA' => Input::post('linkCriteria'), 
                        'DESCRIPTION' => Input::post('linkDescription')
                    );
                    
                    if ($linkId) {
                        $this->db->AutoExecute('META_WFM_STATUS_LINK', $ldata, 'UPDATE', 'ID = ' . $linkId);
                    } else {
                        $ldata['ID'] = getUID();
                        $ldata['WFM_STATUS_ID'] = $wfmStatusId;

                        $this->db->AutoExecute('META_WFM_STATUS_LINK', $ldata);
                    }
                }
                
                (new Mdmeta())->serverReloadByDataView($metaDataId);

                $response = array('status' => 'success', 'wfmWorkFlowId' => $wfmWorkFlowId, 'workFlowStatus' => $wfmStatusArr, 'message' => 'Амжилттай хадгаллаа.');
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
        if (Input::postCheck('workFlowId') && Input::isEmpty('workFlowId') === false) {
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
        return $response;
    }

    public function deleteWfmWorkFlowModel() {
        $response = array('status' => 'warning', 'title' => 'Warning', 'message' => 'Амжилтгүй боллоо.');
        if (Input::postCheck('workFlowId') && Input::isEmpty('workFlowId') === false) {
            $wfmWorkFlowId = Input::post('workFlowId');

            $result = $this->db->AutoExecute('META_WFM_WORKFLOW', array('IS_ACTIVE' => '0'), 'UPDATE', 'ID = ' . $wfmWorkFlowId);
            if ($result) {
                $response = array('status' => 'success', 'title' => 'Success', 'wfmWorkFlowId' => $wfmWorkFlowId, 'message' => 'Амжилттай устгагдлаа.');
            }
        }
        return $response;
    }

    public function deleteCheckFirstProcessObjectModel($mainBpId, $bpOrder) {
        $bpOrder0 = $this->db->GetOne("SELECT META_PROCESS_WORKFLOW_ID FROM META_PROCESS_WORKFLOW WHERE MAIN_BP_ID = $mainBpId");
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
        $rows = self::getPrevNullTransitionDataModel(Input::numeric('metaDataId'), Input::post('transId'));
        $response = array();
        
        if ($rows) {
            
            $filePath = UPLOADPATH.'process/wfmLockIds.json';
            $lockArr = array();
            
            if (file_exists($filePath)) {
                $getJson = file_get_contents($filePath);
                $lockArr = json_decode($getJson, true);
            }
                
            foreach ($rows as $row) {
                
                $item = array(
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
                
                if (isset($lockArr[$row['ID']]) && $lockArr[$row['ID']]['isLock'] == 1) {
                    $item['icon'] = 'fa fa-lock';
                    $item['a_attr'] = array('title' => 'Түгжсэн');
                }
                
                $response[] = $item;
            }
        }
        
        return $response;
    }

    public function getTransitionListJtreeDataPackModel() {
        $rows = self::getPrevNullTransitionDataPackModel(Input::get('metaDataId'), Input::get('transId'));
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
        
        $object = $wfmStatusArr = $tempedArr = array();
        $startWfmStatusId = '0';

        if ($transitionId) {
            
            $idPh = $this->db->Param(0);
            
            $selfData = $this->db->GetRow("
                SELECT 
                    DISTINCT 
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
                WHERE MT.ID = $idPh", array($transitionId));
            
            $startWfmStatusId = $selfData['NEXT_WFM_STATUS_ID'];
            
            $data = $this->db->GetAll("
                SELECT 
                    DISTINCT 
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
                WHERE MT.PREV_ID = $idPh 
                    AND MT.SOURCE_ID = $idPh", array($transitionId));

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

        return array('wfmStatusArr' => $wfmStatusArr, 'object' => $object, 'startWfmStatusId' => $startWfmStatusId);
    }

    public function getTransitionNewListDataPackModel($transitionId = '') {
        (Array) $object = $wfmStatusArr = $tempedArr = array();
        $startWfmStatusId = '0';

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
                                            FROM META_WFM_TRANSITION_PACK MT
                                            LEFT JOIN META_WFM_STATUS PREVS ON MT.PREV_WFM_STATUS_ID = PREVS.ID
                                            LEFT JOIN META_WFM_STATUS NEXTS ON MT.NEXT_WFM_STATUS_ID = NEXTS.ID
                                            WHERE MT.ID =  $transitionId");
            if (!$selfData) {
                return array('wfmStatusArr' => $wfmStatusArr, 'object' => $object, 'startWfmStatusId' => $startWfmStatusId);                
            }
            $startWfmStatusId = $selfData['NEXT_WFM_STATUS_ID'];
            $dataPack = $this->db->GetAll(" SELECT DISTINCT
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
                                            MT.NEXT_ID,
                                            MT.CHILD_WFM_STATUS_ID
                                        FROM META_WFM_TRANSITION_PACK MT
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

            if ($dataPack) {
                foreach ($dataPack as $row) {
                    if ($row['NEXT_WFM_STATUS_ID'] != '' && !in_array($row['NEXT_WFM_STATUS_ID'], $wfmStatusArr)) {
                        array_push($wfmStatusArr, $row['NEXT_WFM_STATUS_ID']);
                    }
                    if ($row['PREV_WFM_STATUS_ID'] != '' && !in_array($row['PREV_WFM_STATUS_ID'], $wfmStatusArr)) {
                        array_push($wfmStatusArr, $row['PREV_WFM_STATUS_ID']);
                    }
                    if ($row['CHILD_WFM_STATUS_ID'] != '' && !in_array($row['CHILD_WFM_STATUS_ID'], $wfmStatusArr)) {
                        array_push($wfmStatusArr, $row['CHILD_WFM_STATUS_ID']);
                    }
                    if (!in_array($row['ID'], $tempedArr)) {
                        array_push($tempedArr, $row['ID']);
                    }

                    array_push($object, $row);
                    $resultData = self::getTransitionNextListDataPackModel($row['ID'], $object, $wfmStatusArr, $tempedArr, $transitionId);
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
                        $result = self::getTransitionNextListDataPackModel($row['NEXT_ID'], $object, $wfmStatusArr, $tempedArr, $transitionId);
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

        return array('wfmStatusArr' => $wfmStatusArr, 'object' => $object, 'startWfmStatusId' => $startWfmStatusId);
    }

    public function getTransitionNextListDataModel($transitionId, $object, $wfmStatusArr, $tempedArr, $sourceId) {
        
        if ($transitionId) {
            
            $id1Ph = $this->db->Param(0);
            $id2Ph = $this->db->Param(1);
            
            $data = $this->db->GetAll(" 
                SELECT 
                    DISTINCT
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
                WHERE (MT.PREV_ID = $id1Ph OR MT.ID = $id1Ph) 
                    AND MT.SOURCE_ID = $id2Ph", array($transitionId, $sourceId));

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

    public function getTransitionNextListDataPackModel($transitionId, $object, $wfmStatusArr, $tempedArr, $sourceId) {
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
                                        FROM META_WFM_TRANSITION_PACK MT
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
                        $resultData = self::getTransitionNextListDataPackModel($row['ID'], $object, $wfmStatusArr, $tempedArr, $sourceId);
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
                            $result = self::getTransitionNextListDataPackModel($row['NEXT_ID'], $object, $wfmStatusArr, $tempedArr, $sourceId);
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

    public function getInteractiveTransitionStatusDataModel($statusIds, $transitionId = null) {
        $response = $this->db->GetAll("".
            "SELECT WFMS.ID, 
                    WFMS.WFM_STATUS_NAME, 
                    WFMS.WFM_STATUS_COLOR, 
                    'rectangle' AS TYPE, 
                    CASE WHEN MWL.ID IS NULL THEN 0 ELSE 1 END AS IS_WORKED,
                    CASE WHEN WT.ID IS NULL THEN 0 ELSE 1 END AS IS_START,
                    '0' AS IS_ACTIVE,
                    WFMS.WFM_STATUS_CODE
            FROM META_WFM_STATUS WFMS 
            LEFT JOIN META_WFM_LOG MWL ON MWL.WFM_STATUS_ID = WFMS.ID AND MWL.REF_STRUCTURE_ID = 1447239000602 AND MWL.RECORD_ID=1577264139664
            LEFT JOIN META_WFM_TRANSITION WT ON WFMS.ID = WT.NEXT_WFM_STATUS_ID AND WT.PREV_WFM_STATUS_ID IS NULL
            WHERE WFMS.ID IN ($statusIds)"
        );
        $item = $response;
        
        $selectedRowData = json_decode(Str::cp1251_utf8(html_entity_decode($_POST['selectedRow'], ENT_QUOTES, 'UTF-8')), true);
        $wfmStatusButtons = (new Mdworkflow())->getWorkflowNextStatus(Input::post('metaDataId'), $selectedRowData, Input::post('refStructureId'), true, true);
        if ($wfmStatusButtons) {
            $wfmStatusButtons = Arr::groupByArray($wfmStatusButtons, 'wfmstatusid');
        } else {
            $wfmStatusButtons = array();
        }
        
        (Array) $tempArr = array();
        if ($transitionId) {
            $position = $this->db->GetOne("SELECT POSITION_SOURCE FROM META_WFM_TRANSITION WHERE ID = $transitionId");
            if ($position) {
                $positionDecode = json_decode($position);
                (Array) $item = array();
                foreach ($response as $row) {
                    if (array_key_exists($row['ID'], $wfmStatusButtons)) {
                        $row['IS_ACTIVE'] = '1';
                    }
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

    public function getTransitionStatusDataPackModel($statusIds, $transitionId = null) {
        $response = $this->db->GetAll("SELECT WFMS.ID, " . $this->db->IfNull('WFMS.WFM_STATUS_NAME', "'---'") . " AS WFM_STATUS_NAME, WFMS.WFM_STATUS_COLOR, 'rectangle' AS TYPE, WFMS.WFM_STATUS_CODE FROM META_WFM_STATUS WFMS  WHERE ID IN ($statusIds) ORDER BY IS_PACK");
        $item = $response;
        (Array) $tempArr = array();
        if ($transitionId) {
            $position = $this->db->GetOne("SELECT POSITION_SOURCE FROM META_WFM_TRANSITION_PACK WHERE ID = $transitionId");
            if ($position) {
                $positionDecode = json_decode($position);
                (Array) $item = array();
                foreach ($response as $row) {
                    foreach ($positionDecode as $pos) {
                        if ($row['ID'] == $pos->id && !in_array($row['ID'], $tempArr)) {
                            $row['TOP'] = $pos->positionTop;
                            $row['LEFT'] = $pos->positionLeft;
                            $row['PACKID'] = issetParam($pos->packid);
                            $row['HEIGHT'] = isset($pos->positionHeight) ? $pos->positionHeight : '';
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
        
        $transitionId = Input::numeric('transitionId');
        $isLock = self::checkWfmLockModel($transitionId);
        
        if ($isLock) {
            return array('status' => 'info', 'text' => 'Түгжигдсэн тул хадгалах боломжгүй!');
        }
        
        $metaDataId = Input::numeric('metaDataId');
        $checkWfmStatusIdsArr = array();
        
        foreach ($_POST['objects'] as $objects) {
            if (!in_array($objects['id'], $checkWfmStatusIdsArr)) {
                array_push($checkWfmStatusIdsArr, $objects['id']);
            }
        }
        $explodeWfmStatusArr = implode(',', $checkWfmStatusIdsArr);
        $idPh = $this->db->Param(0);
        
        $workFlowId = $this->db->GetOne("
            SELECT 
                ST.WFM_WORKFLOW_ID 
            FROM META_WFM_TRANSITION TRA 
                INNER JOIN META_WFM_STATUS ST ON TRA.NEXT_WFM_STATUS_ID = ST.ID 
            WHERE TRA.ID = $idPh", array($transitionId));
        
        $showWfmHtml = '';
        $wfmTransitionName = '';

        foreach ($checkWfmStatusIdsArr as $key => $wfmRow) {
            
            $usedTransitionId = $this->db->GetAll("
                SELECT
                    MWT2.DESCRIPTION, 
                    MWS.WFM_STATUS_NAME, 
                    MWS.ID
                FROM META_WFM_TRANSITION MWT 
                    INNER JOIN META_WFM_STATUS MWS ON MWS.ID = $wfmRow 
                    INNER JOIN META_WFM_TRANSITION MWT2 ON MWT2.ID = MWT.SOURCE_ID 
                WHERE (MWT.PREV_WFM_STATUS_ID IN ($wfmRow) OR MWT.NEXT_WFM_STATUS_ID IN ($wfmRow)) 
                    AND (MWT.ID <> $idPh AND MWT.SOURCE_ID <> $idPh) 
                    AND MWT.IS_TRANSITION = 1", array($transitionId));

            if ($usedTransitionId) {
                foreach ($usedTransitionId as $wfm) {
                    $showWfmHtml .= '<tr>';
                    if ($wfmTransitionName !== $wfm['DESCRIPTION']) {
                        $showWfmHtml .= '<td style="width:300px">'.$wfm['DESCRIPTION'].'</td>';
                    } else {
                        $showWfmHtml .= '<td style="width:300px"></td>';
                    }
                    $showWfmHtml .= '<td style="width:350px">'.$wfm['WFM_STATUS_NAME'].' ('.$wfm['ID'].')</td>';
                    $showWfmHtml .= '</tr>';
                }
                $wfmTransitionName = $wfm['DESCRIPTION'];
            }
        }

        if ($showWfmHtml !== '') {
            
            $showWfmHtmlTemp = '<table><tbody>';
            $showWfmHtmlTemp .= $showWfmHtml;
            $showWfmHtmlTemp .= '</tbody></table>';
            return array('status' => 'warning', 'text' => 'Ашигласан төлөв сонгогдсон байна</br></br>'.$showWfmHtmlTemp, 'transitionId' => $transitionId);
            
        } else {
            
            $transitionData = self::getPrevNullTransitionDataModel($metaDataId, $transitionId);
            $connections = isset($_POST['connections']) ? $_POST['connections'] : array();
            $workFlowArr = $transitionArr = $connection = $statusArr = array();

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

            if (count($workFlowArr) == 0) {
                
                foreach ($connections as $status) {
                    if (!in_array($status['prevStatusId'], $statusArr)) {
                        array_push($statusArr, $status['prevStatusId']);
                    }
                    if (!in_array($status['nextStatusId'], $statusArr) && $status['nextStatusId'] != 'endObject001') {
                        array_push($statusArr, $status['nextStatusId']);
                    }
                }
                
                $workFlowId = getUID();
                $metaData = $this->db->GetRow("SELECT META_DATA_CODE, META_DATA_NAME FROM META_DATA WHERE META_DATA_ID = $idPh", array($metaDataId));
                
                $workFlowData = array(
                    'ID' => $workFlowId,
                    'WFM_WORKFLOW_CODE' => $metaData['META_DATA_CODE'],
                    'WFM_WORKFLOW_NAME' => $metaData['META_DATA_NAME'] . ' ажлын урсгал',
                    'IS_ACTIVE' => '1',
                    'CREATED_USER_ID' => $sessionUserId,
                    'CREATED_DATE' => $currentDate,
                    'REF_STRUCTURE_ID' => $metaDataId,
                );
                $result = $this->db->AutoExecute('META_WFM_WORKFLOW', $workFlowData);
                
                if ($result) {
                    $statusImp = implode(',', $statusArr);
                    foreach ($statusArr as $status) {
                        $this->db->AutoExecute('META_WFM_STATUS', array('WFM_WORKFLOW_ID' => $workFlowId), 'UPDATE', "ID = $status");
                    }
                }
            }

            foreach ($deleteData as $delete) {
                if (!in_array($delete['TRANSITION_ID'], $transitionArr)) {
                    $this->db->Execute("DELETE FROM META_WFM_TRANSITION WHERE ID = $idPh", array($delete['TRANSITION_ID']));
                }
                if (!in_array($delete['ID'], $workFlowArr)) {
                    $this->db->Execute("DELETE FROM META_WFM_WORKFLOW WHERE ID = $idPh", array($delete['ID']));
                }
            }

            $index = 0;
            $prevTransitionId = getUID();
            $ticket = true;
            $prevTransitionId = '';
            
            if (count($connections) != 0) {

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
                        'DESCRIPTION' => issetParam($row['description']),
                        'CRITERIA' => issetParam($row['criteria']),
                        'TRANSITION_TIME' => issetParam($row['transitionTime']),
                        'TIME_TYPE_ID' => issetParam($row['timeTypeId']),
                        'TRANSITION_COST' => issetParam($row['transitionCost']),
                        'TRANSITION_DISTANCE' => issetParam($row['transitionDistance']),
                        'ID' => $newTransitionId,
                        'IS_TRANSITION' => '1',
                        'WFM_WORKFLOW_ID' => $workFlowId,
                        'TOP' => issetParam($row['top']),
                        '"left"' => issetParam($row['left']),
                        'SOURCE_ID' => $transitionId,
                        'MODIFIED_USER_ID' => $sessionUserId,
                        'MODIFIED_DATE' => $currentDate
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
                            '"left"' => $row['positionLeft'],
                            'SOURCE_ID' => $transitionId,
                            'MODIFIED_USER_ID' => $sessionUserId,
                            'MODIFIED_DATE' => $currentDate                            
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
            
            (new Mdmeta())->serverReloadByDataView($metaDataId);
            
            return array('status' => 'success', 'message' => 'Амжилттай хадгаллаа', 'update' => $resultUpdate, 'transitionId' => $transitionId);
        }
    }

    public function saveVisualMetaStatusDataPackModel() {
        
        $metaDataId = Input::numeric('metaDataId');
        $transitionId = Input::post('transitionId');
        $checkWfmStatusIdsArr = array();
        
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
            WHERE 
                (PREV_WFM_STATUS_ID IN ($explodeWfmStatusArr) OR NEXT_WFM_STATUS_ID IN ($explodeWfmStatusArr)) 
                AND (ID <> $transitionId AND SOURCE_ID <> $transitionId)");
        
        if ($usedTransitionId != '0') {
            
            return array('status' => 'warning', 'text' => 'Ашигласан төлөв сонгогдсон байна', 'transitionId' => $transitionId);
            
        } else {
            
            $transitionData = self::getPrevNullTransitionDataPackModel($metaDataId, $transitionId);
            $connections = isset($_POST['connections']) ? $_POST['connections'] : array();
            $workFlowArr = $transitionArr = $connection = $statusArr = array();

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
                                                    INNER JOIN META_WFM_TRANSITION_PACK PREV ON WS.ID = PREV.PREV_WFM_STATUS_ID
                                                    WHERE PREV.SOURCE_ID = $transitionId
                                                )
                                                UNION (
                                                    SELECT WW.ID, NEX.ID AS TRANSITION_ID, WW.REF_STRUCTURE_ID FROM META_WFM_WORKFLOW WW 
                                                    INNER JOIN META_WFM_STATUS WS ON WW.ID = WS.WFM_WORKFLOW_ID
                                                    INNER JOIN META_WFM_TRANSITION_PACK NEX ON WS.ID = NEX.NEXT_WFM_STATUS_ID
                                                    WHERE NEX.SOURCE_ID = $transitionId
                                                )
                                            ) TEMP WHERE TEMP.REF_STRUCTURE_ID = $metaDataId");

            $deleteData2 = $this->db->GetAll("SELECT DISTINCT TEMP.ID, TEMP.TRANSITION_ID FROM (
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

            if (count($workFlowArr) == 0) {
                
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
                    $this->db->Execute("DELETE FROM META_WFM_TRANSITION_PACK WHERE ID = " . $delete['TRANSITION_ID']);
                }
                if (!in_array($delete['ID'], $workFlowArr)) {
                    $this->db->Execute("DELETE FROM META_WFM_WORKFLOW WHERE ID = " . $delete['ID']);
                }
            }

            foreach ($deleteData2 as $delete) {
                if (!in_array($delete['TRANSITION_ID'], $transitionArr)) {
                    $this->db->Execute("DELETE FROM META_WFM_TRANSITION WHERE ID = " . $delete['TRANSITION_ID']);
                }
                if (!in_array($delete['ID'], $workFlowArr)) {
                    $this->db->Execute("DELETE FROM META_WFM_WORKFLOW WHERE ID = " . $delete['ID']);
                }
            }

            $index = 0;
            $index2 = 0;
            $prevTransitionId = getUID();
            $ticket = true;
            $prevTransitionId = '';
            
            if (count($connections) != 0) {
                $_objects = $_POST['objects'];
                
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
                    $ticket = $this->db->AutoExecute('META_WFM_TRANSITION_PACK', $connection);

                    foreach ($_objects as $key => $row2) {
                    
                        if (isset($row2['packid']) && $row['nextStatusId'] == $row2['packid']) {
                            $newTransitionId = getUID();
                
                            if ($index2 == 0) {
                                $index2++;
                                $prevTransitionId = $transitionId;
                            }
        
                            $connection = array(
                                'PREV_WFM_STATUS_ID' => $row['prevStatusId'],
                                'NEXT_WFM_STATUS_ID' => ($row2['id'] == 'endObject001') ? null : $row2['id'],
                                'PREV_ID' => $prevTransitionId,
                                'DESCRIPTION' => isset($row['description']) ? $row['description'] : '',
                                'CRITERIA' => isset($row['criteria']) ? $row['criteria'] : '',
                                'ID' => $newTransitionId,
                                'IS_TRANSITION' => '1',
                                'WFM_WORKFLOW_ID' => $workFlowId,
                                'TOP' => $row['top'],
                                '"left"' => $row['left'],
                                'SOURCE_ID' => $transitionId,
                            );
                            $ticket = $this->db->AutoExecute('META_WFM_TRANSITION', $connection);
                            
                            if ($ticket) {
                                $this->db->AutoExecute('META_WFM_TRANSITION', array('NEXT_ID' => $newTransitionId), 'UPDATE', ' ID =' . $prevTransitionId);
                                $prevTransitionId = $newTransitionId;
                            }  
                        }
                    }                    
                    
                    if ($ticket) {
                        $this->db->AutoExecute('META_WFM_TRANSITION_PACK', array('NEXT_ID' => $newTransitionId), 'UPDATE', ' ID =' . $prevTransitionId);
                        $prevTransitionId = $newTransitionId;
                    }
                }                

                foreach ($_objects as $key => $row) {
                    
                    if (isset($row['packid'])) {
                        $newTransitionId = getUID();
                        if ($key == 0) {
                            $prevTransitionId = $transitionId;
                        }
                        
                        if ($row['id'] != 'endObject001') {
                            $connection = array(
                                'PREV_WFM_STATUS_ID' => $row['packid'],
                                'NEXT_WFM_STATUS_ID' => null,
                                'CHILD_WFM_STATUS_ID' => $row['id'],
                                'PREV_ID' => $transitionId,
                                'ID' => $newTransitionId,
                                'IS_TRANSITION' => '1',
                                'TOP' => $row['positionTop'],
                                'LEFT' => $row['positionLeft'],
                                'SOURCE_ID' => $transitionId
                            );

                            $ticket = $this->db->AutoExecute('META_WFM_TRANSITION_PACK', $connection);
                            if ($ticket) {
                                $prevTransitionId = $newTransitionId;
                            }
                        }
                    }
                }                
                
            } else {
                
                $_objects = $_POST['objects'];
                
                foreach ($_objects as $key => $row) {
                    
                    $newTransitionId = getUID();
                    if ($key == 0) {
                        $prevTransitionId = $transitionId;
                    }
                    
                    if ($row['id'] != 'endObject001') {

                        if (isset($row['packid'])) {
                            $connection = array(
                                'PREV_WFM_STATUS_ID' => $row['packid'],
                                'NEXT_WFM_STATUS_ID' => null,
                                'CHILD_WFM_STATUS_ID' => $row['id'],
                                'PREV_ID' => $transitionId,
                                'ID' => $newTransitionId,
                                'IS_TRANSITION' => '1',
                                'TOP' => $row['positionTop'],
                                'LEFT' => $row['positionLeft'],
                                'SOURCE_ID' => $transitionId
                            );
                        } else {                     
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
                        }

                        $ticket = $this->db->AutoExecute('META_WFM_TRANSITION_PACK', $connection);
                        if ($ticket) {
                            $prevTransitionId = $newTransitionId;
                        }
                    }
                }

                foreach ($_objects as $key => $row) {
                    
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
                            '"left"' => $row['positionLeft'],
                            'SOURCE_ID' => $transitionId
                        );

                        $ticket = $this->db->AutoExecute('META_WFM_TRANSITION', $connection);
                        if ($ticket) {
                            $prevTransitionId = $newTransitionId;
                        }
                    }
                }                
            }
            
            (new Mdmeta())->serverReloadByDataView($metaDataId);
            
            $resultUpdate = $this->db->AutoExecute('META_WFM_TRANSITION_PACK', array('WFM_WORKFLOW_ID' => $workFlowId), 'UPDATE', ' ID = ' . $transitionId);
            $resultUpdate = $this->db->AutoExecute('META_WFM_TRANSITION', array('WFM_WORKFLOW_ID' => $workFlowId), 'UPDATE', ' ID = ' . $transitionId);            
            $this->db->UpdateClob('META_WFM_TRANSITION_PACK', 'POSITION_SOURCE', json_encode($_POST['workFlowHtml']), 'ID = '.$transitionId);
            $this->db->UpdateClob('META_WFM_TRANSITION', 'POSITION_SOURCE', json_encode($_POST['workFlowHtml']), 'ID = '.$transitionId);
            
            return array('status' => 'success', 'message' => 'Амжилттай хадгаллаа', 'update' => $resultUpdate, 'transitionId' => $transitionId);
        }
    }

    public function getPrevNullTransitionDataModel($metaDataId, $transitionId = null) {
        $where = '';
        
        if ($transitionId) {
            $where = "AND WT.ID IN ($transitionId)";
        }

        $data = $this->db->GetAll("
            SELECT 
                DISTINCT 
                WT.ID, 
                WS.WFM_STATUS_NAME, 
                WT.PREV_WFM_STATUS_ID, 
                WT.NEXT_WFM_STATUS_ID, 
                WW.ID AS WORKFLOW_ID, 
                WT.DESCRIPTION 
            FROM META_WFM_WORKFLOW WW 
                INNER JOIN META_WFM_STATUS WS ON WW.ID = WS.WFM_WORKFLOW_ID 
                INNER JOIN META_WFM_TRANSITION WT ON WS.ID = WT.NEXT_WFM_STATUS_ID 
            WHERE WW.REF_STRUCTURE_ID = $metaDataId 
                $where 
                AND PREV_WFM_STATUS_ID IS NULL 
                AND IS_TRANSITION = 0 
            ORDER BY WS.WFM_STATUS_NAME");
        
        return $data;
    }

    public function getPrevNullTransitionDataPackModel($metaDataId, $transitionId = null) {
        $and = "";
        if ($transitionId) {
            $and = "AND WT.ID = $transitionId";
        }

        return $this->db->GetAll("SELECT DISTINCT
                                        WT.ID,
                                        WS.WFM_STATUS_NAME, WT.PREV_WFM_STATUS_ID, WT.NEXT_WFM_STATUS_ID, 
                                        WW.ID AS WORKFLOW_ID, WT.DESCRIPTION 
                                    FROM META_WFM_WORKFLOW WW
                                    INNER JOIN META_WFM_STATUS WS ON WW.ID = WS.WFM_WORKFLOW_ID
                                    INNER JOIN META_WFM_TRANSITION_PACK WT ON WS.ID = WT.NEXT_WFM_STATUS_ID
                                    WHERE REF_STRUCTURE_ID = '$metaDataId' $and AND PREV_WFM_STATUS_ID IS NULL AND IS_TRANSITION = 0
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
        
        if (Input::postCheck('transtionId') && Input::isEmpty('transtionId') === false) {
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
        return $response;
    }

    public function deleteWfmStatusModel() {
        
        $response = array('status' => 'warning', 'message' => 'Амжилтгүй боллоо.');
        
        if (Input::postCheck('statusId') && Input::isEmpty('statusId') === false) {
            
            $statusId = Input::post('statusId');
            
            try {
                $result = $this->db->Execute("
                    DELETE 
                    FROM META_WFM_TRANSITION 
                    WHERE ID IN (
                        SELECT 
                            ID 
                        FROM META_WFM_TRANSITION 
                        WHERE PREV_WFM_STATUS_ID = $statusId OR NEXT_WFM_STATUS_ID = $statusId 
                    ) OR SOURCE_ID IN (
                        SELECT 
                            ID 
                        FROM META_WFM_TRANSITION 
                        WHERE PREV_WFM_STATUS_ID = 7002 OR NEXT_WFM_STATUS_ID = $statusId 
                    )");
                
                if ($result) {
                    $result = $this->db->Execute("DELETE FROM META_WFM_STATUS WHERE ID = $statusId");
                    if ($result) {
                        $response = array('status' => 'success', 'message' => 'Амжилттай устгагдлаа.');
                    }
                }
                
            } catch (Exception $ex) {
                $response = array('status' => 'warning', 'message' => 'Амжилтгүй боллоо, Алдаа: '.$ex->msg, 'exception' => $ex);
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
        
        try {
            
            if (Input::postCheck('rows') && Input::post('type') != 'statusdelete') {
                $ids = Arr::implode_key(',', Input::post('rows'), 'ID', true);
            } else {
                $ids = Input::numeric('statusPermissionId');
            }
            
            if (Input::post('type') == 'statusdelete') {
                $ids = Arr::implode_key(',', Input::post('rows'), 'id', true);
                $this->db->Execute('DELETE FROM META_WFM_INHERITANCE WHERE ID IN ('.$ids.')');
            } else {
                $this->db->Execute('DELETE FROM META_WFM_STATUS_PERMISSION WHERE ID IN ('.$ids.')');
            }
            
            $response = array('status' => 'success', 'message' => 'Амжилттай устгагдлаа');
            
        } catch (Exception $ex) {
            $response = array('status' => 'error', 'message' => $ex->getMessage());
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
            WHERE META_DATA_ID IN ($mainBpId)");

        return $data;
    }

    public function getObjectPositionListModel($mainBpId) {

        $idPh = $this->db->Param(0);
        
        $metaData = $this->db->GetRow("
            SELECT 
                MD.META_DATA_ID, 
                MD.META_DATA_CODE, 
                MD.META_DATA_NAME, 
                MD.ADDON_DATA 
            FROM META_DATA MD
                INNER JOIN META_BUSINESS_PROCESS_LINK MBPL ON MD.META_DATA_ID = MBPL.META_DATA_ID
            WHERE MD.META_DATA_ID = $idPh", array($mainBpId));

        $processWorkflowList = $this->db->GetAll("
            SELECT 
                MPW.META_PROCESS_WORKFLOW_ID, 
                MD.META_DATA_ID, 
                MD.META_DATA_CODE, 
                ".$this->db->IfNull("MBPL.PROCESS_NAME", "MD.META_DATA_NAME")." AS META_DATA_NAME,
                MD.META_TYPE_ID, 
                MPW.MAIN_BP_ID, 
                MPW.DO_BP_ID, 
                MPW.IS_SCHEDULED, 
                MPW.SCHEDULED_DATE_PATH, 
                CASE 
                    WHEN (
                        SELECT 
                            COUNT(META_PROCESS_WORKFLOW_ID) 
                        FROM META_PROCESS_WORKFLOW 
                        WHERE MAIN_BP_ID = MD.META_DATA_ID 
                            AND IS_ACTIVE = 1 
                            AND MAIN_BP_ID <> MPW.MAIN_BP_ID 
                            AND MAIN_BP_ID <> DO_BP_ID   
                        ) > 0 
                    THEN 1 
                    ELSE 0 
                END AS IS_COMPLEX_PROCESS, 
                MPW.TASKFLOW_TYPE, 
                MPW.DESCRIPTION 
            FROM META_PROCESS_WORKFLOW MPW 
                INNER JOIN META_DATA MD ON MD.META_DATA_ID = MPW.DO_BP_ID
                LEFT JOIN META_BUSINESS_PROCESS_LINK MBPL ON MBPL.META_DATA_ID = MD.META_DATA_ID 
                LEFT JOIN META_BOOKMARK_LINKS MBM ON MBM.META_DATA_ID = MD.META_DATA_ID
            WHERE MPW.MAIN_BP_ID = $idPh 
                AND (MBPL.META_DATA_ID IS NOT NULL OR MBM.META_DATA_ID IS NOT NULL)", array($mainBpId));

        $positionData = json_decode($metaData['ADDON_DATA']);

        $position = array();

        if ($positionData) {
            foreach ($positionData as $row) {
                if (isset($row->id)) {
                    $position[$row->id] = array('positionTop' => $row->positionTop, 'positionLeft' => $row->positionLeft);
                }
            }
        }

        $object = array();
        $connect = array();
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
            'height' => '30', 
            'doBpId' => '', 
            'isScheduled' => '', 
            'scheduledDatePath' => '', 
            'taskflowType' => null, 
            'metaTypeId' => null, 
            'description' => null
        ));

        if ($processWorkflowList) {

            foreach ($processWorkflowList as $row) {
                $pId = $row['META_PROCESS_WORKFLOW_ID'];

                array_push($object, array(
                    'id' => $pId,
                    'metaDataCode' => $row['META_DATA_CODE'],
                    'title' => Lang::line($row['META_DATA_NAME']),
                    'doBpId' => $row['DO_BP_ID'],
                    'type' => 'rectangle',
                    'class' => 'wfIconRectangle ' . (($row['DO_BP_ID'] == $mainBpId) ? 'wfIconRectangleBackground' : ''),
                    'positionTop' => (isset($position[$pId]) ? $position[$pId]['positionTop'] : $positionTop),
                    'positionLeft' => (isset($position[$pId]) ? $position[$pId]['positionLeft'] : $positionLeft),
                    'width' => '160',
                    'height' => '70',
                    'isScheduled' => $row['IS_SCHEDULED'], 
                    'scheduledDatePath' => $row['SCHEDULED_DATE_PATH'], 
                    'isComplexProcess' => $row['IS_COMPLEX_PROCESS'], 
                    'taskflowType' => $row['TASKFLOW_TYPE'], 
                    'metaTypeId' => $row['META_TYPE_ID'], 
                    'description' => $row['DESCRIPTION']
                ));
                $positionLeft += 300;
            }

            $connect = $this->db->GetAll("
                SELECT 
                    ".$this->db->IfNull('BEH.META_PROCESS_WF_ID', 0)." AS SOURCE,  
                    BEH.NEXT_META_PROCESS_WF_ID AS TARGET,
                    BEH.CRITERIA 
                FROM META_PROCESS_WF_BEHAVIOUR BEH
                WHERE BEH.MAIN_BP_ID = $idPh", array($mainBpId));

        } else {

            $pId = getUID();

            array_push($object, array(
                'id' => $pId,
                'metaDataCode' => $metaData['META_DATA_CODE'],
                'title' => $metaData['META_DATA_NAME'],
                'doBpId' => $metaData['META_DATA_ID'],
                'type' => 'rectangle',
                'class' => 'wfIconRectangle wfIconRectangleBackground',
                'positionTop' => $positionTop,
                'positionLeft' => $positionLeft + 170,
                'width' => '160',
                'height' => '70',
                'isScheduled' => '', 
                'scheduledDatePath' => ''
            ));

            array_push($connect, array(
                'SOURCE' => '0',
                'TARGET' => $pId,
                'CRITERIA' => ''
            ));
        }

        return array('object' => $object, 'connect' => $connect, 'paramMapLinks' => array(array('id' => '1626661933302760', 'id2' => '1626661978451871')));
    }

    public function lastRunTaskFlowModel($mainBpId, $recordId) {
        $getLastTaskFlowRow = $this->db->GetRow("
            SELECT T0.ID 
            FROM META_TASKFLOW_LOG T0
            LEFT JOIN META_TASKFLOW_LOG_DTL MTLD ON T0.ID = MTLD.TASKFLOW_LOG_ID
            WHERE T0.MAIN_BP_ID = ".$this->db->Param(0)." AND MTLD.RECORD_ID = ".$this->db->Param(1)."
            ORDER BY T0.CREATED_DATE DESC", array($mainBpId, $recordId));            

        return $getLastTaskFlowRow;
    }

    public function lastRunRecordTaskFlowModel($mainBpId, $recordId) {
        $getLastTaskFlowRow = self::lastRunTaskFlowModel($mainBpId, $recordId);          

        if (empty($getLastTaskFlowRow)) {
            return '';
        }

        $createdUserId  = Ue::sessionUserKeyId();

        $getLastTaskFlowRow = $this->db->GetRow("
            SELECT RECORD_ID FROM META_TASKFLOW_LOG_DTL 
            WHERE TASKFLOW_LOG_ID = ".$this->db->Param(0)." AND CREATED_USER_ID = ".$this->db->Param(1)."
            ORDER BY CREATED_DATE DESC", array($getLastTaskFlowRow['ID'], $createdUserId));            

        return $getLastTaskFlowRow;
    }

    public function getObjectPositionListViewModel($mainBpId, $recordId) {

        $idPh = $this->db->Param(0);        
        
        $metaData = $this->db->GetRow("
            SELECT 
                MD.META_DATA_ID, 
                MD.META_DATA_CODE, 
                MD.META_DATA_NAME, 
                MD.ADDON_DATA 
            FROM META_DATA MD
                INNER JOIN META_BUSINESS_PROCESS_LINK MBPL ON MD.META_DATA_ID = MBPL.META_DATA_ID
            WHERE MD.META_DATA_ID = $idPh", array($mainBpId));

        $getLastTaskFlowRow = self::lastRunTaskFlowModel($mainBpId, $recordId);            

        $processWorkflowList = $this->db->GetAll("
            SELECT 
                MPW.META_PROCESS_WORKFLOW_ID, 
                MD.META_DATA_ID, 
                MD.META_DATA_CODE, 
                MD2.META_DATA_CODE AS TASK_FLOW_META_CODE, 
                ".$this->db->IfNull("MBPL.PROCESS_NAME", "MD.META_DATA_NAME")." AS META_DATA_NAME,
                MD.META_TYPE_ID, 
                MPW.MAIN_BP_ID, 
                MPW.DO_BP_ID, 
                MPW.IS_SCHEDULED, 
                MPW.SCHEDULED_DATE_PATH, 
                CASE 
                    WHEN (
                        SELECT 
                            COUNT(META_PROCESS_WORKFLOW_ID) 
                        FROM META_PROCESS_WORKFLOW 
                        WHERE MAIN_BP_ID = MD.META_DATA_ID 
                            AND IS_ACTIVE = 1 
                            AND MAIN_BP_ID <> MPW.MAIN_BP_ID 
                            AND MAIN_BP_ID <> DO_BP_ID   
                        ) > 0 
                    THEN 1 
                    ELSE 0 
                END AS IS_COMPLEX_PROCESS, 
                CASE 
                    WHEN (
                        SELECT 
                            COUNT(MTUIL.META_DATA_ID) 
                        FROM META_TASKFLOW_LOG MTL
                        LEFT JOIN META_TASKFLOW_UI_LOG MTUIL ON MTL.ID = MTUIL.TASKFLOW_LOG_ID
                        WHERE MTL.ID IN (".$this->db->Param(1).") AND MTUIL.META_DATA_ID = MD.META_DATA_ID   
                        ) > 0 
                    THEN 1 
                    ELSE 0 
                END AS IS_WORKED,
                MPW.TASKFLOW_TYPE,
                MBM.BOOKMARK_URL,
                T3.TASKFLOW_ID 
            FROM META_PROCESS_WORKFLOW MPW 
                INNER JOIN META_DATA MD ON MD.META_DATA_ID = MPW.DO_BP_ID
                INNER JOIN META_DATA MD2 ON MD2.META_DATA_ID = MPW.MAIN_BP_ID
                LEFT JOIN META_BUSINESS_PROCESS_LINK MBPL ON MBPL.META_DATA_ID = MD.META_DATA_ID
                LEFT JOIN (
                    SELECT 
                        MTUIL.META_DATA_ID,
                        MTUIL.ID AS TASKFLOW_ID
                    FROM META_TASKFLOW_LOG MTL
                    LEFT JOIN META_TASKFLOW_UI_LOG MTUIL ON MTL.ID = MTUIL.TASKFLOW_LOG_ID
                    WHERE MTL.ID = ".$this->db->Param(1)."
                ) T3 ON T3.META_DATA_ID = MD.META_DATA_ID
                LEFT JOIN META_BOOKMARK_LINKS MBM ON MBM.META_DATA_ID = MD.META_DATA_ID
            WHERE MPW.MAIN_BP_ID = $idPh 
                AND (MBPL.META_DATA_ID IS NOT NULL OR MBM.META_DATA_ID IS NOT NULL)", array($mainBpId, issetParam($getLastTaskFlowRow['ID'])));

        $positionData = json_decode($metaData['ADDON_DATA']);

        $position = array();

        if ($positionData) {
            foreach ($positionData as $row) {
                if (isset($row->id)) {
                    $position[$row->id] = array('positionTop' => $row->positionTop, 'positionLeft' => $row->positionLeft);
                }
            }
        }

        $object = array();
        $connect = array();
        $positionLeft = 110;
        $positionTop = 80;
        $t = 0;

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
            'height' => '30', 
            'doBpId' => '', 
            'isScheduled' => '', 
            'scheduledDatePath' => '', 
            'taskflowType' => null, 
            'metaTypeId' => null
        ));

        if ($processWorkflowList) {

            $lastRunTaskFlow = [];
            $allFlowCount =  count($processWorkflowList);            
            foreach ($processWorkflowList as $row) {
                $pId = $row['META_PROCESS_WORKFLOW_ID'];

                array_push($object, array(
                    'id' => $pId,
                    'metaDataCode' => $row['META_DATA_CODE'],
                    'title' => Lang::line($row['META_DATA_NAME']),
                    'doBpId' => $row['DO_BP_ID'],
                    'type' => 'rectangle',
                    'class' => 'wfIconRectangle ' . (($row['DO_BP_ID'] == $mainBpId) ? 'wfIconRectangleBackground' : ''),
                    'positionTop' => (isset($position[$pId]) ? $position[$pId]['positionTop'] : $positionTop),
                    'positionLeft' => (isset($position[$pId]) ? $position[$pId]['positionLeft'] : $positionLeft),
                    'width' => '160',
                    'height' => '70',
                    'isScheduled' => $row['IS_SCHEDULED'], 
                    'scheduledDatePath' => $row['SCHEDULED_DATE_PATH'], 
                    'isComplexProcess' => $row['IS_COMPLEX_PROCESS'], 
                    'isWorked' => $row['IS_WORKED'], 
                    'taskFlowId' => $row['TASKFLOW_ID'], 
                    'taskflowType' => $row['TASKFLOW_TYPE'], 
                    'bookmark_url' => $row['BOOKMARK_URL'], 
                    'metaTypeId' => $row['META_TYPE_ID']
                ));
                $positionLeft += 300;
                
                if (!$row['TASKFLOW_TYPE']) {
                    $t++;
                }

                if ($row['IS_WORKED'] == '1') {
                    $lastRunTaskFlow = $row;
                }
            }

            if ($lastRunTaskFlow) {
                $_POST['taskFlowCode'] = $lastRunTaskFlow['TASK_FLOW_META_CODE'];
                $_POST['oneSelectedRow'] = ['id' => $recordId];
                $getTaskFlowResult = self::callTaskFlowModel();
            }

            $connect = $this->db->GetAll("
                SELECT 
                    ".$this->db->IfNull('BEH.META_PROCESS_WF_ID', 0)." AS SOURCE,  
                    BEH.NEXT_META_PROCESS_WF_ID AS TARGET,
                    BEH.CRITERIA 
                FROM META_PROCESS_WF_BEHAVIOUR BEH
                WHERE BEH.MAIN_BP_ID = $idPh", array($mainBpId));

        } else {

            $pId = getUID();

            array_push($object, array(
                'id' => $pId,
                'metaDataCode' => $metaData['META_DATA_CODE'],
                'title' => $metaData['META_DATA_NAME'],
                'doBpId' => $metaData['META_DATA_ID'],
                'type' => 'rectangle',
                'class' => 'wfIconRectangle wfIconRectangleBackground',
                'positionTop' => $positionTop,
                'positionLeft' => $positionLeft + 170,
                'width' => '160',
                'height' => '70',
                'isScheduled' => '', 
                'scheduledDatePath' => ''
            ));

            array_push($connect, array(
                'SOURCE' => '0',
                'TARGET' => $pId,
                'CRITERIA' => ''
            ));
        }

        return array(
            'object' => $object, 
            'connect' => $connect, 
            'lastRunTaskFlow' => issetParam($getTaskFlowResult['result']), 
            'allNotUiTaskFlow' => $allFlowCount == $t, 
            'paramMapLinks' => array(array('id' => '1626661933302760', 'id2' => '1626661978451871'))
        );
    }

    public function saveVisualMetaProcessWorkflowModel($object = '', $connect = '') {

        $mainBpId = Input::post('mainBpId');
        $currentDate = Date::currentDate();
        $sessionUserId = Ue::sessionUserKeyId();
        
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
                
                $idPh = $this->db->Param(0);
                
                $this->db->Execute("DELETE FROM META_PROCESS_WF_BEHAVIOUR WHERE MAIN_BP_ID = $idPh", array($mainBpId));
                $this->db->Execute("DELETE FROM META_PROCESS_WORKFLOW WHERE MAIN_BP_ID = $idPh", array($mainBpId));

                $result = $this->db->UpdateClob('META_DATA', 'ADDON_DATA', json_encode($_POST['conn']), 'META_DATA_ID = ' . $mainBpId);
                
                foreach ($object as $key => $row) {

                    $pId = Input::param($row['id']);
                    
                    if ($pId != '0') {
                        $data = array(
                            'META_PROCESS_WORKFLOW_ID' => $pId,
                            'MAIN_BP_ID' => $mainBpId,
                            'DO_BP_ID' => Input::param($row['dobpid']),
                            'IS_ACTIVE' => 1,
                            'CREATED_DATE' => $currentDate,
                            'CREATED_USER_ID' => $sessionUserId, 
                            'IS_SCHEDULED' => Input::param($row['isScheduled']),
                            'SCHEDULED_DATE_PATH' => Input::param($row['scheduledPath']), 
                            'TASKFLOW_TYPE' => Input::param($row['taskflowType']), 
                            'DESCRIPTION' => issetVar($row['description'])
                        );
                        $this->db->AutoExecute('META_PROCESS_WORKFLOW', $data);
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
                
                (new Mdmeta())->setMetaModifiedDate($mainBpId);
                
                $metaRow = self::getMetaDataModel($mainBpId);
                
                (new Mdmeta())->serverReloadByProcess($metaRow['META_DATA_CODE']);
                
                return array('status' => 'success');
            }
            
        } catch (Exception $ex) {
            return array('status' => 'warning', 'text' => $ex->msg, 'message' => $ex);
        }
    }

    public function extractBpmnXml($xml, $return = false, $return2 = false) {
        $value = Xml::createArray($xml);
        $sequenceFlow = [];
        $isSequenceFlow = false;
        $bpmProcess = [];
        $task = [];        
        $gateway = [];        
        $gatewayInclusive = [];        
        $gatewayParallel = [];        
        $gatewayExclusive = [];        

        if (array_key_exists("bpmn2:definitions", $value)) {

            if (!array_key_exists(0, $value["bpmn2:definitions"]["bpmn2:process"])) {
                $bpmProcess[] = $value["bpmn2:definitions"]["bpmn2:process"];
            } else {
                $bpmProcess = $value["bpmn2:definitions"]["bpmn2:process"];
            }

            if (isset($value["bpmn2:definitions"]["bpmn2:collaboration"]) && isset($value["bpmn2:definitions"]["bpmn2:collaboration"]["bpmn2:messageFlow"])) {
                if (!array_key_exists(0, $value["bpmn2:definitions"]["bpmn2:collaboration"]["bpmn2:messageFlow"])) {
                    $sequenceFlow[] = $value["bpmn2:definitions"]["bpmn2:collaboration"]["bpmn2:messageFlow"];
                } else {
                    $sequenceFlow = $value["bpmn2:definitions"]["bpmn2:collaboration"]["bpmn2:messageFlow"];
                }
            }

            foreach ($bpmProcess as $prow) {
                if (isset($prow["bpmn2:task"])) {
                    if (isset($prow["bpmn2:sequenceFlow"]) && !$isSequenceFlow) {
                        $sequenceFlow2 = $sequenceFlow;
                        if (!array_key_exists(0, $prow["bpmn2:sequenceFlow"])) {
                            $sequenceFlow = [$prow["bpmn2:sequenceFlow"]];
                        } else {
                            $sequenceFlow = $prow["bpmn2:sequenceFlow"];
                        }
                        $sequenceFlow = array_merge($sequenceFlow2, $sequenceFlow);
                    }

                    $task2 = [];
                    if (!array_key_exists(0, $prow["bpmn2:task"])) {
                        $task2[] = $prow["bpmn2:task"];
                    } else {
                        $task2 = $prow["bpmn2:task"];
                    }

                    if (isset($value["bpmn2:definitions"]["bpmn2:collaboration"]) && isset($value["bpmn2:definitions"]["bpmn2:collaboration"]["bpmn2:participant"])) {
                        if (!array_key_exists(0, $value["bpmn2:definitions"]["bpmn2:collaboration"]["bpmn2:participant"])) {
                            $participant[] = $value["bpmn2:definitions"]["bpmn2:collaboration"]["bpmn2:participant"];
                        } else {
                            $participant = $value["bpmn2:definitions"]["bpmn2:collaboration"]["bpmn2:participant"];
                        }
                        if (isset($participant)) {
                            foreach ($participant as $part) {
                                if ($part["@attributes"]["processRef"] == $prow["@attributes"]["id"]) {
                                    foreach ($task2 as $tkey => $trow) {
                                        $task2[$tkey]["roleid"] = isset($part["@attributes"]["roleid"]) ? explode(",", $part["@attributes"]["roleid"]) : '';
                                    }
                                }
                            }
                        }
                    }           

                    $task = array_merge($task2, $task);

                    if (isset($prow["bpmn2:gateway"])) {
                        $gateway2 = $gateway;
                        if (!array_key_exists(0, $prow["bpmn2:gateway"])) {
                            $gateway = [$prow["bpmn2:gateway"]];
                        } else {
                            $gateway = $prow["bpmn2:gateway"];
                        }   
                        $gateway = array_merge($gateway2, $gateway);
                    }   
                    
                    if (isset($prow["bpmn2:inclusiveGateway"])) {
                        $gatewayInclusive2 = $gatewayInclusive;
                        if (!array_key_exists(0, $prow["bpmn2:inclusiveGateway"])) {
                            $gatewayInclusive = [$prow["bpmn2:inclusiveGateway"]];
                        } else {
                            $gatewayInclusive = $prow["bpmn2:inclusiveGateway"];
                        }            
                        $gatewayInclusive = array_merge($gatewayInclusive2, $gatewayInclusive);
                    }            
                    
                    if (isset($prow["bpmn2:parallelGateway"])) {
                        $gatewayParallel2 = $gatewayParallel;
                        if (!array_key_exists(0, $prow["bpmn2:parallelGateway"])) {
                            $gatewayParallel = [$prow["bpmn2:parallelGateway"]];
                        } else {
                            $gatewayParallel = $prow["bpmn2:parallelGateway"];
                        }        
                        $gatewayParallel = array_merge($gatewayParallel2, $gatewayParallel);    
                    }            
                    
                    if (isset($prow["bpmn2:exclusiveGateway"])) {
                        $gatewayExclusive2 = $gatewayExclusive;
                        if (!array_key_exists(0, $prow["bpmn2:exclusiveGateway"])) {
                            $gatewayExclusive = [$prow["bpmn2:exclusiveGateway"]];
                        } else {
                            $gatewayExclusive = $prow["bpmn2:exclusiveGateway"];
                        }            
                        $gatewayExclusive = array_merge($gatewayExclusive2, $gatewayExclusive);
                    }            
                }            
            }

            if ($return2) {
                $sequenceFlowArr = [];
                foreach ($sequenceFlow as $row) {
                    if (strpos($row['@attributes']['sourceRef'], $row['@attributes']['targetRef']) === false 
                        && strpos($row['@attributes']['targetRef'], $row['@attributes']['sourceRef']) === false) {
                        array_push($sequenceFlowArr, $row);
                    }
                }
                $sequenceFlow = $sequenceFlowArr;     

                return compact("sequenceFlow", "task", "gateway", "xml");
            }

            if ($return) {

                if ($gateway) {
                    foreach ($gateway as $skey => $row22) {
                        foreach ($sequenceFlow as $tkey2 => $row2) {
                            if ($row2['@attributes']['id'] === $row22['bpmn2:incoming']) {
                                $prevTaskId = $row2['@attributes']['sourceRef'];
                            }
                        }    
                        foreach ($sequenceFlow as $skey => $row2) {
                            if ($row22['@attributes']['id'] === $row2['@attributes']['targetRef']) {
                                $xml = str_replace($row2['@attributes']['targetRef'], $prevTaskId, $xml);
                            }
                            if ($row22['@attributes']['id'] === $row2['@attributes']['sourceRef']) {
                                $xml = str_replace($row2['@attributes']['sourceRef'], $prevTaskId, $xml);
                            }
                        }                     
                    }         
                }         

                if ($gatewayInclusive) {
                    foreach ($gatewayInclusive as $skey => $row22) {
                        foreach ($sequenceFlow as $tkey2 => $row2) {
                            if ($row2['@attributes']['id'] === $row22['bpmn2:incoming']) {
                                $prevTaskId = $row2['@attributes']['sourceRef'];
                            }
                        }    
                        foreach ($sequenceFlow as $skey => $row2) {
                            if ($row22['@attributes']['id'] === $row2['@attributes']['targetRef']) {
                                $xml = str_replace($row2['@attributes']['targetRef'], $prevTaskId.'_inclusive', $xml);
                            }
                            if ($row22['@attributes']['id'] === $row2['@attributes']['sourceRef']) {
                                $xml = str_replace($row2['@attributes']['sourceRef'], $prevTaskId.'_inclusive', $xml);
                            }
                        }                     
                    }         
                }         

                if ($gatewayParallel) {
                    foreach ($gatewayParallel as $skey => $row22) {
                        foreach ($sequenceFlow as $tkey2 => $row2) {
                            if ($row2['@attributes']['id'] === $row22['bpmn2:incoming']) {
                                $prevTaskId = $row2['@attributes']['sourceRef'];
                            }
                        }    
                        foreach ($sequenceFlow as $skey => $row2) {
                            if ($row22['@attributes']['id'] === $row2['@attributes']['targetRef']) {
                                $xml = str_replace($row2['@attributes']['targetRef'], $prevTaskId.'_parallel', $xml);
                            }
                            if ($row22['@attributes']['id'] === $row2['@attributes']['sourceRef']) {
                                $xml = str_replace($row2['@attributes']['sourceRef'], $prevTaskId.'_parallel', $xml);
                            }
                        }                     
                    }         
                }         

                if ($gatewayExclusive) {
                    foreach ($gatewayExclusive as $skey => $row22) {
                        foreach ($sequenceFlow as $tkey2 => $row2) {
                            if ($row2['@attributes']['id'] === $row22['bpmn2:incoming']) {
                                $prevTaskId = $row2['@attributes']['sourceRef'];
                            }
                        }    
                        foreach ($sequenceFlow as $skey => $row2) {
                            if ($row22['@attributes']['id'] === $row2['@attributes']['targetRef']) {
                                $xml = str_replace($row2['@attributes']['targetRef'], $prevTaskId.'_exclusive', $xml);
                            }
                            if ($row22['@attributes']['id'] === $row2['@attributes']['sourceRef']) {
                                $xml = str_replace($row2['@attributes']['sourceRef'], $prevTaskId.'_exclusive', $xml);
                            }
                        }                     
                    }         
                }         

                return compact("sequenceFlow", "task", "gateway", "xml");
            }

            foreach ($sequenceFlow as $skey => $row) {
                foreach ($task as $tkey => $row2) {
                    if ($row2['@attributes']['id'] === $row['@attributes']['targetRef']) {
                        $uid = getUIDAdd($skey);
                        $xml = str_replace($row['@attributes']['targetRef'], $uid, $xml);
                    }
                }
            }         

        } else {

            if (!array_key_exists(0, $value["bpmn:definitions"]["bpmn:process"])) {
                $bpmProcess[] = $value["bpmn:definitions"]["bpmn:process"];
            } else {
                $bpmProcess = $value["bpmn:definitions"]["bpmn:process"];
            }

            if (isset($value["bpmn:definitions"]["bpmn:collaboration"]) && isset($value["bpmn:definitions"]["bpmn:collaboration"]["bpmn:messageFlow"])) {
                if (!array_key_exists(0, $value["bpmn:definitions"]["bpmn:collaboration"]["bpmn:messageFlow"])) {
                    $sequenceFlow[] = $value["bpmn:definitions"]["bpmn:collaboration"]["bpmn:messageFlow"];
                } else {
                    $sequenceFlow = $value["bpmn:definitions"]["bpmn:collaboration"]["bpmn:messageFlow"];
                }
            }

            if (isset($value["bpmn:definitions"]["bpmn:collaboration"]) && isset($value["bpmn:definitions"]["bpmn:collaboration"]["bpmn:participant"])) {
                if (!array_key_exists(0, $value["bpmn:definitions"]["bpmn:collaboration"]["bpmn:participant"])) {
                    $participant[] = $value["bpmn:definitions"]["bpmn:collaboration"]["bpmn:participant"];
                } else {
                    $participant = $value["bpmn:definitions"]["bpmn:collaboration"]["bpmn:participant"];
                }
                if (isset($participant)) {
                    foreach ($participant as $part) {
                        foreach ($bpmProcess as $prow) {
                            if ($part["@attributes"]["processRef"] == $prow["@attributes"]["id"] && isset($prow["bpmn:task"])) {
                                if (!array_key_exists(0, $prow["bpmn:task"])) {
                                    $task3[] = $prow["bpmn:task"];
                                } else {
                                    $task3 = $prow["bpmn:task"];
                                }
                                if (isset($prow["bpmn:sequenceFlow"])) {
                                    $sequenceFlow2 = $sequenceFlow;
                                    $isSequenceFlow = true;
                                    if (!array_key_exists(0, $prow["bpmn:sequenceFlow"])) {
                                        $sequenceFlow = [$prow["bpmn:sequenceFlow"]];
                                    } else {
                                        $sequenceFlow = $prow["bpmn:sequenceFlow"];
                                    }             
                                    
                                    foreach ($sequenceFlow as $seqkey => $seq) {
                                        $sequenceFlow[$seqkey]["roleId"] = $part["@attributes"]["roleid"];
                                    }
                                    
                                    $sequenceFlow = array_merge($sequenceFlow2, $sequenceFlow);               

                                    foreach ($sequenceFlow as $seqkey => $seq) {
                                        foreach ($task3 as $tkey => $t) {
                                            if ($seq["@attributes"]["sourceRef"] == $t["@attributes"]["id"]) {
                                                $sequenceFlow[$seqkey]["roleId"] = $part["@attributes"]["roleid"];
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }

            foreach ($bpmProcess as $prow) {
                if (isset($prow["bpmn:task"])) {
                    if (isset($prow["bpmn:sequenceFlow"]) && !$isSequenceFlow) {
                        $sequenceFlow2 = $sequenceFlow;
                        if (!array_key_exists(0, $prow["bpmn:sequenceFlow"])) {
                            $sequenceFlow = [$prow["bpmn:sequenceFlow"]];
                        } else {
                            $sequenceFlow = $prow["bpmn:sequenceFlow"];
                        }
                        $sequenceFlow = array_merge($sequenceFlow2, $sequenceFlow);
                    }

                    $task2 = $task;
                    if (!array_key_exists(0, $prow["bpmn:task"])) {
                        $task[] = $prow["bpmn:task"];
                    } else {
                        $task = $prow["bpmn:task"];
                    }
                    $task = array_merge($task2, $task);

                    if (isset($prow["bpmn:gateway"])) {
                        $gateway2 = $gateway;
                        if (!array_key_exists(0, $prow["bpmn:gateway"])) {
                            $gateway = [$prow["bpmn:gateway"]];
                        } else {
                            $gateway = $prow["bpmn:gateway"];
                        }   
                        $gateway = array_merge($gateway2, $gateway);
                    }   
                    
                    if (isset($prow["bpmn:inclusiveGateway"])) {
                        $gatewayInclusive2 = $gatewayInclusive;
                        if (!array_key_exists(0, $prow["bpmn:inclusiveGateway"])) {
                            $gatewayInclusive = [$prow["bpmn:inclusiveGateway"]];
                        } else {
                            $gatewayInclusive = $prow["bpmn:inclusiveGateway"];
                        }            
                        $gatewayInclusive = array_merge($gatewayInclusive2, $gatewayInclusive);
                    }            
                    
                    if (isset($prow["bpmn:parallelGateway"])) {
                        $gatewayParallel2 = $gatewayParallel;
                        if (!array_key_exists(0, $prow["bpmn:parallelGateway"])) {
                            $gatewayParallel = [$prow["bpmn:parallelGateway"]];
                        } else {
                            $gatewayParallel = $prow["bpmn:parallelGateway"];
                        }        
                        $gatewayParallel = array_merge($gatewayParallel2, $gatewayParallel);    
                    }            
                    
                    if (isset($prow["bpmn:exclusiveGateway"])) {
                        $gatewayExclusive2 = $gatewayExclusive;
                        if (!array_key_exists(0, $prow["bpmn:exclusiveGateway"])) {
                            $gatewayExclusive = [$prow["bpmn:exclusiveGateway"]];
                        } else {
                            $gatewayExclusive = $prow["bpmn:exclusiveGateway"];
                        }            
                        $gatewayExclusive = array_merge($gatewayExclusive2, $gatewayExclusive);
                    }            
                }            
            }

            if ($return2) {
                $sequenceFlowArr = [];
                foreach ($sequenceFlow as $row) {
                    if (strpos($row['@attributes']['sourceRef'], $row['@attributes']['targetRef']) === false 
                        && strpos($row['@attributes']['targetRef'], $row['@attributes']['sourceRef']) === false) {
                        array_push($sequenceFlowArr, $row);
                    }
                }
                $sequenceFlow = $sequenceFlowArr;

                return compact("sequenceFlow", "task", "gateway", "xml");
            }

            if ($return) {

                if ($gateway) {
                    foreach ($gateway as $skey => $row22) {
                        foreach ($sequenceFlow as $tkey2 => $row2) {
                            if ($row2['@attributes']['id'] === $row22['bpmn:incoming']) {
                                $prevTaskId = $row2['@attributes']['sourceRef'];
                            }
                        }    
                        foreach ($sequenceFlow as $skey => $row2) {
                            if ($row22['@attributes']['id'] === $row2['@attributes']['targetRef']) {
                                $xml = str_replace($row2['@attributes']['targetRef'], $prevTaskId, $xml);
                            }
                            if ($row22['@attributes']['id'] === $row2['@attributes']['sourceRef']) {
                                $xml = str_replace($row2['@attributes']['sourceRef'], $prevTaskId, $xml);
                            }
                        }                     
                    }         
                }         

                if ($gatewayInclusive) {
                    foreach ($gatewayInclusive as $skey => $row22) {
                        foreach ($sequenceFlow as $tkey2 => $row2) {
                            if ($row2['@attributes']['id'] === $row22['bpmn:incoming']) {
                                $prevTaskId = $row2['@attributes']['sourceRef'];
                            }
                        }    
                        foreach ($sequenceFlow as $skey => $row2) {
                            if ($row22['@attributes']['id'] === $row2['@attributes']['targetRef']) {
                                $xml = str_replace($row2['@attributes']['targetRef'], $prevTaskId.'_inclusive', $xml);
                            }
                            if ($row22['@attributes']['id'] === $row2['@attributes']['sourceRef']) {
                                $xml = str_replace($row2['@attributes']['sourceRef'], $prevTaskId.'_inclusive', $xml);
                            }
                        }                     
                    }         
                }         

                if ($gatewayParallel) {
                    foreach ($gatewayParallel as $skey => $row22) {
                        foreach ($sequenceFlow as $tkey2 => $row2) {
                            if ($row2['@attributes']['id'] === $row22['bpmn:incoming']) {
                                $prevTaskId = $row2['@attributes']['sourceRef'];
                            }
                        }    
                        foreach ($sequenceFlow as $skey => $row2) {
                            if ($row22['@attributes']['id'] === $row2['@attributes']['targetRef']) {
                                $xml = str_replace($row2['@attributes']['targetRef'], $prevTaskId.'_parallel', $xml);
                            }
                            if ($row22['@attributes']['id'] === $row2['@attributes']['sourceRef']) {
                                $xml = str_replace($row2['@attributes']['sourceRef'], $prevTaskId.'_parallel', $xml);
                            }
                        }                     
                    }         
                }         

                if ($gatewayExclusive) {
                    foreach ($gatewayExclusive as $skey => $row22) {
                        foreach ($sequenceFlow as $tkey2 => $row2) {
                            if ($row2['@attributes']['id'] === $row22['bpmn:incoming']) {
                                $prevTaskId = $row2['@attributes']['sourceRef'];
                            }
                        }    
                        foreach ($sequenceFlow as $skey => $row2) {
                            if ($row22['@attributes']['id'] === $row2['@attributes']['targetRef']) {
                                $xml = str_replace($row2['@attributes']['targetRef'], $prevTaskId.'_exclusive', $xml);
                            }
                            if ($row22['@attributes']['id'] === $row2['@attributes']['sourceRef']) {
                                $xml = str_replace($row2['@attributes']['sourceRef'], $prevTaskId.'_exclusive', $xml);
                            }
                        }                     
                    }         
                }         

                return compact("sequenceFlow", "task", "gateway", "xml");
            }

            foreach ($sequenceFlow as $skey => $row) {
                foreach ($task as $tkey => $row2) {
                    if ($row2['@attributes']['id'] === $row['@attributes']['targetRef']) {
                        $uid = getUIDAdd($skey);
                        $xml = str_replace($row['@attributes']['targetRef'], $uid, $xml);
                    }
                }
            }            
        }

        return compact("sequenceFlow", "task", "gateway", "xml");
    }

    public function showBpmnModel($mainBpId) {
        return $this->db->GetRow("SELECT META_DATA_ID, META_DATA_CODE, META_DATA_NAME, META_TYPE_ID, ADDON_XML_DATA FROM META_DATA WHERE META_DATA_ID = ".$this->db->Param(0), array($mainBpId));
    }

    public function checkBpModel($idArr) {
        $idStr = join(",", $idArr);

        return $this->db->GetAll("SELECT ID,
            SRC_TABLE_NAME,
            SRC_RECORD_ID,
            TRG_TABLE_NAME,
            TRG_RECORD_ID 
        FROM META_DM_RECORD_MAP 
        WHERE SRC_TABLE_NAME = 'EIS_BPM_PROCESS' 
            AND TRG_TABLE_NAME = 'META_DATA' 
            AND SRC_RECORD_ID IN (".$idStr.")");
    }    

    public function getBpModel($id) {
        $row = $this->db->GetRow("
            SELECT 
                ID,
                SRC_TABLE_NAME,
                SRC_RECORD_ID,
                TRG_TABLE_NAME,
                TRG_RECORD_ID 
            FROM META_DM_RECORD_MAP 
            WHERE SRC_TABLE_NAME = 'EIS_BPM_PROCESS' 
                AND TRG_TABLE_NAME = 'META_DATA' 
                AND SRC_RECORD_ID = ".$id);
        
        $TRG_RECORD_ID = issetParam($row['TRG_RECORD_ID']);

        return $TRG_RECORD_ID ? $TRG_RECORD_ID : $id;
    }       

    public function checkBpIndicatorModel($idArr) {
        $idStr = join(",", $idArr);

        return $this->db->GetAll("SELECT ID,
            SRC_TABLE_NAME,
            SRC_RECORD_ID,
            TRG_TABLE_NAME,
            TRG_RECORD_ID 
        FROM META_DM_RECORD_MAP 
        WHERE SRC_TABLE_NAME = 'KPI_INDICATOR' 
            AND TRG_TABLE_NAME = 'META_DATA' 
            AND SRC_RECORD_ID IN (".$idStr.")");
    }     

    public function getBpIndicatorModel($id) {
        $row = $this->db->GetRow("
            SELECT 
                ID,
                SRC_TABLE_NAME,
                SRC_RECORD_ID,
                TRG_TABLE_NAME,
                TRG_RECORD_ID 
            FROM META_DM_RECORD_MAP 
            WHERE SRC_TABLE_NAME = 'KPI_INDICATOR' 
                AND TRG_TABLE_NAME = 'META_DATA' 
                AND SRC_RECORD_ID = ".$id);
        
        $TRG_RECORD_ID = issetParam($row['TRG_RECORD_ID']);

        return $TRG_RECORD_ID ? $TRG_RECORD_ID : $id;
    }    

    public function saveBpmnModel() {

        $xml = $_POST['xml'];
        $resultXml = self::extractBpmnXml($xml);
        $resultXml = self::extractBpmnXml($resultXml["xml"], true); 
        $resultXml = self::extractBpmnXml($resultXml["xml"], true, true); 
        $mainBpId = Input::post('mainBpId');
        $currentDate = Date::currentDate();
        $sessionUserId = Ue::sessionUserKeyId();
        // pa($resultXml);
        $checkTask = [];
        $checkSeqFlow = [];
        
        try {

            $existStartBehaviour = '0';
            
            foreach ($resultXml["sequenceFlow"] as $row) {
                if (!is_numeric($row['@attributes']["sourceRef"])) {
                    $existStartBehaviour = '1';
                }
            }

            if ($existStartBehaviour == '0') {
                
                return array('status' => 'warning', 'text' => 'Эхлэлийн процесс тодорхойгүй байна.', 'message' => 'Алдаа');
                
            } else {
                
                $idPh = $this->db->Param(0);
                
                $this->db->Execute("DELETE FROM META_PROCESS_WF_BEHAVIOUR WHERE MAIN_BP_ID = $idPh", array($mainBpId));
                $this->db->Execute("DELETE FROM META_PROCESS_WORKFLOW WHERE MAIN_BP_ID = $idPh", array($mainBpId));
                $result = $this->db->UpdateClob('META_DATA', 'ADDON_XML_DATA', $xml, 'META_DATA_ID = ' . $mainBpId);
                
                foreach ($resultXml["task"] as $key => $row) {

                    $data = array(
                        'META_PROCESS_WORKFLOW_ID' => $row["@attributes"]["id"],
                        'MAIN_BP_ID' => $mainBpId,
                        'DO_BP_ID' => Input::param($row['@attributes']["processid"]),
                        'IS_ACTIVE' => 1,
                        'CREATED_DATE' => $currentDate,
                        'CREATED_USER_ID' => $sessionUserId, 
                        'IS_SCHEDULED' => 0,
                        'SCHEDULED_DATE_PATH' => "", 
                        'TASKFLOW_TYPE' => ""
                    );
                    array_push($checkTask, $data);
                    $this->db->AutoExecute('META_PROCESS_WORKFLOW', $data);
                }

                foreach ($resultXml["sequenceFlow"] as $key => $row) {

                    $data = array(
                        'ID' => getUIDAdd($key),
                        'META_PROCESS_WF_ID' => (!is_numeric($row['@attributes']["sourceRef"]) ? null : $row['@attributes']["sourceRef"]),
                        'NEXT_META_PROCESS_WF_ID' => Input::param($row['@attributes']["targetRef"]),
                        'CRITERIA' => issetParam($row['@attributes']["criteria"]),
                        // 'ROLE_ID' => issetDefaultVal($row['roleId'], null),
                        'MAIN_BP_ID' => $mainBpId
                    );

                    if (strpos($row['@attributes']["sourceRef"], '_inclusive') !== false) {
                        $row['@attributes']["sourceRef"] = str_replace('_inclusive','',$row['@attributes']["sourceRef"]);
                        $data['META_PROCESS_WF_ID'] = !is_numeric($row['@attributes']["sourceRef"]) ? null : $row['@attributes']["sourceRef"];
                        $data['GATEWAY_TYPE'] = 'inclusive';
                    } elseif (strpos($row['@attributes']["sourceRef"], '_parallel') !== false) {
                        $row['@attributes']["sourceRef"] = str_replace('_parallel','',$row['@attributes']["sourceRef"]);
                        $data['META_PROCESS_WF_ID'] = !is_numeric($row['@attributes']["sourceRef"]) ? null : $row['@attributes']["sourceRef"];
                        $data['GATEWAY_TYPE'] = 'parallel';
                    } elseif (strpos($row['@attributes']["sourceRef"], '_exclusive') !== false) {
                        $row['@attributes']["sourceRef"] = str_replace('_exclusive','',$row['@attributes']["sourceRef"]);
                        $data['META_PROCESS_WF_ID'] = !is_numeric($row['@attributes']["sourceRef"]) ? null : $row['@attributes']["sourceRef"];
                        $data['GATEWAY_TYPE'] = 'exclusive';
                    }

                    array_push($checkSeqFlow, $data);
                    $this->db->AutoExecute('META_PROCESS_WF_BEHAVIOUR', $data);
                }
                // print_r($checkTask);
                // pa($checkSeqFlow);
                
                (new Mdmeta())->setMetaModifiedDate($mainBpId);
                
                $metaRow = self::getMetaDataModel($mainBpId);
                
                (new Mdmeta())->serverReloadByProcess($metaRow['META_DATA_CODE']);
                
                return array('status' => 'success');
            }
            
        } catch (Exception $ex) {
            return array('status' => 'warning', 'text' => $ex->msg, 'message' => $ex);
        }
    }

    public function saveBpmn2Model() {

        $xml = $_POST['xml'];
        $resultXml = self::extractBpmnXml($xml);
        $resultXml = self::extractBpmnXml($resultXml["xml"], true); 
        $resultXml = self::extractBpmnXml($resultXml["xml"], true, true); 
        $mainBpId = self::getBpModel(Input::post('mainBpId'));
        $currentDate = Date::currentDate();
        $sessionUserId = Ue::sessionUserKeyId();
        $checkTask = [];
        $checkSeqFlow = [];
        
        try {

            $existStartBehaviour = '0';
            
            foreach ($resultXml["sequenceFlow"] as $row) {
                if (!is_numeric($row['@attributes']["sourceRef"])) {
                    $existStartBehaviour = '1';
                }
            }

            if ($existStartBehaviour == '0') {
                
                return array('status' => 'warning', 'text' => 'Эхлэлийн процесс тодорхойгүй байна.', 'message' => 'Алдаа');
                
            } else {
                
                $idPh = $this->db->Param(0);
                
                $this->db->Execute("DELETE FROM META_PROCESS_WF_BEHAVIOUR WHERE MAIN_BP_ID = $idPh", array($mainBpId));
                $this->db->Execute("DELETE FROM META_PROCESS_WORKFLOW WHERE MAIN_BP_ID = $idPh", array($mainBpId));
                $result = $this->db->UpdateClob('META_DATA', 'ADDON_XML_DATA', $xml, 'META_DATA_ID = ' . $mainBpId);
                
                foreach ($resultXml["task"] as $key => $row) {

                    $data = array(
                        'META_PROCESS_WORKFLOW_ID' => $row["@attributes"]["id"],
                        'MAIN_BP_ID' => $mainBpId,
                        'DO_BP_ID' => self::getBpModel(Input::param($row['@attributes']["processid"])),
                        'IS_ACTIVE' => 1,
                        'CREATED_DATE' => $currentDate,
                        'CREATED_USER_ID' => $sessionUserId, 
                        'IS_SCHEDULED' => 0,
                        'SCHEDULED_DATE_PATH' => "", 
                        'TASKFLOW_TYPE' => ""
                    );
                    array_push($checkTask, $data);
                    $this->db->AutoExecute('META_PROCESS_WORKFLOW', $data);

                    if (isset($row["roleid"])) {
                        foreach ($row["roleid"] as $key22 => $row22) {
                            $data = array(
                                'ID' => getUIDAdd($key22),
                                'META_DATA_ID' => self::getBpModel(Input::param($row['@attributes']["processid"])),
                                'ROLE_ID' => $row22,
                                'CREATED_DATE' => $currentDate,
                                'CREATED_USER_ID' => $sessionUserId
                            );
                            array_push($checkTask, $data);
                            $this->db->AutoExecute('META_PROCESS_WORKFLOW_ROLE_MAP', $data);
                        }
                    }
                }

                foreach ($resultXml["sequenceFlow"] as $key => $row) {

                    $data = array(
                        'ID' => getUIDAdd($key),
                        'META_PROCESS_WF_ID' => (!is_numeric($row['@attributes']["sourceRef"]) ? null : $row['@attributes']["sourceRef"]),
                        'NEXT_META_PROCESS_WF_ID' => Input::param($row['@attributes']["targetRef"]),
                        'CRITERIA' => issetParam($row['@attributes']["criteria"]),
                        // 'ROLE_ID' => issetDefaultVal($row['roleId'], null),
                        'MAIN_BP_ID' => $mainBpId
                    );

                    if (strpos($row['@attributes']["sourceRef"], '_inclusive') !== false) {
                        $row['@attributes']["sourceRef"] = str_replace('_inclusive','',$row['@attributes']["sourceRef"]);
                        $data['META_PROCESS_WF_ID'] = !is_numeric($row['@attributes']["sourceRef"]) ? null : $row['@attributes']["sourceRef"];
                        $data['GATEWAY_TYPE'] = 'inclusive';
                    } elseif (strpos($row['@attributes']["sourceRef"], '_parallel') !== false) {
                        $row['@attributes']["sourceRef"] = str_replace('_parallel','',$row['@attributes']["sourceRef"]);
                        $data['META_PROCESS_WF_ID'] = !is_numeric($row['@attributes']["sourceRef"]) ? null : $row['@attributes']["sourceRef"];
                        $data['GATEWAY_TYPE'] = 'parallel';
                    } elseif (strpos($row['@attributes']["sourceRef"], '_exclusive') !== false) {
                        $row['@attributes']["sourceRef"] = str_replace('_exclusive','',$row['@attributes']["sourceRef"]);
                        $data['META_PROCESS_WF_ID'] = !is_numeric($row['@attributes']["sourceRef"]) ? null : $row['@attributes']["sourceRef"];
                        $data['GATEWAY_TYPE'] = 'exclusive';
                    }

                    array_push($checkSeqFlow, $data);
                    $this->db->AutoExecute('META_PROCESS_WF_BEHAVIOUR', $data);
                }
                // print_r($checkTask);
                // pa($checkSeqFlow);
                
                /*(new Mdmeta())->setMetaModifiedDate($mainBpId);
                
                $metaRow = self::getMetaDataModel($mainBpId);
                
                (new Mdmeta())->serverReloadByProcess($metaRow['META_DATA_CODE']);*/
                
                return array('status' => 'success');
            }
            
        } catch (Exception $ex) {
            return array('status' => 'warning', 'text' => $ex->msg, 'message' => $ex);
        }
    }

    public function saveBpmnIndicatorModel() {

        $xml = $_POST['xml'];
        $resultXml = self::extractBpmnXml($xml);
        $resultXml = self::extractBpmnXml($resultXml["xml"], true); 
        $resultXml = self::extractBpmnXml($resultXml["xml"], true, true); 
        $mainBpId = Input::post('mainBpId');
        $currentDate = Date::currentDate();
        $sessionUserId = Ue::sessionUserKeyId();
        $checkTask = [];
        $checkSeqFlow = [];
        
        try {

            $existStartBehaviour = '0';
            
            foreach ($resultXml["sequenceFlow"] as $row) {
                if (!is_numeric($row['@attributes']["sourceRef"])) {
                    $existStartBehaviour = '1';
                }
            }

            if ($existStartBehaviour == '0') {
                
                return array('status' => 'warning', 'text' => 'Эхлэлийн процесс тодорхойгүй байна.', 'message' => 'Алдаа');
                
            } else {
                
                $idPh = $this->db->Param(0);
                
                $this->db->Execute("DELETE FROM META_PROCESS_WF_BEHAVIOUR WHERE MAIN_BP_ID = $idPh", array($mainBpId));
                $this->db->Execute("DELETE FROM META_PROCESS_WORKFLOW WHERE MAIN_BP_ID = $idPh", array($mainBpId));
                // $result = $this->db->UpdateClob('META_DATA', 'ADDON_XML_DATA', $xml, 'META_DATA_ID = ' . $mainBpId);
                
                if (isset($row['@attributes']["processid"])) {
                    foreach ($resultXml["task"] as $key => $row) {

                        $data = array(
                            'META_PROCESS_WORKFLOW_ID' => $row["@attributes"]["id"],
                            'MAIN_BP_ID' => $mainBpId,
                            'DO_BP_ID' => self::getBpIndicatorModel(Input::param($row['@attributes']["processid"])),
                            'IS_ACTIVE' => 1,
                            'CREATED_DATE' => $currentDate,
                            'CREATED_USER_ID' => $sessionUserId, 
                            'IS_SCHEDULED' => 0,
                            'SCHEDULED_DATE_PATH' => "", 
                            'TASKFLOW_TYPE' => ""
                        );
                        array_push($checkTask, $data);
                        $this->db->AutoExecute('META_PROCESS_WORKFLOW', $data);

                        if (isset($row["roleid"])) {
                            foreach ($row["roleid"] as $key22 => $row22) {
                                $data = array(
                                    'ID' => getUIDAdd($key22),
                                    'META_DATA_ID' => self::getBpIndicatorModel(Input::param($row['@attributes']["processid"])),
                                    'ROLE_ID' => $row22,
                                    'CREATED_DATE' => $currentDate,
                                    'CREATED_USER_ID' => $sessionUserId
                                );
                                array_push($checkTask, $data);
                                $this->db->AutoExecute('META_PROCESS_WORKFLOW_ROLE_MAP', $data);
                            }
                        }
                    }

                    foreach ($resultXml["sequenceFlow"] as $key => $row) {

                        $data = array(
                            'ID' => getUIDAdd($key),
                            'META_PROCESS_WF_ID' => (!is_numeric($row['@attributes']["sourceRef"]) ? null : $row['@attributes']["sourceRef"]),
                            'NEXT_META_PROCESS_WF_ID' => !is_numeric(Input::param($row['@attributes']["targetRef"])) ? null : Input::param($row['@attributes']["targetRef"]),
                            'CRITERIA' => issetParam($row['@attributes']["criteria"]),
                            'MAIN_BP_ID' => $mainBpId
                        );

                        if (strpos($row['@attributes']["sourceRef"], '_inclusive') !== false) {
                            $row['@attributes']["sourceRef"] = str_replace('_inclusive','',$row['@attributes']["sourceRef"]);
                            $data['META_PROCESS_WF_ID'] = !is_numeric($row['@attributes']["sourceRef"]) ? null : $row['@attributes']["sourceRef"];
                            $data['GATEWAY_TYPE'] = 'inclusive';
                        } elseif (strpos($row['@attributes']["sourceRef"], '_parallel') !== false) {
                            $row['@attributes']["sourceRef"] = str_replace('_parallel','',$row['@attributes']["sourceRef"]);
                            $data['META_PROCESS_WF_ID'] = !is_numeric($row['@attributes']["sourceRef"]) ? null : $row['@attributes']["sourceRef"];
                            $data['GATEWAY_TYPE'] = 'parallel';
                        } elseif (strpos($row['@attributes']["sourceRef"], '_exclusive') !== false) {
                            $row['@attributes']["sourceRef"] = str_replace('_exclusive','',$row['@attributes']["sourceRef"]);
                            $data['META_PROCESS_WF_ID'] = !is_numeric($row['@attributes']["sourceRef"]) ? null : $row['@attributes']["sourceRef"];
                            $data['GATEWAY_TYPE'] = 'exclusive';
                        }

                        array_push($checkSeqFlow, $data);
                        $this->db->AutoExecute('META_PROCESS_WF_BEHAVIOUR', $data);
                    }
                }
                
                return array('status' => 'success');
            }
            
        } catch (Exception $ex) {
            return array('status' => 'warning', 'text' => $ex->msg, 'message' => $ex);
        }
    }

    public function getMetaDataModel($metaDataId) {
        return $this->db->GetRow("SELECT T0.META_DATA_ID, T0.META_DATA_CODE, T0.META_DATA_NAME, T0.META_TYPE_ID, T1.SUB_TYPE 
            FROM META_DATA T0 
            LEFT JOIN META_BUSINESS_PROCESS_LINK T1 ON T1.META_DATA_ID = T0.META_DATA_ID
            WHERE T0.META_DATA_ID = ".$this->db->Param(0), array($metaDataId)
        );
    }

    public function filterBusinessProcessInfoModel() {

        $this->load->model('mdmetadata', 'middleware/models/');
        $dataViewId = $this->db->GetOne("SELECT META_DATA_ID FROM meta_data where meta_data_code = 'META_BUSINESS_PROCESS_LIST'");

        $param = array(
            'systemMetaGroupId' => $dataViewId,
            'showQuery' => 1, 
            'ignorePermission' => 1
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

    public function getwfmStatusDefaultRuleListModel() {
        return $this->db->GetAll("SELECT * FROM META_WFM_RULE");
    }

    public function iseditStatusUserPermissionModel() {
        $response = array('status' => 'error', 'title' => 'error', 'message' => Lang::line('msg_save_error'));
        try {
            $result = $this->db->AutoExecute("META_WFM_STATUS_PERMISSION", array('IS_EDIT' => Input::post('isEdit')), 'UPDATE', 'ID = '. Input::post('permissionId'));
            if ($result) {
                $response = array('status' => 'success', 'title' => 'success', 'message' => Lang::line('msg_save_success'));
            }
        } catch (Exception $ex) {
            $response = array('status' => 'error', 'title' => 'error', 'message' => Lang::line('msg_save_error'), 'exception' => $ex, 'exceptionmsg' => $ex->msg);
        }
        return $response;
    }

    public function getScheduleDataConfigModel($mainBpId, $doBpId) {
        return $this->db->GetRow("SELECT * FROM META_PROCESS_WORKFLOW WHERE MAIN_BP_ID = $mainBpId AND DO_BP_ID = $doBpId");
    }

    public function savescheduleConfigModel() {
        
        $response = array('status' => 'error', 'message' => Lang::line('msg_save_error'));
        
        try {
            
            $param = array(
                'SCHEDULED_DATE_PATH' => Input::post('scheduleDatePath'), 
                'IS_SCHEDULED' => Input::post('isScheduled')
            );
            
            $result = $this->db->AutoExecute('META_PROCESS_WORKFLOW', $param, 'UPDATE', 'MAIN_BP_ID = ' . Input::post('mainBpId') . ' AND DO_BP_ID = '. Input::post('doProcessId'));
            
            if ($result) {
                $response = array(
                    'status' => 'success', 
                    'message' => Lang::line('msg_save_success'), 
                    'isScheduled' => ($param['IS_SCHEDULED'] ? $param['IS_SCHEDULED'] : ''), 
                    'scheduledDatePath' => ($param['SCHEDULED_DATE_PATH'] ? $param['SCHEDULED_DATE_PATH'] : '')
                );
            }
            
        } catch (Exception $ex) {
            $response = array('status' => 'error', 'message' => Lang::line('msg_save_error'), 'messageexp' => $ex->msg);
        }
        
        return $response;
    }

    public function getexportWorkflowFullModel() {
        set_time_limit(0);
        ini_set('memory_limit', '-1');
        ini_set('default_socket_timeout', 30000);

        if (Input::postCheck('refstructureid') && Input::isEmpty('refstructureid') === false) {
            $refStructureId = Input::post('refstructureid');
            $params = array(
                'refStructureId' => $refStructureId,
                'isUserExport' => Input::post('workflowIsUserExport'),
                'isRoleExport' => Input::post('workflowIsRoleExport'),
                'isNotificationExport' => Input::post('workflowIsNotificationExport'),
            );
            
            $data = $this->ws->runResponse(GF_SERVICE_ADDRESS, 'exportWorkflowFull', $params);

            if ($data['status'] == 'success' && isset($data['result'])) {

                $result = $this->ws->returnValue($data);
                $result['object'] = $refStructureId;

                return $result;  

            } else {
                return array('status' => 'error', 'message' => $this->ws->getResponseMessage($data));
            }
        } 

        return array('status' => 'error', 'message' => 'Дамжуулах параметрын тохиргоо хийгдээгүй байна. (Process Detail -> Post Param)');
    }

    public function exportWorkflowSingleModel() {
        set_time_limit(0);
        ini_set('memory_limit', '-1');
        ini_set('default_socket_timeout', 30000);

        if (Input::postCheck('transitionId') && Input::isEmpty('transitionId') === false) {

            $transitionId = Input::post('transitionId');
            $wfmWorkFlowId = $this->db->GetOne("SELECT WFM_WORKFLOW_ID FROM META_WFM_TRANSITION WHERE ID = $transitionId");
            
            $params = array(
                'sourceTransitionId' => $transitionId,
                'wfmWorkFlowId' => $wfmWorkFlowId,
                'isUserExport' => Input::post('workflowIsUserExport'),
                'isNotificationExport' => Input::post('workflowIsNotificationExport'),
                'isUserExport' => Input::post('workflowIsUserExport'),
                'isRoleExport' => Input::post('workflowIsRoleExport'),
                'isNotificationExport' => Input::post('workflowIsNotificationExport')                
            );            

            $data = $this->ws->runResponse(GF_SERVICE_ADDRESS, 'exportWorkflowSingle', $params);

            if ($data['status'] == 'success' && isset($data['result'])) {

                $result = $this->ws->returnValue($data);
                $result['object'] = $transitionId;

                return $result;  

            } else {
                return array('status' => 'error', 'message' => $this->ws->getResponseMessage($data));
            }
        } 

        return array('status' => 'error', 'message' => 'Дамжуулах параметрын тохиргоо хийгдээгүй байна. (Process Detail -> Post Param)');
    }

    public function importWorkflowModel() {
        set_time_limit(0);
        ini_set('memory_limit', '-1');
        ini_set('default_socket_timeout', 30000);

        $param = array(
            'value' => file_get_contents($_FILES['meta_import_file']['tmp_name'])
        );

        $data = $this->ws->runResponse(GF_SERVICE_ADDRESS, 'importWorkflow', $param);
        
        if ($data['status'] == 'success') {
            return array('status' => 'success', 'message' => Lang::line('msg_save_success'));
        } else {
            return array('status' => 'error', 'message' => $this->ws->getResponseMessage($data));
        }
    }

    public function getWfmStatusLinkDataModel($wfmStatusId) {
        return $this->db->GetRow("SELECT * FROM META_WFM_STATUS_LINK WHERE WFM_STATUS_ID = $wfmStatusId");
    }

    public function getWfmStatusLinkListModel() {
        $response = array();
        $wfmStatusId = Input::post('wfmStatusId');

        $result = $this->db->GetAll("SELECT CRITERIA, DESCRIPTION, WFM_STATUS_ID, ID FROM META_WFM_STATUS_LINK WHERE WFM_STATUS_ID = $wfmStatusId");
        if ($result) {
            $response['total'] = count($result);
            $response['rows'] = $result;
        }

        return $response;
    }

    public function deleteWfmStatusLinkModel() {
        $response = array('status' => 'warning', 'message' => 'Амжилтгүй боллоо');
        $result = $this->db->Execute('DELETE FROM META_WFM_STATUS_LINK WHERE ID = ' . Input::post('statusLinkId'));
        if ($result) {
            $response = array('status' => 'success', 'message' => 'Амжилттай устгагдлаа');
        }
        return $response;
    }

    public function saveMetaStatusLinkDataModel() {
        $response = array('status' => 'warning', 'message' => 'Амжилтгүй боллоо.');
        
        if (Input::postCheck('linkCriteria') && Input::postCheck('linkDescription')) {
            try {
                
                $editId = Input::numeric('id');
                
                if ($editId) {
                    $id = $editId;
                    $ldata = array(
                        'CRITERIA' => Input::post('linkCriteria'), 
                        'DESCRIPTION' => Input::post('linkDescription')
                    );
                    $result = $this->db->AutoExecute('META_WFM_STATUS_LINK', $ldata, 'UPDATE', 'ID = '.$id);
                } else {
                    $id = getUID();
                    $ldata = array(
                        'ID' => $id,
                        'CRITERIA' => Input::post('linkCriteria'), 
                        'DESCRIPTION' => Input::post('linkDescription'),
                        'WFM_STATUS_ID' => Input::post('wfmStatusId')
                    );
                    $result = $this->db->AutoExecute('META_WFM_STATUS_LINK', $ldata);
                }
                
                if ($result) {
                    $response = array('status' => 'success', 'message' => 'Амжилттай хадгаллаа.', 'id' => $id);
                }
            } catch (Exception $ex) {
                $response = array('status' => 'warning', 'message' => 'Амжилтгүй боллоо.', 'ex' => $ex->msg);
            }
        }
        return $response;
    }

    public function insertWfmStatusPackModel() {
        // $currentDate = Date::currentDate();
        // $sessionUserId = Ue::sessionUserKeyId();

        $data = array(
            'ID' => getUID(),
            'IS_PACK' => '1'
        );
        $result = $this->db->AutoExecute('META_WFM_STATUS', $data);        

        if ($result) {
            return $data['ID'];
        }
        return '';
    }

    public function getWorkFlowWfmFieldModel($refStructureId) {
        return $this->db->GetAll("SELECT ID, FIELD_PATH, LABEL_NAME, ".$this->db->IfNull('LOOKUP_META_DATA_ID', 'SELECT_META_DATA_ID')." AS LOOKUP_META_DATA_ID, DESCRIPTION FROM META_WFM_FIELD WHERE REF_STRUCTURE_ID = $refStructureId");
    }    

    public function getTransitionFromWfmId($wfmStatusId) {
        return $this->db->GetRow("".
            "SELECT * FROM (
                SELECT * FROM (
                SELECT
                WT.ID,
                WT.PREV_WFM_STATUS_ID,
                WT.NEXT_WFM_STATUS_ID,
                WT.WFM_WORKFLOW_ID,
                WT.DESCRIPTION,
                WT.IS_TRANSITION,
                LEVEL AS LVL
                FROM META_WFM_TRANSITION WT
                START WITH NEXT_WFM_STATUS_ID=$wfmStatusId
                CONNECT BY NOCYCLE PRIOR PREV_WFM_STATUS_ID=NEXT_WFM_STATUS_ID)
                WHERE PREV_WFM_STATUS_ID IS NULL
                AND IS_TRANSITION = 0
                ORDER BY LVL DESC)
            WHERE ROWNUM=1"
        );
    }    
    
    public function copyWfmWorkFlowTransitionModel() {
        
        $param = array(
            'id' => Input::numeric('transitionId'),
            'description' => Input::post('newName'), 
            'copyWithPermission' => Input::post('isPermission')
        );
        
        $result = $this->ws->runResponse(GF_SERVICE_ADDRESS, 'copy_wfm_transition', $param);

        if ($result['status'] == 'success') {
            $response = array('status' => 'success', 'message' => 'Success');
        } else {
            $response = array('status' => 'error', 'message' => $this->ws->getResponseMessage($result));
        }

        return $response;
    }

    public function saveStartWfmCriteriaModel() {
        
        try {
            loadPhpQuery();
        
            $expressionContent = Input::postNonTags('bpCriteria');
            $expressionContent = str_replace('&nbsp;', '', $expressionContent);
            $wfmStatusId = Input::post('wfmStatusId');
            $workflowId = Input::post('workflowId');
            
            $htmlObj = phpQuery::newDocumentHTML($expressionContent);  
            $matches = $htmlObj->find('span.p-exp-meta:not(:empty)');
            
            if ($matches->length) {
                
                foreach ($matches as $tag) {
                    $metaCode = pq($tag)->attr('data-code');
                    pq($tag)->replaceWith($metaCode);
                }
                
                $expressionContent = $htmlObj->html();
            }        
            $search  = array('\r\n', '\r', '\n', "\r\n", "\r", "\n");
            $replace = array('',     '',   '',   '',     '',   ''); 
                
            $expressionContent = html_entity_decode(trim(str_replace($search, $replace, strip_tags($expressionContent))));     
            $param = array('value' => $expressionContent);        
            
            $checkExpression = $this->ws->runResponse(GF_SERVICE_ADDRESS, 'check_expression', $param);    

            if (isset($checkExpression['status']) && $checkExpression['status'] != 'success') {
                return array('status' => 'error', 'message' => $this->ws->getResponseMessage($checkExpression));
            }
            
            $data = array(
                'ID'                 => getUID(),
                'CRITERIA'            => $expressionContent,
                'DESCRIPTION'         => Input::post('transitionDescription'),
                'NEXT_WFM_STATUS_ID'  => $wfmStatusId,
                'IS_TRANSITION'       => '0',
                'WFM_WORKFLOW_ID'     => $workflowId
            );
            $result = $this->db->AutoExecute('META_WFM_TRANSITION', $data);

            if ($result) {
                return array('status' => 'success', 'message' => $this->lang->line('msg_save_success'), 'data' => $data);
            } else {
                return array('status' => 'error', 'message' => 'Алдаа гарлаа');
            }
            
        } catch (Exception $ex) {
            return array('status' => 'error', 'message' => $ex->getMessage());
        }
    }    
    
    public function lockWfmTransitionModel() {
        
        try {
            
            $transitionId = Input::numeric('transitionId');
            
            if ($transitionId) {
                
                $data = array(
                    'IS_LOCK'        => 1,
                    'LOCKED_USER_ID' => Ue::sessionUserId(),
                    'LOCKED_DATE'    => Date::currentDate()
                );
                $result = $this->db->AutoExecute('META_WFM_TRANSITION', $data, 'UPDATE', 'ID = '.$transitionId);

                if ($result) {
                    
                    $filePath = UPLOADPATH.'process/wfmLockIds.json';
                    $getArr = array();
                    
                    if (file_exists($filePath)) {
                        $getJson = file_get_contents($filePath);
                        $getArr = $getJson ? json_decode($getJson, true) : array();  
                    } 
                    
                    $getArr[$transitionId] = array(
                        'pass' => Hash::createMD5reverse(Input::post('confirmPassword')), 
                        'userId' => $data['LOCKED_USER_ID'], 
                        'date' => $data['LOCKED_DATE'], 
                        'isLock' => 1
                    );
                    
                    file_put_contents($filePath, json_encode($getArr, JSON_UNESCAPED_UNICODE));
                    
                    return array('status' => 'success', 'message' => 'Амжилттай түгжигдлээ.');
                } else {
                    return array('status' => 'error', 'message' => 'Алдаа гарлаа');
                }
                
            } else {
                return array('status' => 'error', 'message' => 'Invalid id!');
            }
            
        } catch (Exception $ex) {
            return array('status' => 'error', 'message' => $ex->getMessage());
        }
    }
    
    public function unlockWfmTransitionModel() {
        
        try {
            
            $transitionId = Input::numeric('transitionId');
            
            if ($transitionId) {
                
                $filePath = UPLOADPATH.'process/wfmLockIds.json';
                $getJson = file_get_contents($filePath);
                $getArr = json_decode($getJson, true);  
                
                if (isset($getArr[$transitionId])) {
                    
                    $row = $getArr[$transitionId];
                    $hash = Hash::createMD5reverse(Input::post('unlockPassword'));
                    
                    if ($row['pass'] == $hash || $hash == 'cf30035cf4943f9fb1a684824df6c9c7') {
                        
                        $data = array(
                            'IS_LOCK'        => 0,
                            'LOCKED_USER_ID' => Ue::sessionUserId(),
                            'LOCKED_DATE'    => Date::currentDate()
                        );
                        
                        $getArr[$transitionId] = array(
                            'pass' => $row['pass'], 
                            'userId' => $data['LOCKED_USER_ID'], 
                            'date' => $data['LOCKED_DATE'], 
                            'isLock' => 0
                        );
                    
                        file_put_contents($filePath, json_encode($getArr, JSON_UNESCAPED_UNICODE));
                        
                        $this->db->AutoExecute('META_WFM_TRANSITION', $data, 'UPDATE', 'ID = '.$transitionId);
                        
                        return array('status' => 'success', 'message' => 'Түгжээг амжилттай цуцаллаа.');
                    } else {
                        return array('status' => 'error', 'message' => 'Нууг үг буруу байна!');
                    }
                    
                } else {
                    return array('status' => 'error', 'message' => 'Тохиргоо олдсонгүй!');
                }
                
            } else {
                return array('status' => 'error', 'message' => 'Invalid id!');
            }
            
        } catch (Exception $ex) {
            return array('status' => 'error', 'message' => $ex->getMessage());
        }
    }
    
    public function checkWfmLockModel($transitionId) {
        
        $filePath = UPLOADPATH.'process/wfmLockIds.json';
        
        if (file_exists($filePath)) {
            
            $getJson = file_get_contents($filePath);
            $getArr = json_decode($getJson, true);  
            
            if (isset($getArr[$transitionId]) && $getArr[$transitionId]['isLock'] == 1) {
                return true;
            }
        }
        
        return false;
    }
    
    public function isWfmLockByStatusIdModel($statusId) {
        
        $filePath = UPLOADPATH.'process/wfmLockIds.json';
        
        if (file_exists($filePath)) {
            
            $ids = array();
            
            if (DB_DRIVER == 'oci8') {
                
                $ids = $this->db->GetAll("
                    SELECT 
                        DISTINCT ID 
                    FROM (
                        SELECT 
                            WT.ID, 
                            WT.PREV_WFM_STATUS_ID, 
                            WT.IS_TRANSITION 
                        FROM META_WFM_TRANSITION WT 
                        START WITH NEXT_WFM_STATUS_ID = ".$this->db->Param(0)." 
                        CONNECT BY NOCYCLE PRIOR PREV_WFM_STATUS_ID = NEXT_WFM_STATUS_ID 
                    ) 
                    WHERE PREV_WFM_STATUS_ID IS NULL 
                        AND IS_TRANSITION = 0", array($statusId));
            }
            
            $getJson = file_get_contents($filePath);
            $getArr = json_decode($getJson, true);  
            
            foreach ($ids as $row) {
                if (isset($getArr[$row['ID']]) && $getArr[$row['ID']]['isLock'] == 1) {
                    return true;
                }
            }
        }
        
        return false;
    }
    
    public function callTaskFlowModel() {
        
        $param = array();
        $taskFlowCode = Input::post('taskFlowCode');
        $isTaskFlowAutoRun = Input::post('isTaskFlowAutoRun');
        
        if (Input::postCheck('oneSelectedRow')) {
            
            $selectedRow = Input::post('oneSelectedRow');
            
            $param['systemMetaGroupId'] = Input::numeric('dmMetaDataId');
            $rowData = isset($selectedRow[0]) ? $selectedRow[0] : $selectedRow;
            
            $param = array_merge($param, $rowData);
        }

        $result = $this->ws->runSerializeResponse(Mdwebservice::$gfServiceAddress, $taskFlowCode, $param);

        if ($result['status'] == 'success') {
            if ($isTaskFlowAutoRun) {
                return array('status' => 'success', 'result' => $result['result']);
            }
            if (isset($result['result']) && isset($result['result']['_taskflowinfo']['doprocessid'])) {
                $response = array('status' => 'success', 'result' => $result['result']);
            } else {
                $response = array('status' => 'error', 'message' => 'doprocessid талбарын утга олдсонгүй!');
            }
        } else {
            $response = array('status' => 'error', 'message' => $this->ws->getResponseMessage($result));
        }
        
        return $response;
    }
    
    public function changeTaskFlowTypeModel() {
        
        $mainBpId = Input::numeric('mainBpId');
        $doBpId = Input::numeric('doBpId');
        
        if ($mainBpId && $doBpId) {
            
            try {
                
                $type = Input::post('type');
                $this->db->AutoExecute('META_PROCESS_WORKFLOW', array('TASKFLOW_TYPE' => $type), 'UPDATE', "MAIN_BP_ID = $mainBpId AND DO_BP_ID = $doBpId");
                
                $metaRow = self::getMetaDataModel($mainBpId);
                
                (new Mdmeta())->serverReloadByProcess($metaRow['META_DATA_CODE']);
                
                $response = array('status' => 'success', 'message' => 'Амжилттай');
                
            } catch (Exception $ex) {
                $response = array('status' => 'error', 'message' => $ex->getMessage());
            }
            
        } else {
            $response = array('status' => 'error', 'message' => 'Invalid ids!');
        }
        
        return $response;
    }
    
    public function viewTaskFlowLogModel() {
        
        $param = Input::post('rowData');
        $param['systemMetaGroupId'] = Input::numeric('dvId');
        $param['mainBpId'] = Input::numeric('bpId');

        $result = $this->ws->runSerializeResponse(Mdwebservice::$gfServiceAddress, 'getTaskflowUILog', $param);
        
        if ($result['status'] == 'success') {
            
            if (isset($result['result']) && $result['result']) {
                
                $response = array('status' => 'success', 'data' => $result['result']);
                $firstRow = $result['result'][0];
                
                $renderResponse = self::getTaskflowUIResponseModel($firstRow['id']);
                
                if ($renderResponse['status'] == 'success') {
                    $response['firstBpRender'] = self::taskFlowBpRenderModel($firstRow['metadataid'], $renderResponse['data']);
                } else {
                    $response['firstBpRender'] = $renderResponse['message'];
                }
                
            } else {
                $response = array('status' => 'error', 'message' => 'No data!');
            }
        } else {
            $response = array('status' => 'error', 'message' => $this->ws->getResponseMessage($result));
        }
        
        return $response;
    }
    
    public function getTaskflowUIResponseModel($id) {
        
        $result = $this->ws->runSerializeResponse(Mdwebservice::$gfServiceAddress, 'getTaskflowUIResponse', array('id' => $id));
        
        if ($result['status'] == 'success') {
            $firstKey = array_key_first($result['result']);
            if ($firstKey && isset($result['result'][$firstKey]) && $result['result'][$firstKey]) {
                $response = array('status' => 'success', 'data' => $result['result'][$firstKey]);
            } else {
                $response = array('status' => 'error', 'message' => 'No data!');
            }
        } else {
            $response = array('status' => 'error', 'message' => $this->ws->getResponseMessage($result));
        }
        
        return $response;
    }
    
    public function taskFlowBpRenderModel($bpId, $data) {
        loadPhpQuery();
                    
        $_POST['metaDataId'] = $bpId;
        $_POST['fillDataParams'] = $data;
        $_POST['isMain'] = 'true';

        $bpContent = (new Mdwebservice())->callMethodByMeta($bpId, null, true);

        $bpContentHtml = phpQuery::newDocumentHTML($bpContent);
        $bpContentHtml->find('.bp-btn-back, .bp-btn-saveadd, .bp-btn-saveedit, .bp-btn-save, .bp-btn-quickmenu, .bp-btn-fieldclean, .dv-right-tools-btn, .meta-toolbar')->remove();

        return $bpContentHtml->html();
    }

    public function deleteAllArrowBpModel() {
        $this->db->Execute('DELETE FROM META_PROCESS_WF_BEHAVIOUR WHERE META_PROCESS_WF_ID IS NOT NULL AND MAIN_BP_ID = ' . Input::post('mainBpId'));
    }

    public function allRoleByMetaIdModel() {
        $idPh = $this->db->Param(0);
        
        $metaData = $this->db->GetAll("
            SELECT 
                MD.META_DATA_ID,
                MD.ROLE_ID,
                MD.ORDER_NUM,
                UR.ROLE_NAME,
                UR.ROLE_CODE
            FROM META_PROCESS_WORKFLOW_ROLE MD
                INNER JOIN UM_ROLE UR ON MD.ROLE_ID = UR.ROLE_ID
            WHERE MD.META_DATA_ID = $idPh", array(Input::post('mainBpId')));

        return $metaData;
    }

    public function allRoleByMetaId2Model() {
        $idPh = $this->db->Param(0);
        $idPh2 = self::getBpModel(Input::post('mainBpId'));
        
        $metaData = $this->db->GetAll("
            SELECT 
                MD.META_DATA_ID,
                MD.ROLE_ID,
                MD.ORDER_NUM,
                UR.ROLE_NAME,
                UR.ROLE_CODE
            FROM META_PROCESS_WORKFLOW_ROLE MD
                INNER JOIN UM_ROLE UR ON MD.ROLE_ID = UR.ROLE_ID
            WHERE MD.META_DATA_ID = $idPh", array($idPh2));

        return $metaData;
    }

    public function getTaskFlowTypeModel() {
        $idPh = $this->db->Param(0);
        $idPh2 = Input::post('mainBpId');
        
        $metaData = $this->db->GetRow("SELECT TASKFLOW_TYPE FROM META_PROCESS_WORKFLOW WHERE MAIN_BP_ID = $idPh AND DO_BP_ID = $idPh", array($idPh2));

        return $metaData;
    }
    
    public function getProcessflowDescriptionModel() {
        $idPh1 = $this->db->Param(0);
        $idPh2 = $this->db->Param(1);
        
        $mainBpId = Input::numeric('mainBpId');
        $doBpId = Input::numeric('doBpId');
        
        $descr = $this->db->GetOne("SELECT DESCRIPTION FROM META_PROCESS_WORKFLOW WHERE MAIN_BP_ID = $idPh1 AND DO_BP_ID = $idPh2", [$mainBpId, $doBpId]);

        return ['description' => $descr];
    }
    
    public function updateProcessflowDescriptionModel() {
        try {
            
            $mainBpId = Input::numeric('mainBpId');
            $doBpId = Input::numeric('doBpId');
            $description = Input::post('description');
        
            $this->db->AutoExecute('META_PROCESS_WORKFLOW', ['DESCRIPTION' => $description], 'UPDATE', "MAIN_BP_ID = $mainBpId AND DO_BP_ID = $doBpId");
            $result = ['status' => 'success'];
            
        } catch (Exception $ex) {
            $result = ['status' => 'error', 'message' => $ex->getMessage()];
        }
        return $result;
    }
    
}
