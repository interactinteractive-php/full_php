<?php if (!defined('_VALID_PHP')) exit('Direct access to this location is not allowed.');

class Info extends Model {
    
    public static $periodListGroupByParent = array();
    public static $periodListGroupById = array();
    private static $scenarioName = null;
    private static $scenarioColor = null;

    public static function YesNoList() {
        $array[] = array('value' => '1', 'name' => Lang::line('yes'));
        $array[] = array('value' => '0', 'name' => Lang::line('no'));
        
        return $array;
    }
    
    public static function showYesNoByNumber($val = '') {
        if ($val == '1' || $val == 'true') {
            return Lang::line('yes');
        } else {
            return Lang::line('no');
        }
    }
    
    public static function convertLetterToNumberBoolean($booleanValue) {
        $value = (!is_array($booleanValue)) ? strtolower($booleanValue) : '';
        return strtr($value, array('true' => 1, 'false' => 0, 'yes' => 1, 'no' => 0));
    }

    public static function getStoreList() {
        global $db;
        $data = $db->GetAll("SELECT STORE_ID, NAME FROM SM_STORE");
        return $data;
    }
    
    public static function getItemList() {
        global $db;
        $data = $db->GetAll("SELECT ITEM_ID, ITEM_CODE, ITEM_NAME FROM IM_ITEM");
        return $data;
    }
    
    public static function SequenceID() {
        return getUID();
    }
    
    public static function getConnections() {
        
        $cache = phpFastCache();
        $data = $cache->get('getDbConnections');
        
        if ($data == null) {
            
            global $db;
            
            $userId = Ue::sessionUserId();
            
            $data = $db->GetAll("
                SELECT 
                    DM.DATABASE_ID, 
                    MDM.DB_NAME, 
                    MDM.DB_TYPE, 
                    MDM.HOST_NAME, 
                    MDM.PORT, 
                    MDM.SID, 
                    MDM.SERVICE_NAME, 
                    MDM.DB_LINK, 
                    MDM.USER_NAME, 
                    MDM.USER_PASSWORD, 
                    DM.IS_DEFAULT 
                FROM MDM_CONNECTIONS MDM 
                    INNER JOIN UM_USER_DATABASE_MAP DM ON DM.DATABASE_ID = MDM.ID 
                WHERE DM.USER_ID = $userId  
                    AND MDM.IS_ACTIVE = 1     
                ORDER BY DM.DATABASE_ID ASC");
            $cache->set('getDbConnections', $data, 86400);
        }
        
        return $data;
    }
    
    public static function getDefaultConnection() {
        
        global $db;
        
        $row = $db->GetRow("
            SELECT 
                DM.DATABASE_ID, 
                MDM.DB_NAME, 
                MDM.DB_TYPE, 
                MDM.HOST_NAME, 
                MDM.PORT, 
                MDM.SID, 
                MDM.SERVICE_NAME, 
                MDM.DB_LINK,  
                MDM.USER_NAME, 
                MDM.USER_PASSWORD
            FROM MDM_CONNECTIONS MDM 
                INNER JOIN UM_USER_DATABASE_MAP DM ON DM.DATABASE_ID = MDM.ID 
            WHERE DM.USER_ID = ".Ue::sessionUserId()." 
                AND DM.IS_DEFAULT = 1 
                AND MDM.IS_ACTIVE = 1 
            ORDER BY DM.DATABASE_ID ASC");
        
        return $row;
    }
    
    public static function getConnectionById($dbId) {
        
        global $db;
        
        $row = $db->GetRow("
            SELECT 
                DM.DATABASE_ID, 
                MDM.DB_NAME, 
                MDM.DB_TYPE, 
                MDM.HOST_NAME, 
                MDM.PORT, 
                MDM.SID, 
                MDM.SERVICE_NAME, 
                MDM.DB_LINK,  
                MDM.USER_NAME, 
                MDM.USER_PASSWORD
            FROM MDM_CONNECTIONS MDM 
                INNER JOIN UM_USER_DATABASE_MAP DM ON DM.DATABASE_ID = MDM.ID 
            WHERE DM.USER_ID = ".Ue::sessionUserId()." 
                AND DM.DATABASE_ID = '$dbId' 
            ORDER BY DM.DATABASE_ID ASC");
        
        return $row;
    }
    
    public static function criteriaCondition() {
        
        $array[] = array('value' => '=', 'code' => '=', 'name' => Lang::lineDefault('META_00134', 'Тэнцүү'));
        $array[] = array('value' => '>', 'code' => '>', 'name' => Lang::lineDefault('META_filter_00135', 'Их'));
        $array[] = array('value' => '<', 'code' => '<', 'name' => Lang::lineDefault('META_filter_00136', 'Бага'));
        $array[] = array('value' => '>=', 'code' => '>=', 'name' => Lang::lineDefault('META_filter_00137', 'Их буюу тэнцүү'));
        $array[] = array('value' => '<=', 'code' => '<=', 'name' => Lang::lineDefault('META_filter_00138', 'Бага буюу тэнцүү'));
        //$array[] = array('value' => 'BETWEEN', 'code' => 'Хооронд', 'name' => 'Хооронд');
        
        return $array;
    }
    
    public static function defaultCriteriaCondition($metaTypeCode = 'string') {
        
        $array[] = array('value' => '=', 'code' => Lang::lineDefault('META_00134', 'Тэнцүү'), 'name' => Lang::lineDefault('META_00134', 'Тэнцүү'));
        $array[] = array('value' => '!=', 'code' => Lang::lineDefault('META_filter_00134', 'Ялгаатай'), 'name' => Lang::lineDefault('META_filter_00134', 'Ялгаатай'));
        
        if ($metaTypeCode == 'date' || $metaTypeCode == 'datetime' || $metaTypeCode == 'bigdecimal' || $metaTypeCode == 'bigdecimal_null') {
            
            $array[] = array('value' => '>', 'code' => Lang::lineDefault('META_filter_00135', 'Их'), 'name' => Lang::lineDefault('META_filter_00135', 'Их'));
            $array[] = array('value' => '<', 'code' => Lang::lineDefault('META_filter_00136', 'Бага'), 'name' => Lang::lineDefault('META_filter_00136', 'Бага'));
            $array[] = array('value' => '>=', 'code' => Lang::lineDefault('META_filter_00137', 'Их буюу тэнцүү'), 'name' => Lang::lineDefault('META_filter_00137', 'Их буюу тэнцүү'));
            $array[] = array('value' => '<=', 'code' => Lang::lineDefault('META_filter_00138', 'Бага буюу тэнцүү'), 'name' => Lang::lineDefault('META_filter_00138', 'Бага буюу тэнцүү'));
            //$array[] = array('value' => 'BETWEEN', 'code' => 'Хооронд', 'name' => 'Хооронд');
            
        } elseif ($metaTypeCode == 'number' || $metaTypeCode == 'integer' || $metaTypeCode == 'decimal') {
            
            $array[] = array('value' => '>', 'code' => Lang::lineDefault('META_filter_00135', 'Их'), 'name' => Lang::lineDefault('META_filter_00135', 'Их'));
            $array[] = array('value' => '<', 'code' => Lang::lineDefault('META_filter_00136', 'Бага'), 'name' => Lang::lineDefault('META_filter_00136', 'Бага'));
            $array[] = array('value' => '>=', 'code' => Lang::lineDefault('META_filter_00137', 'Их буюу тэнцүү'), 'name' => Lang::lineDefault('META_filter_00137', 'Их буюу тэнцүү'));
            $array[] = array('value' => '<=', 'code' => Lang::lineDefault('META_filter_00138', 'Бага буюу тэнцүү'), 'name' => Lang::lineDefault('META_filter_00138', 'Бага буюу тэнцүү'));
            
        } elseif ($metaTypeCode == 'boolean') {
            
            $array = array();
            
        } elseif ($metaTypeCode != 'long') {
            
            $array[] = array('value' => 'like', 'code' => 'Төстэй', 'name' => 'Төстэй');
            $array[] = array('value' => 'start', 'code' => 'Эхлэсэн', 'name' => 'Эхлэсэн');
            $array[] = array('value' => 'end', 'code' => 'Төгссөн', 'name' => 'Төгссөн');
        } 
        
        return $array;
    }
    
    public static function getRefTimeTypeList() {
        global $db;
        $result = $db->GetAll("SELECT TIME_TYPE_ID, TIME_TYPE_NAME FROM REF_TIME_TYPE");
        return $result; 
    }
    
    public static function getRefYearList($year = null) {
        
        global $db;
        
        $where = '';
        if ($year != null) {
            $where = "WHERE YEAR_CODE >= '".$year."'"; 
        }
        
        $result = $db->GetAll("SELECT YEAR_ID, YEAR_NAME, YEAR_CODE FROM REF_YEAR $where ORDER BY YEAR_CODE");
        return $result;
    }
    
    public static function getRefMonthList() {
        global $db;
        $result = $db->GetAll("SELECT MONTH_ID, MONTH_NAME, MONTH_CODE FROM REF_MONTH ORDER BY MONTH_CODE");
        return $result; 
    }
    
    public static function getDashboardType() {
        global $db;
        return $db->GetAll("SELECT CODE, NAME FROM META_DASHBOARD_TYPE WHERE PARENT_ID IS NULL ORDER BY NAME");
    }
    
    public static function getDiagramType($type = null, $isPivot = 0) {
        global $db;
        return $db->GetAll("SELECT CODE, NAME FROM META_DASHBOARD_TYPE WHERE PARENT_ID = (SELECT ID FROM META_DASHBOARD_TYPE WHERE code = '$type') AND IS_PIVOT = $isPivot ORDER BY NAME");
    }
    
    public static function fiscalPeriod() {
        
        $html = '';

        if (Config::getFromCache('CONFIG_FISCAL_PERIOD')) {

            $cache = phpFastCache();

            $dataYear = $cache->get('getFiscalPeriodYearList');
            
            if ($dataYear == null) {
                global $db;
                
                $dataYear = $db->GetAll(
                    "SELECT 
                        ID, 
                        PERIOD_NAME, 
                        IS_CURRENT 
                    FROM FIN_FISCAL_PERIOD 
                    WHERE TYPE_ID = 4 
                        AND (IS_HIDE IS NULL OR IS_HIDE = 0) 
                    ORDER BY START_DATE, END_DATE ASC");
                $cache->set('getFiscalPeriodYearList', $dataYear, 86400);
            }

            if ($dataYear) {
                $periodYearId = Input::param(Session::get(SESSION_PREFIX.'periodYearId'));

                $html .= '<li class="dropdown dropdown-language dropdown-dark fiscal-period-year-container">';

                $currentYearPeriod = '';
                $listYearPeriod = '';

                foreach ($dataYear as $year) {

                    $currentPeriodYearIcon = '';

                    if ($year['ID'] == $periodYearId) {

                        $currentYearPeriod = '<span class="langname">'.$year['PERIOD_NAME'].'</span>';
                        $currentPeriodYearIcon = ' <i class="fa fa-check-circle"></i>';

                    } 

                    $listYearPeriod .= '<li data-id="'.$year['ID'].'">
                                    <a href="javascript:;">
                                      '.$year['PERIOD_NAME'].$currentPeriodYearIcon.'
                                    </a>
                                  </li>';
                }

                $html .= '<a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown" data-close-others="true">
                            '.$currentYearPeriod.'
                          </a>
                          <ul class="dropdown-menu dropdown-menu-default fiscal-period-year dropdown-menu-right">
                            '.$listYearPeriod.'
                          </ul>
                          </li>';
            }

            if (Session::isCheck(SESSION_PREFIX.'periodId')) {
                $html .= '<li class="dropdown dropdown-language dropdown-dark nav-item fiscal-period-child-container">
                                <a href="javascript:;" class="dropdown-toggle dropdown-item" data-toggle="dropdown" data-close-others="true">
                                </a>
                            <ul class="dropdown-menu dropdown-menu-default dropdown-menu-right fiscal-period-child">
                              '.self::childFiscalPeriod($periodYearId).'
                            </ul>
                        </li>';
            }
        }

        return $html;
    }
    
    public static function childFiscalPeriod($parentId, $depth = 0) {
        
        $html = '';
        
        if (empty($parentId)) {
            return $html;
        }
        
        $cache = phpFastCache();
                
        $data = $cache->get('getFiscalPeriodList_'.$parentId);
        
        if ($data == null) {
            global $db;
            
            $data = $db->GetAll(
                "SELECT 
                    ID, 
                    PERIOD_NAME, 
                    IS_CURRENT, 
                    IS_CLOSED 
                FROM FIN_FISCAL_PERIOD 
                WHERE PARENT_ID = $parentId 
                    AND (IS_HIDE IS NULL OR IS_HIDE = 0) 
                ORDER BY START_DATE, END_DATE ASC");
            $cache->set('getFiscalPeriodList_'.$parentId, $data, 86400);
        }
        
        if ($data) {
            
            foreach ($data as $row) {
                
                $currentPeriod = '';
                $currentPeriodIcon = '';
                
                if (isset($row['IS_CLOSED']) && $row['IS_CLOSED'] == '1') {
                    $currentPeriodIcon = ' <i class="fa fa-lock"></i>';
                }
                
                if ($row['ID'] == Session::get(SESSION_PREFIX.'periodId')) {

                    $currentPeriod = ' class="current"';
                    $currentPeriodIcon .= ' <i class="fa fa-check-circle"></i>';
                }

                $html .= '<li'.$currentPeriod.' data-id="'.$row['ID'].'">
                            <a href="javascript:;" class="period-level-'.$depth.'">
                              '.$row['PERIOD_NAME'].$currentPeriodIcon.'
                            </a>
                          </li>';
                
                $html .= self::childFiscalPeriod($row['ID'], $depth + 1);
            }
        }
                    
        return $html;
    }
    
    public static function getUserKeyList() {
        global $db;
        $data = $db->GetAll("SELECT USER_KEY_ID, DEPARTMENT_NAME, DEPARTMENT_ID FROM VW_USER_KEY WHERE SYSTEM_USER_ID = ".Ue::sessionUserId());
        return $data;
    }
    
    public static function getUserKeyRowById($userKeyId) {
        global $db;
        $row = $db->GetRow("SELECT COMPANY_NAME FROM VW_USER_KEY WHERE USER_KEY_ID = $userKeyId");
        return $row;
    }
    
    public static function getEmployeeBalance() {
      
      $array[] = array('value' => '0', 'name' => 'Бүгд');
      $array[] = array('value' => '1', 'name' => 'Хоцорсон');
      $array[] = array('value' => '2', 'name' => 'Илүү цагтай');

      return $array;
    }
    
    public static function getEmployeeBalanceVeri() {
      
      $array[] = array('value' => '0', 'name' => 'Бүгд');
      $array[] = array('value' => '1', 'name' => 'Баталгаажсан');
      $array[] = array('value' => '2', 'name' => 'Баталгаажаагүй');

      return $array;
    }
    
    public static function getNotificationListForSelect() {
        global $db;
        $data = $db->GetAll("
            SELECT 
                N.NOTIFICATION_ID, 
                NVL2(GD.MONGOLIAN, GD.MONGOLIAN, N.MESSAGE) TEXT,
                substr(NVL2(GD.MONGOLIAN, GD.MONGOLIAN, N.MESSAGE), 1, 100) || '...' TEXT_SHORT, 
                N.DIRECT_URL 
            FROM NTF_NOTIFICATION N
                LEFT JOIN GLOBE_DICTIONARY GD ON GD.CODE = N.MESSAGE 
            WHERE N.MESSAGE IS NOT NULL ORDER BY TEXT ASC");
        return $data;
    }
    
    public static function dashboardColorTheme() {
        $array[] = array('value' => '1', 'name' => 'THEME 1', 'code' => '#22c3f5 #3faba4 #ff8d00 #95A5A6 #d05454 #f3c200 #8775a7 #009c02 #b1e10a #e25f9e #e7bda2 #0041c4 #ff8d00 #ff0000 #2585ae #4e8539 #6ba6d4 #fdff00 #00ff04 #0085ff #d40a78 #b4c7f0 #da70d6 #edc613');
        $array[] = array('value' => '2', 'name' => 'THEME 2', 'code' => '#67809F #1BBC9B #E08283 #95A5A6 #F7CA18 #8775A7 #796799 #C49F47 #E87E04 #f3c200 #8E44AD #009c02 #32c5d2 #2585ae #4e8539 #6ba6d4 #fdff00  #3faba4  #2585ae #4e8539 #6ba6d4 #fdff00  #00ff04 #0085ff  #d40a78 #b4c7f0 #da70d6 #edc613  #3faba4 #e7505a #e7bda2 #d05454 #5f00c3 #009c02 #3faba4 #4e8539 #d40a78 #f3c200 #edc613 #e7505a #6ba6d4 #00ff04');
        $array[] = array('value' => '3', 'name' => 'THEME 3', 'code' => '#2AB4C0 #EF4836 #26C281 #5C9BD1 #8775A7 #F2784B #BFBFBF #8E44AD #5E738B #f3c200 #8E44AD #009c02 #32c5d2 #2585ae #4e8539 #6ba6d4 #fdff00  #3faba4  #2585ae #4e8539 #6ba6d4 #fdff00  #00ff04 #0085ff  #d40a78 #b4c7f0 #da70d6 #edc613  #3faba4 #e7505a #e7bda2 #d05454 #5f00c3 #009c02 #3faba4 #4e8539 #d40a78 #f3c200 #edc613 #e7505a #6ba6d4 #00ff04');
        $array[] = array('value' => '4', 'name' => 'THEME 4', 'code' => '#dc6788 #dc67ab #dc67ce #c767dc #a367dc #8067dc #6771dc #6794dc #67b7dc');
        $array[] = array('value' => '5', 'name' => 'THEME 5', 'code' => '#a62c5b #d1768b #dc67ce #c767dc #a367dc #8067dc #6771dc #6794dc #67b7dc');
        
        return $array;
    }
    
    public static function dashboardLegendPosition() {
        $array[] = array('value' => 'top', 'name' => 'Дээр');
        $array[] = array('value' => 'bottom', 'name' => 'Доор');
        $array[] = array('value' => 'right', 'name' => 'Баруун');
        $array[] = array('value' => 'left', 'name' => 'Зүүн');
        
        return $array;
    }
    
    public static function getDashboardColorTheme($id) {
        $array[] = array('value' => '1', 'name' => 'THEME 1', 'code' => '#8E44AD #3faba4 #ff8d00 #95A5A6 #d05454 #f3c200 #8775a7 #009c02 #b1e10a #e25f9e #e7bda2 #0041c4 #ff8d00 #ff0000 #2585ae #4e8539 #6ba6d4 #fdff00 #00ff04 #0085ff #d40a78 #b4c7f0 #da70d6 #edc613 #8E44AD #3faba4 #ff8d00 #95A5A6 #d05454 #f3c200 #8775a7 #009c02 #b1e10a #e25f9e #e7bda2 #0041c4 #ff8d00 #ff0000 #2585ae #4e8539 #6ba6d4 #fdff00 #00ff04 #0085ff #d40a78 #b4c7f0 #da70d6 #edc613 #8E44AD #3faba4 #ff8d00 #95A5A6 #d05454 #f3c200 #8775a7 #009c02 #b1e10a #e25f9e #e7bda2 #0041c4 #ff8d00 #ff0000 #2585ae #4e8539 #6ba6d4 #fdff00 #00ff04 #0085ff #d40a78 #b4c7f0 #da70d6 #edc613');
        $array[] = array('value' => '2', 'name' => 'THEME 2', 'code' => '#67809F #1BBC9B #E08283 #95A5A6 #F7CA18 #8775A7 #796799 #C49F47 #E87E04 #f3c200 #8E44AD #009c02 #32c5d2 #2585ae #4e8539 #6ba6d4 #fdff00 #3faba4 #2585ae #4e8539 #6ba6d4 #fdff00 #00ff04 #0085ff #d40a78 #b4c7f0 #da70d6 #edc613 #3faba4 #e7505a #e7bda2 #d05454 #5f00c3 #009c02 #3faba4 #4e8539 #d40a78 #f3c200 #edc613 #e7505a #6ba6d4 #00ff04');
        $array[] = array('value' => '3', 'name' => 'THEME 3', 'code' => '#2AB4C0 #EF4836 #26C281 #5C9BD1 #8775A7 #F2784B #BFBFBF #8E44AD #5E738B #f3c200 #8E44AD #009c02 #32c5d2 #2585ae #4e8539 #6ba6d4 #fdff00 #3faba4 #2585ae #4e8539 #6ba6d4 #fdff00 #00ff04 #0085ff #d40a78 #b4c7f0 #da70d6 #edc613 #3faba4 #e7505a #e7bda2 #d05454 #5f00c3 #009c02 #3faba4 #4e8539 #d40a78 #f3c200 #edc613 #e7505a #6ba6d4 #00ff04');
        $array[] = array('value' => '4', 'name' => 'THEME 4', 'code' => '#dc6788 #dc67ab #dc67ce #c767dc #a367dc #8067dc #6771dc #6794dc #67b7dc');
        $array[] = array('value' => '5', 'name' => 'THEME 5', 'code' => '#a62c5b #d1768b #dc67ce #c767dc #a367dc #8067dc #6771dc #6794dc #67b7dc');
        $return = '0';
        
        foreach ($array as $value) {
            if ($value['value'] == $id) {
                $return = $value['code'];
            }
        }
        
        return $return;
    }
    
    public static function searchType() {
        
        $array[1] = array('value' => '1', 'name' => 'LEFT');
        $array[2] = array('value' => '2', 'name' => 'TOP');
        $array[3] = array('value' => '3', 'name' => 'BUTTON');
        $array[4] = array('value' => '4', 'name' => 'LEFT STATIC');
        $array[5] = array('value' => '5', 'name' => 'LEFT WEB');
        $array[6] = array('value' => '6', 'name' => 'HIDDEN');
        $array[7] = array('value' => '7', 'name' => 'POPUP');
        $array[8] = array('value' => '8', 'name' => 'LEFT WEB CIVIL');
        
        return $array;
    }
    
    public static function getSearchType($id) {
        
        $searchType = Info::searchType();
        
        if (isset($searchType[$id])) {
            return $searchType[$id]['name'];
        }
        
        return '0';
    }
    
    public static function chooseEaScenario() {
        
        $html = '';
        $isEaScenario = Config::getFromCache('isEaScenario');
        
        if ($isEaScenario == '1') {
            
            $param = array(
                'systemMetaGroupId' => '1580278713512437',
                'showQuery' => 0,
                'ignorePermission' => 1
            );
            $data = WebService::runSerializeResponse(GF_SERVICE_ADDRESS, 'PL_MDVIEW_004', $param);
            
            if (isset($data['result']) && isset($data['result'][0])) {
                
                unset($data['result']['aggregatecolumns']);
                unset($data['result']['paging']);
                
                $tree = Arr::buildTree($data['result'], 0, 'id', 'parentid');
                $dropdown = Info::buildScenarioTree($tree);
                
                $html = '<li class="nav-item dropdown dropdown-language dropdown-dark scenario-container"'.(Info::$scenarioColor ? ' style="background-color:'.Info::$scenarioColor.'"' : '').'>
                    <a href="javascript:;" class="dropdown-toggle navbar-nav-link" data-toggle="dropdown" data-close-others="true">'.Info::$scenarioName.'</a>
                    <ul class="dropdown-menu dropdown-menu-default dropdown-menu-right scenario-child">
                        '.$dropdown.'
                    </ul>
                </li>';
            }
        }
        
        return $html;
    }
    
    public static function buildScenarioTree($result, $depth = 0) {
        
        $list = '';
        $selectedScenarioId = Session::get(SESSION_PREFIX.'eaScenarioId');
        
        foreach ($result as $row) {
                    
            $currentPeriod = $currentPeriodYearIcon = $currentPeriodYearActiveIcon = $hidden = $child = $styles = '';
            
            if (isset($row['children'])) {
                $currentPeriodYearIcon = '<i class="fa fa-angle-right"></i> ';
                $child = Info::buildScenarioTree($row['children'], $depth+1);
            }
            
            if ($row['parentid']) {
                $hidden = ' d-none';
            }
            
            if ($bgColor = issetParam($row['color'])) {
                $styles = ' style="background-color: '.$bgColor.'"';
            }

            if (($row['id'] == $selectedScenarioId) || (!$selectedScenarioId && $row['isactive'] == '1')) {
                
                $currentPeriodYearActiveIcon = ' <i class="fa fa-check-circle"></i>';
                $currentPeriod = ' current';
                
                Info::$scenarioName = $row['name'];
                Info::$scenarioColor = isset($bgColor) ? $bgColor : null;
                
                if (!$selectedScenarioId) {
                    Session::set(SESSION_PREFIX.'eaScenarioId', $row['id']);
                }
            }

            $list .= '<li data-id="'.$row['id'].'" data-parentid="'.$row['parentid'].'" class="nav-item '.$currentPeriod.'"'.$styles.'>
                <a href="javascript:;" class="dropdown-item period-level-'.$depth.' '.$hidden.'">
                '.$currentPeriodYearIcon.$row['name'].$currentPeriodYearActiveIcon.'
                </a>
            </li>';
            
            $list .= $child;
        }
        
        return $list;
    }
    
    public static function getDbName() {
        
        if ($sdbnm = Session::get(SESSION_PREFIX . 'sdbnm')) {
            return '<li class="nav-item text-uppercase font-weight-bold mr-2 db-choose" title="Сонгосон бааз">
                <i class="icon-database mr-1"></i> <span>'.$sdbnm.'</span> 
            </li>';
        }
        
        return '';
    }
    
    public static function formControlType() {
        
        $array[1] = array('value' => '1', 'name' => 'MIN');
        
        return $array;
    }
    
    public static function getLanguageShortName() {
        if (Session::isCheck(SESSION_PREFIX . 'langshortcode')) {
            return Session::get(SESSION_PREFIX . 'langshortcode');
        }
        if ($lang = Config::getFromCache('LANG')) {
            return $lang;
        }
        return 'mn';
    }
    
    public static function fiscalPeriodNewV2() {
        
        $html = '';        

        if (Config::getFromCache('CONFIG_FISCAL_PERIOD')) {
            
            $periodId = Session::get(SESSION_PREFIX . 'periodId');
            
            if ($periodId) {
                global $db;
                $langCode = Lang::getCode();
                $periodName = $db->GetOne("SELECT (FNC_TRANSLATE('$langCode', TRANSLATION_VALUE, 'PERIOD_NAME', PERIOD_NAME)) AS PERIOD_NAME FROM FIN_FISCAL_PERIOD WHERE ID = ".$db->Param(0), array($periodId));
            } else {
                $periodName = 'Тайлант үе сонгогдоогүй!';
            }
            
            $html = '<li class="nav-item dropdown dropdown-language dropdown-dark fiscal-period-child-container" title="'.Lang::lineDefault('REP_FISCAL_PERIOD', 'Тайлант үе').'">
                <a href="javascript:;" class="dropdown-toggle navbar-nav-link align-items-center" data-toggle="dropdown" data-close-others="true">
                    <span class="langname">'.$periodName.'</span>
                </a>
                <ul class="dropdown-menu dropdown-menu-default dropdown-menu-right fiscal-period-child"></ul>
            </li>';
        }

        return $html;
    }
    
    public static function childFiscalPeriodNewV2($parentId, $depth = 0, $parentYear = '', $currentYear) {
        
        $html = '';
        if (empty($parentId)) {
            return $html;
        }
        
        if (isset(self::$periodListGroupByParent[$parentId])) {
            
            $sessPeriodId = Session::get(SESSION_PREFIX.'periodId');
            $childIco = '';
            $childClass = ' child-period-link hidden';
            $childStyle = '';
            $childParentPeriod = isset(self::$periodListGroupById[$sessPeriodId]) ? self::$periodListGroupById[$sessPeriodId] : null;
            
            if ($depth == 0) {
                $childIco = '<i class="fa fa-angle-right"></i> ';
                $childClass = ' hidden';            
                $childStyle = ' style="padding-left:22px"';
                
            } elseif ($depth == 2) {
                $childClass = ' child-period-link-deep hidden'; 
            }
            
            $data = self::$periodListGroupByParent[$parentId];
            
            foreach ($data as $row) {
                
                if (isset(self::$periodListGroupByParent[$row['ID']]) && $depth == 1) {
                    $childIco = '<i class="fa fa-angle-right"></i> ';
                    $childClass = ' hidden';            
                    $childStyle = ' style="padding-left:22px"';
                    
                } elseif($depth == 1) {
                    $childIco = '';
                    $childClass = ' child-period-link hidden';
                    $childStyle = '';
                }

                if ($currentYear == $parentId) {
                    $childClass = '';                
                } elseif ($childParentPeriod == $parentId) {
                    $childClass = ' child-period-link';
                }                
                
                $currentPeriod = '';
                $currentPeriodIcon = '';
                $periodChildClosedIcon = '';
                $currentParentPeriod = '';
                $currentParentPeriodIcon = '';
                
                if ($row['IS_CLOSED'] == '1') {
                    $periodChildClosedIcon = ' <i class="fa fa-lock"></i>';
                }
                
                if ($row['ID'] == $sessPeriodId) {
                    $currentPeriod = ' class="current"';
                    $currentPeriodIcon .= ' <i class="fa fa-check-circle"></i>';
                }
                if ($childParentPeriod[0]['PARENT_ID'] == $row['ID']) {
                    $currentParentPeriod = ' class="current"';
                    $currentParentPeriodIcon = '<i class="fa fa-angle-down"></i> ';
                }

                $html .= '<li'.$currentPeriod.$currentParentPeriod.' data-id="'.$row['ID'].'" data-root-year="'.$parentYear.'" data-parent-id="child-'.rtrim($parentId, '-').'">
                            <a href="javascript:;"'.$childStyle.' class="period-level-'.$depth.$childClass.'">
                              '.($currentParentPeriodIcon === '' ? $childIco : $currentParentPeriodIcon).$row['PERIOD_NAME'].$currentPeriodIcon.$periodChildClosedIcon.'
                            </a>
                          </li>';
                
                $html .= self::childFiscalPeriodNewV2($row['ID'], $depth + 1, $parentYear, $currentYear);
            }
        }

        return $html;
    }
    
    public static function groupByArrayDoubleKey($rows, $key1, $key2) {
        $result1 = array();
        $result2 = array();
        
        foreach ($rows as $data) {
            $id = $data[$key1];
            if (isset($result1[$id])) {
                $result1[$id][] = $data;
            } else {
                $result1[$id] = array($data);
            }
            
            $id = $data[$key2];
            if (isset($result2[$id])) {
                $result2[$id][] = $data;
            } else {
                $result2[$id] = array($data);
            }
        }
        
        return array(
            $key1 => $result1,
            $key2 => $result2
        );
    }   

    public static function getPerformanceQualityGradeByLetter($grade, $classKeyId) {

        return $db->GetOne("SELECT CSG.GRADE
                FROM CAM_SUBJECT_GRADE CSG
                INNER JOIN CAM_GRADE_LEVEL CGL   ON CGL.GRADE_LEVEL_ID = CSG.GRADE_LEVEL_ID
                INNER JOIN CAM_PROGRAM_GRADE CPG ON CPG.GRADE_LEVEL_ID = CSG.GRADE_LEVEL_ID
                INNER JOIN CAM_CLASS_KEY CCK     ON CCK.PROGRAM_ID     = CPG.PROGRAM_ID
                WHERE CCK.CLASS_KEY_ID                                 = $classKeyId
                AND $grade BETWEEN MARK_FROM AND MARK_TO");
    }

    public static function fiscalPeriodNew() {

        $html = '';

        if (Config::getFromCache('CONFIG_FISCAL_PERIOD')) {

            loadPhpFastCache();
            $cache = phpFastCache();

            $dataYear = $cache->get('getFiscalPeriodYearList');

            if ($dataYear == null) {
                global $db;

                $dataYear = $db->GetAll(
                        "SELECT 
                        ID, 
                        PERIOD_NAME, 
                        IS_CURRENT 
                    FROM FIN_FISCAL_PERIOD 
                    WHERE TYPE_ID = 4 
                    ORDER BY START_DATE, END_DATE ASC");
                $cache->set('getFiscalPeriodYearList', $dataYear, 86400);
            }

            if ($dataYear) {
                $periodYearId = Input::param(Session::get(SESSION_PREFIX . 'periodYearId'));
                $listYearPeriod = '';

                foreach ($dataYear as $year) {
                    $currentYearPeriod = '';
                    $currentPeriodYearIcon = '<i class="fa fa-angle-right"></i> ';

                    if ($year['ID'] == $periodYearId) {
                        // $currentPeriodYearIcon = '<i class="fa fa-angle-down"></i> ';
                        $currentYearPeriod = ' current';
                    }

                    $listYearPeriod .= '<li data-id="' . $year['ID'] . '" class="root-period nav-item' . $currentYearPeriod . '">
                                        <a href="javascript:;" class="navbar-nav-link">
                                          ' . $currentPeriodYearIcon . $year['PERIOD_NAME'] . '
                                        </a>
                                      </li>';
                    $listYearPeriod .= self::childFiscalPeriodNew($year['ID'], 0, $year['ID'], $periodYearId);
                }
            }

            if (Session::isCheck(SESSION_PREFIX . 'periodId')) {
                $html .= '<li class="dropdown dropdown-language dropdown-dark nav-item fiscal-period-child-container">
                                <a href="javascript:;" class="dropdown-toggle navbar-nav-link" data-toggle="dropdown" data-close-others="true">
                                </a>
                            <ul class="dropdown-menu dropdown-menu-default dropdown-menu-right fiscal-period-child">
                              ' . $listYearPeriod . '
                            </ul>
                        </li>';
            }
        }

        return $html;
    }

	public static function createLinks($list_class , $_limit = 1, $_page = 1, $_total = 1, $url = '/news', $jq_func = 'moto_url') {
        if ($_limit == 'all') {
            return '';
        }
        
        $last = ceil($_total / $_limit);

        $start = ( $_page  > 0 ) ? $_page : 1;
        $end = ( $_page + 5 < $last ) ? $_page + 5 : $last;

        $html = '<ul class="' . $list_class . '">';

        $class = ( $_page == 1 ) ? "disabled" : "";
        $html .= '<li class="' . $class . '"><a href="javascript:;" onclick="'.$jq_func.'(this, ' . ( $_page - 1 ) . ')" paginator-href="?limit=' . $_limit . '&page=' . ( $_page - 1 ) . '"><i class="fa fa-step-backward" aria-hidden="true"></i></a></li>';

        if ($start > 1) {
            $html .= '<li><a href="javascript:;" onclick="'.$jq_func.'(this, \'1\')" paginator-href="limit=' . $_limit . '&page=1">1</a></li>';
            $html .= '<li class="disabled"><span>...</span></li>';
        }

        for ($i = $start; $i <= $end; $i++) {
            $class = ( $_page == $i ) ? "active" : "";
            $html .= '<li class="' . $class . '"><a href="javascript:;" onclick="'.$jq_func.'(this, ' . $i . ')" paginator-href="limit=' . $_limit . '&page=' . $i . '">' . $i . '</a></li>';
        }

        if ($end < $last) {
            $html .= '<li class="disabled"><span>...</span></li>';
            $html .= '<li><a href="javascript:;" onclick="'.$jq_func.'(this, ' . $last . ')" paginator-href="limit=' . $_limit . '&page=' . $last . '">' . $last . '</a></li>';
        }

        $class = ( $_page == $last ) ? "disabled" : "";
        $html .= '<li class="' . $class . '"><a href="javascript:;" onclick="'.$jq_func.'(this, ' . ( $_page + 1 ) . ')" paginator-href="limit=' . $_limit . '&page=' . ( $_page + 1 ) . '"><i class="fa fa-step-forward" aria-hidden="true"></i></a></li>';

        $html .= '</ul>';

        return $html;
    }
	
    public static function chartDefaultTheme () {
        return array(
            array(
                'code' => 'default',
                'bgColor' => '#FFF',
                'themeColor' => array(
                    '#5470c6',
                    '#91cc75',
                    '#fac858',
                    '#ee6666',
                    '#73c0de',
                    '#3ba272',
                    '#fc8452',
                    '#9a60b4',
                    '#ea7ccc',
                )
            ),
            array(
                'code' => 'vintage',
                'bgColor' => 'rgb(254, 248, 239)',
                'themeColor' => array(
                    'rgb(216, 124, 124)',
                    'rgb(145, 158, 139)',
                    'rgb(215, 171, 130)',
                    'rgb(110, 112, 116)',
                    'rgb(97, 160, 168)',
                    'rgb(239, 161, 141)',
                    'rgb(120, 116, 100)',
                    'rgb(204, 126, 99)',
                    'rgb(114, 78, 88)',
                    'rgb(75, 86, 91)'
                )
            ),
            array(
                'code' => 'dark',
                'bgColor' => 'rgb(51, 51, 51)',
                'themeColor' => array(
                    'rgb(221, 107, 102)',
                    'rgb(117, 154, 160)',
                    'rgb(230, 157, 135)',
                    'rgb(141, 193, 169)',
                    'rgb(234, 126, 83)',
                    'rgb(238, 221, 120)',
                    'rgb(115, 163, 115)',
                    'rgb(115, 185, 188)',
                    'rgb(114, 137, 171)',
                    'rgb(145, 202, 140)',
                    'rgb(244, 159, 66)'
                )
            ),
            array(
                'code' => 'westeros',
                'bgColor' => 'transparent',
                'themeColor' => array(
                    'rgb(81, 107, 145)', 
                    'rgb(89, 196, 230)', 
                    'rgb(237, 175, 218)', 
                    'rgb(147, 183, 227)', 
                    'rgb(165, 231, 240)', 
                    'rgb(203, 176, 227)'
                )
            ),
            array(
                'code' => 'essos',
                'bgColor' => 'rgba(242, 234, 191, 0.15)',
                'themeColor' => array(
                    'rgb(137, 52, 72)',
                    'rgb(217, 88, 80)',
                    'rgb(235, 129, 70)',
                    'rgb(255, 178, 72)',
                    'rgb(242, 214, 67)',
                    'rgb(235, 219, 164)',
                )
            ),
            array(
                'code' => 'wonderland',
                'bgColor' => 'transparent',
                'themeColor' => array(
                    'rgb(78, 163, 151)',
                    'rgb(34, 195, 170)',
                    'rgb(123, 217, 165)',
                    'rgb(208, 100, 138)',
                    'rgb(245, 141, 178)',
                    'rgb(242, 179, 201)',
                )
            ),
            array(
                'code' => 'walden',
                'bgColor' => 'rgba(252, 252, 252, 0)',
                'themeColor' => array(
                    'rgb(63, 177, 227)',
                    'rgb(107, 230, 193)',
                    'rgb(98, 108, 145)',
                    'rgb(160, 167, 230)',
                    'rgb(196, 235, 173)',
                    'rgb(150, 222, 232)',
                )
            ),
            array(
                'code' => 'chalk',
                'bgColor' => 'rgb(41, 52, 65)',
                'themeColor' => array(
                    'rgb(252, 151, 175)',
                    'rgb(135, 247, 207)',
                    'rgb(247, 244, 148)',
                    'rgb(114, 204, 255)',
                    'rgb(247, 197, 160)',
                    'rgb(212, 164, 235)',
                    'rgb(210, 245, 166)',
                    'rgb(118, 242, 242)',
                )
            ),
            array(
                'code' => 'infographic',
                'bgColor' => 'transparent',
                'themeColor' => array(
                    'rgb(193, 35, 43)',
                    'rgb(39, 114, 123)',
                    'rgb(252, 206, 16)',
                    'rgb(232, 124, 37)',
                    'rgb(181, 195, 52)',
                    'rgb(254, 132, 99)',
                    'rgb(155, 202, 99)',
                    'rgb(250, 216, 96)',
                    'rgb(243, 164, 59)',
                    'rgb(96, 192, 221)',
                    'rgb(215, 80, 75)',
                    'rgb(198, 229, 121)',
                    'rgb(244, 224, 1)',
                    'rgb(240, 128, 90)',
                    'rgb(38, 192, 192)',
                )
            ),
            array(
                'code' => 'macarons',
                'bgColor' => 'transparent',
                'themeColor' => array(
                    'rgb(46, 199, 201)',
                    'rgb(182, 162, 222)',
                    'rgb(90, 177, 239)',
                    'rgb(255, 185, 128)',
                    'rgb(216, 122, 128)',
                    'rgb(141, 152, 179)',
                    'rgb(229, 207, 13)',
                    'rgb(151, 181, 82)',
                    'rgb(149, 112, 109)',
                    'rgb(220, 105, 170)',
                    'rgb(7, 162, 164)',
                    'rgb(154, 127, 209)',
                    'rgb(88, 141, 213)',
                    'rgb(245, 153, 78)',
                    'rgb(192, 80, 80)',
                    'rgb(89, 103, 140)',
                    'rgb(201, 171, 0)',
                    'rgb(126, 176, 10)',
                    'rgb(111, 85, 83)',
                    'rgb(193, 64, 137)',
                )
            ),
            array(
                'code' => 'roma',
                'bgColor' => 'transparent',
                'themeColor' => array(
                    'rgb(224, 31, 84)',
                    'rgb(0, 24, 82)',
                    'rgb(245, 232, 200)',
                    'rgb(184, 210, 199)',
                    'rgb(198, 179, 142)',
                    'rgb(164, 216, 194)',
                    'rgb(243, 217, 153)',
                    'rgb(211, 117, 143)',
                    'rgb(220, 195, 146)',
                    'rgb(46, 71, 131)',
                    'rgb(130, 182, 233)',
                    'rgb(255, 99, 71)',
                    'rgb(160, 146, 241)',
                    'rgb(10, 145, 93)',
                    'rgb(234, 248, 137)',
                    'rgb(102, 153, 255)',
                    'rgb(255, 102, 102)',
                    'rgb(60, 179, 113)',
                    'rgb(213, 177, 88)',
                    'rgb(56, 182, 182)',
                )
            ),
            array(
                'code' => 'shine',
                'bgColor' => 'transparent',
                'themeColor' => array(
                    'rgb(193, 46, 52)',
                    'rgb(230, 182, 0)',
                    'rgb(0, 152, 217)',
                    'rgb(43, 130, 29)',
                    'rgb(0, 94, 170)',
                    'rgb(51, 156, 168)',
                    'rgb(205, 168, 25)',
                    'rgb(50, 164, 135)',
                )
            ),
            array(
                'code' => 'purple-passion',
                'bgColor' => 'rgb(91, 92, 110)',
                'themeColor' => array(
                    'rgb(138, 124, 168)',
                    'rgb(224, 152, 199)',
                    'rgb(143, 211, 232)',
                    'rgb(113, 102, 158)',
                    'rgb(204, 112, 175)',
                    'rgb(124, 180, 204)',
                )
            ),
            array(
                'code' => 'halloween',
                'bgColor' => 'rgb(51, 51, 51)',
                'themeColor' => array(
                    'rgb(255, 113, 94)',
                    'rgb(255, 175, 81)',
                    'rgb(255, 238, 81)',
                    'rgb(121, 127, 186)',
                    'rgb(113, 92, 135)',
                )
            ),
        );
    }

    public static function chartDefaultTypes () {
        return array(
            array(
                'id' => 'radar', 
                'name' => 'Radar',
            ), 
            array(
                'id' => 'pyramid', 
                'name' => 'Pyramid',
            ), 
            array(
                'id' => 'clustered_column', 
                'name' => 'Clustered column',
            ), 
            array(
                'id' => 'stacked_column', 
                'name' => 'Stacked column',
            ), 
            array(
                'id' => 'maps', 
                'name' => 'Maps',
            ), 
            array(
                'id' => 'card', 
                'name' => 'Card Default',
                'config' => array(
                    /* 'layoutPositioning' => array('width', 'height', 'leftPadding', 'rightPadding', 'topPadding', 'bottomPadding', 'alignment', 'textColor', 'fontSize') */
                )
            ), 
            array(
                'id' => 'card_vertical', 
                'name' => 'Card Vertical',
                'config' => array(
                    'layoutPositioning' => array('width', 'height', 'leftPadding', 'rightPadding', 'topPadding', 'bottomPadding', 'alignment', 'gridBorderRadius', 'iconFontSize', 'fontSize'),
                    'iconTextPosition' => array('iconLeftPadding', 'iconRightPadding', 'iconTopPadding', 'iconBottomPadding', 'iconAlignment', 'iconFontSize',),
                    'headerTextPosition' => array('headLeftPadding', 'headRightPadding', 'headTopPadding', 'headBottomPadding', 'headAlignment', 'headFontSize',),
                    'footerTextPosition' => array('footLeftPadding', 'footRightPadding', 'footTopPadding', 'footBottomPadding', 'footAlignment', 'footFontSize'),
                )
            )
        );
    }

    public static function chartDefaultAggerates() {
        return array(
            array(
                'id' => 'SUM', 
                'name' => 'SUM'
            ), 
            array(
                'id' => 'MAX', 
                'name' => 'MAX'
            ), 
            array(
                'id' => 'MIN', 
                'name' => 'MIN'
            ), 
            array(
                'id' => 'COUNT', 
                'name' => 'COUNT'
            ), 
            array(
                'id' => 'AVG', 
                'name' => 'AVG'
            )
        );
    }

    public static function chartTypesConfigration () {
        return array (
            array (
                'name' => Lang::line('Layout & positioning'),
                'code' => 'grid',
                'panels' => array (
                    array(
                        'name' => Lang::line('Grid Border Radius'),
                        'code' => 'grid_borderradius',
                        'dataType' => 'long',
                        'lookupType' => '',
                        'chooseType' => '',
                        'children' => '0',
                        'lookupData' => array(),
                    ),
                    array(
                        'name' => Lang::line('Grid width'),
                        'code' => 'grid_width',
                        'dataType' => 'long',
                        'lookupType' => '',
                        'chooseType' => '',
                        'children' => '0',
                        'lookupData' => array(),
                    ),
                    array(
                        'name' => Lang::line('Grid height'),
                        'code' => 'grid_height',
                        'dataType' => 'long',
                        'lookupType' => '',
                        'chooseType' => '',
                        'children' => '0',
                        'lookupData' => array(),
                    ),
                    array(
                        'name' => Lang::line('Grid left'),
                        'code' => 'grid_left',
                        'dataType' => 'long',
                        'lookupType' => '',
                        'chooseType' => '',
                        'children' => '0',
                        'lookupData' => array(),
                    ),
                    array(
                        'name' => Lang::line('Grid right'),
                        'code' => 'grid_right',
                        'dataType' => 'long',
                        'lookupType' => '',
                        'chooseType' => '',
                        'children' => '0',
                        'lookupData' => array(),
                    ),
                    array(
                        'name' => Lang::line('Grid top'),
                        'code' => 'grid_top',
                        'dataType' => 'long',
                        'lookupType' => '',
                        'chooseType' => '',
                        'children' => '0',
                        'lookupData' => array(),
                    ),
                    array(
                        'name' => Lang::line('Grid bottom'),
                        'code' => 'grid_bottom',
                        'dataType' => 'long',
                        'lookupType' => '',
                        'chooseType' => '',
                        'children' => '0',
                        'lookupData' => array(),
                    ),
                    array(
                        'name' => Lang::line('Alignment'),
                        'code' => 'grid_alignment',
                        'dataType' => 'string',
                        'lookupType' => 'combo',
                        'chooseType' => 'single',
                        'children' => '0',
                        'lookupData' => array(
                            array (
                                'name' => 'left',
                                'value' => 'left',
                            ),
                            array (
                                'name' => 'center',
                                'value' => 'center',
                            ),
                            array (
                                'name' => 'right',
                                'value' => 'right',
                            ),
                            array (
                                'name' => 'none',
                                'value' => 'none',
                            ),
                        ),
                    ),
                    array(
                        'name' => Lang::line('Arrage Tooltips'),
                        'code' => 'arrageTooltips',
                        'dataType' => 'string',
                        'lookupType' => 'checkbox',
                        'chooseType' => '',
                        'children' => '0',
                        'lookupData' => array(),
                    ),
                    array(
                        'name' => Lang::line('Font size'),
                        'code' => 'fontSize',
                        'dataType' => 'long',
                        'lookupType' => '',
                        'chooseType' => '',
                        'children' => '0',
                        'lookupData' => array(),
                    ),
                    array(
                        'name' => Lang::line('Font family'),
                        'code' => 'fontFamily',
                        'dataType' => 'string',
                        'lookupType' => '',
                        'chooseType' => '',
                        'children' => '0',
                        'lookupData' => array(),
                    ),
                    array(
                        'name' => Lang::line('TextColor'),
                        'code' => 'textColor',
                        'dataType' => 'string',
                        'lookupType' => '',
                        'chooseType' => '',
                        'children' => '0',
                        'lookupData' => array(),
                    ),
                ),
            ),
            array(
                'name' => Lang::line('iconTextPosition'),
                'code' => 'iconTextPosition',
                'panels' => array(
                    array(
                        'name' => Lang::line('icon left'),
                        'code' => 'iconLeftPadding',
                        'dataType' => 'long',
                        'lookupType' => '',
                        'chooseType' => '',
                        'children' => '0',
                        'lookupData' => array(),
                    ),
                    array(
                        'name' => Lang::line('icon right'),
                        'code' => 'iconRightPadding',
                        'dataType' => 'long',
                        'lookupType' => '',
                        'chooseType' => '',
                        'children' => '0',
                        'lookupData' => array(),
                    ),
                    array(
                        'name' => Lang::line('icon top'),
                        'code' => 'iconTopPadding',
                        'dataType' => 'long',
                        'lookupType' => '',
                        'chooseType' => '',
                        'children' => '0',
                        'lookupData' => array(),
                    ),
                    array(
                        'name' => Lang::line('icon bottom'),
                        'code' => 'iconBottomPadding',
                        'dataType' => 'long',
                        'lookupType' => '',
                        'chooseType' => '',
                        'children' => '0',
                        'lookupData' => array(),
                    ),
                    array(
                        'name' => Lang::line('iconAlignment'),
                        'code' => 'iconAlignment',
                        'dataType' => 'string',
                        'lookupType' => 'combo',
                        'chooseType' => 'single',
                        'children' => '0',
                        'lookupData' => array(
                            array (
                                'name' => 'left',
                                'value' => 'left',
                            ),
                            array (
                                'name' => 'center',
                                'value' => 'center',
                            ),
                            array (
                                'name' => 'right',
                                'value' => 'right',
                            ),
                            array (
                                'name' => 'none',
                                'value' => 'none',
                            ),
                        ),
                    ),
                    array(
                        'name' => Lang::line('iconer Font size'),
                        'code' => 'iconFontSize',
                        'dataType' => 'long',
                        'lookupType' => '',
                        'chooseType' => '',
                        'children' => '0',
                        'lookupData' => array(),
                    ),
                ),
            ),
            array(
                'name' => Lang::line('headerTextPosition'),
                'code' => 'headerTextPosition',
                'panels' => array(
                    array(
                        'name' => Lang::line('Head left'),
                        'code' => 'headLeftPadding',
                        'dataType' => 'long',
                        'lookupType' => '',
                        'chooseType' => '',
                        'children' => '0',
                        'lookupData' => array(),
                    ),
                    array(
                        'name' => Lang::line('Head right'),
                        'code' => 'headRightPadding',
                        'dataType' => 'long',
                        'lookupType' => '',
                        'chooseType' => '',
                        'children' => '0',
                        'lookupData' => array(),
                    ),
                    array(
                        'name' => Lang::line('Head top'),
                        'code' => 'headTopPadding',
                        'dataType' => 'long',
                        'lookupType' => '',
                        'chooseType' => '',
                        'children' => '0',
                        'lookupData' => array(),
                    ),
                    array(
                        'name' => Lang::line('Head bottom'),
                        'code' => 'headBottomPadding',
                        'dataType' => 'long',
                        'lookupType' => '',
                        'chooseType' => '',
                        'children' => '0',
                        'lookupData' => array(),
                    ),
                    array(
                        'name' => Lang::line('headAlignment'),
                        'code' => 'headAlignment',
                        'dataType' => 'string',
                        'lookupType' => 'combo',
                        'chooseType' => 'single',
                        'children' => '0',
                        'lookupData' => array(
                            array (
                                'name' => 'left',
                                'value' => 'left',
                            ),
                            array (
                                'name' => 'center',
                                'value' => 'center',
                            ),
                            array (
                                'name' => 'right',
                                'value' => 'right',
                            ),
                            array (
                                'name' => 'none',
                                'value' => 'none',
                            ),
                        ),
                    ),
                    array(
                        'name' => Lang::line('Header Font size'),
                        'code' => 'headFontSize',
                        'dataType' => 'long',
                        'lookupType' => '',
                        'chooseType' => '',
                        'children' => '0',
                        'lookupData' => array(),
                    ),
                ),
            ),
            array (
                'name' => Lang::line('footerTextPosition'),
                'code' => 'footerTextPosition',
                'panels' => array(
                    array(
                        'name' => Lang::line('foot left'),
                        'code' => 'footLeftPadding',
                        'dataType' => 'long',
                        'lookupType' => '',
                        'chooseType' => '',
                        'children' => '0',
                        'lookupData' => array(),
                    ),
                    array(
                        'name' => Lang::line('foot right'),
                        'code' => 'footRightPadding',
                        'dataType' => 'long',
                        'lookupType' => '',
                        'chooseType' => '',
                        'children' => '0',
                        'lookupData' => array(),
                    ),
                    array(
                        'name' => Lang::line('foot top'),
                        'code' => 'footTopPadding',
                        'dataType' => 'long',
                        'lookupType' => '',
                        'chooseType' => '',
                        'children' => '0',
                        'lookupData' => array(),
                    ),
                    array(
                        'name' => Lang::line('foot bottom'),
                        'code' => 'footBottomPadding',
                        'dataType' => 'long',
                        'lookupType' => '',
                        'chooseType' => '',
                        'children' => '0',
                        'lookupData' => array(),
                    ),
                    array(
                        'name' => Lang::line('footAlignment'),
                        'code' => 'footAlignment',
                        'dataType' => 'string',
                        'lookupType' => 'combo',
                        'chooseType' => 'single',
                        'children' => '0',
                        'lookupData' => array(
                            array (
                                'name' => 'left',
                                'value' => 'left',
                            ),
                            array (
                                'name' => 'center',
                                'value' => 'center',
                            ),
                            array (
                                'name' => 'right',
                                'value' => 'right',
                            ),
                            array (
                                'name' => 'none',
                                'value' => 'none',
                            ),
                        ),
                    ),
                    array(
                        'name' => Lang::line('footer Font size'),
                        'code' => 'footFontSize',
                        'dataType' => 'long',
                        'lookupType' => '',
                        'chooseType' => '',
                        'children' => '0',
                        'lookupData' => array(),
                    ),
                ),
            ),
            array(
                'name' => Lang::line('Title'),
                'code' => 'title',
                'panels' => array(
                    array(
                        'name' => Lang::line('Title'),
                        'code' => 'title_text',
                        'dataType' => 'string',
                        'lookupType' => '',
                        'chooseType' => '',
                        'children' => '0',
                        'lookupData' => array(),
                    ),
                    array(
                        'name' => Lang::line('Sub text'),
                        'code' => 'title_subtext',
                        'dataType' => 'string',
                        'lookupType' => '',
                        'chooseType' => '',
                        'children' => '0',
                        'lookupData' => array(),
                    ),
                    array(
                        'name' => Lang::line('Title position'),
                        'code' => 'title_left',
                        'dataType' => 'string',
                        'lookupType' => 'combo',
                        'chooseType' => 'single',
                        'children' => '0',
                        'lookupData' => array(
                            array (
                                'name' => 'left',
                                'value' => 'left',
                            ),
                            array (
                                'name' => 'center',
                                'value' => 'center',
                            ),
                            array (
                                'name' => 'right',
                                'value' => 'right',
                            ),
                            array (
                                'name' => 'none',
                                'value' => 'none',
                            ),
                        ),
                    ),
                    array(
                        'name' => Lang::line('Sub text Style'),
                        'code' => 'title_subtextStyle',
                        'dataType' => 'string',
                        'lookupType' => '',
                        'chooseType' => '',
                        'children' => '0',
                        'children' => '1',
                        'panels' => array(
                            array(
                                'name' => Lang::line('Sub text color'),
                                'code' => 'title_subtextStyle_color',
                                'dataType' => 'string',
                                'lookupType' => '',
                                'chooseType' => '',
                                'children' => '0',
                                'children' => '0',
                            )
                        ),
                        'lookupData' => array(),
                    )
                )
            ),
            array(
                'name' => Lang::line('Legend'),
                'code' => 'legend',
                'panels' => array(
                    array(
                        'name' => Lang::line('Legend show'),
                        'code' => 'legend_show',
                        'dataType' => 'string',
                        'lookupType' => 'checkbox',
                        'chooseType' => '',
                        'children' => '0',
                        'lookupData' => array(),
                    ),
                    array(
                        'name' => Lang::line('Legend orient'),
                        'code' => 'legend_orient',
                        'dataType' => 'string',
                        'lookupType' => 'combo',
                        'chooseType' => 'single',
                        'children' => '0',
                        'lookupData' => array(
                            array (
                                'name' => 'horizontal',
                                'value' => 'horizontal',
                            ),
                            array (
                                'name' => 'vertical',
                                'value' => 'vertical',
                            ),
                        ),
                    ),
                    array(
                        'name' => Lang::line('Legend position'),
                        'code' => 'legend_left',
                        'dataType' => 'string',
                        'lookupType' => 'combo',
                        'chooseType' => 'single',
                        'children' => '0',
                        'lookupData' => array(
                            array (
                                'name' => 'left',
                                'value' => 'left',
                            ),
                            array (
                                'name' => 'center',
                                'value' => 'center',
                            ),
                            array (
                                'name' => 'right',
                                'value' => 'right',
                            ),
                            array (
                                'name' => 'none',
                                'value' => 'none',
                            ),
                        ),
                    ),
                )
            ),
            array(
                'name' => Lang::line('axisLabel'),
                'code' => 'axisLabel',
                'panels' => array(
                    array(
                        'name' => Lang::line('axisLabel rotate'),
                        'code' => 'axisLabel_rotate',
                        'dataType' => 'long',
                        'lookupType' => '',
                        'chooseType' => '',
                        'children' => '0',
                        'lookupData' => array(),
                    ),
                    array(
                        'name' => Lang::line('axisLabel show'),
                        'code' => 'axisLabel_show',
                        'dataType' => 'string',
                        'lookupType' => 'checkbox',
                        'chooseType' => '',
                        'children' => '0',
                        'lookupData' => array(),
                    ),
                )
            ),
            array(
                'name' => Lang::line('yAxis'),
                'code' => 'yAxis',
                'panels' => array(
                    array(
                        'name' => Lang::line('yAxis rotate'),
                        'code' => 'yAxis_show',
                        'dataType' => 'string',
                        'lookupType' => 'checkbox',
                        'chooseType' => '',
                        'children' => '0',
                        'lookupData' => array(),
                    ),
                )
            ),
            array(
                'name' => Lang::line('xAxis'),
                'code' => 'xAxis',
                'panels' => array(
                    array(
                        'name' => Lang::line('xAxis rotate'),
                        'code' => 'xAxis_show',
                        'dataType' => 'string',
                        'lookupType' => 'checkbox',
                        'chooseType' => '',
                        'children' => '0',
                        'lookupData' => array(),
                    ),
                )
            ),
            array (
                'name' => Lang::line('Appearance'),
                'code' => 'appearance',
                'panels' => array(
                    array(
                        'name' => Lang::line('Smooth'),
                        'code' => 'smooth',
                        'dataType' => 'string',
                        'lookupType' => 'checkbox',
                        'chooseType' => '',
                        'children' => '0',
                        'lookupData' => array(),
                    ),
                    array(
                        'name' => Lang::line('XY Reverse'),
                        'code' => 'xyReverse',
                        'dataType' => 'string',
                        'lookupType' => 'checkbox',
                        'chooseType' => '',
                        'children' => '0',
                        'lookupData' => array(),
                    ),
                )
            )
        );
    }

    public static function renderConfigControl($panel, $graphJsonConfig, $panelCode) {
        $html = '';
        
        if (issetParamArray($panel['children']) && $panel['children']) {
            foreach ($panel['children'] as $key => $row) {
                $panelCode = $row['TYPE_CODE'];
                $html .= self::renderConfigControl($row, $graphJsonConfig, $panelCode);
            }
        } else {
            $panelCode =  Str::replace('.', '_', $panel['TYPE_CODE']);
            $html .= '<div class="form-group row configration '. $panelCode .'">';
                $html .= Form::label(array('text'=> $panel['LABEL_NAME'], 'class'=>'col-form-label col-md-auto text-right pr-0 pt-1', 'style' => 'width: 100px', 'for' => $panelCode));
                $html .= '<div class="col">';
                    switch ($panel['SHOW_TYPE']) {
                        case 'combo':
                            $form = Form::select(array(
                                'class' => 'form-control form-control-sm', 
                                'name' => $panelCode,
                                'id' => $panelCode,
                                'data' => $panel['lookupData'], 
                                'op_value' => 'value', 
                                'op_text' => 'name', 
                                /* 'text' => 'notext',  */
                                'value' => issetParam($graphJsonConfig['chartConfig'][$panelCode])
                            ));
                            break;
                        case 'checkbox':
                            $array = array(
                                'class' => 'form-control form-control-sm', 
                                'name' => $panelCode,
                                'id' => $panelCode,
                                'value' => '1', 
                                'saved_val' => issetParam($graphJsonConfig['chartConfig'][$panelCode]) ? '1' : ''
                            );
                            
                            if (issetParam($graphJsonConfig['chartConfig'][$panelCode]) === '1') {
                                $array['checked'] = 'checked';
                            }
    
                            $form =  Form::checkbox($array);
                            break;
                        
                        default:
                            $form = Form::text(array(
                                'class' => 'form-control form-control-sm ' . $panel['SHOW_TYPE'] . 'Init', 
                                'name' => $panelCode, 
                                'id' => $panelCode,
                                'value' => issetParam($graphJsonConfig['chartConfig'][$panelCode])
                            ));
                            break;
                    }
                    $html .= $form;
                $html .= '</div>';
            $html .= '</div>';
        }
        return $html;
    }

    public static function transformTypeDatas($chartTypesConfigration, $chartTypes, $tmpArray = array(), $tmpKey = '') {
        foreach ($chartTypes['children'] as $row) {
            $tmp = array (
                        'name' => Lang::line($row['code']),
                        'code' => $row['code'],
                        'lookupType ' => '',
                        'dataType' => 'string',
                        'lookupType' => '',
                        'chooseType' => '',
                        'lookupData' => array(),
                        'panels' => array()
                    );

            if (issetParamArray($row['children'])) {
                $tempArr = Info::transformTypeDatas($tmp['panels'], $row, $tmpArray, $row['code']);

                $tmp['panels'] = $tempArr['panels'];
                $tmpKey = $tempArr['tmpKey'];
                $tempArr = $tempArr['tmpArray'];
            }

            if (!in_array($row['code'], $tmpArray)) {
                array_push($tmpArray, $row['code']);
                array_push($chartTypesConfigration, $tmp);
            }
        }
        
        return array('panels' => $chartTypesConfigration, 'tmpArray' => $tmpArray, 'tmpKey' => $tmpKey);
    }

    public static function transformTypeKeyDatas($chartTypesConfigration, $chartTypes, $tmpKey) {
        if (issetParamArray($chartTypes['children'])) {
            foreach ($chartTypes['children'] as $row) {
                $tmp = $tmpKey . '_' . $row['code'];
                if (issetParamArray($row['children'])) {
                    $tmp = Info::transformTypeKey($tmp, $row, $tmp);
                }
    
                array_push($chartTypesConfigration, $tmp);
            }
        }

        return $chartTypesConfigration;
    }

    public static function transformTypeKey($tmpKey, $chartTypes) {
        foreach ($chartTypes['children'] as $row) {
            $tmp = $tmpKey . '_' . $row['code'];
            if (issetParamArray($row['children'])) {
                $tmp = Info::transformTypeKey($tmp, $row);
            }

            $tmpKey = $tmp;
        }
        
        return $tmpKey;
    }
}