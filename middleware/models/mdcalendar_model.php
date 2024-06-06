<?php if (!defined('_VALID_PHP')) exit('Direct access to this location is not allowed.');

class Mdcalendar_Model extends Model {

    private static $gfServiceAddress = GF_SERVICE_ADDRESS;

    public function __construct() {
        parent::__construct();
    }

    public function getCalendarLinkDataModel($metaDataId) {

        $result = $this->db->GetRow("
            SELECT
                MCL.ID,
                MCL.TITLE,
                MCL.WIDTH,
                MCL.HEIGHT,
                MCL.TEXT_FONT_SIZE,
                MCL.COLUMN_PARAM_PATH,
                MCL.START_PARAM_PATH,
                MCL.END_PARAM_PATH,
                MCL.COLOR_PARAM_PATH,
                MCL.FILTER_GROUP_PARAM_PATH,
                MCL.DEFAULT_INTERVAL_ID,
                MD.META_DATA_NAME,
                TMD.META_DATA_ID   AS TARGET_META_DATA_ID,
                TMD.META_DATA_NAME AS TARGET_META_DATA_NAME,
                LMD.META_DATA_ID   AS LINK_META_DATA_ID,
                LMD.META_DATA_NAME AS LINK_META_DATA_NAME,
                RTI.CODE DEFAULT_VIEW,
                (
                    SELECT 
                        MD.META_DATA_NAME
                    FROM META_GROUP_CONFIG MGC
                        INNER JOIN META_DATA MD ON MGC.TRG_META_DATA_ID = MD.META_DATA_ID
                    WHERE 
                        MGC.GROUP_META_DATA_ID = TMD.META_DATA_ID
                        AND MGC.MAIN_META_DATA_ID = TMD.META_DATA_ID
                        AND LOWER(MD.META_DATA_CODE) = MCL.FILTER_GROUP_PARAM_PATH
                ) AS FILTER_GROUP_PARAM_NAME
            FROM META_CALENDAR_LINK MCL 
                INNER JOIN META_DATA MD ON MCL.META_DATA_ID = MD.META_DATA_ID
                INNER JOIN META_DATA TMD ON MCL.TRG_META_DATA_ID = TMD.META_DATA_ID
                LEFT JOIN META_DATA LMD ON MCL.LINK_META_DATA_ID = LMD.META_DATA_ID
                LEFT JOIN REF_TIME_INTERVAL RTI ON MCL.DEFAULT_INTERVAL_ID = RTI.ID 
            WHERE MCL.META_DATA_ID = $metaDataId");

        return $result;
    }

    public function checkCalendarSeeModel($metaDataId) {

        $result = $this->db->GetRow("
            SELECT
                COUNT(1) AS COUNT,
                MCL.META_DATA_ID
            FROM META_CALENDAR_LINK MCL
                INNER JOIN META_DATA MD ON MCL.META_DATA_ID = MD.META_DATA_ID
            WHERE MCL.TRG_META_DATA_ID = $metaDataId 
            GROUP BY MCL.META_DATA_ID");

        if ($result) {
            $response = array(
                'isCalendarSee' => true,
                'calendarMetaDataId' => $result['META_DATA_ID']
            );
        } else {
            $response = array(
                'isCalendarSee' => false,
                'calendarMetaDataId' => null
            );
        }

        return $response;
    }

    public function getCalendarEventsModel() {
        
        $targetMetaDataId = Input::post('targetMetaDataId');
        $startDate = date('Y-m-d h:m:s', Input::post('start'));
        $endDate = date('Y-m-d h:m:s', Input::post('end'));
        $startParamPath = Input::post('startParamPath');
        $endParamPath = Input::post('endParamPath');
        
        $param = array(
            'systemMetaGroupId' => $targetMetaDataId,
            'showQuery' => 0,
            'criteria' => array(),
        );

        $param['criteria'] = array(
            $startParamPath => array(
                array(
                    'operator' => '>',
                    'operand' => $startDate
                )
            ),
            $endParamPath => array(
                array(
                    'operator' => '<',
                    'operand' => $endDate
                )
            ),
        );

        $dataViewValue = $this->ws->runSerializeResponse(self::$gfServiceAddress, Mddatamodel::$getDataViewCommand, $param);

        if (isset($dataViewValue['result'])) {
            unset($dataViewValue['result']['aggregatecolumns']);
            unset($dataViewValue['result']['paging']);
            return $dataViewValue['result'];
        } else {
            return array();
        }
    }

    public function getRefTimeIntervalListModel() {
        $result = $this->db->GetAll("SELECT ID, NAME FROM REF_TIME_INTERVAL");
        return $result;
    }

}
