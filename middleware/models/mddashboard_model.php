<?php if (!defined('_VALID_PHP')) exit('Direct access to this location is not allowed.');

class Mddashboard_Model extends Model {

    public function __construct() {
      parent::__construct();
    }

    public function getMetaDiagramLinkModel($metaDataId, $isEndUser = false) {
        
        if (Input::postCheck('checkMeta') && Input::post('checkMeta') !== 'false') {
            
            $dataViewName = $this->db->GetOne("SELECT META_DATA_NAME FROM META_DATA WHERE META_DATA_ID = ".$this->db->Param(0), array($metaDataId));
            
            $row = array(  
                'ID' => '',
                'META_DATA_ID' => '',
                'PROCESS_META_DATA_ID' => $metaDataId,
                'DATA_VIEW_NAME' => $dataViewName,
                'TEXT' => '',
                'IS_SHOW_TITLE' => '',
                'WIDTH' => '',
                'DIAGRAM_TYPE' => '',
                'TEXT' => '',
                'HEIGHT' => '',
                'IS_SHOW_TITLE' => '',
                'IS_VIEW_DATAGRID' => '',
                'TITLE' => '',
                'DESCRIPTION' => '',
                'IS_SHOW_LABEL' => '',
                'IS_SHOW_EXPORT' => '',
                'IS_DATA_LABEL' => '',
                'META_DATA_NAME' => '',
                'LABEL_STEP' => '',
                'META_DATA_NAME' => '',
                'IS_MULTIPLE' => '',
                'META_ICON_NAME' => '',
                'DASHBOARD_TYPE' => '',
                'IS_X_LABEL' => '',
                'IS_Y_LABEL' => '',
                'IS_BACKGROUND' => '',
                'IS_LITTLE' => '',
                'THEME' => '',
                'XAXIS' => '',
                'YAXIS' => '',
                'XAXISGROUP' => '',
                'YAXISGROUP' => '',
                'DIAGRAM_THEME' => '',
                'X_LABEL_ROTATION' => '',
                'IS_MULTIPLE_PROCESS' => '0',
                'PROCESS_META_DATA_ID2' => '0',
                'PROCESS_META_DATA_ID3' => '0',
                'PROCESS_META_DATA_ID4' => '0',
                'DRILLDOWN' => '0',
                'LEGEND_POSITION' => '', 
                'REAL_LEGEND_POSITION' => '', 
                'VALUE_AXIS_TITLE' => '', 
                'LABEL_TEXT_SUBSTR' => '10000', 
                'COLOR' => '', 
                'COLOR2' => '', 
                'IS_INLINE_LEGEND' => '', 
                'LEGEND_FORMAT' => '', 
                'MINIMUM_VALUE' => '', 
                'MAXIMUM_VALUE' => '', 
                'COLOR_FIELD' => '', 
                'IS_USE_META' => '', 
                'IS_USE_LEGEND' => '', 
                'IS_USE_CRITERIA' => '', 
                'IS_USE_LIST' => '', 
                'IS_USE_GRAPH' => '', 
                'ADDON_SETTINGS' => '', 
                'TEMPLATE_WIDTH' => '', 
                'CATEGORY_AXIS_TITLE' => ''
            );
            return $row;
        }
        
        $row = $this->db->GetRow("
            SELECT
                MDD.ID,
                MDD.META_DATA_ID,
                MDD.PROCESS_META_DATA_ID,
                MDD.PROCESS_META_DATA_ID2,
                MDD.PROCESS_META_DATA_ID3,
                MDD.PROCESS_META_DATA_ID4,
                MD2.META_DATA_CODE AS DATA_VIEW_CODE,
                MD2.META_DATA_NAME AS DATA_VIEW_NAME,
                MD3.META_DATA_CODE AS DATA_VIEW_CODE2,
                MD3.META_DATA_NAME AS DATA_VIEW_NAME2,
                MD4.META_DATA_CODE AS META_DATA_CODE3,
                MD4.META_DATA_NAME AS DATA_VIEW_NAME3,
                MD5.META_DATA_CODE AS DATA_VIEW_CODE4,
                MD5.META_DATA_NAME AS DATA_VIEW_NAME4,
                MDD.DIAGRAM_TYPE,
                MDD.TEXT,
                MDD.WIDTH,
                MDD.HEIGHT,
                MDD.IS_SHOW_TITLE,
                MDD.IS_VIEW_DATAGRID,                
                MDD.TITLE,
                MD.DESCRIPTION,
                MDD.IS_SHOW_LABEL,
                MDD.IS_SHOW_EXPORT, 
                CASE WHEN MDD.IS_DATA_LABEL = 1 THEN '1' ELSE '0' END IS_DATA_LABEL,
                ".$this->db->IfNull('MDD.LABEL_STEP', '0')." AS LABEL_STEP,
                MDD.IS_MULTIPLE, 
                MD.META_DATA_NAME,
                MI.META_ICON_NAME, 
                MDD.DASHBOARD_TYPE, 
                MDD.IS_X_LABEL, 
                MDD.IS_Y_LABEL, 
                MDD.IS_BACKGROUND,
                MDD.IS_LITTLE,
                MDD.THEME,
                MDD.XAXIS,
                MDD.XAXIS2,
                MDD.XAXIS3,
                MDD.XAXIS4,
                MDD.YAXIS,
                MDD.YAXIS2,
                MDD.YAXIS3,
                MDD.YAXIS4,
                MDD.XAXISGROUP,
                MDD.YAXISGROUP,
                MDD.DIAGRAM_THEME,
                MDD.IS_MULTIPLE_PROCESS,
                MDD.X_LABEL_ROTATION, 
                '0' AS DRILLDOWN,
                ".$this->db->IfNull('MDD.LEGEND_POSITION', '\'bottom\'')." AS LEGEND_POSITION, 
                MDD.LEGEND_POSITION AS REAL_LEGEND_POSITION,    
                MDD.VALUE_AXIS_TITLE,
                ".$this->db->IfNull('MDD.LABEL_TEXT_SUBSTR', 10000)." AS LABEL_TEXT_SUBSTR,
                MDD.CATEGORY_AXIS_TITLE,    
                MDD.COLOR,    
                MDD.COLOR2,
                MDD.IS_INLINE_LEGEND,
                MDD.LEGEND_FORMAT,
                MDD.MINIMUM_VALUE,
                MDD.MAXIMUM_VALUE,
                MDD.COLOR_FIELD,
                MDD.IS_USE_META,
                MDD.IS_USE_LEGEND,
                MDD.IS_USE_CRITERIA,
                MDD.IS_USE_GRAPH,
                MDD.ADDON_SETTINGS,
                MDD.TEMPLATE_WIDTH,
                MDD.IS_USE_LIST
            FROM META_DASHBOARD_LINK MDD 
                INNER JOIN META_DATA MD ON MDD.META_DATA_ID = MD.META_DATA_ID AND MD.IS_ACTIVE = 1 
                LEFT JOIN META_DATA MD2 ON MDD.PROCESS_META_DATA_ID = MD2.META_DATA_ID 
                LEFT JOIN META_DATA MD3 ON MDD.PROCESS_META_DATA_ID2 = MD3.META_DATA_ID 
                LEFT JOIN META_DATA MD4 ON MDD.PROCESS_META_DATA_ID3 = MD4.META_DATA_ID 
                LEFT JOIN META_DATA MD5 ON MDD.PROCESS_META_DATA_ID4 = MD5.META_DATA_ID 
                LEFT JOIN META_DATA_ICON MI ON MI.META_ICON_ID = MD.META_ICON_ID 
            WHERE MDD.META_DATA_ID = ".$this->db->Param(0), array($metaDataId) 
        );
        
        if ($row) {
            
            if ($isEndUser) {
                $row['TITLE'] = Mdcommon::titleReplacerByVar($row['TITLE']);
            }
            
            $check = $this->db->GetRow("
                SELECT 
                    COUNT(DD.LINK_META_DATA_ID) AS COUNTT
                FROM META_GROUP_LINK GL
                    INNER JOIN META_DM_DRILLDOWN_DTL DD ON GL.ID = DD.MAIN_GROUP_LINK_ID
                    INNER JOIN META_DATA DA ON DD.LINK_META_DATA_ID = DA.META_DATA_ID
                    INNER JOIN META_TYPE MT ON DA.META_TYPE_ID = MT.META_TYPE_ID
                    LEFT JOIN META_DASHBOARD_LINK LL ON DA.META_DATA_ID = LL.META_DATA_ID
                WHERE GL.META_DATA_ID = (
                    SELECT 
                        MAX(DL.PROCESS_META_DATA_ID)
                    FROM META_DATA D
                        INNER JOIN META_DASHBOARD_LINK DL ON D.META_DATA_ID = DL.META_DATA_ID
                    WHERE D.META_DATA_ID = ".$this->db->Param(0).")", array($metaDataId));
            
            if ((int) $check['COUNTT'] > 0) {
                $row['DRILLDOWN'] = '1';
            }
            
            return $row; 
            
        } else {
            return null;
        }
    }
    
    public function getMetaDiagramLinkThemeModel($metaDataId) {
      $row = $this->db->GetRow("SELECT DIAGRAM_THEME FROM META_DASHBOARD_LINK WHERE PROCESS_META_DATA_ID = $metaDataId AND DIAGRAM_THEME IS NOT NULL");
      if ($row) {
        return $row; 
      } else {
        return null;
      }
    }

    public function getMetaType($metaDataId) {
        return $this->db->GetOne("SELECT META_TYPE_ID FROM META_DATA WHERE META_DATA_ID = ".$this->db->Param(0), array($metaDataId));
    }
    
    public function getMetaDataName($metaDataId) {
        return $this->db->GetOne("SELECT META_DATA_NAME FROM META_DATA WHERE META_DATA_ID = ".$this->db->Param(0), array($metaDataId));
    }

    public function getDataViewId($metaDataId) {
        return $this->db->GetOne("SELECT PROCESS_META_DATA_ID FROM META_DASHBOARD_LINK WHERE META_DATA_ID = ".$this->db->Param(0), array($metaDataId));
    }
    
    public function getMetaSubDiagramLinkModel($metaDataId, $drillField = '') {
        
        $idPh = $this->db->Param(0);
        
        $row = $this->db->GetRow("
            SELECT
                MDD.ID,
                MDD.META_DATA_ID,
                MDD.PROCESS_META_DATA_ID,
                MDD.DIAGRAM_TYPE,
                MDD.TEXT,
                MDD.WIDTH,
                MDD.HEIGHT,
                MDD.IS_SHOW_TITLE,
                MDD.TITLE,
                MDD.IS_SHOW_LABEL,
                MDD.IS_SHOW_EXPORT, 
                CASE WHEN MDD.IS_DATA_LABEL = 1 THEN '1' ELSE '0' END IS_DATA_LABEL,
                ".$this->db->IfNull('MDD.LABEL_STEP', '0')." AS LABEL_STEP,
                MDD.IS_MULTIPLE, 
                MD.META_DATA_NAME,
                MI.META_ICON_NAME, 
                MDD.DASHBOARD_TYPE, 
                MDD.IS_X_LABEL, 
                MDD.IS_Y_LABEL, 
                MDD.IS_BACKGROUND,
                MDD.IS_LITTLE,
                MDD.THEME,
                MDD.XAXIS,
                MDD.YAXIS,
                MDD.XAXISGROUP,
                MDD.YAXISGROUP,
                MDD.X_LABEL_ROTATION,
                '' AS LINK_META_DATA_ID, 
                '' AS META_TYPE_NAME, 
                '' AS GROUP_TYPE, 
                '' AS DM_DRILLDOWN_DTL_ID, 
                '0' AS IS_SENDMAIL
            FROM META_DASHBOARD_LINK MDD 
                INNER JOIN META_DATA MD ON MDD.META_DATA_ID = MD.META_DATA_ID AND MD.IS_ACTIVE = 1 
                LEFT JOIN META_DATA MD2 ON MDD.PROCESS_META_DATA_ID = MD2.META_DATA_ID 
                LEFT JOIN META_DATA_ICON MI ON MI.META_ICON_ID = MD.META_ICON_ID 
            WHERE MDD.META_DATA_ID = $idPh", array($metaDataId));
      
        if ($row) {
          
            $metaType = $this->db->GetOne("
                SELECT 
                    MT.META_TYPE_CODE
                FROM META_GROUP_LINK GL
                    INNER JOIN META_DM_DRILLDOWN_DTL DD ON GL.ID = DD.MAIN_GROUP_LINK_ID
                    INNER JOIN META_DATA DA ON DD.LINK_META_DATA_ID = DA.META_DATA_ID
                    INNER JOIN META_TYPE MT ON DA.META_TYPE_ID = MT.META_TYPE_ID
                WHERE GL.META_DATA_ID = (
                    SELECT 
                        MAX(DL.PROCESS_META_DATA_ID)
                    FROM META_DATA D
                        INNER JOIN META_DASHBOARD_LINK DL ON D.META_DATA_ID = DL.META_DATA_ID
                    WHERE D.META_DATA_ID = $idPh
                )", array($metaDataId));
        
            switch ($metaType) {
                
                case 'DIAGRAM':
                case 'METAGROUP':
                    
                    $where = '';
                    
                    if ($drillField) {
                        $where = "LOWER(DD.MAIN_GROUP_LINK_PARAM) = '".Str::lower($drillField)."' AND ";
                    }
                    
                    $metaDtl = $this->db->GetRow("
                        SELECT
                            DD.LINK_META_DATA_ID,
                            MT.META_TYPE_CODE,
                            DD.ID, 
                            DD.SHOW_TYPE,
                            DD.CRITERIA,
                            DD.IS_SENDMAIL, 
                            MT.META_TYPE_NAME,
                            ".$this->db->IfNull('GL2.LIST_NAME', 'DA.META_DATA_NAME')." AS LIST_NAME 
                        FROM META_GROUP_LINK GL 
                            INNER JOIN META_DM_DRILLDOWN_DTL DD ON GL.ID = DD.MAIN_GROUP_LINK_ID 
                            INNER JOIN META_DATA DA ON DD.LINK_META_DATA_ID = DA.META_DATA_ID 
                            INNER JOIN META_TYPE MT ON DA.META_TYPE_ID = MT.META_TYPE_ID 
                            LEFT JOIN META_DASHBOARD_LINK LL ON DA.META_DATA_ID = LL.META_DATA_ID 
                            LEFT JOIN META_GROUP_LINK GL2 ON GL2.META_DATA_ID = DD.LINK_META_DATA_ID 
                        WHERE $where 
                            GL.META_DATA_ID = (
                                SELECT 
                                    MAX(DL.PROCESS_META_DATA_ID)
                                FROM META_DATA D
                                    INNER JOIN META_DASHBOARD_LINK DL ON D.META_DATA_ID = DL.META_DATA_ID
                                WHERE D.META_DATA_ID = $idPh 
                            )
                        ", array($metaDataId));

                    if ($metaDtl) {
                        $row['LINK_META_DATA_ID']   = $metaDtl['LINK_META_DATA_ID'];
                        $row['META_TYPE_NAME']      = $metaDtl['META_TYPE_NAME'];
                        $row['LIST_NAME']           = $metaDtl['LIST_NAME'];
                        $row['DM_DRILLDOWN_DTL_ID'] = $metaDtl['ID'];
                        $row['CRITERIA']            = $metaDtl['CRITERIA'];
                        $row['SHOW_TYPE']           = $metaDtl['SHOW_TYPE'];
                        $row['IS_SENDMAIL']         = $metaDtl['IS_SENDMAIL'];
                    }

                break;
            }
        
            return $row; 
        } else {
            return null;
        }
    }
    
    public function buildCriteriaModel($id) {
        return $this->db->GetAll("SELECT TRG_PARAM, SRC_PARAM FROM META_DM_DRILLDOWN_PARAM WHERE DM_DRILLDOWN_DTL_ID = $id");
    }
    
    public function getMetaDataLabelName($id, $trgParam) {
        return $this->db->GetOne("SELECT LABEL_NAME FROM META_GROUP_CONFIG WHERE MAIN_META_DATA_ID = $id AND LOWER(FIELD_PATH) = LOWER('$trgParam')");
    }
    
    public function getDashboardListModel($metaDataId) {
        return $this->db->GetAll(" SELECT 
                                    MD.META_DATA_ID, 
                                    MD.META_DATA_CODE, 
                                    MD.META_DATA_NAME, 
                                    MD.CREATED_DATE, 
                                    MD.META_TYPE_ID,
                                    LOWER(MT.META_TYPE_CODE) AS META_TYPE_CODE, 
                                    BP.FIRST_NAME, 
                                    " . $this->db->IfNull('BP.FIRST_NAME', 'UM.USERNAME') . " AS CREATED_PERSON_NAME, 
                                    UM.USERNAME, 
                                    MDL.DIAGRAM_TYPE,
                                    DI.META_ICON_CODE, 
                                    BL.BOOKMARK_URL, 
                                    BL.TARGET AS BOOKMARK_TARGET, 
                                    RL.REPORT_MODEL_ID 
                                FROM META_DATA MD 
                                    INNER JOIN META_DASHBOARD_LINK MDL ON MD.META_DATA_ID = MDL.META_DATA_ID
                                    INNER JOIN META_GROUP_LINK MGL ON MDL.PROCESS_META_DATA_ID = MGL.META_DATA_ID
                                    INNER JOIN META_TYPE MT ON MT.META_TYPE_ID = MD.META_TYPE_ID 
                                    LEFT JOIN UM_SYSTEM_USER UM ON UM.USER_ID = MD.CREATED_USER_ID 
                                    LEFT JOIN BASE_PERSON BP ON BP.PERSON_ID = UM.PERSON_ID 
                                    LEFT JOIN META_DATA_ICON DI ON DI.META_ICON_ID = MD.META_ICON_ID 
                                    LEFT JOIN META_BOOKMARK_LINKS BL ON BL.META_DATA_ID = MD.META_DATA_ID 
                                    LEFT JOIN META_REPORT_LINK RL ON RL.META_DATA_ID = MD.META_DATA_ID 
                                WHERE  MD.IS_ACTIVE = 1 
                                    AND (MD.IS_AUTO_CREATED = 0 OR MD.IS_AUTO_CREATED IS NULL) 
                                    AND MD.IS_SYSTEM = 1 
                                    AND (MDL.PROCESS_META_DATA_ID = $metaDataId OR REF_META_GROUP_ID = $metaDataId)
                                ORDER BY 
                                    MD.META_DATA_CODE, 
                                    MD.META_DATA_NAME 
                                ASC");
    }
    
    public function getBusinessProcessMetadataModel($metaDataId) {
        $outputMetaDataId = $this->db->GetOne("SELECT OUTPUT_META_DATA_ID FROM META_BUSINESS_PROCESS_LINK WHERE META_DATA_ID = $metaDataId");
        if ($outputMetaDataId) {
            $data = $this->db->GetAll("
                SELECT 
                    GD.SRC_META_DATA_ID, 
                    GD.TRG_META_DATA_ID,  
                    MD.META_DATA_NAME AS LABEL_NAME, 
                    MD.META_DATA_CODE, 
                    MT.META_TYPE_NAME, 
                    '' AS IS_SHOW, 
                    '' AS RECORD_TYPE, 
                    '' AS PARENT_ID, 
                    MD.META_TYPE_ID, 
                    MD.META_DATA_CODE AS FIELD_PATH,   
                    " . $this->db->IfNull('FL.DATA_TYPE', 'MT.META_TYPE_CODE') . " AS META_TYPE_CODE 
                FROM META_META_MAP GD 
                INNER JOIN META_DATA MD ON MD.META_DATA_ID = GD.TRG_META_DATA_ID   
                LEFT JOIN META_TYPE MT ON MT.META_TYPE_ID = MD.META_TYPE_ID 
                LEFT JOIN META_FIELD_LINK FL ON FL.META_DATA_ID = GD.TRG_META_DATA_ID    
                WHERE GD.SRC_META_DATA_ID = $outputMetaDataId 
                AND MD.IS_ACTIVE = 1 
                ORDER BY GD.ORDER_NUM ASC");
            
            return $data;
        } else {
            return array();
        }
    }
    
    public function getMetaDataModel($metaDataId) {
        return $this->db->GetRow("SELECT META_TYPE_ID, META_DATA_CODE FROM META_DATA WHERE META_DATA_ID = $metaDataId");
    }
    
    public function getMetaDataOutputMetaDatIdModel($metaDataId) {
        return $this->db->GetOne("SELECT OUTPUT_META_DATA_ID FROM META_BUSINESS_PROCESS_LINK WHERE META_DATA_ID = $metaDataId");
    }
    
    public function getDashboardParamMapModel($metaDataId, $xaxis, $yaxis, $diagramType, $isMultipleProcess, $processMetaDataId2, $processMetaDataId3, $processMetaDataId4) {
        
        $positionData = array();
        $dataResult2 = array();
        $dataResult3 = array();
        $dataResult4 = array();
        
        $data2 = array();
        $data3 = array();
        $data4 = array();
        
        $metaData = self::getMetaDataModel($metaDataId);
        $outputMetaDataId = self::getMetaDataOutputMetaDatIdModel($metaDataId);
        
        $param = array(
            'systemMetaGroupId' => $metaDataId,
            'showQuery' => 0
        );
        
        if ($metaData['META_TYPE_ID'] === Mdmetadata::$businessProcessMetaTypeId  || Mdmetadata::$expressionMetaTypeId === $metaData['META_TYPE_ID']) {
            
            $param = array(
                'systemMetaGroupId' => $outputMetaDataId,
                'showQuery' => 0
            );
            
            if ($isMultipleProcess === '1') {
                $param['showQuery'] = 1;
                
                if ($processMetaDataId2) {
                    $param2 = array(
                        'systemMetaGroupId' => self::getMetaDataOutputMetaDatIdModel($processMetaDataId2),
                        'showQuery' => 0
                    );
                    $metaDataCode2 = self::getMetaDataModel($processMetaDataId2);
                    $data2 = $this->ws->runResponse(GF_SERVICE_ADDRESS, $metaDataCode2['META_DATA_CODE'], $param2);
                }
                
                if ($processMetaDataId3) {
                    $param3 = array(
                        'systemMetaGroupId' => self::getMetaDataOutputMetaDatIdModel($processMetaDataId3),
                        'showQuery' => 0
                    );
                    $metaDataCode3 = self::getMetaDataModel($processMetaDataId3);
                    $data3 = $this->ws->runResponse(GF_SERVICE_ADDRESS, $metaDataCode3['META_DATA_CODE'], $param3);
                }
                if ($processMetaDataId4) {
                    $param4 = array(
                        'systemMetaGroupId' => self::getMetaDataOutputMetaDatIdModel($processMetaDataId4),
                        'showQuery' => 0
                    );
                    $metaDataCode4 = self::getMetaDataModel($processMetaDataId4);
                    $data4 = $this->ws->runResponse(GF_SERVICE_ADDRESS, $metaDataCode4['META_DATA_CODE'], $param4);
                }
                
                if ($data2 && isset($data2['status']) && $data2['status'] === 'success') {
                    $dataResult2 = $data2['result'][0];
                }
                if ($data3 && isset($data3['status']) && $data3['status'] === 'success') {
                    $dataResult3 = $data3['result'][0];
                }
                if ($data4 && isset($data4['status']) && $data4['status'] === 'success') {
                    $dataResult4 = $data4['result'][0];
                }
                
            }
            $data = $this->ws->runResponse(GF_SERVICE_ADDRESS, $metaData['META_DATA_CODE'], $param);
            
        } else {
            $data = $this->ws->runResponse(GF_SERVICE_ADDRESS, 'PL_MDVIEW_004', $param);
        }
        
        if ($data && isset($data['status']) && $data['status'] === 'success') {
            if ($isMultipleProcess === '1') {
                
                $dataResultArr = array();
                $dataResult = $data['result'][0];
                
                if ($dataResult) {
                    $item = array();
                    foreach ($dataResult as $key => $value) {
                        $item[Str::lower($key)] = $value;
                    }
                    array_push($dataResultArr, $item);
                }
                
                if ($dataResult2) {
                    $item2 = array();
                    foreach ($dataResult2 as $key => $value) {
                        $item2[Str::lower($key)] = $value;
                    }
                    array_push($dataResultArr, $item2);
                }
                
                if ($dataResult3) {
                    $item3 = array();
                    foreach ($dataResult3 as $key => $value) {
                        $item3[Str::lower($key)] = $value;
                    }
                    array_push($dataResultArr, $item3);
                }
                
                if ($dataResult4) {
                    $item4 = array();
                    foreach ($dataResult4 as $key => $value) {
                        $item4[Str::lower($key)] = $value;
                    }
                    array_push($dataResultArr, $item4);
                }
                 
                $data['result'] = $dataResultArr;
            }
            else {
                unset($data['result']['aggregatecolumns']);
                unset($data['result']['paging']);
            }
            $diagramTypeExplode = explode('x', $diagramType);
            $diagramTypeCount = (int) $diagramTypeExplode[0] + (int) $diagramTypeExplode[1];
            
            for ($i = 0; $i < $diagramTypeCount; $i++) {
                if (isset($data['result'][$i])) {
                    switch ($i) {
                        case '0' : 
                            $color = '#578ebe'; break;
                        case '1' : 
                            $color = '#e35b5a'; break;
                        case '2' : 
                            $color = '#44b6ae'; break;
                        case '3' : 
                            $color = '#8775a7'; break;
                    }
                    $html = '<style type="text/css">
                                .card-more {
                                    border-top:1px #CCC;
                                    font-size: 14px; color:#FFF;
                                    font-weight: 400;
                                    padding-top:0px;
                                    text-transform: uppercase;
                                    font-size: 14px;
                                }
                                .text-service {
                                    margin-top:10px;
                                    color:#FFF;
                                    font-weight: 400;
                                    text-transform: uppercase;
                                    font-size: 15px;
                                    padding-left: 10px;
                                }
                            </style>
                            <div class="col-md-12 pl0 pr0">
                                <div class="dashboard-stat blue-madison " style="background-color: '. $color .'; margin-bottom:0;">
                                    <div class="desc text-left pl15 text-service" style="">'. $data['result'][$i][$xaxis] .'</div>
                                    <div class="visual"></div>
                                    <div class="details" style="padding-right: 15px;">
                                        <div class="" style="color:#FFF; padding-top:35px; font-weight: 700; font-size: 50px;"> '. $data['result'][$i][$yaxis] .'</div>
                                    </div>
                                </div>
                            </div>
                            <style type="text/css">
                                .dashboard-stat .details {
                                    right: 9px !important;
                                }
                            </style>';
                }
                else $html = '';
                
                array_push($positionData, $html);
            }
        }
        
        return $positionData;
    }
    
}