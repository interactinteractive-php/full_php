<?php if (!defined('_VALID_PHP')) exit('Direct access to this location is not allowed.');
    
class Mdtimestable_Model extends Model {

    private static $gfServiceAddress = GF_SERVICE_ADDRESS;

    public function __construct() {
        parent::__construct();
    }
    
    public function downloadDataIOServiceModel($startDate = false, $endDate = false, $empIds = '', $isDep, $tmsTemplate) {

        $startDate = $startDate ? $startDate : Date::beforeDate('Y-m-d', '-1 day');
        $endDate = $endDate ? $endDate : Date::currentDate('Y-m-d');

        $param = array(
            'startdate' => $startDate,
            'endDate' => $endDate,
            'check' => $isDep,
            'tmsTemplateId' => $tmsTemplate,
            'ids' => $empIds
        );

        $configWsUrl = Config::getFromCache('heavyServiceAddress');

        if ($configWsUrl && @file_get_contents($configWsUrl, false, stream_context_create(array('http' => array('timeout' => 5))))) {
            $serviceAddress = $configWsUrl;
        } else {
            $serviceAddress = self::$gfServiceAddress;
        }

        $result = $this->ws->runSerializeResponse($serviceAddress, 'calculateTMS', $param);     

        if ($result['status'] === 'error') {
            return array(
                'title' => 'Warning',
                'status' => 'error',
                'message' => $this->ws->getResponseMessage($result)
            );
        } else {
            return array(
                'title' => 'Success',
                'status' => 'success',
                'message' => 'Цаг <strong>АМЖИЛТТАЙ</strong> бодогдлоо.'
            );
        }
    }		    

    public function getDepartmentGroupListModel($param) {
        if ($param) {
            $implodeId = is_array($param) ? implode(',', $param) : $param;

            $query = "SELECT DISTINCT
                    TGI.ID,
                    TGI.NAME AS GROUPNAME
                  FROM TNA_EMPLOYEE_GROUP_CONFIG EGC
                  LEFT JOIN VW_EMPLOYEE EMP ON EMP.EMPLOYEE_ID = EGC.EMPLOYEE_ID
                  INNER JOIN TNA_GROUP_INFO TGI ON TGI.ID = EGC.GROUP_ID
                  LEFT JOIN (
                    SELECT 
                      USR.USER_ID, SUBSTR(BP.LAST_NAME,0,1)|| '.' || BP.FIRST_NAME AS CRNAME
                    FROM UM_USER USR
                    INNER JOIN UM_SYSTEM_USER SSR ON SSR.USER_ID = USR.SYSTEM_USER_ID
                    LEFT JOIN BASE_PERSON BP ON BP.PERSON_ID = SSR.PERSON_ID
                  ) USR ON USR.USER_ID = EGC.CREATED_USER_ID
                  LEFT JOIN (
                    SELECT META_VALUE_ID,
                        META_DATA_ID,
                        META_VALUE_CODE,
                        META_VALUE_NAME
                    FROM META_VALUE
                    WHERE META_DATA_ID = 1464077516807272 ) MV ON TGI.SHIFT_NUMBER = MV.META_VALUE_CODE
                  WHERE EGC.IS_ACTIVE = 1 AND EMP.DEPARTMENT_ID in ($implodeId)";

            return $this->db->GetAll($query);
        }
        return array();
    }      

    public function balanceListMainDataGridV3Model() {

        $page = Input::postCheck('page') ? Input::post('page') : 1;
        $rows = Input::postCheck('rows') ? Input::post('rows') : 10;            
        $offset = ($page - 1) * $rows;
        $statusText = '';
        $result = array();
        $result["rows"]  = array();
        $result['total'] = '0';

        $where = "WHERE 1 = 1 ";
        $whereSql2 = '';
        $joiner = '';

        if (Input::postCheck('params')) {
            parse_str(Input::post('params'), $params);
            $VW_EMPLOYEE = isset($params['viewEmployee']) ? 'VW_TMS_EMPLOYEE' : 'VW_EMPLOYEE';

            if(Config::getFromCache('CONFIG_TNA_HIDENOTPLAN')) {
                $joiner = " INNER JOIN (
                            SELECT
                                EE.EMPLOYEE_ID
                            FROM (
                                SELECT  TO_CHAR(TO_DATE('" . $params['startDate'] . "', 'YYYY-MM-DD') - 1 + LEVEL, 'YYYY-MM-DD') AS ATTENDANCE_DATE
                                FROM DUAL
                                CONNECT BY LEVEL <= TO_DATE('" . $params['endDate'] . "', 'YYYY-MM-DD') - TO_DATE('" . $params['startDate'] . "', 'YYYY-MM-DD') + 1 
                            ) T0
                            INNER JOIN $VW_EMPLOYEE EE ON EE.EMPLOYEE_KEY_ID = EE.EMPLOYEE_KEY_ID AND EE.EMPLOYEE_ID = EE.EMPLOYEE_ID
                            INNER JOIN HRM_EMPLOYEE HE ON EE.EMPLOYEE_ID = HE.EMPLOYEE_ID  
                            LEFT JOIN (
                                SELECT 
                                    EMPLOYEE_ID, 
                                    TO_CHAR(BALANCE_DATE, 'YYYY-MM-DD') AS BALANCE_DATE,
                                    '1' AS ICHECK
                                FROM TNA_TIME_BALANCE_HDR 
                            ) T6 ON T0.ATTENDANCE_DATE = T6.BALANCE_DATE AND EE.EMPLOYEE_ID = T6.EMPLOYEE_ID
                            LEFT JOIN (
                                SELECT
                                    T1.PLAN_ID, T1.PLAN_DATE, T0.EMPLOYEE_ID
                                FROM
                                    TMS_EMPLOYEE_TIME_PLAN_HDR T0
                                INNER JOIN TMS_EMPLOYEE_TIME_PLAN_DTL T1 ON T0.ID = T1.TIME_PLAN_ID
                            ) T5 ON EE.EMPLOYEE_ID = T5.EMPLOYEE_ID AND T0.ATTENDANCE_DATE = TO_CHAR(T5.PLAN_DATE, 'YYYY-MM-DD')
                            LEFT JOIN (SELECT TO_CHAR(ATTENDANCE_DATE_TIME, 'YYYY-MM-DD')      AS ATTENDANCE_DATE,
                                TO_CHAR(ATTENDANCE_DATE_TIME, 'YYYY-MM-DD HH24:MI:SS') AS ATTENDANCE_DATE_TIME,
                                EMPLOYEE_ID
                            FROM TNA_TIME_ATTENDANCE
                            ) T10 ON T0.ATTENDANCE_DATE = T10.ATTENDANCE_DATE AND EE.EMPLOYEE_ID = T10.EMPLOYEE_ID
                            LEFT JOIN (
                                SELECT 
                                    T2.PLAN_DURATION, 
                                    '1' AS DARK_CHECK_TIME,
                                    TO_CHAR(T1.PLAN_DATE, 'YYYY-MM-DD') AS MAIN_DATE, 
                                    TO_CHAR(T1.PLAN_DATE + T2.PLAN_DURATION, 'YYYY-MM-DD') AS PLAN_DATE,
                                    T0.EMPLOYEE_ID
                                    FROM TMS_EMPLOYEE_TIME_PLAN_HDR T0 
                                    INNER JOIN TMS_EMPLOYEE_TIME_PLAN_DTL T1 ON T0.ID = T1.TIME_PLAN_ID
                                    INNER JOIN TMS_TIME_PLAN T2 ON T1.PLAN_ID = T2.PLAN_ID
                            ) T7 ON T0.ATTENDANCE_DATE = T7.PLAN_DATE AND EE.EMPLOYEE_ID = T7.EMPLOYEE_ID
                            WHERE T5.PLAN_ID IS NOT NULL OR T10.ATTENDANCE_DATE IS NOT NULL OR T7.DARK_CHECK_TIME IS NOT NULL
                        ) NOTP ON EE.EMPLOYEE_ID = NOTP.EMPLOYEE_ID ";
            }

            if (!empty($params['startDate']) && !empty($params['endDate'])) {
                $filterString = "";

                // <editor-fold defaultstate="collapsed" desc="Criteria">
                if (!empty($params['filterString'])) {
                    $filterString = " AND (LOWER(bl.FIRST_NAME) LIKE LOWER('%" . $params['filterString'] . "%') OR LOWER(bl.LAST_NAME) LIKE LOWER('%" . $params['filterString'] . "%') OR LOWER(EE.REGISTER_NUMBER) LIKE LOWER('%" . $params['filterString'] . "%') OR LOWER(EE.CODE) LIKE LOWER('%" . $params['filterString'] . "%'))";
                }

                if (!empty($params['departmentId'])) {
                    $departmentIds = $params['departmentId'];

                    if(isset($params['isChild']))
                        $departmentIds = $this->getAllChildDepartmentModel($departmentIds);

                    $where = " WHERE EE.DEPARTMENT_ID IN ( " . $departmentIds . ") ";

                    if (isset($params['groupId']) && is_array($params['groupId'])) {
                            $where .= " AND EE.EMPLOYEE_ID IN ( " . implode(',', $params['groupId']) . ") ";
                    }                        

                } elseif (isset($params['groupId']) && is_array($params['groupId'])) {
                    $where = " WHERE EE.EMPLOYEE_ID IN ( " . implode(',', $params['groupId']) . ") ";

                } else
                    $where = "WHERE 1 <> 1 ";

                $filterRuleString = ' WHERE 1 = 1 ';

                $causeString = '';
                $causeString1 = '';
                $causeString2 = ' AND (';
                $causeString3 = '';

                if (isset($params['causeTypeId'])) {
                    $causeString .= 'AND (';

                    foreach ($params['causeTypeId'] as $k => $row) {
                        if ($row == '0') {
                            break;
                        } elseif ($row == 'inTime') {
                            $causeString .= ($causeString === 'AND (') ? '(AA.START_TIME IS NULL OR SB.IS_FAULT = 1) ' : '';
                        } elseif ($row == 'outTime') {
                            $causeString .= ($causeString === 'AND (') ? '(AA.END_TIME IS NULL OR SB.IS_FAULT = 1) ' : 'OR (AA.END_TIME IS NULL OR SB.IS_FAULT = 1)';
                        } elseif ($row == 'isConfirm') {
                            $filterRuleString .= ' AND AA.IS_CONFIRMED = 1 ';
                        } elseif ($row == 'isUnConfirm') {
                            $filterRuleString .= " AND STATUS = 'Зөрчилтэй' ";
                        } elseif ($row == '1') {
                            $filterRuleString .= " AND STATUS = 'Зөрчилгүй' ";
                        } else {
                            $causeString2 .= ($causeString2 === ' AND (') ? ' (AA.CAUSE'.$row.' IS NOT NULL AND AA.CAUSE'.$row.' <> 0)' : ' OR (AA.CAUSE'.$row.' IS NOT NULL AND AA.CAUSE'.$row.' <> 0)';
                        }
                    }

                    $causeString .= ')';
                    $causeString2 .= ')';
                    if ($causeString === 'AND ()') {
                        $causeString = '';
                    }
                    if ($causeString2 === ' AND ()') {
                        $causeString2 = '';
                    }
                    $causeString1 .= $causeString2;
                }                    

                if (Input::postCheck('filterRules')) {
                    $filterRules = json_decode(Str::cp1251_utf8($_POST['filterRules']), true);

                    if (count($filterRules) > 0) {

                        foreach ($filterRules as $rule) {
                            $ruleValue = Input::param(Str::lower(trim($rule['value'])));
                            switch ($rule['field']) {
                                case 'CLEAR_TIME':
                                case 'DEFFERENCE_TIME':
                                    $ruleValue = explode(':', $ruleValue);
                                    $ruleValue = (float) $ruleValue[0] + (float) $ruleValue[1] / 60;
                                    $filterRuleString .= ' AND ' . $rule['field'] . " = '" . $ruleValue . "'";
                                    break;
                                case 'STATUS_TEXT':
                                    $statusText = $ruleValue;
                                    break;
                                default:
                                    $filterRuleString .= ' AND LOWER(' . $rule['field'] . ") LIKE LOWER('%" . $ruleValue . "%')";
                                    break;
                            }
                        }
                    }
                }

                if (isset($params['stringValue'])) {
                    if (empty($params['stringValue']) === false && $params['stringValue'] != '') {
                        $filterRuleString .= " AND (LOWER(LAST_NAME) LIKE LOWER('%" . $params['stringValue'] . "%') OR LOWER(FIRST_NAME) LIKE LOWER('%" . $params['stringValue'] . "%')) ";
                    }
                }

                $sortField = 'FIRST_NAME, BALANCE_DATE';
                $sortOrder = 'ASC';
                if (Input::postCheck('sort') && Input::postCheck('order')) {
                    $sortField = Input::post('sort');
                    $sortOrder = Input::post('order');
                }

                $whereSql2 = $causeString . $causeString1;
                $whereSql = $where . $filterString;
                // </editor-fold>

                $selectList = " SELECT *
                                FROM (
                                        SELECT 
                                            EMPLOYEE_ID,
                                            EMPLOYEE_KEY_ID ,
                                            PICTURE,
                                            POSITION_NAME,
                                            EMPLOYEE_NAME,
                                            EMPLOYEE_NAME_LONG,
                                            LAST_NAME,
                                            FIRST_NAME,
                                            DEPARTMENT_NAME,
                                            EMPLOYEE_CODE,
                                            STATUS_NAME,
                                            DEPARTMENT_ID,
                                            CASE WHEN TO_CHAR(TO_DATE('" . $params['startDate'] . "', 'YYYY-MM-DD'), 'YYYYMM') = TO_CHAR(TO_DATE('" . $params['endDate'] . "', 'YYYY-MM-DD'), 'YYYYMM')
                                                THEN TO_CHAR(TO_DATE('" . $params['startDate'] . "', 'YYYY-MM-DD'), 'YYYY.MM')
                                                ELSE TO_CHAR(TO_DATE('" . $params['startDate'] . "', 'YYYY-MM-DD'), 'YYYY')||'.['||
                                                    TO_CHAR(TO_DATE('" . $params['startDate'] . "', 'YYYY-MM-DD'), 'MM')||'...'||
                                                    TO_CHAR(TO_DATE('" . $params['endDate'] . "', 'YYYY-MM-DD'), 'MM')||']'
                                            END AS BALANCE_DATE,
                                            CASE WHEN TO_CHAR(TO_DATE('" . $params['startDate'] . "', 'YYYY-MM'), 'YYYYMM') = TO_CHAR(TO_DATE('" . $params['endDate'] . "', 'YYYY-MM-DD'), 'YYYYMM')
                                                THEN TO_CHAR(TO_DATE('" . $params['startDate'] . "', 'YYYY-MM-DD'), 'YYYY/MM')||'-'||TO_CHAR(TO_DATE('" . $params['endDate'] . "', 'YYYY-MM-DD'), 'YYYY/MM')
                                                ELSE TO_CHAR(TO_DATE('" . $params['startDate'] . "', 'YYYY-MM-DD'), 'YYYY/MM')||'-'||TO_CHAR(TO_DATE('" . $params['endDate'] . "', 'YYYY-MM-DD'), 'YYYY/MM') END AS BALANCEDATE,    
                                            SUM(PLAN_TIME) AS PLAN_TIME,
                                            CLEAN_TIME AS CLEAR_TIME,
                                            LATE_TIME,
                                            DEFFERENCE_TIME,
                                            CAUSE4,
                                            CAUSE5,
                                            CAUSE6,
                                            CASE WHEN CAUSE13 IS NULL THEN PCAUSE13 ELSE CAUSE13 END AS CAUSE13,
                                            CAUSE20,                                                
                                            CASE
                                                WHEN (DEFFERENCE_TIME < 0) OR (IS_FAULT = 1) THEN 'Зөрчилтэй'
                                                ELSE 'Зөрчилгүй'
                                            END AS STATUS ,
                                            CASE
                                                WHEN (DEFFERENCE_TIME < 0) OR (IS_FAULT = 1) THEN '#f2dede'
                                                ELSE '#dff0d8'
                                            END AS BACKGROUND_COLOR,
                                            CASE
                                                WHEN (DEFFERENCE_TIME < 0) OR (IS_FAULT = 1) THEN '#F00'
                                                ELSE '#000'
                                            END AS FONT_COLOR,
                                            STATUS_ID
                                          FROM (
                                            SELECT 
                                                DISTINCT 
                                                EE.EMPLOYEE_ID,
                                                EE.CODE AS EMPLOYEE_CODE,
                                                SB.IS_FAULT,
                                                ETP.PLAN_ID,
                                                EE.EMPLOYEE_KEY_ID  AS EMPLOYEE_KEY_ID,
                                                EE.EMPLOYEE_PICTURE AS PICTURE,
                                                EE.POSITION_NAME,
                                                EE.DEPARTMENT_NAME,
                                                SUBSTR(EE.LAST_NAME,1,2)||'.'||EE.FIRST_NAME||' ('||EE.CODE||')' AS EMPLOYEE_NAME,
                                                EE.LAST_NAME||' '||EE.FIRST_NAME||' ('||EE.CODE||')' AS EMPLOYEE_NAME_LONG,
                                                EE.STATUS_NAME,
                                                EE.DEPARTMENT_ID,
                                                EE.FIRST_NAME,
                                                EE.LAST_NAME,
                                                AAA.CLEAN_TIME,
                                                AAA.LATE_TIME,
                                                lj.PLAN_TIME,
                                                BB.WFM_STATUS_NAME,
                                                AAA.DEFFERENCE_TIME,
                                                AAA.CAUSE4,
                                                AAA.CAUSE5,
                                                AAA.CAUSE6,
                                                AAA.CAUSE13,
                                                ETP2.PCAUSE13,
                                                AAA.CAUSE20,
                                                TO_CHAR(AA.BALANCE_DATE, 'MM')    AS BALANCE_DATE,
                                                TO_CHAR(AA.BALANCE_DATE, 'YYYY') AS BALANCE_YEAR,
                                                ek.STATUS_ID,
                                                ek.CURRENT_STATUS_ID
                                            FROM $VW_EMPLOYEE EE
                                            INNER JOIN HRM_EMPLOYEE_KEY ek ON EE.EMPLOYEE_KEY_ID = ek.EMPLOYEE_KEY_ID
                                            LEFT JOIN TNA_TIME_BALANCE_HDR AA ON EE.EMPLOYEE_ID = AA.EMPLOYEE_ID AND AA.BALANCE_DATE >= TO_DATE('" . $params['startDate'] . "', 'YYYY-MM-DD') AND AA.BALANCE_DATE <=  TO_DATE('" . $params['endDate'] . "', 'YYYY-MM-DD')
                                            LEFT JOIN (
                                                SELECT EMPLOYEE_ID,
                                                    SUM(CLEAN_TIME) AS CLEAN_TIME, 
                                                    SUM(LATE_TIME) AS LATE_TIME, 
                                                    SUM(CAUSE5) AS CAUSE5, 
                                                    SUM(CAUSE4) AS CAUSE4, 
                                                    SUM(CAUSE6) AS CAUSE6, 
                                                    SUM(CAUSE13) AS CAUSE13, 
                                                    SUM(CAUSE20) AS CAUSE20, 
                                                    SUM(DEFFERENCE_TIME) AS DEFFERENCE_TIME
                                                FROM TNA_TIME_BALANCE_HDR
                                                WHERE BALANCE_DATE >= TO_DATE('" . $params['startDate'] . "', 'YYYY-MM-DD') AND BALANCE_DATE <=  TO_DATE('" . $params['endDate'] . "', 'YYYY-MM-DD')
                                                GROUP BY EMPLOYEE_ID
                                            ) AAA ON EE.EMPLOYEE_ID = AAA.EMPLOYEE_ID
                                            LEFT JOIN meta_wfm_status BB ON BB.ID = AA.WFM_STATUS_ID
                                            LEFT JOIN (
                                                SELECT 
                                                    T0.EMPLOYEE_ID, 
                                                    T0.EMPLOYEE_KEY_ID, 
                                                    T1.PLAN_DATE, 
                                                    T1.PLAN_ID 
                                                FROM  TMS_EMPLOYEE_TIME_PLAN_HDR T0
                                                INNER JOIN TMS_EMPLOYEE_TIME_PLAN_DTL T1 ON T0.ID = T1.TIME_PLAN_ID
                                            ) ETP ON AA.EMPLOYEE_ID = ETP.EMPLOYEE_ID  AND TO_CHAR(AA.BALANCE_DATE, 'yyyyMMdd') = TO_CHAR(ETP.PLAN_DATE, 'yyyyMMdd')
                                            LEFT JOIN (
                                                SELECT 
                                                    T0.EMPLOYEE_ID,
                                                    SUM(FNC_GET_TMS_PLAN_TIME(T1.PLAN_ID)) AS PCAUSE13
                                                FROM  TMS_EMPLOYEE_TIME_PLAN_HDR T0
                                                INNER JOIN TMS_EMPLOYEE_TIME_PLAN_DTL T1 ON T0.ID = T1.TIME_PLAN_ID
                                                WHERE TRUNC(T1.PLAN_DATE) >= TO_DATE('" . $params['startDate'] . "', 'YYYY-MM-DD') AND TRUNC(T1.PLAN_DATE) <= (CASE WHEN TO_DATE('" . $params['endDate'] . "', 'YYYY-MM-DD') <= TRUNC(SYSDATE) THEN TO_DATE('" . $params['endDate'] . "', 'YYYY-MM-DD') ELSE TRUNC(SYSDATE - 1) END)
                                                GROUP BY T0.EMPLOYEE_ID
                                            ) ETP2 ON EE.EMPLOYEE_ID = ETP2.EMPLOYEE_ID                                                    
                                            LEFT JOIN (
                                                  SELECT 
                                                      ROUND(FNC_GET_TMS_PLAN_TIME(TTP.PLAN_ID)/60, 2) AS PLAN_TIME,
                                                      TTP.PLAN_ID,
                                                      TTP.CODE,
                                                      TTP.NAME AS TYPE_NAME,
                                                      TTPDTL.START_TIME,
                                                      TTPDTL.END_TIME
                                                  FROM TMS_TIME_PLAN TTP
                                                  INNER JOIN TMS_TIME_PLAN_DEPARTMENT TTPD ON TTPD.PLAN_ID = TTP.PLAN_ID
                                                  INNER JOIN (
                                                      SELECT 
                                                          PLAN_ID,
                                                          MIN(START_TIME) AS START_TIME ,
                                                          MAX(END_TIME)   AS END_TIME
                                                      FROM (
                                                          SELECT 
                                                              PLAN_ID,
                                                              PLAN_DETAIL_ID,
                                                              CASE
                                                                  WHEN TO_CHAR(START_TIME, 'HH24') = '00'
                                                                  THEN '24:' ||TO_CHAR(START_TIME, 'MI')
                                                                  ELSE TO_CHAR(START_TIME, 'HH24:MI')
                                                              END AS START_TIME,
                                                              TO_CHAR(END_TIME, 'HH24:MI') AS END_TIME
                                                          FROM TMS_TIME_PLAN_DETAIL
                                                      ) GROUP BY PLAN_ID
                                                  ) TTPDTL ON TTP.PLAN_ID = TTPDTL.PLAN_ID
                                            ) lj ON ETP.PLAN_ID = lj.PLAN_ID
                                            $joiner
                                            LEFT JOIN (
                                                SELECT DISTINCT EE.EMPLOYEE_ID AS EMPLOYEE_ID,
                                                EE.CODE AS EMPLOYEE_CODE,
                                                1 AS IS_FAULT,
                                                EE.EMPLOYEE_KEY_ID AS EMPLOYEE_KEY_ID,
                                                EE.EMPLOYEE_PICTURE AS PICTURE,
                                                EE.POSITION_NAME,
                                                EE.DEPARTMENT_NAME,
                                                SUBSTR(EE.LAST_NAME,1,2)||'.'||EE.FIRST_NAME||' ('||EE.CODE||')' AS EMPLOYEE_NAME,
                                                EE.LAST_NAME||' '||EE.FIRST_NAME||' ('||EE.CODE||')' AS EMPLOYEE_NAME_LONG,
                                                EE.STATUS_NAME,
                                                EE.DEPARTMENT_ID,
                                                EE.FIRST_NAME,
                                                EE.LAST_NAME,
                                                0 AS CLEAN_TIME,
                                                0 AS LATE_TIME,
                                                null AS CAUSE4,
                                                null AS CAUSE5,
                                                null AS CAUSE6,
                                                null AS CAUSE13,
                                                null AS CAUSE20,                                                            
                                                0 AS PLAN_TIME,
                                                null AS WFM_STATUS_NAME, 
                                                0 AS DEFFERENCE_TIME,
                                                TO_CHAR(TO_DATE(T0.ATTENDANCE_DATE, 'YYYY-MM-DD'), 'MM')   AS BALANCE_DATE,
                                                TO_CHAR(TO_DATE(T0.ATTENDANCE_DATE, 'YYYY-MM-DD'), 'YYYY') AS BALANCE_YEAR                                                       
                                              FROM (
                                                SELECT TO_CHAR(TO_DATE('" . $params['startDate'] . "', 'YYYY-MM-DD') - 1 + LEVEL, 'YYYY-MM-DD') AS ATTENDANCE_DATE
                                                FROM DUAL
                                                  CONNECT BY LEVEL <= TO_DATE('" . $params['endDate'] . "', 'YYYY-MM-DD') - TO_DATE('" . $params['startDate'] . "', 'YYYY-MM-DD') + 1
                                              ) T0
                                              LEFT JOIN $VW_EMPLOYEE EE ON EE.EMPLOYEE_KEY_ID = EE.EMPLOYEE_KEY_ID AND EE.EMPLOYEE_ID = EE.EMPLOYEE_ID
                                              LEFT JOIN (
                                                SELECT EMPLOYEE_ID, 
                                                    TO_CHAR(BALANCE_DATE, 'YYYY-MM-DD') AS BALANCE_DATE, 
                                                    '1' AS ICHECK, 
                                                    END_TIME,
                                                    TIME_BALANCE_HDR_ID
                                                FROM TNA_TIME_BALANCE_HDR
                                              ) T6 ON T0.ATTENDANCE_DATE = T6.BALANCE_DATE AND T6.EMPLOYEE_ID = EE.EMPLOYEE_ID
                                              LEFT JOIN
                                                (SELECT T1.PLAN_ID,
                                                  T1.PLAN_DATE,
                                                  T0.EMPLOYEE_ID,
                                                  T0.EMPLOYEE_KEY_ID
                                                FROM TMS_EMPLOYEE_TIME_PLAN_HDR T0
                                                INNER JOIN TMS_EMPLOYEE_TIME_PLAN_DTL T1
                                                ON T0.ID                   = T1.TIME_PLAN_ID
                                                ) ETP ON EE.EMPLOYEE_ID = ETP.EMPLOYEE_ID AND T0.ATTENDANCE_DATE = TO_CHAR(ETP.PLAN_DATE, 'YYYY-MM-DD')    
                                              LEFT JOIN
                                              (SELECT ROUND(FNC_GET_TMS_PLAN_TIME(TTP.PLAN_ID)/60, 2) AS PLAN_TIME,
                                                TTP.PLAN_ID,
                                                TTP.CODE,
                                                TTP.NAME AS TYPE_NAME,
                                                TTPDTL.START_TIME,
                                                TTPDTL.END_TIME
                                              FROM TMS_TIME_PLAN TTP
                                              INNER JOIN TMS_TIME_PLAN_DEPARTMENT TTPD ON TTPD.PLAN_ID = TTP.PLAN_ID
                                              INNER JOIN
                                                (SELECT PLAN_ID,
                                                  MIN(START_TIME) AS START_TIME ,
                                                  MAX(END_TIME)   AS END_TIME
                                                FROM
                                                  (SELECT PLAN_ID,
                                                    PLAN_DETAIL_ID,
                                                    CASE
                                                      WHEN TO_CHAR(START_TIME, 'HH24') = '00'
                                                      THEN '24:'||TO_CHAR(START_TIME, 'MI')
                                                      ELSE TO_CHAR(START_TIME, 'HH24:MI')
                                                    END                          AS START_TIME,
                                                    TO_CHAR(END_TIME, 'HH24:MI') AS END_TIME
                                                  FROM TMS_TIME_PLAN_DETAIL
                                                  )
                                                GROUP BY PLAN_ID
                                                ) TTPDTL
                                              ON TTP.PLAN_ID        = TTPDTL.PLAN_ID
                                              ) lj ON ETP.PLAN_ID   = lj.PLAN_ID
                                              " . $whereSql . " AND (T6.ICHECK IS NULL OR T6.END_TIME IS NULL) AND ETP.PLAN_ID IS NOT NULL
                                            UNION
                                            SELECT DISTINCT EE.EMPLOYEE_ID AS EMPLOYEE_ID,
                                                EE.CODE AS EMPLOYEE_CODE,
                                                1 AS IS_FAULT,
                                                EE.EMPLOYEE_KEY_ID AS EMPLOYEE_KEY_ID,
                                                EE.EMPLOYEE_PICTURE AS PICTURE,
                                                EE.POSITION_NAME,
                                                EE.DEPARTMENT_NAME,
                                                SUBSTR(EE.LAST_NAME,1,2)||'.'||EE.FIRST_NAME||' ('||EE.CODE||')' AS EMPLOYEE_NAME,
                                                EE.LAST_NAME||' '||EE.FIRST_NAME||' ('||EE.CODE||')' AS EMPLOYEE_NAME_LONG,
                                                EE.STATUS_NAME,
                                                EE.DEPARTMENT_ID,
                                                EE.FIRST_NAME,
                                                EE.LAST_NAME,
                                                0 AS CLEAN_TIME,
                                                0 AS LATE_TIME,
                                                0 AS PLAN_TIME,
                                                null AS WFM_STATUS_NAME, 
                                                0 AS DEFFERENCE_TIME,
                                                0 AS CAUSE4,
                                                0 AS CAUSE5,
                                                0 AS CAUSE6,
                                                0 AS CAUSE13,
                                                0 AS CAUSE20,                                                    
                                                TO_CHAR(TO_DATE(T0.ATTENDANCE_DATE, 'YYYY-MM-DD'), 'MM')   AS BALANCE_DATE,
                                                TO_CHAR(TO_DATE(T0.ATTENDANCE_DATE, 'YYYY-MM-DD'), 'YYYY') AS BALANCE_YEAR                                                       
                                              FROM (
                                                SELECT 
                                                    TO_CHAR(ATTENDANCE_DATE_TIME, 'YYYY-MM-DD') AS ATTENDANCE_DATE,
                                                    TO_CHAR(ATTENDANCE_DATE_TIME, 'YYYY-MM-DD HH24:MI:SS') AS ATTENDANCE_DATE_TIME,
                                                    EMPLOYEE_ID,
                                                    EMPLOYEE_KEY_ID
                                                FROM TNA_TIME_ATTENDANCE WHERE IS_REMOVED_NOT_PLAN IS NULL OR IS_REMOVED_NOT_PLAN != 1
                                              ) T0
                                              INNER JOIN $VW_EMPLOYEE EE ON T0.EMPLOYEE_KEY_ID = EE.EMPLOYEE_KEY_ID AND T0.EMPLOYEE_ID = EE.EMPLOYEE_ID
                                              INNER JOIN HRM_EMPLOYEE HE ON EE.EMPLOYEE_ID = HE.EMPLOYEE_ID  
                                              LEFT JOIN (
                                                  SELECT
                                                      T1.PLAN_ID, T1.PLAN_DATE, T0.EMPLOYEE_ID
                                                  FROM
                                                      TMS_EMPLOYEE_TIME_PLAN_HDR T0
                                                  INNER JOIN TMS_EMPLOYEE_TIME_PLAN_DTL T1 ON T0.ID = T1.TIME_PLAN_ID
                                              ) T5 ON T0.EMPLOYEE_ID = T5.EMPLOYEE_ID AND T0.ATTENDANCE_DATE = TO_CHAR(T5.PLAN_DATE, 'YYYY-MM-DD')
                                              LEFT JOIN (
                                                SELECT 
                                                    EMPLOYEE_ID, 
                                                    TO_CHAR(BALANCE_DATE, 'YYYY-MM-DD') AS BALANCE_DATE,
                                                    '1' AS ICHECK
                                                FROM TNA_TIME_BALANCE_HDR
                                              ) T6 ON T0.ATTENDANCE_DATE = T6.BALANCE_DATE AND T6.EMPLOYEE_ID = EE.EMPLOYEE_ID
                                              LEFT JOIN (            
                                                    SELECT 
                                                      T2.PLAN_DURATION, 
                                                      T1.PLAN_ID,
                                                      TO_CHAR(T1.PLAN_DATE, 'YYYY-MM-DD') AS MAIN_DATE, 
                                                      TO_CHAR(T1.PLAN_DATE + T2.PLAN_DURATION, 'YYYY-MM-DD') AS PLAN_DATE
                                                    FROM TMS_EMPLOYEE_TIME_PLAN_HDR T0 
                                                    INNER JOIN TMS_EMPLOYEE_TIME_PLAN_DTL T1 ON T0.ID = T1.TIME_PLAN_ID
                                                    INNER JOIN TMS_TIME_PLAN T2 ON T1.PLAN_ID = T2.PLAN_ID
                                              ) T7 ON T0.ATTENDANCE_DATE = T7.PLAN_DATE AND  T0.ATTENDANCE_DATE <> T7.MAIN_DATE
                                              " . $whereSql . " AND T6.ICHECK IS NULL AND T7.PLAN_ID IS NULL AND T0.ATTENDANCE_DATE BETWEEN '" . $params['startDate'] . "' AND '" . $params['endDate'] . "'
                                              ORDER BY FIRST_NAME ASC
                                            ) SB ON SB.EMPLOYEE_ID = EE.EMPLOYEE_ID
                                            " . $whereSql . $whereSql2 . " 
                                            ORDER BY FIRST_NAME ASC
                                        )
                                        WHERE 1 = 1 AND STATUS_ID <> 41 AND CURRENT_STATUS_ID NOT IN (4,6)
                                        GROUP BY 
                                            EMPLOYEE_ID,
                                            EMPLOYEE_KEY_ID ,
                                            PICTURE,
                                            POSITION_NAME,
                                            EMPLOYEE_NAME,
                                            EMPLOYEE_NAME_LONG,
                                            LAST_NAME,
                                            FIRST_NAME,
                                            EMPLOYEE_CODE,
                                            STATUS_NAME,
                                            DEPARTMENT_ID,
                                            DEPARTMENT_NAME,
                                            IS_FAULT,
                                            CLEAN_TIME,
                                            LATE_TIME,
                                            DEFFERENCE_TIME,
                                            CAUSE4,
                                            CAUSE5,
                                            CAUSE6,
                                            CAUSE13,
                                            PCAUSE13,
                                            CAUSE20,                                                 
                                            STATUS_ID,
                                            CURRENT_STATUS_ID,
                                            BALANCE_YEAR
                                ) TEMP $filterRuleString ORDER BY $sortField $sortOrder";
                //echo $selectList; die;

                $rs = $this->db->SelectLimit($selectList, $rows, $offset);
                $result["rows"]  = isset($rs->_array) ? $rs->_array : array();
                //$result['total'] = $this->db->GetOne($selectCount);
                $result['total'] = count($this->db->GetAll($selectList));
                return $result;
            }
        }

        return $result;
    }

    public function balanceListMainDataGridV5Model() {

        $page = Input::postCheck('page') ? Input::post('page') : 1;
        $rows = Input::postCheck('rows') ? Input::post('rows') : 10;            
        $offset = ($page - 1) * $rows;
        $statusText = '';
        $result = array();
        $result["rows"]  = array();
        $result['total'] = '0';

        $where = "WHERE 1 = 1 ";
        $wherePosition = "";
        $whereSql2 = $departmentIds = $departmentSubIds = '';

        if (Input::postCheck('params')) {
            parse_str(Input::post('params'), $params);
//                $VW_EMPLOYEE = isset($params['viewEmployee']) ? 'VW_TMS_EMPLOYEE' : 'VW_EMPLOYEE';
            $VW_EMPLOYEE = 'VW_TMS_EMPLOYEE';

            $empKeyAddon = '';

            $tmsCustomerCode = Config::getFromCache('tmsCustomerCode');

            if ($tmsCustomerCode === 'khaanbank') {
                $empKeyAddon = ' OR ( ( TRUNC(WORK_START_DATE) <= \'' . $params['startDate'] . '\'
                                    AND ( ( TRUNC(WORK_END_DATE) BETWEEN \'' . $params['startDate'] . '\' AND \'' . $params['endDate'] . '\')
                                          AND WORK_END_DATE IS NOT NULL ) )
                                 OR ( TRUNC(WORK_START_DATE) BETWEEN \'' . $params['startDate'] . '\' AND \'' . $params['endDate'] . '\'
                                       AND ( TRUNC(WORK_END_DATE) >= \'' . $params['endDate'] . '\'
                                             AND  WORK_END_DATE IS NOT NULL ) )
                                 OR ( TRUNC(WORK_START_DATE) <= \'' . $params['startDate'] . '\'
                                       AND ( TRUNC(WORK_END_DATE) >= \'' . $params['endDate'] . '\'
                                             AND  WORK_END_DATE IS NOT NULL ) ))';
            }                

            if (!empty($params['startDate']) && !empty($params['endDate'])) {

                $filterString = $tmsClassificationIdNotInAppend = 
                $tmsClassificationIdAsName = $tmsClassificationIdAsAliasName = '';
                $bookTypeIds = '9024,9025,9026,9048';

                // <editor-fold defaultstate="collapsed" desc="Criteria">
                if (!empty($params['filterString'])) {
                    $filterString = " AND (LOWER(bl.FIRST_NAME) LIKE LOWER('%" . $params['filterString'] . "%') OR LOWER(bl.LAST_NAME) LIKE LOWER('%" . $params['filterString'] . "%') OR LOWER(EE.REGISTER_NUMBER) LIKE LOWER('%" . $params['filterString'] . "%') OR LOWER(EE.CODE) LIKE LOWER('%" . $params['filterString'] . "%'))";
                }

                if (is_array($params['newDepartmentId']) && $params['newDepartmentId'][0]) {
                    $departmentIds = $params['newDepartmentId'];
                    $departmentIds = implode(',', $departmentIds);
                    $isChild = issetVar($params['isChild']);

                    $departmentIds = $this->getAllChildDepartmentModel($departmentIds, $isChild);

                    $where = " WHERE EE.DEPARTMENT_ID IN ( " . $departmentIds . ") ";
                    $departmentSubIds = "DEPARTMENT_ID IN ( " . $departmentIds . ") AND ";

                    $positionIds = issetParam($params['positionId']);                                                
                    if ($positionIds) {
                        $positionIds = implode(',', $positionIds);
                        $wherePosition .= "AND EE.POSITION_ID IN ( " . $positionIds . ") ";
                    }

                } elseif ($params['newDepartmentId'] && $tmsCustomerCode == 'gov') {
                    $isChild = issetVar($params['isChild']);

                    $departmentIds = $this->getAllChildDepartmentModel($params['newDepartmentId'], $isChild);

                    $where = " WHERE EE.DEPARTMENT_ID IN ( " . $departmentIds . ") ";
                } elseif (isset($params['groupId']) && is_array($params['groupId'])) {
                    $where = " WHERE EE.EMPLOYEE_ID IN ( SELECT EMPLOYEE_ID FROM TNA_EMPLOYEE_GROUP_CONFIG WHERE GROUP_ID IN (" . implode(',', $params['groupId']) . ")) ";

                } else
                    $where = "WHERE 1 <> 1 ";

                $filterRuleString = ' WHERE 1 = 1 ';

                $causeString = '';
                $causeString1 = '';
                $causeString2 = ' AND (';
                $causeString3 = '';
                (String) $tableColumn1 = $tableColumn2 = $tableColumn3 = '';

                if (isset($params['causeTypeId'])) {
                    $causeString .= 'AND (';

                    foreach ($params['causeTypeId'] as $k => $row) {
                        if ($row == '0') {
                            break;
                        } elseif ($row == 'inTime') {
                            $causeString .= ($causeString === 'AND (') ? '(AA.START_TIME IS NULL) ' : '';
                        } elseif ($row == 'outTime') {
                            $causeString .= ($causeString === 'AND (') ? '(AA.END_TIME IS NULL) ' : 'OR (AA.END_TIME IS NULL)';
                        } elseif ($row == 'isConfirm') {
                            $filterRuleString .= ' AND AA.IS_CONFIRMED = 1 ';
                        } elseif ($row == 'isUnConfirm') {
                            $filterRuleString .= " AND STATUS = 'Зөрчилтэй' ";
                        } elseif ($row == '1') {
                            $filterRuleString .= " AND STATUS = 'Зөрчилгүй' ";
                        } else {
                            $causeString2 .= ($causeString2 === ' AND (') ? ' (AA.CAUSE'.$row.' IS NOT NULL AND AA.CAUSE'.$row.' <> 0)' : ' OR (AA.CAUSE'.$row.' IS NOT NULL AND AA.CAUSE'.$row.' <> 0)';
                        }
                    }

                    $causeString .= ')';
                    $causeString2 .= ')';
                    if ($causeString === 'AND ()') {
                        $causeString = '';
                    }
                    if ($causeString2 === ' AND ()') {
                        $causeString2 = '';
                    }
                    $causeString1 .= $causeString2;
                }                    

                if (Input::postCheck('filterRules')) {
                    $filterRules = json_decode(Str::cp1251_utf8($_POST['filterRules']), true);

                    if (count($filterRules) > 0) {

                        foreach ($filterRules as $rule) {
                            $ruleValue = Input::param(Str::lower(trim($rule['value'])));
                            switch ($rule['field']) {
                                case 'CLEAR_TIME':
                                case 'DEFFERENCE_TIME':
                                    $ruleValue = explode(':', $ruleValue);
                                    $ruleValue = (float) $ruleValue[0] + (float) $ruleValue[1] / 60;
                                    $filterRuleString .= ' AND ' . $rule['field'] . " = '" . $ruleValue . "'";
                                    break;
                                case 'STATUS_TEXT':
                                    $statusText = $ruleValue;
                                    break;
                                default:
                                    $filterRuleString .= ' AND LOWER(' . $rule['field'] . ") LIKE LOWER('%" . $ruleValue . "%')";
                                    break;
                            }
                        }
                    }
                }

                if (isset($params['stringValue'])) {
                    if (empty($params['stringValue']) === false && $params['stringValue'] != '') {

                        if(strpos($params['stringValue'], '.') === false) {
                            $filterRuleString .= " AND (LOWER(LAST_NAME) LIKE LOWER('%" . $params['stringValue'] . "%') OR LOWER(FIRST_NAME) LIKE LOWER('%" . $params['stringValue'] . "%') OR LOWER(EMPLOYEE_CODE) LIKE LOWER('%" . $params['stringValue'] . "%')) ";
                        } else {
                            $strexplode = explode('.', $params['stringValue']);
                            $filterRuleString .= " AND (LOWER(LAST_NAME) LIKE LOWER('%" . $strexplode[0] . "%') AND LOWER(FIRST_NAME) LIKE LOWER('%" . $strexplode[1] . "%')) ";
                        }
                    }
                }

                $sortField = 'DEPARTMENT_NAME, FIRST_NAME, POSITION_NAME';
                $employeeNameColumn = "SUBSTR(EE.LAST_NAME,1,2)||'.'||EE.FIRST_NAME||' ('||EE.CODE||')'";

                if (Config::getFromCache('tmsCustomerCode') == 'gov') {
                    $employeeNameColumn = "SUBSTR(EE.LAST_NAME,1,1)||'.'||EE.FIRST_NAME||' ('||EE.CODE||')'";
                    $tableColumn2 = ", EE.WORK_START_DATE ";
                    $tableColumn3 = " WORK_START_DATE, ";
                    $sortField = 'LPAD(DEP_ORDER, 10), LPAD(POS_ORDER, 10), WORK_START_DATE';
                }

                $sortOrder = 'ASC';
                if (Input::postCheck('sort') && Input::postCheck('order')) {
                    $sortField = Input::post('sort') === 'EMPLOYEE_NAME' ? 'FIRST_NAME' : Input::post('sort');
                    $sortOrder = Input::post('order');
                }

                $whereSql2 = $causeString . $causeString1;
                $whereSql = $where . $filterString;
                // </editor-fold>

                $currentStatusNotIn = Config::getFromCache('tmsCurrentStatus');
                $tmsClassificationIdNotIn = Config::getFromCache('tmsClassificationId');
                $statusNotIn = Config::getFromCache('tmsStatus');
                $tmsBalanceIsMovementEmployee = (isset($params['isMovementEmployee']) && $params['isMovementEmployee']) ? Config::getFromCache('tmsBalanceIsMovementEmployee') : '0';
                $tmsBalanceIsMoveEmployee = Config::getFromCache('tmsBalanceIsMovementEmployee') ? !isset($params['isMovementEmployee']) ? '1' : '0' : '0';
                //$params['startDate'] . "' AND '" . $params['endDate']

                $leftJoinWhere = ($tmsBalanceIsMovementEmployee == '1' ? " AND (HDR.BALANCE_DATE BETWEEN EE.WORK_START_DATE AND NVL(EE.WORK_END_DATE, TO_DATE('". $params['endDate'] ."', 'YYYY-MM-DD')))" : '');
                $leftJoinAnd = ($tmsBalanceIsMovementEmployee == '1' ? " AND (AA.BALANCE_DATE BETWEEN EE.WORK_START_DATE AND NVL(EE.WORK_END_DATE, TO_DATE('". $params['endDate'] ."', 'YYYY-MM-DD')))" : '');

                if ($tmsClassificationIdNotIn) {
                    $tmsClassificationIdNotInAppend = " AND CLASSIFICATION_ID NOT IN ($tmsClassificationIdNotIn) OR CLASSIFICATION_ID IS NULL ";
                    $tmsClassificationIdAsName = 'CLASSIFICATION_ID, ';
                    $tmsClassificationIdAsAliasName = 'EE.CLASSIFICATION_ID, ';
                }

                $mainSelectQuery = "SELECT 
                    EMPLOYEE_ID,
                    EMPLOYEE_KEY_ID ,
                    PICTURE,
                    POSITION_NAME,
                    EMPLOYEE_NAME,
                    EMPLOYEE_NAME_LONG,
                    LAST_NAME,
                    FIRST_NAME,
                    DEPARTMENT_NAME,
                    EMPLOYEE_CODE,
                    STATUS_NAME,
                    DEPARTMENT_ID,
                    POSITION_ID,
                    CASE WHEN TO_CHAR(TO_DATE('" . $params['startDate'] . "', 'YYYY-MM-DD'), 'YYYYMM') = TO_CHAR(TO_DATE('" . $params['endDate'] . "', 'YYYY-MM-DD'), 'YYYYMM')
                        THEN TO_CHAR(TO_DATE('" . $params['startDate'] . "', 'YYYY-MM-DD'), 'YYYY.MM')
                        ELSE TO_CHAR(TO_DATE('" . $params['startDate'] . "', 'YYYY-MM-DD'), 'YYYY')||'.['||
                            TO_CHAR(TO_DATE('" . $params['startDate'] . "', 'YYYY-MM-DD'), 'MM')||'...'||
                            TO_CHAR(TO_DATE('" . $params['endDate'] . "', 'YYYY-MM-DD'), 'MM')||']'
                    END AS BALANCE_DATE,
                    CASE WHEN TO_CHAR(TO_DATE('" . $params['startDate'] . "', 'YYYY-MM'), 'YYYYMM') = TO_CHAR(TO_DATE('" . $params['endDate'] . "', 'YYYY-MM-DD'), 'YYYYMM')
                        THEN TO_CHAR(TO_DATE('" . $params['startDate'] . "', 'YYYY-MM-DD'), 'YYYY/MM')||'-'||TO_CHAR(TO_DATE('" . $params['endDate'] . "', 'YYYY-MM-DD'), 'YYYY/MM')
                        ELSE TO_CHAR(TO_DATE('" . $params['startDate'] . "', 'YYYY-MM-DD'), 'YYYY/MM')||'-'||TO_CHAR(TO_DATE('" . $params['endDate'] . "', 'YYYY-MM-DD'), 'YYYY/MM') END AS BALANCEDATE,    
                    CLEAN_TIME AS CLEAR_TIME,
                    CLEAN_TIME,
                    LATE_TIME,
                    NIGHT_TIME,
                    EARLY_TIME,
                    DEFFERENCE_TIME,
                    CAUSE1,
                    CAUSE2,
                    CAUSE3,
                    CAUSE4,
                    CAUSE5,
                    CAUSE6,
                    CAUSE7,                                                     
                    CAUSE8,
                    CAUSE9,
                    CAUSE10,
                    CAUSE11,
                    CAUSE12,
                    CAUSE13,
                    CAUSE14,
                    CAUSE15,
                    CAUSE16,
                    CAUSE17,
                    CAUSE18,
                    CAUSE19,
                    CAUSE20,
                    CAUSE21,
                    CAUSE22,
                    CAUSE23,
                    CAUSE24,
                    CAUSE25,
                    CAUSE26,
                    CAUSE27,
                    CAUSE28,
                    CAUSE29,
                    CAUSE30,                               
                    PLAN_TIME,                                                
                    CASE
                        WHEN DEFFERENCE_TIME < 0  THEN '#f2dede'
                        WHEN  PLAN_TIME >0 AND CLEAN_TIME=PLAN_TIME THEN '#dff0d8'
                        WHEN  PLAN_TIME >0 AND CLEAN_TIME= 0 THEN '#f2dede'
                        WHEN  PLAN_TIME >0 AND CLEAN_TIME<=PLAN_TIME AND LATE_TIME =0 THEN '#dff0d8'
                        WHEN  PLAN_TIME >0 AND CLEAN_TIME<=PLAN_TIME AND LATE_TIME !=0 THEN '#fbeac5'
                        WHEN  PLAN_TIME >0 AND CLEAN_TIME > PLAN_TIME  THEN '#B4D8E7'
                        WHEN  PLAN_TIME =0 AND CLEAN_TIME > 0 THEN '#f2dede'
                        WHEN  PLAN_TIME =0 AND CLEAN_TIME=0 THEN '#ffffff'
                        ELSE  '#f2dede'
                    END AS BACKGROUND_COLOR,
                    CASE
                        WHEN DEFFERENCE_TIME < 0 THEN '#F00'
                        ELSE '#000'
                    END AS FONT_COLOR,
                    STATUS_ID,
                    IS_USER_CONFIRMED,
                    WFM_STATUS_NAME,
                    WFM_STATUS_COLOR,
                    DEP_ORDER, 
                    $tableColumn3
                    POS_ORDER                                                
                FROM (
                    SELECT 
                        DISTINCT 
                        EE.EMPLOYEE_ID,
                        EE.CODE AS EMPLOYEE_CODE,
                        EE.EMPLOYEE_KEY_ID  AS EMPLOYEE_KEY_ID,
                        EE.EMPLOYEE_PICTURE AS PICTURE,
                        EE.POSITION_NAME,
                        EE.DEPARTMENT_NAME,
                        $employeeNameColumn AS EMPLOYEE_NAME,
                        EE.LAST_NAME||' '||EE.FIRST_NAME||' ('||EE.CODE||')' AS EMPLOYEE_NAME_LONG,
                        EE.STATUS_NAME, 
                        EE.DEPARTMENT_ID, 
                        EE.POSITION_ID, 
                        $tmsClassificationIdAsAliasName  
                        EE.FIRST_NAME,
                        EE.LAST_NAME,
                        EE.DEP_ORDER, 
                        EE.POS_ORDER,
                        AAA.CLEAN_TIME,
                        AAA.LATE_TIME,
                        AAA.NIGHT_TIME,
                        AAA.EARLY_TIME,
                        AAA.DEFFERENCE_TIME,
                        AAA.CAUSE1,
                        AAA.CAUSE2,
                        AAA.CAUSE3,
                        AAA.CAUSE4,
                        AAA.CAUSE5,
                        AAA.CAUSE6,
                        AAA.CAUSE7,                                                     
                        AAA.CAUSE8,
                        AAA.CAUSE9,
                        AAA.CAUSE10,
                        AAA.CAUSE11,
                        AAA.CAUSE12,
                        AAA.CAUSE13,
                        AAA.CAUSE14,
                        AAA.CAUSE15,
                        AAA.CAUSE16,
                        AAA.CAUSE17,
                        AAA.CAUSE18,
                        AAA.CAUSE19,
                        AAA.CAUSE20,
                        AAA.CAUSE21,
                        AAA.CAUSE22,
                        AAA.CAUSE23,
                        AAA.CAUSE24,
                        AAA.CAUSE25,
                        AAA.CAUSE26,
                        AAA.CAUSE27,
                        AAA.CAUSE28,
                        AAA.CAUSE29,
                        AAA.CAUSE30,
                        AAA.PLAN_TIME,
                        TO_CHAR(AA.BALANCE_DATE, 'MM')    AS BALANCE_DATE,
                        TO_CHAR(AA.BALANCE_DATE, 'YYYY') AS BALANCE_YEAR,
                        ek.STATUS_ID,
                        ek.CURRENT_STATUS_ID,
                        AAA.IS_USER_CONFIRMED,
                        CASE
                            WHEN LAB_HEK.EMPLOYEE_KEY_ID IS NULL THEN 0
                            WHEN LAB_HEK.EMPLOYEE_KEY_ID IS NOT NULL AND LAB_HEK.LIMITLESS = '0' THEN 1
                            ELSE 2
                        END AS IS_LABOUR,
                        WFM.WFM_STATUS_NAME,
                        WFM.WFM_STATUS_COLOR   
                        $tableColumn2                                                                                                
                    FROM $VW_EMPLOYEE EE
                    INNER JOIN ( 
                        SELECT
                        MAX(EMPLOYEE_KEY_ID) AS EMPLOYEE_KEY_ID
                        FROM
                        HRM_EMPLOYEE_KEY
                        WHERE $departmentSubIds CURRENT_STATUS_ID <> 6
                            AND ( ";

                            if ($tmsBalanceIsMoveEmployee == '0') {
                                $mainSelectQuery .= " (TRUNC(WORK_START_DATE) BETWEEN '" . $params['startDate'] . "' AND '" . $params['endDate'] . "' AND ( TRUNC(WORK_END_DATE) <= '" . $params['endDate'] . "' OR WORK_END_DATE IS NULL)) ";
                            } else {
                                $mainSelectQuery .= " WORK_END_DATE IS NULL ";
                            }

                        $mainSelectQuery .= " 
                                $empKeyAddon
                            )
                        GROUP BY
                        EMPLOYEE_ID ";
                        if ($tmsBalanceIsMoveEmployee == '0') {
                            $mainSelectQuery .= " UNION ALL
                                SELECT
                                MAX(EMPLOYEE_KEY_ID) AS EMPLOYEE_KEY_ID
                                FROM
                                HRM_EMPLOYEE_KEY
                                WHERE $departmentSubIds CURRENT_STATUS_ID <> 6 
                                    AND ( 
                                        (TRUNC(WORK_START_DATE) <= '" . $params['startDate'] . "' AND ((TRUNC(WORK_END_DATE) BETWEEN '" . $params['startDate'] . "' AND '" . $params['endDate'] . "') OR WORK_END_DATE IS NULL))
                                    )
                                GROUP BY
                                EMPLOYEE_ID ";
                        }
                        $mainSelectQuery.= "
                    ) ACTIVE_HEK ON ACTIVE_HEK.EMPLOYEE_KEY_ID = EE.EMPLOYEE_KEY_ID
                    LEFT JOIN ( 
                            SELECT
                            BB.NEW_EMPLOYEE_KEY_ID AS EMPLOYEE_KEY_ID,
                            '0' AS LIMITLESS
                            FROM HCM_LABOUR_BOOK AA 
                            INNER JOIN HCM_LABOUR_BOOK_DTL BB ON AA.ID = BB.BOOK_ID
                            INNER JOIN HRM_EMPLOYEE_KEY LK ON LK.EMPLOYEE_KEY_ID = BB.NEW_EMPLOYEE_KEY_ID 
                            WHERE AA.BOOK_TYPE_ID IN ($bookTypeIds) AND LK.CURRENT_STATUS_ID NOT IN (1, 3)
                            UNION ALL
                            SELECT
                            BB.NEW_EMPLOYEE_KEY_ID AS EMPLOYEE_KEY_ID,
                            '1' AS LIMITLESS
                            FROM HCM_LABOUR_BOOK AA
                            INNER JOIN HCM_LABOUR_BOOK_DTL BB ON AA.ID = BB.BOOK_ID
                            INNER JOIN HRM_EMPLOYEE_KEY LK ON LK.EMPLOYEE_KEY_ID = BB.NEW_EMPLOYEE_KEY_ID
                            WHERE AA.BOOK_TYPE_ID IN ($bookTypeIds) AND TRUNC(BB.START_DATE) BETWEEN '" . $params['startDate'] . "' AND '" . $params['endDate'] . "' AND LK.CURRENT_STATUS_ID NOT IN (1, 3)
                    ) LAB_HEK ON LAB_HEK.EMPLOYEE_KEY_ID = ACTIVE_HEK.EMPLOYEE_KEY_ID                                                
                    INNER JOIN HRM_EMPLOYEE_KEY ek ON ACTIVE_HEK.EMPLOYEE_KEY_ID = ek.EMPLOYEE_KEY_ID
                    LEFT JOIN TNA_TIME_BALANCE_HDR AA ON EE.EMPLOYEE_ID = AA.EMPLOYEE_ID AND AA.BALANCE_DATE >= TO_DATE('" . $params['startDate'] . "', 'YYYY-MM-DD') AND AA.BALANCE_DATE <=  TO_DATE('" . $params['endDate'] . "', 'YYYY-MM-DD') $leftJoinAnd
                    LEFT JOIN META_WFM_STATUS WFM ON WFM.ID = AA.WFM_STATUS_ID
                    LEFT JOIN (
                        SELECT 
                            HDR.EMPLOYEE_ID,
                            EE.EMPLOYEE_KEY_ID,
                            SUM(HDR.CLEAN_TIME) AS CLEAN_TIME, 
                            SUM(HDR.PLAN_TIME) AS PLAN_TIME, 
                            SUM(HDR.LATE_TIME) AS LATE_TIME, 
                            SUM(HDR.NIGHT_TIME) AS NIGHT_TIME, 
                            SUM(HDR.EARLY_TIME) AS EARLY_TIME,
                            SUM(HDR.CAUSE1) AS CAUSE1,
                            SUM(HDR.CAUSE2) AS CAUSE2,
                            SUM(HDR.CAUSE3) AS CAUSE3,
                            SUM(HDR.CAUSE4) AS CAUSE4,
                            SUM(HDR.CAUSE5) AS CAUSE5,
                            SUM(HDR.CAUSE6) AS CAUSE6,
                            SUM(HDR.CAUSE7) AS CAUSE7,                                                     
                            SUM(HDR.CAUSE8) AS CAUSE8,
                            SUM(HDR.CAUSE9) AS CAUSE9,
                            SUM(HDR.CAUSE10) AS CAUSE10,
                            SUM(HDR.CAUSE11) AS CAUSE11,
                            SUM(HDR.CAUSE12) AS CAUSE12,
                            SUM(HDR.CAUSE13) AS CAUSE13,
                            SUM(HDR.CAUSE14) AS CAUSE14,
                            SUM(HDR.CAUSE15) AS CAUSE15,
                            SUM(HDR.CAUSE16) AS CAUSE16,
                            SUM(HDR.CAUSE17) AS CAUSE17,
                            SUM(HDR.CAUSE18) AS CAUSE18,
                            SUM(HDR.CAUSE19) AS CAUSE19,
                            SUM(HDR.CAUSE20) AS CAUSE20,
                            SUM(HDR.CAUSE21) AS CAUSE21,
                            SUM(HDR.CAUSE22) AS CAUSE22,
                            SUM(HDR.CAUSE23) AS CAUSE23,
                            SUM(HDR.CAUSE24) AS CAUSE24,
                            SUM(HDR.CAUSE25) AS CAUSE25,
                            SUM(HDR.CAUSE26) AS CAUSE26,
                            SUM(HDR.CAUSE27) AS CAUSE27,
                            SUM(HDR.CAUSE28) AS CAUSE28,
                            SUM(HDR.CAUSE29) AS CAUSE29,
                            SUM(HDR.CAUSE30) AS CAUSE30,                                                        
                            SUM(HDR.DEFFERENCE_TIME) AS DEFFERENCE_TIME,
                            SUM(COALESCE(IS_USER_CONFIRMED, 0)) AS IS_USER_CONFIRMED
                        FROM TNA_TIME_BALANCE_HDR HDR
                        INNER JOIN HRM_EMPLOYEE EP ON EP.EMPLOYEE_ID = HDR.EMPLOYEE_ID
                        INNER JOIN HRM_EMPLOYEE_KEY EE ON EE.EMPLOYEE_ID = EP.EMPLOYEE_ID ";

                        if ($tmsBalanceIsMovementEmployee != '1') {
                            $mainSelectQuery.= "INNER JOIN ( 
                                                    SELECT
                                                        MAX(EMPLOYEE_KEY_ID) AS EMPLOYEE_KEY_ID
                                                    FROM
                                                    HRM_EMPLOYEE_KEY
                                                    WHERE $departmentSubIds CURRENT_STATUS_ID <> 6
                                                        AND ( 
                                                            (TRUNC(WORK_START_DATE) BETWEEN '" . $params['startDate'] . "' AND '" . $params['endDate'] . "' AND (TRUNC(WORK_END_DATE) <= '" . $params['endDate'] . "' OR WORK_END_DATE IS NULL))
                                                            $empKeyAddon
                                                        )
                                                        OR (
                                                            (TRUNC(WORK_START_DATE) <= '" . $params['startDate'] . "' AND ((TRUNC(WORK_END_DATE) BETWEEN '" . $params['startDate'] . "' AND '" . $params['endDate'] . "') OR WORK_END_DATE IS NULL))
                                                        )
                                                    GROUP BY
                                                        EMPLOYEE_ID
                                                /*UNION ALL
                                                    SELECT
                                                        MAX(EMPLOYEE_KEY_ID) AS EMPLOYEE_KEY_ID
                                                    FROM
                                                    HRM_EMPLOYEE_KEY
                                                    WHERE $departmentSubIds CURRENT_STATUS_ID <> 6 
                                                        AND ( 
                                                            (TRUNC(WORK_START_DATE) <= '" . $params['startDate'] . "' AND ((TRUNC(WORK_END_DATE) BETWEEN '" . $params['startDate'] . "' AND '" . $params['endDate'] . "') OR WORK_END_DATE IS NULL))
                                                        )
                                                    GROUP BY
                                                        EMPLOYEE_ID*/
                                            ) ACTIVE_HEK ON ACTIVE_HEK.EMPLOYEE_KEY_ID = EE.EMPLOYEE_KEY_ID ";
                        }

                        $mainSelectQuery .= " $where AND TRUNC(HDR.BALANCE_DATE) >= TO_DATE('" . $params['startDate'] . "', 'YYYY-MM-DD') AND HDR.BALANCE_DATE <=  TO_DATE('" . $params['endDate'] . "', 'YYYY-MM-DD') $leftJoinWhere
                        GROUP BY 
                            HDR.EMPLOYEE_ID,
                            EE.EMPLOYEE_KEY_ID
                    ) AAA ON EE.EMPLOYEE_ID = AAA.EMPLOYEE_ID AND EE.EMPLOYEE_KEY_ID = AAA.EMPLOYEE_KEY_ID
                    " . $whereSql . $wherePosition . $whereSql2 . " 
                    ORDER BY LPAD(EE.DEP_ORDER, 10), LPAD(EE.POS_ORDER, 10) $tableColumn2 ASC
                )
                WHERE 1 = 1 
                    AND STATUS_ID NOT IN ($statusNotIn) 
                    AND CURRENT_STATUS_ID NOT IN ($currentStatusNotIn)     
                    AND (IS_LABOUR = 0 OR IS_LABOUR = 2) 
                    $tmsClassificationIdNotInAppend 
                GROUP BY 
                    EMPLOYEE_ID,
                    EMPLOYEE_KEY_ID,
                    PICTURE,
                    POSITION_NAME,
                    EMPLOYEE_NAME,
                    EMPLOYEE_NAME_LONG,
                    LAST_NAME,
                    FIRST_NAME,
                    EMPLOYEE_CODE,
                    STATUS_NAME,
                    DEPARTMENT_ID,
                    POSITION_ID, 
                    $tmsClassificationIdAsName 
                    DEPARTMENT_NAME,
                    CLEAN_TIME,
                    LATE_TIME,
                    NIGHT_TIME,
                    EARLY_TIME,
                    DEFFERENCE_TIME,
                    CAUSE1,
                    CAUSE2,
                    CAUSE3,
                    CAUSE4,
                    CAUSE5,
                    CAUSE6,
                    CAUSE7,                                                     
                    CAUSE8,
                    CAUSE9,
                    CAUSE10,
                    CAUSE11,
                    CAUSE12,
                    CAUSE13,
                    CAUSE14,
                    CAUSE15,
                    CAUSE16,
                    CAUSE17,
                    CAUSE18,
                    CAUSE19,
                    CAUSE20,
                    CAUSE21,
                    CAUSE22,
                    CAUSE23,
                    CAUSE24,
                    CAUSE25,
                    CAUSE26,
                    CAUSE27,
                    CAUSE28,
                    CAUSE29,
                    CAUSE30,                                            
                    PLAN_TIME,                                                 
                    STATUS_ID,
                    DEP_ORDER,
                    POS_ORDER,
                    IS_USER_CONFIRMED,
                    CURRENT_STATUS_ID,
                    BALANCE_YEAR,
                    WFM_STATUS_NAME,
                    $tableColumn3
                    WFM_STATUS_COLOR";

                $selectList = " SELECT * 
                                FROM (
                                    $mainSelectQuery
                                ) TEMP $filterRuleString ORDER BY $sortField $sortOrder";
//                    echo $selectList; die;

                $selectListCount = " SELECT COUNT(*) 
                                FROM (
                                    $mainSelectQuery
                                ) TEMP $filterRuleString";                    

            $selectListSum = "SELECT * 
                                FROM (
                                        SELECT
                                            SUM(CLEAN_TIME) AS CLEAR_TIME,
                                            SUM(LATE_TIME) AS LATE_TIME,
                                            SUM(EARLY_TIME) AS EARLY_TIME,
                                            SUM(DEFFERENCE_TIME) AS DEFFERENCE_TIME,
                                            SUM(CAUSE1) AS CAUSE1,
                                            SUM(CAUSE2) AS CAUSE2,
                                            SUM(CAUSE3) AS CAUSE3,
                                            SUM(CAUSE4) AS CAUSE4,
                                            SUM(CAUSE5) AS CAUSE5,
                                            SUM(CAUSE6) AS CAUSE6,
                                            SUM(CAUSE7) AS CAUSE7,                                                     
                                            SUM(CAUSE8) AS CAUSE8,
                                            SUM(CAUSE9) AS CAUSE9,
                                            SUM(CAUSE10) AS CAUSE10,
                                            SUM(CAUSE11) AS CAUSE11,
                                            SUM(CAUSE12) AS CAUSE12,
                                            SUM(CAUSE13) AS CAUSE13,
                                            SUM(CAUSE14) AS CAUSE14,
                                            SUM(CAUSE15) AS CAUSE15,
                                            SUM(CAUSE16) AS CAUSE16,
                                            SUM(CAUSE17) AS CAUSE17,
                                            SUM(CAUSE18) AS CAUSE18,
                                            SUM(CAUSE19) AS CAUSE19,
                                            SUM(CAUSE20) AS CAUSE20,
                                            SUM(CAUSE21) AS CAUSE21,
                                            SUM(CAUSE22) AS CAUSE22,
                                            SUM(CAUSE23) AS CAUSE23,
                                            SUM(CAUSE24) AS CAUSE24,
                                            SUM(CAUSE25) AS CAUSE25,
                                            SUM(CAUSE26) AS CAUSE26,
                                            SUM(CAUSE27) AS CAUSE27,
                                            SUM(CAUSE28) AS CAUSE28,
                                            SUM(CAUSE29) AS CAUSE29,
                                            SUM(CAUSE30) AS CAUSE30,                         
                                            SUM(NIGHT_TIME) AS NIGHT_TIME,                         
                                            SUM(PLAN_TIME) AS PLAN_TIME,
                                            ' ' AS BALANCE_DATE,
                                            ' ' AS EMPLOYEE_NAME,
                                            ' ' AS POSITION_NAME
                                          FROM (
                                            SELECT * FROM (
                                                $mainSelectQuery
                                            )
                                        ) $filterRuleString ORDER BY $sortField $sortOrder
                                ) TEMP";

                $selectListSumArr = $this->db->GetAll($selectListSum);                     

                $rs = $this->db->SelectLimit($selectList, $rows, $offset);
                $result["rows"]  = isset($rs->_array) ? $rs->_array : array();
                $result['total'] = $this->db->GetOne($selectListCount);
                $result['footer'] = $selectListSumArr;

                return $result;
            }
        }

        return $result;
    }

    public function balanceListMainDataGridV6Model() {

        $page = Input::numeric('page', 1);
        $rows = Input::numeric('rows', 10);
        $balanceCriteria = array();
        $balanceDVid = Config::getFromCache('tnaTimeBalanceHdrDV');

        parse_str(Input::post('params'), $params);

        if (is_array($params['newDepartmentId']) && $params['newDepartmentId'][0]) {
            $departmentIds = $params['newDepartmentId'];
            $departmentIds = implode(',', $departmentIds);
            $isChild = issetVar($params['isChild']);

            $departmentIds = $this->getAllChildDepartmentModel($departmentIds, $isChild);
            $balanceCriteria['filterDepartmentId'] = array(
                array('operator' => 'IN', 'operand' => $departmentIds)
            );

            $positionIds = issetParam($params['positionId']);                                                
            if ($positionIds) {
                $positionIds = implode(',', $positionIds);
                $balanceCriteria['positionId'] = array(
                    array('operator' => 'IN', 'operand' => $positionIds)
                );
            }
        }
        $balanceCriteria['filterStartDate'] = array(
            array('operator' => '=', 'operand' => $params['startDate'])
        );            
        $balanceCriteria['filterEndDate'] = array(
            array('operator' => '=', 'operand' => $params['endDate'])
        );            
        $balanceCriteria['filterStringValue'] = array(
            array('operator' => 'LIKE', 'operand' => '%' . $params['stringValue'] . '%')
        );            
        if ((isset($params['groupId']) && is_array($params['groupId']))) {
            $balanceCriteria['filterGroupId'] = array(
                array('operator' => 'IN', 'operand' => implode(',', $params['groupId']))
            );
        }            
        if ((isset($params['positionId']) && is_array($params['positionId']))) {
            $balanceCriteria['filterPositionId'] = array(
                array('operator' => 'IN', 'operand' => implode(',', $params['positionId']))
            );
        } 
        
        if ((isset($params['causeTypeId']) && is_array($params['causeTypeId']))) {
            $balanceCriteria['filterCauseTypeId'] = array(
                array('operator' => 'IN', 'operand' => implode(',', $params['causeTypeId']))
            );
        }

        if (Input::postCheck('filterRules')) {
            $filterRules = json_decode(Str::cp1251_utf8($_POST['filterRules']), true);

            if (count($filterRules) > 0) {

                foreach ($filterRules as $rule) {
                    $ruleValue = Input::param(trim($rule['value']));

                    switch ($rule['field']) {
                        case 'employeename':
                        case 'positionname':
                            $balanceCriteria[$rule['field']] = array(
                                array('operator' => 'LIKE', 'operand' => '%'.$ruleValue.'%')
                            );
                            break;
                        case 'balancedateshow':
                            $balanceCriteria[$rule['field']] = array(
                                array('operator' => '=', 'operand' => $ruleValue)
                            );
                            break;                                
                        default:

                            if (strpos($ruleValue, ':') !== false) {
                                $ruleValue = explode(':', $ruleValue);
                                $ruleValue = (float) $ruleValue[0] * 60 + (float) $ruleValue[1];                            
                            } else {
                                $ruleValue = (float) $ruleValue * 60;                            
                            }

                            $balanceCriteria[$rule['field']] = array(
                                array('operator' => '=', 'operand' => $ruleValue)
                            );
                            break;
                    }
                }
            }
        }         

        (Array) $param = array(
            'systemMetaGroupId' => $balanceDVid,
            'showQuery' => 0, 
            'ignorePermission' => 1,
            'paging' => array(
                'offset' => $page,
                'pageSize' => $rows
            ),
            'criteria' => $balanceCriteria
        );

        if (Input::postCheck('sort') && Input::postCheck('order')) {
            $param['paging']['sortColumnNames'] = array(
                Input::post('sort') => array(
                    'sortType' => Input::post('order')
                )
            );                
        }               

        $result = $this->ws->runSerializeResponse(self::$gfServiceAddress, Mddatamodel::$getDataViewCommand, $param);

        if ($result['status'] === 'success' && isset($result['result'])) {
            $response = array('status' => 'success');

            $response['total'] = (isset($result['result']['paging']) ? $result['result']['paging']['totalcount'] : 0);
            if (isset($result['result']['aggregatecolumns']) && $result['result']['aggregatecolumns']) {
                $response['footer'] = array($result['result']['aggregatecolumns']);
            }                

            unset($result['result']['paging']);
            unset($result['result']['aggregatecolumns']);

            $response['rows'] = $result['result'];
        } else {
            $response = array('status' => 'error', 'message' => $this->ws->getResponseMessage($result));
        }            

        return $response;
    }

    public function balanceListMainDataGridV2Model() {

        $page = Input::postCheck('page') ? Input::post('page') : 1;
        $rows = Input::postCheck('rows') ? Input::post('rows') : 10;            
        $offset = ($page - 1) * $rows;
        $statusText = '';
        $result = array();
        $result["rows"]  = array();
        $result['total'] = '0';

        $where = "WHERE 1 = 1 ";
        $whereSql2 = '';
        $joiner = '';

        if (Input::postCheck('params')) {
            parse_str(Input::post('params'), $params);
            $VW_EMPLOYEE = isset($params['viewEmployee']) ? 'VW_TMS_EMPLOYEE' : 'VW_EMPLOYEE';

            if(Config::getFromCache('CONFIG_TNA_HIDENOTPLAN')) {
                $joiner = " INNER JOIN (
                            SELECT
                                EE.EMPLOYEE_ID
                            FROM (
                                SELECT  TO_CHAR(TO_DATE('" . $params['startDate'] . "', 'YYYY-MM-DD') - 1 + LEVEL, 'YYYY-MM-DD') AS ATTENDANCE_DATE
                                FROM DUAL
                                CONNECT BY LEVEL <= TO_DATE('" . $params['endDate'] . "', 'YYYY-MM-DD') - TO_DATE('" . $params['startDate'] . "', 'YYYY-MM-DD') + 1 
                            ) T0
                            INNER JOIN $VW_EMPLOYEE EE ON EE.EMPLOYEE_KEY_ID = EE.EMPLOYEE_KEY_ID AND EE.EMPLOYEE_ID = EE.EMPLOYEE_ID
                            INNER JOIN HRM_EMPLOYEE HE ON EE.EMPLOYEE_ID = HE.EMPLOYEE_ID  
                            LEFT JOIN (
                                SELECT 
                                    EMPLOYEE_ID, 
                                    TO_CHAR(BALANCE_DATE, 'YYYY-MM-DD') AS BALANCE_DATE,
                                    '1' AS ICHECK
                                FROM TNA_TIME_BALANCE_HDR 
                            ) T6 ON T0.ATTENDANCE_DATE = T6.BALANCE_DATE AND EE.EMPLOYEE_ID = T6.EMPLOYEE_ID
                            LEFT JOIN (
                                SELECT
                                    T1.PLAN_ID, T1.PLAN_DATE, T0.EMPLOYEE_ID
                                FROM
                                    TMS_EMPLOYEE_TIME_PLAN_HDR T0
                                INNER JOIN TMS_EMPLOYEE_TIME_PLAN_DTL T1 ON T0.ID = T1.TIME_PLAN_ID
                            ) T5 ON EE.EMPLOYEE_ID = T5.EMPLOYEE_ID AND T0.ATTENDANCE_DATE = TO_CHAR(T5.PLAN_DATE, 'YYYY-MM-DD')
                            LEFT JOIN (SELECT TO_CHAR(ATTENDANCE_DATE_TIME, 'YYYY-MM-DD')      AS ATTENDANCE_DATE,
                                TO_CHAR(ATTENDANCE_DATE_TIME, 'YYYY-MM-DD HH24:MI:SS') AS ATTENDANCE_DATE_TIME,
                                EMPLOYEE_ID
                            FROM TNA_TIME_ATTENDANCE
                            ) T10 ON T0.ATTENDANCE_DATE = T10.ATTENDANCE_DATE AND EE.EMPLOYEE_ID = T10.EMPLOYEE_ID
                            LEFT JOIN (
                                SELECT 
                                    T2.PLAN_DURATION, 
                                    '1' AS DARK_CHECK_TIME,
                                    TO_CHAR(T1.PLAN_DATE, 'YYYY-MM-DD') AS MAIN_DATE, 
                                    TO_CHAR(T1.PLAN_DATE + T2.PLAN_DURATION, 'YYYY-MM-DD') AS PLAN_DATE,
                                    T0.EMPLOYEE_ID
                                    FROM TMS_EMPLOYEE_TIME_PLAN_HDR T0 
                                    INNER JOIN TMS_EMPLOYEE_TIME_PLAN_DTL T1 ON T0.ID = T1.TIME_PLAN_ID
                                    INNER JOIN TMS_TIME_PLAN T2 ON T1.PLAN_ID = T2.PLAN_ID
                            ) T7 ON T0.ATTENDANCE_DATE = T7.PLAN_DATE AND EE.EMPLOYEE_ID = T7.EMPLOYEE_ID
                            WHERE T5.PLAN_ID IS NOT NULL OR T10.ATTENDANCE_DATE IS NOT NULL OR T7.DARK_CHECK_TIME IS NOT NULL
                        ) NOTP ON EE.EMPLOYEE_ID = NOTP.EMPLOYEE_ID ";
            }

            if (!empty($params['startDate']) && !empty($params['endDate'])) {
                $filterString = "";

                // <editor-fold defaultstate="collapsed" desc="Criteria">
                if (!empty($params['filterString'])) {
                    $filterString = " AND (LOWER(bl.FIRST_NAME) LIKE LOWER('%" . $params['filterString'] . "%') OR LOWER(bl.LAST_NAME) LIKE LOWER('%" . $params['filterString'] . "%') OR LOWER(EE.REGISTER_NUMBER) LIKE LOWER('%" . $params['filterString'] . "%') OR LOWER(EE.CODE) LIKE LOWER('%" . $params['filterString'] . "%'))";
                }

                if (!empty($params['departmentId'])) {
                    $departmentIds = implode(',', $params['departmentId']);
                    $where = " WHERE EE.DEPARTMENT_ID IN ( " . $departmentIds . ") ";
                }

                $filterRuleString = ' WHERE 1 = 1 ';

                $causeString = '';
                $causeString1 = '';
                $causeString2 = ' AND (';
                $causeString3 = '';

                if (isset($params['causeTypeId'])) {
                    $causeString .= 'AND (';

                    foreach ($params['causeTypeId'] as $k => $row) {
                        if ($row == '0') {
                            break;
                        } elseif ($row == 'inTime') {
                            $causeString .= ($causeString === 'AND (') ? '(AA.START_TIME IS NULL OR SB.IS_FAULT = 1) ' : '';
                        } elseif ($row == 'outTime') {
                            $causeString .= ($causeString === 'AND (') ? '(AA.END_TIME IS NULL OR SB.IS_FAULT = 1) ' : 'OR (AA.END_TIME IS NULL OR SB.IS_FAULT = 1)';
                        } elseif ($row == 'isConfirm') {
                            $filterRuleString .= ' AND AA.IS_CONFIRMED = 1 ';
                        } elseif ($row == 'isUnConfirm') {
                            $filterRuleString .= " AND STATUS = 'Зөрчилтэй' ";
                        } elseif ($row == '1') {
                            $filterRuleString .= " AND STATUS = 'Зөрчилгүй' ";
                        } else {
                            $causeString2 .= ($causeString2 === ' AND (') ? ' (AA.CAUSE'.$row.' IS NOT NULL AND AA.CAUSE'.$row.' <> 0)' : ' OR (AA.CAUSE'.$row.' IS NOT NULL AND AA.CAUSE'.$row.' <> 0)';
                        }
                    }

                    $causeString .= ')';
                    $causeString2 .= ')';
                    if ($causeString === 'AND ()') {
                        $causeString = '';
                    }
                    if ($causeString2 === ' AND ()') {
                        $causeString2 = '';
                    }
                    $causeString1 .= $causeString2;
                }                    

                if (Input::postCheck('filterRules')) {
                    $filterRules = json_decode(Str::cp1251_utf8($_POST['filterRules']), true);

                    if (count($filterRules) > 0) {

                        foreach ($filterRules as $rule) {
                            $ruleValue = Input::param(Str::lower(trim($rule['value'])));
                            switch ($rule['field']) {
                                case 'CLEAR_TIME':
                                case 'DEFFERENCE_TIME':
                                    $ruleValue = explode(':', $ruleValue);
                                    $ruleValue = (float) $ruleValue[0] + (float) $ruleValue[1] / 60;
                                    $filterRuleString .= ' AND ' . $rule['field'] . " = '" . $ruleValue . "'";
                                    break;
                                case 'STATUS_TEXT':
                                    $statusText = $ruleValue;
                                    break;
                                default:
                                    $filterRuleString .= ' AND LOWER(' . $rule['field'] . ") LIKE LOWER('%" . $ruleValue . "%')";
                                    break;
                            }
                        }
                    }
                }

                if (isset($params['stringValue'])) {
                    if (empty($params['stringValue']) === false && $params['stringValue'] != '') {
                        $filterRuleString .= " AND (LOWER(LAST_NAME) LIKE LOWER('%" . $params['stringValue'] . "%') OR LOWER(FIRST_NAME) LIKE LOWER('%" . $params['stringValue'] . "%')) ";
                    }
                }

                $sortField = 'FIRST_NAME, BALANCE_DATE';
                $sortOrder = 'ASC';
                if (Input::postCheck('sort') && Input::postCheck('order')) {
                    $sortField = Input::post('sort');
                    $sortOrder = Input::post('order');
                }

                $whereSql2 = $causeString . $causeString1;
                $whereSql = $where . $filterString;
                // </editor-fold>

                // <editor-fold defaultstate="collapsed" desc="Page Count SQL">
                $selectCount = "SELECT COUNT(EMPLOYEE_ID) 
                                FROM (
                                    SELECT 
                                        EMPLOYEE_ID,
                                        EMPLOYEE_KEY_ID ,
                                        PICTURE,
                                        POSITION_NAME,
                                        EMPLOYEE_NAME,
                                        LAST_NAME,
                                        FIRST_NAME,
                                        DEPARTMENT_NAME,
                                        EMPLOYEE_CODE,
                                        STATUS_NAME,
                                        DEPARTMENT_ID,
                                        CASE WHEN TO_CHAR(TO_DATE('" . $params['startDate'] . "', 'YYYY-MM-DD'), 'YYYYMM') = TO_CHAR(TO_DATE('" . $params['endDate'] . "', 'YYYY-MM-DD'), 'YYYYMM')
                                            THEN TO_CHAR(TO_DATE('" . $params['startDate'] . "', 'YYYY-MM-DD'), 'YYYY.MM')
                                            ELSE TO_CHAR(TO_DATE('" . $params['startDate'] . "', 'YYYY-MM-DD'), 'YYYY')||'.['||
                                                TO_CHAR(TO_DATE('" . $params['startDate'] . "', 'YYYY-MM-DD'), 'MM')||'...'||
                                                TO_CHAR(TO_DATE('" . $params['endDate'] . "', 'YYYY-MM-DD'), 'MM')||']'
                                        END AS BALANCE_DATE,
                                        CASE WHEN TO_CHAR(TO_DATE('" . $params['startDate'] . "', 'YYYY-MM'), 'YYYYMM') = TO_CHAR(TO_DATE('" . $params['endDate'] . "', 'YYYY-MM-DD'), 'YYYYMM')
                                            THEN TO_CHAR(TO_DATE('" . $params['startDate'] . "', 'YYYY-MM-DD'), 'YYYY/MM')||'-'||TO_CHAR(TO_DATE('" . $params['endDate'] . "', 'YYYY-MM-DD'), 'YYYY/MM')
                                            ELSE TO_CHAR(TO_DATE('" . $params['startDate'] . "', 'YYYY-MM-DD'), 'YYYY/MM')||'-'||TO_CHAR(TO_DATE('" . $params['endDate'] . "', 'YYYY-MM-DD'), 'YYYY/MM') END AS BALANCEDATE,    
                                        SUM(PLAN_TIME) AS PLAN_TIME,
                                        SUM(CLEAN_TIME)      AS CLEAR_TIME,
                                        SUM(LATE_TIME)      AS LATE_TIME,
                                        SUM(DEFFERENCE_TIME) AS DEFFERENCE_TIME,
                                        CASE
                                            WHEN SUM(DEFFERENCE_TIME) - SUM(PLAN_TIME) < 0 THEN 'Зөрчилтэй'
                                            WHEN SUM(DEFFERENCE_TIME) != 0 THEN 'Зөрчилтэй'
                                            ELSE 'Зөрчилгүй'
                                        END AS STATUS ,
                                        CASE
                                            WHEN SUM(DEFFERENCE_TIME) != 0 THEN '#f2dede'
                                            ELSE '#dff0d8'
                                        END AS BACKGROUND_COLOR,
                                        CASE
                                            WHEN SUM(DEFFERENCE_TIME) != 0
                                            THEN '#F00'
                                            ELSE '#000'
                                        END AS FONT_COLOR
                                    FROM ( 
                                        SELECT 
                                            DISTINCT 
                                            EE.EMPLOYEE_ID,
                                            EE.CODE             AS EMPLOYEE_CODE,
                                            EE.EMPLOYEE_KEY_ID  AS EMPLOYEE_KEY_ID,
                                            EE.EMPLOYEE_PICTURE AS PICTURE,
                                            EE.POSITION_NAME,
                                            EE.DEPARTMENT_NAME,
                                            SUBSTR(EE.LAST_NAME,1,2)||'.'||EE.FIRST_NAME||' ('||EE.CODE||')' AS EMPLOYEE_NAME,
                                            EE.STATUS_NAME,
                                            EE.DEPARTMENT_ID,
                                            EE.FIRST_NAME,
                                            EE.LAST_NAME,
                                            AA.CLEAN_TIME,
                                            AA.LATE_TIME,
                                            lj.PLAN_TIME,
                                            BB.WFM_STATUS_NAME,
                                            CASE
                                                WHEN AA.DEFFERENCE_TIME > 0 THEN AA.DEFFERENCE_TIME
                                                ELSE 0-AA.DEFFERENCE_TIME
                                            END DEFFERENCE_TIME,
                                            TO_CHAR(AA.BALANCE_DATE, 'MM')    AS BALANCE_DATE,
                                            TO_CHAR(AA.BALANCE_DATE, 'YYYY') AS BALANCE_YEAR
                                        FROM $VW_EMPLOYEE EE
                                        INNER JOIN HRM_EMPLOYEE_KEY ek ON EE.EMPLOYEE_KEY_ID = ek.EMPLOYEE_KEY_ID
                                        LEFT JOIN TNA_TIME_BALANCE_HDR AA ON EE.EMPLOYEE_ID = AA.EMPLOYEE_ID AND AA.BALANCE_DATE >= TO_DATE('" . $params['startDate'] . "', 'YYYY-MM-DD') AND AA.BALANCE_DATE <=  TO_DATE('" . $params['endDate'] . "', 'YYYY-MM-DD')
                                        LEFT JOIN meta_wfm_status BB ON BB.ID = AA.WFM_STATUS_ID
                                        LEFT JOIN (
                                            SELECT 
                                                T0.EMPLOYEE_ID, 
                                                T0.EMPLOYEE_KEY_ID, 
                                                T1.PLAN_DATE, 
                                                T1.PLAN_ID 
                                            FROM  TMS_EMPLOYEE_TIME_PLAN_HDR T0
                                            INNER JOIN TMS_EMPLOYEE_TIME_PLAN_DTL T1 ON T0.ID = T1.TIME_PLAN_ID
                                            WHERE TO_CHAR(T1.PLAN_DATE, 'YYYY-MM-DD') BETWEEN '2017-08-01' AND  '2017-08-22'
                                        ) ETP ON AA.EMPLOYEE_ID = ETP.EMPLOYEE_ID  AND TO_CHAR(AA.BALANCE_DATE, 'yyyyMMdd') = TO_CHAR(ETP.PLAN_DATE, 'yyyyMMdd')
                                        LEFT JOIN (
                                              SELECT 
                                                  ROUND(FNC_GET_TMS_PLAN_TIME(TTP.PLAN_ID)/60, 2) AS PLAN_TIME,
                                                  TTP.PLAN_ID,
                                                  TTP.CODE,
                                                  TTP.NAME AS TYPE_NAME,
                                                  TTPDTL.START_TIME,
                                                  TTPDTL.END_TIME
                                              FROM TMS_TIME_PLAN TTP
                                              INNER JOIN TMS_TIME_PLAN_DEPARTMENT TTPD ON TTPD.PLAN_ID = TTP.PLAN_ID
                                              INNER JOIN (
                                                  SELECT 
                                                      PLAN_ID,
                                                      MIN(START_TIME) AS START_TIME ,
                                                      MAX(END_TIME)   AS END_TIME
                                                  FROM (
                                                      SELECT 
                                                          PLAN_ID,
                                                          PLAN_DETAIL_ID,
                                                          CASE
                                                              WHEN TO_CHAR(START_TIME, 'HH24') = '00'
                                                              THEN '24:' ||TO_CHAR(START_TIME, 'MI')
                                                              ELSE TO_CHAR(START_TIME, 'HH24:MI')
                                                          END AS START_TIME,
                                                          TO_CHAR(END_TIME, 'HH24:MI') AS END_TIME
                                                      FROM TMS_TIME_PLAN_DETAIL
                                                  ) GROUP BY PLAN_ID
                                              ) TTPDTL ON TTP.PLAN_ID = TTPDTL.PLAN_ID
                                        ) lj ON ETP.PLAN_ID = lj.PLAN_ID
                                        LEFT JOIN (
                                            SELECT DISTINCT EE.EMPLOYEE_ID AS EMPLOYEE_ID,
                                            EE.CODE AS EMPLOYEE_CODE,
                                            1 AS IS_FAULT,
                                            EE.EMPLOYEE_KEY_ID AS EMPLOYEE_KEY_ID,
                                            EE.EMPLOYEE_PICTURE AS PICTURE,
                                            EE.POSITION_NAME,
                                            EE.DEPARTMENT_NAME,
                                            SUBSTR(EE.LAST_NAME,1,2)||'.'||EE.FIRST_NAME||' ('||EE.CODE||')' AS EMPLOYEE_NAME,
                                            EE.STATUS_NAME,
                                            EE.DEPARTMENT_ID,
                                            EE.FIRST_NAME,
                                            EE.LAST_NAME,
                                            0 AS CLEAN_TIME,
                                            0 AS LATE_TIME,
                                            0 AS PLAN_TIME,
                                            '' AS WFM_STATUS_NAME, 
                                            0 AS DEFFERENCE_TIME,
                                            TO_CHAR(TO_DATE(T0.ATTENDANCE_DATE, 'YYYY-MM-DD'), 'MM')   AS BALANCE_DATE,
                                            TO_CHAR(TO_DATE(T0.ATTENDANCE_DATE, 'YYYY-MM-DD'), 'YYYY') AS BALANCE_YEAR                                                       
                                          FROM (
                                            SELECT TO_CHAR(TO_DATE('" . $params['startDate'] . "', 'YYYY-MM-DD') - 1 + LEVEL, 'YYYY-MM-DD') AS ATTENDANCE_DATE
                                            FROM DUAL
                                              CONNECT BY LEVEL <= TO_DATE('" . $params['endDate'] . "', 'YYYY-MM-DD') - TO_DATE('" . $params['startDate'] . "', 'YYYY-MM-DD') + 1
                                          ) T0
                                          LEFT JOIN $VW_EMPLOYEE EE ON EE.EMPLOYEE_KEY_ID = EE.EMPLOYEE_KEY_ID AND EE.EMPLOYEE_ID = EE.EMPLOYEE_ID
                                          LEFT JOIN (
                                            SELECT EMPLOYEE_ID, 
                                                   TO_CHAR(BALANCE_DATE, 'YYYY-MM-DD') AS BALANCE_DATE, 
                                                   '1' AS ICHECK, 
                                                   TIME_BALANCE_HDR_ID
                                            FROM TNA_TIME_BALANCE_HDR
                                          ) T6 ON T0.ATTENDANCE_DATE = T6.BALANCE_DATE AND T6.EMPLOYEE_ID = EE.EMPLOYEE_ID
                                          LEFT JOIN
                                            (SELECT T1.PLAN_ID,
                                              T1.PLAN_DATE,
                                              T0.EMPLOYEE_ID
                                            FROM TMS_EMPLOYEE_TIME_PLAN_HDR T0
                                            INNER JOIN TMS_EMPLOYEE_TIME_PLAN_DTL T1
                                            ON T0.ID                   = T1.TIME_PLAN_ID
                                            ) ETP ON EE.EMPLOYEE_ID = ETP.EMPLOYEE_ID AND T0.ATTENDANCE_DATE = TO_CHAR(ETP.PLAN_DATE, 'YYYY-MM-DD')    
                                          LEFT JOIN
                                          (SELECT ROUND(FNC_GET_TMS_PLAN_TIME(TTP.PLAN_ID)/60, 2) AS PLAN_TIME,
                                            TTP.PLAN_ID,
                                            TTP.CODE,
                                            TTP.NAME AS TYPE_NAME,
                                            TTPDTL.START_TIME,
                                            TTPDTL.END_TIME
                                          FROM TMS_TIME_PLAN TTP
                                          INNER JOIN TMS_TIME_PLAN_DEPARTMENT TTPD ON TTPD.PLAN_ID = TTP.PLAN_ID
                                          INNER JOIN
                                            (SELECT PLAN_ID,
                                              MIN(START_TIME) AS START_TIME ,
                                              MAX(END_TIME)   AS END_TIME
                                            FROM
                                              (SELECT PLAN_ID,
                                                PLAN_DETAIL_ID,
                                                CASE
                                                  WHEN TO_CHAR(START_TIME, 'HH24') = '00'
                                                  THEN '24:'
                                                    ||TO_CHAR(START_TIME, 'MI')
                                                  ELSE TO_CHAR(START_TIME, 'HH24:MI')
                                                END                          AS START_TIME,
                                                TO_CHAR(END_TIME, 'HH24:MI') AS END_TIME
                                              FROM TMS_TIME_PLAN_DETAIL
                                              )
                                            GROUP BY PLAN_ID
                                            ) TTPDTL
                                          ON TTP.PLAN_ID        = TTPDTL.PLAN_ID
                                          ) lj ON ETP.PLAN_ID   = lj.PLAN_ID
                                          " . $whereSql . " AND T6.ICHECK IS NULL AND ETP.PLAN_ID IS NOT NULL
                                        UNION
                                        SELECT DISTINCT EE.EMPLOYEE_ID AS EMPLOYEE_ID,
                                            EE.CODE AS EMPLOYEE_CODE,
                                            1 AS IS_FAULT,
                                            EE.EMPLOYEE_KEY_ID AS EMPLOYEE_KEY_ID,
                                            EE.EMPLOYEE_PICTURE AS PICTURE,
                                            EE.POSITION_NAME,
                                            EE.DEPARTMENT_NAME,
                                            SUBSTR(EE.LAST_NAME,1,2)||'.'||EE.FIRST_NAME||' ('||EE.CODE||')' AS EMPLOYEE_NAME,
                                            EE.STATUS_NAME,
                                            EE.DEPARTMENT_ID,
                                            EE.FIRST_NAME,
                                            EE.LAST_NAME,
                                            0 AS CLEAN_TIME,
                                            0 AS LATE_TIME,
                                            0 AS PLAN_TIME,
                                            '' AS WFM_STATUS_NAME, 
                                            0 AS DEFFERENCE_TIME,
                                            TO_CHAR(TO_DATE(T0.ATTENDANCE_DATE, 'YYYY-MM-DD'), 'MM')   AS BALANCE_DATE,
                                            TO_CHAR(TO_DATE(T0.ATTENDANCE_DATE, 'YYYY-MM-DD'), 'YYYY') AS BALANCE_YEAR                                                       
                                          FROM (
                                            SELECT 
                                                TO_CHAR(ATTENDANCE_DATE_TIME, 'YYYY-MM-DD') AS ATTENDANCE_DATE,
                                                TO_CHAR(ATTENDANCE_DATE_TIME, 'YYYY-MM-DD HH24:MI:SS') AS ATTENDANCE_DATE_TIME,
                                                EMPLOYEE_ID,
                                                EMPLOYEE_KEY_ID        
                                            FROM TNA_TIME_ATTENDANCE
                                          ) T0
                                          INNER JOIN $VW_EMPLOYEE EE ON T0.EMPLOYEE_KEY_ID = EE.EMPLOYEE_KEY_ID AND T0.EMPLOYEE_ID = EE.EMPLOYEE_ID
                                          INNER JOIN HRM_EMPLOYEE HE ON EE.EMPLOYEE_ID = HE.EMPLOYEE_ID  
                                          LEFT JOIN (
                                              SELECT
                                                  T1.PLAN_ID, T1.PLAN_DATE, T0.EMPLOYEE_ID
                                              FROM
                                                  TMS_EMPLOYEE_TIME_PLAN_HDR T0
                                              INNER JOIN TMS_EMPLOYEE_TIME_PLAN_DTL T1 ON T0.ID = T1.TIME_PLAN_ID
                                          ) T5 ON T0.EMPLOYEE_ID = T5.EMPLOYEE_ID AND T0.ATTENDANCE_DATE = TO_CHAR(T5.PLAN_DATE, 'YYYY-MM-DD')
                                          LEFT JOIN (
                                            SELECT 
                                                EMPLOYEE_ID, 
                                                TO_CHAR(BALANCE_DATE, 'YYYY-MM-DD') AS BALANCE_DATE,
                                                '1' AS ICHECK
                                            FROM TNA_TIME_BALANCE_HDR
                                          ) T6 ON T0.ATTENDANCE_DATE = T6.BALANCE_DATE AND T6.EMPLOYEE_ID = EE.EMPLOYEE_ID
                                          LEFT JOIN (            
                                                SELECT 
                                                  T2.PLAN_DURATION, 
                                                  TO_CHAR(T1.PLAN_DATE, 'YYYY-MM-DD') AS MAIN_DATE, 
                                                  TO_CHAR(T1.PLAN_DATE + T2.PLAN_DURATION, 'YYYY-MM-DD') AS PLAN_DATE
                                                FROM TMS_EMPLOYEE_TIME_PLAN_HDR T0 
                                                INNER JOIN TMS_EMPLOYEE_TIME_PLAN_DTL T1 ON T0.ID = T1.TIME_PLAN_ID
                                                INNER JOIN TMS_TIME_PLAN T2 ON T1.PLAN_ID = T2.PLAN_ID
                                          ) T7 ON T0.ATTENDANCE_DATE = T7.PLAN_DATE AND  T0.ATTENDANCE_DATE <> T7.MAIN_DATE
                                          " . $whereSql . " AND T6.ICHECK IS NULL AND T5.PLAN_ID IS NULL AND T0.ATTENDANCE_DATE BETWEEN '" . $params['startDate'] . "' AND '" . $params['endDate'] . "'
                                          ORDER BY FIRST_NAME ASC
                                        ) SB ON SB.EMPLOYEE_ID = EE.EMPLOYEE_ID                                            
                                        " . $whereSql . $whereSql2 . " 
                                        ORDER BY EE.FIRST_NAME ASC
                                )
                                WHERE 1 = 1
                                GROUP BY 
                                    EMPLOYEE_ID,
                                    EMPLOYEE_KEY_ID ,
                                    PICTURE,
                                    POSITION_NAME,
                                    EMPLOYEE_NAME,
                                    LAST_NAME,
                                    FIRST_NAME,
                                    EMPLOYEE_CODE,
                                    STATUS_NAME,
                                    DEPARTMENT_ID,
                                    BALANCE_YEAR,
                                    DEPARTMENT_NAME
                            ) TEMP $filterRuleString";
                // </editor-fold>

                $selectList = " SELECT *
                                FROM (
                                        SELECT 
                                            EMPLOYEE_ID,
                                            EMPLOYEE_KEY_ID ,
                                            PICTURE,
                                            POSITION_NAME,
                                            EMPLOYEE_NAME,
                                            LAST_NAME,
                                            FIRST_NAME,
                                            DEPARTMENT_NAME,
                                            EMPLOYEE_CODE,
                                            STATUS_NAME,
                                            DEPARTMENT_ID,
                                            CASE WHEN TO_CHAR(TO_DATE('" . $params['startDate'] . "', 'YYYY-MM-DD'), 'YYYYMM') = TO_CHAR(TO_DATE('" . $params['endDate'] . "', 'YYYY-MM-DD'), 'YYYYMM')
                                                THEN TO_CHAR(TO_DATE('" . $params['startDate'] . "', 'YYYY-MM-DD'), 'YYYY.MM')
                                                ELSE TO_CHAR(TO_DATE('" . $params['startDate'] . "', 'YYYY-MM-DD'), 'YYYY')||'.['||
                                                    TO_CHAR(TO_DATE('" . $params['startDate'] . "', 'YYYY-MM-DD'), 'MM')||'...'||
                                                    TO_CHAR(TO_DATE('" . $params['endDate'] . "', 'YYYY-MM-DD'), 'MM')||']'
                                            END AS BALANCE_DATE,
                                            CASE WHEN TO_CHAR(TO_DATE('" . $params['startDate'] . "', 'YYYY-MM'), 'YYYYMM') = TO_CHAR(TO_DATE('" . $params['endDate'] . "', 'YYYY-MM-DD'), 'YYYYMM')
                                                THEN TO_CHAR(TO_DATE('" . $params['startDate'] . "', 'YYYY-MM-DD'), 'YYYY/MM')||'-'||TO_CHAR(TO_DATE('" . $params['endDate'] . "', 'YYYY-MM-DD'), 'YYYY/MM')
                                                ELSE TO_CHAR(TO_DATE('" . $params['startDate'] . "', 'YYYY-MM-DD'), 'YYYY/MM')||'-'||TO_CHAR(TO_DATE('" . $params['endDate'] . "', 'YYYY-MM-DD'), 'YYYY/MM') END AS BALANCEDATE,    
                                            SUM(PLAN_TIME) AS PLAN_TIME,
                                            CLEAN_TIME AS CLEAR_TIME,
                                            LATE_TIME,
                                            DEFFERENCE_TIME,
                                            CASE
                                                WHEN (DEFFERENCE_TIME < 0) OR (IS_FAULT = 1) THEN 'Зөрчилтэй'
                                                ELSE 'Зөрчилгүй'
                                            END AS STATUS ,
                                            CASE
                                                WHEN (DEFFERENCE_TIME < 0) OR (IS_FAULT = 1) THEN '#f2dede'
                                                ELSE '#dff0d8'
                                            END AS BACKGROUND_COLOR,
                                            CASE
                                                WHEN (DEFFERENCE_TIME < 0) OR (IS_FAULT = 1) THEN '#F00'
                                                ELSE '#000'
                                            END AS FONT_COLOR,
                                            STATUS_ID
                                          FROM (
                                            SELECT 
                                                DISTINCT 
                                                EE.EMPLOYEE_ID,
                                                EE.CODE AS EMPLOYEE_CODE,
                                                SB.IS_FAULT,
                                                ETP.PLAN_ID,
                                                EE.EMPLOYEE_KEY_ID  AS EMPLOYEE_KEY_ID,
                                                EE.EMPLOYEE_PICTURE AS PICTURE,
                                                EE.POSITION_NAME,
                                                EE.DEPARTMENT_NAME,
                                                SUBSTR(EE.LAST_NAME,1,2)||'.'||EE.FIRST_NAME||' ('||EE.CODE||')' AS EMPLOYEE_NAME,
                                                EE.STATUS_NAME,
                                                EE.DEPARTMENT_ID,
                                                EE.FIRST_NAME,
                                                EE.LAST_NAME,
                                                AAA.CLEAN_TIME,
                                                AAA.LATE_TIME,
                                                lj.PLAN_TIME,
                                                BB.WFM_STATUS_NAME,
                                                AAA.DEFFERENCE_TIME,
                                                TO_CHAR(AA.BALANCE_DATE, 'MM')    AS BALANCE_DATE,
                                                TO_CHAR(AA.BALANCE_DATE, 'YYYY') AS BALANCE_YEAR,
                                                ek.STATUS_ID
                                            FROM $VW_EMPLOYEE EE
                                            INNER JOIN HRM_EMPLOYEE_KEY ek ON EE.EMPLOYEE_KEY_ID = ek.EMPLOYEE_KEY_ID
                                            LEFT JOIN TNA_TIME_BALANCE_HDR AA ON EE.EMPLOYEE_ID = AA.EMPLOYEE_ID AND AA.BALANCE_DATE >= TO_DATE('" . $params['startDate'] . "', 'YYYY-MM-DD') AND AA.BALANCE_DATE <=  TO_DATE('" . $params['endDate'] . "', 'YYYY-MM-DD')
                                            LEFT JOIN (
                                                SELECT EMPLOYEE_ID,
                                                    SUM(CLEAN_TIME) AS CLEAN_TIME, 
                                                    SUM(LATE_TIME) AS LATE_TIME, 
                                                    SUM(DEFFERENCE_TIME) AS DEFFERENCE_TIME
                                                FROM TNA_TIME_BALANCE_HDR
                                                WHERE BALANCE_DATE >= TO_DATE('" . $params['startDate'] . "', 'YYYY-MM-DD') AND BALANCE_DATE <=  TO_DATE('" . $params['endDate'] . "', 'YYYY-MM-DD')
                                                GROUP BY EMPLOYEE_ID
                                            ) AAA ON EE.EMPLOYEE_ID = AAA.EMPLOYEE_ID
                                            LEFT JOIN meta_wfm_status BB ON BB.ID = AA.WFM_STATUS_ID
                                            LEFT JOIN (
                                                SELECT 
                                                    T0.EMPLOYEE_ID, 
                                                    T0.EMPLOYEE_KEY_ID, 
                                                    T1.PLAN_DATE, 
                                                    T1.PLAN_ID 
                                                FROM  TMS_EMPLOYEE_TIME_PLAN_HDR T0
                                                INNER JOIN TMS_EMPLOYEE_TIME_PLAN_DTL T1 ON T0.ID = T1.TIME_PLAN_ID
                                            ) ETP ON AA.EMPLOYEE_ID = ETP.EMPLOYEE_ID  AND TO_CHAR(AA.BALANCE_DATE, 'yyyyMMdd') = TO_CHAR(ETP.PLAN_DATE, 'yyyyMMdd')
                                            LEFT JOIN (
                                                  SELECT 
                                                      ROUND(FNC_GET_TMS_PLAN_TIME(TTP.PLAN_ID)/60, 2) AS PLAN_TIME,
                                                      TTP.PLAN_ID,
                                                      TTP.CODE,
                                                      TTP.NAME AS TYPE_NAME,
                                                      TTPDTL.START_TIME,
                                                      TTPDTL.END_TIME
                                                  FROM TMS_TIME_PLAN TTP
                                                  INNER JOIN TMS_TIME_PLAN_DEPARTMENT TTPD ON TTPD.PLAN_ID = TTP.PLAN_ID
                                                  INNER JOIN (
                                                      SELECT 
                                                          PLAN_ID,
                                                          MIN(START_TIME) AS START_TIME ,
                                                          MAX(END_TIME)   AS END_TIME
                                                      FROM (
                                                          SELECT 
                                                              PLAN_ID,
                                                              PLAN_DETAIL_ID,
                                                              CASE
                                                                  WHEN TO_CHAR(START_TIME, 'HH24') = '00'
                                                                  THEN '24:' ||TO_CHAR(START_TIME, 'MI')
                                                                  ELSE TO_CHAR(START_TIME, 'HH24:MI')
                                                              END AS START_TIME,
                                                              TO_CHAR(END_TIME, 'HH24:MI') AS END_TIME
                                                          FROM TMS_TIME_PLAN_DETAIL
                                                      ) GROUP BY PLAN_ID
                                                  ) TTPDTL ON TTP.PLAN_ID = TTPDTL.PLAN_ID
                                            ) lj ON ETP.PLAN_ID = lj.PLAN_ID
                                            $joiner
                                            LEFT JOIN (
                                                SELECT DISTINCT EE.EMPLOYEE_ID AS EMPLOYEE_ID,
                                                EE.CODE AS EMPLOYEE_CODE,
                                                1 AS IS_FAULT,
                                                EE.EMPLOYEE_KEY_ID AS EMPLOYEE_KEY_ID,
                                                EE.EMPLOYEE_PICTURE AS PICTURE,
                                                EE.POSITION_NAME,
                                                EE.DEPARTMENT_NAME,
                                                SUBSTR(EE.LAST_NAME,1,2)||'.'||EE.FIRST_NAME||' ('||EE.CODE||')' AS EMPLOYEE_NAME,
                                                EE.STATUS_NAME,
                                                EE.DEPARTMENT_ID,
                                                EE.FIRST_NAME,
                                                EE.LAST_NAME,
                                                0 AS CLEAN_TIME,
                                                0 AS LATE_TIME,
                                                0 AS PLAN_TIME,
                                                '' AS WFM_STATUS_NAME, 
                                                0 AS DEFFERENCE_TIME,
                                                TO_CHAR(TO_DATE(T0.ATTENDANCE_DATE, 'YYYY-MM-DD'), 'MM')   AS BALANCE_DATE,
                                                TO_CHAR(TO_DATE(T0.ATTENDANCE_DATE, 'YYYY-MM-DD'), 'YYYY') AS BALANCE_YEAR                                                       
                                              FROM (
                                                SELECT TO_CHAR(TO_DATE('" . $params['startDate'] . "', 'YYYY-MM-DD') - 1 + LEVEL, 'YYYY-MM-DD') AS ATTENDANCE_DATE
                                                FROM DUAL
                                                  CONNECT BY LEVEL <= TO_DATE('" . $params['endDate'] . "', 'YYYY-MM-DD') - TO_DATE('" . $params['startDate'] . "', 'YYYY-MM-DD') + 1
                                              ) T0
                                              LEFT JOIN $VW_EMPLOYEE EE ON EE.EMPLOYEE_KEY_ID = EE.EMPLOYEE_KEY_ID AND EE.EMPLOYEE_ID = EE.EMPLOYEE_ID
                                              LEFT JOIN (
                                                SELECT EMPLOYEE_ID, 
                                                    TO_CHAR(BALANCE_DATE, 'YYYY-MM-DD') AS BALANCE_DATE, 
                                                    '1' AS ICHECK, 
                                                    TIME_BALANCE_HDR_ID
                                                FROM TNA_TIME_BALANCE_HDR
                                              ) T6 ON T0.ATTENDANCE_DATE = T6.BALANCE_DATE AND T6.EMPLOYEE_ID = EE.EMPLOYEE_ID
                                              LEFT JOIN
                                                (SELECT T1.PLAN_ID,
                                                  T1.PLAN_DATE,
                                                  T0.EMPLOYEE_ID,
                                                  T0.EMPLOYEE_KEY_ID
                                                FROM TMS_EMPLOYEE_TIME_PLAN_HDR T0
                                                INNER JOIN TMS_EMPLOYEE_TIME_PLAN_DTL T1
                                                ON T0.ID                   = T1.TIME_PLAN_ID
                                                ) ETP ON EE.EMPLOYEE_ID = ETP.EMPLOYEE_ID AND T0.ATTENDANCE_DATE = TO_CHAR(ETP.PLAN_DATE, 'YYYY-MM-DD')    
                                              LEFT JOIN
                                              (SELECT ROUND(FNC_GET_TMS_PLAN_TIME(TTP.PLAN_ID)/60, 2) AS PLAN_TIME,
                                                TTP.PLAN_ID,
                                                TTP.CODE,
                                                TTP.NAME AS TYPE_NAME,
                                                TTPDTL.START_TIME,
                                                TTPDTL.END_TIME
                                              FROM TMS_TIME_PLAN TTP
                                              INNER JOIN TMS_TIME_PLAN_DEPARTMENT TTPD ON TTPD.PLAN_ID = TTP.PLAN_ID
                                              INNER JOIN
                                                (SELECT PLAN_ID,
                                                  MIN(START_TIME) AS START_TIME ,
                                                  MAX(END_TIME)   AS END_TIME
                                                FROM
                                                  (SELECT PLAN_ID,
                                                    PLAN_DETAIL_ID,
                                                    CASE
                                                      WHEN TO_CHAR(START_TIME, 'HH24') = '00'
                                                      THEN '24:'||TO_CHAR(START_TIME, 'MI')
                                                      ELSE TO_CHAR(START_TIME, 'HH24:MI')
                                                    END                          AS START_TIME,
                                                    TO_CHAR(END_TIME, 'HH24:MI') AS END_TIME
                                                  FROM TMS_TIME_PLAN_DETAIL
                                                  )
                                                GROUP BY PLAN_ID
                                                ) TTPDTL
                                              ON TTP.PLAN_ID        = TTPDTL.PLAN_ID
                                              ) lj ON ETP.PLAN_ID   = lj.PLAN_ID
                                              " . $whereSql . " AND T6.ICHECK IS NULL AND ETP.PLAN_ID IS NOT NULL
                                            UNION
                                            SELECT DISTINCT EE.EMPLOYEE_ID AS EMPLOYEE_ID,
                                                EE.CODE AS EMPLOYEE_CODE,
                                                1 AS IS_FAULT,
                                                EE.EMPLOYEE_KEY_ID AS EMPLOYEE_KEY_ID,
                                                EE.EMPLOYEE_PICTURE AS PICTURE,
                                                EE.POSITION_NAME,
                                                EE.DEPARTMENT_NAME,
                                                SUBSTR(EE.LAST_NAME,1,2)||'.'||EE.FIRST_NAME||' ('||EE.CODE||')' AS EMPLOYEE_NAME,
                                                EE.STATUS_NAME,
                                                EE.DEPARTMENT_ID,
                                                EE.FIRST_NAME,
                                                EE.LAST_NAME,
                                                0 AS CLEAN_TIME,
                                                0 AS LATE_TIME,
                                                0 AS PLAN_TIME,
                                                '' AS WFM_STATUS_NAME, 
                                                0 AS DEFFERENCE_TIME,
                                                TO_CHAR(TO_DATE(T0.ATTENDANCE_DATE, 'YYYY-MM-DD'), 'MM')   AS BALANCE_DATE,
                                                TO_CHAR(TO_DATE(T0.ATTENDANCE_DATE, 'YYYY-MM-DD'), 'YYYY') AS BALANCE_YEAR                                                       
                                              FROM (
                                                SELECT 
                                                    TO_CHAR(ATTENDANCE_DATE_TIME, 'YYYY-MM-DD') AS ATTENDANCE_DATE,
                                                    TO_CHAR(ATTENDANCE_DATE_TIME, 'YYYY-MM-DD HH24:MI:SS') AS ATTENDANCE_DATE_TIME,
                                                    EMPLOYEE_ID,
                                                    EMPLOYEE_KEY_ID
                                                FROM TNA_TIME_ATTENDANCE WHERE IS_REMOVED_NOT_PLAN IS NULL OR IS_REMOVED_NOT_PLAN != 1
                                              ) T0
                                              INNER JOIN $VW_EMPLOYEE EE ON T0.EMPLOYEE_KEY_ID = EE.EMPLOYEE_KEY_ID AND T0.EMPLOYEE_ID = EE.EMPLOYEE_ID
                                              INNER JOIN HRM_EMPLOYEE HE ON EE.EMPLOYEE_ID = HE.EMPLOYEE_ID  
                                              LEFT JOIN (
                                                  SELECT
                                                      T1.PLAN_ID, T1.PLAN_DATE, T0.EMPLOYEE_ID
                                                  FROM
                                                      TMS_EMPLOYEE_TIME_PLAN_HDR T0
                                                  INNER JOIN TMS_EMPLOYEE_TIME_PLAN_DTL T1 ON T0.ID = T1.TIME_PLAN_ID
                                              ) T5 ON T0.EMPLOYEE_ID = T5.EMPLOYEE_ID AND T0.ATTENDANCE_DATE = TO_CHAR(T5.PLAN_DATE, 'YYYY-MM-DD')
                                              LEFT JOIN (
                                                SELECT 
                                                    EMPLOYEE_ID, 
                                                    TO_CHAR(BALANCE_DATE, 'YYYY-MM-DD') AS BALANCE_DATE,
                                                    '1' AS ICHECK
                                                FROM TNA_TIME_BALANCE_HDR
                                              ) T6 ON T0.ATTENDANCE_DATE = T6.BALANCE_DATE AND T6.EMPLOYEE_ID = EE.EMPLOYEE_ID
                                              LEFT JOIN (            
                                                    SELECT 
                                                      T2.PLAN_DURATION, 
                                                      T1.PLAN_ID,
                                                      TO_CHAR(T1.PLAN_DATE, 'YYYY-MM-DD') AS MAIN_DATE, 
                                                      TO_CHAR(T1.PLAN_DATE + T2.PLAN_DURATION, 'YYYY-MM-DD') AS PLAN_DATE
                                                    FROM TMS_EMPLOYEE_TIME_PLAN_HDR T0 
                                                    INNER JOIN TMS_EMPLOYEE_TIME_PLAN_DTL T1 ON T0.ID = T1.TIME_PLAN_ID
                                                    INNER JOIN TMS_TIME_PLAN T2 ON T1.PLAN_ID = T2.PLAN_ID
                                              ) T7 ON T0.ATTENDANCE_DATE = T7.PLAN_DATE AND  T0.ATTENDANCE_DATE <> T7.MAIN_DATE
                                              " . $whereSql . " AND T6.ICHECK IS NULL AND T7.PLAN_ID IS NULL AND T0.ATTENDANCE_DATE BETWEEN '" . $params['startDate'] . "' AND '" . $params['endDate'] . "'
                                              ORDER BY FIRST_NAME ASC
                                            ) SB ON SB.EMPLOYEE_ID = EE.EMPLOYEE_ID
                                            " . $whereSql . $whereSql2 . " 
                                            ORDER BY FIRST_NAME ASC
                                        )
                                        WHERE 1 = 1".(Config::getFromCache('CONFIG_TNA_SOYOL') ? " AND STATUS_ID <> 41" : "") . "
                                        GROUP BY 
                                            EMPLOYEE_ID,
                                            EMPLOYEE_KEY_ID ,
                                            PICTURE,
                                            POSITION_NAME,
                                            EMPLOYEE_NAME,
                                            LAST_NAME,
                                            FIRST_NAME,
                                            EMPLOYEE_CODE,
                                            STATUS_NAME,
                                            DEPARTMENT_ID,
                                            DEPARTMENT_NAME,
                                            IS_FAULT,
                                            CLEAN_TIME,
                                            LATE_TIME,
                                            DEFFERENCE_TIME,
                                            STATUS_ID,
                                            BALANCE_YEAR
                                ) TEMP $filterRuleString ORDER BY $sortField $sortOrder";
//                    echo $selectList; die;
//                    print "<pre>";
//                    print($selectList); 
//                    print "</pre>";
//                    die;
                $rs = $this->db->SelectLimit($selectList, $rows, $offset);
                $result["rows"]  = isset($rs->_array) ? $rs->_array : array();
                $result['total'] = $this->db->GetOne($selectCount);
                return $result;
            }
        }

        return $result;
    }

    public function downloadDataModel($sync = false, $startDate = false, $endDate = false) {
        $singleEmployee = false;
        $notPlanDepartment = Config::getFromCache('tmsPlanTimeDefaultDepartment');
        $notPlanDepartment = $notPlanDepartmentArr = $notPlanDepartment ? explode(',', $notPlanDepartment) : array();
        $notPlanDepartment = '^' . join('^', $notPlanDepartment) . '^';        
        $joinNotPlan = -9999999999999;

        if(isset($_POST['balanceParam'])) {
            parse_str($_POST['balanceParam'], $params);            
            $notPlanArr = array();

            $params['departmentId'] = explode(',', $params['departmentId']);
            foreach($params['departmentId'] as $kd => $rowDep) {
                if(in_array($rowDep, $notPlanDepartmentArr)) {
                    array_push($notPlanArr, $rowDep);
                    unset($params['departmentId'][$kd]);
                }
            }

            $joinNotPlan = join(',' , $notPlanArr);      
            $joinNotPlan = empty($joinNotPlan) ? -9999999999999 : "SELECT EMPLOYEE_ID FROM VW_EMPLOYEE WHERE DEPARTMENT_ID IN ($joinNotPlan)";

            $join = join(',' , $params['departmentId']);
            $join = empty($join) ? -9999999999999 : "SELECT EMPLOYEE_ID FROM VW_EMPLOYEE WHERE DEPARTMENT_ID IN ($join)";

        } elseif($sync) {
            $params['startDate'] = $startDate ? $startDate : Date::beforeDate('Y-m-d', '-1 day');
            $params['endDate'] = $endDate ? $endDate : Date::currentDate();
            $join = $joinNotPlan = "SELECT EMPLOYEE_ID FROM VW_EMPLOYEE";

        } else {
            $paramData = $_POST['paramData'];
            $singleEmployee = true;

            foreach($paramData as $pRow) {
                $params[$pRow['postParam']] = $pRow['value'];
                if($pRow['postParam'] == 'employeeId')
                    $join = $joinNotPlan = $pRow['value'];
            }
        }

        $startDate = Date::format('Ymd',$params['startDate']);
        $endDate = Date::format('Ymd',$params['endDate']);

        $year = Date::format('Y',$params['startDate']);
        $month = Date::format('m',$params['startDate']);

        $startMonth = Date::format('m', $params['startDate']);
        $endMonth = Date::format('m', $params['endDate']);

        (Array) $response = array('status' => 'warning', 'message' => 'Төлөвлөгөө үүсээгүй байна.');
        $nightTimeRange = $this->db->GetOne("SELECT VALUE FROM PR_CONFIG WHERE CONFIG_ID = 4");
        $nightStartDate = $nightEndDate = '00:00';

        $planTimeDefault = (float) Config::getFromCache('tmsPlanTimeDefault') * 60;
        $defaultDefferenceTime = (float) Config::getFromCache('tmsDefaultDefferenceTime');
        $defaultDefferenceTime = $defaultDefferenceTime ? $defaultDefferenceTime : -60;
        $customerConfig = Config::getFromCache('tmsIsGolomt');
        $customerConfig = $customerConfig ? $customerConfig : '0';
        $LATE_TYPE = Config::getFromCache('tmsLateType');
        $EARLY_TYPE = Config::getFromCache('tmsEarlyType');            
        $isPlanDownload = Config::getFromCache('tmsIsPlanDownload');       
        $isPlanDownload = $isPlanDownload == '' ? 1 : $isPlanDownload;
        $isEarlyTimeToClean = Config::getFromCache('tmsIsEarlyTimeToCleanTime');
        $isEarlyTimeToClean = $isEarlyTimeToClean ? $isEarlyTimeToClean : 0;            
        $isTmsNightShift = Config::getFromCache('tmsNightShift');
        $isTmsNightShift = $isTmsNightShift ? $isTmsNightShift : '05:00';     

        if ($nightTimeRange) {
            $nightTimeRange = explode('-', $nightTimeRange);

            $nightStartDate = $nightTimeRange[0];
            $nightEndDate = $nightTimeRange[1];                   
        }

        try {

            $plan = $this->db->GetAll(" SELECT 
                                            T1.PLAN_ID , MAX(T2.PLAN_DURATION) AS PLAN_DURATION, T2.IS_LATE
                                        FROM TMS_EMPLOYEE_TIME_PLAN_HDR T0
                                        INNER JOIN TMS_EMPLOYEE_TIME_PLAN_DTL T1 ON T0.ID = T1.TIME_PLAN_ID
                                        INNER JOIN TMS_TIME_PLAN T2 ON T1.PLAN_ID = T2.PLAN_ID
                                        WHERE T0.YEAR_ID = '$year' AND (T0.MONTH_ID = TO_CHAR('$startMonth') OR T0.MONTH_ID = TO_CHAR('$endMonth')) 
                                        AND TO_CHAR(T1.PLAN_DATE, 'YYYYMMDD') BETWEEN '$startDate' AND '$endDate' 
                                        AND T0.EMPLOYEE_ID IN ( " . $join . ")
                                        GROUP BY T1.PLAN_ID, T2.IS_LATE");

            /**
             * Нөхөж татах буюу гарсан цагаа дараагүй өгөгдлийг устгаж байна.
             */
            $this->db->Execute("DELETE FROM TNA_TIME_BALANCE_DTL WHERE TIME_BALANCE_HDR_ID IN (SELECT TIME_BALANCE_HDR_ID FROM TNA_TIME_BALANCE_HDR WHERE EMPLOYEE_ID IN (" . $join . ") AND IS_CONFIRMED = 0 AND TO_CHAR(BALANCE_DATE, 'YYYYMMDD') BETWEEN '$startDate' AND '$endDate')");
            $this->db->Execute("DELETE FROM TNA_TIME_BALANCE_HDR WHERE EMPLOYEE_ID IN (" . $join . ") AND IS_CONFIRMED = 0 AND TO_CHAR(BALANCE_DATE, 'YYYYMMDD') BETWEEN '$startDate' AND '$endDate'");

            if ($plan) {

                $planDurationEndDate = isset($plan[0]['PLAN_DURATION']) ? Date::nextDate($endDate, $plan[0]['PLAN_DURATION'], 'Ymd') : $endDate;

                $this->db->Execute("DELETE FROM TMS_ATTENDANCE  WHERE TO_CHAR(ATTENDANCE_DATE, 'YYYYMMDD') BETWEEN '$startDate' AND '$planDurationEndDate'" . ($singleEmployee ? ' AND EMPLOYEE_ID = ' . $join : ''));

                $this->db->Execute("INSERT INTO TMS_ATTENDANCE (ID, EMPLOYEE_ID, ATTENDANCE_DATE, ATTENDANCE_TIME)
                                    SELECT 
                                        IMPORT_ID_SEQ.NEXTVAL, 
                                        EMPLOYEE_ID, 
                                        ATTENDANCE_DATE_TIME,
                                        ATTENDANCE_DATE_TIME 
                                    FROM TNA_TIME_ATTENDANCE 
                                    WHERE TO_CHAR(ATTENDANCE_DATE_TIME, 'YYYYMMDD') BETWEEN '$startDate' AND '$planDurationEndDate'
                                        AND EMPLOYEE_ID IN ($join)");

                //$this->db->AutoExecute('TNA_TIME_ATTENDANCE', array('IS_REMOVED_NOT_PLAN' => NULL), 'UPDATE', "TO_CHAR(ATTENDANCE_DATE_TIME, 'YYYYMMDD') BETWEEN '$startDate' AND '$planDurationEndDate' AND EMPLOYEE_ID IN ($join)");

                // <editor-fold defaultstate="collapsed" desc="LOOP PLAN">
                foreach ($plan as $key => $row) {
                    $planTime = $this->db->GetOne("SELECT FNC_GET_TMS_PLAN_TIME('". $row['PLAN_ID'] ."') FROM DUAL");

                    $getPlanTime = $this->db->GetRow("SELECT 
                                                            T0.PLAN_ID,
                                                            T0.PLAN_DURATION,
                                                            T2.START_TIME,
                                                            T2.STARTTIME_LIMIT,
                                                            T2.START_TIME_END_TIME,
                                                            T3.END_TIME,
                                                            T3.ENDTIME_LIMIT,
                                                            T3.END_TIME_START_TIME                                         
                                                        FROM TMS_TIME_PLAN T0
                                                        INNER JOIN (
                                                            SELECT * FROM(
                                                                SELECT 
                                                                    T0.PLAN_ID, 
                                                                    TO_CHAR(T0.START_TIME, 'HH24:MI') AS START_TIME,
                                                                    CASE WHEN T0.STARTTIME_LIMIT IS NULL THEN
                                                                      TO_CHAR(T0.START_TIME, 'HH24:MI')
                                                                    ELSE TO_CHAR(T0.STARTTIME_LIMIT, 'HH24:MI')
                                                                    END AS STARTTIME_LIMIT,
                                                                    TO_CHAR(T0.END_TIME, 'HH24:MI') AS START_TIME_END_TIME,
                                                                    T0.ACC_TYPE
                                                                FROM TMS_TIME_PLAN_DETAIL T0
                                                                WHERE T0.PLAN_ID = ". $row['PLAN_ID'] ."
                                                                ORDER BY T0.ACC_TYPE ASC)
                                                            WHERE ROWNUM <= 1
                                                        ) T2 ON T0.PLAN_ID = T2.PLAN_ID
                                                        INNER JOIN (
                                                            SELECT * FROM(
                                                                SELECT 
                                                                    T0.PLAN_ID, 
                                                                    TO_CHAR(T0.END_TIME, 'HH24:MI') AS END_TIME,
                                                                    CASE WHEN T0.ENDTIME_LIMIT IS NULL THEN
                                                                      TO_CHAR(T0.END_TIME, 'HH24:MI')
                                                                    ELSE TO_CHAR(T0.ENDTIME_LIMIT, 'HH24:MI')
                                                                    END AS ENDTIME_LIMIT,
                                                                    TO_CHAR(T0.START_TIME, 'HH24:MI') AS END_TIME_START_TIME,
                                                                    T0.ACC_TYPE
                                                                FROM TMS_TIME_PLAN_DETAIL T0
                                                                WHERE T0.PLAN_ID = ". $row['PLAN_ID'] ."
                                                                ORDER BY T0.ACC_TYPE DESC)
                                                            WHERE ROWNUM <= 1
                                                        ) T3 ON T0.PLAN_ID = T3.PLAN_ID
                                                        WHERE T0.PLAN_ID = ". $row['PLAN_ID'] ."
                                                    GROUP BY T0.PLAN_ID,
                                                        T0.PLAN_DURATION,
                                                        T2.START_TIME,
                                                        T2.STARTTIME_LIMIT,
                                                        T2.START_TIME_END_TIME,
                                                        T3.END_TIME_START_TIME,
                                                        T3.ENDTIME_LIMIT,
                                                        T3.END_TIME");

                    $accType = $this->db->GetAll("SELECT 
                                                        T1.ACC_TYPE,
                                                        T0.PLAN_DURATION,
                                                        TO_CHAR(T1.START_TIME, 'HH24:MI') AS STARTTIME,
                                                        TO_CHAR(T1.END_TIME, 'HH24:MI') AS ENDTIME,
                                                        CASE WHEN T1.STARTTIME_LIMIT IS NULL THEN TO_CHAR(T1.START_TIME, 'HH24:MI') ELSE TO_CHAR(T1.STARTTIME_LIMIT, 'HH24:MI') END AS STARTTIME_LIMIT,
                                                        CASE WHEN T1.ENDTIME_LIMIT IS NULL THEN TO_CHAR(T1.END_TIME, 'HH24:MI') ELSE TO_CHAR(T1.ENDTIME_LIMIT, 'HH24:MI') END AS ENDTIME_LIMIT
                                                    FROM TMS_TIME_PLAN T0
                                                    INNER JOIN TMS_TIME_PLAN_DETAIL T1 ON T0.PLAN_ID = T1.PLAN_ID
                                                    WHERE T0.PLAN_ID = ". $row['PLAN_ID'] ."
                                                    ORDER BY T1.ACC_TYPE");

                    $lunchTimePlan1 = new DateTime($getPlanTime['START_TIME']);
                    $lunchTimePlan2 = new DateTime($getPlanTime['END_TIME']);
                    $hours = 24 * $getPlanTime['PLAN_DURATION'];
                    $lunchTimePlan2->modify("+$hours hours");

                    $intervalp = $lunchTimePlan2->diff($lunchTimePlan1);

                    $hour = $intervalp->format('%h');
                    $min = $intervalp->format('%i');
                    $second = $intervalp->format('%s');

                    $lunchTimePlan = (($hour * 60) + $min + ($second / 60)) - $planTime;  

                    $n1 = '01';  $n2 = '02';  $n3 = '03';  $n4 = '04';  
                    $n5 = '05';  $n6 = '06';  $n7 = '07';  $n8 = '08';  
                    $n9 = '09';  $n10 = '10'; $n11 = '11'; $n12 = '12'; 
                    $n13 = '13'; $n14 = '14'; $n15 = '15'; $n16 = '16';

                    $sizeOf = sizeOf($accType) - (int) 1;

                    if ($accType) {
                        foreach ($accType as $akey => $acc) {
                            $innerJoin = "  INNER JOIN TMS_EMPLOYEE_TIME_PLAN_HDR H ON A.EMPLOYEE_ID = H.EMPLOYEE_ID AND H.YEAR_ID||'-'||CASE WHEN H.MONTH_ID < 10 THEN '0'||H.MONTH_ID ELSE TO_CHAR(H.MONTH_ID) END = TO_CHAR(A.ATTENDANCE_DATE, 'YYYY-MM')
                                            INNER JOIN TMS_EMPLOYEE_TIME_PLAN_DTL B ON TO_CHAR(B.PLAN_DATE, 'YYYYMMDD') = TO_CHAR(A.ATTENDANCE_TIME, 'YYYYMMDD') AND B.TIME_PLAN_ID = H.ID
                                            INNER JOIN (
                                                SELECT DISTINCT
                                                    pd.PLAN_DETAIL_ID,
                                                    p.PLAN_ID, 
                                                    TO_CHAR(pd.START_TIME, 'HH24:MI') AS START_TIME, 
                                                    TO_CHAR(pd.END_TIME, 'HH24:MI') AS END_TIME ,
                                                    pd.ORDER_NUM, 
                                                    pd.IS_NIGHT_SHIFT,
                                                    p.PLAN_DURATION,
                                                    pd.STARTTIME_LIMIT,
                                                    pd.ENDTIME_LIMIT,
                                                    pd.ACC_TYPE
                                                FROM TMS_TIME_PLAN p 
                                                INNER JOIN TMS_TIME_PLAN_DETAIL pd on p.PLAN_ID = pd.PLAN_ID
                                                WHERE pd.ACC_TYPE = '". $acc['ACC_TYPE'] ."' AND p.PLAN_ID = ". $row['PLAN_ID'] ."
                                            ) C ON B.PLAN_ID =  C.PLAN_ID
                                            WHERE H.YEAR_ID = '$year' AND H.MONTH_ID = TO_CHAR('$month') AND TO_CHAR(A.ATTENDANCE_DATE, 'YYYYMMDD') BETWEEN '$startDate' AND '$planDurationEndDate'" . ($singleEmployee ? " AND A.EMPLOYEE_ID = " . $join : "") . " ";

                            $innerJoin1 = " INNER JOIN TMS_EMPLOYEE_TIME_PLAN_HDR H ON A.EMPLOYEE_ID = H.EMPLOYEE_ID AND H.YEAR_ID||'-'||CASE WHEN H.MONTH_ID < 10 THEN '0'||H.MONTH_ID ELSE TO_CHAR(H.MONTH_ID) END = TO_CHAR(A.ATTENDANCE_DATE, 'YYYY-MM')
                                            INNER JOIN TMS_EMPLOYEE_TIME_PLAN_DTL B ON TO_CHAR(B.PLAN_DATE+". $row['PLAN_DURATION'] .", 'YYYYMMDD') = TO_CHAR(A.ATTENDANCE_TIME, 'YYYYMMDD') AND B.TIME_PLAN_ID = H.ID
                                            INNER JOIN (
                                                SELECT DISTINCT
                                                    pd.PLAN_DETAIL_ID,
                                                    p.PLAN_ID, 
                                                    TO_CHAR(pd.START_TIME, 'HH24:MI') AS START_TIME, 
                                                    TO_CHAR(pd.END_TIME, 'HH24:MI') AS END_TIME ,
                                                    pd.ORDER_NUM, 
                                                    pd.IS_NIGHT_SHIFT,
                                                    p.PLAN_DURATION,
                                                    pd.STARTTIME_LIMIT,
                                                    pd.ENDTIME_LIMIT,
                                                    pd.ACC_TYPE
                                                FROM TMS_TIME_PLAN p 
                                                INNER JOIN TMS_TIME_PLAN_DETAIL pd on p.PLAN_ID = pd.PLAN_ID
                                                WHERE pd.ACC_TYPE = '". $acc['ACC_TYPE'] ."' AND p.PLAN_ID = '". $row['PLAN_ID'] ."'
                                            ) C ON B.PLAN_ID =  C.PLAN_ID
                                            WHERE H.YEAR_ID = '$year' AND H.MONTH_ID = TO_CHAR('$month') AND TO_CHAR(A.ATTENDANCE_DATE, 'YYYYMMDD') BETWEEN '$startDate' AND '$planDurationEndDate'" . ($singleEmployee ? " AND A.EMPLOYEE_ID = " . $join : "") . " ";

                            $check = $this->db->GetRow("SELECT 
                                                            DISTINCT 
                                                            A.ID,
                                                            A.EMPLOYEE_ID, 
                                                            C.START_TIME AS START_TIME,
                                                            C.END_TIME AS END_TIME,
                                                            C.PLAN_ID, 
                                                            TO_CHAR(B.PLAN_DATE, 'YYYY-MM-DD')  AS PLAN_DATE
                                                        FROM TMS_ATTENDANCE A
                                                            $innerJoin ");

                            if ($check) {
                                $resultMerge1 = $this->db->Execute(
                                        "MERGE
                                            INTO TMS_ATTENDANCE trg
                                            USING   ( 
                                                SELECT DISTINCT
                                                    T1.ACC_TYPE,
                                                    T0.PLAN_DURATION,
                                                    TO_CHAR(T1.START_TIME, 'HH24:MI') AS STARTTIME,
                                                    TO_CHAR(T1.END_TIME, 'HH24:MI') AS ENDTIME,
                                                    CASE WHEN T1.STARTTIME_LIMIT IS NULL THEN TO_CHAR(T1.START_TIME, 'HH24:MI') ELSE TO_CHAR(T1.STARTTIME_LIMIT, 'HH24:MI') END AS STARTTIME_LIMIT,
                                                    CASE WHEN T1.ENDTIME_LIMIT IS NULL THEN TO_CHAR(T1.END_TIME, 'HH24:MI') ELSE TO_CHAR(T1.ENDTIME_LIMIT, 'HH24:MI') END AS ENDTIME_LIMIT,
                                                    TO_CHAR(T2.PLAN_DATE, 'YYYY-MM-DD') AS PLAN_DATE, 
                                                    TO_CHAR(T2.PLAN_DATE + T0.PLAN_DURATION, 'YYYY-MM-DD') AS PLAN_ENDDATE,
                                                    T3.EMPLOYEE_ID
                                                FROM TMS_TIME_PLAN T0
                                                    INNER JOIN TMS_TIME_PLAN_DETAIL T1 ON T0.PLAN_ID = T1.PLAN_ID
                                                    INNER JOIN TMS_EMPLOYEE_TIME_PLAN_DTL T2 ON T1.PLAN_ID = T2.PLAN_ID
                                                    INNER JOIN TMS_EMPLOYEE_TIME_PLAN_HDR T3 ON T2.TIME_PLAN_ID = T3.ID
                                                    WHERE T1.ACC_TYPE = '". $acc['ACC_TYPE'] ."' AND T0.PLAN_ID = ". $row['PLAN_ID'] ."" . ($singleEmployee ? " AND T3.EMPLOYEE_ID = " . $join : "") . "
                                            ) src ON (trg.EMPLOYEE_ID = src.EMPLOYEE_ID AND TO_CHAR(trg.ATTENDANCE_DATE , 'YYYY-MM-DD')= src.PLAN_DATE) 
                                            WHEN MATCHED THEN UPDATE
                                            SET trg.S0$n1 = src.STARTTIME_LIMIT, 
                                                trg.S0$n9 = src.ENDTIME_LIMIT, 
                                                trg.S0$n6 = src.STARTTIME,  
                                                trg.S0$n14 = src.ENDTIME");

                                if ((int) $getPlanTime['PLAN_DURATION'] > 0) {
                                    $resultMerge1 = $this->db->Execute(
                                                    "MERGE
                                                        INTO tms_attendance trg
                                                        USING   ( 
                                                                SELECT DISTINCT
                                                                    T1.ACC_TYPE,
                                                                    T0.PLAN_DURATION,
                                                                    TO_CHAR(T1.START_TIME, 'HH24:MI') AS STARTTIME,
                                                                    TO_CHAR(T1.END_TIME, 'HH24:MI') AS ENDTIME,
                                                                    CASE WHEN T1.STARTTIME_LIMIT IS NULL THEN TO_CHAR(T1.START_TIME, 'HH24:MI') ELSE TO_CHAR(T1.STARTTIME_LIMIT, 'HH24:MI') END AS STARTTIME_LIMIT,
                                                                    CASE WHEN T1.ENDTIME_LIMIT IS NULL THEN TO_CHAR(T1.END_TIME, 'HH24:MI') ELSE TO_CHAR(T1.ENDTIME_LIMIT, 'HH24:MI') END AS ENDTIME_LIMIT,
                                                                    TO_CHAR(T2.PLAN_DATE, 'YYYY-MM-DD') AS PLAN_DATE,
                                                                    TO_CHAR(T2.PLAN_DATE + T0.PLAN_DURATION, 'YYYY-MM-DD') AS PLAN_ENDDATE,
                                                                    T3.EMPLOYEE_ID
                                                                FROM TMS_TIME_PLAN T0
                                                                    INNER JOIN TMS_TIME_PLAN_DETAIL T1 ON T0.PLAN_ID    = T1.PLAN_ID
                                                                    INNER JOIN TMS_EMPLOYEE_TIME_PLAN_DTL T2 ON T1.PLAN_ID = T2.PLAN_ID
                                                                    INNER JOIN TMS_EMPLOYEE_TIME_PLAN_HDR T3 ON T2.TIME_PLAN_ID = T3.ID
                                                                    WHERE T1.ACC_TYPE = '". $acc['ACC_TYPE'] ."' AND T0.PLAN_ID = ". $row['PLAN_ID'] ."" . ($singleEmployee ? " AND T3.EMPLOYEE_ID = " . $join : "") . "
                                                        ) src ON (trg.EMPLOYEE_ID = src.EMPLOYEE_ID AND TO_CHAR(trg.ATTENDANCE_DATE , 'YYYY-MM-DD')= src.PLAN_ENDDATE) 
                                                        WHEN MATCHED THEN UPDATE
                                                        SET trg.S0$n1 = src.STARTTIME_LIMIT, 
                                                                trg.S0$n9 = src.ENDTIME_LIMIT, 
                                                                trg.S0$n6 = src.STARTTIME, 
                                                                trg.S0$n14 = src.ENDTIME");
                                }

                                if ($resultMerge1) {
                                    $resultMerge2 = $this->db->Execute("MERGE
                                                                        INTO tms_attendance trg
                                                                        USING   ( 
                                                                            SELECT
                                                                            T.ID,
                                                                            MIN(STARTTIME_DIFF) AS STARTTIME_DIFF,
                                                                            MIN(ENDTIME_DIFF) AS ENDTIME_DIFF
                                                                          FROM (                                                                                
                                                                           SELECT DISTINCT
                                                                                A.ID,
                                                                                ROUND(DATEDIFF(
                                                                                    TO_DATE(TO_CHAR(A.ATTENDANCE_TIME, 'YYYY-MM-DD HH24:MI'), 'YYYY-MM-DD HH24:MI'),                                                                                    
                                                                                    TO_DATE(TO_CHAR(A.ATTENDANCE_TIME, 'YYYY-MM-DD ')||CASE WHEN '". $row['PLAN_DURATION'] ."' != '0' THEN '". $acc['STARTTIME_LIMIT'] ."' ELSE '". $acc['STARTTIME_LIMIT'] ."' END, 'YYYY-MM-DD HH24:MI') 
                                                                                )) / 60 AS STARTTIME_DIFF,
                                                                                ROUND(DATEDIFF(
                                                                                    TO_DATE(TO_CHAR(A.ATTENDANCE_TIME, 'YYYY-MM-DD ')||CASE WHEN '". $row['PLAN_DURATION'] ."' != '0' THEN '". $acc['ENDTIME_LIMIT'] ."' ELSE '". $acc['ENDTIME_LIMIT'] ."' END , 'YYYY-MM-DD HH24:MI'),
                                                                                    TO_DATE(TO_CHAR(A.ATTENDANCE_TIME, 'YYYY-MM-DD HH24:MI'), 'YYYY-MM-DD HH24:MI')
                                                                                )) / 60 AS ENDTIME_DIFF
                                                                            FROM TMS_ATTENDANCE A
                                                                            $innerJoin
                                                                            ) T
                                                                            GROUP BY ID                                                                                
                                                                        ) src ON (trg.ID = src.ID)
                                                                        WHEN MATCHED THEN UPDATE
                                                                    SET  trg.S0$n2 = src.STARTTIME_DIFF, trg.S0$n10 = src.ENDTIME_DIFF");

                                    if ((int) $getPlanTime['PLAN_DURATION'] > 0) {                                 
                                        $resultMerge2 = $this->db->Execute("MERGE
                                                                        INTO tms_attendance trg
                                                                        USING   ( 
                                                                            SELECT
                                                                            T.ID,
                                                                            MIN(STARTTIME_DIFF) AS STARTTIME_DIFF,
                                                                            MIN(ENDTIME_DIFF) AS ENDTIME_DIFF
                                                                          FROM (                                                                                  
                                                                           SELECT DISTINCT
                                                                                A.ID,
                                                                                ROUND(DATEDIFF(
                                                                                    TO_DATE(TO_CHAR(A.ATTENDANCE_TIME, 'YYYY-MM-DD HH24:MI'), 'YYYY-MM-DD HH24:MI'),                                                                                    
                                                                                    TO_DATE(TO_CHAR(A.ATTENDANCE_TIME, 'YYYY-MM-DD ')||CASE WHEN '". $row['PLAN_DURATION'] ."' != '0' THEN '". $acc['STARTTIME_LIMIT'] ."' ELSE '". $acc['STARTTIME_LIMIT'] ."' END, 'YYYY-MM-DD HH24:MI')
                                                                                )) / 60 AS STARTTIME_DIFF,
                                                                                ROUND(DATEDIFF(
                                                                                    TO_DATE(TO_CHAR(A.ATTENDANCE_TIME, 'YYYY-MM-DD ')||CASE WHEN '". $row['PLAN_DURATION'] ."' != '0' THEN '". $acc['ENDTIME_LIMIT'] ."' ELSE '". $acc['ENDTIME_LIMIT'] ."' END , 'YYYY-MM-DD HH24:MI'),
                                                                                    TO_DATE(TO_CHAR(A.ATTENDANCE_TIME, 'YYYY-MM-DD HH24:MI'), 'YYYY-MM-DD HH24:MI')
                                                                                )) / 60 AS ENDTIME_DIFF
                                                                            FROM TMS_ATTENDANCE A
                                                                            $innerJoin1
                                                                            ) T
                                                                            GROUP BY ID                                                                                  
                                                                        ) src ON (trg.ID = src.ID)
                                                                        WHEN MATCHED THEN UPDATE
                                                                    SET  trg.S0$n2 = src.STARTTIME_DIFF, trg.S0$n10 = src.ENDTIME_DIFF");
                                    }

                                    if ($resultMerge2) {
                                        $this->db->Execute("MERGE
                                                                INTO TMS_ATTENDANCE TRG  
                                                                USING ( 
                                                                SELECT DISTINCT
                                                                    MAX(ID) AS ID,
                                                                    T0.EMPLOYEE_ID,
                                                                    T1.ATTENDANCE_DATE
                                                                    FROM TMS_ATTENDANCE T0
                                                                    INNER JOIN (
                                                                        SELECT 
                                                                            T0.EMPLOYEE_ID, 
                                                                            T0.ATTENDANCE_DATE, 
                                                                            CASE WHEN '". $row['PLAN_DURATION'] ."' != '0' 
                                                                                THEN MAX(S0$n2) 
                                                                                ELSE MAX(S0$n2) END AS S0$n2
                                                                        FROM (
                                                                            SELECT A.EMPLOYEE_ID,
                                                                                TO_CHAR(A.ATTENDANCE_DATE, 'YYYY-MM-DD') AS ATTENDANCE_DATE,
                                                                                A.S0$n2
                                                                            FROM TMS_ATTENDANCE A
                                                                            $innerJoin
                                                                            AND A.ATTENDANCE_DATE >= TO_DATE(TO_CHAR(A.ATTENDANCE_DATE, 'YYYY-MM-DD')||' '||'".$isTmsNightShift."', 'YYYY-MM-DD HH24:MI')
                                                                        ) T0
                                                                      GROUP BY T0.EMPLOYEE_ID, T0.ATTENDANCE_DATE 
                                                                    ) T1 ON T0.EMPLOYEE_ID = T1.EMPLOYEE_ID AND T0.S0$n2 = T1.S0$n2 AND TO_CHAR(T0.ATTENDANCE_DATE, 'YYYY-MM-DD') = T1.ATTENDANCE_DATE
                                                                    GROUP BY T0.EMPLOYEE_ID, T1.ATTENDANCE_DATE
                                                                ) src ON (trg.ID = src.ID) 
                                                            WHEN MATCHED THEN UPDATE
                                                            SET trg.S0$n3 = 1, trg.S0$n4 = 1, trg.S0$n5 = TO_CHAR(trg.ATTENDANCE_TIME, 'HH24:MI')");

                                        $this->db->Execute("MERGE
                                                                INTO TMS_ATTENDANCE trg  
                                                                USING   (
                                                                    SELECT DISTINCT
                                                                    MAX(ID) AS ID,
                                                                    T0.EMPLOYEE_ID,
                                                                    T1.ATTENDANCE_DATE
                                                                    FROM TMS_ATTENDANCE T0
                                                                    INNER JOIN (
                                                                        SELECT 
                                                                            T0.EMPLOYEE_ID, 
                                                                            T0.ATTENDANCE_DATE, 
                                                                            CASE WHEN '". $row['PLAN_DURATION'] ."' != '0' 
                                                                                THEN MAX(S0$n10) 
                                                                                ELSE MAX(S0$n10) END AS S0$n10
                                                                        FROM (
                                                                            SELECT 
                                                                                A.EMPLOYEE_ID,
                                                                                TO_CHAR(A.ATTENDANCE_DATE, 'YYYY-MM-DD') AS ATTENDANCE_DATE,
                                                                                A.S0$n10
                                                                            FROM TMS_ATTENDANCE A
                                                                            $innerJoin
                                                                        ) T0
                                                                        GROUP BY T0.EMPLOYEE_ID, T0.ATTENDANCE_DATE 
                                                                    ) T1 ON T0.EMPLOYEE_ID = T1.EMPLOYEE_ID AND T0.S0$n10 = T1.S0$n10 AND TO_CHAR(T0.ATTENDANCE_DATE, 'YYYY-MM-DD') = T1.ATTENDANCE_DATE
                                                                    GROUP BY T0.EMPLOYEE_ID, T1.ATTENDANCE_DATE
                                                                ) src ON (trg.ID = src.ID) 
                                                            WHEN MATCHED THEN UPDATE
                                                            SET trg.S0$n11 = 1, trg.S0$n12 = 1, trg.S0$n13 = TO_CHAR(trg.ATTENDANCE_TIME, 'HH24:MI')");

                                        $this->db->Execute("MERGE
                                                                INTO TMS_ATTENDANCE trg  
                                                                USING   (
                                                                    SELECT DISTINCT
                                                                    MAX(ID) AS ID,
                                                                    T0.EMPLOYEE_ID,
                                                                    T1.ATTENDANCE_DATE
                                                                    FROM TMS_ATTENDANCE T0
                                                                    INNER JOIN (
                                                                        SELECT 
                                                                            T0.EMPLOYEE_ID, 
                                                                            T0.ATTENDANCE_DATE, 
                                                                            CASE WHEN '1' != '0' 
                                                                                THEN MIN(S0$n10) 
                                                                                ELSE MAX(S0$n10) END AS S0$n10
                                                                        FROM (
                                                                            SELECT 
                                                                                A.EMPLOYEE_ID,
                                                                                TO_CHAR(A.ATTENDANCE_DATE, 'YYYY-MM-DD') AS ATTENDANCE_DATE,
                                                                                A.S0$n10
                                                                            FROM TMS_ATTENDANCE A
                                                                            $innerJoin
                                                                        ) T0
                                                                        GROUP BY T0.EMPLOYEE_ID, T0.ATTENDANCE_DATE 
                                                                    ) T1 ON T0.EMPLOYEE_ID = T1.EMPLOYEE_ID AND T0.S0$n10 = T1.S0$n10 AND TO_CHAR(T0.ATTENDANCE_DATE, 'YYYY-MM-DD') = T1.ATTENDANCE_DATE
                                                                    GROUP BY T0.EMPLOYEE_ID, T1.ATTENDANCE_DATE
                                                                ) src ON (trg.ID = src.ID) 
                                                            WHEN MATCHED THEN UPDATE
                                                            SET trg.S0$n11 = 1, trg.S0$n12 = 1, trg.S0$n15 = TO_CHAR(trg.ATTENDANCE_TIME, 'HH24:MI')");

                                                            if ((int) $getPlanTime['PLAN_DURATION'] > 0) {
                                                                $this->db->Execute("MERGE
                                                                INTO tms_attendance trg  
                                                                USING ( 
                                                                    SELECT DISTINCT
                                                                        MAX(ID) AS ID,
                                                                        T0.EMPLOYEE_ID,
                                                                        T1.ATTENDANCE_DATE
                                                                        FROM TMS_ATTENDANCE T0
                                                                        INNER JOIN (
                                                                            SELECT 
                                                                                T0.EMPLOYEE_ID, 
                                                                                T0.ATTENDANCE_DATE, 
                                                                                CASE WHEN '". $row['PLAN_DURATION'] ."' != '0' 
                                                                                        THEN MIN(S0$n2) 
                                                                                        ELSE MAX(S0$n2) END AS S0$n2
                                                                            FROM (
                                                                                SELECT A.EMPLOYEE_ID,
                                                                                        TO_CHAR(A.ATTENDANCE_DATE, 'YYYY-MM-DD') AS ATTENDANCE_DATE,
                                                                                        A.S0$n2
                                                                                FROM TMS_ATTENDANCE A
                                                                                $innerJoin1
                                                                            ) T0
                                                                          GROUP BY T0.EMPLOYEE_ID, T0.ATTENDANCE_DATE 
                                                                        ) T1 ON T0.EMPLOYEE_ID = T1.EMPLOYEE_ID AND T0.S0$n2 = T1.S0$n2 AND TO_CHAR(T0.ATTENDANCE_DATE, 'YYYY-MM-DD') = T1.ATTENDANCE_DATE
                                                                        GROUP BY T0.EMPLOYEE_ID, T1.ATTENDANCE_DATE
                                                                    ) src ON (trg.ID = src.ID) 
                                                                WHEN MATCHED THEN UPDATE
                                                                SET trg.S0$n3 = 1, trg.S0$n4 = 1, trg.S0$n5 = TO_CHAR(trg.ATTENDANCE_TIME, 'HH24:MI')");

                                                                $this->db->Execute("MERGE
                                                                            INTO TMS_ATTENDANCE trg  
                                                                            USING   (
                                                                                SELECT DISTINCT
                                                                                MAX(ID) AS ID,
                                                                                T0.EMPLOYEE_ID,
                                                                                T1.ATTENDANCE_DATE
                                                                                FROM TMS_ATTENDANCE T0
                                                                                INNER JOIN (
                                                                                    SELECT 
                                                                                        T0.EMPLOYEE_ID, 
                                                                                        T0.ATTENDANCE_DATE, 
                                                                                        CASE WHEN '". $row['PLAN_DURATION'] ."' != '0' 
                                                                                                THEN MIN(S0$n10) 
                                                                                                ELSE MAX(S0$n10) END AS S0$n10
                                                                                    FROM (
                                                                                        SELECT 
                                                                                                A.EMPLOYEE_ID,
                                                                                                TO_CHAR(A.ATTENDANCE_DATE, 'YYYY-MM-DD') AS ATTENDANCE_DATE,
                                                                                                A.S0$n10
                                                                                        FROM TMS_ATTENDANCE A
                                                                                        $innerJoin1
                                                                                    ) T0
                                                                                    GROUP BY T0.EMPLOYEE_ID, T0.ATTENDANCE_DATE 
                                                                                ) T1 ON T0.EMPLOYEE_ID = T1.EMPLOYEE_ID AND T0.S0$n10 = T1.S0$n10 AND TO_CHAR(T0.ATTENDANCE_DATE, 'YYYY-MM-DD') = T1.ATTENDANCE_DATE
                                                                                GROUP BY T0.EMPLOYEE_ID, T1.ATTENDANCE_DATE
                                                                            ) src ON (trg.ID = src.ID) 
                                                                    WHEN MATCHED THEN UPDATE
                                                                    SET trg.S0$n11 = 1, trg.S0$n12 = 1, trg.S0$n13 = TO_CHAR(trg.ATTENDANCE_TIME, 'HH24:MI')");
                                                                }

                                        if ($sizeOf != $akey) {
                                            $n1 =  (int) $n1 + 16;
                                            $n2 =  (int) $n2 + 16;
                                            $n3 =  (int) $n3 + 16;
                                            $n4 =  (int) $n4 + 16;
                                            $n5 =  (int) $n5 + 16;
                                            $n6 =  (int) $n6 + 16;
                                            $n7 =  (int) $n7 + 16;
                                            $n8 =  (int) $n8 + 16;
                                            $n9 =  (int) $n9 + 16;
                                            $n10 =  (int) $n10 + 16; 
                                            $n11 =  (int) $n11 + 16; 
                                            $n12 =  (int) $n12 + 16; 
                                            $n13 =  (int) $n13 + 16; 
                                            $n14 =  (int) $n14 + 16; 
                                            $n15 =  (int) $n15 + 16; 
                                            $n16 =  (int) $n16 + 16; 
                                        }
                                    }
                                }
                            }
                        }
                    }

                    $leaveLate002 = 'S002';
                    $leaveLate005 = 'S005';

                    if($row['IS_LATE'] == '1') {
                        $leaveLate002 = '0';
                        $leaveLate005 = "'" . $getPlanTime['START_TIME'] . "'";
                    }

                    $dividPlanTime = $planTime / 2;
                    $selectResult = "SELECT DISTINCT
                                        EMPLOYEE_ID,
                                        DEPARTMENT_ID,
                                        TO_DATE(ATTENDANCE_DATE, 'YYYY-MM-DD') AS ATTENDANCE_DATE,
                                        INTIME,
                                        OUTTIME,
                                        CASE WHEN UNCLEARTIME > 0 THEN UNCLEARTIME ELSE 0 END AS UNCLEARTIME,
                                        CASE 
                                            WHEN CHECK_PLAN_NIGHT = 1 THEN "
                                                .$planTime."-" . ($customerConfig == '1' ? "CASE WHEN OUTTIME IS NULL OR INTIME IS NULL THEN 0 ELSE LATE_TIME_NOTLIMIT + EARLY_TIME END" : "LATE_TIME_NOTLIMIT") . "
                                            WHEN CLEARTIME > 0 AND OUTTIME IS NOT NULL THEN "
                                                .($customerConfig == '1' ? $planTime : "CLEARTIME")." 
                                            ELSE 
                                                0 
                                            END AS CLEARTIME,
                                        CASE 
                                            WHEN CHECK_PLAN_NIGHT = 1 THEN
                                                -LATE_TIME_NOTLIMIT
                                        ELSE
                                            " . ($customerConfig == '1' ? "0" : "CASE WHEN CLEARTIME > 0 THEN CLEARTIME + LATE_TIME ELSE 0 END - $planTime") . " 
                                        END AS DEFFERENCE_TIME,
                                        " . ($customerConfig == '1' ? "CASE WHEN OUTTIME IS NULL OR INTIME IS NULL THEN 0 ELSE LATE_TIME + EARLY_TIME END" : "LATE_TIME") . " AS LATE_TIME,
                                        " . ($customerConfig == '1' ? "0" : "EARLY_TIME") . " AS EARLY_TIME,
                                        CASE WHEN '". $row['PLAN_DURATION'] ."' != '0' AND OUTTIME IS NOT NULL
                                            THEN
                                            (ROUND (DATEDIFF(
                                                TO_DATE(ATTENDANCE_DATE||
                                                    CASE WHEN '". $nightStartDate ."' < IN_TIME
                                                        THEN IN_TIME
                                                        ELSE '". $nightStartDate ."'
                                                    END, 'YYYY-MM-DD HH24:MI'),
                                                TO_DATE(ATTENDANCE_DATE||
                                                CASE WHEN '". $nightEndDate ."' < OUT_TIME 
                                                    THEN '". $nightEndDate ."'
                                                    ELSE OUT_TIME
                                                END 
                                                , 'YYYY-MM-DD HH24:MI') + '". $row['PLAN_DURATION'] ."'
                                            )) / 60)
                                        ELSE 0 END AS NIGHT_TIME,
                                        ISMANUAL,
                                        " . ($customerConfig == '1' ? "CASE WHEN CLEARTIME < $dividPlanTime THEN $planTime - CLEARTIME ELSE (CASE WHEN OUTTIME IS NULL OR INTIME IS NULL THEN $planTime ELSE 0 END) END" : "0") . " AS CAUSE13
                                    FROM (
                                        SELECT 
                                            T0.EMPLOYEE_ID,
                                            EMP.DEPARTMENT_ID,
                                            TO_CHAR(T0.ATTENDANCE_DATE, 'YYYY-MM-DD') AS ATTENDANCE_DATE,
                                            T1.S005 AS IN_TIME,
                                            CASE WHEN T2.S0$n13 IS NOT NULL AND T1.S005 <> T2.S0$n13 THEN
                                                TO_DATE(TO_CHAR(T0.ATTENDANCE_DATE, 'YYYY-MM-DD') ||' '||T2.S0$n13, 'YYYY-MM-DD HH24:MI') 
                                            WHEN T3.S0$n13 IS NOT NULL AND T1.S005 <> T3.S0$n13 THEN
                                                TO_DATE(TO_CHAR(T0.ATTENDANCE_DATE, 'YYYY-MM-DD') ||' '||T3.S0$n13, 'YYYY-MM-DD HH24:MI') 
                                            WHEN T3.S0$n15 IS NOT NULL AND T1.S005 <> T3.S0$n15 THEN
                                                TO_DATE(TO_CHAR(T0.ATTENDANCE_DATE, 'YYYY-MM-DD') ||' '||T3.S0$n15, 'YYYY-MM-DD HH24:MI') 
                                            WHEN T4.S0$n13 IS NOT NULL AND T1.S005 <> T4.S0$n13 AND '".$row['PLAN_DURATION']."' != '0' AND T2.S0$n13 IS NULL THEN
                                                TO_DATE(TO_CHAR(T0.ATTENDANCE_DATE, 'YYYY-MM-DD') ||' '||T4.S0$n13, 'YYYY-MM-DD HH24:MI') 
                                            ELSE 
                                                NULL END AS OUTTIME,
                                            T2.S0$n13 AS OUT_TIME,
                                            TO_DATE(TO_CHAR(T0.ATTENDANCE_DATE, 'YYYY-MM-DD')||' '||T1.S005, 'YYYY-MM-DD HH24:MI') AS INTIME,
                                            CASE
                                                WHEN TO_DATE(T2.ATTENDANCE_DATE||CASE WHEN T2.S0$n13 <= T2.S0$n9 THEN T2.S0$n13 ELSE T2.S0$n9 END , 'YYYY-MM-DD HH24:MI') <= TO_DATE(T2.ATTENDANCE_DATE||'".$getPlanTime['START_TIME_END_TIME']."', 'YYYY-MM-DD HH24:MI')
                                                THEN (ROUND (DATEDIFF(
                                                        TO_DATE(T1.ATTENDANCE_DATE||T1.S005, 'YYYY-MM-DD HH24:MI'),
                                                        TO_DATE(T2.ATTENDANCE_DATE||T2.S0$n13, 'YYYY-MM-DD HH24:MI')
                                                    )) / 60)
                                                ELSE (ROUND(DATEDIFF(
                                                        TO_DATE(T1.ATTENDANCE_DATE||T1.S005, 'YYYY-MM-DD HH24:MI'),
                                                        TO_DATE(T2.ATTENDANCE_DATE||T2.S0$n13, 'YYYY-MM-DD HH24:MI')
                                                    )) / 60) 
                                                    - CASE WHEN $lunchTimePlan > 0 THEN $lunchTimePlan ELSE 0 END
                                                    + CASE WHEN 0 > T2.S0$n10 AND T2.S0$n10 >= $defaultDefferenceTime THEN 0-T2.S0$n10 ELSE 0 END
                                            END
                                            AS UNCLEARTIME,
                                                (ROUND(DATEDIFF(
                                                    TO_DATE(T1.ATTENDANCE_DATE||CASE WHEN T1.S005 <= T1.S001 THEN T1.S001 ELSE 
                                                        (CASE WHEN TO_DATE(T1.ATTENDANCE_DATE||T1.S005, 'YYYY-MM-DD HH24:MI') >= TO_DATE(T1.ATTENDANCE_DATE||'".$getPlanTime['START_TIME_END_TIME']."', 'YYYY-MM-DD HH24:MI') 
                                                        AND TO_DATE(T1.ATTENDANCE_DATE||T1.S005, 'YYYY-MM-DD HH24:MI') <= TO_DATE(T1.ATTENDANCE_DATE||'".$getPlanTime['END_TIME_START_TIME']."', 'YYYY-MM-DD HH24:MI') 
                                                        THEN '".$getPlanTime['END_TIME_START_TIME']."' ELSE T1.S005 END) 
                                                    END, 'YYYY-MM-DD HH24:MI'),
                                                    CASE WHEN T4.S0$n13 IS NOT NULL AND T1.S005 <> T4.S0$n13 AND '".$row['PLAN_DURATION']."' != '0' AND T2.S0$n13 IS NULL THEN
                                                        (CASE WHEN TO_DATE(T4.ATTENDANCE_DATE||T4.S0$n13, 'YYYY-MM-DD HH24:MI') >= TO_DATE(T4.ATTENDANCE_DATE||'".$getPlanTime['START_TIME_END_TIME']."', 'YYYY-MM-DD HH24:MI') 
                                                               AND TO_DATE(T4.ATTENDANCE_DATE||T4.S0$n13, 'YYYY-MM-DD HH24:MI') <= TO_DATE(T4.ATTENDANCE_DATE||'".$getPlanTime['END_TIME_START_TIME']."', 'YYYY-MM-DD HH24:MI')
                                                            THEN 
                                                                TO_DATE(T4.ATTENDANCE_DATE||'".$getPlanTime['START_TIME_END_TIME']."', 'YYYY-MM-DD HH24:MI')
                                                            ELSE 
                                                                TO_DATE(T4.ATTENDANCE_DATE||T4.S0$n13, 'YYYY-MM-DD HH24:MI')
                                                        END)
                                                    ELSE
                                                        (CASE WHEN TO_DATE(T2.ATTENDANCE_DATE||T2.S0$n13, 'YYYY-MM-DD HH24:MI') >= TO_DATE(T2.ATTENDANCE_DATE||'".$getPlanTime['START_TIME_END_TIME']."', 'YYYY-MM-DD HH24:MI') 
                                                               AND TO_DATE(T2.ATTENDANCE_DATE||T2.S0$n13, 'YYYY-MM-DD HH24:MI') <= TO_DATE(T2.ATTENDANCE_DATE||'".$getPlanTime['END_TIME_START_TIME']."', 'YYYY-MM-DD HH24:MI')
                                                            THEN TO_DATE(T2.ATTENDANCE_DATE||'".$getPlanTime['START_TIME_END_TIME']."', 'YYYY-MM-DD HH24:MI')
                                                            ELSE 
                                                            CASE WHEN TO_DATE(T2.ATTENDANCE_DATE||T2.S0$n13, 'YYYY-MM-DD HH24:MI') >= TO_DATE(T2.ATTENDANCE_DATE||'".$getPlanTime['END_TIME']."', 'YYYY-MM-DD HH24:MI')
                                                                   OR TO_DATE(T2.ATTENDANCE_DATE||T2.S0$n13, 'YYYY-MM-DD HH24:MI') >= TO_DATE(T2.ATTENDANCE_DATE||'".$getPlanTime['ENDTIME_LIMIT']."', 'YYYY-MM-DD HH24:MI')
                                                            THEN TO_DATE(T2.ATTENDANCE_DATE||'".$getPlanTime['END_TIME']."', 'YYYY-MM-DD HH24:MI')
                                                            ELSE TO_DATE(T2.ATTENDANCE_DATE||T2.S0$n13, 'YYYY-MM-DD HH24:MI') END
                                                        END)
                                                    END
                                                )) / 60)
                                                +
                                                (ROUND(DATEDIFF(
                                                    TO_DATE(TO_CHAR(T0.ATTENDANCE_DATE, 'YYYY-MM-DD ')||T1.S006, 'YYYY-MM-DD HH24:MI'),
                                                    TO_DATE(TO_CHAR(T0.ATTENDANCE_DATE, 'YYYY-MM-DD ')||T1.S001, 'YYYY-MM-DD HH24:MI')
                                                )) / 60)
                                                + CASE WHEN 0 > T2.S0$n10 AND (T2.S0$n10 >= $defaultDefferenceTime OR $customerConfig = '1') THEN 0-T2.S0$n10 ELSE 0 END                                               
                                                - (CASE WHEN
                                                    (TO_DATE(T1.ATTENDANCE_DATE||T1.S005, 'YYYY-MM-DD HH24:MI') >= TO_DATE(T1.ATTENDANCE_DATE||'".$getPlanTime['START_TIME_END_TIME']."', 'YYYY-MM-DD HH24:MI') 
                                                        AND TO_DATE(T1.ATTENDANCE_DATE||T1.S005, 'YYYY-MM-DD HH24:MI') <= TO_DATE(T1.ATTENDANCE_DATE||'".$getPlanTime['END_TIME_START_TIME']."', 'YYYY-MM-DD HH24:MI'))
                                                    OR (
                                                        TO_DATE(T2.ATTENDANCE_DATE||T2.S0$n13, 'YYYY-MM-DD HH24:MI') >= TO_DATE(T2.ATTENDANCE_DATE||'".$getPlanTime['START_TIME_END_TIME']."', 'YYYY-MM-DD HH24:MI') 
                                                        AND TO_DATE(T2.ATTENDANCE_DATE||T2.S0$n13, 'YYYY-MM-DD HH24:MI') <= TO_DATE(T2.ATTENDANCE_DATE||'".$getPlanTime['END_TIME_START_TIME']."', 'YYYY-MM-DD HH24:MI')                                                            
                                                    )
                                                    OR (
                                                        TO_DATE(T2.ATTENDANCE_DATE||T2.S0$n13, 'YYYY-MM-DD HH24:MI') <= TO_DATE(T2.ATTENDANCE_DATE||'".$getPlanTime['END_TIME_START_TIME']."', 'YYYY-MM-DD HH24:MI')
                                                        AND TO_DATE(T1.ATTENDANCE_DATE||T1.S005, 'YYYY-MM-DD HH24:MI') >= TO_DATE(T1.ATTENDANCE_DATE||'".$getPlanTime['START_TIME_END_TIME']."', 'YYYY-MM-DD HH24:MI')
                                                    )
                                                    OR (
                                                        TO_DATE(T1.ATTENDANCE_DATE||T1.S005, 'YYYY-MM-DD HH24:MI') >= TO_DATE(T1.ATTENDANCE_DATE||'".$getPlanTime['END_TIME_START_TIME']."', 'YYYY-MM-DD HH24:MI')
                                                    )
                                                    OR (
                                                        TO_DATE(T2.ATTENDANCE_DATE||T2.S0$n13, 'YYYY-MM-DD HH24:MI') <= TO_DATE(T2.ATTENDANCE_DATE||'".$getPlanTime['START_TIME_END_TIME']."', 'YYYY-MM-DD HH24:MI')
                                                    )
                                                THEN 0 ELSE (CASE WHEN $lunchTimePlan > 0 THEN $lunchTimePlan ELSE 0 END) END)
                                             AS CLEARTIME,
                                            CASE
                                                WHEN TO_DATE(T2.ATTENDANCE_DATE||CASE WHEN T2.S0$n13 <= T2.S0$n9 THEN T2.S0$n13 ELSE T2.S0$n9 END , 'YYYY-MM-DD HH24:MI') <= TO_DATE(T2.ATTENDANCE_DATE||'".$getPlanTime['START_TIME_END_TIME']."', 'YYYY-MM-DD HH24:MI')                                                        
                                                THEN 1
                                                ELSE 0
                                            END
                                            AS ISCHECK,
                                            0 AS DEFFERENCE_TIME,
                                            1 AS ISMANUAL,
                                            CASE WHEN 0 > T1.S002 AND T1.S002 >= $defaultDefferenceTime THEN 0-T1.S002 ELSE 0 END AS LATE_TIME,
                                            CASE WHEN 0 > T2.S0$n10 AND (T2.S0$n10 >= $defaultDefferenceTime OR $customerConfig = '1') THEN 0-T2.S0$n10 ELSE 0 END AS EARLY_TIME,
                                            CASE WHEN T11.EMPLOYEE_ID IS NULL THEN 0 ELSE 1 END AS CHECK_STATUS,
                                            CASE WHEN T3.EMPLOYEE_ID IS NULL THEN 0 ELSE 1 END AS CHECK_PLAN_NIGHT,
                                            CASE WHEN 0 > T1.S002 THEN 0-T1.S002 ELSE 0 END AS LATE_TIME_NOTLIMIT
                                        FROM TMS_ATTENDANCE T0
                                        INNER JOIN (
                                            SELECT 
                                                EMPLOYEE_ID, 
                                                TO_CHAR(ATTENDANCE_DATE, 'YYYY-MM-DD') AS ATTENDANCE_DATE,
                                                MIN(S001) AS S001,
                                                MIN($leaveLate002) AS S002,
                                                MIN($leaveLate005) AS S005,
                                                MIN(S006) AS S006 
                                            FROM TMS_ATTENDANCE WHERE S005 IS NOT NULL 
                                            AND TO_CHAR(ATTENDANCE_DATE, 'YYYYMMDD') BETWEEN '$startDate' AND '$planDurationEndDate'
                                            GROUP BY EMPLOYEE_ID, TO_CHAR(ATTENDANCE_DATE, 'YYYY-MM-DD')
                                        ) T1 ON T0.EMPLOYEE_ID = T1.EMPLOYEE_ID AND TO_CHAR(T0.ATTENDANCE_DATE, 'YYYY-MM-DD') = T1.ATTENDANCE_DATE
                                        LEFT JOIN (
                                            SELECT 
                                                EMPLOYEE_ID, 
                                                TO_CHAR(ATTENDANCE_DATE, 'YYYY-MM-DD') AS ATTENDANCE_DATE,
                                                MAX(S0$n9) AS S0$n9,
                                                MAX(S0$n10) AS S0$n10,
                                                MAX(S0$n13) AS S0$n13, 
                                                MAX(S0$n14) AS S0$n14  
                                            FROM TMS_ATTENDANCE WHERE S0$n13 IS NOT NULL 
                                            AND TO_CHAR(ATTENDANCE_DATE, 'YYYYMMDD') BETWEEN '$startDate' AND '$planDurationEndDate'
                                                                                            GROUP BY EMPLOYEE_ID, 
                                                TO_CHAR(ATTENDANCE_DATE, 'YYYY-MM-DD')
                                        ) T2 ON T0.EMPLOYEE_ID = T2.EMPLOYEE_ID AND TO_CHAR(T0.ATTENDANCE_DATE + '". $row['PLAN_DURATION'] ."', 'YYYY-MM-DD') = T2.ATTENDANCE_DATE
                                        LEFT JOIN (
                                            SELECT 
                                                EMPLOYEE_ID, 
                                                TO_CHAR(ATTENDANCE_DATE, 'YYYY-MM-DD') AS ATTENDANCE_DATE,
                                                S0$n9,
                                                S0$n10,
                                                S0$n13, 
                                                S0$n15, 
                                                S0$n14  
                                            FROM TMS_ATTENDANCE WHERE S0$n15 IS NOT NULL
                                            AND TO_CHAR(ATTENDANCE_DATE, 'YYYYMMDD') BETWEEN '$startDate' AND '$planDurationEndDate'
                                            AND ATTENDANCE_DATE < TO_DATE(TO_CHAR(ATTENDANCE_DATE, 'YYYY-MM-DD')||' ".$isTmsNightShift."', 'YYYY-MM-DD HH24:MI')
                                        ) T3 ON T0.EMPLOYEE_ID = T3.EMPLOYEE_ID AND TO_CHAR(T0.ATTENDANCE_DATE + '1', 'YYYY-MM-DD') = T3.ATTENDANCE_DATE
                                        LEFT JOIN (
                                            SELECT 
                                                EMPLOYEE_ID, 
                                                TO_CHAR(ATTENDANCE_DATE, 'YYYY-MM-DD') AS ATTENDANCE_DATE,
                                                MAX(S0$n9) AS S0$n9,
                                                MAX(S0$n10) AS S0$n10,
                                                MAX(S0$n13) AS S0$n13, 
                                                MAX(S0$n14) AS S0$n14  
                                            FROM TMS_ATTENDANCE WHERE S0$n13 IS NOT NULL 
                                            AND TO_CHAR(ATTENDANCE_DATE, 'YYYYMMDD') BETWEEN '$startDate' AND '$planDurationEndDate'
                                                                                            GROUP BY EMPLOYEE_ID, 
                                                TO_CHAR(ATTENDANCE_DATE, 'YYYY-MM-DD')
                                        ) T4 ON T0.EMPLOYEE_ID = T4.EMPLOYEE_ID AND TO_CHAR(T0.ATTENDANCE_DATE, 'YYYY-MM-DD') = T4.ATTENDANCE_DATE
                                        INNER JOIN TMS_EMPLOYEE_TIME_PLAN_HDR H ON T0.EMPLOYEE_ID = H.EMPLOYEE_ID AND H.YEAR_ID||'-'||CASE WHEN H.MONTH_ID < 10 THEN '0'||H.MONTH_ID ELSE TO_CHAR(H.MONTH_ID) END = TO_CHAR(T0.ATTENDANCE_DATE, 'YYYY-MM')
                                        INNER JOIN TMS_EMPLOYEE_TIME_PLAN_DTL B ON TO_CHAR(B.PLAN_DATE, 'YYYYMMDD') = TO_CHAR(T0.ATTENDANCE_TIME, 'YYYYMMDD') AND B.TIME_PLAN_ID = H.ID
                                        INNER JOIN (
                                            SELECT 
                                                DISTINCT
                                                pd.PLAN_DETAIL_ID,
                                                p.PLAN_ID, 
                                                TO_CHAR(pd.START_TIME, 'HH24:MI') AS START_TIME, 
                                                TO_CHAR(pd.END_TIME, 'HH24:MI') AS END_TIME ,
                                                pd.ORDER_NUM, 
                                                pd.IS_NIGHT_SHIFT,
                                                p.PLAN_DURATION,
                                                pd.STARTTIME_LIMIT,
                                                pd.ENDTIME_LIMIT,
                                                pd.ACC_TYPE
                                            FROM TMS_TIME_PLAN p 
                                            INNER JOIN TMS_TIME_PLAN_DETAIL pd on p.PLAN_ID = pd.PLAN_ID
                                            WHERE p.PLAN_ID = '". $row['PLAN_ID'] ."'
                                        ) C ON B.PLAN_ID =  C.PLAN_ID
                                        INNER JOIN HRM_EMPLOYEE_KEY EMP ON T0.EMPLOYEE_ID = EMP.EMPLOYEE_ID AND EMP.IS_ACTIVE = 1 AND EMP.WORK_END_DATE IS NULL
                                        LEFT JOIN (
                                            SELECT EMPLOYEE_ID, BALANCE_DATE
                                            FROM TNA_TIME_BALANCE_HDR 
                                        ) T11 ON H.EMPLOYEE_ID = T11.EMPLOYEE_ID AND TO_CHAR(T0.ATTENDANCE_DATE, 'YYYY-MM-DD') = TO_CHAR(T11.BALANCE_DATE, 'YYYY-MM-DD')
                                        WHERE H.YEAR_ID = '$year' 
                                            AND H.MONTH_ID = TO_CHAR('$month') 
                                            AND C.PLAN_ID = '". $row['PLAN_ID'] ."' 
                                            AND TO_CHAR(T0.ATTENDANCE_DATE, 'YYYYMMDD') BETWEEN '$startDate' AND '$planDurationEndDate'" . ($singleEmployee ? " AND T0.EMPLOYEE_ID = " . $join : "") . "                                                    
                                    )
                                    WHERE CHECK_STATUS = 0
                                    GROUP BY 
                                        EMPLOYEE_ID,
                                        DEPARTMENT_ID,
                                        ATTENDANCE_DATE,
                                        INTIME,
                                        OUTTIME,
                                        OUT_TIME,
                                        IN_TIME,
                                        UNCLEARTIME,
                                        CLEARTIME,
                                        DEFFERENCE_TIME,
                                        LATE_TIME,
                                        EARLY_TIME,
                                        ISCHECK,
                                        CHECK_PLAN_NIGHT,
                                        LATE_TIME_NOTLIMIT,
                                        ISMANUAL";

                    //echo $selectResult; die;
                    //var_dump($plan); die;
                    //if ($row['PLAN_ID'] == '1507026458176') { echo $selectResult; die; }

                    $data = $this->db->GetAll($selectResult);

                    $overTime = Config::getFromCache('tmsOverTime');
                    $overTime = $overTime ? explode(',', $overTime) : array();
                    $overTime = '^' . join('^', $overTime) . '^';               

                    if ($data) {
                        $cause44 = 0;
                        $nightTimeAdd = 'NIGHT_TIME';

                        if(Config::getFromCache('CONFIG_TNA_HISHIGARVIN')) {
                            $cause44 = "CASE WHEN INSTR('$overTime', '^'||DEPARTMENT_ID||'^') = 0 OR (CASE WHEN DEFFERENCE_TIME < 60 AND DEFFERENCE_TIME < $defaultDefferenceTime THEN CLEARTIME + LATE_TIME ELSE CLEARTIME END < $planTime) THEN 0 ELSE 210 END";
                            $nightTimeAdd = "CASE WHEN INSTR('^222006^', '^'||DEPARTMENT_ID||'^') = 0 AND '".$row['PLAN_DURATION']."' = '0' THEN 480 ELSE NIGHT_TIME END";
                        }

                        $this->db->Execute("INSERT INTO TNA_TIME_BALANCE_HDR (
                            TIME_BALANCE_HDR_ID, 
                            EMPLOYEE_ID,  BALANCE_DATE, START_TIME, END_TIME, UNCLEAN_TIME, CLEAN_TIME,  DEFFERENCE_TIME, 
                            LATE_TIME, EARLY_TIME, 
                            ". $LATE_TYPE .", 
                            ". $EARLY_TYPE .",
                            NIGHT_TIME, IS_UPLOAD, CREATED_DATE, CREATED_USER_ID, IS_CONFIRMED, CAUSE4, CAUSE13)
                                SELECT 
                                    IMPORT_ID_SEQ.NEXTVAL,
                                    EMPLOYEE_ID,
                                    ATTENDANCE_DATE,
                                    INTIME,
                                    OUTTIME,
                                    UNCLEARTIME,
                                    CASE WHEN DEFFERENCE_TIME < 60 AND DEFFERENCE_TIME < $defaultDefferenceTime THEN CLEARTIME ".(Config::getFromCache('tmsLateTimeClearTime') == '1' ? "" : "+LATE_TIME")." ".($isEarlyTimeToClean == '1' ? "-EARLY_TIME" : "")." ELSE CLEARTIME ".($isEarlyTimeToClean == '1' ? "-EARLY_TIME" : "")." END AS CLEARTIME,
                                    CASE WHEN DEFFERENCE_TIME < 60 AND DEFFERENCE_TIME < $defaultDefferenceTime THEN DEFFERENCE_TIME ".(Config::getFromCache('tmsIsEarlyTimeToDifferenceTime') == '1' ? "-EARLY_TIME" : "+EARLY_TIME")." ELSE 0 END AS DEFFERENCE_TIME,
                                    LATE_TIME ".(Config::getFromCache('earlyTimeTolateTime') == '1' ? "+EARLY_TIME " : "")."AS LATE_TIME,
                                    ".($isEarlyTimeToClean == '1' ? "0" : "EARLY_TIME")." AS EARLY_TIME,
                                    LATE_TIME,
                                    ".($isEarlyTimeToClean == '1' ? "0" : "EARLY_TIME")." AS EARLY_TIME,
                                    $nightTimeAdd AS NIGHT_TIME,
                                    ISMANUAL,
                                    SYSDATE,
                                    1,
                                    CASE WHEN DEFFERENCE_TIME > -0.05 OR DEFFERENCE_TIME > $defaultDefferenceTime THEN 
                                        CASE WHEN $customerConfig = 1 AND ((INTIME IS NULL OR OUTTIME IS NULL) OR CAUSE13 > 0) THEN
                                            0
                                        ELSE
                                            1
                                        END
                                    ELSE 0 END AS IS_CONFIRMED,                                        
                                    $cause44 AS CAUSE4,
                                    CAUSE13
                                    FROM ($selectResult)");

                        (Array) $response[$row['PLAN_ID']] = array();
                        $response[$row['PLAN_ID']] = $data;
                        $response = array('status' => 'success', 'message' => 'Амжилттай боллоо');

                    }
                }
                // </editor-fold>

            } else {

                if($isPlanDownload == 1) {
                    $this->db->Execute("DELETE FROM TMS_ATTENDANCE  WHERE TO_CHAR(ATTENDANCE_DATE, 'YYYYMMDD') BETWEEN '$startDate' AND '$endDate'");

                    $this->db->Execute("INSERT INTO TMS_ATTENDANCE (ID, EMPLOYEE_ID, ATTENDANCE_DATE, ATTENDANCE_TIME)
                                        SELECT 
                                            IMPORT_ID_SEQ.NEXTVAL, 
                                            EMPLOYEE_ID, 
                                            ATTENDANCE_DATE_TIME, 
                                            ATTENDANCE_DATE_TIME 
                                        FROM TNA_TIME_ATTENDANCE 
                                        WHERE TO_CHAR(ATTENDANCE_DATE_TIME, 'YYYYMMDD') BETWEEN '$startDate' AND '$endDate'
                                            AND EMPLOYEE_ID IN ($joinNotPlan)");                    
                }
            }

            // <editor-fold defaultstate="collapsed" desc="NO PLAN">
            if($isPlanDownload == 1) {
                $this->db->Execute("MERGE
                                        INTO TMS_ATTENDANCE TRG  
                                        USING ( 
                                        SELECT DISTINCT
                                            T0.EMPLOYEE_ID,
                                            TA.NEXT_ATTENDANCE_DATE,
                                            MIN(T0.ATTENDANCE_DATE) AS ATTENDANCE_DATE
                                            FROM TMS_ATTENDANCE T0
                                            LEFT JOIN (
                                                SELECT DISTINCT T0.EMPLOYEE_ID,
                                                    T0.ATTENDANCE_DATE,
                                                    LEAD(T0.ATTENDANCE_DATE) OVER(PARTITION BY T0.EMPLOYEE_ID ORDER BY T0.EMPLOYEE_ID, T0.ATTENDANCE_DATE) AS NEXT_ATTENDANCE_DATE
                                                  FROM TMS_ATTENDANCE T0
                                                  WHERE TO_CHAR(T0.ATTENDANCE_DATE, 'YYYYMMDD') BETWEEN '$startDate' AND '$endDate'
                                                  ORDER BY T0.EMPLOYEE_ID, T0.ATTENDANCE_DATE                                                                                                  
                                            ) TA ON TA.EMPLOYEE_ID = T0.EMPLOYEE_ID AND TA.ATTENDANCE_DATE = T0.ATTENDANCE_DATE
                                            LEFT JOIN (
                                                SELECT HDR.EMPLOYEE_ID, T2.PLAN_DATE, '1' AS CHECK_PLAN 
                                                FROM TMS_EMPLOYEE_TIME_PLAN_HDR HDR
                                                INNER JOIN TMS_EMPLOYEE_TIME_PLAN_DTL T2 ON HDR.ID = T2.TIME_PLAN_ID
                                                WHERE 1 = 1" . ($singleEmployee ? " AND HDR.EMPLOYEE_ID = " . $join : "") . "
                                            ) T12 ON T0.EMPLOYEE_ID = T12.EMPLOYEE_ID AND TO_CHAR(T0.ATTENDANCE_DATE, 'YYYY-MM-DD') = TO_CHAR(T12.PLAN_DATE, 'YYYY-MM-DD')
                                            WHERE (CHECK_PLAN IS NULL OR T0.EMPLOYEE_ID IN ($joinNotPlan)) AND TO_CHAR(T0.ATTENDANCE_DATE, 'YYYYMMDD') BETWEEN '$startDate' AND '$endDate'" . ($singleEmployee ? " AND T0.EMPLOYEE_ID = " . $join : "") . "
                                            GROUP BY T0.EMPLOYEE_ID, TO_CHAR(T0.ATTENDANCE_DATE, 'YYYYMMDD'), TA.NEXT_ATTENDANCE_DATE
                                        ) SRC ON (TRG.EMPLOYEE_ID = SRC.EMPLOYEE_ID AND TRG.ATTENDANCE_DATE = SRC.ATTENDANCE_DATE) 
                                    WHEN MATCHED THEN UPDATE
                                    SET trg.S003 = 1, trg.S004 = 1, trg.S005 = TO_CHAR(TRG.ATTENDANCE_TIME, 'HH24:MI'), trg.S013 = TO_CHAR(SRC.NEXT_ATTENDANCE_DATE, 'HH24:MI')");

//                    $this->db->Execute("MERGE
//                                            INTO TMS_ATTENDANCE trg  
//                                            USING ( 
//                                            SELECT DISTINCT
//                                                T0.EMPLOYEE_ID,
//                                                CASE WHEN INSTR('^222006^', '^'||EMP.DEPARTMENT_ID||'^') = 0 THEN 
//                                                    MAX(T0.ATTENDANCE_DATE) 
//                                                ELSE 
//                                                    MIN(T0.NEXT_ATTENDANCE_DATE)
//                                                END AS ATTENDANCE_DATE
//                                                FROM (
//                                                    SELECT DISTINCT T0.EMPLOYEE_ID,
//                                                      T0.ATTENDANCE_DATE,
//                                                      LEAD(T0.ATTENDANCE_DATE) OVER(PARTITION BY T0.EMPLOYEE_ID ORDER BY T0.EMPLOYEE_ID, T0.ATTENDANCE_DATE) AS NEXT_ATTENDANCE_DATE
//                                                    FROM TMS_ATTENDANCE T0
//                                                    WHERE TO_CHAR(T0.ATTENDANCE_DATE, 'YYYYMMDD') BETWEEN '$startDate' AND '$endDate'
//                                                    ORDER BY T0.EMPLOYEE_ID, T0.ATTENDANCE_DATE                                                  
//                                                ) T0
//                                                INNER JOIN HRM_EMPLOYEE_KEY EMP ON T0.EMPLOYEE_ID = EMP.EMPLOYEE_ID AND EMP.IS_ACTIVE = 1 AND EMP.WORK_END_DATE IS NULL
//                                                LEFT JOIN (
//                                                    SELECT HDR.EMPLOYEE_ID, T2.PLAN_DATE, '1' AS CHECK_PLAN 
//                                                    FROM TMS_EMPLOYEE_TIME_PLAN_HDR HDR
//                                                    INNER JOIN TMS_EMPLOYEE_TIME_PLAN_DTL T2 ON HDR.ID = T2.TIME_PLAN_ID
//                                                    WHERE 1 = 1" . ($singleEmployee ? " AND HDR.EMPLOYEE_ID = " . $join : "") . "
//                                                ) T12 ON T0.EMPLOYEE_ID = T12.EMPLOYEE_ID AND TO_CHAR(T0.ATTENDANCE_DATE, 'YYYY-MM-DD') = TO_CHAR(T12.PLAN_DATE, 'YYYY-MM-DD')
//                                                WHERE (CHECK_PLAN IS NULL OR T0.EMPLOYEE_ID IN ($joinNotPlan))
//                                                      AND TO_CHAR(T0.ATTENDANCE_DATE, 'YYYYMMDD') BETWEEN '$startDate' AND '$endDate'" . ($singleEmployee ? " AND T0.EMPLOYEE_ID = " . $join : "") . "
//                                                GROUP BY T0.EMPLOYEE_ID, TO_CHAR(ATTENDANCE_DATE, 'YYYYMMDD'), EMP.DEPARTMENT_ID
//                                            ) SRC ON (TRG.EMPLOYEE_ID = SRC.EMPLOYEE_ID AND TRG.ATTENDANCE_DATE = SRC.ATTENDANCE_DATE)
//                                        WHEN MATCHED THEN UPDATE
//                                        SET trg.S011 = 1, trg.S012 = 1, trg.S013 = TO_CHAR(trg.ATTENDANCE_TIME, 'HH24:MI')");

                $selectResultNotPlan = "SELECT DISTINCT
                                    EMPLOYEE_ID,
                                    DEPARTMENT_ID,
                                    TO_DATE(ATTENDANCE_DATE, 'YYYY-MM-DD') AS ATTENDANCE_DATE,
                                    INTIME,
                                    OUTTIME ,
                                    CASE WHEN UNCLEARTIME > 0 THEN UNCLEARTIME ELSE 0 END AS UNCLEARTIME,
                                    CASE WHEN CLEARTIME > 0 AND $customerConfig != '1' THEN CLEARTIME ELSE 0 END AS CLEARTIME,
                                    DEFFERENCE_TIME,
                                    LATE_TIME,
                                    EARLY_TIME,
                                    0 AS NIGHT_TIME,
                                    ISMANUAL
                                FROM (
                                    SELECT 
                                        T0.EMPLOYEE_ID,
                                        EMP.DEPARTMENT_ID,
                                        TO_CHAR(T0.ATTENDANCE_DATE, 'YYYY-MM-DD') AS ATTENDANCE_DATE,
                                        T1.S005 AS IN_TIME,
                                        TO_DATE(TO_CHAR(T0.ATTENDANCE_DATE, 'YYYY-MM-DD')||' '||T1.S005, 'YYYY-MM-DD HH24:MI') AS INTIME,
                                        CASE WHEN T2.S013 IS NOT NULL AND T1.S005 <> T2.S013 THEN
                                            TO_DATE(TO_CHAR(T0.ATTENDANCE_DATE, 'YYYY-MM-DD') ||' '||T2.S013, 'YYYY-MM-DD HH24:MI') 
                                        ELSE 
                                            NULL END AS OUTTIME,
                                        T2.S013 AS OUT_TIME,
                                       0 AS UNCLEARTIME,
                                        (ROUND(DATEDIFF(
                                            TO_DATE(T1.ATTENDANCE_DATE||T1.S005, 'YYYY-MM-DD HH24:MI'),                                                    
                                            TO_DATE(T2.ATTENDANCE_DATE||T2.S013, 'YYYY-MM-DD HH24:MI')
                                        )) / 60)
                                        AS CLEARTIME,
                                        0 AS ISCHECK,
                                        0 AS DEFFERENCE_TIME,
                                        1 AS ISMANUAL,
                                        0 AS LATE_TIME,
                                        0 AS EARLY_TIME,
                                        CASE WHEN T11.EMPLOYEE_ID IS NULL THEN 0 ELSE 1 END AS CHECK_STATUS
                                    FROM TMS_ATTENDANCE T0
                                    INNER JOIN (
                                        SELECT 
                                            EMPLOYEE_ID, 
                                            TO_CHAR(ATTENDANCE_DATE, 'YYYY-MM-DD') AS ATTENDANCE_DATE,
                                            MIN(S001) AS S001,
                                            MIN(S002) AS S002,
                                            MIN(S005) AS S005,
                                            MIN(S006) AS S006 
                                        FROM TMS_ATTENDANCE WHERE S005 IS NOT NULL 
                                        GROUP BY EMPLOYEE_ID, TO_CHAR(ATTENDANCE_DATE, 'YYYY-MM-DD')
                                    ) T1 ON T0.EMPLOYEE_ID = T1.EMPLOYEE_ID AND TO_CHAR(T0.ATTENDANCE_DATE, 'YYYY-MM-DD') = T1.ATTENDANCE_DATE
                                    LEFT JOIN (
                                        SELECT 
                                            EMPLOYEE_ID, 
                                            TO_CHAR(ATTENDANCE_DATE, 'YYYY-MM-DD') AS ATTENDANCE_DATE,
                                            MAX(S009) AS S009,
                                            MAX(S010) AS S010,
                                            MAX(S013) AS S013, 
                                            MAX(S014) AS S014  
                                        FROM TMS_ATTENDANCE WHERE S013 IS NOT NULL 
                                        GROUP BY EMPLOYEE_ID, TO_CHAR(ATTENDANCE_DATE, 'YYYY-MM-DD')
                                    ) T2 ON T0.EMPLOYEE_ID = T2.EMPLOYEE_ID AND T2.ATTENDANCE_DATE = TO_CHAR(T0.ATTENDANCE_DATE, 'YYYY-MM-DD')
                                    INNER JOIN HRM_EMPLOYEE_KEY EMP ON T0.EMPLOYEE_ID = EMP.EMPLOYEE_ID AND EMP.IS_ACTIVE = 1
                                    LEFT JOIN (
                                        SELECT EMPLOYEE_ID, BALANCE_DATE
                                        FROM TNA_TIME_BALANCE_HDR 
                                    ) T11 ON T0.EMPLOYEE_ID = T11.EMPLOYEE_ID AND TO_CHAR(T0.ATTENDANCE_DATE, 'YYYY-MM-DD') = TO_CHAR(T11.BALANCE_DATE, 'YYYY-MM-DD')
                                    LEFT JOIN (
                                        SELECT HDR.EMPLOYEE_ID, T2.PLAN_DATE 
                                        FROM TMS_EMPLOYEE_TIME_PLAN_HDR HDR
                                        INNER JOIN TMS_EMPLOYEE_TIME_PLAN_DTL T2 ON HDR.ID = T2.TIME_PLAN_ID
                                    ) T12 ON T0.EMPLOYEE_ID = T12.EMPLOYEE_ID AND TO_CHAR(T0.ATTENDANCE_DATE, 'YYYY-MM-DD') = TO_CHAR(T12.PLAN_DATE, 'YYYY-MM-DD')                                    
                                    WHERE TO_CHAR(T0.ATTENDANCE_DATE, 'YYYYMMDD') BETWEEN '$startDate' AND '$endDate' AND (T12.EMPLOYEE_ID IS NULL OR T12.EMPLOYEE_ID IN ($joinNotPlan))" . ($singleEmployee ? " AND T0.EMPLOYEE_ID = " . $join : "") . "
                                )
                                WHERE CHECK_STATUS = 0
                                GROUP BY 
                                    EMPLOYEE_ID,
                                    DEPARTMENT_ID,
                                    ATTENDANCE_DATE,
                                    INTIME,
                                    OUTTIME,
                                    OUT_TIME,
                                    IN_TIME,
                                    UNCLEARTIME,
                                    CLEARTIME,
                                    DEFFERENCE_TIME,
                                    LATE_TIME,
                                    EARLY_TIME,
                                    ISCHECK,
                                    ISMANUAL";

                        //echo $selectResultNotPlan; die;

                $dataNotPlan = $this->db->GetAll($selectResultNotPlan);                   

                if ($dataNotPlan) {
                    $cause44 = 0;
                    if(Config::getFromCache('CONFIG_TNA_HISHIGARVIN'))
                        $cause44 = "CASE WHEN INSTR('^7^1490331492612^1490698800425^1490698800458^1490698800470^1490698800487^1490698800561^1500187471892^', '^'||DEPARTMENT_ID||'^') = 0 THEN 0 ELSE 210 END";

                    $this->db->Execute("INSERT INTO TNA_TIME_BALANCE_HDR (
                        TIME_BALANCE_HDR_ID, 
                        EMPLOYEE_ID,  BALANCE_DATE, START_TIME, END_TIME, UNCLEAN_TIME, CLEAN_TIME,  DEFFERENCE_TIME, 
                        LATE_TIME, EARLY_TIME, 
                        ". $LATE_TYPE .", 
                        ". $EARLY_TYPE .",
                        NIGHT_TIME, IS_UPLOAD, CREATED_DATE, CREATED_USER_ID, IS_CONFIRMED, CAUSE4)
                            SELECT 
                                IMPORT_ID_SEQ.NEXTVAL,
                                EMPLOYEE_ID,
                                ATTENDANCE_DATE,
                                INTIME,
                                OUTTIME,
                                UNCLEARTIME,
                                CASE WHEN 
                                    INSTR('$notPlanDepartment', '^'||DEPARTMENT_ID||'^') = 0 THEN CLEARTIME 
                                ELSE
                                    CASE WHEN CLEARTIME < $planTimeDefault THEN CLEARTIME ELSE $planTimeDefault END
                                END AS CLEARTIME,
                                DEFFERENCE_TIME,
                                LATE_TIME,
                                EARLY_TIME ,
                                LATE_TIME,
                                EARLY_TIME,
                                NIGHT_TIME,
                                ISMANUAL,
                                SYSDATE,
                                1, 
                                0 AS IS_CONFIRMED,
                                $cause44 AS CAUSE4
                                FROM ($selectResultNotPlan)");

                }                
            }
            // </editor-fold>

            $response = array('status' => 'warning', 'message' => 'Амжилттай : Давхардсан өгөгдөлийг дахин оруулах боломжгүйг анхаарна уу?', );

        } catch (Exception $ex) {
             var_dump($ex); die;
            //$response = array('status' => 'warning', 'message' => 'Алдаа гарсан', 'exceptionmsg' => $ex->msg, 'exception' => $ex);
            $response = array('status' => 'warning', 'message' => 'Амжилттай:: Давхардсан өгөгдөлийг дахин оруулах боломжгүйг анхаарна уу?', );
        }

        return $response;
    }        

    public function downloadDataRestRangeModel($sync = false, $startDate = false, $endDate = false) {
        $singleEmployee = false;
        $notPlanDepartment = Config::getFromCache('tmsPlanTimeDefaultDepartment');
        $notPlanDepartment = $notPlanDepartmentArr = $notPlanDepartment ? explode(',', $notPlanDepartment) : array();
        $notPlanDepartment = '^' . join('^', $notPlanDepartment) . '^';        
        $joinNotPlan = -9999999999999;

        if(isset($_POST['balanceParam'])) {
            parse_str($_POST['balanceParam'], $params);            
            $notPlanArr = array();

            foreach($params['departmentId'] as $kd => $rowDep) {
                if(in_array($rowDep, $notPlanDepartmentArr)) {
                    array_push($notPlanArr, $rowDep);
                    unset($params['departmentId'][$kd]);
                }
            }

            $joinNotPlan = join(',' , $notPlanArr);      
            $joinNotPlan = empty($joinNotPlan) ? -9999999999999 : "SELECT EMPLOYEE_ID FROM VW_EMPLOYEE WHERE DEPARTMENT_ID IN ($joinNotPlan)";

            $join = join(',' , $params['departmentId']);
            $join = empty($join) ? -9999999999999 : "SELECT EMPLOYEE_ID FROM VW_EMPLOYEE WHERE DEPARTMENT_ID IN ($join)";

        } elseif($sync) {
            $params['startDate'] = $startDate ? $startDate : Date::beforeDate('Y-m-d', '-1 day');
            $params['endDate'] = $endDate ? $endDate : Date::currentDate();
            $join = $joinNotPlan = "SELECT EMPLOYEE_ID FROM VW_EMPLOYEE";

        } else {
            $paramData = $_POST['paramData'];
            $singleEmployee = true;

            foreach($paramData as $pRow) {
                $params[$pRow['postParam']] = $pRow['value'];
                if($pRow['postParam'] == 'employeeId')
                    $join = $joinNotPlan = $pRow['value'];
            }
        }

        $startDate = Date::format('Ymd',$params['startDate']);
        $endDate = Date::format('Ymd',$params['endDate']);

        $year = Date::format('Y',$params['startDate']);
        $month = Date::format('m',$params['startDate']);

        $startMonth = Date::format('m', $params['startDate']);
        $endMonth = Date::format('m', $params['endDate']);

        (Array) $response = array('status' => 'warning', 'message' => 'Төлөвлөгөө үүсээгүй байна.');
        $nightTimeRange = $this->db->GetOne("SELECT VALUE FROM PR_CONFIG WHERE CONFIG_ID = 4");
        $nightStartDate = $nightEndDate = '00:00';

        $planTimeDefault = (float) Config::getFromCache('tmsPlanTimeDefault') * 60;
        $defaultDefferenceTime = (float) Config::getFromCache('tmsDefaultDefferenceTime');
        $defaultDefferenceTime = $defaultDefferenceTime ? $defaultDefferenceTime : -60;
        $customerConfig = Config::getFromCache('tmsIsGolomt');
        $customerConfig = $customerConfig ? $customerConfig : '0';
        $LATE_TYPE = Config::getFromCache('tmsLateType');
        $EARLY_TYPE = Config::getFromCache('tmsEarlyType');            
        $isPlanDownload = Config::getFromCache('tmsIsPlanDownload');       
        $isPlanDownload = $isPlanDownload == '' ? 1 : $isPlanDownload;
        $isEarlyTimeToClean = Config::getFromCache('tmsIsEarlyTimeToCleanTime');
        $isEarlyTimeToClean = $isEarlyTimeToClean ? $isEarlyTimeToClean : 0;            
        $isTmsNightShift = Config::getFromCache('tmsNightShift');
        $isTmsNightShift = $isTmsNightShift ? $isTmsNightShift : '05:00';     
        $tmsRestTimeRange = Config::getFromCache('tmsRestTimeRange');
        $tmsRestTimeRange = $tmsRestTimeRange ? $tmsRestTimeRange : 0;             

        if ($nightTimeRange) {
            $nightTimeRange = explode('-', $nightTimeRange);

            $nightStartDate = $nightTimeRange[0];
            $nightEndDate = $nightTimeRange[1];                   
        }

        try {

            $plan = $this->db->GetAll(" SELECT 
                                            T1.PLAN_ID , MAX(T2.PLAN_DURATION) AS PLAN_DURATION, T2.IS_LATE
                                        FROM TMS_EMPLOYEE_TIME_PLAN_HDR T0
                                        INNER JOIN TMS_EMPLOYEE_TIME_PLAN_DTL T1 ON T0.ID = T1.TIME_PLAN_ID
                                        INNER JOIN TMS_TIME_PLAN T2 ON T1.PLAN_ID = T2.PLAN_ID
                                        WHERE T0.YEAR_ID = '$year' AND (T0.MONTH_ID = TO_CHAR('$startMonth') OR T0.MONTH_ID = TO_CHAR('$endMonth')) 
                                        AND TO_CHAR(T1.PLAN_DATE, 'YYYYMMDD') BETWEEN '$startDate' AND '$endDate' 
                                        AND T0.EMPLOYEE_ID IN ( " . $join . ")
                                        GROUP BY T1.PLAN_ID, T2.IS_LATE");

            /**
             * Нөхөж татах буюу гарсан цагаа дараагүй өгөгдлийг устгаж байна.
             */
            $this->db->Execute("DELETE FROM TNA_TIME_BALANCE_DTL WHERE TIME_BALANCE_HDR_ID IN (SELECT TIME_BALANCE_HDR_ID FROM TNA_TIME_BALANCE_HDR WHERE EMPLOYEE_ID IN (" . $join . ") AND IS_CONFIRMED = 0 AND TO_CHAR(BALANCE_DATE, 'YYYYMMDD') BETWEEN '$startDate' AND '$endDate')");
            $this->db->Execute("DELETE FROM TNA_TIME_BALANCE_HDR WHERE EMPLOYEE_ID IN (" . $join . ") AND IS_CONFIRMED = 0 AND TO_CHAR(BALANCE_DATE, 'YYYYMMDD') BETWEEN '$startDate' AND '$endDate'");

            if ($plan) {

                $planDurationEndDate = isset($plan[0]['PLAN_DURATION']) ? Date::nextDate($endDate, $plan[0]['PLAN_DURATION'], 'Ymd') : $endDate;

                $this->db->Execute("DELETE FROM TMS_ATTENDANCE  WHERE TO_CHAR(ATTENDANCE_DATE, 'YYYYMMDD') BETWEEN '$startDate' AND '$planDurationEndDate'" . ($singleEmployee ? ' AND EMPLOYEE_ID = ' . $join : ''));

                $this->db->Execute("INSERT INTO TMS_ATTENDANCE (ID, EMPLOYEE_ID, ATTENDANCE_DATE, ATTENDANCE_TIME)
                                    SELECT 
                                        IMPORT_ID_SEQ.NEXTVAL, 
                                        EMPLOYEE_ID, 
                                        ATTENDANCE_DATE_TIME,
                                        ATTENDANCE_DATE_TIME 
                                    FROM TNA_TIME_ATTENDANCE 
                                    WHERE TO_CHAR(ATTENDANCE_DATE_TIME, 'YYYYMMDD') BETWEEN '$startDate' AND '$planDurationEndDate'
                                        AND EMPLOYEE_ID IN ($join)");

                //$this->db->AutoExecute('TNA_TIME_ATTENDANCE', array('IS_REMOVED_NOT_PLAN' => NULL), 'UPDATE', "TO_CHAR(ATTENDANCE_DATE_TIME, 'YYYYMMDD') BETWEEN '$startDate' AND '$planDurationEndDate' AND EMPLOYEE_ID IN ($join)");

                // <editor-fold defaultstate="collapsed" desc="LOOP PLAN">
                foreach ($plan as $key => $row) {
                    $planTime = $this->db->GetOne("SELECT FNC_GET_TMS_PLAN_TIME('". $row['PLAN_ID'] ."') FROM DUAL");

                    $getPlanTime = $this->db->GetRow("SELECT 
                                                            T0.PLAN_ID,
                                                            T0.PLAN_DURATION,
                                                            T2.START_TIME,
                                                            T2.STARTTIME_LIMIT,
                                                            T2.START_TIME_END_TIME,
                                                            T3.END_TIME,
                                                            T3.ENDTIME_LIMIT,
                                                            T3.END_TIME_START_TIME                                         
                                                        FROM TMS_TIME_PLAN T0
                                                        INNER JOIN (
                                                            SELECT * FROM(
                                                                SELECT 
                                                                    T0.PLAN_ID, 
                                                                    TO_CHAR(T0.START_TIME, 'HH24:MI') AS START_TIME,
                                                                    CASE WHEN T0.STARTTIME_LIMIT IS NULL THEN
                                                                      TO_CHAR(T0.START_TIME, 'HH24:MI')
                                                                    ELSE TO_CHAR(T0.STARTTIME_LIMIT, 'HH24:MI')
                                                                    END AS STARTTIME_LIMIT,
                                                                    TO_CHAR(T0.END_TIME, 'HH24:MI') AS START_TIME_END_TIME,
                                                                    T0.ACC_TYPE
                                                                FROM TMS_TIME_PLAN_DETAIL T0
                                                                WHERE T0.PLAN_ID = ". $row['PLAN_ID'] ."
                                                                ORDER BY T0.ACC_TYPE ASC)
                                                            WHERE ROWNUM <= 1
                                                        ) T2 ON T0.PLAN_ID = T2.PLAN_ID
                                                        INNER JOIN (
                                                            SELECT * FROM(
                                                                SELECT 
                                                                    T0.PLAN_ID, 
                                                                    TO_CHAR(T0.END_TIME, 'HH24:MI') AS END_TIME,
                                                                    CASE WHEN T0.ENDTIME_LIMIT IS NULL THEN
                                                                      TO_CHAR(T0.END_TIME, 'HH24:MI')
                                                                    ELSE TO_CHAR(T0.ENDTIME_LIMIT, 'HH24:MI')
                                                                    END AS ENDTIME_LIMIT,
                                                                    TO_CHAR(T0.START_TIME, 'HH24:MI') AS END_TIME_START_TIME,
                                                                    T0.ACC_TYPE
                                                                FROM TMS_TIME_PLAN_DETAIL T0
                                                                WHERE T0.PLAN_ID = ". $row['PLAN_ID'] ."
                                                                ORDER BY T0.ACC_TYPE DESC)
                                                            WHERE ROWNUM <= 1
                                                        ) T3 ON T0.PLAN_ID = T3.PLAN_ID
                                                        WHERE T0.PLAN_ID = ". $row['PLAN_ID'] ."
                                                    GROUP BY T0.PLAN_ID,
                                                        T0.PLAN_DURATION,
                                                        T2.START_TIME,
                                                        T2.STARTTIME_LIMIT,
                                                        T2.START_TIME_END_TIME,
                                                        T3.END_TIME_START_TIME,
                                                        T3.ENDTIME_LIMIT,
                                                        T3.END_TIME");

                    $accType = $this->db->GetAll("SELECT 
                                                        T1.ACC_TYPE,
                                                        T0.PLAN_DURATION,
                                                        TO_CHAR(T1.START_TIME, 'HH24:MI') AS STARTTIME,
                                                        TO_CHAR(T1.END_TIME, 'HH24:MI') AS ENDTIME,
                                                        CASE WHEN T1.STARTTIME_LIMIT IS NULL THEN TO_CHAR(T1.START_TIME, 'HH24:MI') ELSE TO_CHAR(T1.STARTTIME_LIMIT, 'HH24:MI') END AS STARTTIME_LIMIT,
                                                        CASE WHEN T1.ENDTIME_LIMIT IS NULL THEN TO_CHAR(T1.END_TIME, 'HH24:MI') ELSE TO_CHAR(T1.ENDTIME_LIMIT, 'HH24:MI') END AS ENDTIME_LIMIT
                                                    FROM TMS_TIME_PLAN T0
                                                    INNER JOIN TMS_TIME_PLAN_DETAIL T1 ON T0.PLAN_ID = T1.PLAN_ID
                                                    WHERE T0.PLAN_ID = ". $row['PLAN_ID'] ."
                                                    ORDER BY T1.ACC_TYPE");

                    $lunchTimePlan1 = new DateTime($getPlanTime['START_TIME']);
                    $lunchTimePlan2 = new DateTime($getPlanTime['END_TIME']);
                    $hours = 24 * $getPlanTime['PLAN_DURATION'];
                    $lunchTimePlan2->modify("+$hours hours");

                    $intervalp = $lunchTimePlan2->diff($lunchTimePlan1);

                    $hour = $intervalp->format('%h');
                    $min = $intervalp->format('%i');
                    $second = $intervalp->format('%s');

                    $lunchTimePlan = (($hour * 60) + $min + ($second / 60)) - $planTime;  

                    $n1 = '01';  $n2 = '02';  $n3 = '03';  $n4 = '04';  
                    $n5 = '05';  $n6 = '06';  $n7 = '07';  $n8 = '08';  
                    $n9 = '09';  $n10 = '10'; $n11 = '11'; $n12 = '12'; 
                    $n13 = '13'; $n14 = '14'; $n15 = '15'; $n16 = '16';

                    $sizeOf = sizeOf($accType) - 1;

                    if ($accType) {
                        foreach ($accType as $akey => $acc) {
                            $innerJoin = "  INNER JOIN TMS_EMPLOYEE_TIME_PLAN_HDR H ON A.EMPLOYEE_ID = H.EMPLOYEE_ID AND H.YEAR_ID||'-'||CASE WHEN H.MONTH_ID < 10 THEN '0'||H.MONTH_ID ELSE TO_CHAR(H.MONTH_ID) END = TO_CHAR(A.ATTENDANCE_DATE, 'YYYY-MM')
                                            INNER JOIN TMS_EMPLOYEE_TIME_PLAN_DTL B ON TO_CHAR(B.PLAN_DATE, 'YYYYMMDD') = TO_CHAR(A.ATTENDANCE_TIME, 'YYYYMMDD') AND B.TIME_PLAN_ID = H.ID
                                            INNER JOIN (
                                                SELECT DISTINCT
                                                    pd.PLAN_DETAIL_ID,
                                                    p.PLAN_ID, 
                                                    TO_CHAR(pd.START_TIME, 'HH24:MI') AS START_TIME, 
                                                    TO_CHAR(pd.END_TIME, 'HH24:MI') AS END_TIME ,
                                                    pd.ORDER_NUM, 
                                                    pd.IS_NIGHT_SHIFT,
                                                    p.PLAN_DURATION,
                                                    pd.STARTTIME_LIMIT,
                                                    pd.ENDTIME_LIMIT,
                                                    pd.ACC_TYPE
                                                FROM TMS_TIME_PLAN p 
                                                INNER JOIN TMS_TIME_PLAN_DETAIL pd on p.PLAN_ID = pd.PLAN_ID
                                                WHERE pd.ACC_TYPE = '". $acc['ACC_TYPE'] ."' AND p.PLAN_ID = ". $row['PLAN_ID'] ."
                                            ) C ON B.PLAN_ID =  C.PLAN_ID
                                            WHERE H.YEAR_ID = '$year' AND H.MONTH_ID = TO_CHAR('$month') AND TO_CHAR(A.ATTENDANCE_DATE, 'YYYYMMDD') BETWEEN '$startDate' AND '$planDurationEndDate'" . ($singleEmployee ? " AND A.EMPLOYEE_ID = " . $join : "") . " ";

                            $innerJoin1 = " INNER JOIN TMS_EMPLOYEE_TIME_PLAN_HDR H ON A.EMPLOYEE_ID = H.EMPLOYEE_ID AND H.YEAR_ID||'-'||CASE WHEN H.MONTH_ID < 10 THEN '0'||H.MONTH_ID ELSE TO_CHAR(H.MONTH_ID) END = TO_CHAR(A.ATTENDANCE_DATE, 'YYYY-MM')
                                            INNER JOIN TMS_EMPLOYEE_TIME_PLAN_DTL B ON TO_CHAR(B.PLAN_DATE+". $row['PLAN_DURATION'] .", 'YYYYMMDD') = TO_CHAR(A.ATTENDANCE_TIME, 'YYYYMMDD') AND B.TIME_PLAN_ID = H.ID
                                            INNER JOIN (
                                                SELECT DISTINCT
                                                    pd.PLAN_DETAIL_ID,
                                                    p.PLAN_ID, 
                                                    TO_CHAR(pd.START_TIME, 'HH24:MI') AS START_TIME, 
                                                    TO_CHAR(pd.END_TIME, 'HH24:MI') AS END_TIME ,
                                                    pd.ORDER_NUM, 
                                                    pd.IS_NIGHT_SHIFT,
                                                    p.PLAN_DURATION,
                                                    pd.STARTTIME_LIMIT,
                                                    pd.ENDTIME_LIMIT,
                                                    pd.ACC_TYPE
                                                FROM TMS_TIME_PLAN p 
                                                INNER JOIN TMS_TIME_PLAN_DETAIL pd on p.PLAN_ID = pd.PLAN_ID
                                                WHERE pd.ACC_TYPE = '". $acc['ACC_TYPE'] ."' AND p.PLAN_ID = '". $row['PLAN_ID'] ."'
                                            ) C ON B.PLAN_ID =  C.PLAN_ID
                                            WHERE H.YEAR_ID = '$year' AND H.MONTH_ID = TO_CHAR('$month') AND TO_CHAR(A.ATTENDANCE_DATE, 'YYYYMMDD') BETWEEN '$startDate' AND '$planDurationEndDate'" . ($singleEmployee ? " AND A.EMPLOYEE_ID = " . $join : "") . " ";

                            $check = $this->db->GetRow("SELECT 
                                                            DISTINCT 
                                                            A.ID,
                                                            A.EMPLOYEE_ID, 
                                                            C.START_TIME AS START_TIME,
                                                            C.END_TIME AS END_TIME,
                                                            C.PLAN_ID, 
                                                            TO_CHAR(B.PLAN_DATE, 'YYYY-MM-DD')  AS PLAN_DATE
                                                        FROM TMS_ATTENDANCE A
                                                            $innerJoin ");

                            if ($check) {
                                $resultMerge1 = $this->db->Execute(
                                        "MERGE
                                            INTO TMS_ATTENDANCE trg
                                            USING   ( 
                                                SELECT DISTINCT
                                                    T1.ACC_TYPE,
                                                    T0.PLAN_DURATION,
                                                    TO_CHAR(T1.START_TIME, 'HH24:MI') AS STARTTIME,
                                                    TO_CHAR(T1.END_TIME, 'HH24:MI') AS ENDTIME,
                                                    CASE WHEN T1.STARTTIME_LIMIT IS NULL THEN TO_CHAR(T1.START_TIME, 'HH24:MI') ELSE TO_CHAR(T1.STARTTIME_LIMIT, 'HH24:MI') END AS STARTTIME_LIMIT,
                                                    CASE WHEN T1.ENDTIME_LIMIT IS NULL THEN TO_CHAR(T1.END_TIME, 'HH24:MI') ELSE TO_CHAR(T1.ENDTIME_LIMIT, 'HH24:MI') END AS ENDTIME_LIMIT,
                                                    TO_CHAR(T2.PLAN_DATE, 'YYYY-MM-DD') AS PLAN_DATE, 
                                                    TO_CHAR(T2.PLAN_DATE + T0.PLAN_DURATION, 'YYYY-MM-DD') AS PLAN_ENDDATE,
                                                    T3.EMPLOYEE_ID
                                                FROM TMS_TIME_PLAN T0
                                                    INNER JOIN TMS_TIME_PLAN_DETAIL T1 ON T0.PLAN_ID = T1.PLAN_ID
                                                    INNER JOIN TMS_EMPLOYEE_TIME_PLAN_DTL T2 ON T1.PLAN_ID = T2.PLAN_ID
                                                    INNER JOIN TMS_EMPLOYEE_TIME_PLAN_HDR T3 ON T2.TIME_PLAN_ID = T3.ID
                                                    WHERE T1.ACC_TYPE = '". $acc['ACC_TYPE'] ."' AND T0.PLAN_ID = ". $row['PLAN_ID'] ."" . ($singleEmployee ? " AND T3.EMPLOYEE_ID = " . $join : "") . "
                                            ) src ON (trg.EMPLOYEE_ID = src.EMPLOYEE_ID AND TO_CHAR(trg.ATTENDANCE_DATE , 'YYYY-MM-DD')= src.PLAN_DATE) 
                                            WHEN MATCHED THEN UPDATE
                                            SET trg.S0$n1 = src.STARTTIME_LIMIT, 
                                                trg.S0$n9 = src.ENDTIME_LIMIT, 
                                                trg.S0$n6 = src.STARTTIME,  
                                                trg.S0$n14 = src.ENDTIME");

                                if ((int) $getPlanTime['PLAN_DURATION'] > 0) {
                                    $resultMerge1 = $this->db->Execute(
                                                    "MERGE
                                                        INTO tms_attendance trg
                                                        USING   ( 
                                                                SELECT DISTINCT
                                                                    T1.ACC_TYPE,
                                                                    T0.PLAN_DURATION,
                                                                    TO_CHAR(T1.START_TIME, 'HH24:MI') AS STARTTIME,
                                                                    TO_CHAR(T1.END_TIME, 'HH24:MI') AS ENDTIME,
                                                                    CASE WHEN T1.STARTTIME_LIMIT IS NULL THEN TO_CHAR(T1.START_TIME, 'HH24:MI') ELSE TO_CHAR(T1.STARTTIME_LIMIT, 'HH24:MI') END AS STARTTIME_LIMIT,
                                                                    CASE WHEN T1.ENDTIME_LIMIT IS NULL THEN TO_CHAR(T1.END_TIME, 'HH24:MI') ELSE TO_CHAR(T1.ENDTIME_LIMIT, 'HH24:MI') END AS ENDTIME_LIMIT,
                                                                    TO_CHAR(T2.PLAN_DATE, 'YYYY-MM-DD') AS PLAN_DATE,
                                                                    TO_CHAR(T2.PLAN_DATE + T0.PLAN_DURATION, 'YYYY-MM-DD') AS PLAN_ENDDATE,
                                                                    T3.EMPLOYEE_ID
                                                                FROM TMS_TIME_PLAN T0
                                                                    INNER JOIN TMS_TIME_PLAN_DETAIL T1 ON T0.PLAN_ID    = T1.PLAN_ID
                                                                    INNER JOIN TMS_EMPLOYEE_TIME_PLAN_DTL T2 ON T1.PLAN_ID = T2.PLAN_ID
                                                                    INNER JOIN TMS_EMPLOYEE_TIME_PLAN_HDR T3 ON T2.TIME_PLAN_ID = T3.ID
                                                                    WHERE T1.ACC_TYPE = '". $acc['ACC_TYPE'] ."' AND T0.PLAN_ID = ". $row['PLAN_ID'] ."" . ($singleEmployee ? " AND T3.EMPLOYEE_ID = " . $join : "") . "
                                                        ) src ON (trg.EMPLOYEE_ID = src.EMPLOYEE_ID AND TO_CHAR(trg.ATTENDANCE_DATE , 'YYYY-MM-DD')= src.PLAN_ENDDATE) 
                                                        WHEN MATCHED THEN UPDATE
                                                        SET trg.S0$n1 = src.STARTTIME_LIMIT, 
                                                                trg.S0$n9 = src.ENDTIME_LIMIT, 
                                                                trg.S0$n6 = src.STARTTIME, 
                                                                trg.S0$n14 = src.ENDTIME");
                                }

                                if ($resultMerge1) {
                                    $resultMerge2 = $this->db->Execute("MERGE
                                                                        INTO tms_attendance trg
                                                                        USING   ( 
                                                                            SELECT
                                                                            T.ID,
                                                                            MIN(STARTTIME_DIFF) AS STARTTIME_DIFF,
                                                                            MIN(ENDTIME_DIFF) AS ENDTIME_DIFF
                                                                          FROM (                                                                                
                                                                           SELECT DISTINCT
                                                                                A.ID,
                                                                                ROUND(DATEDIFF(
                                                                                    TO_DATE(TO_CHAR(A.ATTENDANCE_TIME, 'YYYY-MM-DD HH24:MI'), 'YYYY-MM-DD HH24:MI'),                                                                                    
                                                                                    TO_DATE(TO_CHAR(A.ATTENDANCE_TIME, 'YYYY-MM-DD ')||CASE WHEN '". $row['PLAN_DURATION'] ."' != '0' THEN '". $acc['STARTTIME_LIMIT'] ."' ELSE '". $acc['STARTTIME_LIMIT'] ."' END, 'YYYY-MM-DD HH24:MI') 
                                                                                )) / 60 AS STARTTIME_DIFF,
                                                                                ROUND(DATEDIFF(
                                                                                    TO_DATE(TO_CHAR(A.ATTENDANCE_TIME, 'YYYY-MM-DD ')||CASE WHEN '". $row['PLAN_DURATION'] ."' != '0' THEN '". $acc['ENDTIME_LIMIT'] ."' ELSE '". $acc['ENDTIME_LIMIT'] ."' END , 'YYYY-MM-DD HH24:MI'),
                                                                                    TO_DATE(TO_CHAR(A.ATTENDANCE_TIME, 'YYYY-MM-DD HH24:MI'), 'YYYY-MM-DD HH24:MI')
                                                                                )) / 60 AS ENDTIME_DIFF
                                                                            FROM TMS_ATTENDANCE A
                                                                            $innerJoin
                                                                            ) T
                                                                            GROUP BY ID                                                                                
                                                                        ) src ON (trg.ID = src.ID)
                                                                        WHEN MATCHED THEN UPDATE
                                                                    SET  trg.S0$n2 = src.STARTTIME_DIFF, trg.S0$n10 = src.ENDTIME_DIFF");

                                    if ((int) $getPlanTime['PLAN_DURATION'] > 0) {                                                               
                                        $resultMerge2 = $this->db->Execute("MERGE
                                                                        INTO TMS_ATTENDANCE trg
                                                                        USING   ( 
                                                                            SELECT
                                                                            T.ID,
                                                                            MIN(T.STARTTIME_DIFF) AS STARTTIME_DIFF,
                                                                            MIN(T.ENDTIME_DIFF) AS ENDTIME_DIFF,
                                                                            MIN(TO_CHAR(T.DIFF_MINUT, 'HH24:MI')) AS DIFF_MINUT
                                                                          FROM (                                                                                  
                                                                           SELECT
                                                                                A.ID,
                                                                                ROUND(DATEDIFF(
                                                                                    TO_DATE(TO_CHAR(A.ATTENDANCE_TIME, 'YYYY-MM-DD HH24:MI'), 'YYYY-MM-DD HH24:MI'),                                                                                    
                                                                                    TO_DATE(TO_CHAR(A.ATTENDANCE_TIME, 'YYYY-MM-DD ')||CASE WHEN '". $row['PLAN_DURATION'] ."' != '0' THEN '". $acc['STARTTIME_LIMIT'] ."' ELSE '". $acc['STARTTIME_LIMIT'] ."' END, 'YYYY-MM-DD HH24:MI')
                                                                                )) / 60 AS STARTTIME_DIFF,
                                                                                ROUND(DATEDIFF(
                                                                                    TO_DATE(TO_CHAR(A.ATTENDANCE_TIME, 'YYYY-MM-DD ')||CASE WHEN '". $row['PLAN_DURATION'] ."' != '0' THEN '". $acc['ENDTIME_LIMIT'] ."' ELSE '". $acc['ENDTIME_LIMIT'] ."' END , 'YYYY-MM-DD HH24:MI'),
                                                                                    TO_DATE(TO_CHAR(A.ATTENDANCE_TIME, 'YYYY-MM-DD HH24:MI'), 'YYYY-MM-DD HH24:MI')
                                                                                )) / 60 AS ENDTIME_DIFF,
                                                                                CASE WHEN ROUND(DATEDIFF(LAG(A.ATTENDANCE_DATE) OVER( ORDER BY A.ATTENDANCE_DATE), A.ATTENDANCE_DATE)) / 60 >= $tmsRestTimeRange THEN LAG(A.ATTENDANCE_DATE) OVER( ORDER BY A.ATTENDANCE_DATE) ELSE NULL END AS DIFF_MINUT
                                                                            FROM TMS_ATTENDANCE A
                                                                            $innerJoin1
                                                                            ORDER BY A.ATTENDANCE_DATE
                                                                            ) T
                                                                            GROUP BY ID                                                                                  
                                                                        ) src ON (trg.ID = src.ID)
                                                                        WHEN MATCHED THEN UPDATE
                                                                    SET  trg.S0$n2 = src.STARTTIME_DIFF, trg.S0$n12 = src.DIFF_MINUT, trg.S0$n10 = src.ENDTIME_DIFF");
                                    }

                                    if ($resultMerge2) {
                                        $andJoin = " AND A.ATTENDANCE_DATE >= TO_DATE(TO_CHAR(A.ATTENDANCE_DATE, 'YYYY-MM-DD')||' '||'".$isTmsNightShift."', 'YYYY-MM-DD HH24:MI')";

                                        if($row['PLAN_DURATION'] > 0) {
                                            $andJoin = '';
                                        }

                                        $this->db->Execute("MERGE
                                                                INTO TMS_ATTENDANCE TRG  
                                                                USING ( 
                                                                SELECT DISTINCT
                                                                    MAX(ID) AS ID,
                                                                    T0.EMPLOYEE_ID,
                                                                    T1.ATTENDANCE_DATE
                                                                    FROM TMS_ATTENDANCE T0
                                                                    INNER JOIN (
                                                                        SELECT 
                                                                            T0.EMPLOYEE_ID, 
                                                                            T0.ATTENDANCE_DATE, 
                                                                            CASE WHEN '". $row['PLAN_DURATION'] ."' != '0' 
                                                                                THEN MAX(S0$n2) 
                                                                                ELSE MAX(S0$n2) END AS S0$n2
                                                                        FROM (
                                                                            SELECT A.EMPLOYEE_ID,
                                                                                TO_CHAR(A.ATTENDANCE_DATE, 'YYYY-MM-DD') AS ATTENDANCE_DATE,
                                                                                A.S0$n2
                                                                            FROM TMS_ATTENDANCE A
                                                                            $innerJoin        
                                                                            $andJoin
                                                                        ) T0
                                                                      GROUP BY T0.EMPLOYEE_ID, T0.ATTENDANCE_DATE 
                                                                    ) T1 ON T0.EMPLOYEE_ID = T1.EMPLOYEE_ID AND T0.S0$n2 = T1.S0$n2 AND TO_CHAR(T0.ATTENDANCE_DATE, 'YYYY-MM-DD') = T1.ATTENDANCE_DATE
                                                                    GROUP BY T0.EMPLOYEE_ID, T1.ATTENDANCE_DATE
                                                                ) src ON (trg.ID = src.ID) 
                                                            WHEN MATCHED THEN UPDATE
                                                            SET trg.S0$n3 = 1, trg.S0$n4 = 1, trg.S0$n5 = TO_CHAR(trg.ATTENDANCE_TIME, 'HH24:MI')");

                                        $this->db->Execute("MERGE
                                                                INTO TMS_ATTENDANCE trg  
                                                                USING   (
                                                                    SELECT DISTINCT
                                                                    MAX(ID) AS ID,
                                                                    T0.EMPLOYEE_ID,
                                                                    T1.ATTENDANCE_DATE
                                                                    FROM TMS_ATTENDANCE T0
                                                                    INNER JOIN (
                                                                        SELECT 
                                                                            T0.EMPLOYEE_ID, 
                                                                            T0.ATTENDANCE_DATE, 
                                                                            CASE WHEN '". $row['PLAN_DURATION'] ."' != '0' 
                                                                                THEN MAX(S0$n10) 
                                                                                ELSE MAX(S0$n10) END AS S0$n10
                                                                        FROM (
                                                                            SELECT 
                                                                                A.EMPLOYEE_ID,
                                                                                TO_CHAR(A.ATTENDANCE_DATE, 'YYYY-MM-DD') AS ATTENDANCE_DATE,
                                                                                A.S0$n10
                                                                            FROM TMS_ATTENDANCE A
                                                                            $innerJoin
                                                                        ) T0
                                                                        GROUP BY T0.EMPLOYEE_ID, T0.ATTENDANCE_DATE 
                                                                    ) T1 ON T0.EMPLOYEE_ID = T1.EMPLOYEE_ID AND T0.S0$n10 = T1.S0$n10 AND TO_CHAR(T0.ATTENDANCE_DATE, 'YYYY-MM-DD') = T1.ATTENDANCE_DATE
                                                                    GROUP BY T0.EMPLOYEE_ID, T1.ATTENDANCE_DATE
                                                                ) src ON (trg.ID = src.ID) 
                                                            WHEN MATCHED THEN UPDATE
                                                            SET trg.S0$n11 = 1, trg.S0$n13 = TO_CHAR(trg.ATTENDANCE_TIME, 'HH24:MI')");

                                        if ((int) $getPlanTime['PLAN_DURATION'] == 0) {
                                            $this->db->Execute("MERGE
                                                                    INTO TMS_ATTENDANCE TRG  
                                                                    USING ( 
                                                                    SELECT DISTINCT
                                                                        T0.EMPLOYEE_ID,
                                                                        MIN(T0.ATTENDANCE_DATE) AS ATTENDANCE_DATE
                                                                        FROM TMS_ATTENDANCE T0
                                                                        LEFT JOIN (
                                                                            SELECT HDR.EMPLOYEE_ID, T2.PLAN_DATE, TO_CHAR(T2.PLAN_DATE + T3.PLAN_DURATION, 'YYYY-MM-DD') AS AS_PLAN_DATE, '1' AS CHECK_PLAN 
                                                                            FROM TMS_EMPLOYEE_TIME_PLAN_HDR HDR
                                                                            INNER JOIN TMS_EMPLOYEE_TIME_PLAN_DTL T2 ON HDR.ID = T2.TIME_PLAN_ID
                                                                            INNER JOIN TMS_TIME_PLAN T3 ON T3.PLAN_ID = T2.PLAN_ID
                                                                            WHERE 1 = 1" . ($singleEmployee ? " AND HDR.EMPLOYEE_ID = " . $join : "") . "
                                                                        ) T12 ON T0.EMPLOYEE_ID = T12.EMPLOYEE_ID AND (TO_CHAR(T0.ATTENDANCE_DATE, 'YYYY-MM-DD') = TO_CHAR(T12.PLAN_DATE, 'YYYY-MM-DD') OR TO_CHAR(T0.ATTENDANCE_DATE, 'YYYY-MM-DD') = T12.AS_PLAN_DATE)
                                                                        WHERE TO_CHAR(T0.ATTENDANCE_DATE, 'YYYYMMDD') BETWEEN '$startDate' AND '$endDate'" . ($singleEmployee ? " AND T0.EMPLOYEE_ID = " . $join : "") . "
                                                                        GROUP BY T0.EMPLOYEE_ID, TO_CHAR(ATTENDANCE_DATE, 'YYYYMMDD')
                                                                    ) SRC ON (TRG.EMPLOYEE_ID = SRC.EMPLOYEE_ID AND TRG.ATTENDANCE_DATE = SRC.ATTENDANCE_DATE) 
                                                                WHEN MATCHED THEN UPDATE
                                                                SET trg.S003 = 1, trg.S004 = 1, trg.S0$n15 = TO_CHAR(TRG.ATTENDANCE_TIME, 'HH24:MI')");
                                        }

                                        if ((int) $getPlanTime['PLAN_DURATION'] > 0) {          

                                            $this->db->Execute("MERGE
                                                    INTO tms_attendance trg  
                                                    USING ( 
                                                        SELECT
                                                            MAX(ID) AS ID,
                                                            T0.EMPLOYEE_ID,
                                                            T1.ATTENDANCE_DATE,
                                                            T1.S0$n12
                                                            FROM TMS_ATTENDANCE T0
                                                            INNER JOIN (
                                                                SELECT 
                                                                    T0.EMPLOYEE_ID, 
                                                                    T0.ATTENDANCE_DATE, 
                                                                    MIN(S0$n12) AS S0$n12,
                                                                    CASE WHEN MIN(T0.S0$n12) IS NULL THEN MAX(T0.S0$n2) ELSE NULL END AS S0$n2
                                                                FROM (
                                                                    SELECT A.EMPLOYEE_ID,
                                                                        TO_CHAR(A.ATTENDANCE_DATE, 'YYYY-MM-DD') AS ATTENDANCE_DATE,
                                                                        A.S0$n2,
                                                                        S0$n12
                                                                    FROM TMS_ATTENDANCE A
                                                                    $innerJoin1
                                                                ) T0
                                                              GROUP BY T0.EMPLOYEE_ID, T0.ATTENDANCE_DATE 
                                                            ) T1 ON T0.EMPLOYEE_ID = T1.EMPLOYEE_ID AND CASE WHEN T0.S0$n12 IS NOT NULL THEN T0.S0$n12 ELSE TO_CHAR(T0.S0$n2) END = CASE WHEN T0.S0$n12 IS NOT NULL THEN T1.S0$n12 ELSE TO_CHAR(T1.S0$n2) END AND TO_CHAR(T0.ATTENDANCE_DATE, 'YYYY-MM-DD') = T1.ATTENDANCE_DATE
                                                            GROUP BY T0.EMPLOYEE_ID, T1.ATTENDANCE_DATE, T1.S0$n12
                                                        ) src ON (trg.ID = src.ID) 
                                                    WHEN MATCHED THEN UPDATE
                                                    SET trg.S0$n3 = 1, trg.S0$n4 = 1, trg.S0$n5 = TO_CHAR(trg.ATTENDANCE_TIME, 'HH24:MI')");

//                                                if ($row['PLAN_ID'] == '1519022250448') {
//                                                    echo "                                                                SELECT DISTINCT
//                                                                MAX(ID) AS ID,
//                                                                T0.EMPLOYEE_ID,                                                                
//                                                                CASE WHEN MAX(T0.ATTENDANCE_DATE) = MIN(T0.ATTENDANCE_DATE) THEN 
//                                                                  MIN(T1.NEXT_ATTENDANCE_DATE) 
//                                                                WHEN MAX(T0.ATTENDANCE_DATE) > TO_DATE(TO_CHAR(MAX(T0.ATTENDANCE_DATE), 'YYYY-MM-DD ')||MIN(T0.S012), 'YYYY-MM-DD HH24:MI') THEN
//                                                                  MAX(T1.NEXT_ATTENDANCE_DATE)
//                                                                ELSE 
//                                                                  NULL 
//                                                                END AS S0$n12                                                                    
//                                                                FROM TMS_ATTENDANCE T0
//                                                                INNER JOIN (
//                                                                    SELECT 
//                                                                        T0.EMPLOYEE_ID, 
//                                                                        T0.ATTENDANCE_DATE,                                                                         
//                                                                        MIN(S0$n12) AS S0$n12,
//                                                                        MAX(S0$n10) AS S0$n10,
//                                                                        MAX(T0.NEXT_ATTENDANCE_DATE) AS NEXT_ATTENDANCE_DATE
//                                                                    FROM (
//                                                                        SELECT 
//                                                                            A.EMPLOYEE_ID,
//                                                                            TO_CHAR(A.ATTENDANCE_DATE, 'YYYY-MM-DD') AS ATTENDANCE_DATE,
//                                                                            A.S0$n10,
//                                                                            S0$n12,
//                                                                            LEAD(A.ATTENDANCE_DATE) OVER(PARTITION BY A.EMPLOYEE_ID ORDER BY A.EMPLOYEE_ID, A.ATTENDANCE_DATE) AS NEXT_ATTENDANCE_DATE
//                                                                        FROM TMS_ATTENDANCE A
//                                                                        $innerJoin1
//                                                                        ORDER BY A.EMPLOYEE_ID, A.ATTENDANCE_DATE
//                                                                    ) T0
//                                                                    GROUP BY T0.EMPLOYEE_ID, T0.ATTENDANCE_DATE 
//                                                                ) T1 ON T0.EMPLOYEE_ID = T1.EMPLOYEE_ID AND CASE WHEN T0.S0$n12 IS NOT NULL THEN T0.S0$n12 ELSE TO_CHAR(T0.S0$n10) END = CASE WHEN T0.S0$n12 IS NOT NULL THEN T1.S0$n12 ELSE TO_CHAR(T1.S0$n10) END AND TO_CHAR(T0.ATTENDANCE_DATE, 'YYYY-MM-DD') = T1.ATTENDANCE_DATE
//                                                                GROUP BY T0.EMPLOYEE_ID, TO_CHAR(T0.ATTENDANCE_DATE, 'YYYYMMDD')"; die;
//                                                }
                                            $this->db->Execute("MERGE
                                                        INTO tms_attendance trg  
                                                        USING   (
                                                            SELECT DISTINCT
                                                            MAX(ID) AS ID,
                                                            T0.EMPLOYEE_ID,                                                                
                                                            T1.S0$n12
                                                            FROM TMS_ATTENDANCE T0
                                                            INNER JOIN (
                                                                SELECT 
                                                                    T0.EMPLOYEE_ID, 
                                                                    T0.ATTENDANCE_DATE,                                                                         
                                                                    MIN(S0$n12) AS S0$n12,
                                                                    MAX(S0$n10) AS S0$n10
                                                                FROM (
                                                                    SELECT 
                                                                        A.EMPLOYEE_ID,
                                                                        TO_CHAR(A.ATTENDANCE_DATE, 'YYYY-MM-DD') AS ATTENDANCE_DATE,
                                                                        A.S0$n10,
                                                                        S0$n12
                                                                    FROM TMS_ATTENDANCE A
                                                                    $innerJoin1
                                                                ) T0
                                                                GROUP BY T0.EMPLOYEE_ID, T0.ATTENDANCE_DATE 
                                                            ) T1 ON T0.EMPLOYEE_ID = T1.EMPLOYEE_ID AND CASE WHEN T0.S0$n12 IS NOT NULL THEN T0.S0$n12 ELSE TO_CHAR(T0.S0$n10) END = CASE WHEN T0.S0$n12 IS NOT NULL THEN T1.S0$n12 ELSE TO_CHAR(T1.S0$n10) END AND TO_CHAR(T0.ATTENDANCE_DATE, 'YYYY-MM-DD') = T1.ATTENDANCE_DATE
                                                            GROUP BY T0.EMPLOYEE_ID, T1.S0$n12
                                                        ) src ON (trg.ID = src.ID) 
                                                WHEN MATCHED THEN UPDATE
                                                SET trg.S0$n11 = 1, trg.S0$n13 = CASE WHEN src.S0$n12 IS NOT NULL THEN src.S0$n12 ELSE TO_CHAR(trg.ATTENDANCE_TIME, 'HH24:MI') END");
//                                                $this->db->Execute("MERGE
//                                                            INTO tms_attendance trg  
//                                                            USING   (
//                                                                SELECT DISTINCT
//                                                                MAX(ID) AS ID,
//                                                                T0.EMPLOYEE_ID,                                                                
//                                                                CASE WHEN MAX(T0.ATTENDANCE_DATE) = MIN(T0.ATTENDANCE_DATE) THEN 
//                                                                  MIN(T1.NEXT_ATTENDANCE_DATE) 
//                                                                WHEN MAX(T0.ATTENDANCE_DATE) > TO_DATE(TO_CHAR(MAX(T0.ATTENDANCE_DATE), 'YYYY-MM-DD ')||MIN(T0.S012), 'YYYY-MM-DD HH24:MI') THEN
//                                                                  MAX(T1.NEXT_ATTENDANCE_DATE)
//                                                                ELSE 
//                                                                  NULL 
//                                                                END AS S0$n12
//                                                                FROM TMS_ATTENDANCE T0
//                                                                INNER JOIN (
//                                                                    SELECT 
//                                                                        T0.EMPLOYEE_ID, 
//                                                                        T0.ATTENDANCE_DATE,                                                                         
//                                                                        MIN(S0$n12) AS S0$n12,
//                                                                        MAX(S0$n10) AS S0$n10,
//                                                                        MAX(T0.NEXT_ATTENDANCE_DATE) AS NEXT_ATTENDANCE_DATE
//                                                                    FROM (
//                                                                        SELECT 
//                                                                            A.EMPLOYEE_ID,
//                                                                            TO_CHAR(A.ATTENDANCE_DATE, 'YYYY-MM-DD') AS ATTENDANCE_DATE,
//                                                                            A.S0$n10,
//                                                                            S0$n12,
//                                                                            LEAD(A.ATTENDANCE_DATE) OVER(PARTITION BY A.EMPLOYEE_ID ORDER BY A.EMPLOYEE_ID, A.ATTENDANCE_DATE) AS NEXT_ATTENDANCE_DATE
//                                                                        FROM TMS_ATTENDANCE A
//                                                                        $innerJoin1
//                                                                        ORDER BY A.EMPLOYEE_ID, A.ATTENDANCE_DATE
//                                                                    ) T0
//                                                                    GROUP BY T0.EMPLOYEE_ID, T0.ATTENDANCE_DATE 
//                                                                ) T1 ON T0.EMPLOYEE_ID = T1.EMPLOYEE_ID AND CASE WHEN T0.S0$n12 IS NOT NULL THEN T0.S0$n12 ELSE TO_CHAR(T0.S0$n10) END = CASE WHEN T0.S0$n12 IS NOT NULL THEN T1.S0$n12 ELSE TO_CHAR(T1.S0$n10) END AND TO_CHAR(T0.ATTENDANCE_DATE, 'YYYY-MM-DD') = T1.ATTENDANCE_DATE
//                                                                GROUP BY T0.EMPLOYEE_ID, TO_CHAR(T0.ATTENDANCE_DATE, 'YYYYMMDD')
//                                                            ) src ON (trg.ID = src.ID) 
//                                                    WHEN MATCHED THEN UPDATE
//                                                    SET trg.S0$n11 = 1, trg.S0$n13 = CASE WHEN src.S0$n12 IS NOT NULL THEN TO_CHAR(src.S0$n12, 'HH24:MI') ELSE TO_CHAR(trg.ATTENDANCE_TIME, 'HH24:MI') END");
                                            }

                                        if ($sizeOf != $akey) {
                                            $n1 =  (int) $n1 + 16;
                                            $n2 =  (int) $n2 + 16;
                                            $n3 =  (int) $n3 + 16;
                                            $n4 =  (int) $n4 + 16;
                                            $n5 =  (int) $n5 + 16;
                                            $n6 =  (int) $n6 + 16;
                                            $n7 =  (int) $n7 + 16;
                                            $n8 =  (int) $n8 + 16;
                                            $n9 =  (int) $n9 + 16;
                                            $n10 =  (int) $n10 + 16; 
                                            $n11 =  (int) $n11 + 16; 
                                            $n12 =  (int) $n12 + 16; 
                                            $n13 =  (int) $n13 + 16; 
                                            $n14 =  (int) $n14 + 16; 
                                            $n15 =  (int) $n15 + 16; 
                                            $n16 =  (int) $n16 + 16; 
                                        }
                                    }
                                }
                            }
                        }
                    }

                    $leaveLate002 = 'S002';
                    $leaveLate005 = 'S005';

                    if($row['IS_LATE'] == '1') {
                        $leaveLate002 = '0';
                        $leaveLate005 = "'" . $getPlanTime['START_TIME'] . "'";
                    }

                    $dividPlanTime = $planTime / 2;
                    $selectResult = "SELECT DISTINCT
                                        EMPLOYEE_ID,
                                        DEPARTMENT_ID,
                                        TO_DATE(ATTENDANCE_DATE, 'YYYY-MM-DD') AS ATTENDANCE_DATE,
                                        INTIME,
                                        OUTTIME,
                                        CASE WHEN UNCLEARTIME > 0 THEN UNCLEARTIME ELSE 0 END AS UNCLEARTIME,
                                        CASE 
                                            WHEN CHECK_PLAN_NIGHT = 1 THEN "
                                                .$planTime."-" . ($customerConfig == '1' ? "CASE WHEN OUTTIME IS NULL OR INTIME IS NULL THEN 0 ELSE LATE_TIME_NOTLIMIT + EARLY_TIME END" : "LATE_TIME_NOTLIMIT") . "
                                            WHEN CLEARTIME > 0 AND OUTTIME IS NOT NULL THEN "
                                                .($customerConfig == '1' ? "CASE WHEN LATE_TIME + EARLY_TIME > $dividPlanTime THEN 0 ELSE $planTime END" : "CASE WHEN CLEARTIME > $planTime THEN $planTime ELSE CLEARTIME END")."
                                            ELSE 
                                                0 
                                            END AS CLEARTIME,
                                        CASE 
                                            WHEN CHECK_PLAN_NIGHT = 1 THEN
                                                -LATE_TIME_NOTLIMIT
                                        ELSE
                                            " . ($customerConfig == '1' ? "0" : "CASE WHEN CLEARTIME > 0 THEN CLEARTIME + LATE_TIME ELSE 0 END - $planTime") . " 
                                        END AS DEFFERENCE_TIME,
                                        " . ($customerConfig == '1' ? "CASE WHEN OUTTIME IS NULL OR INTIME IS NULL THEN 0 ELSE (CASE WHEN LATE_TIME + EARLY_TIME > $dividPlanTime THEN 0 ELSE LATE_TIME + EARLY_TIME END) END" : "LATE_TIME") . " AS LATE_TIME,
                                        " . ($customerConfig == '1' ? "0" : "EARLY_TIME") . " AS EARLY_TIME,
                                        CASE WHEN '". $row['PLAN_DURATION'] ."' != '0' AND OUTTIME IS NOT NULL
                                            THEN
                                            (ROUND (DATEDIFF(
                                                TO_DATE(ATTENDANCE_DATE||
                                                    CASE WHEN '". $nightStartDate ."' < IN_TIME
                                                        THEN IN_TIME
                                                        ELSE '". $nightStartDate ."'
                                                    END, 'YYYY-MM-DD HH24:MI'),
                                                TO_DATE(ATTENDANCE_DATE||
                                                CASE WHEN '". $nightEndDate ."' < OUT_TIME 
                                                    THEN '". $nightEndDate ."'
                                                    ELSE OUT_TIME
                                                END 
                                                , 'YYYY-MM-DD HH24:MI') + '". $row['PLAN_DURATION'] ."'
                                            )) / 60)
                                        ELSE 0 END AS NIGHT_TIME,
                                        ISMANUAL,
                                        " . ($customerConfig == '1' ? "CASE WHEN LATE_TIME + EARLY_TIME > $dividPlanTime THEN $planTime ELSE (CASE WHEN OUTTIME IS NULL OR INTIME IS NULL THEN $planTime ELSE 0 END) END" : "0") . " AS CAUSE13
                                    FROM (
                                        SELECT 
                                            T0.EMPLOYEE_ID,
                                            EMP.DEPARTMENT_ID,
                                            TO_CHAR(T0.ATTENDANCE_DATE, 'YYYY-MM-DD') AS ATTENDANCE_DATE,
                                            T1.S005 AS IN_TIME,
                                            CASE WHEN T3.S0$n15 IS NOT NULL AND T1.S005 <> T3.S0$n15 AND T3.S0$n15 < '".$isTmsNightShift."' AND '".$row['PLAN_DURATION']."' = '0' THEN
                                                TO_DATE(TO_CHAR(T0.ATTENDANCE_DATE, 'YYYY-MM-DD') ||' '||T3.S0$n15, 'YYYY-MM-DD HH24:MI') 
                                            WHEN T2.S0$n13 IS NOT NULL AND T1.S005 <> T2.S0$n13 THEN
                                                TO_DATE(TO_CHAR(T0.ATTENDANCE_DATE, 'YYYY-MM-DD') ||' '||T2.S0$n13, 'YYYY-MM-DD HH24:MI') 
                                            WHEN T3.S0$n13 IS NOT NULL AND T1.S005 <> T3.S0$n13 THEN
                                                TO_DATE(TO_CHAR(T0.ATTENDANCE_DATE, 'YYYY-MM-DD') ||' '||T3.S0$n13, 'YYYY-MM-DD HH24:MI') 
                                            WHEN T4.S0$n13 IS NOT NULL AND T1.S005 <> T4.S0$n13 AND '".$row['PLAN_DURATION']."' != '0' AND T2.S0$n13 IS NULL THEN
                                                TO_DATE(TO_CHAR(T0.ATTENDANCE_DATE, 'YYYY-MM-DD') ||' '||T4.S0$n13, 'YYYY-MM-DD HH24:MI') 
                                            ELSE 
                                                NULL END AS OUTTIME,
                                            T2.S0$n13 AS OUT_TIME,
                                            TO_DATE(TO_CHAR(T0.ATTENDANCE_DATE, 'YYYY-MM-DD')||' '||T1.S005, 'YYYY-MM-DD HH24:MI') AS INTIME,
                                            CASE
                                                WHEN TO_DATE(T2.ATTENDANCE_DATE||CASE WHEN T2.S0$n13 <= T2.S0$n9 THEN T2.S0$n13 ELSE T2.S0$n9 END , 'YYYY-MM-DD HH24:MI') <= TO_DATE(T2.ATTENDANCE_DATE||'".$getPlanTime['START_TIME_END_TIME']."', 'YYYY-MM-DD HH24:MI')
                                                THEN (ROUND (DATEDIFF(
                                                        TO_DATE(T1.ATTENDANCE_DATE||T1.S005, 'YYYY-MM-DD HH24:MI'),
                                                        TO_DATE(T2.ATTENDANCE_DATE||T2.S0$n13, 'YYYY-MM-DD HH24:MI')
                                                    )) / 60)
                                                ELSE (ROUND(DATEDIFF(
                                                        TO_DATE(T1.ATTENDANCE_DATE||T1.S005, 'YYYY-MM-DD HH24:MI'),
                                                        TO_DATE(T2.ATTENDANCE_DATE||T2.S0$n13, 'YYYY-MM-DD HH24:MI')
                                                    )) / 60) 
                                                    - CASE WHEN $lunchTimePlan > 0 THEN $lunchTimePlan ELSE 0 END
                                                    + CASE WHEN 0 > T2.S0$n10 AND T2.S0$n10 >= $defaultDefferenceTime THEN 0-T2.S0$n10 ELSE 0 END
                                            END
                                            AS UNCLEARTIME,
                                                (ROUND(DATEDIFF(
                                                    TO_DATE(T1.ATTENDANCE_DATE||CASE WHEN T1.S005 <= T1.S001 THEN T1.S001 ELSE 
                                                        (CASE WHEN TO_DATE(T1.ATTENDANCE_DATE||T1.S005, 'YYYY-MM-DD HH24:MI') >= TO_DATE(T1.ATTENDANCE_DATE||'".$getPlanTime['START_TIME_END_TIME']."', 'YYYY-MM-DD HH24:MI') 
                                                        AND TO_DATE(T1.ATTENDANCE_DATE||T1.S005, 'YYYY-MM-DD HH24:MI') <= TO_DATE(T1.ATTENDANCE_DATE||'".$getPlanTime['END_TIME_START_TIME']."', 'YYYY-MM-DD HH24:MI') 
                                                        THEN '".$getPlanTime['END_TIME_START_TIME']."' ELSE T1.S005 END) 
                                                    END, 'YYYY-MM-DD HH24:MI'),
                                                    CASE
                                                    WHEN T4.S0$n13 IS NOT NULL AND T1.S005 <> T4.S0$n13 AND '".$row['PLAN_DURATION']."' != '0' AND T2.S0$n13 IS NULL THEN
                                                        (CASE WHEN TO_DATE(T4.ATTENDANCE_DATE||T4.S0$n13, 'YYYY-MM-DD HH24:MI') >= TO_DATE(T4.ATTENDANCE_DATE||'".$getPlanTime['START_TIME_END_TIME']."', 'YYYY-MM-DD HH24:MI') 
                                                               AND TO_DATE(T4.ATTENDANCE_DATE||T4.S0$n13, 'YYYY-MM-DD HH24:MI') <= TO_DATE(T4.ATTENDANCE_DATE||'".$getPlanTime['END_TIME_START_TIME']."', 'YYYY-MM-DD HH24:MI')
                                                            THEN 
                                                                TO_DATE(T4.ATTENDANCE_DATE||'".$getPlanTime['START_TIME_END_TIME']."', 'YYYY-MM-DD HH24:MI')
                                                            ELSE 
                                                                TO_DATE(T4.ATTENDANCE_DATE||T4.S0$n13, 'YYYY-MM-DD HH24:MI')
                                                        END)
                                                    ELSE
                                                        (CASE WHEN TO_DATE(T2.ATTENDANCE_DATE||T2.S0$n13, 'YYYY-MM-DD HH24:MI') >= TO_DATE(T2.ATTENDANCE_DATE||'".$getPlanTime['START_TIME_END_TIME']."', 'YYYY-MM-DD HH24:MI') 
                                                               AND TO_DATE(T2.ATTENDANCE_DATE||T2.S0$n13, 'YYYY-MM-DD HH24:MI') <= TO_DATE(T2.ATTENDANCE_DATE||'".$getPlanTime['END_TIME_START_TIME']."', 'YYYY-MM-DD HH24:MI')
                                                            THEN TO_DATE(T2.ATTENDANCE_DATE||'".$getPlanTime['START_TIME_END_TIME']."', 'YYYY-MM-DD HH24:MI')
                                                            ELSE 
                                                            CASE WHEN TO_DATE(T2.ATTENDANCE_DATE||T2.S0$n13, 'YYYY-MM-DD HH24:MI') >= TO_DATE(T2.ATTENDANCE_DATE||'".$getPlanTime['END_TIME']."', 'YYYY-MM-DD HH24:MI')
                                                                   OR TO_DATE(T2.ATTENDANCE_DATE||T2.S0$n13, 'YYYY-MM-DD HH24:MI') >= TO_DATE(T2.ATTENDANCE_DATE||'".$getPlanTime['ENDTIME_LIMIT']."', 'YYYY-MM-DD HH24:MI')
                                                            THEN TO_DATE(T2.ATTENDANCE_DATE||'".$getPlanTime['END_TIME']."', 'YYYY-MM-DD HH24:MI')
                                                            ELSE TO_DATE(T2.ATTENDANCE_DATE||T2.S0$n13, 'YYYY-MM-DD HH24:MI') END
                                                        END)
                                                    END
                                                )) / 60)
                                                +
                                                (ROUND(DATEDIFF(
                                                    TO_DATE(TO_CHAR(T0.ATTENDANCE_DATE, 'YYYY-MM-DD ')||T1.S006, 'YYYY-MM-DD HH24:MI'),
                                                    TO_DATE(TO_CHAR(T0.ATTENDANCE_DATE, 'YYYY-MM-DD ')||T1.S001, 'YYYY-MM-DD HH24:MI')
                                                )) / 60)
                                                + CASE WHEN 0 > T2.S0$n10 AND (T2.S0$n10 >= $defaultDefferenceTime OR $customerConfig = '1') THEN 0-T2.S0$n10 ELSE 0 END                                               
                                                - (CASE WHEN
                                                    (TO_DATE(T1.ATTENDANCE_DATE||T1.S005, 'YYYY-MM-DD HH24:MI') >= TO_DATE(T1.ATTENDANCE_DATE||'".$getPlanTime['START_TIME_END_TIME']."', 'YYYY-MM-DD HH24:MI') 
                                                        AND TO_DATE(T1.ATTENDANCE_DATE||T1.S005, 'YYYY-MM-DD HH24:MI') <= TO_DATE(T1.ATTENDANCE_DATE||'".$getPlanTime['END_TIME_START_TIME']."', 'YYYY-MM-DD HH24:MI'))
                                                    OR (
                                                        TO_DATE(T2.ATTENDANCE_DATE||T2.S0$n13, 'YYYY-MM-DD HH24:MI') >= TO_DATE(T2.ATTENDANCE_DATE||'".$getPlanTime['START_TIME_END_TIME']."', 'YYYY-MM-DD HH24:MI') 
                                                        AND TO_DATE(T2.ATTENDANCE_DATE||T2.S0$n13, 'YYYY-MM-DD HH24:MI') <= TO_DATE(T2.ATTENDANCE_DATE||'".$getPlanTime['END_TIME_START_TIME']."', 'YYYY-MM-DD HH24:MI')                                                            
                                                    )
                                                    OR (
                                                        TO_DATE(T2.ATTENDANCE_DATE||T2.S0$n13, 'YYYY-MM-DD HH24:MI') <= TO_DATE(T2.ATTENDANCE_DATE||'".$getPlanTime['END_TIME_START_TIME']."', 'YYYY-MM-DD HH24:MI')
                                                        AND TO_DATE(T1.ATTENDANCE_DATE||T1.S005, 'YYYY-MM-DD HH24:MI') >= TO_DATE(T1.ATTENDANCE_DATE||'".$getPlanTime['START_TIME_END_TIME']."', 'YYYY-MM-DD HH24:MI')
                                                    )
                                                    OR (
                                                        TO_DATE(T1.ATTENDANCE_DATE||T1.S005, 'YYYY-MM-DD HH24:MI') >= TO_DATE(T1.ATTENDANCE_DATE||'".$getPlanTime['END_TIME_START_TIME']."', 'YYYY-MM-DD HH24:MI')
                                                    )
                                                    OR (
                                                        TO_DATE(T2.ATTENDANCE_DATE||T2.S0$n13, 'YYYY-MM-DD HH24:MI') <= TO_DATE(T2.ATTENDANCE_DATE||'".$getPlanTime['START_TIME_END_TIME']."', 'YYYY-MM-DD HH24:MI')
                                                    )
                                                THEN 0 ELSE (CASE WHEN $lunchTimePlan > 0 THEN $lunchTimePlan ELSE 0 END) END)
                                             AS CLEARTIME,
                                            CASE
                                                WHEN TO_DATE(T2.ATTENDANCE_DATE||CASE WHEN T2.S0$n13 <= T2.S0$n9 THEN T2.S0$n13 ELSE T2.S0$n9 END , 'YYYY-MM-DD HH24:MI') <= TO_DATE(T2.ATTENDANCE_DATE||'".$getPlanTime['START_TIME_END_TIME']."', 'YYYY-MM-DD HH24:MI')                                                        
                                                THEN 1
                                                ELSE 0
                                            END
                                            AS ISCHECK,
                                            0 AS DEFFERENCE_TIME,
                                            1 AS ISMANUAL,
                                            CASE WHEN 0 > T1.S002 AND T1.S002 >= $defaultDefferenceTime THEN 0-T1.S002 ELSE 0 END AS LATE_TIME,
                                            CASE WHEN 0 > T2.S0$n10 AND (T2.S0$n10 >= $defaultDefferenceTime OR $customerConfig = '1') THEN
                                                    0-T2.S0$n10 
                                                    - (CASE WHEN TO_DATE(T2.ATTENDANCE_DATE||T2.S0$n13, 'YYYY-MM-DD HH24:MI') > TO_DATE(T2.ATTENDANCE_DATE||'".$getPlanTime['START_TIME_END_TIME']."', 'YYYY-MM-DD HH24:MI')
                                                        THEN 0 ELSE (CASE WHEN $lunchTimePlan > 0 THEN $lunchTimePlan ELSE 0 END) END)
                                                ELSE 0 END AS EARLY_TIME,
                                            CASE WHEN T11.EMPLOYEE_ID IS NULL THEN 0 ELSE 1 END AS CHECK_STATUS,
                                            CASE WHEN T3.EMPLOYEE_ID IS NULL THEN 0 ELSE 1 END AS CHECK_PLAN_NIGHT,
                                            CASE WHEN 0 > T1.S002 THEN 0-T1.S002 ELSE 0 END AS LATE_TIME_NOTLIMIT
                                        FROM TMS_ATTENDANCE T0
                                        INNER JOIN (
                                            SELECT 
                                                EMPLOYEE_ID, 
                                                TO_CHAR(ATTENDANCE_DATE, 'YYYY-MM-DD') AS ATTENDANCE_DATE,
                                                S001,
                                                $leaveLate002 AS S002,
                                                $leaveLate005 AS S005,
                                                S006 
                                            FROM TMS_ATTENDANCE WHERE S005 IS NOT NULL 
                                            AND TO_CHAR(ATTENDANCE_DATE, 'YYYYMMDD') BETWEEN '$startDate' AND '$planDurationEndDate'
                                        ) T1 ON T0.EMPLOYEE_ID = T1.EMPLOYEE_ID AND TO_CHAR(T0.ATTENDANCE_DATE, 'YYYY-MM-DD') = T1.ATTENDANCE_DATE
                                        LEFT JOIN (
                                            SELECT 
                                                EMPLOYEE_ID, 
                                                TO_CHAR(ATTENDANCE_DATE, 'YYYY-MM-DD') AS ATTENDANCE_DATE,
                                                S0$n9,
                                                S0$n10,
                                                S0$n13, 
                                                S0$n14  
                                            FROM TMS_ATTENDANCE WHERE S0$n13 IS NOT NULL 
                                            AND TO_CHAR(ATTENDANCE_DATE, 'YYYYMMDD') BETWEEN '$startDate' AND '$planDurationEndDate'
                                        ) T2 ON T0.EMPLOYEE_ID = T2.EMPLOYEE_ID AND TO_CHAR(T0.ATTENDANCE_DATE + '". $row['PLAN_DURATION'] ."', 'YYYY-MM-DD') = T2.ATTENDANCE_DATE
                                        LEFT JOIN (
                                            SELECT 
                                                EMPLOYEE_ID, 
                                                TO_CHAR(ATTENDANCE_DATE, 'YYYY-MM-DD') AS ATTENDANCE_DATE,
                                                S0$n9,
                                                S0$n10,
                                                S0$n13, 
                                                S0$n15, 
                                                S0$n14  
                                            FROM TMS_ATTENDANCE WHERE S0$n15 IS NOT NULL
                                            AND TO_CHAR(ATTENDANCE_DATE, 'YYYYMMDD') BETWEEN '$startDate' AND '$planDurationEndDate'
                                            AND ATTENDANCE_DATE < TO_DATE(TO_CHAR(ATTENDANCE_DATE, 'YYYY-MM-DD')||' ".$isTmsNightShift."', 'YYYY-MM-DD HH24:MI')
                                        ) T3 ON T0.EMPLOYEE_ID = T3.EMPLOYEE_ID AND TO_CHAR(T0.ATTENDANCE_DATE + '1', 'YYYY-MM-DD') = T3.ATTENDANCE_DATE
                                        LEFT JOIN (
                                            SELECT 
                                                EMPLOYEE_ID, 
                                                TO_CHAR(ATTENDANCE_DATE, 'YYYY-MM-DD') AS ATTENDANCE_DATE,
                                                S0$n9,
                                                S0$n10,
                                                S0$n13, 
                                                S0$n14  
                                            FROM TMS_ATTENDANCE WHERE S0$n13 IS NOT NULL 
                                            AND TO_CHAR(ATTENDANCE_DATE, 'YYYYMMDD') BETWEEN '$startDate' AND '$planDurationEndDate'
                                        ) T4 ON T0.EMPLOYEE_ID = T4.EMPLOYEE_ID AND TO_CHAR(T0.ATTENDANCE_DATE, 'YYYY-MM-DD') = T4.ATTENDANCE_DATE
                                        INNER JOIN TMS_EMPLOYEE_TIME_PLAN_HDR H ON T0.EMPLOYEE_ID = H.EMPLOYEE_ID AND H.YEAR_ID||'-'||CASE WHEN H.MONTH_ID < 10 THEN '0'||H.MONTH_ID ELSE TO_CHAR(H.MONTH_ID) END = TO_CHAR(T0.ATTENDANCE_DATE, 'YYYY-MM')
                                        INNER JOIN TMS_EMPLOYEE_TIME_PLAN_DTL B ON TO_CHAR(B.PLAN_DATE, 'YYYYMMDD') = TO_CHAR(T0.ATTENDANCE_TIME, 'YYYYMMDD') AND B.TIME_PLAN_ID = H.ID
                                        INNER JOIN (
                                            SELECT 
                                                DISTINCT
                                                pd.PLAN_DETAIL_ID,
                                                p.PLAN_ID, 
                                                TO_CHAR(pd.START_TIME, 'HH24:MI') AS START_TIME, 
                                                TO_CHAR(pd.END_TIME, 'HH24:MI') AS END_TIME ,
                                                pd.ORDER_NUM, 
                                                pd.IS_NIGHT_SHIFT,
                                                p.PLAN_DURATION,
                                                pd.STARTTIME_LIMIT,
                                                pd.ENDTIME_LIMIT,
                                                pd.ACC_TYPE
                                            FROM TMS_TIME_PLAN p 
                                            INNER JOIN TMS_TIME_PLAN_DETAIL pd on p.PLAN_ID = pd.PLAN_ID
                                            WHERE p.PLAN_ID = '". $row['PLAN_ID'] ."'
                                        ) C ON B.PLAN_ID =  C.PLAN_ID
                                        INNER JOIN HRM_EMPLOYEE_KEY EMP ON T0.EMPLOYEE_ID = EMP.EMPLOYEE_ID AND EMP.IS_ACTIVE = 1
                                        LEFT JOIN (
                                            SELECT EMPLOYEE_ID, BALANCE_DATE
                                            FROM TNA_TIME_BALANCE_HDR 
                                        ) T11 ON H.EMPLOYEE_ID = T11.EMPLOYEE_ID AND TO_CHAR(T0.ATTENDANCE_DATE, 'YYYY-MM-DD') = TO_CHAR(T11.BALANCE_DATE, 'YYYY-MM-DD')
                                        WHERE H.YEAR_ID = '$year' 
                                            AND H.MONTH_ID = TO_CHAR('$month') 
                                            AND C.PLAN_ID = '". $row['PLAN_ID'] ."' 
                                            AND TO_CHAR(T0.ATTENDANCE_DATE, 'YYYYMMDD') BETWEEN '$startDate' AND '$planDurationEndDate'" . ($singleEmployee ? " AND T0.EMPLOYEE_ID = " . $join : "") . "                                                    
                                    )
                                    WHERE CHECK_STATUS = 0
                                    GROUP BY 
                                        EMPLOYEE_ID,
                                        DEPARTMENT_ID,
                                        ATTENDANCE_DATE,
                                        INTIME,
                                        OUTTIME,
                                        OUT_TIME,
                                        IN_TIME,
                                        UNCLEARTIME,
                                        CLEARTIME,
                                        DEFFERENCE_TIME,
                                        LATE_TIME,
                                        EARLY_TIME,
                                        ISCHECK,
                                        CHECK_PLAN_NIGHT,
                                        LATE_TIME_NOTLIMIT,
                                        ISMANUAL";

//                        echo $selectResult; die;
                    //var_dump($plan); die;
                    //if ($row['PLAN_ID'] == '1507771320987') { echo $selectResult; die; }

                    $data = $this->db->GetAll($selectResult);

                    $overTime = Config::getFromCache('tmsOverTime');
                    $overTime = $overTime ? explode(',', $overTime) : array();
                    $overTime = '^' . join('^', $overTime) . '^';               

                    if ($data) {
                        $cause44 = 0;
                        if(Config::getFromCache('CONFIG_TNA_HISHIGARVIN'))
                            $cause44 = "CASE WHEN INSTR('$overTime', '^'||DEPARTMENT_ID||'^') = 0 OR (CASE WHEN DEFFERENCE_TIME < 60 AND DEFFERENCE_TIME < $defaultDefferenceTime THEN CLEARTIME + LATE_TIME ELSE CLEARTIME END < $planTime) THEN 0 ELSE 210 END";

                        $this->db->Execute("INSERT INTO TNA_TIME_BALANCE_HDR (
                            TIME_BALANCE_HDR_ID, 
                            EMPLOYEE_ID,  BALANCE_DATE, START_TIME, END_TIME, UNCLEAN_TIME, CLEAN_TIME,  DEFFERENCE_TIME, 
                            LATE_TIME, EARLY_TIME, 
                            ". $LATE_TYPE .", 
                            ". $EARLY_TYPE .",
                            NIGHT_TIME, IS_UPLOAD, CREATED_DATE, CREATED_USER_ID, IS_CONFIRMED, CAUSE4, CAUSE13)
                                SELECT 
                                    IMPORT_ID_SEQ.NEXTVAL,
                                    EMPLOYEE_ID,
                                    ATTENDANCE_DATE,
                                    INTIME,
                                    OUTTIME,
                                    UNCLEARTIME,
                                    CASE WHEN DEFFERENCE_TIME < 60 AND DEFFERENCE_TIME < $defaultDefferenceTime THEN CLEARTIME ".(Config::getFromCache('tmsIsLateTimeClearTime') == '1' ? "+LATE_TIME" : "")." ".($isEarlyTimeToClean == '1' ? "-EARLY_TIME" : "")." ELSE CLEARTIME ".($isEarlyTimeToClean == '1' ? "-EARLY_TIME" : "")." END AS CLEARTIME,
                                    CASE WHEN DEFFERENCE_TIME < 60 AND DEFFERENCE_TIME < $defaultDefferenceTime THEN DEFFERENCE_TIME ".(Config::getFromCache('tmsIsEarlyTimeToDifferenceTime') == '1' ? "-EARLY_TIME" : "+EARLY_TIME")." ELSE 0 END AS DEFFERENCE_TIME,
                                    LATE_TIME ".(Config::getFromCache('tmsEarlyTimeTolateTime') == '1' ? "+EARLY_TIME " : "")."AS LATE_TIME,
                                    ".($isEarlyTimeToClean == '1' ? "0" : "EARLY_TIME")." AS EARLY_TIME,
                                    LATE_TIME,
                                    ".($isEarlyTimeToClean == '1' ? "0" : "EARLY_TIME")." AS EARLY_TIME,
                                    NIGHT_TIME,
                                    ISMANUAL,
                                    SYSDATE,
                                    1, 
                                    CASE WHEN DEFFERENCE_TIME > -0.05 OR DEFFERENCE_TIME > $defaultDefferenceTime THEN 
                                        CASE WHEN $customerConfig = 1 AND ((INTIME IS NULL OR OUTTIME IS NULL) OR CAUSE13 > 0) THEN
                                            0
                                        ELSE
                                            1
                                        END
                                    ELSE 0 END AS IS_CONFIRMED,
                                    $cause44 AS CAUSE4,
                                    CAUSE13
                                    FROM ($selectResult)");

                        (Array) $response[$row['PLAN_ID']] = array();
                        $response[$row['PLAN_ID']] = $data;
                        $response = array('status' => 'success', 'message' => 'Амжилттай боллоо');

                    }
                }
                // </editor-fold>

            } else {

                if($isPlanDownload == 1) {
                    $this->db->Execute("DELETE FROM TMS_ATTENDANCE  WHERE TO_CHAR(ATTENDANCE_DATE, 'YYYYMMDD') BETWEEN '$startDate' AND '$endDate'");

                    $this->db->Execute("INSERT INTO TMS_ATTENDANCE (ID, EMPLOYEE_ID, ATTENDANCE_DATE, ATTENDANCE_TIME)
                                        SELECT 
                                            IMPORT_ID_SEQ.NEXTVAL, 
                                            EMPLOYEE_ID, 
                                            ATTENDANCE_DATE_TIME, 
                                            ATTENDANCE_DATE_TIME 
                                        FROM TNA_TIME_ATTENDANCE 
                                        WHERE TO_CHAR(ATTENDANCE_DATE_TIME, 'YYYYMMDD') BETWEEN '$startDate' AND '$endDate'
                                            AND EMPLOYEE_ID IN ($joinNotPlan)");                    
                }
            }

            // <editor-fold defaultstate="collapsed" desc="NO PLAN">
            if($isPlanDownload == 1) {
//                    echo "SELECT DISTINCT
//                                                    T0.EMPLOYEE_ID,
//                                                    MAX(T0.ATTENDANCE_DATE) AS ATTENDANCE_DATE,
//                                                    CASE WHEN MAX(T0.ATTENDANCE_DATE) = MIN(T0.ATTENDANCE_DATE) THEN 
//                                                      MIN(T0.NEXT_ATTENDANCE_DATE) 
//                                                    WHEN MAX(T0.ATTENDANCE_DATE) > TO_DATE(TO_CHAR(MAX(T0.ATTENDANCE_DATE), 'YYYY-MM-DD ')||MIN(T0.S012), 'YYYY-MM-DD HH24:MI') THEN
//                                                      MAX(T0.NEXT_ATTENDANCE_DATE)
//                                                    ELSE 
//                                                      NULL 
//                                                    END AS NEXT_ATTENDANCE_DATE
//                                                FROM (
//                                                  SELECT DISTINCT
//                                                    T0.EMPLOYEE_ID,
//                                                    T0.ATTENDANCE_DATE,
//                                                    LEAD(T0.ATTENDANCE_DATE) OVER(PARTITION BY T0.EMPLOYEE_ID ORDER BY T0.EMPLOYEE_ID, T0.ATTENDANCE_DATE) AS NEXT_ATTENDANCE_DATE,
//                                                    T0.S012
//                                                  FROM TMS_ATTENDANCE T0
//                                                  LEFT JOIN (
//                                                      SELECT HDR.EMPLOYEE_ID, T2.PLAN_DATE, '1' AS CHECK_PLAN 
//                                                      FROM TMS_EMPLOYEE_TIME_PLAN_HDR HDR
//                                                      INNER JOIN TMS_EMPLOYEE_TIME_PLAN_DTL T2 ON HDR.ID = T2.TIME_PLAN_ID
//                                                  ) T12 ON T0.EMPLOYEE_ID = T12.EMPLOYEE_ID AND TO_CHAR(T0.ATTENDANCE_DATE, 'YYYY-MM-DD') = TO_CHAR(T12.PLAN_DATE, 'YYYY-MM-DD')
//                                                  WHERE T0.S001 IS NULL AND (CHECK_PLAN IS NULL OR T0.EMPLOYEE_ID IN ($joinNotPlan)) AND TO_CHAR(T0.ATTENDANCE_DATE, 'YYYYMMDD') BETWEEN '$startDate' AND '$endDate'" . ($singleEmployee ? " AND T0.EMPLOYEE_ID = " . $join : "") . "
//                                                  ORDER BY T0.EMPLOYEE_ID, T0.ATTENDANCE_DATE
//                                                ) T0
//                                                GROUP BY T0.EMPLOYEE_ID, TO_CHAR(T0.ATTENDANCE_DATE, 'YYYYMMDD')
//                                                ORDER BY T0.EMPLOYEE_ID"; die;

                $this->db->Execute("MERGE
                                        INTO TMS_ATTENDANCE TRG  
                                        USING ( 
                                            SELECT DISTINCT
                                                T0.EMPLOYEE_ID,
                                                MAX(T0.ATTENDANCE_DATE) AS ATTENDANCE_DATE,
                                                CASE WHEN MAX(T0.ATTENDANCE_DATE) = MIN(T0.ATTENDANCE_DATE) THEN 
                                                  MIN(T0.NEXT_ATTENDANCE_DATE) 
                                                WHEN MAX(T0.ATTENDANCE_DATE) > TO_DATE(TO_CHAR(MAX(T0.ATTENDANCE_DATE), 'YYYY-MM-DD ')||MIN(T0.S012), 'YYYY-MM-DD HH24:MI') THEN
                                                  MAX(T0.ATTENDANCE_DATE)
                                                ELSE 
                                                  NULL 
                                                END AS NEXT_ATTENDANCE_DATE
                                            FROM (
                                              SELECT DISTINCT
                                                T0.EMPLOYEE_ID,
                                                T0.ATTENDANCE_DATE,
                                                LEAD(T0.ATTENDANCE_DATE) OVER(PARTITION BY T0.EMPLOYEE_ID ORDER BY T0.EMPLOYEE_ID, T0.ATTENDANCE_DATE) AS NEXT_ATTENDANCE_DATE,
                                                T0.S012
                                              FROM TMS_ATTENDANCE T0
                                              LEFT JOIN (
                                                  SELECT HDR.EMPLOYEE_ID, T2.PLAN_DATE, '1' AS CHECK_PLAN 
                                                  FROM TMS_EMPLOYEE_TIME_PLAN_HDR HDR
                                                  INNER JOIN TMS_EMPLOYEE_TIME_PLAN_DTL T2 ON HDR.ID = T2.TIME_PLAN_ID
                                              ) T12 ON T0.EMPLOYEE_ID = T12.EMPLOYEE_ID AND TO_CHAR(T0.ATTENDANCE_DATE, 'YYYY-MM-DD') = TO_CHAR(T12.PLAN_DATE, 'YYYY-MM-DD')
                                              WHERE (T0.S001 IS NULL OR T0.S012 IS NOT NULL) AND (CHECK_PLAN IS NULL OR T0.EMPLOYEE_ID IN ($joinNotPlan)) AND TO_CHAR(T0.ATTENDANCE_DATE, 'YYYYMMDD') BETWEEN '$startDate' AND '$endDate'" . ($singleEmployee ? " AND T0.EMPLOYEE_ID = " . $join : "") . "
                                              ORDER BY T0.EMPLOYEE_ID, T0.ATTENDANCE_DATE
                                            ) T0
                                            GROUP BY T0.EMPLOYEE_ID, TO_CHAR(T0.ATTENDANCE_DATE, 'YYYYMMDD')
                                            ORDER BY T0.EMPLOYEE_ID
                                        ) SRC ON (TRG.EMPLOYEE_ID = SRC.EMPLOYEE_ID AND TRG.ATTENDANCE_DATE = SRC.ATTENDANCE_DATE)
                                    WHEN MATCHED THEN UPDATE
                                    SET TRG.S011 = CASE WHEN SRC.NEXT_ATTENDANCE_DATE IS NOT NULL THEN 2 ELSE 1 END, 
                                        TRG.S013 = CASE WHEN SRC.NEXT_ATTENDANCE_DATE IS NOT NULL THEN TO_CHAR(SRC.NEXT_ATTENDANCE_DATE, 'HH24:MI') ELSE TO_CHAR(TRG.ATTENDANCE_TIME, 'HH24:MI') END");

                $this->db->Execute("MERGE
                                        INTO TMS_ATTENDANCE TRG  
                                        USING ( 
                                            SELECT DISTINCT
                                              T0.EMPLOYEE_ID,
                                              MIN(T0.ATTENDANCE_DATE) AS ATTENDANCE_DATE,
                                              MIN(T0.S011) AS S011
                                            FROM (
                                                SELECT DISTINCT
                                                  T0.EMPLOYEE_ID,
                                                  T0.ATTENDANCE_DATE,
                                                  LAG(T0.S011) OVER(PARTITION BY T0.EMPLOYEE_ID ORDER BY T0.EMPLOYEE_ID, T0.ATTENDANCE_DATE) AS S011
                                                FROM TMS_ATTENDANCE T0
                                                LEFT JOIN (
                                                    SELECT HDR.EMPLOYEE_ID, T2.PLAN_DATE, TO_CHAR(T2.PLAN_DATE + T3.PLAN_DURATION, 'YYYY-MM-DD') AS AS_PLAN_DATE, '1' AS CHECK_PLAN 
                                                    FROM TMS_EMPLOYEE_TIME_PLAN_HDR HDR
                                                    INNER JOIN TMS_EMPLOYEE_TIME_PLAN_DTL T2 ON HDR.ID = T2.TIME_PLAN_ID
                                                    INNER JOIN TMS_TIME_PLAN T3 ON T3.PLAN_ID = T2.PLAN_ID
                                                ) T12 ON T0.EMPLOYEE_ID = T12.EMPLOYEE_ID AND (TO_CHAR(T0.ATTENDANCE_DATE, 'YYYY-MM-DD') = TO_CHAR(T12.PLAN_DATE, 'YYYY-MM-DD') OR TO_CHAR(T0.ATTENDANCE_DATE, 'YYYY-MM-DD') = T12.AS_PLAN_DATE)
                                                WHERE (CHECK_PLAN IS NULL OR T0.EMPLOYEE_ID IN ($joinNotPlan)) AND TO_CHAR(T0.ATTENDANCE_DATE, 'YYYYMMDD') BETWEEN '$startDate' AND '$endDate'" . ($singleEmployee ? " AND T0.EMPLOYEE_ID = " . $join : "") . "
                                              ORDER BY T0.EMPLOYEE_ID, T0.ATTENDANCE_DATE
                                            ) T0
                                            GROUP BY T0.EMPLOYEE_ID, TO_CHAR(T0.ATTENDANCE_DATE, 'YYYYMMDD')
                                            ORDER BY T0.EMPLOYEE_ID
                                        ) SRC ON (TRG.EMPLOYEE_ID = SRC.EMPLOYEE_ID AND TRG.ATTENDANCE_DATE = SRC.ATTENDANCE_DATE AND (SRC.S011 <> 2 OR SRC.S011 IS NOT NULL)) 
                                    WHEN MATCHED THEN UPDATE
                                    SET trg.S003 = 1, trg.S004 = 1, trg.S005 = TO_CHAR(TRG.ATTENDANCE_TIME, 'HH24:MI')");

                $selectResultNotPlan = "SELECT DISTINCT
                                    EMPLOYEE_ID,
                                    DEPARTMENT_ID,
                                    TO_DATE(ATTENDANCE_DATE, 'YYYY-MM-DD') AS ATTENDANCE_DATE,
                                    INTIME,
                                    OUTTIME,
                                    CASE WHEN UNCLEARTIME > 0 THEN UNCLEARTIME ELSE 0 END AS UNCLEARTIME,
                                    CASE WHEN CLEARTIME > 0 AND $customerConfig != '1' THEN CLEARTIME ELSE 0 END AS CLEARTIME,
                                    DEFFERENCE_TIME,
                                    LATE_TIME,
                                    EARLY_TIME,
                                    0 AS NIGHT_TIME,
                                    ISMANUAL
                                FROM (
                                    SELECT 
                                        T0.EMPLOYEE_ID,
                                        EMP.DEPARTMENT_ID,
                                        TO_CHAR(T0.ATTENDANCE_DATE, 'YYYY-MM-DD') AS ATTENDANCE_DATE,
                                        T1.S005 AS IN_TIME,
                                        TO_DATE(TO_CHAR(T0.ATTENDANCE_DATE, 'YYYY-MM-DD')||' '||T1.S005, 'YYYY-MM-DD HH24:MI') AS INTIME,
                                        CASE WHEN T2.S013 IS NOT NULL AND T1.S005 <> T2.S013 THEN
                                            TO_DATE(TO_CHAR(T0.ATTENDANCE_DATE, 'YYYY-MM-DD') ||' '||T2.S013, 'YYYY-MM-DD HH24:MI') 
                                        ELSE 
                                            NULL END AS OUTTIME,
                                        T2.S013 AS OUT_TIME,
                                       0 AS UNCLEARTIME,
                                        (ROUND(DATEDIFF(
                                            TO_DATE(T1.ATTENDANCE_DATE||T1.S005, 'YYYY-MM-DD HH24:MI'),                                                    
                                            TO_DATE(T2.ATTENDANCE_DATE||T2.S013, 'YYYY-MM-DD HH24:MI')
                                        )) / 60)
                                        AS CLEARTIME,
                                        0 AS ISCHECK,
                                        0 AS DEFFERENCE_TIME,
                                        1 AS ISMANUAL,
                                        0 AS LATE_TIME,
                                        0 AS EARLY_TIME,
                                        CASE WHEN T11.EMPLOYEE_ID IS NULL THEN 0 ELSE 1 END AS CHECK_STATUS
                                    FROM TMS_ATTENDANCE T0
                                    INNER JOIN (
                                        SELECT 
                                            EMPLOYEE_ID, 
                                            TO_CHAR(ATTENDANCE_DATE, 'YYYY-MM-DD') AS ATTENDANCE_DATE,
                                            S001,
                                            S002,
                                            S005,
                                            S006 
                                        FROM TMS_ATTENDANCE WHERE S005 IS NOT NULL 
                                    ) T1 ON T0.EMPLOYEE_ID = T1.EMPLOYEE_ID AND TO_CHAR(T0.ATTENDANCE_DATE, 'YYYY-MM-DD') = T1.ATTENDANCE_DATE
                                    LEFT JOIN (
                                        SELECT 
                                            EMPLOYEE_ID, 
                                            TO_CHAR(ATTENDANCE_DATE, 'YYYY-MM-DD') AS ATTENDANCE_DATE,
                                            MAX(S013) AS S013
                                        FROM TMS_ATTENDANCE WHERE S013 IS NOT NULL 
                                        GROUP BY EMPLOYEE_ID, TO_CHAR(ATTENDANCE_DATE, 'YYYY-MM-DD')
                                    ) T2 ON T0.EMPLOYEE_ID = T2.EMPLOYEE_ID AND T2.ATTENDANCE_DATE = TO_CHAR(T0.ATTENDANCE_DATE, 'YYYY-MM-DD')
                                    INNER JOIN HRM_EMPLOYEE_KEY EMP ON T0.EMPLOYEE_ID = EMP.EMPLOYEE_ID AND EMP.IS_ACTIVE = 1
                                    LEFT JOIN (
                                        SELECT EMPLOYEE_ID, BALANCE_DATE
                                        FROM TNA_TIME_BALANCE_HDR 
                                    ) T11 ON T0.EMPLOYEE_ID = T11.EMPLOYEE_ID AND TO_CHAR(T0.ATTENDANCE_DATE, 'YYYY-MM-DD') = TO_CHAR(T11.BALANCE_DATE, 'YYYY-MM-DD')
                                    LEFT JOIN (
                                        SELECT HDR.EMPLOYEE_ID, T2.PLAN_DATE, TO_CHAR(T2.PLAN_DATE + T3.PLAN_DURATION, 'YYYY-MM-DD') AS AS_PLAN_DATE
                                        FROM TMS_EMPLOYEE_TIME_PLAN_HDR HDR
                                        INNER JOIN TMS_EMPLOYEE_TIME_PLAN_DTL T2 ON HDR.ID = T2.TIME_PLAN_ID
                                        INNER JOIN TMS_TIME_PLAN T3 ON T3.PLAN_ID = T2.PLAN_ID
                                    ) T12 ON T0.EMPLOYEE_ID = T12.EMPLOYEE_ID AND TO_CHAR(T0.ATTENDANCE_DATE, 'YYYY-MM-DD') = TO_CHAR(T12.PLAN_DATE, 'YYYY-MM-DD')
                                    WHERE TO_CHAR(T0.ATTENDANCE_DATE, 'YYYYMMDD') BETWEEN '$startDate' AND '$endDate' AND (T12.EMPLOYEE_ID IS NULL OR T12.EMPLOYEE_ID IN ($joinNotPlan))" . ($singleEmployee ? " AND T0.EMPLOYEE_ID = " . $join : "") . "
                                )
                                WHERE CHECK_STATUS = 0
                                GROUP BY 
                                    EMPLOYEE_ID,
                                    DEPARTMENT_ID,
                                    ATTENDANCE_DATE,
                                    INTIME,
                                    OUTTIME,
                                    OUT_TIME,
                                    IN_TIME,
                                    UNCLEARTIME,
                                    CLEARTIME,
                                    DEFFERENCE_TIME,
                                    LATE_TIME,
                                    EARLY_TIME,
                                    ISCHECK,
                                    ISMANUAL";

                        //echo $selectResultNotPlan; die;

                $dataNotPlan = $this->db->GetAll($selectResultNotPlan);                   

                if ($dataNotPlan) {
                    $cause44 = 0;
                    if(Config::getFromCache('CONFIG_TNA_HISHIGARVIN'))
                        $cause44 = "CASE WHEN INSTR('^7^1490331492612^1490698800425^1490698800458^1490698800470^1490698800487^1490698800561^1500187471892^8^', '^'||DEPARTMENT_ID||'^') = 0 THEN 0 ELSE 210 END";

                    $this->db->Execute("INSERT INTO TNA_TIME_BALANCE_HDR (
                        TIME_BALANCE_HDR_ID, 
                        EMPLOYEE_ID,  BALANCE_DATE, START_TIME, END_TIME, UNCLEAN_TIME, CLEAN_TIME,  DEFFERENCE_TIME, 
                        LATE_TIME, EARLY_TIME, 
                        ". $LATE_TYPE .", 
                        ". $EARLY_TYPE .",
                        NIGHT_TIME, IS_UPLOAD, CREATED_DATE, CREATED_USER_ID, IS_CONFIRMED, CAUSE4)
                            SELECT 
                                IMPORT_ID_SEQ.NEXTVAL,
                                EMPLOYEE_ID,
                                ATTENDANCE_DATE,
                                INTIME,
                                OUTTIME,
                                UNCLEARTIME,
                                CASE WHEN 
                                    INSTR('$notPlanDepartment', '^'||DEPARTMENT_ID||'^') = 0 THEN CLEARTIME 
                                ELSE
                                    CASE WHEN CLEARTIME < $planTimeDefault THEN CLEARTIME ELSE $planTimeDefault END
                                END AS CLEARTIME,
                                DEFFERENCE_TIME,
                                LATE_TIME,
                                EARLY_TIME ,
                                LATE_TIME,
                                EARLY_TIME,
                                NIGHT_TIME,
                                ISMANUAL,
                                SYSDATE,
                                1, 
                                0 AS IS_CONFIRMED,
                                $cause44 AS CAUSE4
                                FROM ($selectResultNotPlan)");

                }                
            }
            // </editor-fold>

            $response = array('status' => 'warning', 'message' => 'Амжилттай : Давхардсан өгөгдөлийг дахин оруулах боломжгүйг анхаарна уу?', );

        } catch (Exception $ex) {
            //echo $selectResult; die;  
            var_dump($ex); die;
            //$response = array('status' => 'warning', 'message' => 'Алдаа гарсан', 'exceptionmsg' => $ex->msg, 'exception' => $ex);
            $response = array('status' => 'warning', 'message' => 'Амжилттай:: Давхардсан өгөгдөлийг дахин оруулах боломжгүйг анхаарна уу?', );
        }

        return $response;
    }        

    public function subBalanceListMainDataGridV3Model() {
        $page = Input::postCheck('page') ? Input::post('page') : 1;
        $rows = Input::postCheck('rows') ? Input::post('rows') : 10;
        $offset = ($page - 1) * $rows;
        $statusText = '';
        $result = $footerArr = array();
        $where = "WHERE 1 = 1 ";
        if (Input::postCheck('params')) {
            parse_str(Input::post('params'), $params);
            $balanceDate = Input::post('balanceDate');
            $VW_EMPLOYEE = isset($params['viewEmployee']) ? 'VW_EMPLOYEE' : 'VW_EMPLOYEE';

            $balanceArr = explode('-', $balanceDate);
            str_replace("/", "-", $balanceArr[0]);

            $startDate = str_replace("/", "-", $balanceArr[0]);
            $endDate = str_replace("/", "-", $balanceArr[1]);

            if (!is_null($balanceDate)) {
                $balanceDateImplode = explode('-', $endDate);
                $startDate = $startDate . '-01';
                $endDate = $endDate . '-' . cal_days_in_month(CAL_GREGORIAN, $balanceDateImplode[1], $balanceDateImplode[0]);

                $startDate = ($startDate < $params['startDate']) ? $params['startDate'] : $startDate;
                $endDate = ($endDate > $params['endDate']) ? $params['endDate'] : $endDate;
            } else {
                $startDate = $params['startDate'];
                $endDate = $params['endDate'];
            }

            if (!empty($startDate) && !empty($endDate)) {
                $filterString = "";

                if (!empty($params['filterString'])) {
                    $filterString = " AND (LOWER(bl.FIRST_NAME) LIKE LOWER('%" . $params['filterString'] . "%') OR LOWER(bl.LAST_NAME) LIKE LOWER('%" . $params['filterString'] . "%') OR LOWER(EE.REGISTER_NUMBER) LIKE LOWER('%" . $params['filterString'] . "%') OR LOWER(EE.CODE) LIKE LOWER('%" . $params['filterString'] . "%'))";
                }

                if (!empty($params['departmentId'])) {
                    $departmentIds = $params['departmentId'];

                    if(isset($params['isChild']))
                        $departmentIds = $this->getAllChildDepartmentModel($departmentIds);                        

                    $where = " WHERE EE.DEPARTMENT_ID IN ( " . $departmentIds . ") ";
                }

                $filterRuleString = ' WHERE 1 = 1 ';

                if (Input::postCheck('filterRules')) {
                    $filterRules = json_decode(Str::cp1251_utf8($_POST['filterRules']), true);

                    if (count($filterRules) > 0) {

                        foreach ($filterRules as $rule) {
                            $ruleValue = Input::param(Str::lower(trim($rule['value'])));
                            switch ($rule['field']) {
                                case 'CLEAR_TIME':
                                case 'DEFFERENCE_TIME':
                                    $ruleValue = explode(':', $ruleValue);
                                    $ruleValue = (float) $ruleValue[0] + (float) $ruleValue[1] / 60;
                                    $filterRuleString .= ' AND ' . $rule['field'] . " = '" . $ruleValue . "'";
                                    break;
                                case 'OUT_TIME':
                                case 'IN_TIME':
                                    $filterRuleString .= ' AND ' . $rule['field'] . " = '" . $ruleValue . "'";
                                    break;
                                case 'STATUS_TEXT':
                                    $statusText = $ruleValue;
                                    break;
                                default:
                                    $filterRuleString .= ' AND LOWER(' . $rule['field'] . ") LIKE LOWER('%" . $ruleValue . "%')";
                                    break;
                            }
                        }
                    }
                }

                $sortField = 'FIRST_NAME , BALANCE_DATE';
                $sortOrder = 'ASC';

                if (Input::postCheck('sort') && Input::postCheck('order')) {
                    $sortField = Input::post('sort');
                    $sortOrder = Input::post('order');
                }

                $employeeId = Input::post('employeeId');

                $whereSql = $where . $filterString . " AND EE.EMPLOYEE_ID = " . $employeeId;

        $selectCount = "
            SELECT COUNT(TIME_BALANCE_HDR_ID) AS ROW_COUNT
            FROM ((
                    SELECT 
                        DISTINCT 
                        TO_CHAR(AA.BALANCE_DATE, 'YYYY-MM-DD') ||' (' ||SUBSTR(TO_CHAR(TO_CHAR(AA.BALANCE_DATE, 'Day')), 1, 3) ||')' BALANCE_DATE1,
                        ROUND(FNC_GET_TMS_PLAN_TIME(lj.PLAN_ID)/60, 2) || ' (' || lj.START_TIME ||'-'|| lj.END_TIME  ||')' AS PLAN_TIME, 
                        CASE
                            WHEN AA.TIME_BALANCE_HDR_ID IS NULL
                            THEN " . getUID() . "
                            ELSE AA.TIME_BALANCE_HDR_ID
                        END AS TIME_BALANCE_HDR_ID,    
                        EE.EMPLOYEE_ID,
                        ee.CODE             AS EMPLOYEE_CODE,
                        ee.EMPLOYEE_KEY_ID  AS EMPLOYEE_KEY_ID,
                        ee.EMPLOYEE_PICTURE AS PICTURE,
                        ee.POSITION_NAME,
                        SUBSTR(EE.LAST_NAME ,1,1)||'.' ||EE.FIRST_NAME ||' (' ||ee.CODE ||')' AS EMPLOYEE_NAME,
                        ee.STATUS_NAME,
                        ee.DEPARTMENT_ID,
                        EE.FIRST_NAME,
                        EE.LAST_NAME,    
                        TO_CHAR(AA.BALANCE_DATE, 'YYYY-MM-DD') BALANCE_DATE,    
                        TO_CHAR(AA.BALANCE_DATE, 'YYYY-MM-DD') CH_BALANCE_DATE,    
                        CASE WHEN AA.START_TIME IS NULL THEN '-' ELSE  TO_CHAR(AA.START_TIME, 'HH24:MI') END AS IN_TIME,
                        CASE WHEN AA.END_TIME IS NULL THEN '-' ELSE  TO_CHAR(AA.END_TIME, 'HH24:MI') END AS OUT_TIME,
                        NVL(TO_CHAR(AA.START_TIME, 'YYYY-MM-DD HH24:MI:SS'),'-') IN_DATETIME,
                        NVL(TO_CHAR(AA.END_TIME, 'YYYY-MM-DD HH24:MI:SS'),'-') OUT_DATETIME,
                        AA.CLEAN_TIME AS CLEAR_TIME,
                        AA.DEFFERENCE_TIME,
                        AA.ORIGINAL_DEFFERENCE_TIME,
                        AA.UNCLEAN_TIME AS UNCLEAR_TIME,
                        AA.FAULT_TYPE,
                        AA.NIGHT_TIME,
                        AA.EARLY_TIME,
                        AA.LATE_TIME,
                        AA.IS_LOCK,
                        lj.TYPE_NAME,
                        AA.IS_CONFIRMED,
                        BB.ID AS WFM_STATUS_ID,
                        LOWER(BB.WFM_STATUS_CODE) AS WFM_STATUS_CODE,
                        BB.WFM_STATUS_NAME,
                        CASE
                            WHEN AA.IS_MANUAL IS NULL
                            THEN 0
                            ELSE AA.IS_MANUAL
                        END AS IS_MANUAL,
                        CASE
                            WHEN AA.IS_CONFIRMED = 1 THEN '#dff0d8'
                            WHEN AA.DEFFERENCE_TIME != 0 THEN '#f2dede'
                            WHEN AA.DEFFERENCE_TIME = 0 THEN '#dff0d8'            
                            WHEN AA.END_TIME IS NULL THEN '#fcf8e3'
                            WHEN AA.START_TIME IS NULL THEN '#fcf8e3'
                            END AS STATUS_COLOR,
                        CASE
                            WHEN AA.IS_CONFIRMED = 1 THEN '#dff0d8'
                            WHEN AA.DEFFERENCE_TIME != 0 THEN '#f2dede'
                            WHEN AA.DEFFERENCE_TIME = 0 THEN '#dff0d8'            
                            WHEN AA.END_TIME IS NULL THEN '#fcf8e3'
                            WHEN AA.START_TIME IS NULL THEN '#fcf8e3'
                            END AS BACKGROUND_COLOR,
                        CASE";

                //if (Config::getFromCache('CONFIG_TNA_HISHIGARVIN') === false) {
                    $selectCount .= " 
                        WHEN tem9.EMPLOYEE_KEY_ID IS NOT NULL THEN 'Ээлжийн амралт'
                        WHEN tem4.EMPLOYEE_KEY_ID IS NOT NULL THEN 'Лист магадлагаа'
                        WHEN BB.WFM_STATUS_NAME IS NOT NULL THEN BB.WFM_STATUS_NAME
                        WHEN tem99.NAME IS NOT NULL THEN tem99.NAME ";
                //}

                $selectCount .= " 
                        WHEN BB.WFM_STATUS_NAME IS NOT NULL THEN BB.WFM_STATUS_NAME
                        WHEN AA.DEFFERENCE_TIME = 0 THEN 'Зөрчилгүй'";

                //if (Config::getFromCache('CONFIG_TNA_HISHIGARVIN') === false) {
                    $selectCount .= " 
                        WHEN AA.START_TIME IS NULL THEN 'Орсон цаг дутуу'
                        WHEN AA.END_TIME IS NULL THEN 'Гарсан цаг дутуу'
                        WHEN AA.IS_LOCK = 1 THEN 'Түгжсэн' ";
                //}

                $selectCount.="
                        ELSE 'Зөрчилтэй' END AS STATUS_TEXT,
                        CASE
                            WHEN AA.IS_CONFIRMED = 1 THEN '#000'
                            WHEN AA.DEFFERENCE_TIME != 0 THEN '#F00'
                            WHEN AA.DEFFERENCE_TIME = 0 THEN '#000'
                            WHEN AA.BALANCE_DATE < SYSDATE THEN '#000'
                            ELSE '#F00'
                        END AS FONT_COLOR,
                        CASE
                            WHEN SUBSTR(TO_CHAR(TO_CHAR(AA.BALANCE_DATE, 'Day')), 1, 3) = 'Sat' THEN '#ffc37b'
                            WHEN SUBSTR(TO_CHAR(TO_CHAR(AA.BALANCE_DATE, 'Day')), 1, 3) = 'Sun' THEN '#ffc37b'
                            WHEN AA.IS_LOCK = 1 THEN '#578ebe'
                            ELSE '#FFF'
                        END AS COLOR,
                        SUBSTR(TO_CHAR(TO_CHAR(AA.BALANCE_DATE, 'Day')), 1, 3) AS SPELL_DAY,
                        CASE WHEN blog.COUNTT IS NULL
                            THEN 0
                            ELSE blog.COUNTT
                        END AS IS_LOG,
                        HE.EMPLOYEE_EMAIL,
                        AA.IS_ZERO_TIME,
                        AA.ADD_DAY
                    FROM $VW_EMPLOYEE EE
                        LEFT JOIN TNA_TIME_BALANCE_HDR AA ON EE.EMPLOYEE_ID = AA.EMPLOYEE_ID AND AA.BALANCE_DATE >= TO_DATE('" . $startDate . "', 'YYYY-MM-DD') AND AA.BALANCE_DATE <= TO_DATE('" . $endDate . "', 'YYYY-MM-DD') 
                        LEFT JOIN META_WFM_STATUS BB ON BB.ID = AA.WFM_STATUS_ID
                        LEFT JOIN (
                            SELECT 
                                T0.EMPLOYEE_ID, 
                                T0.EMPLOYEE_KEY_ID, 
                                T1.PLAN_DATE, 
                                T1.PLAN_ID 
                            FROM  TMS_EMPLOYEE_TIME_PLAN_HDR T0
                            INNER JOIN TMS_EMPLOYEE_TIME_PLAN_DTL T1 ON T0.ID = T1.TIME_PLAN_ID
                            WHERE TO_CHAR(T1.PLAN_DATE, 'YYYY-MM-DD') BETWEEN '$startDate' AND  '$endDate'
                        ) ETP ON AA.EMPLOYEE_ID = ETP.EMPLOYEE_ID  AND TO_CHAR(AA.BALANCE_DATE, 'yyyyMMdd') = TO_CHAR(ETP.PLAN_DATE, 'yyyyMMdd')
                        INNER JOIN HRM_EMPLOYEE HE ON EE.EMPLOYEE_ID = HE.EMPLOYEE_ID  
                        LEFT JOIN (
                            SELECT 
                                EMP.EMPLOYEE_ID,
                                CT.NAME,
                                DTL.START_DATE,
                                DTL.END_DATE
                            FROM (
                                SELECT 
                                    MAX(LV_HEADER_ID) AS LV_HEADER_ID,
                                    EMPLOYEE_KEY_ID
                                FROM LM_HEADER HDR
                                GROUP BY EMPLOYEE_KEY_ID
                            ) tem
                            INNER JOIN LM_HEADER HDR ON tem.LV_HEADER_ID = HDR.LV_HEADER_ID
                            INNER JOIN LM_DETAIL DTL ON DTL.LV_HEADER_ID = HDR.LV_HEADER_ID
                            INNER JOIN HRM_EMPLOYEE_KEY EMPK ON EMPK.EMPLOYEE_KEY_ID = HDR.EMPLOYEE_KEY_ID
                            INNER JOIN HRM_EMPLOYEE EMP ON EMP.EMPLOYEE_ID = EMPK.EMPLOYEE_ID
                            INNER JOIN LM_TYPE TP ON HDR.TYPE_ID = TP.TYPE_ID
                            INNER JOIN TNA_CAUSE_LM_TYPE MP ON TP.TYPE_ID = MP.LM_TYPE_ID
                            INNER JOIN TNA_CAUSE_TYPE CT ON MP.CAUSE_TYPE_ID       = CT.CAUSE_TYPE_ID
                        ) tem99 ON EE.EMPLOYEE_ID = tem99.EMPLOYEE_ID AND AA.BALANCE_DATE BETWEEN tem99.START_DATE AND tem99.END_DATE
                        LEFT JOIN (
                            SELECT 
                                TTP.PLAN_ID,
                                TTP.CODE,
                                TTP.NAME AS TYPE_NAME,
                                TTPDTL.START_TIME,
                                TTPDTL.END_TIME
                            FROM TMS_TIME_PLAN TTP
                            INNER JOIN TMS_TIME_PLAN_DEPARTMENT TTPD ON TTPD.PLAN_ID = TTP.PLAN_ID
                            INNER JOIN (
                                SELECT 
                                    PLAN_ID,
                                    MIN(START_TIME) AS START_TIME ,
                                    MAX(END_TIME)   AS END_TIME
                                FROM (
                                    SELECT 
                                        PLAN_ID,
                                        PLAN_DETAIL_ID,
                                        CASE
                                            WHEN TO_CHAR(START_TIME, 'HH24') = '00'
                                            THEN '24:' ||TO_CHAR(START_TIME, 'MI')
                                            ELSE TO_CHAR(START_TIME, 'HH24:MI')
                                        END AS START_TIME,
                                        CASE
                                            WHEN TO_CHAR(END_TIME, 'HH24') = '00'
                                            THEN '24:' ||TO_CHAR(END_TIME, 'MI')
                                            ELSE TO_CHAR(END_TIME, 'HH24:MI')
                                        END AS END_TIME                                            
                                    FROM TMS_TIME_PLAN_DETAIL
                                ) GROUP BY PLAN_ID
                            ) TTPDTL ON TTP.PLAN_ID = TTPDTL.PLAN_ID
                        ) lj ON ETP.PLAN_ID = lj.PLAN_ID
                        LEFT JOIN (
                            SELECT 
                                ma.START_DATE,
                                ma.END_DATE,
                                ma.ISSUED_BY,
                                he.EMPLOYEE_KEY_ID,
                                he.LV_HEADER_ID
                            FROM LM_MEDICAL_ACT ma
                            INNER JOIN LM_HEADER he ON ma.LM_HEADER_ID = he.LV_HEADER_ID
                        ) tem4 ON AA.BALANCE_DATE <= tem4.END_DATE AND tem4.START_DATE <= AA.BALANCE_DATE AND tem4.EMPLOYEE_KEY_ID = EE.EMPLOYEE_KEY_ID
                        LEFT JOIN ( 
                            SELECT 
                                START_DATE, END_DATE, EMPLOYEE_KEY_ID 
                            FROM LM_REST_EMPLOYEE
                        ) tem9 ON tem9.START_DATE <= AA.BALANCE_DATE AND AA.BALANCE_DATE <= tem9.END_DATE AND EE.EMPLOYEE_KEY_ID = tem9.EMPLOYEE_KEY_ID
                        LEFT JOIN (
                            SELECT 
                                COUNT(ID) AS COUNTT,
                                TIME_BALANCE_HDR_ID
                            FROM TNA_TIME_BALANCE_LOG
                            GROUP BY TIME_BALANCE_HDR_ID
                        ) blog ON AA.TIME_BALANCE_HDR_ID   = blog.TIME_BALANCE_HDR_ID
                        $whereSql AND AA.BALANCE_DATE IS NOT NULL
                    ) 
                UNION (
                    SELECT DISTINCT
                        TO_CHAR(T1.PLAN_DATE, 'YYYY-MM-DD') ||' (' ||SUBSTR(TO_CHAR(TO_CHAR(T1.PLAN_DATE, 'Day')), 1, 3) ||')' BALANCE_DATE1,
                        ROUND(FNC_GET_TMS_PLAN_TIME(T1.PLAN_ID)/60, 2) || ' (' || lj.START_TIME ||'-'|| lj.END_TIME  ||')' AS PLAN_TIME, 
                        ". getUID() ." AS TIME_BALANCE_HDR_ID,
                        EE.EMPLOYEE_ID,
                        EE.CODE             AS EMPLOYEE_CODE,
                        EE.EMPLOYEE_KEY_ID  AS EMPLOYEE_KEY_ID,
                        EE.EMPLOYEE_PICTURE AS PICTURE,
                        EE.POSITION_NAME,
                        SUBSTR(EE.LAST_NAME ,1,1)||'.' ||EE.FIRST_NAME ||' (' ||ee.CODE ||')' AS EMPLOYEE_NAME,
                        EE.STATUS_NAME,
                        EE.DEPARTMENT_ID,
                        EE.FIRST_NAME,
                        EE.LAST_NAME,   
                        TO_CHAR(T1.PLAN_DATE, 'YYYY-MM-DD') AS BALANCE_DATE, 
                        TO_CHAR(T1.PLAN_DATE, 'YYYY-MM-DD') AS CH_BALANCE_DATE, 
                        '-' AS IN_TIME,
                        '-' AS OUT_TIME,
                        '' AS IN_DATETIME,
                        '' AS OUT_DATETIME,
                        0 AS CLEAR_TIME,
                        0-FNC_GET_TMS_PLAN_TIME(T1.PLAN_ID) AS DEFFERENCE_TIME,
                        0 AS ORIGINAL_DEFFERENCE_TIME,
                        0 AS UNCLEAR_TIME,
                        0 AS FAULT_TYPE,
                        0 AS NIGHT_TIME,
                        0 AS EARLY_TIME,
                        0 AS LATE_TIME,
                        0 AS IS_LOCK,
                        lj.TYPE_NAME,
                        0 AS IS_CONFIRMED,
                        0 AS WFM_STATUS_ID,
                        '' AS WFM_STATUS_CODE,
                        '' AS WFM_STATUS_NAME,
                        0 AS IS_MANUAL,
                        '#f2dede' AS STATUS_COLOR,
                        '#f2dede' AS BACKGROUND_COLOR,
                        'Зөрчилтэй' AS STATUS_TEXT,
                        '#F00' AS FONT_COLOR,
                        '' AS COLOR,
                        '' AS SPELL_DAY,
                        0 AS IS_LOG,
                        HE.EMPLOYEE_EMAIL,
                        0 AS IS_ZERO_TIME,
                        0 AS ADD_DAY
                    FROM  $VW_EMPLOYEE EE
                    INNER JOIN HRM_EMPLOYEE HE ON EE.EMPLOYEE_ID = HE.EMPLOYEE_ID  
                    INNER JOIN TMS_EMPLOYEE_TIME_PLAN_HDR T0 ON HE.EMPLOYEE_ID = T0.EMPLOYEE_ID
                    INNER JOIN TMS_EMPLOYEE_TIME_PLAN_DTL T1 ON T0.ID = T1.TIME_PLAN_ID
                    LEFT JOIN (
                      SELECT 
                          EMPLOYEE_ID, 
                          TO_CHAR(BALANCE_DATE, 'YYYY-MM-DD') AS BALANCE_DATE,
                          '1' AS ICHECK
                      FROM TNA_TIME_BALANCE_HDR 
                      WHERE EMPLOYEE_ID = $employeeId
                    ) T2 ON TO_CHAR(T1.PLAN_DATE, 'YYYY-MM-DD') = T2.BALANCE_DATE
                    LEFT JOIN (
                          SELECT DISTINCT
                              TTP.PLAN_ID,
                              TTP.CODE,
                              TTP.NAME AS TYPE_NAME,
                              TTPDTL.START_TIME,
                              TTPDTL.END_TIME
                          FROM TMS_TIME_PLAN TTP
                          INNER JOIN TMS_TIME_PLAN_DEPARTMENT TTPD ON TTPD.PLAN_ID = TTP.PLAN_ID
                          INNER JOIN (
                              SELECT 
                                  PLAN_ID,
                                  MIN(START_TIME) AS START_TIME ,
                                  MAX(END_TIME)   AS END_TIME
                              FROM (
                                  SELECT 
                                      PLAN_ID,
                                      PLAN_DETAIL_ID,
                                      CASE
                                          WHEN TO_CHAR(START_TIME, 'HH24') = '00'
                                          THEN '24:' ||TO_CHAR(START_TIME, 'MI')
                                          ELSE TO_CHAR(START_TIME, 'HH24:MI')
                                      END AS START_TIME,
                                      TO_CHAR(END_TIME, 'HH24:MI') AS END_TIME
                                  FROM TMS_TIME_PLAN_DETAIL
                              ) GROUP BY PLAN_ID
                          ) TTPDTL ON TTP.PLAN_ID = TTPDTL.PLAN_ID
                      ) lj ON T1.PLAN_ID = lj.PLAN_ID
                    $where AND TO_CHAR(T1.PLAN_DATE, 'YYYY-MM-DD') BETWEEN '$startDate' AND  '$endDate' AND T0.EMPLOYEE_ID = $employeeId AND T2.ICHECK IS NULL
                )
                UNION (
                    SELECT
                        ATTENDANCE_DATE ||' (' ||SUBSTR(TO_CHAR(TO_CHAR(TO_DATE(ATTENDANCE_DATE, 'YYYY-MM-DD'), 'Day')), 1, 3) ||')' AS BALANCE_DATE1,
                        '' AS PLAN_TIME, 
                        ". getUID() ." AS TIME_BALANCE_HDR_ID,
                        EE.EMPLOYEE_ID,
                        EE.CODE  AS EMPLOYEE_CODE,
                        EE.EMPLOYEE_KEY_ID  AS EMPLOYEE_KEY_ID,
                        EE.EMPLOYEE_PICTURE AS PICTURE,
                        EE.POSITION_NAME,
                        SUBSTR(EE.LAST_NAME ,1,1)||'.' ||EE.FIRST_NAME ||' (' ||ee.CODE ||')' AS EMPLOYEE_NAME,
                        EE.STATUS_NAME,
                        EE.DEPARTMENT_ID,
                        EE.FIRST_NAME,
                        EE.LAST_NAME,   
                        T0.ATTENDANCE_DATE AS BALANCE_DATE, 
                        T0.ATTENDANCE_DATE AS CH_BALANCE_DATE, 
                        TO_CHAR(TO_DATE(MIN(ATTENDANCE_DATE_TIME), 'YYYY-MM-DD HH24:MI:SS'), 'HH24:MI') AS IN_TIME,
                        TO_CHAR(TO_DATE(MAX(ATTENDANCE_DATE_TIME), 'YYYY-MM-DD HH24:MI:SS'), 'HH24:MI') AS OUT_TIME,
                        '' AS IN_DATETIME,
                        '' AS OUT_DATETIME,
                        0 AS CLEAR_TIME,
                        0 AS DEFFERENCE_TIME,
                        0 AS ORIGINAL_DEFFERENCE_TIME,
                        0 AS UNCLEAR_TIME,
                        0 AS FAULT_TYPE,
                        0 AS NIGHT_TIME,
                        0 AS EARLY_TIME,
                        0 AS LATE_TIME,
                        0 AS IS_LOCK,
                        '' AS TYPE_NAME,
                        0 AS IS_CONFIRMED,
                        0 AS WFM_STATUS_ID,
                        '' AS WFM_STATUS_CODE,
                        '' AS WFM_STATUS_NAME,
                        0 AS IS_MANUAL,
                        '#f2dede' AS STATUS_COLOR,
                        '#f2dede' AS BACKGROUND_COLOR,
                        'Зөрчилтэй' AS STATUS_TEXT,
                        '#F00' AS FONT_COLOR,
                        '' AS COLOR,
                        '' AS SPELL_DAY,
                        0 AS IS_LOG,
                        HE.EMPLOYEE_EMAIL,
                        0 AS IS_ZERO_TIME,
                        0 AS ADD_DAY
                    FROM (
                        SELECT 
                            TO_CHAR(ATTENDANCE_DATE_TIME, 'YYYY-MM-DD') AS ATTENDANCE_DATE,
                            TO_CHAR(ATTENDANCE_DATE_TIME, 'YYYY-MM-DD HH24:MI:SS') AS ATTENDANCE_DATE_TIME,
                            EMPLOYEE_ID,
                            EMPLOYEE_KEY_ID        
                        FROM TNA_TIME_ATTENDANCE
                    ) T0
                    INNER JOIN $VW_EMPLOYEE EE ON T0.EMPLOYEE_KEY_ID = EE.EMPLOYEE_KEY_ID AND T0.EMPLOYEE_ID = EE.EMPLOYEE_ID
                    INNER JOIN HRM_EMPLOYEE HE ON EE.EMPLOYEE_ID = HE.EMPLOYEE_ID  
                    LEFT JOIN (
                        SELECT
                            T1.PLAN_ID, T1.PLAN_DATE, T0.EMPLOYEE_ID, T0.EMPLOYEE_KEY_ID
                        FROM
                            TMS_EMPLOYEE_TIME_PLAN_HDR T0
                        INNER JOIN TMS_EMPLOYEE_TIME_PLAN_DTL T1 ON T0.ID = T1.TIME_PLAN_ID
                    ) T5 ON T0.EMPLOYEE_ID = T5.EMPLOYEE_ID AND T0.ATTENDANCE_DATE = TO_CHAR(T5.PLAN_DATE, 'YYYY-MM-DD')
                    LEFT JOIN (
                      SELECT 
                          EMPLOYEE_ID, 
                          TO_CHAR(BALANCE_DATE, 'YYYY-MM-DD') AS BALANCE_DATE,
                          '1' AS ICHECK
                      FROM TNA_TIME_BALANCE_HDR 
                      WHERE EMPLOYEE_ID = $employeeId
                    ) T6 ON T0.ATTENDANCE_DATE = T6.BALANCE_DATE
                                            LEFT JOIN (            
                          SELECT 
                            T2.PLAN_DURATION, 
                            TO_CHAR(T1.PLAN_DATE, 'YYYY-MM-DD') AS MAIN_DATE, 
                            TO_CHAR(T1.PLAN_DATE + T2.PLAN_DURATION, 'YYYY-MM-DD') AS PLAN_DATE
                          FROM TMS_EMPLOYEE_TIME_PLAN_HDR T0 
                          INNER JOIN TMS_EMPLOYEE_TIME_PLAN_DTL T1 ON T0.ID = T1.TIME_PLAN_ID
                          INNER JOIN TMS_TIME_PLAN T2 ON T1.PLAN_ID = T2.PLAN_ID
                    ) T7 ON T0.ATTENDANCE_DATE = T7.PLAN_DATE AND  T0.ATTENDANCE_DATE <> T7.MAIN_DATE
                    $where AND T0.ATTENDANCE_DATE BETWEEN '$startDate' AND  '$endDate' AND T0.EMPLOYEE_ID = $employeeId AND T6.ICHECK IS NULL AND T5.PLAN_ID IS NULL
                    GROUP BY
                        EE.EMPLOYEE_ID,
                        EE.CODE ,
                        EE.EMPLOYEE_KEY_ID,
                        EE.EMPLOYEE_PICTURE,
                        EE.POSITION_NAME,
                        EE.STATUS_NAME,
                        EE.DEPARTMENT_ID,
                        EE.FIRST_NAME,
                        EE.LAST_NAME,   
                        T0.ATTENDANCE_DATE, 
                        HE.EMPLOYEE_EMAIL
                )
                UNION (
                    SELECT
                        T0.ATTENDANCE_DATE ||' (' ||SUBSTR(TO_CHAR(TO_CHAR(TO_DATE(T0.ATTENDANCE_DATE, 'YYYY-MM-DD'), 'Day')), 1, 3) ||')' AS BALANCE_DATE1,
                        '' AS PLAN_TIME, 
                        ". getUID() ." AS TIME_BALANCE_HDR_ID,
                        EE.EMPLOYEE_ID,
                        EE.CODE  AS EMPLOYEE_CODE,
                        EE.EMPLOYEE_KEY_ID  AS EMPLOYEE_KEY_ID,
                        EE.EMPLOYEE_PICTURE AS PICTURE,
                        EE.POSITION_NAME,
                        SUBSTR(EE.LAST_NAME ,1,1)||'.' ||EE.FIRST_NAME ||' (' ||ee.CODE ||')' AS EMPLOYEE_NAME,
                        EE.STATUS_NAME,
                        EE.DEPARTMENT_ID,
                        EE.FIRST_NAME,
                        EE.LAST_NAME,   
                        T0.ATTENDANCE_DATE AS BALANCE_DATE, 
                        T0.ATTENDANCE_DATE AS CH_BALANCE_DATE, 
                        '-' AS IN_TIME,
                        '-' AS OUT_TIME,
                        '-' AS IN_DATETIME,
                        '-' AS OUT_DATETIME,
                        0 AS CLEAR_TIME,
                        0 AS DEFFERENCE_TIME,
                        0 AS ORIGINAL_DEFFERENCE_TIME,
                        0 AS UNCLEAR_TIME,
                        0 AS FAULT_TYPE,
                        0 AS NIGHT_TIME,
                        0 AS EARLY_TIME,
                        0 AS LATE_TIME,
                        0 AS IS_LOCK,
                        '' AS TYPE_NAME,
                        0 AS IS_CONFIRMED,
                        0 AS WFM_STATUS_ID,
                        '' AS WFM_STATUS_CODE,
                        '' AS WFM_STATUS_NAME,
                        0 AS IS_MANUAL,
                        '#fff' AS STATUS_COLOR,
                        '#f2dede' AS BACKGROUND_COLOR,
                        'Төлөвлөөгүй' AS STATUS_TEXT,
                        '#F00' AS FONT_COLOR,
                        '' AS COLOR,
                        '' AS SPELL_DAY,
                        0 AS IS_LOG,
                        HE.EMPLOYEE_EMAIL,
                        0 AS IS_ZERO_TIME,
                        0 AS ADD_DAY
                    FROM (
                        SELECT  TO_CHAR(TO_DATE('$startDate', 'YYYY-MM-DD') - 1 + LEVEL, 'YYYY-MM-DD') AS ATTENDANCE_DATE
                        FROM DUAL
                        CONNECT BY LEVEL <= TO_DATE('$endDate', 'YYYY-MM-DD') - TO_DATE('$startDate', 'YYYY-MM-DD') + 1 
                    ) T0
                    INNER JOIN $VW_EMPLOYEE EE ON EE.EMPLOYEE_KEY_ID = EE.EMPLOYEE_KEY_ID AND EE.EMPLOYEE_ID = EE.EMPLOYEE_ID
                    INNER JOIN HRM_EMPLOYEE HE ON EE.EMPLOYEE_ID = HE.EMPLOYEE_ID  
                    LEFT JOIN (
                        SELECT 
                            EMPLOYEE_ID, 
                            TO_CHAR(BALANCE_DATE, 'YYYY-MM-DD') AS BALANCE_DATE,
                            '1' AS ICHECK
                        FROM TNA_TIME_BALANCE_HDR 
                        WHERE EMPLOYEE_ID = $employeeId
                    ) T6 ON T0.ATTENDANCE_DATE = T6.BALANCE_DATE
                    LEFT JOIN (
                        SELECT
                            T1.PLAN_ID, T1.PLAN_DATE, T0.EMPLOYEE_ID
                        FROM
                            TMS_EMPLOYEE_TIME_PLAN_HDR T0
                        INNER JOIN TMS_EMPLOYEE_TIME_PLAN_DTL T1 ON T0.ID = T1.TIME_PLAN_ID
                    ) T5 ON EE.EMPLOYEE_ID = T5.EMPLOYEE_ID AND T0.ATTENDANCE_DATE = TO_CHAR(T5.PLAN_DATE, 'YYYY-MM-DD')
                    LEFT JOIN (SELECT TO_CHAR(ATTENDANCE_DATE_TIME, 'YYYY-MM-DD')      AS ATTENDANCE_DATE,
                        TO_CHAR(ATTENDANCE_DATE_TIME, 'YYYY-MM-DD HH24:MI:SS') AS ATTENDANCE_DATE_TIME,
                        EMPLOYEE_ID,
                        EMPLOYEE_KEY_ID
                      FROM TNA_TIME_ATTENDANCE
                      ) T10 ON T0.ATTENDANCE_DATE = T10.ATTENDANCE_DATE AND EE.EMPLOYEE_KEY_ID = T10.EMPLOYEE_KEY_ID AND EE.EMPLOYEE_ID = T10.EMPLOYEE_ID                         
                    $where AND EE.EMPLOYEE_ID = $employeeId AND T6.ICHECK IS NULL AND T5.PLAN_ID IS NULL AND T10.ATTENDANCE_DATE IS NULL                            
                )
            ) $filterRuleString";

            $selectList = "
                SELECT *
                FROM ( (
                    SELECT 
                        DISTINCT 
                        TO_CHAR(AA.BALANCE_DATE, 'YYYY-MM-DD') ||' (' ||SUBSTR(TO_CHAR(TO_CHAR(AA.BALANCE_DATE, 'Day')), 1, 3) ||')' BALANCE_DATE1,
                        ROUND(FNC_GET_TMS_PLAN_TIME(lj.PLAN_ID)/60, 2) || ' (' || lj.START_TIME ||'-'|| lj.END_TIME  ||')' AS PLAN_TIME, 
                        CASE
                            WHEN AA.TIME_BALANCE_HDR_ID IS NULL
                            THEN " . getUID() . "
                            ELSE AA.TIME_BALANCE_HDR_ID
                        END AS TIME_BALANCE_HDR_ID,    
                        EE.EMPLOYEE_ID,
                        ee.CODE             AS EMPLOYEE_CODE,
                        ee.EMPLOYEE_KEY_ID  AS EMPLOYEE_KEY_ID,
                        ee.EMPLOYEE_PICTURE AS PICTURE,
                        ee.POSITION_NAME,
                        SUBSTR(EE.LAST_NAME ,1,1)||'.' ||EE.FIRST_NAME ||' (' ||ee.CODE ||')' AS EMPLOYEE_NAME,
                        ee.STATUS_NAME,
                        ee.DEPARTMENT_ID,
                        EE.FIRST_NAME,
                        EE.LAST_NAME,    
                        TO_CHAR(AA.BALANCE_DATE, 'YYYY-MM-DD') BALANCE_DATE,    
                        TO_CHAR(AA.BALANCE_DATE, 'YYYY-MM-DD') CH_BALANCE_DATE,    
                        CASE WHEN AA.START_TIME IS NULL THEN '-' ELSE  TO_CHAR(AA.START_TIME, 'HH24:MI') END AS IN_TIME,
                        CASE WHEN AA.END_TIME IS NULL THEN '-' ELSE  TO_CHAR(AA.END_TIME, 'HH24:MI') END AS OUT_TIME,
                        NVL(TO_CHAR(AA.START_TIME, 'YYYY-MM-DD HH24:MI:SS'),'-') IN_DATETIME,
                        NVL(TO_CHAR(AA.END_TIME, 'YYYY-MM-DD HH24:MI:SS'),'-') OUT_DATETIME,
                        AA.CLEAN_TIME AS CLEAR_TIME,
                        AA.DEFFERENCE_TIME,
                        AA.ORIGINAL_DEFFERENCE_TIME,
                        AA.UNCLEAN_TIME AS UNCLEAR_TIME,
                        AA.FAULT_TYPE,
                        AA.NIGHT_TIME,
                        AA.EARLY_TIME,
                        AA.LATE_TIME,
                        AA.IS_LOCK,
                        lj.TYPE_NAME,
                        AA.IS_CONFIRMED,    
                        BB.ID AS WFM_STATUS_ID,
                        LOWER(BB.WFM_STATUS_CODE) AS WFM_STATUS_CODE,
                        BB.WFM_STATUS_NAME,
                        CASE
                            WHEN AA.IS_MANUAL IS NULL
                            THEN 0
                            ELSE AA.IS_MANUAL
                        END AS IS_MANUAL,
                        CASE
                            WHEN AA.IS_CONFIRMED = 1 THEN '#dff0d8'
                            WHEN AA.START_TIME IS NULL AND AA.END_TIME IS NULL THEN '#f2dede'
                            WHEN AA.DEFFERENCE_TIME != 0 OR AA.IS_CONFIRMED = 0 THEN '#f2dede'
                            WHEN AA.DEFFERENCE_TIME = 0 AND AA.IS_CONFIRMED = 1 THEN '#dff0d8'            
                            WHEN AA.END_TIME IS NULL THEN '#f2dede'
                            WHEN AA.START_TIME IS NULL THEN '#f2dede'
                            END AS STATUS_COLOR,
                        CASE
                            WHEN AA.IS_CONFIRMED = 1 THEN '#dff0d8'
                            WHEN AA.START_TIME IS NULL AND AA.END_TIME IS NULL THEN '#f2dede'
                            WHEN AA.DEFFERENCE_TIME != 0 OR AA.IS_CONFIRMED = 0 THEN '#f2dede'
                            WHEN AA.DEFFERENCE_TIME = 0 AND AA.IS_CONFIRMED = 1 THEN '#dff0d8'          
                            WHEN AA.END_TIME IS NULL THEN '#f2dede'
                            WHEN AA.START_TIME IS NULL THEN '#f2dede'
                            END AS BACKGROUND_COLOR,
                        CASE";

                    $selectList .= " 
                        WHEN tem9.EMPLOYEE_KEY_ID IS NOT NULL THEN 'Ээлжийн амралт'
                        WHEN tem4.EMPLOYEE_KEY_ID IS NOT NULL THEN 'Лист магадлагаа'
                        WHEN BB.WFM_STATUS_NAME IS NOT NULL THEN BB.WFM_STATUS_NAME
                        WHEN tem99.NAME IS NOT NULL THEN tem99.NAME ";

                $selectList .= " 
                        WHEN BB.WFM_STATUS_NAME IS NOT NULL THEN BB.WFM_STATUS_NAME
                        WHEN AA.DEFFERENCE_TIME = 0 AND AA.IS_CONFIRMED = 1 THEN 'Зөрчилгүй'";

                    $selectList .= " 
                        WHEN AA.START_TIME IS NULL AND AA.END_TIME IS NULL THEN 'Баталсан'
                        WHEN AA.START_TIME IS NULL THEN 'Орсон цаг дутуу'
                        WHEN AA.END_TIME IS NULL THEN 'Гарсан цаг дутуу'
                        WHEN AA.IS_LOCK = 1 THEN 'Түгжсэн' ";

                $selectList.="
                        ELSE 'Зөрчилтэй' END AS STATUS_TEXT,
                        CASE
                            WHEN AA.IS_CONFIRMED = 1 THEN '#000'
                            WHEN AA.DEFFERENCE_TIME != 0 OR AA.IS_CONFIRMED = 0 THEN '#F00'
                            WHEN AA.DEFFERENCE_TIME = 0 AND AA.IS_CONFIRMED = 1 THEN '#000'
                            WHEN AA.BALANCE_DATE < SYSDATE THEN '#000'
                            WHEN AA.END_TIME IS NULL THEN '#F00'
                            WHEN AA.START_TIME IS NULL THEN '#F00'                                
                            ELSE '#F00'
                        END AS FONT_COLOR,
                        CASE
                            WHEN SUBSTR(TO_CHAR(TO_CHAR(AA.BALANCE_DATE, 'Day')), 1, 3) = 'Sat' THEN '#ffc37b'
                            WHEN SUBSTR(TO_CHAR(TO_CHAR(AA.BALANCE_DATE, 'Day')), 1, 3) = 'Sun' THEN '#ffc37b'
                            WHEN AA.IS_LOCK = 1 THEN '#578ebe'
                            WHEN AA.END_TIME IS NULL THEN '#F00'
                            WHEN AA.START_TIME IS NULL THEN '#F00'                                
                            ELSE '#FFF'
                        END AS COLOR,
                        SUBSTR(TO_CHAR(TO_CHAR(AA.BALANCE_DATE, 'Day')), 1, 3) AS SPELL_DAY,
                        CASE WHEN blog.COUNTT IS NULL
                            THEN 0
                            ELSE blog.COUNTT
                        END AS IS_LOG,
                        AA.IS_ZERO_TIME,
                        AA.ADD_DAY,
                        AA.CAUSE6,
                        AA.CAUSE20,
                        AA.CAUSE3,
                        AA.CAUSE13,
                        AA.CAUSE5,
                        AA.CAUSE4,
                        AA.CAUSE7,
                        AA.CAUSE8,
                        AA.CAUSE11,
                        ETP.PLAN_ID
                    FROM $VW_EMPLOYEE EE
                        LEFT JOIN TNA_TIME_BALANCE_HDR AA ON EE.EMPLOYEE_ID = AA.EMPLOYEE_ID AND AA.BALANCE_DATE >= TO_DATE('" . $startDate . "', 'YYYY-MM-DD') AND AA.BALANCE_DATE <= TO_DATE('" . $endDate . "', 'YYYY-MM-DD')
                        LEFT JOIN META_WFM_STATUS BB ON BB.ID = AA.WFM_STATUS_ID
                        LEFT JOIN (
                            SELECT 
                                T0.EMPLOYEE_ID, 
                                T0.EMPLOYEE_KEY_ID, 
                                T1.PLAN_DATE, 
                                T1.PLAN_ID 
                            FROM  TMS_EMPLOYEE_TIME_PLAN_HDR T0
                            INNER JOIN TMS_EMPLOYEE_TIME_PLAN_DTL T1 ON T0.ID = T1.TIME_PLAN_ID
                            WHERE TO_CHAR(T1.PLAN_DATE, 'YYYY-MM-DD') BETWEEN '$startDate' AND  '$endDate' AND T0.EMPLOYEE_ID = $employeeId
                        ) ETP ON AA.EMPLOYEE_ID = ETP.EMPLOYEE_ID  AND TO_CHAR(AA.BALANCE_DATE, 'yyyyMMdd') = TO_CHAR(ETP.PLAN_DATE, 'yyyyMMdd')
                        LEFT JOIN (
                            SELECT 
                                EMP.EMPLOYEE_ID,
                                CT.NAME,
                                DTL.START_DATE,
                                DTL.END_DATE
                            FROM (
                                SELECT 
                                    MAX(LV_HEADER_ID) AS LV_HEADER_ID,
                                    EMPLOYEE_KEY_ID
                                FROM LM_HEADER HDR
                                GROUP BY EMPLOYEE_KEY_ID
                            ) tem
                            INNER JOIN LM_HEADER HDR ON tem.LV_HEADER_ID = HDR.LV_HEADER_ID
                            INNER JOIN LM_DETAIL DTL ON DTL.LV_HEADER_ID = HDR.LV_HEADER_ID
                            INNER JOIN HRM_EMPLOYEE_KEY EMPK ON EMPK.EMPLOYEE_KEY_ID = HDR.EMPLOYEE_KEY_ID
                            INNER JOIN HRM_EMPLOYEE EMP ON EMP.EMPLOYEE_ID = EMPK.EMPLOYEE_ID
                            INNER JOIN LM_TYPE TP ON HDR.TYPE_ID = TP.TYPE_ID
                            INNER JOIN TNA_CAUSE_LM_TYPE MP ON TP.TYPE_ID = MP.LM_TYPE_ID
                            INNER JOIN TNA_CAUSE_TYPE CT ON MP.CAUSE_TYPE_ID       = CT.CAUSE_TYPE_ID
                        ) tem99 ON EE.EMPLOYEE_ID = tem99.EMPLOYEE_ID AND AA.BALANCE_DATE BETWEEN tem99.START_DATE AND tem99.END_DATE
                        LEFT JOIN (
                            SELECT 
                                TTP.PLAN_ID,
                                TTP.CODE,
                                TTP.NAME AS TYPE_NAME,
                                TTPDTL.START_TIME,
                                TTPDTL.END_TIME
                            FROM TMS_TIME_PLAN TTP
                            INNER JOIN TMS_TIME_PLAN_DEPARTMENT TTPD ON TTPD.PLAN_ID = TTP.PLAN_ID
                            INNER JOIN (
                                SELECT F.PLAN_ID, TO_CHAR(F.START_TIME, 'HH24:MI') AS START_TIME, TO_CHAR(FF.END_TIME, 'HH24:MI') AS END_TIME
                                FROM (
                                   SELECT PLAN_ID, MIN(ACC_TYPE) AS ACC_TYPE
                                   FROM TMS_TIME_PLAN_DETAIL GROUP BY PLAN_ID
                                ) X 
                                INNER JOIN TMS_TIME_PLAN_DETAIL F ON F.ACC_TYPE = X.ACC_TYPE AND F.PLAN_ID = X.PLAN_ID
                                INNER JOIN (
                                  SELECT F.PLAN_ID, F.END_TIME
                                  FROM (
                                     SELECT PLAN_ID, MAX(ACC_TYPE) AS ACC_TYPE
                                     FROM TMS_TIME_PLAN_DETAIL GROUP BY PLAN_ID
                                  ) X 
                                  INNER JOIN TMS_TIME_PLAN_DETAIL F ON F.ACC_TYPE = X.ACC_TYPE AND F.PLAN_ID = X.PLAN_ID
                                ) FF ON FF.PLAN_ID = X.PLAN_ID
                            ) TTPDTL ON TTP.PLAN_ID = TTPDTL.PLAN_ID
                        ) lj ON ETP.PLAN_ID = lj.PLAN_ID
                        LEFT JOIN (
                            SELECT 
                                ma.START_DATE,
                                ma.END_DATE,
                                ma.ISSUED_BY,
                                he.EMPLOYEE_KEY_ID,
                                he.LV_HEADER_ID
                            FROM LM_MEDICAL_ACT ma
                            INNER JOIN LM_HEADER he ON ma.LM_HEADER_ID = he.LV_HEADER_ID
                        ) tem4 ON AA.BALANCE_DATE <= tem4.END_DATE AND tem4.START_DATE <= AA.BALANCE_DATE AND tem4.EMPLOYEE_KEY_ID = EE.EMPLOYEE_KEY_ID
                        LEFT JOIN ( 
                            SELECT 
                                START_DATE, END_DATE, EMPLOYEE_KEY_ID 
                            FROM LM_REST_EMPLOYEE
                        ) tem9 ON tem9.START_DATE <= AA.BALANCE_DATE AND AA.BALANCE_DATE <= tem9.END_DATE AND EE.EMPLOYEE_KEY_ID = tem9.EMPLOYEE_KEY_ID
                        LEFT JOIN (
                            SELECT 
                                COUNT(ID) AS COUNTT,
                                TIME_BALANCE_HDR_ID
                            FROM TNA_TIME_BALANCE_LOG
                            GROUP BY TIME_BALANCE_HDR_ID
                        ) blog ON AA.TIME_BALANCE_HDR_ID   = blog.TIME_BALANCE_HDR_ID
                        $whereSql AND AA.BALANCE_DATE IS NOT NULL AND EE.EMPLOYEE_ID = $employeeId
                    ) 
                UNION (
                    SELECT DISTINCT
                        TO_CHAR(T1.PLAN_DATE, 'YYYY-MM-DD') ||' (' ||SUBSTR(TO_CHAR(TO_CHAR(T1.PLAN_DATE, 'Day')), 1, 3) ||')' BALANCE_DATE1,
                        ROUND(FNC_GET_TMS_PLAN_TIME(T1.PLAN_ID)/60, 2) || ' (' || lj.START_TIME ||'-'|| lj.END_TIME  ||')' AS PLAN_TIME, 
                        ". getUID() ." AS TIME_BALANCE_HDR_ID,
                        EE.EMPLOYEE_ID,
                        EE.CODE             AS EMPLOYEE_CODE,
                        EE.EMPLOYEE_KEY_ID  AS EMPLOYEE_KEY_ID,
                        EE.EMPLOYEE_PICTURE AS PICTURE,
                        EE.POSITION_NAME,
                        SUBSTR(EE.LAST_NAME ,1,1)||'.' ||EE.FIRST_NAME ||' (' ||ee.CODE ||')' AS EMPLOYEE_NAME,
                        EE.STATUS_NAME,
                        EE.DEPARTMENT_ID,
                        EE.FIRST_NAME,
                        EE.LAST_NAME,   
                        TO_CHAR(T1.PLAN_DATE, 'YYYY-MM-DD') AS BALANCE_DATE, 
                        TO_CHAR(T1.PLAN_DATE, 'YYYY-MM-DD') AS CH_BALANCE_DATE, 
                        '-' AS IN_TIME,
                        '-' AS OUT_TIME,
                        '' AS IN_DATETIME,
                        '' AS OUT_DATETIME,
                        0 AS CLEAR_TIME,
                        0-FNC_GET_TMS_PLAN_TIME(T1.PLAN_ID) AS DEFFERENCE_TIME,
                        0 AS ORIGINAL_DEFFERENCE_TIME,
                        0 AS UNCLEAR_TIME,
                        0 AS FAULT_TYPE,
                        0 AS NIGHT_TIME,
                        0 AS EARLY_TIME,
                        0 AS LATE_TIME,
                        0 AS IS_LOCK,
                        lj.TYPE_NAME,
                        0 AS IS_CONFIRMED,
                        0 AS WFM_STATUS_ID,
                        '' AS WFM_STATUS_CODE,
                        '' AS WFM_STATUS_NAME,
                        0 AS IS_MANUAL,
                        '#f2dede' AS STATUS_COLOR,
                        '#f2dede' AS BACKGROUND_COLOR,
                        'Зөрчилтэй' AS STATUS_TEXT,
                        '#F00' AS FONT_COLOR,
                        '' AS COLOR,
                        '' AS SPELL_DAY,
                        0 AS IS_LOG,
                        0 AS IS_ZERO_TIME,
                        0 AS ADD_DAY,
                        0 AS CAUSE6,
                        0 AS CAUSE20,
                        0 AS CAUSE3,
                        CASE WHEN TRUNC(T1.PLAN_DATE) < TRUNC(SYSDATE) THEN 0-FNC_GET_TMS_PLAN_TIME(T1.PLAN_ID) ELSE 0 END AS CAUSE13,
                        0 AS CAUSE5,                            
                        0 AS CAUSE4,                       
                        0 AS CAUSE7,                       
                        0 AS CAUSE8,                       
                        0 AS CAUSE11,                       
                        T1.PLAN_ID
                    FROM  $VW_EMPLOYEE EE
                    INNER JOIN TMS_EMPLOYEE_TIME_PLAN_HDR T0 ON EE.EMPLOYEE_ID = T0.EMPLOYEE_ID
                    INNER JOIN TMS_EMPLOYEE_TIME_PLAN_DTL T1 ON T0.ID = T1.TIME_PLAN_ID
                    LEFT JOIN (
                      SELECT 
                          EMPLOYEE_ID, 
                          TO_CHAR(BALANCE_DATE, 'YYYY-MM-DD') AS BALANCE_DATE,
                          '1' AS ICHECK
                      FROM TNA_TIME_BALANCE_HDR 
                      WHERE EMPLOYEE_ID = $employeeId AND TRUNC(BALANCE_DATE) BETWEEN TO_DATE('$startDate') AND TO_DATE('$endDate')
                    ) T2 ON TO_CHAR(T1.PLAN_DATE, 'YYYY-MM-DD') = T2.BALANCE_DATE
                    LEFT JOIN (
                          SELECT DISTINCT
                              TTP.PLAN_ID,
                              TTP.CODE,
                              TTP.NAME AS TYPE_NAME,
                              TTPDTL.START_TIME,
                              TTPDTL.END_TIME
                          FROM TMS_TIME_PLAN TTP
                          INNER JOIN TMS_TIME_PLAN_DEPARTMENT TTPD ON TTPD.PLAN_ID = TTP.PLAN_ID
                          INNER JOIN (
                            SELECT F.PLAN_ID, TO_CHAR(F.START_TIME, 'HH24:MI') AS START_TIME, TO_CHAR(FF.END_TIME, 'HH24:MI') AS END_TIME
                            FROM (
                               SELECT PLAN_ID, MIN(ACC_TYPE) AS ACC_TYPE
                               FROM TMS_TIME_PLAN_DETAIL GROUP BY PLAN_ID
                            ) X 
                            INNER JOIN TMS_TIME_PLAN_DETAIL F ON F.ACC_TYPE = X.ACC_TYPE AND F.PLAN_ID = X.PLAN_ID
                            INNER JOIN (
                              SELECT F.PLAN_ID, F.END_TIME
                              FROM (
                                 SELECT PLAN_ID, MAX(ACC_TYPE) AS ACC_TYPE
                                 FROM TMS_TIME_PLAN_DETAIL GROUP BY PLAN_ID
                              ) X 
                              INNER JOIN TMS_TIME_PLAN_DETAIL F ON F.ACC_TYPE = X.ACC_TYPE AND F.PLAN_ID = X.PLAN_ID
                            ) FF ON FF.PLAN_ID = X.PLAN_ID
                          ) TTPDTL ON TTP.PLAN_ID = TTPDTL.PLAN_ID
                      ) lj ON T1.PLAN_ID = lj.PLAN_ID
                    $where AND TO_CHAR(T1.PLAN_DATE, 'YYYY-MM-DD') BETWEEN '$startDate' AND  '$endDate' AND T0.EMPLOYEE_ID = $employeeId AND T2.ICHECK IS NULL
                )
                UNION (
                    SELECT
                        ATTENDANCE_DATE ||' (' ||SUBSTR(TO_CHAR(TO_CHAR(TO_DATE(ATTENDANCE_DATE, 'YYYY-MM-DD'), 'Day')), 1, 3) ||')' AS BALANCE_DATE1,
                        '' AS PLAN_TIME, 
                        ". getUID() ." AS TIME_BALANCE_HDR_ID,
                        EE.EMPLOYEE_ID,
                        EE.CODE  AS EMPLOYEE_CODE,
                        EE.EMPLOYEE_KEY_ID  AS EMPLOYEE_KEY_ID,
                        EE.EMPLOYEE_PICTURE AS PICTURE,
                        EE.POSITION_NAME,
                        SUBSTR(EE.LAST_NAME ,1,1)||'.' ||EE.FIRST_NAME ||' (' ||ee.CODE ||')' AS EMPLOYEE_NAME,
                        EE.STATUS_NAME,
                        EE.DEPARTMENT_ID,
                        EE.FIRST_NAME,
                        EE.LAST_NAME,   
                        T0.ATTENDANCE_DATE AS BALANCE_DATE, 
                        T0.ATTENDANCE_DATE AS CH_BALANCE_DATE, 
                        TO_CHAR(TO_DATE(MIN(ATTENDANCE_DATE_TIME), 'YYYY-MM-DD HH24:MI:SS'), 'HH24:MI') AS IN_TIME,
                        TO_CHAR(TO_DATE(MAX(ATTENDANCE_DATE_TIME), 'YYYY-MM-DD HH24:MI:SS'), 'HH24:MI') AS OUT_TIME,
                        '' AS IN_DATETIME,
                        '' AS OUT_DATETIME,
                        0 AS CLEAR_TIME,
                        0 AS DEFFERENCE_TIME,
                        0 AS ORIGINAL_DEFFERENCE_TIME,
                        0 AS UNCLEAR_TIME,
                        0 AS FAULT_TYPE,
                        0 AS NIGHT_TIME,
                        0 AS EARLY_TIME,
                        0 AS LATE_TIME,
                        0 AS IS_LOCK,
                        '' AS TYPE_NAME,
                        0 AS IS_CONFIRMED,
                        0 AS WFM_STATUS_ID,
                        '' AS WFM_STATUS_CODE,
                        '' AS WFM_STATUS_NAME,
                        0 AS IS_MANUAL,
                        '#f2dede' AS STATUS_COLOR,
                        '#f2dede' AS BACKGROUND_COLOR,
                        'Зөрчилтэй' AS STATUS_TEXT,
                        '#F00' AS FONT_COLOR,
                        '' AS COLOR,
                        '' AS SPELL_DAY,
                        0 AS IS_LOG,
                        0 AS IS_ZERO_TIME,
                        0 AS ADD_DAY,
                        0 AS CAUSE6,
                        0 AS CAUSE20,
                        0 AS CAUSE3,
                        0 AS CAUSE13,
                        0 AS CAUSE5,                            
                        0 AS CAUSE4,                            
                        0 AS CAUSE7,                            
                        0 AS CAUSE8,                            
                        0 AS CAUSE11,                            
                        T5.PLAN_ID
                    FROM (
                        SELECT 
                            ID,
                            TO_CHAR(ATTENDANCE_DATE_TIME, 'YYYY-MM-DD') AS ATTENDANCE_DATE,
                            ATTENDANCE_DATE_TIME,
                            EMPLOYEE_ID,
                            EMPLOYEE_KEY_ID        
                        FROM TNA_TIME_ATTENDANCE WHERE IS_REMOVED_NOT_PLAN IS NULL OR IS_REMOVED_NOT_PLAN != 1
                            AND EMPLOYEE_ID = $employeeId
                            AND TRUNC(ATTENDANCE_DATE_TIME) BETWEEN TO_DATE('$startDate') AND TO_DATE('$endDate')
                    ) T0
                    INNER JOIN $VW_EMPLOYEE EE ON T0.EMPLOYEE_ID = EE.EMPLOYEE_ID
                    LEFT JOIN (
                        SELECT
                            T1.PLAN_ID, T1.PLAN_DATE, T0.EMPLOYEE_ID, T0.EMPLOYEE_KEY_ID
                        FROM
                            TMS_EMPLOYEE_TIME_PLAN_HDR T0
                        INNER JOIN TMS_EMPLOYEE_TIME_PLAN_DTL T1 ON T0.ID = T1.TIME_PLAN_ID
                        WHERE TRUNC(T1.PLAN_DATE) BETWEEN TO_DATE('$startDate') AND TO_DATE('$endDate')
                    ) T5 ON T0.EMPLOYEE_ID = T5.EMPLOYEE_ID AND T0.ATTENDANCE_DATE = TO_CHAR(T5.PLAN_DATE, 'YYYY-MM-DD')
                    LEFT JOIN (
                      SELECT 
                          EMPLOYEE_ID, 
                          TO_CHAR(BALANCE_DATE, 'YYYY-MM-DD') AS BALANCE_DATE,
                          '1' AS ICHECK
                      FROM TNA_TIME_BALANCE_HDR 
                      WHERE EMPLOYEE_ID = $employeeId AND TRUNC(BALANCE_DATE) BETWEEN TO_DATE('$startDate') AND TO_DATE('$endDate')
                    ) T6 ON T0.ATTENDANCE_DATE = T6.BALANCE_DATE
                    LEFT JOIN (
                          SELECT 
                            T2.PLAN_DURATION, 
                            '1' AS DARK_CHECK_TIME,
                            TO_CHAR(T1.PLAN_DATE, 'YYYY-MM-DD') AS MAIN_DATE, 
                            TO_CHAR(T1.PLAN_DATE + T2.PLAN_DURATION, 'YYYY-MM-DD') AS PLAN_DATE
                          FROM TMS_EMPLOYEE_TIME_PLAN_HDR T0 
                          INNER JOIN TMS_EMPLOYEE_TIME_PLAN_DTL T1 ON T0.ID = T1.TIME_PLAN_ID
                          INNER JOIN TMS_TIME_PLAN T2 ON T1.PLAN_ID = T2.PLAN_ID
                          WHERE T0.EMPLOYEE_ID = $employeeId AND TRUNC(T1.PLAN_DATE) BETWEEN TO_DATE('$startDate') AND TO_DATE('$endDate')
                    ) T7 ON T0.ATTENDANCE_DATE = T7.PLAN_DATE AND  T0.ATTENDANCE_DATE <> T7.MAIN_DATE
                    $where AND TRUNC(T0.ATTENDANCE_DATE_TIME) BETWEEN TO_DATE('$startDate') AND TO_DATE('$endDate') 
                          AND T0.EMPLOYEE_ID = $employeeId AND T6.ICHECK IS NULL AND T5.PLAN_ID IS NULL AND T7.DARK_CHECK_TIME IS NULL
                    GROUP BY
                        EE.EMPLOYEE_ID,
                        EE.CODE ,
                        EE.EMPLOYEE_KEY_ID,
                        EE.EMPLOYEE_PICTURE,
                        EE.POSITION_NAME,
                        EE.STATUS_NAME,
                        EE.DEPARTMENT_ID,
                        EE.FIRST_NAME,
                        EE.LAST_NAME,   
                        T0.ATTENDANCE_DATE,
                        T5.PLAN_ID
                )";

                if (!Config::getFromCache('CONFIG_TNA_HIDENOTPLAN')) {
                    $selectList .= "
                        UNION (
                            SELECT
                                T0.ATTENDANCE_DATE ||' (' ||SUBSTR(TO_CHAR(TO_CHAR(TO_DATE(T0.ATTENDANCE_DATE, 'YYYY-MM-DD'), 'Day')), 1, 3) ||')' AS BALANCE_DATE1,
                                '' AS PLAN_TIME, 
                                ". getUID() ." AS TIME_BALANCE_HDR_ID,
                                EE.EMPLOYEE_ID,
                                EE.CODE  AS EMPLOYEE_CODE,
                                EE.EMPLOYEE_KEY_ID  AS EMPLOYEE_KEY_ID,
                                EE.EMPLOYEE_PICTURE AS PICTURE,
                                EE.POSITION_NAME,
                                SUBSTR(EE.LAST_NAME ,1,1)||'.' ||EE.FIRST_NAME ||' (' ||ee.CODE ||')' AS EMPLOYEE_NAME,
                                EE.STATUS_NAME,
                                EE.DEPARTMENT_ID,
                                EE.FIRST_NAME,
                                EE.LAST_NAME,   
                                T0.ATTENDANCE_DATE AS BALANCE_DATE, 
                                T0.ATTENDANCE_DATE AS CH_BALANCE_DATE, 
                                '-' AS IN_TIME,
                                '-' AS OUT_TIME,
                                '-' AS IN_DATETIME,
                                '-' AS OUT_DATETIME,
                                0 AS CLEAR_TIME,
                                0 AS DEFFERENCE_TIME,
                                0 AS ORIGINAL_DEFFERENCE_TIME,
                                0 AS UNCLEAR_TIME,
                                0 AS FAULT_TYPE,
                                0 AS NIGHT_TIME,
                                0 AS EARLY_TIME,
                                0 AS LATE_TIME,
                                0 AS IS_LOCK,
                                '' AS TYPE_NAME,
                                0 AS IS_CONFIRMED,
                                0 AS WFM_STATUS_ID,
                                '' AS WFM_STATUS_CODE,
                                '' AS WFM_STATUS_NAME,
                                0 AS IS_MANUAL,
                                '#fff' AS STATUS_COLOR,
                                '#f2dede' AS BACKGROUND_COLOR,
                                'Төлөвлөгөөгүй' AS STATUS_TEXT,
                                '#30a2dd' AS FONT_COLOR,
                                '' AS COLOR,
                                '' AS SPELL_DAY,
                                0 AS IS_LOG,
                                0 AS IS_ZERO_TIME,
                                0 AS ADD_DAY,
                                0 AS CAUSE6,
                                0 AS CAUSE20,
                                0 AS CAUSE3,
                                0 AS CAUSE13,
                                0 AS CAUSE5,                                     
                                0 AS CAUSE4,                                     
                                0 AS CAUSE7,                                     
                                0 AS CAUSE8,                                     
                                0 AS CAUSE11,                                     
                                T5.PLAN_ID
                            FROM (
                                SELECT  TO_CHAR(TO_DATE('$startDate', 'YYYY-MM-DD') - 1 + LEVEL, 'YYYY-MM-DD') AS ATTENDANCE_DATE
                                FROM DUAL
                                CONNECT BY LEVEL <= TO_DATE('$endDate', 'YYYY-MM-DD') - TO_DATE('$startDate', 'YYYY-MM-DD') + 1 
                            ) T0
                            INNER JOIN $VW_EMPLOYEE EE ON EE.EMPLOYEE_KEY_ID = EE.EMPLOYEE_KEY_ID AND EE.EMPLOYEE_ID = EE.EMPLOYEE_ID
                            LEFT JOIN (
                                SELECT 
                                    EMPLOYEE_ID, 
                                    TO_CHAR(BALANCE_DATE, 'YYYY-MM-DD') AS BALANCE_DATE,
                                    '1' AS ICHECK
                                FROM TNA_TIME_BALANCE_HDR 
                                WHERE EMPLOYEE_ID = $employeeId
                            ) T6 ON T0.ATTENDANCE_DATE = T6.BALANCE_DATE
                            LEFT JOIN (
                                SELECT
                                    T1.PLAN_ID, T1.PLAN_DATE, T0.EMPLOYEE_ID
                                FROM
                                    TMS_EMPLOYEE_TIME_PLAN_HDR T0
                                INNER JOIN TMS_EMPLOYEE_TIME_PLAN_DTL T1 ON T0.ID = T1.TIME_PLAN_ID
                            ) T5 ON EE.EMPLOYEE_ID = T5.EMPLOYEE_ID AND T0.ATTENDANCE_DATE = TO_CHAR(T5.PLAN_DATE, 'YYYY-MM-DD')
                            LEFT JOIN (SELECT TO_CHAR(ATTENDANCE_DATE_TIME, 'YYYY-MM-DD')      AS ATTENDANCE_DATE,
                                TO_CHAR(ATTENDANCE_DATE_TIME, 'YYYY-MM-DD HH24:MI:SS') AS ATTENDANCE_DATE_TIME,
                                EMPLOYEE_ID,
                                EMPLOYEE_KEY_ID,
                                IS_REMOVED_NOT_PLAN
                            FROM TNA_TIME_ATTENDANCE
                            WHERE TRUNC(ATTENDANCE_DATE_TIME) BETWEEN TO_DATE('$startDate') AND TO_DATE('$endDate')
                            ) T10 ON T0.ATTENDANCE_DATE = T10.ATTENDANCE_DATE AND EE.EMPLOYEE_ID = T10.EMPLOYEE_ID
                            LEFT JOIN (
                                SELECT 
                                    T2.PLAN_DURATION, 
                                    '1' AS DARK_CHECK_TIME,
                                    TO_CHAR(T1.PLAN_DATE, 'YYYY-MM-DD') AS MAIN_DATE, 
                                    TO_CHAR(T1.PLAN_DATE + T2.PLAN_DURATION, 'YYYY-MM-DD') AS PLAN_DATE
                                    FROM TMS_EMPLOYEE_TIME_PLAN_HDR T0 
                                    INNER JOIN TMS_EMPLOYEE_TIME_PLAN_DTL T1 ON T0.ID = T1.TIME_PLAN_ID
                                    INNER JOIN TMS_TIME_PLAN T2 ON T1.PLAN_ID = T2.PLAN_ID
                                    WHERE T0.EMPLOYEE_ID = $employeeId
                            ) T7 ON T0.ATTENDANCE_DATE = T7.PLAN_DATE
                            $where AND EE.EMPLOYEE_ID = $employeeId AND T6.ICHECK IS NULL AND T5.PLAN_ID IS NULL AND (T10.ATTENDANCE_DATE IS NULL OR T7.DARK_CHECK_TIME IS NOT NULL OR T10.IS_REMOVED_NOT_PLAN = 1)
                        )";
                }

                $selectList .= ") $filterRuleString ORDER BY $sortField $sortOrder";                

            //echo $selectCount; die;
            //echo $selectList; die;

                $rs = $this->db->SelectLimit($selectList, $rows, $offset);

                $result["rows"] = isset($rs->_array) ? $rs->_array : array();
                $result['total'] = $this->db->GetOne($selectCount);

                return $result;
            }
        }
    }

    public function subBalanceListMainDataGridV5Model() {
        $page = Input::postCheck('page') ? Input::post('page') : 1;
        $rows = Input::postCheck('rows') ? Input::post('rows') : 10;
        $offset = ($page - 1) * $rows;
        $statusText = '';
        $result = $footerArr = array();
        $where = "WHERE 1 = 1 ";
        if (Input::postCheck('params')) {
            parse_str(Input::post('params'), $params);
            $balanceDate = Input::post('balanceDate');
//                $VW_EMPLOYEE = isset($params['viewEmployee']) ? 'VW_EMPLOYEE' : 'VW_EMPLOYEE';
            $VW_EMPLOYEE = 'VW_TMS_EMPLOYEE';

            $balanceArr = explode('-', $balanceDate);
            str_replace("/", "-", $balanceArr[0]);

            $startDate = str_replace("/", "-", $balanceArr[0]);
            $endDate = str_replace("/", "-", $balanceArr[1]);

            if (!is_null($balanceDate)) {
                $balanceDateImplode = explode('-', $endDate);
                $startDate = $startDate . '-01';
                $endDate = $endDate . '-' . cal_days_in_month(CAL_GREGORIAN, $balanceDateImplode[1], $balanceDateImplode[0]);

                $startDate = ($startDate < $params['startDate']) ? $params['startDate'] : $startDate;
                $endDate = ($endDate > $params['endDate']) ? $params['endDate'] : $endDate;
            } else {
                $startDate = $params['startDate'];
                $endDate = $params['endDate'];
            }

            if (!empty($startDate) && !empty($endDate)) {
                $filterString = "";

                if (!empty($params['filterString'])) {
                    $filterString = " AND (LOWER(bl.FIRST_NAME) LIKE LOWER('%" . $params['filterString'] . "%') OR LOWER(bl.LAST_NAME) LIKE LOWER('%" . $params['filterString'] . "%') OR LOWER(EE.REGISTER_NUMBER) LIKE LOWER('%" . $params['filterString'] . "%') OR LOWER(EE.CODE) LIKE LOWER('%" . $params['filterString'] . "%'))";
                }

                if (is_array($params['newDepartmentId']) && $params['newDepartmentId'][0]) {
                    $departmentIds = $params['newDepartmentId'];
                    $departmentIds = implode(',', $departmentIds);
                    $isChild = issetVar($params['isChild']);

                    $departmentIds = $this->getAllChildDepartmentModel($departmentIds, $isChild);                        

                    $where = " WHERE EE.DEPARTMENT_ID IN ( " . $departmentIds . ") ";
                }

                $filterRuleString = ' WHERE 1 = 1 ';

                if (Input::postCheck('filterRules')) {
                    $filterRules = json_decode(Str::cp1251_utf8($_POST['filterRules']), true);

                    if (count($filterRules) > 0) {

                        foreach ($filterRules as $rule) {
                            $ruleValue = Input::param(Str::lower(trim($rule['value'])));
                            switch ($rule['field']) {
                                case 'CLEAR_TIME':
                                case 'DEFFERENCE_TIME':
                                    $ruleValue = explode(':', $ruleValue);
                                    $ruleValue = (float) $ruleValue[0] + (float) $ruleValue[1] / 60;
                                    $filterRuleString .= ' AND ' . $rule['field'] . " = '" . $ruleValue . "'";
                                    break;
                                case 'OUT_TIME':
                                case 'IN_TIME':
                                    $filterRuleString .= ' AND ' . $rule['field'] . " = '" . $ruleValue . "'";
                                    break;
                                case 'STATUS_TEXT':
                                    $statusText = $ruleValue;
                                    break;
                                default:
                                    $filterRuleString .= ' AND LOWER(' . $rule['field'] . ") LIKE LOWER('%" . $ruleValue . "%')";
                                    break;
                            }
                        }
                    }
                }

                $sortField = 'FIRST_NAME , BALANCE_DATE';
                $sortOrder = 'ASC';

                if (Input::postCheck('sort') && Input::postCheck('order')) {
                    $sortField = Input::post('sort');
                    $sortOrder = Input::post('order');
                }

                $employeeId = Input::post('employeeId');
                $employeeKeyId = Input::post('employeeKeyId');

                $whereSql = $where . $filterString . " AND EE.EMPLOYEE_ID = " . $employeeId;

            $tmsBalanceIsMovementEmployee = (isset($params['isMovementEmployee']) && $params['isMovementEmployee']) ? Config::getFromCache('tmsBalanceIsMovementEmployee') : '0';

            if ($tmsBalanceIsMovementEmployee == '1') {
                $whereSql .= " AND EE.EMPLOYEE_KEY_ID = '$employeeKeyId'";
            }

            $selectList = "
                SELECT *
                FROM ( (
                    SELECT 
                        DISTINCT 
                        TO_CHAR(AA.BALANCE_DATE, 'YYYY-MM-DD') ||' (' || 
                        CASE WHEN TO_CHAR(AA.BALANCE_DATE, 'Dy') = 'Mon' THEN 'Да'
                            WHEN TO_CHAR(AA.BALANCE_DATE, 'Dy') = 'Tue' THEN 'Мя'
                            WHEN TO_CHAR(AA.BALANCE_DATE, 'Dy') = 'Wed' THEN 'Лх'
                            WHEN TO_CHAR(AA.BALANCE_DATE, 'Dy') = 'Thu' THEN 'Пү'
                            WHEN TO_CHAR(AA.BALANCE_DATE, 'Dy') = 'Fri' THEN 'Ба'
                            WHEN TO_CHAR(AA.BALANCE_DATE, 'Dy') = 'Sat' THEN 'Бя'
                            WHEN TO_CHAR(AA.BALANCE_DATE, 'Dy') = 'Sun' THEN 'Ня' 
                        END ||')' BALANCE_DATE1,
                        ROUND(AA.PLAN_TIME/60, 2) || ' (' || TO_CHAR(AA.PLAN_START_TIME, 'HH24:MI') ||'-'|| TO_CHAR(AA.PLAN_END_TIME, 'HH24:MI') ||')' AS PLAN_TIME, 
                        AA.TIME_BALANCE_HDR_ID,    
                        EE.EMPLOYEE_ID,
                        ee.CODE             AS EMPLOYEE_CODE,
                        ee.EMPLOYEE_PICTURE AS PICTURE,
                        ee.POSITION_NAME,
                        SUBSTR(EE.LAST_NAME ,1,1)||'.' ||EE.FIRST_NAME ||' (' ||ee.CODE ||')' AS EMPLOYEE_NAME,
                        ee.STATUS_NAME,
                        EE.FIRST_NAME,
                        EE.LAST_NAME,    
                        TO_CHAR(AA.BALANCE_DATE, 'YYYY-MM-DD') BALANCE_DATE,    
                        TO_CHAR(AA.BALANCE_DATE, 'YYYY-MM-DD') CH_BALANCE_DATE,    
                        CASE WHEN AA.START_TIME IS NULL THEN '-' ELSE  TO_CHAR(AA.START_TIME, 'HH24:MI') END AS IN_TIME,
                        CASE WHEN AA.END_TIME IS NULL THEN '-' ELSE  TO_CHAR(AA.END_TIME, 'HH24:MI') END AS OUT_TIME,
                        CASE WHEN AA.START_TIME IS NULL THEN '' ELSE  TO_CHAR(AA.START_TIME, 'YYYY-MM-DD HH24:MI') END AS IN_TIME_LONG,
                        CASE WHEN AA.END_TIME IS NULL THEN '' ELSE  TO_CHAR(AA.END_TIME, 'YYYY-MM-DD HH24:MI') END AS OUT_TIME_LONG,
                        NVL(TO_CHAR(AA.START_TIME, 'YYYY-MM-DD HH24:MI:SS'),'-') IN_DATETIME,
                        NVL(TO_CHAR(AA.END_TIME, 'YYYY-MM-DD HH24:MI:SS'),'-') OUT_DATETIME,
                        AA.CLEAN_TIME AS CLEAR_TIME,
                        AA.DEFFERENCE_TIME,
                        AA.ORIGINAL_DEFFERENCE_TIME,
                        AA.UNCLEAN_TIME AS UNCLEAR_TIME,
                        AA.FAULT_TYPE,
                        AA.NIGHT_TIME,
                        AA.EARLY_TIME,
                        AA.LATE_TIME,
                        AA.IS_LOCK,
                        AA.IS_CONFIRMED,    
                        BB.ID AS WFM_STATUS_ID,
                        LOWER(BB.WFM_STATUS_CODE) AS WFM_STATUS_CODE,
                        BB.WFM_STATUS_NAME,
                        CASE
                            WHEN AA.IS_MANUAL IS NULL
                            THEN 0
                            ELSE AA.IS_MANUAL
                        END AS IS_MANUAL,
                        '#000' AS  STATUS_COLOR, 
                        CASE
                            " 
                            . ( Config::getFromCache('tmsCustomerCode') == 'gov' ?  "WHEN TO_CHAR(AA.BALANCE_DATE, 'Dy') = 'Sat' THEN '#b3e6b3' WHEN TO_CHAR(AA.BALANCE_DATE, 'Dy') = 'Sun' THEN '#b3e6b3'" : '')
                            . "
                            WHEN AA.DEFFERENCE_TIME != 0 OR AA.IS_CONFIRMED = 0 THEN '#f2dede'        
                            WHEN  AA.PLAN_TIME >0 AND AA.START_TIME IS NOT NULL AND AA.END_TIME IS NOT NULL AND AA.CLEAN_TIME=AA.PLAN_TIME THEN '#dff0d8'
                            WHEN  AA.PLAN_TIME >0 AND AA.START_TIME IS NOT NULL AND AA.END_TIME IS NOT NULL AND AA.CAUSE13 = 0 THEN '#dff0d8'
                            WHEN  AA.PLAN_TIME >0 AND AA.START_TIME IS NOT NULL AND AA.END_TIME IS NOT NULL AND AA.CLEAN_TIME <= AA.PLAN_TIME THEN '#fbeac5'
                            WHEN  AA.PLAN_TIME >0 AND AA.START_TIME IS NULL AND AA.END_TIME IS NULL AND AA.CLEAN_TIME = AA.PLAN_TIME THEN '#dff0d8'
                            WHEN  AA.PLAN_TIME >0 AND AA.START_TIME IS NULL AND AA.END_TIME IS NULL AND AA.CLEAN_TIME = 0 THEN '#f2dede'
                            WHEN  AA.PLAN_TIME >0 AND AA.START_TIME IS NOT NULL AND AA.END_TIME IS NULL THEN '#f2dede'
                            WHEN  AA.PLAN_TIME >0 AND AA.START_TIME IS NULL AND AA.END_TIME IS NOT NULL THEN '#f2dede'
                            WHEN  AA.PLAN_TIME =0 AND AA.START_TIME IS NOT NULL AND AA.END_TIME IS NOT NULL THEN '#f2dede'
                            WHEN  AA.PLAN_TIME =0 AND AA.START_TIME IS NOT NULL AND AA.END_TIME IS NULL THEN '#f2dede'
                            WHEN  AA.PLAN_TIME =0 AND AA.START_TIME IS NULL AND AA.END_TIME IS NOT NULL THEN '#f2dede'
                            WHEN  AA.PLAN_TIME =0 AND AA.START_TIME IS NULL AND AA.END_TIME IS NULL THEN '#ffffff'
                            WHEN AA.DEFFERENCE_TIME = 0 AND AA.IS_CONFIRMED = 1 THEN '#dff0d8' 
                            WHEN AA.IS_USER_CONFIRMED = 1 THEN '#dff0d8' 
                            END AS BACKGROUND_COLOR,     
                        CASE 
                            WHEN AA.CAUSE7 != 0 THEN 'Ээлжийн амралт'    
                            WHEN  AA.PLAN_TIME >0 AND AA.START_TIME IS NOT NULL AND AA.END_TIME IS NOT NULL AND AA.CLEAN_TIME=AA.PLAN_TIME THEN 'Зөрчилгүй'
                            WHEN  AA.PLAN_TIME >0 AND AA.START_TIME IS NOT NULL AND AA.END_TIME IS NOT NULL AND AA.CLEAN_TIME <= AA.PLAN_TIME THEN 'Зөрчилгүй'
                            WHEN  AA.PLAN_TIME >0 AND AA.START_TIME IS NOT NULL AND AA.END_TIME IS NOT NULL AND AA.CAUSE13 = 0  THEN 'Зөрчилгүй'
                            WHEN  AA.PLAN_TIME >0 AND AA.START_TIME IS NULL AND AA.END_TIME IS NULL AND AA.CLEAN_TIME = AA.PLAN_TIME THEN 'Баталсан'
                            WHEN  AA.PLAN_TIME >0 AND AA.START_TIME IS NULL AND AA.END_TIME IS NULL AND AA.CLEAN_TIME = 0 THEN 'Зөрчилтэй'
                            WHEN  AA.PLAN_TIME >0 AND AA.START_TIME IS NOT NULL AND AA.END_TIME IS NULL THEN 'Гарсан цаг дутуу'
                            WHEN  AA.PLAN_TIME >0 AND AA.START_TIME IS NULL AND AA.END_TIME IS NOT NULL THEN 'Орсон цаг дутуу'
                            WHEN  AA.PLAN_TIME =0 AND AA.START_TIME IS NOT NULL AND AA.END_TIME IS NOT NULL THEN 'Илүү цаг'
                            WHEN  AA.PLAN_TIME =0 AND AA.START_TIME IS NOT NULL AND AA.END_TIME IS NULL THEN 'Илүү цаг'
                            WHEN  AA.PLAN_TIME =0 AND AA.START_TIME IS NULL AND AA.END_TIME IS NOT NULL THEN 'Илүү цаг'
                            WHEN  AA.PLAN_TIME =0 AND AA.START_TIME IS NULL AND AA.END_TIME IS NULL THEN 'Төлөвлөгөөгүй'
                            WHEN  AA.IS_LOCK = 1 THEN 'Түгжсэн'
                            END AS STATUS_TEXT,
                        '#000' AS FONT_COLOR,
                        CASE
                            WHEN SUBSTR(TO_CHAR(TO_CHAR(AA.BALANCE_DATE, 'Day')), 1, 3) = 'Sat' THEN '#ffc37b'
                            WHEN SUBSTR(TO_CHAR(TO_CHAR(AA.BALANCE_DATE, 'Day')), 1, 3) = 'Sun' THEN '#ffc37b'
                            WHEN AA.IS_LOCK = 1 THEN '#578ebe'
                            WHEN AA.END_TIME IS NULL THEN '#F00'
                            WHEN AA.START_TIME IS NULL THEN '#F00'                                
                            ELSE '#FFF'
                        END AS COLOR,
                        SUBSTR(TO_CHAR(TO_CHAR(AA.BALANCE_DATE, 'Day')), 1, 3) AS SPELL_DAY,
                        0 AS IS_LOG,
                        AA.IS_ZERO_TIME,
                        AA.ADD_DAY,
                        AA.CAUSE6,
                        AA.CAUSE20,
                        AA.CAUSE3,
                        AA.CAUSE13,
                        AA.CAUSE5,
                        AA.CAUSE4,
                        AA.CAUSE7,
                        AA.CAUSE1,
                        AA.CAUSE2,
                        AA.CAUSE9,
                        AA.CAUSE10,
                        AA.CAUSE12,
                        AA.CAUSE14,
                        AA.CAUSE15,
                        AA.CAUSE8,
                        AA.CAUSE11,
                        AA.CAUSE16,
                        AA.CAUSE17,
                        AA.CAUSE18,
                        AA.CAUSE19,
                        AA.CAUSE21,
                        AA.CAUSE22,
                        AA.CAUSE23,
                        AA.CAUSE24,
                        AA.CAUSE25,
                        AA.CAUSE26,
                        AA.CAUSE27,
                        AA.CAUSE28,
                        AA.CAUSE29,
                        AA.CAUSE30,
                        COALESCE(AA.IS_USER_CONFIRMED, 0) AS IS_USER_CONFIRMED,
                        '' AS PLAN_ID
                    FROM $VW_EMPLOYEE EE ";

            if ($tmsBalanceIsMovementEmployee != '1') {
                $selectList .= "INNER JOIN ( 
                        SELECT
                            MAX(EMPLOYEE_KEY_ID) AS EMPLOYEE_KEY_ID
                        FROM
                            HRM_EMPLOYEE_KEY
                        WHERE CURRENT_STATUS_ID <> 6 
                              AND ( 
                                (TRUNC(WORK_START_DATE) <= '" . $startDate . "' AND ((TRUNC(WORK_END_DATE) BETWEEN '" . $startDate . "' AND '" . $endDate . "') OR WORK_END_DATE IS NULL))
                                 OR
                                (TRUNC(WORK_START_DATE) BETWEEN '" . $startDate . "' AND '" . $endDate . "' AND (TRUNC(WORK_END_DATE) <= '" . $endDate . "' OR WORK_END_DATE IS NULL))
                              )
                        GROUP BY
                           EMPLOYEE_ID
                    ) ACTIVE_HEK ON ACTIVE_HEK.EMPLOYEE_KEY_ID = EE.EMPLOYEE_KEY_ID 
                    LEFT JOIN TNA_TIME_BALANCE_HDR AA ON EE.EMPLOYEE_ID = AA.EMPLOYEE_ID
                    " ;
            } else {
                $selectList .= "
                    INNER JOIN HRM_EMPLOYEE_KEY T0 ON EE.EMPLOYEE_KEY_ID = T0.EMPLOYEE_KEY_ID 
                    LEFT JOIN TNA_TIME_BALANCE_HDR AA ON EE.EMPLOYEE_ID = AA.EMPLOYEE_ID AND AA.BALANCE_DATE BETWEEN T0.WORK_START_DATE  AND NVL(T0.WORK_END_DATE, TO_DATE('$endDate', 'YYYY-MM-DD'))
                    ";
            }
            $selectList .= "
                    LEFT JOIN META_WFM_STATUS BB ON BB.ID = AA.WFM_STATUS_ID
                    $whereSql AND AA.BALANCE_DATE IS NOT NULL AND AA.BALANCE_DATE >= TO_DATE('" . $startDate . "', 'YYYY-MM-DD') AND AA.BALANCE_DATE <= TO_DATE('" . $endDate . "', 'YYYY-MM-DD'))";

                $selectList .= ") $filterRuleString ORDER BY $sortField $sortOrder";       

                    /**
                     * SELECT
                        MAX(EE.EMPLOYEE_KEY_ID) AS EMPLOYEE_KEY_ID,
                        MAX(EE.WORK_START_DATE) AS WORK_START_DATE
                        FROM HRM_EMPLOYEE_KEY EE
                        $whereSql AND EE.CURRENT_STATUS_ID <> 6 
                              AND ( 
                                (TRUNC(EE.WORK_START_DATE) BETWEEN '" . $startDate . "' AND '" . $endDate . "' AND (TRUNC(EE.WORK_END_DATE) <= '" . $endDate . "' OR EE.WORK_END_DATE IS NULL))
                              )
                        GROUP BY
                           EE.EMPLOYEE_ID
                        UNION ALL
                        SELECT
                        MAX(EE.EMPLOYEE_KEY_ID) AS EMPLOYEE_KEY_ID,
                        MAX(EE.WORK_START_DATE) AS WORK_START_DATE
                        FROM HRM_EMPLOYEE_KEY EE
                        $whereSql AND EE.CURRENT_STATUS_ID <> 6 
                              AND (
                                (TRUNC(EE.WORK_END_DATE) BETWEEN '" . $startDate . "' AND '" . $endDate . "' AND (TRUNC(EE.WORK_START_DATE) <= '" . $startDate . "' OR EE.WORK_END_DATE IS NULL))
                              )
                        GROUP BY
                           EE.EMPLOYEE_ID
                     */   

            $selectListSum = "
                SELECT 
                    SUM(CLEAN_TIME) AS CLEAR_TIME,
                    SUM(DEFFERENCE_TIME) AS DEFFERENCE_TIME,
                    SUM(ORIGINAL_DEFFERENCE_TIME) AS ORIGINAL_DEFFERENCE_TIME,
                    SUM(UNCLEAN_TIME) AS UNCLEAR_TIME,
                    SUM(FAULT_TYPE) AS FAULT_TYPE,
                    SUM(NIGHT_TIME) AS NIGHT_TIME,
                    SUM(EARLY_TIME) AS EARLY_TIME,
                    SUM(LATE_TIME) AS LATE_TIME,
                    SUM(CAUSE6) AS CAUSE6,
                    SUM(CAUSE20) AS CAUSE20,
                    SUM(CAUSE3) AS CAUSE3,
                    SUM(CAUSE13) AS CAUSE13,
                    SUM(CAUSE5) AS CAUSE5,
                    SUM(CAUSE4) AS CAUSE4,
                    SUM(CAUSE7) AS CAUSE7,
                    SUM(CAUSE1) AS CAUSE1,
                    SUM(CAUSE2) AS CAUSE2,
                    SUM(CAUSE9) AS CAUSE9,
                    SUM(CAUSE10) AS CAUSE10,
                    SUM(CAUSE12) AS CAUSE12,
                    SUM(CAUSE14) AS CAUSE14,
                    SUM(CAUSE15) AS CAUSE15,
                    SUM(CAUSE8) AS CAUSE8,
                    SUM(CAUSE11) AS CAUSE11,
                    SUM(CAUSE16) AS CAUSE16,
                    SUM(CAUSE17) AS CAUSE17,
                    SUM(CAUSE18) AS CAUSE18,
                    SUM(CAUSE19) AS CAUSE19,
                    SUM(CAUSE21) AS CAUSE21,
                    SUM(CAUSE22) AS CAUSE22,
                    SUM(CAUSE23) AS CAUSE23,
                    SUM(CAUSE24) AS CAUSE24,
                    SUM(CAUSE25) AS CAUSE25,
                    SUM(CAUSE26) AS CAUSE26,
                    SUM(CAUSE27) AS CAUSE27,
                    SUM(CAUSE28) AS CAUSE28,
                    SUM(CAUSE29) AS CAUSE29,
                    SUM(CAUSE30) AS CAUSE30,
                    ROUND(SUM(PLAN_TIME)/60, 2) AS PLAN_TIME,
                    ' ' AS BALANCE_DATE1,
                    ' ' AS IN_TIME,
                    ' ' AS OUT_TIME,
                    ' ' AS STATUS_TEXT
                FROM ((
                    SELECT DISTINCT
                        AA.CLEAN_TIME,
                        AA.DEFFERENCE_TIME,
                        AA.ORIGINAL_DEFFERENCE_TIME,
                        AA.UNCLEAN_TIME,
                        AA.FAULT_TYPE,
                        AA.NIGHT_TIME,
                        AA.EARLY_TIME,
                        AA.LATE_TIME,
                        AA.CAUSE6,
                        AA.CAUSE20,
                        AA.CAUSE3,
                        AA.CAUSE13,
                        AA.CAUSE5,
                        AA.CAUSE4,
                        AA.CAUSE7,
                        AA.CAUSE1,
                        AA.CAUSE2,
                        AA.CAUSE9,
                        AA.CAUSE10,
                        AA.CAUSE12,
                        AA.CAUSE14,
                        AA.CAUSE15,
                        AA.CAUSE8,
                        AA.CAUSE11,
                        AA.CAUSE16,
                        AA.CAUSE17,
                        AA.CAUSE18,
                        AA.CAUSE19,
                        AA.CAUSE21,
                        AA.CAUSE22,
                        AA.CAUSE23,
                        AA.CAUSE24,
                        AA.CAUSE25,
                        AA.CAUSE26,
                        AA.CAUSE27,
                        AA.CAUSE28,
                        AA.CAUSE29,
                        AA.CAUSE30,
                        AA.BALANCE_DATE,
                        AA.PLAN_TIME
                    FROM $VW_EMPLOYEE EE ";

            if ($tmsBalanceIsMovementEmployee != '1') {
                $selectListSum .= "INNER JOIN ( 
                    SELECT
                        MAX(EMPLOYEE_KEY_ID) AS EMPLOYEE_KEY_ID
                    FROM
                        HRM_EMPLOYEE_KEY
                    WHERE CURRENT_STATUS_ID <> 6 
                          AND ( 
                            (TRUNC(WORK_START_DATE) <= '" . $startDate . "' AND ((TRUNC(WORK_END_DATE) BETWEEN '" . $startDate . "' AND '" . $endDate . "') OR WORK_END_DATE IS NULL))
                             OR
                            (TRUNC(WORK_START_DATE) BETWEEN '" . $startDate . "' AND '" . $endDate . "' AND (TRUNC(WORK_END_DATE) <= '" . $endDate . "' OR WORK_END_DATE IS NULL))
                          )
                    GROUP BY
                       EMPLOYEE_ID
                ) ACTIVE_HEK ON ACTIVE_HEK.EMPLOYEE_KEY_ID = EE.EMPLOYEE_KEY_ID    
                LEFT JOIN TNA_TIME_BALANCE_HDR AA ON EE.EMPLOYEE_ID = AA.EMPLOYEE_ID " ;
            } else {
                $selectListSum .= "
                    INNER JOIN HRM_EMPLOYEE_KEY T0 ON EE.EMPLOYEE_KEY_ID = T0.EMPLOYEE_KEY_ID 
                    LEFT JOIN TNA_TIME_BALANCE_HDR AA ON EE.EMPLOYEE_ID = AA.EMPLOYEE_ID AND AA.BALANCE_DATE BETWEEN T0.WORK_START_DATE  AND NVL(T0.WORK_END_DATE, TO_DATE('$endDate', 'YYYY-MM-DD'))
                    ";
            }
            $selectListSum .= "
                    LEFT JOIN META_WFM_STATUS BB ON BB.ID = AA.WFM_STATUS_ID
                    $whereSql AND AA.BALANCE_DATE IS NOT NULL  AND AA.BALANCE_DATE >= TO_DATE('" . $startDate . "', 'YYYY-MM-DD') AND AA.BALANCE_DATE <= TO_DATE('" . $endDate . "', 'YYYY-MM-DD'))";
                $selectListSum .= ")";
            $selectListSumArr = $this->db->GetAll($selectListSum);          

            //echo $selectCount; die;
            //echo $selectList; die;

                $rs = $this->db->SelectLimit($selectList, $rows, $offset);

                $result["rows"] = isset($rs->_array) ? $rs->_array : array();
                $result['total'] = count($this->db->GetAll($selectList));
                $result['footer'] = $selectListSumArr;

                return $result;
            }
        }
    }        

    public function getTnaplanIslockModel($departmentId) {
        return $this->db->GetRow("SELECT DISTINCT IS_STARTTIME, IS_ENDTIME FROM TNA_APPROVE_CONFIG WHERE DEPARTMENT_ID = $departmentId AND USER_ID = " . Ue::sessionUserKeyId());
    }        

    public function getBalanceDetailListV3Model($balanceHdrId, $balanceDate, $employeeKeyId) {
        $colStr = '';

        for($i = 1; $i <= 30; $i++) {
            $colStr .= 'CAUSE' . $i . ', CAUSE' . $i . '_DESC,';
        }

        $colStr = rtrim($colStr, ',');

        $query = "
                    SELECT IS_LOCK, LOCK_END_DATE,
                        $colStr
                    FROM TNA_TIME_BALANCE_HDR 
                    WHERE  TIME_BALANCE_HDR_ID = $balanceHdrId";

        (Array) $response = array('status' => 'error');
        try {
            $result = $this->db->GetRow($query);

            $qry = "
                SELECT 
                    TCT.CAUSE_TYPE_ID, 
                    TCT.NAME,
                    TCT.CODE,
                    TNA_GET_VACATION_TIME(TO_DATE('$balanceDate','YYYY-MM-DD'), $employeeKeyId, TCT.CAUSE_TYPE_ID) AS V_TIME,
                    '' AS CAUSE_PARAM,
                    '' AS CAUSE_PARAM_VALUE,
                    '' AS DESCRIPTION,
                    '' AS DESCRIPTION_CAUSE_DTL,
                    '' AS DESCRIPTION_CAUSE,
                    TCT.IS_EDIT
                FROM TNA_CAUSE_TYPE TCT WHERE IS_ACTIVE = 1
                ORDER BY CAUSE_TYPE_ID";

            $data = $this->db->GetAll($qry);

            $resultData = array();

            foreach ($data as $k => $row) {
                $kk = $k + 1;
                $resultData[$k]['CAUSE_TYPE_ID'] = $row['CAUSE_TYPE_ID'];
                $resultData[$k]['NAME'] = $row['NAME'];
                $resultData[$k]['V_TIME'] = isset($result['CAUSE'.$row['CAUSE_TYPE_ID']]) ? $result['CAUSE'.$row['CAUSE_TYPE_ID']] : '0';
                $resultData[$k]['CAUSE_PARAM'] = $row['CAUSE_PARAM'];
                $resultData[$k]['CAUSE_PARAM_VALUE'] = $row['CAUSE_PARAM_VALUE'];
                $resultData[$k]['CODE'] = $row['CODE'];
                $resultData[$k]['DESCRIPTION_CAUSE_DTL'] = '0';
                $resultData[$k]['DESCRIPTION_CAUSE'] = isset($result['CAUSE' . $row['CAUSE_TYPE_ID'] . '_DESC']) ? $result['CAUSE' . $row['CAUSE_TYPE_ID'] . '_DESC'] : '';
                $resultData[$k]['IS_EDIT'] = $row['IS_EDIT'];

                $resultDescriptionCheck = $this->db->GetOne("   SELECT COUNT(LV_HEADER_ID) AS COUNTT FROM (
                                                                    SELECT 
                                                                         HDR.LV_HEADER_ID,
                                                                         HDR.EMPLOYEE_KEY_ID,
                                                                       TO_CHAR(DTL.START_DATE, 'YYYY-MM-DD') AS START_DATE,
                                                                       TO_CHAR(DTL.END_DATE, 'YYYY-MM-DD') AS END_DATE,
                                                                       HDR.WFM_STATUS_ID

                                                                     FROM LM_HEADER HDR
                                                                        INNER JOIN (
                                                                            SELECT 
                                                                                LV_DETAIL_ID,
                                                                                LV_HEADER_ID,
                                                                                TO_DATE(TO_CHAR(START_DATE, 'YYYY-MM-DD')||' '||TO_CHAR(START_TIME, 'HH24:MI:SS'), 'YYYY-MM-DD HH24:MI:SS') AS START_DATE ,
                                                                                TO_DATE(TO_CHAR(END_DATE, 'YYYY-MM-DD')||' '||TO_CHAR(END_TIME, 'HH24:MI:SS'), 'YYYY-MM-DD HH24:MI:SS') AS END_DATE,
                                                                                LEAVE_MINUTE,
                                                                                CREATED_DATE,
                                                                                LEAVE_HOUR, LEAVE_DAY,
                                                                                CREATED_USER_ID,
                                                                                MODIFIED_DATE,
                                                                                MODIFIED_USER_ID,
                                                                                START_TIME,
                                                                                END_TIME,
                                                                                IS_PAID,
                                                                                COST_AMOUNT 
                                                                            FROM LM_DETAIL
                                                                            WHERE START_DATE IS NOT NULL AND END_DATE IS NOT NULL AND START_TIME IS NOT NULL AND END_TIME IS NOT NULL
                                                                        ) DTL ON DTL.LV_HEADER_ID = HDR.LV_HEADER_ID 
                                                                        INNER JOIN HRM_EMPLOYEE_KEY EMPK ON EMPK.EMPLOYEE_KEY_ID = HDR.EMPLOYEE_KEY_ID
                                                                        INNER JOIN HRM_EMPLOYEE EMP ON EMP.EMPLOYEE_ID = EMPK.EMPLOYEE_ID
                                                                        INNER JOIN LM_TYPE TP ON HDR.TYPE_ID = TP.TYPE_ID
                                                                        INNER JOIN TNA_CAUSE_LM_TYPE MP ON TP.TYPE_ID = MP.LM_TYPE_ID
                                                                        WHERE  EMP.EMPLOYEE_ID = $employeeKeyId AND MP.CAUSE_TYPE_ID = '". $row['CAUSE_TYPE_ID'] ."'
                                                                            AND '$balanceDate' BETWEEN DTL.START_DATE  AND DTL.END_DATE
                                                                ) TEMP");

                if ($resultDescriptionCheck) {
                    $resultData[$k]['DESCRIPTION_CAUSE_DTL'] = '1';
                    $resultData[$k]['DESCRIPTION_CAUSE'] = $this->db->GetOne("   
                                                            SELECT 
                                                                CASE WHEN TEMP.DESCRIPTION IS NULL THEN '' ELSE TEMP.DESCRIPTION END AS DESCRIPTION
                                                            FROM (
                                                                SELECT 
                                                                    HDR.LV_HEADER_ID,
                                                                    HDR.EMPLOYEE_KEY_ID,
                                                                    TO_CHAR(DTL.START_DATE, 'YYYY-MM-DD') AS START_DATE,
                                                                    TO_CHAR(DTL.END_DATE, 'YYYY-MM-DD') AS END_DATE,
                                                                    HDR.WFM_STATUS_ID,
                                                                    HDR.DESCRIPTION
                                                                FROM LM_HEADER HDR
                                                                INNER JOIN (
                                                                    SELECT 
                                                                        LV_DETAIL_ID,
                                                                        LV_HEADER_ID,
                                                                        TO_CHAR(START_DATE, 'YYYY-MM-DD')||' '||TO_CHAR(START_TIME, 'HH24:MI:SS') AS START_DATE ,
                                                                        TO_CHAR(END_DATE, 'YYYY-MM-DD')||' '||TO_CHAR(END_TIME, 'HH24:MI:SS') AS END_DATE,
                                                                        LEAVE_MINUTE,
                                                                        CREATED_DATE,
                                                                        LEAVE_HOUR, LEAVE_DAY,
                                                                        CREATED_USER_ID,
                                                                        MODIFIED_DATE,
                                                                        MODIFIED_USER_ID,
                                                                        START_TIME,
                                                                        END_TIME,
                                                                        IS_PAID,
                                                                        COST_AMOUNT 
                                                                    FROM LM_DETAIL
                                                                ) DTL ON DTL.LV_HEADER_ID = HDR.LV_HEADER_ID 
                                                                INNER JOIN HRM_EMPLOYEE_KEY EMPK ON EMPK.EMPLOYEE_KEY_ID = HDR.EMPLOYEE_KEY_ID
                                                                INNER JOIN HRM_EMPLOYEE EMP ON EMP.EMPLOYEE_ID = EMPK.EMPLOYEE_ID
                                                                INNER JOIN LM_TYPE TP ON HDR.TYPE_ID = TP.TYPE_ID
                                                                INNER JOIN TNA_CAUSE_LM_TYPE MP ON TP.TYPE_ID = MP.LM_TYPE_ID
                                                                WHERE  EMP.EMPLOYEE_ID = $employeeKeyId 
                                                                    AND MP.CAUSE_TYPE_ID = '". $row['CAUSE_TYPE_ID'] ."'
                                                                    AND '$balanceDate' BETWEEN DTL.START_DATE  AND DTL.END_DATE
                                                                ORDER BY HDR.LV_HEADER_ID DESC
                                                            ) TEMP");
                }
            }

            (Array) $response = array();
            $response['causeType'] = $resultData;
            $response['moreDtl'] = '0';
            $response['lock'] = issetVar($result['LOCK_END_DATE']);

            $result = $this->db->GetAll("
                SELECT DISTINCT
                    ACCESS_TYPE_ID,
                    ATTENDANCE_DATE_TIME,
                    DESCRIPTION
                FROM TNA_TIME_ATTENDANCE
                WHERE 
                    EMPLOYEE_KEY_ID = $employeeKeyId AND
                    TO_CHAR(ATTENDANCE_DATE_TIME, 'YYYY-MM-DD') = '" . Date::format("Y-m-d", $balanceDate) . "'");

            if ($result) {
                $response['moreDtl'] = '1';
            }
            $response['status'] = 'success';

        } catch (Exception $ex) {
            (Array) $response = array('status' => 'error', 'message' => $ex->msg , 'messageex' => $ex);
        }

        return $response;
    }        

    public function planTimeMoreModel($employeeId, $blanceDate) {
        $qry = "
            SELECT 
                ETP.TYPENAME,
                ETP.NAME,
                ETP.START_HOUR,
                ETP.END_HOUR
            FROM TABLE(FNC_TNA_GET_TIME_BALANCE(TO_DATE('" . $blanceDate . "','yyyy-MM-dd'), TO_DATE('2016-01-01','yyyy-MM-dd'), $employeeId)) BB
            LEFT JOIN 
            (
              SELECT 
                EPL.EMPLOYEE_ID,
                EPL.PLAN_DATE,
                TP.NAME AS TYPENAME,
                DTL.NAME,
                TO_CHAR(DTL.START_TIME, 'HH24:MI') AS START_HOUR,
                TO_CHAR(DTL.END_TIME, 'HH24:MI') AS END_HOUR
              FROM
                TNA_EMPLOYEE_TIME_PLAN EPL 
              INNER JOIN TNA_TIME_PLAN TPL ON TPL.PLAN_ID = EPL.PLAN_ID
              INNER JOIN TNA_TIME_PLAN_DETAIL DTL ON DTL.PLAN_ID = TPL.PLAN_ID
              LEFT JOIN TNA_TIME_PLAN_TYPE TP ON TP.ID = TPL.TYPE_ID
            ) ETP ON 
              BB.EMPLOYEE_ID = ETP.EMPLOYEE_ID AND 
              TO_CHAR(BB.BALANCE_DATE, 'yyyy-MM-dd') = TO_CHAR(ETP.PLAN_DATE, 'yyyy-MM-dd')
            WHERE ETP.EMPLOYEE_ID = $employeeId ORDER BY ETP.NAME DESC";

        $result = $this->db->GetAll($qry);

        if ($result) {
            return array(
                'status' => 'success',
                'data' => $result,
                "message" => ""
            );
        }
        return array("status" => "error", "message" => "Мэдээлэл байхгүй");
    }        

    public function userSessionIsFullModel() {
        return false;
        $result = $this->db->GetRow("SELECT  COUNT, SESSION_COUNT FROM  TNA_PLAN_PERMISSION_CONFIG  WHERE  USER_ID = " . Ue::sessionUserId());
        if ($result) {
            if (intval($result['SESSION_COUNT']) >= intval($result['COUNT'])) {
                return true;
            }
        }
        return false;
    }


    public function getEmployeeConfirmDataV3Model() {

        $uniqueId = Input::post('timeBalanceHdrId');
        $uniqueId = ($uniqueId === '1') ? '-1' : $uniqueId;

        $nightTime = 0;
        $nightTimeRange = $this->db->GetOne("SELECT VALUE FROM PR_CONFIG WHERE CONFIG_ID = 4");
        $defaultDefferenceTime = (float) Config::getFromCache('tmsDefaultDefferenceTime');
        $defaultDefferenceTime = $defaultDefferenceTime ? $defaultDefferenceTime : -60;

        $isMod= Input::post('isMod');
        $currentDate = Date::currentDate();

        $balanceTimeBalanceId = ($uniqueId === '-1') ? getUID() : $uniqueId;
        $wfmStatusId = Input::post('wfmStatusId');
        $wfmStatusCode = Input::post('wfmStatusCode');
        $userKeyId = Ue::sessionUserKeyId();
        $ticketProc = false;

        $params = array();
        foreach ($_POST as $k => $row) {
            if (isset($_POST[$k][$uniqueId])) {
                if (is_array($_POST[$k][$uniqueId])) {
                    foreach ($_POST[$k][$uniqueId] as $key => $value) {
                        $params[$k][$key] = Input::param($row[$uniqueId][$key]);
                    }
                } else {
                    $params[$k] = Input::param($row[$uniqueId]);
                }
            }
        }

        $index = 1;

        $response = array(
            'status' => 'Error',
            'message' => 'Амжилтгүй боллоо',
        );

        $balanceDate = $endBalanceDate =  Date::format('Y-m-d', $params['balanceDate']);

        $endTime = isset($params['change_outtime']) ? $params['change_outtime'] : (($params['outTime'] !== '-') ? $params['outTime'] : '');
        $startTime = isset($params['change_intime']) ? $params['change_intime'] : (($params['inTime'] !== '-') ? $params['inTime'] : '');
        $planDetail = '';

        $earlyTime = $params['earlyTime']; 
        $lateTime = $params['lateTime']; 
        $clearTime = $params['clearTime']; 
        $diffTime = (float) $params['originalDefferenceTime'];

        $overTime = Config::getFromCache('tmsOverTime');
        $overTime = $overTime ? explode(',', $overTime) : array();            
        $isEarlyTimeToClean = Config::getFromCache('tmsIsEarlyTimeToCleanTime');
        $isEarlyTimeToClean = $isEarlyTimeToClean ? $isEarlyTimeToClean : 0;

        $params['departmentId'] = explode(',', $params['departmentId']);

        $notPlanDepartment = Config::getFromCache('tmsPlanTimeDefaultDepartment');
        $notPlanDepartment = $notPlanDepartment ? explode(',', $notPlanDepartment) : array();
        $notPlanDepartmentCheck = in_array(issetVar($params['departmentId']), $notPlanDepartment) ? true : false;

        $isLateTimeToCleantime = Config::getFromCache('tmsIsLateTimeClearTime');
        $isLateTimeToCleantime = $isLateTimeToCleantime ? $isLateTimeToCleantime : 0;            

        $planData = $this->db->GetRow("SELECT 
                                            T2.PLAN_ID,
                                            T2.PLAN_DURATION,
                                            T2.IS_LATE,
                                            ROUND(FNC_GET_TMS_PLAN_TIME(T1.PLAN_ID)) AS PLAN_TIME,
                                            (ROUND(DATEDIFF(
                                                TO_DATE(TO_CHAR(SYSDATE, 'YYYY-MM-DD')||TO_CHAR(T4.START_TIME, 'HH24:MI'), 'YYYY-MM-DD HH24:MI'),
                                                TO_DATE(TO_CHAR(SYSDATE, 'YYYY-MM-DD')||TO_CHAR(T5.END_TIME, 'HH24:MI'), 'YYYY-MM-DD HH24:MI')  + T2.PLAN_DURATION
                                            )) / 60) - FNC_GET_TMS_PLAN_TIME(T1.PLAN_ID) AS LUNCH_TIME,
                                            TO_CHAR(T4.END_TIME, 'HH24:MI') AS LUNCH_TIME_START,
                                            TO_CHAR(T5.START_TIME, 'HH24:MI') AS LUNCH_TIME_END
                                        FROM TMS_EMPLOYEE_TIME_PLAN_HDR T0
                                            INNER JOIN TMS_EMPLOYEE_TIME_PLAN_DTL T1 ON T0.ID = T1.TIME_PLAN_ID
                                            INNER JOIN TMS_TIME_PLAN T2 ON T1.PLAN_ID = T2.PLAN_ID
                                            INNER JOIN (
                                                SELECT PLAN_ID, MIN(PLAN_DETAIL_ID) AS MIN_DETAIL_ID, MAX(PLAN_DETAIL_ID) AS MAX_DETAIL_ID FROM TMS_TIME_PLAN_DETAIL 
                                                GROUP BY PLAN_ID
                                            ) T3 ON T2.PLAN_ID = T3.PLAN_ID
                                            INNER JOIN  TMS_TIME_PLAN_DETAIL T4 ON T3.MIN_DETAIL_ID = T4.PLAN_DETAIL_ID
                                            INNER JOIN  TMS_TIME_PLAN_DETAIL T5 ON T3.MAX_DETAIL_ID = T5.PLAN_DETAIL_ID
                                        WHERE TO_CHAR(T1.PLAN_DATE, 'YYYY-MM-DD') = '$balanceDate'
                                            AND T0.EMPLOYEE_ID = " . $params['employeeId']);

        $planTimeDefault = (float) Config::getFromCache('tmsPlanTimeDefault') * 60;
        $lunchTimeDefault = (float) Config::getFromCache('tmsLunchTimeDefault') * 60;

        $planTime = isset($planData['PLAN_TIME']) && !$notPlanDepartmentCheck ? (float) $planData['PLAN_TIME'] : ($planTimeDefault ? $planTimeDefault : 0);
        $lunchTime = isset($planData['LUNCH_TIME']) ? (float) $planData['LUNCH_TIME'] : ($lunchTimeDefault ? $lunchTimeDefault : 0);
        $checkTick = false;
        $addPlusDate = '1'; $addonDate = 0; $plusDate = 'T0.PLAN_DURATION';

        if (Input::postCheck('isAddonDate') && Input::isEmpty('isAddonDate') === false && Input::postCheck('addonDate')  && Input::isEmpty('addonDate') === false) {
            $addPlusDate = $plusDate = $addonDate = Input::post('addonDate');
            $endBalanceDate = Date::nextDate($endBalanceDate, $addonDate, 'Y-m-d');
        }

        /**
        *   HDR үүсэн үед дахин цагуудаа бодох шаардлага байхгүй
        */
        $dataHdrExist = $this->db->GetRow("SELECT * FROM TNA_TIME_BALANCE_HDR WHERE TIME_BALANCE_HDR_ID = " . $balanceTimeBalanceId);

        if(empty($dataHdrExist) || array_key_exists('detect_change_intime', $params) || array_key_exists('detect_change_outtime', $params)) {
            if ($endTime && $startTime) {
                $inTime1 = Date::format('H:i', $startTime);
                $outTime1 = Date::format('H:i', $endTime);

                if ($startTime < $endTime) {
                    $d1 = new DateTime($startTime);
                    $d2 = new DateTime($endTime);
                } else {
                    $d1 = new DateTime($endTime);
                    $d2 = new DateTime($startTime);
                }

                // <editor-fold defaultstate="collapsed" desc="GET PLAN">
                $qs = "SELECT 
                        T0.PLAN_ID,
                        T0.PLAN_DURATION,
                        T0.STARTTIME,
                        T0.ENDTIME,
                        T0.STARTTIME_LIMIT,
                        T0.ENDTIME_LIMIT,
                        T0.LUNCH_TIME,
                        T0.PLAN_TIME,
                        CASE WHEN 60 >= T0.EARLY_TIME AND T0.EARLY_TIME > 0 THEN T0.EARLY_TIME ELSE 0 END AS EARLY_TIME,
                        CASE WHEN 60 >= T0.LATE_TIME AND T0.LATE_TIME > 0 AND ( T0.IS_LATE IS NULL OR T0.IS_LATE = 0 ) THEN T0.LATE_TIME ELSE 0 END AS LATE_TIME,
                        CASE WHEN (T0.ENDTIME > '$inTime1' AND T0.STARTTIME < '$outTime1') OR (T0.PLAN_DURATION > 0) THEN
                        (ROUND(DATEDIFF(
                            CASE
                            WHEN T0.STARTTIME < '$inTime1' AND ( T0.IS_LATE IS NULL OR T0.IS_LATE = 0 ) THEN
                                TO_DATE(TO_CHAR(SYSDATE, 'YYYY-MM-DD')||'$inTime1' , 'YYYY-MM-DD HH24:MI')
                            ELSE
                                TO_DATE(TO_CHAR(SYSDATE, 'YYYY-MM-DD')||T0.STARTTIME , 'YYYY-MM-DD HH24:MI')
                            END,
                            CASE WHEN T0.ENDTIME > '$outTime1' THEN
                                CASE WHEN $addonDate != 0 
                                    THEN TO_DATE(TO_CHAR(SYSDATE - $addonDate, 'YYYY-MM-DD')||T0.ENDTIME , 'YYYY-MM-DD HH24:MI') 
                                    ELSE 
                                    CASE WHEN TO_DATE(TO_CHAR(SYSDATE, 'YYYY-MM-DD')||'$outTime1' , 'YYYY-MM-DD HH24:MI') >= TO_DATE(TO_CHAR(SYSDATE, 'YYYY-MM-DD')||T0.STARTTIME_ENDTIME, 'YYYY-MM-DD HH24:MI') AND TO_DATE(TO_CHAR(SYSDATE, 'YYYY-MM-DD')||'$outTime1' , 'YYYY-MM-DD HH24:MI') <= TO_DATE(TO_CHAR(SYSDATE, 'YYYY-MM-DD')||T0.ENDTIME_STARTTIME, 'YYYY-MM-DD HH24:MI') 
                                    THEN 
                                        TO_DATE(TO_CHAR(SYSDATE, 'YYYY-MM-DD')||T0.STARTTIME_ENDTIME, 'YYYY-MM-DD HH24:MI') 
                                    ELSE 
                                        TO_DATE(TO_CHAR(SYSDATE, 'YYYY-MM-DD')||T0.ENDTIME , 'YYYY-MM-DD HH24:MI') END END  
                            ELSE
                                TO_DATE(TO_CHAR(SYSDATE, 'YYYY-MM-DD')||T0.ENDTIME , 'YYYY-MM-DD HH24:MI')
                            END
                            + $plusDate
                        ))/60)
                        + CASE WHEN 60 >= T0.EARLY_TIME AND T0.EARLY_TIME > 0 THEN T0.EARLY_TIME ELSE 0 END
                        + CASE WHEN 60 >= T0.LATE_TIME AND T0.LATE_TIME > 0 AND ( T0.IS_LATE IS NULL OR T0.IS_LATE = 0 ) THEN T0.LATE_TIME ELSE 0 END
                        - (CASE WHEN 
                            (CASE WHEN T0.STARTTIME < '$inTime1' AND ( T0.IS_LATE IS NULL OR T0.IS_LATE = 0 ) THEN
                                    TO_DATE(TO_CHAR(SYSDATE, 'YYYY-MM-DD')||'$inTime1' , 'YYYY-MM-DD HH24:MI')
                                ELSE
                                    TO_DATE(TO_CHAR(SYSDATE, 'YYYY-MM-DD')||T0.STARTTIME , 'YYYY-MM-DD HH24:MI')
                                END >= TO_DATE(TO_CHAR(SYSDATE, 'YYYY-MM-DD')||T0.STARTTIME_ENDTIME, 'YYYY-MM-DD HH24:MI') 
                                AND  CASE
                                    WHEN T0.STARTTIME < '$inTime1' AND ( T0.IS_LATE IS NULL OR T0.IS_LATE = 0 ) THEN
                                        TO_DATE(TO_CHAR(SYSDATE, 'YYYY-MM-DD')||'$inTime1' , 'YYYY-MM-DD HH24:MI')
                                    ELSE
                                        TO_DATE(TO_CHAR(SYSDATE, 'YYYY-MM-DD')||T0.STARTTIME , 'YYYY-MM-DD HH24:MI')
                                    END <= TO_DATE(TO_CHAR(SYSDATE, 'YYYY-MM-DD')||T0.ENDTIME_STARTTIME, 'YYYY-MM-DD HH24:MI'))
                            OR (
                                CASE
                                WHEN T0.ENDTIME > '$outTime1' THEN
                                    CASE WHEN $addonDate != 0 
                                        THEN TO_DATE(TO_CHAR(SYSDATE - $addonDate, 'YYYY-MM-DD')||T0.ENDTIME , 'YYYY-MM-DD HH24:MI') 
                                        ELSE TO_DATE(TO_CHAR(SYSDATE, 'YYYY-MM-DD')||'$outTime1' , 'YYYY-MM-DD HH24:MI')   END  
                                ELSE
                                    TO_DATE(TO_CHAR(SYSDATE, 'YYYY-MM-DD')||T0.ENDTIME , 'YYYY-MM-DD HH24:MI')
                                END >= TO_DATE(TO_CHAR(SYSDATE, 'YYYY-MM-DD')||T0.STARTTIME_ENDTIME, 'YYYY-MM-DD HH24:MI') 
                                AND 
                                CASE
                                WHEN T0.ENDTIME > '$outTime1' THEN
                                    CASE WHEN $addonDate != 0 
                                        THEN TO_DATE(TO_CHAR(SYSDATE - $addonDate, 'YYYY-MM-DD')||T0.ENDTIME , 'YYYY-MM-DD HH24:MI') 
                                        ELSE TO_DATE(TO_CHAR(SYSDATE, 'YYYY-MM-DD')||'$outTime1' , 'YYYY-MM-DD HH24:MI')   END  
                                ELSE
                                    TO_DATE(TO_CHAR(SYSDATE, 'YYYY-MM-DD')||T0.ENDTIME , 'YYYY-MM-DD HH24:MI')
                                END <= TO_DATE(TO_CHAR(SYSDATE, 'YYYY-MM-DD')||T0.ENDTIME_STARTTIME, 'YYYY-MM-DD HH24:MI')                                                            
                            )
                            OR (
                                CASE
                                WHEN T0.ENDTIME > '$outTime1' THEN
                                    CASE WHEN $addonDate != 0 
                                        THEN TO_DATE(TO_CHAR(SYSDATE - $addonDate, 'YYYY-MM-DD')||T0.ENDTIME , 'YYYY-MM-DD HH24:MI') 
                                        ELSE TO_DATE(TO_CHAR(SYSDATE, 'YYYY-MM-DD')||'$outTime1' , 'YYYY-MM-DD HH24:MI')   END  
                                ELSE
                                    TO_DATE(TO_CHAR(SYSDATE, 'YYYY-MM-DD')||T0.ENDTIME , 'YYYY-MM-DD HH24:MI')
                                END <= TO_DATE(TO_CHAR(SYSDATE, 'YYYY-MM-DD')||T0.ENDTIME_STARTTIME, 'YYYY-MM-DD HH24:MI')
                                AND CASE
                                WHEN T0.STARTTIME < '$inTime1' AND ( T0.IS_LATE IS NULL OR T0.IS_LATE = 0 ) THEN
                                    TO_DATE(TO_CHAR(SYSDATE, 'YYYY-MM-DD')||'$inTime1' , 'YYYY-MM-DD HH24:MI')
                                ELSE
                                    TO_DATE(TO_CHAR(SYSDATE, 'YYYY-MM-DD')||T0.STARTTIME , 'YYYY-MM-DD HH24:MI')
                                END >= TO_DATE(TO_CHAR(SYSDATE, 'YYYY-MM-DD')||T0.STARTTIME_ENDTIME, 'YYYY-MM-DD HH24:MI')
                            )
                            OR (
                                CASE
                                WHEN T0.STARTTIME < '$inTime1' AND ( T0.IS_LATE IS NULL OR T0.IS_LATE = 0 ) THEN
                                    TO_DATE(TO_CHAR(SYSDATE, 'YYYY-MM-DD')||'$inTime1' , 'YYYY-MM-DD HH24:MI')
                                ELSE
                                    TO_DATE(TO_CHAR(SYSDATE, 'YYYY-MM-DD')||T0.STARTTIME , 'YYYY-MM-DD HH24:MI')
                                END >= TO_DATE(TO_CHAR(SYSDATE, 'YYYY-MM-DD')||T0.ENDTIME_STARTTIME, 'YYYY-MM-DD HH24:MI')
                            )
                            OR (
                                CASE
                            WHEN T0.ENDTIME > '$outTime1' THEN
                                CASE WHEN $addonDate != 0 
                                    THEN TO_DATE(TO_CHAR(SYSDATE - $addonDate, 'YYYY-MM-DD')||T0.ENDTIME , 'YYYY-MM-DD HH24:MI') 
                                    ELSE TO_DATE(TO_CHAR(SYSDATE, 'YYYY-MM-DD')||'$outTime1' , 'YYYY-MM-DD HH24:MI')   END  
                            ELSE
                                TO_DATE(TO_CHAR(SYSDATE, 'YYYY-MM-DD')||T0.ENDTIME , 'YYYY-MM-DD HH24:MI')
                            END <= TO_DATE(TO_CHAR(SYSDATE, 'YYYY-MM-DD')||T0.STARTTIME_ENDTIME, 'YYYY-MM-DD HH24:MI')
                            )
                        THEN 0 ELSE (CASE WHEN LUNCH_TIME > 0 THEN LUNCH_TIME ELSE 0 END) END)
                        ELSE T0.PLAN_TIME END
                        AS DEFFERENCE_TIME,
                        CASE WHEN (T0.ENDTIME > '$inTime1' AND T0.STARTTIME < '$outTime1') OR T0.PLAN_DURATION > 0 OR $addonDate != 0 THEN
                            (ROUND(DATEDIFF(
                                    CASE
                                    WHEN $addonDate = 0 AND $plusDate != 0 THEN
                                        CASE
                                        WHEN TO_DATE(TO_CHAR(SYSDATE, 'YYYY-MM-DD')||T0.STARTTIME, 'YYYY-MM-DD HH24:MI') >= TO_DATE(TO_CHAR(SYSDATE, 'YYYY-MM-DD')||'$inTime1', 'YYYY-MM-DD HH24:MI') AND '05:00' <= '$inTime1' THEN
                                            TO_DATE(TO_CHAR(SYSDATE, 'YYYY-MM-DD')||T0.STARTTIME, 'YYYY-MM-DD HH24:MI')                                            
                                        WHEN T0.ENDTIME_STARTTIME >= '$inTime1' AND T0.STARTTIME_ENDTIME <= '$inTime1' THEN
                                            TO_DATE(TO_CHAR(SYSDATE, 'YYYY-MM-DD')||T0.ENDTIME_STARTTIME, 'YYYY-MM-DD HH24:MI')
                                        ELSE
                                            TO_DATE(TO_CHAR(SYSDATE, 'YYYY-MM-DD')||'$inTime1' , 'YYYY-MM-DD HH24:MI')
                                        END
                                    ELSE
                                    CASE
                                        WHEN CASE WHEN T0.STARTTIME < T0.STARTTIME_LIMIT AND T0.STARTTIME_LIMIT IS NOT NULL THEN T0.STARTTIME_LIMIT ELSE T0.STARTTIME END < '$inTime1' AND ( T0.IS_LATE IS NULL OR T0.IS_LATE = 0 ) THEN
                                            CASE
                                            WHEN T0.ENDTIME_STARTTIME >= '$inTime1' AND T0.STARTTIME_ENDTIME <= '$inTime1' THEN
                                                TO_DATE(TO_CHAR(SYSDATE, 'YYYY-MM-DD')||T0.ENDTIME_STARTTIME, 'YYYY-MM-DD HH24:MI')
                                            ELSE
                                                TO_DATE(TO_CHAR(SYSDATE, 'YYYY-MM-DD')||'$inTime1' , 'YYYY-MM-DD HH24:MI')
                                            END
                                        ELSE
                                            TO_DATE(TO_CHAR(SYSDATE, 'YYYY-MM-DD')||T0.STARTTIME , 'YYYY-MM-DD HH24:MI')
                                        END
                                    END,
                                    CASE WHEN $addonDate != 0 OR $plusDate != 0 THEN
                                        CASE
                                        WHEN CASE WHEN T0.ENDTIME > T0.ENDTIME_LIMIT AND T0.ENDTIME_LIMIT IS NOT NULL THEN TO_DATE(TO_CHAR(SYSDATE + T0.PLAN_DURATION, 'YYYY-MM-DD')||T0.ENDTIME_LIMIT, 'YYYY-MM-DD HH24:MI') ELSE TO_DATE(TO_CHAR(SYSDATE + T0.PLAN_DURATION, 'YYYY-MM-DD')||T0.ENDTIME, 'YYYY-MM-DD HH24:MI') END > TO_DATE(TO_CHAR(SYSDATE + $addonDate, 'YYYY-MM-DD')||'$outTime1', 'YYYY-MM-DD HH24:MI') AND $addonDate = 0 THEN
                                            TO_DATE(TO_CHAR(SYSDATE + $addonDate, 'YYYY-MM-DD')||'$outTime1', 'YYYY-MM-DD HH24:MI') 
                                        ELSE
                                            CASE
                                            WHEN CASE WHEN T0.ENDTIME > T0.ENDTIME_LIMIT AND T0.ENDTIME_LIMIT IS NOT NULL THEN T0.ENDTIME_LIMIT ELSE T0.ENDTIME END > '$outTime1' AND T0.ENDTIME_STARTTIME >= '$outTime1' AND T0.STARTTIME_ENDTIME <= '$outTime1' THEN                                        
                                                TO_DATE(TO_CHAR(SYSDATE + $addonDate, 'YYYY-MM-DD')||T0.STARTTIME_ENDTIME, 'YYYY-MM-DD HH24:MI')
                                            WHEN CASE WHEN T0.ENDTIME > T0.ENDTIME_LIMIT AND T0.ENDTIME_LIMIT IS NOT NULL THEN TO_DATE(TO_CHAR(SYSDATE + T0.PLAN_DURATION, 'YYYY-MM-DD')||T0.ENDTIME_LIMIT, 'YYYY-MM-DD HH24:MI') ELSE TO_DATE(TO_CHAR(SYSDATE + T0.PLAN_DURATION, 'YYYY-MM-DD')||T0.ENDTIME, 'YYYY-MM-DD HH24:MI') END > TO_DATE(TO_CHAR(SYSDATE + $addonDate, 'YYYY-MM-DD')||'$outTime1', 'YYYY-MM-DD HH24:MI') THEN
                                                TO_DATE(TO_CHAR(SYSDATE + $addonDate, 'YYYY-MM-DD')||'$outTime1', 'YYYY-MM-DD HH24:MI')
                                            ELSE
                                                TO_DATE(TO_CHAR(SYSDATE + T0.PLAN_DURATION, 'YYYY-MM-DD')||T0.ENDTIME, 'YYYY-MM-DD HH24:MI')
                                            END
                                        END
                                    ELSE
                                        CASE
                                        WHEN CASE WHEN T0.ENDTIME > T0.ENDTIME_LIMIT AND T0.ENDTIME_LIMIT IS NOT NULL THEN T0.ENDTIME_LIMIT ELSE T0.ENDTIME END > '$outTime1' THEN
                                            CASE WHEN TO_DATE(TO_CHAR(SYSDATE, 'YYYY-MM-DD')||'$outTime1' , 'YYYY-MM-DD HH24:MI') >= TO_DATE(TO_CHAR(SYSDATE, 'YYYY-MM-DD')||T0.STARTTIME_ENDTIME, 'YYYY-MM-DD HH24:MI') AND TO_DATE(TO_CHAR(SYSDATE, 'YYYY-MM-DD')||'$outTime1' , 'YYYY-MM-DD HH24:MI') <= TO_DATE(TO_CHAR(SYSDATE, 'YYYY-MM-DD')||T0.ENDTIME_STARTTIME, 'YYYY-MM-DD HH24:MI') 
                                            THEN TO_DATE(TO_CHAR(SYSDATE, 'YYYY-MM-DD')||T0.STARTTIME_ENDTIME, 'YYYY-MM-DD HH24:MI') ELSE TO_DATE(TO_CHAR(SYSDATE, 'YYYY-MM-DD')||'$outTime1' , 'YYYY-MM-DD HH24:MI') END
                                        ELSE
                                            TO_DATE(TO_CHAR(SYSDATE, 'YYYY-MM-DD')||T0.ENDTIME, 'YYYY-MM-DD HH24:MI') END
                                    END                                        
                                ))/60)
                            + CASE WHEN $isEarlyTimeToClean = 0 THEN
                              CASE WHEN 60 >= T0.EARLY_TIME AND T0.EARLY_TIME > 0 THEN T0.EARLY_TIME ELSE 0 END
                            ELSE
                              0
                            END
                            + CASE WHEN ".$isLateTimeToCleantime." = '1' THEN
                              T0.LATE_TIME
                            ELSE
                              0
                            END
                            - (CASE WHEN 
                                (CASE WHEN T0.STARTTIME < '$inTime1' AND ( T0.IS_LATE IS NULL OR T0.IS_LATE = 0 ) THEN
                                        TO_DATE(TO_CHAR(SYSDATE, 'YYYY-MM-DD')||'$inTime1' , 'YYYY-MM-DD HH24:MI')
                                    ELSE
                                        TO_DATE(TO_CHAR(SYSDATE, 'YYYY-MM-DD')||T0.STARTTIME , 'YYYY-MM-DD HH24:MI')
                                    END >= TO_DATE(TO_CHAR(SYSDATE, 'YYYY-MM-DD')||T0.STARTTIME_ENDTIME, 'YYYY-MM-DD HH24:MI') 
                                    AND  CASE
                                        WHEN T0.STARTTIME < '$inTime1' AND ( T0.IS_LATE IS NULL OR T0.IS_LATE = 0 ) THEN
                                            TO_DATE(TO_CHAR(SYSDATE, 'YYYY-MM-DD')||'$inTime1' , 'YYYY-MM-DD HH24:MI')
                                        ELSE
                                            TO_DATE(TO_CHAR(SYSDATE, 'YYYY-MM-DD')||T0.STARTTIME , 'YYYY-MM-DD HH24:MI')
                                        END <= TO_DATE(TO_CHAR(SYSDATE, 'YYYY-MM-DD')||T0.ENDTIME_STARTTIME, 'YYYY-MM-DD HH24:MI'))
                                OR (
                                    CASE
                                    WHEN T0.ENDTIME > '$outTime1' THEN
                                        CASE WHEN $addonDate != 0 
                                            THEN TO_DATE(TO_CHAR(SYSDATE - $addonDate, 'YYYY-MM-DD')||T0.ENDTIME , 'YYYY-MM-DD HH24:MI') 
                                            ELSE TO_DATE(TO_CHAR(SYSDATE, 'YYYY-MM-DD')||'$outTime1' , 'YYYY-MM-DD HH24:MI')   END  
                                    ELSE
                                        TO_DATE(TO_CHAR(SYSDATE, 'YYYY-MM-DD')||T0.ENDTIME , 'YYYY-MM-DD HH24:MI')
                                    END >= TO_DATE(TO_CHAR(SYSDATE, 'YYYY-MM-DD')||T0.STARTTIME_ENDTIME, 'YYYY-MM-DD HH24:MI') 
                                    AND 
                                    CASE
                                    WHEN T0.ENDTIME > '$outTime1' THEN
                                        CASE WHEN $addonDate != 0 
                                            THEN TO_DATE(TO_CHAR(SYSDATE - $addonDate, 'YYYY-MM-DD')||T0.ENDTIME , 'YYYY-MM-DD HH24:MI') 
                                            ELSE TO_DATE(TO_CHAR(SYSDATE, 'YYYY-MM-DD')||'$outTime1' , 'YYYY-MM-DD HH24:MI')   END  
                                    ELSE
                                        TO_DATE(TO_CHAR(SYSDATE, 'YYYY-MM-DD')||T0.ENDTIME , 'YYYY-MM-DD HH24:MI')
                                    END <= TO_DATE(TO_CHAR(SYSDATE, 'YYYY-MM-DD')||T0.ENDTIME_STARTTIME, 'YYYY-MM-DD HH24:MI')                                                            
                                )
                                OR (
                                    CASE
                                    WHEN T0.ENDTIME > '$outTime1' THEN
                                        CASE WHEN $addonDate != 0 
                                            THEN TO_DATE(TO_CHAR(SYSDATE - $addonDate, 'YYYY-MM-DD')||T0.ENDTIME , 'YYYY-MM-DD HH24:MI') 
                                            ELSE TO_DATE(TO_CHAR(SYSDATE, 'YYYY-MM-DD')||'$outTime1' , 'YYYY-MM-DD HH24:MI')   END  
                                    ELSE
                                        TO_DATE(TO_CHAR(SYSDATE, 'YYYY-MM-DD')||T0.ENDTIME , 'YYYY-MM-DD HH24:MI')
                                    END <= TO_DATE(TO_CHAR(SYSDATE, 'YYYY-MM-DD')||T0.ENDTIME_STARTTIME, 'YYYY-MM-DD HH24:MI')
                                    AND CASE
                                    WHEN T0.STARTTIME < '$inTime1' AND ( T0.IS_LATE IS NULL OR T0.IS_LATE = 0 ) THEN
                                        TO_DATE(TO_CHAR(SYSDATE, 'YYYY-MM-DD')||'$inTime1' , 'YYYY-MM-DD HH24:MI')
                                    ELSE
                                        TO_DATE(TO_CHAR(SYSDATE, 'YYYY-MM-DD')||T0.STARTTIME , 'YYYY-MM-DD HH24:MI')
                                    END >= TO_DATE(TO_CHAR(SYSDATE, 'YYYY-MM-DD')||T0.STARTTIME_ENDTIME, 'YYYY-MM-DD HH24:MI')
                                )
                                OR (
                                    CASE
                                    WHEN T0.STARTTIME < '$inTime1' AND ( T0.IS_LATE IS NULL OR T0.IS_LATE = 0 ) THEN
                                        TO_DATE(TO_CHAR(SYSDATE, 'YYYY-MM-DD')||'$inTime1' , 'YYYY-MM-DD HH24:MI')
                                    ELSE
                                        TO_DATE(TO_CHAR(SYSDATE, 'YYYY-MM-DD')||T0.STARTTIME , 'YYYY-MM-DD HH24:MI')
                                    END >= TO_DATE(TO_CHAR(SYSDATE, 'YYYY-MM-DD')||T0.ENDTIME_STARTTIME, 'YYYY-MM-DD HH24:MI')
                                )
                                OR (
                                    CASE
                                WHEN T0.ENDTIME > '$outTime1' THEN
                                    CASE WHEN $addonDate != 0 
                                        THEN TO_DATE(TO_CHAR(SYSDATE - $addonDate, 'YYYY-MM-DD')||T0.ENDTIME, 'YYYY-MM-DD HH24:MI') 
                                        ELSE TO_DATE(TO_CHAR(SYSDATE, 'YYYY-MM-DD')||'$outTime1' , 'YYYY-MM-DD HH24:MI')   END  
                                ELSE
                                    TO_DATE(TO_CHAR(SYSDATE, 'YYYY-MM-DD')||T0.ENDTIME , 'YYYY-MM-DD HH24:MI')
                                END <= TO_DATE(TO_CHAR(SYSDATE, 'YYYY-MM-DD')||T0.STARTTIME_ENDTIME, 'YYYY-MM-DD HH24:MI')
                                )
                            THEN (CASE WHEN $addonDate != 0 AND TO_DATE(TO_CHAR(SYSDATE, 'YYYY-MM-DD')||T0.ENDTIME_STARTTIME, 'YYYY-MM-DD HH24:MI') < TO_DATE(TO_CHAR(SYSDATE + $addonDate, 'YYYY-MM-DD')||'$outTime1', 'YYYY-MM-DD HH24:MI') THEN LUNCH_TIME ELSE 0 END) ELSE (CASE WHEN LUNCH_TIME > 0 THEN LUNCH_TIME ELSE 0 END) END)
                        ELSE 0 END AS CLEAR_TIME,
                        CASE WHEN (T0.ENDTIME > '$inTime1' AND T0.STARTTIME < '$outTime1') OR T0.PLAN_DURATION > 0 OR $addonDate != 0 THEN
                            (ROUND(DATEDIFF(
                                TO_DATE(TO_CHAR(SYSDATE, 'YYYY-MM-DD')||'$inTime1' , 'YYYY-MM-DD HH24:MI'),
                                TO_DATE(TO_CHAR(SYSDATE, 'YYYY-MM-DD')||'$outTime1' , 'YYYY-MM-DD HH24:MI')
                                + CASE WHEN $addonDate != 0 THEN $addonDate ELSE 0 END                                    
                            ))/60)
                            + CASE WHEN 60 >= T0.EARLY_TIME AND T0.EARLY_TIME > 0 THEN T0.EARLY_TIME ELSE 0 END
                            - (CASE WHEN 
                                (CASE WHEN T0.STARTTIME < '$inTime1' AND ( T0.IS_LATE IS NULL OR T0.IS_LATE = 0 ) THEN
                                        TO_DATE(TO_CHAR(SYSDATE, 'YYYY-MM-DD')||'$inTime1' , 'YYYY-MM-DD HH24:MI')
                                    ELSE
                                        TO_DATE(TO_CHAR(SYSDATE, 'YYYY-MM-DD')||T0.STARTTIME , 'YYYY-MM-DD HH24:MI')
                                    END >= TO_DATE(TO_CHAR(SYSDATE, 'YYYY-MM-DD')||T0.STARTTIME_ENDTIME, 'YYYY-MM-DD HH24:MI') 
                                    AND  CASE
                                        WHEN T0.STARTTIME < '$inTime1' AND ( T0.IS_LATE IS NULL OR T0.IS_LATE = 0 ) THEN
                                            TO_DATE(TO_CHAR(SYSDATE, 'YYYY-MM-DD')||'$inTime1' , 'YYYY-MM-DD HH24:MI')
                                        ELSE
                                            TO_DATE(TO_CHAR(SYSDATE, 'YYYY-MM-DD')||T0.STARTTIME , 'YYYY-MM-DD HH24:MI')
                                        END <= TO_DATE(TO_CHAR(SYSDATE, 'YYYY-MM-DD')||T0.ENDTIME_STARTTIME, 'YYYY-MM-DD HH24:MI'))
                                OR (
                                    CASE
                                    WHEN T0.ENDTIME > '$outTime1' THEN
                                        CASE WHEN $addonDate != 0 
                                            THEN TO_DATE(TO_CHAR(SYSDATE - $addonDate, 'YYYY-MM-DD')||T0.ENDTIME , 'YYYY-MM-DD HH24:MI') 
                                            ELSE TO_DATE(TO_CHAR(SYSDATE, 'YYYY-MM-DD')||'$outTime1' , 'YYYY-MM-DD HH24:MI')   END  
                                    ELSE
                                        TO_DATE(TO_CHAR(SYSDATE, 'YYYY-MM-DD')||T0.ENDTIME , 'YYYY-MM-DD HH24:MI')
                                    END >= TO_DATE(TO_CHAR(SYSDATE, 'YYYY-MM-DD')||T0.STARTTIME_ENDTIME, 'YYYY-MM-DD HH24:MI') 
                                    AND 
                                    CASE
                                    WHEN T0.ENDTIME > '$outTime1' THEN
                                        CASE WHEN $addonDate != 0 
                                            THEN TO_DATE(TO_CHAR(SYSDATE - $addonDate, 'YYYY-MM-DD')||T0.ENDTIME , 'YYYY-MM-DD HH24:MI') 
                                            ELSE TO_DATE(TO_CHAR(SYSDATE, 'YYYY-MM-DD')||'$outTime1' , 'YYYY-MM-DD HH24:MI')   END  
                                    ELSE
                                        TO_DATE(TO_CHAR(SYSDATE, 'YYYY-MM-DD')||T0.ENDTIME , 'YYYY-MM-DD HH24:MI')
                                    END <= TO_DATE(TO_CHAR(SYSDATE, 'YYYY-MM-DD')||T0.ENDTIME_STARTTIME, 'YYYY-MM-DD HH24:MI')                                                            
                                )
                                OR (
                                    CASE
                                    WHEN T0.ENDTIME > '$outTime1' THEN
                                        CASE WHEN $addonDate != 0 
                                            THEN TO_DATE(TO_CHAR(SYSDATE - $addonDate, 'YYYY-MM-DD')||T0.ENDTIME , 'YYYY-MM-DD HH24:MI') 
                                            ELSE TO_DATE(TO_CHAR(SYSDATE, 'YYYY-MM-DD')||'$outTime1' , 'YYYY-MM-DD HH24:MI')   END  
                                    ELSE
                                        TO_DATE(TO_CHAR(SYSDATE, 'YYYY-MM-DD')||T0.ENDTIME , 'YYYY-MM-DD HH24:MI')
                                    END <= TO_DATE(TO_CHAR(SYSDATE, 'YYYY-MM-DD')||T0.ENDTIME_STARTTIME, 'YYYY-MM-DD HH24:MI')
                                    AND CASE
                                    WHEN T0.STARTTIME < '$inTime1' AND ( T0.IS_LATE IS NULL OR T0.IS_LATE = 0 ) THEN
                                        TO_DATE(TO_CHAR(SYSDATE, 'YYYY-MM-DD')||'$inTime1' , 'YYYY-MM-DD HH24:MI')
                                    ELSE
                                        TO_DATE(TO_CHAR(SYSDATE, 'YYYY-MM-DD')||T0.STARTTIME , 'YYYY-MM-DD HH24:MI')
                                    END >= TO_DATE(TO_CHAR(SYSDATE, 'YYYY-MM-DD')||T0.STARTTIME_ENDTIME, 'YYYY-MM-DD HH24:MI')
                                )
                                OR (
                                    CASE
                                    WHEN T0.STARTTIME < '$inTime1' AND ( T0.IS_LATE IS NULL OR T0.IS_LATE = 0 ) THEN
                                        TO_DATE(TO_CHAR(SYSDATE, 'YYYY-MM-DD')||'$inTime1' , 'YYYY-MM-DD HH24:MI')
                                    ELSE
                                        TO_DATE(TO_CHAR(SYSDATE, 'YYYY-MM-DD')||T0.STARTTIME , 'YYYY-MM-DD HH24:MI')
                                    END >= TO_DATE(TO_CHAR(SYSDATE, 'YYYY-MM-DD')||T0.ENDTIME_STARTTIME, 'YYYY-MM-DD HH24:MI')
                                )
                                OR (
                                    CASE
                                WHEN T0.ENDTIME > '$outTime1' THEN
                                    CASE WHEN $addonDate != 0 
                                        THEN TO_DATE(TO_CHAR(SYSDATE - $addonDate, 'YYYY-MM-DD')||T0.ENDTIME , 'YYYY-MM-DD HH24:MI') 
                                        ELSE TO_DATE(TO_CHAR(SYSDATE, 'YYYY-MM-DD')||'$outTime1' , 'YYYY-MM-DD HH24:MI')   END  
                                ELSE
                                    TO_DATE(TO_CHAR(SYSDATE, 'YYYY-MM-DD')||T0.ENDTIME , 'YYYY-MM-DD HH24:MI')
                                END <= TO_DATE(TO_CHAR(SYSDATE, 'YYYY-MM-DD')||T0.STARTTIME_ENDTIME, 'YYYY-MM-DD HH24:MI')
                                )
                            THEN (CASE WHEN $addonDate != 0 THEN LUNCH_TIME ELSE 0 END) ELSE (CASE WHEN LUNCH_TIME > 0 THEN LUNCH_TIME ELSE 0 END) END)
                        ELSE 0 END AS UNCLEAR_TIME
                    FROM ( 
                        SELECT T0.*,
                            CASE WHEN T0.STARTTIME_LIMIT < '$inTime1' AND T0.STARTTIME_LIMIT IS NOT NULL 
                                THEN ROUND(DATEDIFF(
                                        TO_DATE(TO_CHAR(SYSDATE , 'YYYY-MM-DD')||T0.STARTTIME_LIMIT, 'YYYY-MM-DD HH24:MI'),
                                        TO_DATE(TO_CHAR(SYSDATE, 'YYYY-MM-DD')||'$inTime1', 'YYYY-MM-DD HH24:MI')
                                    ))/60
                                WHEN T0.STARTTIME < '$inTime1' AND T0.STARTTIME_LIMIT IS NULL 
                                THEN ROUND(DATEDIFF(
                                        TO_DATE(TO_CHAR(SYSDATE , 'YYYY-MM-DD')||T0.STARTTIME, 'YYYY-MM-DD HH24:MI'),
                                        TO_DATE(TO_CHAR(SYSDATE, 'YYYY-MM-DD')||'$inTime1', 'YYYY-MM-DD HH24:MI')
                                    ))/60
                                ELSE 0 END AS LATE_TIME,
                            CASE WHEN T0.ENDTIME_LIMIT > '$outTime1' AND T0.ENDTIME_LIMIT IS NOT NULL 
                                THEN ROUND(DATEDIFF(
                                        TO_DATE(TO_CHAR(SYSDATE, 'YYYY-MM-DD')||'$outTime1', 'YYYY-MM-DD HH24:MI'),
                                        TO_DATE(TO_CHAR(SYSDATE , 'YYYY-MM-DD')||T0.ENDTIME_LIMIT , 'YYYY-MM-DD HH24:MI')
                                    ))/60
                                WHEN T0.ENDTIME > '$outTime1' AND T0.ENDTIME_LIMIT IS NULL 
                                THEN ROUND(DATEDIFF(
                                        TO_DATE(TO_CHAR(SYSDATE, 'YYYY-MM-DD')||'$outTime1', 'YYYY-MM-DD HH24:MI'),
                                        TO_DATE(TO_CHAR(SYSDATE , 'YYYY-MM-DD')||T0.ENDTIME, 'YYYY-MM-DD HH24:MI')
                                    ))/60                                    
                                ELSE 0 END AS EARLY_TIME,
                            0 AS NIGHT_TIME
                        FROM (
                                SELECT 
                                    T2.PLAN_ID,
                                    T2.PLAN_DURATION,
                                    T2.IS_LATE,
                                    TO_CHAR(T4.START_TIME, 'HH24:MI') AS STARTTIME,
                                    TO_CHAR(T4.END_TIME, 'HH24:MI') AS STARTTIME_ENDTIME,
                                    TO_CHAR(T5.END_TIME, 'HH24:MI') AS ENDTIME,
                                    TO_CHAR(T5.START_TIME, 'HH24:MI') AS ENDTIME_STARTTIME,
                                    TO_CHAR(T4.STARTTIME_LIMIT, 'HH24:MI') AS STARTTIME_LIMIT,
                                    TO_CHAR(T5.ENDTIME_LIMIT, 'HH24:MI') AS ENDTIME_LIMIT,
                                    (ROUND(DATEDIFF(
                                        TO_DATE(TO_CHAR(SYSDATE, 'YYYY-MM-DD')||TO_CHAR(T4.START_TIME, 'HH24:MI'), 'YYYY-MM-DD HH24:MI'),
                                        TO_DATE(TO_CHAR(SYSDATE, 'YYYY-MM-DD')||TO_CHAR(T5.END_TIME, 'HH24:MI'), 'YYYY-MM-DD HH24:MI')  + T2.PLAN_DURATION
                                    )) / 60) - FNC_GET_TMS_PLAN_TIME(T1.PLAN_ID) AS LUNCH_TIME,
                                    ROUND(FNC_GET_TMS_PLAN_TIME(T1.PLAN_ID)/60, 2) AS PLAN_TIME
                                FROM TMS_EMPLOYEE_TIME_PLAN_HDR T0
                                    INNER JOIN TMS_EMPLOYEE_TIME_PLAN_DTL T1 ON T0.ID = T1.TIME_PLAN_ID
                                    INNER JOIN TMS_TIME_PLAN T2 ON T1.PLAN_ID = T2.PLAN_ID
                                    INNER JOIN (
                                        SELECT PLAN_ID, MIN(PLAN_DETAIL_ID) AS MIN_DETAIL_ID, MAX(PLAN_DETAIL_ID) AS MAX_DETAIL_ID FROM TMS_TIME_PLAN_DETAIL 
                                        GROUP BY PLAN_ID
                                    ) T3 ON T2.PLAN_ID = T3.PLAN_ID
                                    INNER JOIN  TMS_TIME_PLAN_DETAIL T4 ON T3.MIN_DETAIL_ID = T4.PLAN_DETAIL_ID
                                    INNER JOIN  TMS_TIME_PLAN_DETAIL T5 ON T3.MAX_DETAIL_ID = T5.PLAN_DETAIL_ID
                                WHERE TO_CHAR(T1.PLAN_DATE, 'YYYY-MM-DD') = '$balanceDate'
                                    AND T0.EMPLOYEE_ID = '". $params['employeeId'] ."'
                        ) T0
                    ) T0";
                // </editor-fold>

                /*print('<pre>');*/
                //echo $qs; die;
                /*print('</pre>'); die;*/

                $planDetail = $this->db->GetRow($qs);

                $inStartTime = $balanceDate . ' ' . $startTime . ':00';
                $outEndTime = $balanceDate . ' ' . $endTime . ':00';
                $cause4 = 0;

                if ($nightTimeRange) {
                    $nightTimeRange = explode('-', $nightTimeRange);
                    $nightStartDate = $nightTimeRange[0];
                    $nightEndDate = $nightTimeRange[1];                
                }

                if ($planDetail && !$notPlanDepartmentCheck) {
                    $earlyTime = $planDetail['EARLY_TIME']; 
                    $lateTime = $planDetail['LATE_TIME']; 
                    $clearTime = $planDetail['CLEAR_TIME'];
                    $unClearTime = $planDetail['UNCLEAR_TIME'];

                    $diffTime = (float) $clearTime - (float) $planTime;
                    $diffTime = $diffTime < 60 && $diffTime < $defaultDefferenceTime ? $diffTime : 0;

                    if ($nightTimeRange && $addonDate) {
                        $date1 = Date::currentDate('Y-m-d');
                        $date2 = Date::nextDate($date1, $addPlusDate, 'Y-m-d');

                        if ($startTime <= $endTime) {
                            $nightStartD = (new DateTime($nightStartDate) <= new DateTime($startTime)) ? $date1 .' ' . $startTime : $date1 .' ' . $nightStartDate;
                            $nightEndD = (new DateTime($nightEndDate) <= new DateTime($endTime)) ? $date2 .' ' . $nightEndDate : $date2 .' ' . $endTime;
                        } else {
                            $nightEndD = (new DateTime($nightStartDate) <= new DateTime($startTime)) ? $date1 .' ' . $startTime : $date1 .' ' . $nightStartDate;
                            $nightStartD = (new DateTime($nightEndDate) <= new DateTime($endTime)) ? $date2 .' ' . $nightEndDate : $date2 .' ' . $endTime;
                        }

                        $nD2 = new DateTime($nightStartD);
                        $nD1 = new DateTime($nightEndD);

                        $interval = $nD2->diff($nD1);
                        $hour = $interval->format('%h');
                        $min = $interval->format('%i');
                        $second = $interval->format('%s');
                        $lunchTime = ($addonDate) ? 0 : $lunchTime;
                        $nightTime = (($hour * 60) + $min + ($second / 60));

                    }

                } else {

                    $diffTime = 0;
                    $ucTime1 = new DateTime($startTime);
                    $ucTime2 = new DateTime($endTime);  

                    if (($nightTimeRange && new DateTime($nightStartDate) <= new DateTime($startTime))  || $addonDate) {
                        $ucTime2->modify($addPlusDate . ' day');

                        $interval = $ucTime1->diff($ucTime2);
                        $hour = $interval->format('%h');
                        $min = $interval->format('%i');
                        $second = $interval->format('%s');

                        if($hour == 0 && $addPlusDate == 0)
                            $unClearTime = $clearTime = 720;
                        elseif($hour == 0 && $addPlusDate == 1)
                            $unClearTime = $clearTime = 1440;
                        elseif($hour == 0 && $addPlusDate == 2)
                            $unClearTime = $clearTime = 2880;
                        else {
                            $unClearTime = $clearTime = (($hour * 60) + $min + ($second / 60));
                            $clearTime = $notPlanDepartmentCheck ? ($clearTime > $planTimeDefault ? $planTimeDefault : $clearTime) : $clearTime;
                        }
                    } else {
                        $interval = $ucTime1->diff($ucTime2);
                        $hour = $interval->format('%h');
                        $min = $interval->format('%i');
                        $second = $interval->format('%s');

                        $unClearTime = $clearTime = (($hour * 60) + $min + ($second / 60));
                        $clearTime = ($clearTime < $planTime) ? (float) $clearTime : $planTime;
                    }

                    if ($nightTimeRange && $addonDate) {
                        if ((new DateTime($nightStartDate) <= new DateTime($startTime) || new DateTime($startTime) >= new DateTime('18:00'))  || $addonDate) {
                            $date1 = Date::currentDate('Y-m-d');
                            $date2 = Date::nextDate($date1, $addPlusDate, 'Y-m-d');

                            if ($startTime <= $endTime) {
                                $nightStartD = (new DateTime($nightStartDate) <= new DateTime($startTime)) ? $date1 .' ' . $startTime : $date1 .' ' . $nightStartDate;
                                $nightEndD = (new DateTime($nightEndDate) <= new DateTime($endTime)) ? $date2 .' ' . $nightEndDate : $date2 .' ' . $endTime;
                            } else {
                                $nightEndD = (new DateTime($nightStartDate) <= new DateTime($startTime)) ? $date1 .' ' . $startTime : $date1 .' ' . $nightStartDate;
                                $nightStartD = (new DateTime($nightEndDate) <= new DateTime($endTime)) ? $date2 .' ' . $nightEndDate : $date2 .' ' . $endTime;
                            }

                            $nD2 = new DateTime($nightStartD);
                            $nD1 = new DateTime($nightEndD);

                            $interval = $nD2->diff($nD1);
                            $hour = $interval->format('%h');
                            $min = $interval->format('%i');
                            $second = $interval->format('%s');

                            $nightTime = (($hour * 60) + $min + ($second / 60));
                        }
                    }

                    $realClearTime = $clearTime;
                    if($realClearTime > 480) {
                        $cause4 = $realClearTime - 480 - $lunchTime;
                        $cause4 = $cause4 > 0 ? $cause4 : 0;
                    }
                }

                $data = array(
                    'TIME_BALANCE_HDR_ID' => $balanceTimeBalanceId,
                    'EMPLOYEE_ID' => $params['employeeId'],
                    'BALANCE_DATE' => $balanceDate,
                    'START_TIME' => ($startTime) ? $balanceDate. ' ' . $startTime.':00' : '',
                    'END_TIME' => ($endTime) ?  $endBalanceDate. ' ' . $endTime.':00' : '',
                    'CLEAN_TIME' => ($clearTime) ? $clearTime : $params['clearTime'],
                    'UNCLEAN_TIME' => ($unClearTime) ? $unClearTime : $params['unclearTime'],
                    'DEFFERENCE_TIME' => $params['defferenceTime'],
                    'ORIGINAL_DEFFERENCE_TIME' => $params['originalDefferenceTime'],
                    'FAULT_TYPE' => $params['faultType'],
                    'IS_CONFIRMED' => '1',
                    'NIGHT_TIME' => ($nightTime) ? $nightTime : $params['nightTime'],
                    'WFM_STATUS_ID' => $wfmStatusId,
                    'EARLY_TIME' => ($earlyTime) ? $earlyTime : (float) $params['earlyTime'],
                    'LATE_TIME' => ($lateTime) ? $lateTime :  (float) $params['lateTime'],
                    'CAUSE4' => (Config::getFromCache('CONFIG_TNA_HISHIGARVIN') && in_array(issetVar($params['departmentId']), $overTime)) ? '210' : $cause4
                );

            } else {

                $diffTime = ($endTime) ? $params['defferenceTime'] : - $planTime;
                $data = array(
                    'TIME_BALANCE_HDR_ID' => $balanceTimeBalanceId,
                    'EMPLOYEE_ID' => $params['employeeId'],
                    'BALANCE_DATE' => $balanceDate,
                    'START_TIME' => ($startTime) ? $balanceDate. ' ' . $startTime.':00' : '',
                    'END_TIME' => ($endTime) ?  $balanceDate. ' ' . $endTime.':00' : '',
                    'CLEAN_TIME' => ($clearTime) ? $clearTime : $params['clearTime'],
                    'UNCLEAN_TIME' => $params['unclearTime'],
                    'DEFFERENCE_TIME' => $diffTime,
                    'ORIGINAL_DEFFERENCE_TIME' => $params['originalDefferenceTime'],
                    'FAULT_TYPE' => $params['faultType'],
                    'IS_CONFIRMED' => '1',
                    'NIGHT_TIME' => ($nightTime) ? $nightTime : $params['nightTime'],
                    'WFM_STATUS_ID' => $wfmStatusId,
                    'EARLY_TIME' => ($earlyTime) ? $earlyTime : (float) $params['earlyTime'],
                    'LATE_TIME' => ($lateTime) ? $lateTime :  (float) $params['lateTime'],
                    'CAUSE4' => (Config::getFromCache('CONFIG_TNA_HISHIGARVIN') && in_array(issetVar($params['departmentId']), $overTime)) ? '210' : 0
                );
            }

        } else {                

            $data = array(
                'TIME_BALANCE_HDR_ID' => $balanceTimeBalanceId,
                'EMPLOYEE_ID' => $dataHdrExist['EMPLOYEE_ID'],
                'BALANCE_DATE' => $dataHdrExist['BALANCE_DATE'],
                'START_TIME' => $dataHdrExist['START_TIME'],
                'END_TIME' => $dataHdrExist['END_TIME'],
                'CLEAN_TIME' => $dataHdrExist['CLEAN_TIME'],
                'UNCLEAN_TIME' => $dataHdrExist['UNCLEAN_TIME'],
                'DEFFERENCE_TIME' => $dataHdrExist['DEFFERENCE_TIME'],
                'ORIGINAL_DEFFERENCE_TIME' => $dataHdrExist['ORIGINAL_DEFFERENCE_TIME'],
                'FAULT_TYPE' => $dataHdrExist['FAULT_TYPE'],
                'IS_CONFIRMED' => $dataHdrExist['IS_CONFIRMED'],
                'NIGHT_TIME' => $dataHdrExist['NIGHT_TIME'],
                'WFM_STATUS_ID' => $dataHdrExist['WFM_STATUS_ID'],
                'EARLY_TIME' => $dataHdrExist['EARLY_TIME'],
                'LATE_TIME' => $dataHdrExist['LATE_TIME'],
                'CAUSE4' => isset($params['cause_type_value'][4]) ? $params['cause_type_value'][4] : ($dataHdrExist['CAUSE4'] ? $dataHdrExist['CAUSE4'] : ((Config::getFromCache('CONFIG_TNA_HISHIGARVIN') && in_array(issetVar($params['departmentId']), $overTime)) ? '210' : 0))
            );
        }

        $cleanTimeSum = $lateTimeSum = $unclearTimeSum = 0;
        $cause1 = 0;

        $repString = Config::getFromCache('tmsCleanExpression');
        $repString1 = Config::getFromCache('tmsUnCleanExpression');
        $LATE_TYPE = '['.Config::getFromCache('tmsLateType').']';
        $EARLY_TYPE = '['.Config::getFromCache('tmsEarlyType').']';
        $repString2 = $LATE_TYPE;            
        $differenceExpression = Config::getFromCache('tmsDifferenceExpression');
        $tmsNotOverTime = Config::getFromCache('tmsNotOverTime');
        $tmsCause2Expression = Config::getFromCache('tmsCause2Expression');

        $causeEarlyTime = $sumCauseTime = $sumCauseTime1 = $tmsNotOverTimeSum = $tmsCause2Time = 0;
        $causeLateTime = -99999;
        $causeTypeDtl = $this->db->GetAll("SELECT * FROM TNA_CAUSE_TYPE WHERE IS_ACTIVE = 1 ORDER BY CAUSE_TYPE_ID");
        (Array) $mergeArr = array();

        if ($causeTypeDtl && isset($params['cause_type_value'])) {
            foreach ($causeTypeDtl as $key => $row) {

                if(isset($params['cause_type_value'][$row['CAUSE_TYPE_ID']])) {
                    $replaceCauseValue = (($params['cause_type_value'][$row['CAUSE_TYPE_ID']]) ? $params['cause_type_value'][$row['CAUSE_TYPE_ID']] : '0');
                    $repString = str_replace('[CAUSE'. $row['CAUSE_TYPE_ID'] .']', $replaceCauseValue, $repString);
                    $repString1 = str_replace('[CAUSE'. $row['CAUSE_TYPE_ID']  .']', $replaceCauseValue, $repString1);
                    $repString2 = str_replace('[CAUSE'. $row['CAUSE_TYPE_ID']  .']', $replaceCauseValue, $repString2);
                    $tmsNotOverTime = str_replace('[CAUSE'. $row['CAUSE_TYPE_ID']  .']', $replaceCauseValue, $tmsNotOverTime);
                    $differenceExpression = str_replace('[CAUSE'. $row['CAUSE_TYPE_ID']  .']', $replaceCauseValue, $differenceExpression);
                    $tmsCause2Expression = str_replace('[CAUSE'. $row['CAUSE_TYPE_ID']  .']', $replaceCauseValue, $tmsCause2Expression);

                    if ('[CAUSE'. $row['CAUSE_TYPE_ID']. ']' === $EARLY_TYPE) {
                        $causeEarlyTime = str_replace('[CAUSE'. $row['CAUSE_TYPE_ID']  .']', $params['cause_type_value'][$row['CAUSE_TYPE_ID']], $EARLY_TYPE);
                    }
                    if ('[CAUSE'. $row['CAUSE_TYPE_ID']. ']' === $LATE_TYPE) {
                        $causeLateTime = str_replace('[CAUSE'. $row['CAUSE_TYPE_ID']  .']', $params['cause_type_value'][$row['CAUSE_TYPE_ID']], $LATE_TYPE);
                    }

                    $mergeArr['CAUSE'. $row['CAUSE_TYPE_ID']] = $params['cause_type_value'][$row['CAUSE_TYPE_ID']];
                    $mergeArr['CAUSE'. $row['CAUSE_TYPE_ID'].'_DESC'] = $params['description'][$row['CAUSE_TYPE_ID']];
                } else {
                    $replaceCauseValue = 0;
                    $repString = str_replace('[CAUSE'. $row['CAUSE_TYPE_ID'] .']', $replaceCauseValue, $repString);
                    $repString1 = str_replace('[CAUSE'. $row['CAUSE_TYPE_ID']  .']', $replaceCauseValue, $repString1);
                    $repString2 = str_replace('[CAUSE'. $row['CAUSE_TYPE_ID']  .']', -99999, $repString2);
                    $tmsNotOverTime = str_replace('[CAUSE'. $row['CAUSE_TYPE_ID']  .']', $replaceCauseValue, $tmsNotOverTime);
                    $differenceExpression = str_replace('[CAUSE'. $row['CAUSE_TYPE_ID']  .']', $replaceCauseValue, $differenceExpression);                        
                    $tmsCause2Expression = str_replace('[CAUSE'. $row['CAUSE_TYPE_ID']  .']', $replaceCauseValue, $tmsCause2Expression);                        
                }
            }

            $sumCauseTime = eval('return ' . $repString . ';');
            $sumCauseTime1 = eval('return ' . $repString1 . ';');
            $causeLateTime = eval('return ' . $repString2 . ';');
            $tmsCause2Time = eval('return ' . $tmsCause2Expression . ';');
            $tmsNotOverTimeSum = eval('return ' . $tmsNotOverTime . ';');
            $differenceExpression = eval('return ' . $differenceExpression . ';') * (-1);
            $differenceExpression = $differenceExpression < 0 ? $differenceExpression * (-1) : $differenceExpression;
        }

        $fdiffTime = $diffTime + $differenceExpression;
        $fdiffTime = $fdiffTime > 0 ? 0 : $fdiffTime;

        $fearlyTime = (float) $data['EARLY_TIME'] - $causeEarlyTime;
        $fearlyTime = $fearlyTime < 0 ? $fearlyTime * (-1) : $fearlyTime;
        $fcleanTime = (float) $data['CLEAN_TIME'] + $sumCauseTime;
        $flateTime = $tmsCause2Time;

        if ($planData) {
            $fcleanTime = $fcleanTime >= $planTime ? $planTime : $fcleanTime;      
        } elseif(issetVar($mergeArr['CAUSE4']) != 0) {
            $fdiffTime = 0;
        }            

        $param = array(
            'TIME_BALANCE_HDR_ID' => $data['TIME_BALANCE_HDR_ID'],
            'EMPLOYEE_ID' => $data['EMPLOYEE_ID'],
            'BALANCE_DATE' => $data['BALANCE_DATE'],
            'START_TIME' => $data['START_TIME'],
            'END_TIME' => $data['END_TIME'],
            'CLEAN_TIME' => $fcleanTime,
            'UNCLEAN_TIME' => (float) $data['UNCLEAN_TIME'] + $sumCauseTime1,
            'DEFFERENCE_TIME' => $fdiffTime,
            'ORIGINAL_DEFFERENCE_TIME' => $fdiffTime,
            'FAULT_TYPE' => $data['FAULT_TYPE'],
            'WFM_STATUS_ID' => $data['WFM_STATUS_ID'],
            'CONFIRMED_USER_ID' => $userKeyId,
            'IS_CONFIRMED' => '1',
            'NIGHT_TIME' => $data['NIGHT_TIME'],
            'EARLY_TIME' => $fearlyTime,
            'LATE_TIME' => Input::post('timeConfirm') == '1' ? $data['LATE_TIME'] : $flateTime,
            'CAUSE4' => isset($data['CAUSE4']) ? $data['CAUSE4'] : 0,
            'CAUSE17' => $fearlyTime,
            'IS_ZERO_TIME' => isset($addonDate) ? '1' : '0',
            'ADD_DAY' => isset($addonDate) ? $addonDate : '0'
        );

        $param['CAUSE2'] = $param['LATE_TIME'];
        unset($mergeArr['CAUSE2']);

        if($param['DEFFERENCE_TIME'] != 0) {
            $param['CLEAN_TIME'] = $param['CLEAN_TIME'] + $param['LATE_TIME'];
            $param['DEFFERENCE_TIME'] = $param['DEFFERENCE_TIME'] + $param['LATE_TIME'];
        }

        if($param['CLEAN_TIME'] == $planTime) {
            if(isset($mergeArr['CAUSE5']) && !empty($mergeArr['CAUSE5']))
                $param['CLEAN_TIME'] = $param['CLEAN_TIME'] - $mergeArr['CAUSE5'];

            if(isset($mergeArr['CAUSE6']) && !empty($mergeArr['CAUSE6']))
                $param['CLEAN_TIME'] = $param['CLEAN_TIME'] - $mergeArr['CAUSE6'];

            if(isset($mergeArr['CAUSE13']) && !empty($mergeArr['CAUSE13']))
                $param['CLEAN_TIME'] = $param['CLEAN_TIME'] - $mergeArr['CAUSE13'];

            if(isset($mergeArr['CAUSE2']) && !empty($mergeArr['CAUSE2']))
                $param['CLEAN_TIME'] = $param['CLEAN_TIME'] - $mergeArr['CAUSE2'];
        }            

        if($param['CLEAN_TIME'] < $planTime) {
            $param['CAUSE4'] = 0;
        }

        if($tmsNotOverTimeSum != 0) {
            $param['CAUSE4'] = 0;
            $param['NIGHT_TIME'] = 0;
        }

        if(Config::getFromCache('tmsEarlyTimeTolateTime') == '1')
            $param['LATE_TIME'] = $param['LATE_TIME'] + $param['EARLY_TIME'];

        if($fdiffTime < 0) {
            unset($param['WFM_STATUS_ID']);
            unset($param['IS_CONFIRMED']);
        }

        if(empty($dataHdrExist)) {
            if(issetVar($mergeArr['CAUSE4']) == 0)
                unset($mergeArr['CAUSE4']);

            $param = array_merge($param, $mergeArr);
            $this->db->AutoExecute('TNA_TIME_BALANCE_HDR', $param);

        } else {
            unset($param['TIME_BALANCE_HDR_ID']);
            if(issetVar($mergeArr['CAUSE4']) == 0)
                unset($mergeArr['CAUSE4']);

            if(isset($mergeArr[$LATE_TYPE]))
                $param['LATE_TIME'] = $mergeArr[$LATE_TYPE];

            $param = array_merge($param, $mergeArr);                
            $this->db->AutoExecute('TNA_TIME_BALANCE_HDR', $param, 'UPDATE', "TIME_BALANCE_HDR_ID = ". $data['TIME_BALANCE_HDR_ID']);
        }

        $response = array('status' => 'success', 'message' => 'Амжилттай хадгаллаа');

        $result = $this->db->GetRow("
            SELECT 
                TTBH.TIME_BALANCE_HDR_ID,
                TTBH.EMPLOYEE_ID,
                TTBH.BALANCE_DATE,
                TTBH.START_TIME,
                TTBH.END_TIME,
                TTBH.CLEAN_TIME,
                TTBH.UNCLEAN_TIME,
                TTBH.DEFFERENCE_TIME,
                TTBH.ORIGINAL_DEFFERENCE_TIME,
                TTBH.FAULT_TYPE,
                TTBH.IS_CONFIRMED,
                TTBH.CONFIRMED_USER_ID,
                TTBH.NIGHT_TIME,
                TTBH.WFM_STATUS_ID,
                WWS.WFM_STATUS_NAME,
                TTBH.IS_LOCK,
                TTBH.LOCK_END_DATE,
                TTBH.LOCK_USER_ID
            FROM TNA_TIME_BALANCE_HDR TTBH
            INNER JOIN meta_wfm_status WWS ON TTBH.WFM_STATUS_ID = WWS.ID
            WHERE TTBH.TIME_BALANCE_HDR_ID = " . $data['TIME_BALANCE_HDR_ID']);

        return array_merge($response, array('result' => $result, 'timeBalanceHdrId' => $balanceTimeBalanceId));
    }

    public function getEmployeeConfirmDataV5Model() {

        $timeBalanceHdrId = Input::post('timeBalanceHdrId');

        $causeTypeDtl = $this->db->GetAll("SELECT * FROM TNA_CAUSE_TYPE WHERE IS_ACTIVE = 1 ORDER BY CAUSE_TYPE_ID");

        if ($causeTypeDtl) {
            $updateData = array();

            foreach ($causeTypeDtl as $key => $row) {

                if (isset($_POST['cause_type_value'][$timeBalanceHdrId][$row['CAUSE_TYPE_ID']])) {
                    $updateData[$row['CODE']] = Input::param($_POST['cause_type_value'][$timeBalanceHdrId][$row['CAUSE_TYPE_ID']]);
                }
            }

            if ($updateData) {

                $updateData['IS_USER_CONFIRMED'] = 1;

                try {
                    $this->db->AutoExecute('TNA_TIME_BALANCE_HDR', $updateData, 'UPDATE', 'TIME_BALANCE_HDR_ID = '.$timeBalanceHdrId);
                } catch(Exception $ex) {
                    $response = array('status' => 'warning', 'message' => $ex->getMessage());
                }
            }

            $response = array_merge(array('status' => 'success', 'message' => 'Амжилттай хадгаллаа'), array('result' => array(), 'timeBalanceHdrId' => $timeBalanceHdrId));

        } else {
            $response = array('status' => 'warning', 'message' => 'Амжилтгүй');
        }

        return $response;
    }

    public function getEmployeeCancelStatusModel() {
        if(!Input::postCheck('rows')) {
            $timeBalanceHdrId = Input::post('timeBalanceHdrId');
            $timeBalanceHdr = $this->db->GetRow("SELECT IS_LOCK, IS_CONFIRMED, ". $this->db->IfNull('CONFIRMED_USER_ID', '0') ." AS CONFIRMED_USER_ID, EMPLOYEE_ID, TO_CHAR(BALANCE_DATE, 'YYYY-MM-DD') AS BALANCE_DATE FROM TNA_TIME_BALANCE_HDR WHERE TIME_BALANCE_HDR_ID = $timeBalanceHdrId");

            try {
                if ($timeBalanceHdr) {
                    $userKeyId = Ue::sessionUserKeyId();
                    $ticket = false;
                    if ($timeBalanceHdr['IS_CONFIRMED'] === '0' && $timeBalanceHdr['IS_LOCK'] === '0') {
                        $ticket = true;
                    } else {
                        if ($userKeyId === $timeBalanceHdr['CONFIRMED_USER_ID'] && $timeBalanceHdr['IS_CONFIRMED'] === '1' && $timeBalanceHdr['IS_LOCK'] === '0') {
                            $ticket = true;
                        }
                        elseif ($timeBalanceHdr['CONFIRMED_USER_ID'] == '0' && $timeBalanceHdr['IS_CONFIRMED'] === '1' && $timeBalanceHdr['IS_LOCK'] === '0') {
                            $ticket = true;
                        }
                    }

                    $result = $this->db->Execute('DELETE FROM TNA_TIME_BALANCE_DTL WHERE TIME_BALANCE_HDR_ID = ' . $timeBalanceHdrId);
                    if ($result) {
                        self::addUserSessionCountModel();
                        $this->db->Execute('DELETE FROM TNA_TIME_BALANCE_HDR WHERE TIME_BALANCE_HDR_ID = ' . $timeBalanceHdrId);
//                        if (Config::getFromCache('CONFIG_TNA_HISHIGARVIN')) {
//                            $this->db->Execute("DELETE FROM TNA_TIME_ATTENDANCE WHERE EMPLOYEE_ID = '". $timeBalanceHdr['EMPLOYEE_ID'] ."' AND TO_CHAR(ATTENDANCE_DATE_TIME, 'YYYY-MM-DD') = '". $timeBalanceHdr['BALANCE_DATE'] ."'");
//                        }

                        return array('status' => 'success', 'message' => 'Төлөв амжилттай солигдсон', 'result' => '');
                    }
                } else {
                    $timeBalanceHdr = $this->db->GetRow("SELECT ID FROM TNA_TIME_ATTENDANCE WHERE TO_CHAR(ATTENDANCE_DATE_TIME, 'YYYY-MM-DD') = '".Input::post('balanceDate')."' AND EMPLOYEE_ID = ".Input::post('employeeId'));

                    if($timeBalanceHdr) {
                        $this->db->AutoExecute('TNA_TIME_ATTENDANCE', array('IS_REMOVED_NOT_PLAN' => 1), 'UPDATE', "TO_CHAR(ATTENDANCE_DATE_TIME, 'YYYY-MM-DD') = '".Input::post('balanceDate')."' AND EMPLOYEE_ID = ".Input::post('employeeId'));
                        return array('status' => 'success', 'message' => 'Төлөв амжилттай солигдсон', 'result' => '');
                    }

                    return array('status' => 'warning', 'message' => 'Устгах боломжгүй байна!');
                }
            } catch (Exception $ex) {
                return array('status' => 'warning', 'message' => 'Устгах боломжгүй байна!');
            }

        } else {
            $timeBalanceHdrIds = Input::post('rows');

            foreach ($timeBalanceHdrIds as $val) {
                if ($val['islock'] != '1') {
                    $timeBalanceHdrId = isset($val['TIME_BALANCE_HDR_ID']) ? $val['TIME_BALANCE_HDR_ID'] : $val['timebalancehdrid'];
                    $timeBalanceHdrDate = isset($val['BALANCE_DATE']) ? $val['BALANCE_DATE'] : $val['balancedate'];
                    $timeBalanceHdrEmployeeId = isset($val['EMPLOYEE_ID']) ? $val['EMPLOYEE_ID'] : $val['employeeid'];

                    $timeBalanceHdr = $this->db->GetRow("SELECT IS_LOCK, IS_CONFIRMED, ". $this->db->IfNull('CONFIRMED_USER_ID', '0') ." AS CONFIRMED_USER_ID, EMPLOYEE_ID, TO_CHAR(BALANCE_DATE, 'YYYY-MM-DD') AS BALANCE_DATE FROM TNA_TIME_BALANCE_HDR WHERE TIME_BALANCE_HDR_ID = $timeBalanceHdrId");

                    try {
                        if ($timeBalanceHdr) {
                            $userKeyId = Ue::sessionUserKeyId();
                            $ticket = false;
                            if ($timeBalanceHdr['IS_CONFIRMED'] === '0' && $timeBalanceHdr['IS_LOCK'] === '0') {
                                $ticket = true;
                            } else {
                                if ($userKeyId === $timeBalanceHdr['CONFIRMED_USER_ID'] && $timeBalanceHdr['IS_CONFIRMED'] === '1' && $timeBalanceHdr['IS_LOCK'] === '0') {
                                    $ticket = true;
                                }
                                elseif ($timeBalanceHdr['CONFIRMED_USER_ID'] == '0' && $timeBalanceHdr['IS_CONFIRMED'] === '1' && $timeBalanceHdr['IS_LOCK'] === '0') {
                                    $ticket = true;
                                }
                            }

                            $result = $this->db->Execute('DELETE FROM TNA_TIME_BALANCE_DTL WHERE TIME_BALANCE_HDR_ID = ' . $timeBalanceHdrId);
                            if ($result) {
                                self::addUserSessionCountModel();
                                $this->db->Execute('DELETE FROM TNA_TIME_BALANCE_HDR WHERE TIME_BALANCE_HDR_ID = ' . $timeBalanceHdrId);                                
                            }

                        } else {
                            $timeBalanceHdr = $this->db->GetRow("SELECT ID FROM TNA_TIME_ATTENDANCE WHERE TO_CHAR(ATTENDANCE_DATE_TIME, 'YYYY-MM-DD') = '".$timeBalanceHdrDate."' AND EMPLOYEE_ID = ".$timeBalanceHdrEmployeeId);

                            if($timeBalanceHdr) {
                                $this->db->AutoExecute('TNA_TIME_ATTENDANCE', array('IS_REMOVED_NOT_PLAN' => 1), 'UPDATE', "TO_CHAR(ATTENDANCE_DATE_TIME, 'YYYY-MM-DD') = '".$timeBalanceHdrDate."' AND EMPLOYEE_ID = ".$timeBalanceHdrEmployeeId);
                            }
                        }

                    } catch (Exception $ex) {
                    }
                }
            }
            return array('status' => 'success', 'message' => 'Төлөв амжилттай солигдсон', 'result' => '');                
        }
    }        

    public function getVerifEmployeeModel() {
        $userId = Ue::sessionUserId();
        (Array) $departmentId = array();
        (Array) $response = array('CHECK' => '0', 'departmentIds' => $departmentId);
        $qry = "SELECT COUNT(ID) AS COUNTT FROM TNA_EMPLOYEE_PLAN_OWNER WHERE USER_ID = $userId";

        $resultCountt = $this->db->GetOne($qry);

        if ((int) $resultCountt != 0) {
            $qry = "SELECT DISTINCT DEPARTMENT_ID  FROM TNA_EMPLOYEE_PLAN_OWNER WHERE USER_ID = $userId";
            $resultDepartment = $this->db->GetAll($qry);
            foreach ($resultDepartment as $key => $deparment) {
                if (!in_array($deparment['DEPARTMENT_ID'], $departmentId)) {
                    array_push($departmentId, $deparment['DEPARTMENT_ID']);
                }
            }
            $response = array('CHECK' => '1', 'departmentIds' => $departmentId);
        }

        return $response;
    }

    public function getUserDepartmentIdModel() {
        $result = $this->db->GetOne("SELECT DEPARTMENT_ID FROM  TNA_APPROVE_CONFIG  WHERE USER_ID = " . Ue::sessionUserKeyId());
        if ($result) {
            return $result;
        }

        return false;
    }

    public function searchTnaCauseTypeListModel() {
        $data = array(
            array('CAUSE_TYPE_ID' => 'inTime', 'NAME' => 'Орох дараагүй'),
            array('CAUSE_TYPE_ID' => 'outTime', 'NAME' => 'Гарах дараагүй'),
            array('CAUSE_TYPE_ID' => 'isConfirm', 'NAME' => 'Баталгаажсан'),
            array('CAUSE_TYPE_ID' => 'isUnConfirm', 'NAME' => 'Зөрчилтэй')
        );

        return array_merge($data, self::tnaCauseTypeModel());
    }

    public function searchTnaGroupListModel() {
        return $this->db->GetAll("".
            "SELECT GI.ID AS NOT_ID, GI.CODE, GI.NAME||' '||GI.SHIFT_NUMBER AS NAME, GI.CREATED_USER_ID, 
                    GI.CREATED_DATE, GI.MODIFIED_USER_ID,GI.MODIFIED_DATE, GI.SHIFT_NUMBER, GI.ID
             FROM TNA_GROUP_INFO GI
             INNER JOIN TNA_EMPLOYEE_GROUP_CONFIG GC ON GC.GROUP_ID = GI.ID
             WHERE GC.IS_ACTIVE = 1
             GROUP BY GI.ID, GI.CODE, GI.NAME, GI.CREATED_USER_ID, GI.CREATED_DATE, GI.MODIFIED_USER_ID, GI.MODIFIED_DATE, GI.SHIFT_NUMBER");
    }

    public function tmsTemplateListModel() {
        $existMetaId = (new Mdmetadata())->getMetaData('1520247172619');

        if ($existMetaId) {
            (Array) $param = array(
                'systemMetaGroupId' => '1520247172619',
                'showQuery' => 0, 
                'ignorePermission' => 1
            );

            $data = $this->ws->runResponse(self::$gfServiceAddress, Mddatamodel::$getDataViewCommand, $param);            
            if ($data['status'] === 'success' && isset($data['result'])) {
                unset($data['result']['paging']);
                unset($data['result']['aggregatecolumns']);

                return Arr::changeKeyUpper($data['result']);
            }
        } else {
            return $this->db->GetAll("SELECT ID, CODE, NAME FROM TMS_TEMPLATE");
        }
    }

    public function getPositionListModel($param = false) {

        $existMetaId = (new Mdmetadata())->getMetaData('16200129969161');

        if ($existMetaId) {
            (Array) $param = array(
                'systemMetaGroupId' => '16200129969161',
                'showQuery' => 0, 
                'ignorePermission' => 1
            );

            $data = $this->ws->runResponse(self::$gfServiceAddress, Mddatamodel::$getDataViewCommand, $param);            
            if ($data['status'] === 'success' && isset($data['result'])) {
                unset($data['result']['paging']);
                unset($data['result']['aggregatecolumns']);

                return Arr::changeKeyUpper($data['result']);
            } else {
                return array();
            }

        } else {

            $andWhere = "";
            $join = "LEFT";
            if (Config::getFromCache('tmsCustomerCode') == 'gov') {
    
                $andWhere = "AND HP.GLOBE_CODE IS NULL";
                $join = "INNER";
            }
    
            $result = $this->db->GetAll("
                SELECT 
                    HP.POSITION_ID, 
                    HP.POSITION_NAME,
                    HP.DISPLAY_ORDER
                FROM 
                    HRM_POSITION_KEY HPK
                    $join JOIN HRM_POSITION HP ON HPK.POSITION_ID = HP.POSITION_ID $andWhere
                WHERE HPK.DEPARTMENT_ID IN (" . ($param ? implode(",", $param) : "SELECT DEPARTMENT_ID FROM ORG_DEPARTMENT") . ")
                GROUP BY 
                    HP.POSITION_ID, 
                    HP.POSITION_NAME,
                    HP.DISPLAY_ORDER
                ORDER BY TO_NUMBER(HP.DISPLAY_ORDER), HP.POSITION_NAME ASC");

            return $result;
        }        

    }

    public function getDepartmentListModel() {
        (Array) $param = array(
            'systemMetaGroupId' => 1526027254412,
            'showQuery' => 0, 
            'ignorePermission' => 1,
            'paging' => array(
                'offset' => 1,
                'pageSize' => 1
            )
        );

        $data = $this->ws->runResponse(self::$gfServiceAddress, Mddatamodel::$getDataViewCommand, $param);            
        if ($data['status'] === 'success' && isset($data['result'])) {
            unset($data['result']['paging']);
            unset($data['result']['aggregatecolumns']);

            return is_array($data['result']) ? $data['result'][0] : array();
        }            
        // $data = $this->db->GetAll("
        //     SELECT 
        //         CNF.DEPARTMENT_ID AS ID,
        //         DP.DEPARTMENT_NAME || ' - ' || DP.DEPARTMENT_CODE AS NAME
        //     FROM TNA_APPROVE_CONFIG CNF
        //         INNER JOIN ORG_DEPARTMENT DP ON CNF.DEPARTMENT_ID = DP.DEPARTMENT_ID
        //     WHERE CNF.USER_ID = " . Ue::sessionUserKeyId() . "
        //     GROUP BY 
        //         CNF.DEPARTMENT_ID, 
        //         DP.DEPARTMENT_CODE, 
        //         DP.DEPARTMENT_NAME 
        //     ORDER BY DP.DEPARTMENT_NAME ASC");

        // if (count($data) > 0) {
        //     return $data;
        // }
        // return false;
    }

    public function getWfmStatusByBalanceBtnModel($departmentId, $uniqId) {
        $sessionUserKeyId = Ue::sessionUserKeyId();
        (Array) $array_temp = array();
        $joinDepartmentIds = '0';

        $departments = $this->db->GetAll("SELECT DEPARTMENT_ID FROM  TNA_APPROVE_CONFIG  WHERE USER_ID = $sessionUserKeyId");
        if (sizeof($departments) > 0) {

            foreach ($departments as $dep) {
                if (!in_array($dep['DEPARTMENT_ID'], $array_temp)) {
                    array_push($array_temp, $dep['DEPARTMENT_ID']);
                }
            }

            $joinDepartmentIds = join(',', $array_temp);
        }
        $btnAll = $btnItem = '';

        if ($departmentId) {
            $result = $this->db->GetAll("
                SELECT DISTINCT
                    TAC.WFM_STATUS_ID,
                    WWS.WFM_STATUS_CODE,
                    WWS.WFM_STATUS_COLOR,
                    WWS.WFM_STATUS_NAME
                FROM 
                    TNA_APPROVE_CONFIG TAC
                INNER JOIN META_WFM_STATUS WWS ON TAC.WFM_STATUS_ID = WWS.ID
                WHERE  TAC.USER_ID = $sessionUserKeyId AND  TAC.DEPARTMENT_ID in ($joinDepartmentIds)");
            if ($result) {
                $iconClass = $text = '';
                foreach ($result as $k => $row) {
                    if ($row['WFM_STATUS_CODE'] == '002' || $row['WFM_STATUS_CODE'] == '040') {
                        $btnAll .= '<button ';
                        $btnAll .= 'type="button" ';
                        $btnAll .= 'style="padding:0px 7px 1px 7px" ';
                        $btnAll .= 'id="' . $row['WFM_STATUS_ID'] . '" ';
                        $btnAll .= 'data-uniqid="' . $uniqId . '" ';
                        $btnAll .= 'class="btn btn-sm btn-success btn-circle mr10 float-right confirmTimeBalance statusApproveBtn" ';
                        $btnAll .= 'data-status-id="' . $row['WFM_STATUS_ID'] . '" ';
                        $btnAll .= 'data-status-name="' . $row['WFM_STATUS_NAME'] . '" ';
                        $btnAll .= 'data-status-code="' . $row['WFM_STATUS_CODE'] . '" ';
                        $btnAll .= 'title="' . $row['WFM_STATUS_NAME'] . '">';
                        $btnAll .= '<i class="fa fa-save"></i> ' . $row['WFM_STATUS_NAME'] . '';
                        $btnAll .= '</button>';
                        $btnItem .= '<button ';
                        $btnItem .= 'type="button" ';
                        $btnItem .= 'style="padding:0px 7px 1px 7px; margin-right: 2px;" ';
                        $btnItem .= 'data-uniqid="' . $uniqId . '" ';
                        $btnItem .= 'id="' . $row['WFM_STATUS_ID'] . '" ';
                        $btnItem .= 'class="btn btn-sm btn-success employeeConfirmBtn float-right statusApproveBtn" ';
                        $btnItem .= 'data-status-id="' . $row['WFM_STATUS_ID'] . '" ';
                        $btnItem .= 'data-status-name="' . $row['WFM_STATUS_NAME'] . '" ';
                        $btnItem .= 'data-status-code="' . $row['WFM_STATUS_CODE'] . '" ';
                        $btnItem .= 'title="' . $row['WFM_STATUS_NAME'] . '">';
                        $btnItem .= '<i class="fa fa-save"></i>';
                        $btnItem .= '</button>';
                    } else if ($row['WFM_STATUS_CODE'] == '003') {
                        $btnAll .= '<button ';
                        $btnAll .= 'type="button" ';
                        $btnAll .= 'style="padding:0px 7px 1px 7px" ';
                        $btnAll .= 'id="' . $row['WFM_STATUS_ID'] . '" ';
                        $btnAll .= 'data-uniqid="' . $uniqId . '" ';
                        $btnAll .= 'class="btn btn-sm default btn-circle mr10 float-right timeCancelBtn statusCancelBtn" ';
                        $btnAll .= 'data-status-id="' . $row['WFM_STATUS_ID'] . '" ';
                        $btnAll .= 'data-status-name="' . $row['WFM_STATUS_NAME'] . '" ';
                        $btnAll .= 'data-status-code="' . $row['WFM_STATUS_CODE'] . '" ';
                        $btnAll .= 'title="' . $row['WFM_STATUS_NAME'] . '">';
                        $btnAll .= '<i class="fa fa-times"></i> ' . Lang::line('cancel_btn') . '';
                        $btnAll .= '</button>';
                        $btnItem .= ' <button ';
                        $btnItem .= 'type="button" ';
                        $btnItem .= 'style="padding:0px 7px 1px 7px; margin-right: 4px;" ';
                        $btnItem .= 'id="' . $row['WFM_STATUS_ID'] . '" ';
                        $btnItem .= 'data-uniqid="' . $uniqId . '" ';
                        $btnItem .= 'class="btn btn-sm default ml0 float-right employeeCancelBtn statusCancelBtn" ';
                        $btnItem .= 'data-status-id="' . $row['WFM_STATUS_ID'] . '" ';
                        $btnItem .= 'data-status-name="' . $row['WFM_STATUS_NAME'] . '" ';
                        $btnItem .= 'data-status-code="' . $row['WFM_STATUS_CODE'] . '" ';
                        $btnItem .= 'title="' . $row['WFM_STATUS_NAME'] . '">';
                        $btnItem .= '<i class="fa fa-times"></i>';
                        $btnItem .= '</button> ';
                    }
                }
            }
        }

        return array('all' => $btnAll, 'item' => $btnItem);
    }

    public function searchTnaEmployeeStatusListModel($type = false) {
        if (!$type) {
            return array();
        } else {
            return $this->db->GetAll("SELECT * FROM HRM_EMPLOYEE_STATUS WHERE IS_ACTIVE = 1");
        }
    }

    public function getTnaIsApprovedDeparmentModel($departmentId, $uniqId) {
        $sessionUserKeyId = Ue::sessionUserKeyId();
        (Array) $array_temp = array();
        $joinDepartmentIds = '0';

        $departments = $this->db->GetAll("SELECT DEPARTMENT_ID FROM TNA_APPROVE_CONFIG  WHERE USER_ID = $sessionUserKeyId");
        if (sizeof($departments) > 0) {

            foreach ($departments as $dep) {
                if (!in_array($dep['DEPARTMENT_ID'], $array_temp)) {
                    array_push($array_temp, $dep['DEPARTMENT_ID']);
                }
            }

            $joinDepartmentIds = join(',', $array_temp);
        }
        $btnAll = $btnItem = '';
        if ($departmentId) {
            $result = $this->db->GetOne("SELECT DISTINCT MAX(IS_LOCK) AS IS_LOCK FROM TNA_APPROVE_CONFIG TAC WHERE TAC.USER_ID = $sessionUserKeyId AND TAC.DEPARTMENT_ID in ($joinDepartmentIds)");
            if ($result === '1') {
                return true;
            }
        }

        return false;
    }        

    public function tnaCauseTypeModel() {
        $result = $this->db->GetAll("
            SELECT 
                CAUSE_TYPE_ID,
                CODE,
                INITCAP(NAME) AS NAME
            FROM 
                TNA_CAUSE_TYPE
            WHERE IS_ACTIVE = 1 AND IS_REPORT_VIEW = 1
            ORDER BY VIEW_ORDER");
        if ($result) {
            return $result;
        }
        return array();
    }        

    public function tnaCauseTypeGridModel() {
        $result = $this->db->GetAll("
            SELECT 
                CAUSE_TYPE_ID,
                CODE,
                INITCAP(NAME) AS NAME
            FROM 
                TNA_CAUSE_TYPE
            WHERE IS_REPORT_VIEW = 1
            ORDER BY VIEW_ORDER");
        if ($result) {
            return $result;
        }
        return array();
    }        

    public function tnaCauseTypeHdrGridModel() {
        $result = $this->db->GetAll("
            SELECT 
                CAUSE_TYPE_ID,
                CODE,
                COLUMN_WIDTH,
                INITCAP(NAME) AS NAME
            FROM 
                TNA_CAUSE_TYPE
            WHERE IS_REPORT_VIEW = 1 AND IS_MAIN_BALANCE = 1
            ORDER BY VIEW_ORDER");
        if ($result) {
            return $result;
        }
        return array();
    }        

    public function addUserSessionCountModel() {
        $userId = Ue::sessionUserId();
        $sessionCount = $this->db->GetOne("SELECT SESSION_COUNT FROM TNA_PLAN_PERMISSION_CONFIG WHERE USER_ID = $userId");

        $data = array('SESSION_COUNT' => (intval($sessionCount) + 1));
        $this->db->AutoExecute('TNA_PLAN_PERMISSION_CONFIG', $data, 'UPDATE', "USER_ID = $userId");
    }        

    public function multiChangeBalanceQueryV3Model() {
        $params = '';
        parse_str($_POST['balanceDtl'], $params);

        $userId = Ue::sessionUserId();
        $userKeyId = Ue::sessionUserKeyId();
        $currentDate = Date::currentDate('Y-m-d H:i:s');
        $procedureName = 'PRC_TNA_BALANCE_CONFIRM';
        // '--------------- change ------- ';

        $nightTime = 0;
        $nightTimeRange = $this->db->GetOne("SELECT VALUE FROM PR_CONFIG WHERE CONFIG_ID = 4");
        $wfmStatusId = '1472634629956170';
        $defaultDefferenceTime = (float) Config::getFromCache('tmsDefaultDefferenceTime');
        $defaultDefferenceTime = $defaultDefferenceTime ? $defaultDefferenceTime : -60;              

        if ($nightTimeRange) {
            $nightTimeRange = explode('-', $nightTimeRange);
            $nightStartDate = $nightTimeRange[0];
            $nightEndDate = $nightTimeRange[1];
        }

        $planTimeDefault = (float) Config::getFromCache('tmsPlanTimeDefault') * 60;
        $overTime = Config::getFromCache('tmsOverTime');
        $overTime = $overTime ? explode(',', $overTime) : array();            

        $notPlanDepartment = Config::getFromCache('tmsPlanTimeDefaultDepartment');
        $notPlanDepartment = $notPlanDepartment ? explode(',', $notPlanDepartment) : array();
        $isEarlyTimeToClean = Config::getFromCache('tmsIsEarlyTimeToCleanTime');
        $isEarlyTimeToClean = $isEarlyTimeToClean ? $isEarlyTimeToClean : 0;            

        $isLateTimeToCleantime = Config::getFromCache('tmsIsLateTimeClearTime');
        $isLateTimeToCleantime = $isLateTimeToCleantime ? $isLateTimeToCleantime : 0;            

        foreach ($_POST['balanceHdr'] as $k => $row) {
            $balanceTimeBalanceId = isset($row['TIME_BALANCE_ID']) ? Input::param($row['TIME_BALANCE_ID']) : getUID();
            $balanceDate = $endBalanceDate = Date::format('Y-m-d', Input::param($row['BALANCE_DATE']));
            $notPlanDepartmentCheck = in_array(issetVar($row['DEPARTMENT_ID']), $notPlanDepartment) ? true : false;

            $employeeId = Input::param($row['EMPLOYEE_ID']);
            $employeeKeyId = Input::param($row['EMPLOYEE_KEY_ID']);
            $addPlusDate = '1'; $addonDate = 0; $plusDate = 'T0.PLAN_DURATION';

            if (isset($params['isAddonDate']) && !empty($params['isAddonDate']) && isset($params['addonDate']) && !empty($params['addonDate'])) {
                $addPlusDate = $plusDate = $addonDate = $params['addonDate'];
                $endBalanceDate = Date::nextDate($endBalanceDate, $addonDate, 'Y-m-d');
            }

            $endTime = isset($params['change_outtime']) ? $params['change_outtime'] : (($params['outTime'] !== '-') ? $params['outTime'] : '');
            $startTime = isset($params['change_intime']) ? $params['change_intime'] : (($params['inTime'] !== '-') ? $params['inTime'] : '');                

            $earlyTime = $zorchilguiTime = $lateTime = $diffTime  = $clearTime = $unClearTime = $tmsNotOverTimeSum = 0;

            $planData = $this->db->GetRow("SELECT 
                                                T2.PLAN_ID,
                                                T2.PLAN_DURATION,
                                                T2.IS_LATE,
                                                ROUND(FNC_GET_TMS_PLAN_TIME(T1.PLAN_ID)) AS PLAN_TIME,
                                                (ROUND(DATEDIFF(
                                                    TO_DATE(TO_CHAR(SYSDATE, 'YYYY-MM-DD')||TO_CHAR(T4.START_TIME, 'HH24:MI'), 'YYYY-MM-DD HH24:MI'),
                                                    TO_DATE(TO_CHAR(SYSDATE, 'YYYY-MM-DD')||TO_CHAR(T5.END_TIME, 'HH24:MI'), 'YYYY-MM-DD HH24:MI')  + T2.PLAN_DURATION
                                                )) / 60) - FNC_GET_TMS_PLAN_TIME(T1.PLAN_ID) AS LUNCH_TIME,
                                                TO_CHAR(T4.END_TIME, 'HH24:MI') AS LUNCH_TIME_START,
                                                TO_CHAR(T5.START_TIME, 'HH24:MI') AS LUNCH_TIME_END
                                            FROM TMS_EMPLOYEE_TIME_PLAN_HDR T0
                                                INNER JOIN TMS_EMPLOYEE_TIME_PLAN_DTL T1 ON T0.ID = T1.TIME_PLAN_ID
                                                INNER JOIN TMS_TIME_PLAN T2 ON T1.PLAN_ID = T2.PLAN_ID
                                                INNER JOIN (
                                                    SELECT PLAN_ID, MIN(PLAN_DETAIL_ID) AS MIN_DETAIL_ID, MAX(PLAN_DETAIL_ID) AS MAX_DETAIL_ID FROM TMS_TIME_PLAN_DETAIL 
                                                    GROUP BY PLAN_ID
                                                ) T3 ON T2.PLAN_ID = T3.PLAN_ID
                                                INNER JOIN  TMS_TIME_PLAN_DETAIL T4 ON T3.MIN_DETAIL_ID = T4.PLAN_DETAIL_ID
                                                INNER JOIN  TMS_TIME_PLAN_DETAIL T5 ON T3.MAX_DETAIL_ID = T5.PLAN_DETAIL_ID
                                            WHERE   TO_CHAR(T1.PLAN_DATE, 'YYYY-MM-DD') = '$balanceDate'
                                                AND T0.EMPLOYEE_ID = $employeeId");         

            $planTime = isset($planData['PLAN_TIME']) && !$notPlanDepartmentCheck ? (float) $planData['PLAN_TIME'] : ($planTimeDefault ? $planTimeDefault : 0);
            $lunchTime = isset($planData['LUNCH_TIME']) ? 0 : (float) Config::getFromCache('tmsLunchTimeDefault') * 60;
            $diffTime = 0 - $clearTime;
            $planDetail = '';
            $checkTick = false;
            $cause4 = 0;

            $dataHdrExist = $this->db->GetRow("SELECT * FROM TNA_TIME_BALANCE_HDR WHERE TIME_BALANCE_HDR_ID = " . $row['TIME_BALANCE_HDR_ID']);

            if(empty($dataHdrExist) || array_key_exists('detect_change_intime', $params) || array_key_exists('detect_change_outtime', $params)) {
                $inTime1 = $params['inTime'];
                $outTime1 = $params['outTime'];

                if ($params['inTime'] < $params['outTime']) {
                    $d1 = new DateTime($params['inTime']);
                    $d2 = new DateTime($params['outTime']);
                } else {
                    $d1 = new DateTime($params['outTime']);
                    $d2 = new DateTime($params['inTime']);
                }

                // <editor-fold defaultstate="collapsed" desc="GET PLAN">           
                $qs = "SELECT 
                        T0.PLAN_ID,
                        T0.PLAN_DURATION,
                        T0.STARTTIME,
                        T0.ENDTIME,
                        T0.STARTTIME_LIMIT,
                        T0.ENDTIME_LIMIT,
                        T0.LUNCH_TIME,
                        T0.PLAN_TIME,
                        CASE WHEN 60 >= T0.EARLY_TIME AND T0.EARLY_TIME > 0 THEN T0.EARLY_TIME ELSE 0 END AS EARLY_TIME,
                        CASE WHEN 60 >= T0.LATE_TIME AND T0.LATE_TIME > 0 AND ( T0.IS_LATE IS NULL OR T0.IS_LATE = 0 ) THEN T0.LATE_TIME ELSE 0 END AS LATE_TIME,
                        CASE WHEN (T0.ENDTIME > '$inTime1' AND T0.STARTTIME < '$outTime1') OR (T0.PLAN_DURATION > 0) THEN
                        (ROUND(DATEDIFF(
                            CASE
                            WHEN T0.STARTTIME < '$inTime1' AND ( T0.IS_LATE IS NULL OR T0.IS_LATE = 0 ) THEN
                                TO_DATE(TO_CHAR(SYSDATE, 'YYYY-MM-DD')||'$inTime1' , 'YYYY-MM-DD HH24:MI')
                            ELSE
                                TO_DATE(TO_CHAR(SYSDATE, 'YYYY-MM-DD')||T0.STARTTIME , 'YYYY-MM-DD HH24:MI')
                            END,
                            CASE WHEN T0.ENDTIME > '$outTime1' THEN
                                CASE WHEN $addonDate != 0 
                                    THEN TO_DATE(TO_CHAR(SYSDATE - $addonDate, 'YYYY-MM-DD')||T0.ENDTIME , 'YYYY-MM-DD HH24:MI') 
                                    ELSE 
                                    CASE WHEN TO_DATE(TO_CHAR(SYSDATE, 'YYYY-MM-DD')||'$outTime1' , 'YYYY-MM-DD HH24:MI') >= TO_DATE(TO_CHAR(SYSDATE, 'YYYY-MM-DD')||T0.STARTTIME_ENDTIME, 'YYYY-MM-DD HH24:MI') AND TO_DATE(TO_CHAR(SYSDATE, 'YYYY-MM-DD')||'$outTime1' , 'YYYY-MM-DD HH24:MI') <= TO_DATE(TO_CHAR(SYSDATE, 'YYYY-MM-DD')||T0.ENDTIME_STARTTIME, 'YYYY-MM-DD HH24:MI') 
                                    THEN 
                                        TO_DATE(TO_CHAR(SYSDATE, 'YYYY-MM-DD')||T0.STARTTIME_ENDTIME, 'YYYY-MM-DD HH24:MI') 
                                    ELSE 
                                        TO_DATE(TO_CHAR(SYSDATE, 'YYYY-MM-DD')||T0.ENDTIME , 'YYYY-MM-DD HH24:MI') END END  
                            ELSE
                                TO_DATE(TO_CHAR(SYSDATE, 'YYYY-MM-DD')||T0.ENDTIME , 'YYYY-MM-DD HH24:MI')
                            END
                            + $plusDate
                        ))/60)
                        + CASE WHEN 60 >= T0.EARLY_TIME AND T0.EARLY_TIME > 0 THEN T0.EARLY_TIME ELSE 0 END
                        + CASE WHEN 60 >= T0.LATE_TIME AND T0.LATE_TIME > 0 AND ( T0.IS_LATE IS NULL OR T0.IS_LATE = 0 ) THEN T0.LATE_TIME ELSE 0 END
                        - (CASE WHEN 
                            (CASE WHEN T0.STARTTIME < '$inTime1' AND ( T0.IS_LATE IS NULL OR T0.IS_LATE = 0 ) THEN
                                    TO_DATE(TO_CHAR(SYSDATE, 'YYYY-MM-DD')||'$inTime1' , 'YYYY-MM-DD HH24:MI')
                                ELSE
                                    TO_DATE(TO_CHAR(SYSDATE, 'YYYY-MM-DD')||T0.STARTTIME , 'YYYY-MM-DD HH24:MI')
                                END >= TO_DATE(TO_CHAR(SYSDATE, 'YYYY-MM-DD')||T0.STARTTIME_ENDTIME, 'YYYY-MM-DD HH24:MI') 
                                AND  CASE
                                    WHEN T0.STARTTIME < '$inTime1' AND ( T0.IS_LATE IS NULL OR T0.IS_LATE = 0 ) THEN
                                        TO_DATE(TO_CHAR(SYSDATE, 'YYYY-MM-DD')||'$inTime1' , 'YYYY-MM-DD HH24:MI')
                                    ELSE
                                        TO_DATE(TO_CHAR(SYSDATE, 'YYYY-MM-DD')||T0.STARTTIME , 'YYYY-MM-DD HH24:MI')
                                    END <= TO_DATE(TO_CHAR(SYSDATE, 'YYYY-MM-DD')||T0.ENDTIME_STARTTIME, 'YYYY-MM-DD HH24:MI'))
                            OR (
                                CASE
                                WHEN T0.ENDTIME > '$outTime1' THEN
                                    CASE WHEN $addonDate != 0 
                                        THEN TO_DATE(TO_CHAR(SYSDATE - $addonDate, 'YYYY-MM-DD')||T0.ENDTIME , 'YYYY-MM-DD HH24:MI') 
                                        ELSE TO_DATE(TO_CHAR(SYSDATE, 'YYYY-MM-DD')||'$outTime1' , 'YYYY-MM-DD HH24:MI')   END  
                                ELSE
                                    TO_DATE(TO_CHAR(SYSDATE, 'YYYY-MM-DD')||T0.ENDTIME , 'YYYY-MM-DD HH24:MI')
                                END >= TO_DATE(TO_CHAR(SYSDATE, 'YYYY-MM-DD')||T0.STARTTIME_ENDTIME, 'YYYY-MM-DD HH24:MI') 
                                AND 
                                CASE
                                WHEN T0.ENDTIME > '$outTime1' THEN
                                    CASE WHEN $addonDate != 0 
                                        THEN TO_DATE(TO_CHAR(SYSDATE - $addonDate, 'YYYY-MM-DD')||T0.ENDTIME , 'YYYY-MM-DD HH24:MI') 
                                        ELSE TO_DATE(TO_CHAR(SYSDATE, 'YYYY-MM-DD')||'$outTime1' , 'YYYY-MM-DD HH24:MI')   END  
                                ELSE
                                    TO_DATE(TO_CHAR(SYSDATE, 'YYYY-MM-DD')||T0.ENDTIME , 'YYYY-MM-DD HH24:MI')
                                END <= TO_DATE(TO_CHAR(SYSDATE, 'YYYY-MM-DD')||T0.ENDTIME_STARTTIME, 'YYYY-MM-DD HH24:MI')                                                            
                            )
                            OR (
                                CASE
                                WHEN T0.ENDTIME > '$outTime1' THEN
                                    CASE WHEN $addonDate != 0 
                                        THEN TO_DATE(TO_CHAR(SYSDATE - $addonDate, 'YYYY-MM-DD')||T0.ENDTIME , 'YYYY-MM-DD HH24:MI') 
                                        ELSE TO_DATE(TO_CHAR(SYSDATE, 'YYYY-MM-DD')||'$outTime1' , 'YYYY-MM-DD HH24:MI')   END  
                                ELSE
                                    TO_DATE(TO_CHAR(SYSDATE, 'YYYY-MM-DD')||T0.ENDTIME , 'YYYY-MM-DD HH24:MI')
                                END <= TO_DATE(TO_CHAR(SYSDATE, 'YYYY-MM-DD')||T0.ENDTIME_STARTTIME, 'YYYY-MM-DD HH24:MI')
                                AND CASE
                                WHEN T0.STARTTIME < '$inTime1' AND ( T0.IS_LATE IS NULL OR T0.IS_LATE = 0 ) THEN
                                    TO_DATE(TO_CHAR(SYSDATE, 'YYYY-MM-DD')||'$inTime1' , 'YYYY-MM-DD HH24:MI')
                                ELSE
                                    TO_DATE(TO_CHAR(SYSDATE, 'YYYY-MM-DD')||T0.STARTTIME , 'YYYY-MM-DD HH24:MI')
                                END >= TO_DATE(TO_CHAR(SYSDATE, 'YYYY-MM-DD')||T0.STARTTIME_ENDTIME, 'YYYY-MM-DD HH24:MI')
                            )
                            OR (
                                CASE
                                WHEN T0.STARTTIME < '$inTime1' AND ( T0.IS_LATE IS NULL OR T0.IS_LATE = 0 ) THEN
                                    TO_DATE(TO_CHAR(SYSDATE, 'YYYY-MM-DD')||'$inTime1' , 'YYYY-MM-DD HH24:MI')
                                ELSE
                                    TO_DATE(TO_CHAR(SYSDATE, 'YYYY-MM-DD')||T0.STARTTIME , 'YYYY-MM-DD HH24:MI')
                                END >= TO_DATE(TO_CHAR(SYSDATE, 'YYYY-MM-DD')||T0.ENDTIME_STARTTIME, 'YYYY-MM-DD HH24:MI')
                            )
                            OR (
                                CASE
                            WHEN T0.ENDTIME > '$outTime1' THEN
                                CASE WHEN $addonDate != 0 
                                    THEN TO_DATE(TO_CHAR(SYSDATE - $addonDate, 'YYYY-MM-DD')||T0.ENDTIME , 'YYYY-MM-DD HH24:MI') 
                                    ELSE TO_DATE(TO_CHAR(SYSDATE, 'YYYY-MM-DD')||'$outTime1' , 'YYYY-MM-DD HH24:MI')   END  
                            ELSE
                                TO_DATE(TO_CHAR(SYSDATE, 'YYYY-MM-DD')||T0.ENDTIME , 'YYYY-MM-DD HH24:MI')
                            END <= TO_DATE(TO_CHAR(SYSDATE, 'YYYY-MM-DD')||T0.STARTTIME_ENDTIME, 'YYYY-MM-DD HH24:MI')
                            )
                        THEN 0 ELSE (CASE WHEN LUNCH_TIME > 0 THEN LUNCH_TIME ELSE 0 END) END)
                        ELSE T0.PLAN_TIME END
                        AS DEFFERENCE_TIME,
                        CASE WHEN (T0.ENDTIME > '$inTime1' AND T0.STARTTIME < '$outTime1') OR T0.PLAN_DURATION > 0 OR $addonDate != 0 THEN
                            (ROUND(DATEDIFF(
                                    CASE
                                    WHEN $addonDate = 0 AND $plusDate != 0 THEN
                                        CASE
                                        WHEN TO_DATE(TO_CHAR(SYSDATE, 'YYYY-MM-DD')||T0.STARTTIME, 'YYYY-MM-DD HH24:MI') >= TO_DATE(TO_CHAR(SYSDATE, 'YYYY-MM-DD')||'$inTime1', 'YYYY-MM-DD HH24:MI') AND '05:00' <= '$inTime1' THEN
                                            TO_DATE(TO_CHAR(SYSDATE, 'YYYY-MM-DD')||T0.STARTTIME, 'YYYY-MM-DD HH24:MI')                                            
                                        WHEN T0.ENDTIME_STARTTIME >= '$inTime1' AND T0.STARTTIME_ENDTIME <= '$inTime1' THEN
                                            TO_DATE(TO_CHAR(SYSDATE, 'YYYY-MM-DD')||T0.ENDTIME_STARTTIME, 'YYYY-MM-DD HH24:MI')
                                        ELSE
                                            TO_DATE(TO_CHAR(SYSDATE, 'YYYY-MM-DD')||'$inTime1' , 'YYYY-MM-DD HH24:MI')
                                        END
                                    ELSE
                                    CASE
                                        WHEN CASE WHEN T0.STARTTIME < T0.STARTTIME_LIMIT AND T0.STARTTIME_LIMIT IS NOT NULL THEN T0.STARTTIME_LIMIT ELSE T0.STARTTIME END < '$inTime1' AND ( T0.IS_LATE IS NULL OR T0.IS_LATE = 0 ) THEN
                                            CASE
                                            WHEN T0.ENDTIME_STARTTIME >= '$inTime1' AND T0.STARTTIME_ENDTIME <= '$inTime1' THEN
                                                TO_DATE(TO_CHAR(SYSDATE, 'YYYY-MM-DD')||T0.ENDTIME_STARTTIME, 'YYYY-MM-DD HH24:MI')
                                            ELSE
                                                TO_DATE(TO_CHAR(SYSDATE, 'YYYY-MM-DD')||'$inTime1' , 'YYYY-MM-DD HH24:MI')
                                            END
                                        ELSE
                                            TO_DATE(TO_CHAR(SYSDATE, 'YYYY-MM-DD')||T0.STARTTIME , 'YYYY-MM-DD HH24:MI')
                                        END
                                    END,
                                    CASE WHEN $addonDate != 0 OR $plusDate != 0 THEN
                                        CASE
                                        WHEN CASE WHEN T0.ENDTIME > T0.ENDTIME_LIMIT AND T0.ENDTIME_LIMIT IS NOT NULL THEN TO_DATE(TO_CHAR(SYSDATE + T0.PLAN_DURATION, 'YYYY-MM-DD')||T0.ENDTIME_LIMIT, 'YYYY-MM-DD HH24:MI') ELSE TO_DATE(TO_CHAR(SYSDATE + T0.PLAN_DURATION, 'YYYY-MM-DD')||T0.ENDTIME, 'YYYY-MM-DD HH24:MI') END > TO_DATE(TO_CHAR(SYSDATE + $addonDate, 'YYYY-MM-DD')||'$outTime1', 'YYYY-MM-DD HH24:MI') AND $addonDate = 0 THEN
                                            TO_DATE(TO_CHAR(SYSDATE + $addonDate, 'YYYY-MM-DD')||'$outTime1', 'YYYY-MM-DD HH24:MI') 
                                        ELSE
                                            CASE
                                            WHEN CASE WHEN T0.ENDTIME > T0.ENDTIME_LIMIT AND T0.ENDTIME_LIMIT IS NOT NULL THEN T0.ENDTIME_LIMIT ELSE T0.ENDTIME END > '$outTime1' AND T0.ENDTIME_STARTTIME >= '$outTime1' AND T0.STARTTIME_ENDTIME <= '$outTime1' THEN                                        
                                                TO_DATE(TO_CHAR(SYSDATE + $addonDate, 'YYYY-MM-DD')||T0.STARTTIME_ENDTIME, 'YYYY-MM-DD HH24:MI')
                                            WHEN CASE WHEN T0.ENDTIME > T0.ENDTIME_LIMIT AND T0.ENDTIME_LIMIT IS NOT NULL THEN TO_DATE(TO_CHAR(SYSDATE + T0.PLAN_DURATION, 'YYYY-MM-DD')||T0.ENDTIME_LIMIT, 'YYYY-MM-DD HH24:MI') ELSE TO_DATE(TO_CHAR(SYSDATE + T0.PLAN_DURATION, 'YYYY-MM-DD')||T0.ENDTIME, 'YYYY-MM-DD HH24:MI') END > TO_DATE(TO_CHAR(SYSDATE + $addonDate, 'YYYY-MM-DD')||'$outTime1', 'YYYY-MM-DD HH24:MI') THEN
                                                TO_DATE(TO_CHAR(SYSDATE + $addonDate, 'YYYY-MM-DD')||'$outTime1', 'YYYY-MM-DD HH24:MI')
                                            ELSE
                                                TO_DATE(TO_CHAR(SYSDATE + T0.PLAN_DURATION, 'YYYY-MM-DD')||T0.ENDTIME, 'YYYY-MM-DD HH24:MI')
                                            END
                                        END
                                    ELSE
                                        CASE
                                        WHEN CASE WHEN T0.ENDTIME > T0.ENDTIME_LIMIT AND T0.ENDTIME_LIMIT IS NOT NULL THEN T0.ENDTIME_LIMIT ELSE T0.ENDTIME END > '$outTime1' THEN
                                            CASE WHEN TO_DATE(TO_CHAR(SYSDATE, 'YYYY-MM-DD')||'$outTime1' , 'YYYY-MM-DD HH24:MI') >= TO_DATE(TO_CHAR(SYSDATE, 'YYYY-MM-DD')||T0.STARTTIME_ENDTIME, 'YYYY-MM-DD HH24:MI') AND TO_DATE(TO_CHAR(SYSDATE, 'YYYY-MM-DD')||'$outTime1' , 'YYYY-MM-DD HH24:MI') <= TO_DATE(TO_CHAR(SYSDATE, 'YYYY-MM-DD')||T0.ENDTIME_STARTTIME, 'YYYY-MM-DD HH24:MI') 
                                            THEN TO_DATE(TO_CHAR(SYSDATE, 'YYYY-MM-DD')||T0.STARTTIME_ENDTIME, 'YYYY-MM-DD HH24:MI') ELSE TO_DATE(TO_CHAR(SYSDATE, 'YYYY-MM-DD')||'$outTime1' , 'YYYY-MM-DD HH24:MI') END
                                        ELSE
                                            TO_DATE(TO_CHAR(SYSDATE, 'YYYY-MM-DD')||T0.ENDTIME, 'YYYY-MM-DD HH24:MI') END
                                    END                                        
                                ))/60)
                            + CASE WHEN $isEarlyTimeToClean = 0 THEN
                              CASE WHEN 60 >= T0.EARLY_TIME AND T0.EARLY_TIME > 0 THEN T0.EARLY_TIME ELSE 0 END
                            ELSE
                              0
                            END
                            + CASE WHEN ".$isLateTimeToCleantime." = '1' THEN
                              T0.LATE_TIME
                            ELSE
                              0
                            END
                            - (CASE WHEN 
                                (CASE WHEN T0.STARTTIME < '$inTime1' AND ( T0.IS_LATE IS NULL OR T0.IS_LATE = 0 ) THEN
                                        TO_DATE(TO_CHAR(SYSDATE, 'YYYY-MM-DD')||'$inTime1' , 'YYYY-MM-DD HH24:MI')
                                    ELSE
                                        TO_DATE(TO_CHAR(SYSDATE, 'YYYY-MM-DD')||T0.STARTTIME , 'YYYY-MM-DD HH24:MI')
                                    END >= TO_DATE(TO_CHAR(SYSDATE, 'YYYY-MM-DD')||T0.STARTTIME_ENDTIME, 'YYYY-MM-DD HH24:MI') 
                                    AND  CASE
                                        WHEN T0.STARTTIME < '$inTime1' AND ( T0.IS_LATE IS NULL OR T0.IS_LATE = 0 ) THEN
                                            TO_DATE(TO_CHAR(SYSDATE, 'YYYY-MM-DD')||'$inTime1' , 'YYYY-MM-DD HH24:MI')
                                        ELSE
                                            TO_DATE(TO_CHAR(SYSDATE, 'YYYY-MM-DD')||T0.STARTTIME , 'YYYY-MM-DD HH24:MI')
                                        END <= TO_DATE(TO_CHAR(SYSDATE, 'YYYY-MM-DD')||T0.ENDTIME_STARTTIME, 'YYYY-MM-DD HH24:MI'))
                                OR (
                                    CASE
                                    WHEN T0.ENDTIME > '$outTime1' THEN
                                        CASE WHEN $addonDate != 0 
                                            THEN TO_DATE(TO_CHAR(SYSDATE - $addonDate, 'YYYY-MM-DD')||T0.ENDTIME , 'YYYY-MM-DD HH24:MI') 
                                            ELSE TO_DATE(TO_CHAR(SYSDATE, 'YYYY-MM-DD')||'$outTime1' , 'YYYY-MM-DD HH24:MI')   END  
                                    ELSE
                                        TO_DATE(TO_CHAR(SYSDATE, 'YYYY-MM-DD')||T0.ENDTIME , 'YYYY-MM-DD HH24:MI')
                                    END >= TO_DATE(TO_CHAR(SYSDATE, 'YYYY-MM-DD')||T0.STARTTIME_ENDTIME, 'YYYY-MM-DD HH24:MI') 
                                    AND 
                                    CASE
                                    WHEN T0.ENDTIME > '$outTime1' THEN
                                        CASE WHEN $addonDate != 0 
                                            THEN TO_DATE(TO_CHAR(SYSDATE - $addonDate, 'YYYY-MM-DD')||T0.ENDTIME , 'YYYY-MM-DD HH24:MI') 
                                            ELSE TO_DATE(TO_CHAR(SYSDATE, 'YYYY-MM-DD')||'$outTime1' , 'YYYY-MM-DD HH24:MI')   END  
                                    ELSE
                                        TO_DATE(TO_CHAR(SYSDATE, 'YYYY-MM-DD')||T0.ENDTIME , 'YYYY-MM-DD HH24:MI')
                                    END <= TO_DATE(TO_CHAR(SYSDATE, 'YYYY-MM-DD')||T0.ENDTIME_STARTTIME, 'YYYY-MM-DD HH24:MI')                                                            
                                )
                                OR (
                                    CASE
                                    WHEN T0.ENDTIME > '$outTime1' THEN
                                        CASE WHEN $addonDate != 0 
                                            THEN TO_DATE(TO_CHAR(SYSDATE - $addonDate, 'YYYY-MM-DD')||T0.ENDTIME , 'YYYY-MM-DD HH24:MI') 
                                            ELSE TO_DATE(TO_CHAR(SYSDATE, 'YYYY-MM-DD')||'$outTime1' , 'YYYY-MM-DD HH24:MI')   END  
                                    ELSE
                                        TO_DATE(TO_CHAR(SYSDATE, 'YYYY-MM-DD')||T0.ENDTIME , 'YYYY-MM-DD HH24:MI')
                                    END <= TO_DATE(TO_CHAR(SYSDATE, 'YYYY-MM-DD')||T0.ENDTIME_STARTTIME, 'YYYY-MM-DD HH24:MI')
                                    AND CASE
                                    WHEN T0.STARTTIME < '$inTime1' AND ( T0.IS_LATE IS NULL OR T0.IS_LATE = 0 ) THEN
                                        TO_DATE(TO_CHAR(SYSDATE, 'YYYY-MM-DD')||'$inTime1' , 'YYYY-MM-DD HH24:MI')
                                    ELSE
                                        TO_DATE(TO_CHAR(SYSDATE, 'YYYY-MM-DD')||T0.STARTTIME , 'YYYY-MM-DD HH24:MI')
                                    END >= TO_DATE(TO_CHAR(SYSDATE, 'YYYY-MM-DD')||T0.STARTTIME_ENDTIME, 'YYYY-MM-DD HH24:MI')
                                )
                                OR (
                                    CASE
                                    WHEN T0.STARTTIME < '$inTime1' AND ( T0.IS_LATE IS NULL OR T0.IS_LATE = 0 ) THEN
                                        TO_DATE(TO_CHAR(SYSDATE, 'YYYY-MM-DD')||'$inTime1' , 'YYYY-MM-DD HH24:MI')
                                    ELSE
                                        TO_DATE(TO_CHAR(SYSDATE, 'YYYY-MM-DD')||T0.STARTTIME , 'YYYY-MM-DD HH24:MI')
                                    END >= TO_DATE(TO_CHAR(SYSDATE, 'YYYY-MM-DD')||T0.ENDTIME_STARTTIME, 'YYYY-MM-DD HH24:MI')
                                )
                                OR (
                                    CASE
                                WHEN T0.ENDTIME > '$outTime1' THEN
                                    CASE WHEN $addonDate != 0 
                                        THEN TO_DATE(TO_CHAR(SYSDATE - $addonDate, 'YYYY-MM-DD')||T0.ENDTIME, 'YYYY-MM-DD HH24:MI') 
                                        ELSE TO_DATE(TO_CHAR(SYSDATE, 'YYYY-MM-DD')||'$outTime1' , 'YYYY-MM-DD HH24:MI')   END  
                                ELSE
                                    TO_DATE(TO_CHAR(SYSDATE, 'YYYY-MM-DD')||T0.ENDTIME , 'YYYY-MM-DD HH24:MI')
                                END <= TO_DATE(TO_CHAR(SYSDATE, 'YYYY-MM-DD')||T0.STARTTIME_ENDTIME, 'YYYY-MM-DD HH24:MI')
                                )
                            THEN (CASE WHEN $addonDate != 0 AND TO_DATE(TO_CHAR(SYSDATE, 'YYYY-MM-DD')||T0.ENDTIME_STARTTIME, 'YYYY-MM-DD HH24:MI') < TO_DATE(TO_CHAR(SYSDATE + $addonDate, 'YYYY-MM-DD')||'$outTime1', 'YYYY-MM-DD HH24:MI') THEN LUNCH_TIME ELSE 0 END) ELSE (CASE WHEN LUNCH_TIME > 0 THEN LUNCH_TIME ELSE 0 END) END)
                        ELSE 0 END AS CLEAR_TIME,
                        CASE WHEN (T0.ENDTIME > '$inTime1' AND T0.STARTTIME < '$outTime1') OR T0.PLAN_DURATION > 0 OR $addonDate != 0 THEN
                            (ROUND(DATEDIFF(
                                TO_DATE(TO_CHAR(SYSDATE, 'YYYY-MM-DD')||'$inTime1' , 'YYYY-MM-DD HH24:MI'),
                                TO_DATE(TO_CHAR(SYSDATE, 'YYYY-MM-DD')||'$outTime1' , 'YYYY-MM-DD HH24:MI')
                                + CASE WHEN $addonDate != 0 THEN $addonDate ELSE 0 END                                    
                            ))/60)
                            + CASE WHEN 60 >= T0.EARLY_TIME AND T0.EARLY_TIME > 0 THEN T0.EARLY_TIME ELSE 0 END
                            - (CASE WHEN 
                                (CASE WHEN T0.STARTTIME < '$inTime1' AND ( T0.IS_LATE IS NULL OR T0.IS_LATE = 0 ) THEN
                                        TO_DATE(TO_CHAR(SYSDATE, 'YYYY-MM-DD')||'$inTime1' , 'YYYY-MM-DD HH24:MI')
                                    ELSE
                                        TO_DATE(TO_CHAR(SYSDATE, 'YYYY-MM-DD')||T0.STARTTIME , 'YYYY-MM-DD HH24:MI')
                                    END >= TO_DATE(TO_CHAR(SYSDATE, 'YYYY-MM-DD')||T0.STARTTIME_ENDTIME, 'YYYY-MM-DD HH24:MI') 
                                    AND  CASE
                                        WHEN T0.STARTTIME < '$inTime1' AND ( T0.IS_LATE IS NULL OR T0.IS_LATE = 0 ) THEN
                                            TO_DATE(TO_CHAR(SYSDATE, 'YYYY-MM-DD')||'$inTime1' , 'YYYY-MM-DD HH24:MI')
                                        ELSE
                                            TO_DATE(TO_CHAR(SYSDATE, 'YYYY-MM-DD')||T0.STARTTIME , 'YYYY-MM-DD HH24:MI')
                                        END <= TO_DATE(TO_CHAR(SYSDATE, 'YYYY-MM-DD')||T0.ENDTIME_STARTTIME, 'YYYY-MM-DD HH24:MI'))
                                OR (
                                    CASE
                                    WHEN T0.ENDTIME > '$outTime1' THEN
                                        CASE WHEN $addonDate != 0 
                                            THEN TO_DATE(TO_CHAR(SYSDATE - $addonDate, 'YYYY-MM-DD')||T0.ENDTIME , 'YYYY-MM-DD HH24:MI') 
                                            ELSE TO_DATE(TO_CHAR(SYSDATE, 'YYYY-MM-DD')||'$outTime1' , 'YYYY-MM-DD HH24:MI')   END  
                                    ELSE
                                        TO_DATE(TO_CHAR(SYSDATE, 'YYYY-MM-DD')||T0.ENDTIME , 'YYYY-MM-DD HH24:MI')
                                    END >= TO_DATE(TO_CHAR(SYSDATE, 'YYYY-MM-DD')||T0.STARTTIME_ENDTIME, 'YYYY-MM-DD HH24:MI') 
                                    AND 
                                    CASE
                                    WHEN T0.ENDTIME > '$outTime1' THEN
                                        CASE WHEN $addonDate != 0 
                                            THEN TO_DATE(TO_CHAR(SYSDATE - $addonDate, 'YYYY-MM-DD')||T0.ENDTIME , 'YYYY-MM-DD HH24:MI') 
                                            ELSE TO_DATE(TO_CHAR(SYSDATE, 'YYYY-MM-DD')||'$outTime1' , 'YYYY-MM-DD HH24:MI')   END  
                                    ELSE
                                        TO_DATE(TO_CHAR(SYSDATE, 'YYYY-MM-DD')||T0.ENDTIME , 'YYYY-MM-DD HH24:MI')
                                    END <= TO_DATE(TO_CHAR(SYSDATE, 'YYYY-MM-DD')||T0.ENDTIME_STARTTIME, 'YYYY-MM-DD HH24:MI')                                                            
                                )
                                OR (
                                    CASE
                                    WHEN T0.ENDTIME > '$outTime1' THEN
                                        CASE WHEN $addonDate != 0 
                                            THEN TO_DATE(TO_CHAR(SYSDATE - $addonDate, 'YYYY-MM-DD')||T0.ENDTIME , 'YYYY-MM-DD HH24:MI') 
                                            ELSE TO_DATE(TO_CHAR(SYSDATE, 'YYYY-MM-DD')||'$outTime1' , 'YYYY-MM-DD HH24:MI')   END  
                                    ELSE
                                        TO_DATE(TO_CHAR(SYSDATE, 'YYYY-MM-DD')||T0.ENDTIME , 'YYYY-MM-DD HH24:MI')
                                    END <= TO_DATE(TO_CHAR(SYSDATE, 'YYYY-MM-DD')||T0.ENDTIME_STARTTIME, 'YYYY-MM-DD HH24:MI')
                                    AND CASE
                                    WHEN T0.STARTTIME < '$inTime1' AND ( T0.IS_LATE IS NULL OR T0.IS_LATE = 0 ) THEN
                                        TO_DATE(TO_CHAR(SYSDATE, 'YYYY-MM-DD')||'$inTime1' , 'YYYY-MM-DD HH24:MI')
                                    ELSE
                                        TO_DATE(TO_CHAR(SYSDATE, 'YYYY-MM-DD')||T0.STARTTIME , 'YYYY-MM-DD HH24:MI')
                                    END >= TO_DATE(TO_CHAR(SYSDATE, 'YYYY-MM-DD')||T0.STARTTIME_ENDTIME, 'YYYY-MM-DD HH24:MI')
                                )
                                OR (
                                    CASE
                                    WHEN T0.STARTTIME < '$inTime1' AND ( T0.IS_LATE IS NULL OR T0.IS_LATE = 0 ) THEN
                                        TO_DATE(TO_CHAR(SYSDATE, 'YYYY-MM-DD')||'$inTime1' , 'YYYY-MM-DD HH24:MI')
                                    ELSE
                                        TO_DATE(TO_CHAR(SYSDATE, 'YYYY-MM-DD')||T0.STARTTIME , 'YYYY-MM-DD HH24:MI')
                                    END >= TO_DATE(TO_CHAR(SYSDATE, 'YYYY-MM-DD')||T0.ENDTIME_STARTTIME, 'YYYY-MM-DD HH24:MI')
                                )
                                OR (
                                    CASE
                                WHEN T0.ENDTIME > '$outTime1' THEN
                                    CASE WHEN $addonDate != 0 
                                        THEN TO_DATE(TO_CHAR(SYSDATE - $addonDate, 'YYYY-MM-DD')||T0.ENDTIME , 'YYYY-MM-DD HH24:MI') 
                                        ELSE TO_DATE(TO_CHAR(SYSDATE, 'YYYY-MM-DD')||'$outTime1' , 'YYYY-MM-DD HH24:MI')   END  
                                ELSE
                                    TO_DATE(TO_CHAR(SYSDATE, 'YYYY-MM-DD')||T0.ENDTIME , 'YYYY-MM-DD HH24:MI')
                                END <= TO_DATE(TO_CHAR(SYSDATE, 'YYYY-MM-DD')||T0.STARTTIME_ENDTIME, 'YYYY-MM-DD HH24:MI')
                                )
                            THEN (CASE WHEN $addonDate != 0 THEN LUNCH_TIME ELSE 0 END) ELSE (CASE WHEN LUNCH_TIME > 0 THEN LUNCH_TIME ELSE 0 END) END)
                        ELSE 0 END AS UNCLEAR_TIME
                    FROM ( 
                        SELECT T0.*,
                            CASE WHEN T0.STARTTIME_LIMIT < '$inTime1' AND T0.STARTTIME_LIMIT IS NOT NULL 
                                THEN ROUND(DATEDIFF(
                                        TO_DATE(TO_CHAR(SYSDATE , 'YYYY-MM-DD')||T0.STARTTIME_LIMIT, 'YYYY-MM-DD HH24:MI'),
                                        TO_DATE(TO_CHAR(SYSDATE, 'YYYY-MM-DD')||'$inTime1', 'YYYY-MM-DD HH24:MI')
                                    ))/60
                                WHEN T0.STARTTIME < '$inTime1' AND T0.STARTTIME_LIMIT IS NULL 
                                THEN ROUND(DATEDIFF(
                                        TO_DATE(TO_CHAR(SYSDATE , 'YYYY-MM-DD')||T0.STARTTIME, 'YYYY-MM-DD HH24:MI'),
                                        TO_DATE(TO_CHAR(SYSDATE, 'YYYY-MM-DD')||'$inTime1', 'YYYY-MM-DD HH24:MI')
                                    ))/60
                                ELSE 0 END AS LATE_TIME,
                            CASE WHEN T0.ENDTIME_LIMIT > '$outTime1' AND T0.ENDTIME_LIMIT IS NOT NULL 
                                THEN ROUND(DATEDIFF(
                                        TO_DATE(TO_CHAR(SYSDATE, 'YYYY-MM-DD')||'$outTime1', 'YYYY-MM-DD HH24:MI'),
                                        TO_DATE(TO_CHAR(SYSDATE , 'YYYY-MM-DD')||T0.ENDTIME_LIMIT , 'YYYY-MM-DD HH24:MI')
                                    ))/60
                                WHEN T0.ENDTIME > '$outTime1' AND T0.ENDTIME_LIMIT IS NULL 
                                THEN ROUND(DATEDIFF(
                                        TO_DATE(TO_CHAR(SYSDATE, 'YYYY-MM-DD')||'$outTime1', 'YYYY-MM-DD HH24:MI'),
                                        TO_DATE(TO_CHAR(SYSDATE , 'YYYY-MM-DD')||T0.ENDTIME, 'YYYY-MM-DD HH24:MI')
                                    ))/60                                    
                                ELSE 0 END AS EARLY_TIME,
                            0 AS NIGHT_TIME
                        FROM (
                                SELECT 
                                    T2.PLAN_ID,
                                    T2.PLAN_DURATION,
                                    T2.IS_LATE,
                                    TO_CHAR(T4.START_TIME, 'HH24:MI') AS STARTTIME,
                                    TO_CHAR(T4.END_TIME, 'HH24:MI') AS STARTTIME_ENDTIME,
                                    TO_CHAR(T5.END_TIME, 'HH24:MI') AS ENDTIME,
                                    TO_CHAR(T5.START_TIME, 'HH24:MI') AS ENDTIME_STARTTIME,
                                    TO_CHAR(T4.STARTTIME_LIMIT, 'HH24:MI') AS STARTTIME_LIMIT,
                                    TO_CHAR(T5.ENDTIME_LIMIT, 'HH24:MI') AS ENDTIME_LIMIT,
                                    (ROUND(DATEDIFF(
                                        TO_DATE(TO_CHAR(SYSDATE, 'YYYY-MM-DD')||TO_CHAR(T4.START_TIME, 'HH24:MI'), 'YYYY-MM-DD HH24:MI'),
                                        TO_DATE(TO_CHAR(SYSDATE, 'YYYY-MM-DD')||TO_CHAR(T5.END_TIME, 'HH24:MI'), 'YYYY-MM-DD HH24:MI')  + T2.PLAN_DURATION
                                    )) / 60) - FNC_GET_TMS_PLAN_TIME(T1.PLAN_ID) AS LUNCH_TIME,
                                    ROUND(FNC_GET_TMS_PLAN_TIME(T1.PLAN_ID)/60, 2) AS PLAN_TIME
                                FROM TMS_EMPLOYEE_TIME_PLAN_HDR T0
                                    INNER JOIN TMS_EMPLOYEE_TIME_PLAN_DTL T1 ON T0.ID = T1.TIME_PLAN_ID
                                    INNER JOIN TMS_TIME_PLAN T2 ON T1.PLAN_ID = T2.PLAN_ID
                                    INNER JOIN (
                                        SELECT PLAN_ID, MIN(PLAN_DETAIL_ID) AS MIN_DETAIL_ID, MAX(PLAN_DETAIL_ID) AS MAX_DETAIL_ID FROM TMS_TIME_PLAN_DETAIL 
                                        GROUP BY PLAN_ID
                                    ) T3 ON T2.PLAN_ID = T3.PLAN_ID
                                    INNER JOIN  TMS_TIME_PLAN_DETAIL T4 ON T3.MIN_DETAIL_ID = T4.PLAN_DETAIL_ID
                                    INNER JOIN  TMS_TIME_PLAN_DETAIL T5 ON T3.MAX_DETAIL_ID = T5.PLAN_DETAIL_ID
                                WHERE TO_CHAR(T1.PLAN_DATE, 'YYYY-MM-DD') = '$balanceDate'
                                    AND T0.EMPLOYEE_ID = ". $employeeId ."
                        ) T0
                    ) T0";

                //echo $qs; die;
                // </editor-fold>

            $planDetail = $this->db->GetRow($qs);

                if ($planDetail && !$notPlanDepartmentCheck) {
                    $clearTime = $planDetail['CLEAR_TIME'];
                    $earlyTime = $planDetail['EARLY_TIME'];
                    $lateTime = $planDetail['LATE_TIME'];
                    $unClearTime = $planDetail['UNCLEAR_TIME'];
                    $diffTime = (float) $clearTime - (float) $planTime;
                    $diffTime = $diffTime < 60 && $diffTime < $defaultDefferenceTime ? $diffTime : 0;       

                    if (($nightTimeRange && $planDetail['PLAN_DURATION'] !== '0') || $addonDate) {                        
                        $date1 = Date::currentDate('Y-m-d');
                        $date2 = Date::nextDate($date1, $addPlusDate, 'Y-m-d');

                        if ($startTime <= $endTime) {
                            $nightStartD = (new DateTime($nightStartDate) <= new DateTime($startTime)) ? $date1 .' ' . $startTime : $date1 .' ' . $nightStartDate;
                            $nightEndD = (new DateTime($nightEndDate) <= new DateTime($endTime)) ? $date2 .' ' . $nightEndDate : $date2 .' ' . $endTime;
                        } else {
                            $nightEndD = (new DateTime($nightStartDate) <= new DateTime($startTime)) ? $date1 .' ' . $startTime : $date1 .' ' . $nightStartDate;
                            $nightStartD = (new DateTime($nightEndDate) <= new DateTime($endTime)) ? $date2 .' ' . $nightEndDate : $date2 .' ' . $endTime;
                        }

                        $nD2 = new DateTime($nightStartD);
                        $nD1 = new DateTime($nightEndD);

                        $interval = $nD2->diff($nD1);
                        $hour = $interval->format('%h');
                        $min = $interval->format('%i');
                        $second = $interval->format('%s');
                        $lunchTime = ($addonDate) ? 0 : $lunchTime;
                        $nightTime = (($hour * 60) + $min + ($second / 60)) - $lunchTime;

                    }
                } else {

                    $diffTime = 0;
                    $ucTime1 = new DateTime($startTime);
                    $ucTime2 = new DateTime($endTime);  

                    if (($nightTimeRange && new DateTime($nightStartDate) <= new DateTime($startTime)) || $addonDate) {
                        $ucTime2->modify($addPlusDate . ' day');

                        $interval = $ucTime1->diff($ucTime2);
                        $hour = $interval->format('%h');
                        $min = $interval->format('%i');
                        $second = $interval->format('%s');

                        if($hour == 0 && $addPlusDate == 0)
                            $unClearTime = $clearTime = 720;
                        elseif($hour == 0 && $addPlusDate == 1)
                            $unClearTime = $clearTime = 1440;
                        elseif($hour == 0 && $addPlusDate == 2)
                            $unClearTime = $clearTime = 2880;
                        else {
                            $unClearTime = $clearTime = (($hour * 60) + $min + ($second / 60));
                            $clearTime = $notPlanDepartmentCheck ? ($clearTime > $planTimeDefault ? $planTimeDefault : $clearTime) : $clearTime;
                        }
                    } else {
                        $interval = $ucTime1->diff($ucTime2);
                        $hour = $interval->format('%h');
                        $min = $interval->format('%i');
                        $second = $interval->format('%s');

                        $unClearTime = $clearTime = (($hour * 60) + $min + ($second / 60));
                        $clearTime = ($clearTime < $planTime) ? (float) $clearTime : $planTime;
                    }

                    if ($nightTimeRange || $addonDate) {
                        if ((new DateTime($nightStartDate) <= new DateTime($startTime) || new DateTime($startTime) >= new DateTime('18:00')) || $addonDate) {
                            $date1 = Date::currentDate('Y-m-d');
                            $date2 = Date::nextDate($date1, $addPlusDate, 'Y-m-d');

                            if ($startTime <= $endTime) {
                                $nightStartD = (new DateTime($nightStartDate) <= new DateTime($startTime)) ? $date1 .' ' . $startTime : $date1 .' ' . $nightStartDate;
                                $nightEndD = (new DateTime($nightEndDate) <= new DateTime($endTime)) ? $date2 .' ' . $nightEndDate : $date2 .' ' . $endTime;
                            } else {
                                $nightEndD = (new DateTime($nightStartDate) <= new DateTime($startTime)) ? $date1 .' ' . $startTime : $date1 .' ' . $nightStartDate;
                                $nightStartD = (new DateTime($nightEndDate) <= new DateTime($endTime)) ? $date2 .' ' . $nightEndDate : $date2 .' ' . $endTime;
                            }

                            $nD2 = new DateTime($nightStartD);
                            $nD1 = new DateTime($nightEndD);

                            $interval = $nD2->diff($nD1);
                            $hour = $interval->format('%h');
                            $min = $interval->format('%i');
                            $second = $interval->format('%s');
                            $lunchTime = ($addonDate) ? 0 : $lunchTime;

                            $nightTime = (($hour * 60) + $min + ($second / 60)) - $lunchTime;    
                        }
                    }

                    $realClearTime = $clearTime;                        
                    if ($realClearTime > 480) {
                        $cause4 = $realClearTime - 480 - $lunchTime;
                        $cause4 = $cause4 > 0 ? $cause4 : 0;
                    }
                }                                                                                  

                $data = array(
                    'TIME_BALANCE_HDR_ID' => $balanceTimeBalanceId,
                    'EMPLOYEE_ID'       => Input::param($row['EMPLOYEE_ID']),
                    'BALANCE_DATE' => $balanceDate,
                    'START_TIME' => ($startTime) ? $balanceDate. ' ' . $startTime.':00' : '',
                    'END_TIME' => ($endTime) ?  $endBalanceDate. ' ' . $endTime.':00' : '',
                    'CLEAN_TIME' => ($clearTime) ? $clearTime : $params['clearTime'],
                    'UNCLEAN_TIME' => ($unClearTime) ? $unClearTime : $row['UNCLEAR_TIME'],
                    'DEFFERENCE_TIME' => $params['defferenceTime'],
                    'ORIGINAL_DEFFERENCE_TIME' => $row['ORIGINAL_DEFFERENCE_TIME'],
                    'FAULT_TYPE' => $params['faultType'],
                    'IS_CONFIRMED' => '1',
                    'EMPLOYEE_KEY_ID'   => Input::param($row['EMPLOYEE_KEY_ID']),
                    'NIGHT_TIME' => ($nightTime) ? $nightTime : $row['NIGHT_TIME'],
                    'WFM_STATUS_ID' => $wfmStatusId,
                    'EARLY_TIME' => ($earlyTime) ? $earlyTime : (float) $row['EARLY_TIME'],
                    'LATE_TIME' => ($lateTime) ? $lateTime :  (float) $row['LATE_TIME'],
                    'CAUSE4' => (Config::getFromCache('CONFIG_TNA_HISHIGARVIN') && in_array(issetVar($row['DEPARTMENT_ID']), $overTime)) ? '210' : $cause4
                );

                $zorchilguiTime = isset($planDetail['DIFF_TIME']) ? $planDetail['DIFF_TIME'] : 0;
                $inStartTime = $balanceDate . ' ' . $params['inTime'] . ':00';
                $outEndTime = $balanceDate . ' ' . $params['outTime'] . ':00';

            } else {
                $diffTime = (float) $row['DEFFERENCE_TIME'];

                $data = array(
                    'TIME_BALANCE_HDR_ID' => $balanceTimeBalanceId,
                    'EMPLOYEE_ID'       => Input::param($row['EMPLOYEE_ID']),
                    'BALANCE_DATE' => $balanceDate,
                    'START_TIME' => ($startTime) ? $balanceDate. ' ' . $startTime.':00' : '',
                    'END_TIME' => ($endTime) ?  $balanceDate. ' ' . $endTime.':00' : '',
                    'CLEAN_TIME' => $row['CLEAR_TIME'],
                    'UNCLEAN_TIME' => $row['UNCLEAR_TIME'],
                    'DEFFERENCE_TIME' => $params['defferenceTime'],
                    'ORIGINAL_DEFFERENCE_TIME' => $row['ORIGINAL_DEFFERENCE_TIME'],
                    'FAULT_TYPE' => $params['faultType'],
                    'IS_CONFIRMED' => '1',
                    'EMPLOYEE_KEY_ID'   => Input::param($row['EMPLOYEE_KEY_ID']),
                    'NIGHT_TIME' => ($nightTime) ? $nightTime : $row['NIGHT_TIME'],
                    'WFM_STATUS_ID' => $wfmStatusId,
                    'EARLY_TIME' => ($earlyTime) ? $earlyTime : (float) $row['EARLY_TIME'],
                    'LATE_TIME' => ($lateTime) ? $lateTime :  (float) $row['LATE_TIME']
                    //'CAUSE4' => isset($params['cause_type_value'][4]) ? $params['cause_type_value'][4] : ($dataHdrExist['CAUSE4'] ? $dataHdrExist['CAUSE4'] : (Config::getFromCache('CONFIG_TNA_HISHIGARVIN') && in_array(issetVar($params['departmentId']), $overTime)) ? '210' : 0)
                );
            }

            $cleanTimeSum = $lateTimeSum = $unclearTimeSum = 0;
            $cause1 = 0;

            $repString = Config::getFromCache('tmsCleanExpression');
            $repString1 = Config::getFromCache('tmsUnCleanExpression');
            $LATE_TYPE = '['.Config::getFromCache('tmsLateType').']';
            $EARLY_TYPE = '['.Config::getFromCache('tmsEarlyType').']';
            $tmsNotOverTime = Config::getFromCache('tmsNotOverTime');
            $repString2 = $LATE_TYPE;
            $differenceExpression = Config::getFromCache('tmsDifferenceExpression');
            $tmsCause2Expression = Config::getFromCache('tmsCause2Expression');

            $causeEarlyTime = $causeLateTime = $sumCauseTime = $sumCauseTime1 = $tmsCause2Time = 0;                
            $causeTypeDtl = $this->db->GetAll("SELECT * FROM TNA_CAUSE_TYPE WHERE IS_ACTIVE = 1 ORDER BY CAUSE_TYPE_ID");
            (Array) $mergeArr = array();

            if ($causeTypeDtl) {
                foreach ($causeTypeDtl as $key => $row) {
                    $replaceCauseValue = (($params['cause_type_value'][$row['CAUSE_TYPE_ID']]) ? $params['cause_type_value'][$row['CAUSE_TYPE_ID']] : '0');
                    $repString = str_replace('[CAUSE'. $row['CAUSE_TYPE_ID'] .']', $replaceCauseValue, $repString);
                    $repString1 = str_replace('[CAUSE'. $row['CAUSE_TYPE_ID']  .']', $replaceCauseValue, $repString1);
                    $repString2 = str_replace('[CAUSE'. $row['CAUSE_TYPE_ID']  .']', $replaceCauseValue, $repString2);
                    $differenceExpression = str_replace('[CAUSE'. $row['CAUSE_TYPE_ID']  .']', $replaceCauseValue, $differenceExpression);
                    $tmsNotOverTime = str_replace('[CAUSE'. $row['CAUSE_TYPE_ID']  .']', $replaceCauseValue, $tmsNotOverTime);
                    $tmsCause2Expression = str_replace('[CAUSE'. $row['CAUSE_TYPE_ID']  .']', $replaceCauseValue, $tmsCause2Expression);

                    if ('[CAUSE'. $row['CAUSE_TYPE_ID']. ']' === $EARLY_TYPE) {
                        $causeEarlyTime = str_replace('[CAUSE'. $row['CAUSE_TYPE_ID']  .']', $params['cause_type_value'][$row['CAUSE_TYPE_ID']], $EARLY_TYPE);
                    }
                    if ('[CAUSE'. $row['CAUSE_TYPE_ID']. ']' === $LATE_TYPE) {
                        $causeLateTime = str_replace('[CAUSE'. $row['CAUSE_TYPE_ID']  .']', $params['cause_type_value'][$row['CAUSE_TYPE_ID']], $LATE_TYPE);
                    }

                    $mergeArr['CAUSE'. $row['CAUSE_TYPE_ID']] = $params['cause_type_value'][$row['CAUSE_TYPE_ID']];
                    $mergeArr['CAUSE'. $row['CAUSE_TYPE_ID'].'_DESC'] = $params['description'][$row['CAUSE_TYPE_ID']];
                }

                $sumCauseTime = eval('return ' . $repString . ';');
                $sumCauseTime1 = eval('return ' . $repString1 . ';');
                $tmsNotOverTimeSum = eval('return ' . $tmsNotOverTime . ';');
                $causeLateTime = eval('return ' . $repString2 . ';');
                $tmsCause2Time = eval('return ' . $tmsCause2Expression . ';');

                $differenceExpression = eval('return ' . $differenceExpression . ';') * (-1);
                $differenceExpression = $differenceExpression < 0 ? $differenceExpression * (-1) : $differenceExpression;
            }

            $fdiffTime = $diffTime + $differenceExpression;
            $fdiffTime = $fdiffTime > 0 ? 0 : $fdiffTime;

            $fearlyTime = (float) $data['EARLY_TIME'] - $causeEarlyTime;
            $fearlyTime = $fearlyTime < 0 ? $fearlyTime * (-1) : $fearlyTime;
            $fcleanTime = (float) $data['CLEAN_TIME'] + $sumCauseTime;
            $flateTime = $tmsCause2Time;

            if ($planData) {
                $fcleanTime = $fcleanTime >= $planTime ? $planTime : $fcleanTime;      
            }

            $param = array(
                'TIME_BALANCE_HDR_ID' => $data['TIME_BALANCE_HDR_ID'],
                'EMPLOYEE_ID' => $data['EMPLOYEE_ID'],
                'EMPLOYEE_KEY_ID' => $data['EMPLOYEE_KEY_ID'],
                'BALANCE_DATE' => $data['BALANCE_DATE'],
                'START_TIME' => $data['START_TIME'],
                'END_TIME' => $data['END_TIME'],
                'CLEAN_TIME' => $fcleanTime,
                'UNCLEAN_TIME' => (float) $data['UNCLEAN_TIME'] + $sumCauseTime1,
                'DEFFERENCE_TIME' => $fdiffTime,
                'ORIGINAL_DEFFERENCE_TIME' => $fdiffTime,
                'FAULT_TYPE' => $data['FAULT_TYPE'],
                'WFM_STATUS_ID' => $data['WFM_STATUS_ID'],
                'CONFIRMED_USER_ID' => $userKeyId,
                'IS_CONFIRMED' => '1',
                'NIGHT_TIME' => $data['NIGHT_TIME'],
                'EARLY_TIME' => $fearlyTime,
                //'LATE_TIME' => ((float) $data['LATE_TIME'] >= (float) $causeLateTime) ? (float) $data['LATE_TIME'] - (float) $causeLateTime : 0,
                'LATE_TIME' => $flateTime,
                'CAUSE4' => isset($data['CAUSE4']) ? $data['CAUSE4'] : null,
                'IS_ZERO_TIME' => isset($addonDate) ? '1' : '0',
                'ADD_DAY' => isset($addonDate) ? $addonDate : '0'
            );                

            $param['CAUSE2'] = $param['LATE_TIME'];
            unset($mergeArr['CAUSE2']);

            if($param['DEFFERENCE_TIME'] != 0) {
                $param['CLEAN_TIME'] = $param['CLEAN_TIME'] + $param['LATE_TIME'];
                $param['DEFFERENCE_TIME'] = $param['DEFFERENCE_TIME'] + $param['LATE_TIME'];
            }

            if($param['CLEAN_TIME'] == $planTime) {
                if(isset($mergeArr['CAUSE5']) && !empty($mergeArr['CAUSE5']))
                    $param['CLEAN_TIME'] = $param['CLEAN_TIME'] - $mergeArr['CAUSE5'];

                if(isset($mergeArr['CAUSE6']) && !empty($mergeArr['CAUSE6']))
                    $param['CLEAN_TIME'] = $param['CLEAN_TIME'] - $mergeArr['CAUSE6'];

                if(isset($mergeArr['CAUSE13']) && !empty($mergeArr['CAUSE13']))
                    $param['CLEAN_TIME'] = $param['CLEAN_TIME'] - $mergeArr['CAUSE13'];

                if(isset($mergeArr['CAUSE2']) && !empty($mergeArr['CAUSE2']))
                    $param['CLEAN_TIME'] = $param['CLEAN_TIME'] - $mergeArr['CAUSE2'];                    
            }

            if($param['CLEAN_TIME'] < $planTime) {
                $param['CAUSE4'] = 0;
            }                

            if($tmsNotOverTimeSum != 0) {
                $param['CAUSE4'] = 0;
                $param['NIGHT_TIME'] = 0;
            }

            if(Config::getFromCache('tmsEarlyTimeTolateTime') == '1')
                $param['LATE_TIME'] = $param['LATE_TIME'] + $param['EARLY_TIME'];                

            if($fdiffTime < 0) {
                unset($param['WFM_STATUS_ID']);
                unset($param['IS_CONFIRMED']);
            }		

            if(empty($dataHdrExist)) {
                if($mergeArr['CAUSE4'] == 0)
                    unset($mergeArr['CAUSE4']);     

                    $dataHdrExist = $this->db->GetOne("SELECT COUNT(*) FROM TNA_TIME_BALANCE_HDR WHERE TO_CHAR(BALANCE_DATE, 'YYYY-MM-DD') = '". $data['BALANCE_DATE'] ."' AND EMPLOYEE_ID = ". $data['EMPLOYEE_ID']);

                    $param = array_merge($param, $mergeArr);
                    if ($dataHdrExist === '0') {
                            $this->db->AutoExecute('TNA_TIME_BALANCE_HDR', $param);
                    } else {
                            unset($param['TIME_BALANCE_HDR_ID']);

                            $timeBalanceHdrId = $this->db->GetOne("SELECT TIME_BALANCE_HDR_ID FROM TNA_TIME_BALANCE_HDR WHERE TO_CHAR(BALANCE_DATE, 'YYYY-MM-DD') = '". $data['BALANCE_DATE'] ."' AND EMPLOYEE_ID = ". $data['EMPLOYEE_ID']);
                            $this->db->AutoExecute('TNA_TIME_BALANCE_HDR', $param, 'UPDATE', "TIME_BALANCE_HDR_ID = $timeBalanceHdrId");
                    }
            } else {

                unset($param['TIME_BALANCE_HDR_ID']);
                if ($mergeArr['CAUSE4'] == 0) {
                    unset($mergeArr['CAUSE4']);                    
                }

                $param = array_merge($param, $mergeArr);
                $this->db->AutoExecute('TNA_TIME_BALANCE_HDR', $param, 'UPDATE', "TIME_BALANCE_HDR_ID = ". $dataHdrExist['TIME_BALANCE_HDR_ID']);
            }                
        }

        return array('status' => 'success', 'message' => 'Амжилттай хадгаллаа');
    }        

    public function getIslockModel($params) {
        $sessionUserId = Ue::sessionUserId();
        $response = false;

        foreach ($params as $row) {
            if ($row['IS_LOCK'] === '1') {
                $response = true;
            }
        }

        return $response;
    }        

    public function saveBalanceDescriptionModel() {
        $dataHdrExist = $this->db->GetRow("SELECT TIME_BALANCE_HDR_ID FROM TNA_TIME_BALANCE_HDR WHERE TIME_BALANCE_HDR_ID = " . Input::post('balanceId'));

        if($dataHdrExist)
            $result = $this->db->AutoExecute('TNA_TIME_BALANCE_HDR', array('CAUSE' . Input::post('causeTypeId') . '_DESC' => Input::post('description')), 'UPDATE', "TIME_BALANCE_HDR_ID = " . Input::post('balanceId'));
        else
            return array(
                'status' => 'empty',
                'message' => ''
            );                

        if ($result) {
            return array(
                'status' => 'success',
                'message' => 'Амжилттай хадгалагдлаа.'
            );
        }
        return array(
            'status' => 'warning',
            'message' => 'Алдаа гарлаа.'
        );
    }        
    
    public function getMethodIdByMetaData($metaDataId) {
        $row = $this->db->GetRow("
            SELECT 
                MD.META_DATA_ID, 
                MD.META_DATA_CODE, 
                " . $this->db->IfNull("PL.PROCESS_NAME", "MD.META_DATA_NAME") . " AS META_DATA_NAME, 
                PL.INPUT_META_DATA_ID, 
                PL.WS_URL, 
                SL.SERVICE_LANGUAGE_CODE, 
                IMD.META_DATA_CODE AS INPUT_META_DATA_CODE, 
                PL.LABEL_WIDTH, 
                PL.COLUMN_COUNT, 
                PL.IS_TREEVIEW, 
                PL.WINDOW_TYPE, 
                PL.WINDOW_SIZE, 
                PL.WINDOW_WIDTH, 
                PL.WINDOW_HEIGHT, 
                PL.THEME, 
                PL.SUB_TYPE, 
                PL.ACTION_TYPE,
                PL.ACTION_BTN, 
                PL.IS_ADDON_PHOTO, 
                PL.IS_ADDON_FILE, 
                PL.IS_ADDON_COMMENT, 
                PL.IS_ADDON_LOG, 
                PL.IS_ADDON_RELATION, 
                PL.IS_ADDON_WFM_LOG, 
                PL.IS_ADDON_WFM_LOG_TYPE, 
                PL.REF_META_GROUP_ID,
                PL.THEME_CODE,
                PL.SKIN
            FROM META_BUSINESS_PROCESS_LINK PL 
                INNER JOIN META_DATA MD ON MD.META_DATA_ID = PL.META_DATA_ID   
                LEFT JOIN WEB_SERVICE_LANGUAGE SL ON SL.SERVICE_LANGUAGE_ID = PL.SERVICE_LANGUAGE_ID 
                LEFT JOIN META_DATA IMD ON IMD.META_DATA_ID = PL.INPUT_META_DATA_ID 
            WHERE PL.META_DATA_ID = $metaDataId");

        return $row;
    }        

    public function balanceGoogleMapViewModel() {
        $date = Input::post('date');

        (Array) $param = array(
            'systemMetaGroupId' => '1520905242931',
            'showQuery' => 0, 
            'ignorePermission' => 1,
            'criteria' => array(
                'employeeId' =>  array(
                    array(
                        'operator' => '=',
                        'operand' => Input::post('empId')
                    )
                ),
                'attendancedatetime' =>  array(
                    array(
                        'operator' => 'LIKE',
                        'operand' => '%'.date('H:i', strtotime($date)).'%'
                    )
                ),
                'attendanceday' =>  array(
                    array(
                        'operator' => 'LIKE',
                        'operand' => '%'.date('Y-m-d', strtotime($date)).'%'
                    )
                )
            )
        );
        $data = $this->ws->runResponse(GF_SERVICE_ADDRESS, Mddatamodel::$getDataViewCommand, $param);

        if ($data['status'] === 'success' && isset($data['result'])) {
            unset($data['result']['paging']);
            unset($data['result']['aggregatecolumns']);

            if(empty($data['result']))
                return '';
            else {
                if(empty($data['result'][0]['coordinate']))
                    return '';

                $coordinate = explode('|', $data['result'][0]['coordinate']);

                return array(
                    'long' => $coordinate[0],
                    'lat' => $coordinate[1]
                );
            }
        }
    }      

    public function getDeparmentListJtreeDataModel($departmentId = null, $note= false, $notParent = '', $rows = array(), $deep = 0) {
        if($deep === 0) {
            $criteriaValue = array(
                array(
                    'operator' => 'IS NULL',
                    'operand' => ''
                )
            );
            if ($departmentId) {
                $criteriaValue = array(
                    array(
                        'operator' => '=',
                        'operand' => $departmentId
                    )
                );
            }

            $departmentList = array();
            $this->load->model('mdmetadata', 'middleware/models/');
            $getMetaDataId = $this->model->getMetaDataByCodeModel('tmsDepartmentList');            

            if ($departmentId) {
                (Array) $param = array(
                    'systemMetaGroupId' => $getMetaDataId['META_DATA_ID'],
                    'showQuery' => 0, 
                    'ignorePermission' => 1, 
                    'criteria' => array(
                        'parentId' =>  array(
                            array(
                                'operator' => '=',
                                'operand' => $departmentId
                            )
                        )
                    )
                );
            } else {
                (Array) $param = array(
                    'systemMetaGroupId' => $getMetaDataId['META_DATA_ID'],
                    'showQuery' => 0, 
                    'ignorePermission' => 1,
                    /*'criteria' => array(
                        'parentId' =>  array(
                            array(
                                'operator' => 'IS NULL',
                                'operand' => ''
                            )
                        )
                    )*/
                );
            }

            //if($notParent !== '') {
            //    unset($param['criteria']['parentId']);
            //}

            if(Input::get('str') && Input::get('str') !== '___') {
                (Array) $param = array(
                    'systemMetaGroupId' => $getMetaDataId['META_DATA_ID'],
                    'showQuery' => 0, 
                    'ignorePermission' => 1, 
                    'criteria' => array(
                        'departmentname' =>  array(
                            array(
                                'operator' => 'like',
                                'operand' =>'%'.Input::get('str').'%'
                            )
                        )
                    )
                );
            }
            $data = $this->ws->runResponse(self::$gfServiceAddress, Mddatamodel::$getDataViewCommand, $param);

            unset($data['result']['paging']);
            unset($data['result']['aggregatecolumns']);

            if (count($data['result']) == 0) {
                $param['criteria'] = array(
                    'parentId' => array()
                );
                $data = $this->ws->runResponse(self::$gfServiceAddress, Mddatamodel::$getDataViewCommand, $param);
            }

            if ($data['status'] === 'success' && isset($data['result'])) {
                $result['total'] = (isset($data['result']['paging']) ? $data['result']['paging']['totalcount'] : 0);
                unset($data['result']['paging']);
                unset($data['result']['aggregatecolumns']);

                //if (count($data['result']) > 0) {
                if (false) {
                    $parentIds = array();
                    $childIds = array();

                    foreach ($data['result'] as $dk => $dataRow) {
                        if (isset($dataRow['childrecordcount'])) {
                            $data['result'][$dk]['state'] = 'closed';
                            $parentIds[] = $data['result'][$dk]['id'];
                        } else {
                            $data['result'][$dk]['state'] = 'open';
                        }

                        if ($data['result'][$dk]['parentid'] != '') {
                            $childIds[] = array(
                                'k' => $dk, 
                                'parentId' => $data['result'][$dk]['parentid']
                            );
                        }
                    }

                    if ($parentIds && $childIds) {

                        foreach ($childIds as $childId) {

                            if (in_array($childId['parentId'], $parentIds)) {
                                unset($data['result'][$childId['k']]);
                                $result['total'] = $result['total'] - 1;
                            }
                        }

                        $data['result'] = array_values($data['result']);
                    }
                }

                $departmentList = $data['result'];

                $arr1 = array();
                $arr2 = array();
                foreach ($departmentList as $deprow) {
                    if(empty($deprow['parentid']))
                        array_push($arr1, $deprow);
                    else
                        array_push($arr2, $deprow);
                }

                $departmentList = array_merge($arr1, $arr2);
            }
        } else {
            $departmentList = $rows;
        }

        (Array) $response = array();

        if ($departmentList) {
            foreach ($departmentList as $keyIndex => $row) {

                if (!array_find_val($departmentList, 'departmentid', $row['parentid'])) {
                //if (empty($row['parentid'])) {
                    $row['parentid'] = null;
                    //unset($departmentList[$keyIndex]);
                }

                if($row['parentid'] == $departmentId) {
                    $response[] = array(
                        'text'     => $row['code'] . ' - ' . $row['departmentname'],
                        'id'       => $row['departmentid'],
                        'icon'     => 'fa fa-folder text-orange-400',
                        'state'    => array(
                            'selected' => false,
                            'loaded'   => true,
                            'disabled' => false,
                            'opened'   => false
                        ),
                        'children' => $this->getDeparmentListJtreeDataModel($row['departmentid'], false, '', $departmentList, 1)
                    );                
                }
            }
        }
        return $response;
    }        

    public function getAllChildDepartmentModel($ids, $isChild = 1) {

        if ($isChild == 1) {
            $where = "START WITH D.DEPARTMENT_ID IN ($ids)
                 CONNECT BY D.PARENT_ID = PRIOR D.DEPARTMENT_ID";
        } else {
            $where = "WHERE D.DEPARTMENT_ID IN ($ids)"; 
        }

        $depIds = '';

        $this->db->StartTrans(); 
        $this->db->Execute(Ue::createSessionInfo());

        $result = $this->db->GetAll("
            SELECT 
                D.DEPARTMENT_ID AS DEPARTMENTID, 
                D.DEPARTMENT_NAME AS DEPARTMENTNAME 
            FROM VW_ORG_DEPARTMENT_SESSION D
            $where");

        $this->db->CompleteTrans();    

        if ($result) {
            foreach($result as $row) {
                $depIds .= $row['DEPARTMENTID'] . ',';
            }
        } else {
            $depIds = $ids; 
        }

        return rtrim($depIds, ',');
    }        

    public function getAllChildDepartmentByGroupIdModel($ids) {
        
        $this->db->StartTrans(); 
        $this->db->Execute(Ue::createSessionInfo());
        
        $result = $this->db->GetAll("
            SELECT 
                OD.DEPARTMENT_ID, 
                OD.DEPARTMENT_NAME, 
                OD.DISPLAY_ORDER 
            FROM VW_ORG_DEPARTMENT_SESSION OD 
                INNER JOIN VW_EMPLOYEE VE ON VE.DEPARTMENT_ID = OD.DEPARTMENT_ID 
            WHERE VE.EMPLOYEE_ID IN (SELECT EMPLOYEE_ID FROM TNA_EMPLOYEE_GROUP_CONFIG WHERE GROUP_ID IN (" . $ids . ")) 
            GROUP BY 
                OD.DEPARTMENT_ID, 
                OD.DEPARTMENT_NAME, 
                OD.DISPLAY_ORDER 
            ORDER BY LPAD(OD.DISPLAY_ORDER, 10) ASC");    
        
        $this->db->CompleteTrans();    
        
        if ($result) {
            return array('join' => Arr::implode_key(',', $result, 'DEPARTMENT_ID', true), 'array' => $result);
        } else {
            return array('join' => '', 'array' => array());
        }
    }

    public function getAllChildDepartment2Model($ids, $isChild = 1) {

        if ($isChild == 1) {
            $where = "START WITH D.DEPARTMENT_ID IN ($ids)
                 CONNECT BY D.PARENT_ID = PRIOR D.DEPARTMENT_ID";
        } else {
            $where = "WHERE D.DEPARTMENT_ID IN ($ids)"; 
        }

        $depIds = '';

        $this->db->StartTrans(); 
        $this->db->Execute(Ue::createSessionInfo());

        $result = $this->db->GetAll("
            SELECT 
                D.DEPARTMENT_ID, 
                D.DEPARTMENT_NAME 
            FROM VW_ORG_DEPARTMENT_SESSION D 
            $where 
            GROUP BY 
                D.DEPARTMENT_ID, 
                D.DEPARTMENT_NAME");

        $this->db->CompleteTrans();    

        if ($result) {
            foreach($result as $row) {
                $depIds .= $row['DEPARTMENT_ID'] . ',';
            }
        } else {
            $depIds = $ids; 
        }

        return array('join' => rtrim($depIds, ','), 'array' => $result);
    }        

    public function isLockBalanceQueryModel() {
        if (Input::postCheck('data')) {
            $param = $_POST['data'];
            $userId = Ue::sessionUserId();
            $userKeyId = Ue::sessionUserKeyId();
            $currentDate = Date::currentDate('Y-m-d H:i:s');
            $locker = false;

            foreach ($param as $k => $row) {
                $data = array(
                    'IS_LOCK' => '1',
                    'LOCK_END_DATE'  => Date::format("Y-m-d", Input::post('lockEndDate')),
                    'LOCK_USER_ID' => Session::get(SESSION_PREFIX . 'userid'),
                );

                $timeBalanceHdr = $this->db->GetRow("SELECT TIME_BALANCE_HDR_ID FROM TNA_TIME_BALANCE_HDR WHERE TIME_BALANCE_HDR_ID = " . Input::param($row['TIME_BALANCE_HDR_ID']));

                if($timeBalanceHdr) {
                    $this->db->AutoExecute('TNA_TIME_BALANCE_HDR', $data, 'UPDATE', " TIME_BALANCE_HDR_ID = " . Input::param($row['TIME_BALANCE_HDR_ID']));
                    $locker = true;
                }
            }

            if(!$locker)
                return array('status' => 'info', 'message' => 'Түгжих боломжгүй байна!');

        }
        return array('status' => 'success', 'message' => 'Амжилттай түгжигдлээ');
    }        

    public function deleteEmployeeBalanceModel() {

        $deleteId = array();
        $data = Input::postData();

        $message = 'Амжилттай устгалаа';
        $cellResult = false;

        try {
            $pushEmployee = array();
            $pushLockEmployee = array();

            foreach ($data['employeeId'] as $k => $employeeId) {
                $dateJoin = '';
                $paramData = array();

                foreach ($data['tnaEmployeeTimePlanId'][$employeeId] as $j => $planId) {
                    if ($data['fullTimeId'][$k] !== '' && $data['isSelectedCell'][$employeeId][$j] == '1') {
                        $dateJoin .= "'".Date::format('Y-m-d', $data['planDate'][$employeeId][$j])."',";
                        $paramData[$data['planCode'][$employeeId][$j]] =  null;
                    }
                }                    

                if ($data['fullTimeId'][$k] !== '' && $dateJoin !== '') {
                    $balanceEmployees = $this->db->GetAll("".
                        "SELECT TO_CHAR(TB.BALANCE_DATE, 'YYYY-MM-DD') AS BALANCE_DATE, HE.FIRST_NAME || ' - ' ||TO_CHAR(TB.BALANCE_DATE, 'YYYY-MM-DD') AS FIRST_NAME, TB.IS_LOCK
                        FROM TNA_TIME_BALANCE_HDR TB
                        INNER JOIN HRM_EMPLOYEE HE ON HE.EMPLOYEE_ID = TB.EMPLOYEE_ID
                        WHERE TB.EMPLOYEE_ID = ".$employeeId." AND TO_CHAR(TB.BALANCE_DATE, 'YYYY-MM-DD') IN (".rtrim($dateJoin, ',').")
                        ORDER BY TB.BALANCE_DATE"
                    );

                    if($balanceEmployees) {
                        foreach ($balanceEmployees as $balRow) {
                            if(!empty($balRow['IS_LOCK'])) {
                                array_push($pushLockEmployee, $balRow['FIRST_NAME']);
                            }
                            array_push($pushEmployee, $balRow['FIRST_NAME']);
                        }
                    }
                }

            }    

            if($pushLockEmployee)
                $response = array('status' => 'locker', 'data' => $pushLockEmployee);
            elseif($pushEmployee)
                $response = array('status' => 'success', 'data' => $pushEmployee);
            else
                $response = array('status' => 'empty', 'data' => '');

        } catch (Exception $ex) {
            $response = array('status' => 'warning', 'title' => 'Тайлбар', 'message' => Lang::line('msg_delete_error'), 'messageex' => $ex->msg, 'exception' => $ex);
        }

        return $response;
    }

    public function saveBalanceByProcessModel() {

        $_POST['responseType'] = 'returnRequestParams';

        $mdWs  = new Mdwebservice();
        $param = $mdWs->runProcess();

        $selectedRows = json_decode(Input::postNonTags('selectedRows'), true);
        $response = array('status' => 'success', 'message' => Lang::line('msg_save_success'));

        foreach ($selectedRows as $row) {
            
            if ($row['islock'] != '1') {
                $param['id'] = isset($row['TIME_BALANCE_HDR_ID']) ? $row['TIME_BALANCE_HDR_ID'] : $row['timebalancehdrid'];
                $param['balanceDate'] = isset($row['BALANCE_DATE']) ? $row['BALANCE_DATE'] : $row['balancedate'];
                $param['employeeId'] = isset($row['EMPLOYEE_ID']) ? $row['EMPLOYEE_ID'] : $row['employeeid'];
                $param['isUserConfirmed'] = '1';

                $result = $this->ws->runSerializeResponse(self::$gfServiceAddress, 'TnaTimeBalanceHDR_002', $param);

                if ($result['status'] == 'success') {
                    $response = array('status' => 'success', 'message' => Lang::line('msg_save_success'));
                } else {
                    $response = array('status' => 'error', 'message' => $this->ws->getResponseMessage($result));
                }
            }
        }

        return $response;
    }

    public function employeePlanListMainDataGridNewV2Model() {
        $result = $footerArr = $newBody = array();
        $whereString = $theader = $tbody = $tfooter = '';
        $isHishigArvin = Config::getFromCache('CONFIG_TNA_HISHIGARVIN');
        $filterRuleString = '';

        if (Input::postCheck('params')) {
            parse_str(Input::post('params'), $params);

            $isGolomt = $params['golomtView'];
            $golomtView = isset($params['golomtView']) ? (($params['golomtView']) ? 'Домайн' : 'Код') : 'Код';
            if (!empty($params['planYear']) && !empty($params['planMonth'])) {

                $employeers = array();
                $headerDays = array();
                $currentStatusNotIn = Config::getFromCache('tmsCurrentStatus');
                $statusNotIn = Config::getFromCache('tmsStatus');

                if (!empty($params['newDepartmentId']) || (isset($params['groupId']) && is_array($params['groupId']))) {

                    $bookTypeIds = '9024,9025,9026,9048';

                    if (is_array($params['newDepartmentId']) && count($params['newDepartmentId'])) {
                        $departmentIds = $params['newDepartmentId'];
                        $departmentIds = implode(',', $departmentIds);
                        $isChild = issetVar($params['isChild']);

                        $departmentIds = $this->getAllChildDepartmentModel($departmentIds, $isChild);

                        $whereString .= " AND VE.DEPARTMENT_ID IN ( " . $departmentIds . ") ";

                    } elseif ($params['newDepartmentId'] && Config::getFromCache('tmsCustomerCode') == 'gov') {
                        $isChild = issetVar($params['isChild']);

                        $departmentIds = $this->getAllChildDepartmentModel($params['newDepartmentId'], $isChild);

                        $whereString .= " AND VE.DEPARTMENT_ID IN ( " . $departmentIds . ") ";                            
                    } elseif (isset($params['groupId']) && is_array($params['groupId'])) {
                        $whereString .= " AND VE.EMPLOYEE_ID IN ( SELECT EMPLOYEE_ID FROM TNA_EMPLOYEE_GROUP_CONFIG WHERE GROUP_ID IN (" . implode(',', $params['groupId']) . ")) ";

                    }

                    $params['departmentId'] = !empty($departmentIds) ? explode(',', $departmentIds) : '';

                    $paramDepartmentIds = $params['departmentId'];
                    $userId = Ue::sessionUserId();

                    (Array) $departmentId = array();

                    if (isset($params['positionId']) && is_array($params['positionId'])) {
                        $whereString .= " AND VE.POSITION_ID IN (" . implode(',', $params['positionId']) . ")";
                    }

                    if (isset($params['positionGroupId']) && $params['positionGroupId'] != '') {
                        $whereString .= " AND VE.POSITION_GROUP_ID = " . $params['positionGroupId'];
                    }

                    if (isset($params['employeeStatus'])) {
                        if (empty($params['employeeStatus']) === false) {
                            $employeeStatusId = implode(',', $params['employeeStatus']);
                            $whereString .= " AND ek.STATUS_ID IN (". $employeeStatusId .")";
                        }
                    }

                    if (isset($params['stringValue'])) {
                        if (empty($params['stringValue']) === false && $params['stringValue'] != '') {

                            if(strpos($params['stringValue'], '.') === false) {
                                $whereString .= " AND (LOWER(LAST_NAME) LIKE LOWER('%" . $params['stringValue'] . "%') OR LOWER(FIRST_NAME) LIKE LOWER('%" . $params['stringValue'] . "%') OR LOWER(CODE) LIKE LOWER('%" . $params['stringValue'] . "%') OR LOWER(REGISTER_NUMBER) LIKE LOWER('%" . $params['stringValue'] . "%')) ";
                            } else {
                                $strexplode = explode('.', $params['stringValue']);
                                $whereString .= " AND (LOWER(LAST_NAME) LIKE LOWER('%" . $strexplode[0] . "%') AND LOWER(FIRST_NAME) LIKE LOWER('%" . $strexplode[1] . "%')) ";
                            }
                        }
                    }

                    if (Input::postCheck('filterRules')) {
                        $filterRules = json_decode(Str::cp1251_utf8($_POST['filterRules']), true);                
                        if ($filterRules) {
                            foreach ($filterRules as $rule) {
                                $field = $rule['field'];
                                $value = Input::param(Str::lower($rule['value']));
                                if (!empty($value)) {
                                    if ($field === 'employeename') {
                                        $whereString .= " AND (LOWER(VE.FIRST_NAME) LIKE '%$value%')";
                                    } elseif ($field === 'employeeposition') {
                                        $whereString .= " AND (LOWER(VE.POSITION_NAME) LIKE '%$value%')";
                                    }
                                }
                            }
                        }
                    }                        

                    $causeString1 = '';
                    $leftJoin = '';

                    (String) $tableColumn1 = $tableColumn2 = $tableColumn3 = '';

                    $resultCountt = $this->db->GetOne("SELECT COUNT(ID) AS COUNTT FROM TNA_EMPLOYEE_PLAN_OWNER WHERE USER_ID = $userId");

                    if ((int) $resultCountt != 0) {
                        $resultDepartment = $this->db->GetAll("SELECT DISTINCT DEPARTMENT_ID FROM TNA_EMPLOYEE_PLAN_OWNER WHERE USER_ID = $userId");
                        foreach ($resultDepartment as $key => $deparment) {
                            if (!in_array($deparment['DEPARTMENT_ID'], $departmentId)) {
                                array_push($departmentId, $deparment['DEPARTMENT_ID']);
                            }
                        }
                        $response = array('CHECK' => '1', 'departmentIds' => $departmentId);
                    }

                    (Array) $departmentIdArr = array();

                    if (sizeof($departmentId) > 0) {
                        foreach ($paramDepartmentIds as $depart) {
                            if (in_array($depart, $departmentId)) {
                                array_push($departmentIdArr, $depart);
                            }
                        }
                    } else {
                        $departmentIdArr = $paramDepartmentIds;
                    }

                        if (empty($departmentIdArr)) {
                            $causeString1 .= " AND VE.EMPLOYEE_ID IN (SELECT EMPLOYEE_ID FROM TNA_EMPLOYEE_GROUP_CONFIG WHERE GROUP_ID IN (" . implode(',', $params['groupId']) . "))";
                            $departmentList = $this->db->GetAll("SELECT DISTINCT OD.DEPARTMENT_ID, OD.DEPARTMENT_NAME, OD.DISPLAY_ORDER  
                                                                FROM ORG_DEPARTMENT OD
                                                                INNER JOIN VW_EMPLOYEE VE ON VE.DEPARTMENT_ID = OD.DEPARTMENT_ID
                                                                WHERE VE.EMPLOYEE_ID IN (SELECT EMPLOYEE_ID FROM TNA_EMPLOYEE_GROUP_CONFIG WHERE GROUP_ID IN (" . implode(',', $params['groupId']) . "))
                                                                ORDER BY LPAD(OD.DISPLAY_ORDER, 10) ASC");    

                            $departmentIds = Arr::implode_key(',', $departmentList, 'DEPARTMENT_ID', true);
                        } else {
                            $departmentIds = implode(',', $departmentIdArr);
                            if((isset($params['groupId']) && is_array($params['groupId']))) {
                                $causeString1 .= " AND VE.EMPLOYEE_ID IN (SELECT EMPLOYEE_ID FROM TNA_EMPLOYEE_GROUP_CONFIG WHERE GROUP_ID IN (" . implode(',', $params['groupId']) . "))";
                            }
                            $departmentList = $this->db->GetAll("SELECT DEPARTMENT_ID, DEPARTMENT_NAME FROM ORG_DEPARTMENT WHERE DEPARTMENT_ID IN (" . $departmentIds . ") ORDER BY LPAD(DISPLAY_ORDER, 10) ASC");
                        }

                        $leftJoinDay = $leftJoinAttr = $groupBy = '';
                            $days = cal_days_in_month(CAL_GREGORIAN, intval($params['planMonth']), intval($params['planYear'])); // 31, 30, 29, 28
                            for ($iday = 1; $iday <= $days; $iday ++ ) {
                                $dayAddin = $iday;

                                if($iday < 10)
                                    $rday = '0'.$iday;
                                else
                                    $rday = $iday;

                                $leftJoinAttr .= 
                                        "
                                        ROUND(tem$iday.PLAN_TIME/60, 2) AS PLAN_TIME_$iday, 
                                        tem$iday.PLAN_ID AS PLAN_ID_$iday,
                                        tem$iday.SHORT_NAME AS SHORT_NAME_$iday,
                                        tem$iday.COLOR AS COLOR_$iday,
                                    ";
//                                    $leftJoinAttr .= 
//                                            "
//                                            ROUND(tem$iday.PLAN_TIME/60, 2) AS PLAN_TIME_$iday, 
//                                            tem$iday.PLAN_ID AS PLAN_ID_$iday,
//                                            tem$iday.SHORT_NAME AS SHORT_NAME_$iday,
//                                            CASE
//                                              WHEN REST$iday.EMPLOYEE_KEY_ID IS NULL THEN tem$iday.COLOR
//                                              ELSE '#DFF0D8'
//                                            END AS COLOR_$iday,
//                                        ";
                                //$groupBy .= "tem$iday.PLAN_TIME, tem$iday.PLAN_ID, tem$iday.SHORT_NAME, tem$iday.COLOR, REST$iday.EMPLOYEE_KEY_ID, ";
                                //$groupBy .= "tem$iday.PLAN_TIME, tem$iday.PLAN_ID, tem$iday.SHORT_NAME, tem$iday.COLOR, ";

                                $leftJoinDay .= " LEFT JOIN (
                                                    SELECT
                                                        pl.PLAN_ID,
                                                        pl.COLOR, 
                                                        pl.SHORT_NAME,
                                                        FNC_GET_TMS_PLAN_TIME(pl.PLAN_ID) PLAN_TIME
                                                    FROM TMS_TIME_PLAN pl
                                                ) tem$iday ON tem$iday.PLAN_ID = TETPH.D$dayAddin ";

                                /*$leftJoinDay .= " LEFT JOIN (
                                                    SELECT LMR.EMPLOYEE_KEY_ID
                                                    FROM LM_REST_EMPLOYEE LMR WHERE TO_DATE('".$params['planYear']."-".$params['planMonth']."-".$rday."','YYYY-MM-DD') BETWEEN LMR.START_DATE AND LMR.END_DATE
                                                ) REST$iday ON REST$iday.EMPLOYEE_KEY_ID = VE.EMPLOYEE_KEY_ID";*/
                            }

                            $monthLimit = Config::getFromCache('tmsMonthFilter') || Config::getFromCache('tmsMonthFilter') == '0' ? Config::getFromCache('tmsMonthFilter') : '30';
                            $tmsPlanPreview = "((TO_CHAR(WORK_END_DATE, 'YYYY-MM') >= '" . $params['planYear'] ."-". $params['planMonth'] . "') OR WORK_END_DATE IS NULL)";

                            $tmsCustomerCode = Config::getFromCache('tmsCustomerCode');

                            $tmsPlanPreview .= '  OR (  TO_CHAR(WORK_END_DATE,\'YYYY-MM\') >= \'' . $params['planYear'] .'-'. $params['planMonth'] . '\' AND  WORK_END_DATE IS NOT NULL)';

                            if (Config::getFromCache('tmsPlanPreview') == '1') {
                                $tmsPlanPreview = "((TO_CHAR(WORK_END_DATE + $monthLimit, 'YYYY-MM') >= '" . $params['planYear'] ."-". $params['planMonth'] . "') OR WORK_END_DATE IS NULL)";
                            }

                            if ($tmsCustomerCode === 'khaanbank') {
                                $tmsPlanPreview .= '  OR (  TO_CHAR(WORK_END_DATE,\'YYYY-MM\') >= \'' . $params['planYear'] .'-'. $params['planMonth'] . '\' AND  WORK_END_DATE IS NOT NULL)';
                            }                 

                            (String) $tableColumn2 = '';
                            (String) $orderColumn = '';

                            if ($tmsCustomerCode == 'gov') {
                                $tableColumn2 = ", VE.DEP_ORDER, VE.POS_ORDER, VE.WORK_START_DATE ";
                                $orderColumn = "ORDER BY LPAD(DEP_ORDER, 10), LPAD(POS_ORDER, 10), WORK_START_DATE ASC";
                            }   


                            $autoNumber = 1;
                            $employeeCount = $this->db->GetOne("SELECT COUNT(tem.EMPLOYEE_ID) FROM (
                                                                SELECT 
                                                                    VE.EMPLOYEE_ID,
                                                                    VE.EMPLOYEE_KEY_ID,
                                                                    VE.LAST_NAME,
                                                                    VE.FIRST_NAME,
                                                                    VE.CODE,
                                                                    VE.STATUS_NAME,
                                                                    VE.POSITION_NAME,
                                                                    VE.POSITION_KEY_ID,
                                                                    VE.EMPLOYEE_PICTURE, 
                                                                    VE.DEPARTMENT_ID, 
                                                                    VE.DEPARTMENT_NAME,
                                                                    TETPH.ID AS FULL_TIME_ID,
                                                                    TETPH.FULL_TIME,
                                                                    TETPH.D1,TETPH.D2,TETPH.D3,TETPH.D4,TETPH.D5,TETPH.D6,TETPH.D7,TETPH.D8,TETPH.D9,TETPH.D10,
                                                                    TETPH.D11,TETPH.D12,TETPH.D13,TETPH.D14,TETPH.D15,TETPH.D16,TETPH.D17,TETPH.D18,TETPH.D19,TETPH.D20,
                                                                    TETPH.D21,TETPH.D22,TETPH.D23,TETPH.D24,TETPH.D25,TETPH.D26,TETPH.D27,TETPH.D28,TETPH.D29,TETPH.D30,
                                                                    TETPH.D31, ek.STATUS_ID, ek.CURRENT_STATUS_ID $tableColumn2
                                                                FROM VW_TMS_EMPLOYEE VE
                                                                INNER JOIN ( 
                                                                    SELECT
                                                                    MAX(EMPLOYEE_KEY_ID) AS EMPLOYEE_KEY_ID
                                                                    FROM
                                                                    HRM_EMPLOYEE_KEY
                                                                    WHERE ( 
                                                                          ".$tmsPlanPreview."
                                                                        )
                                                                    GROUP BY
                                                                       EMPLOYEE_ID
                                                                ) ACTIVE_HEK ON ACTIVE_HEK.EMPLOYEE_KEY_ID = VE.EMPLOYEE_KEY_ID       
                                                                LEFT JOIN ( 
                                                                    SELECT
                                                                    BB.NEW_EMPLOYEE_KEY_ID AS EMPLOYEE_KEY_ID,
                                                                    '0' AS LIMITLESS
                                                                    FROM HCM_LABOUR_BOOK AA
                                                                    INNER JOIN HCM_LABOUR_BOOK_DTL BB ON AA.ID = BB.BOOK_ID
                                                                    INNER JOIN HRM_EMPLOYEE_KEY LK ON LK.EMPLOYEE_KEY_ID = BB.NEW_EMPLOYEE_KEY_ID
                                                                    WHERE AA.BOOK_TYPE_ID IN ($bookTypeIds) AND LK.CURRENT_STATUS_ID NOT IN (1, 3) 
                                                                    UNION ALL
                                                                    SELECT
                                                                    BB.NEW_EMPLOYEE_KEY_ID AS EMPLOYEE_KEY_ID,
                                                                    '1' AS LIMITLESS
                                                                    FROM HCM_LABOUR_BOOK AA
                                                                    INNER JOIN HCM_LABOUR_BOOK_DTL BB ON AA.ID = BB.BOOK_ID
                                                                    INNER JOIN HRM_EMPLOYEE_KEY LK ON LK.EMPLOYEE_KEY_ID = BB.NEW_EMPLOYEE_KEY_ID
                                                                    WHERE AA.BOOK_TYPE_ID IN ($bookTypeIds) AND TO_CHAR(BB.START_DATE + $monthLimit, 'YYYY-MM') >= '" . $params['planYear'] ."-". $params['planMonth'] . "' AND TO_CHAR(BB.START_DATE - $monthLimit, 'YYYY-MM') <= '" . $params['planYear'] ."-". $params['planMonth'] . "' AND LK.CURRENT_STATUS_ID NOT IN (1, 3)
                                                                ) LAB_HEK ON LAB_HEK.EMPLOYEE_KEY_ID = ACTIVE_HEK.EMPLOYEE_KEY_ID                                                                                                                                     
                                                                INNER JOIN HRM_EMPLOYEE_KEY ek ON ACTIVE_HEK.EMPLOYEE_KEY_ID = ek.EMPLOYEE_KEY_ID
                                                                LEFT JOIN TMS_EMPLOYEE_TIME_PLAN_HDR TETPH ON TETPH.YEAR_ID = " . $params['planYear'] . " AND TETPH.MONTH_ID = " . $params['planMonth'] . " AND TETPH.EMPLOYEE_ID = VE.EMPLOYEE_ID
                                                                $leftJoin
                                                                WHERE ek.STATUS_ID NOT IN ($statusNotIn) AND (CASE
                                                                    WHEN LAB_HEK.EMPLOYEE_KEY_ID IS NULL THEN 0
                                                                    WHEN LAB_HEK.EMPLOYEE_KEY_ID IS NOT NULL AND LAB_HEK.LIMITLESS = '0' THEN 1
                                                                    ELSE 2
                                                                END) IN (0,2) AND ek.CURRENT_STATUS_ID NOT IN ($currentStatusNotIn)
                                                                    " . $whereString . $causeString1 . "
                                                                ) tem");

                            $queryString = "SELECT * FROM (
                                SELECT 
                                    VE.EMPLOYEE_ID,
                                    VE.EMPLOYEE_KEY_ID,
                                    VE.LAST_NAME,
                                    VE.FIRST_NAME,
                                    VE.CODE,
                                    VE.STATUS_NAME,
                                    VE.POSITION_NAME,
                                    VE.POSITION_KEY_ID,
                                    VE.EMPLOYEE_PICTURE, 
                                    VE.DEPARTMENT_ID, 
                                    VE.DEPARTMENT_NAME,
                                    VE.REGISTER_NUMBER,
                                    TETPH.ID AS FULL_TIME_ID,
                                    TETPH.FULL_TIME,
                                    TETPH.D1,TETPH.D2,TETPH.D3,TETPH.D4,TETPH.D5,TETPH.D6,TETPH.D7,TETPH.D8,TETPH.D9,TETPH.D10,
                                    TETPH.D11,TETPH.D12,TETPH.D13,TETPH.D14,TETPH.D15,TETPH.D16,TETPH.D17,TETPH.D18,TETPH.D19,TETPH.D20,
                                    TETPH.D21,TETPH.D22,TETPH.D23,TETPH.D24,TETPH.D25,TETPH.D26,TETPH.D27,TETPH.D28,TETPH.D29,TETPH.D30,
                                    $leftJoinAttr
                                    TETPH.D31, ek.STATUS_ID, ek.CURRENT_STATUS_ID,
                                    ". $this->db->IfNull('TETPH.ID', 'null') ." AS IS_EXIST $tableColumn2
                                FROM VW_TMS_EMPLOYEE VE
                                INNER JOIN ( 
                                    SELECT
                                    MAX(EMPLOYEE_KEY_ID) AS EMPLOYEE_KEY_ID
                                    FROM
                                    HRM_EMPLOYEE_KEY
                                    WHERE (
                                            ".$tmsPlanPreview."
                                          )
                                    GROUP BY
                                       EMPLOYEE_ID
                                ) ACTIVE_HEK ON ACTIVE_HEK.EMPLOYEE_KEY_ID = VE.EMPLOYEE_KEY_ID                     
                                LEFT JOIN ( 
                                    SELECT
                                    BB.NEW_EMPLOYEE_KEY_ID AS EMPLOYEE_KEY_ID,
                                    '0' AS LIMITLESS
                                    FROM HCM_LABOUR_BOOK AA
                                    INNER JOIN HCM_LABOUR_BOOK_DTL BB ON AA.ID = BB.BOOK_ID
                                    INNER JOIN HRM_EMPLOYEE_KEY LK ON LK.EMPLOYEE_KEY_ID = BB.NEW_EMPLOYEE_KEY_ID
                                    WHERE AA.BOOK_TYPE_ID IN ($bookTypeIds) AND LK.CURRENT_STATUS_ID NOT IN (1, 3)
                                    UNION ALL
                                    SELECT
                                    BB.NEW_EMPLOYEE_KEY_ID AS EMPLOYEE_KEY_ID,
                                    '1' AS LIMITLESS
                                    FROM HCM_LABOUR_BOOK AA
                                    INNER JOIN HCM_LABOUR_BOOK_DTL BB ON AA.ID = BB.BOOK_ID
                                    INNER JOIN HRM_EMPLOYEE_KEY LK ON LK.EMPLOYEE_KEY_ID = BB.NEW_EMPLOYEE_KEY_ID
                                    WHERE AA.BOOK_TYPE_ID IN ($bookTypeIds) AND TO_CHAR(BB.START_DATE + $monthLimit, 'YYYY-MM') >= '" . $params['planYear'] ."-". $params['planMonth'] . "' AND TO_CHAR(BB.START_DATE - $monthLimit, 'YYYY-MM') <= '" . $params['planYear'] ."-". $params['planMonth'] . "' AND LK.CURRENT_STATUS_ID NOT IN (1, 3)
                                ) LAB_HEK ON LAB_HEK.EMPLOYEE_KEY_ID = ACTIVE_HEK.EMPLOYEE_KEY_ID                                                                                      
                                INNER JOIN HRM_EMPLOYEE_KEY ek ON ACTIVE_HEK.EMPLOYEE_KEY_ID = ek.EMPLOYEE_KEY_ID
                                LEFT JOIN TMS_EMPLOYEE_TIME_PLAN_HDR TETPH ON TETPH.YEAR_ID = " . $params['planYear'] . " AND TETPH.MONTH_ID = " . $params['planMonth'] . " AND TETPH.EMPLOYEE_ID = VE.EMPLOYEE_ID
                                $leftJoin
                                $leftJoinDay
                                WHERE ek.STATUS_ID NOT IN ($statusNotIn) AND (CASE
                                        WHEN LAB_HEK.EMPLOYEE_KEY_ID IS NULL THEN 0
                                        WHEN LAB_HEK.EMPLOYEE_KEY_ID IS NOT NULL AND LAB_HEK.LIMITLESS = '0' THEN 1
                                        ELSE 2
                                    END) IN (0,2) AND ek.CURRENT_STATUS_ID NOT IN ($currentStatusNotIn)
                                    " . $whereString . $causeString1 . " 
                                ORDER BY ".($tableColumn2 ? ltrim($tableColumn2, ',') : 'VE.DEPARTMENT_NAME, VE.FIRST_NAME, VE.POSITION_NAME')." ASC ) TEMP $orderColumn";

                            $page = Input::postCheck('page') ? Input::post('page') : 1;
                            $rows = Input::postCheck('rows') ? Input::post('rows') : (Config::getFromCache('tmsPageNumber') ? Config::getFromCache('tmsPageNumber') : 50);
                            $offset = ($page - 1) * $rows;                                

                            // echo $queryString; die;
                            $employeers = $this->db->SelectLimit($queryString, $rows, $offset);
                            $employeers = isset($employeers->_array) ? $employeers->_array : array();
                            //pa($employeers);

                            if ($employeeCount > 0) {
                                $selectHoliday = "
                                SELECT 
                                    START_DATE, 
                                    END_DATE, 
                                    HOLIDAY_NAME
                                FROM 
                                    (
                                        (
                                            SELECT DTL.START_DATE, DTL.END_DATE, HDR.ACTIVITY_NAME AS HOLIDAY_NAME
                                            FROM HRM_ACTIVITY_HDR HDR
                                            INNER JOIN HRM_ACTIVITY_DTL DTL ON DTL.ACTIVITY_HDR_ID = HDR.ACTIVITY_HDR_ID
                                            INNER JOIN HRM_ACTIVITY_ATTENDEE ATT ON ATT.ACTIVITY_HDR_ID = HDR.ACTIVITY_HDR_ID
                                            WHERE  END_DATE IS NOT NULL
                                        )
                                        UNION  (
                                            SELECT START_DATE, END_DATE, HOLIDAY_NAME
                                            FROM  LM_HOLIDAY 
                                            WHERE  END_DATE IS NOT NULL
                                        )
                                    )";
                                $holidays = $this->db->GetAll($selectHoliday);

                                foreach ($departmentList as $k => $department) {
                                    $depIndex = 1;
                                    $planDtlGroup = array();

                                    $days = self::getWorkingDateV2Model(array('planMonth'=>$params['planMonth'], 'planYear'=>$params['planYear'], 'tableName' => 'TNA_EMPLOYEE_TIME_PLAN'), $holidays);

                                    foreach ($employeers as $i => $employee) {
                                        if ($department['DEPARTMENT_ID'] === $employee['DEPARTMENT_ID']) {

                                            if (!empty($employee['FULL_TIME_ID'])) {
                                                $planDtlDataByPlan = $this->db->GetAll("SELECT 'D'||LTRIM(TO_CHAR(PLAN_DATE, 'DD'), '0') AS DATE_STR, PLAN_ID ".
                                                            "FROM TMS_EMPLOYEE_TIME_PLAN_DTL ".
                                                            "WHERE TIME_PLAN_ID = " . $employee['FULL_TIME_ID']);
                                                $planDtlGroup = Arr::groupByArray($planDtlDataByPlan, 'DATE_STR');            
                                            }

                                            if (empty($i)) {
                                                $theader = '<thead>';
                                                $theader .= '<tr>';
                                                $theader .= '<th style="width:10px; text-align: center" class="number rowNumber">№</th>';
                                                if ($isGolomt) {
                                                    $theader .= '<th style="width:200px; min-width:200px; text-align: center !important;"><span>Овог, Нэр</span></th>';
                                                    $theader .= '<th style="width:100px; min-width:100px; text-align: center !important;"><span>'. $golomtView .'</span></th>';
                                                } else {
                                                    $theader .= '<th style="width:200px; min-width:200px;  text-align: center !important;"><span>Овог, Нэр ('. $golomtView .' - РД)</span></th>';
                                                }
                                                $theader .= '<th style="width:200px; min-width:200px;" class="text-center">Албан тушаал</th>';
                                            }

                                            if ($depIndex === 1) {
                                                $depIndex++;
                                                $tbody .= '<tbody class="tablesorter-no-sort">';
                                                    $tbody .= '<tr class="row-details" data-department="' . $department['DEPARTMENT_ID'] . '" id="EMPLOYEE_'. $employee['EMPLOYEE_ID'] . '_' . $employee['EMPLOYEE_KEY_ID'] . '_' . $department['DEPARTMENT_ID'] . '">';
                                                        $tbody .= '<td class="number"> &nbsp;&nbsp; </td>';
                                                        $tbody .= '<td class="pl10 departmentTitle" data-colspan="1" colspan="20" style="border-right-color: transparent; word-wrap: break-word;">' . $department['DEPARTMENT_NAME'] . '</td>';
                                                        $tbody .= '<td style="border-right-color: transparent;"></td>';
                                                    $tbody .= '</tr>';
                                                $tbody .= '</tbody>';
                                            }

                                            $tbody .= '<tr data-department="' . $employee['DEPARTMENT_ID'] . '" id="EMPLOYEE_'. $employee['EMPLOYEE_ID'] . '_' . $employee['EMPLOYEE_KEY_ID'] . '_' . $employee['DEPARTMENT_ID'] . '">';
                                            $tbody .= '<td style="padding-left:2px; padding-right:8px; line-height: 13px; vertical-align: middle;" class="text-center no-select">' . $autoNumber . '</td>';
                                            $tbody .= '<td class="align-left pl10 pr10 no-select" style="line-height: 13px; vertical-align: middle;" title="'.$employee['LAST_NAME'].'.'.$employee['FIRST_NAME'].'">';

                                                $empLoyeeName = mb_substr ($employee['LAST_NAME'], 0, 2, 'UTF-8').'.'.$employee['FIRST_NAME'].' ';
                                                if ($tmsCustomerCode == 'gov') {
                                                    $empLoyeeName = mb_substr ($employee['LAST_NAME'], 0, 1, 'UTF-8').'.'.$employee['FIRST_NAME'].' ';
                                                }

                                                $tbody .= '<input type="hidden" data-name="firstName" name="firstName[]" value="' . $employee['FIRST_NAME'] . '">';
                                                $tbody .= '<input type="hidden" data-name="lastName" name="lastName[]" value="' . $employee['LAST_NAME'] . '">';
                                                $tbody .= '<input type="hidden" data-name="departmentId" name="departmentId[]" value="' . $employee['DEPARTMENT_ID'] . '">';
                                                $tbody .= '<input type="hidden" data-name="employeeId" name="employeeId[]" value="' . $employee['EMPLOYEE_ID'] . '">';
                                                $tbody .= '<input type="hidden" data-name="employeeKeyId" name="employeeKeyId[]" value="' . $employee['EMPLOYEE_KEY_ID'] . '">';
                                                $tbody .= '<input type="hidden" data-name="positionKeyId" name="positionKeyId[]" value="' . $employee['POSITION_KEY_ID'] . '">';
                                                $tbody .= '<input type="hidden" data-name="positionName" name="positionName[]" value="' . $employee['POSITION_NAME'] . '">';
                                                $tbody .= '<input type="hidden" data-name="code" name="code[]" value="' . $employee['CODE'] . '">';
                                                $tbody .= '<input type="hidden" data-name="fullTimeId" name="fullTimeId[]" value="' . $employee['FULL_TIME_ID'] . '">';
                                                $tbody .= '<input type="hidden" data-name="isExist" name="isExist[]" value="' . $employee['IS_EXIST'] . '">';

                                                if ($isGolomt) {
                                                        $tbody .= $empLoyeeName;
                                                    $tbody .= '</td>';
                                                    $tbody .= '<td class="pl10 pr10 no-select" style="line-height: 13px; vertical-align: middle;">' . $employee['CODE'] . '</td>';
                                                } else {
                                                    if ($tmsCustomerCode === 'khaanbank' || $tmsCustomerCode === 'golomt') {
                                                        $tbody .= $empLoyeeName. ' <i>('. $employee['CODE'] .')<i>';
                                                    } else {
                                                        $tbody .= $empLoyeeName. ' <i>('. $employee['CODE'] . ' - ' . $employee['REGISTER_NUMBER'] .')<i>';
                                                    }
                                                    $tbody .= '</td>';
                                                }

                                            $tbody .= '<td class="pl10 pr10 no-select" style="line-height: 13px; vertical-align: middle;">' . $employee['POSITION_NAME'] . '</td>';

                                            $totalAmountPlanTime = 0;

                                            $iday = 1;
                                            foreach ($days as $key => $day) {
                                                $dayAddin = $iday;

                                                if (empty($i)) {
                                                    $theader .= '<th rowspan="2" data-isworking="' . $day['SPELL_DAY'] . '" class="tbl-cell ' . $day['DAY_CLASS'] . '" style="width: 22px; font-weight: 500; min-width: 22px; line-height: 13px; vertical-align: middle">';
                                                    $theader .= '<div class="dayName">' . $day['DAY'] . '</div>';
                                                    $theader .= '<div class="dayName">' . $day['SPELL_DAY_SHORT_NAME'] . '</div>';
                                                    $theader .= '</th>';
                                                }

                                                if ($employee['D' . $dayAddin] !== '') {

                                                    if (!isset($planDtlGroup['D'. $iday])) {
                                                        $employee['PLAN_TIME_'. $iday] = 0;
                                                    }

                                                    $dayPlanTime = ($employee['PLAN_TIME_'. $iday] > 0) ? $employee['PLAN_TIME_'. $iday] : '';
                                                    //$dayPlanColor = ($isHishigArvin) ? ($employee['COLOR_'. $iday]) ? $employee['COLOR_'. $iday] : '' : '';
                                                    $dayPlanColor = $employee['COLOR_'. $iday] ? $employee['COLOR_'. $iday] : '';
                                                    $dayPlanTime = ($isHishigArvin) ? ($employee['SHORT_NAME_'. $iday] ? $employee['SHORT_NAME_'. $iday] : $dayPlanTime) : $dayPlanTime;

                                                    $tbody .= '<td title="' . $day['HOLIDAY'] . ' ' . $day['STATUS_DESCRIPTION'] . '" style="cursor: context-menu; background-color:' . (($dayPlanColor) ? $dayPlanColor : ((strtolower($day['DAY_COLOR']) !== '') ? $day['DAY_COLOR'] : 'transparent' )) .'; text-align:center; vertical-align: middle; ' . ($day['IS_LOCK'] == '1' ? 'background-image : url(\'assets/core/global/img/cell-status.png\'); background-repeat: no-repeat; background-position: bottom right;' : '') . ' "  data-isworking="' .$day['SPELL_DAY'] . '" class="tbl-cell  ' . $day['DAY_CLASS'] . '">';
                                                    $tbody .= '<input type="hidden" data-name="tnaEmployeeTimePlanId" name="tnaEmployeeTimePlanId[' . $employee['EMPLOYEE_ID'] .'][]" value="' . (isset($day['ID']) ? $day['ID'] : '') . '">';
                                                    $tbody .= '<input type="hidden" data-name="planDate" name="planDate[' . $employee['EMPLOYEE_ID'] .'][]" value="' . $day['PLAN_DATE'].'">';
                                                    $tbody .= '<input type="hidden" data-name="planCode" name="planCode[' . $employee['EMPLOYEE_ID'] .'][]" value="D'. $dayAddin. '">';
                                                    $tbody .= '<input type="hidden" data-name="planId" name="planId[' . $employee['EMPLOYEE_ID'] .'][]" value="' . $employee['PLAN_ID_'. $iday].'">';
                                                    $tbody .= '<input type="hidden" data-name="planTime" name="planTime[' . $employee['EMPLOYEE_ID'] .'][]" value="' . $employee['PLAN_TIME_'. $iday].'">';
                                                    $tbody .= '<input type="hidden" data-name="isSelectedCell" name="isSelectedCell[' . $employee['EMPLOYEE_ID'] .'][]" value="0">';
                                                        $tbody .= $dayPlanTime;
                                                    $tbody .= '</td>';        
                                                    $totalAmountPlanTime += ($employee['PLAN_TIME_'. $iday] > 0) ? $employee['PLAN_TIME_'. $iday] : '0';
                                                }  else {
                                                    $tbody .= '<td>';
                                                    $tbody .= '</td>';
                                                }

                                                $iday++;
                                            }

                                            $tbody .= '<td class="no-select" style="padding: 0 !important; background-color: #EEE; line-height: 13px; vertical-align: middle">';
                                                $tbody .= '<input type="text" class="form-control fullTime text-center" readonly ="readonly" maxlength="3" name="fullTime[]" data-name="fullTime" value="' . $totalAmountPlanTime . '" title="' . $totalAmountPlanTime . '" onchange="checkFullTime(this);">';
                                            $tbody .= '</td>';
                                            $tbody .= '</tr>';
                                            $autoNumber++;
                                            if (empty($i)) {
                                                $theader .= '<th style="width:40px; max-width:40px; text-align: center;" rowspan="2" class="rowNumber">НИЙТ</th>';
                                                $theader .= '</tr>';
                                                $theader .= '<tr class="bp-filter-row">';
                                                    $theader .= '<th class="rowNumber" style="background-color: rgb(231, 231, 231); position: relative; z-index: 10; background-clip: padding-box; top: -1px; left: 0px;"></th>';

                                                    if ($isGolomt) {
                                                        $theader .= '<th style="background-color: rgb(231, 231, 231); position: relative; z-index: 10; background-clip: padding-box; top: -1px; left: 0px;"><input type="text" data-fieldname="employeename" data-condition="like"></th>';
                                                        $theader .= '<th style="background-color: rgb(231, 231, 231); position: relative; z-index: 10; background-clip: padding-box; top: -1px; left: 0px;"><input type="text" data-fieldname="employeedomain" data-condition="like"></th>';
                                                    } else {
                                                        $theader .= '<th style="background-color: rgb(231, 231, 231); position: relative; z-index: 10; background-clip: padding-box; top: -1px; left: 0px;"><input type="text" data-fieldname="employeename" data-condition="like"></th>';
                                                    }
                                                    $theader .= '<th style="background-color: rgb(231, 231, 231); position: relative; z-index: 10; background-clip: padding-box; top: -1px; left: 0px;"><input type="text" data-fieldname="employeeposition" data-condition="like"></th>';
                                                $theader .= '</tr>';
                                                $theader .= '</thead>';
                                            }
                                        }
                                    }
                                }
                            }

                            $result = ' <input type="hidden" id="tnatimePlanTotalCount" value="'. $employeeCount .'">
                                        <input type="hidden" id="tnatimePlanPage" value="1">
                                        <input type="hidden" id="tnatimePlanIsArchive" value="0">
                                        <input type="hidden" id="srch_yearCode" value="'. $params['planYear'] .'">
                                        <input type="hidden" id="srch_monthCode" value="'. $params['planMonth'] .'">
                                        <div class="pf-custom-pager">
                                            <div id="fz-parent" class="freeze-overflow-xy-auto">
                                                <table class="table table-sm table-bordered table-hover gl-table-dtl bprocess-theme1" id="assetDtls">
                                                    '. $theader .'
                                                    <tbody>'. $tbody .'</tbody>
                                                    <tfoot>
                                                        '. $tfooter .'
                                                    </tfoot>
                                                </table>
                                            </div>
                                            <div class="pf-custom-pager-tool">
                                                <div class="pf-custom-pager-buttons">
                                                    '.Form::select(
                                                        array(
                                                            'name' => '',
                                                            'class' => 'pagination-page-list',
                                                            'op_value' => 'value',
                                                            'op_text' => 'code',
                                                            'style' => 'height:24px; float:left;color:#444',
                                                            'data' => array(
                                                                array('value' => '10', 'code' => '10'),
                                                                array('value' => '20', 'code' => '20'),
                                                                array('value' => '30', 'code' => '30'),
                                                                array('value' => '40', 'code' => '40'),
                                                                array('value' => '50', 'code' => '50'),
                                                                array('value' => '100', 'code' => '100'),
                                                                array('value' => '200', 'code' => '200')
                                                            ),
                                                            'text' => 'notext', 
                                                            'value' => Config::getFromCache('tmsPageNumber') ? Config::getFromCache('tmsPageNumber') : 50
                                                        )
                                                    ).'
                                                    <div class="pf-custom-pager-separator"></div>
                                                    <a href="javascript:;" class="pf-custom-pager-last-prev pf-custom-pager-disabled">
                                                        <span></span>
                                                    </a>
                                                    <a href="javascript:;" class="pf-custom-pager-prev pf-custom-pager-disabled">
                                                        <span></span>
                                                    </a>
                                                    <div class="pf-custom-pager-separator"></div>
                                                    <div class="pf-custom-pager-page-info">Хуудас <span><input type="text" size="2" value="1" data-gotopage="1" class="integerInit"></span> of <span data-pagenumber="1">0</span></div>	
                                                    <div class="pf-custom-pager-separator"></div>
                                                    <a href="javascript:;" class="pf-custom-pager-next pf-custom-pager-disabled">
                                                        <span></span>
                                                    </a>
                                                    <a href="javascript:;" class="pf-custom-pager-last-next pf-custom-pager-disabled">
                                                        <span></span>
                                                    </a>
                                                    <div class="pf-custom-pager-separator"></div>
                                                    <a href="javascript:;" class="pf-custom-pager-refresh pf-custom-pager-disabled">
                                                        <span></span>
                                                    </a>
                                                </div>
                                                <div class="pf-custom-pager-total">Нийт <span>0</span> байна.</div>
                                            </div>
                                        </div>';
                }

                return $result;
            }
        }
    }        

    public function getMetaDataIdModel($metaDataCode) {
        $result = $this->db->GetOne("
            SELECT 
                META_DATA_ID 
            FROM 
                META_DATA 
            WHERE 
                LOWER(META_DATA_CODE) = LOWER('" . $metaDataCode . "')");

        if ($result) {
            return $result;
        }
        return false;
    }        

    public function getWfmStatusByPlanBtnModel($departmentId) {
        $btnAll = $btnItem = '';

        if($departmentId) {
            $result = $this->db->GetAll("
                SELECT 
                    TAC.WFM_STATUS_ID,
                    WWS.WFM_STATUS_CODE,
                    WWS.WFM_STATUS_COLOR,
                    WWS.WFM_STATUS_NAME
                FROM 
                    TNA_APPROVE_CONFIG TAC
                INNER JOIN meta_wfm_status WWS ON TAC.WFM_STATUS_ID = WWS.ID
                WHERE 
                    TAC.USER_ID = " . Ue::sessionUserKeyId() . " AND 
                    TAC.DEPARTMENT_ID = " . $departmentId);
            if ($result) {
                /*
                 * employeeSaveBtn
                 * employeeConfirmBtn
                 */
                $iconClass = $text = '';
                foreach ($result as $k => $row) {
                    if (
                            $row['WFM_STATUS_CODE'] == 'confirmedByCEO' or
                            $row['WFM_STATUS_CODE'] == 'confirmedByExecutive' or
                            $row['WFM_STATUS_CODE'] == 'reviewByHR'
                    ) {
                        $btnItem .= '<button ';
                        $btnItem .= 'type="button" ';
                        $btnItem .= 'id="' . $row['WFM_STATUS_ID'] . '" ';
                        $btnItem .= 'class="btn btn-sm blue-madison mr0  ml0 balanceVerifyItem statusApproveBtn" ';
                        $btnItem .= 'data-status-id="' . $row['WFM_STATUS_ID'] . '" ';
                        $btnItem .= 'data-status-name="' . $row['WFM_STATUS_NAME'] . '" ';
                        $btnItem .= 'data-status-code="' . $row['WFM_STATUS_CODE'] . '" ';
                        $btnItem .= 'title="Сонгосон нүдийг (' . $row['WFM_STATUS_NAME'] . ')">';
                        $btnItem .= '<i class="fa fa-refresh"></i> ';
                        $btnItem .= '</button>';

                        $btnItem .= '<button ';
                        $btnItem .= 'type="button" ';
                        $btnItem .= 'id="' . $row['WFM_STATUS_ID'] . '" ';
                        $btnItem .= 'class="btn btn-sm btn-success mr0  ml0 balanceVerify statusApproveBtn" ';
                        $btnItem .= 'data-status-id="' . $row['WFM_STATUS_ID'] . '" ';
                        $btnItem .= 'data-status-name="' . $row['WFM_STATUS_NAME'] . '" ';
                        $btnItem .= 'data-status-code="' . $row['WFM_STATUS_CODE'] . '" ';
                        $btnItem .= 'title="' . $row['WFM_STATUS_NAME'] . '">';
                        $btnItem .= '<i class="fa fa-rotate-right"></i> ';
                        $btnItem .= '</button>';
                    } else if (
                            $row['WFM_STATUS_CODE'] == 'cancelByCEO' or
                            $row['WFM_STATUS_CODE'] == 'cancelByExecutive' or
                            $row['WFM_STATUS_CODE'] == 'cancelByHR'
                    ) {
                        $btnItem .= '<button ';
                        $btnItem .= 'type="button" ';
                        $btnItem .= 'id="' . $row['WFM_STATUS_ID'] . '" ';
                        $btnItem .= 'class="btn btn-sm default mr0  ml0 balanceNo statusCancelBtn" ';
                        $btnItem .= 'data-status-id="' . $row['WFM_STATUS_ID'] . '" ';
                        $btnItem .= 'data-status-name="' . $row['WFM_STATUS_NAME'] . '" ';
                        $btnItem .= 'data-status-code="' . $row['WFM_STATUS_CODE'] . '" ';
                        $btnItem .= 'title="' . $row['WFM_STATUS_NAME'] . '">';
                        $btnItem .= '<i class="fa fa-times"></i> ';
                        $btnItem .= '</button>';
                    }
                }
            }
        }
        return array('all' => $btnAll, 'item' => $btnItem);
    }        

    public function getArchivListV2Model($year, $month) {   
        parse_str(Input::post('params'), $params);

        if (is_array($params['newDepartmentId']) && count($params['newDepartmentId'])) {

            $departmentIds = $params['newDepartmentId']; 
            $departmentIds = implode(',', $departmentIds); 
            $isChild = issetVar($params['isChild']);

            if ($departmentIds) {

                $departmentIds = $this->getAllChildDepartment2Model($departmentIds, $isChild);

            } elseif (isset($balanceCriteria['filterGroupId'])) {

                $departmentIds = $this->getAllChildDepartmentByGroupIdModel($balanceCriteria['filterGroupId'][0]['operand']);
            }

            $balanceCriteria['filterDepartmentId'] = array(
                array('operator' => 'IN', 'operand' => $departmentIds['join'])
            );

            $departmentList = $departmentIds['array'];
        }        

        $join = $where = '';
        if (Config::getFromCache('TMS_PLAN_ARCHIEVE_BY_DEPARTMENT')) {
            $join = ' INNER JOIN TMS_EMPLOYEE_PLAN_LOG_DEP BB ON BB.LOG_ID = AA.ID ';
            $where = ' AND BB.DEPARTMENT_ID IN ('.$departmentIds['join'].') ';
        }

        $result = $this->db->GetAll("
            SELECT DISTINCT AAA.* FROM(
                SELECT AA.ID,
                    'Архив ' || AA.VERSION_NUMBER AS VERSION,
                    SUBSTR(AA.DESCRIPTION,0,30) AS DESCRIPTION
                FROM 
                    TMS_EMPLOYEE_PLAN_LOG AA
                ".$join."
                WHERE AA.YEAR_ID = ".self::getRefYearIdModel($year)." and AA.MONTH_ID=".self::getRefMonthIdModel($month).$where." 
                ORDER BY AA.VERSION_NUMBER ASC
            ) AAA");

        if ($result) {
            return $result;
        }
        return false;
    }        

    public function employeePlanListV2Model() {
        parse_str(Input::post('params'), $params);
        $departmentIds = $params['newDepartmentId'];
        $departmentIds = is_array($departmentIds) ? implode(',', $departmentIds) : $departmentIds;
        $isChild = issetVar($params['isChild']);
        $ticket = is_array($departmentIds) ? false : strpos($departmentIds, ',');

        if (is_array($departmentIds) && count($params['newDepartmentId']) && implode(',', $params['newDepartmentId'])) {
            $departmentIds = $this->getAllChildDepartmentModel($departmentIds, $isChild);            
        } elseif ($ticket !== false) {
            $departmentIds = $this->getAllChildDepartmentModel($departmentIds, $isChild);
        } elseif (isset($params['groupId']) && is_array($params['groupId'])) {
            $departmentIds = "SELECT DEPARTMENT_ID FROM VW_TMS_EMPLOYEE WHERE EMPLOYEE_ID IN ( SELECT EMPLOYEE_ID FROM TNA_EMPLOYEE_GROUP_CONFIG WHERE GROUP_ID IN (" . implode(',', $params['groupId']) . "))";
        }   

        $data = $this->db->GetAll("
            SELECT 
                TTP.PLAN_ID,
                TTP.CODE,
                TTP.NAME,
                TTPDTL.START_TIME,
                TTPDTL.END_TIME,
                '' AS PLAN_COUNT
            FROM TMS_TIME_PLAN TTP
            INNER JOIN TMS_TIME_PLAN_DEPARTMENT TTPD ON TTPD.PLAN_ID = TTP.PLAN_ID
            INNER JOIN (
                SELECT F.PLAN_ID, TO_CHAR(F.START_TIME, 'HH24:MI') AS START_TIME, TO_CHAR(FF.END_TIME, 'HH24:MI') AS END_TIME
                FROM (
                   SELECT PLAN_ID, MIN(ACC_TYPE) AS ACC_TYPE
                   FROM TMS_TIME_PLAN_DETAIL GROUP BY PLAN_ID
                ) X 
                INNER JOIN TMS_TIME_PLAN_DETAIL F ON F.ACC_TYPE = X.ACC_TYPE AND F.PLAN_ID = X.PLAN_ID
                INNER JOIN (
                  SELECT F.PLAN_ID, F.END_TIME
                  FROM (
                     SELECT PLAN_ID, MAX(ACC_TYPE) AS ACC_TYPE
                     FROM TMS_TIME_PLAN_DETAIL GROUP BY PLAN_ID
                  ) X 
                  INNER JOIN TMS_TIME_PLAN_DETAIL F ON F.ACC_TYPE = X.ACC_TYPE AND F.PLAN_ID = X.PLAN_ID
                ) FF ON FF.PLAN_ID = X.PLAN_ID
            ) TTPDTL ON TTP.PLAN_ID = TTPDTL.PLAN_ID           
            WHERE TTPD.DEPARTMENT_ID IN (" . $departmentIds . ")
            GROUP BY 
                TTP.PLAN_ID,
                TTP.CODE,
                TTP.NAME,
                TTPDTL.START_TIME,
                TTPDTL.END_TIME");

//        $data = $this->db->GetAll("
//            SELECT 
//                TTP.PLAN_ID,
//                TTP.CODE,
//                TTP.NAME,
//                TTPDTL.START_TIME,
//                TTPDTL.END_TIME,
//                COUNT(TEM.PLAN_ID) AS PLAN_COUNT
//            FROM TMS_TIME_PLAN TTP
//            INNER JOIN TMS_TIME_PLAN_DEPARTMENT TTPD ON TTPD.PLAN_ID = TTP.PLAN_ID
//            INNER JOIN (
//                SELECT F.PLAN_ID, TO_CHAR(F.START_TIME, 'HH24:MI') AS START_TIME, TO_CHAR(FF.END_TIME, 'HH24:MI') AS END_TIME
//                FROM (
//                   SELECT PLAN_ID, MIN(ACC_TYPE) AS ACC_TYPE
//                   FROM TMS_TIME_PLAN_DETAIL GROUP BY PLAN_ID
//                ) X 
//                INNER JOIN TMS_TIME_PLAN_DETAIL F ON F.ACC_TYPE = X.ACC_TYPE AND F.PLAN_ID = X.PLAN_ID
//                INNER JOIN (
//                  SELECT F.PLAN_ID, F.END_TIME
//                  FROM (
//                     SELECT PLAN_ID, MAX(ACC_TYPE) AS ACC_TYPE
//                     FROM TMS_TIME_PLAN_DETAIL GROUP BY PLAN_ID
//                  ) X 
//                  INNER JOIN TMS_TIME_PLAN_DETAIL F ON F.ACC_TYPE = X.ACC_TYPE AND F.PLAN_ID = X.PLAN_ID
//                ) FF ON FF.PLAN_ID = X.PLAN_ID
//            ) TTPDTL ON TTP.PLAN_ID = TTPDTL.PLAN_ID
//            LEFT JOIN (
//                SELECT TPD.PLAN_ID, VE.DEPARTMENT_ID
//                FROM VW_EMPLOYEE VE
//                INNER JOIN TMS_EMPLOYEE_TIME_PLAN_HDR ETP ON VE.EMPLOYEE_ID = ETP.EMPLOYEE_ID
//                INNER JOIN TMS_EMPLOYEE_TIME_PLAN_DTL TPD ON ETP.ID = TPD.TIME_PLAN_ID
//                WHERE VE.DEPARTMENT_ID IN (". $departmentIds .")
//                GROUP BY TPD.PLAN_ID, VE.DEPARTMENT_ID
//            ) TEM ON TTP.PLAN_ID = TEM.PLAN_ID                
//            WHERE TTPD.DEPARTMENT_ID IN (" . $departmentIds . ")
//            GROUP BY 
//                TTP.PLAN_ID,
//                TTP.CODE,
//                TTP.NAME,
//                TTPDTL.START_TIME,
//                TTPDTL.END_TIME,
//                TEM.PLAN_ID");

        if ($data) {
            return $data;
        }

        return false;
    }        

    public function saveEmployeePlanV2Model() {

        try {
            $data = Input::postData();
            $currentDate = Date::currentDate();
            $sessionUserKeyId = Ue::sessionUserKeyId();
            (Array) $paramData = array();
            (Array) $tnaEmployeeTimePlanHdr = array(
                'yearId' => Input::param($data['planYear']),
                'monthId' => Input::param($data['planMonth']),
            );

            $employeeIds = $_POST['employeeId'];
            $datesArr = array();
            $tmsCalcIdCode = false;
            $keySplit = Config::getFromCache('TMS_PLAN_IS_KEY_SPLIT');
            
            if (Config::getFromCache('tmsCalcIdCode') == '1') {
                $tmsCalcIdCode = true;
                $getCalcInfo = self::getCalcListModel(Input::param($data['calcId']));
                $caclStartDate = Input::post('startDate');
                $caclEndDate = Input::post('endDate');                

                if (Date::formatter($caclStartDate, 'm') == Date::formatter($caclEndDate, 'm')) {
                    array_push($datesArr, array(
                        'year' => Date::formatter($caclStartDate, 'Y'), 
                        'month' => Date::formatter($caclStartDate, 'm'),
                        'monthId' => (int) Date::formatter($caclStartDate, 'm')
                    ));
                } else {
                    array_push($datesArr, array(
                        'year' => Date::formatter($caclStartDate, 'Y'),
                        'month' => Date::formatter($caclStartDate, 'm'),
                        'monthId' => (int) Date::formatter($caclStartDate, 'm')
                    ));
                    array_push($datesArr, array(
                        'year' => Date::formatter($caclEndDate, 'Y'), 
                        'month' => Date::formatter($caclEndDate, 'm'),
                        'monthId' => (int) Date::formatter($caclEndDate, 'm')
                    ));
                }                
            } else {
                array_push($datesArr, array(
                    'year' => $tnaEmployeeTimePlanHdr['yearId'], 
                    'month' => $tnaEmployeeTimePlanHdr['monthId'],
                    'monthId' => (int) $tnaEmployeeTimePlanHdr['monthId']
                ));     
            }
            
            $idPh1 = $this->db->Param(0);
            $idPh2 = $this->db->Param(1);
            $idPh3 = $this->db->Param(2);
            $idPh4 = $this->db->Param(3);
            
            foreach ($employeeIds as $k => $row) {
                foreach ($datesArr as $dateVal) {

                    $employeeTimePlanId = $tmsCalcIdCode ? $data['isExist'.$dateVal['monthId']][$row] : $data['isExist'][$k];
                    $paramData['ID'] = getUIDAdd($k);
                    $paramData['YEAR_ID'] = $dateVal['year'];
                    $paramData['MONTH_ID'] = $dateVal['monthId'];
                    $paramData['CREATED_USER_ID'] = $sessionUserKeyId;
                    $paramData['CREATED_DATE'] = $currentDate;
                    $paramData['EMPLOYEE_ID'] = Input::param($data['employeeId'][$k]);        
                    $paramData['EMPLOYEE_KEY_ID'] = Input::param($data['employeeKeyId'][$k]);        
                    $queryJoin = '';
                    $queryLogJoin = '';
                    $dateJoin = '';

                    foreach ($_POST['planDate'][$row] as $key => $val) {
                        if (Date::formatter($val, 'Y-m') == $dateVal['year'].'-'.$dateVal['month']) {
                            $planDate = Date::formatter($val, 'Y-m-d');
                            $isSelectedCell = $data['isSelectedCell'][$row][$key];

                            if ($isSelectedCell == '1') {
                                $paramData[$_POST['planCode'][$row][$key]] =  Input::param($data['planIdSet']);
                                $queryJoin = 'OK';                       

                            }
                        }
                    }

                    if($queryJoin === '') {
                        continue;
                    }

                    /**
                     * Insert log actions
                     */
                    //@file_put_contents(BASEPATH.'log/time_eployee_plan.log', 'SUCCESS'.$employeeTimePlanId . ' ' . json_encode($paramData)."\r\n", FILE_APPEND);                            
                    
                    if (!$employeeTimePlanId) {
                        
                        if ($keySplit) {
                            $employeeTimePlanId = $this->db->GetOne("
                                SELECT 
                                    ID 
                                FROM TMS_EMPLOYEE_TIME_PLAN_HDR 
                                WHERE YEAR_ID = $idPh1 
                                    AND MONTH_ID = $idPh2 
                                    AND EMPLOYEE_KEY_ID = $idPh4 
                                    AND EMPLOYEE_ID = $idPh3", 
                                array($paramData['YEAR_ID'], $paramData['MONTH_ID'], $paramData['EMPLOYEE_ID'], $paramData['EMPLOYEE_KEY_ID'])
                            );                            
                        } else {
                            $employeeTimePlanId = $this->db->GetOne("
                                SELECT 
                                    ID 
                                FROM TMS_EMPLOYEE_TIME_PLAN_HDR 
                                WHERE YEAR_ID = $idPh1 
                                    AND MONTH_ID = $idPh2 
                                    AND EMPLOYEE_ID = $idPh3", 
                                array($paramData['YEAR_ID'], $paramData['MONTH_ID'], $paramData['EMPLOYEE_ID'])
                            );
                        }
                    }
                    
                    if ($employeeTimePlanId) {
                        
                        unset($paramData['ID']);
                        unset($paramData['MONTH_ID']);
                        //unset($paramData['EMPLOYEE_ID']);
                        //unset($paramData['EMPLOYEE_KEY_ID']);
                        
                        $this->db->AutoExecute('TMS_EMPLOYEE_TIME_PLAN_HDR', $paramData, 'UPDATE', "ID = $employeeTimePlanId");                
                        /*$this->db->Execute("DELETE FROM TMS_EMPLOYEE_TIME_PLAN_DTL WHERE TIME_PLAN_ID = $employeeTimePlanId AND TO_CHAR(PLAN_DATE, 'YYYY-MM-DD') IN (".rtrim($dateJoin, ',').")");
                        $this->db->Execute("INSERT ALL $queryJoin SELECT * FROM dual");     */
                        
                        $hdrId = $employeeTimePlanId;
                        
                    } else {
                        
                        $this->db->AutoExecute('TMS_EMPLOYEE_TIME_PLAN_HDR', $paramData);
                        //$this->db->Execute("INSERT ALL $queryJoin SELECT * FROM dual");
                        
                        $hdrId = $paramData['ID'];
                    }
                    
                    $bpResult = $this->ws->runSerializeResponse(self::$gfServiceAddress, 'insert_time_plan_location', array('id' => $hdrId));
                    
                    if (isset($bpResult['status']) && $bpResult['status'] != 'success') {
                        
                        $bpMessage = $this->ws->getResponseMessage($bpResult);
                        $bpMessageLower = Str::lower($bpMessage);
                        
                        if (strpos($bpMessageLower, 'комманд олдсонгүй') === false) {
                            return array('status' => 'error', 'message' => $bpMessage);
                        }
                    }

                    //$this->db->Execute("INSERT ALL $queryLogJoin SELECT * FROM dual");

                    $paramData = array();
                }
            }            

        } catch (ADODB_Exception $ex) {
            $errorMsg = $ex->getMessage();

            /**
             * Insert log actions
             */
            //@file_put_contents(BASEPATH.'log/time_eployee_plan.log', $errorMsg."\r\n------------------------[ERROR MAIN SAVE]--------------------------\r\n", FILE_APPEND);
            return array('status' => 'error', 'message' => $errorMsg);
        }            

        return array('status' => 'success', 'message' => 'Амжилттай хадгаллаа');
    }        

    public function deleteEmployeePlanV2MonthModel() {

        $result = array('status' => 'error');
        $deleteId = array();
        $data = Input::postData();

        $userWfmStatusCode = Input::post('userWfmStatusCode');
        $userWfmStatusId = Input::post('userWfmStatusId');
        $message = 'Амжилттай устгалаа';
        $cellResult = false;

        foreach ($data['employeeId'] as $k => $employeeId) {
            foreach ($data['planId'][$employeeId] as $j => $planId) {
                if ($planId != '' and $data['tnaEmployeeTimePlanId'][$employeeId][$j]) {
                    array_push($deleteId, $data['tnaEmployeeTimePlanId'][$employeeId][$j]);

                    $isLock = Input::param($data['isLock'][$employeeId][$j]);
                    $lockEndDate = Input::param($data['lockEndDate'][$employeeId][$j]);
                    $wfmStatusCode = Input::param($data['wfmStatusCode'][$employeeId][$j]);

                    if (self::checkIsLockDate($lockEndDate, $isLock)) {
                        $message = self::$messageIsLock;
                        $cellResult = true;
                        break;
                    }
                }
            }
        }

        if ($cellResult) {
            $response = array('status' => 'warning', 'title' => 'Тайлбар', 'message' => $message);
        } else {
            if (count($deleteId) > 0) {
                $this->db->Execute("
                    DELETE FROM 
                        TNA_EMPLOYEE_TIME_PLAN 
                    WHERE 
                        ID IN(" . implode(',', $deleteId) . ")");
            }
            $response = array('status' => 'success', 'title' => 'Амжилттай', 'message' => $message);
        }
        return $response;
    }        

    public function checkIsLockDate($lockEndDate, $isLock) {
        if ($isLock != null and $lockEndDate != null) {
            $diff = strtotime($lockEndDate) - strtotime(Date::currentDate('Y-m-d'));
            if ($diff >= 0) {
                return true;
            }
        }
        return false;
    }        

    public function sendArchivV2Model() {
        parse_str(Input::post('params'), $params);
        $getUID = getUID();
        $planYear = $params['planYear'];
        $planMonth = $params['planMonth'];
        $getRefPlanYearId = self::getRefYearIdModel($planYear);
        $getRefPlanMonthId = self::getRefMonthIdModel($planMonth);

        $userKeyId = Ue::sessionUserKeyId();

        $response = '';
        $param = array(
            'ID' => $getUID,
            'YEAR_ID' => $getRefPlanYearId,
            'MONTH_ID' => $getRefPlanMonthId,
            'VERSION_NUMBER' => self::getArchivVersionNumberModel($getRefPlanYearId, $getRefPlanMonthId),
            'ARCHIEVED_DATE' => Date::currentDate(),
            'ARCHIEVED_USER_ID' =>  Ue::sessionUserId(),
            'DESCRIPTION' => Input::post('description')
        );

        try {
            $result = $this->db->AutoExecute('TMS_EMPLOYEE_PLAN_LOG', $param);

            $balanceCriteria = $departmentList = array();
            $balanceDVid = Config::getFromCache('tnaTimePlanHdrDV');
            $theader = $tbody = $tfooter = '';
            $theadWidth = (int) Config::getFromCacheDefault('tnaPlanColumnSize', null, '200');            
    
            if ((isset($params['groupId']) && is_array($params['groupId']))) {
                $balanceCriteria['filterGroupId'] = array(
                    array('operator' => 'IN', 'operand' => implode(',', $params['groupId']))
                );
            }   
    
            if (is_array($params['newDepartmentId']) && count($params['newDepartmentId'])) {
    
                $departmentIds = $params['newDepartmentId']; 
                $departmentIds = implode(',', $departmentIds); 
                $isChild = issetVar($params['isChild']);
    
                if ($departmentIds) {
    
                    $departmentIds = $this->getAllChildDepartment2Model($departmentIds, $isChild);
    
                } elseif (isset($balanceCriteria['filterGroupId'])) {
    
                    $departmentIds = $this->getAllChildDepartmentByGroupIdModel($balanceCriteria['filterGroupId'][0]['operand']);
                }
    
                $balanceCriteria['filterDepartmentId'] = array(
                    array('operator' => 'IN', 'operand' => $departmentIds['join'])
                );
    
                $departmentList = $departmentIds['array'];
    
                $positionIds = issetParam($params['positionId']);                                                
                if ($positionIds) {
                    $positionIds = implode(',', $positionIds);
                    $balanceCriteria['positionId'] = array(
                        array('operator' => 'IN', 'operand' => $positionIds)
                    );
                }
            } elseif ($params['newDepartmentId'] && Config::getFromCache('tmsCustomerCode') == 'gov') {
    
                $departmentIds = $params['newDepartmentId'];
                $isChild = issetVar($params['isChild']);
    
                $departmentIds = $this->getAllChildDepartment2Model($departmentIds, $isChild);    
    
                $balanceCriteria['filterDepartmentId'] = array(
                    array('operator' => 'IN', 'operand' => $departmentIds['join'])
                );
    
                $departmentList = $departmentIds['array'];
                $positionIds = issetParam($params['positionId']);      
    
                if ($positionIds) {
                    $positionIds = implode(',', $positionIds);
                    $balanceCriteria['positionId'] = array(
                        array('operator' => 'IN', 'operand' => $positionIds)
                    );
                }                
            }
            
            if (trim($params['stringValue']) != '') {
                $balanceCriteria['filterStringValue'] = array(
                    array('operator' => 'LIKE', 'operand' => '%' . $params['stringValue'] . '%')
                );
            }
    
            if ((isset($params['positionId']) && is_array($params['positionId']))) {
                $balanceCriteria['filterPositionId'] = array(
                    array('operator' => 'IN', 'operand' => implode(',', $params['positionId']))
                );
            }       
    
            $caclStartDate = '';
            $caclEndDate = '';
            $caclYear = '';
            if (Config::getFromCache('tmsCalcIdCode') == '1') {
                if (empty($params['calcId'])) {
                    $response = array('status' => 'error', 'message' => 'Бодолтын дугаараа сонгоно уу!');
                    return $response ;
                }        
                $getCalcInfo = self::getCalcListModel($params['calcId']);
                $caclStartDate = Date::formatter($params['startDate']);
                $caclEndDate = Date::formatter($params['endDate']);
                $caclYear = Date::formatter($params['startDate'], 'Y');
                $balanceCriteria['filterStartDate'] = array(
                    array('operator' => '=', 'operand' => $caclStartDate)
                );            
                $balanceCriteria['filterEndDate'] = array(
                    array('operator' => '=', 'operand' => $caclEndDate)
                );            
    
            } else {
    
                $balanceCriteria['filterYear'] = array(
                    array('operator' => '=', 'operand' => $params['planYear'])
                );     
    
                $balanceCriteria['filterMonth'] = array(
                    array('operator' => '=', 'operand' => $params['planMonth'])
                );            
            }

            $FILTERMONTH = $this->db->addQ($params['planMonth']);
            $FILTERGROUPID = $this->db->addQ('');
            $FILTERPOSITIONID = $this->db->addQ(issetParam($params['positionId']));
            $FILTERDEPARTMENTID = implode(',', $params['newDepartmentId']);
            $FILTERYEAR = $this->db->addQ($params['planYear']);
            $filterStringValue = $this->db->addQ(issetParam($params['stringValue']));

            if ($result) {
                
                $insertHdrQuery = "INSERT INTO TMS_EMPLOYEE_PLAN_LOG_HDR (
                                        ID, 
                                        LOG_ID, 
                                        YEAR_ID,
                                        MONTH_ID,
                                        EMPLOYEE_ID, EMPLOYEE_KEY_ID, CREATED_DATE, CREATED_USER_ID,
                                        D1,
                                        D2,
                                        D3,
                                        D4,
                                        D5,
                                        D6,
                                        D7,
                                        D8,
                                        D9,
                                        D10,
                                        D11,
                                        D12,
                                        D13,
                                        D14,
                                        D15,
                                        D16,
                                        D17,
                                        D18,
                                        D19,
                                        D20,
                                        D21,
                                        D22,
                                        D23,
                                        D24,
                                        D25,
                                        D26,
                                        D27,
                                        D28,
                                        D29,
                                        D30,
                                        D31
                                    )
                                    SELECT 
                                        IMPORT_ID_SEQ.NEXTVAL, 
                                        LOG_ID, 
                                        YEAR_ID,
                                        MONTH_ID,                                        
                                        EMPLOYEE_ID, EMPLOYEE_KEY_ID, CREATED_DATE, CREATED_USER_ID,
                                        D1,
                                        D2,
                                        D3,
                                        D4,
                                        D5,
                                        D6,
                                        D7,
                                        D8,
                                        D9,
                                        D10,
                                        D11,
                                        D12,
                                        D13,
                                        D14,
                                        D15,
                                        D16,
                                        D17,
                                        D18,
                                        D19,
                                        D20,
                                        D21,
                                        D22,
                                        D23,
                                        D24,
                                        D25,
                                        D26,
                                        D27,
                                        D28,
                                        D29,
                                        D30,
                                        D31
                                    FROM (
                                        WITH EMPKEY AS
                                        (
                                        SELECT 
                                        HEK.EMPLOYEE_KEY_ID,
                                        HEK.EMPLOYEE_ID,
                                        HE.EMPLOYEE_CODE,
                                        HE.FIRST_NAME,
                                        HEK.DEPARTMENT_ID,
                                        OD.DEPARTMENT_CODE,
                                        HP.POSITION_NAME,
                                        HEK.WORK_START_DATE,
                                        HEK.WORK_END_DATE,
                                        HEK.CURRENT_STATUS_ID,
                                        HEK.STATUS_ID,
                                        HEK.IS_ACTIVE
                                        FROM HRM_EMPLOYEE_KEY HEK 
                                        INNER JOIN HRM_EMPLOYEE HE ON HEK.EMPLOYEE_ID=HE.EMPLOYEE_ID
                                        INNER JOIN ORG_DEPARTMENT OD ON HEK.DEPARTMENT_ID=OD.DEPARTMENT_ID
                                        INNER JOIN HRM_POSITION_KEY HPK ON HEK.POSITION_KEY_ID=HPK.POSITION_KEY_ID
                                        INNER JOIN HRM_POSITION HP ON HPK.POSITION_ID=HP.POSITION_ID
                                        WHERE 
                                        (CASE 
                                        WHEN HEK.IS_ACTIVE=1 AND HEK.WORK_START_DATE IS NOT NULL AND HEK.WORK_END_DATE IS NULL AND HEK.CURRENT_STATUS_ID NOT IN (2,4,13,14)
                                        THEN 1
                                        WHEN HEK.IS_ACTIVE=0 AND HEK.WORK_START_DATE IS NOT NULL AND HEK.WORK_END_DATE IS NOT NULL  AND  HEK.WORK_END_DATE !=HEK.WORK_START_DATE THEN 1
                                        ELSE 0 END
                                        ) =1
                                        AND(
                                        (
                                        (TO_CHAR(HEK.WORK_END_DATE, 'YYYY-MM') >= TO_CHAR(TO_DATE(($FILTERYEAR ||'-'||$FILTERMONTH),'YYYY-MM'),'YYYY-MM')
                                        OR HEK.WORK_END_DATE IS NULL
                                        ) 
                                        AND TO_CHAR(HEK.WORK_START_DATE, 'YYYY-MM') <= TO_CHAR(TO_DATE(($FILTERYEAR ||'-'||$FILTERMONTH),'YYYY-MM'),'YYYY-MM')
                                        ))
                                        /*AND 
                                        HEK.DEPARTMENT_ID IN (
                                        $FILTERDEPARTMENTID
                                        )*/
                                        )
                                        SELECT 
                                        TETPH.ID,
                                           $getUID AS LOG_ID,
                                            TETPH.YEAR_ID,
                                            TETPH.MONTH_ID,                                            
                                            TETPH.EMPLOYEE_ID,
                                            TETPH.EMPLOYEE_KEY_ID,
                                            SYSDATE AS CREATED_DATE,
                                           $userKeyId AS CREATED_USER_ID,
                                            TETPH.D1,
                                            TETPH.D2,
                                            TETPH.D3,
                                            TETPH.D4,
                                            TETPH.D5,
                                            TETPH.D6,
                                            TETPH.D7,
                                            TETPH.D8,
                                            TETPH.D9,
                                            TETPH.D10,
                                            TETPH.D11,
                                            TETPH.D12,
                                            TETPH.D13,
                                            TETPH.D14,
                                            TETPH.D15,
                                            TETPH.D16,
                                            TETPH.D17,
                                            TETPH.D18,
                                            TETPH.D19,
                                            TETPH.D20,
                                            TETPH.D21,
                                            TETPH.D22,
                                            TETPH.D23,
                                            TETPH.D24,
                                            TETPH.D25,
                                            TETPH.D26,
                                            TETPH.D27,
                                            TETPH.D28,
                                            TETPH.D29,
                                            TETPH.D30,
                                            TETPH.D31
                                        FROM VW_TMS_EMPLOYEE VE
                                        /*INNER JOIN ( 
                                        SELECT
                                        MAX(EMPLOYEE_KEY_ID) AS EMPLOYEE_KEY_ID
                                        FROM
                                        HRM_EMPLOYEE_KEY
                                        WHERE (
                                        ((TO_CHAR(WORK_END_DATE, 'YYYY-MM') >=  TO_CHAR(TO_DATE(($FILTERYEAR ||'-'||$FILTERMONTH),'YYYY-MM'),'YYYY-MM') OR WORK_END_DATE IS NULL)
                                        ))
                                        GROUP BY
                                        EMPLOYEE_ID
                                        ) ACTIVE_HEK ON ACTIVE_HEK.EMPLOYEE_KEY_ID = VE.EMPLOYEE_KEY_ID                     
                                        LEFT JOIN ( 
                                        SELECT
                                        BB.NEW_EMPLOYEE_KEY_ID AS EMPLOYEE_KEY_ID,
                                        '0' AS LIMITLESS
                                        FROM HCM_LABOUR_BOOK AA
                                        INNER JOIN HCM_LABOUR_BOOK_DTL BB ON AA.ID = BB.BOOK_ID
                                        INNER JOIN HRM_EMPLOYEE_KEY LK ON LK.EMPLOYEE_KEY_ID = BB.NEW_EMPLOYEE_KEY_ID
                                        WHERE AA.BOOK_TYPE_ID IN (9024,9025,9026,9048) AND LK.CURRENT_STATUS_ID NOT IN (1, 3)
                                        UNION ALL
                                        SELECT
                                        BB.NEW_EMPLOYEE_KEY_ID AS EMPLOYEE_KEY_ID,
                                        '1' AS LIMITLESS
                                        FROM HCM_LABOUR_BOOK AA
                                        INNER JOIN HCM_LABOUR_BOOK_DTL BB ON AA.ID = BB.BOOK_ID
                                        INNER JOIN HRM_EMPLOYEE_KEY LK ON LK.EMPLOYEE_KEY_ID = BB.NEW_EMPLOYEE_KEY_ID
                                        WHERE AA.BOOK_TYPE_ID IN (9024,9025,9026,9048) AND TO_CHAR(BB.START_DATE + 30, 'YYYY-MM') >= TO_CHAR(TO_DATE(($FILTERYEAR ||'-'||$FILTERMONTH),'YYYY-MM'),'YYYY-MM')  AND TO_CHAR(BB.START_DATE - 30, 'YYYY-MM') <= TO_CHAR(TO_DATE(($FILTERYEAR ||'-'||$FILTERMONTH),'YYYY-MM'),'YYYY-MM') AND LK.CURRENT_STATUS_ID NOT IN (1, 3)
                                        ) LAB_HEK ON LAB_HEK.EMPLOYEE_KEY_ID = ACTIVE_HEK.EMPLOYEE_KEY_ID                                                                                      
                                        INNER JOIN HRM_EMPLOYEE_KEY ek ON ACTIVE_HEK.EMPLOYEE_KEY_ID = ek.EMPLOYEE_KEY_ID*/
                                        INNER JOIN EMPKEY EK ON VE.EMPLOYEE_KEY_ID=EK.EMPLOYEE_KEY_ID
                                        LEFT JOIN TMS_EMPLOYEE_TIME_PLAN_HDR TETPH ON TETPH.YEAR_ID = $FILTERYEAR AND TETPH.MONTH_ID = $FILTERMONTH  AND TETPH.EMPLOYEE_ID = VE.EMPLOYEE_ID
                                        LEFT JOIN TNA_EMPLOYEE_GROUP_CONFIG TEG ON TEG.EMPLOYEE_ID =VE.EMPLOYEE_ID
                                        LEFT JOIN (
                                                SELECT
                                                    pl.PLAN_ID,
                                                    pl.COLOR, 
                                                    pl.SHORT_NAME,
                                                    FNC_GET_TMS_PLAN_TIME(pl.PLAN_ID) PLAN_TIME
                                                FROM TMS_TIME_PLAN pl
                                            ) tem1 ON tem1.PLAN_ID = TETPH.D1  LEFT JOIN (
                                                SELECT
                                                    pl.PLAN_ID,
                                                    pl.COLOR, 
                                                    pl.SHORT_NAME,
                                                    FNC_GET_TMS_PLAN_TIME(pl.PLAN_ID) PLAN_TIME
                                                FROM TMS_TIME_PLAN pl
                                            ) tem2 ON tem2.PLAN_ID = TETPH.D2  LEFT JOIN (
                                                SELECT
                                                    pl.PLAN_ID,
                                                    pl.COLOR, 
                                                    pl.SHORT_NAME,
                                                    FNC_GET_TMS_PLAN_TIME(pl.PLAN_ID) PLAN_TIME
                                                FROM TMS_TIME_PLAN pl
                                            ) tem3 ON tem3.PLAN_ID = TETPH.D3  LEFT JOIN (
                                                SELECT
                                                    pl.PLAN_ID,
                                                    pl.COLOR, 
                                                    pl.SHORT_NAME,
                                                    FNC_GET_TMS_PLAN_TIME(pl.PLAN_ID) PLAN_TIME
                                                FROM TMS_TIME_PLAN pl
                                            ) tem4 ON tem4.PLAN_ID = TETPH.D4  LEFT JOIN (
                                                SELECT
                                                    pl.PLAN_ID,
                                                    pl.COLOR, 
                                                    pl.SHORT_NAME,
                                                    FNC_GET_TMS_PLAN_TIME(pl.PLAN_ID) PLAN_TIME
                                                FROM TMS_TIME_PLAN pl
                                            ) tem5 ON tem5.PLAN_ID = TETPH.D5  LEFT JOIN (
                                                SELECT
                                                    pl.PLAN_ID,
                                                    pl.COLOR, 
                                                    pl.SHORT_NAME,
                                                    FNC_GET_TMS_PLAN_TIME(pl.PLAN_ID) PLAN_TIME
                                                FROM TMS_TIME_PLAN pl
                                            ) tem6 ON tem6.PLAN_ID = TETPH.D6  LEFT JOIN (
                                                SELECT
                                                    pl.PLAN_ID,
                                                    pl.COLOR, 
                                                    pl.SHORT_NAME,
                                                    FNC_GET_TMS_PLAN_TIME(pl.PLAN_ID) PLAN_TIME
                                                FROM TMS_TIME_PLAN pl
                                            ) tem7 ON tem7.PLAN_ID = TETPH.D7  LEFT JOIN (
                                                SELECT
                                                    pl.PLAN_ID,
                                                    pl.COLOR, 
                                                    pl.SHORT_NAME,
                                                    FNC_GET_TMS_PLAN_TIME(pl.PLAN_ID) PLAN_TIME
                                                FROM TMS_TIME_PLAN pl
                                            ) tem8 ON tem8.PLAN_ID = TETPH.D8  LEFT JOIN (
                                                SELECT
                                                    pl.PLAN_ID,
                                                    pl.COLOR, 
                                                    pl.SHORT_NAME,
                                                    FNC_GET_TMS_PLAN_TIME(pl.PLAN_ID) PLAN_TIME
                                                FROM TMS_TIME_PLAN pl
                                            ) tem9 ON tem9.PLAN_ID = TETPH.D9  LEFT JOIN (
                                                SELECT
                                                    pl.PLAN_ID,
                                                    pl.COLOR, 
                                                    pl.SHORT_NAME,
                                                    FNC_GET_TMS_PLAN_TIME(pl.PLAN_ID) PLAN_TIME
                                                FROM TMS_TIME_PLAN pl
                                            ) tem10 ON tem10.PLAN_ID = TETPH.D10  LEFT JOIN (
                                                SELECT
                                                    pl.PLAN_ID,
                                                    pl.COLOR, 
                                                    pl.SHORT_NAME,
                                                    FNC_GET_TMS_PLAN_TIME(pl.PLAN_ID) PLAN_TIME
                                                FROM TMS_TIME_PLAN pl
                                            ) tem11 ON tem11.PLAN_ID = TETPH.D11  LEFT JOIN (
                                                SELECT
                                                    pl.PLAN_ID,
                                                    pl.COLOR, 
                                                    pl.SHORT_NAME,
                                                    FNC_GET_TMS_PLAN_TIME(pl.PLAN_ID) PLAN_TIME
                                                FROM TMS_TIME_PLAN pl
                                            ) tem12 ON tem12.PLAN_ID = TETPH.D12  LEFT JOIN (
                                                SELECT
                                                    pl.PLAN_ID,
                                                    pl.COLOR, 
                                                    pl.SHORT_NAME,
                                                    FNC_GET_TMS_PLAN_TIME(pl.PLAN_ID) PLAN_TIME
                                                FROM TMS_TIME_PLAN pl
                                            ) tem13 ON tem13.PLAN_ID = TETPH.D13  LEFT JOIN (
                                                SELECT
                                                    pl.PLAN_ID,
                                                    pl.COLOR, 
                                                    pl.SHORT_NAME,
                                                    FNC_GET_TMS_PLAN_TIME(pl.PLAN_ID) PLAN_TIME
                                                FROM TMS_TIME_PLAN pl
                                            ) tem14 ON tem14.PLAN_ID = TETPH.D14  LEFT JOIN (
                                                SELECT
                                                    pl.PLAN_ID,
                                                    pl.COLOR, 
                                                    pl.SHORT_NAME,
                                                    FNC_GET_TMS_PLAN_TIME(pl.PLAN_ID) PLAN_TIME
                                                FROM TMS_TIME_PLAN pl
                                            ) tem15 ON tem15.PLAN_ID = TETPH.D15  LEFT JOIN (
                                                SELECT
                                                    pl.PLAN_ID,
                                                    pl.COLOR, 
                                                    pl.SHORT_NAME,
                                                    FNC_GET_TMS_PLAN_TIME(pl.PLAN_ID) PLAN_TIME
                                                FROM TMS_TIME_PLAN pl
                                            ) tem16 ON tem16.PLAN_ID = TETPH.D16  LEFT JOIN (
                                                SELECT
                                                    pl.PLAN_ID,
                                                    pl.COLOR, 
                                                    pl.SHORT_NAME,
                                                    FNC_GET_TMS_PLAN_TIME(pl.PLAN_ID) PLAN_TIME
                                                FROM TMS_TIME_PLAN pl
                                            ) tem17 ON tem17.PLAN_ID = TETPH.D17  LEFT JOIN (
                                                SELECT
                                                    pl.PLAN_ID,
                                                    pl.COLOR, 
                                                    pl.SHORT_NAME,
                                                    FNC_GET_TMS_PLAN_TIME(pl.PLAN_ID) PLAN_TIME
                                                FROM TMS_TIME_PLAN pl
                                            ) tem18 ON tem18.PLAN_ID = TETPH.D18  LEFT JOIN (
                                                SELECT
                                                    pl.PLAN_ID,
                                                    pl.COLOR, 
                                                    pl.SHORT_NAME,
                                                    FNC_GET_TMS_PLAN_TIME(pl.PLAN_ID) PLAN_TIME
                                                FROM TMS_TIME_PLAN pl
                                            ) tem19 ON tem19.PLAN_ID = TETPH.D19  LEFT JOIN (
                                                SELECT
                                                    pl.PLAN_ID,
                                                    pl.COLOR, 
                                                    pl.SHORT_NAME,
                                                    FNC_GET_TMS_PLAN_TIME(pl.PLAN_ID) PLAN_TIME
                                                FROM TMS_TIME_PLAN pl
                                            ) tem20 ON tem20.PLAN_ID = TETPH.D20  LEFT JOIN (
                                                SELECT
                                                    pl.PLAN_ID,
                                                    pl.COLOR, 
                                                    pl.SHORT_NAME,
                                                    FNC_GET_TMS_PLAN_TIME(pl.PLAN_ID) PLAN_TIME
                                                FROM TMS_TIME_PLAN pl
                                            ) tem21 ON tem21.PLAN_ID = TETPH.D21  LEFT JOIN (
                                                SELECT
                                                    pl.PLAN_ID,
                                                    pl.COLOR, 
                                                    pl.SHORT_NAME,
                                                    FNC_GET_TMS_PLAN_TIME(pl.PLAN_ID) PLAN_TIME
                                                FROM TMS_TIME_PLAN pl
                                            ) tem22 ON tem22.PLAN_ID = TETPH.D22  LEFT JOIN (
                                                SELECT
                                                    pl.PLAN_ID,
                                                    pl.COLOR, 
                                                    pl.SHORT_NAME,
                                                    FNC_GET_TMS_PLAN_TIME(pl.PLAN_ID) PLAN_TIME
                                                FROM TMS_TIME_PLAN pl
                                            ) tem23 ON tem23.PLAN_ID = TETPH.D23  LEFT JOIN (
                                                SELECT
                                                    pl.PLAN_ID,
                                                    pl.COLOR, 
                                                    pl.SHORT_NAME,
                                                    FNC_GET_TMS_PLAN_TIME(pl.PLAN_ID) PLAN_TIME
                                                FROM TMS_TIME_PLAN pl
                                            ) tem24 ON tem24.PLAN_ID = TETPH.D24  LEFT JOIN (
                                                SELECT
                                                    pl.PLAN_ID,
                                                    pl.COLOR, 
                                                    pl.SHORT_NAME,
                                                    FNC_GET_TMS_PLAN_TIME(pl.PLAN_ID) PLAN_TIME
                                                FROM TMS_TIME_PLAN pl
                                            ) tem25 ON tem25.PLAN_ID = TETPH.D25  LEFT JOIN (
                                                SELECT
                                                    pl.PLAN_ID,
                                                    pl.COLOR, 
                                                    pl.SHORT_NAME,
                                                    FNC_GET_TMS_PLAN_TIME(pl.PLAN_ID) PLAN_TIME
                                                FROM TMS_TIME_PLAN pl
                                            ) tem26 ON tem26.PLAN_ID = TETPH.D26  LEFT JOIN (
                                                SELECT
                                                    pl.PLAN_ID,
                                                    pl.COLOR, 
                                                    pl.SHORT_NAME,
                                                    FNC_GET_TMS_PLAN_TIME(pl.PLAN_ID) PLAN_TIME
                                                FROM TMS_TIME_PLAN pl
                                            ) tem27 ON tem27.PLAN_ID = TETPH.D27  LEFT JOIN (
                                                SELECT
                                                    pl.PLAN_ID,
                                                    pl.COLOR, 
                                                    pl.SHORT_NAME,
                                                    FNC_GET_TMS_PLAN_TIME(pl.PLAN_ID) PLAN_TIME
                                                FROM TMS_TIME_PLAN pl
                                            ) tem28 ON tem28.PLAN_ID = TETPH.D28  LEFT JOIN (
                                                SELECT
                                                    pl.PLAN_ID,
                                                    pl.COLOR, 
                                                    pl.SHORT_NAME,
                                                    FNC_GET_TMS_PLAN_TIME(pl.PLAN_ID) PLAN_TIME
                                                FROM TMS_TIME_PLAN pl
                                            ) tem29 ON tem29.PLAN_ID = TETPH.D29  LEFT JOIN (
                                                SELECT
                                                    pl.PLAN_ID,
                                                    pl.COLOR, 
                                                    pl.SHORT_NAME,
                                                    FNC_GET_TMS_PLAN_TIME(pl.PLAN_ID) PLAN_TIME
                                                FROM TMS_TIME_PLAN pl
                                            ) tem30 ON tem30.PLAN_ID = TETPH.D30 
                                            LEFT JOIN (
                                                SELECT
                                                    pl.PLAN_ID,
                                                    pl.COLOR, 
                                                    pl.SHORT_NAME,
                                                    FNC_GET_TMS_PLAN_TIME(pl.PLAN_ID) PLAN_TIME
                                                FROM TMS_TIME_PLAN pl
                                            ) tem31 ON tem31.PLAN_ID = TETPH.D31 
                                        WHERE  VE.DEPARTMENT_ID IN (
                                            SELECT 
                                            DEPARTMENT_ID 
                                            FROM 
                                            ORG_DEPARTMENT START WITH DEPARTMENT_ID in ($FILTERDEPARTMENTID) CONNECT BY NOCYCLE PRIOR DEPARTMENT_ID = PARENT_ID
                                        )
                                        AND
                                        (UPPER(VE.FIRST_NAME) LIKE '%'||UPPER(REPLACE('$filterStringValue', '%', ''))||'%'
                                        OR UPPER(VE.LAST_NAME) LIKE '%'||UPPER(REPLACE('$filterStringValue', '%', ''))||'%'
                                        OR UPPER(VE.CODE) LIKE '%'||UPPER(REPLACE('$filterStringValue', '%', ''))||'%'
                                        OR NULL IS NULL)

                                        AND (NULL IS NULL OR  ( 
                                            VE.POSITION_ID IN (
                                            SELECT 
                                                POSITION_ID
                                            FROM 
                                                HRM_POSITION 
                                                where position_id IN ('$FILTERPOSITIONID')
                                            ) 
                                            )
                                        ) 
                                        AND TETPH.ID IS NOT NULL
                                        ORDER BY VE.FIRST_NAME, VE.POSITION_NAME ASC
                                    ) T0";      
                
                $insertHdr = $this->db->Execute($insertHdrQuery);
                
                if ($insertHdr) {
                    $insertDtl = $this->db->Execute(
                        "INSERT INTO TMS_EMPLOYEE_PLAN_LOG_DTL (
                            ID, LOG_ID, PLAN_ID, PLAN_DATE
                        )
                        SELECT 
                            IMPORT_ID_SEQ.NEXTVAL, 
                            $getUID,
                            T0.PLAN_ID,
                            T0.PLAN_DATE
                        FROM (
                            SELECT 
                                DISTINCT
                                T1.PLAN_ID,
                                T1.PLAN_DATE
                            FROM 
                                TMS_EMPLOYEE_TIME_PLAN_HDR p
                            INNER JOIN TMS_EMPLOYEE_TIME_PLAN_DTL T1 ON p.ID = T1.TIME_PLAN_ID
                            INNER JOIN UM_USER u on p.CREATED_USER_ID = u.USER_ID
                            WHERE  TO_CHAR(T1.PLAN_DATE, 'YYYY-MM') = '" . $planYear . "-" . $planMonth . "'
                        ) T0");

                    if ($insertDtl) {
                        $response = array(
                            'status' => 'success',
                            'message' => 'Амжилттай архив үүслээ',
                            'archivList' => self::getArchivListV2Model($planYear, $planMonth)
                        );
                    }
                }

                if (Config::getFromCache('TMS_PLAN_ARCHIEVE_BY_DEPARTMENT') && $departmentIds['array']) {
                    foreach ($departmentIds['array'] as $dddkey => $dddrow) {
                        $insertData = array(
                            'ID'            => getUIDAdd($dddkey), 
                            'LOG_ID'        => $getUID, 
                            'DEPARTMENT_ID' => $dddrow['DEPARTMENT_ID']
                        );

                        $this->db->AutoExecute('TMS_EMPLOYEE_PLAN_LOG_DEP', $insertData);                    
                    }
                }

            }
        } catch (Exception $ex) {
            $response = array(
                            'status' => 'error',
                            'message' => 'Амжилтгүй боллоо',
                            'exception' => $ex
                        );
        }

        return $response;
    }        

    public function getArchivVersionNumberModel($planYear, $planMonth) {

        $response = 1;
        $result = $this->db->GetOne("
            SELECT 
                VERSION_NUMBER
            FROM TNA_EMPLOYEE_PLAN_LOG 
            WHERE YEAR_ID = $planYear and MONTH_ID = $planMonth ORDER BY VERSION_NUMBER DESC");

        if ($result) {
            return $result + $response;
        }
        return $response;
    }        

    public function getRefYearIdModel($yearCode) {
        $result = $this->db->GetOne("
            SELECT 
                YEAR_ID
            FROM REF_YEAR 
            WHERE LOWER(YEAR_CODE) = LOWER('" . $yearCode . "')");
        if ($result) {
            return $result;
        }
        return false;
    }

    public function getRefMonthListModel() {
        $value = Input::post('selected');
        $value2 = '';

        if ($value) {
            $result = $this->db->GetAll("SELECT MONTH_ID, MONTH_NAME, MONTH_CODE FROM REF_MONTH ORDER BY MONTH_CODE");
            $value = (int) $value;

            $pushArray = array();
            if ($value === 1) {
                $value2 = 12;
                array_push($pushArray, array(
                    'MONTH_ID' => "12",
                    'MONTH_NAME' => "12-р сар",
                    'MONTH_CODE' => "12"                            
                ));
            } else {
                $value--;
                $value1 = $value < 10 ? '0'.$value : $value;
                array_push($pushArray, array(
                    'MONTH_ID' => $value,
                    'MONTH_NAME' => $value."-р сар",
                    'MONTH_CODE' => $value1                            
                ));          
                $value2 = $value;
            }                

            foreach ($result as $row) {
                if ((int) $row['MONTH_CODE'] >= $value && (int) $row['MONTH_CODE'] != $value2) {
                    array_push($pushArray, $row);
                }
            }

            foreach ($result as $row) {
                if ((int) $row['MONTH_CODE'] < $value && (int) $row['MONTH_CODE'] != $value2) {
                    array_push($pushArray, $row);
                }
            }

            $result = $pushArray;
        } else {
            $result = $this->db->GetAll("SELECT MONTH_ID, MONTH_NAME, MONTH_CODE FROM REF_MONTH ORDER BY MONTH_CODE");
        }
        return $result; 
    }

    public function concatGroupEmployee($ids) {
        return $this->db->GetOne("SELECT WM_CONCAT(DISTINCT EMPLOYEE_ID) FROM TNA_EMPLOYEE_GROUP_CONFIG WHERE GROUP_ID IN (".$ids.")");
    }         

    public function getRefMonthIdModel($monthCode) {
        $result = $this->db->GetOne("
            SELECT 
                MONTH_ID
            FROM REF_MONTH 
            WHERE LOWER(MONTH_CODE) = LOWER('" . $monthCode . "')");
        if ($result) {
            return $result;
        }
        return false;
    }        

    public function getWorkingDateV2Model($param = array('tableName' => 'TNA_EMPLOYEE_TIME_PLAN'), $holidays) {
        $days = cal_days_in_month(CAL_GREGORIAN, intval($param['planMonth']), intval($param['planYear'])); // 31, 30, 29, 28            
        $resultWorkingDays = self::getWorkingModel(intval($param['planMonth']), intval($param['planYear']));

        $allDay = array();

        for ($i = 1; $i <= $days; $i++) {
            $day = 'DAY_' . $i;
            $cc = $i;

            if ($i < 10) {
                $day = 'DAY_0' . $i;
                $cc = '0' . $i;
            }

            $month = (String) $param['planMonth'];
            $date = $param['planYear'] . '-' . $month . '-' . (String) $cc;
            $timestamp = strtotime($date);
            $spell_day = intval(date('N', $timestamp));
            $dayClass = 'workday';

            if ($spell_day === 6 or $spell_day === 7) {
                $dayClass = 'weekday';
            }

            $allDay[$i]['DAY'] = $cc;
            $allDay[$i]['PLAN_DATE'] = $param['planYear'] . '/' . $param['planMonth'] . '/' . $cc;
            $allDay[$i]['DAY_COLOR'] = '#ffc37b';
            $allDay[$i]['SPELL_DAY'] = $spell_day;
            $allDay[$i]['DAY_CLASS'] = $dayClass;
            $allDay[$i]['SPELL_DAY_SHORT_NAME'] = self::dayShortName($spell_day);
            $allDay[$i]['PLAN_TIME'] = '';
            $allDay[$i]['DAY_WORKING'] = '';
            $allDay[$i]['WFM_STATUS_ID'] = '';
            $allDay[$i]['PLAN_ID'] = '';
            $allDay[$i]['WFM_STATUS_CODE'] = '';
            $allDay[$i]['WFM_STATUS_NAME'] = '';
            $allDay[$i]['APPROVE_LAST_DATE'] = '';
            $allDay[$i]['IS_LOCK'] = '';
            $allDay[$i]['LOCK_END_DATE'] = '';
            $allDay[$i]['LOCK_USER_ID'] = '';
            $allDay[$i]['STATUS_DESCRIPTION'] = '';
            $allDay[$i]['HOLIDAY'] = '';
            $allDay[$i]['COLOR'] = '';
            $allDay[$i]['SHORT_NAME'] = '';

            foreach ($resultWorkingDays as $workinDay) {
                if ((int) $workinDay == (int) $i) {
                    $allDay[$i]['DAY_COLOR'] = '#FFF';
                }
            }

            foreach ($holidays as $holiday) {
                if (Date::format('Y-m-d', $holiday['START_DATE']) <= $date && $date <= Date::format('Y-m-d', $holiday['END_DATE'])) {
                    $allDay[$i]['DAY_COLOR'] = '#FFE4E1';
                    $allDay[$i]['HOLIDAY'] = $holiday['HOLIDAY_NAME'].' (' . Date::format('Y-m-d', $holiday['START_DATE']) . ' - ' . Date::format('Y-m-d', $holiday['END_DATE']) . ')';
                    $allDay[$i]['DAY_CLASS'] = 'weekday';
                }
            }
        }

        return $allDay;
    }

    public function getWorkingDateV3Model($param = array('tableName' => 'TNA_EMPLOYEE_TIME_PLAN'), $holidays) {
        $days = cal_days_in_month(CAL_GREGORIAN, intval($param['planMonth']), intval($param['planYear'])); // 31, 30, 29, 28            
        $resultWorkingDays = self::getWorkingModel(intval($param['planMonth']), intval($param['planYear']));

        $allDay = array();

        for ($i = 1; $i <= $days; $i++) {
            $day = 'DAY_' . $i;
            $cc = $i;

            if ($i < 10) {
                $day = 'DAY_0' . $i;
                $cc = '0' . $i;
            }

            $month = (String) $param['planMonth'];
            $date = $param['planYear'] . '-' . $month . '-' . (String) $cc;
            $timestamp = strtotime($date);
            $spell_day = intval(date('N', $timestamp));
            $dayClass = 'workday';

            if ($spell_day === 6 or $spell_day === 7) {
                $dayClass = 'weekday';
            }

            $allDay[$i]['DAY'] = $cc;
            $allDay[$i]['MONTH'] = (int) $month;
            $allDay[$i]['PLAN_DATE'] = $param['planYear'] . '/' . $param['planMonth'] . '/' . $cc;
            $allDay[$i]['DAY_COLOR'] = '#ffc37b';
            $allDay[$i]['SPELL_DAY'] = $spell_day;
            $allDay[$i]['DAY_CLASS'] = $dayClass;
            $allDay[$i]['SPELL_DAY_SHORT_NAME'] = self::dayShortName($spell_day);
            $allDay[$i]['PLAN_TIME'] = '';
            $allDay[$i]['DAY_WORKING'] = '';
            $allDay[$i]['WFM_STATUS_ID'] = '';
            $allDay[$i]['PLAN_ID'] = '';
            $allDay[$i]['WFM_STATUS_CODE'] = '';
            $allDay[$i]['WFM_STATUS_NAME'] = '';
            $allDay[$i]['APPROVE_LAST_DATE'] = '';
            $allDay[$i]['IS_LOCK'] = '';
            $allDay[$i]['LOCK_END_DATE'] = '';
            $allDay[$i]['LOCK_USER_ID'] = '';
            $allDay[$i]['STATUS_DESCRIPTION'] = '';
            $allDay[$i]['HOLIDAY'] = '';
            $allDay[$i]['COLOR'] = '';
            $allDay[$i]['SHORT_NAME'] = '';

            foreach ($resultWorkingDays as $workinDay) {
                if ((int) $workinDay == (int) $i) {
                    $allDay[$i]['DAY_COLOR'] = '#FFF';
                }
            }

            foreach ($holidays as $holiday) {
                if (Date::format('Y-m-d', $holiday['START_DATE']) <= $date && $date <= Date::format('Y-m-d', $holiday['END_DATE'])) {
                    $allDay[$i]['DAY_COLOR'] = '#FFE4E1';
                    $allDay[$i]['HOLIDAY'] = $holiday['HOLIDAY_NAME'].' (' . Date::format('Y-m-d', $holiday['START_DATE']) . ' - ' . Date::format('Y-m-d', $holiday['END_DATE']) . ')';
                    $allDay[$i]['DAY_CLASS'] = 'weekday';
                }
            }
        }

        return $allDay;
    }

    public function getWorkingTwoDateV3Model($param = array('tableName' => 'TNA_EMPLOYEE_TIME_PLAN'), $holidays, $caclStartDate, $caclEndDate) {                    

        if (Date::formatter($caclStartDate, 'm') == Date::formatter($caclEndDate, 'm')) {
            $days = Date::formatter($caclEndDate, 'd');
        } else {
            $days = cal_days_in_month(CAL_GREGORIAN, intval(Date::formatter($caclStartDate, 'm')), intval(Date::formatter($caclStartDate, 'Y')));
            $days2 = Date::formatter($caclEndDate, 'd');
        }            

        $selectHolidayToWorkday = "
            SELECT 
            WORK_DATE, 
            NAME
        FROM LM_WORKDAY";
        $holidaysToWorkdays = $this->db->GetAll($selectHolidayToWorkday);             

        $allDay = array();
        $startDay = (int) Date::formatter($caclStartDate, 'd');
        $monStart = (int) Date::formatter($caclStartDate, 'm');
        $monEnd = (int) Date::formatter($caclEndDate, 'm');
        $allDay['startColspan'] = $days - $startDay + 1;            
        
        $resultWorkingDays = self::getWorkingModel($monStart, intval(Date::formatter($caclStartDate, 'Y')));      

        for ($i = $startDay; $i <= $days; $i++) {
            $day = 'DAY_' . $i;
            $cc = $i;

            if ($i < 10) {
                $day = 'DAY_0' . $i;
                $cc = '0' . $i;
            }

            $month = (String) $monStart;
            $date = intval(Date::formatter($caclStartDate, 'Y')) . '-' . $month . '-' . (String) $cc;
            $date = Date::formatter($date, 'Y-m-d');
            $timestamp = strtotime($date);
            $spell_day = intval(date('N', $timestamp));
            $dayClass = 'workday';

            if ($spell_day === 6 or $spell_day === 7) {
                $dayClass = 'weekday';
            }

            $allDay[$i]['DAY'] = $cc;
            $allDay[$i]['MONTH'] = $monStart;
            $allDay[$i]['PLAN_DATE'] = Date::formatter($caclStartDate, 'Y') . '/' . ((int) Date::formatter($caclStartDate, 'm')) . '/' . $cc;
            $allDay[$i]['DAY_COLOR'] = '#ffc37b';
            $allDay[$i]['SPELL_DAY'] = $spell_day;
            $allDay[$i]['DAY_CLASS'] = $dayClass;
            $allDay[$i]['SPELL_DAY_SHORT_NAME'] = self::dayShortName($spell_day);
            $allDay[$i]['PLAN_TIME'] = '';
            $allDay[$i]['DAY_WORKING'] = '';
            $allDay[$i]['WFM_STATUS_ID'] = '';
            $allDay[$i]['PLAN_ID'] = '';
            $allDay[$i]['WFM_STATUS_CODE'] = '';
            $allDay[$i]['WFM_STATUS_NAME'] = '';
            $allDay[$i]['APPROVE_LAST_DATE'] = '';
            $allDay[$i]['IS_LOCK'] = '';
            $allDay[$i]['LOCK_END_DATE'] = '';
            $allDay[$i]['LOCK_USER_ID'] = '';
            $allDay[$i]['STATUS_DESCRIPTION'] = '';
            $allDay[$i]['HOLIDAY'] = '';
            $allDay[$i]['COLOR'] = '';
            $allDay[$i]['SHORT_NAME'] = '';

            foreach ($resultWorkingDays as $workinDay) {
                if ((int) $workinDay == (int) $i) {
                    $allDay[$i]['DAY_COLOR'] = '#FFF';
                }
            }

            foreach ($holidaysToWorkdays as $holiday) {
                if (Date::formatter($holiday['WORK_DATE'], 'Y-m-d') == $date) {
                    $allDay[$i]['DAY_COLOR'] = '#FFF';
                    $allDay[$i]['HOLIDAY'] = '';
                    $allDay[$i]['DAY_CLASS'] = 'workday';
                }
            }

            foreach ($holidays as $holiday) {
                if (Date::formatter($holiday['START_DATE'], 'Y-m-d') <= $date && $date <= Date::formatter($holiday['END_DATE'], 'Y-m-d')) {
                    $allDay[$i]['DAY_COLOR'] = '#FFE4E1';
                    $allDay[$i]['HOLIDAY'] = $holiday['HOLIDAY_NAME'].' (' . Date::formatter($holiday['START_DATE'], 'Y-m-d') . ' - ' . Date::formatter($holiday['END_DATE'], 'Y-m-d') . ')';
                    $allDay[$i]['DAY_CLASS'] = 'weekday';
                }
            }
        }

        if (isset($days2)) {
            $resultWorkingDays = self::getWorkingModel($monEnd, intval(Date::formatter($caclEndDate, 'Y')));
            $allDay['endColspan'] = 0;
            for ($i = 1; $i <= $days2; $i++) {
                if (array_key_exists($i, $allDay)) continue;

                $allDay['endColspan'] += 1;
                $day = 'DAY_' . $i;
                $cc = $i;

                if ($i < 10) {
                    $day = 'DAY_0' . $i;
                    $cc = '0' . $i;
                }

                $month = (String) $monEnd;
                $date = Date::formatter($caclEndDate, 'Y') . '-' . $month . '-' . (String) $cc;
                $date = Date::formatter($date, 'Y-m-d');
                $timestamp = strtotime($date);
                $spell_day = intval(date('N', $timestamp));
                $dayClass = 'workday';

                if ($spell_day === 6 or $spell_day === 7) {
                    $dayClass = 'weekday';
                }

                $allDay[$i]['DAY'] = $cc;
                $allDay[$i]['MONTH'] = $monEnd;
                $allDay[$i]['PLAN_DATE'] = Date::formatter($caclEndDate, 'Y') . '/' . ((int) Date::formatter($caclEndDate, 'm')) . '/' . $cc;
                $allDay[$i]['DAY_COLOR'] = '#ffc37b';
                $allDay[$i]['SPELL_DAY'] = $spell_day;
                $allDay[$i]['DAY_CLASS'] = $dayClass;
                $allDay[$i]['SPELL_DAY_SHORT_NAME'] = self::dayShortName($spell_day);
                $allDay[$i]['PLAN_TIME'] = '';
                $allDay[$i]['DAY_WORKING'] = '';
                $allDay[$i]['WFM_STATUS_ID'] = '';
                $allDay[$i]['PLAN_ID'] = '';
                $allDay[$i]['WFM_STATUS_CODE'] = '';
                $allDay[$i]['WFM_STATUS_NAME'] = '';
                $allDay[$i]['APPROVE_LAST_DATE'] = '';
                $allDay[$i]['IS_LOCK'] = '';
                $allDay[$i]['LOCK_END_DATE'] = '';
                $allDay[$i]['LOCK_USER_ID'] = '';
                $allDay[$i]['STATUS_DESCRIPTION'] = '';
                $allDay[$i]['HOLIDAY'] = '';
                $allDay[$i]['COLOR'] = '';
                $allDay[$i]['SHORT_NAME'] = '';

                foreach ($resultWorkingDays as $workinDay) {
                    if ((int) $workinDay == (int) $i) {
                        $allDay[$i]['DAY_COLOR'] = '#FFF';
                    }
                }

                foreach ($holidaysToWorkdays as $holiday) {
                    if (Date::formatter($holiday['WORK_DATE'], 'Y-m-d') == $date) {
                        $allDay[$i]['DAY_COLOR'] = '#FFF';
                        $allDay[$i]['HOLIDAY'] = '';
                        $allDay[$i]['DAY_CLASS'] = 'workday';
                    }
                }                

                foreach ($holidays as $holiday) {
                    if (Date::formatter($holiday['START_DATE'], 'Y-m-d') <= $date && $date <= Date::formatter($holiday['END_DATE'], 'Y-m-d')) {
                        $allDay[$i]['DAY_COLOR'] = '#FFE4E1';
                        $allDay[$i]['HOLIDAY'] = $holiday['HOLIDAY_NAME'].' (' . Date::formatter($holiday['START_DATE'], 'Y-m-d') . ' - ' . Date::formatter($holiday['END_DATE'], 'Y-m-d') . ')';
                        $allDay[$i]['DAY_CLASS'] = 'weekday';
                    }
                }                
            }
        }

        return $allDay;
    }

    public function getWorkingModel($month, $year) {
        $workdays = array();
        $type = CAL_GREGORIAN;
        $month = $month; // Month ID, 1 through to 12.
        $year = $year; // Year in 4 digit 2009 format.
        $day_count = cal_days_in_month($type, $month, $year); // Get the amount of days
        //loop through all days
        for ($i = 1; $i <= $day_count; $i++) {

            $date = $year . '/' . $month . '/' . $i; //format date
            $get_name = date('l', strtotime($date)); //get week day
            $day_name = substr($get_name, 0, 3); // Trim day name to 3 chars
            //if not a weekend add day to array
            if ($day_name != 'Sun' && $day_name != 'Sat') {
                $workdays[] = $i;
            }
        }
        return $workdays;
    }

    public function dayShortName($day) {
        switch ($day) {
            case '1': return 'Да';
            case '2': return 'Мя';
            case '3': return 'Лх';
            case '4': return 'Пү';
            case '5': return 'Ба';
            case '6': return 'Бя';
            case '7': return 'Ня';
        }
    }    

    public function empPlanListMainDataGridV2Model() {
        $response = array('rowCount' => '0', 'html' => '', 'status' => 'error');
        $isHishigArvin = Config::getFromCache('CONFIG_TNA_HISHIGARVIN');
        $result = $footerArr = $newBody = array();
        $whereString = $theader = $tbody = $tfooter = $filterRuleString = '';
        if (Input::postCheck('params')) {
            parse_str(Input::post('params'), $params);
            $page = Input::postCheck('page') ? Input::post('page') : 1;
            $rows = Input::postCheck('rows') ? Input::post('rows') : 10;
            $offset = ($page - 1) * $rows;
            $isGolomt = $params['golomtView'];
            $golomtView = isset($params['golomtView']) ? (($params['golomtView']) ? 'Домайн' : 'Код') : 'Код';

            if (!empty($params['planYear']) && !empty($params['planMonth'])) {

                $employeers = array();
                $headerDays = array();
                $currentStatusNotIn = Config::getFromCache('tmsCurrentStatus');
                $statusNotIn = Config::getFromCache('tmsStatus');                    

                if (!empty($params['newDepartmentId']) || (isset($params['groupId']) && is_array($params['groupId']))) {

                    $bookTypeIds = '9024,9025,9026,9048'; 
                    $ticket = is_array($params['newDepartmentId']) ? false : strpos($params['newDepartmentId'], ',');

                    if (is_array($params['newDepartmentId']) && count($params['newDepartmentId']) && implode(',', $params['newDepartmentId'])) {
                        $departmentIds = $params['newDepartmentId'];
                        $departmentIds = implode(',', $departmentIds);
                        $isChild = issetVar($params['isChild']);

                        $departmentIds = $this->getAllChildDepartmentModel($departmentIds, $isChild);

                        $whereString .= " AND VE.DEPARTMENT_ID IN ( " . $departmentIds . ") ";

                    } elseif ($params['newDepartmentId'] && Config::getFromCache('tmsCustomerCode') == 'gov') {
                        $departmentIds = $params['newDepartmentId'];
                        $isChild = issetVar($params['isChild']);

                        $departmentIds = $this->getAllChildDepartmentModel($departmentIds, $isChild);

                        $whereString .= " AND VE.DEPARTMENT_ID IN ( " . $departmentIds . ") ";
                    } elseif (isset($params['groupId']) && is_array($params['groupId'])) {
                        $whereString .= " AND VE.EMPLOYEE_ID IN ( SELECT EMPLOYEE_ID FROM TNA_EMPLOYEE_GROUP_CONFIG WHERE GROUP_ID IN (" . implode(',', $params['groupId']) . ")) ";
                    }

                    $params['departmentId'] = !empty($departmentIds) ? explode(',', $departmentIds) : '';       

                    $paramDepartmentIds = $params['departmentId'];
                    $userId = Ue::sessionUserId();

                    (Array) $departmentId = array();

                    if (isset($params['positionId']) && is_array($params['positionId'])) {
                        $whereString .= " AND VE.POSITION_ID IN (" . implode(',', $params['positionId']) . ")";
                    }

                    if (isset($params['positionGroupId']) && $params['positionGroupId'] != '') {
                        $whereString .= " AND VE.POSITION_GROUP_ID = " . $params['positionGroupId'];
                    }

                    if (isset($params['employeeStatus']) && empty($params['employeeStatus']) === false) {
                        $employeeStatusId = implode(',', $params['employeeStatus']);
                        $whereString .= " AND ek.STATUS_ID IN (". $employeeStatusId .")";
                    }

                    if (isset($params['stringValue']) && empty($params['stringValue']) === false && $params['stringValue'] != '') {

                        if (strpos($params['stringValue'], '.') === false) {
                            $filterRuleString .= " AND (LOWER(LAST_NAME) LIKE LOWER('%" . $params['stringValue'] . "%') OR LOWER(FIRST_NAME) LIKE LOWER('%" . $params['stringValue'] . "%') OR LOWER(CODE) LIKE LOWER('%" . $params['stringValue'] . "%') OR LOWER(REGISTER_NUMBER) LIKE LOWER('%" . $params['stringValue'] . "%')) ";
                        } else {
                            $strexplode = explode('.', $params['stringValue']);
                            $filterRuleString .= " AND (LOWER(LAST_NAME) LIKE LOWER('%" . $strexplode[0] . "%') OR LOWER(FIRST_NAME) LIKE LOWER('%" . $strexplode[1] . "%')) ";
                        }
                    }                        

                    if (Input::postCheck('filterRules')) {
                        $filterRules = json_decode(Str::cp1251_utf8($_POST['filterRules']), true);                
                        if ($filterRules) {
                            foreach ($filterRules as $rule) {
                                $field = $rule['field'];
                                $value = Input::param(Str::lower($rule['value']));
                                if (!empty($value)) {
                                    if ($field === 'employeename') {
                                        $whereString .= " AND ((LOWER(VE.FIRST_NAME) LIKE '%$value%') OR (LOWER(VE.REGISTER_NUMBER) LIKE '%$value%'))";
                                    } elseif ($field === 'employeeposition') {
                                        $whereString .= " AND (LOWER(VE.POSITION_NAME) LIKE '%$value%')";
                                    }
                                }
                            }
                        }
                    }

                    $causeString1 = '';
                    $leftJoin = '';

                    $resultCountt = $this->db->GetOne("SELECT COUNT(ID) AS COUNTT FROM TNA_EMPLOYEE_PLAN_OWNER WHERE USER_ID = $userId");

                    if ((int) $resultCountt != 0) {
                        $resultDepartment = $this->db->GetAll("SELECT DISTINCT DEPARTMENT_ID FROM TNA_EMPLOYEE_PLAN_OWNER WHERE USER_ID = $userId");
                        foreach ($resultDepartment as $key => $deparment) {
                            if (!in_array($deparment['DEPARTMENT_ID'], $departmentId)) {
                                array_push($departmentId, $deparment['DEPARTMENT_ID']);
                            }
                        }
                        $response = array('CHECK' => '1', 'departmentIds' => $departmentId);
                    }

                    (Array) $departmentIdArr = array();
                    if (sizeof($departmentId) > 0) {
                        foreach ($paramDepartmentIds as $depart) {
                            if (in_array($depart, $departmentId)) {
                                array_push($departmentIdArr, $depart);
                            }
                        }
                    } else {
                        $departmentIdArr = $paramDepartmentIds;
                    }

                        if(empty($departmentIdArr)) {
                            $causeString1 .= " AND VE.EMPLOYEE_ID IN (SELECT EMPLOYEE_ID FROM TNA_EMPLOYEE_GROUP_CONFIG WHERE GROUP_ID IN (" . implode(',', $params['groupId']) . "))";
                            $departmentList = $this->db->GetAll("SELECT DISTINCT OD.DEPARTMENT_ID, OD.DEPARTMENT_NAME, OD.DISPLAY_ORDER  
                                                                FROM ORG_DEPARTMENT OD
                                                                INNER JOIN VW_EMPLOYEE VE ON VE.DEPARTMENT_ID = OD.DEPARTMENT_ID
                                                                WHERE VE.EMPLOYEE_ID IN (SELECT EMPLOYEE_ID FROM TNA_EMPLOYEE_GROUP_CONFIG WHERE GROUP_ID IN (" . implode(',', $params['groupId']) . "))
                                                                ORDER BY LPAD(OD.DISPLAY_ORDER, 10) ASC");    

                            $departmentIds = Arr::implode_key(',', $departmentList, 'DEPARTMENT_ID', true);
                        } else {
                            $departmentIds = implode(',', $departmentIdArr);
                            if((isset($params['groupId']) && is_array($params['groupId']))) {
                                $causeString1 .= " AND VE.EMPLOYEE_ID IN (SELECT EMPLOYEE_ID FROM TNA_EMPLOYEE_GROUP_CONFIG WHERE GROUP_ID IN (" . implode(',', $params['groupId']) . "))";
                            }
                            $departmentList = $this->db->GetAll("SELECT DEPARTMENT_ID, DEPARTMENT_NAME FROM ORG_DEPARTMENT WHERE DEPARTMENT_ID IN (" . $departmentIds . ") ORDER BY LPAD(DISPLAY_ORDER, 10) ASC");                                
                        }                        

                        $leftJoinDay = $leftJoinAttr = $groupBy = '';
                            $days = cal_days_in_month(CAL_GREGORIAN, intval($params['planMonth']), intval($params['planYear'])); // 31, 30, 29, 28
                            for ($iday = 1; $iday <= $days; $iday ++ ) {
                                $dayAddin = $iday;

                                $leftJoinAttr  .= "
                                    ROUND(tem$iday.PLAN_TIME/60, 2) AS PLAN_TIME_$iday, 
                                    tem$iday.PLAN_ID AS PLAN_ID_$iday,
                                    tem$iday.SHORT_NAME AS SHORT_NAME_$iday,
                                    tem$iday.COLOR AS COLOR_$iday,
                                        ";
                                $groupBy  .= "tem$iday.PLAN_TIME, 
                                    tem$iday.PLAN_ID,
                                    tem$iday.SHORT_NAME,
                                    tem$iday.COLOR,
                                        ";

                                $leftJoinDay .= " LEFT JOIN (
                                                        SELECT
                                                            pl.PLAN_ID,
                                                            pl.COLOR, 
                                                            pl.SHORT_NAME,
                                                            FNC_GET_TMS_PLAN_TIME(pl.PLAN_ID) PLAN_TIME
                                                        FROM TMS_TIME_PLAN pl
                                                    ) tem$iday ON tem$iday.PLAN_ID = TETPH.D$dayAddin ";
                            }

                            $tmsPlanPreview = "((TO_CHAR(WORK_END_DATE, 'YYYY-MM') >= '" . $params['planYear'] ."-". $params['planMonth'] . "') OR WORK_END_DATE IS NULL)";
                            $monthLimit = Config::getFromCache('tmsMonthFilter') || Config::getFromCache('tmsMonthFilter') == '0' ? Config::getFromCache('tmsMonthFilter') : '30';                   

                            $tmsCustomerCode = Config::getFromCache('tmsCustomerCode');                                

                            if(Config::getFromCache('tmsPlanPreview') == '1') {
                                $tmsPlanPreview = "((TO_CHAR(WORK_END_DATE + $monthLimit, 'YYYY-MM') >= '" . $params['planYear'] ."-". $params['planMonth'] . "') OR WORK_END_DATE IS NULL)";
                            }                                             

                            if ($tmsCustomerCode === 'khaanbank') {
                                $tmsPlanPreview .= '  OR (  TO_CHAR(WORK_END_DATE,\'YYYY-MM\') >= \'' . $params['planYear'] .'-'. $params['planMonth'] . '\' AND  WORK_END_DATE IS NOT NULL)';
                            }                                

                            (String) $tableColumn2 = '';
                            (String) $orderColumn = '';

                            if (Config::getFromCache('tmsCustomerCode') == 'gov') {
                                $tableColumn2 = ", VE.DEP_ORDER, VE.POS_ORDER, VE.WORK_START_DATE ";
                                $orderColumn = "ORDER BY LPAD(DEP_ORDER, 10), LPAD(POS_ORDER, 10), WORK_START_DATE ASC";
                            }                                

                            $employeeCount = $this->db->GetOne("SELECT COUNT(tem.EMPLOYEE_ID) FROM (
                                SELECT 
                                    VE.EMPLOYEE_ID,
                                    VE.EMPLOYEE_KEY_ID,
                                    VE.LAST_NAME,
                                    VE.FIRST_NAME,
                                    VE.CODE,
                                    VE.STATUS_NAME,
                                    VE.POSITION_NAME,
                                    VE.POSITION_KEY_ID,
                                    VE.EMPLOYEE_PICTURE, 
                                    VE.DEPARTMENT_ID, 
                                    VE.DEPARTMENT_NAME,
                                    TETPH.ID AS FULL_TIME_ID,
                                    TETPH.FULL_TIME,
                                    TETPH.D1,TETPH.D2,TETPH.D3,TETPH.D4,TETPH.D5,TETPH.D6,TETPH.D7,TETPH.D8,TETPH.D9,TETPH.D10,
                                    TETPH.D11,TETPH.D12,TETPH.D13,TETPH.D14,TETPH.D15,TETPH.D16,TETPH.D17,TETPH.D18,TETPH.D19,TETPH.D20,
                                    TETPH.D21,TETPH.D22,TETPH.D23,TETPH.D24,TETPH.D25,TETPH.D26,TETPH.D27,TETPH.D28,TETPH.D29,TETPH.D30,
                                    TETPH.D31, ek.STATUS_ID, ek.CURRENT_STATUS_ID                                                              
                                FROM VW_TMS_EMPLOYEE VE
                                INNER JOIN ( 
                                    SELECT
                                    MAX(EMPLOYEE_KEY_ID) AS EMPLOYEE_KEY_ID
                                    FROM
                                    HRM_EMPLOYEE_KEY
                                    WHERE ( 
                                          ".$tmsPlanPreview."
                                        )
                                    GROUP BY
                                       EMPLOYEE_ID
                                ) ACTIVE_HEK ON ACTIVE_HEK.EMPLOYEE_KEY_ID = VE.EMPLOYEE_KEY_ID       
                                LEFT JOIN ( 
                                    SELECT
                                    BB.NEW_EMPLOYEE_KEY_ID AS EMPLOYEE_KEY_ID,
                                    '0' AS LIMITLESS
                                    FROM HCM_LABOUR_BOOK AA
                                    INNER JOIN HCM_LABOUR_BOOK_DTL BB ON AA.ID = BB.BOOK_ID
                                    WHERE AA.BOOK_TYPE_ID IN ($bookTypeIds) 
                                    UNION ALL
                                    SELECT
                                    BB.NEW_EMPLOYEE_KEY_ID AS EMPLOYEE_KEY_ID,
                                    '1' AS LIMITLESS
                                    FROM HCM_LABOUR_BOOK AA
                                    INNER JOIN HCM_LABOUR_BOOK_DTL BB ON AA.ID = BB.BOOK_ID 
                                    WHERE AA.BOOK_TYPE_ID IN ($bookTypeIds) AND TO_CHAR(BB.START_DATE + $monthLimit, 'YYYY-MM') >= '" . $params['planYear'] ."-". $params['planMonth'] . "' AND TO_CHAR(BB.START_DATE - $monthLimit, 'YYYY-MM') <= '" . $params['planYear'] ."-". $params['planMonth'] . "'
                                ) LAB_HEK ON LAB_HEK.EMPLOYEE_KEY_ID = ACTIVE_HEK.EMPLOYEE_KEY_ID                                                                                                                                     
                                INNER JOIN HRM_EMPLOYEE_KEY ek ON ACTIVE_HEK.EMPLOYEE_KEY_ID = ek.EMPLOYEE_KEY_ID
                                LEFT JOIN TMS_EMPLOYEE_TIME_PLAN_HDR TETPH ON TETPH.YEAR_ID = " . $params['planYear'] . " AND TETPH.MONTH_ID = " . $params['planMonth'] . " AND TETPH.EMPLOYEE_ID = VE.EMPLOYEE_ID
                                $leftJoin
                                WHERE ek.STATUS_ID NOT IN ($statusNotIn) AND (CASE
                                    WHEN LAB_HEK.EMPLOYEE_KEY_ID IS NULL THEN 0
                                    WHEN LAB_HEK.EMPLOYEE_KEY_ID IS NOT NULL AND LAB_HEK.LIMITLESS = '0' THEN 1
                                    ELSE 2
                                END) IN (0,2) AND ek.CURRENT_STATUS_ID NOT IN ($currentStatusNotIn)
                                    " . $whereString . $causeString1 . $filterRuleString . "
                                ) tem");                    

                            $autoNumber = 1;

                            $select = "SELECT * FROM ( 
                                        SELECT 
                                            VE.EMPLOYEE_ID,
                                            VE.EMPLOYEE_KEY_ID,
                                            VE.LAST_NAME,
                                            VE.FIRST_NAME,
                                            VE.CODE,
                                            VE.STATUS_NAME,
                                            VE.POSITION_NAME,
                                            VE.POSITION_KEY_ID,
                                            VE.EMPLOYEE_PICTURE, 
                                            VE.DEPARTMENT_ID, 
                                            VE.DEPARTMENT_NAME,
                                            VE.REGISTER_NUMBER,
                                            TETPH.ID AS FULL_TIME_ID,
                                            TETPH.FULL_TIME,
                                            TETPH.D1,TETPH.D2,TETPH.D3,TETPH.D4,TETPH.D5,TETPH.D6,TETPH.D7,TETPH.D8,TETPH.D9,TETPH.D10,
                                            TETPH.D11,TETPH.D12,TETPH.D13,TETPH.D14,TETPH.D15,TETPH.D16,TETPH.D17,TETPH.D18,TETPH.D19,TETPH.D20,
                                            TETPH.D21,TETPH.D22,TETPH.D23,TETPH.D24,TETPH.D25,TETPH.D26,TETPH.D27,TETPH.D28,TETPH.D29,TETPH.D30,
                                            $leftJoinAttr
                                            TETPH.D31, ek.STATUS_ID, ek.CURRENT_STATUS_ID,
                                            ". $this->db->IfNull('TETPH.ID', 'null') ." AS IS_EXIST $tableColumn2                                         
                                        FROM VW_TMS_EMPLOYEE VE
                                        INNER JOIN ( 
                                            SELECT
                                            MAX(EMPLOYEE_KEY_ID) AS EMPLOYEE_KEY_ID
                                            FROM
                                            HRM_EMPLOYEE_KEY
                                            WHERE (
                                                    ".$tmsPlanPreview."
                                                  )
                                            GROUP BY
                                               EMPLOYEE_ID
                                        ) ACTIVE_HEK ON ACTIVE_HEK.EMPLOYEE_KEY_ID = VE.EMPLOYEE_KEY_ID                     
                                        LEFT JOIN ( 
                                            SELECT
                                            BB.NEW_EMPLOYEE_KEY_ID AS EMPLOYEE_KEY_ID,
                                            '0' AS LIMITLESS
                                            FROM HCM_LABOUR_BOOK AA
                                            INNER JOIN HCM_LABOUR_BOOK_DTL BB ON AA.ID = BB.BOOK_ID
                                            INNER JOIN HRM_EMPLOYEE_KEY LK ON LK.EMPLOYEE_KEY_ID = BB.NEW_EMPLOYEE_KEY_ID
                                            WHERE AA.BOOK_TYPE_ID IN ($bookTypeIds) AND LK.CURRENT_STATUS_ID NOT IN (1, 3)
                                            UNION ALL
                                            SELECT
                                            BB.NEW_EMPLOYEE_KEY_ID AS EMPLOYEE_KEY_ID,
                                            '1' AS LIMITLESS
                                            FROM HCM_LABOUR_BOOK AA
                                            INNER JOIN HCM_LABOUR_BOOK_DTL BB ON AA.ID = BB.BOOK_ID
                                            INNER JOIN HRM_EMPLOYEE_KEY LK ON LK.EMPLOYEE_KEY_ID = BB.NEW_EMPLOYEE_KEY_ID
                                            WHERE AA.BOOK_TYPE_ID IN ($bookTypeIds) AND TO_CHAR(BB.START_DATE + $monthLimit, 'YYYY-MM') >= '" . $params['planYear'] ."-". $params['planMonth'] . "' AND TO_CHAR(BB.START_DATE - $monthLimit, 'YYYY-MM') <= '" . $params['planYear'] ."-". $params['planMonth'] . "' AND LK.CURRENT_STATUS_ID NOT IN (1, 3)
                                        ) LAB_HEK ON LAB_HEK.EMPLOYEE_KEY_ID = ACTIVE_HEK.EMPLOYEE_KEY_ID                                                                                      
                                        INNER JOIN HRM_EMPLOYEE_KEY ek ON ACTIVE_HEK.EMPLOYEE_KEY_ID = ek.EMPLOYEE_KEY_ID
                                        LEFT JOIN TMS_EMPLOYEE_TIME_PLAN_HDR TETPH ON TETPH.YEAR_ID = " . $params['planYear'] . " AND TETPH.MONTH_ID = " . $params['planMonth'] . " AND TETPH.EMPLOYEE_ID = VE.EMPLOYEE_ID
                                        $leftJoin
                                        $leftJoinDay
                                        WHERE ek.STATUS_ID NOT IN ($statusNotIn) AND (CASE
                                                WHEN LAB_HEK.EMPLOYEE_KEY_ID IS NULL THEN 0
                                                WHEN LAB_HEK.EMPLOYEE_KEY_ID IS NOT NULL AND LAB_HEK.LIMITLESS = '0' THEN 1
                                                ELSE 2
                                            END) IN (0,2) AND ek.CURRENT_STATUS_ID NOT IN ($currentStatusNotIn)
                                            " . $whereString . $causeString1 . $filterRuleString . " 
                                        ORDER BY ".($tableColumn2 ? ltrim($tableColumn2, ',') : 'VE.DEPARTMENT_NAME, VE.FIRST_NAME, VE.POSITION_NAME')." ASC ) $orderColumn";

                            //echo $select; die;
                            $employeers = $this->db->SelectLimit($select, $rows, $offset);
                            $employeers = isset($employeers->_array) ? $employeers->_array : array();

                            $tbody = '';

                            if ($employeeCount > 0) {
                                $selectHoliday = "
                                SELECT 
                                    START_DATE, 
                                    END_DATE, 
                                    HOLIDAY_NAME
                                FROM 
                                    (
                                        (
                                            SELECT DTL.START_DATE, DTL.END_DATE, HDR.ACTIVITY_NAME AS HOLIDAY_NAME
                                            FROM HRM_ACTIVITY_HDR HDR
                                            INNER JOIN HRM_ACTIVITY_DTL DTL ON DTL.ACTIVITY_HDR_ID = HDR.ACTIVITY_HDR_ID
                                            INNER JOIN HRM_ACTIVITY_ATTENDEE ATT ON ATT.ACTIVITY_HDR_ID = HDR.ACTIVITY_HDR_ID
                                            WHERE  END_DATE IS NOT NULL
                                        )
                                        UNION  (
                                            SELECT START_DATE, END_DATE, HOLIDAY_NAME
                                            FROM  LM_HOLIDAY 
                                            WHERE  END_DATE IS NOT NULL
                                        )
                                    )";
                                $holidays = $this->db->GetAll($selectHoliday);

                                foreach ($departmentList as $k => $department) {
                                    $depIndex = 1;
                                    $planDtlGroup = array();

                                    foreach ($employeers as $i => $employee) {
                                        if ($department['DEPARTMENT_ID'] === $employee['DEPARTMENT_ID']) {

                                            if (!empty($employee['FULL_TIME_ID'])) {
                                                $planDtlDataByPlan = $this->db->GetAll("SELECT 'D'||LTRIM(TO_CHAR(PLAN_DATE, 'DD'), '0') AS DATE_STR, PLAN_ID ".
                                                            "FROM TMS_EMPLOYEE_TIME_PLAN_DTL ".
                                                            "WHERE TIME_PLAN_ID = " . $employee['FULL_TIME_ID']);
                                                $planDtlGroup = Arr::groupByArray($planDtlDataByPlan, 'DATE_STR');            
                                            }                                                

                                            $days = self::getWorkingDateV2Model(array('planMonth' => $params['planMonth'], 'planYear' => $params['planYear'], 'employee' => $employee, 'headerDays' => $headerDays, 'k' => $k, 'tableName' => 'TNA_EMPLOYEE_TIME_PLAN'), $holidays);

                                            $daysSizeOf = sizeof($days) + 1;

                                            if ($depIndex === 1) {
                                                $depIndex++;
                                                $tbody .= '<tbody class="tablesorter-no-sort">';
                                                    $tbody .= '<tr class="row-details" data-department="' . $department['DEPARTMENT_ID'] . '" id="EMPLOYEE_'. $employee['EMPLOYEE_ID'] . '_' . $employee['EMPLOYEE_KEY_ID'] . '_' . $department['DEPARTMENT_ID'] . '">';
                                                        $tbody .= '<td class="number"> &nbsp;&nbsp; </td>';
                                                        $tbody .= '<td class="pl10 departmentTitle" data-colspan="1" colspan="20" style="border-right-color: transparent; word-wrap: break-word;">' . $department['DEPARTMENT_NAME'] . '</td>';
                                                        $tbody .= '<td style="border-right-color: transparent;"></td>';
                                                    $tbody .= '</tr>';
                                                $tbody .= '</tbody>';
                                            }

                                            $tbody .= '<tr data-department="' . $employee['DEPARTMENT_ID'] . '" id="EMPLOYEE_'. $employee['EMPLOYEE_ID'] . '_' . $employee['EMPLOYEE_KEY_ID'] . '_' . $employee['DEPARTMENT_ID'] . '">';
                                            $tbody .= '<td style="padding-left:2px; padding-right:8px; line-height: 13px; vertical-align: middle" class="text-center no-select">' . $autoNumber . '</td>';
                                            $tbody .= '<td class="align-left pl10 pr10 no-select" style="line-height: 13px; vertical-align: middle;" title="'.$employee['LAST_NAME'].'.'.$employee['FIRST_NAME'].'">';
                                                $empLoyeeName = mb_substr ($employee['LAST_NAME'], 0, 2, 'UTF-8').'.'.$employee['FIRST_NAME'].' ';
                                                if (Config::getFromCache('tmsCustomerCode') == 'gov') {
                                                    $empLoyeeName = mb_substr ($employee['LAST_NAME'], 0, 1, 'UTF-8').'.'.$employee['FIRST_NAME'].' ';
                                                }                                                    
                                                $tbody .= '<input type="hidden" data-name="firstName" name="firstName[]" value="' . $employee['FIRST_NAME'] . '">';
                                                $tbody .= '<input type="hidden" data-name="lastName" name="lastName[]" value="' . $employee['LAST_NAME'] . '">';
                                                $tbody .= '<input type="hidden" data-name="code" name="code[]" value="' . $employee['CODE'] . '">';
                                                $tbody .= '<input type="hidden" data-name="departmentId" name="departmentId[]" value="' . $employee['DEPARTMENT_ID'] . '">';
                                                $tbody .= '<input type="hidden" data-name="employeeId" name="employeeId[]" value="' . $employee['EMPLOYEE_ID'] . '">';
                                                $tbody .= '<input type="hidden" data-name="employeeKeyId" name="employeeKeyId[]" value="' . $employee['EMPLOYEE_KEY_ID'] . '">';
                                                $tbody .= '<input type="hidden" data-name="positionKeyId" name="positionKeyId[]" value="' . $employee['POSITION_KEY_ID'] . '">';
                                                $tbody .= '<input type="hidden" data-name="positionName" name="positionName[]" value="' . $employee['POSITION_NAME'] . '">';
                                                $tbody .= '<input type="hidden" data-name="lastName" name="lastName[]" value="' . $employee['LAST_NAME'] . '">';
                                                $tbody .= '<input type="hidden" data-name="firstName" name="firstName[]" value="' . $employee['FIRST_NAME'] . '">';
                                                $tbody .= '<input type="hidden" data-name="employeePicture" name="employeePicture[]" value="' . $employee['EMPLOYEE_PICTURE'] . '">';
                                                $tbody .= '<input type="hidden" data-name="employeeName" name="employeePicture[]" value="' . $employee['EMPLOYEE_PICTURE'] . '">';//'+employeeName+'
                                                $tbody .= '<input type="hidden" data-name="code" name="code[]" value="' . $employee['CODE'] . '">';
                                                $tbody .= '<input type="hidden" data-name="fullTimeId" name="fullTimeId[]" value="' . $employee['FULL_TIME_ID'] . '">';
                                                $tbody .= '<input type="hidden" data-name="isExist" name="isExist[]" value="' . $employee['IS_EXIST'] . '">';
                                                if ($isGolomt) {
                                                        $tbody .= $empLoyeeName;
                                                    $tbody .= '</td>';
                                                    $tbody .= '<td class="pl10 pr10 no-select">' . $employee['CODE'] . '</td>';
                                                } else {

                                                    if ($tmsCustomerCode === 'khaanbank' || $tmsCustomerCode === 'skyresort') {
                                                        $tbody .= $empLoyeeName. ' <i>('. $employee['CODE'] .')<i>';
                                                    } else {
                                                        $tbody .= $empLoyeeName. ' <i>('. $employee['CODE'] . ' - ' . $employee['REGISTER_NUMBER'] .')<i>';
                                                    }
                                                    $tbody .= '</td>';
                                                }
                                            $tbody .= '<td class="pl10 pr10 no-select" style="line-height: 13px; vertical-align: middle;">' . $employee['POSITION_NAME'] . '</td>';

                                            $totalAmountPlanTime = 0;     
                                            $iday = 1;
                                            foreach ($days as $key => $day) {
                                                $dayAddin = $iday;

                                                if (empty($i)) {
                                                    $theader .= '<th data-isworking="' . $day['SPELL_DAY'] . '" class="tbl-cell ' . $day['DAY_CLASS'] . '" style="width: 22px; font-weight: 500; min-width: 22px; line-height: 13px; vertical-align: middle;">';
                                                    $theader .= '<div class="dayName">' . $day['DAY'] . '</div>';
                                                    $theader .= '<div class="dayName">' . $day['SPELL_DAY_SHORT_NAME'] . '</div>';
                                                    $theader .= '</th>';
                                                }

                                                if ($employee['D' . $dayAddin] !== '') {

                                                    if (!isset($planDtlGroup['D'. $iday])) {
                                                        $employee['PLAN_TIME_'. $iday] = 0;
                                                    }   

                                                    $dayPlanTime = ($employee['PLAN_TIME_'. $iday] > 0) ? $employee['PLAN_TIME_'. $iday] : '';
                                                    $dayPlanColor = $employee['COLOR_'. $iday] ? $employee['COLOR_'. $iday] : '';
                                                    $dayPlanTime = ($isHishigArvin) ? ($employee['SHORT_NAME_'. $iday] ? $employee['SHORT_NAME_'. $iday] : $dayPlanTime) : $dayPlanTime;

                                                    $tbody .= '<td title="' . $day['HOLIDAY'] . ' ' . $day['STATUS_DESCRIPTION'] . '" 
                                                                    style=" cursor: context-menu; background-color:' . (($dayPlanColor) ? $dayPlanColor : ((strtolower($day['DAY_COLOR']) !== '') ? $day['DAY_COLOR'] : 'transparent' )) .'; 
                                                                            text-align:center; ' . ($day['IS_LOCK'] == '1' ? ' 
                                                                            background-image : url(\'assets/core/global/img/cell-status.png\'); 
                                                                            background-repeat: no-repeat; background-position: bottom right;' : '') . ' 
                                                                            vertical-align: middle; "  
                                                                    data-isworking="' .$day['SPELL_DAY'] . '" 
                                                                    class="tbl-cell  ' . $day['DAY_CLASS'] . '">';
                                                    $tbody .= '<input type="hidden" data-name="tnaEmployeeTimePlanId" name="tnaEmployeeTimePlanId[' . $employee['EMPLOYEE_ID'] .'][]" value="' . (isset($day['ID']) ? $day['ID'] : '') . '">';
                                                    $tbody .= '<input type="hidden" data-name="planDate" name="planDate[' . $employee['EMPLOYEE_ID'] .'][]" value="' . $day['PLAN_DATE'].'">';
                                                    $tbody .= '<input type="hidden" data-name="planCode" name="planCode[' . $employee['EMPLOYEE_ID'] .'][]" value="D'. $dayAddin. '">';
                                                    $tbody .= '<input type="hidden" data-name="planId" name="planId[' . $employee['EMPLOYEE_ID'] .'][]" value="' . $employee['PLAN_ID_'. $iday].'">';
                                                    $tbody .= '<input type="hidden" data-name="wfmStatusId" name="wfmStatusId[' . $employee['EMPLOYEE_ID'] .'][]" value="' . $day['WFM_STATUS_ID'].'">';
                                                    $tbody .= '<input type="hidden" data-name="day" name="day[' . $employee['EMPLOYEE_ID'] .'][]" value="' . $day['DAY'].'">';
                                                    $tbody .= '<input type="hidden" data-name="wfmStatusId" name="wfmStatusId[' . $employee['EMPLOYEE_ID'] .'][]" value="' . $day['WFM_STATUS_ID'].'">';
                                                    $tbody .= '<input type="hidden" data-name="wfmStatusCode" name="wfmStatusCode[' . $employee['EMPLOYEE_ID'] .'][]" value="' . $day['WFM_STATUS_CODE'].'">';
                                                    $tbody .= '<input type="hidden" data-name="approveLastDate" name="approveLastDate[' . $employee['EMPLOYEE_ID'] .'][]" value="' . $day['APPROVE_LAST_DATE'].'">';
                                                    $tbody .= '<input type="hidden" data-name="isLock" name="isLock[' . $employee['EMPLOYEE_ID'] .'][]" value="' . $day['IS_LOCK'].'">';
                                                    $tbody .= '<input type="hidden" data-name="lockEndDate" name="lockEndDate[' . $employee['EMPLOYEE_ID'] .'][]" value="' . $day['LOCK_END_DATE'].'">';
                                                    $tbody .= '<input type="hidden" data-name="lockUserId" name="lockUserId[' . $employee['EMPLOYEE_ID'] .'][]" value="' . $day['LOCK_USER_ID'].'">';
                                                    $tbody .= '<input type="hidden" data-name="planTime" name="planTime[' . $employee['EMPLOYEE_ID'] .'][]" value="' . $employee['PLAN_TIME_'.$iday] .'">';
                                                    $tbody .= '<input type="hidden" data-name="isSelectedCell" name="isSelectedCell[' . $employee['EMPLOYEE_ID'] .'][]" value="0">';
                                                    $tbody .= $dayPlanTime;
                                                    $tbody .= '</td>';        
                                                    //$totalAmountPlanTime += ($day['PLAN_TIME'] > 0) ? $day['PLAN_TIME'] : '0';
                                                    $totalAmountPlanTime += ($employee['PLAN_TIME_'. $iday] > 0) ? $employee['PLAN_TIME_'. $iday] : '0';
                                                } else {
                                                    $tbody .= '<td>';
                                                    $tbody .= '</td>';
                                                }
                                                $iday++;
                                            }

                                            $tbody .= '<td class="no-select" style="padding: 0 !important; background-color: #EEE; line-height: 13px; vertical-align: middle;">';
                                                $tbody .= '<input type="text" class="form-control fullTime text-center" readonly ="readonly" maxlength="3" name="fullTime[]" data-name="fullTime" value="' . $totalAmountPlanTime . '" title="' . $totalAmountPlanTime . '" onchange="checkFullTime(this);">';
                                            $tbody .= '</td>';
                                            $tbody .= '</tr>';
                                            $autoNumber++;
                                        }
                                    }
                                }
                            }

                            $response = array('total' => $employeeCount, 'Html' => $tbody, 'status' => 'success');
                }
                else {
                    $response = array('status' => 'error');
                }
            }
        }
        return $response;
    }        

    public function exportTimeEmployeeListV2Model() {
        $page = Input::numeric('page', 1);
        $rows = 10000;
        $balanceCriteria = $departmentList = array();
        $balanceDVid = Config::getFromCache('tnaTimePlanHdrDV');
        $theader = $tbody = $tfooter = '';

        $params = Input::postData();

        if ((isset($params['groupId']) && is_array($params['groupId']))) {
            $balanceCriteria['filterGroupId'] = array(
                array('operator' => 'IN', 'operand' => implode(',', $params['groupId']))
            );
        }   

        if (is_array($params['newDepartmentId']) && count($params['newDepartmentId'])) {

            $departmentIds = $params['newDepartmentId']; 
            $departmentIds = implode(',', $departmentIds); 
            $isChild = issetVar($params['isChild']);

            if ($departmentIds) {

                $departmentIds = $this->getAllChildDepartment2Model($departmentIds, $isChild);

            } elseif (isset($balanceCriteria['filterGroupId'])) {

                $departmentIds = $this->getAllChildDepartmentByGroupIdModel($balanceCriteria['filterGroupId'][0]['operand']);
            }

            $balanceCriteria['filterDepartmentId'] = array(
                array('operator' => 'IN', 'operand' => $departmentIds['join'])
            );

            $departmentList = $departmentIds['array'];

            $positionIds = issetParam($params['positionId']);                                                
            if ($positionIds) {
                $positionIds = implode(',', $positionIds);
                $balanceCriteria['positionId'] = array(
                    array('operator' => 'IN', 'operand' => $positionIds)
                );
            }
        } elseif ($params['newDepartmentId'] && Config::getFromCache('tmsCustomerCode') == 'gov') {

            $departmentIds = $params['newDepartmentId'];
            $isChild = issetVar($params['isChild']);

            $departmentIds = $this->getAllChildDepartment2Model($departmentIds, $isChild);    

            $balanceCriteria['filterDepartmentId'] = array(
                array('operator' => 'IN', 'operand' => $departmentIds['join'])
            );

            $departmentList = $departmentIds['array'];
            $positionIds = issetParam($params['positionId']);      

            if ($positionIds) {
                $positionIds = implode(',', $positionIds);
                $balanceCriteria['positionId'] = array(
                    array('operator' => 'IN', 'operand' => $positionIds)
                );
            }                
        }
        
        if (trim($params['stringValue']) != '') {
            $balanceCriteria['filterStringValue'] = array(
                array('operator' => 'LIKE', 'operand' => '%' . $params['stringValue'] . '%')
            );
        }

        if ((isset($params['positionId']) && is_array($params['positionId']))) {
            $balanceCriteria['filterPositionId'] = array(
                array('operator' => 'IN', 'operand' => implode(',', $params['positionId']))
            );
        }       

        $caclStartDate = '';
        $caclEndDate = '';
        $caclYear = '';
        if (Config::getFromCache('tmsCalcIdCode') == '1') {
            if (empty($params['calcId'])) {
                $response = array('status' => 'error', 'message' => 'Бодолтын дугаараа сонгоно уу!');
                return $response ;
            }        
            $getCalcInfo = self::getCalcListModel($params['calcId']);
            $caclStartDate = Input::post('startDate');
            $caclEndDate = Input::post('endDate');
            $caclYear = Date::formatter($caclStartDate, 'Y');
            $balanceCriteria['filterStartDate'] = array(
                array('operator' => '=', 'operand' => $caclStartDate)
            );            
            $balanceCriteria['filterEndDate'] = array(
                array('operator' => '=', 'operand' => $caclEndDate)
            );            

        } else {

            $balanceCriteria['filterYear'] = array(
                array('operator' => '=', 'operand' => $params['planYear'])
            );     

            $balanceCriteria['filterMonth'] = array(
                array('operator' => '=', 'operand' => $params['planMonth'])
            );            
        }
        
        if (Input::postCheck('filterRules')) {
            $filterRules = json_decode(Str::cp1251_utf8($_POST['filterRules']), true);

            if (is_array($filterRules) && count($filterRules) > 0) {

                foreach ($filterRules as $rule) {
                    $ruleValue = Input::param(trim($rule['value']));

                    switch ($rule['field']) {
                        case 'employeename':
                            $balanceCriteria['name'] = array(
                                array('operator' => 'LIKE', 'operand' => '%'.$ruleValue.'%')
                            );
                            break;
                        case 'employeeposition':
                            $balanceCriteria['positionname'] = array(
                                array('operator' => 'LIKE', 'operand' => '%'.$ruleValue.'%')
                            );
                            break;
                        case 'balancedateshow':
                            $balanceCriteria[$rule['field']] = array(
                                array('operator' => '=', 'operand' => $ruleValue)
                            );
                            break;                                
                        default:

                            if (strpos($ruleValue, ':') !== false) {
                                $ruleValue = explode(':', $ruleValue);
                                $ruleValue = (float) $ruleValue[0] * 60 + (float) $ruleValue[1];                            
                            } else {
                                $ruleValue = (float) $ruleValue * 60;                            
                            }

                            $balanceCriteria[$rule['field']] = array(
                                array('operator' => '=', 'operand' => $ruleValue)
                            );
                            break;
                    }
                }
            }
        }

        $param = array(
            'systemMetaGroupId' => $balanceDVid,
            'showQuery' => 0, 
            'ignorePermission' => 1,
            'paging' => array(
                'offset' => $page,
                'pageSize' => $rows
            ),
            'criteria' => $balanceCriteria
        );        

        $result = $this->ws->runSerializeResponse(self::$gfServiceAddress, Mddatamodel::$getDataViewCommand, $param);

        if ($result['status'] === 'success' && isset($result['result'])) {
            $response = array('status' => 'success');

            $response['total'] = (isset($result['result']['paging']) ? $result['result']['paging']['totalcount'] : 0);
            if (isset($result['result']['aggregatecolumns']) && $result['result']['aggregatecolumns']) {
                $response['footer'] = array($result['result']['aggregatecolumns']);
            }                

            $employeeCount = $result['result']['paging']['totalcount'];
            unset($result['result']['paging']);
            unset($result['result']['aggregatecolumns']);
            $autoNumber = 1;
            $tmsCustomerCode = Config::getFromCache('tmsCustomerCode');
            $isGolomt = $params['golomtView'];
            $golomtView = isset($params['golomtView']) ? (($params['golomtView']) ? 'Домайн' : 'Код') : 'Код';                

            $employeers = $result['result'];

            $selectHoliday = "
                SELECT 
                START_DATE, 
                END_DATE, 
                HOLIDAY_NAME
            FROM LM_HOLIDAY 
            WHERE END_DATE IS NOT NULL";

            $holidays = $this->db->GetAll($selectHoliday);
            $monStart = (int) Date::formatter($caclStartDate, 'm');
            $monEnd = (int) Date::formatter($caclEndDate, 'm');            

            if ($caclStartDate && $caclEndDate) {
                $days = self::getWorkingTwoDateV3Model(array('planYear'=>$caclYear, 'tableName' => 'TNA_EMPLOYEE_TIME_PLAN'), $holidays, $caclStartDate, $caclEndDate);
            } else {
                $days = self::getWorkingDateV3Model(array('planMonth'=>$params['planMonth'], 'planYear'=>$params['planYear'], 'tableName' => 'TNA_EMPLOYEE_TIME_PLAN'), $holidays);
            }
          
            $oneOfWorked = 1;
            $responseArray = array();
            foreach ($departmentList as $k => $department) {
                $depIndex = 1;
                $planDtlGroup = array();                                        
                $departmentEmployee = array();

                foreach ($employeers as $i => $employee) {

                    if ($department['DEPARTMENT_ID'] === $employee['departmentid']) {
                        array_push($departmentEmployee, $employee);
                    }
                }

                if ($departmentEmployee) {
                    $empKey = Arr::groupByArray($departmentEmployee, "employeekeyid");
                    array_push($responseArray, array(
                        'ID' => $department['DEPARTMENT_ID'],
                        'DEPARTMENT_NAME' => $department['DEPARTMENT_NAME'],
                        'EMPLOYEES' => $empKey,
                    ));                    
                }
            }

            return $responseArray;

        } else {
            $response = array('status' => 'error', 'message' => $this->ws->getResponseMessage($result));
            return $response ;
        }        
    }

    // public function exportTimeEmployeeListV2Model($planYear, $planMonth, $days) {

    //     $params = Input::postData();
    //     $positionId = Input::post('positionId');
    //     $stringValue = Input::post('stringValue');
    //     $onlyWorkingDay = Input::post('onlyWorkingDay');
    //     $onlyPositionWorkingDays = Input::post('onlyPositionWorkingDays');
    //     $departmentId = Input::post('newDepartmentId');
    //     $whereString = '';
    //     if (!empty($departmentId)) {
    //         $departmentIds = implode(',', $departmentId);
    //         $isChild = Input::post('isChild');

    //         $departmentId = $this->getAllChildDepartmentModel($departmentIds, $isChild);

    //         //$whereString .= " AND VE.DEPARTMENT_ID IN ( " . $departmentIds . ") ";

    //     }            

    //     $response = array();

    //     if ($planYear != '' && $planMonth != '') {
    //         if ($departmentId) {
    //             if (Input::post('positionId')) { 
    //                 $whereString .= " AND VE.POSITION_ID = " . $positionId;
    //             }

    //             if (Input::post('stringValue')) {
    //                 $whereString .= " AND (LOWER(VE.LAST_NAME) LIKE LOWER('%" . $stringValue . "%') OR LOWER(VE.FIRST_NAME) LIKE LOWER('%" . $stringValue . "%')) ";
    //             }

    //             $leftJoin = '';
    //             if (Input::post('groupId')) {
    //                 $ticketGroup = false;

    //                 $groupIds = '0';
    //                 foreach (Input::post('groupId') as $groupId) {
    //                     if ($groupId != '') {
    //                         $groupIds = $groupIds. ','.$groupId;
    //                         $ticketGroup = true;
    //                     }
    //                 }
    //                 if ($ticketGroup) {
    //                     $leftJoin = "
    //                         INNER JOIN TNA_EMPLOYEE_GROUP_CONFIG GC ON VE.EMPLOYEE_ID = GC.EMPLOYEE_ID
    //                         INNER JOIN TNA_GROUP_INFO GI ON GC.GROUP_ID = GI.ID";
    //                     $causeString1 .= " AND GI.ID IN (". $groupIds. ")";
    //                 }

    //             }

    //             if (Input::post('employeeStatus')) {
    //                 $employeeStatusId = implode(',', $params['employeeStatus']);
    //                 $whereString .= " AND ek.STATUS_ID IN (". $employeeStatusId .")";
    //             }

    //             if (Input::post('stringValue')) {
    //                 $filterRuleString .= " AND (LOWER(LAST_NAME) LIKE LOWER('%" . Input::post('stringValue') . "%') OR LOWER(FIRST_NAME) LIKE LOWER('%" . Input::post('stringValue') . "%')) ";
    //             }

    //             $leftJoinDay = $leftJoinAttr = $groupBy = '';

    //             $departmentList = $this->db->GetAll("
    //                 SELECT 
    //                     DEPARTMENT_ID,
    //                     DEPARTMENT_NAME
    //                 FROM 
    //                     ORG_DEPARTMENT 
    //                 WHERE 
    //                     DEPARTMENT_ID IN (" . $departmentId . ")");

    //             if ($departmentList) {
    //                 for ($iday = 1; $iday <= $days; $iday ++ ) {
    //                     $dayAddin = $iday;

    //                     $leftJoinAttr .= "ROUND(tem$iday.PLAN_TIME/60, 2) AS PLAN_TIME_$iday, tem$iday.PLAN_ID AS PLAN_ID_$iday, tem$iday.START_TIME AS START_TIME_$iday, tem$iday.END_TIME AS END_TIME_$iday,tem$iday.COLOR AS COLOR_$iday,";
    //                     $groupBy .= "tem$iday.PLAN_TIME, tem$iday.PLAN_ID, tem$iday.START_TIME, tem$iday.END_TIME, tem$iday.COLOR,";

    //                     $leftJoinDay .= "   LEFT JOIN (
    //                                             SELECT 
    //                                                 det.START_TIME,
    //                                                 det.END_TIME,
    //                                                 pl.PLAN_ID,
    //                                                 pl.COLOR, 
    //                                                 pl.SHORT_NAME,
    //                                                 FNC_GET_TMS_PLAN_TIME(pl.PLAN_ID) PLAN_TIME
    //                                             FROM TMS_TIME_PLAN pl
    //                                             INNER JOIN (
    //                                                 SELECT
    //                                                 PLAN_ID,
    //                                                 MIN(CASE WHEN ACC_TYPE = 1 THEN TO_CHAR(START_TIME, 'HH24:MI') END) AS START_TIME,
    //                                                 MAX(CASE WHEN ACC_TYPE = 2 THEN TO_CHAR(END_TIME, 'HH24:MI') END) AS END_TIME
    //                                               FROM TMS_TIME_PLAN_DETAIL
    //                                               GROUP BY PLAN_ID
    //                                             ) det ON pl.PLAN_ID = det.PLAN_ID
    //                                         ) tem$iday ON tem$iday.PLAN_ID = TETPH.D$dayAddin ";
    //                 }

    //                 $tmsPlanPreview = "((TO_CHAR(WORK_END_DATE, 'YYYY-MM') >= '" . $params['planYear'] ."-". $params['planMonth'] . "') OR WORK_END_DATE IS NULL)";
    //                 if(Config::getFromCache('tmsPlanPreview') == '1') {
    //                     $tmsPlanPreview = "((TO_CHAR(WORK_END_DATE + 30, 'YYYY-MM') >= '" . $params['planYear'] ."-". $params['planMonth'] . "') OR WORK_END_DATE IS NULL)";
    //                 }                        

    //                 $currentStatusNotIn = Config::getFromCache('tmsCurrentStatus');
    //                 $statusNotIn = Config::getFromCache('tmsStatus');                        

    //                 foreach ($departmentList as $k => $deparment) {
    //                     $departmentEmployee = array();

    //                     $employeers = $this->db->GetAll("
    //                         SELECT 
    //                             VE.EMPLOYEE_ID,
    //                             VE.EMPLOYEE_KEY_ID,
    //                             VE.LAST_NAME,
    //                             VE.FIRST_NAME,
    //                             VE.CODE,
    //                             VE.STATUS_NAME,
    //                             VE.POSITION_NAME,
    //                             VE.POSITION_KEY_ID,
    //                             VE.EMPLOYEE_PICTURE, 
    //                             VE.DEPARTMENT_ID, 
    //                             VE.DEPARTMENT_NAME,
    //                             TETPH.ID AS FULL_TIME_ID,
    //                             TETPH.FULL_TIME,
    //                             TETPH.D1,
    //                             TETPH.D2,
    //                             TETPH.D3,
    //                             TETPH.D4,
    //                             TETPH.D5,
    //                             TETPH.D6,
    //                             TETPH.D7,
    //                             TETPH.D8,
    //                             TETPH.D9,
    //                             TETPH.D10,
    //                             TETPH.D11,
    //                             TETPH.D12,
    //                             TETPH.D13,
    //                             TETPH.D14,
    //                             TETPH.D15,
    //                             TETPH.D16,
    //                             TETPH.D17,
    //                             TETPH.D18,
    //                             TETPH.D19,
    //                             TETPH.D20,
    //                             TETPH.D21,
    //                             TETPH.D22,
    //                             TETPH.D23,
    //                             TETPH.D24,
    //                             TETPH.D25,
    //                             TETPH.D26,
    //                             TETPH.D27,
    //                             TETPH.D28,
    //                             TETPH.D29,
    //                             TETPH.D30,
    //                             $leftJoinAttr
    //                             TETPH.D31
    //                         FROM VW_TMS_EMPLOYEE VE
    //                         INNER JOIN ( 
    //                             SELECT
    //                             MAX(EMPLOYEE_KEY_ID) AS EMPLOYEE_KEY_ID
    //                             FROM
    //                             HRM_EMPLOYEE_KEY
    //                             WHERE (
    //                                     ".$tmsPlanPreview."
    //                                   )
    //                             GROUP BY
    //                                EMPLOYEE_ID
    //                         ) ACTIVE_HEK ON ACTIVE_HEK.EMPLOYEE_KEY_ID = VE.EMPLOYEE_KEY_ID                                   
    //                         INNER JOIN HRM_EMPLOYEE_KEY ek on ACTIVE_HEK.EMPLOYEE_KEY_ID = ek.EMPLOYEE_KEY_ID
    //                         LEFT JOIN TMS_EMPLOYEE_TIME_PLAN_HDR TETPH ON TETPH.YEAR_ID = " . $params['planYear'] . " AND TETPH.MONTH_ID = " . $params['planMonth'] . " AND TETPH.EMPLOYEE_ID = VE.EMPLOYEE_ID
    //                         $leftJoin
    //                         $leftJoinDay
    //                         WHERE ek.STATUS_ID NOT IN ($statusNotIn) AND ek.CURRENT_STATUS_ID NOT IN ($currentStatusNotIn) AND
    //                             VE.DEPARTMENT_ID = '". $deparment['DEPARTMENT_ID'] ."'
    //                             " . $whereString . " 
    //                         GROUP BY    
    //                             VE.EMPLOYEE_ID,
    //                             VE.EMPLOYEE_KEY_ID,
    //                             VE.LAST_NAME,
    //                             VE.FIRST_NAME,
    //                             VE.CODE,
    //                             VE.STATUS_NAME,
    //                             VE.POSITION_NAME,
    //                             VE.POSITION_KEY_ID,
    //                             VE.EMPLOYEE_PICTURE, 
    //                             VE.DEPARTMENT_ID, 
    //                             VE.DEPARTMENT_NAME,
    //                             TETPH.ID,
    //                             TETPH.FULL_TIME,
    //                             TETPH.D1,
    //                             TETPH.D2,
    //                             TETPH.D3,
    //                             TETPH.D4,
    //                             TETPH.D5,
    //                             TETPH.D6,
    //                             TETPH.D7,
    //                             TETPH.D8,
    //                             TETPH.D9,
    //                             TETPH.D10,
    //                             TETPH.D11,
    //                             TETPH.D12,
    //                             TETPH.D13,
    //                             TETPH.D14,
    //                             TETPH.D15,
    //                             TETPH.D16,
    //                             TETPH.D17,
    //                             TETPH.D18,
    //                             TETPH.D19,
    //                             TETPH.D20,
    //                             TETPH.D21,
    //                             TETPH.D22,
    //                             TETPH.D23,
    //                             TETPH.D24,
    //                             TETPH.D25,
    //                             TETPH.D26,
    //                             TETPH.D27,
    //                             TETPH.D28,
    //                             TETPH.D29,
    //                             TETPH.D30,
    //                             $groupBy
    //                             TETPH.D31
    //                         ORDER BY VE.DEPARTMENT_NAME ASC, VE.FIRST_NAME ASC");

    //                     if ($employeers) {
    //                         foreach ($employeers as $i => $employee) {
    //                             array_push($departmentEmployee, $employee);
    //                         }

    //                         array_push($response, array(
    //                             'ID' => $deparment['DEPARTMENT_ID'],
    //                             'DEPARTMENT_NAME' => $deparment['DEPARTMENT_NAME'],
    //                             'EMPLOYEES' => $departmentEmployee,
    //                         ));
    //                     }
    //                 }
    //             }
    //         }
    //     }

    //     return $response;
    // }

    public function getFieldExpressionModel() {
        $calcTypeId = '';
        $metaDataId = '';
        $metaDataCode = '';
        if (Input::post('calcTypeId')) {
            $calcTypeId = Input::post('calcTypeId');
        }
        if (Input::post('metaDataCode')) {
            $metaDataCode = Input::post('metaDataCode');
        }
        $getMetaDataId = $this->db->GetRow("SELECT MD.META_DATA_ID, " . $this->db->IfNull("GD.CODE", "MD.META_DATA_NAME") . " AS META_DATA_NAME
                                            FROM META_DATA MD
                                            LEFT JOIN GLOBE_DICTIONARY GD ON GD.CODE = MD.META_DATA_CODE
                                            WHERE LOWER(MD.META_DATA_CODE) = LOWER('$metaDataCode') AND MD.META_DATA_CODE != MD.META_DATA_NAME");
        if ($getMetaDataId) {
            $metaDataId = $getMetaDataId['META_DATA_ID'];
        }

        if($metaDataId != '') {
            $selectExpression = "SELECT EXPRESSION FROM PRL_CALC_TYPE_DTL WHERE CALC_TYPE_ID = $calcTypeId AND META_DATA_ID = $metaDataId";
            $resultExpression = $this->db->GetRow($selectExpression);
        } else
            $resultExpression = false;

        if ($resultExpression) {
            $selectAllField = "SELECT MD.META_DATA_CODE, " . $this->db->IfNull("GD.CODE", "MD.META_DATA_NAME") . " AS META_DATA_NAME
                                FROM PRL_CALC_TYPE_DTL PCTD 
                                INNER JOIN META_DATA MD ON PCTD.META_DATA_ID = MD.META_DATA_ID 
                                LEFT JOIN GLOBE_DICTIONARY GD ON GD.CODE=MD.META_DATA_CODE                                    
                                WHERE CALC_TYPE_ID = $calcTypeId";
            $getAllField = $this->db->GetAll($selectAllField);

            foreach ($getAllField as $key => $value) {
                $resultExpression = str_replace($value['META_DATA_CODE'], Lang::line($value['META_DATA_NAME']), $resultExpression);
            }
            $resultExpression['FIELD'] = Lang::line($getMetaDataId['META_DATA_NAME']);
            return $resultExpression;
        } else {
            $result = array(
                'FIELD' => $getMetaDataId['META_DATA_NAME'],
                'EXPRESSION' => '');
            return $result;
        }
    }

    public function getWfmStatusDataModel() {

        $this->load->model('mdmetadata', 'middleware/models/');
        $getMetaDataId = $this->model->getMetaDataByCodeModel('tmsBalanceWorkflow');            

        (Array) $param = array(
            'systemMetaGroupId' => $getMetaDataId['META_DATA_ID'],
            'showQuery' => 0, 
            'ignorePermission' => 1
        );

        $data = $this->ws->runResponse(self::$gfServiceAddress, Mddatamodel::$getDataViewCommand, $param);

        unset($data['result']['paging']);
        unset($data['result']['aggregatecolumns']);

        $liStr = '';
        foreach ($data['result'] as $row) {
            $liStr .= '<li><a href="javascript:;" onclick="changeWfmStatusTime(this)" id="' . $row['id'] . '" data-refid="' . $row['refstructureid'] . '" data-color="' . $row['wfmstatuscolor'] . '" data-code="' . $row['wfmstatuscode'] . '">' . $row['wfmstatusname'] . '</a></li>';
        }

        return $liStr;
    }

    public function searchTnaGroupListDVModel() {
        (Array) $param = array(
            'systemMetaGroupId' => '1464052322531',
            'showQuery' => 0, 
            'ignorePermission' => 1
        );

        $data = $this->ws->runResponse(self::$gfServiceAddress, Mddatamodel::$getDataViewCommand, $param);

        unset($data['result']['paging']);
        unset($data['result']['aggregatecolumns']);

        return Arr::changeKeyUpper($data['result']);
    }

    public function saveWfmStatusDataModel() {
        $startDate = Input::post('startDate');
        $endDate = Input::post('endDate');
        $employeesString = Input::post('employeesString');
        $where = "WHERE EE.EMPLOYEE_ID IN (" . rtrim($employeesString, ',') . ")";

        if (Input::post('isTotal') === '1') {
            $where = 'WHERE 1 = 1';
        } elseif (empty($employeesString)) {
            return '';
        }

        if (Input::post('isApprove') != '1') {
            $checkWfm = $this->db->GetOne("SELECT COUNT(AA.TIME_BALANCE_HDR_ID)
                FROM VW_TMS_EMPLOYEE EE
                INNER JOIN ( 
                    SELECT
                    MAX(EMPLOYEE_KEY_ID) AS EMPLOYEE_KEY_ID
                    FROM
                    HRM_EMPLOYEE_KEY
                    WHERE CURRENT_STATUS_ID <> 6 
                        AND ( 
                            (TRUNC(WORK_START_DATE) <= '" . $startDate . "' AND ((TRUNC(WORK_END_DATE) BETWEEN '" . $startDate . "' AND '" . $endDate . "') OR WORK_END_DATE IS NULL))
                            OR
                            (TRUNC(WORK_START_DATE) BETWEEN '" . $startDate . "' AND '" . $endDate . "' AND (TRUNC(WORK_END_DATE) <= '" . $endDate . "' OR WORK_END_DATE IS NULL))
                        )
                    GROUP BY EMPLOYEE_ID
                ) ACTIVE_HEK ON ACTIVE_HEK.EMPLOYEE_KEY_ID = EE.EMPLOYEE_KEY_ID                         
                INNER JOIN TNA_TIME_BALANCE_HDR AA ON EE.EMPLOYEE_ID = AA.EMPLOYEE_ID AND AA.BALANCE_DATE >= TO_DATE('" . $startDate . "', 'YYYY-MM-DD') AND AA.BALANCE_DATE <= TO_DATE('" . $endDate . "', 'YYYY-MM-DD')
                LEFT JOIN META_WFM_STATUS BB ON BB.ID = AA.WFM_STATUS_ID
                $where AND AA.WFM_STATUS_ID = 1546998957540112 AND AA.WFM_STATUS_ID IS NOT NULL");

            $checkWfm2 = $this->db->GetOne("SELECT COUNT(AA.TIME_BALANCE_HDR_ID)
            FROM VW_TMS_EMPLOYEE EE
            INNER JOIN ( 
                SELECT
                MAX(EMPLOYEE_KEY_ID) AS EMPLOYEE_KEY_ID
                FROM
                HRM_EMPLOYEE_KEY
                WHERE CURRENT_STATUS_ID <> 6 
                    AND ( 
                        (TRUNC(WORK_START_DATE) <= '" . $startDate . "' AND ((TRUNC(WORK_END_DATE) BETWEEN '" . $startDate . "' AND '" . $endDate . "') OR WORK_END_DATE IS NULL))
                        OR
                        (TRUNC(WORK_START_DATE) BETWEEN '" . $startDate . "' AND '" . $endDate . "' AND (TRUNC(WORK_END_DATE) <= '" . $endDate . "' OR WORK_END_DATE IS NULL))
                    )
                GROUP BY EMPLOYEE_ID
            ) ACTIVE_HEK ON ACTIVE_HEK.EMPLOYEE_KEY_ID = EE.EMPLOYEE_KEY_ID                         
            INNER JOIN TNA_TIME_BALANCE_HDR AA ON EE.EMPLOYEE_ID = AA.EMPLOYEE_ID AND AA.BALANCE_DATE >= TO_DATE('" . $startDate . "', 'YYYY-MM-DD') AND AA.BALANCE_DATE <= TO_DATE('" . $endDate . "', 'YYYY-MM-DD')
            LEFT JOIN META_WFM_STATUS BB ON BB.ID = AA.WFM_STATUS_ID
            $where AND AA.WFM_STATUS_ID IS NOT NULL");                

            if ($checkWfm == $checkWfm2 && Input::post('wfmStatusId') != '1546998957540112') {
                return '<strong>' . Input::post('wfmStatusText') . '</strong> төлөвт оруулахдаа итгэлтэй байна уу? <br>Төлөвөө өөрчилснөөр Цалин руу Цаг татагдахгүй болохыг анхаарна уу!';
            }
        }
        
        if (Input::post('wfmStatusCode') == 'done') {
            $isLock = '1';
        } elseif (Input::post('wfmStatusCode') == 'cancel') {
            $isLock = '0';
        }

        $this->db->Execute("MERGE INTO TNA_TIME_BALANCE_HDR trg  
            USING (
                SELECT AA.TIME_BALANCE_HDR_ID
                FROM VW_TMS_EMPLOYEE EE
                INNER JOIN ( 
                    SELECT
                    MAX(EMPLOYEE_KEY_ID) AS EMPLOYEE_KEY_ID
                    FROM
                    HRM_EMPLOYEE_KEY
                    WHERE CURRENT_STATUS_ID <> 6 
                          AND ( 
                            (TRUNC(WORK_START_DATE) <= '" . $startDate . "' AND ((TRUNC(WORK_END_DATE) BETWEEN '" . $startDate . "' AND '" . $endDate . "') OR WORK_END_DATE IS NULL))
                             OR
                            (TRUNC(WORK_START_DATE) BETWEEN '" . $startDate . "' AND '" . $endDate . "' AND (TRUNC(WORK_END_DATE) <= '" . $endDate . "' OR WORK_END_DATE IS NULL))
                          )
                    GROUP BY EMPLOYEE_ID
                ) ACTIVE_HEK ON ACTIVE_HEK.EMPLOYEE_KEY_ID = EE.EMPLOYEE_KEY_ID                         
                INNER JOIN TNA_TIME_BALANCE_HDR AA ON EE.EMPLOYEE_ID = AA.EMPLOYEE_ID AND AA.BALANCE_DATE >= TO_DATE('" . $startDate . "', 'YYYY-MM-DD') AND AA.BALANCE_DATE <= TO_DATE('" . $endDate . "', 'YYYY-MM-DD')
                LEFT JOIN META_WFM_STATUS BB ON BB.ID = AA.WFM_STATUS_ID
                $where
            ) src ON (trg.TIME_BALANCE_HDR_ID = src.TIME_BALANCE_HDR_ID) 
            WHEN MATCHED THEN UPDATE
            SET " . (isset($isLock) ? "trg.IS_LOCK = " . $isLock . "," : "") . " trg.WFM_STATUS_ID = " . Input::post('wfmStatusId') . ", trg.WFM_DESCRIPTION = '" . Input::post('description') . "'"
        );       

        $this->db->Execute("INSERT INTO META_WFM_LOG (ID,REF_STRUCTURE_ID,RECORD_ID,WFM_STATUS_ID,WFM_DESCRIPTION,CREATED_DATE,CREATED_USER_ID)  
            SELECT IMPORT_ID_SEQ.NEXTVAL, " . Input::post('refId') . ", AA.TIME_BALANCE_HDR_ID, AA.WFM_STATUS_ID, AA.WFM_DESCRIPTION, '" . Date::currentDate() . "', " . Ue::sessionUserKeyId() . "
            FROM VW_TMS_EMPLOYEE EE
            INNER JOIN ( 
                SELECT
                MAX(EMPLOYEE_KEY_ID) AS EMPLOYEE_KEY_ID
                FROM
                HRM_EMPLOYEE_KEY
                WHERE CURRENT_STATUS_ID <> 6 
                        AND ( 
                        (TRUNC(WORK_START_DATE) <= '" . $startDate . "' AND ((TRUNC(WORK_END_DATE) BETWEEN '" . $startDate . "' AND '" . $endDate . "') OR WORK_END_DATE IS NULL))
                            OR
                        (TRUNC(WORK_START_DATE) BETWEEN '" . $startDate . "' AND '" . $endDate . "' AND (TRUNC(WORK_END_DATE) <= '" . $endDate . "' OR WORK_END_DATE IS NULL))
                        )
                GROUP BY EMPLOYEE_ID
            ) ACTIVE_HEK ON ACTIVE_HEK.EMPLOYEE_KEY_ID = EE.EMPLOYEE_KEY_ID                         
            INNER JOIN TNA_TIME_BALANCE_HDR AA ON EE.EMPLOYEE_ID = AA.EMPLOYEE_ID AND AA.BALANCE_DATE >= TO_DATE('" . $startDate . "', 'YYYY-MM-DD') AND AA.BALANCE_DATE <= TO_DATE('" . $endDate . "', 'YYYY-MM-DD')
            LEFT JOIN META_WFM_STATUS BB ON BB.ID = AA.WFM_STATUS_ID
            $where"
        );                        

    }

    public function deleteEmployeePlanV2Model() {

        $deleteId = array();
        $data = Input::postData();

        $message = 'Амжилттай устгалаа';
        $cellResult = false;

        try {
            if (Config::getFromCache('tmsCalcIdCode') == '1') {
                foreach ($data['employeeId'] as $k => $employeeId) {
                    $dateJoin = '';
                    $dateArray = array();
                    $paramData = array();
                    $getTimePlanIds = array();

                    foreach ($data['tnaEmployeeTimePlanId'][$employeeId] as $j => $planId) {
                        if ($data['fullTimeId'][$k] !== '' && $data['isSelectedCell'][$employeeId][$j] == '1') {
                            $dateJoin .= "'".Date::format('Y-m-d', $data['planDate'][$employeeId][$j])."',";
                            $yearId = Date::format('Y', $data['planDate'][$employeeId][$j]);
                            $monthId = (int) Date::format('m', $data['planDate'][$employeeId][$j]);

                            array_push($dateArray, array(
                                'planId' => $data['planId'][$employeeId][$j],
                                'planDate' => Date::format('Y-m-d', $data['planDate'][$employeeId][$j])
                            ));
                            
                            $timeId = $this->db->GetOne("SELECT ID 
                                FROM TMS_EMPLOYEE_TIME_PLAN_HDR 
                                WHERE EMPLOYEE_ID = " . $employeeId . " AND YEAR_ID = " . $yearId . " AND MONTH_ID = " . $monthId
                            );
                            $paramData[$timeId][$data['planCode'][$employeeId][$j]] =  null;

                            array_push($getTimePlanIds, $timeId);
                        }
                    }                 

                    foreach ($getTimePlanIds as $timeval) {
                        if ($timeval && $dateJoin !== '') {

                            $this->db->Execute("".
                                "DELETE FROM TNA_TIME_BALANCE_HDR TB
                                WHERE TB.EMPLOYEE_ID = ".$employeeId." AND TO_CHAR(TB.BALANCE_DATE, 'YYYY-MM-DD') IN (".rtrim($dateJoin, ',').")"
                            );                               

                            // $this->db->Execute("DELETE FROM TMS_EMPLOYEE_TIME_PLAN_DTL WHERE TIME_PLAN_ID = ".$timeval." AND TO_CHAR(PLAN_DATE, 'YYYY-MM-DD') IN (".rtrim($dateJoin, ',').")");

                            $checkDtl = $this->db->GetOne("SELECT COUNT(*) FROM TMS_EMPLOYEE_TIME_PLAN_DTL WHERE TIME_PLAN_ID = " . $timeval);
                            if ($checkDtl) {

                                // @file_put_contents(BASEPATH.'log/time_eployee_plan.log', 'DELETE ' . Date::currentDate() . ' ' . $timeval . ' ' . json_encode($paramData)."\r\n", FILE_APPEND);

                                $this->db->AutoExecute("TMS_EMPLOYEE_TIME_PLAN_HDR", $paramData[$timeval], 'UPDATE', 'ID = ' . $timeval);

                                // foreach ($dateArray as $rowdatakey => $rowdata) {
                                //     $this->db->Execute('INSERT INTO TMS_EMPLOYEE_PLAN_LOG_DETAIL (ID, HDR_ID, EMPLOYEE_ID, PLAN_DATE, PLAN_ID, CREATED_DATE, CREATED_USER_ID, DESCRIPTION) VALUES ('.
                                //                         $rowdatakey.getUID().', '.$timeval.', '.$employeeId.', \''.Input::param($rowdata['planDate']).'\', '.Input::param($rowdata['planId']).', \''.Date::currentDate().'\', '.Ue::sessionUserKeyId().', \'DELETED\')');
                                // }
                            } else {
                                // @file_put_contents(BASEPATH.'log/time_eployee_plan.log', 'DELETE ' . Date::currentDate() . ' ' . $timeval . "\r\n", FILE_APPEND);
                                $this->db->Execute("DELETE FROM TMS_EMPLOYEE_TIME_PLAN_HDR WHERE ID = " . $timeval);

                                // foreach ($dateArray as $rowdatakey => $rowdata) {
                                //     $this->db->Execute('INSERT INTO TMS_EMPLOYEE_PLAN_LOG_DETAIL (ID, HDR_ID, EMPLOYEE_ID, PLAN_DATE, PLAN_ID, CREATED_DATE, CREATED_USER_ID, DESCRIPTION) VALUES ('.
                                //                         $rowdatakey.getUID().', null, '.$employeeId.', \''.Input::param($rowdata['planDate']).'\', '.Input::param($rowdata['planId']).', \''.Date::currentDate().'\', '.Ue::sessionUserKeyId().', \'DELETED\')');
                                // }                            
                            }
                        }
                    }
                }    
            } else {
                foreach ($data['employeeId'] as $k => $employeeId) {
                    $dateJoin = '';
                    $dateArray = array();
                    $paramData = array();
    
                    foreach ($data['tnaEmployeeTimePlanId'][$employeeId] as $j => $planId) {
                        if ($data['fullTimeId'][$k] !== '' && $data['isSelectedCell'][$employeeId][$j] == '1') {
                            $dateJoin .= "'".Date::format('Y-m-d', $data['planDate'][$employeeId][$j])."',";
                            array_push($dateArray, array(
                                'planId' => $data['planId'][$employeeId][$j],
                                'planDate' => Date::format('Y-m-d', $data['planDate'][$employeeId][$j])
                            ));
                            $paramData[$data['planCode'][$employeeId][$j]] =  null;
                        }
                    }                    
    
                    if ($data['fullTimeId'][$k] !== '' && $dateJoin !== '') {
    
                        $this->db->Execute("".
                            "DELETE FROM TNA_TIME_BALANCE_HDR TB
                            WHERE TB.EMPLOYEE_ID = ".$employeeId." AND TO_CHAR(TB.BALANCE_DATE, 'YYYY-MM-DD') IN (".rtrim($dateJoin, ',').")"
                        );                               
    
                        $this->db->Execute("DELETE FROM TMS_EMPLOYEE_TIME_PLAN_DTL WHERE TIME_PLAN_ID = ".$data['fullTimeId'][$k]." AND TO_CHAR(PLAN_DATE, 'YYYY-MM-DD') IN (".rtrim($dateJoin, ',').")");
    
                        $checkDtl = $this->db->GetOne("SELECT COUNT(*) FROM TMS_EMPLOYEE_TIME_PLAN_DTL WHERE TIME_PLAN_ID = " . $data['fullTimeId'][$k]);
                        if ($checkDtl) {
                            $this->db->AutoExecute("TMS_EMPLOYEE_TIME_PLAN_HDR", $paramData, 'UPDATE', 'ID = ' . $data['fullTimeId'][$k]);
    
                            foreach ($dateArray as $rowdatakey => $rowdata) {
                                $this->db->Execute('INSERT INTO TMS_EMPLOYEE_PLAN_LOG_DETAIL (ID, HDR_ID, EMPLOYEE_ID, PLAN_DATE, PLAN_ID, CREATED_DATE, CREATED_USER_ID, DESCRIPTION) VALUES ('.
                                                    $rowdatakey.getUID().', '.$data['fullTimeId'][$k].', '.$employeeId.', \''.Input::param($rowdata['planDate']).'\', '.(Input::param($rowdata['planId']) ? Input::param($rowdata['planId']) : 'null').', \''.Date::currentDate().'\', '.Ue::sessionUserKeyId().', \'DELETED\')');
                            }
                        } else {
                            $this->db->Execute("DELETE FROM TMS_EMPLOYEE_TIME_PLAN_HDR WHERE ID = " . $data['fullTimeId'][$k]);
    
                            foreach ($dateArray as $rowdatakey => $rowdata) {
                                $this->db->Execute('INSERT INTO TMS_EMPLOYEE_PLAN_LOG_DETAIL (ID, HDR_ID, EMPLOYEE_ID, PLAN_DATE, PLAN_ID, CREATED_DATE, CREATED_USER_ID, DESCRIPTION) VALUES ('.
                                                    $rowdatakey.getUID().', null, '.$employeeId.', \''.Input::param($rowdata['planDate']).'\', '.(Input::param($rowdata['planId']) ? Input::param($rowdata['planId']) : 'null').', \''.Date::currentDate().'\', '.Ue::sessionUserKeyId().', \'DELETED\')');
                            }                            
                        }                        
                    }
                }                    
            }    

            $response = array('status' => 'success', 'title' => 'Амжилттай', 'message' => $message);
        } catch (Exception $ex) {
            // @file_put_contents(BASEPATH.'log/time_eployee_plan.log', $ex->msg."\r\n------------------------[ERROR]--------------------------\r\n", FILE_APPEND);
            $response = array('status' => 'warning', 'title' => 'Тайлбар', 'message' => Lang::line('msg_delete_error'), 'messageex' => $ex->msg, 'exception' => $ex);
        }

        return $response;
    }

    public function saveEmployeePlanPasteV2Model() {

        try {
            $data = Input::postData();
            $message = $response = '';

            if (!isset($data['data'])) {
                return array('status' => 'error', 'message' => 'Хоосон');
            }

            foreach ($data['data'] as $k => $val) {
                $employeeKeyId = Input::param($val['employeeKeyId']);
                $employeeId = Input::param($val['employeeId']);
                $planDate = Date::formatter($val['date'], 'Y-m-d');
                $planYear = (int) Date::formatter($val['date'], 'Y');
                $planMonth = (int) Date::formatter($val['date'], 'm');
                $planDay = (int) Date::formatter($val['date'], 'd');
                $planId = Input::param($val['planId']);

                if(empty($planId))
                    continue;

                $employeeTimePlanId = $this->db->GetOne("SELECT ID FROM TMS_EMPLOYEE_TIME_PLAN_HDR  WHERE YEAR_ID = '". $planYear ."' AND MONTH_ID = '". $planMonth ."' AND EMPLOYEE_ID = '". $employeeId ."'");

                $paramData = array(
                    'D'.$planDay => $planId
                );

                if ($employeeTimePlanId) {
                    $this->db->AutoExecute('TMS_EMPLOYEE_TIME_PLAN_HDR', $paramData, 'UPDATE', " ID = $employeeTimePlanId");

    //                $this->db->Execute('INSERT INTO TMS_EMPLOYEE_PLAN_LOG_DETAIL (ID, HDR_ID, EMPLOYEE_ID, PLAN_DATE, PLAN_ID, CREATED_DATE, CREATED_USER_ID) VALUES ('.
    //                                    $k.getUID().', '.$employeeTimePlanId.', '.$employeeId.', \''.$planDate.'\', '.$planId.', \''.Date::currentDate().'\', '.Ue::sessionUserKeyId().')');                    

                } else {

                    $paramData['ID'] = getUIDAdd($k);
                    $paramData['YEAR_ID'] = $planYear;
                    $paramData['MONTH_ID'] = $planMonth;
                    $paramData['CREATED_USER_ID'] = Ue::sessionUserKeyId();
                    $paramData['CREATED_DATE'] = Date::currentDate();
                    $paramData['EMPLOYEE_ID'] = $employeeId;

                    $this->db->AutoExecute('TMS_EMPLOYEE_TIME_PLAN_HDR', $paramData);

    //                $this->db->Execute('INSERT INTO TMS_EMPLOYEE_PLAN_LOG_DETAIL (ID, HDR_ID, EMPLOYEE_ID, PLAN_DATE, PLAN_ID, CREATED_DATE, CREATED_USER_ID) VALUES ('.
    //                                    $k.getUID().', '.$paramData['ID'].', '.$employeeId.', \''.$planDate.'\', '.$planId.', \''.Date::currentDate().'\', '.Ue::sessionUserKeyId().')');                                        
                }

                // @file_put_contents(BASEPATH.'log/time_eployee_plan.log', 'PASTE SUCCESS ' . Date::currentDate() . ' ' . json_encode($val)."\r\n", FILE_APPEND);            
            }

            return array('status' => 'success', 'message' => 'Амжилттай хадгаллаа');
        } catch (ADODB_Exception $ex) {
            $errorMsg = $ex->getMessage();

            /**
             * Insert log actions
             */
            // @file_put_contents(BASEPATH.'log/time_eployee_plan.log', $errorMsg."\r\n------------------------[PASTE ERROR]--------------------------\r\n", FILE_APPEND);
            return array('status' => 'error', 'message' => $errorMsg);
        }                    
    }        

    public function employeePlanListMainDataGridNewV3Model() {
        $result = $footerArr = $newBody = array();
        $whereString = $theader = $tbody = $tfooter = '';
        $isHishigArvin = Config::getFromCache('CONFIG_TNA_HISHIGARVIN');
        $filterRuleString = '';

        if (Input::postCheck('params')) {
            parse_str(Input::post('params'), $params);

            $isGolomt = $params['golomtView'];
            $golomtView = isset($params['golomtView']) ? (($params['golomtView']) ? 'Домайн' : 'Код') : 'Код';
            if (!empty($params['planYear']) && !empty($params['planMonth'])) {

                $employeers = array();
                $headerDays = array();
                $currentStatusNotIn = Config::getFromCache('tmsCurrentStatus');
                $statusNotIn = Config::getFromCache('tmsStatus');

                if (!empty($params['newDepartmentId']) || (isset($params['groupId']) && is_array($params['groupId']))) {

                    $bookTypeIds = '9024,9025,9026,9048';

                    if (is_array($params['newDepartmentId']) && count($params['newDepartmentId'])) {
                        $departmentIds = $params['newDepartmentId'];
                        $departmentIds = implode(',', $departmentIds);
                        $isChild = issetVar($params['isChild']);

                        if ($departmentIds) {
                            $departmentIds = $this->getAllChildDepartmentModel($departmentIds, $isChild);

                            $whereString .= " AND VE.DEPARTMENT_ID IN ( " . $departmentIds . ") ";
                        }

                    } elseif ($params['newDepartmentId'] && Config::getFromCache('tmsCustomerCode') == 'gov') {                            
                        $isChild = issetVar($params['isChild']);

                        $departmentIds = $this->getAllChildDepartmentModel($params['newDepartmentId'], $isChild);

                        $whereString .= " AND VE.DEPARTMENT_ID IN ( " . $departmentIds . ") ";                            
                    } elseif (isset($params['groupId']) && is_array($params['groupId'])) {
                        $whereString .= " AND VE.EMPLOYEE_ID IN ( SELECT EMPLOYEE_ID FROM TNA_EMPLOYEE_GROUP_CONFIG WHERE GROUP_ID IN (" . implode(',', $params['groupId']) . ")) ";

                    }

                    $params['departmentId'] = !empty($departmentIds) ? explode(',', $departmentIds) : '';

                    $paramDepartmentIds = $params['departmentId'];
                    $userId = Ue::sessionUserId();

                    (Array) $departmentId = array();

                    if (isset($params['positionId']) && is_array($params['positionId'])) {
                        $whereString .= " AND VE.POSITION_ID IN (" . implode(',', $params['positionId']) . ")";
                    }

                    if (isset($params['positionGroupId']) && $params['positionGroupId'] != '') {
                        $whereString .= " AND VE.POSITION_GROUP_ID = " . $params['positionGroupId'];
                    }

                    if (isset($params['employeeStatus'])) {
                        if (empty($params['employeeStatus']) === false) {
                            $employeeStatusId = implode(',', $params['employeeStatus']);
                            $whereString .= " AND ek.STATUS_ID IN (". $employeeStatusId .")";
                        }
                    }

                    if (isset($params['stringValue'])) {
                        if (empty($params['stringValue']) === false && $params['stringValue'] != '') {

                            if(strpos($params['stringValue'], '.') === false) {
                                $whereString .= " AND (LOWER(LAST_NAME) LIKE LOWER('%" . $params['stringValue'] . "%') OR LOWER(FIRST_NAME) LIKE LOWER('%" . $params['stringValue'] . "%') OR LOWER(CODE) LIKE LOWER('%" . $params['stringValue'] . "%') OR LOWER(REGISTER_NUMBER) LIKE LOWER('%" . $params['stringValue'] . "%')) ";
                            } else {
                                $strexplode = explode('.', $params['stringValue']);
                                $whereString .= " AND (LOWER(LAST_NAME) LIKE LOWER('%" . $strexplode[0] . "%') AND LOWER(FIRST_NAME) LIKE LOWER('%" . $strexplode[1] . "%')) ";
                            }
                        }
                    }

                    if (Input::postCheck('filterRules')) {
                        $filterRules = json_decode(Str::cp1251_utf8($_POST['filterRules']), true);                
                        if ($filterRules) {
                            foreach ($filterRules as $rule) {
                                $field = $rule['field'];
                                $value = Input::param(Str::lower($rule['value']));
                                if (!empty($value)) {
                                    if ($field === 'employeename') {
                                        $whereString .= " AND (LOWER(VE.FIRST_NAME) LIKE '%$value%')";
                                    } elseif ($field === 'employeeposition') {
                                        $whereString .= " AND (LOWER(VE.POSITION_NAME) LIKE '%$value%')";
                                    }
                                }
                            }
                        }
                    }                        

                    $causeString1 = '';
                    $leftJoin = '';

                    (String) $tableColumn1 = $tableColumn2 = $tableColumn3 = '';

                    $resultCountt = $this->db->GetOne("SELECT COUNT(ID) AS COUNTT FROM TNA_EMPLOYEE_PLAN_OWNER WHERE USER_ID = $userId");

                    if ((int) $resultCountt != 0) {
                        $resultDepartment = $this->db->GetAll("SELECT DISTINCT DEPARTMENT_ID FROM TNA_EMPLOYEE_PLAN_OWNER WHERE USER_ID = $userId");
                        foreach ($resultDepartment as $key => $deparment) {
                            if (!in_array($deparment['DEPARTMENT_ID'], $departmentId)) {
                                array_push($departmentId, $deparment['DEPARTMENT_ID']);
                            }
                        }
                        $response = array('CHECK' => '1', 'departmentIds' => $departmentId);
                    }

                    (Array) $departmentIdArr = array();

                    if (sizeof($departmentId) > 0) {
                        foreach ($paramDepartmentIds as $depart) {
                            if (in_array($depart, $departmentId)) {
                                array_push($departmentIdArr, $depart);
                            }
                        }
                    } else {
                        $departmentIdArr = $paramDepartmentIds;
                    }

                        if (empty($departmentIdArr)) {
                            $causeString1 .= " AND VE.EMPLOYEE_ID IN (SELECT EMPLOYEE_ID FROM TNA_EMPLOYEE_GROUP_CONFIG WHERE GROUP_ID IN (" . implode(',', $params['groupId']) . "))";
                            $departmentList = $this->db->GetAll("SELECT DISTINCT OD.DEPARTMENT_ID, OD.DEPARTMENT_NAME, OD.DISPLAY_ORDER  
                                                                FROM ORG_DEPARTMENT OD
                                                                INNER JOIN VW_EMPLOYEE VE ON VE.DEPARTMENT_ID = OD.DEPARTMENT_ID
                                                                WHERE VE.EMPLOYEE_ID IN (SELECT EMPLOYEE_ID FROM TNA_EMPLOYEE_GROUP_CONFIG WHERE GROUP_ID IN (" . implode(',', $params['groupId']) . "))
                                                                ORDER BY LPAD(OD.DISPLAY_ORDER, 10) ASC");    

                            $departmentIds = Arr::implode_key(',', $departmentList, 'DEPARTMENT_ID', true);
                        } else {
                            $departmentIds = implode(',', $departmentIdArr);
                            if((isset($params['groupId']) && is_array($params['groupId']))) {
                                $causeString1 .= " AND VE.EMPLOYEE_ID IN (SELECT EMPLOYEE_ID FROM TNA_EMPLOYEE_GROUP_CONFIG WHERE GROUP_ID IN (" . implode(',', $params['groupId']) . "))";
                            }
                            $departmentList = $this->db->GetAll("SELECT DEPARTMENT_ID, DEPARTMENT_NAME FROM ORG_DEPARTMENT WHERE DEPARTMENT_ID IN (" . $departmentIds . ") ORDER BY LPAD(DISPLAY_ORDER, 10) ASC");
                        }

                        $leftJoinDay = $leftJoinAttr = $groupBy = '';
                            $days = cal_days_in_month(CAL_GREGORIAN, intval($params['planMonth']), intval($params['planYear'])); // 31, 30, 29, 28
                            for ($iday = 1; $iday <= $days; $iday ++ ) {
                                $dayAddin = $iday;

                                if($iday < 10)
                                    $rday = '0'.$iday;
                                else
                                    $rday = $iday;

                                $leftJoinAttr .= 
                                        "
                                        ROUND(tem$iday.PLAN_TIME/60, 2) AS PLAN_TIME_$iday, 
                                        tem$iday.PLAN_ID AS PLAN_ID_$iday,
                                        tem$iday.SHORT_NAME AS SHORT_NAME_$iday,
                                        tem$iday.COLOR AS COLOR_$iday,
                                    ";

                                $leftJoinDay .= " LEFT JOIN (
                                                    SELECT
                                                        pl.PLAN_ID,
                                                        pl.COLOR, 
                                                        pl.SHORT_NAME,
                                                        FNC_GET_TMS_PLAN_TIME(pl.PLAN_ID) PLAN_TIME
                                                    FROM TMS_TIME_PLAN pl
                                                ) tem$iday ON tem$iday.PLAN_ID = TETPH.D$dayAddin ";

                            }

                            $monthLimit = Config::getFromCache('tmsMonthFilter') || Config::getFromCache('tmsMonthFilter') == '0' ? Config::getFromCache('tmsMonthFilter') : '30';
                            $tmsPlanPreview = "((TO_CHAR(WORK_END_DATE, 'YYYY-MM') >= '" . $params['planYear'] ."-". $params['planMonth'] . "') OR WORK_END_DATE IS NULL)";

                            $tmsCustomerCode = Config::getFromCache('tmsCustomerCode');

                            $tmsPlanPreview .= '  OR (  TO_CHAR(WORK_END_DATE,\'YYYY-MM\') >= \'' . $params['planYear'] .'-'. $params['planMonth'] . '\' AND  WORK_END_DATE IS NOT NULL)';

                            if (Config::getFromCache('tmsPlanPreview') == '1') {
                                $tmsPlanPreview = "((TO_CHAR(WORK_END_DATE + $monthLimit, 'YYYY-MM') >= '" . $params['planYear'] ."-". $params['planMonth'] . "') OR WORK_END_DATE IS NULL)";
                            }

                            if ($tmsCustomerCode === 'khaanbank') {
                                $tmsPlanPreview .= '  OR (  TO_CHAR(WORK_END_DATE,\'YYYY-MM\') >= \'' . $params['planYear'] .'-'. $params['planMonth'] . '\' AND  WORK_END_DATE IS NOT NULL)';
                            }                 

                            (String) $tableColumn2 = '';
                            (String) $orderColumn = '';

                            if ($tmsCustomerCode == 'gov') {
                                $tableColumn2 = ", VE.DEP_ORDER, VE.POS_ORDER, VE.WORK_START_DATE ";
                                $orderColumn = "ORDER BY LPAD(DEP_ORDER, 10), LPAD(POS_ORDER, 10), WORK_START_DATE ASC";
                            }   


                            $autoNumber = 1;
                            $employeeCount = $this->db->GetOne("SELECT COUNT(tem.EMPLOYEE_ID) FROM (
                                                                SELECT 
                                                                    VE.EMPLOYEE_ID,
                                                                    VE.EMPLOYEE_KEY_ID,
                                                                    VE.LAST_NAME,
                                                                    VE.FIRST_NAME,
                                                                    VE.CODE,
                                                                    VE.STATUS_NAME,
                                                                    VE.POSITION_NAME,
                                                                    VE.POSITION_KEY_ID,
                                                                    VE.EMPLOYEE_PICTURE, 
                                                                    VE.DEPARTMENT_ID, 
                                                                    VE.DEPARTMENT_NAME,
                                                                    TETPH.ID AS FULL_TIME_ID,
                                                                    TETPH.FULL_TIME,
                                                                    TETPH.D1,TETPH.D2,TETPH.D3,TETPH.D4,TETPH.D5,TETPH.D6,TETPH.D7,TETPH.D8,TETPH.D9,TETPH.D10,
                                                                    TETPH.D11,TETPH.D12,TETPH.D13,TETPH.D14,TETPH.D15,TETPH.D16,TETPH.D17,TETPH.D18,TETPH.D19,TETPH.D20,
                                                                    TETPH.D21,TETPH.D22,TETPH.D23,TETPH.D24,TETPH.D25,TETPH.D26,TETPH.D27,TETPH.D28,TETPH.D29,TETPH.D30,
                                                                    TETPH.D31, ek.STATUS_ID, ek.CURRENT_STATUS_ID $tableColumn2
                                                                FROM VW_TMS_EMPLOYEE VE
                                                                INNER JOIN ( 
                                                                    SELECT
                                                                    MAX(EMPLOYEE_KEY_ID) AS EMPLOYEE_KEY_ID
                                                                    FROM
                                                                    HRM_EMPLOYEE_KEY
                                                                    WHERE ( 
                                                                          ".$tmsPlanPreview."
                                                                        )
                                                                    GROUP BY
                                                                       EMPLOYEE_ID
                                                                ) ACTIVE_HEK ON ACTIVE_HEK.EMPLOYEE_KEY_ID = VE.EMPLOYEE_KEY_ID       
                                                                LEFT JOIN ( 
                                                                    SELECT
                                                                    BB.NEW_EMPLOYEE_KEY_ID AS EMPLOYEE_KEY_ID,
                                                                    '0' AS LIMITLESS
                                                                    FROM HCM_LABOUR_BOOK AA
                                                                    INNER JOIN HCM_LABOUR_BOOK_DTL BB ON AA.ID = BB.BOOK_ID
                                                                    INNER JOIN HRM_EMPLOYEE_KEY LK ON LK.EMPLOYEE_KEY_ID = BB.NEW_EMPLOYEE_KEY_ID
                                                                    WHERE AA.BOOK_TYPE_ID IN ($bookTypeIds) AND LK.CURRENT_STATUS_ID NOT IN (1, 3) 
                                                                    UNION ALL
                                                                    SELECT
                                                                    BB.NEW_EMPLOYEE_KEY_ID AS EMPLOYEE_KEY_ID,
                                                                    '1' AS LIMITLESS
                                                                    FROM HCM_LABOUR_BOOK AA
                                                                    INNER JOIN HCM_LABOUR_BOOK_DTL BB ON AA.ID = BB.BOOK_ID
                                                                    INNER JOIN HRM_EMPLOYEE_KEY LK ON LK.EMPLOYEE_KEY_ID = BB.NEW_EMPLOYEE_KEY_ID
                                                                    WHERE AA.BOOK_TYPE_ID IN ($bookTypeIds) AND TO_CHAR(BB.START_DATE + $monthLimit, 'YYYY-MM') >= '" . $params['planYear'] ."-". $params['planMonth'] . "' AND TO_CHAR(BB.START_DATE - $monthLimit, 'YYYY-MM') <= '" . $params['planYear'] ."-". $params['planMonth'] . "' AND LK.CURRENT_STATUS_ID NOT IN (1, 3)
                                                                ) LAB_HEK ON LAB_HEK.EMPLOYEE_KEY_ID = ACTIVE_HEK.EMPLOYEE_KEY_ID                                                                                                                                     
                                                                INNER JOIN HRM_EMPLOYEE_KEY ek ON ACTIVE_HEK.EMPLOYEE_KEY_ID = ek.EMPLOYEE_KEY_ID
                                                                LEFT JOIN TMS_EMPLOYEE_TIME_PLAN_HDR TETPH ON TETPH.YEAR_ID = " . $params['planYear'] . " AND TETPH.MONTH_ID = " . $params['planMonth'] . " AND TETPH.EMPLOYEE_ID = VE.EMPLOYEE_ID
                                                                $leftJoin
                                                                WHERE ek.STATUS_ID NOT IN ($statusNotIn) AND (CASE
                                                                    WHEN LAB_HEK.EMPLOYEE_KEY_ID IS NULL THEN 0
                                                                    WHEN LAB_HEK.EMPLOYEE_KEY_ID IS NOT NULL AND LAB_HEK.LIMITLESS = '0' THEN 1
                                                                    ELSE 2
                                                                END) IN (0,2) AND ek.CURRENT_STATUS_ID NOT IN ($currentStatusNotIn)
                                                                    " . $whereString . $causeString1 . "
                                                                ) tem");

                            $queryString = "SELECT * FROM (
                                SELECT 
                                    VE.EMPLOYEE_ID,
                                    VE.EMPLOYEE_KEY_ID,
                                    VE.LAST_NAME,
                                    VE.FIRST_NAME,
                                    VE.CODE,
                                    VE.STATUS_NAME,
                                    VE.POSITION_NAME,
                                    VE.POSITION_KEY_ID,
                                    VE.EMPLOYEE_PICTURE, 
                                    VE.DEPARTMENT_ID, 
                                    VE.DEPARTMENT_NAME,
                                    VE.REGISTER_NUMBER,
                                    TETPH.ID AS FULL_TIME_ID,
                                    TETPH.FULL_TIME,
                                    TETPH.D1,TETPH.D2,TETPH.D3,TETPH.D4,TETPH.D5,TETPH.D6,TETPH.D7,TETPH.D8,TETPH.D9,TETPH.D10,
                                    TETPH.D11,TETPH.D12,TETPH.D13,TETPH.D14,TETPH.D15,TETPH.D16,TETPH.D17,TETPH.D18,TETPH.D19,TETPH.D20,
                                    TETPH.D21,TETPH.D22,TETPH.D23,TETPH.D24,TETPH.D25,TETPH.D26,TETPH.D27,TETPH.D28,TETPH.D29,TETPH.D30,
                                    $leftJoinAttr
                                    TETPH.D31, ek.STATUS_ID, ek.CURRENT_STATUS_ID,
                                    ". $this->db->IfNull('TETPH.ID', 'null') ." AS IS_EXIST $tableColumn2
                                FROM VW_TMS_EMPLOYEE VE
                                INNER JOIN ( 
                                    SELECT
                                    MAX(EMPLOYEE_KEY_ID) AS EMPLOYEE_KEY_ID
                                    FROM
                                    HRM_EMPLOYEE_KEY
                                    WHERE (
                                            ".$tmsPlanPreview."
                                          )
                                    GROUP BY
                                       EMPLOYEE_ID
                                ) ACTIVE_HEK ON ACTIVE_HEK.EMPLOYEE_KEY_ID = VE.EMPLOYEE_KEY_ID                     
                                LEFT JOIN ( 
                                    SELECT
                                    BB.NEW_EMPLOYEE_KEY_ID AS EMPLOYEE_KEY_ID,
                                    '0' AS LIMITLESS
                                    FROM HCM_LABOUR_BOOK AA
                                    INNER JOIN HCM_LABOUR_BOOK_DTL BB ON AA.ID = BB.BOOK_ID
                                    INNER JOIN HRM_EMPLOYEE_KEY LK ON LK.EMPLOYEE_KEY_ID = BB.NEW_EMPLOYEE_KEY_ID
                                    WHERE AA.BOOK_TYPE_ID IN ($bookTypeIds) AND LK.CURRENT_STATUS_ID NOT IN (1, 3)
                                    UNION ALL
                                    SELECT
                                    BB.NEW_EMPLOYEE_KEY_ID AS EMPLOYEE_KEY_ID,
                                    '1' AS LIMITLESS
                                    FROM HCM_LABOUR_BOOK AA
                                    INNER JOIN HCM_LABOUR_BOOK_DTL BB ON AA.ID = BB.BOOK_ID
                                    INNER JOIN HRM_EMPLOYEE_KEY LK ON LK.EMPLOYEE_KEY_ID = BB.NEW_EMPLOYEE_KEY_ID
                                    WHERE AA.BOOK_TYPE_ID IN ($bookTypeIds) AND TO_CHAR(BB.START_DATE + $monthLimit, 'YYYY-MM') >= '" . $params['planYear'] ."-". $params['planMonth'] . "' AND TO_CHAR(BB.START_DATE - $monthLimit, 'YYYY-MM') <= '" . $params['planYear'] ."-". $params['planMonth'] . "' AND LK.CURRENT_STATUS_ID NOT IN (1, 3)
                                ) LAB_HEK ON LAB_HEK.EMPLOYEE_KEY_ID = ACTIVE_HEK.EMPLOYEE_KEY_ID                                                                                      
                                INNER JOIN HRM_EMPLOYEE_KEY ek ON ACTIVE_HEK.EMPLOYEE_KEY_ID = ek.EMPLOYEE_KEY_ID
                                LEFT JOIN TMS_EMPLOYEE_TIME_PLAN_HDR TETPH ON TETPH.YEAR_ID = " . $params['planYear'] . " AND TETPH.MONTH_ID = " . $params['planMonth'] . " AND TETPH.EMPLOYEE_ID = VE.EMPLOYEE_ID
                                $leftJoin
                                $leftJoinDay
                                WHERE ek.STATUS_ID NOT IN ($statusNotIn) AND (CASE
                                        WHEN LAB_HEK.EMPLOYEE_KEY_ID IS NULL THEN 0
                                        WHEN LAB_HEK.EMPLOYEE_KEY_ID IS NOT NULL AND LAB_HEK.LIMITLESS = '0' THEN 1
                                        ELSE 2
                                    END) IN (0,2) AND ek.CURRENT_STATUS_ID NOT IN ($currentStatusNotIn)
                                    " . $whereString . $causeString1 . " 
                                ORDER BY ".($tableColumn2 ? ltrim($tableColumn2, ',') : 'VE.DEPARTMENT_NAME, VE.FIRST_NAME, VE.POSITION_NAME')." ASC ) TEMP $orderColumn";

                            $page = Input::postCheck('page') ? Input::post('page') : 1;
                            $rows = Input::postCheck('rows') ? Input::post('rows') : (Config::getFromCache('tmsPageNumber') ? Config::getFromCache('tmsPageNumber') : 50);
                            $offset = ($page - 1) * $rows;                                

                            // echo $queryString; die;
                            $employeers = $this->db->SelectLimit($queryString, $rows, $offset);
                            $employeers = isset($employeers->_array) ? $employeers->_array : array();
                            //pa($employeers);

                            if ($employeeCount > 0) {
                                $selectHoliday = "
                                SELECT 
                                    START_DATE, 
                                    END_DATE, 
                                    HOLIDAY_NAME
                                FROM LM_HOLIDAY 
                                WHERE  END_DATE IS NOT NULL";
                                $holidays = $this->db->GetAll($selectHoliday);

                                foreach ($departmentList as $k => $department) {
                                    $depIndex = 1;
                                    $planDtlGroup = array();

                                    $days = self::getWorkingDateV2Model(array('planMonth'=>$params['planMonth'], 'planYear'=>$params['planYear'], 'tableName' => 'TNA_EMPLOYEE_TIME_PLAN'), $holidays);

                                    foreach ($employeers as $i => $employee) {
                                        if ($department['DEPARTMENT_ID'] === $employee['DEPARTMENT_ID']) {

                                            if (!empty($employee['FULL_TIME_ID'])) {
                                                $planDtlDataByPlan = $this->db->GetAll("SELECT 'D'||LTRIM(TO_CHAR(PLAN_DATE, 'DD'), '0') AS DATE_STR, PLAN_ID ".
                                                            "FROM TMS_EMPLOYEE_TIME_PLAN_DTL ".
                                                            "WHERE TIME_PLAN_ID = " . $employee['FULL_TIME_ID']);
                                                $planDtlGroup = Arr::groupByArray($planDtlDataByPlan, 'DATE_STR');            
                                            }

                                            if (empty($i)) {
                                                $theader = '<thead>';
                                                $theader .= '<tr>';
                                                $theader .= '<th style="width:10px; text-align: center" class="number rowNumber">№</th>';
                                                if ($isGolomt) {
                                                    $theader .= '<th style="width:200px; min-width:200px; text-align: center !important;"><span>Овог, Нэр</span></th>';
                                                    $theader .= '<th style="width:100px; min-width:100px; text-align: center !important;"><span>'. $golomtView .'</span></th>';
                                                } else {
                                                    $theader .= '<th style="width:200px; min-width:200px;  text-align: center !important;"><span>Овог, Нэр ('. $golomtView .' - РД)</span></th>';
                                                }
                                                $theader .= '<th style="width:200px; min-width:200px;" class="text-center">Албан тушаал</th>';
                                            }

                                            if ($depIndex === 1) {
                                                $depIndex++;
                                                $tbody .= '<tbody class="tablesorter-no-sort">';
                                                    $tbody .= '<tr class="row-details" data-department="' . $department['DEPARTMENT_ID'] . '" id="EMPLOYEE_'. $employee['EMPLOYEE_ID'] . '_' . $employee['EMPLOYEE_KEY_ID'] . '_' . $department['DEPARTMENT_ID'] . '">';
                                                        $tbody .= '<td class="number"> &nbsp;&nbsp; </td>';
                                                        $tbody .= '<td class="pl10 departmentTitle" data-colspan="1" colspan="20" style="border-right-color: transparent; word-wrap: break-word;">' . $department['DEPARTMENT_NAME'] . '</td>';
                                                        $tbody .= '<td style="border-right-color: transparent;"></td>';
                                                    $tbody .= '</tr>';
                                                $tbody .= '</tbody>';
                                            }

                                            $tbody .= '<tr data-department="' . $employee['DEPARTMENT_ID'] . '" id="EMPLOYEE_'. $employee['EMPLOYEE_ID'] . '_' . $employee['EMPLOYEE_KEY_ID'] . '_' . $employee['DEPARTMENT_ID'] . '">';
                                            $tbody .= '<td style="padding-left:2px; padding-right:8px; line-height: 13px; vertical-align: middle;" class="text-center no-select">' . $autoNumber . '</td>';
                                            $tbody .= '<td class="align-left pl10 pr10 no-select" style="line-height: 13px; vertical-align: middle;" title="'.$employee['LAST_NAME'].'.'.$employee['FIRST_NAME'].'">';

                                                $empLoyeeName = mb_substr ($employee['LAST_NAME'], 0, 2, 'UTF-8').'.'.$employee['FIRST_NAME'].' ';
                                                if ($tmsCustomerCode == 'gov') {
                                                    $empLoyeeName = mb_substr ($employee['LAST_NAME'], 0, 1, 'UTF-8').'.'.$employee['FIRST_NAME'].' ';
                                                }

                                                $tbody .= '<input type="hidden" data-name="firstName" name="firstName[]" value="' . $employee['FIRST_NAME'] . '">';
                                                $tbody .= '<input type="hidden" data-name="lastName" name="lastName[]" value="' . $employee['LAST_NAME'] . '">';
                                                $tbody .= '<input type="hidden" data-name="departmentId" name="departmentId[]" value="' . $employee['DEPARTMENT_ID'] . '">';
                                                $tbody .= '<input type="hidden" data-name="employeeId" name="employeeId[]" value="' . $employee['EMPLOYEE_ID'] . '">';
                                                $tbody .= '<input type="hidden" data-name="employeeKeyId" name="employeeKeyId[]" value="' . $employee['EMPLOYEE_KEY_ID'] . '">';
                                                $tbody .= '<input type="hidden" data-name="positionKeyId" name="positionKeyId[]" value="' . $employee['POSITION_KEY_ID'] . '">';
                                                $tbody .= '<input type="hidden" data-name="positionName" name="positionName[]" value="' . $employee['POSITION_NAME'] . '">';
                                                $tbody .= '<input type="hidden" data-name="code" name="code[]" value="' . $employee['CODE'] . '">';
                                                $tbody .= '<input type="hidden" data-name="fullTimeId" name="fullTimeId[]" value="' . $employee['FULL_TIME_ID'] . '">';
                                                $tbody .= '<input type="hidden" data-name="isExist" name="isExist[]" value="' . $employee['IS_EXIST'] . '">';

                                                if ($isGolomt) {
                                                        $tbody .= $empLoyeeName;
                                                    $tbody .= '</td>';
                                                    $tbody .= '<td class="pl10 pr10 no-select" style="line-height: 13px; vertical-align: middle;">' . $employee['CODE'] . '</td>';
                                                } else {
                                                    if ($tmsCustomerCode === 'khaanbank' || $tmsCustomerCode === 'skyresort') {
                                                        $tbody .= $empLoyeeName. ' <i>('. $employee['CODE'] .')<i>';
                                                    } else {
                                                        $tbody .= $empLoyeeName. ' <i>('. $employee['CODE'] . ' - ' . $employee['REGISTER_NUMBER'] .')<i>';
                                                    }
                                                    $tbody .= '</td>';
                                                }

                                            $tbody .= '<td class="pl10 pr10 no-select" style="line-height: 13px; vertical-align: middle;">' . $employee['POSITION_NAME'] . '</td>';

                                            $totalAmountPlanTime = 0;

                                            $iday = 1;
                                            foreach ($days as $key => $day) {
                                                $dayAddin = $iday;

                                                if (empty($i)) {
                                                    $theader .= '<th rowspan="2" data-isworking="' . $day['SPELL_DAY'] . '" class="tbl-cell ' . $day['DAY_CLASS'] . '" style="width: 22px; font-weight: 500; min-width: 22px; line-height: 13px; vertical-align: middle">';
                                                    $theader .= '<div class="dayName">' . $day['DAY'] . '</div>';
                                                    $theader .= '<div class="dayName">' . $day['SPELL_DAY_SHORT_NAME'] . '</div>';
                                                    $theader .= '</th>';
                                                }

                                                if ($employee['D' . $dayAddin] !== '') {

                                                    if (!array_key_exists('D'. $iday, $planDtlGroup)) {
                                                        $employee['PLAN_TIME_'. $iday] = 0;
                                                    }

                                                    $dayPlanTime = ($employee['PLAN_TIME_'. $iday] > 0) ? $employee['PLAN_TIME_'. $iday] : '';
                                                    //$dayPlanColor = ($isHishigArvin) ? ($employee['COLOR_'. $iday]) ? $employee['COLOR_'. $iday] : '' : '';
                                                    $dayPlanColor = $employee['COLOR_'. $iday] ? $employee['COLOR_'. $iday] : '';
                                                    $dayPlanTime = ($isHishigArvin) ? ($employee['SHORT_NAME_'. $iday] ? $employee['SHORT_NAME_'. $iday] : $dayPlanTime) : $dayPlanTime;

                                                    $tbody .= '<td title="' . $day['HOLIDAY'] . ' ' . $day['STATUS_DESCRIPTION'] . '" style="cursor: context-menu; background-color:' . (($dayPlanColor) ? $dayPlanColor : ((strtolower($day['DAY_COLOR']) !== '') ? $day['DAY_COLOR'] : 'transparent' )) .'; text-align:center; vertical-align: middle; ' . ($day['IS_LOCK'] == '1' ? 'background-image : url(\'assets/core/global/img/cell-status.png\'); background-repeat: no-repeat; background-position: bottom right;' : '') . ' "  data-isworking="' .$day['SPELL_DAY'] . '" class="tbl-cell  ' . $day['DAY_CLASS'] . '">';
                                                    $tbody .= '<input type="hidden" data-name="tnaEmployeeTimePlanId" name="tnaEmployeeTimePlanId[' . $employee['EMPLOYEE_ID'] .'][]" value="' . (isset($day['ID']) ? $day['ID'] : '') . '">';
                                                    $tbody .= '<input type="hidden" data-name="planDate" name="planDate[' . $employee['EMPLOYEE_ID'] .'][]" value="' . $day['PLAN_DATE'].'">';
                                                    $tbody .= '<input type="hidden" data-name="planCode" name="planCode[' . $employee['EMPLOYEE_ID'] .'][]" value="D'. $dayAddin. '">';
                                                    $tbody .= '<input type="hidden" data-name="planId" name="planId[' . $employee['EMPLOYEE_ID'] .'][]" value="' . $employee['PLAN_ID_'. $iday].'">';
                                                    $tbody .= '<input type="hidden" data-name="planTime" name="planTime[' . $employee['EMPLOYEE_ID'] .'][]" value="' . $employee['PLAN_TIME_'. $iday].'">';
                                                    $tbody .= '<input type="hidden" data-name="isSelectedCell" name="isSelectedCell[' . $employee['EMPLOYEE_ID'] .'][]" value="0">';
                                                        $tbody .= $dayPlanTime;
                                                    $tbody .= '</td>';        
                                                    $totalAmountPlanTime += ($employee['PLAN_TIME_'. $iday] > 0) ? $employee['PLAN_TIME_'. $iday] : '0';
                                                }  else {
                                                    $tbody .= '<td>';
                                                    $tbody .= '</td>';
                                                }

                                                $iday++;
                                            }

                                            $tbody .= '<td class="no-select" style="padding: 0 !important; background-color: #EEE; line-height: 13px; vertical-align: middle">';
                                                $tbody .= '<input type="text" class="form-control fullTime text-center" readonly ="readonly" maxlength="3" name="fullTime[]" data-name="fullTime" value="' . $totalAmountPlanTime . '" title="' . $totalAmountPlanTime . '" onchange="checkFullTime(this);">';
                                            $tbody .= '</td>';
                                            $tbody .= '</tr>';
                                            $autoNumber++;
                                            if (empty($i)) {
                                                $theader .= '<th style="width:40px; max-width:40px; text-align: center;" rowspan="2" class="rowNumber">НИЙТ</th>';
                                                $theader .= '</tr>';
                                                $theader .= '<tr class="bp-filter-row">';
                                                    $theader .= '<th class="rowNumber" style="background-color: rgb(231, 231, 231); position: relative; z-index: 10; background-clip: padding-box; top: -1px; left: 0px;"></th>';

                                                    if ($isGolomt) {
                                                        $theader .= '<th style="background-color: rgb(231, 231, 231); position: relative; z-index: 10; background-clip: padding-box; top: -1px; left: 0px;"><input type="text" data-fieldname="employeename" data-condition="like"></th>';
                                                        $theader .= '<th style="background-color: rgb(231, 231, 231); position: relative; z-index: 10; background-clip: padding-box; top: -1px; left: 0px;"><input type="text" data-fieldname="employeedomain" data-condition="like"></th>';
                                                    } else {
                                                        $theader .= '<th style="background-color: rgb(231, 231, 231); position: relative; z-index: 10; background-clip: padding-box; top: -1px; left: 0px;"><input type="text" data-fieldname="employeename" data-condition="like"></th>';
                                                    }
                                                    $theader .= '<th style="background-color: rgb(231, 231, 231); position: relative; z-index: 10; background-clip: padding-box; top: -1px; left: 0px;"><input type="text" data-fieldname="employeeposition" data-condition="like"></th>';
                                                $theader .= '</tr>';
                                                $theader .= '</thead>';
                                            }
                                        }
                                    }
                                }
                            }

                            $result = ' <input type="hidden" id="tnatimePlanTotalCount" value="'. $employeeCount .'">
                                        <input type="hidden" id="tnatimePlanPage" value="1">
                                        <input type="hidden" id="tnatimePlanIsArchive" value="0">
                                        <input type="hidden" id="srch_yearCode" value="'. $params['planYear'] .'">
                                        <input type="hidden" id="srch_monthCode" value="'. $params['planMonth'] .'">
                                        <div class="pf-custom-pager">
                                            <div id="fz-parent" class="freeze-overflow-xy-auto">
                                                <table class="table table-sm table-bordered table-hover gl-table-dtl bprocess-theme1" id="assetDtls">
                                                    '. $theader .'
                                                    <tbody>'. $tbody .'</tbody>
                                                    <tfoot>
                                                        '. $tfooter .'
                                                    </tfoot>
                                                </table>
                                            </div>
                                            <div class="pf-custom-pager-tool">
                                                <div class="pf-custom-pager-buttons">
                                                    '.Form::select(
                                                        array(
                                                            'name' => '',
                                                            'class' => 'pagination-page-list',
                                                            'op_value' => 'value',
                                                            'op_text' => 'code',
                                                            'style' => 'height:24px; float:left;color:#444',
                                                            'data' => array(
                                                                array('value' => '10', 'code' => '10'),
                                                                array('value' => '20', 'code' => '20'),
                                                                array('value' => '30', 'code' => '30'),
                                                                array('value' => '40', 'code' => '40'),
                                                                array('value' => '50', 'code' => '50'),
                                                                array('value' => '100', 'code' => '100'),
                                                                array('value' => '200', 'code' => '200')
                                                            ),
                                                            'text' => 'notext', 
                                                            'value' => Config::getFromCache('tmsPageNumber') ? Config::getFromCache('tmsPageNumber') : 50
                                                        )
                                                    ).'
                                                    <div class="pf-custom-pager-separator"></div>
                                                    <a href="javascript:;" class="pf-custom-pager-last-prev pf-custom-pager-disabled">
                                                        <span></span>
                                                    </a>
                                                    <a href="javascript:;" class="pf-custom-pager-prev pf-custom-pager-disabled">
                                                        <span></span>
                                                    </a>
                                                    <div class="pf-custom-pager-separator"></div>
                                                    <div class="pf-custom-pager-page-info">Хуудас <span><input type="text" size="2" value="1" data-gotopage="1" class="integerInit"></span> of <span data-pagenumber="1">0</span></div>	
                                                    <div class="pf-custom-pager-separator"></div>
                                                    <a href="javascript:;" class="pf-custom-pager-next pf-custom-pager-disabled">
                                                        <span></span>
                                                    </a>
                                                    <a href="javascript:;" class="pf-custom-pager-last-next pf-custom-pager-disabled">
                                                        <span></span>
                                                    </a>
                                                    <div class="pf-custom-pager-separator"></div>
                                                    <a href="javascript:;" class="pf-custom-pager-refresh pf-custom-pager-disabled">
                                                        <span></span>
                                                    </a>
                                                </div>
                                                <div class="pf-custom-pager-total"><span class="pf-custom-pager-total-selectedday" style="font-weight: normal"></span> Нийт <span>0</span> байна.</div>
                                            </div>
                                        </div>';
                }

                return $result;
            }
        }
    }          

    public function employeePlanListMainDataGridNewV4Model() {

        $page = Input::numeric('page', 1);
        $rows = Input::postCheck('rows') ? Input::post('rows') : (Config::getFromCache('tmsPageNumber') ? Config::getFromCache('tmsPageNumber') : 50);
        $balanceCriteria = $departmentList = array();
        $balanceDVid = Config::getFromCache('tnaTimePlanHdrDV');
        $theader = $tbody = $tfooter = '';
        $theadWidth = (int) Config::getFromCacheDefault('tnaPlanColumnSize', null, '200');

        parse_str(Input::post('params'), $params);

        if ((isset($params['groupId']) && is_array($params['groupId']))) {
            $balanceCriteria['filterGroupId'] = array(
                array('operator' => 'IN', 'operand' => implode(',', $params['groupId']))
            );
        }   

        if (is_array($params['newDepartmentId']) && count($params['newDepartmentId'])) {

            $departmentIds = $params['newDepartmentId']; 
            $departmentIds = implode(',', $departmentIds); 
            $isChild = issetVar($params['isChild']);

            if ($departmentIds) {

                $departmentIds = $this->getAllChildDepartment2Model($departmentIds, $isChild);

            } elseif (isset($balanceCriteria['filterGroupId'])) {

                $departmentIds = $this->getAllChildDepartmentByGroupIdModel($balanceCriteria['filterGroupId'][0]['operand']);
            }

            $balanceCriteria['filterDepartmentId'] = array(
                array('operator' => 'IN', 'operand' => $departmentIds['join'])
            );

            $departmentList = $departmentIds['array'];

            $positionIds = issetParam($params['positionId']);                                                
            if ($positionIds) {
                $positionIds = implode(',', $positionIds);
                $balanceCriteria['positionId'] = array(
                    array('operator' => 'IN', 'operand' => $positionIds)
                );
            }
        } elseif ($params['newDepartmentId'] && Config::getFromCache('tmsCustomerCode') == 'gov') {

            $departmentIds = $params['newDepartmentId'];
            $isChild = issetVar($params['isChild']);

            $departmentIds = $this->getAllChildDepartment2Model($departmentIds, $isChild);    

            $balanceCriteria['filterDepartmentId'] = array(
                array('operator' => 'IN', 'operand' => $departmentIds['join'])
            );

            $departmentList = $departmentIds['array'];
            $positionIds = issetParam($params['positionId']);      

            if ($positionIds) {
                $positionIds = implode(',', $positionIds);
                $balanceCriteria['positionId'] = array(
                    array('operator' => 'IN', 'operand' => $positionIds)
                );
            }                
        }
        
        if (trim($params['stringValue']) != '') {
            $balanceCriteria['filterStringValue'] = array(
                array('operator' => 'LIKE', 'operand' => '%' . $params['stringValue'] . '%')
            );
        }

        if ((isset($params['positionId']) && is_array($params['positionId']))) {
            $balanceCriteria['filterPositionId'] = array(
                array('operator' => 'IN', 'operand' => implode(',', $params['positionId']))
            );
        }       

        $caclStartDate = '';
        $caclEndDate = '';
        $caclYear = '';
        if (Config::getFromCache('tmsCalcIdCode') == '1') {
            if (empty($params['calcId'])) {
                $response = array('status' => 'error', 'message' => 'Бодолтын дугаараа сонгоно уу!');
                return $response ;
            }        
            $getCalcInfo = self::getCalcListModel($params['calcId']);
            $caclStartDate = Date::formatter($params['startDate']);
            $caclEndDate = Date::formatter($params['endDate']);
            $caclYear = Date::formatter($params['startDate'], 'Y');
            $balanceCriteria['filterStartDate'] = array(
                array('operator' => '=', 'operand' => $caclStartDate)
            );            
            $balanceCriteria['filterEndDate'] = array(
                array('operator' => '=', 'operand' => $caclEndDate)
            );            

        } else {

            $balanceCriteria['filterYear'] = array(
                array('operator' => '=', 'operand' => $params['planYear'])
            );     

            $balanceCriteria['filterMonth'] = array(
                array('operator' => '=', 'operand' => $params['planMonth'])
            );            
        }
        
        if (Input::postCheck('filterRules')) {
            $filterRules = json_decode(Str::cp1251_utf8($_POST['filterRules']), true);

            if (is_array($filterRules) && count($filterRules) > 0) {

                foreach ($filterRules as $rule) {
                    $ruleValue = Input::param(trim($rule['value']));

                    switch ($rule['field']) {
                        case 'employeename':
                            $balanceCriteria['filterStringValue'] = array(
                                array('operator' => 'LIKE', 'operand' => '%'.$ruleValue.'%')
                            );
                            break;
                        case 'employeeposition':
                            $balanceCriteria['positionname'] = array(
                                array('operator' => 'LIKE', 'operand' => '%'.$ruleValue.'%')
                            );
                            break;
                        case 'balancedateshow':
                            $balanceCriteria[$rule['field']] = array(
                                array('operator' => '=', 'operand' => $ruleValue)
                            );
                            break;                                
                        default:

                            if (strpos($ruleValue, ':') !== false) {
                                $ruleValue = explode(':', $ruleValue);
                                $ruleValue = (float) $ruleValue[0] * 60 + (float) $ruleValue[1];                            
                            } else {
                                $ruleValue = (float) $ruleValue * 60;                            
                            }

                            $balanceCriteria[$rule['field']] = array(
                                array('operator' => '=', 'operand' => $ruleValue)
                            );
                            break;
                    }
                }
            }
        }

        $param = array(
            'systemMetaGroupId' => $balanceDVid,
            'showQuery' => 0, 
            'ignorePermission' => 1,
            'paging' => array(
                'offset' => $page,
                'pageSize' => $rows
            ),
            'criteria' => $balanceCriteria
        );        

        $result = $this->ws->runSerializeResponse(self::$gfServiceAddress, Mddatamodel::$getDataViewCommand, $param);

        if ($result['status'] === 'success' && isset($result['result'])) {
            $response = array('status' => 'success');

            $response['total'] = (isset($result['result']['paging']) ? $result['result']['paging']['totalcount'] : 0);
            if (isset($result['result']['aggregatecolumns']) && $result['result']['aggregatecolumns']) {
                $response['footer'] = array($result['result']['aggregatecolumns']);
            }                

            $employeeCount = $result['result']['paging']['totalcount'];
            unset($result['result']['paging']);
            unset($result['result']['aggregatecolumns']);
            $autoNumber = 1;
            $tmsCustomerCode = Config::getFromCache('tmsCustomerCode');
            $isGolomt = $params['golomtView'];
            $golomtView = isset($params['golomtView']) ? (($params['golomtView']) ? 'Домайн' : 'Код') : 'Код';                

            $employeers = $result['result'];
            $employeersGroupData = Arr::groupByArray($employeers, 'employeekeyid');                   

            $selectHoliday = "
                SELECT 
                START_DATE, 
                END_DATE, 
                HOLIDAY_NAME
            FROM LM_HOLIDAY 
            WHERE END_DATE IS NOT NULL";

            $holidays = $this->db->GetAll($selectHoliday);
            $monStart = (int) Date::formatter($caclStartDate, 'm');
            $monEnd = (int) Date::formatter($caclEndDate, 'm');            

            if ($caclStartDate && $caclEndDate) {
                $days = self::getWorkingTwoDateV3Model(array('planYear'=>$caclYear, 'tableName' => 'TNA_EMPLOYEE_TIME_PLAN'), $holidays, $caclStartDate, $caclEndDate);
            } else {
                $days = self::getWorkingDateV3Model(array('planMonth'=>$params['planMonth'], 'planYear'=>$params['planYear'], 'tableName' => 'TNA_EMPLOYEE_TIME_PLAN'), $holidays);
            }
          
            $oneOfWorked = 1;
            foreach ($departmentList as $k => $department) {
                $depIndex = 1;
                $planDtlGroup = array();                                        

                foreach ($employeersGroupData as $i => $employee) {
                    $employeeTemp = $employee;
                    $employee = $employee['row'];

                    if ($department['DEPARTMENT_ID'] === $employee['departmentid']) {

                        if ($oneOfWorked === 1) {
                            $theader = '<thead>';
                            if(isset($days['endColspan'])) {
                                $theader .= '<tr class="month-merge-row">';    
                                $theader .= '<th></th>';    
                                $theader .= '<th></th>';    
                                $theader .= '<th></th>';    
                                $theader .= '<th class="month-'.((int) Date::formatter($caclStartDate, 'm')).'" style="font-weight:normal;border-bottom:1px solid #ddd !important;text-align: center;text-transform: lowercase;" colspan="'.$days['startColspan'].'">' . ((int) Date::formatter($caclStartDate, 'm'));    
                                $theader .= '-сар</th>';    
                                $theader .= '<th class="month-'.((int) Date::formatter($caclEndDate, 'm')).'" style="font-weight:normal;border-bottom:1px solid #ddd !important;text-align: center;text-transform: lowercase;" colspan="'.$days['endColspan'].'">' . ((int) Date::formatter($caclEndDate, 'm'));    
                                $theader .= '-сар</th>';    
                                $theader .= '<th></th>';
                                $theader .= '</tr>';    
                                $rowspan = '';
                            }
                            $theader .= '<tr>';
                            $theader .= '<th style="width:10px; text-align: center" class="number rowNumber">№</th>';
                            if ($isGolomt) {
                                $theader .= '<th style="width:'.$theadWidth.'px; min-width:'.$theadWidth.'px; text-align: center !important;"><span>Овог, Нэр</span></th>';
                                $theader .= '<th style="width:100px; min-width:100px; text-align: center !important;"><span>'. $golomtView .'</span></th>';
                            } else {
                                $theader .= '<th style="width:'.$theadWidth.'px; min-width:'.$theadWidth.'px; text-align: center !important;"><span>Овог, Нэр ('. $golomtView .' - РД)</span></th>';
                            }
                            $theader .= '<th style="width:'.$theadWidth.'px; min-width:'.$theadWidth.'px;" class="text-center">Албан тушаал</th>';
                        }

                        if ($depIndex === 1) {
                            $depIndex++;          
                            $tbody .= '<tbody class="tablesorter-no-sort">';
                                $tbody .= '<tr class="row-details" data-department="' . $department['DEPARTMENT_ID'] . '" id="EMPLOYEE_'. $employee['employeeid'] . '_' . $employee['employeekeyid'] . '_' . $department['DEPARTMENT_ID'] . '">';
                                    $tbody .= '<td class="number"> &nbsp;&nbsp; </td>';
                                    $tbody .= '<td class="pl10 departmentTitle" data-colspan="1" colspan="2" style="border-right-color: transparent; word-wrap: break-word;">' . $department['DEPARTMENT_NAME'] . '</td>';
                                    $tbody .= '<td style="border-right-color: transparent;"></td>';
                                $tbody .= '</tr>';
                            $tbody .= '</tbody>';
                        }

                        $tbody .= '<tr data-department="' . $employee['departmentid'] . '" id="EMPLOYEE_'. $employee['employeeid'] . '_' . $employee['employeekeyid'] . '_' . $employee['departmentid'] . '">';
                        $tbody .= '<td style="padding-left:2px; padding-right:8px; line-height: 13px; vertical-align: middle;" class="text-center no-select">' . $autoNumber . '</td>';
                        $tbody .= '<td class="align-left pl10 pr10 no-select" style="line-height: 13px; vertical-align: middle;" title="'.$employee['lastname'].'.'.$employee['firstname'].'">';

                            $empLoyeeName = mb_substr ($employee['lastname'], 0, 2, 'UTF-8').'.'.$employee['firstname'].' ';
                            if ($tmsCustomerCode == 'gov') {
                                $empLoyeeName = mb_substr ($employee['lastname'], 0, 1, 'UTF-8').'.'.$employee['firstname'].' ';
                            }

                            $tbody .= '<input type="hidden" data-name="firstName" name="firstName[]" value="' . $employee['firstname'] . '">';
                            $tbody .= '<input type="hidden" data-name="lastName" name="lastName[]" value="' . $employee['lastname'] . '">';
                            $tbody .= '<input type="hidden" data-name="departmentId" name="departmentId[]" value="' . $employee['departmentid'] . '">';
                            $tbody .= '<input type="hidden" data-name="employeeId" name="employeeId[]" value="' . $employee['employeeid'] . '">';
                            $tbody .= '<input type="hidden" data-name="employeeKeyId" name="employeeKeyId[]" value="' . $employee['employeekeyid'] . '">';
                            $tbody .= '<input type="hidden" data-name="positionKeyId" name="positionKeyId[]" value="' . $employee['positionkeyid'] . '">';
                            $tbody .= '<input type="hidden" data-name="positionName" name="positionName[]" value="' . $employee['positionname'] . '">';
                            $tbody .= '<input type="hidden" data-name="code" name="code[]" value="' . $employee['code'] . '">';
                            $tbody .= '<input type="hidden" data-name="fullTimeId" name="fullTimeId[]" value="' . $employee['fulltimeid'] . '">';
                            $tbody .= '<input type="hidden" data-name="isExist" name="isExist[]" value="' . $employee['isexist'] . '">';

                            if ($isGolomt) {
                                    $tbody .= $empLoyeeName;
                                $tbody .= '</td>';
                                $tbody .= '<td class="pl10 pr10 no-select" style="line-height: 13px; vertical-align: middle;">' . $employee['code'] . '</td>';
                            } else {
                                $tbody .= $empLoyeeName. ' <i>('. $employee['code'] .')</i>';
                                $tbody .= '</td>';
                            }

                        $tbody .= '<td class="pl10 pr10 no-select" style="line-height: 13px; vertical-align: middle;">' . $employee['positionname'] . '</td>';

                        $totalAmountPlanTime = 0;                            
                        if (Config::getFromCache('tmsCalcIdCode') == '1') {
                            unset($days['startColspan']);
                            unset($days['endColspan']);
                            $isExistSMonth = false;
                            $isExistEMonth = false;

                            foreach ($employeeTemp['rows'] as $empRows) {
                                if ($empRows['monthid'] == $monStart) {
                                    $isExistSMonth = true;
                                }
                                if ($empRows['monthid'] == $monEnd) {
                                    $isExistEMonth = true;
                                }                                    
                            }                                  

                            foreach ($days as $key => $day) {
                                $iday = $key;
                                $dayAddin = $iday;

                                if ($oneOfWorked === 1) {
                                    $theader .= '<th rowspan="2" data-month="' . $day['MONTH'] . '" data-isworking="' . $day['SPELL_DAY'] . '" class="tbl-cell ' . $day['DAY_CLASS'] . '" style="width: 22px; font-weight: 500; min-width: 22px; line-height: 13px; vertical-align: middle">';
                                    $theader .= '<div class="dayName">' . $day['DAY'] . '</div>';
                                    $theader .= '<div class="dayName">' . $day['SPELL_DAY_SHORT_NAME'] . '</div>';
                                    $theader .= '</th>';
                                }             
                                
                                if (!$isExistSMonth && $day['MONTH'] == $monStart) {
                                    $tbody .= '<td title="' . $day['HOLIDAY'] . ' ' . $day['STATUS_DESCRIPTION'] . '" style="cursor: context-menu; background-color:'.(($day['DAY_COLOR'] !== '') ? $day['DAY_COLOR'] : 'transparent' ).'; text-align:center; vertical-align: middle; ' . ($day['IS_LOCK'] == '1' ? 'background-image : url(\'assets/core/global/img/cell-status.png\'); background-repeat: no-repeat; background-position: bottom right;' : '') . ' "  data-isworking="' .$day['SPELL_DAY'] . '" class="tbl-cell  ' . $day['DAY_CLASS'] . '">';
                                    $tbody .= '<input type="hidden" data-name="tnaEmployeeTimePlanId" name="tnaEmployeeTimePlanId[' . $employee['employeeid'] .'][]" value="' . (isset($day['ID']) ? $day['ID'] : '') . '">';
                                    $tbody .= '<input type="hidden" data-name="planDate" name="planDate[' . $employee['employeeid'] .'][]" value="' . $day['PLAN_DATE'].'">';
                                    $tbody .= '<input type="hidden" data-name="planCode" name="planCode[' . $employee['employeeid'] .'][]" value="D'. $dayAddin. '">';
                                    $tbody .= '<input type="hidden" data-name="planId" name="planId[' . $employee['employeeid'] .'][]" value="">';
                                    $tbody .= '<input type="hidden" data-name="planTime" name="planTime[' . $employee['employeeid'] .'][]" value="">';
                                    $tbody .= '<input type="hidden" data-name="isSelectedCell" name="isSelectedCell[' . $employee['employeeid'] .'][]" value="0">';
                                    $tbody .= '<input type="hidden" data-name="isExist" name="isExist'.$day['MONTH'].'[' . $employee['employeeid'] .']" value="">';
                                    $tbody .= '</td>';                                    
                                }         
                                
                                if (!$isExistEMonth && $day['MONTH'] == $monEnd && $monStart != $monEnd) {
                                    $tbody .= '<td title="' . $day['HOLIDAY'] . ' ' . $day['STATUS_DESCRIPTION'] . '" style="cursor: context-menu; background-color:'.(($day['DAY_COLOR'] !== '') ? $day['DAY_COLOR'] : 'transparent' ).'; text-align:center; vertical-align: middle; ' . ($day['IS_LOCK'] == '1' ? 'background-image : url(\'assets/core/global/img/cell-status.png\'); background-repeat: no-repeat; background-position: bottom right;' : '') . ' "  data-isworking="' .$day['SPELL_DAY'] . '" class="tbl-cell  ' . $day['DAY_CLASS'] . '">';
                                    $tbody .= '<input type="hidden" data-name="tnaEmployeeTimePlanId" name="tnaEmployeeTimePlanId[' . $employee['employeeid'] .'][]" value="' . (isset($day['ID']) ? $day['ID'] : '') . '">';
                                    $tbody .= '<input type="hidden" data-name="planDate" name="planDate[' . $employee['employeeid'] .'][]" value="' . $day['PLAN_DATE'].'">';
                                    $tbody .= '<input type="hidden" data-name="planCode" name="planCode[' . $employee['employeeid'] .'][]" value="D'. $dayAddin. '">';
                                    $tbody .= '<input type="hidden" data-name="planId" name="planId[' . $employee['employeeid'] .'][]" value="">';
                                    $tbody .= '<input type="hidden" data-name="planTime" name="planTime[' . $employee['employeeid'] .'][]" value="">';
                                    $tbody .= '<input type="hidden" data-name="isSelectedCell" name="isSelectedCell[' . $employee['employeeid'] .'][]" value="0">';
                                    $tbody .= '<input type="hidden" data-name="isExist" name="isExist'.$day['MONTH'].'[' . $employee['employeeid'] .']" value="">';
                                    $tbody .= '</td>';                                    
                                }

                                foreach ($employeeTemp['rows'] as $empRows) {                                    
                                    if ($empRows['monthid'] == $day['MONTH']) {
                                        if ($empRows['d' . $dayAddin] !== '') {
                                            
                                            $dayPlanTime = ($empRows['plantime'. $iday] > 0) ? $empRows['plantime'. $iday] : '';
                                            $dayPlanColor = $empRows['color'. $iday] ? $empRows['color'. $iday] : '';
                                            $dayShortName = issetParam($empRows['shortname'. $iday]);
                                            
                                            $dayPlanTime = $dayShortName ? $dayShortName : $dayPlanTime;

                                            $tbody .= '<td title="' . $day['HOLIDAY'] . ' ' . $day['STATUS_DESCRIPTION'] . '" style="cursor: context-menu; background-color:' . (($dayPlanColor && $empRows['plantime'. $iday] != 0) ? $dayPlanColor : (($day['DAY_COLOR'] !== '') ? $day['DAY_COLOR'] : 'transparent' )) .'; text-align:center; vertical-align: middle; ' . ($day['IS_LOCK'] == '1' ? 'background-image : url(\'assets/core/global/img/cell-status.png\'); background-repeat: no-repeat; background-position: bottom right;' : '') . ' "  data-isworking="' .$day['SPELL_DAY'] . '" class="tbl-cell  ' . $day['DAY_CLASS'] . '">';
                                            $tbody .= '<input type="hidden" data-name="tnaEmployeeTimePlanId" name="tnaEmployeeTimePlanId[' . $empRows['employeeid'] .'][]" value="' . (isset($day['ID']) ? $day['ID'] : '') . '">';
                                            $tbody .= '<input type="hidden" data-name="planDate" name="planDate[' . $empRows['employeeid'] .'][]" value="' . $day['PLAN_DATE'].'">';
                                            $tbody .= '<input type="hidden" data-name="planCode" name="planCode[' . $empRows['employeeid'] .'][]" value="D'. $dayAddin. '">';
                                            $tbody .= '<input type="hidden" data-name="planId" name="planId[' . $empRows['employeeid'] .'][]" value="' . $empRows['planid'. $iday].'">';
                                            $tbody .= '<input type="hidden" data-name="planTime" name="planTime[' . $empRows['employeeid'] .'][]" value="' . $empRows['plantime'. $iday].'">';
                                            $tbody .= '<input type="hidden" data-name="isSelectedCell" name="isSelectedCell[' . $empRows['employeeid'] .'][]" value="0">';
                                            $tbody .= '<input type="hidden" data-name="isExist" name="isExist'.$day['MONTH'].'[' . $empRows['employeeid'] .']" value="' . $empRows['isexist'] . '">';
                                                $tbody .= $dayPlanTime;
                                            $tbody .= '</td>';        
                                            $totalAmountPlanTime += ($empRows['plantime'. $iday] > 0) ? $empRows['plantime'. $iday] : '0';
                                        }  else {
                                            $tbody .= '<td>';
                                            $tbody .= '</td>';
                                        }
                                    }
                                }

                                $iday++;
                            }

                        } else {

                            $iday = 1;
                            foreach ($days as $key => $day) {
                                $dayAddin = $iday;

                                if ($oneOfWorked === 1) {
                                    $theader .= '<th rowspan="2" data-isworking="' . $day['SPELL_DAY'] . '" class="tbl-cell ' . $day['DAY_CLASS'] . '" style="width: 22px; font-weight: 500; min-width: 22px; line-height: 13px; vertical-align: middle">';
                                    $theader .= '<div class="dayName">' . $day['DAY'] . '</div>';
                                    $theader .= '<div class="dayName">' . $day['SPELL_DAY_SHORT_NAME'] . '</div>';
                                    $theader .= '</th>';
                                }

                                if ($employee['d' . $dayAddin] !== '') {

                                    $dayPlanTime = ($employee['plantime'. $iday] > 0) ? $employee['plantime'. $iday] : '';
                                    $dayPlanColor = $employee['color'. $iday] ? $employee['color'. $iday] : '';
                                    $dayShortName = issetParam($employee['shortname'. $iday]);
                                    
                                    $dayPlanTime = $dayShortName ? $dayShortName : $dayPlanTime;

                                    $tbody .= '<td title="' . $day['HOLIDAY'] . ' ' . $day['STATUS_DESCRIPTION'] . '" style="cursor: context-menu; background-color:' . (($dayPlanColor) ? $dayPlanColor : ((strtolower($day['DAY_COLOR']) !== '') ? $day['DAY_COLOR'] : 'transparent' )) .'; text-align:center; vertical-align: middle; ' . ($day['IS_LOCK'] == '1' ? 'background-image : url(\'assets/core/global/img/cell-status.png\'); background-repeat: no-repeat; background-position: bottom right;' : '') . ' "  data-isworking="' .$day['SPELL_DAY'] . '" class="tbl-cell ' . $day['DAY_CLASS'] . '">';
                                    $tbody .= '<input type="hidden" data-name="tnaEmployeeTimePlanId" name="tnaEmployeeTimePlanId[' . $employee['employeeid'] .'][]" value="' . (isset($day['ID']) ? $day['ID'] : '') . '">';
                                    $tbody .= '<input type="hidden" data-name="planDate" name="planDate[' . $employee['employeeid'] .'][]" value="' . $day['PLAN_DATE'].'">';
                                    $tbody .= '<input type="hidden" data-name="planCode" name="planCode[' . $employee['employeeid'] .'][]" value="D'. $dayAddin. '">';
                                    $tbody .= '<input type="hidden" data-name="planId" name="planId[' . $employee['employeeid'] .'][]" value="' . $employee['planid'. $iday].'">';
                                    $tbody .= '<input type="hidden" data-name="planTime" name="planTime[' . $employee['employeeid'] .'][]" value="' . $employee['plantime'. $iday].'">';
                                    $tbody .= '<input type="hidden" data-name="isSelectedCell" name="isSelectedCell[' . $employee['employeeid'] .'][]" value="0">';
                                        $tbody .= $dayPlanTime;
                                    $tbody .= '</td>';        
                                    if (issetParam($employee['fondminute'])) {
                                        $totalAmountPlanTime = $employee['fondminute'];
                                    } else {
                                        $totalAmountPlanTime += ($employee['plantime'. $iday] > 0) ? $employee['plantime'. $iday] : '0';
                                    }
                                }  else {
                                    $tbody .= '<td>';
                                    $tbody .= '</td>';
                                }

                                $iday++;
                            }
                        }

                        $tbody .= '<td class="no-select" style="padding: 0 !important; background-color: #EEE; line-height: 13px; vertical-align: middle">';
                            $tbody .= '<input type="text" class="form-control fullTime text-center" readonly ="readonly" maxlength="3" name="fullTime[]" data-name="fullTime" value="' . $totalAmountPlanTime . '" title="' . $totalAmountPlanTime . '" onchange="checkFullTime(this);">';
                        $tbody .= '</td>';
                        $tbody .= '</tr>';
                        $autoNumber++;
                        if ($oneOfWorked === 1) {
                            $theader .= '<th style="width:60px; max-width:60px; text-align: center;" rowspan="2" class="rowNumber">НИЙТ</th>';
                            $theader .= '</tr>';
                            $theader .= '<tr class="bp-filter-row">';
                                $theader .= '<th class="rowNumber" style="background-color: rgb(231, 231, 231); position: relative; z-index: 10; background-clip: padding-box; top: -1px; left: 0px;"></th>';

                                if ($isGolomt) {
                                    $theader .= '<th style="background-color: rgb(231, 231, 231); position: relative; z-index: 10; background-clip: padding-box; top: -1px; left: 0px;"><input type="text" data-fieldname="employeename" data-condition="like"></th>';
                                    $theader .= '<th style="background-color: rgb(231, 231, 231); position: relative; z-index: 10; background-clip: padding-box; top: -1px; left: 0px;"><input type="text" data-fieldname="employeedomain" data-condition="like"></th>';
                                } else {
                                    $theader .= '<th style="background-color: rgb(231, 231, 231); position: relative; z-index: 10; background-clip: padding-box; top: -1px; left: 0px;"><input type="text" data-fieldname="employeename" data-condition="like"></th>';
                                }
                                $theader .= '<th style="background-color: rgb(231, 231, 231); position: relative; z-index: 10; background-clip: padding-box; top: -1px; left: 0px;"><input type="text" data-fieldname="employeeposition" data-condition="like"></th>';
                            $theader .= '</tr>';
                            $theader .= '</thead>';
                        }
                        $depIndex++;
                        $oneOfWorked++;
                    }
                }
            }

            $resultString = ' <input type="hidden" id="tnatimePlanTotalCount" value="'. $employeeCount .'">
            <input type="hidden" id="tnatimePlanPage" value="1">
            <input type="hidden" id="tnatimePlanIsArchive" value="0">
            <input type="hidden" id="srch_yearCode" value="'. $params['planYear'] .'">
            <input type="hidden" id="srch_monthCode" value="'. $params['planMonth'] .'">
            <div class="pf-custom-pager">
                <div id="fz-parent" class="freeze-overflow-xy-auto">
                    <table class="table table-sm table-bordered table-hover gl-table-dtl bprocess-theme1" id="assetDtls">
                        '. $theader .'
                        <tbody>'. $tbody .'</tbody>
                        <tfoot>
                            '. $tfooter .'
                        </tfoot>
                    </table>
                </div>
                <div class="pf-custom-pager-tool">
                    <div class="pf-custom-pager-buttons">
                        '.Form::select(
                            array(
                                'name' => '',
                                'class' => 'pagination-page-list',
                                'op_value' => 'value',
                                'op_text' => 'code',
                                'style' => 'height:24px; float:left;color:#444',
                                'data' => array(
                                    array('value' => '10', 'code' => '10'),
                                    array('value' => '20', 'code' => '20'),
                                    array('value' => '30', 'code' => '30'),
                                    array('value' => '40', 'code' => '40'),
                                    array('value' => '50', 'code' => '50'),
                                    array('value' => '100', 'code' => '100'),
                                    array('value' => '200', 'code' => '200')
                                ),
                                'text' => 'notext', 
                                'value' => Config::getFromCache('tmsPageNumber') ? Config::getFromCache('tmsPageNumber') : 50
                            )
                        ).'
                        <div class="pf-custom-pager-separator"></div>
                        <a href="javascript:;" class="pf-custom-pager-last-prev pf-custom-pager-disabled">
                            <span></span>
                        </a>
                        <a href="javascript:;" class="pf-custom-pager-prev pf-custom-pager-disabled">
                            <span></span>
                        </a>
                        <div class="pf-custom-pager-separator"></div>
                        <div class="pf-custom-pager-page-info">Хуудас <span><input type="text" size="2" value="1" data-gotopage="1" class="integerInit"></span> of <span data-pagenumber="1">0</span></div>	
                        <div class="pf-custom-pager-separator"></div>
                        <a href="javascript:;" class="pf-custom-pager-next pf-custom-pager-disabled">
                            <span></span>
                        </a>
                        <a href="javascript:;" class="pf-custom-pager-last-next pf-custom-pager-disabled">
                            <span></span>
                        </a>
                        <div class="pf-custom-pager-separator"></div>
                        <a href="javascript:;" class="pf-custom-pager-refresh pf-custom-pager-disabled">
                            <span></span>
                        </a>
                    </div>
                    <div class="pf-custom-pager-total"><span class="pf-custom-pager-total-selectedday" style="font-weight: normal"></span> Нийт <span>0</span> байна.</div>
                </div>
            </div>';               

            if (Input::postCheck('reload')) {
                $response = array('total' => $employeeCount, 'Html' => $tbody, 'status' => 'success');
                return $response;
            } else {
                return $resultString;
            }

        } else {
            $response = array('status' => 'error', 'message' => $this->ws->getResponseMessage($result));
            return $response ;
        }            
    }        

    public function getCalcListModel($id = null) {
        (Array) $param = array(
            'systemMetaGroupId' => 1599818054136,
            'showQuery' => 0, 
            'ignorePermission' => 1
        );
        if ($id) {
            $param['criteria']['id'] = array(
                array('operator' => '=', 'operand' => $id)
            );    
        }

        $data = $this->ws->runResponse(self::$gfServiceAddress, Mddatamodel::$getDataViewCommand, $param);            
        if ($data['status'] === 'success' && isset($data['result'])) {
            unset($data['result']['paging']);
            unset($data['result']['aggregatecolumns']);

            return $data['result'];
        }            
    }    

    public function getArchivPlanListMainDataGridModel() {
        $result = $footerArr = $newBody = $employeers = $headerDays = $departmentIdArr = array();
        $response = $theader = $tbody = $tfooter = '';

        $isHishigArvin = Config::getFromCache('CONFIG_TNA_HISHIGARVIN');
        $isGolomt = (defined('CONFIG_TNA_GOLOMT') ? CONFIG_TNA_GOLOMT : false);
        $golomtView = isset($isGolomt) ? (($isGolomt) ? 'Домайн' : 'Код') : 'Код';

        if (Input::post('departmentId')) {

            $departmentIds = rtrim(Input::post('departmentId'), ',');  
            $isChild = issetVar($params['isChild']);

            if ($departmentIds) {

                $departmentIds = $this->getAllChildDepartment2Model($departmentIds, $isChild);

            } elseif (isset($balanceCriteria['filterGroupId'])) {

                $departmentIds = $this->getAllChildDepartmentByGroupIdModel($balanceCriteria['filterGroupId'][0]['operand']);
            }

            $balanceCriteria['filterDepartmentId'] = array(
                array('operator' => 'IN', 'operand' => $departmentIds['join'])
            );

            $departmentIdArr = $departmentIds['array'];
        }        

        $result = $this->db->GetRow("
            SELECT
                YEAR_ID,
                MONTH_ID,
                DESCRIPTION
            FROM 
                TMS_EMPLOYEE_PLAN_LOG
            WHERE 
                ID = " . Input::post('planLogId'));
        
        if ($result) {
            $result['YEAR_ID'] = intval($result['YEAR_ID']);
            $result['MONTH_ID'] = intval($result['MONTH_ID']);
            if (!empty($result['YEAR_ID']) && !empty($result['MONTH_ID'])) {
                if (count($departmentIdArr) > 0) {

                    foreach ($departmentIdArr as $k => $department) {
                    $depIndex = 1;
                        if (empty($k)) {
                            $theader .= '<thead>';
                            $theader .= '<tr>';
                            $theader .= '<th style="width:22px;">№</th>';
                            $theader .= '<th style="width:30px;">&nbsp;</th>';
                            $theader .= '<th style="width:130px;">Овог, Нэр (Код)</th>';
                            $theader .= '<th style="width:160px;">Албан тушаал</th>';
                        }
                        $employeers = $this->db->GetAll("
                            SELECT 
                                VE.EMPLOYEE_ID,
                                VE.EMPLOYEE_KEY_ID,
                                VE.LAST_NAME,
                                VE.FIRST_NAME,
                                VE.CODE,
                                VE.STATUS_NAME,
                                VE.POSITION_NAME,
                                VE.POSITION_KEY_ID,
                                VE.EMPLOYEE_PICTURE, 
                                VE.DEPARTMENT_ID, 
                                VE.DEPARTMENT_NAME,
                                TETPH.ID AS FULL_TIME_ID,
                                TETPH.FULL_TIME
                            FROM VW_EMPLOYEE VE
                            INNER JOIN HRM_EMPLOYEE_KEY ek on VE.EMPLOYEE_KEY_ID = ek.EMPLOYEE_KEY_ID
                            LEFT JOIN TNA_EMPLOYEE_TIME_PLAN_HDR TETPH ON TETPH.YEAR_ID = " . $result['YEAR_ID'] . " AND TETPH.MONTH_ID = " . $result['MONTH_ID'] . " AND TETPH.EMPLOYEE_KEY_ID = VE.EMPLOYEE_KEY_ID
                            WHERE 
                                VE.DEPARTMENT_ID = (" . $department['DEPARTMENT_ID'] . ") 
                            GROUP BY    
                                VE.EMPLOYEE_ID,
                                VE.EMPLOYEE_KEY_ID,
                                VE.LAST_NAME,
                                VE.FIRST_NAME,
                                VE.CODE,
                                VE.STATUS_NAME,
                                VE.POSITION_NAME,
                                VE.POSITION_KEY_ID,
                                VE.EMPLOYEE_PICTURE, 
                                VE.DEPARTMENT_ID, 
                                VE.DEPARTMENT_NAME,
                                TETPH.ID,
                                TETPH.FULL_TIME
                            ORDER BY VE.FIRST_NAME");
                        $autoNumber = 1;
                        $employeeCount = count($employeers);
                        $selectHoliday = "
                        SELECT 
                            START_DATE, 
                            END_DATE, 
                            HOLIDAY_NAME
                        FROM LM_HOLIDAY 
                        WHERE  END_DATE IS NOT NULL";
                        $holidays = $this->db->GetAll($selectHoliday);                        

                        foreach ($employeers as $i => $employee) {
                            if (empty($i)) {
                                $theader = '<thead>';
                                $theader .= '<tr>';
                                $theader .= '<th style="width:30px; max-width:30px;" class="number">№</th>';
                                if ($isGolomt) {
                                    $theader .= '<th style="width:200px; min-width:200px; text-align: center !important;"><span>Овог, Нэр</span></th>';
                                    $theader .= '<th style="width:100px; min-width:100px; text-align: center !important;"><span>'. $golomtView .'</span></th>';
                                } else {
                                    $theader .= '<th style="width:200px; min-width:200px;  text-align: center !important;"><span>Овог, Нэр ('. $golomtView .')</span></th>';
                                }
                                $theader .= '<th style="width:200px; min-width:200px;" class="text-center">Албан тушаал</th>';
                            }

                            $result['MONTH_CODE'] = ($result['MONTH_ID'] < 10 ? '0' . $result['MONTH_ID'] : $result['MONTH_ID']);
                            $days = self::getWorkingDateV2Model(array('planMonth'=>$result['MONTH_CODE'], 'planYear'=>$result['YEAR_ID'], 'tableName' => 'TNA_EMPLOYEE_TIME_PLAN'), $holidays);
                            $daysSizeOf = sizeof($days) + 1;

                            if ($depIndex === 1) {
                                $depIndex++;
                                $tbody .= '<tbody class="tablesorter-no-sort">';
                                    $tbody .= '<tr class="row-details" data-department="' . $department['DEPARTMENT_ID'] . '" id="EMPLOYEE_'. $employee['EMPLOYEE_ID'] . '_' . $employee['EMPLOYEE_KEY_ID'] . '_' . $department['DEPARTMENT_ID'] . '">';
                                        $tbody .= '<td class="number"> &nbsp;&nbsp; </td>';
                                        $tbody .= '<td class="padding-left-10 departmentTitle" data-colspan="1" colspan="4" style="border-right-color: transparent; word-wrap: break-word;">' . $department['DEPARTMENT_NAME'] . '</td>';
                                        $tbody .= '<td style="border-right-color: transparent;"></td>';
                                    $tbody .= '</tr>';
                                $tbody .= '</tbody>';
                            }

                            $tbody .= '<tr data-department="' . $employee['DEPARTMENT_ID'] . '" id="EMPLOYEE_'. $employee['EMPLOYEE_ID'] . '_' . $employee['EMPLOYEE_KEY_ID'] . '_' . $employee['DEPARTMENT_ID'] . '">';
                            $tbody .= '<td style="padding-left:2px; padding-right:8px;" class="text-center no-select">' . $autoNumber . '</td>';
                            $tbody .= '<td class="align-left padding-left-10 padding-right-10 no-select">';
                                $empLoyeeName = $employee['FIRST_NAME'] . '. ' . mb_substr ($employee['LAST_NAME'], 0, 2, 'UTF-8') .',';
                                $tbody .= '<input type="hidden" data-name="firstName" name="firstName[]" value="' . $employee['FIRST_NAME'] . '">';
                                $tbody .= '<input type="hidden" data-name="lastName" name="lastName[]" value="' . $employee['LAST_NAME'] . '">';
                                $tbody .= '<input type="hidden" data-name="code" name="code[]" value="' . $employee['CODE'] . '">';
                                $tbody .= '<input type="hidden" data-name="departmentId" name="departmentId[]" value="' . $employee['DEPARTMENT_ID'] . '">';
                                $tbody .= '<input type="hidden" data-name="employeeId" name="employeeId[]" value="' . $employee['EMPLOYEE_ID'] . '">';
                                $tbody .= '<input type="hidden" data-name="employeeKeyId" name="employeeKeyId[]" value="' . $employee['EMPLOYEE_KEY_ID'] . '">';
                                $tbody .= '<input type="hidden" data-name="positionKeyId" name="positionKeyId[]" value="' . $employee['POSITION_KEY_ID'] . '">';
                                $tbody .= '<input type="hidden" data-name="positionName" name="positionName[]" value="' . $employee['POSITION_NAME'] . '">';
                                $tbody .= '<input type="hidden" data-name="lastName" name="lastName[]" value="' . $employee['LAST_NAME'] . '">';
                                $tbody .= '<input type="hidden" data-name="firstName" name="firstName[]" value="' . $employee['FIRST_NAME'] . '">';
                                $tbody .= '<input type="hidden" data-name="employeePicture" name="employeePicture[]" value="' . $employee['EMPLOYEE_PICTURE'] . '">';
                                $tbody .= '<input type="hidden" data-name="employeeName" name="employeePicture[]" value="' . $employee['EMPLOYEE_PICTURE'] . '">';//'+employeeName+'
                                $tbody .= '<input type="hidden" data-name="code" name="code[]" value="' . $employee['CODE'] . '">';
                                //$tbody .= '<input type="hidden" data-name="statusName" name="statusName[]" value="' . $employee['STATUS_NAME'] . '">';
                                if ($isGolomt) {
                                        $tbody .= $empLoyeeName;
                                    $tbody .= '</td>';
                                    $tbody .= '<td class="padding-left-10 padding-right-10 no-select">' . $employee['CODE'] . '</td>';
                                } else {
                                    $tbody .= $empLoyeeName. ' <i>('. $employee['CODE'] .')<i>';
                                    $tbody .= '</td>';
                                }
                            $tbody .= '<td class="padding-left-10 padding-right-10 no-select">' . $employee['POSITION_NAME'] . '</td>';

                            $totalAmountPlanTime = 0;     
                            foreach ($days as $key => $day) {

                                if (empty($i)) {
                                    $theader .= '<th rowspan="2" data-isworking="' . $day['SPELL_DAY'] . '" class="tbl-cell ' . $day['DAY_CLASS'] . '" style="width: 22px; font-weight: 500; min-width: 22px;">';
                                    $theader .= '<div class="dayName">' . $day['DAY'] . '</div>';
                                    $theader .= '<div class="dayName">' . $day['SPELL_DAY_SHORT_NAME'] . '</div>';
                                    $theader .= '</th>';
                                }

                                if ($day['PLAN_DATE'] != '') {
                                    $dayPlanTime = ($day['PLAN_TIME'] > 0) ? $day['PLAN_TIME'] : '';
                                    $dayPlanColor = ($isHishigArvin) ? $day['COLOR'] : '';
                                    $dayPlanTime = ($isHishigArvin) ? ($day['SHORT_NAME'] ? $day['SHORT_NAME'] : $dayPlanTime) : $dayPlanTime;

                                    $tbody .= '<td title="' . $day['HOLIDAY'] . ' ' . $day['STATUS_DESCRIPTION'] . '" style="cursor: context-menu; background-color:' . (($dayPlanColor) ? $dayPlanColor : ((strtolower($day['DAY_COLOR']) !== '') ? $day['DAY_COLOR'] : 'transparent' )) .'; text-align:center; ' . ($day['IS_LOCK'] == '1' ? 'background-image : url(\'assets/core/global/img/cell-status.png\'); background-repeat: no-repeat; background-position: bottom right;' : '') . ' "  data-isworking="' .$day['SPELL_DAY'] . '" class="tbl-cell  ' . $day['DAY_CLASS'] . '">';
                                    $tbody .= '<input type="hidden" data-name="tnaEmployeeTimePlanId" name="tnaEmployeeTimePlanId[' . $employee['EMPLOYEE_ID'] .'][]" value="' . (isset($day['ID']) ? $day['ID'] : '') . '">';
                                    $tbody .= '<input type="hidden" data-name="planDate" name="planDate[' . $employee['EMPLOYEE_ID'] .'][]" value="' . $day['PLAN_DATE'].'">';
                                    $tbody .= '<input type="hidden" data-name="planId" name="planId[' . $employee['EMPLOYEE_ID'] .'][]" value="' . $day['PLAN_ID'].'">';
                                    $tbody .= '<input type="hidden" data-name="wfmStatusId" name="wfmStatusId[' . $employee['EMPLOYEE_ID'] .'][]" value="' . $day['WFM_STATUS_ID'].'">';
                                    $tbody .= '<input type="hidden" data-name="day" name="day[' . $employee['EMPLOYEE_ID'] .'][]" value="' . $day['DAY'].'">';
                                    $tbody .= '<input type="hidden" data-name="wfmStatusId" name="wfmStatusId[' . $employee['EMPLOYEE_ID'] .'][]" value="' . $day['WFM_STATUS_ID'].'">';
                                    $tbody .= '<input type="hidden" data-name="wfmStatusCode" name="wfmStatusCode[' . $employee['EMPLOYEE_ID'] .'][]" value="' . $day['WFM_STATUS_CODE'].'">';
                                    $tbody .= '<input type="hidden" data-name="approveLastDate" name="approveLastDate[' . $employee['EMPLOYEE_ID'] .'][]" value="' . $day['APPROVE_LAST_DATE'].'">';
                                    $tbody .= '<input type="hidden" data-name="isLock" name="isLock[' . $employee['EMPLOYEE_ID'] .'][]" value="' . $day['IS_LOCK'].'">';
                                    $tbody .= '<input type="hidden" data-name="lockEndDate" name="lockEndDate[' . $employee['EMPLOYEE_ID'] .'][]" value="' . $day['LOCK_END_DATE'].'">';
                                    $tbody .= '<input type="hidden" data-name="lockUserId" name="lockUserId[' . $employee['EMPLOYEE_ID'] .'][]" value="' . $day['LOCK_USER_ID'].'">';
                                    $tbody .= '<input type="hidden" data-name="planTime" name="planTime[' . $employee['EMPLOYEE_ID'] .'][]" value="' . $day['PLAN_TIME'].'">';
                                    $tbody .= '<input type="hidden" data-name="isSelectedCell" name="isSelectedCell[' . $employee['EMPLOYEE_ID'] .'][]" value="0">';
                                    $tbody .= $dayPlanTime;
                                    $tbody .= '</td>';        
                                    $totalAmountPlanTime += ($day['PLAN_TIME'] > 0) ? $day['PLAN_TIME'] : '0';
                                }
                            }
                            $tbody .= '<td class="no-select" style="padding: 0 !important; background-color: #EEE;">';
                                $tbody .= '<input type="text" class="form-control fullTime text-center" readonly ="readonly" maxlength="3" name="fullTime[]" data-name="fullTime" value="' . $totalAmountPlanTime . '" title="' . $totalAmountPlanTime . '" onchange="checkFullTime(this);">';
                                $tbody .= '<input type="hidden" data-name="fullTimeId" name="fullTimeId[]" value="' . $employee['FULL_TIME_ID'] . '">';
                            $tbody .= '</td>';
                            $tbody .= '</tr>';
                            $autoNumber++;
                            if (empty($i)) {
                                $theader .= '<th style="width:30px; max-width:30px;" rowspan="2" class="cursorPointer">Х/Цаг<div><i class="fa fa-save" onclick="saveFullTime(this);"></i></div></th>';
                                $theader .= '</tr>';
                                $theader .= '<tr class="bp-filter-row">';
                                    $theader .= '<th class="rowNumber" style="background-color: rgb(231, 231, 231); position: relative; z-index: 10; background-clip: padding-box; top: -1px; left: 0px;"></th>';

                                    if ($isGolomt) {
                                        $theader .= '<th style="background-color: rgb(231, 231, 231); position: relative; z-index: 10; background-clip: padding-box; top: -1px; left: 0px;"><input type="text" data-fieldname="employeename" data-condition="like"></th>';
                                        $theader .= '<th style="background-color: rgb(231, 231, 231); position: relative; z-index: 10; background-clip: padding-box; top: -1px; left: 0px;"><input type="text" data-fieldname="employeedomain" data-condition="like"></th>';
                                    } else {
                                        $theader .= '<th style="background-color: rgb(231, 231, 231); position: relative; z-index: 10; background-clip: padding-box; top: -1px; left: 0px;"><input type="text" data-fieldname="employeename" data-condition="like"></th>';
                                    }
                                    $theader .= '<th style="background-color: rgb(231, 231, 231); position: relative; z-index: 10; background-clip: padding-box; top: -1px; left: 0px;"><input type="text" data-fieldname="employeeposition" data-condition="like"></th>';
                                $theader .= '</tr>';
                                $theader .= '</thead>';
                            }
                        }
                    }

                    $result = ' <input type="hidden" id="tnatimePlanTotalCount" value="'. $employeeCount .'">
                                    <input type="hidden" id="tnatimePlanPage" value="1">
                                    <input type="hidden" id="tnatimePlanIsArchive" value="1">
                                    <input type="hidden" id="srch_yearCode" value="'. $result['YEAR_ID'] .'">
                                    <input type="hidden" id="srch_monthCode" value="'. $result['MONTH_ID'] .'">
                                    <div class="pf-custom-pager ">
                                        <div id="fz-parent" class="freeze-overflow-xy-auto">
                                            <table class="table table-condensed table-bordered table-hover gl-table-dtl bprocess-theme1" id="assetDtls">
                                                '. $theader .'
                                                <tbody>'. $tbody .'</tbody>
                                                <tfoot>
                                                    '. $tfooter .'
                                                </tfoot>
                                            </table>
                                        </div>
                                        <div class="pf-custom-pager-tool hidden">
                                            <div class="pf-custom-pager-buttons">
                                                <a href="javascript:;" class="pf-custom-pager-last-prev pf-custom-pager-disabled">
                                                    <span></span>
                                                </a>
                                                <a href="javascript:;" class="pf-custom-pager-prev pf-custom-pager-disabled">
                                                    <span></span>
                                                </a>
                                                <div class="pf-custom-pager-separator"></div>
                                                <div class="pf-custom-pager-page-info">Хуудас <span><input type="text" size="2" value="1" data-gotopage="1" class="integerInit"></span> of <span data-pagenumber="1">0</span></div>	
                                                <div class="pf-custom-pager-separator"></div>
                                                <a href="javascript:;" class="pf-custom-pager-next pf-custom-pager-disabled">
                                                    <span></span>
                                                </a>
                                                <a href="javascript:;" class="pf-custom-pager-last-next pf-custom-pager-disabled">
                                                    <span></span>
                                                </a>
                                                <div class="pf-custom-pager-separator"></div>
                                                <a href="javascript:;" class="pf-custom-pager-refresh pf-custom-pager-disabled">
                                                    <span></span>
                                                </a>
                                            </div>
                                            <div class="pf-custom-pager-total">Нийт <span>0</span> байна.</div>
                                        </div>
                                    </div>';
                    
                    return array('status'=>'success', 'Html' => $result);
                }
            }
        } else {
            return array('status'=>'error', 'message' => 'Архивласан бичлэг байхгүй');
        }
    }

    public function getArchivPlanListMainDataGrid2Model() {
        $result = $footerArr = $newBody = array();
        $whereString = $theader = $tbody = $tfooter = '';
        $isHishigArvin = Config::getFromCache('CONFIG_TNA_HISHIGARVIN');
        $filterRuleString = '';
        $logId = Input::post('planLogId');

        $getLogInfo = $this->db->GetRow("
        SELECT
            YEAR_ID,
            MONTH_ID,
            DESCRIPTION
        FROM 
            TMS_EMPLOYEE_PLAN_LOG
        WHERE 
            ID = " . $logId);

        parse_str(Input::post('params'), $params);

        $isGolomt = issetVar($params['golomtView']);
        $golomtView = isset($params['golomtView']) ? (($params['golomtView']) ? 'Домайн' : 'Код') : 'Код';
        if (!empty($getLogInfo['YEAR_ID']) && !empty($getLogInfo['MONTH_ID'])) {

            $params['planMonth'] = $getLogInfo['MONTH_ID'];
            $params['planYear'] = $getLogInfo['YEAR_ID'];
            $params['departmentId'] = Input::post('departmentId');
            $params['isChild'] = Input::post('isChild');
            $employeers = array();
            $headerDays = array();
            $currentStatusNotIn = Config::getFromCache('tmsCurrentStatus');
            $statusNotIn = Config::getFromCache('tmsStatus');

            if (!empty($params['departmentId']) || (isset($params['groupId']) && is_array($params['groupId']))) {

                $bookTypeIds = '9024,9025,9026,9048';

                if ($params['departmentId']) {
                    $departmentIds = $params['departmentId'];
                    $departmentIds = rtrim($departmentIds, ',');
                    $isChild = issetVar($params['isChild']);

                    if ($departmentIds) {
                        $departmentIds = $this->getAllChildDepartmentModel($departmentIds, $isChild);

                        $whereString .= " AND VE.DEPARTMENT_ID IN ( " . $departmentIds . ") ";
                    }

                } elseif ($params['departmentId'] && Config::getFromCache('tmsCustomerCode') == 'gov') {                            
                    $isChild = issetVar($params['isChild']);

                    $departmentIds = $this->getAllChildDepartmentModel($params['departmentId'], $isChild);

                    $whereString .= " AND VE.DEPARTMENT_ID IN ( " . $departmentIds . ") ";                            
                } elseif (isset($params['groupId']) && is_array($params['groupId'])) {
                    $whereString .= " AND VE.EMPLOYEE_ID IN ( SELECT EMPLOYEE_ID FROM TNA_EMPLOYEE_GROUP_CONFIG WHERE GROUP_ID IN (" . implode(',', $params['groupId']) . ")) ";

                }

                $params['departmentId'] = !empty($departmentIds) ? explode(',', $departmentIds) : '';

                $paramDepartmentIds = $params['departmentId'];
                $userId = Ue::sessionUserId();

                (Array) $departmentId = array();

                if (isset($params['positionId']) && is_array($params['positionId'])) {
                    $whereString .= " AND VE.POSITION_ID IN (" . implode(',', $params['positionId']) . ")";
                }

                if (isset($params['positionGroupId']) && $params['positionGroupId'] != '') {
                    $whereString .= " AND VE.POSITION_GROUP_ID = " . $params['positionGroupId'];
                }

                if (isset($params['employeeStatus'])) {
                    if (empty($params['employeeStatus']) === false) {
                        $employeeStatusId = implode(',', $params['employeeStatus']);
                        $whereString .= " AND ek.STATUS_ID IN (". $employeeStatusId .")";
                    }
                }

                if (isset($params['stringValue'])) {
                    if (empty($params['stringValue']) === false && $params['stringValue'] != '') {

                        if(strpos($params['stringValue'], '.') === false) {
                            $whereString .= " AND (LOWER(LAST_NAME) LIKE LOWER('%" . $params['stringValue'] . "%') OR LOWER(FIRST_NAME) LIKE LOWER('%" . $params['stringValue'] . "%') OR LOWER(CODE) LIKE LOWER('%" . $params['stringValue'] . "%') OR LOWER(REGISTER_NUMBER) LIKE LOWER('%" . $params['stringValue'] . "%')) ";
                        } else {
                            $strexplode = explode('.', $params['stringValue']);
                            $whereString .= " AND (LOWER(LAST_NAME) LIKE LOWER('%" . $strexplode[0] . "%') AND LOWER(FIRST_NAME) LIKE LOWER('%" . $strexplode[1] . "%')) ";
                        }
                    }
                }

                if (Input::postCheck('filterRules')) {
                    $filterRules = json_decode(Str::cp1251_utf8($_POST['filterRules']), true);                
                    if ($filterRules) {
                        foreach ($filterRules as $rule) {
                            $field = $rule['field'];
                            $value = Input::param(Str::lower($rule['value']));
                            if (!empty($value)) {
                                if ($field === 'employeename') {
                                    $whereString .= " AND (LOWER(VE.FIRST_NAME) LIKE '%$value%')";
                                } elseif ($field === 'employeeposition') {
                                    $whereString .= " AND (LOWER(VE.POSITION_NAME) LIKE '%$value%')";
                                }
                            }
                        }
                    }
                }                        

                $causeString1 = '';
                $leftJoin = '';

                (String) $tableColumn1 = $tableColumn2 = $tableColumn3 = '';

                $resultCountt = $this->db->GetOne("SELECT COUNT(ID) AS COUNTT FROM TNA_EMPLOYEE_PLAN_OWNER WHERE USER_ID = $userId");

                if ((int) $resultCountt != 0) {
                    $resultDepartment = $this->db->GetAll("SELECT DISTINCT DEPARTMENT_ID FROM TNA_EMPLOYEE_PLAN_OWNER WHERE USER_ID = $userId");
                    foreach ($resultDepartment as $key => $deparment) {
                        if (!in_array($deparment['DEPARTMENT_ID'], $departmentId)) {
                            array_push($departmentId, $deparment['DEPARTMENT_ID']);
                        }
                    }
                    $response = array('CHECK' => '1', 'departmentIds' => $departmentId);
                }

                (Array) $departmentIdArr = array();

                if (sizeof($departmentId) > 0) {
                    foreach ($paramDepartmentIds as $depart) {
                        if (in_array($depart, $departmentId)) {
                            array_push($departmentIdArr, $depart);
                        }
                    }
                } else {
                    $departmentIdArr = $paramDepartmentIds;
                }

                $departmentIds = implode(',', $departmentIdArr);
                if((isset($params['groupId']) && is_array($params['groupId']))) {
                    $causeString1 .= " AND VE.EMPLOYEE_ID IN (SELECT EMPLOYEE_ID FROM TNA_EMPLOYEE_GROUP_CONFIG WHERE GROUP_ID IN (" . implode(',', $params['groupId']) . "))";
                }
                $departmentList = $this->db->GetAll("SELECT DEPARTMENT_ID, DEPARTMENT_NAME FROM ORG_DEPARTMENT WHERE DEPARTMENT_ID IN (" . $departmentIds . ") ORDER BY LPAD(DISPLAY_ORDER, 10) ASC");

                    $leftJoinDay = $leftJoinAttr = $groupBy = '';
                        $days = cal_days_in_month(CAL_GREGORIAN, intval($params['planMonth']), intval($params['planYear'])); // 31, 30, 29, 28
                        for ($iday = 1; $iday <= $days; $iday ++ ) {
                            $dayAddin = $iday;

                            if($iday < 10)
                                $rday = '0'.$iday;
                            else
                                $rday = $iday;

                            $leftJoinAttr .= 
                                    "
                                    ROUND(tem$iday.PLAN_TIME/60, 2) AS PLAN_TIME_$iday, 
                                    tem$iday.PLAN_ID AS PLAN_ID_$iday,
                                    tem$iday.SHORT_NAME AS SHORT_NAME_$iday,
                                    tem$iday.COLOR AS COLOR_$iday,
                                ";

                            $leftJoinDay .= " LEFT JOIN (
                                                SELECT
                                                    pl.PLAN_ID,
                                                    pl.COLOR, 
                                                    pl.SHORT_NAME,
                                                    FNC_GET_TMS_PLAN_TIME(pl.PLAN_ID) PLAN_TIME
                                                FROM TMS_TIME_PLAN pl
                                            ) tem$iday ON tem$iday.PLAN_ID = TETPH.D$dayAddin ";
                        }

                        $monthLimit = Config::getFromCache('tmsMonthFilter') || Config::getFromCache('tmsMonthFilter') == '0' ? Config::getFromCache('tmsMonthFilter') : '30';
                        $tmsPlanPreview = "((TO_CHAR(WORK_END_DATE, 'YYYY-MM') >= '" . $params['planYear'] ."-". $params['planMonth'] . "') OR WORK_END_DATE IS NULL)";

                        $tmsCustomerCode = Config::getFromCache('tmsCustomerCode');

                        $tmsPlanPreview .= '  OR (  TO_CHAR(WORK_END_DATE,\'YYYY-MM\') >= \'' . $params['planYear'] .'-'. $params['planMonth'] . '\' AND  WORK_END_DATE IS NOT NULL)';

                        if (Config::getFromCache('tmsPlanPreview') == '1') {
                            $tmsPlanPreview = "((TO_CHAR(WORK_END_DATE + $monthLimit, 'YYYY-MM') >= '" . $params['planYear'] ."-". $params['planMonth'] . "') OR WORK_END_DATE IS NULL)";
                        }

                        if ($tmsCustomerCode === 'khaanbank') {
                            $tmsPlanPreview .= '  OR (  TO_CHAR(WORK_END_DATE,\'YYYY-MM\') >= \'' . $params['planYear'] .'-'. $params['planMonth'] . '\' AND  WORK_END_DATE IS NOT NULL)';
                        }                 

                        (String) $tableColumn2 = '';
                        (String) $orderColumn = '';

                        if ($tmsCustomerCode == 'gov') {
                            $tableColumn2 = ", VE.DEP_ORDER, VE.POS_ORDER, VE.WORK_START_DATE ";
                            $orderColumn = "ORDER BY LPAD(DEP_ORDER, 10), LPAD(POS_ORDER, 10), WORK_START_DATE ASC";
                        }   


                        $autoNumber = 1;
                        $employeeCount = $this->db->GetOne("SELECT COUNT(tem.EMPLOYEE_ID) FROM (
                                                            SELECT 
                                                                VE.EMPLOYEE_ID,
                                                                VE.EMPLOYEE_KEY_ID,
                                                                VE.LAST_NAME,
                                                                VE.FIRST_NAME,
                                                                VE.CODE,
                                                                VE.STATUS_NAME,
                                                                VE.POSITION_NAME,
                                                                VE.POSITION_KEY_ID,
                                                                VE.EMPLOYEE_PICTURE, 
                                                                VE.DEPARTMENT_ID, 
                                                                VE.DEPARTMENT_NAME,
                                                                TETPH.ID AS FULL_TIME_ID,
                                                                TETPH.D1,TETPH.D2,TETPH.D3,TETPH.D4,TETPH.D5,TETPH.D6,TETPH.D7,TETPH.D8,TETPH.D9,TETPH.D10,
                                                                TETPH.D11,TETPH.D12,TETPH.D13,TETPH.D14,TETPH.D15,TETPH.D16,TETPH.D17,TETPH.D18,TETPH.D19,TETPH.D20,
                                                                TETPH.D21,TETPH.D22,TETPH.D23,TETPH.D24,TETPH.D25,TETPH.D26,TETPH.D27,TETPH.D28,TETPH.D29,TETPH.D30,
                                                                TETPH.D31, ek.STATUS_ID, ek.CURRENT_STATUS_ID $tableColumn2
                                                            FROM VW_TMS_EMPLOYEE VE
                                                            INNER JOIN ( 
                                                                SELECT
                                                                MAX(EMPLOYEE_KEY_ID) AS EMPLOYEE_KEY_ID
                                                                FROM
                                                                HRM_EMPLOYEE_KEY
                                                                WHERE ( 
                                                                        ".$tmsPlanPreview."
                                                                    )
                                                                GROUP BY
                                                                    EMPLOYEE_ID
                                                            ) ACTIVE_HEK ON ACTIVE_HEK.EMPLOYEE_KEY_ID = VE.EMPLOYEE_KEY_ID       
                                                            LEFT JOIN ( 
                                                                SELECT
                                                                BB.NEW_EMPLOYEE_KEY_ID AS EMPLOYEE_KEY_ID,
                                                                '0' AS LIMITLESS
                                                                FROM HCM_LABOUR_BOOK AA
                                                                INNER JOIN HCM_LABOUR_BOOK_DTL BB ON AA.ID = BB.BOOK_ID
                                                                INNER JOIN HRM_EMPLOYEE_KEY LK ON LK.EMPLOYEE_KEY_ID = BB.NEW_EMPLOYEE_KEY_ID
                                                                WHERE AA.BOOK_TYPE_ID IN ($bookTypeIds) AND LK.CURRENT_STATUS_ID NOT IN (1, 3) 
                                                                UNION ALL
                                                                SELECT
                                                                BB.NEW_EMPLOYEE_KEY_ID AS EMPLOYEE_KEY_ID,
                                                                '1' AS LIMITLESS
                                                                FROM HCM_LABOUR_BOOK AA
                                                                INNER JOIN HCM_LABOUR_BOOK_DTL BB ON AA.ID = BB.BOOK_ID
                                                                INNER JOIN HRM_EMPLOYEE_KEY LK ON LK.EMPLOYEE_KEY_ID = BB.NEW_EMPLOYEE_KEY_ID
                                                                WHERE AA.BOOK_TYPE_ID IN ($bookTypeIds) AND TO_CHAR(BB.START_DATE + $monthLimit, 'YYYY-MM') >= '" . $params['planYear'] ."-". $params['planMonth'] . "' AND TO_CHAR(BB.START_DATE - $monthLimit, 'YYYY-MM') <= '" . $params['planYear'] ."-". $params['planMonth'] . "' AND LK.CURRENT_STATUS_ID NOT IN (1, 3)
                                                            ) LAB_HEK ON LAB_HEK.EMPLOYEE_KEY_ID = ACTIVE_HEK.EMPLOYEE_KEY_ID                                                                                                                                     
                                                            INNER JOIN HRM_EMPLOYEE_KEY ek ON ACTIVE_HEK.EMPLOYEE_KEY_ID = ek.EMPLOYEE_KEY_ID
                                                            INNER JOIN TMS_EMPLOYEE_PLAN_LOG_HDR TETPH ON TETPH.EMPLOYEE_ID = VE.EMPLOYEE_ID
                                                            $leftJoin
                                                            WHERE TETPH.LOG_ID = $logId
                                                            ) tem");

                        $queryString = "SELECT * FROM (
                            SELECT 
                                VE.EMPLOYEE_ID,
                                VE.EMPLOYEE_KEY_ID,
                                VE.LAST_NAME,
                                VE.FIRST_NAME,
                                VE.CODE,
                                VE.STATUS_NAME,
                                VE.POSITION_NAME,
                                VE.POSITION_KEY_ID,
                                VE.EMPLOYEE_PICTURE, 
                                VE.DEPARTMENT_ID, 
                                VE.DEPARTMENT_NAME,
                                VE.REGISTER_NUMBER,
                                TETPH.ID AS FULL_TIME_ID,
                                TETPH.D1,TETPH.D2,TETPH.D3,TETPH.D4,TETPH.D5,TETPH.D6,TETPH.D7,TETPH.D8,TETPH.D9,TETPH.D10,
                                TETPH.D11,TETPH.D12,TETPH.D13,TETPH.D14,TETPH.D15,TETPH.D16,TETPH.D17,TETPH.D18,TETPH.D19,TETPH.D20,
                                TETPH.D21,TETPH.D22,TETPH.D23,TETPH.D24,TETPH.D25,TETPH.D26,TETPH.D27,TETPH.D28,TETPH.D29,TETPH.D30,
                                $leftJoinAttr
                                TETPH.D31, ek.STATUS_ID, ek.CURRENT_STATUS_ID,
                                ". $this->db->IfNull('TETPH.ID', 'null') ." AS IS_EXIST $tableColumn2
                            FROM VW_TMS_EMPLOYEE VE
                            INNER JOIN ( 
                                SELECT
                                MAX(EMPLOYEE_KEY_ID) AS EMPLOYEE_KEY_ID
                                FROM
                                HRM_EMPLOYEE_KEY
                                WHERE (
                                        ".$tmsPlanPreview."
                                        )
                                GROUP BY
                                    EMPLOYEE_ID
                            ) ACTIVE_HEK ON ACTIVE_HEK.EMPLOYEE_KEY_ID = VE.EMPLOYEE_KEY_ID                     
                            LEFT JOIN ( 
                                SELECT
                                BB.NEW_EMPLOYEE_KEY_ID AS EMPLOYEE_KEY_ID,
                                '0' AS LIMITLESS
                                FROM HCM_LABOUR_BOOK AA
                                INNER JOIN HCM_LABOUR_BOOK_DTL BB ON AA.ID = BB.BOOK_ID
                                INNER JOIN HRM_EMPLOYEE_KEY LK ON LK.EMPLOYEE_KEY_ID = BB.NEW_EMPLOYEE_KEY_ID
                                WHERE AA.BOOK_TYPE_ID IN ($bookTypeIds) AND LK.CURRENT_STATUS_ID NOT IN (1, 3)
                                UNION ALL
                                SELECT
                                BB.NEW_EMPLOYEE_KEY_ID AS EMPLOYEE_KEY_ID,
                                '1' AS LIMITLESS
                                FROM HCM_LABOUR_BOOK AA
                                INNER JOIN HCM_LABOUR_BOOK_DTL BB ON AA.ID = BB.BOOK_ID
                                INNER JOIN HRM_EMPLOYEE_KEY LK ON LK.EMPLOYEE_KEY_ID = BB.NEW_EMPLOYEE_KEY_ID
                                WHERE AA.BOOK_TYPE_ID IN ($bookTypeIds) AND TO_CHAR(BB.START_DATE + $monthLimit, 'YYYY-MM') >= '" . $params['planYear'] ."-". $params['planMonth'] . "' AND TO_CHAR(BB.START_DATE - $monthLimit, 'YYYY-MM') <= '" . $params['planYear'] ."-". $params['planMonth'] . "' AND LK.CURRENT_STATUS_ID NOT IN (1, 3)
                            ) LAB_HEK ON LAB_HEK.EMPLOYEE_KEY_ID = ACTIVE_HEK.EMPLOYEE_KEY_ID                                                                                      
                            INNER JOIN HRM_EMPLOYEE_KEY ek ON ACTIVE_HEK.EMPLOYEE_KEY_ID = ek.EMPLOYEE_KEY_ID
                            INNER JOIN TMS_EMPLOYEE_PLAN_LOG_HDR TETPH ON TETPH.EMPLOYEE_ID = VE.EMPLOYEE_ID
                            $leftJoin
                            $leftJoinDay
                            WHERE TETPH.LOG_ID = $logId
                            ORDER BY ".($tableColumn2 ? ltrim($tableColumn2, ',') : 'VE.DEPARTMENT_NAME, VE.FIRST_NAME, VE.POSITION_NAME')." ASC ) TEMP $orderColumn";

                        $page = Input::postCheck('page') ? Input::post('page') : 1;
                        $rows = Input::postCheck('rows') ? Input::post('rows') : (Config::getFromCache('tmsPageNumber') ? Config::getFromCache('tmsPageNumber') : 50);
                        $offset = ($page - 1) * $rows;                                

                        // echo $queryString; die;
                        $employeers = $this->db->SelectLimit($queryString, $rows, $offset);
                        $employeers = isset($employeers->_array) ? $employeers->_array : array();
                        //pa($employeers);

                        if ($employeeCount > 0) {
                            $selectHoliday = "
                            SELECT 
                                START_DATE, 
                                END_DATE, 
                                HOLIDAY_NAME
                            FROM LM_HOLIDAY 
                            WHERE  END_DATE IS NOT NULL";
                            $holidays = $this->db->GetAll($selectHoliday);

                            foreach ($departmentList as $k => $department) {
                                $depIndex = 1;
                                $planDtlGroup = array();

                                $days = self::getWorkingDateV2Model(array('planMonth'=>$params['planMonth'], 'planYear'=>$params['planYear'], 'tableName' => 'TNA_EMPLOYEE_TIME_PLAN'), $holidays);

                                foreach ($employeers as $i => $employee) {
                                    if ($department['DEPARTMENT_ID'] === $employee['DEPARTMENT_ID']) {

                                        if (empty($i)) {
                                            $theader = '<thead>';
                                            $theader .= '<tr>';
                                            $theader .= '<th style="width:10px; text-align: center" class="number rowNumber">№</th>';
                                            if ($isGolomt) {
                                                $theader .= '<th style="width:200px; min-width:200px; text-align: center !important;"><span>Овог, Нэр</span></th>';
                                                $theader .= '<th style="width:100px; min-width:100px; text-align: center !important;"><span>'. $golomtView .'</span></th>';
                                            } else {
                                                $theader .= '<th style="width:200px; min-width:200px;  text-align: center !important;"><span>Овог, Нэр ('. $golomtView .' - РД)</span></th>';
                                            }
                                            $theader .= '<th style="width:200px; min-width:200px;" class="text-center">Албан тушаал</th>';
                                        }

                                        if ($depIndex === 1) {
                                            $depIndex++;
                                            $tbody .= '<tbody class="tablesorter-no-sort">';
                                                $tbody .= '<tr class="row-details" data-department="' . $department['DEPARTMENT_ID'] . '" id="EMPLOYEE_'. $employee['EMPLOYEE_ID'] . '_' . $employee['EMPLOYEE_KEY_ID'] . '_' . $department['DEPARTMENT_ID'] . '">';
                                                    $tbody .= '<td class="number"> &nbsp;&nbsp; </td>';
                                                    $tbody .= '<td class="pl10 departmentTitle" data-colspan="1" colspan="20" style="border-right-color: transparent; word-wrap: break-word;">' . $department['DEPARTMENT_NAME'] . '</td>';
                                                    $tbody .= '<td style="border-right-color: transparent;"></td>';
                                                $tbody .= '</tr>';
                                            $tbody .= '</tbody>';
                                        }

                                        $tbody .= '<tr data-department="' . $employee['DEPARTMENT_ID'] . '" id="EMPLOYEE_'. $employee['EMPLOYEE_ID'] . '_' . $employee['EMPLOYEE_KEY_ID'] . '_' . $employee['DEPARTMENT_ID'] . '">';
                                        $tbody .= '<td style="padding-left:2px; padding-right:8px; line-height: 13px; vertical-align: middle;" class="text-center no-select">' . $autoNumber . '</td>';
                                        $tbody .= '<td class="align-left pl10 pr10 no-select" style="line-height: 13px; vertical-align: middle;" title="'.$employee['LAST_NAME'].'.'.$employee['FIRST_NAME'].'">';

                                            $empLoyeeName = mb_substr ($employee['LAST_NAME'], 0, 2, 'UTF-8').'.'.$employee['FIRST_NAME'].' ';
                                            if ($tmsCustomerCode == 'gov') {
                                                $empLoyeeName = mb_substr ($employee['LAST_NAME'], 0, 1, 'UTF-8').'.'.$employee['FIRST_NAME'].' ';
                                            }

                                            $tbody .= '<input type="hidden" data-name="firstName" name="firstName[]" value="' . $employee['FIRST_NAME'] . '">';
                                            $tbody .= '<input type="hidden" data-name="lastName" name="lastName[]" value="' . $employee['LAST_NAME'] . '">';
                                            $tbody .= '<input type="hidden" data-name="departmentId" name="departmentId[]" value="' . $employee['DEPARTMENT_ID'] . '">';
                                            $tbody .= '<input type="hidden" data-name="employeeId" name="employeeId[]" value="' . $employee['EMPLOYEE_ID'] . '">';
                                            $tbody .= '<input type="hidden" data-name="employeeKeyId" name="employeeKeyId[]" value="' . $employee['EMPLOYEE_KEY_ID'] . '">';
                                            $tbody .= '<input type="hidden" data-name="positionKeyId" name="positionKeyId[]" value="' . $employee['POSITION_KEY_ID'] . '">';
                                            $tbody .= '<input type="hidden" data-name="positionName" name="positionName[]" value="' . $employee['POSITION_NAME'] . '">';
                                            $tbody .= '<input type="hidden" data-name="code" name="code[]" value="' . $employee['CODE'] . '">';
                                            $tbody .= '<input type="hidden" data-name="fullTimeId" name="fullTimeId[]" value="' . $employee['FULL_TIME_ID'] . '">';
                                            $tbody .= '<input type="hidden" data-name="isExist" name="isExist[]" value="' . $employee['IS_EXIST'] . '">';

                                            if ($isGolomt) {
                                                    $tbody .= $empLoyeeName;
                                                $tbody .= '</td>';
                                                $tbody .= '<td class="pl10 pr10 no-select" style="line-height: 13px; vertical-align: middle;">' . $employee['CODE'] . '</td>';
                                            } else {
                                                if ($tmsCustomerCode === 'khaanbank' || $tmsCustomerCode === 'skyresort') {
                                                    $tbody .= $empLoyeeName. ' <i>('. $employee['CODE'] .')<i>';
                                                } else {
                                                    $tbody .= $empLoyeeName. ' <i>('. $employee['CODE'] . ' - ' . $employee['REGISTER_NUMBER'] .')<i>';
                                                }
                                                $tbody .= '</td>';
                                            }

                                        $tbody .= '<td class="pl10 pr10 no-select" style="line-height: 13px; vertical-align: middle;">' . $employee['POSITION_NAME'] . '</td>';

                                        $totalAmountPlanTime = 0;

                                        $iday = 1;
                                        foreach ($days as $key => $day) {
                                            $dayAddin = $iday;

                                            if (empty($i)) {
                                                $theader .= '<th rowspan="2" data-isworking="' . $day['SPELL_DAY'] . '" class="tbl-cell ' . $day['DAY_CLASS'] . '" style="width: 22px; font-weight: 500; min-width: 22px; line-height: 13px; vertical-align: middle">';
                                                $theader .= '<div class="dayName">' . $day['DAY'] . '</div>';
                                                $theader .= '<div class="dayName">' . $day['SPELL_DAY_SHORT_NAME'] . '</div>';
                                                $theader .= '</th>';
                                            }

                                            if ($employee['D' . $dayAddin] !== '') {

                                                $dayPlanTime = ($employee['PLAN_TIME_'. $iday] > 0) ? $employee['PLAN_TIME_'. $iday] : '';
                                                //$dayPlanColor = ($isHishigArvin) ? ($employee['COLOR_'. $iday]) ? $employee['COLOR_'. $iday] : '' : '';
                                                $dayPlanColor = $employee['COLOR_'. $iday] ? $employee['COLOR_'. $iday] : '';
                                                $dayPlanTime = $employee['SHORT_NAME_'. $iday] ? $employee['SHORT_NAME_'. $iday] : $dayPlanTime;

                                                $tbody .= '<td title="' . $day['HOLIDAY'] . ' ' . $day['STATUS_DESCRIPTION'] . '" style="cursor: context-menu; background-color:' . (($dayPlanColor) ? $dayPlanColor : ((strtolower($day['DAY_COLOR']) !== '') ? $day['DAY_COLOR'] : 'transparent' )) .'; text-align:center; vertical-align: middle; ' . ($day['IS_LOCK'] == '1' ? 'background-image : url(\'assets/core/global/img/cell-status.png\'); background-repeat: no-repeat; background-position: bottom right;' : '') . ' "  data-isworking="' .$day['SPELL_DAY'] . '" class="tbl-cell  ' . $day['DAY_CLASS'] . '">';
                                                $tbody .= '<input type="hidden" data-name="tnaEmployeeTimePlanId" name="tnaEmployeeTimePlanId[' . $employee['EMPLOYEE_ID'] .'][]" value="' . (isset($day['ID']) ? $day['ID'] : '') . '">';
                                                $tbody .= '<input type="hidden" data-name="planDate" name="planDate[' . $employee['EMPLOYEE_ID'] .'][]" value="' . $day['PLAN_DATE'].'">';
                                                $tbody .= '<input type="hidden" data-name="planCode" name="planCode[' . $employee['EMPLOYEE_ID'] .'][]" value="D'. $dayAddin. '">';
                                                $tbody .= '<input type="hidden" data-name="planId" name="planId[' . $employee['EMPLOYEE_ID'] .'][]" value="' . $employee['PLAN_ID_'. $iday].'">';
                                                $tbody .= '<input type="hidden" data-name="planTime" name="planTime[' . $employee['EMPLOYEE_ID'] .'][]" value="' . $employee['PLAN_TIME_'. $iday].'">';
                                                $tbody .= '<input type="hidden" data-name="isSelectedCell" name="isSelectedCell[' . $employee['EMPLOYEE_ID'] .'][]" value="0">';
                                                    $tbody .= $dayPlanTime;
                                                $tbody .= '</td>';        
                                                $totalAmountPlanTime += ($employee['PLAN_TIME_'. $iday] > 0) ? $employee['PLAN_TIME_'. $iday] : '0';
                                            }  else {
                                                $tbody .= '<td>';
                                                $tbody .= '</td>';
                                            }

                                            $iday++;
                                        }

                                        $tbody .= '<td class="no-select" style="padding: 0 !important; background-color: #EEE; line-height: 13px; vertical-align: middle">';
                                            $tbody .= '<input type="text" class="form-control fullTime text-center" readonly ="readonly" maxlength="3" name="fullTime[]" data-name="fullTime" value="' . $totalAmountPlanTime . '" title="' . $totalAmountPlanTime . '" onchange="checkFullTime(this);">';
                                        $tbody .= '</td>';
                                        $tbody .= '</tr>';
                                        $autoNumber++;
                                        if (empty($i)) {
                                            $theader .= '<th style="width:40px; max-width:40px; text-align: center;" rowspan="2" class="rowNumber">НИЙТ</th>';
                                            $theader .= '</tr>';
                                            $theader .= '<tr class="bp-filter-row">';
                                                $theader .= '<th class="rowNumber" style="background-color: rgb(231, 231, 231); position: relative; z-index: 10; background-clip: padding-box; top: -1px; left: 0px;"></th>';

                                                if ($isGolomt) {
                                                    $theader .= '<th style="background-color: rgb(231, 231, 231); position: relative; z-index: 10; background-clip: padding-box; top: -1px; left: 0px;"><input type="text" data-fieldname="employeename" data-condition="like"></th>';
                                                    $theader .= '<th style="background-color: rgb(231, 231, 231); position: relative; z-index: 10; background-clip: padding-box; top: -1px; left: 0px;"><input type="text" data-fieldname="employeedomain" data-condition="like"></th>';
                                                } else {
                                                    $theader .= '<th style="background-color: rgb(231, 231, 231); position: relative; z-index: 10; background-clip: padding-box; top: -1px; left: 0px;"><input type="text" data-fieldname="employeename" data-condition="like"></th>';
                                                }
                                                $theader .= '<th style="background-color: rgb(231, 231, 231); position: relative; z-index: 10; background-clip: padding-box; top: -1px; left: 0px;"><input type="text" data-fieldname="employeeposition" data-condition="like"></th>';
                                            $theader .= '</tr>';
                                            $theader .= '</thead>';
                                        }
                                    }
                                }
                            }
                        }

                        $result = ' <input type="hidden" id="tnatimePlanTotalCount" value="'. $employeeCount .'">
                                    <input type="hidden" id="tnatimePlanPage" value="1">
                                    <input type="hidden" id="tnatimePlanIsArchive" value="0">
                                    <input type="hidden" id="srch_yearCode" value="'. $params['planYear'] .'">
                                    <input type="hidden" id="srch_monthCode" value="'. $params['planMonth'] .'">
                                    <div class="pf-custom-pager">
                                        <div id="fz-parent" class="freeze-overflow-xy-auto">
                                            <table class="table table-sm table-bordered table-hover gl-table-dtl bprocess-theme1" id="assetDtls">
                                                '. $theader .'
                                                <tbody>'. $tbody .'</tbody>
                                                <tfoot>
                                                    '. $tfooter .'
                                                </tfoot>
                                            </table>
                                        </div>
                                        <div class="pf-custom-pager-tool">
                                            <div class="pf-custom-pager-buttons">
                                                '.Form::select(
                                                    array(
                                                        'name' => '',
                                                        'class' => 'pagination-page-list',
                                                        'op_value' => 'value',
                                                        'op_text' => 'code',
                                                        'style' => 'height:24px; float:left;color:#444',
                                                        'data' => array(
                                                            array('value' => '10', 'code' => '10'),
                                                            array('value' => '20', 'code' => '20'),
                                                            array('value' => '30', 'code' => '30'),
                                                            array('value' => '40', 'code' => '40'),
                                                            array('value' => '50', 'code' => '50'),
                                                            array('value' => '100', 'code' => '100'),
                                                            array('value' => '200', 'code' => '200')
                                                        ),
                                                        'text' => 'notext', 
                                                        'value' => Config::getFromCache('tmsPageNumber') ? Config::getFromCache('tmsPageNumber') : 50
                                                    )
                                                ).'
                                                <div class="pf-custom-pager-separator"></div>
                                                <a href="javascript:;" class="pf-custom-pager-last-prev pf-custom-pager-disabled">
                                                    <span></span>
                                                </a>
                                                <a href="javascript:;" class="pf-custom-pager-prev pf-custom-pager-disabled">
                                                    <span></span>
                                                </a>
                                                <div class="pf-custom-pager-separator"></div>
                                                <div class="pf-custom-pager-page-info">Хуудас <span><input type="text" size="2" value="1" data-gotopage="1" class="integerInit"></span> of <span data-pagenumber="1">0</span></div>	
                                                <div class="pf-custom-pager-separator"></div>
                                                <a href="javascript:;" class="pf-custom-pager-next pf-custom-pager-disabled">
                                                    <span></span>
                                                </a>
                                                <a href="javascript:;" class="pf-custom-pager-last-next pf-custom-pager-disabled">
                                                    <span></span>
                                                </a>
                                                <div class="pf-custom-pager-separator"></div>
                                                <a href="javascript:;" class="pf-custom-pager-refresh pf-custom-pager-disabled">
                                                    <span></span>
                                                </a>
                                            </div>
                                            <div class="pf-custom-pager-total"><span class="pf-custom-pager-total-selectedday" style="font-weight: normal"></span> Нийт <span>0</span> байна.</div>
                                        </div>
                                    </div>';
            }

            return $result;
        }
    }              
    
}