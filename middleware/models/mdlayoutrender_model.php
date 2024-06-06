<?php if (!defined('_VALID_PHP')) exit('Direct access to this location is not allowed.');

if (class_exists('mdlayoutrender_model') != true) {
    class Mdlayoutrender_model extends Model {

        public function __construct() {
            parent::__construct();
        }
        public function getLayoutLinkModel($metaDataId) {
            $metaDataId = Input::param($metaDataId);
            $result = $this->db->GetRow("
                SELECT 
                    MLL.ID,
                    MLL.META_DATA_ID,
                    MLL.THEME_CODE,
                    MD.META_DATA_NAME,
                    MLL.IS_HIDE_BUTTON,
                    MLL.CRITERIA_DATA_VIEW_ID,
                    MLL.USE_BORDER,
                    MLL.REFRESH_TIMER
                FROM 
                    META_LAYOUT_LINK MLL
                INNER JOIN META_DATA MD ON 
                    MLL.META_DATA_ID = MD.META_DATA_ID
                WHERE 
                    MLL.META_DATA_ID = $metaDataId");
            if ($result) {
                return $result;
            }
            return false;
        }

        public function getLayoutParamMapModel($metaLayoutLinkId) {
            $metaLayoutLinkId = Input::param($metaLayoutLinkId);
            $result = $this->db->GetAll("
                SELECT 
                    MLPM.ID,
                    MLPM.LAYOUT_PATH,
                    " . $this->db->IfNull('MLPM.WIDGET_META_DATA_ID', 'MLPM.BP_META_DATA_ID') . " AS BP_META_DATA_ID,
                    MLPM.ORDER_NUM,
                    MLPM.WIDGET_META_DATA_ID,
                    MD.META_TYPE_ID,
                    MD.META_DATA_CODE,
                    MD.META_DATA_NAME
                FROM 
                    META_LAYOUT_PARAM_MAP MLPM
                LEFT JOIN META_DATA MD ON " . $this->db->IfNull('MLPM.WIDGET_META_DATA_ID', 'MLPM.BP_META_DATA_ID') . " = MD.META_DATA_ID
                WHERE 
                    MLPM.META_LAYOUT_LINK_ID = $metaLayoutLinkId 
                ORDER BY MLPM.ORDER_NUM ASC");
            if ($result) {
                return $result;
            }
            return false;
        }

        public function getLayoutNoAsParamMapModel($metaLayoutLinkId) {
            $metaLayoutLinkId = Input::param($metaLayoutLinkId);
            $result = $this->db->GetAll("
                SELECT 
                    MLPM.ID,
                    MLPM.LAYOUT_PATH,
                    MLPM.BP_META_DATA_ID,
                    MLPM.ORDER_NUM,
                    MLPM.WIDGET_META_DATA_ID, 
                    MD.META_TYPE_ID, 
                    MD.META_DATA_CODE, 
                    MD.META_DATA_NAME 
                FROM META_LAYOUT_PARAM_MAP MLPM 
                    LEFT JOIN META_DATA MD ON MLPM.BP_META_DATA_ID = MD.META_DATA_ID 
                WHERE MLPM.META_LAYOUT_LINK_ID = ".$this->db->Param(0)." 
                ORDER BY MLPM.ORDER_NUM ASC", array($metaLayoutLinkId));
            
            if ($result) {
                return $result;
            }
            return false;
        }

        public function getLayoutParamMapControlModel($metaLayoutLinkId) {

            $paramMap = self::getLayoutNoAsParamMapModel($metaLayoutLinkId);
            $html = '';
            $i = 1;

            if ($paramMap) {

                foreach ($paramMap as $k => $row) {

                    $layPath = explode('-', $row['LAYOUT_PATH']);

                    $html .= '<tr>';
                        $html .= '<td style="width: 100px" class="left-padding">Position ' . $layPath[2] . ':</td>';
                        $html .= '<td class="layoutPathCell" data-position="' . $row['ORDER_NUM'] . '">';
                        $html .= '<div class="meta-autocomplete-wrap" data-params="autoSearch=1&grouptype=dataview&metaTypeId=' . Mdmetadata::$metaGroupMetaTypeId . '|' . Mdmetadata::$cardMetaTypeId . '|' . Mdmetadata::$diagramMetaTypeId . '|' . Mdmetadata::$googleMapMetaTypeId . '|' . Mdmetadata::$calendarMetaTypeId. '|' . Mdmetadata::$packageMetaTypeId.'">
                                <div class="input-group double-between-input">
                                    '.Form::hidden(array('name' => 'bpMetaDataId[]', 'value' => $row['BP_META_DATA_ID'])).'
                                    <input id="_displayField" class="form-control form-control-sm md-code-autocomplete" placeholder="'.$this->lang->line('META_00068').'" value="'.$row['META_DATA_CODE'].'" title="'.$row['META_DATA_CODE'].'" type="text">
                                    <span class="input-group-btn">
                                        <button type="button" class="btn default btn-bordered form-control-sm mr0" onclick="commonMetaDataSelectableGrid(\'single\', \'\', this);"><i class="fa fa-search"></i></button>
                                    </span>
                                    <span class="input-group-btn not-group-btn">
                                        <div class="btn-group pf-meta-manage-dropdown">
                                            <button class="btn grey-cascade btn-bordered form-control-sm mr0 dropdown-toggle" type="button" data-toggle="dropdown"></button>
                                            <ul class="dropdown-menu dropdown-menu-right" style="min-width: 126px;" role="menu"></ul>
                                        </div>
                                    </span>
                                    <span class="input-group-btn flex-col-group-btn">
                                        <input id="_nameField" class="form-control form-control-sm md-name-autocomplete" value="'.$row['META_DATA_NAME'].'" title="'.$row['META_DATA_NAME'].'" placeholder="'.$this->lang->line('META_00099').'" type="text">
                                    </span>
                                </div>
                            </div>';
                            $html .= '<input type="hidden" name="layoutPath[]" value="' . $row['LAYOUT_PATH'] . '">';
                            $html .= '<input type="hidden" name="orderNum[]" value="' . $layPath[2] . '">';
                            $html .= '<input type="hidden" name="layoutLinkParamMapId[]" value="' . $row['ID'] . '">';
                        $html .= '</td>';
                        $html .= '<td style="width: 40px; text-align: right">';
                            $html .= '<a href="javascript:;" class="btn btn-sm default ml0" onclick="removeLayoutLinkParamMap(this);"><i class="icon-cross2 font-size-12"></i></a>';
                        $html .= '</td>';
                    $html .= '</tr>';
                    $i++;
                }
            }
            
            return $html;
        }

        public function getMetaWidgetLinkParams() {
            $tmpMetaDataId = Input::numeric('metaDataId');
            $result = $this->db->GetAll("
                SELECT 
                    MWL.LIST_META_DATA_ID,
                    MWL.SUBTYPE,
                    MWL.COLOR_CODE,
                    MWL.ROW_COUNT,
                    MWP.LIST_PARAM,
                    MWP.WIDGET_PARAM
                FROM 
                    META_WIDGET_LINK MWL
                INNER JOIN META_WIDGET_PARAM MWP ON MWL.ID = MWP.WIDGET_LINK_ID
                INNER JOIN META_DATA MD ON MWL.META_DATA_ID = MD.META_DATA_ID
                WHERE 
                    MD.META_DATA_ID = $tmpMetaDataId 
                ORDER BY MWP.ID");

            return $result;
        }

        public function getDataViewColumnNameList($metaDataId) {
            $result = $this->db->GetAll("
                SELECT 
                    MD.META_DATA_CODE,
                    MD.META_DATA_NAME
                FROM 
                    META_META_MAP MMP
                INNER JOIN META_DATA MD ON MMP.TRG_META_DATA_ID = MD.META_DATA_ID
                WHERE 
                    MMP.SRC_META_DATA_ID = $metaDataId 
                ORDER BY MMP.ID");

            return $result;
        }

        public function getMetaWidgetLinkModel($metaDataId) {
            $result = $this->db->GetRow("
                SELECT 
                    ID,
                    SUBTYPE,
                    LIST_META_DATA_ID,
                    ROW_COUNT
                FROM META_WIDGET_LINK 
                WHERE META_DATA_ID=$metaDataId");
            return $result;
        }

        public function getMetaWidgetParamModel($widgetLinkId, $listMetaDataId) {
            $html = '';
            $result = $this->db->GetCol("
                SELECT 
                    LIST_PARAM
                FROM 
                    META_WIDGET_PARAM 
                WHERE 
                    WIDGET_LINK_ID = $widgetLinkId 
                ORDER BY WIDGET_PARAM");

            if ($result) {
                $responseColumnNameList = $this->getDataViewColumnNameList($listMetaDataId);
                foreach ($result as $key => $row) {
                    $html .= '<tr>'
                            . '<td class="left-padding first">Parameter ' . ($key + 1) . ':</td>'
                            . '<td>'
                            . '<select name="listParamName[]" class="form-control select2 select2me" style="width: 100% !important;">'
                            . '<option value="">-Сонгох-</option>';
                    foreach ($responseColumnNameList as $column) {
                        if ($column['META_DATA_CODE'] == $row) {
                            $html.= '<option value="' . $row . '" selected="">' . $row . '</option>';
                        } else {
                            $html.= '<option value="' . $column['META_DATA_CODE'] . '">' . $column['META_DATA_CODE'] . '</option>';
                        }
                    }
                    $html.= '</select>'
                            . '</td>'
                            . '</tr>';
                }
            }

            return $html;
        }

        public function getSingleLayoutParamMapModel($param) {
            $result = $this->db->GetRow("
                SELECT 
                    ID,
                    LAYOUT_PATH,
                    BP_META_DATA_ID,
                    META_LAYOUT_LINK_ID,
                    ORDER_NUM,
                    LABEL_NAME,
                    BACKGROUND_COLOR,
                    FONT_COLOR,
                    WIDGET_META_DATA_ID
                FROM 
                    META_LAYOUT_PARAM_MAP
                WHERE 
                    ID = " . $param['paramMapId']);

            if ($result) {
                return $result;
            }
            return false;
        }    

        public function layoutParamConfigModel($id) {

            $data = $this->db->GetAll("
                SELECT 
                    ID,
                    LAYOUT_PARAM_MAP_ID,
                    WIDGET_PARAM_NAME,
                    META_PARAM_NAME,
                    DEFAULT_VALUE
                FROM META_LAYOUT_PARAM_CONFIG 
                WHERE LAYOUT_PARAM_MAP_ID = ".$id);

            return $data;
        }    

        public function fncRunDataview($dataviewId, $field = "", $operand = "=", $operator = "", $paramFilter = "", $sortField = 'createddate', $sortK = 'desc', $iscriteriaOnly = "0") {
            if ($iscriteriaOnly) {
                $criteria = $paramFilter;
            } else {
                $criteria = array(
                                $field => array(
                                    array(
                                        'operator' => $operand,
                                        'operand' => ($operand == 'like') ? '%'.$operator.'%' : $operator
                                    )
                                )
                            );

                if ($paramFilter) {
                    foreach ($paramFilter as $key => $param) {
                        $criteria[$key] = $param;
                    }
                }
            }

            includeLib('Utils/Functions');

            $paging = array();

            $data = Functions::runDataViewWithoutLogin($dataviewId, $criteria, '0', $paging);

            (Array) $response = array();

            if (isset($data['result']) && $data['result']) {
                unset($data['result']['aggregatecolumns']);
                unset($data['result']['paging']);
                $response = $data['result'];
            }

            return $response;
        }
    }
}
