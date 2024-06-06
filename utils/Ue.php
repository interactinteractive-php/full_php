<?php

if (!defined('_VALID_PHP'))
    exit('Direct access to this location is not allowed.');

class Ue extends Model {

    public static function sessionUserId() {
        return Session::get(SESSION_PREFIX . 'userid');
    }

    public static function sessionUserKeyId() {
        return Session::get(SESSION_PREFIX . 'userkeyid');
    }

    public static function sessionRoleId() {
        return Session::get(SESSION_PREFIX . 'roleid');
    }

    public static function appUserSessionId() {
        return Session::get(SESSION_PREFIX . 'appusersessionid');
    }

    public static function sessionEmployeeId() {
        return Session::get(SESSION_PREFIX . 'employeeid');
    }

    public static function sessionSemisterYear() {
        return Session::get(SESSION_PREFIX . 'semisterYear');
    }

    public static function sessionEmployeeKeyId() {
        return Session::get(SESSION_PREFIX . 'employeekeyid');
    }

    public static function getSessionPersonName() {
        return Session::get(SESSION_PREFIX . 'firstname');
    }

    public static function getSessionUserName() {
        return Session::get(SESSION_PREFIX . 'username');
    }

    public static function getSessionPersonWithLastName() {
        return Session::get(SESSION_PREFIX . 'personname');
    }

    public static function getSessionPositionName() {
        return Session::get(SESSION_PREFIX . 'position');
    }

    public static function getSessionPhoneNumber() {
        return Session::get(SESSION_PREFIX . 'phone');
    }

    public static function getSessionEmail() {
        return Session::get(SESSION_PREFIX . 'email');
    }

    public static function sessionFiscalPeriodStartDateTime() {
        return Session::isCheck(SESSION_PREFIX . 'periodStartDate') ? Session::get(SESSION_PREFIX . 'periodStartDate') . ' 00:00:00' : null;
    }

    public static function sessionFiscalPeriodEndDateTime() {
        return Session::isCheck(SESSION_PREFIX . 'periodEndDate') ? Session::get(SESSION_PREFIX . 'periodEndDate') . ' 00:00:00' : null;
    }

    public static function sessionFiscalPeriodStartDate() {
        return Session::get(SESSION_PREFIX . 'periodStartDate');
    }

    public static function sessionFiscalPeriodEndDate() {
        return Session::get(SESSION_PREFIX . 'periodEndDate');
    }

    public static function sessionFiscalPeriodId() {
        return Session::get(SESSION_PREFIX . 'periodId');
    }

    public static function sessionFiscalPeriodYearId() {
        return Session::get(SESSION_PREFIX . 'periodYearId');
    }

    public static function sessionId() {
        return Session::get(SESSION_PREFIX . 'SSID');
    }

    public static function sessionCustomerId() {
        return Session::get(SESSION_PREFIX_CUST . 'customerId');
    }

    public static function sessionScenarioId() {
        $eaScenarioId = Session::get(SESSION_PREFIX . 'eaScenarioId');
        return $eaScenarioId ? $eaScenarioId : 0;
    }

    public static function sessionCustomerName() {
        return Session::isCheck(SESSION_PREFIX_CUST . 'userid') ? Session::get(SESSION_PREFIX_CUST . 'customerName') : null;
    }

    public static function sessionisLogin() {
        return Session::isCheck(SESSION_PREFIX_CUST . 'isLogin') ? true : false;
    }

    public static function sessionIsUseFolderPermission() {
        return Session::get(SESSION_PREFIX . 'isUseFolderPermission') == '1' ? true : false;
    }

    public static function getSessionUserKeyName($keyName) {
        return Session::isCheck(SESSION_PREFIX . 'userKey' . $keyName) ? Session::get(SESSION_PREFIX . 'userKey' . $keyName) : '&nbsp;';
    }

    public static function getFiscalPeriodPrevMonthId() {
        if ($startDate = Ue::sessionFiscalPeriodStartDate()) {

            global $db;

            $id = $db->GetOne("
                SELECT 
                    ID  
                FROM FIN_FISCAL_PERIOD   
                WHERE TYPE_ID = 1 
                    AND END_DATE < " . $db->ToDate("'$startDate'", 'YYYY-MM-DD') . " 
                ORDER BY END_DATE DESC");

            if ($id) {
                return $id;
            }
        }
        return null;
    }

    public static function currentFiscalPeriodId() {
        global $db;

        $id = $db->GetOne("
            SELECT 
                ID  
            FROM FIN_FISCAL_PERIOD   
            WHERE IS_CURRENT = 1 
            ORDER BY END_DATE ASC");

        if ($id) {
            return $id;
        }

        return null;
    }

    public static function currentFiscalPeriodStartDate() {
        global $db;

        $date = $db->GetOne("
            SELECT 
                START_DATE  
            FROM FIN_FISCAL_PERIOD   
            WHERE IS_CURRENT = 1 
            ORDER BY END_DATE ASC");

        if ($date) {
            return Date::formatter($date, 'Y-m-d');
        }

        return null;
    }

    public static function currentFiscalPeriodEndDate() {
        global $db;

        $date = $db->GetOne("
            SELECT 
                END_DATE  
            FROM FIN_FISCAL_PERIOD   
            WHERE IS_CURRENT = 1 
            ORDER BY END_DATE ASC");

        if ($date) {
            return Date::formatter($date, 'Y-m-d');
        }

        return null;
    }

    public static function sessionPositionKeyId() {
        if ($empId = Ue::sessionEmployeeId()) {

            global $db;

            $posKeyId = $db->GetOne("SELECT POSITION_KEY_ID FROM HRM_EMPLOYEE_KEY WHERE IS_ACTIVE = 1 AND EMPLOYEE_ID = " . $db->Param(0), array($empId));

            if ($posKeyId) {
                return $posKeyId;
            }
        }

        return null;
    }

    public static function sessionDepartmentId() {
        if ($empId = Ue::sessionEmployeeId()) {

            global $db;

            $depId = $db->GetOne("SELECT DEPARTMENT_ID FROM HRM_EMPLOYEE_KEY WHERE IS_ACTIVE = 1 AND EMPLOYEE_ID = " . $db->Param(0), array($empId));

            if ($depId) {
                return $depId;
            }
        }

        return null;
    }

    public static function sessionDepartmentCode() {
        if ($empId = Ue::sessionEmployeeId()) {

            global $db;

            $depCode = $db->GetOne("
                SELECT 
                    ORG.DEPARTMENT_CODE 
                FROM HRM_EMPLOYEE_KEY EK 
                    INNER JOIN ORG_DEPARTMENT ORG ON ORG.DEPARTMENT_ID = EK.DEPARTMENT_ID 
                WHERE EK.IS_ACTIVE = 1 
                    AND EK.EMPLOYEE_ID = " . $db->Param(0),
                    array($empId));

            if ($depCode != '') {
                return $depCode;
            }
        }

        return null;
    }

    public static function sessionDepartmentName() {
        if ($empId = Ue::sessionEmployeeId()) {

            global $db;

            $depName = $db->GetOne("
                SELECT 
                    ORG.DEPARTMENT_NAME  
                FROM HRM_EMPLOYEE_KEY EK 
                    INNER JOIN ORG_DEPARTMENT ORG ON ORG.DEPARTMENT_ID = EK.DEPARTMENT_ID 
                WHERE EK.IS_ACTIVE = 1 
                    AND EK.EMPLOYEE_ID = " . $db->Param(0),
                    array($empId));

            if ($depName) {
                return $depName;
            }
        }

        return null;
    }

    public static function sessionUserKeyDepartmentName() {
        if ($userKeyId = Ue::sessionUserKeyId()) {

            global $db;

            $depName = $db->GetOne("
                SELECT 
                    ORG.DEPARTMENT_NAME 
                FROM UM_USER US
                    INNER JOIN ORG_DEPARTMENT ORG ON ORG.DEPARTMENT_ID = US.DEPARTMENT_ID
                WHERE US.USER_ID = " . $db->Param(0), array($userKeyId));

            if ($depName) {
                return $depName;
            }
        }

        return null;
    }

    public static function sessionUserKeyDepartmentId() {
        if (Session::isCheck(SESSION_PREFIX . 'departmentid')) {

            return Session::get(SESSION_PREFIX . 'departmentid');
        } elseif ($userKeyId = Ue::sessionUserKeyId()) {

            global $db;

            $depId = $db->GetOne("SELECT DEPARTMENT_ID FROM UM_USER WHERE USER_ID = " . $db->Param(0), array($userKeyId));

            if ($depId) {
                return $depId;
            }
        }

        return null;
    }

    public static function sessionUserKeyDepartmentCode() {
        if ($userKeyId = Ue::sessionUserKeyId()) {

            global $db;

            $depCode = $db->GetOne("
                SELECT 
                    ORG.DEPARTMENT_CODE 
                FROM UM_USER UM 
                    INNER JOIN ORG_DEPARTMENT ORG ON ORG.DEPARTMENT_ID = UM.DEPARTMENT_ID 
                WHERE UM.USER_ID = " . $db->Param(0), array($userKeyId));

            if ($depCode != '') {
                return $depCode;
            }
        }

        return null;
    }
    
    public static function sessionRoleCode() {
        if ($userRoleId = Ue::sessionRoleId()) {

            global $db;

            $rCode = $db->GetOne("
                SELECT 
                    ROLE_CODE 
                FROM UM_ROLE
                WHERE ROLE_ID = " . $db->Param(0), array($userRoleId));

            if ($rCode != '') {
                return $rCode;
            }
        }

        return '';        
    }    

    public static function sessionStoreId() {
        if ($departmentId = Ue::sessionUserKeyDepartmentId()) {

            global $db;

            $storeId = $db->GetOne("SELECT STORE_ID FROM SM_STORE WHERE DEPARTMENT_ID = " . $db->Param(0), array($departmentId));

            if ($storeId) {
                return $storeId;
            }
        }

        return null;
    }

    public static function getSessionFiscalPeriodName() {
        if ($fiscalPeriodId = Ue::sessionFiscalPeriodId()) {

            global $db;

            $periodName = $db->GetOne("SELECT PERIOD_NAME FROM FIN_FISCAL_PERIOD WHERE ID = " . $db->Param(0), array($fiscalPeriodId));

            if ($periodName) {
                return $periodName;
            }
        }

        return null;
    }

    public static function getSessionPhoto($attr = '') {
        
        if (Session::isCheck(SESSION_PREFIX . 'picture')) {

            $photo = Session::get(SESSION_PREFIX . 'picture');

            if (!empty($photo) && file_exists($photo)) {
                return '<img src="' . $photo . '" ' . $attr . '>';
            }
        }

        return '<img src="assets/core/global/img/user.png" ' . $attr . '/>';
    }

    public static function getFullUrlPhoto($path, $attr = '') {
        if ($path && file_exists($path)) {
            return '<img src="api/image_thumbnail?width=100&src=' . $path . '" ' . $attr . '>';
        } else {
            return '<img src="assets/core/global/img/user.png" ' . $attr . '/>';
        }
    }

    public static function getPhoto($picture, $path, $attr = '') {
        $photoPath = $path . $picture;

        if ($photoPath && file_exists($photoPath)) {
            return '<img src="' . $photoPath . '" ' . $attr . '>';
        } else {
            return '<img src="assets/core/global/img/user.png" ' . $attr . '/>';
        }
    }

    public static function loginCacheClear() {
        $tmp_dir = Mdcommon::getCacheDirectory();
        $userId = Ue::sessionUserId();
        $userKeyId = Ue::sessionUserKeyId();

        $leftMenuFiles = glob($tmp_dir . "/*/le/leftmenu_" . $userKeyId . "_*.txt");
        foreach ($leftMenuFiles as $leftMenuFile) {
            @unlink($leftMenuFile);
        }
        $topMenuFiles = glob($tmp_dir . "/*/to/topmenu_" . $userKeyId . ".txt");
        foreach ($topMenuFiles as $topMenuFile) {
            @unlink($topMenuFile);
        }
        $topMenuFiles = glob($tmp_dir . "/*/to/topmenumodule_" . $userKeyId . ".txt");
        foreach ($topMenuFiles as $topMenuFile) {
            @unlink($topMenuFile);
        }
        $appMenuFiles = glob($tmp_dir . "/*/ap/appmenu_" . $userKeyId . ".txt");
        foreach ($appMenuFiles as $appMenuFile) {
            @unlink($appMenuFile);
        }
        $appSubMenuFiles = glob($tmp_dir . "/*/ap/appmenu_" . $userKeyId . "_*.txt");
        foreach ($appSubMenuFiles as $appSubMenuFile) {
            @unlink($appSubMenuFile);
        }
        
        $leftMenuFiles = glob($tmp_dir . "/*/le/leftmenu_" . $userId . "_*.txt");
        foreach ($leftMenuFiles as $leftMenuFile) {
            @unlink($leftMenuFile);
        }
        $topMenuFiles = glob($tmp_dir . "/*/to/topmenu_" . $userId . ".txt");
        foreach ($topMenuFiles as $topMenuFile) {
            @unlink($topMenuFile);
        }
        $topMenuFiles = glob($tmp_dir . "/*/to/topmenumodule_" . $userId . ".txt");
        foreach ($topMenuFiles as $topMenuFile) {
            @unlink($topMenuFile);
        }
        $appMenuFiles = glob($tmp_dir . "/*/ap/appmenu_" . $userId . ".txt");
        foreach ($appMenuFiles as $appMenuFile) {
            @unlink($appMenuFile);
        }
        $appSubMenuFiles = glob($tmp_dir . "/*/ap/appmenu_" . $userId . "_*.txt");
        foreach ($appSubMenuFiles as $appSubMenuFile) {
            @unlink($appSubMenuFile);
        }
        
        $finAccountDvFiles = glob($tmp_dir . "/*/fi/finAccountDv_" . $userId . ".txt");
        foreach ($finAccountDvFiles as $finAccountDvFile) {
            @unlink($finAccountDvFile);
        }
        $dvUserFiles = glob($tmp_dir . "/*/dv/dvUser_" . $userKeyId . "_*.txt");
        foreach ($dvUserFiles as $dvUserFile) {
            @unlink($dvUserFile);
        }

        return true;
    }

    public static function startFiscalPeriod() {
        
        if (Config::getFromCache('CONFIG_FISCAL_PERIOD')) {

            global $db;
            $userKeyId = Ue::sessionUserKeyId();

            $userRow = $db->GetRow("
                SELECT 
                    FP.ID, 
                    FP.START_DATE, 
                    FP.END_DATE, 
                    FP.PARENT_ID 
                FROM FIN_FISCAL_PERIOD_USER PU 
                    INNER JOIN FIN_FISCAL_PERIOD FP ON FP.ID = PU.FISCAL_PERIOD_ID 
                WHERE PU.USER_ID = " . $db->Param(0) . "  
                ORDER BY PU.CREATED_DATE DESC", array($userKeyId));

            if ($userRow) {

                if (empty($userRow['PARENT_ID'])) {
                    Session::set(SESSION_PREFIX . 'periodYearId', $userRow['ID']);
                } else {
                    Session::set(SESSION_PREFIX . 'periodYearId', Ue::getFiscalPeriodYearId($userRow['PARENT_ID']));
                }

                Session::set(SESSION_PREFIX . 'periodId', $userRow['ID']);
                Session::set(SESSION_PREFIX . 'periodStartDate', Date::formatter($userRow['START_DATE'], 'Y-m-d'));
                Session::set(SESSION_PREFIX . 'periodEndDate', Date::formatter($userRow['END_DATE'], 'Y-m-d'));
                
            } else {

                $date = Date::currentDate('Y-m-d');

                $row = $db->GetRow("
                    SELECT 
                        ID, START_DATE, END_DATE, PARENT_ID
                    FROM FIN_FISCAL_PERIOD 
                    WHERE TYPE_ID = 1  
                        AND (
                            START_DATE <= " . $db->ToDate("'$date'", 'YYYY-MM-DD') . " AND 
                            END_DATE >= " . $db->ToDate("'$date'", 'YYYY-MM-DD') . ")");

                if ($row) {

                    $data = array(
                        'ID' => getUID(),
                        'FISCAL_PERIOD_ID' => $row['ID'],
                        'USER_ID' => $userKeyId,
                        'CREATED_DATE' => Date::currentDate('Y-m-d H:i:s')
                    );
                    $db->AutoExecute('FIN_FISCAL_PERIOD_USER', $data);

                    if (empty($userRow['PARENT_ID'])) {
                        Session::set(SESSION_PREFIX . 'periodYearId', $row['ID']);
                    } else {
                        Session::set(SESSION_PREFIX . 'periodYearId', Ue::getFiscalPeriodYearId($row['PARENT_ID']));
                    }

                    Session::set(SESSION_PREFIX . 'periodId', $row['ID']);
                    Session::set(SESSION_PREFIX . 'periodStartDate', Date::formatter($row['START_DATE'], 'Y-m-d'));
                    Session::set(SESSION_PREFIX . 'periodEndDate', Date::formatter($row['END_DATE'], 'Y-m-d'));
                }
            }
        }

        return true;
    }

    public static function getFiscalPeriodYearId($parentId) {
        if (empty($parentId)) {
            return $parentId;
        }

        global $db;

        $row = $db->GetRow("SELECT ID, PARENT_ID, TYPE_ID FROM FIN_FISCAL_PERIOD WHERE ID = " . $db->Param(0), array($parentId));

        if ($row) {
            if (empty($row['PARENT_ID']) || $row['TYPE_ID'] == 4) {
                return $parentId;
            } else {
                return Ue::getFiscalPeriodYearId($row['PARENT_ID']);
            }
        }
    }

    public static function createSessionInfo() {

        $userId = Ue::sessionUserId();

        if ($userId == '1' || Ue::sessionRoleId() == '1') {
            $isAdmin = 1;
        } else {
            $isAdmin = 0;
        }

        $userKeyId = Ue::sessionUserKeyId();
        $employeeId = Ue::sessionEmployeeId();
        $employeeKeyId = Ue::sessionEmployeeKeyId();
        $userKeyDepartmentId = Ue::sessionUserKeyDepartmentId();

        $userKeyId = ($userKeyId ? $userKeyId : 'null');
        $userId = ($userId ? $userId : 'null');
        $employeeId = ($employeeId ? $employeeId : 'null');
        $employeeKeyId = ($employeeKeyId ? $employeeKeyId : 'null');
        $userKeyDepartmentId = ($userKeyDepartmentId ? $userKeyDepartmentId : 'null');

        $sql = "INSERT INTO SESSION_INFO (USER_ID, SYSTEM_USER_ID, EMPLOYEE_ID, EMPLOYEE_KEY_ID, DEPARTMENT_ID, IS_ADMIN) ";
        $sql .= "VALUES ($userKeyId, $userId, $employeeId, $employeeKeyId, $userKeyDepartmentId, $isAdmin)";

        return $sql;
    }

    public static function getProfilePhoto($path, $attr = '') {
        if ($path && file_exists($path)) {
            return '<img src="' . $path . '" ' . $attr . '>';
        } else {
            return '<img src="assets/custom/css/social/img/profile_user.png" ' . $attr . '/>';
        }
    }

    public static function getSessionProfilePhoto($attr = '') {
        if (Session::isCheck(SESSION_PREFIX . 'picture')) {

            $photo = Session::get(SESSION_PREFIX . 'picture');

            if (!empty($photo) && file_exists($photo)) {
                return '<img src="' . $photo . '" ' . $attr . '>';
            }
        }

        return '<img src="assets/custom/css/social/img/profile_user.png" ' . $attr . '/>';
    }

    public static function sessionUserRoleCode() {
        return Session::isCheck(SESSION_PREFIX . '_userRoleCode') ? Session::get(SESSION_PREFIX . '_userRoleCode') : null;
    }

    public static function sessionUserRoleTwo() {
        return Session::isCheck(SESSION_PREFIX . '_userTwoRole') ? Session::get(SESSION_PREFIX . '_userTwoRole') : null;
    }

    public static function sessionPassword() {
        return Session::isCheck(SESSION_PREFIX . 'password') ? Session::get(SESSION_PREFIX . 'password') : null;
    }

    public static function sessionPasswordCheck() {
        return Session::isCheck(SESSION_PREFIX . 'passwordCheck') ? Session::get(SESSION_PREFIX . 'passwordCheck') : null;
    }

    public static function sessionSecondaryCurrencyId() {
        $sessionValues = Session::get(SESSION_PREFIX.'sessionValues');
            
        if ($sessionValues && array_key_exists('sessionsecondarycurrencyid', $sessionValues)) {
            return $sessionValues['sessionsecondarycurrencyid'];
        }        
        return '';
    }    

    public static function sessionSecondaryCurrencyCode() {
        $sessionValues = Session::get(SESSION_PREFIX.'sessionValues');
            
        if ($sessionValues && array_key_exists('sessionsecondarycurrencycode', $sessionValues)) {
            return $sessionValues['sessionsecondarycurrencycode'];
        }        
        return '';
    }    

    public static function sessionPrimaryCurrencyId() {
        $sessionValues = Session::get(SESSION_PREFIX.'sessionValues');
            
        if ($sessionValues && array_key_exists('sessionprimarycurrencyid', $sessionValues)) {
            return $sessionValues['sessionprimarycurrencyid'];
        }        
        return '';
    }    
    
    public static function isUseChat() {
        
        $key = SESSION_PREFIX . 'isUseChat';
        
        if (array_key_exists($key, $_SESSION)) {
            
            return Session::get($key) == '1' ? true : false;
        } 
        
        return true;
    }

}
