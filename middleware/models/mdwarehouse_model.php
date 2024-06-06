<?php if (!defined('_VALID_PHP')) exit('Direct access to this location is not allowed.');

class Mdwarehouse_Model extends Model {

    public function __construct() {
        parent::__construct();
    }

    public function getActiveWareHouseListModel() {
        $result = $this->db->GetAll("
            SELECT 
                WAREHOUSE_ID, 
                WAREHOUSE_CODE, 
                WAREHOUSE_NAME
            FROM IM_WAREHOUSE 
            WHERE IS_ACTIVE = 1");
        return $result;
    }

    public function getActiveWHLocationListModel($locationId = '', $warehouseId = '') {
        $result = $this->db->GetAll("
                                    SELECT 
                                        * 
                                    FROM  
                                        WH_LOCATION 
                                    WHERE 
                                        WAREHOUSE_ID=" . $warehouseId . " AND 
                                        PARENT_ID IS NULL 
                                    ORDER BY LOCATION_CODE ASC");

        foreach ($result as $key => $row) {
            self::$whLocation[$key]['LOCATION_ID'] = $row['LOCATION_ID'];
            self::$whLocation[$key]['LOCATION_CODE'] = $row['LOCATION_CODE'];
            self::$whLocation[$key]['LOCATION_NAME'] = $row['LOCATION_NAME'];
            self::$whLocation[$key]['LOCATION_TYPE_ID'] = $row['LOCATION_TYPE_ID'];
            self::$whLocation[$key]['WAREHOUSE_ID'] = $row['WAREHOUSE_ID'];
            $def = $key + 1;
            $space = ' - ';
            self::WHLocationListParentModel($row['LOCATION_ID'], $warehouseId, $space, $def);
        }
        return self::$whLocation;
    }

    public function WHLocationListParentModel($locationId = '', $warehouseId = '', $space = '', $def) {
        $result = $this->db->GetAll("
                                    SELECT 
                                        * 
                                    FROM  
                                        WH_LOCATION 
                                    WHERE 
                                        WAREHOUSE_ID=" . $warehouseId . " AND 
                                        PARENT_ID=" . $locationId . " 
                                    ORDER BY LOCATION_CODE ASC");

        foreach ($result as $key => $row) {
            self::$whLocation[$def]['LOCATION_ID'] = $row['LOCATION_ID'];
            self::$whLocation[$def]['LOCATION_CODE'] = '  ' . $space . $row['LOCATION_CODE'];
            self::$whLocation[$def]['LOCATION_NAME'] = $row['LOCATION_NAME'];
            self::$whLocation[$def]['LOCATION_TYPE_ID'] = $row['LOCATION_TYPE_ID'];
            self::$whLocation[$def]['WAREHOUSE_ID'] = $row['WAREHOUSE_ID'];
            $def = $def + 1;
            self::WHLocationListParentModel($row['LOCATION_ID'], $warehouseId, $space . '-', $def);
        }
        return self::$whLocation;
    }

    public function getActiveWHLocationParentListModel() {
        $sql = '';
        if (Input::post('LOCATION_ID') != null) {
            $sql = ' AND PARENT_ID=' . Input::post('LOCATION_ID');
        } else {
            $sql = ' AND PARENT_ID IS NULL';
        }
        $result = $this->db->GetAll("

                                    SELECT 
                                        * 
                                    FROM  
                                        WH_LOCATION 
                                    WHERE 
                                        WAREHOUSE_ID=" . Input::post('WAREHOUSE_ID') . $sql);
        if (!empty($result)) {
            return $result;
        }
        return null;
    }

    private function recursiveLocationListModel($locationId) {
        $rLocation = array();

        $result = $this->db->GetAll("
                                    SELECT 
                                        * 
                                    FROM 
                                        WH_LOCATION 
                                    WHERE 
                                        PARENT_ID=" . $locationId . "
                                    ");
        if (!empty($result)) {
            foreach ($result as $key => $value) {
                array_push($rLocation, array('LOCATION_ID' => $value['LOCATION_ID'], 'LOCATION_CODE' => $value['LOCATION_CODE'], 'LOCATION_NAME' => $value['LOCATION_NAME'], 'PARENT_ID' => $locationId));
                $this->recursiveLocationListModel($value['LOCATION_ID']);
            }
        }

        return $rLocation;
    }

    public function getActiveWHLocationImageModel() {
        $locid = 0;
        if (Input::post('LOCATION_ID')) {
            $locid = Input::post('LOCATION_ID');
        } else {
            $locid = Input::post('WAREHOUSE_ID');
        }
        $result = $this->db->GetRow("
                                    SELECT 
                                        FA.ATTACH_ID, FA.ATTACH_NAME, FA.ATTACH 
                                    FROM 
                                        META_VALUE_PHOTO MVP
                                    INNER JOIN FILE_ATTACH FA ON MVP.ATTACH_ID=FA.ATTACH_ID
                                    WHERE 
                                        MVP.IS_MAIN=1 
                                            AND
                                        MVP.META_VALUE_ID=" . $locid . "
                                    ");
        if (!empty($result)) {
            return $result;
        }
        return array('ATTACH_ID' => null, 'ATTACH_NAME' => null, 'ATTACH' => null);
    }

    public function getOneLocationModel() {
        $locationId = Input::post('LOCATION_ID');
        if ($locationId != '') {

            $result = $this->db->GetRow("
                                        SELECT 
                                            WL.LOCATION_ID, WL.LOCATION_NAME, WLT.LOCATION_TYPE_NAME 
                                        FROM 
                                            WH_LOCATION WL
                                        INNER JOIN WH_LOCATION_TYPE WLT ON WL.LOCATION_TYPE_ID=WLT.LOCATION_TYPE_ID
                                        WHERE 
                                            WL.LOCATION_ID=" . $locationId . " 
                                                AND 
                                            WL.IS_ACTIVE=1");

            return $result;
        }
        return null;
    }

    public function updateLocationPositionModel() {
        $data = array(
            'LOCATION_ID' => Input::post('LOCATION_ID'),
            'COORDINATE_X' => Input::post('COORDINATE_X'),
            'COORDINATE_Y' => Input::post('COORDINATE_Y')
        );
        $where = "LOCATION_ID = " . Input::post('OLD_LOCATION_ID') . " AND COORDINATE_X=" . Input::post('OLD_COORDINATE_X') . " AND COORDINATE_Y=" . Input::post('OLD_COORDINATE_Y');
        $result = $this->db->AutoExecute('WH_LOCATION_POSITION', $data, 'UPDATE', $where);
        if ($result) {
            return array('status' => 'success');
        }

        return array('status' => 'error', 'message' => 'Алдаа гарлаа!');
    }

    public function createLocationPositionModel() {

        $wareHouseId = '';
        if (Input::post('ISWAREHOUSE') == 'YES') {
            $wareHouseId = Input::post('WAREHOUSE_ID');
        } else {
            $wareHouseId = null;
        }
        $data = array(
            'WAREHOUSE_ID' => $wareHouseId,
            'LOCATION_ID' => Input::post('LOCATION_ID'),
            'COORDINATE_X' => Input::post('COORDINATE_X'),
            'COORDINATE_Y' => Input::post('COORDINATE_Y'),
            'IS_ACTIVE' => 1,
            'CREATED_DATE' => Date::currentDate(),
            'CREATED_USER_ID' => Ue::sessionUserId(),
            'MARKER_NAME' => Input::post('MARKER_NAME')
        );
        $result = $this->db->AutoExecute('WH_LOCATION_POSITION', $data);
        if ($result) {
            return array('status' => 'success');
        }

        return array('status' => 'error', 'message' => 'Алдаа гарлаа!');
    }

    public function getLocationPositionModel() {

        $sql = '';
        //echo 'WAREHOUSE_ID=' . Input::post('WAREHOUSE_ID') . ' - LOCATION_ID=' . Input::post('LOCATION_ID');
        if (Input::post('WAREHOUSE_ID') != '' and Input::post('LOCATION_ID') != '') {
            $sql = "
                SELECT 
                    WLP.*
                FROM 
                    WH_LOCATION WL
                INNER JOIN WH_LOCATION_POSITION WLP ON WL.LOCATION_ID=WLP.LOCATION_ID
                WHERE
                    WL.PARENT_ID=" . Input::post('LOCATION_ID');
        } elseif (Input::post('WAREHOUSE_ID')) {
            $sql = "
                SELECT 
                    WLP.* 
                FROM 
                    WH_LOCATION WL
                INNER JOIN WH_LOCATION_POSITION WLP ON WL.LOCATION_ID=WLP.LOCATION_ID
                WHERE 
                    WL.WAREHOUSE_ID=" . Input::post('WAREHOUSE_ID') . " 
                        AND 
                    WL.PARENT_ID IS NULL
                        AND 
                    WLP.IS_ACTIVE=1
                        AND
                    WL.IS_ACTIVE=1
                        AND 
                    WLP.WAREHOUSE_ID IS NULL";
        }
        $result = $this->db->GetAll($sql);

        $temp = array();
        foreach ($result as $key => $row) {
            $isParent = 0;
            $parent = $this->db->GetAll("
                            SELECT 
                                WL.*
                            FROM 
                                WH_LOCATION WL
                            WHERE
                                WL.PARENT_ID=" . $row['LOCATION_ID']);
            if (!empty($parent)) {
                $isParent = 1;
            }
            array_push($temp, array('WAREHOUSE_ID' => $row['WAREHOUSE_ID'], 'LOCATION_ID' => $row['LOCATION_ID'], 'COORDINATE_X' => $row['COORDINATE_X'], 'COORDINATE_Y' => $row['COORDINATE_Y'], 'IS_ACTIVE' => $row['IS_ACTIVE'], 'CREATED_DATE' => $row['CREATED_DATE'], 'CREATED_USER_ID' => $row['CREATED_USER_ID'], 'MARKER_NAME' => $row['MARKER_NAME'], 'PARENT_ID' => $row['LOCATION_ID'], 'IS_PARENT' => $isParent));
        }
        if (!empty($temp)) {
            return $temp;
        }
        return array();
    }

    public function getParentLocationIdModel() {

        $sql = '';
        //echo 'WAREHOUSE_ID=' . Input::post('WAREHOUSE_ID') . ' - LOCATION_ID=' . Input::post('LOCATION_ID');
        if (Input::post('LOCATION_ID') != '') {
            $sql = "
                SELECT 
                    WL.PARENT_ID
                FROM 
                    WH_LOCATION WL
                WHERE
                    WL.location_ID=" . Input::post('LOCATION_ID');
        }
        $result = $this->db->GetRow($sql);

        if (!empty($result)) {
            return $result;
        }
        return array('PARENT_ID' => 0);
    }

    public function removeLocationPositionModel() {

        if (Input::post('WAREHOUSE_ID') != 0 AND Input::post('LOCATION_ID') == 0) {
            $sql = "WLP.WAREHOUSE_ID=" . Input::post('WAREHOUSE_ID') . " AND ";
        } else {
            $sql = "WLP.LOCATION_ID=" . Input::post('LOCATION_ID') . " AND ";
        }
        $row = $this->db->GetRow("
                    SELECT 
                        WL.LOCATION_ID 
                    FROM 
                        WH_LOCATION_POSITION WLP
                    INNER JOIN WH_LOCATION WL ON WLP.LOCATION_ID=WL.LOCATION_ID
                    WHERE 
                        " . $sql . "
                        WLP.COORDINATE_X=" . Input::post('COORDINATE_X') . "
                          AND 
                        WLP.COORDINATE_Y=" . Input::post('COORDINATE_Y') . "
                          AND 
                        WLP.IS_ACTIVE=1");
        $result = count($row);
        $result = 0;
        if (!empty($result)) {
            $result = $this->db->GetAll("
                                        SELECT
                                            * 
                                        FROM
                                            WH_LOCATION WL
                                        INNER JOIN WH_LOCATION_POSITION WLP ON WL.LOCATION_ID=WLP.LOCATION_ID 
                                        WHERE 
                                            WL.PARENT_ID=" . $row['LOCATION_ID'] . "
                                              AND 
                                            WLP.IS_ACTIVE=1
                                              AND
                                            WL.IS_ACTIVE=1");

            $result = count($result);
            if (empty($result)) {
                $result = $this->db->Execute("
                                        DELETE 
                                            FROM WH_LOCATION_POSITION 
                                        WHERE 
                                            LOCATION_ID=" . $row['LOCATION_ID'] . "
                                                AND
                                            COORDINATE_X=" . Input::post('COORDINATE_X') . "
                                                AND
                                            COORDINATE_Y=" . Input::post('COORDINATE_Y') . "
                                        ");
                if ($result) {
                    return array('status' => 'success');
                }
                return array('status' => 'error', 'message' => 'Алдаа гарлаа!');
            } else {
                return array('status' => 'empty', 'message' => 'Устгах боломжгүй цэг байна');
            }
        } else {
            return array('status' => 'empty', 'message' => 'Алдаа гарлаа!');
        }
    }

    public function getLastLocationIdModel() {
        $result = $this->db->GetRow("
                                    SELECT
                                        * 
                                    FROM
                                        WH_LOCATION WL
                                    WHERE 
                                        WL.PARENT_ID=" . Input::post('LOCATION_ID') . "
                                          AND 
                                        WL.IS_ACTIVE=1");
        $result = count($result);
        if (empty($result)) {
            return $this->db->GetRow("
                                    SELECT
                                        WL.PARENT_ID 
                                    FROM
                                        WH_LOCATION WL
                                    WHERE 
                                        WL.LOCATION_ID=" . Input::post('LOCATION_ID') . "
                                          AND 
                                        WL.IS_ACTIVE=1");
        }
        return array('PARENT_ID' => 0);
    }

    public function getMarkerListModel() {
        return array(
            array('MARKER_NAME' => '_hs-marker-object-blue', 'MARKER_IMAGE' => '<img src="assets/custom/addon/plugins/marker/hotspotCustom/img/marker_blue.png">', 'DESCRIPTION' => 'Хөх'),
            array('MARKER_NAME' => '_hs-marker-object-red', 'MARKER_IMAGE' => '<img src="assets/custom/addon/plugins/marker/hotspotCustom/img/marker_red.png">', 'DESCRIPTION' => 'Улаан'),
            array('MARKER_NAME' => '_hs-marker-object-pink', 'MARKER_IMAGE' => '<img src="assets/custom/addon/plugins/marker/hotspotCustom/img/marker_pink.png">', 'DESCRIPTION' => 'Ягаан'),
            array('MARKER_NAME' => '_hs-marker-object-green', 'MARKER_IMAGE' => '<img src="assets/custom/addon/plugins/marker/hotspotCustom/img/marker_green.png">', 'DESCRIPTION' => 'Ногоон'),
            array('MARKER_NAME' => '_hs-marker-object-brown', 'MARKER_IMAGE' => '<img src="assets/custom/addon/plugins/marker/hotspotCustom/img/marker_brown.png">', 'DESCRIPTION' => 'Бор'),
            array('MARKER_NAME' => '_hs-marker-object-white', 'MARKER_IMAGE' => '<img src="assets/custom/addon/plugins/marker/hotspotCustom/img/marker_white.png">', 'DESCRIPTION' => 'Цагаан'),
            array('MARKER_NAME' => '_hs-marker-object-borderBlack-blue', 'MARKER_IMAGE' => '<img src="assets/custom/addon/plugins/marker/hotspotCustom/img/marker_borderBlack_blue.png">', 'DESCRIPTION' => 'Хүрээтэй хөх'),
            array('MARKER_NAME' => '_hs-marker-object-borderBlack-red', 'MARKER_IMAGE' => '<img src="assets/custom/addon/plugins/marker/hotspotCustom/img/marker_borderBlack_red.png">', 'DESCRIPTION' => 'Хүрээтэй улаан'),
            array('MARKER_NAME' => '_hs-marker-object-borderBlack-pink', 'MARKER_IMAGE' => '<img src="assets/custom/addon/plugins/marker/hotspotCustom/img/marker_borderBlack_pink.png">', 'DESCRIPTION' => 'Хүрээтэй ягаан'),
            array('MARKER_NAME' => '_hs-marker-object-borderBlack-green', 'MARKER_IMAGE' => '<img src="assets/custom/addon/plugins/marker/hotspotCustom/img/marker_borderBlack_green.png">', 'DESCRIPTION' => 'Хүрээтэй ногоон'),
            array('MARKER_NAME' => '_hs-marker-object-borderBlack-brown', 'MARKER_IMAGE' => '<img src="assets/custom/addon/plugins/marker/hotspotCustom/img/marker_borderBlack_brown.png">', 'DESCRIPTION' => 'Хүрээтэй бор'),
            array('MARKER_NAME' => '_hs-marker-object-borderBlack-white', 'MARKER_IMAGE' => '<img src="assets/custom/addon/plugins/marker/hotspotCustom/img/marker_borderBlack_white.png">', 'DESCRIPTION' => 'Хүрээтэй цагаан'),
        );
    }

}
